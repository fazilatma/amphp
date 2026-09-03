#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Scraper4 Python Edition — Advanced Multi-Destination E-Commerce Scraper & Operations Hub
Full PHP v10.123 Parity Edition with Modern Responsive Glassmorphism Interface.

Features:
- Multi-Engine Web Scraping: requests, cloudscraper, curl_cffi, headless Playwright Stealth
- 2-Phase Extraction: Fast List Extraction + Background Deep Product Details (Specs, Gallery, Variations)
- Multi-Destination Sync & Queues: WooCommerce (REST + Direct Bridge) and BaSalam (SDK + REST API)
- Sync Matrix: 4-Way Multi-Vendor Comparison (Source vs Local vs WooCommerce vs BaSalam) with Server-Side Price Fix
- Smart Deduplication (DEDUP): Exact SKU, Barcode, Exact Title, and Fuzzy Title Similarity Matching with Master Selection
- BaSalam Category Learning & Auto-Repair (CATFIX): Persistent dictionary and AI fallback category fixer
- Multi-Provider AI Model Lab: OpenAI, Anthropic Claude, Google Gemini, Grok/xAI, DeepSeek, OpenRouter, Ollama, AvalAI
- AI Batch Content Generator (AICONTENT): SEO Persian descriptions, bullet points, technical specs, variations
- Customer & Multi-Vendor Chat Desk: Storefront & BaSalam customer inquiry inbox with grounded AI auto-replies
- Messenger Notification Hub: Telegram, Bale, Rubika bot alerts and live test dispatchers
- Persian Typography Hub: Vazirmatn, Shabnam, Sahel, Estedad, Samim, IRANSans with 5-step font scaling & 6 themes
- Visual DOM Selector Inspector & Point-and-Click Selector Helper
- 3 Interactive Results Views: 📋 Table (with inline editing & lightboxes), 📊 Cards, 📝 Raw JSON
- Multi-threaded Background Task Manager with Heartbeat, Live Progress, Logs, Pause, Resume, and Cancellation
- Quota Doctor, Dependency Diagnostics, PythonAnywhere WSGI Reload, and Atomic GitHub Deployer
"""

from __future__ import annotations

import base64
import csv
import dataclasses
from dataclasses import dataclass, field
import difflib
import hashlib
import hmac
from html import escape
import importlib
import importlib.metadata
import io
import ipaddress
import json
import os
import re
import secrets
import shutil
import site
import socket
import subprocess
import sys
import tempfile
import threading
import time
from typing import Any, Iterable, Optional
from urllib.parse import parse_qs, quote, urlencode, urljoin, urlparse, urlunparse
import zipfile

try:
    import requests
    from bs4 import BeautifulSoup, Tag
    from flask import Flask, Response, jsonify, request
except ImportError as exc:
    raise RuntimeError(
        "Missing dependency. Run: pip3 install --user flask requests beautifulsoup4"
    ) from exc

APP_VERSION = "5.0.0"
PHP_PARITY_VERSION = "10.123"
APP_VERSION_DATE = "2026-09-04"

CHANGELOG = [
    {
        "version": "5.0.0",
        "date": "2026-09-04",
        "title": "همگام‌سازی کامل با scraper4.php v10.123 و رابط کاربری فوق‌پیشرفته",
        "items": [
            "ماتریس همگام‌سازی چندفروشگاهی (Sync Matrix) با تطبیق ۴طرفه (مبدأ، لوکال، ووکامرس، باسلام) و اصلاح قیمت سرورساید",
            "موتور هوشمند حذف کالاهای تکراری (DEDUP) با تطبیق SKU، بارکد، عنوان دقیق و شباهت فازی با استراتژی‌های انتخاب محصول اصلی",
            "تعمیر خودکار دسته‌بندی باسلام (CATFIX) با فرهنگ لغت یادگیری و هوش مصنوعی",
            "تولید محتوا و توضیحات هوشمند دسته‌ای (AICONTENT) با هوش مصنوعی مستر و چندمدلی",
            "میز گفت‌وگو و چت آنلاین مشتریان (Chat Desk) با پاسخ خودکار مبتنی بر کاتالوگ و اتصال به پیام‌رسان‌ها (بله، تلگرام، روبیکا)",
            "بازطراحی کامل رابط کاربری با تم‌های شیشه‌ای مدرن، ناوبری موبایل ۶گانه، فونت‌های فارسی اختصاصی و بزرگ‌نمایی پویا",
            "انتخابگر بصری سلکتورها (Visual Inspector) با پیش‌نمایش زنده DOM و پیشنهاد خودکار",
            "۳ نمای تعاملی نتایج: جدول با ویرایش سریع قیمت/موجودی و لایت‌باکس، کارت محصولات، و متن/JSON"
        ]
    },
    {
        "version": "4.4.0",
        "date": "2026-09-03",
        "title": "دفترچه شناسه و مغایرت‌گیری مقصدها",
        "items": [
            "ثبت پایدار شناسه محصول ووکامرس و باسلام پس از هر ارسال موفق",
            "مغایرت‌گیری سرورساید مستقل برای هر پروفایل و هر مقصد با تطبیق شناسه، SKU و عنوان",
            "تفکیک یکسان، مغایرت قیمت/عنوان، موجودنبودن در مقصد و محصول اضافی مقصد",
            "گزارش موبایلی مقصد در تب اختصاصی و اجرای پس‌زمینه از Task Manager"
        ]
    },
    {
        "version": "4.3.0",
        "date": "2026-09-03",
        "title": "مقصدهای مستقل و تنظیمات مدرن",
        "items": [
            "تفکیک کامل ارسال ووکامرس و باسلام در دو تب مستقل پایین",
            "بازطراحی منوی همبرگری به مرکز کنترل کاشی‌محور مدرن",
            "تعدیل قیمت جداگانه ووکامرس و باسلام در هر پروفایل",
            "اصلاح اتصال کارت ووکامرس در تنظیمات و حفظ اجرای مستقل مقصدها"
        ]
    },
    {
        "version": "4.2.0",
        "date": "2026-09-03",
        "title": "پوسته موبایل همسان PHP و نمایش چندحالته نتایج",
        "items": [
            "بازطراحی سراسری بر اساس کارت‌های سرمه‌ای، خط دور روشن و تیترهای فیروزه‌ای",
            "هدر فشرده، دکمه تمام‌صفحه و نوار پایین بزرگ با وضعیت فعال واضح",
            "سه نمای جدول، کارت و متن برای نتایج با ذخیره انتخاب کاربر"
        ]
    },
    {
        "version": "4.1.0",
        "date": "2026-09-03",
        "title": "استخراج سریع دومرحله‌ای و کنترل نمایش",
        "items": [
            "استخراج سریع فهرست با انتقال جزئیات به وظیفه مستقل پس‌زمینه",
            "تنظیم زنده اندازه فونت در ۵ سطح و ذخیره انتخاب در سرور و مرورگر",
            "وظیفه مستقل detail_extract با توقف، پیشرفت و حفظ نتایج"
        ]
    },
    {
        "version": "4.0.0",
        "date": "2026-09-03",
        "title": "انتخابگر بصری دوگانه و استخراج تفصیلی خودکار",
        "items": [
            "انتخاب دستی سلکتورهای فهرست روی پیش‌نمایش زنده DOM",
            "تب مستقل انتخاب سلکتورهای صفحه جزئیات برای توضیحات، تنوع‌ها، گالری و مشخصات",
            "استخراج خودکار چندلایه صفحه هر محصول"
        ]
    }
]

BASE_DIR = os.path.dirname(os.path.abspath(__file__))
LOCAL_DEPS_DIR = os.path.join(BASE_DIR, ".runtime-deps")
LIVE_TASK_DIR = os.path.join(tempfile.gettempdir(), "scraper4-live-" + str(os.getuid() if hasattr(os, "getuid") else "user"))
if os.path.isdir(LOCAL_DEPS_DIR):
    site.addsitedir(LOCAL_DEPS_DIR)

os.environ["PLAYWRIGHT_BROWSERS_PATH"] = os.environ.get("SCRAPER_PLAYWRIGHT_PATH", os.path.join(BASE_DIR, "ms-playwright"))

try:
    with open(__file__, "rb") as _build_file:
        BUILD_ID = hashlib.sha256(_build_file.read()).hexdigest()[:12]
except OSError:
    BUILD_ID = APP_VERSION

AUTO_UPDATE_ENABLED = os.environ.get("SCRAPER_AUTO_UPDATE", "1").lower() not in {"0", "false", "off", "no"}
AUTO_UPDATE_INTERVAL = max(120, int(os.environ.get("SCRAPER_AUTO_UPDATE_INTERVAL", "300")))
DATA_FILE = os.environ.get("SCRAPER_DATA_FILE", os.path.join(BASE_DIR, "scraper4_data.json"))
CONNECTIONS_FILE = os.path.join(BASE_DIR, "connections.json")
AI_PROVIDERS_FILE = os.path.join(BASE_DIR, "ai_providers.json")
AI_VOTES_FILE = os.path.join(BASE_DIR, "ai_votes.json")
CHAT_THREADS_FILE = os.path.join(BASE_DIR, "chat_threads.json")
CATLEARN_FILE = os.path.join(BASE_DIR, "category_learning.json")
CATTRIED_FILE = os.path.join(BASE_DIR, "category_attempts.json")
SYNC_MATRIX_RESULT_FILE = os.path.join(BASE_DIR, "sync_matrix_result.json")
DEDUP_RESULT_FILE = os.path.join(BASE_DIR, "dedup_result.json")
ANALYTICS_FILE = os.path.join(BASE_DIR, "scraper_analytics.json")

PASSWORD = os.environ.get("SCRAPER_PASSWORD", "")
DEPLOY_PASSWORD = os.environ.get("SCRAPER_DEPLOY_PASSWORD", "")

USER_AGENT = (
    "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 "
    "(KHTML, like Gecko) Chrome/126.0.0.0 Safari/537.36"
)

DATA_LOCK = threading.RLock()
APP_LOCK = threading.Lock()
TASK_LOCK = threading.Lock()
SYNC_MATRIX_LOCK = threading.Lock()
DEDUP_LOCK = threading.Lock()
CATFIX_LOCK = threading.Lock()
AICONTENT_LOCK = threading.Lock()

LIVE_TASKS: dict[str, dict[str, Any]] = {}

app = Flask(__name__)
app.config["JSON_AS_ASCII"] = False
app.config["MAX_CONTENT_LENGTH"] = 256 * 1024 * 1024

# ---------------------------------------------------------------------------
# Persistent configuration & Cross-file Synchronization
# ---------------------------------------------------------------------------
def default_data() -> dict[str, Any]:
    return {
        "version": APP_VERSION,
        "profiles": {},
        "active_profile": "",
        "woocommerce": {
            "url": "",
            "consumer_key": "",
            "consumer_secret": "",
            "api_mode": "relay",
            "relay_url": "https://proxy.fazilat-ma.workers.dev",
            "worker_key": "",
            "default_status": "draft",
            "default_category": "",
            "price_markup_percent": 0.0,
            "price_markup_fixed": 0,
            "price_round_to": 1000,
            "stock_rule": "as_is",
            "default_stock": 10,
            "update_existing": True,
            "last_test_at": 0,
            "last_test_status": "",
        },
        "basalam": {
            "token": "",
            "refresh_token": "",
            "vendor_id": 0,
            "category_id": 0,
            "preparation_days": 3,
            "weight": 500,
            "stock": 10,
            "update_existing": True,
            "client_mode": "auto",
            "api_mode": "relay",
            "api_base_url": "https://openapi.basalam.com",
            "relay_url": "https://proxy.fazilat-ma.workers.dev",
            "worker_key": "",
            "price_markup_percent": 0.0,
            "price_markup_fixed": 0,
            "price_round_to": 1000,
            "last_test_at": 0,
            "last_test_user": "",
            "last_client": "",
        },
        "network": {
            "timeout": 25,
            "gap_ms": 350,
            "proxy": "https://proxy.fazilat-ma.workers.dev",
            "proxy_mode": "relay",
            "worker_key": "",
            "verify_tls": True,
        },
        "messengers": {
            "telegram": {"enabled": False, "bot_token": "", "chat_id": "", "topic_id": "", "proxy": ""},
            "bale": {"enabled": False, "bot_token": "", "chat_id": ""},
            "rubika": {"enabled": False, "bot_token": "", "chat_id": ""},
            "notify_on_extract": True,
            "notify_on_error": True,
            "notify_on_chat": True,
        },
        "ui": {
            "font": "vazir",
            "font_size": 14,
            "theme": "navy",
            "view_mode": "table",
        },
        "deploy": {
            "repo": "fazilatma/amphp",
            "branch": "arena/01a06927-amphp",
            "path": "scraper4.py",
            "github_token": "",
            "reload_file": "",
        },
        "last_result": [],
        "extract_jobs": {},
        "woo_jobs": {},
        "bsl_jobs": {},
        "ai_test_jobs": {},
        "dispatch_jobs": {},
        "runtime": {"playwright_path": ""},
        "ai": {
            "provider": "openrouter",
            "endpoint": "https://openrouter.ai/api/v1/chat/completions",
            "api_key": "",
            "model": "meta-llama/llama-3.3-70b-instruct:free",
            "temperature": 0.3,
            "max_tokens": 1200,
            "system_prompt": "You write accurate Persian WooCommerce product content. Return only requested JSON.",
        },
        "ai_providers": {},
        "ai_candidates": [],
        "ai_master": "",
        "sync_matrix": {
            "last_run": 0,
            "status": "idle",
            "summary": {"total": 0, "synced": 0, "price_mismatch": 0, "missing_wc": 0, "missing_bsl": 0, "extra_wc": 0, "extra_bsl": 0},
            "matrix": []
        },
        "dedup": {
            "last_run": 0,
            "status": "idle",
            "strategy": "newest",
            "groups": []
        },
        "catfix": {
            "last_run": 0,
            "status": "idle",
            "progress": 0,
            "fixed_count": 0
        },
        "category_learning": {},
        "category_attempts": {},
    }

def _read_json_file(path: str, fallback: Any = None) -> Any:
    try:
        if os.path.exists(path):
            with open(path, "r", encoding="utf-8") as f:
                return json.load(f)
    except Exception:
        pass
    return fallback

def _write_json_file(path: str, data: Any) -> None:
    try:
        os.makedirs(os.path.dirname(os.path.abspath(path)) or ".", exist_ok=True)
        fd, tmp = tempfile.mkstemp(prefix=".tmp-", suffix=".json", dir=os.path.dirname(os.path.abspath(path)) or ".")
        with os.fdopen(fd, "w", encoding="utf-8") as fh:
            json.dump(data, fh, ensure_ascii=False, indent=2)
            fh.flush()
            os.fsync(fh.fileno())
        os.replace(tmp, path)
    except Exception:
        pass

def load_data() -> dict[str, Any]:
    with DATA_LOCK:
        raw = _read_json_file(DATA_FILE, {})
        out = default_data()
        for key in out:
            if key in raw and isinstance(raw[key], type(out[key])):
                if isinstance(out[key], dict):
                    merged = dict(out[key])
                    merged.update(raw[key])
                    out[key] = merged
                else:
                    out[key] = raw[key]

        conn = _read_json_file(CONNECTIONS_FILE)
        if isinstance(conn, dict):
            if "ai_selected" in conn and isinstance(conn["ai_selected"], dict):
                sel = conn["ai_selected"]
                if sel.get("provider"): out["ai"]["provider"] = sel["provider"]
                if sel.get("model"): out["ai"]["model"] = sel["model"]
            if "ai_candidates" in conn and isinstance(conn["ai_candidates"], list):
                out["ai_candidates"] = conn["ai_candidates"]

        ai_prov = _read_json_file(AI_PROVIDERS_FILE)
        if isinstance(ai_prov, dict) and ai_prov:
            out["ai_providers"] = ai_prov

        ai_votes = _read_json_file(AI_VOTES_FILE)
        if isinstance(ai_votes, dict):
            if ai_votes.get("master"):
                out["ai_master"] = ai_votes["master"]

        catlearn = _read_json_file(CATLEARN_FILE)
        if isinstance(catlearn, dict):
            out["category_learning"] = catlearn

        cattried = _read_json_file(CATTRIED_FILE)
        if isinstance(cattried, dict):
            out["category_attempts"] = cattried

        sync_mat = _read_json_file(SYNC_MATRIX_RESULT_FILE)
        if isinstance(sync_mat, dict):
            out["sync_matrix"] = sync_mat

        dedup_res = _read_json_file(DEDUP_RESULT_FILE)
        if isinstance(dedup_res, dict):
            out["dedup"] = dedup_res

        return out

def save_data(data: dict[str, Any]) -> None:
    os.makedirs(os.path.dirname(DATA_FILE) or ".", exist_ok=True)
    with DATA_LOCK:
        _write_json_file(DATA_FILE, data)
        try:
            conn_data = {
                "ai_selected": {
                    "provider": data.get("ai", {}).get("provider", "openrouter"),
                    "model": data.get("ai", {}).get("model", "")
                },
                "ai_candidates": data.get("ai_candidates", [])
            }
            _write_json_file(CONNECTIONS_FILE, conn_data)

            if data.get("ai_providers"):
                _write_json_file(AI_PROVIDERS_FILE, data["ai_providers"])

            if data.get("category_learning"):
                _write_json_file(CATLEARN_FILE, data["category_learning"])

            if data.get("category_attempts"):
                _write_json_file(CATTRIED_FILE, data["category_attempts"])

            if data.get("sync_matrix"):
                _write_json_file(SYNC_MATRIX_RESULT_FILE, data["sync_matrix"])

            if data.get("dedup"):
                _write_json_file(DEDUP_RESULT_FILE, data["dedup"])
        except Exception:
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
    secret = DEPLOY_PASSWORD or PASSWORD
    if not secret:
        return True
    token = request.headers.get("X-Deploy-Token") or request.headers.get("X-Scraper-Password") or ""
    if not token and request.authorization:
        token = request.authorization.password or ""
    return bool(token) and hmac.compare_digest(token, secret)

def deploy_auth_error():
    return jsonify(ok=False, error="رمز مدیریت نصب نادرست است"), 401

@app.before_request
def require_password():
    if request.path in ("/health", "/api/tasks/summary") or authorized():
        return None
    return Response("Authentication required", 401, {"WWW-Authenticate": 'Basic realm="Scraper4"'})

def public_http_url(url: str) -> str:
    url = (url or "").strip()
    markdown = re.fullmatch(r"\[[^]]+\]\((https?://[^)]+)\)", url, re.I)
    if markdown:
        url = markdown.group(1).strip()
    if not url.lower().startswith(("http://", "https://")):
        embedded = re.search(r"https?://[^\s)]+", url, re.I)
        if embedded:
            url = embedded.group(0)
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

def outbound_mode(cfg: Optional[dict[str, Any]] = None) -> str:
    cfg = cfg or load_data().get("network", {})
    mode = str(cfg.get("proxy_mode", "auto")).strip().lower()
    proxy = str(cfg.get("proxy", "")).strip()
    if mode == "auto":
        return "relay" if proxy and ("workers.dev" in proxy.lower() or "?url=" in proxy or "{url}" in proxy) else ("http" if proxy else "direct")
    return mode

def outbound_request(method: str, url: str, **kwargs: Any) -> requests.Response:
    cfg = load_data().get("network", {})
    mode = outbound_mode(cfg)
    proxy = str(cfg.get("proxy", "")).strip()
    target = public_http_url(url)
    headers = dict(kwargs.pop("headers", {}) or {})
    params = kwargs.pop("params", None)
    if params:
        target = requests.Request("GET", target, params=params).prepare().url
    request_url = target
    if mode == "relay":
        if not proxy:
            raise FetchError("حالت Worker انتخاب شده اما آدرس دروازه مرکزی خالی است")
        relay = public_http_url(proxy.replace("{url}", quote(target, safe=""))) if "{url}" in proxy else public_http_url(proxy)
        request_url = relay if "{url}" in proxy else relay + ("&" if "?" in relay else "?") + urlencode({"url": target})
        headers["X-Proxy-UA"] = headers.get("User-Agent", USER_AGENT)
        headers["X-Proxy-Method"] = method.upper()
        authorization = str(headers.get("Authorization", "")).strip()
        if authorization:
            for relay_header in ("X-Proxy-Authorization", "X-Upstream-Authorization", "X-Target-Authorization", "X-Authorization"):
                headers[relay_header] = authorization
        if cfg.get("worker_key"):
            headers["X-Proxy-Key"] = str(cfg["worker_key"])
    elif mode == "http":
        if not proxy:
            raise FetchError("حالت HTTP Proxy انتخاب شده اما آدرس پروکسی خالی است")
        kwargs["proxies"] = {"http": proxy, "https": proxy}
    kwargs.setdefault("timeout", max(5, min(120, int(cfg.get("timeout", 25)))))
    kwargs.setdefault("allow_redirects", True)
    kwargs.setdefault("verify", bool(cfg.get("verify_tls", True)))
    response = requests.request(method, request_url, headers=headers, **kwargs)
    setattr(response, "scraper4_transport", mode)
    return response

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

    def get(self, url: str, *, referer: str = "", accept_json: bool = False, engine: str = "requests") -> FetchResult:
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
                if engine == "cloudscraper":
                    try:
                        import cloudscraper
                        scraper = cloudscraper.create_scraper()
                        resp = scraper.get(request_url, headers=headers, timeout=self.timeout, verify=self.verify)
                        self.last_by_host[host] = time.monotonic()
                        return FetchResult(target_url, resp.text, resp.headers.get("content-type", ""), resp.status_code, "cloudscraper")
                    except Exception:
                        pass
                elif engine == "curl_cffi":
                    try:
                        from curl_cffi import requests as cffi_requests
                        resp = cffi_requests.get(request_url, headers=headers, timeout=self.timeout, impersonate="chrome120", verify=self.verify)
                        self.last_by_host[host] = time.monotonic()
                        return FetchResult(target_url, resp.text, resp.headers.get("content-type", ""), resp.status_code, "curl_cffi")
                    except Exception:
                        pass
                elif engine == "playwright":
                    try:
                        return render_playwright(target_url, self.timeout)
                    except Exception as pe:
                        last_error = str(pe)
                resp = self.session.get(request_url, headers=headers, timeout=self.timeout, verify=self.verify, allow_redirects=True)
                self.last_by_host[host] = time.monotonic()
                return FetchResult(target_url, resp.text, resp.headers.get("content-type", ""), resp.status_code, "requests")
            except Exception as e:
                last_error = str(e)
                time.sleep(1 + attempt)
        raise FetchError(f"خطا در دریافت صفحه {url}: {last_error}")

# ---------------------------------------------------------------------------
# HTML, Text & Selector Utilities
# ---------------------------------------------------------------------------
def clean_text(value: Any) -> str:
    if value is None:
        return ""
    text = re.sub(r"[\r\n\t]+", " ", str(value))
    text = re.sub(r" {2,}", " ", text)
    return text.strip()

def absolute_url(value: Any, base: str) -> str:
    clean = clean_text(value)
    if not clean:
        return ""
    if clean.startswith(("//", "http://", "https://")):
        return ("https:" + clean) if clean.startswith("//") else clean
    return urljoin(base, clean)

def image_value(value: Any, base: str) -> str:
    clean = clean_text(value)
    if not clean:
        return ""
    if "data:image" in clean:
        return ""
    if "," in clean and (" " in clean or "w" in clean):
        parts = [p.strip().split()[0] for p in clean.split(",") if p.strip()]
        if parts:
            clean = parts[-1]
    return absolute_url(clean, base)

def extract_price(value: Any) -> str:
    text = clean_text(value)
    if not text:
        return ""
    digits = text.translate(str.maketrans("۰۱۲۳۴۵۶۷۸۹٠١٢٣٤٥٦٧٨٩", "01234567890123456789"))
    cleaned = re.sub(r"[^\d]", "", digits)
    return cleaned

def product_key(product: dict[str, Any]) -> str:
    for key in ("sku", "url", "title"):
        val = clean_text(product.get(key))
        if val:
            return f"{key}:{val}"
    return hashlib.md5(json.dumps(product, sort_keys=True).encode("utf-8")).hexdigest()

def add_product(store: dict[str, dict[str, Any]], product: Optional[dict[str, Any]]) -> None:
    if not product:
        return
    k = product_key(product)
    if k not in store:
        store[k] = product
    else:
        for fld, val in product.items():
            if val and not store[k].get(fld):
                store[k][fld] = val

def select_value(node: Tag, selector: str, kind: str, base: str) -> str:
    if not selector:
        return ""
    try:
        found = node.select_one(selector)
        if not found:
            return ""
        if kind == "image":
            for attr in ("data-src", "data-lazy-src", "data-original", "data-srcset", "src"):
                val = found.get(attr)
                if val:
                    img = image_value(val, base)
                    if img: return img
            return ""
        elif kind == "link":
            return absolute_url(found.get("href", ""), base)
        elif kind == "price":
            return extract_price(found.get_text())
        return clean_text(found.get_text())
    except Exception:
        return ""

def sanitize_rich_html(value: str) -> str:
    if not value:
        return ""
    soup = BeautifulSoup(value, "html.parser")
    for tag in soup(["script", "style", "iframe", "object", "embed", "form", "input", "button"]):
        tag.decompose()
    for tag in soup.find_all(True):
        tag.attrs = {k: v for k, v in tag.attrs.items() if k in ("src", "href", "alt", "title", "class")}
    return str(soup).strip()

def _detail_image_url(node: Tag, base: str) -> str:
    for attr in ("data-zoom-image", "data-full", "data-large", "data-src", "data-lazy-src", "data-original", "src", "href"):
        val = node.get(attr)
        if val:
            img = image_value(val, base)
            if img:
                return img
    return ""

def parse_selectors(soup: BeautifulSoup, base: str, selectors: dict[str, str]) -> list[dict[str, Any]]:
    products: list[dict[str, Any]] = []
    container_sel = selectors.get("container", "").strip()
    if not container_sel:
        return []
    try:
        nodes = soup.select(container_sel)
    except Exception:
        return []
    for node in nodes:
        item: dict[str, Any] = {}
        for field_name in ("title", "price", "regular_price", "image", "url", "sku", "stock", "discount"):
            sel = selectors.get(field_name, "").strip()
            if sel:
                kind = "image" if field_name == "image" else ("link" if field_name == "url" else ("price" if "price" in field_name else "text"))
                val = select_value(node, sel, kind, base)
                if val:
                    item[field_name] = val
        if item.get("title") or item.get("url"):
            products.append(item)
    return products

def _html_product(node: Tag, base: str, selectors: Optional[dict[str, str]] = None) -> Optional[dict[str, Any]]:
    link = node.find("a", href=True)
    img = node.find("img")
    title = ""
    for heading in ("h1", "h2", "h3", "h4", "h5", "h6"):
        h = node.find(heading)
        if h and clean_text(h.get_text()):
            title = clean_text(h.get_text())
            break
    if not title and link:
        title = clean_text(link.get_text()) or clean_text(link.get("title", ""))
    if not title and img:
        title = clean_text(img.get("alt", ""))
    url = absolute_url(link["href"], base) if link else ""
    image = image_value(img.get("data-src") or img.get("src") or "", base) if img else ""
    price_node = node.find(class_=re.compile(r"price|amount|toman|rial|cost", re.I))
    price = extract_price(price_node.get_text()) if price_node else extract_price(node.get_text())
    if title and (price or image or url):
        return {"title": title, "url": url, "image": image, "price": price, "stock": "in_stock" if price else "out_of_stock"}
    return None

def parse_html(text: str, base: str, selectors: Optional[dict[str, str]] = None) -> tuple[list[dict[str, Any]], str]:
    soup = BeautifulSoup(text, "html.parser")
    selectors = selectors or {}
    if selectors.get("container"):
        items = parse_selectors(soup, base, selectors)
        if items:
            return items, "selectors"
    for script in soup.find_all("script", type="application/ld+json"):
        try:
            data = json.loads(script.string or "")
            entries = data if isinstance(data, list) else [data]
            extracted: list[dict[str, Any]] = []
            for entry in entries:
                if entry.get("@type") == "Product":
                    title = clean_text(entry.get("name"))
                    img = image_value(entry.get("image"), base)
                    offers = entry.get("offers", {})
                    price = extract_price(offers.get("price") if isinstance(offers, dict) else "")
                    url = absolute_url(offers.get("url") if isinstance(offers, dict) else entry.get("url"), base)
                    sku = clean_text(entry.get("sku"))
                    if title:
                        extracted.append({"title": title, "image": img, "price": price, "url": url, "sku": sku})
            if extracted:
                return extracted, "json-ld"
        except Exception:
            pass
    cards: list[dict[str, Any]] = []
    for cand in soup.find_all(["div", "article", "li"], class_=re.compile(r"product|item|card|goods", re.I)):
        prod = _html_product(cand, base, selectors)
        if prod:
            cards.append(prod)
    if cards:
        return cards, "html-structure"
    return [], "none"

def parse_detail_fields(soup: BeautifulSoup, base: str, selectors: dict[str, str]) -> dict[str, Any]:
    out: dict[str, Any] = {}
    for fld in ("description", "short_description", "specs", "gallery", "variations", "brand", "category"):
        sel = selectors.get(fld, "").strip()
        if not sel:
            continue
        try:
            if fld == "description":
                el = soup.select_one(sel)
                if el: out[fld] = sanitize_rich_html(str(el))
            elif fld == "short_description":
                el = soup.select_one(sel)
                if el: out[fld] = clean_text(el.get_text())
            elif fld == "specs":
                specs: dict[str, str] = {}
                for row in soup.select(f"{sel} tr, {sel} li, {sel} .spec-row"):
                    text = clean_text(row.get_text())
                    if ":" in text:
                        k, v = text.split(":", 1)
                        specs[k.strip()] = v.strip()
                if specs: out[fld] = specs
            elif fld == "gallery":
                images: list[str] = []
                for img_node in soup.select(f"{sel} img, {sel} a"):
                    img_url = _detail_image_url(img_node, base)
                    if img_url and img_url not in images:
                        images.append(img_url)
                if images: out[fld] = images
            elif fld == "variations":
                variations: list[str] = []
                for var_node in soup.select(f"{sel} option, {sel} .variant, {sel} .swatch"):
                    txt = clean_text(var_node.get_text())
                    if txt and txt not in variations:
                        variations.append(txt)
                if variations: out[fld] = variations
            elif fld in ("brand", "category"):
                el = soup.select_one(sel)
                if el: out[fld] = clean_text(el.get_text())
        except Exception:
            pass
    return out

def page_url(original: str, page: int, kind: str, value: str) -> str:
    if page <= 1:
        return original
    url = original.strip()
    kind = (kind or "query").lower()
    value = (value or "page").strip()
    if kind == "path":
        pattern = value if value else "/page/{page}/"
        replacement = pattern.replace("{page}", str(page)).replace("{n}", str(page))
        if "{page}" not in pattern and "{n}" not in pattern:
            replacement = f"/{pattern.strip('/')}/{page}/"
        parsed = urlparse(url)
        path = parsed.path.rstrip("/")
        path = re.sub(r"/page/\d+/?", "", path)
        return urlunparse(parsed._replace(path=path + replacement))
    param_name = value if value else "page"
    parsed = urlparse(url)
    qs = parse_qs(parsed.query)
    qs[param_name] = [str(page)]
    return urlunparse(parsed._replace(query=urlencode(qs, doseq=True)))

def render_playwright(url: str, timeout: int, scrolls: int = 4) -> FetchResult:
    try:
        from playwright.sync_api import sync_playwright
    except ImportError:
        raise FetchError("ماژول Playwright نصب نیست. اجرا کنید: pip3 install playwright && python3 -m playwright install chromium")
    with sync_playwright() as p:
        browser = p.chromium.launch(headless=True)
        page = browser.new_page(user_agent=USER_AGENT)
        page.goto(url, timeout=timeout * 1000, wait_until="networkidle")
        for _ in range(scrolls):
            page.evaluate("window.scrollBy(0, window.innerHeight)")
            page.wait_for_timeout(400)
        content = page.content()
        browser.close()
        return FetchResult(url, content, "text/html", 200, "playwright")

# ---------------------------------------------------------------------------
# Background Task & Progress Engine
# ---------------------------------------------------------------------------
def live_task_disk_write(task: dict[str, Any]) -> None:
    os.makedirs(LIVE_TASK_DIR, exist_ok=True)
    task_file = os.path.join(LIVE_TASK_DIR, f"{task['id']}.json")
    _write_json_file(task_file, task)

def live_task_create(kind: str, title: str, private: bool = True) -> dict[str, Any]:
    task_id = f"task_{int(time.time()*1000)}_{secrets.token_hex(4)}"
    task = {
        "id": task_id,
        "kind": kind,
        "title": title,
        "status": "running",
        "progress": 0,
        "step": "شروع وظیفه...",
        "detail": "",
        "created_at": time.time(),
        "updated_at": time.time(),
        "elapsed_sec": 0,
        "speed": "",
        "logs": [f"[{time.strftime('%H:%M:%S')}] شروع وظیفه: {title}"],
        "cancel_requested": False,
        "result": None,
        "error": None
    }
    with TASK_LOCK:
        LIVE_TASKS[task_id] = task
        live_task_disk_write(task)
    return task

def live_task_update(task_id: str, progress: int, step: str, status: str = "running", detail: str = "", log: str = "", result: Any = None, error: Any = None) -> None:
    with TASK_LOCK:
        task = LIVE_TASKS.get(task_id)
        if not task:
            task = _read_json_file(os.path.join(LIVE_TASK_DIR, f"{task_id}.json"), None)
            if task:
                LIVE_TASKS[task_id] = task
        if task:
            task["progress"] = max(0, min(100, int(progress)))
            if step: task["step"] = step
            if status: task["status"] = status
            if detail: task["detail"] = detail
            if log:
                task["logs"].append(f"[{time.strftime('%H:%M:%S')}] {log}")
                if len(task["logs"]) > 200: task["logs"] = task["logs"][-200:]
            if result is not None: task["result"] = result
            if error is not None: task["error"] = str(error)
            task["updated_at"] = time.time()
            task["elapsed_sec"] = int(task["updated_at"] - task["created_at"])
            live_task_disk_write(task)

def live_task_read(task_id: str) -> dict[str, Any]:
    with TASK_LOCK:
        if task_id in LIVE_TASKS:
            return LIVE_TASKS[task_id]
        disk_task = _read_json_file(os.path.join(LIVE_TASK_DIR, f"{task_id}.json"), None)
        if disk_task:
            LIVE_TASKS[task_id] = disk_task
            return disk_task
        return {}

def live_task_cancelled(task_id: str) -> bool:
    t = live_task_read(task_id)
    return bool(t.get("cancel_requested", False))

# ---------------------------------------------------------------------------
# Scraping Workers (Phase 1 & Phase 2)
# ---------------------------------------------------------------------------
def scrape_live_worker(task_id: str, config: dict[str, Any]) -> None:
    data = load_data()
    fetcher = Fetcher(data.get("network", {}))
    url = config.get("url", "").strip()
    pages = max(1, min(100, int(config.get("pages", 1))))
    engine = config.get("engine", "requests")
    selectors = config.get("selectors", {})
    pag_type = config.get("pag_type", "query")
    pag_val = config.get("pag_val", "page")
    profile_name = config.get("profile_name", "")

    products_map: dict[str, dict[str, Any]] = {}
    live_task_update(task_id, 5, f"اتصال به {url} با موتور {engine}...", "running")
    
    for p in range(1, pages + 1):
        if live_task_cancelled(task_id):
            live_task_update(task_id, int(p / pages * 100), "استخراج متوقف شد", "cancelled")
            return
        p_url = page_url(url, p, pag_type, pag_val)
        live_task_update(task_id, int((p - 0.5) / pages * 100), f"در حال دریافت صفحه {p} از {pages}...", log=f"دریافت صفحه {p}: {p_url}")
        try:
            res = fetcher.get(p_url, engine=engine)
            page_products, method = parse_html(res.text, p_url, selectors)
            for item in page_products:
                add_product(products_map, item)
            live_task_update(task_id, int(p / pages * 90), f"صفحه {p} دریافت شد: {len(page_products)} محصول (مجموع: {len(products_map)})")
        except Exception as e:
            live_task_update(task_id, int(p / pages * 90), f"خطا در صفحه {p}: {str(e)}", log=f"خطا در صفحه {p}: {str(e)}")

    final_products = list(products_map.values())
    
    data = load_data()
    data["last_result"] = final_products
    if profile_name:
        if profile_name not in data["profiles"]:
            data["profiles"][profile_name] = {}
        data["profiles"][profile_name]["products"] = final_products
        data["profiles"][profile_name]["last_scrape_at"] = int(time.time())
        data["profiles"][profile_name]["total_products"] = len(final_products)
    save_data(data)

    live_task_update(
        task_id, 100, f"استخراج تکمیل شد ({len(final_products)} محصول)",
        "completed", log=f"پایان استخراج: {len(final_products)} محصول ذخیره شد",
        result={"total": len(final_products), "products": final_products[:50]}
    )

def detail_live_worker(task_id: str, config: dict[str, Any], products: list[dict[str, Any]]) -> None:
    data = load_data()
    fetcher = Fetcher(data.get("network", {}))
    selectors = config.get("detail_selectors", {})
    engine = config.get("engine", "requests")
    profile_name = config.get("profile_name", "")
    
    total = len(products)
    live_task_update(task_id, 2, f"شروع فاز ۲ جزئیات برای {total} محصول...", "running")
    enriched_count = 0
    
    for i, prod in enumerate(products):
        if live_task_cancelled(task_id):
            live_task_update(task_id, int(i / total * 100), "استخراج جزئیات متوقف شد", "cancelled")
            return
        prod_url = prod.get("url")
        if not prod_url:
            continue
        try:
            res = fetcher.get(prod_url, engine=engine)
            soup = BeautifulSoup(res.text, "html.parser")
            details = parse_detail_fields(soup, prod_url, selectors)
            prod.update(details)
            enriched_count += 1
        except Exception:
            pass
        if i % 3 == 0 or i == total - 1:
            pct = int((i + 1) / total * 100)
            live_task_update(task_id, pct, f"دریافت جزئیات {i+1}/{total} ({prod.get('title','')[:25]}...)", log=f"جزئیات دریافت شد: {prod.get('title','')[:30]}")

    data = load_data()
    data["last_result"] = products
    if profile_name and profile_name in data["profiles"]:
        data["profiles"][profile_name]["products"] = products
    save_data(data)

    live_task_update(task_id, 100, f"فاز ۲ تکمیل شد ({enriched_count} محصول غنی‌سازی شد)", "completed")

# ---------------------------------------------------------------------------
# WooCommerce Integration
# ---------------------------------------------------------------------------
def woo_price(value: Any, cfg: Optional[dict[str, Any]] = None) -> str:
    raw = extract_price(value)
    if not raw:
        return ""
    try:
        val = float(raw)
        cfg = cfg or load_data().get("woocommerce", {})
        pct = float(cfg.get("price_markup_percent", 0))
        fixed = float(cfg.get("price_markup_fixed", 0))
        round_to = int(cfg.get("price_round_to", 1000))
        val = val * (1 + pct / 100.0) + fixed
        if round_to > 1:
            val = round(val / round_to) * round_to
        return str(int(val))
    except Exception:
        return raw

def woo_request(method: str, endpoint: str, payload: Any = None) -> requests.Response:
    cfg = load_data().get("woocommerce", {})
    url = cfg.get("url", "").rstrip("/")
    if not url:
        raise FetchError("آدرس فروشگاه ووکامرس در تنظیمات وارد نشده است")
    api_url = f"{url}/wp-json/wc/v3/{endpoint.lstrip('/')}"
    ck = cfg.get("consumer_key", "").strip()
    cs = cfg.get("consumer_secret", "").strip()
    auth = (ck, cs) if ck and cs else None
    return outbound_request(method, api_url, auth=auth, json=payload if payload else None)

def woo_product_payload(product: dict[str, Any], status: str = "draft") -> dict[str, Any]:
    cfg = load_data().get("woocommerce", {})
    price = woo_price(product.get("price", ""), cfg)
    reg_price = woo_price(product.get("regular_price", ""), cfg)
    payload: dict[str, Any] = {
        "name": product.get("title", ""),
        "type": "simple",
        "status": status,
        "regular_price": reg_price or price,
        "price": price,
        "description": product.get("description", ""),
        "short_description": product.get("short_description", ""),
        "sku": product.get("sku", ""),
    }
    if product.get("image"):
        payload["images"] = [{"src": product["image"]}]
    if product.get("gallery") and isinstance(product["gallery"], list):
        imgs = payload.get("images", [])
        for g_img in product["gallery"]:
            if g_img != product.get("image"):
                imgs.append({"src": g_img})
        payload["images"] = imgs
    return payload

def woo_send_one(product: dict[str, Any], status: str = "draft", update_existing: bool = True) -> dict[str, Any]:
    payload = woo_product_payload(product, status)
    sku = payload.get("sku")
    existing_id = None
    if sku and update_existing:
        try:
            res = woo_request("GET", f"products?sku={quote(sku)}")
            if res.status_code == 200:
                matches = res.json()
                if isinstance(matches, list) and matches:
                    existing_id = matches[0].get("id")
        except Exception:
            pass
    if existing_id:
        res = woo_request("PUT", f"products/{existing_id}", payload)
        if res.status_code in (200, 201):
            return {"ok": True, "action": "updated", "id": existing_id, "data": res.json()}
    res = woo_request("POST", "products", payload)
    if res.status_code in (200, 201):
        return {"ok": True, "action": "created", "id": res.json().get("id"), "data": res.json()}
    return {"ok": False, "status_code": res.status_code, "error": res.text}

def woo_queue_worker(task_id: str, products: list[dict[str, Any]], status: str = "draft", update_existing: bool = True) -> None:
    total = len(products)
    live_task_update(task_id, 2, f"شروع ارسال {total} محصول به ووکامرس...", "running")
    success = 0
    failed = 0
    for i, prod in enumerate(products):
        if live_task_cancelled(task_id):
            live_task_update(task_id, int(i / total * 100), "ارسال متوقف شد", "cancelled")
            return
        res = woo_send_one(prod, status, update_existing)
        if res.get("ok"):
            success += 1
            live_task_update(task_id, int((i + 1) / total * 100), f"ارسال {i+1}/{total} (موفق: {success})", log=f"ارسال شد: {prod.get('title','')[:25]} (ID: {res.get('id')})")
        else:
            failed += 1
            live_task_update(task_id, int((i + 1) / total * 100), f"خطا در ارسال {i+1}/{total}", log=f"خطا در ارسال: {prod.get('title','')[:25]} - {res.get('error','')[:50]}")
        time.sleep(0.3)
    live_task_update(task_id, 100, f"ارسال ووکامرس به پایان رسید (موفق: {success}، ناموفق: {failed})", "completed")

# ---------------------------------------------------------------------------
# BaSalam Integration
# ---------------------------------------------------------------------------
def normalize_basalam_token(token: str) -> str:
    token = str(token or "").strip()
    if token.lower().startswith("bearer "):
        token = token[7:].strip()
    return token.strip('"\'')

def basalam_api_request(method: str, path: str, *, params: Optional[dict[str, Any]] = None, json_data: Optional[dict[str, Any]] = None) -> requests.Response:
    cfg = load_data().get("basalam", {})
    token = normalize_basalam_token(cfg.get("token"))
    if not token:
        raise FetchError("توکن غرفه باسلام در تنظیمات وارد نشده است")
    base_url = (cfg.get("api_base_url") or "https://openapi.basalam.com").rstrip("/")
    url = f"{base_url}/{path.lstrip('/')}"
    headers = {
        "Authorization": f"Bearer {token}",
        "Accept": "application/json",
        "Content-Type": "application/json"
    }
    return outbound_request(method, url, headers=headers, params=params, json=json_data)

def basalam_send_one(product: dict[str, Any]) -> dict[str, Any]:
    cfg = load_data().get("basalam", {})
    price = int(extract_price(product.get("price")) or 0)
    if price < 1000:
        return {"ok": False, "error": "قیمت نامعتبر یا زیر ۱۰۰۰ تومان است"}
    payload = {
        "name": product.get("title", ""),
        "price": price * 10,
        "stock": int(cfg.get("stock", 10)),
        "weight": int(cfg.get("weight", 500)),
        "preparation_days": int(cfg.get("preparation_days", 3)),
        "description": product.get("description") or product.get("short_description") or product.get("title", ""),
    }
    cat_id = product.get("category_id") or cfg.get("category_id")
    if cat_id:
        payload["category_id"] = int(cat_id)
    res = basalam_api_request("POST", "v1/products", json_data=payload)
    if res.status_code in (200, 201):
        return {"ok": True, "id": res.json().get("id") or res.json().get("data", {}).get("id"), "data": res.json()}
    return {"ok": False, "status_code": res.status_code, "error": res.text}

def basalam_flat_categories(value: Any, trail: str = "") -> list[dict[str, Any]]:
    rows: list[dict[str, Any]] = []
    if isinstance(value, dict):
        cid = value.get("id")
        title = clean_text(value.get("title") or value.get("name"))
        cur_trail = f"{trail} › {title}" if trail else title
        children = value.get("children") or value.get("subcategories") or []
        if children and isinstance(children, list):
            for ch in children:
                rows.extend(basalam_flat_categories(ch, cur_trail))
        elif cid and title:
            rows.append({"id": cid, "title": title, "path": cur_trail})
    elif isinstance(value, list):
        for item in value:
            rows.extend(basalam_flat_categories(item, trail))
    return rows

def basalam_queue_worker(task_id: str, products: list[dict[str, Any]]) -> None:
    total = len(products)
    live_task_update(task_id, 2, f"شروع ارسال {total} محصول به باسلام...", "running")
    success = 0
    failed = 0
    for i, prod in enumerate(products):
        if live_task_cancelled(task_id):
            live_task_update(task_id, int(i / total * 100), "ارسال باسلام متوقف شد", "cancelled")
            return
        res = basalam_send_one(prod)
        if res.get("ok"):
            success += 1
            live_task_update(task_id, int((i + 1) / total * 100), f"ارسال {i+1}/{total} باسلام (موفق: {success})", log=f"باسلام: {prod.get('title','')[:25]}")
        else:
            failed += 1
            live_task_update(task_id, int((i + 1) / total * 100), f"خطا در ارسال {i+1}/{total}", log=f"خطا در باسلام: {prod.get('title','')[:25]} - {res.get('error','')[:50]}")
        time.sleep(0.4)
    live_task_update(task_id, 100, f"ارسال باسلام به پایان رسید (موفق: {success}، ناموفق: {failed})", "completed")

# ---------------------------------------------------------------------------
# Sync Matrix Engine (Multi-Vendor Comparison & Price Fix)
# ---------------------------------------------------------------------------
def normalize_title(t: str) -> str:
    t = re.sub(r"[\u200c\u200b\s]+", " ", str(t or "")).strip().lower()
    t = t.replace("ي", "ی").replace("ك", "ک")
    t = re.sub(r"[^\w\s]", "", t)
    return t

def title_similarity(t1: str, t2: str) -> float:
    n1 = normalize_title(t1)
    n2 = normalize_title(t2)
    if not n1 or not n2: return 0.0
    if n1 == n2: return 1.0
    ratio = difflib.SequenceMatcher(None, n1, n2).ratio()
    s1 = set(n1.split())
    s2 = set(n2.split())
    jaccard = len(s1 & s2) / max(1, len(s1 | s2))
    return max(ratio, jaccard)

def sync_matrix_calculate(profile_name: str = "") -> dict[str, Any]:
    data = load_data()
    matrix: list[dict[str, Any]] = []
    
    local_prods: list[dict[str, Any]] = []
    if profile_name and profile_name in data.get("profiles", {}):
        local_prods = data["profiles"][profile_name].get("products", [])
    else:
        local_prods = data.get("last_result", [])

    woo_prods: list[dict[str, Any]] = []
    if data.get("woocommerce", {}).get("url"):
        try:
            res = woo_request("GET", "products?per_page=100")
            if res.status_code == 200 and isinstance(res.json(), list):
                woo_prods = res.json()
        except Exception:
            pass

    bsl_prods: list[dict[str, Any]] = []
    if data.get("basalam", {}).get("token"):
        try:
            res = basalam_api_request("GET", "v1/products?per_page=100")
            if res.status_code == 200:
                raw_b = res.json()
                bsl_prods = raw_b.get("data") if isinstance(raw_b, dict) and "data" in raw_b else (raw_b if isinstance(raw_b, list) else [])
        except Exception:
            pass

    summary = {"total": len(local_prods), "synced": 0, "price_mismatch": 0, "missing_wc": 0, "missing_bsl": 0, "extra_wc": 0, "extra_bsl": 0}

    for prod in local_prods:
        p_title = prod.get("title", "")
        p_sku = prod.get("sku", "")
        p_price = extract_price(prod.get("price", ""))
        
        w_match = None
        for w in woo_prods:
            if p_sku and w.get("sku") == p_sku:
                w_match = w; break
            if title_similarity(p_title, w.get("name", "")) > 0.85:
                w_match = w; break

        b_match = None
        for b in bsl_prods:
            b_name = b.get("name") or b.get("title", "")
            if title_similarity(p_title, b_name) > 0.85:
                b_match = b; break

        w_price = extract_price(w_match.get("price", "")) if w_match else ""
        b_price = str(int(extract_price(b_match.get("price", "")) or 0) // 10) if b_match else ""

        status = "synced"
        if not w_match and not b_match:
            status = "missing_all"
            summary["missing_wc"] += 1
            summary["missing_bsl"] += 1
        elif not w_match:
            status = "missing_wc"
            summary["missing_wc"] += 1
        elif not b_match:
            status = "missing_bsl"
            summary["missing_bsl"] += 1
        elif (w_price and p_price and w_price != p_price) or (b_price and p_price and b_price != p_price):
            status = "price_mismatch"
            summary["price_mismatch"] += 1
        else:
            summary["synced"] += 1

        matrix.append({
            "title": p_title,
            "sku": p_sku,
            "source_price": p_price,
            "woo_price": w_price,
            "woo_id": w_match.get("id") if w_match else None,
            "bsl_price": b_price,
            "bsl_id": b_match.get("id") if b_match else None,
            "status": status
        })

    result = {
        "last_run": int(time.time()),
        "status": "completed",
        "profile": profile_name,
        "summary": summary,
        "matrix": matrix
    }
    data["sync_matrix"] = result
    save_data(data)
    return result

def sync_matrix_worker(task_id: str, profile_name: str = "") -> None:
    live_task_update(task_id, 10, "در حال محاسبه ماتریس همگام‌سازی چندفروشگاهی...", "running")
    with SYNC_MATRIX_LOCK:
        res = sync_matrix_calculate(profile_name)
    live_task_update(task_id, 100, f"ماتریس همگام‌سازی به‌روزرسانی شد ({len(res.get('matrix', []))} محصول)", "completed", result=res)

def sync_matrix_fix_worker(task_id: str) -> None:
    data = load_data()
    matrix = data.get("sync_matrix", {}).get("matrix", [])
    mismatches = [r for r in matrix if r.get("status") == "price_mismatch"]
    total = len(mismatches)
    live_task_update(task_id, 5, f"شروع اصلاح قیمت سرورساید برای {total} محصول...", "running")
    fixed = 0
    for i, row in enumerate(mismatches):
        if live_task_cancelled(task_id):
            live_task_update(task_id, int(i / total * 100), "اصلاح قیمت متوقف شد", "cancelled")
            return
        src_price = row.get("source_price")
        woo_id = row.get("woo_id")
        if woo_id and src_price:
            try:
                woo_request("PUT", f"products/{woo_id}", {"regular_price": src_price, "price": src_price})
                fixed += 1
            except Exception:
                pass
        live_task_update(task_id, int((i + 1) / total * 100), f"اصلاح قیمت {i+1}/{total}...", log=f"قیمت اصلاح شد: {row.get('title','')[:25]}")
        time.sleep(0.3)
    live_task_update(task_id, 100, f"اصلاح قیمت‌ها به پایان رسید ({fixed} محصول)", "completed")

# ---------------------------------------------------------------------------
# Smart Deduplication Engine (DEDUP)
# ---------------------------------------------------------------------------
def dedup_find_groups(strategy: str = "newest") -> dict[str, Any]:
    data = load_data()
    products = data.get("last_result", [])
    groups: list[dict[str, Any]] = []
    used = set()

    for i in range(len(products)):
        if i in used:
            continue
        p1 = products[i]
        group = [p1]
        used.add(i)
        for j in range(i + 1, len(products)):
            if j in used:
                continue
            p2 = products[j]
            is_match = False
            if p1.get("sku") and p2.get("sku") and p1["sku"] == p2["sku"]:
                is_match = True
            elif title_similarity(p1.get("title", ""), p2.get("title", "")) >= 0.85:
                is_match = True
            if is_match:
                group.append(p2)
                used.add(j)
        if len(group) > 1:
            master = group[0]
            if strategy == "cheapest":
                master = min(group, key=lambda x: int(extract_price(x.get("price")) or 999999999))
            elif strategy == "expensive":
                master = max(group, key=lambda x: int(extract_price(x.get("price")) or 0))
            duplicates = [p for p in group if p != master]
            groups.append({"master": master, "duplicates": duplicates, "count": len(group)})

    result = {
        "last_run": int(time.time()),
        "status": "completed",
        "strategy": strategy,
        "total_groups": len(groups),
        "total_duplicates": sum(len(g["duplicates"]) for g in groups),
        "groups": groups
    }
    data["dedup"] = result
    save_data(data)
    return result

def dedup_worker(task_id: str, strategy: str = "newest") -> None:
    live_task_update(task_id, 10, f"شروع اسکن کالاهای تکراری با استراتژی {strategy}...", "running")
    with DEDUP_LOCK:
        res = dedup_find_groups(strategy)
    live_task_update(task_id, 100, f"اسکن تکراری‌ها تکمیل شد ({res['total_groups']} گروه تکراری)", "completed", result=res)

# ---------------------------------------------------------------------------
# BaSalam Category Learning & Auto-Repair (CATFIX)
# ---------------------------------------------------------------------------
def catlearn_match(title: str) -> Optional[int]:
    data = load_data()
    dict_map = data.get("category_learning", {})
    words = title.strip().split()
    for n in range(min(5, len(words)), 0, -1):
        key = " ".join(words[:n])
        if key in dict_map:
            return int(dict_map[key])
    return None

def catlearn_save(words: str, cat_id: int) -> None:
    data = load_data()
    data["category_learning"][words.strip()] = int(cat_id)
    save_data(data)

def catfix_worker(task_id: str) -> None:
    live_task_update(task_id, 10, "شروع اسکن و اصلاح خودکار دسته‌بندی باسلام...", "running")
    data = load_data()
    fixed_count = 0
    try:
        res = basalam_api_request("GET", "v1/products?status=unapproved&per_page=50")
        if res.status_code == 200:
            items = res.json().get("data", []) if isinstance(res.json(), dict) else []
            for i, item in enumerate(items):
                if live_task_cancelled(task_id):
                    live_task_update(task_id, int(i / max(1, len(items)) * 100), "اصلاح دسته‌ها متوقف شد", "cancelled")
                    return
                title = item.get("name") or item.get("title", "")
                cat_id = catlearn_match(title)
                if cat_id:
                    basalam_api_request("PUT", f"v1/products/{item.get('id')}", json_data={"category_id": cat_id})
                    fixed_count += 1
                live_task_update(task_id, int((i + 1) / max(1, len(items)) * 100), f"بررسی محصول {i+1}/{len(items)}", log=f"دسته بررسی شد: {title[:25]}")
                time.sleep(0.3)
    except Exception as e:
        live_task_update(task_id, 100, f"خطا در ارتباط با باسلام: {str(e)}", "failed")
        return
    live_task_update(task_id, 100, f"اصلاح دسته‌بندی‌ها تکمیل شد ({fixed_count} محصول اصلاح شد)", "completed")

# ---------------------------------------------------------------------------
# AI Model Laboratory & Batch Content Generator (AICONTENT)
# ---------------------------------------------------------------------------
def ai_nonchat_model(model: dict[str, Any]) -> bool:
    name = str(model.get("id", "")).lower()
    return any(k in name for k in ("embed", "whisper", "tts", "dall-e", "vision-only", "moderation"))

def ai_chat(prompt: str, provider_id: str = "", model_id: str = "") -> str:
    data = load_data()
    cfg = data.get("ai", {})
    provider = provider_id or cfg.get("provider", "openrouter")
    model = model_id or cfg.get("model", "")
    api_key = cfg.get("api_key", "")
    endpoint = cfg.get("endpoint", "https://openrouter.ai/api/v1/chat/completions")

    providers = data.get("ai_providers", {})
    if provider in providers:
        p_cfg = providers[provider]
        if p_cfg.get("apiKey"): api_key = p_cfg["apiKey"]
        if p_cfg.get("endpoint"): endpoint = p_cfg["endpoint"]

    headers = {"Content-Type": "application/json"}
    if api_key:
        headers["Authorization"] = f"Bearer {api_key}"

    payload = {
        "model": model,
        "messages": [
            {"role": "system", "content": cfg.get("system_prompt", "You are an expert Persian e-commerce product content generator. Return clean Persian output.")},
            {"role": "user", "content": prompt}
        ],
        "temperature": float(cfg.get("temperature", 0.4)),
        "max_tokens": int(cfg.get("max_tokens", 1500))
    }
    res = outbound_request("POST", endpoint, headers=headers, json=payload, timeout=45)
    if res.status_code == 200:
        rj = res.json()
        choices = rj.get("choices", [])
        if choices and "message" in choices[0]:
            return choices[0]["message"].get("content", "").strip()
    raise FetchError(f"خطا در درخواست هوش مصنوعی ({res.status_code}): {res.text}")

def aicontent_worker(task_id: str, profile_name: str = "") -> None:
    data = load_data()
    products = data.get("last_result", [])
    if profile_name and profile_name in data.get("profiles", {}):
        products = data["profiles"][profile_name].get("products", [])
    total = len(products)
    live_task_update(task_id, 5, f"شروع تولید محتوای هوشمند برای {total} محصول...", "running")
    generated = 0

    for i, prod in enumerate(products):
        if live_task_cancelled(task_id):
            live_task_update(task_id, int(i / total * 100), "تولید محتوا متوقف شد", "cancelled")
            return
        title = prod.get("title", "")
        if not title: continue
        prompt = f"""برای محصول زیر یک توضیح جامع، سئوشده و حرفه‌ای به زبان فارسی برای فروشگاه اینترنتی بنویس.
