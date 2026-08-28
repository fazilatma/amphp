import React, { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { createRoot } from 'react-dom/client';
import './storefront.css';

const PAGE_SIZE = 20;
const CART_KEY = 'amphp_sf_cart_v1';
const WISH_KEY = 'amphp_sf_wish_v1';
const PENDING_ORDER_KEY = 'amphp_sf_pending_order_v1';
const COLS_KEY = 'scraped_shop_cols';

const faDigits = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
const toFa = (v) => String(v ?? '').replace(/\d/g, (d) => faDigits[d]);
const formatMoney = (n, currency = 'تومان') => {
  const num = Number(n) || 0;
  return `${toFa(num.toLocaleString('en-US'))} ${currency}`;
};

/** Lightweight markdown → safe React nodes (bold/italic/code/links/lists/breaks). */
function escapeHtml(s) {
  return String(s ?? '')
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}
function inlineMd(raw) {
  let s = escapeHtml(raw);
  // code `x`
  s = s.replace(/`([^`]+)`/g, '<code class="sf-md-code">$1</code>');
  // bold **x** or __x__
  s = s.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
  s = s.replace(/__([^_]+)__/g, '<strong>$1</strong>');
  // italic *x* or _x_
  s = s.replace(/(^|[^*])\*([^*\n]+)\*(?!\*)/g, '$1<em>$2</em>');
  s = s.replace(/(^|[^_])_([^_\n]+)_(?!_)/g, '$1<em>$2</em>');
  // links [t](url)
  s = s.replace(/\[([^\]]+)\]\((https?:\/\/[^)\s]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer" class="sf-md-link">$1</a>');
  // bare urls
  s = s.replace(/(^|[\s(])(https?:\/\/[^\s<]+)/g, '$1<a href="$2" target="_blank" rel="noopener noreferrer" class="sf-md-link">$2</a>');
  return s;
}
function renderMarkdown(src) {
  const raw = String(src ?? '').replace(/\r\n/g, '\n').trim();
  if (!raw) return null;
  const lines = raw.split('\n');
  const blocks = [];
  let i = 0;
  while (i < lines.length) {
    const line = lines[i];
    if (/^```/.test(line)) {
      const code = [];
      i++;
      while (i < lines.length && !/^```/.test(lines[i])) { code.push(escapeHtml(lines[i])); i++; }
      i++; // skip closing
      blocks.push(`<pre class="sf-md-pre"><code>${code.join('\n')}</code></pre>`);
      continue;
    }
    if (/^\s*[-*•]\s+/.test(line) || /^\s*\d+[.)]\s+/.test(line)) {
      const items = [];
      const ordered = /^\s*\d+[.)]\s+/.test(line);
      while (i < lines.length && (/^\s*[-*•]\s+/.test(lines[i]) || /^\s*\d+[.)]\s+/.test(lines[i]))) {
        items.push(`<li>${inlineMd(lines[i].replace(/^\s*([-*•]|\d+[.)])\s+/, ''))}</li>`);
        i++;
      }
      blocks.push(ordered ? `<ol class="sf-md-list">${items.join('')}</ol>` : `<ul class="sf-md-list">${items.join('')}</ul>`);
      continue;
    }
    if (/^#{1,3}\s+/.test(line)) {
      const level = (line.match(/^#+/) || ['#'])[0].length;
      const t = inlineMd(line.replace(/^#{1,3}\s+/, ''));
      blocks.push(`<div class="sf-md-h sf-md-h${Math.min(level, 3)}">${t}</div>`);
      i++;
      continue;
    }
    if (/^\s*>\s?/.test(line)) {
      const q = [];
      while (i < lines.length && /^\s*>\s?/.test(lines[i])) {
        q.push(inlineMd(lines[i].replace(/^\s*>\s?/, '')));
        i++;
      }
      blocks.push(`<blockquote class="sf-md-quote">${q.join('<br/>')}</blockquote>`);
      continue;
    }
    if (!line.trim()) { i++; continue; }
    const para = [];
    while (i < lines.length && lines[i].trim() && !/^```/.test(lines[i]) && !/^\s*[-*•]\s+/.test(lines[i]) && !/^\s*\d+[.)]\s+/.test(lines[i]) && !/^#{1,3}\s+/.test(lines[i]) && !/^\s*>\s?/.test(lines[i])) {
      para.push(inlineMd(lines[i]));
      i++;
    }
    blocks.push(`<p class="sf-md-p">${para.join('<br/>')}</p>`);
  }
  return blocks.join('');
}

function MdBubble({ text, role }) {
  if (role === 'user') {
    return <div className="sf-bubble user">{text}</div>;
  }
  const html = renderMarkdown(text);
  return (
    <div
      className="sf-bubble bot sf-bubble-md"
      dangerouslySetInnerHTML={{ __html: html || escapeHtml(text) }}
    />
  );
}

/** Normalize Persian/Arabic digits & letters for search */
function normalizeSearch(s) {
  const fa = '۰۱۲۳۴۵۶۷۸۹';
  const ar = '٠١٢٣٤٥٦٧٨٩';
  let out = String(s ?? '').toLowerCase();
  let buf = '';
  for (let i = 0; i < out.length; i++) {
    const ch = out[i];
    const fi = fa.indexOf(ch);
    if (fi >= 0) { buf += String(fi); continue; }
    const ai = ar.indexOf(ch);
    if (ai >= 0) { buf += String(ai); continue; }
    if (ch === 'ي') { buf += 'ی'; continue; }
    if (ch === 'ك') { buf += 'ک'; continue; }
    if (/[a-z0-9\u0600-\u06FF\s]/i.test(ch)) buf += ch;
    else buf += ' ';
  }
  return buf.replace(/\s+/g, ' ').trim();
}
function productMatchesQuery(p, qNorm) {
  if (!qNorm) return true;
  const hay = normalizeSearch(`${p.title || ''} ${p.category || ''} ${p.description || ''} ${p.price_formatted || ''}`);
  const tokens = qNorm.split(' ').filter(Boolean);
  return tokens.every((t) => hay.includes(t));
}

const clamp = (n, a, b) => Math.max(a, Math.min(b, n));
const safeJson = (raw, fb) => {
  try { return JSON.parse(raw); } catch { return fb; }
};
const loadLS = (k, fb) => {
  try { return safeJson(localStorage.getItem(k), fb) ?? fb; } catch { return fb; }
};
const saveLS = (k, v) => {
  try { localStorage.setItem(k, JSON.stringify(v)); } catch {}
};

/** Woo/gateway return: order-received URL or ?amphp_paid=1 */
function detectOrderReturn() {
  try {
    const u = new URL(window.location.href);
    const q = u.searchParams;
    const path = (u.pathname || '').toLowerCase();
    const orderId = q.get('order_id') || q.get('order') || q.get('amphp_order') || '';
    const orderKey = q.get('key') || q.get('order_key') || q.get('amphp_key') || '';
    const paidFlag = q.get('amphp_paid') === '1' || q.get('payment') === 'success' || q.get('status') === 'paid';
    const onReceived = /order-received|order_received|thank/.test(path) || q.has('order-received');
    const pathOid = (path.match(/order-received\/(\d+)/) || [])[1] || '';
    if (paidFlag || onReceived || (orderId && orderKey) || pathOid) {
      return { order_id: orderId || pathOid, order_key: orderKey, paid: true };
    }
  } catch {}
  return null;
}

function clearOrderReturnParams() {
  try {
    const u = new URL(window.location.href);
    let dirty = false;
    ['amphp_paid', 'amphp_order', 'amphp_key', 'order_id', 'order', 'key', 'order_key', 'payment', 'status'].forEach((k) => {
      if (u.searchParams.has(k)) { u.searchParams.delete(k); dirty = true; }
    });
    if (dirty) {
      const next = u.pathname + (u.searchParams.toString() ? '?' + u.searchParams.toString() : '') + (u.hash || '');
      window.history.replaceState({}, '', next);
    }
  } catch {}
}

const FONT_KEY = (typeof window !== 'undefined' && window.APP_FONT_KEY) ? window.APP_FONT_KEY : 'scraper_font';
const FONT_FALLBACK = 'Tahoma,system-ui,-apple-system,sans-serif';

