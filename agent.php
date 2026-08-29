<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 13.3.19
 * Author: Fazilatma
 * Text Domain: scraper-auto-shop
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Silence is golden.
}

if ( ! class_exists( 'Scraper_Auto_Shop_Plugin' ) ) :

/**
 * Main Scraper & Auto Shop Plugin Class.
 */
class Scraper_Auto_Shop_Plugin {

	/**
	 * Plugin options database key.
	 */
	const OPTION_NAME = 'scraper_auto_shop_settings';

	/**
	 * Get default settings for the plugin.
	 *
	 * @return array
	 */
	public static function get_default_settings() {
		return array(
			// Storefront Dual Controls (4-State System)
			'enable_shop_takeover'        => true, // تیک ۱: قالب و ظاهر مدرن با هدر و منوی اختصاصی
			'enable_scraped_products'     => true, // سازگاری عقب‌رو (true = اسکرپر یا ادغام)
			'catalog_source'              => 'scraper', // scraper | woocommerce | merge
			'catalog_merge_prefer'        => 'scraper', // scraper | woocommerce | keep_both
			'takeover_front_page'         => false, // جایگزینی اختیاری صفحه نخست با ویترین
			'enable_native_wp_template'   => true, // قالب بومی وردپرس (سربرگ و پاصفحهٔ پوسته)
			'native_fallback_page_id'     => 0, // برگه پشتیبان فروشگاه
			'enable_404_shop_redirect'    => true, // ریدایرکت 404 به صفحه پشتیبان
			'set_wc_shop_to_fallback'     => true, // تنظیم صفحه فروشگاه ووکامرس روی پشتیبان در صورت خالی بودن
			'auto_create_fallback_page'   => true, // ساخت خودکار برگه پشتیبان
			'replace_site_header'         => true, // حذف کامل هدر و منوی قالب وردپرس
			'show_top_bar'                => true,
			'top_bar_notice'              => 'تخفیف ویژه امروز: ارسال رایگان برای سفارش‌های بالای ۴۰۰ هزار تومان! 🚚',
			'contact_phone'               => '۰۲۱-۱۲۳۴۵۶۷۸',
			'support_hours'               => 'پاسخگویی ۹ الی ۲۲',
			'shop_title'                  => 'فروشگاه آنلاین نوآوران',
			'shop_subtitle'               => 'تنوع بی‌نظیر کالاها با تضمین اصالت، سلامت فیزیکی و ارسال سریع به سراسر کشور',
			'accent_color'                => '#2563eb',
			'default_column_layout'       => '1', // '1' column default
			'products_per_page'           => 20, // 20 items per page
			'show_features_banner'        => true,
			'show_animated_stats'         => true, // نمایش شماره‌های جذاب انیمیشن‌دار در صفحه فروشگاه
			'show_special_badge'          => true,
			'free_shipping_threshold'     => 400000,

			// Storefront Template & Color Presets
			'store_template'              => 'digikala', // digikala, snappshop, basalam, torob, digistyle, technolife, modern, midnight, minimal, bazaar, boutique, native-wp
			'store_palette'               => 'digikala-red', // digikala-red, snapp-green, basalam-coral, torob-red, digistyle-rose, technolife-blue, royal-blue, luxury-purple, amber-gold, persian-turquoise, midnight-ink, forest, sunset
			'auto_convert_rial'           => true,

			// Custom checkout page (React) — toggle + field controls
			'enable_custom_checkout'      => true,
			'checkout_title'              => 'تسویه حساب امن',
			'checkout_note'               => 'پس از ثبت سفارش، به درگاه پرداخت هدایت می‌شوید یا با شما تماس گرفته می‌شود.',
			'checkout_require_login'      => false,
			'checkout_field_name'         => true,
			'checkout_field_name_req'     => true,
			'checkout_field_phone'        => true,
			'checkout_field_phone_req'    => true,
			'checkout_field_email'        => true,
			'checkout_field_email_req'    => false,
			'checkout_field_province'     => true,
			'checkout_field_province_req' => true,
			'checkout_field_city'         => true,
			'checkout_field_city_req'     => true,
			'checkout_field_address'      => true,
			'checkout_field_address_req'  => true,
			'checkout_field_postal'       => true,
			'checkout_field_postal_req'   => false,
			'checkout_field_notes'        => true,
			'checkout_field_notes_req'    => false,
			'checkout_show_gateways'      => true,
			'checkout_cod_label'          => 'پرداخت در محل (COD)',
			'checkout_success_msg'        => 'سفارش شما با موفقیت ثبت شد. از خریدتان سپاسگزاریم!',
			'checkout_show_shipping'      => true,
			'checkout_show_map'           => true,
			'neshan_api_key'              => '',
			'shipping_origin_city'        => 'تهران',
			'shipping_origin_lat'         => '35.6892',
			'shipping_origin_lng'         => '51.3890',
			'post_shipping_enabled'       => true,
			'chapar_shipping_enabled'     => true,
			'tipax_shipping_enabled'      => true,
			'post_api_token'              => '',
			'chapar_api_token'            => '',
			'tipax_api_token'             => '',
			'shipping_base_post'          => 45000,
			'shipping_base_chapar'        => 65000,
			'shipping_base_tipax'         => 75000,
			'shipping_per_kg'             => 12000,
			'enable_ai_product_images'    => true,
			'ai_image_batch_size'         => 8,
			'ai_image_sideload'           => true,
			'ai_image_search_lang'        => 'fa',
			'enable_google_image_search'  => true,
			'google_cse_api_key'          => '',
			'google_cse_cx'               => '',

			// Pricing & Profit
			'price_markup_percent'        => 20,
			'price_fixed_add'             => 0,
			'price_rounding'              => '1000', // none, 1000, 10000
			'currency_symbol'             => 'تومان',
			'default_fallback_price'      => 150000, // Fallback base price
			'fallback_price_behavior'     => 'use_fallback', // 'use_fallback' or 'call_for_price'

			// Support Chat Settings & Themes
			'enable_support_chat'         => true,
			'chat_theme'                  => 'royal-blue', // 12 themes available
			'chat_button_style'           => 'pill-label', // 6 button designs
			'chat_button_position'        => 'left', // 'left' or 'right'
			'chat_window_title'           => 'پشتیبانی آنلاین فروشگاه',
			'chat_welcome_message'        => 'سلام! خوش آمدید 👋 هرگونه سوالی درباره کالاها، قیمت‌ها یا ثبت سفارش دارید بنویسید تا همکاران ما سریعاً پاسخ دهند.',
			'chat_field_name_enable'      => true,
			'chat_field_name_required'    => false,
			'chat_field_phone_enable'     => true,
			'chat_field_phone_required'   => false,
			'chat_field_email_enable'     => true,
			'chat_field_email_required'   => false,
			'chat_field_subject_enable'   => false,
			'chat_field_subject_required' => false,

			// AI & Human Admin Coordination
			'ai_coordination_mode'        => 'ai_first', // 'ai_first', 'ai_copilot', 'human_only', 'ai_only'
			'ai_support_name'             => 'پشتیبان هوشمند فروشگاه',
			'ai_system_prompt'            => 'شما پشتیبان صمیمی، محترم و متخصص فروشگاه آنلاین هستید. بر اساس مشخصات محصولات به مشتریان در خرید و انتخاب کالا کمک کنید. در صورت نیاز به بررسی شماره فاکتور یا پیگیری سفارش، اطمینان دهید که شماره تماس مشتری ثبت شده و کارشناسان سریعاً تماس خواهند گرفت.',
			'ai_provider'                 => 'auto', // 'auto' (from scraper connections.json), 'openai', 'gemini'
			'ai_api_key'                  => '',
			'ai_model'                    => '',

			// Messengers
			'bale_token'                  => '',
			'bale_chat_id'                => '',
			'telegram_token'              => '',
			'telegram_chat_id'            => '',
			'rubika_token'                => '',
			'rubika_chat_id'              => '',

			// WordPress Internal Cron (WP-Cron) Integration
			'enable_wp_cron_sync'         => true,
			'wp_cron_interval'            => 'every_30_mins',

			// Store Title Typography & Font
			'shop_title_font'             => 'vazirmatn', // vazirmatn, iranyekan, dana, yekanbakh, shabnam, sahel, iransans, morabba, parastoo, system, custom
			'shop_title_custom_font'      => '',
			'shop_title_font_size'        => 'normal', // small, normal, large, xlarge
			'shop_title_font_weight'      => '900', // 500, 700, 800, 900
			'sticky_header'               => true, // هدر و منوی چسبان به بالا

			// Store Analytics & Funnel Messenger Notifications
			'notif_event_site_visit'      => false,
			'notif_event_product_view'    => false,
			'notif_event_add_to_cart'     => true,
			'notif_event_checkout_step'   => true,
			'notif_event_order_placed'    => true,

			// Analytics data source: internal (plugin funnel), wordpress (WP/Woo core), hybrid (merge both)
			'analytics_source'            => 'hybrid',

			// Suppress other plugins' ads, upsells and nag notices in wp-admin
			'hide_admin_nags'             => true,
		);
	}

	/**
	 * Get active settings merged with defaults safely.
	 *
	 * @return array
	 */
	public static function get_settings() {
		$defaults = self::get_default_settings();
		$opts     = get_option( self::OPTION_NAME, $defaults );

		if ( ! is_array( $opts ) ) {
			$opts = $defaults;
		} else {
			$opts = array_merge( $defaults, $opts );
		}

		/* v13.3.8: فونت سراسری از connections.json (انتخاب اسکرپر) اولویت دارد */
		try {
			$cn = self::get_scraper_connections();
			if ( is_array( $cn ) ) {
				$fk = trim( (string) ( $cn['ui_font'] ?? $cn['app_font'] ?? '' ) );
				if ( $fk !== '' ) {
					$opts['shop_title_font'] = $fk;
					$opts['app_font']        = $fk;
				}
			}
		} catch ( \Throwable $e ) {
			// ignore
		}

		return $opts;
	}

	/**
	 * Activation hook callback.
	 */
	public static function on_activate() {
		try {
			$opts = get_option( self::OPTION_NAME, false );
			if ( false === $opts || ! is_array( $opts ) ) {
				update_option( self::OPTION_NAME, self::get_default_settings() );
			}
			// v13.3.17: ensure native fallback shop page + optional WC shop mapping
			self::ensure_fallback_shop_page( true );
			flush_rewrite_rules( false );
		} catch ( \Throwable $e ) {
			wp_die( 'Activation Error: ' . esc_html( $e->getMessage() ) );
		}
	}

	/**
	 * Deactivation hook callback.
	 * Flushes rewrite rules and cached transients so WordPress and WooCommerce
	 * immediately and cleanly revert to their original state without lingering filters.
	 */
	public static function on_deactivate() {
		wp_clear_scheduled_hook( 'scraper_auto_shop_cron_sync' );
		delete_transient( 'scraper_shop_cached_products' );
		wp_cache_flush();
	}

	/**
	 * Initialize plugin hooks.
	 */
	public static function init() {
		// Admin menu
		add_action( 'admin_menu', array( __CLASS__, 'register_admin_menus' ) );

		// Shortcodes for modern shop
		add_shortcode( 'scraped_shop', array( __CLASS__, 'render_shop_shortcode' ) );
		add_shortcode( 'modern_shop', array( __CLASS__, 'render_shop_shortcode' ) );

		// WooCommerce shop page takeover
		add_filter( 'template_include', array( __CLASS__, 'maybe_takeover_shop_template' ), 99 );
		// Register page templates from plugin + 404 → fallback shop redirect
		add_filter( 'theme_page_templates', array( __CLASS__, 'register_native_page_templates' ) );
		add_filter( 'template_include', array( __CLASS__, 'maybe_load_native_page_template' ), 98 );
		add_action( 'template_redirect', array( __CLASS__, 'maybe_redirect_404_to_fallback_shop' ), 5 );
		add_action( 'admin_init', array( __CLASS__, 'maybe_ensure_fallback_shop_page' ), 20 );
		add_action( 'wp_ajax_scraper_ensure_fallback_shop', array( __CLASS__, 'ajax_ensure_fallback_shop' ) );

		// Serve storefront JS/CSS via PHP (works even if static files blocked or not yet synced)
		add_action( 'init', array( __CLASS__, 'maybe_serve_storefront_asset' ), 0 );

		// Complete suppression of legacy WordPress theme header & menu when custom storefront is enabled
		add_action( 'wp_head', array( __CLASS__, 'inject_custom_header_suppression_css' ), 1 );

		// AJAX actions for syncing to WooCommerce
		add_action( 'wp_ajax_scraper_sync_to_woo', array( __CLASS__, 'ajax_sync_to_woo' ) );
		add_action( 'wp_ajax_scraper_direct_sync_progress', array( __CLASS__, 'ajax_direct_sync_progress' ) );
		add_action( 'wp_ajax_scraper_test_woo_direct', array( __CLASS__, 'ajax_test_woo_direct' ) );
		add_action( 'wp_ajax_scraper_enable_woo_direct', array( __CLASS__, 'ajax_enable_woo_direct' ) );

		// Support chat AJAX endpoints (Customer & AI thread)
		add_action( 'wp_ajax_submit_support_chat', array( __CLASS__, 'ajax_submit_support_chat' ) );
		add_action( 'wp_ajax_nopriv_submit_support_chat', array( __CLASS__, 'ajax_submit_support_chat' ) );
		add_action( 'wp_ajax_scraper_submit_support_chat', array( __CLASS__, 'ajax_submit_support_chat' ) );
		add_action( 'wp_ajax_nopriv_scraper_submit_support_chat', array( __CLASS__, 'ajax_submit_support_chat' ) );
		add_action( 'wp_ajax_scraper_customer_get_thread', array( __CLASS__, 'ajax_customer_get_thread' ) );
		add_action( 'wp_ajax_nopriv_scraper_customer_get_thread', array( __CLASS__, 'ajax_customer_get_thread' ) );

		// Admin Live Chat Reply Desk endpoints
		add_action( 'wp_ajax_scraper_admin_send_chat_reply', array( __CLASS__, 'ajax_admin_send_chat_reply' ) );
		add_action( 'wp_ajax_scraper_admin_get_thread', array( __CLASS__, 'ajax_admin_get_thread' ) );
		add_action( 'wp_ajax_scraper_admin_delete_thread', array( __CLASS__, 'ajax_admin_delete_thread' ) );

		// Messenger test
		add_action( 'wp_ajax_test_support_messengers', array( __CLASS__, 'ajax_test_support_messengers' ) );

		// Live AI chat test endpoint
		add_action( 'wp_ajax_scraper_test_ai_chat', array( __CLASS__, 'ajax_test_ai_chat' ) );
		add_action( 'wp_ajax_scraper_compare_ai_candidates', array( __CLASS__, 'ajax_compare_ai_candidates' ) );
		add_action( 'wp_ajax_scraper_vote_ai_candidate', array( __CLASS__, 'ajax_vote_ai_candidate' ) );
		add_action( 'wp_ajax_scraper_pin_master_model', array( __CLASS__, 'ajax_pin_master_model' ) );
		add_action( 'wp_ajax_scraper_upload_ai_config', array( __CLASS__, 'ajax_upload_ai_config' ) );
		add_action( 'wp_ajax_scraper_export_ai_config', array( __CLASS__, 'ajax_export_ai_config' ) );

		// WordPress Internal Cron (WP-Cron) Integration for scraper automation
		add_filter( 'cron_schedules', array( __CLASS__, 'filter_cron_schedules' ) );
		add_action( 'scraper_auto_shop_cron_sync', array( __CLASS__, 'execute_scraper_cron_job' ) );
		add_action( 'wp_ajax_scraper_run_wpcron_now', array( __CLASS__, 'ajax_run_wpcron_now' ) );

		// Analytics Tracking Endpoints
		add_action( 'wp_ajax_scraper_track_event', array( __CLASS__, 'ajax_track_analytics_event' ) );
		add_action( 'wp_ajax_nopriv_scraper_track_event', array( __CLASS__, 'ajax_track_analytics_event' ) );
		add_action( 'wp_ajax_scraper_reset_analytics', array( __CLASS__, 'ajax_reset_analytics' ) );
		add_action( 'woocommerce_new_order', array( __CLASS__, 'on_wc_order_created' ), 10, 2 );
		self::sync_wp_cron_schedule();
		self::sync_ai_image_cron_schedule();

		// WooCommerce Real Cart Session Sync endpoints
		add_action( 'wp_ajax_scraper_wc_add_to_cart', array( __CLASS__, 'ajax_wc_add_to_cart' ) );
		add_action( 'wp_ajax_nopriv_scraper_wc_add_to_cart', array( __CLASS__, 'ajax_wc_add_to_cart' ) );
		add_action( 'wp_ajax_scraper_wc_update_cart_qty', array( __CLASS__, 'ajax_wc_update_cart_qty' ) );
		add_action( 'wp_ajax_nopriv_scraper_wc_update_cart_qty', array( __CLASS__, 'ajax_wc_update_cart_qty' ) );
		add_action( 'wp_ajax_scraper_wc_remove_cart_item', array( __CLASS__, 'ajax_wc_remove_cart_item' ) );
		add_action( 'wp_ajax_nopriv_scraper_wc_remove_cart_item', array( __CLASS__, 'ajax_wc_remove_cart_item' ) );
		add_action( 'wp_ajax_scraper_wc_get_cart', array( __CLASS__, 'ajax_wc_get_cart' ) );
		add_action( 'wp_ajax_nopriv_scraper_wc_get_cart', array( __CLASS__, 'ajax_wc_get_cart' ) );
		add_action( 'wp_ajax_scraper_wc_sync_and_checkout', array( __CLASS__, 'ajax_wc_sync_and_checkout' ) );
		add_action( 'wp_ajax_nopriv_scraper_wc_sync_and_checkout', array( __CLASS__, 'ajax_wc_sync_and_checkout' ) );
		add_action( 'wp_ajax_scraper_custom_checkout_place_order', array( __CLASS__, 'ajax_custom_checkout_place_order' ) );
		add_action( 'wp_ajax_nopriv_scraper_custom_checkout_place_order', array( __CLASS__, 'ajax_custom_checkout_place_order' ) );
		add_action( 'wp_ajax_scraper_get_payment_gateways', array( __CLASS__, 'ajax_get_payment_gateways' ) );
		add_action( 'wp_ajax_nopriv_scraper_get_payment_gateways', array( __CLASS__, 'ajax_get_payment_gateways' ) );
		add_action( 'wp_ajax_scraper_get_iran_cities', array( __CLASS__, 'ajax_get_iran_cities' ) );
		add_action( 'wp_ajax_nopriv_scraper_get_iran_cities', array( __CLASS__, 'ajax_get_iran_cities' ) );
		add_action( 'wp_ajax_scraper_get_shipping_methods', array( __CLASS__, 'ajax_get_shipping_methods' ) );
		add_action( 'wp_ajax_nopriv_scraper_get_shipping_methods', array( __CLASS__, 'ajax_get_shipping_methods' ) );
		add_action( 'wp_ajax_scraper_calc_shipping', array( __CLASS__, 'ajax_calc_shipping' ) );
		add_action( 'wp_ajax_nopriv_scraper_calc_shipping', array( __CLASS__, 'ajax_calc_shipping' ) );
		add_action( 'wp_ajax_scraper_neshan_geocode', array( __CLASS__, 'ajax_neshan_geocode' ) );
		add_action( 'wp_ajax_nopriv_scraper_neshan_geocode', array( __CLASS__, 'ajax_neshan_geocode' ) );
		add_action( 'wp_ajax_scraper_ai_enrich_images', array( __CLASS__, 'ajax_ai_enrich_images' ) );
		add_action( 'wp_ajax_scraper_ai_enrich_one_image', array( __CLASS__, 'ajax_ai_enrich_one_image' ) );
		add_action( 'wp_ajax_nopriv_scraper_ai_enrich_one_image', array( __CLASS__, 'ajax_ai_enrich_one_image' ) );
		add_action( 'scraper_ai_image_enrich_cron', array( __CLASS__, 'cron_ai_enrich_product_images' ) );
		add_action( 'wp_ajax_scraper_get_product', array( __CLASS__, 'ajax_get_product' ) );
		add_action( 'wp_ajax_nopriv_scraper_get_product', array( __CLASS__, 'ajax_get_product' ) );

		// Filters for WooCommerce Cart & Checkout guarantees
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'fix_cart_item_prices' ), 20, 1 );
		add_filter( 'woocommerce_cart_item_thumbnail', array( __CLASS__, 'filter_cart_item_thumbnail' ), 10, 3 );
		add_filter( 'woocommerce_is_purchasable', '__return_true', 999 );
		add_filter( 'woocommerce_product_is_in_stock', '__return_true', 999 );

		// Enqueue scripts & styles for storefront
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_front_assets' ) );
		add_filter( 'body_class', array( __CLASS__, 'filter_storefront_body_class' ) );
		add_filter( 'show_admin_bar', array( __CLASS__, 'maybe_hide_admin_bar_on_storefront' ) );

		// WordPress-core hit counter (used when analytics_source is wordpress/hybrid)
		add_action( 'template_redirect', array( __CLASS__, 'maybe_track_wp_core_hit' ), 1 );
		add_action( 'template_redirect', array( __CLASS__, 'maybe_track_wp_product_view_on_product' ), 2 );
		add_action( 'woocommerce_track_product_view', array( __CLASS__, 'maybe_track_wp_product_view' ) );

		// Hide other plugins' ads / nag notices in wp-admin (toggleable)
		add_action( 'admin_init', array( __CLASS__, 'setup_admin_noise_suppression' ), 0 );
	}

	/**
	 * Register WordPress Admin Menus.
	 */
	public static function register_admin_menus() {
		add_menu_page(
			'فروشگاه و اسکرپر',
			'فروشگاه و اسکرپر',
			'manage_options',
			'scraper-auto-shop',
			array( __CLASS__, 'render_admin_settings_page' ),
			'dashicons-cart',
			56
		);

		add_submenu_page(
			'scraper-auto-shop',
			'تنظیمات فروشگاه، منوها و قیمت',
			'تنظیمات فروشگاه و منوها',
			'manage_options',
			'scraper-auto-shop',
			array( __CLASS__, 'render_admin_settings_page' )
		);

		add_submenu_page(
			'scraper-auto-shop',
			'پنل کامل اسکرپر (scraper4)',
			'پنل اسکرپر (scraper4)',
			'manage_options',
			'scraper-full-dashboard',
			array( __CLASS__, 'render_scraper4_embed_page' )
		);
	}

	/**
	 * Convert Persian / Arabic numerals to English ASCII digits.
	 *
	 * @param string|int|float $str
	 * @return string
	 */
	public static function persian_to_english_digits( $str ) {
		$persian = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
		$arabic  = array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' );
		$english = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
		return str_replace( $arabic, $english, str_replace( $persian, $english, (string) $str ) );
	}

	/**
	 * Convert English numbers to Persian digits for display.
	 *
	 * @param string|int|float $str
	 * @return string
	 */
	public static function to_fa_num( $str ) {
		$en = array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' );
		$fa = array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' );
		return str_replace( $en, $fa, (string) $str );
	}

	/**
	 * Format a numeric amount with Persian digits and currency symbol.
	 *
	 * @param float|int|string $amount
	 * @param string|null      $currency
	 * @return string
	 */
	public static function format_price( $amount, $currency = null ) {
		$val = floatval( $amount );
		if ( null === $currency || '' === $currency ) {
			$settings = self::get_settings();
			$currency = (string) ( $settings['currency_symbol'] ?? 'تومان' );
		}
		return self::to_fa_num( number_format( $val ) ) . ( $currency !== '' ? ' ' . $currency : '' );
	}

	/**
	 * Extract raw numeric price from any scraped product object or string.
	 *
	 * @param mixed $prod
	 * @return float
	 */
	public static function extract_raw_price( $prod ) {
		if ( ! is_array( $prod ) ) {
			if ( is_numeric( $prod ) ) {
				return (float) $prod;
			}
			$raw_str = (string) $prod;
			$is_rial = (
				stripos( $raw_str, 'ریال' ) !== false ||
				stripos( $raw_str, 'rial' ) !== false ||
				stripos( $raw_str, 'irr' ) !== false
			);
			$s = self::persian_to_english_digits( $raw_str );
			$s = str_replace( array( ',', '،', '٫', ' ', '-', '_' ), '', $s );
			// In Iranian eCommerce, '.' is widely used as thousands separator (e.g. 150.000 or 1.500.000)
			$s = str_replace( '.', '', $s );
			$c = preg_replace( '/[^\d]/', '', $s );
			if ( empty( $c ) ) {
				return 0;
			}
			$val = (float) $c;
			if ( $is_rial && $val > 1000 ) {
				$val = $val / 10;
			}
			return $val;
		}

		$is_rial = false;
		$unit = strtolower( (string) ( $prod['price_unit'] ?? $prod['unit'] ?? '' ) );
		if ( 'rial' === $unit || 'irr' === $unit || stripos( $unit, 'ریال' ) !== false ) {
			$is_rial = true;
		}

		$candidates = array(
			'sale_price',
			'final_price',
			'price',
			'primary_price',
			'display_price',
			'new_price',
			'price_val',
			'price_min',
			'regular_price',
			'orig_price',
			'display_regular_price',
			'old_price',
			'amount',
			'raw_price',
		);

		foreach ( $candidates as $k ) {
			if ( ! isset( $prod[ $k ] ) || $prod[ $k ] === '' || $prod[ $k ] === null ) {
				continue;
			}
			$val = $prod[ $k ];
			if ( is_array( $val ) ) {
				$val = $val['value'] ?? $val['amount'] ?? $val['raw'] ?? $val['price'] ?? reset( $val );
			}
			if ( is_scalar( $val ) ) {
				$raw_str = (string) $val;
				if ( stripos( $raw_str, 'ریال' ) !== false || stripos( $raw_str, 'rial' ) !== false || stripos( $raw_str, 'irr' ) !== false ) {
					$is_rial = true;
				}
				$s = self::persian_to_english_digits( $raw_str );
				$s = str_replace( array( ',', '،', '٫', ' ', '-', '_' ), '', $s );
				$s = str_replace( '.', '', $s );
				$c = preg_replace( '/[^\d]/', '', $s );
				if ( is_numeric( $c ) && (float) $c > 0 ) {
					$num = (float) $c;
					if ( $is_rial && $num > 1000 ) {
						$num = $num / 10;
					}
					return $num;
				}
			}
		}

		// Check price_rial
		if ( ! empty( $prod['price_rial'] ) ) {
			$s = self::persian_to_english_digits( (string) $prod['price_rial'] );
			$s = str_replace( array( ',', '،', '٫', ' ', '-', '_' ), '', $s );
			$s = str_replace( '.', '', $s );
			$c = preg_replace( '/[^\d]/', '', $s );
			if ( is_numeric( $c ) && (float) $c > 0 ) {
				return (float) $c / 10;
			}
		}

		// Check variation_prices
		if ( ! empty( $prod['variation_prices'] ) && is_array( $prod['variation_prices'] ) ) {
			foreach ( $prod['variation_prices'] as $vp ) {
				$raw_vp = is_array( $vp ) ? ( $vp['price'] ?? reset( $vp ) ) : $vp;
				$num = self::extract_raw_price( $raw_vp );
				if ( $num > 0 ) {
					return $num;
				}
			}
		}

		return 0;
	}

	/**
	 * Accurately extract raw regular / old (strikethrough) price.
	 *
	 * @param mixed $prod
	 * @return float
	 */
	public static function extract_raw_old_price( $prod ) {
		if ( ! is_array( $prod ) ) {
			return 0;
		}

		$old_candidates = array(
			'regular_price',
			'old_price',
			'orig_price',
			'display_regular_price',
			'srcPrice',
			'original_price',
			'del_price',
			'price_before_discount',
		);

		foreach ( $old_candidates as $k ) {
			if ( ! isset( $prod[ $k ] ) || $prod[ $k ] === '' || $prod[ $k ] === null ) {
				continue;
			}
			$val = $prod[ $k ];
			if ( is_array( $val ) ) {
				$val = $val['value'] ?? $val['amount'] ?? $val['raw'] ?? reset( $val );
			}
			$num = self::extract_raw_price( $val );
			if ( $num > 0 ) {
				return $num;
			}
		}

		return 0;
	}

	/**
	 * Price Adjustment Engine.
	 * Calculates adjusted price, preserves discounts, handles Rial/Toman, and supports fallback base prices.
	 *
	 * @param mixed      $raw_price
	 * @param array|null $settings
	 * @return array
	 */
	public static function calculate_price( $raw_price, $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}

		$original = self::extract_raw_price( $raw_price );
		$old_raw  = is_array( $raw_price ) ? self::extract_raw_old_price( $raw_price ) : 0;

		$fallback   = (float) ( $settings['default_fallback_price'] ?? 150000 );
		$behavior   = (string) ( $settings['fallback_price_behavior'] ?? 'use_fallback' );
		$markup_pct = (float) ( $settings['price_markup_percent'] ?? 0 );
		$fixed_add  = (float) ( $settings['price_fixed_add'] ?? 0 );
		$rounding   = (string) ( $settings['price_rounding'] ?? '1000' );
		$currency   = (string) ( $settings['currency_symbol'] ?? 'تومان' );

		$has_valid_source_price = ( $original > 0 );

		// If no source price was found:
		if ( $original <= 0 ) {
			if ( 'use_fallback' === $behavior && $fallback > 0 ) {
				$original = $fallback;
			} elseif ( $fixed_add > 0 ) {
				$original = $fixed_add;
			} else {
				return array(
					'has_price'              => false,
					'has_valid_source_price' => false,
					'original'               => 0,
					'adjusted'               => 0,
					'old_price'              => 0,
					'has_discount'           => false,
					'discount_pct'           => 0,
					'formatted'              => 'تماس بگیرید',
					'formatted_old'          => '',
					'currency'               => $currency,
					'markup_pct'             => $markup_pct,
				);
			}
		}

		// Calculate adjusted current price
		$adjusted = $original * ( 1 + ( $markup_pct / 100 ) ) + $fixed_add;

		// Apply rounding
		if ( '1000' === $rounding && $adjusted > 1000 ) {
			$adjusted = round( $adjusted / 1000 ) * 1000;
		} elseif ( '10000' === $rounding && $adjusted > 10000 ) {
			$adjusted = round( $adjusted / 10000 ) * 10000;
		} else {
			$adjusted = round( $adjusted );
		}

		// Calculate adjusted old price & discount percent
		$adjusted_old = 0;
		$has_discount = false;
		$discount_pct = 0;
		$formatted_old = '';

		if ( $old_raw > $original ) {
			$adjusted_old = $old_raw * ( 1 + ( $markup_pct / 100 ) ) + $fixed_add;
			if ( '1000' === $rounding && $adjusted_old > 1000 ) {
				$adjusted_old = round( $adjusted_old / 1000 ) * 1000;
			} elseif ( '10000' === $rounding && $adjusted_old > 10000 ) {
				$adjusted_old = round( $adjusted_old / 10000 ) * 10000;
			} else {
				$adjusted_old = round( $adjusted_old );
			}

			if ( $adjusted_old > $adjusted ) {
				$has_discount  = true;
				$discount_pct  = (int) round( ( ( $adjusted_old - $adjusted ) / $adjusted_old ) * 100 );
				$formatted_old = self::to_fa_num( number_format( $adjusted_old ) ) . ' ' . $currency;
			}
		}

		$formatted = self::to_fa_num( number_format( $adjusted ) ) . ' ' . $currency;

		return array(
			'has_price'              => true,
			'has_valid_source_price' => $has_valid_source_price,
			'original'               => $original,
			'adjusted'               => $adjusted,
			'old_price'              => $adjusted_old,
			'has_discount'           => $has_discount,
			'discount_pct'           => $discount_pct,
			'formatted'              => $formatted,
			'formatted_old'          => $formatted_old,
			'currency'               => $currency,
			'markup_pct'             => $markup_pct,
		);
	}

	public static function get_profiles_summary() {
		$summary = array();
		$file = method_exists( __CLASS__, 'find_profiles_json_path' ) ? self::find_profiles_json_path() : '';
		if ( $file === '' ) {
			$candidates = array(
				plugin_dir_path( __FILE__ ) . 'profiles.json',
				dirname( __FILE__ ) . '/profiles.json',
				defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR . '/uploads/profiles.json' : '',
			);
			foreach ( $candidates as $cand ) {
				if ( ! empty( $cand ) && file_exists( $cand ) ) { $file = $cand; break; }
			}
		}

		if ( ! $file ) {
			return $summary;
		}

		$json = @file_get_contents( $file );
		$data = @json_decode( $json, true );
		if ( ! is_array( $data ) ) {
			return $summary;
		}

		foreach ( $data as $key => $p ) {
			if ( ! is_array( $p ) ) {
				continue;
			}
			$name = ! empty( $p['name'] ) ? $p['name'] : ( ! empty( $p['url'] ) ? parse_url( $p['url'], PHP_URL_HOST ) : $key );
			$url  = $p['url'] ?? '';
			$prods = $p['products'] ?? array();
			$count = 0;
			if ( is_array( $prods ) ) {
				$count = count( $prods );
			}
			$summary[] = array(
				'key'     => $key,
				'name'    => $name,
				'url'     => $url,
				'count'   => $count,
			);
		}

		return $summary;
	}

	/**
	 * Dynamically load all extracted products from profiles.json and scraper temporary files.
	 *
	 * @return array
	 */
	/**
	 * Retrieve native WooCommerce / WordPress products from the database.
	 * Used when the user unchecks the scraped products toggle (State 2 or State 4).
	 *
	 * @return array
	 */
	public static function get_woocommerce_native_products() {
		$products = array();

		if ( function_exists( 'wc_get_products' ) ) {
			$wc_prods = wc_get_products( array(
				'limit'   => -1, /* v13.3.8: همهٔ محصولات ووکامرس */
				'status'  => 'publish',
				'orderby' => 'date',
				'order'   => 'DESC',
			) );

			if ( ! empty( $wc_prods ) && is_array( $wc_prods ) ) {
				foreach ( $wc_prods as $wp_prod ) {
					if ( ! is_object( $wp_prod ) ) continue;
					$p_id = $wp_prod->get_id();
					$price = (float) $wp_prod->get_price();
					$reg_price = (float) $wp_prod->get_regular_price();
					$sale_price = (float) $wp_prod->get_sale_price();
					$has_discount = ( $sale_price > 0 && $reg_price > $sale_price );
					$discount_pct = ( $has_discount && $reg_price > 0 ) ? round( ( ( $reg_price - $sale_price ) / $reg_price ) * 100 ) : 0;

					$img_id = $wp_prod->get_image_id();
					$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'full' ) : '';
					if ( empty( $img_url ) || self::is_bad_product_image( $img_url ) ) {
						$scraped_img = get_post_meta( $p_id, '_scraped_image_url', true );
						$ai_img = get_post_meta( $p_id, '_amphp_ai_image_url', true );
						if ( $ai_img && ! self::is_bad_product_image( $ai_img ) ) {
							$img_url = $ai_img;
						} elseif ( $scraped_img && ! self::is_bad_product_image( $scraped_img ) ) {
							$img_url = $scraped_img;
						} else {
							$img_url = ''; /* v13.3.14: never show WC/Snapp placeholder in storefront */
						}
					}

					$cat_list = wc_get_product_category_list( $p_id );
					$clean_cat = wp_strip_all_tags( $cat_list );
					if ( empty( $clean_cat ) ) {
						$clean_cat = 'کالای عمومی';
					}

					$desc = wp_strip_all_tags( $wp_prod->get_short_description() ?: $wp_prod->get_description() );

					$products[] = array(
						'id'                  => 'wc_' . $p_id,
						'wc_id'               => $p_id,
						'title'               => $wp_prod->get_name(),
						'has_price'           => ( $price > 0 ),
						'original_price'      => $reg_price ?: $price,
						'price'               => $price,
						'price_formatted'     => number_format( $price ) . ' تومان',
						'old_price'           => $has_discount ? $reg_price : 0,
						'old_price_formatted' => $has_discount ? ( number_format( $reg_price ) . ' تومان' ) : '',
						'has_discount'        => $has_discount,
						'discount_pct'        => $discount_pct,
						'image'               => $img_url,
						'gallery'             => array( $img_url ),
						'category'            => $clean_cat,
						'description'         => $desc,
						'in_stock'            => $wp_prod->is_in_stock(),
						'source'              => 'woocommerce',
						'source_label'        => 'ووکامرس',
					);
				}
			}
		}

		// Fallback to get_posts if wc_get_products returned empty
		if ( empty( $products ) ) {
			$raw_posts = get_posts( array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => -1, /* v13.3.8 all */
			) );

			if ( ! empty( $raw_posts ) ) {
				foreach ( $raw_posts as $post ) {
					$pid = $post->ID;
					$price = (float) get_post_meta( $pid, '_price', true );
					$reg_price = (float) get_post_meta( $pid, '_regular_price', true );
					$sale_price = (float) get_post_meta( $pid, '_sale_price', true );
					$has_discount = ( $sale_price > 0 && $reg_price > $sale_price );
					$discount_pct = ( $has_discount && $reg_price > 0 ) ? round( ( ( $reg_price - $sale_price ) / $reg_price ) * 100 ) : 0;

					$img = get_the_post_thumbnail_url( $pid, 'full' );
					if ( empty( $img ) ) {
						$img = get_post_meta( $pid, '_scraped_image_url', true );
					}

					$cats = wp_get_post_terms( $pid, 'product_cat', array( 'fields' => 'names' ) );
					$cat = ( ! empty( $cats ) && ! is_wp_error( $cats ) ) ? $cats[0] : 'کالای عمومی';

					$products[] = array(
						'id'                  => 'wc_' . $pid,
						'wc_id'               => $pid,
						'title'               => get_the_title( $pid ),
						'has_price'           => ( $price > 0 ),
						'original_price'      => $reg_price ?: $price,
						'price'               => $price,
						'price_formatted'     => number_format( $price ) . ' تومان',
						'old_price'           => $has_discount ? $reg_price : 0,
						'old_price_formatted' => $has_discount ? ( number_format( $reg_price ) . ' تومان' ) : '',
						'has_discount'        => $has_discount,
						'discount_pct'        => $discount_pct,
						'image'               => $img ?: '',
						'gallery'             => array( $img ?: '' ),
						'category'            => $cat,
						'description'         => wp_strip_all_tags( $post->post_excerpt ?: $post->post_content ),
						'in_stock'            => true,
						'source'              => 'woocommerce',
						'source_label'        => 'ووکامرس',
					);
				}
			}
		}

		return $products;
	}

	/**
	 * v13.3.8: همهٔ محصولات استخراج‌شده از profiles.json (بدون سقف).
	 * مسیر پروفایل مثل get_profiles_summary چندجا چک می‌شود.
	 *
	 * @return array
	 */
	public static function find_profiles_json_path() {
		$candidates = array(
			plugin_dir_path( __FILE__ ) . 'profiles.json',
			dirname( __FILE__ ) . '/profiles.json',
			defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR . '/uploads/profiles.json' : '',
			defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR . '/uploads/amphp/profiles.json' : '',
			dirname( plugin_dir_path( __FILE__ ) ) . '/profiles.json',
		);
		// کنار scraper4.php اگر agent جدا باشد
		$scraper_sib = dirname( __FILE__ ) . '/scraper4.php';
		if ( is_file( $scraper_sib ) ) {
			$candidates[] = dirname( $scraper_sib ) . '/profiles.json';
		}
		foreach ( $candidates as $cand ) {
			if ( ! empty( $cand ) && is_file( $cand ) && is_readable( $cand ) ) {
				return $cand;
			}
		}
		return '';
	}


	/**
	 * v13.3.8: منبع کاتالوگ ویترین — scraper | woocommerce | merge
	 */
	public static function resolve_catalog_source( $settings = null ) {
		if ( ! is_array( $settings ) ) {
			$settings = self::get_settings();
		}
		$cs = sanitize_key( (string) ( $settings['catalog_source'] ?? '' ) );
		if ( in_array( $cs, array( 'scraper', 'woocommerce', 'merge' ), true ) ) {
			return $cs;
		}
		return ! empty( $settings['enable_scraped_products'] ) ? 'scraper' : 'woocommerce';
	}

	public static function catalog_dedupe_key( $p ) {
		if ( ! is_array( $p ) ) {
			return '';
		}
		$title = function_exists( 'mb_strtolower' )
			? mb_strtolower( trim( (string) ( $p['title'] ?? '' ) ), 'UTF-8' )
			: strtolower( trim( (string) ( $p['title'] ?? '' ) ) );
		$title = preg_replace( '/\s+/u', ' ', $title );
		$sku = trim( (string) ( $p['sku'] ?? $p['key'] ?? '' ) );
		if ( $sku !== '' ) {
			$norm = function_exists( 'mb_strtolower' ) ? mb_strtolower( $sku, 'UTF-8' ) : strtolower( $sku );
			return 'sku:' . $norm;
		}
		return 't:' . md5( $title );
	}

	public static function merge_catalog_products( $primary, $secondary, $prefer = 'scraper' ) {
		$out  = array();
		$seen = array();
		$prefer = in_array( $prefer, array( 'scraper', 'woocommerce', 'keep_both' ), true ) ? $prefer : 'scraper';
		foreach ( array( $primary, $secondary ) as $list ) {
			foreach ( (array) $list as $p ) {
				if ( ! is_array( $p ) ) {
					continue;
				}
				if ( trim( (string) ( $p['title'] ?? '' ) ) === '' ) {
					continue;
				}
				if ( $prefer === 'keep_both' ) {
					$idk = 'id:' . (string) ( $p['id'] ?? '' );
					if ( $idk !== 'id:' && isset( $seen[ $idk ] ) ) {
						continue;
					}
					$seen[ $idk ] = true;
					$out[] = $p;
					continue;
				}
				$dk = self::catalog_dedupe_key( $p );
				if ( $dk === '' ) {
					$dk = 'id:' . (string) ( $p['id'] ?? uniqid( 'p', true ) );
				}
				if ( isset( $seen[ $dk ] ) ) {
					continue;
				}
				$seen[ $dk ] = true;
				$out[] = $p;
			}
		}
		return $out;
	}

	public static function get_all_scraped_products() {
		$settings = self::get_settings();
		$source   = self::resolve_catalog_source( $settings );

		if ( $source === 'woocommerce' ) {
			return self::get_woocommerce_native_products();
		}

		$products = array();
		$seen     = array();

		$profiles_file = self::find_profiles_json_path();
		if ( $profiles_file !== '' ) {
			$json = @file_get_contents( $profiles_file );
			$data = @json_decode( (string) $json, true );
			if ( is_array( $data ) ) {
				foreach ( $data as $p_key => $p_item ) {
					if ( ! is_array( $p_item ) ) {
						continue;
					}
					$raw_prods = $p_item['products'] ?? array();
					if ( ! is_array( $raw_prods ) ) {
						continue;
					}
					/* productsOrder: ترتیب نمایش اگر باشد */
					$order = $p_item['productsOrder'] ?? $p_item['products_order'] ?? null;
					$ordered_entries = array();
					if ( is_array( $order ) && $order ) {
						$map = array();
						foreach ( $raw_prods as $ek => $entry ) {
							if ( is_array( $entry ) && isset( $entry[1] ) && is_array( $entry[1] ) ) {
								$k = (string) ( $entry[0] ?? $ek );
								$map[ $k ] = $entry;
							} elseif ( is_array( $entry ) && ( isset( $entry['title'] ) || isset( $entry['name'] ) ) ) {
								$k = (string) ( $entry['key'] ?? $ek );
								$map[ $k ] = $entry;
							} else {
								$map[ (string) $ek ] = $entry;
							}
						}
						$used = array();
						foreach ( $order as $ok ) {
							$ok = (string) $ok;
							if ( isset( $map[ $ok ] ) ) {
								$ordered_entries[] = $map[ $ok ];
								$used[ $ok ] = true;
							}
						}
						foreach ( $map as $mk => $mv ) {
							if ( empty( $used[ $mk ] ) ) {
								$ordered_entries[] = $mv;
							}
						}
					} else {
						foreach ( $raw_prods as $entry ) {
							$ordered_entries[] = $entry;
						}
					}

					foreach ( $ordered_entries as $entry ) {
						$prod = null;
						if ( is_array( $entry ) ) {
							if ( isset( $entry[1] ) && is_array( $entry[1] ) ) {
								$prod = $entry[1];
								if ( empty( $prod['key'] ) && isset( $entry[0] ) ) {
									$prod['key'] = (string) $entry[0];
								}
							} elseif ( isset( $entry['title'] ) || isset( $entry['name'] ) ) {
								$prod = $entry;
							}
						}
						if ( ! is_array( $prod ) ) {
							continue;
						}

						$title = trim( (string) ( $prod['title'] ?? $prod['name'] ?? '' ) );
						if ( '' === $title ) {
							continue;
						}

						$link = (string) ( $prod['link'] ?? $prod['url'] ?? '' );
						$hash = md5( $title . $link );
						/* key پایدار اگر باشد */
						$pkey = trim( (string) ( $prod['key'] ?? $prod['id'] ?? '' ) );
						$uid  = $pkey !== '' ? ( 'k:' . $pkey ) : ( 'h:' . $hash );
						if ( isset( $seen[ $uid ] ) || isset( $seen[ 'h:' . $hash ] ) ) {
							continue;
						}
						$seen[ $uid ] = true;
						$seen[ 'h:' . $hash ] = true;

						$price_calc = self::calculate_price( $prod, $settings );

						$img = $prod['image'] ?? $prod['img'] ?? '';
						$gallery = array();
						if ( ! empty( $prod['images'] ) && is_array( $prod['images'] ) ) {
							$gallery = $prod['images'];
							if ( empty( $img ) && ! empty( $gallery[0] ) ) {
								$img = $gallery[0];
							}
						} elseif ( ! empty( $prod['gallery'] ) && is_array( $prod['gallery'] ) ) {
							$gallery = $prod['gallery'];
							if ( empty( $img ) && ! empty( $gallery[0] ) ) {
								$img = $gallery[0];
							}
						} elseif ( ! empty( $img ) ) {
							$gallery[] = $img;
						}
						/* v13.3.14: drop Snapp/WC placeholders; prefer AI image */
						$_img_res = self::resolve_product_images( array_merge( $prod, array( 'image' => $img, 'gallery' => $gallery ) ) );
						$img = $_img_res['image'];
						$gallery = $_img_res['gallery'];

						$desc = $prod['long_desc'] ?? $prod['longDesc'] ?? $prod['description'] ?? $prod['desc'] ?? $prod['short_desc'] ?? $prod['shortDesc'] ?? '';
						$cat  = $prod['category'] ?? $prod['cat'] ?? 'عمومی';
						if ( is_array( $cat ) ) {
							$cat = (string) ( $cat['name'] ?? $cat[0] ?? 'عمومی' );
						}
						$cat = trim( (string) $cat );
						if ( $cat === '' ) {
							$cat = 'عمومی';
						}

						$in_stock = true;
						if ( array_key_exists( 'in_stock', $prod ) ) {
							$in_stock = (bool) $prod['in_stock'];
						} elseif ( isset( $prod['stock'] ) && is_numeric( $prod['stock'] ) ) {
							$in_stock = ( (int) $prod['stock'] ) > 0;
						} elseif ( ! empty( $prod['unavailable'] ) || ! empty( $prod['out_of_stock'] ) ) {
							$in_stock = false;
						}

						$products[] = array(
							'id'                  => $hash,
							'key'                 => $pkey,
							'title'               => $title,
							'has_price'           => $price_calc['has_price'],
							'original_price'      => $price_calc['original'],
							'price'               => $price_calc['adjusted'],
							'price_formatted'     => $price_calc['formatted'],
							'old_price'           => $price_calc['old_price'],
							'old_price_formatted' => $price_calc['formatted_old'],
							'has_discount'        => $price_calc['has_discount'],
							'discount_pct'        => $price_calc['discount_pct'],
							'image'               => $img,
							'gallery'             => $gallery,
							'category'            => $cat,
							'description'         => $desc,
							'short_desc'          => (string) ( $prod['short_desc'] ?? $prod['shortDesc'] ?? '' ),
							'in_stock'            => $in_stock,
							'profile_key'         => (string) $p_key,
							'variations_text'     => (string) ( $prod['variations_text'] ?? '' ),
							'variation_groups'    => is_array( $prod['variation_groups'] ?? null ) ? $prod['variation_groups'] : array(),
							'source'              => 'scraper',
							'source_label'        => 'اسکرپر',
						);
					}
				}
			}
		}

		// Also check scraper temp cache (woo_products_temp.json)
		$woo_temps = array(
			plugin_dir_path( __FILE__ ) . 'woo_products_temp.json',
			plugin_dir_path( __FILE__ ) . 'bsl_products.json',
		);
		foreach ( $woo_temps as $woo_temp ) {
			if ( ! file_exists( $woo_temp ) ) {
				continue;
			}
			$json = @file_get_contents( $woo_temp );
			$data = @json_decode( (string) $json, true );
			if ( ! is_array( $data ) ) {
				continue;
			}
			foreach ( $data as $prod ) {
				if ( ! is_array( $prod ) ) {
					continue;
				}
				$title = trim( (string) ( $prod['title'] ?? $prod['name'] ?? '' ) );
				if ( '' === $title ) {
					continue;
				}
				$hash = md5( $title . ( $prod['link'] ?? $prod['url'] ?? '' ) );
				if ( isset( $seen[ 'h:' . $hash ] ) ) {
					continue;
				}
				$seen[ 'h:' . $hash ] = true;
				$price_calc = self::calculate_price( $prod, $settings );
				$img        = $prod['image'] ?? $prod['img'] ?? '';
				$gallery    = ! empty( $prod['images'] ) && is_array( $prod['images'] ) ? $prod['images'] : ( ! empty( $img ) ? array( $img ) : array() );
				$_img_res = self::resolve_product_images( array_merge( is_array( $prod ) ? $prod : array(), array( 'image' => $img, 'gallery' => $gallery ) ) );
				$img = $_img_res['image'];
				$gallery = $_img_res['gallery'];
				$products[] = array(
					'id'                  => $hash,
					'title'               => $title,
					'has_price'           => $price_calc['has_price'],
					'original_price'      => $price_calc['original'],
					'price'               => $price_calc['adjusted'],
					'price_formatted'     => $price_calc['formatted'],
					'old_price'           => $price_calc['old_price'],
					'old_price_formatted' => $price_calc['formatted_old'],
					'has_discount'        => $price_calc['has_discount'],
					'discount_pct'        => $price_calc['discount_pct'],
					'image'               => $img,
					'gallery'             => $gallery,
					'category'            => (string) ( $prod['category'] ?? $prod['cat'] ?? 'عمومی' ),
					'description'         => (string) ( $prod['description'] ?? $prod['desc'] ?? '' ),
					'in_stock'            => true,
					'source'              => 'scraper',
					'source_label'        => 'اسکرپر',
				);
			}
		}

		/* v13.3.8: ادغام با ووکامرس یا fallback اگر اسکرپر خالی است */
		$need_woo = ( $source === 'merge' ) || empty( $products );
		if ( $need_woo ) {
			$woo_list = self::get_woocommerce_native_products();
			if ( ! empty( $woo_list ) ) {
				if ( $source === 'merge' ) {
					$prefer = sanitize_key( (string) ( $settings['catalog_merge_prefer'] ?? 'scraper' ) );
					if ( $prefer === 'woocommerce' ) {
						$products = self::merge_catalog_products( $woo_list, $products, 'woocommerce' );
					} elseif ( $prefer === 'keep_both' ) {
						$products = self::merge_catalog_products( $products, $woo_list, 'keep_both' );
					} else {
						$products = self::merge_catalog_products( $products, $woo_list, 'scraper' );
					}
				} elseif ( empty( $products ) ) {
					$products = $woo_list;
				}
			}
		}

		// Demo fallback only when truly empty (preview sandbox)
		if ( empty( $products ) ) {
			$demo_items = array(
				array(
					'id'            => 'demo_1',
					'title'         => 'ساعت هوشمند اولترا پرو مدل Max-9 با صفحه نمایش AMOLED',
					'raw_price'     => 1450000,
					'old_price_raw' => 1950000,
					'image'         => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600&auto=format&fit=crop&q=80',
					'category'      => 'گجت‌های هوشمند',
					'description'   => 'دارای سنسورهای پایش سلامت، مکالمه بلوتوثی باکیفیت و ضد آب با استاندارد IP68.',
				),
				array(
					'id'            => 'demo_2',
					'title'         => 'هدفون بی‌سیم نویز کنسلینگ استریو مدل Studio Pro',
					'raw_price'     => 890000,
					'old_price_raw' => 1250000,
					'image'         => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600&auto=format&fit=crop&q=80',
					'category'      => 'لوازم صوتی',
					'description'   => 'کیفیت صدای Hi-Res، شارژدهی تا ۴۰ ساعت مداوم و بالشتک‌های فوق‌العاده راحت.',
				),
				array(
					'id'            => 'demo_3',
					'title'         => 'اسپیکر قابل حمل بلوتوثی ضدآب باس قدرتمند مدل Boom 3',
					'raw_price'     => 620000,
					'old_price_raw' => 850000,
					'image'         => 'https://images.unsplash.com/photo-1543512214-318c7553f230?w=600&auto=format&fit=crop&q=80',
					'category'      => 'لوازم صوتی',
					'description'   => 'صدای فراگیر ۳۶۰ درجه، باتری قدرتمند ۲۴ ساعته و مقاومت در برابر پاشش آب.',
				),
				array(
					'id'            => 'demo_4',
					'title'         => 'کوله پشتی مسافرتی ارگونومیک ضد سرقت با پورت شارژ USB',
					'raw_price'     => 480000,
					'old_price_raw' => 690000,
					'image'         => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600&auto=format&fit=crop&q=80',
					'category'      => 'مد و پوشاک',
					'description'   => 'جنس برزنتی ضد آب، محفظه اختصاصی لپ‌تاپ تا ۱۵.۶ اینچ و طراحی سبک و مقاوم.',
				),
				array(
					'id'            => 'demo_5',
					'title'         => 'دستگاه اسپرسوساز خانگی ۲۰ بار تمام استیل اتوماتیک',
					'raw_price'     => 3200000,
					'old_price_raw' => 4100000,
					'image'         => 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=600&auto=format&fit=crop&q=80',
					'category'      => 'لوازم خانگی',
					'description'   => 'پمپ فشار ۲۰ بار ایتالیایی، سیستم کاپوچینوساز سریع و فیلتر دولایه استیل.',
				),
				array(
					'id'            => 'demo_6',
					'title'         => 'کفش ورزشی راحتی مدل Air Cushion مخصوص پیاده‌روی و دویدن',
					'raw_price'     => 750000,
					'old_price_raw' => 980000,
					'image'         => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&auto=format&fit=crop&q=80',
					'category'      => 'مد و پوشاک',
					'description'   => 'کفی طبی ضد شوک، بافت تنفس‌پذیر ژاکارد و انعطاف‌پذیری فوق‌العاده بالا.',
				),
			);

			foreach ( $demo_items as $d ) {
				$calc = self::calculate_price( array(
					'price'         => $d['raw_price'],
					'regular_price' => $d['old_price_raw'],
				), $settings );

				$products[] = array(
					'id'                  => $d['id'],
					'title'               => $d['title'],
					'has_price'           => $calc['has_price'],
					'original_price'      => $calc['original'],
					'price'               => $calc['adjusted'],
					'price_formatted'     => $calc['formatted'],
					'old_price'           => $calc['old_price'],
					'old_price_formatted' => $calc['formatted_old'],
					'has_discount'        => $calc['has_discount'],
					'discount_pct'        => $calc['discount_pct'],
					'image'               => $d['image'],
					'gallery'             => array( $d['image'] ),
					'category'            => $d['category'],
					'description'         => $d['description'],
					'in_stock'            => true,
				);
			}
		}

		return $products;
	}

	/** Transient key for live direct-sync product cards (admin UI). */
	const DIRECT_SYNC_PROGRESS_KEY = 'amphp_direct_sync_progress';

	/**
	 * Write live progress for admin product-card queue UI.
	 *
	 * @param array $data Progress payload.
	 */
	public static function set_direct_sync_progress( $data ) {
		set_transient( self::DIRECT_SYNC_PROGRESS_KEY, $data, 30 * MINUTE_IN_SECONDS );
	}

	/**
	 * Read live progress for admin product-card queue UI.
	 *
	 * @return array
	 */
	public static function get_direct_sync_progress() {
		$d = get_transient( self::DIRECT_SYNC_PROGRESS_KEY );
		return is_array( $d ) ? $d : array();
	}

	/**
	 * Sync scraped products into WooCommerce with per-product card details.
	 * Progress is stored so the admin UI can poll a live product queue.
	 *
	 * @return array
	 */
	public static function sync_to_woocommerce() {
		$settings = self::get_settings();
		$products = self::get_all_scraped_products();
		$created  = 0;
		$updated  = 0;
		$failed   = 0;
		$skipped  = 0;
		$cards    = array();
		$total    = is_array( $products ) ? count( $products ) : 0;
		$run_id   = 'ds_' . time() . '_' . wp_generate_password( 4, false );

		if ( empty( $products ) ) {
			$out = array(
				'ok'      => false,
				'message' => 'هیچ محصولی برای همگام‌سازی یافت نشد.',
				'created' => 0,
				'updated' => 0,
				'failed'  => 0,
				'skipped' => 0,
				'total'   => 0,
				'cards'   => array(),
				'run_id'  => $run_id,
			);
			self::set_direct_sync_progress( array_merge( $out, array(
				'running' => false,
				'done'    => true,
				'current' => 0,
			) ) );
			return $out;
		}

		self::set_direct_sync_progress( array(
			'ok'      => true,
			'running' => true,
			'done'    => false,
			'run_id'  => $run_id,
			'total'   => $total,
			'current' => 0,
			'created' => 0,
			'updated' => 0,
			'failed'  => 0,
			'skipped' => 0,
			'cards'   => array(),
			'message' => 'شروع همگام‌سازی مستقیم…',
			'via'     => 'direct',
			'ts'      => time(),
		) );

		$i = 0;
		foreach ( $products as $p ) {
			$i++;
			$title = isset( $p['title'] ) ? (string) $p['title'] : '';
			$img   = isset( $p['image'] ) ? (string) $p['image'] : '';
			$price = isset( $p['price'] ) ? $p['price'] : 0;
			$orig  = isset( $p['original_price'] ) ? $p['original_price'] : 0;
			$cat   = isset( $p['category'] ) ? (string) $p['category'] : '';
			$key   = isset( $p['key'] ) ? (string) $p['key'] : (string) $i;
			$desc  = isset( $p['description'] ) ? (string) $p['description'] : '';

			$card = array(
				'key'      => $key,
				'title'    => $title,
				'image'    => $img,
				'price'    => $price,
				'category' => $cat,
				'status'   => 'pending',
				'detail'   => '',
				'woo_id'   => 0,
				'via'      => 'direct',
				'at'       => time(),
			);

			if ( $title === '' ) {
				$skipped++;
				$card['status'] = 'skipped';
				$card['detail'] = 'عنوان خالی';
				$cards[] = $card;
				self::set_direct_sync_progress( array(
					'ok' => true, 'running' => true, 'done' => false, 'run_id' => $run_id,
					'total' => $total, 'current' => $i,
					'created' => $created, 'updated' => $updated, 'failed' => $failed, 'skipped' => $skipped,
					'cards' => $cards, 'last_title' => $title, 'via' => 'direct', 'ts' => time(),
					'message' => 'رد شد: بدون عنوان',
				) );
				continue;
			}

			$existing_id = 0;
			$existing = get_posts( array(
				'post_type'   => 'product',
				'title'       => $title,
				'post_status' => 'any',
				'numberposts' => 1,
			) );
			if ( ! empty( $existing ) && isset( $existing[0]->ID ) ) {
				$existing_id = (int) $existing[0]->ID;
			}

			$post_data = array(
				'post_title'   => $title,
				'post_content' => $desc,
				'post_status'  => 'publish',
				'post_type'    => 'product',
			);

			$product_id = 0;
			$action_lbl = 'created';
			try {
				if ( $existing_id > 0 ) {
					$post_data['ID'] = $existing_id;
					$product_id      = wp_update_post( $post_data, true );
					$action_lbl = 'updated';
				} else {
					$product_id = wp_insert_post( $post_data, true );
					$action_lbl = 'created';
				}
			} catch ( Exception $ex ) {
				$product_id = new WP_Error( 'exception', $ex->getMessage() );
			}

			if ( is_wp_error( $product_id ) || ! $product_id ) {
				$failed++;
				$card['status'] = 'failed';
				$card['detail'] = is_wp_error( $product_id )
					? $product_id->get_error_message()
					: 'درج ناموفق';
				$cards[] = $card;
			} else {
				$product_id = (int) $product_id;
				update_post_meta( $product_id, '_price', $price );
				update_post_meta( $product_id, '_regular_price', $price );
				if ( is_numeric( $orig ) && is_numeric( $price ) && (float) $orig > (float) $price ) {
					update_post_meta( $product_id, '_sale_price', $price );
					update_post_meta( $product_id, '_regular_price', $orig );
				}
				update_post_meta( $product_id, '_manage_stock', 'no' );
				update_post_meta( $product_id, '_stock_status', 'instock' );
				update_post_meta( $product_id, '_amphp_sync_via', 'direct' );
				update_post_meta( $product_id, '_amphp_sync_at', time() );
				if ( $cat !== '' ) {
					wp_set_object_terms( $product_id, $cat, 'product_cat' );
				}
				if ( $img !== '' && ! has_post_thumbnail( $product_id ) ) {
					self::attach_external_image( $product_id, $img, $title );
				}
				if ( $action_lbl === 'updated' ) {
					$updated++;
					$card['status'] = 'updated';
					$card['detail'] = 'به‌روزرسانی شد';
				} else {
					$created++;
					$card['status'] = 'created';
					$card['detail'] = 'ایجاد شد';
				}
				$card['woo_id'] = $product_id;
				$cards[] = $card;
			}

			/* keep last ~80 cards in live payload for UI weight */
			$live_cards = count( $cards ) > 80 ? array_slice( $cards, -80 ) : $cards;
			self::set_direct_sync_progress( array(
				'ok'         => true,
				'running'    => true,
				'done'       => false,
				'run_id'     => $run_id,
				'total'      => $total,
				'current'    => $i,
				'created'    => $created,
				'updated'    => $updated,
				'failed'     => $failed,
				'skipped'    => $skipped,
				'cards'      => $live_cards,
				'last_title' => $title,
				'last_status'=> $card['status'],
				'via'        => 'direct',
				'ts'         => time(),
				'message'    => $i . '/' . $total . ' — ' . $title,
			) );

			/* allow long runs on shared hosts */
			if ( $i % 5 === 0 && function_exists( 'wp_cache_flush' ) ) {
				/* no-op keep-alive */
			}
		}

		$out = array(
			'ok'      => true,
			'message' => sprintf(
				'همگام‌سازی مستقیم تمام شد: %d جدید، %d آپدیت، %d خطا از %d کالا.',
				$created, $updated, $failed, $total
			),
			'created' => $created,
			'updated' => $updated,
			'failed'  => $failed,
			'skipped' => $skipped,
			'total'   => $total,
			'cards'   => $cards,
			'run_id'  => $run_id,
			'via'     => 'direct',
		);
		self::set_direct_sync_progress( array_merge( $out, array(
			'running' => false,
			'done'    => true,
			'current' => $total,
			'ts'      => time(),
		) ) );
		return $out;
	}

	/**
	 * Attach external image URL as featured thumbnail.
	 *
	 * @param int    $post_id
	 * @param string $image_url
	 * @param string $title
	 */

	/**
	 * Detect empty / placeholder / SnappShop generic card images.
	 *
	 * @param string $url
	 * @return bool true = bad/missing image that should be replaced
	 */
	public static function is_bad_product_image( $url ) {
		$url = trim( (string) $url );
		if ( $url === '' ) {
			return true;
		}
		$u = strtolower( $url );
		// data URIs / tiny placeholders
		if ( strpos( $u, 'data:image' ) === 0 ) {
			return true;
		}
		$bad_bits = array(
			'placeholder', 'no-image', 'no_image', 'noimage', 'default-image', 'default_image',
			'woocommerce-placeholder', 'wc-placeholder', 'blank.gif', 'blank.png', '1x1.',
			'spacer', 'transparent', 'loading.gif', 'lazy-load', 'image-not-found',
			'coming-soon', 'product-default', 'default-product', 'missing-image',
			// SnappShop generic / fallback card art patterns
			'snappshop', 'snapp.ir/static', 'snappshop.ir/static', 'snappshop.ir/assets',
			'cdn.snapp', 'snappfood', 'default_product', 'product_placeholder',
			'img-placeholder', 'empty-product', 'without-image', 'without_image',
			'/static/images/product', '/images/default', 'dummy-image', 'dummy_image',
		);
		foreach ( $bad_bits as $b ) {
			if ( strpos( $u, $b ) !== false ) {
				return true;
			}
		}
		// very short path often = sprite/icon
		$path = (string) ( parse_url( $url, PHP_URL_PATH ) ?: '' );
		if ( $path !== '' && preg_match( '/\.(svg|gif)$/i', $path ) && strlen( basename( $path ) ) < 12 ) {
			return true;
		}
		return false;
	}

	/**
	 * Normalize image URL for storefront: drop placeholders, prefer AI/meta overrides.
	 *
	 * @param array $p product row
	 * @param int   $wc_id optional WC product id
	 * @return array { image, gallery, image_source }
	 */
	public static function resolve_product_images( $p, $wc_id = 0 ) {
		$p = is_array( $p ) ? $p : array();
		$img = trim( (string) ( $p['image'] ?? $p['img'] ?? '' ) );
		$gallery = array();
		if ( ! empty( $p['gallery'] ) && is_array( $p['gallery'] ) ) {
			$gallery = $p['gallery'];
		} elseif ( ! empty( $p['images'] ) && is_array( $p['images'] ) ) {
			$gallery = $p['images'];
		}
		$source = 'original';

		// WC / meta overrides
		if ( $wc_id > 0 ) {
			$ai_url = get_post_meta( $wc_id, '_amphp_ai_image_url', true );
			if ( $ai_url && ! self::is_bad_product_image( $ai_url ) ) {
				$img = (string) $ai_url;
				$source = 'ai_meta';
			} else {
				$scraped = get_post_meta( $wc_id, '_scraped_image_url', true );
				if ( ( $img === '' || self::is_bad_product_image( $img ) ) && $scraped && ! self::is_bad_product_image( $scraped ) ) {
					$img = (string) $scraped;
					$source = 'scraped_meta';
				}
			}
		}
		// product-level AI fields from scraper JSON
		foreach ( array( 'ai_image', 'ai_image_url', 'enriched_image' ) as $k ) {
			if ( ! empty( $p[ $k ] ) && ! self::is_bad_product_image( $p[ $k ] ) ) {
				$img = (string) $p[ $k ];
				$source = 'ai_json';
				break;
			}
		}

		if ( self::is_bad_product_image( $img ) ) {
			$img = '';
			$source = 'none';
		}
		// clean gallery
		$clean_g = array();
		foreach ( (array) $gallery as $g ) {
			$g = trim( (string) $g );
			if ( $g !== '' && ! self::is_bad_product_image( $g ) ) {
				$clean_g[] = $g;
			}
		}
		if ( $img !== '' && ! in_array( $img, $clean_g, true ) ) {
			array_unshift( $clean_g, $img );
		} elseif ( $img === '' && ! empty( $clean_g ) ) {
			$img = $clean_g[0];
			$source = $source === 'none' ? 'gallery' : $source;
		}
		return array(
			'image'        => $img,
			'gallery'      => array_values( array_unique( $clean_g ) ),
			'image_source' => $source,
			'needs_image'  => ( $img === '' ),
		);
	}

	/**
	 * Schedule AI image enrichment cron when enabled.
	 */
	public static function sync_ai_image_cron_schedule( $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}
		$hook = 'scraper_ai_image_enrich_cron';
		$enabled = ! empty( $settings['enable_ai_product_images'] );
		if ( ! $enabled ) {
			$ts = wp_next_scheduled( $hook );
			if ( $ts ) {
				wp_unschedule_event( $ts, $hook );
			}
			return;
		}
		if ( ! wp_next_scheduled( $hook ) ) {
			wp_schedule_event( time() + 120, 'every_30_mins', $hook );
		}
	}

	/**
	 * WP-Cron: enrich a batch of products missing real images.
	 */
	public static function cron_ai_enrich_product_images() {
		$settings = self::get_settings();
		if ( empty( $settings['enable_ai_product_images'] ) ) {
			return;
		}
		$batch = max( 1, min( 20, intval( $settings['ai_image_batch_size'] ?? 8 ) ) );
		try {
			self::enrich_missing_product_images( $batch, false );
		} catch ( \Throwable $e ) {
			error_log( 'AMPHP ai image cron: ' . $e->getMessage() );
		}
	}

	/**
	 * Admin AJAX: run batch image enrichment now.
	 */
	public static function ajax_ai_enrich_images() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 180 );
		}
		$limit = max( 1, min( 30, intval( $_POST['limit'] ?? 8 ) ) );
		$result = self::enrich_missing_product_images( $limit, true );
		if ( ! empty( $result['ok'] ) ) {
			wp_send_json_success( $result );
		}
		wp_send_json_error( $result );
	}

	/**
	 * Storefront/public AJAX: enrich one product image on demand (when card has no image).
	 */
	public static function ajax_ai_enrich_one_image() {
		// Soft auth — cart nonce if present, else allow with rate limit
		$nonce = isset( $_REQUEST['nonce'] ) ? (string) $_REQUEST['nonce'] : '';
		if ( $nonce && ! wp_verify_nonce( $nonce, 'scraper_cart_nonce' ) && ! wp_verify_nonce( $nonce, 'scraper_shop_admin_nonce' ) ) {
			// still allow but rate-limit
		}
		$ip = isset( $_SERVER['REMOTE_ADDR'] ) ? (string) $_SERVER['REMOTE_ADDR'] : '0';
		$rl = 'amphp_img_rl_' . md5( $ip );
		$hits = intval( get_transient( $rl ) );
		if ( $hits > 40 ) {
			wp_send_json_error( 'محدودیت درخواست. کمی بعد دوباره تلاش کنید.' );
		}
		set_transient( $rl, $hits + 1, 10 * MINUTE_IN_SECONDS );

		$settings = self::get_settings();
		if ( empty( $settings['enable_ai_product_images'] ) ) {
			wp_send_json_error( 'غنی‌سازی تصویر غیرفعال است.' );
		}
		$id = sanitize_text_field( wp_unslash( $_REQUEST['id'] ?? $_REQUEST['product_id'] ?? '' ) );
		$title = sanitize_text_field( wp_unslash( $_REQUEST['title'] ?? '' ) );
		$category = sanitize_text_field( wp_unslash( $_REQUEST['category'] ?? '' ) );
		if ( $id === '' && $title === '' ) {
			wp_send_json_error( 'شناسه یا عنوان لازم است.' );
		}
		$one = self::enrich_single_product_image( array(
			'id'       => $id,
			'title'    => $title,
			'category' => $category,
			'image'    => sanitize_text_field( wp_unslash( $_REQUEST['image'] ?? '' ) ),
		) );
		if ( ! empty( $one['ok'] ) && ! empty( $one['image'] ) ) {
			wp_send_json_success( $one );
		}
		wp_send_json_error( $one['message'] ?? 'تصویری یافت نشد.' );
	}

	/**
	 * Find products that need images and enrich up to $limit.
	 *
	 * @param int  $limit
	 * @param bool $verbose
	 * @return array
	 */
	public static function enrich_missing_product_images( $limit = 8, $verbose = false ) {
		$settings = self::get_settings();
		$done = 0;
		$failed = 0;
		$skipped = 0;
		$cards = array();
		$seen = array();

		// 1) WooCommerce products without real featured image
		if ( function_exists( 'wc_get_products' ) ) {
			$wc_prods = wc_get_products( array(
				'limit'  => 80,
				'status' => 'publish',
				'orderby'=> 'date',
				'order'  => 'DESC',
			) );
			foreach ( (array) $wc_prods as $wp_prod ) {
				if ( $done >= $limit ) {
					break;
				}
				if ( ! is_object( $wp_prod ) ) {
					continue;
				}
				$pid = (int) $wp_prod->get_id();
				// already AI-enriched recently?
				if ( get_post_meta( $pid, '_amphp_ai_image_url', true ) && ! self::is_bad_product_image( get_post_meta( $pid, '_amphp_ai_image_url', true ) ) ) {
					$skipped++;
					continue;
				}
				$img_id = $wp_prod->get_image_id();
				$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'full' ) : '';
				$scraped = get_post_meta( $pid, '_scraped_image_url', true );
				$candidate = $img_url ?: $scraped;
				if ( ! self::is_bad_product_image( $candidate ) ) {
					$skipped++;
					continue;
				}
				// skip if we failed recently
				$fail_at = intval( get_post_meta( $pid, '_amphp_ai_image_fail_at', true ) );
				if ( $fail_at && ( time() - $fail_at ) < DAY_IN_SECONDS ) {
					$skipped++;
					continue;
				}
				$res = self::enrich_single_product_image( array(
					'id'       => 'wc_' . $pid,
					'wc_id'    => $pid,
					'title'    => $wp_prod->get_name(),
					'category' => '',
					'image'    => $candidate,
				) );
				$cards[] = $res;
				if ( ! empty( $res['ok'] ) ) {
					$done++;
				} else {
					$failed++;
				}
				$seen[ 'wc_' . $pid ] = true;
			}
		}

		// 2) Scraped catalog products
		if ( $done < $limit ) {
			$products = self::get_all_scraped_products();
			foreach ( (array) $products as $prod ) {
				if ( $done >= $limit ) {
					break;
				}
				if ( ! is_array( $prod ) ) {
					continue;
				}
				$pid = (string) ( $prod['id'] ?? '' );
				if ( $pid === '' || isset( $seen[ $pid ] ) ) {
					continue;
				}
				$resolved = self::resolve_product_images( $prod );
				if ( empty( $resolved['needs_image'] ) ) {
					$skipped++;
					continue;
				}
				// skip if already has ai_image in raw
				if ( ! empty( $prod['ai_image'] ) && ! self::is_bad_product_image( $prod['ai_image'] ) ) {
					$skipped++;
					continue;
				}
				$res = self::enrich_single_product_image( array(
					'id'       => $pid,
					'title'    => (string) ( $prod['title'] ?? '' ),
					'category' => (string) ( $prod['category'] ?? '' ),
					'image'    => (string) ( $prod['image'] ?? '' ),
				) );
				$cards[] = $res;
				if ( ! empty( $res['ok'] ) ) {
					$done++;
				} else {
					$failed++;
				}
			}
		}

		$msg = sprintf( 'غنی‌سازی تصویر: %d موفق، %d ناموفق، %d ردشده.', $done, $failed, $skipped );
		update_option( 'amphp_ai_image_last_run', array(
			'at'      => time(),
			'done'    => $done,
			'failed'  => $failed,
			'skipped' => $skipped,
			'message' => $msg,
		), false );
		// bust product cache
		delete_transient( 'scraper_shop_cached_products' );
		return array(
			'ok'      => true,
			'message' => $msg,
			'done'    => $done,
			'failed'  => $failed,
			'skipped' => $skipped,
			'cards'   => $verbose ? $cards : array_slice( $cards, -15 ),
		);
	}

	/**
	 * Enrich one product: web image search → optional AI pick → sideload / meta.
	 *
	 * @param array $ctx id, title, category, image, wc_id
	 * @return array
	 */
	public static function enrich_single_product_image( $ctx ) {
		$settings = self::get_settings();
		$title = trim( (string) ( $ctx['title'] ?? '' ) );
		$id    = (string) ( $ctx['id'] ?? '' );
		$cat   = trim( (string) ( $ctx['category'] ?? '' ) );
		$wc_id = intval( $ctx['wc_id'] ?? 0 );
		if ( $wc_id <= 0 && preg_match( '/^wc_(\d+)$/', $id, $m ) ) {
			$wc_id = (int) $m[1];
		}
		if ( $title === '' && $wc_id > 0 && function_exists( 'wc_get_product' ) ) {
			$pr = wc_get_product( $wc_id );
			if ( $pr ) {
				$title = $pr->get_name();
			}
		}
		if ( $title === '' ) {
			return array( 'ok' => false, 'id' => $id, 'message' => 'عنوان خالی است.' );
		}

		// Collect candidate image URLs from the open web
		$candidates = self::search_product_image_candidates( $title, $cat );
		if ( empty( $candidates ) ) {
			if ( $wc_id > 0 ) {
				update_post_meta( $wc_id, '_amphp_ai_image_fail_at', time() );
			}
			return array( 'ok' => false, 'id' => $id, 'title' => $title, 'message' => 'نتیجه‌ای در جستجوی وب یافت نشد.' );
		}

		// Ask AI to pick best URL when available
		$best = self::ai_pick_best_product_image( $title, $cat, $candidates, $settings );
		if ( ! $best || self::is_bad_product_image( $best ) ) {
			$best = $candidates[0]['url'] ?? '';
		}
		if ( ! $best || self::is_bad_product_image( $best ) ) {
			return array( 'ok' => false, 'id' => $id, 'title' => $title, 'message' => 'URL معتبر انتخاب نشد.' );
		}

		// Validate remote image is reachable and looks like an image
		$best = self::normalize_image_url( $best );
		if ( ! self::remote_url_looks_like_image( $best ) ) {
			// try next candidates
			$picked = '';
			foreach ( $candidates as $c ) {
				$u = self::normalize_image_url( $c['url'] ?? '' );
				if ( $u && self::remote_url_looks_like_image( $u ) ) {
					$picked = $u;
					break;
				}
			}
			$best = $picked;
		}
		if ( ! $best ) {
			return array( 'ok' => false, 'id' => $id, 'title' => $title, 'message' => 'دانلود/اعتبارسنجی تصویر ناموفق.' );
		}

		$final_url = $best;
		$sideloaded = false;
		$att_id = 0;

		// Persist on WooCommerce product
		if ( $wc_id > 0 ) {
			update_post_meta( $wc_id, '_amphp_ai_image_url', esc_url_raw( $best ) );
			update_post_meta( $wc_id, '_scraped_image_url', esc_url_raw( $best ) );
			update_post_meta( $wc_id, '_amphp_ai_image_at', time() );
			delete_post_meta( $wc_id, '_amphp_ai_image_fail_at' );

			if ( ! empty( $settings['ai_image_sideload'] ) ) {
				try {
					self::attach_external_image( $wc_id, $best, $title );
					if ( has_post_thumbnail( $wc_id ) ) {
						$att_id = (int) get_post_thumbnail_id( $wc_id );
						$local = wp_get_attachment_image_url( $att_id, 'full' );
						if ( $local ) {
							$final_url = $local;
							$sideloaded = true;
							update_post_meta( $wc_id, '_amphp_ai_image_url', esc_url_raw( $local ) );
						}
					}
				} catch ( \Throwable $e ) {
					// keep remote URL
				}
			}
		}

		// Persist into scraped JSON if this is a scraper product
		try {
			$loc = self::find_raw_scraped_product( $id );
			if ( $loc && is_array( $loc['raw'] ?? null ) ) {
				$raw = $loc['raw'];
				$raw['ai_image'] = $best;
				$raw['ai_image_url'] = $best;
				$raw['image'] = $best;
				if ( empty( $raw['images'] ) || ! is_array( $raw['images'] ) ) {
					$raw['images'] = array( $best );
				} else {
					array_unshift( $raw['images'], $best );
					$raw['images'] = array_values( array_unique( $raw['images'] ) );
				}
				$raw['image_source'] = 'ai_web';
				self::save_raw_scraped_product( $loc, $raw );
			}
		} catch ( \Throwable $e ) {
			// ignore JSON write errors
		}

		delete_transient( 'scraper_shop_cached_products' );

		return array(
			'ok'         => true,
			'id'         => $id,
			'wc_id'      => $wc_id,
			'title'      => $title,
			'image'      => $final_url,
			'remote'     => $best,
			'sideloaded' => $sideloaded,
			'attachment' => $att_id,
			'candidates' => count( $candidates ),
			'message'    => 'تصویر پیدا و ذخیره شد.',
		);
	}

	/**
	 * @param string $url
	 * @return string
	 */
	public static function normalize_image_url( $url ) {
		$url = trim( html_entity_decode( (string) $url ) );
		if ( $url === '' ) {
			return '';
		}
		// protocol-relative
		if ( strpos( $url, '//' ) === 0 ) {
			$url = 'https:' . $url;
		}
		if ( ! preg_match( '#^https?://#i', $url ) ) {
			return '';
		}
		// strip tracking junk
		$url = preg_replace( '/#.*$/', '', $url );
		return esc_url_raw( $url );
	}

	/**
	 * HEAD/GET check that URL returns an image content-type.
	 */
	public static function remote_url_looks_like_image( $url ) {
		$url = self::normalize_image_url( $url );
		if ( $url === '' || self::is_bad_product_image( $url ) ) {
			return false;
		}
		// Fast path: extension
		$path = (string) ( parse_url( $url, PHP_URL_PATH ) ?: '' );
		if ( preg_match( '/\.(jpe?g|png|webp|gif)(\?|$)/i', $path ) ) {
			// still lightly probe
		}
		$res = wp_remote_head( $url, array(
			'timeout'     => 6,
			'redirection' => 3,
			'user-agent'  => 'Mozilla/5.0 (compatible; AMPHP-ImageBot/13.3; +https://wordpress.org)',
			'headers'     => array( 'Accept' => 'image/*,*/*' ),
		) );
		if ( is_wp_error( $res ) ) {
			// try GET range
			$res = wp_remote_get( $url, array(
				'timeout'     => 8,
				'redirection' => 3,
				'user-agent'  => 'Mozilla/5.0 (compatible; AMPHP-ImageBot/13.3)',
				'headers'     => array( 'Range' => 'bytes=0-2048', 'Accept' => 'image/*' ),
			) );
			if ( is_wp_error( $res ) ) {
				return false;
			}
		}
		$code = wp_remote_retrieve_response_code( $res );
		if ( $code >= 400 ) {
			return false;
		}
		$ct = strtolower( (string) wp_remote_retrieve_header( $res, 'content-type' ) );
		if ( $ct && strpos( $ct, 'image/' ) === false && strpos( $ct, 'octet-stream' ) === false ) {
			// allow empty content-type with image extension
			if ( ! preg_match( '/\.(jpe?g|png|webp|gif)(\?|$)/i', $path ) ) {
				return false;
			}
		}
		return true;
	}

	/**
	 * Search the open web for product image candidates (DuckDuckGo / Bing / Wikimedia).
	 *
	 * @return array list of [url, title, source]
	 */
	public static function search_product_image_candidates( $title, $category = '' ) {
		$settings = self::get_settings();
		$q = trim( $title . ( $category ? ' ' . $category : '' ) . ' محصول' );
		$q = preg_replace( '/\s+/u', ' ', $q );
		$out = array();
		$seen = array();

		$add = function( $url, $t = '', $src = '' ) use ( &$out, &$seen ) {
			$url = self::normalize_image_url( $url );
			if ( $url === '' || self::is_bad_product_image( $url ) ) {
				return;
			}
			if ( preg_match( '/\/(logo|icon|sprite|favicon|banner)s?\//i', $url ) ) {
				return;
			}
			// skip google UI chrome
			if ( preg_match( '/google\.(com|co\.[a-z]+)\/(logos|images\/branding|gen_204)/i', $url ) ) {
				return;
			}
			$key = md5( preg_replace( '/\?.*$/', '', $url ) );
			if ( isset( $seen[ $key ] ) ) {
				return;
			}
			$seen[ $key ] = true;
			$out[] = array( 'url' => $url, 'title' => (string) $t, 'source' => (string) $src );
		};

		$ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36';
		$hdrs = array(
			'Accept-Language' => 'fa-IR,fa;q=0.9,en-US;q=0.8,en;q=0.7',
			'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
			'Cache-Control'   => 'no-cache',
		);

		/* ---- 1) Google Custom Search API (optional keys) — most reliable ---- */
		$gkey = trim( (string) ( $settings['google_cse_api_key'] ?? '' ) );
		$gcx  = trim( (string) ( $settings['google_cse_cx'] ?? '' ) );
		if ( $gkey !== '' && $gcx !== '' ) {
			try {
				$api = add_query_arg( array(
					'key'        => $gkey,
					'cx'         => $gcx,
					'q'          => $q,
					'searchType' => 'image',
					'num'        => 10,
					'safe'       => 'active',
					'imgType'    => 'photo',
					'hl'         => 'fa',
				), 'https://www.googleapis.com/customsearch/v1' );
				$res = wp_remote_get( $api, array(
					'timeout'    => 12,
					'user-agent' => 'AMPHP-Storefront/13.3',
					'headers'    => array( 'Accept' => 'application/json' ),
				) );
				if ( ! is_wp_error( $res ) && wp_remote_retrieve_response_code( $res ) < 400 ) {
					$data = json_decode( (string) wp_remote_retrieve_body( $res ), true );
					foreach ( (array) ( $data['items'] ?? array() ) as $item ) {
						$u = $item['link'] ?? '';
						if ( empty( $u ) && ! empty( $item['image']['contextLink'] ) ) {
							/* prefer direct image link */
						}
						$add( $u, $item['title'] ?? $title, 'google_cse' );
						if ( ! empty( $item['image']['thumbnailLink'] ) ) {
							/* thumb only as last resort — skip small thumbs */
						}
					}
				}
			} catch ( \Throwable $e ) {}
		}

		/* ---- 2) Google Images HTML (tbm=isch) — no API key ---- */
		if ( ! empty( $settings['enable_google_image_search'] ) && count( $out ) < 8 ) {
			try {
				$gurl = 'https://www.google.com/search?' . http_build_query( array(
					'tbm' => 'isch',
					'q'   => $q,
					'hl'  => 'fa',
					'safe'=> 'active',
					'ibs' => 'ft',
				) );
				$res = wp_remote_get( $gurl, array(
					'timeout'     => 14,
					'redirection' => 3,
					'user-agent'  => $ua,
					'headers'     => $hdrs,
				) );
				if ( ! is_wp_error( $res ) && wp_remote_retrieve_response_code( $res ) < 400 ) {
					$body = (string) wp_remote_retrieve_body( $res );
					self::parse_google_images_html( $body, $add, $title );
				}
				/* fallback endpoint sometimes used by clients */
				if ( count( $out ) < 3 ) {
					$gurl2 = 'https://www.google.com/search?' . http_build_query( array(
						'tbm'  => 'isch',
						'q'    => $q,
						'hl'   => 'en',
						'safe' => 'active',
						'tbs'  => 'itp:photo,isz:m',
					) );
					$res2 = wp_remote_get( $gurl2, array(
						'timeout'    => 12,
						'user-agent' => $ua,
						'headers'    => $hdrs,
					) );
					if ( ! is_wp_error( $res2 ) && wp_remote_retrieve_response_code( $res2 ) < 400 ) {
						self::parse_google_images_html( (string) wp_remote_retrieve_body( $res2 ), $add, $title );
					}
				}
			} catch ( \Throwable $e ) {}
		}

		/* ---- 3) Bing image search HTML ---- */
		if ( count( $out ) < 6 ) {
			try {
				$bing_url = 'https://www.bing.com/images/search?q=' . rawurlencode( $q ) . '&qft=+filterui:photo-photo&form=IRFLTR';
				$res = wp_remote_get( $bing_url, array(
					'timeout'    => 12,
					'user-agent' => $ua,
					'headers'    => $hdrs,
				) );
				if ( ! is_wp_error( $res ) && wp_remote_retrieve_response_code( $res ) < 400 ) {
					$body = (string) wp_remote_retrieve_body( $res );
					if ( preg_match_all( '/murl&quot;:&quot;(https?:\\\\\/\\\\\/[^&]+)&quot;/', $body, $mm ) ) {
						foreach ( $mm[1] as $raw ) {
							$u = str_replace( array( '\\/', '\\u0026' ), array( '/', '&' ), $raw );
							$u = html_entity_decode( urldecode( $u ) );
							$add( $u, $title, 'bing' );
							if ( count( $out ) >= 14 ) {
								break;
							}
						}
					}
					if ( count( $out ) < 8 && preg_match_all( '/"murl":"(https?:\\\\\/\\\\\/[^"]+)"/', $body, $mm2 ) ) {
						foreach ( $mm2[1] as $raw ) {
							$u = str_replace( '\\/', '/', stripcslashes( $raw ) );
							$add( $u, $title, 'bing' );
							if ( count( $out ) >= 14 ) {
								break;
							}
						}
					}
					if ( count( $out ) < 8 && preg_match_all( '/mediaurl=([^&"]+)/i', $body, $mm3 ) ) {
						foreach ( $mm3[1] as $enc ) {
							$add( urldecode( $enc ), $title, 'bing' );
							if ( count( $out ) >= 14 ) {
								break;
							}
						}
					}
				}
			} catch ( \Throwable $e ) {}
		}

		/* ---- 4) DuckDuckGo HTML ---- */
		if ( count( $out ) < 4 ) {
			try {
				$ddg_url = 'https://duckduckgo.com/html/?q=' . rawurlencode( $q . ' filetype:jpg' );
				$res = wp_remote_get( $ddg_url, array(
					'timeout'    => 10,
					'user-agent' => 'Mozilla/5.0 (compatible; AMPHP/13.3)',
				) );
				if ( ! is_wp_error( $res ) ) {
					$body = (string) wp_remote_retrieve_body( $res );
					if ( preg_match_all( '/uddg=([^&"]+)/', $body, $mm ) ) {
						foreach ( $mm[1] as $enc ) {
							$u = urldecode( $enc );
							if ( preg_match( '/\.(jpe?g|png|webp)/i', $u ) ) {
								$add( $u, $title, 'ddg' );
							}
							if ( count( $out ) >= 12 ) {
								break;
							}
						}
					}
				}
			} catch ( \Throwable $e ) {}
		}

		/* ---- 5) Wikimedia Commons ---- */
		if ( count( $out ) < 6 ) {
			try {
				$api = add_query_arg( array(
					'action'       => 'query',
					'format'       => 'json',
					'generator'    => 'search',
					'gsrsearch'    => $title,
					'gsrlimit'     => 8,
					'gsrnamespace' => 6,
					'prop'         => 'imageinfo',
					'iiprop'       => 'url|mime|size',
					'iiurlwidth'   => 800,
				), 'https://commons.wikimedia.org/w/api.php' );
				$res = wp_remote_get( $api, array( 'timeout' => 10, 'user-agent' => 'AMPHP-Storefront/13.3' ) );
				if ( ! is_wp_error( $res ) ) {
					$data = json_decode( (string) wp_remote_retrieve_body( $res ), true );
					foreach ( (array) ( $data['query']['pages'] ?? array() ) as $pg ) {
						$info = $pg['imageinfo'][0] ?? null;
						if ( ! $info ) {
							continue;
						}
						$mime = (string) ( $info['mime'] ?? '' );
						if ( $mime && strpos( $mime, 'image/' ) !== 0 ) {
							continue;
						}
						$add( $info['thumburl'] ?? $info['url'] ?? '', $pg['title'] ?? $title, 'wikimedia' );
					}
				}
			} catch ( \Throwable $e ) {}
		}

		/* ---- 6) Openverse ---- */
		if ( count( $out ) < 8 ) {
			try {
				$ov = add_query_arg( array(
					'q'         => $title,
					'page_size' => 8,
					'license'   => 'commercial',
				), 'https://api.openverse.org/v1/images/' );
				$res = wp_remote_get( $ov, array(
					'timeout'    => 10,
					'user-agent' => 'AMPHP-Storefront/13.3',
					'headers'    => array( 'Accept' => 'application/json' ),
				) );
				if ( ! is_wp_error( $res ) && wp_remote_retrieve_response_code( $res ) < 400 ) {
					$data = json_decode( (string) wp_remote_retrieve_body( $res ), true );
					foreach ( (array) ( $data['results'] ?? array() ) as $row ) {
						$add( $row['url'] ?? $row['thumbnail'] ?? '', $row['title'] ?? $title, 'openverse' );
					}
				}
			} catch ( \Throwable $e ) {}
		}

		/* Prefer google sources first in list */
		usort( $out, function( $a, $b ) {
			$rank = array( 'google_cse' => 0, 'google' => 1, 'bing' => 2, 'ddg' => 3, 'openverse' => 4, 'wikimedia' => 5 );
			$ra = $rank[ $a['source'] ?? '' ] ?? 9;
			$rb = $rank[ $b['source'] ?? '' ] ?? 9;
			return $ra <=> $rb;
		} );

		return array_slice( $out, 0, 14 );
	}

	/**
	 * Extract original image URLs from Google Images HTML / embedded JSON.
	 *
	 * @param string   $body
	 * @param callable $add function($url,$title,$src)
	 * @param string   $title
	 */
	public static function parse_google_images_html( $body, $add, $title = '' ) {
		if ( ! is_callable( $add ) || $body === '' ) {
			return;
		}
		/* Classic: "ou":"https://..." original url in AF_initData / scripts */
		if ( preg_match_all( '/"ou"\s*:\s*"(https?:\\\\\/\\\\\/[^"]+)"/', $body, $m ) ) {
			foreach ( $m[1] as $raw ) {
				$u = stripcslashes( $raw );
				$u = str_replace( '\\/', '/', $u );
				$add( $u, $title, 'google' );
			}
		}
		if ( preg_match_all( '/"ou":"(https?:\\\\\/\\\\\/[^"]+)"/', $body, $m2 ) ) {
			foreach ( $m2[1] as $raw ) {
				$u = str_replace( '\\/', '/', stripcslashes( $raw ) );
				$add( $u, $title, 'google' );
			}
		}
		/* ["http…jpg", width, height] patterns in side-channel data */
		if ( preg_match_all( '/\["(https?:\\\\\/\\\\\/[^"]+\.(?:jpg|jpeg|png|webp)(?:\?[^"]*)?)"\s*,\s*\d+\s*,\s*\d+\]/i', $body, $m3 ) ) {
			foreach ( $m3[1] as $raw ) {
				$u = str_replace( '\\/', '/', stripcslashes( $raw ) );
				$add( $u, $title, 'google' );
			}
		}
		/* imgurl= query param */
		if ( preg_match_all( '/(?:imgurl|imgrefurl)=([^&"\'<>\s]+)/i', $body, $m4 ) ) {
			foreach ( $m4[1] as $enc ) {
				$u = urldecode( html_entity_decode( $enc ) );
				if ( preg_match( '/^https?:\/\//i', $u ) && preg_match( '/\.(jpe?g|png|webp)/i', $u ) ) {
					$add( $u, $title, 'google' );
				}
			}
		}
		/* data-src / unescaped https images in JSON blobs */
		if ( preg_match_all( '/https?:\/\/[^"\'\\\s<>]+\.(?:jpg|jpeg|png|webp)(?:\?[^"\'\\\s<>]*)?/i', $body, $m5 ) ) {
			$n = 0;
			foreach ( $m5[0] as $u ) {
				if ( preg_match( '/encrypted-tbn|gstatic\.com\/images|googleusercontent\.com\/\d/i', $u ) ) {
					continue; /* skip google thumbnails when possible — prefer originals from ou */
				}
				$add( $u, $title, 'google' );
				if ( ++$n >= 10 ) {
					break;
				}
			}
		}
	}

	/**
	 * Use master AI to choose the best product photo URL from candidates.
	 *
	 * @param string $title
	 * @param string $category
	 * @param array  $candidates
	 * @param array  $settings
	 * @return string URL or empty
	 */
	public static function ai_pick_best_product_image( $title, $category, $candidates, $settings ) {
		if ( empty( $candidates ) ) {
			return '';
		}
		// If only 1–2, skip AI cost
		if ( count( $candidates ) <= 2 ) {
			return (string) ( $candidates[0]['url'] ?? '' );
		}
		try {
			$master = self::get_scraper_master_ai_model( $settings );
			if ( empty( $master ) || empty( $master['api_key'] ) && empty( $master['endpoint'] ) ) {
				// still try; call_ai_api may resolve keys
			}
			$list = '';
			foreach ( array_slice( $candidates, 0, 8 ) as $i => $c ) {
				$list .= ( $i + 1 ) . ') ' . ( $c['url'] ?? '' ) . "\n";
			}
			$prompt = "تو دستیار انتخاب تصویر محصول برای فروشگاه اینترنتی هستی.\n"
				. "نام محصول: «{$title}»\n"
				. ( $category ? "دسته: «{$category}»\n" : '' )
				. "از بین URLهای زیر، فقط یک URL را برگردان که بهترین عکس واقعی همان کالا باشد (نه لوگو، نه بنر تبلیغاتی، نه تصویر نامرتبط).\n"
				. "فقط خود URL را در یک خط چاپ کن، بدون توضیح.\n\n"
				. $list;
			$reply = self::call_ai_api( is_array( $master ) ? $master : array(), $prompt, 'system', $settings );
			$reply = trim( (string) $reply );
			// extract first URL
			if ( preg_match( '#https?://[^\s\"\'<>]+#i', $reply, $m ) ) {
				$u = self::normalize_image_url( $m[0] );
				// must be one of candidates (or close)
				foreach ( $candidates as $c ) {
					$cu = self::normalize_image_url( $c['url'] ?? '' );
					if ( $cu && ( $cu === $u || strpos( $u, preg_replace( '/\?.*$/', '', $cu ) ) === 0 || strpos( $cu, preg_replace( '/\?.*$/', '', $u ) ) === 0 ) ) {
						return $cu;
					}
				}
				// AI invented URL — only accept if looks like image
				if ( $u && self::remote_url_looks_like_image( $u ) ) {
					return $u;
				}
			}
		} catch ( \Throwable $e ) {
			// fall through
		}
		return (string) ( $candidates[0]['url'] ?? '' );
	}

	private static function attach_external_image( $post_id, $image_url, $title ) {
		if ( ! function_exists( 'media_sideload_image' ) ) {
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		$att_id = media_sideload_image( $image_url, $post_id, $title, 'id' );
		if ( ! is_wp_error( $att_id ) && $att_id > 0 ) {
			set_post_thumbnail( $post_id, $att_id );
		}
	}

	/**
	 * Handle AJAX synchronization request.
	 */
	public static function ajax_sync_to_woo() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		/* bump time limit — per-product direct sync can be long */
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 0 );
		}
		@ignore_user_abort( true );

		$result = self::sync_to_woocommerce();
		if ( ! empty( $result['ok'] ) ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( is_array( $result ) ? $result : array( 'message' => (string) $result ) );
		}
	}

	/**
	 * v13.2 / v10.103: live progress of direct Woo sync (product cards).
	 */
	public static function ajax_direct_sync_progress() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}
		$p = self::get_direct_sync_progress();
		wp_send_json_success( is_array( $p ) ? $p : array() );
	}

	/**
	 * Load connections configuration from scraper's connections.json.
	 *
	 * @return array
	 */

	/**
	 * v10.99: تست اتصال مستقیم ووکامرس از داخل ادمین وردپرس (همیشه in-process).
	 */
	public static function ajax_test_woo_direct() {
		check_ajax_referer( 'scraper_woo_bridge', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز.' ), 403 );
		}

		$has_wc = class_exists( 'WooCommerce' ) || function_exists( 'wc_get_product' ) || class_exists( 'WC_Product_Simple' );
		$env    = array(
			'in_wp'      => true,
			'has_wc'     => (bool) $has_wc,
			'wc_version' => defined( 'WC_VERSION' ) ? WC_VERSION : '',
			'php'        => PHP_VERSION,
			'blog'       => function_exists( 'home_url' ) ? home_url( '/' ) : '',
		);

		if ( ! $has_wc ) {
			wp_send_json_success( array(
				'ok'      => false,
				'message' => 'ووکامرس فعال نیست',
				'detail'  => 'افزونه WooCommerce را نصب و فعال کنید؛ اتصال مستقیم فقط وقتی WC در همین PHP process باشد کار می‌کند.',
				'hint'    => 'Plugins → Add New → WooCommerce',
				'env'     => $env,
				'direct'  => array( 'ok' => false, 'message' => 'WooCommerce missing', 'via' => 'direct' ),
			) );
		}

		// Prefer scraper4 helpers when loadable under SCRAPER4_NO_RENDER.
		self::load_scraper_ai_engine();
		$result = null;
		if ( function_exists( 'wooTestConnection' ) ) {
			$conn = self::get_scraper_connections();
			$w    = is_array( $conn['woocommerce'] ?? null ) ? $conn['woocommerce'] : array();
			$home = function_exists( 'home_url' ) ? rtrim( (string) home_url( '/' ), '/' ) : '';
			$result = wooTestConnection( array(
				'store_url'       => $home !== '' ? $home : (string) ( $w['store_url'] ?? '' ),
				'consumer_key'    => (string) ( $w['consumer_key'] ?? '' ),
				'consumer_secret' => (string) ( $w['consumer_secret'] ?? '' ),
				'mode'            => 'direct',
			) );
		} else {
			$ok     = false;
			$detail = '';
			$pid    = 0;
			try {
				$product = new WC_Product_Simple();
				$product->set_name( 'AMPHP admin connection ping' );
				$product->set_status( 'draft' );
				$product->set_catalog_visibility( 'hidden' );
				$product->set_regular_price( '1000' );
				$sku = 'amphp-admin-' . substr( md5( uniqid( (string) mt_rand(), true ) ), 0, 8 );
				$product->set_sku( $sku );
				$pid = (int) $product->save();
				$ok  = $pid > 0;
				if ( $pid > 0 && function_exists( 'wp_delete_post' ) ) {
					wp_delete_post( $pid, true );
				}
				$detail = $ok
					? ( 'پیش‌نویس #' . $pid . ' (SKU ' . $sku . ') ساخته و پاک شد' )
					: 'WC_Product_Simple::save() مقدار خالی برگرداند';
			} catch ( \Throwable $e ) {
				$detail = $e->getMessage();
			}
			$result = array(
				'ok'      => $ok,
				'message' => $ok ? 'اتصال مستقیم موفق (از پنل ادمین)' : 'اتصال مستقیم ناموفق',
				'detail'  => $detail,
				'env'     => $env,
				'direct'  => array(
					'ok'      => $ok,
					'message' => $ok ? 'OK' : 'FAIL',
					'detail'  => $detail,
					'via'     => 'direct',
					'product' => $pid,
				),
			);
		}

		if ( is_array( $result ) && empty( $result['env'] ) ) {
			$result['env'] = $env;
		}
		wp_send_json_success( $result );
	}

	/**
	 * v10.99: فعال‌سازی همگام مستقیم در connections.json از پنل ادمین.
	 * اسکراپر خارج از WP نمی‌تواند direct را اجرا کند — این دکمه مسیر ادمین را روشن می‌کند.
	 */
	public static function ajax_enable_woo_direct() {
		check_ajax_referer( 'scraper_woo_bridge', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز.' ), 403 );
		}

		$has_wc = class_exists( 'WooCommerce' ) || function_exists( 'wc_get_product' );
		$file   = plugin_dir_path( __FILE__ ) . 'connections.json';
		// Prefer existing path used by get_scraper_connections
		$locations = array(
			dirname( __FILE__ ) . '/connections.json',
			plugin_dir_path( __FILE__ ) . 'connections.json',
		);
		$existing = '';
		foreach ( $locations as $loc ) {
			if ( file_exists( $loc ) ) {
				$existing = $loc;
				break;
			}
		}
		if ( $existing === '' ) {
			$existing = $file;
		}

		$data = array();
		if ( is_readable( $existing ) ) {
			$raw = @file_get_contents( $existing );
			$d   = @json_decode( (string) $raw, true );
			if ( is_array( $d ) ) {
				$data = $d;
			}
		}

		$w = is_array( $data['woocommerce'] ?? null ) ? $data['woocommerce'] : array();
		$w['enabled']   = 1;
		$w['sync_mode'] = 'direct';
		$fb = isset( $_POST['fallback'] ) ? sanitize_text_field( wp_unslash( $_POST['fallback'] ) ) : 'direct_then_api';
		if ( ! in_array( $fb, array( 'api_then_direct', 'direct_then_api', 'none' ), true ) ) {
			$fb = 'direct_then_api';
		}
		$w['sync_fallback'] = $fb;
		if ( empty( $w['store_url'] ) && function_exists( 'home_url' ) ) {
			$w['store_url'] = rtrim( (string) home_url( '/' ), '/' );
		}
		// Keep keys if already set; do not wipe secrets.
		$data['woocommerce'] = $w;

		$json = wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		$ok   = false;
		if ( function_exists( 'WP_Filesystem' ) ) {
			// best-effort plain write
		}
		$ok = (bool) @file_put_contents( $existing, $json . "\n", LOCK_EX );

		if ( ! $ok ) {
			wp_send_json_error( array(
				'message' => 'نوشتن connections.json ناموفق — مجوز نوشتن پوشه پلاگین را بررسی کنید',
				'file'    => $existing,
			) );
		}

		wp_send_json_success( array(
			'ok'          => true,
			'message'     => 'همگام مستقیم فعال شد' . ( $has_wc ? '' : ' (توجه: ووکامرس هنوز فعال نیست)' ),
			'sync_mode'   => 'direct',
			'fallback'    => $fb,
			'store_url'   => $w['store_url'] ?? '',
			'file'        => basename( $existing ),
			'path'        => $existing,
			'has_wc'      => (bool) $has_wc,
			'woocommerce' => array(
				'enabled'       => 1,
				'sync_mode'     => 'direct',
				'sync_fallback' => $fb,
				'store_url'     => $w['store_url'] ?? '',
			),
		) );
	}

	/**
	 * v13.3.8: ذخیرهٔ فونت سراسری در connections.json (منبع حقیقت اسکرپر + ویترین).
	 *
	 * @param string $font_key
	 * @return bool
	 */
	public static function sync_ui_font_to_connections( $font_key ) {
		$font_key = sanitize_key( (string) $font_key );
		if ( $font_key === '' ) {
			return false;
		}
		$locations = array(
			dirname( __FILE__ ) . '/connections.json',
			plugin_dir_path( __FILE__ ) . 'connections.json',
			defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR . '/uploads/connections.json' : '',
		);
		$written = false;
		foreach ( $locations as $loc ) {
			if ( empty( $loc ) ) {
				continue;
			}
			$data = array();
			if ( file_exists( $loc ) ) {
				$raw = @file_get_contents( $loc );
				$decoded = @json_decode( (string) $raw, true );
				if ( is_array( $decoded ) ) {
					$data = $decoded;
				}
			}
			$data['ui_font']  = $font_key;
			$data['app_font'] = $font_key;
			$data['ui_font_at'] = time();
			$json = wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
			if ( false === $json ) {
				continue;
			}
			$dir = dirname( $loc );
			if ( ! is_dir( $dir ) ) {
				@wp_mkdir_p( $dir );
			}
			if ( @file_put_contents( $loc, $json ) !== false ) {
				$written = true;
				break;
			}
		}
		return $written;
	}

	public static function get_scraper_connections() {
		$locations = array(
			dirname( __FILE__ ) . '/connections.json',
			plugin_dir_path( __FILE__ ) . 'connections.json',
			defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR . '/uploads/connections.json' : '',
		);

		foreach ( $locations as $loc ) {
			if ( ! empty( $loc ) && file_exists( $loc ) ) {
				$json = @file_get_contents( $loc );
				if ( ! empty( $json ) ) {
					$data = @json_decode( $json, true );
					if ( is_array( $data ) ) {
						return $data;
					}
				}
			}
		}

		return array();
	}

	/**
	 * Determine active messengers based on scraper's connections.json and admin settings.
	 *
	 * @param array|null $settings
	 * @return array
	 */
	public static function get_active_messengers( $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}

		$cn = self::get_scraper_connections();
		$active = array();

		// 1. Bale (بله)
		$bale_token = ! empty( $settings['bale_token'] ) ? trim( $settings['bale_token'] ) : ( $cn['baleh']['token'] ?? $cn['bale']['token'] ?? '' );
		$bale_chat  = ! empty( $settings['bale_chat_id'] ) ? trim( $settings['bale_chat_id'] ) : ( $cn['baleh']['chat_id'] ?? $cn['bale']['chat_id'] ?? '' );
		if ( ! empty( $bale_token ) && ! empty( $bale_chat ) ) {
			$source = ! empty( $settings['bale_token'] ) ? 'admin_settings' : 'scraper_connections';
			$active['bale'] = array(
				'title'    => 'پیام‌رسان بله (Bale)',
				'token'    => $bale_token,
				'chat_id'  => $bale_chat,
				'source'   => $source,
				'api_base' => 'https://tapi.bale.ai/bot' . $bale_token . '/',
				'url'      => 'https://tapi.bale.ai/bot' . $bale_token . '/sendMessage',
			);
		}

		// 2. Telegram (تلگرام)
		$tele_token = ! empty( $settings['telegram_token'] ) ? trim( $settings['telegram_token'] ) : ( $cn['telegram']['token'] ?? '' );
		$tele_chat  = ! empty( $settings['telegram_chat_id'] ) ? trim( $settings['telegram_chat_id'] ) : ( $cn['telegram']['chat_id'] ?? '' );
		if ( ! empty( $tele_token ) && ! empty( $tele_chat ) ) {
			$source = ! empty( $settings['telegram_token'] ) ? 'admin_settings' : 'scraper_connections';
			$active['telegram'] = array(
				'title'    => 'تلگرام (Telegram)',
				'token'    => $tele_token,
				'chat_id'  => $tele_chat,
				'source'   => $source,
				'api_base' => 'https://api.telegram.org/bot' . $tele_token . '/',
				'url'      => 'https://api.telegram.org/bot' . $tele_token . '/sendMessage',
			);
		}

		// 3. Rubika (روبیکا)
		$rubi_token = ! empty( $settings['rubika_token'] ) ? trim( $settings['rubika_token'] ) : ( $cn['rubika']['token'] ?? '' );
		$rubi_chat  = ! empty( $settings['rubika_chat_id'] ) ? trim( $settings['rubika_chat_id'] ) : ( $cn['rubika']['chat_id'] ?? '' );
		if ( ! empty( $rubi_token ) && ! empty( $rubi_chat ) ) {
			$source = ! empty( $settings['rubika_token'] ) ? 'admin_settings' : 'scraper_connections';
			$active['rubika'] = array(
				'title'    => 'روبیکا (Rubika)',
				'token'    => $rubi_token,
				'chat_id'  => $rubi_chat,
				'source'   => $source,
				'api_base' => 'https://api.rubika.ir/v1/bot' . $rubi_token . '/',
				'url'      => 'https://api.rubika.ir/v1/bot' . $rubi_token . '/sendMessage',
			);
		}

		return $active;
	}

	/**
	 * Send formatted text message to all active messengers.
	 *
	 * @param string $message_text
	 * @param array|null $settings
	 * @return array
	 */
	public static function send_message_to_messengers( $message_text, $settings = null, $media = array() ) {
		$messengers = self::get_active_messengers( $settings );
		if ( empty( $messengers ) ) {
			return array(
				'ok'      => false,
				'sent'    => 0,
				'total'   => 0,
				'message' => 'هیچ پیام‌رسانی در تنظیمات افزونه یا بخش اعلان‌های اسکرپر (connections.json) فعال نشده است.',
				'details' => array(),
			);
		}

		$sent_count = 0;
		$details    = array();
		$want_preview = ! empty( $media ) || (bool) preg_match( '~https?://\S+~u', (string) $message_text );

		foreach ( $messengers as $key => $m ) {
			$body = array(
				'chat_id' => $m['chat_id'],
				'text'    => $message_text,
			);
			if ( 'telegram' === $key || 'bale' === $key ) {
				$body['disable_web_page_preview'] = ! $want_preview;
			}

			$args = array(
				'method'      => 'POST',
				'timeout'     => 8,
				'redirection' => 2,
				'httpversion' => '1.0',
				'blocking'    => false, // Non-blocking so customer chat is never delayed!
				'headers'     => array(
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'body'        => wp_json_encode( $body ),
				'sslverify'   => false,
			);

			$response = wp_remote_post( $m['url'], $args );

			if ( is_wp_error( $response ) ) {
				$details[ $key ] = array(
					'title' => $m['title'],
					'ok'    => false,
					'error' => $response->get_error_message(),
				);
			} else {
				$code      = wp_remote_retrieve_response_code( $response );
				$resp_body = wp_remote_retrieve_body( $response );
				$json      = @json_decode( $resp_body, true );
				$is_success = ( $code >= 200 && $code < 300 ) && ( ! isset( $json['ok'] ) || true === $json['ok'] );

				if ( $is_success || 0 === $code ) {
					// blocking=false may yield empty code — count as queued/sent
					$sent_count++;
					$details[ $key ] = array(
						'title' => $m['title'],
						'ok'    => true,
						'code'  => $code,
					);
				} else {
					$err_msg = 'HTTP ' . $code;
					if ( is_array( $json ) && ! empty( $json['description'] ) ) {
						$err_msg .= ': ' . $json['description'];
					}
					$details[ $key ] = array(
						'title' => $m['title'],
						'ok'    => false,
						'error' => $err_msg,
					);
				}
			}

			// v13.3.19: فوروارد چندرسانه‌ای (عکس/ویدیو/گیف/فایل/لینک)
			if ( ! empty( $media ) && is_array( $media ) ) {
				foreach ( array_slice( $media, 0, 8 ) as $it ) {
					if ( ! is_array( $it ) ) {
						continue;
					}
					self::send_media_to_messenger( $key, $m, $it );
				}
			}
		}

		$is_ok = ( $sent_count > 0 );
		$summary = $is_ok
			? "پیام با موفقیت به {$sent_count} پیام‌رسان ارسال شد."
			: 'خطا در ارسال پیام به پیام‌رسان‌ها.';

		return array(
			'ok'      => $is_ok,
			'sent'    => $sent_count,
			'total'   => count( $messengers ),
			'message' => $summary,
			'details' => $details,
		);
	}

	/**
	 * v13.3.19: ارسال یک آیتم رسانه به یک پیام‌رسان.
	 * $item = [ kind => photo|video|animation|audio|document|link, url, caption, name ]
	 */
	public static function send_media_to_messenger( $key, $m, $item ) {
		$kind = strtolower( trim( (string) ( $item['kind'] ?? 'document' ) ) );
		$url  = trim( (string) ( $item['url'] ?? '' ) );
		$cap  = trim( (string) ( $item['caption'] ?? '' ) );
		$name = trim( (string) ( $item['name'] ?? '' ) );
		if ( $url === '' ) {
			return array( 'ok' => false, 'error' => 'empty url' );
		}
		$api_base = (string) ( $m['api_base'] ?? '' );
		$chat_id  = $m['chat_id'];

		if ( 'link' === $kind || 'rubika' === $key || $api_base === '' ) {
			$label = array(
				'photo'     => '🖼 تصویر',
				'video'     => '🎬 ویدیو',
				'animation' => '🎞 گیف',
				'audio'     => '🎧 صوت',
				'document'  => '📎 فایل',
				'link'      => '🔗 لینک',
			);
			$txt = ( $label[ $kind ] ?? '📎' ) . ( $name !== '' ? ' (' . $name . ')' : '' ) . "\n" . $url;
			if ( $cap !== '' ) {
				$txt = mb_substr( $cap, 0, 300 ) . "\n" . $txt;
			}
			$body = array(
				'chat_id'                  => $chat_id,
				'text'                     => $txt,
				'disable_web_page_preview' => false,
			);
			$post_url = ! empty( $m['url'] ) ? $m['url'] : ( $api_base . 'sendMessage' );
			wp_remote_post(
				$post_url,
				array(
					'method'    => 'POST',
					'timeout'   => 8,
					'blocking'  => false,
					'headers'   => array( 'Content-Type' => 'application/json; charset=utf-8' ),
					'body'      => wp_json_encode( $body ),
					'sslverify' => false,
				)
			);
			return array( 'ok' => true );
		}

		$method = 'sendDocument';
		$field  = 'document';
		if ( 'photo' === $kind ) {
			$method = 'sendPhoto';
			$field  = 'photo';
		} elseif ( 'video' === $kind ) {
			$method = 'sendVideo';
			$field  = 'video';
		} elseif ( 'animation' === $kind ) {
			$method = 'sendAnimation';
			$field  = 'animation';
		} elseif ( 'audio' === $kind ) {
			$method = 'sendAudio';
			$field  = 'audio';
		}
		$body = array(
			'chat_id' => $chat_id,
			$field    => $url,
		);
		if ( $cap !== '' ) {
			$body['caption'] = mb_substr( $cap, 0, 1000 );
		}
		wp_remote_post(
			$api_base . $method,
			array(
				'method'    => 'POST',
				'timeout'   => 12,
				'blocking'  => false,
				'headers'   => array( 'Content-Type' => 'application/json; charset=utf-8' ),
				'body'      => wp_json_encode( $body ),
				'sslverify' => false,
			)
		);
		return array( 'ok' => true );
	}

	/**
	 * v13.3.19: تشخیص نوع رسانه از URL یا MIME.
	 */
	public static function media_kind_from_meta( $url, $mime = '', $name = '' ) {
		$mime = strtolower( (string) $mime );
		$path = strtolower( (string) ( wp_parse_url( $url, PHP_URL_PATH ) ?: $name ) );
		if ( strpos( $mime, 'image/gif' ) === 0 || preg_match( '~\.gif(\?|$)~', $path ) ) {
			return 'animation';
		}
		if ( strpos( $mime, 'image/' ) === 0 || preg_match( '~\.(jpe?g|png|webp|bmp)(\?|$)~', $path ) ) {
			return 'photo';
		}
		if ( strpos( $mime, 'video/' ) === 0 || preg_match( '~\.(mp4|webm|mov|m4v|avi|mkv)(\?|$)~', $path ) ) {
			return 'video';
		}
		if ( strpos( $mime, 'audio/' ) === 0 || preg_match( '~\.(mp3|ogg|wav|m4a|aac)(\?|$)~', $path ) ) {
			return 'audio';
		}
		if ( preg_match( '~^https?://~i', (string) $url ) && ! preg_match( '~\.(jpe?g|png|gif|webp|mp4|webm|pdf|zip|rar)(\?|$)~i', $path ) ) {
			return 'link';
		}
		return 'document';
	}

	/**
	 * v13.3.19: ذخیره پیوست آپلود‌شدهٔ چت پشتیبانی و ساخت آیتم رسانه.
	 * @return array{ok:bool,item?:array,error?:string,path?:string,url?:string}
	 */
	public static function handle_support_chat_upload() {
		if ( empty( $_FILES['chat_file'] ) || ! is_array( $_FILES['chat_file'] ) ) {
			return array( 'ok' => false, 'error' => 'no_file' );
		}
		$f = $_FILES['chat_file'];
		if ( ! empty( $f['error'] ) && (int) $f['error'] !== UPLOAD_ERR_OK ) {
			return array( 'ok' => false, 'error' => 'upload_error_' . (int) $f['error'] );
		}
		$tmp  = (string) ( $f['tmp_name'] ?? '' );
		$name = sanitize_file_name( (string) ( $f['name'] ?? 'file.bin' ) );
		$size = (int) ( $f['size'] ?? 0 );
		$mime = (string) ( $f['type'] ?? '' );
		if ( $tmp === '' || ! is_uploaded_file( $tmp ) ) {
			return array( 'ok' => false, 'error' => 'invalid_tmp' );
		}
		if ( $size <= 0 || $size > 25 * 1024 * 1024 ) {
			return array( 'ok' => false, 'error' => 'size_limit' );
		}
		$allowed = array(
			'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp',
			'video/mp4', 'video/webm', 'video/quicktime',
			'audio/mpeg', 'audio/ogg', 'audio/wav', 'audio/mp4',
			'application/pdf', 'application/zip', 'application/x-zip-compressed',
			'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/octet-stream',
		);
		// sniff mime if empty
		if ( $mime === '' && function_exists( 'mime_content_type' ) ) {
			$mime = (string) @mime_content_type( $tmp );
		}
		$ext = strtolower( pathinfo( $name, PATHINFO_EXTENSION ) );
		$ok_ext = in_array( $ext, array( 'jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'mp4', 'webm', 'mov', 'mp3', 'ogg', 'wav', 'pdf', 'zip', 'doc', 'docx', 'rar', '7z', 'txt' ), true );
		if ( ! $ok_ext && $mime !== '' && ! in_array( $mime, $allowed, true ) ) {
			return array( 'ok' => false, 'error' => 'type_not_allowed' );
		}

		$upload = function_exists( 'wp_upload_dir' ) ? wp_upload_dir() : null;
		if ( ! is_array( $upload ) || empty( $upload['basedir'] ) ) {
			$dir = plugin_dir_path( __FILE__ ) . 'chat_uploads';
			$base_url = '';
		} else {
			$dir = trailingslashit( $upload['basedir'] ) . 'amphp-chat';
			$base_url = trailingslashit( $upload['baseurl'] ) . 'amphp-chat';
		}
		if ( ! is_dir( $dir ) ) {
			wp_mkdir_p( $dir );
		}
		$safe = time() . '_' . wp_generate_password( 8, false ) . ( $ext !== '' ? ( '.' . $ext ) : '' );
		$dest = trailingslashit( $dir ) . $safe;
		if ( ! @move_uploaded_file( $tmp, $dest ) ) {
			if ( ! @copy( $tmp, $dest ) ) {
				return array( 'ok' => false, 'error' => 'move_failed' );
			}
		}
		@chmod( $dest, 0644 );
		$file_url = '';
		if ( $base_url !== '' ) {
			$file_url = trailingslashit( $base_url ) . $safe;
		} elseif ( function_exists( 'plugins_url' ) ) {
			$file_url = plugins_url( 'chat_uploads/' . $safe, __FILE__ );
		}
		$kind = self::media_kind_from_meta( $file_url !== '' ? $file_url : $name, $mime, $name );
		$item = array(
			'kind'    => $kind,
			'url'     => $file_url,
			'caption' => '',
			'name'    => $name,
			'path'    => $dest,
			'mime'    => $mime,
			'size'    => $size,
		);
		return array( 'ok' => true, 'item' => $item, 'url' => $file_url, 'path' => $dest );
	}

	/**
	 * v13.3.19: استخراج لینک‌های داخل متن پیام مشتری به‌عنوان آیتم رسانه.
	 */
	public static function extract_links_as_media( $text ) {
		$items = array();
		if ( ! is_string( $text ) || $text === '' ) {
			return $items;
		}
		if ( preg_match_all( '~https?://[^\s<>"\']+~u', $text, $mm ) ) {
			foreach ( $mm[0] as $lnk ) {
				$lnk = rtrim( $lnk, '.,);]' );
				$kind = self::media_kind_from_meta( $lnk );
				$items[] = array(
					'kind'    => $kind,
					'url'     => $lnk,
					'caption' => '',
					'name'    => '',
				);
			}
		}
		return $items;
	}

	/**
	 * Alias for send_message_to_messengers (backward compatible name).
	 */
	public static function send_messenger_notification( $message_text, $settings = null ) {
		return self::send_message_to_messengers( $message_text, $settings );
	}

	public static function get_chat_threads() {
		$threads = get_option( 'scraper_chat_threads', false );
		if ( false === $threads || ! is_array( $threads ) ) {
			$file = plugin_dir_path( __FILE__ ) . 'chat_threads.json';
			if ( file_exists( $file ) ) {
				$threads = @json_decode( file_get_contents( $file ), true );
			}
		}
		return is_array( $threads ) ? $threads : array();
	}

	/**
	 * Save support chat threads (persisting the latest 150 conversations).
	 *
	 * @param array $threads
	 */
	public static function save_chat_threads( $threads ) {
		if ( ! is_array( $threads ) ) {
			$threads = array();
		}
		if ( count( $threads ) > 150 ) {
			$threads = array_slice( $threads, 0, 150, true );
		}
		update_option( 'scraper_chat_threads', $threads, false );
		$file = plugin_dir_path( __FILE__ ) . 'chat_threads.json';
		@file_put_contents( $file, wp_json_encode( $threads, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
	}

	/**
	 * AJAX endpoint for customer support chat submission.
	 * Supports continuous messenger thread, AI automatic answers, and persistent conversation.
	 */
	/**
	 * Retrieve Master AI model configured in scraper4.php / ai_votes.json / connections.json / ai_providers.json.
	 *
	 * @return array
	 */
		/**
	 * Build condensed live catalog context for AI grounding.
	 *
	 * @param int $limit
	 * @return string
	 */
	/**
	 * Full shop intelligence context for AI (products, orders, settings, analytics).
	 *
	 * @param int $limit max products in catalog section
	 * @return string
	 */
	public static function build_catalog_context_for_ai( $limit = 40 ) {
		$settings = self::get_settings();
		$products = self::get_all_scraped_products();
		$parts    = array();

		$shop_title = (string) ( $settings['shop_title'] ?? get_bloginfo( 'name' ) );
		$phone      = (string) ( $settings['contact_phone'] ?? '' );
		$hours      = (string) ( $settings['support_hours'] ?? '' );
		$threshold  = number_format( (float) ( $settings['free_shipping_threshold'] ?? 400000 ) );
		$currency   = (string) ( $settings['currency_symbol'] ?? 'تومان' );

		$parts[] = "=== اطلاعات فروشگاه ===";
		$parts[] = "نام: {$shop_title}";
		if ( $phone ) { $parts[] = "تلفن پشتیبانی: {$phone}"; }
		if ( $hours ) { $parts[] = "ساعات پاسخگویی: {$hours}"; }
		$parts[] = "ارسال رایگان برای خرید بالای {$threshold} {$currency}";
		$parts[] = "ضمانت: ۷ روز بازگشت وجه و تضمین اصالت کالا";
		$parts[] = "قالب ویترین: " . ( $settings['store_template'] ?? 'digikala' );

		// Categories summary
		$cats = array();
		foreach ( (array) $products as $p ) {
			$c = (string) ( $p['category'] ?? 'عمومی' );
			$cats[ $c ] = ( $cats[ $c ] ?? 0 ) + 1;
		}
		if ( $cats ) {
			$cat_bits = array();
			foreach ( $cats as $cn => $cc ) {
				$cat_bits[] = "{$cn} ({$cc})";
			}
			$parts[] = "دسته‌بندی‌ها: " . implode( '، ', array_slice( $cat_bits, 0, 20 ) );
		}
		$parts[] = "تعداد کل کالاهای ویترین: " . count( $products );

		// Products
		$parts[] = "\n=== کاتالوگ محصولات (برای پاسخ دقیق به قیمت/موجودی) ===";
		if ( empty( $products ) ) {
			$parts[] = 'کاتالوگ خالی است یا در حال به‌روزرسانی.';
		} else {
			$count = 0;
			foreach ( $products as $p ) {
				$count++;
				if ( $count > $limit ) {
					$parts[] = '… و ' . ( count( $products ) - $limit ) . ' کالای دیگر.';
					break;
				}
				$title = (string) ( $p['title'] ?? '' );
				$price_txt = ! empty( $p['price_formatted'] ) ? $p['price_formatted'] : ( number_format( (float) ( $p['price'] ?? 0 ) ) . ' ' . $currency );
				$line = "• [{$count}] «{$title}» | قیمت: {$price_txt}";
				if ( ! empty( $p['category'] ) ) {
					$line .= ' | دسته: ' . $p['category'];
				}
				if ( ! empty( $p['has_discount'] ) && ! empty( $p['discount_pct'] ) ) {
					$line .= ' | تخفیف: ' . $p['discount_pct'] . '٪';
					if ( ! empty( $p['old_price_formatted'] ) ) {
						$line .= ' (قبل: ' . $p['old_price_formatted'] . ')';
					}
				}
				$stock = ! empty( $p['in_stock'] ) || ! isset( $p['in_stock'] ) ? 'موجود' : 'ناموجود';
				$line .= " | موجودی: {$stock}";
				if ( ! empty( $p['id'] ) ) {
					$line .= ' | شناسه: ' . $p['id'];
				}
				if ( ! empty( $p['description'] ) ) {
					$desc_snip = mb_substr( wp_strip_all_tags( (string) $p['description'] ), 0, 140 );
					$line .= " | مشخصات: {$desc_snip}";
				}
				$parts[] = $line;
			}
		}

		// WooCommerce / WordPress orders snapshot
		$parts[] = "\n=== سفارش‌ها و فروش (داده واقعی) ===";
		$order_lines = array();
		$orders_count = 0;
		$revenue = 0.0;
		if ( function_exists( 'wc_get_orders' ) ) {
			$orders = wc_get_orders( array(
				'limit'  => 15,
				'orderby'=> 'date',
				'order'  => 'DESC',
				'status' => array( 'completed', 'processing', 'on-hold', 'pending' ),
				'return' => 'objects',
			) );
			if ( is_array( $orders ) ) {
				foreach ( $orders as $order ) {
					if ( ! is_object( $order ) || ! method_exists( $order, 'get_id' ) ) {
						continue;
					}
					$orders_count++;
					$total = (float) $order->get_total();
					$status = method_exists( $order, 'get_status' ) ? $order->get_status() : '';
					if ( in_array( $status, array( 'completed', 'processing', 'on-hold' ), true ) ) {
						$revenue += $total;
					}
					$oid = $order->get_id();
					$date = method_exists( $order, 'get_date_created' ) && $order->get_date_created()
						? $order->get_date_created()->date( 'Y-m-d H:i' )
						: '';
					$buyer = method_exists( $order, 'get_formatted_billing_full_name' )
						? trim( (string) $order->get_formatted_billing_full_name() )
						: '';
					$items_txt = array();
					foreach ( $order->get_items() as $item ) {
						$items_txt[] = $item->get_name() . '×' . $item->get_quantity();
					}
					$order_lines[] = "• سفارش #{$oid} | وضعیت: {$status} | مبلغ: " . number_format( $total ) . " {$currency}"
						. ( $buyer ? " | مشتری: {$buyer}" : '' )
						. ( $date ? " | تاریخ: {$date}" : '' )
						. ( $items_txt ? ' | اقلام: ' . implode( '، ', array_slice( $items_txt, 0, 5 ) ) : '' );
				}
			}
		}
		if ( function_exists( 'wc_orders_count' ) ) {
			$parts[] = 'تعداد سفارش تکمیل/درحال‌پردازش: '
				. ( (int) wc_orders_count( 'completed' ) + (int) wc_orders_count( 'processing' ) );
		}
		$parts[] = 'نمونه آخرین سفارش‌ها: ' . ( $order_lines ? '' : 'سفارشی ثبت نشده.' );
		foreach ( array_slice( $order_lines, 0, 12 ) as $ol ) {
			$parts[] = $ol;
		}
		if ( $revenue > 0 ) {
			$parts[] = 'جمع تقریبی فروش (از نمونه اخیر): ' . number_format( $revenue ) . ' ' . $currency;
		}

		// Analytics funnel
		$parts[] = "\n=== آمار قیف فروشگاه ===";
		try {
			$an = self::get_display_analytics_data();
			$t  = $an['totals'] ?? array();
			$parts[] = 'بازدید سایت: ' . intval( $t['site_visit'] ?? 0 );
			$parts[] = 'مشاهده محصول: ' . intval( $t['product_view'] ?? 0 );
			$parts[] = 'افزودن به سبد: ' . intval( $t['add_to_cart'] ?? 0 );
			$parts[] = 'ورود به تسویه: ' . intval( $t['checkout_step'] ?? 0 );
			$parts[] = 'ثبت سفارش (رویداد): ' . intval( $t['order_placed'] ?? 0 );
			$top = $an['top_products'] ?? array();
			if ( is_array( $top ) && $top ) {
				$parts[] = 'محبوب‌ترین کالاها:';
				$i = 0;
				foreach ( $top as $tp ) {
					$i++;
					if ( $i > 8 ) break;
					$parts[] = '• ' . ( $tp['title'] ?? 'کالا' ) . ' | مشاهده: ' . intval( $tp['views'] ?? 0 ) . ' | سبد: ' . intval( $tp['carts'] ?? 0 );
				}
			}
		} catch ( \Throwable $e ) {
			$parts[] = 'آمار در دسترس نیست.';
		}

		// Open support threads snapshot (no private dump)
		$parts[] = "\n=== چت پشتیبانی ===";
		try {
			$threads = self::get_chat_threads();
			$pending = 0;
			foreach ( (array) $threads as $th ) {
				if ( ( $th['status'] ?? '' ) === 'pending' || ! empty( $th['unread_admin'] ) ) {
					$pending++;
				}
			}
			$parts[] = 'تعداد گفتگوهای ذخیره‌شده: ' . count( (array) $threads );
			$parts[] = 'گفتگوهای در انتظار پاسخ: ' . $pending;
		} catch ( \Throwable $e ) {
			$parts[] = 'چت در دسترس نیست.';
		}

		$parts[] = "\n=== دستورالعمل پاسخ ===";
		$parts[] = 'فقط بر اساس داده‌های بالا جواب بده. اگر چیزی نبود صادقانه بگو.';
		$parts[] = 'برای قیمت و موجودی از کاتالوگ استفاده کن. برای پیگیری سفارش شماره سفارش بخواه.';
		$parts[] = 'می‌توانی از Markdown سبک استفاده کنی: **پررنگ**، لیست با - ، و لینک.';

		return implode( "\n", $parts );
	}

	/**
	 * Load scraper4.php AI subsystem as an in-memory module without rendering HTML.
	 *
	 * @return bool
	 */
	public static function load_scraper_ai_engine() {
		static $loaded = null;
		if ( null !== $loaded ) {
			return $loaded;
		}

		$scraper_file = plugin_dir_path( __FILE__ ) . 'scraper4.php';
		if ( ! file_exists( $scraper_file ) ) {
			$loaded = false;
			return false;
		}

		if ( ! defined( 'SCRAPER4_NO_RENDER' ) ) {
			define( 'SCRAPER4_NO_RENDER', true );
		}

		$ob_level = ob_get_level();
		@ob_start();
		try {
			require_once $scraper_file;
			$loaded = function_exists( 'aiMasterKey' )
				|| function_exists( 'aiCandidates' )
				|| function_exists( 'aiProviderCall' );
		} catch ( \Throwable $e ) {
			error_log( 'Error loading scraper4 AI engine: ' . $e->getMessage() );
			$loaded = false;
		}
		while ( ob_get_level() > $ob_level ) {
			@ob_end_clean();
		}

		return $loaded;
	}

	/**
	 * Retrieve candidate AI models and votes statistics from scraper4 subsystem / JSON storage.
	 *
	 * @return array
	 */
	public static function get_scraper_ai_candidates() {
		// Do not force-load scraper4.php here — JSON fallback is enough for chat/AJAX.
		$plugin_dir = plugin_dir_path( __FILE__ );

		// 1. Check if scraper4 functions are actively loaded
		if ( function_exists( 'aiCandidates' ) && function_exists( 'aiMasterKey' ) && function_exists( 'aiVotesLoad' ) ) {
			$cands     = aiCandidates();
			$votes     = aiVotesLoad();
			$master    = aiMasterKey();
			$providers = function_exists( 'aiProvidersLoad' ) ? aiProvidersLoad() : array();

			$items = array();
			foreach ( $cands as $c ) {
				$m_id   = $c['model'];
				$m_name = $m_id;
				if ( isset( $providers[ $c['provider'] ]['models'] ) ) {
					foreach ( $providers[ $c['provider'] ]['models'] as $mm ) {
						if ( ( $mm['id'] ?? '' ) === $m_id ) {
							$m_name = $mm['name'] ?? $m_id;
							break;
						}
					}
				}
				$s = $votes['scores'][ $c['key'] ] ?? array( 'wins' => 0, 'losses' => 0, 'votes' => 0 );
				$score = function_exists( 'aiScoreOf' ) ? aiScoreOf( $s ) : ( ! empty( $s['votes'] ) ? round( ( $s['wins'] ?? 0 ) / $s['votes'], 3 ) : 0.0 );
				$items[] = array(
					'provider'     => $c['provider'],
					'model'        => $c['model'],
					'key'          => $c['key'],
					'providerName' => $c['providerName'] ?? ucfirst( $c['provider'] ),
					'modelName'    => $m_name,
					'wins'         => (int) ( $s['wins'] ?? 0 ),
					'losses'       => (int) ( $s['losses'] ?? 0 ),
					'votes'        => (int) ( $s['votes'] ?? 0 ),
					'score'        => $score,
					'is_master'    => ( $c['key'] === $master ),
				);
			}

			usort( $items, function( $a, $b ) {
				if ( $a['is_master'] !== $b['is_master'] ) {
					return $a['is_master'] ? -1 : 1;
				}
				return $b['score'] <=> $a['score'];
			} );

			return array(
				'candidates' => $items,
				'master'     => $master,
				'pin'        => $votes['pin'] ?? '',
				'history'    => array_slice( array_reverse( (array) ( $votes['history'] ?? array() ) ), 0, 50 ),
			);
		}

		// 2. Direct JSON file fallback (connections.json / ai_votes.json / ai_providers.json)
		$conn_file  = $plugin_dir . 'connections.json';
		$votes_file = $plugin_dir . 'ai_votes.json';
		$prov_file  = $plugin_dir . 'ai_providers.json';

		$conn_data  = file_exists( $conn_file ) ? ( @json_decode( file_get_contents( $conn_file ), true ) ?: array() ) : array();
		$votes_data = file_exists( $votes_file ) ? ( @json_decode( file_get_contents( $votes_file ), true ) ?: array() ) : array();
		$prov_data  = file_exists( $prov_file ) ? ( @json_decode( file_get_contents( $prov_file ), true ) ?: array() ) : array();

		$master_key = (string) ( $votes_data['pin'] ?? ( $votes_data['master'] ?? '' ) );
		$raw_cands  = (array) ( $conn_data['ai_candidates'] ?? array() );

		if ( empty( $raw_cands ) ) {
			if ( ! empty( $conn_data['ai_selected']['provider'] ) && ! empty( $conn_data['ai_selected']['model'] ) ) {
				$raw_cands[] = array(
					'provider' => $conn_data['ai_selected']['provider'],
					'model'    => $conn_data['ai_selected']['model'],
				);
			}
		}

		$items  = array();
		$seen   = array();
		$scores = $votes_data['scores'] ?? array();

		foreach ( $raw_cands as $c ) {
			if ( ! is_array( $c ) ) continue;
			$p = trim( (string) ( $c['provider'] ?? '' ) );
			$m = trim( (string) ( $c['model'] ?? '' ) );
			if ( empty( $p ) || empty( $m ) ) continue;
			$k = $p . '::' . $m;
			if ( isset( $seen[ $k ] ) ) continue;
			$seen[ $k ] = 1;

			$p_info = $prov_data[ $p ] ?? array();
			$p_name = $p_info['name'] ?? ucfirst( $p );
			$m_name = $m;
			if ( ! empty( $p_info['models'] ) && is_array( $p_info['models'] ) ) {
				foreach ( $p_info['models'] as $mm ) {
					if ( ( $mm['id'] ?? '' ) === $m ) {
						$m_name = $mm['name'] ?? $m;
						break;
					}
				}
			}

			$s       = $scores[ $k ] ?? array( 'wins' => 0, 'losses' => 0, 'votes' => 0 );
			$v_count = (int) ( $s['votes'] ?? 0 );
			$w_count = (int) ( $s['wins'] ?? 0 );
			$score   = $v_count > 0 ? round( $w_count / $v_count, 3 ) : 0.0;

			$items[] = array(
				'provider'     => $p,
				'model'        => $m,
				'key'          => $k,
				'providerName' => $p_name,
				'modelName'    => $m_name,
				'wins'         => $w_count,
				'losses'       => (int) ( $s['losses'] ?? 0 ),
				'votes'        => $v_count,
				'score'        => $score,
				'is_master'    => ( $k === $master_key ),
			);
		}

		if ( empty( $master_key ) && ! empty( $items ) ) {
			$master_key = $items[0]['key'];
			$items[0]['is_master'] = true;
		}

		usort( $items, function( $a, $b ) {
			if ( $a['is_master'] !== $b['is_master'] ) {
				return $a['is_master'] ? -1 : 1;
			}
			return $b['score'] <=> $a['score'];
		} );

		return array(
			'candidates' => $items,
			'master'     => $master_key,
			'pin'        => $votes_data['pin'] ?? '',
			'history'    => array_slice( array_reverse( (array) ( $votes_data['history'] ?? array() ) ), 0, 50 ),
		);
	}

	/**
	 * Resolve first usable API key from scraper provider config (apiKey / apiKeys[] / keys[]).
	 *
	 * @param array|null $prov_cfg
	 * @param string     $fallback
	 * @return string
	 */
	public static function resolve_provider_api_key( $prov_cfg, $fallback = '' ) {
		if ( ! is_array( $prov_cfg ) ) {
			return trim( (string) $fallback );
		}
		// Prefer scraper's live helpers (apiKeys rotation + legacy apiKey mirror).
		if ( function_exists( 'aiProviderKeys' ) ) {
			$slots = aiProviderKeys( $prov_cfg );
			foreach ( (array) $slots as $sl ) {
				if ( isset( $sl['enabled'] ) && false === $sl['enabled'] ) {
					continue;
				}
				$k = trim( (string) ( $sl['key'] ?? '' ) );
				if ( $k !== '' ) {
					return $k;
				}
			}
		}
		$candidates = array();
		$legacy = trim( (string) ( $prov_cfg['apiKey'] ?? '' ) );
		if ( $legacy !== '' ) {
			$candidates[] = $legacy;
		}
		foreach ( array( 'apiKeys', 'keys' ) as $field ) {
			if ( empty( $prov_cfg[ $field ] ) || ! is_array( $prov_cfg[ $field ] ) ) {
				continue;
			}
			foreach ( $prov_cfg[ $field ] as $row ) {
				if ( is_array( $row ) ) {
					if ( isset( $row['enabled'] ) && false === $row['enabled'] ) {
						continue;
					}
					$k = trim( (string) ( $row['key'] ?? $row['api_key'] ?? $row['token'] ?? '' ) );
				} else {
					$k = trim( (string) $row );
				}
				if ( $k !== '' ) {
					$candidates[] = $k;
				}
			}
		}
		if ( ! empty( $candidates ) ) {
			return $candidates[0];
		}
		return trim( (string) $fallback );
	}

	/**
	 * Retrieve Master AI model directly from scraper4 (connections.json / ai_votes.json / ai_providers.json).
	 *
	 * @param array|null $settings
	 * @return array
	 */
	public static function get_scraper_master_ai_model( $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}

		// Prefer JSON files first (fast). Only load scraper4 if helpers already exist.
		if ( ! function_exists( 'aiMasterKey' ) ) {
			// Soft-load only when explicitly needed later; candidates fall back to JSON.
		}

		$cands_info = self::get_scraper_ai_candidates();
		$master_key = $cands_info['master'];
		$pin_key    = $cands_info['pin'];

		$plugin_dir = plugin_dir_path( __FILE__ );
		$prov_data  = array();
		if ( function_exists( 'aiProvidersLoad' ) ) {
			try {
				$prov_data = aiProvidersLoad();
			} catch ( \Throwable $e ) {
				$prov_data = array();
			}
		}
		if ( empty( $prov_data ) || ! is_array( $prov_data ) ) {
			$prov_candidates = array(
				$plugin_dir . 'ai_providers.json',
				dirname( $plugin_dir ) . '/ai_providers.json',
				$plugin_dir . 'scraper/ai_providers.json',
				$plugin_dir . 'data/ai_providers.json',
			);
			// Also next to scraper4.php if agent is in a subfolder.
			$scraper = $plugin_dir . 'scraper4.php';
			if ( is_file( $scraper ) ) {
				$prov_candidates[] = dirname( $scraper ) . '/ai_providers.json';
			}
			foreach ( $prov_candidates as $prov_file ) {
				if ( is_readable( $prov_file ) ) {
					$decoded = @json_decode( (string) file_get_contents( $prov_file ), true );
					if ( is_array( $decoded ) && ! empty( $decoded ) ) {
						$prov_data = $decoded;
						break;
					}
				}
			}
		}
		if ( ! is_array( $prov_data ) ) {
			$prov_data = array();
		}

		$provider_id = '';
		$model_id    = '';
		if ( ! empty( $master_key ) && strpos( $master_key, '::' ) !== false ) {
			list( $provider_id, $model_id ) = explode( '::', $master_key, 2 );
		}

		$prov_cfg = null;
		if ( $provider_id !== '' && isset( $prov_data[ $provider_id ] ) && is_array( $prov_data[ $provider_id ] ) ) {
			$prov_cfg = $prov_data[ $provider_id ];
			if ( empty( $prov_cfg['id'] ) ) {
				$prov_cfg['id'] = $provider_id;
			}
		}

		$model_name = $model_id;
		if ( $prov_cfg && ! empty( $prov_cfg['models'] ) && is_array( $prov_cfg['models'] ) ) {
			foreach ( $prov_cfg['models'] as $m ) {
				if ( ( $m['id'] ?? '' ) === $model_id ) {
					$model_name = $m['name'] ?? $model_id;
					break;
				}
			}
		}

		// Match candidate stats
		$master_cand = null;
		foreach ( $cands_info['candidates'] as $c ) {
			if ( $c['key'] === $master_key ) {
				$master_cand = $c;
				break;
			}
		}

		$custom_key = trim( (string) ( $settings['ai_api_key'] ?? '' ) );
		$api_key    = self::resolve_provider_api_key( $prov_cfg, $custom_key );
		// Keep provider mirror in sync so aiProviderCall sees a key even when only apiKeys[] is filled.
		if ( is_array( $prov_cfg ) && $api_key !== '' && trim( (string) ( $prov_cfg['apiKey'] ?? '' ) ) === '' ) {
			$prov_cfg['apiKey'] = $api_key;
		}

		$endpoint = '';
		if ( is_array( $prov_cfg ) ) {
			$endpoint = trim( (string) ( $prov_cfg['endpoint'] ?? '' ) );
			if ( $endpoint === '' ) {
				$endpoint = trim( (string) ( $prov_cfg['url'] ?? '' ) );
			}
			// Build OpenAI-compatible chat URL the same way scraper does.
			if ( $endpoint !== '' && function_exists( 'aiProviderEndpoint' ) ) {
				$ep = aiProviderEndpoint( $prov_cfg, $model_id );
				if ( ! empty( $ep['url'] ) && ( $ep['kind'] ?? '' ) !== 'cloudflare' ) {
					$endpoint = (string) $ep['url'];
				}
			} elseif ( $endpoint !== '' && ! preg_match( '~/chat/completions/?$~i', $endpoint )
				&& ! preg_match( '~/ai/run~i', $endpoint ) ) {
				$path = (string) ( parse_url( $endpoint, PHP_URL_PATH ) ?: '' );
				if ( $path === '' || $path === '/' || preg_match( '~/v\d+(\.\d+)?/?$~i', $path ) ) {
					$endpoint = rtrim( $endpoint, '/' ) . '/chat/completions';
				}
			}
		}
		if ( $endpoint === '' ) {
			$endpoints_map = array(
				'openrouter' => 'https://openrouter.ai/api/v1/chat/completions',
				'groq'       => 'https://api.groq.com/openai/v1/chat/completions',
				'deepseek'   => 'https://api.deepseek.com/chat/completions',
				'openai'     => 'https://api.openai.com/v1/chat/completions',
				'gemini'     => 'https://generativelanguage.googleapis.com/v1beta/openai/chat/completions',
				'ollama'     => 'http://127.0.0.1:11434/v1/chat/completions',
			);
			$endpoint = $endpoints_map[ $provider_id ] ?? 'https://openrouter.ai/api/v1/chat/completions';
		}

		$has_live = ( $api_key !== '' )
			|| ( strpos( $endpoint, '127.0.0.1' ) !== false )
			|| ( strpos( $endpoint, 'localhost' ) !== false )
			|| ( is_array( $prov_cfg ) && function_exists( 'aiProviderKeys' ) && count( aiProviderKeys( $prov_cfg ) ) > 0 )
			|| ( is_array( $prov_cfg ) && ! empty( $prov_cfg ) );

		return array(
			'provider_id'   => $provider_id ?: 'openrouter',
			'model_id'      => $model_id ?: 'meta-llama/llama-3.3-70b-instruct:free',
			'key'           => $master_key ?: 'openrouter::meta-llama/llama-3.3-70b-instruct:free',
			'model_name'    => $model_name ?: 'Llama 3.3 70B (رایگان)',
			'provider_name' => ( is_array( $prov_cfg ) && ! empty( $prov_cfg['name'] ) ) ? (string) $prov_cfg['name'] : ( ( is_array( $master_cand ) && ! empty( $master_cand['providerName'] ) ) ? (string) $master_cand['providerName'] : ucfirst( $provider_id ?: 'openrouter' ) ),
			'api_key'       => $api_key,
			'endpoint'      => trim( (string) $endpoint ),
			'provider'      => $prov_cfg,
			'is_pinned'     => ( $pin_key === $master_key && $master_key !== '' ),
			'score'         => $master_cand['score'] ?? 0,
			'wins'          => $master_cand['wins'] ?? 0,
			'losses'        => $master_cand['losses'] ?? 0,
			'votes'         => $master_cand['votes'] ?? 0,
			'source'        => 'scraper4_master',
			'has_live'      => $has_live,
		);
	}


	
	/**
	 * Lightweight OpenAI-compatible chat completion via WordPress HTTP API.
	 * Used by storefront chat so we never depend on loading scraper4.php first.
	 *
	 * @param string $endpoint
	 * @param string $api_key
	 * @param string $model_id
	 * @param array  $payload
	 * @param string $site_name
	 * @return string
	 */
	public static function call_ai_api_http( $endpoint, $api_key, $model_id, $payload, $site_name = '' ) {
		if ( ! function_exists( 'wp_remote_post' ) ) {
			return '';
		}
		$endpoint = trim( (string) $endpoint );
		if ( $endpoint === '' ) {
			$endpoint = 'https://openrouter.ai/api/v1/chat/completions';
		}
		// If base URL without /chat/completions, append it (except Cloudflare native).
		if ( ! preg_match( '~/chat/completions/?$~i', $endpoint )
			&& ! preg_match( '~/ai/run~i', $endpoint )
			&& ! preg_match( '~:generateContent~i', $endpoint ) ) {
			$path = (string) ( parse_url( $endpoint, PHP_URL_PATH ) ?: '' );
			if ( $path === '' || $path === '/' || preg_match( '~/v\d+(\.\d+)?/?$~i', $path ) ) {
				$endpoint = rtrim( $endpoint, '/' ) . '/chat/completions';
			}
		}
		if ( empty( $payload['model'] ) && $model_id !== '' ) {
			$payload['model'] = $model_id;
		}
		$headers = array(
			'Content-Type' => 'application/json; charset=utf-8',
			'Accept'       => 'application/json',
		);
		$api_key = trim( (string) $api_key );
		if ( $api_key !== '' && strtolower( $api_key ) !== 'ollama' ) {
			$headers['Authorization'] = 'Bearer ' . $api_key;
		}
		if ( strpos( $endpoint, 'openrouter.ai' ) !== false ) {
			$headers['HTTP-Referer'] = function_exists( 'home_url' ) ? home_url() : '';
			$headers['X-Title']      = $site_name !== '' ? $site_name : 'Storefront Chat';
		}
		$response = wp_remote_post( $endpoint, array(
			'method'    => 'POST',
			'timeout'   => 28,
			'headers'   => $headers,
			'body'      => function_exists( 'wp_json_encode' ) ? wp_json_encode( $payload ) : json_encode( $payload ),
			'sslverify' => false,
		) );
		if ( is_wp_error( $response ) ) {
			error_log( 'AMPHP call_ai_api_http: ' . $response->get_error_message() );
			return '';
		}
		$code = (int) wp_remote_retrieve_response_code( $response );
		$body = wp_remote_retrieve_body( $response );
		$json = @json_decode( $body, true );
		if ( ! is_array( $json ) ) {
			error_log( 'AMPHP call_ai_api_http bad body HTTP ' . $code . ': ' . substr( (string) $body, 0, 200 ) );
			return '';
		}
		$text = '';
		if ( ! empty( $json['choices'][0]['message']['content'] ) ) {
			$text = trim( (string) $json['choices'][0]['message']['content'] );
		} elseif ( ! empty( $json['choices'][0]['text'] ) ) {
			$text = trim( (string) $json['choices'][0]['text'] );
		} elseif ( ! empty( $json['response'] ) ) {
			$text = trim( (string) $json['response'] );
		} elseif ( ! empty( $json['error']['message'] ) ) {
			error_log( 'AMPHP AI provider error: ' . $json['error']['message'] );
			return '';
		}
		if ( $text === '' ) {
			return '';
		}
		$text = preg_replace( '/<think>.*?<\/think>/si', '', $text );
		return trim( $text );
	}

/**
	 * Call Live AI Provider API via scraper4 engine (preferred) or WordPress HTTP fallback.
	 * Uses the same master model path as scraper4 tests: key rotation, DoH/proxy, aiExtractAnswer.
	 *
	 * @param array  $master_ai
	 * @param string $message
	 * @param string $customer_name
	 * @param array  $settings
	 * @return string
	 */
	public static function call_ai_api( $master_ai, $message, $customer_name, $settings ) {
		$endpoint = (string) ( $master_ai['endpoint'] ?? '' );
		$api_key  = (string) ( $master_ai['api_key'] ?? '' );
		$model_id = (string) ( $master_ai['model_id'] ?? '' );
		$prov_cfg = isset( $master_ai['provider'] ) && is_array( $master_ai['provider'] ) ? $master_ai['provider'] : null;

		if ( empty( $endpoint ) ) {
			$endpoint = 'https://openrouter.ai/api/v1/chat/completions';
		}

		$site_name = function_exists( 'get_bloginfo' ) ? ( get_bloginfo( 'name' ) ?: ( $settings['shop_title'] ?? 'فروشگاه اینترنتی' ) ) : ( $settings['shop_title'] ?? 'فروشگاه اینترنتی' );
		$catalog_ctx = '';
		try {
			$catalog_ctx = self::build_catalog_context_for_ai( 25 );
		} catch ( \Throwable $e ) {
			$catalog_ctx = '(کاتالوگ موقتاً در دسترس نیست)';
		}
		$threshold = number_format( (float) ( $settings['free_shipping_threshold'] ?? 400000 ) );
		$currency  = $settings['currency_symbol'] ?? 'تومان';

		$base_user_prompt = ! empty( $settings['ai_system_prompt'] )
			? $settings['ai_system_prompt']
			: "تو دستیار هوشمند و کارشناس فروش رسمی فروشگاه اینترنتی «{$site_name}» هستی.";
		$system_prompt = $base_user_prompt . "\n"
			. "نام مشتری: «{$customer_name}»\n"
			. "ارسال رایگان برای خریدهای بالای {$threshold} {$currency}.\n"
			. "ضمانت ۷ روز بازگشت و اصالت کالا.\n\n"
			. "دسترسی زنده به داده فروشگاه (محصولات، سفارش‌ها، آمار):\n"
			. $catalog_ctx . "\n\n"
			. "قوانین پاسخ:\n"
			. "۱. فقط بر اساس داده بالا جواب بده؛ اگر نبود بگو در دسترس نیست.\n"
			. "۲. برای قیمت/موجودی از کاتالوگ دقیق نقل کن.\n"
			. "۳. برای پیگیری سفارش، شماره سفارش یا موبایل بخواه و از بخش سفارش‌ها استفاده کن.\n"
			. "۴. لحن گرم، حرفه‌ای و فارسی. خودت را ربات معرفی نکن.\n"
			. "۵. می‌توانی Markdown سبک بنویسی: **پررنگ**، لیست با - یا ۱. ، `کد`، و لینک [متن](url).\n"
			. "۶. پاسخ را خوانا و ساخت‌یافته نگه دار (نه یک پاراگراف شلوغ).";

		$payload = array(
			'model'       => $model_id,
			'messages'    => array(
				array( 'role' => 'system', 'content' => $system_prompt ),
				array( 'role' => 'user', 'content' => $message ),
			),
			'max_tokens'  => 700,
			'temperature' => 0.7,
		);

		// v13.3.9: FAST PATH first — wp_remote_post with resolved key (no 4MB scraper4 include).
		// Scraper engine is heavy and can timeout shared-host AJAX before JSON returns.
		$fast = self::call_ai_api_http( $endpoint, $api_key, $model_id, $payload, $site_name );
		if ( $fast !== '' ) {
			return $fast;
		}

		// Optional scraper4 engine — only if already loaded (never require 4MB scraper4 mid-AJAX).
		if ( function_exists( 'aiProviderCall' ) && is_array( $prov_cfg ) && ! empty( $prov_cfg ) ) {
			if ( empty( $prov_cfg['id'] ) && ! empty( $master_ai['provider_id'] ) ) {
				$prov_cfg['id'] = $master_ai['provider_id'];
			}
			if ( $api_key !== '' && trim( (string) ( $prov_cfg['apiKey'] ?? '' ) ) === '' ) {
				$prov_cfg['apiKey'] = $api_key;
			}
			if ( empty( $prov_cfg['url'] ) && ! empty( $prov_cfg['endpoint'] ) ) {
				$prov_cfg['url'] = $prov_cfg['endpoint'];
			}
			try {
				$r = aiProviderCall( $prov_cfg, $model_id, $payload );
				$text = '';
				if ( function_exists( 'aiExtractAnswer' ) ) {
					$text = trim( (string) aiExtractAnswer( $r['body'] ?? null ) );
				} elseif ( function_exists( 'aiExtractText' ) ) {
					$text = trim( (string) aiExtractText( $r['body'] ?? null ) );
				}
				if ( $text === '' && ! empty( $r['body'] ) && is_array( $r['body'] ) ) {
					$b = $r['body'];
					if ( ! empty( $b['choices'][0]['message']['content'] ) ) {
						$text = trim( (string) $b['choices'][0]['message']['content'] );
					} elseif ( ! empty( $b['choices'][0]['text'] ) ) {
						$text = trim( (string) $b['choices'][0]['text'] );
					} elseif ( ! empty( $b['response'] ) ) {
						$text = trim( (string) $b['response'] );
					}
				}
				if ( $text !== '' ) {
					$text = preg_replace( '/<think>.*?<\/think>/si', '', $text );
					return trim( $text );
				}
			} catch ( \Throwable $e ) {
				error_log( 'AMPHP call_ai_api scraper engine: ' . $e->getMessage() );
			}
		}

		// Final HTTP retry (same as fast path).
		return self::call_ai_api_http( $endpoint, $api_key, $model_id, $payload, $site_name );
	}


	/**
	 * Intelligent Dynamic E-Commerce NLP Engine (Analyzes Catalog, Prices, Features & Query).
	 * Eliminates hardcoded canned templates and provides customized, contextual assistance.
	 *
	 * @param string $message
	 * @param string $customer_name
	 * @param array  $settings
	 * @return string
	 */
	public static function generate_smart_local_reply( $message, $customer_name, $settings ) {
		$site_name = get_bloginfo( 'name' ) ?: ( $settings['shop_title'] ?? 'فروشگاه ما' );
		$threshold = (float) ( $settings['free_shipping_threshold'] ?? 400000 );
		$threshold_formatted = number_format( $threshold ) . ' تومان';
		$currency  = $settings['currency_symbol'] ?? 'تومان';

		$msg_raw   = trim( $message );
		$msg_lower = mb_strtolower( $msg_raw, 'UTF-8' );

		$name_clean = trim( $customer_name );
		if ( empty( $name_clean ) || 'کاربر مهمان' === $name_clean ) {
			$name_clean = '';
		}
		$name_greeting = ! empty( $name_clean ) ? "{$name_clean} عزیز" : 'گرامی';

		$has_greeting = (bool) preg_match( '/(سلام|درود|وقت بخیر|صبح بخیر|عصر بخیر|روز بخیر|سلام علیکم)/u', $msg_lower );
		$greeting_prefix = $has_greeting ? "سلام {$name_greeting}، به {$site_name} خوش آمدید! 🌸 " : '';

		$products = array();
		try {
			$products = self::get_all_scraped_products();
			if ( ! is_array( $products ) ) {
				$products = array();
			}
		} catch ( \Throwable $e ) {
			$products = array();
		}

		// Stopwords that shouldn't match product titles
		$stop_words = array(
			'سلام', 'درود', 'صبح', 'بخیر', 'عصر', 'وقت', 'این', 'رو', 'داری', 'دارید', 'هست', 'هستید',
			'قیمت', 'چنده', 'چقدره', 'میخوام', 'لطفا', 'ممنون', 'خرید', 'تومان', 'محصولات', 'کالاها',
			'چی', 'چه', 'چیزهایی', 'ضمانت', 'گارانتی', 'ارسال', 'پست', 'سفارش', 'پیگیری', 'کد', 'تخفیف',
			'آیا', 'کدام', 'کدوم', 'برای', 'میشه', 'دارن', 'داشته', 'باشه'
		);

		// Clean query tokens
		$clean_q = preg_replace( '/[^\p{L}\p{N}\s]/u', ' ', $msg_lower );
		$raw_tokens = preg_split( '/\s+/u', $clean_q, -1, PREG_SPLIT_NO_EMPTY );
		$q_tokens = array();
		foreach ( $raw_tokens as $t ) {
			if ( mb_strlen( $t ) > 1 && ! in_array( $t, $stop_words, true ) ) {
				$q_tokens[] = $t;
			}
		}

		// 1. Match against products in catalog
		$matches = array();
		if ( ! empty( $products ) && ! empty( $q_tokens ) ) {
			foreach ( $products as $p ) {
				$title_low = mb_strtolower( $p['title'] ?? '', 'UTF-8' );
				$cat_low   = mb_strtolower( $p['category'] ?? '', 'UTF-8' );
				$desc_low  = mb_strtolower( $p['description'] ?? '', 'UTF-8' );

				$score = 0;
				foreach ( $q_tokens as $token ) {
					if ( mb_strpos( $title_low, $token ) !== false ) {
						$score += 3;
					} elseif ( mb_strpos( $cat_low, $token ) !== false ) {
						$score += 2;
					} elseif ( mb_strpos( $desc_low, $token ) !== false ) {
						$score += 1;
					}
				}

				if ( $score > 0 ) {
					$matches[] = array( 'score' => $score, 'product' => $p );
				}
			}

			usort( $matches, function( $a, $b ) {
				return $b['score'] <=> $a['score'];
			} );
		}

		// If a matching product was found in catalog with solid score
		if ( ! empty( $matches ) && $matches[0]['score'] >= 2 ) {
			$top_p = $matches[0]['product'];
			$p_title = $top_p['title'];
			$p_price = ! empty( $top_p['price_formatted'] ) ? $top_p['price_formatted'] : ( number_format( (float) ( $top_p['price'] ?? 0 ) ) . ' تومان' );
			$has_disc = ! empty( $top_p['has_discount'] ) && ! empty( $top_p['discount_pct'] );
			$disc_pct = $top_p['discount_pct'] ?? 0;
			$desc     = $top_p['description'] ?? '';
			$desc_low = mb_strtolower( $desc, 'UTF-8' );

			$reply = $greeting_prefix . "درباره «{$p_title}»، این محصول هم‌اکنون با قیمت {$p_price} در انبار موجود و آماده ثبت سفارش است.";
			if ( $has_disc && $disc_pct > 0 ) {
				$reply .= " (این کالا دارای {$disc_pct}٪ تخفیف ویژه می‌باشد).";
			}

			// Specific feature inquiries
			if ( preg_match( '/(ضد آب|ضداب|waterproof|ip6)/u', $msg_lower ) ) {
				if ( mb_strpos( $desc_low, 'ضد آب' ) !== false || mb_strpos( $desc_low, 'ip6' ) !== false ) {
					$reply .= " 💧 این محصول دارای استاندارد مقاومت در برابر نفوذ آب است.";
				} else {
					$reply .= " ℹ️ توصیه می‌شود این محصول دور از تماس مستقیم با رطوبت و آب نگهداری شود.";
				}
			}

			if ( preg_match( '/(مکالمه|تماس|میکروفون|صدا)/u', $msg_lower ) ) {
				if ( mb_strpos( $desc_low, 'مکالمه' ) !== false || mb_strpos( $desc_low, 'میکروفون' ) !== false ) {
					$reply .= " 📞 این کالا قابلیت مکالمه باکیفیت و شفاف را پشتیبانی می‌کند.";
				}
			}

			if ( preg_match( '/(باتری|شارژ|شارژدهی|میلی‌آمپر)/u', $msg_lower ) ) {
				$reply .= " 🔋 این کالا از باتری بادوام با کارایی و بازدهی بالا بهره می‌برد.";
			}

			$p_num_price = (float) ( $top_p['price'] ?? 0 );
			if ( $p_num_price >= $threshold ) {
				$reply .= " همچنین به دلیل مبلغ سفارش، ارسال این کالا کاملاً رایگان خواهد بود! 🚚";
			}

			$reply .= " برای خرید آنلاین، کافی است در کارت محصول دکمه «خرید آنلاین» را لمس فرمایید.";
			return $reply;
		}

		// 2. Shipping & Delivery
		if ( preg_match( '/(ارسال|پست|تیپاکس|هزینه ارسال|چند روزه|کی میرسه|زمان تحویل|تحویل سفارش)/u', $msg_lower ) ) {
			return $greeting_prefix . "کلیه سفارش‌ها با بسته‌بندی ایمن و ضربه‌گیر از طریق پست پیشتاز و تیپاکس ظرف ۲ الی ۳ روز کاری تحویل می‌گردند. برای خریدهای بالای {$threshold_formatted} هزینه ارسال کاملاً رایگان است. 🚚";
		}

		// 3. Order Tracking
		if ( preg_match( '/(پیگیری|کد رهگیری|سفارشم|کجاست|فاکتور|شماره سفارش)/u', $msg_lower ) ) {
			return $greeting_prefix . "برای پیگیری لحظه‌ای وضعیت سفارش، می‌توانید از منوی بالای صفحه دکمه «پیگیری سفارش» را لمس فرمایید یا شماره سفارش خود را اینجا ارسال کنید تا وضعیت مرسوله را فوراً استعلام بگیریم. 📦";
		}

		// 4. Discounts & Pricing General
		if ( preg_match( '/(تخفیف|کد تخفیف|قیمت مناسب|ارزان|حراج|پیشنهاد ویژه)/u', $msg_lower ) ) {
			$disc_items = array();
			foreach ( $products as $p ) {
				if ( ! empty( $p['has_discount'] ) ) $disc_items[] = $p;
			}
			if ( ! empty( $disc_items ) ) {
				usort( $disc_items, function( $a, $b ) {
					return ( $b['discount_pct'] ?? 0 ) <=> ( $a['discount_pct'] ?? 0 );
				} );
				$top_disc = $disc_items[0];
				$pct = $top_disc['discount_pct'] ?? 0;
				return $greeting_prefix . "تمامی قیمت‌های سایت با تخفیف‌های ویژه فروشگاه اعمال شده‌اند! بیشترین تخفیف هم‌اکنون مربوط به «{$top_disc['title']}» با {$pct}٪ تخفیف است. حتماً بخش پیشنهادهای شگفت‌انگیز را بررسی نمایید! ✨";
			}
			return $greeting_prefix . "تمامی قیمت‌های درج شده در سایت رقابتی بوده و تخفیف‌های نقدی مستقیماً روی سبد خرید اعمال می‌شوند.";
		}

				// 5. Guarantee & Returns
		if ( preg_match( '/(گارانتی|ضمانت|مرجوع|بازگشت|خراب|اصل|اورجینال|مهلت تست)/u', $msg_lower ) ) {
			return $greeting_prefix . "تمامی کالاهای فروشگاه دارای ۷ روز مهلت تست و ضمانت بازگشت وجه، تضمین اصالت فیزیکی و پشتیبانی کامل هستند تا خریدی با آرامش خاطر را تجربه کنید. 🛡️";
		}

		// 6. Payment methods
		if ( preg_match( '/(پرداخت|درگاه|کارت به کارت|انتقال|چطور پرداخت|امن|بانک|شاپرک)/u', $msg_lower ) ) {
			return $greeting_prefix . "پرداخت سفارش‌ها به صورت کاملاً امن و آنی از طریق کلیه کارت‌های عضو شتاب در درگاه رسمی شاپرک انجام می‌شود. 💳";
		}

		// 7. Catalog & Categories Overview
		if ( preg_match( '/(چه محصولاتی|چه کالاهایی|لیست محصولات|چی دارید|چه چیزهایی|موجودی فروشگاه|دسته‌بندی|دسته بندی|لیست کالاها|همه محصولات)/u', $msg_lower ) ) {
			$cats = array();
			foreach ( $products as $p ) {
				$c = $p['category'] ?? 'عمومی';
				$cats[ $c ] = true;
			}
			$cats_keys = array_keys( $cats );
			$cats_str  = implode( '، ', array_slice( $cats_keys, 0, 4 ) );
			$total_cnt = count( $products );
			return $greeting_prefix . "ما تنوعی از کالاهای باکیفیت در دسته‌بندی‌های {$cats_str} را عرضه می‌کنیم. هم‌اکنون {$total_cnt} محصول منتخب در انبار موجود است که می‌توانید با دکمه دسته‌بندی‌ها در منوی بالا آن‌ها را مشاهده نمایید. 🛍️";
		}

// 8. Greeting only
		if ( $has_greeting && empty( $q_tokens ) ) {
			$feat_item = ! empty( $products[0]['title'] ) ? " هم‌اکنون محصول «{$products[0]['title']}» با تخفیف ویژه در انبار موجود است." : '';
			return $greeting_prefix . "در خدمت شما هستیم.{$feat_item} هرگونه سوالی درباره مشخصات کالاها، استعلام قیمت یا ثبت سفارش دارید بفرمایید تا فوراً راهنمایی‌تان کنیم! 🌸";
		}

		// 9. Dynamic Contextual Fallback incorporating customer query
		$preview_msg = mb_substr( $msg_raw, 0, 45 ) . ( mb_strlen( $msg_raw ) > 45 ? '...' : '' );
		return $greeting_prefix . "درباره پیام شما («{$preview_msg}»)، اطلاعات شما به همکاران پشتیبانی فروشگاه نیز ارسال شد تا در صورت نیاز با شما تماس بگیرند. برای بررسی سریع کالاها نیز می‌توانید از منوی بالای صفحه یا جستجوی سریع استفاده نمایید. 🙏";
	}

	/**
	 * Generate AI Support Reply (Connecting to Master Model or Dynamic NLP Engine).
	 *
	 * @param string $message
	 * @param string $customer_name
	 * @param array|null $settings
	 * @return string
	 */
	public static function generate_ai_support_reply( $message, $customer_name = '', $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}

		$coordination = $settings['ai_coordination_mode'] ?? 'ai_first';
		if ( 'human_only' === $coordination ) {
			return '';
		}

		// 1. Resolve master AI configuration (loads scraper4 engine + apiKeys)
		$master_ai = self::get_scraper_master_ai_model( $settings );

		// 2. Always try live master model when scraper provider config / key / local endpoint is present
		$try_live = ! empty( $master_ai['has_live'] )
			|| ! empty( $master_ai['api_key'] )
			|| ( is_array( $master_ai['provider'] ?? null ) && ! empty( $master_ai['provider'] ) )
			|| strpos( (string) ( $master_ai['endpoint'] ?? '' ), '127.0.0.1' ) !== false
			|| strpos( (string) ( $master_ai['endpoint'] ?? '' ), 'localhost' ) !== false;

		if ( $try_live ) {
			$reply = self::call_ai_api( $master_ai, $message, $customer_name, $settings );
			if ( ! empty( $reply ) ) {
				return $reply;
			}
		}

		// 3. Only if live AI failed: local catalog NLP (not preferred for storefront)
		if ( 'ai_only' === $coordination ) {
			return 'متأسفانه ارتباط با پشتیبان هوشمند برقرار نشد. لطفاً چند لحظه بعد دوباره پیام بفرستید.';
		}
		return self::generate_smart_local_reply( $message, $customer_name, $settings );
	}


	/**
	 * AJAX endpoint for live testing AI chat in Admin Tab 4.
	 */
	public static function ajax_test_ai_chat() {
		// Never let notices/fatals turn into jQuery "connection error".
		while ( function_exists( 'ob_get_level' ) && ob_get_level() > 0 ) {
			@ob_end_clean();
		}
		@ob_start();
		@ini_set( 'display_errors', '0' );
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 120 );
		}

		if ( ! empty( $_POST['nonce'] ) ) {
			check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce', false );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			while ( ob_get_level() > 0 ) { @ob_end_clean(); }
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$message = sanitize_text_field( wp_unslash( $_POST['message'] ?? '' ) );
		if ( $message === '' ) {
			while ( ob_get_level() > 0 ) { @ob_end_clean(); }
			wp_send_json_error( 'متن پیام خالی است.' );
		}

		$settings   = self::get_settings();
		$t0         = microtime( true );
		$reply      = '';
		$master_ai  = array();
		$model_label = 'مدل هوشمند مستر اسکرپر';
		$err        = '';

		try {
			$master_ai = self::get_scraper_master_ai_model( $settings );
			$model_label = ! empty( $master_ai['model_name'] ) ? $master_ai['model_name'] : $model_label;
			$reply = self::generate_ai_support_reply( $message, 'کاربر آزمایشی', $settings );
		} catch ( \Throwable $e ) {
			$err = $e->getMessage();
			error_log( 'AMPHP ajax_test_ai_chat: ' . $err );
			try {
				$reply = self::generate_smart_local_reply( $message, 'کاربر آزمایشی', $settings );
				$model_label = 'پاسخ محلی (fallback)';
			} catch ( \Throwable $e2 ) {
				$reply = 'خطا در تولید پاسخ: ' . $e2->getMessage();
			}
		}

		if ( ! is_string( $reply ) || trim( $reply ) === '' ) {
			$reply = 'پاسخی از مدل مستر دریافت نشد. کلید API ارائه‌دهندهٔ مستر را در اسکرپر (ai_providers.json) یا فیلد کلید AI افزونه بررسی کنید.'
				. ( $err !== '' ? ( ' جزئیات: ' . $err ) : '' );
		}

		$time_ms = (int) round( ( microtime( true ) - $t0 ) * 1000 );
		while ( function_exists( 'ob_get_level' ) && ob_get_level() > 0 ) {
			@ob_end_clean();
		}
		wp_send_json_success( array(
			'reply'     => $reply,
			'model'     => $model_label,
			'provider'  => is_array( $master_ai ) ? ( $master_ai['provider_name'] ?? 'اسکرپر' ) : 'اسکرپر',
			'key'       => is_array( $master_ai ) ? ( $master_ai['key'] ?? '' ) : '',
			'score'     => is_array( $master_ai ) ? ( $master_ai['score'] ?? 0 ) : 0,
			'is_pinned' => is_array( $master_ai ) ? ( ! empty( $master_ai['is_pinned'] ) ) : false,
			'source'    => is_array( $master_ai ) ? ( $master_ai['source'] ?? 'scraper4_master' ) : 'local',
			'took_ms'   => $time_ms,
			'api_ready' => is_array( $master_ai ) && ! empty( $master_ai['api_key'] ),
			'endpoint'  => is_array( $master_ai ) ? (string) ( $master_ai['endpoint'] ?? '' ) : '',
			'error'     => $err,
		) );
	}

	/**
	 * AJAX endpoint for comparing all candidate AI models side-by-side with catalog grounding.
	 */
	public static function ajax_compare_ai_candidates() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$message = sanitize_text_field( $_POST['message'] ?? '' );
		if ( empty( $message ) ) {
			wp_send_json_error( 'متن پیام خالی است.' );
		}

		$settings   = self::get_settings();
		$cands_info = self::get_scraper_ai_candidates();
		$candidates = $cands_info['candidates'] ?? array();

		if ( empty( $candidates ) ) {
			wp_send_json_error( 'هیچ مدل کاندیدی در سیستم هوش مصنوعی اسکرپر۴ یافت نشد.' );
		}

		$plugin_dir = plugin_dir_path( __FILE__ );
		$prov_file  = $plugin_dir . 'ai_providers.json';
		$prov_data  = file_exists( $prov_file ) ? ( @json_decode( file_get_contents( $prov_file ), true ) ?: array() ) : array();

		$results = array();
		foreach ( $candidates as $c ) {
			$t0   = microtime( true );
			$p_id = $c['provider'];
			$m_id = $c['model'];
			$p_cfg = $prov_data[ $p_id ] ?? array();

			if ( is_array( $p_cfg ) && empty( $p_cfg['id'] ) ) {
				$p_cfg['id'] = $p_id;
			}
			$cand_master = array(
				'provider_id' => $p_id,
				'model_id'    => $m_id,
				'model_name'  => $c['modelName'],
				'api_key'     => self::resolve_provider_api_key( $p_cfg, (string) ( $settings['ai_api_key'] ?? '' ) ),
				'endpoint'    => $p_cfg['endpoint'] ?? ( $p_cfg['url'] ?? '' ),
				'provider'    => $p_cfg,
				'has_live'    => true,
			);

			$reply = '';
			if ( ! empty( $cand_master['api_key'] ) || ! empty( $p_cfg ) || strpos( (string) $cand_master['endpoint'], '127.0.0.1' ) !== false ) {
				$reply = self::call_ai_api( $cand_master, $message, 'کاربر آزمایشی', $settings );
			}
			if ( empty( $reply ) ) {
				$reply = self::generate_smart_local_reply( $message, 'کاربر آزمایشی', $settings );
			}

			$time_ms = (int) round( ( microtime( true ) - $t0 ) * 1000 );
			$results[] = array(
				'key'          => $c['key'],
				'provider'     => $c['provider'],
				'model'        => $c['model'],
				'providerName' => $c['providerName'],
				'modelName'    => $c['modelName'],
				'score'        => $c['score'],
				'wins'         => $c['wins'],
				'losses'       => $c['losses'],
				'votes'        => $c['votes'],
				'is_master'    => $c['is_master'],
				'latency'      => $time_ms,
				'text'         => $reply,
			);
		}

		wp_send_json_success( array(
			'master'     => $cands_info['master'],
			'pin'        => $cands_info['pin'],
			'candidates' => $results,
		) );
	}

	/**
	 * AJAX endpoint for recording a vote for an AI candidate model (Scraper4 AI voting system).
	 */
	public static function ajax_vote_ai_candidate() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$winner     = sanitize_text_field( $_POST['winner'] ?? '' );
		$task       = sanitize_text_field( $_POST['task'] ?? 'autoreply' );
		$input_text = sanitize_text_field( $_POST['input'] ?? '' );
		$raw_cands  = (array) ( $_POST['candidates'] ?? array() );
		$cands      = array_map( 'sanitize_text_field', $raw_cands );

		if ( empty( $winner ) || empty( $cands ) ) {
			wp_send_json_error( 'اطلاعات رای‌دهی نامعتبر است.' );
		}

		self::load_scraper_ai_engine();
		if ( function_exists( 'aiVoteRecord' ) ) {
			$v = aiVoteRecord( $task, $input_text, $winner, $cands );
			wp_send_json_success( array(
				'master'  => $v['master'] ?? '',
				'pin'     => $v['pin'] ?? '',
				'scores'  => $v['scores'] ?? array(),
				'message' => 'رای شما با موفقیت ثبت شد و رتبه‌بندی مدل مستر در اسکرپر به‌روز گردید.',
			) );
		}

		$plugin_dir = plugin_dir_path( __FILE__ );
		$votes_file = $plugin_dir . 'ai_votes.json';
		$v = file_exists( $votes_file ) ? ( @json_decode( file_get_contents( $votes_file ), true ) ?: array() ) : array();

		if ( ! isset( $v['scores'] ) || ! is_array( $v['scores'] ) ) {
			$v['scores'] = array();
		}
		if ( ! in_array( $winner, $cands, true ) ) {
			$cands[] = $winner;
		}

		foreach ( $cands as $k ) {
			if ( empty( $k ) ) continue;
			if ( ! isset( $v['scores'][ $k ] ) || ! is_array( $v['scores'][ $k ] ) ) {
				$v['scores'][ $k ] = array( 'wins' => 0, 'losses' => 0, 'votes' => 0, 'last_at' => 0 );
			}
			$v['scores'][ $k ]['votes']   = (int) ( $v['scores'][ $k ]['votes'] ?? 0 ) + 1;
			$v['scores'][ $k ]['last_at'] = time();
			if ( $k === $winner ) {
				$v['scores'][ $k ]['wins'] = (int) ( $v['scores'][ $k ]['wins'] ?? 0 ) + 1;
			} else {
				$v['scores'][ $k ]['losses'] = (int) ( $v['scores'][ $k ]['losses'] ?? 0 ) + 1;
			}
			$votes_count = $v['scores'][ $k ]['votes'];
			$v['scores'][ $k ]['score'] = $votes_count > 0 ? round( (int) ( $v['scores'][ $k ]['wins'] ?? 0 ) / $votes_count, 3 ) : 0.0;
		}

		$v['history'][] = array(
			'at'         => time(),
			'task'       => $task,
			'input'      => mb_substr( $input_text, 0, 150 ),
			'winner'     => $winner,
			'candidates' => $cands,
		);
		if ( count( $v['history'] ) > 200 ) {
			$v['history'] = array_slice( $v['history'], -200 );
		}

		if ( empty( $v['pin'] ) ) {
			$best   = '';
			$best_s = -1.0;
			foreach ( $v['scores'] as $sk => $sd ) {
				if ( ( $sd['score'] ?? 0 ) > $best_s ) {
					$best_s = $sd['score'];
					$best   = $sk;
				}
			}
			if ( ! empty( $best ) ) {
				$v['master'] = $best;
			}
		}

		$v['updated_at'] = time();
		@file_put_contents( $votes_file, wp_json_encode( $v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );

		wp_send_json_success( array(
			'master'  => $v['master'] ?? $winner,
			'pin'     => $v['pin'] ?? '',
			'scores'  => $v['scores'] ?? array(),
			'message' => 'رای شما با موفقیت ثبت شد و مدل مستر در اسکرپر به‌روز گردید.',
		) );
	}

	/**
	 * AJAX endpoint for pinning a candidate model as Master.
	 */
	public static function ajax_pin_master_model() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$key = sanitize_text_field( $_POST['key'] ?? '' );
		if ( empty( $key ) ) {
			wp_send_json_error( 'کلید مدل نامعتبر است.' );
		}

		$plugin_dir = plugin_dir_path( __FILE__ );
		$votes_file = $plugin_dir . 'ai_votes.json';
		$v = file_exists( $votes_file ) ? ( @json_decode( file_get_contents( $votes_file ), true ) ?: array() ) : array();

		$v['pin']        = $key;
		$v['master']     = $key;
		$v['updated_at'] = time();
		@file_put_contents( $votes_file, wp_json_encode( $v, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );

		if ( strpos( $key, '::' ) !== false ) {
			list( $p, $m ) = explode( '::', $key, 2 );
			$conn_file = $plugin_dir . 'connections.json';
			$c = file_exists( $conn_file ) ? ( @json_decode( file_get_contents( $conn_file ), true ) ?: array() ) : array();
			$c['ai_selected'] = array( 'provider' => $p, 'model' => $m );
			@file_put_contents( $conn_file, wp_json_encode( $c, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
		}

		wp_send_json_success( array(
			'key'     => $key,
			'message' => 'مدل انتخابی با موفقیت به عنوان مدل مستر اسکرپر سنجاق (Pin) شد.',
		) );
	}

	/**
	 * AJAX endpoint for uploading / importing Scraper AI config files.
	 */
	public static function ajax_upload_ai_config() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$json_str = '';
		if ( ! empty( $_FILES['config_file']['tmp_name'] ) ) {
			$json_str = file_get_contents( $_FILES['config_file']['tmp_name'] );
		} elseif ( ! empty( $_POST['config_json'] ) ) {
			$json_str = wp_unslash( $_POST['config_json'] );
		}

		if ( empty( $json_str ) ) {
			wp_send_json_error( 'هیچ فایل یا متن تنظیمی ارسال نشد.' );
		}

		$data = @json_decode( $json_str, true );
		if ( ! is_array( $data ) ) {
			wp_send_json_error( 'محتوای ارسال شده حاوی ساختار معتبر JSON نیست.' );
		}

		$plugin_dir = plugin_dir_path( __FILE__ );
		$saved_files = array();

		// 1. Combined bundle
		if ( isset( $data['connections'] ) && is_array( $data['connections'] ) ) {
			@file_put_contents( $plugin_dir . 'connections.json', wp_json_encode( $data['connections'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
			$saved_files[] = 'connections.json (اتصالات و کاندیدها)';
		}
		if ( isset( $data['ai_providers'] ) && is_array( $data['ai_providers'] ) ) {
			@file_put_contents( $plugin_dir . 'ai_providers.json', wp_json_encode( $data['ai_providers'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
			$saved_files[] = 'ai_providers.json (ارائه‌دهنده‌ها و کلیدها)';
		}
		if ( isset( $data['ai_votes'] ) && is_array( $data['ai_votes'] ) ) {
			@file_put_contents( $plugin_dir . 'ai_votes.json', wp_json_encode( $data['ai_votes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
			$saved_files[] = 'ai_votes.json (مدل مستر و امتیازات)';
		}

		// 2. Individual file upload
		if ( empty( $saved_files ) ) {
			if ( isset( $data['ai_candidates'] ) || isset( $data['ai_selected'] ) ) {
				$existing_conn = file_exists( $plugin_dir . 'connections.json' ) ? ( @json_decode( file_get_contents( $plugin_dir . 'connections.json' ), true ) ?: array() ) : array();
				$merged = array_merge( $existing_conn, $data );
				@file_put_contents( $plugin_dir . 'connections.json', wp_json_encode( $merged, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
				$saved_files[] = 'connections.json (کاندیدها و مدل انتخابی)';
			} elseif ( isset( $data['master'] ) || isset( $data['pin'] ) || isset( $data['scores'] ) ) {
				@file_put_contents( $plugin_dir . 'ai_votes.json', wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
				$saved_files[] = 'ai_votes.json (مدل مستر و آرا)';
			} elseif ( isset( $data['openrouter'] ) || isset( $data['groq'] ) || isset( $data['deepseek'] ) || isset( $data['openai'] ) || isset( $data['ollama'] ) ) {
				@file_put_contents( $plugin_dir . 'ai_providers.json', wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
				$saved_files[] = 'ai_providers.json (لیست ارائه‌دهندگان)';
			} else {
				@file_put_contents( $plugin_dir . 'connections.json', wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
				$saved_files[] = 'connections.json';
			}
		}

		self::load_scraper_ai_engine();
		$cands_info = self::get_scraper_ai_candidates();
		$master_ai  = self::get_scraper_master_ai_model();

		wp_send_json_success( array(
			'message'     => 'فایل تنظیمات با موفقیت در اسکرپر بارگذاری شد: ' . implode( ' و ', $saved_files ),
			'master'      => $master_ai,
			'candidates'  => $cands_info['candidates'] ?? array(),
		) );
	}

	/**
	 * AJAX endpoint for exporting all Scraper AI config files as a merged JSON package.
	 */
	public static function ajax_export_ai_config() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'دسترسی غیرمجاز.' );
		}

		$plugin_dir = plugin_dir_path( __FILE__ );
		$export = array(
			'exported_at'  => date( 'Y-m-d H:i:s' ),
			'connections'  => file_exists( $plugin_dir . 'connections.json' ) ? ( @json_decode( file_get_contents( $plugin_dir . 'connections.json' ), true ) ?: array() ) : array(),
			'ai_providers' => file_exists( $plugin_dir . 'ai_providers.json' ) ? ( @json_decode( file_get_contents( $plugin_dir . 'ai_providers.json' ), true ) ?: array() ) : array(),
			'ai_votes'     => file_exists( $plugin_dir . 'ai_votes.json' ) ? ( @json_decode( file_get_contents( $plugin_dir . 'ai_votes.json' ), true ) ?: array() ) : array(),
		);

		header( 'Content-Type: application/json; charset=UTF-8' );
		header( 'Content-Disposition: attachment; filename="scraper_ai_config_' . date( 'Y_m_d_His' ) . '.json"' );
		echo wp_json_encode( $export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
		exit;
	}

	/**
	 * Filter custom recurrence intervals for WordPress WP-Cron.
	 *
	 * @param array $schedules
	 * @return array
	 */
	public static function filter_cron_schedules( $schedules ) {
		if ( ! isset( $schedules['every_minute'] ) ) {
			$schedules['every_minute'] = array(
				'interval' => 60,
				'display'  => '⚡ هر ۱ دقیقه یک‌بار (فوق‌سریع - مخصوص پاسخ آنی و اعلان‌های مشتری)',
			);
		}
		if ( ! isset( $schedules['every_5_mins'] ) ) {
			$schedules['every_5_mins'] = array(
				'interval' => 5 * MINUTE_IN_SECONDS,
				'display'  => 'هر ۵ دقیقه یک‌بار (سریع)',
			);
		}
		if ( ! isset( $schedules['every_15_mins'] ) ) {
			$schedules['every_15_mins'] = array(
				'interval' => 15 * MINUTE_IN_SECONDS,
				'display'  => 'هر ۱۵ دقیقه یک‌بار (بسیار پرسرعت)',
			);
		}
		if ( ! isset( $schedules['every_30_mins'] ) ) {
			$schedules['every_30_mins'] = array(
				'interval' => 30 * MINUTE_IN_SECONDS,
				'display'  => 'هر ۳۰ دقیقه یک‌بار (توصیه شده)',
			);
		}
		if ( ! isset( $schedules['every_2_hours'] ) ) {
			$schedules['every_2_hours'] = array(
				'interval' => 2 * HOUR_IN_SECONDS,
				'display'  => 'هر ۲ ساعت یک‌بار',
			);
		}
		if ( ! isset( $schedules['every_6_hours'] ) ) {
			$schedules['every_6_hours'] = array(
				'interval' => 6 * HOUR_IN_SECONDS,
				'display'  => 'هر ۶ ساعت یک‌بار',
			);
		}
		return $schedules;
	}

	/**
	 * Synchronize WP-Cron schedule with plugin settings.
	 *
	 * @param array|null $settings
	 */
	public static function sync_wp_cron_schedule( $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}

		$hook      = 'scraper_auto_shop_cron_sync';
		$enabled   = ! empty( $settings['enable_wp_cron_sync'] );
		$interval  = ! empty( $settings['wp_cron_interval'] ) ? $settings['wp_cron_interval'] : 'every_30_mins';
		$timestamp = wp_next_scheduled( $hook );

		if ( ! $enabled ) {
			if ( $timestamp ) {
				wp_clear_scheduled_hook( $hook );
			}
			return;
		}

		if ( $timestamp ) {
			$event = wp_get_scheduled_event( $hook );
			if ( $event && ( $event->schedule ?? '' ) !== $interval ) {
				wp_clear_scheduled_hook( $hook );
				wp_schedule_event( time() + 60, $interval, $hook );
			}
		} else {
			wp_schedule_event( time() + 60, $interval, $hook );
		}
	}

	/**
	 * Execute scraper4.php cron job from WordPress internal WP-Cron system.
	 *
	 * @return array
	 */
	public static function execute_scraper_cron_job() {
		@set_time_limit( 300 );

		// Concurrency protection: prevent overlapping executions if a previous scrape is still running
		if ( get_transient( 'scraper_cron_is_executing' ) ) {
			self::process_pending_chat_alerts();
			return array(
				'time'      => time(),
				'date_fa'   => date_i18n( 'Y/m/d H:i:s' ),
				'status'    => 'skipped',
				'http_code' => 200,
				'took_sec'  => 0.05,
				'message'   => 'کران قبلی هنوز فعال است؛ برای جلوگیری از بار اضافی اسکرپ رد شد، اما صف اعلان پیام‌ها بررسی و ارسال گردید.',
			);
		}
		set_transient( 'scraper_cron_is_executing', 1, 120 ); // Lock for 2 minutes

		$t0 = microtime( true );
		$scraper_cron_url = plugins_url( 'scraper4.php?cron_run=1', __FILE__ );

		$response = wp_remote_get( $scraper_cron_url, array(
			'timeout'   => 45,
			'blocking'  => true,
			'sslverify' => false,
		) );

		$success     = false;
		$status_code = 0;
		$message     = '';

		if ( ! is_wp_error( $response ) ) {
			$status_code = (int) wp_remote_retrieve_response_code( $response );
			$body        = wp_remote_retrieve_body( $response );
			$data        = @json_decode( $body, true );

			if ( $status_code === 200 ) {
				$success = true;
				$message = ! empty( $data['note'] ) ? $data['note'] : 'کران‌جاب اسکرپر۴ با موفقیت اجرا گردید.';
			} else {
				$message = 'پاسخ HTTP غیرمنتظره با کد وضعیت ' . $status_code;
			}
		} else {
			$message = 'خطای اتصال به اسکرپر: ' . $response->get_error_message();
		}

		$plugin_dir     = plugin_dir_path( __FILE__ );
		$cron_last_file = $plugin_dir . 'cron_last_run.json';
		$cron_last_data = array();
		if ( file_exists( $cron_last_file ) ) {
			$cron_last_data = @json_decode( file_get_contents( $cron_last_file ), true ) ?: array();
		}

		$took_sec = round( microtime( true ) - $t0, 2 );

		$record = array(
			'time'        => time(),
			'date_fa'     => date_i18n( 'Y/m/d H:i:s' ),
			'status'      => $success ? 'success' : 'error',
			'http_code'   => $status_code,
			'took_sec'    => $took_sec,
			'message'     => $message,
			'cron_result' => $cron_last_data,
		);

		update_option( 'scraper_wpcron_last_run', $record );
		delete_transient( 'scraper_cron_is_executing' );
		self::process_pending_chat_alerts();
		return $record;
	}

	/**
	 * Process any pending un-notified chat messages or retries.
	 */
	public static function process_pending_chat_alerts() {
		$threads_file = plugin_dir_path( __FILE__ ) . 'chat_threads.json';
		if ( ! file_exists( $threads_file ) ) {
			return;
		}
		$threads = @json_decode( file_get_contents( $threads_file ), true );
		if ( ! is_array( $threads ) ) {
			return;
		}

		$changed = false;
		$settings = self::get_settings();

		foreach ( $threads as $sid => &$thr ) {
			if ( ! empty( $thr['unread_by_admin'] ) && empty( $thr['last_notified_time'] ) ) {
				$msg_count = count( $thr['messages'] ?? array() );
				$last_msg  = $thr['messages'][ $msg_count - 1 ]['message'] ?? '';
				if ( ! empty( $last_msg ) ) {
					$alert_text = "🚨 یادآوری پیام معلق مشتری (کران‌جاب ۱ دقیقه‌ای):\n"
						. "👤 مشتری: " . ( $thr['name'] ?? 'ناشناس' ) . "\n"
						. "📞 تماس: " . ( $thr['phone'] ?? '—' ) . "\n"
						. "💬 متن پیام: «{$last_msg}»\n"
						. "⏰ زمان ارسال: " . ( $thr['last_time'] ?? '' ) . "\n"
						. "🔗 پاسخ سریع در پیشخوان وردپرس";
					self::send_message_to_messengers( $alert_text, $settings );
					$thr['last_notified_time'] = time();
					$changed = true;
				}
			}
		}
		unset( $thr );

		if ( $changed ) {
			@file_put_contents( $threads_file, json_encode( $threads, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) );
		}
	}

	/**
	 * AJAX endpoint to run scraper cron immediately on admin button click.
	 */
	public static function ajax_run_wpcron_now() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$res = self::execute_scraper_cron_job();

		$next_ts = wp_next_scheduled( 'scraper_auto_shop_cron_sync' );
		$next_human = $next_ts ? human_time_diff( time(), $next_ts ) : 'تنظیم نشده';

		wp_send_json_success( array(
			'message'    => $res['message'],
			'status'     => $res['status'],
			'took_sec'   => $res['took_sec'],
			'last_run'   => $res['date_fa'],
			'next_human' => $next_human,
		) );
	}

	/**
	 * Retrieve WP-Cron status and schedule details for admin display.
	 *
	 * @param array|null $settings
	 * @return array
	 */
	public static function get_wpcron_status_info( $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}

		$hook       = 'scraper_auto_shop_cron_sync';
		$is_enabled = ! empty( $settings['enable_wp_cron_sync'] );
		$interval   = ! empty( $settings['wp_cron_interval'] ) ? $settings['wp_cron_interval'] : 'every_30_mins';
		$next_ts    = wp_next_scheduled( $hook );

		$interval_labels = array(
			'every_minute'  => '⚡ هر ۱ دقیقه یک‌بار (فوق‌سریع - پاسخ آنی به پیام مشتریان)',
			'every_5_mins'  => 'هر ۵ دقیقه یک‌بار (سریع)',
			'every_15_mins' => 'هر ۱۵ دقیقه یک‌بار (بسیار پرسرعت)',
			'every_30_mins' => 'هر ۳۰ دقیقه یک‌بار (توصیه شده)',
			'hourly'        => 'هر ۱ ساعت یک‌بار',
			'every_2_hours' => 'هر ۲ ساعت یک‌بار',
			'every_6_hours' => 'هر ۶ ساعت یک‌بار',
			'twicedaily'    => 'هر ۱۲ ساعت یک‌بار',
			'daily'         => 'روزانه (یک‌بار در ۲۴ ساعت)',
		);

		$last_run = get_option( 'scraper_wpcron_last_run', array() );

		return array(
			'is_active'       => ( $is_enabled && ! empty( $next_ts ) ),
			'is_enabled'      => $is_enabled,
			'interval'        => $interval,
			'interval_label'  => $interval_labels[ $interval ] ?? $interval,
			'next_timestamp'  => $next_ts,
			'next_human'      => $next_ts ? ( 'در ' . human_time_diff( time(), $next_ts ) . ' دیگر' ) : 'تعریف نشده',
			'last_run'        => $last_run,
			'interval_labels' => $interval_labels,
		);
	}

	/**
	 * Whether admin nag/ad suppression is enabled.
	 *
	 * @return bool
	 */
	public static function is_admin_noise_suppression_enabled() {
		$opts = self::get_settings();
		return ! empty( $opts['hide_admin_nags'] );
	}

	/**
	 * Hook admin noise suppression (other plugins' ads, upsells, nags).
	 */
	public static function setup_admin_noise_suppression() {
		if ( ! is_admin() || ! self::is_admin_noise_suppression_enabled() ) {
			return;
		}

		// Strip third-party admin_notices early, keep WP core + our plugin.
		add_action( 'admin_head', array( __CLASS__, 'strip_foreign_admin_notices' ), 0 );
		add_action( 'admin_print_styles', array( __CLASS__, 'print_admin_noise_suppression_css' ), 100 );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'dequeue_common_plugin_promo_assets' ), 999 );

		// Known promotional / upsell hooks used by popular plugins.
		$known_nags = array(
			array( 'admin_notices', 'woothemes_deactivation_notice' ),
			array( 'admin_notices', 'woothemes_tracker_admin_notice' ),
			array( 'admin_notices', 'woocommerce_admin_notices' ),
			array( 'admin_notices', 'wc_admin_add_notice' ),
			array( 'admin_notices', 'elementor_fail_php_version' ),
			array( 'admin_notices', 'elementor_fail_wp_version' ),
			array( 'admin_notices', 'WC_Admin_Notices::output_notices' ),
			array( 'all_admin_notices', 'WC_Admin_Notices::output_notices' ),
			array( 'admin_footer', 'woocommerce_helper_connect_notice' ),
			array( 'admin_notices', 'yoast_display_premium_notice' ),
			array( 'admin_notices', 'wpseo_admin_notices' ),
			array( 'admin_notices', 'rank_math_admin_notice' ),
			array( 'admin_notices', 'aioseo_admin_notices' ),
			array( 'admin_notices', 'monsterinsights_admin_notices' ),
			array( 'admin_notices', 'exactmetrics_admin_notices' ),
			array( 'admin_notices', 'updraftplus_notice' ),
			array( 'admin_notices', 'duplicator_notice' ),
			array( 'admin_notices', 'wpforms_admin_notice' ),
			array( 'admin_notices', 'nf_admin_notice' ),
			array( 'admin_notices', 'give_admin_notices' ),
			array( 'admin_notices', 'edd_admin_notices' ),
			array( 'admin_notices', 'wordfence_admin_notices' ),
			array( 'admin_notices', 'itsec_admin_notices' ),
			array( 'admin_notices', 'sg_cachepress_admin_notice' ),
			array( 'admin_notices', 'litespeed_admin_notice' ),
			array( 'admin_notices', 'w3tc_admin_notices' ),
			array( 'admin_notices', 'rocket_admin_notice' ),
			array( 'admin_notices', 'autoptimize_admin_notice' ),
			array( 'admin_notices', 'revslider_admin_notice' ),
			array( 'admin_notices', 'js_composer_admin_notice' ),
			array( 'admin_notices', 'vc_admin_notice' ),
			array( 'admin_notices', 'mailchimp_admin_notice' ),
			array( 'admin_notices', 'mc4wp_admin_notice' ),
			array( 'admin_notices', 'contact_form_7_admin_notice' ),
			array( 'admin_notices', 'akismet_admin_notice' ),
			array( 'admin_notices', 'hello_dolly_admin_notice' ),
			array( 'admin_footer_text', '__return_empty_string' ),
		);

		foreach ( $known_nags as $pair ) {
			if ( empty( $pair[0] ) || empty( $pair[1] ) || ! is_string( $pair[1] ) ) {
				continue;
			}
			// Class::method string form is not a valid callable for remove_action; skip those.
			if ( false !== strpos( $pair[1], '::' ) ) {
				continue;
			}
			// Try common priorities without wiping the entire hook.
			foreach ( array( 1, 5, 10, 15, 20, 99, 100, 999 ) as $prio ) {
				remove_action( $pair[0], $pair[1], $prio );
				remove_filter( $pair[0], $pair[1], $prio );
			}
		}

		// Hide WooCommerce marketplace / inbox ads when possible.
		add_filter( 'woocommerce_allow_marketplace_suggestions', '__return_false', 999 );
		add_filter( 'woocommerce_helper_suppress_admin_notices', '__return_true', 999 );
		add_filter( 'woocommerce_show_admin_notice', '__return_false', 999 );
		add_filter( 'woocommerce_admin_features', array( __CLASS__, 'filter_wc_admin_features_disable_ads' ), 999 );
		add_filter( 'elementor/admin-top-bar/is-active', '__return_false', 999 );
		add_filter( 'wpseo_enable_notification_post_crawl_cleanup', '__return_false', 999 );
		add_filter( 'rank_math/admin/admin_notices', '__return_empty_array', 999 );
		add_filter( 'pre_option_wpseo_feature_toggles', array( __CLASS__, 'filter_yoast_promo_off' ), 999 );
		add_filter( 'site_transient_update_plugins', array( __CLASS__, 'filter_strip_plugin_update_ads' ), 999 );
	}

	/**
	 * Disable WooCommerce Admin marketing/inbox features.
	 *
	 * @param array $features
	 * @return array
	 */
	public static function filter_wc_admin_features_disable_ads( $features ) {
		if ( ! is_array( $features ) ) {
			return $features;
		}
		$block = array( 'marketing', 'onboarding', 'onboarding-tasks', 'remote-inbox-notifications', 'remote-free-extensions', 'store-alerts' );
		return array_values( array_diff( $features, $block ) );
	}

	/**
	 * Soft-disable Yoast promo flags if option is an array.
	 *
	 * @param mixed $val
	 * @return mixed
	 */
	public static function filter_yoast_promo_off( $val ) {
		return $val;
	}

	/**
	 * Leave update list intact; placeholder for future ad-payload stripping.
	 *
	 * @param mixed $value
	 * @return mixed
	 */
	public static function filter_strip_plugin_update_ads( $value ) {
		return $value;
	}

	/**
	 * Remove third-party callbacks from admin_notices hooks; keep core + this plugin.
	 */
	public static function strip_foreign_admin_notices() {
		if ( ! self::is_admin_noise_suppression_enabled() ) {
			return;
		}

		$hooks = array( 'admin_notices', 'all_admin_notices', 'network_admin_notices', 'user_admin_notices' );
		$keep_substrings = array(
			'Scraper_Auto_Shop',
			'scraper_',
			'update_nag',
			'maintenance_nag',
			'wp_try_auto_update',
			'wp_print_admin_notice_templates',
			'core_update',
			'export_privacy',
			'personal_data',
			'site_admin_notice',
		);

		foreach ( $hooks as $hook ) {
			global $wp_filter;
			if ( empty( $wp_filter[ $hook ] ) || ! is_object( $wp_filter[ $hook ] ) ) {
				continue;
			}

			$callbacks = $wp_filter[ $hook ]->callbacks ?? array();
			foreach ( $callbacks as $priority => $group ) {
				if ( ! is_array( $group ) ) {
					continue;
				}
				foreach ( $group as $id => $item ) {
					$cb = $item['function'] ?? null;
					$label = '';
					if ( is_string( $cb ) ) {
						$label = $cb;
					} elseif ( is_array( $cb ) ) {
						if ( is_object( $cb[0] ) ) {
							$label = get_class( $cb[0] ) . '::' . (string) ( $cb[1] ?? '' );
						} else {
							$label = (string) ( $cb[0] ?? '' ) . '::' . (string) ( $cb[1] ?? '' );
						}
					} elseif ( $cb instanceof Closure ) {
						// Drop anonymous closures from other plugins (often nags).
						$ref = new \ReflectionFunction( $cb );
						$file = (string) $ref->getFileName();
						if ( $file && false === strpos( $file, 'wp-admin' ) && false === strpos( $file, 'wp-includes' ) && false === strpos( $file, 'agent.php' ) && false === strpos( $file, 'scraper' ) ) {
							remove_action( $hook, $cb, $priority );
						}
						continue;
					}

					$keep = false;
					foreach ( $keep_substrings as $needle ) {
						if ( $label && false !== stripos( $label, $needle ) ) {
							$keep = true;
							break;
						}
					}

					// Keep plain core function names that live in wp-admin.
					if ( ! $keep && is_string( $cb ) && function_exists( $cb ) ) {
						try {
							$ref = new \ReflectionFunction( $cb );
							$file = (string) $ref->getFileName();
							if ( $file && ( false !== strpos( $file, 'wp-admin' ) || false !== strpos( $file, 'wp-includes' ) ) ) {
								$keep = true;
							}
						} catch ( \Throwable $e ) {
							// ignore
						}
					}

					if ( ! $keep && $cb ) {
						remove_action( $hook, $cb, $priority );
					}
				}
			}
		}
	}

	/**
	 * CSS to hide leftover promo banners / nags in wp-admin.
	 */
	public static function print_admin_noise_suppression_css() {
		if ( ! self::is_admin_noise_suppression_enabled() ) {
			return;
		}
		?>
		<style id="scraper-admin-noise-suppression">
			/* Generic plugin promo / upsell banners */
			.notice[class*="promo"],
			.notice[class*="upsell"],
			.notice[class*="offer"],
			.notice[class*="advert"],
			.notice[class*="cross-sell"],
			.notice[class*="go-premium"],
			.notice[class*="go_premium"],
			.notice[class*="is-dismissible"][data-slug],
			.notice-info a[href*="upgrade"],
			.notice-warning a[href*="pricing"],
			.update-nag[class*="premium"],
			.yoast-notice-maintained,
			.yoast-container,
			.rank-math-notice,
			.aioseo-review-plugin-cta,
			.monsterinsights-notice,
			.exactmetrics-notice,
			.wpforms-admin-notice,
			.elementor-message,
			.e-notice--dismissible.e-notice--extended,
			.woocommerce-message.woocommerce-tracker,
			.woocommerce-message[data-nonce],
			.woocommerce-store-alerts,
			.wc-admin-notice,
			.woo-connect-notice,
			.wc-marketing,
			#woothemes-helper,
			#wc-marketplace-suggestions,
			.fs-notice,
			.fs-slug,
			.fs-type-plugin,
			div[class*="freemius"],
			.ngg-notice,
			.jitm-banner,
			.jetpack-jitm-message,
			.wp-mail-smtp-review-notice,
			.ithemes-message,
			.wordfenceStrong2FANotice,
			.sg-notice,
			.litespeed-notice-center,
			.notice.notice-success.w3tc_note,
			.revslider-notice,
			.vc_license-activation-notice,
			.acf-admin-notice,
			.cmb2-notice,
			.redux-message,
			.tgmpa-notice,
			#setting-error-tgmpa,
			.notice-error.afwp {
				/* placeholder selector kept for future targeting */
			}
			/* Targeted hide list */
			.fs-notice, .fs-secure-notice, div.fs-notice,
			.elementor-message-dismissed, .e-notice.e-notice--dismissible,
			.rank-math-notice, .yoast-notice, .yoast-container.yoast-container__alert,
			.woocommerce-store-alerts, .woocommerce-marketplace-suggestions,
			.wc-admin-marketplace-notice, .woo-connect-notice,
			.jitm-card, .jetpack-jitm-message, .jp-license-notice,
			.monsterinsights-notice, .exactmetrics-notice,
			.wpforms-review-notice, .wpforms-admin-notice,
			.aioseo-review-plugin-cta, .aioseo-notifications,
			.sg-admin-notice, .litespeed-callout, .notice.wpr-notice,
			.updraft-ad-container, .duplicator-notice,
			.gf-notice, .give-notice, .edd-notice,
			.wordfence-onboarding, .itsec-notice,
			.notice.seedprod-notice, .otgs-notice,
			.wpml-notice, .wcml-notice,
			.persiandate-notice, .pdate-notice,
			.notice[class*="license"], .notice[class*="premium-"],
			.notice[class*="-upsell"], .notice[class*="cross-promo"],
			#adminmenu .update-plugins .plugin-count[data-upsell],
			.wp-pointer.wp-pointer-top[id*="promo"] {
				display: none !important;
				visibility: hidden !important;
				height: 0 !important;
				overflow: hidden !important;
				margin: 0 !important;
				padding: 0 !important;
				border: 0 !important;
			}
		</style>
		<?php
	}

	/**
	 * Dequeue known promo styles/scripts from other plugins in admin.
	 */
	public static function dequeue_common_plugin_promo_assets() {
		if ( ! self::is_admin_noise_suppression_enabled() ) {
			return;
		}
		$handles = array(
			'woocommerce-admin-app',
			'wc-admin-app',
			'elementor-admin-top-bar',
			'elementor-notice',
			'yoast-seo-premium-promotion',
			'rank-math-promo',
			'monsterinsights-admin-notice',
			'fs_common',
			'freemius',
		);
		foreach ( $handles as $h ) {
			wp_dequeue_style( $h );
			wp_dequeue_script( $h );
			wp_deregister_style( $h );
			wp_deregister_script( $h );
		}
	}

	/**
	 * Option key for native WP hit counters.
	 */
	const WP_CORE_STATS_OPTION = 'scraper_wp_core_hit_stats';

	/**
	 * Read native WordPress hit stats option.
	 *
	 * @return array
	 */
	public static function get_wp_core_hit_stats() {
		$data = get_option( self::WP_CORE_STATS_OPTION, array() );
		if ( ! is_array( $data ) ) {
			$data = array();
		}
		if ( empty( $data['totals'] ) || ! is_array( $data['totals'] ) ) {
			$data['totals'] = array(
				'site_visit'   => 0,
				'product_view' => 0,
			);
		}
		if ( empty( $data['daily'] ) || ! is_array( $data['daily'] ) ) {
			$data['daily'] = array();
		}
		return $data;
	}

	/**
	 * Persist native WP hit stats.
	 *
	 * @param array $data
	 */
	public static function save_wp_core_hit_stats( $data ) {
		update_option( self::WP_CORE_STATS_OPTION, $data, false );
	}

	/**
	 * Track a front-end hit into WordPress options (core-side counter).
	 * Skips bots, admin, AJAX, cron, REST and preview.
	 */
	public static function maybe_track_wp_core_hit() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}
		if ( is_preview() || is_feed() || is_robots() || is_trackback() ) {
			return;
		}
		if ( ! empty( $_SERVER['HTTP_USER_AGENT'] ) ) {
			$ua = strtolower( (string) $_SERVER['HTTP_USER_AGENT'] );
			$bots = array( 'bot', 'spider', 'crawl', 'slurp', 'facebookexternalhit', 'preview', 'pingdom', 'gtmetrix', 'lighthouse', 'headless' );
			foreach ( $bots as $b ) {
				if ( false !== strpos( $ua, $b ) ) {
					return;
				}
			}
		}

		// One hit per session per day (cookie) to avoid refresh inflation.
		$cookie = 'scraper_wp_hit_' . date( 'Ymd' );
		if ( ! empty( $_COOKIE[ $cookie ] ) ) {
			return;
		}
		if ( ! headers_sent() ) {
			setcookie( $cookie, '1', time() + DAY_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
		}
		$_COOKIE[ $cookie ] = '1';

		$data  = self::get_wp_core_hit_stats();
		$today = date( 'Y-m-d' );
		$data['totals']['site_visit'] = intval( $data['totals']['site_visit'] ?? 0 ) + 1;
		if ( empty( $data['daily'][ $today ] ) || ! is_array( $data['daily'][ $today ] ) ) {
			$data['daily'][ $today ] = array( 'site_visit' => 0, 'product_view' => 0 );
		}
		$data['daily'][ $today ]['site_visit'] = intval( $data['daily'][ $today ]['site_visit'] ?? 0 ) + 1;
		self::save_wp_core_hit_stats( $data );
	}

	/**
	 * Track WooCommerce single product view into WP core hit stats.
	 */
	public static function maybe_track_wp_product_view() {
		if ( is_admin() || wp_doing_ajax() ) {
			return;
		}
		// Deduplicate per product per day via cookie.
		$pid = 0;
		if ( function_exists( 'is_product' ) && is_product() ) {
			$pid = intval( get_queried_object_id() );
		}
		$cookie = 'scraper_wp_pv_' . ( $pid ? $pid . '_' : '' ) . date( 'Ymd' );
		if ( ! empty( $_COOKIE[ $cookie ] ) ) {
			return;
		}
		if ( ! headers_sent() ) {
			setcookie( $cookie, '1', time() + DAY_IN_SECONDS, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, is_ssl(), true );
		}
		$_COOKIE[ $cookie ] = '1';

		$data  = self::get_wp_core_hit_stats();
		$today = date( 'Y-m-d' );
		$data['totals']['product_view'] = intval( $data['totals']['product_view'] ?? 0 ) + 1;
		if ( empty( $data['daily'][ $today ] ) || ! is_array( $data['daily'][ $today ] ) ) {
			$data['daily'][ $today ] = array( 'site_visit' => 0, 'product_view' => 0 );
		}
		$data['daily'][ $today ]['product_view'] = intval( $data['daily'][ $today ]['product_view'] ?? 0 ) + 1;
		self::save_wp_core_hit_stats( $data );
	}

	/**
	 * Fallback product-view tracker on single product templates.
	 */
	public static function maybe_track_wp_product_view_on_product() {
		if ( function_exists( 'is_product' ) && is_product() ) {
			self::maybe_track_wp_product_view();
		}
	}

	/**
	 * Build analytics payload from WordPress / WooCommerce core data.
	 *
	 * @return array
	 */
	public static function build_wordpress_core_analytics() {
		$hits = self::get_wp_core_hit_stats();

		// Optional: WP Statistics plugin totals.
		$wp_statistics_visits = 0;
		if ( function_exists( 'wp_statistics_visit' ) ) {
			$wp_statistics_visits = intval( wp_statistics_visit( 'total' ) );
		} elseif ( class_exists( 'WP_Statistics_DB' ) || defined( 'WP_STATISTICS_VERSION' ) ) {
			global $wpdb;
			$table = $wpdb->prefix . 'statistics_visit';
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL
			$found = $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) ) );
			if ( $found === $table ) {
				// phpcs:ignore WordPress.DB.DirectDatabaseQuery,WordPress.DB.PreparedSQL
				$wp_statistics_visits = intval( $wpdb->get_var( "SELECT SUM(visit) FROM `{$table}`" ) );
			}
		}

		$site_visits = max(
			intval( $hits['totals']['site_visit'] ?? 0 ),
			$wp_statistics_visits
		);

		$product_views = intval( $hits['totals']['product_view'] ?? 0 );

		// WooCommerce orders & funnels from real DB.
		$orders_placed  = 0;
		$checkout_steps = 0;
		$add_to_cart    = 0;
		$top_products   = array();
		$daily          = array();
		$recent_events  = array();
		$revenue_total  = 0.0;
		$wc_products    = 0;
		$wc_customers   = 0;

		// Seed daily from hit stats (last 30 days).
		for ( $i = 29; $i >= 0; $i-- ) {
			$d = date( 'Y-m-d', strtotime( "-{$i} days" ) );
			$daily[ $d ] = array(
				'site_visit'    => intval( $hits['daily'][ $d ]['site_visit'] ?? 0 ),
				'product_view'  => intval( $hits['daily'][ $d ]['product_view'] ?? 0 ),
				'add_to_cart'   => 0,
				'checkout_step' => 0,
				'order_placed'  => 0,
			);
		}

		if ( class_exists( 'WooCommerce' ) && function_exists( 'wc_get_orders' ) ) {
			// Order counts (paid / processing / completed / on-hold).
			$statuses = array( 'wc-completed', 'wc-processing', 'wc-on-hold', 'wc-pending' );
			if ( function_exists( 'wc_orders_count' ) ) {
				foreach ( array( 'completed', 'processing', 'on-hold' ) as $st ) {
					$orders_placed += intval( wc_orders_count( $st ) );
				}
				$checkout_steps = $orders_placed + intval( wc_orders_count( 'pending' ) ) + intval( wc_orders_count( 'cancelled' ) );
			}

			// Last 100 orders for revenue, daily, recent events, top products.
			$orders = wc_get_orders( array(
				'limit'      => 100,
				'orderby'    => 'date',
				'order'      => 'DESC',
				'status'     => array( 'completed', 'processing', 'on-hold', 'pending' ),
				'return'     => 'objects',
				'paginate'   => false,
			) );

			$product_scores = array();
			if ( is_array( $orders ) ) {
				foreach ( $orders as $order ) {
					if ( ! is_object( $order ) || ! method_exists( $order, 'get_id' ) ) {
						continue;
					}
					$oid    = $order->get_id();
					$total  = floatval( $order->get_total() );
					$status = method_exists( $order, 'get_status' ) ? $order->get_status() : '';
					$date   = method_exists( $order, 'get_date_created' ) && $order->get_date_created()
						? $order->get_date_created()->date( 'Y-m-d' )
						: date( 'Y-m-d' );
					$time   = method_exists( $order, 'get_date_created' ) && $order->get_date_created()
						? $order->get_date_created()->date( 'H:i' )
						: date( 'H:i' );
					$name   = trim( ( method_exists( $order, 'get_formatted_billing_full_name' ) ? $order->get_formatted_billing_full_name() : '' ) );
					if ( '' === $name ) {
						$name = 'مشتری فروشگاه';
					}
					$phone = method_exists( $order, 'get_billing_phone' ) ? (string) $order->get_billing_phone() : '';

					if ( in_array( $status, array( 'completed', 'processing', 'on-hold' ), true ) ) {
						$revenue_total += $total;
						if ( isset( $daily[ $date ] ) ) {
							$daily[ $date ]['order_placed']++;
							$daily[ $date ]['checkout_step']++;
						}
					} else {
						if ( isset( $daily[ $date ] ) ) {
							$daily[ $date ]['checkout_step']++;
						}
					}

					$item_count = 0;
					foreach ( $order->get_items() as $item ) {
						$item_count += intval( $item->get_quantity() );
						$pid = $item->get_product_id();
						$ptitle = $item->get_name();
						if ( ! isset( $product_scores[ $pid ] ) ) {
							$product_scores[ $pid ] = array(
								'title' => $ptitle,
								'views' => 0,
								'carts' => 0,
								'sales' => 0,
							);
						}
						$product_scores[ $pid ]['sales'] += intval( $item->get_quantity() );
						$product_scores[ $pid ]['carts'] += intval( $item->get_quantity() );
						// views stay from real hit tracker only — do not invent from sales.
					}
					$add_to_cart += max( 1, $item_count );

					$recent_events[] = array(
						'id'        => 'wp_order_' . $oid,
						'type'      => in_array( $status, array( 'completed', 'processing', 'on-hold' ), true ) ? 'order_placed' : 'checkout_step',
						'title'     => 'سفارش #' . $oid . ( $total ? ' — ' . self::format_price( $total ) : '' ),
						'details'   => 'وضعیت: ' . $status,
						'amount'    => $total,
						'customer'  => $name . ( $phone ? " ({$phone})" : '' ),
						'time'      => $time,
						'date'      => $date,
						'timestamp' => method_exists( $order, 'get_date_created' ) && $order->get_date_created()
							? $order->get_date_created()->getTimestamp()
							: time(),
						'ip'        => '',
						'source'    => 'wordpress',
					);
				}
			}

			// If wc_orders_count wasn't available, use collected size.
			if ( $orders_placed <= 0 && is_array( $orders ) ) {
				foreach ( $orders as $order ) {
					if ( is_object( $order ) && method_exists( $order, 'get_status' )
						&& in_array( $order->get_status(), array( 'completed', 'processing', 'on-hold' ), true ) ) {
						$orders_placed++;
					}
				}
			}
			if ( $checkout_steps < $orders_placed ) {
				$checkout_steps = max( $orders_placed, $checkout_steps );
			}
			if ( $add_to_cart < $checkout_steps ) {
				$add_to_cart = max( $checkout_steps, $add_to_cart );
			}

			// Top products by sales.
			uasort( $product_scores, function ( $a, $b ) {
				return intval( $b['sales'] ?? 0 ) <=> intval( $a['sales'] ?? 0 );
			} );
			$top_products = array_slice( $product_scores, 0, 12, true );

			// Product count.
			$wc_products = intval( wp_count_posts( 'product' )->publish ?? 0 );

			// Customers (WP users with customer role + guest approximation).
			$cu = count_users();
			$wc_customers = intval( $cu['avail_roles']['customer'] ?? 0 );
		}

		// Fallbacks if WC not present: use WP posts/comments as soft signals.
		$wp_posts     = intval( wp_count_posts( 'post' )->publish ?? 0 );
		$wp_pages     = intval( wp_count_posts( 'page' )->publish ?? 0 );
		$wp_comments  = intval( wp_count_comments()->approved ?? 0 );
		$wp_users     = intval( count_users()['total_users'] ?? 0 );
		$wp_media     = intval( wp_count_posts( 'attachment' )->inherit ?? 0 );

		// No synthetic padding: only real tracked hits, WC orders and WP Statistics.

		return array(
			'totals' => array(
				'site_visit'    => $site_visits,
				'product_view'  => $product_views,
				'add_to_cart'   => $add_to_cart,
				'checkout_step' => $checkout_steps,
				'order_placed'  => $orders_placed,
			),
			'daily'         => $daily,
			'top_products'  => $top_products,
			'recent_events' => array_slice( $recent_events, 0, 40 ),
			'wp_core_meta'  => array(
				'posts'          => $wp_posts,
				'pages'          => $wp_pages,
				'comments'       => $wp_comments,
				'users'          => $wp_users,
				'media'          => $wp_media,
				'wc_products'    => $wc_products,
				'wc_customers'   => $wc_customers,
				'revenue_total'  => $revenue_total,
				'hits_option'    => intval( $hits['totals']['site_visit'] ?? 0 ),
				'wp_statistics'  => $wp_statistics_visits,
				'source_label'   => 'هسته آمار وردپرس / ووکامرس',
			),
			'source' => 'wordpress',
		);
	}

	/**
	 * Merge internal plugin analytics with WordPress core analytics.
	 *
	 * @param array $internal
	 * @param array $wp
	 * @return array
	 */
	public static function merge_analytics_datasets( $internal, $wp ) {
		$out = is_array( $internal ) ? $internal : array();
		$keys = array( 'site_visit', 'product_view', 'add_to_cart', 'checkout_step', 'order_placed' );

		if ( empty( $out['totals'] ) || ! is_array( $out['totals'] ) ) {
			$out['totals'] = array();
		}
		foreach ( $keys as $k ) {
			$iv = intval( $out['totals'][ $k ] ?? 0 );
			$wv = intval( $wp['totals'][ $k ] ?? 0 );
			// Prefer the higher real signal; WP orders usually more trustworthy for order_placed.
			if ( in_array( $k, array( 'order_placed', 'checkout_step' ), true ) ) {
				$out['totals'][ $k ] = max( $iv, $wv );
			} else {
				$out['totals'][ $k ] = max( $iv, $wv );
			}
		}

		// Merge daily (union of dates, max per metric).
		$daily = is_array( $out['daily'] ?? null ) ? $out['daily'] : array();
		foreach ( ( $wp['daily'] ?? array() ) as $d => $row ) {
			if ( empty( $daily[ $d ] ) ) {
				$daily[ $d ] = $row;
				continue;
			}
			foreach ( $keys as $k ) {
				$daily[ $d ][ $k ] = max( intval( $daily[ $d ][ $k ] ?? 0 ), intval( $row[ $k ] ?? 0 ) );
			}
		}
		ksort( $daily );
		$out['daily'] = $daily;

		// Prefer WP recent order events first, then internal.
		$wp_events  = is_array( $wp['recent_events'] ?? null ) ? $wp['recent_events'] : array();
		$int_events = is_array( $out['recent_events'] ?? null ) ? $out['recent_events'] : array();
		$out['recent_events'] = array_slice( array_merge( $wp_events, $int_events ), 0, 80 );

		// Top products: prefer whichever list is richer; merge by title.
		$merged_top = array();
		foreach ( array( $out['top_products'] ?? array(), $wp['top_products'] ?? array() ) as $list ) {
			if ( ! is_array( $list ) ) {
				continue;
			}
			foreach ( $list as $pid => $tp ) {
				$key = is_string( $pid ) || is_int( $pid ) ? (string) $pid : md5( $tp['title'] ?? wp_json_encode( $tp ) );
				if ( ! isset( $merged_top[ $key ] ) ) {
					$merged_top[ $key ] = array(
						'title' => $tp['title'] ?? 'کالا',
						'views' => intval( $tp['views'] ?? 0 ),
						'carts' => intval( $tp['carts'] ?? 0 ),
					);
				} else {
					$merged_top[ $key ]['views'] = max( $merged_top[ $key ]['views'], intval( $tp['views'] ?? 0 ) );
					$merged_top[ $key ]['carts'] = max( $merged_top[ $key ]['carts'], intval( $tp['carts'] ?? 0 ) );
				}
			}
		}
		uasort( $merged_top, function ( $a, $b ) {
			return intval( $b['views'] ?? 0 ) <=> intval( $a['views'] ?? 0 );
		} );
		$out['top_products'] = array_slice( $merged_top, 0, 12, true );
		$out['wp_core_meta'] = $wp['wp_core_meta'] ?? array();
		$out['source']       = 'hybrid';
		return $out;
	}

	/**
	 * Resolve analytics for admin UI based on analytics_source setting.
	 *
	 * @return array
	 */
	public static function get_display_analytics_data() {
		$opts   = self::get_settings();
		$source = $opts['analytics_source'] ?? 'hybrid';
		if ( ! in_array( $source, array( 'internal', 'wordpress', 'hybrid' ), true ) ) {
			$source = 'hybrid';
		}

		$internal = self::get_analytics_data();

		if ( 'internal' === $source ) {
			$internal['source'] = 'internal';
			$internal['wp_core_meta'] = $internal['wp_core_meta'] ?? array();
			return $internal;
		}

		$wp = self::build_wordpress_core_analytics();
		if ( 'wordpress' === $source ) {
			return $wp;
		}

		return self::merge_analytics_datasets( $internal, $wp );
	}

	/**
	 * Get Analytics data (persisted in DB and shared JSON file).
	 *
	 * @return array
	 */
	/**
	 * Empty analytics skeleton (no demo / hardcoded numbers).
	 *
	 * @return array
	 */
	public static function get_empty_analytics_data() {
		return array(
			'totals' => array(
				'site_visit'    => 0,
				'product_view'  => 0,
				'add_to_cart'   => 0,
				'checkout_step' => 0,
				'order_placed'  => 0,
			),
			'daily'          => array(),
			'top_products'   => array(),
			'recent_events'  => array(),
			'wp_core_meta'   => array(),
			'source'         => 'internal',
		);
	}

	/**
	 * Detect legacy seeded/demo analytics payload so it can be wiped.
	 *
	 * @param array $data
	 * @return bool
	 */
	public static function is_hardcoded_demo_analytics( $data ) {
		if ( ! is_array( $data ) ) {
			return false;
		}
		$totals = $data['totals'] ?? array();
		// Exact legacy seed totals.
		if (
			intval( $totals['site_visit'] ?? 0 ) === 3530
			&& intval( $totals['product_view'] ?? 0 ) === 2206
			&& intval( $totals['add_to_cart'] ?? 0 ) === 482
			&& intval( $totals['checkout_step'] ?? 0 ) === 236
			&& intval( $totals['order_placed'] ?? 0 ) === 101
		) {
			return true;
		}
		$top = $data['top_products'] ?? array();
		if ( is_array( $top ) ) {
			$demo_keys = array( 'prod_t800', 'prod_earbuds', 'prod_pbank', 'prod_speaker', 'prod_holder' );
			$hits = 0;
			foreach ( $demo_keys as $dk ) {
				if ( isset( $top[ $dk ] ) ) {
					$hits++;
				}
			}
			if ( $hits >= 3 ) {
				return true;
			}
			// Title-based detection for re-keyed demo products.
			$demo_titles = array(
				'ساعت هوشمند T800',
				'هندزفری بلوتوثی دو گوشی',
				'پاوربانک ۲۰۰۰۰',
				'اسپیکر قابل حمل ضدآب',
				'پایه نگهدارنده و شارژر وایرلس',
			);
			$title_hits = 0;
			foreach ( $top as $tp ) {
				$t = (string) ( $tp['title'] ?? '' );
				foreach ( $demo_titles as $dt ) {
					if ( $t && false !== strpos( $t, $dt ) ) {
						$title_hits++;
						break;
					}
				}
			}
			if ( $title_hits >= 3 ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Persist analytics payload to option + JSON file.
	 *
	 * @param array $data
	 */
	public static function save_analytics_data( $data ) {
		if ( ! is_array( $data ) ) {
			$data = self::get_empty_analytics_data();
		}
		update_option( 'scraper_analytics_data', $data, false );
		$file = plugin_dir_path( __FILE__ ) . 'scraper_analytics.json';
		@file_put_contents( $file, wp_json_encode( $data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE ), LOCK_EX );
	}

	public static function get_analytics_data() {
		$data = get_option( 'scraper_analytics_data', false );
		if ( false === $data || ! is_array( $data ) ) {
			$file = plugin_dir_path( __FILE__ ) . 'scraper_analytics.json';
			if ( file_exists( $file ) ) {
				$data = @json_decode( file_get_contents( $file ), true );
			}
		}

		// Wipe legacy hardcoded demo seed if still present.
		if ( self::is_hardcoded_demo_analytics( $data ) ) {
			$data = self::get_empty_analytics_data();
			self::save_analytics_data( $data );
			return $data;
		}

		if ( ! is_array( $data ) || empty( $data['totals'] ) || ! is_array( $data['totals'] ) ) {
			$data = self::get_empty_analytics_data();
			self::save_analytics_data( $data );
			return $data;
		}

		// Normalize missing keys to zero (never invent demo numbers).
		foreach ( array( 'site_visit', 'product_view', 'add_to_cart', 'checkout_step', 'order_placed' ) as $k ) {
			if ( ! isset( $data['totals'][ $k ] ) ) {
				$data['totals'][ $k ] = 0;
			} else {
				$data['totals'][ $k ] = max( 0, intval( $data['totals'][ $k ] ) );
			}
		}
		if ( empty( $data['daily'] ) || ! is_array( $data['daily'] ) ) {
			$data['daily'] = array();
		}
		if ( empty( $data['top_products'] ) || ! is_array( $data['top_products'] ) ) {
			$data['top_products'] = array();
		}
		if ( empty( $data['recent_events'] ) || ! is_array( $data['recent_events'] ) ) {
			$data['recent_events'] = array();
		}

		return $data;
	}

	/**
	 * Record a live storefront analytics event and dispatch notification if configured.
	 *
	 * @param string $event_type
	 * @param array  $meta
	 * @return array
	 */
	public static function record_analytics_event( $event_type, $meta = array() ) {
		$data     = self::get_analytics_data();
		$now      = time();
		$today    = date( 'Y-m-d' );
		$time_str = date( 'H:i' );

		// Increment totals
		if ( ! isset( $data['totals'][ $event_type ] ) ) {
			$data['totals'][ $event_type ] = 0;
		}
		$data['totals'][ $event_type ]++;

		// Increment daily stats
		if ( ! isset( $data['daily'][ $today ] ) ) {
			$data['daily'][ $today ] = array(
				'site_visit'    => 0,
				'product_view'  => 0,
				'add_to_cart'   => 0,
				'checkout_step' => 0,
				'order_placed'  => 0,
			);
		}
		if ( ! isset( $data['daily'][ $today ][ $event_type ] ) ) {
			$data['daily'][ $today ][ $event_type ] = 0;
		}
		$data['daily'][ $today ][ $event_type ]++;

		// Top products tracking
		if ( ! empty( $meta['product_title'] ) ) {
			$pid = ! empty( $meta['product_id'] ) ? $meta['product_id'] : md5( $meta['product_title'] );
			if ( ! isset( $data['top_products'][ $pid ] ) ) {
				$data['top_products'][ $pid ] = array(
					'title' => $meta['product_title'],
					'views' => 0,
					'carts' => 0,
				);
			}
			if ( 'product_view' === $event_type ) {
				$data['top_products'][ $pid ]['views']++;
			} elseif ( 'add_to_cart' === $event_type ) {
				$data['top_products'][ $pid ]['carts']++;
			}
		}

		// Prepend to recent events
		$event_entry = array(
			'id'        => 'ev_' . $now . '_' . rand( 100, 999 ),
			'type'      => $event_type,
			'title'     => $meta['title'] ?? ( $meta['product_title'] ?? 'رویداد کاربری' ),
			'details'   => $meta['details'] ?? '',
			'amount'    => floatval( $meta['amount'] ?? ( $meta['total'] ?? ( $meta['price'] ?? 0 ) ) ),
			'customer'  => $meta['customer_name'] ?? ( $meta['customer'] ?? 'کاربر مهمان' ),
			'time'      => $time_str,
			'date'      => $today,
			'timestamp' => $now,
			'ip'        => sanitize_text_field( $_SERVER['REMOTE_ADDR'] ?? '' ),
		);

		if ( ! isset( $data['recent_events'] ) || ! is_array( $data['recent_events'] ) ) {
			$data['recent_events'] = array();
		}
		array_unshift( $data['recent_events'], $event_entry );
		if ( count( $data['recent_events'] ) > 80 ) {
			$data['recent_events'] = array_slice( $data['recent_events'], 0, 80 );
		}

		// Persist (real events only)
		self::save_analytics_data( $data );

		// Check and dispatch messenger notifications
		$settings = self::get_settings();
		self::maybe_send_analytics_messenger_notification( $event_type, $meta, $settings );

		return $data;
	}

	/**
	 * Send formatted Persian alert to Bale, Telegram, Rubika on storefront events.
	 *
	 * @param string $event_type
	 * @param array  $meta
	 * @param array  $settings
	 */
	public static function maybe_send_analytics_messenger_notification( $event_type, $meta, $settings ) {
		$key_map = array(
			'site_visit'    => 'notif_event_site_visit',
			'product_view'  => 'notif_event_product_view',
			'add_to_cart'   => 'notif_event_add_to_cart',
			'checkout_step' => 'notif_event_checkout_step',
			'order_placed'  => 'notif_event_order_placed',
		);

		$setting_key = $key_map[ $event_type ] ?? '';
		if ( empty( $setting_key ) || empty( $settings[ $setting_key ] ) ) {
			return;
		}

		$time_str  = date( 'H:i' );
		$shop_name = ! empty( $settings['shop_title'] ) ? $settings['shop_title'] : 'فروشگاه آنلاین';
		$msg       = '';

		switch ( $event_type ) {
			case 'site_visit':
				$msg = "🌐 [{$shop_name}] ورود بازدیدکننده جدید به ویترین فروشگاه
⏰ زمان: {$time_str}
🔗 آدرس: " . home_url();
				break;

			case 'product_view':
				$p_title = $meta['product_title'] ?? 'کالای فروشگاه';
				$p_price = ! empty( $meta['price'] ) ? self::format_price( $meta['price'] ) : '';
				$msg     = "👁️ [{$shop_name}] مشاهده محصول توسط مشتری
📦 کالا: {$p_title}
" . ( $p_price ? "💰 قیمت: {$p_price}
" : '' ) . "⏰ زمان: {$time_str}";
				break;

			case 'add_to_cart':
				$p_title = $meta['product_title'] ?? 'کالای فروشگاه';
				$qty     = max( 1, intval( $meta['qty'] ?? 1 ) );
				$p_price = ! empty( $meta['price'] ) ? self::format_price( $meta['price'] * $qty ) : '';
				$msg     = "🛒 [{$shop_name}] افزودن به سبد خرید!
📦 کالا: {$p_title}
🔢 تعداد: " . self::to_fa_num( $qty ) . "
" . ( $p_price ? "💰 مبلغ کل کالا: {$p_price}
" : '' ) . "⏰ زمان: {$time_str}";
				break;

			case 'checkout_step':
				$total = ! empty( $meta['total'] ) ? self::format_price( $meta['total'] ) : '';
				$count = ! empty( $meta['count'] ) ? self::to_fa_num( $meta['count'] ) : '۱';
				$msg   = "💳 [{$shop_name}] مشتری وارد مرحله تسویه‌حساب شد!
🛍️ تعداد اقلام: {$count} قلم
" . ( $total ? "💵 جمع کل سبد خرید: {$total}
" : '' ) . "⏰ زمان: {$time_str}
(مرحله آماده پرداخت - احتمال بالای نهایی‌سازی)";
				break;

			case 'order_placed':
				$order_id = $meta['order_id'] ?? ( '#' . rand( 1000, 9999 ) );
				$total    = ! empty( $meta['amount'] ) ? self::format_price( $meta['amount'] ) : ( ! empty( $meta['total'] ) ? self::format_price( $meta['total'] ) : '' );
				$c_name   = $meta['customer_name'] ?? ( $meta['customer'] ?? 'مشتری فروشگاه' );
				$c_phone  = $meta['customer_phone'] ?? '';
				$msg      = "🎉 [{$shop_name}] ثبت سفارش جدید در فروشگاه!
🔖 شماره سفارش: {$order_id}
👤 مشتری: {$c_name}" . ( $c_phone ? " ({$c_phone})" : '' ) . "
" . ( $total ? "💵 مبلغ نهایی: {$total}
" : '' ) . "⏰ زمان: {$time_str}
🚀 لطفاً جهت آماده‌سازی، بسته‌بندی و ارسال اقدام فرمایید.";
				break;
		}

		if ( ! empty( $msg ) ) {
			try {
				self::send_message_to_messengers( $msg, $settings );
			} catch ( \Throwable $e ) {
				// Never break storefront/checkout because of messenger failure.
			}
		}
	}

	/**
	 * AJAX endpoint for frontend client-side tracking.
	 */
	public static function ajax_track_analytics_event() {
		$event_type = sanitize_text_field( $_POST['event_type'] ?? '' );
		$allowed    = array( 'site_visit', 'product_view', 'add_to_cart', 'checkout_step', 'order_placed' );
		if ( ! in_array( $event_type, $allowed, true ) ) {
			wp_send_json_error( 'نوع رویداد نامعتبر است.' );
		}

		$meta = array(
			'product_id'     => sanitize_text_field( $_POST['product_id'] ?? '' ),
			'product_title'  => sanitize_text_field( $_POST['product_title'] ?? '' ),
			'price'          => floatval( $_POST['price'] ?? 0 ),
			'qty'            => intval( $_POST['qty'] ?? 1 ),
			'total'          => floatval( $_POST['total'] ?? 0 ),
			'amount'         => floatval( $_POST['amount'] ?? 0 ),
			'count'          => intval( $_POST['count'] ?? 1 ),
			'order_id'       => sanitize_text_field( $_POST['order_id'] ?? '' ),
			'customer_name'  => sanitize_text_field( $_POST['customer_name'] ?? '' ),
			'customer_phone' => sanitize_text_field( $_POST['customer_phone'] ?? '' ),
			'details'        => sanitize_text_field( $_POST['details'] ?? '' ),
		);

		self::record_analytics_event( $event_type, $meta );
		wp_send_json_success( array( 'ok' => true, 'event' => $event_type ) );
	}

	/**
	 * AJAX endpoint for admin resetting analytics.
	 */
	public static function ajax_reset_analytics() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		delete_option( 'scraper_analytics_data' );
		delete_option( self::WP_CORE_STATS_OPTION );
		$file = plugin_dir_path( __FILE__ ) . 'scraper_analytics.json';
		if ( file_exists( $file ) ) {
			@unlink( $file );
		}

		// Persist a real empty skeleton (no demo numbers).
		self::save_analytics_data( self::get_empty_analytics_data() );
		wp_send_json_success( array( 'ok' => true, 'message' => 'آمار فروشگاه با موفقیت بازنشانی شد.' ) );
	}

	/**
	 * WooCommerce hook when a real order is placed.
	 */
	public static function on_wc_order_created( $order_id, $order = null ) {
		if ( ! $order && function_exists( 'wc_get_order' ) ) {
			$order = wc_get_order( $order_id );
		}
		$total = $order ? $order->get_total() : 0;
		$name  = $order ? ( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ) : '';
		$phone = $order ? $order->get_billing_phone() : '';
		self::record_analytics_event( 'order_placed', array(
			'order_id'       => '#' . $order_id,
			'amount'         => $total,
			'customer_name'  => trim( $name ),
			'customer_phone' => $phone,
			'details'        => 'سفارش آنلاین ووکامرس',
		) );
	}


	/**
	 * v10.100: جزئیات کامل یک محصول برای صفحه/مودال ویترین (توضیح، گالری، …).
	 * بوت اولیه lean است؛ این endpoint سنگین را on-demand می‌آورد.
	 */
	/**
	 * v13.3: پیدا کردن محصول خام در profiles.json + کلید پروفایل (برای ذخیرهٔ AI).
	 *
	 * @param string $id md5(title+link) یا key محصول
	 * @return array{raw:array,profile_key:string,entry_key:string}|null
	 */
	public static function find_raw_scraped_product( $id ) {
		$id = (string) $id;
		if ( $id === '' ) {
			return null;
		}
		$profiles_file = self::find_profiles_json_path();
		if ( $profiles_file === '' || ! file_exists( $profiles_file ) ) {
			return null;
		}
		$data = @json_decode( (string) @file_get_contents( $profiles_file ), true );
		if ( ! is_array( $data ) ) {
			return null;
		}
		foreach ( $data as $p_key => $p_item ) {
			if ( ! is_array( $p_item ) ) {
				continue;
			}
			$raw_prods = $p_item['products'] ?? array();
			if ( ! is_array( $raw_prods ) ) {
				continue;
			}
			foreach ( $raw_prods as $ek => $entry ) {
				$prod = null;
				$entry_is_pair = false;
				if ( is_array( $entry ) ) {
					if ( isset( $entry[1] ) && is_array( $entry[1] ) ) {
						$prod = $entry[1];
						$entry_is_pair = true;
					} elseif ( isset( $entry['title'] ) || isset( $entry['name'] ) ) {
						$prod = $entry;
					}
				}
				if ( ! is_array( $prod ) ) {
					continue;
				}
				$title = trim( (string) ( $prod['title'] ?? $prod['name'] ?? '' ) );
				$link  = (string) ( $prod['link'] ?? $prod['url'] ?? '' );
				$hash  = md5( $title . $link );
				$pkey  = (string) ( $prod['key'] ?? ( $entry_is_pair ? (string) ( $entry[0] ?? '' ) : (string) $ek ) );
				if ( $hash === $id || $pkey === $id || (string) ( $prod['id'] ?? '' ) === $id ) {
					return array(
						'raw'         => $prod,
						'profile_key' => (string) $p_key,
						'entry_key'   => $ek,
						'entry_pair'  => $entry_is_pair,
						'pair_key'    => $entry_is_pair ? (string) ( $entry[0] ?? $pkey ) : $pkey,
					);
				}
			}
		}
		return null;
	}

	/**
	 * v13.3: ذخیرهٔ فیلدهای پرشدهٔ AI روی profiles.json.
	 *
	 * @param array $loc  خروجی find_raw_scraped_product
	 * @param array $prod محصول به‌روز
	 * @return bool
	 */
	public static function save_raw_scraped_product( $loc, $prod ) {
		if ( ! is_array( $loc ) || ! is_array( $prod ) ) {
			return false;
		}
		$profiles_file = self::find_profiles_json_path();
		if ( $profiles_file === '' || ! file_exists( $profiles_file ) ) {
			return false;
		}
		$fp = @fopen( $profiles_file, 'c+' );
		if ( ! $fp ) {
			return false;
		}
		if ( ! flock( $fp, LOCK_EX ) ) {
			fclose( $fp );
			return false;
		}
		$raw = stream_get_contents( $fp );
		$data = @json_decode( (string) $raw, true );
		if ( ! is_array( $data ) ) {
			flock( $fp, LOCK_UN );
			fclose( $fp );
			return false;
		}
		$pk = $loc['profile_key'];
		$ek = $loc['entry_key'];
		if ( ! isset( $data[ $pk ]['products'] ) || ! is_array( $data[ $pk ]['products'] ) ) {
			flock( $fp, LOCK_UN );
			fclose( $fp );
			return false;
		}
		$entry = $data[ $pk ]['products'][ $ek ] ?? null;
		if ( ! empty( $loc['entry_pair'] ) && is_array( $entry ) && isset( $entry[1] ) ) {
			$data[ $pk ]['products'][ $ek ] = array( $loc['pair_key'], $prod );
		} elseif ( is_array( $entry ) ) {
			$data[ $pk ]['products'][ $ek ] = $prod;
		} else {
			flock( $fp, LOCK_UN );
			fclose( $fp );
			return false;
		}
		$json = wp_json_encode( $data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		if ( ! is_string( $json ) || $json === '' ) {
			flock( $fp, LOCK_UN );
			fclose( $fp );
			return false;
		}
		ftruncate( $fp, 0 );
		rewind( $fp );
		fwrite( $fp, $json );
		fflush( $fp );
		flock( $fp, LOCK_UN );
		fclose( $fp );
		return true;
	}

	/**
	 * v13.3: اگر توضیح خالی است، با موتور AI اسکرپر۴ فوری پر کن (on-view).
	 *
	 * @param array $raw محصول خام پروفایل
	 * @return array{product:array,filled:bool,ai:bool,error?:string}
	 */

	/**
	 * v13.3.8: decode entity-escaped AI HTML so PDP never shows &lt;p&gt; literally.
	 *
	 * @param string $html Raw AI / stored description.
	 * @return string Safe HTML fragment.
	 */
	public static function sanitize_product_description_html( $html ) {
		$html = trim( (string) $html );
		if ( $html === '' ) {
			return '';
		}
		if ( function_exists( 'aiSanitizeDescriptionHtml' ) ) {
			return aiSanitizeDescriptionHtml( $html );
		}
		$html = preg_replace( '/^```(?:html)?\s*/i', '', $html );
		$html = preg_replace( '/\s*```$/', '', $html );
		for ( $i = 0; $i < 4; $i++ ) {
			$prev = $html;
			$html = html_entity_decode( $html, ENT_QUOTES | ENT_HTML5, 'UTF-8' );
			if ( $html === $prev ) {
				break;
			}
		}
		$html = str_replace( array( "\r\n", "\r", '&#13;', '&#x0D;', '&#xd;' ), "\n", $html );
		$html = preg_replace( '/\n{3,}/u', "\n\n", $html );
		$allowed = '<p><br><br/><ul><ol><li><strong><b><em><i><u><h3><h4><h5><div><span>';
		$html = strip_tags( $html, $allowed );
		$html = preg_replace( '/\s(on\w+|style)\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/iu', '', $html );
		$html = trim( $html );
		if ( $html !== '' && strpos( $html, '<' ) === false ) {
			$parts = preg_split( '/\n{2,}/u', $html ) ?: array( $html );
			$buf = '';
			foreach ( $parts as $part ) {
				$part = trim( $part );
				if ( $part === '' ) {
					continue;
				}
				$buf .= '<p>' . esc_html( $part ) . '</p>';
			}
			$html = $buf !== '' ? $buf : ( '<p>' . esc_html( $html ) . '</p>' );
		}
		return $html;
	}

	public static function maybe_ai_fill_product_on_view( $raw ) {
		$out = array( 'product' => $raw, 'filled' => false, 'ai' => false );
		if ( ! is_array( $raw ) ) {
			return $out;
		}
		$desc = trim( wp_strip_all_tags( (string) ( $raw['long_desc'] ?? $raw['longDesc'] ?? $raw['description'] ?? $raw['desc'] ?? '' ) ) );
		$short = trim( wp_strip_all_tags( (string) ( $raw['short_desc'] ?? $raw['shortDesc'] ?? $raw['brief'] ?? '' ) ) );
		$need = ( $desc === '' && $short === '' ) || mb_strlen( $desc !== '' ? $desc : $short ) < 40;
		if ( ! $need ) {
			return $out;
		}
		// جلوگیری از اسپم AI روی یک محصول در چند ثانیه
		$lock_key = 'amphp_pdp_ai_' . md5( (string) ( $raw['title'] ?? $raw['name'] ?? '' ) . (string) ( $raw['link'] ?? '' ) );
		if ( get_transient( $lock_key ) ) {
			$out['error'] = 'in_progress';
			return $out;
		}
		set_transient( $lock_key, 1, 90 );

		$filled_prod = null;
		$err = '';
		try {
			if ( self::load_scraper_ai_engine() && function_exists( 'aiFillProductContent' ) ) {
				$r = aiFillProductContent( $raw, true, false, null );
				if ( ! empty( $r['filled'] ) && is_array( $r['product'] ?? null ) ) {
					$filled_prod = $r['product'];
					$out['ai'] = true;
				} elseif ( ! empty( $r['error'] ) ) {
					$err = (string) $r['error'];
				}
			}
		} catch ( \Throwable $e ) {
			$err = $e->getMessage();
		}

		// fallback: call_ai_api ساده اگر موتور اسکرپر در دسترس نبود
		if ( ! $filled_prod ) {
			try {
				$settings = self::get_settings();
				$master = self::get_scraper_master_ai_model( $settings );
				$title = trim( (string) ( $raw['title'] ?? $raw['name'] ?? '' ) );
				if ( $title !== '' && ! empty( $master['endpoint'] ) ) {
					$prompt = "برای محصول «{$title}» یک توضیح فروشگاهی فارسی بنویس.\n"
						. "خروجی فقط JSON: {\"short_description\":\"...\",\"description_html\":\"<p>...</p>\"}";
					$txt = self::call_ai_api( $master, $prompt, 'سیستم', $settings );
					if ( is_string( $txt ) && $txt !== '' ) {
						$j = null;
						if ( preg_match( '/\{[\s\S]*\}/u', $txt, $m ) ) {
							$j = @json_decode( $m[0], true );
						}
						if ( is_array( $j ) ) {
							$filled_prod = $raw;
							$sh = trim( (string) ( $j['short_description'] ?? '' ) );
							$html = trim( (string) ( $j['description_html'] ?? $j['description'] ?? '' ) );
							if ( $sh !== '' ) {
								$filled_prod['short_desc'] = $sh;
								$filled_prod['shortDesc'] = $sh;
							}
							if ( $html !== '' ) {
								$filled_prod['long_desc'] = $html;
								$filled_prod['longDesc'] = $html;
								$filled_prod['description'] = $html;
							}
							$filled_prod['ai_content_at'] = time();
							$out['ai'] = true;
						}
					}
				}
			} catch ( \Throwable $e2 ) {
				$err = $e2->getMessage();
			}
		}

		delete_transient( $lock_key );

		if ( $filled_prod && is_array( $filled_prod ) ) {
			/* v13.3.8: HTML خام entity-encoded را قبل از ذخیره پاک کن */
			foreach ( array( 'long_desc', 'longDesc', 'description', 'desc' ) as $fk ) {
				if ( ! empty( $filled_prod[ $fk ] ) && is_string( $filled_prod[ $fk ] ) ) {
					$filled_prod[ $fk ] = self::sanitize_product_description_html( $filled_prod[ $fk ] );
				}
			}
			foreach ( array( 'short_desc', 'shortDesc', 'brief' ) as $fk ) {
				if ( ! empty( $filled_prod[ $fk ] ) && is_string( $filled_prod[ $fk ] ) ) {
					$filled_prod[ $fk ] = trim( html_entity_decode( wp_strip_all_tags( $filled_prod[ $fk ] ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
				}
			}
			/* تنوع بلند AI را دور بریز */
			if ( ! empty( $filled_prod['variations'] ) && is_array( $filled_prod['variations'] ) ) {
				$filled_prod['variations'] = array_values( array_filter( array_map( function ( $v ) {
					$v = trim( (string) $v );
					if ( $v === '' || mb_strlen( $v, 'UTF-8' ) > 28 ) {
						return null;
					}
					$w = preg_split( '/\s+/u', $v, -1, PREG_SPLIT_NO_EMPTY ) ?: array();
					return count( $w ) <= 4 ? $v : null;
				}, $filled_prod['variations'] ) ) );
			}
			if ( ! empty( $filled_prod['variation_groups'] ) && is_array( $filled_prod['variation_groups'] ) ) {
				$ng = array();
				foreach ( $filled_prod['variation_groups'] as $g ) {
					if ( ! is_array( $g ) ) {
						continue;
					}
					$name = trim( (string) ( $g['name'] ?? '' ) );
					if ( mb_strlen( $name, 'UTF-8' ) > 24 ) {
						$name = 'ویژگی';
					}
					$vals = array();
					foreach ( (array) ( $g['values'] ?? $g['options'] ?? array() ) as $vv ) {
						$vv = trim( (string) $vv );
						if ( $vv === '' || mb_strlen( $vv, 'UTF-8' ) > 28 ) {
							continue;
						}
						$w = preg_split( '/\s+/u', $vv, -1, PREG_SPLIT_NO_EMPTY ) ?: array();
						if ( count( $w ) <= 4 ) {
							$vals[] = $vv;
						}
					}
					if ( $vals ) {
						$ng[] = array( 'name' => ( $name !== '' ? $name : 'ویژگی' ), 'values' => $vals );
					}
				}
				$filled_prod['variation_groups'] = $ng;
				$parts = array();
				foreach ( $ng as $g ) {
					$parts[] = ( $g['name'] !== 'ویژگی' ? $g['name'] . ': ' : '' ) . implode( '، ', $g['values'] );
				}
				$filled_prod['variations_text'] = implode( ' | ', $parts );
			} elseif ( ! empty( $filled_prod['variations_text'] ) ) {
				$vt = trim( (string) $filled_prod['variations_text'] );
				if ( mb_strlen( $vt, 'UTF-8' ) > 160 ) {
					unset( $filled_prod['variations_text'] );
				}
			}
			$out['product'] = $filled_prod;
			$out['filled'] = true;
		} elseif ( $err !== '' ) {
			$out['error'] = $err;
		}
		return $out;
	}

	public static function ajax_get_product() {
		$id = sanitize_text_field( wp_unslash( $_REQUEST['id'] ?? $_REQUEST['product_id'] ?? '' ) );
		if ( $id === '' ) {
			wp_send_json_error( array( 'message' => 'شناسه محصول خالی است' ), 400 );
		}
		$ai_filled = false;
		$ai_error  = '';
		$wc_id = 0;
		if ( preg_match( '/^wc_(\d+)$/', $id, $mm ) ) {
			$wc_id = (int) $mm[1];
		} elseif ( ctype_digit( $id ) && function_exists( 'wc_get_product' ) ) {
			$wc_id = (int) $id;
		}
		if ( $wc_id > 0 && function_exists( 'wc_get_product' ) ) {
			$wp_prod = wc_get_product( $wc_id );
			if ( $wp_prod ) {
				$price = (float) $wp_prod->get_price();
				$reg_price = (float) $wp_prod->get_regular_price();
				$sale_price = (float) $wp_prod->get_sale_price();
				$has_discount = ( $sale_price > 0 && $reg_price > $sale_price );
				$discount_pct = ( $has_discount && $reg_price > 0 ) ? round( ( ( $reg_price - $sale_price ) / $reg_price ) * 100 ) : 0;
				$img_id = $wp_prod->get_image_id();
				$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'full' ) : '';
				$gallery = $img_url ? array( $img_url ) : array();
				foreach ( (array) $wp_prod->get_gallery_image_ids() as $gid ) {
					$gu = wp_get_attachment_image_url( $gid, 'full' );
					if ( $gu ) { $gallery[] = $gu; }
				}
				$cat_list = function_exists( 'wc_get_product_category_list' ) ? wp_strip_all_tags( wc_get_product_category_list( $wc_id ) ) : '';
				wp_send_json_success( array(
					'product' => array(
						'id' => 'wc_' . $wc_id, 'wc_id' => $wc_id, 'title' => $wp_prod->get_name(),
						'has_price' => ( $price > 0 ), 'price' => $price, 'price_formatted' => number_format( $price ) . ' تومان',
						'old_price' => $has_discount ? $reg_price : 0,
						'old_price_formatted' => $has_discount ? ( number_format( $reg_price ) . ' تومان' ) : '',
						'has_discount' => $has_discount, 'discount_pct' => $discount_pct,
						'image' => $img_url, 'gallery' => $gallery,
						'category' => $cat_list ?: 'کالای عمومی',
						'description' => $wp_prod->get_description() ?: $wp_prod->get_short_description(),
						'short_desc' => $wp_prod->get_short_description(),
						'in_stock' => $wp_prod->is_in_stock(), 'sku' => $wp_prod->get_sku(),
						'source' => 'woocommerce', 'source_label' => 'ووکامرس',
					),
					'ai_filled' => false,
				) );
			}
		}
		// v13.3: اول محصول خام — اگر توضیح خالی، AI فوری پر می‌کند
		$loc = self::find_raw_scraped_product( $id );
		if ( $loc && is_array( $loc['raw'] ?? null ) ) {
			$fill = self::maybe_ai_fill_product_on_view( $loc['raw'] );
			if ( ! empty( $fill['filled'] ) && is_array( $fill['product'] ?? null ) ) {
				self::save_raw_scraped_product( $loc, $fill['product'] );
				$ai_filled = true;
			}
			if ( ! empty( $fill['error'] ) ) {
				$ai_error = (string) $fill['error'];
			}
		}

		$products = self::get_all_scraped_products();
		$found    = null;
		foreach ( (array) $products as $p ) {
			if ( ! is_array( $p ) ) {
				continue;
			}
			$pid = (string) ( $p['id'] ?? '' );
			if ( $pid === $id || (string) ( $p['key'] ?? '' ) === $id ) {
				$found = $p;
				break;
			}
		}
		// اگر get_all هنوز کش قدیمی دارد، از raw پر‌شده بساز
		if ( ! $found && $loc && is_array( $loc['raw'] ?? null ) ) {
			$raw = ! empty( $fill['product'] ) ? $fill['product'] : $loc['raw'];
			$settings = self::get_settings();
			$price_calc = self::calculate_price( $raw, $settings );
			$img = $raw['image'] ?? $raw['img'] ?? '';
			$found = array(
				'id'                  => $id,
				'title'               => trim( (string) ( $raw['title'] ?? $raw['name'] ?? '' ) ),
				'has_price'           => $price_calc['has_price'],
				'price'               => $price_calc['adjusted'],
				'price_formatted'     => $price_calc['formatted'],
				'old_price'           => $price_calc['old_price'],
				'old_price_formatted' => $price_calc['formatted_old'],
				'has_discount'        => $price_calc['has_discount'],
				'discount_pct'        => $price_calc['discount_pct'],
				'image'               => $img,
				'gallery'             => ! empty( $raw['images'] ) && is_array( $raw['images'] ) ? $raw['images'] : ( $img ? array( $img ) : array() ),
				'category'            => (string) ( $raw['category'] ?? 'عمومی' ),
				'description'         => (string) ( $raw['long_desc'] ?? $raw['longDesc'] ?? $raw['description'] ?? '' ),
				'short_desc'          => (string) ( $raw['short_desc'] ?? $raw['shortDesc'] ?? '' ),
				'in_stock'            => true,
				'variations_text'     => (string) ( $raw['variations_text'] ?? '' ),
				'variation_groups'    => is_array( $raw['variation_groups'] ?? null ) ? $raw['variation_groups'] : array(),
				'specs'               => is_array( $raw['specs'] ?? null ) ? $raw['specs'] : array(),
			);
		}
		if ( ! $found ) {
			wp_send_json_error( array( 'message' => 'محصول یافت نشد', 'id' => $id ), 404 );
		}
		// اگر AI پر کرد ولی get_all هنوز توضیح قدیمی دارد — از raw ادغام کن
		if ( $ai_filled && $loc && is_array( $fill['product'] ?? null ) ) {
			$rp = $fill['product'];
			$ld = (string) ( $rp['long_desc'] ?? $rp['longDesc'] ?? $rp['description'] ?? '' );
			$sd = (string) ( $rp['short_desc'] ?? $rp['shortDesc'] ?? '' );
			if ( $ld !== '' ) {
				$found['description'] = $ld;
			}
			if ( $sd !== '' ) {
				$found['short_desc'] = $sd;
			}
			if ( ! empty( $rp['category'] ) ) {
				$found['category'] = (string) $rp['category'];
			}
			if ( ! empty( $rp['variations_text'] ) ) {
				$found['variations_text'] = (string) $rp['variations_text'];
			}
			if ( ! empty( $rp['variation_groups'] ) && is_array( $rp['variation_groups'] ) ) {
				$found['variation_groups'] = $rp['variation_groups'];
			}
		}

		$gallery = array();
		if ( ! empty( $found['gallery'] ) && is_array( $found['gallery'] ) ) {
			foreach ( $found['gallery'] as $g ) {
				$u = esc_url_raw( (string) $g );
				if ( $u !== '' ) {
					$gallery[] = $u;
				}
			}
		}
		if ( ! empty( $found['images'] ) && is_array( $found['images'] ) ) {
			foreach ( $found['images'] as $g ) {
				$u = esc_url_raw( (string) $g );
				if ( $u !== '' && ! in_array( $u, $gallery, true ) ) {
					$gallery[] = $u;
				}
			}
		}
		$img = esc_url_raw( (string) ( $found['image'] ?? '' ) );
		if ( $img !== '' && ! in_array( $img, $gallery, true ) ) {
			array_unshift( $gallery, $img );
		}
		if ( ! $gallery && $img ) {
			$gallery = array( $img );
		}

		$desc = (string) ( $found['description'] ?? $found['long_desc'] ?? $found['longDesc'] ?? $found['desc'] ?? '' );
		$short = (string) ( $found['short_desc'] ?? $found['shortDesc'] ?? '' );
		// v13.3.8: HTML واقعی برای PDP (نه entity-encoded)
		$desc_out = self::sanitize_product_description_html( $desc );
		$short_clean = trim( html_entity_decode( wp_strip_all_tags( $short ), ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
		if ( $desc_out === '' && $short_clean !== '' ) {
			$desc_out = '<p>' . esc_html( $short_clean ) . '</p>';
		}
		$desc_clean = wp_strip_all_tags( $desc_out );

		$specs = array();
		if ( ! empty( $found['specs'] ) && is_array( $found['specs'] ) ) {
			$specs = $found['specs'];
		} elseif ( ! empty( $found['attributes'] ) && is_array( $found['attributes'] ) ) {
			$specs = $found['attributes'];
		}
		$variations_text = (string) ( $found['variations_text'] ?? '' );
		if ( $variations_text === '' && ! empty( $found['variations'] ) && is_array( $found['variations'] ) ) {
			$variations_text = implode( '، ', array_map( 'strval', $found['variations'] ) );
		}
		/* v13.3.8: متن تنوع بلند (جمله) را نشان نده */
		if ( $variations_text !== '' && mb_strlen( $variations_text, 'UTF-8' ) > 160 ) {
			$variations_text = '';
		}
		$variation_groups_out = is_array( $found['variation_groups'] ?? null ) ? $found['variation_groups'] : array();
		if ( $variation_groups_out ) {
			$vg2 = array();
			foreach ( $variation_groups_out as $g ) {
				if ( ! is_array( $g ) ) {
					continue;
				}
				$vals = array();
				foreach ( (array) ( $g['values'] ?? array() ) as $vv ) {
					$vv = trim( (string) $vv );
					if ( $vv !== '' && mb_strlen( $vv, 'UTF-8' ) <= 28 ) {
						$vals[] = $vv;
					}
				}
				if ( $vals ) {
					$vg2[] = array(
						'name'   => mb_substr( trim( (string) ( $g['name'] ?? 'ویژگی' ) ), 0, 24 ),
						'values' => $vals,
					);
				}
			}
			$variation_groups_out = $vg2;
		}

		$out = array(
			'id'                   => (string) ( $found['id'] ?? $id ),
			'title'                => (string) ( $found['title'] ?? '' ),
			'has_price'            => ! empty( $found['has_price'] ),
			'price'                => floatval( $found['price'] ?? 0 ),
			'price_formatted'      => (string) ( $found['price_formatted'] ?? '' ),
			'old_price'            => floatval( $found['old_price'] ?? 0 ),
			'old_price_formatted'  => (string) ( $found['old_price_formatted'] ?? '' ),
			'has_discount'         => ! empty( $found['has_discount'] ),
			'discount_pct'         => intval( $found['discount_pct'] ?? 0 ),
			'image'                => $img,
			'gallery'              => array_values( array_slice( $gallery, 0, 12 ) ),
			'category'             => (string) ( $found['category'] ?? 'عمومی' ),
			'description'          => $desc_out !== '' ? $desc_out : $desc_clean,
			'short_desc'           => $short_clean,
			'in_stock'             => isset( $found['in_stock'] ) ? (bool) $found['in_stock'] : true,
			'specs'                => $specs,
			'variations_text'      => $variations_text,
			'variation_groups'     => isset( $variation_groups_out ) ? $variation_groups_out : ( is_array( $found['variation_groups'] ?? null ) ? $found['variation_groups'] : array() ),
			'ai_filled'            => $ai_filled,
			'ai_pending'           => ( $ai_error === 'in_progress' ),
		);
		wp_send_json_success( array( 'product' => $out ) );
	}

	public static function ajax_submit_support_chat() {
		// Clean buffer so PHP notices never corrupt JSON for the storefront chat widget.
		while ( function_exists( 'ob_get_level' ) && ob_get_level() > 0 ) {
			@ob_end_clean();
		}
		if ( function_exists( 'ob_start' ) ) {
			@ob_start();
		}
		@ini_set( 'display_errors', '0' );
		if ( ! empty( $_POST['nonce'] ) ) {
			check_ajax_referer( 'scraper_support_chat_nonce', 'nonce', false );
		}
		$settings = self::get_settings();

		$session_id = sanitize_text_field( $_POST['session_id'] ?? '' );
		if ( empty( $session_id ) ) {
			$session_id = 'sess_' . wp_generate_password( 16, false );
		}

		$name    = sanitize_text_field( $_POST['name'] ?? '' );
		$phone   = sanitize_text_field( $_POST['phone'] ?? '' );
		$email   = sanitize_email( $_POST['email'] ?? '' );
		$subject = sanitize_text_field( $_POST['subject'] ?? '' );
		$message = sanitize_textarea_field( $_POST['message'] ?? '' );
		$media_items = array(); // v13.3.19
		$product_id    = sanitize_text_field( $_POST['product_id'] ?? '' );
		$product_title = sanitize_text_field( $_POST['product_title'] ?? '' );
		$product_ctx   = sanitize_textarea_field( $_POST['product_context'] ?? '' );
		if ( $product_title !== '' || $product_ctx !== '' ) {
			$ctx_block = "\n\n[زمینه محصول]";
			if ( $product_title !== '' ) {
				$ctx_block .= "\nنام: " . $product_title;
			}
			if ( $product_id !== '' ) {
				$ctx_block .= "\nشناسه: " . $product_id;
			}
			if ( $product_ctx !== '' ) {
				$ctx_block .= "\n" . $product_ctx;
			}
			// پیام کاربر دست‌نخورده برای نمایش؛ زمینه فقط به AI می‌رود
			$GLOBALS['_amphp_chat_product_ctx'] = $ctx_block;
			$GLOBALS['_amphp_chat_product_title'] = $product_title;
			$GLOBALS['_amphp_chat_product_id'] = $product_id;
		}

		$has_upload = ! empty( $_FILES['chat_file']['tmp_name'] );
		$has_media_url = trim( (string) ( $_POST['media_url'] ?? '' ) ) !== '';
		if ( empty( $message ) && ! $has_upload && ! $has_media_url ) {
			wp_send_json_error( 'لطفاً متن پیام، لینک یا فایل خود را ارسال کنید.' );
		}
		if ( empty( $message ) && ( $has_upload || $has_media_url ) ) {
			$message = '📎 پیوست چندرسانه‌ای';
		}

		if ( ! empty( $settings['chat_field_name_enable'] ) && ! empty( $settings['chat_field_name_required'] ) && empty( $name ) ) {
			wp_send_json_error( 'لطفاً نام و نام خانوادگی خود را وارد نمایید.' );
		}
		if ( empty( $name ) ) {
			$name = 'کاربر مهمان';
		}

		if ( ! empty( $settings['chat_field_phone_enable'] ) && ! empty( $settings['chat_field_phone_required'] ) && empty( $phone ) ) {
			wp_send_json_error( 'لطفاً شماره تماس یا موبایل خود را وارد نمایید.' );
		}

		if ( ! empty( $settings['chat_field_email_enable'] ) && ! empty( $settings['chat_field_email_required'] ) && ( empty( $email ) || ! is_email( $email ) ) ) {
			wp_send_json_error( 'لطفاً یک آدرس ایمیل معتبر وارد نمایید.' );
		}

		$threads   = self::get_chat_threads();
		$thread_id = 'th_' . substr( md5( $session_id ), 0, 12 );
		$now_time  = current_time( 'timestamp' );
		$time_str  = date_i18n( 'H:i' );
		$full_time = date_i18n( 'Y/m/d - H:i' );

		// Find existing thread or initialize new
		$thread = null;
		$thread_key = $thread_id;
		if ( isset( $threads[ $thread_id ] ) ) {
			$thread = $threads[ $thread_id ];
		} else {
			foreach ( $threads as $k => $t ) {
				if ( ( $t['session_id'] ?? '' ) === $session_id ) {
					$thread     = $t;
					$thread_key = $k;
					break;
				}
			}
		}

		if ( ! $thread ) {
			$thread = array(
				'id'           => $thread_id,
				'session_id'   => $session_id,
				'name'         => $name,
				'phone'        => $phone,
				'email'        => $email,
				'subject'      => $subject,
				'status'       => 'pending',
				'unread_admin' => true,
				'created_at'   => $full_time,
				'updated_at'   => $now_time,
				'messages'     => array(),
			);
		} else {
			if ( ! empty( $name ) && 'کاربر مهمان' !== $name ) {
				$thread['name'] = $name;
			}
			if ( ! empty( $phone ) ) {
				$thread['phone'] = $phone;
			}
			if ( ! empty( $email ) ) {
				$thread['email'] = $email;
			}
			if ( ! empty( $subject ) ) {
				$thread['subject'] = $subject;
			}
			$thread['status']       = 'pending';
			$thread['unread_admin'] = true;
			$thread['updated_at']   = $now_time;
		}

		// 1. Customer Message
		$client_msg_id = sanitize_text_field( $_POST['client_msg_id'] ?? '' );
		$customer_msg_id = ! empty( $client_msg_id ) ? $client_msg_id : ( 'msg_' . $now_time . '_' . rand( 100, 999 ) );
		$media_items = array();
		$upload_res = self::handle_support_chat_upload();
		if ( ! empty( $upload_res['ok'] ) && ! empty( $upload_res['item'] ) ) {
			$media_items[] = $upload_res['item'];
		}
		// لینک‌های چسبانده‌شده در متن
		foreach ( self::extract_links_as_media( $message ) as $li ) {
			$media_items[] = $li;
		}
		// media_url مستقیم از کلاینت (مثلاً URL عمومی)
		$client_media_url = esc_url_raw( trim( (string) ( $_POST['media_url'] ?? '' ) ) );
		if ( $client_media_url !== '' ) {
			$media_items[] = array(
				'kind'    => self::media_kind_from_meta( $client_media_url ),
				'url'     => $client_media_url,
				'caption' => '',
				'name'    => sanitize_text_field( (string) ( $_POST['media_name'] ?? '' ) ),
			);
		}

		$customer_msg = array(
			'id'          => $customer_msg_id,
			'sender'      => 'customer',
			'sender_name' => ( ! empty( $thread['name'] ) && 'کاربر مهمان' !== $thread['name'] ) ? $thread['name'] : 'شما',
			'text'        => $message,
			'time'        => $time_str,
			'timestamp'   => $now_time,
		);
		if ( $media_items ) {
			$customer_msg['media'] = $media_items;
			$first_url = (string) ( $media_items[0]['url'] ?? '' );
			if ( $first_url !== '' ) {
				$customer_msg['media_url']  = $first_url;
				$customer_msg['media_kind'] = (string) ( $media_items[0]['kind'] ?? '' );
			}
		}
		if ( ! empty( $GLOBALS['_amphp_chat_product_id'] ) || ! empty( $GLOBALS['_amphp_chat_product_title'] ) ) {
			$customer_msg['product_id']    = (string) ( $GLOBALS['_amphp_chat_product_id'] ?? '' );
			$customer_msg['product_title'] = (string) ( $GLOBALS['_amphp_chat_product_title'] ?? '' );
			if ( empty( $thread['subject'] ) && ! empty( $customer_msg['product_title'] ) ) {
				$thread['subject'] = 'سوال درباره: ' . $customer_msg['product_title'];
			}
		}
		$thread['messages'][] = $customer_msg;

		// 2. Generate Master AI Reply if enabled (never let AI failures kill the AJAX JSON)
		$_ai_msg = $message;
		if ( ! empty( $GLOBALS['_amphp_chat_product_ctx'] ) ) {
			$_ai_msg .= (string) $GLOBALS['_amphp_chat_product_ctx'];
		}
		if ( function_exists( 'set_time_limit' ) ) {
			@set_time_limit( 90 );
		}
		if ( function_exists( 'wp_raise_memory_limit' ) ) {
			@wp_raise_memory_limit( 'admin' );
		}
		$ai_reply  = '';
		$master_ai = array();
		$model_lbl = 'هوش مصنوعی پشتیبان';
		try {
			$master_ai = self::get_scraper_master_ai_model( $settings );
			$model_lbl = ! empty( $master_ai['model_name'] ) ? $master_ai['model_name'] : $model_lbl;
			$ai_reply  = self::generate_ai_support_reply( $_ai_msg, $thread['name'], $settings );
		} catch ( \Throwable $e ) {
			error_log( 'AMPHP support chat AI error: ' . $e->getMessage() );
			try {
				$ai_reply = self::generate_smart_local_reply( $_ai_msg, $thread['name'], $settings );
				$model_lbl = 'پاسخ محلی فروشگاه';
			} catch ( \Throwable $e2 ) {
				$ai_reply = '';
			}
		}
		if ( ! is_string( $ai_reply ) ) {
			$ai_reply = '';
		}
		$ai_reply = trim( $ai_reply );
		// Never leave the customer with an empty bot bubble.
		if ( $ai_reply === '' ) {
			$ai_reply = self::generate_smart_local_reply( $_ai_msg, $thread['name'], $settings );
			if ( ! is_string( $ai_reply ) || trim( $ai_reply ) === '' ) {
				$site = function_exists( 'get_bloginfo' ) ? ( get_bloginfo( 'name' ) ?: 'فروشگاه' ) : 'فروشگاه';
				$ai_reply = "سلام! پیام شما در «{$site}» دریافت شد. همکاران پشتیبانی به‌زودی پاسخ می‌دهند. برای پیگیری سریع‌تر می‌توانید شماره تماس خود را همین‌جا بنویسید.";
			}
			if ( $model_lbl === 'هوش مصنوعی پشتیبان' ) {
				$model_lbl = 'پشتیبان فروشگاه';
			}
		}

		if ( ! empty( $ai_reply ) ) {
			$ai_msg = array(
				'id'          => 'msg_' . ( $now_time + 1 ) . '_' . rand( 100, 999 ),
				'sender'      => 'ai',
				'sender_name' => ( $settings['ai_support_name'] ?? 'پشتیبان هوشمند' ) . ' (' . $model_lbl . ')',
				'text'        => $ai_reply,
				'time'        => $time_str,
				'timestamp'   => $now_time + 1,
			);
			$thread['messages'][] = $ai_msg;
		}

		// Save updated thread at the front of the list
		unset( $threads[ $thread_key ] );
		$threads = array( $thread_key => $thread ) + $threads;
		self::save_chat_threads( $threads );

		// 3. Forward Customer Message & AI Reply to Messengers (Bale, Telegram, Rubika)
		$site_name      = get_bloginfo( 'name' ) ?: ( $settings['shop_title'] ?? 'فروشگاه آنلاین' );
		$formatted_text = "💬 پیام جدید چت آنلاین ({$site_name})

"
			. "👤 نام مشتری: {$thread['name']}
";
		if ( ! empty( $thread['phone'] ) ) {
			$formatted_text .= "📱 شماره تماس: {$thread['phone']}
";
		}
		if ( ! empty( $thread['email'] ) ) {
			$formatted_text .= "📧 ایمیل: {$thread['email']}
";
		}
		$formatted_text .= "🕒 زمان: {$full_time}
"
			. "━━━━━━━━━━━━━━━━━━━
"
			. "📝 متن پیام مشتری:
"
			. "{$message}
";

		if ( ! empty( $GLOBALS['_amphp_chat_product_title'] ) ) {
			$formatted_text .= "\n🛍 محصول: " . (string) $GLOBALS['_amphp_chat_product_title'];
			if ( ! empty( $GLOBALS['_amphp_chat_product_id'] ) ) {
				$formatted_text .= " (" . (string) $GLOBALS['_amphp_chat_product_id'] . ")";
			}
			$formatted_text .= "\n";
		}

		if ( ! empty( $ai_reply ) ) {
			$formatted_text .= "━━━━━━━━━━━━━━━━━━━
"
				. "🤖 پاسخ هوش مصنوعی مستر ({$model_lbl}):
"
				. "«{$ai_reply}»

"
				. "━━━━━━━━━━━━━━━━━━━
"
				. "⚡ وضعیت: پاسخ هوشمند برای مشتری ارسال شد. جهت پاسخ مستقیم ادمین، به تب ۳ میز پاسخگویی در پیشخوان مراجعه فرمایید.";
		} else {
			$formatted_text .= "━━━━━━━━━━━━━━━━━━━
"
				. "⚡ وضعیت: در انتظار پاسخ مستقیم ادمین در پیشخوان وردپرس (تب ۳ میز پاسخگویی)";
		}

		if ( ! empty( $media_items ) ) {
			$formatted_text .= "
━━━━━━━━━━━━━━━━━━━
📎 پیوست‌ها:
";
			foreach ( array_slice( $media_items, 0, 8 ) as $_mi ) {
				$_u = (string) ( $_mi['url'] ?? '' );
				$_k = (string) ( $_mi['kind'] ?? 'file' );
				$_n = (string) ( $_mi['name'] ?? '' );
				$formatted_text .= '• ' . $_k . ( $_n !== '' ? " ({$_n})" : '' ) . ( $_u !== '' ? ( "
" . $_u ) : '' ) . "
";
			}
			// caption روی اولین رسانه
			if ( ! empty( $media_items[0] ) && is_array( $media_items[0] ) ) {
				$media_items[0]['caption'] = mb_substr( $message, 0, 200 );
			}
		}
		$send_result = self::send_message_to_messengers( $formatted_text, $settings, $media_items );

		// Legacy logs update
		$logs = get_option( 'scraper_support_chat_logs', array() );
		if ( ! is_array( $logs ) ) {
			$logs = array();
		}
		array_unshift( $logs, array(
			'name'     => $thread['name'],
			'phone'    => $thread['phone'],
			'email'    => $thread['email'],
			'subject'  => $thread['subject'],
			'message'  => $message,
			'ai_reply' => $ai_reply,
			'model'    => $model_lbl,
			'time'     => $full_time,
			'sent_ok'  => $send_result['ok'],
			'sent_to'  => $send_result['sent'],
		) );
		$logs = array_slice( $logs, 0, 50 );
		update_option( 'scraper_support_chat_logs', $logs, false );

		$ai_source = 'live';
		if ( $model_lbl === 'پاسخ محلی فروشگاه' || $model_lbl === 'پشتیبان فروشگاه' ) {
			$ai_source = 'local';
		}
		while ( function_exists( 'ob_get_level' ) && ob_get_level() > 0 ) {
			@ob_end_clean();
		}
		wp_send_json_success( array(
			'message'    => 'ok',
			'session_id' => $session_id,
			'thread_id'  => $thread_id,
			'ai_reply'   => $ai_reply,
			'reply'      => $ai_reply,
			'bot_reply'  => $ai_reply,
			'text'       => $ai_reply,
			'ai_model'   => $model_lbl,
			'ai_source'  => $ai_source,
			'ai_key'     => is_array( $master_ai ) ? ( $master_ai['key'] ?? '' ) : '',
			'thread'     => $thread,
			'status'     => $send_result,
		) );
	}

	/**
	 * AJAX endpoint for customer polling updates for active conversation thread.
	 */
	public static function ajax_customer_get_thread() {
		if ( ! empty( $_POST['nonce'] ) ) { check_ajax_referer( 'scraper_support_chat_nonce', 'nonce', false ); }

		$session_id = sanitize_text_field( $_POST['session_id'] ?? '' );
		if ( empty( $session_id ) ) {
			wp_send_json_error( 'شناسه جلسه خالی است.' );
		}

		$threads = self::get_chat_threads();
		foreach ( $threads as $t ) {
			if ( ( $t['session_id'] ?? '' ) === $session_id ) {
				wp_send_json_success( array( 'thread' => $t ) );
			}
		}

		wp_send_json_success( array( 'thread' => null ) );
	}

	/**
	 * AJAX endpoint for admin sending replies from the WordPress Support Desk.
	 * Dispatches reply to customer's live chat AND forwards to messengers (Bale, Telegram, Rubika).
	 */
	public static function ajax_admin_send_chat_reply() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$settings   = self::get_settings();
		$thread_id  = sanitize_text_field( $_POST['thread_id'] ?? '' );
		$reply_text = sanitize_textarea_field( $_POST['reply_text'] ?? '' );

		if ( empty( $thread_id ) || empty( $reply_text ) ) {
			wp_send_json_error( 'شناسه گفتگو یا متن پاسخ نمی‌تواند خالی باشد.' );
		}

		$threads = self::get_chat_threads();
		$key     = null;
		if ( isset( $threads[ $thread_id ] ) ) {
			$key = $thread_id;
		} else {
			foreach ( $threads as $k => $t ) {
				if ( ( $t['id'] ?? '' ) === $thread_id || ( $t['session_id'] ?? '' ) === $thread_id ) {
					$key = $k;
					break;
				}
			}
		}

		if ( null === $key || ! isset( $threads[ $key ] ) ) {
			wp_send_json_error( 'گفتگوی مورد نظر یافت نشد.' );
		}

		$current_user = wp_get_current_user();
		$admin_name   = $current_user->display_name ?: 'مدیریت فروشگاه';
		$now_time     = current_time( 'timestamp' );
		$time_str     = date_i18n( 'H:i' );

		$admin_msg = array(
			'id'          => 'msg_' . $now_time . '_' . rand( 100, 999 ),
			'sender'      => 'admin',
			'sender_name' => $admin_name,
			'text'        => $reply_text,
			'time'        => $time_str,
			'timestamp'   => $now_time,
		);

		$threads[ $key ]['messages'][]   = $admin_msg;
		$threads[ $key ]['status']       = 'replied';
		$threads[ $key ]['unread_admin'] = false;
		$threads[ $key ]['updated_at']   = $now_time;

		self::save_chat_threads( $threads );

		// Forward admin reply to messengers!
		$site_name    = get_bloginfo( 'name' ) ?: ( $settings['shop_title'] ?? 'فروشگاه آنلاین' );
		$target_name  = ! empty( $threads[ $key ]['name'] ) ? $threads[ $key ]['name'] : 'مشتری';
		$target_phone = ! empty( $threads[ $key ]['phone'] ) ? $threads[ $key ]['phone'] : '';

		$admin_forward_text = "👨‍💼 پاسخ پشتیبان در چت آنلاین ({$site_name})

"
			. "👤 مخاطب: {$target_name}
";
		if ( ! empty( $target_phone ) ) {
			$admin_forward_text .= "📱 تلفن مشتری: {$target_phone}
";
		}
		$admin_forward_text .= "🕒 زمان پاسخ: " . date_i18n( 'Y/m/d - H:i' ) . "
"
			. "✍️ ارسال کننده: {$admin_name}

"
			. "━━━━━━━━━━━━━━━━━━━
"
			. "💬 متن پاسخ همکار / ادمین:
"
			. "{$reply_text}

"
			. "━━━━━━━━━━━━━━━━━━━
"
			. "✅ وضعیت: پاسخ در صفحه چت مشتری ثبت و ارسال گردید.";

		$messenger_status = self::send_message_to_messengers( $admin_forward_text, $settings );

		wp_send_json_success( array(
			'message'          => 'پاسخ شما با موفقیت ثبت شد و به پیام‌رسان‌ها فوروارد گردید.',
			'thread'           => $threads[ $key ],
			'messenger_status' => $messenger_status,
		) );
	}

	public static function ajax_admin_get_thread() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$threads   = self::get_chat_threads();
		$thread_id = sanitize_text_field( $_POST['thread_id'] ?? '' );

		if ( ! empty( $thread_id ) ) {
			foreach ( $threads as $t ) {
				if ( ( $t['id'] ?? '' ) === $thread_id || ( $t['session_id'] ?? '' ) === $thread_id ) {
					wp_send_json_success( array( 'thread' => $t ) );
				}
			}
			wp_send_json_error( 'گفتگو پیدا نشد.' );
		}

		wp_send_json_success( array( 'threads' => array_values( $threads ) ) );
	}

	/**
	 * AJAX endpoint for admin deleting a chat thread.
	 */
	public static function ajax_admin_delete_thread() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$thread_id = sanitize_text_field( $_POST['thread_id'] ?? '' );
		if ( empty( $thread_id ) ) {
			wp_send_json_error( 'شناسه گفتگو ارسال نشده است.' );
		}

		$threads = self::get_chat_threads();
		$found   = false;
		foreach ( $threads as $k => $t ) {
			if ( ( $t['id'] ?? '' ) === $thread_id || ( $t['session_id'] ?? '' ) === $thread_id ) {
				unset( $threads[ $k ] );
				$found = true;
				break;
			}
		}

		if ( $found ) {
			self::save_chat_threads( $threads );
			wp_send_json_success( array( 'message' => 'گفتگو با موفقیت حذف گردید.' ) );
		} else {
			wp_send_json_error( 'گفتگو یافت نشد.' );
		}
	}

	/**
	 * AJAX endpoint for admin testing messenger connection.
	 */
	public static function ajax_test_support_messengers() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$time_str  = date_i18n( 'Y/m/d - H:i:s' );
		$site_name = get_bloginfo( 'name' );

		$test_text = "🔔 پیام آزمایشی سیستم پشتیبانی فروشگاه\n\n"
			. "✅ این یک پیام تستی برای بررسی اتصال به پیام‌رسان‌های بخش اعلان اسکرپر است.\n"
			. "🕒 زمان تست: {$time_str}\n"
			. "🏢 فروشگاه: {$site_name}\n"
			. "وضعیت: اتصال صحیح و بدون مشکل برقرار است.";

		$result = self::send_message_to_messengers( $test_text );

		if ( $result['ok'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result );
		}
	}

	/**
	 * Ensure WooCommerce Cart and Session are initialized in AJAX requests.
	 *
	 * @return bool
	 */
	public static function init_wc_cart() {
		if ( ! function_exists( 'WC' ) ) {
			return false;
		}

		if ( is_null( WC()->session ) ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			if ( class_exists( $session_class ) ) {
				WC()->session = new $session_class();
				WC()->session->init();
			}
		}

		if ( is_null( WC()->customer ) && class_exists( 'WC_Customer' ) ) {
			WC()->customer = new WC_Customer( get_current_user_id(), true );
		}

		if ( is_null( WC()->cart ) && class_exists( 'WC_Cart' ) ) {
			WC()->cart = new WC_Cart();
		}

		if ( WC()->session && ! WC()->session->has_session() ) {
			WC()->session->set_customer_session_cookie( true );
		}

		return ( ! is_null( WC()->cart ) );
	}

	/**
	 * Find an existing WooCommerce product by scraped hash or exact title, or create it dynamically.
	 *
	 * @param array $item_data
	 * @return int Product ID or 0
	 */
	public static function find_or_create_wc_product( $item_data ) {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return 0;
		}

		$hash  = sanitize_text_field( $item_data['id'] ?? $item_data['hash'] ?? '' );
		$title = sanitize_text_field( $item_data['title'] ?? '' );
		$price = floatval( $item_data['price'] ?? 0 );
		$img   = esc_url_raw( $item_data['image'] ?? '' );
		$cat   = sanitize_text_field( $item_data['category'] ?? 'عمومی' );

		if ( empty( $title ) && empty( $hash ) ) {
			return 0;
		}

		$product_id = 0;

		// 1. Try lookup by scraped hash
		if ( ! empty( $hash ) ) {
			$existing = get_posts( array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'meta_key'       => '_scraped_hash',
				'meta_value'     => $hash,
				'fields'         => 'ids',
			) );
			if ( ! empty( $existing ) ) {
				$product_id = (int) $existing[0];
			}
		}

		// 2. Try lookup by exact title
		if ( ! $product_id && ! empty( $title ) ) {
			$existing = get_posts( array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => 1,
				'title'          => $title,
				'fields'         => 'ids',
			) );
			if ( ! empty( $existing ) ) {
				$product_id = (int) $existing[0];
			}
		}

		// 3. If found, ensure price and active stock status
		if ( $product_id > 0 ) {
			if ( $price > 0 ) {
				update_post_meta( $product_id, '_price', $price );
				update_post_meta( $product_id, '_regular_price', $price );
			}
			update_post_meta( $product_id, '_stock_status', 'instock' );
			if ( ! empty( $hash ) ) {
				update_post_meta( $product_id, '_scraped_hash', $hash );
			}
			if ( ! empty( $img ) && ! get_post_meta( $product_id, '_scraped_image_url', true ) ) {
				update_post_meta( $product_id, '_scraped_image_url', $img );
			}
			return $product_id;
		}

		// 4. If not found, create new WooCommerce simple product
		$post_data = array(
			'post_title'   => $title,
			'post_content' => sanitize_textarea_field( $item_data['description'] ?? '' ),
			'post_status'  => 'publish',
			'post_type'    => 'product',
		);
		$product_id = wp_insert_post( $post_data );

		if ( $product_id && ! is_wp_error( $product_id ) ) {
			wp_set_object_terms( $product_id, 'simple', 'product_type' );
			if ( ! empty( $cat ) ) {
				wp_set_object_terms( $product_id, $cat, 'product_cat' );
			}
			update_post_meta( $product_id, '_scraped_hash', $hash );
			update_post_meta( $product_id, '_price', $price );
			update_post_meta( $product_id, '_regular_price', $price );
			update_post_meta( $product_id, '_stock_status', 'instock' );
			update_post_meta( $product_id, '_visibility', 'visible' );
			update_post_meta( $product_id, '_virtual', 'no' );
			update_post_meta( $product_id, '_manage_stock', 'no' );
			if ( ! empty( $img ) ) {
				update_post_meta( $product_id, '_scraped_image_url', $img );
			}
			return (int) $product_id;
		}

		return 0;
	}

	/**
	 * Build JSON response with current WooCommerce Cart contents.
	 *
	 * @return array
	 */
	public static function get_wc_cart_response() {
		if ( ! self::init_wc_cart() ) {
			return array(
				'items'        => array(),
				'count'        => 0,
				'total'        => 0,
				'checkout_url' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#',
				'cart_url'     => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#',
			);
		}

		$items     = array();
		$raw_total = 0;

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$product = $cart_item['data'] ?? null;
			if ( ! $product || ! is_object( $product ) ) {
				continue;
			}

			$pid  = $cart_item['product_id'];
			$hash = get_post_meta( $pid, '_scraped_hash', true );
			if ( empty( $hash ) ) {
				$hash = (string) $pid;
			}
			$img = get_post_meta( $pid, '_scraped_image_url', true );
			if ( empty( $img ) && has_post_thumbnail( $pid ) ) {
				$img = get_the_post_thumbnail_url( $pid, 'thumbnail' );
			}

			$price      = floatval( $product->get_price() );
			$qty        = intval( $cart_item['quantity'] );
			$line_total = $price * $qty;
			$raw_total += $line_total;

			$items[] = array(
				'id'         => $hash,
				'product_id' => $pid,
				'key'        => $cart_item_key,
				'title'      => $product->get_name(),
				'price'      => $price,
				'priceTxt'   => number_format( $price ) . ' تومان',
				'img'        => $img,
				'qty'        => $qty,
				'line_total' => $line_total,
			);
		}

		return array(
			'items'        => $items,
			'count'        => WC()->cart->get_cart_contents_count(),
			'total'        => $raw_total,
			'checkout_url' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#',
			'cart_url'     => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#',
		);
	}

	/**
	 * AJAX endpoint to add scraped product directly into WooCommerce Cart.
	 */
	public static function ajax_wc_add_to_cart() {
		check_ajax_referer( 'scraper_cart_nonce', 'nonce' );

		$hash  = sanitize_text_field( $_POST['id'] ?? '' );
		$title = sanitize_text_field( $_POST['title'] ?? '' );
		$price = floatval( $_POST['price'] ?? 0 );
		$img   = esc_url_raw( $_POST['image'] ?? '' );
		$qty   = max( 1, intval( $_POST['qty'] ?? 1 ) );

		if ( empty( $title ) && empty( $hash ) ) {
			wp_send_json_error( 'اطلاعات محصول نامعتبر است.' );
		}

		if ( ! self::init_wc_cart() ) {
			wp_send_json_error( 'ووکامرس فعال نیست.' );
		}

		$product_id = self::find_or_create_wc_product( array(
			'id'    => $hash,
			'title' => $title,
			'price' => $price,
			'image' => $img,
		) );

		if ( ! $product_id ) {
			wp_send_json_error( 'ثبت محصول در دیتابیس با شکست مواجه شد.' );
		}

		// Find if already in cart
		$cart_item_key = null;
		foreach ( WC()->cart->get_cart() as $key => $item ) {
			if ( (int) $item['product_id'] === (int) $product_id ) {
				$cart_item_key = $key;
				break;
			}
		}

		if ( $cart_item_key ) {
			$current_qty = WC()->cart->get_cart()[ $cart_item_key ]['quantity'];
			WC()->cart->set_quantity( $cart_item_key, $current_qty + $qty );
		} else {
			$cart_item_key = WC()->cart->add_to_cart( $product_id, $qty );
		}

		if ( ! $cart_item_key ) {
			wp_send_json_error( 'افزودن به سبد خرید ووکامرس انجام نشد.' );
		}

		WC()->cart->calculate_totals();

		wp_send_json_success( self::get_wc_cart_response() );
	}

	/**
	 * AJAX endpoint to update quantity of an item in WooCommerce Cart.
	 */
	public static function ajax_wc_update_cart_qty() {
		check_ajax_referer( 'scraper_cart_nonce', 'nonce' );

		$hash = sanitize_text_field( $_POST['id'] ?? '' );
		$qty  = intval( $_POST['qty'] ?? 0 );

		if ( ! self::init_wc_cart() ) {
			wp_send_json_error( 'ووکامرس فعال نیست.' );
		}

		$target_key = null;
		foreach ( WC()->cart->get_cart() as $key => $item ) {
			$pid       = $item['product_id'];
			$item_hash = get_post_meta( $pid, '_scraped_hash', true );
			if ( $item_hash === $hash || (string) $pid === (string) $hash ) {
				$target_key = $key;
				break;
			}
		}

		if ( $target_key ) {
			if ( $qty <= 0 ) {
				WC()->cart->remove_cart_item( $target_key );
			} else {
				WC()->cart->set_quantity( $target_key, $qty );
			}
			WC()->cart->calculate_totals();
		}

		wp_send_json_success( self::get_wc_cart_response() );
	}

	/**
	 * AJAX endpoint to remove an item from WooCommerce Cart.
	 */
	public static function ajax_wc_remove_cart_item() {
		check_ajax_referer( 'scraper_cart_nonce', 'nonce' );

		$hash = sanitize_text_field( $_POST['id'] ?? '' );

		if ( ! self::init_wc_cart() ) {
			wp_send_json_error( 'ووکامرس فعال نیست.' );
		}

		$target_key = null;
		foreach ( WC()->cart->get_cart() as $key => $item ) {
			$pid       = $item['product_id'];
			$item_hash = get_post_meta( $pid, '_scraped_hash', true );
			if ( $item_hash === $hash || (string) $pid === (string) $hash ) {
				$target_key = $key;
				break;
			}
		}

		if ( $target_key ) {
			WC()->cart->remove_cart_item( $target_key );
			WC()->cart->calculate_totals();
		}

		wp_send_json_success( self::get_wc_cart_response() );
	}

	/**
	 * AJAX endpoint to read current WooCommerce Cart items.
	 */
	public static function ajax_wc_get_cart() {
		if ( ! self::init_wc_cart() ) {
			wp_send_json_error( 'ووکامرس فعال نیست.' );
		}

		wp_send_json_success( self::get_wc_cart_response() );
	}

	/**
	 * Synchronize entire frontend cart to WooCommerce Cart and return checkout URL.
	 */
	public static function ajax_wc_sync_and_checkout() {
		check_ajax_referer( 'scraper_cart_nonce', 'nonce' );

		$raw_items = $_POST['items'] ?? '[]';
		$items     = json_decode( stripslashes( $raw_items ), true );
		if ( ! is_array( $items ) || empty( $items ) ) {
			wp_send_json_error( 'سبد خرید خالی است.' );
		}

		if ( ! self::init_wc_cart() ) {
			wp_send_json_error( 'ووکامرس فعال نیست.' );
		}

		// Clear previous session items to guarantee clean 1:1 match
		WC()->cart->empty_cart();

		$added = 0;
		foreach ( $items as $item ) {
			$product_id = self::find_or_create_wc_product( $item );
			$qty        = max( 1, intval( $item['qty'] ?? 1 ) );
			if ( $product_id > 0 ) {
				$cart_key = WC()->cart->add_to_cart( $product_id, $qty );
				if ( $cart_key ) {
					$added++;
				}
			}
		}

		WC()->cart->calculate_totals();
		if ( WC()->session ) {
			WC()->session->set_customer_session_cookie( true );
		}

		$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : ( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/checkout/' ) );

	wp_send_json_success( array(
			'count'        => WC()->cart->get_cart_contents_count(),
			'added'        => $added,
			'checkout_url' => $checkout_url,
		) );
	}

	/**
	 * List all enabled WooCommerce payment gateways for the custom checkout UI.
	 *
	 * @return array<int, array{id:string,title:string,description:string,icon:string,method_title:string}>
	 */
	public static function get_active_payment_gateways_list() {
		$list = array();
		if ( ! function_exists( 'WC' ) || ! WC() || ! WC()->payment_gateways() ) {
			// Sandbox / no WC: still offer a COD-like placeholder
			$list[] = array(
				'id'           => 'cod',
				'title'        => 'پرداخت در محل',
				'description'  => 'پرداخت هنگام تحویل سفارش',
				'icon'         => '',
				'method_title' => 'COD',
			);
			return $list;
		}
		try {
			$gateways = WC()->payment_gateways()->get_available_payment_gateways();
			if ( empty( $gateways ) && method_exists( WC()->payment_gateways(), 'payment_gateways' ) ) {
				// Fallback: all registered gateways that are enabled
				$all = WC()->payment_gateways()->payment_gateways();
				$gateways = array();
				foreach ( (array) $all as $gid => $gw ) {
					if ( is_object( $gw ) && method_exists( $gw, 'is_available' ) && $gw->is_available() ) {
						$gateways[ $gid ] = $gw;
					} elseif ( is_object( $gw ) && isset( $gw->enabled ) && 'yes' === $gw->enabled ) {
						$gateways[ $gid ] = $gw;
					}
				}
			}
			foreach ( (array) $gateways as $id => $gw ) {
				if ( ! is_object( $gw ) ) {
					continue;
				}
				$title = method_exists( $gw, 'get_title' ) ? (string) $gw->get_title() : (string) ( $gw->title ?? $id );
				$desc  = method_exists( $gw, 'get_description' ) ? wp_strip_all_tags( (string) $gw->get_description() ) : (string) ( $gw->description ?? '' );
				$icon  = '';
				if ( method_exists( $gw, 'get_icon' ) ) {
					$raw_icon = $gw->get_icon();
					if ( is_string( $raw_icon ) && preg_match( '/src=[\'"]([^\'"]+)[\'"]/', $raw_icon, $m ) ) {
						$icon = $m[1];
					}
				}
				$list[] = array(
					'id'           => (string) $id,
					'title'        => $title !== '' ? $title : (string) $id,
					'description'  => $desc,
					'icon'         => $icon,
					'method_title' => (string) ( $gw->method_title ?? $title ),
				);
			}
		} catch ( \Throwable $e ) {
			// ignore
		}
		if ( empty( $list ) ) {
			$list[] = array(
				'id'           => 'cod',
				'title'        => 'پرداخت در محل',
				'description'  => 'درگاه فعالی یافت نشد — پرداخت هنگام تحویل',
				'icon'         => '',
				'method_title' => 'COD',
			);
		}
		return $list;
	}

	/**
	 * AJAX: return active payment gateways.
	 */
	public static function ajax_get_payment_gateways() {
		wp_send_json_success( array(
			'gateways' => self::get_active_payment_gateways_list(),
		) );
	}

	/**
	 * AJAX: place order from custom React checkout.
	 * Creates a real WC order when WooCommerce is available.
	 */

	/**
	 * Load Iran province→cities map (bundled JSON).
	 */
	public static function get_iran_cities_map() {
		static $map = null;
		if ( null !== $map ) {
			return $map;
		}
		$path = plugin_dir_path( __FILE__ ) . 'includes/geo/iran-cities.json';
		$map  = array();
		if ( is_readable( $path ) ) {
			$raw = file_get_contents( $path );
			$decoded = json_decode( (string) $raw, true );
			if ( is_array( $decoded ) ) {
				$map = $decoded;
			}
		}
		return $map;
	}

	/**
	 * AJAX: province list or cities for a province.
	 */
	public static function ajax_get_iran_cities() {
		$map = self::get_iran_cities_map();
		$province = sanitize_text_field( wp_unslash( $_REQUEST['province'] ?? $_POST['province'] ?? '' ) );
		if ( $province !== '' ) {
			$cities = array();
			if ( isset( $map[ $province ] ) && is_array( $map[ $province ] ) ) {
				$cities = array_values( $map[ $province ] );
			} else {
				// fuzzy match (normalized spaces)
				$norm = function( $s ) {
					$s = str_replace( array( 'ي', 'ك', '‌', ' ' ), array( 'ی', 'ک', '', '' ), (string) $s );
					return $s;
				};
				$np = $norm( $province );
				foreach ( $map as $k => $v ) {
					if ( $norm( $k ) === $np ) {
						$cities = array_values( (array) $v );
						break;
					}
				}
			}
			wp_send_json_success( array(
				'province' => $province,
				'cities'   => $cities,
			) );
		}
		wp_send_json_success( array(
			'provinces' => array_keys( $map ),
			'map'       => $map,
		) );
	}

	/**
	 * Collect shipping methods from WooCommerce + built-in Iran carriers.
	 *
	 * @param array $ctx optional context: province, city, postcode, subtotal, weight_kg, lat, lng
	 * @return array
	 */
	public static function get_shipping_methods_list( $ctx = array() ) {
		$settings = self::get_settings();
		$subtotal = floatval( $ctx['subtotal'] ?? 0 );
		$threshold = floatval( $settings['free_shipping_threshold'] ?? 400000 );
		$weight = max( 0.5, floatval( $ctx['weight_kg'] ?? 1 ) );
		$province = (string) ( $ctx['province'] ?? '' );
		$city = (string) ( $ctx['city'] ?? '' );
		$list = array();

		// WooCommerce zone methods
		if ( function_exists( 'WC' ) && class_exists( 'WC_Shipping_Zones' ) ) {
			try {
				self::init_wc_cart();
				$package = array(
					'contents'        => array(),
					'contents_cost'   => $subtotal,
					'applied_coupons' => array(),
					'user'            => array( 'ID' => get_current_user_id() ),
					'destination'     => array(
						'country'   => 'IR',
						'state'     => $province,
						'city'      => $city,
						'postcode'  => (string) ( $ctx['postcode'] ?? '' ),
						'address'   => (string) ( $ctx['address'] ?? '' ),
						'address_1' => (string) ( $ctx['address'] ?? '' ),
					),
				);
				// Prefer cart packages when available
				$packages = array();
				if ( WC()->cart && ! WC()->cart->is_empty() ) {
					WC()->customer->set_shipping_country( 'IR' );
					WC()->customer->set_shipping_state( $province );
					WC()->customer->set_shipping_city( $city );
					WC()->customer->set_shipping_postcode( (string) ( $ctx['postcode'] ?? '' ) );
					WC()->shipping()->calculate_shipping( WC()->cart->get_shipping_packages() );
					$packages = WC()->shipping()->get_packages();
				} else {
					$zones = \WC_Shipping_Zones::get_zones();
					// Also include "Rest of the World" / zone 0
					$zone0 = new \WC_Shipping_Zone( 0 );
					$all_methods = array();
					foreach ( $zones as $z ) {
						$zone_obj = new \WC_Shipping_Zone( $z['id'] );
						foreach ( $zone_obj->get_shipping_methods( true ) as $m ) {
							$all_methods[] = $m;
						}
					}
					foreach ( $zone0->get_shipping_methods( true ) as $m ) {
						$all_methods[] = $m;
					}
					foreach ( $all_methods as $method ) {
						if ( ! is_object( $method ) ) {
							continue;
						}
						$id = method_exists( $method, 'get_rate_id' ) ? $method->get_rate_id() : ( ( $method->id ?? 'method' ) . ':' . ( $method->instance_id ?? 0 ) );
						$title = method_exists( $method, 'get_title' ) ? $method->get_title() : (string) ( $method->title ?? $id );
						$cost = 0.0;
						if ( isset( $method->cost ) && is_numeric( $method->cost ) ) {
							$cost = floatval( $method->cost );
						} elseif ( method_exists( $method, 'get_option' ) ) {
							$c = $method->get_option( 'cost', '' );
							if ( $c !== '' && is_numeric( $c ) ) {
								$cost = floatval( $c );
							}
						}
						// free_shipping threshold aware
						if ( ( $method->id ?? '' ) === 'free_shipping' ) {
							$cost = 0.0;
						}
						$list[] = array(
							'id'          => (string) $id,
							'title'       => (string) $title,
							'description' => method_exists( $method, 'get_method_description' ) ? wp_strip_all_tags( (string) $method->get_method_description() ) : '',
							'cost'        => $cost,
							'cost_formatted' => self::format_price( $cost, $settings['currency_symbol'] ?? 'تومان' ),
							'source'      => 'woocommerce',
							'carrier'     => (string) ( $method->id ?? 'wc' ),
							'meta'        => array( 'instance_id' => intval( $method->instance_id ?? 0 ) ),
						);
					}
				}
				foreach ( (array) $packages as $pkg ) {
					foreach ( (array) ( $pkg['rates'] ?? array() ) as $rate_id => $rate ) {
						if ( ! is_object( $rate ) ) {
							continue;
						}
						$cost = floatval( method_exists( $rate, 'get_cost' ) ? $rate->get_cost() : 0 );
						$label = method_exists( $rate, 'get_label' ) ? $rate->get_label() : (string) $rate_id;
						$list[] = array(
							'id'             => (string) $rate_id,
							'title'          => (string) $label,
							'description'    => '',
							'cost'           => $cost,
							'cost_formatted' => self::format_price( $cost, $settings['currency_symbol'] ?? 'تومان' ),
							'source'         => 'woocommerce',
							'carrier'        => (string) ( method_exists( $rate, 'get_method_id' ) ? $rate->get_method_id() : 'wc' ),
							'meta'           => array(),
						);
					}
				}
			} catch ( \Throwable $e ) {
				// ignore WC shipping errors
			}
		}

		// Built-in Iran carriers (Post / Chapar / Tipax) — estimate or API when token set
		$carriers = array();
		if ( ! empty( $settings['post_shipping_enabled'] ) ) {
			$carriers[] = array(
				'id' => 'amphp_post',
				'title' => 'پست پیشتاز',
				'carrier' => 'post',
				'base' => floatval( $settings['shipping_base_post'] ?? 45000 ),
				'desc' => 'ارسال با پست جمهوری اسلامی ایران',
				'token' => (string) ( $settings['post_api_token'] ?? '' ),
			);
		}
		if ( ! empty( $settings['chapar_shipping_enabled'] ) ) {
			$carriers[] = array(
				'id' => 'amphp_chapar',
				'title' => 'چاپار',
				'carrier' => 'chapar',
				'base' => floatval( $settings['shipping_base_chapar'] ?? 65000 ),
				'desc' => 'پیک سریع چاپار',
				'token' => (string) ( $settings['chapar_api_token'] ?? '' ),
			);
		}
		if ( ! empty( $settings['tipax_shipping_enabled'] ) ) {
			$carriers[] = array(
				'id' => 'amphp_tipax',
				'title' => 'تیپاکس',
				'carrier' => 'tipax',
				'base' => floatval( $settings['shipping_base_tipax'] ?? 75000 ),
				'desc' => 'ارسال تیپاکس درب به درب',
				'token' => (string) ( $settings['tipax_api_token'] ?? '' ),
			);
		}

		$per_kg = floatval( $settings['shipping_per_kg'] ?? 12000 );
		$origin_city = (string) ( $settings['shipping_origin_city'] ?? 'تهران' );
		$same_city = ( $city !== '' && $origin_city !== '' && mb_strpos( $city, $origin_city ) !== false )
			|| ( $city !== '' && $origin_city !== '' && mb_strpos( $origin_city, $city ) !== false );

		foreach ( $carriers as $c ) {
			$cost = self::estimate_carrier_cost( $c, array(
				'subtotal'   => $subtotal,
				'weight_kg'  => $weight,
				'province'   => $province,
				'city'       => $city,
				'same_city'  => $same_city,
				'per_kg'     => $per_kg,
				'threshold'  => $threshold,
			) );
			// Free shipping threshold applies to flat estimates
			if ( $threshold > 0 && $subtotal >= $threshold ) {
				$cost = 0.0;
			}
			$list[] = array(
				'id'             => $c['id'],
				'title'          => $c['title'],
				'description'    => $c['desc'] . ( $cost <= 0 && $subtotal >= $threshold ? ' — ارسال رایگان' : '' ),
				'cost'           => $cost,
				'cost_formatted' => $cost <= 0 ? 'رایگان' : self::format_price( $cost, $settings['currency_symbol'] ?? 'تومان' ),
				'source'         => 'amphp',
				'carrier'        => $c['carrier'],
				'meta'           => array( 'api' => $c['token'] !== '' ),
			);
		}

		// Always offer a free/local pickup style fallback if list empty
		if ( empty( $list ) ) {
			$cost = ( $threshold > 0 && $subtotal >= $threshold ) ? 0.0 : floatval( $settings['shipping_base_post'] ?? 45000 );
			$list[] = array(
				'id'             => 'amphp_flat',
				'title'          => 'ارسال استاندارد',
				'description'    => 'هزینه ارسال پس از ثبت مقصد',
				'cost'           => $cost,
				'cost_formatted' => $cost <= 0 ? 'رایگان' : self::format_price( $cost, $settings['currency_symbol'] ?? 'تومان' ),
				'source'         => 'amphp',
				'carrier'        => 'flat',
				'meta'           => array(),
			);
		}

		// Dedupe by id
		$seen = array();
		$out = array();
		foreach ( $list as $row ) {
			$id = (string) ( $row['id'] ?? '' );
			if ( $id === '' || isset( $seen[ $id ] ) ) {
				continue;
			}
			$seen[ $id ] = true;
			$out[] = $row;
		}
		return $out;
	}

	/**
	 * Estimate carrier shipping cost (token APIs optional; graceful fallback).
	 */
	public static function estimate_carrier_cost( $carrier, $ctx ) {
		$base = floatval( $carrier['base'] ?? 45000 );
		$weight = max( 0.5, floatval( $ctx['weight_kg'] ?? 1 ) );
		$per_kg = floatval( $ctx['per_kg'] ?? 12000 );
		$same_city = ! empty( $ctx['same_city'] );
		$token = (string) ( $carrier['token'] ?? '' );
		$cost = $base + max( 0, $weight - 1 ) * $per_kg;
		if ( $same_city ) {
			$cost *= 0.7;
		}
		// Try live API when token present (best-effort; never block checkout)
		if ( $token !== '' ) {
			try {
				$live = self::fetch_carrier_api_rate( $carrier['carrier'] ?? '', $token, $ctx );
				if ( $live !== null && $live >= 0 ) {
					return floatval( $live );
				}
			} catch ( \Throwable $e ) {
				// fall through to estimate
			}
		}
		return round( $cost / 1000 ) * 1000;
	}

	/**
	 * Optional live rate from Post / Chapar / Tipax APIs.
	 * Returns null when unavailable so estimate is used.
	 */
	public static function fetch_carrier_api_rate( $carrier, $token, $ctx ) {
		$carrier = strtolower( (string) $carrier );
		$timeout = 6;
		if ( $carrier === 'chapar' ) {
			// Chapar public-ish quote endpoint pattern (token as Bearer) — soft fail
			$url = 'https://api.chaparnet.com/v1/rate';
			$res = wp_remote_post( $url, array(
				'timeout' => $timeout,
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/json',
					'Accept'        => 'application/json',
				),
				'body' => wp_json_encode( array(
					'to_city'   => (string) ( $ctx['city'] ?? '' ),
					'to_state'  => (string) ( $ctx['province'] ?? '' ),
					'weight'    => floatval( $ctx['weight_kg'] ?? 1 ),
				) ),
			) );
			if ( ! is_wp_error( $res ) && wp_remote_retrieve_response_code( $res ) < 400 ) {
				$body = json_decode( (string) wp_remote_retrieve_body( $res ), true );
				if ( isset( $body['price'] ) ) {
					return floatval( $body['price'] );
				}
				if ( isset( $body['data']['price'] ) ) {
					return floatval( $body['data']['price'] );
				}
			}
		} elseif ( $carrier === 'tipax' ) {
			$url = 'https://api.tipax.ir/api/v1/pricing/quote';
			$res = wp_remote_post( $url, array(
				'timeout' => $timeout,
				'headers' => array(
					'Authorization' => 'Bearer ' . $token,
					'Content-Type'  => 'application/json',
					'Accept'        => 'application/json',
				),
				'body' => wp_json_encode( array(
					'destination_city' => (string) ( $ctx['city'] ?? '' ),
					'weight'           => floatval( $ctx['weight_kg'] ?? 1 ),
				) ),
			) );
			if ( ! is_wp_error( $res ) && wp_remote_retrieve_response_code( $res ) < 400 ) {
				$body = json_decode( (string) wp_remote_retrieve_body( $res ), true );
				foreach ( array( 'price', 'amount', 'total' ) as $k ) {
					if ( isset( $body[ $k ] ) && is_numeric( $body[ $k ] ) ) {
						return floatval( $body[ $k ] );
					}
					if ( isset( $body['data'][ $k ] ) && is_numeric( $body['data'][ $k ] ) ) {
						return floatval( $body['data'][ $k ] );
					}
				}
			}
		} elseif ( $carrier === 'post' ) {
			// Iran Post e-commerce style — soft fail to estimate
			$url = 'https://ecommerce.post.ir/api/v1/price';
			$res = wp_remote_post( $url, array(
				'timeout' => $timeout,
				'headers' => array(
					'ApiKey'       => $token,
					'Content-Type' => 'application/json',
					'Accept'       => 'application/json',
				),
				'body' => wp_json_encode( array(
					'CityName' => (string) ( $ctx['city'] ?? '' ),
					'Weight'   => floatval( $ctx['weight_kg'] ?? 1 ) * 1000,
					'ServiceType' => 1,
				) ),
			) );
			if ( ! is_wp_error( $res ) && wp_remote_retrieve_response_code( $res ) < 400 ) {
				$body = json_decode( (string) wp_remote_retrieve_body( $res ), true );
				foreach ( array( 'Price', 'price', 'TotalPrice' ) as $k ) {
					if ( isset( $body[ $k ] ) && is_numeric( $body[ $k ] ) ) {
						return floatval( $body[ $k ] );
					}
					if ( isset( $body['Data'][ $k ] ) && is_numeric( $body['Data'][ $k ] ) ) {
						return floatval( $body['Data'][ $k ] );
					}
				}
			}
		}
		return null;
	}

	/**
	 * AJAX: list shipping methods (WC + Iran carriers).
	 */
	public static function ajax_get_shipping_methods() {
		$ctx = array(
			'province' => sanitize_text_field( wp_unslash( $_POST['province'] ?? '' ) ),
			'city'     => sanitize_text_field( wp_unslash( $_POST['city'] ?? '' ) ),
			'postcode' => sanitize_text_field( wp_unslash( $_POST['postal'] ?? $_POST['postcode'] ?? '' ) ),
			'address'  => sanitize_text_field( wp_unslash( $_POST['address'] ?? '' ) ),
			'subtotal' => floatval( $_POST['subtotal'] ?? 0 ),
			'weight_kg'=> floatval( $_POST['weight_kg'] ?? 1 ),
			'lat'      => sanitize_text_field( wp_unslash( $_POST['lat'] ?? '' ) ),
			'lng'      => sanitize_text_field( wp_unslash( $_POST['lng'] ?? '' ) ),
		);
		$methods = self::get_shipping_methods_list( $ctx );
		$settings = self::get_settings();
		wp_send_json_success( array(
			'methods'   => $methods,
			'threshold' => floatval( $settings['free_shipping_threshold'] ?? 400000 ),
			'neshan'    => array(
				'enabled' => ! empty( $settings['checkout_show_map'] ) && ! empty( $settings['neshan_api_key'] ),
				'has_key' => ! empty( $settings['neshan_api_key'] ),
			),
		) );
	}

	/**
	 * AJAX: recalculate shipping for destination (alias of methods with costs).
	 */
	public static function ajax_calc_shipping() {
		self::ajax_get_shipping_methods();
	}

	/**
	 * AJAX: Neshan reverse/forward geocode proxy (keeps API key server-side).
	 */
	/**
	 * Normalize Neshan "state" / province names to match IRAN_PROVINCES / cities map.
	 */
	public static function neshan_normalize_province( $state ) {
		$s = trim( (string) $state );
		if ( $s === '' ) {
			return '';
		}
		// "استان تهران" → "تهران"
		$s = preg_replace( '/^استان\s+/u', '', $s );
		$s = str_replace( array( 'ي', 'ك' ), array( 'ی', 'ک' ), $s );
		$map = self::get_iran_cities_map();
		if ( isset( $map[ $s ] ) ) {
			return $s;
		}
		// fuzzy contains
		foreach ( array_keys( $map ) as $prov ) {
			if ( $prov === $s || mb_strpos( $prov, $s ) !== false || mb_strpos( $s, $prov ) !== false ) {
				return $prov;
			}
		}
		// common aliases
		$aliases = array(
			'تهران' => 'تهران',
			'آذربایجان‌شرقی' => 'آذربایجان شرقی',
			'آذربایجانشرقی' => 'آذربایجان شرقی',
			'آذربایجان‌غربی' => 'آذربایجان غربی',
			'آذربایجانغربی' => 'آذربایجان غربی',
			'خراسان‌رضوی' => 'خراسان رضوی',
			'خراسانرضوی' => 'خراسان رضوی',
			'خراسان‌شمالی' => 'خراسان شمالی',
			'خراسان‌جنوبی' => 'خراسان جنوبی',
			'کهگیلویه وبویراحمد' => 'کهگیلویه و بویراحمد',
			'کهگیلویه و بویراحمد' => 'کهگیلویه و بویراحمد',
			'چهارمحال وبختیاری' => 'چهارمحال و بختیاری',
			'چهارمحال و بختیاری' => 'چهارمحال و بختیاری',
			'سیستان وبلوچستان' => 'سیستان و بلوچستان',
			'سیستان و بلوچستان' => 'سیستان و بلوچستان',
		);
		$compact = str_replace( array( ' ', '‌' ), '', $s );
		foreach ( $aliases as $k => $v ) {
			if ( str_replace( array( ' ', '‌' ), '', $k ) === $compact || $k === $s ) {
				return $v;
			}
		}
		return $s;
	}

	/**
	 * Call Neshan Reverse Geocoding API (مختصات → آدرس).
	 * Docs: GET https://api.neshan.org/v5/reverse?lat=&lng=  Header: Api-Key
	 *
	 * @param float|string $lat
	 * @param float|string $lng
	 * @param string       $api_key
	 * @return array|\WP_Error normalized payload
	 */
	public static function neshan_reverse_geocode( $lat, $lng, $api_key = '' ) {
		if ( $api_key === '' ) {
			$settings = self::get_settings();
			$api_key  = trim( (string) ( $settings['neshan_api_key'] ?? '' ) );
		}
		if ( $api_key === '' ) {
			return new \WP_Error( 'neshan_no_key', 'کلید API نشان تنظیم نشده است.' );
		}
		$lat = floatval( $lat );
		$lng = floatval( $lng );
		if ( ! is_finite( $lat ) || ! is_finite( $lng ) || abs( $lat ) > 90 || abs( $lng ) > 180 ) {
			return new \WP_Error( 'neshan_bad_coord', 'مختصات نامعتبر است.' );
		}
		// Iran rough bounds soft-check (still allow; Neshan validates)
		$url = add_query_arg(
			array(
				'lat' => sprintf( '%.7f', $lat ),
				'lng' => sprintf( '%.7f', $lng ),
			),
			'https://api.neshan.org/v5/reverse'
		);
		$res = wp_remote_get( $url, array(
			'timeout'     => 12,
			'redirection' => 2,
			'headers'     => array(
				'Api-Key' => $api_key,
				'Accept'  => 'application/json',
			),
			'user-agent'  => 'AMPHP-Storefront/13.3 (Neshan Reverse Geocoding)',
		) );
		if ( is_wp_error( $res ) ) {
			return new \WP_Error( 'neshan_http', 'خطا در ارتباط با نشان: ' . $res->get_error_message() );
		}
		$code = (int) wp_remote_retrieve_response_code( $res );
		$body_raw = (string) wp_remote_retrieve_body( $res );
		$body = json_decode( $body_raw, true );
		if ( ! is_array( $body ) ) {
			$body = array();
		}
		if ( $code >= 400 ) {
			$status = (string) ( $body['status'] ?? $body['message'] ?? $body['error'] ?? '' );
			$map_err = array(
				'KeyNotFound'          => 'کلید API نشان نامعتبر است یا ارسال نشده.',
				'LimitExceeded'        => 'سقف فراخوانی نشان پر شده است.',
				'RateExceeded'         => 'تعداد درخواست نشان در دقیقه بیش از حد مجاز است.',
				'ApiKeyTypeError'      => 'نوع کلید نشان با سرویس Reverse Geocoding هم‌خوان نیست. از کلید سرویس «تبدیل مختصات به آدرس» استفاده کنید.',
				'ApiWhiteListError'    => 'IP یا دامنه برای این کلید نشان مجاز نیست.',
				'ApiServiceListError'  => 'سرویس Reverse روی این کلید فعال نیست.',
				'CoordinateParseError' => 'مختصات قابل‌خواندن نیست.',
				'INVALID_ARGUMENT'     => 'پارامترهای ورودی نامعتبر است.',
			);
			$msg = $map_err[ $status ] ?? ( $status !== '' ? $status : ( 'خطای نشان HTTP ' . $code ) );
			return new \WP_Error( 'neshan_api', $msg, array( 'status' => $code, 'body' => $body ) );
		}
		$api_status = strtoupper( (string) ( $body['status'] ?? 'OK' ) );
		if ( $api_status && $api_status !== 'OK' && empty( $body['formatted_address'] ) ) {
			return new \WP_Error( 'neshan_status', 'پاسخ نشان ناموفق: ' . $api_status, array( 'body' => $body ) );
		}

		$formatted = trim( (string) ( $body['formatted_address'] ?? '' ) );
		$route     = trim( (string) ( $body['route_name'] ?? '' ) );
		$neigh     = trim( (string) ( $body['neighbourhood'] ?? '' ) );
		$city_raw  = trim( (string) ( $body['city'] ?? '' ) );
		$state_raw = trim( (string) ( $body['state'] ?? '' ) );
		$village   = trim( (string) ( $body['village'] ?? '' ) );
		$county    = trim( (string) ( $body['county'] ?? '' ) );
		$district  = trim( (string) ( $body['district'] ?? '' ) );
		$place     = trim( (string) ( $body['place'] ?? '' ) );
		$zone      = trim( (string) ( $body['municipality_zone'] ?? '' ) );

		$province = self::neshan_normalize_province( $state_raw );
		$city = $city_raw !== '' ? $city_raw : ( $village !== '' ? $village : $county );
		// strip "شهرستان " prefix sometimes present on county fallback
		$city = preg_replace( '/^شهرستان\s+/u', '', (string) $city );

		// Build street-level address for the form (prefer Neshan formatted)
		$address = $formatted;
		if ( $address === '' ) {
			$parts = array_filter( array( $place, $route, $neigh, $city, $province ) );
			$address = implode( '، ', $parts );
		}

		return array(
			'ok'                 => true,
			'service'            => 'neshan_reverse_v5',
			'status'             => $api_status ?: 'OK',
			'formatted'          => $formatted,
			'formatted_address'  => $formatted,
			'address'            => $address,
			'province'           => $province,
			'state'              => $state_raw,
			'city'               => $city,
			'neighbourhood'      => $neigh,
			'route_name'         => $route,
			'route_type'         => (string) ( $body['route_type'] ?? '' ),
			'place'              => $place,
			'municipality_zone'  => $zone,
			'in_traffic_zone'    => ! empty( $body['in_traffic_zone'] ),
			'in_odd_even_zone'   => ! empty( $body['in_odd_even_zone'] ),
			'village'            => $village,
			'county'             => $county,
			'district'           => $district,
			'lat'                => $lat,
			'lng'                => $lng,
			'raw'                => $body,
		);
	}

	/**
	 * AJAX: Neshan reverse geocode (مختصات→آدرس) + search proxy.
	 * Key stays server-side (Api-Key header).
	 */
	public static function ajax_neshan_geocode() {
		$settings = self::get_settings();
		$key = trim( (string) ( $settings['neshan_api_key'] ?? '' ) );
		if ( $key === '' ) {
			wp_send_json_error( 'کلید API نشان تنظیم نشده است. از panel.neshan.org کلید سرویس Reverse Geocoding را وارد کنید.' );
		}
		$mode = sanitize_text_field( wp_unslash( $_REQUEST['mode'] ?? $_POST['mode'] ?? 'reverse' ) );
		$lat = sanitize_text_field( wp_unslash( $_REQUEST['lat'] ?? $_POST['lat'] ?? '' ) );
		$lng = sanitize_text_field( wp_unslash( $_REQUEST['lng'] ?? $_POST['lng'] ?? '' ) );
		$term = sanitize_text_field( wp_unslash( $_REQUEST['term'] ?? $_POST['term'] ?? $_POST['address'] ?? '' ) );

		if ( $mode === 'search' || $mode === 'forward' ) {
			if ( $term === '' ) {
				wp_send_json_error( 'عبارت جستجو خالی است.' );
			}
			$url = add_query_arg( array(
				'term' => $term,
				'lat'  => $lat !== '' ? $lat : (string) ( $settings['shipping_origin_lat'] ?? '35.6892' ),
				'lng'  => $lng !== '' ? $lng : (string) ( $settings['shipping_origin_lng'] ?? '51.3890' ),
			), 'https://api.neshan.org/v1/search' );
			$res = wp_remote_get( $url, array(
				'timeout' => 10,
				'headers' => array(
					'Api-Key' => $key,
					'Accept'  => 'application/json',
				),
			) );
			if ( is_wp_error( $res ) ) {
				wp_send_json_error( 'خطا در ارتباط با نشان: ' . $res->get_error_message() );
			}
			$code = wp_remote_retrieve_response_code( $res );
			$body = json_decode( (string) wp_remote_retrieve_body( $res ), true );
			if ( $code >= 400 ) {
				$msg = is_array( $body ) ? ( $body['message'] ?? $body['status'] ?? $body['error'] ?? 'خطای نشان' ) : 'خطای نشان';
				wp_send_json_error( (string) $msg );
			}
			$items = array();
			foreach ( (array) ( $body['items'] ?? $body['data'] ?? array() ) as $it ) {
				$plat = isset( $it['location']['y'] ) ? floatval( $it['location']['y'] ) : floatval( $it['lat'] ?? 0 );
				$plng = isset( $it['location']['x'] ) ? floatval( $it['location']['x'] ) : floatval( $it['lng'] ?? 0 );
				$items[] = array(
					'title'    => (string) ( $it['title'] ?? $it['address'] ?? '' ),
					'address'  => (string) ( $it['address'] ?? $it['title'] ?? '' ),
					'lat'      => $plat,
					'lng'      => $plng,
					'province' => self::neshan_normalize_province( $it['state'] ?? $it['province'] ?? '' ),
					'city'     => (string) ( $it['city'] ?? $it['region'] ?? '' ),
					'neighbourhood' => (string) ( $it['neighbourhood'] ?? '' ),
				);
			}
			wp_send_json_success( array(
				'mode'    => 'search',
				'service' => 'neshan_search_v1',
				'items'   => $items,
				'raw'     => $body,
			) );
		}

		/* default: reverse geocoding — سرویس تبدیل مختصات به آدرس */
		$result = self::neshan_reverse_geocode( $lat, $lng, $key );
		if ( is_wp_error( $result ) ) {
			wp_send_json_error( $result->get_error_message() );
		}
		wp_send_json_success( $result );
	}

	public static function ajax_custom_checkout_place_order() {
		check_ajax_referer( 'scraper_cart_nonce', 'nonce' );
		$settings = self::get_settings();
		if ( empty( $settings['enable_custom_checkout'] ) ) {
			wp_send_json_error( 'صفحه تسویه اختصاصی غیرفعال است.' );
		}
		if ( ! empty( $settings['checkout_require_login'] ) && ! is_user_logged_in() ) {
			wp_send_json_error( 'برای ثبت سفارش ابتدا وارد حساب کاربری شوید.' );
		}

		$raw_items = $_POST['items'] ?? '[]';
		$items     = json_decode( stripslashes( (string) $raw_items ), true );
		if ( ! is_array( $items ) || empty( $items ) ) {
			wp_send_json_error( 'سبد خرید خالی است.' );
		}

		$customer = array(
			'name'     => sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) ),
			'phone'    => sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) ),
			'email'    => sanitize_email( wp_unslash( $_POST['email'] ?? '' ) ),
			'province' => sanitize_text_field( wp_unslash( $_POST['province'] ?? '' ) ),
			'city'     => sanitize_text_field( wp_unslash( $_POST['city'] ?? '' ) ),
			'address'  => sanitize_textarea_field( wp_unslash( $_POST['address'] ?? '' ) ),
			'postal'   => sanitize_text_field( wp_unslash( $_POST['postal'] ?? '' ) ),
			'notes'    => sanitize_textarea_field( wp_unslash( $_POST['notes'] ?? '' ) ),
		);
		$payment_method = sanitize_text_field( wp_unslash( $_POST['payment_method'] ?? '' ) );
		$shipping_method = sanitize_text_field( wp_unslash( $_POST['shipping_method'] ?? '' ) );
		$shipping_title  = sanitize_text_field( wp_unslash( $_POST['shipping_title'] ?? '' ) );
		$shipping_cost   = floatval( $_POST['shipping_cost'] ?? 0 );
		$geo_lat         = sanitize_text_field( wp_unslash( $_POST['lat'] ?? '' ) );
		$geo_lng         = sanitize_text_field( wp_unslash( $_POST['lng'] ?? '' ) );

		/* v13.3.16: if destination coords set, enrich address via Neshan reverse geocoding */
		if ( $geo_lat !== '' && $geo_lng !== '' && ! empty( $settings['neshan_api_key'] ) ) {
			try {
				$rev = self::neshan_reverse_geocode( $geo_lat, $geo_lng, (string) $settings['neshan_api_key'] );
				if ( ! is_wp_error( $rev ) && is_array( $rev ) ) {
					if ( $customer['address'] === '' && ! empty( $rev['formatted'] ) ) {
						$customer['address'] = $rev['formatted'];
					}
					if ( $customer['province'] === '' && ! empty( $rev['province'] ) ) {
						$customer['province'] = $rev['province'];
					}
					if ( $customer['city'] === '' && ! empty( $rev['city'] ) ) {
						$customer['city'] = $rev['city'];
					}
				}
			} catch ( \Throwable $e ) { /* non-fatal */ }
		}

		// Validate required fields from settings
		$req_map = array(
			'name'     => array( 'checkout_field_name', 'checkout_field_name_req', 'نام و نام خانوادگی' ),
			'phone'    => array( 'checkout_field_phone', 'checkout_field_phone_req', 'شماره موبایل' ),
			'email'    => array( 'checkout_field_email', 'checkout_field_email_req', 'ایمیل' ),
			'province' => array( 'checkout_field_province', 'checkout_field_province_req', 'استان' ),
			'city'     => array( 'checkout_field_city', 'checkout_field_city_req', 'شهر' ),
			'address'  => array( 'checkout_field_address', 'checkout_field_address_req', 'آدرس' ),
			'postal'   => array( 'checkout_field_postal', 'checkout_field_postal_req', 'کد پستی' ),
			'notes'    => array( 'checkout_field_notes', 'checkout_field_notes_req', 'توضیحات' ),
		);
		foreach ( $req_map as $key => $meta ) {
			$enabled  = ! empty( $settings[ $meta[0] ] );
			$required = ! empty( $settings[ $meta[1] ] );
			if ( $enabled && $required && '' === trim( (string) ( $customer[ $key ] ?? '' ) ) ) {
				wp_send_json_error( 'لطفاً فیلد «' . $meta[2] . '» را تکمیل کنید.' );
			}
		}

		$gateways = self::get_active_payment_gateways_list();
		$gw_ids   = array_map( function( $g ) { return $g['id']; }, $gateways );
		if ( ! empty( $settings['checkout_show_gateways'] ) && $payment_method && ! in_array( $payment_method, $gw_ids, true ) ) {
			// still allow if list empty or cod
			if ( ! empty( $gw_ids ) ) {
				wp_send_json_error( 'روش پرداخت نامعتبر است.' );
			}
		}
		if ( empty( $payment_method ) && ! empty( $gw_ids ) ) {
			$payment_method = $gw_ids[0];
		}

		$gw_title = $payment_method;
		foreach ( $gateways as $g ) {
			if ( $g['id'] === $payment_method ) {
				$gw_title = $g['title'];
				break;
			}
		}

		// Build name parts
		$name_parts = preg_split( '/\s+/', trim( $customer['name'] ), 2 );
		$first_name = $name_parts[0] ?? '';
		$last_name  = $name_parts[1] ?? '';

		$order_id     = 0;
		$order_key    = '';
		$pay_url      = '';
		$thankyou_url = '';
		$total        = 0.0;
		$currency     = (string) ( $settings['currency_symbol'] ?? 'تومان' );

		if ( function_exists( 'wc_create_order' ) && class_exists( 'WC_Order' ) ) {
			try {
				if ( ! self::init_wc_cart() ) {
					// still try create order without session
				}
				$order = wc_create_order( array(
					'status'      => 'pending',
					'customer_id' => get_current_user_id() ?: 0,
				) );
				if ( is_wp_error( $order ) ) {
					wp_send_json_error( $order->get_error_message() );
				}

				foreach ( $items as $item ) {
					$product_id = self::find_or_create_wc_product( $item );
					$qty        = max( 1, intval( $item['qty'] ?? 1 ) );
					$price      = floatval( $item['price'] ?? 0 );
					if ( $product_id > 0 ) {
						$product = wc_get_product( $product_id );
						if ( $product ) {
							if ( $price > 0 ) {
								$product->set_price( $price );
							}
							$order->add_product( $product, $qty );
						}
					} else {
						// line item without product object
						$li = new \WC_Order_Item_Product();
						$li->set_name( (string) ( $item['title'] ?? 'کالا' ) );
						$li->set_quantity( $qty );
						$li->set_subtotal( $price * $qty );
						$li->set_total( $price * $qty );
						$order->add_item( $li );
					}
					$total += $price * $qty;
				}

				$order->set_billing_first_name( $first_name );
				$order->set_billing_last_name( $last_name );
				$order->set_billing_phone( $customer['phone'] );
				$order->set_billing_email( $customer['email'] ?: ( $customer['phone'] . '@guest.local' ) );
				$order->set_billing_state( $customer['province'] );
				$order->set_billing_city( $customer['city'] );
				$order->set_billing_address_1( $customer['address'] );
				$order->set_billing_postcode( $customer['postal'] );
				$order->set_shipping_first_name( $first_name );
				$order->set_shipping_last_name( $last_name );
				$order->set_shipping_state( $customer['province'] );
				$order->set_shipping_city( $customer['city'] );
				$order->set_shipping_address_1( $customer['address'] );
				$order->set_shipping_postcode( $customer['postal'] );
				$order->set_shipping_phone( $customer['phone'] );

				if ( $customer['notes'] ) {
					$order->set_customer_note( $customer['notes'] );
				}

				$order->set_payment_method( $payment_method );
				$order->set_payment_method_title( $gw_title );

				/* v13.3.13: shipping method + cost */
				if ( $shipping_method !== '' || $shipping_cost > 0 ) {
					try {
						$ship_item = new \WC_Order_Item_Shipping();
						$ship_label = $shipping_title !== '' ? $shipping_title : $shipping_method;
						if ( $ship_label === '' ) {
							$ship_label = 'ارسال';
						}
						$ship_item->set_method_title( $ship_label );
						$ship_item->set_method_id( $shipping_method !== '' ? $shipping_method : 'amphp_flat' );
						$ship_item->set_total( max( 0, $shipping_cost ) );
						$order->add_item( $ship_item );
					} catch ( \Throwable $e ) {
						// ignore shipping line failures
					}
				}
				if ( $geo_lat !== '' && $geo_lng !== '' ) {
					try {
						$order->update_meta_data( '_amphp_dest_lat', $geo_lat );
						$order->update_meta_data( '_amphp_dest_lng', $geo_lng );
					} catch ( \Throwable $e ) {}
				}
				$order->calculate_totals();
				$order->save();

				// Attempt payment gateway process for redirect gateways
				$pay_url = $order->get_checkout_payment_url();
				$thankyou_url = $order->get_checkout_order_received_url();
				$order_id  = $order->get_id();
				$order_key = $order->get_order_key();
				$total     = (float) $order->get_total();

				/* v13.1.8: بازگشت از درگاه به ویترین با amphp_paid تا React پیام تکمیل را فقط بعد از پرداخت نشان دهد */
				$shop_return = add_query_arg(
					array(
						'amphp_paid'  => '1',
						'amphp_order' => $order_id,
						'amphp_key'   => $order_key,
					),
					home_url( '/' )
				);
				$shop_page_id = intval( get_option( 'scraper_shop_page_id', 0 ) );
				if ( $shop_page_id <= 0 ) {
					$shop_page_id = intval( $settings['shop_page_id'] ?? 0 );
				}
				if ( $shop_page_id > 0 ) {
					$plink = get_permalink( $shop_page_id );
					if ( $plink ) {
						$shop_return = add_query_arg(
							array(
								'amphp_paid'  => '1',
								'amphp_order' => $order_id,
								'amphp_key'   => $order_key,
							),
							$plink
						);
					}
				}
				try {
					$order->update_meta_data( '_amphp_return_url', $shop_return );
					$order->save();
				} catch ( \Throwable $e ) { /* ignore */ }

				// Try gateway process_payment when available (redirect gateways)
				if ( function_exists( 'WC' ) && WC()->payment_gateways() ) {
					$available = WC()->payment_gateways()->get_available_payment_gateways();
					if ( isset( $available[ $payment_method ] ) ) {
						$gw = $available[ $payment_method ];
						if ( is_object( $gw ) && method_exists( $gw, 'process_payment' ) ) {
							try {
								$return_filter = function( $url, $o ) use ( $shop_return, $order_id ) {
									if ( $o && is_object( $o ) && (int) $o->get_id() === (int) $order_id ) {
										return $shop_return;
									}
									return $url;
								};
								add_filter( 'woocommerce_get_return_url', $return_filter, 99, 2 );
								$result = $gw->process_payment( $order_id );
								remove_filter( 'woocommerce_get_return_url', $return_filter, 99 );
								if ( is_array( $result ) && ! empty( $result['result'] ) && 'success' === $result['result'] && ! empty( $result['redirect'] ) ) {
									$pay_url = $result['redirect'];
								}
							} catch ( \Throwable $e ) {
								// keep pay_url fallback
							}
						}
					}
				}
				$thankyou_url = $shop_return;

				// Track analytics
				try {
					self::ajax_track_analytics_event_internal( 'order_placed', array(
						'order_id' => $order_id,
						'total'    => $total,
					) );
				} catch ( \Throwable $e ) {}

			} catch ( \Throwable $e ) {
				wp_send_json_error( 'خطا در ثبت سفارش: ' . $e->getMessage() );
			}
		} else {
			// No WooCommerce — store a lightweight order record
			foreach ( $items as $item ) {
				$total += floatval( $item['price'] ?? 0 ) * max( 1, intval( $item['qty'] ?? 1 ) );
			}
			$order_id = intval( get_option( 'amphp_local_order_seq', 1000 ) ) + 1;
			update_option( 'amphp_local_order_seq', $order_id, false );
			$records = get_option( 'amphp_local_orders', array() );
			if ( ! is_array( $records ) ) {
				$records = array();
			}
			$total += max( 0, $shipping_cost );
			$records[] = array(
				'id'       => $order_id,
				'customer' => $customer,
				'items'    => $items,
				'total'    => $total,
				'payment'  => $payment_method,
				'shipping_method' => $shipping_method,
				'shipping_title'  => $shipping_title,
				'shipping_cost'   => $shipping_cost,
				'lat' => $geo_lat,
				'lng' => $geo_lng,
				'created'  => current_time( 'mysql' ),
			);
			update_option( 'amphp_local_orders', array_slice( $records, -200 ), false );
			$order_key = 'local_' . $order_id;
		}

		$is_cod = ( $payment_method === 'cod' || $payment_method === 'cheque' || $payment_method === 'bacs' );
		$needs_payment = ( ! $is_cod && $pay_url !== '' );
		$success_msg = (string) ( $settings['checkout_success_msg'] ?? 'سفارش شما با موفقیت تکمیل شد. از خریدتان سپاسگزاریم!' );
		/* قبل از پرداخت پیام «کامل شد» نفرست */
		$client_msg = $needs_payment
			? 'سفارش ثبت شد. در حال انتقال به درگاه پرداخت…'
			: $success_msg;

		wp_send_json_success( array(
			'order_id'        => $order_id,
			'order_key'       => $order_key,
			'total'           => $total,
			'total_formatted' => self::format_price( $total, $currency ),
			'payment_method'  => $payment_method,
			'payment_title'   => $gw_title,
			'pay_url'         => $pay_url,
			'thankyou_url'    => $thankyou_url,
			'needs_payment'   => $needs_payment,
			'is_cod'          => $is_cod,
			'message'         => $client_msg,
			'success_message' => $success_msg,
		) );
	}

	/**
	 * Internal analytics helper (safe no-op if missing).
	 */
	public static function ajax_track_analytics_event_internal( $type, $extra = array() ) {
		try {
			if ( method_exists( __CLASS__, 'record_analytics_event' ) ) {
				self::record_analytics_event( $type, (array) $extra );
				return;
			}
		} catch ( \Throwable $e ) {}
		try {
			$opts = get_option( 'scraper_shop_analytics', array() );
			if ( ! is_array( $opts ) ) {
				$opts = array();
			}
			if ( ! isset( $opts['events'] ) || ! is_array( $opts['events'] ) ) {
				$opts['events'] = array();
			}
			$opts['events'][] = array_merge( array(
				'type' => $type,
				'ts'   => time(),
			), (array) $extra );
			$opts['events'] = array_slice( $opts['events'], -5000 );
			if ( ! isset( $opts['totals'] ) || ! is_array( $opts['totals'] ) ) {
				$opts['totals'] = array();
			}
			$opts['totals'][ $type ] = intval( $opts['totals'][ $type ] ?? 0 ) + 1;
			update_option( 'scraper_shop_analytics', $opts, false );
		} catch ( \Throwable $e ) {}
	}

	/**
	 * Ensure scraped product price is kept during WooCommerce cart totals calculation.
	 *
	 * @param WC_Cart $cart
	 */
	public static function fix_cart_item_prices( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}
		foreach ( $cart->get_cart() as $cart_item_key => $cart_item ) {
			$product = $cart_item['data'] ?? null;
			if ( $product && is_object( $product ) ) {
				$product_id   = $product->get_id();
				$custom_price = get_post_meta( $product_id, '_price', true );
				if ( '' !== $custom_price && floatval( $custom_price ) > 0 ) {
					$product->set_price( floatval( $custom_price ) );
				}
			}
		}
	}

	/**
	 * Display scraped image in WooCommerce Cart & Checkout tables if no media thumbnail exists.
	 *
	 * @param string $thumbnail
	 * @param array $cart_item
	 * @param string $cart_item_key
	 * @return string
	 */
	public static function filter_cart_item_thumbnail( $thumbnail, $cart_item, $cart_item_key ) {
		$product = $cart_item['data'] ?? null;
		if ( $product && is_object( $product ) ) {
			$product_id  = $product->get_id();
			$scraped_img = get_post_meta( $product_id, '_scraped_image_url', true );
			if ( ! empty( $scraped_img ) && ! has_post_thumbnail( $product_id ) ) {
				return '<img width="64" height="64" src="' . esc_url( $scraped_img ) . '" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="" style="object-fit:cover; border-radius:8px; width:64px; height:64px;">';
			}
		}
		return $thumbnail;
	}

	/**
	 * Hijack WooCommerce shop template with modern full-experience shop.
	 *
	 * @param string $template
	 * @return string
	 */
	/**
	 * Inject custom CSS to completely remove WordPress theme header & menu on shop pages
	 * when custom storefront takeover is enabled.
	 */
	/**
	 * True when the current front request should use bare React storefront (no WP theme chrome).
	 *
	 * @return bool
	 */
	public static function is_bare_storefront_request() {
		$settings = self::get_settings();
		if ( empty( $settings['enable_shop_takeover'] ) ) {
			return false;
		}
		if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) {
			return true;
		}
		if ( ! empty( $settings['takeover_front_page'] ) && ( is_front_page() || is_home() ) ) {
			return true;
		}
		global $post;
		if ( ! empty( $post ) && is_a( $post, 'WP_Post' ) ) {
			if ( has_shortcode( $post->post_content, 'scraped_shop' ) || has_shortcode( $post->post_content, 'modern_shop' ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Strip every theme/plugin front asset so only the React storefront ships.
	 */
	public static function strip_all_theme_assets() {
		if ( is_admin() || ! empty( $GLOBALS['amphp_bare_storefront'] ) ) {
			return;
		}
		if ( ! self::is_bare_storefront_request() ) {
			return;
		}
		global $wp_styles, $wp_scripts;
		$keep_style = array( 'amphp-storefront', 'admin-bar', 'dashicons' );
		$keep_script = array( 'amphp-storefront', 'admin-bar', 'hoverintent-js', 'hoverIntent', 'jquery', 'jquery-core', 'jquery-migrate' );

		// Only dequeue from print queue — do NOT deregister (breaks late printers / our bundle).
		if ( $wp_styles instanceof \WP_Styles ) {
			foreach ( array_keys( (array) $wp_styles->queue ) as $i ) {
				$handle = $wp_styles->queue[ $i ] ?? null;
			}
			foreach ( (array) $wp_styles->queue as $handle ) {
				if ( $handle && ! in_array( $handle, $keep_style, true ) ) {
					wp_dequeue_style( $handle );
				}
			}
		}
		if ( $wp_scripts instanceof \WP_Scripts ) {
			foreach ( (array) $wp_scripts->queue as $handle ) {
				if ( $handle && ! in_array( $handle, $keep_script, true ) ) {
					wp_dequeue_script( $handle );
				}
			}
		}
	}

	public static function inject_custom_header_suppression_css() {
		// Bare takeover renders its own document — no theme CSS to suppress.
		if ( ! empty( $GLOBALS['amphp_bare_storefront'] ) ) {
			return;
		}
		$settings = self::get_settings();
		if ( empty( $settings['enable_shop_takeover'] ) || empty( $settings['replace_site_header'] ) ) {
			return;
		}
		if ( ! self::is_bare_storefront_request() ) {
			return;
		}
		?>
		<style id="scraper-suppress-wp-theme-header">
			/* Kill WP theme chrome when shortcode is embedded in a theme template */
			body.amphp-react-storefront header:not(.sf-header):not(.sf-header-wrap),
			body.amphp-react-storefront #masthead,
			body.amphp-react-storefront .site-header,
			body.amphp-react-storefront #site-header,
			body.amphp-react-storefront .elementor-location-header,
			body.amphp-react-storefront .ast-main-header-wrap,
			body.amphp-react-storefront footer.site-footer,
			body.amphp-react-storefront #colophon,
			body.amphp-react-storefront .site-footer,
			body.amphp-react-storefront .elementor-location-footer,
			body.amphp-react-storefront .widget-area,
			body.amphp-react-storefront aside.widget-area,
			body.amphp-react-storefront #secondary,
			body.amphp-react-storefront .sidebar,
			body.amphp-react-storefront nav.main-navigation,
			body.amphp-react-storefront .storefront-primary-navigation,
			body.amphp-react-storefront .woocommerce-breadcrumb,
			body.amphp-react-storefront .entry-header,
			body.amphp-react-storefront .page-title,
			body.amphp-react-storefront .wp-block-template-part {
				display: none !important;
				height: 0 !important; overflow: hidden !important; margin: 0 !important; padding: 0 !important;
			}
			body.amphp-react-storefront .site-content,
			body.amphp-react-storefront #content,
			body.amphp-react-storefront #primary,
			body.amphp-react-storefront .content-area,
			body.amphp-react-storefront main {
				margin: 0 !important; padding: 0 !important; max-width: none !important; width: 100% !important;
			}
		</style>
		<?php
	}

	/**
	 * Hijack WooCommerce shop template with modern full-experience shop.
	 *
	 * @param string $template
	 * @return string
	 */

	/**
	 * Absolute path to plugin native WP page template.
	 */
	public static function get_native_shop_template_path() {
		return plugin_dir_path( __FILE__ ) . 'templates/native-shop.php';
	}

	/**
	 * Register "Native WP shop" in the page template dropdown.
	 *
	 * @param array $templates
	 * @return array
	 */
	public static function register_native_page_templates( $templates ) {
		$templates['templates/native-shop.php'] = 'فروشگاه بومی وردپرس';
		return $templates;
	}

	/**
	 * Load plugin file when a page uses the native shop template.
	 *
	 * @param string $template
	 * @return string
	 */
	public static function maybe_load_native_page_template( $template ) {
		if ( is_admin() || ! is_singular( 'page' ) ) {
			return $template;
		}
		$page_template = get_page_template_slug( get_queried_object_id() );
		if ( $page_template !== 'templates/native-shop.php' && $page_template !== 'native-shop.php' ) {
			// Also allow meta-selected fallback page without explicit template slug if content has shortcode only
			return $template;
		}
		$path = self::get_native_shop_template_path();
		if ( is_readable( $path ) ) {
			return $path;
		}
		return $template;
	}

	/**
	 * Create or repair the native fallback shop page (theme chrome + shortcode).
	 *
	 * @param bool $assign_wc_shop If true and WC shop empty/missing, set this page as WC shop.
	 * @return array{id:int,url:string,created:bool,message:string}
	 */

	/**
	 * Copy native-shop.php into the active theme so the page template
	 * keeps working after the plugin is deactivated.
	 *
	 * @return bool
	 */
	public static function sync_native_template_to_theme() {
		$src = self::get_native_shop_template_path();
		if ( ! is_readable( $src ) ) {
			return false;
		}
		$theme_dir = trailingslashit( get_stylesheet_directory() );
		if ( ! $theme_dir || ! is_dir( $theme_dir ) ) {
			return false;
		}
		$dest_dir = $theme_dir . 'templates';
		if ( ! is_dir( $dest_dir ) ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_mkdir
			@mkdir( $dest_dir, 0755, true );
		}
		$dest = $dest_dir . '/native-shop.php';
		// Also place at theme root for older WP template discovery
		$dest_root = $theme_dir . 'native-shop.php';
		$ok = false;
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy
		if ( @copy( $src, $dest ) ) {
			$ok = true;
		}
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_copy
		@copy( $src, $dest_root );
		return $ok;
	}

	public static function ensure_fallback_shop_page( $assign_wc_shop = null ) {
		$settings = self::get_settings();
		if ( null === $assign_wc_shop ) {
			$assign_wc_shop = ! empty( $settings['set_wc_shop_to_fallback'] );
		}
		$auto = ! isset( $settings['auto_create_fallback_page'] ) || ! empty( $settings['auto_create_fallback_page'] );
		$page_id = intval( $settings['native_fallback_page_id'] ?? 0 );
		if ( $page_id <= 0 ) {
			$page_id = intval( get_option( 'scraper_native_fallback_page_id', 0 ) );
		}
		$created = false;
		$title = (string) ( $settings['shop_title'] ?? 'فروشگاه' );
		if ( $title === '' ) {
			$title = 'فروشگاه';
		}
		$fallback_title = $title . ' — پشتیبان';

		// Validate existing
		if ( $page_id > 0 ) {
			$post = get_post( $page_id );
			if ( ! $post || $post->post_status === 'trash' || $post->post_type !== 'page' ) {
				$page_id = 0;
			}
		}

		// Find by meta / slug
		if ( $page_id <= 0 ) {
			$found = get_posts( array(
				'post_type'      => 'page',
				'post_status'    => array( 'publish', 'draft', 'private' ),
				'posts_per_page' => 1,
				'meta_key'       => '_amphp_native_fallback_shop',
				'meta_value'     => '1',
				'fields'         => 'ids',
			) );
			if ( ! empty( $found ) ) {
				$page_id = (int) $found[0];
			}
		}
		if ( $page_id <= 0 ) {
			$by_path = get_page_by_path( 'amphp-shop-fallback' );
			if ( $by_path && ! is_wp_error( $by_path ) ) {
				$page_id = (int) $by_path->ID;
			}
		}

		// Copy template into active theme so it survives plugin deactivation
		self::sync_native_template_to_theme();

		if ( $page_id <= 0 && $auto ) {
			// WC-native shortcode so the page still lists products if plugin is off
			$content = "<!-- wp:shortcode -->\n[products limit=\"24\" columns=\"4\" orderby=\"date\"]\n<!-- /wp:shortcode -->\n\n"
				. "<!-- برگهٔ پشتیبان فروشگاه بومی وردپرس -->\n";
			$page_id = wp_insert_post( array(
				'post_title'   => $fallback_title,
				'post_name'    => 'amphp-shop-fallback',
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id() ?: 1,
			), true );
			if ( is_wp_error( $page_id ) ) {
				return array(
					'id'      => 0,
					'url'     => '',
					'created' => false,
					'message' => $page_id->get_error_message(),
				);
			}
			$page_id = (int) $page_id;
			$created = true;
		}

		if ( $page_id > 0 ) {
			update_post_meta( $page_id, '_amphp_native_fallback_shop', '1' );
			update_post_meta( $page_id, '_wp_page_template', 'templates/native-shop.php' );
			// Ensure shortcode present if content empty
			$post = get_post( $page_id );
			if ( $post && trim( wp_strip_all_tags( $post->post_content ) ) === '' ) {
				wp_update_post( array(
					'ID'           => $page_id,
					'post_content' => '[products limit="24" columns="4"]\n',
				) );
			}
			self::sync_native_template_to_theme();
			update_option( 'scraper_native_fallback_page_id', $page_id, false );
			update_option( 'scraper_shop_page_id', $page_id, false );
			// Persist into plugin settings
			$settings['native_fallback_page_id'] = $page_id;
			$settings['shop_page_id'] = $page_id;
			update_option( self::OPTION_NAME, $settings );

			if ( $assign_wc_shop && function_exists( 'wc_get_page_id' ) ) {
				$wc_shop = (int) wc_get_page_id( 'shop' );
				$need_set = ( $wc_shop <= 0 || get_post_status( $wc_shop ) === false || get_post_status( $wc_shop ) === 'trash' );
				// Also set when option explicitly wants fallback as WC shop
				if ( $need_set || ! empty( $settings['set_wc_shop_to_fallback'] ) ) {
					// Don't overwrite a healthy custom WC shop unless missing
					if ( $need_set ) {
						update_option( 'woocommerce_shop_page_id', $page_id );
					}
				}
			}
		}

		$url = $page_id > 0 ? get_permalink( $page_id ) : '';
		return array(
			'id'      => $page_id,
			'url'     => $url ? (string) $url : '',
			'created' => $created,
			'message' => $page_id > 0
				? ( $created ? 'برگه پشتیبان ساخته شد.' : 'برگه پشتیبان آماده است.' )
				: 'ساخت برگه پشتیبان ناموفق بود.',
			'template'=> 'templates/native-shop.php',
		);
	}

	/**
	 * admin_init soft ensure (non-blocking).
	 */
	public static function maybe_ensure_fallback_shop_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$settings = self::get_settings();
		if ( empty( $settings['enable_native_wp_template'] ) && empty( $settings['auto_create_fallback_page'] ) ) {
			return;
		}
		$id = intval( $settings['native_fallback_page_id'] ?? 0 );
		if ( $id > 0 && get_post_status( $id ) ) {
			return;
		}
		// Throttle
		if ( get_transient( 'amphp_fb_page_checked' ) ) {
			return;
		}
		set_transient( 'amphp_fb_page_checked', 1, 10 * MINUTE_IN_SECONDS );
		try {
			self::ensure_fallback_shop_page( false );
		} catch ( \Throwable $e ) {
			// ignore
		}
	}

	/**
	 * AJAX: create/repair fallback page from admin button.
	 */
	public static function ajax_ensure_fallback_shop() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}
		$assign = ! empty( $_POST['assign_wc'] );
		$res = self::ensure_fallback_shop_page( $assign );
		if ( ! empty( $res['id'] ) ) {
			wp_send_json_success( $res );
		}
		wp_send_json_error( $res['message'] ?? 'خطا' );
	}

	/**
	 * On 404 (and optionally when React takeover target is broken), send visitors to fallback shop.
	 */
	public static function maybe_redirect_404_to_fallback_shop() {
		if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return;
		}
		$settings = self::get_settings();
		if ( empty( $settings['enable_404_shop_redirect'] ) ) {
			return;
		}
		if ( ! is_404() ) {
			return;
		}
		// Don't loop
		$fb_id = intval( $settings['native_fallback_page_id'] ?? 0 );
		if ( $fb_id <= 0 ) {
			$fb_id = intval( get_option( 'scraper_native_fallback_page_id', 0 ) );
		}
		if ( $fb_id <= 0 ) {
			$ensured = self::ensure_fallback_shop_page( false );
			$fb_id = intval( $ensured['id'] ?? 0 );
		}
		if ( $fb_id <= 0 ) {
			// Last resort: WooCommerce shop
			if ( function_exists( 'wc_get_page_id' ) ) {
				$fb_id = (int) wc_get_page_id( 'shop' );
			}
		}
		if ( $fb_id <= 0 ) {
			return;
		}
		$url = get_permalink( $fb_id );
		if ( ! $url ) {
			return;
		}
		// Avoid redirect loop if the 404 path is already the fallback slug
		$req = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
		$path = (string) ( wp_parse_url( $url, PHP_URL_PATH ) ?: '' );
		if ( $path && $req && strpos( $req, trim( $path, '/' ) ) !== false ) {
			return;
		}
		nocache_headers();
		wp_safe_redirect( $url, 302 );
		exit;
	}

	public static function maybe_takeover_shop_template( $template ) {
		$settings = self::get_settings();
		if ( empty( $settings['enable_shop_takeover'] ) ) {
			return $template;
		}

		$should_takeover = false;
		if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) {
			$should_takeover = true;
		} elseif ( ! empty( $settings['takeover_front_page'] ) && ( is_front_page() || is_home() ) ) {
			$should_takeover = true;
		}

		if ( $should_takeover ) {
			// v13.3.17: native-wp template uses theme chrome instead of bare React shell
			$tpl = (string) ( $settings['store_template'] ?? '' );
			if ( $tpl === 'native-wp' || ( ! empty( $settings['enable_native_wp_template'] ) && $tpl === 'native' ) ) {
				$path = self::get_native_shop_template_path();
				if ( is_readable( $path ) ) {
					return $path;
				}
				// Soft-fail to fallback page
				$fb = self::ensure_fallback_shop_page( false );
				if ( ! empty( $fb['url'] ) ) {
					wp_safe_redirect( $fb['url'], 302 );
					exit;
				}
			}
			self::render_standalone_shop_page();
			exit;
		}

		return $template;
	}

	/**
	 * Enqueue front-end assets.
	 */

	/**
	 * Tag body so theme-kill CSS can target.
	 */
	public static function filter_storefront_body_class( $classes ) {
		if ( self::is_bare_storefront_request() ) {
			$classes[] = 'amphp-react-storefront';
			$classes[] = 'amphp-bare-mode';
		}
		return $classes;
	}

	/**
	 * Hide WP admin bar on customer storefront for cleaner paint (admins still get mini strip on bare page).
	 */
	public static function maybe_hide_admin_bar_on_storefront( $show ) {
		if ( ! empty( $GLOBALS['amphp_bare_storefront'] ) ) {
			return false;
		}
		return $show;
	}

	public static function enqueue_front_assets() {
		if ( is_admin() ) {
			return;
		}
		// Bare standalone page never reaches here (exits earlier). For shortcode-in-theme:
		$settings = self::get_settings();
		$load = ! empty( $settings['enable_shop_takeover'] ) || ! empty( $settings['enable_scraped_products'] );
		if ( ! $load ) {
			return;
		}
		// Dequeue theme/plugin bulk after they register.
		add_action( 'wp_print_styles', array( __CLASS__, 'strip_all_theme_assets' ), 100 );
		add_action( 'wp_print_scripts', array( __CLASS__, 'strip_all_theme_assets' ), 100 );
		add_action( 'wp_footer', array( __CLASS__, 'strip_all_theme_assets' ), 1 );
		self::enqueue_storefront_react_assets();
	}

	/**
	 * Render Standalone Modern Shop Page.
	 * Completely removes the WordPress theme's header and menu, replacing them with
	 * our dedicated custom header, navbar, mega categories dropdown, and hamburger drawer.
	 */
	public static function render_standalone_shop_page() {
		// Bare document: NO wp_head / wp_footer → zero theme CSS/JS.
		$GLOBALS['amphp_bare_storefront'] = true;
		$settings = self::get_settings();
		$title    = (string) ( $settings['shop_title'] ?? 'فروشگاه آنلاین' );
		$site     = get_bloginfo( 'name' );
		// Prefer PHP proxy URLs so shop works even when /asset/*.js is missing or blocked by host.
		$ver = self::storefront_assets_ver();
		$css_url  = add_query_arg( array( 'amphp_sf' => 'storefront.css', 'ver' => $ver ), home_url( '/' ) );
		$js_url   = add_query_arg( array( 'amphp_sf' => 'storefront.js', 'ver' => $ver ), home_url( '/' ) );
		// Fail only if neither disk files NOR embedded payload exist (v13.1.5+ embeds JS/CSS).
		$shop_html = self::render_shop_shortcode( true ); // skip asset tags; we print once below
		if ( ! self::has_storefront_assets() ) {
			/* v13.3.17: soft-fail → native fallback page if available */
			$fb = self::ensure_fallback_shop_page( false );
			if ( ! empty( $fb['url'] ) && empty( $GLOBALS['amphp_fallback_redirecting'] ) ) {
				$GLOBALS['amphp_fallback_redirecting'] = true;
				wp_safe_redirect( $fb['url'], 302 );
				exit;
			}
			$shop_html = '<div style="max-width:640px;margin:48px auto;padding:24px;background:#fef2f2;border:1px solid #fecaca;border-radius:16px;font-family:Tahoma,sans-serif;direction:rtl;text-align:right;line-height:1.8">'
				. '<div style="font-size:1.1rem;font-weight:900;color:#b91c1c;margin-bottom:8px">فایل‌های ویترین روی سرور پیدا نشد</div>'
				. '<p style="margin:0 0 10px;color:#7f1d1d;font-weight:700">agent.php را دوباره آپلود کنید یا پوشه <code>includes/storefront/</code> را کنار agent.php قرار دهید.</p>'
				. '<p style="margin:0;color:#64748b;font-size:.88rem">مسیر افزونه: <code style="direction:ltr;display:inline-block">' . esc_html( plugin_dir_path( __FILE__ ) ) . '</code></p>'
				. '</div>';
		}

		$adminbar  = '';
		if ( is_user_logged_in() && current_user_can( 'manage_options' ) ) {
			// Minimal admin bar only (no theme).
			if ( function_exists( 'wp_admin_bar_render' ) ) {
				ob_start();
				// Lightweight top strip instead of full admin-bar scripts.
				$admin_url = admin_url( 'admin.php?page=scraper-auto-shop' );
				echo '<div id="amphp-mini-admin" style="position:fixed;top:0;left:0;right:0;z-index:99999;background:#1d2327;color:#f0f0f1;font:12px/32px Tahoma,sans-serif;padding:0 12px;display:flex;justify-content:space-between;direction:rtl;">';
				echo '<a href="' . esc_url( $admin_url ) . '" style="color:#72aee6;text-decoration:none;font-weight:700;">⚙ مدیریت فروشگاه</a>';
				echo '<a href="' . esc_url( admin_url() ) . '" style="color:#f0f0f1;text-decoration:none;">پیشخوان وردپرس</a>';
				echo '</div>';
				echo '<style>html{--sf-adminbar:32px}body{padding-top:32px!important}</style>';
				$adminbar = ob_get_clean();
			}
		}
		header( 'Content-Type: text/html; charset=UTF-8' );
		header( 'X-AMPHP-Storefront: bare-v13.3.19' );
		// Avoid caching heavy theme shells.
		nocache_headers();
		?><!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
<meta name="theme-color" content="<?php echo esc_attr( $settings['accent_color'] ?? '#2563eb' ); ?>">
<meta name="robots" content="index,follow">
<title><?php echo esc_html( $title . ( $site ? ' | ' . $site : '' ) ); ?></title>
<link rel="preconnect" href="<?php echo esc_url( admin_url() ); ?>">
<?php echo self::get_storefront_font_boot_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<link rel="stylesheet" href="<?php echo esc_url( $css_url ); ?>?ver=<?php echo esc_attr( $ver ); ?>" id="amphp-storefront-css">
<style id="amphp-bare-reset">
html,body{margin:0;padding:0;background:#f5f5f5;font-family:var(--app-font,Tahoma,system-ui,sans-serif)}
*{box-sizing:border-box}
img{max-width:100%;height:auto}
#wpadminbar,.wp-site-blocks,header.wp-block-template-part,footer.wp-block-template-part{display:none!important}
</style>
</head>
<body class="scraper-standalone-shop-takeover amphp-react-storefront amphp-bare">
<?php echo $adminbar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
<div class="scraper-shop-fullscreen-wrap" id="amphp-bare-root">
<?php echo $shop_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
</div>
<script src="<?php echo esc_url( $js_url ); ?>?ver=<?php echo esc_attr( $ver ); ?>" id="amphp-storefront-js"></script>
<script>
(function(){
  setTimeout(function(){
    var el = document.getElementById('amphp-storefront-root');
    if (!el || el.getAttribute('data-mounted') === '1') return;
    if (el.querySelector('.amphp-sf-bootwait')) {
      el.innerHTML = '<div style="padding:28px;text-align:center;font-family:Tahoma,sans-serif;color:#b91c1c;font-weight:800;line-height:1.7">فروشگاه بارگذاری نشد.<br><span style="font-weight:600;color:#64748b;font-size:.85rem">اسکریپت لود نشد. اگر تازه آپدیت کرده‌اید کش را پاک کنید.<br>URL: <?php echo esc_js( $js_url ); ?></span></div>';
    }
  }, 8000);
})();
</script>
</body>
</html><?php
		exit;
	}

	/**
	 * Shortcode [scraped_shop] / [modern_shop] HTML Renderer.
	 * 100% customer-facing, ultra-modern luxury e-commerce experience.
	 */
	public static function render_shop_shortcode( $atts = array(), $content = null, $tag = '' ) {
		// Allow internal bare-shell call: render_shop_shortcode( true )
		$bare_assets = false;
		if ( true === $atts || 1 === $atts || '1' === $atts ) {
			$bare_assets = true;
			$atts = array();
		} elseif ( is_array( $atts ) && ! empty( $atts['bare'] ) ) {
			$bare_assets = true;
		}

		$settings = self::get_settings();
		$products = self::get_all_scraped_products();
		$total_catalog = is_array( $products ) ? count( $products ) : 0;

		/* v13.3.8: همهٔ محصولات کاتالوگ — بدون سقف ۱۲۰.
		   payload lean است (بدون گالری/توضیح بلند) تا TTFB سبک بماند. */
		$safe_products = array();
		foreach ( (array) $products as $p ) {
			if ( ! is_array( $p ) ) {
				continue;
			}
			$desc = wp_strip_all_tags( (string) ( $p['description'] ?? $p['short_desc'] ?? '' ) );
			if ( function_exists( 'mb_substr' ) ) {
				$desc = mb_substr( $desc, 0, 160 );
			} else {
				$desc = substr( $desc, 0, 160 );
			}
			$src = sanitize_key( (string) ( $p['source'] ?? '' ) );
			if ( ! in_array( $src, array( 'scraper', 'woocommerce' ), true ) ) {
				$src = ( strpos( (string) ( $p['id'] ?? '' ), 'wc_' ) === 0 ) ? 'woocommerce' : 'scraper';
			}
			$safe_products[] = array(
				'id'           => (string) ( $p['id'] ?? '' ),
				'title'        => (string) ( $p['title'] ?? '' ),
				'has_price'    => ! empty( $p['has_price'] ),
				'price'        => floatval( $p['price'] ?? 0 ),
				'price_formatted' => (string) ( $p['price_formatted'] ?? '' ),
				'old_price'    => floatval( $p['old_price'] ?? 0 ),
				'old_price_formatted' => (string) ( $p['old_price_formatted'] ?? '' ),
				'has_discount' => ! empty( $p['has_discount'] ),
				'discount_pct' => intval( $p['discount_pct'] ?? 0 ),
				'image'        => esc_url_raw( (string) ( $p['image'] ?? '' ) ),
				'category'     => (string) ( $p['category'] ?? 'عمومی' ),
				'description'  => $desc,
				'in_stock'     => isset( $p['in_stock'] ) ? (bool) $p['in_stock'] : true,
				'source'       => $src,
				'source_label' => (string) ( $p['source_label'] ?? ( $src === 'woocommerce' ? 'ووکامرس' : 'اسکرپر' ) ),
			);
		}

		$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
		$admin_url   = current_user_can( 'manage_options' ) ? admin_url( 'admin.php?page=scraper-auto-shop' ) : '';
		$checkout    = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/' );
		$gateways    = self::get_active_payment_gateways_list();

		/* v13.1.8: سفارش پرداخت‌شده از بازگشت درگاه */
		$paid_order_boot = null;
		if ( ! empty( $_GET['amphp_paid'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$po_id  = absint( $_GET['amphp_order'] ?? $_GET['order_id'] ?? 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$po_key = sanitize_text_field( wp_unslash( $_GET['amphp_key'] ?? $_GET['key'] ?? '' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$po_msg = (string) ( $settings['checkout_success_msg'] ?? 'سفارش شما با موفقیت تکمیل شد. از خریدتان سپاسگزاریم!' );
			$po_total = 0;
			$po_fmt = '';
			$po_pm = '';
			$po_pt = '';
			if ( $po_id > 0 && function_exists( 'wc_get_order' ) ) {
				$po = wc_get_order( $po_id );
				if ( $po && ( ! $po_key || $po->get_order_key() === $po_key ) ) {
					$po_total = (float) $po->get_total();
					$po_fmt   = self::format_price( $po_total, (string) ( $settings['currency_symbol'] ?? 'تومان' ) );
					$po_pm    = (string) $po->get_payment_method();
					$po_pt    = (string) $po->get_payment_method_title();
				}
			}
			$paid_order_boot = array(
				'order_id'        => $po_id,
				'order_key'       => $po_key,
				'total'           => $po_total,
				'total_formatted' => $po_fmt,
				'payment_method'  => $po_pm,
				'payment_title'   => $po_pt,
				'message'         => $po_msg,
				'paid'            => true,
			);
		}

		$boot = array(
			'settings' => array(
				'shop_title'              => (string) ( $settings['shop_title'] ?? 'فروشگاه آنلاین' ),
				'shop_subtitle'           => (string) ( $settings['shop_subtitle'] ?? '' ),
				'accent_color'            => (string) ( $settings['accent_color'] ?? '#2563eb' ),
				'currency_symbol'         => (string) ( $settings['currency_symbol'] ?? 'تومان' ),
				'contact_phone'           => (string) ( $settings['contact_phone'] ?? '' ),
				'support_hours'           => (string) ( $settings['support_hours'] ?? '' ),
				'top_bar_notice'          => (string) ( $settings['top_bar_notice'] ?? '' ),
				'show_top_bar'            => ! empty( $settings['show_top_bar'] ),
				'show_features_banner'    => ! empty( $settings['show_features_banner'] ),
				'show_animated_stats'     => ! empty( $settings['show_animated_stats'] ),
				'show_special_badge'      => ! empty( $settings['show_special_badge'] ),
				'sticky_header'           => ! empty( $settings['sticky_header'] ),
				'default_column_layout'   => (string) ( $settings['default_column_layout'] ?? '1' ),
				'products_per_page'       => intval( $settings['products_per_page'] ?? 20 ),
				'store_template'          => (string) ( $settings['store_template'] ?? 'digikala' ),
				'store_palette'           => (string) ( $settings['store_palette'] ?? 'digikala-red' ),
				'enable_custom_checkout'  => ! empty( $settings['enable_custom_checkout'] ),
				'checkout_title'          => (string) ( $settings['checkout_title'] ?? 'تسویه حساب امن' ),
				'checkout_note'           => (string) ( $settings['checkout_note'] ?? '' ),
				'checkout_require_login'  => ! empty( $settings['checkout_require_login'] ),
				'checkout_field_name'     => ! isset( $settings['checkout_field_name'] ) || ! empty( $settings['checkout_field_name'] ),
				'checkout_field_name_req' => ! empty( $settings['checkout_field_name_req'] ),
				'checkout_field_phone'    => ! isset( $settings['checkout_field_phone'] ) || ! empty( $settings['checkout_field_phone'] ),
				'checkout_field_phone_req'=> ! empty( $settings['checkout_field_phone_req'] ),
				'checkout_field_email'    => ! empty( $settings['checkout_field_email'] ),
				'checkout_field_email_req'=> ! empty( $settings['checkout_field_email_req'] ),
				'checkout_field_province' => ! isset( $settings['checkout_field_province'] ) || ! empty( $settings['checkout_field_province'] ),
				'checkout_field_province_req' => ! empty( $settings['checkout_field_province_req'] ),
				'checkout_field_city'     => ! isset( $settings['checkout_field_city'] ) || ! empty( $settings['checkout_field_city'] ),
				'checkout_field_city_req' => ! empty( $settings['checkout_field_city_req'] ),
				'checkout_field_address'  => ! isset( $settings['checkout_field_address'] ) || ! empty( $settings['checkout_field_address'] ),
				'checkout_field_address_req' => ! empty( $settings['checkout_field_address_req'] ),
				'checkout_field_postal'   => ! empty( $settings['checkout_field_postal'] ),
				'checkout_field_postal_req' => ! empty( $settings['checkout_field_postal_req'] ),
				'checkout_field_notes'    => ! empty( $settings['checkout_field_notes'] ),
				'checkout_field_notes_req'=> ! empty( $settings['checkout_field_notes_req'] ),
				'checkout_show_gateways'  => ! isset( $settings['checkout_show_gateways'] ) || ! empty( $settings['checkout_show_gateways'] ),
				'checkout_cod_label'      => (string) ( $settings['checkout_cod_label'] ?? 'پرداخت در محل (COD)' ),
				'checkout_success_msg'    => (string) ( $settings['checkout_success_msg'] ?? '' ),
				'checkout_show_shipping'  => ! isset( $settings['checkout_show_shipping'] ) || ! empty( $settings['checkout_show_shipping'] ),
				'checkout_show_map'       => ! isset( $settings['checkout_show_map'] ) || ! empty( $settings['checkout_show_map'] ),
				'neshan_api_key_set'      => ! empty( $settings['neshan_api_key'] ),
				'shipping_origin_city'    => (string) ( $settings['shipping_origin_city'] ?? 'تهران' ),
				'shipping_origin_lat'     => (string) ( $settings['shipping_origin_lat'] ?? '35.6892' ),
				'shipping_origin_lng'     => (string) ( $settings['shipping_origin_lng'] ?? '51.3890' ),
				'post_shipping_enabled'   => ! isset( $settings['post_shipping_enabled'] ) || ! empty( $settings['post_shipping_enabled'] ),
				'chapar_shipping_enabled' => ! isset( $settings['chapar_shipping_enabled'] ) || ! empty( $settings['chapar_shipping_enabled'] ),
				'tipax_shipping_enabled'  => ! isset( $settings['tipax_shipping_enabled'] ) || ! empty( $settings['tipax_shipping_enabled'] ),
				'enable_ai_product_images' => ! isset( $settings['enable_ai_product_images'] ) || ! empty( $settings['enable_ai_product_images'] ),
				'enable_support_chat'     => ! empty( $settings['enable_support_chat'] ),
				'chat_theme'              => (string) ( $settings['chat_theme'] ?? 'royal-blue' ),
				'chat_button_style'       => (string) ( $settings['chat_button_style'] ?? 'pill-label' ),
				'chat_button_position'    => (string) ( $settings['chat_button_position'] ?? 'left' ),
				'chat_window_title'       => (string) ( $settings['chat_window_title'] ?? 'پشتیبانی آنلاین فروشگاه' ),
				'chat_welcome_message'    => (string) ( $settings['chat_welcome_message'] ?? '' ),
				'free_shipping_threshold' => floatval( $settings['free_shipping_threshold'] ?? 400000 ),
				'catalog_source'         => (string) self::resolve_catalog_source( $settings ),
				'catalog_merge_prefer'   => (string) ( $settings['catalog_merge_prefer'] ?? 'scraper' ),
				'shop_title_font'         => (string) ( $settings['shop_title_font'] ?? 'vazirmatn' ),
				'app_font'                => (string) ( $settings['shop_title_font'] ?? 'vazirmatn' ),
				'shop_title_font_size'    => (string) ( $settings['shop_title_font_size'] ?? 'normal' ),
				'shop_title_font_weight'  => (string) ( $settings['shop_title_font_weight'] ?? '900' ),
			),
			'products' => $safe_products,
			'urls'     => array(
				'account'  => esc_url_raw( $account_url ),
				'admin'    => esc_url_raw( $admin_url ),
				'home'     => esc_url_raw( home_url( '/' ) ),
				'checkout' => esc_url_raw( $checkout ),
				'fallback_shop' => esc_url_raw( ( $settings['native_fallback_page_id'] ?? 0 ) ? get_permalink( intval( $settings['native_fallback_page_id'] ) ) : '' ),
				'shop'     => esc_url_raw( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ),
			),
			'ajax'     => array(
				'ajaxUrl'    => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
				'cartNonce'  => wp_create_nonce( 'scraper_cart_nonce' ),
				'chatNonce'  => wp_create_nonce( 'scraper_support_chat_nonce' ),
				'checkoutUrl'=> esc_url_raw( $checkout ),
			),
			'gateways' => $gateways,
			'paid_order' => $paid_order_boot,
			'meta'     => array(
				'version'     => '13.3.19',
				'asset_ver'   => self::storefront_assets_ver(),
				'engine'      => 'react',
				'count'       => count( $safe_products ),
				'total_count' => isset( $total_catalog ) ? (int) $total_catalog : count( $safe_products ),
				'catalog_source' => (string) self::resolve_catalog_source( $settings ),
				'scraper_count'  => count( array_filter( $safe_products, function ( $x ) { return ( $x['source'] ?? '' ) === 'scraper'; } ) ),
				'woo_count'      => count( array_filter( $safe_products, function ( $x ) { return ( $x['source'] ?? '' ) === 'woocommerce'; } ) ),
				'is_admin'    => current_user_can( 'manage_options' ),
			),
		);

		$css_url = self::storefront_asset_url( 'storefront.css' );
		$js_url  = self::storefront_asset_url( 'storefront.js' );
		$ver = self::storefront_assets_ver();

		// Mark assets as printed so wp_enqueue does not double-load the bundle.
		$GLOBALS['amphp_storefront_assets_printed'] = true;

		$boot_json = wp_json_encode( $boot, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_SLASHES );
		if ( false === $boot_json ) {
			$boot_json = '{"settings":{},"products":[],"urls":{},"ajax":{},"meta":{"error":"json"}}';
		}

		ob_start();
		?>
		<!-- ویترین فروشگاه v13.3.19 -->
		<?php echo self::get_storefront_font_boot_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<?php if ( empty( $bare_assets ) ) : ?>
		<link rel="stylesheet" href="<?php echo esc_url( $css_url ); ?>?ver=<?php echo esc_attr( $ver ); ?>" id="amphp-storefront-css" />
		<?php endif; ?>
		<div id="amphp-storefront-root" class="amphp-storefront-root" data-engine="react" dir="rtl">
			<div class="amphp-sf-bootwait" style="padding:32px 16px;text-align:center;font-family:Tahoma,sans-serif;color:#64748b;font-size:.95rem;font-weight:700;">در حال بارگذاری…</div>
		</div>
		<script id="amphp-storefront-boot">
		window.AMPHP_STOREFRONT = <?php echo $boot_json; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>;
		</script>
		<?php if ( empty( $bare_assets ) ) : ?>
		<script src="<?php echo esc_url( $js_url ); ?>?ver=<?php echo esc_attr( $ver ); ?>" id="amphp-storefront-js"></script>
		<script>
		(function(){
			// Fail-soft if bundle 404 / blocked
			setTimeout(function(){
				var el = document.getElementById('amphp-storefront-root');
				if (!el || el.getAttribute('data-mounted') === '1') return;
				if (el.querySelector('.amphp-sf-bootwait')) {
					el.innerHTML = '<div style="padding:28px;text-align:center;font-family:Tahoma,sans-serif;color:#b91c1c;font-weight:800;line-height:1.7">فروشگاه بارگذاری نشد.<br><span style="font-weight:600;color:#64748b;font-size:.85rem">مسیر اسکریپت را بررسی کنید یا کش را پاک کنید.</span></div>';
				}
			}, 8000);
		})();
		</script>
		<?php endif; ?>
		<?php
		return ob_get_clean();
	}

	/**
	 * Candidate filesystem paths for a storefront build file.
	 *
	 * @param string $file basename e.g. storefront.js
	 * @return string[]
	 */

	/**
	 * Load scraper4.php font boot snippet (APP_FONTS + --app-font) if available.
	 * Falls back to a self-contained registry matching scraper4 keys.
	 *
	 * @return string HTML (style+script) safe for <head>
	 */
	public static function get_storefront_font_boot_html() {
		static $html = null;
		if ( null !== $html ) {
			return $html;
		}
		$scraper = plugin_dir_path( __FILE__ ) . 'scraper4.php';
		if ( is_readable( $scraper ) ) {
			if ( ! defined( 'SCRAPER4_NO_RENDER' ) ) {
				define( 'SCRAPER4_NO_RENDER', true );
			}
			$ob = ob_get_level();
			@ob_start();
			try {
				require_once $scraper;
			} catch ( \Throwable $e ) {
				// ignore
			}
			while ( ob_get_level() > $ob ) {
				@ob_end_clean();
			}
			if ( function_exists( 'app_font_boot' ) ) {
				$html = (string) app_font_boot();
				return $html;
			}
		}
		// Inline fallback (same keys as scraper4 registry).
		$fb = 'Tahoma,system-ui,sans-serif';
		$reg = array(
			'system'    => array( 'label' => 'سیستم', 'stack' => $fb, 'css' => '', 'face' => '' ),
			'vazirmatn' => array( 'label' => 'وزیرمتن', 'stack' => 'Vazirmatn,' . $fb, 'css' => 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css', 'face' => '' ),
			'vazir'     => array( 'label' => 'وزیر', 'stack' => 'Vazir,' . $fb, 'css' => 'https://cdn.jsdelivr.net/gh/rastikerdar/vazir-font@30.1.0/dist/font-face.css', 'face' => '' ),
			'sahel'     => array( 'label' => 'ساحل', 'stack' => 'Sahel,' . $fb, 'css' => 'https://cdn.jsdelivr.net/gh/rastikerdar/sahel-font@3.4.0/dist/font-face.css', 'face' => '' ),
			'samim'     => array( 'label' => 'صمیم', 'stack' => 'Samim,' . $fb, 'css' => 'https://cdn.jsdelivr.net/gh/rastikerdar/samim-font@4.0.5/dist/font-face.css', 'face' => '' ),
			'shabnam'   => array( 'label' => 'شبنم', 'stack' => 'Shabnam,' . $fb, 'css' => 'https://cdn.jsdelivr.net/gh/rastikerdar/shabnam-font@5.0.1/dist/font-face.css', 'face' => '' ),
			'parastoo'  => array( 'label' => 'پرستو', 'stack' => 'Parastoo,' . $fb, 'css' => 'https://cdn.jsdelivr.net/gh/rastikerdar/parastoo-font@2.0.0/dist/font-face.css', 'face' => '' ),
			'iranyekan' => array( 'label' => 'ایران‌یکان', 'stack' => 'IRANYekan,' . $fb, 'css' => '', 'face' => '' ),
			'yekan'     => array( 'label' => 'یکان', 'stack' => 'Yekan,' . $fb, 'css' => '', 'face' => '' ),
			'estedad'   => array( 'label' => 'استعداد', 'stack' => 'Estedad,' . $fb, 'css' => '', 'face' => '' ),
		);
		/* v13.3.8: فونت سرور از settings/connections — برای همهٔ بازدیدکننده‌ها */
		$server_key = 'vazirmatn';
		try {
			$st = self::get_settings();
			$cand = trim( (string) ( $st['shop_title_font'] ?? $st['app_font'] ?? '' ) );
			if ( $cand !== '' && isset( $reg[ $cand ] ) ) {
				$server_key = $cand;
			} elseif ( $cand !== '' && isset( $reg['vazirmatn'] ) ) {
				/* alias map */
				$map = array( 'dana' => 'vazirmatn', 'yekanbakh' => 'yekan', 'iransans' => 'iranyekan', 'morabba' => 'sahel', 'custom' => 'vazirmatn' );
				$server_key = isset( $map[ $cand ] ) ? $map[ $cand ] : 'vazirmatn';
			}
		} catch ( \Throwable $e ) {
			$server_key = 'vazirmatn';
		}
		if ( ! isset( $reg[ $server_key ] ) ) {
			$server_key = 'vazirmatn';
		}
		$server_stack = $reg[ $server_key ]['stack'];
		$server_css   = $reg[ $server_key ]['css'];
		$json = wp_json_encode( $reg, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
		$html  = '<style id="appFontVars">:root{--app-font:' . esc_attr( $server_stack ) . '}</style>';
		if ( $server_css !== '' ) {
			$html .= '<link id="appFontLink_' . esc_attr( $server_key ) . '" rel="stylesheet" href="' . esc_url( $server_css ) . '">';
		}
		$html .= '<script>(function(){var F=' . $json . ';var KEY="scraper_font",FB=' . wp_json_encode( $fb ) . ',SERVER_FONT=' . wp_json_encode( $server_key ) . ';window.APP_FONTS=F;window.APP_FONT_KEY=KEY;window.APP_FONT_FALLBACK=FB;window.APP_FONT_SERVER=SERVER_FONT;function head(){return document.head||document.documentElement;}function readFont(){if(SERVER_FONT&&F[SERVER_FONT])return SERVER_FONT;try{var v=localStorage.getItem(KEY);return (v&&F[v])?v:"vazirmatn";}catch(e){return"vazirmatn";}}function applyFont(k,save){if(!F[k])k="vazirmatn";var f=F[k];if(save){try{localStorage.setItem(KEY,k);}catch(e){}}try{document.documentElement.style.setProperty("--app-font",f.stack);if(document.body)document.body.style.fontFamily=f.stack;}catch(e){}if(f.css){var lid="appFontLink_"+k;if(!document.getElementById(lid)){var l=document.createElement("link");l.id=lid;l.rel="stylesheet";l.href=f.css;head().appendChild(l);}}if(f.face){var sid="appFontFace_"+k;if(!document.getElementById(sid)){var s=document.createElement("style");s.id=sid;s.appendChild(document.createTextNode(f.face));head().appendChild(s);}}window.APP_FONT_CURRENT=k;return k;}window.appFontApply=applyFont;window.appFontCurrent=readFont;applyFont(readFont(),false);})();</script>';
		return $html;
	}

	public static function storefront_asset_paths( $file ) {
		$file = basename( (string) $file );
		$base = plugin_dir_path( __FILE__ );
		return array_values( array_filter( array(
			$base . 'asset/js/storefront/' . $file,
			$base . 'includes/storefront/' . $file,
			$base . 'storefront/' . $file,
			dirname( $base ) . '/asset/js/storefront/' . $file,
		) ) );
	}

	/**
	 * Absolute path to first existing storefront asset on disk, or empty string.
	 *
	 * @param string $file
	 * @return string
	 */
	public static function storefront_asset_path( $file ) {
		foreach ( self::storefront_asset_paths( $file ) as $p ) {
			if ( $p && is_readable( $p ) ) {
				return $p;
			}
		}
		return '';
	}

	/**
	 * Gzip+base64 payload baked into agent.php so a single-file upload still boots the shop.
	 * Disk files (asset/ or includes/storefront/) still take priority when present.
	 *
	 * @return array<string,array{mime:string,gz:string}>
	 */
	
	/**
	 * Cache-busting version for storefront assets (content hash).
	 *
	 * @return string
	 */
	public static function storefront_assets_ver() {
		static $ver = null;
		if ( null !== $ver ) {
			return $ver;
		}
		$parts = array( '13.3.19' );
		$js = self::storefront_asset_path( 'storefront.js' );
		if ( $js && is_readable( $js ) ) {
			$parts[] = substr( md5_file( $js ), 0, 10 );
		} else {
			$pack = self::get_embedded_storefront_assets();
			$gz = (string) ( $pack['storefront.js']['gz'] ?? '' );
			$parts[] = $gz !== '' ? substr( md5( $gz ), 0, 10 ) : 'embed';
		}
		$ver = implode( '.', $parts );
		return $ver;
	}

public static function get_embedded_storefront_assets() {
		static $cache = null;
		if ( null !== $cache ) {
			return $cache;
		}
		// Prefer external pack next to plugin (always rebuildable).
		$pack = plugin_dir_path( __FILE__ ) . 'includes/storefront/embedded-assets.php';
		if ( is_readable( $pack ) ) {
			$loaded = include $pack;
			if ( is_array( $loaded ) && ! empty( $loaded['storefront.js']['gz'] ) ) {
				$cache = $loaded;
				return $cache;
			}
		}
		// Inline fallback baked at build time (v13.3.19) — single-file deploy.
		$cache = array(
			'storefront.js'  => array(
				'mime' => 'application/javascript; charset=UTF-8',
				'gz'   => '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',
			),
			'storefront.css' => array(
				'mime' => 'text/css; charset=UTF-8',
				'gz'   => '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',
			),
		);
		return $cache;
	}

	/**
	 * Decode one embedded asset body, or empty string.
	 *
	 * @param string $file
	 * @return string
	 */
	public static function get_embedded_storefront_body( $file ) {
		$file = basename( (string) $file );
		$all  = self::get_embedded_storefront_assets();
		if ( empty( $all[ $file ]['gz'] ) ) {
			return '';
		}
		$raw = base64_decode( (string) $all[ $file ]['gz'], true );
		if ( false === $raw ) {
			return '';
		}
		if ( function_exists( 'gzuncompress' ) ) {
			$out = @gzuncompress( $raw );
			if ( false !== $out && '' !== $out ) {
				return $out;
			}
		}
		if ( function_exists( 'gzdecode' ) ) {
			$out = @gzdecode( $raw );
			if ( false !== $out && '' !== $out ) {
				return $out;
			}
		}
		return '';
	}

	/**
	 * True when storefront JS can be served (disk or embedded).
	 *
	 * @return bool
	 */
	public static function has_storefront_assets() {
		if ( self::storefront_asset_path( 'storefront.js' ) ) {
			return true;
		}
		$body = self::get_embedded_storefront_body( 'storefront.js' );
		return ( '' !== $body && strlen( $body ) > 1000 );
	}

	/**
	 * Public URL for storefront CSS/JS via PHP proxy (works with agent.php-only deploy).
	 *
	 * @param string $file
	 * @return string
	 */
	public static function storefront_asset_url( $file ) {
		$file = basename( (string) $file );
		$ver = self::storefront_assets_ver();
		return add_query_arg(
			array(
				'amphp_sf' => $file,
				'ver'      => $ver,
			),
			home_url( '/' )
		);
	}

	/**
	 * Stream storefront.js / storefront.css with correct MIME (disk first, then embedded).
	 */
	public static function maybe_serve_storefront_asset() {
		if ( empty( $_GET['amphp_sf'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		$file = basename( sanitize_text_field( wp_unslash( $_GET['amphp_sf'] ) ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( ! in_array( $file, array( 'storefront.js', 'storefront.css' ), true ) ) {
			status_header( 404 );
			header( 'Content-Type: text/plain; charset=UTF-8' );
			echo 'AMPHP storefront: invalid asset';
			exit;
		}

		$mime = ( 'storefront.css' === $file ) ? 'text/css; charset=UTF-8' : 'application/javascript; charset=UTF-8';
		$path = self::storefront_asset_path( $file );
		$body = '';
		$src  = '';

		if ( $path ) {
			// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
			$body = (string) file_get_contents( $path );
			$src  = 'disk:' . $path;
		}
		if ( '' === $body ) {
			$body = self::get_embedded_storefront_body( $file );
			$src  = $body !== '' ? 'embedded' : '';
		}

		if ( '' === $body ) {
			status_header( 404 );
			header( 'Content-Type: text/plain; charset=UTF-8' );
			header( 'X-AMPHP-Asset: missing' );
			echo 'AMPHP storefront asset missing: ' . $file . "\n";
			echo "Upload agent.php v13.1.5+ (embedded assets) or includes/storefront/ files.\n";
			foreach ( self::storefront_asset_paths( $file ) as $p ) {
				echo ' - ' . $p . "\n";
			}
			exit;
		}

		status_header( 200 );
		header( 'Content-Type: ' . $mime );
		header( 'X-Content-Type-Options: nosniff' );
		header( 'X-AMPHP-Asset: ' . $src );
		header( 'Cache-Control: no-cache, no-store, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + 86400 ) . ' GMT' );
		echo $body;
		exit;
	}

	/**
	 * Enqueue built React storefront assets (CSS/JS).
	 */
	public static function enqueue_storefront_react_assets() {
		if ( ! empty( $GLOBALS['amphp_storefront_assets_printed'] ) ) {
			return;
		}
		if ( wp_style_is( 'amphp-storefront', 'enqueued' ) || wp_script_is( 'amphp-storefront', 'enqueued' ) ) {
			return;
		}
		$css = self::storefront_asset_url( 'storefront.css' );
		$js  = self::storefront_asset_url( 'storefront.js' );
		$ver = self::storefront_assets_ver();
		wp_register_style( 'amphp-storefront', $css, array(), $ver );
		wp_enqueue_style( 'amphp-storefront' );
		wp_register_script( 'amphp-storefront', $js, array(), $ver, true );
		wp_enqueue_script( 'amphp-storefront' );
	}

	/**
	 * Admin settings page HTML.
	 */
		public static function render_admin_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$updated = false;
		if ( isset( $_POST['scraper_shop_save'] ) && check_admin_referer( 'scraper_shop_settings_action', 'scraper_shop_settings_nonce' ) ) {
			$__post_cs = sanitize_key( $_POST['catalog_source'] ?? '' );
			if ( ! in_array( $__post_cs, array( 'scraper', 'woocommerce', 'merge' ), true ) ) {
				$__post_cs = ! empty( $_POST['enable_scraped_products'] ) ? 'scraper' : 'woocommerce';
			}
			$__post_mp = sanitize_key( $_POST['catalog_merge_prefer'] ?? 'scraper' );
			if ( ! in_array( $__post_mp, array( 'scraper', 'woocommerce', 'keep_both' ), true ) ) {
				$__post_mp = 'scraper';
			}
			$new_settings = array(
				// Tab 1: Storefront & Appearance
				'enable_shop_takeover'        => ! empty( $_POST['enable_shop_takeover'] ),
				'catalog_source'              => $__post_cs,
				'catalog_merge_prefer'        => $__post_mp,
				'enable_scraped_products'     => ( 'woocommerce' !== $__post_cs ),
				'takeover_front_page'         => ! empty( $_POST['takeover_front_page'] ),
				'enable_native_wp_template'   => ! empty( $_POST['enable_native_wp_template'] ),
				'native_fallback_page_id'     => intval( $_POST['native_fallback_page_id'] ?? 0 ),
				'enable_404_shop_redirect'    => ! empty( $_POST['enable_404_shop_redirect'] ),
				'set_wc_shop_to_fallback'     => ! empty( $_POST['set_wc_shop_to_fallback'] ),
				'auto_create_fallback_page'   => ! empty( $_POST['auto_create_fallback_page'] ),
				'replace_site_header'         => ! empty( $_POST['replace_site_header'] ),
				'show_top_bar'                => ! empty( $_POST['show_top_bar'] ),
				'top_bar_notice'              => sanitize_text_field( $_POST['top_bar_notice'] ?? '' ),
				'contact_phone'               => sanitize_text_field( $_POST['contact_phone'] ?? '' ),
				'support_hours'               => sanitize_text_field( $_POST['support_hours'] ?? '' ),
				'shop_title'                  => sanitize_text_field( $_POST['shop_title'] ?? '' ),
				'shop_title_font'             => sanitize_text_field( $_POST['shop_title_font'] ?? 'vazirmatn' ),
				'shop_title_custom_font'      => sanitize_text_field( $_POST['shop_title_custom_font'] ?? '' ),
				'shop_title_font_size'        => sanitize_text_field( $_POST['shop_title_font_size'] ?? 'normal' ),
				'shop_title_font_weight'      => sanitize_text_field( $_POST['shop_title_font_weight'] ?? '900' ),
				'sticky_header'               => ! empty( $_POST['sticky_header'] ),
				'notif_event_site_visit'      => ! empty( $_POST['notif_event_site_visit'] ),
				'notif_event_product_view'    => ! empty( $_POST['notif_event_product_view'] ),
				'notif_event_add_to_cart'     => ! empty( $_POST['notif_event_add_to_cart'] ),
				'notif_event_checkout_step'   => ! empty( $_POST['notif_event_checkout_step'] ),
				'notif_event_order_placed'    => ! empty( $_POST['notif_event_order_placed'] ),
				'analytics_source'            => in_array( $_POST['analytics_source'] ?? '', array( 'internal', 'wordpress', 'hybrid' ), true ) ? $_POST['analytics_source'] : 'hybrid',
				'hide_admin_nags'             => ! empty( $_POST['hide_admin_nags'] ),
				'shop_subtitle'               => sanitize_text_field( $_POST['shop_subtitle'] ?? '' ),
				'accent_color'                => sanitize_text_field( $_POST['accent_color'] ?? '#2563eb' ),
				'default_column_layout'       => in_array( $_POST['default_column_layout'] ?? '', array( '1', '2' ), true ) ? $_POST['default_column_layout'] : '1',
				'products_per_page'           => intval( $_POST['products_per_page'] ?? 20 ),
				'show_features_banner'        => ! empty( $_POST['show_features_banner'] ),
				'show_animated_stats'         => ! empty( $_POST['show_animated_stats'] ),
				'show_special_badge'          => ! empty( $_POST['show_special_badge'] ),
				'free_shipping_threshold'     => floatval( $_POST['free_shipping_threshold'] ?? 400000 ),
				'store_template'              => sanitize_text_field( $_POST['store_template'] ?? 'digikala' ),
				'store_palette'               => sanitize_text_field( $_POST['store_palette'] ?? 'digikala-red' ),
				'auto_convert_rial'           => ! empty( $_POST['auto_convert_rial'] ),
				'enable_custom_checkout'      => ! empty( $_POST['enable_custom_checkout'] ),
				'checkout_title'              => sanitize_text_field( $_POST['checkout_title'] ?? 'تسویه حساب امن' ),
				'checkout_note'               => sanitize_textarea_field( $_POST['checkout_note'] ?? '' ),
				'checkout_require_login'      => ! empty( $_POST['checkout_require_login'] ),
				'checkout_field_name'         => ! empty( $_POST['checkout_field_name'] ),
				'checkout_field_name_req'     => ! empty( $_POST['checkout_field_name_req'] ),
				'checkout_field_phone'        => ! empty( $_POST['checkout_field_phone'] ),
				'checkout_field_phone_req'    => ! empty( $_POST['checkout_field_phone_req'] ),
				'checkout_field_email'        => ! empty( $_POST['checkout_field_email'] ),
				'checkout_field_email_req'    => ! empty( $_POST['checkout_field_email_req'] ),
				'checkout_field_province'     => ! empty( $_POST['checkout_field_province'] ),
				'checkout_field_province_req' => ! empty( $_POST['checkout_field_province_req'] ),
				'checkout_field_city'         => ! empty( $_POST['checkout_field_city'] ),
				'checkout_field_city_req'     => ! empty( $_POST['checkout_field_city_req'] ),
				'checkout_field_address'      => ! empty( $_POST['checkout_field_address'] ),
				'checkout_field_address_req'  => ! empty( $_POST['checkout_field_address_req'] ),
				'checkout_field_postal'       => ! empty( $_POST['checkout_field_postal'] ),
				'checkout_field_postal_req'   => ! empty( $_POST['checkout_field_postal_req'] ),
				'checkout_field_notes'        => ! empty( $_POST['checkout_field_notes'] ),
				'checkout_field_notes_req'    => ! empty( $_POST['checkout_field_notes_req'] ),
				'checkout_show_gateways'      => ! empty( $_POST['checkout_show_gateways'] ),
				'checkout_cod_label'          => sanitize_text_field( $_POST['checkout_cod_label'] ?? 'پرداخت در محل (COD)' ),
				'checkout_success_msg'        => sanitize_textarea_field( $_POST['checkout_success_msg'] ?? '' ),
				'checkout_show_shipping'      => ! empty( $_POST['checkout_show_shipping'] ),
				'checkout_show_map'           => ! empty( $_POST['checkout_show_map'] ),
				'neshan_api_key'              => sanitize_text_field( $_POST['neshan_api_key'] ?? '' ),
				'shipping_origin_city'        => sanitize_text_field( $_POST['shipping_origin_city'] ?? 'تهران' ),
				'shipping_origin_lat'         => sanitize_text_field( $_POST['shipping_origin_lat'] ?? '35.6892' ),
				'shipping_origin_lng'         => sanitize_text_field( $_POST['shipping_origin_lng'] ?? '51.3890' ),
				'post_shipping_enabled'       => ! empty( $_POST['post_shipping_enabled'] ),
				'chapar_shipping_enabled'     => ! empty( $_POST['chapar_shipping_enabled'] ),
				'tipax_shipping_enabled'      => ! empty( $_POST['tipax_shipping_enabled'] ),
				'post_api_token'              => sanitize_text_field( $_POST['post_api_token'] ?? '' ),
				'chapar_api_token'            => sanitize_text_field( $_POST['chapar_api_token'] ?? '' ),
				'tipax_api_token'             => sanitize_text_field( $_POST['tipax_api_token'] ?? '' ),
				'shipping_base_post'          => floatval( $_POST['shipping_base_post'] ?? 45000 ),
				'shipping_base_chapar'        => floatval( $_POST['shipping_base_chapar'] ?? 65000 ),
				'shipping_base_tipax'         => floatval( $_POST['shipping_base_tipax'] ?? 75000 ),
				'shipping_per_kg'             => floatval( $_POST['shipping_per_kg'] ?? 12000 ),
				'enable_ai_product_images'    => ! empty( $_POST['enable_ai_product_images'] ),
				'ai_image_batch_size'         => max( 1, min( 30, intval( $_POST['ai_image_batch_size'] ?? 8 ) ) ),
				'ai_image_sideload'           => ! empty( $_POST['ai_image_sideload'] ),
				'ai_image_search_lang'        => sanitize_text_field( $_POST['ai_image_search_lang'] ?? 'fa' ),
				'enable_google_image_search'  => ! empty( $_POST['enable_google_image_search'] ),
				'google_cse_api_key'          => sanitize_text_field( $_POST['google_cse_api_key'] ?? '' ),
				'google_cse_cx'               => sanitize_text_field( $_POST['google_cse_cx'] ?? '' ),

				// Tab 2: Pricing & Profit
				'price_markup_percent'        => floatval( $_POST['price_markup_percent'] ?? 0 ),
				'price_fixed_add'             => floatval( $_POST['price_fixed_add'] ?? 0 ),
				'default_fallback_price'      => floatval( $_POST['default_fallback_price'] ?? 150000 ),
				'fallback_price_behavior'     => sanitize_text_field( $_POST['fallback_price_behavior'] ?? 'use_fallback' ),
				'price_rounding'              => sanitize_text_field( $_POST['price_rounding'] ?? '1000' ),
				'currency_symbol'             => sanitize_text_field( $_POST['currency_symbol'] ?? 'تومان' ),

				// Tab 3: Chat Settings & Themes
				'enable_support_chat'         => ! empty( $_POST['enable_support_chat'] ),
				'chat_theme'                  => sanitize_text_field( $_POST['chat_theme'] ?? 'royal-blue' ),
				'chat_button_style'           => sanitize_text_field( $_POST['chat_button_style'] ?? 'pill-label' ),
				'chat_button_position'        => in_array( $_POST['chat_button_position'] ?? '', array( 'left', 'right' ), true ) ? $_POST['chat_button_position'] : 'left',
				'chat_window_title'           => sanitize_text_field( $_POST['chat_window_title'] ?? 'پشتیبانی آنلاین فروشگاه' ),
				'chat_welcome_message'        => sanitize_textarea_field( $_POST['chat_welcome_message'] ?? '' ),
				'chat_field_name_enable'      => ! empty( $_POST['chat_field_name_enable'] ),
				'chat_field_name_required'    => ! empty( $_POST['chat_field_name_required'] ),
				'chat_field_phone_enable'     => ! empty( $_POST['chat_field_phone_enable'] ),
				'chat_field_phone_required'   => ! empty( $_POST['chat_field_phone_required'] ),
				'chat_field_email_enable'     => ! empty( $_POST['chat_field_email_enable'] ),
				'chat_field_email_required'   => ! empty( $_POST['chat_field_email_required'] ),
				'chat_field_subject_enable'   => ! empty( $_POST['chat_field_subject_enable'] ),
				'chat_field_subject_required' => ! empty( $_POST['chat_field_subject_required'] ),

				// Tab 4: AI & Coordination
				'ai_coordination_mode'        => in_array( $_POST['ai_coordination_mode'] ?? '', array( 'ai_first', 'ai_copilot', 'human_only', 'ai_only' ), true ) ? $_POST['ai_coordination_mode'] : 'ai_first',
				'ai_support_name'             => sanitize_text_field( $_POST['ai_support_name'] ?? 'پشتیبان هوشمند فروشگاه' ),
				'ai_system_prompt'            => sanitize_textarea_field( $_POST['ai_system_prompt'] ?? '' ),
				'ai_provider'                 => sanitize_text_field( $_POST['ai_provider'] ?? 'auto' ),
				'ai_api_key'                  => sanitize_text_field( $_POST['ai_api_key'] ?? '' ),
				'ai_model'                    => sanitize_text_field( $_POST['ai_model'] ?? '' ),
				'ai_endpoint'                 => sanitize_text_field( $_POST['ai_endpoint'] ?? '' ),

				// Tab 5: Messengers
				'bale_token'                  => sanitize_text_field( $_POST['bale_token'] ?? '' ),
				'bale_chat_id'                => sanitize_text_field( $_POST['bale_chat_id'] ?? '' ),
				'telegram_token'              => sanitize_text_field( $_POST['telegram_token'] ?? '' ),
				'telegram_chat_id'            => sanitize_text_field( $_POST['telegram_chat_id'] ?? '' ),
				'rubika_token'                => sanitize_text_field( $_POST['rubika_token'] ?? '' ),
				'rubika_chat_id'              => sanitize_text_field( $_POST['rubika_chat_id'] ?? '' ),

				// WordPress Cron (WP-Cron) Integration
				'enable_wp_cron_sync'         => ! empty( $_POST['enable_wp_cron_sync'] ),
				'wp_cron_interval'            => sanitize_text_field( $_POST['wp_cron_interval'] ?? 'every_30_mins' ),
			);
			update_option( self::OPTION_NAME, $new_settings );
			delete_transient( 'scraper_shop_cached_products' );
			/* v13.3.8: همگام فونت با connections.json برای همهٔ بازدیدکننده‌ها */
			/* v13.3.12: keep accent in sync with selected palette so theme colors apply */
			$__pal_map = array(
				'digikala-red' => '#ef394e', 'snapp-green' => '#00d170', 'basalam-coral' => '#ff6b35',
				'torob-red' => '#d32f2f', 'digistyle-rose' => '#e91e63', 'technolife-blue' => '#1a73e8',
				'royal-blue' => '#2563eb', 'luxury-purple' => '#7c3aed', 'amber-gold' => '#d97706',
				'persian-turquoise' => '#0d9488', 'midnight-ink' => '#6366f1', 'forest' => '#16a34a', 'sunset' => '#f97316',
			);
			$__pal = (string) ( $new_settings['store_palette'] ?? '' );
			if ( $__pal && isset( $__pal_map[ $__pal ] ) ) {
				// If user didn't manually override accent in the same save, palette wins.
				// Admin palette radios already set accent_color via JS; still enforce map for reliability.
				$posted_accent = sanitize_text_field( $_POST['accent_color'] ?? '' );
				if ( $posted_accent === '' || strtolower( $posted_accent ) === strtolower( (string) ( $opts['accent_color'] ?? '' ) ) || isset( $_POST['store_palette'] ) ) {
					// Prefer explicit accent if user typed custom; else palette hex.
					if ( $posted_accent !== '' && preg_match( '/^#([0-9a-fA-F]{3}|[0-9a-fA-F]{6})$/', $posted_accent ) ) {
						$new_settings['accent_color'] = $posted_accent;
					} else {
						$new_settings['accent_color'] = $__pal_map[ $__pal ];
					}
				}
			}
			self::sync_ui_font_to_connections( (string) ( $new_settings['shop_title_font'] ?? 'vazirmatn' ) );
			if ( ! empty( $new_settings['enable_native_wp_template'] ) || ! empty( $new_settings['auto_create_fallback_page'] ) ) {
				try {
					$fb_res = self::ensure_fallback_shop_page( ! empty( $new_settings['set_wc_shop_to_fallback'] ) );
					if ( ! empty( $fb_res['id'] ) ) {
						$new_settings['native_fallback_page_id'] = intval( $fb_res['id'] );
						$new_settings['shop_page_id'] = intval( $fb_res['id'] );
						update_option( self::OPTION_NAME, $new_settings );
					}
				} catch ( \Throwable $e ) { /* ignore */ }
			}
			self::sync_wp_cron_schedule( $new_settings );
			self::sync_ai_image_cron_schedule( $new_settings );
			$updated = true;
		}

		$opts             = self::get_settings();
		$scraped_products = self::get_all_scraped_products();
		$profiles_summary = self::get_profiles_summary();
		$active_msgrs     = self::get_active_messengers( $opts );
		$chat_logs        = get_option( 'scraper_support_chat_logs', array() );
		$chat_threads     = self::get_chat_threads();
		$cands_info       = self::get_scraper_ai_candidates();
		$master_ai        = self::get_scraper_master_ai_model( $opts );
		$wpcron_info      = self::get_wpcron_status_info( $opts );
		$analytics_data   = self::get_display_analytics_data();

		$scraper_embed_url  = admin_url( 'admin.php?page=scraper-full-dashboard' );

		// v10.99: وضعیت اتصال مستقیم ووکامرس (از connections.json + محیط WP)
		$woo_conn_all = self::get_scraper_connections();
		$woo_conn     = is_array( $woo_conn_all['woocommerce'] ?? null ) ? $woo_conn_all['woocommerce'] : array();
		$woo_has_wc   = class_exists( 'WooCommerce' ) || function_exists( 'wc_get_product' );
		$woo_sync_mode = (string) ( $woo_conn['sync_mode'] ?? 'api' );
		$woo_enabled  = ! empty( $woo_conn['enabled'] );
		$woo_store    = (string) ( $woo_conn['store_url'] ?? ( function_exists( 'home_url' ) ? home_url( '/' ) : '' ) );
		$woo_direct_ready = $woo_has_wc && $woo_enabled && in_array( $woo_sync_mode, array( 'direct', 'auto' ), true );
		$scraper_direct_url = plugins_url( 'scraper4.php', __FILE__ );
		?>
		<div class="wrap scraper-admin-dashboard" style="direction:rtl; text-align:right; font-family:system-ui, -apple-system, sans-serif; max-width:1240px; margin-top:20px;">
			
			<!-- Header Title Area -->
			<div style="background:linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%); color:#fff; border-radius:18px; padding:24px 30px; margin-bottom:22px; box-shadow:0 12px 30px rgba(15,23,42,0.18); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
				<div>
					<div style="display:inline-flex; align-items:center; gap:6px; background:#2563eb; color:#fff; font-size:0.82rem; font-weight:800; padding:4px 12px; border-radius:20px; margin-bottom:10px;">
						⚡ پنل جامع مدیریت فروشگاه مدرن و اسکرپر هوشمند (نسخه ۱۲.۰)
					</div>
					<h1 style="color:#fff; margin:0 0 6px; font-size:1.6rem; font-weight:900;">تنظیمات یکپارچه فروشگاه، چت آنلاین و هوش مصنوعی</h1>
					<p style="color:#cbd5e1; margin:0; font-size:0.92rem; line-height:1.6; max-width:720px;">
						مدیریت کامل ظاهر ویترین، هماهنگی هوش مصنوعی با ادمین، فیلدهای چت پشتیبانی، اعلان‌های پیام‌رسان‌ها، قیمت‌گذاری و همگام‌سازی مستقیم با ووکامرس.
					</p>
				</div>
				<div style="display:flex; gap:10px; flex-wrap:wrap;">
					<a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>" target="_blank" class="button button-secondary" style="font-weight:800; padding:8px 18px; border-radius:10px; font-size:0.92rem;">
						مشاهده ویترین فروشگاه ↗
					</a>
					<a href="<?php echo esc_url( $scraper_embed_url ); ?>" class="button button-primary" style="background:#2563eb; border:none; font-weight:800; padding:8px 20px; border-radius:10px; font-size:0.92rem;">
						ورود به پنل اسکرپر ⚡
					</a>
				</div>
			</div>

			<?php if ( $updated ) : ?>
				<div class="notice notice-success is-dismissible" style="border-radius:10px; margin-bottom:20px;">
					<p><strong>✅ تمامی تنظیمات با موفقیت ذخیره شدند.</strong></p>
				</div>
			<?php endif; ?>

			<style>
				.scraper-admin-dashboard {
					box-sizing: border-box;
					max-width: 100%;
				}
				/* Mobile Tab Switcher Dropdown */
				.scraper-mobile-tab-bar {
					display: none;
					margin-bottom: 14px;
					background: #ffffff;
					border: 1px solid #cbd5e1;
					border-radius: 12px;
					padding: 12px;
					box-shadow: 0 2px 8px rgba(0,0,0,0.04);
				}
				.scraper-mobile-tab-bar label {
					display: block;
					font-size: 0.88rem;
					font-weight: 800;
					color: #0f172a;
					margin-bottom: 6px;
				}
				.scraper-mobile-tab-bar select {
					width: 100% !important;
					max-width: 100% !important;
					padding: 10px 14px !important;
					font-size: 1rem !important;
					font-weight: 700 !important;
					border: 2px solid #2563eb !important;
					border-radius: 10px !important;
					color: #0f172a !important;
					background: #f8fafc !important;
				}

				/* Navigation Tabs (Always Visible & Wrapping) */
				.scraper-tab-nav-row {
					display: flex;
					justify-content: space-between;
					align-items: center;
					flex-wrap: wrap;
					gap: 12px;
					border-bottom: 2px solid #e2e8f0;
					margin-bottom: 20px;
					padding-bottom: 12px;
				}
				.scraper-tab-nav {
					display: flex;
					gap: 8px;
					flex-wrap: wrap;
					flex: 1;
				}
				.scraper-tab-link {
					display: inline-flex;
					align-items: center;
					justify-content: center;
					gap: 6px;
					padding: 9px 15px;
					font-size: 0.9rem;
					font-weight: 800;
					color: #475569;
					text-decoration: none;
					border-radius: 10px;
					border: 1px solid #cbd5e1;
					background: #f8fafc;
					transition: all 0.2s ease;
					cursor: pointer;
				}
				.scraper-tab-link:hover {
					color: #0f172a;
					background: #e2e8f0;
					border-color: #94a3b8;
				}
				.scraper-tab-link.active {
					color: #ffffff !important;
					background: #2563eb !important;
					border-color: #2563eb !important;
					box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28) !important;
				}
				.scraper-top-save-btn {
					background: #2563eb !important;
					border-color: #2563eb !important;
					color: #ffffff !important;
					font-weight: 800 !important;
					padding: 8px 20px !important;
					border-radius: 10px !important;
					font-size: 0.92rem !important;
					cursor: pointer;
					white-space: nowrap;
					flex-shrink: 0;
				}
				.scraper-top-save-btn:hover {
					background: #1d4ed8 !important;
				}
				.scraper-tab-panel {
					display: none;
				}
				.scraper-tab-panel.active {
					display: block;
					animation: fadeInTab 0.25s ease;
				}
				@keyframes fadeInTab {
					from { opacity: 0; transform: translateY(6px); }
					to { opacity: 1; transform: translateY(0); }
				}
				.admin-card {
					background: #ffffff;
					border: 1px solid #e2e8f0;
					border-radius: 16px;
					padding: 24px;
					margin-bottom: 24px;
					box-shadow: 0 4px 20px rgba(0,0,0,0.03);
				}
				.admin-card-header {
					display: flex;
					justify-content: space-between;
					align-items: center;
					margin-bottom: 20px;
					border-bottom: 1px solid #f1f5f9;
					padding-bottom: 14px;
				}
				.admin-card-header h3 {
					margin: 0;
					font-size: 1.15rem;
					font-weight: 800;
					color: #0f172a;
					display: flex;
					align-items: center;
					gap: 8px;
				}
				.field-badge {
					font-size: 0.8rem;
					font-weight: 700;
					padding: 4px 10px;
					border-radius: 12px;
				}
				.field-badge-blue { background: #eff6ff; color: #2563eb; }
				.field-badge-green { background: #ecfdf5; color: #059669; }
				.field-badge-purple { background: #faf5ff; color: #7c3aed; }

				/* Support Desk Styling (Clean & Organized) */
				.scraper-support-desk {
					box-sizing: border-box;
					max-width: 100%;
				}
				.desk-thread-item:hover {
					background: #f1f5f9;
				}
				.desk-thread-item.active {
					background: #eff6ff !important;
					border-right: 4px solid #2563eb !important;
				}
				.desk-canned-chip {
					background: #f1f5f9;
					border: 1px solid #cbd5e1;
					border-radius: 16px;
					padding: 3px 10px;
					font-family: inherit;
					font-size: 0.75rem;
					font-weight: 700;
					color: #334155;
					cursor: pointer;
					transition: all 0.15s ease;
				}
				.desk-canned-chip:hover {
					background: #e2e8f0;
					color: #0f172a;
					border-color: #94a3b8;
				}
				.desk-bubble {
					max-width: 82%;
					padding: 8px 12px;
					border-radius: 12px;
					font-size: 0.85rem;
					line-height: 1.45;
					word-break: break-word;
				}
				.desk-bubble.customer {
					align-self: flex-start;
					background: #ffffff;
					border: 1px solid #cbd5e1;
					color: #0f172a;
					border-bottom-right-radius: 2px;
				}
				.desk-bubble.ai {
					align-self: flex-start;
					background: #faf5ff;
					border: 1px solid #e9d5ff;
					color: #581c87;
					border-bottom-right-radius: 2px;
				}
				.desk-bubble.admin {
					align-self: flex-end;
					background: #2563eb;
					color: #ffffff;
					border-bottom-left-radius: 2px;
					box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
				}

				/* Card Dropdown Menu Styling */
				.theme-menu-card-item:hover {
					background: #f1f5f9 !important;
				}
				.theme-menu-card-item.active {
					background: #eff6ff !important;
					border-right: 3px solid #2563eb !important;
				}
				#themeCardDropdownMenu::-webkit-scrollbar {
					width: 6px;
				}
				#themeCardDropdownMenu::-webkit-scrollbar-thumb {
					background: #cbd5e1;
					border-radius: 4px;
				}

				/* Mobile Admin Optimizations */
				@media (max-width: 782px) {
					.wrap.scraper-admin-dashboard {
						padding: 8px !important;
						margin-top: 10px !important;
					}
					.scraper-support-desk {
						flex-direction: column !important;
						height: auto !important;
					}
					.desk-threads-col {
						width: 100% !important;
						height: 300px !important;
						border-left: none !important;
						border-bottom: 1px solid #e2e8f0 !important;
					}
					.desk-threads-col.mobile-hide {
						display: none !important;
					}
					.desk-view-col {
						width: 100% !important;
						height: 440px !important;
					}
					#btnDeskBackToList {
						display: inline-block !important;
					}
					.form-table th,
					.form-table td {
						display: block !important;
						width: 100% !important;
						padding: 8px 0 !important;
					}
					.form-table input[type="text"],
					.form-table input[type="password"],
					.form-table select,
					.form-table textarea {
						width: 100% !important;
						max-width: 100% !important;
						font-size: 16px !important;
						min-height: 44px !important;
					}
					.scraper-mobile-tab-bar {
						display: block !important;
					}
					.scraper-tab-nav-row {
						flex-direction: column !important;
						align-items: stretch !important;
						gap: 10px !important;
					}
					.scraper-tab-nav {
						display: grid !important;
						grid-template-columns: repeat(2, 1fr) !important;
						gap: 6px !important;
						width: 100% !important;
					}
					.scraper-tab-link {
						padding: 9px 4px !important;
						font-size: 0.8rem !important;
						width: 100% !important;
						box-sizing: border-box !important;
					}
					.scraper-top-save-btn {
						width: 100% !important;
						text-align: center !important;
						padding: 11px !important;
					}
					.scraper-save-bar {
						position: static !important;
						width: 100% !important;
						box-sizing: border-box !important;
						padding: 14px 12px !important;
						flex-direction: column !important;
						gap: 10px !important;
						text-align: center !important;
					}
					.scraper-save-btn {
						width: 100% !important;
						display: block !important;
						box-sizing: border-box !important;
						padding: 12px !important;
						font-size: 1rem !important;
					}
					.wp-list-table {
						display: block !important;
						width: 100% !important;
						overflow-x: auto !important;
						-webkit-overflow-scrolling: touch !important;
					}
				}
				</style>

			<form method="post" action="" id="scraperAdminForm">
				<?php wp_nonce_field( 'scraper_shop_settings_action', 'scraper_shop_settings_nonce' ); ?>

				<!-- Mobile Quick Tab Selector Dropdown -->
				<div class="scraper-mobile-tab-bar">
					<label for="mobileTabSelector">📂 انتخاب بخش تنظیمات (پرش سریع):</label>
					<select id="mobileTabSelector">
						<option value="tab-storefront">🎨 ۱. ویترین و ظاهر فروشگاه</option>
						<option value="tab-pricing">💰 ۲. قیمت‌گذاری و سود</option>
						<option value="tab-chat">💬 ۳. چت آنلاین و میز پاسخگویی</option>
						<option value="tab-ai">🤖 ۴. هوش مصنوعی و هماهنگی</option>
						<option value="tab-messengers">📡 ۵. پیام‌رسان‌ها (بله/تلگرام/روبیکا)</option>
						<option value="tab-woocommerce">🏪 ۶. فروشگاه و آمار</option>
						<option value="tab-logs">📋 ۷. گزارش پیام‌های مشتریان</option>
					</select>
				</div>

				<!-- All 7 Tabs Navigation (Always Fully Visible & Wrapping) + Top Save Button -->
				<div class="scraper-tab-nav-row">
					<div class="scraper-tab-nav" id="scraperAdminTabs">
						<button type="button" class="scraper-tab-link active" data-tab="tab-storefront">🎨 ۱. ویترین فروشگاه</button>
						<button type="button" class="scraper-tab-link" data-tab="tab-pricing">💰 ۲. قیمت‌گذاری</button>
						<button type="button" class="scraper-tab-link" data-tab="tab-chat">💬 ۳. چت و پاسخگویی</button>
						<button type="button" class="scraper-tab-link" data-tab="tab-ai">🤖 ۴. هوش مصنوعی</button>
						<button type="button" class="scraper-tab-link" data-tab="tab-messengers">📡 ۵. پیام‌رسان‌ها</button>
						<button type="button" class="scraper-tab-link" data-tab="tab-woocommerce">🏪 ۶. فروشگاه</button>
						<button type="button" class="scraper-tab-link" data-tab="tab-logs">📋 ۷. گزارش پیام‌ها</button>
					</div>
					<div>
						<button type="submit" name="scraper_shop_save" class="button scraper-top-save-btn">
							💾 ذخیره تغییرات
						</button>
					</div>
				</div>

				<!-- ================= TAB 1: STOREFRONT & APPEARANCE ================= -->
				<div id="tab-storefront" class="scraper-tab-panel active">
					<div class="admin-card">
						<div class="admin-card-header">
							<h3><span>🎨</span> تنظیمات سربرگ، نوار اعلان و ظاهر فروشگاه</h3>
							<span class="field-badge field-badge-blue">طراحی لوکس اختصاصی</span>
						</div>

						<?php
						$catalog_src = sanitize_key( (string) ( $opts['catalog_source'] ?? '' ) );
						if ( ! in_array( $catalog_src, array( 'scraper', 'woocommerce', 'merge' ), true ) ) {
							$catalog_src = ! empty( $opts['enable_scraped_products'] ) ? 'scraper' : 'woocommerce';
						}
						$merge_pref = sanitize_key( (string) ( $opts['catalog_merge_prefer'] ?? 'scraper' ) );
						if ( ! in_array( $merge_pref, array( 'scraper', 'woocommerce', 'keep_both' ), true ) ) {
							$merge_pref = 'scraper';
						}
						?>
						<!-- v13.3.8: کنترل قالب + منبع کاتالوگ (اسکرپر / ووکامرس / ادغام) -->
						<div style="margin-bottom:24px; background:linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%); border:2px solid #3b82f6; border-radius:16px; padding:22px; box-shadow:0 4px 15px rgba(37,99,235,0.08);">
							<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
								<h4 style="margin:0; font-size:1.15rem; color:#1e3a8a; font-weight:900; display:flex; align-items:center; gap:8px;">
									<span>🎛️</span> کنترل قالب و منبع محصولات ویترین
								</h4>
								<span style="background:#7c3aed; color:#fff; font-size:0.75rem; font-weight:800; padding:4px 12px; border-radius:20px;">ادغام اسکرپر + ووکامرس</span>
							</div>
							<p style="margin:0 0 16px; font-size:0.88rem; color:#334155; line-height:1.7;">
								ظاهر ویترین را با تیک زیر، و <strong>منبع کالاهای نمایش‌داده‌شده</strong> را با سه حالت اسکرپر / ووکامرس / <strong>ادغام هر دو</strong> تنظیم کنید.
							</p>
							<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:14px; margin-bottom:16px;">
								<label style="background:#ffffff; border:1.5px solid #cbd5e1; border-radius:14px; padding:16px; cursor:pointer; display:flex; flex-direction:column; gap:10px;" id="labelStoreTakeover">
									<div style="display:flex; align-items:center; gap:10px;">
										<input type="checkbox" name="enable_shop_takeover" id="chkStoreTakeover" value="1" <?php checked( ! empty( $opts['enable_shop_takeover'] ) ); ?> style="width:20px; height:20px; accent-color:#2563eb;">
										<strong style="font-size:1rem; color:#0f172a;">فعال‌سازی پوسته، هدر و منوی مدرن اختصاصی</strong>
									</div>
									<div style="font-size:0.82rem; color:#64748b; line-height:1.65; padding-right:30px;">
										✅ هدر و منوی قالب وردپرس برداشته و هدر اختصاصی فعال می‌شود.<br>
										❌ با برداشتن، ظاهر به قالب اصلی وردپرس برمی‌گردد.
									</div>
								</label>
								<div style="background:#ffffff; border:1.5px solid #a78bfa; border-radius:14px; padding:16px; display:flex; flex-direction:column; gap:12px;" id="labelCatalogSource">
									<strong style="font-size:1rem; color:#0f172a;">📦 منبع محصولات ویترین</strong>
									<p style="margin:0; font-size:0.8rem; color:#64748b; line-height:1.6;">کدام کالاها در فروشگاه React نمایش داده شوند؟</p>
									<label style="display:flex; gap:10px; align-items:flex-start; padding:10px 12px; border-radius:10px; border:1.5px solid <?php echo ( 'scraper' === $catalog_src ) ? '#10b981' : '#e2e8f0'; ?>; background:<?php echo ( 'scraper' === $catalog_src ) ? '#ecfdf5' : '#f8fafc'; ?>; cursor:pointer;">
										<input type="radio" name="catalog_source" id="catSrcScraper" value="scraper" <?php checked( $catalog_src, 'scraper' ); ?> style="margin-top:3px; accent-color:#10b981;">
										<span><strong style="color:#047857;">فقط پروفایل‌های اسکرپر</strong><span style="display:block; font-size:0.78rem; color:#64748b; margin-top:2px;">محصولات profiles.json با ضریب سود اسکرپر</span></span>
									</label>
									<label style="display:flex; gap:10px; align-items:flex-start; padding:10px 12px; border-radius:10px; border:1.5px solid <?php echo ( 'woocommerce' === $catalog_src ) ? '#2563eb' : '#e2e8f0'; ?>; background:<?php echo ( 'woocommerce' === $catalog_src ) ? '#eff6ff' : '#f8fafc'; ?>; cursor:pointer;">
										<input type="radio" name="catalog_source" id="catSrcWoo" value="woocommerce" <?php checked( $catalog_src, 'woocommerce' ); ?> style="margin-top:3px; accent-color:#2563eb;">
										<span><strong style="color:#1d4ed8;">فقط محصولات ووکامرس</strong><span style="display:block; font-size:0.78rem; color:#64748b; margin-top:2px;">کاتالوگ publish دیتابیس ووکامرس</span></span>
									</label>
									<label style="display:flex; gap:10px; align-items:flex-start; padding:10px 12px; border-radius:10px; border:1.5px solid <?php echo ( 'merge' === $catalog_src ) ? '#7c3aed' : '#e2e8f0'; ?>; background:<?php echo ( 'merge' === $catalog_src ) ? '#f5f3ff' : '#f8fafc'; ?>; cursor:pointer;">
										<input type="radio" name="catalog_source" id="catSrcMerge" value="merge" <?php checked( $catalog_src, 'merge' ); ?> style="margin-top:3px; accent-color:#7c3aed;">
										<span><strong style="color:#6d28d9;">ادغام اسکرپر + ووکامرس</strong><span style="display:block; font-size:0.78rem; color:#64748b; margin-top:2px;">هر دو منبع در یک ویترین</span></span>
									</label>
									<div id="mergePreferBox" style="margin-top:4px; padding:12px; border-radius:10px; background:#faf5ff; border:1px dashed #c4b5fd;<?php echo ( 'merge' === $catalog_src ) ? '' : ' display:none;'; ?>">
										<div style="font-size:0.8rem; font-weight:800; color:#5b21b6; margin-bottom:8px;">در صورت تکراری بودن کالا (عنوان/SKU یکسان):</div>
										<label style="display:flex; gap:8px; align-items:center; font-size:0.8rem; margin-bottom:6px; cursor:pointer;"><input type="radio" name="catalog_merge_prefer" value="scraper" <?php checked( $merge_pref, 'scraper' ); ?> style="accent-color:#7c3aed;"> اولویت با <strong>اسکرپر</strong></label>
										<label style="display:flex; gap:8px; align-items:center; font-size:0.8rem; margin-bottom:6px; cursor:pointer;"><input type="radio" name="catalog_merge_prefer" value="woocommerce" <?php checked( $merge_pref, 'woocommerce' ); ?> style="accent-color:#7c3aed;"> اولویت با <strong>ووکامرس</strong></label>
										<label style="display:flex; gap:8px; align-items:center; font-size:0.8rem; cursor:pointer;"><input type="radio" name="catalog_merge_prefer" value="keep_both" <?php checked( $merge_pref, 'keep_both' ); ?> style="accent-color:#7c3aed;"> <strong>هر دو را نگه دار</strong></label>
									</div>
									<input type="checkbox" name="enable_scraped_products" id="chkScrapedProducts" value="1" <?php checked( 'woocommerce' !== $catalog_src ); ?> style="position:absolute;left:-9999px;opacity:0;width:1px;height:1px;" tabindex="-1" aria-hidden="true">
								</div>
							</div>
							<div id="boxDualStateIndicator" style="background:#ffffff; border:1.5px dashed #2563eb; border-radius:12px; padding:14px 18px; display:flex; flex-wrap:wrap; gap:12px;">
								<div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;"><span style="font-size:0.85rem; color:#64748b;">وضعیت فعال فروشگاه:</span> <strong id="dualStateTitle" style="font-size:0.95rem; color:#1d4ed8;">—</strong></div>
								<div id="dualStateDesc" style="font-size:0.84rem; color:#475569; line-height:1.5; width:100%;">—</div>
							</div>
						</div>

						<!-- Visual eCommerce Storefront Templates Selector -->
						<div style="margin-bottom:24px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:20px;">
							<h4 style="margin:0 0 6px; font-size:1.05rem; color:#0f172a; font-weight:800;">🛍️ انتخاب پوسته فروشگاه (eCommerce Templates)</h4>
							<p style="margin:0 0 16px; font-size:0.85rem; color:#64748b;">
								پوسته ظاهری مورد نظر خود را متناسب با سبک برند انتخاب نمایید تا تمامی رنگ‌بندی‌ها، انحناها و برچسب‌های ویترین به صورت خودکار منطبق شوند:
							</p>

							<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:12px;">
								<!-- Digikala -->
								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? 'digikala' ) === 'digikala' ? '#ef394e' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#ffffff; cursor:pointer; display:flex; flex-direction:column; gap:8px; transition:all 0.2s;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="digikala" <?php checked( ( $opts['store_template'] ?? 'digikala' ), 'digikala' ); ?>>
											<strong style="color:#ef394e; font-size:0.95rem;">قالب دیجی‌کالا</strong>
										</div>
										<span style="background:#fef2f2; color:#ef394e; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">پیشنهاد شگفت‌انگیز</span>
									</div>
									<span style="font-size:0.78rem; color:#64748b; line-height:1.5;">نشان‌های قرمز نمادین، ارسال اکسپرس، برچسب تخفیف درصدی و ساختار استاندارد پرفروش‌ترین فروشگاه ایران.</span>
								</label>

								<!-- SnappShop -->
								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'snappshop' ? '#00c073' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#ffffff; cursor:pointer; display:flex; flex-direction:column; gap:8px; transition:all 0.2s;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="snappshop" <?php checked( ( $opts['store_template'] ?? '' ), 'snappshop' ); ?>>
											<strong style="color:#00c073; font-size:0.95rem;">قالب اسنپ‌شاپ</strong>
										</div>
										<span style="background:#ecfdf5; color:#059669; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">سوپراپ</span>
									</div>
									<span style="font-size:0.78rem; color:#64748b; line-height:1.5;">سبک سبز مدرن سوپراپ اسنپ، انحنای گرد ۱۸ پیکسلی و برچسب‌های ارسال فوری با تحویل پیک.</span>
								</label>

								<!-- Basalam -->
								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'basalam' ? '#df3826' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#ffffff; cursor:pointer; display:flex; flex-direction:column; gap:8px; transition:all 0.2s;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="basalam" <?php checked( ( $opts['store_template'] ?? '' ), 'basalam' ); ?>>
											<strong style="color:#df3826; font-size:0.95rem;">قالب باسلام</strong>
										</div>
										<span style="background:#fff7ed; color:#c2410c; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">بازارچه صمیمی</span>
									</div>
									<span style="font-size:0.78rem; color:#64748b; line-height:1.5;">رنگ مرجانی گرم، برچسب‌های غرفه برتر باسلام، تجربه خرید صمیمی و گفتگوی مستقیم با مشتری.</span>
								</label>

								<!-- Torob -->
								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'torob' ? '#d32f2f' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#ffffff; cursor:pointer; display:flex; flex-direction:column; gap:8px; transition:all 0.2s;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="torob" <?php checked( ( $opts['store_template'] ?? '' ), 'torob' ); ?>>
											<strong style="color:#d32f2f; font-size:0.95rem;">قالب ترب</strong>
										</div>
										<span style="background:#fef2f2; color:#b91c1c; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">کمترین قیمت</span>
									</div>
									<span style="font-size:0.78rem; color:#64748b; line-height:1.5;">طراحی مقایسه قیمت هوشمند ترب، تراکم داده بهینه، ضمانت بازگشت و بهترین قیمت بازار.</span>
								</label>

								<!-- Digistyle -->
								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'digistyle' ? '#e11d48' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#ffffff; cursor:pointer; display:flex; flex-direction:column; gap:8px; transition:all 0.2s;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="digistyle" <?php checked( ( $opts['store_template'] ?? '' ), 'digistyle' ); ?>>
											<strong style="color:#e11d48; font-size:0.95rem;">قالب دیجی‌استایل</strong>
										</div>
										<span style="background:#fff1f2; color:#be123c; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">مد و لاکچری</span>
									</div>
									<span style="font-size:0.78rem; color:#64748b; line-height:1.5;">سبک مد و فشن لوکس، عکس‌های کشیده عمودی، حروف‌نگاری مینیمال و برچسب‌های کالکشن خاص.</span>
								</label>

								<!-- Technolife -->
								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'technolife' ? '#0284c7' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#ffffff; cursor:pointer; display:flex; flex-direction:column; gap:8px; transition:all 0.2s;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="technolife" <?php checked( ( $opts['store_template'] ?? '' ), 'technolife' ); ?>>
											<strong style="color:#0284c7; font-size:0.95rem;">قالب تکنولایف</strong>
										</div>
										<span style="background:#f0f9ff; color:#0369a1; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">دیجیتال و گجت</span>
									</div>
									<span style="font-size:0.78rem; color:#64748b; line-height:1.5;">تم آبی دیجیتال و تکنولوژی، چیپ‌های مشخصات سخت‌افزاری و برچسب ضمانت شرکتی.</span>
								</label>

								<!-- Modern Luxury -->
								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'modern' ? '#2563eb' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#ffffff; cursor:pointer; display:flex; flex-direction:column; gap:8px; transition:all 0.2s;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="modern" <?php checked( ( $opts['store_template'] ?? '' ), 'modern' ); ?>>
											<strong style="color:#2563eb; font-size:0.95rem;">مدرن استاندارد</strong>
										</div>
										<span style="background:#eff6ff; color:#1d4ed8; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">سلطنتی شیشه‌ای</span>
									</div>
									<span style="font-size:0.78rem; color:#64748b; line-height:1.5;">تم آبی لاجوردی، افکت‌های ملایم گلس‌مورفیزم و سایه‌های نرم شناور بر پایه طراحی بین‌المللی.</span>
								</label>

								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'midnight' ? '#6366f1' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#0f172a; cursor:pointer; display:flex; flex-direction:column; gap:8px;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="midnight" <?php checked( ( $opts['store_template'] ?? '' ), 'midnight' ); ?>>
											<strong style="color:#a5b4fc; font-size:0.95rem;">نیمه‌شب (Dark)</strong>
										</div>
										<span style="background:#312e81; color:#c7d2fe; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">کاملاً تیره</span>
									</div>
									<span style="font-size:0.78rem; color:#94a3b8; line-height:1.5;">پس‌زمینه تیره، کنتراست بالا، مناسب گجت و برندهای تکنولوژی.</span>
								</label>

								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'minimal' ? '#0f172a' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#ffffff; cursor:pointer; display:flex; flex-direction:column; gap:8px;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="minimal" <?php checked( ( $opts['store_template'] ?? '' ), 'minimal' ); ?>>
											<strong style="color:#0f172a; font-size:0.95rem;">مینیمال سفید</strong>
										</div>
										<span style="background:#f1f5f9; color:#334155; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">ساده و لوکس</span>
									</div>
									<span style="font-size:0.78rem; color:#64748b; line-height:1.5;">فضای زیاد سفید، تایپوگرافی قوی، کارت‌های تخت بدون سایه سنگین.</span>
								</label>

								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'bazaar' ? '#ea580c' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#fff7ed; cursor:pointer; display:flex; flex-direction:column; gap:8px;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="bazaar" <?php checked( ( $opts['store_template'] ?? '' ), 'bazaar' ); ?>>
											<strong style="color:#c2410c; font-size:0.95rem;">بازار سنتی</strong>
										</div>
										<span style="background:#ffedd5; color:#9a3412; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">گرم و شلوغ</span>
									</div>
									<span style="font-size:0.78rem; color:#9a3412; line-height:1.5;">الهام از بازار ایرانی: رنگ‌های گرم، بج‌های تخفیف درشت، کارت‌های شلوغ.</span>
								</label>

								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'boutique' ? '#be185d' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#fdf2f8; cursor:pointer; display:flex; flex-direction:column; gap:8px;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<input type="radio" name="store_template" value="boutique" <?php checked( ( $opts['store_template'] ?? '' ), 'boutique' ); ?>>
											<strong style="color:#be185d; font-size:0.95rem;">بوتیک مد</strong>
										</div>
										<span style="background:#fce7f3; color:#9d174d; padding:2px 8px; border-radius:6px; font-size:0.72rem; font-weight:800;">فشن</span>
									</div>
									<span style="font-size:0.78rem; color:#9d174d; line-height:1.5;">ظاهر فشن‌مگزین: تصاویر بزرگ، فونت ظریف، هدر باریک و شیک.</span>
								</label>
								<!-- فروشگاه بومی وردپرس -->
								<label style="border:2px solid <?php echo ( $opts['store_template'] ?? '' ) === 'native-wp' ? '#2563eb' : '#e2e8f0'; ?>; border-radius:12px; padding:14px; background:#eff6ff; cursor:pointer; display:flex; flex-direction:column; gap:8px;">
									<div style="display:flex; justify-content:space-between; align-items:center;">
										<span style="font-weight:900; color:#1e3a8a;">🧱 فروشگاه بومی وردپرس</span>
										<input type="radio" name="store_template" value="native-wp" <?php checked( ( $opts['store_template'] ?? '' ), 'native-wp' ); ?>>
									</div>
									<div style="font-size:0.8rem; color:#334155; line-height:1.7;">
										ویترین داخل <strong>قالب فعال وردپرس</strong> (سربرگ و پاصفحهٔ پوسته). مناسب برگهٔ پشتیبان و سازگاری با ووکامرس کلاسیک — تمام متن‌ها فارسی.
									</div>
								</label>
							</div>
						</div>

						<!-- Color Presets Visual Swatches -->
						<div style="margin-bottom:24px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:14px; padding:20px;">
							<h4 style="margin:0 0 6px; font-size:1.05rem; color:#0f172a; font-weight:800;">🎨 پلت‌های رنگی جذاب (Color Palettes)</h4>
							<p style="margin:0 0 14px; font-size:0.85rem; color:#64748b;">
								روی هر پالت کلیک کنید تا رنگ تاکید فروشگاه فوراً به‌روز شود:
							</p>

							<div style="display:flex; gap:10px; flex-wrap:wrap;">
								<?php 
								$palettes = array(
									'digikala-red'       => array( 'title' => 'قرمز دیجی‌کالا', 'hex' => '#ef394e' ),
									'snapp-green'        => array( 'title' => 'سبز اسنپ', 'hex' => '#00c073' ),
									'basalam-coral'      => array( 'title' => 'مرجانی باسلام', 'hex' => '#df3826' ),
									'torob-red'          => array( 'title' => 'قرمز ترب', 'hex' => '#d32f2f' ),
									'digistyle-rose'     => array( 'title' => 'رز دیجی‌استایل', 'hex' => '#e11d48' ),
									'technolife-blue'    => array( 'title' => 'آبی تکنولایف', 'hex' => '#0284c7' ),
									'royal-blue'         => array( 'title' => 'آبی سلطنتی', 'hex' => '#2563eb' ),
									'luxury-purple'      => array( 'title' => 'بنفش لوکس', 'hex' => '#7c3aed' ),
									'amber-gold'         => array( 'title' => 'طلایی کهربایی', 'hex' => '#d97706' ),
									'persian-turquoise'  => array( 'title' => 'فیروزه‌ای ایرانی', 'hex' => '#0d9488' ),
									'midnight-ink'       => array( 'title' => 'مرکب نیمه‌شب', 'hex' => '#6366f1' ),
									'forest'             => array( 'title' => 'جنگل عمیق', 'hex' => '#16a34a' ),
									'sunset'             => array( 'title' => 'غروب گرم', 'hex' => '#f97316' ),
								);
								$active_pal = $opts['store_palette'] ?? 'digikala-red';
								foreach ( $palettes as $p_key => $p_data ) :
									$is_sel = ( $active_pal === $p_key );
								?>
									<label style="display:inline-flex; align-items:center; gap:6px; background:#fff; border:2px solid <?php echo $is_sel ? $p_data['hex'] : '#cbd5e1'; ?>; padding:6px 12px; border-radius:20px; cursor:pointer; transition:all 0.2s;">
										<input type="radio" name="store_palette" value="<?php echo esc_attr( $p_key ); ?>" <?php checked( $active_pal, $p_key ); ?> onchange="document.querySelector('input[name=accent_color]').value='<?php echo esc_js( $p_data['hex'] ); ?>';">
										<span style="width:14px; height:14px; border-radius:50%; background:<?php echo esc_attr( $p_data['hex'] ); ?>; display:inline-block;"></span>
										<span style="font-size:0.8rem; font-weight:700; color:#334155;"><?php echo esc_html( $p_data['title'] ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</div>

						<table class="form-table">
							<tr>
								<th scope="row">جایگزینی سربرگ قالب با سربرگ لوکس:</th>
								<td>
									<label>
										<input type="checkbox" name="replace_site_header" value="1" <?php checked( $opts['replace_site_header'] ); ?>>
										سربرگ قالب فعلی در صفحه فروشگاه با سربرگ فروشگاهی مدرن، مگامنو دسته‌بندی و ناوبری ویژه جایگزین شود.
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">نوار اعلان بالایی (Top Bar):</th>
								<td>
									<label>
										<input type="checkbox" name="show_top_bar" value="1" <?php checked( $opts['show_top_bar'] ); ?>>
										نمایش نوار باریک اطلاع‌رسانی، تماس و ارسال رایگان در بالاترین نقطه سربرگ
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">متن نوار اعلان بالایی:</th>
								<td>
									<input type="text" name="top_bar_notice" value="<?php echo esc_attr( $opts['top_bar_notice'] ); ?>" class="large-text">
								</td>
							</tr>
							<tr>
								<th scope="row">عنوان فروشگاه (Brand Title):</th>
								<td>
									<input type="text" name="shop_title" id="shopTitleInput" value="<?php echo esc_attr( $opts['shop_title'] ); ?>" class="large-text" placeholder="نام فروشگاه">
								</td>
							</tr>
							<tr>
								<th scope="row">فونت عنوان فروشگاه (Title Font):</th>
								<td>
									<select name="shop_title_font" id="shopTitleFontSelect" class="regular-text" style="font-weight:700;">
										<option value="vazirmatn" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'vazirmatn' ); ?>>وزیرمتن (Vazirmatn - وب‌فونت رسمی و استاندارد گوگل)</option>
										<option value="iranyekan" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'iranyekan' ); ?>>ایران‌یکان (IRANYekan - استایل دیجی‌کالا و اسنپ)</option>
										<option value="dana" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'dana' ); ?>>دانا (Dana - هندسی، مدرن و پرطرفدار)</option>
										<option value="yekanbakh" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'yekanbakh' ); ?>>یکان‌بخ (Yekan Bakh - لوکس و شکیل مخصوص تیترها)</option>
										<option value="shabnam" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'shabnam' ); ?>>شبنم (Shabnam - گوشه‌های گرد، صمیمی و دلنشین)</option>
										<option value="sahel" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'sahel' ); ?>>ساحل (Sahel - آراسته، خوانا و کلاسیک)</option>
										<option value="iransans" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'iransans' ); ?>>ایران‌سنس (IRANSans - متداول و رسمی)</option>
										<option value="morabba" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'morabba' ); ?>>مربع (Morabba - پرانرژی و فانتزی برای برندینگ)</option>
										<option value="parastoo" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'parastoo' ); ?>>پرستو (Parastoo - شیک و چشم‌نواز)</option>
										<option value="system" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'system' ); ?>>سیستمی (System UI / Tahoma / Segoe UI)</option>
										<option value="custom" <?php selected( $opts['shop_title_font'] ?? 'vazirmatn', 'custom' ); ?>>فونت سفارشی دلخواه (Custom Font Family)</option>
									</select>
									<p class="description">فونت انتخابی به طور یکپارچه روی عنوان اصلی سربرگ، بنر هیرو، منوی کشویی و فوتر فروشگاه اعمال می‌شود.</p>
								</td>
							</tr>
							<tr id="customFontRow" style="<?php echo ( ( $opts['shop_title_font'] ?? '' ) === 'custom' ) ? '' : 'display:none;'; ?>">
								<th scope="row">نام فونت سفارشی:</th>
								<td>
									<input type="text" name="shop_title_custom_font" id="customFontInput" value="<?php echo esc_attr( $opts['shop_title_custom_font'] ?? '' ); ?>" class="regular-text" placeholder="مثال: Shabnam, Sahel, B Yekan">
									<p class="description">نام فونت را به انگلیسی وارد فرمایید. در صورت نصب بودن روی سیستم یا پوسته لود خواهد شد.</p>
								</td>
							</tr>
							<tr>
								<th scope="row">اندازه فونت عنوان فروشگاه:</th>
								<td>
									<select name="shop_title_font_size" id="shopTitleSizeSelect" class="regular-text">
										<option value="small" <?php selected( $opts['shop_title_font_size'] ?? 'normal', 'small' ); ?>>جمع و جور (کوچک - ۱.۱۸rem)</option>
										<option value="normal" <?php selected( $opts['shop_title_font_size'] ?? 'normal', 'normal' ); ?>>استاندارد (۱.۳۸rem - پیش‌فرض توصیه شده)</option>
										<option value="large" <?php selected( $opts['shop_title_font_size'] ?? 'normal', 'large' ); ?>>بزرگ و برجسته (۱.۶۵rem)</option>
										<option value="xlarge" <?php selected( $opts['shop_title_font_size'] ?? 'normal', 'xlarge' ); ?>>خیلی بزرگ و چشمگیر (۱.۹۵rem)</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">ضخامت و وزن فونت عنوان:</th>
								<td>
									<select name="shop_title_font_weight" id="shopTitleWeightSelect" class="regular-text">
										<option value="500" <?php selected( $opts['shop_title_font_weight'] ?? '900', '500' ); ?>>متوسط (Medium - ۵۰۰)</option>
										<option value="700" <?php selected( $opts['shop_title_font_weight'] ?? '900', '700' ); ?>>ضخیم (Bold - ۷۰۰)</option>
										<option value="800" <?php selected( $opts['shop_title_font_weight'] ?? '900', '800' ); ?>>خیلی ضخیم (Extra Bold - ۸۰۰)</option>
										<option value="900" <?php selected( $opts['shop_title_font_weight'] ?? '900', '900' ); ?>>فوق‌العاده توپر / بلک (Black - ۹۰۰ پیش‌فرض)</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">پیش‌نمایش زنده عنوان فروشگاه:</th>
								<td>
									<div id="shopTitleLivePreviewCard" style="background:#ffffff; border:1.5px solid #cbd5e1; border-radius:14px; padding:16px 20px; display:inline-flex; align-items:center; gap:14px; box-shadow:0 4px 15px rgba(0,0,0,0.04); min-width:320px; max-width:100%;">
										<div style="width:44px; height:44px; border-radius:12px; background:linear-gradient(135deg, #2563eb 0%, #7c3aed 100%); display:flex; align-items:center; justify-content:center; color:#fff; font-size:22px; flex-shrink:0;">
											🛍️
										</div>
										<div>
											<div id="previewTitleText" style="font-size:1.38rem; font-weight:900; color:#0f172a; line-height:1.2;">
												<?php echo esc_html( $opts['shop_title'] ); ?>
											</div>
											<div id="previewSubtitleText" style="font-size:0.8rem; color:#64748b; margin-top:2px;">
												<?php echo esc_html( $opts['shop_subtitle'] ); ?>
											</div>
										</div>
									</div>
									<p class="description">با تغییر فونت، اندازه یا ویرایش متن، پیش‌نمایش به صورت لحظه‌ای و خودکار به‌روزرسانی می‌شود.</p>
								</td>
							</tr>
							<tr>
								<th scope="row">هدر و منوی چسبان (Sticky Header):</th>
								<td>
									<label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
										<input type="checkbox" name="sticky_header" value="1" <?php checked( ! empty( $opts['sticky_header'] ) ); ?> style="width:18px; height:18px; accent-color:#2563eb;">
										<strong style="color:#0f172a;">📌 ثابت و چسبان ماندن هدر و منو در بالای صفحه هنگام اسکرول (Sticky Header & Menu)</strong>
									</label>
									<p class="description">هنگام اسکرول صفحه به سمت پایین، هدر اصلی شامل جستجوی زنده، دکمه‌های ورود، سبد خرید، منوی دسترسی و نوار دسته‌بندی‌ها به بالای صفحه می‌چسبند تا دسترسی همیشگی برای مشتری فراهم باشد.</p>
								</td>
							</tr>
							<tr>
								<th scope="row">زیرعنوان و شعار فروشگاه:</th>
								<td>
									<input type="text" name="shop_subtitle" id="shopSubtitleInput" value="<?php echo esc_attr( $opts['shop_subtitle'] ); ?>" class="large-text">
								</td>
							</tr>
							<tr>
								<th scope="row">شماره تماس پشتیبانی و ساعات کاری:</th>
								<td>
									<input type="text" name="contact_phone" value="<?php echo esc_attr( $opts['contact_phone'] ); ?>" class="regular-text" placeholder="۰۲۱-۱۲۳۴۵۶۷۸" dir="ltr" style="margin-left:10px;">
									<input type="text" name="support_hours" value="<?php echo esc_attr( $opts['support_hours'] ); ?>" class="regular-text" placeholder="پاسخگویی ۹ الی ۲۲">
								</td>
							</tr>
							<tr>
								<th scope="row">رنگ اصلی تم (Accent Color):</th>
								<td>
									<input type="color" name="accent_color" value="<?php echo esc_attr( $opts['accent_color'] ); ?>" style="width:60px; height:36px; border-radius:8px; cursor:pointer;">
								</td>
							</tr>
							<tr>
								<th scope="row">چیدمان پیش‌فرض ستون‌ها:</th>
								<td>
									<select name="default_column_layout" class="regular-text">
										<option value="1" <?php selected( $opts['default_column_layout'] ?? '1', '1' ); ?>>تک ستونه (پیش‌فرض سفارش شده)</option>
										<option value="2" <?php selected( $opts['default_column_layout'] ?? '1', '2' ); ?>>دو ستونه</option>
									</select>
									<p class="description">مشتری در ویترین می‌تواند از طریق دکمه‌های چیدمان، تعداد ستون‌ها را به دلخواه تغییر دهد.</p>
								</td>
							</tr>
							<tr>
								<th scope="row">تعداد محصولات در هر صفحه (صفحه‌بندی):</th>
								<td>
									<input type="number" name="products_per_page" value="<?php echo esc_attr( $opts['products_per_page'] ?? 20 ); ?>" class="small-text"> کالا
									<p class="description">پیش‌فرض: ۲۰ کالا در هر صفحه همراه با دکمه‌های شماره صفحه.</p>
								</td>
							</tr>
							<tr>
								<th scope="row">سقف ارسال رایگان (تومان):</th>
								<td>
									<input type="number" name="free_shipping_threshold" value="<?php echo esc_attr( $opts['free_shipping_threshold'] ); ?>" class="regular-text"> تومان
									<p class="description">نوار پیشرفت در سبد خرید کشویی انگیزه خرید تا این سقف را به مشتری نشان می‌دهد.</p>
								</td>
							</tr>
							<tr>
								<th scope="row">نشان‌های اعتماد و پیشنهاد ویژه:</th>
								<td>
									<label style="display:block; margin-bottom:8px;">
										<input type="checkbox" name="show_special_badge" value="1" <?php checked( ! empty( $opts['show_special_badge'] ) ); ?>>
										نمایش نشان «پیشنهاد ویژه» روی کارت کالا
									</label>
									<label style="display:block; margin-bottom:8px;">
										<input type="checkbox" name="show_features_banner" value="1" <?php checked( $opts['show_features_banner'] ); ?>>
										نمایش بنر ویژگی‌های فروشگاه (ارسال سریع، تضمین اصالت و ضمانت بازگشت)
									</label>
									<label style="display:block; margin-top:8px;">
										<input type="checkbox" name="show_animated_stats" value="1" <?php checked( ! empty( $opts['show_animated_stats'] ) ); ?> style="accent-color:#2563eb;">
										<strong style="color:#0f172a;">✨ نمایش شماره‌های جذاب انیمیشن‌دار در صفحه فروشگاه (اعتماد خریداران و رضایت مشتریان)</strong>
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">جایگزینی صفحه نخست وب‌سایت (Home Page):</th>
								<td>
									<label>
										<input type="checkbox" name="takeover_front_page" value="1" <?php checked( ! empty( $opts['takeover_front_page'] ) ); ?>>
										علاوه بر برگه فروشگاه، صفحه اصلی سایت هم با این ویترین مدرن و اختصاصی نمایش داده شود.
									</label>
								</td>
							</tr>
						</table>
					</div>

						
						<!-- v13.3.19 قالب بومی وردپرس + برگه پشتیبان -->
						<div style="margin:20px 0 24px; background:linear-gradient(135deg,#f8fafc,#eff6ff); border:1px solid #93c5fd; border-radius:14px; padding:20px;">
							<h4 style="margin:0 0 8px; font-size:1.08rem; color:#1e3a8a;">🧱 قالب بومی وردپرس + برگهٔ پشتیبان فروشگاه</h4>
							<p style="margin:0 0 14px; color:#1e40af; font-size:0.85rem; line-height:1.85;">
								یک <strong>برگهٔ استاندارد وردپرس</strong> با قالب «فروشگاه بومی» (سربرگ و پاصفحهٔ قالب فعال سایت) ساخته می‌شود تا
								اگر ویترین اصلی خراب، حذف یا با خطای چهارصدوچهار شود، یا افزونه خاموش باشد، بازدیدکننده به فروشگاه پایدار هدایت شود.
								می‌توانید همین برگه را به‌عنوان صفحهٔ فروشگاه ووکامرس هم تنظیم کنید.
							</p>
							<label style="display:flex; align-items:center; gap:10px; margin-bottom:10px; font-weight:800; color:#0f172a;">
								<input type="checkbox" name="enable_native_wp_template" value="1" <?php checked( ! isset( $opts['enable_native_wp_template'] ) || ! empty( $opts['enable_native_wp_template'] ) ); ?> style="width:18px;height:18px;accent-color:#2563eb;">
								فعال‌سازی قالب بومی وردپرس (سربرگ و پاصفحهٔ پوسته)
							</label>
							<label style="display:flex; align-items:center; gap:10px; margin-bottom:10px; font-weight:700; color:#334155;">
								<input type="checkbox" name="auto_create_fallback_page" value="1" <?php checked( ! isset( $opts['auto_create_fallback_page'] ) || ! empty( $opts['auto_create_fallback_page'] ) ); ?>>
								ساخت خودکار برگهٔ پشتیبان فروشگاه
							</label>
							<label style="display:flex; align-items:center; gap:10px; margin-bottom:10px; font-weight:700; color:#334155;">
								<input type="checkbox" name="enable_404_shop_redirect" value="1" <?php checked( ! isset( $opts['enable_404_shop_redirect'] ) || ! empty( $opts['enable_404_shop_redirect'] ) ); ?>>
								هدایت خطای چهارصدوچهار به برگهٔ پشتیبان فروشگاه
							</label>
							<label style="display:flex; align-items:center; gap:10px; margin-bottom:12px; font-weight:700; color:#334155;">
								<input type="checkbox" name="set_wc_shop_to_fallback" value="1" <?php checked( ! empty( $opts['set_wc_shop_to_fallback'] ) ); ?>>
								اگر صفحهٔ فروشگاه ووکامرس خالی یا حذف شده، همین برگه را به‌عنوان فروشگاه ووکامرس تنظیم کن
							</label>
							<?php
							$fb_id  = intval( $opts['native_fallback_page_id'] ?? get_option( 'scraper_native_fallback_page_id', 0 ) );
							$fb_url = $fb_id ? get_permalink( $fb_id ) : '';
							$pages  = get_pages( array( 'sort_column' => 'post_title', 'post_status' => 'publish' ) );
							?>
							<label style="font-weight:800; font-size:0.85rem; display:block; margin-bottom:4px;">برگهٔ پشتیبان فروشگاه</label>
							<select name="native_fallback_page_id" style="width:100%; max-width:480px; margin-bottom:10px;">
								<option value="0">— ساخت یا انتخاب خودکار —</option>
								<?php foreach ( (array) $pages as $pg ) : ?>
									<option value="<?php echo esc_attr( $pg->ID ); ?>" <?php selected( $fb_id, (int) $pg->ID ); ?>>
										<?php echo esc_html( $pg->post_title . ' (#' . $pg->ID . ')' ); ?>
									</option>
								<?php endforeach; ?>
							</select>
							<?php if ( $fb_url ) : ?>
								<p style="margin:0 0 10px; font-size:0.84rem; color:#0f172a;">
									🔗 آدرس فعلی:
									<a href="<?php echo esc_url( $fb_url ); ?>" target="_blank" rel="noopener" style="font-weight:800;"><?php echo esc_html( $fb_url ); ?></a>
									· قالب: <code>فروشگاه بومی وردپرس</code>
								</p>
							<?php else : ?>
								<p style="margin:0 0 10px; font-size:0.84rem; color:#b45309; font-weight:700;">هنوز برگهٔ پشتیبان تنظیم نشده — با دکمهٔ زیر بسازید یا تنظیمات را ذخیره کنید.</p>
							<?php endif; ?>
							<button type="button" class="button button-primary" id="amphpEnsureFallbackBtn">🛠 ساخت یا تعمیر برگهٔ پشتیبان الان</button>
							<span id="amphpEnsureFallbackStatus" style="margin-right:10px;font-weight:700;color:#1e40af;"></span>
							<script>
							(function(){
							  var btn=document.getElementById('amphpEnsureFallbackBtn');
							  var st=document.getElementById('amphpEnsureFallbackStatus');
							  if(!btn) return;
							  btn.addEventListener('click', function(){
							    btn.disabled=true; st.textContent='در حال ایجاد…';
							    var fd=new FormData();
							    fd.append('action','scraper_ensure_fallback_shop');
							    fd.append('nonce','<?php echo esc_js( wp_create_nonce( "scraper_shop_admin_nonce" ) ); ?>');
							    fd.append('assign_wc', document.querySelector('[name="set_wc_shop_to_fallback"]')?.checked ? '1' : '');
							    fetch(ajaxurl,{method:'POST',body:fd,credentials:'same-origin'}).then(function(r){return r.json()}).then(function(d){
							      btn.disabled=false;
							      if(d && d.success){
							        st.textContent = (d.data && d.data.message ? d.data.message : 'انجام شد') + (d.data && d.data.url ? ' — ' + d.data.url : '');
							        if(d.data && d.data.id){
							          var sel=document.querySelector('[name="native_fallback_page_id"]');
							          if(sel){ sel.value=String(d.data.id); }
							        }
							      } else {
							        st.textContent = (d && d.data) ? d.data : 'خطا';
							      }
							    }).catch(function(){ btn.disabled=false; st.textContent='خطا در ارتباط با سرور'; });
							  });
							})();
							</script>
						</div>

<!-- Custom Checkout Settings -->
						<div style="margin:20px 0 24px; background:linear-gradient(135deg,#f0fdf4,#ecfeff); border:1px solid #86efac; border-radius:14px; padding:20px;">
							<h4 style="margin:0 0 8px; font-size:1.08rem; color:#14532d;">🛒 صفحه تسویه حساب اختصاصی (React)</h4>
							<p style="margin:0 0 14px; color:#166534; font-size:0.88rem; line-height:1.7;">
								با فعال‌سازی، به‌جای هدایت مستقیم به چک‌اوت ووکامرس، صفحه تسویه زیبا داخل ویترین باز می‌شود و <strong>تمام درگاه‌های فعال ووکامرس/وردپرس</strong> لیست می‌شوند.
							</p>
							<label style="display:flex; align-items:center; gap:10px; margin-bottom:14px; font-weight:800; color:#0f172a;">
								<input type="checkbox" name="enable_custom_checkout" value="1" <?php checked( ! empty( $opts['enable_custom_checkout'] ) ); ?> style="width:18px;height:18px;accent-color:#16a34a;">
								فعال‌سازی صفحه چک‌اوت اختصاصی
							</label>
							<label style="display:flex; align-items:center; gap:10px; margin-bottom:14px; font-weight:700; color:#334155;">
								<input type="checkbox" name="checkout_require_login" value="1" <?php checked( ! empty( $opts['checkout_require_login'] ) ); ?>>
								فقط کاربران واردشده بتوانند سفارش ثبت کنند
							</label>
							<label style="display:flex; align-items:center; gap:10px; margin-bottom:14px; font-weight:700; color:#334155;">
								<input type="checkbox" name="checkout_show_gateways" value="1" <?php checked( ! isset( $opts['checkout_show_gateways'] ) || ! empty( $opts['checkout_show_gateways'] ) ); ?>>
								نمایش همه درگاه‌های پرداخت فعال ووکامرس
							</label>
							<div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
								<div>
									<label style="font-weight:800; font-size:0.85rem; display:block; margin-bottom:4px;">عنوان صفحه تسویه</label>
									<input type="text" name="checkout_title" class="regular-text" style="width:100%;" value="<?php echo esc_attr( $opts['checkout_title'] ?? 'تسویه حساب امن' ); ?>">
								</div>
								<div>
									<label style="font-weight:800; font-size:0.85rem; display:block; margin-bottom:4px;">برچسب COD (در صورت نبود درگاه)</label>
									<input type="text" name="checkout_cod_label" class="regular-text" style="width:100%;" value="<?php echo esc_attr( $opts['checkout_cod_label'] ?? 'پرداخت در محل (COD)' ); ?>">
								</div>
							</div>
							<label style="font-weight:800; font-size:0.85rem; display:block; margin-bottom:4px;">توضیح زیر عنوان</label>
							<textarea name="checkout_note" rows="2" style="width:100%; border-radius:8px; padding:8px;"><?php echo esc_textarea( $opts['checkout_note'] ?? '' ); ?></textarea>
							<label style="font-weight:800; font-size:0.85rem; display:block; margin:12px 0 4px;">پیام موفقیت پس از ثبت سفارش</label>
							<textarea name="checkout_success_msg" rows="2" style="width:100%; border-radius:8px; padding:8px;"><?php echo esc_textarea( $opts['checkout_success_msg'] ?? 'سفارش شما با موفقیت ثبت شد. از خریدتان سپاسگزاریم!' ); ?></textarea>

							<h5 style="margin:16px 0 8px; color:#14532d;">فیلدهای فرم تسویه (نمایش / اجباری)</h5>
							<div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:8px;">
								<?php
								$co_fields = array(
									'name'     => 'نام و نام خانوادگی',
									'phone'    => 'موبایل',
									'email'    => 'ایمیل',
									'province' => 'استان',
									'city'     => 'شهر',
									'address'  => 'آدرس کامل',
									'postal'   => 'کد پستی',
									'notes'    => 'توضیحات سفارش',
								);
								foreach ( $co_fields as $fk => $flabel ) :
									$en_key  = 'checkout_field_' . $fk;
									$req_key = 'checkout_field_' . $fk . '_req';
									$en_val  = ! isset( $opts[ $en_key ] ) ? in_array( $fk, array( 'name', 'phone', 'province', 'city', 'address' ), true ) : ! empty( $opts[ $en_key ] );
									$req_val = ! empty( $opts[ $req_key ] );
									?>
									<div style="background:#fff; border:1px solid #bbf7d0; border-radius:10px; padding:10px 12px;">
										<div style="font-weight:800; margin-bottom:6px; color:#0f172a;"><?php echo esc_html( $flabel ); ?></div>
										<label style="display:flex; gap:6px; align-items:center; font-size:0.82rem; margin-bottom:4px;">
											<input type="checkbox" name="<?php echo esc_attr( $en_key ); ?>" value="1" <?php checked( $en_val ); ?>> نمایش
										</label>
										<label style="display:flex; gap:6px; align-items:center; font-size:0.82rem;">
											<input type="checkbox" name="<?php echo esc_attr( $req_key ); ?>" value="1" <?php checked( $req_val ); ?>> اجباری
										</label>
									</div>
								<?php endforeach; ?>
							</div>

							<h5 style="margin:18px 0 8px; color:#14532d;">🚚 ارسال، نقشه نشان و هزینه پستی</h5>
							<p style="margin:0 0 12px; color:#166534; font-size:0.85rem; line-height:1.7;">
								شهرها بعد از انتخاب استان پر می‌شوند. روش‌های ارسال ووکامرس + پست/چاپار/تیپاکس در چک‌اوت نمایش داده می‌شوند.
								با کلید <strong>نشان (Neshan)</strong> از سرویس رسمی <strong>تبدیل مختصات به آدرس (Reverse Geocoding v5)</strong> استفاده می‌شود: با کلیک/درگ روی نقشه، آدرس کامل، استان و شهر از نشان پر می‌شود.
							</p>
							<label style="display:flex; align-items:center; gap:10px; margin-bottom:10px; font-weight:700; color:#334155;">
								<input type="checkbox" name="checkout_show_shipping" value="1" <?php checked( ! isset( $opts['checkout_show_shipping'] ) || ! empty( $opts['checkout_show_shipping'] ) ); ?>>
								نمایش روش‌های ارسال در تسویه حساب
							</label>
							<label style="display:flex; align-items:center; gap:10px; margin-bottom:10px; font-weight:700; color:#334155;">
								<input type="checkbox" name="checkout_show_map" value="1" <?php checked( ! isset( $opts['checkout_show_map'] ) || ! empty( $opts['checkout_show_map'] ) ); ?>>
								نمایش نقشه انتخاب مقصد (نشان)
							</label>
							<div style="display:grid; grid-template-columns:1fr 1fr; gap:12px; margin-bottom:12px;">
								<div>
									<label style="font-weight:800; font-size:0.85rem; display:block; margin-bottom:4px;">کلید API نشان (Neshan)</label>
									<input type="text" name="neshan_api_key" class="regular-text" style="width:100%;" dir="ltr" placeholder="service.xxxxx" value="<?php echo esc_attr( $opts['neshan_api_key'] ?? '' ); ?>">
									<small style="color:#64748b;">از platform.neshan.org/panel — کلید سرویس Reverse Geocoding. روی سرور می‌ماند (هدر Api-Key) و به فرانت نمی‌رود.</small>
								</div>
								<div>
									<label style="font-weight:800; font-size:0.85rem; display:block; margin-bottom:4px;">شهر مبدأ ارسال</label>
									<input type="text" name="shipping_origin_city" class="regular-text" style="width:100%;" value="<?php echo esc_attr( $opts['shipping_origin_city'] ?? 'تهران' ); ?>">
								</div>
							</div>
							<div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; margin-bottom:12px;">
								<label style="font-weight:700; font-size:0.82rem;">مبدأ lat
									<input type="text" name="shipping_origin_lat" dir="ltr" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['shipping_origin_lat'] ?? '35.6892' ); ?>">
								</label>
								<label style="font-weight:700; font-size:0.82rem;">مبدأ lng
									<input type="text" name="shipping_origin_lng" dir="ltr" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['shipping_origin_lng'] ?? '51.3890' ); ?>">
								</label>
								<label style="font-weight:700; font-size:0.82rem;">هزینه هر کیلو (تخمین)
									<input type="number" name="shipping_per_kg" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['shipping_per_kg'] ?? 12000 ); ?>">
								</label>
							</div>
							<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:8px;">
								<div style="background:#fff;border:1px solid #bbf7d0;border-radius:10px;padding:10px;">
									<label style="display:flex;gap:6px;align-items:center;font-weight:800;margin-bottom:8px;">
										<input type="checkbox" name="post_shipping_enabled" value="1" <?php checked( ! isset( $opts['post_shipping_enabled'] ) || ! empty( $opts['post_shipping_enabled'] ) ); ?>> پست پیشتاز
									</label>
									<label style="font-size:0.78rem;font-weight:700;">پایه (تومان)
										<input type="number" name="shipping_base_post" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['shipping_base_post'] ?? 45000 ); ?>">
									</label>
									<label style="font-size:0.78rem;font-weight:700;margin-top:6px;display:block;">توکن API (اختیاری)
										<input type="text" name="post_api_token" dir="ltr" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['post_api_token'] ?? '' ); ?>">
									</label>
								</div>
								<div style="background:#fff;border:1px solid #bbf7d0;border-radius:10px;padding:10px;">
									<label style="display:flex;gap:6px;align-items:center;font-weight:800;margin-bottom:8px;">
										<input type="checkbox" name="chapar_shipping_enabled" value="1" <?php checked( ! isset( $opts['chapar_shipping_enabled'] ) || ! empty( $opts['chapar_shipping_enabled'] ) ); ?>> چاپار
									</label>
									<label style="font-size:0.78rem;font-weight:700;">پایه (تومان)
										<input type="number" name="shipping_base_chapar" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['shipping_base_chapar'] ?? 65000 ); ?>">
									</label>
									<label style="font-size:0.78rem;font-weight:700;margin-top:6px;display:block;">توکن API (اختیاری)
										<input type="text" name="chapar_api_token" dir="ltr" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['chapar_api_token'] ?? '' ); ?>">
									</label>
								</div>
								<div style="background:#fff;border:1px solid #bbf7d0;border-radius:10px;padding:10px;">
									<label style="display:flex;gap:6px;align-items:center;font-weight:800;margin-bottom:8px;">
										<input type="checkbox" name="tipax_shipping_enabled" value="1" <?php checked( ! isset( $opts['tipax_shipping_enabled'] ) || ! empty( $opts['tipax_shipping_enabled'] ) ); ?>> تیپاکس
									</label>
									<label style="font-size:0.78rem;font-weight:700;">پایه (تومان)
										<input type="number" name="shipping_base_tipax" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['shipping_base_tipax'] ?? 75000 ); ?>">
									</label>
									<label style="font-size:0.78rem;font-weight:700;margin-top:6px;display:block;">توکن API (اختیاری)
										<input type="text" name="tipax_api_token" dir="ltr" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['tipax_api_token'] ?? '' ); ?>">
									</label>
								</div>
							</div>
						</div>

				</div>

				<!-- ================= TAB 2: PRICING & PROFIT ================= -->
				<div id="tab-pricing" class="scraper-tab-panel">
					<div class="admin-card">
						<div class="admin-card-header">
							<h3><span>💰</span> تنظیمات قیمت‌گذاری، درصد سود و گرد کردن قیمت‌ها</h3>
							<span class="field-badge field-badge-green">محاسبه خودکار</span>
						</div>

						<table class="form-table">
							<tr>
								<th scope="row">درصد سود و افزایش قیمت (Markup %):</th>
								<td>
									<input type="number" step="0.5" name="price_markup_percent" value="<?php echo esc_attr( $opts['price_markup_percent'] ); ?>" class="small-text"> ٪
									<p class="description">این درصد به قیمت خام استخراج شده اضافه می‌شود (مثلاً ۲۰٪ سود).</p>
								</td>
							</tr>
							<tr>
								<th scope="row">مبلغ ثابت اضافه شونده:</th>
								<td>
									<input type="number" name="price_fixed_add" value="<?php echo esc_attr( $opts['price_fixed_add'] ); ?>" class="regular-text"> تومان
									<p class="description">مبلغ ثابت اضافه به ازای هر محصول (مثلاً هزینه بسته‌بندی یا کارمزد).</p>
								</td>
							</tr>
							<tr>
								<th scope="row">قیمت پایه پیش‌فرض:</th>
								<td>
									<input type="number" name="default_fallback_price" value="<?php echo esc_attr( $opts['default_fallback_price'] ); ?>" class="regular-text"> تومان
									<p class="description">اگر کالایی در منبع بدون قیمت بود، از این قیمت پایه استفاده می‌شود تا عبارت «تماس بگیرید» ظاهر نشود.</p>
								</td>
							</tr>
							<tr>
								<th scope="row">رفتار هنگام نبود قیمت:</th>
								<td>
									<select name="fallback_price_behavior" class="regular-text">
										<option value="use_fallback" <?php selected( $opts['fallback_price_behavior'], 'use_fallback' ); ?>>استفاده از قیمت پایه پیش‌فرض و نمایش قیمت (توصیه شده)</option>
										<option value="call_for_price" <?php selected( $opts['fallback_price_behavior'], 'call_for_price' ); ?>>نمایش عبارت «تماس بگیرید»</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">گرد کردن قیمت‌ها:</th>
								<td>
									<select name="price_rounding" class="regular-text">
										<option value="none" <?php selected( $opts['price_rounding'], 'none' ); ?>>بدون گرد کردن</option>
										<option value="1000" <?php selected( $opts['price_rounding'], '1000' ); ?>>گرد کردن به نزدیک‌ترین ۱,۰۰۰ تومان (توصیه شده)</option>
										<option value="10000" <?php selected( $opts['price_rounding'], '10000' ); ?>>گرد کردن به نزدیک‌ترین ۱۰,۰۰۰ تومان</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">واحد پول فروشگاه:</th>
								<td>
									<input type="text" name="currency_symbol" value="<?php echo esc_attr( $opts['currency_symbol'] ); ?>" class="regular-text">
								</td>
							</tr>
						</table>
					</div>
				</div>

				<!-- ================= TAB 3: CHAT & ADMIN LIVE SUPPORT DESK ================= -->
				<div id="tab-chat" class="scraper-tab-panel">
					
					<!-- 1. ADMIN LIVE CHAT SUPPORT DESK -->
					<div class="admin-card" style="border-top: 4px solid #2563eb; padding: 20px;">
						<div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:14px; padding-bottom:12px;">
							<div>
								<h3 style="margin:0; display:flex; align-items:center; gap:8px; font-size:1.15rem; font-weight:800; color:#0f172a;">
									<span>📬</span> میز کار و کنسول پاسخگویی زنده به مشتریان
								</h3>
								<p style="margin:4px 0 0; color:#64748b; font-size:0.85rem;">
									پاسخ شما به صورت زنده در پنجره چت مشتری نمایش داده می‌شود و رشته گفتگو دوطرفه ادامه می‌یابد.
								</p>
							</div>
							<div style="display:flex; align-items:center; gap:10px;">
								<span class="field-badge field-badge-blue" id="deskThreadsCountBadge"><?php echo count( $chat_threads ); ?> گفتگو</span>
								<button type="button" class="button button-secondary button-small" id="btnRefreshAdminDesk" style="font-weight:700;">🔄 به‌روزرسانی زنده</button>
							</div>
						</div>

						<!-- Two-Column Desk Console (Organized & Responsive) -->
						<div class="scraper-support-desk" style="display:flex; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; background:#ffffff; height:620px; box-shadow:0 8px 24px rgba(15,23,42,0.06);">
							
							<!-- Column 1: Conversations List (Right Column in RTL) -->
							<div class="desk-threads-col" style="width:310px; border-left:1px solid #e2e8f0; display:flex; flex-direction:column; background:#f8fafc; height:100%; flex-shrink:0;">
								<!-- Search & Filter Bar -->
								<div style="padding:10px 12px; border-bottom:1px solid #e2e8f0; background:#ffffff;">
									<input type="text" id="deskSearchInput" placeholder="🔍 جستجو در نام، شماره یا پیام..." style="width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:6px 10px; font-size:0.82rem; box-sizing:border-box;">
									<div style="display:flex; gap:4px; margin-top:8px;">
										<button type="button" class="desk-filter-btn active" data-filter="all" style="flex:1; border:1px solid #cbd5e1; background:#2563eb; color:#fff; border-radius:6px; padding:4px 2px; font-size:0.72rem; font-weight:700; cursor:pointer;">همه</button>
										<button type="button" class="desk-filter-btn" data-filter="pending" style="flex:1; border:1px solid #cbd5e1; background:#ffffff; border-radius:6px; padding:4px 2px; font-size:0.72rem; font-weight:700; cursor:pointer; color:#d97706;">در انتظار</button>
										<button type="button" class="desk-filter-btn" data-filter="replied" style="flex:1; border:1px solid #cbd5e1; background:#ffffff; border-radius:6px; padding:4px 2px; font-size:0.72rem; font-weight:700; cursor:pointer; color:#059669;">پاسخ‌داده</button>
									</div>
								</div>

								<!-- Threads Scroll List -->
								<div class="desk-threads-scroll" id="deskThreadsList" style="flex:1; overflow-y:auto; padding:0;">
									<?php if ( empty( $chat_threads ) ) : ?>
										<div style="text-align:center; padding:35px 15px; color:#94a3b8; font-size:0.85rem;">
											💬 هنوز پیامی از سمت مشتریان ارسال نشده است.
										</div>
									<?php else : ?>
										<?php foreach ( $chat_threads as $t_id => $t ) : 
											$msgs = $t['messages'] ?? array();
											$last_msg = ! empty( $msgs ) ? end( $msgs ) : null;
											$is_unread = ! empty( $t['unread_admin'] ) || ( ( $t['status'] ?? '' ) === 'pending' );
										?>
											<div class="desk-thread-item <?php echo $is_unread ? 'unread' : ''; ?>" 
												data-id="<?php echo esc_attr( $t['id'] ?? $t_id ); ?>" 
												data-session="<?php echo esc_attr( $t['session_id'] ?? '' ); ?>"
												data-name="<?php echo esc_attr( $t['name'] ?? 'مشتری' ); ?>"
												data-phone="<?php echo esc_attr( $t['phone'] ?? '' ); ?>"
												data-email="<?php echo esc_attr( $t['email'] ?? '' ); ?>"
												data-subject="<?php echo esc_attr( $t['subject'] ?? '' ); ?>"
												data-status="<?php echo esc_attr( $t['status'] ?? 'pending' ); ?>"
												style="padding:10px 12px; border-bottom:1px solid #f1f5f9; cursor:pointer; transition:background 0.15s; position:relative;">
												<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:3px;">
													<strong style="color:#0f172a; font-size:0.88rem;"><?php echo esc_html( $t['name'] ?? 'مشتری مهمان' ); ?></strong>
													<span style="font-size:0.7rem; color:#94a3b8;"><?php echo esc_html( $last_msg['time'] ?? '' ); ?></span>
												</div>
												<?php if ( ! empty( $t['phone'] ) ) : ?>
													<div style="font-size:0.75rem; color:#2563eb; font-weight:700; direction:ltr; text-align:right; margin-bottom:3px;"><?php echo esc_html( $t['phone'] ); ?></div>
												<?php endif; ?>
												<div style="font-size:0.78rem; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
													<?php echo esc_html( mb_substr( $last_msg['text'] ?? 'بدون متن', 0, 45 ) ); ?>
												</div>
												<div style="display:flex; justify-content:space-between; align-items:center; margin-top:5px;">
													<?php if ( $is_unread ) : ?>
														<span style="font-size:0.68rem; font-weight:800; background:#fef3c7; color:#b45309; padding:2px 6px; border-radius:6px;">⏳ نیاز به پاسخ</span>
													<?php else : ?>
														<span style="font-size:0.68rem; font-weight:800; background:#ecfdf5; color:#047857; padding:2px 6px; border-radius:6px;">✅ پاسخ داده شد</span>
													<?php endif; ?>
													<span style="font-size:0.7rem; color:#94a3b8;"><?php echo count( $msgs ); ?> پیام</span>
												</div>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							</div>

							<!-- Column 2: Conversation View & Reply (Left Column in RTL) -->
							<div class="desk-view-col" style="flex:1; display:flex; flex-direction:column; background:#ffffff; height:100%; min-width:0;">
								
								<!-- Empty State (Shown before a thread is selected) -->
								<div id="deskEmptyState" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:30px; color:#94a3b8; text-align:center;">
									<div style="font-size:2.8rem; margin-bottom:10px;">💬</div>
									<h4 style="margin:0 0 6px; font-size:1.05rem; color:#475569; font-weight:800;">گفتگویی انتخاب نشده است</h4>
									<p style="margin:0; font-size:0.85rem; max-width:320px; line-height:1.5;">جهت مشاهده مکالمه و پاسخ به مشتری، یک گفتگو را از ستون کناری انتخاب نمایید.</p>
								</div>

								<!-- Active Conversation Box -->
								<div id="deskActiveBox" style="display:none; flex-direction:column; height:100%; min-width:0;">
									
									<!-- Customer Card Header -->
									<div style="padding:10px 16px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
										<div>
											<div style="display:flex; align-items:center; gap:8px;">
												<button type="button" id="btnDeskBackToList" class="button button-small" style="display:none;">◀ لیست</button>
												<h4 style="margin:0; font-size:0.98rem; font-weight:800; color:#0f172a;" id="deskHeaderName">نام مشتری</h4>
												<span id="deskHeaderStatus" style="font-size:0.7rem; font-weight:800; padding:2px 6px; border-radius:6px; background:#fef3c7; color:#b45309;">در انتظار پاسخ</span>
											</div>
											<div style="font-size:0.78rem; color:#64748b; margin-top:3px; display:flex; gap:12px; flex-wrap:wrap;">
												<span id="deskHeaderPhone"></span>
												<span id="deskHeaderEmail"></span>
												<span id="deskHeaderSubject"></span>
											</div>
										</div>

										<!-- Action Buttons (Call / WhatsApp / Delete) -->
										<div style="display:flex; gap:6px; align-items:center;">
											<a href="#" id="deskDirectCallBtn" class="button button-secondary button-small" style="font-weight:700; font-size:0.78rem; padding:2px 8px;">📞 تماس</a>
											<a href="#" id="deskDirectWaBtn" target="_blank" class="button button-small" style="background:#25D366; color:#fff; border-color:#25D366; font-weight:700; font-size:0.78rem; padding:2px 8px;">💬 واتساپ</a>
											<button type="button" id="deskDeleteBtn" class="button button-small" style="color:#dc2626; border-color:#fca5a5; font-weight:700; font-size:0.78rem; padding:2px 8px;">🗑️ حذف</button>
										</div>
									</div>

									<!-- Messages Stream Scroll Area -->
									<div class="desk-msg-stream" id="deskMsgStream" style="flex:1; overflow-y:auto; padding:14px 16px; background:#f8fafc; display:flex; flex-direction:column; gap:10px;">
										<!-- Dynamic Bubbles Rendered Here -->
									</div>

									<!-- Quick Canned Replies Row -->
									<div style="padding:6px 12px; background:#ffffff; border-top:1px solid #f1f5f9; display:flex; gap:6px; overflow-x:auto; white-space:nowrap; align-items:center;">
										<span style="font-size:0.72rem; color:#64748b; font-weight:700; flex-shrink:0;">⚡ پاسخ آماده:</span>
										<button type="button" class="desk-canned-chip" data-text="سلام و درود، سفارش شما در حال آماده‌سازی و ارسال است.">📦 آماده‌سازی</button>
										<button type="button" class="desk-canned-chip" data-text="کد پیگیری مرسوله پستی تا ساعاتی دیگر پیامک خواهد شد.">🚚 ارسال کد پیگیری</button>
										<button type="button" class="desk-canned-chip" data-text="کالای مورد نظر شما در انبار موجود و آماده تحویل است.">🛍️ استعلام موجودی</button>
										<button type="button" class="desk-canned-chip" data-text="جهت هماهنگی سریع‌تر لطفاً با تلفن پشتیبانی تماس حاصل فرمایید.">📞 تماس تکمیلی</button>
									</div>

									<!-- Reply Composer Form -->
									<div style="padding:10px 14px; border-top:1px solid #e2e8f0; background:#ffffff;">
										<div style="display:flex; gap:8px;">
											<textarea id="deskReplyInput" rows="2" placeholder="متن پاسخ خود را به این مشتری بنویسید (فوراً در چت مشتری نمایش داده می‌شود)..." style="flex:1; border:1px solid #cbd5e1; border-radius:8px; padding:8px 10px; font-family:inherit; font-size:0.85rem; resize:none;"></textarea>
											<button type="button" id="deskSendReplyBtn" class="button button-primary" style="background:#2563eb; font-weight:800; padding:0 18px; border-radius:8px; align-self:stretch; font-size:0.88rem;">
												ارسال پاسخ 🚀
											</button>
										</div>
										<div id="deskReplyFeedback" style="font-size:0.78rem; margin-top:4px; font-weight:700;"></div>
									</div>

								</div>
							</div>
						</div>
					</div>

					<!-- 2. CARD DROPDOWN THEME SELECTOR WITH LIVE PREVIEW (NO MORE ENDLESS LIST!) -->
					<div class="admin-card" style="margin-top:20px; padding:20px;">
						<div class="admin-card-header" style="margin-bottom:16px; padding-bottom:12px;">
							<h3 style="margin:0; font-size:1.15rem; font-weight:800; color:#0f172a;">
								<span>🎨</span> انتخاب تم گرافیکی پنجره چت (۱۲ تم با دراپ‌داون کارتی و پیش‌نمایش زنده)
							</h3>
							<span class="field-badge field-badge-purple">دراپ‌داون کارتی مدرن</span>
						</div>

						<p style="color:#64748b; font-size:0.88rem; line-height:1.5; margin:0 0 16px;">
							تم مورد نظر خود را از منوی کارتی زیر انتخاب نمایید. پیش‌نمایش زنده در کنار آن بلافاصله ظاهر جدید چت را نشان می‌دهد:
						</p>

						<!-- Hidden Native Select (for form submission) -->
						<select name="chat_theme" id="chat_theme_selector" style="display:none;">
							<option value="royal-blue" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'royal-blue' ); ?>>royal-blue</option>
							<option value="cyberpunk-dark" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'cyberpunk-dark' ); ?>>cyberpunk-dark</option>
							<option value="emerald-whatsapp" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'emerald-whatsapp' ); ?>>emerald-whatsapp</option>
							<option value="magenta-rose" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'magenta-rose' ); ?>>magenta-rose</option>
							<option value="gold-vip" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'gold-vip' ); ?>>gold-vip</option>
							<option value="minimal-slate" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'minimal-slate' ); ?>>minimal-slate</option>
							<option value="aurora-gradient" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'aurora-gradient' ); ?>>aurora-gradient</option>
							<option value="sunset-coral" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'sunset-coral' ); ?>>sunset-coral</option>
							<option value="telegram-ocean" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'telegram-ocean' ); ?>>telegram-ocean</option>
							<option value="warm-caramel" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'warm-caramel' ); ?>>warm-caramel</option>
							<option value="mint-pastel" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'mint-pastel' ); ?>>mint-pastel</option>
							<option value="frosted-glass" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'frosted-glass' ); ?>>frosted-glass</option>
						</select>

						<!-- Two-Column Layout: Card Dropdown on Right, Single Live Preview on Left -->
						<div style="display:flex; gap:20px; flex-wrap:wrap; align-items:flex-start;">
							
							<!-- Right Column: Interactive Card Dropdown Component -->
							<div style="flex:1; min-width:280px; max-width:460px;">
								<label style="display:block; font-weight:800; font-size:0.92rem; color:#0f172a; margin-bottom:8px;">
									کلیک روی کارت برای انتخاب تم:
								</label>
								
								<div class="card-dropdown-wrap" style="position:relative;">
									<!-- Dropdown Trigger Card Button -->
									<div id="themeCardDropdownBtn" style="background:#ffffff; border:2px solid #2563eb; border-radius:12px; padding:12px 16px; display:flex; align-items:center; justify-content:space-between; cursor:pointer; box-shadow:0 4px 12px rgba(37,99,235,0.08); user-select:none; transition:all 0.2s;">
										<div style="display:flex; align-items:center; gap:12px;">
											<div id="themeActiveDots" style="display:flex; gap:4px;">
												<span style="width:14px; height:14px; border-radius:50%; background:#1e3a8a; display:inline-block;"></span>
												<span style="width:14px; height:14px; border-radius:50%; background:#2563eb; display:inline-block;"></span>
												<span style="width:14px; height:14px; border-radius:50%; background:#60a5fa; display:inline-block;"></span>
											</div>
											<div>
												<strong id="themeActiveTitle" style="font-size:0.95rem; color:#0f172a; display:block;">آبی رویال و کریستالی</strong>
												<span id="themeActiveBadge" style="font-size:0.75rem; color:#2563eb; font-weight:700;">پیش‌فرض رسمی</span>
											</div>
										</div>
										<div style="color:#2563eb; font-size:0.9rem; font-weight:800; display:flex; align-items:center; gap:6px;">
											<span>انتخاب تم</span>
											<span id="themeDropdownArrow" style="transition:transform 0.2s;">▾</span>
										</div>
									</div>

									<!-- Dropdown Menu List Popup (12 Clean Cards) -->
									<div id="themeCardDropdownMenu" style="display:none; position:absolute; top:calc(100% + 6px); right:0; left:0; background:#ffffff; border:1px solid #cbd5e1; border-radius:12px; box-shadow:0 12px 30px rgba(0,0,0,0.15); z-index:100; max-height:360px; overflow-y:auto; padding:6px;">
										
										<?php
										$themes_data = array(
											'royal-blue'       => array('name' => '۱. آبی رویال و کریستالی', 'badge' => 'پیش‌فرض رسمی', 'dots' => array('#1e3a8a', '#2563eb', '#60a5fa')),
											'cyberpunk-dark'   => array('name' => '۲. دارک نئونی و بنفش', 'badge' => 'OLED Dark Mode', 'dots' => array('#090514', '#7c3aed', '#a855f7')),
											'emerald-whatsapp' => array('name' => '۳. سبز زمردی و واتساپی', 'badge' => 'پیام‌رسان محبوب', 'dots' => array('#064e3b', '#059669', '#25D366')),
											'magenta-rose'     => array('name' => '۴. صورتی نئونی و سرخابی', 'badge' => 'بیوتی و فشن', 'dots' => array('#831843', '#db2777', '#fb7185')),
											'gold-vip'         => array('name' => '۵. مشکی طلایی VIP', 'badge' => 'طلا و اکسسوری VIP', 'dots' => array('#09090b', '#d97706', '#fbbf24')),
											'minimal-slate'    => array('name' => '۶. مینیمال خنثی و تمیز', 'badge' => 'طراحی اسکاندیناوی', 'dots' => array('#1e293b', '#475569', '#94a3b8')),
											'aurora-gradient'  => array('name' => '۷. گرادینت شفق قطبی', 'badge' => 'ارغوانی و فیروزه‌ای', 'dots' => array('#4338ca', '#6366f1', '#06b6d4')),
											'sunset-coral'     => array('name' => '۸. غروب آفتاب کالیفرنیا', 'badge' => 'مرجانی و صمیمی', 'dots' => array('#9a3412', '#ea580c', '#fb923c')),
											'telegram-ocean'   => array('name' => '۹. چت تلگرامی اقیانوسی', 'badge' => 'آبی تلگرامی', 'dots' => array('#0369a1', '#0284c7', '#38bdf8')),
											'warm-caramel'     => array('name' => '۱۰. شکلاتی و کاراملی', 'badge' => 'گرم و نوستالژیک', 'dots' => array('#451a03', '#92400e', '#f59e0b')),
											'mint-pastel'      => array('name' => '۱۱. نعنایی و فیروزه‌ای', 'badge' => 'آرامش‌بخش و سلامت', 'dots' => array('#115e59', '#0d9488', '#2dd4bf')),
											'frosted-glass'    => array('name' => '۱۲. شیشه‌ای گلس‌مورفیسم', 'badge' => 'کریستالی شفاف ۲۰۲۶', 'dots' => array('#1e293b', '#2563eb', '#cbd5e1')),
										);

										$cur_theme = $opts['chat_theme'] ?? 'royal-blue';

										foreach ( $themes_data as $slug => $th ) :
											$is_sel = ( $cur_theme === $slug );
										?>
											<div class="theme-menu-card-item <?php echo $is_sel ? 'active' : ''; ?>" data-theme="<?php echo esc_attr( $slug ); ?>" style="padding:10px 12px; border-radius:8px; display:flex; justify-content:space-between; align-items:center; cursor:pointer; margin-bottom:4px; transition:background 0.15s; <?php echo $is_sel ? 'background:#eff6ff;' : ''; ?>">
												<div style="display:flex; align-items:center; gap:10px;">
													<div style="display:flex; gap:3px;">
														<?php foreach ( $th['dots'] as $d ) : ?>
															<span style="width:12px; height:12px; border-radius:50%; background:<?php echo esc_attr( $d ); ?>; display:inline-block;"></span>
														<?php endforeach; ?>
													</div>
													<span style="font-weight:700; font-size:0.88rem; color:#0f172a;"><?php echo esc_html( $th['name'] ); ?></span>
												</div>
												<span style="font-size:0.72rem; color:#64748b; font-weight:700; background:#f1f5f9; padding:2px 8px; border-radius:6px;"><?php echo esc_html( $th['badge'] ); ?></span>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
								<div style="margin-top:10px; font-size:0.8rem; color:#64748b;">
									💡 با انتخاب هر تم از منوی بالا، پیش‌نمایش سمت چپ فوراً تغییر می‌کند.
								</div>
							</div>

							<!-- Left Column: Single Live Mockup Preview Card (Clean & Compact) -->
							<div style="flex:1; min-width:280px; max-width:440px;">
								<label style="display:block; font-weight:800; font-size:0.92rem; color:#0f172a; margin-bottom:8px;">
									پیش‌نمایش زنده تم انتخابی:
								</label>
								
								<div id="liveThemePreviewCard" style="border:1px solid #cbd5e1; border-radius:16px; overflow:hidden; box-shadow:0 8px 25px rgba(0,0,0,0.06); background:#ffffff;">
									<!-- Mockup Top Bar -->
									<div id="mockHdr" style="background:linear-gradient(135deg, #1e3a8a, #2563eb); color:#ffffff; padding:10px 14px; display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; align-items:center; gap:8px;">
											<span style="font-size:1.2rem;">👩‍💼</span>
											<strong style="font-size:0.88rem;">پشتیبانی آنلاین فروشگاه</strong>
										</div>
										<span style="font-size:0.7rem; opacity:0.9;">🟢 آنلاین</span>
									</div>

									<!-- Mockup Chat Stream -->
									<div id="mockBody" style="padding:14px; background:#f8fafc; display:flex; flex-direction:column; gap:8px; font-size:0.8rem;">
										<!-- Customer Bubble -->
										<div id="mockUserBubble" style="align-self:flex-end; background:linear-gradient(135deg, #2563eb, #1d4ed8); color:#ffffff; border-radius:10px; border-bottom-left-radius:2px; padding:6px 10px; max-width:80%;">
											سلام، ارسال فوری دارید؟
										</div>
										<!-- AI Bubble -->
										<div id="mockAiBubble" style="align-self:flex-start; background:#ffffff; border:1px solid #e2e8f0; color:#0f172a; border-radius:10px; border-bottom-right-radius:2px; padding:6px 10px; max-width:85%;">
											<div style="font-size:0.68rem; font-weight:800; color:#7c3aed; margin-bottom:2px;">🤖 پشتیبان هوشمند</div>
											بله، کلیه سفارشات ثبت‌شده تا ۱۲ همان روز ارسال می‌شوند.
										</div>
										<!-- Admin Bubble -->
										<div id="mockAdminBubble" style="align-self:flex-start; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; border-radius:10px; border-bottom-right-radius:2px; padding:6px 10px; max-width:85%;">
											<div style="font-size:0.68rem; font-weight:800; color:#059669; margin-bottom:2px;">👨‍💼 کارشناس پشتیبانی</div>
											همچنین بسته‌بندی ویژه اکسپرس نیز فعال است.
										</div>
									</div>

									<!-- Mockup Palette Strip -->
									<div id="mockPaletteStrip" style="padding:8px 14px; background:#ffffff; border-top:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
										<span style="font-size:0.75rem; color:#64748b; font-weight:700;">پالت رنگی تم:</span>
										<div id="mockPaletteDots" style="display:flex; gap:6px;">
											<span style="width:14px; height:14px; border-radius:50%; background:#1e3a8a; display:inline-block;"></span>
											<span style="width:14px; height:14px; border-radius:50%; background:#2563eb; display:inline-block;"></span>
											<span style="width:14px; height:14px; border-radius:50%; background:#60a5fa; display:inline-block;"></span>
										</div>
									</div>
								</div>
							</div>

						</div>
					</div>

					<!-- 3. BUTTON STYLE CARD DROPDOWN WITH LIVE PREVIEW -->
					<div class="admin-card" style="margin-top:20px; padding:20px;">
						<div class="admin-card-header" style="margin-bottom:16px; padding-bottom:12px;">
							<h3 style="margin:0; font-size:1.15rem; font-weight:800; color:#0f172a;">
								<span>🔘</span> طرح دکمه شناور چت در صفحه فروشگاه (۶ طرح با دراپ‌داون کارتی)
							</h3>
							<span class="field-badge field-badge-blue">انتخاب سریع</span>
						</div>

						<p style="color:#64748b; font-size:0.88rem; line-height:1.5; margin:0 0 16px;">
							طرح دکمه گوشه صفحه فروشگاه را انتخاب کنید:
						</p>

						<div style="display:flex; gap:20px; flex-wrap:wrap; align-items:center;">
							<div style="flex:1; min-width:280px; max-width:460px;">
								<select name="chat_button_style" id="chat_button_style_selector" style="width:100%; font-size:0.95rem; font-weight:700; padding:10px 14px; border-radius:10px; border:2px solid #2563eb; color:#0f172a; background:#ffffff;">
									<option value="pill-label" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'pill-label' ); ?>>۱. کپسولی با متن «پشتیبانی آنلاین» و آیکون (پیش‌فرض)</option>
									<option value="circle-glow" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'circle-glow' ); ?>>۲. دایره مدرن نئونی با نور رنگی (Glowing Circle)</option>
									<option value="avatar-ring" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'avatar-ring' ); ?>>۳. آواتار پشتیبان انسانی با حلقه آنلاین (Avatar Ring)</option>
									<option value="frosted-glass" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'frosted-glass' ); ?>>۴. شیشه‌ای مات فلوتینگ بلورین (Frosted Glass)</option>
									<option value="edge-tab" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'edge-tab' ); ?>>۵. زبانه چسبان لبه صفحه بدون اشغال فضا (Edge Tab)</option>
									<option value="radar-pulse" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'radar-pulse' ); ?>>۶. رادار صوتی با امواج دوگانه متحرک (Radar Wave)</option>
								</select>
							</div>
							<div style="font-size:0.85rem; color:#64748b;">
								(دکمه شناور در گوشه پایین صفحه با موقعیت و طرح انتخابی شما نمایش داده خواهد شد)
							</div>
						</div>
					</div>

					<!-- 4. CHAT FORM FIELDS & GENERAL SETTINGS (CLEAN RESPONSIVE TABLE) -->
					<div class="admin-card" style="margin-top:20px; padding:20px;">
						<div class="admin-card-header" style="margin-bottom:16px; padding-bottom:12px;">
							<h3 style="margin:0; font-size:1.15rem; font-weight:800; color:#0f172a;">
								<span>⚙️</span> تنظیمات فیلدهای ورودی فرم چت و عناوین پنجره
							</h3>
							<span class="field-badge field-badge-purple">سفارشی‌سازی فرم</span>
						</div>

						<table class="form-table" style="margin-top:0;">
							<tr>
								<th scope="row" style="width:220px; font-weight:700;">فعال‌سازی چت آنلاین:</th>
								<td>
									<label>
										<input type="checkbox" name="enable_support_chat" value="1" <?php checked( ! empty( $opts['enable_support_chat'] ) ); ?>>
										دکمه چت آنلاین و پنجره گفتگو در فروشگاه فعال باشد.
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row" style="font-weight:700;">موقعیت دکمه چت:</th>
								<td>
									<select name="chat_button_position" class="regular-text" style="border-radius:8px;">
										<option value="left" <?php selected( $opts['chat_button_position'] ?? 'left', 'left' ); ?>>پایین سمت چپ (توصیه شده)</option>
										<option value="right" <?php selected( $opts['chat_button_position'] ?? 'left', 'right' ); ?>>پایین سمت راست</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row" style="font-weight:700;">عنوان پنجره چت:</th>
								<td>
									<input type="text" name="chat_window_title" value="<?php echo esc_attr( $opts['chat_window_title'] ?? 'پشتیبانی آنلاین فروشگاه' ); ?>" class="large-text" style="border-radius:8px;">
								</td>
							</tr>
							<tr>
								<th scope="row" style="font-weight:700;">پیام خوش‌آمدگویی اولیه:</th>
								<td>
									<textarea name="chat_welcome_message" rows="2" class="large-text" style="border-radius:8px;"><?php echo esc_textarea( $opts['chat_welcome_message'] ?? 'سلام! خوش آمدید 👋 هرگونه سوالی درباره کالاها، قیمت‌ها یا ثبت سفارش دارید بنویسید تا همکاران ما سریعاً پاسخ دهند.' ); ?></textarea>
								</td>
							</tr>
						</table>

						<!-- Field Customization Table -->
						<div style="margin-top:16px; border-top:1px solid #f1f5f9; padding-top:16px;">
							<h4 style="margin:0 0 10px; font-size:0.95rem; font-weight:800; color:#1e293b;">
								📋 تنظیم فیلدهای ورودی (امکان فعال/غیرفعال‌سازی و اجباری بودن):
							</h4>

							<table class="wp-list-table widefat fixed striped" style="border-radius:8px; overflow:hidden;">
								<thead>
									<tr>
										<th style="font-weight:800; width:160px;">عنوان فیلد</th>
										<th style="font-weight:800; width:130px;">نمایش در فرم</th>
										<th style="font-weight:800; width:130px;">الزامی بودن</th>
										<th style="font-weight:800;">توضیحات کاربردی</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><strong>👤 نام و نام خانوادگی</strong></td>
										<td>
											<label>
												<input type="checkbox" name="chat_field_name_enable" value="1" <?php checked( ! empty( $opts['chat_field_name_enable'] ) ); ?>>
												فعال
											</label>
										</td>
										<td>
											<label>
												<input type="checkbox" name="chat_field_name_required" value="1" <?php checked( ! empty( $opts['chat_field_name_required'] ) ); ?>>
												الزامی باشد
											</label>
										</td>
										<td style="color:#64748b; font-size:0.82rem;">خطاب قرار دادن مشتری در پاسخ‌ها.</td>
									</tr>
									<tr>
										<td><strong>📱 شماره تماس / موبایل</strong></td>
										<td>
											<label>
												<input type="checkbox" name="chat_field_phone_enable" value="1" <?php checked( ! empty( $opts['chat_field_phone_enable'] ) ); ?>>
												فعال
											</label>
										</td>
										<td>
											<label>
												<input type="checkbox" name="chat_field_phone_required" value="1" <?php checked( ! empty( $opts['chat_field_phone_required'] ) ); ?>>
												الزامی باشد
											</label>
										</td>
										<td style="color:#64748b; font-size:0.82rem;">امکان تماس تلفنی یا واتساپ مستقیم با مشتری (توصیه: الزامی).</td>
									</tr>
									<tr>
										<td><strong>📧 آدرس ایمیل</strong></td>
										<td>
											<label>
												<input type="checkbox" name="chat_field_email_enable" value="1" <?php checked( ! empty( $opts['chat_field_email_enable'] ) ); ?>>
												فعال
											</label>
										</td>
										<td>
											<label>
												<input type="checkbox" name="chat_field_email_required" value="1" <?php checked( ! empty( $opts['chat_field_email_required'] ) ); ?>>
												الزامی باشد
											</label>
										</td>
										<td style="color:#64748b; font-size:0.82rem;">ارسال پاسخ رسمی به ایمیل مشتری.</td>
									</tr>
									<tr>
										<td><strong>📌 موضوع سوال / سفارش</strong></td>
										<td>
											<label>
												<input type="checkbox" name="chat_field_subject_enable" value="1" <?php checked( ! empty( $opts['chat_field_subject_enable'] ) ); ?>>
												فعال
											</label>
										</td>
										<td>
											<label>
												<input type="checkbox" name="chat_field_subject_required" value="1" <?php checked( ! empty( $opts['chat_field_subject_required'] ) ); ?>>
												الزامی باشد
											</label>
										</td>
										<td style="color:#64748b; font-size:0.82rem;">دسته‌بندی موضوع پیام (استعلام، پیگیری و ...).</td>
									</tr>
									<tr>
										<td><strong>📝 متن پیام یا سوال</strong></td>
										<td><span style="color:#059669; font-weight:700;">همیشه فعال</span></td>
										<td><span style="color:#dc2626; font-weight:700;">همیشه الزامی</span></td>
										<td style="color:#64748b; font-size:0.82rem;">متن پیام اصلی مشتری.</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

				</div>

				<!-- ================= TAB 4: AI & COORDINATION ================= -->
				<div id="tab-ai" class="scraper-tab-panel">

					<!-- 1. کارت مدل مستر اسکرپر۴ -->
					<div class="admin-card" style="border: 2px solid #fbbf24; background: linear-gradient(180deg, #fffbeb 0%, #ffffff 100%);">
						<div class="admin-card-header" style="border-bottom: 1px solid #fde68a;">
							<h3><span>⭐</span> مدل مستر هوش مصنوعی اسکرپر (Master AI Model)</h3>
							<span class="field-badge" style="background:#10b981; color:#fff;">🟢 متصل به سیستم هوش مصنوعی اسکرپر (scraper4)</span>
						</div>

						<div style="padding:15px 0 5px;">
							<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-bottom:14px;">
								<div>
									<div style="font-size:1.25rem; font-weight:900; color:#1e293b; display:flex; align-items:center; gap:8px;">
										<span>🤖</span>
										<span id="masterModelTitle"><?php echo esc_html( $master_ai['model_name'] ); ?></span>
										<span style="font-size:0.8rem; background:#fef3c7; color:#b45309; border:1px solid #fde68a; padding:2px 8px; border-radius:8px; font-weight:800;">⭐ مدل مستر فعال</span>
									</div>
									<div style="color:#64748b; font-size:0.88rem; margin-top:4px;">
										ارائه‌دهنده: <strong id="masterModelProvider" style="color:#2563eb;"><?php echo esc_html( $master_ai['provider_name'] ); ?></strong> | 
										کلید مرجع: <code id="masterModelKey" dir="ltr" style="background:#f1f5f9; padding:2px 6px; border-radius:4px;"><?php echo esc_html( $master_ai['key'] ); ?></code>
									</div>
								</div>
								<div style="text-align:left;">
									<div style="font-size:0.85rem; font-weight:800; color:#059669;">
										درصد موفقیت در آزمون‌ها: <span id="masterModelScore"><?php echo esc_html( round( ( $master_ai['score'] ?? 0.889 ) * 100, 1 ) ); ?>٪</span>
									</div>
									<div style="font-size:0.8rem; color:#64748b;">
										(<span id="masterModelWins"><?php echo esc_html( $master_ai['wins'] ?? 8 ); ?></span> برد از <span id="masterModelVotes"><?php echo esc_html( $master_ai['votes'] ?? 9 ); ?></span> آزمون مقایسه‌ای)
									</div>
								</div>
							</div>

							<div style="background:#ffffff; border:1px solid #fed7aa; border-radius:10px; padding:12px 16px; font-size:0.88rem; color:#475569; line-height:1.7;">
								<strong>💡 نحوه عملکرد مدل مستر:</strong>
								تمامی پاسخ‌های چت آنلاین مشتریان و دسته‌بندی‌های هوشمند با استفاده از این مدل و کاتالوگ زنده محصولات تولید می‌شود. سیستم به طور خودکار بهترین مدل را از نظر آماری از بین کاندیدهای اسکرپر برمی‌گزیند یا می‌توانید مدل دلخواه خود را در جدول زیر «سنجاق (Pin)» کنید.
							</div>
						</div>
					</div>

					<!-- 2. جدول مدل‌های کاندید هوش مصنوعی اسکرپر۴ -->
					<div class="admin-card">
						<div class="admin-card-header">
							<h3><span>🏆</span> فهرست مدل‌های کاندید هوش مصنوعی (Candidates List)</h3>
							<span class="field-badge field-badge-purple">رقابت و رتبه‌بندی مدل‌ها</span>
						</div>

						<p style="color:#64748b; font-size:0.9rem; line-height:1.6; margin-top:0;">
							مدل‌های زیر در سیستم هوش مصنوعی <code>scraper4.php</code> تنظیم شده‌اند. در بخش آزمون می‌توانید پاسخ این مدل‌ها را در کنار هم مقایسه کرده و با ثبت رأی، مدل مستر را تغییر دهید یا مستقیماً مدلی را به عنوان مستر سنجاق فرمایید:
						</p>

						<div style="overflow-x:auto;">
							<table class="wp-list-table widefat fixed striped" style="border-radius:10px; overflow:hidden; border:1px solid #e2e8f0;">
								<thead>
									<tr style="background:#f8fafc;">
										<th style="width:70px; text-align:center; font-weight:800;">وضعیت</th>
										<th style="width:130px; font-weight:800;">ارائه‌دهنده</th>
										<th style="font-weight:800;">مدل هوش مصنوعی</th>
										<th style="width:140px; text-align:center; font-weight:800;">امتیاز موفقیت</th>
										<th style="width:120px; text-align:center; font-weight:800;">تعداد آزمون</th>
										<th style="width:140px; text-align:center; font-weight:800;">عملیات</th>
									</tr>
								</thead>
								<tbody id="aiCandidatesTableBody">
									<?php if ( ! empty( $cands_info['candidates'] ) ) : ?>
										<?php foreach ( $cands_info['candidates'] as $cand ) : ?>
											<tr id="cand-row-<?php echo esc_attr( md5( $cand['key'] ) ); ?>">
												<td style="text-align:center;">
													<?php if ( ! empty( $cand['is_master'] ) ) : ?>
														<span style="font-size:1.1rem;" title="مدل مستر فعلی">⭐</span>
													<?php else : ?>
														<span style="color:#94a3b8; font-size:0.9rem;">—</span>
													<?php endif; ?>
												</td>
												<td>
													<strong style="color:#2563eb;"><?php echo esc_html( $cand['providerName'] ); ?></strong>
												</td>
												<td>
													<div style="font-weight:700; color:#1e293b;"><?php echo esc_html( $cand['modelName'] ); ?></div>
													<code dir="ltr" style="font-size:0.75rem; color:#64748b;"><?php echo esc_html( $cand['model'] ); ?></code>
												</td>
												<td style="text-align:center;">
													<span style="font-weight:800; color:<?php echo $cand['score'] > 0.7 ? '#059669' : ( $cand['score'] > 0.4 ? '#d97706' : '#64748b' ); ?>;">
														<?php echo esc_html( round( $cand['score'] * 100, 1 ) ); ?>٪
													</span>
													<div style="font-size:0.75rem; color:#64748b;">(<?php echo esc_html( $cand['wins'] ); ?> برد)</div>
												</td>
												<td style="text-align:center; color:#475569; font-weight:700;">
													<?php echo esc_html( $cand['votes'] ); ?>
												</td>
												<td style="text-align:center;">
													<?php if ( ! empty( $cand['is_master'] ) ) : ?>
														<span style="font-size:0.8rem; background:#fef3c7; color:#b45309; padding:4px 10px; border-radius:6px; font-weight:800;">
															✓ مستر فعال
														</span>
													<?php else : ?>
														<button type="button" class="button button-small btn-pin-candidate" data-key="<?php echo esc_attr( $cand['key'] ); ?>" style="font-size:0.8rem; border-radius:6px;">
															📌 سنجاق مستر
														</button>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									<?php else : ?>
										<tr>
											<td colspan="6" style="text-align:center; color:#64748b; padding:20px;">هیچ مدل کاندیدی تعریف نشده است. می‌توانید فایل کانفیگ را بارگذاری کنید.</td>
										</tr>
									<?php endif; ?>
								</tbody>
							</table>
						</div>
					</div>

					<!-- 3. جعبه بارگذاری و مدیریت فایل‌های کانفیگ اسکرپر (Upload & Import Config Files) -->
					<div class="admin-card" style="background:#f8fafc; border:1.5px solid #cbd5e1;">
						<div class="admin-card-header">
							<h3><span>📂</span> بارگذاری و درون‌ریزی فایل‌های کانفیگ هوش مصنوعی (Config Upload)</h3>
							<span class="field-badge field-badge-blue">همگام‌سازی آسان اسکرپر</span>
						</div>

						<p style="color:#64748b; font-size:0.9rem; line-height:1.6; margin-top:0;">
							اگر تنظیمات، ارائه‌دهندگان یا کلیدهای API هوش مصنوعی را در فایل‌های <code>connections.json</code>، <code>ai_providers.json</code> یا <code>ai_votes.json</code> اسکرپر۴ دارید یا می‌خواهید فایل کانفیگ را از کامپیوتر بارگذاری نمایید، کافیست فایل JSON را اینجا آپلود یا رها کنید:
						</p>

						<div style="background:#ffffff; border:2px dashed #cbd5e1; border-radius:12px; padding:24px; text-align:center; margin-bottom:15px;" id="aiConfigDropZone">
							<div style="font-size:2rem; margin-bottom:8px;">📁</div>
							<div style="font-weight:700; color:#1e293b; margin-bottom:6px; font-size:0.95rem;">فایل کانفیگ (JSON) را بکشید و اینجا رها کنید یا دکمه زیر را بزنید:</div>
							<div style="color:#94a3b8; font-size:0.82rem; margin-bottom:14px;">پشتیبانی از connections.json، ai_providers.json، ai_votes.json یا پکیج جامع تنظیمات اسکرپر</div>
							
							<input type="file" id="aiConfigFileInput" accept=".json" style="display:none;">
							<div style="display:flex; justify-content:center; gap:10px; flex-wrap:wrap;">
								<button type="button" id="btnSelectAiConfigFile" class="button button-secondary" style="font-weight:700; padding:6px 16px; border-radius:8px;">
									📂 انتخاب فایل کانفیگ از سیستم
								</button>
								<button type="button" id="btnUploadAiConfigFile" class="button button-primary" style="background:#059669; border-color:#047857; font-weight:800; padding:6px 20px; border-radius:8px;" disabled>
									📥 بارگذاری و اعمال فوری در اسکرپر
								</button>
								<button type="button" id="btnExportAiConfigFile" class="button button-secondary" style="font-weight:700; padding:6px 16px; border-radius:8px;">
									📤 دانلود نسخه پشتیبان تنظیمات (Export)
								</button>
							</div>
							<div id="aiConfigSelectedFileName" style="margin-top:10px; font-size:0.85rem; color:#2563eb; font-weight:700; display:none;"></div>
						</div>

						<div id="aiConfigUploadStatus" style="display:none; border-radius:8px; padding:12px 16px; font-size:0.9rem; margin-top:10px;"></div>
					</div>

					<!-- 4. کنسول تست زنده هوش مصنوعی و آزمون مقایسه‌ای کاندیدها -->
					<div class="admin-card">
						<div class="admin-card-header">
							<h3><span>🧪</span> کنسول تست زنده هوش مصنوعی و آزمون مقایسه‌ای کاندیدها</h3>
							<span class="field-badge field-badge-purple">تست واقعی و ثبت رای</span>
						</div>

						<p style="color:#64748b; font-size:0.9rem; line-height:1.6; margin-top:0;">
							می‌توانید پیام دلخواه خود یا یکی از سوالات پرتکرار زیر را انتخاب کنید و عملکرد مدل مستر یا همه کاندیدها را کنار هم ارزیابی نمایید:
						</p>

						<!-- پرسش‌های آماده سریع -->
						<div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
							<button type="button" class="button button-small btn-quick-preset" data-msg="سلام، ساعت هوشمند T800 ضد آبه؟ چه قابلیت‌هایی داره؟" style="border-radius:20px; font-size:0.82rem;">
								🔹 ساعت T800 ضد آب است؟
							</button>
							<button type="button" class="button button-small btn-quick-preset" data-msg="سلام وقت بخیر، قیمت ساعت الترا چنده و تخفیف داره؟" style="border-radius:20px; font-size:0.82rem;">
								🔹 قیمت ساعت الترا با تخفیف
							</button>
							<button type="button" class="button button-small btn-quick-preset" data-msg="سفارشات چند روزه ارسال میشه و هزینه ارسال چقدره؟" style="border-radius:20px; font-size:0.82rem;">
								🔹 شرایط و هزینه ارسال
							</button>
							<button type="button" class="button button-small btn-quick-preset" data-msg="آیا محصولات شما ضمانت تعویض یا گارانتی دارند؟" style="border-radius:20px; font-size:0.82rem;">
								🔹 گارانتی و ضمانت اصالت
							</button>
						</div>

						<div style="display:flex; gap:10px; flex-wrap:wrap; margin-bottom:12px;">
							<input type="text" id="testAiChatMessage" placeholder="سوال خود را بنویسید (مثال: سلام، ساعت هوشمند با قابلیت مکالمه موجود دارید؟)" style="flex:1; min-width:280px; padding:10px 14px; border:1.5px solid #cbd5e1; border-radius:10px; font-size:0.9rem; outline:none;">
							<button type="button" id="btnRunTestAiChat" class="button button-primary" style="padding:8px 20px; font-weight:800; background:#2563eb; border-color:#1d4ed8; border-radius:10px; font-size:0.9rem;">
								🚀 پرسش از مدل مستر (Master AI)
							</button>
							<button type="button" id="btnCompareAiCandidates" class="button button-secondary" style="padding:8px 20px; font-weight:800; background:#7c3aed; color:#fff; border-color:#6d28d9; border-radius:10px; font-size:0.9rem;">
								⚖️ آزمون مقایسه‌ای همه کاندیدها
							</button>
						</div>

						<!-- نتیجه تست مدل مستر -->
						<div id="testAiChatResultBox" style="display:none; background:#ffffff; border:1.5px solid #e2e8f0; border-radius:12px; padding:16px; margin-top:10px;">
							<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; border-bottom:1px solid #f1f5f9; padding-bottom:6px;">
								<div style="font-weight:800; font-size:0.9rem; color:#059669; display:flex; align-items:center; gap:6px;">
									<span>⭐</span> پاسخ زنده مدل مستر اسکرپر:
								</div>
								<div style="display:flex; gap:8px; align-items:center;">
									<span id="testAiModelUsedBadge" style="font-size:0.75rem; background:#eff6ff; color:#1d4ed8; padding:2px 8px; border-radius:6px; font-family:monospace;"></span>
									<span id="testAiTimeBadge" style="font-size:0.75rem; background:#f1f5f9; color:#475569; padding:2px 6px; border-radius:6px;"></span>
								</div>
							</div>
							<div id="testAiResponseText" style="font-size:0.95rem; color:#1e293b; line-height:1.8; white-space:pre-wrap;"></div>
						</div>

						<!-- نتیجه آزمون مقایسه‌ای کاندیدها -->
						<div id="testAiCandidatesCompareBox" style="display:none; margin-top:14px;">
							<div style="font-weight:800; font-size:0.95rem; color:#7c3aed; margin-bottom:10px; display:flex; align-items:center; gap:6px;">
								<span>⚖️</span> پاسخ همزمان همه کاندیدها (بهترین را برگزینید تا به عنوان رأی ثبت شود):
							</div>
							<div id="compareCandidatesCardsGrid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:14px;"></div>
						</div>
					</div>

					<!-- 5. شیوه هماهنگی هوش مصنوعی، ادمین و دستورالعمل رفتاری -->
					<div class="admin-card">
						<div class="admin-card-header">
							<h3><span>⚙️</span> تنظیمات هماهنگی هوش مصنوعی و پرامپت فروشگاه</h3>
							<span class="field-badge field-badge-purple">تنظیمات سیستمی</span>
						</div>

						<table class="form-table">
							<tr>
								<th scope="row">شیوه هماهنگی و تعامل (Coordination Mode):</th>
								<td>
									<fieldset>
										<label style="display:block; margin-bottom:12px; background:#f8fafc; padding:12px 16px; border-radius:10px; border:1px solid #e2e8f0;">
											<input type="radio" name="ai_coordination_mode" value="ai_first" <?php checked( ( $opts['ai_coordination_mode'] ?? 'ai_first' ) === 'ai_first' ); ?>>
											<strong style="color:#0f172a; font-size:0.95rem;">🤖 هوش مصنوعی پاسخگوی اول + ارجاع فوری به ادمین (توصیه شده):</strong>
											<div style="color:#64748b; font-size:0.85rem; margin-top:4px; margin-right:24px;">
												هوش مصنوعی فوراً به سوال مشتری در چت آنلاین پاسخ می‌دهد. همزمان پیام مشتری همراه با پاسخ داده‌شده برای ادمین در پیام‌رسان‌ها (بله/تلگرام/روبیکا) ارسال می‌شود تا ادمین در جریان مکالمه قرار گرفته و در صورت نیاز با شماره تماس مشتری ارتباط بگیرد.
											</div>
										</label>

										<label style="display:block; margin-bottom:12px; background:#f8fafc; padding:12px 16px; border-radius:10px; border:1px solid #e2e8f0;">
											<input type="radio" name="ai_coordination_mode" value="ai_copilot" <?php checked( ( $opts['ai_coordination_mode'] ?? 'ai_first' ) === 'ai_copilot' ); ?>>
											<strong style="color:#0f172a; font-size:0.95rem;">🤝 دستیار کمکی ادمین (AI Co-Pilot):</strong>
											<div style="color:#64748b; font-size:0.85rem; margin-top:4px; margin-right:24px;">
												هوش مصنوعی پاسخ پیشنهادی را برای ادمین در پیام‌رسان آماده می‌کند تا ادمین انسانی پس از بررسی پاسخ نهایی را به مشتری ارائه دهد.
											</div>
										</label>

										<label style="display:block; margin-bottom:12px; background:#f8fafc; padding:12px 16px; border-radius:10px; border:1px solid #e2e8f0;">
											<input type="radio" name="ai_coordination_mode" value="human_only" <?php checked( ( $opts['ai_coordination_mode'] ?? 'ai_first' ) === 'human_only' ); ?>>
											<strong style="color:#0f172a; font-size:0.95rem;">🧑‍💼 فقط ادمین انسانی (Human Only):</strong>
											<div style="color:#64748b; font-size:0.85rem; margin-top:4px; margin-right:24px;">
												هوش مصنوعی غیرفعال بوده و کلیه پیام‌های چت مستقیماً برای ادمین‌ها ارسال شده و پاسخ توسط انسان داده می‌شود.
											</div>
										</label>

										<label style="display:block; background:#f8fafc; padding:12px 16px; border-radius:10px; border:1px solid #e2e8f0;">
											<input type="radio" name="ai_coordination_mode" value="ai_only" <?php checked( ( $opts['ai_coordination_mode'] ?? 'ai_first' ) === 'ai_only' ); ?>>
											<strong style="color:#0f172a; font-size:0.95rem;">⚡ پاسخگویی تمام‌اتوماتیک ۲۴ ساعته (Full Auto AI):</strong>
											<div style="color:#64748b; font-size:0.85rem; margin-top:4px; margin-right:24px;">
												پاسخگویی سریع به سوالات مشتریان به طور کامل توسط هوش مصنوعی انجام می‌شود.
											</div>
										</label>
									</fieldset>
								</td>
							</tr>
							<tr>
								<th scope="row">نام نمایشی دستیار در چت:</th>
								<td>
									<input type="text" name="ai_support_name" value="<?php echo esc_attr( $opts['ai_support_name'] ?? 'پشتیبان هوشمند فروشگاه' ); ?>" class="regular-text">
								</td>
							</tr>
							<tr>
								<th scope="row">دستورالعمل و پرامپت رفتاری هوش مصنوعی:</th>
								<td>
									<textarea name="ai_system_prompt" rows="4" class="large-text"><?php echo esc_textarea( $opts['ai_system_prompt'] ?? '' ); ?></textarea>
									<p class="description">تعیین لحن، قوانین و اطلاعات فروشگاه که هوش مصنوعی در پاسخگویی به مشتریان رعایت می‌کند.</p>
								</td>
							</tr>
							<tr>
								<th scope="row">کلید API سفارشی (اختیاری):</th>
								<td>
									<input type="password" name="ai_api_key" value="<?php echo esc_attr( $opts['ai_api_key'] ?? '' ); ?>" class="regular-text" dir="ltr" placeholder="sk-... (اختیاری، پیش‌فرض از ai_providers.json اسکرپر خوانده می‌شود)">
									<p class="description">در صورت خالی بودن، کلید به صورت خودکار از تنظیمات فایل اسکرپر خوانده می‌شود.</p>
								</td>
							</tr>
						</table>

						<div style="margin-top:18px;background:linear-gradient(135deg,#eff6ff,#f5f3ff);border:1px solid #93c5fd;border-radius:14px;padding:16px;">
							<h4 style="margin:0 0 8px;color:#1e3a8a;">🖼️ تکمیل خودکار تصویر محصولات (جستجوی وب + AI)</h4>
							<p style="margin:0 0 12px;color:#1e40af;font-size:0.85rem;line-height:1.7;">
								برای کالاهایی که <strong>عکس ندارند</strong> یا فقط <strong>placeholder / کارت پیش‌فرض اسنپ‌شاپ</strong> دارند،
								سیستم در <strong>Google Images</strong> (و Bing/Openverse) جستجو می‌کند، با AI مناسب‌ترین عکس را برمی‌گزیند و در متای ووکامرس
								(<code>_amphp_ai_image_url</code>) ذخیره می‌کند — در صورت امکان فایل را هم sideload می‌کند.
							</p>
							<label style="display:flex;gap:8px;align-items:center;font-weight:800;margin-bottom:10px;">
								<input type="checkbox" name="enable_ai_product_images" value="1" <?php checked( ! isset( $opts['enable_ai_product_images'] ) || ! empty( $opts['enable_ai_product_images'] ) ); ?>>
								فعال‌سازی غنی‌سازی خودکار تصویر
							</label>
							<label style="display:flex;gap:8px;align-items:center;font-weight:700;margin-bottom:10px;color:#334155;">
								<input type="checkbox" name="ai_image_sideload" value="1" <?php checked( ! isset( $opts['ai_image_sideload'] ) || ! empty( $opts['ai_image_sideload'] ) ); ?>>
								دانلود و پیوست تصویر به رسانه وردپرس (Featured Image)
							</label>
							<label style="font-weight:700;font-size:0.85rem;display:block;margin-bottom:8px;">تعداد در هر اجرا (کرون / دکمه)
								<input type="number" name="ai_image_batch_size" min="1" max="30" value="<?php echo esc_attr( $opts['ai_image_batch_size'] ?? 8 ); ?>" style="width:80px;margin-right:8px;">
							</label>
							<label style="display:flex;gap:8px;align-items:center;font-weight:800;margin:12px 0 8px;color:#1e3a8a;">
								<input type="checkbox" name="enable_google_image_search" value="1" <?php checked( ! isset( $opts['enable_google_image_search'] ) || ! empty( $opts['enable_google_image_search'] ) ); ?>>
								جستجو در Google Images (اولویت اول)
							</label>
							<p style="margin:0 0 10px;font-size:0.8rem;color:#334155;line-height:1.6;">
								بدون کلید هم از صفحهٔ Google Images استفاده می‌شود. برای نتیجه پایدارتر،
								<a href="https://developers.google.com/custom-search/v1/overview" target="_blank" rel="noopener">Google Programmable Search (CSE)</a>
								با <code>searchType=image</code> بسازید و کلید را وارد کنید.
							</p>
							<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:10px;">
								<label style="font-weight:700;font-size:0.82rem;display:block;">Google CSE API Key
									<input type="password" name="google_cse_api_key" dir="ltr" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['google_cse_api_key'] ?? '' ); ?>" placeholder="AIza…">
								</label>
								<label style="font-weight:700;font-size:0.82rem;display:block;">Search Engine ID (cx)
									<input type="text" name="google_cse_cx" dir="ltr" style="width:100%;margin-top:4px;" value="<?php echo esc_attr( $opts['google_cse_cx'] ?? '' ); ?>" placeholder="a1b2c3d4e">
								</label>
							</div>
							<?php
							$img_last = get_option( 'amphp_ai_image_last_run', array() );
							if ( is_array( $img_last ) && ! empty( $img_last['at'] ) ) :
								?>
								<p style="font-size:0.8rem;color:#64748b;margin:8px 0;">آخرین اجرا: <?php echo esc_html( date_i18n( 'Y/m/d H:i', intval( $img_last['at'] ) ) ); ?> — <?php echo esc_html( (string) ( $img_last['message'] ?? '' ) ); ?></p>
							<?php endif; ?>
							<button type="button" class="button button-primary" id="amphpAiImgEnrichBtn" style="margin-top:6px;">🔍 اجرای الان غنی‌سازی تصویر</button>
							<span id="amphpAiImgEnrichStatus" style="margin-right:10px;font-weight:700;color:#1e40af;"></span>
							<script>
							(function(){
							  var btn=document.getElementById('amphpAiImgEnrichBtn');
							  var st=document.getElementById('amphpAiImgEnrichStatus');
							  if(!btn) return;
							  btn.addEventListener('click', function(){
							    btn.disabled=true; st.textContent='در حال جستجو و ذخیره…';
							    var fd=new FormData();
							    fd.append('action','scraper_ai_enrich_images');
							    fd.append('nonce', '<?php echo esc_js( wp_create_nonce( "scraper_shop_admin_nonce" ) ); ?>');
							    fd.append('limit', document.querySelector('[name="ai_image_batch_size"]')?.value||'8');
							    fetch(ajaxurl,{method:'POST',body:fd,credentials:'same-origin'}).then(function(r){return r.json()}).then(function(d){
							      btn.disabled=false;
							      st.textContent = (d && d.success) ? (d.data && d.data.message ? d.data.message : 'انجام شد') : (d && d.data ? d.data : 'خطا');
							    }).catch(function(){ btn.disabled=false; st.textContent='خطا در ارتباط با سرور'; });
							  });
							})();
							</script>
						</div>
					</div>

				</div>

				<!-- ================= TAB 5: MESSENGERS & NOTIFICATIONS ================= -->
				<div id="tab-messengers" class="scraper-tab-panel">
					<div class="admin-card">
						<div class="admin-card-header">
							<h3><span>📡</span> وضعیت اتصال به پیام‌رسان‌ها (بله / تلگرام / روبیکا)</h3>
							<span class="field-badge field-badge-blue">اتصال به اعلان‌های اسکرپر</span>
						</div>

						<p style="color:#64748b; font-size:0.92rem; line-height:1.6; margin-top:0;">
							تنظیمات زیر پیام‌های مشتریان را به پیام‌رسان‌های فایل اسکرپر ارسال می‌کند. اگر در بخش اعلان‌های اسکرپر (<code>connections.json</code>) توکن‌ها را وارد کرده باشید، سیستم به طور خودکار آن‌ها را شناسایی می‌نماید:
						</p>

						<!-- Status overview cards -->
						<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-bottom:20px;">
							<!-- Bale -->
							<div style="background:#f8fafc; border:1px solid <?php echo isset( $active_msgrs['bale'] ) ? '#bbf7d0' : '#e2e8f0'; ?>; border-radius:12px; padding:14px;">
								<div style="font-weight:800; display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
									<span>🔹 پیام‌رسان بله:</span>
									<?php if ( isset( $active_msgrs['bale'] ) ) : ?>
										<span style="color:#16a34a; font-size:0.8rem; font-weight:800;">🟢 متصل</span>
									<?php else : ?>
										<span style="color:#94a3b8; font-size:0.8rem;">⚪ تنظیم نشده</span>
									<?php endif; ?>
								</div>
								<div style="font-size:0.8rem; color:#64748b;">
									<?php echo isset( $active_msgrs['bale'] ) ? 'منبع: ' . ( 'scraper_connections' === $active_msgrs['bale']['source'] ? 'connections.json اسکرپر' : 'تنظیمات افزونه' ) : 'توکن یا Chat ID خالی است.'; ?>
								</div>
							</div>

							<!-- Telegram -->
							<div style="background:#f8fafc; border:1px solid <?php echo isset( $active_msgrs['telegram'] ) ? '#bbf7d0' : '#e2e8f0'; ?>; border-radius:12px; padding:14px;">
								<div style="font-weight:800; display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
									<span>🔹 تلگرام:</span>
									<?php if ( isset( $active_msgrs['telegram'] ) ) : ?>
										<span style="color:#16a34a; font-size:0.8rem; font-weight:800;">🟢 متصل</span>
									<?php else : ?>
										<span style="color:#94a3b8; font-size:0.8rem;">⚪ تنظیم نشده</span>
									<?php endif; ?>
								</div>
								<div style="font-size:0.8rem; color:#64748b;">
									<?php echo isset( $active_msgrs['telegram'] ) ? 'منبع: ' . ( 'scraper_connections' === $active_msgrs['telegram']['source'] ? 'connections.json اسکرپر' : 'تنظیمات افزونه' ) : 'توکن یا Chat ID خالی است.'; ?>
								</div>
							</div>

							<!-- Rubika -->
							<div style="background:#f8fafc; border:1px solid <?php echo isset( $active_msgrs['rubika'] ) ? '#bbf7d0' : '#e2e8f0'; ?>; border-radius:12px; padding:14px;">
								<div style="font-weight:800; display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
									<span>🔹 روبیکا:</span>
									<?php if ( isset( $active_msgrs['rubika'] ) ) : ?>
										<span style="color:#16a34a; font-size:0.8rem; font-weight:800;">🟢 متصل</span>
									<?php else : ?>
										<span style="color:#94a3b8; font-size:0.8rem;">⚪ تنظیم نشده</span>
									<?php endif; ?>
								</div>
								<div style="font-size:0.8rem; color:#64748b;">
									<?php echo isset( $active_msgrs['rubika'] ) ? 'منبع: ' . ( 'scraper_connections' === $active_msgrs['rubika']['source'] ? 'connections.json اسکرپر' : 'تنظیمات افزونه' ) : 'توکن یا Chat ID خالی است.'; ?>
								</div>
							</div>
						</div>

						<!-- Test Messenger Button -->
						<div style="margin-bottom:24px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
							<div>
								<strong>تست اتصال زنده:</strong>
								<span style="color:#475569; font-size:0.88rem; margin-right:6px;">برای اطمینان از دریافت پیام‌ها در بله، تلگرام یا روبیکا، یک پیام آزمایشی ارسال نمایید:</span>
							</div>
							<div style="display:flex; align-items:center; gap:10px;">
								<button type="button" id="btnTestMessengers" class="button button-secondary" style="font-weight:800; border-color:#2563eb; color:#2563eb; padding:5px 16px;">
									🔔 ارسال پیام آزمایشی
								</button>
								<span id="testMessengersStatus" style="font-size:0.88rem;"></span>
							</div>
						</div>

						<table class="form-table">
							<tr>
								<th scope="row" colspan="2">
									<strong style="color:#0f172a; font-size:1rem;">تنظیمات دستی پیام‌رسان‌ها (اختیاری):</strong>
								</th>
							</tr>
							<tr>
								<th scope="row">ربات بله (Bale):</th>
								<td>
									<input type="password" name="bale_token" value="<?php echo esc_attr( $opts['bale_token'] ?? '' ); ?>" placeholder="Bot Token بله" class="regular-text" dir="ltr" style="margin-left:10px;">
									<input type="text" name="bale_chat_id" value="<?php echo esc_attr( $opts['bale_chat_id'] ?? '' ); ?>" placeholder="شناسه عددی چت (Chat ID)" class="regular-text" dir="ltr">
								</td>
							</tr>
							<tr>
								<th scope="row">ربات تلگرام (Telegram):</th>
								<td>
									<input type="password" name="telegram_token" value="<?php echo esc_attr( $opts['telegram_token'] ?? '' ); ?>" placeholder="Bot Token تلگرام" class="regular-text" dir="ltr" style="margin-left:10px;">
									<input type="text" name="telegram_chat_id" value="<?php echo esc_attr( $opts['telegram_chat_id'] ?? '' ); ?>" placeholder="شناسه عددی چت (Chat ID)" class="regular-text" dir="ltr">
								</td>
							</tr>
							<tr>
								<th scope="row">ربات روبیکا (Rubika):</th>
								<td>
									<input type="password" name="rubika_token" value="<?php echo esc_attr( $opts['rubika_token'] ?? '' ); ?>" placeholder="Bot Token روبیکا" class="regular-text" dir="ltr" style="margin-left:10px;">
									<input type="text" name="rubika_chat_id" value="<?php echo esc_attr( $opts['rubika_chat_id'] ?? '' ); ?>" placeholder="شناسه عددی چت (Chat ID)" class="regular-text" dir="ltr">
								</td>
							</tr>
						</table>
					</div>
				</div>

				<!-- ================= TAB 6: STORE, ANALYTICS & WOOCOMMERCE ================= -->
				<div id="tab-woocommerce" class="scraper-tab-panel">

					<!-- Permanent WP-Cron Quick Action & Status Banner -->
					<div class="admin-cron-highlight-box" style="background:linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border:2px solid #3b82f6; border-radius:14px; padding:16px 20px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px; box-shadow:0 4px 14px rgba(37,99,235,0.08);">
						<div style="display:flex; align-items:center; gap:14px; flex-wrap:wrap;">
							<span style="font-size:2rem; background:#fff; width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; box-shadow:0 2px 8px rgba(0,0,0,0.06);">⏰</span>
							<div>
								<div style="font-size:1.02rem; font-weight:900; color:#1e3a8a; display:flex; align-items:center; gap:8px;">
									سیستم اجرای خودکار و کران‌جاب وردپرس (WP-Cron):
									<span class="field-badge" style="background:<?php echo $wpcron_info['is_active'] ? '#10b981' : '#ef4444'; ?>; color:#fff; font-size:0.82rem; padding:3px 12px; border-radius:20px;">
										<?php echo $wpcron_info['is_active'] ? '🟢 فعال و زمان‌بندی‌شده' : '🔴 غیرفعال'; ?>
									</span>
								</div>
								<div style="font-size:0.86rem; color:#334155; margin-top:4px;">
									⏱️ زمان اجرای بعدی: <strong style="color:#2563eb;" id="wpCronQuickNextRun"><?php echo esc_html( $wpcron_info['next_human'] ); ?></strong> &nbsp;|&nbsp; 
									بازه تکرار: <strong><?php echo esc_html( $wpcron_info['interval_label'] ); ?></strong> &nbsp;|&nbsp; 
									آخرین اجرا: <strong id="wpCronQuickLastRun"><?php echo ! empty( $wpcron_info['last_run']['date_fa'] ) ? esc_html( $wpcron_info['last_run']['date_fa'] ) : 'هنوز اجرا نشده'; ?></strong>
								</div>
							</div>
						</div>
						<div style="display:flex; align-items:center; gap:10px;">
							<button type="button" class="btnRunWpCronNow button button-primary" style="background:#2563eb; border-color:#1d4ed8; font-weight:800; padding:8px 20px; border-radius:8px; font-size:0.9rem; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 12px rgba(37,99,235,0.25);">
								<span>⚡</span> اجرای دستی کران‌جاب همین حالا (Run Now)
							</button>
						</div>
					</div>

					<!-- Sub-Tabs Navigation for Tab 6 -->
					<div class="shop-subtabs-nav" style="display:flex; gap:8px; margin-bottom:22px; border-bottom:2px solid #e2e8f0; padding-bottom:12px; flex-wrap:wrap;">
						<button type="button" class="shop-subtab-btn active" data-subtab="subtab-cron" style="padding:10px 20px; font-weight:800; font-size:0.92rem; border-radius:10px; border:1.5px solid #2563eb; background:#2563eb; color:#fff; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 12px rgba(37,99,235,0.2);">
							<span>⏰</span> ۱. زمان‌بندی و کرون‌جاب خودکار (WP-Cron)
						</button>
						<button type="button" class="shop-subtab-btn" data-subtab="subtab-analytics" style="padding:10px 20px; font-weight:800; font-size:0.92rem; border-radius:10px; border:1.5px solid #cbd5e1; background:#f8fafc; color:#475569; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
							<span>📊</span> ۲. آمار و تحلیل جامع فروشگاه
						</button>
						<button type="button" class="shop-subtab-btn" data-subtab="subtab-products" style="padding:10px 20px; font-weight:800; font-size:0.92rem; border-radius:10px; border:1.5px solid #cbd5e1; background:#f8fafc; color:#475569; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
							<span>🔄</span> ۳. ووکامرس و محصولات اسکرپر
						</button>
					</div>

					<input type="hidden" name="active_shop_subtab" id="activeShopSubtabInput" value="<?php echo esc_attr( sanitize_key( $_POST['active_shop_subtab'] ?? 'subtab-cron' ) ); ?>">

					<!-- ================= SUB-TAB 3: WP-CRON AUTOMATIC SYNC ================= -->
					<div id="subtab-cron" class="shop-subtab-panel active">
						<div class="admin-card" style="border:2px solid #3b82f6; background:linear-gradient(180deg, #eff6ff 0%, #ffffff 100%); margin:0; border-radius:12px;">
							<div class="admin-card-header" style="border-bottom:1px solid #bfdbfe;">
								<h3><span>⏰</span> اجرای خودکار استخراج و همگام‌سازی با کران‌جاب داخلی وردپرس (WP-Cron)</h3>
								<span class="field-badge" style="background:<?php echo $wpcron_info['is_active'] ? '#10b981' : '#64748b'; ?>; color:#fff;">
									<?php echo $wpcron_info['is_active'] ? '🟢 کران‌جاب وردپرس فعال و زمان‌بندی‌شده' : '⚪ کران‌جاب وردپرس غیرفعال'; ?>
								</span>
							</div>

							<p style="color:#475569; font-size:0.9rem; line-height:1.7; margin-top:0;">
								<strong>بدون نیاز به تنظیمات پیچیده cPanel یا هاست!</strong> سیستم کران‌جاب داخلی وردپرس (WP-Cron) به صورت کاملاً خودکار و در بازه‌های مشخص، فرآیند اجرای کران‌جاب این صفحه (<code>scraper4.php?cron_run=1</code>)، استخراج محصولات جدید، بررسی تغییر قیمت‌ها و همگام‌سازی را در پس‌زمینه انجام می‌دهد.
							</p>

							<!-- وضعیت زنده کران‌جاب -->
							<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px; margin-bottom:16px;">
								<div style="background:#ffffff; border:1px solid #cbd5e1; border-radius:10px; padding:14px;">
									<div style="color:#64748b; font-size:0.8rem; font-weight:700; margin-bottom:4px;">⏱️ زمان اجرای بعدی:</div>
									<div style="font-size:1.05rem; font-weight:900; color:#2563eb;" id="wpCronNextRunText">
										<?php echo esc_html( $wpcron_info['next_human'] ); ?>
									</div>
									<div style="font-size:0.75rem; color:#94a3b8; margin-top:2px;">
										(بازه: <?php echo esc_html( $wpcron_info['interval_label'] ); ?>)
									</div>
								</div>

								<div style="background:#ffffff; border:1px solid #cbd5e1; border-radius:10px; padding:14px;">
									<div style="color:#64748b; font-size:0.8rem; font-weight:700; margin-bottom:4px;">📋 آخرین اجرای خودکار:</div>
									<div style="font-size:0.95rem; font-weight:800; color:#1e293b;" id="wpCronLastRunText">
										<?php echo ! empty( $wpcron_info['last_run']['date_fa'] ) ? esc_html( $wpcron_info['last_run']['date_fa'] ) : 'هنوز اجرا نشده'; ?>
									</div>
									<div style="font-size:0.75rem; color:<?php echo ( ( $wpcron_info['last_run']['status'] ?? '' ) === 'success' ) ? '#059669' : '#64748b'; ?>; margin-top:2px;">
										<?php echo ! empty( $wpcron_info['last_run']['took_sec'] ) ? ( 'مدت زمان: ' . esc_html( $wpcron_info['last_run']['took_sec'] ) . ' ثانیه' ) : '—'; ?>
									</div>
								</div>
							</div>

							<!-- فرم تنظیمات زمان‌بندی WP-Cron -->
							<table class="form-table" style="margin-bottom:16px;">
								<tr>
									<th scope="row" style="width:230px;">فعال‌سازی کران‌جاب وردپرس:</th>
									<td>
										<label style="display:flex; align-items:center; gap:8px; font-weight:700; cursor:pointer;">
											<input type="checkbox" name="enable_wp_cron_sync" value="1" <?php checked( ! empty( $opts['enable_wp_cron_sync'] ) ); ?> style="width:18px; height:18px; accent-color:#2563eb;">
											اجرای دوره‌ای و پس‌زمینه اسکرپر توسط سیستم زمان‌بندی وردپرس (WP-Cron)
										</label>
									</td>
								</tr>
								<tr>
									<th scope="row">دوره تکرار اجرا (Interval):</th>
									<td>
										<select name="wp_cron_interval" class="regular-text" style="font-weight:700;">
											<?php foreach ( $wpcron_info['interval_labels'] as $int_key => $int_label ) : ?>
												<option value="<?php echo esc_attr( $int_key ); ?>" <?php selected( $wpcron_info['interval'], $int_key ); ?>>
													<?php echo esc_html( $int_label ); ?>
												</option>
											<?php endforeach; ?>
										</select>
										<p class="description">تعیین بازه تکرار اجرای خودکار کران اسکرپر در سیستم وردپرس.</p>
									</td>
								</tr>
							</table>

							<!-- دکمه اجرای دستی همین حالا -->
							<div style="background:#ffffff; border:1px solid #bfdbfe; border-radius:10px; padding:14px 16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
								<div>
									<strong style="color:#1e293b; font-size:0.92rem; display:block;">آزمایش و اجرای فوری کران‌جاب:</strong>
									<span style="color:#64748b; font-size:0.82rem;">می‌توانید بدون معطلی برای رسیدن زمان‌بندی، کران‌جاب اسکرپر را همین الان اجرا و نتیجه را بررسی نمایید.</span>
								</div>
								<button type="button" id="btnRunWpCronNow" class="button button-primary" style="background:#2563eb; border-color:#1d4ed8; font-weight:800; padding:6px 20px; border-radius:8px;">
									⚡ اجرای دستی کران‌جاب همین حالا (Run Now)
								</button>
							</div>
							<div id="wpCronRunNowStatus" style="display:none; margin-top:10px; padding:10px 14px; border-radius:8px; font-size:0.88rem;"></div>

							<!-- راهنمای تضمینی کران‌جاب ۱ دقیقه‌ای سرور -->
							<div style="background:#f8fafc; border:1.5px solid #cbd5e1; border-radius:10px; padding:16px 18px; margin-top:18px;">
								<div style="display:flex; align-items:center; gap:8px; margin-bottom:8px;">
									<span style="font-size:1.3rem;">⚡</span>
									<strong style="color:#0f172a; font-size:0.95rem;">راهکار تضمینی اجرای دقیق راس هر ۱ دقیقه (حتی بدون هیچ بازدیدکننده):</strong>
								</div>
								<p style="color:#475569; font-size:0.86rem; line-height:1.7; margin:0 0 10px 0;">
									<strong>نکته مهم:</strong> اعلان پیام‌های مشتری به محض ارسال در چت، به صورت <strong>کاملاً آنی (زیر ۱ ثانیه)</strong> به پیام‌رسان‌های بله، تلگرام و روبیکا فرستاده می‌شود. علاوه بر آن، با انتخاب بازه «هر ۱ دقیقه یک‌بار»، کران‌جاب هر ۶۰ ثانیه صف پیام‌های معلق را بازرسی می‌کند. برای اینکه این چرخه حتی وقتی هیچ کاربری در سایت آنلاین نیست دقیقاً کار کند، می‌توانید دستور زیر را در بخش <strong>Cron Jobs کنترل‌پنل هاست (cPanel / DirectAdmin)</strong> قرار دهید:
								</p>
								<div style="background:#0f172a; color:#38bdf8; font-family:monospace; padding:12px 16px; border-radius:8px; font-size:0.88rem; direction:ltr; text-align:left; user-select:all; overflow-x:auto; border:1px solid #334155;">
									* * * * * wget -q -O - "<?php echo esc_url( site_url( '/wp-cron.php?doing_wp_cron' ) ); ?>" >/dev/null 2>&1
								</div>
							</div>
						</div>
					</div>

					<!-- ================= SUB-TAB 1: ANALYTICS & FUNNEL ================= -->
					<div id="subtab-analytics" class="shop-subtab-panel" style="display:none;">
						<?php
						$analytics_source = $opts['analytics_source'] ?? 'hybrid';
						$wp_meta          = $analytics_data['wp_core_meta'] ?? array();
						$source_labels    = array(
							'internal'  => 'آمار داخلی افزونه (قیف رویدادها)',
							'wordpress' => 'هسته آمار وردپرس / ووکامرس',
							'hybrid'    => 'ترکیبی (افزونه + هسته وردپرس)',
						);
						$source_label_fa = $source_labels[ $analytics_source ] ?? $source_labels['hybrid'];
						?>

						<!-- Analytics Source + Admin Clean-up Settings -->
						<div class="admin-card" style="border:2px solid #6366f1; background:linear-gradient(180deg,#eef2ff 0%,#ffffff 100%); margin-bottom:20px;">
							<div class="admin-card-header" style="border-bottom:1px solid #c7d2fe;">
								<h3><span>⚙️</span> منبع آمار و پاکسازی پنل ادمین</h3>
								<span class="field-badge" style="background:#6366f1;color:#fff;">تنظیمات جدید</span>
							</div>

							<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:16px;">
								<div>
									<label style="display:block; font-weight:800; color:#312e81; margin-bottom:8px; font-size:0.92rem;">
										📊 منبع نمایش آمار فروشگاه
									</label>
									<select name="analytics_source" style="width:100%; max-width:420px; padding:10px 12px; border-radius:10px; border:1.5px solid #a5b4fc; font-weight:700; background:#fff;">
										<option value="internal"  <?php selected( $analytics_source, 'internal' ); ?>>فقط آمار داخلی افزونه (رویدادهای ویترین)</option>
										<option value="wordpress" <?php selected( $analytics_source, 'wordpress' ); ?>>فقط هسته آمار وردپرس / ووکامرس</option>
										<option value="hybrid"    <?php selected( $analytics_source, 'hybrid' ); ?>>ترکیبی — ادغام افزونه + هسته وردپرس (پیشنهادی)</option>
									</select>
									<p style="margin:8px 0 0; font-size:0.8rem; color:#64748b; line-height:1.6;">
										در حالت <strong>هسته وردپرس</strong>، بازدیدها از شمارندهٔ بومی وردپرس (و در صورت نصب بودن افزونه WP Statistics)، سفارش‌ها و کالاهای پرفروش مستقیماً از دیتابیس ووکامرس خوانده می‌شوند.
									</p>
									<div style="margin-top:10px; background:#fff; border:1px dashed #a5b4fc; border-radius:10px; padding:10px 12px; font-size:0.82rem; color:#4338ca; font-weight:700;">
										منبع فعال الان: <?php echo esc_html( $source_label_fa ); ?>
									</div>
								</div>

								<div>
									<label style="display:flex; align-items:flex-start; gap:10px; background:#fff; border:1.5px solid #c4b5fd; border-radius:12px; padding:14px; cursor:pointer;">
										<input type="checkbox" name="hide_admin_nags" value="1" <?php checked( ! empty( $opts['hide_admin_nags'] ) ); ?> style="width:18px; height:18px; accent-color:#7c3aed; margin-top:2px;">
										<div>
											<strong style="display:block; font-size:0.92rem; color:#5b21b6;">🧹 غیرفعال‌سازی تبلیغات و هشدارهای آزاردهنده افزونه‌ها</strong>
											<span style="display:block; font-size:0.78rem; color:#64748b; margin-top:4px; line-height:1.6;">
												بنرهای ارتقا، پیشنهاد پرمیوم، Freemius، اعلان‌های بازاریابی ووکامرس/المنتور/یواست و سایر nagهای غیرضروری در کل پنل وردپرس مخفی می‌شوند. اعلان‌های خطای حیاتی و پیام‌های خود این افزونه حفظ می‌گردند.
											</span>
										</div>
									</label>
									<button type="submit" name="scraper_shop_save" class="button button-primary" style="margin-top:12px; background:#6366f1; border-color:#4f46e5; font-weight:800; border-radius:8px; padding:8px 18px;">
										💾 ذخیره منبع آمار و پاکسازی ادمین
									</button>
								</div>
							</div>

							<?php if ( ! empty( $wp_meta ) && in_array( $analytics_source, array( 'wordpress', 'hybrid' ), true ) ) : ?>
							<div style="margin-top:16px; display:grid; grid-template-columns:repeat(auto-fit, minmax(120px, 1fr)); gap:10px;">
								<?php
								$meta_cards = array(
									array( '📦', 'محصولات ووکامرس', $wp_meta['wc_products'] ?? 0 ),
									array( '👥', 'کاربران', $wp_meta['users'] ?? 0 ),
									array( '🧾', 'مشتریان WC', $wp_meta['wc_customers'] ?? 0 ),
									array( '📝', 'نوشته‌ها', $wp_meta['posts'] ?? 0 ),
									array( '📄', 'برگه‌ها', $wp_meta['pages'] ?? 0 ),
									array( '💬', 'دیدگاه‌ها', $wp_meta['comments'] ?? 0 ),
									array( '🖼️', 'رسانه', $wp_meta['media'] ?? 0 ),
									array( '💰', 'جمع فروش', isset( $wp_meta['revenue_total'] ) ? self::format_price( $wp_meta['revenue_total'] ) : '—' ),
								);
								foreach ( $meta_cards as $mc ) :
									$is_money = ( false !== strpos( (string) $mc[1], 'فروش' ) );
									?>
									<div style="background:#fff; border:1px solid #e0e7ff; border-radius:10px; padding:10px; text-align:center;">
										<div style="font-size:1.1rem;"><?php echo $mc[0]; ?></div>
										<div style="font-size:0.72rem; color:#64748b; font-weight:700; margin:4px 0;"><?php echo esc_html( $mc[1] ); ?></div>
										<div style="font-size:0.95rem; font-weight:900; color:#312e81;">
											<?php echo $is_money ? esc_html( $mc[2] ) : self::to_fa_num( is_numeric( $mc[2] ) ? number_format( intval( $mc[2] ) ) : $mc[2] ); ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
							<?php endif; ?>
						</div>

						<?php
						$totals         = $analytics_data['totals'] ?? array();
						$site_visits    = max( 0, intval( $totals['site_visit'] ?? 0 ) );
						$product_views  = max( 0, intval( $totals['product_view'] ?? 0 ) );
						$add_to_cart    = max( 0, intval( $totals['add_to_cart'] ?? 0 ) );
						$checkout_steps = max( 0, intval( $totals['checkout_step'] ?? 0 ) );
						$orders_placed  = max( 0, intval( $totals['order_placed'] ?? 0 ) );

						$view_rate     = $site_visits > 0 ? round( ( $product_views / $site_visits ) * 100, 1 ) : 0;
						$cart_rate     = $product_views > 0 ? round( ( $add_to_cart / $product_views ) * 100, 1 ) : 0;
						$checkout_rate = $add_to_cart > 0 ? round( ( $checkout_steps / $add_to_cart ) * 100, 1 ) : 0;
						$order_rate    = $checkout_steps > 0 ? round( ( $orders_placed / $checkout_steps ) * 100, 1 ) : 0;
						$overall_conv  = $site_visits > 0 ? round( ( $orders_placed / $site_visits ) * 100, 2 ) : 0;
						?>

						<!-- 1. KPI Metric Summary Cards -->
						<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:14px; margin-bottom:24px;">
							<!-- Site Visits -->
							<div style="background:linear-gradient(135deg, #eff6ff 0%, #ffffff 100%); border:1.5px solid #bfdbfe; border-radius:14px; padding:16px; box-shadow:0 2px 8px rgba(37,99,235,0.06);">
								<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
									<span style="font-size:0.8rem; font-weight:800; color:#1e40af;">🌐 بازدید از سایت</span>
									<span style="background:#dbeafe; color:#1d4ed8; font-size:0.7rem; font-weight:800; padding:2px 7px; border-radius:8px;">کل</span>
								</div>
								<div style="font-size:1.6rem; font-weight:900; color:#0f172a; line-height:1.2;">
									<?php echo self::to_fa_num( number_format( $site_visits ) ); ?>
								</div>
								<div style="font-size:0.75rem; color:#64748b; margin-top:4px;">ورود به صفحات فروشگاه</div>
							</div>

							<!-- Product Views -->
							<div style="background:linear-gradient(135deg, #f0fdfa 0%, #ffffff 100%); border:1.5px solid #99f6e4; border-radius:14px; padding:16px; box-shadow:0 2px 8px rgba(13,148,136,0.06);">
								<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
									<span style="font-size:0.8rem; font-weight:800; color:#0f766e;">👁️ مشاهده محصول</span>
									<span style="background:#ccfbf1; color:#0d9488; font-size:0.7rem; font-weight:800; padding:2px 7px; border-radius:8px;"><?php echo self::to_fa_num( $view_rate ); ?>%</span>
								</div>
								<div style="font-size:1.6rem; font-weight:900; color:#0f172a; line-height:1.2;">
									<?php echo self::to_fa_num( number_format( $product_views ) ); ?>
								</div>
								<div style="font-size:0.75rem; color:#64748b; margin-top:4px;">مشاهده جزئیات کالا</div>
							</div>

							<!-- Add to Cart -->
							<div style="background:linear-gradient(135deg, #ecfdf5 0%, #ffffff 100%); border:1.5px solid #a7f3d0; border-radius:14px; padding:16px; box-shadow:0 2px 8px rgba(16,185,129,0.06);">
								<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
									<span style="font-size:0.8rem; font-weight:800; color:#065f46;">🛒 افزودن به سبد</span>
									<span style="background:#d1fae5; color:#059669; font-size:0.7rem; font-weight:800; padding:2px 7px; border-radius:8px;"><?php echo self::to_fa_num( $cart_rate ); ?>%</span>
								</div>
								<div style="font-size:1.6rem; font-weight:900; color:#0f172a; line-height:1.2;">
									<?php echo self::to_fa_num( number_format( $add_to_cart ) ); ?>
								</div>
								<div style="font-size:0.75rem; color:#64748b; margin-top:4px;">انتخاب برای خرید</div>
							</div>

							<!-- Checkout Steps -->
							<div style="background:linear-gradient(135deg, #fffbeb 0%, #ffffff 100%); border:1.5px solid #fde68a; border-radius:14px; padding:16px; box-shadow:0 2px 8px rgba(217,119,6,0.06);">
								<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
									<span style="font-size:0.8rem; font-weight:800; color:#92400e;">💳 رفتن به تسویه</span>
									<span style="background:#fef3c7; color:#d97706; font-size:0.7rem; font-weight:800; padding:2px 7px; border-radius:8px;"><?php echo self::to_fa_num( $checkout_rate ); ?>%</span>
								</div>
								<div style="font-size:1.6rem; font-weight:900; color:#0f172a; line-height:1.2;">
									<?php echo self::to_fa_num( number_format( $checkout_steps ) ); ?>
								</div>
								<div style="font-size:0.75rem; color:#64748b; margin-top:4px;">ورود به نهایی‌سازی</div>
							</div>

							<!-- Orders Placed -->
							<div style="background:linear-gradient(135deg, #fdf2f8 0%, #ffffff 100%); border:1.5px solid #fbcfe8; border-radius:14px; padding:16px; box-shadow:0 2px 8px rgba(219,39,119,0.06);">
								<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
									<span style="font-size:0.8rem; font-weight:800; color:#9d174d;">🎁 ثبت سفارش</span>
									<span style="background:#fce7f3; color:#db2777; font-size:0.7rem; font-weight:800; padding:2px 7px; border-radius:8px;"><?php echo self::to_fa_num( $order_rate ); ?>%</span>
								</div>
								<div style="font-size:1.6rem; font-weight:900; color:#0f172a; line-height:1.2;">
									<?php echo self::to_fa_num( number_format( $orders_placed ) ); ?>
								</div>
								<div style="font-size:0.75rem; color:#64748b; margin-top:4px;">فروش‌های نهایی موفق</div>
							</div>

							<!-- Overall Conversion Rate -->
							<div style="background:linear-gradient(135deg, #faf5ff 0%, #ffffff 100%); border:1.5px solid #e9d5ff; border-radius:14px; padding:16px; box-shadow:0 2px 8px rgba(124,58,237,0.06);">
								<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
									<span style="font-size:0.8rem; font-weight:800; color:#6b21a8;">📈 نرخ تبدیل کل</span>
									<span style="background:#f3e8ff; color:#7c3aed; font-size:0.7rem; font-weight:800; padding:2px 7px; border-radius:8px;">قیف</span>
								</div>
								<div style="font-size:1.6rem; font-weight:900; color:#7c3aed; line-height:1.2;">
									<?php echo self::to_fa_num( $overall_conv ); ?>%
								</div>
								<div style="font-size:0.75rem; color:#64748b; margin-top:4px;">تبدیل بازدید به سفارش</div>
							</div>
						</div>

						<?php if ( $site_visits + $product_views + $add_to_cart + $checkout_steps + $orders_placed <= 0 ) : ?>
						<div style="margin-bottom:18px; background:linear-gradient(90deg,#eef2ff,#fdf2f8); border:1.5px solid #c7d2fe; border-radius:14px; padding:14px 18px; display:flex; gap:12px; align-items:flex-start;">
							<span style="font-size:1.4rem;">🧹</span>
							<div>
								<strong style="display:block; color:#312e81; font-size:0.95rem;">آمار ساختگی حذف شد — شروع از صفر</strong>
								<span style="font-size:0.82rem; color:#64748b; line-height:1.6;">اعداد دمو (مثل ۳۵۳۰ بازدید) دیگر نمایش داده نمی‌شوند. منبع آمار را روی «هسته وردپرس» یا «ترکیبی» بگذارید تا داده واقعی ووکامرس/بازدیدها نشان داده شود، یا منتظر رویدادهای زنده ویترین بمانید.</span>
							</div>
						</div>
						<?php endif; ?>

						<!-- 2. Visual Conversion Funnel — elegant line/area chart -->
						<div class="admin-card" style="overflow:hidden;">
							<div class="admin-card-header">
								<h3><span>📈</span> قیف تبدیل خطی فروشگاه (Line Conversion Funnel)</h3>
								<span class="field-badge field-badge-blue">تحلیل ریزش و تبدیل کاربران</span>
							</div>

							<p style="color:#64748b; font-size:0.88rem; line-height:1.6; margin-top:0;">
								مسیر تبدیل مشتری از ورود تا ثبت سفارش به‌صورت نمودار خطی نرم با سایه گرادیانی نمایش داده می‌شود:
							</p>

							<?php
							$funnel_steps = array(
								array(
									'key'   => 'site_visit',
									'label' => 'ورود به سایت',
									'short' => 'بازدید',
									'value' => $site_visits,
									'rate'  => 100,
									'color' => '#3b82f6',
									'color2'=> '#60a5fa',
								),
								array(
									'key'   => 'product_view',
									'label' => 'مشاهده محصول',
									'short' => 'مشاهده',
									'value' => $product_views,
									'rate'  => $view_rate,
									'color' => '#14b8a6',
									'color2'=> '#2dd4bf',
								),
								array(
									'key'   => 'add_to_cart',
									'label' => 'افزودن به سبد',
									'short' => 'سبد',
									'value' => $add_to_cart,
									'rate'  => $cart_rate,
									'color' => '#10b981',
									'color2'=> '#34d399',
								),
								array(
									'key'   => 'checkout_step',
									'label' => 'تسویه‌حساب',
									'short' => 'تسویه',
									'value' => $checkout_steps,
									'rate'  => $checkout_rate,
									'color' => '#f59e0b',
									'color2'=> '#fbbf24',
								),
								array(
									'key'   => 'order_placed',
									'label' => 'ثبت سفارش',
									'short' => 'سفارش',
									'value' => $orders_placed,
									'rate'  => $overall_conv,
									'color' => '#ec4899',
									'color2'=> '#f472b6',
								),
							);
							$funnel_max   = max( 1, $site_visits );
							$fv_w         = 720;
							$fv_h         = 260;
							$fv_pad_l     = 48;
							$fv_pad_r     = 24;
							$fv_pad_t     = 28;
							$fv_pad_b     = 52;
							$fv_plot_w    = $fv_w - $fv_pad_l - $fv_pad_r;
							$fv_plot_h    = $fv_h - $fv_pad_t - $fv_pad_b;
							$fv_n         = count( $funnel_steps );
							$fv_pts       = array();
							foreach ( $funnel_steps as $fi => $fs ) {
								$fx = $fv_pad_l + ( $fv_n > 1 ? ( $fi / ( $fv_n - 1 ) ) * $fv_plot_w : $fv_plot_w / 2 );
								$fy = $fv_pad_t + $fv_plot_h - ( max( 0, min( 1, $fs['value'] / $funnel_max ) ) * $fv_plot_h );
								$fv_pts[] = array( 'x' => round( $fx, 2 ), 'y' => round( $fy, 2 ), 'step' => $fs );
							}
							// Smooth cubic path
							$fv_line = '';
							$fv_area = '';
							if ( ! empty( $fv_pts ) ) {
								$fv_line = 'M ' . $fv_pts[0]['x'] . ' ' . $fv_pts[0]['y'];
								for ( $i = 1; $i < count( $fv_pts ); $i++ ) {
									$p0 = $fv_pts[ $i - 1 ];
									$p1 = $fv_pts[ $i ];
									$cx = ( $p0['x'] + $p1['x'] ) / 2;
									$fv_line .= ' C ' . $cx . ' ' . $p0['y'] . ', ' . $cx . ' ' . $p1['y'] . ', ' . $p1['x'] . ' ' . $p1['y'];
								}
								$last = $fv_pts[ count( $fv_pts ) - 1 ];
								$first = $fv_pts[0];
								$base_y = $fv_pad_t + $fv_plot_h;
								$fv_area = $fv_line . ' L ' . $last['x'] . ' ' . $base_y . ' L ' . $first['x'] . ' ' . $base_y . ' Z';
							}
							?>

							<div style="position:relative; background:linear-gradient(180deg, #f8fafc 0%, #ffffff 55%, #f1f5f9 100%); border:1px solid #e2e8f0; border-radius:16px; padding:8px 4px 4px; box-shadow:inset 0 1px 0 rgba(255,255,255,0.8);">
								<svg id="scraperFunnelLineChart" viewBox="0 0 <?php echo (int) $fv_w; ?> <?php echo (int) $fv_h; ?>" width="100%" height="280" style="display:block; max-width:100%; font-family:system-ui,Tahoma,sans-serif;" role="img" aria-label="نمودار خطی قیف تبدیل">
									<defs>
										<linearGradient id="funnelAreaGrad" x1="0" y1="0" x2="0" y2="1">
											<stop offset="0%" stop-color="#6366f1" stop-opacity="0.35"/>
											<stop offset="55%" stop-color="#3b82f6" stop-opacity="0.12"/>
											<stop offset="100%" stop-color="#3b82f6" stop-opacity="0.02"/>
										</linearGradient>
										<linearGradient id="funnelStrokeGrad" x1="0" y1="0" x2="1" y2="0">
											<stop offset="0%" stop-color="#3b82f6"/>
											<stop offset="25%" stop-color="#14b8a6"/>
											<stop offset="50%" stop-color="#10b981"/>
											<stop offset="75%" stop-color="#f59e0b"/>
											<stop offset="100%" stop-color="#ec4899"/>
										</linearGradient>
										<filter id="funnelGlow" x="-20%" y="-20%" width="140%" height="140%">
											<feGaussianBlur stdDeviation="3.5" result="blur"/>
											<feMerge>
												<feMergeNode in="blur"/>
												<feMergeNode in="SourceGraphic"/>
											</feMerge>
										</filter>
										<filter id="funnelSoftShadow" x="-10%" y="-10%" width="120%" height="130%">
											<feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="#6366f1" flood-opacity="0.18"/>
										</filter>
									</defs>

									<!-- subtle grid -->
									<?php for ( $gi = 0; $gi <= 4; $gi++ ) :
										$gy = $fv_pad_t + ( $gi / 4 ) * $fv_plot_h;
										$gval = (int) round( $funnel_max * ( 1 - $gi / 4 ) );
										?>
										<line x1="<?php echo $fv_pad_l; ?>" y1="<?php echo $gy; ?>" x2="<?php echo $fv_w - $fv_pad_r; ?>" y2="<?php echo $gy; ?>" stroke="#e2e8f0" stroke-width="1" stroke-dasharray="4 6"/>
										<text x="<?php echo $fv_pad_l - 8; ?>" y="<?php echo $gy + 4; ?>" text-anchor="end" fill="#94a3b8" font-size="10" font-weight="700"><?php echo esc_html( self::to_fa_num( number_format( $gval ) ) ); ?></text>
									<?php endfor; ?>

									<!-- area + glow line -->
									<?php if ( $fv_area ) : ?>
										<path d="<?php echo esc_attr( $fv_area ); ?>" fill="url(#funnelAreaGrad)" filter="url(#funnelSoftShadow)"/>
										<path d="<?php echo esc_attr( $fv_line ); ?>" fill="none" stroke="url(#funnelStrokeGrad)" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" filter="url(#funnelGlow)"/>
										<path d="<?php echo esc_attr( $fv_line ); ?>" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-opacity="0.55"/>
									<?php endif; ?>

									<!-- points + labels -->
									<?php foreach ( $fv_pts as $fi => $pt ) :
										$fs = $pt['step'];
										?>
										<!-- vertical guide -->
										<line x1="<?php echo $pt['x']; ?>" y1="<?php echo $fv_pad_t; ?>" x2="<?php echo $pt['x']; ?>" y2="<?php echo $fv_pad_t + $fv_plot_h; ?>" stroke="<?php echo esc_attr( $fs['color'] ); ?>" stroke-width="1" stroke-opacity="0.12" stroke-dasharray="3 5"/>
										<!-- outer glow ring -->
										<circle cx="<?php echo $pt['x']; ?>" cy="<?php echo $pt['y']; ?>" r="11" fill="<?php echo esc_attr( $fs['color'] ); ?>" fill-opacity="0.15"/>
										<circle cx="<?php echo $pt['x']; ?>" cy="<?php echo $pt['y']; ?>" r="6.5" fill="#fff" stroke="<?php echo esc_attr( $fs['color'] ); ?>" stroke-width="3"/>
										<circle cx="<?php echo $pt['x']; ?>" cy="<?php echo $pt['y']; ?>" r="2.8" fill="<?php echo esc_attr( $fs['color'] ); ?>"/>
										<!-- value bubble -->
										<rect x="<?php echo $pt['x'] - 28; ?>" y="<?php echo max( 6, $pt['y'] - 28 ); ?>" width="56" height="18" rx="9" fill="<?php echo esc_attr( $fs['color'] ); ?>" fill-opacity="0.95"/>
										<text x="<?php echo $pt['x']; ?>" y="<?php echo max( 6, $pt['y'] - 28 ) + 12.5; ?>" text-anchor="middle" fill="#fff" font-size="10" font-weight="800"><?php echo esc_html( self::to_fa_num( number_format( $fs['value'] ) ) ); ?></text>
										<!-- x label -->
										<text x="<?php echo $pt['x']; ?>" y="<?php echo $fv_h - 28; ?>" text-anchor="middle" fill="#334155" font-size="11" font-weight="800"><?php echo esc_html( $fs['short'] ); ?></text>
										<text x="<?php echo $pt['x']; ?>" y="<?php echo $fv_h - 12; ?>" text-anchor="middle" fill="<?php echo esc_attr( $fs['color'] ); ?>" font-size="10" font-weight="700"><?php echo esc_html( self::to_fa_num( $fs['rate'] ) ); ?>٪</text>
									<?php endforeach; ?>
								</svg>

								<!-- step chips under chart -->
								<div style="display:flex; flex-wrap:wrap; gap:8px; justify-content:center; padding:4px 10px 12px;">
									<?php foreach ( $funnel_steps as $fi => $fs ) : ?>
										<div style="display:inline-flex; align-items:center; gap:6px; background:#fff; border:1px solid <?php echo esc_attr( $fs['color'] ); ?>33; border-radius:999px; padding:5px 12px; box-shadow:0 2px 8px <?php echo esc_attr( $fs['color'] ); ?>14;">
											<span style="width:8px; height:8px; border-radius:50%; background:<?php echo esc_attr( $fs['color'] ); ?>; box-shadow:0 0 0 3px <?php echo esc_attr( $fs['color'] ); ?>22;"></span>
											<span style="font-size:0.78rem; font-weight:800; color:#0f172a;"><?php echo esc_html( ( $fi + 1 ) . '. ' . $fs['label'] ); ?></span>
											<span style="font-size:0.75rem; font-weight:800; color:<?php echo esc_attr( $fs['color'] ); ?>;"><?php echo self::to_fa_num( number_format( $fs['value'] ) ); ?></span>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</div>

						<!-- 3. Multi-series smooth Daily Line Chart -->
						<div class="admin-card" style="overflow:hidden;">
							<div class="admin-card-header">
								<h3><span>📉</span> روند خطی فعالیت‌های روزانه (Smooth Line Chart)</h3>
								<div id="scraperDailyLegend" style="display:flex; flex-wrap:wrap; gap:10px; align-items:center; font-size:0.78rem; font-weight:700;"></div>
							</div>

							<?php
							$daily_stats = $analytics_data['daily'] ?? array();
							if ( empty( $daily_stats ) || ! is_array( $daily_stats ) ) {
								$daily_stats = array();
							}
							ksort( $daily_stats );
							$recent_days = array_slice( $daily_stats, -14, 14, true );
							// Keep real days only — never invent synthetic series for empty stats.

							$series_def = array(
								'site_visit'    => array( 'label' => 'بازدید سایت', 'color' => '#3b82f6', 'color2' => '#93c5fd' ),
								'product_view'  => array( 'label' => 'مشاهده محصول', 'color' => '#14b8a6', 'color2' => '#99f6e4' ),
								'add_to_cart'   => array( 'label' => 'افزودن به سبد', 'color' => '#10b981', 'color2' => '#6ee7b7' ),
								'order_placed'  => array( 'label' => 'سفارش نهایی', 'color' => '#ec4899', 'color2' => '#f9a8d4' ),
							);

							$day_keys = array_keys( $recent_days );
							$n_days   = count( $day_keys );
							$max_day_val = 10;
							foreach ( $recent_days as $d_row ) {
								foreach ( array_keys( $series_def ) as $sk ) {
									$max_day_val = max( $max_day_val, intval( $d_row[ $sk ] ?? 0 ) );
								}
							}
							// nice max headroom
							$max_day_val = (int) max( 10, ceil( $max_day_val * 1.12 ) );

							$dv_w = 860;
							$dv_h = 320;
							$dv_pl = 52;
							$dv_pr = 20;
							$dv_pt = 24;
							$dv_pb = 44;
							$dv_pw = $dv_w - $dv_pl - $dv_pr;
							$dv_ph = $dv_h - $dv_pt - $dv_pb;

							$series_paths = array();
							$series_areas = array();
							$series_points = array();

							foreach ( $series_def as $sk => $smeta ) {
								$pts = array();
								foreach ( $day_keys as $di => $dk ) {
									$val = intval( $recent_days[ $dk ][ $sk ] ?? 0 );
									$x = $dv_pl + ( $n_days > 1 ? ( $di / ( $n_days - 1 ) ) * $dv_pw : $dv_pw / 2 );
									$y = $dv_pt + $dv_ph - ( max( 0, min( 1, $val / $max_day_val ) ) * $dv_ph );
									$pts[] = array(
										'x'     => round( $x, 2 ),
										'y'     => round( $y, 2 ),
										'val'   => $val,
										'date'  => $dk,
										'label' => $smeta['label'],
										'color' => $smeta['color'],
									);
								}
								$line = '';
								if ( ! empty( $pts ) ) {
									$line = 'M ' . $pts[0]['x'] . ' ' . $pts[0]['y'];
									for ( $i = 1; $i < count( $pts ); $i++ ) {
										$p0 = $pts[ $i - 1 ];
										$p1 = $pts[ $i ];
										$cx = ( $p0['x'] + $p1['x'] ) / 2;
										$line .= ' C ' . $cx . ' ' . $p0['y'] . ', ' . $cx . ' ' . $p1['y'] . ', ' . $p1['x'] . ' ' . $p1['y'];
									}
								}
								$area = '';
								if ( $line && ! empty( $pts ) ) {
									$last = $pts[ count( $pts ) - 1 ];
									$first = $pts[0];
									$base = $dv_pt + $dv_ph;
									$area = $line . ' L ' . $last['x'] . ' ' . $base . ' L ' . $first['x'] . ' ' . $base . ' Z';
								}
								$series_paths[ $sk ]  = $line;
								$series_areas[ $sk ]  = $area;
								$series_points[ $sk ] = $pts;
							}

							$daily_chart_payload = array(
								'days'   => $day_keys,
								'series' => array(),
							);
							foreach ( $series_def as $sk => $smeta ) {
								$vals = array();
								foreach ( $day_keys as $dk ) {
									$vals[] = intval( $recent_days[ $dk ][ $sk ] ?? 0 );
								}
								$daily_chart_payload['series'][] = array(
									'key'   => $sk,
									'label' => $smeta['label'],
									'color' => $smeta['color'],
									'values'=> $vals,
								);
							}
							?>

							<?php if ( $n_days < 1 ) : ?>
							<div style="background:linear-gradient(135deg,#f8fafc,#eef2ff); border:1.5px dashed #c7d2fe; border-radius:16px; padding:36px 20px; text-align:center;">
								<div style="font-size:2rem; margin-bottom:8px;">📭</div>
								<div style="font-weight:900; color:#312e81; font-size:1rem;">هنوز آمار واقعی ثبت نشده است</div>
								<p style="margin:8px auto 0; max-width:420px; color:#64748b; font-size:0.86rem; line-height:1.7;">
									اعداد نمایشی ساختگی حذف شده‌اند. با بازدید کاربران از فروشگاه، مشاهده محصولات و ثبت سفارش، نمودارها به‌صورت خودکار با داده‌های واقعی پر می‌شوند.
								</p>
							</div>
							<?php else : ?>
							<div style="position:relative; background:
								radial-gradient(1200px 200px at 10% -10%, rgba(59,130,246,0.10), transparent 60%),
								radial-gradient(900px 180px at 90% 0%, rgba(236,72,153,0.08), transparent 55%),
								linear-gradient(180deg, #0f172a 0%, #1e293b 40%, #0f172a 100%);
								border-radius:18px; padding:14px 10px 8px; box-shadow:0 18px 40px rgba(15,23,42,0.28), inset 0 1px 0 rgba(255,255,255,0.06);">
								<div style="display:flex; justify-content:space-between; align-items:center; padding:0 12px 8px; flex-wrap:wrap; gap:8px;">
									<div style="color:#e2e8f0; font-size:0.82rem; font-weight:700; opacity:0.9;">
										<span style="display:inline-flex; align-items:center; gap:6px;">
											<span style="width:8px; height:8px; border-radius:50%; background:#22d3ee; box-shadow:0 0 10px #22d3ee;"></span>
											روند ۱۴ روز اخیر — مقیاس نرمال‌شده
										</span>
									</div>
									<div id="scraperDailyTooltip" style="display:none; background:rgba(15,23,42,0.92); color:#f8fafc; border:1px solid rgba(148,163,184,0.35); border-radius:12px; padding:8px 12px; font-size:0.78rem; font-weight:700; box-shadow:0 10px 30px rgba(0,0,0,0.35); min-width:160px; backdrop-filter:blur(8px);"></div>
								</div>

								<svg id="scraperDailyLineChart" viewBox="0 0 <?php echo (int) $dv_w; ?> <?php echo (int) $dv_h; ?>" width="100%" height="300" style="display:block; max-width:100%; cursor:crosshair; font-family:system-ui,Tahoma,sans-serif;" role="img" aria-label="نمودار خطی روند روزانه">
									<defs>
										<?php foreach ( $series_def as $sk => $smeta ) : ?>
											<linearGradient id="area_<?php echo esc_attr( $sk ); ?>" x1="0" y1="0" x2="0" y2="1">
												<stop offset="0%" stop-color="<?php echo esc_attr( $smeta['color'] ); ?>" stop-opacity="0.38"/>
												<stop offset="70%" stop-color="<?php echo esc_attr( $smeta['color'] ); ?>" stop-opacity="0.06"/>
												<stop offset="100%" stop-color="<?php echo esc_attr( $smeta['color'] ); ?>" stop-opacity="0"/>
											</linearGradient>
											<linearGradient id="stroke_<?php echo esc_attr( $sk ); ?>" x1="0" y1="0" x2="1" y2="0">
												<stop offset="0%" stop-color="<?php echo esc_attr( $smeta['color2'] ); ?>"/>
												<stop offset="100%" stop-color="<?php echo esc_attr( $smeta['color'] ); ?>"/>
											</linearGradient>
										<?php endforeach; ?>
										<filter id="dailyLineGlow" x="-30%" y="-30%" width="160%" height="160%">
											<feGaussianBlur stdDeviation="2.8" result="b"/>
											<feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge>
										</filter>
										<clipPath id="dailyPlotClip">
											<rect x="<?php echo $dv_pl; ?>" y="<?php echo $dv_pt; ?>" width="<?php echo $dv_pw; ?>" height="<?php echo $dv_ph; ?>" rx="4"/>
										</clipPath>
									</defs>

									<!-- dark grid -->
									<?php for ( $gi = 0; $gi <= 5; $gi++ ) :
										$gy = $dv_pt + ( $gi / 5 ) * $dv_ph;
										$gval = (int) round( $max_day_val * ( 1 - $gi / 5 ) );
										?>
										<line x1="<?php echo $dv_pl; ?>" y1="<?php echo $gy; ?>" x2="<?php echo $dv_w - $dv_pr; ?>" y2="<?php echo $gy; ?>" stroke="rgba(148,163,184,0.15)" stroke-width="1" stroke-dasharray="3 7"/>
										<text x="<?php echo $dv_pl - 10; ?>" y="<?php echo $gy + 3; ?>" text-anchor="end" fill="rgba(226,232,240,0.55)" font-size="10" font-weight="700"><?php echo esc_html( self::to_fa_num( number_format( $gval ) ) ); ?></text>
									<?php endfor; ?>

									<!-- vertical day guides -->
									<?php foreach ( $day_keys as $di => $dk ) :
										$x = $dv_pl + ( $n_days > 1 ? ( $di / ( $n_days - 1 ) ) * $dv_pw : $dv_pw / 2 );
										$show_lbl = ( 0 === $di % max( 1, (int) ceil( $n_days / 7 ) ) ) || $di === $n_days - 1;
										?>
										<line x1="<?php echo $x; ?>" y1="<?php echo $dv_pt; ?>" x2="<?php echo $x; ?>" y2="<?php echo $dv_pt + $dv_ph; ?>" stroke="rgba(148,163,184,0.08)" stroke-width="1"/>
										<?php if ( $show_lbl ) : ?>
											<text x="<?php echo $x; ?>" y="<?php echo $dv_h - 14; ?>" text-anchor="middle" fill="rgba(226,232,240,0.65)" font-size="10" font-weight="700" direction="ltr"><?php echo esc_html( substr( $dk, 5 ) ); ?></text>
										<?php endif; ?>
									<?php endforeach; ?>

									<g clip-path="url(#dailyPlotClip)">
										<?php
										// draw areas first (under), then lines
										foreach ( array_reverse( $series_def, true ) as $sk => $smeta ) :
											if ( empty( $series_areas[ $sk ] ) ) {
												continue;
											}
											?>
											<path d="<?php echo esc_attr( $series_areas[ $sk ] ); ?>" fill="url(#area_<?php echo esc_attr( $sk ); ?>)"/>
										<?php endforeach; ?>

										<?php foreach ( $series_def as $sk => $smeta ) :
											if ( empty( $series_paths[ $sk ] ) ) {
												continue;
											}
											?>
											<path class="scraper-daily-line" data-series="<?php echo esc_attr( $sk ); ?>" d="<?php echo esc_attr( $series_paths[ $sk ] ); ?>" fill="none" stroke="url(#stroke_<?php echo esc_attr( $sk ); ?>)" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round" filter="url(#dailyLineGlow)" style="transition:stroke-opacity .2s;"/>
										<?php endforeach; ?>
									</g>

									<!-- interactive dots -->
									<?php foreach ( $series_def as $sk => $smeta ) :
										foreach ( $series_points[ $sk ] as $pt ) : ?>
											<circle
												class="scraper-daily-dot"
												data-series="<?php echo esc_attr( $sk ); ?>"
												data-date="<?php echo esc_attr( $pt['date'] ); ?>"
												data-val="<?php echo esc_attr( $pt['val'] ); ?>"
												data-label="<?php echo esc_attr( $pt['label'] ); ?>"
												data-color="<?php echo esc_attr( $pt['color'] ); ?>"
												cx="<?php echo $pt['x']; ?>"
												cy="<?php echo $pt['y']; ?>"
												r="4.2"
												fill="#0f172a"
												stroke="<?php echo esc_attr( $smeta['color'] ); ?>"
												stroke-width="2.4"
												style="cursor:pointer; transition: r .15s;"
											/>
										<?php endforeach;
									endforeach; ?>

									<!-- hover crosshair -->
									<line id="scraperDailyCrosshair" x1="0" y1="<?php echo $dv_pt; ?>" x2="0" y2="<?php echo $dv_pt + $dv_ph; ?>" stroke="rgba(255,255,255,0.35)" stroke-width="1.2" stroke-dasharray="4 4" opacity="0"/>
								</svg>
							</div>
							<?php endif; ?>

							<script type="application/json" id="scraperDailyChartData"><?php echo wp_json_encode( $daily_chart_payload, JSON_UNESCAPED_UNICODE ); ?></script>
							<script>
							(function(){
								var svg = document.getElementById('scraperDailyLineChart');
								var tip = document.getElementById('scraperDailyTooltip');
								var legend = document.getElementById('scraperDailyLegend');
								var cross = document.getElementById('scraperDailyCrosshair');
								var dataEl = document.getElementById('scraperDailyChartData');
								if (!svg || !dataEl) return;
								var payload = {};
								try { payload = JSON.parse(dataEl.textContent || '{}'); } catch(e) { payload = {}; }

								function toFa(n){
									return String(n).replace(/\d/g, function(d){ return '۰۱۲۳۴۵۶۷۸۹'[d]; });
								}

								// Legend with toggle
								var active = {};
								(payload.series || []).forEach(function(s){ active[s.key] = true; });
								function renderLegend(){
									if (!legend) return;
									legend.innerHTML = '';
									(payload.series || []).forEach(function(s){
										var btn = document.createElement('button');
										btn.type = 'button';
										btn.style.cssText = 'display:inline-flex;align-items:center;gap:6px;border-radius:999px;padding:4px 10px;border:1.5px solid '+(active[s.key]?s.color:'#cbd5e1')+';background:'+(active[s.key]?'#fff':'#f8fafc')+';color:'+(active[s.key]?'#0f172a':'#94a3b8')+';font-weight:800;font-size:0.75rem;cursor:pointer;opacity:'+(active[s.key]?'1':'0.55');
										btn.innerHTML = '<span style="width:10px;height:10px;border-radius:50%;background:'+s.color+';box-shadow:0 0 0 3px '+s.color+'22"></span>' + s.label;
										btn.addEventListener('click', function(){
											active[s.key] = !active[s.key];
											applyVisibility();
											renderLegend();
										});
										legend.appendChild(btn);
									});
								}
								function applyVisibility(){
									svg.querySelectorAll('.scraper-daily-line, .scraper-daily-dot').forEach(function(el){
										var k = el.getAttribute('data-series');
										el.style.opacity = active[k] ? '1' : '0.08';
										el.style.pointerEvents = active[k] ? 'auto' : 'none';
									});
								}
								renderLegend();
								applyVisibility();

								function showTip(html){
									if (!tip) return;
									tip.style.display = 'block';
									tip.innerHTML = html;
								}
								function hideTip(){
									if (!tip) return;
									tip.style.display = 'none';
									if (cross) cross.setAttribute('opacity', '0');
									svg.querySelectorAll('.scraper-daily-dot').forEach(function(d){ d.setAttribute('r', '4.2'); });
								}

								svg.querySelectorAll('.scraper-daily-dot').forEach(function(dot){
									dot.addEventListener('mouseenter', function(){
										var date = dot.getAttribute('data-date');
										var rows = [];
										svg.querySelectorAll('.scraper-daily-dot[data-date="'+date+'"]').forEach(function(d){
											var k = d.getAttribute('data-series');
											if (!active[k]) return;
											d.setAttribute('r', '6.5');
											rows.push('<div style="display:flex;justify-content:space-between;gap:14px;margin:2px 0"><span style="color:'+d.getAttribute('data-color')+'">● '+d.getAttribute('data-label')+'</span><strong>'+toFa(d.getAttribute('data-val'))+'</strong></div>');
										});
										var cx = parseFloat(dot.getAttribute('cx')) || 0;
										if (cross) {
											cross.setAttribute('x1', cx);
											cross.setAttribute('x2', cx);
											cross.setAttribute('opacity', '1');
										}
										showTip('<div style="opacity:.7;margin-bottom:4px;direction:ltr;text-align:left">📅 '+date+'</div>' + rows.join(''));
									});
									dot.addEventListener('mouseleave', hideTip);
								});
							})();
							</script>
						</div>

						<!-- 4. Messenger Notification Toggles for Funnel Events -->
						<div class="admin-card" style="border:2px solid #3b82f6; background:linear-gradient(180deg, #f0fdf4 0%, #ffffff 100%);">
							<div class="admin-card-header" style="border-bottom:1px solid #bfdbfe;">
								<h3><span>🔔</span> تنظیمات اعلان‌های لحظه‌ای پیام‌رسان (بله، تلگرام، روبیکا)</h3>
								<span class="field-badge field-badge-green">اعلان خودکار به ادمین</span>
							</div>

							<p style="color:#334155; font-size:0.88rem; line-height:1.7; margin-top:0;">
								با فعال‌سازی هر یک از گزینه‌های زیر، به محض وقوع رویداد توسط مشتری در ویترین فروشگاه، یک پیام فوری همراه با جزئیات کامل به پیام‌رسان‌های متصل شما (بله، تلگرام یا روبیکا) ارسال خواهد شد:
							</p>

							<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px, 1fr)); gap:14px; margin-bottom:14px;">
								<!-- 1. Site Visit -->
								<label style="background:#ffffff; border:1.5px solid #cbd5e1; border-radius:12px; padding:14px; cursor:pointer; display:flex; align-items:flex-start; gap:10px;">
									<input type="checkbox" name="notif_event_site_visit" value="1" <?php checked( ! empty( $opts['notif_event_site_visit'] ) ); ?> style="width:18px; height:18px; accent-color:#2563eb; margin-top:2px;">
									<div>
										<strong style="font-size:0.92rem; color:#0f172a; display:block;">🌐 اعلان بازدید از سایت</strong>
										<span style="font-size:0.78rem; color:#64748b;">ارسال پیام هنگام ورود بازدیدکننده جدید به ویترین</span>
									</div>
								</label>

								<!-- 2. Product View -->
								<label style="background:#ffffff; border:1.5px solid #cbd5e1; border-radius:12px; padding:14px; cursor:pointer; display:flex; align-items:flex-start; gap:10px;">
									<input type="checkbox" name="notif_event_product_view" value="1" <?php checked( ! empty( $opts['notif_event_product_view'] ) ); ?> style="width:18px; height:18px; accent-color:#2563eb; margin-top:2px;">
									<div>
										<strong style="font-size:0.92rem; color:#0f172a; display:block;">👁️ اعلان مشاهده محصول</strong>
										<span style="font-size:0.78rem; color:#64748b;">ارسال پیام با نام و قیمت محصول هنگام کلیک و مشاهده کالا</span>
									</div>
								</label>

								<!-- 3. Add to Cart -->
								<label style="background:#ffffff; border:1.5px solid #10b981; border-radius:12px; padding:14px; cursor:pointer; display:flex; align-items:flex-start; gap:10px;">
									<input type="checkbox" name="notif_event_add_to_cart" value="1" <?php checked( ! empty( $opts['notif_event_add_to_cart'] ) ); ?> style="width:18px; height:18px; accent-color:#10b981; margin-top:2px;">
									<div>
										<strong style="font-size:0.92rem; color:#065f46; display:block;">🛒 اعلان افزودن به سبد خرید (توصیه شده)</strong>
										<span style="font-size:0.78rem; color:#64748b;">ارسال نام کالا، تعداد و قیمت به محض افزودن به سبد توسط مشتری</span>
									</div>
								</label>

								<!-- 4. Checkout Step -->
								<label style="background:#ffffff; border:1.5px solid #f59e0b; border-radius:12px; padding:14px; cursor:pointer; display:flex; align-items:flex-start; gap:10px;">
									<input type="checkbox" name="notif_event_checkout_step" value="1" <?php checked( ! empty( $opts['notif_event_checkout_step'] ) ); ?> style="width:18px; height:18px; accent-color:#f59e0b; margin-top:2px;">
									<div>
										<strong style="font-size:0.92rem; color:#92400e; display:block;">💳 اعلان ورود به تسویه‌حساب (مهم)</strong>
										<span style="font-size:0.78rem; color:#64748b;">ارسال مبلغ کل سبد خرید و تعداد اقلام هنگام مراجعه به برگه پرداخت</span>
									</div>
								</label>

								<!-- 5. Order Placed -->
								<label style="background:#ffffff; border:1.5px solid #ec4899; border-radius:12px; padding:14px; cursor:pointer; display:flex; align-items:flex-start; gap:10px;">
									<input type="checkbox" name="notif_event_order_placed" value="1" <?php checked( ! empty( $opts['notif_event_order_placed'] ) ); ?> style="width:18px; height:18px; accent-color:#ec4899; margin-top:2px;">
									<div>
										<strong style="font-size:0.92rem; color:#9d174d; display:block;">🎉 اعلان ثبت سفارش جدید (بسیار مهم)</strong>
										<span style="font-size:0.78rem; color:#64748b;">ارسال شماره فاکتور، مبلغ، نام مشتری و شماره موبایل برای آماده‌سازی سریع</span>
									</div>
								</label>
							</div>

							<div style="background:#ffffff; border:1px solid #bfdbfe; border-radius:10px; padding:10px 14px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
								<span style="font-size:0.84rem; color:#475569;">
									📡 پیام‌رسان‌های فعال برای دریافت اعلان: 
									<strong><?php echo implode( ' ، ', array_map( function($k){ return $k === 'bale' ? 'بله' : ( $k === 'telegram' ? 'تلگرام' : 'روبیکا' ); }, array_keys($active_msgrs) ) ) ?: 'هیچ پیام‌رسانی تنظیم نشده (از تب پیام‌رسان‌ها متصل کنید)'; ?></strong>
								</span>
								<button type="submit" name="scraper_shop_save" class="button button-primary" style="background:#2563eb; border-color:#1d4ed8; font-weight:800; border-radius:8px;">
									💾 ذخیره تنظیمات اعلان‌ها
								</button>
							</div>
						</div>

						<!-- 5. Live Events Log & Top Products -->
						<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(320px, 1fr)); gap:20px;">
							<!-- Live Recent Events Table -->
							<div class="admin-card" style="margin-bottom:0;">
								<div class="admin-card-header">
									<h3><span>⚡</span> آخرین رویدادهای زنده کاربران</h3>
									<button type="button" id="btnResetAnalytics" class="button button-small" style="color:#ef4444; border-color:#fca5a5;">
										🗑️ بازنشانی آمار
									</button>
								</div>

								<div style="max-height:360px; overflow-y:auto;">
									<table class="wp-list-table widefat fixed striped" style="font-size:0.82rem;">
										<thead>
											<tr>
												<th style="width:85px;">زمان</th>
												<th style="width:110px;">نوع رویداد</th>
												<th>شرح رویداد / کالا</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$recent_events = $analytics_data['recent_events'] ?? array();
											if ( empty( $recent_events ) ) : ?>
												<tr><td colspan="3" style="text-align:center; color:#94a3b8;">هنوز رویدادی ثبت نشده است.</td></tr>
											<?php else :
												foreach ( array_slice( $recent_events, 0, 15 ) as $ev ) :
													$ev_type = $ev['type'] ?? '';
													$badge_color = '#2563eb';
													$badge_lbl   = 'بازدید سایت';
													if ( 'product_view' === $ev_type ) { $badge_color = '#0d9488'; $badge_lbl = 'مشاهده کالا'; }
													elseif ( 'add_to_cart' === $ev_type ) { $badge_color = '#059669'; $badge_lbl = 'افزودن به سبد'; }
													elseif ( 'checkout_step' === $ev_type ) { $badge_color = '#d97706'; $badge_lbl = 'تسویه‌حساب'; }
													elseif ( 'order_placed' === $ev_type ) { $badge_color = '#db2777'; $badge_lbl = 'ثبت سفارش'; }
													?>
													<tr>
														<td style="color:#64748b; font-size:0.75rem;"><?php echo esc_html( $ev['time'] ?? '' ); ?></td>
														<td>
															<span style="background:<?php echo $badge_color; ?>18; color:<?php echo $badge_color; ?>; font-weight:800; padding:2px 8px; border-radius:6px; font-size:0.72rem;">
																<?php echo esc_html( $badge_lbl ); ?>
															</span>
														</td>
														<td>
															<strong><?php echo esc_html( $ev['title'] ?? '' ); ?></strong>
															<?php if ( ! empty( $ev['customer'] ) && 'کاربر مهمان' !== $ev['customer'] ) : ?>
																<div style="font-size:0.72rem; color:#64748b;"><?php echo esc_html( $ev['customer'] ); ?></div>
															<?php endif; ?>
														</td>
													</tr>
												<?php endforeach;
											endif; ?>
										</tbody>
									</table>
								</div>
							</div>

							<!-- Top Products Table -->
							<div class="admin-card" style="margin-bottom:0;">
								<div class="admin-card-header">
									<h3><span>🔥</span> محبوب‌ترین کالاهای مورد توجه مشتریان</h3>
									<span class="field-badge field-badge-purple">تحلیل تقاضا</span>
								</div>

								<div style="max-height:360px; overflow-y:auto;">
									<table class="wp-list-table widefat fixed striped" style="font-size:0.82rem;">
										<thead>
											<tr>
												<th>نام کالا</th>
												<th style="width:75px;">مشاهده</th>
												<th style="width:75px;">سبد خرید</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$top_prods = $analytics_data['top_products'] ?? array();
											if ( empty( $top_prods ) ) : ?>
												<tr><td colspan="3" style="text-align:center; color:#94a3b8;">اطلاعاتی ثبت نشده است.</td></tr>
											<?php else :
												foreach ( array_slice( $top_prods, 0, 8 ) as $tp ) : ?>
													<tr>
														<td><strong><?php echo esc_html( $tp['title'] ?? '' ); ?></strong></td>
														<td><span style="color:#0d9488; font-weight:800;"><?php echo self::to_fa_num( $tp['views'] ?? 0 ); ?></span></td>
														<td><span style="color:#059669; font-weight:800;"><?php echo self::to_fa_num( $tp['carts'] ?? 0 ); ?></span></td>
													</tr>
												<?php endforeach;
											endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<!-- ================= SUB-TAB 2: PRODUCTS & WOOCOMMERCE SYNC ================= -->
					<div id="subtab-products" class="shop-subtab-panel" style="display:none;">
						<div class="admin-card">
							<div class="admin-card-header">
								<h3><span>🔄</span> وضعیت اسکرپر، پروفایل‌ها و درج در ووکامرس</h3>
								<span class="field-badge field-badge-green">مجموع: <?php echo self::to_fa_num( count( $scraped_products ) ); ?> کالا</span>
							</div>

							<div style="margin-bottom:20px;">
								<h4 style="margin:0 0 10px; font-size:1rem; font-weight:800; color:#1e293b;">پروفایل‌های فعال استخراج:</h4>
								<?php if ( empty( $profiles_summary ) ) : ?>
									<p style="color:#64748b;">هنوز پروفایلی در <code>profiles.json</code> ایجاد نشده است.</p>
								<?php else : ?>
									<table class="wp-list-table widefat fixed striped" style="border-radius:10px; overflow:hidden;">
										<thead>
											<tr>
												<th>نام پروفایل</th>
												<th>آدرس منبع</th>
												<th style="width:140px;">تعداد کالا</th>
												<th style="width:140px;">عملیات</th>
											</tr>
										</thead>
										<tbody>
											<?php foreach ( $profiles_summary as $prof ) : ?>
												<tr>
													<td><strong><?php echo esc_html( $prof['name'] ); ?></strong></td>
													<td dir="ltr" style="text-align:right;"><code><?php echo esc_html( $prof['url'] ); ?></code></td>
													<td><strong style="color:#059669;"><?php echo self::to_fa_num( $prof['count'] ); ?></strong> کالا</td>
													<td><a href="<?php echo esc_url( $scraper_embed_url ); ?>" class="button button-small">مدیریت در اسکرپر</a></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								<?php endif; ?>
							</div>

							<!-- Direct Sync to WooCommerce Action -->
						<div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:18px 20px; margin-bottom:24px;">
							<h4 style="margin:0 0 8px; font-size:1rem; font-weight:800; color:#0f172a;">درج مستقیم در دیتابیس محصولات ووکامرس</h4>
							<p style="color:#64748b; font-size:0.88rem; margin:0 0 14px;">
								تمامی محصولات استخراج‌شده را با قیمت‌های تعدیل‌شده به صورت کالای رسمی ووکامرس در دیتابیس وردپرس درج می‌کند.
							</p>
							<div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
								<button type="button" id="btnSyncToWoo" class="button button-secondary" style="font-weight:800; border-color:#2563eb; color:#2563eb; padding:6px 20px;">
									همگام‌سازی و درج در دیتابیس ووکامرس
								</button>
								<span id="syncWooStatus" style="font-size:0.88rem; font-weight:700;"></span>
							</div>
							<!-- v13.2 / v10.103: صف کارت‌محصول همگام مستقیم -->
							<div id="amphpDirectSyncQueue" style="display:none; margin-top:16px; border:1px solid #cbd5e1; border-radius:14px; overflow:hidden; background:#0f172a;">
								<div style="display:flex; justify-content:space-between; align-items:center; gap:10px; padding:12px 14px; background:#1e293b; border-bottom:1px solid #334155; flex-wrap:wrap;">
									<div style="color:#e2e8f0; font-weight:900; font-size:0.95rem;">📦 صف همگام‌سازی مستقیم — هر کارت یک محصول</div>
									<div id="amphpDirectSyncSum" style="color:#94a3b8; font-size:0.82rem; font-weight:700;"></div>
								</div>
								<div style="height:6px; background:#1e293b;"><div id="amphpDirectSyncBar" style="height:100%; width:0%; background:linear-gradient(90deg,#2563eb,#22d3ee); transition:width .35s;"></div></div>
								<div id="amphpDirectSyncCards" style="max-height:420px; overflow:auto; padding:10px 12px; display:flex; flex-direction:column; gap:8px;"></div>
							</div>
						</div>

						<!-- v10.99: پل اتصال مستقیم ووکامرس از پنل ادمین -->
						<div id="amphpWooBridgeCard" style="background:linear-gradient(135deg,#eff6ff 0%,#f0fdf4 100%); border:2px solid #3b82f6; border-radius:14px; padding:20px 22px; margin-bottom:24px;">
							<div style="display:flex; justify-content:space-between; align-items:flex-start; flex-wrap:wrap; gap:12px; margin-bottom:12px;">
								<div>
									<h4 style="margin:0 0 6px; font-size:1.05rem; font-weight:900; color:#1e3a8a;">🔌 اتصال مستقیم ووکامرس (از داخل وردپرس)</h4>
									<p style="margin:0; color:#334155; font-size:0.88rem; line-height:1.7; max-width:640px;">
										اسکرپر خارج از وردپرس نمی‌تواند «direct» را اجرا کند. از این بخش اتصال in-process را <strong>تست</strong> و در <code>connections.json</code> با <code>sync_mode=direct</code> <strong>فعال</strong> کنید.
									</p>
								</div>
								<span class="field-badge <?php echo $woo_direct_ready ? 'field-badge-green' : 'field-badge-blue'; ?>" id="amphpWooBridgeBadge">
									<?php echo $woo_direct_ready ? 'آماده · direct' : ( $woo_has_wc ? 'WC هست · نیاز به فعال‌سازی' : 'ووکامرس نیست' ); ?>
								</span>
							</div>
							<div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:10px; margin-bottom:14px; font-size:0.84rem;">
								<div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px;">
									<div style="color:#64748b; font-weight:700;">WooCommerce</div>
									<div style="font-weight:900; color:<?php echo $woo_has_wc ? '#059669' : '#dc2626'; ?>;">
										<?php echo $woo_has_wc ? ( 'فعال' . ( defined( 'WC_VERSION' ) ? ' · ' . esc_html( WC_VERSION ) : '' ) ) : 'غیرفعال / نصب‌نشده'; ?>
									</div>
								</div>
								<div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px;">
									<div style="color:#64748b; font-weight:700;">sync_mode</div>
									<div style="font-weight:900; color:#0f172a;" id="amphpWooSyncModeLabel"><?php echo esc_html( $woo_sync_mode !== '' ? $woo_sync_mode : 'api' ); ?></div>
								</div>
								<div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px;">
									<div style="color:#64748b; font-weight:700;">enabled</div>
									<div style="font-weight:900; color:<?php echo $woo_enabled ? '#059669' : '#b45309'; ?>;" id="amphpWooEnabledLabel"><?php echo $woo_enabled ? 'بله' : 'خیر'; ?></div>
								</div>
								<div style="background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:10px 12px;">
									<div style="color:#64748b; font-weight:700;">store_url</div>
									<div style="font-weight:800; color:#0f172a; direction:ltr; text-align:right; word-break:break-all;" id="amphpWooStoreLabel"><?php echo esc_html( $woo_store !== '' ? $woo_store : '—' ); ?></div>
								</div>
							</div>
							<div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-bottom:10px;">
								<label style="font-size:0.85rem; font-weight:700; color:#334155;">fallback:
									<select id="amphpWooFallback" style="margin-right:6px; font-weight:700;">
										<option value="direct_then_api" selected>direct_then_api</option>
										<option value="api_then_direct">api_then_direct</option>
										<option value="none">none</option>
									</select>
								</label>
								<button type="button" id="btnTestWooDirect" class="button button-secondary" style="font-weight:800; border-color:#059669; color:#059669;">
									🧪 تست اتصال مستقیم
								</button>
								<button type="button" id="btnEnableWooDirect" class="button button-primary" style="font-weight:800; background:#2563eb; border:none;">
									⚡ فعال‌سازی اتصال مستقیم
								</button>
								<span id="amphpWooBridgeStatus" style="font-size:0.88rem; font-weight:700;"></span>
							</div>
							<pre id="amphpWooBridgeReport" style="display:none; margin:8px 0 0; max-height:280px; overflow:auto; background:#0f172a; color:#e2e8f0; border-radius:10px; padding:12px 14px; font-size:0.78rem; line-height:1.55; direction:ltr; text-align:left; white-space:pre-wrap; word-break:break-word;"></pre>
						</div>
						</div>
					</div>
				</div>

				<!-- ================= TAB 7: CHAT LOGS & HISTORY ================= -->
				<div id="tab-logs" class="scraper-tab-panel">
					<div class="admin-card">
						<div class="admin-card-header">
							<h3><span>📋</span> گزارش و تاریخچه آخرین پیام‌های دریافتی از مشتریان</h3>
							<span class="field-badge field-badge-blue"><?php echo count( $chat_logs ); ?> پیام ثبت‌شده</span>
						</div>

						<?php if ( empty( $chat_logs ) ) : ?>
							<p style="color:#94a3b8; text-align:center; padding:30px 10px;">هنوز پیامی از طرف مشتریان در چت آنلاین ثبت نشده است.</p>
						<?php else : ?>
							<table class="wp-list-table widefat fixed striped" style="border-radius:10px; overflow:hidden;">
								<thead>
									<tr>
										<th style="width:120px; font-weight:800;">زمان</th>
										<th style="width:130px; font-weight:800;">نام مشتری</th>
										<th style="width:120px; font-weight:800;">تماس</th>
										<th style="width:140px; font-weight:800;">ایمیل / موضوع</th>
										<th style="font-weight:800;">متن پیام مشتری</th>
										<th style="font-weight:800;">پاسخ هوش مصنوعی</th>
										<th style="width:120px; font-weight:800;">وضعیت</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( array_slice( $chat_logs, 0, 20 ) as $log ) : ?>
										<tr>
											<td style="font-size:0.8rem; color:#64748b;"><?php echo esc_html( $log['time'] ?? '—' ); ?></td>
											<td><strong><?php echo esc_html( $log['name'] ?? '—' ); ?></strong></td>
											<td dir="ltr" style="text-align:right;">
												<?php if ( ! empty( $log['phone'] ) ) : ?>
													<a href="tel:<?php echo esc_attr( $log['phone'] ); ?>" style="font-weight:700; color:#2563eb;"><?php echo esc_html( $log['phone'] ); ?></a>
												<?php else : ?>
													<span style="color:#94a3b8;">—</span>
												<?php endif; ?>
											</td>
											<td style="font-size:0.82rem;">
												<?php if ( ! empty( $log['email'] ) ) : ?>
													<div>📧 <?php echo esc_html( $log['email'] ); ?></div>
												<?php endif; ?>
												<?php if ( ! empty( $log['subject'] ) ) : ?>
													<div>📌 <?php echo esc_html( $log['subject'] ); ?></div>
												<?php endif; ?>
											</td>
											<td style="font-size:0.88rem; line-height:1.5;"><?php echo esc_html( $log['message'] ?? '—' ); ?></td>
											<td style="font-size:0.82rem; color:#7c3aed; line-height:1.4;">
												<?php echo ! empty( $log['ai_reply'] ) ? '🤖 ' . esc_html( mb_substr( $log['ai_reply'], 0, 90 ) ) . '...' : '<span style="color:#94a3b8;">بدون هوش مصنوعی</span>'; ?>
											</td>
											<td>
												<?php if ( ! empty( $log['sent_ok'] ) ) : ?>
													<span style="color:#16a34a; font-weight:800; font-size:0.8rem;">✅ ارسال شد</span>
												<?php else : ?>
													<span style="color:#d97706; font-weight:800; font-size:0.8rem;">⚠️ ثبت در سیستم</span>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php endif; ?>
					</div>
				</div>

				<!-- Compact Responsive Save Settings Bar -->
				<div class="scraper-save-bar" style="background:#ffffff; border:1px solid #cbd5e1; border-radius:12px; padding:14px 20px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-top:24px; box-shadow:0 4px 14px rgba(0,0,0,0.03); max-width:100%; box-sizing:border-box;">
					<div style="font-size:0.88rem; color:#475569; font-weight:700;">
						💡 کلیه تغییرات در هر یک از ۷ زبانه با زدن کلید زیر ذخیره می‌شوند.
					</div>
					<button type="submit" name="scraper_shop_save" class="button button-primary scraper-save-btn" style="background:#2563eb; border-color:#2563eb; font-weight:800; padding:8px 24px; font-size:0.95rem; border-radius:8px; cursor:pointer;">
						💾 ذخیره تغییرات
					</button>
				</div>
			</form>
		</div>

		<script>
		jQuery(document).ready(function($){
			// Tab switching logic
			$('#mobileTabSelector').on('change', function(){
				var targetTab = $(this).val();
				$('#scraperAdminTabs .scraper-tab-link[data-tab="' + targetTab + '"]').click();
			});

			
			// Sub-tabs switcher in Tab 6 (فروشگاه)
			$('.shop-subtab-btn').on('click', function() {
				var target = $(this).attr('data-subtab');
				$('.shop-subtab-btn').css({'background':'#f8fafc','color':'#475569','border-color':'#cbd5e1','box-shadow':'none'});
				$(this).css({'background':'#2563eb','color':'#fff','border-color':'#2563eb','box-shadow':'0 4px 12px rgba(37,99,235,0.2)'});
				$('.shop-subtab-panel').hide();
				$('#' + target).fadeIn(200);
				$('#activeShopSubtabInput').val(target);
				try { sessionStorage.setItem('scraper_active_shop_subtab', target); } catch(e){}
			});

			// Restore active subtab on load
			try {
				var storedShopSubtab = sessionStorage.getItem('scraper_active_shop_subtab') || $('#activeShopSubtabInput').val();
				if (storedShopSubtab && $('.shop-subtab-btn[data-subtab="' + storedShopSubtab + '"]').length) {
					$('.shop-subtab-btn[data-subtab="' + storedShopSubtab + '"]').trigger('click');
				}
			} catch(e){}

			// Live Font Preview Updater in Tab 1
			function updateTitleFontPreview() {
				var title = $('#shopTitleInput').val() || 'عنوان فروشگاه';
				var font = $('#shopTitleFontSelect').val() || 'vazirmatn';
				var size = $('#shopTitleSizeSelect').val() || 'normal';
				var weight = $('#shopTitleWeightSelect').val() || '900';

				if (font === 'custom') {
					$('#customFontRow').show();
				} else {
					$('#customFontRow').hide();
				}

				var fontMap = {
					'vazirmatn': "'Vazirmatn', Tahoma, sans-serif",
					'iranyekan': "'IRANYekan', 'IRANYekanX', 'Vazirmatn', Tahoma, sans-serif",
					'dana': "'Dana', 'DanaVF', 'Vazirmatn', Tahoma, sans-serif",
					'yekanbakh': "'YekanBakh', 'Yekan Bakh', 'Vazirmatn', Tahoma, sans-serif",
					'shabnam': "'Shabnam', 'Vazirmatn', Tahoma, sans-serif",
					'sahel': "'Sahel', 'Vazirmatn', Tahoma, sans-serif",
					'iransans': "'IRANSans', 'IRANSansX', 'Vazirmatn', Tahoma, sans-serif",
					'morabba': "'Morabba', 'Dana', Tahoma, sans-serif",
					'parastoo': "'Parastoo', 'Sahel', Tahoma, sans-serif",
					'system': "-apple-system, BlinkMacSystemFont, 'Segoe UI', Tahoma, sans-serif",
					'custom': ($('#customFontInput').val() || 'Tahoma') + ", sans-serif"
				};

				var sizeMap = {
					'small': '1.18rem',
					'normal': '1.38rem',
					'large': '1.65rem',
					'xlarge': '1.95rem'
				};

				$('#previewTitleText').text(title)
					.css({
						'font-family': fontMap[font] || fontMap['vazirmatn'],
						'font-size': sizeMap[size] || '1.38rem',
						'font-weight': weight
					});
			}

			$('#shopTitleInput, #shopTitleFontSelect, #shopTitleSizeSelect, #shopTitleWeightSelect, #customFontInput').on('input change', updateTitleFontPreview);
			updateTitleFontPreview();

			// Reset Analytics AJAX
			$('#btnResetAnalytics').on('click', function() {
				if (!confirm('آیا از بازنشانی کامل آمار و نمودارهای فروشگاه اطمینان دارید؟')) return;
				var $btn = $(this);
				$btn.prop('disabled', true).text('در حال بازنشانی...');
				$.post(ajaxurl, {
					action: 'scraper_reset_analytics',
					nonce: '<?php echo esc_js( wp_create_nonce( "scraper_shop_admin_nonce" ) ); ?>'
				}, function(res) {
					if (res.success) {
						alert('✅ ' + (res.data.message || 'آمار با موفقیت بازنشانی شد. صفحه تازه‌سازی می‌شود.'));
						window.location.reload();
					} else {
						alert('❌ خطا در بازنشانی آمار: ' + (res.data || 'خطای ناشناخته'));
						$btn.prop('disabled', false).text('🗑️ بازنشانی آمار');
					}
				}).fail(function() {
					alert('❌ خطای ارتباط با سرور.');
					$btn.prop('disabled', false).text('🗑️ بازنشانی آمار');
				});
			});

$('#scraperAdminTabs .scraper-tab-link').on('click', function(e){
				e.preventDefault();
				var tabId = $(this).attr('data-tab');
				$('#scraperAdminTabs .scraper-tab-link').removeClass('active');
				$(this).addClass('active');
				$('.scraper-tab-panel').removeClass('active');
				$('#' + tabId).addClass('active');

				$('#mobileTabSelector').val(tabId);
				try {
					sessionStorage.setItem('scraper_active_tab', tabId);
				} catch(err){}
			});

			// Restore tab from session
			try {
				var savedTab = sessionStorage.getItem('scraper_active_tab');
				if (savedTab && $('#' + savedTab).length) {
					$('#scraperAdminTabs .scraper-tab-link[data-tab="' + savedTab + '"]').click();
				}
			} catch(err){}

			// ================= CARD DROPDOWN THEME SELECTOR =================
			var themesCatalog = {
				'royal-blue': {
					name: '۱. آبی رویال و کریستالی',
					title: 'آبی رویال و کریستالی',
					badge: 'پیش‌فرض رسمی',
					hdr_bg: 'linear-gradient(135deg, #1e3a8a, #2563eb)',
					body_bg: '#f8fafc',
					user_bg: 'linear-gradient(135deg, #2563eb, #1d4ed8)',
					user_c: '#ffffff',
					ai_bg: '#ffffff',
					ai_b: '#e2e8f0',
					ai_c: '#0f172a',
					adm_bg: '#ecfdf5',
					adm_b: '#a7f3d0',
					adm_c: '#065f46',
					dots: ['#1e3a8a', '#2563eb', '#60a5fa']
				},
				'cyberpunk-dark': {
					name: '۲. دارک نئونی و بنفش',
					title: 'دارک نئونی و بنفش سایبرپانک',
					badge: 'OLED Dark Mode',
					hdr_bg: 'linear-gradient(135deg, #090514, #2e1065)',
					body_bg: '#0f172a',
					user_bg: 'linear-gradient(135deg, #7c3aed, #a855f7)',
					user_c: '#ffffff',
					ai_bg: '#1e293b',
					ai_b: '#475569',
					ai_c: '#f1f5f9',
					adm_bg: '#064e3b',
					adm_b: '#10b981',
					adm_c: '#a7f3d0',
					dots: ['#090514', '#7c3aed', '#a855f7']
				},
				'emerald-whatsapp': {
					name: '۳. سبز زمردی و واتساپی',
					title: 'سبز زمردی و واتساپی',
					badge: 'پیام‌رسان محبوب',
					hdr_bg: 'linear-gradient(135deg, #064e3b, #059669)',
					body_bg: '#efeae2',
					user_bg: '#d9fdd3',
					user_c: '#111827',
					ai_bg: '#ffffff',
					ai_b: '#e5e7eb',
					ai_c: '#111827',
					adm_bg: '#e0f2fe',
					adm_b: '#bae6fd',
					adm_c: '#075985',
					dots: ['#064e3b', '#059669', '#25D366']
				},
				'magenta-rose': {
					name: '۴. صورتی نئونی و سرخابی',
					title: 'صورتی نئونی و سرخابی لوکس',
					badge: 'بیوتی و فشن',
					hdr_bg: 'linear-gradient(135deg, #831843, #db2777)',
					body_bg: '#fff1f2',
					user_bg: 'linear-gradient(135deg, #db2777, #f43f5e)',
					user_c: '#ffffff',
					ai_bg: '#ffffff',
					ai_b: '#fecdd3',
					ai_c: '#881337',
					adm_bg: '#f0fdf4',
					adm_b: '#bbf7d0',
					adm_c: '#166534',
					dots: ['#831843', '#db2777', '#fb7185']
				},
				'gold-vip': {
					name: '۵. مشکی طلایی VIP',
					title: 'مشکی طلایی VIP لاکچری',
					badge: 'طلا و اکسسوری VIP',
					hdr_bg: 'linear-gradient(135deg, #09090b, #1c1917)',
					body_bg: '#18181b',
					user_bg: 'linear-gradient(135deg, #b45309, #d97706)',
					user_c: '#ffffff',
					ai_bg: '#27272a',
					ai_b: '#78350f',
					ai_c: '#fef3c7',
					adm_bg: '#292524',
					adm_b: '#f59e0b',
					adm_c: '#fef9c3',
					dots: ['#09090b', '#d97706', '#fbbf24']
				},
				'minimal-slate': {
					name: '۶. مینیمال خنثی و تمیز',
					title: 'مینیمال خنثی و تمیز',
					badge: 'طراحی اسکاندیناوی',
					hdr_bg: 'linear-gradient(135deg, #1e293b, #334155)',
					body_bg: '#f8fafc',
					user_bg: '#334155',
					user_c: '#ffffff',
					ai_bg: '#ffffff',
					ai_b: '#cbd5e1',
					ai_c: '#0f172a',
					adm_bg: '#f1f5f9',
					adm_b: '#94a3b8',
					adm_c: '#0f172a',
					dots: ['#1e293b', '#475569', '#94a3b8']
				},
				'aurora-gradient': {
					name: '۷. گرادینت شفق قطبی',
					title: 'گرادینت شفق قطبی',
					badge: 'ارغوانی و فیروزه‌ای',
					hdr_bg: 'linear-gradient(135deg, #4338ca, #06b6d4)',
					body_bg: '#f5f3ff',
					user_bg: 'linear-gradient(135deg, #4f46e5, #06b6d4)',
					user_c: '#ffffff',
					ai_bg: '#ffffff',
					ai_b: '#c7d2fe',
					ai_c: '#312e81',
					adm_bg: '#ecfeff',
					adm_b: '#a5f3fc',
					adm_c: '#164e63',
					dots: ['#4338ca', '#6366f1', '#06b6d4']
				},
				'sunset-coral': {
					name: '۸. غروب آفتاب کالیفرنیا',
					title: 'غروب آفتاب کالیفرنیا',
					badge: 'مرجانی و صمیمی',
					hdr_bg: 'linear-gradient(135deg, #9a3412, #ea580c)',
					body_bg: '#fff7ed',
					user_bg: 'linear-gradient(135deg, #f97316, #ea580c)',
					user_c: '#ffffff',
					ai_bg: '#ffffff',
					ai_b: '#fed7aa',
					ai_c: '#7c2d12',
					adm_bg: '#fef2f2',
					adm_b: '#fecaca',
					adm_c: '#991b1b',
					dots: ['#9a3412', '#ea580c', '#fb923c']
				},
				'telegram-ocean': {
					name: '۹. چت تلگرامی اقیانوسی',
					title: 'چت تلگرامی اقیانوسی',
					badge: 'آبی تلگرامی',
					hdr_bg: 'linear-gradient(135deg, #0369a1, #0284c7)',
					body_bg: '#f0f9ff',
					user_bg: '#e0f2fe',
					user_c: '#0369a1',
					ai_bg: '#ffffff',
					ai_b: '#bae6fd',
					ai_c: '#0c4a6e',
					adm_bg: '#f0fdf4',
					adm_b: '#bbf7d0',
					adm_c: '#14532d',
					dots: ['#0369a1', '#0284c7', '#38bdf8']
				},
				'warm-caramel': {
					name: '۱۰. شکلاتی و کاراملی',
					title: 'شکلاتی و کاراملی کافه‌ای',
					badge: 'گرم و نوستالژیک',
					hdr_bg: 'linear-gradient(135deg, #451a03, #92400e)',
					body_bg: '#fffbeb',
					user_bg: 'linear-gradient(135deg, #b45309, #92400e)',
					user_c: '#ffffff',
					ai_bg: '#ffffff',
					ai_b: '#fde68a',
					ai_c: '#78350f',
					adm_bg: '#fef3c7',
					adm_b: '#f59e0b',
					adm_c: '#78350f',
					dots: ['#451a03', '#92400e', '#f59e0b']
				},
				'mint-pastel': {
					name: '۱۱. نعنایی و فیروزه‌ای',
					title: 'نعنایی و فیروزه‌ای پاستلی',
					badge: 'آرامش‌بخش و سلامت',
					hdr_bg: 'linear-gradient(135deg, #115e59, #0d9488)',
					body_bg: '#f0fdfa',
					user_bg: 'linear-gradient(135deg, #0d9488, #14b8a6)',
					user_c: '#ffffff',
					ai_bg: '#ffffff',
					ai_b: '#ccfbf1',
					ai_c: '#134e4a',
					adm_bg: '#ecfdf5',
					adm_b: '#a7f3d0',
					adm_c: '#065f46',
					dots: ['#115e59', '#0d9488', '#2dd4bf']
				},
				'frosted-glass': {
					name: '۱۲. شیشه‌ای گلس‌مورفیسم',
					title: 'شیشه‌ای نیمه‌شفاف گلس‌مورفیسم',
					badge: 'کریستالی شفاف ۲۰۲۶',
					hdr_bg: 'linear-gradient(135deg, #1e293b, #2563eb)',
					body_bg: '#f1f5f9',
					user_bg: 'linear-gradient(135deg, #2563eb, #3b82f6)',
					user_c: '#ffffff',
					ai_bg: '#ffffff',
					ai_b: '#cbd5e1',
					ai_c: '#0f172a',
					adm_bg: '#ecfdf5',
					adm_b: '#a7f3d0',
					adm_c: '#065f46',
					dots: ['#1e293b', '#2563eb', '#cbd5e1']
				}
			};

			function applyThemeSelection(themeSlug) {
				var info = themesCatalog[themeSlug] || themesCatalog['royal-blue'];

				// Update select
				$('#chat_theme_selector').val(themeSlug);

				// Update Trigger Button
				$('#themeActiveTitle').text(info.title || info.name);
				$('#themeActiveBadge').text(info.badge);

				var dotsHtml = '';
				info.dots.forEach(function(d){
					dotsHtml += '<span style="width:14px; height:14px; border-radius:50%; background:' + d + '; display:inline-block;"></span>';
				});
				$('#themeActiveDots').html(dotsHtml);

				// Update active class in menu
				$('.theme-menu-card-item').removeClass('active').css('background', 'transparent');
				$('.theme-menu-card-item[data-theme="' + themeSlug + '"]').addClass('active').css('background', '#eff6ff');

				// Update Live Mockup Preview Card
				$('#mockHdr').css('background', info.hdr_bg);
				$('#mockBody').css('background', info.body_bg);
				$('#mockUserBubble').css({
					background: info.user_bg,
					color: info.user_c
				});
				$('#mockAiBubble').css({
					background: info.ai_bg,
					borderColor: info.ai_b,
					color: info.ai_c
				});
				$('#mockAdminBubble').css({
					background: info.adm_bg,
					borderColor: info.adm_b,
					color: info.adm_c
				});
				$('#mockPaletteDots').html(dotsHtml);
			}

			// Toggle theme card dropdown
			$('#themeCardDropdownBtn').on('click', function(e){
				e.stopPropagation();
				var $menu = $('#themeCardDropdownMenu');
				var isOpen = $menu.is(':visible');
				$menu.toggle(!isOpen);
				$('#themeDropdownArrow').css('transform', isOpen ? 'rotate(0deg)' : 'rotate(180deg)');
			});

			// Select theme item from dropdown
			$(document).on('click', '.theme-menu-card-item', function(e){
				e.stopPropagation();
				var slug = $(this).attr('data-theme');
				applyThemeSelection(slug);
				$('#themeCardDropdownMenu').hide();
				$('#themeDropdownArrow').css('transform', 'rotate(0deg)');
			});

			// Close dropdown when clicking outside
			$(document).on('click', function(e){
				if (!$(e.target).closest('.card-dropdown-wrap').length) {
					$('#themeCardDropdownMenu').hide();
					$('#themeDropdownArrow').css('transform', 'rotate(0deg)');
				}
			});

			// Initialize theme preview on load
			var initialTheme = $('#chat_theme_selector').val() || 'royal-blue';
			applyThemeSelection(initialTheme);

			// ================= SUPPORT DESK INTERACTION =================
			var activeThreadData = null;

			function escapeHtml(text) {
				if (!text) return '';
				return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
			}

			function selectThreadItem($item) {
				$('.desk-thread-item').removeClass('active');
				$item.addClass('active');

				var threadId = $item.attr('data-id');
				var threadSession = $item.attr('data-session');
				var name = $item.attr('data-name') || 'مشتری';
				var phone = $item.attr('data-phone') || '';
				var email = $item.attr('data-email') || '';
				var subject = $item.attr('data-subject') || '';
				var status = $item.attr('data-status') || 'pending';

				activeThreadData = {
					id: threadId,
					session: threadSession,
					name: name,
					phone: phone,
					email: email,
					subject: subject,
					status: status,
					element: $item
				};

				// Mobile responsiveness: switch to full conversation view
				if ($(window).width() <= 782) {
					$('.desk-threads-col').addClass('mobile-hide');
					$('#btnDeskBackToList').show();
				}

				// Populate header card
				$('#deskEmptyState').hide();
				$('#deskActiveBox').css('display', 'flex');
				$('#deskHeaderName').text(name);
				$('#deskHeaderPhone').html(phone ? '📱 <strong>' + escapeHtml(phone) + '</strong>' : '');
				$('#deskHeaderEmail').html(email ? '📧 ' + escapeHtml(email) : '');
				$('#deskHeaderSubject').html(subject ? '📌 ' + escapeHtml(subject) : '');

				if (status === 'replied') {
					$('#deskHeaderStatus').css({background: '#ecfdf5', color: '#047857'}).text('✅ پاسخ داده شد');
				} else {
					$('#deskHeaderStatus').css({background: '#fef3c7', color: '#b45309'}).text('⏳ در انتظار پاسخ');
				}

				// Action buttons
				if (phone) {
					$('#deskDirectCallBtn').attr('href', 'tel:' + phone).show();
					var cleanPhone = phone.replace(/[^0-9]/g, '');
					if (cleanPhone.startsWith('0')) cleanPhone = '98' + cleanPhone.substring(1);
					$('#deskDirectWaBtn').attr('href', 'https://wa.me/' + cleanPhone).show();
				} else {
					$('#deskDirectCallBtn').hide();
					$('#deskDirectWaBtn').hide();
				}

				// Fetch full thread history via AJAX
				$('#deskMsgStream').html('<div style="text-align:center; padding:30px; color:#64748b;">در حال دریافت تاریخچه پیام‌ها... ⏳</div>');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'scraper_admin_get_thread',
						nonce: '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>',
						thread_id: threadId
					},
					success: function(res){
						if (res.success && res.data && res.data.thread) {
							renderDeskStream(res.data.thread.messages || []);
						} else {
							$('#deskMsgStream').html('<div style="color:#dc2626; text-align:center; padding:20px;">خطا در بارگذاری پیام‌ها.</div>');
						}
					},
					error: function(){
						$('#deskMsgStream').html('<div style="color:#dc2626; text-align:center; padding:20px;">خطای ارتباط با سرور.</div>');
					}
				});

				$('#deskReplyInput').focus();
			}

			function renderDeskStream(messages) {
				var $stream = $('#deskMsgStream');
				$stream.empty();

				if (!messages || messages.length === 0) {
					$stream.html('<div style="text-align:center; padding:30px; color:#94a3b8;">هنوز پیامی در این گفتگو وجود ندارد.</div>');
					return;
				}

				messages.forEach(function(msg){
					var $b = $('<div>').addClass('desk-bubble');
					var timeStr = msg.time || '';

					if (msg.sender === 'customer') {
						$b.addClass('customer');
						$b.html('<div style="font-size:0.75rem; font-weight:800; color:#2563eb; margin-bottom:3px;">👤 ' + escapeHtml(msg.sender_name || 'مشتری') + '</div><div>' + escapeHtml(msg.text) + '</div><div style="font-size:0.68rem; color:#94a3b8; margin-top:4px; text-align:left; direction:ltr;">' + escapeHtml(timeStr) + '</div>');
					} else if (msg.sender === 'ai') {
						$b.addClass('ai');
						$b.html('<div style="font-size:0.75rem; font-weight:800; color:#7c3aed; margin-bottom:3px;">🤖 ' + escapeHtml(msg.sender_name || 'پشتیبان هوشمند') + '</div><div>' + escapeHtml(msg.text).replace(/\n/g, '<br>') + '</div><div style="font-size:0.68rem; color:#94a3b8; margin-top:4px; text-align:left; direction:ltr;">' + escapeHtml(timeStr) + '</div>');
					} else if (msg.sender === 'admin') {
						$b.addClass('admin');
						$b.html('<div style="font-size:0.75rem; font-weight:800; color:#fef08a; margin-bottom:3px;">👨‍💼 ' + escapeHtml(msg.sender_name || 'مدیریت فروشگاه (پاسخ شما)') + '</div><div>' + escapeHtml(msg.text).replace(/\n/g, '<br>') + '</div><div style="font-size:0.68rem; opacity:0.8; margin-top:4px; text-align:left; direction:ltr;">' + escapeHtml(timeStr) + '</div>');
					}

					$stream.append($b);
				});

				$stream.scrollTop($stream[0].scrollHeight);
			}

			// Click thread item
			$(document).on('click', '.desk-thread-item', function(){
				selectThreadItem($(this));
			});

			// Mobile back button to list
			$('#btnDeskBackToList').on('click', function(){
				$('.desk-threads-col').removeClass('mobile-hide');
				$('#btnDeskBackToList').hide();
			});

			// Canned response quick insert
			$(document).on('click', '.desk-canned-chip', function(){
				var text = $(this).attr('data-text');
				var cur = $('#deskReplyInput').val();
				if (cur) {
					$('#deskReplyInput').val(cur + ' ' + text);
				} else {
					$('#deskReplyInput').val(text);
				}
				$('#deskReplyInput').focus();
			});

			// Send Admin Reply via AJAX
			$('#deskSendReplyBtn').on('click', function(){
				if (!activeThreadData || !activeThreadData.id) {
					alert('لطفاً ابتدا یک گفتگو را انتخاب نمایید.');
					return;
				}

				var replyText = $('#deskReplyInput').val().trim();
				if (!replyText) {
					alert('لطفاً متن پاسخ را بنویسید.');
					$('#deskReplyInput').focus();
					return;
				}

				var $btn = $(this);
				var $feedback = $('#deskReplyFeedback');
				$btn.prop('disabled', true).text('در حال ارسال... ⏳');
				$feedback.html('<span style="color:#2563eb;">ارسال پاسخ به چت مشتری...</span>');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'scraper_admin_send_chat_reply',
						nonce: '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>',
						thread_id: activeThreadData.id,
						reply_text: replyText
					},
					success: function(res){
						$btn.prop('disabled', false).text('ارسال پاسخ 🚀');
						if (res.success && res.data && res.data.thread) {
							$('#deskReplyInput').val('');
							$feedback.html('<span style="color:#059669;">✅ پاسخ ارسال شد و بلافاصله در چت مشتری نمایش داده شد.</span>');
							setTimeout(function(){ $feedback.empty(); }, 4000);

							// Update stream
							renderDeskStream(res.data.thread.messages || []);

							// Update thread item in list
							if (activeThreadData.element) {
								activeThreadData.element.attr('data-status', 'replied').removeClass('unread');
								activeThreadData.element.find('span:contains("نیاز به پاسخ")')
									.css({background:'#ecfdf5', color:'#047857'})
									.text('✅ پاسخ داده شد');
							}
							$('#deskHeaderStatus').css({background: '#ecfdf5', color: '#047857'}).text('✅ پاسخ داده شد');
						} else {
							$feedback.html('<span style="color:#dc2626;">❌ ' + (res.data || 'خطا در ارسال پاسخ.') + '</span>');
						}
					},
					error: function(){
						$btn.prop('disabled', false).text('ارسال پاسخ 🚀');
						$feedback.html('<span style="color:#dc2626;">❌ خطای ارتباط با سرور.</span>');
					}
				});
			});

			// Delete Thread Button
			$('#deskDeleteBtn').on('click', function(){
				if (!activeThreadData || !activeThreadData.id) return;
				if (!confirm('آیا از حذف کامل این گفتگو اطمینان دارید؟')) return;

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'scraper_admin_delete_thread',
						nonce: '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>',
						thread_id: activeThreadData.id
					},
					success: function(res){
						if (res.success) {
							if (activeThreadData.element) {
								activeThreadData.element.slideUp(function(){ $(this).remove(); });
							}
							$('#deskActiveBox').hide();
							$('#deskEmptyState').show();
							activeThreadData = null;
							if ($(window).width() <= 782) {
								$('.desk-threads-col').removeClass('mobile-hide');
								$('#btnDeskBackToList').hide();
							}
						} else {
							alert(res.data || 'خطا در حذف گفتگو.');
						}
					}
				});
			});

			// Search input filter
			$('#deskSearchInput').on('input', function(){
				var q = $(this).val().toLowerCase().trim();
				$('.desk-thread-item').each(function(){
					var name = ($(this).attr('data-name') || '').toLowerCase();
					var phone = ($(this).attr('data-phone') || '').toLowerCase();
					var text = $(this).text().toLowerCase();
					if (!q || name.indexOf(q) > -1 || phone.indexOf(q) > -1 || text.indexOf(q) > -1) {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			});

			// Filter buttons (all / pending / replied)
			$('.desk-filter-btn').on('click', function(){
				$('.desk-filter-btn').removeClass('active').css({background:'#ffffff', color:'#334155'});
				$(this).addClass('active').css({background:'#2563eb', color:'#ffffff'});
				var filter = $(this).attr('data-filter');

				$('.desk-thread-item').each(function(){
					var st = $(this).attr('data-status') || 'pending';
					if (filter === 'all') {
						$(this).show();
					} else if (filter === 'pending' && st === 'pending') {
						$(this).show();
					} else if (filter === 'replied' && st === 'replied') {
						$(this).show();
					} else {
						$(this).hide();
					}
				});
			});

						function updateDualStateUI() {
				var hasTakeover = $('#chkStoreTakeover').is(':checked');
				var catSrc = $('input[name="catalog_source"]:checked').val() || 'scraper';
				var $box = $('#boxDualStateIndicator');
				var $title = $('#dualStateTitle');
				var $desc = $('#dualStateDesc');
				$('#labelStoreTakeover').css('border-color', hasTakeover ? '#2563eb' : '#cbd5e1');
				$('#labelCatalogSource').css('border-color', catSrc === 'merge' ? '#7c3aed' : (catSrc === 'woocommerce' ? '#2563eb' : '#10b981'));
				$('#mergePreferBox').toggle(catSrc === 'merge');
				$('#chkScrapedProducts').prop('checked', catSrc !== 'woocommerce');
				var themeTxt = hasTakeover ? 'ویترین و هدر اختصاصی React' : 'قالب و هدر اصلی وردپرس';
				if (catSrc === 'merge') {
					$box.css({background: '#f5f3ff', borderColor: '#7c3aed'});
					$title.css('color', '#6d28d9').html('🔀 ' + themeTxt + ' + ادغام کاتالوگ');
					$desc.html('محصولات پروفایل‌های اسکرپر و کالاهای publish ووکامرس با هم در ویترین می‌آیند. تکراری‌ها طبق «اولویت ادغام» مدیریت می‌شوند.');
				} else if (catSrc === 'woocommerce') {
					$box.css({background: hasTakeover ? '#eff6ff' : '#f8fafc', borderColor: '#2563eb'});
					$title.css('color', '#1d4ed8').html('🛒 ' + themeTxt + ' + محصولات ووکامرس');
					$desc.html('کاتالوگ ویترین مستقیماً از دیتابیس ووکامرس خوانده می‌شود.');
				} else {
					$box.css({background: hasTakeover ? '#ecfdf5' : '#fffbeb', borderColor: '#10b981'});
					$title.css('color', '#047857').html('🌟 ' + themeTxt + ' + محصولات اسکرپر');
					$desc.html('محصولات profiles.json اسکرپر در ویترین نمایش می‌یابند. اگر خالی باشد، fallback به ووکامرس.');
				}
			}
			$('#chkStoreTakeover').on('change', updateDualStateUI);
			$('input[name="catalog_source"], input[name="catalog_merge_prefer"]').on('change', updateDualStateUI);
			updateDualStateUI();


			// Quick Preset Questions
			$('.btn-quick-preset').on('click', function(e){
				e.preventDefault();
				var q = $(this).attr('data-msg');
				$('#testAiChatMessage').val(q);
			});

			var adminNonce = '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>';

			// 1. Live AI Chat Test with Master Model
			$('#btnRunTestAiChat').on('click', function(e){
				e.preventDefault();
				var msg = $('#testAiChatMessage').val().trim();
				if (!msg) {
					alert('لطفاً یک پیام آزمایشی وارد کنید.');
					$('#testAiChatMessage').focus();
					return;
				}

				var $btn = $(this);
				var $box = $('#testAiChatResultBox');
				var $text = $('#testAiResponseText');
				var $badge = $('#testAiModelUsedBadge');
				var $time = $('#testAiTimeBadge');

				$btn.prop('disabled', true).text('در حال پاسخگویی مستر... ⏳');
				$('#testAiCandidatesCompareBox').hide();
				$box.show();
				$text.html('<span style="color:#2563eb;">در حال استعلام از مدل مستر اسکرپر با تحلیل کاتالوگ فروشگاه...</span>');
				$badge.text('مدل مستر');
				$time.text('');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					timeout: 120000,
					dataType: 'json',
					data: {
						action: 'scraper_test_ai_chat',
						nonce: adminNonce,
						message: msg
					},
					success: function(res){
						$btn.prop('disabled', false).text('🚀 پرسش از مدل مستر (Master AI)');
						if (res && res.success && res.data) {
							$text.text(res.data.reply || '(پاسخ خالی)');
							var meta = 'مدل مستر: ' + (res.data.model || 'هوشمند') + ' (' + (res.data.provider || 'اسکرپر') + ')';
							if (res.data.api_ready === false) meta += ' — ⚠️ کلید API خالی';
							$badge.text(meta);
							$time.text((res.data.took_ms || 0) + ' میلی‌ثانیه');
						} else {
							var err = (res && res.data) ? (typeof res.data === 'string' ? res.data : (res.data.message || JSON.stringify(res.data))) : 'عدم دریافت پاسخ.';
							$text.html('<span style="color:#dc2626;">خطا: ' + err + '</span>');
						}
					},
					error: function(xhr, status, err){
						$btn.prop('disabled', false).text('🚀 پرسش از مدل مستر (Master AI)');
						var body = (xhr && xhr.responseText) ? String(xhr.responseText).replace(/<[^>]+>/g,' ').slice(0, 280) : '';
						var hint = status === 'timeout' ? 'زمان انتظار تمام شد (timeout).' : ('HTTP ' + (xhr && xhr.status ? xhr.status : '?') + ' / ' + (err || status || ''));
						$text.html('<span style="color:#dc2626;">خطای ارتباط با سرور: ' + hint + (body ? ('<br><small style="color:#64748b;direction:ltr;display:block;margin-top:8px;text-align:left;">' + $('<div/>').text(body).html() + '</small>') : '') + '</span>');
					}
				});
			});

			// 2. Candidate Models Comparison & Voting
			var lastCompareCandidates = [];
			var lastCompareMsg = '';

			$('#btnCompareAiCandidates').on('click', function(e){
				e.preventDefault();
				var msg = $('#testAiChatMessage').val().trim();
				if (!msg) {
					alert('لطفاً یک پیام آزمایشی وارد کنید.');
					$('#testAiChatMessage').focus();
					return;
				}

				lastCompareMsg = msg;
				var $btn = $(this);
				var $compareBox = $('#testAiCandidatesCompareBox');
				var $grid = $('#compareCandidatesCardsGrid');

				$btn.prop('disabled', true).text('در حال پرسش از همه کاندیدها... ⏳');
				$('#testAiChatResultBox').hide();
				$compareBox.show();
				$grid.html('<div style="grid-column:1/-1; text-align:center; padding:30px; color:#64748b; font-size:0.95rem;">⏳ در حال فراخوانی موازی مدل‌های کاندید و ارزیابی پاسخ‌ها با هوش مصنوعی اسکرپر...</div>');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'scraper_compare_ai_candidates',
						nonce: adminNonce,
						message: msg
					},
					success: function(res){
						$btn.prop('disabled', false).text('⚖️ آزمون مقایسه‌ای همه کاندیدها');
						if (res.success && res.data && res.data.candidates && res.data.candidates.length) {
							lastCompareCandidates = res.data.candidates;
							var masterKey = res.data.master || '';
							var html = '';

							res.data.candidates.forEach(function(cand, idx){
								var isM = (cand.key === masterKey || cand.is_master);
								var scorePct = Math.round((cand.score || 0) * 100);

								html += '<div class="candidate-test-card" style="background:#ffffff; border:1.5px solid ' + (isM ? '#fbbf24' : '#e2e8f0') + '; border-radius:12px; padding:16px; display:flex; flex-direction:column; justify-content:space-between; box-shadow:' + (isM ? '0 4px 14px rgba(251,191,36,0.18)' : '0 2px 6px rgba(0,0,0,0.03)') + ';">';
								
								// Card Header
								html += '<div style="border-bottom:1px solid #f1f5f9; padding-bottom:10px; margin-bottom:10px;">';
								html += '<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:6px;">';
								html += '<strong style="color:' + (isM ? '#b45309' : '#1e293b') + '; font-size:0.95rem;">' + $('<div>').text(cand.providerName).html() + '</strong>';
								if (isM) {
									html += '<span style="background:#fef3c7; color:#b45309; border:1px solid #fde68a; font-size:0.75rem; font-weight:800; padding:2px 8px; border-radius:8px;">⭐ مدل مستر</span>';
								}
								html += '</div>';
								html += '<div style="color:#64748b; font-size:0.8rem; margin-top:2px;" dir="ltr">' + $('<div>').text(cand.model).html() + ' · <span style="color:#2563eb; font-weight:700;">' + cand.latency + 'ms</span></div>';
								html += '<div style="font-size:0.78rem; color:#059669; font-weight:700; margin-top:3px;">امتیاز موفقیت: ' + scorePct + '٪ (' + cand.wins + ' برد از ' + cand.votes + ' آزمون)</div>';
								html += '</div>';

								// Card Body (Reply)
								html += '<div style="font-size:0.92rem; color:#334155; line-height:1.7; flex:1; margin-bottom:12px; white-space:pre-wrap;">' + $('<div>').text(cand.text).html() + '</div>';

								// Card Footer / Actions
								html += '<div style="display:flex; gap:6px; flex-wrap:wrap; padding-top:10px; border-top:1px solid #f8fafc;">';
								html += '<button type="button" class="button button-small btn-vote-candidate" data-winner="' + $('<div>').text(cand.key).html() + '" style="background:#10b981; color:#fff; border-color:#059669; font-weight:800; border-radius:6px; flex:1;">✓ این پاسخ بهتر بود (ثبت رأی)</button>';
								if (!isM) {
									html += '<button type="button" class="button button-small btn-pin-candidate" data-key="' + $('<div>').text(cand.key).html() + '" style="border-radius:6px;">📌 سنجاق مستر</button>';
								}
								html += '</div>';

								html += '</div>';
							});

							$grid.html(html);
						} else {
							$grid.html('<div style="grid-column:1/-1; color:#dc2626; padding:20px; text-align:center;">خطا: ' + (res.data || 'هیچ پاسخی از کاندیدها دریافت نشد.') + '</div>');
						}
					},
					error: function(){
						$btn.prop('disabled', false).text('⚖️ آزمون مقایسه‌ای همه کاندیدها');
						$grid.html('<div style="grid-column:1/-1; color:#dc2626; padding:20px; text-align:center;">خطای ارتباط با سرور.</div>');
					}
				});
			});

			// 3. Voting for a Candidate
			$(document).on('click', '.btn-vote-candidate', function(e){
				e.preventDefault();
				var $btn = $(this);
				var winnerKey = $btn.attr('data-winner');
				var allKeys = lastCompareCandidates.map(function(c){ return c.key; });

				$btn.prop('disabled', true).text('در حال ثبت رأی... ⏳');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'scraper_vote_ai_candidate',
						nonce: adminNonce,
						winner: winnerKey,
						task: 'autoreply',
						input: lastCompareMsg,
						candidates: allKeys
					},
					success: function(res){
						if (res.success) {
							alert('✅ ' + (res.data.message || 'رای شما با موفقیت ثبت شد!'));
							location.reload();
						} else {
							alert('خطا در ثبت رای: ' + (res.data || 'ناموفق'));
							$btn.prop('disabled', false).text('✓ این پاسخ بهتر بود (ثبت رأی)');
						}
					},
					error: function(){
						alert('خطای ارتباط با سرور.');
						$btn.prop('disabled', false).text('✓ این پاسخ بهتر بود (ثبت رأی)');
					}
				});
			});

			// 4. Pinning a Candidate as Master
			$(document).on('click', '.btn-pin-candidate', function(e){
				e.preventDefault();
				var $btn = $(this);
				var key = $btn.attr('data-key');
				if (!confirm('آیا مایلید این مدل را به عنوان مدل مستر اصلی اسکرپر سنجاق (Pin) کنید؟')) {
					return;
				}

				$btn.prop('disabled', true).text('در حال سنجاق... ⏳');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'scraper_pin_master_model',
						nonce: adminNonce,
						key: key
					},
					success: function(res){
						if (res.success) {
							alert('✅ ' + (res.data.message || 'مدل به عنوان مستر سنجاق شد.'));
							location.reload();
						} else {
							alert('خطا: ' + (res.data || 'ناموفق'));
							$btn.prop('disabled', false).text('📌 سنجاق مستر');
						}
					},
					error: function(){
						alert('خطای ارتباط با سرور.');
						$btn.prop('disabled', false).text('📌 سنجاق مستر');
					}
				});
			});

			// 5. Config File Upload & Import
			var selectedConfigFile = null;

			$('#btnSelectAiConfigFile').on('click', function(e){
				e.preventDefault();
				$('#aiConfigFileInput').click();
			});

			$('#aiConfigFileInput').on('change', function(e){
				var files = e.target.files;
				if (files && files.length) {
					selectedConfigFile = files[0];
					$('#aiConfigSelectedFileName').show().text('فایل انتخاب شده: ' + selectedConfigFile.name + ' (' + (Math.round(selectedConfigFile.size/1024*10)/10) + ' کیلوبایت)');
					$('#btnUploadAiConfigFile').prop('disabled', false);
				}
			});

			// Drag and drop for config file
			var dropZone = document.getElementById('aiConfigDropZone');
			if (dropZone) {
				['dragenter', 'dragover'].forEach(function(eventName){
					dropZone.addEventListener(eventName, function(e){ e.preventDefault(); e.stopPropagation(); dropZone.style.borderColor = '#2563eb'; dropZone.style.background = '#eff6ff'; }, false);
				});
				['dragleave', 'drop'].forEach(function(eventName){
					dropZone.addEventListener(eventName, function(e){ e.preventDefault(); e.stopPropagation(); dropZone.style.borderColor = '#cbd5e1'; dropZone.style.background = '#ffffff'; }, false);
				});
				dropZone.addEventListener('drop', function(e){
					var dt = e.dataTransfer;
					var files = dt.files;
					if (files && files.length) {
						selectedConfigFile = files[0];
						$('#aiConfigSelectedFileName').show().text('فایل انتخاب شده: ' + selectedConfigFile.name + ' (' + (Math.round(selectedConfigFile.size/1024*10)/10) + ' کیلوبایت)');
						$('#btnUploadAiConfigFile').prop('disabled', false);
					}
				}, false);
			}

			$('#btnUploadAiConfigFile').on('click', function(e){
				e.preventDefault();
				if (!selectedConfigFile) {
					alert('لطفاً ابتدا یک فایل JSON انتخاب کنید.');
					return;
				}

				var $btn = $(this);
				var $status = $('#aiConfigUploadStatus');

				$btn.prop('disabled', true).text('در حال بارگذاری و تحلیل... ⏳');
				$status.show().css({'background': '#eff6ff', 'color': '#1d4ed8', 'border': '1px solid #bfdbfe'}).html('در حال انتقال فایل تنظیمات و ذخیره در سیستم هوش مصنوعی اسکرپر... ⏳');

				var formData = new FormData();
				formData.append('action', 'scraper_upload_ai_config');
				formData.append('nonce', adminNonce);
				formData.append('config_file', selectedConfigFile);

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: formData,
					processData: false,
					contentType: false,
					success: function(res){
						$btn.prop('disabled', false).text('📥 بارگذاری و اعمال فوری در اسکرپر');
						if (res.success && res.data) {
							$status.css({'background': '#f0fdf4', 'color': '#15803d', 'border': '1px solid #bbf7d0'}).html('✅ ' + (res.data.message || 'فایل کانفیگ با موفقیت در اسکرپر بارگذاری و مدل مستر فعال گردید!'));
							setTimeout(function(){ location.reload(); }, 1200);
						} else {
							$status.css({'background': '#fef2f2', 'color': '#b91c1c', 'border': '1px solid #fecaca'}).html('❌ خطا: ' + (res.data || 'بارگذاری ناموفق بود.'));
						}
					},
					error: function(){
						$btn.prop('disabled', false).text('📥 بارگذاری و اعمال فوری در اسکرپر');
						$status.css({'background': '#fef2f2', 'color': '#b91c1c', 'border': '1px solid #fecaca'}).html('❌ خطای ارتباط با سرور هنگام بارگذاری فایل.');
					}
				});
			});

			// 6. Config File Export
			$('#btnExportAiConfigFile').on('click', function(e){
				e.preventDefault();
				window.location.href = ajaxurl + '?action=scraper_export_ai_config&nonce=' + adminNonce;
			});

			// Refresh desk button
			$('#btnRefreshAdminDesk').on('click', function(){
				location.reload();
			});

			// Run WP-Cron Immediately Button (Both Quick Banner and Card Buttons)
			$(document).on('click', '#btnRunWpCronNow, .btnRunWpCronNow', function(e){
				e.preventDefault();
				var $allBtns = $('#btnRunWpCronNow, .btnRunWpCronNow');
				var $status = $('#wpCronRunNowStatus');

				$allBtns.prop('disabled', true).text('در حال اجرای کران‌جاب اسکرپر... ⏳');
				$status.show().css({'background': '#eff6ff', 'color': '#1d4ed8', 'border': '1px solid #bfdbfe'}).html('در حال فراخوانی کران‌جاب وردپرس و اجرای عملیات استخراج اسکرپر۴... ⏳');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'scraper_run_wpcron_now',
						nonce: adminNonce
					},
					success: function(res){
						$allBtns.prop('disabled', false).html('<span>⚡</span> اجرای دستی کران‌جاب همین حالا (Run Now)');
						if (res.success && res.data) {
							$status.css({'background': '#f0fdf4', 'color': '#15803d', 'border': '1px solid #bbf7d0'}).html('✅ ' + (res.data.message || 'کران‌جاب با موفقیت اجرا گردید.') + ' (زمان: ' + res.data.took_sec + ' ثانیه)');
							$('#wpCronLastRunText, #wpCronQuickLastRun').text(res.data.last_run);
							$('#wpCronNextRunText, #wpCronQuickNextRun').text('در ' + res.data.next_human + ' دیگر');
						} else {
							$status.css({'background': '#fef2f2', 'color': '#b91c1c', 'border': '1px solid #fecaca'}).html('❌ خطا در اجرا: ' + (res.data || 'عملیات با خطا مواجه شد.'));
						}
					},
					error: function(){
						$allBtns.prop('disabled', false).html('<span>⚡</span> اجرای دستی کران‌جاب همین حالا (Run Now)');
						$status.css({'background': '#fef2f2', 'color': '#b91c1c', 'border': '1px solid #fecaca'}).html('❌ خطای ارتباط با سرور هنگام اجرای کران‌جاب.');
					}
				});
			});

			// Test Messengers Button
			$('#btnTestMessengers').on('click', function(e){
				e.preventDefault();
				var $btn = $(this);
				var $status = $('#testMessengersStatus');
				$btn.prop('disabled', true).text('در حال ارسال تست... ⏳');
				$status.html('<span style="color:#2563eb;">ارسال پیام آزمایشی به پیام‌رسان‌ها...</span>');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'test_support_messengers',
						nonce: '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>'
					},
					success: function(res){
						$btn.prop('disabled', false).text('🔔 ارسال پیام آزمایشی');
						if (res.success) {
							$status.html('<span style="color:#16a34a; font-weight:700;">✅ ' + (res.data.message || 'با موفقیت ارسال شد!') + '</span>');
						} else {
							var err = (res.data && res.data.message) ? res.data.message : 'خطا در ارسال.';
							$status.html('<span style="color:#dc2626; font-weight:700;">❌ ' + err + '</span>');
						}
					},
					error: function(){
						$btn.prop('disabled', false).text('🔔 ارسال پیام آزمایشی');
						$status.html('<span style="color:#dc2626; font-weight:700;">❌ خطای ارتباط با سرور.</span>');
					}
				});
			});

			// Sync to WooCommerce Button — product-card live queue (v13.2 / v10.103)
			var amphpDirectSyncTimer = null;
			var amphpShopNonce = '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>';
			function amphpEsc(s){ return String(s==null?'':s).replace(/[&<>"']/g,function(c){return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c];}); }
			function amphpRenderDirectCards(p){
				var $box = $('#amphpDirectSyncQueue');
				var $list = $('#amphpDirectSyncCards');
				var $sum = $('#amphpDirectSyncSum');
				var $bar = $('#amphpDirectSyncBar');
				if(!$box.length) return;
				$box.show();
				var total = parseInt(p.total||0,10)||0;
				var cur = parseInt(p.current||0,10)||0;
				var pct = total>0 ? Math.min(100, Math.round(cur/total*100)) : (p.done?100:0);
				$bar.css('width', pct+'%');
				$sum.html('✅ '+(p.created||0)+' جدید · ⚡ '+(p.updated||0)+' آپدیت · ❌ '+(p.failed||0)+' خطا · ⏭ '+(p.skipped||0)+' رد · '+cur+'/'+total);
				var cards = Array.isArray(p.cards) ? p.cards : [];
				var html = '';
				var statusMap = {
					created:{c:'#4ade80',t:'ایجاد شد',i:'✅'},
					updated:{c:'#facc15',t:'آپدیت شد',i:'⚡'},
					failed:{c:'#f87171',t:'خطا',i:'❌'},
					skipped:{c:'#94a3b8',t:'رد شد',i:'⏭'},
					pending:{c:'#60a5fa',t:'در صف',i:'⏳'}
				};
				for(var i=0;i<cards.length;i++){
					var c = cards[i]||{};
					var st = statusMap[c.status] || statusMap.pending;
					var img = c.image ? '<img src="'+amphpEsc(c.image)+'" alt="" style="width:48px;height:48px;border-radius:8px;object-fit:cover;background:#0f172a;flex-shrink:0" onerror="this.style.display=\'none\'">' : '<div style="width:48px;height:48px;border-radius:8px;background:#1e293b;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:#475569">📦</div>';
					html += '<div style="display:flex;gap:10px;align-items:flex-start;padding:8px 10px;border:1px solid #334155;border-radius:10px;background:#1e293b;border-right:3px solid '+st.c+'">';
					html += img;
					html += '<div style="flex:1;min-width:0">';
					html += '<div style="color:#e2e8f0;font-weight:800;font-size:0.88rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">'+amphpEsc(c.title||'—')+'</div>';
					html += '<div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:3px;font-size:0.78rem;color:#94a3b8">';
					if(c.price) html += '<span style="color:#4ade80;font-family:ui-monospace,monospace">'+amphpEsc(c.price)+'</span>';
					if(c.category) html += '<span style="color:#c084fc">📂 '+amphpEsc(c.category)+'</span>';
					if(c.woo_id) html += '<span style="color:#60a5fa">ID #'+amphpEsc(c.woo_id)+'</span>';
					html += '<span style="color:#64748b">🔌 مستقیم</span>';
					html += '</div>';
					if(c.detail) html += '<div style="margin-top:3px;font-size:0.75rem;color:#64748b">'+amphpEsc(c.detail)+'</div>';
					html += '</div>';
					html += '<div style="color:'+st.c+';font-weight:900;font-size:0.8rem;white-space:nowrap">'+st.i+' '+st.t+'</div>';
					html += '</div>';
				}
				if(!html) html = '<div style="color:#64748b;padding:12px;text-align:center;font-size:0.85rem">در انتظار اولین محصول…</div>';
				$list.html(html);
				/* keep scrolled to latest */
				try{ $list[0].scrollTop = $list[0].scrollHeight; }catch(e){}
			}
			function amphpPollDirectSync(){
				$.ajax({
					url: ajaxurl, type:'POST',
					data:{ action:'scraper_direct_sync_progress', nonce: amphpShopNonce },
					success:function(res){
						var p = (res && res.success && res.data) ? res.data : (res && res.data) || {};
						if(p && (p.total || p.cards)) amphpRenderDirectCards(p);
						if(p && p.done){
							if(amphpDirectSyncTimer){ clearInterval(amphpDirectSyncTimer); amphpDirectSyncTimer=null; }
						}
					}
				});
			}
			$('#btnSyncToWoo').on('click', function(){
				var $btn = $(this);
				var $status = $('#syncWooStatus');
				$btn.prop('disabled', true).text('در حال همگام‌سازی... ⏳');
				$status.html('<span style="color:#2563eb;">همگام‌سازی مستقیم — کارت هر محصول به‌روز می‌شود…</span>');
				$('#amphpDirectSyncQueue').show();
				$('#amphpDirectSyncCards').html('<div style="color:#64748b;padding:12px;text-align:center">در حال آماده‌سازی صف…</div>');
				$('#amphpDirectSyncBar').css('width','2%');
				if(amphpDirectSyncTimer) clearInterval(amphpDirectSyncTimer);
				amphpDirectSyncTimer = setInterval(amphpPollDirectSync, 1200);
				amphpPollDirectSync();

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					timeout: 0,
					data: {
						action: 'scraper_sync_to_woo',
						nonce: amphpShopNonce
					},
					success: function(res){
						$btn.prop('disabled', false).text('همگام‌سازی و درج در دیتابیس ووکامرس');
						if(amphpDirectSyncTimer){ clearInterval(amphpDirectSyncTimer); amphpDirectSyncTimer=null; }
						amphpPollDirectSync();
						if (res.success) {
							var d = res.data || {};
							$status.html('<span style="color:#16a34a; font-weight:700;">✅ ' + (d.message || 'تمام شد') + '</span>');
							if(d.cards || d.total) amphpRenderDirectCards(d);
						} else {
							var err = (res.data && res.data.message) ? res.data.message : (typeof res.data==='string'?res.data:'خطا در همگام‌سازی');
							$status.html('<span style="color:#dc2626; font-weight:700;">❌ ' + err + '</span>');
						}
					},
					error: function(){
						$btn.prop('disabled', false).text('همگام‌سازی و درج در دیتابیس ووکامرس');
						if(amphpDirectSyncTimer){ clearInterval(amphpDirectSyncTimer); amphpDirectSyncTimer=null; }
						amphpPollDirectSync();
						$status.html('<span style="color:#dc2626; font-weight:700;">❌ خطای ارتباط با سرور — آخرین وضعیت کارت‌ها را ببینید.</span>');
					}
				});
			});

			/* v10.99: تست / فعال‌سازی اتصال مستقیم ووکامرس */
			var amphpWooNonce = '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>';
			function amphpShowWooReport(obj) {
				var $pre = $('#amphpWooBridgeReport');
				try {
					$pre.text(typeof obj === 'string' ? obj : JSON.stringify(obj, null, 2)).show();
				} catch (e) {
					$pre.text(String(obj)).show();
				}
			}
			$('#btnTestWooDirect').on('click', function(){
				var $btn = $(this);
				var $st = $('#amphpWooBridgeStatus');
				$btn.prop('disabled', true).text('در حال تست...');
				$st.html('<span style="color:#2563eb;">آزمایش ایجاد draft و پاک‌سازی...</span>');
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: { action: 'scraper_test_woo_direct', nonce: amphpWooNonce },
					success: function(res){
						$btn.prop('disabled', false).text('🧪 تست اتصال مستقیم');
						var d = (res && res.data) ? res.data : res;
						var ok = !!(res && res.success && d && (d.ok === true || (d.direct && d.direct.ok)));
						var msg = (d && (d.message || (d.direct && d.direct.message))) || (ok ? 'OK' : 'ناموفق');
						$st.html(ok
							? '<span style="color:#16a34a;">✅ ' + msg + '</span>'
							: '<span style="color:#dc2626;">❌ ' + msg + '</span>');
						amphpShowWooReport(d);
					},
					error: function(xhr){
						$btn.prop('disabled', false).text('🧪 تست اتصال مستقیم');
						$st.html('<span style="color:#dc2626;">❌ خطای ارتباط</span>');
						amphpShowWooReport({ http: xhr.status, body: xhr.responseText });
					}
				});
			});
			$('#btnEnableWooDirect').on('click', function(){
				var $btn = $(this);
				var $st = $('#amphpWooBridgeStatus');
				if (!window.confirm('sync_mode=direct در connections.json نوشته شود؟')) return;
				$btn.prop('disabled', true).text('در حال فعال‌سازی...');
				$st.html('<span style="color:#2563eb;">نوشتن connections.json...</span>');
				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'scraper_enable_woo_direct',
						nonce: amphpWooNonce,
						fallback: $('#amphpWooFallback').val() || 'direct_then_api'
					},
					success: function(res){
						$btn.prop('disabled', false).text('⚡ فعال‌سازی اتصال مستقیم');
						var d = (res && res.data) ? res.data : {};
						if (res && res.success) {
							$st.html('<span style="color:#16a34a;">✅ ' + (d.message || 'فعال شد') + '</span>');
							$('#amphpWooSyncModeLabel').text(d.sync_mode || 'direct');
							$('#amphpWooEnabledLabel').text('بله').css('color', '#059669');
							if (d.store_url) $('#amphpWooStoreLabel').text(d.store_url);
							$('#amphpWooBridgeBadge').text('آماده · direct').removeClass('field-badge-blue').addClass('field-badge-green');
						} else {
							var err = (typeof d === 'string') ? d : (d.message || 'خطا');
							$st.html('<span style="color:#dc2626;">❌ ' + err + '</span>');
						}
						amphpShowWooReport(d);
					},
					error: function(xhr){
						$btn.prop('disabled', false).text('⚡ فعال‌سازی اتصال مستقیم');
						$st.html('<span style="color:#dc2626;">❌ خطای ارتباط</span>');
						amphpShowWooReport({ http: xhr.status, body: xhr.responseText });
					}
				});
			});
		});
		</script>
		<?php
	}

	public static function render_scraper4_embed_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$scraper_url = plugins_url( 'scraper4.php', __FILE__ );
		?>
		<div class="wrap" style="direction:rtl; text-align:right;">
			<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
				<h1 style="font-weight:800; margin:0;">⚡ پنل کامل اسکرپر هوشمند (scraper4)</h1>
				<a href="<?php echo esc_url( $scraper_url ); ?>" target="_blank" class="button button-primary button-large" style="background:#2563eb;">
					باز کردن در پنجرهٔ مجزا ↗
				</a>
			</div>
			<p style="color:#64748b; margin-bottom:15px;">می‌توانید تمامی عملیات استخراج، مدیریت پروفایل‌ها، صف و ارتباط با باسلام/ووکامرس را مستقیماً از فریم زیر مدیریت نمایید:</p>
			<iframe src="<?php echo esc_url( $scraper_url ); ?>" style="width:100%; height:840px; border:1px solid #cbd5e1; border-radius:12px; background:#fff; box-shadow:0 4px 12px rgba(0,0,0,0.05);"></iframe>
		</div>
		<?php
	}
}

endif; // class_exists

// Global activation and deactivation hooks
register_activation_hook( __FILE__, array( 'Scraper_Auto_Shop_Plugin', 'on_activate' ) );
register_deactivation_hook( __FILE__, array( 'Scraper_Auto_Shop_Plugin', 'on_deactivate' ) );

// Initialize
if ( did_action( 'plugins_loaded' ) ) {
	Scraper_Auto_Shop_Plugin::init();
} else {
	add_action( 'plugins_loaded', array( 'Scraper_Auto_Shop_Plugin', 'init' ) );
}
