#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Scraper4 PythonAnywhere edition — single-file Flask product scraper.

This is a focused Python port of scraper4.php's extraction workflow.  It keeps
its important ideas (profiles, CSS selectors, pagination, automatic product
recognition, detail enrichment, exports, pacing, retries, and WooCommerce
sending) while replacing the PHP-only/background-task UI with a request-sized
Flask application suitable for PythonAnywhere.

Extraction follows scraper4.php: the server downloads HTML, applies saved CSS selectors,
uses structural HTML fallbacks, and optionally renders JavaScript with Playwright before
applying the exact same selector parser. No source-site product API or hydration-state
extraction is used.

PythonAnywhere setup
--------------------
1. Upload this file, then in a Bash console run:
       pip3 install --user flask requests beautifulsoup4 lxml html5lib playwright cloudscraper selenium
       python3 -m playwright install chromium
2. In the Web tab create a Flask app and make the WSGI file contain:
       import sys
       sys.path.insert(0, '/home/YOUR_USERNAME/scraper4')
       from scraper4 import app as application
3. Set SCRAPER_PASSWORD in the WSGI file or environment for a public install.
   Example, before importing this module:
       import os; os.environ['SCRAPER_PASSWORD'] = 'a-long-random-password'
4. Recommended location on PythonAnywhere:
       /home/YOUR_USERNAME/scraper4/scraper4.py
   Keep it out of public_html.  The browser reaches it through the Web app,
   not as a downloadable source file.  The data and updater backup will then
   be created in that same private directory.  Point the WSGI file at
   /home/YOUR_USERNAME/scraper4 as shown above.
5. PythonAnywhere free accounts can only contact allow-listed sites.  A paid
   account may be required for arbitrary source sites, Digikala, and shops.

The Deploy tab can check a GitHub branch and atomically replace this file. It
first compiles and validates the download, keeps scraper4.py.bak, and can touch
the configured PythonAnywhere WSGI file to reload the web app. Configure a
private repository token through GITHUB_TOKEN rather than saving it in JSON.

Run locally with:  python3 scraper4.py
Data is stored beside the file in scraper4_data.json.  No external template,
static asset, database, worker, or JavaScript package is required.
"""

from __future__ import annotations

import base64
import csv
import hashlib
import hmac
import io
import ipaddress
import importlib
import importlib.metadata
import json
import os
import re
import secrets
import site
import socket
import subprocess
import shutil
import sys
import tempfile
import threading
import time
import zipfile
from dataclasses import dataclass, field
from html import escape
from typing import Any, Iterable, Optional
from urllib.parse import parse_qs, quote, urlencode, urljoin, urlparse, urlunparse

try:
    import requests
    from bs4 import BeautifulSoup, Tag
    from flask import Flask, Response, jsonify, request
except ImportError as exc:  # clear diagnosis in PythonAnywhere's error log
    raise RuntimeError(
        "Missing dependency. Run: pip3 install --user flask requests beautifulsoup4"
    ) from exc

APP_VERSION = "3.6.0"
CHANGELOG = [
    {"version":"3.6.0","date":"2026-09-03","title":"دروازه مرکزی تمام اتصال‌های خروجی","items":["یک تنظیم واحد مستقیم/Worker/HTTP Proxy برای مبدأ، باسلام، ووکامرس، AI و GitHub","حذف تنظیمات شبکه تکراری از کارت‌های باسلام و ووکامرس","آزمایش توان Worker برای عبور Authorization، method و JSON بدون افشای کلید واقعی","نمایش مسیر فعال و سلامت دروازه در رابط بازطراحی‌شده"]},
    {"version":"3.5.0","date":"2026-09-03","title":"دسترسی سریع وظایف و آزمایشگاه حرفه‌ای AI","items":["دکمه چسبان تسک منیجر کنار منوی همبرگری با شمارنده زنده","نمایش برجسته نام پروفایل روی همه وظایف استخراج","فیلتر امتیاز و latency، رتبه‌بندی، percentile و مقایسه کنارهم مدل‌ها","بازطراحی جدول نتایج با جزئیات بازشونده و نمایش حرفه‌ای موبایل"]},
    {"version":"3.4.0","date":"2026-09-03","title":"REST API باسلام و مرکز عملیات","items":["انتخاب خودکار SDK سپس REST API مستقیم بدون وابستگی به SDK","تنظیم Base URL، روش کلاینت و مسیر مستقیم/Worker برای API باسلام","مدیریت REST محصولات، غرفه، گفت‌وگوها، پیام‌ها و سفارش‌های غرفه","بازطراحی مرکز باسلام و کارت‌های عملیاتی منظم و واکنش‌گرا"]},
    {"version":"3.3.1","date":"2026-09-03","title":"استخراج موازی و رابط وظایف","items":["حذف کامل FIFO؛ هر استخراج بلافاصله و مستقل آغاز می‌شود","گیرکردن یک منبع مانع اجرای پروفایل‌های دیگر نیست","فیلتر زنده نوع و وضعیت وظایف همراه جستجو در رویدادها","حفظ توقف امن، تشخیص قطع‌شدن و ادامه از checkpoint"]},
    {"version":"3.3.0","date":"2026-09-03","title":"رله ووکامرس و صف استخراج پایدار","items":["ارسال GET/POST/PUT ووکامرس از Cloudflare Worker با احراز هویت query امن HTTPS","اجرای FIFO استخراج برای جلوگیری از رقابت Playwright و جلو زدن وظایف","نمایش مرحله شبکه پیش از دریافت محصول، تشخیص وظیفه قطع‌شده و اجرای مجدد از checkpoint","بهبود کارت‌های مقصد و سلامت صف در رابط موبایل"]},
    {"version":"3.2.1","date":"2026-09-03","title":"ترمیم احراز هویت باسلام","items":["پاک‌سازی خودکار Bearer تکراری، کوتیشن و فاصله از Personal Token","ارسال هدرهای سازگار با رله و fallback امن به اتصال مستقیم هنگام 401 رله","تشخیص جداگانه Worker ناسازگار، توکن نامعتبر و محدودیت شبکه PythonAnywhere"]},
    {"version":"3.2.0","date":"2026-09-03","title":"ارسال کامل پروفایل‌ها و مرکز وظایف","items":["ارسال سرور‌ساید و کامل محصولات ذخیره‌شده هر پروفایل به ووکامرس و باسلام","تسک منیجر مرکزی با توقف، حذف، بازیابی پس از refresh و گزارش زمانی","نوار پیشرفت چندمرحله‌ای با شمارنده، موفق/خطا، زمان سپری‌شده و تخمین پایان","گزارش نسخه‌ها در رابط و API"]},
    {"version":"3.1.0","date":"2026-09-03","title":"رله رسمی SDK باسلام","items":["عبور درخواست‌های SDK از Cloudflare Worker","تشخیص دقیق خطای 403 و حفظ Authorization و multipart","کاتالوگ، آمار، کاندید و مستر مدل‌های هوش مصنوعی"]},
    {"version":"3.0.0","date":"2026-09-03","title":"صفحه‌بندی PHP و آزمایشگاه مدل‌ها","items":["صفحه‌بندی relative/full سازگار با PHP","استخراج واقعی چندصفحه‌ای","فیلتر، امتیازدهی و صفحه‌بندی نتایج آزمون AI"]},
    {"version":"2.9.0","date":"2026-09-03","title":"وظایف زنده و نصب SDK","items":["استخراج زنده سرور‌ساید","نصب غیرهمزمان SDK از سه منبع رسمی","جزئیات زمانی عملیات"]},
]
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
LOCAL_DEPS_DIR = os.path.join(BASE_DIR, ".runtime-deps")
LIVE_TASK_DIR = os.path.join(tempfile.gettempdir(), "scraper4-live-"+str(os.getuid() if hasattr(os,"getuid") else "user"))
if os.path.isdir(LOCAL_DEPS_DIR):site.addsitedir(LOCAL_DEPS_DIR)
# Keep browser installation and runtime lookup in the same quota-controlled folder.
os.environ["PLAYWRIGHT_BROWSERS_PATH"] = os.environ.get("SCRAPER_PLAYWRIGHT_PATH", os.path.join(BASE_DIR, "ms-playwright"))
try:
    with open(__file__, "rb") as _build_file:
        BUILD_ID = hashlib.sha256(_build_file.read()).hexdigest()[:12]
except OSError:
    BUILD_ID = APP_VERSION
AUTO_UPDATE_ENABLED = os.environ.get("SCRAPER_AUTO_UPDATE", "1").lower() not in {"0", "false", "off", "no"}
AUTO_UPDATE_INTERVAL = max(120, int(os.environ.get("SCRAPER_AUTO_UPDATE_INTERVAL", "300")))
DATA_FILE = os.environ.get("SCRAPER_DATA_FILE", os.path.join(BASE_DIR, "scraper4_data.json"))
PASSWORD = os.environ.get("SCRAPER_PASSWORD", "")
DEPLOY_PASSWORD = os.environ.get("SCRAPER_DEPLOY_PASSWORD", "")
MAX_PAGES_HARD = 50
MAX_PRODUCTS_HARD = 2000
MAX_HTML_BYTES = 12 * 1024 * 1024
USER_AGENT = (
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 "
    "(KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36"
)
PERSIAN_DIGITS = str.maketrans("۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩", "01234567890123456789")
DATA_LOCK = threading.RLock()
LIVE_TASK_LOCK = threading.RLock()
LIVE_TASKS: dict[str,dict[str,Any]] = {}

app = Flask(__name__)
app.config["JSON_AS_ASCII"] = False


# ---------------------------------------------------------------------------
# Persistent configuration
# ---------------------------------------------------------------------------
def default_data() -> dict[str, Any]:
    return {
        "profiles": {},
        "active_profile": "",
        "woocommerce": {"url": "", "consumer_key": "", "consumer_secret": "", "api_mode": "relay", "relay_url": "https://proxy.fazilat-ma.workers.dev", "worker_key": ""},
        "network": {"timeout": 25, "gap_ms": 350, "proxy": "https://proxy.fazilat-ma.workers.dev", "proxy_mode": "relay", "worker_key": "", "verify_tls": True},
        "deploy": {
            "repo": "fazilatma/amphp", "branch": "arena/01a0640f-amphp", "path": "scraper4.py",
            "github_token": "", "reload_file": "",
        },
        "last_result": [],
        "extract_jobs": {},
        "woo_jobs": {},
        "runtime": {"playwright_path": ""},
        "ai": {"provider": "openrouter", "endpoint": "https://openrouter.ai/api/v1/chat/completions", "api_key": "", "model": "meta-llama/llama-3.3-70b-instruct:free", "temperature": 0.3, "max_tokens": 1200, "system_prompt": "You write accurate Persian WooCommerce product content. Return only requested JSON."},
        "ai_providers": {},
        "ai_candidates": [],
        "ai_master": "",
        "basalam": {"token": "", "refresh_token": "", "vendor_id": 0, "category_id": 0, "preparation_days": 3, "weight": 500, "stock": 10, "update_existing": True, "client_mode": "auto", "api_mode": "relay", "api_base_url": "https://openapi.basalam.com", "relay_url": "https://proxy.fazilat-ma.workers.dev", "worker_key": "", "last_test_at": 0, "last_test_user": "", "last_client": ""},
        "bsl_jobs": {},
        "ai_test_jobs": {},
        "dispatch_jobs": {},
    }


def load_data() -> dict[str, Any]:
    with DATA_LOCK:
        try:
            with open(DATA_FILE, "r", encoding="utf-8") as fh:
                raw = json.load(fh)
            out = default_data()
            for key in out:
                if key in raw and isinstance(raw[key], type(out[key])):
                    if isinstance(out[key],dict):
                        merged=dict(out[key]);merged.update(raw[key]);out[key]=merged
                    else:out[key]=raw[key]
            return out
        except (OSError, ValueError, TypeError):
            return default_data()


def save_data(data: dict[str, Any]) -> None:
    os.makedirs(os.path.dirname(DATA_FILE) or ".", exist_ok=True)
    with DATA_LOCK:
        fd, tmp = tempfile.mkstemp(prefix=".scraper4-", suffix=".json", dir=os.path.dirname(DATA_FILE) or ".")
        try:
            with os.fdopen(fd, "w", encoding="utf-8") as fh:
                json.dump(data, fh, ensure_ascii=False, indent=2)
                fh.flush()
                os.fsync(fh.fileno())
            os.replace(tmp, DATA_FILE)
        finally:
            try:
                if os.path.exists(tmp):
                    os.unlink(tmp)
            except OSError:
                pass


# ---------------------------------------------------------------------------
# Security and HTTP
# ---------------------------------------------------------------------------
def authorized() -> bool:
    if not PASSWORD:
        return True
    supplied = request.headers.get("X-Scraper-Password", "")
    if not supplied:
        auth = request.authorization
        supplied = auth.password if auth else ""
    return bool(supplied) and hmac.compare_digest(supplied, PASSWORD)


def deploy_authorized() -> bool:
    """Authorize sensitive updater operations without locking the public UI."""
    supplied = request.headers.get("X-Deploy-Password", "")
    return bool(DEPLOY_PASSWORD and supplied and hmac.compare_digest(supplied, DEPLOY_PASSWORD))


def deploy_auth_error():
    if not DEPLOY_PASSWORD:
        return jsonify(ok=False, error="رمز مدیریت نصب در WSGI تنظیم نشده است"), 503
    return jsonify(ok=False, error="رمز مدیریت نصب نادرست است"), 401


@app.before_request
def require_password():
    if request.path == "/health" or authorized():
        return None
    return Response("Authentication required", 401, {"WWW-Authenticate": 'Basic realm="Scraper4"'})


def public_http_url(url: str) -> str:
    """Validate source URLs and reject localhost/private literal addresses."""
    url = (url or "").strip()
    markdown = re.fullmatch(r"\[[^]]+\]\((https?://[^)]+)\)", url, re.I)
    if markdown: url = markdown.group(1).strip()
    # Also tolerate copied rich-text links that contain a URL after display text.
    if not url.lower().startswith(("http://", "https://")):
        embedded = re.search(r"https?://[^\s)]+", url, re.I)
        if embedded: url = embedded.group(0)
    parsed = urlparse(url)
    if parsed.scheme not in ("http", "https") or not parsed.hostname:
        raise ValueError("آدرس باید با http:// یا https:// شروع شود")
    host = parsed.hostname.lower().rstrip(".")
    if host in {"localhost", "localhost.localdomain"}:
        raise ValueError("آدرس محلی مجاز نیست")
    try:
        ip = ipaddress.ip_address(host)
        if not ip.is_global:
            raise ValueError("IP خصوصی/محلی مجاز نیست")
    except ValueError as exc:
        if "مجاز نیست" in str(exc):
            raise
    return url


class FetchError(RuntimeError):
    pass


@dataclass
class FetchResult:
    url: str
    text: str
    content_type: str
    status: int
    mode: str = "http"


def outbound_mode(cfg: Optional[dict[str,Any]]=None) -> str:
    cfg=cfg or load_data().get("network",{});mode=clean_text(cfg.get("proxy_mode","auto")).lower();proxy=clean_text(cfg.get("proxy"))
    return ("relay" if proxy and ("workers.dev" in proxy.lower() or "?url=" in proxy or "{url}" in proxy) else "http" if proxy else "direct") if mode=="auto" else mode


def outbound_request(method: str, url: str, **kwargs: Any) -> requests.Response:
    """Route every requests-based external call through the one global connection gateway."""
    cfg=load_data().get("network",{});mode=outbound_mode(cfg);proxy=clean_text(cfg.get("proxy"));target=public_http_url(url)
    headers=dict(kwargs.pop("headers",{}) or {});params=kwargs.pop("params",None)
    if params:target=requests.Request("GET",target,params=params).prepare().url
    request_url=target
    if mode=="relay":
        if not proxy:raise FetchError("حالت Worker انتخاب شده اما آدرس دروازه مرکزی خالی است")
        relay=public_http_url(proxy.replace("{url}",quote(target,safe=""))) if "{url}" in proxy else public_http_url(proxy);request_url=relay if "{url}" in proxy else relay+("&" if "?" in relay else "?")+urlencode({"url":target});headers["X-Proxy-UA"]=headers.get("User-Agent",USER_AGENT);headers["X-Proxy-Method"]=method.upper()
        authorization=clean_text(headers.get("Authorization"))
        if authorization:headers["X-Proxy-Authorization"]=authorization;headers["X-Upstream-Authorization"]=authorization
        if cfg.get("worker_key"):headers["X-Proxy-Key"]=str(cfg["worker_key"])
    elif mode=="http":
        if not proxy:raise FetchError("حالت HTTP Proxy انتخاب شده اما آدرس پروکسی خالی است")
        kwargs["proxies"]={"http":proxy,"https":proxy}
    kwargs.setdefault("timeout",max(5,min(120,int(cfg.get("timeout",25)))));kwargs.setdefault("allow_redirects",True);kwargs.setdefault("verify",bool(cfg.get("verify_tls",True)))
    response=requests.request(method,request_url,headers=headers,**kwargs);setattr(response,"scraper4_transport",mode);return response


def outbound_browser_target(url: str) -> str:
    cfg=load_data().get("network",{});mode=outbound_mode(cfg);proxy=clean_text(cfg.get("proxy"));target=public_http_url(url)
    if mode!="relay":return target
    relay=public_http_url(proxy.replace("{url}",quote(target,safe=""))) if "{url}" in proxy else public_http_url(proxy);return relay if "{url}" in proxy else relay+("&" if "?" in relay else "?")+urlencode({"url":target})


class Fetcher:
    def __init__(self, cfg: dict[str, Any]):
        self.timeout = max(5, min(90, int(cfg.get("timeout", 25))))
        self.gap = max(0, min(10000, int(cfg.get("gap_ms", 350)))) / 1000.0
        self.verify = bool(cfg.get("verify_tls", True))
        self.proxy = str(cfg.get("proxy", "")).strip()
        self.proxy_mode = str(cfg.get("proxy_mode", "auto")).strip().lower()
        self.worker_key = str(cfg.get("worker_key", "")).strip()
        if self.proxy_mode == "auto":
            self.proxy_mode = "relay" if ("workers.dev" in self.proxy.lower() or "{url}" in self.proxy or "?url=" in self.proxy) else ("http" if self.proxy else "direct")
        self.session = requests.Session()
        self.session.headers.update({
            "User-Agent": USER_AGENT,
            "Accept": "text/html,application/xhtml+xml,application/json;q=0.9,*/*;q=0.8",
            "Accept-Language": "fa-IR,fa;q=0.9,en-US;q=0.7,en;q=0.6",
            "Cache-Control": "no-cache",
        })
        if self.proxy and self.proxy_mode == "http":
            self.session.proxies.update({"http": self.proxy, "https": self.proxy})
        self.last_by_host: dict[str, float] = {}

    def get(self, url: str, *, referer: str = "", accept_json: bool = False) -> FetchResult:
        url = public_http_url(url)
        target_url = url
        request_url = url
        if self.proxy and self.proxy_mode == "relay":
            relay = public_http_url(self.proxy.replace("{url}", quote(target_url, safe=""))) if "{url}" in self.proxy else public_http_url(self.proxy)
            if "{url}" not in self.proxy:
                separator = "&" if "?" in relay else "?"
                request_url = relay + separator + urlencode({"url": target_url})
            else:
                request_url = relay
        host = urlparse(target_url).hostname or ""
        elapsed = time.monotonic() - self.last_by_host.get(host, 0)
        if elapsed < self.gap:
            time.sleep(self.gap - elapsed)
        headers = {}
        if referer:
            headers["Referer"] = referer
        if self.proxy_mode == "relay":
            headers["X-Proxy-UA"] = USER_AGENT
            if referer: headers["X-Proxy-Referer"] = referer
            if self.worker_key: headers["X-Proxy-Key"] = self.worker_key
        if accept_json:
            headers["Accept"] = "application/json,text/plain,*/*"
        last_error = ""
        for attempt in range(3):
            try:
                response = self.session.get(
                    request_url, headers=headers, timeout=self.timeout,
                    allow_redirects=True, verify=self.verify, stream=True,
                )
                self.last_by_host[host] = time.monotonic()
                body = response.raw.read(MAX_HTML_BYTES + 1, decode_content=True)
                if len(body) > MAX_HTML_BYTES:
                    raise FetchError("پاسخ بزرگ‌تر از ۱۲ مگابایت است")
                response.encoding = response.encoding or response.apparent_encoding or "utf-8"
                text = body.decode(response.encoding, errors="replace")
                if response.status_code in (429, 500, 502, 503, 504) and attempt < 2:
                    time.sleep(1.5 * (2 ** attempt))
                    continue
                if not 200 <= response.status_code < 400:
                    raise FetchError(f"HTTP {response.status_code} برای {url}")
                return FetchResult(
                    target_url if self.proxy_mode == "relay" else response.url, text, response.headers.get("Content-Type", ""),
                    response.status_code,
                )
            except (requests.RequestException, FetchError) as exc:
                last_error = str(exc)
                if attempt < 2:
                    time.sleep(1.0 * (2 ** attempt))
        raise FetchError(last_error or "دریافت صفحه ناموفق بود")


# ---------------------------------------------------------------------------
# Normalization and product conversion
# ---------------------------------------------------------------------------
def clean_text(value: Any) -> str:
    if value is None:
        return ""
    if isinstance(value, (dict, list)):
        return ""
    return re.sub(r"\s+", " ", str(value).translate(PERSIAN_DIGITS)).strip()


def absolute_url(value: Any, base: str) -> str:
    value = clean_text(value)
    if not value or value.startswith(("data:", "javascript:", "#")):
        return ""
    return urljoin(base, value)


def image_value(value: Any, base: str) -> str:
    if isinstance(value, list):
        for item in value:
            found = image_value(item, base)
            if found:
                return found
        return ""
    if isinstance(value, dict):
        for key in ("url", "src", "webp_url", "image_url", "original", "800", "main"):
            if key in value:
                found = image_value(value[key], base)
                if found:
                    return found
        for item in value.values():
            found = image_value(item, base)
            if found:
                return found
        return ""
    return absolute_url(value, base)


def extract_price(value: Any) -> str:
    """Normalize visible price text using scraper4.php's currency-aware rules."""
    text = clean_text(value)
    if not text:
        return ""
    currency = r"تومان|تومن|ریال|ر\.ی|USD|EUR|GBP|AED|TRY|CAD|AUD|CHF|JPY|CNY|£|\$|€|¥|₽|₺|₹|﷼"
    number = r"\d(?:[\d,،٬.٫\s]*\d)?"
    matches = re.findall(rf"(?:({number})\s*({currency})|({currency})\s*({number}))", text, re.I)
    if matches:
        choices = []
        for left, right_cur, left_cur, right in matches:
            raw, cur = (left, right_cur) if left else (right, left_cur)
            digits = re.sub(r"\D", "", raw)
            if digits:
                choices.append((len(digits), clean_text(f"{cur} {raw}" if left_cur else f"{raw} {cur}")))
        if choices:
            return max(choices, key=lambda item: item[0])[1]
    grouped = re.findall(r"\d{1,3}(?:[,،٬\s]\d{3})+", text)
    if grouped:
        return max(grouped, key=lambda item: len(re.sub(r"\D", "", item))) + " تومان"
    nums = [x for x in re.findall(r"\d{4,}", text) if int(x) >= 1000]
    return (max(nums, key=int) + " تومان") if nums else ""


def product_key(product: dict[str, Any]) -> str:
    link = re.sub(r"[?#].*$", "", clean_text(product.get("link"))).rstrip("/")
    if link:
        identity = "url:" + link
    else:
        identity = "title:" + clean_text(product.get("title")).lower() + "|" + clean_text(product.get("price"))
    return hashlib.md5(identity.encode("utf-8", "ignore")).hexdigest()


def add_product(store: dict[str, dict[str, Any]], product: Optional[dict[str, Any]]) -> None:
    if not product:
        return
    key = product_key(product)
    if key in store:
        old = store[key]
        for field, value in product.items():
            if value not in ("", None, [], {}) and old.get(field) in ("", None, [], {}):
                old[field] = value
    elif len(store) < MAX_PRODUCTS_HARD:
        product["key"] = key
        store[key] = product


# ---------------------------------------------------------------------------
# HTML, selector and hydration extraction
# ---------------------------------------------------------------------------
def select_value(node: Tag, selector: str, kind: str, base: str) -> str:
    if not selector:
        return ""
    try:
        matches = node.select(selector)
    except Exception as exc:
        raise ValueError(f"سلکتور نامعتبر ({kind}): {exc}") from exc
    for match in matches:
        if kind == "link":
            value = match.get("href") or (match.find("a", href=True) or {}).get("href", "")
            value = absolute_url(value, base)
        elif kind == "image":
            value = ""
            for attr in ("data-zoom", "data-large", "data-src", "data-lazy-src", "src"):
                if match.get(attr):
                    value = absolute_url(match.get(attr), base)
                    break
            if not value:
                img = match.find("img")
                value = image_value(dict(img.attrs) if img else "", base)
        else:
            value = clean_text(match.get_text(" ", strip=True))
        if value:
            return value
    return ""


def parse_selectors(soup: BeautifulSoup, base: str, selectors: dict[str, str]) -> list[dict[str, Any]]:
    container = clean_text(selectors.get("container"))
    if not container:
        return []
    try:
        nodes = soup.select(container)
    except Exception as exc:
        raise ValueError(f"سلکتور ظرف نامعتبر است: {exc}") from exc
    out: list[dict[str, Any]] = []
    for node in nodes:
        title = select_value(node, selectors.get("title", ""), "title", base)
        if not title:
            candidate = node.select_one("h1,h2,h3,h4,[class*='title'],a[title]")
            title = clean_text(candidate.get("title") or candidate.get_text(" ", strip=True)) if candidate else ""
        price = extract_price(select_value(node, selectors.get("price", ""), "price", base))
        if not price:
            candidate = node.select_one("[class*='price'],[class*='amount'],ins")
            price = extract_price(candidate.get_text(" ", strip=True)) if candidate else ""
        link = select_value(node, selectors.get("link", ""), "link", base)
        if not link:
            candidate = node if node.name == "a" and node.get("href") else node.find("a", href=True)
            link = absolute_url(candidate.get("href"), base) if candidate else ""
        image = select_value(node, selectors.get("image", ""), "image", base)
        if not image:
            img = node.find("img")
            if img:
                image = next((absolute_url(img.get(a), base) for a in ("data-src", "data-lazy-src", "src") if img.get(a)), "")
        sku = select_value(node, selectors.get("sku", ""), "sku", base)
        if title or link:
            out.append({"title": title[:300], "price": price, "link": link, "image": image, "sku": sku})
    return out


def _html_product(node: Tag, base: str, selectors: Optional[dict[str, str]] = None) -> Optional[dict[str, Any]]:
    """PHP-compatible DOM extraction: selectors first, then structural HTML guesses."""
    selectors = selectors or {}
    title = select_value(node, selectors.get("title", ""), "title", base)
    if not title:
        candidate = node.select_one("h1,h2,h3,h4,[class*='title'],[class*='name'],a[title]")
        title = clean_text((candidate.get("title") if candidate else "") or (candidate.get_text(" ", strip=True) if candidate else ""))
    if not title:
        image_title = node.find("img")
        title = clean_text((image_title.get("alt") or image_title.get("title")) if image_title else "")
    if not title:
        pieces = [clean_text(x) for x in node.stripped_strings]
        pieces = [x for x in pieces if len(x) > 3 and not re.fullmatch(r"[%0-9,،٬.٫ تومانریال]+", x)]
        title = max(pieces, key=len, default="")
    price = extract_price(select_value(node, selectors.get("price", ""), "price", base))
    if not price:
        candidate = node.select_one("[class*='price'],[class*='amount'],ins,[itemprop='price']")
        price = extract_price((candidate.get("content") or candidate.get_text(" ", strip=True)) if candidate else "")
    if not price:
        price = extract_price(node.get_text(" ", strip=True))
    link = select_value(node, selectors.get("link", ""), "link", base)
    if not link:
        candidate = node if node.name == "a" and node.get("href") else node.find("a", href=True)
        link = absolute_url(candidate.get("href"), base) if candidate else ""
    image = select_value(node, selectors.get("image", ""), "image", base)
    if not image:
        img = node.find("img")
        if img:
            image = next((absolute_url(img.get(a), base) for a in ("data-zoom-image","data-large_image","data-src","data-lazy-src","src") if img.get(a)), "")
    sku = select_value(node, selectors.get("sku", ""), "sku", base) or clean_text(node.get("data-product-id", ""))
    if not title and not link:
        return None
    return {"title": title[:300], "price": price, "link": link, "image": image, "sku": sku}


def parse_html(text: str, base: str, selectors: Optional[dict[str, str]] = None) -> tuple[list[dict[str, Any]], BeautifulSoup, dict[str, int]]:
    """Parse only the downloaded/rendered DOM, matching scraper4.php (never APIs/hydration)."""
    soup = BeautifulSoup(text, "lxml")
    store: dict[str, dict[str, Any]] = {}
    selectors = selectors or {}
    selector_rows = parse_selectors(soup, base, selectors) if selectors.get("container") else []
    for row in selector_rows:
        add_product(store, row)
    if not store:
        candidates = soup.select("li.product,article[class*='product'],div.product-card,div.product-item,div[class*='product-card'],div[class*='product-item'],[data-product-id],[itemtype*='Product']")
        # Like PHP's outer-container repair: if one wrapper was selected, descend to repeated cards.
        if len(candidates) == 1:
            nested = candidates[0].select("li,article,div[class*='product'],[data-product-id]")
            if len(nested) > 1:
                candidates = nested
        for node in candidates:
            add_product(store, _html_product(node, base, selectors))
    if not store:
        # PHP-style last fallback: product links with images are reliable even
        # when a shop uses unknown generated class names (e.g. barfbox.ir).
        for link in soup.select("a[href*='/product/'],a[href*='/products/'],a[href*='/shop/']"):
            if not link.find("img") and not link.select_one("[class*='price']"): continue
            node: Tag = link
            for _ in range(5):
                parent=node.parent
                if not isinstance(parent,Tag): break
                node=parent
                if node.find("img") and extract_price(node.get_text(" ",strip=True)): break
            add_product(store,_html_product(node,base,selectors))
    return list(store.values()), soup, {"selector_matches": len(selector_rows), "dom_products": len(store), "html_bytes": len(text.encode("utf-8", "ignore"))}


def parse_detail_fields(soup: BeautifulSoup, base: str, selectors: dict[str, str]) -> dict[str, Any]:
    """Extract phase-one detail fields with custom selectors plus safe fallbacks."""
    out: dict[str, Any] = {}
    mapping = {
        "sku": ("sku", "[itemprop='sku'],[class*='sku']"),
        "weight": ("weight", "[class*='weight'],[data-weight]"),
        "category": ("category", "[class*='breadcrumb'] a:last-child,[itemprop='category']"),
        "brand": ("brand", "[itemprop='brand'],[class*='brand']"),
        "stock": ("stock", "[class*='stock'],[class*='availability']"),
        "short_desc": ("short_desc", "[class*='short-description'],[class*='excerpt']"),
        "long_desc": ("long_desc", "[itemprop='description'],[class*='description']"),
    }
    for field, (selector_key, fallback) in mapping.items():
        selector = clean_text(selectors.get(selector_key)) or fallback
        try:
            node = soup.select_one(selector)
        except Exception as exc:
            raise ValueError(f"سلکتور جزئیات {field} نامعتبر است: {exc}") from exc
        if node:
            out[field] = clean_text(node.get_text(" ", strip=True))[:20000]
    variation_selector = clean_text(selectors.get("variations"))
    if variation_selector:
        groups, flat = [], []
        for selector in re.split(r"[\n|]+", variation_selector):
            selector = selector.strip()
            if not selector: continue
            try: boxes = soup.select(selector)
            except Exception as exc: raise ValueError(f"سلکتور تنوع نامعتبر است: {exc}") from exc
            for box in boxes[:20]:
                name = clean_text(box.get("data-name") or box.get("name") or box.get("aria-label") or "تنوع")
                values = []
                options = box.select("option,button,li,label,[data-value],[class*='swatch']")
                if not options: options = list(box.children) if isinstance(box, Tag) else []
                for option in options:
                    if not isinstance(option, Tag): continue
                    value = clean_text(option.get("data-value") or option.get("value") or option.get("title") or option.get_text(" ", strip=True))
                    if value and value.lower() not in {"انتخاب", "choose", "select"} and value not in values: values.append(value[:100])
                if values:
                    groups.append({"name": name[:100], "values": values[:50]})
                    for value in values:
                        if value not in flat: flat.append(value)
        if groups:
            out["variation_groups"], out["variations"], out["variations_text"] = groups, flat, "، ".join(flat)
    price_selector = clean_text(selectors.get("price")) or "[itemprop='price'],[class*='price']"
    try:
        price_node = soup.select_one(price_selector)
    except Exception as exc:
        raise ValueError(f"سلکتور قیمت جزئیات نامعتبر است: {exc}") from exc
    if price_node:
        out["price"] = extract_price(price_node.get("content") or price_node.get_text(" ", strip=True))
    image_selector = clean_text(selectors.get("gallery")) or "[class*='gallery'] img,[class*='product'] img"
    images: list[str] = []
    try:
        image_nodes = soup.select(image_selector)
    except Exception as exc:
        raise ValueError(f"سلکتور گالری نامعتبر است: {exc}") from exc
    for node in image_nodes[:40]:
        for attr in ("data-zoom", "data-large", "data-src", "data-lazy-src", "src"):
            url = absolute_url(node.get(attr), base)
            if url and url not in images:
                images.append(url)
                break
    if images:
        out["image"], out["images"] = images[0], images[:20]
    return {key: value for key, value in out.items() if value not in ("", [], None)}


# ---------------------------------------------------------------------------
# PHP-compatible HTML extraction and optional browser rendering
# ---------------------------------------------------------------------------
def page_url(original: str, page: int, kind: str, value: str) -> str:
    if page <= 1:
        return original
    parsed = urlparse(original)
    if kind == "path":
        # Match PHP build_page_url_custom(path_pattern): append the pattern to
        # the original listing path, not merely to the domain root.
        pattern=value or "/page/{page}/";replacement=pattern.replace("{page}",str(page));root=f"{parsed.scheme}://{parsed.netloc}"
        base_path=re.sub(r"/page/\d+/?$","",parsed.path.rstrip("/"),flags=re.I)
        return root+base_path+(replacement if replacement.startswith("/") else "/"+replacement)
    if kind == "full":
        return value.replace("{page}",str(page)) if value else original
    param = value or "page"
    query = parse_qs(parsed.query, keep_blank_values=True)
    query[param] = [str(page)]
    return urlunparse(parsed._replace(query=urlencode(query, doseq=True)))



def temporary_browser_path() -> str:
    user = re.sub(r"[^A-Za-z0-9_.-]", "_", os.path.basename(os.path.expanduser("~")) or "user")
    return os.path.join(tempfile.gettempdir(), f"scraper4-{user}-playwright")


def configured_browser_path() -> str:
    try:
        path = clean_text(load_data().get("runtime", {}).get("playwright_path"))
    except Exception:
        path = ""
    return path if path and os.path.isdir(path) else os.path.join(BASE_DIR, "ms-playwright")


def find_browser_executable(preferred: str = "") -> str:
    roots = []
    for root in (preferred, os.path.join(BASE_DIR, "ms-playwright"), temporary_browser_path(), os.path.expanduser("~/.cache/ms-playwright")):
        root = os.path.abspath(root) if root else ""
        if root and root not in roots and os.path.isdir(root): roots.append(root)
    names = {"chrome", "chromium", "chrome-headless-shell", "headless_shell", "google-chrome"}
    candidates = []
    for root in roots:
        for directory, _, files in os.walk(root):
            for name in files:
                path = os.path.join(directory, name)
                if name in names and os.access(path, os.X_OK): candidates.append(path)
    # Prefer the compact headless shell, then newest installed executable.
    candidates.sort(key=lambda x: ("headless" not in x.lower(), -os.path.getmtime(x)))
    return candidates[0] if candidates else ""


def render_playwright(url: str, timeout: int, scrolls: int = 4) -> FetchResult:
    browser_path = configured_browser_path()
    os.environ["PLAYWRIGHT_BROWSERS_PATH"] = browser_path
    try:
        from playwright.sync_api import sync_playwright
    except ImportError as exc:
        raise FetchError("Playwright نصب نیست؛ از بخش به‌روزرسانی «نصب وابستگی‌ها» را اجرا کنید") from exc
    public_http_url(url)
    try:
        with sync_playwright() as pw:
            expected = pw.chromium.executable_path
            executable = expected if expected and os.path.isfile(expected) else find_browser_executable(browser_path)
            if not executable:
                raise FetchError("فایل اجرایی مرورگر پیدا نشد. دکمه «نصب سبک Playwright» را اجرا کنید؛ در صورت کمبود سهمیه، مرورگر خودکار در فضای موقت نصب می‌شود.")
            network=load_data().get("network",{});network_mode=outbound_mode(network);launch_options={"headless":True,"executable_path":executable,"args":["--no-sandbox","--disable-dev-shm-usage"]}
            if network_mode=="http" and network.get("proxy"):launch_options["proxy"]={"server":str(network["proxy"])}
            browser = pw.chromium.launch(**launch_options);page = browser.new_page(user_agent=USER_AGENT,locale="fa-IR")
            if network_mode=="relay":
                relay_headers={"X-Proxy-UA":USER_AGENT}
                if network.get("worker_key"):relay_headers["X-Proxy-Key"]=str(network["worker_key"])
                page.set_extra_http_headers(relay_headers)
            page.goto(outbound_browser_target(url), wait_until="networkidle", timeout=timeout * 1000)
            for _ in range(max(0, min(12, scrolls))):
                page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
                page.wait_for_timeout(700)
            html = page.content()
            final_url = page.url
            browser.close()
        return FetchResult(final_url, html, "text/html", 200, "browser")
    except FetchError:
        raise
    except Exception as exc:
        raise FetchError(f"مرورگر headless ناموفق بود: {exc}") from exc


@dataclass
class ScrapeReport:
    products: dict[str, dict[str, Any]] = field(default_factory=dict)
    logs: list[str] = field(default_factory=list)
    pages: int = 0
    modes: set[str] = field(default_factory=set)
    diagnostics: dict[str, Any] = field(default_factory=dict)
    job_id: str = ""