/** Font registry aligned with scraper4.php app_fonts_registry() */
const SF_FONTS = {
  system: { stack: FONT_FALLBACK, css: '', face: '' },
  vazirmatn: {
    stack: 'Vazirmatn,' + FONT_FALLBACK,
    css: 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css',
    face: '',
  },
  vazir: {
    stack: 'Vazir,' + FONT_FALLBACK,
    css: 'https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@30.1.0/dist/font-face.css',
    face: '',
  },
  sahel: {
    stack: 'Sahel,' + FONT_FALLBACK,
    css: 'https://cdn.jsdelivr.net/gh/rastikerdar/sahel-font@3.4.0/dist/font-face.css',
    face: '',
  },
  samim: {
    stack: 'Samim,' + FONT_FALLBACK,
    css: 'https://cdn.jsdelivr.net/gh/rastikerdar/samim-font@4.0.5/dist/font-face.css',
    face: '',
  },
  shabnam: {
    stack: 'Shabnam,' + FONT_FALLBACK,
    css: 'https://cdn.jsdelivr.net/gh/rastikerdar/shabnam-font@5.0.1/dist/font-face.css',
    face: '',
  },
  parastoo: {
    stack: 'Parastoo,' + FONT_FALLBACK,
    css: 'https://cdn.jsdelivr.net/gh/rastikerdar/parastoo-font@2.0.0/dist/font-face.css',
    face: '',
  },
  estedad: {
    stack: 'Estedad,' + FONT_FALLBACK,
    css: '',
    face:
      "@font-face{font-family:'Estedad';src:url('https://cdn.jsdelivr.net/gh/aminabedi68/Estedad@8.5/fonts/Statics/webfonts/Estedad-Regular.woff2') format('woff2');font-weight:400;font-style:normal;font-display:swap}" +
      "@font-face{font-family:'Estedad';src:url('https://cdn.jsdelivr.net/gh/aminabedi68/Estedad@8.5/fonts/Statics/webfonts/Estedad-Medium.woff2') format('woff2');font-weight:500;font-style:normal;font-display:swap}" +
      "@font-face{font-family:'Estedad';src:url('https://cdn.jsdelivr.net/gh/aminabedi68/Estedad@8.5/fonts/Statics/webfonts/Estedad-SemiBold.woff2') format('woff2');font-weight:600;font-style:normal;font-display:swap}" +
      "@font-face{font-family:'Estedad';src:url('https://cdn.jsdelivr.net/gh/aminabedi68/Estedad@8.5/fonts/Statics/webfonts/Estedad-Bold.woff2') format('woff2');font-weight:700;font-style:normal;font-display:swap}",
  },
  iranyekan: {
    stack: 'IRANYekan,' + FONT_FALLBACK,
    css: '',
    face:
      "@font-face{font-family:'IRANYekan';src:url('https://cdn.jsdelivr.net/gh/morajabi/balast-website@master/static/fonts/iranyekan/woff2/iranyekanweblight.woff2') format('woff2');font-weight:300;font-style:normal;font-display:swap}" +
      "@font-face{font-family:'IRANYekan';src:url('https://cdn.jsdelivr.net/gh/morajabi/balast-website@master/static/fonts/iranyekan/woff2/iranyekanwebregular.woff2') format('woff2');font-weight:400;font-style:normal;font-display:swap}" +
      "@font-face{font-family:'IRANYekan';src:url('https://cdn.jsdelivr.net/gh/morajabi/balast-website@master/static/fonts/iranyekan/woff2/iranyekanwebbold.woff2') format('woff2');font-weight:700;font-style:normal;font-display:swap}",
  },
  yekan: {
    stack: 'Yekan,' + FONT_FALLBACK,
    css: '',
    face: "@font-face{font-family:'Yekan';src:url('https://cdn.jsdelivr.net/gh/hemedani/yekan@3.0.0/Yekan.woff') format('woff');font-weight:normal;font-style:normal;font-display:swap}",
  },
};
// agent.php admin aliases → scraper4 keys
SF_FONTS.dana = SF_FONTS.vazirmatn;
SF_FONTS.yekanbakh = SF_FONTS.yekan;
SF_FONTS.iransans = SF_FONTS.iranyekan;
SF_FONTS.morabba = SF_FONTS.sahel;
SF_FONTS.custom = SF_FONTS.vazirmatn;

function resolveFontKey(preferred) {
  try {
    if (typeof window !== 'undefined') {
      if (typeof window.appFontCurrent === 'function') {
        const k = window.appFontCurrent();
        if (k && (window.APP_FONTS?.[k] || SF_FONTS[k])) return k;
      }
      const ls = localStorage.getItem(FONT_KEY);
      if (ls && (window.APP_FONTS?.[ls] || SF_FONTS[ls])) return ls;
    }
  } catch (_) {}
  const pref = String(preferred || '').trim();
  if (pref && SF_FONTS[pref]) return pref;
  return 'vazirmatn';
}

function applyStorefrontFont(key) {
  const k = resolveFontKey(key);
  if (typeof window !== 'undefined' && typeof window.appFontApply === 'function' && window.APP_FONTS) {
    try { window.appFontApply(k, false); return k; } catch (_) {}
  }
  const pack = (typeof window !== 'undefined' && window.APP_FONTS && window.APP_FONTS[k])
    ? window.APP_FONTS[k]
    : (SF_FONTS[k] || SF_FONTS.system);
  const stack = pack.stack || FONT_FALLBACK;
  try {
    document.documentElement.style.setProperty('--app-font', stack);
    if (document.body) document.body.style.fontFamily = stack;
  } catch (_) {}
  if (pack.css) {
    const lid = 'sfFontLink_' + k;
    if (!document.getElementById(lid)) {
      const l = document.createElement('link');
      l.id = lid; l.rel = 'stylesheet'; l.href = pack.css;
      (document.head || document.documentElement).appendChild(l);
    }
  }
  if (pack.face) {
    const sid = 'sfFontFace_' + k;
    if (!document.getElementById(sid)) {
      const s = document.createElement('style');
      s.id = sid; s.appendChild(document.createTextNode(pack.face));
      (document.head || document.documentElement).appendChild(s);
    }
  }
  return k;
}

const PALETTE_ACCENTS = {
  'digikala-red': '#ef394e',
  'snapp-green': '#00d170',
  'basalam-coral': '#ff6b35',
  'torob-red': '#d32f2f',
  'digistyle-rose': '#e91e63',
  'technolife-blue': '#1a73e8',
  'royal-blue': '#2563eb',
  'luxury-purple': '#7c3aed',
  'amber-gold': '#d97706',
  'persian-turquoise': '#0d9488',
  'midnight-ink': '#6366f1',
  forest: '#16a34a',
  sunset: '#f97316',
  modern: '#2563eb',
  midnight: '#6366f1',
  minimal: '#0f172a',
  bazaar: '#ea580c',
  boutique: '#db2777',
};

const IRAN_PROVINCES = [
  'تهران','اصفهان','فارس','خراسان رضوی','آذربایجان شرقی','آذربایجان غربی','خوزستان','مازندران','گیلان','کرمان',
  'البرز','قم','قزوین','همدان','کرمانشاه','یزد','سیستان و بلوچستان','گلستان','لرستان','مرکزی','هرمزگان',
  'بوشهر','زنجان','اردبیل','کردستان','سمنان','چهارمحال و بختیاری','کهگیلویه و بویراحمد','ایلام','خراسان شمالی','خراسان جنوبی',
];


function useAdminBarOffset() {
  const [top, setTop] = useState(0);
  useEffect(() => {
    const measure = () => {
      const bar = document.getElementById('wpadminbar');
      if (!bar) { setTop(0); return; }
      const cs = getComputedStyle(bar);
      if (cs.display === 'none' || cs.visibility === 'hidden') { setTop(0); return; }
      if (cs.position !== 'fixed' && cs.position !== 'sticky') { setTop(0); return; }
      setTop(bar.offsetHeight || (window.innerWidth <= 782 ? 46 : 32));
    };
    measure();
    window.addEventListener('resize', measure, { passive: true });
    return () => window.removeEventListener('resize', measure);
  }, []);
  useEffect(() => {
    document.documentElement.style.setProperty('--sf-adminbar', `${top}px`);
  }, [top]);
  return top;
}

