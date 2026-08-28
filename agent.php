<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 13.3.12
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
			$records[] = array(
				'id'       => $order_id,
				'customer' => $customer,
				'items'    => $items,
				'total'    => $total,
				'payment'  => $payment_method,
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
		header( 'X-AMPHP-Storefront: bare-v13.3.12' );
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
				'version'     => '13.3.12',
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
		<!-- AMPHP Storefront v13.3.12 -->
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
		$parts = array( '13.3.12' );
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
