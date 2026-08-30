<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 13.3.42
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
			'takeover_front_page'         => true, // v13.3.32: صفحه نخست هم ویترین React
			'enable_native_wp_template'   => true, // قالب بومی فقط برای برگهٔ پشتیبان (نه ویترین اصلی React)
			'native_fallback_page_id'     => 0, // برگه پشتیبان فروشگاه (جدا از فروشگاه اصلی)
			'enable_404_shop_redirect'    => true, // ریدایرکت 404 به صفحه پشتیبان
			'set_wc_shop_to_fallback'     => false, // v13.3.32: پیش‌فرض خاموش — پشتیبان جای ویترین React را نگیرد
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

		/* v13.3.32: ویترین React روی دامنهٔ اصلی (صفحهٔ خانه) — نه فقط /shop یا ?amphp_shop=1 */
		if ( ! empty( $opts['enable_shop_takeover'] ) ) {
			$opts['takeover_front_page'] = true;
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
			self::ensure_fallback_shop_page( false );
			self::maybe_detach_fallback_from_primary_shop();
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
		add_action( 'admin_init', array( __CLASS__, 'maybe_detach_fallback_from_primary_shop' ), 25 );
		add_action( 'admin_head', array( __CLASS__, 'amphp_admin_sticky_assets' ) );
		add_action( 'template_redirect', array( __CLASS__, 'maybe_detach_fallback_from_primary_shop' ), 1 );
		add_action( 'wp_footer', array( __CLASS__, 'maybe_print_native_support_chat' ), 50 );
		add_action( 'wp_ajax_scraper_ensure_fallback_shop', array( __CLASS__, 'ajax_ensure_fallback_shop' ) );
		add_action( 'wp_ajax_scraper_autosave_settings', array( __CLASS__, 'ajax_autosave_settings' ) );

		// Serve storefront JS/CSS via PHP (works even if static files blocked or not yet synced)
		add_action( 'init', array( __CLASS__, 'maybe_serve_storefront_asset' ), 0 );

		// Complete suppression of legacy WordPress theme header & menu when custom storefront is enabled
		add_action( 'wp_head', array( __CLASS__, 'inject_custom_header_suppression_css' ), 1 );

		// AJAX actions for syncing to WooCommerce
		add_action( 'wp_ajax_scraper_sync_to_woo', array( __CLASS__, 'ajax_sync_to_woo' ) );
		add_action( 'wp_ajax_scraper_direct_sync_progress', array( __CLASS__, 'ajax_direct_sync_progress' ) );
		add_action( 'wp_ajax_scraper_test_woo_direct', array( __CLASS__, 'ajax_test_woo_direct' ) );
		add_action( 'wp_ajax_scraper_enable_woo_direct', array( __CLASS__, 'ajax_enable_woo_direct' ) );
		/* v13.3.33 / v10.115: جدول مقایسه نظیر‌به‌نظیر در پنل افزونه */
		add_action( 'wp_ajax_scraper_sync_matrix_start', array( __CLASS__, 'ajax_sync_matrix_start' ) );
		add_action( 'wp_ajax_scraper_sync_matrix_status', array( __CLASS__, 'ajax_sync_matrix_status' ) );
		add_action( 'wp_ajax_scraper_sync_matrix', array( __CLASS__, 'ajax_sync_matrix' ) );
		add_action( 'wp_ajax_scraper_sync_matrix_fix_start', array( __CLASS__, 'ajax_sync_matrix_fix_start' ) );
		add_action( 'wp_ajax_scraper_sync_matrix_fix_status', array( __CLASS__, 'ajax_sync_matrix_fix_status' ) );
		add_action( 'wp_ajax_scraper_sync_matrix_fix_stop', array( __CLASS__, 'ajax_sync_matrix_fix_stop' ) );

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

	/**
	 * v13.3.32: nonce اتصال مستقیم — هم scraper_woo_bridge و هم scraper_shop_admin_nonce
	 * (قبلاً JS یکی و PHP دیگری می‌فرستاد → 403 body "-1").
	 *
	 * @return bool
	 */
	public static function verify_woo_bridge_nonce() {
		$nonce = isset( $_REQUEST['nonce'] ) ? (string) wp_unslash( $_REQUEST['nonce'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( $nonce === '' && isset( $_REQUEST['_ajax_nonce'] ) ) {
			$nonce = (string) wp_unslash( $_REQUEST['_ajax_nonce'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		if ( $nonce === '' ) {
			return false;
		}
		if ( wp_verify_nonce( $nonce, 'scraper_woo_bridge' ) ) {
			return true;
		}
		if ( wp_verify_nonce( $nonce, 'scraper_shop_admin_nonce' ) ) {
			return true;
		}
		return false;
	}

	public static function ajax_test_woo_direct() {
		if ( ! self::verify_woo_bridge_nonce() ) {
			wp_send_json_error(
				array(
					'message' => 'نشست امنیتی منقضی شده — صفحه را رفرش کنید (Ctrl+F5).',
					'code'    => 'bad_nonce',
				),
				403
			);
		}
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
		if ( ! self::verify_woo_bridge_nonce() ) {
			wp_send_json_error(
				array(
					'message' => 'نشست امنیتی منقضی شده — صفحه را رفرش کنید (Ctrl+F5).',
					'code'    => 'bad_nonce',
				),
				403
			);
		}
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
	/**
	 * v13.3.33: Ensure scraper4 helpers (matrix*) are loaded without HTML render.
	 *
	 * @return bool
	 */
	public static function load_scraper_matrix_engine() {
		static $ok = null;
		if ( null !== $ok ) {
			return $ok;
		}
		if ( function_exists( 'matrixBuild' ) && function_exists( 'matrixQueryPage' ) ) {
			$ok = true;
			return true;
		}
		$scraper_file = plugin_dir_path( __FILE__ ) . 'scraper4.php';
		if ( ! file_exists( $scraper_file ) ) {
			$ok = false;
			return false;
		}
		if ( ! defined( 'SCRAPER4_NO_RENDER' ) ) {
			define( 'SCRAPER4_NO_RENDER', true );
		}
		$ob = ob_get_level();
		@ob_start();
		try {
			require_once $scraper_file;
			$ok = function_exists( 'matrixBuild' ) && function_exists( 'matrixQueryPage' );
		} catch ( \Throwable $e ) {
			error_log( 'matrix engine load: ' . $e->getMessage() );
			$ok = false;
		}
		while ( ob_get_level() > $ob ) {
			@ob_end_clean();
		}
		return (bool) $ok;
	}

	/**
	 * v13.3.33: شروع ساخت جدول مقایسه روی سرور (پس‌زمینه).
	 */
	public static function ajax_sync_matrix_start() {
		if ( ! self::verify_woo_bridge_nonce() && ! check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'نشست امنیتی منقضی — Ctrl+F5' ), 403 );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز' ), 403 );
		}
		if ( ! self::load_scraper_matrix_engine() ) {
			wp_send_json_error( array( 'message' => 'scraper4.php یا توابع matrix در دسترس نیست' ) );
		}
		@set_time_limit( 0 );
		@ignore_user_abort( true );
		$opts = array(
			'profile'    => isset( $_REQUEST['profile'] ) ? sanitize_text_field( wp_unslash( (string) $_REQUEST['profile'] ) ) : 'all',
			'source'     => 'wp_admin',
			'background' => false,
		);
		$lock = defined( 'SYNC_MATRIX_LOCK_FILE' ) ? SYNC_MATRIX_LOCK_FILE : ( plugin_dir_path( __FILE__ ) . 'sync_matrix.lock' );
		$fp   = @fopen( $lock, 'c' );
		if ( ! $fp || ! flock( $fp, LOCK_EX | LOCK_NB ) ) {
			if ( $fp ) {
				@fclose( $fp );
			}
			wp_send_json_success(
				array(
					'ok'      => false,
					'error'   => 'یک ساخت جدول در حال اجراست',
					'running' => true,
				)
			);
		}
		if ( function_exists( 'matrixProgress' ) ) {
			matrixProgress(
				array(
					'running'    => true,
					'done'       => false,
					'error'      => '',
					'pct'        => 1,
					'phase'      => 'start',
					'started_at' => time(),
					'source'     => 'wp_admin',
					'log_add'    => array( '🚀 ساخت جدول از پنل افزونه وردپرس…' ),
				)
			);
		}
		/* پاسخ زود، ادامه در shutdown تا مرورگر timeout نکند */
		$payload = wp_json_encode(
			array(
				'success' => true,
				'data'    => array(
					'ok'      => true,
					'started' => true,
					'message' => 'ساخت روی سرور شروع شد (مسیر WC مستقیم در صورت امکان)',
					'running' => true,
				),
			)
		);
		if ( ! headers_sent() ) {
			header( 'Content-Type: application/json; charset=UTF-8' );
			header( 'Connection: close' );
			header( 'Content-Length: ' . strlen( $payload ) );
		}
		echo $payload;
		if ( function_exists( 'fastcgi_finish_request' ) ) {
			@fastcgi_finish_request();
		} else {
			@ob_end_flush();
			@flush();
		}
		register_shutdown_function(
			function () use ( $fp, $lock, $opts ) {
				try {
					if ( function_exists( 'matrixBuild' ) ) {
						matrixBuild( $opts );
					}
				} catch ( \Throwable $e ) {
					if ( function_exists( 'matrixProgress' ) ) {
						matrixProgress(
							array(
								'running' => false,
								'done'    => true,
								'error'   => $e->getMessage(),
								'pct'     => 100,
								'log_add' => array( '❌ ' . $e->getMessage() ),
							)
						);
					}
				}
				@flock( $fp, LOCK_UN );
				@fclose( $fp );
				@unlink( $lock );
			}
		);
		exit;
	}

	/**
	 * v13.3.33: وضعیت جاب جدول مقایسه.
	 */
	public static function ajax_sync_matrix_status() {
		if ( ! self::verify_woo_bridge_nonce() && ! check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'نشست امنیتی منقضی — Ctrl+F5' ), 403 );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز' ), 403 );
		}
		if ( ! self::load_scraper_matrix_engine() ) {
			wp_send_json_error( array( 'message' => 'scraper4 در دسترس نیست' ) );
		}
		$prog = function_exists( 'matrixProgressRead' ) ? matrixProgressRead() : array();
		$fix  = function_exists( 'matrixFixProgressRead' ) ? matrixFixProgressRead() : array();
		$has  = defined( 'SYNC_MATRIX_RESULT_FILE' ) && is_file( SYNC_MATRIX_RESULT_FILE );
		$age  = $has ? ( time() - (int) @filemtime( SYNC_MATRIX_RESULT_FILE ) ) : -1;
		$rows = 0;
		if ( $has && function_exists( 'matrixResultLoad' ) ) {
			$d    = matrixResultLoad();
			$rows = (int) ( $d['row_count'] ?? 0 );
		}
		$direct = function_exists( 'wooDirectAvailable' ) && wooDirectAvailable();
		$any_run = ! empty( $prog['running'] ) || ! empty( $fix['running'] );
		$use_fix = ! empty( $fix['running'] ) || ( ! empty( $fix['done'] ) && empty( $prog['running'] ) );
		wp_send_json_success(
			array(
				'ok'             => true,
				'running'        => $any_run,
				'build_running'  => ! empty( $prog['running'] ),
				'fix_running'    => ! empty( $fix['running'] ),
				'done'           => ! empty( $prog['done'] ) || ! empty( $fix['done'] ),
				'progress'       => $use_fix ? $fix : $prog,
				'build_progress' => $prog,
				'fix_progress'   => $fix,
				'job'            => ! empty( $fix['running'] ) ? 'fix' : ( ! empty( $prog['running'] ) ? 'build' : 'idle' ),
				'has_result'     => $has,
				'result_age_sec' => $age,
				'result_rows'    => $rows,
				'woo_direct'     => (bool) $direct,
			)
		);
	}

	/**
	 * v13.3.33: خواندن صفحه‌بندی‌شدهٔ نتیجهٔ جدول مقایسه.
	 */
	public static function ajax_sync_matrix() {
		if ( ! self::verify_woo_bridge_nonce() && ! check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'نشست امنیتی منقضی — Ctrl+F5' ), 403 );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز' ), 403 );
		}
		if ( ! self::load_scraper_matrix_engine() || ! function_exists( 'matrixQueryPage' ) ) {
			wp_send_json_error( array( 'message' => 'scraper4 / matrixQueryPage نیست' ) );
		}
		$opts = array(
			'page'          => max( 1, (int) ( $_REQUEST['page'] ?? 1 ) ),
			'per_page'      => max( 10, min( 200, (int) ( $_REQUEST['per_page'] ?? 50 ) ) ),
			'q'             => isset( $_REQUEST['q'] ) ? sanitize_text_field( wp_unslash( (string) $_REQUEST['q'] ) ) : '',
			'only_dup'      => ! empty( $_REQUEST['only_dup'] ),
			'only_mismatch' => ! empty( $_REQUEST['only_mismatch'] ),
			'only_missing'  => ! empty( $_REQUEST['only_missing'] ),
		);
		$out = matrixQueryPage( $opts );
		if ( ! is_array( $out ) ) {
			$out = array( 'ok' => false, 'error' => 'پاسخ نامعتبر' );
		}
		/* progress overlay */
		if ( function_exists( 'matrixProgressRead' ) ) {
			$out['progress'] = matrixProgressRead();
			$out['running']  = ! empty( $out['progress']['running'] );
		}
		$out['woo_direct'] = function_exists( 'wooDirectAvailable' ) && wooDirectAvailable();
		wp_send_json_success( $out );
	}


	/**
	 * v13.3.38 / v10.121: مغایرت کامل (قیمت+ارسال+حذف+گزارش) از جدول مقایسه.
	 */
	public static function ajax_sync_matrix_fix_start() {
		if ( ! self::verify_woo_bridge_nonce() && ! check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'نشست امنیتی منقضی — Ctrl+F5' ), 403 );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز' ), 403 );
		}
		if ( ! self::load_scraper_matrix_engine() || ! function_exists( 'matrixFixRun' ) ) {
			wp_send_json_error( array( 'message' => 'موتور matrixFix در scraper4 نیست' ) );
		}
		@set_time_limit( 0 );
		@ignore_user_abort( true );
		$opts = array(
			'scope'    => isset( $_REQUEST['scope'] ) ? sanitize_key( wp_unslash( (string) $_REQUEST['scope'] ) ) : 'all',
			'delay_ms' => isset( $_REQUEST['delay_ms'] ) ? (int) $_REQUEST['delay_ms'] : 180,
			'source'   => 'wp_admin',
		);
		if ( ! in_array( $opts['scope'], array( 'all', 'woo', 'bsl' ), true ) ) {
			$opts['scope'] = 'all';
		}
		if ( ! defined( 'SYNC_MATRIX_RESULT_FILE' ) || ! is_file( SYNC_MATRIX_RESULT_FILE ) ) {
			wp_send_json_error( array( 'message' => 'اول جدول مقایسه را بسازید' ) );
		}
		$lock = defined( 'SYNC_MATRIX_FIX_LOCK_FILE' ) ? SYNC_MATRIX_FIX_LOCK_FILE : ( plugin_dir_path( __FILE__ ) . 'sync_matrix_fix.lock' );
		$fp   = @fopen( $lock, 'c' );
		if ( ! $fp || ! flock( $fp, LOCK_EX | LOCK_NB ) ) {
			if ( $fp ) {
				@fclose( $fp );
			}
			wp_send_json_success( array( 'ok' => false, 'error' => 'یک اصلاح در حال اجراست', 'running' => true ) );
		}
		if ( function_exists( 'matrixFixProgress' ) ) {
			matrixFixProgress(
				array(
					'running'    => true,
					'done'       => false,
					'error'      => '',
					'pct'        => 1,
					'phase'      => 'queued',
					'started_at' => time(),
					'source'     => 'wp_admin',
					'job'        => 'fix',
					'log'        => array(),
					'live'       => array(),
					'log_add'    => array( '🚀 اصلاح مغایرت از پنل افزونه…' ),
				)
			);
		}
		$payload = wp_json_encode(
			array(
				'success' => true,
				'data'    => array(
					'ok'      => true,
					'started' => true,
					'running' => true,
					'message' => 'اصلاح روی سرور شروع شد',
				),
			)
		);
		if ( ! headers_sent() ) {
			header( 'Content-Type: application/json; charset=UTF-8' );
			header( 'Connection: close' );
			header( 'Content-Length: ' . strlen( $payload ) );
		}
		echo $payload;
		if ( function_exists( 'fastcgi_finish_request' ) ) {
			@fastcgi_finish_request();
		} else {
			@ob_end_flush();
			@flush();
		}
		register_shutdown_function(
			function () use ( $fp, $lock, $opts ) {
				try {
					if ( function_exists( 'matrixFixRun' ) ) {
						matrixFixRun( $opts );
					}
				} catch ( \Throwable $e ) {
					if ( function_exists( 'matrixFixProgress' ) ) {
						matrixFixProgress(
							array(
								'running' => false,
								'done'    => true,
								'error'   => $e->getMessage(),
								'pct'     => 100,
								'log_add' => array( '❌ ' . $e->getMessage() ),
							)
						);
					}
				}
				@flock( $fp, LOCK_UN );
				@fclose( $fp );
				@unlink( $lock );
			}
		);
		exit;
	}

	/** وضعیت جاب اصلاح */
	public static function ajax_sync_matrix_fix_status() {
		if ( ! self::verify_woo_bridge_nonce() && ! check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'نشست امنیتی منقضی' ), 403 );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز' ), 403 );
		}
		self::load_scraper_matrix_engine();
		$prog = function_exists( 'matrixFixProgressRead' ) ? matrixFixProgressRead() : array();
		wp_send_json_success(
			array(
				'ok'       => true,
				'running'  => ! empty( $prog['running'] ),
				'done'     => ! empty( $prog['done'] ),
				'progress' => $prog,
				'job'      => 'fix',
			)
		);
	}

	/** توقف اصلاح */
	public static function ajax_sync_matrix_fix_stop() {
		if ( ! self::verify_woo_bridge_nonce() && ! check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce', false ) ) {
			wp_send_json_error( array( 'message' => 'نشست امنیتی منقضی' ), 403 );
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز' ), 403 );
		}
		self::load_scraper_matrix_engine();
		$stop = defined( 'SYNC_MATRIX_FIX_STOP_FILE' ) ? SYNC_MATRIX_FIX_STOP_FILE : ( plugin_dir_path( __FILE__ ) . 'sync_matrix_fix_stop.json' );
		@file_put_contents( $stop, wp_json_encode( array( 'at' => time() ) ), LOCK_EX );
		if ( function_exists( 'matrixFixProgress' ) ) {
			matrixFixProgress( array( 'log_add' => array( '⏹ درخواست توقف ثبت شد…' ) ) );
		}
		wp_send_json_success( array( 'ok' => true, 'stopping' => true ) );
	}


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

			// v13.3.32: فوروارد چندرسانه‌ای (عکس/ویدیو/گیف/فایل/لینک)
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
	 * v13.3.32: ارسال یک آیتم رسانه به یک پیام‌رسان.
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
	 * v13.3.32: تشخیص نوع رسانه از URL یا MIME.
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
	 * v13.3.32: ذخیره پیوست آپلود‌شدهٔ چت پشتیبانی و ساخت آیتم رسانه.
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
	 * v13.3.32: استخراج لینک‌های داخل متن پیام مشتری به‌عنوان آیتم رسانه.
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
		$media_items = array(); // v13.3.32
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
		if ( self::is_site_home_request() ) {
			return true;
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
		/* v13.3.32: صفحهٔ خانه همیشه React — قالب native را اینجا بارگذاری نکن */
		$settings = self::get_settings();
		if ( ! empty( $settings['enable_shop_takeover'] ) && self::is_site_home_request() ) {
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
			// v13.3.32: برگهٔ پشتیبان ≠ صفحهٔ فروشگاه اصلی — shop_page_id را روی پشتیبان ننویس
			$settings['native_fallback_page_id'] = $page_id;
			update_option( self::OPTION_NAME, $settings );

			/* v13.3.32: فقط وقتی صریحاً خواسته شده و takeover React خاموش است،
			   فروشگاه ووکامرس را روی پشتیبان بگذار — وگرنه ویترین React می‌میرد. */
			$react_takeover = ! empty( $settings['enable_shop_takeover'] );
			$tpl_now = (string) ( $settings['store_template'] ?? 'digikala' );
			$want_native_primary = ( $tpl_now === 'native-wp' || $tpl_now === 'native' );
			if ( $assign_wc_shop && function_exists( 'wc_get_page_id' ) && ( ! $react_takeover || $want_native_primary ) ) {
				$wc_shop = (int) wc_get_page_id( 'shop' );
				$need_set = ( $wc_shop <= 0 || get_post_status( $wc_shop ) === false || get_post_status( $wc_shop ) === 'trash' );
				if ( $need_set && ! empty( $settings['set_wc_shop_to_fallback'] ) ) {
					update_option( 'woocommerce_shop_page_id', $page_id );
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
	
	/**
	 * v13.3.32: ذخیرهٔ خودکار تنظیمات ادمین (AJAX) بدون رفرش صفحه.
	 */
	public static function ajax_autosave_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => 'دسترسی غیرمجاز' ), 403 );
		}
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );

		// Reuse POST field parsing from full save by simulating the same keys
		if ( empty( $_POST ) || count( $_POST ) < 3 ) {
			wp_send_json_error( array( 'message' => 'داده خالی' ) );
		}

		// Build settings the same way as render_admin_settings_page save block
		// Call internal merge helper
		try {
			$new_settings = self::build_settings_from_request();
			if ( ! is_array( $new_settings ) || ! $new_settings ) {
				wp_send_json_error( array( 'message' => 'ساخت تنظیمات ناموفق' ) );
			}
			update_option( self::OPTION_NAME, $new_settings );
			delete_transient( 'scraper_shop_cached_products' );

			// Light side-effects (no heavy ensure loops)
			if ( ! empty( $new_settings['enable_shop_takeover'] ) ) {
				$_tpl = (string) ( $new_settings['store_template'] ?? 'digikala' );
				if ( $_tpl !== 'native-wp' && $_tpl !== 'native' ) {
					$new_settings['set_wc_shop_to_fallback'] = false;
					update_option( self::OPTION_NAME, $new_settings );
				}
				try {
					self::maybe_detach_fallback_from_primary_shop();
				} catch ( \Throwable $e ) { /* ignore */ }
			}

			wp_send_json_success(
				array(
					'message'   => 'ذخیره شد',
					'saved_at'  => current_time( 'H:i:s' ),
					'version'   => '13.3.32',
					'takeover'  => ! empty( $new_settings['enable_shop_takeover'] ),
					'template'  => (string) ( $new_settings['store_template'] ?? '' ),
					'wc_fb'     => ! empty( $new_settings['set_wc_shop_to_fallback'] ),
				)
			);
		} catch ( \Throwable $e ) {
			wp_send_json_error( array( 'message' => $e->getMessage() ) );
		}
	}

	/**
	 * v13.3.32: استخراج آرایهٔ تنظیمات از درخواست POST (ذخیره دستی و خودکار مشترک).
	 *
	 * @return array
	 */
	public static function build_settings_from_request() {
		$__post_cs = sanitize_key( $_POST['catalog_source'] ?? '' );
		if ( ! in_array( $__post_cs, array( 'scraper', 'woocommerce', 'merge' ), true ) ) {
			$__post_cs = ! empty( $_POST['enable_scraped_products'] ) ? 'scraper' : 'woocommerce';
		}
		$__post_mp = sanitize_key( $_POST['catalog_merge_prefer'] ?? 'scraper' );
		if ( ! in_array( $__post_mp, array( 'scraper', 'woocommerce', 'keep_both' ), true ) ) {
			$__post_mp = 'scraper';
		}

		// Start from current settings so missing fields (other tabs) are preserved on partial autosave
		$cur = self::get_settings();
		$posted = array();
		// Only overwrite keys that appear in POST (autosave sends whole form)
		$map_bool = array(
			'enable_shop_takeover', 'takeover_front_page', 'enable_native_wp_template',
			'auto_create_fallback_page', 'enable_404_shop_redirect', 'set_wc_shop_to_fallback',
			'enable_support_chat', 'enable_custom_checkout', 'checkout_require_login',
			'checkout_show_gateways', 'checkout_show_shipping', 'checkout_show_map',
			'show_top_bar', 'show_features_banner', 'show_animated_stats', 'show_special_badge',
			'sticky_header', 'enable_scraped_products', 'replace_site_header',
			'enable_wp_cron_sync', 'chat_field_name_enable', 'chat_field_name_required',
			'chat_field_phone_enable', 'chat_field_phone_required', 'chat_field_email_enable',
			'chat_field_email_required',
		);
		foreach ( $map_bool as $k ) {
			if ( array_key_exists( $k, $_POST ) ) {
				$v = $_POST[ $k ];
				if ( $k === 'set_wc_shop_to_fallback' ) {
					$posted[ $k ] = ( (string) $v === '1' );
				} else {
					$posted[ $k ] = ! empty( $v ) && (string) $v !== '0';
				}
			}
		}
		$map_text = array(
			'shop_title', 'shop_subtitle', 'contact_phone', 'support_hours', 'accent_color',
			'currency_symbol', 'store_template', 'store_palette', 'shop_title_font',
			'shop_title_custom_font', 'shop_title_font_size', 'shop_title_font_weight',
			'default_column_layout', 'top_bar_notice', 'chat_window_title', 'chat_welcome_message',
			'chat_button_position', 'ai_support_name', 'ai_system_prompt', 'checkout_title',
			'checkout_note', 'checkout_cod_label', 'checkout_success_msg', 'wp_cron_interval',
			'bale_token', 'bale_chat_id', 'telegram_token', 'telegram_chat_id', 'rubika_token',
			'rubika_chat_id', 'catalog_source', 'catalog_merge_prefer',
		);
		foreach ( $map_text as $k ) {
			if ( array_key_exists( $k, $_POST ) ) {
				if ( in_array( $k, array( 'chat_welcome_message', 'ai_system_prompt', 'checkout_note', 'checkout_success_msg' ), true ) ) {
					$posted[ $k ] = sanitize_textarea_field( wp_unslash( (string) $_POST[ $k ] ) );
				} else {
					$posted[ $k ] = sanitize_text_field( wp_unslash( (string) $_POST[ $k ] ) );
				}
			}
		}
		if ( array_key_exists( 'catalog_source', $_POST ) || array_key_exists( 'enable_scraped_products', $_POST ) ) {
			$posted['catalog_source'] = $__post_cs;
			$posted['enable_scraped_products'] = ( 'woocommerce' !== $__post_cs );
		}
		if ( array_key_exists( 'catalog_merge_prefer', $_POST ) ) {
			$posted['catalog_merge_prefer'] = $__post_mp;
		}
		if ( array_key_exists( 'products_per_page', $_POST ) ) {
			$posted['products_per_page'] = max( 1, min( 200, intval( $_POST['products_per_page'] ) ) );
		}
		if ( array_key_exists( 'native_fallback_page_id', $_POST ) ) {
			$posted['native_fallback_page_id'] = intval( $_POST['native_fallback_page_id'] );
		}
		// checkout field toggles
		foreach ( $_POST as $pk => $pv ) {
			$pk = (string) $pk;
			if ( strpos( $pk, 'checkout_field_' ) === 0 ) {
				$posted[ sanitize_key( $pk ) ] = ! empty( $pv ) && (string) $pv !== '0';
			}
		}

		$merged = array_merge( is_array( $cur ) ? $cur : array(), $posted );

		/* v13.3.32: خانهٔ سایت همیشه React وقتی takeover روشن است */
		if ( ! empty( $merged['enable_shop_takeover'] ) ) {
			$merged['takeover_front_page'] = true;
		}

		// Keep accent in sync with palette when palette posted
		if ( ! empty( $merged['store_palette'] ) ) {
			$__pal_map = array(
				'digikala-red' => '#ef394e', 'snapp-green' => '#00d170', 'basalam-coral' => '#ff6b35',
				'torob-red' => '#d32f2f', 'digistyle-rose' => '#e11d48', 'technolife-blue' => '#0284c7',
				'royal-blue' => '#2563eb', 'luxury-purple' => '#7c3aed', 'amber-gold' => '#d97706',
				'persian-turquoise' => '#0d9488', 'midnight-ink' => '#1e293b', 'forest' => '#166534',
				'sunset' => '#ea580c',
			);
			$pal = (string) $merged['store_palette'];
			if ( isset( $__pal_map[ $pal ] ) && empty( $_POST['accent_color'] ) ) {
				$merged['accent_color'] = $__pal_map[ $pal ];
			}
		}
		return $merged;
	}

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


	/**
	 * v13.3.32: اگر فروشگاه ووکامرس اشتباهاً روی برگهٔ «پشتیبان» نشسته و
	 * ویترین React فعال است، ارتباط را قطع کن تا رفرش دوباره native نیاید.
	 */
	public static function maybe_detach_fallback_from_primary_shop() {
		$settings = self::get_settings();
		$tpl = (string) ( $settings['store_template'] ?? 'digikala' );
		// فقط وقتی کاربر عمداً native را به‌عنوان ویترین اصلی خواسته، دست نزن
		if ( $tpl === 'native-wp' || $tpl === 'native' ) {
			return;
		}
		// اگر صریحاً خواسته پشتیبان = فروشگاه WC و takeover خاموش است، دست نزن
		if ( empty( $settings['enable_shop_takeover'] ) && ! empty( $settings['set_wc_shop_to_fallback'] ) ) {
			return;
		}
		$fb_id = intval( $settings['native_fallback_page_id'] ?? get_option( 'scraper_native_fallback_page_id', 0 ) );
		if ( $fb_id <= 0 ) {
			return;
		}

		// throttle: یک‌بار در هر درخواست / هر چند دقیقه روی فرانت
		static $done = false;
		if ( $done ) {
			return;
		}
		$done = true;

		$changed = false;
		$primary_id = 0;

		if ( function_exists( 'wc_get_page_id' ) ) {
			$wc_shop = (int) wc_get_page_id( 'shop' );
			if ( $wc_shop > 0 && $wc_shop === $fb_id ) {
				// بساز / پیدا کن برگهٔ اصلی فروشگاه (جدا از پشتیبان)
				$primary_id = self::ensure_primary_react_shop_page( $settings, $fb_id );
				if ( $primary_id > 0 && $primary_id !== $fb_id ) {
					update_option( 'woocommerce_shop_page_id', $primary_id );
					$changed = true;
				}
				update_option(
					'amphp_detached_fallback_shop',
					array(
						'at'          => time(),
						'fallback_id' => $fb_id,
						'primary_id'  => $primary_id,
						'reason'      => 'v13.3.32_react_primary',
					),
					false
				);
			}
		}

		// shop_page_id افزونه نباید = پشتیبان باشد
		if ( intval( $settings['shop_page_id'] ?? 0 ) === $fb_id ) {
			if ( $primary_id <= 0 ) {
				$primary_id = self::ensure_primary_react_shop_page( $settings, $fb_id );
			}
			$settings['shop_page_id'] = $primary_id > 0 ? $primary_id : 0;
			// force set_wc off so save loops don't re-bind
			if ( ! empty( $settings['set_wc_shop_to_fallback'] ) && ! empty( $settings['enable_shop_takeover'] ) ) {
				$settings['set_wc_shop_to_fallback'] = false;
			}
			update_option( self::OPTION_NAME, $settings );
			$changed = true;
		}
		$sp = intval( get_option( 'scraper_shop_page_id', 0 ) );
		if ( $sp === $fb_id ) {
			if ( $primary_id > 0 ) {
				update_option( 'scraper_shop_page_id', $primary_id, false );
			} else {
				delete_option( 'scraper_shop_page_id' );
			}
			$changed = true;
		}
		if ( $changed && function_exists( 'wc_delete_shop_order_transients' ) ) {
			// no-op friendly
		}
	}

	/**
	 * v13.3.32: برگهٔ اصلی فروشگاه برای takeover React (جدا از «— پشتیبان»).
	 *
	 * @param array $settings
	 * @param int   $fallback_id
	 * @return int page id
	 */
	public static function ensure_primary_react_shop_page( $settings, $fallback_id = 0 ) {
		$fallback_id = (int) $fallback_id;
		$title = trim( (string) ( $settings['shop_title'] ?? '' ) );
		if ( $title === '' ) {
			$title = function_exists( 'get_bloginfo' ) ? (string) get_bloginfo( 'name' ) : 'فروشگاه';
		}
		// نگذار عنوان «… — پشتیبان» باشد
		$title = preg_replace( '/\s*[—\-]\s*پشتیبان\s*$/u', '', $title );
		if ( $title === '' ) {
			$title = 'فروشگاه';
		}

		$existing = intval( $settings['shop_page_id'] ?? 0 );
		if ( $existing > 0 && $existing !== $fallback_id ) {
			$p = get_post( $existing );
			if ( $p && $p->post_type === 'page' && $p->post_status !== 'trash' ) {
				// مطمئن شو قالب native روی primary نیست
				$tpl = get_page_template_slug( $existing );
				if ( $tpl === 'templates/native-shop.php' || $tpl === 'native-shop.php' ) {
					update_post_meta( $existing, '_wp_page_template', 'default' );
				}
				delete_post_meta( $existing, '_amphp_native_fallback_shop' );
				return $existing;
			}
		}

		// by meta
		$found = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => 1,
				'meta_key'       => '_amphp_primary_react_shop',
				'meta_value'     => '1',
				'fields'         => 'ids',
			)
		);
		if ( ! empty( $found ) ) {
			$pid = (int) $found[0];
			if ( $pid !== $fallback_id ) {
				return $pid;
			}
		}

		$by_path = get_page_by_path( 'amphp-shop' );
		if ( $by_path && ! is_wp_error( $by_path ) && (int) $by_path->ID !== $fallback_id ) {
			update_post_meta( (int) $by_path->ID, '_amphp_primary_react_shop', '1' );
			update_post_meta( (int) $by_path->ID, '_wp_page_template', 'default' );
			return (int) $by_path->ID;
		}

		// create
		$content = "<!-- wp:shortcode -->\n[modern_shop]\n<!-- /wp:shortcode -->\n\n"
			. "<!-- ویترین اصلی React — برگهٔ پشتیبان جداست -->\n";
		$pid = wp_insert_post(
			array(
				'post_title'   => $title,
				'post_name'    => 'amphp-shop',
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id() ?: 1,
			),
			true
		);
		if ( is_wp_error( $pid ) || ! $pid ) {
			return 0;
		}
		$pid = (int) $pid;
		update_post_meta( $pid, '_amphp_primary_react_shop', '1' );
		update_post_meta( $pid, '_wp_page_template', 'default' );
		return $pid;
	}


	/**
	 * v13.3.32: آیا این درخواست باید ویترین React bare (دیجی‌کالا و …) باشد؟
	 */
	
	/**
	 * v13.3.32: نوار/منوی خود پیشخوان وردپرس — فقط دسکتاپ sticky؛ موبایل دست‌نخورده
	 * (position:fixed روی #adminmenuwrap در موبایل باعث پرت شدن همبرگر به چپ می‌شد).
	 */
	public static function amphp_admin_sticky_assets() {
		if ( ! is_admin() ) {
			return;
		}
		$page = isset( $_GET['page'] ) ? sanitize_key( (string) $_GET['page'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$ours = ( $page === 'scraper-auto-shop' || $page === 'scraper-full-dashboard' );
		// CSS سراسری پیشخوان: فقط sticky امن دسکتاپ — بدون دستکاری موبایل
		echo '<style id="amphp-wp-admin-chrome-sticky">
/* دسکتاپ: نوار بالا و منوی کناری چسبان */
@media screen and (min-width: 783px) {
  #wpadminbar{
    position:fixed!important;
    top:0!important;left:0!important;right:0!important;
    width:100%!important;z-index:99999!important;
  }
  #adminmenuback,#adminmenuwrap{
    position:fixed!important;
    top:32px!important;
    bottom:0!important;
    z-index:9990!important;
    height:auto!important;
    overflow-y:auto!important;
    overflow-x:hidden!important;
  }
  #adminmenuback{z-index:9989!important}
  #adminmenu{margin-top:0!important}
}
/* موبایل: منو/همبرگر وردپرس دست‌نخورده — فقط نوار بالا و جلوگیری از اسکرول افقی */
@media screen and (max-width: 782px) {
  #wpadminbar{
    position:fixed!important;
    top:0!important;left:0!important;right:0!important;
    width:100%!important;max-width:100vw!important;
    z-index:99999!important;
  }
  /* عمداً #adminmenuwrap و #adminmenuback را override نمی‌کنیم */
  body.wp-admin #wpwrap{overflow-x:hidden;max-width:100vw}
}
</style>';
		if ( $ours ) {
			echo '<style id="amphp-admin-panel-v27">
/* ——— پنل افزونه v13.3.32: فشرده، بدون اسکرول افقی، موبایل‌دوست ——— */
html.wp-toolbar{overflow-x:hidden!important}
body.wp-admin{overflow-x:hidden!important;max-width:100vw!important}
#wpbody-content{overflow-x:hidden!important;padding-bottom:40px}
.wrap.scraper-admin-dashboard{
  direction:rtl!important;text-align:right!important;
  box-sizing:border-box!important;
  max-width:100%!important;width:100%!important;
  margin:12px 0 24px!important;padding:0 4px!important;
  font-family:Tahoma,Tahoma,ui-sans-serif,system-ui,-apple-system,sans-serif!important;
  font-size:13px!important;line-height:1.55!important;color:#1e293b!important;
  overflow-x:hidden!important;
}
.wrap.scraper-admin-dashboard *,
.wrap.scraper-admin-dashboard *::before,
.wrap.scraper-admin-dashboard *::after{box-sizing:border-box!important}
.wrap.scraper-admin-dashboard img,
.wrap.scraper-admin-dashboard svg,
.wrap.scraper-admin-dashboard table,
.wrap.scraper-admin-dashboard pre{max-width:100%!important}
.wrap.scraper-admin-dashboard h1{font-size:1.25rem!important;font-weight:800!important;line-height:1.35!important;margin:0 0 6px!important}
.wrap.scraper-admin-dashboard h2{font-size:1.1rem!important;font-weight:800!important}
.wrap.scraper-admin-dashboard h3{font-size:1rem!important;font-weight:800!important;margin:0!important}
.wrap.scraper-admin-dashboard h4{font-size:0.95rem!important;font-weight:800!important}
.wrap.scraper-admin-dashboard p{font-size:0.84rem!important;line-height:1.65!important}
.wrap.scraper-admin-dashboard .form-table th{
  font-size:0.84rem!important;font-weight:700!important;color:#334155!important;
  padding:10px 0 6px!important;width:auto!important;
}
.wrap.scraper-admin-dashboard .form-table td{padding:6px 0 12px!important;font-size:0.84rem!important}
.wrap.scraper-admin-dashboard input[type=text],
.wrap.scraper-admin-dashboard input[type=password],
.wrap.scraper-admin-dashboard input[type=number],
.wrap.scraper-admin-dashboard input[type=url],
.wrap.scraper-admin-dashboard input[type=email],
.wrap.scraper-admin-dashboard select,
.wrap.scraper-admin-dashboard textarea{
  font-size:13px!important;max-width:100%!important;
  border-radius:8px!important;
}
.wrap.scraper-admin-dashboard .button,
.wrap.scraper-admin-dashboard .button-primary,
.wrap.scraper-admin-dashboard .button-secondary{
  font-size:12.5px!important;font-weight:700!important;
  border-radius:8px!important;padding:6px 12px!important;height:auto!important;line-height:1.4!important;
}
.wrap.scraper-admin-dashboard .admin-card{
  background:#fff!important;border:1px solid #e2e8f0!important;
  border-radius:12px!important;padding:14px 14px!important;margin-bottom:14px!important;
  box-shadow:0 1px 3px rgba(15,23,42,.04)!important;
  overflow:hidden!important;max-width:100%!important;
}
.wrap.scraper-admin-dashboard .admin-card-header{
  display:flex!important;justify-content:space-between!important;align-items:center!important;
  flex-wrap:wrap!important;gap:8px!important;
  margin-bottom:12px!important;padding-bottom:10px!important;border-bottom:1px solid #f1f5f9!important;
}
.wrap.scraper-admin-dashboard .field-badge{
  font-size:0.72rem!important;font-weight:700!important;padding:3px 8px!important;border-radius:999px!important;
}
.amphp-admin-hero{
  background:linear-gradient(135deg,#0f172a 0%,#1e293b 55%,#1d4ed8 140%)!important;
  color:#fff!important;border-radius:14px!important;
  padding:14px 16px!important;margin-bottom:14px!important;
  display:flex!important;justify-content:space-between!important;align-items:flex-start!important;
  flex-wrap:wrap!important;gap:12px!important;
  box-shadow:0 8px 24px rgba(15,23,42,.18)!important;
  max-width:100%!important;overflow:hidden!important;
}
.amphp-admin-hero h1{color:#fff!important;font-size:1.15rem!important;margin:0 0 4px!important}
.amphp-admin-hero p{color:#cbd5e1!important;margin:0!important;font-size:0.8rem!important;max-width:560px!important}
.amphp-admin-hero .amphp-hero-badge{
  display:inline-flex!important;align-items:center!important;gap:4px!important;
  background:rgba(37,99,235,.95)!important;color:#fff!important;
  font-size:0.72rem!important;font-weight:800!important;
  padding:3px 10px!important;border-radius:999px!important;margin-bottom:8px!important;
}
.amphp-admin-hero .scraper-admin-topbar{
  display:flex!important;flex-wrap:wrap!important;gap:8px!important;align-items:center!important;
}
.amphp-admin-hero .scraper-admin-topbar .button{margin:0!important;white-space:nowrap!important}
.scraper-tab-nav-row{
  display:flex!important;justify-content:space-between!important;align-items:center!important;
  flex-wrap:wrap!important;gap:8px!important;
  border-bottom:1px solid #e2e8f0!important;margin-bottom:12px!important;padding-bottom:10px!important;
  max-width:100%!important;
}
.scraper-tab-nav{
  display:flex!important;flex-wrap:wrap!important;gap:6px!important;flex:1 1 auto!important;
  max-width:100%!important;
}
.scraper-tab-link{
  display:inline-flex!important;align-items:center!important;justify-content:center!important;
  gap:4px!important;padding:7px 10px!important;
  font-size:0.78rem!important;font-weight:700!important;color:#475569!important;
  text-decoration:none!important;border-radius:8px!important;
  border:1px solid #e2e8f0!important;background:#f8fafc!important;
  cursor:pointer!important;line-height:1.3!important;white-space:nowrap!important;
}
.scraper-tab-link:hover{background:#e2e8f0!important;color:#0f172a!important}
.scraper-tab-link.active{
  color:#fff!important;background:#2563eb!important;border-color:#2563eb!important;
  box-shadow:0 2px 8px rgba(37,99,235,.25)!important;
}
.scraper-top-save-btn{
  background:#2563eb!important;border-color:#2563eb!important;color:#fff!important;
  font-weight:800!important;padding:7px 14px!important;border-radius:8px!important;
  font-size:0.8rem!important;cursor:pointer!important;white-space:nowrap!important;flex-shrink:0!important;
}
.scraper-tab-panel{display:none}
.scraper-tab-panel.active{display:block}
.scraper-mobile-tab-bar{display:none;margin-bottom:10px;background:#fff;border:1px solid #e2e8f0;border-radius:10px;padding:10px}
.scraper-mobile-tab-bar label{display:block;font-size:0.78rem;font-weight:800;color:#0f172a;margin-bottom:4px}
.scraper-mobile-tab-bar select{
  width:100%!important;max-width:100%!important;padding:8px 10px!important;
  font-size:14px!important;font-weight:700!important;border:1.5px solid #2563eb!important;border-radius:8px!important;
}
/* جلوگیری از اسکرول افقی داخل ویترین */
.wrap.scraper-admin-dashboard [style*="grid-template-columns"],
.wrap.scraper-admin-dashboard [style*="display:grid"],
.wrap.scraper-admin-dashboard [style*="display:flex"]{
  max-width:100%!important;
}
.wrap.scraper-admin-dashboard .amphp-fluid-grid{
  display:grid!important;
  grid-template-columns:repeat(auto-fit,minmax(min(100%,220px),1fr))!important;
  gap:10px!important;margin-bottom:12px!important;width:100%!important;
}
#amphpAutosavePill{font-size:0.75rem!important;padding:4px 10px!important;border-radius:8px!important}
.scraper-save-bar{max-width:100%!important;box-sizing:border-box!important}

@media screen and (max-width: 782px) {
  .wrap.scraper-admin-dashboard{
    margin:6px 0 20px!important;padding:0!important;font-size:12.5px!important;
  }
  .amphp-admin-hero{padding:12px!important;border-radius:12px!important;margin-bottom:10px!important}
  .amphp-admin-hero h1{font-size:1rem!important}
  .amphp-admin-hero p{font-size:0.75rem!important}
  .amphp-admin-hero .scraper-admin-topbar{width:100%!important}
  .amphp-admin-hero .scraper-admin-topbar .button{
    flex:1 1 auto!important;text-align:center!important;font-size:11.5px!important;padding:8px 10px!important;
  }
  .scraper-mobile-tab-bar{display:block!important}
  .scraper-tab-nav-row{flex-direction:column!important;align-items:stretch!important;gap:8px!important}
  .scraper-tab-nav{
    display:grid!important;grid-template-columns:repeat(2,minmax(0,1fr))!important;
    gap:6px!important;width:100%!important;
  }
  .scraper-tab-link{
    width:100%!important;padding:8px 4px!important;font-size:0.72rem!important;
    white-space:normal!important;text-align:center!important;line-height:1.25!important;
  }
  .scraper-top-save-btn{width:100%!important;text-align:center!important;padding:10px!important}
  .wrap.scraper-admin-dashboard .admin-card{padding:12px!important;border-radius:10px!important;margin-bottom:10px!important}
  .wrap.scraper-admin-dashboard .form-table,
  .wrap.scraper-admin-dashboard .form-table tbody,
  .wrap.scraper-admin-dashboard .form-table tr,
  .wrap.scraper-admin-dashboard .form-table th,
  .wrap.scraper-admin-dashboard .form-table td{
    display:block!important;width:100%!important;max-width:100%!important;
  }
  .wrap.scraper-admin-dashboard .form-table th{padding:8px 0 2px!important}
  .wrap.scraper-admin-dashboard .form-table td{padding:2px 0 10px!important}
  .wrap.scraper-admin-dashboard input[type=text],
  .wrap.scraper-admin-dashboard input[type=password],
  .wrap.scraper-admin-dashboard input[type=number],
  .wrap.scraper-admin-dashboard select,
  .wrap.scraper-admin-dashboard textarea{
    width:100%!important;max-width:100%!important;font-size:16px!important; /* iOS zoom avoid */
    min-height:40px!important;
  }
  .wrap.scraper-admin-dashboard .wp-list-table{
    display:block!important;width:100%!important;overflow-x:auto!important;-webkit-overflow-scrolling:touch!important;
  }
  .scraper-support-desk{flex-direction:column!important;height:auto!important}
  .desk-threads-col{width:100%!important;height:260px!important;border-left:none!important;border-bottom:1px solid #e2e8f0!important}
  .desk-threads-col.mobile-hide{display:none!important}
  .desk-view-col{width:100%!important;height:400px!important}
  #btnDeskBackToList{display:inline-block!important}
  .scraper-save-bar{
    position:static!important;width:100%!important;flex-direction:column!important;
    gap:8px!important;text-align:center!important;padding:12px!important;
  }
  .scraper-save-btn{width:100%!important;display:block!important;padding:11px!important;font-size:0.9rem!important}
  /* کارت‌های عریض تب ویترین */
  .wrap.scraper-admin-dashboard div[style*="minmax(280px"]{grid-template-columns:1fr!important}
  #amphp-wc-shop-fallback-card button{flex:1 1 140px!important;min-width:0!important;height:42px!important;font-size:0.85rem!important}
}
@media screen and (max-width: 480px) {
  .scraper-tab-nav{grid-template-columns:1fr 1fr!important}
  .amphp-admin-hero .amphp-hero-badge{font-size:0.68rem!important}
}
</style>';
		}
	}



	/**
	 * v13.3.32: آیا درخواست فعلی صفحهٔ اصلی دامنه است؟ (/, front page، posts home)
	 * حتی اگر show_on_front=page و page_on_front برگهٔ دیگری باشد.
	 *
	 * @return bool
	 */
	public static function is_site_home_request() {
		if ( is_admin() ) {
			return false;
		}
		// پرچم‌های کوئری اصلی وردپرس (پس از parse_query)
		if ( function_exists( 'is_front_page' ) && is_front_page() ) {
			return true;
		}
		if ( function_exists( 'is_home' ) && is_home() ) {
			// فقط وقتی «نوشته‌ها» همان صفحهٔ نخست‌اند
			if ( (string) get_option( 'show_on_front', 'posts' ) === 'posts' ) {
				return true;
			}
		}
		// برگهٔ ثابتِ صفحهٔ نخست
		$fp = (int) get_option( 'page_on_front', 0 );
		if ( $fp > 0 && function_exists( 'is_page' ) && is_page( $fp ) ) {
			return true;
		}
		// اگر کوئری اصلی قبلاً اجرا شده و front نبود، دیگر URI را حدس نزن
		if ( did_action( 'wp' ) || did_action( 'template_redirect' ) ) {
			return false;
		}
		// قبل از main query: فقط مسیر ریشه بدون آرگومان محتوا
		$uri  = isset( $_SERVER['REQUEST_URI'] ) ? (string) $_SERVER['REQUEST_URI'] : '';
		$path = (string) ( wp_parse_url( $uri, PHP_URL_PATH ) ?: '/' );
		$path = untrailingslashit( $path );
		$home_path = untrailingslashit( (string) ( wp_parse_url( home_url( '/' ), PHP_URL_PATH ) ?: '/' ) );
		if ( $path !== '' && $path !== '/' && $path !== $home_path ) {
			return false;
		}
		$skip = array( 'p', 'page_id', 'pagename', 'name', 'product', 'product_cat', 'post_type', 's', 'rest_route', 'wc-ajax', 'preview', 'amphp_sf' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		foreach ( $skip as $k ) {
			if ( isset( $_GET[ $k ] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return false;
			}
		}
		return true;
	}

	public static function should_render_react_storefront( $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}
		if ( empty( $settings['enable_shop_takeover'] ) ) {
			return false;
		}
		if ( is_admin() || ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) ) {
			return false;
		}
		// /?amphp_shop=1 — لینک مستقیم ویترین React
		if ( isset( $_GET['amphp_shop'] ) && (string) $_GET['amphp_shop'] !== '' && (string) $_GET['amphp_shop'] !== '0' ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		}
		// v13.3.32: صفحهٔ اصلی دامنه (/, front page, blog home) → React bare
		if ( self::is_site_home_request() ) {
			return true;
		}
		if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) {
			return true;
		}
		if ( ! empty( $settings['takeover_front_page'] ) && ( is_front_page() || is_home() ) ) {
			return true;
		}
		$fb_id = intval( $settings['native_fallback_page_id'] ?? get_option( 'scraper_native_fallback_page_id', 0 ) );
		/* اگر صفحهٔ اصلی همان برگهٔ پشتیبان باشد، React روی خانه اولویت دارد (v13.3.32) */
		$__home_react = self::is_site_home_request();
		if ( is_singular( 'page' ) ) {
			$pid = (int) get_queried_object_id();
			if ( $pid > 0 ) {
				// برگهٔ پشتیبان native = نه React — مگر همین برگه صفحهٔ خانه باشد
				if ( ! $__home_react && $fb_id > 0 && $pid === $fb_id ) {
					return false;
				}
				if ( ! $__home_react && get_post_meta( $pid, '_amphp_native_fallback_shop', true ) === '1' ) {
					return false;
				}
				$tpl_slug = get_page_template_slug( $pid );
				if ( ! $__home_react && ( $tpl_slug === 'templates/native-shop.php' || $tpl_slug === 'native-shop.php' ) ) {
					return false;
				}
				if ( get_post_meta( $pid, '_amphp_primary_react_shop', true ) === '1' ) {
					return true;
				}
				$shop_pid = intval( $settings['shop_page_id'] ?? get_option( 'scraper_shop_page_id', 0 ) );
				if ( $shop_pid > 0 && $pid === $shop_pid ) {
					return true;
				}
				if ( function_exists( 'wc_get_page_id' ) ) {
					$wc_shop = (int) wc_get_page_id( 'shop' );
					if ( $wc_shop > 0 && $pid === $wc_shop ) {
						return true;
					}
				}
				$post = get_post( $pid );
				if ( $post ) {
					if ( $post->post_name === 'amphp-shop' ) {
						return true;
					}
					if ( is_string( $post->post_content )
						&& ( has_shortcode( $post->post_content, 'modern_shop' ) || has_shortcode( $post->post_content, 'scraped_shop' ) ) ) {
						return true;
					}
				}
			}
		}
		return false;
	}

	public static function maybe_takeover_shop_template( $template ) {
		$settings = self::get_settings();
		if ( empty( $settings['enable_shop_takeover'] ) ) {
			return $template;
		}
		if ( ! self::should_render_react_storefront( $settings ) ) {
			return $template;
		}
		// ویترین React bare — مگر قالب صریحاً native-wp
		$tpl = (string) ( $settings['store_template'] ?? 'digikala' );
		if ( $tpl === 'native-wp' || $tpl === 'native' ) {
			$path = self::get_native_shop_template_path();
			if ( is_readable( $path ) ) {
				return $path;
			}
			$fb = self::ensure_fallback_shop_page( false );
			if ( ! empty( $fb['url'] ) ) {
				wp_safe_redirect( $fb['url'], 302 );
				exit;
			}
		}
		self::render_standalone_shop_page();
		exit;
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
		header( 'X-AMPHP-Storefront: bare-v13.3.32' );
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
				'version'     => '13.3.32',
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
		<!-- ویترین فروشگاه v13.3.32 -->
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
		$parts = array( '13.3.32' );
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
		// Inline fallback baked at build time (v13.3.32) — single-file deploy.
		$cache = array(
			'storefront.js'  => array(
				'mime' => 'application/javascript; charset=UTF-8',
				'gz'   => 'H4sIAItMk2oC/9y9e3fbuJI4+P98Cpm/rI54DSmS7byosDWJY3fc7UfiV5L29XpoCbIYU6RCUn5rPvtWFd6U7KTv3D1nd/qkLRIA8SgUClWFqsJVlNfejSejyUGZ5XyYZ2kZDqdpv4yztOHfe9OC14oyj/ul11XptZ1Bg/v3OS+neVrj9TpvnZ3xYicbTBN42zv/zvtla5JnZVbeTnhrFBV71+mnPJvwvLxt9aMkaXDmDfgwmial5/d4Sz4HfHYFHXqfhff8ZpLlZRHcz2YsScL7GTuupHIOqd3n//jHf9T+UfvPJO7zFDq7z6N+iSk5PmAvBlPqdWscp63vBWRh7no2uc3ji1FZa/T92mbU5+dZdslqW2m/VYvSQS0ui1o0HMZJHJW8aMnPDkdxUSuyad7ntX424DV4lS0PatN0wPNaOeK1na1DlVwbZlOsLsUMrGJ7a31j92CjBlVzmVzLs6ysDeIc4Jblt7VsCKmmoTLnHDvwHGET5eHB7fg8S1rDLG94YpQ84WOeAijZ/mBBNsIsSiB3b1HuMI8u5Ndbi/LF9J+NYbhQZHNhA3mGw8kh/8Mj+VfxgPKfLcrvA9rxG+zBu4U9zPLrKB+cAYJCkaOFnZwWEwQ35L9flD/m4wzyjhflJdHdLeR9zFReXPI8gpkwGP/RwfgwDNNpkjw8IHrDZPGl0MsI670eZgQNHn7MYF2cfMxOHx74ifef/6nq9E6Z+ioMPdWA1+MBfukT/n8BTI9hPU0BKoPAWo6iA0sdQP70x5RP+WYGCHI0GQCO2uV0/j6fJIDbB+VjBQ54OZ85Y5+zUC7jqCjii5SdZ7jYNDy2U1jBJUv9e0RUnN9JEUIKvsjJDEvxCpNWhOeZeJlSV/MwfXj4ks22U4tKxAUt3fVsPMlSQEdc8k6BQnbV0Cdo0L+Ph435aajXrTQNZJiRJZo5vxzl2XVtI88RC1TFjVar5Qe1MrrksPbTmqgLV2OB2TWYmjg6TyCzzGpiJLUMVmRNg+V6FPdHNTFLT1fR8vyuDZFWZT4amMkQxrp7nl8ByNBMvgUTOSWVei1EUVV71vdQt5ncbxkiwbfMNBXa7ZqCB8W/AwsQ4zeK8KCw2kv5NXSju0H1AAGaImGEIoCYjY3CAYPPoFRcfJrmvIJAS+0u1v1nFr7L8+gWCtEv+6qR+7E9iq0X4X1/mudQDa3LGfsBC+CS3wZLbQZjwZ+zs4In6okoNTxbYPw9U9DBXuSMNrGY8I9l9NNF3JUYifQop/0AYbUUhldZPKi16/VGFlKSz8oWdMDOiUPPW6ZUyPS/ZmJrLVnu1+tLP7LKoBqY3EhO8tOwhD8+AScKo/xiiuS/aCU8vShHzRXsVgQ0ruMnrf4oTgYAhjDt8gR2M8jqvI38e+wufj8VsG1EPhuG7e7wbdQdLi/705Phqan5ZLi8ctq1KpvOoB5iHOTGj30sDAyi0M1hkU/9tsZO7xGNQ6y3+2fPxJoPopxIbMAZTlhM85Uxws4gYWfZdcrzYB1QS0zwbKan7MtA0JRHa2zhD1VbUrUcZ0ZWzcUCUA0AU0QPVvWfCmsXsXYBTbSAPBE2IGRU4zDoKDd1fKadCEEPFNILvcAL2x6DH3hY8WYSGN4zbxn7RtS/8fwkDE6fXzBNJFLTi5P0dCZ2nT+y8Pk/ny8/vzAofFjY8Phplwk98aUHvfSwB4iaQdkqswNgItKLxupL3wylSMQCYbA2xJDiUDXRBeSGZpClGsYpH3gPD5QAXFrCo9RDVOZi5RAeZ+FSB/FW7c1+hsufULa4jsv+qBH79/0IGISCeuIF9JJOx+fAlgRU+hzYgcsupcvhBfJbMxuiEuDC6Pva/oA+nSE+Z74EUwY0MAkTSGA8zLHXXs9recsAzYy1/SBnf2aNxO81UshhckuC8aShmbI/MuY9qz/3/GUP/jAAVUKggg/0LA71xAxnvh8kuiLAs4TWeggInbB0ubGU4FQ8PABTkuETdIpSep4X4EzRi/9I68vcR+IzmRYjqNdnBOgsBEJojS7IlwEFcWRQWlGHCEhC9JZLytKNgDLcxyE/iU67gnjkCJQYlnc3Ww5hkDENcgr4MFPkZhoS86W4pqnNNVFDPJxKmcJn2OASoA5vpbD5NHy/NYC9oOvHYdy6ipIpZ6ZN6AyrtqqJHCGbxALJLZShRGLccgTnILYR2OlzXkuBhYcmgDxFkCDkkBpRvFqDZICg5i03Sqz3RLIF4vNTgJ9MAGwb1WAmitq9tyz3KHyFJlvfszhteKyGkzLzghJ+/FZta1i7zaa1MayJEtkSIGgohEQgnSQJF+sMoKYoL6uhQAeMSYRUG0htUfJogMyIwl2zPONE7V9iXdGyUiwwzV8enpwCprfVx7Sgc8RRG01jQz/EPKUsZglAf+Yzi7CdE2HDplpnyCxNCwBVs6NoHaTmvIANoQs8RQPxEQQnWAMWSXO+bAPf7VaEFEOndJiuMEyBsfpX61lx6/GZW9gp27bKlv6sMtSOhq0qpLbArsA/nU7U+ohXOZQMGIwyj9IixoHIxG+D8F6wRaLsh7iYREDSYIM64szOeY/J61k6jC+CLHGy9uSOaTE3fyGP6HDRUJwYaBSJcS0U0wnKnZxkXyOG186ngIoFIiU1Abg3A/F2XbEG9+NoEsQJg5W9EfVHgc3sEy4ijlnCCkgok0lyK5hazXHAjOKs9lGECmzeWCCTxti52paXYQ7LGSsz4mucbxd+Y+2OM//h4eQUJiJN3A9hppdo73flDjF+NfIWflbjNxNYtQA0Wst9Hl/Bcq0VQHYSqduoSWFfrGlr6XLSibQM/7ud4vumFO/DvQG+fpKiergpXoFtNl8Agw1pByTw74C8H25RoQMpXIdH9Hp2drCxvr9xeLa1e7ixv/tu++Dsw97Z7t7h2dHBxtne/tm3vaOzL1vb22fvN842t/Y3PoTf6EPoffhXhk/9BBrcEAMJ56bYkJsF8LI/lRLb4Qh3ZDH1tfG0KGvnXJNgCS4GiFcSrZyASAsABvZk2UPwCUoGggVKxYKH84GqEfPC4lCweFmo+DmbaSfhs8qvx4Jfh08Mj7mAeU8s5l3wlchCkTDiMMViH12Q08XNb0oig8X7Txfz/pjcyE+myPtPbUY60n3qRZATYLbgB6ePiAZTQawWiwZT/z6SIsHU7yo+QIgGUxINogWigVVZNPsF5jsh5juWjHeuWO5MrIA+8HElYLUQQBesYCCduvZnA3YmJ+kYGQSQGpz3FUwANIRtcp2oSZt9ktosIrEM2ilgLPLtTM6QqEskXSTZeZTsRmMuiTKtQqrC6sgH7Ijoc8CxjKo45Nao1Jr5PTNpmxGpDcN5Ovd71jqP00GD+sE1pSgJkiipWzXv82E4p2pytxgqLZVxTnE+LzK9GzDBiwSSLsXFMXJHagCfiNSg6u3Jao4BKpPoNsmiQXAvN8ug2WFyK0RIncVpXAbnA9EMKvoqGqJqne8HSj7sA+UD3i0o9YIQ+rtS1AXN5eWh3lIXADhLWmbL7TpvqC8r89t73gCRJ05hdd7euwVkK1NgwlAndGbIIzBp6/DBedS/XDgW2P0VZbHLUpGZquBx/K98LQpCvvr0Az+fXhAGh67OUOYOOXw8qBR4rHqnuNXIxnAIO92vDE+UdAa3NZjH1cpXW4OGKT6eoPYVNtOPUTpI+NyWs7iGyleysK4UdsQcK/n1kVQ+cYa0HcHmVP56XXZ5p6Kdx1ZApQIs53y4z4FHA3rzS8CRhSswWUwV5j4dWnhQ1ek+8pHQilqf3ab9jZuS57Cy6Ozq17o991llAItW+yNVmaIS0a54XuCHXud1a7XV8dhx1pIHViEXEtP30KR1SbVZ+3MQ7gwa3/2fnGQ1vxc3zRw2oHjM//edan0dhN/Zj8GTR1u//+Tw6o/BT9W6fw3Cr4N/gX9tzUlDDBD9b+qDefRTfXA6p91Nf6b2fUxb3DUKZf+PgaMZ5sOfaYaf1s6WVe1suVg7Wy7Uzv4Y/IJ29q+B0c4miZFhfh8weIWlEPJIPhX4+N6sNAAjwrcITRorI/fo+Hc8OmZpJTWPnjxQLkBqxhPu/H/X8mvYpFcja9m4Y0cCU4/DO6XAuxNKwCO/ywNEh2777XFXlPoWHjc7v/32W4f9CO9Ovp0iDrXfJo0fUI2PCeERuzs5Pg1/sOPwm5AYSOGKcqtuNm3caWqrWkUljGDL7k7ap6ZsjmWhFbuc0qHQaiI1SYgfMRzDJJs0CLWPYb3A2DAjPJYjEUNoY+dldWyXhz9wQN1vb3e5HOU6D1f+0fi23PGBB73iUPk6P2WfwnW+3GEf4fWTGPhvSeOKs2Pf//T2R72Orx+huN9rECQ+MiwYHrNv4Sc/EGlQnCqj1HVuNJFWDccSkvb3FUiqncqAKXFnsgB03wKcumkemWclGCBk2r3j4K4VDyA/HszM6SpQCgDUOEr7jv5/PreVZteOklaq9q0iXYvrxdLzW22MtcC+Smrge6Hh/wCLhkVhJrJ+Xocs2IxmUp49OWVD/HMbwuQJwjsKV9lNuNRhG/jnAP9sqUOIgpeHsNsCl+Wc1JtkId9NVPl+wqN80Rd2hvimb7WxNR7zARKEJfu8o2fn0Edd+UkaXcUXaErglK/XdXpLUqo4vbB2hkXZIJRtpZNp+Qkktb9dWoqWCwr6ZtMb4yJVC+woxEOL7pE8MurS8j2ivYlEHaVjxkIK/Y+kFAbQexveUR6zMDc8QiIfI5uepViIlY0pO/KtZdEVzVpUZldSDppu7CFb2vDhPW1Mfdk3H/Chzd43tkVN9wu6X6//0dhlVv+ad3Yj22LdCbyCLZEamzQufXaJWmTEOnE+fRyOSFBEKI2BtiJqovLkSrfTWGpcVYb525H/8HAHm/nXhu9rEnylYdk1C/dbZTEaeMsVcAUbWpzlcXm7za+4oJs/wm9zbb4FqgnAdNddQ5/M/HBw3mrmR3CFU0s6qBz+MhylON/JaaCUh6RGDZmUXkB/5RGeIr0E/nXuwH+dWxMAwMOvOooM7nIteuv1fkzrXZCEM5yTfZGDs8I+hy/YITwYBP5qzG4a1ZE3D99+ts4zQUIktNpXo6B+380BrHsY3snNCQaIU38U7jcA3e6MpuCoF0FtQcPqoSSG1GnqfcStWe47sxw5knu/kYDgpJeU/GKHF0V0wddHUZryxCEmoufSDsMtx/aAkJNF2UpXPnRaWToWhcIEKLTT9h4UKUpZR8Meh1tuC/rI2rYZyntcpfvhHTt7eEA4tBmCxIL3H2KFXYZbDauiu7lpAukM8c2kboEw/0miPEy4naMors7u2Nnb2bXOWLMzdnFjS3Teqp0nNO5AFgWeWTlHBc/fJ1n/EjL1tyt2iT7ulsm8Lgggc+eu45nzWQZyYjrlGze8P60KsxsPDzcAUU3gfOdTMgoCfnvM9x25HFps/3b38NBZefH2roeia5bwFheaefcjZUFVg5mPUX0CHCmq5MtrztNamzhhqIbV8DMYem2IX9Zy5IlrI+CfieGNUixUG06KuRMlzw8+h23oxk5UjlrDJIM+dPjq8zs/eOEM5oIroe2TTeEW8Aqj6nebcV6UCvC7eBYy/xGRLfu71FG4Acik/cBImg10hNXAivhZDQQRWJWWB8oI9igczdTGMAqPiEIonthSJgIpmzmtTyJUli2acqdYzn9MeVF+imLX1tctNE2/xOVIY6UZFC45Oay7R4ZFP2vi50XgDu4uXLUGd2cP7uipwSnpa8FaYEfAGcsdcI7Wyq5Kmndss67Hkk73GsfhMUi0SXQLgoJVUlqHAPv99rj3bfk4+OYHILwwM26xVcJ+YWxHAAw/wpUXtjkJAOFH2Gm/Wn211nm9smrnrGEOX6tgwI/wBV9Vm9iP8Hj5B7sL7+NBcLu8zNSyD46Ys28Hd0zvhMExc/fu4AfTPFPQ7MzY8W/fQBqxGKlj4JyGMDSGWB2qLfaOdu4h6gcOeg3DvgQHSD5wAz5ufvN9lGKsun4QFwZ1zdEan9250zrKpsngW8yTQfjVzrjOo8lCuifWzEiJLBYCP7ZmHjkhdlBtNvMbeQSDj7QiIY9oky6HoUn8mZJukI3/9ynn0mH4nf3Bw3JoNud10hZIvr4MvVFZTorg+XMCw/eileUXzwdZv3hOO0RzwLHreWtUjpNenJIZLFAfb5mzNOx007fVo8Zuurzsl8uhV4ec4uQUi6ZYx9H+lj6vbphjxFRrmrydOI2HMQBHnv9iB2r/h457u7WrGLakmrdcLnu4/RAohoDpNcnAoEEvmshgepqlzbGqbMCvajy9inPkdWA7w4/pQ6q/oAmMBgPSB0dJbcSTCWTXrqM8hS2uaHlE9ZKIWKoDXrJp7lhUj6Wqfkf8Mvxd9tajCYyJe6ilN+4Xsiid/+Yn/DQsGQ+BVX5bKuhxgF4StaBDjRIKiAPdT2W4pAjhNchuJKbbFnZOXgumj4D7S4Uqx5MOM8nuip9qZvNh+Pz/PgneNf86i5p3/5y22+vtJv58eEl/X9PLJr1s0svK5ib8XX1FxVZffaC/m/DS2cScFaihST8f8C8VW+m8xpz1Nr1sbsDLarvdgZcPr/CbzTeUs/lhHV8+bNLL5uaH0/+vduyfzVa7+Qabfv8Km2mLNl9SM6ub1Mxa+/Qfz56zIiJ1c+QgXTK0zlvuCqEljiPG/d5SO1AJhUjoBPmwBYQJTwp7cYR4B4UgVzyxpY5t2zlUtp0kEqVaZkvF2bPR1S11Knt0KQ01tTQjTDUL0v17gfyqLQw1lT2oTK7l2E/VWm8pbUX9Pp+UxXtRrkDXDN4qM+Dkeb4ONTT8VoF0s9FmL3w0xgy9QVRGTWna6iGlanq+3pq1C4YZa+aOtay6h1RXkAUbX48GPswNRCyQ+RI6AnCS81hV/SgVG6GO9qFx+F5yHUqZVuxGu43SF8kvq8nA1P9WzvTAjADGjX0ui1kmzfwrEA2xyRUYLPysip81YeYflWUen09LjqYPYb4gsZjALhgmIgcNd0AiUiQhTJn2KsB3qkQ6FxAKSc+CIkqB6N7BxrAdxsrZYJxd8Y3xpLwVJpuh8DK4I48xT9ma1AZResHzbFokt0CRt0C8zT8e7mzXbBsO9bI+4v1LMmZTpVAcyWHXoPPztNwA2o+cyxdB8XX2x9uBYMN0RnmbcK9VTJK4bHg1z29JWzdHE3/HcVnhZkHTgMuLwWaJEiQ84Vrz2cmJJyYDZPO84KXH5HuzLxNO2YnXT6KiQOhBNj1TKu7Em1nukRuITCknGz+m8RWk4XOT08vp6cL+SavMk/ZpF7pa6q6WrENdPemcVnvr9V1IQTsAmosL+VxMeJIQmOGFjHW9018BzQq1567puaajaZntczyhxaa4PAHe54LfKfZxrDmsTgRHf1rILuEE8vyKv0smo+jv9KbSvgdkNLvehLQD2CcB86LiNu3XsFOb2Bw9fQL5o4YgyrOkUGiHv8DhDWLq0kA9fIr7yBdspfJBpe8D5pcca0K+GZmU8W5Gdjcol4/iwQAaB3l5AsyN8NeEB50PCy2tTeDjYitNgFghUzvYQ7PEXMIHHgiGg1rRh9Lww6NxAlgOzCsfH2Da38Xs1V+Zvr5YfTAlYwBKPKHZGU9LSip4QjaTvzZB0F57fiV5fcVpecDWpGhv9GvVrS1cmIDqSQF15dk1/hRAnwjDYaf6pVpfLq4VqjvAOqAqlPR+ra4XPwUwyTnbRfj85J/N4LRxAozOqW/7gezYjiu4tKG2o8lE1TYjspOWzREn+Qbw6YK45OY55BMmRXl0HvebiJA1ldgsRvGwrAHk1Yf9JJ40J1E5Ek854idAEgSIGChHPskSoqSL0pog3MBrIfOkR6p8E5ZoSHxBQAOxz+4ZT3HhNHG9XOQkOMGHSTODrQkka/FCHUE906BJFcpnXQYWbXMYjeNEPuN8m6dmNPiORqkiAUQr2M7Vy20iC0qRSLxcC3BcJLeTUTNF5Zh4BGEfoCrGO4KXOygMwsZ85hWaGfVRDsFS0IGr5o18hj8XcQqv8RjkHQs0CS8BgE3ck+kVuwAPcsTjKL+EXCitHsexfiRsrMGem9O8Cr0fckYqBbbl/mWKdGKCaifoBMqtgMpZwZud2iSjuWwCcQFhrqb7RFMMQClG0cTualFmE9kvelQTgb49lxyNhqcXI9MNN9n0BdKzS94cRFA/+UNYCdlwCBuoSsFBAJ7ar+iPod7H6N2bxPCjUqwe4et1PACkRjO7ZpT2Ryh44jOKxYI5EO9mhCTYu8A0SWYE0zRGobh5Hg9i/ZIjW4NvZdGcIFTHtatmhFvYOQesgJcRlMBWrprxgGcXeTQZUfoYlh6HP4Q6V6QaaHKyMKshRhEe3YpHjUb2223tGmZWo9B1HhMGoet47WacAPt9AwO4rN3IBf/TvUL5fijHpO2C7RT+Yo6juudSU0EE+yPubfIt78Puqt6sR5jwa/lYxqVORkbz39tJYq2C58+vr69b16ukJ+m8efPmObXn2cQeABYglQJqj48JzJl8JLbZO/1/pTNfd7axQ6+fp4o/dzoFjBup+JCXzLOi2KOJ/7WNqPPznf6OtwgQH3M+VB96OsUTVciZHVHKz+BJcil0vMj7WFh8EwnBknjf8Tvx8j8dAjTUxr1Ub5r7hRELSfcT3lXVHiB89WhmxAF/I1GCa0IiDlpjBPnDw1JjRat2QForgelG0TRD8wv5vIeaGdiY8TmldPG8S56SIKFiRxKy/2qkQq5kUHMSqgahhMiTCVxKUe+UvAb5ATnhWymMLNT8IKkKbz1+kjiSG8AwdMaG0iLK6p4XpAHIzIkrGbK8mkK4yJ7uXyMJRe1QLhHyKP6s1ev43VIb3R2xyyzvuUPZPWjkZJs3P0QYny/0Z4dlmA7/BetBFiVPx+xIn4zZkT5t9rhX/DRmx1bxdMyOLHo6ZkcUPRmzY7P4acyOD8XTMTuePZV/lsQFxQYpHg/s8bF8PLDHdFH3cX8nWQwK9KPHI3/0878b+aMfYeSPfvSvRP4Y8UrcjaPCdGWQS2eyo0LbWvp40GF7SfmzfkTqIrUVpGgf0b9sAUaMG35rTLnP/5k2av9oRGXN7/nP/S7UWAqi8fDgeVIX9F//8V/LR8WyjMlToF5J9+W40NE3lvjDw/tC6q48r4slhWVLGlKfAJU4ulkcYD8Oc1jG3UfS5aDo9AY1aT6p02z/PGekTMJKqNYMUTVqZhThswmIYPewqoNHK5ppI5Z9PkRh0j4mlEkmEgV8Df2bSwZacXKqwD+UVr7hcDZfkENB1OiTbQtNoFC0Wh/jh1xa7VqxLqxPFsy5+g5HpFMAhMN6PdeWekOBDzBC6QlvznGSUGZKjgcQwGdxmM8nZkBnpU9Yh0VhrF+6nbdhhgemIayC5CTDTShGf28/ajbJJtkq0c2aTQbpOM1WWepzBi+dhwd0T+v4gwztVmRp1v4tArpulxfmfYivmKx5H68GCI57N+z0+Gj5SrYGcYGaDtxZ6vVpK077yXTAi4b3FoSS9HacTYvfaO+chlNToZ3JnEp8oDOz6xFQ1IYZoC/OdWczfdhIy4g9hv6pXHmopO451aMXMnJksH35PSIE8GRUtREp8nW0gjK6kIpirQPGb4QGWWiBOy+tHG+bKKXMWbVzDjSRlrlvFuVuE4UWJdqONUBH94CHSDTE9ow8GZcVdhYUaAlJyCm3sJ425ru6eYCLAczHQnmVuw7sOvzKAorsL0ISNQEPDws+VStJe8ermZCzsJeqvilLdk8Mal9nfBL7vUjeKnSy2qVFxp7OME66MuuDyVJTJjKezWXQbM3cIahYB49GvIgChZsuWLx1xQgse9pnUTadWR8p58YnvlZekfLrzSIw0gyig169odMHBvXA/FZqLuV0YYiNkI5yvJ63qZ0XG3Qg7AM/aNI8jWzvCnOgUhkvcfulYtTL4KNCRuDMPfSpkr3/WAYUtUB6L2InWuSqaNsm4MewzVmbtjbhJqMujcfToS3cURimBat9RZ0DeesgyCgEUCu2Uf76zHXa5qO/P3Od16ofH/iIzj/4oIL59rIv1XLn4SNrDiax0nzjV6dUNPfqkQWoqVPlIM1dj4oievtZpr5U9NM7RCBUiSpMrDpve22fze0VPXvpBp61gldWVJV7mjmV9a48Qg9WOnptk+69QsGrpEATb5cUyLpe6BHlpL/ZIeWe+jSwiXvnlUvk1zSxN1SlXERTH1mkFZpaztPUcvG6+FJaG58iZ/IAWZ8NO6F+5oMAmbNZjZJuFCCd+sRGM4jmFqjZy1spzDJxCeTjb2sPMB5MjFb1wGmK6DB01nGe3XjiMNXLo0EMIo5pqm/RAmq2p89HAnlwBvKv5I0veGkpHD5wwKt4UmLYHDu0msUxlz7I3hi86aQkX5alBUoLzUymFR8ElYzNhk7kPZ1TuDlKN0KfoDscFlCEfjF/j1qV+z5FKpnmqLxHn7sLl7+XFSSShR7FGBLEkQEyZJZhnBmLTSGWkTDwRLs8RSpJraYt8wIf3UMXhNv/fD9yarySq3sAmWU2wVWHlsD257Bl0IRSHkbMw81nwBNe8hpOEBqumT0CQyBVP0FKWamE8MfCp2GkgpRwY4Ggo+7YnxI+lMZoQQh5hGTCvVvgTteEhm3kEkUB3QSO9jzAOaR8wwhkGS/gIjATxcoi+4u0B3uOghYGWwImL7DtEvqJ5ulwI5CYpSyRXC8alRpIcdkWwS0TJZsrtLZnClkSXymzJtyPzrPBrdytjWO1TLbIkhSPFYDk2BVgRhyDjQA2ucYFspPMiSEh067sF1kdGroQF5JitDm0mAREJUfpFjIacZTIimfWbE8it2t2Y5Kp8bzATYdZ1WOQTep3U1Q21k1DoMqlmFZdWvQ/pQBJdk/De7erQc5kggyhweSReEJNSdWhQyR1oqCUvUpXA6cr1iSNJCRKMzhWqiBqpL01586la970Wc6vrEIuBDNsnyBGu4Aw5RGyR2iMinukam3jfnAlQA8LBxFM9VXEmRJ5pGYNdB69V7O1ewe1UkzPx3EJFdIbmjEAo3Y/rzSV+4USjGdlldirAr1zGrLUrmJQv2pBG2FQcrY/IMjYBSiinISuMg2rIpKOZ8crGeHSUrWsNTfjyAok9Oh4EG+eHoHS4Jh5XGrkSwa29Tq9CdhidWp2lA+dSaHZl0SmW9Luuni9itWWip1fTi9RcDHRJS4fZ8GWM4waiEwUSwkxCFz4Ds9WaQO5pynFXC2pBdlzHe8VIyBpVH54QIrcIs/tD5Leoh8dlwcKUl/v9PsnIAjc4jbKV6tJpXZ+mLthXo2ucit17POQH8smmFOI1Y8WkVoD1u4mb1NlRotR6soTDKaZniSnaOVIJVMolZqwhmiqnITz6IQxOE/SU72/4bOyVYEREWbbaWHis4RUdSJdjvRAZeP5Eun/RB9g7LCsMECAYAtE37nddxou9FyTGJg6ei9MnUy0lyxsT5OFJaX4FiWlORLuviGm+LoEcrF29bZL5DejNIb1u9j4bkGA5vXGm47vz+2dzo64aMtUdn7BT5DN3h1vK7sjNUI0PDQhv8SOLiMqlpU9lKV2cLDFw1nxyRF9CNPoU7mOxrlq0VUomoYper7DPMP/Gsq0wDGK0GM7qgAG4Yg1CVfOCN0ta55Md1MrPCkdnSGBkPTI2oIoTsU8K2EFkVALecEqBiCwfJ7a6yWeWxN04cpZIHtL+8uuIJmPTzMQZEnbLFw1lNU0cRPZylQVNvbqQgmCC46cV9rt9nMsIgRHtKN4ojSdr6PvGv3Z2faqguVjZ9potGkLnX+68Xm5XqS46T9ZSQ8GiQe6iwvq4QCssAQQHB5fpHvypOvpquUlDoOEfY8WxfVR7qcH7yaTivhIaS1+w/tHaREN+XYGktmmrKJnYi6qmMFPlm/Mi2FcfwprHvrZmAslL3Y9Oms+2t9aeho6Dw+eNgeGpxr3EeNkQlh2NbEeJOEApmWxswTwHfEVLOVBYn3svYUGfsMwL4Sfe8OGb6Io+8ve2+eUD9QHPhuSryISpC63X3zF79Frw84T8WG6ZaU4Lp10IIrbeSgVmw11khs6rgiJ0zDSSHHmjiHeZCra/qMe5FCe/vv34l0yMmqrmTmrOhTRTkd5eB+l8ZhspbboQBUeREA+dAwuMGrmPibi6zmZl22hfdvetESp3008QIP/StoXtNASaTebCb+xHn+HXWoi3/fyAZ7k6KR+lkzHpiPitcDHoaxkKGq4Vs+fpFOsej8Y5WjNIt92+UVk5+5hB0m/kceDd4A26nlf1CgfN9KB9YY2ovYrWuep93XqoftmfS0S7ApkiqoD7RO/kPEWvqE52noSjSfq5aPOkhZw9KgGkeWTUSTAU0bnB/EdjfM6HmTXlHgn/BPxKcvG1FycJHumJrK7tN5Rb+K8orXdB2XP5yYJiz6TtqON9kzaXF0KLWZsMAxPvC/8/DJGO/sxmvXuZHfwd8877dqxmkf5YjOxwXA+WUify6gcifJ3ZaPtu5a1kAPShtBWNjo+G+VoxjVCp6+ZY2x0HVXCnpULnFCUQlQqFj0U9FNTYsni6EsR+niUV7laZOqp/R7GDS99aVcQlMve5MY6kbyUbAZy22TmqnnsVASlWiScpVroSoEWAioA3fOaTQ+1pIBfIYwSGC7gjYGV76a0MyVZRKpT4E76RbFJr76y7zEVA8kPkKkOExH5YTgMkZEE+jtFy3Wa4ftIri+0uqPfXK5p/OEAGVomI0qNxxf0g6pbfICpv+CpXAa0nMe8pNomUR4RLuugYKxEdRqhPjVhT+TXwqatyCQOAdjEVSuWc0nN7E946Aor2Vl9xbhgPH/2peDQ3fYqtb1sy6ok8jxeo1Kjox2bd3ZGPALdc/HIF9V+vwTmnw4rBR4pPlE3LFMr0dr118BrWxzTj8La6g2KEYY1dQhuXXdcmJOIyqGuF6VpJs2tb5DtoUTH+lymSfPw/tx7E40Sq2nTPJ5Lo7hJ5VwysioycRwXGCS6Sabg2kmuU3Vbawvs/70QAXhMKA3HwB8Z6yi/4KTwhD5q9adw+mR4dJDnwCVlFAjoqFCsDF3K8GguqiNsBgDWKNo/pCWGV5Bc41+iZ2xTGCmyD2mlp2eRVv5uohmEfQHQX4V9208FEVZeI8YqwYGu48F2uyXJUnsJLDf2F57l6iwZlpmVDgbtUgc2094H/CcConE/gI7COg2g39yUPYhERJhNaY0FfUaB8UOKKwe+UKOkQUE74jqDOU9eyBZevKbmjcjl/Bu2ALOOzRI4eexYbX2yVWM89m222O9i4bat/t5QxbX5CtXHYDxaH/BB+ykCHLFhhoO2uzrObYHTBr0Qq+ftMsQWAFOSEonJF5VJQ9wKu1xd0aG8RoErBO7uUi4L+abcqFXih2x6nnC3oJVWLb6TYRzb7DqdT1lYdAdY7vmUhUWPJtX3hcU20D3CCwAUS7nWvvhk9iCRFHLo8hPvfFqWGe7x3BwtyhehllFvyGXjjuf5ePaylHdlEDlNMyiSEiJK1Wgmdc7/Hl1wqx1AHlXQKHBSwswylje2fCrJhhHTbsklfvHh223OPIzcDryxR0dtjjVfTJew+Ex6pUeDwQa6kuDpNodtpeGh37LHoJLbXJeSbqKPF5TnPFw0YJ1ATYauZyyL2FQg+FAqIa1ry1DYECeMOlwBW/W7wu5PhKZI2VA1diu9bLNUwPHWFyT7KseVN0wEwZgk+JbK6KGjYXgvy7uXHeQh8cxAkuxIA4s779Y/lD0bDZ2QGVpNtrAOmM3xcHG0DXYllK9XuYLTENa53aYIClVhWd68BryZAJlp4IjbOGInktut7fPAgLpQ6NJWlJB/Z8l9KeVK1btfhvqZhHOUOwcZfQzibhJdFPW19pvXxE+qkrg69FfCwo/7MxNKPbqgzUzeuGHuTtIblSzSWTV6K7RaBulnQFqqruU0LndQPQDGLb1q9UM8RFlyaSOuG2Vxs9hi4k71i2BHavoK0F+/tk+HrxzLI90xeRQsHN6pKqbGsKg+HXoeGxSm0rBPKokgpBPGbtcYA0hwo2GoqlWEyBGxJBO3I3FoaWfRc0B8jkpFmQ7bRVdFxZoJo0ysW/C48HUsnoQtLNbfF4oMEaOQ6lcgBgAmaCgm03M3veziXUNFfI4Rv2aLIIsEVHYQupcrLEvDBIAQG6WRutaKRbo7kehOJDT5Ga0I+ZkeU0R9EpkACiigMqMw0h3D2cvEaCM1+MXVx1jLY9XH2P6j1VdH/wb5Ahq+nj2EQLXcm7aCEiycJdQUPYFQqeEnVMhg7H6PB6UVizFy2FtCab2yejuYW1m9O9XV+wJ5YfH40th5CoaNS/hxE9hSrBeqRV4Y4i7TLsnFElr2OsUP96PwjydCX7E9N98NEscuhu7XJsISu3Gz7Ehg7Jo7eSnw+d/d4o9EU2N57BSbi6HHttz+Lgp7x0aJU8YNqMeu3Z5YcfjYplu5HduPjeV2dlFWpIjLoZzei1LzMhdlC1WK43FcbsbnPEdbP8dSCXfsRYUa44RxJg+dGiZGvNhPOiuvUbjEH2M3Ivb0PmJIOWr1k7vVlZ55DA6G7Gwo8pLsgu3K5+3dFevyTTt2C//tt9/wfimKlQwVrHaajTMs8Hx3+ND2H9rU3m0SvlxjV0m41nmzttpeM5Vd5PaRR73JdYQz2UBHxTaT7yuVuCNrFVvH11WDyM5LaUqpq1iVdbxUxoMrr6VR4YuXMnZcR9kXtldkoZX2miwF+7Qs9rrzRpV7ufpaFlxdefVSlnz54sWqLNpZ7bRfycIrL1c6aypK3crayuvXqrG11y9evVTtvXnVeaH7zOsIupW1thy+gKPsxurr1y/bqpKXr169WunIWlZXX7xYW1uVDb981WlD0TVTaWe13V5ZhXqV/ebaSgc+19DUCXIWXr5eW32x9kIDVydIo9bVl69ftd9oq1GToIx7ZSg63QWTUtEYONG5LxJXkpuIQMDbUcoLJczpENxtKca16S4l4cs14AMqTNcqTeBT9Z6FaV0N4kVXun+0RUtRmNX/O+lGFJs6DwFVIz9oxPUwY/ESmdA0KDVGPz1iIrG2/05YZr7I/KBaVkmWVocloW6LE8K8Xl9qlHV5k2Jeb+bQ7bLeLFnyWxgLz8LOS4zHr5DCp04bGo8t1NewzQfoUeclski8hU76eJ2XHDq16KuNRGUKMaGsA9vSflt2gTuAJY0+Rng1VeftW+BtHkJUaGIZgI62MzQagqGYrIorghur0cQNWsaQhXINq6WrFuz/75ZpufyCr/4bl2izU12YlXVYWXaLV1mzqo5r2lGk1ocmqptWlbirJq+sGlxYbphHubLsddl+G0t+JJM4FOM9mYBDGZuG6C/VnZLys9dYakT11H94iOryQojsNAQ0ilADFkzfhqTfEy3KLjyEkc9gKf53ZI0kiSvKRLs/9aaGywtiwdrAqgEF1MDqWXBrm0o/RA2lQ7vV6H6bvH0bdthS4zbRaxD6SPscMHhWOLLYjVd4csrQiGe181sqIg0qPZ6WUyxzAHXJzb07kgeMQAUD0NNO0HGnjO58tKYsRH8lnDVUOogJK83KFj761n3Bw8fpbf2/gYd0UjAK4E+bZ+781emj8bQUwYMiO9WlU5hWVgmUJPH2eDRLXEFMwMNUy3dixCk6FwIeJt3yJDnF+2Txp4nBo8RvCogVW4gVxy483B7CfHQXktGuajg3DRMRzbtJvXzgJ/lpnVAbHh7Qxg7bledEOapjdQeeOXJEPWxy1nnLe2vwPzf7Vw9oiqEFa4GI1v0uYlnMjiL2PmLHEYtIk3qTICp+lhZc5/L3m/z9nosA3NGEXZvHPwl9D4ehh36IHMMV1ehpOqmV2bQ/EvKAeMZ4LfQgQrRE05s+ajhrg/NEPMjQK/Ib+UZ1ymeoFaN0YUX4K+oZ5NmkhrenyeAkmGu9ikKX/JYqgl+KhIYPUBupJCneCd0mAN9Nbmt9eJhERclrolv9EQUxkZ5HeExXI0PLmjS+tGJymOn5GC3a8kRErzjVxyfwlk1LL5Bgt+99FiMttZ8Ivic8QnXu+XxpgjrGuFGHMPSOVX+bLyzBaRVXKfjB97wlzPrR3Vekbw18+/uLrNRzZWuIk6yYy7heWJu1ki5zW3+3wL8d7WDI8v2KTOrDuAfr6v4cpTY+2EuDkg2yMWWKew0ZUYCDW5jB8SbKPUHOrBqCmIkTJTTrAHGT50UAi3zGbLNBPNApfSspi9E3joFw3pAkxmrgASMK6pMqUy9LloxBsTrhS3x5Ha++xhpqtXRiQ/sm8sqJgkYfCSRAGwDfZyCR+hOmwlFa+CNLn1Pp84WlLfyRpb9R6W8LSzsIpNRgenbVxgWIBMukETOoB54v8NmXXoumUlPrArRS4SHt6oEAmXqvn6x3QSTJL5bR3lXaUJPmqkYkWb9FqwgnAKbQMZI2iHSMqEilpMOIzF91Od7SCAq71TE0qm+2sC/5PcLPZr4xOFL3IqAeF62U5jRKrtq1FRfGrdFtNTXqYLsWhZxb6TAToV5U2zPna0cF9V27uZgirhoJj/Y0SzO/ElByUMeICrr9GO0rraXL5lcWw6AyzKEAvnNWdy9sznWuZANwg0ptrzIZsxTPFn8vYLmmsovkA4iqEPq6kftMHkjLS0GUBctmbs+vpAfMgRgDGJQtCiTXUEr5JYtt/KxPPAmcOLuKMlrrf3sIaCF25M+mOfjiMwXFkRuFDxu0nXlOmecq85uT+Y0yv6lMWIrK+OczsMzXzqvpyJk8K7XHGEq+28UTYCAeHhrY5zZ7SlnYeEKrtj30bU3/bl65AizRGwP0Cy+QndGlXjcq9oN/D+k3CWGLuexXBPTWhYi/luwXlE3xtl97cOQkVhkcblUZ0lgN0TNBcZ0pOBN01YH8maCeDsBLB97I35H9/p+l3cM8/FMYFv28c1159ZmugI6L/qRVkzqfi+L+F6JT81n1+p8GdxFCz9LwsGw9cis7u07IAUGrqIfV+FI5B4b6WWpfSeu8hZZ/G4hN01hXoA/4cwzH635kc+D7/7M21/6VNq1PkBpeJ6rpvslwDorWRXrOLhNY+Iy4wtz4R8Fmr7cs38fgJtkEj5mji0hsELqk/JKVqMRpdt4eDjVDgYYnhAaJq/OPkWgl1J9YI+W7CGXu2O4wizUOVLqLGeqwKwFAGHZmQVcFwdQ1EGFI5YnxZVJRevddQMp8kEf/KJAGc9qazbmIv+hoj4s6FYTwlBS35dRsyxy35dKqRh+WcIvGy+1E7uR/b8edO3otn95r7Xbp/JEOWcXalHUBLET8QMvZaZEXgBCTlK2XZT/SBzacaxswLbTolMmtepwqOy4liylRQ0pkluQBUpX1JkK+qvdsYsszC6QbehXmH+r5CgP9yjcpnJk3EtHM63RiCzVWUSlqKhkGZTb9PNUwQBd/V8xxQGfJmW6KrhinWgiBKoH87PQzRtSVvvyck+u7eBHioXixpGE7xYDVSMYy4QqNnd12nRdhQQPo4aRqWVZPtysJzyebLliJ0wlGYZbp5xydLs6TqZIWoyGAx3oXBewJtjLxZgoRQcLpKGGHfB5FxcjJnGQTWkbOUJ0XORjnSIhQ08FZV3rmN3E5L0zr96r0PDamU0aaXihsS4SxPrDl6UdE7gLDfxpkuLjQRpqEClZl1yOusYaatgdGCfZQZEt2GZkkSznHZJ68bMRTxmvfgf+U+vk8rhy5bUWVM7ZRIvTL18Pq2dpmNH/gUznO6byczafQdvFVKp0G0qjorLp9fJPGjGeapJ8JUz2OXihoCaD4IRQFQ+loG6dQc++rcgCHB8u7g8U6jljX2D6ieRlG3YR9EH7IArIr9NdpUxy558Bi5hRlC3i2Zi6KxvDQzbGw7h7ULi53QD1d3us0cxUHwOJ6E9u+BSjfOpomSkcsNMvHd/Lu6cmjfpEkDluFg1RnlbYU2AHRpaqkrE5bpbHVlbcYqYBSV3vcVmsfJOZ+QSv5z8gkW3L0X7zCoVfvZjij4IZ0VYK8PuFMiFxbQInURQvkLi+vY7CkOHV7gvggzMSb3I8PRaLACMnoRzXp81Sx4o+E3y8/iU6pDvgN014KPFBA4dq0jh3rx51dXiVNcigfhI3YXC8t02QYgfmMIJb2M9LpEEQ4v3eQBH9GTFZv8UsHwD5NoAGZOTMepXbIwPuJqFz2y40aCFVWu2CiHVYh2kVfx7Tl1terJqDzhAqOYo2FXOMuU9whqRZ7mBgW7THoHSS+iCdiDTyo3JL1aFcr3/XSeZbTdFbsrO+n5+fJXG/tPPQCfmI2qMOAOUVcOMCeMSguklGDeJBglEahSk/DexrupxF6arTZObVTwJNol8KztFkZjzG63ngSLHBD5C2d/fCAVwvLG4fZHIq1oSOH+bSg5xkbxiGsw3fAqh/kofBGfpey+6uYXwfoiAzsZwLlfLY3xIIHwFpPYjaK2UbONhL5xUHO7sUG/RU+Ek/fsP8JBnH/qp8wDQDGv8pfKlPmyZ/8Fr9D2VE8Rol8QLcT8QQLdicb4PVVwhs2uI2ZsA4mkOEDgiwHeMHQxApfDCmniI492uOtYZ6N9Z1Poe0s0MM4R/I5cAoGlfpmDLfdMQ17vnVPZwoazFumNMUA20D/dfzDdRwQw0n0GhM8EJWQbm7k6hHmQ6d/M+nf/AAy4Js2zFbIceZ8079vT/XvW7V/36AqtEn+GiEabCQ+2xrK2d9I2D3eMnSIEu+Q54Qum4QuW0OffRhaWOJODxYcEwJ+gILPhhYCav9I0si3GXw3Ac6dbv8D5AGmfaAnBKt5R+09w/uU7Wrw5oXzLMoHsCaiRQN2CqhBu19JA2snEUHxnpo8giaP7SYRFNSlHwSqY8j/OAzvN4p+4MGfaMI9doAuuedRHng1j23zYRl47/I8u8ZHjx1N5OvRxGP75IIo3unZY2iqL1PIjp994EngfSBNoMe+xJC5d+CxHRDZAhXYDl889m4yKSpJB8REBp743c7wwpqd7O5TDgwfUh5cfd5RGg8A0nR5nDdjX2A8rwPvfdS/lEHV3wTeYXTusc4KVI8XhMPjKoyXWEjWeQn14+qGx1eifWgMXqCSdwmmwvefSOBiK+0AL4orRE9WXhmgra4QuFZXsewFOhuw1TXxLMCw+gJbHMADtPcxwzuCVl85kF19bUF29Y0L1rW2A9Q1qA0YDWAC4PmlgW8Hx7jZwQfoyeYKPkA3NlfxAb7ZXMMH+GDzBT5ABzZf4gM0vfkKH6DZzdcIKmhv8w0+dLDCNj5R1Vj3CtbdwcrXoPLd6VjAo4O9sqdqZQWyd4BKwrR8hmkBcAaeIJ8ek4AOPElkEScAOT1JVWHycVICT1FezzKsP7cspef2V620qFLl3nxSg068ws/o+ef3lpaQG3YiYN3Gxqf9fEh74jebZgAX66xcOkSARNW7j1AzvmOIavxV5zAu1s6F3jMUVusLkCcmDppJ7lYicCBc1Ynur0uOGZVmgVMHyf9kWGvSphOv90X2D7/CiNROtzDwLcOLLoCqJVlfMDi/vivmfMKjUn5LbMKifVJx+Y9wDnNgIBggdyB7/dPvHhu6HjdWdj2K+6O/14W/3QiQ3T+JLH8DsvvV3qH04V9APtrlCH7FvR24n2Cj05zYLFQipBhn45OVGCfExuAvMi3lNbJ38J2oFH0AibECqjmO8lsi/78T+f8K/fhhY7NQ2hTUEm6Ch/pd6C8GJmHBfC/EiwVTju0THH5A+3/Y25N9j8Ev7Kt/US1/QC18Yu/3PCmjhRyOyFE7qSwnFBAfrKwmb1lpiB5U9NujVX5zqvxmV/ltQZVOgQX5usW/iM2Fhx2BQcCWT3DIfOKzdBKevIGNDLYh2H1O2VUcfirrdW/dqLmIHmL90pt0XYQ17FI5FSJjRwrd6h2dC/NQB9CwiwnlQD4RDWE01koL9frSes7+iKhAY+kqfnhYB37x9Vv82+n8Fq4Di/5XFOJWyaeOw2Q5XWhRIvWjym5/oo8FSrWw0BRUWg0aVaveA2QhKLOy8qa7SANb1boava4OvvjEjZTptGKBJ6QSNh/vGUFusXHEiWk/hKPUAUYyWQiMqlpTezOoCLjW2DQEiLKhb8eKujdhiieZf0XyE0ulaoUJxu7RRvMX3rEwlX5EVR1XJcxfMdEu30faiUcEEXR7/vAAuFGvyznHnQ1VXqgIG8ShUo8RTJj0F6m6hEttuO0uWQEARZErW5IooQ+/IFn4JGkWhaaTBdBgRRTQPvkRIu1b8aTOYTVYMVE4+BOAVcaizVgVmc319pEJ/SPC3ohdE3mFy0zeeSHmZfEs0JnYJLwnv3iMeIAqbvWLAjg+e+qlSbV7ItwCitEYRgE4MgrMImJTiIgKRYEXTeEzXXZF0RWAhe5TwZIn4ueGIjOoVqY5JV9zjqEXLL4tn1oaQJQjVbRe+9mN2tu1QjrLQxbg0+LJidhqT4PS8bC1bGOn5gxuN8JjtzLcTvAmnSxdF9p4nxm7DXFbD7+uDeOGKcHUoYQ88MPzRC4Mm4ReJEhZIj1ai6CcyQh5n2T82MO8otnNJgiAS+yarRldtzSjn1PpyTTEwz29iqwbCMwqkwE6xWA0D4n1FFPj9EsVX8T2200MdCkV4LRoP7ka3sTygHUaPhlGqRtP3Rt8dI3Mk06FAGFoSdJCKC4L2L4+s4s4vInFkeEFeSpD1y9i3D4W7kEPD2/eLt6crGuCp8C0f0JtxaecCLKyf4EeKhZDzWs09XGa5IxZlURTzc87F+OGSt9er8O0Hea+mriT0y7gXAm1Mc4ozIPPPkWNbIIXkFiH7BNt5CwCfolDxV4De82gHyWhDTzhrUw/6bqMK6b3rHoda7EC+E7MpQzh3JmadE8Xe6x5oc1QoZMYoxV9u4p9dJJqlbatfYaVwo6PfAVvK59OJtWAa8I4Z4mi5XSekxD0HK9U5uKgGaUqgfuDUqGcdHCn2CLmGiCdGkwmZmnemVBbg5IenSuj568hEsNwQ//YuWVYNSETCmE7ghHFArQTpP2XJErktersPNIQLQ/b3dxEz8yV4U8Spie5CCau7hQvWQJAWsIxnSSnDA3A9cg6C+y4plNlwN8lsuyELXODj83Tpv7Uthunqrpk1y0PMnKyEKcRugHKyEWIL6f2mZU64+LkD5H/Fqp42Pf4LVBeGWeqbPIZtJLPeCD6bdqAyg6E6yea0jkJKgjDDNNNdBR4FTrXGfXfCeg4mLo4CUjXI8TEO9z1HnZo3bmGPo9uoqjDatAPPGlJUWgVn3wXF8EhkwClVbzXT5JhgF2w8WgmRo7vvMRg3o42Y4iEUc0FD6VYUIb9BDbashbjIXraJzxvYYCgrc0cSJ4k+11xG5M81VBxe+RN01+EHlJpCVp4/Z8J6CPdMXOcBBHpIvWRy3Q+FqYuwhyHusQ1fTdBACyjzfhf5yXUBQD6OgAjt9NFG7YgL7gdJwnYHucd+B3nXXFNFPvYYU+wVOVubsrHOO0W8RtNzNhwzhhF/UMizwc4G+SfpKn5PtJRbeBbr+M/N0ZwvQ5oV0nTwJXTy2SA1NzYVsYqaGopbqbK8bw1bwGrSqKBir7coHDJZnehCHaIy6mfttxU2NyspI10INxtxzHGhUzFybUieNr0iYdklOz03oR49K1w1scxv7ZiJ11wGWIX2hLx0eyUhhBnMQbCAqITm56psaMRkxi+OZiJA6sYAgbNyNElB+oa1Ovxb7nwaSQP/pjiDodAJFMWq5N2esv9LlrdEiYSn01xDsXFXLwlLrLdFXJsQshtkveICFKGoIeYRbgiP8j0B5Sqy2eyvE+rQfF4hEwNjDELWTRnDdEiU/UjByyDmycJFS+gPIy0h/FABgNRAxUTYGiIHjDdoB+Iewc2FuQxpw5f2qeSIw4PU3LmN7QTA2la1NV4HdxzdQLGEjzBoAMo1EvDCyuziU44zCYzYWaqD1sJSu6VGjKxoQxaHXtWwH40Z+XqnkmrKQzKiS1W86BVtCKHtolhGUuNyuMqGdSdLOR62XtppHwtjUm2pehx6cajmkwV16mi/olVQhEcUl1l4OzLbzDUirvuupfxw8P7VPE979FOHkh1jhGS8vB9uogM5ERKcr+Xh/e0koK8QhYYYImdCIgxwxBMsKYqdCyvErH5he9XVjmjiKZqBUEz5oXZS0jniFem11CQm/XErDWk0sXbzAfY1+vARm7Dakd4wFTgugeh8zpGqVP0iaTO3EidpSV1yhIKiFLqxGmrSJ2lJXXmqBZUtiTvU8fQ/JPjR34/07E8Tipb4ymal7p37eoYnMv8NPSu5XOJGRiOk1LH+ABJhMbHqRU6FmcUWvfeqQQYlX7GgzCf6bKxCjP7+Bc6Eq39nUCmR78RCOgzY+2sOnWoU6C8eaFuzdhZDJBioynCixSZo59IwiIwoj0+Vy0KuCBvkjlOWzaEzAtbUECD5eliBAXz6jsjqnbEfO9AxXqzvPwOlV/MWYznY8rYC19IxDhOrWQuI/5RIktF+P6nApACWcXs0dSpmIipkSyIPE5D6IlnAw5m9baSGlsYclXJKyQmXIh0Z+ioo5hql8/v09CLzjPhwLkuHCSFwyb8fEqiW/V7KO64V06UaJSsfCjx7Nr4WqLtJf3ZkH6ddGwnnm5ikb+Npov0tHcl8w6M9+dgKoMwC09NPp6UMR/UeNrPbyclPQ3wLwbtqV1kIATQwY8Mbye9QKVNMnqGfpCuop+Uq+jRpIYX59EfTiYE8hFPZAfqVfQInSArDYxViD7xhBH4xNMetCsecFRjEXVPOp+SCXMNjZfpD15iP1HV2g6yHywHWapZPmPd6hFrl89Yf55d0MjQsFnCTHi0CmPmmjBjph9sFpADr7yR/q416dddQ2XmEdkJC7fedcvXd0P5+gqYCFtm2ZTY4WvC5FWUon5fR4Bx0Jwwd13oVftD6B3ub0ixRs9sjEHYKSSlktt2Y+BBduO336eKC9mNlfx/EIffpye78Sm7nYQHcYWkX2HaSfu0EvkYygnbzY7fhS7cTnCz8pavJv4MXsdTfK2QbizmptsEGnKv3FxFin8gyVTm96wSH1JkK4UY5m6SFbWVnpF2MUvfowE2pV9QQxUaznbShhvQkZ0YK2dmWTifOmVpHf68rMR/XbNlDs0cU+jqF7r+p74YpwsU0JLOSODUFDDk4rb8v6eTWlXNZ+Gaql0zGroi2/9beaGr1tzq533hf6HB92RDL86ZYPyVgw9mzm2YdSDF5DGPAYp1tkmfVRzj9WgecYg3Z30LumhVLtCVzbkX/NsaEITFbUE4JfyLTdDmu5OrzQs2qYncrPC3lJuV2kn6v7KTiC1gYHaDsb0bCHgsouI2+e1r8vsk4TUUVwLB9g5RlNMeL7uY0JZ9AGTSUxs07cFqn6MNzaHF9veohOlHZWMn9+3Q7hUhSWh0Hh6UwW2TmG+vy6u22ux22MjhSxmZDG10FphzW0EBpuo+tEZZX6NgRpZT59yVR6o7KGYmqIEQdk6oG1ZSQJcH0itOXuxOUc1NcMPc3BvefhtmeB+4ivmUn2SnbBpGLaX+Y0N4cbrfpXCEkW6OTTFQQb2eLLQzbvi+CnALEE1YxIbobDcVhzzYpwzGmGkhqJvJC5R+qS/s39ARio04UeEzeZjGlTivtkqbO/cUnezHpxQQX2u/ZKLCR1+Faln2zs6EubTXTZH9FVLyGZ4RcbZCN26nqOyAdEtk24hdJGxT3Oz8IVzz2RnqijjL8UQJc++S0BOOCCKsLq6SZVJIwX44yMb2VSarL325za9YKL+f6xspT+6SU7y0Cn7QQCCJ5i9RgE7RrWVVgg8dvJjQEDGoEYwAvcVxGYhHXBEYBkC5fjgqBryBzFUxlOZYBTuDV1+pTkF9c41jU27g8DPr6FfaDZzTYaoKzCX0fjtDK+pHbS1Iwn2Vok7Yk3Aaz9IwaZ2D/NRQ0jiOLJEIwJbKGDqKULF83OQNSMbVSSYI5g9GlJCpft5Lliwb77lgytjWvYwWoY7j8cqSZIYHAwuL4xWev1Dpr9RkX8O4HttxOwSlEZeUAgXr+CKA24ovbuoT/qsBncV05dmOcxbWVUSpjC4oBh0ejTw84M+aJkuPuZp2ZWxUwI/IRqXX9Xpk6QYt517ZwpovaI8KBNvNXI/iaZipDjWmokdT+gzQG/Me6Q+bis5MK52ZVjujBp9BXTIqBYEoMt2gnl6ljchnWQVkkCU7SA2+EJ17iTfbxkDQVchdkJmRQFoHWrNcj3j2KWpUHFSGYcxu0S85BfkAz7DlRjIKQQrB2CPCGGGkUUoaEITDmG2EvDtvr2Xsb3YxrjwF/lNkeLFDLFp43YR/2svRONtuyONtj92E43iuCFlqQRlyyZwr8pRvZ7Ws9Ammg0PhswFdX3G7/rQn8UIH3qqfpevu+7jXpePbfBN+jarRkx51X/7XHUMXOz/fhJvO1Pyas681btv59yb83SG746nwsbyVv1dTKPLOKXKBSX85XVDepTfhnpMufUlvwnLiTO0ir3BpQnYTvh/+q9Gf/r7X9d9zorVctQFuEe35B4ZnZFvh0kG9Lo4pBUQYiPi9kYq3PFr29CUHZEA26h7gGld8YT8csnG3b+jPOOwTbd4Nx+7tEWMVnLle3zWBpMbhLpuYV/gqb/TZxGe76u6YA6G23sP0XTb24T+2JclyP+wrutR+e2A04iNipW4aI7ahzK5ugTg5CvCRpQA/mMlo17gHvYJdHkgYkqzQuCgRTKXdiQ1mIBhWIWObYsvn0CVken4v0PuJAqZbbkIPD6ntcYVbBZDvDeSCTu7KU8OA4qZy8/Aw8mmAt+b45bZ3G1CKe6trb+SebYwkSRdn5dLvh930FndJO4TBAIdApzd61CtCArZhZmwLo05s+JS0BX2WccBhltXjS18Mm645Cho3gj/eCIGTvkGfMHFAfAD0ie2GFT3KJKwoYfoS0h5rVMG8YHKw5QNAe1Gxq0KZhHN6mL7+GuTDrfBGrYHgc9q4ARk83LBTYNACzw4au6y/7AmqyG4Eso3UecoWPLo+eWPoDsEAIHqLu9uQuimqmmBVgv4i8mJVB6qqMTy6VW1BVQfY1112AxD3S2G/chDewPg2YEBt6PVBd9wdhx/TBiye/vIyrd0x5OyGk+5udxdzdn1/LHNAuus3x13/ANOh7n6zqdLHzX7Xn2A6LNCxSscCcgrDcPLwYJYzJVgXPAhMLruqalnV7MAK+iGeuze6kt1p44qNAKwHJO8Y1Ntyi2wBuA4YXWKrVu+wB9M09DWqhyPLxGP0iIkHFLOvX7mxrTxG2kCDrozykdBth9HE3Oc9bYx8PK0opv52OBQZ99thf0JE8SycTmiYDbsvgKU3bh8cy5LRoovUR5WL1BHTt8PBhFitbXrZBn4bg0bdJwiebcIlbbJ0Vq+fQf4I5dmqAWDjDDrn3M0KtZ+1zN3uNiDkLXR0hfiIqVfAeXFR7Uxyd2fVyZgL+IiwOwOqd/a4eQt07X0anuFJ9VAdU/uL+TnIhVLyaHsuDqTYVS/pDiVnk68Gg3H5Lc0o0dE4myBcDVS7i6OO4NY38a0SC5lXXZe4VABn8SoGCegJA37JDlH4oHCR9rHrMJ5VS/HqN6gOffQLFe6k+pHUQmq0ulSmb7QgjtIeGejjSV1jUXvS+FRBBM0kpLtFSO4WCz6T5wDdS8hDK/fUtXKH1KP04eESXxd817sMwwX9qNeP0Kd+n5wIYI/6Woa3GFzjl+JioJdBG5iSMzynH7JLPJ0/M7zIJRH2H1HjkvFHmZFLixk5m/lsv3dJhvrhfgC9StGKkO2bTVdlEjMEBfJJjxw+Uj8oxC9CfCg6VNGdY/eGpnu3qntVFbsTtObRjt9aHR9Cx291z2aXiNKlb9/YvZe7N1/eKyUdWtnIeoKSOdq6ILX0MttJNfB2aRhUlpP06YrkGCwKI5cYXjQxjKgJ+9VIyLqK7j/DwF5LOo7XNBVR36jvMcXNlOVKp5ziUmUhETtaMqfzEd8/ppZZ9dyFaYOsZr5W9xiR17xgrrQdIhdRPa0IKVNby6LAFIelFW6EZQinVI+drrbpKq1JStpTc6kRqk8dRn6qP5zS/TKCtEUGqkMD1QgIddJrTBFgaLGG2lYZH9IGLLTJIoBZgLdHLSysoKtKoh2VirMxy4yxcxuh9JidSzYT5PVmEj7/Z/7PtPf8gn3H52kb/nv453Rzc/PD8wvr3g7LN6thOWRJo9Qep+vqfejHJIn6sKtP2H/9x3+Z9+8T5tlWmTuJdadfGR6g2xWjRpbIOD2t3J6ztvLCVuzuJ+quwE9Sy3wYV9xD7uI5o3fHdpS27AxEvXhCFubaCFfeH2oGtzjTXDr7k7tEbbe1Jy4c1dF/HynREpeQCkSgoW9rd5CCUxjzzHUH6ZlkGUCIXU/UJ3103l/0kZ2hPtuYqs8+gXwWF9z5Qqapwpe6jR9TPuU7MUjTZVRcOt+4WSo6y8bUuVq9t8CHeGMKOFVkyRUnFbLfKkcc81vCLPoMmOhgO7Zu3iTzXwOJhhMYh44sbLq84wRrLxmeGQwyfcGXbfNO7iz2TeUYoB2PT2zVpU8hAxPhA0h3/z5/5mk1bhtjptpVJCDuYzhpFWk3bzYFA0FHBc88QaK8Zz39tAQYmIPAAo3MBHlM/S7VYQb1e2ncEJZkKEQ8OrAt+qvnCep6t44wu1416t+SxiXWrfC9pECL0DtpoU19Ek89z/2ORu+42s37PawTqSHj1JxfxbAQFMRN2OB2l3elQ5EDbR1kn3z7ZLhG6lmqe5aqnt3LTrWN2VWp4S07CwtyeXm2oDNz1199ScNfOC1iN2XonYkdiK5ieuYtf0nZVq5T8eitEKl3pqyOjyxy9mOdQ4cN8oPdiU5Wxw8y58DkfIQOJlykm3VyZd9JeHJTnopTT30rjH3Bh2U0LN1DyjBFFQ0AFz8VHiNhae2dknTqC1itPdfJkVfKrAtvF0eZn4p+afTpymLGnwHDh7n9W3yZIF3BazkaY7WwI+AI2BKXV6YJ1Y16fGkeMWyael7VlwLq0M3pr1zBZt/k6+xzq6v2NreX2B092cpPJZeDM7EXoyH3eYoe2+ZK5NJ8cS+5x8C+DYkCWty3fztP8R5WffncXnxynp4y8SPvdUibTdsRUJ7dnqfLy6qc/t6c0Uu/sb9KtPvc4yF06C/Y2r/QE6otLtLwr9KKz5e6t2PQ7aNS/MRlTVc0LWkP6L9KfXuHw4rlyHhKBN9KCeuSHRmM9Sgdwx7DB0RiZUwc9DRSdT764c7cZ9LLAaN80YqIa+SakZzEaGwZa2PLXN6Iad/K/Hd6Vz5efr5T5AlhYSCvuNH3rbIEUiZ3AfPNFvJTgBpfuM/gZ8/2AP00tVi1PT3RgP0wsZU7Dl/iHYcZVoAyQYYV0kXM2gp2zirEmUVynZjrrWQOKHy/PW7nFl9NFfCErlKwYcJ8JzhhuU8K7kRY1FbH0H7NphgThyK4iOBwKMZ0Tdw9mH4RCloTFGedhu7U+0guHptMDoKdM5kPD3+VuEYMoJmAJ1fw/KJz6N4Cw/A+DduluRsrX76BUaUYMUBOywVwMPmvdxXkSQdldEf9QKZrBCB2VcYi+EDWIVuuT8U2bfrbylyhB08YPmhbX3FkGXUQR/eBbtalz0wWT0Wwz6WtuF7fNlcsbMUq3CEP28Ct5Lxr3O+2xeaGwcUxjKd9hbly8EAjbZBEAbUaS21fiqH6dH7mjEz65yWKvdy2LguBgjAiGdxzuQOy237UyGPGU1rBJrJ4KeAzm+My/kyR6H9NYRjPZCTsdwneVkdePQVwRuxGerHslGGH7QML4Rkwf5fU9s/05CvQ8dPwXcL087ME6+RYoeUbuKPRKilPihIL7oAoqZ73reebFBvnkkLv4HWh+4pkrq7gHUa5D5tVXg//u4E3KIHouhx25OW4okDpL9Nl3avtt7G6/ytpJv/Xi24cApHHzzKow+augKnKf/stzFjShD846rdvTWUPKTT0kCMg4mUumDtRJrayrA18k3whubnrVs4dgQ72L4JHh7XtffpDbBhswOBnwLEAJAGwzeZX2FUJwqd6thald/XHN8BSARQBqM1mAQwJQVd+DD1dmL6zOJ0wRhrMsVL+DjldX129W3Tf9TYuGy+kpgv/AM6nyu2KeG3vw8b2xuHGB49ZN4aQQ6IAG95MTQFcEk4KWh2KHf3adGoofL2G4mIc9LGVy93eMvYqwWyQl5LGRy8Cm2sw0S2Mm7Dw+kvdMwXUMDxy5CFDg+jbO7C79vg43bldhiBRlZb3tk+mQksy9rG+4LI0t58RV48yBYpGdvdWf7VJmgi7mc5qsHjEr+erTAGt1Mt9PAhgBeOx4DDJroP9cibOMiv3dIf35lJurCznXBL+AHY/DgQULzALzMV3sCsi4nReO5jzOIZIzgSY9kVjfCJQ0DPrqr4Ghq4c8HrHlzdlcvcGW2uDfBdL1nxoAt6VQsxR8j5tlBLjqCg1VdVFdZDLISRw3eIVa1qimaGspQcLK0d1tOpYqDrYXGu/efWwIhckgsAXt/o83e7Pq5lZoNrSEQgsNaq5nX2BpLNqSzoiTIH6zKGTR/qiH4wZUZqYCghCgLDETeoAda7NZNgGgnJD3P0jbsomk7fQKHXltfEU2BgmCRUdIx4NlOXfeTa4heclVPGJQgZ1aaHhFfPCE3fBNG6iE7gLUyK+ZdeXRJDJBW7PLdpEiLEoaU7fHFFZN6juVmuNWxfbi0W2VL24frXzCmc10JNktcr+rqZDKZeEWuNeUCpX06NPqMpHdEtLlpYJiJVUf1hVzORKlXdQwRsve9SOseqzW7TvjrIZ1k03ggKsRXRCnu+x+eIHae2wPWsvs1BSbMN6rxkKJnJomEhsaX3yxHU1ZkP8kFtiDycV+5CZlWPFMLGcm+cjm8joGWdkkSL1GOpVVq5ujO9UMaP9xlc0xSKis4XM/NorZLwls4V3/qI+Xm+JlnoZhlEdBCW6XtqU1DoTGvB9zA3jHiUGDYyjIHWomTorSTCr6CpDy570jYxAJg7wT5jNWGnXF+OBmBsLRqrbKyNbeb0mrj1e0lCsFHgjbKLnNYnv5RmZMk2XwWF00HTNRMroLoYsrHaELcCJmEUZ/ObU68lprQEzMkInkqJ27y27QWda37M4bXis5vnL3swLuMMp2lG7WmdxGpsgq4D2Z5PoFr0trC+eTSvR8yesL3BS1DMOJ4aj6o41nzWx+az+KZvYfNZYrIi+bemd6pqXuHMGJ2xbtC5O9IChxVkhFbMLtWy5qA+/niifUKuaPsJL0ckJOelREnwTqFcKUPhIYxMrPJloSWWEUzEUJiMcgkw0UV8LumF9G2NRNjZfy2/GjPcaY9toh401OzUOx7Jv47d9BLUE7Qp2fgx7vZNiv8v7nyHV0qw1JnYcHKtJfduSVaFv9z4SvWe7uoK+NvjvK9Vlr9EPd7LGGKCArBIUZspYMEQ4BZCfNBAIbroVx8g0Iwx9xg7HvQ1t7qW9W1GKQnwV+qgMTRUpim/QN8JU35EmwnBbH6M5x2XmWGy79eyZKAGlP8LmDgtpG/m7PvXE7zV2xRhE6z7bJZr2IZfTS+9yZLsw4t1wUDTEKET31JeCbTWQerIeKxrQL0zEGj4+YvgO2WM3xS0cjycCYOSTQ6XdJJzk/V+ZZD0xDw8np4/O+K0aD9t+YkSvsNVPqdUqFP+7yHWlVqDZDfrm8LVeR7TxzDlr35y9+rpjgN6w3/VVNyqNdd2aNXr1tYJICpd9jWVSwowSJWONEWEEsjFJpxYgDDQ8NghDOX2RJPsyFrLbfhrovsOsPdpxKvyxDIQdcd/dMBBwu9AnvWnA57ilDnMg7DBJ+GNABNNkmmHifkenrfeCjC4+MhlVKYCCXY+AIVg8A+axPYFjdwLH8xOo1rkQXAVRg/nE5iqV6rkbV+du/MTcYReRyvQMIQt05FBrMkzB4aKCH3WYUKSANBdMAGYbmjfTsKumYSymYewvHqhZZMJ+j2Zg/MgM3Ngr0gBl14b0rgvp3XlIT2BHQ0+UsboFN0KsQGBjvV23Xg3s3Sqwdx8HtmphV8KShjsO6FU1OqVGqcXKDPzK18PK13J9nIW77vpQEDuD2szkbKvJ2RWTs+s/Cptb1ZCZnz5O7sL52TArRMk02/JOKqm8C/vsMkRD5M+CBTJmbJdvx0rtfIlq533BXfx22Wt8DvfZvgB+AM+aC8L6D0PEvn02Prk8lUvlUN92u2+Yh/3wsxT7ZsBf7NfrhwtYDGTr9pHTihuHMJBLNONTyvjwMDjTHNQhZBxCnz4jHNGKcFwJ6JuKmobQ1nd8hqq2sW/7um/EUVYGvR8iPZNDsU38sEf7cz3at3q0Dxn7+oDGaZe8wvbDnLpUbfJzeAM1Q0Hd7GfTLNTy2YBJp++rm3g/2xh6GdCrAN/nuc5+tjr7GTI+G7M1rHHON/Sr3nNxVr76swo0rZu45ugykhtrHW87p2QVofFFW4hW43BbiEC4f8lJqhbt+OYU7SzcXozT7BCwIRVHbmYKlw5bgyzlCHOrwC+j+VeJ5ofC6lRi+tdfwvSvT2P617mp+mpN1VfI+KowXYzhb+L4IyM/JFw342GHLrr/wgL8FXR/tHWF9ot7wB0CMY/5hy7mHxrM/zv9Xoz5CXdQH14fx/0tg/s/YRLgSZnp76X4YgZAnldVmcVn/zLLIRV5SkxCRvEs7HfPHFOWM8Vn0JsSp5iQo2QJoWR85d/jwDUQEc5J44zN99ji5SbQoNLy6Ytrz/5tIteZELme7Jjoj+SDzyp8rd0/qkSvWdInipSzUNc9s2ZPSRxVKVMLIGOJjk5z/yaJbxKa3R+lds29yFk/EzNuewEC5Ptyts98ehETuwbix2PCYDgnDLqFXckvnBcGxdz03bl5Uvazp4TOIIQKpTovkGKpYmZPiZxQ4zyoDAd9pjlosYzPHuOgFcU1/BXSWYerNptglYX+e+IIKnZI7DCaCjVdL3uNxSBdgGmpUpDtPAUcnyFYQLxwpK4tUlH/nobPpmhqwN5N6anjs+MELaiE3PZRHv3/IY/631eNn4/xqiVIhQKyrGVwbwX7PU6ULUl3wBvHCZ5unMkUcWmjZQnwRUfTUKaktnsBGpzoTYNOeOT5Hp4SFvWSjl17TuIDGtiafSd3swA2j2TKyhZ8QlrbVNqcWm4H1mWk0gLiI1o6CAjJ+8TxrAZPa3naj4WNlD4kEye9yupI75OtRPQGns/FfZFu0WoY/Lh0lL82nBFg79EJg/t03HAvTeACIFbycInKBQB/OoHFKcX5V1sSXvyu75KvniPgGdcfeOT6MXEGGd7TCII2s7sdqF0DPvlDnHCG5iiBEPQ6rSDcZ0Kqa9WfHjzh0ct1Om+/c2RFFBHHFWXLXDqsI6EnWpMtu5AyaIQC3sqEhH5YIrMxcqdVD5Tfw7hvtp3WngwKJ2cOMKarjtEM6lKwRTPLqS5LoaPZgnNUDJ3rYrVTI3NqqyBspUrFXupb4q2DH3PFTelecXMuTVaEb9pnNK0P78+B3opboqrnkzTX76NChuQT55JJNJ9WjKKcD4J7acMgEi0Qqy8JhWaMD4d43XZQucnmvTTgwFFaPYTZsvsrLrgvHx+Dfp7rv1x0JqU6GJxuJ18OjLfEg+45b8kn2yVgq7QdSYQzDV2jxWnoaBoRXdBFp7R5CaCgNHUe9S/Fm1my1nFLWjXXs4benY/zUlMqNjRwlP1OeX1FLaJcmZrMLSBxkB2WgTzSriycEo3+5LeAurRGUr0hYcXWlDOzLFW1uCxzEYf6sdrtZWlaMBuL6wxUumhiFg9myZE30vpa583aylqb7D58BcVSrFa0MdO2N7TYWPqAt0eLbKANcSw6YXpxPHUtke1OzG1u9s4EELLLpuQLpu+QoDUTqsnDw98qtqZarpCeJlloYVna0s8C3wRBIqQjMmEQryWfLOxrqUcLB7uxmsMEw90EcRiLucqYutdBuZKonrmflOaTUuwUlNpN7RWbP7Fi58hNrJZkXl2SuV6SzCVwilai1X91fStZvzcH7BDjl0u8rX5m8zqfk+om5a5PQYGF5WIyN6MZXhDvdgmPyMXQ9DrF2Et66qu5AmNEKKWIDcMp9bo7lZsdYpU+bI/DYZCJjCGkTumzWwdlbw2+3oa3Dr5G8F7tLIa0J6dF2cLtHByHQaRarH4eTn1i32M9OOzPFUb+UhjRzcI2uw1hWGIoURgrN6sRhqRDBL+BB436xFDm9RHKnyP/vjIc6oi1Zm7ESmnTIomcRRLNL5Jo0SLxVfCmDdigD8JIBWcahRgHJKVQFBc6CBr0biM80FUrdzbbpgKgEG4ILdsNu2IjY4gD6Y67eW012JBGXhvKyOvlixerrx46K69FiXa1xVG4qM2e02CwAcXEjKjmruRVj3Z/RAsrAaJ4ezYz0NEwFxPk2ts9hC/XoPpELVdsSlIMlRSeRKfBSDCEEaAIUY6bBfM2+h/MG9OaqcYwvA1v2DS88gONJDcsewhHKgYjbVSRzUPPL1OTL6QKwE9IGolvR9ZyrC75cMQWLuqZJK5oSQ4tWooo7CqzVkk4Zcn8wptv6JaVptv2Zm0s4IHJRgt2GHwiVlci92lpzI5h7ko5JbHpka5VbJvQ413YRzMmGWh6co1Gr6wd9ePUMaEqNW7oJyl72c5faPJWmpidpR2zsxQxO9W0I/iSJWv2TJaoGO/+kusieUoP/qZDHh6JWC0gMYi7JJ7l6OnzvUTB+1kOwniuno7Uk2HCL20/9mdz9lmv1owLiWWi9C12rJsz3jjKpevMu1z6fHwvGbbJLStfFTSk9kZE+up0Aox/Gs7dROMj9xeNOV3ffbS/FfxZiNCLnudX4jNyCt/0umdfqITMLRr/WTXIszEuzDXJi74ModYS+jQbYG9Vp20XVJ4LByPMHeDY6OfIFs6+kKETgPEo1w4uMswlJH4vjddLKtsTesiuvKqnUYFZattd/Uki0jtds5Q3nB4J88Axeaq1rZk9T5QNrRTdu6Wj3ystg1BpR+wuCmL8LBkQGBZjFspSbVaRipARyvTSel3yfHMnodLo6nbfoL2eYwAL4uQVj5K9fMBzK9ogfWTMoxfU6Phi3st3pbdCZJBJ/w9379ocx5Ukin2/v4Lo4YW6hALQjRfBbhZ7KRKUOOJLBPWgQAxY6K5Gl1ioalVVg2yhe+NqrFfcmNhrr6/DjrUjfD32XWl0Z0ZLSTOzsw7v7yClb/sH7J/gzDyvPFXVAKSZ3fV1hER0nTp1Hnny5MnMkw8dI3EqrU1lTCNpSqt1/jacZDs6JKkpsC3OsBvJ3emm9CDkDxpLNsMZ952QQrNpu9HQsjRttINL74Tc0ecdTJewtPckSR/fwLwKFHX5rSDN4GuZ61lMRH/mNUQC86hoTHothJ0CezlI3VfDEyxN3T104hkHglJty7935d/XyUnqlRT/fSuFineHGNdYy6SBdl/XWp6VJkf4n4Y6BV85eZ4dH9mk7SzESkZTQMp1F2Ouu5jluquw5n2XRTV1Q3GJEWNATLT6L2wI6ZFi8/OiSB407gOtmfRMasDiYSNP+lvD1r0hpjSqY6wPgBr0Lvz2UatmYLiyfskLS8oxvNgMF7wmwn579tDYgO6YzuRZDl3iRmB1XoVGoDkTAUyIHfJZLL7ocGwteV4aX6Py4AgSGXQ08N5KKei1rEMzZRWf5LrisQU9oZowApt+pEmLx/fNTyY/yp7u6gUYFxcGXgWtu/hXqg9hrszgMRduets65Isc4JiLLJYZfXnkgmghBAUfI2jz7DG15Fh0cjfs9y4iJwwjaCvHi2CGDrWJy4A13RIct4sKNgPTba63MrCVxUL+el8VvS/UB4aV/SEAnupFYfxXaoVAUdbeloSAKukWE3zzxOiocZ1cpLjvK51UPAs6uImEPH0PtcvAMt4LeqNukGp3wO2AeDcz7xBzRRpROOSMXGSJjoniVaVOSegeXKW1mLJWPYxeFNv8ts0lKv1U6jENRVtYsSdiJ0qhtG+EUhBViTKRGLoXz1OwwrFjAgAB7y5F82MpevoEz1Z/SfxwB3625R/IMHJQbD27AX9lHrhACiMufNXhVVsBECTVm0j4J+VuMaLxn31E7ZEWtXwQ5I/cxEudlgbEEZB+pTsfuyg3jKd9mALTLxlPBPwVOqZFaKol2/FdOIPS4hGC4d/lpUvxcMG6RoZK5JNAj5FrY6n8Yip9PLjwFDDhKUDhKVSyk5lXSPMKq6SpwFGqseJVAopRYkvuFIYOg+tJ9mGXaYr/ubak6c2NzGZ0wyr+1eyguKyigg2qAEBwCuqhmyhUdDFGNwMO2jpHThsWNfwBixpay2hUwmylQ6dycUNJGndCN2VQfQO9j8zjvqX9HaPKl2ANgjNmZPSQEUoLqxWRAB/SPaQ9WjhIxTSQygi6nib1d0Ys9j0ySiCrYDoilyIWbMf+MBsklHt5Mgknk7uGe7hboP/I+c83pfeQ8gxorG26b6f1i+7rxX4iFClleCZxg3x/1jm3hj5HQOFWG5PJA0yRgLk69WUAE1y1bM8ceFc31/B4ZJMBAVKECoynwA+NCzdA5i4BiVSWb5FWQF4+5Qkw4UqpY+t+kaEU7/GWEW8EPVWgxakOr9LS95BcJmQXkTKiIeoL+FoALXkfQ5PNz79qRxp4Z2TH7zsX88BS1jfcf83y/mH90F01jYAFJsh1ll3iwvG2Quf+1fw3i/LEGqcrlqbTNpcn45xSZTTdRZ6K4Kfsmyc5y+prIrwxpzB0T6w75b3JNyHxR2e6MHQV8TEXkAVC1XotdUv7uRWgJxntKXl1qtrxbg4Z3o8pZEaJwgaMBLydGgTQwTuOUfcYuCI3IaBvL8jyNBm3MF/gMGulnEH782K0qe8prk6itnnD0buiPib6FDc4rrq4d+W7tNADxmVk0htSQ2WuA4hgQ43hfelehNBmrIkA5ra1sQOg3JzgJ5IApSbjL1nBcx3Q+6XmE8JKr/RRu5ihZzso8IxFtpxoNRxFckGZ5QnIyqmLL9Bz+Lhi/DiiEBMLK3OSM803tGwPgq7FjAMgN1cvNjbWN9xNN7B0ZXBS8JoAEyLtxWp5t1htzV0p1Ikr6qwVu+saTUGFhOCYSEKYypi8uVkIPWGRNJVSFb+vlh8YMd76ThcXrsWjbpGwevGcusyTmaaI5gv5VEwo7VpJZWCI/Ho5SqyTPuvyk57wK/fyAn7lmkWycUjFdzIutByLQKrYae46nXSnsYtmKjZi7EC3uy4/RcJ/qaGItTh9QEkR+sAJrDQ7dTh/4GRE9iz2rvnQlGGBY2KBY5dJuoKBo5AHuoxuiliNpqtNpgqqIR4uasjhk+KcERvw3mntctyJW4DLdI8iIPRqyHKvtq0nzD+LJ2tAtnQAaB5tJ3btuinDR797NsL4wdA256CglYI/F/JXquSvouCFURvMU0HX4o66yLR0u0iDTBr32GNWVMyAQNDM1wIkybnMbwXcXq9LjFzKzR1uVg0Y2N0/ZbQUFNcMOGJiaFi0oFBXSOIWMdQ6vtCUO/Qir5JhXK0ucBTDlBRqivPe98J6gqBDGcYWcSkzFxNthaTpu4mjchjZhmlG3JV6iEhbpMkCIbG6UnCNihZpkT4/BB/XR7Ik0XCqlzTiS0rxmHE93cr15AtKgNfMpYE1C4A7DkR6LUkvqIRlZ5PU6JXUQ11kQ9vGKS1NPNOiyOY5KPO8tihisQe77D7QGPD8aNMdMpUFjAVWTcUka4UA/iy4qu6IbwT0KF+KJ8mTiYcbh0NKtnoUiLiYqjjOgpSCovPKN/1xMsqtIgyPJn8qrlU99eUvsVXE72vB/uhAWHKqgn4AB2GPl5kMrKqTEcX/305GaVdV2h7H3a2nIkbbNnKWauQ9+oHhxOGTvTC7HTy5F+DJCcJ3Cvt36t4anggzE3WXsQ5PSrSPjo/iWQXHyZRDXDQtIRZ0KyHO+zvrwQ/sE6LPamNz1vFfWq/KaVntEF9UtfozP1UMl8YEu6bYQJZgVT7e3RMP5ynHrCKkxMax249NwroYiBO0X1ROML0nxpf684lrQYW0lk/dtFpa+6BCWkvL0praSzwWdFlgRYtpHe20JKAKKIoB/XRU2IdRUt6HFYGnKzaAaJbt1kI+uMD7qbDdx5C6DZOJ3bvPpx4gn+ZWNQ/Sq4KATQFYP9NqWlCNKWPUwRPYZBQsqT+U98OFcFMNDEwEu7Du6LBUpJQ4s/Iopb6nRQEplrLbsdQMuZa+SF8vRRJtQhdEJ0t1Bqc/U52drP7CunFJ/RVPJaksrRhCB9brfrAU9gCbwn4YpHfToB8+bbPIYTHGPaQQhzGGJaRwhibCoWOHJ1zAu+paq7aQL9Tu1RYwRvFb6cKC27iEVgv5gld7DUqtT2AIC/iJCkZ9dwj1dSNpsfoCvNDYVVTOTE8+DO6dfBhk3ZnkPE0qyXnUrSKhebdEkOOuJpthl5O5PFGb/t2R2bhsqRSxS+qvpYL4/oD9bBTpCmQJMqulW7zAOXV7iwHA1nZFg5USGhHyik38xqh66+6PJG763ZNX7s7/91YuPuPKxX+mldM3yJ0SyW/96FWN/zVX1dh4DHOtl6GMJ9JOSwS4O86l5WbuSNcl89KYd2CA48BB0w2eZJme0ajDcA0mhrpRkyRMO16KcReT/QPZYsVqBVpyRJg2piTVu7a8Z2k/GT8iYwX/FLj6MLuVjGIMnFc+jSlQnh2nOHM6Y6yBxkS0PYKYzo9tOFxsLFT3FuUmpB6B5K1ISsShdyMnU492qExQ0XZcJWSph8zmEE8O8uvAxDfcc0Fq30m0ezsSv0F4U2O8J7Kk/NnGiXZhTfefb7zXE0B1abpvc7yzBhuLwRo1ww0UZ1NH5iBacfM5Y3OqB5jrAdJIygPEEHpqgCQNm+3T73J7JBDqeUh0E5dd3XQsAfcxwtDWh0NMC5ZL01puKjGjTj2l5lsssBza45koc2F2d5QGwgxMfdqZ+yAlAEwm+AstplrcmmrYtTm3uSbA7N2c7mZZdPzCfU3IPbmVhqQTemFeD1FL4b0RgEjQOYhbLLx4ajeZuRhp2UulMrdDAfojp/WuWIzgyblc5CQqMRoCqizUoHqW4ogsaFkWVqn3U4w8y0PE5kUMAk74Tw2oH/2ggPowO0YJB11DCfU0XZMTSK3p22EUATEP4ECV4X7tiIozKxIimAbfvL195frW3pnbPa2+bF4vB6DnT6OlCtJTz1Utt+i5mpTdYXgeMXKkByIvs4uVjgsRHhKtqclXsa1ScXFUPhGHI1VV4XJYicumGqFt6Dgzh4Tdg+BxLUgBTqLsepocEsRcNhZ+/ynPxBCpzox22TrOap03qt3QI34dLNLPlUkQq1216HReVgYkjZZOrjiZ1HOVGs494RsbHlU16o57+hiLzcysWBdEX45sJt5GajWkeEfeW7E4NE5bJzaJa2GvYnjGw0VqivgdnLSwM5f2NUy+R2Yw8YLn90EedE3OeJU5QMVpjaUiOEQbo0f/huTocweYtgfY8vjgHIwcGPlzjxZCGHyW+QfBwqN/g0/0QjJtUowO3ExwoLkrPovcXngQZCXH0yQppB4sfC/vqsl9UCngRKFsTx7VnVLLfmKgAfswS6JgKSDdQK6SoIr5xidn5IqnUrl7Q6cSezvwH9/yh9Z5LMtaGFdUj+FQH5wx8hiLTdyrwp161dUekt6xDIYi7y2UBlqYYGgnbM2IsCFmGWyWLMPLg9to8ObKWU/dmIVPPGEUOoENMQcVRILQgIVwSgtuZNJnXozVTKksZQFfgcOqnIYa9VRdzhhyLr8Oi2GCQ2unXCUlnr1TTuxK53GxKVQ90tcK8ItSPwd5fScfhNmu04riJb/Xq+OTjG6ciBO4+7iNheUhKVxzj/WrbULmRJ0lSatWm2JcIbZgR92iN/YQNuBVvzuwfbGPrVc03BtDtZvF2NspRa8luylpAUj2XRhfOOJymP7CNV8gQzzw4cjGu9aI5o7SlPeOpTEUMlYuc9+5llXTAV0DSbtVK9S6cFGRsdXtw1AEWZeCtPFNkWUtcZ+rs6SxwBoyI6f2E64OXdjlqTiNnori9neYB+H6+uqGFhRhnniFjJmaOnaVlvmkubLpagu45mqzcWFFPc97i+srm411VwVTaBKGFuOBdcTr5gUMbC32apNgS2IJCB9409akPJvqyhvey1ji10uxxO9QYG+822YBGt4KuKWZyHqg+78ywrtdaarntF6NybWJKhXSDL1nwVGk/URdu2angMlSK/BuTPgEhw9zzCAxL/bQd4DFN5nbDzqFyAvFSA0KoCuN9VW1QPPeX0bu9VzcWeIlKIbpiufnr+OdqPoGgOXK2dPNpZw9m9QTa1LcAl/eG8d2do9KzmzuZgKHKPCLliKEbbaYCIWfGtPRuLpqR7pVNdcRBzDqVOg+7kq2LxUTDTCUlBiWK5XM5OKCYgmF0MEAUrkIGM98p9TCC+dolZjLndOBZCJH2S6FtjuXihwg5+DS1Zg6mj8AIbieAKKgkogCuYu+1X7VS6SVPGZpAoyVjXZKpw7aLNjj0oLN2QsWlAcPIm5YMUJ4QzsFurHypKQAdAYVivCg83rQJtchd4RJhJ6ZIB3yU7c09VHCxm6mtFeg/vZwhM+pCjQWnuyWQqcFoQI67g3CXi+AQ5vyjeUqU4lTVFZS2ISb8p6tiyfL3SSJpKCsNZXKdDABDhLWHJVv+cSLtevKXD2eN9lYHGOHZTQBS7qjCZp2KHjlLCqNZ5pwTxhncPo4K5yo7KGL3dP+06CRVk4PJmf3lUonRx3DHb7j0Kh0UXNa6EFcaEg7WgXSXCPWZI1ln7YsuwRlrjPioxNh8jBTMjUDHdFqj643VwwtXWlcvNBcX+GR2TlCy/33BuaAtQVjzdOhXJyj9kadD3HpfEj/Oc+HdPb5EFefD9tFckPTU3Oda7SvY+YG5bku8s+oyeVMryRpVJAJZnRIFlVI+cKkrpqHuUODysjKPogSj7Xm+l7B47adSCWIL4N2JEoV4faJcpcUHX2u6FCeMJ0+Kjj6CK2qdYS2aAH7khkeQ9MzlRhHSngaV+oQkrNoHNpHrP5piqZK1UOydKb6wO5i1JF0MkGnqj7sgQGuEJxrMFnXhFsZlJxTEingD1DsRzYjwZUcFeu5snnMjT6aTEwWRZhg3KlXgUpofBA5xogopSYdtw54EE8mfYFMPnQ+cEe4Op36aYCbraxJzqCsmV3TnkByooomOauKJjlJRcOaO02VkhdVKRg26kd/7Bb2oFfyEvNGjqs2Jpp4ZzKsht6cXh/2vP+njQK189JEwiYSMvJZmVi4yGnJkKY5j8va8VtDzDxFrKVvxt7HzWwzJQNGYUY2hVHrMeIURvkqdkZIYUYw5VElhRkJCjOSFObpCRSmXR8rEvP0x5MY51+WwhxJEsApzEhSGLdEXdxK6tIWgYGKlIg1vzWTwjytpjBPkcJsVVCYPqcwfaIwWzDgyQSQrlMfnwI8Ae6zgK1ccyadKa9hidDoq7EtAu7pQzwLvbEanUF1qlpje5Z9dQbU5J82G8hTzyAUVcDDyB0FUQhQziqkNUYw/4Dx/emd0EzOQDu3qmjnFqOdI3RJ/v8RSAQhVxlTEostZmbSiR3oQrL6UjlpBVihuAyUrg246EQJZMBSf0A6HxdtAyWHHKKXEz88rg9NSnXlnq73bjxTb8xhIV2lpJaIGYkyBYDm8pOOCv/i2SooMgrVnLl4KYK24f1aS3LvvqhTtDQVtzJsxo0KYWnLslw3imh94sl72Y5IRV4oLRVQfleJo3gtL3/Oz8vP1amJ0Bfxl+xg22ytr1ZpLjELoftmWI+YFLOyvuEyLZ+eI06qn1ipVKUcy7KpkgpJJ1RtcBugxNi8/CDhm6VvP023cWjOfpSf3AocdinDJDC7CaqlfY8LrZURWeaarXo0vyI0N67fqaOkxuTE5spFqdutDOyiFEqoJCd1K4jgGP5yvuno2I5qN10hcTIoHdwBC9Ts8TycxrG/o1UynYDHWuoovchmS/0yShFTJC4bW/WEq4cQ6fsqLF2IagapGAyVlgUAfIwlLaUdctXHrWTqztVTmCW7+UaDHaOcaaBVD1eVJU4r9PoZKgBdZd0aYIR8Mo6RBaFR6DHdXqijNAV6d4fqV2FdARHj8v7uJ6ijHyAHk4hMIlH56t+sAwbXY+ugg1+qpbwmXJJwKj7pVchlE2N2MJgmCqCR1qBS0D6WMGQ0E8BmoZSZ8Vw9IXib0FeRWDTRdGoDP7WBP3JzlmVRokOKOtUI2R4gf6N93OnXKZhjZD3ON9c2NhuNDTh3fGOpA58iJUX7hruomU3YGpoMgGw9U/5TradeRTi4UZfKcS+oWl+M0aD2Li11i1GbxNKTnUB5EuZEl03dsIAtiWuhshUe/i/jKuRKp1pvpdY68DTauqnQXsvFPgqxPKhebcdl+lcK2CC9iZxqCFpxCSlmG1tp44Rd5+u/E+wytdaGw4IOsPWoDIWVMmsj2/s3x809c4Y5RsWl3aD2fl7Or81Mmd6NSh7vxn/0TYzj7JYPf7qApE1unx0s+Uhg8l1Wzo9dH8hNzuzy8HLDsVmTeThPO3WmV1y/gLaDSV07J6ys4JWcnA/efuBJX3WIGLZGTSq3Lg/FprVITKRITHoi8Bl6wVTUAsiNG+HWLWxcjmrhaftWYev8fHlFEmc2mU4qyXQoUuiyOwh1T6ggKOM8o5ckDztIYTVlcBeZkBktdtgjVc/wFtsRbGq61DvItMo59YCgeSbf9kXHEUuJ12nS8YSv45SisAJV5vRBcCH7wLP7ckT3A+3Ab6Jnzi8mMi7mWivyVmSASxEhcwNKNnnJxpqMnbmyKX4Azokf680V+QrEAvmusSZrrTUuymqbzYuqHkY+ET9XVy5syJrirlpUoEsr2dTGSnNN1l5fWVvZ3FSdUWpY1R9p+mWXpGmSn2yubm5uNNQ3GxcuXFhpyo9WV9fX19ZW5VcbF5oNqIqQWLVAAaPavNC4CJMEGG1srq2ur61vFIKBRl5jGnkREspslFHGCJm0IHE6DQzgLdyw8Q+hsmBeyb5YP3mRiPUekfdqSgbF6B2jqPoHSd0pbutmYVvr0DM8Nmanbu3h0gb33rfMJdAITNh+3sOhebna9XiYME7cFfnSLewG2oaxwkUC+35eOhiifCfLFxZ2vVu5q3/fY7+fxu4tFGvCnnsP/yZHQdqPkifu01jYNxNlTXnuKS36AqpZoRrud03QHSuDhR2f3kqbYrJXYP4WHTzTipBwWHGLVIqe4VXfeqKh/itANZ/4aS9Db0MSNrU7oX6EyqmI8twgN8EWkDg/jPAWEv7cQhobTYEQL7HmJJ2ULchY+kvlJoktxkYx8/kStoduwkuqYY+L7x+cftfLgqe6eDBgO0gZlSGD4faRfeACVAoyT+oBBz9ZsY4ZE16QZ5XRMlbQQpeNQAdXDaz4ssx4p1pOmp8nxEA3N3N5pYPDOlUv7TivgR3nVThqluO8krpaxw4XSYAqI70GxUivrMA0wMyIgmKUV42oZPStorzOe00ch5AGU4unc6r4DjFheTpE4miowbgJuWoE9FizxiJXQzs2SV08Zi3E9jzFA2YxspGJROsMJYLEQPcMhxjpDhSZksQnMp+4scV4Ou4h0gS0+ccwMg4j3rV9tTfU8IU3bmUn7cjGIzjGT5uPsU+KVO4vI1y5kRmmi7MEEWwqhtqQckphtHlyEOSDIK211IwER4n/yLO/cPBULaMxXCkojuRl7rHF23PBn1l7Sa+DUoGWqkyzUiUXO/aOJWafJU+yE0Y5FAdFKgdctMXgVhVOMYsL95NQm1HuuVLqzVUhWxejM1s0A9ZjRLaAFjkzggcsj+Y4za5luB4YZDSIWd0ma8lG3JkLdV4HG5GbMWfpE1ZbW13U4aBezeJTWm9jHDWLi2uh1b+I+z0/Tzf//PVa60FonBjKifXsthotdQKQJe6e0gtGxes6aWqbYIY0oP5W4i6nWKA2juxktUXsalE7pXlXxSWXrS47ks6Z42W+6VQJLxrTGL51tNqvVdlM4Gkkd5k9k1zMlhAGKj605nZRzK2I6sSr8+ONOHY1U33+tvlUKEhrCUpMdxQV+YBInvMm9YKMbCYoaHHssE4yQjnfiILXVoz5aqtgR9ZwtWXY1Lamk36KN7vuOHFvdd173fbNrmf7wpko2+pcjy16rOxS1zHUu0wzCCe8P8TdRv5IdZb9iwX/wcpzlEIyLpCE2D7IY9rL5YOcncPiGI8rj/G4eIzH/BiXLg3CDJVO8bh4iqs3nKRMpwAyz4pgcMuGnO1mVLIlRKTgnlh0W2JnCJAGsTQTSXBiefqH8XCU10C+eTsTkkkqfqEhEMaMZ4dXFkR4dQ51hWNr5EpXCXFyUURe8SYtvSm2hYTFh0ds7YHu+UG5Z30OKu+UJL4ahZh8peIKN1VvSx4rSdyl8nvA+r4jPMDEBVVbZe6DZe+jf3BExokpWp3feYKx74dBmo/raIETVRbu9HelbyAGRPAoteY4CqRngu9hBWo+weZ9xy+2kqACLqZYYsdohb+T7Hq1mjThQkOoWs+PD4I0GWXReDvIb8RAvl+7f+umtJOqKfZbPWej4RAD9pMYF+dbvZDcq9/201ik/LRqvUZkFiBVeO+P8uR60h1lCMFRBUA6IYwZl4r0RSHlUBUavr6gxI6BaqpCSKUIDZFYRrrNIHxaKhZiFYTRUsPHMczJbedLeBcBjs0KMiNBPVcB68lkBE1WlVesgVm3kTOqWjcfKqL1BPwpLyKWymXENxpceGchwYRm9N5IkDKayqyl7mBE7c5oaW9vkB9GCl6+53f8QtlIeQ6jGn1EIcPstRk5Tov60njTUQYycywvLCuT+WBp/HZbtdrCCFr7s2BdNZLV9XxozEm83U2TKIL63aBey8QD6jRCcdU9UlCuwEpMxBWXICLRB49gup70wnbBvLPv2EYTQLPvVdFoPM/SQlWWEzTVAQzm+oEjCXGgxXJJkeUlTUt4qYQRy04hzIP1oZlXZCWvSy1KzsQvzc5oDqFl86mcMncBnv4wC3q1VlwcQVoUCeOqEaRFATDVQSlQ8qVBVAxJ/LbG1bIvINgt7vWgMrgb0yboMsnwa3HCxVQTqTBEyB01NX17xoTEWGWHyiYRY+jcdDLr7kq86ttlkdEjROxmTux56P5P6pr1OKsj7WDEv8MQclyjjJGeDXivDE9SCike4pq0XDaii9b/yr/rLZmeTWVlor8XpOZW6ZtbVuqmNcV1XkeHZtfwpU31gss8N6K64xZrasbVNiFBmw/MbfR2QH/uwB/MhGNuMa8qywjMnCWNeoovS7Ulg23d3iuUU7f2b6Jk1jFkoTXzqn8pzK5pyWd+fq7OboCcoomO22chIK4m9T5AQepkUW08lo6GRfCst17HlZNueoUMUyKqlbC4LAUvQDDKw/cWNwVxlbE+Wfafaq9vXDRSmQmpZJdekPk3NkzWFzMdoYQrZMMiWKNjIl96NSXtGmaz0RKh052n+S7eQO3cSHcx0JZnFCkkxSmuuRf6UXJQa+ER1PXjbgBHEPCt9BglUAH5S0ZUw37qHwY1wnBlgioeAjhZe6Ih9FotfngU9oJEVvVHvTARaq4IyFd06VaqshNFCwsONHEr3Yl2Cy0IL2bRAzkfl8Z2eCA7CA/9AzVIoByPCx+5swbZC5ByZ6J6nhwcRGUACDFj6GN0ENFSGIOMEJYaU1JGurT3JEXZTwYjPX7iZ7dAEgiHUdCamwuXDuXD9KTWjJwxru5aSAOho9Sd6jwgpi9EljIs832KtQ+BwWsnlayU72lWqpMu4TAkXySZ2XBpFicEFeYa8/O3orr1nevTxY23Y/pyfWC9WX+STZufL3YIXNqfrU9sC7ot82rEDiteLTmBVwOgVwuhI8w16B7SQqFlXPVCiloHfr0ab8QWG5Lo16qUIcMZUmJqSYmpV0x7lbIQ6RaLJ03dI52OECZ/sRO1QFhF79drMgWhK1zg8nzYWl5+8uTJ0pPVpSQ9WG5evHhx+Smy8CKnwFMfHXrOULsTk/DTTcNhDgJCgBbwFKZfJjusA506qiFxDpUc4dUuifqXLz1clr9qMuH6YXIUCDWLzCBOD45GsRTOJobV5e5i9zjEDMVhNqXLxNJ7xxUjFkuFKQoxlI3eyZ1E/wR0JObvA7xCTeiHJ57hYGuVmr69TSFw3UAS8IAIeOreFIfUXJOMG/kxE2A+W3VP7r0vg8KcRN8Di77Tvkh/JI0vf/xj6HzgtAutVND6inGejd4HnN6XW6mk+RWdabpPip1IK5csMhxYO1lt3uLk5AYPTj4YUnYwRDP1USd0bx0ZctQPThy1MRJIxXESoY+LOEtCoYlA1USRYIY6rra/E+7ShbIURTuPsecRmjL+YJWAFpffE23IRsqyPtvKlI3TTFz4nNVqQNCHqRgI+0ifMvROKADCMykAwlMUAOEZ1E6h09EzDE9UCLR0vXsZhXQboVHm7KMnoKMHzUTnmrOPnoCOnmqETcXFyJyWQbMgv5IDjPdHOQyNXtIB+jbmThIRYJxqFDd0kCE0Xb6LYB6hVJ7dQO2DXQeH30qVN/9bfETl2nY9dujO0rueqFktwBamnSNcOKDLZ7UBb0qz1QhgJeAWZIv8UVWxGiK6KUynRb1LrtyHC+d1tQPxDJFzoyWiTZbFn3viZCno45H7VLINC+9idGpnE3RIBrPlMrdaypGXabagUxiSPBPJpg85FIkSwrEaTsLcWIBzpZS5jiS2UH8JIl894FJR4ZKyAJIz8ZwnND4NK7itc3jLZrNbcSu22S1Hsgj3Ab8QNBR6SsKCr0M6a/HFXWUPL84ct3xleZq1fskZoHylSeuHbuA5y0Kg7RznLL8D5/qoLu6EDfpe3FzfaJCXgr4MCz3CDMaqpjP6DXie97lSHtjmpkDEUvY76I9FGCg6clQ0hGGzQwF4sXg0CXt2tMYVAQeY8VdbrA/OVl6NzFZ+uOQJL+ZVsKQlK0QVq6vwDkaCpnQqsUJO1usKkgTS+szVNT4aghKJi0c5eDSNZBasTFfEb5M7W4EMBws/Vp0W2gOSrTSTQiolEKbhcdTNv9L6oc5LKYJMwIV7aX22VUBJYdRsqOZeC+sF84CK2hfOrKgTV+Zyi1Vhmm0VwD4XN+0lx5zEY/ZwrrLhd+jC/Q0MuoJnu6LQWwjNhh1zwrR3kgla4pGRkJtYxieWLYLqDQ06regQlhBpy5eWk5/tLIEq69itujoPPfKyUXbV855UPG8QMIyBk3ZoKHvQyCgxyh9BGRYWBlAIwqNqVZi3h+VIJ6FtK6R60FRY2Z3zoSVc861GmUiLIjVWWalquLYrSmn8SeGgLPlnFLHRmpW9qKFwX0+k3pTSqpnpFmdvYm3hJcexyCihgvQI0Vv6wilJXDxOC/cqMlx7ySxlsmI87rjBoDAB1fj3JKg7lyOLcULMRSbLoK8ih9Lo2uQ+mEsdOkVgJyQOyw5L+txic3EhREo8axPE1iYQo2g4yopVfcRsWU1UIfQpNfgOT/2gTDjE0bHyMs59scp2lgCCwzMubj8MQG3LZLdD2gtxh1W0SUwonaGw1HVNBCtdP2npmsqeN9GsqrWWmF9RFFgGwrmCm74NrDYXfiIy01nOPjG345UWpp0YcasVk+EVDL507JRNh+4Lm/ZqXxT3tNO0eJ+K56iDwY+1oyKiO18oMSSbeM5vlNpwWsWjaEUfmaxsnZdNi3aIG/Lui5lKvikztM28IZt9iYVnjbjHCySTosbsBfOLWHJhQtb9IuVP4aZr1t1WqVXkKwM64M7SvgYA3RjNYo5P8jadxfbmpciDpewpmGIeecWpjtz1IyAELIb8XA7WvLKZpLOyOycZyhG2z8QpLbAynCKzuTxDR+NrFPrtFSvG63aQl2K8QlkLo2SelzcWOrllymNs0Y0cSZJzhglS7vJWvBEMTxvLvJU6oe6BtO1X9it2fkoTJ1QHzxWtVLZAc7zTtUNADrUxxN3QewKkwOujgPMexsOmYqklgMpEqGqUu0HM7TjDkhYqWHgVFygbL9yKe1MhGAWt4xgkRnQ3sUREFvbwrTB4Mpk8CeNe8kRnmMQAC6o1rMufRdagFAWsFDV0V0VcIhBZoa90yY+7gyQlv31xz6qK7vT76B1GHnao5hAxZtWTeEu5GY2AC1Rb/ZTwvU3xLrXpxrlgKsItoEnQYtMd4T99eBrD/0fA2Q2k7QhpldttY4z5tH2E+DGZSAemI90TPK+Sd3uyAAIV1gonk3RGrRHUAvn6iMvkq6i6X/COjHQv1diOW38KxeyOQTOzA+8Ixvu0rYYJED5C0w3pJEHxWzF2BazGwgIaBAk/amBAsDTE0rEnDo0RllJH3H1JbQahsjjyBvDh0RJGkYxJSzCF3qexh5ZEi83JZER/JZ8msM4nNBtNVS4i2hAxglBWaFCFhlWBJnQ/9I5pkYMe3ltgiGmFTPcQgzAp+JMIicB5L2+fNxw+bFzvvGts2+uF863ZkPKPsfZ3mGP7ebztUNYmrN1jaFXn18ZYO1ZCSi1bYSyQCjP1glFHc71lGaajfGUFwtz2tgoM9w1WIo6NoaXL6nrDWSFO6laYKAobhbSts83iRW077g2nPSzmOagMctOdssGvkiH8oTdTQm4fcjxvdg75palXq7Ws9xfn5w+XepLkyAsrLOI3bqUKBaWa1EbKY0uJ2QVVbUGTuIoJQDS5OBDpDKQB8m1H2E4YxjBg3k8Kc1RtRCDh/XJeF6pDecu703WJuLtb5lzYT4sGRIzJF8K7koM76VIxM3haSOCK1BE3cbuXIEWoU5KUeZHbRvnnRSqRdVv/kiFsKTeqCnx7JMJLhQ66c1KjMmazMKNmJmZxZvJA2+O3w0MXB59bgwcoerk1+Lg0eDxphK6yHeuxA0uCVIUPEU/ynA/xIOFWcPK8L/bPI8hUqHfXW5iBzsYkKJlW5b7uYLrrFkthzcZyY0a61fwE96Mb6OfiaMd8IRIUxfagEIRAeQnIwN0sdY3piZoIzuWodXT1w42UPdwL2cPtIXvYHu7SoGzlhIkUoodZ1GcEVQqSoNL/346boeZZ7LCoRWH2j12WijEwjhPq56r5ucZiDdBXgcUEnNVlEXuUZbbzFnNbJJXZWVwX24H02VhHAUH83DA/m5tqENL2rWhTx2bnKEcO9KCc6bc5JW9/3Z4JBmyAznD5aVKMfg+dqbj3AOVUuKbY2NexLiQ2OzHjKpZCSiInjhwiK624oqxeaKKOmYRNK5Q+2fompghspDThbjKBIxRF0sk7SehsouNLZb6aTHJ1dadj79ZzfptnHGxS4VxTD0zUEX1hhEuuwWUvseYzKt+zMO4/EtpVMLWhcOYZvHfKDCrfC48nuVkHuSXepDpdmfRKLaltzytPKssZyHgI8tzQB7k2xz/ANbqaHB6G+fVwP0jfjA+LQTlJoJtRr36I8UskV+A7x/rK1joRrgWTSZxSint1DyqW5iZaFg/ytpq1nqZ7E1XQA0wsdZPdUQxyNEu6GYg5slxVFpoHfKdwrih2WsUCp3WzUMRcwmx3vM3WDx/KLYwJwHYcjYDzevPzt0S6bfd2ivusBY/Y6KxRrLU01BBG8SyWkhCoUQVQ3lqR517TvDfSt2uBsL6fdd2AbJThVgyX5WC6Hc5jSY6KLhAUVxWSgvGArjx0yo4QSXM4v+YQY4VhFBLHrWas9MzaRTFBjFviG15zlNPRpYVomhVIT1ifqsxbxTtwGa0xLrqdVrZbZxsE2Gaclj9jAivNVnX5ChAjldDjWoAZ5K4FDnoMVupBzbJD3dQptak1R6qcHVZXLMarwGbnjKsvchPtMn8Ye8yPxGJIgifnXhnSdWySbvkAG+39on0TX7VzsqQOcLOYuyUl3yrK3YJX8CJLS0QJFPk8DnM7RLxmAy0llnFFabTTS7EyjEsXFtQw4p10V0u1oYcXX7B+XtKWfI9v5F9J/HyL+MHG8xkGCsLOTVFAPrTrVO1k+4u1H/DF1IeakqGCmd+caSmC6lE4JkIR4Ns+iIRdGYtv0B4ZOjDinKxj3EaIxRSY3yfMx+zxfVgjYaJvaRxWNtfXaDFyfSVpHJPexOi8geWGxFTkXVtNaW4o8egnBq1KTjmR9B3mosPHKKC4KRAk0keCFLrqBppzh6M2wwK1vbelklJzqNvOVH62zj87ofrUImbFYaw3V0xuA/iFJE75ABcE/IpJnPi1dihfXalKQYjTGGIeTXQkPWH4pMFco6gdnL2KlIdldQoT2FLsusp61Qphr4lcXu6oTI/KVMhViEkEnCIaCVMxuuUTp24t9dEkFkti/1Cbsw18ShTqvp/VfTh4pPcePYXSdROoRHJppKhEsuBJaI3JOdM9wj8Lzd322Da+jNwjpzU+0fjyPV7LWFcS0KH4XgZ/x+4R7h9FZmyTwzeyeik8iDaFO/JLL5XRnIj6H9lWsEvMCLZ9wjuPe07IgOahjOfw1NgURi6v5z4lm0KM482LyaOgbGZY8X1YsjJsVVXSduA7u61aDft0ppFwxTnjFtyYQQwI8041vVvBdGPWPign70EkjYxG+8wjW60cmbW/T3AEo71xGy1MCpF5Z3du8aBWx4V4HPagTBRR5ceI96XkMxtV8y4RO9bC7DW6i/dCdy6cTKIKz0xWVtke8Ap7Cd1IOxJExOAUmCsA5tibCTt9vWyxYH3Bgo1dNeNr6NlbItp0P0yGeV71CF2OS2bK6CQ8N6abbGG+iAToPPAfY+3fOTasB/Ex3nlvzPXxklAMvPPuU28g12FwpkMQzqw1dwCVK06XZgvODv5O6PsH7Lwwd4Nbp7Pax6k3ADFKtUd7AkONbUnuu2iBuiW57+Il8dbJ3LfAaozaW9hL68X5lHBjUG2bd/xat37kmNAf06fafuKpYoIG7nnvqdOimtOxN9YMjGAfxwIFjrxAXU8p5Rs9jbVR5dg7EsTCO2IEpd+hjUQnjc6qi3biysDdUrdab9CbJxtG/rjm1mIAGvwJAXpp7sd5zWkBjRWvPfHWadV9q+sRPBUsc2kUiacN5UshDnSXTmek2hdabl98rTt94rPxoZ39CWRxqtQyRzrMiwU7Yt7ZFjPUtt+pwQFoT+OknlRHoidY7JUVvLyUv1cnk6PKeN1QTtp5rGsHlDmyNYx4WXlUEVCG31nKyHBHlWrWo6KalRWYBhAyR5jlXXLs2KmUEEqviupXVZM+0jFn7LBFFedSmeg2C9dN1kdMjqNnLY8Kfh7Jy/yKYMqDFosEpFarEAroelfntErV7QRIRiy2ToUwpPis1BLn1OU7y+eqOWeAm2KS3VQH111dVQFzSF/eJuUf5jxoW+e5PFrFvXs6U7bzZSukBCU29eRbu6a5tRsVUXrkTAMzzGk+jxExTUZmKISCC8wc6TXtx48H0Ss4EPPylS7XUQpY28b3/GwScDzvIvkSCE+xccUGxm2Vqrxg0YxzPSc8mEt0YBwm+o08Xx/m/ozvrwVt34NGBJt/TRxamZe4eLpj/pq5vjxzIzbwBMY80laiCRtwUh0f+e1uPSLnIXkyaGk5gZNh5LTovdjTodHmAscD8AzRilkH5G6fRx/SzPOJ05i+ReAXKXBtaXrzAmZw5CHuZZcRdBk6LfGlWTl6Pi7e5ostd57f3WPDRqPCLuVgHxarnemKnzTDGYjHxTh06prXZilk+0BP5oD3kpG2hEV3ObFUXVpmq4TOlRf8RXHTXPcX3jjttCL/Cmz2sg7wDAYCzlQFDrAEWo2zr2E6j9B2Ppd2BEn58jvhhnCxirxonTR6NcR5wymaNgJnwLa5vXKFKQ0wQc2hxUiJnVhYNqMF1AuI5kYqk9+M4AnSmvHHuX6NjNsXcvNkJVMvhykYLWVpl9IWw1+PnooCoCX3cLCstEpxCPPK019smH7RHqZv3auPvX4xIDBwMFaVI2Aeje2jsLMiqIIgd4RskH0IK6MOddoWTQyVRerpRh+4SdXKkRLpIMGUkOJQGRRNQQZSwSfsEc4zq7KpjAWiA2GyuHplE5G4bCLCDqJTKVZF94puKXLK9cA/fAhvnzgEpIhnooAmg6mWfYAgrrkawCMJYDquz0AjSzccVYn2TJp71m8lDS2MAhNvTKcmIbb+mOOEqhwWhryuCdgpHybUS6EwZ2zLCSvs2yuss6Ac+xUr7FesMDby9tC75ecgMgdh5KZZMQv5NZBMcGxB6j5JqlOU+3mx/BX84moS90NAfYypfV9qLPfk31tYCOvUcNPU+2lebzjuFhY9EHGy3NsYaSpCX5THaJn5uix+oJrBwij1mssN95qMpJ6RJfDtRKpW5NkQUmkmnxJs8Z0U/tmW9fyMjD6h3NxAvxbUtYFIHMxvdFCl0vKzOTJo9DP4SVoWs0Ewklk5HT18CxTkFrlKdW4F84u3gtbVIctsYhilTHqvwY9rPubBHGUUByIV9uCNyQQehIXtUnAkImIEJtN4E4i3Xxe6YzTAcVpNM7hxbsKsAZasNy69k0oFngUKTQk31x1gulMK9Z06LmaDhpk4wtvrfuBIL7z7qMaU71ANnk0wehk54gHX4uN92y0czeuBiAAQi0vgWHrq2VlUYDERpAvrDVhRtH2PUYtlJvF6YF99dGWGDaIFV/smb1rqHWDIfzFAgHqr4SiDiIZj1F53MFKIa7ejNF+q7G4aJmmYjz2TyDf30vnFtKIO2ZcdCzLLe8Ct23SUyU2jszWs73etjAJO6+aoVOY+HtZZYFOC8oYjwCJcmVRMckV6z2NUF+0pEXtpaN+lx94Nv5DFIvYGUXU2h9i77heELawMUmQAY3TfKIx2WgGzvARcdqC80dUGgmYDujTHgifDygWZPbS06PBtltYpETwvJ+9sbmR1KlIUwmmn86uw3VJoNng6DFOVrGKSO7nXFcEsBOABF6R9ehy042DirUjZ9/Uu8F/1+zgUYCdo/6O9fF1RKo7rWyLCr9PuJefwiHgwVLybuUh/gNDyHWkUgIEG3qLYa5m2JgxdSpGxpyUxoKyt+n1ObHNvKxCHiTC0l8fKCl1gRSEpsIUddJ3i93hbCcWWdRQOi4WJvQepGDRmY4vFzqbNSXpnN1YcyYYj32qfUdKPiwEz8XWOAI4i6BvDOkV802B21QBDOcBQDzBUAwzNAM88Qn1F2g/jMBsEvbeT9LGHO18VCCfKFFHU4mVaRT8bpJQyfN9V7OpBAOdRQZJBJJfDwIxsq43Gyuraypojje1zby9BVFgkV7Jm45IMTkAY2xCe/KI9CUM7i4pbj+ZTRwQyxoMLDSSBH1BoW6o/r6PxL+XhYZCM8tf8uBcF3s2wfjW2zBDEZLS8Op0xw7XCDNGbb2WtQfNjkaFRpUVHF/rNYXaMxWa7cSltK93HanOxm2NkqtBrXrqUoO0B3jMml0VmhAS1a95fhlORRcgVe2gRs5LVmyuNy2kH/m2tbeIv+LfVbNBP/NNqXhQV4E9rNViFn/Bva22VSvEP1NhovPz2sJ4u4y8H24WVwNjQZ4VSegqU1isQpFoSWV25iIosZSXOULdAUWGvdwqkuGXbum4l/NR8PTUBLdVGPCmCYl0SJ5aZEq34ujLvPRKbFULgBwFxZsyM+GoizJNZ/ksyuH6g/XfhV9B6EFAcWbQ9jMZ1aIYr2YAeBEbBlutrA+1dgamSjIqmoCiIeVxXtItKUsC62LJdPN0ahnR2zJGijRZa4s5XKoLmenkdqbHOPTjXlJQ7URwhxlhRAqFyY7VNQnAm5kLTMQIakLbYhEUxmm2hRMsrFeN5UTGec8W4HFKjnRs5IC9qvrXYwM1QpupTs0Q+i0+fw+58nLj4J8rcItmZEEPAKBNUIyZWnLIU3YQIA9CEvK1WVdAEMm0DmgCQ2Il3kV3ATlLGT+yTgIognsVCIKvQFngkCavMYsYSmPFDwhh5SXRvq0w44gxCYK4oNTudTm3p4IU8IjBotPWAI1NB8mcfoHn5AI3pAK06bPQGts6uqoO1eJrlLiNAbmGuLMmrRTIkZ9MUQrPsG4VYaBtIEQjwuO8VS1/NxbN12iY5KTN7M1PpCkggkEwuWyvdvxiOnzPpCZAi1dvQeiMtAwPMSqzN1etm0CkaiBa+wEnkrs1os3RZCXDhwGilOqwQugqnKSNXklgeF5ZGyhT2WjQ0S2udLopuLVKgk8LJA2j/ZEjhDTWb5wgL6b2qqyB1AWTcqYUFIXenxoRU6KFLREkGSkCdcYYGrjLuVVQvsDMz3KXts+710I4xCUwCpY229YjCrbiQCKSisNF6LaynBW/iwtW10vXhSvHbLgAp8MEBah4CykGjF1Dc+gFzjHmxbfXD48SLMo+UEHBOKe3Dk9iOWQLLmF96oo+OHI4O2rtP4p18l6xxQ9SLR4F/FPSYZ5ZVbuxJI2lGDGdOrJxcKIyQpRpFw2IyNhZ/kBMSP5Kp/spLp09iK40OO4gfSNlLGisj+qhNRGLFg0iLFa9G7usRv+MaF1O5pcVbrnTpfTqBrXwralgC4spcevo6OmziAu0hoO8G3nYAPQhYv4Khydy3UD/xJLE8qN3KJCICQFu45WERc6VoUrxui5m06lQiPuz6kZcLieUWiFiK0aHMi64x+2Qh/GR0SlNGNrllfWPfG7ljz3dRky19MubqY6bwwHvuhrgtbzbl33V5cTuAj4wCfdCBD7nt38CyUh8XNPEFAw54L4JsDMRfp1VojWA6roogNRVGbgddYGVwAk81Ij610qk+7dafugnmbcez7KmO1XSEFrZ9l4LqPwVI99vKW7bAq215trcrmkxvB3l7myyfR4U4Tt62uAnckm/1HbcOryJO9WPTP4aD0tVGJoXoyobDTB0wnJivloiGcsNM/oae/Fz9Bg+IhYt5w2KRAR43DDzeDOt5Wh+BAG+GMA09QDxR6lIspTURumrFQV2nZJHh106423o9FckW6IY0QScDSdNDK9ScTg5Lo0IWaTFXoX9Ucsehd4gwGSE/89YIfg0d29q62fK9EVXtesKG1D30QlvtPlcPC+HHxFboIqN8LUjDI4lF19PkkEDNt8dkcljcV4eWMv4qMs+FWImR2fNzkTCNP3TQ+eIsk77tjXHSvp70bWsh1I2xVK9oajt9Hy0pJC9/Ez2+byJVIQd6JlwgofGMZbI2ZRGER6tsmCazWxfIBUyI1uDoFLcZJ75aWHo1ajEKjth8XBcRzyYT+rsq/5ImFn6tOahy1zCr347nVX7WddS11KOMFUwmwIjeJ3Wt6UXyvTYjuCLVaaepuIpqrf2iWiuSaq2Iq7XUEURMJQNFylgemyleQaMPBTxL37XFAIa9i+urPeOY9k4X1o7N90G5EgDqoA8DL9V9hzmOvNvl3rlu4KIHetGf1fZaJYUVLez72FBrD1nPwjFnOnufe6m09bFduGMNmNCmrXTgGJMX5W8OhY8QvwWUNByrXaADs214JhYMwToUg2KssKLTsZBWAQU39Bmsbpt01jHvCo0GIcUGVBgBEz2ZYziCSlXC314g8UdXafNQgOts0a7GtrNkir5llhRAfNAsSeJ1nW3cCBJ2XaqoY2Lai8i+Bs7rHIk4YuBaGhLBS2cJsCgscMGirTwXLMmibRmKFPJEnkUwIe7KpLor3JFfwJGc+dJEpk6T1DhmIelwOHf7pL519VUSLCffwRjnMrb1JCuNjTWiXgp3qWAyCTPMXJShF2DQqw8il12dSCBkKk7R1MFjVLfQXL94sSEzVBSUMvgG2oYNVJA7yxjSlqZswBAHUlj1FclcKzGwFMAnBs4ADhg0AxwM6/cBEBi/ZO5u6N4PvbuhBqv60H0NhT/3aV/QRx9xLSkMJRRbjH1F8kOGKnR1DxrgJSiF+dQ0SUYJVDK8cm563Lc8RLnKIOcCKPoeJfG9oIv5qDFsNaEL5gpqx5dyJR3FIB1FXr4Tg2RUlzo091gf/MAwdB+3yCC++9jthQdBlsOj+DGlrZFlEh3FTW/g3U70dW+gI/plyHkyRQ0tfHmy880OIt520nknXVho1dVNKFBj+onyf0Ezgi0hMTUbVh7j5/16gvlRC2gSn66e2Lgc4N1tgAsj2xS0SYalFcFms9i+wK6iEatNxzG3UaQXINw7bzZz0XYj9M6Tm6o2STyvFas6S4ftSuhb8q8waWq0R5d8tcgjpT3te/7OSOTuOw98f6HnsXde6SXGp4TFqW+6Yww5oqyC9GCVTZCjzX7HMNmj6mg92KESrNSZ8hR+S7UJsvfd+thx0SS4X7bkGeh5K8N5kGfOewNtUfF0KmwptnjY0HZlHB89gW39aosnWpbn+w1vW5t2bNthPLa9G/LM001MYQwh6ZjDMsWcn9dGc4kafgjDT1SAryK0KHTxeR0VdaWxtulUiByVy3URhJ5Qe/8JmSPkRipDPe2hHowxUhlqzvy8YcynQh4xiCzQqsvHnMgVPlT2qmQsWAUMJX44h9w69ZADI7HbppyL55V6QMCDGT35J8IkzgAmvjZMvkkWPr6rLHTcm6QhRue9xEY8GZjsNjq/GvDd1uC7rYbvG/DdZuDTnrCkgcbrYaJpxYAIdxOMkaqDHWDEi+qICBUVMSRCwESkKYb3VPxPytWtxUMz17cJzFDlVW1VnaNwHAvNwaEopUzDcax+55647DSCGBmsNLU2PefWIzKgnkzDrQK9ONQhHsWMauRz9l2Kqnv8Knnmasdyzc2qKk0TsKnKSk0GQzybiFxp2XZGwThFuTgQAKQAk2MxcAIaAFD+DgQAcwuAAFpXnPTon6BmOs2rjALfGRZjj+DtzlW/OzBqQRFCHYMDUTBP70wX1LEQXZElvBXMx44QuYW8u6bkXfHSukyH5/n1RuMy3QvvJR15ydJ6nJBhksQKM4GfSkVoLhkf4/KWe0eRexRdugRs/1z9KGL9oOYu0gFrnVYO6y6V+Ti5duDdyc31KEdNilHwugw9w6S7Iff5sFV3yELlVn5KXIZ0jHByXBp/zJp6dcgl9kaVK3hztaVWi7ksFzvm2lsQa1inBV291c7Jl9pNgNa0Gi/URHBk73bb7xbzg4pdy2JxFqRrSp7H5evJ5G0jwuxjRiSTNq8ug0XPx04pT4CkXPsUTVQnmm/js4mA1FxtNi6sSG8EUZXyEOiYf2ub6xc25udvodn4lYgiD/WCp44x9lYZwe30jzyDfDtQd+zF/JGCxXuA2/iOmWP7XSyI0Qf13VDYAuRuChs9UqlZgQNGHagdtX8C+K2ylHDddlTU0KmU6Tb1iZbOnxcVjCWiJIdNtyonQUGPS0VvYHYJdO0D4e06ZeZoYZ6C0vfSV5Y5w6pn2bMsED52+xRzN5Ldpd5PIytxBbre2p4LeD8aYoC7lGg80KpuYgAJYwtlxCiaXoOWPJyfv079vBWoqpH4Vt7zw09tbJfabhksh9astcaEUEt7IJ7DCDxM9bE39MeYVIrCIqNrR+qiWTMO6N0hXu8F3jCvp5RLSvMjuTdKOEbEJY1v7m13T6yBVd47ucoaVHnCquA4ZEB3h9Uu+J6tNjagbq3m6DjFuQrWw7KQUjNRGThFN5e0E7Vo/iC7jkTUqVRsADt89I9tc7tbanO1BcuIbD5G43NnRWPeRK1JMRNsRc6GCFhe2b37ykggxRsR4aS4+JKJ14sfClPGRH8b2g7wxM4fy5et1OUvW7DTunhkt4Btxr+uHOM2nclZ8Eoyint+GgYZ1Jj5zjWsHdZjT1N7zy/tA9jEhg5LWzx0ecbW4wh5GHNVs4p6B9xcV9U6xNqxUF7+qHhlUenjtTN8THe6ufdqPju/Bw+86wZ4bdzH00UlT4ED+wpSfSHfEylQol07xpTPIuK20j0trk7QC9FKTKDOKUr0gjdBETIo13MrqBGM+K1AzcOZanpT2EU6Avnbo7qVwuRKKGxqZu0DOCzmdJpp22lMaCGUIyOcCu4HIW2PjtDEtIxjF70IrfzfqyuOe1uwXa6cQcLApByRdNBye8BWCHVZ566SGQpxyR+EdStXUTFPS2k76rsWtWSvxnwlWwzg9nCbfzJhea9MWHQeGNltob3iGDZPrG7WqjT2lR/7YUNSPjVnbZdRhU15KQaOUgAmQf0ttGDYk5wM+ao7xQJM4sDYP7S4U98LW9lQjxR3jH4ABo8xgpX7yGz9UFvnGRQ27qRoAGYrsYR2zAQytRVkRrFmVGW+lZFEh5USkuZIp80mU16alBYsR96NvL7YdOP5RViJEb1Ykd68YfE23Xjb9b3+UjbwU3Shk453yq5ERQzojIT1yKhVl7/GwghlLMsdV3/kjaZTfbeKphM8R44VJEvVcdy3Qw1DlwR6X7+U0uXIEx2b+yE98YaTeDqSkfJiJSKjUgsVv9iUOYZ0n8msDAmoL03MZHyPJUBxjUd13bcnk4hZJEapJYZtKVIr9G1GwwA120khJRI5jyQFrWPoJSb3iHF71VorM0Xp6jFNdOEUPlZnRGRv4xmHhc71ECkqViSRphkjbYR5XVj01HnCsaY7m1quVVBLIoR2Z44sllUc90mZSDb1Afe4K6kX1S4Ry2JyrR9LqBWnrmQcKbsETHYheVDBZ9gl5g1vW4RoQeJBQbAIzAD1Gnxgn2ormkzvqRcnZFQxjmxBTygE5Mf3/KIGhF1K5oMwo2lBCf58HIzRoht/6iQ8+CAOR/qpSCPV0bKVaAiBR784NMW0sZREYhSBRTt99soK75yLMitgNJXYnCMVcdHSqsMiRYtyyl0o58Zvs6mkLy+2ZcdWQG0DAWlTi89anMcHO0y3AfWIOeFp18InfAVM3ZsJ9yZExVqa5Akh7Fx9LphM5tBSX/hbKv0g+/zdoTSGlsJ7YOlzZbPUR6fZolR/KjOnuG4KtFhPTNH1TH3TbLZl8sMrpmxN0ZEVNtl4RpxCpX+IdUK12EPIUHhUNBAHrJOhp4Dds3An4E/wLheFMn4By07NM3qy5UCDaLY4FDG6gGrFRhUmxEWbh7iAF46urJVEIpHcmstvvVUwK3EFGavccTJ5maypg4jFJRMSm4kqBGMoKw+tDREUovDbQdhpRnyLVWVZy6uyrOV2ljXXpOAKjPO73O6B+EuRGPsi6r4bG6zpZWovuBjAT1q7rgjpVod8srGZ0JiyoTdNyGpWU6aPBT5iXV3wKK2L1JDcibUoEddjc8ZFZL0n6O8dEGq9TTeaeJtcAXojM5IKYHFzBXkDqLWCOm6OuzfQH0KsduhKlcm1wser4uPip9cqPj1f+PRi9afnKz4d+erTPmXbFpNUKlsbdlodGBQiaxgCIWGY+ACfZiFwq4+FF+2y6wjIZiEm7BUqXLMLX8uxcMNN7Ruxwqm32kASJQRVlW85sBVKCKFEAcg+3AOjR8s1oHLmChGXaDYB/AKG6CWNnc53yozN+ln1Vysr5jM+ipHP22GU7FjFx2vNNae8h1s66jtvfwM5CTqkZgzsXvEzgs2ayiEBSG+EflXS2tmVVDk36fpiS5d6bEnWrWIQJ0ljr6oGhVf+4VBAgPx+4Bu7YMpXIRgYssDYFMkZWJ0p3sWycxJMhbqakkyMtI/hDIcaITvpSw4Y4mNu/WS1IAU4PTB8YHxDyUJKlBunSC8LMRKCKLXdovDVYlO9gznHB1HAuZCCsw/xOSPKIg+MAi/lXs0GOFaBfSFnzfEmZ3bUOHDhrMGHgLV52A+D9C4Q+fCp4rbKZkKe4snEWLeTUdoNtvyDINVJr6/5uV/gpu4k/KggW+sR2w3IWWm0wVfCQxgv9ppk5zRHflpAzukGD/NAw05YlVpWvYeMdzVPtxqUsp3O0q3GUrUqWmQaUiqYrWilueIVRmh5UOYD+8J19ZKfHlDio0za38zP65Kd1V1zNcJLW8wk81jR8da92IVN3kr5sV+rLaSuJgSBa+/zvLiJ+d1w3pMs6Jz2v3o3p3QnhVuXttAijdEvTNgS60QmJdvDhqO8wgJmBJ9bRvB5KfMUaloKFx7QoUkhScYG7JtCRKtbcqVvBSnskavMUcqokNj1eNEWtTiHpnAIDGybgVjynG0xMpMl5v6IgnDk5jhjSZZ6J+6BO5hUQAqY5r2rgeLlwmnYoQSzyicqFVf0kZfEaG0Yot5JCLCafGECJ6EoFXgcQ02K8Wd2jDwnIpcuviPtgJ9a+DxkB6UK0aPGEYpxJDiOyDFiAw6aNApaV6Z0torctvIiOUYvVphGiAkW4KW4QzObNpii/sLcYBLup3auaT33VFqhRCJfA7vjpygrEYAZNu3bkfjtwAzMfAeZ3BQBA/icPGstY13NZ5UjmBkH7kIYs0KGzFIFtjvTno6+URYbTkk/ymJPCpOANvtNYYXROfZS3oGF4CmupFep7NqljrU4hpnfewXdRDxgQXiQIBKK9FSGzzTA+K0l05kOK28ZKwIHmZQsiYDzoq0YcC3JdRK36RTaC+W2JzOoYHqYGdlbXYFfT8plvCdBosrNqcAYFdrItcZFoG1qP6hDyJm6Vv8jGU/YGoAqZBbPwii1egAmnm/FfLUlM4qHdta+7ZiHo4GRyuAHbLCwuXY+yHfFSc3Ae5idDbyjOCMWYC+DM7M3igLNAFjgxUkoIL/ik8XN8X6UdB8HvTuSu8zhvAtgY7tDyWkBKrZN4FQ0S34910dmLnA2h7KdeHdJfUMGy22olw2jsIupTBp0ly2Mhd72KaykmeU1prMRSpqAJ/okm2T2fLHw3Gwy1B9nf2JbZMzESjZVCzLWvFc7R4fcIuHO4jCBdVlU8D9X4+ZJWQ/Wm5EOixnHgCRc05SW/SBDL22nRezse0AL0a2OCGu9L8OsJR6cacLAoCEwa66J/9VqbtZjru8Vubm8xA0I+7SpvXsvrZ+QLKkFa7mNd6wJpV6j4PWY4IdO+LZjJ0oSrhmzJ+nPnOTIafv2JEceMa8NnJjhN88+0ZGc6OiHTdTevbk7EjpHxx2xjMQZX1zlvVExCPIq0O7IDDZRFWwiAG4BNiOJABI2GAGPRpW45BEmb1QA2VBuFxyEggp9OL3iW0ShwtJNGLpZaQ5FXNXTI5+oU+4AswZwucdpywOuHuL1cjxpSltJ4RWhgwdYcRBkJDPb891eEcHPk+FgUwSmKmTxJNPCMdn2uE2EECwcHalANqZuEpYopOIvVxWpFI2vrq00L1xY2XSqsl2qTshAVNXEqwbRk/4WenzTP71HegX0UtpD2iEoxYxVj7Er011LNgF6eMX3yk48KfBqb/mWdaAavHS10BVRFWBHfAD5xH03K5kWKjHCzjCCjHhWl5ZgMaVOUephllMln2PuEMAVtXnKwbYTWykI2yqd3fujIB2LXNhJegXQX3S6g514tYWfbt+5vST0lmF/XAcpLHcWXtrdoc5l17sv4bgw/IAVfUAFfdjJdxUWBWhqCT0fklsX/lA4dSeiYEsgoxXZkYsoafUxnpwLQEgJ36azcq0EdvouHaUWwSbu6HMdSwKBPjcXm8QlOWYqgXXZ8r27iXvV97ZjYeE48I5HGfLwUYiJfWNY27t4SqG90hbqS7LWzvXUfSN270Tubd/d9t27ye7UfT/1jmHNe2Si/sr4tSQDSQ4IQNwNWkexuz9CTQ7SyVbDPQrSDAXXWnNzaXWpWXMFMxekd4HT9w+C27AerZo4KHvJYW3qZjAs1sT76ZJ50s1BqfxZ2SC8rijWVUXQTF5LhtFErUka9oLXkuTxtjF0LBVfI7Pau34+mFHhXoCIVq7A7Xt40awG6WWxsSwQvLdQmaW6UGk57HLJ6Ik40aKsW4w6ei/ot2aHJMW15ov8yphW3mL9tUh8E5k2Y+hDgh7PYurOwhxYjRmvJpN4UBpEdj3BYadBNrAnWl0IJ6uGk6xhgQl4WTlxMTfpPQYMejeESm/ZaLyIthSL/ebq6mZ/s7HZWFxprKw11lY2alN2UO/t3du6cvX+3rWtt+7fuXNze+/Vm3deuXJz77U7d17f25vzaoDXAcwr6Mlj/CDzTv6GKMlBhgdpmCEf2ZufP0BV4hAFsoyGTs4kh5GH1WK8XKhnA/RIgQLpRALLNZ2+ilqY7a2r97bu7924fX/r3u0r0Nu1O3u379zfe3N7a+/Ovb0Hd97ce/vGzZt7r2ztXb9xb+uaFw1c+FAkg76LuT2iyoNi5USt2Uql1mxFas0o4yJFNit4oTcaxgtdqemExebUDIqEHntIssGg1ODFizrsJVC81APOMPKAMVV9zBndhJGb4Mzo5rdEZDjScMLHqJEtqWN5Hkq0hSi+x2/Kqlr+FepqyjUoFCOxt02bvY3xUZhQEPOa/wDmFZW51ylwMoIS99q1O7dIE1tkQWa5PfMkpDoclBZ0ixpJKaaLqcp1UVdLFWbwHRM2F7XJMIo7dG+GlyYoxy29B+dWvebWHNcELdhE7xrD6CNlIgPNmbTJDcTso1E22B7HXa+CwFFwLaomGdkqN4o5GNTJ+HukhfsctYex1WQJh1WzVVi81lh3VFBeHRY3Vk31hK4/AxJKQVER0UNE9MQTVlT6k3p8EpJHhOTxiUiOckwZyeNTkBxDBc1AchJUJZiaKjF0R+ofSJwjdWMVtmOoSMr2jWGFL6WKfwuAfwM2difYBVCA1AVUX1J23PkAgr2M4IV787TLEqUUPa3eDrS92zq1ngg/QwbTzHTlMFN7sqQE+7HY1lTYJrVa2rrlSl614edog5WQzjlZgu7UC8KXHIEcBrrzmJfVMrioarRdKEdjZj68p32VabL2iUuR+TMyZHH5WwG2bWFaAvQnMR3YoJSBu3G6cTUwDfVDTU/Rs6VAzIxfgrUMoiuk02INJBPrncpXGCVY2BMe8nNnYzM8zmZop8eTv1kCgHYfX7u6NVfKYHzGD+thzzHcRkkvPMVJuID0wVNiXLxXxVERDjxT6CY91+954aCd9Dy/xw54KOa0EuMBZfm5ZOCtrLmjnlfzD4eD4V7W3+v6ab53BFJHl5c+CbMBlYYpK5U6iL0kBYSh1z34KOum/jDo7WWDZLjXTaKs5voDb6f2/d/BafP9M/zna/znG/znW/znd/jP7/GfP+A/f4///LG2616Bg+fyNomcyhasA6JPzYGtPYz8blBffthbPgC6d9kfgHDpuGHgIcJ4tedfvvjsxcfPP3/xSc3xLh+L6cbe7dEh8Ht1TBrYkDj26PzxFUyWntxMuj4QG9FdLYgX39xGleO588f59BFTqXa1On3k9iUgc09+V+979ZGnjD8EfqMdQc+R4x+1Si/zMI8C9b7fqj2tOW2QbUgljBQZf2PckrGJWjEmqlyPL11adxbjBTRl9UEi6wVX8vrYgbmJYaUihYK/jzsU6PXq0sZC+m+ba8tNvFtursDD2mYDTrZN+HVxpeH64oMUL33r0csrzvKKupNFuhsftOj9IbAOa0sXXatys+FAu3CMBEdh8ATTxgIG91qJC3QlzVo+u3Z5Na2r9gI3Cz8IQCyvZSDKOnqlqOl+lCTiaPKCxfiyt7QGk9jZ1VCBObTDS956O0QtA5YkXi04HObjGpbHnQR3YxTVWni7Hi805+dTOkBrAz/q19ATj86QbOm97Gm9Ftbc427kZxlJxI+y/iIOHRAgmT5ya34a+osDsnypteYaU4zxre4/ZQMg/8WVbWTn1I9FQieX1rxFuAeb+9zzz59/fe773z0y99pIvQUsnmbe8fM/vvgYEfr7X7Rq/8//+t//bc397qvnv37+JT3Blnr+uxefPf9SvP2rz2suoP5X+Pu/+x/g3W9xG3z3lXj7H/4TlMDv57+G/7+ENx9h6V//n1D6NZR88fzz738hav71R9DOZ8+fPf/6+bdY8k9/839Bt7/Cj55/QR3/DTYOVb56/uzFZ1jyN/8jlHwDA/1HKIHd9/wZ1sc3//Nfwxv4DhqDUiz5j59ja/j1d7/C58//nr79CIb0sfjmr/99jW2797Li3T0O4ec1igCYAX+iWGh60PuSc71PM5HlS26Oc+j+jo4bisQ4IP51o1EvIG9101yqmos9UxXvX/K6IEa9EdAi0kh5l9OFiO9G2BANdZzt1P7pf/kCiBuswh/+77//D/Trrz6EP//0m/+WHv7jf6Y/f/1Xtd2d+N9u7DLrLXk3WknIkCm9tBasWyKGRQEwiUo9Xl5TI3n0/bfnXvz8+TfP/wDYHWJKTuCZHpnuznNT35Mp8DwQ4No8nAltXnqJSqPcKrxMhQd2YY0K3x8lWGxG8ISWG7d07tFotIxJVg3y40f1nZ892l1wHkEjL13qAsjP0eYDatJfPOwtYknt8vnmpWX8dfkl4WdgTo+XH74MLbwMLeBPHMkl4OST+IC+kT9rha/29uCbPfhmb++MX9R/NsFeHNHbw5j6q3fmHr7sYAvwZXB4+fzKpWX4U/nt3q5DndKne/Dl3ukfPtyBLx7uYl+7D+v1QZ4Ps07r4fLD5Z2fOQ8zLHcIbP65ARq+1s6v1M6JO0qvtrcf+fFjvI+LMPtoAkd9AFJ/AhWBJQnSmg3nKITKBAG/BGScwMOsvuvYQ3iYXYIh4ADgsz/fEFbUEJhBR8+gUhUuk/4kV0Slpk8XcSW7Rjeux2o7yftyNr/5nzRX25P5nzxt9OhPr718ELqP/s0jvkni/WxI5bVz1uYhvEcg1F7ixT9ZvQht+cMkw5e1l6xvoly0dMkqPZCll61S3JdUPI/oITjZXtIlBZKtTkNWVUwy8lQNyUBuCZOUutGwA4MCtFJmTqdw8NJzzoRRVAmiZK4FbSnEEDR9GMPsbEg9TGURM9sfmPWjtVyCRTxkUQM69oL87NGjR/VOa5AfRk7nYfbycujaPGP2MtQ4v0yloikbYy/h1lzuQBvDyX4K/z1cnoyiSRJNonAitvhkfxIcTsLJaDLYWV1c3530wqMJHv/Ow31n52eXd1++TGAvbcqsnsQPnyxMKNcubMOXPfi/Xtv5WW335drkpZ2fvbT78ksT3B6XaXvINuQ4HcBXZrnTU6ICR+3JxMxLgmj5EnQCM8KuhjRUPZ3BTnNxYxenySYGk1gOl0A2xCD3kwmh3KwWrLpmbIc9a9Gq9p0eJN9/Wj82mPHRZAJvcn08A3LMqMZOQUo5R+c1YhZwk8BD4tAiyWG3I3NhpQAaevFORPdVhFDLYoqho977mhFdWGDfz8/P8frYhtN2pK4CDjIqAMYTP1pYcGUM3UeXhmnh6IKC2mU60i6fP/aFuo72hTzLLi1DjcuPHCtL8TLgzcs7iy//07/75e7DbEEPGtYQ3zzsLewsOdYbPh135M2qVgQTcNHVfdH8ZnUnJq/B8ehSFMLk4KjHF2wDI5bJlieyEQdbESb0l5bhs0cChhJ+o86jS0lUPBCyvGZgB5/Cl0l0+VHr0aXRWeqOojJ4f3LcdFen1QCsh0uHRP2g2sIyAGGn9pParrPT2JVAA/jCXEM2UdMcTa2t0QF2mD3CwTn59/yxFsF8d9WZ4rBHMFr4QgGlhBGXH2ad2QhsLWuxvo3AlUtFtWn81po8ukT2T3jCFVCbiji4L+2ny5cJ5uaTIuznQkUDj605Sk1G5WRorOKrqn1JZTORWL2txmJ6W8QG67syEBMLiEVoDQv7HwGUlAA0BLjoUGYGW412YlA/Jg+owE0TEDHzqc6pVRtlwDU5tsgKaGNJrNj7/mgfDa6oOrO0njqanBJ1b5+5pf0kP6efYHI1t+fHB0GajLJovB3kNxQr0Tre28NzuxVPJsT1T6dsblcSft7Vvv+77599//X333z/7fe/+/733//h+7///o81N/ZqL/7Ti//txS9f/O8v/o8X//nF3774/MUXL35VI4KfVjKApPx5EqRX/UxYGTMmMIQjIjTq8NDwgomX7oS7Lia7JP+xO30Zkt2/jInMogXVl+8UsXXkxYVvRoVvRjbyoyt27cW/r1GV2ve/qFW8/W/k2+9+xd4u7/iLHzQWLz4cNTYajUX8c/06MP/qyE6cDnyTtPDDczWFWBFnWBaQAz2nWQpmD2jsdfXxPdfQOAKr9ej8sVRvIVOCqjQMyJsHB0k6NiUgb3fTcIhtmsJhGnaDPTQM8fM86NGLRyxQlBS/cVz9EA12668kgO/AgKHjSjquR97l2MjzkaPUKUc9T94EeJcFJfWf1nNXE9WY8hz2BlQLdYfMfodsYIZ+Cmii4wsyv6Xp1H2c6e/ompRZ/9RjrwecMWoZt/Mk9Q8o6t6NPDhEDRdwNPq2JpdNp1bTe5k1JKuhTDXkFsx0MNygHic3DO2jHbPh+YVzypv3btZlNk9sHisuoWDmkGdmFvhpd3DXT/3DDLZZHW9E8wEaWkiW09pFeIEL86vXhHY4BBEDUz6ZIvYsNMq6lG6V5avHwbj44Z5dJj6WZfBp6Fkvhj52jTukWdPfDP0xSjSiPBt1u0GWmbd4vTnKxEv63E28Zep6MQ26AUZJnIiR6Md8ALKqovY0Nox5WLM/AkbexxDPkkuwXz5crsNJ4xDfAExDcxcnQxaNk0mCiR/no8nEl9vsWAG1lUKhq8HSilwcMKonmeVCZeTvg94PQYC24OTnyNV6h4PWtVbPZcvhmqWXP+EvfyF+q7VwFdx30R5sy0ecBVy38Y7AGlOqV7tchu0jwzjMa+BwbZnB1IXid3kiKa7TqXVqCzPf4jmBH0P/A0L3toTTIMQcbmNFMcmWqX48RaPZ2OGrIOnPcKB8BUQDlhQ+Py9bvXL37t71O7fv772+9aBTUdaS1ywpkEiE3RGch/f9QXLou9k4A1KwOArdRcxfFyyKAjfz42wRjvSwX3PT3DsWxa1jCm3dOsrdbpbBLN0+zALFTPfI/yBE8hurOrW3VIlbW1AfkFKntbzc7cXAB8AyhEeYRydfPhgsp36Wh4+DtOeny7q1vzhaXV1qNFaXdWuLOIdF7HcJmiyOwO79x/ZMffzFamOpudQAXjnLl2d1mvmDINKdbuPTj+iUWpGdLq2d3udheMj6hKcf1Sd8J/qEHpfWT+lz4O/DljC9iucf06/4UvS8Dj03T+4ZTlD4Okl013dlwY/oW7UlOl+Bzk8BNVDooOf3dN9b4pl1rSr/hW7iWP46DKNx6yX5xUvtLO22RmlUf+mkwcJHsb8f9MKNzWX55V9swtpgk9kykouwmy0/CfZFgayyeC84GEV+uvQk6fdXXnLOCU6o/pJ8btOIngThwSBvrTUa4pmUSq0Yq0aiBEABZGncyp74w+m/6oRuwRejwzPNZ/2/hvlsB4fhK0nUO9OMNv5rmNGZZ3PhjLOB3RamfjwOHvvmDLlx78rtB1jyA3ac/uaMADgErvg9fz9c3vfReQbGvZ+FefAXh/AQAL0iCEhw6BEu01TNM3wU4XTPBJLVP3GB/7VmmP4Lkpl/rTnu/zPgtY3TPxSffwgUBsEh7M44XKYugZnAE44aoDkVplSYEZ/CqZOattN8CbryPfir2TXgFZeo633/8cBTD1iKMEam0pO/9QtcuP19aobYICzrjuCUPrRaNsLoYFB1kaEvZ2IvlfK0ihAwh+nZdYa3Ska6Xo+8AuO87SjTzWgnRS11muNfR1igz8/H7F4hVylSZvdhLgWLDPr21r23tu7RvTyzJEjbxeYwwfR1WAlpR297qalriMqq4tYkRnWubD2cqtu7ShXDcCC/0Amhz0VTo2eQMmJNLw67Zjq0VofWqn0iYHR8bnvoVzCbth2qu7RATNlS8XGdHHPcnN0y6gues8tU2+WSnXy3U1HWAgTJdxFPlqQQlVLObtj0k8mRQBF9V6p+yNvSJdpvqJlBj5Qgzcf12iJKZItCYksdV3+6n/TGgLHWs/weK18nsuGljn27GiNn6+hL21rWR0DdDOPHe7UFuqee0y0CGshxvTK+gZ6jBr9mXfbShTo6w4Y9L6LkKdgHDiobBAHMICTVgEfDcM3gB4Hfm0xmgcXBBQ1iEbIC0FekAVlCGlmaynUo/LNMhUbN58LHUPjofvCUrHrVoJw/YWpaeyctuXreca0XHoSP4UBbTAE3W7WfBP3Vi2sBaj9iRI6DNED7stpPGo1e80IDyvf9DKofLnZhQ0f4pt/f2F9dhzewxZN91U5vdaW/AqI9dUATXkyTLKAuLjaDjVX8IOgO4iQK+8HifjSid03/wmqwCe/SZOxHunhlfWM12IfiaPR0lI4Xh6N0GNGbC91VPxBqn/0gXTyAw5W6v3jhQmMD1Tlon+vHizDz90dJKEbQ6F1c28ReDsNejCfTIiIXvNhY3djoN+G8TFLMrAXj2fBX1/yam41i2Do424sXVpvQMAbfS2M2NNUUawX43hBON+yw37ywAs3s+x/4foow8Nc3G10oSEZ5+P4owCHvr1y4cAEPczRPff7li0+fPyOLUbf2/PPnv3vxIRSIxxcfotXac7RYff5brPT8G3xzDn7+/sVn3/8Cy3/5/O/gkeznnv9avP32+bMXP5/59h+wQLxFI7qvoc0vVX9oO/f1i0/Qsk4Wof0eGcfRw6+EfZ0cK5R/gXZ6+OXPX3xMf+BzGJho7VM0Byx9CsP7/MWnaGv7C+j8K2zpG/gpR3HuxWfnnn/x4qMXn333CRvZd19BZ2ygHyFY+LjR4u9rmhWCEwq+/k51Dc199vxbLMYHnN6vDbifwQi/wCnKMcKjaff5Ny8+hupyCJ/gulDbv8G5i5HCunwJo4dy6vy7X734VICMwPCpnA78puX7DUKEOpZQ/bi0tN8SlD6SC8Tf/PrFJzARXLpd9z3Y0gZzWkU0otmKYYlpKgNHDQYxXFivrwi9YJ4fCWjgwz8+/xxg8RWs1Jf4+Bk1/LFa1n/Earhi2M5XNNzPEGcIYXGiiFVoYkmrLNDv+R/+6d/9AuELjXws1uRTgdtmEX8Oi/jJd7+iLqCZb6GZz+XywfA+VUj3CXX+LTUOD99gi9C4nloBVZ7/jhD1N1jllzSUrwB+BnkBdrTuvxb7zUwN2v57eFTfEGIBQL77SkKJluUzwKUvCHT/gEV/wHZhIhq9vqV10YBFWH1qAAvr8d2vEKw4OFUK4/+M1tJUo0cxPxy6oRG48DbJgLl8TjvsE7HBadW+/wUHEG6AFx9yeCDG4MjUUHTNDwltcP31lvma1tCu9jG18wyRX7T/B/j/a7PB7H2M6PX9L8z2RCqEy/qNRFfccWgMrEggIvJX9sNn9gBwnxLifayR//nvBfJ8RZDRK/Kl2IhyQ+PkGZmANzQW0Sb28tHzL228URQZAf+t3NRfSwpEFsliyQDAn8rhwBff0FBoZb7GaoqEyemKnSmR6itq//PnXwjqTf2KfQSzQLIgEEkuKUJDwvkbwrQv9MmhNiJH4I+hn2/wWwEz3GWaPhPJf/4tbUT8trDWema/RfRRVBWX7XMo3511QAGcJNVRw0ZMo2/FwqFptoH5l0Qtvjz3/Dc0NOz9U43UCCRx1ODZoBZUffFrSU2BTH8iYKgQ7hMyAJd4/iUdhfpR4N6HAvLPAAhfi1EBJggi8KEgWRxHAJ8B08Vmo1X8UpA5ABDHn9/iar34qLDP8Mdv1aH5MR0Qso3dkw9zovHYv1xyQhkgO2LLIfpJEow7Ab5USCN3yBcCDGp2GsH+TlMWcQggqOUZAp3hxn2mSZ9AcYHeH+FOEBOSu7sw0T8iZaRNKrEJgUWglw0+U0fI5wTPz8yx+NvvJIr+DoDzoZjOLPAobkZQwmfCWUHM/beSRSL6yVAAkE/PUkELdzuOXmyMj4gR+kadRM8E26LJJ56aCuyfq6F/wyi2TU2/+u5/IswjLP5Cngt4livs/oROI8NzvfhHelRtwcj+gHTf4tZovnAofiYJkAK9JqrEqGgCCYgNxPwzYnPUGSqOH3MsfWtw5VNxPitm4jOx+nKFnv+dgC+VC9T/XJxRyHaJ9r6B9fkKIWltHsFZniN6J3mfby0ajKe88BIxTCau0FcKVF+Rg5XE9I8Mo4Rz/wcAtkBh0dKHCHKY8a5b5GsReN9ono0+/kKA5pfYgGBGPn/+tzZjQTDRXAYtGkCAUPjLF5+I1VVUghpEnBAIw/hPApZ+g1+JDfWZJEvyQP0NOt7AeBn1Zocf0h9Zl07UzwiCnwrs/YxAqvgv6v2Z2JHY1Df2PpUkjHMbbEeIDb/rGikAQfdMoeYXBqQAXrEUVO1TtoQwvE+++8rQDnL+kYhDU/xG8RrI5n2i9v3Hz//44lMcK5ErgeGE+4VHpHTniOd5hrRC8pzUMAOnOJg+UsIGjJBQg7D4Q0XAfy9PNL1WnxNb/K3kpNQB8ytirBU7ZgQbyUkafH+GZz8XN74hYqIff01n9IcKlh/LI1eiN3JTtHi/NkwNIRbhN5306lNxKKlT+5lg2WjJoa0vbCb2Y9GmZrphggLQdFIKLpj6+rVsS1JsedZLSv4ZkYhPJGVG6Q9PeiEEQuN/lOfmp0ok+pUirB9+94nZ7T+nsYjDz4iO1BKTI3GVAbCMpf8lknwJBmjgbxWl/VrIQFI4NYTiYyF4yQGVd9c5GAf6uelD8+cE3o8UOv1SrcLvaekZO6hFXBw0l3eFQCWX5AWJjno8SMNI4n1mTltE0K+sfUlTw931S8SD775iUpNYma+1jgAXVfHLP6dDELbEV4RqX1M5R1NxOFnIqkVyIykyKiEPWUmmqL7azEiHaDk1O/cMD69zeERodoMOTHXok7wm5bLfSICjQCCplSE0ulP4+HdIcr7/heBG8dv/QkTrv5wTGwL+J3wW1PyZFifkyfe5IElwTO+6QucAk9fKB6J3X0ipHDeSWRPaYi9+rkUww2x/QcKOFHC/VPvwczxPkUURBzDgNZd1mRj5TDN8p2k+kNx+LYVGJrUwPPi8xKCQr6M8z76R1EBzBZLF/wT3jyU1yxNF7lRJa75WABJUS074a7Wh4ehhmxmXCbe0PK0M7fp/y3v37jauI1/0//MpwI7C0201IFKWHblpiFfvJyVapK0Hwws20Q2gTQANAQ2CFMm1xl6W7LUmMzmTZG5mklmTmdyZI0VxrPErHs+cNX/kfgnJ+c9fYPIRblXtR+/dLwC0nJk5ZyUWgcbu/axdu6p21a8+ZiKe4ICPaaSfcrMCEaeisiNhMlFcEeBhfUq0Lh+IE1fKEk9oKfjB8SXuWZV0MQ5UiD2/fP4uSgjI8R5Jaw8uI5cSkbhUHe6XNJFP1SkiiUOYNR6Jo5L18gHTbYQ2RewD2vhMyuo4fdi3J7BF3lfHQGoHMBPFqIXrTiJcSn34mLi30BblGc1YHqrVnGt8EjNqOSImBD2SUoFaMx3KQtxnAuhHQugjsZqIW9AgrgoZomLTG5fAkVdL64gQxKXtCFmvVJmekkLwYaxwcCFNbKqP2dH7S+xXPNO/opn+MDaRfkz7IzZPcOn2feLPqi0QeyhEFi4MyYXn3Aqo+LP4lP9MXSUmx7AR/QZZjkZpsdQhDndJ9MjeGOOLGcunKJtKkUAcZo+4resDfCxnjll9pBGTjUKRKB8zK4ScEqbyCoYqODTbH3QGf6BoSDg/Yp8+FRIEM2Cys5z9eUK1KEfwU7Yo2DNpUSVmpZtXH4tOMn0ExcD3hYnhqWBhv2SK4mNhy3mP7GgP4+P6EekF0qrF1D8hm6pGXEGDsU1XTL6+VETNT5N2LFTZKWr9PW4Qwdlnm0US8EMxR9QAl1Cl0k+B5b+WZ+GzL8WBhUa696RY/Qk/kGPTGGkkzx8SqX3IZuFddtg8lso8bjWo7wPFXip22GO2I3gfBGeK7cFPxXtyBVCDJbYrRFOaT8VAKk3frGuxHZwJCzHbeUoKzb8odiTSb4TojfxHtz9CKx8rqirnog+4VYqMcO8LYwB1GvcinpYTWODJLoftsOlVWKZ2oaJeIygWwE+5ZvpQivnxhnqqXHzgW/zGgNlqY/nsITdg8glZn/BKgEkkj4iUPuScXZx8chFV0zEdLpKFMD3pI2Y/fsjOJqGIihdoDGJD4QFLhkjcP+IugnaPejHxSCE3fJnmK5bBWddigVe5gCAy0020TJcQx+djSYnUX5Ih2YZlRihuQUlbFuP7EWKE/FbkYyHAfcoOBLHvPuNmUIUpfxgbZh8x0pBkK0/2Z5/QKn1I0/SecjtG2iYTXx5IS/96/k0N9ZHJT0IwYpYFeXY9FRLNF3gsCGWBHTrx3qRKPhNTh7quZOKcT6mFH8VfkcV/ygyciLMhxNZ1JSRjG0My6KZ4zbej9erblSFPSmxKCIkSPTzfaCBKoalC2yhf+tW8y3Jj1HO9TtDddPs87h1T7MXVSx8SBHcMO70h4qHhXTLHZG1XuM8QhkZ0w65v7O+3K9vBINgM2pg7Bh5ziBS9Xnq3F7JMI4j03Qh2yDdDfzqIgvrWbuJdTLcYNhoDP7pEXk37+yJGgULQbwVe1Hq9+r2TxxdPvOq8fBz9eUTEg2nZwo3E8wig9VowiBBIwDT6PiLPGHbX3uu5g0Gw7WPYhGXjRPKXGNh23nvWgb22btmpFZnOI2TQKMslsTFg6qC3s4FV++taWopmEXXYa127rz2bmc8nGRb6PDO/IPwm2FOYVwSts/v+vaE/iE7jXTo2faHvdnz+ZvxWKByTem7Tv3OD1iffN6IyqPfDdns17O3vS5ghjLyat8e8wta8rK44e2QtROa2Z4bH3Jfm5+bsOXseMcfsvhmeOnHSOlDIIJcEWAuGHSRIIDDHk4F8l5PBXq8fNoE2BghpRL9hqh0F9GinZfrVk7EjFS3LTb9hnkOg7244Mq2j/ksvvzo399K8/3LGkkq8vnL8hrWQwxCGVYwJQ6y1bbdNP/RNOelzdmZdlo0N81nDV+pt3+3LSoZspAuCRygQTd1j1OVA+O4pP7WP4ZAsC+gEMc37kXncNuYMTKKSUfi7WPjYq1nlXVG+/V34PfmzgKlqOYHdQdApDXDqbYyKDd1BhKsDLKwTwDphaKxE5GPxnYsFsaz0fnnUd3sGB4NqA8EYjtELgfX5aqQsTHIPg5dyatsQtZWO7DH0bhbeaIfds21ggA7OfWQiNqQV1woleSpDeAz07ejxXFf6BWAQamSeDg0xE2GwGkJTRYNbQdSC3rqR68DWb/qGtTgzBycnBTa1wjaL4OqGZfYrfqzFH8UnnvGlnPguS47CsB52On6/7pf1mkf1xANCgKk0MWKJf+51m/B5fme+gh5TPSiM5SiJFYNnRZel0EVYOv4euVUhFp34XAmE7zA8QY9beqp0FDayN6xjTfxTTe8Vf1rm5eFJ0Gkmek4gZOW4phFMbThUJmXY6ezq3/gErVcGIbDbPlCABkelQCMBMaPrsG+77ciJgGpu9Pzu3TDsAL8RZ0TfbmvMYx4YSmCHqXPDtYfJZ42YNbFMTLvxA3S/3I6/zmfwnza2FWJFQ/xnW+Yrm+eHWuqNuEgfivQli2lVzZ5dF3G6rd1eGJm9Sp2w12+X6+KTLZ7dkc/uWPv78/ZOtQe194D8h/WWL3CVQUY5PjsLFfX6lOLuHFtGYPm7sh9wCjbklz3PaZmylrW5dTv+Mg/jGThyAAfAIs9TqyDwZDfciBO/j+kCn4V6taj1Y7K+imd3qjE8n60csXGZwUt1ODPaZseyO69jyhZTrhQC3q8wUSAeO0GKyuHBG5W5VxDcVltjGPRl9iLmTIvHpwxmXghzXYEHCgeMPKD9Ir7b83rljht0yz3YDi0DyH0Vh0/M39kRX5fghHbOi2/nu56zItnpZQaFYGwOoyjsGnbkbl7G6HtnDopc9XfPhaOug4sG84z5pUEQPY+HHgi38kHJsLKJRh0QTAQD9qP7alBGH5aYVej3PyjhRcrzB6XffVFiF/Bf/Ur57Qm71UJF7XPQRn6DGoVk/AKdsAOsT934FO3s9d1mE3FbMQUBc/HfI3aIUQHOBn1sw8Z+2TOP7AUgW9pH9lz8M2eVBgh4CY/7B9YGAi1Yzphl6LWUbjEgQhWfYQsYUw9797a7Q3zpMnK0C8GO07XdgL4MnH4VRSzp6ovHVoU4n7VoGA7/TENL8at2Br9ChtTQ+FOu2Bua+Y1Zyn6fIUYFp6zNC2TxLBaYLV/a358xJfp5VMEZeLPfxhADxIMW8N4LDMpT7oZSQ+U4pjvY7dZNgR2wbbcWhuYM6EFxlPZOlcGO9zsIgLywwz2OTcNlHvW2DAt2g5rf7Qf1Vg20NH60WHb8AihvdTh6MIdcP7oeUs4AQyuBYds4C8nnROH4kwR2UH8V8A5YQIV60Gpmx546/ZzXrVTdkRtEJv1bavgskyKfTXuv48Mp6jnG8o2VVXTl9XaBAdT7PkFou+0BECqQaznsB82gC6RpATkjXnKF+c3j1Jp78BjZlbldXdGRWFcqKPpY+sNtTi+gHJmtid5okb4Q+YRGsDCDjv0rgjJWKhxsAJjJ5dnZGSDIy5QnGv6onISo77LEbtjBXFc8ZQzM5tCk9CQW11L2dnFLHeB+wfcQBncxxTMC4hl80WwuIjlG272/iyyVZabbFaS3IFn3KqHxcdVVKP9M9bdhCpPlmBAmNDgtpYxpVFDubQ07m2WG0WpZYsRwwiCzQe0K1TTQrlrhCHUEArcRjGmQI0rHVZbwPWBwi0ZJ/uAGhkPSNeeOIurJaPaBxA9ihrYGr/3hFz/5M0NgrLpiGgcdF1W9pELAqy+3N9sqX3z2IVmTyZUE78U+ZzbGr/8EuSVJ7esq16xxrolQycA12XzWd4FnIuqy04fVuYUf2lzQg6UMu6c9RNaFv4Mtx7Vx1Cs9vw57wBky9ttQ2e9uzH63VfbbotzntUEU1rdmkE+ByETwxiDECIRRhsSCOKMgHTQ88zwmH7osFYwKtN2PaojhghjfCSwXFREUUfGOnhJgMvkAM5UBJbabs793XIcYguUHXhXUkfsklgKYmFfif8vI9VCohq1ZhhUm+GVllfNISS5pTDp1FB8MLj1QPIwuO8TaWoCT9s2FCazGOkh21pSSyx4qi7Ego+8DJBfQKPuLRtjlJC+6yORgWOcemp7cpstw5O02cRpokSu07qaPISZ4f0t+ru8zVy66yFNIHJr4+q//EQp+/dd/DzRtF2nNNJGbrgfEp6nILXdQg61YR0B9SurJP9d69WhRrFEKUBmrxJJKVWunTf1tyzaePzFgjznDxTxgZgKnwk6VB2zfqBsY9+tXfwUymFCy5QhzAJ5h+8DEt1CmMEDLw5mXtcFTdrNH9zQOXUooD2TdWy07Fp5yd68oXkS/5RYmhjAOQUU0K1G3BFu+4/aBm3aMsSREKF3K5P37//rTErkTK2ObvOVmKwS+NEm7QaJd9LFBDyS61D6w1un/hZNFzAKFCGNC7kAvQEdADHGz3smkMnoJjnH1BcSyltKRhcxPl5ZiuG9DHUR+/Yi0rm+JnQo+g7qPlvB6g1xE1TkxjdbLOQNECaGkfStz3ke6jZAhUuxP2dxUApMByp3cy26sFXS1mblsX+ba8qnq944Dm4FDE1jZOt+J49eTwcqnif9i3xaQ8ztk7hoU7GqsjRUud4cdpTKcV/aDNW5d+Pt1fXyGadhUCQPJh/WxjAlIlc7gch9koglJlb1AaIrqK0m2C2QIVFJLga0pj60i3Vwnu5zKAl9tBu9N1MUs6L9WdXa1cZX2SlFHA8xu0la5/Eosi03FpUA8LTfcTQPNyJRPzJlpaXshJDg3ZgYg31CMq3iK15GP0R3wU+7kph+4ueU0xmpMyNTgpd4g4wAQhKqo8D/7GTaFLnVfGukdIQt+/TcPSsx5Aku1FvMr/PH/LKH3No+XiW0K6U2CJ6V66uYcjJMtCw657A62DG0tXHUtyD0EnSLJi+jfuDfTY+GxqA7iRx/KI0ST1a+DrI5A5A5jf+HAB4k9iPzOAMR1KbmjyP5GtEsS+026OyKZ/SxmeoFBg+C+ORzsgtze6Pv+CnTcGR7oqQ1UCH+MsBYpBnbs8zCqnaMml87Px9K59RJ8uxfB8TGPKQfsXSHBD0l2B1Xt1NyicgO0W25YIMS2lOdB18SbNCWrR+PY7kt0rebMJUTxQeVC322ilqcs/1oRqwCZBNSueH0ihZLdQeClpXqv7478fqmPt31CCvdAVgubqf1Du6WEt/8sMmsyLskaKGNYd9Yr7ISMeTemsSglmioRQxeQs4KfT0e6SEfKvNjqtvvLCfY8H0ZSlIGFxbxm6s1WTgUD3Dx0AR2/vQ1kkTzg4qlAf9xnX4DcAlx4G7EMDNR0H5WkW897JWbbZE49JWQ0dIrnc4MtTYdW6iEnkhJ5lODTz/SaWchd6eu/+fEYTYQGGfVd7URUMr9w88AI3QkwM0vr4LvMKiqFDzmXXbrg2+EXfPmcOCojb1BndYeZk9L2Gf4DWWkMI2mK1fr26gm7xWGlTtibBKV40/WC4cCZP25vwviatHkxdn6+8UrjNTqqFJOHTZr3ZeJaRt0n5fQgbddNklxyk7dOqE93pMyXtwQJqXmHn+jRTkRnOf/Osn8XzSvwuMl1G/UwaEMbgWfDv5xNluctda998CP1BCQ0fHWEp+MXx27wwmaPas3KY/0bsg0mhu/QnbE48X7z7J/Q5Uhd2p/+BbVmU8F8uxo2wIx0wmzGCM3hpr/NcGel5XrhiD/IsFlolIswJiuY6Mk4XjnR9zsJgvv5n2E+HEU30Wgryd5LzDkUrWu0+ZUXe9p75IJJ4R8lkiw+xndV2wb3+EeGgnX9lgIN3scbGvQvfVqZTJHk/LcRhtEUqmQEqmSkGR5yxTT0/n32JV0cFQhpsIkatH3WD6F4C5WfVIZSu6nKt4LvEdQsJ7cw7ra7aLAQAOYMGkt/zBvyETq8MT2Oe5s+4L57T1AkQ/c5LPgbKvo4W+5ayZK7Bn6EKhaJXkx3DvB+CUu4EQhfOMdnUc8h6QtNp2fxyjC03TqpP2/22yCDkfMVfhwq7iCLf3wpp+03xgg5LGTsELINpQ7wu8NcQSfrfflCmV/4TGjv2Bz2m9BuUA+NVM60NKnjwRtTa9a3lMUiVhgesOBdaeDgwZffmJW+MAlMYUoKH7zFDu+TIGrXw3aI6DSvnvjeiZOIaeP2m0F3New5cweq5w/lUhwMN7UzdpDgsYlTvun2nJMHh7QCsvtE5AzyAHh7OIiCxi5mA4X94BiNtr9DOe0iYObqKbTXBlIHXRvOodj/Uvj/oR8WjM7e9FvudoCDH3SAb7bwIlk7En74dyVxF841T1DSlKF/+8MITRqBOoUFKkCQlP3dFJVM2xuE1wIOpc7KX/xDSXBKCsSi+LCP2cxIa/cLanmoboOf/TWc0CUWvUnDfmLEYjGmG3DRT6oFsoDUGl5QNzYiv+2go5zWimpix2X58d+CMpIos5428ZBgwZtmmw0T1Pd2SnMl+BdxqOT+fG1uTrMvP2XQHhQhw25E8HLk/WePjCk25InDbsiO33S5NpG719TuYhAsiz4VvRZd5RkRYbbxvDT7Fikz5lrD3l23YpXmG/aqkdo7+cok5nGE1WusjzHJUmt0dGsm2V0SeeyGFUsO6yn3yPMppzkmRnTh03Lf3ya54TomTmnbLRfa3HYC6UwHIkPKmW4I/U060+3a28lnraQz3U7ywfnkgxXVv86+HH+D6nrqt7Tv3Yr0JwkP7LUwy3HlsiwyhCLDrCI9WWQXiuxmFXGxa9SjbfwnbnfejhuYs+Oa5vKc/5iR60b1Cny+Im9MB3W35yOKp+ZeZcsCp/v9cHSTDENQqC8K9dOFrqFcRfnYeZk2ljFloaPG/r78UsWLWde8L1KDBF3zFfv+0crxV9R6y4ZWiFzs7pep0MGCdGdPu5vD63AUAl3fEP4uZ6pZmI8oRDba4UiY3IrKyHgLW489yPRZVzpgF1Z6Bt1JujY6kVrSDfGGNivK2G/A5HTw570bqSttOdAbmKIhcu+cmlssV+ZfceC/Bde8IingfrVuXjl6Rl7532fuiZLGLPs+igjXq+YN+0xMOFegYuERekZ6hN6XT+/Ip3dEzYo36RX7PnqKHtjXRLXm3o5jyiqPyiqtY8ft3fiXO/KXO/AL8K0ajT9oQIm016dVNDHX41fQu/OG5lt6pXqt4OeFHcVJFVbZOWOTT58j96Md7TiX4y+7jtyRiJ9427lS2cEPd+AD7PWW5vepxO5kDGoevZl48VPzwqME1znu7UJcIUxrvDy78vOd/A4eAA3eLJrV2dmd2Nc0d4KvFE7wMXMn9p+FGSTrOZJi/Jhm9KUrFlDrfVHpm8XL0lBej3aOmm9WdsrxE5x4C4laKbSLhXb1QneQ5pO7YOyitCaYk8QqNcyW1l25UOX4+Q71uKX1WC6iUm6X/Ii3ODfQO/g6+l3vaDRm2RmjmINirUSxFcUHWZuQA/sNagz91s7YVxZg0GGANs1VkFswpoyqxxMghFfRje9K1cQJ0BzXLArLYi+edXswxb50TrtSAQpom2dsWfVlz7I10r6hkPYNlbSHSNK7GIxkrwoOmd09WR+NTl+QGxMuyI3sBYHGbxc3npzuA7vtV9kFms71bxytvIxnoav8LLe+eiSUsZzguGdSvPwMsMweqyMhSxwkfb6y9O37YdiJbS1ZRpNO6BFabtQf+qnbIkxD/vFXT1EofojGKkQ5VDz1YmG2O6GxhPoThWGbLlHE2zA3NzI8V6a1Xbu+MOhi0ChCczLL5cdGjvV6MLbKnqySwYuIqH7tHnRNuQgM6RpQOFZNaP1uq62IGU92nNm/hcvqGJPbmAb70v3+XYxw1m91v/6TL43Ju670/MuE9xvU9C+Gci00jRpHhJIwN3WVtlBZe2jk258KyA+UZvSqRpX5/DgSDLu3Wj6o1R34dC4cbrZ9xX/h1PxizzctxzWPV45jWc4XybPxjfg7hVysxt/f7Dm3429n3W4dWritx2vUtHiNm2q8xpb4wt/c+jYiIIYY+lA6srdLf5UYiPDA2sAb/EHYd3AGQGd3N4FX0NSiM/mBNcEaJNyZlDCQp2jwfkToHeR3gIEgDJmE0CPew++wFY7BPqafnrIgfULr5eEiFDpCkfToJQLfQEsyEobykWfu8agzdCkWHgkRcyvsSrW3z32I29yHOOGf0PdxxjyHsgLHXgrcjH6T/9qQCnJS8fVBGW7ZO6lIjbXz9kr64WW7l1Kl63YnXfC6fS2lh9fsm9ozA12QWXB7VtDGNvath01cw9dXqOKb4i17RmQGRZfUwLMwrqMrnnRlXIcaz7Ecx3MkIjiO+PZN3/a6dqO7sIORHLb0mtb8pHUn6jh79/7+SiL+40ikB4AcifIjQECqkaGMlq2UFIEdQhp828+IuugWRl0ciQ4VdrEFJ755xK9Ci1rIxNt+ViDFEXTvov5j9EXGO+JXAjJYBgHjbbl48GscabHly4wU0IOqEZIFzLD2ts2Bj32rVED8s+HfLZ91s+IGtUaAIdzQ8lZiubby1muRqMlhr+Ncw1ov0hI69Auf7163uiVjX0zzpo9fmyBi+v3dxBTc9EE+t5BCZKgMDvZK3+xREsQs0oypZeDr1DLwp40XUt7gAUPdZMCQUiSOGNqKA4eU3/lF0pYMHdJiiJSCcRDRluoXm4go4tM5nJ58YbEPQ763kXy9bnWYIMVhJvl6XSV0qDHpW42uHj6EZD2UZD1Uyfq2zyKIbvvwZds8LUj5tG9Tw85t3+Zk5azdJvqGQoLSKM2oyJ0b9uHc78/Ohn3QCqC+9QMZezSg3JX8y5FIiURahoHtEP8k6lYjkpZ5RFJiyDw8KdPNbauKKwzK3Ol+392tBAP6a27JnQH7WHwWfjjygbPF/WrW+Id1Bw6u1eoba5fX9/ffgE0ENKmEFt6uQsX6ps7Z06gI3V7seOZtLu2B5rOVCJpBRWarso06BlY2qGGgvlbJjerMDBRRuMp50IEw1mYLbTyap9598XVLCb15s3oFvbnun6pesd/CMJz7dmTZd1KT5UbQIoie/sBaVL857MsgZoTse8wNgZayKoN6EhcG4jm/Nli2L63TDsH0qs6yve22h75zCQgIl+BeteeDNMnTNR9b23/2A/v73++uHz3G3l+unloWed1TiZxFIND8cd4YtBVb/uAcWZb1Ov/+v362fkwqnEd86UV+fJH17AiyUrEerJNQijUxb/FM6k6c5tphr22wYBAEjgKx8bR56ei8dbDBK1jG0DQY5XW/ekfQ5B0xLjEjFZYheblC6id+2PKRgcUVG6K6Cv3FIvABTn8mLSwjmIlzzz7L9WWRmny5Orew/Pr1heWjR602ENKBvQ0lXG1jQz9ELXjiA73yr1vk/yOn+KQ1idrd83olHgrMgqym1rzFjS5Bh33OoKYm9Gj3Ui6K0/kHM+G2vEkOgLHGqKhaD3/EgEw/YUI6c8ROORfYY4KkoZMpl4mtSvbNffbb7HAefJMInpQndICcRndxLgmP6EMH6iRu/VDmXSZpFeiu624HoG+GfbRJ9TZDt+9Ji9pyZdQPWKql7HTT/Li5hEePtkI/ZRDTTzi+5UTuWTStcOxTTAveDwfdjPAoeBm6nPlyvQ8MOe2NI5Cep7dSKApiHnWlfMCOTW686KuElx1WNGFDeLG7l6biSWac3XlPvrf5ST7NK4gKUVIRIlAHR0uAWslWIuRla4pIQ6wYS5cb7VCP4DptbqVjDkuUeALBz9+JI5ZWuVfGqMVMF6tkutiSQVQSuwW3UMek3OWHwmGQLeWPJdNGgSHPJWmowAC6LIQKQq+QLh+tsU3xCHPNp5slp3n67J8YSGRJDdnTo6PtN8T5PT8OGoRCHlVe+YYqK0zK1i5Vq9XLInBW42o94EIFlqhl6bF9YC8fvWRZk0SqKfRe7oaRP1mojrpMmfakeNnSeCIpe5NcUTvXMeO0+Yb0/JznLv7CRL4+MesNuo1wml0NDCsv0i2X2tLxlVuJ+MqtcfGVsvak8w56JDHnnVdhrYNmN9dzHnSIreF4jgKFtJBATPHlUBfhF+m5ojk+zmey4bFTz2MQs2ZTDYg8wwIi7QE5aHe8MW7wZwpCIONRMfDOB+i6DAfclyX0kTujxD2WyJPsKT/4xrn+eJprtfG7L8YQ7pmx4a9ZSBK0Ojya2/UXMSYF/du1eG58/vXf/LgUh6qVRDKVh4zDlb7+k58oXtBKTEx++HfBIvJQznCn6FQDes+Mvdw6VCDnVm4gp/ILKKAT8TosW1LHoo8juxXRgv1Njm86uoNuO+j6Y89uQ3UWfGtxPFNLxpOiG9uPPqWcOgyfXQlXwEQW6LP5BfBpJA7DfkvKB2+OO+DQ/I6xS/qZjXGbObFW8aHNIqdEFASer1eUqK5xDZY6CP+qDXF8kzJKi3sc4rPHMgqD4sWuwLqqs93zx3YJNIvMeNYUdyKEdOQ3vHmMM0BYf8op8H7SYbOwwVQU7Tc2Y8zJwkWsTjSttLwMsoVlJa8cC+YrO0YqHVfyBC83kU05xlQxTddwEMp1/3J53pouluq6ddgGg655fA5kLb3FyaKjcW5egHYtgmX0MBk3xkQ462uxjNnB3XG46qGBMnI7QPr4WR+veoXSHeoOwpI7YcIn4UJ+CDMD9WGspeExZStF9ohSQTL2elIdfscfTGUu0s/MvC0fFXBW9YDupQQcyQt5BH2Jo6t/islEfkWJ2T42gMMbTCSYiEkztAstnk0Z2HBT06iegGiPEVTvlShP1wOWxPD5n5Z+/zGLx/09TDmln+TRAtNM9iHm7ud/j1ECXz0VOQ8oNUVedN6z3/KsR08owIPG/4SDurNcnPAN0w5+gkoNTJVykE02P7//Qow+Yddj2dsoqeOzL77VGfnhEymAcuD/vOnQTUFILZRNVRAI6V1PeOqliQnkSw7jzyIfedQkqIKUjuPTZx9h9gHm/RSDQEyo10X94SCaHGQivVvG4k1k0UfhSw9/heSXvepFL5LkxvIpU1rQJyWWy0sNRhw/Hy6aIZhFHD63g8zZyWGt8jWVy9bQ2ZwcEzItE7HXgib+fEDZ0X7DcZDGeYTlN4zAWIPcltmvqjMbJXOByfucpRW6LjG1N0om3pzIB3Dyb+hcbvq+oXyU2zX80coGEwDuQkmgUPv6UjJDZaKlQuHzG/Isk6CvA5BJvIjjiRNCWwzlzgPk4BtjLWhuwBDKlJbOo+r5uKRF2z5hiZjw42OUdzEV6WfY1udMEBaxt/SiLEwpQZh2im+dvqwa39pj8XhxBqRNfXO4CfJHuQOqgud2m34/HA7auysIP49g/atL15y9Wq0VddpO2z+IrZu9/Kp56Hk88P39Fht7xvi0+WBJPlELKCWmP3HQ8thxHpicKCrnKX1AkwTHkhx+gCYMdQXwD0u2Aj9V1FNNvk1muwzB4QlyOwZ08ZmwGkD3KqqjobotD02nSTJF4DRKsV3S9i+QwfUkKn7EbOtJXA3oUDlKmN3FGywiNm7tup+yzsIgon4WwETUUp+ye9SYZ0Se+qu85KQbVBYQRsbYYmM69b0BivxmBizIoOCdMbcb4zWvdDShKn/r5r68K5z18SAdqYaRznHTaAei1h4auIoRCw/TLpPnyNb/iXYspQx33LhoG6Vjpd9/noOONn6cwupX2JYwEopWdGvT4tSDjO9/CprVrU8I3SQMUHn6/fjRKrJL3rpi4sWHzA4DXOYYh8GiRIBSzkkxGzpnXyCvkazv2Q90gSt9MMtJGLazsXxyJKx2kNOk1L2Fihbn7ORn578RlBDlGv+MUhyz9FNAtigvY+5bZXKT7TxO7Wh2dDwQKUXp8/vPf1ISiW8pB1duhYoYqwjCmh5D597nBGxC4sW/8BE9ya81Q8otaYRBF4B6qZhQSmaizXeYUlFiSXKfP7SwZWnSZG3HK9cKdIynxMH6GUv0nG/YFGgvQlKRxLrtJ2Gu8miVezYnb/NFJ7jJkkjxCUz2F5PaG6C9bHiN1B5IN/UZS9DI9gAlUX1vzPWGHC+ySUavLLcx5gwcGy7A56DM0xSpvhDbvjBRci+rS+iktpzyDprIQiTaQUxCXUpvJJweoQVl3pbzQLKWNZCsBIp5wUmffV+eu5w5MB6Fqv7y+Eu5TNhY9R7ukgQmxRvLHF5/2rwU378VLXSvr3Uv655luRLf5BDZ2OQlpp4Dk2i+LGsb6AETzVryDmjcFdDhwYy+iXGWizn12VnhVnE+x4GDBVRw7415S6AHxB4MAmGAeROgZ+Byef6o+N36rvwkEAiUgkfnld+z0OPPtnICPlSAA4z06PNIjzYLBdEDPlLQR+bIswsCSYKC6jMDSdQwkrlEGEk3Bdaw45uw8na3OjNnyZyGSV/itWhdcCUe/7/YdWZm+nE1yxOidUogKTk3TejYyN0dEMY+Qnm6MZTnkCrz3T6hSTVsyuTl7NpBN0B38nNh13e2q+wOn4fHJENhcIF1Z9vQWgwdTCOXipBB1/hUhAxFwyRDZLDWbengux279i5uOyyINhVAQ5UnI2iY3yrwVwJRwQ9+xw3a9KQfbgfdOj2sBzAv8Nf1PEp7hz+HeCuKn9CrhR7BEtMfZNPIdNe27De01mjQq/bt9Phgd7l+umzPt2+kB3HGvpKu4b79ZvrhW/ad9Ov3QNtNNwWcYttP1bCcBOy4lHyAIS76k5t+FYi6bRvCD7WGORtqKMf2yE0Ko4O8bmahDmacg99nZ2dm2pWuP2i53ZrbC2pb/m4NaNdudKs9ODf8C+ilZrYrotYaC2mowRqA6vjyK5VXT7523LD29/lH+0g05sVuE158Zb7y8snX5vBF9tE+HSmYtE37AhBeU2LSXtAwaS+omLRv+9W2X2kEXc9switw1oCS0cOiJMVtYWTQ7Cyv522/Ugd6Ivf3Xrd6Ojq65cNWrfIGb5p3ebzDXXutue5cOMhKhoiRQbWKIFtr7w1cWD3ZabP6trcWl1lH/2kMsmkKtgsvNeU76Hh9ITfi664Sq3VVD765WhypFfRhUWFLBT5aVePColuGrQyDh0xEWZEvgYh8gZU7Nuqx5KLH6N8y/lLptdDwlhEVc/VQQTGbVRNWIkpw5ygzuOVuhY3QojmeuTA7q3PBTaDxN+Bf7v571dq7sL9PS6bEllzgsSXKkmXnZ5JhJgvXaJNn8sbZ2W2YERfoCp7Vw04PFtg3FjvmtuWYM/X9/Tr7eUb9GUOvtvf3O2xzWxQG2MwligsKUdzVieLumPA9dxcDoWviOJIxT+de6MLfPdTCRxgNdaF6Tl/ic1nrfqEiRoDBUKk3lB/Z1ptpJikjwhinSGo9O/jAXjE3q6c29/cjCu3AgEa/PfBLvAJzprW/P9MSrwhBYrO6thfA+OuhJzIatCuS4cLTGo/TMPKUYBAr48ihhEYN+v1D0lYflOLLWcMO6ljSOFhf2AHyho5T85YIq7pr7WGPZWcRJsN8wd1UOhF3QN1XTRGzlbWd4p7NnJ+dXTFbfMYPMM73vAQJqkVVnfabuJw3MVBN5cPwtYasYBdW5UoixPWCvkMu5O+QutuuxyeoZV9IxioGyVjFC4VsNVkGO4i/49/U+yTp0Nv0Kfk7F4qwAP+YLIFojgz/lduuT0eW+vuIUOhqW01ZQPrbVF6x4+M38u1NTBfrHzU3xVn7EpSYszChb60Ch//sbFxtGz1l6Sn92G1qP6IkQk/Fgl59oYzmwqEYzTngM83qVZ1rXM3iM80Ka5RzEp2FnAMOci7eYK4PT+wbMH84efgT5V1FjsLkEuAwi5HvnOOULg6lC0oE5BWWiO0gW+4Agpq5Kc8gIWyAwLYadHzYxaxkjWDyXn5lLpV4WRRrUjZwDCWPaZWTpaQ/tqRs7UA8k1Dl60qULFaMe2u5H3aCgZTcsK88COeatdeMP+ti0t0qatfQrwacduU60DZx6rxM83cVhivLwOoDq+fFTJZRy1qg+b5rbwJJtzEF/G4bRFzfBzrdpGigqtGKot7AOXZs2O1tNUEq7Bzj/fi/5iuvVU4cQ+Qm8aiCXYvRzdAmx+n7LCr9JF1w0lYH9Da8dK6aN5qrFGN+zto7lwHrhmYoBr+mTJ4EPr02O5s7p0B7eXPDODfMToRBs9WrcNxWBv36lHOBo4IXwy72sZroIvvFx8x/9NMFE6mDMgHCoFgNcEQUzGWEN202xhaf9lM6FdTKzgJOZtMKQFzRafohHFUYjB6X7eAD20BwK9BetJ+Iv3GGyRWJJibAvhDs+J75PeSJd3V+pxe+oBf+zyNv0W4zz4lI8HMyENyKWv1wVIrXjgm5Zp5ghu6YeGAusifkCfgFmt/Jso9G76fCo+wjDPhgN+JkGfsllnn2ackUeEGWTLxeYrXt72PE3vUBaWn2XXtvy/d7p9kJeLlxHq/1nauYYbUKY5JEca7arCjmP+VLTZ6eTfUgReGzqR3coIc05Ultn4ngWxdP0M1w2G+FIYER2MBfmghoFPk1FpTL4BROw1EAB4bfhvfszfWUAy+LDX72gxLuhpvm+Uh2/K2oihro+YjsHJySLlhk7uDf7lqx1SSCJs9HMRMnE8omPcOPEvrq3OJbkRhu9ZwzcxUzNQGdnYlQ81B+m7DPbyEKeSTTw8MAQOWNuKr7BgwI1LjZWehFLOQpycc3rcXbBGLByijPZ2dvsxPQvh7pm13VzpWpaWpTcwEDm4F1mDp+/FPyiUC77JOY5NhlHCc7dNpgMuO2T+Kj4C1sf8IewG4sXB+Yd238ZO8KJ9hd825MXItGdvWJLnzGbsHxwu5ddnVeevZr2Cb8JxvDRKxYkj/N2J/SJMxFQg3uwOIxaAh9932KN37Qwqe5G1H0EXPM9/vQrpBFtn22FNmyyH2QRbyuCluTra+qcjibzKFvEu9pQg3LEltQE2ruVkn6WFRsSUzCdBpd+2qVhJLEjyBhOkcirPdSjFeIjcuvHMHUFDO7CXLXJQ2l7kAwjwvszkqiSO6x0Cxn7a59dd3GoEfMjMJuuwj3iuItMQ8qqBdR0Pavubt4kIujdW9wQI8raDwGbgmyCDRQCfvNY3v3D47t7cB/uweVHvm0g0BO1c2/Zgu4BdK3Zuthb3ehhIbuFapiye2h5+V1OtaA0eMmXg1BqIitOTiQ/hZ0hfV8T4G2mlNfoIPbNPBnOMc49KuQtuBHkF6uudE12GKwCSLQC1A23KRZB0Zwjl7muU03cdF9RD/kb1BhLGmLN+GLqIB/YfXEq3HOPuLLLxFa6TQZ9xzwjG23HcAB4WMSENA454/PKQK1slfSUaLPH8K2+wyvR7kfCuYe4hfOchfEaiySKmbTnpSuLqTpCrXg+7bXlcLzm5lq7Vsi4kNwocMosSkp50JSyhmAKlBvab8AcXcMW7ZvZ6l3Ej6i0dVLSB1PljgSWf+J9D0m6FwVgs7VWNBR6cTM1geRocr0yxrFCFLRJfBpdEu6OWKa5XVSHlE1nJ29FAN3xrsAjun4i7K71kidxN3EPnSb68pGwoJvBf4oq5g9f8LK2zLqEaImnx57WBws5GWdW+1WFa7SrK6tGSgyGcrlRCPw214t/2mt79+DX9gilORysOw96Kb5CMM/Ef5kZm7dXjPouildVdFj3gSwBwy2/JiARaDix+Ra8p6omK6v0jUUPRYVkwMPr2ueOikNR6kOjf1FVkpLROvCOsgsTcnX8p/Gw34fA0xYJdLklHxj3A+iNi4LyeXgJq7UaAqf88owppn7U9EC09zRpWAGrRQ8Fn3T3dqxvvUFxMFh96uoaVy1z4GIux42Sk0LiJou0u7aZDemz1fhJbxC4zyvtnZhXYVo4ltg4/l7z77AEMbnf0qxJ/D1ael3vz6yd+7gd//K80TF/r4yT9SGkNzR1Nn2Yzm654OQSY6ISa8mFjD8BDcD5fIQGaecrCtAeQHA7gFVa6ysX7cDF7Ug89gZxtjcWhVMw9FVjj/BEFa7XC609q4p92M9wjmkC5fDGXWHgyjs1OTwKVddjXKPTWniJXZt2FdWblyvMJU3aOyaLHXfJp41geeg6Ylb1ze5QwddXsI35n5yDxU0blblqGqbMY7YAdlYE/hYNQs1jPMunmlrm6CeITyW7BY+0M5jccfDjk/DPo8BsXB8KJZicSsrivR8zZAsfuagQ1lIifSTlTJBizfrhOUjkKF867+IOHAtXwpI/6CqXFx7izNKZoiWlbSocFWaOKhTM+a5Stf3vYG4p9vfP1eBj7Vhv022Zn1p00/YnaKH0GsgIg/wVocXoipIbq0NzKBv79EGqAHFgvDOP9rsw5a/Kx/CZ5suFeAJ/WXfYtcq8Tx+YutdcpJ9lL+zXRL/zHZLBMLr1m44xA5j5cpXsrjwWYef+Kf9feUaiy9lrTNoUmk3coA/+JVuODLRCy3W/BpC3GmYmmqtWw6I34G2EDv7PiVVAuPpVPaI9gOmttsdMlKcs+mS1zGAPoM+uo6ClIyOZwljPa5IFmBVVa5a3OdEzbSFcLPxCUTEmAP7RGz3P8DKsdb2ShSiZsc1FYQbARpQZyNRtbyXhsUCEkFd0c6bsEkWQk21SpJVvr3jAu5CXQB9xF1nHz37grlVM6vGs48rhFrD9DtcmyfoBQttxCdTLKD2uF1pJaqOPQ/tu/1qk2xOF7jN6UJscmsKPyUC8KRaJ0G6E80dAuJOZjWUmbomTgopMOqisPeCce6iw+LcFeR/1dyaFWISmK4ZMxHHVdrKCyjpLWbFxCbKTOiSKqdgEPmFadCV5MNdLWwYSVV4gdqTvvQJBXx9QUT9pRZ9mfFyfXa2nuV3QiGN9UWjE3gU2qjU/2mllAjz4J7e9cUCSJt4MtgWLx3ZUxpW2Z10dcEfUtxq0eCAxobCb1RcnMPVeqg8m5RbUqHnH6phGYkkkYc+H3Kj1hXmGHPFZz8vqZpDWhSnbsStZSkRRVgDsYIbt+8oTuIZCBt1KSlYeghVom6o+TH0/V8Kq6sn5QZy0uZPZdLZujgF8zMRJuEzKMWffM/WAtU+ZuhKBQtV+vrhT41EHoZ0WGk9zATm5146cfysIg+y9OaowLKrAY1fxpIi/aR0jWcTQdp4ytGf9JhQHs75Qshey3iuk339cKc8BUHxwbF7DmV2PtPhqv5rUmhe79Mq9JjGNDGYmJwmNn+zJMwqpEqURFAsPrXX45DXccfjNAid8iXE2cyE5suJt4K9x8J/0vLDy8k4PeXsLH1FeUYTkBl5nQvLZDLSzvqE2KgaK0FmlHFiTGzLq9GYKH/lBBbO3N4wAxf2yCi9lAB/Cbo9lED3GDp0jQKgKbwCo+wdEHkHvsmtss1KRHmsePyz7Q6j8Cw/ph1WRgmXy+wNt7O+8MnRDLL5LU8zEfRCaib4AIqnIsIBgXjiGG2MOqeal0KP/zJujrjJGLSIFzpHqpk5v10+Q/OTzBC9kJoh3v3iGeKF5ByNpRtp937RpKMaygubzqcd4kxtdPRWqEe4X6jTc7dvJqclxbHCHmdxrCZDD2ZVbK1Kx6Ejuy2yOTYl7FuinmZcTfPAblpqAtvscbObgRc+3fw6IbfJzGmeWUVQxlT0bWLOyVlRnW+6p9UmHBWEWi2MWiAR1Axrj3xObCRbfhESG+Tih4k1O/gmi8aGTygA062XndNUPBq1zU9ZkHbJjCXcp+yexGJ6x3ocyZrc1qlpzJ2JxKbmVz3ltr/tt0FxJ7N6K2yD+KXsiEVxlPG5oFMZVByUiRSiLrhdIBzqJEFMaroIy+gRStnB1WBhstoUS0Hvoc8MqWLYb47xKIwFBRtJXo1NvpdKjaGO+1mwoYR3EYV8P8jl7to9XPYJiBlF3L6P+lQ/HA2c47YgCf5yiirk0IoJg7m3lEXpscyeX/696BMwcV9Y0PY0pyD3jU4JCnwQyZnJFAu6w47fD+pjZ4bfYk4+MdMQUgKeSCpYRkFfiiYqn5zo1bSQyQaX5LZalLjXXRwjpANXLY/6bm9ipYO9kgOrkAH++odf/PjPUIF999nnyFY/5k4Q3H8IEdCndW89NDLnoKMzsTfxPJlpKnzs/qJM+Cj7aAjuVmKYfepPHKqMBXQwn7oxk8d8i2hvZ035JIYSUr7lPEmdOKbSeM/E73BXauYDKN2pX7Fsw4YK4l+7TfVXbs28HaMB9woGlQR1LkAEFssqVpWwbhFh4bYvt/RZ3QA9znz09Q8/nZSWmL9f6SbzVbf0PAfOdGtBBhqJ8s9TNciMkGJ8X2HLP1CdSQssGcZkw5CeRDR1qYONSQRoPhH2LhQI0N6FS3p/EsYA5OS3p+QM3DUuTQb6QfCWxs/upAR9VRgydAcqieVNcXWK+zFUedXfpZSoKM02MakTSrDnKSnA7KzZTCXftt+Ef9BV8dB2IWQqEtLibIxo8aZiJDqL+PQM1E6OhPOOewmpbNjOm1iQBaDLqj3lnoSxfpUn42Ke3pkoQxNmIaYrVdVn7tJY9znmidrMd5xTC9jzmKr6emQqD7NUhPSuaIrEhDLqAKZPfp6d5b/PVKvyocRz7BAuvFqXrELCd8DUWQmzdT6Zs5y+y9ppK0/dm/7iizPBJR2VUvbecRzqzKJ24UKIl6g2kJD+voBhV1rglEoNx4jxElbq+QfPP2A8hnGhDwRG17HSV6ip/BvlWzimQ3TZ7QpijdRix5gWTD/u78UNngYlD8Re4NQf2QtArsqrBq3JAsE0K6p1qqNEAkEzICiYgafaZD37gap5IZAY6TiFHl6TwMSLK/SSgFPjoZPqcrZ9TRPOFGnxkrE5ghnroQaPmA4Cl1W9HUwyZsYQ+q4XhIbd5X2qsTQHXMtG1yySa4HXybpjTo7M44bZZHhFdmEeiOaonAPplFdcA4xqoo9ZP/D7yN9RdzAWUdL8yNB/qbfcntun3372s8RvUdBzd9hrSO9Q4ueFCF/jGBNxIyW8u4D5KKUSHEcWTkPrDfp1bQIG4bBfJwvNKAzrYQdUojreVuv70+DU/EtCh3taok1GyHVSfNXyiqXbZY5osmEZwSeRQKzXq3Po8RhvW5pp+DVx8cMeinuf9QObKAX2q+qdeQbOGTX+mwelT7d/k0wTtrKMHSKJ4Smhvyt3iWn3s/dpmgg7IH1hmEK7W4leOM/PAZCcbAbkBeh4Dn6sxGiDpQMYk4gj9vKRjbWmYEjnvzE/6rm7mezofCY3Wjk0N4L36vEmjvHu2HMBd5eqBn9UYO8mYmaEdf4fx3vW9e2oEPX1xWJBCAMY4mFcT/oCmYY7CDy/wBmIfk2JqGM2UIlh202wjz6iy8vPtfvvMfQNbXAnZVlPN0nfBe8lKCgbMrGpQSYWEcuUIInZaIaFZFMAaNmUMFFG6f/7aQlNTQwVMMJMysTPmUN2fxI4fqir1Am6wfS5OXEfu7SP7bhP5fn83DYT1DdkfIH7mRsgEv/T83f0uf7pXxjJjTHuxnsw7JARZDCFzozvZCaDy4Ay/hAzimSl3EgWhcU5LQ9aVR+ZshN5V4BCF7Hf9lkqAeHPnkojkHx9ywdpYXb2bT8pMSC+pC+6PJlPIfV7kokTziol9DPDj3lna3qnQLd6Xd1V5RBGgs02JrBrN1m62zKcYQjOvKVYDi7v78+IGBBJqa1uPLjLuhqXEli41sYeM6VsYukBGO6w7ycSkP4oK/sG19VUrxDuqEQAR5i26J9jTGCBXm3XF3FBnWK+C7NCyaSZd2/fb7IoFD3DrzqqybDhZc3loJuTZLj4tTyumuLW7c225s/2IqguZtDJoxw4dAbgn4rth4xbMgsbw6Bou8ISKhuW79dv5hpV0iaMbgu/IWVr5KyK5R9keNyyfyTG6JZn7kn8UJ/hh0Y2R049G+0QQCrBhS5zNNW+jWHlK0Gz67YdiRFaCqozM37F7+JAaoNhrxf2oxrolJG9FtpuGsZyaDfSMJa79na6ZBJ9lGOBJoFG+eMk1CjWW0/CWa517Osp7FEGWDaaF7tqE3N24k2T41dwILWR3wbd0a9J30RDZHqaKYH8RGoUps2kxKClP/ziL/5Upihnvz/lVo/H6FyFlm1u7li37GvJLtaqvFVGU3h7GOCKkYMyQmuB+sr+wmbyGxEhgaSwE7qzs104kAmQrWvZLvkkXDdXZSDc7erGH37x8z8jA7EMbcCAwW5F+sEn0zBiJKEc11MW5g5/fyHTpOkxhasMmartV0+1fYZNtVGPdsrYRuAdbFiLq85apVJZtXEBtJ9SK3H7ADffEMNCNqbsstBLPyK/Nry3eiA7/PyBU9qwEKeqa7ez8OOuKZbda7HVliAZVsNeNfnsEqGfYYUdO4QKZ4KMGOWb1VUej09QsQtiRUzT9as4X6t6ENgqt/snIrsJkrTtDxKPXUQV5NCkSBsr5m3LvmxhlM6bN69hJttwy2exf/DdvCxCYnq+tXcA5W/Pzt6uIFsTfwndOxrcCqIWSuiwA44ZFtXXM7FGhgAV13jbUqvs4U48IIBD9tHeqgrAgxVO8xP0bhU612ObWlkS+Zm74lD9byRADxB4V0SHV4c8bpYi4VYxEHV/f1fAgjQo/FMsx9r6wirOQW84wPbt8/ILbJ0f/zlq6hUOx0P5rT9gJ/qGqAEW8jbDs9n4bxsYv0dvKUUXrptngP3AFjjDtsDwyJ4SRyZ2AZBkn28DoJeO7wXuMstHjOcGBavBIzp1zPM6LbAOUgDlgUSFACI7v7BlWvZ2Av3hjB7teiY/2nUw3OwEkcbu5cRdqeLKRMTEeIBrVOmKD8Ta6Bv1auHK7OyZZHDsFcuOn3Gma9ir+/sm5nhJzGLpq4d4Y8fFbZYsFdkAWk7gdHd9tQFqHTeNAT/A/8X6DXsINFbZDLpa+O9ydQCtw9BFZBtsQgprM9xOr9WjcddAQTfYWJbVpvibNfRVXhZUvMy2WEtEhbZQado7o8I74llLL/Gw1haz/6XL8LBZWUzwQENGwS4jCcMvKZx6/sianV3mBI1+85joxgGqzn8BaNtuybQ+8dsiKxB7W/zOSiu2FOUF6RiCr8ghKGXj9LPHT85ZWFV6BmCUuCtgfjlsVGkfYaN43Ot9Tocy6jeqYHRqYfTvWxlxw/czY4LP5McE2+j44fcHzt7pet3vRY4BHYfBUNjlMYwWNg4wbhj7bjIQ3jvVvQOiuzvVtxYp8ptgh8y3LGfvIKYdkdrirdnZtyodenrs+3tr3x98f2X9pe8ffH/w0pFjxNkuWawypapLeCrwqo4AX8YmD/hU3avekRjDd1SM4TsUORz/Rl8VgHb2wLkjYovvVWKypy4k9s8gZ/8IKtbeVzcN6+d1OB6BWy4jZ5coCqVLJejaMoIn8G5eisHi8G5WA0ooie9cSgHGaJ/1q2riEJGTi/aSEGYuofyl3sDhDdaH3Onw2VNjf7+gBDOnV9RQq3EhpVBfjFimhP3kRrwoka7WmJdTKGEW7/28GMaccUB0ue1Xr/uwKm5Q6/u99q59rxL/HfRgwnz4CGKa/BmJ2tYBTEEMqMIPLRATvISsAsIF5+8Da9G8UcWzUBSVv6xXOGCiaTEMdsxaDVt7GYil67GLKjeAri9X8MDEryg4WlaisRvUOTwWFs765rZPaNgkM9gz23BM3EF657FGUMnMPPudJoD3BQZIuJN2ckMIbEK+IQw6EUSyUhlJnBmvPw1VwJkNLJ56jdz62f/LtD66nJatsVSDaIlgProfJ651KvzIxIZ/8+yfWS7FL6ms2roi0zOXncwcyNRLGM5DibxBaijeBavdvo6Lhqu7zCSdzUxJJ5b3+R6ERbLRtOAQCSKwVHtRsgrxxGLg0JxbnLH2rptXWGNXJmvMyI/9pnzHz9+l1Fsfy/l79jkM8jcs2e2jEjo1UfYsKgPF3yEtXAyf7r4TM/sOcYknUg2MAY44GJ4e6l0QbzdJTiMWT+tG5Ya7CcdtDabAQDItExcuDxogK82/XHm5cnw+aVEGRWVm1ZooWe6PPiyMOtYNP0KzJkwCGXmdk2A7zDXNypFBRXxkk8aFwUt5DpqJsU3WVy0b2XSGdpfsHQQg7MAp1A77jvGdRqNh2A2QcG6RSum8Njd3oEVR/iWfHUWYpL4tjh03l5/Uobfy7mFaL/YeZvy1S0u/dskw6mXIp4YxvXWujol1DP12o/TsE5bNlNiXsApqfl87XGftC2yKvp6fXi7MeFcWWozOoDlgnlLXlPXo0H3aav592kacwBZdk4H4V+n003bAakXVFgsvKqkrWDidDDUmBr0+SRNirPxnrBQU66zGiyYC1bI6OdIo9yik7BlqzfrMmvUWuoLDCrLhM4a+Sic9XUutMvVpt2jwbB5LcCAoaYGBdCjBTXZIfpyuV3eMtc+PHyjQrFtvlcmOHw/1cnrvXU7suryb+bHTx8wAh9wggvq3EltFMWR8A/Lnjgy5Dg4UZm+zbnLFnStVzBL1kr0deH4If92hF+DfSs9r2JX7Qc+ueGGd/tmxK80AHo78TZgfzmi9YNBru7sOWh180AKkT8TNaecpXtSYnSh6wJfo3Yd5QwmXDeS2H1CUMEwhy1n5jggyVfmMYuWKjVyEfWpa8b3Brs5x/9zIDJVgniBDzV+4tbih2nJ1g6rgwb/7V6DujRh1AAfxHrJH4cUd0wD5FlNMxnw8k8C/GuZqwms5dkBGK+hq0gEZ+MagFTQiKATy92raG/mNQzgj0xINCHNWTPIbyizu788IuyBaBFVy/tt/kIAl6SR2Nc/c2wzDyIHjikyN5z37rGcve/aqZ9/37GuewMitku5Lty2oK9vdKmo+DJTLXuvb7TF51vwKP7RBT4o/U+a1oCoTbHC3sy6HFKBcvZSHSv4QP+rH2ftY02d5OmnT9OwRnnQzcLDOjPb32+ZS9dQSHUi3qqe4+H0L+SqsG//qWYsIYHSLY82N7KaLGTp3nbWRDc+hOH+AMKSWQP2+WD11cXb24ky1OrLW2as15rLngEZXg+1qHDi3yD4PA3VhztBUQ3M2rEYVkQqvNtjtbIZtgu7BJNMs8rYBJQZR2PdrPRd0WcQwBzbUDLbctlvuYyrVXVki8js9TOqnFDHs7eqOt9ZY39+HP7sIuvgdv/HyayeABbXEsKMKcqMumqJAalNxGe2damt29tj//R1zba78mlturO+9fLAvP796YB05FsAxNYjMlrXYcrbt8/E6nrTZx6BrvioXL5LLXkMTbI+BwoUtON1W2Aou+Z3QVMAOPbQqiVSDEtxvJH9fqo5ycnQveGtL61UT/0XnxaPzGCeFhLqec9OWSupnoNCWlbnPYxs5K4HffEaaPRzO1sD0PDuq8FdxsocdTA+3Gw4xQ9wJ0rZPGBkJ+fjrQw8pKDM3Hy9R92xK2pJO00d3kYdP08cvHlOJ+vjzdKo+/sOyfUl7PAfPjmA2lXSTXtdudNOPj0T26Sir6rd9eyudNbDXTV5FDlLpANdqkT3M6EE6kwY8TiO6C7IccUg6vIMZyHiJ/f34cwVtu27QHZgjcfklLmku8x22OKRWHJP9RTx++CPMAEvWXqxGy0wg6SQonRD6CHod3sYrKUPSBaNwWG/RTRiUtPd6cLwE2wzgnAF4y3cZ/N1k7WSW1ZpCNLm1y7A+2y2TG/YxwWmTcleGfQ4PD6fY9cFBtQllMKfBTss8mTH/NdoJq8hPV7NuO2u0D9oYjw47JacEbMUtLLCV9btYYTlA8YEniLFHVTMB2H+xukNGRZBNTOM7FC9vn42qF/n5BGfMy4sXKyCrBRGyGjqH7nerp+53j96XCSMMy7lo3/NZWsjL3cg8G9nzr1p21K7e80+dmn919vgrr9hd9u0kfenjF/zEqWSj39x0zSN7UfvAPrLXpX/78O9cZf64tcHJ6qJIOEsK+lzjuMEg3Jf+qMMi2+wUQ8Oy8nCZY4dLHyQBz4zaL1VOYrRRN69EV5To55XoixIb3zmytxa1oS4ovK6MCOQRfmBC5ys911tB4jaP28acYcVjPUjNcmmHpnfBq5DQjjLUcj+E4y/aNY1yGSQ7dv4a9g5sqjGFyscNe2mCYoOwARWOqKQfnebJEfDoQpsZ5o5m8gSIGPllhEQBUoa4jruF4kYr7DETUg1tO3gp5fZ64jMzKjND00zVgMn1G0HXR6RVjt55enm5duHG9dXayvmbb52/Ccfetns/QDtI11jotMxb6vXlRf0+92L+fW7UB8GvRoI2kGlckJ7UsF9YOIj82nYAf6AMuxtzD42pe7EIU1fB0t2TcKoXkaPba6k5tOMptHfshr1bxJfw5Ol1Y91qJv6SceTMzr6pIW8WHA88LYXHDoNi/i7Lklwb52pIi+FVwixVBLYNzZRdO7LH9qELxNFB2/bClo9C+hrJ4mj8XmIWEY+p0CN0OUqAw7I3bqkyOcj2IJMvwZY+fnJujnczd04bLX6LOSKLGBHgqLpFAMAkNsQLOFIToCxV/dlZUGbcwGNA2fKeUX2o3Daqj5l5AbfUi4OJq/A4Fw4pLkBDPmU3K189ffYJ83l6/mCGvK6WQEFdklhw+/tL1D+ZQO5iNYY/XsqCP16K4Y9ZciiCQF7KgUBeGgeBvJTAcqM6dRjkpST+mwJ1vBRj793KR+hdOB3BQoIYKn1HxkP/no1g6zY9Fkl8Ud6lEW0LaByYTm921mMzKCdwJElipNDByAEF8CxLYXXRlnPsKUtxUfkMY4zn3FPn/GJiAcRUXJx4KmBofC7s25SiebIZuecrM3I2yp0SGP+ogrgJ8aYvc3UQn1unvnfcf8WarE3OPyXDWe3maY2nQ/Oy2NF9wReWqqfqCPyNeh3RfbbmaEGZ+uLMvOMt7nrmEjA5Z0ZCRZeuUygiWu/L7qCO9Yzozne0XhmEIBHAC7cQQH2JmfhR9yybt+IvGBYdV4HuIvl13FLrWErXwfxn8l4XuUsVtxqCzm77iEHj9n1TGkLiErbRcI24k2Fv2Hb7uU3Uu3Bcw5O2V4aPS+yjeNkLBmSryR8fp4NbFVG01quzWMOy+G0p/RsmeCSL02W7bl+H06DVjcW6eSbW1f2gba4Kf+Rj59HCEFW3PbNmz0N5Aq6Gn5mDDsxDed566TwUeel8xilxE9R61GBYczKd6Kp0y2bWJu8oULSScv1C1YOXbyesFXj9fxmkoh12ZJFPqUcZlJEdn6rOCdZxi0+ZILxbaM8gCxV8oOwIJn4SLR6dP7BvHbCy9CKdnV6cZcHTsix4PKhHhvqIJ4lgTlFuaHG7mBfnYKBOzJNbK/CADTS7etLsylEseIqLR89+ixf4+AROqQ1VxBvpIt5oUhFvlC3igVxTi8Ia5qbQCql+aTgrjNpHeT5pnron1FJBnX6Ve/Ebi5GjqcXIEbHBu1Vh5bytWTcFTXFrJq7RCE2QggveIsZCVDNnnZpD0+RVolTX10gVXx9JuXFpzVtf9Hw8Puizg/9gsq8lxFQ4lxD8POYcC8fgdSAhy77DjS83yMxBuXtTFIC+slkA/xaQBEMbWXb7bmeA2opcLul2JZdUZn9tBWgY3SV3PWbQ2SNnAREMgG8cgE4Lq9sDloHXS0eNReNoojWp91m4veEgbbFmlLX41mhZ0CTePv7vTMxKbgfhl27uRWHPmbM3/Za7HaA7waAThlHL0HcBsGXZ2XVMtJnUQLCt69xsqJCcN4bkkHh1OoCVj6kOBOTEz2xrKCUWBEV4efS0kE+oRJdeTJfmaJFIE72yjkLPFDLks+EJ0cjexM18DnfdmShjW3rkEmbmHA4aj888KmJpPnVg2EKegp/ER1txiIXHWliyfQkZzujovJWnn03MI/Q5bqocgvnJj/b3Z+R9kZZCc4kERNgPuTdDI8taAEUJyGjJ0qe7X6SoJ0aw9A1HsGTFhKxlx7klBnBRDuCiPgBQgxdu0QBuabtHMHfeobRJAOS/QUQmIG4V4CUzbQJqYTY3fIb9diKcAc4tHNKqWI/ULEkOujQpB13K5qCxZh35Pa0YE03tpvqMtFN01RCC3S37IvT51lEhjV7kXufIK1+Cb2oQ3jdlnUtTs84l5oofidC1RAKvGeYAWvDzXOGvaNncYyc215U52TW6iaiLyRdtVK8hJdTcrieb0paFx0+4yeRiS2OSi62S+HPP59nF7vkxb7vna5LvPV9JMAZftAxj+J6WYmxB6PFpr/pvaanzsnGhuQBUjepF3SX4YlbGTC82KbGAARM47SQvjniAgSsrgPEtnI0WM1MvnY0cEPyVVM5K2hmedQPRkxiKHcOJlfkwJQlrTWW3oxVhTeppjwg5T2YWkb1IJd9EyqXkm/b5iG7OEWGixoId5HZJPp3Legibw34rSpgfpKUBDlo6pGtCeY1jQfBKh86N617ibdTa+uzOwcM17zmePXBAr/aYXo2kwdRnJveDsFQG4UKp+oQl3oazmTez4qU6ycqX549bsVs6K/xmWGxQYQwAvSo8zrtfn19cW3fkwOE83/XMEZwAcbdOWkxbX5c2lELb9kBBpZspvE4FSSZxcTr1bel4S7dWnklYRQ6NwKWO7B3xF41SMIhTWaEH3JG96wP2WNx38sf32NOe1+NQOewGRlzROA3+QN7HOLvCAUy7QHJ20gB7b7dsEKTdQTRw3vbRXagTDAaORyZzlL0U09gSM5kzBAy6ohiBIt2rbbpsaxS5H0I53RewOOSeFtLvl+Sb42L2k/5ujba/A2e323Pm52y3HTS7aCkEVsrSjxs2FrgFR45jEOrtQQHiRcZAyu1g258IC8ALI8LlVJ3lPiXb+5dF8BhRhc8s4gWT7pVVA7ndP3n2W+bPi3yOuBwGDMbYIOPgM+SIulsaXgmsMA99bIXD/mAxHwjkD7/4yx+VDDvxgvSP5DsTtE3KAiErQrc9SjG0Eflt58heopzm5Yuef39LbWhl1jXoojGojbj7GHsW7sS433AXorWzHvHNxUjpfCROvD0RnY5YFLC9aOVR9zQQrVulnEJYC07SGVuA9Sn1iniMM9TrTujzz8eXlwkmI+cT3qvxVE94hS9cB0kTTDsmRngSTKGJ2+NTJ4lOlNthMxQ4H63AA/HHcGbmNOfkcdlmWEVBtxFmoULN6zssvmPFUActWU9eoEMvo4bBcFMaT8ZgSrUCLeddETYYli0luIzx9U9/mOzZwTjMJaR6pjkCwd9GIL2IiB6BLoDiifzbbbcHZxhSP9LBwD9kgkPWDnCUZrOdTL5Yi/b3b/sKrrYCgZtG1lZ+5NX4OzBNHp6JrCbphayU1IIYeIMJCYC5Us2x7/Cn798b+oPodBeketzkF0C7Zg5zUhFFNQbOQpCaYmEjJU5DLf3dFUqtEfaFa7eIztvfh3MUhIiBqbluWdbBBMRAqcUKdsUffvGTPyNCKNwZfGmykZyToMyXY/dm5AMgMCb8m/kcsnsPmDQcG5t1Pq2sAP52pj3ss5+yEraOleViQa2OnJe7WKFkJ3zkQHdha2uzlbZ0E8z8cdYP4ZDt8ZUdLXgpXOgMhqdRjw1kIDsE08F7c2b3MgbDN7jheKCtO+OSl7tRSCDIeykuaRMoFGZ7wBsJ1OPijg3qoBQbSTHWJpAIGFUOODbTeoQIgKiPCFT7WKTsQg+BD0oinp3jQoJqkcqWqqNtP/vNs8+ff0Dhmk9EUTwB5aWxY7QDhAhN71dY25nLieQWIcZ+Re4mXXUJ7jDnlNEN9/LkuUnqCN+T2PkMQ0Pb5wdp/KhHX/1K8pt0dAeTJLDr0hezgLPz3eX1MTksC73EudgMd5Sa3wwTqXiKQx7HtJMd2ZeN4CdbRiQoRCUG4fFD0H7XpwrhYwxxgZOi6U25FbzxW0ETGIwE6PLzBxKpFsbD1diJ40OV2WP4jGyVRFogbZx8hJuml4lInnVci5OvNexsKovi5YUcelrIoY3IHEG3CVTj3t+dPgJxHKwp793EAGaRNoRkrGKqeF0rnu0zoQspRb3kl05xN3VDCTrQqDf+i/l1YjG1otOm/irshudPjPUk9HJ6zvvaAItvwQVW5MagWcbrbkKBikc+DaF2wr7/H7oFMX2izJpq2KdjhwkVUo4nYNAF6K8f/g8OJ+dMwND8Ti/anTihI2dfHASnJFHaf/drw75sG4iH9W/k3PaIR7NPyeg4BpOSkFY7LErJ5A2aEloohTFzdyYs6EQR7ptDkMIIWA+E+TOqHI+yuzqGK3T4JQ70ZPqaBxjiKeP5xNcJLSG8L2PEUylgGhh7Yhd8Wy9iMXF7KXBF6rQ+9W7OxNPExd/cOr/jIe3XNM97VR/N20m0sfNehRfFGJzvpOQkkUAdNwRFGX6MebImOzh4V8YL+X/xD1NAD8g+JSZmIu6jzBU5yWh0dSOLrrgPD/cpfeFD//mPphq6zA7fPDW3WEhRrtdUT5rTZlPZy7Z5VtKDgB84C5SA1zf5ObSzCQ3f4WTGamTVJIVSBq+Ik/hkQjOBOoeKCPuzv/73f/6hkceZum6aM9GzZHcYlCPZFTEmdlLIVKgrbU6cxo6AFWipBpk1ahCF6JzTc5ukqqNnKdqoZ0Y6OMFf/ZOq3CAgJU6rpg+Vvv5//m2a/GEbyqiA89ar0kl05vKiwVTTDCa81xExguxIOZThDDbAD/9OgWMRizHtthb9z5Qo2LXIlAJFbP2YVqxAWE6gUC7VJ5TLkomiRgBcxjKmjecvHOQ1U/HL/WNoMH/4xU/+kckin1G6mEdM/aYt9fxdns7rKVvLTCt5FoeJhzih8ZzZzhETFF38RYiAloazyGqJ7aHbxnCQZNNG1B9i7uoxScQ6fjNL7dAubnquxxSgV3s7pfm53o6GC3Nybo6+rgT3ocrKyeN9QtjnKDKvnvjeiZObqiHeyNj/cf6LFG76pNIXDoS0R5UFTLT9WYjNJAjlbEMkjDPGGNGIOsZlGeU0CzjqNoMarQBx9wN/YK7wJGFrnj1at6bQobPmwCsaP267pYUOupq9yTSWpSm33NK0rKXolurH74IS460fbjJHNJl4b70+2b4RAaxFwo3eD74XRoEXtZyNI3th/+C7GwcC1lrV8tn9TtG1aSvsYPBAd+BnCvQ5mTVafj8s8b9lYJWtlKyLuymp7BkTX05BrQRo2Y2MyW6G6JUtICjt5swghOknFKf0ULsRSeYQ+5RQzei6VNlErePf9k2QkIWR0X4B//1PnsOslHGLi8bZ3zJEB8LXl5e4aKV9DPtfDFQAmBrx/XvDB8YMVFbbdPGCXLmHL1oD8dY0i8Df0a1PP/tZ1oDGXIPlVff13zzInojD1vfwVyhj/P4LyjaLQW9KcupD1okQcKUMCLdD1vVpVm6FcZecOfWtGV//5n+QlSYgT23jaHzSrU8IV8R2aKaVcNpsAP/xYmbCgKxfYBw6eXCbcOn/U4iXtPU+YVlPJTuMRYYJ7FG03iz70jgTTlENSUU6/43NoKlbZAWtGoWn6VA1qhuCrBkg4QMOxamlxpxo4JiGiETbojw9qX2gPjxtctlqy98lwYpZR3FM+b49UjrN7ueYBt+KJmzkybOPCI3rHSQOymRwiPaM33/y+8+O/f6LghxDOYiW8v925hSNz5KHaFZwxNXdgZ+SQnLF+0lFEWg1DxpTFw7ikxztegz+lbSodBem5SmoxAWDls5LpMowgU4wRgBVZ7Dc7AfqYNMqgfQRPf5N1ANsMwrafqwdhN0xmsEfSSfIdq1QpuTtgelZRbpBV80x5RXeZunAo6g8qCl41oUmoZzKcluP3Rhux71PqI3JG23VzFBC+RXxkOmSBHNXwq+fTLo7eBO5V8ypd3gY2SQQuj/5x2JeMsEoxl0kiu7rx8aawjv16yuSyyW7LKFy8dVfsevxsc6TQQc1E665YQxVkwBj0DbidgcYRA26zgSa/2nzelRppekvCzE3rxkbDrRbpDzO2cKgogHqOgVTTx3o/Ed3YJBwaCumT+4nraqGsMTcNUDGhZGDfAJtesKbEN4Oyzapf+fqscrVKKbuBdxbU0v3y1NeX39TXwPNHtdihrf5+ZO29KlmRwj5HCWdqaVtjlnmVPr4TsPF/xkH0ySwzEkJneY7+Y4Kk+fFvNi39/ou4lY6I8oDhAnlDdCkCjxqRgzIgAuuSK5eBR7UeKCs8iV2FMh1IIKy2jgy3uVOBvIXcjQozlF/OK8FJDzptsBNT4U3+A341ppEfja+/tnfZxvDBcMtsVTEpKtjWmZxDZD0NFCMP8aEDHoK/gtMajyPHFdI4WPXvUlP9r4btLMSHQqLVIaW98LF3T/84ocPS2MafRFybqwzx2CZ/5EuZQWnDS5L1lEDC/vijhpqhKUFLzxSXgSPP3xi4f86fFmaqI0x4sTh2OTmIM0lWf73qXZ7Sax81x+llNwnaK2M9b0XvtW//pvHpUQjnBlL7jrmqgTbilwdQP5DliLy8NtqxTuUc+f/MRvohdFwd6Q4KCokfNirhag/HFB2AfrA5ePJiJa9kUycPpneTtcQxrR2reJri7Q1TV4koTDyGabNmczC+g1H9vO/Z448Uw5uzC1KaniqRZX8S/4Ig2PXM1OPbex1TnrtHmNqIryAe0p3LZ/+kdbuR59OPbii26BsQ/LH/JoSM8JTeqc/0tg+PMzY8m7N0gNTr3RjE/S3P66JEvpsJsmrwBKcHhoOCjOoYwbrBywnl2ox13iwS2FpvlejW4rxLHirFwxK/O9UDBhemORY6ur+JfoVRKp0O+fSBu+wKbnMlxPc1ByqZxPcyhT3Ntu8/630NedCp7h/CTupNFn+EeYT/Rv2DiwFyaLtr3nrYpYnn+IvyRvzXSZ8PuBemgzH9tsax+rUnST8EOFR/C30KeOCbdzK59y2jb/d7/XDTlhGSJzeJC77Gcb7P38ny/gem3iYKV25MRNb3rBBBo0qjb7v1watoId5p2qYA3OA4Yv7+yf8VxBB0tDcSSiqg843dJ75SvhGPy0CTmBWpgfAXD7EzJMicvEpy1CPkSIfJXyBi5g1uaQoAAuqNw5GgKC3P9qthOf7JGgLYZuQMALPUY0aGXy69bI2uYrHYabbrOHUkyuTsN2/dsJ9efNkkWfla2i+1Tx1mTOuEvcTnJJfFjcYmPSRPfI13EDFifnsTjgL5AwGCuAg20cNg6kzdE72mEUqX9cila+lApVTRC0C/niks7Q+6UYiOpWR9+Nl7ydIRMRvkVFhWsvfKjSTrDB2ARnnqTG2EsL+VY9QlF8nriN5J2foF9gsTWtRHzi0r857suyCBZVIiF+tHziX6j7iN298+6wZx4GOgPiNE/DfK8a6Zg+Y9CI6bPMwqa0xXqpvoIWAhUBtgD58UKKT/wM8WzfUswODQZn35zjqxkvwXtBuHz7OC9+e0Mk47TCwJvhDwo/+RboBKx30CjunWV/oip1MeF5syDMNlNX38lxti9Bc8EaqxP8yZ9cSrDua5rZUB/i7AtdxET7RmBk+Fh/xVS8/DtustWzC2cJ6nS1bpONyhvYoGLScmRkUfiqBtw7jvoVPrsKHGz2o4Bx8OO15zgX8O9hyzkQ2yvQrPb8euG14k8v4A/agRk5UNuLCOS68chnNUReCHSe03YC+DByJxecGNYnwSj+RaoAxSwiW+d2TVQIlg8+vx2MvMMXR/DEzz4QQTcobOdBMWXlPswIiZIZmHs5pTOrTFEdSiDsjylTM0huQ+F5ieAcki+B9UiaC0jugN7NU5oQ+x3yt7Y3NLlDR6GBDuHpzFN2RVXwblh3ImrhcjQ/clxPnrQDzkI7KJ3LMNDDwRMbrPN9kkLNZ3kO0KMQQETwtI+aFQE6cGTHF5vPJs3/B9qgMm8zKob0nM4EaBAfTLmriuONsYYf7xNut7qlilbjnNoOuy06lwzm1YhY6Fo4msymuRK9X57Wx3GQY8coQfvfr0vN3YS55rBbLddgAEdzcYzvSaXUPbAHVB9tWxQaEDTy/v49/Wt39fUKsdzeBe5ZXIuv1KuHySWR5WyQUOIV7vnxrbak8v35qnjJNDAct08C8lXhBTd8w54+FKHUqJ+QJKKA9o0tApsbiNIetmCE4ETwClR0fFHLzj+L6JU7t7EALGXQ0V3oVI46K5WQtxhAT4tobPmMR022GTHI6BeucoqejGj2xBPYY5v6vxkE6CAXIvwETkAEkJh5PmDVcjSJhryadB8e5yOpcqzC84hARFZptcILwism8XhOcNgYmRT6YvCCAaobtrFragfpUCeGbFF8tkxfmMMB46oqbNc3lnMD25WRgezqAXA9qn7zRCcZ6gwHzJCO3VW06z+Q7ZvVyTc3phcsJuywe3PiYy+RPlmLYTaMqJttLFEp4+uhlM3Ehi66rxmovYW9CKI7f/YpcdBDaGXP3mBYy8QvQ0zu+2zctJukXb38oghFbFJuKRpbSs988fxdo/F0i9+fvQNF/FqLZWACZbeRyAsziIbzzEfMpeufZJ0yfI5s7T0Wdc+OtqlEwN90Kwt+C8ABdBxESH0CLflSDx4sb2HdRH5x9ym9kDEnPd1bkfSfcZHnSc6LvyZj1WOTPPoRCKRqhqE1+LmtnzeRh6RMZCX/4d4Uy/Ee4J1Om6ulGwT+DtDA0MmBPpkYwKQ063xqISTFUyVTjzoLimGRFMgE0XjxgRj78SjwAfiKt5pxIq7knUsHwMqFRcqBQ4gW6Dpo9guc4PZ+mNRz4YlpB7iIQeWdVVfjD7hvRrnMX/t4kHGg8127ngySTZZLBOzt+294cDnYdzGLc9/2VVtBzcq3hsofLoodH9B7u3eRogBHL9nCQ1VuRit2JhE2hCTx65O4OHL8iPmKm8sOMC0H3zrr9iLpDSeFsApF23ozsoBsgbP05OPacI5EczIoYzBltLJTqR+0shw8L/IGzgiXdyOnYiENzFqnCaXLzimyd7QJONIj+bpr3c4jrfpK4CISFvXMt551rHPHF4vCu9/gGGWF+ZJ4o6J4679KAw8YY+WkzkEIYcjnneO6jA5tAhkEpULHizTxYNczhYd7L+xE9JHNA5jGVz1hK5HN9k/eIp43RxZGzrXgi3iqYCBypyPI03YxIEtqCOU+TNW/8bLTjnIXJhv6uBM2u23aWBaWKhE5Y9TbPkMLsB41hl5zzSqstUCXhkHNAVOPenBFL3SkaxEtPu8ueYcv4XaYeFnY5IbbVWy538M/sdFftNM2l0u25rG4jWiyVjLt8v2VapEz7SqKNqDpJgt2l5UvLtZXVGzfPX7h54/rqYs5zh0Y8M2NGs7NmVOmEno8GaRxcOey2dw1M7YvfavgN0yfnqvPdRYNya5X5DJWpkj7MOIg6/JcohLOhD2Ize47Um2ccOPQbqdZZ6py+tQcSHk5h2PYrfr+PGL5rNB/rpe9ktlbqwn8NjHHQsln2seVkruQObj5YAdytxryhpxdqV0PP7FsL0IE+y8yW29nFdgV2lwc8gdHWKuw9olpgs5aT+LHmKT/a/awczqJfNnaK4Rz71X4Sy7jCuwPnP1SH+U0Ui4xIxCmRjQNE3WUfI7aVMqaUGi413ACTHNiRSKLSrf5RVrwL5NytkNn60urStep/fx30oBIZhaqGMAodP9HbWcC8wmVKH+CwWJcFtAmVG24naO86q24r7Lj2wO0OygO/HzQWmAXpO5uvzdfn66zsSBqQFtqwDcsisqbyPeNUIknJhxzHUNeRXt/sn3odpRrRQ6p2gPbjykmYfK2ZV6EZ3gkOi3Pqvx/lSZ4iTFMik75G1lHj9WNY76nXj8H4TxnAaSVzuYbMhSdnojxVyG0WlEQYrrdLqciQXLmzrLFYkFXj3I2lswz54xqUxlX3QR7o1n3MMms5CCuv4EP79itziQcvY5Jmzm+xd3bNO4Au/bf/H9vsu2+O2QMA',
			),
			'storefront.css' => array(
				'mime' => 'text/css; charset=UTF-8',
				'gz'   => 'H4sIAItMk2oC/9V9W4/jSpLeu38FPYMGWrOSlqRIilJhB/NgGDCwD4btB6+NeaBIqsRtSdSQVFfXEeq/O+8ZmRlJUt3VC/gUTneXROYlMjMyLl9E7Lu2HR6rVX9cFWVZX4d98Of6uNkl9Qv4cBXTj7Mo3lTGxxvy8THd1eHB+Lhvj7Sd4/EYHmP+zeGVPUp/+Adl0VX8GfIf/2iof9DXwpL+8I8u96Gmj+VRnuaik0PbVXVHBxSSH9F+V1TNvd8HUXz7wT/pT0XVvu2DMIhuP4KE/N+9Hoqv4ZL+rMN8wR871QVpbXUiD6o3i2NNehmG9rIP6uv3r+yDoquLVXPt60F8t6RviFaK6tJcD0UnWylut9WxpdT8X8WpvRTLoH/vh/qyujfLgH57rlf8E/JNce1Xfd01x4+/LP+yP9THtqvJP4rjUHePQ/tj1Td/NNfXPZ846f3Hx5r2ebs9qqary6Fpr/tuOL9QAq6Kc/NKfm1eT8MLHcLqWFya8/v+e9F91QNbvJTtue3Ep4L4i5dDUX577dr7tdLfHF4XL2R2hFK0zX0Uht9PL7eiqsiYJJnK4lx+3RLaB/8UqBcBHReETm/14VszsN5X/YVsuxOdVHEdGjLkoq8rOaugePDBNdcTIcvA51XVZdsVbK7X9lqrhw930sH1Aacq3yvvXU+aubXNlZDyRWwc+jacqNFac3l9XIofq7emGk50rl9eqqa/nYv3/eHclt/0g9fbfVjK3/r6TNZB/UoHTPcLNirWQkk+L5orWV/eEaHv1yjOyeZZMlLSjoNVEJNtS0h3KbrX5roPg+I+tOz9ob2R3fYAsziT1opu9UoPAjmDX3dhVb8uxaFdijMdJOmX5Z/LPArjWu4AegT5RiHbrN6v866+8N/f+IJvw1Au935LVjgEIyAHgk5Ckuh4rn+8/Pu9H5rjO5sjZSj9rSjJPqiHt7q+vrDtuWrIzu/3lFmQdXktbnt2bunrq7eO/Er/gN2cm++16qW50smuWGee5jLSGiCOYDNxLfcAZQl9e24qzhTiNF3K/9cxYQ3iqAmustvtSHuSBnSXR/SYA6JtY0I1e8DBuiK8lS8wHZA4QGxsRvsp2WXWaF/YuecsLCQ8TMwgD1+Ka3Phx6A//vf7ua+DaJ31ZD8emyuhw8ffvtXvx6641H0gHniEXx6e5sqPrftlGORkiox9fwwt9mr4Yc71+q039wBbUrJ5sfVpyX5ohvf9epc6raijT3th3wr+TDfE49b2DZ/50JTf3l/Im5pNSRYs2PIfZGtW9Q96hOMQoa5gpYx76d2gmR57YGEuA33uzyH/r3gZOsK4+Xj0U8E67oOacLOX9nvdHc/kve9N3xzOtT2bddOTI3IhxBhMEpP9EcSh7ipKwKuaBl19Jtvg+3hHF8Jk1Mq8dk31Qv8gzP5CPhlqMoDz/XLtKf8hXI+wIMqBYvrngjEb9oc6oPx2RZZUHg16LIIQ3hYZ3QSAUvqrYB3lnFRL8br+ZJyH2BMM6O894X7l6SFfJAz5uK+vleCdK8EzesKWh71io3YjBbtLe6eVkbULbGpLWmQ2KVJKPEbKUJCSNXsgxKnM0+PjkZTp0AY5SyGNq4sqDkPY3OrcvraC8ySxZj3s3ybv0Yy3P3XkCJJmjf1C/kUob4wFvTeidZTSm2PkSoqSlN9J7C7S95Cx+xNBGymGJhXcPmxKYOfYW2lqoRzqbBJNHfZvizoWn9/xOeoBK+GBt0y4cPuAS0Qpu4+sJ4JT9JB3ukHCMLUv3x25fM/1QMi+omeAbqzVmi7Z24msCfusJoOgc9WM4NRUFblqmdikPqzP5+bWN71cPiFms2MhRbt1nEJqq6E9S2A5SUg5dUOCR26SDHTByeXiXKm2lMr0gYVBoYxQ6OeJYU5/88T0burM0j3wn5vLre0GIs1y+e5EmnevxMQQcsRIBYeil9mGHnBT+rTmMTVAs2PPyOC6ZLG95XJCULFHsjjLssq8PUPyE40KSPSa3KJbVI1gzQQ6uROPuyrNjV7q/JjXXAAQjN2996ZYpn2HMMKzKwFloYzaUmpIQtA3WZrX13P9BD+d4qAmvzXIy1VkQZnNMQmzxBVaHTEFyiKqNXJqU8EnWXvgdzFm92Mtx8gPFU1ilyT7E92eD0xrBKYAR9fk3ykpm3/L/lxdmh9fyWXcE7F8aT8fbIj2Yk99AUZFD0N7q6FAINfOP0RDDzJGhIzXvqjoHzMHzpboRrRC8oFLyWDdlO3DvQsM/jR1+LHtusnBBZejFxwcza241md92IoD2XCE5zJRm6op5/o4kC3b8e2fS+Hu2HaXPfsXFSv/7euKPLsIeqLL1v/7K+H9C/3YqiUvN8JCEYgDMXWWjfPmHpfpA4IcUsmuwoCLrVTrUcpJ+MKk6eZMfxFXiLAjrOrvZFA9tyOAgydeBdKJmjP4TDcLPsTOrC1lAFZFrQVpDI0F39+CVRBlITMXzHpI6Ue4LcndE2zlmTqALb5cVfqUWNSJg8lblfSOIL2FImMTnHU+vd2ihU/dm81okhBhNObxpypynAMt7TjzdPJ5P3kiubnpwaVJ4wLTVrkv8hiE+sp/ae8DZSGO0QtwI/A0ahOEtiHHOJQB4xBTXe1ta4kf7qT2e3ZBntozVW8FLy6iYlPkdk9uC+vyTH4VrC4GrC52CZvPuJhF98LcDGfOJXN4b/tG416KUmgyJG/4etW1Nw/T1ebAf6J7biFOXyg5Mb7Vn2CFiblmhBtSzTUBBog4YbxHUJbylSz8flpucmZoUQIqO56SraR6W9BNAY1W/4VMlrC5DCgUgAzsBP2iQVH2nAtLHduXULVIJ2VedFjS1uwRD+wG7R5hm3S8cwwzCZvDsQOGmBC3wQAp1vEDAIJgCq5hbbdHiW3nLfnJDSHmdL8cpIAMjmHi6dHWzpA7feKoOr0z0z0gAmSM7eHfydW2OjYD4f7fsZfXt5MhfUXWel3qoTAUe2OHMr6g70++eGy1YpOZ02aC9QAVsHxMARMKwE/rt27fJex7i9n6DR5oMLmuKesH2FyUBc0hBNytSgtDqMM6IEvRjdNnpwZpH0GkMTI8Y8pZ7m+OM9ARTcHcyZml82bWWred9lkw55HnjCJGVGgUSEzmlfu3i2+8tBV9IYjbCA6VcJ3hXVkuo1yaet1BwoEkI7tHWA5mnpIcZWnOCH+e/+byOBNB9/9WxVAoTvsvl6a60lf+Pq41/jk6xrvNVs6vTuttfTAFyz9vtkmURk/088sK6zNz4oInnFJ4iGLqHPn8Sbh9RVGUx9unxysEX4PqnkaGujxdyQ4/1pNLuYm3SaKWMifHobaoEBXbTZ0/1xMyad7RJzVt0YIN++Nvl7pqiuCr1g6DLfPfPPxa/bH5UVdauvS40EyBk/EDJnOyf/GumFCie9YasaOgsZakUOB6QKkUOktfRFsPF35rXQiEEUuzMqXu2UTJYi9VLDE5TamYzBRu7jCV/qU53p7M8cvYlmPQ5OowXJ/xj6cmVSxbiNcJAvi8fSOZAgswi6wj6RFFpBjHqmvNyatLWc+ty6IbHrh+aw1NIlf4qsktYTU1q1+2M+A+sxUV62IPtet767QjelT2EPjAXNPgoaheayClRkASZ//Wy5w6q8xt+DPtpJMClbUXx4R4i/SBngmuEasjJ6XHEZuLbjtYnw9nwzEy/12El2wMcnpWfn4P9szpPKWKz1q5Ft8fzzo8TD1Zyll6D3CLp+OP8giNG/oD5VLDua1468h0L/VrMWnlWAUxtHJoWybjowY6KmeGzF82gMSOASS2DCDR0dCgpY1jE47YNey7YEtNJknsmkxMwA5thOJ1tB0av08zeqcMLbCf+m7GD0l60+bwa5YVBWFAbGxjN0XmXhUjdgtz6JxDLo3P1vQIfTelO8OFhmqI7PWSPD2MWOmk73MCCZZbpiVEOxPnomsf4PRECNggM3iKreG7HtAxrEXIsRZRERVxTf7O4mhTM8O2wAQa9myN2WS/m4DPzLGc4raqaB0fu2CdH7sXB8TVtXKTKTUzViZ0CnOcoyqOctmuDU6xglVI5qZWpjwXl9tXinAgK7SM1/n3t2XEzbqLacTFRnG3DqAWaC9sHbUInOTaoCZAdrVxBBgOwoIdZKkxghRsmdWxLoZ7V1syo4mUlLzdeevhYiCj4xwMZDSBgczk8YdbP0PsAXpIfVPVT19h4uOxjZ4xoKs7hTBfGD5XfWqZwIsKnmyg/AZ2KXeMlcbIkfAjp5eZUGLchOLeNZtY3DUbeydaowrWh+bVEAVzdzdF5jCdJvr7wbABQvFdMD3M+mSt6fFc9CeDoVm+WR8gWYC/jsfskB0w5zvmo+X3TWazic+CGo+ZjnYK2dtcbKizVKU0pzoPnc+Sxd4PyBCv9uYa3VdQZkvFhaNFfIZZ85jpCFNvCvL39U46bsr9UBzuZ7Ik5PfeYXm2l4+L5peCRh6499bIeczhOgdEUKM+ALreR/avxeixkSDTAFyPhhSalnGZo6AoMdbP8B/Zig4gk6CEBA6bunPCA02c4QTroRnO9Sy137y1ImslVn3Ztecztg8Nv8rqB7fNyLHRkcXCEcaboJBrvoVoHIb4cNVfi9tqeL/V+x/BpbhWxdB27+gA9nsZ0KHak05tmwQjrwjHkSm+sSU2d4ndJI0e4t5wfgfnal58CvwwMLSvDwxgiv/2Rqvr2hHArHASYOOAQA8PlsrvlAtf0NgVxycH5y6sBbhuEPNTZoMVoEJTOC363GfMbmD7z0yaFvTHbXB9aCvtVMiRFbQ8YdsYcyiYl1uSKg//OiJP22skj4XcaYQMKvKItcTkwH38Ah6g2BXKF0hTQ0MUTFNBQUZ96yBHzJTAN+qR2CGqENJ2e64wEATQLuxoKDat4URW4/WEIiaGjjBAh4fPEOi7+kbkyK/JMjoStVkyGd3mitwqp8fouxl713ol0L8yndSS9rv2bfyFoGq+P+Y7YCFeZqJhQzKCgpFAS2Pimb81dtc7prJpSPPWaJmR6FOsHOY1G9rS1FjYgSMwevx+4MwAQqyb0mOPR03NT0UjjIYgPANHZYP+dmv6Tz4qtMnnTgp545MXHJUSZV/B+mroFJsnHe6sifNjShX0bHwT/i4iwlomT4Cv4vB3yf6wx+C08QRmjKEGAJ5LtMMGRaQRJAQgc5UPr+dbxZSSjpgdGpFTeAz0uIwDVYjc0NznOnQ+wCgQm9tPQ6p5u8WwujVnjFr5iEwrULsxItIq875qGhNXHWcAffKBeKnGLCFbv7LiWZuZhAc4NDquaao/gbUXQjZrnDIlR483OR7kZoyHEUmrrx/O52vSer+KPHyOsDfryXicI8aaI+p3NuPvbLB3kscMpm29k46/k+p3mC4iuNAKoJjZZhWB3iuiMpD7rG9KvtJxFroRyiNbyGXtPsF3RBzyBKz6g0I8VjKggDgREaaWYe26KiQ/sb1dAkVEjxxoPwvBhizw0bBW564IUfQ3qr4weVkHf/JmRq3qQrcxG4hQ88PTGESP/rgBsFhN6MDsQut9DPP+lVxQyUIPQ+K5HFcbS1/xlEwVr6HLBizgW9OfPM68XDpocwUUmQizRALgucF1Jl7b0ZQiK0yTXRMBhOsnVlwTnc9aYcuE+Yrfw0NbfkOmKmxAdJLcW5mjSQeOWk7gcW6jvvMc4Ok3wtrnog7NoTOIo38p9ODUaz/h5t+iMExoX8KH6sZMhTq4N92pfb4yrAXKvxgY19EIcxOhEULE0s0SCWAm4tUWTH8NdMs652Y/RKIUntBxW0dqxwF+inHDngPgnfF6K2wSDDy7IgwYSgZQ+pK+AfY0tVn4PAriaD5ttOBg4+ngaDRkxavWHghrpV3LaFzdlUi3I7UJIEGSdbRxW2NO0GNH8fpK1gf0kyYwLcljAvrOSC/iCSdgFMZAVrKD9eup7cfc20CuV48jqKc6JT+ZeoyQikzo/Rl0KnhNtH9szoTU+wNjTNe67+n9lS7U0wwrDa5R9cX59WGaMKy4jh3n+mg0Fb8S2ZrxpdpHwT8Hq2gEiM1jL7IRQW1NHTJV0Z9q7ZvwABs5gPmUSG6QM+XFnAAhg4qS58+7ruZZAfF8WxfkRXbaxt0hGOZQK6cyQF+FSMq2a7aPgScq96GjMExh/rMaU47amuR4Pl03lQ3vCQmLw7muFOJmnUiHwLWlG4iwVJHC6ti2lJVAwwY0xsR86Uc2loW9si09oBOuvc3CZiScMSnmFEv4HG8JbE221+zEF9j1D1+/LcEv5+Yxsk9h+IB5423RjSwavZ8f56Ynrw/v51qGxIqbVe01+EIh+M2Y6aG9vY8iK34WZseXF6rVwM8J/OVz1hthUX4PgReEcWkPK9vq8WKhn43o7lBKuCFMppSEP7dpDegIJRBD8mVjGePmWlodAN8qEQugsjUdFQlcENx0zBLgkBbmJcuns2e4GiYghTEuwb2W8LPpPSyfnATOCkApI3yqAkq5BEf6IASx0fBSXzSCRsIw0xuC6N8mCvK/FlWtbQomuJF+Z4AbDQQjRypWXfEG01xpWD62H+MwMjCiLIPeLv7+thhLqLbiHFjBbmiMazYrkA1M9H+SXV7/a7COsz4o74emJCzgj6buvq6jbBkt15tltAAzWrND9ZBHS7W0uhLiyOb+B3yBLtZDQIANMvJHKSER/y8DxzJoKPYlxYO6bf2rr63VZGN6vJ8ZuBslSvKamajNHojPku+7xcSrTAkVqqQZ2GwKnlKqE6/RC+fhPDGTMXp86eW57et5WaocwwOX9GdZeVx3BjA8U1T67FDlLEFDla3ccL4FlYP2OEPUSICJLQNUYf92YQquc9FHbdW+KQfZwZcZxu8tiILV4JrFDjt39Hw38D+G92dzX5rwBNmKjJ+cnS4B2VTY4WFzHdqhOP/i+ffjJYRqhOCvxB1YFWff7aVviyictn2q/ZrBtrmBWt80OYtGYOzRRvrvKNJ/iyD9p5BIjrmM4qIDdqzELUWEL1eacGMQoC2Aj362mkAVhLVUEULQAj12iGUcWo+ycEaGDQ95SfMq965idKF9ppkvxafhi4ZoBj4I9bW+gxeDAxKXCAIHD25hkOw219TKOH2gkaBka0Pn7njXVd2XD1OkdFKyWGpU6uRoAXTlHhJzZwqKRLFSTeHWZzcRLlMynqpC6SzD9NAWRF8zU7daYZbspgcagBltuRBNJ6DpaBOG4TOB5UjaJj26B5ZjyBB3OfBnJkw5klf/2LInjnOC0R1mE0rQ0KIchBYJwUDl/WSzWbffzABv5nEA39edZd7TEPHyVFAQ4sFeKkX3iNId5vum+8ivvYEIYZkANfQEHQJLEYR4z7jr8idDFsGZNhcgFGEo0jORZQZRgDCuZD/1HZTszW/faD5YLzkjk5xRMoOebiQcy+olEoXD2OPQeOhEU39tw5GIOeMacvKzGNcSHWcCdusmnwFPelK9cm8ySVHvWtDv/GvB1BNXRh/bISMb65fkHD2oS//aT+sbmEt6PgPk5vP74cBcUZJz5OkX0+XgSw08brFLU9Rkx7tbXSrQ4y7+Yn7Hg0Kr1c26kD1P7c/0NilPzVnhVaRBAH9DZPRx0PvMW8mvfhfB5bYSuV4oz5OxkfA29z+48WTGtR+k9s6HM2hpRexUYLf/bWqKFQ1sVO5+58myrerHZJbXxMp3k8rtIaDr94YIDNeW7f6l+pexg2KPcdJdbSt4zbqGYdpHQ71zA1TNY+U40QRxeLZuM47HynSED/Qf93aolTuPC64uE5Rsw+gNOs89uimaCFZOmOP6c/+y0pT6c5Pl2A5aMoi6o2fdarofuvb6+piGQupXKMKbkZ0Z7huiJjbGI8P7jQYZ6eQI6WQ9CkMcBgkM3Thg+CWnHh8G99qBb7G6CuL60cUV9KjXhPc8OGtnWeI54mgk4ATxc5nYcsMywreSHf4iur73def0TXNqPef2Ej3RvW13xC4nnm4GxWQauap+wswlfMO6G1Ayhd+Gz2DhnHsMC6hxrDhdzX7jjiQzkwFUl3KGQQVpNH0j3x/b8t4/nkghTH82WuaMAFF6spzzIPMWdmps3Z+B0vN7DskuRPUAmV4IQCyXT8EnBUwTa3+nm4dlFUbrVoRLytwXgfqV+4aMV+gS9fs/sQzugQB0/Cn4E88FFPC//qTdd6GFmTayXYSgcgPrha0/+91Kh2Sk/dGPik9e0EITIuO5fpp/YCdasgASMKkRmocoCaW3B2axX85KkzKaC8XMIDQWpX+4d69kQdEuxHegi/gTUqz8x28hFfOxVEEw1tnYLJ+CJwMYNHD6Ly3j3jjmGqaPURS3PcK0CZUNGi3wNaag6iwX40NxkwGwAWGsIEsUK7ALZsE8MFEmNzaAlls0jj5jDQyNag6CmbcyCWJm+GATY+xiz2ZQ1cmwIUFS3nzSlnJn2DU8rEDZifjYU8AIUvx2mnWGnchesjX7hkyQemk/RM91+Y3cxOjepNe0Z28KW6JdCKMlpHin+/9ZEEQcpiEG9E3ZhyxbsIS4ne/d1ygEUe9WVqU6Jz+FhV5wUosXdsyL2P1TMzbnKWq1gYJzUTin4NzLr+ed0pYYNRjmFJtpxIiQGE6zJb5XJ2OJ3dS2VjNQu5EegSfyz9rVdNAuLsV5/lA5wsdqh96RZoSDpg5dWFbAa5nkFAYxkoiZHCyg/9hVjuyv9OQNlcoqXMYSn/xsRQw+Q6KF9t9MvqPuBjVNIiZG+m5wX+PFGifkS5OinrJNkuuwOysQr1EuteZV6AIbG2tea+I+ZXKJaOmvFDH4Fba9GK/YI9ieU4QTyXoF3WvUfR0qhU7MYhDiobh5olBnGxbnPbBSBbppV5DsQ06xP6APTxdzg6OzsjglloNuk86LxDSa1BbGF8SRBtxkjmMNZHZiIahW4iazK7oej5mpmZ14zCmsAm5IoPDsFM8rYAxrZqUea8cP9c0TlInE0JqvOcHv2zGonAAyzpiwvDCnc1P5BgWifGbm+x4DkMr2RRjl2MHynanpuoxEG6H4XaWd8Eqx652KNc+s08cO3gdgVo+fJK2ZnkdyeBuGkaCBPjkslwkHY/ngLT+DhHqgSBOD9aIFQfXSt0QMq8/V02EbTgtz5RQrHGFGZn+jm/XxTiQDN0TBeAgUHlYfGZHi4jNlTzNiFUZNabj9fMKWtsMqwviqzkirthGJagSdxr2ZOCfukdlzSxtGA+yb5+xz42V4ub1uhnwTZbh8c2quyqOz4ne042Zzo6cwl8kr2b5vxbu9u6GG+maVH0C3Ar9BJrOFMF+pNwESXSgftO71TQULw2DgsizF15Qhz14VPOj9p5cpQZbp9U2oxy7kRadl9aFKXt84iHRWZpVYrRR7Saof5qpNRWEgwo9qj+kaZnMwqQaW2GVcQMKgjqS3pmwxy2Ccowgu+dbt9ERWmBkXWF0/ly1GSUotf2i2Phq6V6CV/j12QVawq9mlfwxz5GQRINgFhdMt4e9rTe45FXpGU37NL9Gjeof6tXeDuzVFXHztJv0tgbDYaG0RNhs/IACwuyYL18zdT4mG6LL3fhtOt131909LAR1DPLe0a8lgSHGtIIcES47MB4ZaXxCotBnAterrkqa6Ha1VM2PlFAaSm9jUwGzI3LE+xjoV7GEXlVGJZaqsy6Is5t2rY2BBBCSKKDd3QpK+B/oHq/yo8C50Srx6opuNdhQMFkOZcJMJw+SY8jBWSE5UsreHzWufejD9rjnZMlZY+UhF6WAr58Iz10LuVT7scVtqTGipMfHshpR9ggNDpjmMS0IRVw4jDqkcOGEAg9rr0lSaxv0NfkWsGBS64xlnXzLH2WcWzbkVLE/3v1TNa/OtOBerrq7+/gAsYi896y9QYozpxzzTri1Ikm9owo5jjHdEU4veCK0IZ7T7CcMq2oZOP2F42KQl1g/ZpsfjBu/nUPRkOpcVBeOc7Z5o4mRyBTozytMizj0z2tQ13tPQdu0Bo1u1IUzu6PRy2DJ2h/VS1WVd+BeIoW5WXdvXzhLtojrbOF2VcZSnB7Srsk7q0jMhVT9pdTjfnb547SV3mapkW0SeZaJVltC+uva9OKPdxGm2qQ9ON1GV1BW6RjVZVbual+zmfP9x795Xt3t3Ozs9bctNUVdOT1kV59UOJV563Ph6Ki4H6l1uz+522G23YeZuhyTdhDvPpjvYlbpkN7e665uCMKl7949727g7Iqx2SY6s0nGbZTXaW3isjp7NJ2uLrZrrN7ujbJNlx8jpKDkmWZ2i60Qvfw/1jm1X94Oz47JikxTuVkjz0MOA6FwSDwO605AmhyPstpvIXZy6IH2UnsXZ1srVz93R7Y26Qifzt9vqd7wIYK1kqVen7of0UR5OCvsFjpMl/JzcBNTJbHxG8xwZH4C8eObnT2SDmDbxqlZZRhos34yvlhtlrn/X7y4nWPDUk8Y95x+SNXZfqT2+iQ6v9GDxOoDci3zvjjTjUSAq9r2oae4DWfVQxzeRz3ZlsSnETSHkwkAWS8QIf3hdvDxBRLM0IED6LOc8z7f1rEc5tmbWo1TymfUgDw9ePlH0kKIHZj1Pa9jMG2w7f7zK1YgtnNgZ+Op5zhGMzp63VibqOw6z6JmikcA6PoMutkF99ivA4D77HW2QdytwflJRUYDrO0Wzd7KoTjDrechwyL1zLI6HeSPT8v4IM57V1LX4zoDts8YLcIsyhWFUpdW8QZen5jZSAnaiJQonP0MuK3wfvtT53iZsxvfwhtBTAyjQwDV6c7xt7h9EzalWa76qDOPt8215bl9bKzgx2sYFEmPyE1PA9xdSBJJ36gQFmD4RNbJfGAf8lZo3jP7nrThA9M0zfv/c6sC0bjMbOBR/FEXnKHKuwMkVuSQak0NtE9TBW+FXdOseiOlyNLwr9vfCykGxAURkM5jRO76lnc4TXoSNC+hLQYmFa9Oa0SM8pDE0M1bbopjIJCDq0lAl/VBskznzQ8+TXEoEfTGjTeR+3xHdKIp97xJBpPmHq1xXh3i73bq6aB3lKa5RVURpy9EAlJFeJ9iuYIfQ3MkoXNZb26zjadu4rR9WATi7BN06zGXpD52K534jqnRJI8KxikGzune4MpH67TCzmYzQbB7uV5tWyFUSzm4UoKftcRoQ6s0/J/PogG91jA5e8juLlc1cBCnMPOY3nUijsvBspAgAw9M3s1/2p/b2W6yXSDcTZwjwMT6CeQ16VkyM9hnmxMysv8XA6nRiHwroYRm1H/wOw6zXRKGkZNaLBUQdrVb/OwytWD9jlz9Vpw6bzfONBH9Reb+fGQbUOU3msYm3SWJnwp1u0MeLGDFRx8/6Vl+pz4T70MZkkY2QRdJdHR6kTLLwgiSJKgSKC6MmpqV0r4xb0fRjTlDLajMXm8d9fTqvssoJXPRmyhNuSZekFxZ3V1w+EFHoWBuRoyxbvZPzG3aE5EeuDnVxrOFDaJZikJu/PDclV151inARqW3Aqz6QR6aTQbJk9WvLhIP6rCEy/VbdEHMZ/AYxvImv/0pj64wQLex907oGv8HyklqMhzz6DPjdiAuK81lRMqSLlRELBBClnuREKiAoDT8jRAapSuomu/Z466fSIy6l6XaBwHQzB6YriCHS14uA7LESqTEsGERE+jSdmSofS7KP16liYwIlApSL2151FfJA35ifHjlMrdzI87K1jWe9AXSHlYGR4owcK82MA2Glh1/Qupnvz+DS1Lss5HVWdRM3YzmMdd7EP5XWTQ5BZXUDoY4AJ8ejKuYneaPN3k4AIqTCFuk357ago/AX6wDpymzw4xhYO0u2SX5AYv7qDMni4RFA6QDZFTA/pEK/YkLCJlAyRilYncLJsh1A65gxs4gw052VUtsZyRRo17r1wftP1MhRr0otGcJkXMDNZlbyvhfXFEe70Cn85HKPwbJiuO14hZjJlG/O4M0GaPI2ObR0l2U76+urMiJWZZzFmT4PTqmM1ArgM1IIuRVVRgo/qgIzK56NbUSsEFCb0ZCf3WR5mxyQhWyp3uwwJwwLQ9vVMRXcnwhogIlArJguDLRo3G1qfE8mZgWwYVH+AUP/wdbNUz+RBdhFSfODjCHQaPt2URNfaB8e72M3oxBoXFwIyI9xE/MitJNhhJO1Rh2urHpgeTIt/NtvvMR5Grw4nrzOC2OEVnijG/uU+lCDsgUuBpnSliOMmXs6x7O0GS2i2dgMLsUe5XVURO+7pNgcch+SNtT5Am5dW93L4TG73qwbHunqeF4/mFDurJgaZzQaIQ8/XM+NSpgujD2dcscdkx8h7x4A4aey4PFImywMRLwksGkoDNugg8pBLs4UT4jAYMQeYLptlsTQpyxxMkefKgF9AmpqGgxG4/eQFxwnlyfAzlR63NqggGP6s3yMMkRAOpa1R7/RiLoN1mViBOzoMm6jUmsGgmq3+cFyOPOPIsSB2L0eiq9Rtl1Gm90yTsMlkaYWo3G2qoi0jKgQStuFyJtnTrRTe6lFsVBLS5PXPA1U7dFLVCZ+QLiEkaZPNSNOraCJ2JfiN5SwVp0rCr0oiVq6uhWkmVt7fn9tr6xQRvhlmUVfWM6CXc7/zsjf6fbLcrv7EuyiL0v62JY8F0f8903Mv4/545sd+3uhBstjPU0bBjXM6QdOxfk4jccTrzHAnZgE/bfuqJf5ACGJdpBCO8kbmceZFaqGWb3VFcC+/taU3+puROqZSIZHqzomnt0Xp+lS/r+O4/HdlyDiHK7DuSnjHadKDKdYoolJvDIRzLEv3w9G6qWxa0GKzByG7soPIGgitmq6rc8sxa1L1+jo5vgbJ3Gua671l4cRPx9ilMUldTZpCimgKz54lFu7wLBF09HkXkbzQdV8xyc/Y8ZRPlomXQkbbpiMTg6yjfF5BwdvWJu36ojdhB1wxssLqcSYlhgng0H6ukSKr/y8FVIGQuWmoiq7gRLsy9wCjTA6lTY0FGY5gbo8VketmjP997nyoz7bC6ItFtvjpuK0o1Z1WsnW8FTrcbHfZ2ZQdSL/rXBpJJBe1SjvT+0bdfc+kHKR8PvZBk2Rtyxz85bR5obmXE9jitwUnq6h0DBR5/NKtXrk/qkIc1FSCk1IwJfIpiqdJneZLOFHjulqRqB4kgJAu6DHwpP+SBUde0GqJv3b11Us09XJAVHnHYyDkjoHouMnM2yxrklsXtoXNZrr5TFpxTFz1+I2e91iOTxmVk2z6mcYueH6+2E6cZbiljtTcs7Q6sxxbciz+Yj9Fmao42ISTFOX8lITWL1050VREmiOMpyYd6dVjigzwlpRq6UYwR9jVaIzVSU6+8Uq0dmkxW+LVYlmg+yK5vxAhCr6+aovu5b6QlzpwjB2r36Y2fNZXQ2xrryJFQWT0JTT9f5HcCmuVTG03bv4kup//BwOJ5GmgPWukhJyo0hmN8d3/szsz3PYKuo9472whQKhktO3jMkdPXXnCXs1M6+kvTl/4Xv2s7RxMVbE/qrmlPFFf6ItL4ZjIJ5OR2CWRJhR1V6qPKBzo/64TAGspR/wpFlQfEbGgiRVZhJCVyrRWcv7KfkM3MRCYMz/wQwHLmpnHep5JbztGHjd7LV+C8yTaQZMHA7HbSWLiLeXlsjWXXMzLMPJnIx2oXS6z5Ck3WB6n7quccjRMRZqOzNnLiCnUDWlGWB3NItAaGQRmGE2B6BbGkBnpee/kqNNNpNxdTKOpLLzO2K5NrqxW9FTABvcfvI3duQorVNnQpH4AwBnmIms91xhubrCcnCFbV7mZ+BwsxfKfNywexWIaIe8I8CKUKd2T3hLrI0VRak2xXkJPjk1N/Drsavrh3Pqnr5VX5BoC7s4u+reUAe4jfjDGpHRnPZQGpMyHuLByYCG/ApxV5BX6mMYL3LB+pZPpljzHD7Au8cOInB7LwVV8nIBMjuhNxxNMW2qI7IYly8RlL4zA5sAGlen+xLlmJ1iZDb1lFnZ3W+ynO0GQMfam8v5p80A2Uh2IdosWbZqfINCLwVX5hFtf8q6vPUj8tgwYDI1Q//eui4w7fdHnFYZqCj3mTmF3JoBnAsShgF8PE9cx7GSRmgLq+v9Mi6LAE2Nx+rDt8vrhFYG8hBzon0ooPCqa9/8E1AFSWbVSocThNK7FEBAr8yy9rBzQRP2MVAPlz+12egGBdxOhJ1Lh3sGHO6Zq4xH0pCp8jA/rTH5Kk5OybL46f/ZxMti2nvSa3E41xr5y44NVzCuLdVAyN6uK72TaYjkNIIgwYUoZF/rRv/q2EKfBWg9hRVBmQ82JIattZI/uZAYUyLluaA+ZEzpimKWhQsV7LA4w3N7+bDLiEfciQtBIMXMXngoWC541xU6RxkdQ5mHHGXOpR7tnSKbThwz4xwAettjE9nqXREmrqeTas3JWJ2PiPdjpl2VX9kZrJu/jGdSV35eJf3iYNYYYC3KjgY4Tc1hbPg+TcBh6r7sYmoYEoVk28WhzXwcOTfTEm73OA1312+gWowJktEohDgJfUaWORBlAy+AwqhUNmAc08kAdaSXYvCV1Y2VPsP++XM2ObSSNs9v5jj7MCyZbc/h1m1+ttOdjVJeXWklvCclzfwJFK7YrjqLNoShUVuzI5PM698j6TLk5bf748nhjUBppxGPQjJzJmKd/ZHK76P5p7fOjYmXnzaHIvmaIUi++LmcfpEpy+2gxYnUBK1SGXpu9PYxN6K3rVyLVbWZdE9FiYO/hLwOkSIzJDOzZ/V5pJFqjCrMTK32tzbuVzERwLK5NfOKjOCUtwDaR/jDTAEtM4VwuQ1kAw+/EDUOrwzttPzPwDstsC3ZKnX/hOtzY7g+zQrnsNFfARjHGMAYNq6MxR5WnLjZPUFRYguhTtu74fpuMnLSk22aEv0brWGvhklda6hUIKRzNyJrNRQHz60HTa3uLfgpHhJ2mJmxyDHZhy9upL4xaCXP4D5/GBfhsbJ6VhMxjvsCno3UJPOlIzB8XcbCl9NM1PecEJ8ModTGUIdOyVmR1lVXFbsxNActLg18N1r/ORe3vt7Lf1ivBMNpaX8CinSHVm0UF+krLljHPYYK7Ea/SsX/MiG7uuzhwx2y5+iBA8yefxK9gVSdU824OKhPYF0vnyc96YHiWbRHhSirBcPrb5wySGCqWcOC2SFSKNvigjsfzwR3j2o3WJ+aBwJQsjBdzPVfV04kgsnlY7mg4vGfdHADwW3Uv210ZXmz0+z/Y2+2MzHtV4YfelzL0ee7liMda2COwPYvI48M07qG9iP/JjcyMqynfMfPOYstVzPSuek+nrqBEzMsj4Zwrw53sybbi1XvURV4tAo/Sp0784q5ExWvHeFhbt1GrB5kAqEUkwVFzOlTKqoQQZRyCryp697ZTRjRDOt4fmnodImjGEevvkQXvWWRY0xMWcrSutzR/AkVpn+1gLWpnMwo0YrgpL3lb7exTtT+HPU209Sbqof7HDlAjeJZjs7n/LPpbjF2qOmWNMRZO9hka7gXmNfQje8BUBGHC9uSkI5vQBcu+emFA3Q0gWcSZkjLtXxAt3tXjjtJnkcKCGspjhToyjX5n0orxc02zzuQAPHwW9uW7eVSd2XtQxkAC17gn5y6WenemltBVaizTh6tmIUFgUCil/u1KduK8OKmavYNYepkgXirhFc3RPWl/lYidJR7Ivffz2TTkt97ADhN5eAMw7Udp0qzIqz+aNsLU5qEwEN/J/fNy72ng+S1zAzF1XhL4ApoJL0+WD25XeqvtHrewoPxiwWEWjXKemXnYcQMrdMlaFyG65YhJMYhobgcI7dkOobcR6AVeIllVQCcTYjShpxtu64zB5mAOs7sMn1x8w3Xh2dR7IRt1EN5cjUm8XlB821ysNDx/5Ah/rdroPDsH3/7Vr8fu+JS94H89hF+Ubwz/BhazUg/9DSHtj3DxDlGvI0HI4M5i8ZyziQoiqjaeTVyN+YmzBfOmKUFRjvykxjg3mOz/myMli5GwNxRMc8FEulgpvFwodg7dCQVlMqIxx7nobN+b43Oj+EkoxUWG3AROqkcRDYGfgcpbYJ1TD56VUl7pvQWRzNr7+VJuLT4eYMIFmNzWD1aeV5oGepd9v1tKWRCmPNFFhz/fgpWAYP3LrAMMIpxURWFKvR8zIEYOs7iQnKquESButKBWM3AyRlAKG83DvdVihL7ouqKV82UNe90o8X0LiT/5eV4vsZtGDoRaOzkYaJFlijRYvxAbUBw9ib3Lxe6JqyTD1noh8ZIWSnpoP83nMwON+JOFzUHTa0fhnWFjrUAVAxjWm3/THlWw/0DqtLTxp50H4473vMxkzyv7KTNS0TDPrUVB5fKApJ4MW39TtkqUxcMzXed4LNK19urBYq7evzfbBBUQjOjHo3CpXh5eSYRuCzNwAERTam48QSPNnDbwEyAECmkBN1WiJeiuc8KknTLP+bmuFmYJIIGGatrTN+7Fdf6PHc7W33yZJSoNOA+JnJWilsCrSsOKhHMyKcD9jiK3Oe9PxTUKcRD2zxJq5CRuV52JdfB6XZ1fz8TZZsacNWxoNeaZSPeh/CGijKneudsIiGiCTIgckmZyX2YXdIxjHpZ7pRHxF0mi8fO3GTWgXRGP6+u/DIQAQ8LrDH3oOSjeGJt87cbYnlOXBCc1wIpE+Ho4Pr+srRi953QeLRqLnJSsLgY1dFTrhvXKaHaweRQtgNMxfHWXMuTL+sazPaQz0n250qJ9rkdF6S8+i0b5kTGQCpZbomcskx/m+SI6Z1TkiAXux3KAJMYy5ykfy2ax9xK8tE685ZBtfLsybZZ2cXn0hvKE0UkqlNxpZjgblZCYVavbEkx/TUOz+EBWfMckHiOPD0qloPOSGcXzpa6oiTdxJXlAhQHyuzBQjxFWZZuEp0iqRiGQoT/MhcaSOzjyRm1DyevCc63zcxObiVcnIlPJJ8bya4B5oOwEs5/nAddmLgfJa5fWznGCo8XI8dQQ6jLBYUhaR+91TnIPOlUwHadgBgeEufPuq9jQxjbybIgjyFwha3Vpfl07ptQFBwXWUlo9sxfwrOyCTCFEyaAy1SpYfktoujHlBMvCVN+W7hSFLLvTY3Bk4oqXcabZRJrA9LhfiCbjusE86UXI/el3c6acvOHN/APPkkkrTGVjNGHC9UyQ/l/+n+9WLSoXP8AAA==',
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
	
	/**
	 * v13.3.32: Boot سبک برای چت پشتیبانی هوشمند (صفحه بومی / پشتیبان).
	 *
	 * @return array
	 */
	public static function get_support_chat_boot() {
		$settings = self::get_settings();
		$ajax_url = admin_url( 'admin-ajax.php' );
		return array(
			'mode'      => 'chat-only',
			'chat_only' => true,
			'settings'  => array(
				'enable_support_chat'  => ! empty( $settings['enable_support_chat'] ),
				'chat_window_title'    => (string) ( $settings['chat_window_title'] ?? 'پشتیبانی آنلاین' ),
				'chat_welcome_message' => (string) ( $settings['chat_welcome_message'] ?? '' ),
				'chat_button_position' => (string) ( $settings['chat_button_position'] ?? 'left' ),
				'chat_field_name_enable'     => ! empty( $settings['chat_field_name_enable'] ),
				'chat_field_name_required'   => ! empty( $settings['chat_field_name_required'] ),
				'chat_field_phone_enable'    => ! empty( $settings['chat_field_phone_enable'] ),
				'chat_field_phone_required'  => ! empty( $settings['chat_field_phone_required'] ),
				'chat_field_email_enable'    => ! empty( $settings['chat_field_email_enable'] ),
				'chat_field_email_required'  => ! empty( $settings['chat_field_email_required'] ),
				'shop_title'           => (string) ( $settings['shop_title'] ?? '' ),
				'contact_phone'        => (string) ( $settings['contact_phone'] ?? '' ),
				'accent_color'         => (string) ( $settings['accent_color'] ?? '#2563eb' ),
				'ai_support_name'      => (string) ( $settings['ai_support_name'] ?? 'پشتیبان هوشمند' ),
			),
			'ajax'      => array(
				'ajaxUrl'   => $ajax_url,
				'url'       => $ajax_url,
				'chatNonce' => wp_create_nonce( 'scraper_support_chat_nonce' ),
				'nonce'     => wp_create_nonce( 'scraper_support_chat_nonce' ),
			),
			'meta'      => array(
				'version'   => '13.3.32',
				'asset_ver' => self::storefront_assets_ver(),
				'mode'      => 'chat-only',
			),
			'urls'      => array(
				'home' => home_url( '/' ),
			),
			'products'  => array(),
		);
	}

	/**
	 * v13.3.32: چاپ ویجت چت پشتیبانی هوشمند روی قالب بومی / هر صفحهٔ غیر React.
	 * فقط FAB + پنجرهٔ چت (بدون کل ویترین).
	 */
	public static function print_native_support_chat_widget() {
		if ( ! empty( $GLOBALS['amphp_bare_storefront'] ) ) {
			return; // bare React already has SupportChat
		}
		if ( ! empty( $GLOBALS['amphp_native_chat_printed'] ) ) {
			return;
		}
		$settings = self::get_settings();
		if ( empty( $settings['enable_support_chat'] ) ) {
			return;
		}
		// فقط روی برگهٔ native / پشتیبان / فروشگاه بومی — یا وقتی صریحاً خواسته شده
		$force = ! empty( $GLOBALS['amphp_force_native_chat'] );
		if ( ! $force ) {
			$is_native = false;
			if ( is_singular( 'page' ) ) {
				$pid = get_queried_object_id();
				$tpl = get_page_template_slug( $pid );
				if ( $tpl === 'templates/native-shop.php' || $tpl === 'native-shop.php' ) {
					$is_native = true;
				}
				if ( get_post_meta( $pid, '_amphp_native_fallback_shop', true ) === '1' ) {
					$is_native = true;
				}
			}
			if ( ! $is_native ) {
				return;
			}
		}
		if ( ! self::has_storefront_assets() ) {
			return;
		}
		$GLOBALS['amphp_native_chat_printed'] = true;
		$boot = self::get_support_chat_boot();
		$boot_json = wp_json_encode( $boot, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
		if ( ! $boot_json ) {
			$boot_json = '{}';
		}
		$ver = self::storefront_assets_ver();
		$css_url = add_query_arg( array( 'amphp_sf' => 'storefront.css', 'ver' => $ver ), home_url( '/' ) );
		$js_url  = add_query_arg( array( 'amphp_sf' => 'storefront.js', 'ver' => $ver ), home_url( '/' ) );
		// Minimal CSS isolation so theme chrome stays; chat FAB is fixed
		echo "\n<!-- AMPHP support chat (native) v13.3.32 -->\n";
		echo '<link rel="stylesheet" href="' . esc_url( $css_url ) . '" id="amphp-storefront-css-chat" />' . "\n";
		echo '<div id="amphp-support-chat-root" class="amphp-support-chat-root" data-engine="react-chat" dir="rtl" style="position:relative;z-index:99999;"></div>' . "\n";
		echo '<script id="amphp-storefront-boot">window.AMPHP_STOREFRONT = ' . $boot_json . ';</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<script src="' . esc_url( $js_url ) . '" id="amphp-storefront-js-chat" defer></script>' . "\n";
	}

	/**
	 * v13.3.32: wp_footer hook — چت روی قالب بومی.
	 */
	public static function maybe_print_native_support_chat() {
		self::print_native_support_chat_widget();
	}

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
				'takeover_front_page'         => ! empty( $_POST['takeover_front_page'] ) || ! empty( $_POST['enable_shop_takeover'] ),
				'enable_native_wp_template'   => ! empty( $_POST['enable_native_wp_template'] ),
				'native_fallback_page_id'     => intval( $_POST['native_fallback_page_id'] ?? 0 ),
				'enable_404_shop_redirect'    => ! empty( $_POST['enable_404_shop_redirect'] ),
				'set_wc_shop_to_fallback'     => ( isset( $_POST['set_wc_shop_to_fallback'] ) && (string) $_POST['set_wc_shop_to_fallback'] === '1' ),
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
						// v13.3.32: shop_page_id را با پشتیبان یکی نکن
						update_option( self::OPTION_NAME, $new_settings );
					}
					self::maybe_detach_fallback_from_primary_shop();
				} catch ( \Throwable $e ) { /* ignore */ }
			}
			// v13.3.32: با React روشن، primary بساز و پشتیبان را از /shop جدا کن
			if ( ! empty( $new_settings['enable_shop_takeover'] ) ) {
				try {
					$_tpl_s = (string) ( $new_settings['store_template'] ?? 'digikala' );
					if ( $_tpl_s !== 'native-wp' && $_tpl_s !== 'native' ) {
						$new_settings['set_wc_shop_to_fallback'] = false;
					}
					$pid = self::ensure_primary_react_shop_page( $new_settings, intval( $new_settings['native_fallback_page_id'] ?? 0 ) );
					if ( $pid > 0 ) {
						$new_settings['shop_page_id'] = $pid;
						update_option( 'scraper_shop_page_id', $pid, false );
						if ( function_exists( 'wc_get_page_id' ) ) {
							$wc = (int) wc_get_page_id( 'shop' );
							$fb = intval( $new_settings['native_fallback_page_id'] ?? 0 );
							if ( $wc <= 0 || $wc === $fb ) {
								update_option( 'woocommerce_shop_page_id', $pid );
							}
						}
					}
					update_option( self::OPTION_NAME, $new_settings );
					self::maybe_detach_fallback_from_primary_shop();
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
		<div class="wrap scraper-admin-dashboard">
			
			<!-- Header Title Area v13.3.32 -->
			<div class="amphp-admin-hero">
				<div style="min-width:0;flex:1 1 220px;">
					<div class="amphp-hero-badge">⚡ پنل فروشگاه · v13.3.32</div>
					<h1>تنظیمات ویترین، چت و هوش مصنوعی</h1>
					<p>ظاهر فروشگاه، قیمت‌گذاری، پشتیبانی، پیام‌رسان‌ها و همگام‌سازی ووکامرس — فشرده و مناسب موبایل.</p>
				</div>
				<div class="scraper-admin-topbar">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="button button-secondary">خانه / ویترین ↗</a>
					<a href="#amphp-wc-shop-fallback-card" class="button" style="background:#ea580c;border-color:#c2410c;color:#fff;">ووکامرس=پشتیبان</a>
					<a href="<?php echo esc_url( $scraper_embed_url ); ?>" class="button button-primary" style="background:#2563eb;border:none;">پنل اسکرپر</a>
				</div>
			</div>

			<?php if ( $updated ) : ?>
				<div class="notice notice-success is-dismissible" style="border-radius:10px; margin-bottom:20px;">
					<p><strong>✅ تمامی تنظیمات با موفقیت ذخیره شدند.</strong></p>
				</div>
			<?php endif; ?>

			<style id="amphp-admin-inline-extras">
				/* extras that stay next to markup (desk / theme menu) */
				.desk-thread-item:hover { background: #f1f5f9; }
				.desk-thread-item.active { background: #eff6ff !important; border-right: 4px solid #2563eb !important; }
				.desk-canned-chip {
					background: #f1f5f9; border: 1px solid #cbd5e1; border-radius: 14px;
					padding: 2px 8px; font-size: 0.72rem; font-weight: 700; color: #334155; cursor: pointer;
				}
				.desk-canned-chip:hover { background: #e2e8f0; color: #0f172a; }
				.desk-bubble { max-width: 82%; padding: 7px 10px; border-radius: 10px; font-size: 0.8rem; line-height: 1.45; word-break: break-word; }
				.desk-bubble.customer { align-self: flex-start; background: #fff; border: 1px solid #cbd5e1; color: #0f172a; border-bottom-right-radius: 2px; }
				.desk-bubble.ai { align-self: flex-start; background: #faf5ff; border: 1px solid #e9d5ff; color: #581c87; border-bottom-right-radius: 2px; }
				.desk-bubble.admin { align-self: flex-end; background: #2563eb; color: #fff; border-bottom-left-radius: 2px; }
				.theme-menu-card-item:hover { background: #f1f5f9 !important; }
				.theme-menu-card-item.active { background: #eff6ff !important; border-right: 3px solid #2563eb !important; }
				.scraper-tab-panel { display: none; }
				.scraper-tab-panel.active { display: block; animation: amphpFadeTab .2s ease; }
				@keyframes amphpFadeTab { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: none; } }
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
				<div class="scraper-tab-nav-row scraper-sticky-admin-head">
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
						<span id="amphpAutosavePill" class="scraper-autosave-pill" title="ذخیرهٔ خودکار">⏳ آمادهٔ ذخیرهٔ خودکار</span>
						<button type="submit" name="scraper_shop_save" id="scraperShopSaveBtn" class="button scraper-top-save-btn">
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
						<div style="margin-bottom:14px; background:linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%); border:1.5px solid #3b82f6; border-radius:12px; padding:14px; box-shadow:0 2px 10px rgba(37,99,235,0.06); max-width:100%; overflow:hidden;">
							<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
								<h4 style="margin:0; font-size:1.15rem; color:#1e3a8a; font-weight:900; display:flex; align-items:center; gap:8px;">
									<span>🎛️</span> کنترل قالب و منبع محصولات ویترین
								</h4>
								<span style="background:#7c3aed; color:#fff; font-size:0.75rem; font-weight:800; padding:4px 12px; border-radius:20px;">ادغام اسکرپر + ووکامرس</span>
							</div>
							<p style="margin:0 0 16px; font-size:0.88rem; color:#334155; line-height:1.7;">
								ظاهر ویترین را با تیک زیر، و <strong>منبع کالاهای نمایش‌داده‌شده</strong> را با سه حالت اسکرپر / ووکامرس / <strong>ادغام هر دو</strong> تنظیم کنید.
							</p>
							<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap:10px; margin-bottom:12px; width:100%;">
								<label style="background:#ffffff; border:2px solid #2563eb; border-radius:14px; padding:16px; cursor:pointer; display:flex; flex-direction:column; gap:10px; box-shadow:0 4px 14px rgba(37,99,235,.12);" id="labelStoreTakeover">
									<div style="display:flex; align-items:center; gap:10px;">
										<input type="checkbox" name="enable_shop_takeover" id="chkStoreTakeover" value="1" <?php checked( ! empty( $opts['enable_shop_takeover'] ) ); ?> style="width:22px; height:22px; accent-color:#2563eb;">
										<strong style="font-size:1.05rem; color:#1e3a8a;">🚀 ویترین React اختصاصی (دیجی‌کالا / اسنپ‌شاپ / …)</strong>
									</div>
									<div style="font-size:0.84rem; color:#334155; line-height:1.7; padding-right:32px;">
										<strong style="color:#0f172a;">این تیک باید روشن باشد</strong> تا با انتخاب «قالب دیجی‌کالا» (یا سایر پوسته‌ها) فروشگاه React تمام‌صفحه بیاید.<br>
										✅ هدر وردپرس حذف و هدر/منوی اختصاصی React فعال می‌شود.<br>
										❌ خاموش = ظاهر قالب وردپرس / برگهٔ پشتیبان بومی.
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


						<?php
						// v13.3.32: وضعیت ویترین React در برابر پشتیبان — راهنمای پیدا کردن تیک‌ها
						$_sf_take = ! empty( $opts['enable_shop_takeover'] );
						$_sf_tpl  = (string) ( $opts['store_template'] ?? 'digikala' );
						$_sf_wc_fb = ! empty( $opts['set_wc_shop_to_fallback'] );
						$_sf_fb_id = intval( $opts['native_fallback_page_id'] ?? get_option( 'scraper_native_fallback_page_id', 0 ) );
						$_sf_wc_shop = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'shop' ) : 0;
						$_sf_bound = ( $_sf_fb_id > 0 && $_sf_wc_shop > 0 && $_sf_fb_id === $_sf_wc_shop );
						$_sf_react_ok = $_sf_take && $_sf_tpl !== 'native-wp' && $_sf_tpl !== 'native' && ! $_sf_bound;
						$_sf_primary = intval( $opts['shop_page_id'] ?? get_option( 'scraper_shop_page_id', 0 ) );
						/* v13.3.32: لینک اصلی ویترین = دامنهٔ خانه */
						$_sf_react_url = home_url( '/' );
						$_sf_shop_url = '';
						if ( $_sf_primary > 0 ) {
							$_sf_shop_url = get_permalink( $_sf_primary );
						}
						if ( ! $_sf_shop_url && function_exists( 'wc_get_page_permalink' ) ) {
							$_sf_shop_url = wc_get_page_permalink( 'shop' );
						}
						?>
						<div style="margin:0 0 12px; padding:12px 14px; border-radius:12px; max-width:100%; overflow:hidden; border:1.5px solid <?php echo $_sf_react_ok ? '#10b981' : '#f59e0b'; ?>; background:<?php echo $_sf_react_ok ? 'linear-gradient(135deg,#ecfdf5,#f0fdf4)' : 'linear-gradient(135deg,#fffbeb,#fef3c7)'; ?>;">
							<div style="font-weight:900; font-size:1.05rem; color:<?php echo $_sf_react_ok ? '#065f46' : '#92400e'; ?>; margin-bottom:8px;">
								<?php echo $_sf_react_ok ? '✅ ویترین React آماده است' : '⚠️ ویترین React هنوز کامل فعال نیست'; ?>
							</div>
							<ul style="margin:0 0 10px; padding-right:18px; font-size:0.86rem; line-height:1.85; color:#334155; font-weight:700;">
								<li>تیک «ویترین React اختصاصی»: <strong style="color:<?php echo $_sf_take ? '#059669' : '#dc2626'; ?>"><?php echo $_sf_take ? 'روشن' : 'خاموش — روشن کنید'; ?></strong></li>
								<li>پوسته انتخاب‌شده: <strong><?php echo esc_html( $_sf_tpl ); ?></strong><?php echo ( $_sf_tpl === 'native-wp' ) ? ' <span style="color:#dc2626">(بومی = نه React؛ دیجی‌کالا را بزنید)</span>' : ''; ?></li>
								<li>تیک «صفحه فروشگاه ووکامرس = پشتیبان»: <strong style="color:<?php echo $_sf_wc_fb || $_sf_bound ? '#dc2626' : '#059669'; ?>"><?php echo ( $_sf_wc_fb || $_sf_bound ) ? 'روشن/متصل — برای React خاموش کنید' : 'خاموش (درست)'; ?></strong>
									— کارت نارنجی <strong>بلافاصله زیر همین بنر</strong></li>
							</ul>
							<p style="margin:0; font-size:0.84rem; font-weight:800;">
								🏠 ویترین React روی <strong>صفحهٔ اصلی سایت</strong>:
								<a href="<?php echo esc_url( $_sf_react_url ); ?>" target="_blank" rel="noopener" style="color:#1d4ed8;"><?php echo esc_html( $_sf_react_url ); ?></a>
								<?php if ( ! empty( $_sf_shop_url ) && untrailingslashit( (string) $_sf_shop_url ) !== untrailingslashit( (string) $_sf_react_url ) ) : ?>
									· فروشگاه: <a href="<?php echo esc_url( $_sf_shop_url ); ?>" target="_blank" rel="noopener" style="color:#1d4ed8;"><?php echo esc_html( $_sf_shop_url ); ?></a>
								<?php endif; ?>
							</p>
						</div>

						
						<!-- v13.3.32: کلید روشن/خاموش ووکامرس=پشتیبان — بدون اتکا به ظاهر چک‌باکس وردپرس -->
						<div id="amphp-wc-shop-fallback-card" style="margin:0 0 14px;padding:14px;background:linear-gradient(135deg,#fff7ed,#ffedd5);border:2px solid #ea580c;border-radius:12px;box-shadow:0 4px 14px rgba(234,88,12,.12);max-width:100%;overflow:hidden;">
							<div style="font-weight:900;font-size:1.15rem;color:#9a3412;margin-bottom:6px;">🟧 فروشگاه ووکامرس = برگهٔ پشتیبان؟</div>
							<p style="margin:0 0 14px;font-size:0.88rem;font-weight:700;color:#7c2d12;line-height:1.7;">
								اگر فقط کادر نارنجی می‌بینید و تیک نیست، از دکمه‌های زیر استفاده کنید (ظاهر چک‌باکس وردپرس گاهی مخفی می‌شود).
							</p>
							<?php
							$_wc_fb_on = ! empty( $opts['set_wc_shop_to_fallback'] );
							?>
							<!-- مقدار واقعی برای ذخیرهٔ فرم -->
							<input type="hidden" name="set_wc_shop_to_fallback" id="amphpWcFbHidden" value="<?php echo $_wc_fb_on ? '1' : '0'; ?>">

							<div style="display:flex;flex-wrap:wrap;gap:12px;align-items:center;margin-bottom:12px;">
								<button type="button" id="amphpWcFbOffBtn" class="button" style="min-width:160px;height:48px;font-size:1rem;font-weight:900;border-radius:12px;border:3px solid #059669 !important;<?php echo ! $_wc_fb_on ? 'background:#059669!important;color:#fff!important;' : 'background:#fff!important;color:#065f46!important;'; ?>">
									✓ خاموش (مناسب React)
								</button>
								<button type="button" id="amphpWcFbOnBtn" class="button" style="min-width:160px;height:48px;font-size:1rem;font-weight:900;border-radius:12px;border:3px solid #dc2626 !important;<?php echo $_wc_fb_on ? 'background:#dc2626!important;color:#fff!important;' : 'background:#fff!important;color:#991b1b!important;'; ?>">
									روشن (پشتیبان = /shop)
								</button>
								<span id="amphpWcFbStatus" style="font-weight:900;font-size:1rem;padding:10px 14px;border-radius:10px;<?php echo $_wc_fb_on ? 'background:#fef2f2;color:#b91c1c;' : 'background:#ecfdf5;color:#047857;'; ?>">
									وضعیت: <?php echo $_wc_fb_on ? 'روشن' : 'خاموش'; ?>
								</span>
							</div>

							<!-- چک‌باکس یدکی خیلی درشت (اگر قالب مخفی‌اش نکند) -->
							<label style="display:flex !important;align-items:center;gap:12px;margin:12px 0 0;padding:12px;background:#fff;border:2px dashed #fb923c;border-radius:10px;cursor:pointer;visibility:visible !important;opacity:1 !important;">
								<input type="checkbox" id="chkWcShopFallback" value="1" <?php checked( $_wc_fb_on ); ?>
									style="position:static !important;left:auto !important;width:28px !important;height:28px !important;min-width:28px !important;min-height:28px !important;margin:0 !important;opacity:1 !important;visibility:visible !important;display:inline-block !important;appearance:auto !important;-webkit-appearance:checkbox !important;accent-color:#ea580c;flex-shrink:0;z-index:5;"
									onchange="if(window.amphpSetWcFb) window.amphpSetWcFb(this.checked);">
								<span style="font-weight:800;color:#7c2d12;font-size:0.95rem;">یا از این تیک استفاده کنید: صفحه فروشگاه ووکامرس = پشتیبان</span>
							</label>

							<p style="margin:12px 0 0;font-size:0.8rem;color:#78716c;font-weight:700;line-height:1.65;">
								برای <strong>دیجی‌کالا / React</strong> دکمهٔ <strong style="color:#059669;">خاموش</strong> را بزنید و تنظیمات را ذخیره کنید.
								نسخه افزونه: <code>۱۳٫۳٫۲۴</code>
							</p>
						</div>
						<script>
						(function(){
						  var hidden = document.getElementById('amphpWcFbHidden');
						  var chk = document.getElementById('chkWcShopFallback');
						  var st = document.getElementById('amphpWcFbStatus');
						  var onB = document.getElementById('amphpWcFbOnBtn');
						  var offB = document.getElementById('amphpWcFbOffBtn');
						  function paint(on){
						    if (hidden) hidden.value = on ? '1' : '0';
						    if (chk) chk.checked = !!on;
						    if (st) {
						      st.textContent = 'وضعیت: ' + (on ? 'روشن' : 'خاموش');
						      st.style.background = on ? '#fef2f2' : '#ecfdf5';
						      st.style.color = on ? '#b91c1c' : '#047857';
						    }
						    if (onB) {
						      onB.style.background = on ? '#dc2626' : '#fff';
						      onB.style.color = on ? '#fff' : '#991b1b';
						    }
						    if (offB) {
						      offB.style.background = !on ? '#059669' : '#fff';
						      offB.style.color = !on ? '#fff' : '#065f46';
						    }
						  }
						  window.amphpSetWcFb = function(on){ paint(!!on); };
						  if (onB) onB.addEventListener('click', function(e){ e.preventDefault(); paint(true); });
						  if (offB) offB.addEventListener('click', function(e){ e.preventDefault(); paint(false); });
						  // ذخیره: hidden همیشه ارسال می‌شود؛ PHP فقط '1' را true می‌گیرد
						  function goWcCard(){
						    var card=document.getElementById('amphp-wc-shop-fallback-card');
						    if(!card) return;
						    var tabBtn=document.querySelector('.scraper-tab-link[data-tab="tab-storefront"]');
						    if(tabBtn) try{ tabBtn.click(); }catch(e){}
						    setTimeout(function(){
						      card.scrollIntoView({behavior:'smooth',block:'center'});
						      card.style.outline='4px solid #ea580c';
						    }, 150);
						  }
						  if(location.hash==='#amphp-wc-shop-fallback-card' || location.hash==='#wc-fallback') goWcCard();
						  document.querySelectorAll('a[href="#amphp-wc-shop-fallback-card"]').forEach(function(a){
						    a.addEventListener('click', function(e){ e.preventDefault(); goWcCard(); });
						  });
						})();
						</script>



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
										فقط وقتی می‌خواهید <strong>کل ویترین</strong> با پوستهٔ وردپرس باشد این را بزنید. برای ویترین React (پیشنهادی) یکی از پوسته‌های بالا را نگه دارید؛ پشتیبان جداگانه در باکس پایین ساخته می‌شود.
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
									<div style="display:flex;align-items:flex-start;gap:10px;padding:12px 14px;background:#ecfdf5;border:2px solid #10b981;border-radius:12px;font-weight:800;">
										<span style="font-size:1.4rem;line-height:1;">🏠</span>
										<span>صفحهٔ <strong>اصلی دامنه (Home / /)</strong> = ویترین React اختصاصی<br>
										<span style="font-size:0.82rem;font-weight:700;color:#047857;">از v13.3.32 با روشن بودن «ویترین React» صفحهٔ خانه خودکار React است — نه آدرس فرعی.</span></span>
										<input type="hidden" name="takeover_front_page" value="1">
									</div>
								</td>
							</tr>
						</table>
					</div>

						
						<!-- v13.3.32 قالب بومی وردپرس + برگه پشتیبان -->
						<div style="margin:20px 0 24px; background:linear-gradient(135deg,#f8fafc,#eff6ff); border:1px solid #93c5fd; border-radius:14px; padding:20px;">
							<h4 style="margin:0 0 8px; font-size:1.08rem; color:#1e3a8a;">🧱 قالب بومی وردپرس + برگهٔ پشتیبان فروشگاه</h4>
							<p style="margin:0 0 14px; color:#1e40af; font-size:0.85rem; line-height:1.85;">
								<strong>ویترین اصلی</strong> همان فروشگاه React است (دیجی‌کالا/اسنپ‌شاپ/…). این بخش فقط یک
								<strong>برگهٔ پشتیبان</strong> با قالب بومی وردپرس می‌سازد تا در خطای ۴۰۴ یا خرابی ویترین، مشتری جایی برای خرید داشته باشد.
								عنوان «نام‌فروشگاه — پشتیبان» طبیعی است و <em>نباید</em> جای ویترین اصلی را بگیرد.
							</p>
							<p style="margin:0 0 14px; padding:10px 12px; background:#fef3c7; border:1px solid #f59e0b; border-radius:10px; color:#92400e; font-size:0.82rem; font-weight:700; line-height:1.7;">
								⚠️ اگر با رفرش فقط صفحهٔ «… — پشتیبان» می‌بینید: قالب ویترین را روی native نگذارید، تیک «صفحه فروشگاه ووکامرس = پشتیبان» را خاموش کنید، ذخیره کنید. از v13.3.32 افزونه خودش پشتیبان را از فروشگاه اصلی جدا می‌کند.
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
							<div id="amphp-wc-fallback-bind" style="margin:12px 0 14px; padding:12px 14px; background:#fff7ed; border:1px dashed #fb923c; border-radius:10px;">
								<p style="margin:0; font-size:0.86rem; font-weight:800; color:#9a3412; line-height:1.7;">
									🟧 تیک «صفحه فروشگاه ووکامرس = پشتیبان» را <a href="#amphp-wc-shop-fallback-card" style="color:#c2410c;text-decoration:underline;">بالای همین تب ویترین</a> (کارت نارنجی، زیر بنر وضعیت) ببینید و تغییر دهید.
									وضعیت: <strong><?php echo ! empty( $opts['set_wc_shop_to_fallback'] ) ? 'روشن' : 'خاموش'; ?></strong>
								</p>
							</div>
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
							    fd.append('assign_wc', (document.getElementById('amphpWcFbHidden')||{}).value==='1' || document.getElementById('chkWcShopFallback')?.checked ? '1' : '');
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
							<div id="compareCandidatesCardsGrid" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap:14px;"></div>
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

							<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap:16px;">
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

							<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap:14px; margin-bottom:14px;">
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
						<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(min(100%, 240px), 1fr)); gap:20px;">
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
				
<!-- v13.3.33 / v10.115: جدول مقایسه نظیر‌به‌نظیر در پنل افزونه -->
				<div class="admin-card" id="amphpSyncMatrixCard" style="margin-top:18px;background:linear-gradient(135deg,#0f172a,#1e1b4b);border:2px solid #7c3aed;border-radius:14px;padding:0;overflow:hidden;">
					<div class="admin-card-header" style="background:transparent;border-bottom:1px solid #4c1d95;padding:14px 18px;">
						<h3 style="color:#e9d5ff;margin:0;"><span>📊</span> جدول مقایسهٔ نظیر‌به‌نظیر</h3>
						<span class="field-badge field-badge-blue" id="amphpSmBadge">پروفایل × WC × غرفه‌ها</span>
					</div>
					<div style="padding:16px 18px;">
						<p style="margin:0 0 12px;color:#c4b5fd;font-size:0.86rem;line-height:1.75;">
							ساخت <b>کاملاً سرورساید</b> است — مناسب کاتالوگ بزرگ.
							وقتی اتصال مستقیم ووکامرس فعال باشد، محصولات WC از <b>دیتابیس محلی</b> خوانده می‌شوند (خیلی سریع‌تر از REST API). از باسلام فقط محصولات <b>فعال و قابل‌مشاهده برای مشتری</b> می‌آید. «🔧 اصلاح مغایرت‌ها»: قیمت + ارسال missing + حذف/بایگانی extra — سرورساید؛ دو ردیف گزارش به انتهای جدول.
							نتیجه در فایل سرور ذخیره می‌شود؛ این صفحه فقط صفحه‌بندی می‌خواند.
						</p>
						<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:12px;align-items:center;">
							<button type="button" class="button button-primary" id="amphpSmStart" style="font-weight:800;background:#7c3aed;border:none;">🚀 ساخت روی سرور</button>
							<button type="button" class="button" id="amphpSmLoad" style="font-weight:800;border-color:#06b6d4;color:#0e7490;">📖 خواندن نتیجه</button>
							<button type="button" class="button" id="amphpSmFix" style="font-weight:800;background:#16a34a;border:none;color:#fff;">🔧 اصلاح مغایرت‌ها</button>
							<button type="button" class="button" id="amphpSmFixWoo" style="font-weight:700;font-size:12px;">فقط WC</button>
							<button type="button" class="button" id="amphpSmFixBsl" style="font-weight:700;font-size:12px;">فقط غرفه‌ها</button>
							<button type="button" class="button" id="amphpSmFixStop" style="font-weight:700;font-size:12px;color:#b91c1c;display:none;">⏹ توقف</button>
							<span id="amphpSmHint" style="font-size:0.82rem;font-weight:700;color:#a5b4fc;"></span>
						</div>
						<div id="amphpSmJobBar" style="display:none;margin-bottom:12px;padding:10px 12px;background:#0f172a;border:1px solid #334155;border-radius:10px;">
							<div style="display:flex;justify-content:space-between;font-size:0.82rem;color:#e2e8f0;margin-bottom:6px;">
								<span id="amphpSmJobLabel">در حال ساخت…</span><span id="amphpSmJobPct">0٪</span>
							</div>
							<div style="height:8px;background:#1e293b;border-radius:99px;overflow:hidden;">
								<div id="amphpSmJobFill" style="height:100%;width:0%;background:linear-gradient(90deg,#7c3aed,#2563eb);transition:width .3s;"></div>
							</div>
							<div id="amphpSmJobLog" style="margin-top:8px;max-height:240px;overflow:auto;font-size:0.78rem;color:#e2e8f0;line-height:1.7;font-family:ui-monospace,Tahoma,sans-serif;"></div>
							<div id="amphpSmFixBanner" style="display:none;margin-top:8px;padding:8px 10px;background:#14532d33;border:1px solid #22c55e55;border-radius:8px;font-size:0.8rem;color:#bbf7d0;"></div>
				<div id="amphpSmFixReport" style="display:none;margin:8px 0;padding:12px 14px;background:linear-gradient(135deg,#0f172a,#14532d44);border:1px solid #34d39966;border-radius:10px;color:#e2e8f0"></div>
						</div>
						<div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:10px;align-items:center;">
							<input type="search" id="amphpSmQ" placeholder="جستجوی عنوان…" style="flex:1;min-width:140px;font-size:13px;padding:6px 10px;border-radius:8px;border:1px solid #475569;background:#0f172a;color:#e2e8f0;">
							<select id="amphpSmPer" style="max-width:120px;font-size:13px;padding:6px;border-radius:8px;border:1px solid #475569;background:#0f172a;color:#e2e8f0;">
								<option value="25">۲۵ / صفحه</option>
								<option value="50" selected>۵۰ / صفحه</option>
								<option value="100">۱۰۰ / صفحه</option>
								<option value="200">۲۰۰ / صفحه</option>
							</select>
							<label style="font-size:0.8rem;color:#cbd5e1;display:flex;gap:4px;align-items:center;cursor:pointer;"><input type="checkbox" id="amphpSmOnlyMis"> فقط مغایرت</label>
							<label style="font-size:0.8rem;color:#cbd5e1;display:flex;gap:4px;align-items:center;cursor:pointer;"><input type="checkbox" id="amphpSmOnlyMiss"> فقط ناقص</label>
							<label style="font-size:0.8rem;color:#cbd5e1;display:flex;gap:4px;align-items:center;cursor:pointer;"><input type="checkbox" id="amphpSmOnlyDup"> فقط تکراری</label>
							<button type="button" class="button" id="amphpSmFilter" style="font-weight:700;">اعمال فیلتر</button>
						</div>
						<div id="amphpSmSummary" style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:8px;font-size:0.8rem;"></div>
						<div id="amphpSmMeta" style="font-size:0.78rem;color:#94a3b8;margin-bottom:8px;"></div>
						<div style="overflow:auto;max-height:min(70vh,560px);border:1px solid #334155;border-radius:10px;background:#0f172a;">
							<table id="amphpSmTable" style="width:100%;border-collapse:collapse;font-size:0.78rem;min-width:680px;color:#e2e8f0;">
								<thead style="position:sticky;top:0;background:#1e293b;z-index:2;">
									<tr id="amphpSmHead"></tr>
								</thead>
								<tbody id="amphpSmBody">
									<tr><td style="padding:18px;color:#64748b;text-align:center;">برای شروع «ساخت روی سرور» یا «خواندن نتیجه» را بزنید</td></tr>
								</tbody>
							</table>
						</div>
						<div id="amphpSmPager" style="display:flex;flex-wrap:wrap;gap:8px;align-items:center;justify-content:center;margin-top:12px;"></div>
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

			<script>
			(function(){
			  // v13.3.32: ذخیرهٔ خودکار تنظیمات + وضعیت
			  var form = document.getElementById('scraperAdminForm');
			  var pill = document.getElementById('amphpAutosavePill');
			  if (!form) return;
			  var timer = null, saving = false, lastPayload = '';
			  var nonce = <?php echo wp_json_encode( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>;
			  function setPill(state, text){
			    if (!pill) return;
			    pill.className = 'scraper-autosave-pill' + (state ? ' is-' + state : '');
			    pill.textContent = text || '';
			  }
			  function collect(){
			    var fd = new FormData(form);
			    fd.set('action', 'scraper_autosave_settings');
			    fd.set('nonce', nonce);
			    // ensure wc fallback hidden is authoritative
			    var h = document.getElementById('amphpWcFbHidden');
			    if (h) fd.set('set_wc_shop_to_fallback', h.value === '1' ? '1' : '0');
			    return fd;
			  }
			  function payloadKey(fd){
			    try {
			      var o = {};
			      fd.forEach(function(v,k){ if (k==='nonce'||k==='action') return; o[k]=String(v); });
			      return JSON.stringify(o);
			    } catch(e) { return String(Date.now()); }
			  }
			  function doSave(force){
			    if (saving) return;
			    var fd = collect();
			    var key = payloadKey(fd);
			    if (!force && key === lastPayload) {
			      setPill('', '✓ همگام');
			      return;
			    }
			    saving = true;
			    setPill('saving', '⏳ در حال ذخیره…');
			    fetch((window.ajaxurl || <?php echo wp_json_encode( admin_url( 'admin-ajax.php' ) ); ?>), {
			      method: 'POST',
			      body: fd,
			      credentials: 'same-origin'
			    }).then(function(r){ return r.json(); }).then(function(d){
			      saving = false;
			      if (d && d.success) {
			        lastPayload = key;
			        var t = (d.data && d.data.saved_at) ? d.data.saved_at : '';
			        setPill('saved', '✓ ذخیره شد' + (t ? ' ' + t : ''));
			      } else {
			        var msg = (d && d.data && d.data.message) ? d.data.message : 'خطا در ذخیره';
			        setPill('error', '✗ ' + msg);
			      }
			    }).catch(function(){
			      saving = false;
			      setPill('error', '✗ خطای شبکه');
			    });
			  }
			  function schedule(){
			    setPill('', '… تغییر کرد — ذخیره تا چند ثانیه دیگر');
			    if (timer) clearTimeout(timer);
			    timer = setTimeout(function(){ doSave(false); }, 1200);
			  }
			  form.addEventListener('input', schedule, true);
			  form.addEventListener('change', schedule, true);
			  // buttons for wc fb already update hidden — listen click
			  document.addEventListener('click', function(e){
			    if (e.target && (e.target.id === 'amphpWcFbOnBtn' || e.target.id === 'amphpWcFbOffBtn')) {
			      schedule();
			    }
			  }, true);
			  // initial mark
			  setPill('', '✓ ذخیرهٔ خودکار فعال');
			  // expose
			  window.amphpAdminAutosave = function(){ doSave(true); };
			})();
			</script>

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

			/* v13.3.33 / v10.115: جدول مقایسه در پنل افزونه */
			if (typeof amphpEsc !== 'function') {
				window.amphpEsc = function(s){ return String(s==null?'':s).replace(/[&<>"']/g,function(c){return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'})[c];}); };
			}
			window._amphpSmPage = 1;
			window._amphpSmPoll = null;
			function amphpSmFa(n){
				try {
					return String(n).replace(/\d/g, function(d){ return '۰۱۲۳۴۵۶۷۸۹'[d]; });
				} catch(e){ return String(n); }
			}
			function amphpSmMoney(n){
				n = parseInt(n,10)||0; if(n<=0) return '—';
				try { return amphpSmFa(n.toLocaleString('en-US')); } catch(e){ return String(n); }
			}
			function amphpSmToneBg(t){
				return ({ok:'#14532d',warn:'#713f12',bad:'#7f1d1d',missing_dst:'#1e3a8a',extra:'#334155',no_src:'#3f3f46',na:'#0f172a'})[t]||'#0f172a';
			}
			function amphpSmToneFg(t){
				return ({ok:'#bbf7d0',warn:'#fde68a',bad:'#fecaca',missing_dst:'#bfdbfe',extra:'#e2e8f0',no_src:'#d4d4d8',na:'#64748b'})[t]||'#94a3b8';
			}
			function amphpSmCell(html, tone){
				var bg=amphpSmToneBg(tone), fg=amphpSmToneFg(tone);
				return '<td style="padding:7px 8px;border-bottom:1px solid #1e293b;background:'+bg+';color:'+fg+';vertical-align:top;line-height:1.55">'+html+'</td>';
			}
			function amphpSmShowJob(on){
				var bar=document.getElementById('amphpSmJobBar');
				if(bar) bar.style.display = on ? 'block' : 'none';
			}
			function amphpSmPaintProgress(p){
				p = p || {};
				amphpSmShowJob(true);
				var pct = Math.max(0, Math.min(100, parseInt(p.pct,10)||0));
				var elP = document.getElementById('amphpSmJobPct');
				var elF = document.getElementById('amphpSmJobFill');
				var elL = document.getElementById('amphpSmJobLabel');
				var elG = document.getElementById('amphpSmJobLog');
				if(elP) elP.textContent = amphpSmFa(pct)+'٪';
				if(elF) elF.style.width = pct+'%';
				if(elL){
					var ph = p.phase || '';
					elL.textContent = p.running ? ('⏳ '+(ph||'در حال ساخت')+'…') : (p.done ? (p.error?'❌ خطا':'✅ تمام') : '—');
				}
				if(elG && Array.isArray(p.log)){
					var lines = p.log.slice(-80).map(function(x){ return (x && x.m) ? x.m : String(x); });
					elG.innerHTML = lines.map(function(l){ return '<div style="border-bottom:1px solid #1e293b;padding:2px 0">'+amphpEsc(l)+'</div>'; }).join('');
					try{ elG.scrollTop = elG.scrollHeight; }catch(e){}
				}
				var stopBtn = document.getElementById('amphpSmFixStop');
				if(stopBtn){
					var fixing = !!(p.running && (p.job==='fix' || p.ok_n!=null || String(p.phase||'').match(/fix|send|del|queued|start/)));
					stopBtn.style.display = fixing ? '' : 'none';
				}
				var ban = document.getElementById('amphpSmFixBanner');
				if(ban && (p.ok_n!=null || p.job==='fix' || String(p.phase||'').match(/fix|send|del|queued|start/))){
					ban.style.display = 'block';
					ban.innerHTML = (p.running?'⏳ در حال مغایرت‌گیری… ':'')
						+'✅ '+amphpSmFa(p.ok_n||0)+' · ❌ '+amphpSmFa(p.fail_n||0)
						+' · '+amphpSmFa(p.cur||0)+'/'+amphpSmFa(p.total||0)
						+' · 💰'+amphpSmFa(p.price_n||0)+' · 📤'+amphpSmFa(p.sent_n||0)+' · 🗑'+amphpSmFa(p.del_n||0);
				}
			}
			function amphpSmAjax(action, extra, cb){
				var data = $.extend({
					action: action,
					nonce: (typeof amphpShopNonce !== 'undefined' ? amphpShopNonce : ''),
					_ajax_nonce: (typeof amphpShopNonce !== 'undefined' ? amphpShopNonce : '')
				}, extra || {});
				if(typeof amphpWooNonce !== 'undefined' && amphpWooNonce){
					data.nonce = amphpWooNonce;
					data._ajax_nonce = amphpWooNonce;
				}
				$.ajax({
					url: (typeof ajaxurl !== 'undefined' ? ajaxurl : '<?php echo esc_js( admin_url( "admin-ajax.php" ) ); ?>'),
					type: 'POST', dataType: 'json', data: data,
					success: function(res){
						var d = (res && res.success && res.data) ? res.data : (res && res.data) ? res.data : res;
						if(cb) cb(d, res);
					},
					error: function(xhr){
						var msg = 'خطای شبکه';
						try {
							var b = xhr && xhr.responseText ? String(xhr.responseText) : '';
							if(b === '-1' || (xhr && xhr.status === 403)) msg = 'نشست امنیتی — Ctrl+F5';
						} catch(e){}
						if(cb) cb({ ok:false, error: msg }, null);
					}
				});
			}
			function amphpSmStart(){
				amphpSmShowJob(true);
				var elL = document.getElementById('amphpSmJobLabel');
				var body = document.getElementById('amphpSmBody');
				if(elL) elL.textContent = '🚀 ارسال جاب…';
				if(body) body.innerHTML = '<tr><td style="padding:16px;text-align:center;color:#a5b4fc">ساخت روی سرور شروع شد — می‌توانید این صفحه را باز بگذارید</td></tr>';
				var hint = document.getElementById('amphpSmHint');
				if(hint) hint.textContent = 'شروع…';
				amphpSmAjax('scraper_sync_matrix_start', { source: 'wp_admin' }, function(d){
					if(!d || (!d.ok && !d.started && !d.running)){
						if(hint) hint.innerHTML = '<span style="color:#f87171">'+(d && (d.error||d.message) || 'شروع ناموفق')+'</span>';
						if(d && d.running) amphpSmPoll();
						return;
					}
					if(hint) hint.innerHTML = '<span style="color:#4ade80">✓ جاب روی سرور</span>';
					amphpSmPoll();
				});
			}
			function amphpSmPoll(){
				if(window._amphpSmPoll) clearInterval(window._amphpSmPoll);
				var tick = function(){
					amphpSmAjax('scraper_sync_matrix_status', {}, function(d){
						if(!d) return;
						var p = d.progress || {};
						if(d.fix_running && d.fix_progress) amphpSmPaintProgress(d.fix_progress);
						else if(p) amphpSmPaintProgress(p);
						var badge = document.getElementById('amphpSmBadge');
						if(badge && typeof d.woo_direct !== 'undefined'){
							badge.textContent = d.woo_direct ? '⚡ WC مستقیم' : '🌐 WC از API';
						}
						if(d.running || d.fix_running || d.build_running){
							if(d.fix_running){
								window._amphpSmFixTick = (window._amphpSmFixTick||0)+1;
								if(window._amphpSmFixTick % 3 === 0){
									try{ amphpSmLoad(window._amphpSmPage||1, true); }catch(e){}
								}
							}
						} else {
							if(window._amphpSmPoll){ clearInterval(window._amphpSmPoll); window._amphpSmPoll=null; }
							if(d.has_result){ setTimeout(function(){ amphpSmLoad(window._amphpSmLastPages||1); }, 500); }
						}
					});
				};
				tick();
				window._amphpSmPoll = setInterval(tick, 1500);
			}
			function amphpSmFixStart(scope){
				scope = scope || 'all';
				if(!confirm('مغایرت‌گیری کامل از جدول؟\n• قیمت + ارسال missing + حذف extra\nمحدوده: '+(scope==='woo'?'فقط ووکامرس':(scope==='bsl'?'فقط غرفه‌ها':'همه'))+'\nدو ردیف گزارش به جدول اضافه می‌شود.')) return;
				amphpSmShowJob(true);
				var elL=document.getElementById('amphpSmJobLabel');
				if(elL) elL.textContent='🔧 شروع اصلاح…';
				var stop=document.getElementById('amphpSmFixStop'); if(stop) stop.style.display='';
				var log=document.getElementById('amphpSmJobLog'); if(log) log.innerHTML='';
				amphpSmAjax('scraper_sync_matrix_fix_start', { scope: scope, source: 'wp_admin' }, function(d){
					if(!d || (!d.ok && !d.started && !d.running)){
						alert((d && (d.error||d.message)) || 'شروع ناموفق');
						if(d && d.running) amphpSmPoll();
						return;
					}
					window._amphpSmFixTick=0;
					amphpSmPoll();
				});
			}
			function amphpSmFixStop(){
				amphpSmAjax('scraper_sync_matrix_fix_stop', {}, function(d){
					var hint=document.getElementById('amphpSmHint');
					if(hint) hint.textContent = (d && d.ok) ? 'درخواست توقف…' : 'توقف ناموفق';
				});
			}
			function amphpSmPaintReports(d){
				var box = document.getElementById('amphpSmFixReport');
				if(!box) return;
				var reps = (d && d.reports) ? d.reports : [];
				var st = (d && d.fix_stats) ? d.fix_stats : null;
				if((!reps || !reps.length) && !st){ box.style.display='none'; box.innerHTML=''; return; }
				var html = '<div style="font-weight:900;color:#fde68a;font-size:13px;margin-bottom:8px">📋 گزارش آخرین مغایرت‌گیری'
					+(d.fix_at?(' <span style="font-weight:600;color:#94a3b8;font-size:10px">· '+new Date(d.fix_at*1000).toLocaleString('fa-IR')+'</span>'):'')
					+'</div>';
				if(st){
					html += '<div style="font-size:12px;color:#bbf7d0;margin-bottom:8px;font-weight:700">'
						+(st.stopped?'⏹ متوقف — ':'✅ ')
						+'موفق '+amphpSmFa(st.ok||0)+' · ناموفق '+amphpSmFa(st.fail||0)+' از '+amphpSmFa(st.total||0)
						+' · 💰'+amphpSmFa(st.priced||0)+' · 📤'+amphpSmFa(st.sent||0)+' · 🗑'+amphpSmFa(st.deleted||0)
						+'</div>';
				}
				(reps||[]).forEach(function(r){
					if(!r) return;
					var bg = r.report_type==='work' ? 'rgba(30,58,95,.55)' : 'rgba(20,83,45,.55)';
					html += '<div style="background:'+bg+';border:1px solid #334155;border-radius:8px;padding:8px 10px;margin-bottom:6px">';
					html += '<div style="font-weight:800;color:#fde68a;margin-bottom:4px">'+amphpEsc(r.title||'گزارش')+'</div>';
					if(r.report_text) html += '<div style="font-size:11px;color:#bbf7d0;margin-bottom:4px">'+amphpEsc(r.report_text)+'</div>';
					(r.report_lines||[]).forEach(function(x){
						html += '<div style="font-size:10.5px;border-bottom:1px solid #33415544;padding:2px 0">'+amphpEsc(String(x))+'</div>';
					});
					html += '</div>';
				});
				if(st && st.total===0){
					html += '<div style="font-size:11px;color:#fbbf24">⚠️ کاری پیدا نشد — جدول را از نو بسازید و دوباره اصلاح کنید.</div>';
				}
				box.innerHTML = html;
				box.style.display = 'block';
			}
			function amphpSmLoad(page, silent){
				page = page || 1; window._amphpSmPage = page;
				silent = !!silent;
				var body = document.getElementById('amphpSmBody');
				var sum = document.getElementById('amphpSmSummary');
				var meta = document.getElementById('amphpSmMeta');
				var pager = document.getElementById('amphpSmPager');
				var head = document.getElementById('amphpSmHead');
				if(body && !silent) body.innerHTML = '<tr><td style="padding:16px;text-align:center;color:#67e8f9">📖 خواندن از فایل سرور…</td></tr>';
				var extra = {
					page: String(page),
					per_page: String((document.getElementById('amphpSmPer')||{}).value || 50),
					q: (document.getElementById('amphpSmQ')||{}).value || ''
				};
				if((document.getElementById('amphpSmOnlyMis')||{}).checked) extra.only_mismatch = '1';
				if((document.getElementById('amphpSmOnlyMiss')||{}).checked) extra.only_missing = '1';
				if((document.getElementById('amphpSmOnlyDup')||{}).checked) extra.only_dup = '1';
				amphpSmAjax('scraper_sync_matrix', extra, function(d){
					if(d && d.progress) amphpSmPaintProgress(d.progress);
					if(d && d.running) amphpSmPoll();
					if(!d || d.ok === false){
						if(d && d.need_build){
							if(body) body.innerHTML = '<tr><td style="padding:16px;text-align:center;color:#fbbf24">هنوز جدولی نیست. «🚀 ساخت روی سرور» را بزنید'+(d.running?' (در حال ساخت…)':'')+'</td></tr>';
							if(d.running) amphpSmPoll();
							return;
						}
						/* wp_send_json_success wraps — if message */
						var err = (d && (d.error || d.message)) ? (d.error || d.message) : 'ناموفق';
						if(body) body.innerHTML = '<tr><td style="padding:12px;color:#f87171">'+amphpEsc(err)+'</td></tr>';
						return;
					}
					if(d.done || (d.progress && d.progress.done)) amphpSmShowJob(!!(d.progress && d.progress.running));
					var shops = d.shops || [];
					var h = '<th style="padding:8px;text-align:right;border-bottom:1px solid #475569">#</th>'
						+'<th style="padding:8px;text-align:right;border-bottom:1px solid #475569">عنوان</th>'
						+'<th style="padding:8px;text-align:right;border-bottom:1px solid #475569">پروفایل</th>'
						+'<th style="padding:8px;text-align:right;border-bottom:1px solid #475569">مبدأ</th>'
						+'<th style="padding:8px;text-align:right;border-bottom:1px solid #475569">پروفایل ﷼</th>'
						+'<th style="padding:8px;text-align:right;border-bottom:1px solid #475569">WC انتظار</th>'
						+'<th style="padding:8px;text-align:right;border-bottom:1px solid #475569">WC واقعی</th>';
					shops.forEach(function(s){
						h += '<th style="padding:8px;text-align:right;border-bottom:1px solid #475569">🏪 '+(s.name||('#'+s.vendor_id))+'</th>';
					});
					h += '<th style="padding:8px;text-align:right;border-bottom:1px solid #475569">وضعیت</th>';
					if(head) head.innerHTML = h;
					var s = d.summary || {};
					if(sum){
						var chip = function(lab,val,bg){ return '<span style="background:'+bg+';color:#fff;padding:4px 8px;border-radius:8px;font-weight:800">'+lab+': '+amphpSmFa(val||0)+'</span>'; };
						sum.innerHTML = chip('کل', d.row_count_all||s.total,'#4c1d95')+chip('فیلتر',d.total,'#5b21b6')+chip('یکسان',s.ok,'#166534')+chip('مغایرت',s.price_mismatch,'#b91c1c')
							+chip('نیست WC',s.missing_woo,'#1d4ed8')+chip('نیست غرفه',s.missing_bsl,'#0369a1');
					}
					var when = d.generated_at ? new Date(d.generated_at*1000).toLocaleString('fa-IR') : '—';
					if(meta){
						meta.innerHTML = '📖 '+when+' · منبع '+(d.source||'—')
							+' · صفحه '+amphpSmFa(d.page)+'/'+amphpSmFa(d.pages)
							+' · WC: '+amphpSmFa(d.woo_count||0)
							+(d.woo_direct ? ' · <b style="color:#4ade80">⚡ مستقیم</b>' : ' · 🌐 API')
							+(d.woo_error ? (' · ⚠️ '+amphpEsc(d.woo_error)) : '')
							+(d.bsl_error ? (' · ⚠️ BSL: '+amphpEsc(d.bsl_error)) : '');
					}
					var badge = document.getElementById('amphpSmBadge');
					if(badge && typeof d.woo_direct !== 'undefined'){
						badge.textContent = d.woo_direct ? '⚡ WC مستقیم' : '🌐 WC از API';
					}
					try{ amphpSmPaintReports(d); }catch(e){}
				var rows = d.rows || [];
					if(!rows.length){
						if(body) body.innerHTML = '<tr><td colspan="20" style="padding:16px;text-align:center;color:#94a3b8">ردیفی در این فیلتر نیست</td></tr>';
					} else if(body){
						var start = ((d.page||1)-1)*(d.per_page||50);
						body.innerHTML = rows.map(function(r,i){
							if(r && (r.is_report || String(r.bare||'').indexOf('__report_')===0)){
								var lines=(r.report_lines||[]).map(function(x){return '<div style="padding:2px 0;border-bottom:1px solid #33415544">'+amphpEsc(String(x))+'</div>';}).join('');
								var bg = (r.report_type==='work') ? '#1e3a5f' : '#14532d';
								var tr='<tr style="background:'+bg+'">';
								tr += amphpSmCell(amphpSmFa(start+i+1),'na');
								tr += '<td colspan="'+(6+(shops.length||0)+1)+'" style="padding:10px 12px;border-bottom:1px solid #334155;color:#e2e8f0;vertical-align:top">';
								tr += '<div style="font-weight:900;font-size:12px;margin-bottom:6px;color:#fde68a">'+amphpEsc(r.title||'گزارش')+'</div>';
								tr += '<div style="font-size:11px;color:#bbf7d0;margin-bottom:6px">'+amphpEsc(r.report_text||'')+'</div>';
								tr += '<div style="font-size:10.5px;line-height:1.7;max-height:160px;overflow:auto">'+lines+'</div>';
								tr += '</td></tr>';
								return tr;
							}
							var stMap = {ok:['✅ یکسان','ok'],mismatch:['❌ مغایرت','bad'],missing:['📤 ناقص','missing_dst'],only_profile:['📘 فقط مبدأ','missing_dst'],only_dest:['📦 فقط مقصد','extra'],partial:['➖ ناقص','warn'],removed:['🗑 حذف‌شد','extra'],report:['📋 گزارش','na']};
							var st = stMap[r.status] || ['?','na'];
							var flags = '';
							(r.flags||[]).forEach(function(f){
								if(String(f).indexOf('dup')===0) flags += ' <span style="background:#6d28d9;color:#fff;border-radius:4px;padding:0 5px;font-size:10px">تکراری</span>';
							});
							var tr = '<tr>';
							tr += amphpSmCell(amphpSmFa(start+i+1),'na');
							tr += amphpSmCell('<div style="font-weight:700;max-width:200px">'+amphpEsc(r.title||r.bare||'')+'</div>','na');
							tr += amphpSmCell(amphpEsc(r.profile||'—'),'na');
							tr += amphpSmCell(amphpSmMoney(r.src_price), r.src_price>0?'na':'no_src');
							tr += amphpSmCell(amphpSmMoney(r.profile_price), r.profile_price>0?'na':'no_src');
							tr += amphpSmCell(amphpSmMoney(r.woo_expect), r.woo_expect>0?'na':'no_src');
							if(r.woo){
								tr += amphpSmCell(amphpSmMoney(r.woo.price)+'<div style="font-size:9px;opacity:.75">#'+amphpSmFa(r.woo.id)+' · '+amphpEsc(r.woo.status||'')+'</div>', r.woo_tone||'na');
							} else {
								tr += amphpSmCell(r.profile_hits?'نیست':'—', r.profile_hits?'missing_dst':'na');
							}
							shops.forEach(function(sh){
								var cell = (r.shops && r.shops[sh.vendor_id]) ? r.shops[sh.vendor_id] : null;
								if(cell){
									var exp = cell.expect || r.bsl_expect || 0;
									tr += amphpSmCell(amphpSmMoney(cell.price)+'<div style="font-size:9px;opacity:.75">انتظار '+amphpSmMoney(exp)+' · #'+amphpSmFa(cell.id)+'</div>', cell.tone||'na');
								} else {
									tr += amphpSmCell(r.profile_hits?'نیست':'—', r.profile_hits?'missing_dst':'na');
								}
							});
							tr += amphpSmCell(st[0]+flags, st[1]);
							tr += '</tr>';
							return tr;
						}).join('');
					}
					if(pager){
						var pg = '';
						var mk = function(lab,p,dis){
							return '<button type="button" class="button'+(p===d.page?' button-primary':'')+'" '+(dis?'disabled':'')+' data-sm-page="'+p+'" style="font-size:12px;min-width:36px">' + lab + '</button>';
						};
						pg += mk('«', Math.max(1,(d.page||1)-1), (d.page||1)<=1);
						var a = Math.max(1,(d.page||1)-2), b = Math.min(d.pages||1,(d.page||1)+2);
						for(var p=a;p<=b;p++) pg += mk(amphpSmFa(p), p, false);
						pg += mk('»', Math.min(d.pages||1,(d.page||1)+1), (d.page||1)>=(d.pages||1));
						pager.innerHTML = pg;
						$(pager).find('[data-sm-page]').on('click', function(){
							var p = parseInt($(this).attr('data-sm-page'),10)||1;
							amphpSmLoad(p);
						});
					}
				});
			}
			$(function(){
				$('#amphpSmStart').on('click', function(e){ e.preventDefault(); amphpSmStart(); });
				$('#amphpSmLoad, #amphpSmFilter').on('click', function(e){ e.preventDefault(); amphpSmLoad(1); });
				$('#amphpSmFix').on('click', function(e){ e.preventDefault(); amphpSmFixStart('all'); });
				$('#amphpSmFixWoo').on('click', function(e){ e.preventDefault(); amphpSmFixStart('woo'); });
				$('#amphpSmFixBsl').on('click', function(e){ e.preventDefault(); amphpSmFixStart('bsl'); });
				$('#amphpSmFixStop').on('click', function(e){ e.preventDefault(); amphpSmFixStop(); });
				$('#amphpSmPer').on('change', function(){ amphpSmLoad(1); });
				$('#amphpSmQ').on('keydown', function(e){ if(e.key==='Enter'){ e.preventDefault(); amphpSmLoad(1); }});
				/* silent status on load */
				amphpSmAjax('scraper_sync_matrix_status', {}, function(d){
					if(!d) return;
					if(d.running){ amphpSmPaintProgress(d.progress||{}); amphpSmPoll(); }
					var badge = document.getElementById('amphpSmBadge');
					if(badge && typeof d.woo_direct !== 'undefined'){
						badge.textContent = d.woo_direct ? '⚡ WC مستقیم' : '🌐 WC از API';
					}
					var hint = document.getElementById('amphpSmHint');
					if(hint && d.has_result){
						hint.innerHTML = '<span style="color:#67e8f9">نتیجهٔ قبلی: '+amphpSmFa(d.result_rows||0)+' ردیف — «خواندن» را بزنید</span>';
					}
				});
			});

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
			/* v13.3.32: nonce درست برای پل ووکامرس (قبلاً mismatch → 403/-1) */
			var amphpWooNonce = '<?php echo esc_js( wp_create_nonce( 'scraper_woo_bridge' ) ); ?>';
			var amphpWooNonceAlt = '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>';
			function amphpShowWooReport(obj) {
				var $pre = $('#amphpWooBridgeReport');
				try {
					$pre.text(typeof obj === 'string' ? obj : JSON.stringify(obj, null, 2)).show();
				} catch (e) {
					$pre.text(String(obj)).show();
				}
			}
			function amphpParseWooXhrError(xhr) {
				var body = (xhr && xhr.responseText) ? String(xhr.responseText).trim() : '';
				var parsed = null;
				try { parsed = body ? JSON.parse(body) : null; } catch (e) { parsed = null; }
				if (body === '-1' || body === '0' || (xhr && xhr.status === 403 && !parsed)) {
					return {
						http: xhr ? xhr.status : 0,
						body: body,
						message: 'نشست امنیتی نامعتبر (nonce). صفحه را یک‌بار رفرش کنید (Ctrl+F5) و دوباره تست کنید.',
						code: 'bad_nonce'
					};
				}
				if (parsed && parsed.data) {
					return parsed.data;
				}
				return { http: xhr ? xhr.status : 0, body: body, message: 'خطای ارتباط با سرور' };
			}
			function amphpWooAjax(action, extra, $btn, idleLabel, busyLabel, onOk) {
				var $st = $('#amphpWooBridgeStatus');
				$btn.prop('disabled', true).text(busyLabel);
				var payload = $.extend({
					action: action,
					nonce: amphpWooNonce,
					_ajax_nonce: amphpWooNonce
				}, extra || {});
				$.ajax({
					url: (typeof ajaxurl !== 'undefined' ? ajaxurl : '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>'),
					type: 'POST',
					dataType: 'json',
					data: payload,
					success: function(res){
						$btn.prop('disabled', false).text(idleLabel);
						if (res && res.success) {
							onOk(res);
						} else {
							var d = (res && res.data) ? res.data : {};
							var err = (typeof d === 'string') ? d : (d.message || 'خطا');
							$st.html('<span style="color:#dc2626;">❌ ' + err + '</span>');
							amphpShowWooReport(d);
						}
					},
					error: function(xhr){
						/* یک‌بار با nonce جایگزین تلاش کن */
						if (!payload._amphpRetried && (xhr.status === 403 || String(xhr.responseText||'').trim() === '-1')) {
							payload._amphpRetried = 1;
							payload.nonce = amphpWooNonceAlt;
							payload._ajax_nonce = amphpWooNonceAlt;
							$.ajax({
								url: payload.url || (typeof ajaxurl !== 'undefined' ? ajaxurl : '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>'),
								type: 'POST', dataType: 'json', data: payload,
								success: function(res2){
									$btn.prop('disabled', false).text(idleLabel);
									if (res2 && res2.success) { onOk(res2); }
									else {
										var d2 = (res2 && res2.data) ? res2.data : {};
										$st.html('<span style="color:#dc2626;">❌ ' + ((d2 && d2.message) || 'خطا') + '</span>');
										amphpShowWooReport(d2);
									}
								},
								error: function(xhr2){
									$btn.prop('disabled', false).text(idleLabel);
									var info = amphpParseWooXhrError(xhr2);
									$st.html('<span style="color:#dc2626;">❌ ' + (info.message || 'خطای ارتباط') + '</span>');
									amphpShowWooReport(info);
								}
							});
							return;
						}
						$btn.prop('disabled', false).text(idleLabel);
						var info = amphpParseWooXhrError(xhr);
						$st.html('<span style="color:#dc2626;">❌ ' + (info.message || 'خطای ارتباط') + '</span>');
						amphpShowWooReport(info);
					}
				});
			}
			$('#btnTestWooDirect').on('click', function(){
				var $btn = $(this);
				$('#amphpWooBridgeStatus').html('<span style="color:#2563eb;">آزمایش ایجاد draft و پاک‌سازی...</span>');
				amphpWooAjax('scraper_test_woo_direct', {}, $btn, '🧪 تست اتصال مستقیم', 'در حال تست...', function(res){
					var d = (res && res.data) ? res.data : res;
					var ok = !!(d && (d.ok === true || (d.direct && d.direct.ok)));
					var msg = (d && (d.message || (d.direct && d.direct.message))) || (ok ? 'OK' : 'ناموفق');
					$('#amphpWooBridgeStatus').html(ok
						? '<span style="color:#16a34a;">✅ ' + msg + '</span>'
						: '<span style="color:#dc2626;">❌ ' + msg + '</span>');
					amphpShowWooReport(d);
				});
			});
			$('#btnEnableWooDirect').on('click', function(){
				var $btn = $(this);
				if (!window.confirm('sync_mode=direct در connections.json نوشته شود؟')) return;
				$('#amphpWooBridgeStatus').html('<span style="color:#2563eb;">نوشتن connections.json...</span>');
				amphpWooAjax('scraper_enable_woo_direct', {
					fallback: $('#amphpWooFallback').val() || 'direct_then_api'
				}, $btn, '⚡ فعال‌سازی اتصال مستقیم', 'در حال فعال‌سازی...', function(res){
					var d = (res && res.data) ? res.data : {};
					$('#amphpWooBridgeStatus').html('<span style="color:#16a34a;">✅ ' + (d.message || 'فعال شد') + '</span>');
					$('#amphpWooSyncModeLabel').text(d.sync_mode || 'direct');
					$('#amphpWooEnabledLabel').text('بله').css('color', '#059669');
					if (d.store_url) $('#amphpWooStoreLabel').text(d.store_url);
					$('#amphpWooBridgeBadge').text('آماده · direct').removeClass('field-badge-blue').addClass('field-badge-green');
					amphpShowWooReport(d);
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