function useScrollProgress() {
  const [progress, setProgress] = useState(0);
  const [scrolled, setScrolled] = useState(false);
  useEffect(() => {
    let ticking = false;
    const onScroll = () => {
      if (ticking) return;
      ticking = true;
      requestAnimationFrame(() => {
        ticking = false;
        const y = window.pageYOffset || document.documentElement.scrollTop || 0;
        const max = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
        setProgress(clamp((y / max) * 100, 0, 100));
        setScrolled(y > 48);
      });
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    return () => window.removeEventListener('scroll', onScroll);
  }, []);
  return { progress, scrolled };
}

function useCountdown(hours = 8) {
  const endRef = useRef(Date.now() + hours * 3600 * 1000);
  const [left, setLeft] = useState(endRef.current - Date.now());
  useEffect(() => {
    const t = setInterval(() => setLeft(Math.max(0, endRef.current - Date.now())), 1000);
    return () => clearInterval(t);
  }, []);
  const total = Math.floor(left / 1000);
  const h = String(Math.floor(total / 3600)).padStart(2, '0');
  const m = String(Math.floor((total % 3600) / 60)).padStart(2, '0');
  const s = String(total % 60).padStart(2, '0');
  return { h, m, s };
}

function ToastHost({ toasts, dismiss }) {
  if (!toasts.length) return null;
  return (
    <div className="sf-toast-wrap" aria-live="polite">
      {toasts.map((t) => (
        <div key={t.id} className={`sf-toast ${t.type || ''}`} onClick={() => dismiss(t.id)}>
          {t.text}
        </div>
      ))}
    </div>
  );
}

function ProductCard({ p, cols, currency, wish, onWish, onQuick, onAdd, showSpecial, template }) {
  const templatePill = {
    digikala: '🚚 ارسال امروز با اکسپرس',
    snappshop: '⚡ تحویل فوری با اسنپ',
    basalam: '⭐ غرفه برتر باسلام',
    torob: '🏷️ کمترین قیمت بازار',
    technolife: '⚡ گارانتی اصالت کالا',
    digistyle: '✨ کالکشن ویژه',
  }[template];

  return (
    <article className="sf-card" data-id={p.id}>
      <div className="sf-thumb">
        <button type="button" className={`sf-wish ${wish ? 'on' : ''}`} onClick={() => onWish(p.id)} aria-label="علاقه‌مندی">
          {wish ? '♥' : '♡'}
        </button>
        <span className="sf-stock">موجود در انبار</span>
        {p.has_discount && p.discount_pct ? (
          <span className="sf-disc">{toFa(p.discount_pct)}٪ تخفیف</span>
        ) : null}
        {p.image ? (
          <img
            src={p.image}
            alt={p.title}
            loading="lazy"
            onError={(e) => {
              e.currentTarget.style.display = 'none';
              e.currentTarget.parentElement.querySelector('.sf-thumb-empty')?.classList.add('show');
            }}
          />
        ) : null}
        <div className="sf-thumb-empty" style={{ display: p.image ? 'none' : 'grid' }}>📦</div>
      </div>
      <div className="sf-card-body">
        <div className="sf-card-cat">{p.category || 'عمومی'}{templatePill ? ` · ${templatePill}` : ''}</div>
        <h3 className="sf-card-title" title={p.title}>{p.title}</h3>
        <div className="sf-price-row">
          <div>
            {p.has_discount && (p.old_price_formatted || p.old_price) ? (
              <div className="sf-old">{p.old_price_formatted || formatMoney(p.old_price, currency)}</div>
            ) : showSpecial && !p.has_discount ? (
              <div className="sf-chip" style={{ display: 'inline-flex' }}>✨ پیشنهاد ویژه</div>
            ) : <span />}
            <div className="sf-price">{p.price_formatted || formatMoney(p.price, currency)}</div>
          </div>
        </div>
        <div className="sf-card-actions">
          <button type="button" className="sf-btn ghost" onClick={() => onQuick(p)}>مشاهده مشخصات</button>
          <button type="button" className="sf-btn primary" onClick={() => onAdd(p)}>افزودن به سبد</button>
        </div>
      </div>
    </article>
  );
}

function CartDrawer({ open, onClose, items, currency, onQty, onRemove, onCheckout, busy, freeShip }) {
  if (!open) return null;
  const total = items.reduce((s, it) => s + (Number(it.price) || 0) * (it.qty || 1), 0);
  const thr = Number(freeShip) || 0;
  const remain = thr > 0 ? Math.max(0, thr - total) : 0;
  const progress = thr > 0 ? Math.min(100, Math.round((total / thr) * 100)) : 0;
  return (
    <>
      <div className="sf-overlay" onClick={onClose} />
      <aside className="sf-drawer right" role="dialog" aria-label="سبد خرید">
        <div className="sf-drawer-head">
          <h3>🛒 سبد خرید ({toFa(items.length)})</h3>
          <button type="button" className="sf-close" onClick={onClose}>✕</button>
        </div>
        <div className="sf-drawer-body">
          {thr > 0 && items.length ? (
            <div className="sf-ship-bar">
              {remain > 0
                ? <span>فقط {formatMoney(remain, currency)} تا ارسال رایگان 🚚</span>
                : <span className="ok">ارسال این سفارش رایگان است ✓</span>}
              <div className="sf-ship-track"><i style={{ width: `${progress}%` }} /></div>
            </div>
          ) : null}
          {!items.length ? (
            <div className="sf-empty" style={{ border: 'none', boxShadow: 'none' }}>
              <div style={{ fontSize: '2.4rem' }}>🛍️</div>
              <h4>سبد خرید خالی است</h4>
              <p>کالای مورد علاقه‌تان را اضافه کنید.</p>
            </div>
          ) : items.map((it) => (
            <div className="sf-cart-item" key={it.id}>
              {it.image ? <img src={it.image} alt="" /> : <div style={{ width: 64, height: 64, borderRadius: 12, background: '#f1f5f9', display: 'grid', placeItems: 'center' }}>📦</div>}
              <div>
                <h4>{it.title}</h4>
                <div className="meta">{it.price_txt || formatMoney(it.price, currency)}</div>
                <div className="sf-qty">
                  <button type="button" onClick={() => onQty(it.id, (it.qty || 1) - 1)}>−</button>
                  <strong>{toFa(it.qty || 1)}</strong>
                  <button type="button" onClick={() => onQty(it.id, (it.qty || 1) + 1)}>＋</button>
                </div>
              </div>
              <button type="button" className="sf-close" onClick={() => onRemove(it.id)} title="حذف">🗑</button>
            </div>
          ))}
        </div>
        <div className="sf-drawer-foot">
          <div className="sf-cart-total">
            <span>جمع کل</span>
            <span>{formatMoney(total, currency)}</span>
          </div>
          <button type="button" className="sf-btn primary block lg" disabled={!items.length || busy} onClick={onCheckout}>
            {busy ? 'در حال آماده‌سازی…' : 'ادامه و تسویه‌حساب'}
          </button>
        </div>
      </aside>
    </>
  );
}

function MenuDrawer({ open, onClose, settings, categories, onCat, cartCount, onOpenCart, accountUrl, adminUrl }) {
  if (!open) return null;
  return (
    <>
      <div className="sf-overlay" onClick={onClose} />
      <aside className="sf-drawer left" role="dialog" aria-label="منو">
        <div className="sf-drawer-head sf-menu-head">
          <div className="sf-menu-head-title">
            <span className="sf-burger-ico" aria-hidden>
              <i /><i /><i />
            </span>
            <h3>منوی فروشگاه</h3>
          </div>
          <button type="button" className="sf-close" onClick={onClose}>✕</button>
        </div>
        <div className="sf-drawer-body">
          <p style={{ fontWeight: 800, color: '#64748b', marginTop: 0 }}>{settings.shop_subtitle}</p>
          <div style={{ display: 'grid', gap: 8 }}>
            <button type="button" className="sf-action-btn" style={{ justifyContent: 'flex-start' }} onClick={() => { onCat('all'); onClose(); window.scrollTo({ top: 0, behavior: 'smooth' }); }}>🏠 صفحه اصلی</button>
            <button type="button" className="sf-action-btn" style={{ justifyContent: 'flex-start' }} onClick={() => { onOpenCart(); onClose(); }}>🛒 سبد خرید ({toFa(cartCount)})</button>
            <a className="sf-action-btn" style={{ justifyContent: 'flex-start' }} href={accountUrl}>👤 حساب کاربری</a>
            {adminUrl ? <a className="sf-action-btn" style={{ justifyContent: 'flex-start' }} href={adminUrl}>⚙️ مدیریت</a> : null}
            {settings.contact_phone ? <a className="sf-action-btn" style={{ justifyContent: 'flex-start' }} href={`tel:${settings.contact_phone}`}>📞 {settings.contact_phone}</a> : null}
          </div>
          <h4 style={{ margin: '18px 0 8px', fontWeight: 900 }}>دسته‌بندی‌ها</h4>
          <div style={{ display: 'grid', gap: 4 }}>
            <button type="button" className="sf-mega-item" onClick={() => { onCat('all'); onClose(); }}>همه دسته‌ها</button>
            {Object.entries(categories).map(([name, count]) => (
              <button type="button" key={name} className="sf-mega-item" onClick={() => { onCat(name); onClose(); }}>
                <span>📂 {name}</span>
                <span className="sf-mega-count">{toFa(count)}</span>
              </button>
            ))}
          </div>
        </div>
      </aside>
    </>
  );
}

function QuickView({ product, currency, onClose, onAdd }) {
  if (!product) return null;
  return (
    <div className="sf-modal" role="dialog" aria-modal="true">
      <div className="sf-overlay" onClick={onClose} />
      <div className="sf-modal-card">
        <button type="button" className="sf-close sf-modal-close" onClick={onClose}>✕</button>
        <div className="sf-modal-grid">
          <div className="sf-modal-img">
            {product.image ? <img src={product.image} alt={product.title} /> : <div style={{ fontSize: '4rem' }}>📦</div>}
          </div>
          <div className="sf-modal-info">
            <div className="cat">{product.category || 'عمومی'}</div>
            <h3>{product.title}</h3>
            {product.has_discount && (product.old_price_formatted || product.old_price) ? (
              <div className="sf-old">{product.old_price_formatted || formatMoney(product.old_price, currency)}</div>
            ) : null}
            <div className="sf-price" style={{ fontSize: '1.35rem', marginTop: 4 }}>
              {product.price_formatted || formatMoney(product.price, currency)}
            </div>
            <div className="desc">{product.description || 'توضیحات تکمیلی این کالا به‌زودی تکمیل می‌شود. اصالت کالا و ارسال سریع تضمین شده است.'}</div>
            <button type="button" className="sf-btn primary lg" onClick={() => { onAdd(product); onClose(); }}>افزودن به سبد خرید</button>
          </div>
        </div>
      </div>
    </div>
  );
}


function fieldOn(settings, key, defOn = true) {
  const v = settings?.[key];
  if (v === undefined || v === null) return defOn;
  return !!v;
}

function CheckoutPage({
  open, onClose, items, currency, settings, ajax, gateways: bootGateways,
  onQty, onRemove, onClearCart, toast, initialDone = null,
}) {
  const [gateways, setGateways] = useState(() => Array.isArray(bootGateways) ? bootGateways : []);
  const [payment, setPayment] = useState('');
  const [busy, setBusy] = useState(false);
  const [done, setDone] = useState(() => (initialDone && typeof initialDone === 'object' ? initialDone : null));
  const [err, setErr] = useState('');
  const [form, setForm] = useState({
    name: '', phone: '', email: '', province: '', city: '', address: '', postal: '', notes: '',
  });

  useEffect(() => {
    if (!open) return;
    setErr('');
    if (initialDone && typeof initialDone === 'object' && initialDone.phase === 'complete') {
      setDone(initialDone);
    } else if (!done || done.phase !== 'complete') {
      if (!initialDone) setDone(null);
    }
    let cancelled = false;
    (async () => {
      try {
        const fd = new FormData();
        fd.append('action', 'scraper_get_payment_gateways');
        const res = await fetch(ajax.ajaxUrl || '/wp-admin/admin-ajax.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const data = await res.json().catch(() => ({}));
        const list = data?.data?.gateways || data?.gateways || [];
        if (!cancelled && Array.isArray(list) && list.length) {
          setGateways(list);
          setPayment((p) => p || list[0].id);
        } else if (!cancelled && (!gateways || !gateways.length)) {
          const fallback = [{ id: 'cod', title: settings.checkout_cod_label || 'پرداخت در محل', description: 'پرداخت هنگام تحویل', icon: '' }];
          setGateways(fallback);
          setPayment('cod');
        }
      } catch {
        if (!cancelled && !gateways.length) {
          setGateways([{ id: 'cod', title: settings.checkout_cod_label || 'پرداخت در محل', description: '', icon: '' }]);
          setPayment('cod');
        }
      }
    })();
    return () => { cancelled = true; };
  }, [open]);

  useEffect(() => {
    if (gateways.length && !payment) setPayment(gateways[0].id);
  }, [gateways, payment]);

  if (!open) return null;

  const total = items.reduce((s, it) => s + (Number(it.price) || 0) * (it.qty || 1), 0);
  const set = (k, v) => setForm((f) => ({ ...f, [k]: v }));

  const validate = () => {
    const checks = [
      ['name', 'checkout_field_name', 'checkout_field_name_req', 'نام و نام خانوادگی', true],
      ['phone', 'checkout_field_phone', 'checkout_field_phone_req', 'شماره موبایل', true],
      ['email', 'checkout_field_email', 'checkout_field_email_req', 'ایمیل', false],
      ['province', 'checkout_field_province', 'checkout_field_province_req', 'استان', true],
      ['city', 'checkout_field_city', 'checkout_field_city_req', 'شهر', true],
      ['address', 'checkout_field_address', 'checkout_field_address_req', 'آدرس', true],
      ['postal', 'checkout_field_postal', 'checkout_field_postal_req', 'کد پستی', false],
      ['notes', 'checkout_field_notes', 'checkout_field_notes_req', 'توضیحات', false],
    ];
    for (const [key, en, req, label, defOn] of checks) {
      if (fieldOn(settings, en, defOn) && fieldOn(settings, req, false) && !String(form[key] || '').trim()) {
        return `لطفاً فیلد «${label}» را تکمیل کنید.`;
      }
    }
    if (fieldOn(settings, 'checkout_show_gateways', true) && gateways.length && !payment) {
      return 'روش پرداخت را انتخاب کنید.';
    }
    if (!items.length) return 'سبد خرید خالی است.';
    return '';
  };

  const placeOrder = async () => {
    const v = validate();
    if (v) { setErr(v); return; }
    setBusy(true); setErr('');
    try {
      const fd = new FormData();
      fd.append('action', 'scraper_custom_checkout_place_order');
      fd.append('nonce', ajax.cartNonce || '');
      fd.append('items', JSON.stringify(items.map((it) => ({
        id: it.id, title: it.title, price: it.price, qty: it.qty || 1, image: it.image || '',
      }))));
      Object.entries(form).forEach(([k, val]) => fd.append(k, val));
      fd.append('payment_method', payment);
      const res = await fetch(ajax.ajaxUrl || '/wp-admin/admin-ajax.php', { method: 'POST', body: fd, credentials: 'same-origin' });
      const data = await res.json().catch(() => ({}));
      if (!data?.success) {
        setErr(data?.data || data?.message || 'ثبت سفارش ناموفق بود.');
        return;
      }
      const payload = data.data || {};
      const needsPay = !!(payload.needs_payment || payload.pay_url)
        && payload.payment_method
        && payload.payment_method !== 'cod'
        && !payload.is_cod;
      if (needsPay && payload.pay_url) {
        /* هنوز به درگاه نرفته — پیام «سفارش کامل شد» نگو؛ مستقیم بفرست */
        try {
          saveLS(PENDING_ORDER_KEY, {
            order_id: payload.order_id,
            order_key: payload.order_key,
            total: payload.total,
            total_formatted: payload.total_formatted,
            payment_method: payload.payment_method,
            payment_title: payload.payment_title,
            thankyou_url: payload.thankyou_url || '',
            message: payload.message || settings.checkout_success_msg || '',
            at: Date.now(),
          });
        } catch {}
        onClearCart?.();
        toast?.('در حال انتقال به درگاه پرداخت…', 'ok');
        setDone({ ...payload, phase: 'redirecting' });
        setTimeout(() => {
          try { window.location.href = payload.pay_url; } catch {
            setDone({ ...payload, phase: 'awaiting_payment' });
          }
        }, 450);
        return;
      }
      try { localStorage.removeItem(PENDING_ORDER_KEY); } catch {}
      setDone({ ...payload, phase: 'complete', paid: true });
      onClearCart?.();
      toast?.(payload.message || settings.checkout_success_msg || 'سفارش شما ثبت شد', 'ok');
    } catch {
      setErr('خطا در ارتباط با سرور. دوباره تلاش کنید.');
    } finally {
      setBusy(false);
    }
  };

  const showGw = fieldOn(settings, 'checkout_show_gateways', true);

  return (
    <div className="sf-checkout" role="dialog" aria-modal="true" aria-label="تسویه حساب">
      <div className="sf-checkout-top">
        <button type="button" className="sf-checkout-back" onClick={onClose}>→ بازگشت به فروشگاه</button>
        <div>
          <h2>{settings.checkout_title || 'تسویه حساب امن'}</h2>
          {settings.checkout_note ? <p>{settings.checkout_note}</p> : null}
        </div>
        <div className="sf-checkout-steps">
          <span className="on">۱. سبد</span>
          <span className="on">۲. اطلاعات</span>
          <span className={done && done.phase === 'complete' ? 'on' : (done ? 'mid' : '')}>۳. پرداخت</span>
        </div>
      </div>

      {done ? (
        <div className={`sf-checkout-success ${(done.phase === 'redirecting' || done.phase === 'awaiting_payment') ? 'pending' : 'complete'}`}>
          {(done.phase === 'redirecting' || done.phase === 'awaiting_payment') ? (
            <>
              <div className="ico">⏳</div>
              <h3>در حال انتقال به درگاه پرداخت…</h3>
              <p>سفارش ثبت شد؛ لطفاً پرداخت را در درگاه تکمیل کنید.</p>
              <p>شماره سفارش: <strong>{toFa(done.order_id)}</strong></p>
              <p>مبلغ: <strong>{done.total_formatted || formatMoney(done.total, currency)}</strong></p>
              {done.pay_url ? (
                <a className="sf-btn primary lg" href={done.pay_url}>ورود به درگاه پرداخت ↗</a>
              ) : null}
              <p className="sf-co-hint">پیام تکمیل سفارش فقط پس از بازگشت موفق از درگاه نمایش داده می‌شود.</p>
            </>
          ) : (
            <>
              <div className="ico">✓</div>
              <h3>{done.message || settings.checkout_success_msg || 'سفارش شما با موفقیت تکمیل شد'}</h3>
              <p>شماره سفارش: <strong>{toFa(done.order_id)}</strong></p>
              <p>مبلغ: <strong>{done.total_formatted || formatMoney(done.total, currency)}</strong></p>
              <p>روش پرداخت: <strong>{done.payment_title || done.payment_method}</strong></p>
              <button type="button" className="sf-btn primary lg" onClick={onClose}>بازگشت به فروشگاه</button>
            </>
          )}
        </div>
      ) : (
        <div className="sf-checkout-grid">
          <div className="sf-checkout-main">
            <section className="sf-co-card">
              <h3>اطلاعات گیرنده</h3>
              <div className="sf-co-fields">
                {fieldOn(settings, 'checkout_field_name', true) ? (
                  <label className="sf-co-field">
                    <span>نام و نام خانوادگی{fieldOn(settings, 'checkout_field_name_req', true) ? ' *' : ''}</span>
                    <input value={form.name} onChange={(e) => set('name', e.target.value)} autoComplete="name" />
                  </label>
                ) : null}
                {fieldOn(settings, 'checkout_field_phone', true) ? (
                  <label className="sf-co-field">
                    <span>موبایل{fieldOn(settings, 'checkout_field_phone_req', true) ? ' *' : ''}</span>
                    <input value={form.phone} onChange={(e) => set('phone', e.target.value)} inputMode="tel" dir="ltr" autoComplete="tel" placeholder="09xxxxxxxxx" />
                  </label>
                ) : null}
                {fieldOn(settings, 'checkout_field_email', false) ? (
                  <label className="sf-co-field">
                    <span>ایمیل{fieldOn(settings, 'checkout_field_email_req', false) ? ' *' : ''}</span>
                    <input value={form.email} onChange={(e) => set('email', e.target.value)} type="email" dir="ltr" autoComplete="email" />
                  </label>
                ) : null}
                {fieldOn(settings, 'checkout_field_province', true) ? (
                  <label className="sf-co-field">
                    <span>استان{fieldOn(settings, 'checkout_field_province_req', true) ? ' *' : ''}</span>
                    <select value={form.province} onChange={(e) => set('province', e.target.value)}>
                      <option value="">انتخاب استان</option>
                      {IRAN_PROVINCES.map((p) => <option key={p} value={p}>{p}</option>)}
                    </select>
                  </label>
                ) : null}
                {fieldOn(settings, 'checkout_field_city', true) ? (
                  <label className="sf-co-field">
                    <span>شهر{fieldOn(settings, 'checkout_field_city_req', true) ? ' *' : ''}</span>
                    <input value={form.city} onChange={(e) => set('city', e.target.value)} autoComplete="address-level2" />
                  </label>
                ) : null}
                {fieldOn(settings, 'checkout_field_address', true) ? (
                  <label className="sf-co-field full">
                    <span>آدرس کامل{fieldOn(settings, 'checkout_field_address_req', true) ? ' *' : ''}</span>
                    <textarea rows={2} value={form.address} onChange={(e) => set('address', e.target.value)} autoComplete="street-address" />
                  </label>
                ) : null}
                {fieldOn(settings, 'checkout_field_postal', false) ? (
                  <label className="sf-co-field">
                    <span>کد پستی{fieldOn(settings, 'checkout_field_postal_req', false) ? ' *' : ''}</span>
                    <input value={form.postal} onChange={(e) => set('postal', e.target.value)} dir="ltr" inputMode="numeric" />
                  </label>
                ) : null}
                {fieldOn(settings, 'checkout_field_notes', false) ? (
                  <label className="sf-co-field full">
                    <span>توضیحات سفارش{fieldOn(settings, 'checkout_field_notes_req', false) ? ' *' : ''}</span>
                    <textarea rows={2} value={form.notes} onChange={(e) => set('notes', e.target.value)} />
                  </label>
                ) : null}
              </div>
            </section>

            {showGw ? (
              <section className="sf-co-card">
                <h3>روش پرداخت</h3>
                <p className="sf-co-hint">درگاه‌های فعال ووکامرس / وردپرس</p>
                <div className="sf-gateways">
                  {gateways.map((g) => (
                    <label key={g.id} className={`sf-gw ${payment === g.id ? 'on' : ''}`}>
                      <input type="radio" name="sf_pay" value={g.id} checked={payment === g.id} onChange={() => setPayment(g.id)} />
                      <span className="sf-gw-body">
                        {g.icon ? <img src={g.icon} alt="" className="sf-gw-icon" /> : <span className="sf-gw-ph">💳</span>}
                        <span>
                          <strong>{g.title}</strong>
                          {g.description ? <small>{g.description}</small> : null}
                        </span>
                      </span>
                    </label>
                  ))}
                </div>
              </section>
            ) : null}

            {err ? <div className="sf-co-err">{err}</div> : null}
          </div>

          <aside className="sf-checkout-side">
            <section className="sf-co-card sticky">
              <h3>خلاصه سفارش</h3>
              <div className="sf-co-items">
                {items.map((it) => (
                  <div className="sf-co-item" key={it.id}>
                    {it.image ? <img src={it.image} alt="" /> : <span className="ph">📦</span>}
                    <div className="meta">
                      <strong>{it.title}</strong>
                      <span>{toFa(it.qty || 1)} × {it.price_txt || formatMoney(it.price, currency)}</span>
                    </div>
                    <div className="sf-qty mini">
                      <button type="button" onClick={() => onQty(it.id, (it.qty || 1) - 1)}>−</button>
                      <button type="button" onClick={() => onRemove(it.id)} title="حذف">🗑</button>
                    </div>
                  </div>
                ))}
              </div>
              <div className="sf-co-sum">
                <span>جمع کل</span>
                <strong>{formatMoney(total, currency)}</strong>
              </div>
              <button type="button" className="sf-btn primary block lg sf-co-pay-desk" disabled={busy || !items.length} onClick={placeOrder}>
                {busy ? 'در حال ثبت سفارش…' : 'ثبت و پرداخت'}
              </button>
              <p className="sf-co-secure">🔒 پرداخت امن · اطلاعات شما محفوظ است</p>
            </section>
          </aside>
        </div>
      )}

      {!done ? (
        <div className="sf-co-paybar" role="region" aria-label="پرداخت">
          <div className="sf-co-paybar-inner">
            <div className="sf-co-paybar-meta">
              <span className="lbl">مبلغ قابل پرداخت</span>
              <strong>{formatMoney(total, currency)}</strong>
              <small>{toFa(items.reduce((s, it) => s + (it.qty || 1), 0))} کالا</small>
            </div>
            <button type="button" className="sf-btn primary lg sf-co-paybar-btn" disabled={busy || !items.length} onClick={placeOrder}>
              {busy ? 'در حال ثبت…' : 'ثبت سفارش و پرداخت'}
            </button>
          </div>
        </div>
      ) : null}
    </div>
  );
}

function SupportChat({ settings, ajax }) {
  const enabled = !!settings.enable_support_chat;
  const [open, setOpen] = useState(false);
  const [text, setText] = useState('');
  const [busy, setBusy] = useState(false);
  const [msgs, setMsgs] = useState(() => ([
    { id: 'w1', role: 'bot', text: settings.chat_welcome_message || 'سلام! خوش آمدید 👋 سوال خود را بنویسید.' },
  ]));
  const boxRef = useRef(null);
  const pos = settings.chat_button_position === 'right' ? 'right' : 'left';

  useEffect(() => {
    if (boxRef.current) boxRef.current.scrollTop = boxRef.current.scrollHeight;
  }, [msgs, open]);

  if (!enabled) return null;

  const send = async () => {
    const msg = text.trim();
    if (!msg || busy) return;
    setText('');
    setMsgs((m) => [...m, { id: `u${Date.now()}`, role: 'user', text: msg }]);
    setBusy(true);
    try {
      const fd = new FormData();
      fd.append('action', 'scraper_submit_support_chat');
      fd.append('nonce', ajax.chatNonce || '');
      fd.append('message', msg);
      const res = await fetch(ajax.ajaxUrl, { method: 'POST', body: fd, credentials: 'same-origin' });
      const data = await res.json().catch(() => ({}));
      const reply =
        data?.data?.reply ||
        data?.data?.ai_reply ||
        data?.data?.message ||
        data?.data?.response ||
        (data?.success ? 'پیام شما دریافت شد. به‌زودی پاسخ می‌دهیم.' : 'ارسال انجام شد.');
      setMsgs((m) => [...m, { id: `b${Date.now()}`, role: 'bot', text: String(reply) }]);
    } catch {
      setMsgs((m) => [...m, { id: `b${Date.now()}`, role: 'bot', text: 'ارتباط برقرار نشد. کمی بعد دوباره تلاش کنید.' }]);
    } finally {
      setBusy(false);
    }
  };

  return (
    <>
      <button type="button" className={`sf-chat-fab ${pos}`} onClick={() => setOpen((v) => !v)}>
        <span>💬</span>
        <span className="lbl">{settings.chat_window_title || 'پشتیبانی'}</span>
      </button>
      {open ? (
        <div className={`sf-chat-win ${pos}`}>
          <div className="sf-chat-head">
            <span>{settings.chat_window_title || 'پشتیبانی آنلاین'}</span>
            <button type="button" onClick={() => setOpen(false)} style={{ color: '#fff', fontWeight: 900 }}>✕</button>
          </div>
          <div className="sf-chat-msgs" ref={boxRef}>
            {msgs.map((m) => (
              <MdBubble key={m.id} role={m.role} text={m.text} />
            ))}
            {busy ? <div className="sf-bubble bot sf-bubble-typing">در حال نوشتن…</div> : null}
          </div>
          <div className="sf-chat-input">
            <textarea
              value={text}
              placeholder="پیام خود را بنویسید..."
              rows={1}
              onChange={(e) => setText(e.target.value)}
              onKeyDown={(e) => {
                if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); send(); }
              }}
            />
            <button type="button" className="sf-chat-send" onClick={send} disabled={busy}>➤</button>
          </div>
        </div>
      ) : null}
    </>
  );
}

