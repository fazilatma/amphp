#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Deployer4 — فایل مستقل نصب/به‌روزرسانی چندبرنچی برای Scraper4 (PythonAnywhere).

این فایل کنار scraper4.py می‌نشیند و کاملاً مستقل است (چیزی از scraper4
ایمپورت نمی‌کند). همه برنچ‌های کاندید را از GitHub بررسی می‌کند و برنچی که
جدیدترین APP_VERSION را داشته باشد به‌صورت اتمیک روی scraper4.py نصب می‌کند.

نصب روی PythonAnywhere (داخل Bash Console):
    cd ~/scraper4
    curl -fsSL https://raw.githubusercontent.com/fazilatma/amphp/arena/01a06ac3-amphp/setup_deployer4.sh -o setup_deployer4.sh
    bash setup_deployer4.sh

بعد از نصب، در نوار آدرس مرورگر بزنید (اسلش آخر مهم نیست، خودکار اضافه می‌شود):
    https://fazilatma.pythonanywhere.com/deployer/

رمز ورود همان رمز SCRAPER_DEPLOY_PASSWORD است که اسکریپت نصب چاپ می‌کند
(اگر قبلاً نصب اصلی انجام شده باشد، همان رمز قبلی reuse می‌شود).

نکته‌ها:
- آپدیت خودکار سرور (هر چند دقیقه) فقط «ارتقا» می‌دهد و هرگز دانگرید نمی‌کند.
- نسخه قبلی همیشه در scraper4.py.bak می‌ماند و دکمه «بازگشت» آن را برمی‌گرداند.
- این فایل با متغیرهای محیطی WSGI پیکربندی می‌شود (DEPLOYER_*‎). ویرایش از
  رابط هم در deployer4_data.json کنار همین فایل ذخیره می‌شود.
