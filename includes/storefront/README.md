# Storefront build assets (fallback copy)

Production JS/CSS for the React storefront.

These files are also at `asset/js/storefront/`. A second copy lives here so
deploys that miss the `asset/` tree still boot via the PHP proxy:

`https://yoursite.ir/?amphp_sf=storefront.js`

Rebuild from `storefront-react/`:

```bash
cd storefront-react && npm ci && npm run build
cp ../asset/js/storefront/storefront.* ./
```