function StoreApp({ boot }) {
  const settings = boot.settings || {};
  const products = Array.isArray(boot.products) ? boot.products : [];
  const ajax = boot.ajax || {};
  const currency = settings.currency_symbol || 'تومان';
  const palette = settings.store_palette || 'digikala-red';
  const accent = settings.accent_color || PALETTE_ACCENTS[palette] || '#ef394e';

  const categories = useMemo(() => {
    const map = {};
    products.forEach((p) => {
      const c = p.category || 'عمومی';
      map[c] = (map[c] || 0) + 1;
    });
    return map;
  }, [products]);

  const [query, setQuery] = useState('');
  const [cat, setCat] = useState('all');
  const [sort, setSort] = useState('default');
  const [page, setPage] = useState(1);
  const [cols, setCols] = useState(() => loadLS(COLS_KEY, settings.default_column_layout || '4') || '4');
  const [cart, setCart] = useState(() => loadLS(CART_KEY, []));
  const [wish, setWish] = useState(() => loadLS(WISH_KEY, {}));
  const [cartOpen, setCartOpen] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const [megaOpen, setMegaOpen] = useState(false);
  const [quick, setQuick] = useState(null);
  const [checkoutOpen, setCheckoutOpen] = useState(false);
  const [checkoutBusy, setCheckoutBusy] = useState(false);
  const [checkoutDoneSeed, setCheckoutDoneSeed] = useState(null);
  const [toasts, setToasts] = useState([]);
  const megaRef = useRef(null);
  const searchRef = useRef(null);
  const [searchOpen, setSearchOpen] = useState(false);
  const [searchFocus, setSearchFocus] = useState(false);
  // close expanded search on outside click
  useEffect(() => {
    const onDoc = (e) => {
      try {
        if (!searchRef.current) return;
        if (searchRef.current.contains(e.target)) return;
        if (!query.trim()) {
          setSearchOpen(false);
          setSearchFocus(false);
        } else {
          setSearchOpen(false);
        }
      } catch (err) {}
    };
    document.addEventListener('mousedown', onDoc);
    document.addEventListener('touchstart', onDoc, { passive: true });
    return () => {
      document.removeEventListener('mousedown', onDoc);
      document.removeEventListener('touchstart', onDoc);
    };
  }, [query]);

  useAdminBarOffset();
  const { progress, scrolled } = useScrollProgress();
  const timer = useCountdown(8);

  useEffect(() => { saveLS(CART_KEY, cart); }, [cart]);
  useEffect(() => { saveLS(WISH_KEY, wish); }, [wish]);
  useEffect(() => { saveLS(COLS_KEY, cols); }, [cols]);

  useEffect(() => {
    document.documentElement.style.setProperty('--sf-accent', accent);
    applyStorefrontFont(settings.shop_title_font || settings.app_font || 'vazirmatn');
    try {
      const onStorage = (e) => {
        if (e && e.key === FONT_KEY) applyStorefrontFont(settings.shop_title_font);
      };
      window.addEventListener('storage', onStorage);
      return () => window.removeEventListener('storage', onStorage);
    } catch {}
    try {
      const fd = new FormData();
      fd.append('action', 'scraper_track_event');
      fd.append('event_type', 'site_visit');
      fetch(ajax.ajaxUrl || '/wp-admin/admin-ajax.php', { method: 'POST', body: fd, credentials: 'same-origin' }).catch(() => {});
    } catch {}
  }, []);

  useEffect(() => {
    const onDoc = (e) => {
      if (megaRef.current && !megaRef.current.contains(e.target)) setMegaOpen(false);
    };
    document.addEventListener('click', onDoc);
    return () => document.removeEventListener('click', onDoc);
  }, []);

  const toast = useCallback((text, type = 'ok') => {
    const id = `${Date.now()}_${Math.random()}`;
    setToasts((t) => [...t, { id, text, type }]);
    setTimeout(() => setToasts((t) => t.filter((x) => x.id !== id)), 2800);
  }, []);

  /* v13.1.8: پیام تکمیل فقط بعد از بازگشت از درگاه (نه با زدن دکمه پرداخت) */
  useEffect(() => {
    const ret = detectOrderReturn();
    let pending = null;
    try { pending = loadLS(PENDING_ORDER_KEY, null); } catch { pending = null; }
    const bootPaid = boot && boot.paid_order && typeof boot.paid_order === 'object' ? boot.paid_order : null;
    const successMsg = settings.checkout_success_msg || 'سفارش شما با موفقیت تکمیل شد. از خریدتان سپاسگزاریم!';

    if (bootPaid && (bootPaid.order_id || bootPaid.paid)) {
      const seed = {
        order_id: bootPaid.order_id,
        order_key: bootPaid.order_key || '',
        total: bootPaid.total,
        total_formatted: bootPaid.total_formatted,
        payment_method: bootPaid.payment_method || '',
        payment_title: bootPaid.payment_title || '',
        message: bootPaid.message || successMsg,
        phase: 'complete',
        paid: true,
      };
      setCheckoutDoneSeed(seed);
      setCheckoutOpen(true);
      try { localStorage.removeItem(PENDING_ORDER_KEY); } catch {}
      clearOrderReturnParams();
      toast(seed.message, 'ok');
      return;
    }

    if (ret && ret.paid) {
      const base = (pending && typeof pending === 'object') ? pending : {};
      const seed = {
        ...base,
        order_id: ret.order_id || base.order_id || '',
        order_key: ret.order_key || base.order_key || '',
        message: base.message || successMsg,
        phase: 'complete',
        paid: true,
      };
      setCheckoutDoneSeed(seed);
      setCheckoutOpen(true);
      setCart([]);
      try { localStorage.removeItem(PENDING_ORDER_KEY); } catch {}
      clearOrderReturnParams();
      toast(seed.message, 'ok');
      return;
    }

    if (pending && pending.at && (Date.now() - Number(pending.at)) > 2 * 3600 * 1000) {
      try { localStorage.removeItem(PENDING_ORDER_KEY); } catch {}
    }
  }, []);

  const filtered = useMemo(() => {
    const q = normalizeSearch(query);
    let list = products.filter((p) => {
      if (cat !== 'all' && (p.category || 'عمومی') !== cat) return false;
      if (!q) return true;
      return productMatchesQuery(p, q);
    });
    if (sort === 'price-asc') list = [...list].sort((a, b) => (a.price || 0) - (b.price || 0));
    if (sort === 'price-desc') list = [...list].sort((a, b) => (b.price || 0) - (a.price || 0));
    if (sort === 'title') list = [...list].sort((a, b) => String(a.title || '').localeCompare(String(b.title || ''), 'fa'));
    return list;
  }, [products, query, cat, sort]);

  const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
  const pageSafe = clamp(page, 1, totalPages);
  const pageItems = filtered.slice((pageSafe - 1) * PAGE_SIZE, pageSafe * PAGE_SIZE);

  useEffect(() => { setPage(1); }, [query, cat, sort]);

  const cartCount = cart.reduce((s, it) => s + (it.qty || 1), 0);

  const addToCart = (p) => {
    setCart((prev) => {
      const i = prev.findIndex((x) => x.id === p.id);
      if (i >= 0) {
        const next = [...prev];
        next[i] = { ...next[i], qty: (next[i].qty || 1) + 1 };
        return next;
      }
      return [...prev, {
        id: p.id,
        title: p.title,
        price: p.price,
        price_txt: p.price_formatted || formatMoney(p.price, currency),
        image: p.image || '',
        qty: 1,
      }];
    });
    toast(`«${p.title}» به سبد اضافه شد`);
    try {
      const fd = new FormData();
      fd.append('action', 'scraper_track_event');
      fd.append('event_type', 'add_to_cart');
      fd.append('product_id', p.id || '');
      fd.append('product_title', p.title || '');
      fd.append('price', p.price || 0);
      fetch(ajax.ajaxUrl || '/wp-admin/admin-ajax.php', { method: 'POST', body: fd, credentials: 'same-origin' }).catch(() => {});
    } catch {}
  };

  const setQty = (id, qty) => {
    setCart((prev) => prev
      .map((it) => (it.id === id ? { ...it, qty } : it))
      .filter((it) => (it.qty || 0) > 0));
  };

  const toggleWish = (id) => {
    setWish((w) => {
      const next = { ...w };
      if (next[id]) delete next[id];
      else next[id] = true;
      return next;
    });
  };

  const openQuick = (p) => {
    setQuick(p);
    try {
      const fd = new FormData();
      fd.append('action', 'scraper_track_event');
      fd.append('event_type', 'product_view');
      fd.append('product_id', p.id || '');
      fd.append('product_title', p.title || '');
      fd.append('price', p.price || 0);
      fetch(ajax.ajaxUrl || '/wp-admin/admin-ajax.php', { method: 'POST', body: fd, credentials: 'same-origin' }).catch(() => {});
    } catch {}
  };

  const checkout = async () => {
    if (!cart.length) return;
    try {
      const tfd = new FormData();
      tfd.append('action', 'scraper_track_event');
      tfd.append('event_type', 'checkout_step');
      tfd.append('count', cartCount);
      tfd.append('total', cart.reduce((s, it) => s + (Number(it.price) || 0) * (it.qty || 1), 0));
      fetch(ajax.ajaxUrl || '/wp-admin/admin-ajax.php', { method: 'POST', body: tfd, credentials: 'same-origin' }).catch(() => {});
    } catch {}

    // Custom React checkout page (admin toggle)
    if (settings.enable_custom_checkout !== false && settings.enable_custom_checkout !== 0 && settings.enable_custom_checkout !== '0') {
      setCartOpen(false);
      setCheckoutOpen(true);
      return;
    }

    // Fallback: sync to WooCommerce checkout URL
    setCheckoutBusy(true);
    try {
      const fd = new FormData();
      fd.append('action', 'scraper_wc_sync_and_checkout');
      fd.append('nonce', ajax.cartNonce || '');
      fd.append('items', JSON.stringify(cart.map((it) => ({
        id: it.id,
        title: it.title,
        price: it.price,
        qty: it.qty || 1,
        image: it.image || '',
      }))));
      const res = await fetch(ajax.ajaxUrl || '/wp-admin/admin-ajax.php', { method: 'POST', body: fd, credentials: 'same-origin' });
      const data = await res.json().catch(() => ({}));
      const url = data?.data?.checkout_url || data?.data?.url || ajax.checkoutUrl;
      if (url) window.location.href = url;
      else toast('آدرس تسویه در دسترس نیست', 'err');
    } catch {
      if (ajax.checkoutUrl) window.location.href = ajax.checkoutUrl;
      else toast('خطا در اتصال به تسویه', 'err');
    } finally {
      setCheckoutBusy(false);
    }
  };

  const stickyOn = settings.sticky_header !== false && settings.sticky_header !== 0 && settings.sticky_header !== '0';

  const amazing = useMemo(
    () => products.filter((x) => x.has_discount).slice(0, 12),
    [products],
  );

  const liveSuggestions = useMemo(() => {
    const q = normalizeSearch(query);
    if (!q || q.length < 1) return [];
    return products.filter((p) => productMatchesQuery(p, q)).slice(0, 8);
  }, [products, query]);

  useEffect(() => {
    const onDoc = (e) => {
      if (searchRef.current && !searchRef.current.contains(e.target)) {
        setSearchOpen(false);
        setSearchFocus(false);
      }
    };
    document.addEventListener('mousedown', onDoc);
    return () => document.removeEventListener('mousedown', onDoc);
  }, []);

  return (
    <div
      className={`sf-app${checkoutOpen ? ' is-checkout' : ''}${scrolled ? ' is-scrolled' : ''}`}
      data-palette={palette}
      data-template={settings.store_template || 'digikala'}
      style={{ ['--sf-accent']: accent }}
    >
      <ToastHost toasts={toasts} dismiss={(id) => setToasts((t) => t.filter((x) => x.id !== id))} />

      {settings.show_top_bar !== false ? (
        <div className="sf-topbar">
          <div className="sf-container sf-topbar-inner">
            <div style={{ display: 'flex', gap: 10, alignItems: 'center', flexWrap: 'wrap' }}>
              <span className="sf-topbar-live"><span className="dot" />ارسال سریع</span>
              <span>{settings.top_bar_notice || 'ارسال سریع و تضمین اصالت کالا'}</span>
            </div>
            <div className="sf-topbar-links">
              {settings.support_hours ? <span>🕒 {settings.support_hours}</span> : null}
              {settings.contact_phone ? <a href={`tel:${settings.contact_phone}`}>📞 {settings.contact_phone}</a> : null}
            </div>
          </div>
        </div>
      ) : null}

      <div className={`sf-header-wrap ${scrolled ? 'is-compact' : ''}`} style={stickyOn ? undefined : { position: 'relative', top: 'auto' }}>
        <div className="sf-container">
          <header className="sf-header" ref={megaRef}>
            <div className="sf-header-main">
              <a className="sf-brand" href="#" onClick={(e) => { e.preventDefault(); window.scrollTo({ top: 0, behavior: 'smooth' }); }}>
                <div className="sf-brand-logo" aria-hidden>د</div>
                <div className="sf-brand-info">
                  <h1>{settings.shop_title || 'فروشگاه آنلاین'}</h1>
                  <p>{settings.shop_subtitle || ''}</p>
                  <div className="sf-chips">
                    <span className="sf-chip live">● آنلاین</span>
                  </div>
                </div>
              </a>

              <div className={`sf-search ${searchFocus || searchOpen ? 'is-open' : 'is-collapsed'}`} ref={searchRef}>
                <button
                  type="button"
                  className="sf-search-toggle"
                  aria-label={searchOpen || searchFocus ? 'بستن جستجو' : 'باز کردن جستجو'}
                  aria-expanded={searchOpen || searchFocus}
                  title="جستجو"
                  onClick={() => {
                    if (searchOpen || searchFocus) {
                      setSearchOpen(false);
                      setSearchFocus(false);
                    } else {
                      setSearchOpen(true);
                      setSearchFocus(true);
                      requestAnimationFrame(() => {
                        try { searchRef.current?.querySelector('input')?.focus(); } catch (e) {}
                      });
                    }
                  }}
                >
                  <span className="ico" aria-hidden>🔍</span>
                </button>
                <div className="sf-search-panel">
                <input
                  value={query}
                  onChange={(e) => { setQuery(e.target.value); setSearchOpen(true); setPage(1); }}
                  onFocus={() => { setSearchFocus(true); setSearchOpen(true); }}
                  onBlur={() => {
                    // allow click on dropdown items
                    setTimeout(() => {
                      try {
                        if (searchRef.current && !searchRef.current.contains(document.activeElement)) {
                          if (!query.trim()) { setSearchFocus(false); setSearchOpen(false); }
                        }
                      } catch (e) {}
                    }, 120);
                  }}
                  onKeyDown={(e) => {
                    if (e.key === 'Enter') {
                      e.preventDefault();
                      setSearchOpen(false);
                      setSearchFocus(false);
                      document.getElementById('sfProducts')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                    if (e.key === 'Escape') {
                      setSearchOpen(false);
                      setSearchFocus(false);
                      setQuery('');
                    }
                  }}
                  placeholder="جستجو در کالاها، برندها و دسته‌ها..."
                  aria-label="جستجوی محصولات"
                  aria-autocomplete="list"
                  aria-expanded={searchOpen && !!query}
                  autoComplete="off"
                  tabIndex={(searchOpen || searchFocus) ? 0 : -1}
                />
                {query ? (
                  <button type="button" className="clear" onClick={() => { setQuery(''); setSearchOpen(true); setSearchFocus(true); }} aria-label="پاک کردن">✕</button>
                ) : null}
                {searchOpen && query.trim() ? (
                  <div className="sf-search-drop" role="listbox">
                    {liveSuggestions.length ? (
                      <>
                        <div className="sf-search-drop-head">
                          <span>{toFa(liveSuggestions.length)} نتیجه</span>
                          <button type="button" onClick={() => {
                            setSearchOpen(false);
                            document.getElementById('sfProducts')?.scrollIntoView({ behavior: 'smooth' });
                          }}>مشاهده همه</button>
                        </div>
                        {liveSuggestions.map((p) => (
                          <button
                            type="button"
                            key={`sg-${p.id}`}
                            className="sf-search-item"
                            role="option"
                            onClick={() => {
                              setSearchOpen(false);
                              openQuick(p);
                            }}
                          >
                            <span className="sf-search-thumb">
                              {p.image
                                ? <img src={p.image} alt="" loading="lazy" />
                                : <span className="ph">📦</span>}
                            </span>
                            <span className="sf-search-meta">
                              <span className="t">{p.title}</span>
                              <span className="c">{p.category || 'عمومی'}</span>
                            </span>
                            <span className="sf-search-price">
                              {p.has_discount && p.discount_pct ? (
                                <span className="disc">{toFa(p.discount_pct)}٪</span>
                              ) : null}
                              <span className="pr">{p.price_formatted || formatMoney(p.price, currency)}</span>
                            </span>
                          </button>
                        ))}
                        <button
                          type="button"
                          className="sf-search-more"
                          onClick={() => {
                            setSearchOpen(false);
                            document.getElementById('sfProducts')?.scrollIntoView({ behavior: 'smooth' });
                          }}
                        >
                          نمایش {toFa(filtered.length)} کالا در فروشگاه ←
                        </button>
                      </>
                    ) : (
                      <div className="sf-search-empty">
                        <span>نتیجه‌ای برای «{query}» پیدا نشد</span>
                        <button type="button" onClick={() => setQuery('')}>پاک کردن جستجو</button>
                      </div>
                    )}
                  </div>
                ) : null}
                </div>
              </div>

              <div className="sf-actions">
                <button type="button" className={`sf-burger-btn ${menuOpen ? 'is-open' : ''}`} onClick={() => setMenuOpen(true)} aria-label="باز کردن منو" title="منو">
                  <span className="sf-burger-ico" aria-hidden><i /><i /><i /></span>
                  <span className="sf-burger-lbl">منو</span>
                </button>
                <a className="sf-action-btn sf-action-account" href={boot.urls?.account || '#'} aria-label="حساب کاربری">
                  <span className="sf-action-ico" aria-hidden>👤</span>
                  <span className="lbl">حساب</span>
                </a>
                <button type="button" className="sf-action-btn cart" onClick={() => setCartOpen(true)} aria-label="سبد خرید">
                  <span className="sf-action-ico" aria-hidden>🛒</span>
                  <span className="lbl">سبد</span>
                  {cartCount > 0 ? <span className="sf-badge">{toFa(cartCount)}</span> : null}
                </button>
                {boot.urls?.admin ? (
                  <a className="sf-action-btn sf-action-admin" href={boot.urls.admin} aria-label="مدیریت"><span className="sf-action-ico">⚙️</span></a>
                ) : null}
              </div>
            </div>

            <nav className="sf-nav" aria-label="منوی اصلی">
              <div className="sf-nav-links">
                <button
                  type="button"
                  className="sf-nav-btn"
                  onClick={(e) => { e.stopPropagation(); setMegaOpen((v) => !v); }}
                >
                  ☰ دسته‌بندی کالاها ▾
                </button>
                <button type="button" className={`sf-nav-link ${cat === 'all' && !query ? 'active' : ''}`} onClick={() => { setCat('all'); setQuery(''); window.scrollTo({ top: 0, behavior: 'smooth' }); }}>🏠 صفحه اصلی</button>
                <button type="button" className="sf-nav-link" onClick={() => document.getElementById('sfProducts')?.scrollIntoView({ behavior: 'smooth' })}>
                  🛍️ همه محصولات ({toFa(products.length)})
                </button>
                <button type="button" className="sf-nav-link" onClick={() => { setSort('price-asc'); document.getElementById('sfProducts')?.scrollIntoView({ behavior: 'smooth' }); }}>
                  🔥 پیشنهادهای اقتصادی
                </button>
                {settings.contact_phone ? (
                  <a className="sf-nav-link" href={`tel:${settings.contact_phone}`}>📞 تماس با ما</a>
                ) : null}
              </div>
              <div className="sf-nav-status" aria-hidden="true" />

              {megaOpen ? (
                <div className="sf-mega">
                  <div style={{ padding: '6px 10px', fontWeight: 800, fontSize: '.82rem', color: '#64748b' }}>دسته‌بندی‌های کالا</div>
                  <button type="button" className={`sf-mega-item ${cat === 'all' ? 'active' : ''}`} onClick={() => { setCat('all'); setMegaOpen(false); }}>
                    <span>همه دسته‌ها</span>
                    <span className="sf-mega-count">{toFa(products.length)}</span>
                  </button>
                  {Object.entries(categories).map(([name, count]) => (
                    <button type="button" key={name} className={`sf-mega-item ${cat === name ? 'active' : ''}`} onClick={() => { setCat(name); setMegaOpen(false); document.getElementById('sfProducts')?.scrollIntoView({ behavior: 'smooth' }); }}>
                      <span>📂 {name}</span>
                      <span className="sf-mega-count">{toFa(count)}</span>
                    </button>
                  ))}
                </div>
              ) : null}
            </nav>
            <div className="sf-progress" aria-hidden><span style={{ width: `${progress}%` }} /></div>
          </header>
        </div>
      </div>

      <main className="sf-container">
        <section className="sf-hero" aria-label="بنر فروشگاه">
          <div className="sf-hero-content">
            <h2>{settings.shop_title || 'فروشگاه آنلاین'}</h2>
            <p>{settings.shop_subtitle || 'خرید مطمئن با ارسال سریع، ضمانت اصالت و بهترین قیمت'}</p>
            {settings.show_features_banner !== false ? (
              <div className="sf-hero-features">
                <div className="sf-hero-feature">🚚 ارسال سریع</div>
                <div className="sf-hero-feature">✅ ضمانت اصالت</div>
                <div className="sf-hero-feature">↩️ ۷ روز بازگشت</div>
                <div className="sf-hero-feature">💬 پشتیبانی</div>
              </div>
            ) : null}
          </div>
          <div className="sf-hero-side" aria-hidden>
            <div className="sf-hero-badge">
              <div className="big">{toFa(products.length || 0)}+</div>
              <div className="sub">کالای آماده ارسال</div>
            </div>
          </div>
        </section>

        {amazing.length ? (
          <section className="sf-amazing" aria-label="پیشنهاد شگفت‌انگیز">
            <div className="sf-amazing-head">
              <div className="title">
                <span>🔥</span>
                <span>پیشنهاد شگفت‌انگیز</span>
              </div>
              <div className="sf-timer" style={{ background: 'transparent' }}>
                <span>{toFa(timer.h)}</span>
                <span style={{ color: '#fff', background: 'transparent', minWidth: 0, padding: 0 }}>:</span>
                <span>{toFa(timer.m)}</span>
                <span style={{ color: '#fff', background: 'transparent', minWidth: 0, padding: 0 }}>:</span>
                <span>{toFa(timer.s)}</span>
              </div>
            </div>
            <div className="sf-amazing-scroller">
              {amazing.map((p) => (
                <button
                  type="button"
                  key={`amz-${p.id}`}
                  className="sf-amazing-card"
                  onClick={() => openQuick(p)}
                >
                  {p.image ? <img src={p.image} alt="" loading="lazy" /> : <div style={{ height: 118, display: 'grid', placeItems: 'center', fontSize: '2rem', background: '#fafafa' }}>📦</div>}
                  <div className="body">
                    <div className="t">{p.title}</div>
                    {p.has_discount && p.discount_pct ? (
                      <div style={{ color: '#ef394e', fontWeight: 900, fontSize: '.72rem', marginTop: 4 }}>{toFa(p.discount_pct)}٪</div>
                    ) : null}
                    {p.old_price || p.old_price_formatted ? (
                      <div className="old">{p.old_price_formatted || formatMoney(p.old_price, currency)}</div>
                    ) : null}
                    <div className="pr">{p.price_formatted || formatMoney(p.price, currency)}</div>
                  </div>
                </button>
              ))}
            </div>
          </section>
        ) : (
          <div className="sf-flash">
            <div>⚡ پیشنهادهای ویژه امروز</div>
            <div className="sf-timer">
              <span>{toFa(timer.h)}</span>:
              <span>{toFa(timer.m)}</span>:
              <span>{toFa(timer.s)}</span>
            </div>
          </div>
        )}

        {settings.show_features_banner !== false ? (
          <div className="sf-trust">
            <div className="sf-trust-item"><span className="ic">🚚</span><span>ارسال سریع سراسر کشور</span></div>
            <div className="sf-trust-item"><span className="ic">🛡️</span><span>ضمانت اصالت کالا</span></div>
            <div className="sf-trust-item"><span className="ic">↩️</span><span>۷ روز ضمانت بازگشت</span></div>
            <div className="sf-trust-item"><span className="ic">💳</span><span>پرداخت امن آنلاین</span></div>
          </div>
        ) : null}

        {settings.show_animated_stats !== false ? (
          <div className="sf-kpis">
            <div className="sf-kpi"><div className="n">{toFa(products.length || 0)}</div><div className="l">کالای متنوع</div></div>
            <div className="sf-kpi"><div className="n">{toFa(Object.keys(categories).length || 0)}</div><div className="l">دسته‌بندی</div></div>
            <div className="sf-kpi"><div className="n">{toFa(amazing.length || 0)}</div><div className="l">پیشنهاد ویژه</div></div>
            <div className="sf-kpi"><div className="n">۲۴/۷</div><div className="l">پشتیبانی</div></div>
          </div>
        ) : null}

        <div className="sf-toolbar" id="sfProducts">
          <h3>
            {cat === 'all' ? 'همه محصولات' : cat}
            <span style={{ color: '#94a3b8', fontWeight: 800, fontSize: '.9rem' }}> ({toFa(filtered.length)})</span>
          </h3>
          <div className="sf-toolbar-controls">
            <select className="sf-select" value={sort} onChange={(e) => setSort(e.target.value)}>
              <option value="default">مرتب‌سازی پیش‌فرض</option>
              <option value="price-asc">ارزان‌ترین</option>
              <option value="price-desc">گران‌ترین</option>
              <option value="title">بر اساس نام</option>
            </select>
            {['1', '2', '3', '4'].map((c) => (
              <button key={c} type="button" className={`sf-col-btn ${cols === c ? 'active' : ''}`} onClick={() => setCols(c)} title={`${c} ستونه`}>
                {toFa(c)}
              </button>
            ))}
          </div>
        </div>

        <div className="sf-cat-pills">
          <button type="button" className={`sf-pill ${cat === 'all' ? 'active' : ''}`} onClick={() => setCat('all')}>همه</button>
          {Object.keys(categories).map((name) => (
            <button key={name} type="button" className={`sf-pill ${cat === name ? 'active' : ''}`} onClick={() => setCat(name)}>{name}</button>
          ))}
        </div>

        <div className={`sf-grid cols-${cols}`}>
          {pageItems.length ? pageItems.map((p, idx) => (
            <ProductCard
              key={p.id || idx}
              p={p}
              cols={cols}
              currency={currency}
              wish={!!wish[p.id]}
              onWish={toggleWish}
              onQuick={openQuick}
              onAdd={addToCart}
              showSpecial={!!settings.show_special_badge}
              template={settings.store_template}
            />
          )) : (
            <div className="sf-empty">
              <div style={{ fontSize: '3rem' }}>🔍</div>
              <h4>کالایی یافت نشد</h4>
              <p>عبارت جستجو یا فیلتر دسته‌بندی را تغییر دهید.</p>
              <button type="button" className="sf-btn ghost" onClick={() => { setQuery(''); setCat('all'); }}>نمایش همه محصولات</button>
            </div>
          )}
        </div>

        {totalPages > 1 ? (
          <div className="sf-pagination">
            <button type="button" className="sf-page-btn" disabled={pageSafe <= 1} onClick={() => setPage(pageSafe - 1)}>« قبلی</button>
            {Array.from({ length: totalPages }, (_, i) => i + 1)
              .filter((p) => p === 1 || p === totalPages || Math.abs(p - pageSafe) <= 2)
              .reduce((acc, p, idx, arr) => {
                if (idx > 0 && p - arr[idx - 1] > 1) acc.push('…');
                acc.push(p);
                return acc;
              }, [])
              .map((p, i) => (
                typeof p === 'number' ? (
                  <button key={p} type="button" className={`sf-page-btn ${p === pageSafe ? 'active' : ''}`} onClick={() => { setPage(p); document.getElementById('sfProducts')?.scrollIntoView({ behavior: 'smooth' }); }}>
                    {toFa(p)}
                  </button>
                ) : <span key={`e${i}`} style={{ padding: '0 6px', color: '#94a3b8', fontWeight: 800 }}>…</span>
              ))}
            <button type="button" className="sf-page-btn" disabled={pageSafe >= totalPages} onClick={() => setPage(pageSafe + 1)}>بعدی »</button>
          </div>
        ) : null}
      </main>

      <footer className="sf-footer">
        <div className="sf-container sf-footer-grid">
          <div>
            <h4>{settings.shop_title || 'فروشگاه'}</h4>
            <p>{settings.shop_subtitle || 'خرید آنلاین مطمئن با ارسال سریع'}</p>
          </div>
          <div>
            <h4>دسترسی سریع</h4>
            <ul>
              <li><a href="#" onClick={(e) => { e.preventDefault(); setCat('all'); }}>همه محصولات</a></li>
              <li><a href={boot.urls?.account || '#'}>حساب کاربری</a></li>
              <li><a href="#" onClick={(e) => { e.preventDefault(); setCartOpen(true); }}>سبد خرید</a></li>
            </ul>
          </div>
          <div>
            <h4>پشتیبانی</h4>
            <ul>
              {settings.contact_phone ? <li><a href={`tel:${settings.contact_phone}`}>{settings.contact_phone}</a></li> : null}
              {settings.support_hours ? <li>{settings.support_hours}</li> : null}
              <li>ارسال سریع سراسر کشور</li>
            </ul>
          </div>
        </div>
        <div className="sf-copy">© {toFa(new Date().getFullYear())} {settings.shop_title || 'فروشگاه'} · تمامی حقوق محفوظ است</div>
      </footer>

      <nav className="sf-mob-bar" aria-label="منوی موبایل">
        <button type="button" className="sf-mob-item active" onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}>
          <span>🏠</span><span>خانه</span>
        </button>
        <button type="button" className="sf-mob-item sf-mob-menu" onClick={() => setMenuOpen(true)}>
          <span className="sf-burger-ico sm" aria-hidden><i /><i /><i /></span><span>منو</span>
        </button>
        <button type="button" className="sf-mob-item" onClick={() => setCartOpen(true)}>
          <span>🛒</span><span>سبد</span>
          {cartCount > 0 ? <span className="sf-badge">{toFa(cartCount)}</span> : null}
        </button>
        <a className="sf-mob-item" href={boot.urls?.account || '#'}>
          <span>👤</span><span>حساب</span>
        </a>
      </nav>

      <CartDrawer
        open={cartOpen}
        onClose={() => setCartOpen(false)}
        items={cart}
        currency={currency}
        onQty={setQty}
        onRemove={(id) => setCart((c) => c.filter((x) => x.id !== id))}
        onCheckout={checkout}
        busy={checkoutBusy}
        freeShip={settings.free_shipping_threshold}
      />
      <CheckoutPage
        open={checkoutOpen}
        onClose={() => { setCheckoutOpen(false); setCheckoutDoneSeed(null); }}
        items={cart}
        currency={currency}
        settings={settings}
        ajax={ajax}
        gateways={boot.gateways || []}
        onQty={setQty}
        onRemove={(id) => setCart((c) => c.filter((x) => x.id !== id))}
        onClearCart={() => setCart([])}
        toast={toast}
        initialDone={checkoutDoneSeed}
      />
      <MenuDrawer
        open={menuOpen}
        onClose={() => setMenuOpen(false)}
        settings={settings}
        categories={categories}
        onCat={setCat}
        cartCount={cartCount}
        onOpenCart={() => setCartOpen(true)}
        accountUrl={boot.urls?.account || '#'}
        adminUrl={boot.urls?.admin || ''}
      />
      <QuickView product={quick} currency={currency} onClose={() => setQuick(null)} onAdd={addToCart} />
      <SupportChat settings={settings} ajax={ajax} />
    </div>
  );
}