def save_extract_checkpoint(job_id: str, config: dict[str, Any], report: ScrapeReport, next_page: int, next_url: str = "", status: str = "running") -> None:
    """Atomic per-page checkpoint, safe even when several profiles run concurrently."""
    with DATA_LOCK:
        data = load_data()
        jobs = data.setdefault("extract_jobs", {})
        public_config = {k: v for k, v in config.items() if not str(k).startswith("_") and k != "job_id"}
        jobs[job_id] = {"id": job_id, "profile": clean_text(config.get("_profile_name")), "status": status, "updated_at": int(time.time()), "next_page": next_page, "next_url": next_url, "total": len(report.products), "config": public_config, "products": list(report.products.values()), "logs": report.logs[-20:]}
        # Free-plan storage guard: retain only the 12 newest resumable reports.
        if len(jobs) > 12:
            for key in sorted(jobs, key=lambda x: int(jobs[x].get("updated_at", 0)))[:-12]:jobs.pop(key, None)
        save_data(data)
    task_id=clean_text(config.get("_live_task_id"))
    if task_id:
        pages=max(1,int(config.get("pages",1)));done=max(0,min(pages,next_page-1));percent=min(88,round(done/pages*88));last=report.logs[-1] if report.logs else f"آماده‌سازی صفحه {next_page}"
        current=live_task_read(task_id);elapsed=max(1,int(time.time()-int(current.get("created_at",time.time()))));eta=int(elapsed/done*(pages-done)) if done else 0
        live_task_update(task_id,percent,f"صفحه {done} از {pages}","running","محصول‌ها: "+str(len(report.products))+" · "+last,done=done,total=pages,extracted=len(report.products),elapsed_seconds=elapsed,eta_seconds=eta)


def scrape(config: dict[str, Any]) -> ScrapeReport:
    source = public_http_url(str(config.get("url", "")))
    pages = max(1, min(MAX_PAGES_HARD, int(config.get("pages", 1))))
    mode = str(config.get("render", "auto"))
    selectors = config.get("selectors") if isinstance(config.get("selectors"), dict) else {}
    detail_selectors = config.get("detail_selectors") if isinstance(config.get("detail_selectors"), dict) else {}
    pag_kind = str(config.get("pagination", "query"))
    pag_value = str(config.get("page_value", "page"))
    enrich = bool(config.get("enrich", False))
    detail_limit = max(0, min(100, int(config.get("detail_limit", 20))))
    cfg = load_data()
    fetcher = Fetcher(cfg["network"])
    job_id = re.sub(r"[^a-zA-Z0-9_-]", "", clean_text(config.get("job_id")))[:80] or ("job-" + time.strftime("%Y%m%d-%H%M%S") + "-" + hashlib.sha1(source.encode()).hexdigest()[:6])
    report = ScrapeReport(job_id=job_id)
    for old in config.get("_resume_products", []) if isinstance(config.get("_resume_products"), list) else []:
        if isinstance(old, dict): add_product(report.products, old)
    start_page = max(1, min(pages, int(config.get("_start_page", 1))))
    next_url = str(config.get("_next_url", ""))
    save_extract_checkpoint(job_id, config, report, start_page, next_url)

    for number in range(start_page, pages + 1):
        task_id=clean_text(config.get("_live_task_id"))
        if task_id and live_task_cancelled(task_id):raise ValueError("استخراج با درخواست کاربر متوقف شد")
        if pag_kind == "next" and number > 1:
            if not next_url:
                report.logs.append("صفحه‌بندی متوقف شد: لینک صفحه بعد پیدا نشد")
                break
            url = next_url
        else:
            url = page_url(source, number, pag_kind, pag_value)
        rows: list[dict[str, Any]] = []
        soup: Optional[BeautifulSoup] = None
        diag: dict[str, Any] = {}
        fetch_error = ""

        # scraper4.php strategy: fetch the page DOM and run selectors. In auto mode,
        # Playwright is only a DOM renderer fallback; it never calls a product API.
        if mode != "browser":
            try:
                if task_id:live_task_update(task_id,max(3,round((number-1)/pages*88)),f"دریافت HTML صفحه {number} از {pages}","running",f"درخواست شبکه آغاز شد · {url}",done=number-1,total=pages,extracted=len(report.products))
                result = fetcher.get(url)
                rows, soup, diag = parse_html(result.text, result.url, selectors)
                report.modes.add("html")
                report.logs.append(f"صفحه {number}: {len(rows)} محصول از DOM صفحه")
            except (FetchError, ValueError) as exc:
                fetch_error = str(exc)

        if not rows and mode in ("auto", "browser"):
            try:
                if task_id:live_task_update(task_id,max(4,round((number-1)/pages*88)+2),f"رندر مرورگر صفحه {number} از {pages}","running",("HTML محصولی نداشت"+(" · "+fetch_error if fetch_error else "")+"؛ Playwright در حال اجراست"),done=number-1,total=pages,extracted=len(report.products))
                result = render_playwright(url, fetcher.timeout, int(config.get("scrolls", 4)))
                rows, soup, diag = parse_html(result.text, result.url, selectors)
                report.modes.add("playwright-dom")
                report.logs.append(f"صفحه {number}: {len(rows)} محصول از DOM رندرشده")
            except (FetchError, ValueError) as exc:
                fetch_error = str(exc)

        if pag_kind == "next" and soup is not None:
            try:
                next_node = soup.select_one(pag_value or "a[rel='next']")
                next_url = absolute_url(next_node.get("href"), url) if next_node else ""
            except Exception as exc:
                raise ValueError(f"سلکتور صفحه بعد نامعتبر است: {exc}") from exc

        new_count = 0
        for row in rows:
            before = len(report.products)
            add_product(report.products, row)
            new_count += len(report.products) - before
        report.pages = number
        report.diagnostics[f"page_{number}"] = diag
        save_extract_checkpoint(job_id, config, report, number + 1, next_url)
        if not rows:
            report.logs.append(f"صفحه {number}: محصولی پیدا نشد" + (f" — {fetch_error}" if fetch_error else ""))
            if number == 1 and fetch_error:
                report.diagnostics["error"] = fetch_error
            break
        if number > 1 and new_count == 0:
            report.logs.append("صفحه‌بندی متوقف شد: محصول تازه‌ای نبود")
            break

    if enrich and report.products:
        enriched = 0
        for product in list(report.products.values()):
            if enriched >= detail_limit:
                break
            if not product.get("link"):
                continue
            if not detail_selectors and product.get("image") and product.get("price") and product.get("images"):
                continue
            try:
                detail = fetcher.get(product["link"], referer=source)
                detail_rows, detail_soup, _ = parse_html(detail.text, detail.url)
                custom_detail = parse_detail_fields(detail_soup, detail.url, detail_selectors)
                for key, value in custom_detail.items():
                    if value not in ("", None, [], {}):
                        product[key] = value
                # Prefer JSON product whose title resembles the list title.
                candidate = max(detail_rows, key=lambda x: int(clean_text(x.get("title")) in clean_text(product.get("title"))), default=None)
                if candidate:
                    for key, value in candidate.items():
                        if value not in ("", None, [], {}) and product.get(key) in ("", None, [], {}):
                            product[key] = value
                if not product.get("image"):
                    meta = detail_soup.select_one("meta[property='og:image'],meta[name='twitter:image']")
                    if meta:
                        product["image"] = absolute_url(meta.get("content"), detail.url)
                enriched += 1
            except (FetchError, ValueError):
                continue
        report.logs.append(f"جزئیات {enriched} محصول ناقص بررسی شد")
    rules=config.get("profile_rules") if isinstance(config.get("profile_rules"),dict) else {}
    suffix=clean_text(rules.get("title_suffix"));prefix=clean_text(rules.get("title_prefix"));mode=str(rules.get("price_mode","none"));value=float(rules.get("price_value",0) or 0);step=max(0,int(rules.get("price_round",0) or 0));default_stock=clean_text(rules.get("default_stock"));default_category=clean_text(rules.get("default_category"))
    for product in report.products.values():
        title=clean_text(product.get("title"))
        if prefix and not title.startswith(prefix):title=prefix+" "+title
        if suffix and not title.endswith(suffix):title=title+" "+suffix
        product["title"]=title
        raw=woo_price(product.get("source_price") or product.get("price"));product["source_price"]=raw
        price=float(raw or 0)
        if mode=="percent":price*=1+value/100
        elif mode=="multiplier":price*=value
        elif mode=="fixed":price+=value
        if step:price=round(price/step)*step
        if price>0:product["price"]=str(max(0,round(price)))
        if default_stock and product.get("stock") in (None,""):product["stock"]=default_stock
        if default_category and not product.get("category"):product["category"]=default_category
        if rules.get("bsl_category_id"):product["basalam_category_id"]=int(rules["bsl_category_id"])
        if rules.get("woo_category_id"):product["woo_category_id"]=int(rules["woo_category_id"])
    save_extract_checkpoint(job_id, config, report, report.pages + 1, next_url, "completed")
    return report


# ---------------------------------------------------------------------------
# WooCommerce and exports
# ---------------------------------------------------------------------------
def woo_request(method: str, endpoint: str, payload: Any = None) -> requests.Response:
    cfg=load_data()["woocommerce"];base=str(cfg.get("url","")).rstrip("/");ck,cs=str(cfg.get("consumer_key","")),str(cfg.get("consumer_secret",""))
    if not base or not ck or not cs:raise ValueError("اتصال WooCommerce کامل نیست")
    target=public_http_url(base)+"/wp-json/wc/v3/"+endpoint.lstrip("/");network=load_data().get("network",{});mode=outbound_mode(network);headers={"User-Agent":USER_AGENT,"Accept":"application/json"};auth=(ck,cs)
    if mode=="relay":
        parsed=urlparse(target);query=parse_qs(parsed.query,keep_blank_values=True);query.update({"consumer_key":[ck],"consumer_secret":[cs]});target=urlunparse(parsed._replace(query=urlencode(query,doseq=True)));auth=None
    try:response=outbound_request(method,target,json=payload,auth=auth,headers=headers,timeout=60)
    except requests.RequestException as exc:raise FetchError(f"اتصال ووکامرس از دروازه مرکزی ناموفق بود: {exc}") from exc
    if not response.ok:raise FetchError(f"WooCommerce {getattr(response,'scraper4_transport','gateway')} HTTP {response.status_code}: {clean_text(response.text)[:500]}")
    return response


def woo_price(value: Any) -> str:
    digits = re.sub(r"[^0-9]", "", clean_text(value))
    return digits.lstrip("0") or "0"


def woo_product_payload(product: dict[str, Any], status: str = "draft") -> dict[str, Any]:
    groups = product.get("variation_groups") if isinstance(product.get("variation_groups"), list) else []
    payload: dict[str, Any] = {
        "name": clean_text(product.get("title")) or "بدون نام",
        "type": "variable" if groups else "simple", "regular_price": woo_price(product.get("price")),
        "status": status if status in {"draft", "publish", "private", "pending"} else "draft",
    }
    for source, target in (("sku", "sku"), ("short_desc", "short_description"), ("long_desc", "description")):
        if product.get(source): payload[target] = str(product[source])
    images = list(product.get("images", [])) if isinstance(product.get("images"), list) else []
    if product.get("image") and product["image"] not in images: images.insert(0, product["image"])
    if images: payload["images"] = [{"src": str(url)} for url in images[:20] if url]
    if product.get("stock") not in (None, ""):
        stock_text = clean_text(product.get("stock")).lower()
        quantity = re.sub(r"\D", "", stock_text)
        payload["manage_stock"] = bool(quantity)
        if quantity: payload["stock_quantity"] = int(quantity)
        payload["stock_status"] = "outofstock" if stock_text in {"0", "ناموجود", "false"} else "instock"
    if groups:
        payload["attributes"] = [{"name": clean_text(g.get("name")) or f"گزینه {i+1}", "visible": True, "variation": True, "options": [clean_text(v) for v in g.get("values", [])[:50] if clean_text(v)]} for i, g in enumerate(groups[:3])]
    if product.get("weight"): payload["weight"] = re.sub(r"[^0-9.]", "", clean_text(product.get("weight")))
    if product.get("category"): payload["categories"] = [{"name": clean_text(product.get("category"))}]
    meta = []
    if product.get("link"): meta.append({"key": "_scraper_source_url", "value": product["link"]})
    if product.get("brand"): meta.append({"key": "_scraper_source_brand", "value": product["brand"]})
    if meta: payload["meta_data"] = meta
    return payload


def woo_send_one(product: dict[str, Any], status: str, update_existing: bool) -> dict[str, Any]:
    payload = woo_product_payload(product, status)
    existing_id = 0
    sku = clean_text(product.get("sku"))
    if update_existing and sku:
        found = woo_request("GET", "products?per_page=1&sku=" + quote(sku)).json()
        if isinstance(found, list) and found: existing_id = int(found[0].get("id", 0))
    if payload.get("categories"):
        category_name = clean_text(payload["categories"][0].get("name"))
        category_id = 0
        if category_name:
            found_categories = woo_request("GET", "products/categories?per_page=20&search=" + quote(category_name)).json()
            exact = next((row for row in found_categories if clean_text(row.get("name")).lower() == category_name.lower()), None) if isinstance(found_categories, list) else None
            if exact:
                category_id = int(exact.get("id", 0))
            else:
                category_id = int(woo_request("POST", "products/categories", {"name": category_name}).json().get("id", 0))
        payload["categories"] = [{"id": category_id}] if category_id else []
    result = woo_request("PUT" if existing_id else "POST", f"products/{existing_id}" if existing_id else "products", payload).json()
    parent_id = int(result.get("id", 0))
    groups = product.get("variation_groups") if isinstance(product.get("variation_groups"), list) else []
    variation_count = 0
    if parent_id and groups:
        existing_combos = set()
        if existing_id:
            old_variations = woo_request("GET", f"products/{parent_id}/variations?per_page=100").json()
            if isinstance(old_variations, list):
                for row in old_variations:
                    existing_combos.add(tuple((clean_text(a.get("name")), clean_text(a.get("option"))) for a in row.get("attributes", [])))
        # One child per option for one group; bounded Cartesian combinations for multiple groups.
        combinations = [[]]
        for group in groups[:3]:
            combinations = [old + [(clean_text(group.get("name")) or "تنوع", clean_text(value))] for old in combinations for value in group.get("values", [])[:20] if clean_text(value)][:50]
        for combo in combinations:
            combo_key = tuple(combo)
            if combo_key in existing_combos:
                continue
            child = {"regular_price": woo_price(product.get("price")), "attributes": [{"name": name, "option": value} for name, value in combo]}
            woo_request("POST", f"products/{parent_id}/variations", child)
            variation_count += 1
    return {"source": product.get("title"), "id": parent_id, "name": result.get("name"), "action": "updated" if existing_id else "created", "variations": variation_count}


def export_csv(products: list[dict[str, Any]]) -> Response:
    stream = io.StringIO()
    stream.write("\ufeff")
    writer = csv.writer(stream)
    fields = ["title", "price", "link", "image", "sku", "stock", "brand"]
    writer.writerow(["#"] + fields)
    for index, product in enumerate(products, 1):
        writer.writerow([index] + [product.get(field, "") for field in fields])
    return Response(stream.getvalue(), mimetype="text/csv; charset=utf-8", headers={
        "Content-Disposition": f'attachment; filename="products-{time.strftime("%Y%m%d-%H%M%S")}.csv"'
    })


def export_xlsx(products: list[dict[str, Any]]) -> Response:
    fields = ["title", "price", "link", "image", "sku", "stock", "brand", "short_desc", "long_desc"]
    rows = [["#"] + fields] + [[index] + [p.get(field, "") for field in fields] for index, p in enumerate(products, 1)]
    sheet_rows = []
    for r_index, row in enumerate(rows, 1):
        cells = []
        for c_index, value in enumerate(row, 1):
            number = c_index
            col = ""
            while number:
                number, remainder = divmod(number - 1, 26)
                col = chr(65 + remainder) + col
            text = escape(clean_text(value), quote=False)
            cells.append(f'<c r="{col}{r_index}" t="inlineStr"><is><t>{text}</t></is></c>')
        sheet_rows.append(f'<row r="{r_index}">{"".join(cells)}</row>')
    sheet = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>' + "".join(sheet_rows) + '</sheetData></worksheet>'
    output = io.BytesIO()
    with zipfile.ZipFile(output, "w", zipfile.ZIP_DEFLATED) as archive:
        archive.writestr("[Content_Types].xml", '<?xml version="1.0"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/><Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/></Types>')
        archive.writestr("_rels/.rels", '<?xml version="1.0"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/></Relationships>')
        archive.writestr("xl/workbook.xml", '<?xml version="1.0"?><workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships"><sheets><sheet name="Products" sheetId="1" r:id="rId1"/></sheets></workbook>')
        archive.writestr("xl/_rels/workbook.xml.rels", '<?xml version="1.0"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/></Relationships>')
        archive.writestr("xl/worksheets/sheet1.xml", sheet)
    return Response(output.getvalue(), mimetype="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", headers={"Content-Disposition": f'attachment; filename="products-{time.strftime("%Y%m%d-%H%M%S")}.xlsx"'})


# ---------------------------------------------------------------------------
# Atomic GitHub deployer (Python counterpart of deploy.php)
# ---------------------------------------------------------------------------
DEPLOY_LOCK = threading.Lock()
DEPENDENCY_LOCK = threading.Lock()


def git_blob_sha(content: bytes) -> str:
    return hashlib.sha1(b"blob " + str(len(content)).encode("ascii") + b"\0" + content).hexdigest()


def deploy_config(data: Optional[dict[str, Any]] = None) -> dict[str, str]:
    raw = (data or load_data()).get("deploy", {})
    return {
        "repo": clean_text(raw.get("repo")) or "fazilatma/amphp",
        "branch": clean_text(raw.get("branch")) or "arena/01a0640f-amphp",
        "path": clean_text(raw.get("path")) or "scraper4.py",
        "github_token": os.environ.get("GITHUB_TOKEN", "").strip() or clean_text(raw.get("github_token")),
        "reload_file": os.path.expanduser(clean_text(raw.get("reload_file"))),
    }


def github_file(cfg: dict[str, str], include_content: bool = False) -> dict[str, Any]:
    repo = cfg["repo"].strip("/")
    if not re.fullmatch(r"[A-Za-z0-9_.-]+/[A-Za-z0-9_.-]+", repo):
        raise ValueError("نام repository باید به صورت owner/repo باشد")
    remote_path = cfg["path"].strip("/")
    if not remote_path or not remote_path.endswith(".py") or ".." in remote_path.split("/"):
        raise ValueError("مسیر منبع باید یک فایل امن با پسوند .py باشد")
    api_url = "https://api.github.com/repos/" + repo + "/contents/" + quote(remote_path, safe="/")
    headers = {"User-Agent": "scraper4-python-deployer", "Accept": "application/vnd.github+json"}
    if cfg.get("github_token"):
        headers["Authorization"] = "Bearer " + cfg["github_token"]
    try:
        response = outbound_request("GET",api_url,params={"ref":cfg["branch"]},headers=headers,timeout=30)
    except requests.RequestException as exc:
        raise FetchError(f"ارتباط با GitHub ناموفق بود: {exc}") from exc
    if response.status_code == 401:
        raise FetchError("توکن GitHub نامعتبر یا منقضی است (HTTP 401)")
    if response.status_code == 403:
        raise FetchError("دسترسی GitHub رد شد یا محدودیت نرخ تمام شده است (HTTP 403)")
    if response.status_code == 404:
        raise FetchError("repository، branch یا فایل در GitHub پیدا نشد (HTTP 404)")
    if not response.ok:
        raise FetchError(f"GitHub HTTP {response.status_code}: {response.text[:250]}")
    try:
        meta = response.json()
    except ValueError as exc:
        raise FetchError("پاسخ GitHub معتبر نیست") from exc
    if not isinstance(meta, dict) or meta.get("type") != "file":
        raise FetchError("مسیر GitHub یک فایل نیست")
    if int(meta.get("size") or 0) > 2 * 1024 * 1024:
        raise FetchError("فایل به‌روزرسانی بزرگ‌تر از ۲ مگابایت است")
    out = {"sha": str(meta.get("sha", "")), "size": int(meta.get("size") or 0),
           "html_url": str(meta.get("html_url", "")), "name": str(meta.get("name", ""))}
    if include_content:
        encoded = str(meta.get("content", "")).replace("\n", "")
        try:
            content = base64.b64decode(encoded, validate=True)
        except (ValueError, TypeError) as exc:
            raise FetchError("محتوای فایل از GitHub قابل خواندن نیست") from exc
        out["content"] = content
    return out


def validate_deploy_source(content: bytes) -> str:
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
    required = ("APP_VERSION", "Flask(", '@app.get("/")', "/api/deploy/run")
    missing = [marker for marker in required if marker not in text]
    if missing:
        raise FetchError("نسخه تازه اعتبارسنجی نشد؛ نشانه‌های Scraper4 کامل نیست")
    match = re.search(r'^APP_VERSION\s*=\s*["\']([^"\']+)', text, re.MULTILINE)
    return match.group(1) if match else "unknown"


def atomic_write(path: str, content: bytes, mode: int = 0o600) -> None:
    directory = os.path.dirname(path) or "."
    os.makedirs(directory, exist_ok=True)
    fd, temporary = tempfile.mkstemp(prefix=".deploy-", suffix=".tmp", dir=directory)
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


def touch_reload_file(path: str) -> bool:
    if not path:
        return False
    # PythonAnywhere's WSGI file is outside this app directory by design. Do
    # not create an arbitrary path from the web UI; only touch an existing file.
    if not os.path.isfile(path):
        raise FetchError("فایل WSGI برای reload پیدا نشد؛ مسیر را بررسی کنید")
    os.utime(path, None)
    return True


def deploy_check() -> dict[str, Any]:
    cfg = deploy_config()
    remote = github_file(cfg)
    with open(os.path.abspath(__file__), "rb") as fh:
        local = fh.read()
    return {
        "repo": cfg["repo"], "branch": cfg["branch"], "path": cfg["path"],
        "local_sha": git_blob_sha(local), "remote_sha": remote["sha"],
        "update_available": git_blob_sha(local) != remote["sha"],
        "remote_size": remote["size"], "html_url": remote["html_url"],
        "version": APP_VERSION,
    }


def deploy_install() -> dict[str, Any]:
    if not DEPLOY_LOCK.acquire(blocking=False):
        raise FetchError("یک نصب دیگر هم‌اکنون در حال اجراست")
    try:
        cfg = deploy_config()
        remote = github_file(cfg, include_content=True)
        content = remote["content"]
        new_version = validate_deploy_source(content)
        target = os.path.abspath(__file__)
        with open(target, "rb") as fh:
            current = fh.read()
        if git_blob_sha(current) == remote["sha"]:
            sdk_installed=ensure_basalam_sdk() if b"basalam-sdk" in content else False
            return {"changed": False, "message": "همین نسخه اکنون نصب است"+("؛ SDK باسلام نیز خودکار نصب شد" if sdk_installed else ""), "version": APP_VERSION,"dependencies_repaired":sdk_installed}
        old_mode = os.stat(target).st_mode & 0o777
        backup = target + ".bak"
        atomic_write(backup, current, old_mode)
        atomic_write(target, content, old_mode)
        sdk_installed=ensure_basalam_sdk() if b"basalam-sdk" in content else False
        reloaded = touch_reload_file(cfg["reload_file"]) if cfg["reload_file"] else False
        return {"changed": True, "message": "نسخه تازه اتمیک نصب شد"+(" و SDK باسلام نیز نصب شد" if sdk_installed else ""), "version": new_version,
                "sha": remote["sha"], "backup": os.path.basename(backup), "reload_requested": reloaded,"dependencies_repaired":sdk_installed}
    finally:
        DEPLOY_LOCK.release()


def deploy_rollback() -> dict[str, Any]:
    if not DEPLOY_LOCK.acquire(blocking=False):
        raise FetchError("یک عملیات نصب دیگر در حال اجراست")
    try:
        target = os.path.abspath(__file__)
        backup = target + ".bak"
        if not os.path.isfile(backup):
            raise FetchError("نسخه پشتیبان scraper4.py.bak وجود ندارد")
        with open(backup, "rb") as fh:
            content = fh.read()
        version = validate_deploy_source(content)
        current_mode = os.stat(target).st_mode & 0o777
        atomic_write(target, content, current_mode)
        cfg = deploy_config()
        reloaded = touch_reload_file(cfg["reload_file"]) if cfg["reload_file"] else False
        return {"changed": True, "message": "نسخه پشتیبان بازیابی شد", "version": version,
                "reload_requested": reloaded}
    finally:
        DEPLOY_LOCK.release()


# ---------------------------------------------------------------------------
# Automatic code updates and browser build detection
# ---------------------------------------------------------------------------
AUTO_UPDATE_STATE = {"last": 0.0, "running": False, "error": ""}
AUTO_UPDATE_LOCK = threading.Lock()


def pythonanywhere_reload() -> bool:
    """Reload the current PythonAnywhere app when its local API token exists."""
    token_file = os.path.expanduser("~/.pythonanywhere_api_token")
    try:
        token = open(token_file, encoding="utf-8").read().strip()
        if not token:
            return False
        username = os.path.basename(os.path.expanduser("~"))
        domain = username.lower() + ".pythonanywhere.com"
        url = f"https://www.pythonanywhere.com/api/v0/user/{username}/webapps/{domain}/reload/"
        response = outbound_request("POST",url,headers={"Authorization":"Token "+token},timeout=30)
        return response.status_code in (200, 201)
    except Exception:
        return False


def auto_update_worker() -> None:
    global BUILD_ID
    try:
        cfg = deploy_config()
        remote = github_file(cfg, include_content=True)
        target = os.path.abspath(__file__)
        with open(target, "rb") as fh:
            current = fh.read()
        if git_blob_sha(current) != remote["sha"]:
            new_version = validate_deploy_source(remote["content"])
            mode = os.stat(target).st_mode & 0o777
            atomic_write(target + ".bak", current, mode)
            atomic_write(target, remote["content"], mode)
            AUTO_UPDATE_STATE["error"] = ""
            app.logger.info("Automatically installed Scraper4 %s", new_version)
            reload_file = cfg.get("reload_file", "")
            if reload_file and os.path.isfile(reload_file):
                os.utime(reload_file, None)
            pythonanywhere_reload()
    except Exception as exc:
        AUTO_UPDATE_STATE["error"] = str(exc)[:300]
        app.logger.warning("Automatic update check failed: %s", exc)
    finally:
        AUTO_UPDATE_STATE["last"] = time.time()
        AUTO_UPDATE_STATE["running"] = False
        try:
            AUTO_UPDATE_LOCK.release()
        except RuntimeError:
            pass


@app.before_request
def schedule_auto_update():
    if not AUTO_UPDATE_ENABLED or request.path.startswith("/api/deploy/"):
        return None
    now = time.time()
    if now - float(AUTO_UPDATE_STATE["last"]) < AUTO_UPDATE_INTERVAL:
        return None
    if AUTO_UPDATE_LOCK.acquire(blocking=False):
        AUTO_UPDATE_STATE["running"] = True
        threading.Thread(target=auto_update_worker, name="scraper4-auto-update", daemon=True).start()
    return None


# ---------------------------------------------------------------------------
# Flask API
# ---------------------------------------------------------------------------
def live_task_disk_write(task: dict[str,Any]) -> None:
    os.makedirs(LIVE_TASK_DIR,mode=0o700,exist_ok=True);atomic_write(os.path.join(LIVE_TASK_DIR,task["id"]+".json"),json.dumps(task,ensure_ascii=False).encode(),0o600)


def live_task_create(kind: str, title: str, private: bool=True) -> dict[str,Any]:
    task={"id":"task-"+secrets.token_hex(8),"kind":kind,"title":title,"private":private,"status":"waiting","progress":0,"step":"در صف اجرا","details":[],"created_at":int(time.time()),"updated_at":int(time.time())}
    with LIVE_TASK_LOCK:
        LIVE_TASKS[task["id"]]=task;live_task_disk_write(task)
        for key in sorted(LIVE_TASKS,key=lambda x:LIVE_TASKS[x]["updated_at"])[:-30]:LIVE_TASKS.pop(key,None)
    return task


def live_task_update(task_id: str, progress: int, step: str, status: str="running", detail: str="", **extra: Any) -> None:
    with LIVE_TASK_LOCK:
        task=LIVE_TASKS.get(task_id)
        if not task:return
        task.update(progress=max(0,min(100,int(progress))),step=clean_text(step),status=status,updated_at=int(time.time()),**extra)
        if detail:task.setdefault("details",[]).append({"at":time.strftime("%H:%M:%S"),"text":clean_text(detail)[:700]});task["details"]=task["details"][-80:]
        live_task_disk_write(task)


def live_task_read(task_id: str) -> dict[str,Any]:
    with LIVE_TASK_LOCK:task=dict(LIVE_TASKS.get(task_id,{ }))
    if not task and re.fullmatch(r"task-[0-9a-f]{16}",task_id):
        try:
            with open(os.path.join(LIVE_TASK_DIR,task_id+".json"),encoding="utf-8") as fh:task=json.load(fh)
        except (OSError,ValueError):task={}
    return task


def live_task_cancelled(task_id: str) -> bool:
    return bool(live_task_read(task_id).get("cancel_requested"))


@app.get("/api/tasks/summary")
def api_live_tasks_summary():
    rows=[]
    try:
        for name in os.listdir(LIVE_TASK_DIR):
            if re.fullmatch(r"task-[0-9a-f]{16}\.json",name):
                try:
                    with open(os.path.join(LIVE_TASK_DIR,name),encoding="utf-8") as fh:row=json.load(fh)
                    if isinstance(row,dict):rows.append(row)
                except (OSError,ValueError):pass
    except OSError:pass
    with LIVE_TASK_LOCK:rows.extend(dict(x) for x in LIVE_TASKS.values())
    unique={x.get("id"):x for x in rows if x.get("id")};now=int(time.time());active=sum(x.get("status") in {"waiting","running"} and now-int(x.get("updated_at",now))<=600 for x in unique.values())
    return jsonify(ok=True,active=active,total=len(unique),attention=sum(x.get("status") in {"failed","interrupted"} for x in unique.values()))


@app.get("/api/tasks")
def api_live_tasks():
    if not deploy_authorized():return deploy_auth_error()
    rows: dict[str,dict[str,Any]]={}
    try:
        for name in os.listdir(LIVE_TASK_DIR):
            if re.fullmatch(r"task-[0-9a-f]{16}\.json",name):
                try:
                    with open(os.path.join(LIVE_TASK_DIR,name),encoding="utf-8") as fh:row=json.load(fh)
                    if isinstance(row,dict):rows[row.get("id",name[:-5])]=row
                except (OSError,ValueError):pass
    except OSError:pass
    with LIVE_TASK_LOCK:rows.update({k:dict(v) for k,v in LIVE_TASKS.items()})
    now=int(time.time())
    for row in rows.values():
        if row.get("status") in {"waiting","running"} and now-int(row.get("updated_at",now))>600:
            row["status"]="interrupted";row["step"]="فرآیند سرور قطع یا Reload شده است";row["updated_at"]=now
            with LIVE_TASK_LOCK:LIVE_TASKS[row["id"]]=row
            live_task_disk_write(row)
    extract_jobs=load_data().get("extract_jobs",{})
    for row in rows.values():
        if row.get("kind")=="scrape" and not row.get("profile"):
            row["profile"]=clean_text((extract_jobs.get(row.get("id"),{}) or {}).get("profile")) or "پروفایل نامشخص"
    result=sorted(rows.values(),key=lambda x:int(x.get("updated_at",0)),reverse=True)[:100]
    return jsonify(ok=True,tasks=result,summary={"running":sum(x.get("status") in {"waiting","running"} for x in result),"completed":sum(x.get("status")=="completed" for x in result),"failed":sum(x.get("status") in {"failed","interrupted"} for x in result)})


@app.post("/api/tasks/<task_id>/cancel")
def api_live_task_cancel(task_id: str):
    if not deploy_authorized():return deploy_auth_error()
    with LIVE_TASK_LOCK:
        task=LIVE_TASKS.get(task_id) or live_task_read(task_id)
        if not task:return jsonify(ok=False,error="وظیفه پیدا نشد"),404
        if task.get("status") in {"completed","failed","cancelled"}:return jsonify(ok=True,task=task)
        task["cancel_requested"]=True;task["step"]="درخواست توقف ثبت شد";task["updated_at"]=int(time.time());LIVE_TASKS[task_id]=task;live_task_disk_write(task)
    return jsonify(ok=True,task=task)


@app.post("/api/tasks/<task_id>/retry")
def api_live_task_retry(task_id: str):
    if not deploy_authorized():return deploy_auth_error()
    task=live_task_read(task_id)
    if task.get("kind")!="scrape" or task.get("status") not in {"failed","interrupted","cancelled"}:return jsonify(ok=False,error="فقط استخراج قطع‌شده یا ناموفق قابل اجرای مجدد است"),400
    job=load_data().get("extract_jobs",{}).get(task_id)
    if not isinstance(job,dict) or not isinstance(job.get("config"),dict):return jsonify(ok=False,error="checkpoint این استخراج پیدا نشد"),404
    cfg=dict(job["config"]);cfg["_profile_name"]=clean_text(task.get("profile"));cfg.update({"_start_page":max(1,int(job.get("next_page",1))),"_next_url":job.get("next_url",""),"_resume_products":job.get("products",[])})
    task.update(status="waiting",progress=max(0,int(task.get("progress",0))),step="اجرای مجدد مستقل آغاز شد",cancel_requested=False,updated_at=int(time.time()));task.setdefault("details",[]).append({"at":time.strftime("%H:%M:%S"),"text":"ادامه از آخرین checkpoint درخواست شد"})
    with LIVE_TASK_LOCK:LIVE_TASKS[task_id]=task;live_task_disk_write(task)
    threading.Thread(target=scrape_live_worker,args=(task_id,cfg),name="scrape-retry",daemon=True).start();return jsonify(ok=True,task=task)


@app.delete("/api/tasks/<task_id>")
def api_live_task_delete(task_id: str):
    if not deploy_authorized():return deploy_auth_error()
    task=live_task_read(task_id)
    if task.get("status") in {"waiting","running"} and not task.get("cancel_requested"):return jsonify(ok=False,error="ابتدا وظیفه فعال را متوقف کنید"),409
    with LIVE_TASK_LOCK:LIVE_TASKS.pop(task_id,None)
    try:os.unlink(os.path.join(LIVE_TASK_DIR,task_id+".json"))
    except OSError:pass
    return jsonify(ok=True)


@app.get("/api/changelog")
def api_changelog():
    return jsonify(ok=True,current=APP_VERSION,releases=CHANGELOG)


@app.get("/api/tasks/<task_id>")
def api_live_task(task_id: str):
    task=live_task_read(task_id)
    if not task:return jsonify(ok=False,error="وظیفه زنده پیدا نشد یا فایل موقت آن پاک شده است"),404
    if task.get("private",True) and not deploy_authorized():return deploy_auth_error()
    return jsonify(ok=True,task=task)


@app.get("/health")
def health():
    return jsonify(ok=True, version=APP_VERSION, build=BUILD_ID,
                   auto_update=AUTO_UPDATE_ENABLED, update_error=AUTO_UPDATE_STATE["error"])


@app.get("/")
def index():
    return Response(INDEX_HTML, mimetype="text/html; charset=utf-8")


@app.get("/api/config")
def get_config():
    data = load_data()
    woo = dict(data["woocommerce"])
    if woo.get("consumer_key"):
        woo["consumer_key"] = "••••" + woo["consumer_key"][-4:]
    if woo.get("consumer_secret"):
        woo["consumer_secret"] = "••••" + woo["consumer_secret"][-4:]
    if woo.get("worker_key"):
        woo["worker_key"] = "••••" + str(woo["worker_key"])[-4:]
    deploy = dict(data["deploy"])
    deploy["has_github_token"] = bool(os.environ.get("GITHUB_TOKEN", "") or deploy.get("github_token"))
    deploy["github_token"] = ""
    network = dict(data["network"])
    if network.get("worker_key"):
        network["worker_key"] = "••••" + str(network["worker_key"])[-4:]
    active=data.get("active_profile","") if data.get("active_profile","") in data["profiles"] else ""
    return jsonify(ok=True, profiles=data["profiles"], active_profile=active, network=network, woocommerce=woo,
                   deploy=deploy, last_count=len(data["last_result"]), version=APP_VERSION,
                   build=BUILD_ID, auto_update=AUTO_UPDATE_ENABLED)


@app.post("/api/settings")
def settings():
    if not deploy_authorized():return deploy_auth_error()
    body = request.get_json(silent=True) or {}
    data = load_data()
    if isinstance(body.get("network"), dict):
        for key in ("timeout", "gap_ms", "proxy", "proxy_mode", "worker_key", "verify_tls"):
            if key in body["network"]:
                value = body["network"][key]
                if key == "worker_key" and str(value).startswith("••••"):
                    continue
                data["network"][key] = value
    if isinstance(body.get("woocommerce"), dict):
        for key in ("url", "consumer_key", "consumer_secret", "api_mode", "relay_url", "worker_key"):
            value = body["woocommerce"].get(key)
            if value is not None and not str(value).startswith("••••"):
                data["woocommerce"][key] = str(value).strip()
    if isinstance(body.get("deploy"), dict):
        if not deploy_authorized():
            return deploy_auth_error()
        for key in ("repo", "branch", "path", "reload_file"):
            if key in body["deploy"]:
                data["deploy"][key] = str(body["deploy"][key]).strip()
        # Blank means keep the existing token; explicit clear_token removes it.
        token = str(body["deploy"].get("github_token", "")).strip()
        if token:
            data["deploy"]["github_token"] = token
        if body["deploy"].get("clear_token"):
            data["deploy"]["github_token"] = ""
    save_data(data)
    return jsonify(ok=True)


@app.post("/api/network/diagnose")
def api_network_diagnose():
    if not deploy_authorized():return deploy_auth_error()
    cfg=load_data().get("network",{});mode=clean_text(cfg.get("proxy_mode","auto"));canary="Scraper4-Canary-"+secrets.token_hex(4)
    try:
        response=outbound_request("POST","https://httpbin.org/anything",headers={"Authorization":"Bearer "+canary,"User-Agent":"Scraper4-Gateway-Test"},json={"probe":"scraper4","method":"POST"},timeout=30);payload=response.json() if response.ok else {};headers=payload.get("headers",{}) if isinstance(payload,dict) else {};auth=next((str(v) for k,v in headers.items() if k.lower()=="authorization"),"");method=str(payload.get("method","")).upper();json_body=payload.get("json");result={"reachable":response.ok,"authorization":auth=="Bearer "+canary,"method":method=="POST","json":isinstance(json_body,dict) and json_body.get("probe")=="scraper4","status":response.status_code,"transport":getattr(response,"scraper4_transport",mode)};result["ready_for_authenticated_api"]=all(result[k] for k in ("reachable","authorization","method","json"));return jsonify(ok=True,**result)
    except Exception as exc:return jsonify(ok=False,error=f"آزمایش دروازه مرکزی ناموفق بود: {exc}",transport=mode),400


def cleanup_dependency_space() -> int:
    """Remove disposable caches and stale installer backups, never user data."""
    freed = 0
    targets = [os.path.expanduser("~/.cache/pip"), os.path.expanduser("~/.cache/ms-playwright"), os.path.join(BASE_DIR, "__pycache__")]
    for target in targets:
        if not os.path.exists(target): continue
        for root, _, files in os.walk(target):
            for name in files:
                try: freed += os.path.getsize(os.path.join(root, name))
                except OSError: pass
        shutil.rmtree(target, ignore_errors=True)
    for prefix in ("scraper4.py.", "wsgi-"):
        backups = sorted((x for x in os.listdir(BASE_DIR) if x.startswith(prefix) and x.endswith(".bak")), reverse=True)
        for name in backups[2:]:
            path = os.path.join(BASE_DIR, name)
            try: freed += os.path.getsize(path); os.unlink(path)
            except OSError: pass
    return freed


