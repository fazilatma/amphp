#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Scraper4 PythonAnywhere edition — single-file Flask product scraper.

This is a focused Python port of scraper4.php's extraction workflow.  It keeps
its important ideas (profiles, CSS selectors, pagination, automatic product
recognition, detail enrichment, exports, pacing, retries, and WooCommerce
sending) while replacing the PHP-only/background-task UI with a request-sized
Flask application suitable for PythonAnywhere.

React / Next / SPA support is deliberately layered:
  1. normal server HTML and JSON-LD;
  2. hydration JSON (__NEXT_DATA__, application/json, preloaded state);
  3. known JSON APIs (including Digikala);
  4. JSON API URLs discovered in the initial shell;
  5. optional Playwright rendering when Playwright + Chromium are available.

PythonAnywhere setup
--------------------
1. Upload this file, then in a Bash console run:
       pip3 install --user flask requests beautifulsoup4
   Optional browser fallback (usually only practical on a paid/custom image):
       pip3 install --user playwright && python3 -m playwright install chromium
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
import tempfile
import threading
import time
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

APP_VERSION = "1.2.0"
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


def scalar_at(obj: Any, paths: Iterable[str]) -> Any:
    for path in paths:
        cur = obj
        ok = True
        for part in path.split("."):
            if isinstance(cur, dict) and part in cur:
                cur = cur[part]
            else:
                ok = False
                break
        if ok and cur not in (None, "", [], {}):
            return cur
    return ""


def extract_price(value: Any) -> str:
    if isinstance(value, dict):
        value = scalar_at(value, (
            "selling_price", "sale_price", "discounted_price", "price", "amount",
            "value", "rrp_price", "main_price", "min_price",
        ))
    if isinstance(value, list):
        value = next((price for item in value if (price := extract_price(item))), "")
    text = clean_text(value)
    if not text:
        return ""
    nums = re.findall(r"\d[\d,.٬،]*", text)
    if not nums:
        return ""
    # scraper4.php behavior: a normalized numeric value, not formatted HTML.
    candidates = [re.sub(r"\D", "", n) for n in nums]
    candidates = [x for x in candidates if x]
    return candidates[-1] if candidates else ""


def product_key(product: dict[str, Any]) -> str:
    identity = product.get("link") or product.get("sku") or (
        str(product.get("title", "")).lower() + "|" + str(product.get("price", ""))
    )
    return hashlib.md5(identity.encode("utf-8", "ignore")).hexdigest()


def normalize_product(raw: dict[str, Any], base: str) -> Optional[dict[str, Any]]:
    title = clean_text(scalar_at(raw, (
        "title_fa", "title", "name", "product_name", "displayName", "label",
        "attributes.title_fa", "attributes.name",
    )))
    raw_id = scalar_at(raw, ("id", "product_id", "productId", "sku", "code", "dkp"))
    link = absolute_url(scalar_at(raw, (
        "url.uri", "url", "link", "href", "product_url", "canonical_url", "seo.url",
        "attributes.url",
    )), base)
    if not link and raw_id and "digikala" in urlparse(base).hostname.lower():
        link = f"https://www.digikala.com/product/dkp-{raw_id}/"
    price_raw = scalar_at(raw, (
        "default_variant.price.selling_price", "default_variant.price.rrp_price",
        "price.selling_price", "price.sale_price", "selling_price", "sale_price",
        "discounted_price", "final_price", "price", "offers.price",
        "attributes.discounted_price", "attributes.main_price",
    ))
    image_raw = scalar_at(raw, (
        "images.main.url", "images.main", "images", "image", "image_url", "thumbnail",
        "featured_image", "media.url", "attributes.featured_image",
    ))
    sku = clean_text(scalar_at(raw, ("sku", "product_code", "code", "id", "product_id")))
    if not title or (not link and not image_raw and not price_raw and not raw_id):
        return None
    # Avoid treating brand/category/menu dictionaries as products. An ID by
    # itself is not enough: hydration trees contain hundreds of named IDs.
    keys = " ".join(str(k).lower() for k in raw.keys())
    product_signal = any(x in keys for x in (
        "product", "price", "image", "url", "sku", "offer", "variant", "inventory", "stock"
    ))
    if not product_signal:
        return None
    product = {
        "title": title[:300],
        "price": extract_price(price_raw),
        "link": link,
        "image": image_value(image_raw, base),
        "sku": sku,
    }
    stock = scalar_at(raw, ("stock", "inventory", "default_variant.stock", "is_available", "available"))
    if stock not in ("", None):
        product["stock"] = clean_text(stock)
    brand = scalar_at(raw, ("brand.title_fa", "brand.name", "brand", "attributes.brand"))
    if brand:
        product["brand"] = clean_text(brand)
    return product


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


def walk_json(value: Any, base: str, store: dict[str, dict[str, Any]], depth: int = 0) -> None:
    if depth > 35 or len(store) >= MAX_PRODUCTS_HARD:
        return
    if isinstance(value, dict):
        add_product(store, normalize_product(value, base))
        for child in value.values():
            walk_json(child, base, store, depth + 1)
    elif isinstance(value, list):
        for child in value:
            walk_json(child, base, store, depth + 1)


