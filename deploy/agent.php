<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 13.2.0
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
			'enable_scraped_products'     => true, // تیک ۲: محصولات هوشمند و جدید اسکرپر
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
			return $defaults;
		}

		return array_merge( $defaults, $opts );
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
		$candidates = array(
			plugin_dir_path( __FILE__ ) . 'profiles.json',
			dirname( __FILE__ ) . '/profiles.json',
			defined( 'WP_CONTENT_DIR' ) ? WP_CONTENT_DIR . '/uploads/profiles.json' : '',
		);

		$file = null;
		foreach ( $candidates as $cand ) {
			if ( ! empty( $cand ) && file_exists( $cand ) ) {
				$file = $cand;
				break;
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
				'limit'   => 100,
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
						'id'                  => $p_id,
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
					);
				}
			}
		}

		// Fallback to get_posts if wc_get_products returned empty
		if ( empty( $products ) ) {
			$raw_posts = get_posts( array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 100,
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
						'id'                  => $pid,
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
					);
				}
			}
		}

		return $products;
	}

	public static function get_all_scraped_products() {
		$settings = self::get_settings();
		$use_scraped = ! empty( $settings['enable_scraped_products'] );

		// State 2 & State 4: If scraped products toggle is unchecked, return native WooCommerce / WordPress products!
		if ( ! $use_scraped ) {
			return self::get_woocommerce_native_products();
		}

		$products = array();
		$seen     = array();

		// Check profiles.json in plugin directory
		$profiles_file = plugin_dir_path( __FILE__ ) . 'profiles.json';
		if ( file_exists( $profiles_file ) ) {
			$json = @file_get_contents( $profiles_file );
			$data = @json_decode( $json, true );
			if ( is_array( $data ) ) {
				foreach ( $data as $p_key => $p_item ) {
					if ( ! is_array( $p_item ) ) {
						continue;
					}
					$raw_prods = $p_item['products'] ?? array();
					if ( is_array( $raw_prods ) ) {
						foreach ( $raw_prods as $entry ) {
							$prod = null;
							if ( is_array( $entry ) ) {
								if ( isset( $entry[1] ) && is_array( $entry[1] ) ) {
									$prod = $entry[1];
								} elseif ( isset( $entry['title'] ) || isset( $entry['name'] ) ) {
									$prod = $entry;
								}
							}

							if ( ! is_array( $prod ) ) {
								continue;
							}

							$title = trim( $prod['title'] ?? $prod['name'] ?? '' );
							if ( '' === $title ) {
								continue;
							}

							$hash = md5( $title . ( $prod['link'] ?? $prod['url'] ?? '' ) );
							if ( isset( $seen[ $hash ] ) ) {
								continue;
							}
							$seen[ $hash ] = true;

							$price_calc = self::calculate_price( $prod, $settings );

							// Extract images
							$img = $prod['image'] ?? $prod['img'] ?? '';
							$gallery = array();
							if ( ! empty( $prod['images'] ) && is_array( $prod['images'] ) ) {
								$gallery = $prod['images'];
								if ( empty( $img ) && ! empty( $gallery[0] ) ) {
									$img = $gallery[0];
								}
							} elseif ( ! empty( $img ) ) {
								$gallery[] = $img;
							}

							$desc = $prod['long_desc'] ?? $prod['description'] ?? $prod['desc'] ?? $prod['short_desc'] ?? '';
							$cat  = $prod['category'] ?? $prod['cat'] ?? 'عمومی';

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
								'category'            => $cat,
								'description'         => $desc,
								'in_stock'            => true,
							);
						}
					}
				}
			}
		}

		// Also check scraper temp cache (woo_products_temp.json)
		$woo_temp = plugin_dir_path( __FILE__ ) . 'woo_products_temp.json';
		if ( file_exists( $woo_temp ) ) {
			$json = @file_get_contents( $woo_temp );
			$data = @json_decode( $json, true );
			if ( is_array( $data ) ) {
				foreach ( $data as $prod ) {
					if ( is_array( $prod ) ) {
						$title = trim( $prod['title'] ?? $prod['name'] ?? '' );
						if ( '' !== $title ) {
							$hash = md5( $title . ( $prod['link'] ?? $prod['url'] ?? '' ) );
							if ( ! isset( $seen[ $hash ] ) ) {
								$seen[ $hash ] = true;
								$price_calc     = self::calculate_price( $prod, $settings );
								$img            = $prod['image'] ?? $prod['img'] ?? '';
								$gallery        = ! empty( $prod['images'] ) && is_array( $prod['images'] ) ? $prod['images'] : ( ! empty( $img ) ? array( $img ) : array() );
								$products[]     = array(
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
									'category'            => $prod['category'] ?? $prod['cat'] ?? 'عمومی',
									'description'         => $prod['description'] ?? $prod['desc'] ?? '',
									'in_stock'            => true,
								);
							}
						}
					}
				}
			}
		}

		// Also check existing WooCommerce products if available
		if ( empty( $products ) && function_exists( 'wc_get_products' ) ) {
			$wc_prods = wc_get_products( array( 'limit' => 50, 'status' => 'publish' ) );
			if ( ! empty( $wc_prods ) ) {
				foreach ( $wc_prods as $wp_prod ) {
					$w_id        = $wp_prod->get_id();
					$w_title     = $wp_prod->get_name();
					$w_reg_price = (float) $wp_prod->get_regular_price();
					$w_sale_price= (float) $wp_prod->get_sale_price();
					$w_price     = (float) $wp_prod->get_price();
					$w_img_id    = $wp_prod->get_image_id();
					$w_img       = $w_img_id ? wp_get_attachment_image_url( $w_img_id, 'full' ) : '';
					$w_cats      = wc_get_product_category_list( $w_id );
					$w_cat_clean = strip_tags( $w_cats ) ?: 'عمومی';

					$calc = self::calculate_price( array(
						'price'         => $w_price,
						'regular_price' => $w_reg_price > $w_price ? $w_reg_price : 0,
					), $settings );

					$products[] = array(
						'id'                  => 'wc_' . $w_id,
						'title'               => $w_title,
						'has_price'           => $calc['has_price'],
						'original_price'      => $calc['original'],
						'price'               => $calc['adjusted'],
						'price_formatted'     => $calc['formatted'],
						'old_price'           => $calc['old_price'],
						'old_price_formatted' => $calc['formatted_old'],
						'has_discount'        => $calc['has_discount'],
						'discount_pct'        => $calc['discount_pct'],
						'image'               => $w_img,
						'gallery'             => array( $w_img ),
						'category'            => $w_cat_clean,
						'description'         => $wp_prod->get_short_description() ?: $wp_prod->get_description(),
						'in_stock'            => $wp_prod->is_in_stock(),
					);
				}
			}
		}

		// Demo fallback items if still empty so the shop displays beautifully on preview
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
			$loaded = function_exists( 'aiMasterKey' ) || function_exists( 'aiCandidates' );
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
		self::load_scraper_ai_engine();
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
	 * Retrieve Master AI model directly from scraper4 (connections.json / ai_votes.json / ai_providers.json).
	 *
	 * @param array|null $settings
	 * @return array
	 */
	public static function get_scraper_master_ai_model( $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}

		$cands_info = self::get_scraper_ai_candidates();
		$master_key = $cands_info['master'];
		$pin_key    = $cands_info['pin'];

		$plugin_dir = plugin_dir_path( __FILE__ );
		$prov_file  = $plugin_dir . 'ai_providers.json';
		$prov_data  = file_exists( $prov_file ) ? ( @json_decode( file_get_contents( $prov_file ), true ) ?: array() ) : array();

		$provider_id = '';
		$model_id    = '';
		if ( ! empty( $master_key ) && strpos( $master_key, '::' ) !== false ) {
			list( $provider_id, $model_id ) = explode( '::', $master_key, 2 );
		}

		$prov_cfg = $prov_data[ $provider_id ] ?? null;
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
		$api_key = $prov_cfg['apiKey'] ?? ( $prov_cfg['keys'][0]['key'] ?? $custom_key );
		if ( empty( $api_key ) && ! empty( $custom_key ) ) {
			$api_key = $custom_key;
		}

		$endpoint = $prov_cfg['endpoint'] ?? ( $prov_cfg['url'] ?? '' );
		if ( empty( $endpoint ) ) {
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

		return array(
			'provider_id'   => $provider_id ?: 'openrouter',
			'model_id'      => $model_id ?: 'meta-llama/llama-3.3-70b-instruct:free',
			'key'           => $master_key ?: 'openrouter::meta-llama/llama-3.3-70b-instruct:free',
			'model_name'    => $model_name ?: 'Llama 3.3 70B (رایگان)',
			'provider_name' => $prov_cfg['name'] ?? ( $master_cand['providerName'] ?? ucfirst( $provider_id ?: 'openrouter' ) ),
			'api_key'       => trim( (string) $api_key ),
			'endpoint'      => trim( (string) $endpoint ),
			'provider'      => $prov_cfg,
			'is_pinned'     => ( $pin_key === $master_key ),
			'score'         => $master_cand['score'] ?? 0.889,
			'wins'          => $master_cand['wins'] ?? 8,
			'losses'        => $master_cand['losses'] ?? 1,
			'votes'         => $master_cand['votes'] ?? 9,
			'source'        => 'scraper4_master',
		);
	}

	/**
	 * Call Live AI Provider API (OpenAI / OpenRouter / Groq / DeepSeek / Ollama / Gemini).
	 *
	 * @param array  $master_ai
	 * @param string $message
	 * @param string $customer_name
	 * @param array  $settings
	 * @return string
	 */
	public static function call_ai_api( $master_ai, $message, $customer_name, $settings ) {
		$endpoint = $master_ai['endpoint'];
		$api_key  = $master_ai['api_key'];
		$model_id = $master_ai['model_id'];

		if ( empty( $endpoint ) ) {
			$endpoint = 'https://openrouter.ai/api/v1/chat/completions';
		}

		$site_name = get_bloginfo( 'name' ) ?: ( $settings['shop_title'] ?? 'فروشگاه اینترنتی' );
		$catalog_ctx = self::build_catalog_context_for_ai( 40 );
		$threshold = number_format( (float) ( $settings['free_shipping_threshold'] ?? 400000 ) );
		$currency  = $settings['currency_symbol'] ?? 'تومان';

		$base_user_prompt = ! empty( $settings['ai_system_prompt'] )
			? $settings['ai_system_prompt']
			: "تو دستیار هوشمند و کارشناس فروش رسمی فروشگاه اینترنتی «{$site_name}» هستی.";
		$system_prompt = $base_user_prompt . "
"
			. "نام مشتری: «{$customer_name}»
"
			. "ارسال رایگان برای خریدهای بالای {$threshold} {$currency}.
"
			. "ضمانت ۷ روز بازگشت و اصالت کالا.

"
			. "دسترسی زنده به داده فروشگاه (محصولات، سفارش‌ها، آمار):
"
			. $catalog_ctx . "

"
			. "قوانین پاسخ:
"
			. "۱. فقط بر اساس داده بالا جواب بده؛ اگر نبود بگو در دسترس نیست.
"
			. "۲. برای قیمت/موجودی از کاتالوگ دقیق نقل کن.
"
			. "۳. برای پیگیری سفارش، شماره سفارش یا موبایل بخواه و از بخش سفارش‌ها استفاده کن.
"
			. "۴. لحن گرم، حرفه‌ای و فارسی. خودت را ربات معرفی نکن.
"
			. "۵. می‌توانی Markdown سبک بنویسی: **پررنگ**، لیست با - یا ۱. ، `کد`، و لینک [متن](url).
"
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

		$headers = array(
			'Content-Type' => 'application/json; charset=utf-8',
		);
		if ( ! empty( $api_key ) ) {
			$headers['Authorization'] = 'Bearer ' . $api_key;
		}
		if ( strpos( $endpoint, 'openrouter.ai' ) !== false ) {
			$headers['HTTP-Referer'] = home_url();
			$headers['X-Title']      = $site_name;
		}

		$response = wp_remote_post( $endpoint, array(
			'method'    => 'POST',
			'timeout'   => 12,
			'headers'   => $headers,
			'body'      => wp_json_encode( $payload ),
			'sslverify' => false,
		) );

		if ( ! is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );
			$json = @json_decode( $body, true );
			if ( is_array( $json ) ) {
				$text = '';
				if ( ! empty( $json['choices'][0]['message']['content'] ) ) {
					$text = trim( (string) $json['choices'][0]['message']['content'] );
				} elseif ( ! empty( $json['choices'][0]['text'] ) ) {
					$text = trim( (string) $json['choices'][0]['text'] );
				} elseif ( ! empty( $json['response'] ) ) {
					$text = trim( (string) $json['response'] );
				}
				if ( ! empty( $text ) ) {
					$text = preg_replace( '/<think>.*?<\/think>/si', '', $text );
					$text = preg_replace( '/\s+/u', ' ', trim( $text ) );
					return $text;
				}
			}
		}

		return '';
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

		$products = self::get_all_scraped_products();

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

		// 1. Resolve master AI configuration
		$master_ai = self::get_scraper_master_ai_model( $settings );

		// 2. If an API key or local endpoint exists, query the live generative model
		if ( ! empty( $master_ai['api_key'] ) || strpos( (string) $master_ai['endpoint'], '127.0.0.1' ) !== false || strpos( (string) $master_ai['endpoint'], 'localhost' ) !== false ) {
			$reply = self::call_ai_api( $master_ai, $message, $customer_name, $settings );
			if ( ! empty( $reply ) ) {
				return $reply;
			}
		}

		// 3. Dynamic Contextual E-Commerce NLP Engine (analyzes catalog, prices, features & query)
		return self::generate_smart_local_reply( $message, $customer_name, $settings );
	}

	/**
	 * AJAX endpoint for live testing AI chat in Admin Tab 4.
	 */
	public static function ajax_test_ai_chat() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

		$message = sanitize_text_field( $_POST['message'] ?? '' );
		if ( empty( $message ) ) {
			wp_send_json_error( 'متن پیام خالی است.' );
		}

		$settings = self::get_settings();
		$t0 = microtime( true );
		$reply = self::generate_ai_support_reply( $message, 'کاربر آزمایشی', $settings );
		$time_ms = (int) round( ( microtime( true ) - $t0 ) * 1000 );

		$master_ai = self::get_scraper_master_ai_model( $settings );
		$model_label = ! empty( $master_ai['model_name'] ) ? $master_ai['model_name'] : 'مدل هوشمند مستر اسکرپر';

		wp_send_json_success( array(
			'reply'     => $reply,
			'model'     => $model_label,
			'provider'  => $master_ai['provider_name'] ?? 'اسکرپر',
			'key'       => $master_ai['key'] ?? '',
			'score'     => $master_ai['score'] ?? 0.889,
			'is_pinned' => $master_ai['is_pinned'] ?? false,
			'source'    => $master_ai['source'] ?? 'scraper4_master',
			'took_ms'   => $time_ms,
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

			$cand_master = array(
				'provider_id' => $p_id,
				'model_id'    => $m_id,
				'model_name'  => $c['modelName'],
				'api_key'     => $p_cfg['apiKey'] ?? ( $p_cfg['keys'][0]['key'] ?? ( $settings['ai_api_key'] ?? '' ) ),
				'endpoint'    => $p_cfg['endpoint'] ?? ( $p_cfg['url'] ?? '' ),
				'provider'    => $p_cfg,
			);

			$reply = '';
			if ( ! empty( $cand_master['api_key'] ) || strpos( (string) $cand_master['endpoint'], '127.0.0.1' ) !== false ) {
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
	public static function ajax_get_product() {
		$id = sanitize_text_field( wp_unslash( $_REQUEST['id'] ?? $_REQUEST['product_id'] ?? '' ) );
		if ( $id === '' ) {
			wp_send_json_error( array( 'message' => 'شناسه محصول خالی است' ), 400 );
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
		if ( ! $found ) {
			// fallback: partial title match only if exact id miss and looks like hash
			wp_send_json_error( array( 'message' => 'محصول یافت نشد', 'id' => $id ), 404 );
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
		$desc_clean = wp_strip_all_tags( $desc );
		$short_clean = wp_strip_all_tags( $short );
		if ( $desc_clean === '' && $short_clean !== '' ) {
			$desc_clean = $short_clean;
		}

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
			'description'          => $desc_clean,
			'short_desc'           => $short_clean,
			'in_stock'             => isset( $found['in_stock'] ) ? (bool) $found['in_stock'] : true,
			'specs'                => $specs,
			'variations_text'      => $variations_text,
			'variation_groups'     => is_array( $found['variation_groups'] ?? null ) ? $found['variation_groups'] : array(),
		);
		wp_send_json_success( array( 'product' => $out ) );
	}

	public static function ajax_submit_support_chat() {
		if ( ! empty( $_POST['nonce'] ) ) { check_ajax_referer( 'scraper_support_chat_nonce', 'nonce', false ); }
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

		// 2. Generate Master AI Reply if enabled
		$_ai_msg = $message;
		if ( ! empty( $GLOBALS['_amphp_chat_product_ctx'] ) ) {
			$_ai_msg .= (string) $GLOBALS['_amphp_chat_product_ctx'];
		}
		$ai_reply  = self::generate_ai_support_reply( $_ai_msg, $thread['name'], $settings );
		$master_ai = self::get_scraper_master_ai_model();
		$model_lbl = ! empty( $master_ai['model_name'] ) ? $master_ai['model_name'] : 'هوش مصنوعی پشتیبان';

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

		wp_send_json_success( array(
			'message'    => 'پیام شما با موفقیت ثبت شد.',
			'session_id' => $session_id,
			'thread_id'  => $thread_id,
			'ai_reply'   => $ai_reply,
			'ai_model'   => $model_lbl,
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
		$ver = '13.2.0';
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
		header( 'X-AMPHP-Storefront: bare-v13.2.0' );
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

		// Lean product payload — no gallery arrays / long descriptions (huge JSON was slowing TTFB).
		$safe_products = array();
		$max_products  = 120; // hard cap for initial boot payload
		$i = 0;
		foreach ( (array) $products as $p ) {
			if ( ! is_array( $p ) ) {
				continue;
			}
			if ( ++$i > $max_products ) {
				break;
			}
			$desc = wp_strip_all_tags( (string) ( $p['description'] ?? '' ) );
			if ( function_exists( 'mb_substr' ) ) {
				$desc = mb_substr( $desc, 0, 160 );
			} else {
				$desc = substr( $desc, 0, 160 );
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
				'version'   => '13.2.0',
				'engine'    => 'react',
				'count'     => count( $safe_products ),
				'is_admin'  => current_user_can( 'manage_options' ),
			),
		);

		$css_url = self::storefront_asset_url( 'storefront.css' );
		$js_url  = self::storefront_asset_url( 'storefront.js' );
		$ver = '13.2.0';

		// Mark assets as printed so wp_enqueue does not double-load the bundle.
		$GLOBALS['amphp_storefront_assets_printed'] = true;

		$boot_json = wp_json_encode( $boot, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_SLASHES );
		if ( false === $boot_json ) {
			$boot_json = '{"settings":{},"products":[],"urls":{},"ajax":{},"meta":{"error":"json"}}';
		}

		ob_start();
		?>
		<!-- AMPHP Storefront v13.2.0 -->
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
		$json = wp_json_encode( $reg, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT );
		$html  = '<style id="appFontVars">:root{--app-font:' . esc_attr( $fb ) . '}</style>';
		$html .= '<script>(function(){var F=' . $json . ';var KEY="scraper_font",FB=' . wp_json_encode( $fb ) . ';window.APP_FONTS=F;window.APP_FONT_KEY=KEY;window.APP_FONT_FALLBACK=FB;function head(){return document.head||document.documentElement;}function readFont(){try{var v=localStorage.getItem(KEY);return (v&&F[v])?v:"vazirmatn";}catch(e){return"vazirmatn";}}function applyFont(k,save){if(!F[k])k="vazirmatn";var f=F[k];if(save){try{localStorage.setItem(KEY,k);}catch(e){}}try{document.documentElement.style.setProperty("--app-font",f.stack);}catch(e){}if(f.css){var lid="appFontLink_"+k;if(!document.getElementById(lid)){var l=document.createElement("link");l.id=lid;l.rel="stylesheet";l.href=f.css;head().appendChild(l);}}if(f.face){var sid="appFontFace_"+k;if(!document.getElementById(sid)){var s=document.createElement("style");s.id=sid;s.appendChild(document.createTextNode(f.face));head().appendChild(s);}}window.APP_FONT_CURRENT=k;return k;}window.appFontApply=applyFont;window.appFontCurrent=readFont;applyFont(readFont(),false);try{window.addEventListener("storage",function(e){if(e&&e.key===KEY)applyFont(readFont(),false);});}catch(e){}})();</script>';
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
	public static function get_embedded_storefront_assets() {
		static $cache = null;
		if ( null !== $cache ) {
			return $cache;
		}
		// Optional external pack (smaller agent.php deploys).
		$pack = plugin_dir_path( __FILE__ ) . 'includes/storefront/embedded-assets.php';
		if ( is_readable( $pack ) ) {
			$loaded = include $pack;
			if ( is_array( $loaded ) ) {
				$cache = $loaded;
				return $cache;
			}
		}
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
		$ver = '13.2.0';
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
		header( 'Cache-Control: public, max-age=86400' );
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
		$ver = '13.1.2';
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
			$new_settings = array(
				// Tab 1: Storefront & Appearance
				'enable_shop_takeover'        => ! empty( $_POST['enable_shop_takeover'] ),
				'enable_scraped_products'     => ! empty( $_POST['enable_scraped_products'] ),
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

						<!-- 🎛️ سیستم کنترل دوگانه وضعیت فروشگاه (۴ حالت ممکن) -->
						<div style="margin-bottom:24px; background:linear-gradient(135deg, #f0fdf4 0%, #eff6ff 100%); border:2px solid #3b82f6; border-radius:16px; padding:22px; box-shadow:0 4px 15px rgba(37,99,235,0.08);">
							<div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:12px;">
								<h4 style="margin:0; font-size:1.15rem; color:#1e3a8a; font-weight:900; display:flex; align-items:center; gap:8px;">
									<span>🎛️</span> سیستم کنترل دوگانه قالب و محصولات (۴ حالت ممکن)
								</h4>
								<span style="background:#2563eb; color:#fff; font-size:0.75rem; font-weight:800; padding:4px 12px; border-radius:20px;">
									کنترل مستقل با دو تیک مجزا
								</span>
							</div>
							<p style="margin:0 0 16px; font-size:0.88rem; color:#334155; line-height:1.7;">
								با دو تیک زیر می‌توانید قالب و ظاهر فروشگاه را مستقل از منبع محصولات تنظیم نمایید. در حالت پیش‌فرض، هر دو تیک فعال هستند تا کامل‌ترین و مدرن‌ترین ویترین اختصاصی به مشتریان نمایش داده شود.
							</p>

							<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:14px; margin-bottom:16px;">
								<!-- تیک ۱: قالب، هدر و منوی اختصاصی -->
								<label style="background:#ffffff; border:1.5px solid #cbd5e1; border-radius:14px; padding:16px; cursor:pointer; display:flex; flex-direction:column; gap:10px; transition:all 0.2s;" id="labelStoreTakeover">
									<div style="display:flex; align-items:center; gap:10px;">
										<input type="checkbox" name="enable_shop_takeover" id="chkStoreTakeover" value="1" <?php checked( ! empty( $opts['enable_shop_takeover'] ) ); ?> style="width:20px; height:20px; accent-color:#2563eb;">
										<strong style="font-size:1rem; color:#0f172a;">۱. فعال‌سازی پوسته، هدر و منوی مدرن اختصاصی</strong>
									</div>
									<div style="font-size:0.82rem; color:#64748b; line-height:1.65; padding-right:30px;">
										✅ <strong>هدر و منوی قالب وردپرس کلاً برداشته می‌شود</strong> و هدر اختصاصی شامل لوگو، مگامنو دسته‌بندی‌ها، جستجوی زنده، دکمه‌های حساب کاربری، سبد خرید و منوی همبرگری فعال می‌گردد.<br>
										❌ با برداشتن این تیک، ظاهر، هدر و منو دقیقاً به قالب اصلی وردپرس بازمی‌گردد.
									</div>
								</label>

								<!-- تیک ۲: محصولات هوشمند اسکرپر -->
								<label style="background:#ffffff; border:1.5px solid #cbd5e1; border-radius:14px; padding:16px; cursor:pointer; display:flex; flex-direction:column; gap:10px; transition:all 0.2s;" id="labelScrapedProducts">
									<div style="display:flex; align-items:center; gap:10px;">
										<input type="checkbox" name="enable_scraped_products" id="chkScrapedProducts" value="1" <?php checked( ! empty( $opts['enable_scraped_products'] ) ); ?> style="width:20px; height:20px; accent-color:#10b981;">
										<strong style="font-size:1rem; color:#0f172a;">۲. فعال‌سازی محصولات جدید و هوشمند اسکرپر</strong>
									</div>
									<div style="font-size:0.82rem; color:#64748b; line-height:1.65; padding-right:30px;">
										✅ محصولات جدید و هوشمند استخراج‌شده از اسکرپر (با اعمال ضرایب سود، تخفیف‌ها و پالایش ممیزها) نمایش داده می‌شوند.<br>
										❌ با برداشتن این تیک، محصولات اصلی و واقعی موجود در دیتابیس ووکامرس و وردپرس لود می‌شوند.
									</div>
								</label>
							</div>

							<!-- جعبه وضعیت زنده ۴ حالت -->
							<div id="boxDualStateIndicator" style="background:#ffffff; border:1.5px dashed #2563eb; border-radius:12px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
								<div style="display:flex; align-items:center; gap:8px;">
									<span style="font-size:0.85rem; color:#64748b;">وضعیت فعال فروشگاه:</span>
									<strong id="dualStateTitle" style="font-size:0.95rem; color:#1d4ed8;">—</strong>
								</div>
								<div id="dualStateDesc" style="font-size:0.84rem; color:#475569; line-height:1.5;">—</div>
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

			// Dual State Interactive Indicator (4 States)
			function updateDualStateUI() {
				var hasTakeover = $('#chkStoreTakeover').is(':checked');
				var hasProducts = $('#chkScrapedProducts').is(':checked');
				var $box = $('#boxDualStateIndicator');
				var $title = $('#dualStateTitle');
				var $desc = $('#dualStateDesc');

				$('#labelStoreTakeover').css('border-color', hasTakeover ? '#2563eb' : '#cbd5e1');
				$('#labelScrapedProducts').css('border-color', hasProducts ? '#10b981' : '#cbd5e1');

				if (hasTakeover && hasProducts) {
					// State 1: Default
					$box.css({background: '#eff6ff', borderColor: '#2563eb'});
					$title.css('color', '#1d4ed8').html('🌟 حالت ۱ (پیش‌فرض): ویترین و هدر اختصاصی + محصولات جدید اسکرپر');
					$desc.html('هدر و منوی قالب وردپرس کلاً حذف شده، هدر و منوی اختصاصی فعال است و محصولات هوشمند اسکرپر نمایش می‌یابند.');
				} else if (hasTakeover && !hasProducts) {
					// State 2: Custom theme with native WooCommerce products
					$box.css({background: '#f0fdf4', borderColor: '#10b981'});
					$title.css('color', '#047857').html('🎨 حالت ۲: ویترین و هدر اختصاصی + محصولات اصلی ووکامرس');
					$desc.html('هدر و منوی قالب وردپرس کلاً حذف شده و هدر اختصاصی فعال است، اما محصولات مستقیماً از دیتابیس اصلی ووکامرس لود می‌شوند.');
				} else if (!hasTakeover && hasProducts) {
					// State 3: Native WP Theme with Scraped products
					$box.css({background: '#fffbeb', borderColor: '#f59e0b'});
					$title.css('color', '#b45309').html('🛍️ حالت ۳: قالب و هدر اصلی وردپرس + محصولات جدید اسکرپر');
					$desc.html('هدر، منو و قالب اصلی وردپرس بدون تغییر نمایش می‌یابند، اما محصولات هوشمند جدید اسکرپر لود می‌شوند.');
				} else {
					// State 4: Fully reverted to original WP & WooCommerce
					$box.css({background: '#f8fafc', borderColor: '#94a3b8'});
					$title.css('color', '#475569').html('↩️ حالت ۴: بازگشت کامل به حالت اولیه (قالب پیش‌فرض وردپرس + محصولات اصلی ووکامرس)');
					$desc.html('سایت کاملاً به حالت استاندارد بدون هیچ‌گونه تغییرات ظاهری یا محصولی از سوی افزونه بازمی‌گردد.');
				}
			}

			$('#chkStoreTakeover, #chkScrapedProducts').on('change', updateDualStateUI);
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
					data: {
						action: 'scraper_test_ai_chat',
						nonce: adminNonce,
						message: msg
					},
					success: function(res){
						$btn.prop('disabled', false).text('🚀 پرسش از مدل مستر (Master AI)');
						if (res.success && res.data) {
							$text.text(res.data.reply);
							$badge.text('مدل مستر: ' + (res.data.model || 'هوشمند') + ' (' + (res.data.provider || 'اسکرپر') + ')');
							$time.text(res.data.took_ms + ' میلی‌ثانیه');
						} else {
							$text.html('<span style="color:#dc2626;">خطا: ' + (res.data || 'عدم دریافت پاسخ.') + '</span>');
						}
					},
					error: function(){
						$btn.prop('disabled', false).text('🚀 پرسش از مدل مستر (Master AI)');
						$text.html('<span style="color:#dc2626;">خطای ارتباط با سرور.</span>');
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
