#!/usr/bin/env python3
# NEXA Shop preview server — mirrors nexa-shop.php APIs
from __future__ import annotations
import json, os, re, sqlite3, hashlib, secrets, time, uuid, mimetypes, threading, urllib.request, urllib.error
from http.server import ThreadingHTTPServer, BaseHTTPRequestHandler
from urllib.parse import urlparse, parse_qs, unquote
from datetime import datetime, timedelta
from pathlib import Path

ROOT = Path(__file__).resolve().parent
DB = ROOT / "data" / "nexa.sqlite"
PLUGINS = ROOT / "plugins"
UPLOADS = ROOT / "uploads"
ASSETS = ROOT / "assets"
for p in (DB.parent, PLUGINS, UPLOADS):
    p.mkdir(parents=True, exist_ok=True)

STATUS_FA = {
    "pending": "در انتظار پرداخت",
    "processing": "در حال پردازش",
    "shipped": "ارسال شده",
    "completed": "تکمیل شده",
    "cancelled": "لغو شده",
    "refunded": "بازپرداخت",
}
CATS = [
    ("perfume", "عطر و زیبایی", "✦"),
    ("fashion", "مد و پوشاک", "◈"),
    ("tech", "دیجیتال", "◉"),
    ("home", "خانه و زندگی", "◇"),
    ("jewel", "زیورآلات", "✧"),
]
SEED_PRODUCTS = [
    ("عطر امپریال طلایی", "imperial-gold", "NX-P-01", "perfume", 2450000, 2180000, 18, "assets/p1.jpg",
     "رایحه‌ای گرم از کهربا، زعفران و چوب عود. بطری کریستال با درپوش طلایی — امضای نِکسا."),
    ("شال ابریشم شامپاین", "silk-champagne", "NX-F-02", "fashion", 1890000, 0, 12, "assets/p2.jpg",
     "ابریشم خالص با بافت نرم و رنگ شامپاین. مناسب شب‌های رسمی و سفرهای آرام."),
    ("ساعت هوشمند رزگلد", "watch-rosegold", "NX-T-03", "tech", 4200000, 3890000, 9, "assets/p3.jpg",
     "بدنه رزگلد، بند چرم ایتالیایی، نمایشگر همیشه روشن و مقاومت در برابر آب."),
    ("ست سرامیک دست‌ساز", "ceramic-set", "NX-H-04", "home", 1150000, 0, 20, "assets/p4.jpg",
     "سرامیک کرم و طلا، پخت دو مرحله‌ای. مناسب پذیرایی‌های کم‌شمار و دقیق."),
    ("هدفون پریمیوم نِکسا", "nexa-audio", "NX-T-05", "tech", 3750000, 3490000, 14, "assets/p5.jpg",
     "صدای استودیویی، حذف نویز تطبیقی و طراحی مات مشکی با حلقه طلایی."),
    ("گردنبند مروارید آرام", "pearl-necklace", "NX-J-06", "jewel", 5900000, 0, 6, "assets/p6.jpg",
     "مروارید آب شیرین گرید A با زنجیر طلایی ۱۸ عیار. بست مخفی ایمنی."),
    ("ست مراقبت پوست اِتوِل", "skin-atelier", "NX-P-07", "perfume", 2100000, 1890000, 22, "assets/p7.jpg",
     "سرم، تونر و کرم شب با عصاره گل محمدی و نیاسینامید. ساخت محدود."),
    ("کیف چرم دست‌دوز", "leather-bag", "NX-F-08", "fashion", 3280000, 0, 8, "assets/p8.jpg",
     "چرم گیاه‌دباغی کنیاک، یراق طلایی و دوخت زینی. هر کیف شماره سریال دارد."),
]
SAMPLE_PLUGINS = {
    "nexa-topbar/nexa-topbar.php": '''<?php
/**
 * Plugin Name: نوار اعلان نِکسا
 * Description: نمایش پیام سفارشی در نوار بالای فروشگاه
 * Version: 1.0.0
 * Author: NEXA
 */
if (!defined('ABSPATH')) exit;
add_action('wp_head', function () {
    echo "<!-- NEXA topbar plugin loaded -->\\n";
});
add_filter('nexa_topbar', function ($text) {
    return $text . '  ·  افزونه نوار اعلان فعال است';
});
''',
    "nexa-order-sms/nexa-order-sms.php": '''<?php
/**
 * Plugin Name: اعلام سفارش ووکامرس‌گونه
 * Description: هوک woocommerce_thankyou را برای اعلان تکمیلی می‌گیرد
 * Version: 1.0.0
 */
if (!defined('ABSPATH')) exit;
add_action('woocommerce_thankyou', function ($order_id) {
    do_action('nexa_log', 'woocommerce_thankyou fired for '.$order_id);
});
add_action('nexa_after_order', function ($order) {
    update_option('nexa_last_order_plugin', $order['number'] ?? '');
});
'''
}

def now():
    return datetime.now().strftime("%Y-%m-%d %H:%M:%S")

def hash_pw(pw: str) -> str:
    salt = secrets.token_hex(16)
    h = hashlib.pbkdf2_hmac("sha256", pw.encode(), salt.encode(), 120000).hex()
    return f"pbkdf2:{salt}:{h}"

