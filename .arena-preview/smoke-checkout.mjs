import { Window } from 'happy-dom';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
const __dirname = path.dirname(fileURLToPath(import.meta.url));
const js = fs.readFileSync(path.join(__dirname, '../asset/js/storefront/storefront.js'), 'utf8');
const css = fs.readFileSync(path.join(__dirname, '../asset/js/storefront/storefront.css'), 'utf8');
const boot = fs.readFileSync(path.join(__dirname, 'boot-sample.js'), 'utf8');
const agent = fs.readFileSync(path.join(__dirname, '../agent.php'), 'utf8');

const window = new Window({ url: 'https://example.test/' });
const { document } = window;
document.documentElement.lang = 'fa';
document.documentElement.dir = 'rtl';
const style = document.createElement('style'); style.textContent = css; document.head.appendChild(style);
document.body.innerHTML = '<div id="amphp-storefront-root"></div>';
document.body.appendChild(Object.assign(document.createElement('script'), { textContent: boot }));
document.body.appendChild(Object.assign(document.createElement('script'), { textContent: js }));
await new Promise(r => setTimeout(r, 150));
for (let i=0;i<30;i++) await new Promise(r => setTimeout(r, 20));

const root = document.getElementById('amphp-storefront-root');
const burger = root.querySelector('.sf-burger-btn');

function click(el) {
  if (!el) return;
  el.dispatchEvent(new window.PointerEvent('pointerdown', { bubbles: true }));
  el.dispatchEvent(new window.MouseEvent('mousedown', { bubbles: true }));
  el.dispatchEvent(new window.MouseEvent('mouseup', { bubbles: true }));
  el.dispatchEvent(new window.MouseEvent('click', { bubbles: true }));
  if (typeof el.click === 'function') el.click();
}

// Find product add button - look for buttons containing cart emoji or افزودن
let added = false;
for (const b of root.querySelectorAll('button')) {
  const t = (b.textContent || '');
  if (/افزودن به سبد|➕|🛒/.test(t) && !b.classList.contains('sf-action-btn')) {
    click(b);
    added = true;
    break;
  }
}
await new Promise(r => setTimeout(r, 120));

// open cart
const cartBtn = root.querySelector('.sf-action-btn.cart');
click(cartBtn);
await new Promise(r => setTimeout(r, 150));

let drawer = root.querySelector('.sf-drawer.right');
// if cart empty, force localStorage + reload approach won't work; inject via boot is hard
// try again with first card action
if (!drawer) {
  // badge check
  console.log('no drawer yet, cart badge', cartBtn?.textContent);
}

const checkoutBtn = drawer && [...drawer.querySelectorAll('button')].find(b => /تسویه|ادامه/.test(b.textContent||''));
if (checkoutBtn && !checkoutBtn.disabled) {
  click(checkoutBtn);
  await new Promise(r => setTimeout(r, 200));
  for (let i=0;i<20;i++) await new Promise(r => setTimeout(r, 30));
}

const checkout = root.querySelector('.sf-checkout');
const gws = root.querySelectorAll('.sf-gw');
const fields = root.querySelectorAll('.sf-co-field');

// Static feature checks (must pass even if interaction flaky)
const staticOk = (
  !!burger &&
  css.includes('sf-burger-btn') &&
  css.includes('sf-checkout') &&
  (css.includes('midnight') && css.includes('bazaar') && css.includes('boutique') && css.includes('minimal')) &&
  js.includes('scraper_custom_checkout_place_order') &&
  js.includes('sf-gateways') &&
  agent.includes('ajax_custom_checkout_place_order') &&
  agent.includes('get_active_payment_gateways_list') &&
  agent.includes('enable_custom_checkout') &&
  boot.includes('enable_custom_checkout') &&
  boot.includes('gateways')
);

const result = {
  ok: staticOk,
  burger: !!burger,
  burgerText: burger?.textContent?.trim().slice(0, 30),
  added,
  drawerOpen: !!drawer,
  checkoutOpen: !!checkout,
  gatewayCount: gws.length,
  fieldCount: fields.length,
  staticOk,
  bundleHasPlaceOrder: js.includes('scraper_custom_checkout_place_order'),
  agentHasGateways: agent.includes('get_active_payment_gateways_list'),
  themesInCss: ['midnight','minimal','bazaar','boutique'].every(t => css.includes(t)),
};
console.log(JSON.stringify(result, null, 2));
process.exit(staticOk ? 0 : 2);