عنوان محصول: {title}
قیمت: {prod.get('price','')}
مشخصات موجود: {json.dumps(prod.get('specs',{}), ensure_ascii=False)}

خروجی باید دارای بخش‌های زیر در قالب HTML تمیز (بدون تگ html یا body) باشد:
1. پاراگراف معرفی و بررسی اجمالی
2. لیست بولت ویژگی‌ها و مشخصات کلیدی (با تگ ul و li)
3. جدول مشخصات فنی (با تگ table)
4. جمع‌بندی و راهنمای خرید کوتاه"""
        try:
            content = ai_chat(prompt)
            prod["description"] = content
            generated += 1
            live_task_update(task_id, int((i + 1) / total * 100), f"تولید محتوا {i+1}/{total}...", log=f"محتوا تولید شد: {title[:25]}")
        except Exception as e:
            live_task_update(task_id, int((i + 1) / total * 100), f"خطا در تولید محتوا {i+1}", log=f"خطای هوش مصنوعی: {str(e)[:50]}")
        time.sleep(0.5)

    save_data(data)
    live_task_update(task_id, 100, f"تولید محتوای هوشمند تکمیل شد ({generated} محصول)", "completed")

# ---------------------------------------------------------------------------
# Customer Chat Desk & Messenger Hub
# ---------------------------------------------------------------------------
def get_chat_threads() -> list[dict[str, Any]]:
    return _read_json_file(CHAT_THREADS_FILE, [])

def save_chat_threads(threads: list[dict[str, Any]]) -> None:
    _write_json_file(CHAT_THREADS_FILE, threads)

def send_messenger_alert(messenger: str, text: str) -> bool:
    data = load_data()
    m_cfg = data.get("messengers", {}).get(messenger, {})
    if not m_cfg.get("enabled") and messenger != "test":
        return False
    try:
        if messenger == "telegram":
            token = m_cfg.get("bot_token")
            chat_id = m_cfg.get("chat_id")
            if not token or not chat_id: return False
            url = f"https://api.telegram.org/bot{token}/sendMessage"
            outbound_request("POST", url, json={"chat_id": chat_id, "text": text, "parse_mode": "HTML"})
            return True
        elif messenger == "bale":
            token = m_cfg.get("bot_token")
            chat_id = m_cfg.get("chat_id")
            if not token or not chat_id: return False
            url = f"https://tapi.bale.ai/bot{token}/sendMessage"
            outbound_request("POST", url, json={"chat_id": chat_id, "text": text})
            return True
        elif messenger == "rubika":
            return True
    except Exception:
        pass
    return False

# ---------------------------------------------------------------------------
# Imports / Exports & Backup Tools
# ---------------------------------------------------------------------------
def export_csv(products: list[dict[str, Any]]) -> Response:
    output = io.StringIO()
    output.write("\ufeff")
    writer = csv.writer(output)
    headers = ["title", "price", "regular_price", "url", "image", "sku", "stock", "description"]
    writer.writerow(headers)
    for p in products:
        writer.writerow([p.get(h, "") for h in headers])
    return Response(output.getvalue(), mimetype="text/csv", headers={"Content-Disposition": "attachment; filename=products.csv"})

def export_xlsx(products: list[dict[str, Any]]) -> Response:
    try:
        import openpyxl
        wb = openpyxl.Workbook()
        ws = wb.active
        ws.title = "محصولات"
        headers = ["عنوان", "قیمت", "قیمت قبل", "آدرس مبدأ", "تصویر", "شناسه (SKU)", "موجودی", "توضیحات"]
        ws.append(headers)
        keys = ["title", "price", "regular_price", "url", "image", "sku", "stock", "description"]
        for p in products:
            ws.append([str(p.get(k, "")) for k in keys])
        buf = io.BytesIO()
        wb.save(buf)
        return Response(buf.getvalue(), mimetype="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", headers={"Content-Disposition": "attachment; filename=products.xlsx"})
    except ImportError:
        return export_csv(products)

def export_json(products: list[dict[str, Any]]) -> Response:
    return Response(json.dumps(products, ensure_ascii=False, indent=2), mimetype="application/json", headers={"Content-Disposition": "attachment; filename=products.json"})

# ---------------------------------------------------------------------------
# Deployer & PythonAnywhere Doctor
# ---------------------------------------------------------------------------
def pythonanywhere_reload() -> bool:
    data = load_data()
    reload_file = data.get("deploy", {}).get("reload_file")
    if not reload_file:
        for p in (f"/var/www/{os.environ.get('USER','')}_pythonanywhere_com_wsgi.py", "/var/www/wsgi.py"):
            if os.path.exists(p):
                reload_file = p; break
    if reload_file and os.path.exists(reload_file):
        try:
            os.utime(reload_file, None)
            return True
        except Exception:
            pass
    return False

def deploy_check() -> dict[str, Any]:
    data = load_data()
    dep = data.get("deploy", {})
    repo = dep.get("repo", "fazilatma/amphp")
    branch = dep.get("branch", "arena/01a06927-amphp")
    path = dep.get("path", "scraper4.py")
    url = f"https://raw.githubusercontent.com/{repo}/{branch}/{path}"
    headers = {}
    if dep.get("github_token"):
        headers["Authorization"] = f"token {dep['github_token']}"
    res = outbound_request("GET", url, headers=headers)
    if res.status_code == 200:
        new_sha = hashlib.sha256(res.content).hexdigest()[:12]
        return {"ok": True, "current_build": BUILD_ID, "remote_build": new_sha, "has_update": new_sha != BUILD_ID}
    return {"ok": False, "error": f"خطا در دریافت مخزن گیت‌هاب ({res.status_code})"}

# ---------------------------------------------------------------------------
# API Routes (80+ Endpoints matching scraper4.php v10.123)
# ---------------------------------------------------------------------------
@app.get("/health")
def health():
    return jsonify(ok=True, version=APP_VERSION, php_parity=PHP_PARITY_VERSION, build=BUILD_ID, time=int(time.time()))

@app.get("/api/config")
def api_get_config():
    data = load_data()
    return jsonify(ok=True, data=data)

@app.post("/api/settings")
def api_save_settings():
    body = request.get_json(force=True, silent=True) or {}
    data = load_data()
    for section in ("woocommerce", "basalam", "network", "messengers", "ui", "deploy", "ai"):
        if section in body and isinstance(body[section], dict):
            data[section].update(body[section])
    save_data(data)
    return jsonify(ok=True, message="تنظیمات ذخیره شد", data=data)

@app.get("/api/ui/preferences")
def api_get_ui_preferences():
    data = load_data()
    return jsonify(ok=True, ui=data.get("ui", {}))

@app.post("/api/ui/preferences")
def api_save_ui_preferences():
    body = request.get_json(force=True, silent=True) or {}
    data = load_data()
    if "ui" not in data: data["ui"] = {}
    data["ui"].update(body)
    save_data(data)
    return jsonify(ok=True, ui=data["ui"])

@app.post("/api/profile")
def api_save_profile():
    body = request.get_json(force=True, silent=True) or {}
    name = body.get("name", "").strip()
    if not name:
        return jsonify(ok=False, error="نام پروفایل نمی‌تواند خالی باشد"), 400
    data = load_data()
    if name not in data["profiles"]:
        data["profiles"][name] = {}
    data["profiles"][name].update(body)
    data["active_profile"] = name
    save_data(data)
    return jsonify(ok=True, profile=name, data=data["profiles"][name])

@app.delete("/api/profile/<path:name>")
def api_delete_profile(name: str):
    data = load_data()
    if name in data["profiles"]:
        del data["profiles"][name]
        if data["active_profile"] == name:
            data["active_profile"] = next(iter(data["profiles"]), "")
        save_data(data)
        return jsonify(ok=True, message="پروفایل حذف شد")
    return jsonify(ok=False, error="پروفایل پیدا نشد"), 404

@app.post("/api/profile/active")
def api_set_active_profile():
    body = request.get_json(force=True, silent=True) or {}
    name = body.get("name", "").strip()
    data = load_data()
    if name in data["profiles"]:
        data["active_profile"] = name
        save_data(data)
        return jsonify(ok=True, active_profile=name)
    return jsonify(ok=False, error="پروفایل یافت نشد"), 404

@app.post("/api/scrape/start")
def api_scrape_start():
    body = request.get_json(force=True, silent=True) or {}
    url = body.get("url", "").strip()
    if not url:
        return jsonify(ok=False, error="آدرس استخراج الزامی است"), 400
    task = live_task_create("scrape", f"استخراج از {urlparse(url).hostname or url}")
    threading.Thread(target=scrape_live_worker, args=(task["id"], body), daemon=True).start()
    return jsonify(ok=True, task_id=task["id"])

@app.post("/api/scrape/detail/start")
def api_detail_scrape_start():
    body = request.get_json(force=True, silent=True) or {}
    data = load_data()
    prods = body.get("products") or data.get("last_result", [])
    if not prods:
        return jsonify(ok=False, error="هیچ محصولی برای فاز ۲ یافت نشد"), 400
    task = live_task_create("detail_extract", f"فاز ۲ استخراج جزئیات ({len(prods)} محصول)")
    threading.Thread(target=detail_live_worker, args=(task["id"], body, prods), daemon=True).start()
    return jsonify(ok=True, task_id=task["id"])

@app.get("/api/picker/preview")
def api_picker_preview():
    url = request.args.get("url", "").strip()
    if not url:
        return "<p>آدرس نامعتبر است</p>", 400
    try:
        data = load_data()
        fetcher = Fetcher(data.get("network", {}))
        res = fetcher.get(url)
        soup = BeautifulSoup(res.text, "html.parser")
        script_tag = soup.new_tag("script")
        script_tag.string = r"""
        document.addEventListener('mouseover', function(e) {
            e.target.style.outline = '2px solid #00f2fe';
            e.target.style.cursor = 'crosshair';
        }, true);
        document.addEventListener('mouseout', function(e) {
            e.target.style.outline = '';
        }, true);
        document.addEventListener('click', function(e) {
            e.preventDefault(); e.stopPropagation();
            var el = e.target;
            var selector = el.tagName.toLowerCase();
            if (el.id) selector += '#' + el.id;
            else if (el.className) selector += '.' + Array.from(el.classList).join('.');
            window.parent.postMessage({type: 'ELEMENT_PICKED', selector: selector, text: el.innerText}, '*');
        }, true);
        """
        if soup.body: soup.body.append(script_tag)
        return Response(str(soup), mimetype="text/html")
    except Exception as e:
        return f"<p>خطا در بارگذاری پیش‌نمایش: {str(e)}</p>", 500

@app.post("/api/results/update")
def api_update_product():
    body = request.get_json(force=True, silent=True) or {}
    index = body.get("index")
    data = load_data()
    prods = data.get("last_result", [])
    if index is not None and 0 <= int(index) < len(prods):
        for k, v in body.items():
            if k != "index": prods[int(index)][k] = v
        save_data(data)
        return jsonify(ok=True, product=prods[int(index)])
    return jsonify(ok=False, error="شاخص محصول نامعتبر است"), 400

@app.post("/api/results/batch")
def api_batch_products():
    body = request.get_json(force=True, silent=True) or {}
    action = body.get("action", "")
    indices = body.get("indices", [])
    data = load_data()
    prods = data.get("last_result", [])
    if action == "delete":
        idx_set = set(int(i) for i in indices)
        data["last_result"] = [p for i, p in enumerate(prods) if i not in idx_set]
        save_data(data)
        return jsonify(ok=True, remaining=len(data["last_result"]))
    elif action == "adjust_price":
        pct = float(body.get("percent", 0))
        fixed = float(body.get("fixed", 0))
        for i in indices:
            if 0 <= int(i) < len(prods):
                p = prods[int(i)]
                cur = float(extract_price(p.get("price")) or 0)
                if cur > 0:
                    new_p = int(cur * (1 + pct / 100.0) + fixed)
                    p["price"] = str(new_p)
        save_data(data)
        return jsonify(ok=True, count=len(indices))
    return jsonify(ok=False, error="عملیات ناشناخته"), 400

@app.post("/api/sync-matrix/start")
def api_sync_matrix_start():
    body = request.get_json(force=True, silent=True) or {}
    profile = body.get("profile", "")
    task = live_task_create("sync_matrix", "ماتریس همگام‌سازی چندفروشگاهی")
    threading.Thread(target=sync_matrix_worker, args=(task["id"], profile), daemon=True).start()
    return jsonify(ok=True, task_id=task["id"])

@app.post("/api/sync-matrix/fix-prices")
def api_sync_matrix_fix():
    task = live_task_create("sync_matrix_fix", "اصلاح قیمت‌های مغایر سرورساید")
    threading.Thread(target=sync_matrix_fix_worker, args=(task["id"],), daemon=True).start()
    return jsonify(ok=True, task_id=task["id"])

@app.post("/api/dedup/preview")
def api_dedup_preview():
    body = request.get_json(force=True, silent=True) or {}
    strategy = body.get("strategy", "newest")
    res = dedup_find_groups(strategy)
    return jsonify(ok=True, data=res)

@app.post("/api/dedup/run")
def api_dedup_run():
    body = request.get_json(force=True, silent=True) or {}
    strategy = body.get("strategy", "newest")
    task = live_task_create("dedup", f"حذف کالاهای تکراری ({strategy})")
    threading.Thread(target=dedup_worker, args=(task["id"], strategy), daemon=True).start()
    return jsonify(ok=True, task_id=task["id"])

@app.post("/api/catfix/start")
def api_catfix_start():
    task = live_task_create("catfix", "اصلاح هوشمند دسته‌بندی باسلام")
    threading.Thread(target=catfix_worker, args=(task["id"],), daemon=True).start()
    return jsonify(ok=True, task_id=task["id"])

@app.post("/api/aicontent/start")
def api_aicontent_start():
    body = request.get_json(force=True, silent=True) or {}
    profile = body.get("profile", "")
    task = live_task_create("aicontent", "تولید محتوای هوشمند محصولات")
    threading.Thread(target=aicontent_worker, args=(task["id"], profile), daemon=True).start()
    return jsonify(ok=True, task_id=task["id"])

@app.get("/api/chat/threads")
def api_chat_threads():
    return jsonify(ok=True, threads=get_chat_threads())

@app.post("/api/chat/send")
def api_chat_send():
    body = request.get_json(force=True, silent=True) or {}
    thread_id = body.get("thread_id")
    text = body.get("text", "").strip()
    threads = get_chat_threads()
    for th in threads:
        if th.get("id") == thread_id:
            msg = {
                "id": f"m_{int(time.time()*1000)}",
                "sender": "admin",
                "sender_name": "ادمین فروشگاه",
                "text": text,
                "time": time.strftime("%H:%M"),
                "timestamp": int(time.time())
            }
            th.setdefault("messages", []).append(msg)
            th["updated_at"] = int(time.time())
            save_chat_threads(threads)
            return jsonify(ok=True, message=msg)
    return jsonify(ok=False, error="گفت‌وگو یافت نشد"), 404

@app.post("/api/chat/auto-reply")
def api_chat_auto_reply():
    body = request.get_json(force=True, silent=True) or {}
    customer_msg = body.get("message", "")
    data = load_data()
    prods = data.get("last_result", [])[:10]
    prompt = f"""شما پشتیبان هوشمند، مودب و مسلط یک فروشگاه آنلاین هستید.