- اجرا محلی (تست):  python3 deployer4.py  (روی پورت 8001)
"""

from __future__ import annotations

import base64
import hashlib
import hmac
import json
import os
import re
import secrets
import tempfile
import threading
import time
from typing import Any, Optional
from urllib.parse import quote

try:
    import requests
except ImportError as exc:  # pragma: no cover
    raise RuntimeError(
        "Missing dependency 'requests'. Run: pip install flask requests"
    ) from exc

try:
    from flask import Flask, Response, jsonify, request
except ImportError as exc:  # pragma: no cover
    raise RuntimeError(
        "Missing dependency 'flask'. Run: pip install flask requests"
    ) from exc


DEPLOYER_VERSION = "1.0.0"
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# فایل اصلی سایت که باید آپدیت شود. پیش‌فرض: scraper4.py کنار همین فایل.
TARGET_FILE = os.environ.get(
    "DEPLOYER_TARGET", os.path.join(BASE_DIR, "scraper4.py")
)
DATA_FILE = os.environ.get(
    "DEPLOYER_DATA_FILE", os.path.join(BASE_DIR, "deployer4_data.json")
)
PASSWORD = os.environ.get("DEPLOYER_PASSWORD", "")

DEFAULT_REPO = "fazilatma/amphp"
DEFAULT_BRANCHES = ["arena/01a06ac3-amphp", "arena/01a0640f-amphp"]
DEFAULT_PATH = "scraper4.py"
MAX_BRANCHES = 8

# نشانه‌هایی که فایل مقصد حتماً باید داشته باشد تا معتبر شناخته شود.
TARGET_MARKERS = ("APP_VERSION", "Flask(", '@app.get("/")', "/api/deploy/run")

BRANCH_RE = re.compile(r"^[A-Za-z0-9._\-/]{1,150}$")
REPO_RE = re.compile(r"^[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+$")

DEPLOY_LOCK = threading.RLock()
AUTO_LOCK = threading.Lock()
AUTO_STATE: dict[str, Any] = {
    "last": 0.0,
    "running": False,
    "error": "",
    "last_result": "هنوز بررسی انجام نشده است",
}

app = Flask(__name__)
app.config["JSON_AS_ASCII"] = False


# ---------------------------------------------------------------------------
# Helpers
# ---------------------------------------------------------------------------
def clean_text(value: Any) -> str:
    return str(value if value is not None else "").strip()


def clean_branch(value: Any) -> str:
    text = clean_text(value)
    if not text or text in {"__CLEAR__", "—", "-"}:
        return ""
    text = text.strip("/")
    if not BRANCH_RE.fullmatch(text):
        return ""
    if ".." in text.split("/") or "//" in text:
        return ""
    return text


def normalize_branches(raw: Any, fallback: str = "") -> list[str]:
    items: list[str] = []
    if isinstance(raw, (list, tuple)):
        for entry in raw:
            for piece in str(entry).replace(",", "\n").splitlines():
                piece = piece.strip()
                if not piece:
                    continue
                cleaned = clean_branch(piece)
                if cleaned and cleaned not in items:
                    items.append(cleaned)
    elif isinstance(raw, str):
        for piece in re.split(r"[\s,;\n]+", raw):
            cleaned = clean_branch(piece)
            if cleaned and cleaned not in items:
                items.append(cleaned)
    fb = clean_branch(fallback)
    if not items and fb:
        items.append(fb)
    if not items:
        items.extend(DEFAULT_BRANCHES[:1])
    return items[:MAX_BRANCHES]


def parse_version_tuple(value: str) -> tuple[int, ...]:
    parts = re.findall(r"\d+", str(value or ""))
    if not parts:
        return (0,)
    return tuple(int(x) for x in parts[:4])


def compare_versions(left: str, right: str) -> int:
    a = parse_version_tuple(left)
    b = parse_version_tuple(right)
    width = max(len(a), len(b))
    a = a + (0,) * (width - len(a))
    b = b + (0,) * (width - len(b))
    return (a > b) - (a < b)


def extract_version_from_text(text: str) -> str:
    match = re.search(r'^APP_VERSION\s*=\s*["\']([^"\']+)', text, re.MULTILINE)
    return match.group(1).strip() if match else "unknown"


def git_blob_sha(content: bytes) -> str:
    return hashlib.sha1(
        b"blob " + str(len(content)).encode("ascii") + b"\0" + content
    ).hexdigest()


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


class FetchError(RuntimeError):
    pass


# ---------------------------------------------------------------------------
# Config (env defaults + JSON overrides from the UI)
# ---------------------------------------------------------------------------
def default_branches_from_env() -> list[str]:
    raw = os.environ.get("DEPLOYER_BRANCHES", "")
    if raw.strip():
        return normalize_branches(raw)
    return list(DEFAULT_BRANCHES)


def load_state() -> dict[str, Any]:
    try:
        with open(DATA_FILE, "r", encoding="utf-8") as fh:
            data = json.load(fh)
        return data if isinstance(data, dict) else {}
    except (OSError, ValueError):
        return {}


def save_state(state: dict[str, Any]) -> None:
    os.makedirs(os.path.dirname(DATA_FILE) or ".", exist_ok=True)
    fd, tmp = tempfile.mkstemp(
        prefix=".deployer4-", suffix=".json", dir=os.path.dirname(DATA_FILE) or "."
    )
    try:
        with os.fdopen(fd, "w", encoding="utf-8") as fh:
            json.dump(state, fh, ensure_ascii=False, indent=2)
            fh.flush()
            os.fsync(fh.fileno())
        os.replace(tmp, DATA_FILE)
    finally:
        try:
            if os.path.exists(tmp):
                os.unlink(tmp)
        except OSError:
            pass


def eff_config() -> dict[str, Any]:
    state = load_state()
    branches = (
        normalize_branches(state.get("branches"))
        if state.get("branches")
        else default_branches_from_env()
    )
    return {
        "repo": clean_text(state.get("repo"))
        or clean_text(os.environ.get("DEPLOYER_REPO"))
        or DEFAULT_REPO,
        "branches": branches,
        "branch": branches[0],
        "path": clean_text(state.get("path"))
        or clean_text(os.environ.get("DEPLOYER_PATH"))
        or DEFAULT_PATH,
        "github_token": os.environ.get("GITHUB_TOKEN", "").strip()
        or clean_text(state.get("github_token")),
        "target": TARGET_FILE,
        "reload_file": os.path.expanduser(
            clean_text(os.environ.get("DEPLOYER_RELOAD_FILE"))
        ),
        "check_on_load": bool(state.get("check_on_load", False)),
        "auto_update": bool(
            state.get(
                "auto_update",
                os.environ.get("DEPLOYER_AUTO_UPDATE", "1").lower()
                not in {"0", "false", "off", "no"},
            )
        ),
        "auto_interval": max(
            120,
            int(
                state.get(
                    "auto_interval",
                    os.environ.get("DEPLOYER_AUTO_INTERVAL", "300"),
                )
            ),
        ),
    }


# ---------------------------------------------------------------------------
# GitHub access (direct; the deployer is independent of the scraper gateway)
# ---------------------------------------------------------------------------
def gh_headers(token: str) -> dict[str, str]:
    headers = {
        "User-Agent": "deployer4",
        "Accept": "application/vnd.github+json",
    }
    if token:
        headers["Authorization"] = "Bearer " + token
    return headers


def gh_get(api_url: str, token: str, params: Optional[dict[str, Any]] = None) -> Any:
    try:
        response = requests.get(
            api_url, params=params or {}, headers=gh_headers(token), timeout=30
        )
    except requests.RequestException as exc:
        raise FetchError(f"ارتباط با GitHub ناموفق بود: {exc}") from exc
    if response.status_code == 401:
        raise FetchError("توکن GitHub نامعتبر یا منقضی است (HTTP 401)")
    if response.status_code == 403:
        raise FetchError("دسترسی GitHub رد شد یا محدودیت نرخ تمام شده است (HTTP 403)")
    if response.status_code == 404:
        raise FetchError("repository، برنچ یا فایل در GitHub پیدا نشد (HTTP 404)")
    if not response.ok:
        raise FetchError(f"GitHub HTTP {response.status_code}: {response.text[:250]}")
    try:
        return response.json()
    except ValueError as exc:
        raise FetchError("پاسخ GitHub معتبر نیست") from exc


def github_file_for(
    repo: str, branch: str, remote_path: str, token: str = "", include_content: bool = False
) -> dict[str, Any]:
    repo = str(repo or "").strip("/")
    if not REPO_RE.fullmatch(repo):
        raise ValueError("نام repository باید به صورت owner/repo باشد")
    branch_cleaned = clean_branch(branch)
    if not branch_cleaned:
        raise ValueError("نام برنچ معتبر نیست")
    remote_path = str(remote_path or "").strip("/")
    if (
        not remote_path
        or not remote_path.endswith(".py")
        or ".." in remote_path.split("/")
    ):
        raise ValueError("مسیر منبع باید یک فایل امن با پسوند .py باشد")
    api_url = (
        "https://api.github.com/repos/" + repo + "/contents/" + quote(remote_path, safe="/")
    )
    meta = gh_get(api_url, token, params={"ref": branch_cleaned})
    if not isinstance(meta, dict) or meta.get("type") != "file":
        raise FetchError("مسیر GitHub یک فایل نیست")
    if int(meta.get("size") or 0) > 2 * 1024 * 1024:
        raise FetchError("فایل به‌روزرسانی بزرگ‌تر از ۲ مگابایت است")
    out: dict[str, Any] = {
        "sha": str(meta.get("sha", "")),
        "size": int(meta.get("size") or 0),
        "html_url": str(meta.get("html_url", "")),
        "name": str(meta.get("name", "")),
        "branch": branch_cleaned,
    }
    if include_content:
        encoded = str(meta.get("content", "")).replace("\n", "")
        try:
            content = base64.b64decode(encoded, validate=True)
        except (ValueError, TypeError) as exc:
            raise FetchError("محتوای فایل از GitHub قابل خواندن نیست") from exc
        out["content"] = content
        try:
            out["version"] = extract_version_from_text(content.decode("utf-8", errors="replace"))
        except Exception:
            out["version"] = "unknown"
    return out


def github_branch_list(repo: str, token: str = "") -> list[dict[str, Any]]:
    repo = str(repo or "").strip("/")
    if not REPO_RE.fullmatch(repo):
        raise ValueError("نام repository باید به صورت owner/repo باشد")
    data = gh_get(
        "https://api.github.com/repos/" + repo + "/branches",
        token,
        params={"per_page": 100},
    )
    out: list[dict[str, Any]] = []
    if isinstance(data, list):
        for row in data:
            if isinstance(row, dict) and clean_branch(row.get("name")):
                out.append(
                    {"name": clean_branch(row.get("name")), "protected": bool(row.get("protected"))}
                )
    return out[:100]


def github_python_files(repo: str, branch: str, token: str = "") -> list[str]:
    repo = str(repo or "").strip("/")
    branch_cleaned = clean_branch(branch)
    if not REPO_RE.fullmatch(repo) or not branch_cleaned:
        raise ValueError("ریپو و برنچ لازم است")
    data = gh_get(
        "https://api.github.com/repos/" + repo + "/git/trees/" + quote(branch_cleaned, safe=""),
        token,
        params={"recursive": 1},
    )
    tree = data.get("tree") if isinstance(data, dict) else None
    files: list[str] = []
    if isinstance(tree, list):
        for node in tree:
            if not isinstance(node, dict) or node.get("type") != "blob":
                continue
            path = str(node.get("path", ""))
            if path.endswith(".py") and ".." not in path.split("/") and len(path) <= 200:
                files.append(path)
    files.sort(key=lambda x: (x != "scraper4.py", x.lower()))
    return files[:200]


# ---------------------------------------------------------------------------
# Local target file
# ---------------------------------------------------------------------------
def read_target() -> bytes:
    with open(TARGET_FILE, "rb") as fh:
        return fh.read()


def local_info() -> dict[str, str]:
    try:
        content = read_target()
    except OSError as exc:
        raise FetchError(f"فایل اصلی پیدا نشد: {TARGET_FILE} ({exc})") from exc
    try:
        text = content.decode("utf-8")
    except UnicodeDecodeError:
        text = ""
    return {
        "version": extract_version_from_text(text) if text else "unknown",
        "sha": git_blob_sha(content),
        "size": len(content),
    }


def validate_target_source(content: bytes) -> str:
    if not content.startswith((b"#!/", b"#", b'"""', b"'''")):
        raise FetchError("فایل دانلودشده شبیه کد Python نیست")
    try:
        text = content.decode("utf-8")
    except UnicodeDecodeError as exc:
        raise FetchError("فایل دانلودشده UTF-8 نیست") from exc
    try:
        compile(text, "scraper4-update.py", "exec")
    except SyntaxError as exc:
        raise FetchError(f"نسخه تازه خطای syntax دارد: خط {exc.lineno}: {exc.msg}") from exc
    missing = [marker for marker in TARGET_MARKERS if marker not in text]
    if missing:
        raise FetchError("نسخه تازه اعتبارسنجی نشد؛ نشانه‌های Scraper4 کامل نیست")
    return extract_version_from_text(text)