def playwright_browser_ready(root: str) -> bool:
    if not os.path.isdir(root): return False
    executable_names = {"chrome", "chromium", "chrome-headless-shell", "headless_shell"}
    for directory, _, files in os.walk(root):
        if any(name in executable_names and os.access(os.path.join(directory, name), os.X_OK) for name in files): return True
    return False


def playwright_runtime_ready(python_bin: str, env: dict[str, str]) -> bool:
    """Check the executable revision expected by the currently installed package."""
    probe = "from playwright.sync_api import sync_playwright;import os; p=sync_playwright().start(); x=p.chromium.executable_path; p.stop(); raise SystemExit(0 if os.path.isfile(x) else 1)"
    try:
        return subprocess.run([python_bin, "-c", probe], env=env, capture_output=True, timeout=30).returncode == 0
    except (OSError, subprocess.TimeoutExpired):
        return False


def safe_remove_generated(path: str, allowed_roots: list[str]) -> int:
    """Delete one known generated path without ever following symlinks."""
    absolute = os.path.abspath(os.path.expanduser(path))
    if not any(os.path.commonpath([absolute, root]) == root for root in allowed_roots):
        return 0
    size = 0
    try:
        if os.path.islink(absolute):
            os.unlink(absolute); return 0
        if os.path.isfile(absolute):
            size = os.path.getsize(absolute); os.unlink(absolute); return size
        if not os.path.isdir(absolute): return 0
        for directory, _, files in os.walk(absolute):
            for name in files:
                try: size += os.path.getsize(os.path.join(directory, name))
                except OSError: pass
        shutil.rmtree(absolute, ignore_errors=True)
    except OSError:
        return 0
    return size


def account_cleanup() -> dict[str, Any]:
    """Clean only provably disposable account data; preserve system and user content."""
    home = os.path.abspath(os.path.expanduser("~")); app_root = os.path.abspath(BASE_DIR)
    allowed = [home, app_root]
    candidates = [
        os.path.join(home, ".cache", "pip"), os.path.join(home, ".cache", "uv"),
        os.path.join(home, ".cache", "pypoetry"), os.path.join(home, ".cache", "ms-playwright"),
        os.path.join(home, ".npm", "_cacache"), os.path.join(home, ".npm", "_logs"),
        os.path.join(app_root, "__pycache__"), os.path.join(app_root, ".pytest_cache"),
    ]
    freed, removed = 0, []
    for path in candidates:
        amount = safe_remove_generated(path, allowed)
        if amount or not os.path.exists(path):
            if amount: removed.append(os.path.relpath(path, home))
            freed += amount
    # Interrupted installers and atomic-write leftovers in the dedicated app folder.
    temp_patterns = re.compile(r"^(?:\.download-|\.pa-auth-|\.pa-response-|\.scraper4-|\.wsgi\.tmp)")
    try:
        for name in os.listdir(app_root):
            if temp_patterns.match(name):
                path=os.path.join(app_root,name); amount=safe_remove_generated(path,allowed)
                if amount: freed+=amount;removed.append(os.path.relpath(path,home))
    except OSError: pass
    # Keep two newest application/WSGI backups.
    for prefix in ("scraper4.py.", "wsgi-"):
        try: backups=sorted((x for x in os.listdir(app_root) if x.startswith(prefix) and x.endswith(".bak")),reverse=True)
        except OSError: backups=[]
        for name in backups[2:]:
            path=os.path.join(app_root,name);amount=safe_remove_generated(path,allowed)
            if amount:freed+=amount;removed.append(os.path.relpath(path,home))
    # Remove incomplete browser downloads. If lightweight shell exists, full Chromium copies are redundant.
    browser_root=os.path.join(app_root,"ms-playwright")
    headless_ready=any("headless" in directory.lower() and playwright_browser_ready(directory) for directory,dirs,files in os.walk(browser_root)) if os.path.isdir(browser_root) else False
    if os.path.isdir(browser_root):
        for name in os.listdir(browser_root):
            path=os.path.join(browser_root,name)
            redundant=(headless_ready and name.startswith("chromium-") and "headless" not in name)
            incomplete=os.path.isdir(path) and not playwright_browser_ready(path) and not name.startswith("ffmpeg")
            if redundant or incomplete:
                amount=safe_remove_generated(path,allowed)
                if amount:freed+=amount;removed.append(os.path.relpath(path,home))
    # Dedicated venv only: uninstall packages no longer used by Scraper4.
    candidates_py=[os.path.join(app_root,"venv","bin","python"),os.path.join(sys.prefix,"bin","python")]
    python_bin=next((x for x in candidates_py if os.path.isfile(x) and os.access(x,os.X_OK) and "uwsgi" not in os.path.basename(x).lower()),"")
    uninstall_output=""
    if python_bin:
        try:
            run=subprocess.run([python_bin,"-m","pip","uninstall","-y","selenium","cloudscraper","html5lib","trio","trio-websocket"],capture_output=True,text=True,timeout=180)
            uninstall_output=(run.stdout or run.stderr)[-1200:]
        except (OSError,subprocess.TimeoutExpired): pass
    return {"freed_bytes":freed,"freed_mb":round(freed/1048576,1),"removed":removed,"uninstall":uninstall_output}


def bounded_path_size(path: str, deadline: float, counter: list[int]) -> tuple[int, bool]:
    """Folder size without following links, bounded to keep a web request responsive."""
    total, complete = 0, True
    try:
        if os.path.islink(path): return os.lstat(path).st_size, True
        if os.path.isfile(path): return os.path.getsize(path), True
        for directory, dirs, files in os.walk(path, followlinks=False):
            dirs[:] = [name for name in dirs if not os.path.islink(os.path.join(directory, name))]
            for name in files:
                if time.monotonic() > deadline or counter[0] >= 150000: return total, False
                try: total += os.path.getsize(os.path.join(directory, name)); counter[0] += 1
                except OSError: pass
    except OSError:
        complete = False
    return total, complete


def safe_home_path(relative: str) -> tuple[str, str]:
    home = os.path.realpath(os.path.expanduser("~"))
    relative = str(relative or "").strip().replace("\\", "/").lstrip("/")
    target = os.path.realpath(os.path.join(home, relative))
    if os.path.commonpath([home, target]) != home: raise ValueError("مسیر خارج از پوشه حساب مجاز نیست")
    return home, target


@app.get("/api/files")
def api_file_explorer():
    if not deploy_authorized(): return deploy_auth_error()
    try: home, target = safe_home_path(request.args.get("path", ""))
    except ValueError as exc: return jsonify(ok=False, error=str(exc)), 400
    if not os.path.isdir(target): return jsonify(ok=False, error="پوشه پیدا نشد"), 404
    deadline=time.monotonic()+8.0; counter=[0]; entries=[]
    try: children=list(os.scandir(target))[:250]
    except OSError as exc: return jsonify(ok=False,error=f"خواندن پوشه ممکن نیست: {exc}"),400
    children.sort(key=lambda item:(not item.is_dir(follow_symlinks=False),item.name.lower()))
    for item in children:
        if time.monotonic()>deadline: break
        try:
            is_dir=item.is_dir(follow_symlinks=False); size,complete=bounded_path_size(item.path,deadline,counter)
            stat=item.stat(follow_symlinks=False)
            rel=os.path.relpath(item.path,home); rel="" if rel=="." else rel
            entries.append({"name":item.name,"path":rel,"directory":is_dir,"symlink":item.is_symlink(),"size":size,"complete":complete,"modified":int(stat.st_mtime),"protected":item.name.startswith(".") or os.path.realpath(item.path)==os.path.realpath(BASE_DIR)})
        except OSError: continue
    disk=shutil.disk_usage(home); account_used,account_complete=bounded_path_size(home,time.monotonic()+8.0,[0])
    quota_text=""
    try:
        q=subprocess.run(["quota","-s"],capture_output=True,text=True,timeout=8)
        quota_text=(q.stdout or q.stderr).strip()[-2000:]
    except (OSError,subprocess.TimeoutExpired): pass
    quota_used = quota_limit = quota_remaining = None
    def quota_number(token: str) -> Optional[int]:
        match=re.fullmatch(r"([0-9.]+)([KMGTP]?)",token.strip(),re.I)
        if not match:return None
        value=float(match.group(1));unit=match.group(2).upper()
        return int(value*(1024**({"":1,"K":1,"M":2,"G":3,"T":4,"P":5}[unit])))
    for line in quota_text.splitlines():
        parts=line.split()
        if len(parts)>=4 and (parts[0].startswith("/") or parts[0].startswith("*") or ":" in parts[0]):
            used=quota_number(parts[1].rstrip("*"));soft=quota_number(parts[2]);hard=quota_number(parts[3])
            limit=hard or soft
            if used is not None and limit:
                quota_used,quota_limit,quota_remaining=used,limit,max(0,limit-used);break
    rel_current=os.path.relpath(target,home);rel_current="" if rel_current=="." else rel_current
    parent="" if not rel_current else os.path.dirname(rel_current)
    return jsonify(ok=True,current=rel_current,parent=parent,home=home,entries=entries,truncated=len(children)>len(entries),scanned_files=counter[0],account_used=account_used,account_complete=account_complete,account_quota_used=quota_used,account_quota_limit=quota_limit,account_quota_remaining=quota_remaining,filesystem={"total":disk.total,"used":disk.used,"free":disk.free},quota=quota_text)


@app.post("/api/deploy/cleanup")
def api_deploy_cleanup():
    if not deploy_authorized(): return deploy_auth_error()
    result=account_cleanup()
    return jsonify(ok=True,message=f"پاکسازی امن انجام شد؛ {result['freed_mb']} مگابایت فایل موقت حذف شد",**result)


@app.post("/api/deploy/dependencies")
def api_deploy_dependencies():
    if not deploy_authorized(): return deploy_auth_error()
    # Only runtime requirements are installed. Selenium/cloudscraper/html5lib were
    # unused and consumed scarce free-plan quota.
    packages = ["flask", "requests", "beautifulsoup4", "lxml", "playwright", "basalam-sdk"]
    env = dict(os.environ); browser_root = os.path.join(BASE_DIR, "ms-playwright")
    env["PLAYWRIGHT_BROWSERS_PATH"] = browser_root
    env["PIP_NO_CACHE_DIR"] = "1"
    python_bin = runtime_python_bin()
    if not python_bin: return jsonify(ok=False, error="مفسر Python واقعی پیدا نشد؛ uWSGI برای نصب بسته قابل استفاده نیست"), 500
    freed = cleanup_dependency_space()
    try:
        pip_run = subprocess.run([python_bin, "-m", "pip", "install", "--no-cache-dir", *packages], capture_output=True, text=True, timeout=420, env=env)
        if pip_run.returncode: raise RuntimeError((pip_run.stderr or pip_run.stdout)[-2000:])
        if playwright_runtime_ready(python_bin, env):
            return jsonify(ok=True, browser_installed=True, freed_mb=round(freed/1048576,1), message="کتابخانه و نسخه هماهنگ مرورگر Playwright آماده است")
        # Headless Shell is substantially smaller than full Chromium and is enough
        # for headless DOM rendering on PythonAnywhere.
        browser_run = subprocess.run([python_bin, "-m", "playwright", "install", "chromium-headless-shell"], capture_output=True, text=True, timeout=600, env=env)
        ready = browser_run.returncode == 0 and playwright_runtime_ready(python_bin, env)
        selected_root = browser_root
        warning = ""
        if not ready:
            # PythonAnywhere's /tmp does not consume the account home quota. It is
            # ideal for the browser binary when the free-plan disk is full.
            temp_root = temporary_browser_path()
            shutil.rmtree(temp_root, ignore_errors=True); os.makedirs(temp_root, mode=0o700, exist_ok=True)
            temp_env = dict(env); temp_env["PLAYWRIGHT_BROWSERS_PATH"] = temp_root
            temp_run = subprocess.run([python_bin, "-m", "playwright", "install", "chromium-headless-shell"], capture_output=True, text=True, timeout=600, env=temp_env)
            ready = temp_run.returncode == 0 and playwright_runtime_ready(python_bin, temp_env)
            if ready: selected_root = temp_root
            else: warning = (temp_run.stderr or temp_run.stdout or browser_run.stderr or browser_run.stdout)[-1200:]
        if ready:
            data=load_data(); data.setdefault("runtime", {})["playwright_path"]=selected_root; save_data(data)
            os.environ["PLAYWRIGHT_BROWSERS_PATH"] = selected_root
            place = "فضای موقت خارج از سهمیه" if selected_root == temporary_browser_path() else "پوشه برنامه"
            return jsonify(ok=True, browser_installed=True, browser_path=selected_root, freed_mb=round(freed/1048576,1), message=f"Playwright و مرورگر سبک در {place} نصب و آماده شد")
        return jsonify(ok=True, browser_installed=False, freed_mb=round(freed/1048576,1), message="کتابخانه نصب شد اما دانلود مرورگر هم در پوشه برنامه و هم در فضای موقت ناموفق بود.", warning=warning)
    except (OSError, subprocess.TimeoutExpired, RuntimeError) as exc:
        hint = " ابتدا فایل‌های غیرضروری حساب را حذف کنید." if "quota" in str(exc).lower() else ""
        return jsonify(ok=False, error=f"نصب سبک وابستگی‌ها ناموفق بود: {exc}{hint}", freed_mb=round(freed/1048576,1)), 400


@app.post("/api/deploy/check")
def api_deploy_check():
    if not deploy_authorized():
        return deploy_auth_error()
    try:
        return jsonify(ok=True, **deploy_check())
    except (ValueError, FetchError, OSError) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.post("/api/deploy/run")
def api_deploy_run():
    if not deploy_authorized():
        return deploy_auth_error()
    try:
        return jsonify(ok=True, **deploy_install())
    except (ValueError, FetchError, OSError) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.post("/api/deploy/rollback")
def api_deploy_rollback():
    if not deploy_authorized():
        return deploy_auth_error()
    try:
        return jsonify(ok=True, **deploy_rollback())
    except (ValueError, FetchError, OSError) as exc:
        return jsonify(ok=False, error=str(exc)), 400


def ai_key_preview(key: str) -> str:
    key=str(key).strip()
    return (key[:5]+"…"+key[-4:]) if len(key)>10 else ((key[:2]+"••••") if key else "")


def normalize_ai_providers(value: Any) -> dict[str,dict[str,Any]]:
    if isinstance(value,list):value={str(x.get("id") or f"provider-{i+1}"):x for i,x in enumerate(value) if isinstance(x,dict)}
    if not isinstance(value,dict):raise ValueError("ساختار ارائه‌دهندگان باید object یا array باشد")
    out={}
    for raw_id,raw in value.items():
        if not isinstance(raw,dict):continue
        pid=re.sub(r"[^a-zA-Z0-9_.-]+","-",clean_text(raw.get("id") or raw_id)).strip("-")[:80]
        if not pid:continue
        models=[];seen=set()
        source_models=raw.get("models",[])
        if isinstance(source_models,dict):source_models=[dict(v,id=k) if isinstance(v,dict) else {"id":k,"name":str(v)} for k,v in source_models.items()]
        for model in source_models if isinstance(source_models,list) else []:
            if isinstance(model,str):model={"id":model,"name":model}
            if not isinstance(model,dict):continue
            mid=clean_text(model.get("id") or model.get("model"));
            if not mid or mid in seen:continue
            seen.add(mid);m=dict(model);m["id"]=mid;m["name"]=clean_text(m.get("name") or mid);m["enabled"]=m.get("enabled",True) is not False;models.append(m)
        keys=[];seen_keys=set()
        source_keys=raw.get("apiKeys",[])
        if not isinstance(source_keys,list):source_keys=[]
        legacy=raw.get("apiKey",raw.get("api_key",""));source_keys=([{"key":legacy}]+source_keys) if legacy else source_keys
        for item in source_keys:
            row=item if isinstance(item,dict) else {"key":item};key=str(row.get("key","")).strip()
            if not key or key in seen_keys:continue
            seen_keys.add(key);keys.append({"key":key,"label":clean_text(row.get("label")),"acct":clean_text(row.get("acct")),"enabled":row.get("enabled",True) is not False})
        out[pid]={**raw,"id":pid,"name":clean_text(raw.get("name") or pid),"vendor":clean_text(raw.get("vendor")),"url":clean_text(raw.get("url") or raw.get("endpoint")),"endpoint":clean_text(raw.get("endpoint") or raw.get("url")),"enabled":raw.get("enabled",True) is not False,"apiKey":keys[0]["key"] if keys else str(legacy or ""),"apiKeys":keys,"models":models}
    if not out:raise ValueError("هیچ ارائه‌دهنده معتبر و دارای شناسه‌ای پیدا نشد")
    return out


def ai_public_config(data: Optional[dict[str, Any]] = None) -> dict[str, Any]:
    cfg=dict((data or load_data()).get("ai", {}));key=str(cfg.get("api_key", ""));cfg["api_key"]=ai_key_preview(key);cfg["has_key"]=bool(key)
    return cfg


def ai_provider_summary(data: Optional[dict[str,Any]]=None) -> dict[str,Any]:
    data=data or load_data();providers=[]
    try: normalized=normalize_ai_providers(data.get("ai_providers",{})) if data.get("ai_providers") else {}
    except ValueError:normalized={}
    for p in normalized.values():
        keys=p.get("apiKeys",[]);providers.append({"id":p["id"],"name":p["name"],"vendor":p.get("vendor",""),"url":p.get("url",""),"enabled":p.get("enabled",True),"key_count":len(keys),"key_preview":[ai_key_preview(x.get("key","")) for x in keys],"models":p.get("models",[])})
    return {"providers":providers,"selected":{"provider":data.get("ai",{}).get("provider",""),"model":data.get("ai",{}).get("model","")},"total_models":sum(len(x["models"]) for x in providers)}


def ai_endpoint(url: str) -> str:
    url=public_http_url(url);path=urlparse(url).path.rstrip("/")
    if re.search(r"/(chat/completions|messages|generateContent)$",path,re.I) or ":generateContent" in url:return url
    return url.rstrip("/")+"/chat/completions"


def ai_extract_text(body: Any) -> str:
    if not isinstance(body,dict):return ""
    choices=body.get("choices")
    if isinstance(choices,list) and choices:
        row=choices[0] if isinstance(choices[0],dict) else {};msg=row.get("message",{}) if isinstance(row.get("message"),dict) else {}
        content=msg.get("content") or msg.get("reasoning") or row.get("text")
        if isinstance(content,list):content="".join(str(x.get("text",x.get("content",""))) for x in content if isinstance(x,dict))
        if content:return str(content)
    for key in ("result","data"):
        if isinstance(body.get(key),dict):
            text=ai_extract_text(body[key])
            if text:return text
    return str(body.get("response") or body.get("text") or "")


def ai_chat(prompt: str, provider_id: str="", model_id: str="") -> str:
    data=load_data();cfg=data.get("ai",{});pid=provider_id or clean_text(cfg.get("provider"));model=model_id or clean_text(cfg.get("model"));provider=None
    if data.get("ai_providers"):
        providers=normalize_ai_providers(data["ai_providers"]);provider=providers.get(pid)
        if not provider or provider.get("enabled") is False:provider=next((x for x in providers.values() if x.get("enabled",True) and x.get("models")),None)
    base_url=str((provider or {}).get("url") or cfg.get("endpoint", ""))
    if provider and not model:model=clean_text((provider.get("models") or [{}])[0].get("id"))
    key_rows=[x for x in (provider or {}).get("apiKeys",[]) if x.get("enabled",True) and x.get("key")]
    if not key_rows and cfg.get("api_key"):key_rows=[{"key":str(cfg["api_key"]),"acct":""}]
    if not model:raise ValueError("مدل هوش مصنوعی انتخاب نشده است")
    cloudflare="/ai/run" in base_url.lower()
    endpoint=public_http_url(base_url.rstrip("/")+("/"+model if cloudflare and base_url.rstrip("/").lower().endswith("/ai/run") else "")) if cloudflare else ai_endpoint(base_url)
    if not key_rows and "localhost" not in endpoint:raise ValueError("برای ارائه‌دهنده انتخاب‌شده کلید API ثبت نشده است")
    payload={"model":model,"messages":[{"role":"system","content":str(cfg.get("system_prompt") or "You write accurate Persian WooCommerce product content. Return only requested JSON.")},{"role":"user","content":prompt}],"temperature":float(cfg.get("temperature",.3)),"max_tokens":max(64,min(32000,int(cfg.get("max_tokens",1200))))}
    if cloudflare:payload.pop("model",None)
    errors=[]
    for key_row in key_rows or [{"key":"","acct":""}]:
        key=str(key_row.get("key",""));request_endpoint=endpoint;acct=clean_text(key_row.get("acct"))
        if cloudflare and acct:request_endpoint=re.sub(r"(/accounts/)[^/]+(/)",rf"\g<1>{acct}\2",request_endpoint,count=1,flags=re.I)
        headers={"Content-Type":"application/json","User-Agent":USER_AGENT}
        if key:headers["Authorization"]="Bearer "+key
        try:
            response=outbound_request("POST",request_endpoint,json=payload,headers=headers,timeout=90)
            if response.ok:
                text=ai_extract_text(response.json())
                if text:return text
                raise FetchError("پاسخ مدل خالی بود")
            errors.append(f"HTTP {response.status_code}: {response.text[:240]}")
            if response.status_code not in (401,402,403,429):break
        except (requests.RequestException,ValueError) as exc:errors.append(str(exc));break
    raise FetchError(errors[-1] if errors else "فراخوانی مدل ناموفق بود")


@app.get("/api/ai/providers")
def api_ai_providers():
    if not deploy_authorized():return deploy_auth_error()
    return jsonify(ok=True,**ai_provider_summary())


@app.post("/api/ai/providers/import")
def api_ai_providers_import():
    if not deploy_authorized():return deploy_auth_error()
    upload=request.files.get("file")
    if not upload:return jsonify(ok=False,error="فایل ارائه‌دهندگان ارسال نشده است"),400
    try:
        raw=upload.read(2*1024*1024+1)
        if len(raw)>2*1024*1024:raise ValueError("فایل بزرگ‌تر از ۲ مگابایت است")
        payload=json.loads(raw.decode("utf-8-sig"));providers=payload
        if isinstance(payload,dict) and isinstance(payload.get("files"),dict):providers=php_file_json(payload["files"],"ai_providers.json")
        imported=normalize_ai_providers(providers);data=load_data();providers=normalize_ai_providers(data["ai_providers"]) if data.get("ai_providers") else {};providers.update(imported);data["ai_providers"]=providers
        current=data.get("ai",{}).get("provider");first=providers.get(current) or next((x for x in imported.values() if x.get("enabled",True)),next(iter(imported.values())))
        models=first.get("models",[]);model=models[0]["id"] if models else ""
        data["ai"].update({"provider":first["id"],"endpoint":first["url"],"api_key":first.get("apiKey",""),"model":model});save_data(data)
        return jsonify(ok=True,count=len(imported),total=len(providers),models=sum(len(x["models"]) for x in imported.values()),**ai_provider_summary(data))
    except (ValueError,TypeError,json.JSONDecodeError) as exc:return jsonify(ok=False,error=str(exc)),400


@app.post("/api/ai/providers/save")
def api_ai_provider_save():
    if not deploy_authorized():return deploy_auth_error()
    try:
        body=request.get_json(silent=True) or {};row=body.get("provider",{});data=load_data();allp=normalize_ai_providers(data["ai_providers"]) if data.get("ai_providers") else {};pid=str(row.get("id","new"))
        if body.get("preserve_keys") and pid in allp:
            old_keys=allp[pid].get("apiKeys",[]);row=dict(row);row["apiKeys"]=old_keys+list(row.get("apiKeys",[]))
        new=normalize_ai_providers({pid:row});allp.update(new);data["ai_providers"]=allp;save_data(data);return jsonify(ok=True,**ai_provider_summary(data))
    except (ValueError,TypeError) as exc:return jsonify(ok=False,error=str(exc)),400


@app.delete("/api/ai/providers/<path:provider_id>")
def api_ai_provider_delete(provider_id: str):
    if not deploy_authorized():return deploy_auth_error()
    data=load_data();data.get("ai_providers",{}).pop(provider_id,None)
    if data.get("ai",{}).get("provider")==provider_id:data["ai"].update({"provider":"","model":""})
    save_data(data);return jsonify(ok=True,**ai_provider_summary(data))


@app.post("/api/ai/select")
def api_ai_select():
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {};pid=clean_text(body.get("provider"));mid=clean_text(body.get("model"));data=load_data();providers=normalize_ai_providers(data.get("ai_providers",{}))
    if pid not in providers: return jsonify(ok=False,error="ارائه‌دهنده پیدا نشد"),404
    valid={x["id"] for x in providers[pid].get("models",[])}
    if mid not in valid:return jsonify(ok=False,error="مدل در این ارائه‌دهنده پیدا نشد"),404
    p=providers[pid];data["ai"].update({"provider":pid,"model":mid,"endpoint":p["url"],"api_key":p.get("apiKey","")});save_data(data);return jsonify(ok=True,ai=ai_public_config(data))


@app.get("/api/ai/stats")
def api_ai_stats():
    if not deploy_authorized():return deploy_auth_error()
    data=load_data();providers=normalize_ai_providers(data.get("ai_providers",{})) if data.get("ai_providers") else {};models=[(p,m) for p in providers.values() for m in p.get("models",[])];tested=[m for _,m in models if m.get("tested")];available=[m for _,m in models if m.get("available")];latencies=[int(m.get("latencyMs",0)) for m in available if int(m.get("latencyMs",0))>0]
    by_provider=[]
    for p in providers.values():
        rows=p.get("models",[]);by_provider.append({"id":p["id"],"name":p["name"],"total":len(rows),"tested":sum(bool(m.get("tested")) for m in rows),"available":sum(bool(m.get("available")) for m in rows)})
    total=len(models);return jsonify(ok=True,total=total,tested=len(tested),available=len(available),failed=sum(bool(m.get("tested")) and not bool(m.get("available")) for _,m in models),untested=total-len(tested),free=sum(bool(m.get("free")) for _,m in models),reasoning=sum(bool(m.get("reasoning")) for _,m in models),vision=sum(bool(m.get("vision")) for _,m in models),tool_calling=sum(bool(m.get("toolCalling")) for _,m in models),avg_latency=round(sum(latencies)/len(latencies)) if latencies else 0,coverage_pct=round(len(tested)*100/total) if total else 0,health_pct=round(len(available)*100/len(tested)) if tested else 0,providers=by_provider,candidates=data.get("ai_candidates",[]),master=data.get("ai_master",""))


@app.route("/api/ai/candidates",methods=["GET","POST"])
def api_ai_candidates():
    if not deploy_authorized():return deploy_auth_error()
    data=load_data()
    if request.method=="POST":
        body=request.get_json(silent=True) or {};providers=normalize_ai_providers(data.get("ai_providers",{})) if data.get("ai_providers") else {};valid={(p["id"],m["id"]) for p in providers.values() for m in p.get("models",[])};rows=[]
        for item in body.get("candidates",[]):
            if isinstance(item,dict) and (clean_text(item.get("provider")),clean_text(item.get("model"))) in valid:rows.append({"provider":clean_text(item["provider"]),"model":clean_text(item["model"])})
        rows=list({x["provider"]+"\0"+x["model"]:x for x in rows}.values());master=clean_text(body.get("master"));keys={x["provider"]+"/"+x["model"] for x in rows};data["ai_candidates"]=rows;data["ai_master"]=master if master in keys else "";save_data(data)
    return jsonify(ok=True,candidates=data.get("ai_candidates",[]),master=data.get("ai_master",""))


@app.get("/api/ai/config")
def api_ai_config():
    if not deploy_authorized():return deploy_auth_error()
    data=load_data();return jsonify(ok=True,ai=ai_public_config(data),**ai_provider_summary(data))


@app.post("/api/ai/settings")
def api_ai_settings():
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {};incoming=body.get("ai") if isinstance(body.get("ai"),dict) else {};data=load_data();cfg=data.setdefault("ai",default_data()["ai"])
    for field in ("provider","endpoint","model","temperature","max_tokens","system_prompt"):
        if field in incoming:cfg[field]=incoming[field]
    key=str(incoming.get("api_key","")).strip()
    if key and not key.startswith("••"):cfg["api_key"]=key
    save_data(data);return jsonify(ok=True,ai=ai_public_config(data))


@app.post("/api/ai/test")
def api_ai_test():
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {}
    try:return jsonify(ok=True,answer=ai_chat(str(body.get("prompt") or 'فقط این JSON را برگردان: {"status":"ok"}'),clean_text(body.get("provider")),clean_text(body.get("model")))[:2000])
    except (ValueError,FetchError,requests.RequestException) as exc:return jsonify(ok=False,error=str(exc)),400


def ai_nonchat_model(model: dict[str,Any]) -> bool:
    text=(clean_text(model.get("id"))+" "+clean_text(model.get("name"))).lower()
    return any(x in text for x in ("embedding","embed-","whisper","transcri","tts","speech","image","vision-encoder","moderation","rerank"))


def ai_test_public(job: dict[str,Any]) -> dict[str,Any]:
    rows=job.get("rows",[]);done=[x for x in rows if x.get("status") in ("ok","failed")]
    recommended=max(done,key=lambda x:float(x.get("score",0)),default=None)
    return {"id":job.get("id"),"status":job.get("status"),"created_at":job.get("created_at"),"cursor":job.get("cursor",0),"total":len(rows),"ok_count":sum(x.get("status")=="ok" for x in rows),"failed_count":sum(x.get("status")=="failed" for x in rows),"waiting":len(rows)-len(done),"reply_ok":sum(bool(x.get("reply_ok")) for x in rows),"category_ok":sum(bool(x.get("category_ok")) for x in rows),"recommended":{"provider":recommended.get("provider"),"provider_name":recommended.get("provider_name"),"model":recommended.get("model"),"model_name":recommended.get("model_name"),"score":recommended.get("score",0)} if recommended else None,"options":job.get("options",{}),"rows":rows}


@app.get("/api/ai/test/jobs")
def api_ai_test_jobs():
    if not deploy_authorized():return deploy_auth_error()
    data=load_data();jobs=sorted((ai_test_public(x) for x in data.get("ai_test_jobs",{}).values()),key=lambda x:x.get("created_at",0),reverse=True)
    return jsonify(ok=True,jobs=jobs[:20])


@app.post("/api/ai/test/jobs")
def api_ai_test_start():
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {};per=max(1,min(5000,int(body.get("per_provider",5000))));only=bool(body.get("only_untested",False));skip=body.get("skip_nonchat",True) is not False;rows=[];data=load_data();providers=normalize_ai_providers(data.get("ai_providers",{}))
    for provider in providers.values():
        if provider.get("enabled") is False:continue
        count=0
        for model in provider.get("models",[]):
            if count>=per:break
            if model.get("enabled",True) is False or (skip and ai_nonchat_model(model)) or (only and model.get("tested")):continue
            rows.append({"provider":provider["id"],"provider_name":provider["name"],"model":model["id"],"model_name":model.get("name",model["id"]),"status":"waiting","free":bool(model.get("free")),"reasoning":bool(model.get("reasoning")),"vision":bool(model.get("vision")),"tool_calling":bool(model.get("toolCalling"))});count+=1
    if not rows:return jsonify(ok=False,error="مدل آزمایش‌نشده‌ای مطابق فیلترها پیدا نشد"),400
    jid="ait-"+time.strftime("%Y%m%d-%H%M%S")+"-"+secrets.token_hex(2);job={"id":jid,"status":"waiting","created_at":int(time.time()),"cursor":0,"rows":rows,"options":{"reply_message":clean_text(body.get("reply_message") or "سلام، این محصول موجود است و چه زمانی ارسال می‌شود؟"),"category_title":clean_text(body.get("category_title") or "ادو پرفیوم مردانه دیور ساواج ۱۰۰ میلی‌لیتر"),"per_provider":per,"only_untested":only,"skip_nonchat":skip,"delay_ms":max(0,min(60000,int(body.get("delay_ms",120))))}}
    data.setdefault("ai_test_jobs",{})[jid]=job;save_data(data);return jsonify(ok=True,job=ai_test_public(job))


@app.post("/api/ai/test/jobs/<job_id>/process")
def api_ai_test_process(job_id: str):
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {};batch=max(1,min(5,int(body.get("batch",3))));data=load_data();job=data.get("ai_test_jobs",{}).get(job_id)
    if not job:return jsonify(ok=False,error="صف آزمون پیدا نشد"),404
    job["status"]="running";processed=0
    while int(job.get("cursor",0))<len(job["rows"]) and processed<batch:
        i=int(job["cursor"]);row=job["rows"][i];started=time.monotonic()
        reply_started=time.monotonic();reply="";reply_error="";category="";category_error=""
        try:
            reply=ai_chat("به پیام نمونه مشتری فروشگاه، مؤدبانه، دقیق و کوتاه به فارسی پاسخ بده. اطلاعات ساختگی نساز. پیام مشتری: "+clean_text(job.get("options",{}).get("reply_message") or job.get("options",{}).get("prompt") or "سلام، این محصول موجود است؟"),row["provider"],row["model"])
        except Exception as exc:reply_error=clean_text(exc)[:500]
        reply_ms=round((time.monotonic()-reply_started)*1000);category_started=time.monotonic()
        try:
            category=ai_chat("برای عنوان محصول زیر مناسب‌ترین دسته‌بندی فروشگاهی را تعیین کن. فقط JSON با کلیدهای category و reason برگردان. عنوان: "+clean_text(job.get("options",{}).get("category_title") or "ادو پرفیوم مردانه دیور ساواج"),row["provider"],row["model"])
        except Exception as exc:category_error=clean_text(exc)[:500]
        category_ms=round((time.monotonic()-category_started)*1000);reply=clean_text(reply)[:1000];category=clean_text(category)[:1000];errors=" | ".join(x for x in (reply_error,category_error) if x);both=bool(reply and category)
        row.update(status="ok" if both else "failed",answer=reply,reply=reply,reply_ok=bool(reply),reply_error=reply_error,reply_ms=reply_ms,category=category,category_ok=bool(category),category_error=category_error,category_ms=category_ms,error=errors,latency_ms=round((time.monotonic()-started)*1000))
        row["score"]=round(max(0,(100 if both else 42 if (reply or category) else 0)+(5 if row.get("free") else 0)-min(30,row["latency_ms"]/3000)),1)
        # Persist both customer-reply and categorization health like the PHP model laboratory.
        provider=data.get("ai_providers",{}).get(row["provider"],{});model=next((x for x in provider.get("models",[]) if isinstance(x,dict) and clean_text(x.get("id"))==row["model"]),None)
        if model is not None:model.update({"tested":True,"available":both,"replyAvailable":bool(reply),"categoryAvailable":bool(category),"latencyMs":row["latency_ms"],"lastTestAt":int(time.time()),"testError":errors,"testScore":row["score"]})
        job["cursor"]=i+1;processed+=1
        if job["options"].get("delay_ms") and processed<batch:time.sleep(job["options"]["delay_ms"]/1000)
        save_data(data)
    if job["cursor"]>=len(job["rows"]):job["status"]="completed"
    save_data(data);return jsonify(ok=True,processed=processed,job=ai_test_public(job))


@app.delete("/api/ai/test/jobs/<job_id>")
def api_ai_test_delete(job_id: str):
    if not deploy_authorized():return deploy_auth_error()
    data=load_data();data.get("ai_test_jobs",{}).pop(job_id,None);save_data(data);return jsonify(ok=True)


@app.post("/api/ai/enrich")
def api_ai_enrich():
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {};limit=max(1,min(5,int(body.get("limit",3))))
    data=load_data();done=[];errors=[]
    for product in data.get("last_result",[]):
        if len(done)>=limit:break
        if product.get("short_desc") and product.get("long_desc"):continue
        prompt="محصول زیر را بدون ادعای ساختگی تکمیل کن. فقط JSON با کلیدهای short_desc و long_desc و tags(array) بده:\n"+json.dumps({k:product.get(k) for k in ("title","brand","category","price","variations_text")},ensure_ascii=False)
        try:
            text=ai_chat(prompt);match=re.search(r"\{.*\}",text,re.S);obj=json.loads(match.group(0) if match else text)
            for field in ("short_desc","long_desc","tags"):
                if obj.get(field):product[field]=obj[field]
            done.append(product.get("title"))
        except Exception as exc:errors.append({"title":product.get("title"),"error":str(exc)[:300]})
    save_data(data);return jsonify(ok=not errors,done=done,errors=errors,total=len(done))


@app.post("/api/profile")
def profile_save():
    body = request.get_json(silent=True) or {}
    name = clean_text(body.get("name"))[:100]
    config = body.get("config")
    if not name or not isinstance(config, dict):
        return jsonify(ok=False, error="نام و تنظیمات پروفایل لازم است"), 400
    public_http_url(str(config.get("url", "")))
    data = load_data()
    config=dict(config);config["saved_products"]=[dict(x) for x in data.get("last_result",[])[:MAX_PRODUCTS_HARD] if isinstance(x,dict)]
    data["profiles"][name] = config
    data["active_profile"] = name
    save_data(data)
    return jsonify(ok=True, profiles=data["profiles"], active_profile=name)


@app.delete("/api/profile/<path:name>")
def profile_delete(name: str):
    data = load_data()
    data["profiles"].pop(name, None)
    if data.get("active_profile")==name:data["active_profile"]=""
    save_data(data)
    return jsonify(ok=True, profiles=data["profiles"], active_profile=data.get("active_profile",""))


@app.post("/api/profile/active")
def profile_activate():
    body=request.get_json(silent=True) or {};name=clean_text(body.get("name"));data=load_data()
    if name and name not in data.get("profiles",{}):return jsonify(ok=False,error="پروفایل پیدا نشد"),404
    data["active_profile"]=name;save_data(data);return jsonify(ok=True,active_profile=name,config=data.get("profiles",{}).get(name))


def scrape_result(report: ScrapeReport, profile_name: str="") -> dict[str,Any]:
    products=list(report.products.values())
    with DATA_LOCK:
        data=load_data();target=profile_name if profile_name in data.get("profiles",{}) else data.get("active_profile","");previous_rows=data.get("profiles",{}).get(target,{}).get("saved_products",data["last_result"]) if target else data["last_result"];previous={product_key(p):p for p in previous_rows if isinstance(p,dict)};current={product_key(p):p for p in products}
        comparison={"added":sum(k not in previous for k in current),"removed":sum(k not in current for k in previous),"changed":sum(k in previous and any(str(p.get(f,""))!=str(previous[k].get(f,"")) for f in ("price","stock","image","title")) for k,p in current.items())}
        data["last_result"]=products
        if target:data["profiles"][target]["saved_products"]=[dict(x) for x in products]
        save_data(data)
    return {"products":products,"total":len(products),"pages":report.pages,"modes":sorted(report.modes),"logs":report.logs,"diagnostics":report.diagnostics,"comparison":comparison,"job_id":report.job_id,"profile":target}


