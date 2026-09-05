#!/usr/bin/env node
/**
 * snap.mjs — zero-dependency SnappShop probe + extractor for Termux/Node.
 *
 * No npm install needed (uses only Node built-ins).
 *
 * Usage:
 *   node snap.mjs probe   <url>        # diagnose: status, headers, block type
 *   node snap.mjs extract <url>        # fetch + extract products (no deps)
 *   node snap.mjs show    <file>       # pretty-print products.json
 *
 * IMPORTANT: SnappShop returns 403 "VPN را خاموش کنید" for VPN/datacenter
 * IPs. Run from your phone with VPN OFF (mobile data or home Wi-Fi in Iran).
 */
import fs from "node:fs";
import https from "node:https";
import http from "node:http";

const UA =
  "Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 Mobile Safari/537.36";

function request(url, { method = "GET", body = null } = {}) {
  return new Promise((resolve) => {
    const u = new URL(url);
    const lib = u.protocol === "https:" ? https : http;
    const req = lib.request(
      u,
      {
        method,
        timeout: 20000,
        headers: {
          "User-Agent": UA,
          Accept:
            "text/html,application/xhtml+xml,application/json;q=0.9,*/*;q=0.8",
          "Accept-Language": "fa-IR,fa;q=0.9,en;q=0.8",
          "Cache-Control": "no-cache",
          ...(body ? { "Content-Type": "application/json", "Content-Length": Buffer.byteLength(body) } : {}),
        },
      },
      (res) => {
        const chunks = [];
        res.on("data", (c) => chunks.push(c));
        res.on("end", () =>
          resolve({
            ok: true,
            status: res.statusCode,
            headers: res.headers,
            body: Buffer.concat(chunks).toString("utf-8"),
          })
        );
      }
    );
    req.on("error", (e) => resolve({ ok: false, code: e.code || e.message }));
    req.on("timeout", () => {
      req.destroy();
      resolve({ ok: false, code: "TIMEOUT" });
    });
    if (body) req.write(body);
    req.end();
  });
}

// ---------- probe ----------
async function probe(url) {
  console.log("Target:", url);
  const r = await request(url);
  if (!r.ok) {
    console.log("ERROR:", r.code, "— connection died before HTTP (TLS/IP block).");
    console.log("If a browser on this same phone works but this fails => TLS/IP fingerprint block;");
    console.log("try: Home internet, VPN OFF, or an Iranian residential proxy.");
    return;
  }
  console.log("Status:", r.status, r.headers["content-type"] || "");
  console.log("Server:", r.headers["server"] || "-");
  const sc = r.headers["set-cookie"] || [];
  if (sc.length) console.log("Set-Cookie:", sc.join(" | ").slice(0, 200));
  const b = r.body;
  const type = b.includes("VPN") || b.includes("خاموش کنید")
    ? "VPN/geo block (403 page) — turn VPN OFF or use Iranian residential IP"
    : /cloudflare|cf-chl/i.test(b)
      ? "Cloudflare challenge"
      : b.includes("sotoon") || /cdn\.edge\.sotoon/i.test(b)
        ? "Sotoon CDN"
        : /__NEXT_DATA__/i.test(b)
          ? "Next.js app (has __NEXT_DATA__ — extract from JSON)"
          : b.includes("challenge") || b.includes("captcha")
            ? "JS challenge/captcha (use Playwright)"
            : "Plain HTML/SR site";
  console.log("Block type:", type);
  console.log("---- first 500 chars ----");
  console.log(b.slice(0, 500));
  fs.writeFileSync("last_response.html", b);
  console.log("\n(saved full body to last_response.html)");
}

// ---------- dependency-free extractor (regex heuristics) ----------
function toNum(price) {
  if (price == null) return NaN;
  const n = String(price)
    .replace(/[۰-۹]/g, (d) => "۰۱۲۳۴۵۶۷۸۹".indexOf(d))
    .replace(/[٠-٩]/g, (d) => "٠١٢٣٤٥٦٧٨٩".indexOf(d))
    .replace(/[^\d.]/g, "");
  return Number(n);
}

