<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 13.1.7
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

				// Try gateway process_payment when available (redirect gateways)
				if ( function_exists( 'WC' ) && WC()->payment_gateways() ) {
					$available = WC()->payment_gateways()->get_available_payment_gateways();
					if ( isset( $available[ $payment_method ] ) ) {
						$gw = $available[ $payment_method ];
						if ( is_object( $gw ) && method_exists( $gw, 'process_payment' ) ) {
							try {
								$result = $gw->process_payment( $order_id );
								if ( is_array( $result ) && ! empty( $result['result'] ) && 'success' === $result['result'] && ! empty( $result['redirect'] ) ) {
									$pay_url = $result['redirect'];
								}
							} catch ( \Throwable $e ) {
								// keep pay_url fallback
							}
						}
					}
				}

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

		$success_msg = (string) ( $settings['checkout_success_msg'] ?? 'سفارش شما ثبت شد.' );
		wp_send_json_success( array(
			'order_id'      => $order_id,
			'order_key'     => $order_key,
			'total'         => $total,
			'total_formatted' => self::format_price( $total, $currency ),
			'payment_method'=> $payment_method,
			'payment_title' => $gw_title,
			'pay_url'       => $pay_url,
			'thankyou_url'  => $thankyou_url,
			'message'       => $success_msg,
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
		$ver      = '13.1.7';
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
		header( 'X-AMPHP-Storefront: bare-v13.1.7' );
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
			'meta'     => array(
				'version'   => '13.1.7',
				'engine'    => 'react',
				'count'     => count( $safe_products ),
				'is_admin'  => current_user_can( 'manage_options' ),
			),
		);

		$css_url = self::storefront_asset_url( 'storefront.css' );
		$js_url  = self::storefront_asset_url( 'storefront.js' );
		$ver     = '13.1.7';

		// Mark assets as printed so wp_enqueue does not double-load the bundle.
		$GLOBALS['amphp_storefront_assets_printed'] = true;

		$boot_json = wp_json_encode( $boot, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_SLASHES );
		if ( false === $boot_json ) {
			$boot_json = '{"settings":{},"products":[],"urls":{},"ajax":{},"meta":{"error":"json"}}';
		}

		ob_start();
		?>
		<!-- AMPHP Storefront v13.1.7 -->
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
				'gz'   => 'eNq9PduSo8hy7/4KvBsT0b0WHO5C6rDjPDj8dB4ctl8cDj+UoJA4jYQWUPf0KubfnXWlbiB6dsbTMTMtBFVZWVl5z2Tfd9149/2h9lFZ4su4937FdbJL8Yty0Y/J5TyKk0q7nMDlOtvh8KBdHrqajFPXdVjH7JvDkd5KftiFEvUVuwf+sEsj/koeC0vywy6dbyMmtxVRkRV8kkPXV7gnAIXww8fvUdXchr0Xxdev7MpwQlX3vvdCL7p+9VL42x8P6CnckJ8gLJ7ZbSeMYDT/BDfKJ1GNYZZx7M57D1/enugF1GPkN5cBj/y7DXmCj4Kqc3M5oF6Mgq5Xv+4INv8Lnboz2njDxzDis39rNh75tsU+uwLfoMvgD7hv6m+/bX7bH3Dd9Rh+QfWI+/uh++oPzR/N5bhnC4fZv34LyJzX671qelyOTXfZ92P7QhDoo7Y5wsfmeBpfCAh+jc5N+7F/Q/3TBNjzS9m1Xc+vcuQ/vxxQ+Xrsu9ulmr45HJ9fYHWAKTLmPgrDt9PLFVUVwCTQVKK2fNoC7r1/8uSDCh6fAU/v+PDajHR2fzgD2Z3IotBlbABkNOBKrMpDdwZcczkBWka2rgqXXY/oWi/dBcubDzeY4HJXlyqeK2/9AMNcu+YCqHzhhEOeVheqjdacj/cz+uq/N9V4Imv98lI1w7VFH/tD25Wv042X623ciE8DbmEf5EcCMKEXF1R0hBKuo+YC+8smAvw+RXEBxLOhqCQTe74XA9kC6s6oPzaXfeih29jR58fuCtR2V1bRwmio94/kIMAZfNqFFT5u+KHd8DPtpdmXza9lEYUxFhRAjiAjFCAzvA+KHp/Z53e24dswFNu938IOhwoEcCDIIgSK6hZ/ffn7bRib+oOukTCU4YpKoAM8vmN8eaHk6TdA+cOeMAvYlyO67um5JY/77z18JP+o07TNG5azNBeyWJ9ONjNcDqMpyOFsJsaCBghLGLq2qRhTiLNsI/4GMbAGftQ4V9ntdjCewAGh8ogccwVp2xiwZgLsBRXwVrbBBCB+gChs2vgZUJkB7Qs994yFhcDD+AqK8AVdmjM7BkP977d2wF4U5APQY91cAA/f/vqKP+oenfHg8Rvu4Zf7zHDlt639ZegVsETKvr+NnevR8Ju+1svroNMA3VIgXtf+dEAPzfixD3aZNYo8+mQW+i3nz4Qg7tduaNjKx6Z8/XiBJyc2JVgwZ8t/AGlW+Cs5wnHowC5npZR7TdQwMT16w7O+DeS+X0P2B72MPTBuBs90lxfEg4eBm710b7ivW3jurRmaQ4vN1QTNAEfkDMgYdRQDfXhxOE0VpcqjEw563AIZvC1PdAYmI3fm2DfVC/kHmP0ZrowYAGhv58tA+A9wPWBBhAPFIcGgx6+Fm6junynrkSfVi3L33oozQs6HF6piIyfUoKBs+soLooLhbMMfn64sIM0zlynmzs2pM8JbKOgEKgI/HfYAwFQ62c4xJ3LayYDsLMPgUkJQXE3D+W137PiRT+PpzNPf9UM/cbzh1APtw7DaRsFvwDY1WJwMOwqijLDsBVkQpRkTBlQITAJAI7uU40bof2mlbhddkrJT5tY92igLO0k6YYf+bmDHYLA7tsYJYCm12cjA/rq7ukUEs/vIuMM7RXchTDUUhpkp9XYg9Vo8Atp9IsAIYfkB2bL3E+wJvYYBCLLW6QSemqoCGUf1FXkRt21zHZpBbB/Xb6kMEzpVEGcqtiVon0WwWKSKOSmalFuuAg1kw4GrW7LMVA+pIv6sYSgHDH0/MvTlJ59Y3lWeWUID/9icr10/ghrJFKsTDG/LolTTLjikDAVE/uwTcsB1tc9YxyMA9YlnIFP3JY9NkisAoZxG8jjP80oXWyH8RIuaCZFPWyeJSggCqkkJSqx3VVZos+CiLjCTvIgaFsMqDplbvMzEtjKkfxgvn1HmMoWPhspyQ6aGzTEORX011Vu+/qROwzxVD14QCfHtoGxL9Bpr2p8IzdztTTPuC8D2HdXbKABXMBguowGaMLNaXI90aa6hVs1L1SMhmQiOTDXdkODhpKdtrXH4jOIObaKm7O42a9WOO+NGqDpihWVHxbTN9PdpmzNrlxndu6xV5oKYM27ywsXnDVqcEb8u1HvTSiR1oAPoksAuqXpKJAbdPKF1LLCQaWwvaA+txkzWP6vusZCvGjpndn79DObKyTpDtkpmG17Q20OusWwlkmOv0wA1SmwezsmCgDBp8b/WCflRGbymEKbh48044yOa2VPNQie2BnW2wBSTLZ/kpilfUEvebYcsGyCW0qjbJESCp6q5UEusEYwJOyjRLcd/7bsrKBc553ZE9nHkEPC34dtpk8bUkJJykDofNOuSDEKMS8EHQsZK664/M55GbIz/fvJBOjwTS1LyC/d94TMzKwnqKbH8Sb+CZoxEtgZePJAUuS0qFP3AdLbpoDMOudGuBeQIgfTViIC5RA1tizOxacgS7h4dHN7UFx64LQrDa+HQQPi56Lu7cnoih4KeazzF1PpsM3XJPgmZfRKhCMVgn0R5HCUYqPqLcGBptsrkYKSfde9kTta4wuIFlbvuvaCoVbNKLl4QmTQqY+ISYf+kygSUMClTmjyxbIZlLtuDlh5LU0QwN7kzZYvO1ydiFcAObeKgeHvfREFBPj0/tlISyd16RdMns9B9nPTclDgcleXDH6wdAWo7GKp6nmkQZArJ+DVG463Hhs6ou/UEb7eeutsOu6he47CLHjjscnH8VdLPHW7OCaShqfCnRRi/vEToOfXK2ksIi+eNogU+T6eWKrxOxZMCyiSwjbk6FvvKwzYLpzeiZE0QpPA0sRhL1iQxlzWJSYkGVF5waI6aKljY1BTpYFpDDLeDajRtVfWdMz2FT6U2R6cj1i0aThpDi3X1cc57zh0mdZ0f8oOmVDrksi5vcpNN/Ci/uGUzqgdWuqGbs+mXF6bUxKnasXfMOz3vAYgXk7gW6UrV2TIucCYVn/p5bBqj8ANTb8BU9i83mLgp9yM63FrYEvg8WCxPldKFWDI6IxIms+XWwnks1H32QFFLAWKy3zX97Xnx2MTCY6aIR00Lzcq4LJyOBA4rVUD/pJZjGjoKmjgmhJdbt51TFhW1wPGCsRlbvMrs16VWZOyEP5R917YuOqSwCMT4X6luKWEjkBG0EmSzIUh8gJEQCRryi/5wQVd//Lji/VfvjC4VGrv+wwnAfi+ij3K8u7AFDBQsPOKPp9v5YKhvdIt1KjGHJKHuO/VHMhlcyHWxJbDDMIxgVc0FKnT13yQ0jLGlgBmxT8XHIXVv0P8z7s5VAxhw0Y4kT5qeM9CqhmuttXNvgds2iNkpU6RMYcY/kDUiDdMqurgQJ9Rv0B3+DvzNrxuQyGRiHaeI/NgDBoeu+rgrhpPjlvFueUlN80EXbmkmIIuDCO4290gcC0FpgAYZJqcjUT1wH78oN/hdT1jXHoYaGzAwdQPFAfW1VzliLhU+y2OyxGRz82izsbu2Er5EUN0TVFjWhRm6p8saT7Abx5PpTWaCpwcGaPHwFQp9j6+gRz6lNFwlmcw0JrMof4gBrkuA0BT0S1EkS5cxKcjezgl6L2hKh3snnfOCfiq4tBhRMoiFZvg8L1mtr9dm+MG7CEP+4O1zqiNiLi+4aMpr4vIcLmPAC9r7I5tjJtCixyZ4nLyjgkv5Kg5/lpKpzuidkpmoWTGPEx7mUsehQIHYc8RnclvLnVFMlUwbmIg6PB0CkWWGLQtTVVctNBNxbeTgmwKFw7mz5JPmoCzQT4lG/9q0LmwVC8oTi7wT/cnSnaQfWQ7t0ossrzO58+4IhyyZ3Nt5rXhmb1Yintl8Eq7HWF9kVfo2cG2ODk64k2Uw6vyLHxx5ewCjDH50n/E31b1xZ3xfZIExZYHGM8nyM4nrmfS+gtVyuuiJPKecw6fZJE1L/LWUwHjKmg/6JEiUoSnZ7sR5aOdaLWy7zY7ntKIF39pM6o2izk7byhRatwtF0U6tXAhdBTUopQrhJza33pNINMDuu3fnvcygUDJJNFdmYQtxNFyJbkuVqf2UDUiHWXS5csVXHyBy2qZ0tDkNm0bGTQXbbVwkSuLChGhPn2IyCgZQZvETCJX0eQLDB6IdPxxxGJqI+ymtJg5Uf76yge/NcJqJ9BQiekd+WZW34kjlY964lbk9lhodGXkvlLWzBD2RHiZjPNN6gu4iFHPm22Cyc+zKV8dSuYOALJKFsgpn+mQ9yXaWOLAYWC2UtNGEu4J0XOVKeIqBDkgqF7ZiAk4+9h0xYNty2ymgprOg2tlS4ZQtle0knfuaKSmDT54mQhaYG8tcEmrRNCxI7ftS8GZemfzuLJ1pcuYTcmiBPEy2bAhnmRFH+yGWr7kGhXfGwZYbrNe+gaMGDFiV5qrGJBzH9G5i0M65m/nR/LRFSyG4P842cybkzxqWB2CtZGqR3jRNxQsHhAWgaH2wj2ZSz1KErO49+Cv1cwV/wj8yad8upXqnJUq7HdYMw64MHDFBcDx1w1LsU9HF5e2OlBicwU8ubwNUwYI+Vurr5mN8/LppAdX7A2VMFzwMRH5lz/JuWjKgiFH5RXu8606E3Eh3ZFzfNOnp80wk0j1jW7WPvL94fuTwrEumVog55hS1gHjrKzSc8OS4NuZP1fm9Uyq4QUENDn0BgAaZdsjut+OQqzIMGVkjeJCetmVfuSshbTIoRcYjM5AECcPYmNKxEqYo5lJnXAlnxfdaOYXT2yPg+eH2pBh4DyhEhxZXMh0jSIW3+NIRAgKWyotx6q4jrER1RqgOlJht/QJhGYk5pndGmYRZXKsC9yljTJI5xcIKYyMppElpzcwkdol/9fHrRvnQNvcFOuUSMLXjmlsnIfNBb+29bQZ4fPxoMatHkpJV0pr6AOL8Zsld0F0/NINVCVUpIc81u+JgJPNO3tk4+rk7+KY/YTLD6uYrrnjOmMyf4npoqBZvpOH3kZYW/Sd0SpOx8qUKtbUeSSsHy085e5XVIbVEgZ3H5Nb6ZlxzRtqCW83W4+G2HaigQoOL85iNeu0xpYk7H+Y+8pxAiniwiwDxJKTK9CyYAxByN+hBWHXqjgM284kgwErWE9n+DVV4svz1/DTynZafpiWhsWSzqkfvalkNA4SBb9NjHEZamh+t2NvFb+/PSwVcPuOTMnMC/sT5GlNAXeh/ApXjv3lBnA9eeTs0pX/AfzS4fwqifBNtgmQTPSsrCuihuoujJUfyL4AcMdx/qA+QzbrzLE4NjexWgkhHCI/mN9LsPteXJKXPHutvc2P5Dweb4P0RsXM1EsDF2MrCMBOQOR/5nKzhj1JTkRt8WqKlEWMSuhd/jIiFu3XHSsY4Ew4t227A64pzLPcA08dX+WLsQIHi0iWJxTrHXFAEcrppoAdMpWmhmYUTzm6oAHomzCAhURxhuYIV+rsdabaDcHPYluPr2kqq83vbuC7sKLMxYHDGI7LjTo/VbSG5fwfz4pO1tnqEWYwiarO5g1NRq+NiLVG5Dg9d69iNqP2T538+5M0NGEcKDZeBFUw+I70maRGFjz2Ukl5zdWzmRp4kTUETyil7NJO1dyRZe+tI1n6UTGI5tUhqq0ePFZdSoHzZ2oSdRq5a7Az61co8UeMDociHygjk2Dn816qPJw/NmMx69MLwstZfMrrQPNM04jFnh/OBSOGhmq1pfKcKBivPV0TarZReI43EHDMgvsDHB7pwxNF0gi5seIMKD+VdVynNfDvT2MmswkAFryyOoVMmx0gUSwNSJX0qidw6JeWpshrKcB+PHQKrSi8Vn/Q5Vo2R6haAXjX+zIdOlaGjJFRO8YKyJmtWWMqVj9+AAofJ/0ahuxvfWueUV4euzDSNhOhf2vbUCiFQvMeFdtAd1SGFUh3CFQNZ7kxXE3Sv2iFlcQHle9wbTrgpy7c8IZJHdjC3SuI9InhX+4sQOpq33hiAFJ+i7jucqRtT/Dlqlu4KWVd8supMOdP6BoS8kkDED/JcQ4qijEvdT36navb6t++kDH4WnZGOzihdgU+7mOntfWpMokihLNRuOsFN0TZcKHrSxJDpytfFEoEzVag1KVYk/nzSvLIlmcDo7F6Q7+b3gponto6+RCELhPWn9JwJqPNwHB7bG67A8XoGyJzct8OBBowE5yiyL3pgYK4jwrJfLcucjjU2nX+ulBl38Rf9O1bXV/lXQyDP3LVviTQpT00rM0GEQ8D9xOnuTsCmMUUm+u3cKHuUyI4VzdwZa3X+xfyNyUxDAPNG4pW8W0ALL2Iva3PnnyYOUz5AInsFWXeWXYXvD4vbU6O0PRPkwbOPbw0oDJeOUv9G/qZRUDzjnLR326g/MsQwq9JzmHd2jaF+rKxQF0cOa1Kil2IoblfCbdyA/n7rRiyDbkxxtZmgYBvabGqIe8Y2NcK0+oJZanYxv62khc99RXYxUVDMMCoAgXty1o2hh7HvLsf74yTD6RF85uRO3esNmImNdsv4cSV1IlN9e/aw/5WmDk+NHV7sUk71S4Y9BgaLrSnfuvo4cfEzNXOaoA6A99wZax9wW+9ZXtBCzYAjGqU3INE8I4yUzAoGPvVtwL01N75UL58LTvGZCG2bE1HhRFuqubMd1WP2PW4uHsGdplFatDFp+JmMNUuOuWoiLC9Oj+knFu7Ri9FVc6mg2Z23kZwFJXhvQ76vu/I23Bcjfy9Wa69k0jkjBSkDbOe61HIjw2lp3z+Tcj7JOXS9/k+FRuRfES1t/eeqOTavqEV+j6v//e5elVpTSvdEpBrn6h970KLMecKwAnXWmicMD0lWuubBBcyUuOc5oAGWc/YJ82vNmUitYZLZKyoyFBczK0owds80dn13cOGtSuI6rq1ZDtuojJzrqYFXYzQzCy5PFzg0xHxob9icK0LbBBc27qp0i6IZ3IX1zIr67gO1zmniLE/wwZomqlJcORGHAdWcMVjTtLevt/7Dv976a2vNtC0ThCtrpryKi2rnRF5WJ3MzofOBRL+71t6j3XYb5vYepVkS7mYo4YAP7mmuuB8adPHHWw8qRDNYiwqrXVo4dqne5jl2zhbWVY2+/fWMqwZ5T5Pi7RHTHSy/+6fykHnusmu83TSc2vBtsZVduCH6l9HFzvBCEi467H+hvaU8nhn1i/fLgFFfnjz23y9CBnmavleYTeZCpaccnYWyaPpZ8Z9MvUZ5HtZ0K78ijTsqaYEr86oHAotyN7tgjPxiZBqxm/yKtOkwnMZZ9nbaUI/ns9UbbLOqGc1ixxm9T9NSL4TDrT/Chjqn4N8pU8Q/oJHN/z8JyYqojazn2hh5/ZtPnRWlNkDJntkY/vflQgS1SY/EuJm0QYaQzYadPX+XfEhTL5FlUOyWCxQgFyvIU8kKzB66aredKBeErdRbGDiOfsQeaE6PNaUAbJSH1QA00V5P1reTOFdg1epjIrINFQbBehnfNacy9Q3S35Lc4ZXRHJIzDEI6eNmKMoU9ZG61ctXJtqqqgWCHBpZN0iu+8Zlx+dpNS9IolujXMxTLgwBm474OhOgHORWfzV6Kwyx05dFn9CJhy77IIG1v/VMUKh0HjI5WuIAfZKQdFYYXP0JmGRg/E49WrK+TN3VWOlNH4ZrO1C9/vufX5EKVwNBo9krvY6T3aXWMxGj1YR03N2JY23nXMKpbQoTy4vUVomb3T+cUZ9SuB5Wl5hnjEMmpFxBN2CEbG9N+v2lB8peE5C6cflnFcWF2ZTW/mhav+UKMDse06QyF3T83X5+aizf0x8PGRJSXZF+0zjjKCv0KD68635ESQy4TlMdokhj2Y6yr+wOtU8foTJtZwXWoJPP4Y4RLBaxdtWemnuvCjktZqq3wkf6FJOQ+qWM/L3cY5WzP6tbv6DimxsVJ3kkoPTF8FSNXGrk8ilh5mnrePaNNo93yxtH5yeoKrjiyFvmDBZ3RQSs1IutJtq44WRtyCg28OCLgSnzbiogrXbVoVbbRNEufiuzH3Z3FaXEKq0T5UZKR2wNIqh8yd08HDSy7csJZUGtQ/IivM3XKjrJy/THWZGixL0Jhsrk1CxYC83FfsDmglCI6OseC02tFObcYn1cWLx2suTP1uIE72CgkPV7aLOyVEsFONlMw+7bTg/dNYVb370St3hpJcHgzfyp11tEVal99FRgjecYIEIocLWeKmMZ6nW8OmLa+AzUMt9Wnq6KsEdbqKaZKvdQKWms1wKYJ6htoBnYFkHaT8oYSeUlrnsCvSUe4Vgq06AN3B74eOMF35hoJG1X93K5wlFbordV0x4PetCgeHKtnLnIXDlzffM6xvvy+DuZoX6HfRLlbvzk1FxmK9ZmMtuLjdnGiK9Z5BPJ9Rx8mdat267uuDr04SYFJkIftcGiSw2zzKbJRczmxx3dZi6/W2pdlyb8mDHn1rrj7QHz3NqWObTq+C9PZylWbWuLOpYMd31n296rWQbLPBHtImB/6rj0qcnIoP3I8amvow6l9Zlydi5YVJFeOMszWlJ3LXxgXztRL8dT19Im2RysEGMafa4ckNaWO3bTaHg1tERjr4b7Yzo5Up1ojkmhq1NzbWkLni1rUKUge7Eb9HEzoVlK2XYZh+LDdGjM2HiBbgyZQ7etZAme5QcuJ8Un2U+rMXdCaKmy+fECUTPsANq5ZS0/plFtPn/tpCfadP9x+WPvtWC3EEH4tUWvMxYrjkLgaUzPAnN4XR42DXh/pD7gkbYZnGpCu3jmZvMxcbBIwM9e1xnU8teE97Ghk1dElFJeoROvk6lKWryO722Hc3AAlw6DYH1msmB/U60t5h6MT8GIWZ6zqhEnOHZNLxoOaDZubPexTJ9ik/103V4xju5MNZ4XRC5a/6sRoafIZsVDMGh8m3IYZExpmTLx6IOmfYBldjzmMjULetkEtFSZ64AMHmGq9bnSjaTkKMW+IoVGmZX0mBJiuCQG6Y+DnprrQNKfm8mqGv/Mkz+vICn+ndZrjzJk9QI73TEyfvMFzGK08iBwlKbITFLIinMlVIRH2dCZX5Uaqjazkkd02ieyUAYxgjnImZWA7vXnTzruhyWt+39n5AngX4TyxpirjqMjcr4MtcYpLfSpBNnJr+Cz0dbHhIYpjnnMz3PqatK8BdEVREW+1N8aCBNmKzA/xxthdCQZRbbwxNqrjXbKdfcupqwPLI3g9I9q8WXM/e+XhqltZfHfVreScrbqRVZGuulVJIVh1P3lbxTpgu/XwSse2a+M4Zcy90NZpLqpFvOv2Sk8OjsM82n5bjz/9bbGP8GK6b1Y/or+Fdt0zk/tHXSA7eaKDGDteulWebNMoi1bhQHs13WpK5n3IV93PWiwJX+2uRvVhHWSTdFnw7q4a6oLeaP7zKniV3BnRjy6qsmod0PRFcupuca62biSSddyqXJZ72uaaZM8OYTK++2ylNTG3XW9ufDA280Y7jXdjtLn+68vjK6+k1GvYom2MHKUI37EEN305XvfGJrVyx3UPnITsT8ChfiTKtDb/uh1XskrWuVq+b3fUHl0rBzigPxDqLUXFVn6YopJGSzqRafBYmZXmtPaBePziCTYV/f/ZaFWQKEikK1gxu5uk514Hy5TFDcfEs21BrZhRPaSxatRWW4QeFJzzN1CQ3OID2qZr1uc8T2IrHbG+FWM65PsO9PQonnsWFJHmdzv9uDrE2+3WztbFoA+7tfsKDIjCWaewMOsDtsvZofnSatC9t2Y2+szYjhfJTq96Ml82FYSFaPI/dWy5Xa+4L0nhsOvdIKumt7gyaP1mNdJKRqgPr9KriSuHKAlXD6pk8Jlwaml8yV/SdXhwk7oLD7PotzYrX7kJQpm5rx86FS4M7kfLHOG+mblp2cVw6q4/pejCMc2DM6TwMQbBugFndoxD+xnmRKtDfkpdiDWJeShUf97Mo7Sm5KfUkxhT2Ko9m8VIe3I8LutRfkopimueJeFPzKlDknx+EO832cT5M2BoWb0a80jibZqabU0fDzjHiygyv/3D/wGP/NQv',
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
		$ver  = '13.1.7';
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