def json_documents(soup: BeautifulSoup) -> Iterable[Any]:
    for script in soup.find_all("script"):
        text = script.string or script.get_text() or ""
        text = text.strip()
        if not text or len(text) > MAX_HTML_BYTES:
            continue
        typ = (script.get("type") or "").lower()
        sid = (script.get("id") or "").lower()
        if "json" in typ or sid in {"__next_data__", "__nuxt_data__"}:
            try:
                yield json.loads(text)
                continue
            except ValueError:
                pass
        # Redux/Next shells often assign a JSON object to a global variable.
        if any(mark in text for mark in ("__PRELOADED_STATE__", "__INITIAL_STATE__", "__APOLLO_STATE__")):
            match = re.search(r"(?:__PRELOADED_STATE__|__INITIAL_STATE__|__APOLLO_STATE__)\s*=\s*", text)
            if match:
                tail = text[match.end():].strip().rstrip(";")
                try:
                    yield json.loads(tail)
                except ValueError:
                    pass


def parse_json_payload(payload: Any, base: str) -> list[dict[str, Any]]:
    store: dict[str, dict[str, Any]] = {}
    walk_json(payload, base, store)
    return list(store.values())


def parse_html(text: str, base: str, selectors: Optional[dict[str, str]] = None) -> tuple[list[dict[str, Any]], BeautifulSoup, dict[str, int]]:
    soup = BeautifulSoup(text, "html.parser")
    store: dict[str, dict[str, Any]] = {}
    selector_rows = parse_selectors(soup, base, selectors or {}) if selectors and selectors.get("container") else []
    for row in selector_rows:
        add_product(store, row)
    hydration_before = len(store)
    for doc in json_documents(soup):
        walk_json(doc, base, store)
    hydration_count = len(store) - hydration_before

    if not store:
        candidates = soup.select(
            "li.product,article[class*='product'],div.product-card,div.product-item,"
            "div[class*='product-card'],[data-product-id]"
        )
        for node in candidates:
            title_node = node.select_one("h1,h2,h3,h4,[class*='title'],a[title]")
            link_node = node if node.name == "a" else node.find("a", href=True)
            image_node = node.find("img")
            price_node = node.select_one("[class*='price'],[class*='amount'],ins")
            row = {
                "title": clean_text((title_node.get("title") if title_node else "") or (title_node.get_text(" ") if title_node else "")),
                "price": extract_price(price_node.get_text(" ") if price_node else ""),
                "link": absolute_url(link_node.get("href"), base) if link_node else "",
                "image": next((absolute_url(image_node.get(a), base) for a in ("data-src", "src") if image_node and image_node.get(a)), ""),
                "sku": clean_text(node.get("data-product-id", "")),
            }
            add_product(store, row if row["title"] or row["link"] else None)
    return list(store.values()), soup, {
        "selector_matches": len(selector_rows), "hydration_products": hydration_count,
        "html_bytes": len(text.encode("utf-8", "ignore")),
    }


# ---------------------------------------------------------------------------
# SPA adapters and optional rendering
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


def digikala_api_urls(source_url: str, page: int) -> list[str]:
    parsed = urlparse(source_url)
    if "digikala.com" not in (parsed.hostname or "").lower():
        return []
    query = parse_qs(parsed.query, keep_blank_values=True)
    query["page"] = [str(page)]
    path = parsed.path.strip("/")
    match = re.search(r"categories/([^/]+)", path)
    if match:
        slug = match.group(1)
        return [
            "https://api.digikala.com/v1/categories/" + quote(slug) + "/search/?" + urlencode(query, doseq=True),
            "https://api.digikala.com/v2/category/" + quote(slug) + "/?" + urlencode(query, doseq=True),
        ]
    return ["https://api.digikala.com/v1/search/?" + urlencode(query, doseq=True)]


def discovered_api_urls(soup: BeautifulSoup, page_url_value: str) -> list[str]:
    """Conservative API discovery: only likely product JSON URLs, same site family."""
    origin_host = (urlparse(page_url_value).hostname or "").lower()
    root = ".".join(origin_host.split(".")[-2:])
    found: list[str] = []
    pattern = re.compile(r"(?:https?:)?(?:\\?/\\?/)?[^\"'\s<>]{0,200}(?:api|search|products?|catalog)[^\"'\s<>]{0,250}", re.I)
    for script in soup.find_all("script"):
        text = (script.string or script.get_text() or "")[:2_000_000]
        text = text.replace("\\/", "/").replace("\\u0026", "&")
        for raw in pattern.findall(text):
            raw = raw.strip(" ,;()[]{}\\")
            if raw.startswith("//"):
                raw = "https:" + raw
            candidate = urljoin(page_url_value, raw)
            parsed = urlparse(candidate)
            if parsed.scheme not in ("http", "https") or not parsed.hostname:
                continue
            if root and not parsed.hostname.lower().endswith(root):
                continue
            if not re.search(r"api|search|products?|catalog", parsed.path, re.I):
                continue
            if candidate not in found:
                found.append(candidate)
            if len(found) >= 5:
                return found
    return found


def render_playwright(url: str, timeout: int, scrolls: int = 4) -> FetchResult:
    try:
        from playwright.sync_api import sync_playwright
    except ImportError as exc:
        raise FetchError("Playwright نصب نیست؛ حالت auto/API را به‌کار ببرید") from exc
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


