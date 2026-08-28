# AMPHP React Storefront

ویترین کامل فروشگاه با React 18 + Vite.

## توسعه

```bash
cd storefront-react
npm install
npm run dev
```

## ساخت خروجی برای وردپرس

```bash
npm run build
```

خروجی در `../asset/js/storefront/` قرار می‌گیرد:
- `storefront.js`
- `storefront.css`

افزونه `agent.php` این باندل را از طریق shortcodeهای `[scraped_shop]` / `[modern_shop]` و takeover فروشگاه بارگذاری می‌کند.
دادهٔ اولیه از `window.AMPHP_STOREFRONT` تزریق می‌شود.