function mount() {
  try {
    const el = document.getElementById('amphp-storefront-root');
    if (!el) {
      console.error('[AMPHP] #amphp-storefront-root not found');
      return;
    }
    if (el.getAttribute('data-mounted') === '1') return;
    const boot = (typeof window !== 'undefined' && window.AMPHP_STOREFRONT) ? window.AMPHP_STOREFRONT : {};
    const root = createRoot(el);
    root.render(<StoreApp boot={boot} />);
    el.setAttribute('data-mounted', '1');
    try { el.querySelector('.amphp-sf-bootwait')?.remove(); } catch (_) {}
  } catch (err) {
    console.error('[AMPHP] mount failed', err);
    const el = document.getElementById('amphp-storefront-root');
    if (el) {
      el.innerHTML = '<div style="padding:24px;text-align:center;font-family:Tahoma,sans-serif;color:#b91c1c;font-weight:800;line-height:1.7">خطا در اجرای فروشگاه<br><span style="font-size:.8rem;font-weight:600;color:#64748b">' + String(err && err.message || err) + '</span></div>';
    }
  }
}

function scheduleMount() {
  const run = () => mount();
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', run, { once: true });
  } else {
    run();
  }
  // Extra safety for late boot injection
  setTimeout(run, 50);
  setTimeout(run, 300);
}

scheduleMount();

export default StoreApp;
