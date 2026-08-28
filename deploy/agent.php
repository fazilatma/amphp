<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 13.3.15
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
			'store_template'              => 'digikala', // digikala, snappshop, basalam, torob, digistyle, technolife, modern, midnight, minimal, bazaar, boutique
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
				'title'   => 'پیام‌رسان بله (Bale)',
				'token'   => $bale_token,
				'chat_id' => $bale_chat,
				'source'  => $source,
				'url'     => 'https://tapi.bale.ai/bot' . $bale_token . '/sendMessage',
			);
		}

		// 2. Telegram (تلگرام)
		$tele_token = ! empty( $settings['telegram_token'] ) ? trim( $settings['telegram_token'] ) : ( $cn['telegram']['token'] ?? '' );
		$tele_chat  = ! empty( $settings['telegram_chat_id'] ) ? trim( $settings['telegram_chat_id'] ) : ( $cn['telegram']['chat_id'] ?? '' );
		if ( ! empty( $tele_token ) && ! empty( $tele_chat ) ) {
			$source = ! empty( $settings['telegram_token'] ) ? 'admin_settings' : 'scraper_connections';
			$active['telegram'] = array(
				'title'   => 'تلگرام (Telegram)',
				'token'   => $tele_token,
				'chat_id' => $tele_chat,
				'source'  => $source,
				'url'     => 'https://api.telegram.org/bot' . $tele_token . '/sendMessage',
			);
		}

		// 3. Rubika (روبیکا)
		$rubi_token = ! empty( $settings['rubika_token'] ) ? trim( $settings['rubika_token'] ) : ( $cn['rubika']['token'] ?? '' );
		$rubi_chat  = ! empty( $settings['rubika_chat_id'] ) ? trim( $settings['rubika_chat_id'] ) : ( $cn['rubika']['chat_id'] ?? '' );
		if ( ! empty( $rubi_token ) && ! empty( $rubi_chat ) ) {
			$source = ! empty( $settings['rubika_token'] ) ? 'admin_settings' : 'scraper_connections';
			$active['rubika'] = array(
				'title'   => 'روبیکا (Rubika)',
				'token'   => $rubi_token,
				'chat_id' => $rubi_chat,
				'source'  => $source,
				'url'     => 'https://api.rubika.ir/v1/bot' . $rubi_token . '/sendMessage',
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
	public static function send_message_to_messengers( $message_text, $settings = null ) {
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

		foreach ( $messengers as $key => $m ) {
			$body = array(
				'chat_id' => $m['chat_id'],
				'text'    => $message_text,
			);
			if ( 'telegram' === $key ) {
				$body['disable_web_page_preview'] = true;
			}

			$args = array(
				'method'      => 'POST',
				'timeout'     => 3,
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

				if ( $is_success ) {
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
	 * Retrieve all support chat threads from WordPress database.
	 *
	 * @return array
	 */

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

		if ( empty( $message ) ) {
			wp_send_json_error( 'لطفاً متن پیام یا سوال خود را بنویسید.' );
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
		$customer_msg = array(
			'id'          => $customer_msg_id,
			'sender'      => 'customer',
			'sender_name' => ( ! empty( $thread['name'] ) && 'کاربر مهمان' !== $thread['name'] ) ? $thread['name'] : 'شما',
			'text'        => $message,
			'time'        => $time_str,
			'timestamp'   => $now_time,
		);
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

		$send_result = self::send_message_to_messengers( $formatted_text, $settings );

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
	public static function ajax_neshan_geocode() {
		$settings = self::get_settings();
		$key = trim( (string) ( $settings['neshan_api_key'] ?? '' ) );
		if ( $key === '' ) {
			wp_send_json_error( 'کلید API نشان تنظیم نشده است.' );
		}
		$mode = sanitize_text_field( wp_unslash( $_POST['mode'] ?? 'reverse' ) );
		$lat = sanitize_text_field( wp_unslash( $_POST['lat'] ?? '' ) );
		$lng = sanitize_text_field( wp_unslash( $_POST['lng'] ?? '' ) );
		$term = sanitize_text_field( wp_unslash( $_POST['term'] ?? $_POST['address'] ?? '' ) );

		if ( $mode === 'search' || $mode === 'forward' ) {
			if ( $term === '' ) {
				wp_send_json_error( 'عبارت جستجو خالی است.' );
			}
			$url = add_query_arg( array(
				'term' => $term,
				'lat'  => $lat !== '' ? $lat : '35.6892',
				'lng'  => $lng !== '' ? $lng : '51.3890',
			), 'https://api.neshan.org/v1/search' );
		} else {
			// reverse
			if ( $lat === '' || $lng === '' ) {
				wp_send_json_error( 'مختصات نامعتبر است.' );
			}
			$url = 'https://api.neshan.org/v5/reverse?lat=' . rawurlencode( $lat ) . '&lng=' . rawurlencode( $lng );
		}

		$res = wp_remote_get( $url, array(
			'timeout' => 8,
			'headers' => array(
				'Api-Key' => $key,
				'Accept'  => 'application/json',
			),
		) );
		if ( is_wp_error( $res ) ) {
			wp_send_json_error( 'خطا در ارتباط با نشان: ' . $res->get_error_message() );
		}
		$code = wp_remote_retrieve_response_code( $res );
		$body_raw = (string) wp_remote_retrieve_body( $res );
		$body = json_decode( $body_raw, true );
		if ( $code >= 400 ) {
			$msg = is_array( $body ) ? ( $body['message'] ?? $body['error'] ?? 'خطای نشان' ) : 'خطای نشان';
			wp_send_json_error( (string) $msg );
		}
		$out = array( 'raw' => $body, 'mode' => $mode );
		if ( $mode === 'search' || $mode === 'forward' ) {
			$items = array();
			$items_src = $body['items'] ?? $body['data'] ?? array();
			foreach ( (array) $items_src as $it ) {
				$items[] = array(
					'title'    => (string) ( $it['title'] ?? $it['address'] ?? '' ),
					'address'  => (string) ( $it['address'] ?? $it['title'] ?? '' ),
					'lat'      => isset( $it['location']['y'] ) ? floatval( $it['location']['y'] ) : floatval( $it['lat'] ?? 0 ),
					'lng'      => isset( $it['location']['x'] ) ? floatval( $it['location']['x'] ) : floatval( $it['lng'] ?? 0 ),
					'province' => (string) ( $it['state'] ?? $it['province'] ?? '' ),
					'city'     => (string) ( $it['city'] ?? $it['region'] ?? '' ),
				);
			}
			$out['items'] = $items;
		} else {
			$out['formatted'] = (string) ( $body['formatted_address'] ?? $body['address_line'] ?? $body['formatted'] ?? '' );
			$out['province']  = (string) ( $body['state'] ?? $body['province'] ?? '' );
			$out['city']      = (string) ( $body['city'] ?? $body['neighbourhood'] ?? $body['county'] ?? '' );
			$out['route_name']= (string) ( $body['route_name'] ?? $body['municipality_zone'] ?? '' );
			$out['lat'] = floatval( $lat );
			$out['lng'] = floatval( $lng );
		}
		wp_send_json_success( $out );
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
			$shop_html = '<div style="max-width:640px;margin:48px auto;padding:24px;background:#fef2f2;border:1px solid #fecaca;border-radius:16px;font-family:Tahoma,sans-serif;direction:rtl;text-align:right;line-height:1.8">'
				. '<div style="font-size:1.1rem;font-weight:900;color:#b91c1c;margin-bottom:8px">فایل‌های ویترین روی سرور پیدا نشد</div>'
				. '<p style="margin:0 0 10px;color:#7f1d1d;font-weight:700">agent.php نسخه ۱۳.۱.۳+ را دوباره آپلود کنید (JS/CSS داخل خود فایل embed شده است). یا پوشه <code>includes/storefront/</code> را کنار agent.php قرار دهید.</p>'
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
		header( 'X-AMPHP-Storefront: bare-v13.3.15' );
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
				'version'     => '13.3.15',
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
		<!-- AMPHP Storefront v13.3.15 -->
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
		$parts = array( '13.3.15' );
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
		// Inline fallback baked at build time (v13.3.9) — single-file deploy.
		$cache = array(
			'storefront.js'  => array(
				'mime' => 'application/javascript; charset=UTF-8',
				'gz'   => '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',
			),
			'storefront.css' => array(
				'mime' => 'text/css; charset=UTF-8',
				'gz'   => 'H4sIAHQGkmoC/9V9W4/jSpLeu38FPYMGWrOSlqRIipKwg3kwDBjYB8P2g9fGPFBkssRtSdSQVFXXEeq/O+8ZeSOp6uoFfAqnu0si8xKZGRmXLyL2XdsOj9Wqr1dFWaLrsA/+jOrNLkEH8OEqJh9nUbyptI83+OM63aHwqH3ctzVpp67rsI7ZN8cX+ij5YR+URVexZ/B/7KMB/SSvhSX5YR9d7gMij+VRnua8k2PbVagjAwrxD2+/K6rm3u+DKL79ZJ/0p6Jq3/ZBGES3n0GC/+9ejsX3cEl+1mG+YI+dUIFbW53wg/LNoka4l2FoL/sAXV+/0w+KDhWr5tqjgX+3JG/wVorq0lyPRSdaKW63Vd0Sav6v4tReimXQv/cDuqzuzTIg357Rin2Cvymu/apHXVN//GX5l/0R1W2H8D+KekDd49j+XPXNH831Zc8mjnv/+bEmfd5uj6rpUDk07XXfDecDIeCqODcv+Nfm5TQcyBBWdXFpzu/716L7rga2OJTtue34p5z4i8OxKH+8dO39Wqlvji+LA54dphRpcx+F4evpcCuqCo9JkKkszuX3LaZ98E+BfBHQcYHp9IaOP5qB9r7qL3jbncikiuvQ4CEXParErILiwQbXXE+YLAObV4XKtivoXK/tFcmHj3fcwfUBpyreK+9dj5u5tc0Vk/LANw55G05Ua625vDwuxc/VW1MNJzLXb4eq6W/n4n1/PLflD/Xg9XYfluK3Hp3xOshfyYDJfnGNirZQ4s+L5orXl3WE6fs9inO8eZaUlKTjYBXEeNti0l2K7qW57sOguA8tfX9ob3i3PcAszri1olu9kIOAz+D3XVihlyU/tEt+poMk/bb8c5lHYYzEDiBHkG0UvM3Qfp136MJ+f2MLvg1Dsdz7LV7hEIwAHwgyCUGi+ox+Hv793g9N/U7nSBhKfytKvA/Q8IbQ9UC356rBO7/fE2aB1+WluO3puSWvr946/Cv5A3Zzbl6R7KW5ksmuaGee5jLcGiAOZzMxEnuAsIS+PTcVYwpxmi7F/+sYswZ+1DhX2e12uD1BA7LLI3LMAdG2MaaaOeBgXWHeyhaYDIgfIDo2rf0U7zJjtAd67hkLCzEP4zPIw0NxbS7sGPT1f7+fexRE66zH+7FurpgOH3/7gd7rrrigPuAPPMJvD09z5cfW/jIMcjxFyr4/htb1avihz/X6o9f3AF1SvHld69Pi/dAM7/v1LrVakUef9EK/5fyZbIjHre0bNvOhKX+8H/Cbik0JFszZ8h94a1boJznCceigLmellHup3aCYHn1goS8Dee7PIfuvOAwdZtxsPOqpYB33AcLc7NC+oq4+4/dem745npE5m3XT4yNywcQYdBLj/RHEoeoqSsCrigYdOuNt8Dre0QUzGbkyL11THcgfmNlf8CcDwgM43y/XnvAfzPUwCyIcKCZ/LiizoX/IA8puV8eSiqNBjkUQwtsiI5sAUEp9FayjnJFqyV9Xn4zzEHOCAfm9x9yvPD3Ei5gh13t0rTjvXHGe0WO2POwlGzUbKehd2lutjKxdYFJb0CIzSZES4lFShpyUtNkjJk6lnx4fjyRMhzTIWApuXF5UcRjC5lbn9qXlnCeJFeuh/9Z5j2K8/anDRxA3q+0X/C9MeW0sznsjWkcpuTlGrqQoSdmdRO8idQ9puz/htBFiaFLB7UOnBHaOuZWmFsqiziZR1KH/Nqhj8Pkdm6MasBQeWMuYC7cPuESEsvvIeCI4RQ9xp2skDFPz8t3hy/eMBkz2FTkDZGOt1mTJ3k54TehnCA+CzFUxglNTVfiqpWKT/BCdz82tb3qxfFzMpsdCiHbrOIXUlkN7lsBikpBy8oYEj9wEGciC48vFulJNKZXqAwuNQhmm0OeJoU9/88T0bvLMkj3wn5vLre0GLM0y+e6Em7evxEQTcvhIOYcil9mGHHBd+jTmMTVAvWPPyOC6ZLG55XJMUL5HsjjLskq/PUP8E40KSOSa3Dq3qBzBmgp0YifWuyrNtV5QXueICQCcsdv33hTLNO8QSnh6JThZKKW2kBqSEPSNl+bl5Yye4KdTHFTntxp5mYrMKbOpkzBLbKHVElOgLCJbw6c25XyStgd+52O2P1ZyjPhQ0iS2SbI/ke35cGmNwBRg6ZrsOylls2/pn6tL8/M7vox7LJYvzeeDDdZezKkvwKjIYWhvCAoEYu38Q9T0IG1EjvGaFxX5Y+bA6RLdsFaIP7ApGaybsn3Yd4HGn6YOv2u7bnJwweXOCw6O5lZc0VkdtuKINxzmuVTUJmrKGdUD3rId2/65EO7qtrvs6b+IWPlv31f42UXQY10W/e/vmPcv1GOrFr/ccAtFwA/E1FnWzpt9XKYPiOOQCnYVBkxsJVqPVE7CA5WmmzP5hV8h3I6wQq94UD2zI4CDx18F0omcM/hMNQs+dJ1ZU8oArIpYC9IYGgte34JVEGUhNRfMekjqR25bkr0n6MpTdcC1+GJVyVN8UScOJmtV0DuC9OaKjElw2vn0dosWPnVvNqNJQgej0Y8/UZHjHGhp9czTyeb95Ilk5qYHkya1C0xZ5b6JYxCqK//Q3gfCQiyjF+BG4GmnTRDahizjUAaMQ1R1NbetIX7Yk9rv6QV5as9EveW8uIiKTZGbPdktrMsz/pWzuhiwutgmbD7jYubdc3MznDmTzOG97RuNfSkKoUmTvOHrVdfePExXmQP/iey5BT99oeDE7q3+BCtM9DXD3JBorgkwQMQJ5T2csoSvZOHrabnJqaFFCqj0eAq2kqptQTYFNFr9FzxZzOYyoFAAMtAT9IsGRdFzzi11dF9C1SKdlHmdwxK2Zo94YDZo9gjbJOOdY5hJ6BzqDhhiQrcNBkixlh8AEMSl4GrWdnOUru28xT+5JsSc7pejEJDBMUw8PZrameNOnziqVu/UdA+IABlje/x3fLWt6mbA3P/V9fL6dtKkr8hYrwsaCk2x13Yo5Qvq/mSLR1cr1pk5aSZYD1ABy8cUMK4AfFq/tfsuYd9bl61f44Eak+uaEj3A5iIsaA4h4G6VWpiDOrQDvBTdOH12cpDmEXQ0hoenTTnL/c0xBjqiKeg7OTN03sxY67ZTPgvqPPKcUYcRFRoFEp155f7t4hsvaUVdCPw2gkPFXGd4l5bLKBemXnuQcCDJyO7hloOZpyR3sjRrhJ/nv7k4zljQ/b9VMRSS0/7Lpamu5JW/j2uNf47qeLfZivmhFG3RURcs/7zZJlEaPdHPLyusz8yJCZ5wSuExiolz5OsnYfcVRVEeb58eLxd8Nap7GhlQebriHV6jyaXcxNskkUuZ4+OADCpExXaD8ud6ckyadfRFTRu0oMP++NsFVU0RfFfaYbCl/puHX6uvm5+oUtKlx4WmC5yUH1CZk/6LdUWFEtWz0ogtBY22JIQC2wNKpNBZ+qKz9XDht9aFQBgxNCtd6p5NlCz2UsUQk9OUiMlU4WYOU+FfmuPtySy/jGk5Bk2ujsP1Gf94qlPFsIV4nSCAz5s3ki6wALPIOhIeUYcUY1l1jTl5dSnjuXVZdMPDrd8aQxPIFbZqYksYTc3ql+4MuM9MRcW42EPl+t5a7fAepT0EPjDXNHgsqhcEpNQISOL032qZU2uVmQ1/pp10UqAy9uKYEG+QPlAzcWvE8sgJ6XHE5qLaDtbn41lzjMx/18FLNho5PSs/vwdz5mSeQsWnrVyL18ezDg9dTxZyltoDzOJp+aM8QuOG/EC5VHNuS946Mt0LeikmrRyrIIZWDmXLpHxUQ0fl1JD5ywaQ2DKAxIYBJKo1DVrYODbhiF3DvAu2xGSSxLbJRAfskEYIXkfZod33aUbulKEF9lPfzfghSK/bHH7NsiIhDA4b29hNkdlXxYjdQh8645BL7bM1OUKvunSnudCcGiJ9vcRPDyNWOuH7nECC5YZpyaGd8XPRtQ9weiIH2CDTeIqp4dse0DGsRciwFlERFTHCf2dxtEHUsM0xgZo9W2E26e864DOzLKduW1W0jusuWOd1d7BAXF0rNplUM2NpQicwxzmq4iiX7drgFEtYhWBucmXKc3G5fScIB7xCy3idv74tI2bWXUwjLjaSu3UAtUB6oeuoROAkVwY1DrJD2hGgOAgDdpCl2ghSsGVWNSqGe4cMmVFHSgrebr31sDGQUT0HAxlNYCAzcfzh1s8c9gA1pL6p0NNXGP94bKNnFOhqTyHMF5rPVZ1aKvA6BU86UHYD25SrY6kxMiT8yOmlJpTYbUKx75pNzO+ajbkTjVEF62PzoomCub2bIn2YVhP9/ajZAKH4zpmey/pkrGl9LvqTxtAM36wPkMzBX3WdHbOjy/nu8tGy+yYz2cRXQY3HTEc7iextLibUWahSilOdh85nyaLvB3iIV3Nzje4rKLOl/MJRIj7FrHnMdJipNwX++3rHHTflfiiO9zNeEvx7b7E808vHRPNLQSIP7Htr5DzmcJ0DLKgRHwBZ75r+azF6bATINADXoyaFpmVc5k5QFB/rV/iPTEUHkIlTQgCHdd05YYEm1nCC9dAMZzRL7ddvrchYiVVfdu357NqHml9l9ZPZZsTYyMhi7ghjTRDINdtCJA6Df7jqr8VtNbzf0P5ncCmuVTG03btzAPu9COiQ7QmntkmCkVe440gX3+gS67vEbJJEDzFvOLuDczkvNgV2GCja1wcG0MV/c6MhhCwBzAgnATYOCPTwYKn8Trnw4IxdsXxycO7cWuDWDWJ2ykywAlRoCqtFn/uM2g1M/5lO04L82A2uj22lnAq5YwUNT9g2djkU9MstSaWHfx3hp801EsdC7DRMBhl5RFuicuA+PoAHCHaF8AXc1NBgBVNXUByjvnWQI2ZS4Bv1SOwcqpCj7fZcuUAQQLswo6HotIYTXo2XkxMxMXSYAVo8fIZA36EbliO/J8uoxmqzYDKqzRW+VU6P0Xcz+q7xSqB+pTqpIe137dv4C0HVvD7mO2AhXmaiYU0ygoIRR0u7xDN/a/Sut0xl05DmrdYyJdGXWDn0azY0pamxsANLYPT4/cCZAYRYN6XHHu80NT8VjTAagvAMHJUO+set6b/4qJAmnzsp+I0vXnCnlCj6CtZXTafYPOlwp02cH1OqoGfj6/B3HhHWUnkCfBWHv0v2hz0Gp40nMGMMNQDwXLwdOigsjThCADJb+fB6vmVMKe6I2qEdcgqLgR6XcaAKkWua+1yHzgcYhcPm9mlINWu3GFa35uyiVj4i03LUbuwQaaV5XzbtElctZwB58uHwUo1ZQrZ+ZcWzNjMJD3BoZFzTVH8Ca8+FbNo4YUqWHq9zPMjNKA/DklaPHtbna9x6v4o8fA6zN+PJeJwjxoojqnc24+9sXO8kjxlM23gnHX8nVe9QXYRzoRVAMdPNygO9V1hlwPdZ35RspeMstCOUR7aQzdp9gu+IOOQJWPUHhXisZEABsSIidC3D2HVViH9ic7sEkogeOdB8FoINaeCjZq3ObRGi6G9EfaHysgr+ZM2MWtW5bqM3EDnND09jED364wbAYhWhA70LpfdRzPt3fEElCzUMgeeyXG00fcVTMlW8hi4bsIBvTX/yOPNy4aDNJVBkIszSEQDPDK4z8dqWphQZYZr0mgggXD8x4prIfNYSW8bNV+weHtryh2Oq3AZEJsm8lbkz6UCt5AQW5zbqO88Bnn7DrX026lAfOoU4+pdCDU6+9gk3/9YJw4T2JfdQ7ZipUAX3pju5z1eatUD6FwPtOhphbjw0gotYqlksAcxEvJqC6a+BbmnnzOznkCi5J3Tc1pGacYBfYtww5wB4Z7zecpsEBc+uMAOGkgGUvoRvgD5NbBY+jwI/mk8bLRjYeDo42hmy4lVrj5i1kq5FNK7qiqfbEdoEkCDxOpq4rTEnaN0RvL6U9QH9hAlMSfIuAX2npRfxhBNQCrtAVqKD9cup7cfc20Cul487UE8oxT+ZfAyTCk/o/Rl0KniNt183Z0zq/ZEypivqe3J/pQv5NMVKg2tUfnF+eegmDCOuY8e4vjOail2JdM3YUu2j4J+DVTQCxGaxF9mIoLYmDpmq6E9I+SY8wEYGYD4lghvkVHnRJ4DJIKPk2fO2q3lWQDzb1gV+kZ62cXeIC3OolFMRoC9DJEXbiO5j4InKfegoF6Yw/6zGlDttTWI8X66biob3mITF8YwqibhZJ8IhcG3JBsIslaewqtuWsBJo2IDGmJgt/cjGMrBXpqUHdMK0t1nYjIQxJsmcYgGfYy2BrUn3mpn4wnX9w9dvS/DLuXmM7FMYPqDfeFvnRuaN3s+Pc9Pj14f3MxIhsfxmlXsNvlBwfjNmemhv76PIis/C7NjyQrUa+DmBv3zOejtYlN9D4AVhXNrjyrR6HAz0sxbdHQoJN4TJlJLwc5tWg44QAlEkXzaWMW6updUC8K0SvgAyW1MtSWCD4KZjlgCHNDAvWT6dPcPWMAEptHFx7rWEn03vYfHkJHCWA0op4VMZUMokONwHJoiJhhf6ohY0EoaZ2hBY/9ZRkP+1qJCyKejgRvKdBm7UEIwMqVh1xRtMc6Vg+a79GIeRhhGlGfR28evbYiyh2opxYAm7ITGu2axANjDR/4l3OfrXYB1nfVDej02JWcAfDeq+r6NsGS3Xm2W0ADNa00P1EEdLtrS6YuKI5v4HfIEs1oNDgDUyskcJIR3+XwqOpdBQ15cED2q39a++tlaTjanxfmXgbpRIyWtmojZzID5Lvu8W469SJZSrknpgsy54CqmOv0YunIf1xEzG6PGll+e2R/OyVFmGBybpz7Ly2O4MYHgmqPTZocpZ4gxVNnLD+RZUDNrjDJEjASa2DFCF/tuGKdjORR+1Zfu6HGQGX2Yufm9AFIwG1zR22Lqj57uB/zG8P5v7UocniFZE/OTsdAmOTeU6PHSuQzsU5188/368BFeNHPgrfgdWxdl3e6nbIgqnbZ9yv2awbWagVjdNTqMRKHs0kf47gvTfOpD+U0gky1xGcNEBPVb8lsLCly1N2DEI0BbARj9bTSAKwlqoCCFogRw7h2UcWo+ycEaGDQ95cfMy965kdKF5pqkvxafh84ZIBj4I9TW+gxeDBRIXCAILD25gkMw218TKOH2gHUHJxobO7fGuK9SXD12ktFKyGGpUauVoAXRlHhJ9Z3KKRLFUTeHWpzeRW6akPFWG0hmG6aEtsL6mp241wizpTQ80AD3acsGbTkDT0SYMw2cCyx1pm9ToHq4cQ5q4y4A/M2HKkbj6x5Y9sZwTlO4wm1DiDC3KQWgRFwxk3k86m3X7Qw/wph4H8D3qDPOegoiXp4KAEI/mUkm6R4TuMN832Ud+7Q1ECIsEqKEn6BBYiiDEe8Zdlz8ZsgjOtL4AIQ9DEZ6JLNOIAoRxKfvJ76Bkr3/7RvLBeskZ6eSMkhn0tCPhaFYvnigcxh6H2kMnkvprG45EzGnXkJWfRbuWyDgTsFs3+Qx40pPqlX2TCYp614J8518Lqp7YMvrYDhnZWL8k56hBXfqXflrfcLmk5zNAZj6/H4/UFSU4R55+010OvtTA4xa7NHWa7Fh3q0sFetzF3/TvWFBotboZF7Lnqf2Z3CblqTlLvIowCLjf4Bl9LPQ+9Vayq99GcNmtRLYXyvNkrCW8zf0PbjyZcc0Hib3zYQ1aWBE7Gdjtf5uYYnkDG5m733qybCv0mMzymhj5blKxPTh0/d5ggeHa0t2/lP/SdlDsMU7aq20ErxnXMEz7qKl3doCqfqwsJxonDsvWrcfxGJmO3AP9x70dkHTnMcHVZoKCbWi9Qee5Rzd1JoIVE2a4/ty/rCSl/txkOaaDFg8CdeSsG033Q9deXx7TUEj1CkF4U7JTw32D1cRGe2R4v5EgI5UcIZ2sR6GJwyCBoR0HDL9k1GPDYF478K2rrgK/flRxBTXqNeY9D8baaZZ4hjgaCThx+Ll0bLlmGWFbyQx/4V3fe9RZfZOcWs+5vXhPZG+bHdHLiaWbcWIytVxVnzBzcd+w6gaUTGG34TNYOOsecwXUWFacDtHfmCNJz2QA1aWcYlBBGk3fyPd1W977xxMphMnPRsmcESBKj5dzHmTewE6NrfszUHp2zzmyCxE9QKQXAhDL5VPwSQ7TdLW/U83DsgqjdSvCJWHui0D+ynxD2itkifr9n2gG94ADOv4U/InlAgrYX39S7rvQwExr2S5CULmB9kLXn/5upEPS0v6oR/knB2ehCZ7xXD3NPjATLRkACZjUyJmHKAmFtwdmsV/OSpMymgtFzyA0FqV/vHcveEGdXfDvQBfxF6RY+Y/fQjLmYymDYIyzsVk+BU8GMGjg9F8axr1xzDVMHyMpbnqESRMyG7SzwNeYgqqyXIwPxU4GQAfkYgVZIlmBWTAL5oGJMrGxAbTcoHH0FWugaVRzEMyslUkQM8UH6xhjG3s2g6pWhg0BkvLmkzaUO82u4WEF0k7Exp4CRpC6b6dZZ9iK7MVbs2/wBImX9oP3jMof+CZ27k1yTXv2JrclmoUwWkyKd7L/nwVBxGEauoC+Kf2QZgsWELfzvfsehSDq3ciqhHL8UxjoBSu1eGHGvPDdPzVjfZ68VhsoOBeFcwrOHX4975SyxMjBUKfYTCNG5Ijh1Ftie3UylthObWs0A7Ub4RF4Iv+sWU3H2cWlOM8fKkP4GO2QO1KPcFDUIQtLC3gtk5zAIEYSMeODBfQfs8qR+ZWavKZSGYXLaOKTz1bEYDPEWmj/Q+c78m6Q08RiYqTuBvs1VqxxQr7UKeop2yS4Dr2zAv4a4VJrVoUuMLGx+rXG71Mql/CW/koQg99h24vxij2c7VlFOB1Zr6B7jbivQ6nQ8VkMXDzkN08UqmzD/LwHRqpAO+2KI/uQVewP6MPTxdzg6IwsTonhoNuk8yIxtSaVhfHgcKQBN5nlWAOZnWgIqpG4Se+KrMdjZmpmKx5zCqvgNiQQeHbqziugDWtmpR5jxw/o5gnKdMTQ6q9Zwe/bMagcBzLOmLC4MKdzU/kGBaJ8Zub7HgOQivZ5GOXYwfKdqem6jFgbIfhdqZ2wSrHrnYw1z4zTRw/eB2BWj0+SVk/PIzi8CcNInIE+OSyXCQdj+OANP4OAejiRJhrrdRYEVUvfYjEMnaunwzasFubKKUY4wozM/lo36/qOJQM7REF7CBQelh9pkeL8M2lP02IVRk1pbvv5hC1t56oI46s6I6zaWiSqFnQa93rinLh3zJ5Z2lw0cH3znH1uvAwvs9fNkG+izC3fnJqr9Ois2B1tudns6CmXy+QFb9+34t3c3VBDfTPKDzi3ArtBJrOFUF+pNwESWSgftO7lTQYLw2Dgsiz514Qhz14Vd9D7p5cpcSzTyxtXj23Ii0rL6kOVvLwxEOmszCqxXCn6klA/9FWbisJwCD+yPapr6M3BpBquxC7jApIL6oh7a8rWZRmMcyeCS7x1Oz2RFWbGBYbQc9lipKTUsodm66OhfQUa6d9jG2QFu5pd+kczR04WAYJdEDjdEv6+VuSeU6FnNOXX/BI9sneoX3s3uF1TxMbXbtLfEgjrGq0pwmbjBwQAdtd44Zq5+ylREF363m/D6bar/v5lKaBjiOcWdi0RDMmvFcchcSVHZgNzWl8cUGk9gGvVo5Kkuh2tVTNj5SQGkpnY5MBMyFyN6lilgj3uojIqXZkqUVmUxbx7dQws6ACJOpSbOyZJ3wP9g1Z+lHgXMiVWPdHORjsKBouhTLjJuGFyTHkYKyTHK9mbw2a1Tz2YftucbBgrjHykvHSwkXPhmWsh9yof5rgNNSY01Jh4dkPSPsGAIdMcxiYhjyuHEYdEDpwwgEHtdakrTeP+Br8iVgwS3fGMsy+Z4+zTi+bcCpqn+1+q5qX5UZyLVYeqvz8Ai9gLz/oBSowx+Zhl2jUFSfwNSdhRx+6OSGrRG6YV5oxmP2FYRdvQ6icMj5u0dPWDt2ldb9z9HIseT+eyImCcs9kTSZyMr0BrRnlaxLlnRhuE3D0NbdceXXSrNpjJ1VYvxy1ld65eKlSiwr9AFHWz6toeWUu0i1C2sboq4yhPj86uSpSg0jMhWT9pdTzfrb5Y7SV7mapkW0SeZSJVlpx9de17cXZ2E6fZBh2tbqIqQZVzjRBeVbOal+jmfP95795Xt3t3O1s9bctNgSqrp6yK82rnJF5ab3w9FZcj8S63Z3s77LbbMLO3Q5Juwp1n0x3NSl2imxvq+qbATOre/ePeNvaOCKtdkjtWqd5mGXL2FtZV7dl8orbYqrn+MDvKNllWR1ZHSZ1kKHWuE7n8PdSr2w71g7XjsmKTFPZWSPPQw4DIXBIPA7qTkCaLI+y2m8heHFTgPkrP4myRdPUzd3R7I67QyfztpvodLwJYK1no1an9IXmUhZPCfoHjZAk/xzcBcTJrn5E8R9oHIC+e/vkT2SCmTbyyVZqRxpVvxlfLjTDXv6t3lxMseOpJ7Z7zD8kYu6/UHttExxdysFgdQOZFvnc1yXgU8Ip9BznNfSCqHqr4JvzZriw2Bb8puFwYiGKJLsIfXxaHJ4iolwYESJ/lnOfZtp71KMPWzHqUSD6zHmThwcsnih4S9MCs50kNm3mDbeePV7oaXQvHd4Z79TznCEZnz1srHfUdh1n0TNFIYB2fQRfToD77FWBwn/2OMsjbFTi/qKgowPWdotk7mVcnmPU8ZDj43qmL+jhvZEreH2HGs5q6Fq8U2D5rvAC3KFIYRlVazRt0eWpuIyVgJ1oicPIz5LLc9+FLne9twmR8D28IPTGAAg1coTfH22b+Qac51WjNV5VhvH22Lc/tS2sEJ0bbuHDEmHxiCu795SgCyTq1ggJ0n4gc2S+MA/5KzBta//NWHCD65hm/P7c6MK3bzAaOxR9F0VmKnC1wMkUuicbkUNMEdfRW+OXd2gdiuhwN64r+vTByUGwAEekMZvTu3tJW5wkrwsYE9CWnxMK2ac3oER7SGJoZq21RTGQS4HVpiJJ+LLbJnPk5z5NYSgf6Ykabjvt9h3WjKPa9iwWR5h+2cl0d4+12a+uiKMpTt0ZVYaUtdwagjPQ6wXY5O4TmTkrhEm1Ns46nbe22fhgF4MwSdOswF6U/VCqe+w2r0iWJCHdVDJrVvcWVsdRvhpnNZIR683C/mrRyXCXh7EYBetocpwah3vxzMo8O7q3uooOX/NZiZTMXQQgzj/lNJ8KozD0bqQOA4emb2i/7U3v7LdZLRzcTZwjwMTaCeQ16VoyP9hnmRM2sv8XAanViHgroYRm1H/wOw6zXRCGlZNqLAUQdrVb/Owytrn7GLn+iTh03m+cbCf4i834/Mwyoc+rMYxNvk8TMhDvdoI8XUWI6HT/rG7oSnwnzoY3JIhsui6Q7FB6FTLLwgiSxKgSKCztNTEvhXhm3oqnHrKCW1WYuNo/5+lReZZkTuOj1lCfMki5Izy3utrh8xKJQjbTIUZqt3sr5DTty5EeujqioEXzImaUY5OYvz03JlFeVIpxHamvwqg/HI9PJIGmy+rVhwnH6rCEy/VbdHOYy+I3D8Ma//iuJrdNCtFzv69Y1+I0rL6nBePCjz4DftbigOJ8VJYO7WGmxQABR6klOJAOC0vArQmQcVUntZNceb/1UesSlMN0uHDDdzILpcmLw9PU8IHusRGoMCwZhkT5NZ6bKdyXZd9epomMCJQKki9tcdRnyQN6Ynx45TI3cyPOytY1nvQF0h5WBHcUZGVaaGgfCSg2/IHUz35/Bpcl3acjrrOomdsZyGOu8iT+V1k0MQWZ1A6GOACfHoirmJ3kjzd5OACIkwxbJN+e2IKPwF+sA6cpM8OMYWDtLtkl+dMT8ocyRxcMjgJIB0itgfkiFekWHhE2gZLRSsCqFk2E7gNYxbWYRZqY7I6W2NZIp0K5x64P3n6iRI18VWjKEydiAm82s5H0H2xRHulAp/MRyj8GyYrjtWIWYyZRv1uD1BkjyNjG0dJdlO+PrqzQiVmWcxZk6D1apjNQI4NNSCNkVVUYKP8oCMyuWjW1ErOBQm9GQn91keZsckAVvqV7vMMcMy4W2QzER3J8IaICJQIyYLhdoUbvb5PieTMwKYMO8/IML/Qdb10/9RBZgGyXNDrILgUbaN4ua+EL73PE+ZjMSgcbEhQD/aDcxK0I7GUY4WWvU4sqyB5on08C//cZLnKXBi+PJ67zQRmiEN9qxT6kPNShaYGKQLm1Zwpi+p3N3ljatRWc2No1L0UdZHRXe+y4pNsfch6QNVb6AW9dW93J4zK43a4dH2jqe1w/GlTsjpsYajULIww/Xc6MSpgtjT6fcscfkR8jbB4D7qQx4vKNNGgbCX+LYNCcMW6ODzEHOzxRLiEBhxB5gummWdKFPaeJkhj6VAvoE1FQ3GIzG7zlesJxcngA7Xemxa4MCjunP8jHKEAHpaNYe9UbD6zYYl4kWsKPKuI1KrRkIqt3mR8PhzD6KHA7E7uVYfI+y7TLa7JZxGi6xNLUYjbOVRaRFRAVX2i5Y3jwzop3aC+LFQg0tTVzzJFC1d16iIvGDg0toafpkM/zUcprwfcl/cxLWqHNFoBclVktXtwI3c2vP7y/tlRbKCL8ts+gbzVmwy9nfGf473X5bbnffgl30bUke2+Ln4oj9vonZ9zF7fLOjfy/kYFmsp27DIIY59cCpONfTeDz+GgXc8UmQf6uOepEPEJJoBym0E7yRepxpoWqY1VteAfTrH035A3UjUs9EMjxS1THx7L44TZfi/3Ucj+++xCHOuXU4O2W85VSJ4RRLZ2ISr0wEc+yL94ORemn0WhAiM4Oh2/IDCJqIjZpu6zNNcWvTNartHH/jJM5VzbX+8tDi50MXZd2SOp00gRSQFR88yq1ZYNig6WhyL635oGpe3ZOfMeMoHy2TLoUNO0xGJQfZxu55B0dvWJu36ojZhBlwxsoLycSYhhgngkF6VDqKr3zeCikCoXJdURXdQAn2MLdAI4xOJQ0NhV5OAJV1VSvVnOq/z5Uf9dleHNpisa03FaMdsaqTSraap1qNi/4+M4OqFflvhEs7AulljfL+1L4Rd+/DUS4Sfj/boMnzlmV23jLS3NCc0TSmyE7haRsKNRN1Pq9Uq0fun4ow5yWlnAkJ2BKZVCXTZC6TJfzIMl3NCBRPUgBo5/RYeNIfyaJjB0fVpH/7vopFujoxIOK8g3FQQudw6PjJDFusbRKbl/ZFjuZ6eUxacfTctW6bvWqxHB4zq6YZ9TO03HD9/TidOEtyy50uOWfO6swx0uTZfMR+CzPUMTEJpqlLWakJV71060VeEmiOMpzod6dRjijTwlqdVks+gj/GqkRnskp09otVorNJi9/WVSWaDrIrmvPDIVSRz1d92bXEF2JLF5qxe/VTz55P62rwdWVNrAiYhKScRvufwaW4VsXQdu/8S6L/sXM4nHiaAtq7TErIjCKZ2Rzb+TOzP89hq07vGeuFLhQIlZy+ZXTu6Kk7j9mrnnkl7fX5c9+zn6WNi7E89lc2J40v6hNledEcA/F0OgK9JMKMqvZC5QGda/XHRQpgJf2AJ/WC4jMyFiSpNJNguhKJzljeL8lnYCcWAmP+D2Y4cFE741DPK+FtxsCrZq/oLdBPph4wcTzW20oUEW8vLZatu+amWYaTORntQuF0nyFJ28H0PnVd4ZCjOuZqOzVnLiCnkDWlKWB3NItAqGURmGE2B6BbEkBnpOe/4qONN5N2dVKOJLPzW2K5MrrRW9FTABvcfuI3euQIrVNrQhH/AwBnqIms91xhubzCcnCFbQ7zM3DY2QtFPm7YvQxENEPeHcCKUKV2T1hLtI0VQak2xXkJPjk1N/Br3SH0sE7d07fqwRFtYRZnl91r6gCzEX8YI9KaUx5KbVLaQyw4GdCQXSH2CrJKfRTjhS9Y3/KJFGuewwd499hBBG7vJadKXi5AZifnDUdSTOvqiCjG5UsEpe7MwCSAwtWpvng5ZqsYmUk9aVa295soZ7sB0LH2ZnP+aTNANpJdiDSLl60a36DQS8GUeYe2P2Vd3voReXQYMJmapn9vbReY8vs7nFYZqCj3lTmF7JoBjAtihgF8PE9cx7GURkgLq+v9Mi6LAE2NxerDt8vrhFYG8hAzon1IoPCqa9/8E5AFSWbVSocThNK7EEBAr9Sy9jBzQWP2MRAPlz+12egGBdyOh50Lh3sGHO6ZrYxHwpAp8zA/rTH5Kk5OybLu0//ZxMt82nvca3E8I4X8pceGKRjXlmggeG+jSu1kEiI5jSBI3EKUY1+rRv9q2UKfBWg9hRVxMh/XkCi21kj+ZENidImU5YL6EDGlK4JZ5i5UsMPizJ3by4dddnjErbgQB6SY2guPBc0Fb7tC5yijYyjzkKHMmdSjvFN40/Fjpp0DQG9zbDxbvS3CxGg6qdacjNX5iHg/ZtqV+ZWtwdr5y1gmdennldKvG8waA6xF2ZEAp6k5jA3fpwlYTN2XXUwOQ6CQTLs4tJmPI+dmWsLNHqfh7uoNpxajg2QUCiFOQp+RZQ5EWcMLOGFUMhuwG9NJAXW4l2LwldWNpT5D//k5m5yzkjbLb2Y5+1xYMtOew6zb7GynOxOlvLqSSnhPSpr5Eyhcvl1VFm0IQyO2Zksmmde/R9KlyMsf98eTwxuB0k4jHrlkZk3EOPsjld9H809vrRvTXX5aH4rga5ogefBzOfUiVZbbQYkTqQ5aJTL03OjtOteit41ci1W1mXRPRYmFv4S8ziFFZo7MzJ7VZ5FGsjGiMFO12t/auF9FRwCL5tbUKzKCU94CaB/mDzMFtEwXwsU2EA08/ELUOLwyNNPyPwPvNMC2eKug/gnX50ZzfeoVzmGjvwIwjl0AY9i4NBZ7WHFiZ/cERYkNhDpp7+bWd5ORk55s0xTr384a9nKYxLXmlAq4dG5HZK2G4ui59aCp1b4Fv8RDQg8zNRZZJvvwYEfqa4OW8ozb5w/jIjxWVs9qOozjvoBnLTXJfOkIDF+VsfDlNOP1PSfEJ00oNTHUoVVylqd1VVXFbhTNQYpLA9+N0n/Oxa1He/EP45VgOC3NT0CR7tCojWIjffkFa7nHnAK71q9U8b9NyK42e/iwh+w5euAA0+efRG84qs7JZmwc1BewrsPXSU9qoO4s2qNClNGC5vXXThkkMNGsYcHs0FEo2+CCOx/PBHePbDdYn5qHA6BkYLqo6x9VViSCzuVjsaD88U86uIHgNurf1royvNlp9v+xN9uamPIrww89ruXo613LkYo10Edg+pcdjwzTuobyI/8mN7JjWE/5jp9zFhuuZkfnuvt46gZO9LA8EsK9Ot71mmwHo96jLPBoFH4UOnfmFXMnKl5bwsPcuo2uepAJhFJMFhTRp0+oKEMEnZST4E1V985sQotmWMfzS0OnSzeKcfTqS1TRWxo5RsWUpSityxzNX1Bh+lcLWOvKyYwSrQ6ctLf87TZWidqfo95mmnpT9XCfIweoUTzL0fmcfzbdLcYONdmSmjhrBptsNfcC9Rra8T0AKmJxYVMSUvENzoVLPr1wgI468EzADEm5lg/odu/KcSfJ80gBbi11IwW6co3/J9JKcTPN8xYkgD/81rZle7mgrkQ+lAGw4AX+ycmbleytuRVUuTpr5dGKaVgQCCQ63K9N2VaYFzdVs28wU8cLxFrFvLrBqi/xt2Kho9xjuf9+xpsW/94DwGkqBqcZrs04VZIVYfVH216o0sQFHvI7vm8O954MktUy0xRX7S2OKyCR9Opg9fh2Qd9J9byFB+MXcwi1bJT2Ss/DiBlapUtQuAzbLYNJ7IaEuuUYsSXTMeS+A1rhLrEsC4DTCRHa4LNt1nVmIBNQx5lepgc73zA6Potix2wDDeXJ1pj45wXJt8nAQvX/wUP8b9dA4tk//vYDvdddcUF9IL59hN8k7ww/hlYx0g81zaFtzzBxjhZv48HIuJxFYzlnEieKqNp5NXI75ibMF9aYhQVGOfKTGODeY73+bOwsXewAc0fFPBdIpIKZxsOFYu/QHamgZEY8+jgLnfV7a1R+DCsZLbfYgIvQSuXAszGwO0hqE7Rj/NGLTNozpbdYmll7L0/cpcXOG0SwaJvD6NHI80LKUO+y17cllwlhzhdRcPz1FKwCCu9duDLASMZFVBSi0LMxB3zobhYX4lPFJAqnKx2I1RScnAGE8nZjcV+pKNEvqq54UUxZ8U47WkztQvxfXo7na9yGoRWBRk+eS7TIEilajB+oDQjO3uT+5XKuCe3kQxT6ITFSRko66P8NJ7PDjbjTec1BXeuHYV2hZS0AFcOoVts/U55Vc/+AqvSksSfdh+OO93zMJM8qOynzEtawT23FwKWigKS7mLZ6p2ylqQuG5ttO8Fml683VAsVdPf5vOggioelRj1rhUnd5eSoR2CxNwwFhTam4sQSPJnBbw0yAEClHCbotFy95c18VJGmXf8z1cdMwSQcaZKyuMXnvVlzRee52NvpkySid0oD9GM9ZyW8JZ11xUIlgRj4dsMedyH3W+0NCnUJ3aJsnaZVjZLaXXcp1cLod6u9nrGwTA648FuRaM2zE+xDeUFFmVe+cTSSHaOIYEL6k9OQ+1C5pGUa9LHfKI2Ivk8FjZ24y40Bao59XV34Z8ICHhasx+6Dko3hiZfM3G6J5TmwQnNcCKRLhqOD6/rI0Yvet0Hhn1VzHSXHFxciOnnLd2E4J2Y5LDqU7QFccb821PPmyrsFsD/mcZH+2lGie23FByqvf0mFOZAwkkuUWyynL9LdJji69c0oSZGK3RRlgEqOZk9SvRfOYW0k+WmfeMqhGnj3RNi27+Fx6w4//9P8AEcHJBg36AAA=',
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
								با وارد کردن کلید <strong>نشان (Neshan)</strong>، مشتری می‌تواند مقصد را روی نقشه انتخاب کند.
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
									<small style="color:#64748b;">از panel.neshan.org — کلید روی سرور می‌ماند و به فرانت ارسال نمی‌شود.</small>
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
							    }).catch(function(){ btn.disabled=false; st.textContent='خطای ارتباط'; });
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