def verify_pw(pw: str, stored: str) -> bool:
    try:
        _, salt, h = stored.split(":")
        calc = hashlib.pbkdf2_hmac("sha256", pw.encode(), salt.encode(), 120000).hex()
        return secrets.compare_digest(calc, h)
    except Exception:
        return False

def db():
    con = sqlite3.connect(DB, timeout=15)
    con.row_factory = sqlite3.Row
    con.execute("PRAGMA foreign_keys=ON")
    con.execute("PRAGMA journal_mode=WAL")
    return con

def init_db():
    con = db()
    con.executescript("""
    CREATE TABLE IF NOT EXISTS users(
        id INTEGER PRIMARY KEY, name TEXT, email TEXT UNIQUE, phone TEXT, pass TEXT,
        role TEXT DEFAULT 'customer', created TEXT);
    CREATE TABLE IF NOT EXISTS sessions(
        id TEXT PRIMARY KEY, user_id INTEGER, data TEXT, expires INTEGER);
    CREATE TABLE IF NOT EXISTS categories(
        id INTEGER PRIMARY KEY, slug TEXT UNIQUE, name TEXT, icon TEXT);
    CREATE TABLE IF NOT EXISTS products(
        id INTEGER PRIMARY KEY, title TEXT, slug TEXT UNIQUE, sku TEXT, cat TEXT,
        price INTEGER, sale_price INTEGER DEFAULT 0, stock INTEGER, image TEXT,
        description TEXT, featured INTEGER DEFAULT 1, status TEXT DEFAULT 'publish', created TEXT);
    CREATE TABLE IF NOT EXISTS orders(
        id INTEGER PRIMARY KEY, number TEXT UNIQUE, user_id INTEGER, items TEXT,
        subtotal INTEGER, discount INTEGER, shipping INTEGER, total INTEGER,
        status TEXT, pay TEXT, address TEXT, note TEXT, coupon TEXT, created TEXT);
    CREATE TABLE IF NOT EXISTS coupons(
        id INTEGER PRIMARY KEY, code TEXT UNIQUE, type TEXT, amount INTEGER,
        min_total INTEGER DEFAULT 0, max_uses INTEGER DEFAULT 0, used INTEGER DEFAULT 0,
        active INTEGER DEFAULT 1);
    CREATE TABLE IF NOT EXISTS reviews(
        id INTEGER PRIMARY KEY, product_id INTEGER, user_id INTEGER, name TEXT,
        rating INTEGER, text TEXT, status TEXT DEFAULT 'approved', created TEXT);
    CREATE TABLE IF NOT EXISTS wishlist(user_id INTEGER, product_id INTEGER);
    CREATE TABLE IF NOT EXISTS chats(
        id INTEGER PRIMARY KEY, thread TEXT, name TEXT, role TEXT, message TEXT, created TEXT, seen INTEGER DEFAULT 0);
    CREATE TABLE IF NOT EXISTS settings(k TEXT PRIMARY KEY, v TEXT);
    CREATE TABLE IF NOT EXISTS plugins(
        id INTEGER PRIMARY KEY, slug TEXT UNIQUE, name TEXT, version TEXT, description TEXT,
        file TEXT, active INTEGER DEFAULT 0);
    CREATE TABLE IF NOT EXISTS wp_options(
        option_id INTEGER PRIMARY KEY, option_name TEXT UNIQUE, option_value TEXT, autoload TEXT DEFAULT 'yes');
    CREATE TABLE IF NOT EXISTS notify_log(id INTEGER PRIMARY KEY, channel TEXT, payload TEXT, ok INTEGER, created TEXT);
    """)
    if con.execute("SELECT COUNT(*) FROM users").fetchone()[0] == 0:
        con.execute("INSERT INTO users(name,email,phone,pass,role,created) VALUES(?,?,?,?,?,?)",
                    ("مدیر نِکسا", "admin@nexa.shop", "09120000000", hash_pw("admin123"), "admin", now()))
        con.execute("INSERT INTO users(name,email,phone,pass,role,created) VALUES(?,?,?,?,?,?)",
                    ("سارا امینی", "sara@nexa.shop", "09121112233", hash_pw("sara123"), "customer", now()))
    if con.execute("SELECT COUNT(*) FROM categories").fetchone()[0] == 0:
        con.executemany("INSERT INTO categories(slug,name,icon) VALUES(?,?,?)", CATS)
    if con.execute("SELECT COUNT(*) FROM products").fetchone()[0] == 0:
        for t, sl, sku, cat, price, sale, stock, img, desc in SEED_PRODUCTS:
            con.execute("""INSERT INTO products(title,slug,sku,cat,price,sale_price,stock,image,description,featured,created)
                           VALUES(?,?,?,?,?,?,?,?,?,1,?)""", (t, sl, sku, cat, price, sale, stock, img, desc, now()))
        con.executemany("INSERT INTO reviews(product_id,user_id,name,rating,text,created) VALUES(?,?,?,?,?,?)", [
            (1, 2, "سارا امینی", 5, "رایحه ماندگار و بسته‌بندی فوق‌العاده شیک.", now()),
            (2, 2, "سارا امینی", 5, "ابریشم واقعی است؛ رنگ در نور عصر فوق‌العاده است.", now()),
            (3, 2, "نیما", 4, "سبک، زیبا، باتری خوب. بند چرم کیفیت بالایی دارد.", now()),
        ])
        con.execute("INSERT INTO coupons(code,type,amount,min_total,active) VALUES('NEXA10','percent',10,0,1)")
        con.execute("INSERT INTO coupons(code,type,amount,min_total,active) VALUES('WELCOME','fixed',150000,1000000,1)")
    defaults = {
        "shop_name": "نِکسا",
        "topbar": "ارسال رایگان سفارش‌های بالای ۲ میلیون تومان  ·  ضمانت اصالت کالا  ·  پشتیبانی در بله، تلگرام و روبیکا",
        "card_number": "6219-8619-0000-1234",
        "tg_token": "", "tg_chat": "",
        "bale_token": "", "bale_chat": "",
        "rb_token": "", "rb_chat": "",
        "support_welcome": "سلام، به پشتیبانی نِکسا خوش آمدید.",
    }
    for k, v in defaults.items():
        con.execute("INSERT OR IGNORE INTO settings(k,v) VALUES(?,?)", (k, v))
    con.commit(); con.close()

