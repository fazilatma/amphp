#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Deployer — standalone auto-discover & installer for any branch/file.

این فایل کاملاً مستقل از scraper4.py است و آدرس جداگانه دارد.
چون برنچ‌های مختلف ممکن است فایل‌های اسکریپر کاملاً متفاوت تولید کنند،
دیپلوی به هیچ فایل خاصی لینک نیست — نام فایل هدف را خودتان می‌دهید
(پیش‌فرض scraper4.py) و هر تب می‌تواند فیلتر/فایل جدا داشته باشد.

Usage console:
  python3 deployer.py --scan --filter arena --file scraper4.py
  python3 deployer.py --auto --filter-zip --file scraper4.py --repo fazilatma/amphp
  python3 deployer.py --install --branch arena/01a06927-amphp --file scraper4.py --target ~/amphp/scraper4.py --force

Web UI (standalone address, multi-branch simultaneous):
  python3 deployer.py --serve --port 8055 --repo fazilatma/amphp
  # then open / (supports ?repo=&filter=&file=&branch= for each tab)

PythonAnywhere WSGI (separate address, survives scraper overwrites):
  # deployer lives at /var/www/..._wsgi_deployer.py  OR mounted via DispatcherMiddleware
  from deployer import app as application

Env:
  SCRAPER_REPO / DEPLOYER_REPO, GITHUB_TOKEN, SCRAPER_APP_DIR, DEPLOYER_TARGET, DEPLOYER_FILE