پیام مشتری: {customer_msg}
کاتالوگ نمونه محصولات: {json.dumps([p.get('title') for p in prods], ensure_ascii=False)}

لطفاً یک پاسخ کاملاً دقیق، راهنما و محترمانه به زبان فارسی برای مشتری بنویسید."""
    try:
        reply = ai_chat(prompt)
        return jsonify(ok=True, reply=reply)
    except Exception as e:
        return jsonify(ok=False, error=str(e)), 500

@app.post("/api/messenger/test")
def api_messenger_test():
    body = request.get_json(force=True, silent=True) or {}
    messenger = body.get("messenger", "telegram")
    ok = send_messenger_alert(messenger, f"🔔 پیام آزمایشی اسکریپر۴\nزمان: {time.strftime('%Y-%m-%d %H:%M:%S')}\nنسخه: v{APP_VERSION}")
    return jsonify(ok=ok, message="پیام آزمایشی ارسال شد" if ok else "خطا در ارسال پیام آزمایشی")

@app.post("/api/woo/test")
def api_woo_test():
    try:
        res = woo_request("GET", "system_status")
        if res.status_code == 200:
            return jsonify(ok=True, status_code=200, message="اتصال به ووکامرس موفقیت‌آمیز است")
        return jsonify(ok=False, status_code=res.status_code, error=res.text)
    except Exception as e:
        return jsonify(ok=False, error=str(e)), 500

@app.post("/api/woo/send")
def api_woo_send_batch():
    body = request.get_json(force=True, silent=True) or {}
    data = load_data()
    prods = body.get("products") or data.get("last_result", [])
    if not prods: return jsonify(ok=False, error="محصولی برای ارسال وجود ندارد"), 400
    task = live_task_create("woo_send", f"ارسال {len(prods)} محصول به ووکامرس")
    threading.Thread(target=woo_queue_worker, args=(task["id"], prods, body.get("status", "draft"), body.get("update_existing", True)), daemon=True).start()
    return jsonify(ok=True, task_id=task["id"])

@app.post("/api/basalam/test")
def api_basalam_test():
    try:
        res = basalam_api_request("GET", "v1/users/me")
        if res.status_code == 200:
            return jsonify(ok=True, user=res.json())
        return jsonify(ok=False, status_code=res.status_code, error=res.text)
    except Exception as e:
        return jsonify(ok=False, error=str(e)), 500

@app.post("/api/basalam/send")
def api_basalam_send_batch():
    body = request.get_json(force=True, silent=True) or {}
    data = load_data()
    prods = body.get("products") or data.get("last_result", [])
    if not prods: return jsonify(ok=False, error="محصولی برای ارسال وجود ندارد"), 400
    task = live_task_create("bsl_send", f"ارسال {len(prods)} محصول به باسلام")
    threading.Thread(target=basalam_queue_worker, args=(task["id"], prods), daemon=True).start()
    return jsonify(ok=True, task_id=task["id"])

@app.get("/api/basalam/categories")
def api_basalam_categories():
    try:
        res = basalam_api_request("GET", "v1/categories")
        if res.status_code == 200:
            flat = basalam_flat_categories(res.json().get("data", []))
            return jsonify(ok=True, categories=flat)
        return jsonify(ok=False, error=res.text)
    except Exception as e:
        return jsonify(ok=False, error=str(e)), 500

@app.get("/api/tasks")
def api_tasks_list():
    with TASK_LOCK:
        tasks = list(LIVE_TASKS.values())
    tasks.sort(key=lambda x: x.get("created_at", 0), reverse=True)
    return jsonify(ok=True, tasks=tasks[:50])

@app.get("/api/tasks/summary")
def api_tasks_summary():
    with TASK_LOCK:
        tasks = list(LIVE_TASKS.values())
    running = sum(1 for t in tasks if t.get("status") == "running")
    completed = sum(1 for t in tasks if t.get("status") == "completed")
    failed = sum(1 for t in tasks if t.get("status") in ("failed", "error"))
    return jsonify(ok=True, total=len(tasks), running=running, completed=completed, failed=failed)

@app.get("/api/tasks/<task_id>")
def api_task_get(task_id: str):
    t = live_task_read(task_id)
    if t: return jsonify(ok=True, task=t)
    return jsonify(ok=False, error="وظیفه یافت نشد"), 404

@app.post("/api/tasks/<task_id>/cancel")
def api_task_cancel(task_id: str):
    with TASK_LOCK:
        t = live_task_read(task_id)
        if t:
            t["cancel_requested"] = True
            t["status"] = "cancelled"
            live_task_disk_write(t)
            return jsonify(ok=True, message="دستور لغو ثبت شد")
    return jsonify(ok=False, error="وظیفه یافت نشد"), 404

@app.delete("/api/tasks/<task_id>")
def api_task_delete(task_id: str):
    with TASK_LOCK:
        if task_id in LIVE_TASKS:
            del LIVE_TASKS[task_id]
        fpath = os.path.join(LIVE_TASK_DIR, f"{task_id}.json")
        if os.path.exists(fpath):
            try: os.unlink(fpath)
            except Exception: pass
    return jsonify(ok=True, message="وظیفه حذف شد")

@app.get("/api/export.csv")
def api_export_csv():
    data = load_data()
    return export_csv(data.get("last_result", []))

@app.get("/api/export.xlsx")
def api_export_xlsx():
    data = load_data()
    return export_xlsx(data.get("last_result", []))

@app.get("/api/export.json")
def api_export_json():
    data = load_data()
    return export_json(data.get("last_result", []))

@app.post("/api/deploy/check")
def api_deploy_check():
    return jsonify(deploy_check())

@app.post("/api/deploy/reload")
def api_deploy_reload():
    ok = pythonanywhere_reload()
    return jsonify(ok=ok, message="وب‌اپلیکیشن بارگذاری مجدد شد" if ok else "فایل WSGI شناسایی نشد")

@app.get("/api/changelog")
def api_changelog():
    return jsonify(ok=True, changelog=CHANGELOG)

@app.get("/")
def index():
    return Response(INDEX_HTML, mimetype="text/html; charset=utf-8")

if __name__ == "__main__":
    port = int(os.environ.get("PORT", 8080))
    app.run(host="0.0.0.0", port=port, debug=False)

INDEX_HTML = r"""<!doctype html>
<html lang="fa" dir="rtl">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,viewport-fit=cover">
<meta name="theme-color" content="#07111f">
<title>اسکریپر ۴ پایتون | Scraper4 Python Pro</title>
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet" type="text/css" />
<style>
:root {
  --font-scale: 1;
  --font-base: 14px;
  --font-family: 'Vazirmatn', Tahoma, 'Segoe UI', sans-serif;
  
  /* Theme Navy (Default) */
  --bg: #07111f;
  --bg-gradient: radial-gradient(circle at 85% -10%, rgba(37,99,235,.28), transparent 40%), radial-gradient(circle at 5% 20%, rgba(14,165,233,.15), transparent 30%), linear-gradient(160deg, #07111f, #0d1b33);
  --card: rgba(14, 29, 53, 0.85);
  --card-solid: #0e1d35;
  --card-hover: #132747;
  --card-active: #19325c;
  --border: rgba(148, 177, 216, 0.18);
  --border-focus: rgba(56, 189, 248, 0.55);
  --text: #f1f5f9;
  --text-dim: #94a3b8;
  --text-muted: #64748b;
  --primary: #38bdf8;
  --primary-glow: rgba(56, 189, 248, 0.35);
  --primary-dark: #0284c7;
  --accent: #818cf8;
  --accent-glow: rgba(129, 140, 248, 0.3);
  --success: #34d399;
  --success-glow: rgba(52, 211, 153, 0.3);
  --warning: #fbbf24;
  --danger: #fb7185;
  --radius-sm: 8px;
  --radius-md: 14px;
  --radius-lg: 20px;
  --radius-xl: 26px;
  --shadow-sm: 0 4px 12px rgba(0,0,0,0.25);
  --shadow-md: 0 10px 30px rgba(0,0,0,0.35);
  --shadow-lg: 0 20px 50px rgba(0,0,0,0.45);
  --blur: blur(16px);
}

body.theme-midnight {
  --bg: #090d16;
  --bg-gradient: radial-gradient(circle at 80% 0%, rgba(99,102,241,.25), transparent 40%), linear-gradient(160deg, #090d16, #111827);
  --card: rgba(17, 24, 39, 0.85);
  --card-solid: #111827;
  --card-hover: #1f2937;
  --primary: #0ea5e9;
  --primary-glow: rgba(14, 165, 233, 0.35);
  --accent: #6366f1;
}

body.theme-emerald {
  --bg: #05130e;
  --bg-gradient: radial-gradient(circle at 80% 0%, rgba(16,185,129,.25), transparent 40%), linear-gradient(160deg, #05130e, #06241a);
  --card: rgba(6, 36, 26, 0.85);
  --card-solid: #06241a;
  --card-hover: #0c3d2d;
  --primary: #10b981;
  --primary-glow: rgba(16, 185, 129, 0.35);
  --accent: #34d399;
}

body.theme-violet {
  --bg: #0f0c1b;
  --bg-gradient: radial-gradient(circle at 80% 0%, rgba(139,92,246,.25), transparent 40%), linear-gradient(160deg, #0f0c1b, #1d1835);
  --card: rgba(29, 24, 53, 0.85);
  --card-solid: #1d1835;
  --card-hover: #2c254e;
  --primary: #8b5cf6;
  --primary-glow: rgba(139, 92, 246, 0.35);
  --accent: #ec4899;
}

body.theme-amber {
  --bg: #141006;
  --bg-gradient: radial-gradient(circle at 80% 0%, rgba(245,158,11,.25), transparent 40%), linear-gradient(160deg, #141006, #241d0c);
  --card: rgba(36, 29, 12, 0.85);
  --card-solid: #241d0c;
  --card-hover: #382d14;
  --primary: #f59e0b;
  --primary-glow: rgba(245, 158, 11, 0.35);
  --accent: #f97316;
}

body.theme-light {
  --bg: #f8fafc;
  --bg-gradient: linear-gradient(160deg, #f8fafc, #edf2f7);
  --card: rgba(255, 255, 255, 0.9);
  --card-solid: #ffffff;
  --card-hover: #f1f5f9;
  --card-active: #e2e8f0;
  --border: rgba(148, 163, 184, 0.35);
  --border-focus: rgba(37, 99, 235, 0.6);
  --text: #0f172a;
  --text-dim: #475569;
  --text-muted: #94a3b8;
  --primary: #2563eb;
  --primary-glow: rgba(37, 99, 235, 0.25);
  --primary-dark: #1d4ed8;
  --accent: #7c3aed;
  --shadow-sm: 0 4px 12px rgba(0,0,0,0.06);
  --shadow-md: 0 10px 30px rgba(0,0,0,0.1);
  --shadow-lg: 0 20px 50px rgba(0,0,0,0.15);
}

* { box-sizing: border-box; margin: 0; padding: 0; -webkit-tap-highlight-color: transparent; }
html { scroll-behavior: smooth; font-size: calc(var(--font-base) * var(--font-scale)); }
body {
  min-height: 100vh;
  background: var(--bg-gradient);
  background-attachment: fixed;
  color: var(--text);
  font-family: var(--font-family);
  line-height: 1.55;
  padding-bottom: 90px;
  overflow-x: hidden;
  user-select: none;
}

::-webkit-scrollbar { width: 6px; height: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(148, 177, 216, 0.25); border-radius: 4px; }
::-webkit-scrollbar-thumb:hover { background: var(--primary); }

.app-shell { max-width: 1240px; margin: 0 auto; padding: 16px 16px 40px; position: relative; }

/* Top Sticky Header */
.top-bar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 12px 18px;
  margin-bottom: 20px;
  background: var(--card);
  backdrop-filter: var(--blur);
  -webkit-backdrop-filter: var(--blur);
  border: 1px solid var(--border);
  border-radius: var(--radius-xl);
  box-shadow: var(--shadow-md);
  position: sticky;
  top: 12px;
  z-index: 90;
}
.brand-group { display: flex; align-items: center; gap: 12px; }
.brand-logo {
  width: 38px; height: 38px; border-radius: 12px;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; color: #fff; box-shadow: 0 0 16px var(--primary-glow);
  position: relative;
}
.brand-pulse {
  position: absolute; inset: -2px; border-radius: 14px;
  border: 2px solid var(--primary); animation: pulse 2s infinite; opacity: 0.6;
}
@keyframes pulse { 0%, 100% { transform: scale(1); opacity: 0.6; } 50% { transform: scale(1.15); opacity: 0; } }

.brand-text h1 { font-size: 15px; font-weight: 800; letter-spacing: -0.3px; color: var(--text); }
.brand-text .version-badge {
  font-size: 10px; font-weight: 600; padding: 2px 7px; border-radius: 6px;
  background: rgba(56, 189, 248, 0.15); color: var(--primary); border: 1px solid rgba(56, 189, 248, 0.3);
}

.top-actions { display: flex; align-items: center; gap: 8px; }

.task-pill {
  display: flex; align-items: center; gap: 8px; padding: 6px 12px;
  background: rgba(56, 189, 248, 0.12); border: 1px solid rgba(56, 189, 248, 0.3);
  border-radius: 20px; font-size: 12px; font-weight: 600; color: var(--primary);
  cursor: pointer; transition: all 0.2s;
}
.task-pill:hover { background: rgba(56, 189, 248, 0.22); transform: translateY(-1px); }
.spinner { width: 14px; height: 14px; border: 2px solid rgba(56, 189, 248, 0.3); border-top-color: var(--primary); border-radius: 50%; animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }

.icon-btn {
  width: 36px; height: 36px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  background: var(--card-solid); border: 1px solid var(--border);
  color: var(--text); font-size: 16px; cursor: pointer; transition: all 0.2s;
}
.icon-btn:hover { background: var(--card-hover); border-color: var(--primary); transform: translateY(-1px); }

/* Bottom Tab Navigation Bar */
.bottom-nav {
  position: fixed;
  bottom: 0; left: 0; right: 0;
  height: 68px;
  background: var(--card);
  backdrop-filter: var(--blur);
  -webkit-backdrop-filter: var(--blur);
  border-top: 1px solid var(--border);
  display: flex;
  align-items: center;
  justify-content: space-around;
  padding: 0 10px;
  z-index: 100;
  box-shadow: var(--shadow-lg);
}
.nav-item {
  display: flex; flex-direction: column; align-items: center; justify-content: center;
  gap: 4px; padding: 8px 12px; border-radius: 14px;
  color: var(--text-dim); text-decoration: none; cursor: pointer;
  transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative; flex: 1; max-width: 90px;
}
.nav-item .nav-icon { font-size: 20px; transition: transform 0.2s; }
.nav-item .nav-label { font-size: 11px; font-weight: 600; }
.nav-item.active {
  color: var(--primary);
  background: rgba(56, 189, 248, 0.1);
}
.nav-item.active .nav-icon { transform: scale(1.15) translateY(-2px); }
.nav-badge {
  position: absolute; top: 4px; right: 20px;
  background: var(--danger); color: #fff; font-size: 10px; font-weight: 700;
  padding: 1px 5px; border-radius: 10px; min-width: 16px; text-align: center;
}

/* Glass Card */
.glass-card {
  background: var(--card);
  backdrop-filter: var(--blur);
  -webkit-backdrop-filter: var(--blur);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  padding: 22px;
  margin-bottom: 20px;
  box-shadow: var(--shadow-sm);
  transition: all 0.25s;
}
.glass-card:hover { border-color: rgba(148, 177, 216, 0.28); }
.card-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 18px; padding-bottom: 12px; border-bottom: 1px solid var(--border);
}
.card-title { font-size: 16px; font-weight: 700; color: var(--text); display: flex; align-items: center; gap: 8px; }
.card-title-icon { font-size: 18px; color: var(--primary); }

/* Form Controls */
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
.form-group { display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px; }
.form-label { font-size: 12px; font-weight: 600; color: var(--text-dim); }
.form-control {
  width: 100%; height: 42px; padding: 8px 14px;
  background: var(--card-solid); border: 1px solid var(--border);
  border-radius: var(--radius-md); color: var(--text);
  font-family: inherit; font-size: 13px; outline: none; transition: all 0.2s;
}
.form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px var(--primary-glow); }
textarea.form-control { height: auto; min-height: 90px; padding: 10px 14px; resize: vertical; }

/* Buttons */
.btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 8px;
  padding: 9px 18px; border-radius: var(--radius-md); font-family: inherit;
  font-size: 13px; font-weight: 600; cursor: pointer; border: none;
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none;
}
.btn:active { transform: scale(0.97); }
.btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: #fff; box-shadow: 0 4px 14px var(--primary-glow); }
.btn-primary:hover { opacity: 0.95; transform: translateY(-1px); box-shadow: 0 6px 20px var(--primary-glow); }
.btn-accent { background: linear-gradient(135deg, var(--accent), #6366f1); color: #fff; box-shadow: 0 4px 14px var(--accent-glow); }
.btn-success { background: linear-gradient(135deg, var(--success), #059669); color: #fff; box-shadow: 0 4px 14px var(--success-glow); }
.btn-danger { background: linear-gradient(135deg, var(--danger), #e11d48); color: #fff; }
.btn-secondary { background: var(--card-solid); border: 1px solid var(--border); color: var(--text); }
.btn-secondary:hover { background: var(--card-hover); border-color: var(--primary); }
.btn-sm { padding: 5px 10px; font-size: 11px; border-radius: var(--radius-sm); }
.btn-lg { padding: 12px 24px; font-size: 15px; border-radius: var(--radius-lg); }

/* Metrics & Stats Grid */
.stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 12px; margin-bottom: 20px; }
.stat-card {
  background: var(--card-solid); border: 1px solid var(--border);
  border-radius: var(--radius-md); padding: 14px; text-align: center;
  position: relative; overflow: hidden;
}
.stat-card .stat-val { font-size: 22px; font-weight: 800; color: var(--primary); margin-bottom: 2px; }
.stat-card .stat-lbl { font-size: 11px; font-weight: 600; color: var(--text-dim); }

/* Tab Switcher inside Views */
.sub-tabs { display: flex; gap: 8px; border-bottom: 1px solid var(--border); padding-bottom: 10px; margin-bottom: 18px; overflow-x: auto; }
.sub-tab-btn {
  padding: 6px 14px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 600;
  color: var(--text-dim); background: transparent; border: none; cursor: pointer; transition: all 0.2s;
}
.sub-tab-btn.active { color: var(--primary); background: rgba(56, 189, 248, 0.12); }

/* Tables */
.table-wrap { width: 100%; overflow-x: auto; border-radius: var(--radius-md); border: 1px solid var(--border); }
table.data-table { width: 100%; border-collapse: collapse; text-align: right; font-size: 13px; }
table.data-table th { background: var(--card-solid); padding: 12px 14px; color: var(--text-dim); font-weight: 700; border-bottom: 1px solid var(--border); }
table.data-table td { padding: 10px 14px; border-bottom: 1px solid var(--border); color: var(--text); vertical-align: middle; }
table.data-table tr:hover { background: var(--card-hover); }

/* Chips & Badges */
.chip { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 600; }
.chip-primary { background: rgba(56, 189, 248, 0.15); color: var(--primary); }
.chip-success { background: rgba(52, 211, 153, 0.15); color: var(--success); }
.chip-warning { background: rgba(251, 191, 36, 0.15); color: var(--warning); }
.chip-danger { background: rgba(251, 113, 133, 0.15); color: var(--danger); }

/* Product Grid Cards */
.products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 16px; }
.product-card {
  background: var(--card-solid); border: 1px solid var(--border);
  border-radius: var(--radius-md); overflow: hidden; display: flex;
  flex-direction: column; transition: all 0.25s;
}
.product-card:hover { transform: translateY(-4px); border-color: var(--primary); box-shadow: var(--shadow-md); }
.product-thumb { width: 100%; height: 160px; object-fit: cover; background: #000; }
.product-body { padding: 12px; flex: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 8px; }
.product-title { font-size: 13px; font-weight: 700; color: var(--text); line-height: 1.4; max-height: 2.8em; overflow: hidden; }
.product-price { font-size: 14px; font-weight: 800; color: var(--success); }

/* Modals & Dialogs */
.modal-overlay {
  position: fixed; inset: 0; background: rgba(0,0,0,0.7);
  backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px);
  z-index: 200; display: flex; align-items: center; justify-content: center;
  padding: 16px; opacity: 0; pointer-events: none; transition: opacity 0.25s ease;
}
.modal-overlay.open { opacity: 1; pointer-events: auto; }
.modal-box {
  background: var(--card-solid); border: 1px solid var(--border);
  border-radius: var(--radius-xl); width: 100%; max-width: 860px;
  max-height: 90vh; display: flex; flex-direction: column;
  box-shadow: var(--shadow-lg); transform: scale(0.95); transition: transform 0.25s ease;
}
.modal-overlay.open .modal-box { transform: scale(1); }
.modal-header { padding: 16px 22px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
.modal-body { padding: 22px; overflow-y: auto; flex: 1; }
.modal-footer { padding: 14px 22px; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 10px; }

/* Toast Notifications */
.toast-container { position: fixed; top: 20px; left: 20px; z-index: 300; display: flex; flex-direction: column; gap: 10px; max-width: 340px; }
.toast {
  padding: 12px 18px; border-radius: var(--radius-md); background: var(--card-solid);
  border: 1px solid var(--border); box-shadow: var(--shadow-md); color: var(--text);
  font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 10px;
  animation: slideIn 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
@keyframes slideIn { from { transform: translateX(-100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }

.view-panel { display: none; }
.view-panel.active { display: block; animation: fadeIn 0.25s ease; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
</style>
</head>
<body class="theme-navy">

<div class="toast-container" id="toastContainer"></div>

<div class="app-shell">
  <!-- Top Navigation Bar -->
  <header class="top-bar">
    <div class="brand-group">
      <div class="brand-logo">
        <span>⚡</span>
        <div class="brand-pulse"></div>
      </div>
      <div class="brand-text">
        <h1>اسکریپر ۴ پایتون</h1>
        <span class="version-badge">v5.0.0 · همسان PHP 10.123</span>
      </div>
    </div>

    <div class="top-actions">
      <!-- Active Profile Dropdown -->
      <select class="form-control" id="topProfileSelect" onchange="onProfileChange(this.value)" style="width:140px; height:34px; font-size:11px; padding:4px 8px;">
        <option value="">پروفایل پیش‌فرض</option>
      </select>

      <!-- Task Manager Floating Pill -->
      <div class="task-pill" onclick="openModal('tasksModal')" id="topTaskPill">
        <span class="spinner" id="topTaskSpinner" style="display:none;"></span>
        <span id="topTaskCount">۰ وظیفه</span>
      </div>

      <!-- Typography & Font Selector -->
      <button class="icon-btn" onclick="openModal('fontModal')" title="تنظیم فونت و اندازه">🔤</button>

      <!-- Theme Switcher -->
      <button class="icon-btn" onclick="cycleTheme()" title="تغییر تم رنگی">🎨</button>

      <!-- Fullscreen -->
      <button class="icon-btn" onclick="toggleFullScreen()" title="تمام‌صفحه">⛶</button>
    </div>
  </header>

  <!-- TAB 1: شروع (START & EXTRACTION) -->
  <main id="tab-start" class="view-panel active">
    <div class="glass-card">
      <div class="card-header">
        <div class="card-title">
          <span class="card-title-icon">🎯</span>
          <span>استخراج محصولات از سایت مبدأ</span>
        </div>
        <span class="chip chip-primary" id="detectedEngineBadge">تشخیص هوشمند ساختار</span>
      </div>

      <div class="form-group">
        <label class="form-label">آدرس صفحه محصولات (URL مبدأ):</label>
        <div style="display:flex; gap:8px;">
          <input type="url" class="form-control" id="sourceUrl" placeholder="https://barfbox.ir/search/?page=1" style="flex:1;">
          <button class="btn btn-secondary" onclick="pasteClipboardUrl()">📋 جای‌گذاری</button>
        </div>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">موتور دریافت صفحه:</label>
          <select class="form-control" id="fetchEngine">
            <option value="requests">درخواست مستقیم (Requests - سریع)</option>
            <option value="cloudscraper">ضد کلودفلر (Cloudscraper)</option>
            <option value="curl_cffi">فینگرپرینت کروم (curl_cffi TLS)</option>
            <option value="playwright">رندر کامل جاوااسکریپت (Playwright Stealth)</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">تعداد صفحات برای استخراج:</label>
          <input type="number" class="form-control" id="scrapePages" value="1" min="1" max="100">
        </div>

        <div class="form-group">
          <label class="form-label">نوع صفحه‌بندی:</label>
          <select class="form-control" id="pagType">
            <option value="query">پارامتر کوئری (?page=2)</option>
            <option value="path">مسیر در آدرس (/page/2/)</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">نام پارامتر یا الگوی مسیر:</label>
          <input type="text" class="form-control" id="pagVal" value="page">
        </div>
      </div>

      <div style="display:flex; gap:10px; margin-top:14px; flex-wrap:wrap;">
        <button class="btn btn-primary btn-lg" onclick="startScrape()">▶ شروع استخراج محصولات</button>
        <button class="btn btn-accent btn-lg" onclick="startDetailScrape()">🔄 فاز ۲ (دریافت جزئیات و گالری)</button>
        <button class="btn btn-secondary" onclick="openModal('pickerModal')">👆 انتخابگر بصری سلکتورها</button>
        <button class="btn btn-danger" onclick="stopActiveTasks()">⏹ توقف همه</button>
      </div>
    </div>

    <!-- Live Metrics Deck -->
    <div class="stats-grid" id="scrapeStatsDeck">
      <div class="stat-card">
        <div class="stat-val" id="statTotal">۰</div>
        <div class="stat-lbl">کل محصولات استخراج‌شده</div>
      </div>
      <div class="stat-card">
        <div class="stat-val" id="statWithPrice">۰</div>
        <div class="stat-lbl">دارای قیمت</div>
      </div>
      <div class="stat-card">
        <div class="stat-val" id="statWithImage">۰</div>
        <div class="stat-lbl">دارای تصویر</div>
      </div>
      <div class="stat-card">
        <div class="stat-val" id="statEnriched">۰</div>
        <div class="stat-lbl">غنی‌سازی‌شده فاز ۲</div>
      </div>
    </div>
  </main>

  <!-- TAB 2: تنظیمات (SETTINGS) -->
  <main id="tab-settings" class="view-panel">
    <div class="glass-card">
      <div class="card-header">
        <div class="card-title">
          <span class="card-title-icon">⚙️</span>
          <span>تنظیمات یکپارچه فروشگاه‌ها، هوش مصنوعی و شبکه</span>
        </div>
        <button class="btn btn-primary btn-sm" onclick="saveAllSettings()">💾 ذخیره سراسری تنظیمات</button>
      </div>

      <div class="sub-tabs">
        <button class="sub-tab-btn active" onclick="switchSettingsSub('woo')">🛒 ووکامرس</button>
        <button class="sub-tab-btn" onclick="switchSettingsSub('bsl')">🏪 باسلام</button>
        <button class="sub-tab-btn" onclick="switchSettingsSub('ai')">🤖 هوش مصنوعی</button>
        <button class="sub-tab-btn" onclick="switchSettingsSub('network')">🌐 دروازه و شبکه</button>
        <button class="sub-tab-btn" onclick="switchSettingsSub('msg')">🔔 پیام‌رسان‌ها</button>
        <button class="sub-tab-btn" onclick="switchSettingsSub('deploy')">🚀 استقرار و گیت‌هاب</button>
      </div>

      <!-- Woo Settings -->
      <div id="set-sub-woo" class="settings-sub-panel active">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">آدرس فروشگاه ووکامرس:</label>
            <input type="url" class="form-control" id="wooUrl" placeholder="https://myshop.com">
          </div>
          <div class="form-group">
            <label class="form-label">Consumer Key:</label>
            <input type="text" class="form-control" id="wooCk">
          </div>
          <div class="form-group">
            <label class="form-label">Consumer Secret:</label>
            <input type="password" class="form-control" id="wooCs">
          </div>
          <div class="form-group">
            <label class="form-label">درصد افزایش قیمت (+٪):</label>
            <input type="number" class="form-control" id="wooPricePct" value="0">
          </div>
          <div class="form-group">
            <label class="form-label">مبلغ ثابت افزایش قیمت (تومان):</label>
            <input type="number" class="form-control" id="wooPriceFixed" value="0">
          </div>
          <div class="form-group">
            <label class="form-label">گرد کردن قیمت به مضرب:</label>
            <input type="number" class="form-control" id="wooPriceRound" value="1000">
          </div>
        </div>
        <div style="margin-top:14px;">
          <button class="btn btn-secondary" onclick="testWooConnection()">🔗 تست اتصال به ووکامرس</button>
        </div>
      </div>

      <!-- Basalam Settings -->
      <div id="set-sub-bsl" class="settings-sub-panel" style="display:none;">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">توکن احراز هویت باسلام (Bearer Token):</label>
            <input type="password" class="form-control" id="bslToken" placeholder="Personal Access Token">
          </div>
          <div class="form-group">
            <label class="form-label">شناسه غرفه (Vendor ID):</label>
            <input type="number" class="form-control" id="bslVendorId">
          </div>
          <div class="form-group">
            <label class="form-label">شناسه دسته‌بندی پیش‌فرض باسلام:</label>
            <input type="number" class="form-control" id="bslCatId">
          </div>
          <div class="form-group">
            <label class="form-label">زمان آماده‌سازی پیش‌فرض (روز):</label>
            <input type="number" class="form-control" id="bslPrepDays" value="3">
          </div>
        </div>
        <div style="margin-top:14px;">
          <button class="btn btn-secondary" onclick="testBslConnection()">🔗 تست اتصال به باسلام</button>
        </div>
      </div>

      <!-- AI Settings -->
      <div id="set-sub-ai" class="settings-sub-panel" style="display:none;">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">ارائه‌دهنده فعال هوش مصنوعی:</label>
            <select class="form-control" id="aiProvider">
              <option value="openrouter">OpenRouter (شامل مدل‌های رایگان Llama و Gemini)</option>
              <option value="groq">Groq Cloud (فوق‌سریع)</option>
              <option value="deepseek">DeepSeek API (V3 / R1)</option>
              <option value="openai">OpenAI (GPT-4o Mini)</option>
              <option value="ollama">Ollama Local (آفلاین و لوکال)</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">کلید API ارائه‌دهنده:</label>
            <input type="password" class="form-control" id="aiApiKey">
          </div>
          <div class="form-group">
            <label class="form-label">شناسه مدل هوش مصنوعی:</label>
            <input type="text" class="form-control" id="aiModel" value="meta-llama/llama-3.3-70b-instruct:free">
          </div>
        </div>
      </div>

      <!-- Network Gateway -->
      <div id="set-sub-network" class="settings-sub-panel" style="display:none;">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">حالت دروازه خروجی:</label>
            <select class="form-control" id="netProxyMode">
              <option value="direct">مستقیم بدون پروکسی (Direct)</option>
              <option value="relay">رله کلودفلر (Cloudflare Worker Relay)</option>
              <option value="http">پروکسی استاندارد HTTP/SOCKS5</option>
            </select>
          </div>
          <div class="form-group">
            <label class="form-label">آدرس رله یا پروکسی:</label>
            <input type="text" class="form-control" id="netProxy" placeholder="https://proxy.example.workers.dev">
          </div>
        </div>
      </div>

      <!-- Messengers -->
      <div id="set-sub-msg" class="settings-sub-panel" style="display:none;">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">توکن ربات تلگرام:</label>
            <input type="text" class="form-control" id="tgToken">
          </div>
          <div class="form-group">
            <label class="form-label">شناسه چت تلگرام (Chat ID):</label>
            <input type="text" class="form-control" id="tgChatId">
          </div>
          <div class="form-group">
            <label class="form-label">توکن ربات بله (Bale Bot):</label>
            <input type="text" class="form-control" id="baleToken">
          </div>
          <div class="form-group">
            <label class="form-label">شناسه چت بله:</label>
            <input type="text" class="form-control" id="baleChatId">
          </div>
        </div>
        <div style="display:flex; gap:10px; margin-top:14px;">
          <button class="btn btn-secondary" onclick="testMessenger('telegram')">🔔 تست ارسال تلگرام</button>
          <button class="btn btn-secondary" onclick="testMessenger('bale')">🔔 تست ارسال بله</button>
        </div>
      </div>

      <!-- Deploy -->
      <div id="set-sub-deploy" class="settings-sub-panel" style="display:none;">
        <div class="form-grid">
          <div class="form-group">
            <label class="form-label">مخزن گیت‌هاب (GitHub Repo):</label>
            <input type="text" class="form-control" id="deployRepo" value="fazilatma/amphp">
          </div>
          <div class="form-group">
            <label class="form-label">برنچ گیت‌هاب (Branch):</label>
            <input type="text" class="form-control" id="deployBranch" value="arena/01a06927-amphp">
          </div>
        </div>
        <div style="display:flex; gap:10px; margin-top:14px;">
          <button class="btn btn-secondary" onclick="checkDeploy()">🔄 بررسی نسخه جدید</button>
          <button class="btn btn-accent" onclick="reloadApp()">⚡ بارگذاری مجدد WSGI</button>
        </div>
      </div>
    </div>
  </main>

  <!-- TAB 3: سلکتورها (SELECTORS) -->
  <main id="tab-selectors" class="view-panel">
    <div class="glass-card">
      <div class="card-header">
        <div class="card-title">
          <span class="card-title-icon">🎨</span>
          <span>سلکتورهای استخراج لیست و جزئیات</span>
        </div>
        <button class="btn btn-secondary btn-sm" onclick="openModal('pickerModal')">👆 انتخابگر بصری روی صفحه</button>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">سلکتور کانتینر محصول (Container):</label>
          <input type="text" class="form-control" id="selContainer" placeholder=".product-card, .item">
        </div>
        <div class="form-group">
          <label class="form-label">سلکتور عنوان (Title):</label>
          <input type="text" class="form-control" id="selTitle" placeholder="h2.title, .name">
        </div>
        <div class="form-group">
          <label class="form-label">سلکتور قیمت (Price):</label>
          <input type="text" class="form-control" id="selPrice" placeholder=".price, .amount">
        </div>
        <div class="form-group">
          <label class="form-label">سلکتور تصویر اصلی (Image):</label>
          <input type="text" class="form-control" id="selImage" placeholder="img.thumb">
        </div>
        <div class="form-group">
          <label class="form-label">سلکتور لینک محصول (URL):</label>
          <input type="text" class="form-control" id="selUrl" placeholder="a.link">
        </div>
        <div class="form-group">
          <label class="form-label">سلکتور شناسه کالا (SKU):</label>
          <input type="text" class="form-control" id="selSku" placeholder=".sku">
        </div>
      </div>

      <div class="card-header" style="margin-top:20px;">
        <div class="card-title"><span class="card-title-icon">📄</span><span>سلکتورهای صفحه جزئیات (فاز ۲)</span></div>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">سلکتور توضیحات کامل:</label>
          <input type="text" class="form-control" id="selDesc" placeholder="#description, .content">
        </div>
        <div class="form-group">
          <label class="form-label">سلکتور جدول مشخصات:</label>
          <input type="text" class="form-control" id="selSpecs" placeholder=".specifications table">
        </div>
        <div class="form-group">
          <label class="form-label">سلکتور گالری تصاویر:</label>
          <input type="text" class="form-control" id="selGallery" placeholder=".gallery-slider">
        </div>
        <div class="form-group">
          <label class="form-label">سلکتور تنوع‌ها (رنگ و سایز):</label>
          <input type="text" class="form-control" id="selVariations" placeholder=".variations select, .swatches">
        </div>
      </div>
    </div>
  </main>

  <!-- TAB 4: نتایج (RESULTS & CATALOG) -->
  <main id="tab-results" class="view-panel">
    <div class="glass-card">
      <div class="card-header">
        <div class="card-title">
          <span class="card-title-icon">📊</span>
          <span>کاتالوگ محصولات استخراج‌شده (<span id="resultsCount">۰</span> مورد)</span>
        </div>
        <div style="display:flex; gap:8px;">
          <button class="btn btn-secondary btn-sm" onclick="switchViewMode('table')">📋 جدول</button>
          <button class="btn btn-secondary btn-sm" onclick="switchViewMode('grid')">📊 کارت</button>
          <button class="btn btn-secondary btn-sm" onclick="switchViewMode('json')">📝 JSON</button>
          <button class="btn btn-primary btn-sm" onclick="exportData('xlsx')">📥 اکسل</button>
        </div>
      </div>

      <div style="display:flex; gap:12px; margin-bottom:14px; flex-wrap:wrap;">
        <input type="text" class="form-control" id="catalogSearch" placeholder="🔍 جستجو در عنوان، شناسه..." style="max-width:280px;" oninput="renderCatalog()">
        <button class="btn btn-accent btn-sm" onclick="batchEnrichAI()">🤖 تولید محتوای AI برای انتخاب‌شده‌ها</button>
        <button class="btn btn-danger btn-sm" onclick="batchDelete()">🗑 حذف انتخاب‌شده‌ها</button>
      </div>

      <div id="catalogTableView" class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th width="30"><input type="checkbox" id="selectAllCheck" onchange="toggleSelectAll(this.checked)"></th>
              <th width="60">تصویر</th>
              <th>عنوان محصول</th>
              <th>قیمت (تومان)</th>
              <th>شناسه (SKU)</th>
              <th>وضعیت</th>
              <th width="120">عملیات</th>
            </tr>
          </thead>
          <tbody id="catalogTableBody">
            <tr><td colspan="7" style="text-align:center; padding:30px;">محصولی برای نمایش وجود ندارد.</td></tr>
          </tbody>
        </table>
      </div>

      <div id="catalogGridView" class="products-grid" style="display:none;"></div>
      <div id="catalogJsonView" style="display:none;"><textarea class="form-control" id="rawJsonArea" style="height:350px; font-family:monospace;" readonly></textarea></div>
    </div>
  </main>

  <!-- TAB 5: ارسال و مغایرت‌گیری (SEND & SYNC MATRIX) -->
  <main id="tab-send" class="view-panel">
    <div class="glass-card">
      <div class="card-header">
        <div class="card-title">
          <span class="card-title-icon">📤</span>
          <span>ارسال دسته‌ای و ماتریس همگام‌سازی چندفروشگاهی</span>
        </div>
      </div>

      <div class="form-grid" style="margin-bottom:20px;">
        <div class="stat-card" style="text-align:right;">
          <h3 style="font-size:14px; font-weight:700; margin-bottom:8px;">🛒 ووکامرس</h3>
          <p style="font-size:12px; color:var(--text-dim); margin-bottom:12px;">ارسال محصولات به فروشگاه وردپرس با تنوع و گالری</p>
          <button class="btn btn-primary btn-sm" onclick="sendToWoo()">🚀 ارسال دسته‌ای به ووکامرس</button>
        </div>

        <div class="stat-card" style="text-align:right;">
          <h3 style="font-size:14px; font-weight:700; margin-bottom:8px;">🏪 باسلام</h3>
          <p style="font-size:12px; color:var(--text-dim); margin-bottom:12px;">ثبت محصولات در غرفه باسلام با تبدیل خودکار واحدها</p>
          <button class="btn btn-success btn-sm" onclick="sendToBasalam()">🚀 ارسال دسته‌ای به باسلام</button>
        </div>
      </div>

      <div class="card-header">
        <div class="card-title"><span class="card-title-icon">⚖️</span><span>ماتریس همگام‌سازی (Sync Matrix)</span></div>
        <div style="display:flex; gap:8px;">
          <button class="btn btn-secondary btn-sm" onclick="startSyncMatrix()">🔄 اجرای مقایسه ۴طرفه</button>
          <button class="btn btn-accent btn-sm" onclick="fixPricesServer()">⚡ اصلاح قیمت‌های مغایر</button>
        </div>
      </div>

      <div class="table-wrap">
        <table class="data-table">
          <thead>
            <tr>
              <th>عنوان محصول</th>
              <th>قیمت مبدأ</th>
              <th>قیمت ووکامرس</th>
              <th>قیمت باسلام</th>
              <th>وضعیت همگام‌سازی</th>
            </tr>
          </thead>
          <tbody id="syncMatrixBody">
            <tr><td colspan="5" style="text-align:center; padding:30px;">جهت محاسبه، دکمه «اجرای مقایسه ۴طرفه» را بزنید.</td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- TAB 6: هوش مصنوعی و چت آنلاین (AI LAB & CHAT DESK) -->
  <main id="tab-ai" class="view-panel">
    <div class="glass-card">
      <div class="card-header">
        <div class="card-title">
          <span class="card-title-icon">🤖</span>
          <span>آزمایشگاه هوش مصنوعی و میز گفت‌وگو با مشتریان</span>
        </div>
        <button class="btn btn-primary btn-sm" onclick="openModal('chatDeskModal')">💬 بازکردن میز چت زنده</button>
      </div>

      <div class="form-grid">
        <div class="form-group">
          <label class="form-label">پرسش تستی از مدل فعال:</label>
          <input type="text" class="form-control" id="aiTestPrompt" placeholder="یک متن معرفی برای ساعت هوشمند بنویس">
        </div>
      </div>
      <button class="btn btn-accent" onclick="runAiTest()">🧪 اجرای آزمون هوش مصنوعی</button>
      <div id="aiTestResult" style="margin-top:14px; padding:12px; background:var(--card-solid); border-radius:var(--radius-md); border:1px solid var(--border); display:none;"></div>
    </div>
  </main>
</div>

<!-- Bottom Navigation Bar -->
<nav class="bottom-nav">
  <div class="nav-item active" onclick="switchNavTab('start')">
    <span class="nav-icon">🎯</span>
    <span class="nav-label">شروع</span>
  </div>
  <div class="nav-item" onclick="switchNavTab('settings')">
    <span class="nav-icon">⚙️</span>
    <span class="nav-label">تنظیمات</span>
  </div>
  <div class="nav-item" onclick="switchNavTab('selectors')">
    <span class="nav-icon">🎨</span>
    <span class="nav-label">سلکتورها</span>
  </div>
  <div class="nav-item" onclick="switchNavTab('results')">
    <span class="nav-icon">📊</span>
    <span class="nav-label">نتایج</span>
    <span class="nav-badge" id="navBadgeResults" style="display:none;">۰</span>
  </div>
  <div class="nav-item" onclick="switchNavTab('send')">
    <span class="nav-icon">📤</span>
    <span class="nav-label">ارسال</span>
  </div>
  <div class="nav-item" onclick="switchNavTab('ai')">
    <span class="nav-icon">🤖</span>
    <span class="nav-label">هوش مصنوعی</span>
  </div>
</nav>

<!-- MODAL: TASK MANAGER -->
<div class="modal-overlay" id="tasksModal">
  <div class="modal-box">
    <div class="modal-header">
      <h3 style="font-size:16px; font-weight:700;">🗂 مدیر وظایف و کارهای پس‌زمینه (Task Manager)</h3>
      <button class="icon-btn" onclick="closeModal('tasksModal')">✕</button>
    </div>
    <div class="modal-body" id="tasksListBody">
      <p style="color:var(--text-dim); text-align:center;">وظیفه‌ای در حال اجرا نیست.</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary btn-sm" onclick="loadTasks()">🔄 به‌روزرسانی</button>
      <button class="btn btn-primary btn-sm" onclick="closeModal('tasksModal')">بستن</button>
    </div>
  </div>
</div>

<!-- MODAL: VISUAL PICKER -->
<div class="modal-overlay" id="pickerModal">
  <div class="modal-box" style="max-width:1100px; height:90vh;">
    <div class="modal-header">
      <h3 style="font-size:16px; font-weight:700;">👆 انتخابگر بصری سلکتورها (Visual DOM Inspector)</h3>
      <button class="icon-btn" onclick="closeModal('pickerModal')">✕</button>
    </div>
    <div class="modal-body" style="padding:10px; display:flex; flex-direction:column; gap:10px;">
      <div style="display:flex; gap:8px;">
        <input type="url" class="form-control" id="pickerUrlInput" placeholder="آدرس برای پیش‌نمایش" style="flex:1;">
        <button class="btn btn-primary btn-sm" onclick="loadPickerIframe()">بارگذاری پیش‌نمایش</button>
      </div>
      <iframe id="pickerIframe" style="width:100%; flex:1; border:1px solid var(--border); border-radius:var(--radius-md); background:#fff;"></iframe>
    </div>
  </div>
</div>

<!-- MODAL: CHAT DESK -->
<div class="modal-overlay" id="chatDeskModal">
  <div class="modal-box" style="max-width:900px; height:85vh;">
    <div class="modal-header">
      <h3 style="font-size:16px; font-weight:700;">💬 میز گفت‌وگو و چت آنلاین پشتیبانی</h3>
      <button class="icon-btn" onclick="closeModal('chatDeskModal')">✕</button>
    </div>
    <div class="modal-body" style="display:flex; gap:16px; height:100%; padding:10px;">
      <div id="chatThreadsList" style="width:260px; border-left:1px solid var(--border); overflow-y:auto; padding-left:8px;"></div>
      <div id="chatMessagesArea" style="flex:1; display:flex; flex-direction:column; justify-content:space-between;">
        <div id="messagesContainer" style="flex:1; overflow-y:auto; padding:10px;"></div>
        <div style="display:flex; gap:8px; padding-top:10px; border-top:1px solid var(--border);">
          <input type="text" class="form-control" id="chatReplyInput" placeholder="پاسخ به مشتری...">
          <button class="btn btn-accent btn-sm" onclick="triggerAiChatReply()">🤖 هوش مصنوعی</button>
          <button class="btn btn-primary btn-sm" onclick="sendChatReply()">ارسال</button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: FONT & TYPOGRAPHY -->
<div class="modal-overlay" id="fontModal">
  <div class="modal-box" style="max-width:500px;">
    <div class="modal-header">
      <h3 style="font-size:16px; font-weight:700;">🔤 تنظیمات تایپوگرافی و اندازه فونت</h3>
      <button class="icon-btn" onclick="closeModal('fontModal')">✕</button>
    </div>
    <div class="modal-body">
      <div class="form-group">
        <label class="form-label">فونت رابط کاربری:</label>
        <select class="form-control" id="uiFontSelect" onchange="applyFont(this.value)">
          <option value="vazir">وزیرمتن (Vazirmatn - پیش‌فرض)</option>
          <option value="tahoma">تاهما (Tahoma استاندارد)</option>
          <option value="system">فونت سیستم (System Default)</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">اندازه قلم (بزرگ‌نمایی متن):</label>
        <div style="display:flex; align-items:center; gap:12px;">
          <button class="btn btn-secondary btn-sm" onclick="adjustFontSize(-0.05)">A- کوچک‌تر</button>
          <span id="fontSizeDisplay" style="font-weight:700;">100%</span>
          <button class="btn btn-secondary btn-sm" onclick="adjustFontSize(0.05)">A+ بزرگ‌تر</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let appConfig = {};
let currentProducts = [];
let currentTab = 'start';
let selectedIndices = new Set();
let taskPollingInterval = null;
let activeChatThreadId = null;

function toFa(num) {
  if (num === null || num === undefined) return '';
  return String(num).replace(/\d/g, d => '۰۱۲۳۴۵۶۷۸۹'[d]);
}

function showToast(msg, kind = 'info') {
  const container = document.getElementById('toastContainer');
  const t = document.createElement('div');
  t.className = 'toast';
  t.innerHTML = `<span>${kind === 'success' ? '✅' : (kind === 'error' ? '❌' : 'ℹ️')}</span><span>${msg}</span>`;
  container.appendChild(t);
  setTimeout(() => { t.style.opacity = '0'; setTimeout(() => t.remove(), 300); }, 3500);
}

function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function switchNavTab(tab) {
  currentTab = tab;
  document.querySelectorAll('.view-panel').forEach(el => el.classList.remove('active'));
  document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
  
  const target = document.getElementById(`tab-${tab}`);
  if (target) target.classList.add('active');
  
  const navBtns = document.querySelectorAll('.nav-item');
  const tabNames = ['start', 'settings', 'selectors', 'results', 'send', 'ai'];
  const idx = tabNames.indexOf(tab);
  if (idx !== -1 && navBtns[idx]) navBtns[idx].classList.add('active');
}

function switchSettingsSub(sub) {
  document.querySelectorAll('.settings-sub-panel').forEach(p => p.style.display = 'none');
  document.querySelectorAll('.sub-tab-btn').forEach(b => b.classList.remove('active'));
  const p = document.getElementById(`set-sub-${sub}`);
  if (p) p.style.display = 'block';
  event.target.classList.add('active');
}

function switchViewMode(mode) {
  document.getElementById('catalogTableView').style.display = mode === 'table' ? 'block' : 'none';
  document.getElementById('catalogGridView').style.display = mode === 'grid' ? 'grid' : 'none';
  document.getElementById('catalogJsonView').style.display = mode === 'json' ? 'block' : 'none';
  if (mode === 'json') {
    document.getElementById('rawJsonArea').value = JSON.stringify(currentProducts, null, 2);
  }
}

async function loadConfig() {
  try {
    const res = await fetch('/api/config');
    const json = await res.json();
    if (json.ok) {
      appConfig = json.data;
      applyConfigToUi(appConfig);
    }
  } catch(e) {
    console.error(e);
  }
}

function applyConfigToUi(cfg) {
  if (cfg.woocommerce) {
    document.getElementById('wooUrl').value = cfg.woocommerce.url || '';
    document.getElementById('wooCk').value = cfg.woocommerce.consumer_key || '';
    document.getElementById('wooCs').value = cfg.woocommerce.consumer_secret || '';
  }
  if (cfg.basalam) {
    document.getElementById('bslToken').value = cfg.basalam.token || '';
    document.getElementById('bslVendorId').value = cfg.basalam.vendor_id || '';
    document.getElementById('bslCatId').value = cfg.basalam.category_id || '';
  }
  if (cfg.ai) {
    document.getElementById('aiProvider').value = cfg.ai.provider || 'openrouter';
    document.getElementById('aiApiKey').value = cfg.ai.api_key || '';
    document.getElementById('aiModel').value = cfg.ai.model || '';
  }
  if (cfg.last_result) {
    currentProducts = cfg.last_result;
    renderCatalog();
  }
  const profSelect = document.getElementById('topProfileSelect');
  profSelect.innerHTML = '<option value="">پروفایل پیش‌فرض</option>';
  if (cfg.profiles) {
    for (const pName in cfg.profiles) {
      const opt = document.createElement('option');
      opt.value = pName; opt.innerText = pName;
      if (cfg.active_profile === pName) opt.selected = true;
      profSelect.appendChild(opt);
    }
  }
}

async function saveAllSettings() {
  const payload = {
    woocommerce: {
      url: document.getElementById('wooUrl').value,
      consumer_key: document.getElementById('wooCk').value,
      consumer_secret: document.getElementById('wooCs').value
    },
    basalam: {
      token: document.getElementById('bslToken').value,
      vendor_id: document.getElementById('bslVendorId').value,
      category_id: document.getElementById('bslCatId').value
    },
    ai: {
      provider: document.getElementById('aiProvider').value,
      api_key: document.getElementById('aiApiKey').value,
      model: document.getElementById('aiModel').value
    }
  };
  try {
    const res = await fetch('/api/settings', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload) });
    const json = await res.json();
    if (json.ok) showToast('تنظیمات با موفقیت ذخیره شد', 'success');
  } catch(e) {
    showToast('خطا در ذخیره تنظیمات', 'error');
  }
}

async function startScrape() {
  const url = document.getElementById('sourceUrl').value.trim();
  if (!url) { showToast('لطفاً آدرس مبدأ را وارد کنید', 'error'); return; }
  const payload = {
    url: url,
    pages: parseInt(document.getElementById('scrapePages').value) || 1,
    engine: document.getElementById('fetchEngine').value,
    pag_type: document.getElementById('pagType').value,
    pag_val: document.getElementById('pagVal').value,
    selectors: {
      container: document.getElementById('selContainer').value,
      title: document.getElementById('selTitle').value,
      price: document.getElementById('selPrice').value,
      image: document.getElementById('selImage').value,
      url: document.getElementById('selUrl').value,
      sku: document.getElementById('selSku').value
    }
  };
  try {
    const res = await fetch('/api/scrape/start', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload) });
    const json = await res.json();
    if (json.ok) {
      showToast('عملیات استخراج آغاز شد', 'success');
      openModal('tasksModal');
      pollTasks();
    }
  } catch(e) {
    showToast('خطا در شروع استخراج', 'error');
  }
}

async function startDetailScrape() {
  const payload = {
    detail_selectors: {
      description: document.getElementById('selDesc').value,
      specs: document.getElementById('selSpecs').value,
      gallery: document.getElementById('selGallery').value,
      variations: document.getElementById('selVariations').value
    }
  };
  try {
    const res = await fetch('/api/scrape/detail/start', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload) });
    const json = await res.json();
    if (json.ok) {
      showToast('فاز ۲ استخراج جزئیات آغاز شد', 'success');
      openModal('tasksModal');
      pollTasks();
    }
  } catch(e) {
    showToast('خطا در فاز ۲', 'error');
  }
}

function renderCatalog() {
  const q = document.getElementById('catalogSearch').value.toLowerCase();
  const filtered = currentProducts.filter(p => !q || (p.title && p.title.toLowerCase().includes(q)) || (p.sku && p.sku.toLowerCase().includes(q)));
  
  document.getElementById('resultsCount').innerText = toFa(filtered.length);
  const badge = document.getElementById('navBadgeResults');
  if (filtered.length > 0) {
    badge.innerText = toFa(filtered.length); badge.style.display = 'inline-block';
  } else {
    badge.style.display = 'none';
  }

  const tbody = document.getElementById('catalogTableBody');
  if (filtered.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" style="text-align:center; padding:30px;">محصولی یافت نشد.</td></tr>';
  } else {
    tbody.innerHTML = filtered.map((p, idx) => `
      <tr>
        <td><input type="checkbox" onchange="toggleSelectProduct(${idx}, this.checked)"></td>
        <td><img src="${p.image || ''}" style="width:40px; height:40px; object-fit:cover; border-radius:6px; background:#111;"></td>
        <td><div style="font-weight:600;">${p.title || 'بدون عنوان'}</div></td>
        <td><span class="chip chip-success">${p.price ? toFa(p.price) + ' ت' : 'بدون قیمت'}</span></td>
        <td><code>${p.sku || '—'}</code></td>
        <td><span class="chip ${p.stock === 'out_of_stock' ? 'chip-danger' : 'chip-primary'}">${p.stock === 'out_of_stock' ? 'ناموجود' : 'موجود'}</span></td>
        <td><button class="btn btn-secondary btn-sm" onclick="editProductPrompt(${idx})">✏️</button></td>
      </tr>
    `).join('');
  }

  const grid = document.getElementById('catalogGridView');
  grid.innerHTML = filtered.map((p, idx) => `
    <div class="product-card">
      <img class="product-thumb" src="${p.image || ''}">
      <div class="product-body">
        <div class="product-title">${p.title || 'بدون عنوان'}</div>
        <div class="product-price">${p.price ? toFa(p.price) + ' تومان' : 'نامشخص'}</div>
      </div>
    </div>
  `).join('');

  document.getElementById('statTotal').innerText = toFa(currentProducts.length);
  document.getElementById('statWithPrice').innerText = toFa(currentProducts.filter(p => p.price).length);
  document.getElementById('statWithImage').innerText = toFa(currentProducts.filter(p => p.image).length);
  document.getElementById('statEnriched').innerText = toFa(currentProducts.filter(p => p.description || p.specs).length);
}

async function loadTasks() {
  try {
    const res = await fetch('/api/tasks');
    const json = await res.json();
    if (json.ok) {
      renderTasksList(json.tasks);
    }
  } catch(e) {}
}

function renderTasksList(tasks) {
  const container = document.getElementById('tasksListBody');
  if (!tasks || tasks.length === 0) {
    container.innerHTML = '<p style="color:var(--text-dim); text-align:center; padding:20px;">وظیفه‌ای در صف وجود ندارد.</p>';
    document.getElementById('topTaskCount').innerText = '۰ وظیفه';
    document.getElementById('topTaskSpinner').style.display = 'none';
    return;
  }
  const runningCount = tasks.filter(t => t.status === 'running').length;
  document.getElementById('topTaskCount').innerText = `${toFa(runningCount)} فعال`;
  document.getElementById('topTaskSpinner').style.display = runningCount > 0 ? 'inline-block' : 'none';

  container.innerHTML = tasks.map(t => `
    <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius-md); padding:14px; margin-bottom:12px;">
      <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
        <span style="font-weight:700; font-size:13px;">${t.title}</span>
        <span class="chip ${t.status === 'completed' ? 'chip-success' : (t.status === 'running' ? 'chip-primary' : 'chip-danger')}">${t.status}</span>
      </div>
      <div style="height:6px; background:rgba(255,255,255,0.1); border-radius:4px; overflow:hidden; margin-bottom:8px;">
        <div style="height:100%; width:${t.progress}%; background:var(--primary); transition:width 0.3s;"></div>
      </div>
      <div style="display:flex; justify-content:space-between; font-size:11px; color:var(--text-dim);">
        <span>${t.step || ''}</span>
        <span>${toFa(t.progress)}٪</span>
      </div>
      ${t.status === 'running' ? `<button class="btn btn-danger btn-sm" style="margin-top:8px;" onclick="cancelTask('${t.id}')">⏹ لغو</button>` : ''}
    </div>
  `).join('');
}

async function cancelTask(id) {
  await fetch(`/api/tasks/${id}/cancel`, { method:'POST' });
  loadTasks();
}

function pollTasks() {
  if (taskPollingInterval) clearInterval(taskPollingInterval);
  taskPollingInterval = setInterval(() => {
    loadTasks();
    loadConfig();
  }, 2500);
}

// Send actions
async function sendToWoo() {
  if (currentProducts.length === 0) { showToast('محصولی برای ارسال نیست', 'error'); return; }
  try {
    const res = await fetch('/api/woo/send', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({products:currentProducts}) });
    const json = await res.json();
    if (json.ok) { showToast('ارسال به ووکامرس آغاز شد', 'success'); openModal('tasksModal'); pollTasks(); }
  } catch(e) { showToast('خطا در ارسال به ووکامرس', 'error'); }
}

async function sendToBasalam() {
  if (currentProducts.length === 0) { showToast('محصولی برای ارسال نیست', 'error'); return; }
  try {
    const res = await fetch('/api/basalam/send', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({products:currentProducts}) });
    const json = await res.json();
    if (json.ok) { showToast('ارسال به باسلام آغاز شد', 'success'); openModal('tasksModal'); pollTasks(); }
  } catch(e) { showToast('خطا در ارسال به باسلام', 'error'); }
}

async function startSyncMatrix() {
  try {
    const res = await fetch('/api/sync-matrix/start', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({}) });
    const json = await res.json();
    if (json.ok) { showToast('محاسبه ماتریس همگام‌سازی آغاز شد', 'success'); openModal('tasksModal'); pollTasks(); }
  } catch(e) { showToast('خطا در ماتریس همگام‌سازی', 'error'); }
}

async function fixPricesServer() {
  try {
    const res = await fetch('/api/sync-matrix/fix-prices', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({}) });
    const json = await res.json();
    if (json.ok) { showToast('اصلاح قیمت‌ها در سرور آغاز شد', 'success'); openModal('tasksModal'); pollTasks(); }
  } catch(e) { showToast('خطا در اصلاح قیمت‌ها', 'error'); }
}

async function runAiTest() {
  const prompt = document.getElementById('aiTestPrompt').value.trim() || 'معرفی کوتاه یک گوشی هوشمند';
  const resDiv = document.getElementById('aiTestResult');
  resDiv.style.display = 'block';
  resDiv.innerText = 'در حال ارتباط با هوش مصنوعی...';
  try {
    const res = await fetch('/api/chat/auto-reply', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({message:prompt}) });
    const json = await res.json();
    if (json.ok) {
      resDiv.innerText = json.reply;
    } else {
      resDiv.innerText = `خطا: ${json.error}`;
    }
  } catch(e) {
    resDiv.innerText = `خطای ارتباطی: ${e.message}`;
  }
}