def scrape_live_worker(task_id: str, config: dict[str,Any]) -> None:
    if live_task_cancelled(task_id):live_task_update(task_id,0,"پیش از شروع متوقف شد","cancelled","درخواست توقف اجرا شد");return
    try:
        live_task_update(task_id,2,"اعتبارسنجی آدرس و تنظیمات","running","استخراج مستقل و موازی روی سرور آغاز شد؛ وظایف دیگر منتظر این منبع نمی‌مانند",execution="parallel");config=dict(config);config["job_id"]=task_id;config["_live_task_id"]=task_id;report=scrape(config);live_task_update(task_id,92,"ذخیره نتایج روی سرور","running",f"{len(report.products)} محصول آماده شد");result=scrape_result(report,clean_text(config.get("_profile_name")));live_task_update(task_id,100,"استخراج کامل شد","completed",f"{result['total']} محصول از {result['pages']} صفحه",result=result,done=result['pages'],total=result['pages'],extracted=result['total'],eta_seconds=0)
    except Exception as exc:live_task_update(task_id,100,"استخراج ناموفق بود","failed",str(exc),error=clean_text(exc)[:1500])


@app.post("/api/scrape/start")
def api_scrape_start():
    config=request.get_json(silent=True) or {}
    try:public_http_url(str(config.get("url","")))
    except ValueError as exc:return jsonify(ok=False,error=str(exc)),400
    active=load_data().get("active_profile","");config["_profile_name"]=active;title="استخراج محصولات"+(f" · {active}" if active else "");task=live_task_create("scrape",title,private=False);task["profile"]=active;LIVE_TASKS[task["id"]]=task;live_task_disk_write(task);threading.Thread(target=scrape_live_worker,args=(task["id"],config),name="scrape-live",daemon=True).start();return jsonify(ok=True,task=task)


@app.post("/api/scrape")
def api_scrape():
    body=request.get_json(silent=True) or {}
    try:return jsonify(ok=True,**scrape_result(scrape(body)))
    except (ValueError,FetchError) as exc:return jsonify(ok=False,error=str(exc)),400
    except Exception as exc:app.logger.exception("scrape failed");return jsonify(ok=False,error=f"خطای داخلی: {exc}"),500


@app.get("/api/extract/jobs")
def extract_jobs():
    jobs = load_data().get("extract_jobs", {})
    summary = [{k: v for k, v in row.items() if k not in ("products", "config", "logs")} for row in jobs.values()]
    summary.sort(key=lambda row: int(row.get("updated_at", 0)), reverse=True)
    return jsonify(ok=True, jobs=summary)


@app.post("/api/extract/resume/<job_id>")
def extract_resume(job_id: str):
    data = load_data(); job = data.get("extract_jobs", {}).get(job_id)
    if not isinstance(job, dict):
        return jsonify(ok=False, error="نقطه ادامه پیدا نشد"), 404
    if job.get("status") == "completed":
        return jsonify(ok=True, completed=True, products=job.get("products", []), total=job.get("total", 0), job_id=job_id)
    cfg = dict(job.get("config") or {})
    cfg.update({"job_id": job_id, "_start_page": int(job.get("next_page", 1)), "_next_url": job.get("next_url", ""), "_resume_products": job.get("products", [])})
    try:
        report = scrape(cfg); products = list(report.products.values())
        data = load_data(); data["last_result"] = products; save_data(data)
        return jsonify(ok=True, products=products, total=len(products), pages=report.pages, modes=sorted(report.modes), logs=report.logs, job_id=job_id, comparison={})
    except (ValueError, FetchError) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.delete("/api/extract/jobs/<job_id>")
def extract_job_delete(job_id: str):
    data = load_data(); data.get("extract_jobs", {}).pop(job_id, None); save_data(data)
    return jsonify(ok=True)


def read_csv_upload(upload: Any) -> tuple[list[str], list[dict[str, str]]]:
    raw = upload.read(5 * 1024 * 1024 + 1)
    if len(raw) > 5 * 1024 * 1024: raise ValueError("فایل بزرگ‌تر از ۵ مگابایت است")
    text = raw.decode("utf-8-sig", errors="replace")
    try: dialect = csv.Sniffer().sniff(text[:8192], delimiters=",;\t|")
    except csv.Error: dialect = csv.excel
    reader = csv.DictReader(io.StringIO(text), dialect=dialect)
    headers = [clean_text(x) for x in (reader.fieldnames or []) if x]
    rows = [{clean_text(k): clean_text(v) for k, v in row.items() if k} for row in reader]
    return headers, rows[:MAX_PRODUCTS_HARD]


def mapped_import_products(rows: list[dict[str, str]], mapping: dict[str, str], options: dict[str, Any]) -> list[dict[str, Any]]:
    products = []
    multiplier = max(0.0, min(1000.0, float(options.get("price_multiplier", 1) or 1)))
    addition = max(-1e12, min(1e12, float(options.get("price_addition", 0) or 0)))
    prefix, suffix = clean_text(options.get("title_prefix"))[:100], clean_text(options.get("title_suffix"))[:100]
    for row in rows:
        product = {field: clean_text(row.get(column, "")) for field, column in mapping.items() if column}
        product["title"] = clean_text(" ".join(x for x in (prefix, product.get("title", ""), suffix) if x))[:300]
        if product.get("price"):
            number = woo_price(product["price"])
            product["price"] = str(max(0, round(float(number) * multiplier + addition)))
        if product.get("images"):
            product["images"] = [clean_text(x) for x in re.split(r"[|,\n]+", product["images"]) if clean_text(x)][:20]
            product["image"] = product["images"][0] if product["images"] else product.get("image", "")
        if product.get("title") or product.get("link"):
            product["key"] = product_key(product); products.append(product)
    return products


@app.post("/api/import/preview")
def import_preview():
    upload=request.files.get("file")
    if not upload: return jsonify(ok=False,error="فایل CSV ارسال نشده است"),400
    try:
        headers,rows=read_csv_upload(upload)
        return jsonify(ok=True,headers=headers,sample=rows[:5],total=len(rows))
    except ValueError as exc: return jsonify(ok=False,error=str(exc)),400


@app.post("/api/import/apply")
def import_apply():
    upload=request.files.get("file")
    if not upload: return jsonify(ok=False,error="فایل CSV ارسال نشده است"),400
    try:
        headers,rows=read_csv_upload(upload)
        mapping=json.loads(request.form.get("mapping","{}")); options=json.loads(request.form.get("options","{}"))
        if not isinstance(mapping,dict) or not isinstance(options,dict): raise ValueError("تنظیمات نگاشت نامعتبر است")
        products=mapped_import_products(rows,mapping,options)
        data=load_data()
        if options.get("mode")=="append":
            merged={product_key(x):x for x in data["last_result"] if isinstance(x,dict)}
            for product in products: merged[product_key(product)]=product
            products=list(merged.values())[:MAX_PRODUCTS_HARD]
        data["last_result"]=products;save_data(data)
        return jsonify(ok=True,products=products,total=len(products),imported=len(rows))
    except (ValueError,TypeError,json.JSONDecodeError) as exc: return jsonify(ok=False,error=str(exc)),400


@app.post("/api/settings/backup")
def settings_backup():
    if not deploy_authorized(): return deploy_auth_error()
    payload={"format":"scraper4-settings","version":APP_VERSION,"created_at":int(time.time()),"data":load_data()}
    return Response(json.dumps(payload,ensure_ascii=False,indent=2),mimetype="application/json",headers={"Content-Disposition":f'attachment; filename="scraper4-settings-{time.strftime("%Y%m%d-%H%M%S")}.json"',"Cache-Control":"no-store"})


def php_file_json(files: dict[str, Any], name: str) -> Any:
    meta=files.get(name)
    if not isinstance(meta,dict) or not meta.get("b64"):return None
    try:return json.loads(base64.b64decode(str(meta["b64"]),validate=True).decode("utf-8-sig"))
    except (ValueError,TypeError,UnicodeDecodeError,json.JSONDecodeError):return None


def convert_php_profile(row: dict[str, Any]) -> dict[str, Any]:
    selectors=row.get("selectors") if isinstance(row.get("selectors"),dict) else {}
    detail=row.get("detailSelectors") or row.get("detail_selectors") or {}
    if isinstance(detail,dict):
        detail={str(k):(str(v.get("selector",'')) if isinstance(v,dict) and v.get("enabled",True) else (str(v) if not isinstance(v,dict) else '')) for k,v in detail.items()}
    rules={"title_suffix":row.get("titleSuffix",row.get("title_suffix","")),"title_prefix":row.get("titlePrefix",row.get("title_prefix","")),"price_mode":row.get("priceMode",row.get("price_mode","none")),"price_value":row.get("priceVal",row.get("price_value",0)),"price_round":row.get("roundPrice",row.get("price_round",0)),"default_stock":row.get("stock_quantity",row.get("default_stock","")),"default_category":row.get("category",row.get("bslCategoryId","")),"bsl_category_id":row.get("bslCategoryId",0),"woo_category_id":row.get("wooCategoryId",0)}
    pag_type=str(row.get("pagType",row.get("pagination","query")));pag_map={"param":"query","query":"query","query_custom":"query","path":"path","path_pattern":"path","full_pattern":"full","full":"full","next":"next"}
    saved=[];raw_products=row.get("products",[])
    if isinstance(raw_products,dict):raw_products=list(raw_products.values())
    if isinstance(raw_products,list):
        for item in raw_products:
            product=item[1] if isinstance(item,list) and len(item)==2 and isinstance(item[1],dict) else item
            if isinstance(product,dict):saved.append(product)
    return {"url":row.get("url",row.get("list_url","")),"pages":int(row.get("pages",row.get("maxPages",1)) or 1),"render":"auto","pagination":pag_map.get(pag_type,"query"),"page_value":row.get("pagVal",row.get("pageParam","page")),"scrolls":4,"enrich":bool(detail),"detail_limit":int(row.get("detailLimit",20) or 20),"selectors":selectors,"detail_selectors":detail if isinstance(detail,dict) else {},"profile_rules":rules,"saved_products":saved[:MAX_PRODUCTS_HARD]}


def convert_php_bundle(payload: dict[str, Any]) -> tuple[dict[str, Any], list[str]]:
    files=payload.get("files") if isinstance(payload.get("files"),dict) else {};data=load_data();restored=[]
    profiles=php_file_json(files,"profiles.json")
    if isinstance(profiles,dict):
        data["profiles"]={str(k):convert_php_profile(v) for k,v in profiles.items() if isinstance(v,dict)};data["active_profile"]=next(iter(data["profiles"]),"");restored.append("profiles.json")
    connections=php_file_json(files,"connections.json")
    if isinstance(connections,dict):
        woo=connections.get("woocommerce",{});bsl=connections.get("basalam",{})
        if isinstance(woo,dict):data["woocommerce"].update({"url":woo.get("store_url",woo.get("url","")),"consumer_key":woo.get("consumer_key",""),"consumer_secret":woo.get("consumer_secret","")})
        if isinstance(bsl,dict):data["basalam"].update({k:bsl[k] for k in data["basalam"] if k in bsl})
        restored.append("connections.json")
    providers=php_file_json(files,"ai_providers.json")
    if isinstance(providers,dict):
        data["ai_providers"]=providers
        first=next((v for v in providers.values() if isinstance(v,dict) and v.get("enabled",True)),None)
        if first:
            models=first.get("models",[]);model=models[0].get("id","") if models and isinstance(models[0],dict) else ""
            data["ai"].update({"provider":first.get("id",first.get("name","custom")),"endpoint":first.get("endpoint",first.get("url","")),"api_key":first.get("apiKey",first.get("api_key","")),"model":model})
        restored.append("ai_providers.json")
    return data,restored


@app.post("/api/settings/restore")
def settings_restore():
    if not deploy_authorized(): return deploy_auth_error()
    upload=request.files.get("file")
    if not upload:return jsonify(ok=False,error="فایل پشتیبان ارسال نشده است"),400
    raw=upload.read(20*1024*1024+1)
    if len(raw)>20*1024*1024:return jsonify(ok=False,error="فایل پشتیبان بزرگ‌تر از ۲۰ مگابایت است"),400
    try:
        payload=json.loads(raw.decode("utf-8-sig")); restored=payload.get("data") if isinstance(payload,dict) else None
        if isinstance(payload,dict) and isinstance(payload.get("files"),dict):
            clean,php_restored=convert_php_bundle(payload)
            if not php_restored:raise ValueError("بسته PHP خوانده شد اما profiles.json، connections.json یا ai_providers.json معتبر نداشت")
        else:
            if payload.get("format")!="scraper4-settings" or not isinstance(restored,dict):raise ValueError("فایل تنظیمات معتبر Python یا بسته PHP نیست")
            defaults=default_data(); clean={}
            for key,default in defaults.items():clean[key]=restored.get(key,default) if isinstance(restored.get(key,default),type(default)) else default
        if os.path.exists(DATA_FILE):
            with open(DATA_FILE,"rb") as src: atomic_write(DATA_FILE+".restore.bak",src.read())
        save_data(clean)
        return jsonify(ok=True,message="همه تنظیمات و صف‌ها بازیابی شدند")
    except (ValueError,TypeError,json.JSONDecodeError,OSError) as exc:return jsonify(ok=False,error=str(exc)),400


@app.route("/api/export.csv", methods=["GET", "POST"])
def api_export():
    body = request.get_json(silent=True) or {}
    products = body.get("products") if isinstance(body.get("products"), list) else load_data()["last_result"]
    return export_csv(products)


@app.get("/api/export.json")
def api_export_json():
    payload = json.dumps(load_data()["last_result"], ensure_ascii=False, indent=2)
    return Response(payload, mimetype="application/json; charset=utf-8", headers={"Content-Disposition": f'attachment; filename="products-{time.strftime("%Y%m%d-%H%M%S")}.json"'})


@app.get("/api/export.xlsx")
def api_export_xlsx():
    return export_xlsx(load_data()["last_result"])


@app.post("/api/import.csv")
def api_import_csv():
    upload=request.files.get("file")
    if not upload:return jsonify(ok=False,error="فایل CSV ارسال نشده است"),400
    try:
        headers,rows=read_csv_upload(upload)
        aliases={"title":["title","name","عنوان","نام"],"price":["price","قیمت"],"link":["link","url","لینک"],"image":["image","تصویر"],"sku":["sku","کد"],"stock":["stock","موجودی"],"brand":["brand","برند"]}
        lowered={h.lower():h for h in headers};mapping={field:next((lowered[a] for a in names if a in lowered),"") for field,names in aliases.items()}
        products=mapped_import_products(rows,mapping,{})
        data=load_data();data["last_result"]=products;save_data(data)
        return jsonify(ok=True,products=products,total=len(products))
    except ValueError as exc:return jsonify(ok=False,error=str(exc)),400



def runtime_python_bin() -> str:
    """Find a real Python binary; under PythonAnywhere sys.executable is uWSGI."""
    candidates=[os.path.join(BASE_DIR,"venv","bin","python"),os.path.join(os.environ.get("VIRTUAL_ENV",""),"bin","python") if os.environ.get("VIRTUAL_ENV") else "",os.path.join(sys.prefix,"bin","python"),str(getattr(sys,"_base_executable","")),shutil.which("python3") or ""]
    return next((os.path.abspath(x) for x in candidates if x and os.path.isfile(x) and os.access(x,os.X_OK) and "uwsgi" not in os.path.basename(os.path.realpath(x)).lower()),"")


def basalam_sdk_status() -> dict[str,Any]:
    try:return {"installed":True,"version":importlib.metadata.version("basalam-sdk"),"python":runtime_python_bin(),"uwsgi":os.path.basename(sys.executable).lower().startswith("uwsgi")}
    except importlib.metadata.PackageNotFoundError:return {"installed":False,"version":"","python":runtime_python_bin(),"uwsgi":os.path.basename(sys.executable).lower().startswith("uwsgi")}


def ensure_basalam_sdk(progress: Optional[Any]=None) -> bool:
    """Install the official SDK, with PyPI/GitHub fallbacks and progress events."""
    if basalam_sdk_status()["installed"]:
        if progress:progress(100,"SDK از قبل نصب است","نسخه نصب‌شده شناسایی شد")
        return False
    if not DEPENDENCY_LOCK.acquire(blocking=False):raise ValueError("نصب SDK باسلام هم‌اکنون در درخواست دیگری در حال اجراست؛ چند لحظه بعد دوباره تست کنید")
    try:
        if basalam_sdk_status()["installed"]:return False
        python_bin=runtime_python_bin()
        if not python_bin:raise ValueError("مفسر Python واقعی پیدا نشد؛ مسیر uWSGI برای نصب قابل استفاده نیست")
        app_venv=os.path.abspath(os.path.join(BASE_DIR,"venv"));inside_app_venv=os.path.commonpath([app_venv,os.path.abspath(python_bin)])==app_venv
        base_cmd=[python_bin,"-m","pip","install","--disable-pip-version-check","--no-cache-dir"]
        if not inside_app_venv:
            os.makedirs(LOCAL_DEPS_DIR,mode=0o700,exist_ok=True);base_cmd.extend(["--target",LOCAL_DEPS_DIR])
        attempts=[("PyPI تنظیم‌شده",["basalam-sdk"]),("PyPI رسمی",["--index-url","https://pypi.org/simple","basalam-sdk"]),("GitHub رسمی",["https://github.com/basalam/python-sdk/archive/refs/heads/main.zip"])]
        details=[]
        for index,(label,args) in enumerate(attempts):
            if progress:progress(12+index*25,f"تلاش از {label}",f"مفسر واقعی: {python_bin}")
            result=subprocess.run([*base_cmd,*args],capture_output=True,text=True,timeout=240,env={**os.environ,"PIP_NO_CACHE_DIR":"1"})
            if os.path.isdir(LOCAL_DEPS_DIR):site.addsitedir(LOCAL_DEPS_DIR)
            importlib.invalidate_caches();output=clean_text((result.stderr or result.stdout)[-900:])
            if result.returncode==0 and basalam_sdk_status()["installed"]:
                if progress:progress(100,"نصب SDK کامل شد",f"منبع موفق: {label} · نسخه {basalam_sdk_status()['version']}")
                return True
            details.append(label+": "+output)
            if progress:progress(28+index*25,f"{label} ناموفق بود",output[-350:])
        raise ValueError("هر سه مسیر نصب SDK ناموفق بود: "+" | ".join(details))
    except subprocess.TimeoutExpired as exc:raise ValueError("نصب خودکار SDK باسلام بیشتر از ۴ دقیقه طول کشید؛ دوباره تلاش کنید") from exc
    finally:DEPENDENCY_LOCK.release()


def normalize_basalam_token(value: Any) -> str:
    """Accept a raw Personal Token or a copied Authorization header, never Bearer Bearer."""
    token=str(value or "").strip().strip('"\'').strip()
    token=re.sub(r"^Authorization\s*:\s*", "", token, flags=re.I).strip()
    token=re.sub(r"^(?:(?:Bearer|Token)\s+)+", "", token, flags=re.I).strip().strip('"\'').strip()
    token=re.sub(r"\s+", "", token)
    if not token or token.lower() in {"bearer","token","null","none"}:raise ValueError("Personal Token باسلام خالی یا نامعتبر است؛ فقط متن خود توکن را وارد کنید")
    return token


def basalam_api_request(method: str, path: str, *, params: Optional[dict[str,Any]]=None, json_data: Any=None, data: Any=None, files: Any=None) -> Any:
    """Direct SalamAPI REST client routed exclusively by the global connection gateway."""
    cfg=load_data().get("basalam",{});token=normalize_basalam_token(cfg.get("token"));base=public_http_url(clean_text(cfg.get("api_base_url")) or "https://openapi.basalam.com").rstrip("/");target=base+"/"+path.lstrip("/");headers={"Authorization":"Bearer "+token,"Accept":"application/json","User-Agent":"Scraper4-REST/"+APP_VERSION}
    try:response=outbound_request(method,target,params=params,headers=headers,json=json_data,data=data,files=files,timeout=60)
    except requests.RequestException as exc:raise FetchError(f"REST API باسلام از دروازه مرکزی قابل دسترسی نیست: {exc}") from exc
    transport=getattr(response,"scraper4_transport","gateway")
    if not response.ok:
        body=clean_text(response.text)[:700];hint="؛ Worker مرکزی باید X-Proxy-Authorization را به Authorization تبدیل و method/body را حفظ کند" if transport=="relay" and response.status_code in {401,403} else ""
        raise FetchError(f"Basalam REST {transport} HTTP {response.status_code}: {body}{hint}")
    try:return response.json()
    except ValueError:raise FetchError("REST API باسلام پاسخ JSON معتبر برنگرداند")


def basalam_strategy(sdk_call: Any, api_call: Any) -> tuple[Any,str]:
    mode=clean_text(load_data().get("basalam",{}).get("client_mode","auto")) or "auto";errors=[]
    network_mode=outbound_mode(load_data().get("network",{}))
    if mode=="sdk" and network_mode=="http":raise FetchError("SDK رسمی از HTTP CONNECT Proxy مرکزی پشتیبانی نمی‌کند؛ روش مدیریت باسلام را روی خودکار یا REST API قرار دهید")
    if mode in {"auto","sdk"} and network_mode!="http":
        try:return sdk_call(),"sdk"
        except Exception as exc:
            errors.append("SDK: "+clean_text(exc)[:600])
            if mode=="sdk":raise
    try:return api_call(),"api"
    except Exception as exc:
        errors.append("REST API: "+clean_text(exc)[:700]);raise FetchError(" | ".join(errors)) from exc


def basalam_api_rows(payload: Any) -> list[dict[str,Any]]:
    if isinstance(payload,list):return [x for x in payload if isinstance(x,dict)]
    if isinstance(payload,dict):
        for key in ("data","items","results","chats","messages","parcels","products"):
            value=payload.get(key)
            if isinstance(value,list):return [x for x in value if isinstance(x,dict)]
            if isinstance(value,dict):
                nested=basalam_api_rows(value)
                if nested:return nested
    return []


def rewind_upload_files(files: Any) -> None:
    if not files:return
    values=files.values() if isinstance(files,dict) else files if isinstance(files,(list,tuple)) else [files]
    for value in values:
        candidate=value[-1] if isinstance(value,tuple) and value else value
        if isinstance(candidate,(list,tuple,dict)):rewind_upload_files(candidate)
        elif hasattr(candidate,"seek"):
            try:candidate.seek(0)
            except (OSError,ValueError):pass


def install_basalam_relay_transport() -> None:
    """Teach the official SDK to send its synchronous calls through our URL relay."""
    from basalam_sdk.base_client import BaseClient
    if getattr(BaseClient.request_sync,"_scraper4_relay",False):return
    original=BaseClient.request_sync
    def request_sync(self,method,path,params=None,data=None,json_data=None,files=None,headers=None,response_model=None,require_auth=True):
        relay=clean_text(getattr(self.config,"scraper4_relay_url",""))
        if not relay:return original(self,method,path,params,data,json_data,files,headers,response_model,require_auth)
        target=urljoin(self.base_url,path)
        if params:target=requests.Request("GET",target,params=params).prepare().url
        request_headers=self.config.get_headers().copy()
        if require_auth:request_headers.update(self.auth.get_auth_headers_sync())
        if headers:request_headers.update(headers)
        authorization=clean_text(request_headers.get("Authorization"))
        if authorization:
            raw=normalize_basalam_token(authorization);authorization="Bearer "+raw;request_headers["Authorization"]=authorization
            # New dedicated Workers can map this explicit control header; old Workers simply ignore it.
            request_headers["X-Proxy-Authorization"]=authorization;request_headers["X-Upstream-Authorization"]=authorization
        request_headers["X-Proxy-UA"]=request_headers.get("User-Agent",USER_AGENT);request_headers["X-Proxy-Method"]=method.upper();worker_key=clean_text(getattr(self.config,"scraper4_worker_key",""))
        if worker_key:request_headers["X-Proxy-Key"]=worker_key
        response=outbound_request(method,target,headers=request_headers,data=data,json=json_data,files=files,timeout=float(self.config.timeout))
        if not response.ok:
            body=clean_text(response.text)[:700];hint="؛ دروازه مرکزی هدر احراز هویت را عبور نداده است" if response.status_code==401 and "authorization" in body.lower() else ""
            raise ValueError(f"Basalam SDK relay HTTP {response.status_code}: {body}{hint}")
        setattr(self.config,"scraper4_last_transport","relay");return self._parse_response_data(response,response_model)
    request_sync._scraper4_relay=True;BaseClient.request_sync=request_sync


def basalam_client():
    cfg=load_data().get("basalam",{});token=normalize_basalam_token(cfg.get("token",""));refresh=str(cfg.get("refresh_token","")).strip()
    ensure_basalam_sdk()
    try:
        from basalam_sdk import BasalamClient, PersonalToken
        from basalam_sdk.config import BasalamConfig
    except ImportError as exc:raise ValueError("SDK نصب شد اما فرآیند وب هنوز آن را نمی‌بیند؛ یک بار دیگر تست اتصال را بزنید") from exc
    sdk_cfg=BasalamConfig(timeout=45,user_agent="Scraper4/"+APP_VERSION);network=load_data().get("network",{});mode=outbound_mode(network);relay=clean_text(network.get("proxy"))
    if mode=="relay" and relay:
        install_basalam_relay_transport();sdk_cfg.scraper4_relay_url=relay;sdk_cfg.scraper4_worker_key=clean_text(network.get("worker_key"))
    return BasalamClient(auth=PersonalToken(token=token,refresh_token=refresh,config=sdk_cfg),config=sdk_cfg)


def basalam_photo_files(product: dict[str,Any]) -> list[io.BytesIO]:
    urls=list(product.get("images",[])) if isinstance(product.get("images"),list) else []
    if product.get("image") and product["image"] not in urls:urls.insert(0,product["image"])
    files=[]
    for index,url in enumerate(urls[:5]):
        try:
            public_http_url(url);response=outbound_request("GET",url,headers={"User-Agent":USER_AGENT},timeout=30)
            if not response.ok or len(response.content)>10*1024*1024:continue
            stream=io.BytesIO(response.content);ext=os.path.splitext(urlparse(url).path)[1].lower()
            stream.name=f"product-{index}{ext if ext in {'.jpg','.jpeg','.png','.webp'} else '.jpg'}";files.append(stream)
        except Exception:continue
    return files


def basalam_send_one_sdk(product: dict[str,Any]) -> dict[str,Any]:
    ensure_basalam_sdk()
    from basalam_sdk.core.models import ProductRequestSchema, GetVendorProductsSchema
    cfg=load_data().get("basalam",{});vendor=int(cfg.get("vendor_id",0));category=int(product.get("basalam_category_id") or cfg.get("category_id",0))
    if not vendor or not category:raise ValueError("شناسه غرفه و دسته‌بندی پیش‌فرض باسلام لازم است")
    client=basalam_client();sku=clean_text(product.get("sku"));existing=None
    if cfg.get("update_existing",True) and sku:
        found=client.get_vendor_products_sync(vendor,GetVendorProductsSchema(skus=[sku],per_page=10))
        existing=next((x for x in (found.data or []) if clean_text(getattr(x,"sku",""))==sku),None)
    req=ProductRequestSchema(name=clean_text(product.get("title"))[:250],brief=clean_text(product.get("short_desc"))[:600],description=clean_text(product.get("long_desc"))[:10000],category_id=category,preparation_days=max(1,int(cfg.get("preparation_days",3))),weight=float(re.sub(r"[^0-9.]","",clean_text(product.get("weight"))) or cfg.get("weight",500)),package_weight=int(cfg.get("package_weight",0) or 0) or None,primary_price=int(woo_price(product.get("price"))),stock=int(re.sub(r"\D","",clean_text(product.get("stock"))) or cfg.get("stock",10)),sku=sku or None)
    photos=basalam_photo_files(product)
    try:
        result=client.update_product_sync(int(existing.id),req,photo_files=photos or None) if existing else client.create_product_sync(vendor,req,photo_files=photos or None)
        return {"source":product.get("title"),"id":getattr(result,"id",None),"action":"updated" if existing else "created","photos":len(photos)}
    except Exception as exc:
        text=clean_text(exc);response=getattr(exc,"response",None);status=getattr(response,"status_code",0);body=""
        if response is not None:
            try:body=clean_text(response.text)[:800]
            except Exception:pass
        if status==403 or "403" in text:
            mode=clean_text(cfg.get("api_mode","relay"));scope="ایجاد/ویرایش محصول"+(" و بارگذاری تصویر" if photos else "")
            raise ValueError(f"باسلام دسترسی {scope} را با 403 رد کرد. مسیر={mode}؛ شناسه غرفه={vendor}؛ دسته={category}. اگر تست اتصال موفق است، Personal Token مجوز مدیریت محصول/آپلود این غرفه را ندارد. پاسخ: {body or text}") from exc
        raise
    finally:
        for photo in photos:
            try: photo.close()
            except Exception: pass


def basalam_send_one_api(product: dict[str,Any]) -> dict[str,Any]:
    cfg=load_data().get("basalam",{});vendor=int(cfg.get("vendor_id",0));category=int(product.get("basalam_category_id") or cfg.get("category_id",0));sku=clean_text(product.get("sku"))
    if not vendor or not category:raise ValueError("شناسه غرفه و دسته‌بندی پیش‌فرض باسلام لازم است")
    existing=None
    if cfg.get("update_existing",True) and sku:
        rows=basalam_api_rows(basalam_api_request("GET",f"/v1/vendors/{vendor}/products",params={"skus":sku,"per_page":10}))
        existing=next((x for x in rows if clean_text(x.get("sku"))==sku),None)
    photos=basalam_photo_files(product);photo_ids=[]
    try:
        for photo in photos:
            upload=basalam_api_request("POST","/v1/files",data={"file_type":"product.photo"},files={"file":(getattr(photo,"name","product.jpg"),photo,"image/jpeg")});file_data=upload.get("data",upload) if isinstance(upload,dict) else {};file_id=file_data.get("id") if isinstance(file_data,dict) else None
            if file_id:photo_ids.append(int(file_id))
        payload={"name":clean_text(product.get("title"))[:250],"brief":clean_text(product.get("short_desc"))[:600],"description":clean_text(product.get("long_desc"))[:10000],"category_id":category,"preparation_days":max(1,int(cfg.get("preparation_days",3))),"weight":float(re.sub(r"[^0-9.]","",clean_text(product.get("weight"))) or cfg.get("weight",500)),"primary_price":int(woo_price(product.get("price"))),"stock":int(re.sub(r"\D","",clean_text(product.get("stock"))) or cfg.get("stock",10)),"is_wholesale":False}
        if sku:payload["sku"]=sku
        if photo_ids:payload.update(photo=photo_ids[0],photos=photo_ids[1:] or None)
        existing_id=int((existing or {}).get("id",0));result=basalam_api_request("PATCH" if existing_id else "POST",f"/v1/products/{existing_id}" if existing_id else f"/v1/vendors/{vendor}/products",json_data=payload);row=result.get("data",result) if isinstance(result,dict) else {}
        return {"source":product.get("title"),"id":row.get("id") if isinstance(row,dict) else None,"action":"updated" if existing_id else "created","photos":len(photo_ids),"client":"rest-api"}
    finally:
        for photo in photos:
            try:photo.close()
            except Exception:pass


def basalam_send_one(product: dict[str,Any]) -> dict[str,Any]:
    result,client=basalam_strategy(lambda:basalam_send_one_sdk(product),lambda:basalam_send_one_api(product))
    if isinstance(result,dict):result["client"]=client
    return result


@app.get("/api/basalam/config")
def api_basalam_config():
    if not deploy_authorized():return deploy_auth_error()
    cfg=dict(load_data().get("basalam",{}))
    for k in ("token","refresh_token","worker_key"):
        if cfg.get(k):cfg[k]="••••"+str(cfg[k])[-4:]
    return jsonify(ok=True,basalam=cfg,sdk=basalam_sdk_status())


@app.post("/api/basalam/settings")
def api_basalam_settings():
    if not deploy_authorized():return deploy_auth_error()
    incoming=(request.get_json(silent=True) or {}).get("basalam",{});data=load_data();cfg=data.setdefault("basalam",default_data()["basalam"])
    for k,v in incoming.items():
        if k in cfg and not (k in {"token","refresh_token","worker_key"} and str(v).startswith("••••")):cfg[k]=v
    save_data(data);return jsonify(ok=True)


def basalam_install_worker(task_id: str) -> None:
    def progress(value: int, step: str, detail: str="") -> None:live_task_update(task_id,value,step,"running",detail)
    try:
        live_task_update(task_id,5,"بررسی محیط Python","running",f"uWSGI={sys.executable} · Python={runtime_python_bin()}")
        installed=ensure_basalam_sdk(progress);live_task_update(task_id,100,"SDK رسمی باسلام آماده است","completed","نصب تازه انجام شد" if installed else "نسخه موجود معتبر بود",sdk=basalam_sdk_status())
    except Exception as exc:live_task_update(task_id,100,"نصب SDK ناموفق بود","failed",str(exc),error=clean_text(exc)[:1800],sdk=basalam_sdk_status())


@app.post("/api/basalam/sdk/install/start")
def api_basalam_sdk_install_start():
    if not deploy_authorized():return deploy_auth_error()
    task=live_task_create("basalam_sdk","نصب SDK رسمی باسلام");threading.Thread(target=basalam_install_worker,args=(task["id"],),name="basalam-sdk-install",daemon=True).start();return jsonify(ok=True,task=task)


@app.post("/api/basalam/sdk/install")
def api_basalam_sdk_install():
    if not deploy_authorized():return deploy_auth_error()
    try:
        installed=ensure_basalam_sdk();return jsonify(ok=True,installed_now=installed,sdk=basalam_sdk_status(),message="SDK رسمی باسلام نصب و آماده است" if installed else "SDK رسمی باسلام از قبل آماده بود")
    except Exception as exc:return jsonify(ok=False,error=str(exc),stage="install",sdk=basalam_sdk_status()),400


@app.post("/api/basalam/test")
def api_basalam_test():
    if not deploy_authorized():return deploy_auth_error()
    try:
        was_missing=not basalam_sdk_status()["installed"]
        result,client=basalam_strategy(lambda:basalam_client().get_current_user_sync(),lambda:basalam_api_request("GET","/v1/users/me"));raw=result.model_dump() if hasattr(result,"model_dump") else result.get("data",result) if isinstance(result,dict) else {};user_name=clean_text(raw.get("name") or raw.get("title") or raw.get("id") or "connected") if isinstance(raw,dict) else "connected";sdk=basalam_sdk_status();data=load_data();data["basalam"].update(last_test_at=int(time.time()),last_test_user=user_name,last_client=client);save_data(data);return jsonify(ok=True,user=user_name,client=client,sdk=sdk,installed_now=was_missing and sdk["installed"])
    except Exception as exc:
        text=clean_text(exc);sdk=basalam_sdk_status();mode=clean_text(load_data().get("basalam",{}).get("client_mode","auto"));return jsonify(ok=False,error=text,detail=text[:1200],stage="connection",client_mode=mode,sdk=sdk),400


@app.get("/api/basalam/products")
def api_basalam_products():
    if not deploy_authorized():return deploy_auth_error()
    try:
        cfg=load_data().get("basalam",{});vendor=int(cfg.get("vendor_id",0))
        if not vendor:raise ValueError("شناسه غرفه باسلام تنظیم نشده است")
        def sdk_products():
            ensure_basalam_sdk();from basalam_sdk.core.models import GetVendorProductsSchema
            return basalam_client().get_vendor_products_sync(vendor,GetVendorProductsSchema(page=1,per_page=20))
        result,client=basalam_strategy(sdk_products,lambda:basalam_api_request("GET",f"/v1/vendors/{vendor}/products",params={"page":1,"per_page":20}));source=getattr(result,"data",None) or basalam_api_rows(result);rows=[]
        for product in source:
            row=product if isinstance(product,dict) else product.model_dump() if hasattr(product,"model_dump") else {};rows.append({"id":row.get("id"),"name":clean_text(row.get("name") or row.get("title")),"sku":clean_text(row.get("sku")),"price":row.get("primary_price",row.get("price")),"stock":row.get("stock"),"status":clean_text(row.get("status"))})
        return jsonify(ok=True,products=rows,total=len(rows),client=client,sdk=basalam_sdk_status())
    except Exception as exc:return jsonify(ok=False,error=str(exc)),400


@app.get("/api/basalam/vendor")
def api_basalam_vendor():
    if not deploy_authorized():return deploy_auth_error()
    try:
        cfg=load_data().get("basalam",{});vendor_id=int(cfg.get("vendor_id",0))
        if not vendor_id:raise ValueError("شناسه غرفه باسلام تنظیم نشده است")
        vendor,client=basalam_strategy(lambda:basalam_client().get_vendor_sync(vendor_id,prefer="return=representation"),lambda:basalam_api_request("GET",f"/v1/vendors/{vendor_id}"));raw=vendor.model_dump() if hasattr(vendor,"model_dump") else vendor.get("data",vendor) if isinstance(vendor,dict) else {}
        def pick(*names):
            for name in names:
                value=raw.get(name) if isinstance(raw,dict) else None
                if value not in (None,""):return value
            return ""
        public={"id":pick("id"),"title":clean_text(pick("title","name")),"identifier":clean_text(pick("identifier","slug")),"status":clean_text(pick("status")),"city":clean_text(pick("city_name","city")),"score":pick("score","rating"),"url":clean_text(pick("url"))}
        return jsonify(ok=True,vendor=public,client=client,sdk=basalam_sdk_status())
    except Exception as exc:return jsonify(ok=False,error=str(exc)),400


BASALAM_CATEGORY_CACHE={"at":0.0,"rows":[]}


def basalam_flat_categories(value: Any, trail: str="") -> list[dict[str,Any]]:
    if hasattr(value,"model_dump"):value=value.model_dump()
    out=[]
    if isinstance(value,list):
        for item in value:out.extend(basalam_flat_categories(item,trail))
    elif isinstance(value,dict):
        title=clean_text(value.get("title") or value.get("name"));cid=value.get("id")
        here=(trail+" ← "+title).strip(" ←") if title else trail
        if cid is not None and title:out.append({"id":cid,"name":title,"path":here})
        for key,item in value.items():
            if key not in {"id","title","name","description","icon","photo","image","url","slug"}:out.extend(basalam_flat_categories(item,here))
    return out


@app.get("/api/basalam/categories")
def api_basalam_categories():
    if not deploy_authorized():return deploy_auth_error()
    try:
        query=clean_text(request.args.get("q")).lower()
        if not BASALAM_CATEGORY_CACHE["rows"] or time.time()-BASALAM_CATEGORY_CACHE["at"]>3600:
            payload,client=basalam_strategy(lambda:basalam_client().get_categories_sync(),lambda:basalam_api_request("GET","/v1/categories"));rows=basalam_flat_categories(payload);unique={str(x["id"]):x for x in rows};BASALAM_CATEGORY_CACHE.update(at=time.time(),rows=list(unique.values()))
        rows=BASALAM_CATEGORY_CACHE["rows"]
        if query:rows=[x for x in rows if query in (clean_text(x["path"])+" "+str(x["id"])).lower()]
        return jsonify(ok=True,categories=rows[:80],total=len(rows),cached_at=int(BASALAM_CATEGORY_CACHE["at"]))
    except Exception as exc:return jsonify(ok=False,error=str(exc)),400


