<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 13.3.13
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
					if ( empty( $img_url ) ) {
						$scraped_img = get_post_meta( $p_id, '_scraped_image_url', true );
						$img_url = ! empty( $scraped_img ) ? $scraped_img : ( function_exists( 'wc_placeholder_img_src' ) ? wc_placeholder_img_src() : '' );
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
		header( 'X-AMPHP-Storefront: bare-v13.3.13' );
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
				'version'     => '13.3.13',
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
		<!-- AMPHP Storefront v13.3.13 -->
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
		$parts = array( '13.3.13' );
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
				'gz'   => 'H4sIAJYCkmoC/9y9e3fbuJI4+P98Cpm/rI94DSuS7byksDWJH52kYycd20mnfb0eWoQs2hTJ5sNvzWffqsKbkp30nbvn7E5Ot0W8gUKhUChUFS7DovVmmk/y/Sor+LjI0ioY1+moirO07d95dclbZVXEo8obqPjWdtTm/l3Bq7pIW3x5mXdOTni5m0V1AqFPp+d8VHXyIquy6ibnnUlYfrpKPxdZzovqpjMKk6TNmRfxcVgnlecPeUd+9/nsEjq0kwV3/DrPiqrs381m7NciuJuxJ43YU4gcPP3HP/6j9Y/WfybxiKfQ1y88HFUYU+AHdiKqqdOdaZx2zktIwtTNLL8p4rNJ1WqP/NZOOOKnWXbBWu/TUacVplErrspWOB7HSRxWvOzIYgeTuGyVWV2MeGuURbwFQdly1KrTiBetasJbu+8PVHRrnNVYXYoJWMXH95vbe/vbLaiay+hWkWVVK4oLAFtW3LSyMcSahqqCc+zAUwRNUQT7N9PTLOmMs6LtiVHyhE95CpBkm9GCZARZmEDq50Wp4yI8k6VvF6WL2T+ZwnAhy8HCBooMh1NA+scH0i/jiNJ3F6WPAOv4Nfbg08IeZsVVWEQngJ+Q5f3CTtZljuCG9C+L0qd8mkHazqK0JLy9gbStTKXFFS9CmAmD8E8chA+CIK2T5P4esRsmiy8FXkZI7w0xod/mwVYGy+JoKzu+v+dH3n/+p6rTO2aqVBB4qgFvyPtY0if0fwOIHsNyqgEqUd9ajaIDS70Z4+lfNa/5TgYIcphHgKN2Pp3+hecJ4PZ+9VCGfV7NJ87YYRbIVRyWZXyWsrcZLjYNj/0UFnDFUv8OERXnNy8DiMGAnMygEkGYtDJ4m4lATV0tgvT+/k02208tIhGXtHQ3s2mepYCOuOKdDKXsqiFP0KB/F4/b89OwvGzFaSDDjCzRzPnVpMiuWttFgVigKm53Oh2/36rCCw5rP22JunA1lpjcgqmJw9MEEqusJUbSyopW2NJguZrEo0lLzNLjVXQ8f2BDpNOYjzYmMoSx7p7nNwAyNpNvwUROSaNeC1FU1Z5VHuo2k/s1QyT4mpmmArtdk/Gm/HdgAWL8WRnclFZ7Kb+CbgzOqB4gQDUSRsgCiNk+Kx0w+AxyxeXnuuANBFrqDrDud1nwpijCG8hEv+ybRu6Htih2XQZ3o7oooBpalzP2OyyAC37TX+oyGAv+nJyUPFFfRKnh2wLjb5mCDtFtliBGx4R/LKOfAeKuxEikRwXtBwirpSC4zOKo1V1ebmcBRfms6kAH7JQ48LwVioVE/1smdtaKFf7y8tLvWWNQbYxuJ0fFcVDBH5+AEwZhcVYj+S87CU/PqsnqGnYrBBrX85POaBInEYAhSAc8gd0MknqvQ/8Ou4vlawHbduizcdAdjF+Hg/HKil8fjY9NzUfjlbXjgVVZPYN6iG+Q+z72sTQwCAM3hYU+9dsaO4VDGodYb3dPnog13y8KIrF9znDCYpqvjBF29hN2kl2lvOhfA2qJCZ7N9JRtRYKmPFhjB3+o2oqq5TgzsmouFoBqAHgi+rCqPy+tXcTaBTTRAvJE2ICQUY3DoIvC1PGGdiIEPVBIL/D6XtD1GPzAx5o3k8Dwnngr2Dei/u2nR0H/+OkZ00QiNb04So9nYtf5ngVP//l05emZQeGr0obHD7tM6ImBIfTSwx4gavarTpXtAxORnrXXn/tmKH8WYoEwWBtiSHGgmhgAckMzyFKN45RH3v09RQCXlvAw9RCVuVg5hMdZsNRDvFV7s5/h8ieULa/iajRpx/7dKAQGoaSeeH0KpPX0FNiSPuU+BXbgYkDxcnh9WdbMhqgEuDAq39qMqOgM8TnzJZgyoIFJkEAE40GBvfaGXsdbAWhmrOv3C/Yuayf+sJ1CCpNbEownDcyUfc+Y92T5qeevePCHAagSAhUU0LM41hMznvl+P9EVAZ4ltNYDQOiEpSvtpQSn4v4emJIMv6BTFDP0vD7OFAX8B1pf4T4Sn7wuJ1CvzwjQWQCE0Bpdv1gBFMSRQW5FHUIgCeFrLinLIATKcBcH/Cg8HgjiUSBQYljeg2wlgEHGNMga8GGmyE0dEPOluKba5pqoIR7U8kjhM2xwCVCHd1LYfNq+34lgLxj4cRB3LsOk5sy0CZ1hzVY1kSNkk1gguYUqkEgM7UjOQWwjsNMXvJUCCw9NAHkKIUKcQ1pE8VptOgP0W95Ku8J6jyRbIIofA/xkBGDbpAUzUbbuvBW5R2EQmuycZ3Ha9lgLJ2Xm9Sv48Tut9+PWTVa3prAmKmRLgKDhISSE00mScLHOAGqK8rIWnueAMQmRagOpLSseRsiMKNw1y/NDofYvsa5oWSkWmOavCI6OAdO7qjAt6AJx1EbT2NAPMU8pi1kC0J/5zCJsh0TYsKnOCTJLdQmgWu0pWgexBS9hQxgAT9FGfISDE6wBi6Q5JbvAd7sVIcXQMT2mKwxSYKz+1XrW3Hp85mZ28natvJU/awy1p2GrMqktcCDwT8cTtd7lTQ6FA4NRFWFaxjgQGfk2Cu4EWyTybsVlHgJJgw1qlzM75S1Gb2bpOD7r88RJ+iR3TIu5+Qt5RIeLhuzEQOORGNdCWed47uR09jXH8NZpDahYIlJSE4B7s9POpuIM7qZh3v9QMFjY2+Fo0rd5fURFQkrrrAIHlDxPbgRPqxkOmFCc1BGeoPo2ayxwSSPsXG0rKzCF1YxVGbE1TtmFZazNcebf3x8dz1iWJm5BmOgl2vrdY4cYvhp5B4u1+HUOixZgRkt5xONLWK2tEqhOIkUbLXnWF0vaWrkcJSIdw/3upxDckWf74HMEoc/ymB4cUAg4ZpMdeOvTzj4d9XfhpB/cYpZ9eaoO3mPo5GR/e/PL9sHJ+72D7S97bz7un2x9Otn7dHByuL998unLyfdPhyff3n/8ePJ2+2Tn/ZftreAtloNeB39l8DFKoK1t0f+gObMWkVkAJruoPKcdTAA2csZb07qsWqdcE14JJQboVhGFzOEgC3AFpmTFQ6gJ+gXHCTwLC87NB1pGLAuLA8HYZYHi4mxWnY6cTS49Flw6FDGc5QKWPbFYdsFNIuNERxCHFRa754KUAW55NR0ULI6/XszxY3S7OKqR469t9jnUfRqGkNLHZMEF1g8cCGpBohYfCGr/LpQHgdofqN1fHAhqOhCECw4EVmXh7CdY7oRY7liy24VitDNC/BEwbxXgszh1Lli3QC915bsRO5Fz9BW5AjgqOOE1jAAshL1xk2hIl32WIiyiqwzaKWEoMnQiJ0jUJaLOkuw0TPbCKVfkuaOqsDryETsi+tznmEdVHHAzKLVifst01E5IksJgnrb9lnVO4zRqUy+4pg4VgREP56beL3wczAmX3E0FM0vpm5Obz5+RPkVMMB99QYni8ityQ6rz50hfUNL2aCU7AI88vEmyMOrfyb2xv9pjcudDGJ3EaVz1DyNqBMV6DXlQs8ovkToNjoDYAafWr/RCENK6iqqCxorqQO+fC0DLk47ZXwdOCIVjVXFzx9twvolTWJTw7WQQjdTAcKH850TTRODHNiH7aTi6WDgQ2OgVObHzUpaZLP8w0jcKi4yQLktu8dP6jLA2cISDMnHMoWjUSH+ocie7aWJ7PIYd7WeGJnLaA3sfzeNno9D7qK1zT3MUscKW+S5Mo4TP7TCLK2iUkplVnbD5FVjHzw+jUcQez8cQdqLq56uy89v17D6E9Y3ymM8u94UDEwa05acAIzO78FhMBOZKjs38N0W2D5QRQk9T6iYdbV9XvIClRBdTP9fnuWJu7xet7gdqMlkFfl3yosRyXu9lZ73T89iTrCPvooJTYia2AxM1IKll62sUbEftbf8Hl1Sr5+X1agHbTDzl//surN4BENi36NFbq99/cC/1W/RDie33KHgX/QssamfuoMP+iv6uqPf0x6LedE5wm/5IovuQIHhgZMX+b5Ej9P0r+pHQ93HBa9UUvFaLBa/VQsHrt+gnBK/fIyN4/bUwJ5TfIwZBWArBaSa/SvzcMQvt14JWWhmYOPZH41L4O0fY/9qI/TN79K64hAMx3l0X/7uWX9smuxpZq/YBeyMw9TA4ULK5AyHfe+MPeB/RYdB9fTgQuT4Eh6u9X375pcdCHhwcfThGJOq+Ttohh4p8jAnesIOjQ+DwOTsMPoiTAYlT8ViqW07bB5rcqoZRxCLYsIOj7rHJW2BeaMfOpyQktKDoojTAQgyHkWd5m7D7EJYMDA8TgkM5GDGKLvVf1se+cugtjmrw4fVXLod6wIO1f7Q/rPR8YDrf4WgP+DHbDQ74So/9BsFdMfhfkvY7GKrv774OYTlh+DfI7w/bBI3fGOYMDtmHYNfvizjIT7VR7AE3oka7ikMJTruCBjQlDN4YUCXuhJaA9e8Bta5X35hvdQ5A6HSHh/2DThxBehzNzP0pEAwA1jRMR46Efz61k2ZXjhhWCu+tLANueF3MPb/fxlgLbK4k6L0TMvwtWDssDDKR9OM6ZMbVcCbPrkfHbIx/LoMeuxH0dxqss5Ngqccu8M8m/tlS1wwlrw5g0wUmy7mLN9HiMDdR+UcJD4tFJewEUSay2ng/nfII6cKSfaMxtFOo0EAWScPL+AyVBZz8y8s6viMJVpyeWRvEomQ4hr1P87r6DEezv51bniQXZPTN3pfjQlWL7E2A1xKDN/JSaEBL+A1tUXTCUVJkzKTw/408egH0XgcHlMYszA3eIK2PkUfPUszEqnbN3vjWshiIZi1KsyepB0039pAtXfgQTtu1L/vmAz50Wc7b+6KquwX9X17+vb3HrA6uHtit7IuFJxALtkZqbdK+9tk1CooR7cQV9GEwpeMhgikHGou4iZKSG91Oe6l90xjnL2/8+/sD2NQzOFX6mhbfaGgOzNL94C5Hk0etgRvY2eKsiKubj/ySC+oJ1PDDXKuvgXgCPN2l19bXL6GrtWI1FPI+XiulJHQq4C/DkYprnIIGS2lIb9SwScoFdFje1CkKTFNwwJ05OODWJAAAsVRP0cKvXB+69aI/pEUv6MI5zsutSMGZYV+DZ+wJfBgsRhgr9Zp2c/CrT15/te4tK8wLw7hVwxAdn4PZ4ElwILcpGCHO/5vgtg1Id2CEBG+G39qwQ1g9lBSROk29/2bNc+TM8zebJEbtCo5PelXJAru8LMMzvjkJ05QnDj0R3T4TyhZuPsar4IyT3tjaQH70Olk6FbkCoNJO2xx1zMpKVtK2h+Hk24I+sq6tawLrD1bqbXDAzu/vEQxdBgCxgP27WGPXwVbbquhgbo7glIbYZmLfw2H+s8R5mG47RRFdndyzkz9mVzphw07Yw70t0WnrdpoQrQNlFFhmpRyWvHibZKMLSNRl1+wcI9wwk3kpEADmwF3IM6dYBifGtObb13xUNw+1F/f3JwBQQ+N8pyyp/gDrPeVfnOM5NNn95eD+vrf27PXBEE+xWcI7XEji3UJKT6oFMx+j/ASYUxTBV1ecp60uMcVQDWthMRh7a4wlWwWyx60JsNLE+4YpZmqN83Lu3sjz+1+DLnRjN6wmnXGSQR96fP3pgd9/5gzmjKvz22ebxi3gF6bNcjtxUVYK8nt47zFfiKiWXS51hG0AMqklMJXKAT2hG7Amftb7ggSsS/0Cpen6JpjO1N4wDd4QfVC8sSVFBEo2c1rPQ5SVLZpzJ1vB/6p5WX0OY1eh181Up9/iaqLR0gwK15wc1sEDw6KfDfHzrO8O7iBYtwZ3YA/uzWODUwexBYuBvQHuWO6Bc5RWdlUSvUObfT2UVHrYPgwO4XCbhDdwYLBySh0QYMFfHw4/rBz2P/h9OMQwM265WcJ+YVREAA4QtfbMVhsBMEBcr/ti/cVG7+Xaup20QUl8o4EFEPmMr6uNDEKHK3CGOgju4qh/ubLC1PLvv2HOBt4/YHo/7B8ydw+HWpnmn/qrvRk7/OUDHE0spuoQuKgxDJEhdgdqpz2gDXyMIoPNYdtwMv1NpCO4Dx+ufvB9PNJYdYWCJYPK5qmOzw7cCZ5kdRJ9j3kSBRm3U66KMF9IA8XymaoTjIXLDy2fB+6EHaybzfz2n5nPfjXihT8z2q1Po8BE/kh0F2XT/30iuz9QZPcXD04js1F/JhmCZPOrwJtUVV72nz4lMJyXnaw4explo/IpbRarEceuF51JNU2GcUp6r0CIvBXO0qA3SF83bxkH6cqKX60E3jKklEfHmDXFOg6/vNe31G1zg5hq+ZO3G6fxOAbgyKtf7EDr/9BN76B1GcPu1PJWqhUPdyICxRiQvSV5GdTgRZ0YjE+zdHWqKov4ZYunl3GBbA/sbFiYClL9JU1gGEUkIw6T1oQnOSS3rsIihd2u7HhEAD9kxF7t84olhaNCHUrJ/bb4Zfi74m2GOYyJeyi0N+YWMiuCPymO+HFQMR4A0/y6UtDjAL0PWQc61K4gg7jL3ayCJUUTr+AoR6d2W6XOSevA9BFwfypT427SYSzZRflDee2vUfD0/z7qv1n98yRcvf1n3e1udlfxZ+s5/X1JgR0K7FBgbWcH/q6/oGzrL7bo7w4EejuYsgY1rNLPFv6lbGu9l5iy2aXAzjYE1rvdHgS2XmCZnVeUsrO1iYGtHQrs7Gwd/3+1Y/9c7XRXX2HTb19gM13R5nNqZn2HmtnoHv/jyVPGQxSEVqGDdH/adgwXpZQdh4z7w6VuX0VwEdHr/xp1gDDhheGwChHvIBOkii+21LNw9EOklDnpbJTq01sqbp6N+G6p19iuK6mZqQ82QjezpBsBry9LdYVmplIAldGtAvupWhsupZ1wNOJ5Vb4V+Uq0xeCdKgOunhebUEPb75RIN9td9sxH7cvAi8IqXJW6rB5SqlXP1zu0trkwY8X7LWusVdMepLmCLNj4ejRQsDAQsUDmS+gIwEkmZF31o1LshLrYh8ahvGQ/lGyt3Av32pUvop83o4G//6Wa6YHpcX3iRiGXxSyTev0NiAbY5BoMFn7Wxc+G0OsPq6qIT+uKo9pDUCyILHPYBYNEpKDODpyOFEkIUqbNCDBMlUhrAkIhaUpQhikQ3VvYGD4GsbIumGaXfHuaVzdCRzMQZgUneBkw8JSaSSsK0zNeZHWZ3ABFfg9H3eLdwe7Hlq2/oQKbEz66IO01lQtPJgXsGnSNnlbbQPuRc/kmKL5OfncTCVZMJ1Q3Cfc6ZZ7EVdtreX5Harc58vkTjssKNwuaBlxeDDZLPE3CF641nx0deWIy4JxelLzymAyvjmTEMTvyRklYlgg9SKZvisWdeCcrPLL7kDFVvv1XHV9CHH6vcgocHy/sn9SBOOoeD6Crle5qxXrU1aPecbO33siFFLQDoDk7k99lzpOEwAwB0s71jn8GNGvUnrum55oO6yr7wvHaFpvi8lL4Cxf8TvkFx1rA6kRwjOpSdgknkBeX/E2ST8K/05tG+x6Q0exqB+L2YZ8EzAvLm3TUwk7tYHP09RmOIi0EUZElpUI7/AUOL4qpS5H6+ByPkC94n8oPFf8FML/iWBPyzcikTPcyUrzBI/okjiJoHI7OOTA3wj4TPnQ6LLS0lUPh8n2aALFCpjb6hIqIhYQPfBAMo1Y5gtzww8NpAlgOzCuf7mPc38Xs9Z+ZvpFYfTAlUwBKnNPsTOuKokqekJbkz00QtNedX0neSHFaHrA1KWoc/Vx1GwsXJqB6UkJdRXaFPyXQJ8Jw2Kl+qtbni2uF6vaxDqgKT3s/V9ezHwKYzjknZfD06J+r/eP2ETA6x75t+LFnW6rg0obaDvNc1TYjspNWqxNO5xvApzPikldPIZ0wKSzC03i0igjZUpGr5SQeVy2AvCo4SuJ8NQ+rifgqED8BknCAiIFyFHmWECVdFLcKhxsIljJNmqDKkNBEQ+ILBzQ49tk94ykunFVcL2cFHZygYLKawdYEp2sRoI6gyClapQrlt84Di3Z1HE7jRH7jfJuv1TA6R31UEQFHK9jOVeAmkRnlkUgErgQ4zpKbfLKaopxMfMKBH6AqxjuBwC1khsPGfOIl6hyN8ByCuaADl6vX8hv+nMUpBOMpnHcs0CS8AgCu4p5MQewCfMgRT8PiAlIht/qcxvqTsLEFe25B8ypEgChGVzGwLY8uUqQTOUqgoBN4bgVUzkq+2mvlGc3lKhAXOMy1dJ9oigEo5STM7a6WVZbLftGnmgg05rngqC9cn01MN9xo0xeIzy74ahRC/WQAYUVk4zFsoCoGBwF4agfRAEOFp2jOm8Two2KsHmHwKo4AqVHbbjVMRxM8eOI3HosFcyDCZoR0sHeBaaLMCOo0xkPx6mkcxTpQIFuDoapczRGq09blaohb2CkHrIDABHJgK5erccSzsyLMJxQ/haXH4Q+hziWJBlY56Zu1EKMIj27Ep0YjO3TTuoKZ1Sh0VcSEQWgr3rqeJsB+X8MALlrXcsH/cK9Qxh7KEumkZHulv5jjaO651FQ/hP0R9zYZKkawu6qQ9QkTfiU/q7jS0cho/ns7SaxV/+nTq6urztU6yUl6r169ekrteTaxB4D1kUoBtcfPBOZMfhLb7B3/v9KZP3Y/YodePk0Vf+50Chg3kvIhL1lkZfmJJv7nNqLej3f6E94hQLwr+FgV9HSMJ6qQMzuhmB/Bk86l0PGyGGFmUSYUB0vifadvROB/OgRoqIt7qbkOLs2xEGclCU6aYg84fA1pZsR9fztRB9eEjjionNEv7u+X2mtatAOntQqYbjyaZqiNIb8/oWQGNmb8TilefO8J00g4lUJHEtIKa6fiXMmg5kSeVYd/RtAXSpMRXJ6i3qjzGqT3yereimGkt+b3k+bhbciPEufkBjAMnLHhaRHP6p7XT/twZk7ckyErmjGEi+zx/rWTQNQO+RJxHsWfjeVlLLfURftG7DIrhu5Q9vbbBWnszQ8RxucL+dnnKvjjX9EpZFXyuJOO9FEnHenjypDb5Q+ddGyWjzvpSMPHnXQU4aNOOj6XP3TScVs+7qTj4LH0kyQusZ2P5cOePLaqhz15JIu6j/s7ncUgQxk+7OqjLP6uq48yRFcfZfivuPoY8Yajjd3SdCUupPnYbqk1MH286LANpPzZKCRxkdoKUtSUGF10ACOmbb8zpdSn/0zbrX+0w6rlD/2n/gBqrATRuL/3PCkL+q//+K+V3XJF+OD5VKJcSfflfandbSzx+/tPpZRded4Acwo9lzSgPgEqcbS02Md+HBSwjAcPxMtB0e0NStJ8EqfZFnnOSJmElRCtGaJqxMx4hM9yOILdwaruP1jRTCu0fOFjPEzaN4YyyriegNLQv7looBVHxwr8Y6n7G4xn8xk5ZESJPmm50AQKQatVGAtyKY+1nFtYRRbMuSqHI9IxAMLx8nKhFffGAh9ghNL03dzjJIFMlBwPIIDP4qCYj8yAzkpzsB4Lg1gHBr3XQYZ3pwGsguQow00oRgNvP1xdJU1lK8cgW11lEI/TbOWlPmcQ6N3fo2Vaz48y1GGRuVn3lxDoup1faPshvmK05n28FiA47t2w0+OnZR3ZieISJR24sywv1504HSV1xMu29xoOJenNNKvLX2jvrIPaVGgnMqcS32f17GoCFLVtBuiL693ZTF820jJiD6F/KlceCqmHTvVodowcGWxf/pAIAXxZOkhoYnGn3RNU4ZkUFBsZcNEWtnNSCtx7bqV4H4lSypR1O2VfE2mZ+mpR6kei0CJH11EM6Oke8ACJhtiekSfjssLeggwdcRJy8i2sp4vprmwe4GIA86VUZuSuxbr2t7KAIvuLkERNwP39gqJqJWlzeDUTchY+p6pvSr/dE4Pa1AmfxX4vo0sdrXZpkbCtE4xtrky6NUlqykTCwVwCzdbMHYJybvCgi4uwr3DTBYu3qRiBFU/bK8qmU6uQMmx8pLSyiJSlP5d9c5pBdNCrN3D6wKAemN9GzZWcLvSpEdBVjjf0drTxYpsuhH3gB02cp5HtY2kuVBrjJW6/Uox61f+ikBE4cw9NrGTvt6o+uSmQ9ovYiQ4ZK9q6CVgYtjlr09Ya3aTgZXTlx/bhjvwuLVjta+oeyNuEg4xCALVi29XPz1yvawr9/ZnrvVT92OITuv/gUQPz7WVfqeXOgwfWHExio/n2z06paO7FAwtQU6fGRZq7HhVF9L5kmSqp6Kd3gEBoElWYWHXf9tK+m9suh/bS7XvWCl5bU1V+0syprHftAXqw1tNrm2TvDQreJAWaeLukQNb1TI+oIPnNLgn3VNG+Tdx7L1wiv6GJvaEq1SKa+sAibdDUap6mVovXxZvK2vgUOZMXyPpu2PHtM+/1x9zNapR03f7o2Ec2mjicW6BmL++kMMvEJZB5vy09QAcwMSrZA6cp3MHQXcdpdu2Jy1SvCKMYjjiWLYxFC6jZob4f6cuLMzj/St74jFeWwGGLA17FeYV+cmxfahbHXMF5D83OUCwxIPZ+Xmihmcm0YZKgorHZwHG1p1NKN0XJRqgIGslhBkXoF/P3KFW5G5FrkrpA4T1a4p25/L2sIJEs9CRGJyDOGSBDZhnGmbHYZGIZHQYeaZenSCWp1bRjAlDoDrogTP7n+1FQ441U3QNIrLIcVx1qBdvFYcugCaU0dJFHtiQ84RVv4QSh4prZI9DnUbMIUspGJYQ/Fj5loXJLwo0GgnazYxclfKiM0oI45BGSCTtvgTsD4wq2XUgUBXQTODr0AOeQ8o1DOMt4fS48MZFzLNK/SIew5yhooXclYPL6tl5CkWieDjcCpQsvNZFcoxoV25fHZfsIbqko2VyhtT1zdFYSXyq1JtyPTrPoRu7Wxs5aRpsu7sjjsQKQHLsCDBwlUBmH3bnKBbKTzPEfIeMu7YCsDhVdiAtJ0b0cakwCopLpdAcZjThMZMUza7bD0O2a3Zhkajyv78bDrOoxyCZ12GSVjQ3SAKhyJaZV5xb9T8kjkt3T4M7tar9gMkK6z2DySjyhpqTo0CGSOlJQymGjq32nK9Yk1RISlRkcq5TXNJLemnvnylVveiLnV1YhF4IZtk8Qo11AqPKIs0dg9IuHJGrt4n5wKUAPCwcRTPVVOJYSaSRm7es0CjeTtakHtVLWp9O4ggophGoMwKjdzQtN5X6hDsazqknsVYbhFg1ZSlfRi18zo40weHK2CxBk7AzkQk5CV6mGNRFJO7DjjYRgaamZ15qbUWj5EHpwPIg3j49ASXDMPC61iyUD2+VlCgnYYnVqdpRJnYmh2ZdEZlDR7rp4vYrVloqdX04vUXAx0RUuH2fBVjN0E4hMFEsJMQhcGIZvK7eB3OOUYq6W1ILslnbwis6PNCoDkgFF7pA995akt2hWx+WFgpTXO/3+AQj6bnYb5ZvVpFI6nxWuX1cjq7xNHf085MeyHFNKsfpRI1JLwLqD5HWq1GjRLV11hN4z06PkGLUcKWcKuVLjxxBVlZNgHp3Q6eZReqz3N/xWuiowIsJsOy5IfJaQqE7Ey5Huq2S8XyL5n+gDjB2WFboNEGyB6Du3+07DhZ5rEgNTR+HS1MlEe8nC9jRZWFKCb5FTqiPh7htgjK9zIBdrV28bSL4xQmNYv4uV7xZ4ZP7cftXz/bm909kRF22ZSs+v/wNks3fHqLE7UiNEwwPj7Uvs6NKFYtXYQ1lq+wVbPJw1n2zTcRp9ytfTONfMug5Z0yBFY3iYZ/hfQ5kWOHoSemhHFcAgHLEmYeyM0N2y5sn0ILX8kdLVGRIISY+sLYi8V8yzEpZrCbWQF6xiAAIr5qm9XuKFNUG5e86Cs7fUvxwIkvnwNANBlrTNwlVDWU0Tk9AWpio/sZdn6iC44Mp5rdvtPsUs4uCIehSP5Kb7dTRjoz+7H73mwfKhO21U2rQPnYeuQ16uFylu+o9WMoRB4oXu4ox6OAArzAEEh8dn6Sd50/V41fLRBjS7DRd5+lGmqPtv8rxxfKS4Dr/mo8O0DMf8YwYnsx1ZxdB4WVROgh/N354/hnFdFNY89LM95zte7Hp013z45f3S49C5v/e0OjB8tbiPGCcjgmqgiXWSBDgti40lgO+IL/GaMrEKe6+hgV/Q+Qvh56dx2zduk/0V7/VTSgfqA8XGZLaIBGnA7YCv+D0Ktu004TVmUDWy49JJI5HdTsNTsWWrUhg6rgiJ0zDSSHHnDttRKGNR9x/lIAfy9t+/E2HJyKitZuas6kC4N62L4C5M4ynpSr2nC1X4EM74YAsLS/ST+QUjMXhK6mXvUb/tU13hqd+N3EeF/0bcN9TQEnHXOwm/tj5/hV0ql+FPRYQ3OTpqlCX11HREBEv8HMtKxqKGK/X9WdrHqvD+pEBtFhna42ehnfoJO0jyjSKO3gDaqO8vokb5uZ1GVgh1RO0gauep8Cb10A1ZpUWEXYGMUXWgfuI3Ut7CEKqjbSbhNFeBdzpJasDRpxpEVuSTUICnCk/341sa51UcZVcUeStMFPEry6bUXJwkn0xNpHdphVFu4gRR225L6fO5UUKjz8TtaqU9EzdXl0KLGUvGwZH3jZ9exKhnP0W13t3sFv5+8o4HtnPmulisJpaM56PF6XMFhSNhAbtv13c1ayEFThtCWtnu+awuUI2rRqOvmaNsdBk2PKFVC4xQlEBUChY9POinJseSxdFXwtdxXTS5WmTqqf0hOgqvfKlX0K9WvPzaupG8kWwGctuk5qp57FS4qlp0OEv1oSsFWgioAHTPW131UEoK+BXAKIHhAt4YWPlBSjtTkoUkOgXuZFSWOxT0lX6PqRhIfh+Z6iARPiDKcYCMJNDfGjXXaYbvQrm+UOuOfgu5pvGHA2RomUwoNp6e0Q+KbvEDpv6Mp3IZ0HKe8opqy8MiJFzWrsJYheI0Qn1qwp7It6VNW+FvOQZgE1etWM4lNbM/4KEbrGRv/QXjgvH8UUnBobvtNWp73pVVSeR5uEYlRkc9Nu/khHgEetjigRLNfj8H5p8uKwUeKT5RNyxjG+7ZdWngtS2O6WtpbfUGxQjDVrXPbV13XJqbiMalrhemaSbVra+R7aFIR/tcxkn18NFceBWVEptxdRHPxZEbpWouGlkVGTmNS3QLvUqq4NpIrtc0W+sK7H9XCn88GizfHAV/ZKzD4oyTwBP6qMWfwuiT4dVBUQCXlJFfoMNSsTL0CsODqSiOsBkAWKOo/5BW6GlBco2/i56xA6GkyD6mjZ6ehVr4+xHVIOwXf34v7ed9Goiw9hIxVh0c6P0dbHdQ0VlqM4Hlxn7Hu1ydJD0ys8rBoGvqwEE6/Ij/CTdp3O9DR2Gd9qHf3HrhIxSuYQ6kNhYPDvDA+DHFlQMl1ChpUNCOeL9gzpIXkoUVr6n5KnQ5/7Z9gLnAZgmcv7laWye2aOy30rfZYn/wW6kc08j4K5Vdq69QfQzGo+UBH7WdIsARG2Y4aLuro8I+cNqgF8fqeb0MsQXAlKREYopFedIAt8IBV29yKKtR4AqBu7uQy0KGlBm1itzK6tOEuxmtuGb23Qwd2mZX6XzMwqy7wHLPxyzMepg3wwuzbaN5hNcHUCwVWvrik9qDRFJIoddOvNO6qjLc47m5WpQBIZZRIeSyccfzfLx7WSoG0qecphnkUwlXWlNpJnXu/x5ccOs9QB6V0QhwUvG8TCmfaNmsSIcR4yIyiV98+RYVzEOn7cAbe3TVZl+TYWVdvLiTVulhFG2jKQnebnPYVtoe2i17DCqJCp1Lmok+nFHe83DRgHUDlY1dy1gWslog+FgKIa13yvCwIW4YtbsCtu4PhN6fcE2RsrFq7FJa2WapgOOlL0j2uMCVVyaCYMQJhv6SxDIcB3cyv/PSAJbpYhk+s4y+64Wdd+vPxrJn4dhxmaHX9MI60C/8eLG3DTYWwtdxoeBUAlG32xT+oRosy6uXyGUAmWnjiLs4YsexW23bPDCgLuTQtBMmZN9ZcV+ecqXo3a8C/U2Hczx3RhkVhuNuEp6VyxvdVy+Jn1Q5cXXoUkLDj/sz40g9PKPNTD6xYWzm9EYls/TWjdwKtZbh9BORlGpgGY3LHVQPgHFLrtosiJcoSy5txHWjNG4Wa0zsq34R7EhM3wD6y5f27XDkaB7pjsmrYGHwTlUxNYZF9WnH89igUJWGfVKdCAK6YRwMjDKABDcqhqpahacc4VoycTsSB5Z0Fi0HRHEUKsp42C4GykPWTChlYt2Cx4XSsfgSurBY/0gIMoTLQqpfgRgAmKCimIwv3PhqgI8LlfEpev+aLYIsElDZQeheobAsDRIAQmyERuodKxbq7oSiO6GQ5ONTU0wV02MKqU8iEUABGVRiGIS6Yzh7mRhtqAa/uPoYa3mo+hjbf7D65uhfIV9Aw9ezhxBo5nvVVVCChbOEkqJHECo1/IRyJIzdH/J+ZXlMCR32llBar6zhJqY2Vu9mc/U+Q15YfD43ep6CYeMSftz4uRTrhWqRb4W4y3RA52IJLXudkqFJGPz1iBcsduumuw7j2HjsljYulljuJtlOwdgld9JS4PMnbvYHHKux09LJNudPjx24/V3kAo9liZPHda7Hpm5PLJ987KNbue3nj4VyO7uuGqeIy7Gc3utK8zLXVQdFitNpXO3Ep7xAXT9HUwl37EWZ2mHCOJOXTm3jN17sJ721l3i4xB+jNyId1FYBObMbJbfra0Pz2b8es5uxSEuyM3Ymvz/urZkhXI9tnP7ll1/wQSlynwwVrPdW2zeY4enZ+L7r33dFe0nwfIONkmCj92pjvbth+V0s7CuP5VWunZ3JBnrKy5kMrzX8jmw0dB1fNhUie8+lKqWuYl3W8VwpD669lEqFz55LN3I9pV/YXZOZ1robMhfs0zLby94rle/5+kuZcX3txXOZ8/mzZ+sya2+9130hM689X+ttKId1axtrL1+qxjZePnvxXLX36kXvme4zX0bQrW105fAFHGU31l++fN5VlTx/8eLFWk/Wsr7+7NnGxrps+PmLXheybphKe+vd7to61Kv0NzfWelBcQ1NHyFl4/nJj/dnGMw1cHSGVWtefv3zRfaW1Rk2EUu6VLul0F0xMQ2LgOOyOEvcklwu/wB/DlJfqMKe9cnflMa5LzygJW66IR5SZXlTKoagKZ0G6rAbxbCDNP7qipTDIlv87GYTkqroIAFVDv9+Ol4OMxUukQtOm2Bjt9IiJxNr+G5hYUyLz+8286mRpdVgS6q64ISyWl5fa1bJ8OrFYXi2g29XyasWSX4JYWBb2nqOXfoUUPnXa0HhsYXkD27yHHvWeI4vEO2ikjw94yaFTi77aSFSiOCZUy8C2dF9XA+AOYEnXFQopkqD3+jXwNvcBCjQxD0BH6xka2cNYTFbDFMF122j8Bq2g70K5htXSVQv2/3fLtFp5xtf/jUt0tddcmI112Fh2i1fZalMct2p7kboaG69uWlTirpqisWpwYbmuHuXKstdl93Us+ZFM4lCMD2MCDmWsDtBealCT8HPYXmqHy6l/fx8uy2cisuMA0ChECVi/fh2QfE+0KLtwH4Q+g6X436E1kj+awkS7P8urGi7PiAXrAqsGFFADa2jBrWsq3Q3bSoZWa3Svk9evgx5bateJXoNosoX7HDB4pvSvpeuv8OiYoRLPeu+XVHgaVHI8fU6x1AH0w5fuSO7RAxUMQE87QcedMnrk0ZqyAO2VcNZQ6CAmrDIrW9joW3K88cP0dvm/gYd0YtAL4A+bZ+78LVOhaV0J50GhHevSKYyrmgRKknh7PJolbiAm4GGqz3dixCkaFwIeJoPqKDnGB2TxZxWdR4nfFBArthDrz9KFh9tDmI/BQjI6UA0XpmEiosUgWa7u+VFxvEyoDR/3qGOH7cp7Io7iWOMRzTlHLAernPVe8+EG/M/N/jUEmmJowUZf+O1+H7IPJfsSsp2QPQkZj/FoN04QFQ+lBtdb+ftV/k4L4Yw7zNml+XxH6HsyDjy0Q+TorqhFX3XeqrJ6NBHnAfGN/lroQ7hoCevrEUo4W9FpIj6k6xVZRoaoTvkNtaKXLqwIf0U9UZHlLXxATTonwVQrKDJd8BuqCH7JExp+QG0kkiR/J/S4AJTLb1oj+MjDsuIt0a3RhJyYSMsjvKZrkaJlSypfWj45zPRshYu2POHRK0719QmEsrry+hLs9kPPYqSVthPBcMJDFOe+nc9NUEcfN+oShsJY9df5zBKcVnYVgwWmRUeo9aO5r4h/H/l2+bOs0nNlS4iTrJxLuFxYm7WSbgpbfrfAvh31YEjz/ZJU6oN4COvq7hRPbTz6lPYrFmVTShRvGjKiAPs3MIPTHTz39Atm1dCPmbhRQrUOOG7youzDIp8xW20QL3Qq34r6gEZUQBaB3ZMkxmrgHj0K6psqUy9LloxCsbrhS3z5/q5+txpqtWR1Y/vp8caNgkYf9WZKFQD4DoFE6iJMuaO08Efmfku53y7MbeGPerqAcn9dmNtBICUG07OrNi5AJFgm7ZhBPfB9ht++tFo0lZpaF6CVsu+1qwcCZOq9fLTeBZ4k31hKe6O0rSbNFY1Isl6jVoTjAFPIGEkaRDJGFKRS1F5I6q86H+9oBIXd6gk0qp+5sJ/1/YLFZr5ROFJvJKAcF7WU5iRKrti1E5fGrNFtNTXiYLsWhZzv03EmXL2otmdOaUcElWszF5PFFSPh1Z5maeZXAp4c1DWigm4ao36ltXTZ/Mpi6FSGORTAd+7q7oTOuU6VbABuUKltVSZ9luLd4rsSlmsqu0g2gCgKodLtwmfyQlo+D6I0WD4W9vxKesAciDGAQdUhR3JtJZRfstjGQ33jSeDE2VWU0Vr/+2O0taId+dA0ByUOySmO3Ch82KDtxLeU+FYlfnUSv1LiV5UIS1Ep/xwCy3zpBE1HzuRdqT3GQPLdLp4AA4HXfzHdijwiLGw/IlXbH/u2pP+6aDwMluiNAfqFz8fO6KWvsfL94N9B/DghbDHv/AqH3joT8dfKGUYCJ9ZB4QyOjMQag8OtKkMaqyF6JiiuMwVngq46kD8T1NMBeOXAG/k70t9/V9k9LIJ3QrHox50byAfRdAV0XfSOVk3qFBfZ/TdEp+aTlpffGdylV9zT4HPVeeAZdjZJyABBC7XHTf9SnANDvZvaz9I6oUDbtwFb22NVrCswr9SiO163kM2Bb/7P2tz4V9q0iiA1nCT6lsgkOBdF1yK+YNMEFj4jrrAw9lGw2esty/fRuUmW4zVzeBaKDULnlCVZhUKc1d7rk7FmKFDxhNAgcWX+MRKthPoTa6R8H+KZO7Y7zGKNA43uYoK67EoAEIadWdBVQTB1DUQYUnljPE0aQu/UBaRMh/PotxJpMKet2dyL+Iuu9rioU0EIb0lxW07NtsxxW66savRlCbdovNxO5E7+93bcuavX6vG91m6X7h/pklWsTcU1JYHwH2jQ7u0iKwBxTFK6Xpb+yAjYcK51wPShRcfkN+qzVnpc6iymjhryRGadPOBUZYWEy1cVznL7PLPgdENBof6hvi/R0a8MycOZCdERzQTr3D7UWFnlUVOdYfDMpr9rDQM08XePOQ7orHOmG6MrxqkWh0AVQXZ2+hs96kpbfs7J9F0ExPFQBKzTsB1jwGpOxjLiEpWd3XadgNCgAfRwYvVZVk+3exKejzZdsCLrHL0wy/hTjkYXp0mtTovhGMBjhUUGe4KtRHyZQniQcDpK2CG/J2E5cRLzLKdl5AzVCcjBOFdChJoOzrqnZ34dV/OHaR1unp6nRnXKnKYXHrYlwlgF7PP0A0fuEt1/GmQ4O9NKmoQKVmVXE66xhpq2B0YR9lBkS3YeGSVzOddknnxsxFPKaxPgP6V8/rRsXLkdhI07tiwR8uXpuHm39jGcv/BpXOf0ns/mY8S7clLoVMiHii+b28dXqcx4qUn6pVDVg00lKFATQPFDeBQMpKFtnELNw2/KABw+LOsOFms/YgOj+4jqZeh1E/ZB+CENyIGQX6er4sq9ABazIC9bwLOtFiJrDB+DAjPr7kHt4nEHlNMVw95qofwAWKKQxNZvAcq3iaqJ0hAL1fIxTNY9Q3nVL6LEZaswkOqt05YCOyCaVFWU1OuqOLa+9ho9FVDs+pDbYu2zxDw0aEW/C020dY4+5Q0Ovfk2wwk5N6SnEuTzCSfiyPUeKJF6aIHM5eVzDNYpTr2eIAoEmQjJ/fhARAqMkIx+2JI2Tw0t/lDY/fKj8JjqgN8gHabAA/XJXZuWsWP9uLPLB6bpHMqjoB2bR6dlnHQjMJ/Qj6X+jDQ6hCOcPzxL+u9CJqu3+KV9YJ9yaEAmzoxFqe0y8C4Xlct+uV4DocpmF4y3wyZEB2jrmHbc+obNCDSeUM5RrLGQadxFijsk1WIPE92iPQS9s8QX/kSsgfcbr2Q92NVGuWE6z3Kazoqd9W19eprM9dZOQyvgR2aDOgyYU8alA+wZg+wiGiWIZwl6aRS+KNPgjob7eYKWGl12Su2U8CXaJfcsXThsTNG73jTvLzBD5B2dfH+PLw3LB4jZHIp1oSMHRV3S94wlcQDr8BOw6udFIKyRP6Xs7jLmV300RAb2M4F8Pvs8xoznwFqXMYtjdlWw60SWOC/Yndig/4BC4us79j9BJ+5/6C+MA4DxP+Qv5amK5Dd+g+Xw7Cg+w0R+oNmJ+IIFu5tF+HyVsIbthzET2sEEMvxAkBUALxiaWOGLIeVk0b5Hh7wzLrKpfvMpsI0FhujnSH73nYz9Rn0zhtvulIY937qnEwUN5h2Tm3yAXaH9Ov7h2g+I4SSG7RIvRCWkV68K9QnzoeO/m/jvQKLiAMp0YbYCjjPnm/59f6x/35v9+w5VoU7ytxDR4Drx2e1Yzv51wu7wlaEDPPGOeUHockDocjv22cexhSXu9GDGjBDwI2TcHVsIqO0jSSLfZVAuB86dXgAE5AGmPdITgtV8ovZ2oZr3djX48sJpFhYRrIlw0YCdDGrQbimpYO1EIii+UJPvockdu0kEBXXpdwLVDqQ/GQd32+Wo78GfMOce20eT3NOw6Hstj33k46rvvSmK7Ao/PXaYy+Bh7rEvZIIowvTtMVTVlzGkx8+2eNL3tkgS6LFvMSR+2vfYLhzZ+sqxHQY89ibPy0bUPjGRfU/8fszwwZrd7PZzAQwfUh5cfd5hGkcAaXo8zpuxLRjPy773NhxdSKfqr/reQXjqsd4aVI/vhcPnOoyXWEjWew714+qGzxeifWgMAlDJmwRjofxnOnCxtW4fH4orRU/WXhigra8RuNbXMe8ZGhuw9Q3xLcCw/gxbjOAD2nuX4RtB6y8cyK6/tCC7/soF60bXAeoG1AaMBjAB8P3cwLeHY9zp4Qf0ZGcNP6AbO+v4AWV2NvADCuw8ww/owM5z/ICmd17gBzS78xJBBe3tvMKPHlbYxS+qGutew7p7WPkGVL5XTwU8etgre6rW1iB5F6gkTMsbmBYAZ98T5NNjEtB9TxJZxAlATk9SVZh8nJS+pyivZynWH1qa0nP7qxZaNKnycD6qTTdewRu0/POHS0vIDTsesMLY2LQfjmlPfGvTDOBinZVLlwgQqXr3BGrGMLqoxl91D+Ni7ZzrPUNhtbwAeWLioJnkbiUC94WpOtH9Tckxo9Cs79RB539SrDVxde4Nt2T/sBR6pHa6hY5vGT50AVQtyUaCwfn5XbHgOQ8rWZbYhEX7pOLyH+Ac5sBAMEDuQPb6h+UeGroeN1Z2NYlHk7/Xhb/dCJDdr0SW3wLZfWfvUPryr0822tUEfsW7HbifYKN1QWwWChFS9LPx2YqME2Jj8BeZluoK2TsoJypFG0BirIBqTsPihsj/b0T+30E/vtnYLIQ2JbWEm+CBDgv5RWQiFsz3QrxYMOW4/RAcvkH7v9nbk/2OwU/sq9+plt+glr+c/Z4nVbiQwxEpaieV+YQAYstKWuUdKw7Rg7J+f7DK706V3+0qvy+o0smwIF23+CexufCxKzDIZ6c05L9gyH+Mg6NXsJHBNgS7zzGr42CzWl72No2Yi+gh1i+tSS+EW8MB5VMuMnbloVuF0biwCLQDDTubEA78OhYNoTfWRgvLy0sXBfseUob2Uh3f318Av/jyNf7t9X4JLoBF/ysMcKs8DR2DyT8Wa5RI+ahcjH+Ya4FKLSxUBZVag0bUqvcAmQnyrK29GiySwDalrkauq50vPvIi5a9hQwNPnErYvL9nBLnFxhEnpu0Q3qcOMP5cqFE6J9aUzf4aKg+41tg0BIiyoW3Hmnw3AQHfhVmQRSyRquUmGLtHG81f4fLyaSjtiJoyroabvw9jbfL9XhvxCCeCbs/v7wE3lpflnOPOhiIvFIQVcaDEYwQTJu1FmibhUhpum0s2AEBe5KqOJEpowy9IFn5JmkWu6WQGVFgRGbRNfohI+1p8qXtYDVaMFAb+BGBtmrRgM1ZZZnO9fWBCv4fYG7FrIq9wkck3L8S8LJ4Fkg/mwR3ZxaPHAxRxq188gOO3pwKrVLsn3C3gMRrdKABHRo5ZhG8K4VGhLPGhKfymx67IuwKw0CPKWPFE/FyTZwbVSl1Q9BXn6HrBfgXXdimF50jlrdf+dr32DiyXzvKSBfg0nh+Jrfa4XzkWthYyhuYO7jrEa7cq2EvwJZ0s3RTSeJ8ZvQ3xWg+/aiVx2+Rg6lJCXvjhfSIXik1CLtJPWSItWst+NZMe8k6k/9i9oiHZrXIEwE2N74xaktFzSzL6JpWWTBkua72KLOezuXGsIBx0isFoHpIwoTZGv1TxKLZDUQx0KRXgtGg/mRpGsexKHTzqRmlQ1e4LPrpG5kmjQoAwtKR8PNQdmcG29ZmN4iCKxZXhCPVCBtD1UYzbx8I96P7+1evFm5MFoRqY9hOUVpwURJCV/gv0ULEYal6L2sdpkjNm+36uNT/vPIwbKHn78jJM217hq4k7Oh4AzlVQG+OM3Dz47AQicnyAxKo310rOwuGXuFQctrHXDPpREdrAF77K9IOuS79ies9aXsZaTGNJbh5lCObu1KR5uthjTYA2Q4VOYoymxrKJfXSTauW2tX3iRmbHRr6Bt42iWd50uCaUc5bIW07vKR2CnuKTylxcNOOpSuD+qFIoJw3cybeIeQZIx/az3HpEzLjaoodjKvfJ6PlniMQwXNc/dmoVNFXIhEDY9mBEvgDtCKn/JYkSWa06O49URCuC7qAw3jMLpfiTBOlRIZyJ60fGWQJAWsIxHSXHDBXA9ch6C/S4klop8A+ILDtuy1znY/O0qaxtvXGqakB63fIioyANcRqh66CMTIT4SmrfWak7Lk72EMUvgfKHfYdlgfJKP1PVKp9BK8WM90W/TRtQ2b4w/URVOidCOWGYYbzxjgJBIXOdUf8dh45x7eIkIN2QEBPfcNd72IH15hraPLqRog6rQb/vSU2KUov4ZFg8BIdMAuRW/l4/S4YBdsH2g4noOb73HJ15O9KMDAmjmgseyGNBFRQJbLRVK8ZL9HREeN5BB0HvdwogeZLsD8RrTPJWQ/ntkS9NfxNySCUl6ODzf8ahjzTHLHAShKeL1Ecu0yksVF2EOg51iWv6bpwAWN4143+dl1APAOjnAMy5nR7asA/ygttxooDtccLA7zhhxTWR72OHPcFcjbe5KR39tNuew3MzNpwzRl7/kMjzCGeD7JM0Nf+CdFQr+MIWgLqXjo/g5WVAu0acBq6cXiYdpBZaCwogLOMq8TJVgfetRQdYVToaKO/LbXKXbHYX8mCHuJz6aceNhc3NitpOI2FuO43RL2Qqbq4VwdOqTzwgpWSn98bFo2+5s/4a8yvLd9IZly52oS3hH82OaYvjLPpAWEB0YtMzNXZUYhLDNxczcd/KhoBBNXI0yYG6IgD7L4WwaSQL/pj8DgdAJFMWq5t2ChX+AACeESYSn01+DsXDXLwjHrLdE+fYhJDbRH8iIkgJgh5iEuGKLJDpAhSr82cyv0+rQfF4hExt9DELSTRnbdEiU/UjByydmycJZS8hP4x0iP5AokjUQNkEGNqiB0w36PfFuwPbC9KYU4cv9VPJEIcHKRnzG9qJjjQt6mqsDu64ugFjCd5g0AUUyqUhwKos1xEHWT4Taqb6spWg5D6pISPbSqHV0WcF7Ed1Vq7embSaQqec2GIzDVpFLXJoW1iL58EPRDIoO1nI9bIvUkk5l8ok2/LoMYkdiUJYK65Tef0Tq4Q8OKS6yr6zL79CVyvuuhtM4vv7L6nie76QY40EKPv9PWzgX9JFZKAgUlL4wyK4o5XULxpkgQGW2JGAGDN0wQRrqkHHiiYRm1/4fmOVM/JoqlYQNGMCzF5COkUEmV5D/cKsJ2atIRUvQjMfYL+8DGzkNqx2hAdMBa57OHTmMZ46RZ/o1FmYU2dlnTplDgVEeerEaWucOivr1Fngbb3SJfmSOormV44d+d1M+/I4amyNx6he6r61q31wrvDjwLuS3xUmoDtOip3iB0QRGu+klutYnFFo3XujImBU+hsvwnym88bKzezDJbQnWrucQKYHywgE9JnRdladOtAxkN8EqFszNo0BUqyuEV4kyKx/cBIWjhHt8bliUcAF+ZLMTtqxIWQCbEEGDZbHsxEUTNB3RtTsiCnvQMUKWVZ+F8ouZhrj/ZjS4MUAHTF2UiuaS49/FMlS4b7/MQekQFYxua6diomYmpMFnefqAHri2YBDwUIjNrYwZNxIKyUm5CLeGTpET2pt8jmtAy88zYQB56YwkBQGm/DzOQlv1O+BeONeGVGiUrKyocS7a2NribqX9Gdb2nXStZ34uo5F+kdUXaSvT5cybd9Yf0a1dMIsLDX5NK9iHrV4Oipu8oq+IvyLTntaZxkcAujiR7q3k1agUicZLUO3pKnoZ2Uqepi38OE8+sNJhUB+4o1spIKiR2gE2Whgqlz0iS/0wCe+PkG74gNHNRVe96TxKakwt1B5mf7gI/a5qtY2kN2yDGSpZvmNdatPrF1+Y/1FdkYjQ8VmCTNh0SqUmVtCjZl+sFlADnzyRtq7tqRddwuFmYekJyzMejctW99tZesrYCJ0mWVTYodvCZVXkYv6fRUCxkFzQt11oVXt70LucDchwRp9A7HD127RJaU6t13GwINcxq+nteJCLmN1/r+Jg2l9dBkfs1Ee3MQNkh5h3FH3uOH5GPIJ3c2eP4AujHLcrLyVKPdnGKwx2CDdEB+58TaBhtSxm6pI8e9IMpX6PWv4hxTJSiCGqTukRW3FZyRdzNK3qIBN8Tk11KDhbDttuw4d2ZHRcmaWhvOxk5fW4Y/zSvzXNVvq0MxRhW6W0PU/ViJMFwigJZ2RwGkpYMjFbdl/13mrKeazcE3VrhkNXZFt/62s0FVrbvXztvA/0eBb0qEX90ww/sbFBzP3Nsy6kGLymscAxbrbpGINw3g9mgcM4s1d34IuWpULdGVz5gX/tgYEYXFbEEYJ/2ITtPluFmrzgk0ql5sV/lZys1I7yehndhKxBURmN5jau4GAxyIqbpPfkSa/jxJeQ3ElEGzrEEU57fGycU5b9j6QSU9t0LQHq32ONjSHFtvlUQgzCqv2ZuHbrt0bhyQh0bm/Vwq3q8R8ewPe1NVmo3G7gJLSMxnq6CxQ57Y04Wv1Hlq7Wt4gZ0aWUefck0eqO3jMTFACIfScUDasTgED3pdWcfJhd/JqbpwbFubd8O7rIMP3wJXPp+IoO2Z1EHaU+I+NIeB0f0DuCEPdHKvRUcHycrJQz7jt+8rBLUA0YSEbo7FdLS55sE8ZjDHTh6BBJh9Q+qm+sH9DR8g3YqzcZ/Lgr7Lh59UWaXPnnaKj/fiYHOJr6ZeMVPjoK1ctK97JiVCX9gYpsr/ilHxWt9HpwBq9uJ2isAPibcPk2EXCLvnNLu6DDZ+doayIswJvlOieMQk8YYgg3OriKlkhgRTsh1E2tZ8yWX/uy21+zUL5z4V+kfLoJDnGR6vgBxUEPmTzjyhAp+jVsibBhw6OcxoiOjWCEaC1OC4D8YkrAt0AKNMPR8SAL5C5IobKXKtgZ/DpK9UpqG+ucWzKdRx+VpurX6k38JYuU5VjLiH32x5bXj9aG/0k2FQx6oY9CaoYLwI6p3B+aqvTOI4skQjAlr6X0FGEimXjJl9AMqZOMkIwfzCihFT1i2GyZOl4zzlTxrbupLcIdR2PT5YkM7wYWJgdn/D8iUp/pib7Gcbr2PbbISiNeKQUKFjPFw7c1nzxUp+wX+3TXcxA3u04d2EDRZSq8IyuuvFq5P4efzY0WXrI1HQgfaMCfoQ2Kr1cXg4t2aBl3Ctb2PAF7VGOYAeZa1FcB5nqULsWPaqpGIoAIO2B/rBadKZudKZudkYNPoO6pFcKAlFoukE9HaXt0GdZA2ToR1h0kBp8Jjr3HF+2jYGgK5e7cGZGAmldaM0KPeLZSdhuGKiMg5hdol1y6rMbvMOWG8k0gFMI+h4RyghTjVKi2EmQxOwi4IN5fS2jf3ODfuXJ8Z8iw4sNYlHD6yT4ai9HY2x7Ia+3PXYSZPFcFtLUgjxkkjmX5THbzmZeaRNMF4fCZgO6vuZ2/XFL4oUGvE07S9fc92GrS8e2+ST4Fja9Jz1ovvyvG4YuNn4+CQ6cqfk5Y19r3Lbx70nwu0N2R7WwsYzk77iGLJ+cLDlGfXe6oKxLT4LPTry0JT0JTp3ohVbhUoXsJPgy/le9P/19q+u/Z0RrmWqfBL+FtOdvGp6RbQVLm8vL4ppSQIRNgs3hVPlbnq54+pEDUiCbDjZxjSu+MArGLB9Ehv7kQUS0eS/I3dcjcuWceXl5zziSyoM9NjHBvWBUtCM28dmeejtmU4itbzF+j+U+/GNbkixHQaToUvf1ppGIT4mVOmlP2YVSu7oE4uQIwKeWAHxzJr1d4x70AnZ5IGFIsgJjokQwlXonNpiBYFiZjG6KfT6fLi8j0/OuRM1YcphumQnd36e2xRVuFUC+L4ALuji6rY4NA4qbysn9/dSnAV6a65fL4WWfYtxXXYdT925jKkm6uCuXdj/sZLi4S9ogDAY4Bjp9MaReERKwCzNjW+h14sKnqC3os/QDDrOsPp/7Ytj0zFG/fSL444sAOOkTSL4QF8SbQJ/YXtCQo0yChhAmkpD2WLsJ5gWTgy1vAtqLil0RyiSYk8NEujScD7eCE7UG+m/S9onP8uDCjoFBCzzbbO+xaMUTVJGdCGSbqvuULfh0bfJy6A7BACB6ibvbmLopqppgVYL+IvJiVZuqqhw+3aq2oKpN7OseOwGI+5XQX9kMTmB8FzAg4PiCzUE+yIMnaRsWT7SyQms3h5S9YDLYG+xhyp7v5zIFTnfRaj7wNzEe6o5WV1V8vhoN/AnGwwLNVTxmkFMYBJP7e7OcKcJ64EFgcjVQVcuqZpuW0w/xPTjRlVzX7Rs2BbBu0nnHoN6Wm2ULwLXJ6BFbtXrHQ5imsa9RPZhaKh7TB1Q8IJv9/MqJreUx1Qoa9GSUj4RuP0hzrWjwZ9ie+nhbwWt/P4hFwt1+kOREFM+DIqdhtu2+AJaeuH1wNEumix5SnzYeUkdM3w/KnFitfQrsA7+NTqPuPoQAnn3CJa2ydL68fA7pUzzPNhUA2+fQOedtVqj9vGPedrcBIV+hoyfEp0wFAefFQ7Uzyd2dNydjzuEjwu4cqN75w+ot0LUvaXCON9VjdU3tL+bnIBVyyavtOT+QYlfFC+6uu8k3ncG4/JZmlOhqnIWIdgaqg8VeR5Drzn0rx0LmVddFO/Qt8eoxnIAeUeCX7BDmvw4WSR8HDuPZ1BRvlkFx6IMllLuTZiEphdRoda1U32hBvE+HpKCPN3XtRe1J5VMFEVSTkOYWAZlbLCgm7wEG15CGWu6pq+UOse/T+/trDC4oN7wOggX9WF5+jzb1t2REAHvUtyq4ROcaP+UXA60MusCUnOM9/Zhd4+38ueFFromw/x62rxl/kBm5tpiR85nPbofXpKgf3PahV7+ST7Jbs+mqRGKGMMN4SAYfqd//IH4R4mPRoYbsHLs3Nt27VN1ritgdpzUPdvzS6vgYOn6peza7QZSufPvF7tvCffnyTgnpUMtG1tOvmCOt66eWXGYvaTrergyDygo6fbpHcnQWhZ5LDC+aGEbUuP1qJ6RdRe+foWOvJe3Hq06F1zfqe0x+M2W+ysmnuFSZSfiOlszpvMf3J6mlVj33YFqUtUxp9Y4RWc0L5krrIXLh1dOSstS2lEWBKQ4qy90IyxBOqR47aeAMlNQkJempedQIxacOI1/rgjW9LyNIW2igOjZQDYFQJ8N2jQBDjTWUtoqkzAYstMlCgFkfX49amFlBV+VEPSrlZ2OWGWXnLkLpIT2XbCbIa54HT/9Z/DMdPj1jE/yuu/Dv/p/1zs7O1tMzI9s8r41tVtsyyJJKqUNOz9X70I88CUe8nefsv/7jv0x4kjPP1srcT6w3/aoAqgccokaWSDk9bbyes7H2zBbsbifqrcBzqat1FTfMQy7iOaV3R3eUtuwMjnpxThrmWglXvh9qBrc40Tw6+4O3RG2ztUceHNXefx/I0RGPkApEEBJrbQ5ScnJjnrnmIEMTLR0IsWmuiozQeH9RITtBFbuqVbHPcD6LS+6UkHEq86Vu46+a13w3htN0FZYXThk3SXlnuaqdp9WHC2yIr2rAqTJLLjmJkP1ONeGY3hFq0Te5P+ufxAYNbkj910Ci7TjGoSsLmy7vxc4NBcM7gyjTD3zZOu9kzmK/VI4O2vH6xBZd+uQyMBE2gPT279MnnhbjdtFnql1F4rNrdCetPO0Wq6uCgaCrgieeIFHek6H+WgIMLODAAo3MBHlMgSUoHPuP3ypjhrAkXSHi1YGt0d+8T1DPu/WE2vW6Ef9WNC6xboXtJTlahN5JDW3qk/gaem45Gr1jajdv93BBpIaUUwt+GcNCUBA3boO7Az6QBkUOtLWTfbLtk+4aqWep7lmqenYnO9U1aleVhrfsLCzIlZXZgs7MPX+1lQY/cVvEzqvAOxE7ED3F9MRb2UrZQaFj8eqtFLG3Jq/2jyxS9mOdQpcNssBZrqPV9YNMuTYp76CDCRfxZp2M7DcJj86rY3HrqV+FsR/4sJSGpXlIFaQoogHgYlFhMRJU1t4pSad+gNXac50U+aTMhbB2cYT5qeiXRp+BzGbsGdB9mNu/xY8J0hO8lqExVgs7Ao6ALXH5ZJoQ3ajP5+YT3aap73X9KKB2Gp7+zBNs9ku+zj63vm5vc5uJ3dGjg+JYcjk4E9sxvcKQosW2LvG9MiXuJPfYt19DiskZW/eXwxQdMevH57bjo8P0mIkfsaMepqurtg2fvLs9TFdWVD5d3tzRS7uxvyrU+9zmAXToL9jan9AXii2iNPirsl4OT93XMej1UXn8xGVNTzQtaQvovyr9eofDihXIeEoEf58S1iW70hnrYTqFPYZHRGKlTxy0NFJ1Plhwd66YtHJAL1+0IuIWmWYkRzEqW8Za2bKQL2LarzL/nd5VD+ef7xRZQpiJ2uINM/qRlZdAyuQuYMp8Rn4KUOMJB06et7dtC9CT2mLVtvVEA/bDxDbeOHwu3jiEbHgmKLBCeohZb69zWiHOLJLpxFxvJXNA7vvtcTuv+GqqgDd0jYxt4+Y7wQkrfBJwJ0KjtjmG7kuWok8c8uAinMPhMWZg/O7B9AtX0Po056zTwJ16H8nFQ5PJ4WDnTOb9/V8VrhEDaCbgyRU8n+gUerfAsNSPw3Zp7sXK569gVCl6DJDTEgEHU/x8V/E8aaOM7qjfl/EaAbBHB9IXwQFph2y6NhXbtOkfKHWFIXzRw+T6iSPLwJs4ugN6WZeKWZ4mK+Hsc2kzXl4+ME8sbMbK3SEPuvj+Bh8Y87sD+QAPD3roxtN+wlwZeKCSNpxEAbXaS11fHkP17fzMGZm0z0sUe3lgPRYCGWFE0rnnSg/Obp/D9mnJTitawcazeCXgM5vjMr6mSPTfpTCMj9IT9m4CgZSsegrgjNhYPUJfBT22CyyEZ72YKKnt1/ToHdDx42A3Yfr7Y4J1cqzQsg3c1GiVVkdFRRkhqL53re9xio1zSaE/4nOhu4pkrq/hG0aFD5tVsRz8dxtfUIKj60rQk4/jigyVv0KPda93X8fq/a9kNfm/ng3iAIg8FsugDpu7Aqaq+OWXIGPJKvzBUb9+bSq7T6Gh+wIBEa9wwdyJPLGVZG3gn8kWkpu3btX9HIIO9i+CR4917X36NjYMNmDwR+BYAJIA2NXVd7CrEoSP9Wwtih/owmNgqXAKK8hUAENC0FWFq8XxHxfHE8b8IZyGs1/lLzniZFHzbdHPjrVxVrWfSUkX/gGcT5XZFfHa3tb2x+2D7S2PWS+GkEGiABu+TE0OXBJOAlrtih3t2nRsIGy9xuJhHLSxlcvd3jJua9d/C/JSUvnoWd/mGox3C2MmLKz+UvdOASUMD1x5SNcg+vUO7K49PgAmR0jCiaqyrLd9UhVakr6P9QOXlXn9jLh6PFPg0cju3vrPNkkTYTfTW+8vHvHL+SpTQCsVuIujPqxgvBYcJ9lVf7eaibvMxjvdwZ15lBsrKziXhL8Pux8HAooPmPXNw3ewKyLi9F46mPMwhkjOBJj2RWN8xFHQQWwJo9B1ZcSXe758KZO7L9haG+THWL0hrk86vwq2Q5/3aaOUGEdZqammLKqHXE6FSOCaxUvC9wdHNUNZyxAWVoHiaNWxQHVwdaP76sX9mlyQCAJfvOrzeLs/rmZmg0p7ILDEqOZ19gUnnXX7pCPcFMhiVLd5VU4/9AM5/+DGpwKCsFZh0QHqXJdJtw0E5bZ4+0e8lE0qb4ER6spn48mxMSA3CjomPIyU5t9pFt146B8rlsbmFurSQsMn5qmOX/n8NH5EI3AXpkR8q4EviSCrxAK35xZ1IsRY1GlOvxzRWDco7lZrjVsP24tFttR8uH699wJnta8nyWqV/V1JhxIuCbHGnaBUrqRH31BVD8iWliwpExArKf6wqpjJlSrfoILQH3xI7RitPrtF++0om2H96HpQgLWIRsjzPTYlvqHUjtqz9jLrtU1a4JHeayLBREaGicSWrvJHnqsxG+JuYR17OInYx8ysHMuHiWXcPO/ZRHrPOCGNFCnHUEFZuXoxvtfEjO4rX9EUi4jOFjLzGy+Q8ZbMFr75i/J4vSVa4mUYRnMQFOlaaVNU50RIwL9gahAPKbIPyKVlqJm6K0kwqRxkGvTCNjKEM3Ef/wTZjFV2fTFeiLm+YKS4vTGytZcb4tnjJQ3FRoZXQid6XpL4Xt6RKdV06RxGO03XTKT07mLIwnpP6AIciVmUzm+OvaGc1hYwIxM0Iilbd96K63Smc57FadtjLc9f8WZen9uc4m5t++0/idPYOFkFtD/Jwxu0trBKfKob3vMnLBI4KerJg4nhqAa55rMmNp8VHbOJzWflYkU4ryumuuYl7tzBCd0WLYsTPWCocVZKwexCKVsh6sPSE2UTalUTIbwUnZyQkR5FQZm+CpKDwgcam1hudkRLKiEoxFBgyFQBnIkmqrQ06LfkYZiV5aa0LJMzPmznttIOyzU7lQe57Fv+OkJQS9CuYedz2OudGDss339m9gxn7YntB8dqUr+2ZFXo270PRe/Znq4g0gr/kRJdDttRsJe1c4ACskqQmSllwQDh1If0pI1AcONNM7VpRij65A7HvQ9tfk6HlyIXufgq9VUZqiqSF99+ZA5TkXOaCIJ9fY22b1+X7esS+50nT0QOyL0FmzsspH3k7yLqiT9s74kxiNZ9tkc0bbeQ00thObI9GDHkLttiFKJ7qqRgWw2kHq3H8qDzExOxgZ8PKL5Dcu7GuJnjaS4ARjY5lNuNwkne/5lJ1hNzf390/OCMX6rxsP1HRvQCW71KrVYh+99Frhu1As1uEJnL1+VlRBvP3LNG5u7V1x0D9Ib9LlLdaDQ2cGvW6KUvHdThMtJYJk+YVaLOWDkijEA2JunUAoSBhnODMJQSiSjZl1yc3TbTvu47zNqDHafMW1Vf6BFH7oaBgNuDPulNA4rjlgoEN/Lv70v8MSCCaTLNMPG+o9PWe0FGF1+ZTJsUQMFuSMAQLJ4Bc25PYO5OYD4/gWqdi4OrIGown9hco1I9d3lz7vJH5g67iFRmaAhZX3sOtSbDZBwvyril3YQiBaS5YAIw+9C8mYY9NQ25mIbcXzxQs8iE/h7NQP7ADJzYK9IAZc+G9J4L6b15SE9gR0NLlFy9ghsiViCwsd6BW68G9l4T2HsPA1u1sCdhScPN+xRUjdbUKLXYmIGfKT1ulJbr4zzYc9eHgtg51GYmZ19Nzp6YnD3/QdhcqobM/EQ4uQvn58KsEHWm2Rfcxrn4uQ0idh2gIvJXwQIZNbbr17kSO1+j2PlWcBe/XA/bX4NbKEnt9+Fbc0FY/5MAse+W5UfXx3KpPNGv3d4a5uE2+CqPfTPgL26Xl58sYDGQrbtFTituP4GBXKMan4T/fvCkf645qCeQ8AT69BXhiFqEecOhbypqqqGtHL+hqn3s263uG3GUjUHfBkjP5FBsFT/s0e1cj26tHt1Cwq2+oHHaJauw26CgLjWb/BqcQM2QUTf71TQLtXw1YNLxt+ol3q82hl73KSjA93Wus1+tzn6FhK9GbQ1rnLMNzcxtEk4LBGcNeFry+SZlJoJjreR9556scWx81hWHqzzYF4cg3MHkNDWz9nxzj3Ye7C/GavYE8CEVl25mEpeedKIs5Qh1K8NPI3rGJaY/EYqnEtkz/lPYnvHH0T3jcxOWcWvGIP0c/0iUF0P5m8j+AACeENKbUbEnLt7/xEr8Gbx/sHWF/4t7wB1KMb8EnrhL4IlZAn+n34uXQOUugeqxJbBllsAPuAX4Uvr6n1MMmAGQCVbz8OKzf5n3kBI9dV5CjvE8iAbnjk7LuWI4KKTOVUwcqGQOIW184d/hwDUQEc5J+5zN99hi6ibQoBL36Rdsz/9tZ69zcfZ6tGOiP5IhPm8wuHb/qBK9cOnVWxFzHui6Z9bsqaNH87ipTyK5REenuX/T0W8SGDYAj++ajZGzfi5m3DYHBMhHcrbPfQqIid2Ac8hDp8Jg7lToZnaPgMH8qVDMTeTOzaOHQHtK6DJCyFKa8wIxlkxm9tjZE2qcB5Vhpc81Ky2W8flDrLSiuIbRQjrrsNdmL2zy0n/vXIISHjp/GJGFmq7nw/ZikC7AtFRJyvYeA47PECxwznCOX1skq/49DT7VqHPA3tf01fPZlwRVqcQBbkfqAPwm7/w/NbWg3+ObSxALGWReQza/WF5/vyRKqWQQ8/aXBK85TmSMeL3RUgnY0W41lE6pbWeAmid606CrHnnRh9eF5XJF969DJ/IeNW3NvlO4SQCbBxJlZQuKkPg2lcqnlv2BGcR3qQqxgyoPAkLyYXG8tMFrW56OYqEspW/LxJWvUj/S+2QnEb2B7zfi4Ug3a9MfflI5UmAbzgiwT2iNwcl4Dt+gEHeunKlbJsrXB/jTVSxOKc6/2pKg1I5+VL55oYCXXb/h3etO4gwyuKMR9LvM7nZf7RpQ5Ddx1RmYOwVC0EnaQLgnhFQT1Z8hfOEdzCSdV+T5YrkWEfcWVce8PqxdoidapC27kDJohDzfyoiEflgik9GFp1UP5P+EDuBsha1P0jucnDnAmIG6TzOoS14XzSynOi/5kGYLLlTRh66L1U6NzKmtgbCNKhV7qZ+Lt26AzFs3f1SOFtWW1F0RRmq/o459cHcK9FY8F9W8qKS5fhuW0jefuKBMwvm4chIWPOrfSWUGEWmBWJUkFJoxPh7ju9v9xpM2O1KTA0dp9RBmy+6veOm+engM+nuu/3LRmZjmYHC6nXQ5MN4RH7rnvCO/bNuA95VtUSKsaug9LU5DRx2J8IxePKXNSwAFD1Wn4ehChMyStZ49qpp6e9bQB/MOX1pK1oaajrLffy6vqTVUKJWTufUjLrSDqi+vthvrBhU2VVnAXFoiqd6PsGJrxplZlapaXJWF8Ef9UO32qjQtGNrhGgVVLpaYtYNJcuDtdHmj92pjbaNL+h++AmIlFivqmmkdHFprLL3HV6RFMpCGP0vRCasXtauRbHdibm+zNyaAkJ03JZsw/ZYELZlAzR1eAjeRNdXHCmlxkgUWkqUd/S3QTdAjwjmiEgbvOvLLQr6O+rRQcBCrOUzQ7U0/DmIxVxlT7zsokxLVM7dIZYpUYqOg2EFqL9jikQU7R21itSKL5oos9IpkLn1TpBK1/5vLWx33h3PADtCPucTbZjGb1dlKmnuUuzwFARYajMncjGb4ULzbJbwqF0PT6xR9MOmpb6YKjBEulUI2Dmrq9aCWex1ilb50j4NxPxMJY4itqdilg7KXBl8vg0sHX0MINzuLru3JeFG2cDkHx3E/VC02iwe1T9x7rAdHblXRA5jCiEEWdNllAMMSQwmDWJlbTdE1HSL4CXxo1Cd+slie4vFzChW6w6GOWGvmRKyULi2S0Fkk4fwiCRctEl85cbqA/XkzCJWTpmmA/kBScklxpp2hQe8ugk1dtTxZOKZv/t1NcCFkbSfshk2NQg7EO2bnrfX+hVT2ulDKXs+fPVt/cd9beylydJstToNFbQ6dBvsXkE3MiGruRj75aPdHtLDWRxTvzmYGOhrmYoJcvbv74PkGVJ+o5YpNSYqhooKj8Lg/FfxgCChClONkwbxN/wfzBlilNqhxcBmcsDq48fsaSU5Ydh9MlS9G2qhCm4WeX6YmXRwqAD8hairKTq3l2FzywZQtXNQzSVxRoxxavDRyKOwqs1ZJULNkfuHNN3TJKtNte7M2mvDAY6MmOww+Easrkfu0VGpHd3eVnJLY9EjXKrZN6PEN7KMZk/wzfbnKozfWjrpVO6pUlcYN/SWPXrYRGKq+VcZ3Z2X77qyE70417Qi+ZMmaPZMkKsY3wOS6SB6Thr/qkaVHIlYLHBjEmxKfCrT4uarw3P2pgLN4ob6+qC/Dg09te/ZPc3paLzaMKYltNhY7Ws4FnMELaULzvpC2H1fAMuGbeZa2r3Ie0nolPH71en30gxrMvUjjI/cXTjk943345X3/sBQuGD3Pb/hp5OTG6eXQflgJeVtUArRqkHdkXKhtkjV9FUCtFfRpFmNvVadtU9S/UmFohKkxjo1+vthnszek8ARg/FJoQxfp7hIirypj/ZLK9oQYciCf7Gk3YJba+leHdEJ6r2uWxw2nR0JNMCKLta41s28SpUsrT+6DyhHvVZZiqNQndhcFMX7WERAYFqMeylKtXpEK1xFKBdMKLnm+eZtQCXR1u69Qb89RhIXT5CUPk09FxAvL6yAVMmrS/w9379ocx5UliH3fX0FUc6FKIQFUFR4Es5is4VMimyIpQtSDIBpMVGUBSSQyi1lZIEFUTazaesVGx6w9XocdY0d43fYO1dru5lBSd0+Pw/M7SOnb/AH7J/icc1/nZmaBkLpnxusIiai8efM+zz2vex4VLVo+mUfyWamtEBhkkY6VOJFWpzK2kTSp1Sp/e51kOzo0qSmwLc+wG8nd6ab0IOQPGstwilPuxYhCtKkdfD+yLE4b7fDcxYg7/FzEtAkLW4/TbO8a5leg6Mvvh9kQvpY5n8VE9Gd+gzq6GxeNSi9HcFLgLIeZ+3Z0jMWpe4DOPH1p0fpE/n0s/14kZ6mrGf57OoOKewOMb6wv/0Ltxq6VPK0mB/gPIp2Kr5xEz46TbNJ3FmImo0kg5bxLMOddwnLeVVj1vsuim7oRdQwzjHCGADcFKiGiwNr8vCiShMa9qxWTvkkRWCQ2ktJfGXiXBpjaqI4xP2DVoHfhv49KMbOGrZVzflTSjeH1ZjTnN3Htn0wfGhvQbdOZpOXQJR4EVucDaASaM1f9QuyQz2LzRYd9a8vz0vgalYTjp5EMPhr6pzMKfi3r0Ey5W32uKx5Zqyc0E0Zg0480afH4yPxk8qMKyaA3oF/cGHgVeo/xr9QewlxZOGjprvfE3OOKAfa5yGKZ05dHLpAWrqDgYwRunj4mT45FJ3nDfh8jcMIwwrZywAinqFCbuA1Y0y2t45Oifs2s6ROutjJrK4uF/PVIFT0S6gPDyv6QBZ7oTWH8V2aFQlFW35aEgBppjwm+H7HLAdwnFzHuI6WSSqatDh4iIU/fQeUysIx3wt6oG2baLfBJSLybmXeEOSONKBxxRi62RMdU8apSpyR0D67SWkxYqz5GMUpsftvmEpV+KvOZhqItrNlT6V4g/vSNUAqiKmEmEkMPklkKWnjgmEBAwLtL0fxIip4BrafXXxA/3N1geCXYkeHkoNh6dkP+yjxwgRRGXPiqw6t6ISAk1ZtI/CflbjGigz/7iNojLWoFIMgfuqmfOZ5eiENA/Up1fuCi3HAw6cMUmH7JeCTgr8gxLUJTnmwncIEGZUUSgmHg5Z1LkbhgXSNDpfJJgMfItaFUfjGRvh5ceAqZ8BSi8BQp2cnMK6J5RVXSVOgo1VjxJgHFKHEkNwpDh8H1JPuwyXjnf64jaXpzY3MY3aiKfzUnKCmrqOCAqgWgdQrrkZsqUHQxVjdbHLR5jp02bGr0AzY1srbRqITZTkdO5eZGEjVuRG7GVvUueiGZx4uW9rePKl9aaxCcMTOjj4xQVtitmAT4iK4h7dHGrpwGYhmB17ej+gcjFgMfGSWQVTAtkUuRC9aTYDDcTSkH83gcjcePDffwuID/kfOfbUovIuUh0Fhecy9k9bPu28V+YhQpZZgmcYG8N43OLZPvUTK71BiP38dUCZizU18GmPV6X8v2zJF3aW0ZySObDAiQImRgMgF+qF+4ADJ3CYikhvkV0grIu6c8BSZcUkL7Q2IoxXu8ZMQLQV8VaHGqw6t4+hqSschvs3tIGdkQ9QV8LwCXvIshymZnf2pHHPhgZMfxO5XwAFPWN+yrdy0vINYPXVXTCFiAglxn2yUuHG8rdA5gzX8zjpA1TlcsTadtLk/2c0qZ0XTneUqCj9g3eznL7msivTHnMHRTrDvls8kPIfFHJ7ovdBXyMfePBUTlXc7c0nn2QvQoozMlb05VO/76gMF9n0JnlDBsyFDAhcwAgA7icYS6x9AVOQoBfHvhMM/SQw/zBg6GXsYZtD8vRJv6vuLqJGibNxy8K+pjwk9xg+Oqe3tXvssKPWB8RkZjEBvKNUC8Z68aO/alexECm75GApjj1oYOWOXmGD+RCCgzmX/JGp7rgN4uNT8kqPRLH7WLmXqehAWesciWE64GUiQ3lBmegKycufgCPYiPKsaPI4owwbC2JjnJfCPL9GDbTnwOC7m2dLaxurLqrrmhpSvbtiMYwpoQai9W+3BUrLbstgp13qqos1yoc29kNAUVEoJjIgphSmP0hOah9IRB0kRKVfy6Wn5gxHjrO11cuBW/XkKsfjKjLvNkxinC+XSk5ITujazkMjBEfr38YWRR+rDLKT3BV+7nBfjKNYtkw5CK82RcaTkUgVSx0dx0OtlGYxOtVGzA2IBuN11ORfJ/qaGIvXj9gJJuYfWBE2g1O3WgP0AZkT3DaHfQlGGBE2KBE5dJuoKBo9AHuoxuiliNpqstpgqqIR6CamBdyYOUHiI04L3T8vmkk3gAy3SPIlbo7YjlYG1bT5iHFilrSKZ0sNA86k7i2nUzBo9Z92SI8ebAtubIE5n1JZHyV6bkr6LghVmezFNB1+LGXWRahl3EQSade+IzIypmQCBw5h3Y7PZ+LvNcAbcXdYmRy7i5w3rVgIHd/VNGS4KCGXDMxNCoaEGhrpDELWKkdXyRKXfoRV4lw7haXeAohikt1BT0PkBDf1w6HJot4mJoipiJtkLSDNzUUbmMbLs0I+5KPUSsDdJkgZBYXSm4xkWDtFjTD8HH9REtSTCc6C2N+ZZSXGbcT7dyP/mG0sJr5tKsNQuE2w9Fmi2JL6iEqeUkNrqa+aiLbGjTOKWlSaZaFNk8B2Wg1xZFzOe6y+4DjQHPjzbdwe8+AIgFVk3FJvNiWP5heEndEV8K6VG+FE+SJxMP1/YHlHT1IBTxMVVxMgwzCo7OK98IDtNRbhVhmDT5U3Gt6qkvf4mjIn5fDrdHO8KQUxX0QyCEPV5mMrGqTkaUB2A9HWVdVWn9MOleeSJita0jZ6lG3qMfGFYcPtmKhjfDx3dCpJwgfGdwfifulcGxa2ai7zLWYa+E+4h8FGkVkJMJX3HRtFyx7VHlivP+Tkr4gX1C8FlqrE0j/6X9qpyW1Q7xRVW7P/VTxXBpSLBrigNkCVZl8u4eS5wnHLKKKyUOjt1+YhLXJYCcoP2icoLpPTHg5J9PXAsrpLV84mbV0trNCmktK0tr6izxmNBlgRUNpnXU05KAKlZRDOijUeEcfhiVz2FFAOqKAyCaZae1kBcu9D8SpvsYWrdhMrL7W3zqIfJpblXzIL2qFbAxAOtnUo0LqiGljzp4WjYZDUvqD+X9cCHsVAMDFMEprDs6PBUpJU6sPMqo70lRQEqk7HYkNUOupS/S10uxBJvIBdHJUp0B9Weqs+PVX1g3Kam/kolElaUdw9WB/doLF6IeQFPUj8Lsdhb2oydtFkEswfiHFOowwfCEFNbQRDp07DCFc3hXXfNqc/lc7U5tDmMVn87m5tzGObRayOf82ttQan0CQ5jDT1RQ6r0B1NeNZMXqc/BCQ1dROTM5nhhcOp4YhN3p6DyqROfXR1Uo9MNRCSG/NdJoM+9yNPdRpA79o5E5uGyr5FQ/iuqXM4F8f8B5Nop0bWyPzGrpFi90Xn+8aQBwtF3RYKWERoi84hDfHVUf3YsjCZtZ9/idu/3/vZ17dMKde/Rn2jl9g9wpoXzvR+/qo3/NXTU2Hv1c62Uo84m00xKB7o5yabmZO9Jzybw05h0Y6Dh00HSDJ1umZzTqMFyDiaVuVEcR046XYt0lZP9AtliJ2gFPjgjTx5SketeW9yztJ+NHZMzgd4Grj4bvpKMEA+iVqTEFzLPjFQ+dzghroDERHY8wIfqxDsTFhkJ1b1FuQuoRSN6KpUQc+ddyMvVoR8oEFW3HVWKWesRsDpFykFsHJsDhngtS+06i3elY/AbhTY3xjsiW8mcbJ9qFNd1/vvFeTQHUpem+zfFOG2wiBmvUDNdQnM0cmYuo5eYzxuZUDzDXA6SRlAeIofTUAEkaNscn7XJ7JBDqeWh0E59d3XQsAPcxwhDX+wNMD5ZL01puKjGlTj2j5j0WYA7t8Uy0uWh4e5SFwgxMfdqZWc9oAcZj/IUWUx63pgq6Nuc204Q1e5TT3SyLkl+4r4m4I7fSkHQiP87rEWop/MshiASdXuLxMON2k0MXIy77mVTmdihQf+x4j2gzMJ5bLnITlRgNsaos5KB6luKILPAsC6vMfxcj0PJQsXkRgoAT/lMD68c/KLA+zI5hwlHXYEI9TdfkBlJ7+kEUx4DMQyCoMuyvHVlxakUCBNPg3ZvrF65e2Tpxu6+rL5vX2wHg+W68UIF66rmq5RYcV+9FZXcYnk+M/OgBycssYyVyIcJEojU1uSq2VUouDsrHwnCsqipYjiph2VQjsI0cZ+qQsHsQPC6HGayTKLuapfu0Yi4bC7//lDQxQqwzpV22j9Na541qL/SYXweLNHRlFMRqV2060cvKwKTxwvEVx+N6rlLEucd8Y69HVY26475+jMVmplasC6QvRzYVbmO1G1K8I++tRBCN1+0Tm8TlqFcxPOPhIjVF/KpOkj1zaV/DJHxkBpPM+Xkf5EHX5I5XGQRUvNZEKoIjtDF68G9Ijj61g+l7gC1Pdk7ByIGRP/VgLoLBD4fBTjj34N/gE72QTJsUo0N3KDjQ3BWfxW4v2gmHJb/T61EhBWHhe3lXTe6DSgEnCmV7klR3Si2HqVkNOIfDNA4XQtIN5CoZqphvcnxmrmQilbtPdUqxD8Jg751gYNFjWeZhfFGTz0gTzgR5jPkmnlXhTb3kag9J/0jGQpH3FkoDLUwwtA+2ZkTYEO9hhr57lIXiAA3eXDnriZuYlegdMwqdyIaYgwokQWDAAjllthuZcpkXYzVTKktZwFfgsCqnoUY9UZczBp3Lr6NiuODIOimXSIlnn5Rju9L5XGwMVb+ujSauC/4COPb6Rr4bDTcd73q+EPR6dXySUY5TQYG7e20sLA9JwZp7pF+tEzCnipakXq02wbBCbMP63aIz9gAO4KWgu2u7Yh9Zr2i4TwfqNIuxtzOKYkt2U9ICkOy7MM5wzOUw/YVrvkCGeDcAko13rTHNHaUp/21LYyhkrFzmwHMtq6YBXQNJu1Ur5LpwUZEx1m1iKIKtS0Ha+KbIMk/c5+psaSyuhszMqf2EK0Pk7XZ5Sk6jp6L4/R3mQbiysrSqBUWYJ14hY8amjl3FM580W2uutoBrLjUbZ1rqedafX2mtNVZcFUuhSRBaDAnWEa+bZzDAtTirTVpbEkveootvKHGEcSFdecN7GVP8vVJM8VsU4Bvvtll8hmshtzQT2Q90/9dGeLcrTfUc792EXJuoUiHd0L61jiL9J+raNTsFTJbagY8SgicgPswxg8S8xEffARbeZOZC2CkEXigGalAL2mqsLKkNmvX/MnbviCAGlNceo3Qls7O38U5UfQOL5crZ082lnD2POGtNilvgy3vjxM7yUcmZzWylQESBX7QUIeywJYQogsyYjibVVTvSraq5gjCAQaci97Ar2b5MTDTESFJiWK5UMpOLC4olFEEH40flInA8851SGy+co1WCLndGx5GJHWW7FNnuXCpygJyDS1djijSvgxBcTwFQUElEAd1F3+q86i3SSh6zNSHGzEY7pdcOmsXtLW3YjL1hYXnwIOJGFSOEN3RSoBsrX0oGi85WhSI86PwedMh1xB1hEqFnJlCH/NQtTz1lYzdT2ilgf3s4wudUxRmLjndLIWpBoICOe7tRrxcC0aa8Y7nKWOIUlZUUNuGGvGfrImW5naaxFJS1plKZDmZh/a3EvQcs7L1w7CfadWWmnsyarCyOscMymoAF3dEYTTvUeuUsKI1vmnCPGWf4+nFWOFHZQxenp/0nrkbl9ChVDe8rk06OOpY7fMdXo9JFzfHQg7jQkMJEEq3FghTTArIs1JZll8DMdYZ8dEJMHmVKpmggEq3O6EqzZXBpq3H2THOlxS3IOEDL83cZc8HagrHm6VAuzlF7o+hDUqIP2T8nfcim04ekmj48LKIbmp6a60yj/RQzOCjPdZGHRk0uZ3oliaM+Erkh3IAsqhDz3YvqqnmYOzSojKxsQpT6rDU38Aset+1UKkECGbQjVaoIt0+Yu6To6HNFh/KE6fRRwdGH1epX7SO0RRvYl8zwATQ9VYlxqISng0odQnoSjUP7kNV/naKpUvWQLpyoPrC7GHUkG4/RqaoPZ2CEOwR0DSbrmnAr+yXnlFQK+Pso9iObkeJOjor1XNn8PvwZjccmm+J4/GHeqVctldD4kOkyAkqpScetB/6H0EAqgCmAzvfdEe5Op/66hZuurElPoKyZXtOeQHqsiiY9qYomPU5Fw5p7nSolL6pSMGzUj/7YLZxBv+Ql5o8cVx1MNPEeyrAa+nD6fTjzwZ82CtTOSxMJG0nIwGdlZOEipyUjmuY8LGsn8PqYgYpYy8CMvY+H2WZK9hmGGdkYRu3HiGMY5avYGSGGGSE+rsQwI4FhRhLDbB2DYdr1A4Vitn48inH+ZTHMoUQBHMOMJIZxS9jFrcQubREYqIiJWPN7UzHMVjWG2UIMs1eBYfocw/QJw+zBgMdjALpO/eA1iyeW+yTLVq45Fc+U97CEaPTVGI7VPcEQT4JvrEanYJ2q1tiZZV+dADT5p80G8tRTEEXV4mHkjoIoNDu7bxXSHuMy/4Dx/emd0ExOgDv3qnDnHsOdI3RJ/v/RkghErlRYqcUWMx+J1A50IVl9qZy0AqxQXAZK2wZcdKoEMgytTTofl8L6Cg45Qi8nTjzeG5jU6so9XZ/dZKremK+FdJWSWiJmJMoUAJrLTzsq/Itvq6DIKFRz5uKlCNqG92ue5N4DUadoaSpuZdiMGxXC0mPLct0oojXFk/eyHZGSvFBaKqA8rxJG8Vpe/pydlZ8rqomrL+Iv2bG22V7vVWkuMRuh+05Uj5kU01pZdZmWT8+R9AmplVJVyrEsq6qQjFVi1QYzYohTY/Pyg4Rv5kPzOt1Gz9B+lJ/cChh2KdMkMLspqqUDnwutlRFZZppePZ5tCc2NG3TqKKkxObHZOit1u5WBXZRCCZXkpG4FERx1a7NNR8d2VKfpBomTYYlwhyxOs8/zcRrH/o5WyXRCHmupo/Qia576ZZQipkhcNnr1lKuHEOj7KixdhGoGqRiMlJYFFvgISzylHXLVx146cWfqGcyS3XyjwY5RzjTQqoerylLHi/zhEBWArrJuDTFAPhnHyILIKPSYbi/SUZpCfboj9avo0J7Wk4rznaKOfpgiGyXyicTlq3+zDxhcj+2DDn6pt1K4JOFUAtKrkMsmxuxga5qqBY21BpWC9rG0IaOpC2w2SpkZz9RTWm8T+ioWmyaazuzFz+zFH7k5y7YowSFDnWqMbA+gv9E2nvSrFMwxth5nm8ura43GKtCdwFjqwKeISdG+4TFqZlO2hyYTINvPjP9U+6l3EQg36lI57IVV+4sxGnS8Rtxqj2Gb1NKTHYN5UuZEN5y4UQFaUtcCZSs6/F8mVcCVTbTeSu116GuwdTOhvZabfRBheVi9247L9K8UsEF6EznVK2jFJaSYbWynjRN2ne//RrjJ1FqrDgs6wPajMhRWxrywUjtoDx7uqTPMMSounQZ19vNynm1myvTTuOTxbvxH38E4zm6Z+NMFJB1ym3aw3COhyXtZOT92fSAPObPLw8sNx2ZNZoGedupMr7hyBnb7elTXzgmtFl7Jyfng7QdS+ioiYtgaNancujwUh9ZCMbFCMdmxi8/AC6aiNkAe3BiPbuHgclCLXnduFbTOzpZ3JHWmo+m0Ek1HIpUuu4OQC65XUMZ5Ri9JHnaQwmrK4C4yMTNa7LBHqj7EW2xHsKnZQm9nqFXOmQ8IzTd5t886jthKvE6Tjid8HycUhRWwMscPggu5ADx7IEe0F2oHfh09M52dT2VczGUv9lsywKWIkLkKJWu8ZHVZxs5srYkfAHPix0qzJV+BWCDfNZZlreXGWVltrXlW1cPIJ+LnUuvMqqwp7qpFBbq0kk2ttprLsvZKa7m1tqY6oxSxqj/S9MsuSdMkP1lbWltbbahvVs+cOdNqyo+WllZWlpeX5FerZ5oNqIorsWQtBYxq7UzjLEwS1mh1bXlpZXlltRAMNPYbk9iPEVEOR0NKGCFzFqROp4EBvIUbNv4hUBbMK9kX6yc/FrHeY/JezcigGL1jFFbfS+tO8Vg3C8dah57hsTE7desMlw64/4FlLoFGYML28w4Ozc/VqUdiwjhxV+RNt6DbcT9Em1SRyL6XlwlDvpHlc3Ob/g1oVf1+h/3uJ+4NFGuinvsO/k0Pwqwfp4/dfiLsmwmzZjz1lBZ9AdSsUA03uybojpXAwo5Pb2VNMckrMH2LDp5pRUiIKm6RStEz/OpbTzTUvwhY83GQ9YbobUjCpnYn1I9QORNRnhvkJugBiguiGG8h4c87iGPjCSDiBdacxJOyBRlLf6HcJLHF2ChmQF/A9vxE/sCGfS6+r7/+rpcFT3WRMGA7iBmVIYPh9pF94AJUBjIPcCWzzXHLIjMmvCBPKqNlrNBDl41QB1cNrfiyzHinWk6anSXAQDc3c3mlg8M6VS/tOK+hHedVOGqW47ySulrHDhc5gCojvYbFSK+swDTAzIjCYpRXDahk9K2ivM76TRyHkAYzi6dzqvgOMWFJHWJBGmowbgKuGi16olljkauhnZicLj6zFmJnnuIBsxjZyESidYYSQRLAe4ZDjHUHCk1J5BObT9zEYjyBZ0CcgDb/GEbGYci7tq3Ohhq+8Mat7KQd23AEZPx18zH2SbFK/WWEKzc2w3RxliCCTcRQG1JOKYw2T3fCfDfMap6akeAoKQSqoP0FwlO1jcZwpaA4kpe5RxZvzwV/Zu0lvQ5KBVqqYgmKch63nOUfsXMn2fmiHIqDIpUDLtpicKsKp5jEhftJqMMoz1wpAeeSkK2L0ZktnAH7kZEtoIXOjOAB26M5TnNqGayHBhgNYFa3yVqyAXfqRr2jg43Iw5iz9AlL3uMu6nBQr2bxKd4FjKNmcXEeWv2LuN+zs3Tzz18vexci48RQzqtnt9XwFAUgS9wtpReMi9d12iy4fgcImp23yykWqIMjO1nyiF0taqc076q45LLVZUfiOUNeZptOlfCiIY3BW0er/bzKZkJfA7nL7JnkZnrKmLP0oTW3s2JuRVAnXp2TN+LYdSpsNbA2nwoFaS2tEtMdxUU+IJZ03qRekJHNBAYtjh1KZIRyfhAFr60Y8yWvYEfWcLVl2MS2ppN+ile6bpq6l7ru7W77Ste3feFMlG1F1xMLHyu71BUM9S6zDAKFDwZ42sgfqc6Sf7HgP1h5hjJIJgWUkNiEPKGzXCbkjA4LMp5UkvGkSMYTTsalS4MwQyUqnhSpuHrDUcpkAkvmWxEMLtkrZ7sZlWwJESi4JxbdltgZAqRBLM1EIpxEUv8oGYzyGsg3V4dCMsnEL0xIgDHjGfEahjFenUNd4dgau9JVQlAuisgr3mSlN8W2ELEE8IitXdA9Xyj3rOmg8k5Jk0txhMlXKq5wM/W25LGSJl0qvwKs70XhASYuqNoqcR9sex/9g2MyTszQ6vzWY4x9Pwiz/LCOFjhxZeFGf1P6BsKHaMtZG+aHcSg9EwIfK1DzKTYfOEGxlRQVcAnFEjtCK/yNdNOv1aQJFxpC1XpBshNm6WgYH66H+bUE0Pfb771zQ9pJ1RT7rZ6Ho8EAA/aTGJfkV3oRuVd/EGSJyPhp1Xqb0CysVOF9MMrTq2l3NMQVjCsWpBPBmHGrSF8UUQpVoeHrC0zsmFXNVAipDFdDJJaRbjO4Pp6KhVi1wmipEWBI6Rl57AK53sUFx2YFmpFLPVOx1uPxCJqsKq/YA7NvI2dUtW8BVETrCfhT3kQslduIb/Ry4Z2FXCY0o/dHApXRVKZtdQcjandGC1tbu/l+rNYr8INOUCgbKc9hVKOPKGSYvTcjx/GoLw03HWUgM8PSwrIymQ6Wxm+3VavNjaC1PwvUVQNZXc+Hxpwm690sjWOoPwzrtaF4QJ1GJK66R2qVK6ASE3ElpRWR4IMkGGEUw5wXzDv7jm00ATj7dhWORnqWFaqay8u7mQ5gMAOMkkTEoRbLJUaWlzSe8FKJYpadQpgHa6KZVyQlr0stSs7EL83OaA7Bs/lUjpm7sJ7BYBj2al5SHEFWFAmTqhFkRQEw00EpUPKlQVQMSfy2xuXZFxDsFvd2WBncjWkTdJlk+LU44WKqiUwYIuSOmpq+PWNCYqKyQw3HMWPo3Gw87e5KvOrbZbHRI8TsZk6ceej+T+qa9TitI+1gxL/DEHJco4yRnlle2MFxSiHFQzyVlstGdNH6X/l3xZPp2VRWJvp7Rmpulb7Zs1I3LSuu8zY6NLuGL22qF1zmuR3XHbdYUzOutgkJJl3C3EanQ/pzBf5gJhxzi3lJWUZg5ixp1FN8WaotGWzr9l6BnLq1v4WSWcegBW/qVf9CNLysJZ/Z2Zk6uwFyiiY6bo+FgHiS1nuwClIni2rjVDoaFpdnxbuLOyfd9AoZpkRUK2FxWQpegMsoie8lbgriKmN9sux/rb2+cdHIZCakkl16QeZfXTVZX8x0hBKukA2L1hodE/nWqylp1zCbjVbpxjYe5pt4A7XxXrbpRyAVGkUKSXGKa+5FQZzu1DwkQd0g6YZAgoBvpcc4hQrIXzKkGvWzYD+sEYQrE1TxEAJl7YmG0Gu1+OFB1AtTWTUY9aJUqLliQF/xuUuZyk4Uz8050MSlbCPeLLQgvJhFD+R8XBrb/o7sINoPdtQgAXPsFT5ypw2yFyLmHorqebqzE5cXQIgZQYDRQURLUQIyQlRqTEkZ2cLW4wxlPxmM9OhxMHwHJIFoEIfezEy0sC8fJse1ZuSMXnXXQhqIHKXuVPSAmL4IWcqozPcp1j4CBq+dVrJSga9ZqU62gMOQfJFkZqOFaZwQVJhpzM6ux3XrOzegixt/w/TlBsB6s/4kmzY7W+wQuLQ/W5/YFnRb5tWIHVa8WnoMrwaLXi2EJphr0O3SRqFlXPVGilqDoF4NN+KIDUj08yplyGiKlJhZUmLmF9NeZSxEusXiSVP3WKcjhMmf7cQeCKvo/XpZpiB0hQtcng+8xcXHjx8vPF5aSLOdxebZs2cXnyALL3IK7Abo0HOC2p2EhJ9uFg1yEBBCtICnMP0y2WEd8NRBDZFzpOQIv3ZO1D9/7v6i/FWT+db304NQqFlkAnF6cDSIZUCbGFSXu0vcowgzFEfDCV0mlt47rhix2CqYbIqhbPRJ7qT6J4AjMX9P8Qo1pR++eAbC5pWavrlOIXDdUCLwkBB45l4RRGqmScaNnMyEmM9W3ZP778ugMMfh99DC73Qush+J48sf/xg8HzrtQisVuL5inCfD9yHH9+VWKnF+RWca75NiJ9bKJQsNh9ZJVoe3ODl5wMPjCUPGCEM8VR91TPcWyZCjvnDsqI2RQCbISYw+LoKWREITgaqJIsKMdFztYCPapAtlKYp2DrHnEZoy/mCVgBaX90UbspGyrM+OMmXjNBMXPme1GiL0TAyEfaSpDL0TCoDoRAqA6DUKgOgEaqfI6egZRscqBDxdb31IId1GaJQ5nfSERHrQTHSmOZ30hER6qgE2ExcjM1oGHYb5hRzWeHuUw9DoJRHQC5g7SUSAcapB3OBBBtB0+S6CeURSefYUtQ92HRy+lylv/vf5iMq17XqM6E7Tux6rWS2sLUw7x3XhC12m1WZ5M5qtBgArAbdAW+SPqorVENFNYTIp6l1y5T5coNfVDsRTRM5VT0SbLIs/twVlKejjkftUsg0L72J0aicTdEgGs+Uyt1rKkZdptqBTGJKkiWTThxyKBAnhWB36Hxo5z+FKKXMdSWyh/hJEvnrIpaLCJWVhSU7Ecx7T+CSq4LZO4S2bzW4lXmKzW45kEd4D+MKlodBTci34PmTTNl/cVUZ4cUaRGosG9a+x1i85A5SvNGn/0A38LS5pKzvHGcvvwLkxqos7YQO+Z9dWVhvkpaAvwyKfIIOxqtmUfkOe532mlAe2uSYAsZT9DvpjEQaKjhwVDWHY7EgsvNg8moQ9O9rjioADzPirLfYHZyuaOUb54ZInvJhXwZKWrBBVrK7COxgJmtKpxAq5sF5XOWrxR33q7hofDYGJxMWjHDyaRjILVqYr4rfJnYehDAcLP5YcD+0ByVaaSSGVEgjT8Djq5l9FFsa1VoogE3DhdlafbhVQUhg1G6q5O1G9YB5QUfvMiRV14spcHrEqSLOtAtjn4qa95JiT+swezlU2/A5duN/FoCtI2xWGfoir2bBjTpj2jjNBS30yEnJTy/jEskVQvQHmSK3oEJYQacuXlpOf7SyBKuvErbo6j3zyslF21bO+VDyv0mIYAyft0FD2oJFRYpQ/gjIsLAygEIRH1aowb4/KkU4i21ZI9aCxsLI750NLueZbjTKVFkVqrLJS1XBtV5TS+NMCoSz5ZxSh0ZqVvamRcF9Ppd6U0qqZ6RZnb2Jt4SXHkcgooYL0CNFb+sIpSVw8Tgr3KlLJXTJLGbeMxx03GBQmoBr+DsK6c/5eYnn1AuQik2XAV6FDaXRtch/MZA5RETgJqcOyw5I+t9hcUgiRkkw7BIl1CMQoGo6yYlUfMVtWE1UIfUoNvMMTsNdllTGRjtabOPf5KttZWhAcnnFx+2EL1LZMdjukvRB3WEWbxJTSGQpLXddEsNL1U0/XVPa8qWZVrb3E/IqiwDIQztW66dvAanPhA5GZznL2SSw7XmFh2kkQtryEDK9g8CWyUzYdeixs2qt9UdzXUdPifSrSUQeDH2tHxXvhrLVRYkg28pxdLbXheEVS1NIkk5Wt8LJJ0Q5xVd59MVPJazJD29QbsumXWEhrxD1eKJkUNWY/nJ3HkjNjsu4XKX8KN13T7rZKrSJfGRKBO0n7egHoxmgac3yct+k0tjcvRR4sZU/BFPPIK0505K4fsULAYqgTIwZrXtlM0knZneMM5Qjap8KUFlgZTJHZ3KMYHY2fUui3O1aM1/UwL8V4hTIPo2RekzcWOq+jlWWJbuRIkpwxTJByl7fijWB42kTmrdQJdXelbb+yX7HzU5q47Km6KBatVLYgYtd2rRCQVwfaGOJh5O/GSDNRwOljPGwqlloCqEyIqka5G8TcjoZY4qGChVdxAbPxwitJbyIEo9A7SkBiRHcTS0RkYQ/fj8LH4/HjKOmlj3WGSQywoFrDuvxZZA3KUMDKUEN3ScQlApEV+soWgqS7m2bkty/uWVXRrX4fvcPIww7VHCLGrHoSbyk3oxFwAWurn3J9b1K8S226cSqciHALaBI033RH+E8fng7g/0Pg7Pal7QhpldttY4y51T5E+BiPpQPToe4JnpfIuz2dA4EKa0XjcTal1ghqgXx9yGXyJVTdz/mHRrqXamzHrW9BMbtj0Mzsvn8I491qq2HCCh+i6YZ0kqD4rRi7AnZjbg4NgoQfNTAgWBph6YEviMYIS6kj7r6kDoNQWRz6+/Dh4QJGkUxISzCB3ieJj5ZE883xeER/JZ8moC4gMBtNVC4iOhAJLqGs0KAKDasCTehx5B/RJoc9vLfAENMKmO4gBGFS8F1CBdf8vH3NcPh9DMXqGtv2eoG+NRtS/jHW/g5zbL+Gtx3K2oS1ewSt6vzaGGvHSkipZSuMBVJhpl4w6miueJZhOrSwZwXCvOTvFRjuy6xEkI1dS5fV83enhTipW2GiKGwU4rbOJRYv6pLjXnbau8U8B5VBbnoTNvglMoQf+FMl5PaAw3mzM+CXpn6t5lnvz87ODhZ6EuXICyss4jdupQoFpZrURkqypcTsgqq2oElcwgQgGl3sinQG0gD5piNsJwxjGDLvJwU5qjYCkPB+uaYLtdOi/7TrEnJ39wxduJgVDYgYky+EdyUHd7KFYmbwrJDAFbEjHuJ2L0WMUKckKbMit43yz4tVIuu2/iVD2FJuVBX4NkhF9moH3TmpURmzWZhRMxOz7djkgbbHb4eHLg4+twYPq+jn1uCT0uCR0ghdZTvRY8+AliYykagcIlLynA9xlHIrOEnvi/3zCDIV6t0VDzPQ2ZAEJZOq3NcdTHftsRTWbCzvTUm3mh/jfvQe+rk42jFfiARFsT0sBCFQXgIycDdLXWN6oibCUzlqHV398F7GHtYj9rAzYA9PBps0KFs5YSKF6GEW9RlhlYIkrPT/t+NmqHkWOyxqUVjUgC5LxRgaxwn1c8n8XGZuR/RVaDEBJ3VZxB5lme28xdwWSWV2EtfFdih9NlZQQBA/V83P5poahLR9K9rUsdk5ypEDPSin+m1OyNtft2eCAZtFZ7DcTYvR76EzFfceVjkTrik29HWsC4m1TsK4ioWIksgJkkNoxUsqyuqFJuqYSdi0QumTrW8SisBGShPuJhM6QlEknbzTlGgTkS+V+Wo8ztXVnY69W8/5bZ5xsMmEc009NFFH9IURbrleLnuLNZ9R+Z7lGviRq121pvYqnHgG/dfMoPI9sdw35WEd5JZ4c0+7TEqv1JLa9pbypLKcgYwFLs8NjSapEh8/wT26lO7vR/nVaDvM7ib7xaCcJNBNqVcPMH6J5AoC50hf2VoU4Wk4HoM0ST7SkvMQW3MTLYsHeVvNWk/TvYkq6AEmlrrJ7igGOZol3QzFHFmuKgvMQ35SOFeUOF6xwPFuFoqYS5jtjrfm/fCh3MSYAOzE0Qg4rzc7i1Vwxk8yPGcePGKj00ax7OlVwzVKprGUBECNqgXlrRV57mXNeyN+I2MpZCamXDcgG2W4FcNlOZhuh/NYkqOiCwTFVUWkYNyhKw+dsiNC1BzNLjvEWGEYhdRxqxkrPbN2UUwQ45bwhtcc5XR0WSGaZgXQE9RnKvNW8Q5cRmtMim6nle3W2QEBthmnFUyZQKvpVZe3ABmphB5PQ8wg9zR00GOwUg9qth3t3JxSm4ox0+WMWF2zGK8Cm50zrr7ITbTL/GHiMz8SiyEJH5+6M6Dr2DS7EsDaaO8X7Zv4rp2TJXOAm8XcLRn5VlHuFryCF1laYkqgyOexm9sh4jUbaCmxjCtKo52dS5RhXDY3p4aRbGSbWqqNfLz4gv3z07bkewIj/0rkF1jIDw5ewCBQIHZuigLyoV2n6iTbXyz/gC8mAdSUDBXM/OZUSxFUjwKZiESAb5sQCbsyFt+gPTJ4YMQ5Wce4jRCLKSC/T5CP2eP7sEfCRN/SOLTWVpZpM3J9JWkck+5gdN7QckNi0QS6tprS3FAi6ScGrUpOORb17eaiwy0UUNwMEBLpI0EKXXJDzbk7Lsh0S65Ou3VJKik1h3rJmcjPVvhnx1SfWMisOIyVZsvkNpidJRSnfIALAn7FJI79WjuUL7WqUhDiNALMo4mOpMcMnzSYyxS1g7NXsfKwrE5hAkeKXVdZr7wIzprI5eWOyviojIVcBZiEwCmikTAVo1s+QXVrWYAmsViSBPvanG0UUKJQ9/1hPQDCI7336CmSrpuAJdJzI4Ul0jlfrtYBOWe6h/hnrrnZPrCNL2P30PEOjjW+3Oe1jHUlLToUrw/h74F7iOdHoRnb5PD0sF4KD6JN4fpB6aUymhNR/2PbCnaBGcG2j3nnc88JGdA8kvEctoxNYezyeu4W2RRiHG9eTB4FZTPDiu+jkpWhV1VJ24FvbHq1GvbpTGLhinPCI7g6BRkQ5L3W9K6F6casc1BO3oNAGhuN9olHtlQ5Mn6+k2McwQRLjxYmhci80zu3eFCr40I8DntQJoqo8mPE+1LymY2reZeYkbVo+DbdxfuROxONx3GFZyYrq2wPeIX9lG6kHblExOAUmCtYzAN/6trp62WLBesLFuzAVTN+ip69JaRN98NH5FpePUKXw5KZMjoJzxzQTbYwX0QEdA34jwPt33lgWA98eehf8w+4Pl4iin3/mrvl78t92D8REQSatezuQ+UK6tL0gHbwd0Lfv8/ohbkb3Hs9q32U+fsgRqn26ExgqLE9yX0XLVD3JPddvCTeO577FlCNUXsLZ2mlOJ8SbOxX2+YdXe7WDx0T+mOype0nthQTtO9e87ccj2pODvwDzcAI9vFAgMChH6rrKaV8o6cDkyPHPxTIwj9kCKXfoYNElEZn1UU7cWXgbqlbrTfozTMcxMFhza0lsGjwJ4LVy/IgyWuOBzhWvPbFW8erB1bXI3gqWObSKFJfG8qXQhzoLp3OSLUvtNyB+Fp3ehCw8aGd/TFocaLUMoc6zIu1drhsbOQM2/Y7tZpXmMZxPamORE+w2a0WXl7K30vj8WFlvG4oJ+081rUDyhzaGka8rDysCCjD7yxlZLjDSjXrYVHNygpMA7gyh4CDDyTHjp1KCaH0qqh+VTXpIx1zxg5bVEGXyki3Wbhusj7iAdZzLo8Kfh7Ry2xLMOWhxyIBqd0qhAK60dU5rTJ1OwGSEYutUyEMKT4rs8Q5dfnO8rlqzhnvjiWT7GY6uO7SkgqYQ/ryNin/MOdB26LnkrSKe/dsqmwXyFZICUps6vG3dk1zazcqgvTImYRmmJN8FiNimozMUAgFZ5g50mntx4+E6CoOxLy82uU6SrHWtvE9p01iHa+5iL4EwFNsXHGA8VhlKi9YPIWuPyKr3ZlUB8Zhot/IDzQxD6Z8/zRsB/6jWLL5T4loPYr91EXqjvlrZvqS5sZs4CmMeaStRFM24LQ6PvKFbj0m5yFJGbS0nAJlGDkevRdnOtK9XAOOB9YzQitmHZC7DSNxYYQBcRqT07T8IgWuLU2vncEMjjzEvewyhi4jxxNfsm0llU/xNl8cuWv87h4bNhoVdikH57BY7URX/KgZBiF6xS3GoVPXvDZLIdsHfDIDvJeMtCUsusuJperSMlsldK684C+Km+a6v/DGaWcV+VfgsJd1gCcwEHAmKnCAJdBqmL2M6Twi2/lc2hGk5cvvlBvCJSryokVp9G4IesMxmjYCZ4tt7UVFhQkNMEXNocVIiZNY2DajBdQbiOZGKpPflOAJ0prxx7l+jYzbF3LzZCVTL4cpGC0Msy6lLYa/Pj0VBUBL7uHL0vJKcQjzSuovDky/aA/Tt+7VD/x+MSAwcDBWFWCKmO2jsLOiVQVB7hDZIJsIK6MORW2LJobKIvX1Rh94SNXOkRJplGJKSEFU9oumIPtSwSfsEa4xq7KJjAWiA2GyuHplE5GkbCJiMNbl12Ksiu4V3lLolOuBf/gQLhw7BMSIJ8KAJoOpln0AIS67eoFHcoGJXJ8AR5ZuOKoS7Zk096zfShxaGAUm3phMTEJs/TGHCVU5Kgx5RSOw13yYUi+FwpyxLcfscGDvsM6CchRU7HBQscPYyOWB/06Q7y50wyh2P4yLWcgvg2SCYwszd5BWpyiP8mL5RfziUpr0ox33nt9w96TC8lD+Xcc42/fwn7cS/6O83nDch/j0fibrYaCpt2L4ZxcNM9+WxXfl5/tYeC/xm4sN96oMpH6PrP8OUvF0XZZep9JQWZ6gc8sHGRp2ynr5EM09Eyg3F9B3wrq2D7k3u9pBhYqXD2fInDEfwk/SsbDMuYllUaLM62cBfayTn1RnPZydXw+9xwOW1kSzLMlQuq7Bj3cCTIKZDCkIRCiMwRvjMTwI89qF8ECEwwhNmvHmqncxqAvFMVrfOF6TJXrPTYw1AJGVxrkPMqm9sxZCo8G1Fcdp72YU5ztz3PpMHSbiCE+vvdCRHnh7qMIUrzDxYDzGwGXkg4eKObSmXsexXAyF838i7n/viYna+VNgH3E551YaDfc9QPHbmAeUre7F0L706MrcGoQFHvdNxrTM76HdmRgeLLnXcJQpRMMxCq+nGCPEtdtROi9VdjuL0izKD32Twjf3s9n5rKIOWZYdCQTLe8BD23SUsU2j83BQv9i1cgk43pVRqcw9GNRZSFNc41VHrIrwYVLByBXOvYXhXLSLROJvD+1L9MR/LyikrwCGPK5O45D4N4KClIWVQXzcBrnSvVsY7KRiyfLS2jJKcrdrLAP10XNxigUPhtYZmTW0tOXw6XVYGdQW8nLyyubGVa8FiUIY7Wx2CU5aBs2GTwZRppJUjHMHtl4EsRDrDo/SLv1e+97Yb0mJ9+0ucF31PRwIMBF08NFKvq4RFIPzJ8LfwGn30lN0bTZQHJu5Pn8flypwpCkAhhe4hl4pH8bahjAC3Io4VRtU+g2vvsdRbO4/DAUFEdb1kpa06NbqwyFprYXxc52C9vg7KQWUdRT4il1J/PczMWZA1HS6M3m2SdnsJooNWXXkW+0oSkpxMV4ms87QaqPceWFQpzBveo1dNcBIDjDSA4zUACMzwBOPUN+L9qMkGu6GvQ/SbM/HQ68KhOdkhuBpMTBe0bkGMaSM2fcQu7obAhUqiC+ID+QwMA3bUqPRWlpuLTvSwj7391OEhHnyH2s2zsmIBASuDeG+L9qTa2inTnHr8WzmiOjFSK7QKhKYAAWzpfqzOgT/Qh7th+kofztIenHob0X1h4lleyAmo4XUyZQZLhdmiC58reUGzY+Fg0Y9FpEsdJbDlBjzzXbjXNZWCo+l5vwox3BUkd88dy5FgwO8XEzPi3QIKarU/L+MJiJ1kJvREZrHVGT1ZqtxPuvAv97yGv6Cf71mg37iH695VlSAP95SuAQ/4V9veYlK8Q/UWG28eXlQzxbxl4Ptwk5gQOiTrlL2mlVaqQCQavFjqXUWtVfKNJyBbgGbwlnvFNCwZxu47qScYL6dmSiW6iAeFzaxLnETS0eJpnt0OnPhrNoiAL4bEj/GbIefpMIm2QzlCVlZ39VOu/Ar9O6GFDwWDQ7jwzo0wzVrgA9Co1XL9V2BdqnA/EhGL1PQDiQ8mCsaQ6UZQF1iGSy+3gSGFHXMe6KNZlnioldqf2a6eR2RsU44ONOUiDtVjCAGVlFSoPJdte1AcCbmFtMxUhmgtsTEQjHqbKE5yyu14XlRG55zbbgcUqOdG+Y/L6q7tazAbU8m6lOWtZYFpc/hdO6mLv55i+yw7QxPxAwwzATViHkVJJZCmhBiAJyQt9WuCpxA9myAE2AlNpJNZBWwk4zxEhdJKoW5T2MfkE1oCzCSeFVmLmNJyziNMIZdEtrbKvuNIEG4li2lWifi1JZOXUi+gDejkwfMmAqMP51+5mX6mRD9rKI1+vxapKuKrhaJWe4y/OMW5mpW8qGFMYiraQoxWfaMYiu0DHjo8OgenHnJyFfz7myLdkgyCs2xDFV6AhIDBGvLtkl2LcYR5UxaQgsnffysN5J3DzEFsbZNr5vxhhThwf4CJpC7FmvNUginwHaDbPpWYpLZhvW3eJIriSKPCjsihQh7Cxqai7VoisJW8xTTpEBvANj3BxTJUDN3jjCGPqy69VF3PcZzWhgLcs9pzD2FzriEimRMBFQPD9GWVQY2iesFJmaKZ7RN4e5GdjhJYA0eFdKvoMqQPIiLOT/KhQ3vTlTPCo7DhVtqpdbDneIXW+g3B8gFtQwhpZvRGygu+IAlvocZ2CxVw27qvxX7pHAA6qQ0DbuJHZ4EtjE/t6sJRg4Eg47sbrKRb5LhbYQq8DgMDsIec8Kyyo3paCwthoHSJMqfhSIGWVpQtCEmu2LxB/kf8SOd6K/8bLKbWBlzGPl9X0pb0i4ZwUcdIZIl7hpZ4oPYvRjz66x+MWtbVrzQyhYeEd21UquoYcnUKXKek4uoh8ENOsCFfhz6T0LoQaz1VYxC5p5GbcQgtZyl3cp8IWKBHuKBh03MlVJJcbges17VWUMCOPMjPxdyyjrIVYq9oSSLrrHwZNH6ZCBKU0bmt2XVYt8fuQd+4KLSWrpfzNQPmIYDr7Qb4mK82ZR/V+Qd7T58ZHTl+x34kJv57VsG6QcFpXvBVgPei3ga++Kv4xVaE8myqoJFTYQ926ALDAxOYEsD4paVOXW3W99yU0zRjiRsS4dl6qMxbd+l+PlbsNL9tnKMLXBoe77t2IrW0eth3r5ERs6jQsgm/5K49NuTb/V1to6kIoj5kekfIz/paiOTLbS16jCrBowcFqgtoqFcNpO/rCc/U7/MY1/hZl62GGNYj8tmPd6J6ttJfQRSuxnCJPJHvix1KWzSsohS1XJQrykZY/i1EW16b2cirwJdhqboTyBxemRFldN5YGlUyBjN5yrKj8rjuOt3cU1GyMacHsGvXcc2rG56gT+iqj1fmIu6Az+yNewz9agQaUwchR6yx5fDLDqQUHQ1S/dpqfnxGI8HxXM1sPTul5BlLoRFvJ7rMz9zPScr+IGDfhYnmfRNv4eTDvSkb1oboS6HpU5FY9vJu2g0ITn4dXTuXkesQr7yTKRAROMbI2RttSIQj9bTGPyLKiECrtA3ahvFzn1oIV8tIn0QewyDIzQf1UVws/GY/i7Jv6R5hV/LDqrX9ZrVD5NZlYp1BTUs9bdiVgCYNAEqiepZ04tkdzn/15L6s9dptYqarLtFTVYsNVkx12QpAoS8JFuHjPE7NiPcQuMOtXKWiushWy3sXFxTHRoHtA+6sHFcn1yuhPYPfRh3qe4HzEHkUZd74bqhew+95Ap+q7Z3qqtgufMuNuQdIvdRoHGms3e5N0pb0+zCXWrI5DRtjQM0TF6IXxsIXyB+2ycROFY7Q9SybRgmFvTAoohhMSZY0blYCKgAf6uaAKtbJZ1dzL9Fo8GVYgMqjIBJm8wBHJdKVcLffijBR1dp85B/K2zTHiYFp0j0IbMEAMEETREi3tdZxY0MYdelijr2pb2J7Gtgu06RbCMGrqUgAv9pMisKClyoaCsHBUuqaFv2IIV0kCcRSoizMhntClfhZ3AkJ74hkRnSJCZOWOQ5Ivh9Uti6+tYIdpMfYAxnmdiakVZjdZkwlwJdKhiPr6OFM97oNdxtoNWxy+5J5CJcV+GIJg6SUN1Cc+Xs2YZMRFFQw+Cb8TiC81OQOMsAIvKeoSCKkNIWV7CELpdLrCtF6Ukc9w7emwD8B4P6Y1gGDFIy8zByH0f+w0gvqvrQPY1inzvoE24MEM7SwjgicbzYR3QjgvRJX3aGeNNJoTw1PpKRAOWJua6CfR70LS9QriLIueSJ/kVpcifsYs5pDE1NsIL5gNrJuVyJRQmIRbGfbyQgEtWlysw90hQfOIXunkdG7909txfthMMcHsWPiTgWCvGL69zQP0j1nW6oFSFDZDmZYoZ2vTzZ2WYHoe4w7XyQzc15dXXhCZiYfqLgX9CEYEvk8pBYkljo3wrqmJg1L8BI8nqtxOr5EK9oQ9wY2abASzL0rAgoGyb2LXUFflhqOo6+eMJNIbi7Zo5x0Tgj8q+RH6q2Obymlag6DYftKxhYUq+wWWq0R+cCtcMjpSnt+8HGSCTnuwbcfqHnA/+a0kYcHGsScjGrr7kHGFNEmf3owSqjH0fb9R7AZA+rw/Fgh0qcUsRkC35LZQk09163fgDwDlvQL5vq7Ot5K8t4kGKu+fvaZGJrIowl9nhc0HZloB49gUv61R7PpCwJ+2X/krbduGTH6bjkX5bETjcxgTFEpE+OyrhydlZbxaVq+BEMP1URvIqrRbGJr+mwp63G8ppTIWhUbtdZEHUi7d4nJI2IW6Hs6mnv6sEYK5RdzY9fM+z4REghBpAFWPX4mFO5wwNlkErWgFWLoYQOZ8DNTwd8MVK7bUqqeE0pBcR6MKum4PjQTTGsSaAtj9fJhCdwlQmOu07qYPTOS23Ak5HHbqJ3q1m+m3r5bqrhB2b5brLl066u2D7eBBM+KwY8uJ1iDFQdzAAjWlRHPKioiCEPQiYXTTB8p+J7Mq5hLVLLXF8cMFuUn2qr6Rwl4kSoC7qilDIJv5Wr37kv7jWN9EU2KU2tOc+5jYgMmJdYSe6XHOoQqTBDGvmMfW2i6h79lDxvteO45mJVlaYJyFRlhSaDHZ5MLq60XDuhNJyhMByKBaQAkj0xcFq0t3L1OxQLmFsLCEvrCiqP/gdqppO8yujv7UExtghe5FwKurtGFyhCpGPwHwrW6Z/oLjoR8iryguvhbOIIOVsIuctKyBUvrXtzeJ5daTTO0xXwftqRFyrebkrWRxIqWEp3qf3MJdNjXNoA6GK3G587B+z+TL0bs36gXjfWAWkdL4d9lxp8nFwbOIHc3IRy0KQYBBdlaBkmQg64T4etr0P2KbfyT+I2ZIe4To5L4+fXDu8OuJjeqHL1bi55areYS3KxY66yBXGGdVpQ0FvtHH9/3YTVmlTDhZoIxevsth8V83+KU8tibRakakqOx+Xq8fi0kV0uYMYjkxavLoNBzyZOKQ+AxFwXKFqoTiTfxmcT4ai51GycaUlvA1F1FBp77iYQh5Uzq7Ozl9As/J2YIgv1wieOMeZWGb/t9I48Q3z7o1iFlS/khxT83cUE2r5i5tj+CAsS9DF9NxLX/rmbwUGPVerVyP8p3szYUfnHAN8qCwlXaMdFtZxKiW5jn3jh9GlRwRgbSnTYdKtyDhSUt1R0GbNHoOseSG1PKfOGh3kISt9LX1jm7KqeZc+yQPjQXaaYurHsLvPfja3EFOhaa3sm4F3oPfiIVo2s85LULCSMLZIRoWh6DdryaHb2NvVzLVRVY/GtvNKHn9qmLrPdLliOrGl7jQmfFrZALocR+JjKY2sQHGLSKAp7jK4bmYtmyzigjwZ4pxf6/byeUa4ozY7AYFIOEUlJzZv7D7vH1sAq+8dXWYYqB6wKjkMGbHdY7YJv2VJjFerWao6OQ5yrYDwsyyg1E5cXp+jGknVij+aPuS1EVKlMHAA7PPSPbfNht9TmkgfbCFjlMUbbc6dFW15DdUkx02tFToYYOF7ZvXt1JIDickwwKW67ZGL14ofCZDHV30a2gztx80fypZe5/KUHJ62LJNsDrhn/unKM60STh+HFdJT0giwKh1Bj6jvXsHZYjz1N7DO/sA3LJg50VDrikcszsh7FyMOY+5kl1Dng4dpT+5Box0F546PikcWlj5dP8DGyf2+F/k/z6fk7eGBd90PU1o6QuqjkKECwryHWF5E2CRUoya6dYEpnEVFbKZ3ml8boZWglHlB0ihK54PVPjAzKHTtoEbL3oZqHM9H4pnCKdITxC6O6laLkRiTMZ6adg8jXNqMFUiuwa6ocFYEquHsRHY+O0MJ4xnGLXkRWfu+lFggggu1y5QxStkzK0UgHJbcHbIVIl3W2lMxQiDt+IapbuYiKeVhKx1FfsKgtezfhO+mxBbeH2/yTEct+GbHoPC+y20J7xTGsHVvd7FVp7K0f+2FDYj41Z22MUQVNeSnGjVL+ZWH9DpotbElOhnzRnWIBKjkZ+4fGdfL7VJjFRnqkeGL0AzB4jBGsPEfm6EfaEM+AsHEXRWMvW4cllGMmUKmtHzN6NaMpC6yMIzpslJA0RzotNlnt0qS0YDnyr+V1tIafnYedGNGLlvTWjYpX6Mabru/3F4a7QRb22sqxThmTqIgAnZEwGRl5dfnrQFieHMhyx9Uf+aPJRF+oor0Ez4FjBcFSdRz3aqTX0CWBPtAvpXQ58kXH5l5IT7zhpL6OVKS8VAnJqNRBxS/WZA4h3Wc6LQMCKktTM5nAZwlOXOMxXQ/syaRiFqnRaYlhW3rUCnWb0TBAzXZaSHlELiJpQekY+anJLWLcWrXSykwxkh+lunACHysaEdvHeAqx0LkcYoXFiijSNGOkjTivCzOeOk8o1nSnY8vlCmxJiNDuzJHFsorjHpSRZFMTuMOuxF5Uu4Qsi8mzfiyiVpy6knGk7BIy2YXkQbU+QZeYN/hSihYkHhQEi9AMUO/Buk3VWhpN76gXx2RMMZ5q21LDoVL1BAUNyE/ZZWS+Gw1pWlCCP/fCQzTexp86yQ4+COJIPxVqpDpathIN4eLRL76aYtpYSiIxisCinT57ZYVvzkWZFRCaSmzOkYq4aGnVYZGgRTnlJpRz47fYVNKXF9qyYytgtlkBaT+Lz1qcxwc7DLdZ6pT52akL2fAx3wEW5iPl/oKoWMvSPCWAnanPhOPxDBrlC39KpR/keq2BtHuWwnto6XMV34R9dJoepfJTmTflVZMW64kpuj1U3zSbbZnc8IYpW1Z4pMXsRZIpcQiV/iHRCdMAj+VCQ0UpPwHqZGgpOCcW7IT8Cd7lolDGJ2DZp3nGTrYdaPzMNociQhdArdiogoSkaOuQFODC0ZW1kkgkilt2+XW3ClYlrh8TlRtOJieTNXWQsKRkOmIzUYVgC2XloXUgwkKUfTvIOs2IH7GqLGp5VRa13M6i5poUW6FxbpfHPRR/KdJiX0TVdxMDNfFQnQUXA/RJE9eWkG51SCcbmgmMKdt504SkZjVleljgI1bU/Y7SikoNye1EZxhK6omhcTGZ7An8ewWEWn/Njcf+GleAXhoaSQWguNlC3gBqtVDHzWH3EogXcrcjV6pMnhY+XhIfFz99WvHpe4VPz1Z/+l7Fp3GgPh1SNm0xSaWytddOqwPDQuQMgyDkGiYBrE+zEJg1w8KzdtltXMhmIebrDSpctgsv51i46mb2hViB6i01EEUJQVXlUw5thRKuUKoWyCbuodGj5Xqhcmbpn5RwNi34GQzBSxo7nc+UGZkNh9VftVrmMz6KOODtMEx2pOLfeTPNCe/hpo7qzttfRU6CiNSUga0XP6O1WVY5IjJt09AxJd7GpsTKuUnHl1i61CNLsvZKAXgFjr2kGhT5IvcHYgXIxQe+sQsmfBceDQxaYGyK5AyszhTvYhk4CaZCXU1JJkbaxnCGQ42QUfqS14X4mJs9WS1IAU4PDB8Y31AyjRLlxv/Rf2uIoQ5Eqe0Bha/mm+odzDnZiUPOhRQce4jPGVGWeGAUeCn3XjaLYxXYF3LWHG9wZkeNAzfOGnwEUJtH/SjMbgOSj54obqtsIuQrnkyMdT0dZd3wSrATZjqp9eUgDwrc1JWUkwoysB6x04CclQYbfCWcgfFir0k2TjPkkwXonG7wMM8znIQlqWXVZ8j4UfN0qmEpm+k03WoiVauiRaYhFdGkpypaaa54hRFZzpLbhQvXpXNBtkOJjYbS/GZ2VpdsLG2aqxFe6jFTzCOFx71LeOl66GWc7Ndqc5mrEUHo2uc8Lx5ifjf8oXK9m9EuV49ySmdSuHVpCy3SCP3AhAmxTlRSMjpsOMoNLGSW77ll+Z6XMkuhpqVw4YEm/DpFJKqF+DeFiFXvyJ1+J8zgjFxi3lFGhcSux4s2qMU5NIXzX2jbDCSS52yLkZksMDdHFGcjN+TMrPFb3WPPwBVMGiAFTPPe1YvifyiuaxxKIKuzkYorehCDEzQ0jFDvRAJspNEXJmgSilIBx2/l0FEk2A/lcSjoROzSxXesfe0zC54jRihVCB7tUSfGkeI4YseIDTho0ihoXZnS2Sp06+VFdIwOqzCNCBMowEtxh2YObThB/YW5wSTYz+xc0nrumbRCiUU+BnbHT4FUYlhmOLSnY/HbgRkwEXAoD0XIFnxG0lrLSlfzWeUIZcZXuxCmrJABs1SBnc57JshGWWx4TXpRFltSmAS02W8KG4yOsOfyDmwE6/KS9CCVXbvUsRbHgIOXLxgKGRirXUKI2PP1rsrgmYUYn7VkOtNh5Z6xInCQSRmmMXBedBRDriW5TeI2UaGtSB57MoMKJ8HQyN7qCvx2Wi7jPQkUVW5OxcCo0EYuN84CblPnQREhZ+Ja/Y9kvGBrAKqQmToLg9TqAZh4vRXz1SbMKB7aWfl2Eh50BkYq4xywwcLh2niabwpKzZY3GJ5seUfJkFiArSHQzN4oDjUDYC0vTkIt8tWALG6OtuO0uxf2bknuMgd6F8LBdgeS0wJQbJvAqGiS/HauSWYuYDaHso1kc0F9Q8bKbag3HMRRF1OVNOguWxgLXQgobKSZ5VOmsxFKmpAn8iR7ZPZ8tvDcbDLQHw3/xLbImImVrKkWZCx5v3aKiNw8wc78IIV9mVfrf6rGzZPCHuw3IzoWM46xR7imKSs7P0Z+1s6K0Nn3AReiLx0h1npfhlFL/be60gKjISBrpon/1Wpu2GNu7hW5t/zUDQn6tJW9ezurH5MMyYO93ME71pRSq1FwekzgQxS+7diJkIRLxvRJBlMnOXLagT3JkU/MawMnZvjNk090JCc6+mETtU9v7o6EztFxRyw325BvrnLbqBgEuSJrH2S2NnHV2sSwuIW1GUkAkGuDEe5oVKlLjmDyRuUt4R0kOAi1KvTh5FpgIYUKSzdh6GalMRRxU18f5ERRuQFmBeByj9OWBK5+D0ebjJvSVlJ4RKiAAVbYAxmuzPZ2tzdEsPNkN9gU8acKSTrJsnCfTHvcJi4Q7BtRVMAaE/f6sIQgFXu5pDClaHxpudU8c6a15lQls1SdkH2oqok3DaIn/S30eCd4fY8U9A7QpTSHtCNMihmrHhNXZrOWXAL0cDXwy847IbBqpwPLOFAzJKEVj4J0O3aMBxBP3HeHJctCJUXYCURgsKeHdWUIRplRlHaYpUzJZ5gzBIyhzTMKtp3EyjDYVtnqHo3C7FCkuk6zCwD9otMN7MSvzV1fv3VzQagto/5hHYSw3Jl7Y3ODOpddb76B48KQA1bEARXoYSPfVFAUoqUl9LxP7lz4Q8HUpZjCKoGIVuRGzqKglWLUOBcWISN4m0xLpRLa2bl0EFpcNnFFn+uwd7joMzOJyUuSYyIS2JfHgf8wdfcCfych/uPewD8aDZGFjyPM25vA3t5GIoXmSldQXTL0Nm5k7oXEvRS7TwL3IfyXbsIGZ/4R7HmPLNQvHr6dDkGQg/OfdEOvm7jbI1TkIJr0GnBmsyHKrbXm2sLSQrPmCl4uzG4Dox/shDdhP7yaoJO9dL8GBw2GxZp4N1swT7o5KJU/KxuE1xXFuqqIiclrySiZqDTJol74dprurRs7x1LxZbKqvR3ku1Mq3AkR0MoVuHkPL5rWIL0sNjYMBestNGaZLlRKDrtc8nkiDLQo6xaDit4J+970iKO413yTLx7Szlucv5GIkWczdj4k5/Ekpe40yIHdmPJqPP5wUBrE8GqKw87C4a490epCIKx6nWQNa5mAlZUTF3MTJkkh8OfdCCq9b4PxPJpSzPebS0tr/bXGWmO+1WgtN5Zbq7UJo9NbW3euXLj03tblK++/d+vWjfWtt27cunjhxtbbt279dGtrxq8BXIcwr7AnqXhv6B//DWGS3hDpaDRENrI3O9tDTeIA5bEhDV2koYp9rJbg3UL9+gAdUqBA+pDAdk0mH6ESZv3KpTtX3tu6dvO9K3duXoDeLt/aunnrva2761e2bt3Z+ujW3a0Prt24sXXxytbVa3euXPbvDVz4UOR6vo2pO+JKQtE6VmnWqlSataTSjBIqUgyzgvN5o2Gcz5WWThhsTsygSOaxhyQbDEsNnj2ro1sCxst8YAxj/3pXm2/PGNWEEZuAZnTzd0QMOFJwwseokC1pY3maSTSFKL7Hb8qaWv4VqmrKNSjoInG3TZu7TfCR1EiCd81/AO+KutzbFBcZlxLP2uVb75AitsiCTHN35jlGdQAoLecWFZJSShdTlfuibpYqrOA7JjAuKpNhFLfo2gzvTFCMW3gIdKtec2uOa2IVrKFzjeHzETORfeZU3OSGYvbxaLi7fph0/QoER7G0qJrkY6u8KGZAtjwefrtats9ReZhYTZZgWDVbBcXLjRVHRd7VsW8T1VRPqPqH47FIdYWAHiGgpwjoPFxuPTkOyGMC8uRYIEcxpgzkyWuAHMMDTQFyklPlMjVV3ueOVD+QNEfaxipox6CQlMwbYwefyxT/FgL/lvjZRrgJSwFCF2B9idnRygqWYGtI64Vn83V3JUon+rp6G9D2pvfaeiLkDNlLM8uVYKjOZEkH9mOhramgTSq1tHHLhbzqwM+QnqQEdM7xAnSnbgtfagRyGDAI9rJaBBdVjbILxWhMvIfXtB8xRdY2cSkyPcYQWVz+VizburAsAfyTmg7spZShuXG6SfViGuyHip6iY0sBmRm3BGsbRFeIp8UeSCbWfy1fYXRgeU84x8+cjM3wOZuhfR6P/2YBFrS7d/nSlZlSguITfljPe47hNkpq4QlOwv0wxWtSZFz8jwSpCHd9U+gmPTfr+eFuO+n5WY8ReCjmuBJjAA3zU/mu31p2455fC/YHu4OtYX+rG2T51gFIHUNe+jga7lIpAIEplSqIrTQDgKHXEXw07GbBIOxtDXfTwVY3jYc1N9n1N2rf/x1Qm+9f4D9f4z/f4D/f4j+/w39+j//8Af/5e/znj7VN9w4QnvPrJHIqU7AOiD41B472IA66YX3xfm9xB/De+WQXhEskyj4CjF97+dWrL159+vLZq89qjn/+SEw38W+O9oHfq2NOwIaEsQenj+5gLvT0RtoNANmI7mphMn93HTWOp04f5ZMHTKMaa236yO2rhfTld/W+Xx/5yvZDwDeaEfQcOf6RV3qZR3kcqvd9r/ak5rRBtiGNMGJk/H2A8UpMwIoDwsr15Ny5FWc+mUNL1gAksl54Ia8fODA3MaxMZEgItvGEAr5eWlidy/5tc3mxiVfLzRY8LK81gLKtwa+zrQZmrsIPMrzzrcdvtpzFlrqSRbyb7Hj0fh9Yh+WFs65VudlwoF0gI+FBFD7GrLAAwT0vdQGvZEMvYLcuP83qqr3QHUZPQxDLa0MQZR29U9R0P05TQZr8cD457y8AsPobm3pVYA7t6Jy/0o5Qy4AlqV8L9wf5YQ3Lk06KpzGOax5eridzzdnZjAhobTeI+zV0xCMaMlx4OHxSr0U196gbB8MhScQPhv15HDoAQDp54NaCLArmd8nwpebNNCYYzVtdf8oGQP5LKtsYnlI/5gmcXNpzj2APDvepl89efn3q+989MNfaiL3FWvSH/tHLP776FAH6+194tf/nf/3v/7bmfvf85a9ffkVPcKRe/u7VFy+/Em//6lnNBdB/jr//u/8B3v0Wj8F3z8Xb//CfoAR+v/w1/P8VvPkES//6/4TSr6Hky5fPvv+FqPnXn0A7X7x88fLrl99iyT/9zf8F3f4KP3r5JXX8N9g4VHn+8sWrL7Dkb/5HKPkGBvqPUAKn7+ULrI9v/ue/hjfwHTQGpVjyH59ha/j1d7/C52d/T99+AkP6VHzz1/++xo7dYFi8usch/LxGngVD4E8UC00P+lxyrrc/FEm85OE4hd7v6LehUIwD4l83HvVCclY3zWWqucQ3VfH6Ja8LZNQbAS4ijZR/PpuL+WmEA9FQ5Gyj9k//y5eA3GAX/vB///1/oF9/9TH8+aff/Lf08B//M/3567+qbW4k/3Z1k93R9oRkVonIkCk9txyuWCKGhQEwR0o9WVxWI3nw/benXv385Tcv/wDQnWDGTeCZHpju3uOWvsdj4FlAwLVZoAltXnqOSuPcKjxPhTt2YY0KH41SLGaREWi78UjnPo1Gy5hk1CA/flDf+NmDzTnnATTyxrkuLPkpOnyATfrz+715LKmdP908t4i/zr8h3AwM9Xjz/pvQwpvQAv7EkZwDTj5Ndugb+bNW+GprC77Zgm+2tk74Rf1nY+zFEb3dT6i/emfm/psOtgBfhvvnT7fOLcKfym+3Nh3qlD7dgi+3Xv/h/Q344v4m9rV5v17fzfPBsOPdX7y/uPEz5/4Qyx1atuDULtq91k63aqfEFaVf29qOg2QPr+NiTC6aAqkPQepPoSKwJGFWs9c5jqAyrUBQWmScwP1hfdOxh3B/eA6GgAOAz/58Q2ipIRhQCnoGlKpgmfQnuUIqNU1dxI3sMl24HqnjJK/L2fxmf9Jcao9nf/Kk0aM/vfbiTuQ++DcP+CFJtocDKq+dsg4PwT0uQu0NXvyTpbPQVjBIh/iy9ob1TZyLls5ZpTuy9LxViueSimcRPGQQzLRLCiRbnYasqphk7KsakoG8IixS6kbDDgwK4EqZGJ0Cv0vHORM8UeV/klkVtKEQA9DsfgKzs1fqfiaLmNX+rtk/2ssF2MR9FjSgY2/Izx48eFDveLv5fux07g/fXIxcm2ccvgk1Ti9SqWjKhthzeDQXO9DGYLydwX/3F8ejeJzG4zgaiyM+3h6H++NoPBrvbizNr2yOe9HBGMm/c3/b2fjZ+c03z9Oylw7lsJ4m9x/PjSmVLhzDN334v17b+Flt883a+I2Nn72x+eYbYzwe5+l4yDbkOB2AV3Yf31OiAgft8djMSy7R4jnoBGaEXQ1oqHo6uxvN+dVNnCabGExiMVoA2RDD2Y/HBHLTWrDqsjvbnrVpVedOD5KfP60fG035aDyGN7kmzwAcU6oxKkgZ5YheI2QBNwk8JA4tlhx2OzYXVmpBIz/ZiOm+igBqUUwxctT7QDOic3Ps+9nZGV4f23DajtRVACGjAmA88aO5OVfGzX1wbpAVSBcU1M4TSTt/+igQ6jo6F5KWnVuEGucfOFYS4kWAmzc35t/8p3/3y837wzk9aNhDfHO/N7ex4Fhv+HTckT+tWnGZgIuu7ovmN607MXm9HA/OxRFMDkg9vmAHGKFMtjyWjTjYirCgP7cInz0QayjXb9R5cC6NiwRhmNfM2sGn8GUan3/gPTg3OkndUVxe3p8cNd2lSfUC1qOFfcJ+UG1uERZho/aT2qaz0diUiwbrC3ON2ERNczS1tgYHOGH2CHdPyb+nj7QIFrhLzgSHPYLRwhdqUUoQcf7+sDMdgK1tLda3Abhyq6g2jd/akwfnyPwJKVwBtKmIL/e57WzxPK25+aS49jORwoFH1hzFZNLKydBYxVdV55LKpgKxelsNxfS2CA3Wd+VFTK1FLK7WoHD+cYHS0gINYF10JDMDrUY7sVs/Igeo0M1SEDHzic6eVRsNgWtybJEVwMaSWLH37dE22ltRdWZoPXE0OiXs3j5xS9tpfko/weRqbi9IdsIsHQ3jw/Uwv6ZYCe9oawvptpeMx8T1TyZsbjdSTu9q3//d9y++//r7b77/9vvfff/77//w/d9//8eam/i1V//p1f/26pev/vdX/8er//zqb189e/Xlq1/VCOFnlQwgKX8eh9mlYCiMjBkTGAGJiIw6PDK8YOpnG9Gmi7ksyX3sVl+GYQ/OY8qyeE71FThFaB35SeGbUeGbkQ386Ilde/Xva1Sl9v0vahVv/xv59rtfsbeLG8H808b82fujxmqjMY9/rl4F5l+R7NTpwDephx+eqinAijnDMocc6CnNUpjt6PXMbWFuUvUoGIHdenD6SKq3kClBVRoG4s3DnTQ7NCUgb3ezaIBtmsJBFnXDLTQMCfI87NGLByxOlBS/cVz9CO116xdTgHdgwNBvJTusx/75xMjzsaPVKT1f3gT45wUmDZ5gQCyFVBNKZjjcpVqoO2T2O2QDMwgyABMdXpC5LU0m7v5Qf0fXpMz6p574cM5j1DKu52kW7FDQvWt5uI8aLuBo9G1NLpvOrKYPhtaQrIaGqiG3YKaD0Qb1OLldaLRbd44Mzy98U+7euVGX+Tqxeay4gIKZQ46ZwzDIuru3gyzYH8Ixq+ONaL6LhhaS5bROEV7gwvzqNaEdjkDEwOROpog9C42yLqVbZflqLzwsfrhll4mPZRl8GvnWi0GAXeMJadb0N4PgECUaUT4cdbvhcGje4vXmaChe0udu6i9S1/NZ2A0xSOJYjEQ/5rsgqypsT2PDkIc1+yNg5AMM7Sy5BPvl/cU6UBqH+AZgGpqbOBkyaByPU8zvOBuPx4E8ZkdqUb0MCl29LF7s4oBRPcksFyoDfg96PwQA2oKTnyFP6w2+tK61ey7bDtdsvfwJf/kL8VvthavWfRPtwa4ECLMA6zbc0bImlM3VLpdR+8gwDnMZOFxbZiB1rvhdnkqM63Rqndrc1LdIJ/Bj6H+XwL0t12k3wmxthwpjki1T/WiCNrOJw3dBcSq7ylVANGBJ4bOzstULt29vXb11872tn175qFNR5slrlgxQJK7dAdDD94LddD9wh4dDQAXzo8idx0x14bwocIdBMpwHkh71a+710D8Sxd4RRbX2DnK3OxzCLN0+zALFTPcgeBoh+k1Undr7qsStzakPSKnjLS52ewnwAbAN0QHmzskXd3YXs2CYR3th1guyRd3aXxwsLS00GkuLurV5nMM89rsATRZHYPf+Y3umPv5iqbHQXGgArzzMF6d1Ogx2w1h3uo5PP6JTakV2urD8+j73o33WJzz9qD7hO9En9Liw8po+d4NtOBKmV/H8Y/oVX4qeV6Dn5vE9AwWFr9NUd31bFvyIvlVbovMWdP6apQYMHfaCnu77inhmXavKf6GbOJK/9qP40HtDfvFGe5h1vVEW1984brDwURJsh71odW1RfvkXa7A32ORwEdFF1B0uPg63RYGsMn8n3BnFQbbwOO33W284pwQnVH9DPrdpRI/DaGc395YbDfFMSiUvwaqxKIGlALR06A0fB4PJv+qE3oEvRvsnms/Kfw3zWQ/3o4tp3DvRjFb/a5jRiWdz5oSzgdMWZUFyGO4FhoZcu3Ph5kdY8gNOnP7mhAuwD1zxw2A7WtwO0HcGxr09jPLwL/bhIQR8RSsgl0OPcJGmap7hoxine6IlWfoTN/hfa4bZvyCa+dea4/Y/A1zbMP1D4fmHrMJuuA+nM4kWqUtgJpDCUQM0p8KUCjPiU3jtpCbt6yCMB0ngw1/NrgGvuEBdbwd7u756wFJcY2Qqfflbv8CN296mZogNwrLuCKj0vtUyc8XcrbrI0JcziZ9JeVoFCJjBROw6q1slI12vx36BcV53lOlmvJGhlvp6iH8dYYE+O5uwe4VcZUeZ3oe5FCwy6OtX7rx/5Q7dyzNLgqxdbA5TSV+FnZB29LaTmrqGqKwqbk0SVOfK1qOJur2rVDGku/ILnfr5VDwxegYpI9b05vBrJmt3aK/axy6MDs9tD/0C5s22I3WXNogpWyo+rpNjjpuzW0Z9wXNymWq9XLKRb3YqyjwAkHwT4WRBClEZZeeGQz8eHwgQ0Xel6oe8LV2g84aaGfRICbP8sF6bR4lsXkhsmePqT7fT3iFArPUsv8fKVwlt+Jlj364myNk6+tK2NuzjQt2Ikr2t2lwuHB9UiwAGclwXD6+h46iBr2mXvXShjr6wUc+PKXUK9oGDGu6GIcwgItWAT8NwzeB3w6A3Hk9bFgc3NExExAoAX5FrfAFxZGkqV6HwzzIVGjWfCx9D4aP3widk1asG5fwJU9PaOzHA3Z5/VOtFO9EeELT5DGDTq/0k7C+dXQ5R+5EgcOxkIdqX1X7SaPSaZxpQvh0Mofr+fBcOdIxv+v3V7aUVeANHPN1W7fSWWv0WiPbUAU14PkuHIXVxthmuLuEHYXc3SeOoH85vxyN61wzOLIVr8C5LD4NYF7dWVpfCbSiOR09G2eH8YJQNYnpzprsUhELtsx1m8ztAXKn7s2fONFZRnYP2uUEyDzN/NEojMYJG7+zyGvayH/USpEzzCFzwYnVpdbXfBHqJCe9zHM9qsLQc1NzhKIGjg7M9e2apCQ1j7L0sYUNTTbFWgO+NgLphh/3mmRY0sx08DYIM1yBYWWt0oSAd5dGjUYhD3m6dOXMGiHkXzVNffvXq85cvyGLUrb189vJ3rz6GAvH46mO0WnuJFqsvf4uVXn6Db07Bz9+/+uL7X2D5L1/+HTyS/dzLX4u337588ernU9/+AxaIt2hE9zW0+ZXqD23nvn71GVrWySK03yPjOHr4lbCvk2OF8i/RTg+//PmrT+kPfA4DE619juaApU9heM9efY62tr+Azp9jS9/ATzmKU6++OPXyy1efvPriu8/YyL57Dp2xgX6Cy8LHjRZ/X9OscDmh4OvvVNfQ3Bcvv8VifMDp/dos9wsY4Zc4RTlGeDTtvvzm1adQXQ7hM9wXavs3OHcxUtiXr2D0UE6df/erV5+LJaNl+FxOB37T9v0GV4Q6lqv6aWlrv6VV+kRuEH/z61efwURw6zbdfTjSBnK8IhjRbMWwxDSVgaNeBjFc2K/nBF4wz0/EauDDP758BmvxHHbqK3z8ghr+VG3rP2I13DFs5zkN9wuEGQJYnChCFZpY0i4L8Hv5h3/6d7/A9YVGPhV78rmAbbOJP4dN/Oy7X1EX0My30MwzuX0wvM8V0H1GnX9LjcPDN9giNK6nVgCVl78jQP0NVvklDeU5rJ8BXlg72vdfi/NmpgZt/z08qm8IsGBBvnsuV4m25QuApS9p6f4Bi/6A7cJENHh9S/uiFxbX6nOzsLAf3/0KlxUHp0ph/F/QXppq9Cjmh0M3OAI33kYZMJdndMI+Ewecdu37X/AFwgPw6mO+HggxODI1FF3zYwIb3H99ZL6mPbSrfUrtvEDgF+3/Af7/2hww+xwjeH3/C3M8EQvhtn4jwRVPHBoDKxSIgPzcfvjCHgCeUwK8TzXwv/y9AJ7ntDJ6R74SB1EeaJw8QxPwhsYi2sRePnn5lQ03CiPjwn8rD/XXEgORRbLYMljgz+Vw4ItvaCi0M19jNYXC5HTFyZRA9Zzaf/byS4G9qV9xjmAWiBYEIMktxdWQ6/wNQdqXmnKog8gB+FPo5xv8VqwZnjKNnwnlv/yWDiJ+W9hrPbPfIvgorIrb9gzKN6cRKFgniXXUsBHS6FuxcWiabdb8K8IWX516+RsaGvb+uQZqXCRBapA2qA1VX/xaYlNA05+JNVQA9xkZgEs4/4pIoX4UsPexWPkXsAhfi1EBJAgk8LFAWRxGAJ4B0sVho138SqA5WCAOP7/F3Xr1SeGc4Y/fKqL5KREI2cbm8cSccDz2L7ecQAbQjjhyCH4SBeNJgC8V0MgT8qVYBjU7DWB/pzGLIAK41JKGQGd4cF9o1CdAXID3J3gSxITk6S5M9I+IGemQSmjCxaKllw2+UCTkGa3nF4Ys/vY7CaK/g8X5WExn2vIobkZgwhfCWUHM/beSRSL8yUAAgE/PUq0WnnYcvTgYnxAj9I2iRC8E26LRJ1JNtezP1NC/YRjbxqbPv/ufCPIIir+UdAFpuYLuz4gaGZ7r1T/So2oLRvYHxPsWt0bzBaL4hURAauk1UiVGRSNIAGxA5l8Qm6NoqCA/hix9a2Dlc0GfFTPxhdh9uUMv/06sL5UL0H8maBSyXaK9b2B/nuNKWodHcJanCN9J3udbCwcjlRdeIobJxB16rpbqOTlYSUj/xDBKOPd/gMUWICxa+hiXHGa86Rb5Wly8bzTPRh9/KZbml9iAYEaevfxbm7GgNdFcBm0arACB8FevPhO7q7AENYgwIQCG8Z+0WPoNfiUO1BcSLUmC+ht0vIHxMuzNiB/iH1mXKOoXtIKfC+j9gpZU8V/U+wtxIrGpb+xzKlEY5zbYiRAHftM1UgAu3QsFml+aJYXlFVtB1T5nWwjD++y75wZ3kPOPBBya4jeK10A27zN17j99+cdXn+NYCV0JCCfYLzwipjtFPM8LxBWS56SG2XIKwvSJEjZghAQaBMUfKwT+e0nR9F49I7b4W8lJKQLzK2KsFTtmBBvJSRp4f4G0n4sb3xAy0Y+/Jhr9sVrLTyXJleCN3BRt3q8NU0OARfBNlF59KoiSotovBMtGWw5tfWkzsZ+KNjXTDRMUC02UUnDB1NevZVsSY0taLzH5F4QiPpOYGaU/pPRCCITG/yjp5udKJPqVQqwff/eZOe0/p7EI4mdER2qJyZG4y7CwjKX/JaJ8uQzQwN8qTPu1kIGkcGoQxadC8JIDKp+uUzAO9HPTRPPntLyfKHD6pdqF39PWM3ZQi7g4aC7vCoFKbskrEh31eBCHkcT7wlBbBNDn1rmkqeHp+iXCwXfPmdQkduZrrSPATVX88s+JCMKReE6g9jWVczAVxMkCVi2SG0mRYQlJZCWaovrqMCMeou3U7NwLJF6nkERodoMIpiL6JK9Juew3csFRIJDYyiAa3Sl8/DtEOd//QnCj+O1/IaT1X06JAwH/EzwLbP5CixOS8j0TKAnI9KYrdA4wea18IHz3pZTK8SCZPaEj9urnWgQzzPaXJOxIAfcrdQ6fIT1FFkUQYIBrLusyMfKFZvhep/lAdPu1FBqZ1MLg4FmJQSFfR0nPvpHYQHMFksX/DM+PJTVLiiJPqsQ1X6sFElhLTvhrdaCB9LDDjNuER1pSK4O7XggWT2HA/7e8d+9u4zryRf+/n6LZUXi6rQZESrIjg4Z4LcmUHJGiJcp6XIYHbAKNhwigIaBJECKx1tjLkr3WJDcnj7mZcWZNZnJnxooim/ErHmfOyh+5X0Ky//MXmHyEU1X70Xv3Cw1KSWbOXYlFoLF7P2vXrqpd9auHNNJPuVmBiFNR2ZEwmSiuCPCwPgaty3vixJWyxCNaCn5wfIl7ViVdjAMVYs8vn76NEgJyvA+ktQeXkUuJSFyqDvdLmshDdYpI4hBmjQ/EUcl6eZ/pNkKbIvYBbXwmZXWcPuzbI9gi76pjILUDmIli1MJ1JxEupj58TNxbaIvyjGYsD9VqzjU+CRm1HBETgj6QUoFaMx3KQtxnAuhHQugjsZqIW9AgrgoZokLTG5fAkVdL64gQxKXtCFmvVJkOSSF4HCocXEgTm+pjdvT+EvsVzvSvaKYfhybSj2l/hOYJLt2+S/xZtQViD4XIwoUhufCcWwEVfxae8p+pq8TkGDaiD5HlaJQWSh3icJdEj+yNMb6QsXyKsqkUCcRh9gG3db2Hj+XMMauPNGKyUSgS5UNmhZBTwlRewVAFh2b7g87g9xQNCedH7NNDIUEwAyY7y9mfR1SLcgQfskXBnkmLKjEr3bz6UHSS6SMoBr4rTAyHgoX9kimKD4Ut5x2yoz0Ij+sPSC+QVi2m/gnZVDXiChoMbbpi8vWlImo+jNqxUGWnqPV3uEEEZ59tFknAD8QcUQNcQpVKPwWW/1qehU++FAcWGunekWL1J/xADk1jpJE8fUCk9pjNwtvssHkolXncalDfe4q9VOywh2xH8D4IzhTagw/Fe3IFUIMltitEU5pPxUAqTd+sa6EdnAkLIds5JIXmd4odifQbIXoj/9Htj9DKx4qqyrnofW6VIiPcu8IYQJ3GvYinZQ4LPNnlsB02vQrL1C5U1GsExQL4KddMH0gxP9xQh8rFB77FbwyYrTaUzx5wAyafkI2cVwJMIvmASOkx5+zi5JOLqJqO6XCRLITpSR8x+/EDdjYJRVS8QGMQGwoPWDJE4v4RdxG0e9SLiQ8UcsOXab5CGZx1LRR4lQsIIjPdRMt0CXF8PpSUSP0lGZJtWGaE4haUuGUxvB8hRshvRT4WAtyn7EAQ++4zbgZVmPLj0DD7ASMNSbbyZH/yCa3SY5qmd5TbMdI2mfhyX1r6N9JvaqiPTH4SghGzLMiz61BINF/gsSCUBXbohHuTKvlMTB3qupKJcz6lFv4g/Ios/lNm4EScDSG2bighGTUMyaCb4nXPCTbKrxV3eE5iS0JIGPTwtXodUQotFdpG+dIvp12Wm8OeW+u0ultun8e9Y4a9sHrpQ4Lgjn6nt4N4aHiXzDFZ20XuM4ShEV2/65kHB+3ibmvQ2mq1MXUMPOYQKXq99G7PZ4lGEOi73toj3wz96SBoVbdHkXcx26Jfrw+84BJ5NR0ciBgFCkG/2aoFzVfK3zlzcvH0S6VTJ9GfR0Q8WLYj3EhqNQJoXW4NAgQSsMy+h8gzptN19nvuYNDa9TBswnZwIvlLDGs77T177Kxv2E5sRabzCBnUC3JJHAyYGvf2NrFqb0PLSlHPog5nvev0tWcz8+kkw0KfZ+YXhN8EewrziqB1Tt+7u+MNglfxLh2bXuq7HY+/Gb7lC8ekntvwbq/S+qT7RhQH1b7fbl/3ewcHEmYII6/mnQmvsDUvqCvOHtkLgVWvWf4J94X5uTlnzplHzDGnb/lnT5+xxwoZpJIAa8F0WhESaFmTyUC+y8lgv9f3G0AbA4Q0ot8w044CetRrWl75TOhIRctyzatbFxDnu+sPLfu498Kpl+bmXpj3TiUsqcTrK4Rv2AspDGGnjDFhiLW267bph74lJ33OSazLdrBhPmv4SrXtuX1ZyQ4b6YLgEQpEU/cEdbklfPeUn9oncEi2DXSCkOb9wDrpmHMm5lBJKPxtLHzipaTyrijf/jb8Hv1ZwFQ1Sy2ng6BTGuBUE6NifXcQ4OoAC+u0YJ0wNFYi8rH4zsWMWFZ6vzDsuz2Tg0G1gWDMktnzgfV5aqQsTHIPg5dSatsUtSH4DqF3s/BGx++ebwMDLOHcBxZiQ9phrVCSZzKEx0DfJT2eqwND7FE2pDYMjQMWV0elroPgbaU+VH4TP7Thw2oP6mvBh1drCNAFfwfbJdcZNP3hWs+rttx2aUdicdUpP2JlEPjVbeDRM/PObpkw0JyRhCFi4ZoIRuR0yn7NGiFCeUV6chah5n5QwUBPBAKMBHyqsEEInXH8rIg4TY9CLQ4o+cWc852TehzywIK1gVOk7cUWsOr2awb/W4D+NtFZyg3cQqtmEkZbONfroq5EQmjCqA3xoVDFJTNZtDVzmnMCd+t1DO8tzWlL2sJJ87uXvdEFf9gtbcNmtbYpoS0cfa/hNoPjVD6Awc7Owu+9PiViu8Dy6FjInTxY/2hnLXNrJwh8xB9DiirJrzrdITEA2fUXTR/dzFSqw/5swyHl9/B8chsuA5t02hYB2I0F1btbHvqhoZGHLsPfZfc9pO0ruwCa+Obv/gUKfvN3/2QCU83aWjSRW26t4Q20fdR0BxXYrlVE3aTEP/xzpVcNFsUaxVDXsEosqVS1fs3S37ZBwHsE4pdd2llMQ2+jCHbsVGHAdoVSoYmC31d/CyKc2IlyhCkocLB9YOLriyZMib8T4MzL2uApU/9JmSuR5qI8GGMOKziNG57oa6vTgCbQgZz/4LjtQGAJOphICsH1zLZ7b2TCArO8Q9vci/r8wrZg/9cJbIlLJkK2Y5KdY50vR8sxCGRxQGsZAyyzKFeSQfDZtsD+PF+kycDDE09hmCLgNCYG93OE8UmkwSp0mAv7vvBbF3PC+lsyG33YycrGYFB5YmGydnOhiVi65hH2FNFIAKc7sCW3PzIGHXPihiJgA6Wb//E//9ogD4xDZZ/kbrnR9IFL52m3FWkXryXw0obsgGN7g/6fOVnEOtE72czJK+kF6EjHC9ykdxL3HL1UdQP1BYT/k9gBNh4FjoYlECIkmuog0utHcEqdQewW8RnUfdxAjZBu1dU5sczmqZQB4q4ztG8FfhIQzqPYl7HDQGF1VAKaqki+1kturNnqajNTcSpcejlb/s5JYLp/9a/I2De0zZW1PAyJM078l/uOQOncLRJ4ZQaPw9pY4UJ3p6NUhvPKfrAnrQt/v6qPz7RMhyphuKKwPraZg1RJIin0gc/kJFX2AgHQqK9EDyEgQ6CSSgyfQnlsZ4mROtmlVNb11GZQ1ZzMKamoVnVytWGVTieroy0EhG6rZ16H92JjSi4FLL9Qd7dMlLwpBUNppq7tBZ8QMGirmHSdjq5oh2jBeYg3qJ/ye0Fd/EgtpzFWMydTg5d6g4QDQBCqcqa8/z42hbeQX5rxHSELfvP39w1mb8ZS9cX0Cn/yrwY6vHAXQ5jk1E2GcoMqg8TEhGmWBYdccAfbprYWrroWZFHHe2S6ePk9vwB6KC551UH8+LE8QjZU0J5d0EsQu7HE2J8/8DBFa+B1BpiHVmgpqJ5cDUaknVwjdZv0k/MIjg2DBiVla2cwAh2l3ve8Neg4KShaIlcF7w2DUgQqa8XZhlFVjltcV9kOdRX7Bfh2N4DjYx5RWkGv4WV2SJMZlXfPzi0qSvNuoW6XUMMJn7e6FhofFCDk+ondF8gSUZqLKCaD4lLfbaDkpCz/eharAJkE5JxwfQKFkt1BqxbXcWp9d+j1jT4aSIROUgPJ1W/E9g/tFgMNpsyZNR+XZA0UMBIm6RV2Qoa8G5F/jUhTBjF0gdIl+Pl0pIt0pMyLo267v8mx5/kwoqIMLCymglCNASkVDHDzkM0ufHsEZBE94MKpQBeGJ1+A3AJceIThXyZemn9gyJuQdwxmMmb3IAYyGjrF07mBelCZaj1kdzfICI9PP9NrZl7Kxjd//5MJehkNMui72omogGVzeXyIFlgEs+6Mv72JiF22FD7kXHbJJlLhNpF0ThwUkDdo8k2a5lNRNB9TYZusZq1vL512mjwS/7SzRegz10BF2hmU5k86mEC3QZsXw43m6y/WX6ajinQMplQ4ZId4nbiWWfVIVc+haEQ3efO0+rQiZb60JYhIzRV+ogd7AZ3l/DvLl5g1r3eDUX7dRj0M2tBGq+bAv5xNYqp3Za+992P1BCQAUXWE18IXJ27wzGaPa83KY/0Z2QYTwytkZhMn3odPfoO3NOrS/uxH1JpDBTmVpUy0rqcyQitxdXrL31trujV/yB8kWHA0ysXIzzXExjdPFk/3vU6E4H7+A4QQV3QTjbai7N1g9+lff59vfuXFnvYe3VqTx5xBksXH+K5q6eFOUshQsK7fkm/Wuwb6N2BDxXyKJOe/dd8PplAlA1AlA80MkyqmocPEky8N8i5NF9JgE9Vp+2wcQfEWKj+pDEa7ocq3gu8ROhcnNz/strtoMq8pdn8eSn/sAvkDvCNkehy/oL/PrzsfoUiGN45Y8EMq+jBZ7holyV0DL0AVi0Qvpju3vAEJX+fdAIQvnOPzqOeQ9IVm4vPwBHruVkn9ebPfBhmM7qvw445iQV/880s5ba8+QchhXrZHkG0IbdXr7qQKOknvyxeYCSC3vWNrp9+AdltV34ylmYiTOh68IbUmfYtZLEKF4T6Ld5AGDu6v/sys9LlJYApTUvjgTXZ4nwFRu+q3fQzofen0d06fwTBgt99oda/7vdLcWL0sofQzg50t7YwdRHhs5JRvuL3SmfERrYAu7TvkDPIAuLMzCFr1ESZQgv1QMuttb4/SgATAzNVTaL8NpA66to3ZYcWVtbgyxasrGJ2z5TXd3RYOftABvtmESdaPhB/+o4ExiE8+ZJ74n5N3hjL0P/0wfItGoE5hhgrQisr+boxKpu0NIhIAh1Jn5Uf/bAhOSb6r5FL7MZsZaft/Ti3vqNvg/b+DE9pgDu807EdmKBZTGvtqUOk1QRaQWsNz6sZm4LVLeLeotaJeOOCy/OQfUBnRy2zETTwkWPCm2WbDnJ69PWPOgH8xdF/uz5fn5jT78iGLhiSnQnY/hFdF7z75wJxiQ54+6obseA2XaxOpe03tLsYNMId90WvRVZ5EBmYbz0urb5MyY63Xnd0NO1RpnrFX9djeSVcmMfUNrF59Y4JJllqjo1s3yZLI49TtUHLYiN0oN0CCoBsm0rACKUZ04dMbfW+X5IYriDXddpoutLlbaolb43UQGTTnBRDg13egv1G/lV1nFH3WCV0jKHGmU4k8iPs8uFh/HV8ewT/ceSbFM2KtfA8+35P3roOq2/NMSkvKr6u6mDRdFni13/eH18igQnlJeaF+vNAyyiOU+pGXaWMZSxY6bh4cyC9lvN51rT2BQtzqWi86e8eLJ19U6y2YWiFymNkrUKHxgvSciXu2wOtwhAA9rAl/jTvlJHgZFL7qbX8oTFVZZaRrl6O7OSW6xygdcDIrvTMek6uL05auJdvlNah/LXbzLUeyhnCvgXv77NxioTj/Ygn+W3Cte3KJ98rKnCozd+/4nTC34N4r5XlYLEk1trOHB+p5ahyvSe849xZ8KHRwYEHdFjar3Yba5MqF6b8R9LgHlXryxvNesQo8xrrjwChYiddByeyICsr7eyWojbKI33JG8vNtJ9iDQyQYlXaxLxeoL/It6q38Vgz2jluykkL4fM/GASnlRmG520q5kY1uBE3m/RV2jZiAUyszY/CaRp9rx4unkD574a/7YlGUeV4rYDEx0XdiE30HuNaVctLOjfpyJEmO93y/E2oNSeJ/x68RVE7Q3/Fidk/MQfbxV4fI3h+g2oUQB6hYfc5cgUO23M0p9lN/At9vkzlQvA1Ts5ZwBzutFaYnLBPoMIqwHEwF/9hMMcMMJtZ4Rdo6HvJAj89i9vx1xaDtkzlbuEvktOLUlEbEdEe7zcw4TiuX5jihvb5oD0MGWQSsMj9/9aWZu+dtpedfRlxaoKbfmYp1cxpphKgkojV1lbZQ5nhgpqtRGbQHsl8DakXJrzKJ/vzuzaYH0uE2fLrg72y1PeUa7uz84hXLLrnWyeJJLMqZG3krnQ+/r+D1zIXw+5u9UjP8dh5zzbfhScxiHPqsMImC4NpBD2w00GpSmpkXEm6AyIJ4bwmSLH5sg2RwqmZhopPenmMc29+lv3O2McCcnRZmaLQ38TZpAOoRDgPkR3cLdjvNT6trklV64kRGrtY5JAKFd7xjoJ+EATR7ArabwYJ9kHF8gl4Hxh++MCiO5iEBTTCIha9+hY/Rh54BpygvPWYhObgpHnMOBEVBFDEkJUSv0WoWepjWdqqY2kPemwWOe8fdI5GMCWd97tXX5l59kVu0vodzWStRup/wLo0be67xX+tSjIuKZx6IbB2nEnU1dta3nfPxhxecZkzgqzm9eMErzlpMWoTjV3tmotsg81qPSXbwbIR9a2ITa/j6ear4nnjLmREpP9CNrFWzDw5mLCX5N87im32JjUhw9yvo7MydYd3BqFu1RFqHm95CxcL03tLBUXNp1P0dw2xcBwfn8a2FEHV/icHuLwGpY0brhSWPY8iBisIwEh0J9A4SR4VTAIxHKYmKEo2Jy0fLXtkduq3Aon+NukfpH8QQnf2OFzT9Wsl8Y3XtOpqla6PSEpBU36NM5G57ABsCtkXB77cauHVsG7YNMo8igx/EabD24bHjBmXLuumVlyM5bZcRRzRwbf3pTfQ9oP7DTFgJ74hfF2ZWQF5YlgsGP/EcDSgyBxJe0kXETJ90MxS7R9aVAHtWLBavBA786wask0W3Vam30CEb2oWv2mLB9+TVWiQSKrHXearjRVrAEv0i0BiXENy/3uqCvDfaX4EWKkR6vMzY5r7k+0BOcyjrJiQHBmUl8R57r7wL1OTcKIN64Y6KrQH9tfaKDWjM649gOuRncdEmH5T2+MXZOv+wUYI9f6x8Y/3CxsHBjfU5+Jf/QozY98pQszY3eylT4wRe2fcWqyAcePwovFnei3gJN6i+XRS+sLZBBd2XtVq8oDwzA2WU5dkG2dJD9+I927mqX8dfF1/3FG/jV8tX8cr2+tnyVedN9Dy+7gS2893YhLkBtAkHszewF9VvJfZlIGmKf1cJayapMqgnYhUQz7ltYMW5vEEbBdNOlFYcSo9YugwUicvgemVYGczcQnlsTqwfPPm+8z1M73mCVbACcrdIeBXLcCOcn+dP8tagsVDBhQ25Iust/cf/fH/jhJTFb3rSV+zkIuvaTQ8zlPElYb2EUqyJeZunmCqF+X9K7LVN5gCLEXUGpjG+fHzeHm/yClbQtROGecMrf1cQ5nfFuMSUFFnqmJUiieb4AZRGIJCwYlNUx5JLYhH4cHAgAgcwyqPkes51jykTImnTSnluYeWVKwsrx4/bbSClsXMJSriUWUVM5opMLb7C0nMLb/U9uuaTc3zGzqOT9Go9g/3hnuVTqyXCcEtBlZ+zILycjmu1mCfCdG5ATDoobNE9fyhRK6Logx8ziAcM4P4MUb7Q3yp2h5DpncY6GbsZ2SsmG+iT32aH4uBZHHVjDk8t5DW6J5MhHJ+O7I8bMe6h0LBC5z7QXdfdbYE87vdRJ+9t+W6/Ju0GK8Vhv8VAaJMT8fBD5zImlNFW6GcMfOcRj/zPdQtL0wrnP7muohm41U3wgoaXocuJL1f7wJLjl24CA2d6LU6xxqZRV+yq90R+5a6vEl6y93DOhtB+ux+n4jwzzkzb+fc2P8+neaUDK2kon0m9QSWLM6Zji2JqSkwMCUNWji3OiWcaCR+bne2h8DNWIlhW2HNrJRrEsqIHsazEg1ioLqiMKcDQn1A5SjTQSJVKOBrInoezshdx1d2bIl4EZwpLF+ptX/c8v2btxSNHDMIYRJyrt0JP62NxH6VjpOlK8ihN4JC9ppngVXQsM0IF30vUXv/4i5/+IHk25T1UZ2LNPJRE04oZyOjhk9+wYH9DjSNArwXlDumGEDfmFycMneIwVM5+QxVt8jLhy0B0F0Rsk0bAyDMzTBIr0o1s7Kwcv2zbedznld1Z6PqBl89/GFeF4sYVqyOtEyNs7hXI180geL9DMjCo1oiIAcJM8K1XKPiGdEeZ536Hwtq5kfugaHXr/jQ8CNhrmvt9KrXFgz72IkEfe5OCPmTt0RtFvCZlN4ovwVq3Gt1Udz5Qe7Z3JrMLKKTFKSBUc4m6CL/I6zTNG2M+8dCYOPU8MCJpNtUojZ7HwjScAbmNdWoTnPOgfHpkRjguBsNwHz2qgBS/NPDqHl+V8RgG3XAf8pN60pVkTXP5Mv/wxQTaxVFNiMtJCrClFeJBdzcX0VcW/e60qDt4/M3f/8QIPegNAYv5gPE445u/+qninKW46pqpzvcZy8gjTPw9M+PQAopPDAnZO1J8yV5qfInyC6jMubgdljXUsejjSG5FtOA8y+lMJ3Or2251vYlHs6n6MLy5OJmtRcNc8Hb9x58SOipD2lK8KBGSEF1JvgDOi8RhOm/K4//VSUcc2lvRpVo/pDGcJMUFPGTyzKFbOGfiCXtVcTaf1KDRQSAPbYiTm5TO49wRgolmwjmU3Nivwrqqs93wJnYJNKHEMJsYeyKsK+Q3vHl0f0SANkKHezfqR5LZYCy455ntLnOycBanE00rLa+AdGHb0SukjPlKdt2Ou7s+wssqZFMlcypX6zUacXh1u1KYt6dz8b5iH7XBVtc6OQfSlt5ivqAtnJvnYA0QPry69+5N2ePrnnYdlBxyFgbRHDl8N619Ur2uYz5lX5gIfN1rSfImBO4Vfm1HMIpQFybaRR5S1glkjigTRAPC8loc9rzBVMYt/cRM2/BBBl9Vj+deTLyRnJCH9RkcJetTBIX8FUngH5vA300mEORi0SwEV3OyVwa2s6VpVI+efEhu3e8YhLd8n4HRP/1r4+uPmTrw9acGSyPAXRinmewjzN3P/wldF786FNh1BDGYFjLw5LccvfYReZ3S+B9xcC6WUwG+IXz8J6jAky4vj7F88/P1F2L0ESskQ+EmcP4nX/xJZ+SHj6TwyQHc0qZDN1whtVBWDEEgpHc94hC6uQnkSw7HxsIxeCgH6IQEq/jpk48QRY45soSRqTn1uqC/MwjyR77Gd8vEINgk+sh86cGvkPySVz3rRZLbWF4cSu/wyGCYzGqExOT5cNEMwez38LndSpydFNYqX1O57B20hNE9dKJlIryk1oSf9wjl+kMOzjDJuye9YcQuGaS2zH5V/ZIIlBMm73MGD3tDYiNtGhZe9MgHcO5v6lxu+r6hdJTaNfzRTo5wBO5CYL6oe30pmaEy0VKd8Pg9epK9z9MxYmQQ68nICaEthnJDgyAUwUQTmttiKDJKU9uoeT40tBigRwxRl9l2QNzFnBKfYWOfMzlYRATRi7IwYTsy5RTfevV11foWeJNUEpwCeQWwtbMF8kehA5pCze02vL6/M2iP1hBHDFHXrq8sl/YrlWbQaZcCbxxaM3vpVfOAuHDgBwcdNvaE8WnzwbI1oBJgROY/ctLyiDYeLhUpKucpfkIbwgOHJDqyxskVwD8MNRN+KqrHmnwbz7ckyeERsjsWfvuZMBpA94qq35i6L49MqFE6RTgXypVkaBsYjbBReLOAXQVEo32hQwX+U9RQGrA4nbC1G17MPAuDCPpJYa9BU33K7n1DphHU1F/lnSzd+DI3dbLGZhvPqe910OO3EoKVBxnvTLiMmax4xWMcVAFct/el3ThtTA4djjWMdI6bRjsRtfZuTgKVOkqzTJ4jW/8n2rEUt9px26JjGieMrz9PwWyZPE5h8stuTJgIRTO6tWlx6mGG1zsZ7erWJ0SUEAaoNP1+8nAV6SVtYRFC/wGzwwCbOcHROQjSXUo6MW5DJ+1zZDaS9z35vi5yxY9mOQk77WSIgRQZq91KaVIq30JJC7Mv8MPz94RwwPIXUrIaBiQMhMuTKn6qTG60nYexLc3OjvsiOQR9fvfpTw2RwoTQlFMrVARZRRTWNBk6+D6neGsSMH7HR/QovdYEOdfQCIMcVvVSIaEYVqTNt5haYbB0J08f2NiyNGmytsOVa7Z0P9nIyfoZS9mTbtgUQehCVJHEesmLom+k0Sp3ZY16H4hOcJMlkSLma/sir8UB2kuO+o3tgXhTLKfeu2wPUDqMdybcbsjxIp/k+TYZvPrhhEsOZQ4KHHBW9d245AkTJXcLu4xudSsxb6ZcNiLRDkIl6XJ6XfdmrEMLahhBGnbHiobdEQEszDjqky/IU5czJbo4U9lfmXwtl4hmp97EXZZ4aXhnmcLrr1mXw/u3rIXu9bXuJd2zrBTDmxwiG4e82tRzII/uy/C3QRHINWvRO6BJV0BHx1h4BuMsl3Nqs7PCjaLRTPDNkA70SNQ98uDlMY2hC4OIe2TuBOjJuFKYPy5+t78tP4m4SKXg8Xnl93BFQgf/vWaKg78adome/X3u2d9mrv+6g38MkMHarTkZgQOtjOoTAwfUsIG5SNhANw5K7Fmw8E63PDNnS3D6qAP0erAhmBKPrlzslmZm+mE1d3JiiEl4Czk3DejY0B0NCOUYAcbcEGBshyrz3D5hXNQdgmQu7Tqtbgvd4C/4Xa80YuFwIhwiGvqAC6x7B/v2ol9CPPBYRIRpJkREUPRDNCQCax1Jj+RR6Iu8OCqxiNhYwARVHo2YYH62wF4ptBs/eB231aYnfX+31a3Sw2oL5gX+urUa4Zfjzz5eiuIndGuhR7DE9Ae5NPLc9T3nhtYaDfqY43vxAQaeczNeFti/F8RH0fOcq/Eqrjuvxh++6Xw3/r7rgcIbbw2YxaV411aiscaXow9uetEnS14Z6LrtmMJztoJwthWUZHvkKoWRIUE/sVAH0cPh99nZmZl2sesNmm634vZalW1vVAHydZa9cg9ODm8J3dCsdlHUWmHRGBVYBtAeT71YfOnMyydN++CAf8RYjOwXuw148cX54qkzL8/hi+yjcyVQwPIazirQXkOC5a1qYHmrKljeVa8ceMV6q1uzGvAKnDagZjSwKMlxQVC+6s3O8nquesUqkBS57K8F5SvB8SBw2l6ZN3jPus0DN24DWWyUVsdJwPaI9nenKCjX3r+BC6snrmiUO7X1sMwGenxjwFBDcF54qSHfQVfx1dQgn9tKnM45PUznXHaUTqsPiwq7quWhZTUsLLplOsoweKjHxYSYnZaI2YGFOzHssTwRJ+jfAv5S7DXR9JYQz3PuSOE8t8oWLMRFnT1fTArluV1kw7NpgmdWZ2d1LngLCPwG/Mu9lc/Z+6sHB7ReSkDMKg+IUdYrIWhfxXhcWKMtnsgbZ2dHMB/uwENtt+p3erC6nrnYs0Z2yZoBUaTGfp5Rf0bnVKDoHtvZNoV9NVIpYlWhiNs6RdyeELfljjCutSKOI1Ms+9JzXfbbR1r2i7Dsq+UlfYWXkpZ9tSgGgBFcsTeUH9m2m2lECeMizPhFsRkr8NU5b90qn711cHARo1AwiM1rDzyDv2zNdA4OZjriBSFD3Cqv77dg6FW/JiCW20XJaOFphYeUmGnqLwiUYaBTRJdmaT5RgzXCi1nTaVWxpDneWKgAZUO/qXkgaDaft+197LHsLHS+Yj3nbiqdCDugbqmGiDFL2klhz2a2Z2fPWx0+42MM6dwO0Re6ZZ3sG7iUS8DMZ1T+C1/vIBcYwapcjYQ1ruqbYzV9c1TddjU8OW0nLNr1iVO2iohJdgW/UOyYUiKJnUbLYAfxd/wbe5+EHHqbPkV/5/IQFuAfoyUQXooB0nGz9ZXAVn8fEixOZbshC0hPm+KLTnjsXnRuwWRfPG7dEifsC/D7nI0pWe4U2xjyGFbaRh9Zeko/dhvajyh/0FOxnOeeK4dZPRKHWQIO0yif0/nFuSQO0yiyRjkP0ZnHEjCPpXB73YQHIEFaF2Hq8IeBDxIXMhMmi1y0Fy+WljiNi5NoVYnVvIoS4Hg8TpY0gJRmluTBI8QLENGutzoe7F9WcruL3jinXpyLpc0RxRoERwNCo0KlnCAl5bHlZOsGAplETZV7chRQgBvuqjf6fqc1kLIa9pUHCi3b+43wsy4Y3S6jRg39qsMRV6gCVRN/TssTdlthtbIMrDwweF7MYqlO7AWa7dvOLSDmNibwGrVBqPU8oNFbFLFUNptB0BuUTpzY6fa2GyAHdk7wfvyf88WXi6dPgGofiEdF7FoIGIN2OE7b51HTJ5GCk7U6oDvw0lJq1rNzFDS+ZO8vJSDloOmJIdookycx2JZnZ1Pn9GLq1DCWDZNzESfnnHOxOOhXp5wIHNLFot/F/pUj3aMfPMzpQb+sWkgYlOMDxsPeh3MhYxovoi1mxeeMnlPStIIN114ang/nEMhSTli2gw8cE4N+QCXRfiL2xblhw9Z+6YZ8ctX+TyQh0VaxlkQ4+pKMRreDZt8fGuHsW8lSFHSTz5JRBwXcq8l8VQYrcXCA4Xzdvr4gqlqE6recNdLC5UxhD/HsveTRMSyWkU3Yik+1LdyzzvG6zmXVJY0At4uKFe+cegCGtoPbyrF7LmRuZE+4zY/cc/R3TAsdig43LNDTbut6GuKvcxfHXYtuLp++zS44RbgMJTD/DC17v37yEB9/RmkBfGBDoRCWXslvnnxEWSjf1V4Up8Eljx0HyafBdTgNgr6KDpGsJqgyEFuAEQJXoQoKNawIkCX9WLldJv6/qOjv7HwvLXvOuTIdC5Ef4XwvuQHWe1nWSY3LrxyWyxJTcwtOvss6yJPYYKvspkD86OyzkJjS+m3n3IaDsWUIk83uGAg95v/CR5gpD0S7AOh52R0hKxX8bX8wpsdFtNkNgj6cBtBA0e83TuzfG5/Y34P/RuNijzyJQRii6uZfdkRYPsm6s1W/N1ow0L64RlWsuD30eLtCXAd2K9LkdR/YutSgcRz9begJ6/i+Ai0zp5ZH7mmZ+CvwHY5mJk67i3h4LLvBMmwHe6HbB5EMj+ZbNOXANJfoXZ7zC4SN/YuIAsZfoLJY0BEvwhfxPv/CqglXYsm56ckvF52IgLFUbHV33XYLmISHYNAg6M+fnFOkGZXcY4F5csOIq3/EoOc3fED/cIKYqvaAVIoJIPOS1GqcpFD5uO4EfSm5HAuStIk3hYu9YFpH0R1i589q9PwZgBxWbWq/AF13TEe27yTJ1RJgYNnTS0jhWpaATfqfSNBmB9U5cVCdCw8qlU6sZEEcnVIeUx7sx3h9qVCMIBVd/JlGqCdbPRPpb3gotqNcPjt7OUS2CzcBSvbyi7K51kmWx83EPnQbG8o+woI3Wt4wqZgzf9pO2zIfYY5zfv8uR09bR+Y5Z9skek6MF9Kyjwy0ZLyN8vq6iYZ4U7EF11teu1ZJf1rpe3d5Auun9w25HAzF/QGlOT9kABkzcxvOukkG/nhVWY95EyyPM52K5Bv0kGWeFhXThUG8hqzHomLymOB1zVMnpb4e69DEX2SlIlG76CBT8KOvpT8Nh035sFklUtOPvjHpB1HbL5GAMGs0Xw5uWYiNJvM5rwzDSLkDCy0wzR1dwyTQSsZj0Tfdkxjr21hAoBR2o7XqwFnpLDkXN/y60bCBpuna4raDljr6eA5ewfsKzvHurK9uqCg+fANsPn3nyRcYMfb0r8nZH74eGn/49bH9pfEf/p1nCwj9K2W2gE0pB4NUGHihhQrT+jHHr6gTCYvPfIRbgRCdRd6BUtJ9izS4sksX1QQm69eNb1ktyGwmpjkxw0KRsLGVw0+aELpcILT315TLiCYDFEMD99EsaTuDwO9U5PApY0mFMlBMaVcjZm06311bvVIc0KK36iOLJXC5hSdNq1ZCrZ+bNG/xC3S6KYJv7Lb/LugAwprlkK8HviPApsZk2ooAKN2xUed4zcUTbf2Ws+ojfpLsFj7QTmNhU2eHp+lsY/whHB6KeU5cgYkiDU+z3omfOSaNdTVyM32VZ+WzY3Y/8WaVoF74zghAFvivIQyspcsA8R868I5LF3um0LvCvEIJgmUxLiick0oudQp06WLX82oDcS9ycLBUhI+VnX6bTHz60safsDscTB8/AwLyAE3pvBBVQVLr7sDy+s4+bYAKUCwI7vyjwz5seyP5ED47ZMmFJ/SXfQs9WcTz8Imjd6kU7aP8ne2S8Ge2WwIQXbdH/g52GCtXvpKSzWcdfuKfDg6UuwO+lJXOoEGlQaEP83iPVZWvLoSduqUr2FrsAfE70BVC38pDUiQwgEllj+hkz/Rmp0fGhCWHLtVKJtBnq4+eeiAjo59PxE6KK5KEZ1SWqxb2OVIzbSHcbHwCEaJj7JwOTa5jrBxrba8FPip1XE9BfAegAXU2IlXLe0BYLCARyv2eNmF5FkJNuEVyVbqlYhV3oS5+fsA9FT948gXzYkXXVnTPLBro2M+0O1ybR+h0CG2EJ1MonjaZdOpUuuWJ56HTCMoNsjOtctvQamjcaQi/ELTd+EzozYOEJto7AgSaTG4jEzbkzg0kMMwCv/eccdCCo+KgZaQB09xIFWoi1oB0FJ+JMJLNUV5AQW8xKQoxUianC6CcgkHgZWbDVHLQdbVATaTVaCbjiS99QhE2XxBVf6nFuyW8DJy+lnTRT0FktUWzg9ncTa3+T4tGxK2ee9bWFjMARMLJYHvcOLavNKzyO+lbgD/E2NWiycFDTYXhqDAkR6v1SOmWKMWQQs8/VN3gI7mCjnxApMYJK9wxZItPfm6oqkNcFqduhK0laRFZ0d2hfhu2X1KcchMQDWpSVLD1mJVI3VDzQ+j77zKrq0UFB3KK5U9l7rGaOAbTE9JE4Qoo04t8T8tMT/zoMHOhjG8e/MyM4JgnJJz2Y6hi5KPOfCPCgEVFIGRZLlF/ZSABGr8MRUX6SekaxyZD2jjkaDt6EB6Pn3suZK8lvtTJvna0Y56CTvjg2G2DMjuf6fBA/zUpNK33cR16QmOaHExMTpObny0XnwpiEUQx67JP7Y0wxnDS8TgNgqN8CXEYE8HQUuJbYO+xcIu4/HAqGhelnJ3GV5RuKgJSkNY5v0AWI+2sj8iNqq0ShEYZl8PEtrQazVxpjHIYOFN7w+xb2CPTeCECt9Hq9lAC3WfwwXco4pT82TGsuQQyb9uzuFG2UQwomwsPOHXcncA/z4/pEiujhCcl9oabWZ/75Gj22PSWp5kIeiE2E3wA2VMR4IBAPCmZbQzzpZpX/Br/ZdIccYsxaBHPdY5UK3N6u3yG5vPMEL0QmyHe/ewZ4oXkHE2kG2n2ft6ko9rJM5tOpx3iTG30rFWoR1zxq9PTCKzotMQ4lt/jLI7VZOrBg4qxVen4GOsho2NDwmxF6mkokF5jp2GrecySx80uBp77dPPbhNQmE6d55hiC4MWiHSNzTo5i6nzTNa024aggVCp+0ASJoGLa+z7dqTpIt/wixFR83sXDyKKNn2XV2Pgp7Hq6BXNSmgqH42g5jSkq1rBCEfeQ3ZPYTPFQ0qVH93VsHlNnIrKr+VVPoe3teu2TPBV4029jaudwSyyKs4zPBR3LoOOgUKRQdcb9AiH9Rikir+3CL6A7HmWJVKMzmd0mWw565+vvU7A06zhH1RPmgoytJO/G8u8mo77TbufcUvxKjQUV30/l79pFXPIZiHkn3L6HGlXfHw5KJx1BE/zlGFnIoWVTBnNtKYjSE9k9v/173mdg5MIwo+1pzkHumRoTFfggojOTKBh0dzpev1WdODP8GjP/xExDSBFEGKlimRl9yZqodHKiV+NiJhtclN1qcblBf3GCmA5stTDsu73cagd7JSWQPQFu84+/+MkPdMYEu+7tJ58jk9Xd746OcjiIJD19FU+KmYbCoK4vylxoYXuCbRkMAE39ieM+MR955iinKaoTrBmkIX+EcNhIHKi4Mk9s0zG4p89GFsxRgnmELAESrJ4gGJQMYMKr5Cvkbd83JJODH98GIfZtniWPFKJ3BdapEQG+wEIMTiJmIXGu56Ej2CNee0pC4q5UCenINb7xpkb+341JhurhaWoON+FckC2FTxRdOIUJB1D8aejJBmZnoZFYeoFj8A+6th3ZkICkKmPOr3uSbI8pVoXrcOpz2Ck5Ek6RbhRyaaedNrNwdkCfVQ3c9STS7Es8wQ/zCE4EAsmZ9pGu4VQvq8sTHa6Y62Ij3dVKLeDMY2LQbt9SHtrwh5+Os7ORg5V/xCKhXzDpEjI0En5CAY29KkQ1Cjka52FpjSI3McnGxlqH+O8z5bJ8KLHcOgQJrdYlq5Ch+7AkdsSCmr6BWHrGFY3tS/a/5C0+P2tQ1GkmZnqcxBN76HqjIrHdp7PzUyYuJrElvgmo5RAtWkLKPH3v6XtMjKMNjsyN4fOcML5Cofn3hLV+QofncdpFBBqohF4aTZh/ZB2LmzwbQhqAtcCoPrbfhRM+rRq0bAr8wqS4tsUp2f7HCggMzMChNllPvq8qAQgiRNJ2prtRHohocZ9rCCglHj6lrmfgaUpZonCFF16NIcxYA7VJjOYWqIxa1vgIz2ecpu/WWr7pdHmfKgzinCt86CdEEhawUVl3eEggV/Jw05Ml28kEgW8MCyl4LmnFNbSYBno89VteH88OFGPNRRR6PjL1X6pNt+f26bf334/8FrR67h57DQkeSvw8E95nEmsifqREeGawH6VUhOfIwnFcrUG/qk3AwN/pV8lcMPT9qt8B6byKV6f6BjU5Of+SoKEODdplBFslBS4tCVK8XeYWJRsWaAINCQJgv1KeQ/+7cN/STMOvkVsI9lBcQmyMHaIU2LCqr2AP3b7UGFAemDrdDo7yTdjMdNP1Ft5boThySOjPys1W3BvqXZonih+OC2cxrKtK97mz/RT4uHwzIK/jJvPwEwYjDgYHPgGGP3Q6kY11pmBJ28/MkXqYKDyBIW0n8qPzR2ZH8F413MUh2hV7LsCuYtXgjwroVS5uRljHfznms6HvR4Woryxmy0LoTR8O40rUMwVkxEGr5mW4ptCvMel3wgYyGLJVjn30EV2lfa7dxk6gb2iD+8zKerpR+s54L0JByYBpDQ0wLYtYpoRIS8YyyySbDDi7hoSIMY3/72eGSdI9YoIFmPuVGDrzD+7ngeOGuoxOq9uaPpMg7mOX9rET9qkwn57ZIkd9O4wvyJztHz75zdO39Ln+2Y/M6MaYdP862Omg7TZ/RgT+TmIyqAQg08eYUSAJcj9aFBbnijxpVZVkyk6kXUgJdcS56jEoceFeHYMRj74eBCAuzM5e9aIiA3Q5kF3O5+FG/c4zccJ1wkCvJ/yYdrbGdwp0ay3QHSeOYIHYamPyqnaDJecswBmG0KzbilniwsHBjAhJkJQK54zsyAVdkYsJLFxvY4+ZWpZbegCGu9P3IvkGf5yEvs+1NdVHgbvNEMgJpi35txARVGDXOrVFXNBSNt+FWaHUt8zXtO81WFCEno9UHVU+aGhZc6HVTUmJmv1aGleNcev2VlvzrnoeVBcy6OhRDhw6AexLxfVCxi2ZhYNRObRdYQmVDcv367M56hjahNHV1TNStkbOqlj+XoL/J/tHQgwOm9a+hA/0GHxg4HDgxPPBHuEjElrgGxxMse9gePNaq9F12yUJEWi0yjMzXtHr4kAqg51eDxOog1IZOOu+48Yx7HacehzDbtcZxUtGwQc5FGAUZxCPKoY7NJwXG2MLc+7hxUXJK2JfKkOvDfqfV5HObqZI1jJjgAhEmhDmvaPEfsYff/Gjv5Y5kdnvh9x08RC9dfAGkdssNmznQhQwr1nmrTKywMuoFk46ebwiQg6ooOwv7AevHhA2SSwMvzs724UzlXCVurbj0h33easnQ6uulDf/+Iuf/4DcCqWzPIagdYvSsTqaRw1j0+S4DlnYNPz9hcx0pEep9RjIzFr57BoDmdmsBnsFbKFVG2/ai73SerFY7Dk4/dpPsXW4Msbds4NhBptTdlgolh+RmxTeldyX3X16v2RsYl7d9a7TTgKBuqBYfS+EFl2K7b/u98rRZ5cIwggr3HZ8qHCmlRDxWovEeK85d/gvvfIOjxOkyJ/ewcGuwD6oU6ibWLqOiFPqsLEvbh7b7wGj6S2ct+4BQcO03mPTunNsX4l1ETMLw+yHU2s7o0hY+T09kO5eeiDdYGer0wq0rSv7uVfGeQuImnnsXFDsig9E4/SNIsYW9mZn70Xj7vZsJ3zGd5/p9NSuXi0P4Dl0SoSzNLyAYllMt9Nr9qhHFVCDTNbKVbUV/mYF/ROviliTq/b+GAM7wykG0XT/ngqkhRyNXuKxbB1mZomX4bFyspggVFOGvl0tr28swC8xLGD+yIYjpdjbGTStTbr0uo8Xb8f201+AjeV0ZO6E8G2ReoG9LX5npRWNVXlBXgXjK3IIStkwxd/JM3M2VhWfAUyUDmQG81u847e6lmkcGIjCxkZ/g1OIDPULihiSlhny53sJ0YI3EiMB76VHAjp41+v1B6X9V6tVrwdqIvQcRkPBVicwRtAcY7Qgdt5iUIeBV94fE+XBJ99bpJBPAhqxfM8u7Y9DAmKjuw6lZmd9NCjj8xPf21//3uB7axsvfG/8vcELx07QLr9u8xqV6q6vz20IcnzV3qeGx3zKbpYDTyI6wi8KpCNsc4wcVH6l7woiLn9SCjwRXnizGG4C6klkNw1SdpOgae19dQvxAF6vbAEzuoq8ToZRG9cN6N1VDJ/mPb2OOReoQhjGdT1W2hDf+bEC+rzjBeWr8iS7XuZ9uUo7S5w+1/HAVO898N7gMfc6enJoHhxklGAmzKIabDEpqgzqK7a61fYO7BBLcfxP9XlXgt3sCS8f0knLrKvyBez9vBjGnDkmIu155YYHq+K2Kn2v1x45N4vh30EPJsyDj3Cyyp+Rwh0dOg6ObHje7MMWiYBTrAmH/4G9aN0p40EjSspfNoocycqyGeItLBWwXesqkEq3xu4G3BZ0/GoRTyP8iie9bUfaukNdK+GSeoHVQ1YI/5ZR5CMjdsCC4Hm0AVQzM89K0ATw3sAACfrLie0JTm9yT5h0QogUcTKcMDFodxq6sJ0e7DbqOXLvJ/8vk7XpUlC2xvI7of7H8ypHjOlF46sH9BtU/+GTf2MJrL6ksmrrihjGvCkSM09SL2E4D2T4PQn/eAendvs8Lhyu8FUmSmwlihKhkMZ3ISyUgwpdiYgQsWXai5JZiCc2g+Xk/OKevX/e2mON7eVrzEwPAKUsk6ARfkwXpWL+nnwOg/yQpRj8wEB/E8pYQmWg+Fuk+4jh051jZGbfIj7xSEruMoh0xKGw9HDPjJibPHkkWEydGxTq7hYcv02YAhPJtEB8uDCog/Q/f6p4qjh/MmrHAxl/pmfnSlH448eZkYe6ui2UIQpMltGXKWlN/VSDmBwZVMRHljc2BF5Kc9GKjC1fX7UMMNOZN13SMgnAsQTnUNvvl8xv1et106mDxHOT9IDSy3NzYy2S6m/47MTk94nj5vKUFnmSZv3uPF/r92Rjd0c3dieYUhLkVdOc3iZSxWwGpm5TNp58wlLIEfsSthjNkafCVeu+CFDv61mB5cJMdiGgxegMGgPmonJBWY9tusXoCcejdhNdHqGfPTrmGNvq0Zk2dnpMY9jNuv5hCQYN4HlKukGYHULNT448DdMAMg/vnHHMMCR+JRjPRhf6b7IrwR3NK62zuKlaBHTFXJDFH/4derOpBkOm20SKRbTCDAel+fCaEaa0bvUivnGhmxvaNHpRN7eZHmL61wMohEJB3OetdgSXN5qpAcHgCfqqhWa4XZWi/uGfZeB0PHvJqGbtb/l+UIItQyaAOzVnWHO2a06l5lypOWs1rh0GZZLAyc6GErvThQd4uNKXflkX27wi5xIgnIWfMQlJuyyhlLl3QZeHMVI+Nso0IH8IH/VlipYWtIsqGLXrl4OiyCNSGYw6W36bwvAxQx+LonGhxAA0CK/Sc0EqRXUDSK/R2nbbbqGPaah2ZAnQLnqYEUUpAky03KytuxsHB/BnBxGUvuXVT718Gvb+rpD2QWusIvRihbivCrLkjMq7s7Mn/vu3rPW5wstuob6xf2p8ID+/NLaPnWjBRhwE1q69uFuqO51wfs44Mt36S3JSAjmbFTR69BjCC2KsOxVmv1vxOr6lIBdVUVcUeVokUs9Q/r5cHqYkOFyori9vlC38F30/js9jaM/YWe/nTpNiIvNNyJRi1hj1mwkpUeYTMqLgcDoDC7GLivxVnOydDibWGPk7mFvjNEnNp82EFCf89XbNWd+wk7Kd8AKDmkO41/HEJ2TIjSc+ocf5Ep9wq2809Ql/HE9+wn+Ipz+Zg6crzuV4izcRrTohiUvfWU6s2g2cK0Es4crVWN6UIIg+WV/DLCDxpra7zig2QXFgVkGXQw4wgxbGQGxkWMrwcxGNNm6rO7CGnOkKXXxhm2+xxTa1UrLYX+gA/hHy/DLo/5L6Ja5yHE2640MfQUBDRqsAMMcLBv5OtYlJwvCIcvZ7wJRbuwyolIFxyncZmE2+dhLLak0hNsz6NqxPrWlxix2mh2oQ6O+Kz1FeMZlTf1yuN8mVutxrWmcS5n+XtsIeGof3kmzNu7QTjuHvx1J+h514B3+/k/S7WF85PPGBA207w7IVQd29Wx6RdQDOc8v8FkW+Oa8F5buc7cN5emrxbnHQa7cC5DQk31zpls9e6R6/0rW5Qc+0S3edyzwRz+vdwHotcOZfsp27/fJl7+zZ+ZdmT774orPFvp2hL7fwC37iNLLZb2y51rH9u/2xc2x/i/69Bf/OgXZjb3KiuiuSdZGcPVc/aTIw1uU/67DIyDLF0LCsPFvm2NnShwO2Zt3tv1A8g17gW2kltkSJW2klbokSm986tr9+tw91QeENZURwzPPzEjpf7Lm1NSRt66Rjzpl2ONZxbJaNEU3vQrVISg6KIW/0fTj9gpFlFkABLbDj13RGcD5NKFQAJXU5R7GBX4cKh1TSC17lCMd4cqHqi2n3mDgBEkZ6GSFQgJAhrOyXUNpo+j2mCVZQRUNbs9vric/MOsT0xZmyCZPrgX7vIWoaR+J69Y03KkurV65X1l67duO1a3Dq7br3WqjOdM2FnaZ1Sb2VuKtfoNxNv0AJ+m51u0LCKZBpWJCeVLBfWLgVeJXdFvyBMs+Kj3c3Cx9PwcXbl9Bod5Gfw5kWnUMnnEJn5LjOThZfwnPnqhJRMRN+SThwZmevaiBaGYcDh5eusqMgm7vLsmMUSkIIWur1eZ4R27KqwCwJf0yR1zY1i1Tl2D7bhy4QRwdNVAtXAusSs2FdIhvWMlP5qmR6LA3xni0C9MbeuFSst9qgtVh3y2fvgl44Uy5jToWTZ+bmeDdT57TV5HcTQ1JsiQCH5Q6B+ZHQEC7gUEUxXy57s7OgIritGgO9lHcG6kPl4kB9zFPPohLw3BBfitxJmMODivDfT5mB9KvDJ5+w2+an92fovnsZlLplCetycLBM/ZN5OO6WQyjD5SQow+UQypAgARmc4XIKnOHyJDjD5QgsC0P+1yANl6NQLgps4XIIo3MpHW1vYdmDhXQuy7vaySh+rwUI4weqrjPoWnelTZxIW8S4w2xWZ2erbALl/A0lRQwVMhiWQP2DoxTR7+46coqrykrcVT7DEMMpr6pTfjcy/2Im7uaeCRgamwqHZbbLNyGXPWVCXgtSZwSGPyxilGS45QtcF8Tn9tnvnPRetPO1ybmnZDd+N01lXPatbbGf+4IrLJfPXkAIT1TqiOqT1UYbylxYnJkvVRdrNWsZWFxpRoI+GjUK40ATXMEdVLGeIV3eDDeKAx/kAXjhEkKhLjM7HSqeBetS+AXzo4RVUNLz1DouqXUsx+tgl+JprwsweeWunEAw2x6Gkrt99NuiEpeUEo5Zd82wk35vp+32U5tod+GwhiftWgE+LrOP4mWRoz59fJwOLmnp7G02XP7bcvw3zJKDWryz7VxwanAWVLqhUDfPhLqq12pbvnDlOtGxCXSyXrOuOPNQHr6V4Vd26W41gsK8/UIHSrzQSTgi1kClJz8Uam1BACTvSYc2OOMw+9JxIGglUeXtchVevhGxVOA13usgEu2x84qceaqUfw558dmyzFd7ic+YoLtLaMtAboEfCObYwk+ixePzY+fSmJWlF+ngrIZwyVUNLrnK3aGlk7R4EomDEeV8m4MpV0MwZerEPPkTAQvYRDtlVdop1VTIeCL9Fi/heAqUTVW+G+ry3TCvfDdMlu9AqKkEfgVBprVCqq8Jzgoj9mGan0lV3RJqKZZ5OtyKzyxDDqeWIYfEBc+VOd0BiQFjWyZNRaGpRaIVWqPhuHTJFkzwEvEVopo5++wc+mwtEaX6nkaq+PpQCo3L69WNxZqHhwd9LuE/mLNjGQNSL0akvipDi6na+9+Ff5xXRUpbMnB4ZOCIUcCb15atJKReG0iCBYG/4fbdzgBVFblc0nlCLqnMoNVsoVF0RC44PC0xXfgJN0p8YwwKLaxuDzgGxuwcNxfN45HWpNJn4/aGY7TJmlHW4k9Gy4Imd1ve8H9nYlZAmoVDoLUf+Jjoe8trurstvBIcdHw/aJr6LgC2LDu7gYlkI9oHNvVdRn0KwVUnEBySrk4FsO4hzaFsrP/MNoZSYkHQQzWNmhbSyZSoshpSpTVcJMJEv4rj0DOFCPlcVIVc5Kz6uJcv4q573U/YlSCfWjc8K+Vs0Fh84kkRSvKx88IR0hT8JD5qaUSrejyXc4kYzvD4vJ2mnOXmEfosN1QOwRxAhwcHM/IORkuCtUzyIeyHS1JiQhZqAw/lX4e2vQBa0ndBvtHnu5+lpEcGsPyMA1i2JSFrIPeXRPfvyu7f1bsPGvDCJez+JW3rCM7OuxM3BoDsNwjI+MPtAbxkojVALcxmRnjYNiM+unBo4YD2ZGbs6BxJ9rmcl30uJ7PPUKcOvJ5WjImlzqr6jCdUDaU6ODuhz5dkTvK7Wk7yu1rswrPyzeWp+eYy860NhMd/JA3HDPPhyvh5LvNXtGnus/Oaacmc6JaiabPyr9mwWkFCqLjdmmxJW5W0DCHLEzKEMOy3yx5PEXLZCxnbZU+Tei97SpYQ+KKlCcH3tDwhC0KDf54pNZaPlMj2taAMakb5ru7Wdzcp6VU1tCUxB2ALuGyeF4fcYbglK4DxLbwWLCbmT3gtKIHQH8KlqdDxHDkbUScId+MBQ3qTKa0kBWtNJbejFWFN6rkLCENJooPLXsTyZy2J/FnOWpNuzDEut8J8l+VuiT6dS3oIe8O53Y9YHqSRAU5ZOqIrQm8NfbvxLocOjUYt8jZqbPyyoYpr3itVnUEJVOoqU6mRNJjmzGR+EJQKIFooVZ+2xdtwMPNm9mqxTrLyhfmTduhaygpf87NtKYwBzFQPDqqcdb8yv7i+UZIDh7O8VrNAArTDbp2xmaK+Ic0n2ZepgWLUzrxFBTEmcl869SXpZBO3Vp7JV1mueMClju2vLJpGaxBmo0C3sWP73T57LG45+eM32dNercfxBdjNi7iaKbn8gbyHKe0IRznt4qg0igMTNZsOyNDuIBiU3ABdazqtwaBUJVM5il2KUWyZmcqrFDZMVxND0KF7lS2X7Yws1yooR4GQOcMbaR29viHfnBToyIcL/YcZGJXMetvbM52G2yvNzzluu9Xooo0QOCnLHWo6WOAmnDglk2DrxhlhwgkDKbRbu16uAMqajxlhHM3r/VOyuX+ZGVNc5DOLgH+kdiXVQF6zj578lrnjIZsjJoco22FA9SRvNDmi7raOy1MUMUZNf6c/WEyPnv7jL/7mx4bpRF6Q3n18Y4KiSUDOsiJ0caMsAZuB1y4d24+U09xU0VXyH6gNrcyGhvcwAe0KNx/jzgRVaLD9hrsQ7ZzVgG8uRkprTXHg7Yt4QAzghe1FK49qp4lwmyrlZMYCc5JO2AKsT7FXxGOcoateTpddPr40MPeEtA14n8azNeDVvXCzIy0w7sR3y5KWkzw6uDM5+YHoQ6HtN3wRG91s1UD4MUszc5rf5SS8eFZRq1v3k5A05vUNFl6toqOyBref5qbcS6hhsLMlzSYTcDiaLS1rTRaeCpY1IkzG/OZnP4z2bDwJpwKJnimNCFXTxcywRPMYHAwET9Tfbrs9OMGQ+JEMguCIKYpYO8BQGo12NH3SWnBwsN1V4DMVTMI4gKbyI6/G24NpquGRyGqSLslKSc0FmTcYOf+Z/9Qc+w5/+t7dHW8QvNoFmR73+BIo1p6laaGoxMBRCDJTKGrEhGmopT9aI3BszHLPnHtFfM3BARyjIEIMLM1fy7bHOYiBkoNk7Io//uKnPyBCyNwZfGmSoTWjKJnboScwsoHzVjXiCsznkN14wKTh2Nis82llBfC3c+2dPvspKefaREkuFNOqyHi5ZxXKdcIxDjQXtrYOW2lbt75QOmrFd7nKV3a4UI0BdSbwO416HCAD2SGYDt6bc6PXMbS1zk3GA23dGZd8vRv4hEm5H+OSDgFpIFoz3kWgFhd2bFAFldiMCrHOeYusemlopUznERIAImUhvN9DkXQDHQPeM0R0KsfSIjfwSL4zLd8wgix+/vQ9CrZ6JIriASgvi0tmu4W4avH9Cms7sx0Bp/YxciNwt+iSS3CHuVIBnW+384OLVxHyILLzaXocbZ+P45gbH3z1K8lvzFhoAhMksOvSATODs/PdVetjejcWOIVzseXvKTVf8yNg+tkBSxPaSY7LSUY9ki0jegZiOYLs+Bh0342pAnAYQ1zgpGhVp9wK1clbQRMYzAhU5dP7Et4PxsOV2NzRXcrsMUwrtkoC118bJx/hqm9VE5Fck85rcfQ1dzpbyqpU0yKGqlrEkNP2XUzBBmTj3htNH0A0CQuO9y436kugDSEaahQrXtWKJ3tL6FJKVi/FfZPspm4nQc8Z9a5/Mb1OLKZWdM3SX4Xt8PSRuREFrIzPeV8bYPYFuADY2hw0CnjTTcgb4cinodSOjwhGf8E9iBmQZOIz07kWukqoODzsvIlI0N88+B8cg6eUg6N5nV4wyp2TifMvDCdFEEgJbvuHX5vOtmMiBsnvyamNA3UfTsnp2AGi5pTTTgsjCqetKaGZYhizdidiqeUKUN3aATGM0IgIC1eV5FF6VwfhMUkscqZHgervYyyYjO8TX3PaQnhvJkioUsY0MerEyfi2kcVkwvZimFTUaX3y3ZSpp6kLv7lVfsdD+q9l3amVPbRvD6KR8bUiL4rBN9+KiUoiCypuCYrJ+xhTXeQ7OnhXJsv5P/rnKWKHZZ8iE5OL/yhzRR4yGmHdTKIrPdn8cx/6z3881dBlitfVs3OLmRTl1hrqWXPNWlV2M8j7kh5E/PAQKAHvb9ITYSYTGr7DyYzVyKqJyqUM0gon8VFOS4E6h4oU+/7f/ce//dBM401dN86b6Fm0OyxUlCyLCDmcF2kO6oobFKcxJWAFWrogZo8aBD565vTcBmnroKBdRSv1zFCPLv7b36j6DQa84rRqKpHxzf/z+2kyhWwqowLee6EsHURnthdNpp0mcOH9pggOZIfKkWxnsAF++I8KnoJYjGm3teh/okzB7kWmFClCA8i0ggVCoQGFcsE+ol8aFgobbeAytjltxHrmIHuW4pP751Bi/viLn/4Lk0Y+I5z9D5gGTlvq6ds8s8shW8tEO3kShwmHmNN8zqzniMSGzv0iOEDLpJVluMT20G1jZxBl0yxR+hgRVzIRFTpeI0nz0O5uem6N6UAv9faM+bnenobscGZujr6ute5BlcUzJ/uETMxxIF46/Z3TZ7ZUW7yZwABC3PAY3mxeAQwHQhqkygNy7X8WXZMH2ZXtiIiBxpwgG1HHuDCjHGdtjla6SvEERaDufssbWBWet2W96gw37Cn06KQ5qGaNH/fd8gJa49gMONbylHtueVreknVR9ZO3QY+pbhxtMoc0mXhzvZFv44jI1SzpRu8H3wvDVi1oljaP7a/4429vjgUcqKrosyuerJvTpt/ByIHuwJsmr2/T6/sG/1sAXtmMCbu4m6L6npn7fgpqJYi6bmDmux2iV7aBoLTLM5MgLB5RiNID7VYkmn3lU8IlohtTNa32yT/1bZAQhpHTfgH//SvP/mIkXOSigfa3DMuBcInlPS5aah/C/hcDFZCEZngFX/eAMwOVVbZcvCNXruKz1kC8Nc0i8Hd0A9T77ycNaMJVWFp13/z9/eSJOGp9D36FQsbXXxg8JZmaYPKIdSKIk5EAwnTEuj5NwqSedNGZUt+6+c2H/4MMNW3y0zaPhyfdRk68HbZDEw2F06Io/+XlzIgRWb/EOHKewDaBAf+nkC9p633CUtFJdhiKDDlMUrTeLGvFJBtOVg1RTTr9ja1WQzfKClo1M0/THdWubgqyZpBi9zmYnpZVLNfAMX0DybZZ+Q1i+0B9eM3istW2NyLBihlIcUzp7j1SOk3u54QGb/dzNvLoyUdP3wKKeAuJg+Cjj9Ce+fUnX3924usvMnIzpGDSyf87iVM0ObsQgj/BEVd1B15MCkkV7/OKItBqGridLhyEJzka9hiAI6lR8S5My1NQi2sNmjovkSpDDp1gggCqzmCh0W+pg42rBNJL9OSzqAfYZtBqe6F24HcnaAZ/Jp0g2b1CmZLeAK/8MnSDrpqbo5p5oaVDB6LyoKYu2BCahHIqy209cWO4HfcegdJFb7VVO4OB8isimtI9Ceb8gl8/ybs7eBOp18yxd0QQWQ4QzJ/+SzYvyTGKSXeJovv6sbGu8E79BovkcskuDVQuvvpbdkU+0X+y1UHNhGtuGELVIKwYNI643QFGUIOuk0Pzv2YdC4rNOP0lYV6mNePAgXaTlMc5RxhUNEjMUsbUUwc6f+kODCJObdn0yV2lVdUQlpi7B8ioMHKRj+DF5rwK4e2wLF36d64eq1yNQuqew9U1tXSvMOUN9rO6G2j2uCYzvM3Pn3GkWzU7QsjvKOpPLW1zzDKn0se36i7+zxxPk/grJZdmnO+k+yrkzyd2ue/s911EfSwNi+iJhbl4TdCkMrxqhgzFgAuuSK7Voo+57FmYrPIl9BVIdSKCsto4Et7lfgbyF/I1yE7vezTHBSQ86bnATU+Zl/h1+NbMIz+b37z/T8nWcMFwDZbCkXR1TGcp7gGizgaK8cfMyaCn4L/ApCbzyEmFFD7WqOU92ftuq52UIEpYpBK0vOcu7v7xFz98YExo9HnIuaHOHMJk/iXdyjJOG1yWpKMGFvb5HTXUCEunmnmkPA8ef/SEjP91+LI0UZsTxImjscmtQZxLEjfem2q3G2Llu94wpuQ+QmtlqO89963+zd8/NCKNcGYsueuEqxJsK3B1fOzHLC/X0bfVXu1IDp7/v9lAz42Gu0PFR1Eh4aNeLQT9nQGBp9MHLh/nI1r2RjThbD69nSVDn9aulX1tEbemyYskFEYwbfbH+Syszziyn/8T8+SZcnATblFiw1MtquRg8mcYHLuemXpsE69z4mv3EJOL4AXcId21fPpnWjs9B3auwWXdBiUbknlWdLze+JIStPyZxvb4KGNLuzWLD0y90g1N0H/6ceVKybEVJa8MS3B8aDgozDyLmT/vs6w6qsVc48EuhaZ5tQrdUkxmwdu91sDgf6diwPBCnmOpq/uX6FcQsdLtlEsbvMOm3Blf5ripOVLPctzKZPc22bz/J+lryoVOdv8idlJpsvwzzOcxzEphK1AWx9arG2KO80/wl+SM+TYTPe9zJ00GYPunGsXe1J0k/BDhUPwn6FPC9dqkdU+5a5t8t9/r+x2/gJA4vTwe+wmm+//7rSTTu5JInQzpyn2Z2PCmg9nQi/W+51UGzVYPk+pUMI/dAAMYDw5Oey8ieqSpOZMo2dShrq+Ea/RhFnICszHdB9byGHPeiNjFQ5YAB0NFPoq4AmexanJIURAWVF8cDAFBZ3+0WgnH9zxwC36boDAwBbJi0kjg0s1T2uQq/oaJXrNm6UJ0ZSKW+5dPu6e2zmT5Vb6MxlvNUZf54iqBP+2z8sviJkORPrZPnoaYd5u77OacBXIFA/VvkOyhhuHUCRone8xilWtarHIvFqocI2oR8sdjnaXtSTcR0ZmMnB+vej9BIiJui4wK09L9VqGZaIWhA8gkP42JlRDsr3qAovSau47ojZypX1+zNItZfeCovjrvSbIKZlQi0X21fuBcqvuI37vx7bNungQ6AuI3T8N/L5obmjUg7zW03+ZxUncm+KjeQ/sAi4DaBG14bNC5/x6erJvq2UHRoHYeCRSvwHutdvvogV74dk4X47i7wLrgDxE3+ufpBKx0sJrZOc32QhfsZMCrhmY8y0RJfT/N0TYLzgXvowz+l7m6GrDuaJi7o/q/NwS/arARM3QsPt4btfQ4bKvTdAhlC2st3XFEEq6S7wxbg2ZpZuYY2pNqGzDom/hgCT6s9uD9i/Dh1VqtdBv/DrZLr/sOivNrPa/actvwIhfvB+xBhfynKOTo+Lz97TNlAhWDz680NGNiMr3R8JmNJifEkvJGCrRSUtrBpHAGmSCVh2OaeR2SwjgIceHD0tNRWgKSvQ0GWECiBF4GJSIgvQVKL8slTOBxzFHa2dzqAhEMx5vCT5sD4A7t7Kus5EDUyM1oeF6eihyXAo1DehmfTrGxwMAjCWfTHItBTGY5/tAcEGI8ULJZmst3kJEmxjux+Xz05HfYHpVhk1k8sutjItKCYEDaLUsYN5wsq3CHdqfSPZutz/bcRqvrskPlaB6pmDyOBZPJzIGN4JXyvDaWNQbvrgzhD782nr4Nc8kjrVjmvzpI0NY+25GlSnfsCKQ92LUqtB/s3/mDA/xT6R4cENa8uwXMr9AI7FfKBKsnQeEdkQrgLG75wqX15cL8xtl5ShGBieFNTC2Jt8v0DXP12Agyp7IynjkC2jO7BENqLk5zVooZAoaOXW4EkyM61v4sflvi0E2OkpARQ3PGSxgulC3mahGCmKzT2fQYi5huMySS01lY5xg9HdfoieWPxjD1fzfH8QgSIP86TEACEJh4nDNprxoCwl6Nev5N8m/VuVZmbMQRwiE0w16O2Ih8LqsRThviiiIfjFr3oZqddlIt7Zb6VAnAy4uPlsgLUxhgOHXZzVrWdkpY+nY0LD0e/q2HpOdvNMdYbzJknWjctaoMp9lrJ6xeqp04vnApQZPZg5scMRn9yVassnFUxGh7kUIRNx29bCKuY9Zd00Tlw+/lhNL4w6/IvwaRmTHrjmUjE1+Cnt723L5lM0E9e/tDEQy3oshStJEYTz58+jbQ+NtE7k/fgqL/JkSziQAwu8jlBBTFA3jnI+YQ9NaTT5g6Rgbzt4h1vJNyXa1qQTA33SKi14LwAF0HERIfQIteUIHHi5vYd1EfwkGGv5EtIz7fSXHzHX+rQAadlNh5skU9ZI0cSR8UjVDIJT+XtbMmf1B5LhvfD/8xU4b/CPdkzM483Sj4Z5AWdswk1JKpAUiMQedPhkGSjTQy1cCTkDTyLEki/sXzx7tIR08JB8CPpErKkVRJPZIyhpeIbJKCZBIu0C7o5gh+Uwo8mlZ/4IlpBcGLQOBLe6rK7nevBqPSOfh7jXCc8WC7kY5yTJZFhs9cutJ0tnYGo9JNz0ET9lqz1SulWrNlD++IHq5oHdy/TD1c5imKx0l9FUnIS4GDSPGlltMAFj10R4OSVxQfDw7WN44yKgTNO+/2A+oNZXNzCAO6NOg6rW4LQecvwKlXCvpyKCMxlIY+2SxNj9pdjv/V8galChZ1g1LTQRiZ80gVpVVuIZHts13AiQbR2y3rSgpxXYkSF2GosHfWUt5Z44AtNgdofZNvkN2as89zZ5TeVGeeT7cY5K24IUehC7Gcczxt0dghjGDQCVSkdysNFQ3zb1hvpv2I3o0pEPGYhGciHfKZvsZ7xDO+6NLIXjOchusZ04ADFQmappoQSUFDaClO1bzt88FeyQXKgu6utRpdt1267glKFamYiFjFniHzQX2nS451xnbTskkx9ZScE0G6ckopngqYUscDjb4bFPpwQPIUKgFLrea3vaLX7yOa6/qrK29cemPD+Fbia0YX/qujp7uW0JAajSbL7eAyejVad3Pe1FPMdMt5kuJiVypr11evvbZ0bfXK9cWU56X98UK3ZgVoaYBa+hbfxkD1W9DpUheRRoOkhL6ijw52kKHfeuUginBb5HMBZwpUhzkvFDVf5GWUeLdtxGJlH9Onlxo26m4Lke+dwJbTMvUydmdnrW6R7JqXrq8sl//bKyAoG2Q1KJvCanDydG9vARPGFggfvsQiGRbQaFCou51We1S67jb9jusM3O6gMPD6rfoCMzF8a+vl+ep8lZUdSgvDQhvWqiDiJorfMc9GklA85kB1uhD9ylb/7Ct46okeUrUDNDAWz8BEas28BM3wTnDQk7P/7ThP4RNgGgqZzjOwj5uvnMB6z75yAsZ/FrTScMdUcMewCfYoCRFuoQUl0YFbG1GiKaRU7gppLmZkTbiwunKe4TosQ2lcQQ9OjG6VJY0vYQIJBQHYc16cizw4hdl3ud809s4Z1cbQpf/jfwF0iy7bQ7oDAA==',
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