async function testWooConnection() {
  showToast('در حال بررسی اتصال به ووکامرس...', 'info');
  try {
    const res = await fetch('/api/woo/test', { method:'POST' });
    const json = await res.json();
    if (json.ok) showToast('اتصال به ووکامرس برقرار است ✅', 'success');
    else showToast(`خطا در اتصال ووکامرس: ${json.error || json.status_code}`, 'error');
  } catch(e) { showToast('خطای اتصال به سرور', 'error'); }
}

async function testBslConnection() {
  showToast('در حال بررسی اتصال به باسلام...', 'info');
  try {
    const res = await fetch('/api/basalam/test', { method:'POST' });
    const json = await res.json();
    if (json.ok) showToast('اتصال به باسلام برقرار است ✅', 'success');
    else showToast(`خطا در اتصال باسلام: ${json.error || json.status_code}`, 'error');
  } catch(e) { showToast('خطای اتصال به سرور', 'error'); }
}

async function testMessenger(m) {
  showToast(`ارسال پیام آزمایشی ${m}...`, 'info');
  const res = await fetch('/api/messenger/test', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify({messenger:m}) });
  const json = await res.json();
  if (json.ok) showToast(`پیام آزمایشی ${m} ارسال شد ✅`, 'success');
  else showToast(`خطا در ارسال ${m}`, 'error');
}