@app.get("/api/basalam/operations")
def api_basalam_operations():
    if not deploy_authorized():return deploy_auth_error()
    results={};checks={"chats":("/v1/chats",{"limit":20,"order_by":"updated_at"}),"unseen":("/v1/chats/unseen-count",None),"orders":("/v1/vendor-parcels",{"per_page":20})}
    for key,(path,params) in checks.items():
        try:
            payload=basalam_api_request("GET",path,params=params);results[key]=payload;results[key+"_count"]=len(basalam_api_rows(payload)) if key!="unseen" else payload.get("data",payload) if isinstance(payload,dict) else payload
        except Exception as exc:results[key+"_error"]=clean_text(exc)[:700]
    successes=sum(1 for key in checks if key in results)
    return jsonify(ok=successes>0,successes=successes,**results)


@app.get("/api/basalam/chats/<int:chat_id>/messages")
def api_basalam_chat_messages(chat_id: int):
    if not deploy_authorized():return deploy_auth_error()
    try:
        payload=basalam_api_request("GET",f"/v1/chats/{chat_id}/messages",params={"limit":50});return jsonify(ok=True,messages=basalam_api_rows(payload),raw=payload if not basalam_api_rows(payload) else None)
    except Exception as exc:return jsonify(ok=False,error=str(exc)),400


@app.post("/api/basalam/chats/<int:chat_id>/messages")
def api_basalam_chat_send(chat_id: int):
    if not deploy_authorized():return deploy_auth_error()
    text=clean_text((request.get_json(silent=True) or {}).get("text"))
    if not text:return jsonify(ok=False,error="متن پیام خالی است"),400
    try:
        payload={"chat_id":chat_id,"content":{"text":text},"message_type":"text","temp_id":int(time.time()*1000)};result=basalam_api_request("POST",f"/v1/chats/{chat_id}/messages",json_data=payload);return jsonify(ok=True,message=result)
    except Exception as exc:return jsonify(ok=False,error=str(exc)),400


def basalam_job_public(job: dict[str,Any]) -> dict[str,Any]:
    out={k:v for k,v in job.items() if k not in {"products","results"}};out["results"]=job.get("results",[])[-20:];return out


@app.get("/api/basalam/jobs")
def api_basalam_jobs():
    if not deploy_authorized():return deploy_auth_error()
    rows=[basalam_job_public(x) for x in load_data().get("bsl_jobs",{}).values()];rows.sort(key=lambda x:int(x.get("updated_at",0)),reverse=True);return jsonify(ok=True,jobs=rows[:20])


@app.post("/api/basalam/jobs")
def api_basalam_job_create():
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {};data=load_data();products=body.get("products") if isinstance(body.get("products"),list) else data.get("last_result",[]);products=[dict(x) for x in products[:MAX_PRODUCTS_HARD] if isinstance(x,dict)]
    if not products:return jsonify(ok=False,error="محصولی برای صف باسلام وجود ندارد"),400
    jid="bsl-"+time.strftime("%Y%m%d-%H%M%S")+"-"+secrets.token_hex(2);now=int(time.time());job={"id":jid,"status":"waiting","created_at":now,"updated_at":now,"cursor":0,"total":len(products),"sent":0,"updated":0,"failed":0,"products":products,"results":[]}
    jobs=data.setdefault("bsl_jobs",{});jobs[jid]=job
    for old in sorted(jobs,key=lambda x:int(jobs[x].get("updated_at",0)))[:-10]:jobs.pop(old,None)
    save_data(data);return jsonify(ok=True,job=basalam_job_public(job))


@app.post("/api/basalam/jobs/<job_id>/process")
def api_basalam_job_process(job_id: str):
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {};batch=max(1,min(5,int(body.get("batch",3))));data=load_data();job=data.get("bsl_jobs",{}).get(job_id)
    if not job:return jsonify(ok=False,error="صف باسلام پیدا نشد"),404
    job["status"]="running";processed=0
    while job["cursor"]<job["total"] and processed<batch:
        product=job["products"][job["cursor"]]
        try:
            result=basalam_send_one(product);job["results"].append({"ok":True,**result});job["sent"]+=1
            if result.get("action")=="updated":job["updated"]+=1
        except Exception as exc:job["failed"]+=1;job["results"].append({"ok":False,"source":product.get("title"),"error":clean_text(exc)[:500]})
        job["cursor"]+=1;processed+=1;job["updated_at"]=int(time.time());save_data(data)
    if job["cursor"]>=job["total"]:job["status"]="completed"
    save_data(data);return jsonify(ok=True,processed=processed,job=basalam_job_public(job))


@app.delete("/api/basalam/jobs/<job_id>")
def api_basalam_job_delete(job_id: str):
    if not deploy_authorized():return deploy_auth_error()
    data=load_data();data.get("bsl_jobs",{}).pop(job_id,None);save_data(data);return jsonify(ok=True)


@app.post("/api/basalam/send")
def api_basalam_send():
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {};limit=max(1,min(5,int(body.get("limit",3))));data=load_data();sent=[];failed=[]
    for product in data.get("last_result",[])[:limit]:
        try:sent.append(basalam_send_one(product))
        except Exception as exc:failed.append({"title":product.get("title"),"error":str(exc)[:400]})
    return jsonify(ok=not failed,sent=sent,failed=failed)


def profile_dispatch_worker(task_id: str, profile_name: str, products: list[dict[str,Any]], destinations: list[str], woo_status: str, woo_update: bool) -> None:
    """Send every saved product for one profile, checkpointing after each destination item."""
    started=time.time();total=max(1,len(products)*len(destinations));done=sent=failed=0;counts={x:{"sent":0,"failed":0} for x in destinations}
    try:
        live_task_update(task_id,1,"آماده‌سازی ارسال کامل پروفایل","running",f"پروفایل {profile_name} · {len(products)} محصول · مقصد: {'، '.join(destinations)}",profile=profile_name,total=total,done=0,sent=0,failed=0,destinations=counts)
        for destination in destinations:
            label="ووکامرس" if destination=="woocommerce" else "باسلام"
            live_task_update(task_id,max(1,int(done/total*100)),f"شروع ارسال به {label}","running",f"{len(products)} محصول این پروفایل برای {label} بررسی می‌شود")
            for index,product in enumerate(products,1):
                if live_task_cancelled(task_id):
                    elapsed=max(1,int(time.time()-started));live_task_update(task_id,int(done/total*100),"ارسال توسط کاربر متوقف شد","cancelled",f"پس از {done} عملیات و {elapsed} ثانیه متوقف شد",done=done,total=total,sent=sent,failed=failed,destinations=counts,elapsed_seconds=elapsed);return
                title=clean_text(product.get("title") or product.get("name") or f"محصول {index}")
                try:
                    if destination=="woocommerce":result=woo_send_one(product,woo_status,woo_update)
                    else:result=basalam_send_one(product)
                    sent+=1;counts[destination]["sent"]+=1;message=f"✓ {label} · {index}/{len(products)} · {title}"
                    if isinstance(result,dict) and result.get("action"):message+=f" · {result['action']}"
                except Exception as exc:
                    failed+=1;counts[destination]["failed"]+=1;message=f"✕ {label} · {index}/{len(products)} · {title} · {clean_text(exc)[:350]}"
                done+=1;elapsed=max(.001,time.time()-started);rate=done/elapsed;remaining=int((total-done)/rate) if rate else 0
                live_task_update(task_id,max(2,int(done/total*100)),f"{label}: محصول {index} از {len(products)}","running",message,profile=profile_name,done=done,total=total,sent=sent,failed=failed,destinations=counts,elapsed_seconds=int(elapsed),eta_seconds=remaining,current_product=title)
        status="completed";step="ارسال کامل پروفایل پایان یافت";detail=f"{sent} عملیات موفق و {failed} خطا در {int(time.time()-started)} ثانیه"
        live_task_update(task_id,100,step,status,detail,profile=profile_name,done=done,total=total,sent=sent,failed=failed,destinations=counts,elapsed_seconds=int(time.time()-started),eta_seconds=0)
    except Exception as exc:
        live_task_update(task_id,max(1,int(done/total*100)),"ارسال کامل پروفایل ناموفق بود","failed",str(exc),profile=profile_name,done=done,total=total,sent=sent,failed=failed,error=clean_text(exc)[:1500],destinations=counts)


def start_profile_dispatch(profile_name: str, body: dict[str,Any]) -> dict[str,Any]:
    data=load_data();profile=data.get("profiles",{}).get(profile_name)
    if not isinstance(profile,dict):raise ValueError(f"پروفایل «{profile_name}» پیدا نشد")
    products=[dict(x) for x in profile.get("saved_products",[])[:MAX_PRODUCTS_HARD] if isinstance(x,dict)]
    if not products:raise ValueError(f"پروفایل «{profile_name}» محصول ذخیره‌شده ندارد؛ ابتدا آن را استخراج کنید")
    requested=body.get("destinations",["woocommerce","basalam"]);destinations=[x for x in ("woocommerce","basalam") if x in requested]
    if not destinations:raise ValueError("حداقل یک مقصد انتخاب کنید")
    task=live_task_create("profile_dispatch",f"ارسال کامل پروفایل «{profile_name}»",private=True)
    task.update(profile=profile_name,total=len(products)*len(destinations),done=0,sent=0,failed=0,destinations={x:{"sent":0,"failed":0} for x in destinations});live_task_disk_write(task)
    threading.Thread(target=profile_dispatch_worker,args=(task["id"],profile_name,products,destinations,str(body.get("woo_status","draft")),bool(body.get("woo_update",True))),name="profile-dispatch",daemon=True).start()
    return task


@app.post("/api/dispatch/profile/<path:profile_name>")
def api_profile_dispatch(profile_name: str):
    if not deploy_authorized():return deploy_auth_error()
    try:return jsonify(ok=True,task=start_profile_dispatch(profile_name,request.get_json(silent=True) or {}))
    except ValueError as exc:return jsonify(ok=False,error=str(exc)),400


@app.post("/api/dispatch/profiles")
def api_profiles_dispatch():
    if not deploy_authorized():return deploy_auth_error()
    body=request.get_json(silent=True) or {};data=load_data();names=body.get("profiles")
    if not isinstance(names,list) or not names:names=list(data.get("profiles",{}))
    tasks=[];errors=[]
    for name in names:
        try:tasks.append(start_profile_dispatch(clean_text(name),body))
        except ValueError as exc:errors.append(str(exc))
    if not tasks:return jsonify(ok=False,error="؛ ".join(errors) or "پروفایل قابل ارسالی وجود ندارد"),400
    return jsonify(ok=True,tasks=tasks,errors=errors)


@app.post("/api/woo/test")
def woo_test():
    if not deploy_authorized():return deploy_auth_error()
    try:
        response = woo_request("GET", "system_status")
        return jsonify(ok=True, status=response.json())
    except (ValueError, FetchError, requests.RequestException) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.post("/api/woo/queue")
def woo_queue_create():
    if not deploy_authorized():return deploy_auth_error()
    body = request.get_json(silent=True) or {}
    products = body.get("products") if isinstance(body.get("products"), list) else load_data()["last_result"]
    products = [p for p in products[:MAX_PRODUCTS_HARD] if isinstance(p, dict)]
    if not products: return jsonify(ok=False, error="محصولی برای ارسال وجود ندارد"), 400
    job_id = "woo-" + time.strftime("%Y%m%d-%H%M%S") + "-" + hashlib.sha1(str(time.time_ns()).encode()).hexdigest()[:5]
    job = {"id": job_id, "status": "pending", "created_at": int(time.time()), "updated_at": int(time.time()), "cursor": 0, "total": len(products), "sent": 0, "failed": 0, "status_value": str(body.get("status", "draft")), "update_existing": bool(body.get("update_existing", True)), "products": products, "results": []}
    data = load_data(); jobs = data.setdefault("woo_jobs", {}); jobs[job_id] = job
    if len(jobs) > 10:
        for key in sorted(jobs, key=lambda x: int(jobs[x].get("updated_at", 0)))[:-10]: jobs.pop(key, None)
    save_data(data)
    return jsonify(ok=True, job={k:v for k,v in job.items() if k not in ("products","results")})


@app.get("/api/woo/jobs")
def woo_jobs_list():
    if not deploy_authorized():return deploy_auth_error()
    jobs = load_data().get("woo_jobs", {})
    rows = [{k:v for k,v in job.items() if k not in ("products","results")} for job in jobs.values()]
    rows.sort(key=lambda x: int(x.get("updated_at", 0)), reverse=True)
    return jsonify(ok=True, jobs=rows)


@app.post("/api/woo/process/<job_id>")
def woo_queue_process(job_id: str):
    if not deploy_authorized():return deploy_auth_error()
    body = request.get_json(silent=True) or {}; batch = max(1, min(25, int(body.get("batch", 10))))
    data = load_data(); job = data.get("woo_jobs", {}).get(job_id)
    if not isinstance(job, dict): return jsonify(ok=False, error="صف ووکامرس پیدا نشد"), 404
    products = job.get("products", []); cursor = int(job.get("cursor", 0)); job["status"] = "running"
    for index in range(cursor, min(len(products), cursor + batch)):
        try:
            result = woo_send_one(products[index], str(job.get("status_value", "draft")), bool(job.get("update_existing", True)))
            job["sent"] = int(job.get("sent", 0)) + 1
        except Exception as exc:
            result = {"source": products[index].get("title"), "error": str(exc)}
            job["failed"] = int(job.get("failed", 0)) + 1
        job.setdefault("results", []).append(result); job["cursor"] = index + 1; job["updated_at"] = int(time.time())
        save_data(data)  # PHP-style atomic per-product checkpoint
    job["status"] = "completed" if int(job.get("cursor", 0)) >= len(products) else "pending"
    save_data(data)
    public = {k:v for k,v in job.items() if k not in ("products","results")}
    return jsonify(ok=True, job=public, recent=job.get("results", [])[-batch:])


@app.delete("/api/woo/jobs/<job_id>")
def woo_job_delete(job_id: str):
    if not deploy_authorized():return deploy_auth_error()
    data=load_data(); data.get("woo_jobs", {}).pop(job_id, None); save_data(data); return jsonify(ok=True)


@app.post("/api/woo/send")
def woo_send():
    if not deploy_authorized():return deploy_auth_error()
    """Backward-compatible one-request sender, now using Phase 3 upsert logic."""
    body=request.get_json(silent=True) or {}; products=body.get("products") if isinstance(body.get("products"),list) else load_data()["last_result"]
    sent=[];failed=[]
    for product in products[:max(1,min(25,int(body.get("limit",20))))]:
        try: sent.append(woo_send_one(product,str(body.get("status","draft")),bool(body.get("update_existing",True))))
        except Exception as exc: failed.append({"source":product.get("title"),"error":str(exc)})
    return jsonify(ok=not failed,sent=sent,failed=failed)