def fetch_candidates(
    cfg: dict[str, Any], include_content: bool = False
) -> tuple[list[dict[str, Any]], str]:
    try:
        local_sha = git_blob_sha(read_target())
    except OSError:
        local_sha = ""
    candidates: list[dict[str, Any]] = []
    for branch in cfg["branches"]:
        try:
            remote = github_file_for(
                cfg["repo"], branch, cfg["path"], cfg.get("github_token", ""), include_content
            )
            candidates.append(
                {
                    "branch": branch,
                    "version": remote.get("version", "") if include_content else "",
                    "sha": remote.get("sha", ""),
                    "size": remote.get("size", 0),
                    "html_url": remote.get("html_url", ""),
                    "update_available": bool(local_sha) and local_sha != remote.get("sha", ""),
                    "content": remote.get("content") if include_content else None,
                }
            )
        except (ValueError, FetchError) as exc:
            candidates.append(
                {
                    "branch": branch,
                    "version": "",
                    "sha": "",
                    "size": 0,
                    "html_url": "",
                    "update_available": False,
                    "error": str(exc),
                    "content": None,
                }
            )
    return candidates, local_sha


def pick_newest(candidates: list[dict[str, Any]]) -> Optional[dict[str, Any]]:
    valid = [c for c in candidates if c.get("sha") and not c.get("error")]
    if not valid:
        return None
    if all((c.get("version") or "unknown") == "unknown" for c in valid):
        return valid[0]
    return max(
        valid,
        key=lambda c: (
            parse_version_tuple(c.get("version") or "0"),
            str(c.get("version") or ""),
            str(c.get("branch") or ""),
        ),
    )


def touch_reload_file(path: str) -> bool:
    if not path:
        return False
    if not os.path.isfile(path):
        raise FetchError("فایل WSGI برای reload پیدا نشد؛ مسیر را بررسی کنید")
    os.utime(path, None)
    return True


def pythonanywhere_reload() -> bool:
    token_file = os.path.expanduser("~/.pythonanywhere_api_token")
    try:
        with open(token_file, encoding="utf-8") as fh:
            token = fh.read().strip()
        if not token:
            return False
        username = os.path.basename(os.path.expanduser("~"))
        domain = username.lower() + ".pythonanywhere.com"
        url = (
            f"https://www.pythonanywhere.com/api/v0/user/{username}"
            f"/webapps/{domain}/reload/"
        )
        response = requests.post(
            url, headers={"Authorization": "Token " + token}, timeout=30
        )
        return response.status_code in (200, 201)
    except Exception:
        return False


def check_all() -> dict[str, Any]:
    cfg = eff_config()
    try:
        info = local_info()
    except FetchError as exc:
        return {
            "repo": cfg["repo"], "branches": cfg["branches"], "path": cfg["path"],
            "target": cfg["target"], "local_version": "unknown", "local_sha": "",
            "local_error": str(exc), "newest_branch": "", "newest_version": "",
            "update_available": False, "candidates": [],
            "check_on_load": cfg["check_on_load"],
        }
    candidates, local_sha = fetch_candidates(cfg, include_content=True)
    for cand in candidates:
        if cand.get("content") is not None and not cand.get("version"):
            try:
                cand["version"] = extract_version_from_text(
                    cand["content"].decode("utf-8", errors="replace")
                )
            except Exception:
                cand["version"] = "unknown"
        cand.pop("content", None)
    newest = pick_newest(candidates)
    if newest is None:
        errors = "; ".join(
            f"{c.get('branch')}: {c.get('error', 'خطا')}" for c in candidates[:4]
        )
        raise FetchError(
            f"هیچ برنچی قابل بررسی نبود: {errors}" if errors else "هیچ برنچی قابل بررسی نبود"
        )
    return {
        "repo": cfg["repo"], "branches": cfg["branches"], "path": cfg["path"],
        "target": cfg["target"], "local_version": info["version"], "local_sha": local_sha,
        "newest_branch": newest["branch"], "newest_version": newest.get("version", "unknown"),
        "update_available": local_sha != newest["sha"],
        "candidates": candidates, "check_on_load": cfg["check_on_load"],
    }


def install_branch(requested_branch: str = "") -> dict[str, Any]:
    if not DEPLOY_LOCK.acquire(blocking=False):
        raise FetchError("یک نصب دیگر هم‌اکنون در حال اجراست")
    try:
        cfg = eff_config()
        wanted = clean_branch(requested_branch)
        branches = list(cfg["branches"])
        if wanted and wanted not in branches:
            branches = ([wanted] + branches)[:MAX_BRANCHES]
        cfg = dict(cfg)
        cfg["branches"] = branches
        candidates, _ = fetch_candidates(cfg, include_content=True)
        target_cand: Optional[dict[str, Any]] = None
        if wanted:
            for cand in candidates:
                if cand.get("branch") == wanted and cand.get("content") is not None:
                    target_cand = cand
                    break
            if target_cand is None:
                err = next(
                    (c.get("error", "دریافت ناموفق بود") for c in candidates if c.get("branch") == wanted),
                    "دریافت ناموفق بود",
                )
                raise FetchError(f"برنچ {wanted}: {err}")
        else:
            ok = [c for c in candidates if c.get("content") is not None]
            for cand in ok:
                if not cand.get("version"):
                    try:
                        cand["version"] = extract_version_from_text(
                            cand["content"].decode("utf-8", errors="replace")
                        )
                    except Exception:
                        cand["version"] = "unknown"
            target_cand = pick_newest(ok)
            if target_cand is None:
                errors = "; ".join(
                    f"{c.get('branch', '?')}: {c.get('error', 'خطا')}" for c in candidates[:4]
                )
                raise FetchError(
                    f"هیچ برنچی قابل نصب نبود: {errors}" if errors else "هیچ برنچی قابل نصب نبود"
                )
        content = target_cand["content"]
        new_version = validate_target_source(content)
        try:
            current = read_target()
        except OSError as exc:
            raise FetchError(f"فایل اصلی قابل خواندن نیست: {exc}") from exc
        if git_blob_sha(current) == target_cand["sha"]:
            return {
                "changed": False, "message": "همین نسخه اکنون نصب است",
                "version": new_version, "branch": target_cand["branch"],
                "newest_branch": target_cand["branch"], "newest_version": new_version,
            }
        try:
            old_mode = os.stat(TARGET_FILE).st_mode & 0o777
        except OSError:
            old_mode = 0o600
        atomic_write(TARGET_FILE + ".bak", current, old_mode)
        atomic_write(TARGET_FILE, content, old_mode)
        reloaded = touch_reload_file(cfg["reload_file"]) if cfg["reload_file"] else False
        pa_reloaded = pythonanywhere_reload()
        return {
            "changed": True,
            "message": f"نسخه {new_version} از برنچ {target_cand['branch']} نصب شد",
            "version": new_version, "sha": target_cand["sha"],
            "branch": target_cand["branch"], "newest_branch": target_cand["branch"],
            "newest_version": new_version, "backup": os.path.basename(TARGET_FILE + ".bak"),
            "reload_requested": bool(reloaded or pa_reloaded),
        }
    finally:
        DEPLOY_LOCK.release()