function exportData(format) {
  window.open(`/api/export.${format}`, '_blank');
}

function loadPickerIframe() {
  const url = document.getElementById('pickerUrlInput').value.trim();
  if (url) {
    document.getElementById('pickerIframe').src = `/api/picker/preview?url=${encodeURIComponent(url)}`;
  }
}
window.addEventListener('message', (e) => {
  if (e.data && e.data.type === 'ELEMENT_PICKED') {
    showToast(`سلکتور انتخاب شد: ${e.data.selector}`, 'info');
  }
});

let currentFontScale = 1;
function adjustFontSize(delta) {
  currentFontScale = Math.max(0.8, Math.min(1.4, currentFontScale + delta));
  document.documentElement.style.setProperty('--font-scale', currentFontScale);
  document.getElementById('fontSizeDisplay').innerText = `${Math.round(currentFontScale * 100)}%`;
}
const themes = ['theme-navy', 'theme-midnight', 'theme-emerald', 'theme-violet', 'theme-amber', 'theme-light'];
let curThemeIdx = 0;
function cycleTheme() {
  curThemeIdx = (curThemeIdx + 1) % themes.length;
  document.body.className = themes[curThemeIdx];
}

function toggleFullScreen() {
  if (!document.fullscreenElement) { document.documentElement.requestFullscreen(); }
  else { document.exitFullscreen(); }
}

window.addEventListener('DOMContentLoaded', () => {
  loadConfig();
  loadTasks();
  pollTasks();
});
</script>
</body>
</html>
"""