"""

import os
import re
import sys
import json
import time
import argparse
import urllib.parse
from datetime import datetime
from typing import Dict, List, Optional, Tuple, Any

try:
    import requests
except ImportError:
    print("requests required: pip install requests")
    sys.exit(1)

REPO = os.environ.get("DEPLOYER_REPO") or os.environ.get("SCRAPER_REPO") or "fazilatma/amphp"
DEFAULT_BRANCH = os.environ.get("SCRAPER_BRANCH", "arena/01a06927-amphp")
DEFAULT_FILE = os.environ.get("DEPLOYER_FILE") or os.environ.get("DEPLOYER_TARGET_FILE") or "scraper4.py"
DEPLOYER_VERSION = "1.1.0"
DEFAULT_TARGET = os.environ.get("DEPLOYER_TARGET") or os.path.join(os.path.expanduser("~"), "amphp", "scraper4.py")
GITHUB_API = "https://api.github.com"
RAW_BASE = "https://raw.githubusercontent.com"

def log(msg: str):
    print(f"[deployer {datetime.now().strftime('%H:%M:%S')}] {msg}")

def fail(msg: str, code: int = 1):
    print(f"ERROR: {msg}", file=sys.stderr)
    sys.exit(code)

def parse_version(v: str) -> Tuple[int, ...]:
    v = (v or "").strip().strip('"\'')
    m = re.search(r'(\d+)\.(\d+)\.(\d+)', v)
    if m:
        return tuple(int(x) for x in m.groups())
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
        if page > 20:
            break
    return branches

def fetch_file_from_branch(repo: str, branch: str, path: str = DEFAULT_FILE) -> Optional[bytes]:
    branch_enc = urllib.parse.quote(branch, safe="/")
    # path may contain subdirs, keep slashes
    path_enc = urllib.parse.quote(path, safe="/")
    url = f"{RAW_BASE}/{repo}/{branch_enc}/{path_enc}"
    try:
        r = requests.get(url, timeout=30, headers={"User-Agent": "scraper4-deployer"})
        if r.status_code == 200 and r.content:
            return r.content
    except Exception:
        pass
    return None

def extract_app_version(content: bytes) -> Optional[str]:
    try:
        text = content.decode('utf-8', errors='ignore')
    except:
        return None
    m = re.search(r'APP_VERSION\s*=\s*["\']([^"\']+)["\']', text)
    if m:
        return m.group(1).strip()
    # also try generic VERSION
    m2 = re.search(r'VERSION\s*=\s*["\']([^"\']+)["\']', text)
    if m2:
        return m2.group(1).strip()
    return None

def scan_all_branches(repo: str = REPO, filter_zip: bool = False, filter_keywords: str = "", target_file: str = DEFAULT_FILE) -> List[Dict[str, Any]]:
    branches_data = list_branches(repo)
    log(f"Found {len(branches_data)} branches in {repo} (target file: {target_file})")
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
        content = fetch_file_from_branch(repo, name, target_file)
        if not content:
            continue
        ver = extract_app_version(content)
        # if file exists but has no version, still list it with 0.0.0 so user sees it
        if not ver:
            # try to use commit date as pseudo version? keep 0.0.0
            ver = "0.0.0"
            ver_tuple = (0, 0, 0)
        else:
            ver_tuple = parse_version(ver)
        sha = (b.get("commit", {}) or {}).get("sha", "")[:7]
        results.append({
            "branch": name,
            "version": ver,
            "version_tuple": ver_tuple,
            "sha": sha,
            "size": len(content),
            "content": content
        })
        log(f"  {name} -> {ver} ({sha}) {len(content)} bytes")
        time.sleep(0.12)
    results.sort(key=lambda x: (x["version_tuple"], x["branch"]), reverse=True)
    return results

def get_local_version(target_path: Optional[str] = None) -> Tuple[str, Tuple[int, ...]]:
    if target_path is None:
        target_path = DEFAULT_TARGET
        # also try current dir file
        if not os.path.isfile(target_path):
            # fallback to env APP_DIR + file
            app_dir = os.environ.get("SCRAPER_APP_DIR") or os.path.join(os.path.expanduser("~"), "amphp")
            cand = os.path.join(app_dir, os.path.basename(DEFAULT_FILE))
            if os.path.isfile(cand):
                target_path = cand
            elif os.path.isfile(DEFAULT_FILE):
                target_path = DEFAULT_FILE
    if not os.path.isfile(target_path):
        return ("0.0.0", (0, 0, 0))
    try:
        with open(target_path, "rb") as f:
            content = f.read()
        ver = extract_app_version(content) or "0.0.0"
        return (ver, parse_version(ver))
    except:
        return ("0.0.0", (0, 0, 0))

def install_content(content: bytes, target_path: Optional[str] = None, backup: bool = True) -> str:
    if target_path is None:
        target_path = DEFAULT_TARGET
    # If target is a directory, append basename
    if os.path.isdir(target_path):
        target_path = os.path.join(target_path, os.path.basename(DEFAULT_FILE))
    os.makedirs(os.path.dirname(os.path.abspath(target_path)) or ".", exist_ok=True)
    # generic validation: Python files should parse, other files just check non-empty
    if target_path.endswith(".py"):
        if b"APP_VERSION" not in content and b"VERSION" not in content:
            # allow but warn
            log("Warning: downloaded file has no VERSION marker")
        try:
            import ast
            ast.parse(content.decode('utf-8', errors='ignore'))
        except SyntaxError as e:
            fail(f"Downloaded file has syntax error: {e}")

    if backup and os.path.isfile(target_path):
        bak = f"{target_path}.{datetime.now().strftime('%Y%m%d-%H%M%S')}.bak"
        try:
            import shutil
            shutil.copy2(target_path, bak)
            log(f"Backup written: {bak}")
        except Exception as e:
            log(f"Backup failed: {e}")

    tmp = f"{target_path}.new.{os.getpid()}"
    with open(tmp, "wb") as f:
        f.write(content)
    try:
        os.chmod(tmp, 0o600)
    except:
        pass
    os.replace(tmp, target_path)
    log(f"Installed to {target_path} ({len(content)} bytes)")

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
                domain = f"{user_lower}.pythonanywhere.com"
                api_url = f"https://www.pythonanywhere.com/api/v0/user/{user}/webapps/{domain}/reload/"
                log(f"Reloading webapp {domain} via API...")
                headers = {"Authorization": f"Token {token}"}
                r = requests.post(api_url, headers=headers, timeout=20)
                if r.status_code in (200, 201):
                    log(f"Reload OK: {r.status_code}")
                else:
                    log(f"Reload API returned {r.status_code}: {r.text[:200]}")
        except Exception as e:
            log(f"Reload via API failed: {e}, trying touch WSGI")
            try:
                for p in [f"/var/www/{user_lower}_pythonanywhere_com_wsgi.py", f"/var/www/{user}_pythonanywhere_com_wsgi.py"]:
                    if os.path.isfile(p):
                        os.utime(p, None)
                        log(f"Touched {p}")
                        break
            except Exception as e2:
                log(f"Touch WSGI failed: {e2}")
    else:
        try:
            for p in [f"/var/www/{user_lower}_pythonanywhere_com_wsgi.py"]:
                if os.path.isfile(p):
                    os.utime(p, None)
                    log(f"Touched {p}")
        except:
            pass
    return target_path

# ---------------------------------------------------------------------------
# Standalone Flask app — independent address, multi-branch simultaneous
# ---------------------------------------------------------------------------
DEPLOYER_HTML = r"""<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>📦 دیپلوی مستقل — چند برنچ هم‌زمان</title>
<link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet"/>
<style>
:root{--bg:#07111f;--card:#0e1d35;--border:rgba(148,177,216,.16);--text:#f1f5f9;--dim:#94a3b8;--pri:#38bdf8;--acc:#818cf8;--ok:#34d399;--danger:#fb7185;--r:14px}
*{box-sizing:border-box}body{margin:0;background:radial-gradient(circle at 85% -10%,rgba(37,99,235,.28),transparent 40%),linear-gradient(160deg,#07111f,#0d1b33);color:var(--text);font-family:'Vazirmatn',Tahoma,sans-serif;min-height:100vh}
a{color:var(--pri);text-decoration:none}
.header{position:sticky;top:0;backdrop-filter:blur(16px);background:rgba(7,17,31,.85);border-bottom:1px solid var(--border);padding:10px 14px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;z-index:10}
.badge{font-size:11px;border:1px solid var(--border);padding:3px 8px;border-radius:999px}
.btn{border:0;border-radius:10px;padding:8px 14px;font-weight:800;cursor:pointer}
.btn-pri{background:var(--pri);color:#000}.btn-acc{background:var(--acc);color:#000}.btn-sec{background:rgba(255,255,255,.08);color:var(--text);border:1px solid var(--border)}.btn-danger{background:var(--danger);color:#000}
.btn:disabled{opacity:.5;cursor:not-allowed}
.wrap{max-width:1100px;margin:0 auto;padding:16px}
.card{background:rgba(14,29,53,.88);border:1px solid var(--border);border-radius:var(--r);padding:14px;margin-bottom:12px}
.card h3{margin:0 0 8px;font-size:13px}
.row{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:11px;border:1px solid var(--border);border-radius:12px;margin-bottom:8px;background:rgba(255,255,255,.03)}
.row.is-newest{border-color:var(--acc);background:rgba(129,140,248,.12)}
.chip{display:inline-block;background:rgba(255,255,255,.08);border-radius:999px;padding:2px 8px;font-size:11px;margin:2px}
.chip-ok{background:rgba(52,211,153,.18);color:var(--ok)}.chip-pri{background:rgba(56,189,248,.18);color:var(--pri)}
input,select{width:100%;padding:9px 10px;border-radius:10px;border:1px solid var(--border);background:#0b1e3a;color:var(--text)}
.grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px}
@media(max-width:820px){.grid{grid-template-columns:1fr}}
#log{white-space:pre-wrap;font-family:monospace;font-size:11px;max-height:260px;overflow:auto;background:#020617;padding:10px;border-radius:10px;border:1px solid var(--border)}
.kbd{font-family:monospace;background:rgba(255,255,255,.08);padding:1px 6px;border-radius:6px;font-size:11px}
</style>
</head>
<body>
<div class="header">
  <span style="font-weight:900">📦 دیپلوی مستقل — آدرس جداگانه</span>
  <span class="badge" id="localVer">محلی v—</span>
  <span class="badge" id="branchCount">— برنچ</span>
  <span class="badge" id="fileBadge">scraper4.py</span>
  <div style="flex:1"></div>
  <span class="badge">هر تب یک فیلتر/فایل جدا → چند برنچ هم‌زمان</span>
</div>
<div class="wrap">
  <div class="card">
    <h3>این دیپلوی به scraper4.py لینک نیست — هر برنچ می‌تواند فایل متفاوت داشته باشد</h3>
    <p style="color:var(--dim);font-size:12px;line-height:1.8;margin:6px 0 10px">
      نام <b>فایل هدف</b> را وارد کنید (مثلاً <span class="kbd">scraper4.py</span>، <span class="kbd">scraper4.php</span>، <span class="kbd">app.py</span>).
      هر تب می‌تواند <span class="kbd">?file=</span> و <span class="kbd">?filter=</span> جدا داشته باشد و جدیدترین نسخه آن فایل را از همه برنچ‌ها پیدا کند.
    </p>
    <div class="grid">
      <label>مخزن<input id="repo" value="fazilatma/amphp" dir="ltr" placeholder="owner/repo"></label>
      <label>فیلتر برنچ<input id="filter" value="zip,arena,deploy" placeholder="خالی=همه"></label>
      <label>فایل هدف<input id="targetFile" value="scraper4.py" dir="ltr" placeholder="scraper4.py"></label>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px">
      <button class="btn btn-pri" id="scanBtn" onclick="scan()">🔍 اسکن همه برنچ‌ها</button>
      <button class="btn btn-acc" onclick="autoInstall()">🚀 نصب خودکار جدیدترین</button>
      <button class="btn btn-sec" onclick="copyLink()">🔗 کپی لینک این فیلتر/فایل</button>
      <button class="btn btn-sec" onclick="openNewTab()">↗ تب جدید (هم‌زمان)</button>
    </div>
    <div id="status" style="margin-top:10px;color:var(--dim);font-size:12px"></div>
    <div style="margin-top:8px;display:flex;gap:8px;flex-wrap:wrap">
      <label style="flex:1;min-width:220px">مسیر نصب محلی<input id="installPath" dir="ltr" placeholder="خالی = پیش‌فرض ~/amphp/scraper4.py"></label>
    </div>
  </div>

  <div class="card">
    <h3>🎯 انتخاب برنچ با نسخه — نصب دستی</h3>
    <select id="branchSelect" onchange="onBranchChange()"><option>— ابتدا اسکن کنید —</option></select>
    <div id="branchMeta" style="margin-top:8px;font-size:12px;color:var(--dim)"></div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px">
      <button class="btn btn-acc" onclick="installSelected()">⬇️ نصب انتخاب‌شده</button>
      <button class="btn btn-danger" onclick="installSelected(true)">⚡ نصب اجباری</button>
      <a id="rawLink" class="btn btn-sec" href="#" target="_blank" style="display:none">📄 فایل خام</a>
      <a id="ghLink" class="btn btn-sec" href="#" target="_blank" style="display:none">🔗 گیت‌هاب</a>
    </div>
  </div>

  <div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;flex-wrap:wrap">
      <h3>📋 لیست برنچ‌ها — با شماره نسخه</h3>
      <input id="q" placeholder="جستجو در نام/نسخه…" style="max-width:220px" oninput="renderTable()">
    </div>
    <div id="table" style="margin-top:8px"></div>
  </div>

  <div class="card"><h3>📜 لاگ</h3><div id="log"></div></div>
  <div class="card" style="font-size:11px;color:var(--dim);line-height:1.8">
    <b>نکته چندبرنچی:</b> هر تب یک URL جدا دارد. مثلاً تب ۱: <span class="kbd">?filter=arena&file=scraper4.py</span> ، تب ۲: <span class="kbd">?filter=zip&file=app.py</span>.
    دیپلوی به هیچ وجه به scraper4.py لینک نیست — فقط فایل نامی که شما می‌دهید را از برنچ‌ها می‌گیرد.
  </div>
</div>
<script>
let last=null, newest=null;
const $=id=>document.getElementById(id);
function esc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')}
function toFa(n){return String(n).replace(/\d/g,d=>'۰۱۲۳۴۵۶۷۸۹'[d])}
function logLine(t){const el=$('log'); el.textContent+='['+new Date().toLocaleTimeString('fa-IR')+'] '+t+'\n'; el.scrollTop=el.scrollHeight}
function syncUrl(){
  const repo=$('repo').value.trim(), filter=$('filter').value.trim(), file=$('targetFile').value.trim(), br=$('branchSelect').value||'';
  const u=new URL(location.href); u.searchParams.set('repo', repo); u.searchParams.set('filter', filter); u.searchParams.set('file', file); if(br) u.searchParams.set('branch', br); else u.searchParams.delete('branch');
  history.replaceState(null,'',u.toString());
  $('fileBadge').textContent=file||'—';
}
function copyLink(){ syncUrl(); navigator.clipboard.writeText(location.href).then(()=>alert('لینک کپی شد:\n'+location.href)); }
function openNewTab(){ syncUrl(); window.open(location.href, '_blank'); }
function onBranchChange(){
  const sel=$('branchSelect'), meta=$('branchMeta'), raw=$('rawLink'), gh=$('ghLink');
  const opt=sel.options[sel.selectedIndex]; if(!opt||!opt.value){meta.textContent=''; raw.style.display='none'; gh.style.display='none'; return;}
  const ver=opt.dataset.version||'', size=opt.dataset.size||'', sha=opt.dataset.sha||'', br=opt.value;
  meta.innerHTML=`نسخه: <b style="color:var(--pri)">v${esc(ver)}</b> | حجم: ${toFa(Math.round(parseInt(size||'0')/1024))}KB | SHA: <span dir="ltr">${esc(sha)}</span>`;
  const repo=$('repo').value.trim()||'fazilatma/amphp', file=$('targetFile').value.trim()||'scraper4.py';
  raw.href=`https://raw.githubusercontent.com/${repo}/${encodeURIComponent(br).replace(/%2F/g,'/')}/${encodeURIComponent(file).replace(/%2F/g,'/')}`;
  raw.style.display=''; raw.textContent=`📄 ${file} @ ${br}`;
  gh.href=`https://github.com/${repo}/tree/${encodeURIComponent(br)}`; gh.style.display='';
  syncUrl();
}
function pick(br){ $('branchSelect').value=br; onBranchChange(); $('branchSelect').scrollIntoView({behavior:'smooth',block:'center'}); }
async function scan(){
  const repo=$('repo').value.trim()||'fazilatma/amphp', filter=$('filter').value.trim(), file=$('targetFile').value.trim()||'scraper4.py';
  const status=$('status'), table=$('table'), sel=$('branchSelect'), btn=$('scanBtn');
  btn.disabled=true; status.textContent='⏳ در حال اسکن...'; table.innerHTML='<span class="chip">بارگذاری...</span>'; sel.innerHTML='<option>— در حال اسکن... —</option>';
  syncUrl();
  try{
    const res=await fetch('/api/scan',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({repo, filter, file})});
    const j=await res.json();
    if(!j.ok){status.textContent='❌ '+j.error; table.innerHTML=`<span class="chip" style="background:rgba(251,113,133,.2)">${esc(j.error)}</span>`; sel.innerHTML='<option>— خطا —</option>'; return;}
    last=j; newest=j.newest;
    const local=j.local_version||'—';
    $('localVer').textContent='محلی v'+local;
    $('branchCount').textContent=toFa(j.count)+' برنچ';
    const newestInfo = newest ? `<b>v${esc(newest.version)} (${esc(newest.branch)})</b> — ${(JSON.stringify(newest.version_tuple||[])>JSON.stringify(j.local_tuple||[])?'جدیدتر ⬆️':'به‌روز ✅')}` : 'یافت نشد';
    status.innerHTML=`فایل: <b dir="ltr">${esc(file)}</b> | محلی: <b>v${esc(local)}</b> | جدیدترین: ${newestInfo} | ${toFa(j.count)} برنچ دارای فایل`;
    logLine(`اسکن ${file}: ${j.count} برنچ، جدیدترین ${newest?.branch||'—'} v${newest?.version||'—'}`);
    const rows=j.branches||[];
    if(!rows.length) sel.innerHTML='<option>— نتیجه‌ای نیست —</option>';
    else {
      sel.innerHTML=rows.map(r=>{const isN=newest&&r.branch===newest.branch; return `<option value="${esc(r.branch)}" data-version="${esc(r.version)}" data-size="${r.size}" data-sha="${esc(r.sha||'')}" ${isN?'selected':''}>${esc(r.branch)} — v${esc(r.version)}${isN?' ⭐':''} (${Math.round(r.size/1024)}KB)</option>`}).join('');
      const want=new URLSearchParams(location.search).get('branch');
      if(want && rows.some(r=>r.branch===want)) sel.value=want;
      onBranchChange();
    }
    renderTable();
  }catch(e){ status.textContent='خطای شبکه: '+e.message; logLine('خطا: '+e.message);}
  finally{ btn.disabled=false; }
}
function renderTable(){
  if(!last) return;
  const table=$('table'), q=($('q').value||'').trim().toLowerCase();
  const rows=(last.branches||[]).filter(r=>!q||r.branch.toLowerCase().includes(q)||r.version.toLowerCase().includes(q));
  if(!rows.length){ table.innerHTML='<span class="chip">نتیجه‌ای نیست</span>'; return; }
  const file=$('targetFile').value.trim()||'scraper4.py', repo=$('repo').value.trim()||'fazilatma/amphp';
  table.innerHTML=rows.map(r=>{
    const isN=newest&&r.branch===newest.branch;
    return `<div class="row ${isN?'is-newest':''}">
      <div style="flex:1;min-width:220px">
        <b dir="ltr">${esc(r.branch)}</b> <span style="color:var(--pri);font-weight:900">v${esc(r.version)}</span> ${isN?'<span class="chip chip-ok">جدیدترین ⭐</span>':''}
        <div style="margin-top:4px;display:flex;gap:4px;flex-wrap:wrap">
          <span class="chip">${toFa(Math.round(r.size/1024))}KB</span>
          ${r.sha?`<span class="chip" dir="ltr">${esc(r.sha)}</span>`:''}
          <span class="chip chip-pri" dir="ltr">${esc((r.version_tuple||[]).join('.'))}</span>
        </div>
      </div>
      <div style="display:flex;gap:6px;flex-wrap:wrap">
        <button class="btn btn-sec" onclick="pick('${esc(r.branch).replace(/'/g,"&#39;")}')">👁 انتخاب</button>
        <button class="btn btn-acc" onclick="installBranch('${esc(r.branch).replace(/'/g,"&#39;")}')">⬇️ نصب</button>
        <a class="btn btn-sec" href="https://raw.githubusercontent.com/${esc(repo)}/${esc(r.branch).replace(/'/g,"&#39;").replace(/%2F/g,'/')}/${esc(file).replace(/'/g,"&#39;").replace(/%2F/g,'/')}" target="_blank">📄 خام</a>
      </div>
    </div>`;
  }).join('');
}
async function doInstall(branch, force){
  const repo=$('repo').value.trim()||'fazilatma/amphp', file=$('targetFile').value.trim()||'scraper4.py', target=$('installPath').value.trim(), status=$('status');
  status.textContent=`⏳ نصب ${branch} (${file})...`; logLine(`نصب ${branch} file=${file} force=${!!force} target=${target||'default'}`);
  try{
    const res=await fetch('/api/install',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({repo, branch, file, target, force:!!force})});
    const j=await res.json();
    if(j.ok && j.changed){ status.innerHTML=`✅ ${esc(j.message)}<br>→ ${esc(j.target||'')}<br>محلی v${esc(j.local_version)} → v${esc(j.remote_version)} (${esc(j.branch)})`; logLine(j.message); }
    else if(j.ok){ status.textContent=j.message; logLine(j.message); }
    else { status.textContent='❌ '+j.error; logLine('خطا: '+j.error); alert(j.error); }
  }catch(e){ status.textContent='خطای شبکه: '+e.message; logLine('خطای شبکه: '+e.message); }
}
async function installBranch(b, force){ if(!b) return; if(!confirm(`برنچ «${b}» (${$('targetFile').value.trim()}) نصب شود؟`)) return; await doInstall(b,force); }
async function installSelected(force){
  const sel=$('branchSelect'); if(!sel.value){alert('ابتدا برنچ انتخاب کنید'); return;}
  if(!confirm(`برنچ «${sel.value}» نصب شود؟`)) return; await doInstall(sel.value, force);
}
async function autoInstall(){
  const repo=$('repo').value.trim()||'fazilatma/amphp', filter=$('filter').value.trim(), file=$('targetFile').value.trim()||'scraper4.py', target=$('installPath').value.trim(), status=$('status');
  if(!confirm(`نصب خودکار جدیدترین ${file} با فیلتر فعلی؟`)) return;
  status.textContent='⏳ نصب خودکار...';
  try{
    const res=await fetch('/api/install',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({repo, filter, file, target})});
    const j=await res.json();
    if(j.ok&&j.changed){ status.innerHTML=`✅ ${esc(j.message)}<br>→ ${esc(j.target||'')}`; logLine(j.message); }
    else { status.textContent=j.message||j.error; logLine(j.message||j.error); }
  }catch(e){ status.textContent='خطای شبکه: '+e.message; }
}
(function(){
  const sp=new URLSearchParams(location.search);
  if(sp.get('repo')) $('repo').value=sp.get('repo');
  if(sp.get('filter')) $('filter').value=sp.get('filter');
  if(sp.get('file')) $('targetFile').value=sp.get('file');
  if(sp.get('target')) $('installPath').value=sp.get('target');
  setTimeout(scan, 350);
  $('fileBadge').textContent=$('targetFile').value.trim()||'scraper4.py';
})();
</script>
</body>
</html>
"""

# Global Flask app — importable as `from deployer import app`
try:
    from flask import Flask, request, jsonify, Response
    app = Flask(__name__)

    @app.get("/")
    def index():
        return Response(DEPLOYER_HTML, mimetype="text/html; charset=utf-8")

    @app.get("/health")
    def health():
        local_ver, _ = get_local_version()
        return jsonify(ok=True, local_version=local_ver, repo=REPO, file=DEFAULT_FILE)

    @app.post("/api/scan")
    def api_scan():
        body = request.get_json(silent=True) or {}
        r_repo = (body.get("repo") or REPO).strip() or REPO
        r_filter = (body.get("filter") or "").strip()
        r_file = (body.get("file") or DEFAULT_FILE).strip() or DEFAULT_FILE
        r_token = (body.get("token") or os.environ.get("GITHUB_TOKEN") or "").strip()
        if r_token:
            os.environ["GITHUB_TOKEN"] = r_token
        results = scan_all_branches(r_repo, filter_keywords=r_filter, target_file=r_file)
        local_ver, local_tuple = get_local_version()
        # if target file is not default, try that path
        if r_file != DEFAULT_FILE:
            # try to get local version of that specific file
            try:
                cand = os.path.join(os.path.dirname(DEFAULT_TARGET) or ".", os.path.basename(r_file))
                if os.path.isfile(cand):
                    local_ver, local_tuple = get_local_version(cand)
                elif os.path.isfile(r_file):
                    local_ver, local_tuple = get_local_version(r_file)
            except:
                pass
        newest = results[0] if results else None
        resp = {
            "ok": True,
            "repo": r_repo,
            "file": r_file,
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
        r_repo = (body.get("repo") or REPO).strip() or REPO
        r_branch = (body.get("branch") or "").strip()
        r_filter = (body.get("filter") or "").strip()
        r_file = (body.get("file") or DEFAULT_FILE).strip() or DEFAULT_FILE
        r_target = (body.get("target") or "").strip() or None
        # resolve target path
        if r_target:
            target_path = os.path.expanduser(r_target)
            # if target is a directory, keep filename
            if os.path.isdir(target_path) or r_target.endswith("/"):
                target_path = os.path.join(target_path, os.path.basename(r_file))
        else:
            # default target: ~/amphp/<file> or ./<file>
            base_dir = os.path.join(os.path.expanduser("~"), "amphp")
            if os.path.isdir(base_dir):
                target_path = os.path.join(base_dir, os.path.basename(r_file))
            else:
                target_path = os.path.join(".", os.path.basename(r_file))
        r_force = bool(body.get("force", False))
        r_token = (body.get("token") or os.environ.get("GITHUB_TOKEN") or "").strip()
        if r_token:
            os.environ["GITHUB_TOKEN"] = r_token
        try:
            if not r_branch:
                results = scan_all_branches(r_repo, filter_keywords=r_filter, target_file=r_file)
                if not results:
                    return jsonify(ok=False, error=f"هیچ برنچی با فایل {r_file} پیدا نشد"), 404
                newest = results[0]
                r_branch = newest["branch"]
                content = newest["content"]
                remote_ver = newest["version"]
                remote_tuple = newest["version_tuple"]
            else:
                content = fetch_file_from_branch(r_repo, r_branch, r_file)
                if not content:
                    return jsonify(ok=False, error=f"فایل {r_file} از برنچ {r_branch} دریافت نشد"), 404
                remote_ver = extract_app_version(content) or "0.0.0"
                remote_tuple = parse_version(remote_ver)
            local_ver, local_tuple = get_local_version(target_path)
            if not r_force and remote_tuple <= local_tuple and remote_tuple != (0,0,0):
                return jsonify(ok=True, changed=False, message=f"محلی v{local_ver} جدیدتر یا مساوی v{remote_ver} است", local_version=local_ver, remote_version=remote_ver, branch=r_branch, target=target_path)
            install_content(content, target_path=target_path)
            return jsonify(ok=True, changed=True, message=f"✅ {r_branch} v{remote_ver} → {target_path} نصب شد", local_version=local_ver, remote_version=remote_ver, branch=r_branch, target=target_path)
        except Exception as e:
            return jsonify(ok=False, error=f"{type(e).__name__}: {e}"), 500

except ImportError:
    app = None

def serve_ui(port: int = 5001, repo: str = REPO, filter_kw: str = "", token: str = ""):
    if app is None:
        fail("Flask required for --serve : pip install flask")
    # sync env for scan
    if token:
        os.environ["GITHUB_TOKEN"] = token
    print(f"Serving deployer UI on http://0.0.0.0:{port} — repo={repo} file={DEFAULT_FILE} (standalone, not linked to scraper)")
    app.run(host="0.0.0.0", port=port, debug=False)

def main():
    global DEFAULT_FILE
    parser = argparse.ArgumentParser(description="Deployer (standalone, not linked to scraper) - find newest file across branches")
    parser.add_argument("--scan", action="store_true", help="Scan all branches and list versions")
    parser.add_argument("--filter-zip", action="store_true", help="Only consider branches containing zip/arena (alias for --filter zip,arena,deploy)")
    parser.add_argument("--filter", type=str, default="", dest="filter", help="Comma separated keywords to filter branches, e.g. zip,arena,deploy")
    parser.add_argument("--file", type=str, default=DEFAULT_FILE, help=f"File to fetch from branches (default {DEFAULT_FILE})")
    parser.add_argument("--target", type=str, default="", help="Local path to install to (default ~/amphp/<file>)")
    parser.add_argument("--install", action="store_true", help="Install a branch (default newest)")
    parser.add_argument("--branch", type=str, default="", help="Specific branch to install")
    parser.add_argument("--auto", action="store_true", help="Auto: scan and install if newer than local")
    parser.add_argument("--force", action="store_true", help="Force install even if same version")
    parser.add_argument("--repo", type=str, default=REPO, help="GitHub repo owner/name")
    parser.add_argument("--json", action="store_true", help="Output JSON for API")
    parser.add_argument("--serve", action="store_true", help="Run standalone web UI (separate address, multi-branch tabs)")
    parser.add_argument("--port", type=int, default=8001, help="Port for --serve (default 8001)")
    args = parser.parse_args()

    repo = args.repo
    target_file = args.file.strip() or DEFAULT_FILE
    target_path = args.target.strip() or None

    if args.serve:
        token = os.environ.get("GITHUB_TOKEN") or os.environ.get("SCRAPER_GITHUB_TOKEN") or ""
        DEFAULT_FILE = target_file
        serve_ui(port=args.port, repo=repo, filter_kw=args.filter or ("zip,arena,deploy" if args.filter_zip else ""), token=token)
        return

    if args.scan or args.auto or (not args.install and not args.branch and not args.serve):
        results = scan_all_branches(repo, filter_zip=args.filter_zip, filter_keywords=args.filter, target_file=target_file)
        if not results:
            log(f"No branches with valid {target_file} found")
            if args.json:
                print(json.dumps({"ok": False, "error": f"no branches found for {target_file}", "repo": repo, "file": target_file}))
            return

        newest = results[0]
        # local version for that file
        if target_path:
            local_ver_str, local_ver_tuple = get_local_version(target_path)
        else:
            # try default target for that file
            cand = os.path.join(os.path.expanduser("~"), "amphp", os.path.basename(target_file))
            if os.path.isfile(cand):
                local_ver_str, local_ver_tuple = get_local_version(cand)
            elif os.path.isfile(target_file):
                local_ver_str, local_ver_tuple = get_local_version(target_file)
            else:
                local_ver_str, local_ver_tuple = get_local_version()

        if args.json:
            out = {
                "ok": True,
                "repo": repo,
                "file": target_file,
                "local_version": local_ver_str,
                "local_tuple": local_ver_tuple,
                "newest": {k: v for k, v in newest.items() if k != "content"},
                "all": [{k: v for k, v in r.items() if k != "content"} for r in results]
            }
            print(json.dumps(out, ensure_ascii=False, indent=2))
        else:
            print("\n" + "="*70)
            print(f"Repo: {repo}  File: {target_file}")
            print(f"Local version: {local_ver_str} {local_ver_tuple}  -> {target_path or cand if 'cand' in locals() else target_file}")
            print(f"Newest remote: {newest['branch']} -> {newest['version']} {newest['version_tuple']} ({newest['sha']})")
            print("="*70)
            for r in results[:20]:
                marker = " <-- NEWEST" if r == newest else ""
                print(f"{r['branch']:45} {r['version']:12} {r['sha']:8} {r['size']:8} bytes{marker}")
            print("="*70)

        if args.auto:
            if newest["version_tuple"] > local_ver_tuple or args.force:
                log(f"Auto-installing newest {newest['branch']} ({newest['version']}) file {target_file} over local {local_ver_str}")
                install_content(newest["content"], target_path=target_path or (locals().get("cand") if 'cand' in locals() else None))
                new_local_str, _ = get_local_version(target_path or (locals().get("cand") if 'cand' in locals() else None))
                log(f"After install local version: {new_local_str}")
            else:
                log(f"Local {local_ver_str} is already newest or newer than remote {newest['version']}, skipping (use --force to override)")

    if args.install:
        branch = args.branch.strip()
        if branch:
            log(f"Installing specific branch: {branch} file {target_file}")
            content = fetch_file_from_branch(repo, branch, target_file)
            if not content:
                fail(f"Could not fetch {target_file} from branch {branch}")
            ver = extract_app_version(content) or "0.0.0"
            log(f"Fetched {branch} version {ver} {len(content)} bytes")
            if target_path:
                local_ver_str, local_ver_tuple = get_local_version(target_path)
            else:
                local_ver_str, local_ver_tuple = get_local_version()
            remote_tuple = parse_version(ver)
            if not args.force and remote_tuple <= local_ver_tuple and remote_tuple != (0,0,0):
                log(f"Local {local_ver_str} >= remote {ver}, use --force to override")
                if not args.force:
                    return
            install_content(content, target_path=target_path)
        else:
            results = scan_all_branches(repo, filter_zip=args.filter_zip, filter_keywords=args.filter, target_file=target_file)
            if not results:
                fail("No branches found to install")
            newest = results[0]
            if target_path:
                local_ver_str, local_ver_tuple = get_local_version(target_path)
            else:
                local_ver_str, local_ver_tuple = get_local_version()
            if not args.force and newest["version_tuple"] <= local_ver_tuple and newest["version_tuple"] != (0,0,0):
                log(f"Local {local_ver_str} >= newest {newest['version']}, use --force")
                return
            log(f"Installing newest {newest['branch']} {newest['version']} file {target_file}")
            install_content(newest["content"], target_path=target_path)

if __name__ == "__main__":
    main()
