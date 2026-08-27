# نِکسا | NEXA Shop

فروشگاه لوکس تک‌فایلی PHP با هستهٔ ووکامرس‌گونه، چت پشتیبانی آنلاین، اعلان رویدادها به **بله، تلگرام و روبیکا**، و نصب افزونهٔ وردپرس.

## فایل اصلی

`nexa-shop.php` — تمام منطق، رابط، لایهٔ سازگاری وردپرس و هوک‌های ووکامرس در همین فایل است.

پوشهٔ `assets/` فقط تصویرهای نمایشی محصولات است.

## اجرا با PHP

```bash
php -S 0.0.0.0:8080 nexa-shop.php
```

نیازمندی: PHP 8.0+ با `pdo_sqlite` و `json` — اختیاری: `curl` و `zip`.

## ورود مدیر

- ایمیل: `admin@nexa.shop`
- رمز: `admin123`

## پیام‌رسان‌ها

از **پنل مدیریت → پیام‌رسان و تنظیمات**:

| پیام‌رسان | توکن | Chat ID | API |
|---|---|---|---|
| تلگرام | از `@BotFather` | شناسهٔ عددی شما یا کانال | `api.telegram.org/bot{token}/sendMessage` |
| بله | از بازوپدر بله | شناسه چت | `tapi.bale.ai/bot{token}/sendMessage` |
| روبیکا | توکن بات رسمی | `chat_id` | `botapi.rubika.ir/v3/{token}/sendMessage` |

دکمهٔ «ارسال پیام آزمایشی» را بزنید. وب‌هوک‌ها:

```
https://your-domain/hook/telegram
https://your-domain/hook/bale
https://your-domain/hook/rubika
```

پاسخ به چت از پیام‌رسان:

```
/reply THREAD متن پاسخ
```

رویدادهای ارسالی: سفارش جدید، تغییر وضعیت سفارش، ثبت‌نام، پیام چت، دیدگاه، موجودی کم، نصب افزونه.

## افزونه وردپرس

از **افزونه‌ها** فایل `.php` یا `.zip` را آپلود کنید. لایهٔ سازگاری شامل:

`add_action` `add_filter` `do_action` `apply_filters` `$wpdb` `get_option` `update_option` `add_shortcode` `add_menu_page` و هوک‌های `woocommerce_thankyou`، `woocommerce_add_to_cart`، `woocommerce_order_status_changed`.

افزونه‌های سنگین مثل خودِ ووکامرس یا المنتور به هستهٔ کامل وردپرس نیاز دارند؛ افزونه‌های هوک‌محور و ساده اجرا می‌شوند.

## کوپن‌های نمونه

- `NEXA10` — ۱۰٪
- `WELCOME` — ۱۵۰ هزار تومان (حداقل ۱ میلیون)
