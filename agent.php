<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 13.1.8
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

	public static function sync_to_woocommerce() {
		$settings = self::get_settings();
		$products = self::get_all_scraped_products();
		$template = ! empty( $settings['store_template'] ) ? $settings['store_template'] : 'digikala';
		$palette  = ! empty( $settings['store_palette'] ) ? $settings['store_palette'] : 'digikala-red';
		$created  = 0;
		$updated  = 0;

		if ( empty( $products ) ) {
			return array(
				'ok'      => false,
				'message' => 'هیچ محصولی برای همگام‌سازی یافت نشد.',
				'created' => 0,
				'updated' => 0,
				'total'   => 0,
			);
		}

		foreach ( $products as $p ) {
			$existing_id = 0;
			$existing = get_posts( array(
				'post_type'   => 'product',
				'title'       => $p['title'],
				'post_status' => 'any',
				'numberposts' => 1,
			) );

			if ( ! empty( $existing ) && isset( $existing[0]->ID ) ) {
				$existing_id = $existing[0]->ID;
			}

			$post_data = array(
				'post_title'   => $p['title'],
				'post_content' => $p['description'],
				'post_status'  => 'publish',
				'post_type'    => 'product',
			);

			if ( $existing_id > 0 ) {
				$post_data['ID'] = $existing_id;
				$product_id      = wp_update_post( $post_data );
				$updated++;
			} else {
				$product_id = wp_insert_post( $post_data );
				$created++;
			}

			if ( $product_id && ! is_wp_error( $product_id ) ) {
				// Set WooCommerce Meta
				update_post_meta( $product_id, '_price', $p['price'] );
				update_post_meta( $product_id, '_regular_price', $p['price'] );
				if ( $p['original_price'] > $p['price'] ) {
					update_post_meta( $product_id, '_sale_price', $p['price'] );
					update_post_meta( $product_id, '_regular_price', $p['original_price'] );
				}
				update_post_meta( $product_id, '_manage_stock', 'no' );
				update_post_meta( $product_id, '_stock_status', 'instock' );

				// Assign category
				if ( ! empty( $p['category'] ) ) {
					wp_set_object_terms( $product_id, $p['category'], 'product_cat' );
				}

				// Image attachment
				if ( ! empty( $p['image'] ) && ! has_post_thumbnail( $product_id ) ) {
					self::attach_external_image( $product_id, $p['image'], $p['title'] );
				}
			}
		}

		return array(
			'ok'      => true,
			'message' => 'همگام‌سازی محصولات با موفقیت پایان یافت.',
			'created' => $created,
			'updated' => $updated,
			'total'   => count( $products ),
		);
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

		$result = self::sync_to_woocommerce();
		if ( $result['ok'] ) {
			wp_send_json_success( $result );
		} else {
			wp_send_json_error( $result['message'] );
		}
	}

	/**
	 * Load connections configuration from scraper's connections.json.
	 *
	 * @return array
	 */
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
		$thread['messages'][] = $customer_msg;

		// 2. Generate Master AI Reply if enabled
		$ai_reply  = self::generate_ai_support_reply( $message, $thread['name'], $settings );
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
		$ver      = '13.1.8';
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
		header( 'X-AMPHP-Storefront: bare-v13.1.8' );
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
				'version'   => '13.1.8',
				'engine'    => 'react',
				'count'     => count( $safe_products ),
				'is_admin'  => current_user_can( 'manage_options' ),
			),
		);

		$css_url = self::storefront_asset_url( 'storefront.css' );
		$js_url  = self::storefront_asset_url( 'storefront.js' );
		$ver     = '13.1.8';

		// Mark assets as printed so wp_enqueue does not double-load the bundle.
		$GLOBALS['amphp_storefront_assets_printed'] = true;

		$boot_json = wp_json_encode( $boot, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_SLASHES );
		if ( false === $boot_json ) {
			$boot_json = '{"settings":{},"products":[],"urls":{},"ajax":{},"meta":{"error":"json"}}';
		}

		ob_start();
		?>
		<!-- AMPHP Storefront v13.1.8 -->
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
		$ver  = '13.1.8';
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
									تمامی محصولات استخراج‌شده را با قیمت‌های تعدیل‌شده به صورت کالای رسمی ووکامرس در دیتابیس وردپرس درج می‌کند:
								</p>
								<div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
									<button type="button" id="btnSyncToWoo" class="button button-secondary" style="font-weight:800; border-color:#2563eb; color:#2563eb; padding:6px 20px;">
										همگام‌سازی و درج در دیتابیس ووکامرس
									</button>
									<span id="syncWooStatus" style="font-size:0.88rem; font-weight:700;"></span>
								</div>
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

			// Sync to WooCommerce Button
			$('#btnSyncToWoo').on('click', function(e){
				e.preventDefault();
				var $btn = $(this);
				var $status = $('#syncWooStatus');
				$btn.prop('disabled', true).text('در حال همگام‌سازی... ⏳');
				$status.html('<span style="color:#2563eb;">در حال انتقال محصولات به ووکامرس...</span>');

				$.ajax({
					url: ajaxurl,
					type: 'POST',
					data: {
						action: 'scraper_sync_to_woo',
						nonce: '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>'
					},
					success: function(res){
						$btn.prop('disabled', false).text('همگام‌سازی و درج در دیتابیس ووکامرس');
						if (res.success) {
							$status.html('<span style="color:#16a34a; font-weight:700;">✅ ' + res.data.message + '</span>');
						} else {
							$status.html('<span style="color:#dc2626; font-weight:700;">❌ ' + (res.data || 'خطا در همگام‌سازی') + '</span>');
						}
					},
					error: function(){
						$btn.prop('disabled', false).text('همگام‌سازی و درج در دیتابیس ووکامرس');
						$status.html('<span style="color:#dc2626; font-weight:700;">❌ خطای ارتباط با سرور.</span>');
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