function extract(html) {
  const products = [];
  // Split into candidate product blocks: common container-ish boundaries.
  const blocks = html.split(
    /<article\b|<div[^>]*(?:class|data-testid)=["'][^"']*(?:product|card|item|Product)[^"']*["'][^>]*>/gi
  );
  for (const blk of blocks.slice(1)) {
    const title =
      (blk.match(/<h[1-4][^>]*>([^<]{3,120})<\/h[1-4]>/i) || [])[1] ||
      (blk.match(/<img[^>]*alt=["']([^"']{3,120})["']/i) || [])[1] ||
      "";
    const matches = [...blk.matchAll(/([\d۰-۹٠-٩][\d,٬٫۰-۹٠-٩]{2,20})\s*(تومان|ریال)?/g)];
    // Prefer: price followed by تومان/ریال > value with thousand separators > longest.
    const price = (() => {
      const withUnit = matches.find((m) => m[2]);
      if (withUnit) return toNum(withUnit[1]);
      const withSep = matches.find((m) => /[,\u066c\u066b]/.test(m[1]));
      if (withSep) return toNum(withSep[1]);
      const longest = [...matches].sort((a, b) => b[1].length - a[1].length)[0];
      return longest ? toNum(longest[1]) : NaN;
    })();
    const link = (blk.match(/href=["']([^"']+)["']/i) || [])[1] || "";
    const img = (blk.match(/<img[^>]*src=["']([^"']+)["']/i) || [])[1] || "";
    if (title) products.push({ title: title.trim(), price, link, image: img });
  }
  // Deduplicate by title
  const seen = new Set();
  return products.filter((p) => {
    if (seen.has(p.title)) return false;
    seen.add(p.title);
    return true;
  });
}

async function extractUrl(url) {
  const fromFile = !/^https?:\/\//i.test(url);
  let body = "";
  let status = 200;
  if (fromFile) {
    body = fs.readFileSync(url, "utf-8");
    console.log("Local file:", url);
  } else {
    const r = await request(url);
    if (!r.ok) {
      console.log("ERROR:", r.code);
      return;
    }
    status = r.status;
    body = r.body;
  }
  if (status === 403) {
    console.log("403 VPN block — turn VPN off / use Iranian IP. (See probe for details)");
    fs.writeFileSync("last_response.html", body);
    return;
  }
  fs.writeFileSync("last_response.html", body);
  const t = body.trim();
  let products;
  if (t.startsWith("{") || t.startsWith("[")) {
    products = walkJson(JSON.parse(t));
    console.log("JSON API response — extracted", products.length, "products.");
  } else {
    products = extract(body);
    console.log("HTML page — extracted", products.length, "products (regex heuristics).");
  }
  fs.writeFileSync("products.json", JSON.stringify(products, null, 2));
  console.log("Saved -> products.json");
  return products;
}

function walkJson(node) {
  const out = [];
  const walk = (n) => {
    if (Array.isArray(n)) {
      for (const it of n) {
        if (it && typeof it === "object") {
          const t = it.title || it.name || it.product_name;
          const p = it.price_with_discount ?? it.price ?? it.sale_price;
          if (t && p != null)
            out.push({
              title: String(t),
              price: toNum(p),
              link: it.link || it.url || it.slug || "",
              image: it.image || it.thumbnail || "",
            });
          walk(it);
        }
      }
    } else if (n && typeof n === "object") for (const v of Object.values(n)) walk(v);
  };
  walk(node);
  return out;
}

const [mode, arg] = process.argv.slice(2);
const url = arg || "https://snappshop.ir/category/kitchen-appliances?is_available=true&sort=50aLgW&page=336";
if (mode === "probe") await probe(url);
else if (mode === "extract") await extractUrl(url);
else if (mode === "show")
  console.log(JSON.stringify(JSON.parse(fs.readFileSync(arg, "utf-8")), null, 2));
else console.log("Usage: node snap.mjs probe|extract [url]");
