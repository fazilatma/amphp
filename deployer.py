#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Deployer — Auto-discover newest scraper4.py across all repo branches and install it.

کنار فایل اصلی قرار می‌گیرد و بطور خودکار همه برنچ‌های ریپو را سرچ می‌کند،
جدیدترین فایل (بر اساس APP_VERSION) را پیدا کرده و اتوماتیک نصب می‌کند.

Usage:
  python3 deployer.py --scan
  python3 deployer.py --install [--branch BRANCH] [--force]
  python3 deployer.py --auto   # scan + install newest if newer than local

On PythonAnywhere:
  curl -fsSL https://raw.githubusercontent.com/fazilatma/amphp/arena/01a06927-amphp/deployer.py -o ~/scraper4/deployer.py
  python3 ~/scraper4/deployer.py --auto

Environment:
  SCRAPER_REPO=owner/repo (default fazilatma/amphp)
  SCRAPER_BRANCH=fallback branch
  GITHUB_TOKEN=optional token for higher rate limit
  SCRAPER_APP_DIR=~/scraper4
  SCRAPER_DATA_FILE=~/scraper4/scraper4_data.json
"""

import os
import re
import sys
import json
import time
import hashlib
import argparse
import urllib.parse
from datetime import datetime
from typing import Dict, List, Optional, Tuple, Any

try:
    import requests
except ImportError:
    print("requests required: pip install requests")
    sys.exit(1)

REPO = os.environ.get("SCRAPER_REPO", "fazilatma/amphp")
DEFAULT_BRANCH = os.environ.get("SCRAPER_BRANCH", "arena/01a06927-amphp")
APP_FILE_NAME = "scraper4.py"
GITHUB_API = "https://api.github.com"
RAW_BASE = "https://raw.githubusercontent.com"

def log(msg: str):
    print(f"[deployer {datetime.now().strftime('%H:%M:%S')}] {msg}")

def fail(msg: str, code: int = 1):
    print(f"ERROR: {msg}", file=sys.stderr)
    sys.exit(code)

def parse_version(v: str) -> Tuple[int, ...]:
    """Parse semantic version like 5.2.0 into tuple for comparison."""
    v = (v or "").strip().strip('"\'')
    # Extract numbers
    m = re.search(r'(\d+)\.(\d+)\.(\d+)', v)
    if m:
        return tuple(int(x) for x in m.groups())
    # Fallback: try to find any numbers
    nums = re.findall(r'\d+', v)
    if nums:
        return tuple(int(x) for x in nums[:3])
    return (0, 0, 0)

def version_str(t: Tuple[int, ...]) -> str:
    return ".".join(str(x) for x in t)

def github_headers() -> Dict[str, str]:
    h = {"Accept": "application/vnd.github.v3+json", "User-Agent": "scraper4-deployer"}
    token = os.environ.get("GITHUB_TOKEN") or os.environ.get("SCRAPER_GITHUB_TOKEN") or ""
    token = token.strip()
    if token:
        h["Authorization"] = f"token {token}"
    return h

def list_branches(repo: str = REPO) -> List[Dict[str, Any]]:
    """List all branches via GitHub API with pagination."""
    branches = []
    page = 1
    per_page = 100
    while True:
        url = f"{GITHUB_API}/repos/{repo}/branches?per_page={per_page}&page={page}"
        log(f"Fetching branches page {page}: {url}")
        try:
            r = requests.get(url, headers=github_headers(), timeout=20)
        except Exception as e:
            fail(f"GitHub API error: {e}")
        if r.status_code == 403 and "rate limit" in r.text.lower():
            fail(f"GitHub rate limit hit. Set GITHUB_TOKEN env var. Body: {r.text[:200]}")
        if r.status_code != 200:
            fail(f"GitHub API {r.status_code}: {r.text[:300]}")
        data = r.json()
        if not isinstance(data, list) or not data:
            break
        branches.extend(data)
        if len(data) < per_page:
            break
        page += 1
        if page > 20:  # safety
            break
    return branches

def fetch_file_from_branch(repo: str, branch: str, path: str = APP_FILE_NAME) -> Optional[bytes]:
    """Fetch raw file content from a branch."""
    # Encode branch for URL (branches may contain slashes)
    # raw.githubusercontent.com uses branch as path, not encoded for slashes? Actually need to keep slashes
    # Use quote with safe="/"
    branch_enc = urllib.parse.quote(branch, safe="/")
    url = f"{RAW_BASE}/{repo}/{branch_enc}/{path}"
    try:
        r = requests.get(url, timeout=30, headers={"User-Agent": "scraper4-deployer"})
        if r.status_code == 200 and r.content:
            return r.content
    except Exception:
        pass
    return None

def extract_app_version(content: bytes) -> Optional[str]:
    """Extract APP_VERSION from file content."""
    try:
        text = content.decode('utf-8', errors='ignore')
    except:
        return None
    # Look for APP_VERSION = "x.y.z"
    m = re.search(r'APP_VERSION\s*=\s*["\']([^"\']+)["\']', text)
    if m:
        return m.group(1).strip()
    return None

def scan_all_branches(repo: str = REPO, filter_zip: bool = False, filter_keywords: str = "") -> List[Dict[str, Any]]:
    """
    Scan all branches and find versions.
    If filter_zip True, only branches containing 'zip' or 'arena' are considered.
    filter_keywords: comma separated keywords to filter branches (like zip,arena,deploy).
    """
    branches_data = list_branches(repo)
    log(f"Found {len(branches_data)} branches in {repo}")
    # Build keyword list
    kw_list: List[str] = []
    if filter_keywords:
        kw_list = [k.strip().lower() for k in filter_keywords.split(",") if k.strip()]
    elif filter_zip:
        kw_list = ["zip", "arena", "deploy"]
    if kw_list:
        filtered = [b for b in branches_data if any(kw in (b.get("name") or "").lower() for kw in kw_list)]
        if filtered:
            branches_data = filtered
            log(f"Filtered to {len(branches_data)} branches by keywords {kw_list}")
    results = []
    for b in branches_data:
        name = b.get("name") or ""
        if not name:
            continue
        # Fetch file
        content = fetch_file_from_branch(repo, name, APP_FILE_NAME)
        if not content:
            # Try to check if branch has file via API contents check to avoid 404 spam?
            continue
        ver = extract_app_version(content)
        if not ver:
            continue
        ver_tuple = parse_version(ver)
        sha = (b.get("commit", {}) or {}).get("sha", "")[:7]
        results.append({
            "branch": name,
            "version": ver,
            "version_tuple": ver_tuple,
            "sha": sha,
            "size": len(content),
            "content": content  # keep for install if needed, but may be large
        })
        log(f"  {name} -> {ver} ({sha}) {len(content)} bytes")
        # Be nice to GitHub API
        time.sleep(0.2)
    # Sort by version descending, then by branch name
    results.sort(key=lambda x: (x["version_tuple"], x["branch"]), reverse=True)
    return results

def get_local_version(app_dir: Optional[str] = None) -> Tuple[str, Tuple[int, ...]]:
    """Get local installed version."""
    if app_dir is None:
        app_dir = os.environ.get("SCRAPER_APP_DIR") or os.path.join(os.path.expanduser("~"), "scraper4")
    app_file = os.path.join(app_dir, APP_FILE_NAME)
    if not os.path.isfile(app_file):
        return ("0.0.0", (0, 0, 0))
    try:
        with open(app_file, "rb") as f:
            content = f.read()
        ver = extract_app_version(content) or "0.0.0"
        return (ver, parse_version(ver))
    except:
        return ("0.0.0", (0, 0, 0))

def install_content(content: bytes, app_dir: Optional[str] = None, backup: bool = True) -> str:
    """Install given content atomically."""
    if app_dir is None:
        app_dir = os.environ.get("SCRAPER_APP_DIR") or os.path.join(os.path.expanduser("~"), "scraper4")
    os.makedirs(app_dir, exist_ok=True)
    app_file = os.path.join(app_dir, APP_FILE_NAME)
    # Validate
    if b"APP_VERSION" not in content or b"Flask(" not in content:
        fail("Downloaded file doesn't look like scraper4.py (missing markers)")
    try:
        import ast
        ast.parse(content.decode('utf-8', errors='ignore'))
    except SyntaxError as e:
        fail(f"Downloaded file has syntax error: {e}")

    if backup and os.path.isfile(app_file):
        bak = f"{app_file}.{datetime.now().strftime('%Y%m%d-%H%M%S')}.bak"
        try:
            import shutil
            shutil.copy2(app_file, bak)
            log(f"Backup written: {bak}")
        except Exception as e:
            log(f"Backup failed: {e}")

    tmp = f"{app_file}.new.{os.getpid()}"
    with open(tmp, "wb") as f:
        f.write(content)
    os.chmod(tmp, 0o600)
    os.replace(tmp, app_file)
    log(f"Installed to {app_file} ({len(content)} bytes)")
    # Update deploy branch in data file
    data_file = os.environ.get("SCRAPER_DATA_FILE") or os.path.join(app_dir, "scraper4_data.json")
    try:
        import json as js
        data = {}
        if os.path.isfile(data_file):
            try:
                with open(data_file, "r", encoding="utf-8") as df:
                    data = js.load(df)
                    if not isinstance(data, dict):
                        data = {}
            except:
                data = {}
        # Ensure deploy info exists
        deploy = data.get("deploy") if isinstance(data.get("deploy"), dict) else {}
        # We'll update branch to newest installed branch if provided via env
        # Keep existing repo
        data["deploy"] = deploy
        with open(data_file + ".tmp", "w", encoding="utf-8") as out:
            js.dump(data, out, ensure_ascii=False, indent=2)
        os.replace(data_file + ".tmp", data_file)
        os.chmod(data_file, 0o600)
    except Exception as e:
        log(f"Could not update data file: {e}")

    # Try to reload webapp if on PythonAnywhere
    try:
        user = os.environ.get("USER") or os.getlogin()
    except:
        user = "Fazilatma"
    user_lower = user.lower()
    token_file = os.path.join(os.path.expanduser("~"), ".pythonanywhere_api_token")
    if os.path.isfile(token_file):
        try:
            token = open(token_file, "r").read().strip()
            if token:
                import subprocess
                # Use curl via API
                domain = f"{user_lower}.pythonanywhere.com"
                api_url = f"https://www.pythonanywhere.com/api/v0/user/{user}/webapps/{domain}/reload/"
                log(f"Reloading webapp {domain} via API...")
                # Use requests if available
                headers = {"Authorization": f"Token {token}"}
                r = requests.post(api_url, headers=headers, timeout=20)
                if r.status_code in (200, 201):
                    log(f"Reload OK: {r.status_code}")
                else:
                    log(f"Reload API returned {r.status_code}: {r.text[:200]}")
        except Exception as e:
            log(f"Reload via API failed: {e}, trying touch WSGI")
            try:
                import pathlib
                for p in [f"/var/www/{user_lower}_pythonanywhere_com_wsgi.py", f"/var/www/{user}_pythonanywhere_com_wsgi.py"]:
                    if os.path.isfile(p):
                        os.utime(p, None)
                        log(f"Touched {p}")
                        break
            except Exception as e2:
                log(f"Touch WSGI failed: {e2}")
    else:
        # Fallback touch
        try:
            for p in [f"/var/www/{user_lower}_pythonanywhere_com_wsgi.py"]:
                if os.path.isfile(p):
                    os.utime(p, None)
                    log(f"Touched {p}")
        except:
            pass

    return app_file

def main():
    parser = argparse.ArgumentParser(description="Deployer - find newest scraper4.py across branches")
    parser.add_argument("--scan", action="store_true", help="Scan all branches and list versions")
    parser.add_argument("--filter-zip", action="store_true", help="Only consider branches containing zip/arena (alias for --filter zip,arena,deploy)")
    parser.add_argument("--filter", type=str, default="", dest="filter", help="Comma separated keywords to filter branches, e.g. zip,arena,deploy")
    parser.add_argument("--install", action="store_true", help="Install a branch (default newest)")
    parser.add_argument("--branch", type=str, default="", help="Specific branch to install")
    parser.add_argument("--auto", action="store_true", help="Auto: scan and install if newer than local")
    parser.add_argument("--force", action="store_true", help="Force install even if same version")
    parser.add_argument("--repo", type=str, default=REPO, help="GitHub repo owner/name")
    parser.add_argument("--json", action="store_true", help="Output JSON for API")
    args = parser.parse_args()

    repo = args.repo

    if args.scan or args.auto or (not args.install and not args.branch):
        # Scan mode
        results = scan_all_branches(repo, filter_zip=args.filter_zip, filter_keywords=args.filter)
        if not results:
            log("No branches with valid scraper4.py found")
            if args.json:
                print(json.dumps({"ok": False, "error": "no branches found", "repo": repo}))
            return

        newest = results[0]
        local_ver_str, local_ver_tuple = get_local_version()

        if args.json:
            out = {
                "ok": True,
                "repo": repo,
                "local_version": local_ver_str,
                "local_tuple": local_ver_tuple,
                "newest": {k: v for k, v in newest.items() if k != "content"},
                "all": [{k: v for k, v in r.items() if k != "content"} for r in results]
            }
            print(json.dumps(out, ensure_ascii=False, indent=2))
        else:
            print("\n" + "="*70)
            print(f"Repo: {repo}")
            print(f"Local version: {local_ver_str} {local_ver_tuple}")
            print(f"Newest remote: {newest['branch']} -> {newest['version']} {newest['version_tuple']} ({newest['sha']})")
            print("="*70)
            for r in results[:20]:
                marker = " <-- NEWEST" if r == newest else ""
                print(f"{r['branch']:45} {r['version']:12} {r['sha']:8} {r['size']:8} bytes{marker}")
            print("="*70)

        if args.auto:
            if newest["version_tuple"] > local_ver_tuple or args.force:
                log(f"Auto-installing newest {newest['branch']} ({newest['version']}) over local {local_ver_str}")
                install_content(newest["content"])
                # Verify
                new_local_str, _ = get_local_version()
                log(f"After install local version: {new_local_str}")
            else:
                log(f"Local {local_ver_str} is already newest or newer than remote {newest['version']}, skipping (use --force to override)")

    if args.install:
        branch = args.branch.strip()
        if branch:
            log(f"Installing specific branch: {branch}")
            content = fetch_file_from_branch(repo, branch, APP_FILE_NAME)
            if not content:
                fail(f"Could not fetch {APP_FILE_NAME} from branch {branch}")
            ver = extract_app_version(content) or "unknown"
            log(f"Fetched {branch} version {ver} {len(content)} bytes")
            local_ver_str, local_ver_tuple = get_local_version()
            remote_tuple = parse_version(ver)
            if not args.force and remote_tuple <= local_ver_tuple:
                log(f"Local {local_ver_str} >= remote {ver}, use --force to override")
                if not args.force:
                    return
            install_content(content)
        else:
            # Install newest
            results = scan_all_branches(repo, filter_zip=args.filter_zip, filter_keywords=args.filter)
            if not results:
                fail("No branches found to install")
            newest = results[0]
            local_ver_str, local_ver_tuple = get_local_version()
            if not args.force and newest["version_tuple"] <= local_ver_tuple:
                log(f"Local {local_ver_str} >= newest {newest['version']}, use --force")
                return
            log(f"Installing newest {newest['branch']} {newest['version']}")
            install_content(newest["content"])

if __name__ == "__main__":
    main()