def setting(k, default=""):
    con = db()
    row = con.execute("SELECT v FROM settings WHERE k=?", (k,)).fetchone()
    con.close()
    return row["v"] if row else default

def set_setting(k, v):
    con = db(); con.execute("INSERT INTO settings(k,v) VALUES(?,?) ON CONFLICT(k) DO UPDATE SET v=excluded.v", (k, v)); con.commit(); con.close()

def all_settings():
    con = db(); rows = con.execute("SELECT k,v FROM settings").fetchall(); con.close()
    return {r["k"]: r["v"] for r in rows}

def product_row(r):
    if not r: return None
    d = dict(r)
    cmap = {c[0]: c[1] for c in CATS}
    d["cat_name"] = cmap.get(d.get("cat"), d.get("cat"))
    d["rating"] = 5
    return d

def cart_from_session(sess):
    return sess.get("cart") or []

def hydrate_cart(raw):
    if not raw: return []
    con = db()
    out = []
    for it in raw:
        p = con.execute("SELECT * FROM products WHERE id=?", (it["id"],)).fetchone()
        if p:
            out.append({"id": p["id"], "qty": it["qty"], "product": product_row(p)})
    con.close()
    return out

def http_json(url, payload, timeout=10):
    data = json.dumps(payload, ensure_ascii=False).encode("utf-8")
    req = urllib.request.Request(url, data=data, headers={"Content-Type": "application/json"}, method="POST")
    try:
        with urllib.request.urlopen(req, timeout=timeout) as resp:
            body = resp.read().decode("utf-8", "ignore")
            return True, body
    except Exception as e:
        return False, str(e)

def notify(event: str, title: str, text: str):
    s = all_settings()
    msg = f"✦ نِکسا | {title}\n\n{text}\n\nرویداد: {event}\nزمان: {now()}"
    results = []
    # Telegram
    if s.get("tg_token") and s.get("tg_chat"):
        ok, body = http_json(f"https://api.telegram.org/bot{s['tg_token']}/sendMessage",
                             {"chat_id": s["tg_chat"], "text": msg})
        results.append(("telegram", ok, body))
    # Bale (Telegram-compatible + business API)
    if s.get("bale_token") and s.get("bale_chat"):
        tok, chat = s["bale_token"], s["bale_chat"]
        ok, body = http_json(f"https://tapi.bale.ai/bot{tok}/sendMessage",
                             {"chat_id": chat, "text": msg})
        if not ok:
            ok, body = http_json("https://tapi.bale.ai/business/bot/sendMessage",
                                 {"chat_id": chat, "text": msg}, timeout=10)
        results.append(("bale", ok, body))
    # Rubika official bot API
    if s.get("rb_token") and s.get("rb_chat"):
        ok, body = http_json(f"https://botapi.rubika.ir/v3/{s['rb_token']}/sendMessage",
                             {"chat_id": s["rb_chat"], "text": msg})
        results.append(("rubika", ok, body))
    con = db()
    for ch, ok, body in results:
        con.execute("INSERT INTO notify_log(channel,payload,ok,created) VALUES(?,?,?,?)",
                    (ch, body[:2000], 1 if ok else 0, now()))
    if not results:
        con.execute("INSERT INTO notify_log(channel,payload,ok,created) VALUES(?,?,?,?)",
                    ("none", msg[:2000], 0, now()))
    con.commit(); con.close()
    return results

def parse_multipart(headers, body: bytes):
    ctype = headers.get("Content-Type", "")
    m = re.search(r"boundary=(.+)", ctype)
    if not m:
        return {}, {}
    boundary = m.group(1).strip().encode()
    parts = body.split(b"--" + boundary)
    fields, files = {}, {}
    for part in parts:
        if b"Content-Disposition" not in part:
            continue
        head, _, data = part.partition(b"\r\n\r\n")
        data = data.rstrip(b"\r\n")
        hs = head.decode("utf-8", "ignore")
        name = re.search(r'name="([^"]+)"', hs)
        fname = re.search(r'filename="([^"]+)"', hs)
        if not name:
            continue
        if fname:
            files[name.group(1)] = {"filename": fname.group(1), "data": data}
        else:
            fields[name.group(1)] = data.decode("utf-8", "ignore")
    return fields, files

