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
import json
import os
import re
import socket
import subprocess
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

APP_VERSION = "1.6.0"
BASE_DIR = os.path.dirname(os.path.abspath(__file__))
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

app = Flask(__name__)
app.config["JSON_AS_ASCII"] = False


# ---------------------------------------------------------------------------
# Persistent configuration
# ---------------------------------------------------------------------------
def default_data() -> dict[str, Any]:
    return {
        "profiles": {},
        "woocommerce": {"url": "", "consumer_key": "", "consumer_secret": ""},
        "network": {"timeout": 25, "gap_ms": 350, "proxy": "", "verify_tls": True},
        "deploy": {
            "repo": "fazilatma/amphp", "branch": "arena/01a0640f-amphp", "path": "scraper4.py",
            "github_token": "", "reload_file": "",
        },
        "last_result": [],
        "extract_jobs": {},
        "woo_jobs": {},
    }


def load_data() -> dict[str, Any]:
    with DATA_LOCK:
        try:
            with open(DATA_FILE, "r", encoding="utf-8") as fh:
                raw = json.load(fh)
            out = default_data()
            for key in out:
                if key in raw and isinstance(raw[key], type(out[key])):
                    out[key] = raw[key]
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


class Fetcher:
    def __init__(self, cfg: dict[str, Any]):
        self.timeout = max(5, min(90, int(cfg.get("timeout", 25))))
        self.gap = max(0, min(10000, int(cfg.get("gap_ms", 350)))) / 1000.0
        self.verify = bool(cfg.get("verify_tls", True))
        self.proxy = str(cfg.get("proxy", "")).strip()
        self.session = requests.Session()
        self.session.headers.update({
            "User-Agent": USER_AGENT,
            "Accept": "text/html,application/xhtml+xml,application/json;q=0.9,*/*;q=0.8",
            "Accept-Language": "fa-IR,fa;q=0.9,en-US;q=0.7,en;q=0.6",
            "Cache-Control": "no-cache",
        })
        if self.proxy:
            self.session.proxies.update({"http": self.proxy, "https": self.proxy})
        self.last_by_host: dict[str, float] = {}

    def get(self, url: str, *, referer: str = "", accept_json: bool = False) -> FetchResult:
        url = public_http_url(url)
        host = urlparse(url).hostname or ""
        elapsed = time.monotonic() - self.last_by_host.get(host, 0)
        if elapsed < self.gap:
            time.sleep(self.gap - elapsed)
        headers = {}
        if referer:
            headers["Referer"] = referer
        if accept_json:
            headers["Accept"] = "application/json,text/plain,*/*"
        last_error = ""
        for attempt in range(3):
            try:
                response = self.session.get(
                    url, headers=headers, timeout=self.timeout,
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
                    response.url, text, response.headers.get("Content-Type", ""),
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
    price = extract_price(select_value(node, selectors.get("price", ""), "price", base))
    if not price:
        candidate = node.select_one("[class*='price'],[class*='amount'],ins,[itemprop='price']")
        price = extract_price((candidate.get("content") or candidate.get_text(" ", strip=True)) if candidate else "")
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
        pattern = value or "/page/{page}/"
        root = f"{parsed.scheme}://{parsed.netloc}"
        return urljoin(root, pattern.replace("{page}", str(page)))
    param = value or "page"
    query = parse_qs(parsed.query, keep_blank_values=True)
    query[param] = [str(page)]
    return urlunparse(parsed._replace(query=urlencode(query, doseq=True)))



def render_playwright(url: str, timeout: int, scrolls: int = 4) -> FetchResult:
    try:
        from playwright.sync_api import sync_playwright
    except ImportError as exc:
        raise FetchError("Playwright نصب نیست؛ از بخش به‌روزرسانی «نصب وابستگی‌ها» را اجرا کنید") from exc
    public_http_url(url)
    try:
        with sync_playwright() as pw:
            browser = pw.chromium.launch(headless=True, args=["--no-sandbox", "--disable-dev-shm-usage"])
            page = browser.new_page(user_agent=USER_AGENT, locale="fa-IR")
            page.goto(url, wait_until="networkidle", timeout=timeout * 1000)
            for _ in range(max(0, min(12, scrolls))):
                page.evaluate("window.scrollTo(0, document.body.scrollHeight)")
                page.wait_for_timeout(700)
            html = page.content()
            final_url = page.url
            browser.close()
        return FetchResult(final_url, html, "text/html", 200, "browser")
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
    """Atomic per-page checkpoint, adapted from scraper4.php extractCheckpoint."""
    data = load_data()
    jobs = data.setdefault("extract_jobs", {})
    public_config = {k: v for k, v in config.items() if not str(k).startswith("_") and k != "job_id"}
    jobs[job_id] = {"id": job_id, "status": status, "updated_at": int(time.time()), "next_page": next_page, "next_url": next_url, "total": len(report.products), "config": public_config, "products": list(report.products.values()), "logs": report.logs[-20:]}
    # Free-plan storage guard: retain only the 12 newest resumable reports.
    if len(jobs) > 12:
        for key in sorted(jobs, key=lambda x: int(jobs[x].get("updated_at", 0)))[:-12]:
            jobs.pop(key, None)
    save_data(data)


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
                result = fetcher.get(url)
                rows, soup, diag = parse_html(result.text, result.url, selectors)
                report.modes.add("html")
                report.logs.append(f"صفحه {number}: {len(rows)} محصول از DOM صفحه")
            except (FetchError, ValueError) as exc:
                fetch_error = str(exc)

        if not rows and mode in ("auto", "browser"):
            try:
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
    save_extract_checkpoint(job_id, config, report, report.pages + 1, next_url, "completed")
    return report


# ---------------------------------------------------------------------------
# WooCommerce and exports
# ---------------------------------------------------------------------------
def woo_request(method: str, endpoint: str, payload: Any = None) -> requests.Response:
    cfg = load_data()["woocommerce"]
    base = str(cfg.get("url", "")).rstrip("/")
    ck, cs = str(cfg.get("consumer_key", "")), str(cfg.get("consumer_secret", ""))
    if not base or not ck or not cs:
        raise ValueError("اتصال WooCommerce کامل نیست")
    public_http_url(base)
    url = base + "/wp-json/wc/v3/" + endpoint.lstrip("/")
    response = requests.request(method, url, json=payload, auth=(ck, cs), timeout=60, headers={"User-Agent": USER_AGENT})
    if not response.ok:
        raise FetchError(f"WooCommerce HTTP {response.status_code}: {response.text[:300]}")
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
        response = requests.get(api_url, params={"ref": cfg["branch"]}, headers=headers, timeout=30)
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
            return {"changed": False, "message": "همین نسخه اکنون نصب است", "version": APP_VERSION}
        old_mode = os.stat(target).st_mode & 0o777
        backup = target + ".bak"
        atomic_write(backup, current, old_mode)
        atomic_write(target, content, old_mode)
        reloaded = touch_reload_file(cfg["reload_file"]) if cfg["reload_file"] else False
        return {"changed": True, "message": "نسخه تازه اتمیک نصب شد", "version": new_version,
                "sha": remote["sha"], "backup": os.path.basename(backup), "reload_requested": reloaded}
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
        response = requests.post(url, headers={"Authorization": "Token " + token}, timeout=30)
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
    deploy = dict(data["deploy"])
    deploy["has_github_token"] = bool(os.environ.get("GITHUB_TOKEN", "") or deploy.get("github_token"))
    deploy["github_token"] = ""
    return jsonify(ok=True, profiles=data["profiles"], network=data["network"], woocommerce=woo,
                   deploy=deploy, last_count=len(data["last_result"]), version=APP_VERSION,
                   build=BUILD_ID, auto_update=AUTO_UPDATE_ENABLED)


@app.post("/api/settings")
def settings():
    body = request.get_json(silent=True) or {}
    data = load_data()
    if isinstance(body.get("network"), dict):
        for key in ("timeout", "gap_ms", "proxy", "verify_tls"):
            if key in body["network"]:
                data["network"][key] = body["network"][key]
    if isinstance(body.get("woocommerce"), dict):
        for key in ("url", "consumer_key", "consumer_secret"):
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


@app.post("/api/deploy/dependencies")
def api_deploy_dependencies():
    if not deploy_authorized():
        return deploy_auth_error()
    packages = ["flask", "requests", "beautifulsoup4", "lxml", "html5lib", "soupsieve", "playwright", "cloudscraper", "selenium"]
    env = dict(os.environ)
    env.setdefault("PLAYWRIGHT_BROWSERS_PATH", os.path.join(BASE_DIR, "ms-playwright"))
    candidates = [os.path.join(BASE_DIR, "venv", "bin", "python"), os.path.join(sys.prefix, "bin", "python"), sys.executable]
    python_bin = next((x for x in candidates if os.path.isfile(x) and os.access(x, os.X_OK) and "uwsgi" not in os.path.basename(x).lower()), "")
    if not python_bin:
        return jsonify(ok=False, error="Python virtualenv پیدا نشد؛ نصب‌کننده کامل را اجرا کنید"), 500
    try:
        pip_run = subprocess.run([python_bin, "-m", "pip", "install", "--upgrade", *packages], capture_output=True, text=True, timeout=420, env=env)
        if pip_run.returncode:
            raise RuntimeError((pip_run.stderr or pip_run.stdout)[-2000:])
        browser_run = subprocess.run([python_bin, "-m", "playwright", "install", "chromium"], capture_output=True, text=True, timeout=600, env=env)
        if browser_run.returncode:
            raise RuntimeError((browser_run.stderr or browser_run.stdout)[-2000:])
        return jsonify(ok=True, message="کتابخانه‌های استخراج و Chromium نصب شدند", output=(browser_run.stdout or pip_run.stdout)[-1500:])
    except (OSError, subprocess.TimeoutExpired, RuntimeError) as exc:
        return jsonify(ok=False, error=f"نصب وابستگی‌ها ناموفق بود: {exc}"), 400


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


@app.post("/api/profile")
def profile_save():
    body = request.get_json(silent=True) or {}
    name = clean_text(body.get("name"))[:100]
    config = body.get("config")
    if not name or not isinstance(config, dict):
        return jsonify(ok=False, error="نام و تنظیمات پروفایل لازم است"), 400
    public_http_url(str(config.get("url", "")))
    data = load_data()
    data["profiles"][name] = config
    save_data(data)
    return jsonify(ok=True, profiles=data["profiles"])


@app.delete("/api/profile/<path:name>")
def profile_delete(name: str):
    data = load_data()
    data["profiles"].pop(name, None)
    save_data(data)
    return jsonify(ok=True, profiles=data["profiles"])


@app.post("/api/scrape")
def api_scrape():
    body = request.get_json(silent=True) or {}
    try:
        report = scrape(body)
        products = list(report.products.values())
        data = load_data()
        previous = {product_key(p): p for p in data["last_result"] if isinstance(p, dict)}
        current = {product_key(p): p for p in products}
        added = [p for key, p in current.items() if key not in previous]
        removed = [p for key, p in previous.items() if key not in current]
        changed = [p for key, p in current.items() if key in previous and any(
            str(p.get(field, "")) != str(previous[key].get(field, ""))
            for field in ("price", "stock", "image", "title")
        )]
        comparison = {"added": len(added), "changed": len(changed), "removed": len(removed)}
        data["last_result"] = products
        save_data(data)
        return jsonify(ok=True, products=products, total=len(products), pages=report.pages,
                       modes=sorted(report.modes), logs=report.logs, diagnostics=report.diagnostics,
                       comparison=comparison, job_id=report.job_id)
    except (ValueError, FetchError) as exc:
        return jsonify(ok=False, error=str(exc)), 400
    except Exception as exc:
        app.logger.exception("scrape failed")
        return jsonify(ok=False, error=f"خطای داخلی: {exc}"), 500


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
    upload = request.files.get("file")
    if not upload:
        return jsonify(ok=False, error="فایل CSV ارسال نشده است"), 400
    text = upload.read(5 * 1024 * 1024 + 1).decode("utf-8-sig", errors="replace")
    if len(text.encode("utf-8")) > 5 * 1024 * 1024:
        return jsonify(ok=False, error="فایل بزرگ‌تر از ۵ مگابایت است"), 400
    rows = list(csv.DictReader(io.StringIO(text)))
    products = []
    for row in rows[:MAX_PRODUCTS_HARD]:
        lowered = {clean_text(key).lower(): value for key, value in row.items() if key}
        product = {field: clean_text(lowered.get(field, "")) for field in ("title", "price", "link", "image", "sku", "stock", "brand")}
        if product["title"] or product["link"]:
            product["key"] = product_key(product)
            products.append(product)
    data = load_data(); data["last_result"] = products; save_data(data)
    return jsonify(ok=True, products=products, total=len(products))


@app.post("/api/woo/test")
def woo_test():
    try:
        response = woo_request("GET", "system_status")
        return jsonify(ok=True, status=response.json())
    except (ValueError, FetchError, requests.RequestException) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.post("/api/woo/queue")
def woo_queue_create():
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
    jobs = load_data().get("woo_jobs", {})
    rows = [{k:v for k,v in job.items() if k not in ("products","results")} for job in jobs.values()]
    rows.sort(key=lambda x: int(x.get("updated_at", 0)), reverse=True)
    return jsonify(ok=True, jobs=rows)


@app.post("/api/woo/process/<job_id>")
def woo_queue_process(job_id: str):
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
    data=load_data(); data.get("woo_jobs", {}).pop(job_id, None); save_data(data); return jsonify(ok=True)


@app.post("/api/woo/send")
def woo_send():
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
@media(max-width:900px){.grid4{grid-template-columns:repeat(2,minmax(0,1fr))}.hero{padding:20px}.wrap{padding:18px 14px 60px}}
@media(max-width:640px){html,body{max-width:100%;overflow-x:hidden}body{font-size:13px}.wrap{padding:10px 8px calc(28px + env(safe-area-inset-bottom))}.hero{border-radius:18px;padding:14px 12px;margin-bottom:10px}.hero-main{gap:10px}.logo{width:43px;height:43px;border-radius:13px;font-size:22px}.eyebrow{display:none}.hero h1{font-size:20px}.sub{font-size:10px;line-height:1.55;max-width:230px}.hero-badge{display:none}.tabs{position:fixed;top:auto;right:auto;left:50%;bottom:0;width:100%;margin:0;border-radius:15px;padding:5px;justify-content:flex-start;z-index:50;overflow-x:auto;overscroll-behavior-x:contain;scroll-snap-type:x proximity}.tabs button{flex:1 0 62px;min-width:62px;min-height:48px;padding:5px 3px;font-size:9px;white-space:nowrap;flex-direction:column;gap:1px;scroll-snap-align:start}.tabs button i{font-size:18px}.card{padding:14px 12px;border-radius:16px;margin-bottom:10px}.grid,.grid4{grid-template-columns:1fr;gap:12px}.wide{grid-column:auto}input,select,textarea{font-size:16px;min-height:48px}.actions{display:grid;grid-template-columns:1fr}.actions button{width:100%;padding:11px 8px;min-height:46px}.actions button:first-child:last-child{grid-column:1/-1}.note{font-size:12px;padding:10px}.tablebox{max-height:none;overflow:visible;background:transparent;border:0;box-shadow:none;padding:0}table,thead,tbody,tr,td{display:block}thead{display:none}tbody{display:grid;gap:10px}tbody tr{position:relative;padding:13px 88px 13px 12px;min-height:104px;border:1px solid var(--line);border-radius:16px;background:var(--card);box-shadow:0 8px 24px #0003}tbody tr:hover{background:var(--card)}td{padding:3px 0;border:0;text-align:right}td:before{content:attr(data-label);color:var(--muted);font-size:10px;margin-left:6px}td:nth-child(1){position:absolute;left:9px;top:8px;color:#7188a5;font-size:10px}td:nth-child(1):before,td:nth-child(2):before{display:none}td:nth-child(2){position:absolute;right:12px;top:13px}td:nth-child(2) img{width:64px;height:76px;border-radius:11px}td:nth-child(3){font-weight:700;font-size:13px;line-height:1.65;margin-bottom:4px}td:nth-child(4){color:#7ce6ba;font-weight:700;direction:ltr;text-align:right}td:empty{display:none}.empty{padding:35px 10px!important}.empty:before{display:none}}
@media(max-height:480px) and (max-width:900px){.hero{display:none}.tabs{bottom:0}.tabs button{min-height:40px;flex-direction:row;font-size:10px}.tabs button i{font-size:15px}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important;scroll-behavior:auto!important}}

/* v1.6: visual parity with scraper4.php */
body{background:#0f172a;background-image:none;color:#e2e8f0;padding:12px 12px 90px}body:before{display:none}.wrap{max-width:1400px;padding:0;margin:auto}.hero{background:#1e293b;border:1px solid #334155;border-radius:12px;padding:12px 14px;margin:0 0 14px;box-shadow:none}.hero:after{display:none}.logo{width:40px;height:40px;border-radius:8px;font-size:20px;box-shadow:none}.eyebrow{display:none}.hero h1{font-size:18px}.sub{font-size:11px}.card{background:#1e293b;border:1px solid #334155;border-radius:12px;padding:14px;margin-bottom:14px;box-shadow:none;backdrop-filter:none}input,select,textarea{background:#0f172a;border:1px solid #475569;border-radius:8px;color:#fff}button,.file-btn{border-radius:8px;box-shadow:none}.primary-actions{background:#1e293b;border-color:#334155;border-radius:12px;box-shadow:none}.note,.status{background:#0f172a;border-color:#334155;border-radius:10px}.tabs{left:0;right:0;bottom:0;transform:none;width:100%;max-width:none;background:#0f172a;border:0;border-top:1px solid #334155;border-radius:0;padding:0 env(safe-area-inset-right) env(safe-area-inset-bottom) env(safe-area-inset-left);gap:0;box-shadow:0 -4px 20px rgba(0,0,0,.5)}.tabs button{flex:1 0 64px;min-height:60px;border:0;border-radius:0;color:#64748b;flex-direction:column;gap:2px;padding:8px 4px;font-size:11px}.tabs button.on{color:#3b82f6;background:#1e293b;border:0}.tabs button.on i{transform:translateY(-2px) scale(1.15);filter:drop-shadow(0 3px 8px rgba(59,130,246,.7))}.tabs button i{font-size:21px}.tablebox{background:#1e293b}.app-footer{height:72px}
</style></head><body><div class="wrap">
<header class="hero"><div class="hero-main"><div class="logo">🕸️</div><div><div class="eyebrow">مرکز استخراج محصول</div><h1>Scraper4 <small id="appVersion">v1.6.0</small></h1><div class="sub">استخراج مستقیم DOM و سلکتورها، مطابق نسخه PHP</div></div></div><div class="hero-badge"><span>●</span> آنلاین و آماده</div></header>

<section id="scrape" class="pane on"><div class="card"><div class="grid grid4">
<div class="wide"><label>آدرس صفحهٔ فهرست/جست‌وجو</label><input id="url" placeholder="https://www.digikala.com/search/?q=..." dir="ltr"></div>
<div><label>تعداد صفحه</label><input id="pages" type="number" min="1" max="50" value="1"></div>
<div><label>روش محتوا</label><select id="render"><option value="auto">خودکار: HTML سپس Playwright</option><option value="http">HTML مستقیم (روش PHP)</option><option value="browser">DOM رندرشده با Playwright</option></select></div>
<div><label>صفحه‌بندی</label><select id="pagination"><option value="query">پارامتر Query</option><option value="path">الگوی مسیر</option><option value="next">سلکتور لینک صفحه بعد</option></select></div>
<div><label>نام پارامتر / الگو</label><input id="page_value" value="page" dir="ltr" placeholder="page یا /page/{page}/"></div>
<div><label>تعداد اسکرول در Browser</label><input id="scrolls" type="number" value="4" min="0" max="12"></div>
<div><label>تکمیل تصویر/قیمت از صفحه جزئیات</label><select id="enrich"><option value="0">خاموش</option><option value="1">روشن</option></select></div>
<div><label>سقف صفحات جزئیات</label><input id="detail_limit" type="number" value="20" min="0" max="100"></div>
</div></div>
<details class="card advanced"><summary><span><b>سلکتورهای CSS</b><small>اختیاری — فقط برای سایت‌های خاص</small></span><i>⌄</i></summary><div class="advanced-body"><div class="note">مانند نسخه PHP، استخراج فقط از DOM و سلکتورها انجام می‌شود. برای سایت‌های JavaScript حالت Playwright را انتخاب کنید.</div><div class="grid grid4">
<div><label>ظرف محصول *</label><input id="sel_container" dir="ltr" placeholder="article.product"></div><div><label>عنوان</label><input id="sel_title" dir="ltr" placeholder="h2.title"></div><div><label>قیمت</label><input id="sel_price" dir="ltr" placeholder=".price"></div><div><label>لینک</label><input id="sel_link" dir="ltr" placeholder="a"></div><div><label>تصویر</label><input id="sel_image" dir="ltr" placeholder="img"></div><div><label>SKU</label><input id="sel_sku" dir="ltr"></div>
</div><h3 class="section-mini">سلکتورهای صفحه جزئیات</h3><div class="grid grid4"><div><label>گالری تصاویر</label><input id="det_gallery" dir="ltr" placeholder=".gallery img"></div><div><label>تنوع‌ها</label><input id="det_variations" dir="ltr" placeholder=".variations | .sizes"></div><div><label>وزن</label><input id="det_weight" dir="ltr"></div><div><label>دسته‌بندی</label><input id="det_category" dir="ltr"></div><div><label>قیمت جزئیات</label><input id="det_price" dir="ltr"></div><div><label>موجودی</label><input id="det_stock" dir="ltr"></div><div><label>برند</label><input id="det_brand" dir="ltr"></div><div><label>SKU</label><input id="det_sku" dir="ltr"></div><div><label>توضیح کوتاه</label><input id="det_short_desc" dir="ltr"></div><div><label>توضیح بلند</label><input id="det_long_desc" dir="ltr"></div></div></div></details><div class="primary-actions actions"><button id="runBtn" onclick="runScrape()">🚀 شروع برداشت</button><button class="gray" onclick="saveProfilePrompt()">☆ ذخیره پروفایل</button><button class="green" onclick="downloadCSV()">↓ CSV</button><button class="gray" onclick="downloadJSON()">↓ JSON</button><button class="gray" onclick="downloadXLSX()">↓ Excel</button><label class="file-btn">↑ ورود CSV<input id="csvImport" type="file" accept=".csv,text/csv" onchange="importCSV(this)"></label></div>
<div id="status" class="card status">آماده برای برداشت محصولات</div><div class="card tablebox"><table><thead><tr><th>#</th><th>تصویر</th><th>عنوان</th><th>قیمت</th><th>SKU</th><th>لینک</th></tr></thead><tbody id="rows"><tr><td class="empty" colspan="6">پس از شروع برداشت، محصولات اینجا نمایش داده می‌شوند.</td></tr></tbody></table></div></section>
<section id="selectors" class="pane"><div id="selectorsMount"></div></section>
<section id="results" class="pane"><div class="primary-actions actions"><button class="green" onclick="downloadCSV()">↓ CSV</button><button class="gray" onclick="downloadJSON()">↓ JSON</button><button class="gray" onclick="downloadXLSX()">↓ Excel</button></div><div id="resultsMount"></div></section>
<section id="imports" class="pane"><div class="card"><h3>📥 درون‌ریزی محصولات</h3><div class="note">فایل CSV را مانند بخش درون‌ریزی نسخه PHP وارد کنید؛ نتیجه در تب نتایج نمایش داده می‌شود.</div><div class="actions"><label class="file-btn">↑ انتخاب و ورود CSV<input type="file" accept=".csv,text/csv" onchange="importCSV(this)"></label></div></div></section>
<section id="more" class="pane"><div class="card"><h3>☰ ابزارهای بیشتر</h3><div class="actions"><button onclick="openTab('profiles')">☆ پروفایل‌ها</button><button onclick="openTab('jobs')">◷ صف استخراج</button><button onclick="openTab('deploy')">↻ به‌روزرسانی و کتابخانه‌ها</button></div></div></section>
<section id="jobs" class="pane"><div class="card"><h3>صف استخراج و نقاط ادامه</h3><div class="note">پس از هر صفحه یک checkpoint اتمیک ذخیره می‌شود؛ عملیات قطع‌شده را بدون شروع از ابتدا ادامه دهید.</div><div class="actions"><button onclick="loadJobs()">تازه‌سازی صف</button></div><div id="jobList"></div></div></section>
<section id="profiles" class="pane"><div class="card"><h3>پروفایل‌های ذخیره‌شده</h3><div id="profileList"></div></div></section>
<section id="settings" class="pane"><div class="card"><div class="grid"><div><label>Timeout ثانیه</label><input id="timeout" type="number"></div><div><label>فاصله درخواست‌ها، ms</label><input id="gap_ms" type="number"></div><div class="wide"><label>Proxy اختیاری</label><input id="proxy" dir="ltr" placeholder="http://user:pass@host:port"></div><div><label><input id="verify_tls" type="checkbox" style="width:auto"> بررسی گواهی TLS</label></div></div><div class="actions"><button onclick="saveSettings()">ذخیره</button></div></div>
<div class="card note"><b>روش استخراج نسخه PHP:</b> HTML صفحه دریافت و سلکتورهای CSS روی DOM اجرا می‌شوند. در سایت‌های JavaScript، Playwright ابتدا DOM کامل را رندر می‌کند. هیچ API محصول یا hydration استفاده نمی‌شود.</div></section>
<section id="woo" class="pane"><div class="card"><h3>اتصال و صف ووکامرس</h3><div class="grid"><div class="wide"><label>URL فروشگاه</label><input id="woo_url" dir="ltr"></div><div><label>Consumer key</label><input id="woo_ck" dir="ltr"></div><div><label>Consumer secret</label><input id="woo_cs" type="password" dir="ltr"></div><div><label>وضعیت محصول</label><select id="woo_product_status"><option value="draft">پیش‌نویس</option><option value="publish">انتشار</option><option value="pending">در انتظار بررسی</option><option value="private">خصوصی</option></select></div><div><label>تعداد هر مرحله</label><input id="woo_batch" type="number" min="1" max="25" value="10"></div><div><label><input id="woo_update" type="checkbox" checked style="width:auto"> بروزرسانی محصول هم‌SKU</label></div></div><div class="actions"><button onclick="saveSettings(true)">ذخیره اتصال</button><button class="gray" onclick="wooTest()">تست</button><button class="green" onclick="wooQueue()">افزودن نتایج به صف</button><button class="gray" onclick="loadWooJobs()">تازه‌سازی صف</button></div><div id="wooStatus" class="status">صف مرحله‌ای برای سازگاری با محدودیت اجرای PythonAnywhere</div><div id="wooJobList"></div></div></section>
<section id="deploy" class="pane"><div class="card"><h3>نصب‌کننده اتمیک از GitHub</h3><div class="note">نسخه تازه پیش از نصب با کامپایل Python بررسی می‌شود. نسخه فعلی در <code>scraper4.py.bak</code> می‌ماند. برای repository خصوصی بهتر است متغیر محیطی <code>GITHUB_TOKEN</code> را در WSGI تنظیم کنید.</div><div class="grid" style="margin-top:12px"><div><label>Repository (owner/repo)</label><input id="dep_repo" dir="ltr"></div><div><label>Branch</label><input id="dep_branch" dir="ltr"></div><div><label>مسیر فایل در repository</label><input id="dep_path" dir="ltr"></div><div><label>GitHub token اختیاری</label><input id="dep_token" type="password" dir="ltr" placeholder="خالی = نگه‌داشتن قبلی / استفاده از GITHUB_TOKEN"></div><div class="wide"><label>مسیر کامل WSGI برای Reload اختیاری</label><input id="dep_reload" dir="ltr" placeholder="/var/www/USERNAME_pythonanywhere_com_wsgi.py"></div></div><div class="actions"><button onclick="saveDeploy()">ذخیره تنظیمات</button><button class="gray" onclick="installDeps()">نصب کتابخانه‌ها و Playwright</button><button class="gray" onclick="deployCheck()">بررسی نسخه</button><button class="green" onclick="deployRun()">نصب نسخه تازه</button><button class="gray" onclick="deployRollback()">بازگشت به .bak</button></div><div id="deployStatus" class="status">ابتدا تنظیمات را ذخیره و سپس نسخه را بررسی کنید.</div></div></section>
<footer class="app-footer"><nav class="tabs" aria-label="منوی اصلی"><button class="on" data-tab="scrape"><i>🎯</i><span>شروع</span></button><button data-tab="settings"><i>⚙️</i><span>تنظیمات</span></button><button data-tab="selectors"><i>🎨</i><span>سلکتورها</span></button><button data-tab="results"><i>📊</i><span>نتایج</span></button><button data-tab="woo"><i>📤</i><span>ارسال</span></button><button data-tab="imports"><i>📥</i><span>درون‌ریزی</span></button><button data-tab="more"><i>☰</i><span>بیشتر</span></button></nav></footer></div><script>
let products=[],profiles={},currentBuild=''; const $=id=>document.getElementById(id); const esc=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
(function phpLayout(){const scrape=$('scrape'),adv=scrape.querySelector('details.advanced'),status=$('status'),table=status?status.nextElementSibling:null;if(adv)$('selectorsMount').appendChild(adv);if(status)$('resultsMount').appendChild(status);if(table)$('resultsMount').appendChild(table);scrape.querySelectorAll('[onclick^="download"],label.file-btn').forEach(x=>x.remove())})();
function openTab(name){const b=document.querySelector(`.tabs button[data-tab="${name}"]`),target=$(name);if(!target)return;document.querySelectorAll('.tabs button,.pane').forEach(x=>x.classList.remove('on'));if(b)b.classList.add('on');target.classList.add('on');localStorage.setItem('scraperActiveTab',b?name:'more');window.scrollTo({top:0,behavior:'smooth'})}document.querySelectorAll('.tabs button').forEach(b=>b.onclick=()=>openTab(b.dataset.tab));
function config(){let selectors={},detail_selectors={};['container','title','price','link','image','sku'].forEach(k=>selectors[k]=$('sel_'+k).value.trim());['gallery','variations','weight','category','price','stock','brand','sku','short_desc','long_desc'].forEach(k=>detail_selectors[k]=$('det_'+k).value.trim());return {url:$('url').value.trim(),pages:+$('pages').value,render:$('render').value,pagination:$('pagination').value,page_value:$('page_value').value.trim(),scrolls:+$('scrolls').value,enrich:$('enrich').value==='1',detail_limit:+$('detail_limit').value,selectors,detail_selectors}}
function apply(c){if(!c)return;['url','pages','render','pagination','page_value','scrolls','detail_limit'].forEach(k=>{if(c[k]!==undefined)$(k).value=c[k]});$('enrich').value=c.enrich?'1':'0';Object.entries(c.selectors||{}).forEach(([k,v])=>{if($('sel_'+k))$('sel_'+k).value=v||''});Object.entries(c.detail_selectors||{}).forEach(([k,v])=>{if($('det_'+k))$('det_'+k).value=v||''})}
async function api(path,opt={}){let r=await fetch(path,{...opt,headers:{'Content-Type':'application/json',...(opt.headers||{})}});let j=await r.json();if(!r.ok||j.ok===false)throw Error(j.error||'خطای درخواست');return j}
let deploySecret=sessionStorage.getItem('scraperDeployPassword')||'';
async function deployApi(path,opt={}){if(!deploySecret){deploySecret=prompt('رمز مدیریت نصب را وارد کنید:')||'';if(!deploySecret)throw Error('رمز مدیریت نصب وارد نشد');sessionStorage.setItem('scraperDeployPassword',deploySecret)}try{return await api(path,{...opt,headers:{...(opt.headers||{}),'X-Deploy-Password':deploySecret}})}catch(e){if(/رمز مدیریت نصب/.test(e.message)){deploySecret='';sessionStorage.removeItem('scraperDeployPassword')}throw e}}
async function init(){let d=await api('/api/config');currentBuild=d.build||'';$('appVersion').textContent='v'+(d.version||'1.6.0');profiles=d.profiles||{};$('timeout').value=d.network.timeout;$('gap_ms').value=d.network.gap_ms;$('proxy').value=d.network.proxy||'';$('verify_tls').checked=d.network.verify_tls!==false;$('woo_url').value=d.woocommerce.url||'';$('woo_ck').value=d.woocommerce.consumer_key||'';$('woo_cs').value=d.woocommerce.consumer_secret||'';$('dep_repo').value=d.deploy.repo||'';$('dep_branch').value=d.deploy.branch||'';$('dep_path').value=d.deploy.path||'';$('dep_reload').value=d.deploy.reload_file||'';$('dep_token').placeholder=d.deploy.has_github_token?'توکن تنظیم شده است؛ خالی = نگه‌داشتن':'GitHub token اختیاری';renderProfiles();loadJobs();loadWooJobs();openTab(localStorage.getItem('scraperActiveTab')||'scrape')}
async function runScrape(){const btn=$('runBtn'),old=btn.innerHTML;if(!$('url').value.trim()){$('status').innerHTML='<span class="error">لطفاً آدرس صفحه را وارد کنید.</span>';$('url').focus();return}btn.disabled=true;btn.innerHTML='<span class="spinner"></span>در حال برداشت';$('status').innerHTML='<span class="spinner"></span> در حال دریافت و تحلیل صفحات…\nاین پنجره را تا پایان عملیات باز نگه دارید.';try{let d=await api('/api/scrape',{method:'POST',body:JSON.stringify(config())});products=d.products;renderRows();let c=d.comparison||{};$('status').innerHTML=`<span class="ok">✓ ${d.total} محصول از ${d.pages} صفحه استخراج شد</span>\nجدید: ${c.added||0} · تغییرکرده: ${c.changed||0} · حذف‌شده: ${c.removed||0}\nروش: ${esc(d.modes.join(' · '))}\n${esc(d.logs.join('\n'))}`;openTab('results');}catch(e){$('status').innerHTML='<span class="error">✗ عملیات ناموفق بود\n'+esc(e.message)+'</span>'}finally{btn.disabled=false;btn.innerHTML=old}}
function renderRows(){if(!products.length){$('rows').innerHTML='<tr><td class="empty" colspan="6">محصولی پیدا نشد. آدرس، روش محتوا یا سلکتورها را بررسی کنید.</td></tr>';return}$('rows').innerHTML=products.map((p,i)=>`<tr><td data-label="ردیف">${i+1}</td><td data-label="تصویر">${p.image?`<img src="${esc(p.image)}" loading="lazy" alt="">`:''}</td><td data-label="عنوان">${esc(p.title)}</td><td data-label="قیمت" dir="ltr">${esc(p.price)}</td><td data-label="SKU">${esc(p.sku)}</td><td data-label="لینک">${p.link?`<a href="${esc(p.link)}" target="_blank" rel="noopener">مشاهده ↗</a>`:''}</td></tr>`).join('')}
async function saveProfilePrompt(){let name=prompt('نام پروفایل:');if(!name)return;let d=await api('/api/profile',{method:'POST',body:JSON.stringify({name,config:config()})});profiles=d.profiles;renderProfiles()}
function renderProfiles(){$('profileList').innerHTML=Object.entries(profiles).map(([n,c])=>`<div class="card"><b>${esc(n)}</b><br><small dir="ltr">${esc(c.url)}</small><div class="actions"><button onclick='loadProfile(${JSON.stringify(n)})'>بارگذاری</button><button class="gray" onclick='delProfile(${JSON.stringify(n)})'>حذف</button></div></div>`).join('')||'<div class="note">هنوز پروفایلی نیست.</div>'}
function loadProfile(n){apply(profiles[n]);document.querySelector('[data-tab="scrape"]').click()} async function delProfile(n){if(!confirm('حذف شود؟'))return;let d=await api('/api/profile/'+encodeURIComponent(n),{method:'DELETE'});profiles=d.profiles;renderProfiles()}
async function loadJobs(){try{let d=await api('/api/extract/jobs');$('jobList').innerHTML=(d.jobs||[]).map(j=>`<div class="card"><b>${esc(j.id)}</b><br><small>وضعیت: ${esc(j.status)} · محصول: ${j.total||0} · صفحه بعد: ${j.next_page||'-'}</small><div class="actions">${j.status!=='completed'?`<button onclick="resumeJob('${esc(j.id)}')">ادامه</button>`:''}<button class="gray" onclick="deleteJob('${esc(j.id)}')">حذف</button></div></div>`).join('')||'<div class="note">صف خالی است.</div>'}catch(e){$('jobList').textContent=e.message}}
async function resumeJob(id){try{$('jobList').textContent='در حال ادامه استخراج…';let d=await api('/api/extract/resume/'+encodeURIComponent(id),{method:'POST',body:'{}'});products=d.products||[];renderRows();$('status').innerHTML=`<span class="ok">✓ عملیات ادامه یافت؛ ${d.total||0} محصول</span>`;openTab('results')}catch(e){$('jobList').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deleteJob(id){if(!confirm('این نقطه ادامه حذف شود؟'))return;await api('/api/extract/jobs/'+encodeURIComponent(id),{method:'DELETE'});loadJobs()}
function downloadCSV(){location.href='/api/export.csv'}function downloadJSON(){location.href='/api/export.json'}function downloadXLSX(){location.href='/api/export.xlsx'}async function importCSV(input){if(!input.files[0])return;let form=new FormData();form.append('file',input.files[0]);try{let r=await fetch('/api/import.csv',{method:'POST',body:form}),d=await r.json();if(!r.ok||!d.ok)throw Error(d.error||'ورود ناموفق');products=d.products||[];renderRows();$('status').innerHTML=`<span class="ok">✓ ${d.total} محصول از CSV وارد شد</span>`;openTab('results')}catch(e){$('status').innerHTML='<span class="error">'+esc(e.message)+'</span>'}finally{input.value=''}}
async function saveSettings(woo=false){let body={network:{timeout:+$('timeout').value,gap_ms:+$('gap_ms').value,proxy:$('proxy').value.trim(),verify_tls:$('verify_tls').checked}};if(woo)body.woocommerce={url:$('woo_url').value.trim(),consumer_key:$('woo_ck').value.trim(),consumer_secret:$('woo_cs').value.trim()};await api('/api/settings',{method:'POST',body:JSON.stringify(body)});alert('ذخیره شد')}
async function wooTest(){try{$('wooStatus').textContent='در حال تست…';let d=await api('/api/woo/test',{method:'POST',body:'{}'});$('wooStatus').innerHTML='<span class="ok">اتصال موفق است.</span>'}catch(e){$('wooStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function wooQueue(){try{let d=await api('/api/woo/queue',{method:'POST',body:JSON.stringify({products,status:$('woo_product_status').value,update_existing:$('woo_update').checked})});$('wooStatus').innerHTML='<span class="ok">صف ساخته شد؛ اکنون «پردازش مرحله بعد» را بزنید.</span>';loadWooJobs()}catch(e){$('wooStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function loadWooJobs(){try{let d=await api('/api/woo/jobs');$('wooJobList').innerHTML=(d.jobs||[]).map(j=>`<div class="card"><b>${esc(j.id)}</b><br><small>${esc(j.status)} · پیشرفت ${j.cursor||0}/${j.total||0} · موفق ${j.sent||0} · خطا ${j.failed||0}</small><div class="actions">${j.status!=='completed'?`<button onclick="processWoo('${esc(j.id)}')">پردازش مرحله بعد</button>`:''}<button class="gray" onclick="deleteWoo('${esc(j.id)}')">حذف</button></div></div>`).join('')||'<div class="note">صفی وجود ندارد.</div>'}catch(e){$('wooStatus').textContent=e.message}}
async function processWoo(id){try{$('wooStatus').textContent='در حال ارسال مرحله…';let d=await api('/api/woo/process/'+encodeURIComponent(id),{method:'POST',body:JSON.stringify({batch:+$('woo_batch').value})});$('wooStatus').innerHTML=`<span class="ok">پیشرفت ${d.job.cursor}/${d.job.total}؛ موفق ${d.job.sent}؛ خطا ${d.job.failed}</span>`;loadWooJobs()}catch(e){$('wooStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deleteWoo(id){if(!confirm('صف حذف شود؟'))return;await api('/api/woo/jobs/'+encodeURIComponent(id),{method:'DELETE'});loadWooJobs()}
async function saveDeploy(){let deploy={repo:$('dep_repo').value.trim(),branch:$('dep_branch').value.trim(),path:$('dep_path').value.trim(),reload_file:$('dep_reload').value.trim(),github_token:$('dep_token').value.trim()};await deployApi('/api/settings',{method:'POST',body:JSON.stringify({deploy})});$('dep_token').value='';$('deployStatus').innerHTML='<span class="ok">تنظیمات نصب ذخیره شد.</span>'}
async function installDeps(){if(!confirm('کتابخانه‌های استخراج و مرورگر Chromium نصب/به‌روزرسانی شوند؟ ممکن است چند دقیقه طول بکشد.'))return;try{$('deployStatus').textContent='در حال نصب Playwright، Chromium و کتابخانه‌های استخراج…';let d=await deployApi('/api/deploy/dependencies',{method:'POST',body:'{}'});$('deployStatus').innerHTML='<span class="ok">'+esc(d.message)+'</span>'}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deployCheck(){try{$('deployStatus').textContent='در حال بررسی GitHub…';let d=await deployApi('/api/deploy/check',{method:'POST',body:'{}'});$('deployStatus').innerHTML=`نسخه جاری: ${esc(d.version)}\nSHA محلی: ${esc(d.local_sha)}\nSHA راه دور: ${esc(d.remote_sha)}\n${d.update_available?'<span class="ok">نسخه متفاوت آماده نصب است.</span>':'نسخه محلی و راه دور یکسان‌اند.'}`;}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deployRun(){if(!confirm('فایل جاری جایگزین و نسخه قبلی در .bak ذخیره شود؟'))return;try{$('deployStatus').textContent='در حال دانلود، اعتبارسنجی و نصب…';let d=await deployApi('/api/deploy/run',{method:'POST',body:'{}'});$('deployStatus').innerHTML='<span class="ok">'+esc(d.message)+' — نسخه '+esc(d.version)+'</span>\n'+(d.reload_requested?'درخواست reload فرستاده شد.':'در صورت تنظیم‌نبودن WSGI، از تب Web دکمه Reload را بزنید.');}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deployRollback(){if(!confirm('نسخه scraper4.py.bak بازیابی شود؟'))return;try{let d=await deployApi('/api/deploy/rollback',{method:'POST',body:'{}'});$('deployStatus').innerHTML='<span class="ok">'+esc(d.message)+' — نسخه '+esc(d.version)+'</span>';}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function watchBuild(){try{let r=await fetch('/health',{cache:'no-store'}),d=await r.json();if(currentBuild&&d.build&&d.build!==currentBuild){document.body.style.opacity='.65';location.reload()}}catch(e){}}
init().then(()=>setInterval(watchBuild,30000)).catch(e=>$('status').textContent=e.message);
</script></body></html>'''


if __name__ == "__main__":
    # Local development only. PythonAnywhere imports `app` as `application`.
    app.run(host="0.0.0.0", port=int(os.environ.get("PORT", "8000")), debug=False)