def scrape(config: dict[str, Any]) -> ScrapeReport:
    source = public_http_url(str(config.get("url", "")))
    pages = max(1, min(MAX_PAGES_HARD, int(config.get("pages", 1))))
    mode = str(config.get("render", "auto"))
    selectors = config.get("selectors") if isinstance(config.get("selectors"), dict) else {}
    pag_kind = str(config.get("pagination", "query"))
    pag_value = str(config.get("page_value", "page"))
    enrich = bool(config.get("enrich", False))
    detail_limit = max(0, min(100, int(config.get("detail_limit", 20))))
    cfg = load_data()
    fetcher = Fetcher(cfg["network"])
    report = ScrapeReport()

    for number in range(1, pages + 1):
        url = page_url(source, number, pag_kind, pag_value)
        rows: list[dict[str, Any]] = []
        soup: Optional[BeautifulSoup] = None
        diag: dict[str, Any] = {}
        fetch_error = ""

        # Digikala's public JSON API sees products that its React shell does not
        # include as cards. API mode also avoids spending a browser process.
        if mode in ("auto", "api"):
            for api_url in digikala_api_urls(source, number):
                try:
                    result = fetcher.get(api_url, referer=url, accept_json=True)
                    payload = json.loads(result.text)
                    rows = parse_json_payload(payload, result.url)
                    if rows:
                        report.modes.add("digikala-api")
                        report.logs.append(f"صفحه {number}: {len(rows)} محصول از API دیجی‌کالا")
                        break
                except (FetchError, ValueError) as exc:
                    fetch_error = str(exc)

        if not rows and mode != "browser":
            try:
                result = fetcher.get(url)
                rows, soup, diag = parse_html(result.text, result.url, selectors)
                report.modes.add("hydration" if diag.get("hydration_products") else "html")
                report.logs.append(f"صفحه {number}: {len(rows)} محصول از HTML/JSON داخلی")
                # Look for fetch/API URLs referenced by an otherwise empty shell.
                if not rows and mode in ("auto", "api") and soup:
                    for api_url in discovered_api_urls(soup, result.url):
                        try:
                            api_result = fetcher.get(api_url, referer=result.url, accept_json=True)
                            payload = json.loads(api_result.text)
                            rows = parse_json_payload(payload, api_result.url)
                            if rows:
                                report.modes.add("discovered-api")
                                report.logs.append(f"صفحه {number}: API داخلی صفحه پیدا شد")
                                break
                        except (FetchError, ValueError):
                            continue
            except (FetchError, ValueError) as exc:
                fetch_error = str(exc)

        if not rows and mode in ("auto", "browser"):
            try:
                result = render_playwright(url, fetcher.timeout, int(config.get("scrolls", 4)))
                rows, soup, diag = parse_html(result.text, result.url, selectors)
                report.modes.add("browser")
                report.logs.append(f"صفحه {number}: {len(rows)} محصول پس از اجرای JavaScript")
            except (FetchError, ValueError) as exc:
                fetch_error = str(exc)

        new_count = 0
        for row in rows:
            before = len(report.products)
            add_product(report.products, row)
            new_count += len(report.products) - before
        report.pages = number
        report.diagnostics[f"page_{number}"] = diag
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
            if not product.get("link") or (product.get("image") and product.get("price")):
                continue
            try:
                detail = fetcher.get(product["link"], referer=source)
                detail_rows, detail_soup, _ = parse_html(detail.text, detail.url)
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
        data["last_result"] = products
        save_data(data)
        return jsonify(ok=True, products=products, total=len(products), pages=report.pages,
                       modes=sorted(report.modes), logs=report.logs, diagnostics=report.diagnostics)
    except (ValueError, FetchError) as exc:
        return jsonify(ok=False, error=str(exc)), 400
    except Exception as exc:
        app.logger.exception("scrape failed")
        return jsonify(ok=False, error=f"خطای داخلی: {exc}"), 500


@app.route("/api/export.csv", methods=["GET", "POST"])
def api_export():
    body = request.get_json(silent=True) or {}
    products = body.get("products") if isinstance(body.get("products"), list) else load_data()["last_result"]
    return export_csv(products)


@app.post("/api/woo/test")
def woo_test():
    try:
        response = woo_request("GET", "system_status")
        return jsonify(ok=True, status=response.json())
    except (ValueError, FetchError, requests.RequestException) as exc:
        return jsonify(ok=False, error=str(exc)), 400


@app.post("/api/woo/send")
def woo_send():
    body = request.get_json(silent=True) or {}
    products = body.get("products") if isinstance(body.get("products"), list) else load_data()["last_result"]
    limit = max(1, min(100, int(body.get("limit", 20))))
    sent, failed = [], []
    for product in products[:limit]:
        payload: dict[str, Any] = {
            "name": clean_text(product.get("title")) or "بدون نام",
            "type": "simple", "regular_price": str(product.get("price") or "0"),
            "status": str(body.get("status", "draft")),
        }
        if product.get("sku"):
            payload["sku"] = str(product["sku"])
        if product.get("image"):
            payload["images"] = [{"src": product["image"]}]
        if product.get("link"):
            payload["meta_data"] = [{"key": "_scraper_source_url", "value": product["link"]}]
        try:
            result = woo_request("POST", "products", payload).json()
            sent.append({"source": product.get("title"), "id": result.get("id"), "name": result.get("name")})
        except Exception as exc:
            failed.append({"source": product.get("title"), "error": str(exc)})
    return jsonify(ok=not failed, sent=sent, failed=failed)