def rollback() -> dict[str, Any]:
    if not DEPLOY_LOCK.acquire(blocking=False):
        raise FetchError("یک عملیات نصب دیگر در حال اجراست")
    try:
        backup = TARGET_FILE + ".bak"
        if not os.path.isfile(backup):
            raise FetchError("نسخه پشتیبان scraper4.py.bak وجود ندارد")
        with open(backup, "rb") as fh:
            content = fh.read()
        version = validate_target_source(content)
        try:
            mode = os.stat(TARGET_FILE).st_mode & 0o777
        except OSError:
            mode = 0o600
        atomic_write(TARGET_FILE, content, mode)
        cfg = eff_config()
        reloaded = touch_reload_file(cfg["reload_file"]) if cfg["reload_file"] else False
        pythonanywhere_reload()
        return {
            "changed": True, "message": "نسخه پشتیبان بازیابی شد",
            "version": version, "reload_requested": reloaded,
        }
    finally:
        DEPLOY_LOCK.release()


# ---------------------------------------------------------------------------
# Automatic check + update loop (only upgrades, never downgrades)
# ---------------------------------------------------------------------------
def auto_cycle() -> str:
    cfg = eff_config()
    try:
        info = local_info()
    except FetchError as exc:
        return f"خطا: {exc}"
    candidates, _ = fetch_candidates(cfg, include_content=True)
    ok = [c for c in candidates if c.get("content") is not None]
    for cand in ok:
        if not cand.get("version"):
            try:
                cand["version"] = extract_version_from_text(
                    cand["content"].decode("utf-8", errors="replace")
                )
            except Exception:
                cand["version"] = "unknown"
    newest = pick_newest(ok)
    if newest is None:
        return "خطا: هیچ برنچی در دسترس نبود"
    new_version = newest.get("version") or "unknown"
    if compare_versions(new_version, info["version"]) < 0:
        return f"به‌روز است (محلی v{info['version']}؛ جدیدترین v{new_version} در {newest['branch']})"
    try:
        current = read_target()
    except OSError as exc:
        return f"خطا: {exc}"
    if git_blob_sha(current) == newest["sha"]:
        return f"به‌روز است (v{info['version']})"
    if compare_versions(new_version, info["version"]) < 0:
        return f"به‌روز است (محلی v{info['version']}؛ جدیدترین v{new_version} در {newest['branch']})"
    result = install_branch(newest["branch"])
    return f"{result['message']}"


def auto_loop() -> None:
    time.sleep(60)  # let the web app finish starting
    while True:
        try:
            cfg = eff_config()
            interval = cfg["auto_interval"]
            if cfg["auto_update"] and AUTO_LOCK.acquire(blocking=False):
                try:
                    AUTO_STATE["running"] = True
                    outcome = auto_cycle()
                    AUTO_STATE["last_result"] = outcome
                    AUTO_STATE["error"] = ""
                except Exception as exc:
                    AUTO_STATE["error"] = str(exc)[:300]
                    AUTO_STATE["last_result"] = f"خطای آپدیت خودکار: {exc}"
                finally:
                    AUTO_STATE["last"] = time.time()
                    AUTO_STATE["running"] = False
                    try:
                        AUTO_LOCK.release()
                    except RuntimeError:
                        pass
            else:
                AUTO_STATE["last"] = time.time()
            time.sleep(interval)
        except Exception as exc:  # never kill the loop
            AUTO_STATE["error"] = str(exc)[:300]
            time.sleep(300)


# ---------------------------------------------------------------------------
# Auth + Flask API (all fetch() calls below are RELATIVE so the app works
# both at "/" and mounted under "/deployer" via DispatcherMiddleware)
# ---------------------------------------------------------------------------
def authorized() -> bool:
    if not PASSWORD:
        return False
    supplied = request.headers.get("X-Deployer-Password", "")
    return bool(supplied) and hmac.compare_digest(supplied, PASSWORD)


def auth_error():
    if not PASSWORD:
        return jsonify(ok=False, error="رمز دیپلویِر در WSGI تنظیم نشده است"), 503
    return jsonify(ok=False, error="رمز دیپلویِر نادرست است"), 401


@app.before_request
def guard():
    if request.path in ("/", "/health"):
        return None
    if request.path.startswith("/api/") and not authorized():
        return auth_error()
    return None


@app.get("/health")
def health():
    try:
        info = local_info()
        local_version, local_sha = info["version"], info["sha"]
    except FetchError:
        local_version, local_sha = "unknown", ""
    cfg = eff_config()
    return jsonify(
        ok=True,
        deployer_version=DEPLOYER_VERSION,
        target_version=local_version,
        target_sha=local_sha,
        auto_update=cfg["auto_update"],
        auto_running=AUTO_STATE["running"],
        auto_last_result=AUTO_STATE["last_result"],
        auto_error=AUTO_STATE["error"],
    )


@app.get("/api/config")
def api_config():
    cfg = eff_config()
    try:
        info = local_info()
    except FetchError as exc:
        info = {"version": "unknown", "sha": "", "size": 0, "error": str(exc)}
    has_token = bool(os.environ.get("GITHUB_TOKEN", "").strip() or load_state().get("github_token"))
    return jsonify(
        ok=True,
        deployer=DEPLOYER_VERSION,
        repo=cfg["repo"],
        branches=cfg["branches"],
        branch=cfg["branch"],
        path=cfg["path"],
        target=cfg["target"],
        check_on_load=cfg["check_on_load"],
        auto_update=cfg["auto_update"],
        auto_interval=cfg["auto_interval"],
        has_token=has_token,
        local_version=info.get("version", "unknown"),
        local_sha=info.get("sha", ""),
        local_error=info.get("error", ""),
        auto_state={
            "running": AUTO_STATE["running"],
            "last_result": AUTO_STATE["last_result"],
            "error": AUTO_STATE["error"],
        },
    )