# ---------------------------------------------------------------------------
# Inline interface (keeps deployment genuinely single-file)
# ---------------------------------------------------------------------------
INDEX_HTML = r'''<!doctype html>
<html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,viewport-fit=cover"><meta name="theme-color" content="#07111f">
<title>Scraper4 Python</title><style>
:root{--bg:#07111f;--bg2:#0a1830;--card:rgba(15,28,48,.82);--card2:#13243d;--line:rgba(148,177,216,.16);--text:#f4f8ff;--muted:#9db0ca;--blue:#38bdf8;--blue2:#2563eb;--green:#34d399;--red:#fb7185;--amber:#fbbf24;--shadow:0 18px 55px rgba(0,0,0,.28);--radius:20px}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;min-height:100vh;background:radial-gradient(circle at 85% -10%,rgba(37,99,235,.28),transparent 34%),radial-gradient(circle at 5% 18%,rgba(14,165,233,.13),transparent 26%),linear-gradient(155deg,var(--bg),var(--bg2));background-attachment:fixed;color:var(--text);font-family:Tahoma,"Segoe UI",Arial,sans-serif;font-size:14px;line-height:1.55}body:before{content:"";position:fixed;inset:0;pointer-events:none;opacity:.22;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:34px 34px}.wrap{position:relative;max-width:1200px;margin:auto;padding:28px 22px 70px}.hero{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:22px;padding:24px 26px;border:1px solid var(--line);border-radius:26px;background:linear-gradient(125deg,rgba(15,38,68,.92),rgba(16,31,53,.76));box-shadow:var(--shadow);overflow:hidden;position:relative}.hero:after{content:"";position:absolute;width:220px;height:220px;border-radius:50%;left:-70px;top:-110px;background:rgba(56,189,248,.11);filter:blur(2px)}.hero-main{display:flex;align-items:center;gap:16px;position:relative;z-index:1}.logo{width:58px;height:58px;display:grid;place-items:center;flex:none;border-radius:18px;font-size:29px;background:linear-gradient(145deg,#0ea5e9,#2563eb);box-shadow:0 10px 28px rgba(37,99,235,.34)}.eyebrow{font-size:10px;letter-spacing:.8px;color:#75d5ff;margin-bottom:2px}h1{font-size:clamp(21px,4vw,30px);margin:0;letter-spacing:-.5px}h1 small{font-size:10px;font-weight:500;color:#7f96b4;background:#09182c;border:1px solid var(--line);padding:3px 7px;border-radius:20px;vertical-align:middle}.sub{color:var(--muted);margin:5px 0 0}.hero-badge{position:relative;z-index:1;white-space:nowrap;padding:8px 13px;border-radius:999px;border:1px solid rgba(52,211,153,.25);background:rgba(52,211,153,.09);color:#8af0c9;font-size:12px}.hero-badge:before{content:"";display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--green);margin-left:7px;box-shadow:0 0 10px var(--green)}
.app-footer{height:74px}.tabs{position:fixed;bottom:0;left:50%;transform:translateX(-50%);width:min(100%,1200px);z-index:20;display:flex;gap:7px;margin:0;padding:7px;border:1px solid var(--line);border-radius:16px;background:rgba(7,17,31,.82);backdrop-filter:blur(16px);box-shadow:0 8px 28px rgba(0,0,0,.2);overflow-x:auto;scrollbar-width:none}.tabs::-webkit-scrollbar{display:none}.tabs button{flex:1;min-width:max-content;background:transparent;border:1px solid transparent;color:var(--muted);box-shadow:none;display:flex;align-items:center;justify-content:center;gap:7px}.tabs button i{font-style:normal;font-size:17px;line-height:1}.tabs button.on{color:white;border-color:rgba(56,189,248,.23);background:linear-gradient(135deg,rgba(14,165,233,.22),rgba(37,99,235,.2))}.pane{display:none;animation:rise .28s ease}.pane.on{display:block}@keyframes rise{from{opacity:0;transform:translateY(7px)}to{opacity:1;transform:none}}.card{background:var(--card);backdrop-filter:blur(14px);border:1px solid var(--line);border-radius:var(--radius);padding:20px;margin-bottom:14px;box-shadow:var(--shadow)}.card h3{margin:0;font-size:18px}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px}.grid4{grid-template-columns:repeat(4,minmax(0,1fr))}.wide{grid-column:1/-1}label{display:block;color:#b9c8dc;font-size:12px;font-weight:600;margin:0 2px 6px}input,select,textarea,button{font-family:inherit;font-size:16px;touch-action:manipulation;border-radius:12px;border:1px solid var(--line);padding:11px 13px;background:rgba(5,14,27,.72);color:var(--text);width:100%;outline:none;transition:.2s ease}input:hover,select:hover,textarea:hover{border-color:rgba(56,189,248,.3)}input:focus,select:focus,textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(56,189,248,.12);background:#081426}input::placeholder{color:#627895}select{cursor:pointer}button{width:auto;min-height:42px;cursor:pointer;background:linear-gradient(135deg,#0284c7,#2563eb);border-color:rgba(125,211,252,.28);font-weight:700;box-shadow:0 7px 18px rgba(37,99,235,.16)}button:hover{filter:brightness(1.1);transform:translateY(-1px)}button:active{transform:translateY(0)}button:disabled{opacity:.62;cursor:wait;transform:none}button.gray{background:#17263d;border-color:#33465f;box-shadow:none}button.green{background:linear-gradient(135deg,#059669,#047857);border-color:#34d399}.actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:17px}.primary-actions{margin:0 0 14px;padding:12px;border:1px solid var(--line);border-radius:16px;background:rgba(10,23,42,.8);box-shadow:var(--shadow)}details.advanced summary{display:flex;align-items:center;justify-content:space-between;gap:12px;cursor:pointer;list-style:none}details.advanced summary::-webkit-details-marker{display:none}details.advanced summary small{display:block;color:var(--muted);font-weight:400;margin-top:2px}details.advanced summary>i{font-style:normal;font-size:22px;color:var(--blue);transition:.2s}details.advanced[open] summary>i{transform:rotate(180deg)}.advanced-body{padding-top:15px}.advanced-body>.note{margin-bottom:14px}.section-mini{font-size:14px!important;margin:18px 0 10px!important;color:#8edfff}.file-btn{display:inline-flex!important;align-items:center;justify-content:center;margin:0!important;padding:10px 13px;border:1px solid #33465f;border-radius:12px;background:#17263d;color:white!important;cursor:pointer;font-weight:700}.file-btn input{display:none}.note{padding:12px 14px;border-radius:13px;border:1px solid rgba(56,189,248,.12);background:rgba(19,42,70,.68);color:#bfd0e5;line-height:1.85}.status{white-space:pre-wrap;line-height:1.9;color:#b9cae0;border-right:3px solid var(--blue);min-height:58px}.error{color:var(--red)}.ok{color:var(--green)}code{direction:ltr;display:inline-block;color:#a5e4ff;background:#061426;border-radius:6px;padding:1px 5px}table{width:100%;border-collapse:separate;border-spacing:0;direction:rtl}th,td{padding:11px 10px;border-bottom:1px solid var(--line);text-align:right;vertical-align:middle}th{color:#96ddfb;position:sticky;top:0;background:#112139;z-index:2;font-size:12px}tbody tr{transition:.2s}tbody tr:hover{background:rgba(56,189,248,.045)}td img{width:62px;height:62px;object-fit:contain;background:#fff;border-radius:12px;padding:3px;box-shadow:0 4px 14px #0004}.tablebox{max-height:620px;overflow:auto;padding:0;border-radius:var(--radius)}a{color:#78d7ff;text-decoration:none}a:hover{text-decoration:underline}.badge{display:inline-block;padding:4px 8px;border:1px solid #36516f;border-radius:12px;color:#b6d6ef;margin:3px}.empty{padding:42px 14px!important;text-align:center!important;color:var(--muted)}.spinner{display:inline-block;width:16px;height:16px;border:2px solid #fff5;border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;vertical-align:-3px;margin-left:7px}@keyframes spin{to{transform:rotate(360deg)}}
.gateway-card{border-color:#22d3ee55;background:linear-gradient(145deg,rgba(8,47,73,.93),rgba(15,28,48,.94))}.gateway-flow{display:flex;align-items:center;justify-content:center;gap:10px;margin:4px 0 16px;padding:12px;border:1px solid #38bdf833;border-radius:14px;background:#061426}.gateway-flow i{font-style:normal;color:#38bdf8}.gateway-flow strong{padding:6px 10px;border-radius:10px;background:#164e63;color:#cffafe}.gateway-services{display:grid;grid-template-columns:repeat(3,1fr);gap:7px;margin-top:14px}.gateway-services span{padding:8px;text-align:center;border:1px solid var(--line);border-radius:10px;background:#081528;color:#c8ddf3;font-size:11px}@media(max-width:640px){.gateway-services{grid-template-columns:repeat(2,1fr)}.gateway-flow{font-size:11px;gap:6px}}
.settings-panel{width:460px}.settings-panel-body>.admin-section>.card{border-radius:16px;padding:16px}.settings-panel-body>.admin-section>.card:before{content:"";display:block;width:42px;height:3px;border-radius:9px;background:linear-gradient(90deg,var(--blue),var(--green));margin-bottom:13px}.grid>div{padding:2px}.operations-card{background:linear-gradient(145deg,rgba(10,34,55,.94),rgba(20,27,51,.92))}.operation-columns{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:14px}.operation-columns h4{color:#8edfff;margin:0 0 8px}.operation-list{display:grid;gap:7px;max-height:320px;overflow:auto}.operation-item{padding:10px;border:1px solid var(--line);border-radius:12px;background:#081528;cursor:pointer;transition:.2s}.operation-item:hover{border-color:#38bdf866;transform:translateY(-1px)}.operation-item small{display:block;color:var(--muted);margin-top:3px}.chat-panel{margin-top:14px;border:1px solid #38bdf844;border-radius:15px;background:#071321;padding:12px}.message-list{display:flex;flex-direction:column;gap:7px;max-height:350px;overflow:auto;padding:8px}.message{max-width:88%;padding:9px 11px;border-radius:13px;background:#172a46;color:#dcecff}.message:nth-child(even){align-self:flex-end;background:#0f513d}.message small{display:block;color:#91a9c5;font-size:9px}.chat-compose{display:grid;grid-template-columns:1fr auto;gap:8px;margin-top:8px}.chat-compose button{height:100%}@media(max-width:640px){.operation-columns{grid-template-columns:1fr}.settings-panel{width:100%;max-width:100vw}.chat-compose{grid-template-columns:1fr}.operation-list{max-height:240px}}
.task-center{display:grid;gap:10px}.task-card{padding:14px;border:1px solid var(--line);border-radius:16px;background:linear-gradient(145deg,rgba(11,28,50,.94),rgba(14,24,42,.85));position:relative;overflow:hidden}.task-card.running{border-color:#38bdf866}.task-card.completed{border-color:#34d39955}.task-card.failed{border-color:#fb718555}.task-top,.task-metrics,.task-actions{display:flex;align-items:center;justify-content:space-between;gap:8px;flex-wrap:wrap}.task-top small{color:var(--muted)}.profile-task-chip{color:#cffafe;border-color:#22d3ee55;background:#164e6355;font-size:10px}.task-metrics{justify-content:flex-start;margin:9px 0;color:#b9cbe1;font-size:11px}.task-metrics span{padding:4px 8px;border-radius:9px;background:#081528}.task-progress{height:11px;border-radius:99px;background:#06101e;overflow:hidden;border:1px solid #29405d;margin:9px 0}.task-progress i{display:block;height:100%;min-width:2px;border-radius:inherit;background:linear-gradient(90deg,#0ea5e9,#38bdf8,#34d399);background-size:200% 100%;animation:progressGlow 2s linear infinite;transition:width .45s ease}.task-card.failed .task-progress i{background:#fb7185}.task-step{font-weight:700;color:#d9f2ff;margin-top:8px}.task-timeline{max-height:155px;overflow:auto;margin-top:9px;padding-right:8px;border-right:2px solid #244665}.task-event{font-size:11px;color:#aebfd5;padding:4px 8px;overflow-wrap:anywhere}.task-event b{color:#70d7ff}.task-actions{justify-content:flex-end;margin-top:9px}.task-actions button{font-size:11px;min-height:34px;padding:6px 10px}.changelog{display:grid;gap:10px}.release{padding:14px;border:1px solid var(--line);border-radius:16px;background:#09172a}.release.current{border-color:#34d39966;background:linear-gradient(140deg,#0b2929,#10223b)}.release-head{display:flex;justify-content:space-between;gap:8px}.release ul{margin:9px 0 0;padding-right:20px;color:#b8c9dd}.dispatch-hero{border-color:#38bdf855;background:linear-gradient(135deg,rgba(2,132,199,.16),rgba(16,185,129,.10))}@keyframes progressGlow{to{background-position:-200% 0}}
@media(max-width:900px){.grid4{grid-template-columns:repeat(2,minmax(0,1fr))}.hero{padding:20px}.wrap{padding:18px 14px 60px}}
@media(max-width:640px){html,body{max-width:100%;overflow-x:hidden}body{font-size:13px}.wrap{padding:10px 8px calc(28px + env(safe-area-inset-bottom))}.hero{border-radius:18px;padding:14px 12px;margin-bottom:10px}.hero-main{gap:10px}.logo{width:43px;height:43px;border-radius:13px;font-size:22px}.eyebrow{display:none}.hero h1{font-size:20px}.sub{font-size:10px;line-height:1.55;max-width:230px}.hero-badge{display:none}.tabs{position:fixed;top:auto;right:auto;left:50%;bottom:0;width:100%;margin:0;border-radius:15px;padding:5px;justify-content:flex-start;z-index:50;overflow-x:auto;overscroll-behavior-x:contain;scroll-snap-type:x proximity}.tabs button{flex:1 0 62px;min-width:62px;min-height:48px;padding:5px 3px;font-size:9px;white-space:nowrap;flex-direction:column;gap:1px;scroll-snap-align:start}.tabs button i{font-size:18px}.card{padding:14px 12px;border-radius:16px;margin-bottom:10px}.grid,.grid4{grid-template-columns:1fr;gap:12px}.wide{grid-column:auto}input,select,textarea{font-size:16px;min-height:48px}.actions{display:grid;grid-template-columns:1fr}.actions button{width:100%;padding:11px 8px;min-height:46px}.actions button:first-child:last-child{grid-column:1/-1}.note{font-size:12px;padding:10px}.tablebox{max-height:none;overflow:visible;background:transparent;border:0;box-shadow:none;padding:0}table,thead,tbody,tr,td{display:block}thead{display:none}tbody{display:grid;gap:10px}tbody tr{position:relative;padding:13px 88px 13px 12px;min-height:104px;border:1px solid var(--line);border-radius:16px;background:var(--card);box-shadow:0 8px 24px #0003}tbody tr:hover{background:var(--card)}td{padding:3px 0;border:0;text-align:right}td:before{content:attr(data-label);color:var(--muted);font-size:10px;margin-left:6px}td:nth-child(1){position:absolute;left:9px;top:8px;color:#7188a5;font-size:10px}td:nth-child(1):before,td:nth-child(2):before{display:none}td:nth-child(2){position:absolute;right:12px;top:13px}td:nth-child(2) img{width:64px;height:76px;border-radius:11px}td:nth-child(3){font-weight:700;font-size:13px;line-height:1.65;margin-bottom:4px}td:nth-child(4){color:#7ce6ba;font-weight:700;direction:ltr;text-align:right}td:empty{display:none}.empty{padding:35px 10px!important}.empty:before{display:none}}
@media(max-height:480px) and (max-width:900px){.hero{display:none}.tabs{bottom:0}.tabs button{min-height:40px;flex-direction:row;font-size:10px}.tabs button i{font-size:15px}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important;scroll-behavior:auto!important}}

.file-list{display:grid;gap:6px}.file-row{display:grid;grid-template-columns:minmax(0,1fr) 130px 120px;align-items:center;gap:8px;background:#0f172a;border:1px solid #334155;border-radius:8px;padding:9px 11px}.file-row button{background:transparent;border:0;padding:0;min-height:0;text-align:right;color:#93c5fd;box-shadow:none}.file-row .fsize{text-align:left;direction:ltr;color:#fbbf24}.file-row small{color:#64748b;text-align:left}.space-card{background:#0f172a;border:1px solid #334155;border-radius:10px;padding:10px;text-align:center}.space-card b{display:block;font-size:18px;color:#67e8f9}.space-card span{font-size:10px;color:#94a3b8}@media(max-width:640px){.file-row{grid-template-columns:minmax(0,1fr) 90px}.file-row small{display:none}}
.task-manager-btn{position:fixed;top:10px;right:62px;z-index:10049;width:auto;min-width:132px;height:44px;padding:0 12px;display:flex;align-items:center;justify-content:center;gap:7px;border-radius:12px;background:linear-gradient(135deg,#0f766e,#0369a1);border:1px solid #67e8f955;box-shadow:0 10px 28px #02061788}.task-manager-btn b{display:grid;place-items:center;min-width:23px;height:23px;padding:0 5px;border-radius:99px;background:#020617;color:#7dd3fc;font-size:11px}.task-manager-btn.has-active b{background:#052e16;color:#86efac;box-shadow:0 0 12px #22c55e77}.task-btn-icon{font-size:18px}.hamburger-btn{position:fixed;top:10px;right:10px;z-index:10050;width:44px;height:44px;padding:0;border-radius:12px;background:#1e293b;border:1px solid #475569;color:#e2e8f0;font-size:22px;display:grid;place-items:center}.settings-overlay{position:fixed;inset:0;background:#0009;z-index:9998;display:none}.settings-overlay.open{display:block}.settings-panel{position:fixed;top:0;right:-430px;width:410px;max-width:94vw;height:100dvh;background:#0f172a;border-left:1px solid #334155;z-index:10000;overflow-y:auto;transition:right .25s}.settings-panel.open{right:0}.settings-panel-head{position:sticky;top:0;z-index:5;background:#1e293b;padding:12px 205px 12px 12px;min-height:64px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid #334155}.settings-panel-head h2{margin:0;font-size:16px}.settings-panel-body{padding:10px}.admin-nav{display:grid;grid-template-columns:repeat(2,1fr);gap:6px;margin-bottom:10px}.admin-nav button{font-size:12px;padding:8px}.admin-section{display:none}.admin-section.admin-on{display:block}.hamburger-btn.active{background:#3b82f6;color:#08111e}.section-head{display:flex;justify-content:space-between;align-items:flex-start;gap:10px;margin-bottom:12px}.section-head small{color:var(--muted)}.ai-tabs{display:flex;gap:6px;overflow:auto;margin:10px 0 14px;padding:5px;background:#091426;border-radius:14px}.ai-tabs button{font-size:11px;flex:1;white-space:nowrap;background:transparent;box-shadow:none}.ai-tabs button.on{background:#1d4ed8}.ai-pane{display:none}.ai-pane.on{display:block}.provider-list{display:grid;gap:8px;margin-top:12px}.provider-row{padding:11px;border:1px solid var(--line);border-radius:13px;background:#091426;display:grid;grid-template-columns:1fr auto;gap:7px}.provider-row small{color:var(--muted)}.provider-row .models{grid-column:1/-1;display:flex;gap:4px;flex-wrap:wrap}.model-chip{font-size:10px;padding:3px 7px;border-radius:99px;background:#172a46;color:#bce9ff}.backup-hero{border-color:#10b98155;background:linear-gradient(145deg,#0c2d2b,#10243c)}.admin-nav button.nav-on{border-color:#38bdf8;background:#164e63}.test-results{display:grid;gap:6px;margin-top:10px;max-height:430px;overflow:auto}.test-row{display:grid;grid-template-columns:1fr auto;gap:5px;padding:9px;border:1px solid var(--line);border-radius:11px;background:#091426}.test-row.ok{border-right:3px solid var(--green)}.test-row.failed{border-right:3px solid var(--red)}.test-row small{grid-column:1/-1;color:var(--muted);overflow-wrap:anywhere}.inline-search{display:grid;grid-template-columns:1fr auto;gap:6px}.category-results{display:grid;gap:5px;max-height:230px;overflow:auto;margin-top:7px}.category-item{display:flex;align-items:center;justify-content:space-between;gap:7px;padding:7px 9px;border:1px solid var(--line);border-radius:10px;background:#091426}.category-item button{padding:4px 8px;min-height:32px;font-size:11px}.settings-panel .grid4{grid-template-columns:repeat(2,minmax(0,1fr))}.result-modal{display:none;position:fixed;inset:0;z-index:11000;background:#020617dd;padding:clamp(8px,3vw,30px);backdrop-filter:blur(8px)}.result-modal.open{display:flex}.result-modal-card{width:min(1500px,100%);height:100%;margin:auto;display:flex;flex-direction:column;background:#0b1528;border:1px solid #334155;border-radius:20px;overflow:hidden;box-shadow:0 25px 80px #000a}.result-modal-head{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:14px 18px;background:linear-gradient(135deg,#172554,#0f2944);border-bottom:1px solid #334155}.result-modal-head h2{margin:0;font-size:18px}.result-modal-head small{color:#9db0ca}.result-modal-head .actions{margin:0}.modal-tools{display:grid;grid-template-columns:1fr 220px;gap:8px;padding:10px 14px}.modal-tools.pro-filters{grid-template-columns:minmax(240px,2fr) repeat(6,minmax(125px,1fr))}.modal-pagination{display:flex;align-items:center;justify-content:center;gap:8px;padding:8px;border-top:1px solid var(--line);background:#0b1528}.modal-pagination select{width:auto}.model-catalog,.candidate-list{display:grid;gap:6px;max-height:460px;overflow:auto}.model-row,.candidate-row{display:grid;grid-template-columns:minmax(0,1fr) auto auto;align-items:center;gap:8px;padding:9px 11px;border:1px solid var(--line);border-radius:11px;background:#091426}.model-row:hover{border-color:#38bdf855}.health-meter{width:70px;height:6px;border-radius:10px;background:#1e293b;overflow:hidden}.health-meter i{display:block;height:100%;background:var(--green)}.candidate-row.master{border-color:#fbbf2466;background:#2a220d}.stats+.model-catalog{margin-top:10px}.result-modal .stats{padding:0 14px 10px}.modal-table{overflow:auto;flex:1;border-top:1px solid var(--line)}.modal-table table{min-width:1100px}.modal-table td{max-width:330px;white-space:normal;line-height:1.7}.answer-cell{font-size:11px;color:#dbeafe}.cap-dot{display:inline-block;padding:2px 5px;margin:2px;border-radius:5px;background:#1e293b;font-size:9px}.ai-pro-table thead th{top:0;background:linear-gradient(180deg,#172b48,#102039);text-transform:none;letter-spacing:.1px}.ai-pro-table tbody tr{border-right:3px solid transparent}.ai-pro-table tbody tr.row-ok{border-right-color:var(--green);background:#052e1b22}.ai-pro-table tbody tr.row-partial{border-right-color:var(--amber)}.ai-pro-table tbody tr.row-failed{border-right-color:var(--red)}.rank-medal{display:grid;place-items:center;width:31px;height:31px;border-radius:10px;background:#172a46;color:#9bdfff;font-weight:800}.rank-medal.top{background:linear-gradient(145deg,#92400e,#f59e0b);color:#fff}.score-box{min-width:110px}.score-line{display:flex;justify-content:space-between;font-weight:800}.score-meter{height:6px;background:#1e293b;border-radius:9px;overflow:hidden;margin-top:5px}.score-meter i{display:block;height:100%;background:linear-gradient(90deg,#ef4444,#fbbf24,#34d399);border-radius:inherit}.answer-preview{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}.answer-cell details{margin-top:5px}.answer-cell summary{cursor:pointer;color:#7dd3fc;font-size:10px}.answer-full{white-space:pre-wrap;padding:8px;margin-top:5px;border-radius:8px;background:#06111f;max-height:180px;overflow:auto}.ai-compare-bar{display:none;margin:0 14px 10px;padding:10px 12px;border:1px solid #a78bfa55;border-radius:13px;background:linear-gradient(135deg,#2e1065aa,#172554aa);align-items:center;justify-content:space-between;gap:10px}.ai-compare-bar.show{display:flex}.ai-compare-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:8px;width:100%}.ai-compare-card{padding:10px;border:1px solid #ffffff1a;border-radius:11px;background:#071426}.ai-compare-card b,.ai-compare-card small{display:block}.ai-compare-card small{color:var(--muted)}.latency-pill{display:inline-flex;padding:3px 7px;border-radius:8px;background:#172a46;font-size:10px}.result-modal-card{position:relative}.result-modal-head{flex:none}.result-modal .modal-tools{flex:none;background:#081426;border-bottom:1px solid var(--line)}.ai-select{width:18px;height:18px;accent-color:#38bdf8}.progress-pulse{animation:pulse 1s infinite}.profile-picker{position:relative;border-color:#38bdf844;background:linear-gradient(145deg,rgba(12,38,65,.95),rgba(13,27,48,.88))}.profile-picker select{font-weight:700;border-color:#38bdf866}.profile-picker .note{margin-top:10px}.profile-card{cursor:pointer;transition:.2s}.profile-card:hover{border-color:#38bdf8;transform:translateY(-1px)}.live-task{margin:12px 0;padding:13px;border:1px solid #38bdf855;border-radius:15px;background:linear-gradient(145deg,#071a30,#0d2540);box-shadow:inset 0 1px #ffffff0a}.live-task-head{display:flex;justify-content:space-between;gap:10px;color:#dff6ff}.progress-track{height:10px;margin:9px 0;border-radius:99px;overflow:hidden;background:#020817;border:1px solid #28435f}.progress-track i{display:block;width:0;height:100%;border-radius:inherit;background:linear-gradient(90deg,#06b6d4,#3b82f6,#8b5cf6);background-size:200% 100%;animation:flow 1.4s linear infinite;transition:width .45s ease}.live-step{font-size:12px;color:#7dd3fc;font-weight:700}.live-details{max-height:150px;overflow:auto;margin-top:7px;font:10px/1.8 ui-monospace,monospace;color:#94a3b8;direction:rtl}.live-detail{padding:2px 0;border-bottom:1px dashed #33415555}@keyframes flow{to{background-position:-200% 0}}@keyframes pulse{50%{opacity:.55}}@media(max-width:640px){.settings-panel{width:100%;max-width:100%;right:-100%}.settings-panel .grid4,.modal-tools,.modal-tools.pro-filters{grid-template-columns:1fr}.result-modal{padding:0}.result-modal-card{border-radius:0}.result-modal-head{padding:9px;align-items:flex-start}.result-modal-head h2{font-size:15px}.settings-panel-head{padding-right:120px;min-height:62px}.hamburger-btn{top:8px;right:8px}.task-manager-btn{top:8px;right:60px;min-width:48px;width:48px;padding:0}.task-btn-text{display:none}.task-manager-btn b{position:absolute;top:-5px;left:-5px;min-width:18px;height:18px;font-size:9px}.result-modal .modal-table table{display:table;min-width:1050px}.result-modal .modal-table thead{display:table-header-group}.result-modal .modal-table tbody{display:table-row-group}.result-modal .modal-table tr{display:table-row;padding:0;border-radius:0}.result-modal .modal-table th,.result-modal .modal-table td{display:table-cell;position:static;padding:9px}.result-modal .modal-table td:before{display:none}}
/* v1.6: visual parity with scraper4.php */
body{background:#0f172a;background-image:none;color:#e2e8f0;padding:12px 12px 90px}body:before{display:none}.wrap{max-width:1400px;padding:0;margin:auto}.hero{background:#1e293b;border:1px solid #334155;border-radius:12px;padding:12px 64px 12px 14px;margin:0 0 14px;box-shadow:none}.hero:after{display:none}.logo{width:40px;height:40px;border-radius:8px;font-size:20px;box-shadow:none}.eyebrow{display:none}.hero h1{font-size:18px}.sub{font-size:11px}.card{background:#1e293b;border:1px solid #334155;border-radius:12px;padding:14px;margin-bottom:14px;box-shadow:none;backdrop-filter:none}input,select,textarea{background:#0f172a;border:1px solid #475569;border-radius:8px;color:#fff}button,.file-btn{border-radius:8px;box-shadow:none}.primary-actions{background:#1e293b;border-color:#334155;border-radius:12px;box-shadow:none}.note,.status{background:#0f172a;border-color:#334155;border-radius:10px}.tabs{left:0;right:0;bottom:0;transform:none;width:100%;max-width:none;background:#0f172a;border:0;border-top:1px solid #334155;border-radius:0;padding:0 env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);gap:0;box-shadow:0 -4px 20px rgba(0,0,0,.5)}.tabs button{flex:1 0 64px;min-height:60px;border:0;border-radius:0;color:#64748b;flex-direction:column;gap:2px;padding:8px 4px;font-size:11px}.tabs button.on{color:#3b82f6;background:#1e293b;border:0}.tabs button.on i{transform:translateY(-2px) scale(1.15);filter:drop-shadow(0 3px 8px rgba(59,130,246,.7))}.tabs button i{font-size:21px}.tablebox{background:#1e293b}.app-footer{height:72px}
</style></head><body><button class="hamburger-btn" id="hamburgerBtn" onclick="toggleSettingsPanel()" aria-label="تنظیمات عمومی">☰</button><button class="task-manager-btn" id="taskManagerTopBtn" onclick="openTaskManager()" aria-label="مدیریت وظایف"><span class="task-btn-icon">◷</span><span class="task-btn-text">تسک منیجر</span><b id="taskTopCount">۰</b></button><div class="settings-overlay" id="settingsOverlay" onclick="toggleSettingsPanel(false)"></div><aside class="settings-panel" id="settingsPanel"><div class="settings-panel-head"><h2>☰ تنظیمات عمومی</h2><button class="gray" onclick="toggleSettingsPanel(false)">✕</button></div><div class="settings-panel-body"><div class="admin-nav"><button onclick="showAdmin('backupAdmin')">💾 پشتیبان کل سایت</button><button onclick="showAdmin('settings')">🌐 اتصال مبدأ</button><button onclick="showAdmin('aiAdmin');loadAI()">🤖 هوش مصنوعی</button><button onclick="showAdmin('basalamAdmin');loadBasalam()">🛍️ اتصال باسلام</button><button onclick="showAdmin('profiles')">☆ پروفایل‌ها</button><button onclick="showAdmin('changelogAdmin');loadChangelog()">📋 تغییرات نسخه</button><button onclick="showAdmin('deploy')">↻ بروزرسانی</button><button onclick="showAdmin('files');browseFiles('')">📁 فایل‌ها</button></div><section id="backupAdmin" class="admin-section"><div class="card backup-hero"><h3>💾 ذخیره و بازیابی همه تنظیمات سایت</h3><p class="note">این همان بخش پشتیبان کلی سایت است و شامل پروفایل‌ها، اتصال‌ها، صف‌ها، ارائه‌دهندگان، مدل‌ها و کلیدهای خصوصی می‌شود. فایل را امن نگه دارید.</p><div class="actions"><button class="green" onclick="backupSettings()">⬇ دانلود پشتیبان کامل</button><label class="file-btn">♻ بارگذاری و بازیابی فایل<input type="file" accept=".json,application/json" onchange="restoreSettings(this)"></label></div><div id="backupStatus" class="status">آماده دانلود یا بازیابی تنظیمات Python و بسته‌های PHP</div></div></section><section id="basalamAdmin" class="admin-section"><div class="card"><div class="section-head"><div><h3>🛍️ اتصال هوشمند باسلام</h3><small>SDK رسمی با fallback خودکار به REST API مستقل</small></div><span id="bslSdkBadge" class="badge">در حال بررسی…</span></div><div class="grid"><div><label>شناسه غرفه</label><input id="bsl_vendor" type="number"></div><div><label>شناسه دسته‌بندی پیش‌فرض</label><input id="bsl_category" type="number"></div><div class="wide"><label>جستجوی دسته‌بندی رسمی باسلام</label><div class="inline-search"><input id="bsl_category_query" placeholder="نام یا شناسه دسته"><button class="gray" onclick="searchBasalamCategories()">جستجو</button></div><div id="bslCategoryResults" class="category-results"></div></div><div class="wide"><label>Personal Token</label><input id="bsl_token" type="password" dir="ltr"></div><div class="wide"><label>Refresh Token</label><input id="bsl_refresh" type="password" dir="ltr"></div><div><label>روش مدیریت باسلام</label><select id="bsl_client_mode"><option value="auto">خودکار: SDK ← REST API</option><option value="api">فقط REST API مستقیم</option><option value="sdk">فقط SDK رسمی</option></select></div><div class="wide"><label>Base URL رسمی REST API</label><input id="bsl_api_base_url" dir="ltr" value="https://openapi.basalam.com"></div><div><label>زمان آماده‌سازی</label><input id="bsl_days" type="number" value="3"></div><div><label>وزن پیش‌فرض گرم</label><input id="bsl_weight" type="number" value="500"></div><div><label>موجودی پیش‌فرض</label><input id="bsl_stock" type="number" value="10"></div><div><label><input id="bsl_update" type="checkbox" checked style="width:auto"> بروزرسانی محصول هم‌SKU</label></div></div><div class="note" style="margin-top:12px">حالت خودکار ابتدا SDK و سپس REST API را امتحان می‌کند. مسیر شبکه از «دروازه اتصال مرکزی» خوانده می‌شود. Worker باید هدر <code>X-Proxy-Authorization</code> را به <code>Authorization</code> تبدیل و method/body را حفظ کند.</div><div class="actions"><button onclick="saveBasalam()">ذخیره</button><button class="gray" onclick="installBasalamSdk()">نصب/ترمیم SDK</button><button class="gray" onclick="testBasalam()">تست اتصال باسلام</button><button class="green" onclick="loadBasalamVendor()">اطلاعات غرفه</button><button class="green" onclick="loadBasalamProducts()">محصولات غرفه</button></div><div id="bslAdminStatus" class="status">توکن را بدون عبارت Bearer وارد کنید؛ در خطای 401 رله، اتصال مستقیم خودکار آزمایش می‌شود.</div><div id="bslLiveTask" class="live-task" style="display:none"><div class="live-task-head"><b id="bslTaskTitle">در حال اجرا</b><span id="bslTaskPercent">۰٪</span></div><div class="progress-track"><i id="bslTaskBar"></i></div><div id="bslTaskStep" class="live-step"></div><div id="bslTaskDetails" class="live-details"></div></div><div id="bslVendorCard"></div><div id="bslProductList" class="provider-list"></div></div><div class="card operations-card"><div class="section-head"><div><h3>💬 مرکز عملیات باسلام</h3><small>REST API مستقل برای گفت‌وگوها، پیام‌ها و سفارش‌های غرفه</small></div><span class="badge">فاز عملیات</span></div><div class="actions"><button class="green" onclick="loadBasalamOperations()">دریافت داشبورد</button><button class="gray" onclick="loadBasalamOperations()">↻ بروزرسانی</button></div><div id="bslOperationStats" class="stats"></div><div class="operation-columns"><div><h4>گفت‌وگوهای اخیر</h4><div id="bslChatList" class="operation-list"><div class="note">داشبورد را دریافت کنید.</div></div></div><div><h4>سفارش‌های غرفه</h4><div id="bslOrderList" class="operation-list"><div class="note">داشبورد را دریافت کنید.</div></div></div></div><div id="bslChatPanel" class="chat-panel" style="display:none"><div class="section-head"><b id="bslChatTitle">گفت‌وگو</b><button class="gray" onclick="$('bslChatPanel').style.display='none'">✕</button></div><div id="bslMessages" class="message-list"></div><div class="chat-compose"><textarea id="bslMessageText" rows="2" placeholder="پاسخ به مشتری…"></textarea><button onclick="sendBasalamMessage()">ارسال پیام</button></div></div><div id="bslOperationStatus" class="status">این بخش از REST API استفاده می‌کند و به نصب SDK وابسته نیست.</div></div></section><section id="aiAdmin" class="admin-section"><div class="card"><div class="section-head"><div><h3>🤖 مرکز هوش مصنوعی</h3><small>مدیریت چند ارائه‌دهنده، مدل‌ها و چند کلید API مانند نسخه PHP</small></div><span id="aiSummary" class="badge">در حال خواندن…</span></div><div class="ai-tabs"><button class="on" onclick="aiPane('providers',this)">🧠 ارائه‌دهنده‌ها</button><button onclick="aiPane('editor',this)">✏️ ویرایش</button><button onclick="aiPane('models',this);loadAIStats()">📋 مدل‌ها</button><button onclick="aiPane('candidates',this);loadAICandidates()">🏆 کاندید + مستر</button><button onclick="aiPane('test',this)">✨ محتوا</button><button onclick="aiPane('health',this);loadAITestJobs()">🧪 تست مدل‌ها</button></div><div id="aiPaneProviders" class="ai-pane on"><div class="grid"><div><label>ارائه‌دهنده فعال</label><select id="ai_provider" onchange="aiSelectProvider()"><option value="">— ارائه‌دهنده‌ای نیست —</option></select></div><div><label>مدل فعال</label><select id="ai_model" onchange="aiSelectModel()"><option value="">—</option></select></div></div><div id="aiProviderList" class="provider-list"></div><div class="actions"><label class="file-btn">⬆ بارگذاری ai_providers.json<input type="file" accept=".json,application/json" onchange="importAIProviders(this)"></label><button class="gray" onclick="loadAI()">↻ تازه‌سازی</button></div></div><div id="aiPaneEditor" class="ai-pane"><div class="grid"><div><label>شناسه یکتا</label><input id="ai_edit_id" dir="ltr" placeholder="openrouter"></div><div><label>نام نمایشی</label><input id="ai_edit_name" placeholder="OpenRouter"></div><div><label>Vendor اختیاری</label><input id="ai_edit_vendor" dir="ltr"></div><div><label><input id="ai_edit_enabled" type="checkbox" checked style="width:auto"> ارائه‌دهنده فعال باشد</label></div><div class="wide"><label>Base URL یا Endpoint</label><input id="ai_endpoint" dir="ltr" placeholder="https://.../v1/chat/completions"></div><div class="wide"><label>کلیدهای API — هر خط: کلید | برچسب | Account ID کلادفلر</label><textarea id="ai_keys" rows="4" dir="ltr" placeholder="sk-... | حساب اول"></textarea></div><div class="wide"><label>مدل‌ها — هر خط: model-id | نام نمایشی | free</label><textarea id="ai_models" rows="7" dir="ltr" placeholder="model/id | نام مدل | free"></textarea></div></div><div class="actions"><button onclick="saveAIProvider()">ذخیره ارائه‌دهنده</button><button class="gray" onclick="newAIProvider()">ارائه‌دهنده تازه</button><button class="gray" onclick="deleteAIProvider()">حذف</button></div></div><div id="aiPaneModels" class="ai-pane"><div id="aiStatsCards" class="stats"></div><div id="aiStatsBars"></div><div class="modal-tools" style="padding:10px 0"><input id="aiCatalogSearch" placeholder="جستجوی مدل یا ارائه‌دهنده…" oninput="renderAIModelCatalog()"><select id="aiCatalogFilter" onchange="renderAIModelCatalog()"><option value="all">همه مدل‌ها</option><option value="available">سالم</option><option value="failed">ناموفق</option><option value="untested">تست‌نشده</option><option value="free">رایگان</option></select></div><div id="aiModelCatalog" class="model-catalog"></div></div><div id="aiPaneCandidates" class="ai-pane"><div class="note">مانند نسخه PHP، مدل‌های سالم را به کاندیدها اضافه کنید و یک مدل مستر برای دسته‌بندی و پاسخ خودکار تعیین کنید.</div><div class="grid"><div><label>ارائه‌دهنده</label><select id="aiCandProvider" onchange="renderCandidateModels()"></select></div><div><label>مدل سالم</label><select id="aiCandModel"></select></div></div><div class="actions"><button onclick="addAICandidate()">افزودن کاندید</button><button class="green" onclick="addAllHealthyCandidates()">افزودن همه سالم‌ها</button><button class="gray" onclick="saveAICandidates()">ذخیره</button></div><div id="aiCandidateList" class="candidate-list"></div><div class="grid"><div class="wide"><label>مدل مستر</label><select id="aiMasterModel" onchange="saveAICandidates()"><option value="">خودکار — بهترین امتیاز</option></select></div></div></div><div id="aiPaneTest" class="ai-pane"><div class="grid"><div><label>Temperature</label><input id="ai_temperature" type="number" min="0" max="2" step="0.1"></div><div><label>Max tokens</label><input id="ai_max_tokens" type="number" min="64" max="32000"></div><div class="wide"><label>System prompt</label><textarea id="ai_system_prompt" rows="3"></textarea></div><div class="wide"><label>پیام آزمایش</label><textarea id="ai_test_prompt" rows="3">فقط این JSON را برگردان: {"status":"ok"}</textarea></div></div><div class="actions"><button onclick="saveAIOptions()">ذخیره گزینه‌ها</button><button class="gray" onclick="testAI()">تست مدل فعال</button><button class="green" onclick="enrichAI()">تکمیل ۳ محصول</button></div></div><div id="aiPaneHealth" class="ai-pane"><div class="grid grid4"><div><label>حداکثر مدل هر ارائه‌دهنده</label><input id="ai_test_per" type="number" min="1" max="5000" value="5000"></div><div><label>تأخیر بین مدل‌ها (ms)</label><input id="ai_test_delay" type="number" min="0" max="60000" value="120"></div><div><label>مدل در هر درخواست</label><input id="ai_test_batch" type="number" min="1" max="3" value="1"></div><div><label><input id="ai_test_only" type="checkbox" style="width:auto"> فقط تست‌نشده‌ها</label><label><input id="ai_test_skip" type="checkbox" checked style="width:auto"> ردکردن مدل غیرچت</label></div><div class="wide"><label>پیام نمونه مشتری</label><textarea id="ai_reply_sample" rows="2">سلام، این محصول موجود است و چه زمانی ارسال می‌شود؟</textarea></div><div class="wide"><label>عنوان نمونه برای دسته‌بندی</label><input id="ai_category_sample" value="ادو پرفیوم مردانه دیور ساواج ۱۰۰ میلی‌لیتر"></div></div><div class="actions"><button class="green" onclick="startAutoAITests()">▶ تست خودکار همه مدل‌ها</button><button onclick="processAITests()">اجرای فقط یک مرحله</button><button class="gray" onclick="stopAutoAITests()">توقف خودکار</button><button class="gray" onclick="openAITestModal()">جدول پیشرفته نتایج</button></div><div id="aiTestSummary" class="stats"></div><div id="aiTestRows" class="test-results"></div></div><div id="aiStatus" class="status">فایل PHP را بارگذاری کنید؛ ارائه‌دهنده‌ها و مدل‌ها فوراً در فهرست ظاهر می‌شوند.</div></div></section><div id="adminMount"></div></div></aside><div class="wrap">
<header class="hero"><div class="hero-main"><div class="logo">🕸️</div><div><div class="eyebrow">مرکز استخراج محصول</div><h1>Scraper4 <small id="appVersion">v2.1.0</small></h1><div class="sub">استخراج مستقیم DOM و سلکتورها، مطابق نسخه PHP</div></div></div><div class="hero-badge"><span>●</span> آنلاین و آماده</div></header>

<section id="scrape" class="pane on"><div class="card profile-picker"><div class="section-head"><div><h3>☆ پروفایل فعال</h3><small>انتخاب شما روی سرور ذخیره و بعد از هر بار تازه‌سازی خودکار بازیابی می‌شود.</small></div><span id="activeProfileBadge" class="badge">پروفایل جدید</span></div><div class="grid"><div class="wide"><label>پروفایل ذخیره‌شده</label><select id="profileSelect" onchange="loadSelectedProfile()"><option value="">— پروفایل جدید —</option></select></div></div><div id="activeProfileInfo" class="note">یک پروفایل را انتخاب کنید؛ نیازی به دکمه بارگذاری نیست.</div><div class="actions"><button class="gray" onclick="saveProfilePrompt()">ذخیره پروفایل فعلی</button><button class="gray" onclick="deleteSelectedProfile()">حذف پروفایل</button></div></div><div class="card"><div class="grid grid4">
<div class="wide"><label>آدرس صفحهٔ فهرست/جست‌وجو</label><input id="url" placeholder="https://www.digikala.com/search/?q=..." dir="ltr"></div>
<div><label>تعداد صفحه</label><input id="pages" type="number" min="1" max="50" value="1"></div>
<div><label>روش محتوا</label><select id="render"><option value="auto">خودکار: HTML سپس Playwright</option><option value="http">HTML مستقیم (روش PHP)</option><option value="browser">DOM رندرشده با Playwright</option></select></div>
<div><label>صفحه‌بندی</label><select id="pagination"><option value="query">پارامتر Query</option><option value="path">الگوی مسیر نسبت به صفحه فهرست</option><option value="full">الگوی کامل URL</option><option value="next">سلکتور لینک صفحه بعد</option></select></div>
<div><label>نام پارامتر / الگو</label><input id="page_value" value="page" dir="ltr" placeholder="page یا /page/{page}/"></div>
<div><label>تعداد اسکرول در Browser</label><input id="scrolls" type="number" value="4" min="0" max="12"></div>
<div><label>تکمیل تصویر/قیمت از صفحه جزئیات</label><select id="enrich"><option value="0">خاموش</option><option value="1">روشن</option></select></div>
<div><label>سقف صفحات جزئیات</label><input id="detail_limit" type="number" value="20" min="0" max="100"></div>
</div></div>
<details class="card advanced"><summary><span><b>سلکتورهای CSS</b><small>اختیاری — فقط برای سایت‌های خاص</small></span><i>⌄</i></summary><div class="advanced-body"><div class="note">مانند نسخه PHP، استخراج فقط از DOM و سلکتورها انجام می‌شود. برای سایت‌های JavaScript حالت Playwright را انتخاب کنید.</div><div class="grid grid4">
<div><label>ظرف محصول *</label><input id="sel_container" dir="ltr" placeholder="article.product"></div><div><label>عنوان</label><input id="sel_title" dir="ltr" placeholder="h2.title"></div><div><label>قیمت</label><input id="sel_price" dir="ltr" placeholder=".price"></div><div><label>لینک</label><input id="sel_link" dir="ltr" placeholder="a"></div><div><label>تصویر</label><input id="sel_image" dir="ltr" placeholder="img"></div><div><label>SKU</label><input id="sel_sku" dir="ltr"></div>
</div><h3 class="section-mini">سلکتورهای صفحه جزئیات</h3><div class="grid grid4"><div><label>گالری تصاویر</label><input id="det_gallery" dir="ltr" placeholder=".gallery img"></div><div><label>تنوع‌ها</label><input id="det_variations" dir="ltr" placeholder=".variations | .sizes"></div><div><label>وزن</label><input id="det_weight" dir="ltr"></div><div><label>دسته‌بندی</label><input id="det_category" dir="ltr"></div><div><label>قیمت جزئیات</label><input id="det_price" dir="ltr"></div><div><label>موجودی</label><input id="det_stock" dir="ltr"></div><div><label>برند</label><input id="det_brand" dir="ltr"></div><div><label>SKU</label><input id="det_sku" dir="ltr"></div><div><label>توضیح کوتاه</label><input id="det_short_desc" dir="ltr"></div><div><label>توضیح بلند</label><input id="det_long_desc" dir="ltr"></div></div></div></details><div class="primary-actions actions"><button id="runBtn" onclick="runScrape()">🚀 شروع برداشت</button><button class="gray" onclick="saveProfilePrompt()">☆ ذخیره پروفایل</button><button class="green" onclick="downloadCSV()">↓ CSV</button><button class="gray" onclick="downloadJSON()">↓ JSON</button><button class="gray" onclick="downloadXLSX()">↓ Excel</button><label class="file-btn">↑ ورود CSV<input id="csvImport" type="file" accept=".csv,text/csv" onchange="importCSV(this)"></label></div>
<div id="status" class="card status">آماده برای برداشت محصولات</div><div id="extractLiveTask" class="card live-task" style="display:none"><div class="live-task-head"><b id="extractTaskTitle">استخراج محصولات</b><span id="extractTaskPercent">۰٪</span></div><div class="progress-track"><i id="extractTaskBar"></i></div><div id="extractTaskStep" class="live-step"></div><div id="extractTaskDetails" class="live-details"></div></div><div class="card tablebox"><table><thead><tr><th>#</th><th>تصویر</th><th>عنوان</th><th>قیمت</th><th>SKU</th><th>لینک</th></tr></thead><tbody id="rows"><tr><td class="empty" colspan="6">پس از شروع برداشت، محصولات اینجا نمایش داده می‌شوند.</td></tr></tbody></table></div></section>
<section id="profileSettings" class="pane"><div class="card"><h3>⚙️ تنظیمات پروفایل</h3><div class="grid grid4"><div><label>پیشوند عنوان</label><input id="rule_title_prefix"></div><div><label>پسوند عنوان</label><input id="rule_title_suffix"></div><div><label>نوع تعدیل قیمت</label><select id="rule_price_mode"><option value="none">بدون تغییر</option><option value="percent">درصد</option><option value="multiplier">ضریب</option><option value="fixed">مبلغ ثابت</option></select></div><div><label>مقدار تعدیل</label><input id="rule_price_value" type="number" step="0.01" value="0"></div><div><label>گردکردن قیمت</label><input id="rule_price_round" type="number" value="0" placeholder="مثلاً 1000"></div><div><label>موجودی پیش‌فرض</label><input id="rule_default_stock" type="number"></div><div><label>دسته‌بندی پیش‌فرض</label><input id="rule_default_category"></div><div><label>شناسه دسته باسلام</label><input id="rule_bsl_category_id" type="number"></div><div><label>شناسه دسته ووکامرس</label><input id="rule_woo_category_id" type="number"></div></div><div class="note">این تنظیمات همراه پروفایل ذخیره و روی نتایج همان پروفایل اعمال می‌شوند.</div></div></section>
<section id="selectors" class="pane"><div id="selectorsMount"></div></section>
<section id="results" class="pane"><div class="primary-actions actions"><button class="green" onclick="downloadCSV()">↓ CSV</button><button class="gray" onclick="downloadJSON()">↓ JSON</button><button class="gray" onclick="downloadXLSX()">↓ Excel</button></div><div id="resultsMount"></div></section>
<section id="imports" class="pane"><div class="card"><h3>📥 درون‌ریزی و نگاشت CSV</h3><div class="note">ابتدا فایل را پیش‌نمایش کنید، سپس ستون هر فیلد و تعدیل قیمت/عنوان را مشخص کنید.</div><div class="actions"><label class="file-btn">انتخاب CSV<input id="advancedCsv" type="file" accept=".csv,text/csv" onchange="previewImport(this)"></label></div><div id="importMap" class="grid grid4" style="margin-top:14px"></div><div id="importOptions" class="grid" style="display:none;margin-top:14px"><div><label>ضریب قیمت</label><input id="impMul" type="number" step="0.01" value="1"></div><div><label>مبلغ ثابت افزوده</label><input id="impAdd" type="number" value="0"></div><div><label>پیشوند عنوان</label><input id="impPrefix"></div><div><label>پسوند عنوان</label><input id="impSuffix"></div><div><label>شیوه ورود</label><select id="impMode"><option value="replace">جایگزینی نتایج</option><option value="append">افزودن/بروزرسانی نتایج</option></select></div></div><div id="importPreview" class="status" style="display:none;margin-top:14px"></div><div class="actions"><button id="applyImportBtn" style="display:none" onclick="applyImport()">اجرای درون‌ریزی</button></div></div></section>

<section id="jobs" class="pane"><div class="card"><div class="section-head"><div><h3>◷ مرکز مدیریت وظایف</h3><small>همه عملیات بلندمدت، حتی پس از refresh، با زمان‌بندی و جزئیات قابل پیگیری‌اند</small></div><span id="taskManagerBadge" class="badge">—</span></div><div id="taskManagerStats" class="stats"></div><div class="note">استخراج‌ها مستقل و موازی اجرا می‌شوند؛ توقف یک سایت، پروفایل‌های دیگر را معطل نمی‌کند.</div><div class="modal-tools" style="margin-top:10px"><input id="taskSearch" placeholder="جستجو در پروفایل، مرحله یا خطا…" oninput="renderTaskManager()"><select id="taskStatusFilter" onchange="renderTaskManager()"><option value="all">همه وضعیت‌ها</option><option value="active">فعال</option><option value="completed">کامل</option><option value="failed">ناموفق/قطع‌شده</option></select><select id="taskKindFilter" onchange="renderTaskManager()"><option value="all">همه عملیات</option><option value="scrape">استخراج</option><option value="profile_dispatch">ارسال مقصدها</option><option value="basalam_sdk">SDK باسلام</option></select></div><div class="actions"><button onclick="loadTaskManager()">↻ تازه‌سازی</button><button class="gray" onclick="taskAutoRefresh=!taskAutoRefresh;loadTaskManager()" id="taskAutoBtn">پایش خودکار: روشن</button></div><div id="taskManagerList" class="task-center"></div></div><div class="card"><h3>صف استخراج و نقاط ادامه</h3><div class="note">پس از هر صفحه یک checkpoint اتمیک ذخیره می‌شود؛ عملیات قطع‌شده را بدون شروع از ابتدا ادامه دهید.</div><div class="actions"><button onclick="loadJobs()">تازه‌سازی نقاط ادامه</button></div><div id="jobList"></div></div></section><section id="changelogAdmin" class="admin-section"><div class="card"><div class="section-head"><div><h3>📋 گزارش تغییرات نسخه‌ها</h3><small>تاریخچه قابلیت‌های نصب‌شده این فایل</small></div><span id="changeCurrent" class="badge"></span></div><div id="changelogList" class="changelog"></div></div></section>
<section id="files" class="pane"><div class="card"><h3>📁 فایل اکسپلورر فضای حساب</h3><div class="note">نمایش فقط‌خواندنی پوشه خانگی؛ فایل‌های سیستمی، توکن‌ها و اطلاعات شخصی از این بخش حذف یا باز نمی‌شوند.</div><div id="spaceSummary" class="stats" style="margin-top:12px"></div><div id="quotaInfo" class="status" style="margin-top:12px"></div><div class="actions"><button class="gray" onclick="browseFiles(fileParent)">⬆ پوشه بالاتر</button><button onclick="browseFiles(fileCurrent)">تازه‌سازی</button></div><div id="filePath" class="status" dir="ltr"></div><div id="fileRows" class="file-list"></div></div></section>
<section id="profiles" class="pane"><div class="card"><h3>پروفایل‌های ذخیره‌شده</h3><div id="profileList"></div></div></section>
<section id="settings" class="pane"><div class="card gateway-card"><div class="section-head"><div><h3>🌐 دروازه مرکزی اتصال‌های خروجی</h3><small>یک مسیر مشترک برای استخراج، باسلام، ووکامرس، هوش مصنوعی، GitHub و Reload</small></div><span id="gatewayModeBadge" class="badge">—</span></div><div class="gateway-flow"><span>Scraper4</span><i>←</i><strong id="gatewayFlowMode">دروازه</strong><i>←</i><span>سرویس‌های بیرونی</span></div><div class="grid"><div><label>Timeout ثانیه</label><input id="timeout" type="number"></div><div><label>فاصله درخواست‌ها، ms</label><input id="gap_ms" type="number"></div><div class="wide"><label>آدرس Cloudflare Worker یا HTTP Proxy</label><input id="proxy" dir="ltr" placeholder="https://proxy.example.workers.dev"></div><div><label>نوع اتصال همه سرویس‌ها</label><select id="proxy_mode" onchange="updateGatewayUI()"><option value="relay">Cloudflare Worker با پارامتر url</option><option value="direct">اتصال مستقیم</option><option value="http">HTTP CONNECT Proxy</option><option value="auto">تشخیص خودکار</option></select></div><div><label>کلید محافظتی Worker، اختیاری</label><input id="worker_key" type="password" dir="ltr"></div><div><label><input id="verify_tls" type="checkbox" style="width:auto"> بررسی گواهی TLS</label></div></div><div class="gateway-services"><span>🎯 استخراج</span><span>🛍️ باسلام</span><span>🛒 ووکامرس</span><span>🤖 هوش مصنوعی</span><span>↻ GitHub</span><span>☁️ PythonAnywhere</span></div><div class="actions"><button onclick="saveSettings()">ذخیره مسیر مرکزی</button><button class="green" onclick="diagnoseGateway()">آزمایش کامل Worker</button><button class="gray" onclick="useMyWorker()">Worker پیش‌فرض</button></div><div id="networkStatus" class="status">آزمایش کامل مشخص می‌کند Worker هدر Authorization، متد POST و بدنه JSON را واقعاً عبور می‌دهد یا خیر.</div></div></section>
<section id="woo" class="pane"><div class="card dispatch-hero"><div class="section-head"><div><h3>🚀 ارسال کامل محصولات پروفایل</h3><small>کل محصولات ذخیره‌شده پروفایل، بدون نیاز به بازماندن صفحه، روی سرور ارسال می‌شوند</small></div><span class="badge">خودکار</span></div><div class="grid"><div><label>پروفایل مقصد</label><select id="dispatchProfile"></select></div><div><label>مقصدها</label><label><input id="dispatchWoo" type="checkbox" checked style="width:auto"> ووکامرس</label><label><input id="dispatchBasalam" type="checkbox" checked style="width:auto"> باسلام</label></div></div><div class="actions"><button class="green" onclick="dispatchSelectedProfile()">▶ ارسال کامل این پروفایل</button><button onclick="dispatchAllProfiles()">ارسال کامل همه پروفایل‌ها</button><button class="gray" onclick="openTaskManager()">مشاهده تسک منیجر</button></div><div id="dispatchStatus" class="status">هر پروفایل از محصولات ذخیره‌شده خودش استفاده می‌کند؛ پیشرفت و خطاها در تسک منیجر ثبت می‌شوند.</div></div><div class="card"><h3>اتصال و صف ووکامرس</h3><div class="grid"><div class="wide"><label>URL فروشگاه</label><input id="woo_url" dir="ltr"></div><div><label>Consumer key</label><input id="woo_ck" dir="ltr"></div><div><label>Consumer secret</label><input id="woo_cs" type="password" dir="ltr"></div><div><label>وضعیت محصول</label><select id="woo_product_status"><option value="draft">پیش‌نویس</option><option value="publish">انتشار</option><option value="pending">در انتظار بررسی</option><option value="private">خصوصی</option></select></div><div><label>تعداد هر مرحله</label><input id="woo_batch" type="number" min="1" max="25" value="10"></div><div><label><input id="woo_update" type="checkbox" checked style="width:auto"> بروزرسانی محصول هم‌SKU</label></div></div><div class="actions"><button onclick="saveSettings(true)">ذخیره اتصال</button><button class="gray" onclick="wooTest()">تست</button><button class="green" onclick="wooQueue()">افزودن نتایج به صف</button><button class="gray" onclick="loadWooJobs()">تازه‌سازی صف</button></div><div id="wooStatus" class="status">صف مرحله‌ای برای سازگاری با محدودیت اجرای PythonAnywhere</div><div id="wooJobList"></div></div><div class="card"><div class="section-head"><div><h3>🛍️ صف حرفه‌ای باسلام</h3><small>ایجاد یا ویرایش براساس SKU، همراه تصاویر و ادامه‌پذیر بعد از قطع صفحه</small></div><span class="badge">SDK رسمی</span></div><div class="grid"><div><label>تعداد در هر مرحله</label><input id="bsl_batch" type="number" min="1" max="5" value="3"></div><div><label>عملیات</label><button class="green" onclick="createBasalamQueue()">افزودن همه نتایج به صف</button></div></div><div class="actions"><button onclick="processBasalamQueue()">▶ اجرای مرحله بعد</button><button class="gray" onclick="loadBasalamJobs()">↻ تازه‌سازی صف‌ها</button></div><div id="bslSendStatus" class="status">صف مرحله‌ای برای جلوگیری از timeout حساب رایگان</div><div id="bslJobList" class="provider-list"></div></div></section>
<section id="deploy" class="pane"><div class="card"><h3>نصب‌کننده اتمیک از GitHub</h3><div class="note">نسخه تازه پیش از نصب با کامپایل Python بررسی می‌شود. نسخه فعلی در <code>scraper4.py.bak</code> می‌ماند. برای repository خصوصی بهتر است متغیر محیطی <code>GITHUB_TOKEN</code> را در WSGI تنظیم کنید.</div><div class="grid" style="margin-top:12px"><div><label>Repository (owner/repo)</label><input id="dep_repo" dir="ltr"></div><div><label>Branch</label><input id="dep_branch" dir="ltr"></div><div><label>مسیر فایل در repository</label><input id="dep_path" dir="ltr"></div><div><label>GitHub token اختیاری</label><input id="dep_token" type="password" dir="ltr" placeholder="خالی = نگه‌داشتن قبلی / استفاده از GITHUB_TOKEN"></div><div class="wide"><label>مسیر کامل WSGI برای Reload اختیاری</label><input id="dep_reload" dir="ltr" placeholder="/var/www/USERNAME_pythonanywhere_com_wsgi.py"></div></div><div class="actions"><button onclick="saveDeploy()">ذخیره تنظیمات</button><button class="gray" onclick="cleanupAccount()">پاکسازی فضای بلااستفاده</button><button class="gray" onclick="installDeps()">پاکسازی و نصب سبک Playwright</button><button class="gray" onclick="deployCheck()">بررسی نسخه</button><button class="green" onclick="deployRun()">نصب نسخه تازه</button><button class="gray" onclick="deployRollback()">بازگشت به .bak</button></div><div id="deployStatus" class="status">ابتدا تنظیمات را ذخیره و سپس نسخه را بررسی کنید.</div></div></section>
<footer class="app-footer"><nav class="tabs" aria-label="مراحل پروفایل"><button class="on" data-tab="scrape"><i>🎯</i><span>شروع</span></button><button data-tab="profileSettings"><i>⚙️</i><span>تنظیمات</span></button><button data-tab="selectors"><i>🎨</i><span>سلکتورها</span></button><button data-tab="results"><i>📊</i><span>نتایج</span></button><button data-tab="woo"><i>📤</i><span>ارسال</span></button><button data-tab="imports"><i>📥</i><span>درون‌ریزی</span></button></nav></footer></div><div id="aiTestModal" class="result-modal" onclick="if(event.target===this)closeAITestModal()"><div class="result-modal-card"><div class="result-modal-head"><div><h2>🧪 آزمایشگاه پیشرفته مدل‌ها</h2><small id="aiModalSubtitle">پاسخ مشتری و دسته‌بندی نمونه برای همه مدل‌ها</small></div><div class="actions"><button class="green" onclick="activateBestAIModel()">★ فعال‌سازی بهترین</button><button class="gray" onclick="downloadAITestResults()">↓ JSON</button><button class="gray" onclick="closeAITestModal()">✕</button></div></div><div class="modal-tools pro-filters"><input id="aiResultSearch" placeholder="جستجو در ارائه‌دهنده، مدل، پاسخ یا خطا…" oninput="aiResultPage=1;renderAITestModal()"><select id="aiResultProvider" onchange="aiResultPage=1;renderAITestModal()"><option value="all">همه ارائه‌دهندگان</option></select><select id="aiResultFilter" onchange="aiResultPage=1;renderAITestModal()"><option value="all">همه وضعیت‌ها</option><option value="ok">کاملاً سالم</option><option value="partial">ناقص</option><option value="failed">ناموفق</option><option value="waiting">در انتظار</option></select><select id="aiResultCapability" onchange="aiResultPage=1;renderAITestModal()"><option value="all">همه قابلیت‌ها</option><option value="free">رایگان</option><option value="reasoning">استدلالی</option><option value="vision">Vision</option><option value="tools">Tool calling</option></select><select id="aiResultSort" onchange="renderAITestModal()"><option value="score">بیشترین امتیاز</option><option value="fast">سریع‌ترین</option><option value="provider">ارائه‌دهنده</option><option value="model">نام مدل</option><option value="status">وضعیت</option></select><select id="aiResultLatency" onchange="aiResultPage=1;renderAITestModal()"><option value="0">هر latency</option><option value="1000">زیر ۱ ثانیه</option><option value="3000">زیر ۳ ثانیه</option><option value="10000">زیر ۱۰ ثانیه</option></select><input id="aiResultMinScore" type="number" min="0" max="100" value="0" placeholder="حداقل امتیاز" oninput="aiResultPage=1;renderAITestModal()"></div><div id="aiModalStats" class="stats"></div><div id="aiCompareBar" class="ai-compare-bar"></div><div class="modal-table"><table class="ai-pro-table"><thead><tr><th>انتخاب</th><th>رتبه</th><th>ارائه‌دهنده / مدل</th><th>امتیاز و قابلیت</th><th>پاسخ مشتری</th><th>دسته‌بندی محصول</th><th>کارایی</th><th>نتیجه</th></tr></thead><tbody id="aiModalRows"></tbody></table></div><div class="modal-pagination"><button class="gray" onclick="aiResultPage=Math.max(1,aiResultPage-1);renderAITestModal()">قبلی</button><span id="aiResultPageInfo"></span><button class="gray" onclick="aiResultPage++;renderAITestModal()">بعدی</button><select id="aiResultPageSize" onchange="aiResultPage=1;renderAITestModal()"><option>25</option><option selected>50</option><option>100</option><option>250</option></select></div></div></div><script>
let products=[],profiles={},activeProfile='',currentBuild=''; const $=id=>document.getElementById(id); const esc=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
(function phpLayout(){const scrape=$('scrape'),adv=scrape.querySelector('details.advanced'),status=$('status'),table=status?status.nextElementSibling:null;if(adv)$('selectorsMount').appendChild(adv);if(status)$('resultsMount').appendChild(status);if(table)$('resultsMount').appendChild(table);scrape.querySelectorAll('[onclick^="download"],label.file-btn').forEach(x=>x.remove())})();
(function globalDrawer(){const mount=$('adminMount');['settings','profiles','jobs','changelogAdmin','deploy','files'].forEach((id,i)=>{const node=$(id);if(node){node.classList.remove('pane','on');node.classList.add('admin-section');;mount.appendChild(node)}});const woo=$('woo'),grid=woo?woo.querySelector('.grid'):null,settings=$('settings');if(grid&&settings){const card=document.createElement('div');card.className='card';card.innerHTML='<h3>🛒 اتصال ووکامرس</h3><div id="wooConnectionMount"></div><div class="actions"><button onclick="saveSettings(true)">ذخیره اتصال</button><button class="gray" onclick="wooTest()">تست اتصال</button></div><div id="wooConnectionStatus" class="status"></div>';settings.appendChild(card);card.querySelector('#wooConnectionMount').appendChild(grid);woo.querySelectorAll('button[onclick="saveSettings(true)"],button[onclick="wooTest()"]').forEach(x=>x.remove())}})();
function toggleSettingsPanel(force){const open=force===undefined?!$('settingsPanel').classList.contains('open'):force;$('settingsPanel').classList.toggle('open',open);$('settingsOverlay').classList.toggle('open',open);$('hamburgerBtn').classList.toggle('active',open);document.body.style.overflow=(open||$('aiTestModal')?.classList.contains('open'))?'hidden':''}
showAdmin('backupAdmin');
function showAdmin(id){document.querySelectorAll('.admin-section').forEach(x=>x.classList.toggle('admin-on',x.id===id));document.querySelectorAll('.admin-nav button').forEach(x=>x.classList.toggle('nav-on',(x.getAttribute('onclick')||'').includes("'"+id+"'")))}
function openTab(name){const b=document.querySelector(`.tabs button[data-tab="${name}"]`),target=$(name);if(!target)return;document.querySelectorAll('.tabs button,.pane').forEach(x=>x.classList.remove('on'));if(b)b.classList.add('on');target.classList.add('on');localStorage.setItem('scraperActiveTab',b?name:'more');window.scrollTo({top:0,behavior:'smooth'})}document.querySelectorAll('.tabs button').forEach(b=>b.onclick=()=>openTab(b.dataset.tab));
function config(){let selectors={},detail_selectors={};['container','title','price','link','image','sku'].forEach(k=>selectors[k]=$('sel_'+k).value.trim());['gallery','variations','weight','category','price','stock','brand','sku','short_desc','long_desc'].forEach(k=>detail_selectors[k]=$('det_'+k).value.trim());let profile_rules={title_prefix:$('rule_title_prefix').value.trim(),title_suffix:$('rule_title_suffix').value.trim(),price_mode:$('rule_price_mode').value,price_value:+$('rule_price_value').value,price_round:+$('rule_price_round').value,default_stock:$('rule_default_stock').value,default_category:$('rule_default_category').value.trim(),bsl_category_id:+$('rule_bsl_category_id').value,woo_category_id:+$('rule_woo_category_id').value};return {url:$('url').value.trim(),pages:+$('pages').value,render:$('render').value,pagination:$('pagination').value,page_value:$('page_value').value.trim(),scrolls:+$('scrolls').value,enrich:$('enrich').value==='1',detail_limit:+$('detail_limit').value,selectors,detail_selectors,profile_rules}}
function apply(c){if(!c)return;['url','pages','render','pagination','page_value','scrolls','detail_limit'].forEach(k=>{if(c[k]!==undefined)$(k).value=c[k]});$('enrich').value=c.enrich?'1':'0';Object.entries(c.selectors||{}).forEach(([k,v])=>{if($('sel_'+k))$('sel_'+k).value=v||''});Object.entries(c.detail_selectors||{}).forEach(([k,v])=>{if($('det_'+k))$('det_'+k).value=v||''});let r=c.profile_rules||{};['title_prefix','title_suffix','price_mode','price_value','price_round','default_stock','default_category','bsl_category_id','woo_category_id'].forEach(k=>{if($('rule_'+k)&&r[k]!==undefined)$('rule_'+k).value=r[k]})}
async function api(path,opt={}){let r=await fetch(path,{...opt,headers:{'Content-Type':'application/json',...(opt.headers||{})}});let j=await r.json();if(!r.ok||j.ok===false)throw Error(j.error||'خطای درخواست');return j}
let deploySecret=sessionStorage.getItem('scraperDeployPassword')||'';
async function deployApi(path,opt={}){if(!deploySecret){deploySecret=prompt('رمز مدیریت نصب را وارد کنید:')||'';if(!deploySecret)throw Error('رمز مدیریت نصب وارد نشد');sessionStorage.setItem('scraperDeployPassword',deploySecret)}try{return await api(path,{...opt,headers:{...(opt.headers||{}),'X-Deploy-Password':deploySecret}})}catch(e){if(/رمز مدیریت نصب/.test(e.message)){deploySecret='';sessionStorage.removeItem('scraperDeployPassword')}throw e}}
const taskStatusLabel=s=>({waiting:'در صف',running:'در حال اجرا',completed:'کامل',failed:'ناموفق',cancelled:'متوقف',interrupted:'قطع‌شده'}[s]||s||'—');
const shortDuration=n=>{n=Math.max(0,Math.round(Number(n||0)));if(n<60)return n+' ثانیه';if(n<3600)return Math.floor(n/60)+' دقیقه و '+n%60+' ثانیه';return Math.floor(n/3600)+' ساعت و '+Math.floor(n%3600/60)+' دقیقه'};
let taskAutoRefresh=true,taskRefreshTimer=0,taskRowsData=[];
function taskCard(t){const p=Math.max(0,Math.min(100,Number(t.progress||0))),elapsed=t.elapsed_seconds??Math.max(0,Math.floor(Date.now()/1000-Number(t.created_at||Date.now()/1000))),active=['waiting','running'].includes(t.status);let destination=Object.entries(t.destinations||{}).map(([k,v])=>(k==='woocommerce'?'ووکامرس':'باسلام')+`: ${v.sent||0} موفق / ${v.failed||0} خطا`).join(' · ');return `<article class="task-card ${esc(t.status)}"><div class="task-top"><div><b>${esc(t.title||t.kind)}</b>${t.kind==='scrape'?` <span class="badge profile-task-chip">👤 ${esc(t.profile||'پروفایل نامشخص')}</span>`:''}<br><small>${esc(t.kind||'')} · ${new Date((t.created_at||0)*1000).toLocaleString('fa-IR')} ${t.execution==='parallel'?' · اجرای مستقل':''}</small></div><span class="badge ${t.status==='completed'?'ok':t.status==='failed'?'error':''}">${esc(taskStatusLabel(t.status))}</span></div><div class="task-progress"><i style="width:${p}%"></i></div><div class="task-top"><span class="task-step">${esc(t.step||'')}</span><b>${p}٪</b></div><div class="task-metrics"><span>انجام ${t.done??'—'} / ${t.total??'—'}</span><span class="ok">موفق ${t.sent??'—'}</span><span class="error">خطا ${t.failed??'—'}</span><span>سپری‌شده ${shortDuration(elapsed)}</span>${active&&t.eta_seconds!=null?`<span>زمان باقی‌مانده ≈ ${shortDuration(t.eta_seconds)}</span>`:''}</div>${destination?`<small>${esc(destination)}</small>`:''}<div class="task-timeline">${(t.details||[]).map(x=>`<div class="task-event"><b>${esc(x.at)}</b> · ${esc(x.text)}</div>`).join('')||'<div class="task-event">هنوز رویدادی ثبت نشده است.</div>'}</div><div class="task-actions">${active?`<button class="gray" onclick="cancelTask('${esc(t.id)}')">توقف امن</button>`:(t.kind==='scrape'&&['failed','interrupted','cancelled'].includes(t.status)?`<button onclick="retryTask('${esc(t.id)}')">↻ ادامه از checkpoint</button>`:'')}<button class="gray" onclick="deleteTask('${esc(t.id)}')">حذف گزارش</button></div></article>`}
function renderTaskManager(){let q=($('taskSearch')?.value||'').toLowerCase(),sf=$('taskStatusFilter')?.value||'all',kf=$('taskKindFilter')?.value||'all';let rows=taskRowsData.filter(t=>{let active=['waiting','running'].includes(t.status),statusOk=sf==='all'||sf==='active'&&active||sf==='completed'&&t.status==='completed'||sf==='failed'&&['failed','interrupted','cancelled'].includes(t.status),text=[t.title,t.profile,t.step,t.error,...(t.details||[]).map(x=>x.text)].join(' ').toLowerCase();return statusOk&&(kf==='all'||t.kind===kf)&&(!q||text.includes(q))});$('taskManagerList').innerHTML=rows.map(taskCard).join('')||'<div class="note">وظیفه‌ای مطابق این فیلتر پیدا نشد.</div>'}
async function loadTaskManager(){clearTimeout(taskRefreshTimer);try{let d=await deployApi('/api/tasks');taskRowsData=d.tasks||[];$('taskManagerBadge').textContent=(d.summary.running||0)+' اجرای مستقل';$('taskTopCount').textContent=d.summary.running||0;$('taskManagerTopBtn').classList.toggle('has-active',d.summary.running>0);$('taskManagerStats').innerHTML=`<div class="space-card"><b>${d.tasks.length}</b><span>کل گزارش‌ها</span></div><div class="space-card"><b>${d.summary.running}</b><span>هم‌زمان فعال</span></div><div class="space-card"><b>${d.summary.completed}</b><span>کامل</span></div><div class="space-card"><b>${d.summary.failed}</b><span>نیازمند بررسی</span></div>`;renderTaskManager();$('taskAutoBtn').textContent='پایش خودکار: '+(taskAutoRefresh?'روشن':'خاموش')}catch(e){$('taskManagerList').innerHTML='<span class="error">'+esc(e.message)+'</span>'}if(taskAutoRefresh&&$('jobs')?.classList.contains('admin-on'))taskRefreshTimer=setTimeout(loadTaskManager,1800)}
async function cancelTask(id){if(!confirm('وظیفه پس از پایان عملیات جاری متوقف شود؟'))return;await deployApi('/api/tasks/'+encodeURIComponent(id)+'/cancel',{method:'POST',body:'{}'});loadTaskManager()}
async function retryTask(id){await deployApi('/api/tasks/'+encodeURIComponent(id)+'/retry',{method:'POST',body:'{}'});loadTaskManager()}
async function deleteTask(id){await deployApi('/api/tasks/'+encodeURIComponent(id),{method:'DELETE'});loadTaskManager()}
function openTaskManager(){toggleSettingsPanel(true);showAdmin('jobs');loadTaskManager()}
function dispatchPayload(){let destinations=[];if($('dispatchWoo').checked)destinations.push('woocommerce');if($('dispatchBasalam').checked)destinations.push('basalam');return {destinations,woo_status:$('woo_product_status').value,woo_update:$('woo_update').checked}}
async function dispatchSelectedProfile(){let n=$('dispatchProfile').value;if(!n){$('dispatchStatus').innerHTML='<span class="error">پروفایل دارای محصول را انتخاب کنید.</span>';return}try{let d=await deployApi('/api/dispatch/profile/'+encodeURIComponent(n),{method:'POST',body:JSON.stringify(dispatchPayload())});$('dispatchStatus').innerHTML='<span class="ok">وظیفه کامل «'+esc(n)+'» روی سرور آغاز شد.</span> شناسه: '+esc(d.task.id);openTaskManager()}catch(e){$('dispatchStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function dispatchAllProfiles(){if(!confirm('محصولات ذخیره‌شده همه پروفایل‌ها به مقصدهای انتخاب‌شده ارسال شوند؟'))return;try{let d=await deployApi('/api/dispatch/profiles',{method:'POST',body:JSON.stringify(dispatchPayload())});$('dispatchStatus').innerHTML='<span class="ok">'+d.tasks.length+' وظیفه مستقل روی سرور ساخته شد.</span>'+((d.errors||[]).length?'\nردشده: '+esc(d.errors.join(' · ')):'');openTaskManager()}catch(e){$('dispatchStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function loadChangelog(){try{let d=await api('/api/changelog');$('changeCurrent').textContent='نسخه جاری '+d.current;$('changelogList').innerHTML=(d.releases||[]).map((r,i)=>`<article class="release ${i===0?'current':''}"><div class="release-head"><b>نسخه ${esc(r.version)} · ${esc(r.title)}</b><small>${esc(r.date)}</small></div><ul>${(r.items||[]).map(x=>'<li>'+esc(x)+'</li>').join('')}</ul></article>`).join('')}catch(e){$('changelogList').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function loadTaskTopSummary(){try{let d=await api('/api/tasks/summary');$('taskTopCount').textContent=d.active||0;$('taskManagerTopBtn').classList.toggle('has-active',d.active>0);$('taskManagerTopBtn').title=(d.active||0)+' وظیفه فعال · '+(d.attention||0)+' نیازمند بررسی'}catch(e){}}
async function init(){loadTaskTopSummary();let d=await api('/api/config');currentBuild=d.build||'';$('appVersion').textContent='v'+(d.version||'3.6.0');profiles=d.profiles||{};$('timeout').value=d.network.timeout;$('gap_ms').value=d.network.gap_ms;$('proxy').value=d.network.proxy||'';$('proxy_mode').value=d.network.proxy_mode||'auto';$('worker_key').value=d.network.worker_key||'';$('verify_tls').checked=d.network.verify_tls!==false;updateGatewayUI();$('woo_url').value=d.woocommerce.url||'';$('woo_ck').value=d.woocommerce.consumer_key||'';$('woo_cs').value=d.woocommerce.consumer_secret||'';$('dep_repo').value=d.deploy.repo||'';$('dep_branch').value=d.deploy.branch||'';$('dep_path').value=d.deploy.path||'';$('dep_reload').value=d.deploy.reload_file||'';$('dep_token').placeholder=d.deploy.has_github_token?'توکن تنظیم شده است؛ خالی = نگه‌داشتن':'GitHub token اختیاری';activeProfile=d.active_profile||'';renderProfiles();if(activeProfile&&profiles[activeProfile])loadProfile(activeProfile,false,false);else updateActiveProfileUI();loadJobs();loadWooJobs();let saved=localStorage.getItem('scraperActiveTab');openTab(['scrape','profileSettings','selectors','results','woo','imports'].includes(saved)?saved:'scrape')}
async function loadBasalam(){try{let d=await deployApi('/api/basalam/config'),b=d.basalam,s=d.sdk||{};$('bsl_vendor').value=b.vendor_id||0;$('bsl_category').value=b.category_id||0;$('bsl_token').value=b.token||'';$('bsl_refresh').value=b.refresh_token||'';$('bsl_client_mode').value=b.client_mode||'auto';$('bsl_api_base_url').value=b.api_base_url||'https://openapi.basalam.com';$('bsl_days').value=b.preparation_days||3;$('bsl_weight').value=b.weight||500;$('bsl_stock').value=b.stock||10;$('bsl_update').checked=b.update_existing!==false;$('bslSdkBadge').textContent=s.installed?'SDK '+s.version:'نصب نشده';$('bslSdkBadge').className='badge '+(s.installed?'ok':'error');if(b.last_test_at)$('bslAdminStatus').innerHTML='<span class="ok">آخرین اتصال موفق: '+esc(b.last_test_user||'باسلام')+' · '+new Date(b.last_test_at*1000).toLocaleString('fa-IR')+'</span>'}catch(e){$('bslAdminStatus').textContent=e.message}}
async function saveBasalam(){try{let basalam={vendor_id:+$('bsl_vendor').value,category_id:+$('bsl_category').value,token:$('bsl_token').value.trim(),refresh_token:$('bsl_refresh').value.trim(),client_mode:$('bsl_client_mode').value,api_base_url:$('bsl_api_base_url').value.trim(),preparation_days:+$('bsl_days').value,weight:+$('bsl_weight').value,stock:+$('bsl_stock').value,update_existing:$('bsl_update').checked};await deployApi('/api/basalam/settings',{method:'POST',body:JSON.stringify({basalam})});$('bslAdminStatus').innerHTML='<span class="ok">اتصال ذخیره شد.</span>'}catch(e){$('bslAdminStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
function renderLiveTask(task){$('bslLiveTask').style.display='block';$('bslTaskTitle').textContent=task.title||'وظیفه سرور';$('bslTaskPercent').textContent=(task.progress||0)+'٪';$('bslTaskBar').style.width=(task.progress||0)+'%';$('bslTaskStep').textContent=task.step||'';$('bslTaskDetails').innerHTML=(task.details||[]).slice().reverse().map(x=>`<div class="live-detail"><b>${esc(x.at)}</b> · ${esc(x.text)}</div>`).join('')}
async function watchLiveTask(id){for(;;){let d=await deployApi('/api/tasks/'+encodeURIComponent(id));renderLiveTask(d.task);if(['completed','failed'].includes(d.task.status))return d.task;await new Promise(r=>setTimeout(r,850))}}
async function installBasalamSdk(){try{$('bslAdminStatus').innerHTML='<span class="spinner"></span> وظیفه نصب روی سرور آغاز می‌شود…';let d=await deployApi('/api/basalam/sdk/install/start',{method:'POST',body:'{}'}),task=await watchLiveTask(d.task.id);if(task.status==='failed')throw Error(task.error||'نصب ناموفق بود');$('bslAdminStatus').innerHTML='<span class="ok">SDK روی سرور نصب و آماده شد · نسخه '+esc(task.sdk?.version||'')+'</span>';$('bslSdkBadge').textContent='SDK '+(task.sdk?.version||'آماده');$('bslSdkBadge').className='badge ok'}catch(e){$('bslAdminStatus').innerHTML='<span class="error">خطای نصب: '+esc(e.message)+'</span>'}}
async function testBasalam(){try{$('bslAdminStatus').innerHTML='<span class="spinner"></span> SDK آماده است؛ در حال آزمایش مجوز توکن و اتصال API باسلام…';let d=await deployApi('/api/basalam/test',{method:'POST',body:'{}'});$('bslAdminStatus').innerHTML='<span class="ok">اتصال باسلام موفق: '+esc(d.user)+' · کلاینت '+esc(d.client||'—')+(d.installed_now?' · SDK نیز نصب شد':'')+'</span>';$('bslSdkBadge').textContent='SDK '+(d.sdk?.version||'آماده');$('bslSdkBadge').className='badge ok'}catch(e){$('bslAdminStatus').innerHTML='<span class="error">خطای اتصال: '+esc(e.message)+'</span>'}}
async function loadBasalamVendor(){try{$('bslAdminStatus').textContent='در حال دریافت اطلاعات رسمی غرفه…';let d=await deployApi('/api/basalam/vendor'),v=d.vendor||{};$('bslVendorCard').innerHTML=`<div class="provider-row vendor-card"><div><b>${esc(v.title||'غرفه')} <code>#${esc(v.id)}</code></b><br><small>${esc(v.identifier||'')} · ${esc(v.city||'')} · امتیاز ${esc(v.score??'—')}</small></div><span class="badge">${esc(v.status||'—')}</span></div>`;$('bslAdminStatus').innerHTML='<span class="ok">اطلاعات غرفه با کلاینت '+esc(d.client||'—')+' دریافت شد.</span>'}catch(e){$('bslAdminStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function loadBasalamProducts(){try{$('bslAdminStatus').textContent='در حال دریافت محصولات غرفه…';let d=await deployApi('/api/basalam/products');$('bslProductList').innerHTML=(d.products||[]).map(p=>`<div class="provider-row"><div><b>${esc(p.name||'بدون نام')}</b> <code>#${esc(p.id)}</code><br><small>SKU: ${esc(p.sku||'—')} · موجودی: ${esc(p.stock??'—')} · قیمت: ${esc(p.price??'—')}</small></div><span class="badge">${esc(p.status||'—')}</span></div>`).join('')||'<div class="note">محصولی در غرفه پیدا نشد.</div>';$('bslAdminStatus').innerHTML='<span class="ok">'+d.total+' محصول نخست غرفه با '+esc(d.client||'—')+' دریافت شد.</span>'}catch(e){$('bslAdminStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
function bslApiList(v){if(Array.isArray(v))return v;if(v&&typeof v==='object'){for(const k of ['data','items','results','chats','messages','parcels','products']){let x=v[k];if(Array.isArray(x))return x;if(x&&typeof x==='object'){let y=bslApiList(x);if(y.length)return y}}}return []}
let activeBasalamChat=0;
async function loadBasalamOperations(){try{$('bslOperationStatus').innerHTML='<span class="spinner"></span> دریافت هم‌زمان گفت‌وگوها و سفارش‌ها از REST API…';let d=await deployApi('/api/basalam/operations'),chats=bslApiList(d.chats),orders=bslApiList(d.orders),unseen=d.unseen_count?.count??d.unseen_count?.data?.count??d.unseen_count??0;$('bslOperationStats').innerHTML=`<div class="space-card"><b>${chats.length}</b><span>گفت‌وگوی اخیر</span></div><div class="space-card"><b>${typeof unseen==='object'?'—':unseen}</b><span>پیام خوانده‌نشده</span></div><div class="space-card"><b>${orders.length}</b><span>سفارش/مرسوله</span></div>`;$('bslChatList').innerHTML=chats.map(c=>{let contact=c.contact||{},last=c.last_message||{},txt=last.content?.text||last.text||'';return `<div class="operation-item" onclick="openBasalamChat(${Number(c.id)||0},'${esc(contact.name||contact.title||'گفت‌وگو')}')"><b>${esc(contact.name||contact.title||'گفت‌وگو #'+c.id)}</b><small>${esc(txt||c.chat_type||'')} · خوانده‌نشده ${c.unseen_message_count||0}</small></div>`}).join('')||'<div class="note">گفت‌وگویی برگردانده نشد.</div>';$('bslOrderList').innerHTML=orders.map(o=>`<div class="operation-item"><b>سفارش/مرسوله #${esc(o.id||o.order_id||'—')}</b><small>${esc(o.status||o.state||'')} · ${esc(o.created_at||o.estimate_send_at||'')}</small></div>`).join('')||'<div class="note">سفارشی برگردانده نشد.</div>';$('bslOperationStatus').innerHTML=(d.chats_error?'<span class="error">چت: '+esc(d.chats_error)+'</span><br>':'')+(d.orders_error?'<span class="error">سفارش: '+esc(d.orders_error)+'</span>':'<span class="ok">داشبورد REST API بروزرسانی شد.</span>')}catch(e){$('bslOperationStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function openBasalamChat(id,title){if(!id)return;activeBasalamChat=id;$('bslChatPanel').style.display='block';$('bslChatTitle').textContent=title+' · #'+id;$('bslMessages').innerHTML='<span class="spinner"></span>';try{let d=await deployApi('/api/basalam/chats/'+id+'/messages'),rows=d.messages||[];$('bslMessages').innerHTML=rows.map(m=>`<div class="message">${esc(m.content?.text||m.text||m.message_type||'پیام')}<small>${esc(m.created_at||'')} · ${esc(m.sender?.id||'')}</small></div>`).join('')||'<div class="note">پیامی برگردانده نشد.</div>';$('bslMessages').scrollTop=$('bslMessages').scrollHeight}catch(e){$('bslMessages').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function sendBasalamMessage(){let text=$('bslMessageText').value.trim();if(!activeBasalamChat||!text)return;try{await deployApi('/api/basalam/chats/'+activeBasalamChat+'/messages',{method:'POST',body:JSON.stringify({text})});$('bslMessageText').value='';await openBasalamChat(activeBasalamChat,$('bslChatTitle').textContent.split('·')[0]);$('bslOperationStatus').innerHTML='<span class="ok">پیام با REST API ارسال شد.</span>'}catch(e){$('bslOperationStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function searchBasalamCategories(){try{$('bslCategoryResults').innerHTML='<div class="status">در حال دریافت درخت دسته‌ها…</div>';let d=await deployApi('/api/basalam/categories?q='+encodeURIComponent($('bsl_category_query').value.trim()));$('bslCategoryResults').innerHTML=(d.categories||[]).map(x=>`<div class="category-item"><span>${esc(x.path)} <code>${esc(x.id)}</code></span><button onclick="chooseBasalamCategory('${esc(x.id)}','${esc(x.name)}')">انتخاب</button></div>`).join('')||'<div class="note">دسته‌ای پیدا نشد.</div>'}catch(e){$('bslCategoryResults').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
function chooseBasalamCategory(id,name){$('bsl_category').value=id;$('bsl_category_query').value=name;$('bslCategoryResults').innerHTML='<span class="ok">دسته انتخاب شد؛ اکنون تنظیمات باسلام را ذخیره کنید.</span>'}
let activeBasalamJob='';
function renderBasalamJobs(jobs){let list=jobs||[];if(list[0])activeBasalamJob=list[0].id;$('bslJobList').innerHTML=list.map(j=>`<div class="provider-row"><div><b>${esc(j.id)}</b><br><small>${esc(j.status)} · ${j.cursor}/${j.total} · موفق ${j.sent} · ویرایش ${j.updated} · خطا ${j.failed}</small><div class="progress-track"><i style="width:${j.total?Math.round(j.cursor/j.total*100):0}%"></i></div></div><div><button class="gray" onclick="deleteBasalamJob('${esc(j.id)}')">حذف</button></div>${(j.results||[]).slice(-3).map(r=>`<small class="${r.ok?'ok':'error'}">${r.ok?'✓':'✕'} ${esc(r.source||'')} ${esc(r.error||'')}</small>`).join('')}</div>`).join('')||'<div class="note">صفی وجود ندارد.</div>'}
async function loadBasalamJobs(){try{let d=await deployApi('/api/basalam/jobs');renderBasalamJobs(d.jobs)}catch(e){$('bslSendStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function createBasalamQueue(){if(!products.length){$('bslSendStatus').innerHTML='<span class="error">ابتدا محصول استخراج یا وارد کنید.</span>';return}try{let d=await deployApi('/api/basalam/jobs',{method:'POST',body:JSON.stringify({products})});activeBasalamJob=d.job.id;$('bslSendStatus').innerHTML='<span class="ok">صف '+d.job.total+' محصولی ساخته شد.</span>';await loadBasalamJobs()}catch(e){$('bslSendStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function processBasalamQueue(){if(!activeBasalamJob){await loadBasalamJobs();if(!activeBasalamJob)return}try{$('bslSendStatus').innerHTML='<span class="spinner"></span> در حال ارسال مرحله با SDK رسمی…';let d=await deployApi('/api/basalam/jobs/'+encodeURIComponent(activeBasalamJob)+'/process',{method:'POST',body:JSON.stringify({batch:+$('bsl_batch').value})});$('bslSendStatus').innerHTML='<span class="ok">مرحله انجام شد؛ پیشرفت '+d.job.cursor+'/'+d.job.total+'، موفق '+d.job.sent+'، خطا '+d.job.failed+'</span>';await loadBasalamJobs()}catch(e){$('bslSendStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deleteBasalamJob(id){if(!confirm('صف باسلام حذف شود؟'))return;await deployApi('/api/basalam/jobs/'+encodeURIComponent(id),{method:'DELETE'});if(activeBasalamJob===id)activeBasalamJob='';await loadBasalamJobs()}
let aiProviders=[];
function aiPane(id,btn){document.querySelectorAll('.ai-pane').forEach(x=>x.classList.toggle('on',x.id==='aiPane'+id[0].toUpperCase()+id.slice(1)));document.querySelectorAll('.ai-tabs button').forEach(x=>x.classList.toggle('on',x===btn))}
function aiCurrent(){return aiProviders.find(x=>x.id===$('ai_provider').value)}
function renderAI(){let selected=$('ai_provider').value,sel=$('ai_provider');sel.innerHTML='<option value="">— انتخاب —</option>'+aiProviders.map(p=>`<option value="${esc(p.id)}">${p.enabled?'●':'○'} ${esc(p.name)} (${p.models.length})</option>`).join('');sel.value=aiProviders.some(x=>x.id===selected)?selected:(sel.dataset.selected||aiProviders[0]?.id||'');renderAIModels();$('aiSummary').textContent=aiProviders.length+' ارائه‌دهنده · '+aiProviders.reduce((n,p)=>n+p.models.length,0)+' مدل';$('aiProviderList').innerHTML=aiProviders.map(p=>`<div class="provider-row"><div><b>${esc(p.name)}</b> <code>${esc(p.id)}</code><br><small>${esc(p.url)} · ${p.key_count} کلید ${p.key_preview.map(esc).join('، ')}</small></div><button class="gray" onclick="editAIProvider('${esc(p.id)}')">ویرایش</button><div class="models">${p.models.slice(0,12).map(m=>`<span class="model-chip">${esc(m.name||m.id)}</span>`).join('')}${p.models.length>12?`<span class="badge">+${p.models.length-12}</span>`:''}</div></div>`).join('')||'<div class="note">هنوز ارائه‌دهنده‌ای ثبت نشده است. فایل PHP را بارگذاری یا ارائه‌دهنده تازه بسازید.</div>'}
function renderAIModels(){let p=aiCurrent(),sel=$('ai_model'),wanted=sel.dataset.selected||sel.value;sel.innerHTML=(p?.models||[]).filter(m=>m.enabled!==false).map(m=>`<option value="${esc(m.id)}">${esc(m.name||m.id)}${m.free?' · رایگان':''}</option>`).join('')||'<option value="">— مدلی نیست —</option>';if(p?.models.some(m=>m.id===wanted))sel.value=wanted}
async function importAIProviders(input){if(!input.files[0])return;try{$('aiStatus').textContent='در حال درون‌ریزی و ساخت فهرست مدل‌ها…';let secret=await needDeploySecret(),f=new FormData();f.append('file',input.files[0]);let r=await fetch('/api/ai/providers/import',{method:'POST',headers:{'X-Deploy-Password':secret},body:f}),d=await r.json();if(!r.ok||!d.ok)throw Error(d.error);$('aiStatus').innerHTML='<span class="ok">'+d.count+' ارائه‌دهنده و '+d.models+' مدل وارد شد.</span>';await loadAI()}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}finally{input.value=''}}
async function loadAI(){try{let d=await deployApi('/api/ai/config');aiProviders=d.providers||[];$('ai_provider').dataset.selected=d.selected?.provider||'';$('ai_model').dataset.selected=d.selected?.model||'';$('ai_temperature').value=d.ai.temperature??.3;$('ai_max_tokens').value=d.ai.max_tokens||1200;$('ai_system_prompt').value=d.ai.system_prompt||'';renderAI()}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function aiSelectProvider(){renderAIModels();let p=aiCurrent(),m=p?.models?.[0]?.id;if(p&&m)await selectAI(p.id,m)}
async function aiSelectModel(){let p=aiCurrent();if(p&&$('ai_model').value)await selectAI(p.id,$('ai_model').value)}
async function selectAI(provider,model){try{await deployApi('/api/ai/select',{method:'POST',body:JSON.stringify({provider,model})});$('aiStatus').innerHTML='<span class="ok">مدل فعال ذخیره شد.</span>'}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
function newAIProvider(){['ai_edit_id','ai_edit_name','ai_edit_vendor','ai_endpoint','ai_keys','ai_models'].forEach(x=>$(x).value='');$('ai_keys').dataset.preserve='';$('ai_edit_enabled').checked=true;aiPane('editor',document.querySelectorAll('.ai-tabs button')[1])}
function editAIProvider(id){let p=aiProviders.find(x=>x.id===id);if(!p)return;$('ai_edit_id').value=p.id;$('ai_edit_name').value=p.name;$('ai_edit_vendor').value=p.vendor||'';$('ai_endpoint').value=p.url||'';$('ai_edit_enabled').checked=p.enabled!==false;$('ai_keys').value='';$('ai_keys').placeholder=(p.key_count||0)+' کلید محفوظ است؛ کلید تازه را برای افزودن وارد کنید';$('ai_keys').dataset.preserve='1';$('ai_models').value=p.models.map(m=>m.id+' | '+(m.name||m.id)+(m.free?' | free':'')).join('\n');aiPane('editor',document.querySelectorAll('.ai-tabs button')[1])}
async function saveAIProvider(){try{let id=$('ai_edit_id').value.trim();if(!id)throw Error('شناسه ارائه‌دهنده لازم است');let preserve=$('ai_keys').dataset.preserve==='1',prior=aiProviders.find(x=>x.id===id);let keys=$('ai_keys').value.split(/\n+/).map(x=>{let [key,label,acct]=x.split('|');return {key:key.trim(),label:(label||'').trim(),acct:(acct||'').trim(),enabled:true}}).filter(x=>x.key);let models=$('ai_models').value.split(/\n+/).map(x=>{let [mid,name,free]=x.split('|');mid=(mid||'').trim();return {...(prior?.models.find(m=>m.id===mid)||{}),id:mid,name:(name||mid||'').trim(),free:/free|رایگان|true|1/i.test(free||''),enabled:true}}).filter(x=>x.id);let provider={id,name:$('ai_edit_name').value.trim()||id,vendor:$('ai_edit_vendor').value.trim(),url:$('ai_endpoint').value.trim(),enabled:$('ai_edit_enabled').checked,models};provider.apiKeys=keys;let d=await deployApi('/api/ai/providers/save',{method:'POST',body:JSON.stringify({provider,preserve_keys:preserve})});aiProviders=d.providers||[];$('ai_keys').dataset.preserve='';renderAI();$('aiStatus').innerHTML='<span class="ok">ارائه‌دهنده و '+models.length+' مدل ذخیره شد.</span>'}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deleteAIProvider(){let id=$('ai_edit_id').value.trim();if(!id||!confirm('ارائه‌دهنده و مدل‌هایش حذف شوند؟'))return;try{let d=await deployApi('/api/ai/providers/'+encodeURIComponent(id),{method:'DELETE'});aiProviders=d.providers||[];renderAI();newAIProvider();$('aiStatus').innerHTML='<span class="ok">حذف شد.</span>'}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function saveAIOptions(){try{let ai={temperature:+$('ai_temperature').value,max_tokens:+$('ai_max_tokens').value,system_prompt:$('ai_system_prompt').value};await deployApi('/api/ai/settings',{method:'POST',body:JSON.stringify({ai})});$('aiStatus').innerHTML='<span class="ok">گزینه‌های تولید ذخیره شد.</span>'}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function testAI(){try{$('aiStatus').textContent='در حال تست مدل انتخاب‌شده…';let d=await deployApi('/api/ai/test',{method:'POST',body:JSON.stringify({provider:$('ai_provider').value,model:$('ai_model').value,prompt:$('ai_test_prompt').value})});$('aiStatus').innerHTML='<span class="ok">اتصال موفق: '+esc(d.answer)+'</span>'}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function enrichAI(){if(!confirm('توضیحات حداکثر ۳ محصول ناقص با مدل فعال ساخته شود؟'))return;try{$('aiStatus').textContent='در حال تولید محتوا…';let d=await deployApi('/api/ai/enrich',{method:'POST',body:JSON.stringify({limit:3})});$('aiStatus').innerHTML='<span class="ok">'+d.total+' محصول تکمیل شد.</span>'}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
let aiStatsData=null,aiCandidates=[];
async function loadAIStats(){try{aiStatsData=await deployApi('/api/ai/stats');$('aiStatsCards').innerHTML=`<div class="space-card"><b>${aiStatsData.total}</b><span>کل مدل‌ها</span></div><div class="space-card"><b>${aiStatsData.available}</b><span>سالم</span></div><div class="space-card"><b>${aiStatsData.failed}</b><span>ناموفق</span></div><div class="space-card"><b>${aiStatsData.untested}</b><span>تست‌نشده</span></div><div class="space-card"><b>${aiStatsData.avg_latency||'—'}</b><span>میانگین ms</span></div><div class="space-card"><b>${aiStatsData.tool_calling}</b><span>ابزارپذیر</span></div>`;$('aiStatsBars').innerHTML=`<div class="live-task" style="display:block"><div class="live-task-head"><b>پوشش آزمایش</b><span>${aiStatsData.coverage_pct}٪</span></div><div class="progress-track"><i style="width:${aiStatsData.coverage_pct}%"></i></div><div class="live-task-head"><b>سلامت مدل‌های آزمایش‌شده</b><span>${aiStatsData.health_pct}٪</span></div><div class="progress-track"><i style="width:${aiStatsData.health_pct}%"></i></div></div>`;renderAIModelCatalog()}catch(e){$('aiModelCatalog').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
function renderAIModelCatalog(){let q=($('aiCatalogSearch')?.value||'').toLowerCase(),f=$('aiCatalogFilter')?.value||'all',rows=aiProviders.flatMap(p=>p.models.map(m=>({...m,provider:p.id,provider_name:p.name}))).filter(m=>{let ok=!q||(`${m.provider_name} ${m.name} ${m.id}`).toLowerCase().includes(q);return ok&&(f==='all'||f==='available'&&m.available||f==='failed'&&m.tested&&!m.available||f==='untested'&&!m.tested||f==='free'&&m.free)});rows.sort((a,b)=>(b.testScore||0)-(a.testScore||0));$('aiModelCatalog').innerHTML=rows.map(m=>`<div class="model-row"><div><b>${esc(m.name||m.id)}</b><br><small>${esc(m.provider_name)} · <code>${esc(m.id)}</code></small></div><span class="badge ${m.available?'ok':m.tested?'error':''}">${m.available?'سالم':m.tested?'ناموفق':'تست‌نشده'}</span><span>${m.testScore||0} امتیاز<br><small>${m.latencyMs||0} ms</small></span></div>`).join('')||'<div class="note">مدلی مطابق فیلتر پیدا نشد.</div>'}
async function loadAICandidates(){try{let d=await deployApi('/api/ai/candidates');aiCandidates=d.candidates||[];$('aiCandProvider').innerHTML=aiProviders.map(p=>`<option value="${esc(p.id)}">${esc(p.name)}</option>`).join('');renderCandidateModels();renderAICandidates(d.master||'')}catch(e){$('aiCandidateList').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
function renderCandidateModels(){let p=aiProviders.find(x=>x.id===$('aiCandProvider').value);$('aiCandModel').innerHTML=(p?.models||[]).filter(m=>m.available).map(m=>`<option value="${esc(m.id)}">${esc(m.name||m.id)} · ${m.testScore||0}</option>`).join('')||'<option value="">مدل سالمی نیست</option>'}
function candidateKey(x){return x.provider+'/'+x.model}
function renderAICandidates(master=''){let rows=aiCandidates.map(x=>{let p=aiProviders.find(y=>y.id===x.provider),m=p?.models.find(y=>y.id===x.model);return {...x,provider_name:p?.name||x.provider,model_name:m?.name||x.model,score:m?.testScore||0}});$('aiCandidateList').innerHTML=rows.map((x,i)=>`<div class="candidate-row ${candidateKey(x)===master?'master':''}"><div><b>${candidateKey(x)===master?'★ ':''}${esc(x.model_name)}</b><br><small>${esc(x.provider_name)} · ${x.score} امتیاز</small></div><button class="gray" onclick="aiCandidates.splice(${i},1);renderAICandidates($('aiMasterModel').value)">حذف</button></div>`).join('')||'<div class="note">هنوز کاندیدی انتخاب نشده است.</div>';$('aiMasterModel').innerHTML='<option value="">خودکار — بهترین امتیاز</option>'+rows.map(x=>`<option value="${esc(candidateKey(x))}">${esc(x.model_name)} · ${x.score}</option>`).join('');$('aiMasterModel').value=master||''}
function addAICandidate(){let x={provider:$('aiCandProvider').value,model:$('aiCandModel').value};if(x.provider&&x.model&&!aiCandidates.some(y=>candidateKey(y)===candidateKey(x)))aiCandidates.push(x);renderAICandidates($('aiMasterModel').value)}
function addAllHealthyCandidates(){aiProviders.forEach(p=>p.models.filter(m=>m.available).forEach(m=>{let x={provider:p.id,model:m.id};if(!aiCandidates.some(y=>candidateKey(y)===candidateKey(x)))aiCandidates.push(x)}));renderAICandidates($('aiMasterModel').value)}
async function saveAICandidates(){try{let d=await deployApi('/api/ai/candidates',{method:'POST',body:JSON.stringify({candidates:aiCandidates,master:$('aiMasterModel').value})});aiCandidates=d.candidates||[];renderAICandidates(d.master||'');$('aiStatus').innerHTML='<span class="ok">کاندیدها و مدل مستر ذخیره شدند.</span>'}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
let activeAITestJob='',aiTestJobData=null,aiAutoRunning=false,aiResultPage=1,aiCompareSet=new Set();
function testState(r){return r.status==='ok'?'ok':(r.reply_ok||r.category_ok)?'partial':r.status==='failed'?'failed':'waiting'}
function renderAITest(job){aiTestJobData=job||null;if(!job){activeAITestJob='';$('aiTestSummary').innerHTML='';$('aiTestRows').innerHTML='<div class="note">هنوز صف آزمونی ساخته نشده است.</div>';return}activeAITestJob=job.id;$('aiTestSummary').innerHTML=`<div class="space-card"><b>${job.cursor}/${job.total}</b><span>پیشرفت</span></div><div class="space-card"><b>${job.ok_count}</b><span>هر دو سالم</span></div><div class="space-card"><b>${job.reply_ok}</b><span>پاسخ مشتری</span></div><div class="space-card"><b>${job.category_ok}</b><span>دسته‌بندی</span></div><div class="space-card"><b>${job.failed_count}</b><span>ناقص/ناموفق</span></div>`+`<div class="wide live-task" style="display:block"><div class="live-task-head"><b>${esc(job.status)}</b><span>${job.total?Math.round(job.cursor/job.total*100):0}٪</span></div><div class="progress-track"><i style="width:${job.total?Math.round(job.cursor/job.total*100):0}%"></i></div><div class="live-step">${job.cursor<job.total?'مدل بعدی: '+esc(job.rows[job.cursor]?.model_name||''):'همه مدل‌ها بررسی شدند'}</div></div>`;$('aiTestRows').innerHTML=(job.rows||[]).slice(-12).reverse().map(r=>`<div class="test-row ${testState(r)}"><b>${esc(r.provider_name)} · ${esc(r.model_name)}</b><span class="badge">${testState(r)==='ok'?'✓ هر دو سالم':testState(r)==='partial'?'◐ ناقص':r.status==='failed'?'✕ ناموفق':'در انتظار'}</span><small>${r.latency_ms?r.latency_ms+' ms · ':''}${esc(r.reply||r.category||r.error||'')}</small></div>`).join('');if($('aiTestModal').classList.contains('open'))renderAITestModal()}
async function loadAITestJobs(){try{let d=await deployApi('/api/ai/test/jobs');renderAITest((d.jobs||[])[0])}catch(e){$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
function aiTestBody(){return {per_provider:+$('ai_test_per').value,delay_ms:+$('ai_test_delay').value,only_untested:$('ai_test_only').checked,skip_nonchat:$('ai_test_skip').checked,reply_message:$('ai_reply_sample').value,category_title:$('ai_category_sample').value}}
async function startAITests(openModal=false){let d=await deployApi('/api/ai/test/jobs',{method:'POST',body:JSON.stringify(aiTestBody())});renderAITest(d.job);$('aiStatus').innerHTML='<span class="ok">صف جامع با '+d.job.total+' مدل ساخته شد.</span>';if(openModal)openAITestModal();return d.job}
async function startAutoAITests(){try{aiAutoRunning=false;await startAITests(true);aiAutoRunning=true;$('aiStatus').innerHTML='<span class="progress-pulse">● تست خودکار همه مدل‌ها در حال اجراست؛ صفحه را باز نگه دارید.</span>';await autoAITestLoop()}catch(e){aiAutoRunning=false;$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function autoAITestLoop(){while(aiAutoRunning&&aiTestJobData&&aiTestJobData.status!=='completed'){await processAITests(true);await new Promise(r=>setTimeout(r,180))}if(aiTestJobData?.status==='completed'){$('aiStatus').innerHTML='<span class="ok">✓ آزمایش خودکار همه '+aiTestJobData.total+' مدل کامل شد.</span>';aiAutoRunning=false;await loadAI()}}
function stopAutoAITests(){aiAutoRunning=false;$('aiStatus').textContent='اجرای خودکار متوقف شد؛ نتایج ذخیره هستند و اجرای مرحله‌ای قابل ادامه است.'}
async function processAITests(automatic=false){if(!activeAITestJob){await loadAITestJobs();if(!activeAITestJob)return}try{if(!automatic)$('aiStatus').textContent='در حال اجرای دو آزمون برای هر مدل…';let d=await deployApi('/api/ai/test/jobs/'+encodeURIComponent(activeAITestJob)+'/process',{method:'POST',body:JSON.stringify({batch:automatic?1:+$('ai_test_batch').value})});renderAITest(d.job);if(!automatic)$('aiStatus').innerHTML='<span class="ok">'+d.processed+' مدل آزمایش شد؛ '+d.job.waiting+' مدل باقی مانده.</span>';return d.job}catch(e){aiAutoRunning=false;$('aiStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>';throw e}}
function openAITestModal(){if(!aiTestJobData){loadAITestJobs().then(()=>{if(aiTestJobData)openAITestModal()});return}$('aiTestModal').classList.add('open');document.body.style.overflow='hidden';renderAITestModal()}
function closeAITestModal(){$('aiTestModal').classList.remove('open');if(!$('settingsPanel').classList.contains('open'))document.body.style.overflow=''}
function aiRowKey(r){return String(r.provider||'')+'::'+String(r.model||'')}
function toggleAICompare(key,on){if(on){if(aiCompareSet.size>=4){alert('برای مقایسه دقیق حداکثر ۴ مدل انتخاب کنید.');renderAITestModal();return}aiCompareSet.add(key)}else aiCompareSet.delete(key);renderAICompare()}
function renderAICompare(){let box=$('aiCompareBar'),rows=(aiTestJobData?.rows||[]).filter(r=>aiCompareSet.has(aiRowKey(r)));box.classList.toggle('show',rows.length>0);box.innerHTML=rows.length?`<div class="ai-compare-grid">${rows.map(r=>`<div class="ai-compare-card"><b>${esc(r.model_name)}</b><small>${esc(r.provider_name)} · امتیاز ${r.score||0}</small><small>${r.latency_ms||0} ms · پاسخ ${r.reply_ok?'✓':'✕'} · دسته ${r.category_ok?'✓':'✕'}</small></div>`).join('')}</div><button class="gray" onclick="aiCompareSet.clear();renderAITestModal()">پاک‌کردن</button>`:''}
async function activateAIModel(provider,model){await selectAI(provider,model);$('aiStatus').innerHTML='<span class="ok">مدل انتخاب‌شده فعال شد.</span>'}
function renderAITestModal(){let job=aiTestJobData;if(!job)return;let providerSel=$('aiResultProvider'),current=providerSel.value||'all',all=job.rows||[],providers=[...new Set(all.map(r=>r.provider))];providerSel.innerHTML='<option value="all">همه ارائه‌دهندگان</option>'+providers.map(id=>`<option value="${esc(id)}">${esc((all.find(r=>r.provider===id)||{}).provider_name||id)}</option>`).join('');providerSel.value=providers.includes(current)?current:'all';let q=($('aiResultSearch').value||'').toLowerCase(),filter=$('aiResultFilter').value,cap=$('aiResultCapability').value,sort=$('aiResultSort').value,maxLatency=+$('aiResultLatency').value||0,minScore=+$('aiResultMinScore').value||0;let rows=all.filter(r=>{let st=testState(r),hay=[r.provider_name,r.model_name,r.model,r.reply,r.category,r.error,r.reply_error,r.category_error].join(' ').toLowerCase(),capOk=cap==='all'||cap==='free'&&r.free||cap==='reasoning'&&r.reasoning||cap==='vision'&&r.vision||cap==='tools'&&r.tool_calling,latencyOk=!maxLatency||(r.latency_ms>0&&r.latency_ms<=maxLatency);return (!q||hay.includes(q))&&(providerSel.value==='all'||r.provider===providerSel.value)&&(filter==='all'||st===filter)&&capOk&&latencyOk&&(r.score||0)>=minScore});rows.sort((a,b)=>sort==='fast'?(a.latency_ms||1e12)-(b.latency_ms||1e12):sort==='provider'?String(a.provider_name).localeCompare(String(b.provider_name),'fa'):sort==='model'?String(a.model_name).localeCompare(String(b.model_name),'fa'):sort==='status'?testState(a).localeCompare(testState(b)):(b.score||0)-(a.score||0));let tested=rows.filter(r=>r.latency_ms>0).map(r=>r.latency_ms).sort((a,b)=>a-b),pct=n=>tested.length?tested[Math.min(tested.length-1,Math.floor((tested.length-1)*n))]:0,healthy=rows.filter(r=>testState(r)==='ok').length,best=rows.reduce((a,r)=>(r.score||0)>(a?.score||-1)?r:a,null);let size=+$('aiResultPageSize').value||50,pages=Math.max(1,Math.ceil(rows.length/size));aiResultPage=Math.max(1,Math.min(aiResultPage,pages));let offset=(aiResultPage-1)*size,shown=rows.slice(offset,offset+size);$('aiResultPageInfo').textContent=`صفحه ${aiResultPage} از ${pages} · نمایش ${shown.length} از ${rows.length}`;$('aiModalSubtitle').textContent=`${job.cursor} از ${job.total} مدل · تحلیل پاسخ مشتری و دسته‌بندی`;$('aiModalStats').innerHTML=`<div class="space-card"><b>${rows.length}</b><span>نتیجه فیلترشده</span></div><div class="space-card"><b>${healthy}</b><span>کاملاً سالم · ${rows.length?Math.round(healthy/rows.length*100):0}٪</span></div><div class="space-card"><b>${pct(.5)||'—'}</b><span>میانه latency (ms)</span></div><div class="space-card"><b>${pct(.95)||'—'}</b><span>P95 latency (ms)</span></div><div class="space-card"><b>${esc(best?.model_name||'—')}</b><span>برتر · ${best?.score||0} امتیاز</span></div>`;renderAICompare();$('aiModalRows').innerHTML=shown.map((r,i)=>{let st=testState(r),rank=offset+i+1,key=encodeURIComponent(aiRowKey(r)),reply=r.reply||r.reply_error||r.error||'در انتظار',category=r.category||r.category_error||r.error||'در انتظار',caps=(r.free?'<i class="cap-dot">رایگان</i>':'')+(r.reasoning?'<i class="cap-dot">استدلال</i>':'')+(r.vision?'<i class="cap-dot">تصویر</i>':'')+(r.tool_calling?'<i class="cap-dot">ابزار</i>':'');return `<tr class="row-${st}"><td><input class="ai-select" type="checkbox" ${aiCompareSet.has(aiRowKey(r))?'checked':''} onchange="toggleAICompare(decodeURIComponent('${key}'),this.checked)"></td><td><span class="rank-medal ${rank<=3?'top':''}">${rank<=3?['🥇','🥈','🥉'][rank-1]:rank}</span></td><td><b>${esc(r.provider_name)}</b><br><strong>${esc(r.model_name)}</strong><br><code>${esc(r.model)}</code></td><td><div class="score-box"><div class="score-line"><span>${r.score||0}</span><small>از ۱۰۰</small></div><div class="score-meter"><i style="width:${Math.min(100,r.score||0)}%"></i></div></div>${caps}</td><td class="answer-cell"><span class="${r.reply_ok?'ok':'error'}">${r.reply_ok?'✓ معتبر':'✕ نامعتبر'}</span><div class="answer-preview">${esc(reply)}</div><details><summary>مشاهده پاسخ کامل</summary><div class="answer-full">${esc(reply)}</div></details></td><td class="answer-cell"><span class="${r.category_ok?'ok':'error'}">${r.category_ok?'✓ معتبر':'✕ نامعتبر'}</span><div class="answer-preview">${esc(category)}</div><details><summary>مشاهده خروجی کامل</summary><div class="answer-full">${esc(category)}</div></details></td><td><span class="latency-pill">کل ${r.latency_ms||0} ms</span><br><small>پاسخ ${r.reply_ms||0} · دسته ${r.category_ms||0}</small></td><td><span class="badge ${st==='ok'?'ok':st==='waiting'?'':'error'}">${st==='ok'?'سالم':st==='partial'?'ناقص':st==='failed'?'خطا':'انتظار'}</span><br>${st==='ok'?`<button style="margin-top:5px;padding:5px 8px;min-height:30px;font-size:10px" onclick="activateAIModel(decodeURIComponent('${encodeURIComponent(r.provider||'')}'),decodeURIComponent('${encodeURIComponent(r.model||'')}'))">فعال‌سازی</button>`:''}</td></tr>`}).join('')||'<tr><td colspan="8" class="empty">نتیجه‌ای مطابق فیلترهای حرفه‌ای نیست.</td></tr>'}

async function activateBestAIModel(){let r=aiTestJobData?.recommended;if(!r){$('aiStatus').innerHTML='<span class="error">هنوز مدل موفقی برای پیشنهاد وجود ندارد.</span>';return}await selectAI(r.provider,r.model);$('ai_provider').dataset.selected=r.provider;$('ai_model').dataset.selected=r.model;await loadAI();$('aiStatus').innerHTML='<span class="ok">مدل برتر «'+esc(r.model_name)+'» با امتیاز '+r.score+' فعال شد.</span>'}
function downloadAITestResults(){if(!aiTestJobData)return;let blob=new Blob([JSON.stringify(aiTestJobData,null,2)],{type:'application/json'}),a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download=(aiTestJobData.id||'ai-tests')+'.json';a.click();URL.revokeObjectURL(a.href)}
function renderExtractTask(t){$('extractLiveTask').style.display='block';$('extractTaskTitle').textContent=t.title||'استخراج';$('extractTaskPercent').textContent=(t.progress||0)+'٪';$('extractTaskBar').style.width=(t.progress||0)+'%';$('extractTaskStep').textContent=(t.step||'')+(t.extracted!=null?' · '+t.extracted+' محصول':'')+(t.elapsed_seconds!=null?' · '+shortDuration(t.elapsed_seconds):'')+(t.eta_seconds?' · حدود '+shortDuration(t.eta_seconds)+' باقی‌مانده':'');$('extractTaskDetails').innerHTML=(t.details||[]).map(x=>`<div class="live-detail"><b>${esc(x.at)}</b> · ${esc(x.text)}</div>`).join('');$('extractTaskDetails').scrollTop=$('extractTaskDetails').scrollHeight}
async function runScrape(){const btn=$('runBtn'),old=btn.innerHTML;if(!$('url').value.trim()){$('status').innerHTML='<span class="error">لطفاً آدرس صفحه را وارد کنید.</span>';$('url').focus();return}btn.disabled=true;btn.innerHTML='<span class="spinner"></span>در حال برداشت';$('status').innerHTML='<span class="progress-pulse">● وظیفه استخراج روی سرور اجرا می‌شود؛ جزئیات زنده پایین نمایش داده می‌شود.</span>';try{let started=await api('/api/scrape/start',{method:'POST',body:JSON.stringify(config())}),task;for(;;){let d=await api('/api/tasks/'+encodeURIComponent(started.task.id));task=d.task;renderExtractTask(task);if(['completed','failed','cancelled','interrupted'].includes(task.status))break;await new Promise(r=>setTimeout(r,750))}if(task.status!=='completed')throw Error(task.error||task.step||'استخراج کامل نشد');let d=task.result;products=d.products;renderRows();let c=d.comparison||{};$('status').innerHTML=`<span class="ok">✓ ${d.total} محصول از ${d.pages} صفحه استخراج و روی سرور ذخیره شد</span>\nجدید: ${c.added||0} · تغییرکرده: ${c.changed||0} · حذف‌شده: ${c.removed||0}\nروش: ${esc(d.modes.join(' · '))}`;openTab('results')}catch(e){$('status').innerHTML='<span class="error">✗ عملیات ناموفق بود\n'+esc(e.message)+'</span>'}finally{btn.disabled=false;btn.innerHTML=old}}
function renderRows(){if(!products.length){$('rows').innerHTML='<tr><td class="empty" colspan="6">محصولی پیدا نشد. آدرس، روش محتوا یا سلکتورها را بررسی کنید.</td></tr>';return}$('rows').innerHTML=products.map((p,i)=>`<tr><td data-label="ردیف">${i+1}</td><td data-label="تصویر">${p.image?`<img src="${esc(p.image)}" loading="lazy" alt="">`:''}</td><td data-label="عنوان">${esc(p.title)}</td><td data-label="قیمت" dir="ltr">${esc(p.price)}</td><td data-label="SKU">${esc(p.sku)}</td><td data-label="لینک">${p.link?`<a href="${esc(p.link)}" target="_blank" rel="noopener">مشاهده ↗</a>`:''}</td></tr>`).join('')}
async function saveProfilePrompt(){let name=prompt('نام پروفایل:',activeProfile||'');if(!name)return;let d=await api('/api/profile',{method:'POST',body:JSON.stringify({name,config:config()})});profiles=d.profiles;activeProfile=d.active_profile||name;renderProfiles();updateActiveProfileUI()}
function renderProfiles(){const entries=Object.entries(profiles);$('profileList').innerHTML=entries.map(([n,c])=>`<div class="card profile-card" onclick='loadProfile(${JSON.stringify(n)},true,true)'><b>${n===activeProfile?'● ':''}${esc(n)}</b><br><small dir="ltr">${esc(c.url)}</small><div class="actions"><button class="gray" onclick='event.stopPropagation();delProfile(${JSON.stringify(n)})'>حذف</button></div></div>`).join('')||'<div class="note">هنوز پروفایلی نیست.</div>';$('profileSelect').innerHTML='<option value="">— پروفایل جدید —</option>'+entries.map(([n])=>`<option value="${esc(n)}">${esc(n)}</option>`).join('');$('profileSelect').value=activeProfile||'';if($('dispatchProfile')){$('dispatchProfile').innerHTML=entries.map(([n,c])=>`<option value="${esc(n)}">${esc(n)} — ${c.saved_products?.length||0} محصول</option>`).join('')||'<option value="">پروفایلی موجود نیست</option>';$('dispatchProfile').value=activeProfile&&profiles[activeProfile]?activeProfile:(entries[0]?.[0]||'')}}
function updateActiveProfileUI(){let p=profiles[activeProfile];$('profileSelect').value=activeProfile||'';$('activeProfileBadge').textContent=activeProfile||'پروفایل جدید';$('activeProfileBadge').className='badge '+(activeProfile?'ok':'');$('activeProfileInfo').innerHTML=p?`<b>${esc(activeProfile)}</b> · ${p.saved_products?.length||0} محصول ذخیره‌شده<br><small dir="ltr">${esc(p.url||'')}</small>`:'یک پروفایل را انتخاب کنید؛ انتخاب روی سرور ذخیره می‌شود.'}
async function loadSelectedProfile(){let n=$('profileSelect').value;await loadProfile(n,true,true)}
function deleteSelectedProfile(){const n=$('profileSelect').value;if(!n){alert('یک پروفایل انتخاب کنید');return}delProfile(n)}
async function loadProfile(n,switchTab=true,persist=true){if(persist){let d=await api('/api/profile/active',{method:'POST',body:JSON.stringify({name:n})});activeProfile=d.active_profile||''}else activeProfile=n||'';let p=profiles[activeProfile];if(p){apply(p);if(Array.isArray(p.saved_products)){products=p.saved_products;renderRows()}$('status').innerHTML='<span class="ok">✓ پروفایل «'+esc(activeProfile)+'» خودکار بارگذاری شد؛ '+products.length+' محصول بازیابی شد.</span>'}renderProfiles();updateActiveProfileUI();if(switchTab)document.querySelector('[data-tab="scrape"]').click()}
async function delProfile(n){if(!confirm('پروفایل حذف شود؟'))return;let d=await api('/api/profile/'+encodeURIComponent(n),{method:'DELETE'});profiles=d.profiles;activeProfile=d.active_profile||'';renderProfiles();updateActiveProfileUI()}
async function loadJobs(){try{let d=await api('/api/extract/jobs');$('jobList').innerHTML=(d.jobs||[]).map(j=>`<div class="card"><b>${esc(j.id)}</b><br><small>وضعیت: ${esc(j.status)} · محصول: ${j.total||0} · صفحه بعد: ${j.next_page||'-'}</small><div class="actions">${j.status!=='completed'?`<button onclick="resumeJob('${esc(j.id)}')">ادامه</button>`:''}<button class="gray" onclick="deleteJob('${esc(j.id)}')">حذف</button></div></div>`).join('')||'<div class="note">صف خالی است.</div>'}catch(e){$('jobList').textContent=e.message}}
async function resumeJob(id){try{$('jobList').textContent='در حال ادامه استخراج…';let d=await api('/api/extract/resume/'+encodeURIComponent(id),{method:'POST',body:'{}'});products=d.products||[];renderRows();$('status').innerHTML=`<span class="ok">✓ عملیات ادامه یافت؛ ${d.total||0} محصول</span>`;openTab('results')}catch(e){$('jobList').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deleteJob(id){if(!confirm('این نقطه ادامه حذف شود؟'))return;await api('/api/extract/jobs/'+encodeURIComponent(id),{method:'DELETE'});loadJobs()}
function downloadCSV(){location.href='/api/export.csv'}function downloadJSON(){location.href='/api/export.json'}function downloadXLSX(){location.href='/api/export.xlsx'}let importFile=null,importHeaders=[];
async function previewImport(input){if(!input.files[0])return;importFile=input.files[0];let f=new FormData();f.append('file',importFile);try{let r=await fetch('/api/import/preview',{method:'POST',body:f}),d=await r.json();if(!r.ok||!d.ok)throw Error(d.error);importHeaders=d.headers||[];const fields={title:'عنوان',price:'قیمت',link:'لینک',image:'تصویر اصلی',images:'گالری',sku:'SKU',stock:'موجودی',brand:'برند',category:'دسته‌بندی',weight:'وزن',short_desc:'توضیح کوتاه',long_desc:'توضیح کامل'};$('importMap').innerHTML=Object.entries(fields).map(([k,n])=>`<div><label>${n}</label><select data-map="${k}"><option value="">— استفاده نشود —</option>${importHeaders.map(h=>`<option value="${esc(h)}" ${h.toLowerCase()===k?'selected':''}>${esc(h)}</option>`).join('')}</select></div>`).join('');$('importOptions').style.display='grid';$('applyImportBtn').style.display='inline-block';$('importPreview').style.display='block';$('importPreview').textContent=`${d.total} ردیف؛ ستون‌ها: ${importHeaders.join('، ')}`;}catch(e){$('importPreview').style.display='block';$('importPreview').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function applyImport(){if(!importFile)return;let mapping={};document.querySelectorAll('[data-map]').forEach(x=>mapping[x.dataset.map]=x.value);let options={price_multiplier:+$('impMul').value,price_addition:+$('impAdd').value,title_prefix:$('impPrefix').value,title_suffix:$('impSuffix').value,mode:$('impMode').value};let f=new FormData();f.append('file',importFile);f.append('mapping',JSON.stringify(mapping));f.append('options',JSON.stringify(options));try{$('importPreview').textContent='در حال درون‌ریزی…';let r=await fetch('/api/import/apply',{method:'POST',body:f}),d=await r.json();if(!r.ok||!d.ok)throw Error(d.error);products=d.products||[];renderRows();$('status').innerHTML=`<span class="ok">✓ ${d.total} محصول پس از نگاشت وارد شد</span>`;openTab('results')}catch(e){$('importPreview').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function needDeploySecret(){if(!deploySecret){deploySecret=prompt('رمز مدیریت نصب را وارد کنید:')||'';if(!deploySecret)throw Error('رمز وارد نشد');sessionStorage.setItem('scraperDeployPassword',deploySecret)}return deploySecret}
async function backupSettings(){try{let secret=await needDeploySecret(),r=await fetch('/api/settings/backup',{method:'POST',headers:{'X-Deploy-Password':secret}});if(!r.ok){let d=await r.json();throw Error(d.error)}let blob=await r.blob(),a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='scraper4-settings.json';a.click();URL.revokeObjectURL(a.href);$('backupStatus').innerHTML='<span class="ok">پشتیبان دانلود شد.</span>'}catch(e){$('backupStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function restoreSettings(input){if(!input.files[0]||!confirm('همه تنظیمات و صف‌های فعلی جایگزین شوند؟'))return;try{let secret=await needDeploySecret(),f=new FormData();f.append('file',input.files[0]);let r=await fetch('/api/settings/restore',{method:'POST',headers:{'X-Deploy-Password':secret},body:f}),d=await r.json();if(!r.ok||!d.ok)throw Error(d.error);$('backupStatus').innerHTML='<span class="ok">'+esc(d.message)+'؛ صفحه در حال بارگذاری مجدد است.</span>';setTimeout(()=>location.reload(),900)}catch(e){$('backupStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}finally{input.value=''}}
async function importCSV(input){if(!input.files[0])return;let form=new FormData();form.append('file',input.files[0]);try{let r=await fetch('/api/import.csv',{method:'POST',body:form}),d=await r.json();if(!r.ok||!d.ok)throw Error(d.error||'ورود ناموفق');products=d.products||[];renderRows();$('status').innerHTML=`<span class="ok">✓ ${d.total} محصول از CSV وارد شد</span>`;openTab('results')}catch(e){$('status').innerHTML='<span class="error">'+esc(e.message)+'</span>'}finally{input.value=''}}
function updateGatewayUI(){let mode=$('proxy_mode')?.value||'direct',names={relay:'Cloudflare Worker',direct:'اتصال مستقیم',http:'HTTP Proxy',auto:'تشخیص خودکار'},label=names[mode]||mode;if($('gatewayModeBadge')){$('gatewayModeBadge').textContent=label;$('gatewayModeBadge').className='badge '+(mode==='direct'?'ok':'')}if($('gatewayFlowMode'))$('gatewayFlowMode').textContent=label}
async function diagnoseGateway(){try{$('networkStatus').innerHTML='<span class="spinner"></span> آزمایش دسترسی، Authorization، POST و JSON…';let d=await deployApi('/api/network/diagnose',{method:'POST',body:'{}'}),mark=x=>x?'✓':'✕';$('networkStatus').innerHTML=(d.ready_for_authenticated_api?'<span class="ok">دروازه برای APIهای احراز هویت‌شده آماده است.</span>':'<span class="error">دروازه برای باسلام/AI خصوصی کامل نیست.</span>')+`\nدسترسی ${mark(d.reachable)} · Authorization ${mark(d.authorization)} · POST ${mark(d.method)} · JSON ${mark(d.json)} · مسیر ${esc(d.transport)}`}catch(e){$('networkStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function useMyWorker(){$('proxy').value='https://proxy.fazilat-ma.workers.dev';$('proxy_mode').value='relay';updateGatewayUI();await saveSettings();}
async function saveSettings(woo=false){let body={network:{timeout:+$('timeout').value,gap_ms:+$('gap_ms').value,proxy:$('proxy').value.trim(),proxy_mode:$('proxy_mode').value,worker_key:$('worker_key').value.trim(),verify_tls:$('verify_tls').checked}};if(woo)body.woocommerce={url:$('woo_url').value.trim(),consumer_key:$('woo_ck').value.trim(),consumer_secret:$('woo_cs').value.trim()};await deployApi('/api/settings',{method:'POST',body:JSON.stringify(body)});updateGatewayUI();if($('networkStatus'))$('networkStatus').innerHTML='<span class="ok">دروازه مرکزی ذخیره شد و همه اتصال‌های خروجی از این مسیر استفاده می‌کنند.</span>'}
async function wooTest(){const target=$('wooConnectionStatus')||$('wooStatus');try{target.textContent='در حال تست ووکامرس از دروازه مرکزی '+$('proxy_mode').value+'…';let d=await deployApi('/api/woo/test',{method:'POST',body:'{}'});target.innerHTML='<span class="ok">اتصال ووکامرس از دروازه مرکزی موفق است.</span>'}catch(e){target.innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function wooQueue(){try{let d=await deployApi('/api/woo/queue',{method:'POST',body:JSON.stringify({products,status:$('woo_product_status').value,update_existing:$('woo_update').checked})});$('wooStatus').innerHTML='<span class="ok">صف ساخته شد؛ اکنون «پردازش مرحله بعد» را بزنید.</span>';loadWooJobs()}catch(e){$('wooStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function loadWooJobs(){try{let d=await deployApi('/api/woo/jobs');$('wooJobList').innerHTML=(d.jobs||[]).map(j=>`<div class="card"><b>${esc(j.id)}</b><br><small>${esc(j.status)} · پیشرفت ${j.cursor||0}/${j.total||0} · موفق ${j.sent||0} · خطا ${j.failed||0}</small><div class="progress-track"><i style="width:${j.total?Math.round((j.cursor||0)/j.total*100):0}%"></i></div><div class="actions">${j.status!=='completed'?`<button onclick="processWoo('${esc(j.id)}')">پردازش مرحله بعد</button>`:''}<button class="gray" onclick="deleteWoo('${esc(j.id)}')">حذف</button></div></div>`).join('')||'<div class="note">صفی وجود ندارد.</div>'}catch(e){$('wooStatus').textContent=e.message}}
async function processWoo(id){try{$('wooStatus').textContent='در حال ارسال مرحله…';let d=await deployApi('/api/woo/process/'+encodeURIComponent(id),{method:'POST',body:JSON.stringify({batch:+$('woo_batch').value})});$('wooStatus').innerHTML=`<span class="ok">پیشرفت ${d.job.cursor}/${d.job.total}؛ موفق ${d.job.sent}؛ خطا ${d.job.failed}</span>`;loadWooJobs()}catch(e){$('wooStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deleteWoo(id){if(!confirm('صف حذف شود؟'))return;await deployApi('/api/woo/jobs/'+encodeURIComponent(id),{method:'DELETE'});loadWooJobs()}
async function saveDeploy(){let deploy={repo:$('dep_repo').value.trim(),branch:$('dep_branch').value.trim(),path:$('dep_path').value.trim(),reload_file:$('dep_reload').value.trim(),github_token:$('dep_token').value.trim()};await deployApi('/api/settings',{method:'POST',body:JSON.stringify({deploy})});$('dep_token').value='';$('deployStatus').innerHTML='<span class="ok">تنظیمات نصب ذخیره شد.</span>'}
let fileCurrent='',fileParent='';const formatBytes=n=>{n=Number(n||0);if(n<1024)return n+' B';const u=['KB','MB','GB','TB'];let i=-1;do{n/=1024;i++}while(n>=1024&&i<u.length-1);return n.toFixed(n>=100?0:n>=10?1:2)+' '+u[i]};
async function browseFiles(path=''){try{let secret=await needDeploySecret();$('fileRows').innerHTML='<div class="status">در حال محاسبه اندازه پوشه‌ها…</div>';let r=await fetch('/api/files?path='+encodeURIComponent(path||''),{headers:{'X-Deploy-Password':secret},cache:'no-store'}),d=await r.json();if(!r.ok||!d.ok)throw Error(d.error);fileCurrent=d.current||'';fileParent=d.parent||'';$('filePath').textContent=d.home+(fileCurrent?'/'+fileCurrent:'');$('spaceSummary').innerHTML=`<div class="space-card"><b>${formatBytes(d.account_quota_used??d.account_used)}</b><span>فضای مصرف‌شده حساب${d.account_quota_used==null&&!d.account_complete?' (تقریبی)':''}</span></div><div class="space-card"><b>${formatBytes(d.account_quota_remaining??d.filesystem.free)}</b><span>${d.account_quota_remaining!=null?'فضای باقی‌مانده سهمیه':'فضای آزاد فایل‌سیستم'}</span></div><div class="space-card"><b>${d.account_quota_limit!=null?formatBytes(d.account_quota_limit):d.scanned_files}</b><span>${d.account_quota_limit!=null?'سقف سهمیه حساب':'فایل بررسی‌شده'}</span></div>`;$('quotaInfo').textContent=d.quota||'سامانه سهمیه عدد جداگانه‌ای گزارش نکرد؛ مصرف پوشه حساب و فضای فایل‌سیستم نمایش داده شده است.';$('fileRows').innerHTML=(d.entries||[]).map(e=>`<div class="file-row"><div>${e.directory&&!e.symlink?`<button onclick="browseFiles(decodeURIComponent('${encodeURIComponent(e.path)}'))">📁 ${esc(e.name)}</button>`:`<span>📄 ${esc(e.name)}</span>`}${e.protected?' <span class="badge">محافظت‌شده</span>':''}</div><span class="fsize">${e.complete?'':'≈ '}${formatBytes(e.size)}</span><small>${new Date(e.modified*1000).toLocaleDateString('fa-IR')}</small></div>`).join('')||'<div class="note">این پوشه خالی است.</div>'}catch(e){$('fileRows').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function cleanupAccount(){if(!confirm('فقط cacheها، نصب‌های نیمه‌کاره، مرورگرهای تکراری و بسته‌های بلااستفاده پاک شوند؟ فایل‌های سیستمی، برنامه، تنظیمات و فایل‌های شخصی حفظ می‌شوند.'))return;try{$('deployStatus').textContent='در حال پاکسازی امن فضای حساب…';let d=await deployApi('/api/deploy/cleanup',{method:'POST',body:'{}'});$('deployStatus').innerHTML='<span class="ok">'+esc(d.message)+'</span>\n'+esc((d.removed||[]).join('\n'))}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function installDeps(){if(!confirm('کتابخانه‌های استخراج و مرورگر Chromium نصب/به‌روزرسانی شوند؟ ممکن است چند دقیقه طول بکشد.'))return;try{$('deployStatus').textContent='در حال نصب Playwright، Chromium و کتابخانه‌های استخراج…';let d=await deployApi('/api/deploy/dependencies',{method:'POST',body:'{}'});$('deployStatus').innerHTML='<span class="ok">'+esc(d.message)+'</span>'+(!d.browser_installed&&d.warning?'\n'+esc(d.warning):'')+(d.browser_installed?'\nمرورگر اکنون آماده استخراج است.':'')}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deployCheck(){try{$('deployStatus').textContent='در حال بررسی GitHub…';let d=await deployApi('/api/deploy/check',{method:'POST',body:'{}'});$('deployStatus').innerHTML=`نسخه جاری: ${esc(d.version)}\nSHA محلی: ${esc(d.local_sha)}\nSHA راه دور: ${esc(d.remote_sha)}\n${d.update_available?'<span class="ok">نسخه متفاوت آماده نصب است.</span>':'نسخه محلی و راه دور یکسان‌اند.'}`;}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deployRun(){if(!confirm('فایل جاری جایگزین و نسخه قبلی در .bak ذخیره شود؟'))return;try{$('deployStatus').textContent='در حال دانلود، اعتبارسنجی و نصب…';let d=await deployApi('/api/deploy/run',{method:'POST',body:'{}'});$('deployStatus').innerHTML='<span class="ok">'+esc(d.message)+' — نسخه '+esc(d.version)+'</span>\n'+(d.reload_requested?'درخواست reload فرستاده شد.':'در صورت تنظیم‌نبودن WSGI، از تب Web دکمه Reload را بزنید.');}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deployRollback(){if(!confirm('نسخه scraper4.py.bak بازیابی شود؟'))return;try{let d=await deployApi('/api/deploy/rollback',{method:'POST',body:'{}'});$('deployStatus').innerHTML='<span class="ok">'+esc(d.message)+' — نسخه '+esc(d.version)+'</span>';}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function watchBuild(){try{let r=await fetch('/health',{cache:'no-store'}),d=await r.json();if(currentBuild&&d.build&&d.build!==currentBuild){document.body.style.opacity='.65';location.reload()}}catch(e){}}
init().then(()=>{setInterval(watchBuild,30000);setInterval(loadTaskTopSummary,10000)}).catch(e=>$('status').textContent=e.message);
</script></body></html>'''


if __name__ == "__main__":
    # Local development only. PythonAnywhere imports `app` as `application`.
    app.run(host="0.0.0.0", port=int(os.environ.get("PORT", "8000")), debug=False)