def parse_plugin_header(src: str):
    def grab(key):
        m = re.search(rf"{key}:\s*(.+)", src)
        return m.group(1).strip() if m else ""
    return {
        "name": grab("Plugin Name") or "افزونه بدون نام",
        "version": grab("Version") or "1.0",
        "description": grab("Description") or "",
        "author": grab("Author") or "",
    }

SESSIONS = {}

class Handler(BaseHTTPRequestHandler):
    protocol_version = "HTTP/1.1"

    def log_message(self, fmt, *args):
        print("[nexa]", args[0] if args else fmt)

    def _cors(self):
        origin = self.headers.get("Origin") or "*"
        self.send_header("Access-Control-Allow-Origin", origin)
        self.send_header("Access-Control-Allow-Credentials", "true")
        self.send_header("Access-Control-Allow-Headers", "Content-Type")
        self.send_header("Access-Control-Allow-Methods", "GET,POST,OPTIONS")
        self.send_header("Cache-Control", "no-store")
        # allow iframe preview
        self.send_header("X-Frame-Options", "ALLOWALL")

    def send_bytes(self, code, data: bytes, ctype="text/html; charset=utf-8"):
        self.send_response(code)
        self._cors()
        self.send_header("Content-Type", ctype)
        self.send_header("Content-Length", str(len(data)))
        sid = getattr(self, "_set_sid", None)
        if sid:
            self.send_header("Set-Cookie", f"nexa_sid={sid}; Path=/; SameSite=Lax; HttpOnly")
        self.end_headers()
        self.wfile.write(data)

    def json(self, obj, code=200):
        raw = json.dumps(obj, ensure_ascii=False).encode("utf-8")
        self.send_bytes(code, raw, "application/json; charset=utf-8")

    def body(self):
        n = int(self.headers.get("Content-Length") or 0)
        return self.rfile.read(n) if n else b""

    def json_body(self):
        try:
            return json.loads(self.body().decode("utf-8") or "{}")
        except Exception:
            return {}

    def sid(self):
        ck = self.headers.get("Cookie") or ""
        m = re.search(r"nexa_sid=([A-Za-z0-9_\-]+)", ck)
        if m:
            return m.group(1)
        sid = secrets.token_urlsafe(18)
        self._set_sid = sid
        return sid

    def session(self):
        sid = self.sid()
        s = SESSIONS.get(sid)
        if not s:
            s = {"cart": [], "user_id": None}
            SESSIONS[sid] = s
        return s

    def user(self):
        s = self.session()
        if not s.get("user_id"):
            return None
        con = db()
        u = con.execute("SELECT id,name,email,phone,role FROM users WHERE id=?", (s["user_id"],)).fetchone()
        con.close()
        return dict(u) if u else None

    def need_admin(self):
        u = self.user()
        return u if u and u["role"] == "admin" else None

    def do_OPTIONS(self):
        self.send_bytes(204, b"")

    def do_GET(self):
        u = urlparse(self.path)
        path = unquote(u.path)
        qs = {k: v[0] if v else "" for k, v in parse_qs(u.query).items()}
        if path.startswith("/assets/"):
            return self.static(ROOT / path.lstrip("/"))
        if path.startswith("/uploads/"):
            return self.static(ROOT / path.lstrip("/"))
        if path.startswith("/api/"):
            return self.api_get(path, qs)
        if path.startswith("/hook/"):
            return self.json({"ok": True, "hint": "POST updates here"})
        return self.index()

    def do_POST(self):
        u = urlparse(self.path)
        path = unquote(u.path)
        qs = {k: v[0] if v else "" for k, v in parse_qs(u.query).items()}
        if path.startswith("/hook/"):
            return self.hook(path.rsplit("/", 1)[-1])
        if path.startswith("/api/"):
            return self.api_post(path, qs)
        self.json({"ok": False, "error": "یافت نشد"}, 404)

    def static(self, fp: Path):
        fp = fp.resolve()
        if ROOT not in fp.parents and fp != ROOT:
            return self.send_bytes(403, b"forbidden")
        if not fp.is_file():
            return self.send_bytes(404, b"not found")
        ctype = mimetypes.guess_type(str(fp))[0] or "application/octet-stream"
        data = fp.read_bytes()
        self.send_bytes(200, data, ctype)

    def index(self):
        html = (ROOT / "app.html").read_text(encoding="utf-8")
        self.send_bytes(200, html.encode("utf-8"))

    def api_get(self, path, qs):
        s = self.session()
        if path == "/api/state":
            con = db()
            cats = [dict(r) for r in con.execute("SELECT slug,name,icon FROM categories")]
            featured = [product_row(r) for r in con.execute("SELECT * FROM products WHERE status='publish' ORDER BY id LIMIT 8")]
            con.close()
            u = self.user()
            keys = ["shop_name", "topbar", "card_number"]
            if u and u.get("role") == "admin":
                keys += ["tg_token", "tg_chat", "bale_token", "bale_chat", "rb_token", "rb_chat"]
            return self.json({
                "ok": True,
                "user": u,
                "cart": hydrate_cart(s.get("cart")),
                "categories": cats,
                "featured": featured,
                "settings": {k: all_settings().get(k, "") for k in keys},
                "csrf": self.sid(),
            })
        if path == "/api/products":
            con = db()
            sql = "SELECT * FROM products WHERE status='publish'"
            args = []
            if qs.get("cat"):
                sql += " AND cat=?"; args.append(qs["cat"])
            if qs.get("q"):
                sql += " AND (title LIKE ? OR description LIKE ?)"; q = f"%{qs['q']}%"; args += [q, q]
            if qs.get("featured"):
                sql += " AND featured=1"
            sort = qs.get("sort")
            if sort == "price_asc":
                sql += " ORDER BY CASE WHEN sale_price>0 THEN sale_price ELSE price END ASC"
            elif sort == "price_desc":
                sql += " ORDER BY CASE WHEN sale_price>0 THEN sale_price ELSE price END DESC"
            elif sort == "sale":
                sql += " AND sale_price>0 ORDER BY id DESC"
            else:
                sql += " ORDER BY id DESC"
            items = [product_row(r) for r in con.execute(sql, args)]
            con.close()
            return self.json({"ok": True, "items": items})
        if path.startswith("/api/product/"):
            slug = path.split("/")[-1]
            con = db()
            p = con.execute("SELECT * FROM products WHERE slug=? OR id=?", (slug, slug if slug.isdigit() else -1)).fetchone()
            if not p:
                con.close(); return self.json({"ok": False, "error": "یافت نشد"}, 404)
            revs = [dict(r) for r in con.execute("SELECT * FROM reviews WHERE product_id=? AND status='approved'", (p["id"],))]
            rel = [product_row(r) for r in con.execute("SELECT * FROM products WHERE cat=? AND id!=? LIMIT 4", (p["cat"], p["id"]))]
            con.close()
            return self.json({"ok": True, "item": product_row(p), "reviews": revs, "related": rel})
        if path == "/api/cart":
            return self.json({"ok": True, "cart": hydrate_cart(s.get("cart"))})
        if path == "/api/orders":
            u = self.user()
            if not u: return self.json({"ok": False, "error": "ورود لازم است"}, 401)
            con = db()
            rows = con.execute("SELECT * FROM orders WHERE user_id=? ORDER BY id DESC", (u["id"],)).fetchall()
            con.close()
            items = []
            for r in rows:
                d = dict(r); d["status_fa"] = STATUS_FA.get(d["status"], d["status"]); items.append(d)
            return self.json({"ok": True, "items": items})
        if path.startswith("/api/track/"):
            num = unquote(path.split("/")[-1])
            con = db()
            r = con.execute("SELECT * FROM orders WHERE number=?", (num,)).fetchone(); con.close()
            if not r: return self.json({"ok": False, "error": "سفارش پیدا نشد"})
            d = dict(r); d["status_fa"] = STATUS_FA.get(d["status"], d["status"])
            return self.json({"ok": True, "item": d})
        if path == "/api/chat":
            thread = qs.get("thread") or ""
            con = db()
            items = [dict(r) for r in con.execute("SELECT * FROM chats WHERE thread=? ORDER BY id", (thread,))]
            con.close()
            return self.json({"ok": True, "items": items})
        if path == "/api/admin/stats":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            con = db()
            orders = con.execute("SELECT COUNT(*) c, COALESCE(SUM(total),0) t FROM orders").fetchone()
            customers = con.execute("SELECT COUNT(*) c FROM users WHERE role='customer'").fetchone()["c"]
            chats = con.execute("SELECT COUNT(DISTINCT thread) c FROM chats").fetchone()["c"]
            con.close()
            return self.json({"ok": True, "orders": orders["c"], "revenue": orders["t"], "customers": customers, "chats": chats})
        if path in ("/api/admin/products", "/api/admin/orders", "/api/admin/coupons", "/api/admin/plugins"):
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            kind = path.rsplit("/", 1)[-1]
            con = db()
            items = [dict(r) for r in con.execute(f"SELECT * FROM {kind} ORDER BY id DESC")]
            con.close()
            if kind == "orders":
                for it in items:
                    it["status_fa"] = STATUS_FA.get(it["status"], it["status"])
            return self.json({"ok": True, "items": items})
        if path == "/api/admin/chats":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            con = db()
            rows = con.execute("""SELECT thread, name, message as last, created FROM chats
                WHERE id IN (SELECT MAX(id) FROM chats GROUP BY thread) ORDER BY id DESC""").fetchall()
            con.close()
            return self.json({"ok": True, "items": [dict(r) for r in rows]})
        self.json({"ok": False, "error": "API یافت نشد"}, 404)

    def api_post(self, path, qs):
        s = self.session()
        if path == "/api/auth/login":
            b = self.json_body()
            con = db()
            u = con.execute("SELECT * FROM users WHERE email=?", ((b.get("email") or "").strip().lower(),)).fetchone()
            con.close()
            if not u or not verify_pw(b.get("password") or "", u["pass"]):
                return self.json({"ok": False, "error": "ایمیل یا رمز نادرست است"})
            s["user_id"] = u["id"]
            return self.json({"ok": True, "user": {"id": u["id"], "name": u["name"], "email": u["email"], "phone": u["phone"], "role": u["role"]}})
        if path == "/api/auth/register":
            b = self.json_body()
            name, email, phone, pw = (b.get("name") or "").strip(), (b.get("email") or "").strip().lower(), b.get("phone") or "", b.get("password") or ""
            if not name or not email or len(pw) < 4:
                return self.json({"ok": False, "error": "نام، ایمیل و رمز معتبر لازم است"})
            con = db()
            try:
                cur = con.execute("INSERT INTO users(name,email,phone,pass,role,created) VALUES(?,?,?,?,?,?)",
                                  (name, email, phone, hash_pw(pw), "customer", now()))
                uid = cur.lastrowid; con.commit()
            except sqlite3.IntegrityError:
                con.close(); return self.json({"ok": False, "error": "این ایمیل قبلاً ثبت شده"})
            con.close()
            s["user_id"] = uid
            threading.Thread(target=notify, args=("register", "عضو جدید", f"{name}\n{email}\n{phone}"), daemon=True).start()
            return self.json({"ok": True, "user": {"id": uid, "name": name, "email": email, "phone": phone, "role": "customer"}})
        if path == "/api/auth/logout":
            s["user_id"] = None
            return self.json({"ok": True})
        if path == "/api/cart":
            b = self.json_body()
            action, pid, qty = b.get("action"), int(b.get("id") or 0), int(b.get("qty") or 1)
            cart = s.setdefault("cart", [])
            if action == "add":
                found = next((x for x in cart if x["id"] == pid), None)
                if found: found["qty"] += max(1, qty)
                else: cart.append({"id": pid, "qty": max(1, qty)})
                msg = "به سبد افزوده شد"
            elif action == "set":
                cart[:] = [x for x in cart if not (x["id"] == pid and qty <= 0)]
                for x in cart:
                    if x["id"] == pid: x["qty"] = max(1, qty)
                msg = "تعداد به‌روز شد"
            elif action == "remove":
                s["cart"] = [x for x in cart if x["id"] != pid]
                msg = "حذف شد"
            else:
                msg = "سبد"
            return self.json({"ok": True, "cart": hydrate_cart(s.get("cart")), "message": msg})
        if path == "/api/coupon":
            code = (self.json_body().get("code") or "").strip().upper()
            con = db(); c = con.execute("SELECT * FROM coupons WHERE code=? AND active=1", (code,)).fetchone(); con.close()
            if not c: return self.json({"ok": False, "error": "کوپن نامعتبر است"})
            s["coupon"] = {"code": c["code"], "type": c["type"], "amount": c["amount"]}
            return self.json({"ok": True, "coupon": s["coupon"], "message": "کوپن اعمال شد"})
        if path == "/api/checkout":
            b = self.json_body()
            cart = hydrate_cart(s.get("cart"))
            if not cart: return self.json({"ok": False, "error": "سبد خالی است"})
            for it in cart:
                if it["qty"] > it["product"]["stock"]:
                    return self.json({"ok": False, "error": f"موجودی «{it['product']['title']}» کافی نیست"})
            sub = sum(it["qty"] * (it["product"]["sale_price"] or it["product"]["price"]) for it in cart)
            ship = 45000
            coupon = s.get("coupon")
            if b.get("coupon") and not coupon:
                con = db(); c = con.execute("SELECT * FROM coupons WHERE code=? AND active=1", (b["coupon"].upper(),)).fetchone(); con.close()
                if c: coupon = {"code": c["code"], "type": c["type"], "amount": c["amount"]}
            disc = 0
            if coupon:
                disc = int(sub * coupon["amount"] / 100) if coupon["type"] == "percent" else int(coupon["amount"])
            total = max(0, sub + ship - disc)
            u = self.user()
            number = "NXA-" + datetime.now().strftime("%Y%m%d") + "-" + str(secrets.randbelow(9000) + 1000)
            addr = json.dumps({"name": b.get("name"), "phone": b.get("phone"), "city": b.get("city"),
                               "address": b.get("address"), "zip": b.get("zip")}, ensure_ascii=False)
            items = json.dumps([{"id": i["product"]["id"], "title": i["product"]["title"], "qty": i["qty"],
                                 "price": i["product"]["sale_price"] or i["product"]["price"]} for i in cart], ensure_ascii=False)
            con = db()
            con.execute("""INSERT INTO orders(number,user_id,items,subtotal,discount,shipping,total,status,pay,address,note,coupon,created)
                           VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)""",
                        (number, u["id"] if u else 0, items, sub, disc, ship, total, "pending", b.get("pay") or "cod",
                         addr, b.get("note") or "", (coupon or {}).get("code", ""), now()))
            for it in cart:
                con.execute("UPDATE products SET stock=stock-? WHERE id=?", (it["qty"], it["product"]["id"]))
                row = con.execute("SELECT title,stock FROM products WHERE id=?", (it["product"]["id"],)).fetchone()
                if row and row["stock"] <= 3:
                    threading.Thread(target=notify, args=("low_stock", "موجودی کم", f"{row['title']}\nباقی‌مانده: {row['stock']}"), daemon=True).start()
            con.commit(); con.close()
            lines = "\n".join(f"• {i['product']['title']} × {i['qty']}" for i in cart)
            threading.Thread(target=notify, args=("order", "سفارش جدید "+number,
                f"{b.get('name')}\n{b.get('phone')}\n{b.get('city')}\n{lines}\nجمع: {total:,} تومان\nپرداخت: {b.get('pay')}"), daemon=True).start()
            s["cart"] = []; s["coupon"] = None
            return self.json({"ok": True, "number": number, "total": total, "pay": b.get("pay")})
        if path == "/api/review":
            u = self.user()
            if not u: return self.json({"ok": False, "error": "ورود لازم است"})
            b = self.json_body()
            con = db()
            con.execute("INSERT INTO reviews(product_id,user_id,name,rating,text,created) VALUES(?,?,?,?,?,?)",
                        (int(b.get("product_id") or 0), u["id"], u["name"], int(b.get("rating") or 5), (b.get("text") or "")[:800], now()))
            con.commit(); con.close()
            threading.Thread(target=notify, args=("review", "دیدگاه جدید", f"{u['name']}: {b.get('text')}"), daemon=True).start()
            return self.json({"ok": True, "message": "دیدگاه ثبت شد"})
        if path == "/api/wishlist":
            u = self.user()
            if not u: return self.json({"ok": False, "error": "برای علاقه‌مندی وارد شوید"})
            pid = int(self.json_body().get("id") or 0)
            con = db()
            if con.execute("SELECT 1 FROM wishlist WHERE user_id=? AND product_id=?", (u["id"], pid)).fetchone():
                con.execute("DELETE FROM wishlist WHERE user_id=? AND product_id=?", (u["id"], pid)); msg = "از علاقه‌مندی حذف شد"
            else:
                con.execute("INSERT INTO wishlist(user_id,product_id) VALUES(?,?)", (u["id"], pid)); msg = "به علاقه‌مندی افزوده شد"
            con.commit(); con.close()
            return self.json({"ok": True, "message": msg})
        if path == "/api/chat":
            b = self.json_body()
            thread = b.get("thread") or "guest"
            name = b.get("name") or "مهمان"
            msg = (b.get("message") or "").strip()
            if not msg: return self.json({"ok": False, "error": "پیام خالی"})
            con = db()
            if con.execute("SELECT COUNT(*) c FROM chats WHERE thread=?", (thread,)).fetchone()["c"] == 0:
                con.execute("INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)",
                            (thread, "نِکسا", "support", setting("support_welcome", "سلام، چطور کمک کنیم؟"), now()))
            con.execute("INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)",
                        (thread, name, "user", msg, now()))
            con.commit(); con.close()
            threading.Thread(target=notify, args=("chat", "پیام پشتیبانی", f"{name} ({thread})\n{msg}\nپاسخ: /reply {thread} متن"), daemon=True).start()
            return self.json({"ok": True})
        if path == "/api/admin/chat":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            b = self.json_body()
            con = db()
            con.execute("INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)",
                        (b.get("thread"), "پشتیبانی", "support", b.get("message") or "", now()))
            con.commit(); con.close()
            return self.json({"ok": True})
        if path == "/api/admin/products":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            b = self.json_body()
            slug = re.sub(r"[^a-z0-9\-]+", "-", (b.get("title") or "item").lower()) + "-" + str(int(time.time()) % 10000)
            con = db()
            if b.get("id"):
                con.execute("""UPDATE products SET title=?,price=?,sale_price=?,stock=?,cat=?,image=?,description=? WHERE id=?""",
                            (b.get("title"), int(b.get("price") or 0), int(b.get("sale_price") or 0), int(b.get("stock") or 0),
                             b.get("cat") or "home", b.get("image") or "", b.get("description") or "", int(b["id"])))
            else:
                con.execute("""INSERT INTO products(title,slug,sku,cat,price,sale_price,stock,image,description,featured,created)
                               VALUES(?,?,?,?,?,?,?,?,?,1,?)""",
                            (b.get("title"), slug, "NX-" + secrets.token_hex(3), b.get("cat") or "home",
                             int(b.get("price") or 0), int(b.get("sale_price") or 0), int(b.get("stock") or 0),
                             b.get("image") or "assets/p1.jpg", b.get("description") or "", now()))
            con.commit(); con.close()
            return self.json({"ok": True})
        if path == "/api/admin/orders":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            b = self.json_body()
            con = db()
            con.execute("UPDATE orders SET status=? WHERE id=?", (b.get("status"), b.get("id")))
            row = con.execute("SELECT number,status FROM orders WHERE id=?", (b.get("id"),)).fetchone()
            con.commit(); con.close()
            if row:
                threading.Thread(target=notify, args=("order_status", "وضعیت سفارش "+row["number"],
                    f"وضعیت جدید: {STATUS_FA.get(row['status'], row['status'])}"), daemon=True).start()
            return self.json({"ok": True})
        if path == "/api/admin/coupons":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            b = self.json_body()
            con = db()
            con.execute("INSERT OR REPLACE INTO coupons(code,type,amount,active) VALUES(?,?,?,1)",
                        ((b.get("code") or "").upper(), b.get("type") or "percent", int(b.get("amount") or 0)))
            con.commit(); con.close()
            return self.json({"ok": True})
        if path == "/api/admin/settings":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            b = self.json_body()
            for k, v in b.items():
                set_setting(k, str(v))
            return self.json({"ok": True, "settings": all_settings()})
        if path == "/api/admin/test-notify":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            res = notify("test", "پیام آزمایشی نِکسا", "اگر این متن را می‌بینید، اتصال پیام‌رسان درست است.")
            if not res:
                return self.json({"ok": False, "error": "توکن و Chat ID را ذخیره کنید"})
            ok = sum(1 for _, a, _ in res if a)
            return self.json({"ok": True, "message": f"{ok} از {len(res)} پیام‌رسان ارسال شد"})
        if path == "/api/admin/plugins":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            b = self.json_body()
            con = db()
            if b.get("toggle"):
                row = con.execute("SELECT active FROM plugins WHERE id=?", (b.get("id"),)).fetchone()
                if row:
                    con.execute("UPDATE plugins SET active=? WHERE id=?", (0 if row["active"] else 1, b.get("id")))
            con.commit(); con.close()
            return self.json({"ok": True})
        if path == "/api/admin/plugins/upload":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            fields, files = parse_multipart({k: self.headers[k] for k in self.headers}, self.body())
            f = files.get("plugin")
            if not f: return self.json({"ok": False, "error": "فایل ارسال نشد"})
            name = f["filename"]
            if not re.search(r"\.(php|zip)$", name, re.I):
                return self.json({"ok": False, "error": "فقط PHP یا ZIP"})
            slug = re.sub(r"[^a-zA-Z0-9_\-]+", "-", Path(name).stem)
            dest_dir = PLUGINS / slug
            dest_dir.mkdir(exist_ok=True)
            header = {"name": name, "version": "1.0", "description": ""}
            if name.lower().endswith(".php"):
                src = f["data"].decode("utf-8", "ignore")
                header = parse_plugin_header(src)
                dest = dest_dir / Path(name).name
                dest.write_text(src, encoding="utf-8")
                rel = f"{slug}/{Path(name).name}"
            else:
                zpath = dest_dir / name
                zpath.write_bytes(f["data"])
                rel = f"{slug}/{name}"
                header["description"] = "بسته ZIP افزونه وردپرس — روی PHP اجرا می‌شود"
            con = db()
            con.execute("INSERT OR REPLACE INTO plugins(slug,name,version,description,file,active) VALUES(?,?,?,?,?,0)",
                        (slug, header["name"], header["version"], header["description"], rel))
            con.commit(); con.close()
            threading.Thread(target=notify, args=("plugin", "نصب افزونه", header["name"]), daemon=True).start()
            return self.json({"ok": True, "message": "افزونه نصب شد. برای اجرای PHP از فایل nexa-shop.php استفاده کنید."})
        if path == "/api/admin/plugins/samples":
            if not self.need_admin(): return self.json({"ok": False, "error": "ممنوع"}, 403)
            con = db()
            for rel, src in SAMPLE_PLUGINS.items():
                dest = PLUGINS / rel
                dest.parent.mkdir(parents=True, exist_ok=True)
                dest.write_text(src, encoding="utf-8")
                h = parse_plugin_header(src)
                slug = rel.split("/")[0]
                con.execute("INSERT OR REPLACE INTO plugins(slug,name,version,description,file,active) VALUES(?,?,?,?,?,1)",
                            (slug, h["name"], h["version"], h["description"], rel))
            con.commit(); con.close()
            threading.Thread(target=notify, args=("plugin", "افزونه‌های نمونه", "نوار اعلان و هوک سفارش نصب شد"), daemon=True).start()
            return self.json({"ok": True, "message": "افزونه‌های نمونه نصب و فعال شدند"})
        self.json({"ok": False, "error": "API یافت نشد"}, 404)

    def hook(self, which):
        try:
            payload = json.loads(self.body().decode("utf-8") or "{}")
        except Exception:
            payload = {}
        text, chat_from = "", ""
        if which in ("telegram", "bale"):
            msg = payload.get("message") or payload.get("edited_message") or {}
            text = (msg.get("text") or "")
            chat_from = str((msg.get("chat") or {}).get("id") or "")
        elif which == "rubika":
            text = ((payload.get("message") or {}) or {}).get("text") or payload.get("text") or ""
            chat_from = str(payload.get("chat_id") or "")
        m = re.match(r"/reply\s+(\S+)\s+([\s\S]+)", text.strip())
        if m:
            thread, body = m.group(1), m.group(2)
            con = db()
            con.execute("INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)",
                        (thread, "پشتیبانی", "support", body, now()))
            con.commit(); con.close()
        elif text:
            con = db()
            con.execute("INSERT INTO chats(thread,name,role,message,created) VALUES(?,?,?,?,?)",
                        ("hook-" + which, "messenger", "user", text, now()))
            con.commit(); con.close()
        if chat_from and which == "telegram" and not setting("tg_chat"):
            set_setting("tg_chat", chat_from)
        self.json({"ok": True})


def main():
    init_db()
    port = int(os.environ.get("PORT", "8080"))
    httpd = ThreadingHTTPServer(("0.0.0.0", port), Handler)
    print(f"NEXA shop on http://0.0.0.0:{port}")
    httpd.serve_forever()

if __name__ == "__main__":
    main()
