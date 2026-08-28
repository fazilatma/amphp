window.AMPHP_STOREFRONT = {
  settings: {
    shop_title: 'فروشگاه تست',
    shop_subtitle: 'پیش‌نمایش سندباکس',
    accent_color: '#2563eb',
    currency_symbol: 'تومان',
    contact_phone: '021-12345678',
    support_hours: '۹ تا ۲۲',
    top_bar_notice: 'ارسال رایگان برای سفارش‌های بالای ۴۰۰ هزار تومان',
    show_top_bar: true,
    show_features_banner: true,
    show_animated_stats: true,
    show_special_badge: true,
    sticky_header: true,
    default_column_layout: '2',
    products_per_page: 12,
    store_template: 'digikala',
    store_palette: 'digikala-red',
    enable_support_chat: true,
    chat_theme: 'royal-blue',
    chat_button_style: 'pill-label',
    chat_button_position: 'left',
    chat_window_title: 'پشتیبانی',
    chat_welcome_message: 'سلام! چطور می‌تونم کمکتون کنم؟',
    free_shipping_threshold: 400000
  },
  products: [
    {id:'1',title:'ساعت هوشمند اولترا پرو',has_price:true,price:1450000,price_formatted:'۱٬۴۵۰٬۰۰۰ تومان',old_price:1950000,old_price_formatted:'۱٬۹۵۰٬۰۰۰ تومان',has_discount:true,discount_pct:26,image:'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&auto=format&fit=crop&q=60',category:'گجت',description:'سنسور سلامت و ضدآب',in_stock:true},
    {id:'2',title:'هدفون بی‌سیم نویزکنسلینگ',has_price:true,price:890000,price_formatted:'۸۹۰٬۰۰۰ تومان',old_price:1250000,old_price_formatted:'۱٬۲۵۰٬۰۰۰ تومان',has_discount:true,discount_pct:29,image:'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&auto=format&fit=crop&q=60',category:'صوتی',description:'شارژدهی ۴۰ ساعته',in_stock:true},
    {id:'3',title:'اسپیکر بلوتوثی ضدآب',has_price:true,price:620000,price_formatted:'۶۲۰٬۰۰۰ تومان',old_price:850000,old_price_formatted:'۸۵۰٬۰۰۰ تومان',has_discount:true,discount_pct:27,image:'https://images.unsplash.com/photo-1543512214-318c7553f230?w=400&auto=format&fit=crop&q=60',category:'صوتی',description:'باس قدرتمند',in_stock:true},
    {id:'4',title:'کوله پشتی ضد سرقت',has_price:true,price:480000,price_formatted:'۴۸۰٬۰۰۰ تومان',old_price:690000,old_price_formatted:'۶۹۰٬۰۰۰ تومان',has_discount:true,discount_pct:30,image:'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&auto=format&fit=crop&q=60',category:'مد',description:'پورت USB',in_stock:true},
    {id:'5',title:'اسپرسوساز خانگی ۲۰ بار',has_price:true,price:3200000,price_formatted:'۳٬۲۰۰٬۰۰۰ تومان',old_price:4100000,old_price_formatted:'۴٬۱۰۰٬۰۰۰ تومان',has_discount:true,discount_pct:22,image:'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=400&auto=format&fit=crop&q=60',category:'خانگی',description:'فیلتر استیل',in_stock:true},
    {id:'6',title:'کفش ورزشی Air Cushion',has_price:true,price:750000,price_formatted:'۷۵۰٬۰۰۰ تومان',old_price:980000,old_price_formatted:'۹۸۰٬۰۰۰ تومان',has_discount:true,discount_pct:23,image:'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&auto=format&fit=crop&q=60',category:'مد',description:'کفی طبی',in_stock:true}
  ],
  urls: { account: '#', admin: '', home: '/', checkout: '#' },
  ajax: { ajaxUrl: '/admin-ajax.php', cartNonce: 'test', chatNonce: 'test', checkoutUrl: '#' },
  meta: { version: '13.1.1', engine: 'react', count: 6, is_admin: false }
};