@app.post("/api/settings")
def api_settings():
    body = request.get_json(silent=True) or {}
    state = load_state()
    if "repo" in body:
        repo = clean_text(body.get("repo"))
        if repo and not REPO_RE.fullmatch(repo):
            return jsonify(ok=False, error="نام repository باید به صورت owner/repo باشد"), 400
        if repo:
            state["repo"] = repo
    if "branches" in body or "branch" in body:
        merged = normalize_branches(
            body.get("branches", body.get("branch", "")), clean_text(body.get("branch", ""))
        )
        legacy = clean_branch(body.get("branch", ""))
        if legacy and legacy not in merged:
            merged = ([legacy] + merged)[:MAX_BRANCHES]
        state["branches"] = merged
    if "path" in body:
        path = clean_text(body.get("path"))
        if path:
            if not path.endswith(".py") or ".." in path.split("/"):
                return jsonify(ok=False, error="مسیر باید فایل امن با پسوند .py باشد"), 400
            state["path"] = path
    if "check_on_load" in body:
        state["check_on_load"] = bool(body.get("check_on_load"))
    if "auto_update" in body:
        state["auto_update"] = bool(body.get("auto_update"))
    if "auto_interval" in body:
        try:
            state["auto_interval"] = max(120, int(body.get("auto_interval")))
        except (TypeError, ValueError):
            pass
    token = clean_text(body.get("github_token", ""))
    if token == "__CLEAR__":
        state["github_token"] = ""
    elif token:
        state["github_token"] = token
    save_state(state)
    return jsonify(ok=True)


@app.get("/api/branches")
def api_branches():
    cfg = eff_config()
    repo = clean_text(request.args.get("repo")) or cfg["repo"]
    try:
        return jsonify(ok=True, repo=repo, branches=github_branch_list(repo, cfg["github_token"]))
    except (ValueError, FetchError) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.get("/api/files")
def api_files():
    cfg = eff_config()
    repo = clean_text(request.args.get("repo")) or cfg["repo"]
    branch = clean_text(request.args.get("branch")) or cfg["branch"]
    try:
        return jsonify(
            ok=True, repo=repo, branch=branch,
            files=github_python_files(repo, branch, cfg["github_token"]),
        )
    except (ValueError, FetchError) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.post("/api/check")
def api_check():
    try:
        return jsonify(ok=True, **check_all())
    except (ValueError, FetchError) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.post("/api/update")
def api_update():
    body = request.get_json(silent=True) or {}
    wanted = clean_branch(body.get("branch", "")) if isinstance(body, dict) else ""
    try:
        return jsonify(ok=True, **install_branch(wanted))
    except (ValueError, FetchError, OSError) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.post("/api/rollback")
def api_rollback():
    try:
        return jsonify(ok=True, **rollback())
    except (ValueError, FetchError, OSError) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.post("/api/auto/run")
def api_auto_run():
    if AUTO_LOCK.acquire(blocking=False):
        try:
            AUTO_STATE["running"] = True
            try:
                outcome = auto_cycle()
                AUTO_STATE["last_result"] = outcome
                AUTO_STATE["error"] = ""
            except Exception as exc:
                AUTO_STATE["error"] = str(exc)[:300]
                AUTO_STATE["last_result"] = f"خطای آپدیت خودکار: {exc}"
            finally:
                AUTO_STATE["last"] = time.time()
                AUTO_STATE["running"] = False
        finally:
            try:
                AUTO_LOCK.release()
            except RuntimeError:
                pass
        return jsonify(ok=True, result=AUTO_STATE["last_result"], error=AUTO_STATE["error"])
    return jsonify(ok=False, error="آپدیت خودکار هم‌اکنون در حال اجراست"), 409


