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
    python3 deployer.py --web            # local web UI on http://0.0.0.0:8787/deployer/

Web UI on PythonAnywhere
------------------------
The WSGI callable `wsgi_application` serves a status/install page at the
`/deployer/` path and can be mounted on your existing web app without a
second web app (PythonAnywhere free plans allow one). Run
`bash scripts/install_deployer_webapp.sh` in the PythonAnywhere console;
it copies this file to ~/.deployer, writes a WSGI wrapper and patches the
main WSGI file. The page is then at:

    https://<username>.pythonanywhere.com/deployer/

Set DEPLOYER_WEB_TOKEN for install/rollback actions (read-only otherwise).

Configuration (CLI flags override env vars, env vars override defaults):
    DEPLOYER_REPO          repository as owner/repo          (default fazilatma/amphp)
    DEPLOYER_ALL_BRANCHES  search EVERY branch of the repo   (default 1/true; '0' disables)
    DEPLOYER_BRANCHES      explicit comma separated branch list (overrides all-branches)
    DEPLOYER_PATH          file inside the repository         (default scraper4.py)
    DEPLOYER_TARGET        local file to update (default: scraper4.py next to this file)
    DEPLOYER_GITHUB_TOKEN  private repository token (optional)
    DEPLOYER_RELOAD_FILE   WSGI file to touch after an update (PythonAnywhere)
    DEPLOYER_INTERVAL      seconds between auto checks (min 30, default 300)
    DEPLOYER_LOG_FILE      append logs here (optional)
    DEPLOYER_GITHUB_BASE   GitHub API base (default https://api.github.com)

In ALL-BRANCHES mode (the default) the deployer lists every branch of the
repository through the GitHub API, downloads scraper4.py from each of them
in parallel, compares APP_VERSION values and installs the newest. Set
DEPLOYER_BRANCHES (or pass --branches) to limit the check to specific
branches. A failed branch never blocks the others; the newest valid
version wins, and the installed version is never downgraded.
"""

from __future__ import annotations

import argparse
import base64
import hashlib
import hmac
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
from concurrent.futures import ThreadPoolExecutor

DEPLOYER_VERSION = "1.2.0"
DEFAULT_REPO = "fazilatma/amphp"
DEFAULT_BRANCHES = ["arena/01a0640f-amphp"]
DEFAULT_PATH = "scraper4.py"
DEFAULT_INTERVAL = 300
MIN_INTERVAL = 30
CONNECT_TIMEOUT = 30
MAX_CONTENT_BYTES = 2 * 1024 * 1024
MAX_BRANCHES = 500
BRANCH_WORKERS = 8
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
    all_raw = env("DEPLOYER_ALL_BRANCHES", "1").lower()
    all_branches = all_raw not in {"0", "false", "off", "no", ""}
    if branches_raw:
        branches = [clean_text(x) for x in branches_raw.split(",") if clean_text(x)]
        all_branches = False  # explicit list wins
    elif all_branches:
        branches = []  # resolved from the GitHub branch list at run time
    else:
        branches = list(DEFAULT_BRANCHES)
    env_target = env("DEPLOYER_TARGET")
    target = os.path.abspath(os.path.expanduser(env_target)) if env_target else os.path.abspath(default_target())
    cfg = {
        "repo": env("DEPLOYER_REPO", DEFAULT_REPO) or DEFAULT_REPO,
        "branches": branches,
        "all_branches": all_branches,
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
        cfg["all_branches"] = False
    if args.all_branches is not None:
        cfg["all_branches"] = args.all_branches
        if cfg["all_branches"] and not args.branches:
            cfg["branches"] = []
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
    if not cfg["branches"] and not cfg["all_branches"]:
        raise DeployerError("هیچ برنچی پیکربندی نشده است")
    if not re.fullmatch(r"[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+", cfg["repo"]):
        raise DeployerError("نام repository باید به صورت owner/repo باشد")
    return cfg


# ---------------------------------------------------------------------------
# GitHub access
# ---------------------------------------------------------------------------

def _github_request(url: str, cfg: dict, not_found_message: str = "برنچ یا فایل در GitHub پیدا نشد (HTTP 404)"):
    """GET a GitHub JSON endpoint with unified error handling."""
    headers = {"User-Agent": "scraper4-standalone-deployer", "Accept": "application/vnd.github+json"}
    if cfg.get("token"):
        headers["Authorization"] = "Bearer " + cfg["token"]
    request = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(request, timeout=CONNECT_TIMEOUT) as response:
            raw = response.read().decode("utf-8")
            return json.loads(raw), response
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
            raise DeployerError(not_found_message) from exc
        raise DeployerError(f"GitHub HTTP {exc.code}: {body}") from exc
    except urllib.error.URLError as exc:
        raise DeployerError(f"ارتباط با GitHub ناموفق بود: {exc.reason}") from exc
    except (ValueError, json.JSONDecodeError) as exc:
        raise DeployerError("پاسخ GitHub معتبر نیست") from exc


def fetch_branches(cfg: dict, limit: int = MAX_BRANCHES) -> list[str]:
    """List every branch of the repository (paginated), deduplicated."""
    branches: list[str] = []
    page = 1
    seen: set[str] = set()
    while page <= 20 and len(branches) < limit:
        url = (
            f"{cfg['github_base']}/repos/{urllib.parse.quote(cfg['repo'], safe='/')}/branches"
            f"?per_page=100&page={page}"
        )
        payload, _ = _github_request(url, cfg, "repository یا API برنچ‌ها در GitHub پیدا نشد (HTTP 404)")
        if not isinstance(payload, list):
            raise DeployerError("پاسخ فهرست برنچ‌های GitHub معتبر نیست")
        for item in payload:
            if isinstance(item, dict):
                name = clean_text(item.get("name"))
                if name and name not in seen:
                    seen.add(name)
                    branches.append(name)
        if len(payload) < 100:
            break
        page += 1
    if not branches:
        raise DeployerError("فهرست برنچ‌های repository از GitHub خالی دریافت شد")
    return branches[:limit]


def fetch_file(cfg: dict, branch: str) -> dict:
    """Fetch one file from a branch; returns sha/size/content/html_url."""
    url = (
        f"{cfg['github_base']}/repos/{urllib.parse.quote(cfg['repo'], safe='/')}"
        f"/contents/{urllib.parse.quote(cfg['path'], safe='/')}"
        + "?ref=" + urllib.parse.quote(branch, safe="")
    )
    payload, _ = _github_request(url, cfg)
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


def resolve_branches(cfg: dict) -> list[str]:
    """Return the branch list to scan: explicit config or every repo branch."""
    branches = list(cfg.get("branches") or [])
    if cfg.get("all_branches") and not branches:
        branches = fetch_branches(cfg)
        cfg["branches"] = branches
        cfg["branches_resolved"] = True
    seen: set[str] = set()
    unique = []
    for branch in branches:
        branch = clean_text(branch)
        if branch and branch not in seen:
            seen.add(branch)
            unique.append(branch)
    if not unique:
        raise DeployerError("هیچ برنچی برای بررسی در دسترس نیست")
    return unique


def collect_candidates(cfg: dict) -> tuple[list[dict], list[dict], list[str]]:
    """Fetch the file from every branch (in parallel); newest valid version first."""
    branches = resolve_branches(cfg)
    candidates: list[dict] = []
    errors: list[dict] = []

    def fetch_one(branch: str):
        try:
            meta = fetch_file(cfg, branch)
            meta["version"] = validate_source(meta["content"])
            return branch, meta, None
        except Exception as exc:  # keep going; a bad branch never blocks the rest
            return branch, None, str(exc)[:300]

    with ThreadPoolExecutor(max_workers=BRANCH_WORKERS) as pool:
        for branch, meta, error in pool.map(fetch_one, branches):
            if error:
                errors.append({"branch": branch, "error": error})
            else:
                candidates.append(meta)
    candidates.sort(key=lambda item: version_key(item["version"]), reverse=True)
    return candidates, errors, branches


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
    candidates, errors, branches = collect_candidates(cfg)
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
        "repo": cfg["repo"], "branches": branches, "all_branches": bool(cfg.get("all_branches")),
        "path": cfg["path"], "target": target, "current_version": current_version,
        "best": {"branch": best["branch"], "version": best["version"], "sha": best["sha"]} if best else None,
        "rows": rows, "total_checked": total, "found_with_file": len(candidates),
    }


def format_report(cfg: dict, report: dict) -> str:
    source = "همه برنچ‌های ریپو (خودکار از GitHub)" if report.get("all_branches") else "برنچ‌های پیکربندی‌شده"
    lines = [
        f"فایل هدف: {report['target']}",
        f"فایل محلی: v{report['current_version']}",
        f"منبع برنچ‌ها: {source}",
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
# Web UI (optional): /deployer page on an existing PythonAnywhere web app,
# or a local server via `deployer.py --web`. Standard library only.
# ---------------------------------------------------------------------------

WEB_PAGE = r'''<!doctype html>
<html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>دیپلوی‌ر اسکرپر۴</title><style>
:root{--bg:#07111f;--card:#0d1c31;--line:#213650;--text:#eaf3ff;--muted:#9db0ca;--blue:#38bdf8;--green:#34d399;--red:#fb7185;--amber:#fbbf24}
*{box-sizing:border-box}body{margin:0;min-height:100vh;background:radial-gradient(1200px 500px at 90% -10%,#16294f33,transparent),linear-gradient(155deg,#07111f,#0a1830);color:var(--text);font-family:Tahoma,"Segoe UI",Arial,sans-serif;font-size:14px;line-height:1.7}
.wrap{max-width:920px;margin:auto;padding:34px 20px 80px}h1{font-size:24px;margin:0 0 4px}h1 small{color:var(--muted);font-size:13px;font-weight:500}.sub{color:var(--muted);margin-bottom:20px}
.card{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:18px;margin-bottom:14px;box-shadow:0 14px 40px #00000055}
.row{display:flex;gap:10px;flex-wrap:wrap;align-items:center}.row>div{flex:1;min-width:150px}label{display:block;color:#b9c8dc;font-size:12px;font-weight:700;margin-bottom:5px}
input{width:100%;background:#051326;border:1px solid var(--line);border-radius:10px;color:var(--text);padding:10px 12px;font-family:inherit;font-size:14px}
button{cursor:pointer;border-radius:10px;border:1px solid #2c4a6d;background:linear-gradient(135deg,#075985,#1d4ed8);color:#fff;font-family:inherit;font-weight:700;font-size:14px;padding:10px 18px}
button.gray{background:#17263d;border-color:#33465f}button.green{background:linear-gradient(135deg,#047857,#065f46);border-color:#34d399}
button:disabled{opacity:.55;cursor:wait}.actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:16px}
.status{white-space:pre-wrap;line-height:1.9;color:#b9cae0;border-right:3px solid var(--blue);padding-right:12px;min-height:40px}
#branches{max-height:340px;overflow:auto;margin-top:4px}
.ok{color:var(--green)}.err{color:var(--red)}.warn{color:var(--amber)}code{direction:ltr;display:inline-block;color:#a5e4ff;background:#061426;border-radius:6px;padding:1px 6px}
.branch{display:flex;flex-wrap:wrap;gap:10px;align-items:center;padding:10px 12px;border:1px solid var(--line);border-radius:11px;background:#081528;margin-top:7px;font-size:13px}
.branch.best{border-color:#34d39955;background:#052e2244}.branch .tag{font-weight:800;font-size:11px;padding:3px 8px;border-radius:99px;background:#11324a;color:#8ed0ff}
.branch.best .tag{background:#064e3b;color:#6ee7b7}.branch small{color:var(--muted)}.err-row{border-color:#fb718544;color:#fda4af}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin:4px 0 14px}.stat{padding:12px;border:1px solid var(--line);border-radius:12px;background:#0a1a2e;text-align:center}.stat b{display:block;font-size:19px}.stat span{color:var(--muted);font-size:12px}
note{display:block;padding:10px 12px;border:1px solid #fbbf2444;background:#42200633;border-radius:10px;color:#fde68a;margin-bottom:12px}
@media(max-width:640px){.stats{grid-template-columns:1fr 1fr}}
</style></head><body><div class="wrap">
<h1>⚙️ دیپلوی‌ر مستقل اسکرپر۴ <small>جستجوی همه برنچ‌های ریپو و نصب جدیدترین نسخه</small></h1>
<div class="sub">فهرست‌کشی خودکار برنچ‌ها از GitHub → مقایسه نسخه‌ها → نصب اتمیک کنار <code>scraper4.py</code> با پشتیبان <code>.bak</code></div>
<div class="card" id="tokenCard" style="display:none"><note>🔒 برای نصب و بازگشت، رمز وب دیپلوی‌ر لازم است. برای تنظیم آن <code>DEPLOYER_WEB_TOKEN</code> را در فایل WSGI ‌قرار دهید.</note></div>
<div class="card">
<div class="stats"><div class="stat"><b id="stCurrent">—</b><span>نسخه نصب‌شده</span></div><div class="stat"><b id="stBest">—</b><span>جدیدترین برنچ</span></div><div class="stat"><b id="stChecked">۰</b><span>برنچ بررسی‌شده</span></div></div>
<div class="actions"><button onclick="refresh()">↻ بررسی نسخه‌ها</button><button class="green" id="btnInstall" onclick="act('install')">⬇ نصب جدیدترین نسخه</button><button class="gray" id="btnRollback" onclick="act('rollback')">↩ بازگشت به .bak</button><button class="gray" id="btnTok" onclick="askToken()">🔑 رمز</button></div>
<div id="status" class="status">در حال بارگذاری…</div>
<div id="branches"></div>
</div>
<div class="card"><b>پیکربندی فعلی</b><div id="cfg" class="status" style="margin-top:8px">—</div></div>
<script>
let T=sessionStorage.getItem('deployerTok')||'',hasToken=__HAS_TOKEN__;
const $=id=>document.getElementById(id);
function esc(s){return String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}
async function api(path,opt={}){const h={'Content-Type':'application/json',...(opt.headers||{})};if(T)h['X-Deployer-Token']=T;const r=await fetch(path,{...opt,headers:h});let j={};try{j=await r.json()}catch(e){};if(r.status===401||r.status===403)throw Error(j.error||'رمز لازم است');if(!r.ok||j.ok===false)throw Error(j.error||'خطای درخواست');return j}
function msg(html,cls){$('status').innerHTML='<span class="'+(cls||'')+'">'+html+'</span>'}
function askToken(){const v=prompt('رمز دیپلوی‌ر (DEPLOYER_WEB_TOKEN):');if(v){sessionStorage.setItem('deployerTok',v);T=v;location.reload()}}
function setBusy(b){['btnInstall','btnRollback'].forEach(id=>{const el=$(id);el.disabled=b});$('status').textContent=b?'در حال اجرا…':$('status').textContent}
function render(d){const r=d.report||d;$('stCurrent').textContent='v'+esc(r.current_version);const best=r.best||{};$('stBest').textContent=best.branch?esc(best.branch)+' v'+esc(best.version):'—';$('stChecked').textContent=r.total_checked||0;
 const rows=(r.rows||[]).map(x=>{if(x.error)return '<div class="branch err-row"><b>'+esc(x.branch)+'</b><small class="err">خطا: '+esc(x.error)+'</small></div>';return '<div class="branch '+(x.newest?'best':'')+'"><b>'+esc(x.branch)+'</b><span>v'+esc(x.version)+'</span><code>'+esc(String(x.sha||'').slice(0,8))+'</code>'+(x.newest?'<b class="tag">جدیدترین</b>':'')+'</div>'}).join('');$('branches').innerHTML=rows||'<div class="note">هیچ برنچی خوانده نشد.</div>';
 $('cfg').innerHTML='repository: <code>'+esc(r.repo)+'</code> · مسیر: <code>'+esc(r.path)+'</code><br>فایل هدف: <code>'+esc(r.target)+'</code><br>برنچ‌ها: '+(r.all_branches?'<b class="ok">همه برنچ‌های ریپو ('+(r.branches||[]).length+' برنچ — خودکار از GitHub)</b>':esc((r.branches||[]).join('، ')))+'<br>برنچ‌های دارای فایل: '+(r.found_with_file||0)+' از '+(r.total_checked||0);}
async function refresh(){try{setBusy(true);const d=await api('/deployer/status');if(!d.ok)throw Error(d.error||'خطا');render(d);msg('بررسی کامل شد.','ok')}catch(e){msg(esc(e.message),'err')}finally{setBusy(false)}}
async function act(kind){if(kind==='install'&&!confirm('فایل جاری جایگزین و نسخه قبلی در .bak ذخیره شود؟'))return;if(kind==='rollback'&&!confirm('نسخه scraper4.py.bak بازیابی شود؟'))return;try{setBusy(true);const d=await api('/deployer/'+kind,{method:'POST',body:'{}'});render(d);msg(esc(d.message||(d.changed?'تغییر اعمال شد.':'تغییری لازم نبود')),d.changed===false?'':'ok')}catch(e){if(/رمز/.test(e.message)){msg(esc(e.message),'err');if(!hasToken)$('tokenCard').style.display='block'}else msg(esc(e.message),'err')}finally{setBusy(false)}}
$('tokenCard').style.display=hasToken?'none':'block';$('btnTok').style.display=hasToken?'none':'inline-block';refresh();
</script></div></body></html>'''


def _web_config() -> dict:
    """Config for the web app: like CLI but driven purely by DEPLOYER_* env vars."""
    ns = argparse.Namespace(repo=None, branches=None, all_branches=None, path=None, target=None,
                            token=None, reload_file=None, interval=None, log_file=None, github_base=None)
    return load_config(ns)


def _report_public(report: dict) -> dict:
    """JSON-safe copy of a report (candidate payloads are never exported)."""
    rows = []
    for row in report.get("rows", []):
        item = {k: row[k] for k in ("branch", "version", "sha", "size", "newest") if k in row}
        if "error" in row:
            item["error"] = row["error"]
        rows.append(item)
    out = dict(report)
    out["rows"] = rows
    return out


def _web_response(start_response, status: str, body: bytes, content_type: str = "application/json",
                  extra: dict[str, str] | None = None) -> list[bytes]:
    headers = [("Content-Type", content_type), ("Content-Length", str(len(body))), ("Cache-Control", "no-store")]
    headers += [(k, v) for k, v in (extra or {}).items()]
    start_response(status, headers)
    return [body]


def _web_json(start_response, payload: dict, status: str = "200 OK") -> list[bytes]:
    return _web_response(start_response, status,
                         json.dumps(payload, ensure_ascii=False).encode("utf-8"))


def _web_token_ok(environ: dict) -> bool:
    token = os.environ.get("DEPLOYER_WEB_TOKEN", "").strip()
    if not token:
        return False
    supplied = environ.get("HTTP_X_DEPLOYER_TOKEN", "")
    if not supplied:
        supplied = (urllib.parse.parse_qs(environ.get("QUERY_STRING", "")).get("token") or [""])[0]
    return bool(supplied) and hmac.compare_digest(supplied, token)


def wsgi_application(environ, start_response) -> list[bytes]:
    """WSGI entry point. Mounted at /deployer by install_deployer_webapp.sh."""
    path = environ.get("PATH_INFO", "/") or "/"
    if path.startswith("/deployer"):
        path = path[len("/deployer"):] or "/"
    method = environ.get("REQUEST_METHOD", "GET").upper()
    if path in ("/favicon.ico", "/favicon.png"):
        return _web_response(start_response, "404 Not Found", b"", "text/plain")

    if method == "GET" and path in ("/", ""):
        page = WEB_PAGE.replace("__HAS_TOKEN__",
                                "true" if os.environ.get("DEPLOYER_WEB_TOKEN", "").strip() else "false")
        return _web_response(start_response, "200 OK", page.encode("utf-8"), "text/html; charset=utf-8")

    if method == "GET" and path == "/status":
        try:
            cfg = _web_config()
            report = build_report(cfg)
            return _web_json(start_response, {"ok": True, "report": _report_public(report)})
        except DeployerError as exc:
            return _web_json(start_response, {"ok": False, "error": str(exc)})
        except Exception as exc:
            log.exception("status failed")
            return _web_json(start_response, {"ok": False, "error": str(exc)[:300]})

    if method == "POST" and path in ("/check", "/install", "/rollback"):
        if not _web_token_ok(environ):
            if not os.environ.get("DEPLOYER_WEB_TOKEN", "").strip():
                return _web_json(start_response,
                                 {"ok": False, "error": "توکن وب دیپلوی‌ر تنظیم نشده است؛ فقط نمایش وضعیت فعال است (DEPLOYER_WEB_TOKEN)"},
                                 "403 Forbidden")
            return _web_json(start_response, {"ok": False, "error": "رمزِ توکن وب دیپلوی‌ر نادرست است"}, "401 Unauthorized")
        try:
            cfg = _web_config()
            if path == "/check":
                result = run_once(cfg, install=False)
                payload = {"ok": True, "action": "check", "report": _report_public(result["report"])}
            elif path == "/install":
                result = run_once(cfg, install=True)
                payload = {"ok": True, "action": "install", "changed": result["changed"],
                           "message": result["message"] if "message" in result else (
                               "نسخه " + result["version"] + " از برنچ " + result["branch"] + " نصب شد"
                               if result["changed"] else "تغییری لازم نیست"),
                           "version": result["version"], "branch": result["branch"],
                           "report": _report_public(result["report"])}
            else:
                result = rollback(cfg)
                cfg = _web_config()
                payload = {"ok": True, "action": "rollback", "changed": True,
                           "message": "نسخه " + result["version"] + " از .bak بازیابی شد",
                           "version": result["version"],
                           "report": _report_public(build_report(cfg))}
            return _web_json(start_response, payload)
        except DeployerError as exc:
            return _web_json(start_response, {"ok": False, "error": str(exc)})
        except Exception as exc:
            log.exception("web action %s failed", path)
            return _web_json(start_response, {"ok": False, "error": str(exc)[:300]})

    return _web_json(start_response, {"ok": False, "error": "مسیر پیدا نشد"}, "404 Not Found")


def serve_web(cfg: dict, port: int, quiet: bool = False) -> None:
    """Local HTTP server for `deployer.py --web` (same UI as the WSGI mount)."""
    from wsgiref.simple_server import make_server
    url = f"http://0.0.0.0:{port}/deployer/"
    if not quiet:
        log.info("وب دیپلوی‌ر اجرا شد: %s (برنچ‌ها: %s)", url, ", ".join(cfg["branches"]))
    httpd = make_server("0.0.0.0", int(port), wsgi_application)
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        log.info("وب دیپلوی‌ر متوقف شد")


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
    parser.add_argument("--branches", help="comma separated branch list (disables all-branches)")
    parser.add_argument("--all-branches", action=argparse.BooleanOptionalAction, default=None,
                        help="scan every branch of the repository (default: on)")
    parser.add_argument("--path", help="file inside the repository")
    parser.add_argument("--target", help="local main file to update")
    parser.add_argument("--token", help="GitHub token (or DEPLOYER_GITHUB_TOKEN)")
    parser.add_argument("--reload-file", nargs="?", const="", help="WSGI file to touch after an update (bare flag or empty disables)")
    parser.add_argument("--interval", type=int, help="seconds between auto checks (min 30)")
    parser.add_argument("--log-file", help="append log lines to this file")
    parser.add_argument("--github-base", help="GitHub API base (default https://api.github.com)")
    parser.add_argument("--web", action="store_true", help="serve the web UI locally (WSGI app also available)")
    parser.add_argument("--port", type=int, default=8787, help="port for --web (default 8787)")
    parser.add_argument("-v", "--verbose", action="store_true", help="log every detail")
    parser.add_argument("--version", action="version", version=f"deployer {DEPLOYER_VERSION}")
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
        if args.web:
            serve_web(cfg, args.port)
            return 0
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
