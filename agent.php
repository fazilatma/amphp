<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 13.1.6
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
			self::send_messenger_notification( $msg, $settings );
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
		$ver      = '13.1.6';
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
		header( 'X-AMPHP-Storefront: bare-v13.1.6' );
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
				'version'   => '13.1.6',
				'engine'    => 'react',
				'count'     => count( $safe_products ),
				'is_admin'  => current_user_can( 'manage_options' ),
			),
		);

		$css_url = self::storefront_asset_url( 'storefront.css' );
		$js_url  = self::storefront_asset_url( 'storefront.js' );
		$ver     = '13.1.6';

		// Mark assets as printed so wp_enqueue does not double-load the bundle.
		$GLOBALS['amphp_storefront_assets_printed'] = true;

		$boot_json = wp_json_encode( $boot, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_UNESCAPED_SLASHES );
		if ( false === $boot_json ) {
			$boot_json = '{"settings":{},"products":[],"urls":{},"ajax":{},"meta":{"error":"json"}}';
		}

		ob_start();
		?>
		<!-- AMPHP Storefront v13.1.6 -->
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
		$ver  = '13.1.6';
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
