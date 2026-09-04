# Deployer — نصب خودکار جدیدترین نسخه از همه برنچ‌ها

این فایل `deployer.py` کنار `scraper4.py` قرار می‌گیرد و تمام برنچ‌های ریپو را اسکن می‌کند،
جدیدترین `APP_VERSION` را پیدا کرده و به‌صورت اتمیک نصب می‌کند.

## قابلیت‌ها
- ✅ اسکن همه برنچ‌ها از GitHub API (pagination)
- ✅ فیلتر برنچ‌های زیپو: `--filter-zip` یا `--filter zip,arena,deploy`
- ✅ استخراج `APP_VERSION` از هر برنچ و مرتب‌سازی نزولی
- ✅ نصب اتوماتیک جدیدترین نسخه اگر از نسخه محلی جدیدتر باشد
- ✅ بک‌آپ با timestamp و جایگزینی اتمیک
- ✅ به‌روزرسانی `deploy.branch` در `scraper4_data.json`
- ✅ تلاش برای reload وب‌اپ PythonAnywhere (توکن API یا touch WSGI)

## استفاده محلی
```bash
python3 deployer.py --scan --filter-zip --repo fazilatma/amphp
python3 deployer.py --auto --filter-zip --repo fazilatma/amphp
python3 deployer.py --install --branch arena/01a06927-amphp --force
```

## اسنیپت کنسول PythonAnywhere (کپی‌پیست مستقیم)

### گزینه A — یک‌خطی (سریع)
```bash
curl -sSL https://raw.githubusercontent.com/fazilatma/amphp/arena/01a06927-amphp/deployer.py | python3 - --auto --filter-zip --repo fazilatma/amphp
```

### گزینه B — کامل با لاگ و ری‌لود
```bash
cd ~/amphp || cd ~/scraper4 || mkdir -p ~/amphp && cd ~/amphp
curl -sSL https://raw.githubusercontent.com/fazilatma/amphp/arena/01a06927-amphp/deployer.py -o deployer.py
curl -sSL https://raw.githubusercontent.com/fazilatma/amphp/arena/01a06927-amphp/scraper4.py -o scraper4.py.new
python3 -m py_compile scraper4.py.new && mv scraper4.py.new scraper4.py
python3 deployer.py --scan --filter-zip --json
python3 deployer.py --auto --filter-zip --repo fazilatma/amphp --force
# اطمینان از WSGI
echo "from scraper4 import app as application" > /var/www/$(whoami)_pythonanywhere_com_wsgi.py 2>/dev/null || echo "WSGI touch skipped"
touch /var/www/$(whoami)_pythonanywhere_com_wsgi.py 2>/dev/null; echo "Done ✅"
```

### گزینه C — نصب مستقیم آخرین نسخه این برنچ (بدون اسکن همه)
```bash
curl -sSL https://raw.githubusercontent.com/fazilatma/amphp/arena/01a06927-amphp/scraper4.py -o ~/amphp/scraper4.py
python3 -m py_compile ~/amphp/scraper4.py && echo "✅ نصب شد"
```

## API داخل اپ
- `POST /api/system/info` → نسخه‌ها، دیسک، فایل‌ها
- `POST /api/system/php-version` → php -v
- `POST /api/deployer/scan` `{repo, filter, token}` → لیست برنچ‌ها با نسخه
- `POST /api/deployer/install` `{repo, branch?, filter?, force?, token}` → نصب جدیدترین یا برنچ خاص

## رابط انتخاب برنچ با شماره نسخه (جدید 5.3.1)

### داخل scraper4.py — تب تنظیمات → دیپلوی خودکار 📦
- **دکمه 🔍 اسکن همه برنچ‌ها** → تمام برنچ‌ها را از GitHub می‌خواند، هرکدام `APP_VERSION` را استخراج کرده و نزولی مرتب می‌کند
- **دراپ‌داون 🎯 انتخاب دستی برنچ** → هر گزینه به شکل `arena/01a06927-amphp — v5.3.1 ⭐ جدیدترین (396KB)` نمایش داده می‌شود (نام برنچ + نسخه + حجم + SHA + برچسب ⭐)
- **متا زیر دراپ‌داون** → `نسخه: v5.3.1 | حجم: 396KB | SHA: abc1a4a`
- **جدول برنچ‌ها** → هر سطر برنچ + `vX.Y.Z` برجسته + `⭐ جدیدترین` + چیپ KB/SHA/tuple + دکمه 👁 انتخاب (می‌برد به دراپ‌داون) + ⬇️ نصب مستقیم
- **دکمه‌ها**: `⬇️ نصب برنچ انتخاب‌شده` و `⚡ نصب اجباری انتخاب‌شده` → همان `POST /api/deployer/install {branch, force}` را صدا می‌زنند، پس از نصب `location.reload()`

### فایل مستقل deployer.py — حالت --serve
```bash
# اجرای رابط مستقل روی پورت 5001
python3 deployer.py --serve --port 5001 --repo fazilatma/amphp --filter zip,arena,deploy
# سپس باز کنید: http://localhost:5001/  (یا روی PA: https://yourname.pythonanywhere.com/deployer)
```
این UI مستقل دقیقاً همان تجربه انتخاب برنچ + نسخه را دارد، بدون نیاز به اجرای scraper4.py، برای دیسک پر یا خرابی scraper.

## تب‌ها در UI
- **تب شروع**: مدیریت پروفایل، وضعیت همگام‌سازی، همگام‌سازی دستی با progress bar و لاگ
- **تب تنظیمات → سیستم 💻**: نمایش نسخه اپ، پایتون، Flask، PHP، دیسک، فایل‌ها، دکمه سلامت و کپی
- **تب تنظیمات → دیپلوی خودکار 📦**: اسکن همه برنچ‌ها + **دراپ‌داون برنچ با نسخه** + جدول نسخه‌دار + نصب انتخابی/اجباری
