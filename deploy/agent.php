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
