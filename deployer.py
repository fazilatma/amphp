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

DEPLOYER_HTML = r"""
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>📦 دیپلوی خودکار — انتخاب برنچ</title>
<style>
  body{font-family:Vazirmatn,Tahoma,sans-serif;background:#0f172a;color:#e2e8f0;margin:0;padding:16px}
  .card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:16px;margin-bottom:14px}
  .btn{border:0;border-radius:10px;padding:8px 14px;cursor:pointer;font-weight:700}
  .btn-primary{background:#38bdf8;color:#000}.btn-accent{background:#a78bfa;color:#000}.btn-danger{background:#f87171;color:#000}
  .chip{display:inline-block;background:rgba(255,255,255,.1);border-radius:999px;padding:2px 8px;font-size:11px;margin:2px}
  .chip-success{background:rgba(34,197,94,.2);color:#86efac}.chip-primary{background:rgba(56,189,248,.2);color:#7dd3fc}
  .row{display:flex;justify-content:space-between;align-items:center;gap:8px;padding:10px;border:1px solid rgba(255,255,255,.1);border-radius:12px;margin-bottom:8px}
  .row.is-newest{border-color:#a78bfa; background:rgba(167,139,250,.12)}
  select,input{width:100%;padding:8px;border-radius:10px;border:1px solid rgba(255,255,255,.15);background:#1e293b;color:#e2e8f0}
  #log{white-space:pre-wrap;font-family:monospace;font-size:11px;max-height:220px;overflow:auto;background:#020617;padding:10px;border-radius:10px}
</style>
</head>
<body>
<h2>📦 دیپلوی خودکار — همه برنچ‌ها با شماره نسخه</h2>
<div class="card">
  <div style="display:flex;gap:8px;flex-wrap:wrap">
    <input id="repo" value="fazilatma/amphp" style="flex:1;min-width:180px" dir="ltr" placeholder="owner/repo">
    <input id="filter" value="zip,arena,deploy" style="flex:1;min-width:180px" placeholder="فیلتر: zip,arena">
    <button class="btn btn-primary" onclick="scan()">🔍 اسکن همه برنچ‌ها</button>
    <button class="btn btn-accent" onclick="autoInstall()">🚀 نصب خودکار جدیدترین</button>
  </div>
  <div id="status" style="margin-top:10px"></div>
</div>

<div class="card">
  <div style="font-weight:800;margin-bottom:8px">🎯 انتخاب دستی برنچ</div>
  <select id="branchSelect" onchange="onBranchChange()"><option>— ابتدا اسکن کنید —</option></select>
  <div id="branchMeta" style="margin-top:8px;font-size:12px;color:#94a3b8"></div>
  <div style="margin-top:10px;display:flex;gap:8px;flex-wrap:wrap">
    <button class="btn btn-accent" onclick="installSelected()">⬇️ نصب انتخاب‌شده</button>
    <button class="btn btn-danger" onclick="installSelected(true)">⚡ نصب اجباری</button>
  </div>
</div>

<div class="card">
  <div style="font-weight:800;margin-bottom:8px">📋 لیست برنچ‌ها (با نسخه)</div>
  <div id="table"></div>
</div>

<div class="card"><div style="font-weight:800;margin-bottom:6px">📜 لاگ</div><div id="log"></div></div>

<script>
let last = null;
function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}
function toFa(n){return String(n).replace(/\d/g,d=>'۰۱۲۳۴۵۶۷۸۹'[d])}
function logLine(t){const el=document.getElementById('log'); el.textContent += '['+new Date().toLocaleTimeString('fa-IR')+'] '+t+'\n'; el.scrollTop=el.scrollHeight}

async function scan(){
  const repo=document.getElementById('repo').value.trim()||'fazilatma/amphp';
  const filter=document.getElementById('filter').value.trim();
  const status=document.getElementById('status');
  const table=document.getElementById('table');
  const sel=document.getElementById('branchSelect');
  status.textContent='⏳ در حال اسکن...'; table.innerHTML='<span class="chip">بارگذاری...</span>';
  sel.innerHTML='<option>— در حال اسکن... —</option>';
  try{
    const res=await fetch('/api/scan',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({repo,filter})});
    const j=await res.json();
    if(!j.ok){status.textContent='❌ '+j.error; table.innerHTML=`<span class="chip chip-danger">${esc(j.error)}</span>`; return}
    last=j;
    const newest=j.newest;
    status.innerHTML=`محلی: <b>${esc(j.local_version)}</b> | جدیدترین: <b>${esc(newest?.version||'')} (${esc(newest?.branch||'')})</b> | ${toFa(j.count)} برنچ`;
    logLine(`اسکن کامل: ${j.count} برنچ، جدیدترین ${newest?.branch} v${newest?.version}`);
    const rows=j.branches||[];
    sel.innerHTML=rows.map(r=>{
      const isNew=r.branch===newest?.branch;
      return `<option value="${esc(r.branch)}" data-version="${esc(r.version)}" data-size="${r.size}" data-sha="${esc(r.sha||'')}" ${isNew?'selected':''}>${esc(r.branch)} — v${esc(r.version)}${isNew?' ⭐':''} (${Math.round(r.size/1024)}KB)</option>`;
    }).join('')||'<option>— نتیجه‌ای نیست —</option>';
    onBranchChange();
    table.innerHTML=rows.map(r=>{
      const isNew=r.branch===newest?.branch;
      return `<div class="row ${isNew?'is-newest':''}">
        <div style="flex:1"><b dir="ltr">${esc(r.branch)}</b> <span style="color:#38bdf8;font-weight:800">v${esc(r.version)}</span> ${isNew?'<span class="chip chip-success">جدیدترین ⭐</span>':''}
          <div style="margin-top:4px"><span class="chip">${toFa(Math.round(r.size/1024))}KB</span> ${r.sha?`<span class="chip" dir="ltr">${esc(r.sha)}</span>`:''} <span class="chip chip-primary" dir="ltr">${esc((r.version_tuple||[]).join('.'))}</span></div>
        </div>
        <div style="display:flex;gap:6px"><button class="btn btn-primary" onclick="pick('${esc(r.branch).replace(/'/g,'&#39;')}')">👁</button><button class="btn btn-accent" onclick="installBranch('${esc(r.branch).replace(/'/g,'&#39;')}')">⬇️ نصب</button></div>
      </div>`;
    }).join('')||'<span class="chip">نتیجه‌ای نیست</span>';
  }catch(e){status.textContent='خطای شبکه: '+e.message}
}
function pick(b){const sel=document.getElementById('branchSelect'); sel.value=b; onBranchChange(); sel.scrollIntoView({behavior:'smooth'})}
function onBranchChange(){const sel=document.getElementById('branchSelect'); const meta=document.getElementById('branchMeta'); const opt=sel.options[sel.selectedIndex]; if(!opt||!opt.value){meta.textContent='';return} meta.innerHTML=`نسخه: <b style="color:#38bdf8">v${esc(opt.dataset.version)}</b> | حجم: ${toFa(Math.round(parseInt(opt.dataset.size||'0')/1024))}KB | SHA: <span dir="ltr">${esc(opt.dataset.sha)}</span>`}
async function installBranch(branch,force){if(!branch)return; if(!confirm(`برنچ «${branch}» نصب شود؟`))return; await doInstall(branch,force)}
async function installSelected(force){const sel=document.getElementById('branchSelect'); if(!sel.value){alert('ابتدا برنچ انتخاب کنید');return} await installBranch(sel.value,force)}
async function doInstall(branch,force){const repo=document.getElementById('repo').value.trim()||'fazilatma/amphp'; const status=document.getElementById('status'); status.textContent=`⏳ نصب ${branch}...`; logLine(`نصب ${branch} force=${!!force}`); try{const res=await fetch('/api/install',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({repo,branch,force:!!force})}); const j=await res.json(); if(j.ok&&j.changed){status.innerHTML=`✅ ${esc(j.message)}<br>محلی ${esc(j.local_version)} → ${esc(j.remote_version)}`; logLine(j.message); setTimeout(()=>location.reload(),2000)}else if(j.ok){status.textContent=j.message; logLine(j.message)}else{status.textContent='❌ '+j.error; logLine('خطا: '+j.error)}}catch(e){status.textContent='خطای شبکه: '+e.message}}
async function autoInstall(){const repo=document.getElementById('repo').value.trim()||'fazilatma/amphp'; const filter=document.getElementById('filter').value.trim(); if(!confirm('نصب خودکار جدیدترین نسخه؟'))return; const status=document.getElementById('status'); status.textContent='⏳ نصب خودکار...'; try{const res=await fetch('/api/install',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({repo,filter})}); const j=await res.json(); if(j.ok&&j.changed){status.innerHTML=`✅ ${esc(j.message)}`; logLine(j.message); setTimeout(()=>location.reload(),2000)}else{status.textContent=j.message||j.error; logLine(j.message||j.error)}}catch(e){status.textContent='خطای شبکه: '+e.message}}
window.addEventListener('DOMContentLoaded',()=>{/* auto scan on load */});
</script>
</body>
</html>
"""

