import React, { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { createRoot } from 'react-dom/client';
import './storefront.css';

const PAGE_SIZE = 20;
const CART_KEY = 'amphp_sf_cart_v1';
const WISH_KEY = 'amphp_sf_wish_v1';
const COLS_KEY = 'scraped_shop_cols';

const faDigits = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
const toFa = (v) => String(v ?? '').replace(/\d/g, (d) => faDigits[d]);
const formatMoney = (n, currency = 'تومان') => {
  const num = Number(n) || 0;
  return `${toFa(num.toLocaleString('en-US'))} ${currency}`;
};
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
        setScrolled(y > 16);
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

function CartDrawer({ open, onClose, items, currency, onQty, onRemove, onCheckout, busy }) {
  if (!open) return null;
  const total = items.reduce((s, it) => s + (Number(it.price) || 0) * (it.qty || 1), 0);
  return (
    <>
      <div className="sf-overlay" onClick={onClose} />
      <aside className="sf-drawer right" role="dialog" aria-label="سبد خرید">
        <div className="sf-drawer-head">
          <h3>🛒 سبد خرید ({toFa(items.length)})</h3>
          <button type="button" className="sf-close" onClick={onClose}>✕</button>
        </div>
        <div className="sf-drawer-body">
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
            {busy ? 'در حال انتقال…' : 'ادامه و تسویه‌حساب'}
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
        <div className="sf-drawer-head">
          <h3>☰ منوی فروشگاه</h3>
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
              <div key={m.id} className={`sf-bubble ${m.role === 'user' ? 'user' : 'bot'}`}>{m.text}</div>
            ))}
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
  const accent = settings.accent_color || '#2563eb';

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
  const [cols, setCols] = useState(() => loadLS(COLS_KEY, settings.default_column_layout || '1') || '1');
  const [cart, setCart] = useState(() => loadLS(CART_KEY, []));
  const [wish, setWish] = useState(() => loadLS(WISH_KEY, {}));
  const [cartOpen, setCartOpen] = useState(false);
  const [menuOpen, setMenuOpen] = useState(false);
  const [megaOpen, setMegaOpen] = useState(false);
  const [quick, setQuick] = useState(null);
  const [checkoutBusy, setCheckoutBusy] = useState(false);
  const [toasts, setToasts] = useState([]);
  const megaRef = useRef(null);

  useAdminBarOffset();
  const { progress, scrolled } = useScrollProgress();
  const timer = useCountdown(8);

  useEffect(() => { saveLS(CART_KEY, cart); }, [cart]);
  useEffect(() => { saveLS(WISH_KEY, wish); }, [wish]);
  useEffect(() => { saveLS(COLS_KEY, cols); }, [cols]);

  useEffect(() => {
    document.documentElement.style.setProperty('--sf-accent', accent);
    // track visit once
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

  const filtered = useMemo(() => {
    const q = query.trim().toLowerCase();
    let list = products.filter((p) => {
      if (cat !== 'all' && (p.category || 'عمومی') !== cat) return false;
      if (!q) return true;
      const hay = `${p.title || ''} ${p.category || ''} ${p.description || ''}`.toLowerCase();
      return hay.includes(q);
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
      try {
        const tfd = new FormData();
        tfd.append('action', 'scraper_track_event');
        tfd.append('event_type', 'checkout_step');
        tfd.append('count', cartCount);
        tfd.append('total', cart.reduce((s, it) => s + (Number(it.price) || 0) * (it.qty || 1), 0));
        fetch(ajax.ajaxUrl || '/wp-admin/admin-ajax.php', { method: 'POST', body: tfd, credentials: 'same-origin' }).catch(() => {});
      } catch {}
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

  return (
    <div className="sf-app" style={{ ['--sf-accent']: accent }}>
      <ToastHost toasts={toasts} dismiss={(id) => setToasts((t) => t.filter((x) => x.id !== id))} />

      {settings.show_top_bar !== false ? (
        <div className="sf-topbar">
          <div className="sf-container sf-topbar-inner">
            <div style={{ display: 'flex', gap: 10, alignItems: 'center', flexWrap: 'wrap' }}>
              <span className="sf-topbar-live"><span className="dot" />فروشگاه آنلاین</span>
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
                <div className="sf-brand-logo" aria-hidden>🛍️</div>
                <div className="sf-brand-info">
                  <h1>{settings.shop_title || 'فروشگاه آنلاین'}</h1>
                  <p>{settings.shop_subtitle || ''}</p>
                  <div className="sf-chips">
                    <span className="sf-chip live">● آنلاین</span>
                    <span className="sf-chip">⚡ ارسال سریع</span>
                    {settings.support_hours ? <span className="sf-chip">🕒 {settings.support_hours}</span> : null}
                  </div>
                </div>
              </a>

              <div className="sf-search">
                <span className="ico">🔍</span>
                <input
                  value={query}
                  onChange={(e) => setQuery(e.target.value)}
                  placeholder="جستجو در بین هزاران کالای متنوع و باکیفیت..."
                  aria-label="جستجوی محصولات"
                />
                {query ? (
                  <button type="button" className="clear" onClick={() => setQuery('')}>✕</button>
                ) : null}
              </div>

              <div className="sf-actions">
                <button type="button" className="sf-icon-btn" onClick={() => setMenuOpen(true)} aria-label="منو">☰</button>
                {boot.urls?.admin ? (
                  <a className="sf-action-btn" href={boot.urls.admin}><span>⚙️</span><span className="lbl">مدیریت</span></a>
                ) : null}
                <a className="sf-action-btn" href={boot.urls?.account || '#'}><span>👤</span><span className="lbl">حساب</span></a>
                <button type="button" className="sf-action-btn cart" onClick={() => setCartOpen(true)}>
                  <span>🛒</span><span className="lbl">سبد</span>
                  {cartCount > 0 ? <span className="sf-badge">{toFa(cartCount)}</span> : null}
                </button>
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
              <div className="sf-nav-status"><span className="sf-topbar-live" style={{ padding: '2px 8px' }}><span className="dot" /></span> فروشگاه آنلاین • ارسال فوری</div>

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
        <div className="sf-flash">
          <div>⚡ پیشنهادات شگفت‌انگیز امروز <span style={{ opacity: .9, fontWeight: 700, fontSize: '.85rem' }}>(فرصت ویژه)</span></div>
          <div className="sf-timer">
            <span>{toFa(timer.h)}</span>:
            <span>{toFa(timer.m)}</span>:
            <span>{toFa(timer.s)}</span>
          </div>
        </div>

        <section className="sf-hero">
          <h2>{settings.shop_title || 'فروشگاه آنلاین'}</h2>
          <p>{settings.shop_subtitle || 'تنوع بی‌نظیر کالاها با تضمین اصالت و ارسال سریع'}</p>
          {settings.show_features_banner !== false ? (
            <div className="sf-hero-features">
              <div className="sf-hero-feature">🚀 ارسال سریع سراسر کشور</div>
              <div className="sf-hero-feature">💎 تضمین ۱۰۰٪ اصالت کالا</div>
              <div className="sf-hero-feature">🔄 ضمانت ۷ روزه بازگشت</div>
              <div className="sf-hero-feature">🛡️ پشتیبانی تخصصی</div>
            </div>
          ) : null}
        </section>

        {settings.show_animated_stats !== false ? (
          <div className="sf-kpis">
            <div className="sf-kpi"><div className="n">{toFa(products.length || 0)}+</div><div className="l">کالای متنوع</div></div>
            <div className="sf-kpi"><div className="n">{toFa(Object.keys(categories).length || 0)}</div><div className="l">دسته‌بندی</div></div>
            <div className="sf-kpi"><div className="n">{toFa(98)}٪</div><div className="l">رضایت مشتریان</div></div>
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
            <p>{settings.shop_subtitle || 'تجربه خرید مدرن با React'}</p>
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
        <div className="sf-copy">© {toFa(new Date().getFullYear())} {settings.shop_title || 'فروشگاه'} · طراحی‌شده با React</div>
      </footer>

      <nav className="sf-mob-bar" aria-label="منوی موبایل">
        <button type="button" className="sf-mob-item active" onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}>
          <span>🏠</span><span>خانه</span>
        </button>
        <button type="button" className="sf-mob-item" onClick={() => setMenuOpen(true)}>
          <span>☰</span><span>منو</span>
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