# ---------------------------------------------------------------------------
# Inline UI (single-file; relative fetch() so it works under /deployer too)
# ---------------------------------------------------------------------------
INDEX_HTML = r'''<!doctype html>
<html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,viewport-fit=cover"><meta name="theme-color" content="#07111f">
<title>Deployer4 — نصب چندبرنچی Scraper4</title><style>
:root{--bg:#07111f;--bg2:#0a1830;--card:rgba(15,28,48,.9);--line:rgba(148,177,216,.16);--text:#f4f8ff;--muted:#9db0ca;--blue:#38bdf8;--green:#34d399;--red:#fb7185;--amber:#fbbf24}
*{box-sizing:border-box}body{margin:0;min-height:100vh;background:linear-gradient(155deg,var(--bg),var(--bg2));background-attachment:fixed;color:var(--text);font-family:Tahoma,"Segoe UI",Arial,sans-serif;font-size:14px;line-height:1.7}
.wrap{max-width:860px;margin:auto;padding:26px 16px 70px}
.hero{display:flex;align-items:center;gap:14px;padding:20px;border:1px solid var(--line);border-radius:20px;background:linear-gradient(125deg,rgba(15,38,68,.92),rgba(16,31,53,.76));margin-bottom:14px}
.logo{width:52px;height:52px;display:grid;place-items:center;border-radius:16px;font-size:26px;background:linear-gradient(145deg,#0ea5e9,#2563eb)}
h1{font-size:21px;margin:0}h1 small{font-size:10px;color:#7f96b4;background:#09182c;border:1px solid var(--line);padding:3px 8px;border-radius:20px}
.sub{color:var(--muted);font-size:12px;margin:4px 0 0}
.card{background:var(--card);border:1px solid var(--line);border-radius:18px;padding:18px;margin-bottom:12px}
.card h3{margin:0 0 8px;font-size:17px}
.note{padding:11px 13px;border-radius:12px;border:1px solid rgba(56,189,248,.12);background:rgba(19,42,70,.68);color:#bfd0e5;font-size:12.5px}
.status{white-space:pre-wrap;color:#b9cae0;border-right:3px solid var(--blue);min-height:52px;padding:8px 10px;font-size:12.5px}
.error{color:var(--red)}.ok{color:var(--green)}
code{direction:ltr;display:inline-block;color:#a5e4ff;background:#061426;border-radius:6px;padding:1px 5px;font-size:11px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:10px}.wide{grid-column:1/-1}
label{display:block;color:#b9c8dc;font-size:12px;font-weight:700;margin:0 2px 6px}
input,textarea,button{font-family:inherit;font-size:15px;border-radius:11px;border:1px solid var(--line);padding:10px 12px;background:rgba(5,14,27,.72);color:var(--text);width:100%;outline:none}
input:focus,textarea:focus{border-color:var(--blue)}
button{width:auto;min-height:42px;cursor:pointer;background:linear-gradient(135deg,#0284c7,#2563eb);font-weight:700}
button.gray{background:#17263d;border-color:#33465f}button.green{background:linear-gradient(135deg,#059669,#047857)}
button:disabled{opacity:.6;cursor:wait}
.actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}
.hidden{display:none!important}
.banner{display:none;align-items:center;gap:10px;flex-wrap:wrap;padding:12px 15px;border-radius:13px;margin-bottom:12px;background:linear-gradient(135deg,#b45309,#ea580c);font-weight:800}
.banner button{width:auto;min-height:34px;padding:6px 13px;font-size:12px}.banner .ghost{background:transparent;border:1px solid #fff8;color:#fff}
.local{background:#0f172a;border:1px solid #334155;border-radius:10px;padding:9px 11px;margin:10px 0;font-family:monospace;font-size:11px;color:#67e8f9;line-height:1.9}
.local span{color:#64748b}
.chips{display:flex;flex-wrap:wrap;gap:7px;margin-top:8px}
.chip{display:inline-flex;align-items:center;gap:7px;padding:5px 5px 5px 10px;border:1px solid #33465f;border-radius:99px;background:#0b1a2e;font-size:12px;direction:ltr}
.chip button{width:auto;min-height:0;padding:2px 8px;font-size:11px;border-radius:99px;background:#3b1d24;border-color:#fb718555;color:#fecaca}
.chip.new{border-color:#34d39988;background:#052e22;color:#a7f3d0}
.cands{display:grid;gap:7px;margin-top:10px}
.cand{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:8px;align-items:center;padding:10px 12px;border:1px solid var(--line);border-radius:12px;background:#081528}
.cand.new{border-color:#34d39977;background:linear-gradient(140deg,#0b2929,#10223b)}
.cand small{color:var(--muted);display:block;font-size:10.5px;margin-top:3px}
.cand button{font-size:11px;min-height:32px;padding:5px 10px}
.drop{position:absolute;top:100%;right:0;left:0;z-index:30;max-height:210px;overflow:auto;background:#0b1a2e;border:1px solid #38bdf855;border-radius:10px;margin-top:4px;display:none}
.drop.open{display:block}.opt{display:flex;justify-content:space-between;padding:8px 10px;cursor:pointer;font-size:12px;direction:ltr;text-align:left}
.opt:hover{background:#164e63}.meta{color:#64748b;font-size:10px}
.checkline{display:flex;align-items:center;gap:8px;margin-top:10px;font-size:12px;cursor:pointer}
.checkline input{width:auto;min-height:0;accent-color:#38bdf8}
.checkline input[type=number]{width:80px}
#toast{position:fixed;bottom:30px;left:50%;transform:translateX(-50%) translateY(20px);z-index:99999;max-width:92vw;padding:11px 18px;border-radius:12px;background:#0f172a;border:1px solid #38bdf866;font-size:13px;font-weight:700;opacity:0;pointer-events:none;transition:.25s;text-align:center}
#toast.show{opacity:1;transform:translateX(-50%)}#toast.err{border-color:#fb718588;color:#fecaca}
.foot{color:var(--muted);font-size:11px;text-align:center;margin-top:14px}
@media(max-width:640px){.grid{grid-template-columns:1fr}.actions{display:grid;grid-template-columns:1fr}.actions button{width:100%}}
</style></head><body><div class="wrap">
<div id="banner" class="banner"><span id="bannerText" style="flex:1;min-width:200px"></span><button onclick="goInstall()">نصب کن</button><button class="ghost" onclick="hideBanner()">بعداً</button></div>
<header class="hero"><div class="logo">🧭</div><div><h1>دیپلویِر مستقل <small id="depVer">v1.0.0</small></h1><div class="sub">بررسی چندبرنچی و نصب خودکار جدیدترین نسخه scraper4.py</div></div></header>
<div class="card"><h3>🔄 نسخهٔ کد اصلی</h3>
<div class="note">همه برنچ‌های کاندید بررسی می‌شوند و برنچی که <b>جدیدترین <code>APP_VERSION</code></b> را داشته باشد نصب می‌شود. آپدیت خودکار سرور فقط ارتقا می‌دهد و هرگز دانگرید نمی‌کند. نسخه قبلی در <code>scraper4.py.bak</code> می‌ماند.</div>
<div id="localBox" class="local">—</div>
<button class="green" id="mainBtn" onclick="checkInstall(true)" style="width:100%;padding:12px">🔍 بررسی و نصب نسخهٔ جدید</button>
<button class="gray hidden" id="updateBtn" onclick="updateNewest()" style="width:100%;padding:11px;margin-top:8px">⬇ نصب جدیدترین نسخه</button>
<div id="status" class="status" style="margin-top:8px">آماده بررسی.</div>
<label class="checkline"><input type="checkbox" id="autoCheck" onchange="saveSettings(true)"> بررسی خودکار هنگام باز شدن صفحه (فقط اطلاع؛ نصب با تأیید شماست)</label>
<label class="checkline"><input type="checkbox" id="autoUpdate" onchange="saveSettings(true)"> آپدیت خودکار سرور هر <input type="number" id="autoInterval" min="120" max="3600" step="60" value="300" onclick="event.stopPropagation()" onchange="saveSettings(true)"> ثانیه</label>
</div>
<div class="card"><h3>⚙️ منبع</h3>
<div class="grid"><div><label>Repository (owner/repo)</label><div style="display:flex;gap:6px"><input id="repo" dir="ltr" style="flex:1"><button class="gray" id="repoBtn" onclick="loadBranches(true)" style="flex:0 0 auto;width:auto">🔄</button></div></div>
<div><label>مسیر فایل در repository</label><div style="position:relative"><input id="path" dir="ltr" autocomplete="off" oninput="filterFiles()" onfocus="filterFiles()"><div class="drop" id="fileDrop"></div></div><small id="fileCount" style="color:var(--muted);font-size:10px"></small></div>
<div class="wide"><label>برنچ‌های کاندید — هر خط یک برنچ (جدیدترین نسخه نصب می‌شود)</label><textarea id="branches" dir="ltr" rows="3"></textarea>
<div style="display:flex;gap:6px;margin-top:6px;flex-wrap:wrap"><div style="flex:1;position:relative;min-width:150px"><input id="branchPick" dir="ltr" autocomplete="off" placeholder="کلیک یا تایپ برای انتخاب برنچ…" oninput="filterBranches()" onfocus="filterBranches()"><div class="drop" id="branchDrop"></div></div><button class="gray" onclick="addBranch()" style="width:auto">＋ افزودن برنچ</button></div>
<div id="chips" class="chips"></div></div>
<div class="wide"><label>GitHub token اختیاری (فقط ریپوی خصوصی)</label><input id="token" type="password" dir="ltr" placeholder="خالی = نگه‌داشتن قبلی"></div>
</div>
<div class="actions"><button onclick="saveSettings()">💾 ذخیره تنظیمات</button><button class="gray" onclick="check(true)">بررسی نسخه‌ها</button><button class="green" onclick="updateNewest()">⬇ نصب جدیدترین</button><button class="gray" onclick="rollback()">بازگشت به .bak</button><button class="gray" onclick="autoRun()">⚡ اجرای فوری آپدیت خودکار</button></div>
<div id="cands" class="cands"></div>
</div>
<div class="foot" id="foot">—</div>
</div><div id="toast"></div>
<script>
const $=id=>document.getElementById(id);
const esc=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const toFa=x=>String(x??'').replace(/[0-9]/g,d=>'۰۱۲۳۴۵۶۷۸۹'[+d]);
const basePath=()=>{let b=location.pathname;return b.endsWith('/')?b:b+'/'};
let SECRET=sessionStorage.getItem('deployer4Password')||'';
async function needSecret(){if(!SECRET){SECRET=prompt('رمز دیپلویِر را وارد کنید:')||'';if(!SECRET)throw Error('رمز وارد نشد');sessionStorage.setItem('deployer4Password',SECRET)}return SECRET}
async function api(p,opt={}){let s;try{s=await needSecret()}catch(e){throw e}let r=await fetch(basePath()+p,{...opt,headers:{...(opt.headers||{}),'X-Deployer-Password':s,'Content-Type':'application/json'}});let d=await r.json().catch(()=>({ok:false,error:'پاسخ نامعتبر'}));if((r.status===401||r.status===503)&&/رمز/.test(d.error||'')){SECRET='';sessionStorage.removeItem('deployer4Password')}if(!r.ok||d.ok===false)throw Error(d.error||('HTTP '+r.status));return d}
function showToast(m,e){let t=$('toast');clearTimeout(window._tt);t.textContent=m;t.className=e?'err show':'show';window._tt=setTimeout(()=>t.className=e?'err':'',3200)}
function showBanner(t){$('banner').style.display='flex';$('bannerText').textContent=t}
function hideBanner(){$('banner').style.display='none'}
function goInstall(){hideBanner();updateNewest()}
let BRANCHES=[],FILES=[];
const uiBranches=()=>{let seen=[],out=[];($('branches').value||'').split(/[\s,;\n]+/).map(x=>x.trim()).filter(Boolean).forEach(b=>{if(!seen.includes(b)){seen.push(b);out.push(b)}});return out.slice(0,8)};
function renderChips(nw){let l=uiBranches();$('chips').innerHTML=l.map(b=>'<span class="chip'+(nw&&b===nw?' new':'')+'">'+esc(b)+(nw&&b===nw?' ★ جدیدترین':'')+'<button onclick="removeBranch(\''+esc(b)+'\')">✕</button></span>').join('')||'<span style="color:var(--muted);font-size:11px">برنچی ثبت نشده است.</span>'}
function removeBranch(b){$('branches').value=uiBranches().filter(x=>x!==b).join('\n');renderChips();saveSettings()}
function addBranch(){let v=($('branchPick').value||'').trim();if(!v){showToast('ابتدا یک برنچ انتخاب یا تایپ کنید',1);return}if(!/^[A-Za-z0-9._\-/]{1,150}$/.test(v)||v.includes('..')){showToast('نام برنچ معتبر نیست',1);return}let l=uiBranches();if(l.includes(v)){showToast('این برنچ قبلا اضافه شده');return}if(l.length>=8){showToast('حداکثر ۸ برنچ',1);return}l.push(v);$('branches').value=l.join('\n');$('branchPick').value='';closeDrops();renderChips();saveSettings()}
function closeDrops(){['branchDrop','fileDrop'].forEach(id=>{let e=$(id);if(e)e.classList.remove('open')})}
document.addEventListener('click',e=>{if(!e.target.closest||(!e.target.closest('#branchPick')&&!e.target.closest('#branchDrop')&&!e.target.closest('#path')&&!e.target.closest('#fileDrop')))closeDrops()});
async function loadBranches(m){let repo=($('repo').value||'').trim();if(!repo){if(m)showToast('ابتدا نام ریپو را وارد کنید',1);return}let b=$('repoBtn');if(b){b.disabled=true;b.textContent='⏳'}try{let d=await api('api/branches?repo='+encodeURIComponent(repo));BRANCHES=d.branches||[];if(m)showToast('✓ '+toFa(BRANCHES.length)+' برنچ')}catch(e){BRANCHES=[];if(m)showToast(e.message,1)}finally{if(b){b.disabled=false;b.textContent='🔄'}}filterBranches()}
function filterBranches(){let box=$('branchDrop');if(!box)return;let q=($('branchPick').value||'').trim().toLowerCase();let items=(BRANCHES||[]).filter(b=>!q||b.name.toLowerCase().includes(q)).slice(0,30);if(!items.length){box.innerHTML='<div class="opt"><span>موردی یافت نشد — نام کامل را تایپ و «افزودن برنچ» را بزنید</span></div>';box.classList.add('open');return}box.innerHTML=items.map((b,i)=>'<div class="opt" data-i="'+i+'"><span>'+esc(b.name)+'</span><span class="meta">'+(b.protected?'protected':'')+'</span></div>').join('');box.querySelectorAll('.opt').forEach(el=>{el.onmousedown=e=>{e.preventDefault();let b=items[+el.dataset.i];if(b){$('branchPick').value=b.name;closeDrops()}}});box.classList.add('open')}
async function loadFiles(){let repo=($('repo').value||'').trim(),br=uiBranches();if(!repo||!br.length)return;try{let d=await api('api/files?repo='+encodeURIComponent(repo)+'&branch='+encodeURIComponent(br[0]));FILES=d.files||[];$('fileCount').textContent=FILES.length?toFa(FILES.length)+' فایل Python در برنچ '+br[0]:''}catch(e){FILES=[];$('fileCount').textContent=''}filterFiles()}
function filterFiles(){let box=$('fileDrop');if(!box)return;let q=($('path').value||'').trim().toLowerCase();let items=(FILES||[]).filter(f=>!q||f.toLowerCase().includes(q)).slice(0,30);if(!items.length){box.classList.remove('open');return}box.innerHTML=items.map((f,i)=>'<div class="opt" data-i="'+i+'"><span>'+esc(f)+'</span></div>').join('');box.querySelectorAll('.opt').forEach(el=>{el.onmousedown=e=>{e.preventDefault();let f=items[+el.dataset.i];if(f){$('path').value=f;closeDrops()}}});box.classList.add('open')}
async function saveSettings(silent){try{let t=$('token').value.trim();let body={repo:$('repo').value.trim(),branches:uiBranches(),path:$('path').value.trim(),check_on_load:$('autoCheck').checked,auto_update:$('autoUpdate').checked,auto_interval:+$('autoInterval').value||300};if(t==='__CLEAR__')body.clear_token=true;else if(t)body.github_token=t;else body.github_token='';if(!body.github_token)delete body.github_token;await api('api/settings',{method:'POST',body:JSON.stringify(body)});$('token').value='';renderChips();if(!silent){$('status').innerHTML='<span class="ok">تنظیمات ذخیره شد.</span>';showToast('✓ ذخیره شد')}}catch(e){if(!silent){$('status').innerHTML='<span class="error">'+esc(e.message)+'</span>';showToast(e.message,1)}throw e}}
async function check(manual){try{$('status').textContent='در حال بررسی همه برنچ‌ها…';let d=await api('api/check',{method:'POST',body:'{}'});renderChips(d.newest_branch);
$('localBox').innerHTML='فایل اصلی: <b>'+esc(d.target||'scraper4.py')+'</b> · نسخه محلی <b>v'+esc(d.local_version)+'</b><br><span>SHA محلی:</span> '+esc(d.local_sha||'—')+'<br><span>جدیدترین:</span> v'+esc(d.newest_version||'?')+' در برنچ '+esc(d.newest_branch||'—');
if(d.local_error)$('localBox').innerHTML+='<br><span class="error">'+esc(d.local_error)+'</span>';
if(!d.update_available){$('status').innerHTML='<span class="ok">✓ به‌روز است — v'+esc(d.local_version)+'</span>';$('updateBtn').classList.add('hidden');hideBanner()}
else{$('status').innerHTML='⬆ نسخه جدید: <b>v'+esc(d.newest_version||'?')+'</b> در برنچ <code>'+esc(d.newest_branch||'')+'</code> · جاری v'+esc(d.local_version);$('updateBtn').classList.remove('hidden');showBanner('⬆ نسخه جدید v'+(d.newest_version||'')+' در برنچ '+(d.newest_branch||'')+' موجود است')}
$('cands').innerHTML=(d.candidates||[]).map(c=>{if(c.error)return '<div class="cand"><div><b dir="ltr">'+esc(c.branch)+'</b><small class="error">'+esc(c.error)+'</small></div></div>';let isN=c.branch===(d.newest_branch||'');return '<div class="cand'+(isN?' new':'')+'"><div><b dir="ltr">'+esc(c.branch)+'</b>'+(isN?' <span style="color:var(--green);font-size:10px">★ جدیدترین</span>':'')+'<small>نسخه: <b>v'+esc(c.version||'?')+'</b> · SHA: <code>'+esc((c.sha||'').slice(0,10))+'</code> · '+(c.update_available?'متفاوت از فایل اصلی':'یکسان')+'</small></div><div><button class="gray" onclick="updateBranch(\''+esc(c.branch)+'\')">نصب این برنچ</button></div></div>'}).join('')||'<div class="note">کاندیدی یافت نشد.</div>';
if(manual&&d.update_available)showToast('⬆ نسخه جدید v'+(d.newest_version||'')+' آماده نصب است');return d}catch(e){$('status').innerHTML='<span class="error">'+esc(e.message)+'</span>';if(manual)showToast(e.message,1);throw e}}
async function checkInstall(manual){let b=$('mainBtn');if(b){b.disabled=true;b.textContent='⏳ در حال بررسی…'}try{let d=await check(false);if(!d.update_available){if(manual)showToast('✓ به‌روز است');return d}await updateNewest()}catch(e){}finally{if(b){b.disabled=false;b.textContent='🔍 بررسی و نصب نسخهٔ جدید'}}}
async function updateNewest(){let t='';try{let d0=await api('api/check',{method:'POST',body:'{}'});if(d0&&d0.newest_branch)t=d0.newest_branch}catch(e){}await updateBranch(t)}
async function updateBranch(branch){let label=branch?(' برنچ '+branch):' جدیدترین نسخه';if(!confirm('فایل اصلی جایگزین و نسخه قبلی در .bak ذخیره شود؟\n\nمقصد:'+label))return;try{$('status').textContent='در حال دانلود، اعتبارسنجی و نصب'+label+'…';let d=await api('api/update',{method:'POST',body:JSON.stringify(branch?{branch}:{})});$('status').innerHTML='<span class="ok">'+esc(d.message)+' — نسخه '+esc(d.version)+'</span>\n'+(d.reload_requested?'درخواست reload فرستاده شد؛ چند ثانیه بعد صفحه را رفرش کنید.':'فایل WSGI تنظیم نشده؛ از تب Web دکمه Reload را بزنید.');hideBanner();$('updateBtn').classList.add('hidden');showToast('✓ نصب شد: v'+d.version)}catch(e){$('status').innerHTML='<span class="error">'+esc(e.message)+'</span>';showToast(e.message,1)}}
async function rollback(){if(!confirm('نسخه scraper4.py.bak بازیابی شود؟'))return;try{let d=await api('api/rollback',{method:'POST',body:'{}'});$('status').innerHTML='<span class="ok">'+esc(d.message)+' — نسخه '+esc(d.version)+'</span>'}catch(e){$('status').innerHTML='<span class="error">'+esc(e.message)+'</span>';showToast(e.message,1)}}
async function autoRun(){try{$('status').textContent='در حال اجرای چرخه خودکار…';let d=await api('api/auto/run',{method:'POST',body:'{}'});$('status').innerHTML='<span class="ok">'+esc(d.result||'انجام شد')+'</span>';check(false).catch(()=>{})}catch(e){$('status').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function init(){let d=await api('api/config');$('depVer').textContent='v'+(d.deployer||'1.0.0');$('repo').value=d.repo||'';$('branches').value=(d.branches||[]).join('\n');$('path').value=d.path||'';$('autoCheck').checked=!!d.check_on_load;$('autoUpdate').checked=d.auto_update!==false;$('autoInterval').value=d.auto_interval||300;$('token').placeholder=d.has_token?'توکن تنظیم شده؛ خالی = نگه‌داشتن':'GitHub token اختیاری';$('foot').innerHTML='هدف: <code>'+esc(d.target||'')+'</code> · خودکار: '+(d.auto_update?'روشن':'خاموش')+' · آخرین چرخه: '+esc(d.auto_state?.last_result||'—');renderChips();await loadBranches(false).catch(()=>{});await loadFiles().catch(()=>{});try{$('localBox').innerHTML='نسخه محلی: <b>v'+esc(d.local_version)+'</b> · SHA: '+esc((d.local_sha||'').slice(0,12)||'—')}catch(e){}}
init().then(()=>{if($('autoCheck').checked)check(false).catch(()=>{})}).catch(e=>{$('status').innerHTML='<span class="error">'+esc(e.message)+'</span>'});
</script></body></html>'''


if __name__ == "__main__":
    # Local testing only (PythonAnywhere imports `app`; see setup_deployer4.sh).
    threading.Thread(target=auto_loop, name="deployer4-auto", daemon=True).start()
    app.run(host="0.0.0.0", port=int(os.environ.get("PORT", "8001")), debug=False)
else:
    # Under WSGI the auto loop starts on first import.
    if os.environ.get("DEPLOYER_AUTO_START", "1").lower() not in {"0", "false", "off", "no"}:
        threading.Thread(target=auto_loop, name="deployer4-auto", daemon=True).start()


@app.get("/")
def index():
    return Response(INDEX_HTML, mimetype="text/html; charset=utf-8")