def serve_ui(port: int = 5001, repo: str = REPO, filter_kw: str = "", token: str = ""):
    try:
        from flask import Flask, request, jsonify, Response
    except ImportError:
        fail("Flask required for --serve : pip install flask")
    app = Flask(__name__)

    @app.get("/")
    def index():
        return Response(DEPLOYER_HTML, mimetype="text/html; charset=utf-8")

    @app.post("/api/scan")
    def api_scan():
        body = request.get_json(silent=True) or {}
        r_repo = (body.get("repo") or repo).strip() or REPO
        r_filter = (body.get("filter") or filter_kw).strip()
        r_token = (body.get("token") or token).strip()
        # Temporarily set env token if provided
        if r_token:
            os.environ["GITHUB_TOKEN"] = r_token
        results = scan_all_branches(r_repo, filter_keywords=r_filter)
        # Strip content for response
        local_ver, local_tuple = get_local_version()
        # Find newest
        newest = results[0] if results else None
        resp = {
            "ok": True,
            "repo": r_repo,
            "local_version": local_ver,
            "local_tuple": local_tuple,
            "count": len(results),
            "newest": {k: v for k, v in newest.items() if k != "content"} if newest else None,
            "branches": [{k: v for k, v in r.items() if k != "content"} for r in results],
        }
        return jsonify(resp)

    @app.post("/api/install")
    def api_install():
        body = request.get_json(silent=True) or {}
        r_repo = (body.get("repo") or repo).strip() or REPO
        r_branch = (body.get("branch") or "").strip()
        r_filter = (body.get("filter") or filter_kw).strip()
        r_force = bool(body.get("force", False))
        r_token = (body.get("token") or token).strip()
        if r_token:
            os.environ["GITHUB_TOKEN"] = r_token
        try:
            if not r_branch:
                results = scan_all_branches(r_repo, filter_keywords=r_filter)
                if not results:
                    return jsonify(ok=False, error="هیچ برنچی پیدا نشد"), 404
                newest = results[0]
                r_branch = newest["branch"]
                content = newest["content"]
                remote_ver = newest["version"]
                remote_tuple = newest["version_tuple"]
            else:
                content = fetch_file_from_branch(r_repo, r_branch, APP_FILE_NAME)
                if not content:
                    return jsonify(ok=False, error=f"فایل از برنچ {r_branch} دریافت نشد"), 404
                remote_ver = extract_app_version(content) or "unknown"
                remote_tuple = parse_version(remote_ver)
            local_ver, local_tuple = get_local_version()
            if not r_force and remote_tuple <= local_tuple:
                return jsonify(ok=True, changed=False, message=f"محلی {local_ver} جدیدتر یا مساوی {remote_ver} است", local_version=local_ver, remote_version=remote_ver, branch=r_branch)
            install_content(content)
            new_local, _ = get_local_version()
            return jsonify(ok=True, changed=True, message=f"✅ {r_branch} v{remote_ver} نصب شد", local_version=local_ver, remote_version=remote_ver, branch=r_branch)
        except Exception as e:
            return jsonify(ok=False, error=f"{type(e).__name__}: {e}"), 500

    print(f"Serving deployer UI on http://0.0.0.0:{port} — repo={repo}")
    app.run(host="0.0.0.0", port=port, debug=False)

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
    parser.add_argument("--serve", action="store_true", help="Run mini web UI with branch picker showing versions")
    parser.add_argument("--port", type=int, default=5001, help="Port for --serve (default 5001)")
    args = parser.parse_args()

    repo = args.repo

    if args.serve:
        token = os.environ.get("GITHUB_TOKEN") or os.environ.get("SCRAPER_GITHUB_TOKEN") or ""
        serve_ui(port=args.port, repo=repo, filter_kw=args.filter or ("zip,arena,deploy" if args.filter_zip else ""), token=token)
        return

    if args.scan or args.auto or (not args.install and not args.branch and not args.serve):
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