# ---------------------------------------------------------------------------
# Inline interface (keeps deployment genuinely single-file)
# ---------------------------------------------------------------------------
INDEX_HTML = r'''<!doctype html>
<html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover"><meta name="theme-color" content="#07111f">
<title>Scraper4 Python</title><style>
:root{--bg:#07111f;--bg2:#0a1830;--card:rgba(15,28,48,.82);--card2:#13243d;--line:rgba(148,177,216,.16);--text:#f4f8ff;--muted:#9db0ca;--blue:#38bdf8;--blue2:#2563eb;--green:#34d399;--red:#fb7185;--amber:#fbbf24;--shadow:0 18px 55px rgba(0,0,0,.28);--radius:20px}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;min-height:100vh;background:radial-gradient(circle at 85% -10%,rgba(37,99,235,.28),transparent 34%),radial-gradient(circle at 5% 18%,rgba(14,165,233,.13),transparent 26%),linear-gradient(155deg,var(--bg),var(--bg2));background-attachment:fixed;color:var(--text);font-family:Tahoma,"Segoe UI",Arial,sans-serif;font-size:14px;line-height:1.55}body:before{content:"";position:fixed;inset:0;pointer-events:none;opacity:.22;background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);background-size:34px 34px}.wrap{position:relative;max-width:1200px;margin:auto;padding:28px 22px 70px}.hero{display:flex;align-items:center;justify-content:space-between;gap:20px;margin-bottom:22px;padding:24px 26px;border:1px solid var(--line);border-radius:26px;background:linear-gradient(125deg,rgba(15,38,68,.92),rgba(16,31,53,.76));box-shadow:var(--shadow);overflow:hidden;position:relative}.hero:after{content:"";position:absolute;width:220px;height:220px;border-radius:50%;left:-70px;top:-110px;background:rgba(56,189,248,.11);filter:blur(2px)}.hero-main{display:flex;align-items:center;gap:16px;position:relative;z-index:1}.logo{width:58px;height:58px;display:grid;place-items:center;flex:none;border-radius:18px;font-size:29px;background:linear-gradient(145deg,#0ea5e9,#2563eb);box-shadow:0 10px 28px rgba(37,99,235,.34)}.eyebrow{font-size:10px;letter-spacing:.8px;color:#75d5ff;margin-bottom:2px}h1{font-size:clamp(21px,4vw,30px);margin:0;letter-spacing:-.5px}h1 small{font-size:10px;font-weight:500;color:#7f96b4;background:#09182c;border:1px solid var(--line);padding:3px 7px;border-radius:20px;vertical-align:middle}.sub{color:var(--muted);margin:5px 0 0}.hero-badge{position:relative;z-index:1;white-space:nowrap;padding:8px 13px;border-radius:999px;border:1px solid rgba(52,211,153,.25);background:rgba(52,211,153,.09);color:#8af0c9;font-size:12px}.hero-badge:before{content:"";display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--green);margin-left:7px;box-shadow:0 0 10px var(--green)}
.tabs{position:sticky;top:10px;z-index:20;display:flex;gap:7px;margin:0 0 16px;padding:7px;border:1px solid var(--line);border-radius:16px;background:rgba(7,17,31,.82);backdrop-filter:blur(16px);box-shadow:0 8px 28px rgba(0,0,0,.2);overflow-x:auto;scrollbar-width:none}.tabs::-webkit-scrollbar{display:none}.tabs button{flex:1;min-width:max-content;background:transparent;border:1px solid transparent;color:var(--muted);box-shadow:none;display:flex;align-items:center;justify-content:center;gap:7px}.tabs button i{font-style:normal;font-size:17px;line-height:1}.tabs button.on{color:white;border-color:rgba(56,189,248,.23);background:linear-gradient(135deg,rgba(14,165,233,.22),rgba(37,99,235,.2))}.pane{display:none;animation:rise .28s ease}.pane.on{display:block}@keyframes rise{from{opacity:0;transform:translateY(7px)}to{opacity:1;transform:none}}.card{background:var(--card);backdrop-filter:blur(14px);border:1px solid var(--line);border-radius:var(--radius);padding:20px;margin-bottom:14px;box-shadow:var(--shadow)}.card h3{margin:0 0 16px;font-size:18px}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px}.grid4{grid-template-columns:repeat(4,minmax(0,1fr))}.wide{grid-column:1/-1}label{display:block;color:#b9c8dc;font-size:12px;font-weight:600;margin:0 2px 6px}input,select,textarea,button{font:inherit;border-radius:12px;border:1px solid var(--line);padding:11px 13px;background:rgba(5,14,27,.72);color:var(--text);width:100%;outline:none;transition:.2s ease}input:hover,select:hover,textarea:hover{border-color:rgba(56,189,248,.3)}input:focus,select:focus,textarea:focus{border-color:var(--blue);box-shadow:0 0 0 3px rgba(56,189,248,.12);background:#081426}input::placeholder{color:#627895}select{cursor:pointer}button{width:auto;min-height:42px;cursor:pointer;background:linear-gradient(135deg,#0284c7,#2563eb);border-color:rgba(125,211,252,.28);font-weight:700;box-shadow:0 7px 18px rgba(37,99,235,.16)}button:hover{filter:brightness(1.1);transform:translateY(-1px)}button:active{transform:translateY(0)}button:disabled{opacity:.62;cursor:wait;transform:none}button.gray{background:#17263d;border-color:#33465f;box-shadow:none}button.green{background:linear-gradient(135deg,#059669,#047857);border-color:#34d399}.actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:17px}.primary-actions{margin:0 0 14px;padding:12px;border:1px solid var(--line);border-radius:16px;background:rgba(10,23,42,.8);box-shadow:var(--shadow)}details.advanced summary{display:flex;align-items:center;justify-content:space-between;gap:12px;cursor:pointer;list-style:none}details.advanced summary::-webkit-details-marker{display:none}details.advanced summary small{display:block;color:var(--muted);font-weight:400;margin-top:2px}details.advanced summary>i{font-style:normal;font-size:22px;color:var(--blue);transition:.2s}details.advanced[open] summary>i{transform:rotate(180deg)}.advanced-body{padding-top:15px}.advanced-body>.note{margin-bottom:14px}.note{padding:12px 14px;border-radius:13px;border:1px solid rgba(56,189,248,.12);background:rgba(19,42,70,.68);color:#bfd0e5;line-height:1.85}.status{white-space:pre-wrap;line-height:1.9;color:#b9cae0;border-right:3px solid var(--blue);min-height:58px}.error{color:var(--red)}.ok{color:var(--green)}code{direction:ltr;display:inline-block;color:#a5e4ff;background:#061426;border-radius:6px;padding:1px 5px}table{width:100%;border-collapse:separate;border-spacing:0;direction:rtl}th,td{padding:11px 10px;border-bottom:1px solid var(--line);text-align:right;vertical-align:middle}th{color:#96ddfb;position:sticky;top:0;background:#112139;z-index:2;font-size:12px}tbody tr{transition:.2s}tbody tr:hover{background:rgba(56,189,248,.045)}td img{width:62px;height:62px;object-fit:contain;background:#fff;border-radius:12px;padding:3px;box-shadow:0 4px 14px #0004}.tablebox{max-height:620px;overflow:auto;padding:0;border-radius:var(--radius)}a{color:#78d7ff;text-decoration:none}a:hover{text-decoration:underline}.badge{display:inline-block;padding:4px 8px;border:1px solid #36516f;border-radius:12px;color:#b6d6ef;margin:3px}.empty{padding:42px 14px!important;text-align:center!important;color:var(--muted)}.spinner{display:inline-block;width:16px;height:16px;border:2px solid #fff5;border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;vertical-align:-3px;margin-left:7px}@keyframes spin{to{transform:rotate(360deg)}}
@media(max-width:900px){.grid4{grid-template-columns:repeat(2,minmax(0,1fr))}.hero{padding:20px}.wrap{padding:18px 14px 60px}}
@media(max-width:640px){html,body{max-width:100%;overflow-x:hidden}body{font-size:13px}.wrap{padding:10px 8px calc(88px + env(safe-area-inset-bottom))}.hero{border-radius:18px;padding:14px 12px;margin-bottom:10px}.hero-main{gap:10px}.logo{width:43px;height:43px;border-radius:13px;font-size:22px}.eyebrow{display:none}.hero h1{font-size:20px}.sub{font-size:10px;line-height:1.55;max-width:230px}.hero-badge{display:none}.tabs{position:fixed;top:auto;right:8px;left:8px;bottom:calc(7px + env(safe-area-inset-bottom));margin:0;border-radius:17px;padding:5px;justify-content:stretch;z-index:50}.tabs button{flex:1 1 0;min-width:0;min-height:50px;padding:5px 2px;font-size:9px;white-space:nowrap;flex-direction:column;gap:1px}.tabs button i{font-size:18px}.card{padding:14px 12px;border-radius:16px;margin-bottom:10px}.grid,.grid4{grid-template-columns:1fr;gap:12px}.wide{grid-column:auto}input,select,textarea{font-size:16px;min-height:48px}.actions{display:grid;grid-template-columns:1fr}.actions button{width:100%;padding:11px 8px;min-height:46px}.actions button:first-child:last-child{grid-column:1/-1}.note{font-size:12px;padding:10px}.tablebox{max-height:none;overflow:visible;background:transparent;border:0;box-shadow:none;padding:0}table,thead,tbody,tr,td{display:block}thead{display:none}tbody{display:grid;gap:10px}tbody tr{position:relative;padding:13px 88px 13px 12px;min-height:104px;border:1px solid var(--line);border-radius:16px;background:var(--card);box-shadow:0 8px 24px #0003}tbody tr:hover{background:var(--card)}td{padding:3px 0;border:0;text-align:right}td:before{content:attr(data-label);color:var(--muted);font-size:10px;margin-left:6px}td:nth-child(1){position:absolute;left:9px;top:8px;color:#7188a5;font-size:10px}td:nth-child(1):before,td:nth-child(2):before{display:none}td:nth-child(2){position:absolute;right:12px;top:13px}td:nth-child(2) img{width:64px;height:76px;border-radius:11px}td:nth-child(3){font-weight:700;font-size:13px;line-height:1.65;margin-bottom:4px}td:nth-child(4){color:#7ce6ba;font-weight:700;direction:ltr;text-align:right}td:empty{display:none}.empty{padding:35px 10px!important}.empty:before{display:none}}
@media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important;scroll-behavior:auto!important}}
</style></head><body><div class="wrap">
<header class="hero"><div class="hero-main"><div class="logo">🕸️</div><div><div class="eyebrow">مرکز استخراج محصول</div><h1>Scraper4 <small id="appVersion">v1.2.0</small></h1><div class="sub">استخراج هوشمند از HTML، React، JSON و API</div></div></div><div class="hero-badge"><span>●</span> آنلاین و آماده</div></header>
<nav class="tabs" aria-label="منوی اصلی"><button class="on" data-tab="scrape"><i>⌁</i><span>برداشت</span></button><button data-tab="profiles"><i>☆</i><span>پروفایل‌ها</span></button><button data-tab="settings"><i>⚙</i><span>تنظیمات</span></button><button data-tab="woo"><i>◈</i><span>ووکامرس</span></button><button data-tab="deploy"><i>↻</i><span>به‌روزرسانی</span></button></nav>
<section id="scrape" class="pane on"><div class="card"><div class="grid grid4">
<div class="wide"><label>آدرس صفحهٔ فهرست/جست‌وجو</label><input id="url" placeholder="https://www.digikala.com/search/?q=..." dir="ltr"></div>
<div><label>تعداد صفحه</label><input id="pages" type="number" min="1" max="50" value="1"></div>
<div><label>روش محتوا</label><select id="render"><option value="auto">خودکار: API ← HTML/JSON ← Browser</option><option value="api">API و hydration (بدون مرورگر)</option><option value="http">فقط HTML/hydration</option><option value="browser">مرورگر JavaScript (Playwright)</option></select></div>
<div><label>صفحه‌بندی</label><select id="pagination"><option value="query">پارامتر Query</option><option value="path">الگوی مسیر</option></select></div>
<div><label>نام پارامتر / الگو</label><input id="page_value" value="page" dir="ltr" placeholder="page یا /page/{page}/"></div>
<div><label>تعداد اسکرول در Browser</label><input id="scrolls" type="number" value="4" min="0" max="12"></div>
<div><label>تکمیل تصویر/قیمت از صفحه جزئیات</label><select id="enrich"><option value="0">خاموش</option><option value="1">روشن</option></select></div>
<div><label>سقف صفحات جزئیات</label><input id="detail_limit" type="number" value="20" min="0" max="100"></div>
</div></div>
<details class="card advanced"><summary><span><b>سلکتورهای CSS</b><small>اختیاری — فقط برای سایت‌های خاص</small></span><i>⌄</i></summary><div class="advanced-body"><div class="note">برای React ابتدا حالت «خودکار» را امتحان کنید. فقط اگر محصول پیدا نشد، سلکتور وارد کنید.</div><div class="grid grid4">
<div><label>ظرف محصول *</label><input id="sel_container" dir="ltr" placeholder="article.product"></div><div><label>عنوان</label><input id="sel_title" dir="ltr" placeholder="h2.title"></div><div><label>قیمت</label><input id="sel_price" dir="ltr" placeholder=".price"></div><div><label>لینک</label><input id="sel_link" dir="ltr" placeholder="a"></div><div><label>تصویر</label><input id="sel_image" dir="ltr" placeholder="img"></div><div><label>SKU</label><input id="sel_sku" dir="ltr"></div>
</div></div></details><div class="primary-actions actions"><button id="runBtn" onclick="runScrape()">🚀 شروع برداشت</button><button class="gray" onclick="saveProfilePrompt()">☆ ذخیره پروفایل</button><button class="green" onclick="downloadCSV()">↓ خروجی CSV</button></div>
<div id="status" class="card status">آماده برای برداشت محصولات</div><div class="card tablebox"><table><thead><tr><th>#</th><th>تصویر</th><th>عنوان</th><th>قیمت</th><th>SKU</th><th>لینک</th></tr></thead><tbody id="rows"><tr><td class="empty" colspan="6">پس از شروع برداشت، محصولات اینجا نمایش داده می‌شوند.</td></tr></tbody></table></div></section>
<section id="profiles" class="pane"><div class="card"><h3>پروفایل‌های ذخیره‌شده</h3><div id="profileList"></div></div></section>
<section id="settings" class="pane"><div class="card"><div class="grid"><div><label>Timeout ثانیه</label><input id="timeout" type="number"></div><div><label>فاصله درخواست‌ها، ms</label><input id="gap_ms" type="number"></div><div class="wide"><label>Proxy اختیاری</label><input id="proxy" dir="ltr" placeholder="http://user:pass@host:port"></div><div><label><input id="verify_tls" type="checkbox" style="width:auto"> بررسی گواهی TLS</label></div></div><div class="actions"><button onclick="saveSettings()">ذخیره</button></div></div>
<div class="card note"><b>React و PythonAnywhere:</b> روش API/hydration به مرورگر نیاز ندارد و برای دیجی‌کالا انتخاب بهتر است. Playwright فقط وقتی کار می‌کند که خود پکیج و Chromium روی حساب نصب و قابل اجرا باشند. حساب رایگان PythonAnywhere دسترسی اینترنتی محدود به allowlist دارد.</div></section>
<section id="woo" class="pane"><div class="card"><div class="grid"><div class="wide"><label>URL فروشگاه</label><input id="woo_url" dir="ltr"></div><div><label>Consumer key</label><input id="woo_ck" dir="ltr"></div><div><label>Consumer secret</label><input id="woo_cs" type="password" dir="ltr"></div></div><div class="actions"><button onclick="saveSettings(true)">ذخیره اتصال</button><button class="gray" onclick="wooTest()">تست</button><button class="green" onclick="wooSend()">ارسال حداکثر ۲۰ محصول به پیش‌نویس</button></div><div id="wooStatus" class="status"></div></div></section>
<section id="deploy" class="pane"><div class="card"><h3>نصب‌کننده اتمیک از GitHub</h3><div class="note">نسخه تازه پیش از نصب با کامپایل Python بررسی می‌شود. نسخه فعلی در <code>scraper4.py.bak</code> می‌ماند. برای repository خصوصی بهتر است متغیر محیطی <code>GITHUB_TOKEN</code> را در WSGI تنظیم کنید.</div><div class="grid" style="margin-top:12px"><div><label>Repository (owner/repo)</label><input id="dep_repo" dir="ltr"></div><div><label>Branch</label><input id="dep_branch" dir="ltr"></div><div><label>مسیر فایل در repository</label><input id="dep_path" dir="ltr"></div><div><label>GitHub token اختیاری</label><input id="dep_token" type="password" dir="ltr" placeholder="خالی = نگه‌داشتن قبلی / استفاده از GITHUB_TOKEN"></div><div class="wide"><label>مسیر کامل WSGI برای Reload اختیاری</label><input id="dep_reload" dir="ltr" placeholder="/var/www/USERNAME_pythonanywhere_com_wsgi.py"></div></div><div class="actions"><button onclick="saveDeploy()">ذخیره تنظیمات</button><button class="gray" onclick="deployCheck()">بررسی نسخه</button><button class="green" onclick="deployRun()">نصب نسخه تازه</button><button class="gray" onclick="deployRollback()">بازگشت به .bak</button></div><div id="deployStatus" class="status">ابتدا تنظیمات را ذخیره و سپس نسخه را بررسی کنید.</div></div></section>
</div><script>
let products=[],profiles={},currentBuild=''; const $=id=>document.getElementById(id); const esc=s=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
function openTab(name){const b=document.querySelector(`.tabs button[data-tab="${name}"]`);if(!b)return;document.querySelectorAll('.tabs button,.pane').forEach(x=>x.classList.remove('on'));b.classList.add('on');$(name).classList.add('on');localStorage.setItem('scraperActiveTab',name);window.scrollTo({top:0,behavior:'smooth'})}document.querySelectorAll('.tabs button').forEach(b=>b.onclick=()=>openTab(b.dataset.tab));
function config(){let selectors={};['container','title','price','link','image','sku'].forEach(k=>selectors[k]=$('sel_'+k).value.trim());return {url:$('url').value.trim(),pages:+$('pages').value,render:$('render').value,pagination:$('pagination').value,page_value:$('page_value').value.trim(),scrolls:+$('scrolls').value,enrich:$('enrich').value==='1',detail_limit:+$('detail_limit').value,selectors}}
function apply(c){if(!c)return;['url','pages','render','pagination','page_value','scrolls','detail_limit'].forEach(k=>{if(c[k]!==undefined)$(k).value=c[k]});$('enrich').value=c.enrich?'1':'0';Object.entries(c.selectors||{}).forEach(([k,v])=>{if($('sel_'+k))$('sel_'+k).value=v||''})}
async function api(path,opt={}){let r=await fetch(path,{...opt,headers:{'Content-Type':'application/json',...(opt.headers||{})}});let j=await r.json();if(!r.ok||j.ok===false)throw Error(j.error||'خطای درخواست');return j}
let deploySecret=sessionStorage.getItem('scraperDeployPassword')||'';
async function deployApi(path,opt={}){if(!deploySecret){deploySecret=prompt('رمز مدیریت نصب را وارد کنید:')||'';if(!deploySecret)throw Error('رمز مدیریت نصب وارد نشد');sessionStorage.setItem('scraperDeployPassword',deploySecret)}try{return await api(path,{...opt,headers:{...(opt.headers||{}),'X-Deploy-Password':deploySecret}})}catch(e){if(/رمز مدیریت نصب/.test(e.message)){deploySecret='';sessionStorage.removeItem('scraperDeployPassword')}throw e}}
async function init(){let d=await api('/api/config');currentBuild=d.build||'';$('appVersion').textContent='v'+(d.version||'1.2.0');profiles=d.profiles||{};$('timeout').value=d.network.timeout;$('gap_ms').value=d.network.gap_ms;$('proxy').value=d.network.proxy||'';$('verify_tls').checked=d.network.verify_tls!==false;$('woo_url').value=d.woocommerce.url||'';$('woo_ck').value=d.woocommerce.consumer_key||'';$('woo_cs').value=d.woocommerce.consumer_secret||'';$('dep_repo').value=d.deploy.repo||'';$('dep_branch').value=d.deploy.branch||'';$('dep_path').value=d.deploy.path||'';$('dep_reload').value=d.deploy.reload_file||'';$('dep_token').placeholder=d.deploy.has_github_token?'توکن تنظیم شده است؛ خالی = نگه‌داشتن':'GitHub token اختیاری';renderProfiles();openTab(localStorage.getItem('scraperActiveTab')||'scrape')}
async function runScrape(){const btn=$('runBtn'),old=btn.innerHTML;if(!$('url').value.trim()){$('status').innerHTML='<span class="error">لطفاً آدرس صفحه را وارد کنید.</span>';$('url').focus();return}btn.disabled=true;btn.innerHTML='<span class="spinner"></span>در حال برداشت';$('status').innerHTML='<span class="spinner"></span> در حال دریافت و تحلیل صفحات…\nاین پنجره را تا پایان عملیات باز نگه دارید.';try{let d=await api('/api/scrape',{method:'POST',body:JSON.stringify(config())});products=d.products;renderRows();$('status').innerHTML=`<span class="ok">✓ ${d.total} محصول از ${d.pages} صفحه استخراج شد</span>\nروش: ${esc(d.modes.join(' · '))}\n${esc(d.logs.join('\n'))}`;$('rows').closest('.tablebox').scrollIntoView({behavior:'smooth',block:'start'});}catch(e){$('status').innerHTML='<span class="error">✗ عملیات ناموفق بود\n'+esc(e.message)+'</span>'}finally{btn.disabled=false;btn.innerHTML=old}}
function renderRows(){if(!products.length){$('rows').innerHTML='<tr><td class="empty" colspan="6">محصولی پیدا نشد. آدرس، روش محتوا یا سلکتورها را بررسی کنید.</td></tr>';return}$('rows').innerHTML=products.map((p,i)=>`<tr><td data-label="ردیف">${i+1}</td><td data-label="تصویر">${p.image?`<img src="${esc(p.image)}" loading="lazy" alt="">`:''}</td><td data-label="عنوان">${esc(p.title)}</td><td data-label="قیمت" dir="ltr">${esc(p.price)}</td><td data-label="SKU">${esc(p.sku)}</td><td data-label="لینک">${p.link?`<a href="${esc(p.link)}" target="_blank" rel="noopener">مشاهده ↗</a>`:''}</td></tr>`).join('')}
async function saveProfilePrompt(){let name=prompt('نام پروفایل:');if(!name)return;let d=await api('/api/profile',{method:'POST',body:JSON.stringify({name,config:config()})});profiles=d.profiles;renderProfiles()}
function renderProfiles(){$('profileList').innerHTML=Object.entries(profiles).map(([n,c])=>`<div class="card"><b>${esc(n)}</b><br><small dir="ltr">${esc(c.url)}</small><div class="actions"><button onclick='loadProfile(${JSON.stringify(n)})'>بارگذاری</button><button class="gray" onclick='delProfile(${JSON.stringify(n)})'>حذف</button></div></div>`).join('')||'<div class="note">هنوز پروفایلی نیست.</div>'}
function loadProfile(n){apply(profiles[n]);document.querySelector('[data-tab="scrape"]').click()} async function delProfile(n){if(!confirm('حذف شود؟'))return;let d=await api('/api/profile/'+encodeURIComponent(n),{method:'DELETE'});profiles=d.profiles;renderProfiles()}
function downloadCSV(){location.href='/api/export.csv'}
async function saveSettings(woo=false){let body={network:{timeout:+$('timeout').value,gap_ms:+$('gap_ms').value,proxy:$('proxy').value.trim(),verify_tls:$('verify_tls').checked}};if(woo)body.woocommerce={url:$('woo_url').value.trim(),consumer_key:$('woo_ck').value.trim(),consumer_secret:$('woo_cs').value.trim()};await api('/api/settings',{method:'POST',body:JSON.stringify(body)});alert('ذخیره شد')}
async function wooTest(){try{$('wooStatus').textContent='در حال تست…';let d=await api('/api/woo/test',{method:'POST',body:'{}'});$('wooStatus').innerHTML='<span class="ok">اتصال موفق است.</span>'}catch(e){$('wooStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function wooSend(){if(!confirm('حداکثر ۲۰ محصول به صورت پیش‌نویس ساخته شود؟'))return;try{let d=await api('/api/woo/send',{method:'POST',body:JSON.stringify({products,status:'draft',limit:20})});$('wooStatus').textContent=`ارسال: ${d.sent.length}، ناموفق: ${d.failed.length}`;}catch(e){$('wooStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function saveDeploy(){let deploy={repo:$('dep_repo').value.trim(),branch:$('dep_branch').value.trim(),path:$('dep_path').value.trim(),reload_file:$('dep_reload').value.trim(),github_token:$('dep_token').value.trim()};await deployApi('/api/settings',{method:'POST',body:JSON.stringify({deploy})});$('dep_token').value='';$('deployStatus').innerHTML='<span class="ok">تنظیمات نصب ذخیره شد.</span>'}
async function deployCheck(){try{$('deployStatus').textContent='در حال بررسی GitHub…';let d=await deployApi('/api/deploy/check',{method:'POST',body:'{}'});$('deployStatus').innerHTML=`نسخه جاری: ${esc(d.version)}\nSHA محلی: ${esc(d.local_sha)}\nSHA راه دور: ${esc(d.remote_sha)}\n${d.update_available?'<span class="ok">نسخه متفاوت آماده نصب است.</span>':'نسخه محلی و راه دور یکسان‌اند.'}`;}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deployRun(){if(!confirm('فایل جاری جایگزین و نسخه قبلی در .bak ذخیره شود؟'))return;try{$('deployStatus').textContent='در حال دانلود، اعتبارسنجی و نصب…';let d=await deployApi('/api/deploy/run',{method:'POST',body:'{}'});$('deployStatus').innerHTML='<span class="ok">'+esc(d.message)+' — نسخه '+esc(d.version)+'</span>\n'+(d.reload_requested?'درخواست reload فرستاده شد.':'در صورت تنظیم‌نبودن WSGI، از تب Web دکمه Reload را بزنید.');}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function deployRollback(){if(!confirm('نسخه scraper4.py.bak بازیابی شود؟'))return;try{let d=await deployApi('/api/deploy/rollback',{method:'POST',body:'{}'});$('deployStatus').innerHTML='<span class="ok">'+esc(d.message)+' — نسخه '+esc(d.version)+'</span>';}catch(e){$('deployStatus').innerHTML='<span class="error">'+esc(e.message)+'</span>'}}
async function watchBuild(){try{let r=await fetch('/health',{cache:'no-store'}),d=await r.json();if(currentBuild&&d.build&&d.build!==currentBuild){document.body.style.opacity='.65';location.reload()}}catch(e){}}
init().then(()=>setInterval(watchBuild,30000)).catch(e=>$('status').textContent=e.message);
</script></body></html>'''


if __name__ == "__main__":
    # Local development only. PythonAnywhere imports `app` as `application`.
    app.run(host="0.0.0.0", port=int(os.environ.get("PORT", "8000")), debug=False)
