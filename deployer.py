#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Scraper4 standalone multi-branch deployer.

This file lives NEXT TO scraper4.py (the main site code) and is a fully
independent deployer. It checks every configured GitHub branch for the
newest APP_VERSION of scraper4.py, then atomically installs that version
next to itself with a .bak backup.

It never updates or replaces itself — only the target file (scraper4.py).
Nothing but the Python standard library is required.

Usage
-----
    python3 deployer.py                  # auto loop: check + update, default every 300s
    python3 deployer.py --once           # single check + update, then exit (cron friendly)
    python3 deployer.py --check          # report only; nothing is written
    python3 deployer.py --rollback       # restore target from target.bak
    python3 deployer.py --branches dev,main,arena/01a0640f-amphp

Configuration (CLI flags override env vars, env vars override defaults):
    DEPLOYER_REPO          repository as owner/repo          (default fazilatma/amphp)
    DEPLOYER_BRANCHES      comma separated branch list        (default arena/01a0640f-amphp)
    DEPLOYER_PATH          file inside the repository         (default scraper4.py)
    DEPLOYER_TARGET        local file to update (default: scraper4.py next to this file)
    DEPLOYER_GITHUB_TOKEN  private repository token (optional)
    DEPLOYER_RELOAD_FILE   WSGI file to touch after an update (PythonAnywhere)
    DEPLOYER_INTERVAL      seconds between auto checks (min 30, default 300)
    DEPLOYER_LOG_FILE      append logs here (optional)
    DEPLOYER_GITHUB_BASE   GitHub API base (default https://api.github.com)

In auto mode the deployer checks immediately, then repeats every interval.
Use --once (e.g. from a PythonAnywhere scheduled task) or --check only to
decide manually. A failed branch never blocks the others; the newest valid
version wins, and the installed version is never downgraded.
"""

from __future__ import annotations

import argparse
import base64
import hashlib
import json
import logging
import os
import re
import signal
import sys
import tempfile
import time
import urllib.error
import urllib.parse
import urllib.request

DEFAULT_REPO = "fazilatma/amphp"
DEFAULT_BRANCHES = ["arena/01a0640f-amphp"]
DEFAULT_PATH = "scraper4.py"
DEFAULT_INTERVAL = 300
MIN_INTERVAL = 30
CONNECT_TIMEOUT = 30
MAX_CONTENT_BYTES = 2 * 1024 * 1024
REQUIRED_MARKERS = ("APP_VERSION", "Flask(", '@app.get("/")', "/api/deploy/run")
VERSION_RE = re.compile(r'^APP_VERSION\s*=\s*["\']([^"\']+)', re.MULTILINE)
PERSIAN_DIGITS = str.maketrans("۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩", "01234567890123456789")

log = logging.getLogger("scraper4-deployer")


class DeployerError(Exception):
    """Raised for any expected failure; the message is user facing."""


# ---------------------------------------------------------------------------
# Small helpers
# ---------------------------------------------------------------------------

def clean_text(value: object) -> str:
    if value is None:
        return ""
    if isinstance(value, (dict, list)):
        return ""
    return re.sub(r"\s+", " ", str(value).translate(PERSIAN_DIGITS)).strip()


def blob_sha(content: bytes) -> str:
    return hashlib.sha1(b"blob " + str(len(content)).encode("ascii") + b"\0" + content).hexdigest()


def source_version(content: bytes) -> str:
    """Extract APP_VERSION from source bytes; 'unknown' when missing."""
    try:
        text = content.decode("utf-8")
    except UnicodeDecodeError:
        return "unknown"
    match = VERSION_RE.search(text)
    return match.group(1) if match else "unknown"


def version_key(value: str) -> tuple[int, ...]:
    """Comparable key for versions like 4.5.0 or 4.5.0-rc1."""
    text = clean_text(value).lower()
    match = re.match(r"^v?(\d+(?:\.\d+){0,3})", text)
    if match:
        numbers = [int(part) for part in match.group(1).split(".")]
        suffix = text[match.end():]
    else:
        numbers = [0]
        suffix = text
    numbers = (numbers + [0, 0, 0, 0])[:4]
    # A bare release outranks its own pre-release (e.g. 4.5.0 > 4.5.0-rc1).
    suffix_rank = 0 if suffix else 1
    return tuple(numbers) + (suffix_rank,)


def validate_source(content: bytes) -> str:
    """Validate downloaded Scraper4 source and return its APP_VERSION."""
    if not content.lstrip().startswith((b"#", b'"""', b"'''")):
        raise DeployerError("فایل دانلودشده شبیه کد Python نیست")
    try:
        text = content.decode("utf-8")
    except UnicodeDecodeError as exc:
        raise DeployerError("فایل دانلودشده UTF-8 نیست") from exc
    try:
        compile(text, "scraper4-update.py", "exec")
    except SyntaxError as exc:
        raise DeployerError(f"نسخه تازه خطای syntax دارد: خط {exc.lineno}: {exc.msg}") from exc
    missing = [marker for marker in REQUIRED_MARKERS if marker not in text]
    if missing:
        raise DeployerError("نسخه تازه اعتبارسنجی نشد؛ نشانه‌های Scraper4 کامل نیست")
    return source_version(content)


def atomic_write(path: str, content: bytes, mode: int = 0o600) -> None:
    directory = os.path.dirname(path) or "."
    os.makedirs(directory, exist_ok=True)
    fd, temporary = tempfile.mkstemp(prefix=".deployer-", suffix=".tmp", dir=directory)
    try:
        with os.fdopen(fd, "wb") as fh:
            fh.write(content)
            fh.flush()
            os.fsync(fh.fileno())
        os.chmod(temporary, mode)
        os.replace(temporary, path)
    finally:
        if os.path.exists(temporary):
            os.unlink(temporary)


# ---------------------------------------------------------------------------
# Configuration
# ---------------------------------------------------------------------------

def default_target() -> str:
    return os.path.join(os.path.dirname(os.path.abspath(__file__)), DEFAULT_PATH)


def load_config(args: argparse.Namespace) -> dict:
    def env(name: str, default: str = "") -> str:
        return os.environ.get(name, default).strip()

    branches_raw = env("DEPLOYER_BRANCHES")
    branches = ([clean_text(x) for x in branches_raw.split(",") if clean_text(x)]
                if branches_raw else list(DEFAULT_BRANCHES))
    env_target = env("DEPLOYER_TARGET")
    target = os.path.abspath(os.path.expanduser(env_target)) if env_target else os.path.abspath(default_target())
    cfg = {
        "repo": env("DEPLOYER_REPO", DEFAULT_REPO) or DEFAULT_REPO,
        "branches": branches,
        "path": env("DEPLOYER_PATH", DEFAULT_PATH) or DEFAULT_PATH,
        "target": target,
        "token": env("DEPLOYER_GITHUB_TOKEN"),
        "reload_file": os.path.expanduser(env("DEPLOYER_RELOAD_FILE")),
        "interval": max(MIN_INTERVAL, int(env("DEPLOYER_INTERVAL", str(DEFAULT_INTERVAL)) or DEFAULT_INTERVAL)),
        "log_file": env("DEPLOYER_LOG_FILE"),
        "github_base": env("DEPLOYER_GITHUB_BASE", "https://api.github.com").rstrip("/"),
    }
    # CLI overrides
    if args.repo:
        cfg["repo"] = args.repo
    if args.branches:
        cfg["branches"] = [clean_text(x) for x in args.branches.split(",") if clean_text(x)]
    if args.path:
        cfg["path"] = args.path
    if args.target:
        cfg["target"] = os.path.abspath(os.path.expanduser(args.target))
    if args.token:
        cfg["token"] = args.token
    if args.reload_file is not None:
        cfg["reload_file"] = os.path.expanduser(args.reload_file)
    if args.interval:
        cfg["interval"] = max(MIN_INTERVAL, int(args.interval))
    if args.log_file:
        cfg["log_file"] = args.log_file
    if args.github_base:
        cfg["github_base"] = args.github_base.rstrip("/")
    if not cfg["branches"]:
        raise DeployerError("هیچ برنچی پیکربندی نشده است")
    if not re.fullmatch(r"[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+", cfg["repo"]):
        raise DeployerError("نام repository باید به صورت owner/repo باشد")
    return cfg


# ---------------------------------------------------------------------------
# GitHub access
# ---------------------------------------------------------------------------

def fetch_file(cfg: dict, branch: str) -> dict:
    """Fetch one file from a branch; returns sha/size/content/html_url."""
    url = (
        f"{cfg['github_base']}/repos/{urllib.parse.quote(cfg['repo'], safe='/')}"
        f"/contents/{urllib.parse.quote(cfg['path'], safe='/')}"
        + "?ref=" + urllib.parse.quote(branch, safe="")
    )
    headers = {"User-Agent": "scraper4-standalone-deployer", "Accept": "application/vnd.github+json"}
    if cfg.get("token"):
        headers["Authorization"] = "Bearer " + cfg["token"]
    request = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(request, timeout=CONNECT_TIMEOUT) as response:
            payload = json.loads(response.read().decode("utf-8"))
    except urllib.error.HTTPError as exc:
        body = ""
        try:
            body = exc.read().decode("utf-8", "replace")[:250]
        except OSError:
            pass
        if exc.code == 401:
            raise DeployerError("توکن GitHub نامعتبر یا منقضی است (HTTP 401)") from exc
        if exc.code == 403:
            raise DeployerError("دسترسی GitHub رد شد یا محدودیت نرخ تمام شده است (HTTP 403)") from exc
        if exc.code == 404:
            raise DeployerError("برنچ یا فایل در GitHub پیدا نشد (HTTP 404)") from exc
        raise DeployerError(f"GitHub HTTP {exc.code}: {body}") from exc
    except urllib.error.URLError as exc:
        raise DeployerError(f"ارتباط با GitHub ناموفق بود: {exc.reason}") from exc
    except (ValueError, json.JSONDecodeError) as exc:
        raise DeployerError("پاسخ GitHub معتبر نیست") from exc
    if not isinstance(payload, dict) or payload.get("type") != "file":
        raise DeployerError("مسیر GitHub یک فایل نیست")
    size = int(payload.get("size") or 0)
    if size > MAX_CONTENT_BYTES:
        raise DeployerError("فایل به‌روزرسانی بزرگ‌تر از ۲ مگابایت است")
    encoded = str(payload.get("content", "")).replace("\n", "")
    try:
        content = base64.b64decode(encoded, validate=True)
    except (ValueError, TypeError) as exc:
        raise DeployerError("محتوای فایل از GitHub قابل خواندن نیست") from exc
    return {
        "branch": branch,
        "sha": str(payload.get("sha", "")),
        "size": size,
        "html_url": str(payload.get("html_url", "")),
        "content": content,
    }


def collect_candidates(cfg: dict) -> tuple[list[dict], list[dict]]:
    """Fetch the file from every branch; newest valid version comes first."""
    candidates: list[dict] = []
    errors: list[dict] = []
    for branch in cfg["branches"]:
        try:
            meta = fetch_file(cfg, branch)
            meta["version"] = validate_source(meta["content"])
            candidates.append(meta)
        except Exception as exc:  # keep going; a bad branch never blocks the rest
            errors.append({"branch": branch, "error": str(exc)[:300]})
    candidates.sort(key=lambda item: version_key(item["version"]), reverse=True)
    return candidates, errors


# ---------------------------------------------------------------------------
# Install / reload
# ---------------------------------------------------------------------------

def pythonanywhere_reload() -> bool:
    """Reload the PythonAnywhere web app when its local API token exists."""
    token_file = os.path.expanduser("~/.pythonanywhere_api_token")
    try:
        token = open(token_file, encoding="utf-8").read().strip()
        if not token:
            return False
        username = os.path.basename(os.path.expanduser("~"))
        domain = username.lower() + ".pythonanywhere.com"
        url = f"https://www.pythonanywhere.com/api/v0/user/{username}/webapps/{domain}/reload/"
        request = urllib.request.Request(url, data=b"", headers={"Authorization": "Token " + token})
        with urllib.request.urlopen(request, timeout=CONNECT_TIMEOUT) as response:
            return response.status in (200, 201)
    except Exception:
        return False


def apply_update(cfg: dict, best: dict) -> dict:
    """Install the newest candidate next to this file; never downgrade."""
    target = cfg["target"]
    if not os.path.isfile(target):
        raise DeployerError(f"فایل اصلی پیدا نشد: {target}")
    with open(target, "rb") as fh:
        current = fh.read()
    current_sha = blob_sha(current)
    current_version = source_version(current)

    if current_sha == best["sha"]:
        return {"changed": False, "reason": "same", "version": current_version, "branch": best["branch"]}
    if version_key(best["version"]) < version_key(current_version):
        return {"changed": False, "reason": "downgrade", "version": current_version, "branch": best["branch"]}

    mode = os.stat(target).st_mode & 0o777
    backup = target + ".bak"
    atomic_write(backup, current, mode)
    atomic_write(target, best["content"], mode)

    reloaded = False
    if cfg["reload_file"] and os.path.isfile(cfg["reload_file"]):
        try:
            os.utime(cfg["reload_file"], None)
            reloaded = True
        except OSError as exc:
            log.warning("خطای لمس فایل WSGI: %s", exc)
    if cfg["reload_file"] and not reloaded:
        log.warning("فایل WSGI برای reload پیدا نشد: %s", cfg["reload_file"])
    if pythonanywhere_reload():
        reloaded = True
    return {
        "changed": True, "reason": "updated", "version": best["version"],
        "branch": best["branch"], "sha": best["sha"], "backup": os.path.basename(backup),
        "reloaded": reloaded,
    }


def rollback(cfg: dict) -> dict:
    """Restore target from target.bak."""
    target = cfg["target"]
    backup = target + ".bak"
    if not os.path.isfile(backup):
        raise DeployerError(f"نسخه پشتیبان {backup} وجود ندارد")
    with open(backup, "rb") as fh:
        content = fh.read()
    version = validate_source(content)
    mode = os.stat(target).st_mode & 0o777
    atomic_write(target, content, mode)
    return {"changed": True, "version": version, "backup": os.path.basename(backup)}


# ---------------------------------------------------------------------------
# Report / run
# ---------------------------------------------------------------------------

def build_report(cfg: dict) -> dict:
    candidates, errors = collect_candidates(cfg)
    best = candidates[0] if candidates else None
    target = cfg["target"]
    current_version = "unknown"
    if os.path.isfile(target):
        with open(target, "rb") as fh:
            current_version = source_version(fh.read())
    rows = []
    total = len(candidates) + len(errors)
    for index, candidate in enumerate(candidates):
        newest = index == 0
        rows.append({"branch": candidate["branch"], "version": candidate["version"], "sha": candidate["sha"],
                     "size": candidate["size"], "newest": newest, "candidate": candidate})
    for error in errors:
        rows.append({"branch": error["branch"], "error": error["error"]})
    return {
        "ok": bool(candidates),
        "repo": cfg["repo"], "branches": cfg["branches"], "path": cfg["path"],
        "target": target, "current_version": current_version,
        "best": {"branch": best["branch"], "version": best["version"], "sha": best["sha"]} if best else None,
        "rows": rows, "total_checked": total,
    }


def format_report(cfg: dict, report: dict) -> str:
    lines = [
        f"فایل هدف: {report['target']}",
        f"فایل محلی: v{report['current_version']}",
    ]
    if report["best"]:
        lines.append(f"جدیدترین برنچ: {report['best']['branch']} — v{report['best']['version']}")
    lines.append("برنچ‌ها:")
    for row in report["rows"]:
        if "error" in row:
            lines.append(f"  - {row['branch']:<34} خطا: {row['error']}")
        else:
            mark = "جدیدترین" if row["newest"] else ""
            lines.append(f"  - {row['branch']:<34} v{row['version']}  {mark}")
    return "\n".join(lines)


def run_once(cfg: dict, install: bool = True) -> dict:
    report = build_report(cfg)
    if not report["best"]:
        detail = "؛ ".join(f"{row['branch']}: {row['error']}" for row in report["rows"] if "error" in row)
        raise DeployerError("هیچ برنچی در دسترس نیست — " + (detail or "هیچ برنچی پیکربندی نشده است"))
    best = report["best"]
    if not install:
        return {"report": report, "action": "check"}
    candidate = next(row["candidate"] for row in report["rows"] if row["newest"])
    result = apply_update(cfg, candidate)
    result["report"] = report
    return result


# ---------------------------------------------------------------------------
# CLI
# ---------------------------------------------------------------------------

def build_parser() -> argparse.ArgumentParser:
    parser = argparse.ArgumentParser(
        description="Scraper4 standalone multi-branch deployer (checks GitHub and updates scraper4.py).")
    parser.add_argument("--once", action="store_true", help="run a single check+update pass and exit (cron friendly)")
    parser.add_argument("--check", action="store_true", help="report latest versions only; do not write anything")
    parser.add_argument("--rollback", action="store_true", help="restore scrap4.py from scraper4.py.bak")
    parser.add_argument("--repo", help="repository as owner/repo")
    parser.add_argument("--branches", help="comma separated branch list")
    parser.add_argument("--path", help="file inside the repository")
    parser.add_argument("--target", help="local main file to update")
    parser.add_argument("--token", help="GitHub token (or DEPLOYER_GITHUB_TOKEN)")
    parser.add_argument("--reload-file", nargs="?", const="", help="WSGI file to touch after an update (bare flag or empty disables)")
    parser.add_argument("--interval", type=int, help="seconds between auto checks (min 30)")
    parser.add_argument("--log-file", help="append log lines to this file")
    parser.add_argument("--github-base", help="GitHub API base (default https://api.github.com)")
    parser.add_argument("-v", "--verbose", action="store_true", help="log every detail")
    return parser


def setup_logging(cfg: dict, verbose: bool) -> None:
    handlers: list[logging.Handler] = [logging.StreamHandler(sys.stdout)]
    if cfg["log_file"]:
        try:
            handlers.append(logging.FileHandler(cfg["log_file"], encoding="utf-8"))
        except OSError as exc:
            log.warning("باز شدن فایل لاگ ناموفق بود: %s", exc)
    logging.basicConfig(
        level=logging.DEBUG if verbose else logging.INFO,
        format="%(asctime)s %(levelname)s %(message)s",
        handlers=handlers,
    )


def loop_forever(cfg: dict) -> int:
    """Auto mode: check immediately, then repeat every interval."""
    log.info("deployer خودکار آغاز شد — برنچ‌ها: %s | هر %s ثانیه", ", ".join(cfg["branches"]), cfg["interval"])
    stopping = False

    def stop(_signum, _frame):
        nonlocal stopping
        stopping = True

    signal.signal(signal.SIGTERM, stop)
    signal.signal(signal.SIGINT, stop)

    while not stopping:
        started = time.time()
        try:
            result = run_once(cfg, install=True)
            if result["changed"]:
                log.info("نصب شد: %s از برنچ %s (v%s) — reload: %s",
                         result["version"], result["branch"], result["version"], result["reloaded"])
                print(format_report(cfg, result["report"]))
            else:
                log.info("بررسی انجام شد — تغییری لازم نیست (v%s / بهترین: %s v%s)",
                         result["report"]["current_version"],
                         result["report"]["best"]["branch"], result["report"]["best"]["version"])
        except Exception as exc:
            log.error("بررسی به‌روزرسانی ناموفق بود: %s", exc)
        elapsed = time.time() - started
        remaining = max(1, cfg["interval"] - elapsed)
        for _ in range(int(remaining)):
            if stopping:
                break
            time.sleep(1)
    log.info("deployer متوقف شد")
    return 0


def main(argv: list[str] | None = None) -> int:
    args = build_parser().parse_args(argv)
    try:
        cfg = load_config(args)
    except DeployerError as exc:
        print(f"خطا: {exc}", file=sys.stderr)
        return 2
    setup_logging(cfg, args.verbose)

    try:
        if args.rollback:
            result = rollback(cfg)
            log.info("بازگشت انجام شد: v%s از %s", result["version"], result["backup"])
            return 0
        try:
            result = run_once(cfg, install=not args.check)
        except DeployerError as exc:
            log.error("%s", exc)
            return 1
        if args.check:
            print(format_report(cfg, result["report"]))
            return 0
        if not args.once:
            return loop_forever(cfg)
        if result["changed"]:
            print(format_report(cfg, result["report"]))
        log.info("نتیجه: %s", result.get("reason", "check"))
        if result["changed"]:
            log.info("نسخه %s از برنچ %s نصب شد — reload: %s",
                     result["version"], result["branch"], result.get("reloaded", False))
        else:
            log.info("تغییری لازم نیست — نصب فعلی v%s", result["report"]["current_version"])
        return 0
    except DeployerError as exc:
        log.error("%s", exc)
        return 1
    except KeyboardInterrupt:
        log.info("متوقف شد")
        return 130


if __name__ == "__main__":
    sys.exit(main())
