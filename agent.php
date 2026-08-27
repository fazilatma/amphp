<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 12.5.0
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
			// Storefront
			'enable_shop_takeover'        => true,
			'replace_site_header'         => true,
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
			'show_special_badge'          => true,
			'free_shipping_threshold'     => 400000,

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
			'chat_field_phone_required'   => true,
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

		// AJAX actions for syncing to WooCommerce
		add_action( 'wp_ajax_scraper_sync_to_woo', array( __CLASS__, 'ajax_sync_to_woo' ) );

		// Support chat AJAX endpoints (Customer & AI thread)
		add_action( 'wp_ajax_submit_support_chat', array( __CLASS__, 'ajax_submit_support_chat' ) );
		add_action( 'wp_ajax_nopriv_submit_support_chat', array( __CLASS__, 'ajax_submit_support_chat' ) );
		add_action( 'wp_ajax_scraper_customer_get_thread', array( __CLASS__, 'ajax_customer_get_thread' ) );
		add_action( 'wp_ajax_nopriv_scraper_customer_get_thread', array( __CLASS__, 'ajax_customer_get_thread' ) );

		// Admin Live Chat Reply Desk endpoints
		add_action( 'wp_ajax_scraper_admin_send_chat_reply', array( __CLASS__, 'ajax_admin_send_chat_reply' ) );
		add_action( 'wp_ajax_scraper_admin_get_thread', array( __CLASS__, 'ajax_admin_get_thread' ) );
		add_action( 'wp_ajax_scraper_admin_delete_thread', array( __CLASS__, 'ajax_admin_delete_thread' ) );

		// Messenger test
		add_action( 'wp_ajax_test_support_messengers', array( __CLASS__, 'ajax_test_support_messengers' ) );

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

		// Filters for WooCommerce Cart & Checkout guarantees
		add_action( 'woocommerce_before_calculate_totals', array( __CLASS__, 'fix_cart_item_prices' ), 20, 1 );
		add_filter( 'woocommerce_cart_item_thumbnail', array( __CLASS__, 'filter_cart_item_thumbnail' ), 10, 3 );
		add_filter( 'woocommerce_is_purchasable', '__return_true', 999 );
		add_filter( 'woocommerce_product_is_in_stock', '__return_true', 999 );

		// Enqueue scripts & styles for storefront
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_front_assets' ) );
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
			$s = self::persian_to_english_digits( (string) $prod );
			$s = str_replace( array( ',', '،', '٫', ' ' ), '', $s );
			$c = preg_replace( '/[^\d.]/', '', $s );
			return ! empty( $c ) ? (float) $c : 0;
		}

		$candidates = array(
			'final_price',
			'price',
			'primary_price',
			'display_price',
			'new_price',
			'regular_price',
			'price_val',
			'price_min',
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
				$s = self::persian_to_english_digits( (string) $val );
				$s = str_replace( array( ',', '،', '٫', ' ' ), '', $s );
				$c = preg_replace( '/[^\d.]/', '', $s );
				if ( is_numeric( $c ) && (float) $c > 0 ) {
					$num = (float) $c;
					$unit = strtolower( (string) ( $prod['price_unit'] ?? $prod['unit'] ?? '' ) );
					if ( ( 'rial' === $unit || 'irr' === $unit ) && $num > 1000 ) {
						$num = $num / 10;
					}
					return $num;
				}
			}
		}

		// Check price_rial
		if ( ! empty( $prod['price_rial'] ) ) {
			$s = self::persian_to_english_digits( (string) $prod['price_rial'] );
			$s = str_replace( array( ',', '،', '٫', ' ' ), '', $s );
			$c = preg_replace( '/[^\d.]/', '', $s );
			if ( is_numeric( $c ) && (float) $c > 0 ) {
				return (float) $c / 10;
			}
		}

		// Check variation_prices
		if ( ! empty( $prod['variation_prices'] ) && is_array( $prod['variation_prices'] ) ) {
			foreach ( $prod['variation_prices'] as $vp ) {
				$raw_vp = is_array( $vp ) ? ( $vp['price'] ?? reset( $vp ) ) : $vp;
				$s = self::persian_to_english_digits( (string) $raw_vp );
				$s = str_replace( array( ',', '،', '٫', ' ' ), '', $s );
				$c = preg_replace( '/[^\d.]/', '', $s );
				if ( is_numeric( $c ) && (float) $c > 0 ) {
					return (float) $c;
				}
			}
		}

		return 0;
	}

	/**
	 * Price Adjustment Engine.
	 * Calculates adjusted price, supports fallback base price, avoids "تماس بگیرید" unless desired.
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
		$fallback = (float) ( $settings['default_fallback_price'] ?? 150000 );
		$behavior = (string) ( $settings['fallback_price_behavior'] ?? 'use_fallback' );
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
					'has_price'  => false,
					'original'   => 0,
					'adjusted'   => 0,
					'formatted'  => 'تماس بگیرید',
					'markup_pct' => $markup_pct,
				);
			}
		}

		// Calculate adjusted price
		$adjusted = $original * ( 1 + ( $markup_pct / 100 ) ) + $fixed_add;

		// Apply rounding
		if ( '1000' === $rounding && $adjusted > 1000 ) {
			$adjusted = round( $adjusted / 1000 ) * 1000;
		} elseif ( '10000' === $rounding && $adjusted > 10000 ) {
			$adjusted = round( $adjusted / 10000 ) * 10000;
		} else {
			$adjusted = round( $adjusted );
		}

		$formatted = self::to_fa_num( number_format( $adjusted ) ) . ' ' . $currency;

		return array(
			'has_price'              => true,
			'has_valid_source_price' => $has_valid_source_price,
			'original'               => $original,
			'adjusted'               => $adjusted,
			'formatted'              => $formatted,
			'markup_pct'             => $markup_pct,
		);
	}

	/**
	 * Get summary list of profiles from scraper4 profiles.json (used internally for admin sync).
	 *
	 * @return array
	 */
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
	public static function get_all_scraped_products() {
		$products = array();
		$settings = self::get_settings();
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
								'id'              => $hash,
								'title'           => $title,
								'has_price'       => $price_calc['has_price'],
								'original_price'  => $price_calc['original'],
								'price'           => $price_calc['adjusted'],
								'price_formatted' => $price_calc['formatted'],
								'image'           => $img,
								'gallery'         => $gallery,
								'category'        => $cat,
								'description'     => $desc,
								'in_stock'        => true,
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
									'id'              => $hash,
									'title'           => $title,
									'has_price'       => $price_calc['has_price'],
									'original_price'  => $price_calc['original'],
									'price'           => $price_calc['adjusted'],
									'price_formatted' => $price_calc['formatted'],
									'image'           => $img,
									'gallery'         => $gallery,
									'category'        => $prod['category'] ?? $prod['cat'] ?? 'عمومی',
									'description'     => $prod['description'] ?? $prod['desc'] ?? '',
									'in_stock'        => true,
								);
							}
						}
					}
				}
			}
		}

		return $products;
	}

	/**
	 * Sync extracted products directly into WooCommerce database.
	 *
	 * @return array
	 */
	public static function sync_to_woocommerce() {
		$settings = self::get_settings();
		$products = self::get_all_scraped_products();
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
				'timeout'     => 15,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking'    => true,
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
		$threads = get_option( 'scraper_chat_threads', array() );
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
	}

	/**
	 * AJAX endpoint for customer support chat submission.
	 * Supports continuous messenger thread, AI automatic answers, and persistent conversation.
	 */
	public static function ajax_submit_support_chat() {
		check_ajax_referer( 'scraper_support_chat_nonce', 'nonce' );
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

		// Validate required fields based on admin settings
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

		if ( ! empty( $settings['chat_field_subject_enable'] ) && ! empty( $settings['chat_field_subject_required'] ) && empty( $subject ) ) {
			wp_send_json_error( 'لطفاً موضوع پیام خود را وارد نمایید.' );
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
		$customer_msg = array(
			'id'          => 'msg_' . $now_time . '_' . rand( 100, 999 ),
			'sender'      => 'customer',
			'sender_name' => ( ! empty( $thread['name'] ) && 'کاربر مهمان' !== $thread['name'] ) ? $thread['name'] : 'شما',
			'text'        => $message,
			'time'        => $time_str,
			'timestamp'   => $now_time,
		);
		$thread['messages'][] = $customer_msg;

		// 2. Generate AI Reply if enabled
		$ai_reply = self::generate_ai_support_reply( $message, $thread['name'], $settings );
		if ( ! empty( $ai_reply ) ) {
			$ai_msg = array(
				'id'          => 'msg_' . ( $now_time + 1 ) . '_' . rand( 100, 999 ),
				'sender'      => 'ai',
				'sender_name' => $settings['ai_support_name'] ?? 'پشتیبان هوشمند فروشگاه',
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

		// Forward notification to messengers
		$site_name      = get_bloginfo( 'name' );
		$formatted_text = "💬 پیام جدید از چت آنلاین فروشگاه

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
		if ( ! empty( $thread['subject'] ) ) {
			$formatted_text .= "📌 موضوع: {$thread['subject']}
";
		}
		$formatted_text .= "🕒 زمان: {$full_time}
"
			. "🏢 فروشگاه: {$site_name}

"
			. "📝 متن پیام مشتری:
{$message}
";

		if ( ! empty( $ai_reply ) ) {
			$formatted_text .= "
🤖 پاسخ اولیه هوش مصنوعی:
«{$ai_reply}»

"
				. "⚡ وضعیت: پاسخ هوشمند فوراً به مشتری داده شد. جهت پاسخ مستقیم انسانی، به پیشخوان وردپرس > تنظیمات اسکرپر > تب ۳ (میز پاسخگویی) مراجعه فرمایید.";
		} else {
			$formatted_text .= "
⚡ وضعیت: در انتظار پاسخ مستقیم ادمین در پیشخوان وردپرس (تب ۳ میز پاسخگویی)";
		}

		$send_result = self::send_message_to_messengers( $formatted_text );

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
			'thread'     => $thread,
			'status'     => $send_result,
		) );
	}

	/**
	 * AJAX endpoint for customer live polling their chat thread.
	 */
	public static function ajax_customer_get_thread() {
		check_ajax_referer( 'scraper_support_chat_nonce', 'nonce' );
		$session_id = sanitize_text_field( $_POST['session_id'] ?? '' );
		if ( empty( $session_id ) ) {
			wp_send_json_error( 'شناسه جلسه ارسال نشده است.' );
		}

		$threads = self::get_chat_threads();
		$thread  = null;
		foreach ( $threads as $t ) {
			if ( ( $t['session_id'] ?? '' ) === $session_id ) {
				$thread = $t;
				break;
			}
		}

		if ( ! $thread ) {
			wp_send_json_success( array(
				'found'    => false,
				'messages' => array(),
			) );
		}

		wp_send_json_success( array(
			'found'     => true,
			'thread_id' => $thread['id'] ?? '',
			'status'    => $thread['status'] ?? 'pending',
			'customer'  => array(
				'name'  => $thread['name'] ?? '',
				'phone' => $thread['phone'] ?? '',
				'email' => $thread['email'] ?? '',
			),
			'messages'  => $thread['messages'] ?? array(),
		) );
	}

	/**
	 * AJAX endpoint for Admin sending a live reply to a customer's chat thread.
	 */
	public static function ajax_admin_send_chat_reply() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'دسترسی غیرمجاز.' );
		}

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

		wp_send_json_success( array(
			'message' => 'پاسخ شما با موفقیت برای مشتری ارسال شد و در چت او نمایش داده خواهد شد.',
			'thread'  => $threads[ $key ],
		) );
	}

	/**
	 * AJAX endpoint for admin retrieving all threads or a specific thread.
	 */
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
	public static function maybe_takeover_shop_template( $template ) {
		$settings = self::get_settings();
		if ( empty( $settings['enable_shop_takeover'] ) ) {
			return $template;
		}

		if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_taxonomy' ) && is_product_taxonomy() ) ) {
			self::render_standalone_shop_page();
			exit;
		}

		return $template;
	}

	/**
	 * Enqueue front-end assets.
	 */
	public static function enqueue_front_assets() {
		wp_register_style( 'vazirmatn-font', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css', array(), '33.003' );
		wp_enqueue_style( 'vazirmatn-font' );
	}

	/**
	 * Render Standalone Modern Shop Page.
	 */
	public static function render_standalone_shop_page() {
		$settings = self::get_settings();
		get_header();

		// Suppress legacy theme header if replace_site_header is enabled
		if ( ! empty( $settings['replace_site_header'] ) ) {
			echo '<style>
				header.site-header, #masthead, .header-wrap, .main-header, .site-top-bar, .entry-header { display: none !important; }
				body { background-color: #f8fafc !important; }
			</style>';
		}

		echo '<div class="scraper-shop-fullscreen-wrap" style="width:100%; max-width:1440px; margin:0 auto; padding:0 15px 40px;">';
		echo self::render_shop_shortcode();
		echo '</div>';

		get_footer();
	}

	/**
	 * Shortcode [scraped_shop] / [modern_shop] HTML Renderer.
	 * 100% customer-facing, ultra-modern luxury e-commerce experience.
	 */
	public static function render_shop_shortcode() {
		$settings = self::get_settings();
		$products = self::get_all_scraped_products();

		// Unique categories
		$categories = array();
		foreach ( $products as $p ) {
			$cat = ! empty( $p['category'] ) ? $p['category'] : 'عمومی';
			$categories[ $cat ] = ( $categories[ $cat ] ?? 0 ) + 1;
		}

		$account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
		$scraper_admin_url = admin_url( 'admin.php?page=scraper-full-dashboard' );

		ob_start();
		?>
		<!-- Modern Online Storefront -->
		<style>
			:root {
				--sp-accent: <?php echo esc_attr( $settings['accent_color'] ); ?>;
				--sp-accent-rgb: 37, 99, 235;
				--sp-accent-hover: #1d4ed8;
				--sp-bg-card: #ffffff;
				--sp-border: #e2e8f0;
				--sp-text: #0f172a;
				--sp-muted: #64748b;
				--sp-radius: 18px;
				--sp-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.06);
			}
			.modern-shop-root {
				font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Vazirmatn", "IRANSans", sans-serif;
				direction: rtl;
				text-align: right;
				margin: 15px auto 80px;
				color: var(--sp-text);
				width: 100%;
				box-sizing: border-box;
				-webkit-font-smoothing: antialiased;
			}
			.modern-shop-root * {
				box-sizing: border-box;
			}

			/* Modern Top Announcement Bar */
			.store-topbar {
				background: linear-gradient(90deg, #0f172a 0%, #1e293b 100%);
				color: #e2e8f0;
				padding: 9px 20px;
				border-radius: 12px;
				margin-bottom: 12px;
				display: flex;
				justify-content: space-between;
				align-items: center;
				font-size: 0.85rem;
				box-shadow: 0 4px 12px rgba(0,0,0,0.06);
			}
			.store-topbar-notice {
				display: flex;
				align-items: center;
				gap: 10px;
			}
			.store-badge-live {
				background: #10b981;
				color: #fff;
				padding: 2px 9px;
				border-radius: 20px;
				font-size: 0.75rem;
				font-weight: 700;
				display: inline-flex;
				align-items: center;
				gap: 4px;
			}
			.store-topbar-links {
				display: flex;
				align-items: center;
				gap: 18px;
			}
			.store-topbar-link {
				color: #cbd5e1;
				text-decoration: none;
				display: flex;
				align-items: center;
				gap: 5px;
				transition: color 0.2s;
			}
			.store-topbar-link:hover {
				color: #60a5fa;
			}

			/* Luxury Main Store Header */
			.store-main-header {
				background: #ffffff;
				border: 1px solid var(--sp-border);
				border-radius: var(--sp-radius);
				padding: 16px 24px;
				margin-bottom: 15px;
				box-shadow: var(--sp-shadow);
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 20px;
				flex-wrap: wrap;
				position: relative;
			}
			.store-brand {
				display: flex;
				align-items: center;
				gap: 12px;
				text-decoration: none;
			}
			.store-brand-logo {
				width: 48px;
				height: 48px;
				border-radius: 14px;
				background: linear-gradient(135deg, var(--sp-accent) 0%, #7c3aed 100%);
				display: flex;
				align-items: center;
				justify-content: center;
				color: #fff;
				box-shadow: 0 6px 16px rgba(37,99,235,0.3);
			}
			.store-brand-logo svg {
				width: 26px;
				height: 26px;
				fill: currentColor;
			}
			.store-brand-info h2 {
				margin: 0;
				font-size: 1.35rem;
				font-weight: 900;
				color: #0f172a;
				line-height: 1.2;
			}
			.store-brand-info span {
				font-size: 0.8rem;
				color: var(--sp-muted);
				font-weight: 500;
			}

			/* Live Header Search */
			.store-header-search {
				flex: 1;
				max-width: 480px;
				position: relative;
			}
			.store-header-search input {
				width: 100%;
				padding: 12px 46px 12px 36px;
				border: 1.5px solid #cbd5e1;
				border-radius: 30px;
				font-size: 0.92rem;
				background: #f8fafc;
				transition: all 0.25s ease;
				outline: none;
				font-family: inherit;
			}
			.store-header-search input:focus {
				border-color: var(--sp-accent);
				background: #fff;
				box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
			}
			.store-header-search svg.search-icon {
				position: absolute;
				right: 16px;
				top: 50%;
				transform: translateY(-50%);
				width: 20px;
				height: 20px;
				fill: #94a3b8;
				pointer-events: none;
			}
			.search-clear-btn {
				position: absolute;
				left: 14px;
				top: 50%;
				transform: translateY(-50%);
				width: 20px;
				height: 20px;
				border-radius: 50%;
				background: #cbd5e1;
				color: #475569;
				display: none;
				align-items: center;
				justify-content: center;
				font-size: 0.75rem;
				cursor: pointer;
				user-select: none;
			}
			.search-clear-btn.active {
				display: flex;
			}

			/* Header Actions Area */
			.store-header-actions {
				display: flex;
				align-items: center;
				gap: 12px;
			}
			.btn-header-action {
				background: #f1f5f9;
				color: #334155;
				border: 1px solid #e2e8f0;
				padding: 10px 16px;
				border-radius: 12px;
				font-size: 0.88rem;
				font-weight: 700;
				text-decoration: none;
				display: inline-flex;
				align-items: center;
				gap: 8px;
				transition: all 0.2s;
				cursor: pointer;
			}
			.btn-header-action:hover {
				background: #e2e8f0;
				color: #0f172a;
			}
			.btn-header-cart {
				background: var(--sp-accent);
				color: #fff;
				border: none;
				box-shadow: 0 6px 18px rgba(37,99,235,0.28);
				position: relative;
			}
			.btn-header-cart:hover {
				background: var(--sp-accent-hover);
				color: #fff;
			}
			.cart-count-badge {
				background: #ef4444;
				color: #fff;
				border-radius: 20px;
				padding: 2px 7px;
				font-size: 0.75rem;
				font-weight: 800;
				transition: transform 0.2s;
			}
			.cart-count-badge.pulse {
				animation: cartPulse 0.4s ease;
			}
			@keyframes cartPulse {
				0% { transform: scale(1); }
				50% { transform: scale(1.4); }
				100% { transform: scale(1); }
			}
			.btn-mobile-toggle {
				display: none;
				background: #f1f5f9;
				border: none;
				padding: 10px;
				border-radius: 10px;
				cursor: pointer;
			}

			/* Modern Navigation Bar */
			.store-navbar {
				background: #ffffff;
				border: 1px solid var(--sp-border);
				border-radius: var(--sp-radius);
				padding: 8px 16px;
				margin-bottom: 20px;
				box-shadow: 0 4px 15px rgba(0,0,0,0.03);
				display: flex;
				align-items: center;
				justify-content: space-between;
				gap: 15px;
				position: relative;
				z-index: 40;
			}
			.store-nav-right {
				display: flex;
				align-items: center;
				gap: 8px;
				flex-wrap: wrap;
			}
			.nav-mega-btn {
				background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
				color: #fff;
				border: none;
				padding: 10px 18px;
				border-radius: 12px;
				font-weight: 700;
				font-size: 0.9rem;
				display: inline-flex;
				align-items: center;
				gap: 8px;
				cursor: pointer;
				transition: all 0.2s;
				position: relative;
			}
			.nav-mega-btn:hover {
				background: #0f172a;
				box-shadow: 0 4px 14px rgba(15,23,42,0.2);
			}
			.nav-item-link {
				color: #475569;
				text-decoration: none;
				padding: 8px 14px;
				border-radius: 10px;
				font-size: 0.9rem;
				font-weight: 600;
				transition: all 0.2s;
				display: inline-flex;
				align-items: center;
				gap: 6px;
			}
			.nav-item-link:hover, .nav-item-link.active {
				color: var(--sp-accent);
				background: rgba(37,99,235,0.08);
			}

			/* Dropdown Mega Menu for Categories */
			.mega-dropdown-panel {
				display: none;
				position: absolute;
				top: 100%;
				right: 16px;
				margin-top: 8px;
				width: 320px;
				background: #ffffff;
				border: 1px solid var(--sp-border);
				border-radius: 16px;
				box-shadow: 0 20px 40px -10px rgba(0,0,0,0.18);
				padding: 12px;
				z-index: 100;
			}
			.mega-dropdown-panel.open {
				display: block;
				animation: dropdownFade 0.2s ease-out;
			}
			@keyframes dropdownFade {
				from { opacity: 0; transform: translateY(-8px); }
				to { opacity: 1; transform: translateY(0); }
			}
			.dropdown-cat-item {
				display: flex;
				align-items: center;
				justify-content: space-between;
				padding: 10px 14px;
				border-radius: 10px;
				color: #334155;
				text-decoration: none;
				font-size: 0.88rem;
				font-weight: 600;
				cursor: pointer;
				transition: background 0.15s;
			}
			.dropdown-cat-item:hover {
				background: #f1f5f9;
				color: var(--sp-accent);
			}

			/* Flash Sale Promotional Bar */
			.flash-sale-bar {
				background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #991b1b 100%);
				color: #fff;
				border-radius: 14px;
				padding: 12px 20px;
				margin-bottom: 20px;
				display: flex;
				justify-content: space-between;
				align-items: center;
				flex-wrap: wrap;
				gap: 12px;
				box-shadow: 0 8px 20px rgba(239, 68, 68, 0.25);
			}
			.flash-sale-title {
				display: flex;
				align-items: center;
				gap: 10px;
				font-size: 0.95rem;
			}
			.flash-icon {
				font-size: 1.2rem;
				animation: flashSpin 2s infinite ease-in-out;
			}
			@keyframes flashSpin {
				0%, 100% { transform: scale(1); }
				50% { transform: scale(1.25); }
			}
			.flash-timer {
				display: flex;
				align-items: center;
				gap: 6px;
				font-weight: 800;
				direction: ltr;
			}
			.timer-box {
				background: rgba(0,0,0,0.3);
				padding: 4px 8px;
				border-radius: 6px;
				font-size: 0.88rem;
				letter-spacing: 1px;
			}

			/* Luxury Hero Banner */
			.modern-shop-hero {
				background: radial-gradient(circle at 85% 20%, rgba(37,99,235,0.3) 0%, transparent 50%),
				            linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e1b4b 100%);
				border-radius: var(--sp-radius);
				padding: 45px 30px;
				color: #fff;
				text-align: center;
				margin-bottom: 25px;
				box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35);
				position: relative;
				overflow: hidden;
				border: 1px solid rgba(255,255,255,0.1);
			}
			.modern-shop-hero h1 {
				font-size: 2.3rem;
				font-weight: 900;
				margin-bottom: 10px;
				color: #ffffff;
				letter-spacing: -0.5px;
			}
			.modern-shop-hero p {
				font-size: 1.08rem;
				color: #cbd5e1;
				max-width: 680px;
				margin: 0 auto 24px;
				line-height: 1.7;
			}
			.hero-features-bar {
				display: flex;
				flex-wrap: wrap;
				justify-content: center;
				gap: 12px;
			}
			.hero-feature-item {
				background: rgba(255,255,255,0.08);
				backdrop-filter: blur(10px);
				border: 1px solid rgba(255,255,255,0.15);
				padding: 8px 16px;
				border-radius: 30px;
				font-size: 0.85rem;
				color: #f8fafc;
				display: flex;
				align-items: center;
				gap: 8px;
			}

			/* Toolbar & Category Chips */
			.shop-toolbar {
				display: flex;
				justify-content: space-between;
				align-items: center;
				margin-bottom: 16px;
				padding: 12px 20px;
				background: #ffffff;
				border: 1px solid var(--sp-border);
				border-radius: var(--sp-radius);
				flex-wrap: wrap;
				gap: 12px;
			}
			.sort-select {
				padding: 8px 14px;
				border-radius: 10px;
				border: 1px solid #cbd5e1;
				font-family: inherit;
				font-size: 0.88rem;
				background-color: #fff;
				color: #334155;
				cursor: pointer;
				outline: none;
			}
			.filter-pills-wrap {
				display: flex;
				overflow-x: auto;
				padding-bottom: 8px;
				gap: 8px;
				margin-bottom: 22px;
				scrollbar-width: thin;
			}
			.filter-pill {
				background: #fff;
				border: 1px solid var(--sp-border);
				padding: 8px 16px;
				border-radius: 30px;
				font-size: 0.88rem;
				font-weight: 600;
				color: #475569;
				cursor: pointer;
				white-space: nowrap;
				transition: all 0.2s;
				display: inline-flex;
				align-items: center;
				gap: 6px;
			}
			.filter-pill.active, .filter-pill:hover {
				background: var(--sp-accent);
				color: #fff;
				border-color: var(--sp-accent);
				box-shadow: 0 4px 12px rgba(37,99,235,0.25);
			}
			.filter-pill-badge {
				background: rgba(0,0,0,0.08);
				padding: 2px 7px;
				border-radius: 20px;
				font-size: 0.72rem;
			}
			.filter-pill.active .filter-pill-badge {
				background: rgba(255,255,255,0.25);
				color: #fff;
			}

			/* Products Grid */
			.products-grid {
				display: grid;
				gap: 20px;
				transition: all 0.25s ease;
			}

			/* Columns Switcher Classes (Default: 1 Column) */
			.products-grid.cols-1 {
				grid-template-columns: 1fr !important;
				gap: 16px;
			}
			.products-grid.cols-1 .product-card {
				flex-direction: row;
				align-items: center;
				padding: 14px;
				gap: 18px;
			}
			.products-grid.cols-1 .card-img-wrap {
				width: 160px;
				min-width: 160px;
				height: 160px;
				border-radius: 12px;
			}
			.products-grid.cols-1 .card-body {
				flex: 1;
				padding: 0;
				display: flex;
				flex-direction: column;
				justify-content: center;
			}
			.products-grid.cols-1 .card-title {
				font-size: 1.1rem;
				height: auto;
				margin-bottom: 8px;
			}
			.products-grid.cols-1 .card-actions {
				max-width: 320px;
				margin-top: 10px;
			}

			.products-grid.cols-2 {
				grid-template-columns: repeat(2, 1fr) !important;
				gap: 16px;
			}
			.products-grid.cols-2 .product-card {
				flex-direction: column;
			}
			.products-grid.cols-2 .card-img-wrap {
				width: 100%;
				height: 200px;
			}

			.products-grid.cols-3 {
				grid-template-columns: repeat(3, 1fr) !important;
				gap: 18px;
			}
			.products-grid.cols-3 .product-card {
				flex-direction: column;
			}
			.products-grid.cols-3 .card-img-wrap {
				width: 100%;
				height: 200px;
			}

			.products-grid.cols-4 {
				grid-template-columns: repeat(4, 1fr) !important;
				gap: 18px;
			}
			.products-grid.cols-4 .product-card {
				flex-direction: column;
			}
			.products-grid.cols-4 .card-img-wrap {
				width: 100%;
				height: 180px;
			}

			/* Products Toolbar (View Switcher & Info) */
			.shop-toolbar {
				background: #ffffff;
				border: 1px solid var(--sp-border);
				border-radius: 14px;
				padding: 12px 18px;
				margin-bottom: 22px;
				display: flex;
				justify-content: space-between;
				align-items: center;
				flex-wrap: wrap;
				gap: 12px;
				box-shadow: 0 2px 8px rgba(0,0,0,0.03);
			}
			.toolbar-info {
				font-size: 0.92rem;
				font-weight: 700;
				color: #334155;
				display: flex;
				align-items: center;
				gap: 8px;
			}
			.toolbar-info .count-badge {
				background: #eff6ff;
				color: var(--sp-accent, #2563eb);
				padding: 3px 10px;
				border-radius: 20px;
				font-size: 0.82rem;
			}
			.toolbar-view-switcher {
				display: flex;
				align-items: center;
				gap: 6px;
			}
			.switcher-label {
				font-size: 0.85rem;
				color: #64748b;
				margin-left: 6px;
				font-weight: 600;
			}
			.col-switch-btn {
				background: #f8fafc;
				border: 1px solid #e2e8f0;
				border-radius: 8px;
				padding: 6px 12px;
				font-family: inherit;
				font-size: 0.82rem;
				font-weight: 700;
				color: #475569;
				cursor: pointer;
				display: inline-flex;
				align-items: center;
				gap: 6px;
				transition: all 0.2s ease;
			}
			.col-switch-btn:hover {
				background: #f1f5f9;
				color: #0f172a;
			}
			.col-switch-btn.active {
				background: var(--sp-accent, #2563eb);
				color: #ffffff;
				border-color: var(--sp-accent, #2563eb);
				box-shadow: 0 3px 10px rgba(37, 99, 235, 0.3);
			}
			.col-switch-btn svg {
				width: 16px;
				height: 16px;
			}

			/* Pagination Controls (صفحه‌بندی ۲۰ تایی) */
			.shop-pagination-wrap {
				display: flex;
				justify-content: center;
				align-items: center;
				gap: 8px;
				margin-top: 35px;
				margin-bottom: 25px;
				flex-wrap: wrap;
			}
			.page-btn {
				min-width: 40px;
				height: 40px;
				padding: 0 12px;
				border-radius: 10px;
				border: 1px solid #e2e8f0;
				background: #ffffff;
				color: #334155;
				font-family: inherit;
				font-size: 0.9rem;
				font-weight: 700;
				cursor: pointer;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				transition: all 0.2s ease;
				box-shadow: 0 2px 4px rgba(0,0,0,0.02);
			}
			.page-btn:hover:not(:disabled) {
				background: #f8fafc;
				border-color: #cbd5e1;
				color: #0f172a;
			}
			.page-btn.active {
				background: var(--sp-accent, #2563eb);
				color: #ffffff;
				border-color: var(--sp-accent, #2563eb);
				box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
			}
			.page-btn:disabled {
				opacity: 0.4;
				cursor: not-allowed;
			}
			.product-card {
				background: var(--sp-bg-card);
				border: 1px solid var(--sp-border);
				border-radius: var(--sp-radius);
				overflow: hidden;
				display: flex;
				flex-direction: column;
				transition: transform 0.28s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.28s ease;
				position: relative;
				box-shadow: var(--sp-shadow);
			}
			.product-card:hover {
				transform: translateY(-6px);
				box-shadow: 0 16px 30px -8px rgba(0,0,0,0.12);
			}
			.card-thumb-wrap {
				position: relative;
				width: 100%;
				padding-top: 100%;
				background: #f8fafc;
				overflow: hidden;
			}
			.card-thumb-wrap img {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				object-fit: cover;
				transition: transform 0.45s ease;
			}
			.product-card:hover .card-thumb-wrap img {
				transform: scale(1.06);
			}
			.card-wishlist-btn {
				position: absolute;
				top: 12px;
				right: 12px;
				width: 34px;
				height: 34px;
				border-radius: 50%;
				background: rgba(255, 255, 255, 0.9);
				backdrop-filter: blur(4px);
				border: none;
				display: flex;
				align-items: center;
				justify-content: center;
				cursor: pointer;
				z-index: 3;
				transition: transform 0.2s, background 0.2s;
				box-shadow: 0 2px 8px rgba(0,0,0,0.08);
			}
			.card-wishlist-btn:hover {
				transform: scale(1.15);
				background: #fff;
			}
			.card-wishlist-btn svg {
				width: 18px;
				height: 18px;
				fill: #94a3b8;
				transition: fill 0.2s;
			}
			.card-wishlist-btn.liked svg {
				fill: #ef4444;
			}
			.card-stock-badge {
				position: absolute;
				top: 12px;
				left: 12px;
				background: rgba(16, 185, 129, 0.95);
				color: #fff;
				font-size: 0.72rem;
				font-weight: 700;
				padding: 3px 9px;
				border-radius: 6px;
				z-index: 2;
			}
			.card-discount-badge {
				position: absolute;
				bottom: 10px;
				right: 12px;
				background: #ef4444;
				color: #fff;
				font-size: 0.75rem;
				font-weight: 800;
				padding: 2px 8px;
				border-radius: 6px;
				z-index: 2;
			}
			.card-body {
				padding: 18px;
				display: flex;
				flex-direction: column;
				flex-grow: 1;
			}
			.card-category {
				font-size: 0.78rem;
				color: var(--sp-muted);
				margin-bottom: 6px;
				font-weight: 600;
			}
			.card-title {
				font-size: 0.98rem;
				font-weight: 700;
				line-height: 1.55;
				margin-bottom: 12px;
				height: 46px;
				overflow: hidden;
				display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
				color: var(--sp-text);
			}
			.card-pricing-block {
				margin-top: auto;
				margin-bottom: 16px;
				display: flex;
				flex-direction: column;
				gap: 3px;
			}
			.pricing-row-top {
				display: flex;
				align-items: center;
				justify-content: space-between;
				min-height: 20px;
			}
			.card-old-price {
				font-size: 0.82rem;
				color: #94a3b8;
				text-decoration: line-through;
			}
			.card-special-tag {
				color: #059669;
				font-size: 0.75rem;
				font-weight: 700;
			}
			.card-new-price {
				font-size: 1.28rem;
				font-weight: 900;
				color: #059669;
			}
			.card-actions {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 8px;
			}
			.btn-card-quick {
				background: #f1f5f9;
				color: #334155;
				border: none;
				border-radius: 10px;
				padding: 9px;
				font-weight: 700;
				font-size: 0.85rem;
				cursor: pointer;
				text-align: center;
				transition: all 0.2s;
				font-family: inherit;
			}
			.btn-card-quick:hover {
				background: #e2e8f0;
			}
			.btn-card-buy {
				background: var(--sp-accent);
				color: #fff;
				border: none;
				border-radius: 10px;
				padding: 9px;
				font-weight: 700;
				font-size: 0.85rem;
				cursor: pointer;
				text-align: center;
				transition: all 0.2s;
				text-decoration: none;
				display: inline-flex;
				align-items: center;
				justify-content: center;
				gap: 6px;
				font-family: inherit;
			}
			.btn-card-buy:hover {
				background: var(--sp-accent-hover);
				color: #fff;
			}
			.btn-card-buy.added {
				background: #10b981;
			}

			/* Empty search state */
			.search-no-results {
				background: #fff;
				border: 1px solid var(--sp-border);
				border-radius: var(--sp-radius);
				padding: 40px 20px;
				text-align: center;
				grid-column: 1 / -1;
				margin: 10px 0;
			}
			.search-no-results h4 {
				margin: 10px 0;
				font-size: 1.15rem;
				font-weight: 800;
			}
			.search-no-results p {
				color: var(--sp-muted);
				font-size: 0.9rem;
				margin-bottom: 16px;
			}

			/* Slide-over Cart Drawer */
			.cart-drawer-overlay {
				position: fixed;
				inset: 0;
				background: rgba(0, 0, 0, 0.45);
				backdrop-filter: blur(4px);
				z-index: 9998;
				opacity: 0;
				pointer-events: none;
				transition: opacity 0.3s ease;
			}
			.cart-drawer-overlay.open {
				opacity: 1;
				pointer-events: auto;
			}
			.cart-drawer {
				position: fixed;
				top: 0;
				left: -420px;
				width: 400px;
				max-width: 92vw;
				height: 100vh;
				background: #fff;
				z-index: 9999;
				box-shadow: 10px 0 30px rgba(0,0,0,0.15);
				transition: left 0.35s cubic-bezier(0.16, 1, 0.3, 1);
				display: flex;
				flex-direction: column;
				direction: rtl;
				text-align: right;
			}
			.cart-drawer.open {
				left: 0;
			}
			.cart-drawer-header {
				padding: 18px 22px;
				border-bottom: 1px solid var(--sp-border);
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			.cart-shipping-progress {
				background: #f8fafc;
				padding: 12px 20px;
				border-bottom: 1px solid #f1f5f9;
				font-size: 0.82rem;
			}
			.progress-track {
				height: 6px;
				background: #e2e8f0;
				border-radius: 10px;
				margin-top: 6px;
				overflow: hidden;
			}
			.progress-fill {
				height: 100%;
				background: #10b981;
				width: 0%;
				transition: width 0.3s;
			}
			.cart-drawer-items {
				flex-grow: 1;
				overflow-y: auto;
				padding: 16px 20px;
				display: flex;
				flex-direction: column;
				gap: 14px;
			}
			.cart-item-row {
				display: flex;
				gap: 12px;
				align-items: center;
				padding-bottom: 12px;
				border-bottom: 1px solid #f1f5f9;
			}
			.cart-item-img {
				width: 64px;
				height: 64px;
				border-radius: 12px;
				object-fit: cover;
				background: #f1f5f9;
			}
			.cart-item-info {
				flex-grow: 1;
			}
			.cart-item-title {
				font-size: 0.88rem;
				font-weight: 700;
				margin-bottom: 4px;
				line-height: 1.4;
			}
			.cart-item-price {
				font-size: 0.88rem;
				color: #059669;
				font-weight: 800;
				margin-bottom: 6px;
			}
			.cart-item-qty-row {
				display: flex;
				align-items: center;
				gap: 8px;
			}
			.cart-qty-btn {
				width: 26px;
				height: 26px;
				border: 1px solid #cbd5e1;
				background: #fff;
				border-radius: 6px;
				display: flex;
				align-items: center;
				justify-content: center;
				cursor: pointer;
				font-weight: 700;
			}
			.cart-qty-btn:hover {
				background: #f1f5f9;
			}
			.cart-qty-num {
				font-weight: 800;
				font-size: 0.9rem;
				min-width: 18px;
				text-align: center;
			}
			.cart-item-del {
				background: none;
				border: none;
				color: #94a3b8;
				font-size: 0.95rem;
				cursor: pointer;
				margin-right: auto;
				padding: 4px;
				transition: color 0.2s;
			}
			.cart-item-del:hover {
				color: #ef4444;
			}
			.cart-drawer-footer {
				padding: 20px;
				border-top: 1px solid var(--sp-border);
				background: #f8fafc;
			}
			.cart-total-row {
				display: flex;
				justify-content: space-between;
				font-size: 1.15rem;
				font-weight: 800;
				margin-bottom: 14px;
			}

			/* Quick View Modal */
			.modal-overlay {
				position: fixed;
				inset: 0;
				background: rgba(15, 23, 42, 0.6);
				backdrop-filter: blur(6px);
				display: none;
				align-items: center;
				justify-content: center;
				z-index: 10000;
				padding: 20px;
			}
			.modal-overlay.open {
				display: flex;
			}
			.modal-content {
				background: #fff;
				border-radius: 24px;
				max-width: 760px;
				width: 100%;
				overflow: hidden;
				position: relative;
				animation: modalSlide 0.28s cubic-bezier(0.16, 1, 0.3, 1);
				box-shadow: 0 25px 60px -15px rgba(0,0,0,0.3);
			}
			@keyframes modalSlide {
				from { transform: translateY(25px); opacity: 0; }
				to { transform: translateY(0); opacity: 1; }
			}
			.modal-close {
				position: absolute;
				top: 18px;
				left: 18px;
				font-size: 1.3rem;
				cursor: pointer;
				width: 36px;
				height: 36px;
				background: #f1f5f9;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				color: #475569;
				z-index: 10;
				transition: background 0.2s;
			}
			.modal-close:hover {
				background: #e2e8f0;
			}
			.modal-inner { display: flex; flex-direction: column; max-height: 85vh; overflow-y: auto; }
			@media(min-width: 680px) {
				.modal-inner { flex-direction: row; }
				.modal-col-img { width: 45%; }
				.modal-col-info { width: 55%; padding: 30px; }
			}
			.modal-col-info { padding: 22px; }
			.modal-col-img img { width: 100%; height: 100%; object-fit: cover; }
			.modal-qty-control {
				display: flex;
				align-items: center;
				gap: 10px;
				margin-bottom: 18px;
			}
			.qty-picker {
				display: flex;
				align-items: center;
				border: 1.5px solid #cbd5e1;
				border-radius: 10px;
				overflow: hidden;
			}
			.qty-picker button {
				width: 36px;
				height: 36px;
				background: #f8fafc;
				border: none;
				cursor: pointer;
				font-size: 1.1rem;
				font-weight: 700;
			}
			.qty-picker button:hover {
				background: #e2e8f0;
			}
			.qty-picker span {
				width: 40px;
				text-align: center;
				font-weight: 800;
			}

			/* Toast Notification */
			.store-toast {
				position: fixed;
				bottom: 30px;
				left: 50%;
				transform: translateX(-50%) translateY(100px);
				background: #0f172a;
				color: #fff;
				padding: 12px 24px;
				border-radius: 30px;
				box-shadow: 0 12px 30px rgba(0,0,0,0.25);
				display: flex;
				align-items: center;
				gap: 10px;
				font-size: 0.92rem;
				font-weight: 700;
				z-index: 100000;
				transition: transform 0.35s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.35s;
				opacity: 0;
				pointer-events: none;
			}
			.store-toast.show {
				transform: translateX(-50%) translateY(0);
				opacity: 1;
			}
			.toast-icon {
				width: 22px;
				height: 22px;
				background: #10b981;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				font-size: 0.8rem;
			}

			/* Mobile Bottom App Navigation Bar */
			.mobile-bottom-bar {
				display: none;
				position: fixed;
				bottom: 0;
				left: 0;
				right: 0;
				background: rgba(255, 255, 255, 0.94);
				backdrop-filter: blur(12px);
				border-top: 1px solid var(--sp-border);
				padding: 8px 15px;
				z-index: 9990;
				justify-content: space-around;
				align-items: center;
				box-shadow: 0 -4px 15px rgba(0,0,0,0.06);
			}

			/* Online Support Chat System: 12 Visual Themes, 6 Button Styles & Live Thread */
			:root {
				--chat-primary: #2563eb;
				--chat-primary-rgb: 37, 99, 235;
				--chat-hdr-bg: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
				--chat-hdr-text: #ffffff;
				--chat-window-bg: #ffffff;
				--chat-body-bg: #f8fafc;
				--chat-user-bg: linear-gradient(135deg, #2563eb, #1d4ed8);
				--chat-user-text: #ffffff;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #e2e8f0;
				--chat-ai-text: #0f172a;
				--chat-ai-badge: #7c3aed;
				--chat-admin-bg: #ecfdf5;
				--chat-admin-border: #a7f3d0;
				--chat-admin-text: #065f46;
				--chat-admin-badge: #059669;
				--chat-btn-bg: linear-gradient(135deg, #2563eb 0%, #7c3aed 100%);
				--chat-btn-text: #ffffff;
				--chat-radius: 20px;
				--chat-backdrop: none;
			}

			/* 12 Color Schemes & Themes */
			.support-chat-wrapper.theme-royal-blue {
				--chat-primary: #2563eb;
				--chat-primary-rgb: 37, 99, 235;
				--chat-hdr-bg: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
				--chat-hdr-text: #ffffff;
				--chat-body-bg: #f8fafc;
				--chat-user-bg: linear-gradient(135deg, #2563eb, #1d4ed8);
				--chat-user-text: #ffffff;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #e2e8f0;
				--chat-ai-text: #0f172a;
				--chat-ai-badge: #7c3aed;
				--chat-admin-bg: #ecfdf5;
				--chat-admin-border: #a7f3d0;
				--chat-admin-text: #065f46;
				--chat-admin-badge: #059669;
				--chat-btn-bg: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-cyberpunk-dark {
				--chat-primary: #a855f7;
				--chat-primary-rgb: 168, 85, 247;
				--chat-hdr-bg: linear-gradient(135deg, #090514 0%, #2e1065 100%);
				--chat-hdr-text: #f3e8ff;
				--chat-window-bg: #0f172a;
				--chat-body-bg: #0b1120;
				--chat-user-bg: linear-gradient(135deg, #7c3aed, #a855f7);
				--chat-user-text: #ffffff;
				--chat-ai-bg: #1e293b;
				--chat-ai-border: #475569;
				--chat-ai-text: #e2e8f0;
				--chat-ai-badge: #c084fc;
				--chat-admin-bg: #064e3b;
				--chat-admin-border: #10b981;
				--chat-admin-text: #a7f3d0;
				--chat-admin-badge: #34d399;
				--chat-btn-bg: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-emerald-whatsapp {
				--chat-primary: #059669;
				--chat-primary-rgb: 5, 150, 105;
				--chat-hdr-bg: linear-gradient(135deg, #064e3b 0%, #059669 100%);
				--chat-hdr-text: #ffffff;
				--chat-body-bg: #efeae2;
				--chat-user-bg: #d9fdd3;
				--chat-user-text: #111827;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #e5e7eb;
				--chat-ai-text: #111827;
				--chat-ai-badge: #059669;
				--chat-admin-bg: #e0f2fe;
				--chat-admin-border: #bae6fd;
				--chat-admin-text: #075985;
				--chat-admin-badge: #0284c7;
				--chat-btn-bg: linear-gradient(135deg, #059669 0%, #10b981 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-magenta-rose {
				--chat-primary: #db2777;
				--chat-primary-rgb: 219, 39, 119;
				--chat-hdr-bg: linear-gradient(135deg, #831843 0%, #db2777 100%);
				--chat-hdr-text: #ffffff;
				--chat-body-bg: #fff1f2;
				--chat-user-bg: linear-gradient(135deg, #db2777, #f43f5e);
				--chat-user-text: #ffffff;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #fecdd3;
				--chat-ai-text: #881337;
				--chat-ai-badge: #e11d48;
				--chat-admin-bg: #f0fdf4;
				--chat-admin-border: #bbf7d0;
				--chat-admin-text: #166534;
				--chat-admin-badge: #15803d;
				--chat-btn-bg: linear-gradient(135deg, #db2777 0%, #f43f5e 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-gold-vip {
				--chat-primary: #d97706;
				--chat-primary-rgb: 217, 119, 6;
				--chat-hdr-bg: linear-gradient(135deg, #09090b 0%, #1c1917 100%);
				--chat-hdr-text: #fef08a;
				--chat-window-bg: #18181b;
				--chat-body-bg: #111113;
				--chat-user-bg: linear-gradient(135deg, #b45309, #d97706);
				--chat-user-text: #ffffff;
				--chat-ai-bg: #27272a;
				--chat-ai-border: #78350f;
				--chat-ai-text: #fef3c7;
				--chat-ai-badge: #f59e0b;
				--chat-admin-bg: #292524;
				--chat-admin-border: #f59e0b;
				--chat-admin-text: #fef9c3;
				--chat-admin-badge: #fbbf24;
				--chat-btn-bg: linear-gradient(135deg, #b45309 0%, #f59e0b 100%);
				--chat-btn-text: #09090b;
			}

			.support-chat-wrapper.theme-minimal-slate {
				--chat-primary: #334155;
				--chat-primary-rgb: 51, 65, 85;
				--chat-hdr-bg: linear-gradient(135deg, #1e293b 0%, #334155 100%);
				--chat-hdr-text: #ffffff;
				--chat-body-bg: #f8fafc;
				--chat-user-bg: #334155;
				--chat-user-text: #ffffff;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #cbd5e1;
				--chat-ai-text: #0f172a;
				--chat-ai-badge: #475569;
				--chat-admin-bg: #f1f5f9;
				--chat-admin-border: #94a3b8;
				--chat-admin-text: #0f172a;
				--chat-admin-badge: #0f172a;
				--chat-btn-bg: linear-gradient(135deg, #1e293b 0%, #334155 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-aurora-gradient {
				--chat-primary: #6366f1;
				--chat-primary-rgb: 99, 102, 241;
				--chat-hdr-bg: linear-gradient(135deg, #4338ca 0%, #06b6d4 100%);
				--chat-hdr-text: #ffffff;
				--chat-body-bg: #f5f3ff;
				--chat-user-bg: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
				--chat-user-text: #ffffff;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #c7d2fe;
				--chat-ai-text: #312e81;
				--chat-ai-badge: #4f46e5;
				--chat-admin-bg: #ecfeff;
				--chat-admin-border: #a5f3fc;
				--chat-admin-text: #164e63;
				--chat-admin-badge: #0891b2;
				--chat-btn-bg: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-sunset-coral {
				--chat-primary: #ea580c;
				--chat-primary-rgb: 234, 88, 12;
				--chat-hdr-bg: linear-gradient(135deg, #9a3412 0%, #ea580c 100%);
				--chat-hdr-text: #ffffff;
				--chat-body-bg: #fff7ed;
				--chat-user-bg: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
				--chat-user-text: #ffffff;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #fed7aa;
				--chat-ai-text: #7c2d12;
				--chat-ai-badge: #c2410c;
				--chat-admin-bg: #fef2f2;
				--chat-admin-border: #fecaca;
				--chat-admin-text: #991b1b;
				--chat-admin-badge: #b91c1c;
				--chat-btn-bg: linear-gradient(135deg, #ea580c 0%, #f97316 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-telegram-ocean {
				--chat-primary: #0284c7;
				--chat-primary-rgb: 2, 132, 199;
				--chat-hdr-bg: linear-gradient(135deg, #0369a1 0%, #0284c7 100%);
				--chat-hdr-text: #ffffff;
				--chat-body-bg: #f0f9ff;
				--chat-user-bg: #e0f2fe;
				--chat-user-text: #0369a1;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #bae6fd;
				--chat-ai-text: #0c4a6e;
				--chat-ai-badge: #0284c7;
				--chat-admin-bg: #f0fdf4;
				--chat-admin-border: #bbf7d0;
				--chat-admin-text: #14532d;
				--chat-admin-badge: #16a34a;
				--chat-btn-bg: linear-gradient(135deg, #0284c7 0%, #38bdf8 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-warm-caramel {
				--chat-primary: #92400e;
				--chat-primary-rgb: 146, 64, 14;
				--chat-hdr-bg: linear-gradient(135deg, #451a03 0%, #92400e 100%);
				--chat-hdr-text: #ffffff;
				--chat-body-bg: #fffbeb;
				--chat-user-bg: linear-gradient(135deg, #b45309 0%, #92400e 100%);
				--chat-user-text: #ffffff;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #fde68a;
				--chat-ai-text: #78350f;
				--chat-ai-badge: #b45309;
				--chat-admin-bg: #fef3c7;
				--chat-admin-border: #f59e0b;
				--chat-admin-text: #78350f;
				--chat-admin-badge: #d97706;
				--chat-btn-bg: linear-gradient(135deg, #78350f 0%, #b45309 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-mint-pastel {
				--chat-primary: #0d9488;
				--chat-primary-rgb: 13, 148, 136;
				--chat-hdr-bg: linear-gradient(135deg, #115e59 0%, #0d9488 100%);
				--chat-hdr-text: #ffffff;
				--chat-body-bg: #f0fdfa;
				--chat-user-bg: linear-gradient(135deg, #0d9488 0%, #14b8a6 100%);
				--chat-user-text: #ffffff;
				--chat-ai-bg: #ffffff;
				--chat-ai-border: #ccfbf1;
				--chat-ai-text: #134e4a;
				--chat-ai-badge: #0d9488;
				--chat-admin-bg: #ecfdf5;
				--chat-admin-border: #a7f3d0;
				--chat-admin-text: #065f46;
				--chat-admin-badge: #059669;
				--chat-btn-bg: linear-gradient(135deg, #0d9488 0%, #2dd4bf 100%);
				--chat-btn-text: #ffffff;
			}

			.support-chat-wrapper.theme-frosted-glass {
				--chat-primary: #2563eb;
				--chat-primary-rgb: 37, 99, 235;
				--chat-hdr-bg: linear-gradient(135deg, rgba(30, 41, 59, 0.92) 0%, rgba(37, 99, 235, 0.88) 100%);
				--chat-hdr-text: #ffffff;
				--chat-window-bg: rgba(255, 255, 255, 0.92);
				--chat-body-bg: rgba(248, 250, 252, 0.82);
				--chat-backdrop: blur(18px);
				--chat-user-bg: linear-gradient(135deg, rgba(37, 99, 235, 0.95), rgba(59, 130, 246, 0.9));
				--chat-user-text: #ffffff;
				--chat-ai-bg: rgba(255, 255, 255, 0.95);
				--chat-ai-border: rgba(203, 213, 225, 0.8);
				--chat-ai-text: #0f172a;
				--chat-ai-badge: #4f46e5;
				--chat-admin-bg: rgba(236, 253, 245, 0.95);
				--chat-admin-border: rgba(167, 243, 208, 0.8);
				--chat-admin-text: #065f46;
				--chat-admin-badge: #059669;
				--chat-btn-bg: rgba(255, 255, 255, 0.92);
				--chat-btn-text: #0f172a;
			}

			/* Chat Wrapper Positioning */
			.support-chat-wrapper {
				position: fixed;
				bottom: 25px;
				z-index: 9998;
				font-family: inherit;
			}
			.support-chat-wrapper.pos-left {
				left: 25px;
			}
			.support-chat-wrapper.pos-right {
				right: 25px;
			}

			/* ================= 6 Button Styles ================= */
			/* 1. Pill with Label (Default) */
			.support-chat-wrapper.btn-pill-label .support-chat-btn {
				display: inline-flex;
				align-items: center;
				gap: 10px;
				background: var(--chat-btn-bg);
				color: var(--chat-btn-text);
				border: none;
				border-radius: 50px;
				padding: 12px 22px;
				cursor: pointer;
				box-shadow: 0 10px 25px rgba(var(--chat-primary-rgb), 0.38);
				transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
				position: relative;
				font-size: 0.95rem;
				font-weight: 800;
			}
			.support-chat-wrapper.btn-pill-label .support-chat-btn:hover {
				transform: translateY(-3px) scale(1.03);
				box-shadow: 0 14px 30px rgba(var(--chat-primary-rgb), 0.5);
			}

			/* 2. Modern Glowing Circle */
			.support-chat-wrapper.btn-circle-glow .support-chat-btn {
				width: 60px;
				height: 60px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				background: var(--chat-btn-bg);
				color: var(--chat-btn-text);
				border: none;
				cursor: pointer;
				box-shadow: 0 0 25px rgba(var(--chat-primary-rgb), 0.65), 0 10px 20px rgba(0,0,0,0.15);
				transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
				position: relative;
			}
			.support-chat-wrapper.btn-circle-glow .support-chat-btn .support-chat-label {
				display: none;
			}
			.support-chat-wrapper.btn-circle-glow .support-chat-btn:hover {
				transform: translateY(-4px) scale(1.08);
				box-shadow: 0 0 35px rgba(var(--chat-primary-rgb), 0.85), 0 15px 25px rgba(0,0,0,0.2);
			}

			/* 3. Live Support Avatar with Ring */
			.support-chat-wrapper.btn-avatar-ring .support-chat-btn {
				width: 62px;
				height: 62px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				background: #ffffff;
				color: #1e293b;
				border: 3px solid var(--chat-primary);
				cursor: pointer;
				box-shadow: 0 10px 30px rgba(0,0,0,0.15);
				position: relative;
				transition: all 0.3s ease;
				font-size: 1.7rem;
			}
			.support-chat-wrapper.btn-avatar-ring .support-chat-btn .support-chat-label {
				display: none;
			}
			.support-chat-wrapper.btn-avatar-ring .support-chat-btn:hover {
				transform: scale(1.08);
			}

			/* 4. Frosted Glassmorphism Floating Bubble */
			.support-chat-wrapper.btn-frosted-glass .support-chat-btn {
				display: inline-flex;
				align-items: center;
				gap: 10px;
				background: rgba(255, 255, 255, 0.85);
				backdrop-filter: blur(14px);
				-webkit-backdrop-filter: blur(14px);
				border: 1.5px solid rgba(255, 255, 255, 0.7);
				color: #0f172a;
				border-radius: 50px;
				padding: 12px 22px;
				cursor: pointer;
				box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
				font-size: 0.95rem;
				font-weight: 800;
				transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
				position: relative;
			}
			.support-chat-wrapper.btn-frosted-glass .support-chat-btn:hover {
				background: rgba(255, 255, 255, 0.95);
				transform: translateY(-3px);
				box-shadow: 0 16px 40px rgba(0,0,0,0.16);
			}

			/* 5. Edge Tab Ribbon */
			.support-chat-wrapper.btn-edge-tab.pos-left {
				left: 0 !important;
				bottom: 50% !important;
				transform: translateY(50%);
			}
			.support-chat-wrapper.btn-edge-tab.pos-right {
				right: 0 !important;
				bottom: 50% !important;
				transform: translateY(50%);
			}
			.support-chat-wrapper.btn-edge-tab .support-chat-btn {
				display: flex;
				align-items: center;
				gap: 8px;
				background: var(--chat-btn-bg);
				color: var(--chat-btn-text);
				border: none;
				padding: 14px 18px;
				cursor: pointer;
				font-size: 0.92rem;
				font-weight: 800;
				box-shadow: 0 8px 25px rgba(0,0,0,0.18);
				transition: all 0.2s ease;
			}
			.support-chat-wrapper.btn-edge-tab.pos-left .support-chat-btn {
				border-radius: 0 16px 16px 0;
			}
			.support-chat-wrapper.btn-edge-tab.pos-right .support-chat-btn {
				border-radius: 16px 0 0 16px;
			}

			/* 6. Sonar / Radar Double Wave */
			.support-chat-wrapper.btn-radar-pulse .support-chat-btn {
				width: 60px;
				height: 60px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				background: var(--chat-btn-bg);
				color: var(--chat-btn-text);
				border: none;
				cursor: pointer;
				position: relative;
				box-shadow: 0 10px 25px rgba(var(--chat-primary-rgb), 0.4);
				transition: transform 0.3s;
			}
			.support-chat-wrapper.btn-radar-pulse .support-chat-btn .support-chat-label {
				display: none;
			}
			.support-chat-wrapper.btn-radar-pulse .support-chat-btn::before,
			.support-chat-wrapper.btn-radar-pulse .support-chat-btn::after {
				content: "";
				position: absolute;
				top: 0; left: 0; right: 0; bottom: 0;
				border-radius: 50%;
				border: 2px solid var(--chat-primary);
				animation: radarPulse 2.4s infinite cubic-bezier(0.25, 1, 0.5, 1);
				pointer-events: none;
			}
			.support-chat-wrapper.btn-radar-pulse .support-chat-btn::after {
				animation-delay: 1.2s;
			}
			@keyframes radarPulse {
				0% { transform: scale(1); opacity: 0.9; }
				100% { transform: scale(1.85); opacity: 0; }
			}

			/* Common Button SVGs and Badges */
			.support-chat-btn svg {
				width: 24px;
				height: 24px;
				fill: none;
				stroke: currentColor;
				stroke-width: 2;
				flex-shrink: 0;
			}
			.support-chat-badge {
				position: absolute;
				top: -2px;
				right: -2px;
				width: 13px;
				height: 13px;
				background: #10b981;
				border: 2.5px solid #fff;
				border-radius: 50%;
				box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
				animation: pulse-green 2s infinite;
			}
			@keyframes pulse-green {
				0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
				70% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
				100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
			}

			/* Chat Popup Window */
			.support-chat-window {
				display: none;
				position: fixed;
				bottom: 90px;
				width: 380px;
				max-width: calc(100vw - 30px);
				background: var(--chat-window-bg);
				backdrop-filter: var(--chat-backdrop);
				-webkit-backdrop-filter: var(--chat-backdrop);
				border-radius: var(--chat-radius);
				box-shadow: 0 24px 60px rgba(15, 23, 42, 0.25);
				border: 1px solid rgba(226, 232, 240, 0.85);
				z-index: 9999;
				overflow: hidden;
				flex-direction: column;
				animation: chatSlideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
			}
			.support-chat-wrapper.pos-left .support-chat-window {
				left: 25px;
			}
			.support-chat-wrapper.pos-right .support-chat-window {
				right: 25px;
			}
			.support-chat-wrapper.btn-edge-tab.pos-left .support-chat-window {
				left: 25px;
				bottom: 80px;
			}
			.support-chat-wrapper.btn-edge-tab.pos-right .support-chat-window {
				right: 25px;
				bottom: 80px;
			}
			.support-chat-window.open {
				display: flex;
			}
			@keyframes chatSlideUp {
				from { opacity: 0; transform: translateY(20px) scale(0.95); }
				to { opacity: 1; transform: translateY(0) scale(1); }
			}

			/* Chat Header */
			.chat-hdr {
				background: var(--chat-hdr-bg);
				color: var(--chat-hdr-text);
				padding: 16px 20px;
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			.chat-hdr-agent {
				display: flex;
				align-items: center;
				gap: 12px;
			}
			.chat-agent-avatar {
				width: 44px;
				height: 44px;
				border-radius: 50%;
				background: rgba(255, 255, 255, 0.2);
				display: flex;
				align-items: center;
				justify-content: center;
				font-size: 1.4rem;
				position: relative;
				box-shadow: 0 4px 10px rgba(0,0,0,0.15);
			}
			.chat-agent-avatar::after {
				content: '';
				position: absolute;
				bottom: 1px;
				left: 1px;
				width: 11px;
				height: 11px;
				background: #10b981;
				border: 2px solid #fff;
				border-radius: 50%;
			}
			.chat-hdr-info h4 {
				margin: 0 0 3px;
				font-size: 1.05rem;
				font-weight: 800;
				color: var(--chat-hdr-text);
			}
			.chat-hdr-info span {
				font-size: 0.78rem;
				opacity: 0.85;
				display: flex;
				align-items: center;
				gap: 4px;
			}
			.chat-close-btn {
				background: rgba(255, 255, 255, 0.16);
				border: none;
				color: #fff;
				width: 32px;
				height: 32px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				cursor: pointer;
				font-size: 1.1rem;
				transition: background 0.2s;
			}
			.chat-close-btn:hover {
				background: rgba(255, 255, 255, 0.32);
			}

			/* Chat User Mini-Bar (Shows when user identity is known) */
			.chat-user-bar {
				background: #f1f5f9;
				border-bottom: 1px solid #e2e8f0;
				padding: 6px 16px;
				font-size: 0.8rem;
				color: #475569;
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			.chat-user-bar strong {
				color: #1e293b;
			}
			.chat-user-edit-btn {
				background: none;
				border: none;
				color: var(--chat-primary);
				cursor: pointer;
				font-size: 0.78rem;
				font-weight: 700;
				padding: 2px 6px;
			}

			/* Chat Body & Continuous Stream */
			.chat-body-scroll {
				padding: 16px 18px;
				height: 320px;
				max-height: 50vh;
				overflow-y: auto;
				background: var(--chat-body-bg);
				display: flex;
				flex-direction: column;
				gap: 12px;
				scroll-behavior: smooth;
			}
			.chat-msg-bubble {
				max-width: 86%;
				padding: 11px 15px;
				border-radius: 16px;
				font-size: 0.9rem;
				line-height: 1.55;
				position: relative;
				word-break: break-word;
				animation: msgPop 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
			}
			@keyframes msgPop {
				from { opacity: 0; transform: translateY(8px) scale(0.96); }
				to { opacity: 1; transform: translateY(0) scale(1); }
			}
			.chat-msg-bubble.outgoing {
				align-self: flex-end;
				background: var(--chat-user-bg);
				color: var(--chat-user-text);
				border-bottom-left-radius: 4px;
				box-shadow: 0 4px 14px rgba(0,0,0,0.08);
			}
			.chat-msg-bubble.incoming {
				align-self: flex-start;
				border-bottom-right-radius: 4px;
				box-shadow: 0 2px 8px rgba(0,0,0,0.04);
			}
			.chat-msg-bubble.incoming.ai-bubble {
				background: var(--chat-ai-bg);
				color: var(--chat-ai-text);
				border: 1px solid var(--chat-ai-border);
			}
			.chat-msg-bubble.incoming.admin-bubble {
				background: var(--chat-admin-bg);
				color: var(--chat-admin-text);
				border: 1px solid var(--chat-admin-border);
				border-left: 3px solid var(--chat-admin-badge);
			}
			.chat-msg-bubble.incoming.welcome-bubble {
				background: var(--chat-ai-bg);
				color: var(--chat-ai-text);
				border: 1px solid var(--chat-ai-border);
			}
			.chat-msg-sender-badge {
				font-size: 0.78rem;
				font-weight: 800;
				margin-bottom: 4px;
				display: flex;
				align-items: center;
				gap: 4px;
			}
			.chat-msg-bubble.incoming.ai-bubble .chat-msg-sender-badge {
				color: var(--chat-ai-badge);
			}
			.chat-msg-bubble.incoming.admin-bubble .chat-msg-sender-badge {
				color: var(--chat-admin-badge);
			}
			.chat-msg-time {
				font-size: 0.68rem;
				margin-top: 5px;
				opacity: 0.75;
				text-align: left;
				direction: ltr;
			}

			/* Typing Indicator Animation */
			.chat-typing-bubble {
				align-self: flex-start;
				background: #ffffff;
				border: 1px solid #e2e8f0;
				padding: 10px 16px;
				border-radius: 14px;
				border-bottom-right-radius: 4px;
				display: inline-flex;
				align-items: center;
				gap: 6px;
			}
			.chat-typing-dots {
				display: inline-flex;
				gap: 4px;
			}
			.chat-typing-dots span {
				width: 7px;
				height: 7px;
				background: var(--chat-primary);
				border-radius: 50%;
				animation: dotPulse 1.2s infinite ease-in-out;
			}
			.chat-typing-dots span:nth-child(2) { animation-delay: 0.2s; }
			.chat-typing-dots span:nth-child(3) { animation-delay: 0.4s; }
			@keyframes dotPulse {
				0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
				40% { transform: scale(1); opacity: 1; }
			}

			/* Chat Footer & Inputs */
			.chat-footer {
				padding: 14px 16px;
				background: #ffffff;
				border-top: 1px solid #f1f5f9;
			}
			.chat-contact-fields {
				display: flex;
				flex-direction: column;
				gap: 8px;
				margin-bottom: 8px;
				transition: all 0.3s ease;
			}
			.chat-contact-fields.collapsed {
				display: none;
			}
			.chat-input-row {
				display: flex;
				gap: 8px;
				align-items: center;
			}
			.chat-input-row input,
			.chat-contact-fields input {
				width: 100%;
				box-sizing: border-box;
				border: 1.5px solid #e2e8f0;
				border-radius: 10px;
				padding: 8px 12px;
				font-family: inherit;
				font-size: 0.85rem;
				color: #1e293b;
				background: #f8fafc;
				transition: all 0.2s ease;
			}
			.chat-msg-textarea {
				flex: 1;
				box-sizing: border-box;
				border: 1.5px solid #e2e8f0;
				border-radius: 10px;
				padding: 9px 12px;
				font-family: inherit;
				font-size: 0.88rem;
				color: #1e293b;
				background: #f8fafc;
				resize: none;
				min-height: 44px;
				max-height: 100px;
				transition: all 0.2s ease;
			}
			.chat-input-row input:focus,
			.chat-contact-fields input:focus,
			.chat-msg-textarea:focus {
				outline: none;
				border-color: var(--chat-primary);
				background: #fff;
				box-shadow: 0 0 0 3px rgba(var(--chat-primary-rgb), 0.12);
			}
			.chat-send-btn {
				background: var(--chat-primary);
				color: #fff;
				border: none;
				border-radius: 10px;
				width: 44px;
				height: 44px;
				flex-shrink: 0;
				cursor: pointer;
				display: flex;
				align-items: center;
				justify-content: center;
				transition: all 0.2s;
				box-shadow: 0 4px 12px rgba(var(--chat-primary-rgb), 0.3);
			}
			.chat-send-btn svg {
				width: 20px;
				height: 20px;
				fill: currentColor;
			}
			.chat-send-btn:hover {
				opacity: 0.95;
				transform: scale(1.04);
			}
			.chat-send-btn:disabled {
				opacity: 0.5;
				cursor: not-allowed;
				transform: none;
			}

			/* Mobile Responsiveness for Storefront Chat */
			@media (max-width: 768px) {
				.support-chat-wrapper {
					bottom: 75px !important;
				}
				.support-chat-wrapper.pos-left {
					left: 15px !important;
				}
				.support-chat-wrapper.pos-right {
					right: 15px !important;
				}
				.support-chat-btn .support-chat-label {
					display: none !important;
				}
				.support-chat-btn {
					padding: 14px !important;
					border-radius: 50% !important;
				}
				.support-chat-window {
					bottom: 140px !important;
					left: 15px !important;
					right: 15px !important;
					width: auto !important;
					max-height: calc(100vh - 160px) !important;
				}
				.chat-body-scroll {
					height: 260px !important;
				}
			}
			.mob-bar-item {
				display: flex;
				flex-direction: column;
				align-items: center;
				gap: 3px;
				color: #64748b;
				text-decoration: none;
				font-size: 0.72rem;
				font-weight: 600;
				position: relative;
			}
			.mob-bar-item.active, .mob-bar-item:hover {
				color: var(--sp-accent);
			}
			.mob-bar-item svg {
				width: 22px;
				height: 22px;
				fill: currentColor;
			}
			.mob-cart-badge {
				position: absolute;
				top: -4px;
				right: -6px;
				background: #ef4444;
				color: #fff;
				border-radius: 10px;
				font-size: 0.65rem;
				padding: 1px 5px;
				font-weight: 800;
			}

			/* Modern Store Footer */
			.modern-store-footer {
				background: #ffffff;
				border: 1px solid var(--sp-border);
				border-radius: var(--sp-radius);
				padding: 35px 25px 20px;
				margin-top: 40px;
				box-shadow: var(--sp-shadow);
			}
			.footer-grid {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
				gap: 25px;
				margin-bottom: 25px;
			}
			.footer-col h4 {
				margin-top: 0;
				margin-bottom: 14px;
				font-size: 1.05rem;
				font-weight: 800;
				color: #0f172a;
			}
			.footer-col p {
				color: #64748b;
				font-size: 0.88rem;
				line-height: 1.7;
			}
			.footer-col ul {
				list-style: none;
				padding: 0;
				margin: 0;
			}
			.footer-col ul li {
				margin-bottom: 8px;
			}
			.footer-col ul li a {
				color: #475569;
				text-decoration: none;
				font-size: 0.88rem;
				transition: color 0.2s;
			}
			.footer-col ul li a:hover {
				color: var(--sp-accent);
			}
			.footer-bottom {
				border-top: 1px solid #f1f5f9;
				padding-top: 18px;
				display: flex;
				justify-content: space-between;
				align-items: center;
				flex-wrap: wrap;
				gap: 10px;
				font-size: 0.82rem;
				color: #94a3b8;
			}

			/* Responsive Adjustments */
			@media (max-width: 860px) {
				.store-topbar { flex-direction: column; gap: 8px; text-align: center; }
				.store-header-search { order: 3; max-width: 100%; width: 100%; }
				.store-navbar { display: none; }
				.btn-mobile-toggle { display: block; }
				.mobile-bottom-bar { display: flex; }
				.modern-shop-root { margin-bottom: 110px; }
			}
			@media (max-width: 640px) {
				.modern-shop-hero { padding: 30px 18px; }
				.modern-shop-hero h1 { font-size: 1.7rem; }
				.modern-shop-hero p { font-size: 0.92rem; }
				.desktop-only {
					display: none !important;
				}
				.products-grid.cols-1 .product-card {
					flex-direction: column;
					padding: 0;
					gap: 0;
				}
				.products-grid.cols-1 .card-img-wrap {
					width: 100%;
					min-width: 100%;
					height: 220px;
					border-radius: 0;
				}
				.products-grid.cols-1 .card-body {
					padding: 12px;
				}
				.products-grid.cols-1 .card-actions {
					max-width: 100%;
				}
				.products-grid.cols-2 {
					grid-template-columns: repeat(2, 1fr) !important;
					gap: 10px;
				}
				.products-grid.cols-2 .card-img-wrap {
					height: 160px;
				}
				.card-body { padding: 12px; }
				.card-title { font-size: 0.85rem; height: 38px; line-height: 1.45; }
				.card-new-price { font-size: 1.05rem; }
				.btn-card-quick { display: none; }
				.card-actions { grid-template-columns: 1fr; }
				.btn-card-buy { padding: 8px; font-size: 0.8rem; }
			}
		</style>

		<div class="modern-shop-root" id="modernShopApp">

			<?php if ( ! empty( $settings['show_top_bar'] ) ) : ?>
				<!-- Modern Top Announcement Bar -->
				<div class="store-topbar">
					<div class="store-topbar-notice">
						<span class="store-badge-live">⚡ ویژه</span>
						<span><?php echo esc_html( $settings['top_bar_notice'] ); ?></span>
					</div>
					<div class="store-topbar-links">
						<?php if ( ! empty( $settings['contact_phone'] ) ) : ?>
							<a href="tel:<?php echo esc_attr( $settings['contact_phone'] ); ?>" class="store-topbar-link">
								<span>📞</span>
								<span>پشتیبانی: <?php echo esc_html( $settings['contact_phone'] ); ?></span>
							</a>
						<?php endif; ?>
						<span style="color:#475569;">|</span>
						<span>🕒 <?php echo esc_html( $settings['support_hours'] ); ?></span>
					</div>
				</div>
			<?php endif; ?>

			<!-- Main Store Header -->
			<div class="store-main-header">
				<a href="#" class="store-brand" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;">
					<div class="store-brand-logo">
						<svg viewBox="0 0 24 24"><path d="M19 6h-2c0-2.76-2.24-5-5-5S7 3.24 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-7-3c1.66 0 3 1.34 3 3H9c0-1.66 1.34-3 3-3zm7 17H5V8h14v12zm-7-8c-1.66 0-3-1.34-3-3H7c0 2.76 2.24 5 5 5s5-2.24 5-5h-2c0 1.66-1.34 3-3 3z"/></svg>
					</div>
					<div class="store-brand-info">
						<h2><?php echo esc_html( $settings['shop_title'] ); ?></h2>
						<span><?php echo esc_html( $settings['shop_subtitle'] ); ?></span>
					</div>
				</a>

				<!-- Smart Header Search -->
				<div class="store-header-search">
					<input type="text" id="headerLiveSearch" placeholder="جستجو در بین هزاران کالای متنوع و باکیفیت...">
					<span class="search-clear-btn" id="searchClearBtn">✕</span>
					<svg class="search-icon" viewBox="0 0 24 24"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"/></svg>
				</div>

				<!-- Header Action Buttons -->
				<div class="store-header-actions">
					<?php if ( current_user_can( 'manage_options' ) ) : ?>
						<a href="<?php echo esc_url( $scraper_admin_url ); ?>" class="btn-header-action" title="مدیریت فروشگاه">
							<span>⚙️</span>
							<span>مدیریت</span>
						</a>
					<?php endif; ?>

					<a href="<?php echo esc_url( $account_url ); ?>" class="btn-header-action">
						<span>👤</span>
						<span>حساب کاربری</span>
					</a>

					<button type="button" class="btn-header-action btn-header-cart" id="headerCartBtn">
						<span>🛒</span>
						<span>سبد خرید</span>
						<span class="cart-count-badge" id="headerCartCount">۰</span>
					</button>

					<button type="button" class="btn-mobile-toggle" id="mobileMenuToggle" title="منو">
						<span style="font-size:1.4rem; line-height:1;">☰</span>
					</button>
				</div>
			</div>

			<!-- Modern Navigation Bar -->
			<div class="store-navbar">
				<div class="store-nav-right">
					<!-- Mega Menu Trigger for Categories -->
					<button type="button" class="nav-mega-btn" id="megaCategoriesBtn">
						<span>☰</span>
						<span>دسته‌بندی کالاها</span>
						<span style="font-size:0.75rem;">▼</span>
					</button>

					<a href="#" class="nav-item-link active" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;">
						<span>🏠</span>
						<span>صفحه اصلی</span>
					</a>

					<a href="#productsAnchor" class="nav-item-link" onclick="document.getElementById('productsGrid').scrollIntoView({behavior:'smooth'}); return false;">
						<span>🛍️</span>
						<span>همه محصولات (<?php echo self::to_fa_num( count( $products ) ); ?>)</span>
					</a>

					<a href="#productsAnchor" class="nav-item-link" onclick="document.getElementById('sortSelector').value='price-asc'; document.getElementById('sortSelector').dispatchEvent(new Event('change')); document.getElementById('productsGrid').scrollIntoView({behavior:'smooth'}); return false;">
						<span>🔥</span>
						<span>پیشنهادهای اقتصادی</span>
					</a>

					<?php if ( ! empty( $settings['contact_phone'] ) ) : ?>
						<a href="tel:<?php echo esc_attr( $settings['contact_phone'] ); ?>" class="nav-item-link">
							<span>📞</span>
							<span>تماس با ما</span>
						</a>
					<?php endif; ?>
				</div>

				<div class="store-nav-left" style="display:flex; align-items:center; gap:8px;">
					<span style="font-size:0.82rem; color:#10b981; font-weight:700; display:flex; align-items:center; gap:5px;">
						<span style="width:8px; height:8px; background:#10b981; border-radius:50%; display:inline-block; box-shadow:0 0 8px #10b981;"></span>
						فروشگاه آنلاین • ارسال فوری
					</span>
				</div>

				<!-- Categories Dropdown Panel -->
				<div class="mega-dropdown-panel" id="megaDropdownPanel">
					<div style="padding:8px 10px; font-weight:800; font-size:0.85rem; color:#64748b; border-bottom:1px solid #f1f5f9;">دسته‌بندی‌های کالا:</div>
					<div class="dropdown-cat-item" data-cat="all">
						<span>همه دسته‌ها</span>
						<span class="filter-pill-badge"><?php echo self::to_fa_num( count( $products ) ); ?></span>
					</div>
					<?php foreach ( $categories as $cat_name => $cat_count ) : ?>
						<div class="dropdown-cat-item" data-cat="<?php echo esc_attr( $cat_name ); ?>">
							<span>📂 <?php echo esc_html( $cat_name ); ?></span>
							<span class="filter-pill-badge"><?php echo self::to_fa_num( $cat_count ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Flash Sale Promotional Bar -->
			<div class="flash-sale-bar">
				<div class="flash-sale-title">
					<span class="flash-icon">⚡</span>
					<strong>پیشنهادات شگفت‌انگیز امروز</strong>
					<span style="font-size:0.82rem; opacity:0.9;">(فرصت ویژه با تخفیف‌های استثنایی)</span>
				</div>
				<div class="flash-timer" id="flashTimer">
					<span class="timer-box" id="timerHours">۰۸</span> :
					<span class="timer-box" id="timerMinutes">۴۲</span> :
					<span class="timer-box" id="timerSeconds">۱۵</span>
				</div>
			</div>

			<!-- Hero Banner -->
			<div class="modern-shop-hero">
				<h1><?php echo esc_html( $settings['shop_title'] ); ?></h1>
				<p><?php echo esc_html( $settings['shop_subtitle'] ); ?></p>
				
				<?php if ( ! empty( $settings['show_features_banner'] ) ) : ?>
					<div class="hero-features-bar">
						<div class="hero-feature-item"><span>🚀</span> ارسال سریع سراسر کشور</div>
						<div class="hero-feature-item"><span>💎</span> تضمین ۱۰۰٪ اصالت فیزیکی کالا</div>
						<div class="hero-feature-item"><span>🔄</span> ضمانت ۷ روزه بازگشت وجه</div>
						<div class="hero-feature-item"><span>🛡️</span> پشتیبانی تخصصی ۲۴ ساعته</div>
					</div>
				<?php endif; ?>
			</div>

			<div id="productsAnchor"></div>

			<?php if ( empty( $products ) ) : ?>
				<!-- Clean Customer-facing Empty State -->
				<div class="shop-empty-state">
					<div class="empty-icon">📦</div>
					<h3>در حال حاضر کالایی در این بخش موجود نیست</h3>
					<p>کالاهای جدید به زودی در انبار موجود و در فروشگاه عرضه خواهند شد. برای اطلاع از موجودی مجدداً سر بزنید یا با پشتیبانی تماس حاصل فرمایید.</p>
					<?php if ( ! empty( $settings['contact_phone'] ) ) : ?>
						<a href="tel:<?php echo esc_attr( $settings['contact_phone'] ); ?>" class="btn-card-buy" style="padding:12px 28px; font-size:1rem;">
							📞 تماس با پشتیبانی فروشگاه
						</a>
					<?php endif; ?>
				</div>
			<?php else : ?>

				<!-- Toolbar -->
				<div class="shop-toolbar">
					<div class="toolbar-right">
						<span id="productCounter" style="font-weight:700; color:#475569; font-size:0.92rem;">
							نمایش <?php echo self::to_fa_num( count( $products ) ); ?> محصول فعال
						</span>
					</div>
					<div class="toolbar-left" style="display:flex; align-items:center; gap:10px;">
						<label for="sortSelector" style="font-size:0.88rem; color:#64748b;">مرتب‌سازی:</label>
						<select id="sortSelector" class="sort-select">
							<option value="default">پیش‌فرض</option>
							<option value="price-asc">ارزان‌ترین به گران‌ترین</option>
							<option value="price-desc">گران‌ترین به ارزان‌ترین</option>
							<option value="title">نام محصول (الف-ی)</option>
						</select>
					</div>
				</div>

				<!-- Category Filter Chips -->
				<div class="filter-pills-wrap" id="categoryPills">
					<div class="filter-pill active" data-cat="all">همه دسته‌ها <span class="filter-pill-badge"><?php echo self::to_fa_num( count( $products ) ); ?></span></div>
					<?php foreach ( $categories as $cat_name => $cat_count ) : ?>
						<div class="filter-pill" data-cat="<?php echo esc_attr( $cat_name ); ?>">
							<?php echo esc_html( $cat_name ); ?>
							<span class="filter-pill-badge"><?php echo self::to_fa_num( $cat_count ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>

				<!-- Products Section Toolbar (تعداد ستون‌ها و شمارش) -->
				<div class="shop-toolbar" id="shopToolbar">
					<div class="toolbar-info">
						<span id="productCounter">نمایش کالاها...</span>
						<span class="count-badge">صفحه‌بندی ۲۰ تایی</span>
					</div>
					<div class="toolbar-view-switcher">
						<span class="switcher-label">چیدمان ستون‌ها:</span>
						<button type="button" class="col-switch-btn active" data-cols="1" title="تک ستونه (پیش‌فرض)">
							<svg viewBox="0 0 24 24" fill="currentColor">
								<rect x="3" y="4" width="18" height="6" rx="2"></rect>
								<rect x="3" y="14" width="18" height="6" rx="2"></rect>
							</svg>
							<span>تک ستون</span>
						</button>
						<button type="button" class="col-switch-btn" data-cols="2" title="دو ستونه">
							<svg viewBox="0 0 24 24" fill="currentColor">
								<rect x="3" y="4" width="8" height="16" rx="2"></rect>
								<rect x="13" y="4" width="8" height="16" rx="2"></rect>
							</svg>
							<span>۲ ستون</span>
						</button>
						<button type="button" class="col-switch-btn desktop-only" data-cols="3" title="سه ستونه">
							<svg viewBox="0 0 24 24" fill="currentColor">
								<rect x="2" y="4" width="5.5" height="16" rx="1.5"></rect>
								<rect x="9.25" y="4" width="5.5" height="16" rx="1.5"></rect>
								<rect x="16.5" y="4" width="5.5" height="16" rx="1.5"></rect>
							</svg>
							<span>۳ ستون</span>
						</button>
						<button type="button" class="col-switch-btn desktop-only" data-cols="4" title="چهار ستونه">
							<svg viewBox="0 0 24 24" fill="currentColor">
								<rect x="2" y="4" width="4" height="16" rx="1"></rect>
								<rect x="7.33" y="4" width="4" height="16" rx="1"></rect>
								<rect x="12.66" y="4" width="4" height="16" rx="1"></rect>
								<rect x="18" y="4" width="4" height="16" rx="1"></rect>
							</svg>
							<span>۴ ستون</span>
						</button>
					</div>
				</div>

				<!-- Products Grid (Default cols-1) -->
				<div class="products-grid cols-1" id="productsGrid">
					<?php foreach ( $products as $p ) : ?>
						<div class="product-card" 
							data-id="<?php echo esc_attr( $p['id'] ); ?>"
							data-cat="<?php echo esc_attr( $p['category'] ); ?>" 
							data-title="<?php echo esc_attr( mb_strtolower( $p['title'] ) ); ?>"
							data-price-num="<?php echo esc_attr( $p['price'] ); ?>">
							
							<div class="card-thumb-wrap">
								<!-- Wishlist Heart Button -->
								<button type="button" class="card-wishlist-btn" data-id="<?php echo esc_attr( $p['id'] ); ?>" title="افزودن به علاقه‌مندی‌ها">
									<svg viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
								</button>

								<span class="card-stock-badge">موجود در انبار</span>

								<?php if ( $p['original_price'] > $p['price'] ) : 
									$disc = round( ( ( $p['original_price'] - $p['price'] ) / $p['original_price'] ) * 100 );
								?>
									<span class="card-discount-badge"><?php echo self::to_fa_num( $disc ); ?>٪ تخفیف</span>
								<?php endif; ?>

								<?php if ( ! empty( $p['image'] ) ) : ?>
									<img src="<?php echo esc_url( $p['image'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy" onerror="this.src='data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'300\' height=\'300\' viewBox=\'0 0 300 300\'><rect width=\'300\' height=\'300\' fill=\'%23f1f5f9\'/><text x=\'50%25\' y=\'50%25\' dominant-baseline=\'middle\' text-anchor=\'middle\' font-size=\'40\' fill=\'%23cbd5e1\'>📦</text></svg>'">
								<?php else : ?>
									<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:3.5rem;color:#cbd5e1;">📦</div>
								<?php endif; ?>
							</div>

							<div class="card-body">
								<div class="card-category"><?php echo esc_html( $p['category'] ); ?></div>
								<h3 class="card-title" title="<?php echo esc_attr( $p['title'] ); ?>"><?php echo esc_html( $p['title'] ); ?></h3>
								
								<div class="card-pricing-block">
									<div class="pricing-row-top">
										<?php if ( $p['original_price'] > 0 && $p['original_price'] > $p['price'] ) : ?>
											<span class="card-old-price"><?php echo self::to_fa_num( number_format( $p['original_price'] ) ); ?> <?php echo esc_html( $settings['currency_symbol'] ); ?></span>
										<?php else : ?>
											<span></span>
										<?php endif; ?>

										<?php if ( ! empty( $settings['show_special_badge'] ) ) : ?>
											<span class="card-special-tag">✨ پیشنهاد ویژه</span>
										<?php endif; ?>
									</div>
									<div class="card-new-price"><?php echo esc_html( $p['price_formatted'] ); ?></div>
								</div>

								<div class="card-actions">
									<button type="button" class="btn-card-quick open-quick-view" 
										data-title="<?php echo esc_attr( $p['title'] ); ?>"
										data-price="<?php echo esc_attr( $p['price_formatted'] ); ?>"
										data-old-price="<?php echo esc_attr( $p['original_price'] > $p['price'] ? self::to_fa_num( number_format( $p['original_price'] ) ) . ' ' . $settings['currency_symbol'] : '' ); ?>"
										data-img="<?php echo esc_url( $p['image'] ); ?>"
										data-cat="<?php echo esc_attr( $p['category'] ); ?>"
										data-desc="<?php echo esc_attr( $p['description'] ); ?>">
										مشاهده مشخصات
									</button>
									<button type="button" class="btn-card-buy add-to-cart-btn"
										data-id="<?php echo esc_attr( $p['id'] ); ?>"
										data-title="<?php echo esc_attr( $p['title'] ); ?>"
										data-price="<?php echo esc_attr( $p['price'] ); ?>"
										data-price-txt="<?php echo esc_attr( $p['price_formatted'] ); ?>"
										data-img="<?php echo esc_url( $p['image'] ); ?>">
										افزودن به سبد
									</button>
								</div>
							</div>
						</div>
					<?php endforeach; ?>

					<!-- No Results Message -->
					<div class="search-no-results" id="searchNoResults" style="display:none;">
						<div style="font-size:3.5rem; margin-bottom:8px;">🔍</div>
						<h4>متأسفانه کالایی با عبارت جستجو شده یافت نشد</h4>
						<p>لطفاً املای عبارت را بررسی کنید یا دسته‌بندی دیگری را انتخاب نمایید.</p>
						<button type="button" class="btn-card-quick" id="resetSearchBtn" style="padding:10px 22px;">نمایش همه محصولات</button>
					</div>
				</div>

				<!-- Pagination Controls (صفحه‌بندی ۲۰ تایی) -->
				<div class="shop-pagination-wrap" id="shopPaginationWrap"></div>

			<?php endif; ?>

			<!-- Quick View Modal -->
			<div class="modal-overlay" id="quickViewModal">
				<div class="modal-content">
					<div class="modal-close" id="closeQuickView">✕</div>
					<div class="modal-inner">
						<div class="modal-col-img">
							<img src="" id="modalImg" alt="تصویر کالا">
						</div>
						<div class="modal-col-info">
							<div style="display:flex; gap:10px; margin-bottom:10px;">
								<span id="modalCat" style="background:#f1f5f9; padding:4px 12px; border-radius:6px; font-size:0.8rem; font-weight:700; color:#475569;"></span>
								<span style="background:#ecfdf5; color:#059669; padding:4px 10px; border-radius:6px; font-size:0.8rem; font-weight:700;">✨ آماده ارسال فوری</span>
							</div>
							<h2 id="modalTitle" style="font-size:1.3rem; font-weight:900; margin-bottom:15px; line-height:1.5;"></h2>
							
							<div style="margin-bottom:15px;">
								<div id="modalOldPrice" style="font-size:0.9rem; color:#94a3b8; text-decoration:line-through; margin-bottom:2px;"></div>
								<div id="modalPrice" style="font-size:1.6rem; font-weight:900; color:#059669;"></div>
							</div>

							<p id="modalDesc" style="color:#64748b; font-size:0.92rem; line-height:1.8; max-height:160px; overflow-y:auto; margin-bottom:20px;"></p>
							
							<div class="modal-qty-control">
								<label style="font-weight:700; font-size:0.9rem;">تعداد:</label>
								<div class="qty-picker">
									<button type="button" id="modalQtyMinus">-</button>
									<span id="modalQtyNum">۱</span>
									<button type="button" id="modalQtyPlus">+</button>
								</div>
							</div>

							<div style="display:flex; gap:12px; align-items:center; margin-bottom:15px;">
								<button type="button" class="btn-card-buy" id="modalAddToCartBtn" style="flex:1; padding:12px; font-size:1rem;">
									افزودن به سبد خرید
								</button>
							</div>

							<div style="color:#059669; font-size:0.85rem; font-weight:700; display:flex; align-items:center; gap:6px;">
								<span>🛡️</span> ۷ روز ضمانت بازگشت و تعویض بدون قید و شرط
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Cart Drawer -->
			<div class="cart-drawer-overlay" id="cartDrawerOverlay"></div>
			<div class="cart-drawer" id="cartDrawer">
				<div class="cart-drawer-header">
					<h3 style="margin:0; font-size:1.2rem; font-weight:800;">سبد خرید شما</h3>
					<span id="closeCartDrawer" style="cursor:pointer; font-size:1.4rem; color:#64748b;">✕</span>
				</div>

				<!-- Free Shipping Progress -->
				<div class="cart-shipping-progress" id="cartShippingBox">
					<div id="shippingProgressText">در حال محاسبه هزینه ارسال...</div>
					<div class="progress-track">
						<div class="progress-fill" id="shippingProgressFill"></div>
					</div>
				</div>

				<div class="cart-drawer-items" id="cartItemsList">
					<!-- Injected by JS -->
				</div>

				<div class="cart-drawer-footer">
					<div class="cart-total-row">
						<span>مجموع خرید:</span>
						<span id="cartTotalPrice" style="color:#059669;">۰ <?php echo esc_html( $settings['currency_symbol'] ); ?></span>
					</div>
					<?php
					$checkout_url  = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#';
					$cart_page_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#';
					?>
					<a href="<?php echo esc_url( $checkout_url ); ?>" class="btn-card-buy" id="btnGoToCheckout" style="display:block; width:100%; text-align:center; padding:14px; font-size:1.05rem; text-decoration:none; font-weight:800;">
						تکمیل سفارش و تسویه حساب ➔
					</a>
					<a href="<?php echo esc_url( $cart_page_url ); ?>" style="display:block; text-align:center; margin-top:10px; font-size:0.82rem; color:#64748b; text-decoration:underline;">
						مشاهده و ویرایش سبد خرید ووکامرس
					</a>
				</div>
			</div>

			<!-- Toast Notification -->
			<div class="store-toast" id="storeToast">
				<span class="toast-icon">✓</span>
				<span id="toastMessage">کالا به سبد خرید اضافه شد</span>
			</div>

			<!-- Mobile Bottom App Navigation Bar -->
			<div class="mobile-bottom-bar">
				<a href="#" class="mob-bar-item active" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;">
					<svg viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
					<span>خانه</span>
				</a>
				<a href="#" class="mob-bar-item" id="mobBarCatsBtn">
					<svg viewBox="0 0 24 24"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>
					<span>دسته‌ها</span>
				</a>
				<a href="#" class="mob-bar-item" id="mobBarSearchBtn">
					<svg viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
					<span>جستجو</span>
				</a>
				<a href="#" class="mob-bar-item" id="mobBarCartBtn">
					<div style="position:relative; display:inline-block;">
						<svg viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/></svg>
						<span class="mob-cart-badge" id="mobCartCount">۰</span>
					</div>
					<span>سبد خرید</span>
				</a>
			</div>

			<?php if ( ! empty( $settings['enable_support_chat'] ) ) : ?>
			<!-- Floating Online Support Chat Widget -->
			<div class="support-chat-wrapper <?php echo 'right' === ( $settings['chat_button_position'] ?? 'left' ) ? 'pos-right' : 'pos-left'; ?> theme-<?php echo esc_attr( $settings['chat_theme'] ?? 'royal-blue' ); ?> btn-<?php echo esc_attr( $settings['chat_button_style'] ?? 'pill-label' ); ?>" id="supportChatWrap">
				
				<!-- Trigger Button (Custom styled per selected design) -->
				<button type="button" class="support-chat-btn" id="supportChatTrigger" aria-label="پشتیبانی آنلاین">
					<span class="support-chat-badge"></span>
					<?php if ( ( $settings['chat_button_style'] ?? 'pill-label' ) === 'avatar-ring' ) : ?>
						<span style="font-size:1.6rem; line-height:1;">👩‍💼</span>
					<?php else : ?>
						<svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
					<?php endif; ?>
					<span class="support-chat-label">پشتیبانی آنلاین</span>
				</button>

				<!-- Popup Chat Window Box -->
				<div class="support-chat-window" id="supportChatBox">
					<!-- Header Bar -->
					<div class="chat-hdr">
						<div class="chat-hdr-agent">
							<div class="chat-agent-avatar">👩‍💼</div>
							<div class="chat-hdr-info">
								<h4><?php echo esc_html( $settings['chat_window_title'] ?? 'پشتیبانی آنلاین فروشگاه' ); ?></h4>
								<span><span style="color:#10b981;">●</span> آنلاین • پاسخگویی هوشمند</span>
							</div>
						</div>
						<button type="button" class="chat-close-btn" id="supportChatClose" aria-label="بستن">✕</button>
					</div>

					<!-- Customer Mini Bar (Collapsed view when customer info is saved) -->
					<div class="chat-user-bar" id="chatUserBar" style="display:none;">
						<div>👤 گفتگو با: <strong id="chatUserBarName">مشتری</strong> <span id="chatUserBarPhone" dir="ltr" style="font-weight:700;"></span></div>
						<button type="button" class="chat-user-edit-btn" id="chatUserEditBtn">ویرایش مشخصات</button>
					</div>

					<!-- Continuous Messenger Scroll Area -->
					<div class="chat-body-scroll" id="supportChatBody">
						<div class="chat-msg-bubble incoming welcome-bubble">
							<div class="chat-msg-sender-badge">🌸 پشتیبانی فروشگاه</div>
							<div><?php echo nl2br( esc_html( $settings['chat_welcome_message'] ?? 'سلام! خوش آمدید 👋 هرگونه سوالی درباره محصولات یا ثبت سفارش دارید بنویسید تا همکاران ما سریعاً پاسخ دهند.' ) ); ?></div>
							<div class="chat-msg-time"><?php echo esc_html( date_i18n( 'H:i' ) ); ?></div>
						</div>
					</div>

					<!-- Typing Indicator Animation -->
					<div id="chatTypingIndicator" style="display:none; padding:0 18px 8px;">
						<div class="chat-typing-bubble">
							<span style="font-size:0.78rem; font-weight:700; color:var(--chat-primary);" id="chatTypingText">🤖 در حال نگارش پاسخ...</span>
							<div class="chat-typing-dots"><span></span><span></span><span></span></div>
						</div>
					</div>

					<!-- Message Composer Footer -->
					<div class="chat-footer">
						<form id="supportChatForm" onsubmit="return false;">
							<!-- Contact inputs (Name, Phone, Email, Subject) shown if not filled -->
							<div class="chat-contact-fields" id="chatContactFields">
								<?php if ( ! empty( $settings['chat_field_name_enable'] ) ) : ?>
									<div>
										<input type="text" id="chatNameInput" placeholder="نام و نام خانوادگی <?php echo ! empty( $settings['chat_field_name_required'] ) ? '(الزامی)*' : '(اختیاری)'; ?>" <?php echo ! empty( $settings['chat_field_name_required'] ) ? 'required' : ''; ?> maxlength="60">
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $settings['chat_field_phone_enable'] ) ) : ?>
									<div>
										<input type="tel" id="chatPhoneInput" placeholder="شماره موبایل / تماس <?php echo ! empty( $settings['chat_field_phone_required'] ) ? '(الزامی)*' : '(اختیاری)'; ?>" <?php echo ! empty( $settings['chat_field_phone_required'] ) ? 'required' : ''; ?> maxlength="20" dir="ltr">
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $settings['chat_field_email_enable'] ) ) : ?>
									<div>
										<input type="email" id="chatEmailInput" placeholder="آدرس ایمیل <?php echo ! empty( $settings['chat_field_email_required'] ) ? '(الزامی)*' : '(اختیاری)'; ?>" <?php echo ! empty( $settings['chat_field_email_required'] ) ? 'required' : ''; ?> maxlength="80" dir="ltr">
									</div>
								<?php endif; ?>

								<?php if ( ! empty( $settings['chat_field_subject_enable'] ) ) : ?>
									<div>
										<input type="text" id="chatSubjectInput" placeholder="موضوع سوال <?php echo ! empty( $settings['chat_field_subject_required'] ) ? '(الزامی)*' : '(اختیاری)'; ?>" <?php echo ! empty( $settings['chat_field_subject_required'] ) ? 'required' : ''; ?> maxlength="100">
									</div>
								<?php endif; ?>
							</div>

							<!-- Continuous Message Textarea & Send Button -->
							<div class="chat-input-row">
								<textarea id="chatMsgInput" class="chat-msg-textarea" placeholder="پیام یا سوال شما... (Enter)" rows="1" required maxlength="1200"></textarea>
								<button type="submit" id="chatSendBtn" class="chat-send-btn" aria-label="ارسال">
									<svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<!-- Modern Store Footer -->
			<div class="modern-store-footer">
				<div class="footer-grid">
					<div class="footer-col">
						<h4><?php echo esc_html( $settings['shop_title'] ); ?></h4>
						<p><?php echo esc_html( $settings['shop_subtitle'] ); ?></p>
						<p style="color:#059669; font-weight:700;">🟢 فروشگاه آنلاین و سفارش‌گیری فعال است</p>
					</div>
					<div class="footer-col">
						<h4>دسترسی سریع</h4>
						<ul>
							<li><a href="#" onclick="window.scrollTo({top:0,behavior:'smooth'}); return false;">صفحه اصلی فروشگاه</a></li>
							<li><a href="#productsAnchor" onclick="document.getElementById('productsGrid').scrollIntoView({behavior:'smooth'}); return false;">فهرست کامل کالاها</a></li>
							<li><a href="<?php echo esc_url( $account_url ); ?>">پیگیری سفارشات و حساب کاربری</a></li>
						</ul>
					</div>
					<div class="footer-col">
						<h4>دسته‌بندی‌های برتر</h4>
						<ul>
							<?php 
							$top_cats = array_slice( $categories, 0, 5, true );
							foreach ( $top_cats as $tc_name => $tc_count ) : 
							?>
								<li><a href="#" class="footer-cat-link" data-cat="<?php echo esc_attr( $tc_name ); ?>"><?php echo esc_html( $tc_name ); ?> (<?php echo self::to_fa_num( $tc_count ); ?>)</a></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="footer-col">
						<h4>تماس و پشتیبانی</h4>
						<p>ساعات پاسخگویی: <?php echo esc_html( $settings['support_hours'] ); ?></p>
						<?php if ( ! empty( $settings['contact_phone'] ) ) : ?>
							<p style="font-size:1.1rem; font-weight:800; color:#0f172a;">📞 <?php echo esc_html( $settings['contact_phone'] ); ?></p>
						<?php endif; ?>
						<div style="background:#f1f5f9; padding:10px 14px; border-radius:10px; font-size:0.82rem; color:#475569;">
							✨ ضمانت ۱۰۰٪ سلامت فیزیکی و بازگشت کالا
						</div>
					</div>
				</div>
				<div class="footer-bottom">
					<div>تمامی حقوق این وب‌سایت برای فروشگاه آنلاین محفوظ است © <?php echo esc_html( date( 'Y' ) ); ?></div>
					<div>تجربه خرید مدرن و هوشمند آنلاین</div>
				</div>
			</div>

		</div>

		<!-- Interactive Storefront Script -->
		<script>
		(function() {
			const app = document.getElementById('modernShopApp');
			if (!app) return;

			const freeShippingThreshold = <?php echo (int) ( $settings['free_shipping_threshold'] ?? 400000 ); ?>;
			const currencySymbol = ' <?php echo esc_js( $settings['currency_symbol'] ); ?>';

			let cart = [];
			try {
				const saved = localStorage.getItem('modern_shop_cart');
				if (saved) cart = JSON.parse(saved);
			} catch(e) {}

			let wishlist = [];
			try {
				const savedW = localStorage.getItem('modern_shop_wishlist');
				if (savedW) wishlist = JSON.parse(savedW);
			} catch(e) {}

			function toFa(num) {
				const fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
				return String(num).replace(/\d/g, d => fa[d]);
			}

			function formatPrice(num) {
				return toFa(new Intl.NumberFormat('en-US').format(Math.round(num))) + currencySymbol;
			}

			function showToast(msg) {
				const toast = document.getElementById('storeToast');
				const txt = document.getElementById('toastMessage');
				if (!toast || !txt) return;
				txt.textContent = msg;
				toast.classList.add('show');
				setTimeout(() => toast.classList.remove('show'), 2800);
			}

			// Flash Deals Countdown Timer
			let timerSecondsTotal = 8 * 3600 + 42 * 60 + 15;
			setInterval(() => {
				if (timerSecondsTotal > 0) timerSecondsTotal--;
				const h = Math.floor(timerSecondsTotal / 3600);
				const m = Math.floor((timerSecondsTotal % 3600) / 60);
				const s = timerSecondsTotal % 60;
				const hEl = document.getElementById('timerHours');
				const mEl = document.getElementById('timerMinutes');
				const sEl = document.getElementById('timerSeconds');
				if (hEl) hEl.textContent = toFa(String(h).padStart(2, '0'));
				if (mEl) mEl.textContent = toFa(String(m).padStart(2, '0'));
				if (sEl) sEl.textContent = toFa(String(s).padStart(2, '0'));
			}, 1000);

			// Mega menu toggle
			const megaBtn = document.getElementById('megaCategoriesBtn');
			const megaPanel = document.getElementById('megaDropdownPanel');
			if (megaBtn && megaPanel) {
				megaBtn.addEventListener('click', (e) => {
					e.stopPropagation();
					megaPanel.classList.toggle('open');
				});
				document.addEventListener('click', () => {
					megaPanel.classList.remove('open');
				});
			}

			// WooCommerce Cart Configuration
			const scraperCartConfig = {
				ajaxUrl: '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>',
				nonce: '<?php echo esc_js( wp_create_nonce( 'scraper_cart_nonce' ) ); ?>',
				checkoutUrl: '<?php echo esc_url( function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#' ); ?>'
			};

			// Pagination and Column Layout Configuration
			const PAGE_SIZE = 20; // صفحه‌بندی ۲۰ تایی
			let currentPage = 1;
			let currentCat = 'all';
			let searchQuery = '';

			const headerSearch = document.getElementById('headerLiveSearch');
			const clearBtn = document.getElementById('searchClearBtn');
			const noResults = document.getElementById('searchNoResults');
			const resetBtn = document.getElementById('resetSearchBtn');
			const paginationWrap = document.getElementById('shopPaginationWrap');
			const productsGrid = document.getElementById('productsGrid');
			const colSwitchBtns = app.querySelectorAll('.col-switch-btn');

			// Column Switcher Logic (Default: cols-1)
			function setColumns(cols) {
				colSwitchBtns.forEach(btn => {
					btn.classList.toggle('active', btn.getAttribute('data-cols') === String(cols));
				});
				if (productsGrid) {
					productsGrid.className = 'products-grid cols-' + cols;
				}
				try {
					localStorage.setItem('scraped_shop_cols', cols);
				} catch(e) {}
			}

			colSwitchBtns.forEach(btn => {
				btn.addEventListener('click', () => {
					const cols = btn.getAttribute('data-cols') || '1';
					setColumns(cols);
				});
			});

			// Load preferred columns or default to 1
			let initialCols = '1';
			try {
				initialCols = localStorage.getItem('scraped_shop_cols') || '1';
			} catch(e) {}
			setColumns(initialCols);

			// Render Pagination Navigation (صفحه‌بندی ۲۰ تایی)
			function renderPagination(totalPages, page) {
				if (!paginationWrap) return;
				if (totalPages <= 1) {
					paginationWrap.innerHTML = '';
					paginationWrap.style.display = 'none';
					return;
				}

				paginationWrap.style.display = 'flex';
				let html = '';

				// Prev Button
				html += `<button type="button" class="page-btn page-prev" ${page === 1 ? 'disabled' : ''} data-page="${page - 1}">« قبلی</button>`;

				// Page Numbers
				for (let p = 1; p <= totalPages; p++) {
					if (p === 1 || p === totalPages || (p >= page - 2 && p <= page + 2)) {
						html += `<button type="button" class="page-btn ${p === page ? 'active' : ''}" data-page="${p}">${toFa(p)}</button>`;
					} else if (p === page - 3 || p === page + 3) {
						html += `<span style="padding:0 6px; color:#94a3b8; font-weight:700;">...</span>`;
					}
				}

				// Next Button
				html += `<button type="button" class="page-btn page-next" ${page === totalPages ? 'disabled' : ''} data-page="${page + 1}">بعدی »</button>`;

				paginationWrap.innerHTML = html;

				// Attach events to pagination buttons
				paginationWrap.querySelectorAll('.page-btn').forEach(btn => {
					btn.addEventListener('click', () => {
						const targetPage = parseInt(btn.getAttribute('data-page'));
						if (targetPage && targetPage !== currentPage && targetPage >= 1 && targetPage <= totalPages) {
							applyFilters(targetPage);
							const toolbar = document.getElementById('shopToolbar');
							if (toolbar) {
								toolbar.scrollIntoView({ behavior: 'smooth', block: 'start' });
							}
						}
					});
				});
			}

			function applyFilters(page = 1) {
				const allCards = Array.from(app.querySelectorAll('.product-card'));
				const matchedCards = allCards.filter(card => {
					const cat = card.getAttribute('data-cat');
					const title = card.getAttribute('data-title');
					const matchCat = (currentCat === 'all' || cat === currentCat);
					const matchSearch = (!searchQuery || title.includes(searchQuery));
					return matchCat && matchSearch;
				});

				const totalMatched = matchedCards.length;
				const totalPages = Math.ceil(totalMatched / PAGE_SIZE) || 1;

				if (page > totalPages) page = totalPages;
				if (page < 1) page = 1;
				currentPage = page;

				const startIdx = (currentPage - 1) * PAGE_SIZE;
				const endIdx = startIdx + PAGE_SIZE;

				// Hide all cards first, then show current page slice
				allCards.forEach(card => card.style.display = 'none');
				const pageCards = matchedCards.slice(startIdx, endIdx);
				pageCards.forEach(card => card.style.display = 'flex');

				const counter = document.getElementById('productCounter');
				if (counter) {
					if (totalMatched === 0) {
						counter.textContent = 'هیچ کالایی یافت نشد';
					} else {
						const fromNum = toFa(startIdx + 1);
						const toNum = toFa(Math.min(endIdx, totalMatched));
						const totalNum = toFa(totalMatched);
						counter.textContent = `نمایش ${fromNum} تا ${toNum} از ${totalNum} کالا (صفحه ${toFa(currentPage)} از ${toFa(totalPages)})`;
					}
				}

				if (noResults) {
					noResults.style.display = (totalMatched === 0 && allCards.length > 0) ? 'block' : 'none';
				}

				renderPagination(totalPages, currentPage);
			}

			function onSearch(val) {
				searchQuery = val.trim().toLowerCase();
				if (clearBtn) {
					clearBtn.classList.toggle('active', searchQuery.length > 0);
				}
				applyFilters();
			}

			if (headerSearch) {
				headerSearch.addEventListener('input', (e) => onSearch(e.target.value));
			}

			if (clearBtn) {
				clearBtn.addEventListener('click', () => {
					if (headerSearch) headerSearch.value = '';
					onSearch('');
				});
			}

			if (resetBtn) {
				resetBtn.addEventListener('click', () => {
					if (headerSearch) headerSearch.value = '';
					currentCat = 'all';
					app.querySelectorAll('#categoryPills .filter-pill').forEach(p => {
						p.classList.toggle('active', p.getAttribute('data-cat') === 'all');
					});
					onSearch('');
				});
			}

			// Category pill clicks
			app.querySelectorAll('#categoryPills .filter-pill').forEach(pill => {
				pill.addEventListener('click', () => {
					app.querySelectorAll('#categoryPills .filter-pill').forEach(p => p.classList.remove('active'));
					pill.classList.add('active');
					currentCat = pill.getAttribute('data-cat');
					applyFilters();
				});
			});

			// Dropdown category clicks
			app.querySelectorAll('.dropdown-cat-item[data-cat], .footer-cat-link[data-cat]').forEach(item => {
				item.addEventListener('click', (e) => {
					e.preventDefault();
					const cat = item.getAttribute('data-cat');
					currentCat = cat;
					app.querySelectorAll('#categoryPills .filter-pill').forEach(p => {
						p.classList.toggle('active', p.getAttribute('data-cat') === cat);
					});
					if (megaPanel) megaPanel.classList.remove('open');
					applyFilters();
					const grid = document.getElementById('productsGrid');
					if (grid) grid.scrollIntoView({behavior:'smooth'});
				});
			});

			// Sorting
			const sortSel = document.getElementById('sortSelector');
			if (sortSel) {
				sortSel.addEventListener('change', () => {
					const grid = document.getElementById('productsGrid');
					if (!grid) return;
					const cards = Array.from(grid.querySelectorAll('.product-card'));
					const val = sortSel.value;

					cards.sort((a, b) => {
						if (val === 'price-asc') {
							return parseFloat(a.getAttribute('data-price-num') || 0) - parseFloat(b.getAttribute('data-price-num') || 0);
						} else if (val === 'price-desc') {
							return parseFloat(b.getAttribute('data-price-num') || 0) - parseFloat(a.getAttribute('data-price-num') || 0);
						} else if (val === 'title') {
							return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'), 'fa');
						}
						return 0;
					});

					cards.forEach(c => grid.appendChild(c));
				});
			}

			// Wishlist functionality
			function updateWishlistUI() {
				app.querySelectorAll('.card-wishlist-btn').forEach(btn => {
					const id = btn.getAttribute('data-id');
					btn.classList.toggle('liked', wishlist.includes(id));
				});
			}

			app.querySelectorAll('.card-wishlist-btn').forEach(btn => {
				btn.addEventListener('click', (e) => {
					e.stopPropagation();
					const id = btn.getAttribute('data-id');
					const idx = wishlist.indexOf(id);
					if (idx > -1) {
						wishlist.splice(idx, 1);
						showToast('از لیست علاقه‌مندی‌ها حذف شد');
					} else {
						wishlist.push(id);
						showToast('❤️ به لیست علاقه‌مندی‌ها افزوده شد');
					}
					try {
						localStorage.setItem('modern_shop_wishlist', JSON.stringify(wishlist));
					} catch(err) {}
					updateWishlistUI();
				});
			});
			updateWishlistUI();

			// Cart Management
			function updateCartUI() {
				const countEl = document.getElementById('headerCartCount');
				const mobCountEl = document.getElementById('mobCartCount');
				const listEl = document.getElementById('cartItemsList');
				const totalEl = document.getElementById('cartTotalPrice');
				const progressText = document.getElementById('shippingProgressText');
				const progressFill = document.getElementById('shippingProgressFill');

				const totalItems = cart.reduce((acc, it) => acc + it.qty, 0);
				const totalPrice = cart.reduce((acc, it) => acc + (it.price * it.qty), 0);

				if (countEl) {
					countEl.textContent = toFa(totalItems);
					countEl.classList.remove('pulse');
					void countEl.offsetWidth;
					countEl.classList.add('pulse');
				}
				if (mobCountEl) mobCountEl.textContent = toFa(totalItems);
				if (totalEl) totalEl.textContent = formatPrice(totalPrice);

				// Shipping progress
				if (progressText && progressFill) {
					if (totalPrice >= freeShippingThreshold) {
						progressText.innerHTML = '🎉 <strong style="color:#059669;">تبریک!</strong> سفارش شما شامل <strong>ارسال رایگان</strong> شد.';
						progressFill.style.width = '100%';
					} else {
						const remain = freeShippingThreshold - totalPrice;
						progressText.innerHTML = '🚚 با خرید <strong>' + formatPrice(remain) + '</strong> دیگر، ارسال شما <strong>رایگان</strong> خواهد بود!';
						const pct = Math.min(100, Math.round((totalPrice / freeShippingThreshold) * 100));
						progressFill.style.width = pct + '%';
					}
				}

				if (listEl) {
					if (cart.length === 0) {
						listEl.innerHTML = '<div style="text-align:center; color:#94a3b8; padding:50px 10px;">سبد خرید شما در حال حاضر خالی است.</div>';
					} else {
						listEl.innerHTML = cart.map((it, idx) => `
							<div class="cart-item-row">
								<img src="${it.img || ''}" class="cart-item-img" alt="${it.title}">
								<div class="cart-item-info">
									<div class="cart-item-title">${it.title}</div>
									<div class="cart-item-price">${formatPrice(it.price * it.qty)}</div>
									<div class="cart-item-qty-row">
										<button type="button" class="cart-qty-btn cart-qty-minus" data-idx="${idx}">-</button>
										<span class="cart-qty-num">${toFa(it.qty)}</span>
										<button type="button" class="cart-qty-btn cart-qty-plus" data-idx="${idx}">+</button>
										<button type="button" class="cart-item-del" data-idx="${idx}" title="حذف کالا">🗑️</button>
									</div>
								</div>
							</div>
						`).join('');

						listEl.querySelectorAll('.cart-qty-plus').forEach(btn => {
							btn.addEventListener('click', () => {
								const idx = parseInt(btn.getAttribute('data-idx'));
								if (cart[idx]) {
									cart[idx].qty++;
									saveCart();
									syncUpdateQtyWoo(cart[idx].id, cart[idx].qty);
								}
							});
						});

						listEl.querySelectorAll('.cart-qty-minus').forEach(btn => {
							btn.addEventListener('click', () => {
								const idx = parseInt(btn.getAttribute('data-idx'));
								if (cart[idx]) {
									if (cart[idx].qty > 1) {
										cart[idx].qty--;
										saveCart();
										syncUpdateQtyWoo(cart[idx].id, cart[idx].qty);
									} else {
										const removedId = cart[idx].id;
										cart.splice(idx, 1);
										saveCart();
										syncRemoveItemWoo(removedId);
									}
								}
							});
						});

						listEl.querySelectorAll('.cart-item-del').forEach(btn => {
							btn.addEventListener('click', () => {
								const idx = parseInt(btn.getAttribute('data-idx'));
								if (cart[idx]) {
									const removedId = cart[idx].id;
									cart.splice(idx, 1);
									saveCart();
									syncRemoveItemWoo(removedId);
									showToast('🗑️ کالا از سبد خرید حذف شد');
								}
							});
						});
					}
				}
			}

			function saveCart() {
				try {
					localStorage.setItem('modern_shop_cart', JSON.stringify(cart));
				} catch(e) {}
				updateCartUI();
			}

			// Synchronize Add to Cart with WooCommerce Real Cart Session
			function syncAddToCartWoo(prod, qty) {
				const fd = new FormData();
				fd.append('action', 'scraper_wc_add_to_cart');
				fd.append('nonce', scraperCartConfig.nonce);
				fd.append('id', prod.id || '');
				fd.append('title', prod.title || '');
				fd.append('price', prod.price || 0);
				fd.append('image', prod.img || '');
				fd.append('qty', qty || 1);

				fetch(scraperCartConfig.ajaxUrl, {
					method: 'POST',
					body: fd
				})
				.then(r => r.json())
				.then(res => {
					if (res.success && res.data && Array.isArray(res.data.items)) {
						cart = res.data.items;
						saveCart();
					}
				})
				.catch(err => console.warn('WC Cart sync warning:', err));
			}

			// Synchronize Quantity changes with WooCommerce
			function syncUpdateQtyWoo(id, qty) {
				const fd = new FormData();
				fd.append('action', 'scraper_wc_update_cart_qty');
				fd.append('nonce', scraperCartConfig.nonce);
				fd.append('id', id);
				fd.append('qty', qty);

				fetch(scraperCartConfig.ajaxUrl, {
					method: 'POST',
					body: fd
				})
				.then(r => r.json())
				.then(res => {
					if (res.success && res.data && Array.isArray(res.data.items)) {
						cart = res.data.items;
						saveCart();
					}
				})
				.catch(err => console.warn('WC Cart qty sync warning:', err));
			}

			// Synchronize Item Removal with WooCommerce
			function syncRemoveItemWoo(id) {
				const fd = new FormData();
				fd.append('action', 'scraper_wc_remove_cart_item');
				fd.append('nonce', scraperCartConfig.nonce);
				fd.append('id', id);

				fetch(scraperCartConfig.ajaxUrl, {
					method: 'POST',
					body: fd
				})
				.then(r => r.json())
				.then(res => {
					if (res.success && res.data && Array.isArray(res.data.items)) {
						cart = res.data.items;
						saveCart();
					}
				})
				.catch(err => console.warn('WC Cart remove sync warning:', err));
			}

			// Pull items from WooCommerce on page load to keep in sync
			function syncLoadWooCart() {
				const fd = new FormData();
				fd.append('action', 'scraper_wc_get_cart');

				fetch(scraperCartConfig.ajaxUrl, {
					method: 'POST',
					body: fd
				})
				.then(r => r.json())
				.then(res => {
					if (res.success && res.data && Array.isArray(res.data.items) && res.data.items.length > 0) {
						cart = res.data.items;
						saveCart();
					}
				})
				.catch(e => {});
			}

			function addToCart(prod, qty = 1) {
				const found = cart.find(it => it.id === prod.id);
				if (found) {
					found.qty += qty;
				} else {
					cart.push({
						id: prod.id,
						title: prod.title,
						price: prod.price,
						priceTxt: prod.priceTxt,
						img: prod.img,
						qty: qty
					});
				}
				saveCart();
				showToast('✅ «' + prod.title.substring(0, 24) + '...» به سبد خرید اضافه شد');

				// Sync with real WooCommerce Cart
				syncAddToCartWoo(prod, qty);
			}

			// Add to cart buttons on product cards
			app.querySelectorAll('.add-to-cart-btn').forEach(btn => {
				btn.addEventListener('click', (e) => {
					e.stopPropagation();
					const prod = {
						id: btn.getAttribute('data-id'),
						title: btn.getAttribute('data-title'),
						price: parseFloat(btn.getAttribute('data-price') || 0),
						priceTxt: btn.getAttribute('data-price-txt'),
						img: btn.getAttribute('data-img')
					};
					addToCart(prod, 1);

					// Button visual feedback
					btn.classList.add('added');
					const origHtml = btn.innerHTML;
					btn.innerHTML = '✓ افزوده شد';
					setTimeout(() => {
						btn.classList.remove('added');
						btn.innerHTML = origHtml;
					}, 1600);
				});
			});

			// Drawer controls
			const cartDrawer = document.getElementById('cartDrawer');
			const cartOverlay = document.getElementById('cartDrawerOverlay');
			const closeCart = document.getElementById('closeCartDrawer');
			const headerCartBtn = document.getElementById('headerCartBtn');
			const mobBarCartBtn = document.getElementById('mobBarCartBtn');

			function openCartDrawer() {
				if (cartDrawer && cartOverlay) {
					cartDrawer.classList.add('open');
					cartOverlay.classList.add('open');
				}
			}
			function closeCartDrawer() {
				if (cartDrawer && cartOverlay) {
					cartDrawer.classList.remove('open');
					cartOverlay.classList.remove('open');
				}
			}

			if (headerCartBtn) headerCartBtn.addEventListener('click', openCartDrawer);
			if (mobBarCartBtn) mobBarCartBtn.addEventListener('click', (e) => { e.preventDefault(); openCartDrawer(); });
			if (closeCart) closeCart.addEventListener('click', closeCartDrawer);
			if (cartOverlay) cartOverlay.addEventListener('click', closeCartDrawer);

			// Mobile bottom bar links
			const mobBarCatsBtn = document.getElementById('mobBarCatsBtn');
			if (mobBarCatsBtn) {
				mobBarCatsBtn.addEventListener('click', (e) => {
					e.preventDefault();
					const pills = document.getElementById('categoryPills');
					if (pills) pills.scrollIntoView({behavior:'smooth'});
				});
			}

			const mobBarSearchBtn = document.getElementById('mobBarSearchBtn');
			if (mobBarSearchBtn) {
				mobBarSearchBtn.addEventListener('click', (e) => {
					e.preventDefault();
					if (headerSearch) {
						headerSearch.scrollIntoView({behavior:'smooth'});
						headerSearch.focus();
					}
				});
			}

			// Quick View Modal
			const qvModal = document.getElementById('quickViewModal');
			const closeQv = document.getElementById('closeQuickView');
			let activeModalProduct = null;
			let modalQty = 1;

			const modalQtyNum = document.getElementById('modalQtyNum');
			const modalQtyPlus = document.getElementById('modalQtyPlus');
			const modalQtyMinus = document.getElementById('modalQtyMinus');

			if (modalQtyPlus) {
				modalQtyPlus.addEventListener('click', () => {
					modalQty++;
					if (modalQtyNum) modalQtyNum.textContent = toFa(modalQty);
				});
			}
			if (modalQtyMinus) {
				modalQtyMinus.addEventListener('click', () => {
					if (modalQty > 1) {
						modalQty--;
						if (modalQtyNum) modalQtyNum.textContent = toFa(modalQty);
					}
				});
			}

			app.querySelectorAll('.open-quick-view').forEach(btn => {
				btn.addEventListener('click', () => {
					const card = btn.closest('.product-card');
					const title = btn.getAttribute('data-title');
					const priceTxt = btn.getAttribute('data-price');
					const oldPrice = btn.getAttribute('data-old-price');
					const img = btn.getAttribute('data-img');
					const cat = btn.getAttribute('data-cat');
					const desc = btn.getAttribute('data-desc');
					const priceNum = parseFloat(card ? card.getAttribute('data-price-num') : 0);

					modalQty = 1;
					if (modalQtyNum) modalQtyNum.textContent = toFa(modalQty);

					activeModalProduct = {
						id: card ? card.getAttribute('data-id') : 'prod_' + Math.random(),
						title, price: priceNum, priceTxt, img
					};

					document.getElementById('modalTitle').textContent = title;
					document.getElementById('modalPrice').textContent = priceTxt;
					
					const oldEl = document.getElementById('modalOldPrice');
					if (oldPrice) {
						oldEl.textContent = oldPrice;
						oldEl.style.display = 'block';
					} else {
						oldEl.style.display = 'none';
					}

					document.getElementById('modalCat').textContent = '📂 ' + (cat || 'عمومی');
					document.getElementById('modalDesc').textContent = desc || 'توضیحات تکمیلی برای این محصول در برگه رسمی درج شده است.';
					document.getElementById('modalImg').src = img || '';

					qvModal.classList.add('open');
				});
			});

			if (closeQv) {
				closeQv.addEventListener('click', () => qvModal.classList.remove('open'));
			}
			if (qvModal) {
				qvModal.addEventListener('click', (e) => {
					if (e.target === qvModal) qvModal.classList.remove('open');
				});
			}

			// Close on ESC key
			document.addEventListener('keydown', (e) => {
				if (e.key === 'Escape') {
					if (qvModal) qvModal.classList.remove('open');
					closeCartDrawer();
				}
			});

			const modalAddBtn = document.getElementById('modalAddToCartBtn');
			if (modalAddBtn) {
				modalAddBtn.addEventListener('click', () => {
					if (activeModalProduct) {
						addToCart(activeModalProduct, modalQty);
						qvModal.classList.remove('open');
					}
				});
			}

			// Support Chat Widget Continuous Messenger Logic
			(function(){
				const chatTrigger = document.getElementById('supportChatTrigger');
				const chatBox = document.getElementById('supportChatBox');
				const chatClose = document.getElementById('supportChatClose');
				const chatForm = document.getElementById('supportChatForm');
				const chatSendBtn = document.getElementById('chatSendBtn');
				const chatBody = document.getElementById('supportChatBody');
				const chatMsgInput = document.getElementById('chatMsgInput');
				const chatContactFields = document.getElementById('chatContactFields');
				const chatUserBar = document.getElementById('chatUserBar');
				const chatUserBarName = document.getElementById('chatUserBarName');
				const chatUserBarPhone = document.getElementById('chatUserBarPhone');
				const chatUserEditBtn = document.getElementById('chatUserEditBtn');
				const chatTypingIndicator = document.getElementById('chatTypingIndicator');
				const chatTypingText = document.getElementById('chatTypingText');

				if (!chatTrigger || !chatBox) return;

				// Session ID management
				let sessionId = localStorage.getItem('scraper_chat_session_id');
				if (!sessionId) {
					sessionId = 'sess_' + Math.random().toString(36).substring(2, 12) + Date.now().toString(36);
					localStorage.setItem('scraper_chat_session_id', sessionId);
				}

				// Restore saved customer info
				const savedName = localStorage.getItem('scraper_chat_customer_name') || '';
				const savedPhone = localStorage.getItem('scraper_chat_customer_phone') || '';
				const savedEmail = localStorage.getItem('scraper_chat_customer_email') || '';

				const nameInput = document.getElementById('chatNameInput');
				const phoneInput = document.getElementById('chatPhoneInput');
				const emailInput = document.getElementById('chatEmailInput');

				if (nameInput && savedName) nameInput.value = savedName;
				if (phoneInput && savedPhone) phoneInput.value = savedPhone;
				if (emailInput && savedEmail) emailInput.value = savedEmail;

				function updateCustomerBar() {
					const curName = (nameInput ? nameInput.value.trim() : '') || savedName;
					const curPhone = (phoneInput ? phoneInput.value.trim() : '') || savedPhone;
					if (curName || curPhone) {
						if (chatUserBarName) chatUserBarName.textContent = curName || 'کاربر';
						if (chatUserBarPhone) chatUserBarPhone.textContent = curPhone ? ' • ' + curPhone : '';
						if (chatUserBar) chatUserBar.style.display = 'flex';
						if (chatContactFields) chatContactFields.classList.add('collapsed');
					}
				}

				if (savedName || savedPhone) {
					updateCustomerBar();
				}

				if (chatUserEditBtn) {
					chatUserEditBtn.addEventListener('click', () => {
						if (chatContactFields) {
							chatContactFields.classList.toggle('collapsed');
							if (!chatContactFields.classList.contains('collapsed')) {
								if (phoneInput) phoneInput.focus();
							}
						}
					});
				}

				// Toggle Chat Window
				chatTrigger.addEventListener('click', () => {
					chatBox.classList.toggle('open');
					if (chatBox.classList.contains('open')) {
						chatBody.scrollTop = chatBody.scrollHeight;
						pollThreadMessages();
						if (chatMsgInput) chatMsgInput.focus();
					}
				});

				if (chatClose) {
					chatClose.addEventListener('click', () => {
						chatBox.classList.remove('open');
					});
				}

				// Close on click outside
				document.addEventListener('click', (e) => {
					if (chatBox.classList.contains('open') && !chatBox.contains(e.target) && !chatTrigger.contains(e.target)) {
						chatBox.classList.remove('open');
					}
				});

				// Auto-expand textarea on Enter (Shift+Enter for newline)
				if (chatMsgInput) {
					chatMsgInput.addEventListener('keydown', (e) => {
						if (e.key === 'Enter' && !e.shiftKey) {
							e.preventDefault();
							if (chatForm) {
								const submitEvent = new Event('submit', { cancelable: true, bubbles: true });
								chatForm.dispatchEvent(submitEvent);
							}
						}
					});
				}

				// Track rendered message IDs to avoid duplicates
				const renderedMsgIds = new Set();

				function renderMessageBubble(msg) {
					if (!msg || !msg.id || renderedMsgIds.has(msg.id)) return;
					renderedMsgIds.add(msg.id);

					const bubble = document.createElement('div');
					const timeStr = msg.time || '';

					if (msg.sender === 'customer') {
						bubble.className = 'chat-msg-bubble outgoing';
						bubble.innerHTML = `<div>${escapeHtml(msg.text)}</div><div class="chat-msg-time">${escapeHtml(timeStr)} ✓✓</div>`;
					} else if (msg.sender === 'ai') {
						bubble.className = 'chat-msg-bubble incoming ai-bubble';
						bubble.innerHTML = `<div class="chat-msg-sender-badge">🤖 ${escapeHtml(msg.sender_name || 'پشتیبان هوشمند')}</div><div>${escapeHtml(msg.text).replace(/\n/g, '<br>')}</div><div class="chat-msg-time">${escapeHtml(timeStr)}</div>`;
					} else if (msg.sender === 'admin') {
						bubble.className = 'chat-msg-bubble incoming admin-bubble';
						bubble.innerHTML = `<div class="chat-msg-sender-badge">👨‍💼 ${escapeHtml(msg.sender_name || 'مدیریت فروشگاه (پاسخ ادمین)')}</div><div>${escapeHtml(msg.text).replace(/\n/g, '<br>')}</div><div class="chat-msg-time">${escapeHtml(timeStr)}</div>`;
					}

					chatBody.appendChild(bubble);
					chatBody.scrollTop = chatBody.scrollHeight;
				}

				// Polling thread messages from server
				let isPolling = false;
				function pollThreadMessages() {
					if (isPolling) return;
					isPolling = true;

					const formData = new FormData();
					formData.append('action', 'scraper_customer_get_thread');
					formData.append('nonce', '<?php echo esc_js( wp_create_nonce( 'scraper_support_chat_nonce' ) ); ?>');
					formData.append('session_id', sessionId);

					fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
						method: 'POST',
						body: formData
					})
					.then(r => r.json())
					.then(res => {
						isPolling = false;
						if (res.success && res.data && res.data.messages) {
							res.data.messages.forEach(msg => renderMessageBubble(msg));
						}
					})
					.catch(() => { isPolling = false; });
				}

				// Initial poll to load existing conversation
				pollThreadMessages();

				// Periodic polling every 4 seconds when chat is open
				setInterval(() => {
					if (chatBox.classList.contains('open')) {
						pollThreadMessages();
					}
				}, 4000);

				// Chat Form Submit (Continuous Sending)
				if (chatForm) {
					chatForm.addEventListener('submit', (e) => {
						e.preventDefault();
						const nameEl = document.getElementById('chatNameInput');
						const phoneEl = document.getElementById('chatPhoneInput');
						const emailEl = document.getElementById('chatEmailInput');
						const subjectEl = document.getElementById('chatSubjectInput');
						const msgEl = document.getElementById('chatMsgInput');

						const name = nameEl ? nameEl.value.trim() : (savedName || '');
						const phone = phoneEl ? phoneEl.value.trim() : (savedPhone || '');
						const email = emailEl ? emailEl.value.trim() : (savedEmail || '');
						const subject = subjectEl ? subjectEl.value.trim() : '';
						const message = msgEl ? msgEl.value.trim() : '';

						if (phoneEl && phoneEl.hasAttribute('required') && !phone) {
							showToast('لطفاً شماره تماس خود را وارد نمایید.', 'error');
							if (chatContactFields) chatContactFields.classList.remove('collapsed');
							phoneEl.focus();
							return;
						}
						if (emailEl && emailEl.hasAttribute('required') && !email) {
							showToast('لطفاً آدرس ایمیل معتبر وارد نمایید.', 'error');
							if (chatContactFields) chatContactFields.classList.remove('collapsed');
							emailEl.focus();
							return;
						}
						if (nameEl && nameEl.hasAttribute('required') && !name) {
							showToast('لطفاً نام و نام خانوادگی خود را وارد نمایید.', 'error');
							if (chatContactFields) chatContactFields.classList.remove('collapsed');
							nameEl.focus();
							return;
						}
						if (!message) {
							if (msgEl) msgEl.focus();
							return;
						}

						// Save to localStorage
						if (name) localStorage.setItem('scraper_chat_customer_name', name);
						if (phone) localStorage.setItem('scraper_chat_customer_phone', phone);
						if (email) localStorage.setItem('scraper_chat_customer_email', email);

						updateCustomerBar();

						// Clear message input immediately & show temporary customer bubble
						const tempMsgId = 'msg_temp_' + Date.now();
						const currentTimeStr = new Date().toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit' });
						renderMessageBubble({
							id: tempMsgId,
							sender: 'customer',
							sender_name: name || 'شما',
							text: message,
							time: currentTimeStr
						});

						if (msgEl) msgEl.value = '';

						// Show Typing indicator for AI
						if (chatTypingIndicator) {
							chatTypingIndicator.style.display = 'block';
							chatBody.scrollTop = chatBody.scrollHeight;
						}

						chatSendBtn.disabled = true;

						const formData = new FormData();
						formData.append('action', 'submit_support_chat');
						formData.append('nonce', '<?php echo esc_js( wp_create_nonce( 'scraper_support_chat_nonce' ) ); ?>');
						formData.append('session_id', sessionId);
						formData.append('name', name);
						formData.append('phone', phone);
						formData.append('email', email);
						formData.append('subject', subject);
						formData.append('message', message);

						fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
							method: 'POST',
							body: formData
						})
						.then(r => r.json())
						.then(res => {
							chatSendBtn.disabled = false;
							if (chatTypingIndicator) chatTypingIndicator.style.display = 'none';

							if (res.success && res.data) {
								if (res.data.thread && res.data.thread.messages) {
									res.data.thread.messages.forEach(msg => renderMessageBubble(msg));
								}
								if (msgEl) msgEl.focus();
							} else {
								showToast(res.data || 'خطا در ثبت پیام.', 'error');
							}
						})
						.catch(err => {
							chatSendBtn.disabled = false;
							if (chatTypingIndicator) chatTypingIndicator.style.display = 'none';
							showToast('خطای اتصال به سرور.', 'error');
						});
					});
				}
			})();

			// Initialize cart view & pull WooCommerce active session
			updateCartUI();
			syncLoadWooCart();

			// Checkout button click handler: GUARANTEED sync of all items to WooCommerce Cart
			const checkoutBtnEl = document.getElementById('btnGoToCheckout');
			if (checkoutBtnEl) {
				checkoutBtnEl.addEventListener('click', (e) => {
					e.preventDefault();
					if (!cart || cart.length === 0) {
						showToast('سبد خرید شما خالی است! لطفاً ابتدا کالایی را اضافه کنید.', 'error');
						return;
					}

					checkoutBtnEl.innerHTML = 'در حال آماده‌سازی سبد خرید ووکامرس... ⏳';
					checkoutBtnEl.style.pointerEvents = 'none';
					checkoutBtnEl.style.opacity = '0.85';

					const fd = new FormData();
					fd.append('action', 'scraper_wc_sync_and_checkout');
					fd.append('nonce', scraperCartConfig.nonce);
					fd.append('items', JSON.stringify(cart));

					fetch(scraperCartConfig.ajaxUrl, {
						method: 'POST',
						body: fd
					})
					.then(r => r.json())
					.then(res => {
						if (res.success && res.data && res.data.checkout_url) {
							window.location.href = res.data.checkout_url;
						} else {
							window.location.href = scraperCartConfig.checkoutUrl;
						}
					})
					.catch(err => {
						window.location.href = scraperCartConfig.checkoutUrl;
					});
				});
			}
		})();
		</script>
		<?php
		return ob_get_clean();
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
				'replace_site_header'         => ! empty( $_POST['replace_site_header'] ),
				'show_top_bar'                => ! empty( $_POST['show_top_bar'] ),
				'top_bar_notice'              => sanitize_text_field( $_POST['top_bar_notice'] ?? '' ),
				'contact_phone'               => sanitize_text_field( $_POST['contact_phone'] ?? '' ),
				'support_hours'               => sanitize_text_field( $_POST['support_hours'] ?? '' ),
				'shop_title'                  => sanitize_text_field( $_POST['shop_title'] ?? '' ),
				'shop_subtitle'               => sanitize_text_field( $_POST['shop_subtitle'] ?? '' ),
				'accent_color'                => sanitize_text_field( $_POST['accent_color'] ?? '#2563eb' ),
				'default_column_layout'       => in_array( $_POST['default_column_layout'] ?? '', array( '1', '2' ), true ) ? $_POST['default_column_layout'] : '1',
				'products_per_page'           => intval( $_POST['products_per_page'] ?? 20 ),
				'show_features_banner'        => ! empty( $_POST['show_features_banner'] ),
				'show_special_badge'          => ! empty( $_POST['show_special_badge'] ),
				'free_shipping_threshold'     => floatval( $_POST['free_shipping_threshold'] ?? 400000 ),

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

				// Tab 5: Messengers
				'bale_token'                  => sanitize_text_field( $_POST['bale_token'] ?? '' ),
				'bale_chat_id'                => sanitize_text_field( $_POST['bale_chat_id'] ?? '' ),
				'telegram_token'              => sanitize_text_field( $_POST['telegram_token'] ?? '' ),
				'telegram_chat_id'            => sanitize_text_field( $_POST['telegram_chat_id'] ?? '' ),
				'rubika_token'                => sanitize_text_field( $_POST['rubika_token'] ?? '' ),
				'rubika_chat_id'              => sanitize_text_field( $_POST['rubika_chat_id'] ?? '' ),
			);
			update_option( self::OPTION_NAME, $new_settings );
			$updated = true;
		}

		$opts             = self::get_settings();
		$scraped_products = self::get_all_scraped_products();
		$profiles_summary = self::get_profiles_summary();
		$active_msgrs     = self::get_active_messengers( $opts );
		$chat_logs        = get_option( 'scraper_support_chat_logs', array() );
		$chat_threads     = self::get_chat_threads();

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
				.scraper-tab-nav {
					display: flex;
					gap: 8px;
					border-bottom: 2px solid #e2e8f0;
					margin-bottom: 24px;
					overflow-x: auto;
					white-space: nowrap;
					-webkit-overflow-scrolling: touch;
					scrollbar-width: none;
					padding-bottom: 6px;
				}
				.scraper-tab-nav::-webkit-scrollbar {
					display: none;
				}
				.scraper-tab-link {
					display: inline-flex;
					align-items: center;
					gap: 8px;
					padding: 11px 18px;
					font-size: 0.95rem;
					font-weight: 800;
					color: #64748b;
					text-decoration: none;
					border-radius: 12px;
					border: 1px solid transparent;
					background: #f1f5f9;
					transition: all 0.2s ease;
					cursor: pointer;
					flex-shrink: 0;
				}
				.scraper-tab-link:hover {
					color: #0f172a;
					background: #e2e8f0;
				}
				.scraper-tab-link.active {
					color: #ffffff;
					background: #2563eb;
					border-color: #2563eb;
					box-shadow: 0 4px 12px rgba(37, 99, 235, 0.28);
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

				/* Support Desk Styling */
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
					border-radius: 20px;
					padding: 4px 12px;
					font-family: inherit;
					font-size: 0.78rem;
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
					padding: 10px 14px;
					border-radius: 14px;
					font-size: 0.88rem;
					line-height: 1.5;
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

				/* Mobile Admin Optimizations */
				@media (max-width: 782px) {
					.wrap.scraper-admin-dashboard {
						padding: 8px !important;
						margin-top: 10px !important;
					}
					.scraper-support-desk {
						flex-direction: column !important;
						min-height: auto !important;
					}
					.desk-threads-col {
						width: 100% !important;
						max-height: 340px !important;
						border-left: none !important;
						border-bottom: 1px solid #e2e8f0 !important;
					}
					.desk-threads-col.mobile-hide {
						display: none !important;
					}
					.desk-view-col.mobile-fullscreen {
						width: 100% !important;
					}
					.chat-themes-grid,
					.chat-btn-styles-grid {
						grid-template-columns: 1fr !important;
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
					.scraper-save-bar {
						position: sticky !important;
						bottom: 0 !important;
						z-index: 999 !important;
						background: rgba(255,255,255,0.96) !important;
						backdrop-filter: blur(12px) !important;
						padding: 12px 16px !important;
						border-top: 1px solid #cbd5e1 !important;
						box-shadow: 0 -4px 20px rgba(0,0,0,0.08) !important;
					}
				}
				</style>

			<!-- Modern Tab Navigation -->
			<div class="scraper-tab-nav" id="scraperAdminTabs">
				<button type="button" class="scraper-tab-link active" data-tab="tab-storefront">🎨 ۱. ویترین و ظاهر فروشگاه</button>
				<button type="button" class="scraper-tab-link" data-tab="tab-pricing">💰 ۲. قیمت‌گذاری و سود</button>
				<button type="button" class="scraper-tab-link" data-tab="tab-chat">💬 ۳. چت آنلاین و میز پاسخگویی</button>
				<button type="button" class="scraper-tab-link" data-tab="tab-ai">🤖 ۴. هوش مصنوعی و هماهنگی</button>
				<button type="button" class="scraper-tab-link" data-tab="tab-messengers">📡 ۵. پیام‌رسان‌ها (بله/تلگرام/روبیکا)</button>
				<button type="button" class="scraper-tab-link" data-tab="tab-woocommerce">🔄 ۶. ووکامرس و اسکرپر</button>
				<button type="button" class="scraper-tab-link" data-tab="tab-logs">📋 ۷. گزارش پیام‌های مشتریان</button>
			</div>

			<form method="post" action="" id="scraperAdminForm">
				<?php wp_nonce_field( 'scraper_shop_settings_action', 'scraper_shop_settings_nonce' ); ?>

				<!-- ================= TAB 1: STOREFRONT & APPEARANCE ================= -->
				<div id="tab-storefront" class="scraper-tab-panel active">
					<div class="admin-card">
						<div class="admin-card-header">
							<h3><span>🎨</span> تنظیمات سربرگ، نوار اعلان و ظاهر فروشگاه</h3>
							<span class="field-badge field-badge-blue">طراحی لوکس اختصاصی</span>
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
									<input type="text" name="shop_title" value="<?php echo esc_attr( $opts['shop_title'] ); ?>" class="large-text">
								</td>
							</tr>
							<tr>
								<th scope="row">زیرعنوان و شعار فروشگاه:</th>
								<td>
									<input type="text" name="shop_subtitle" value="<?php echo esc_attr( $opts['shop_subtitle'] ); ?>" class="large-text">
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
									<label style="display:block;">
										<input type="checkbox" name="show_features_banner" value="1" <?php checked( $opts['show_features_banner'] ); ?>>
										نمایش بنر ویژگی‌های فروشگاه (ارسال سریع، تضمین اصالت و ضمانت بازگشت)
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">جایگزینی صفحه فروشگاه ووکامرس:</th>
								<td>
									<label>
										<input type="checkbox" name="enable_shop_takeover" value="1" <?php checked( $opts['enable_shop_takeover'] ); ?>>
										برگه اصلی فروشگاه ووکامرس با این ویترین جذاب و مدرن نمایش داده شود.
									</label>
								</td>
							</tr>
						</table>
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
					<div class="admin-card" style="border-top: 4px solid #2563eb;">
						<div class="admin-card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
							<div>
								<h3 style="margin:0; display:flex; align-items:center; gap:8px;">
									<span>📬</span> میز کار و کنسول پاسخگویی زنده به گفتگوهای مشتریان
								</h3>
								<p style="margin:4px 0 0; color:#64748b; font-size:0.88rem;">
									پاسخ شما به صورت زنده و دوطرفه مانند پیام‌رسان در پنجره چت مشتری نمایش داده خواهد شد.
								</p>
							</div>
							<div style="display:flex; align-items:center; gap:10px;">
								<span class="field-badge field-badge-blue" id="deskThreadsCountBadge"><?php echo count( $chat_threads ); ?> گفتگو</span>
								<button type="button" class="button button-secondary" id="btnRefreshAdminDesk" style="font-weight:700;">🔄 به‌روزرسانی زنده</button>
							</div>
						</div>

						<!-- Two-Column Desk Console -->
						<div class="scraper-support-desk" style="display:flex; gap:18px; margin-top:16px; min-height:540px; border:1px solid #e2e8f0; border-radius:14px; overflow:hidden; background:#ffffff;">
							
							<!-- Column 1: Conversations List -->
							<div class="desk-threads-col" style="width:340px; border-left:1px solid #e2e8f0; display:flex; flex-direction:column; background:#f8fafc;">
								<!-- Search & Filter Bar -->
								<div style="padding:12px 14px; border-bottom:1px solid #e2e8f0; background:#ffffff;">
									<input type="text" id="deskSearchInput" placeholder="🔍 جستجو در نام، شماره یا پیام..." style="width:100%; border:1px solid #cbd5e1; border-radius:8px; padding:7px 10px; font-size:0.85rem;">
									<div style="display:flex; gap:6px; margin-top:8px;">
										<button type="button" class="desk-filter-btn active" data-filter="all" style="flex:1; border:1px solid #cbd5e1; background:#ffffff; border-radius:6px; padding:4px; font-size:0.75rem; font-weight:700; cursor:pointer;">همه</button>
										<button type="button" class="desk-filter-btn" data-filter="pending" style="flex:1; border:1px solid #cbd5e1; background:#ffffff; border-radius:6px; padding:4px; font-size:0.75rem; font-weight:700; cursor:pointer; color:#d97706;">در انتظار</button>
										<button type="button" class="desk-filter-btn" data-filter="replied" style="flex:1; border:1px solid #cbd5e1; background:#ffffff; border-radius:6px; padding:4px; font-size:0.75rem; font-weight:700; cursor:pointer; color:#059669;">پاسخ‌داده</button>
									</div>
								</div>

								<!-- Threads Scroll List -->
								<div class="desk-threads-scroll" id="deskThreadsList" style="flex:1; overflow-y:auto; max-height:480px;">
									<?php if ( empty( $chat_threads ) ) : ?>
										<div style="text-align:center; padding:40px 15px; color:#94a3b8; font-size:0.88rem;">
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
												style="padding:12px 14px; border-bottom:1px solid #f1f5f9; cursor:pointer; transition:background 0.2s;">
												<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
													<strong style="color:#0f172a; font-size:0.9rem;"><?php echo esc_html( $t['name'] ?? 'مشتری مهمان' ); ?></strong>
													<span style="font-size:0.72rem; color:#64748b;"><?php echo esc_html( $last_msg['time'] ?? '' ); ?></span>
												</div>
												<?php if ( ! empty( $t['phone'] ) ) : ?>
													<div style="font-size:0.78rem; color:#2563eb; font-weight:700; direction:ltr; text-align:right; margin-bottom:4px;"><?php echo esc_html( $t['phone'] ); ?></div>
												<?php endif; ?>
												<div style="font-size:0.8rem; color:#64748b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
													<?php echo esc_html( mb_substr( $last_msg['text'] ?? 'بدون پیام', 0, 50 ) ); ?>
												</div>
												<div style="display:flex; justify-content:space-between; align-items:center; margin-top:6px;">
													<?php if ( $is_unread ) : ?>
														<span style="font-size:0.7rem; font-weight:800; background:#fef3c7; color:#b45309; padding:2px 8px; border-radius:10px;">⏳ نیاز به پاسخ</span>
													<?php else : ?>
														<span style="font-size:0.7rem; font-weight:800; background:#ecfdf5; color:#047857; padding:2px 8px; border-radius:10px;">✅ پاسخ داده شد</span>
													<?php endif; ?>
													<span style="font-size:0.72rem; color:#94a3b8;"><?php echo count( $msgs ); ?> پیام</span>
												</div>
											</div>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							</div>

							<!-- Column 2: Active Conversation & Reply Pane -->
							<div class="desk-view-col" style="flex:1; display:flex; flex-direction:column; background:#ffffff;">
								
								<!-- Empty State -->
								<div id="deskEmptyState" style="flex:1; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:40px; color:#94a3b8; text-align:center;">
									<div style="font-size:3rem; margin-bottom:12px;">👈</div>
									<h4 style="margin:0 0 6px; font-size:1.1rem; color:#475569;">گفتگویی انتخاب نشده است</h4>
									<p style="margin:0; font-size:0.88rem;">جهت مشاهده پیام‌ها، اطلاعات تماس و ارسال پاسخ زنده، یک گفتگو را از ستون کناری انتخاب کنید.</p>
								</div>

								<!-- Active Conversation Box -->
								<div id="deskActiveBox" style="display:none; flex-direction:column; height:100%;">
									
									<!-- Customer Card Header -->
									<div style="padding:14px 20px; border-bottom:1px solid #e2e8f0; background:#f8fafc; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
										<div>
											<div style="display:flex; align-items:center; gap:8px;">
												<button type="button" id="btnDeskBackToList" class="button button-small" style="display:none;">◀ لیست</button>
												<h4 style="margin:0; font-size:1.05rem; font-weight:800; color:#0f172a;" id="deskHeaderName">نام مشتری</h4>
												<span id="deskHeaderStatus" style="font-size:0.72rem; font-weight:800; padding:2px 8px; border-radius:10px; background:#fef3c7; color:#b45309;">در انتظار پاسخ</span>
											</div>
											<div style="font-size:0.82rem; color:#64748b; margin-top:4px; display:flex; gap:14px; flex-wrap:wrap;">
												<span id="deskHeaderPhone"></span>
												<span id="deskHeaderEmail"></span>
												<span id="deskHeaderSubject"></span>
											</div>
										</div>

										<!-- Quick Action Buttons for Customer -->
										<div style="display:flex; gap:8px; align-items:center;">
											<a href="#" id="deskDirectCallBtn" class="button button-secondary button-small" style="font-weight:700;">📞 تماس تلفنی</a>
											<a href="#" id="deskDirectWaBtn" target="_blank" class="button button-small" style="background:#25D366; color:#fff; border-color:#25D366; font-weight:700;">💬 واتساپ</a>
											<button type="button" id="deskDeleteBtn" class="button button-small" style="color:#dc2626; border-color:#fca5a5; font-weight:700;">🗑️ حذف</button>
										</div>
									</div>

									<!-- Messages Stream Area -->
									<div class="desk-msg-stream" id="deskMsgStream" style="flex:1; overflow-y:auto; padding:20px; background:#f8fafc; display:flex; flex-direction:column; gap:12px; max-height:360px;">
										<!-- Dynamic Bubbles appended here -->
									</div>

									<!-- Canned Responses Quick Chips -->
									<div style="padding:8px 16px; background:#ffffff; border-top:1px solid #f1f5f9; display:flex; gap:6px; overflow-x:auto; white-space:nowrap; align-items:center;">
										<span style="font-size:0.75rem; color:#64748b; font-weight:700; flex-shrink:0;">⚡ پاسخ‌های آماده:</span>
										<button type="button" class="desk-canned-chip" data-text="سلام و درود، سفارش شما در حال آماده‌سازی و بسته‌بندی است.">📦 در حال آماده‌سازی</button>
										<button type="button" class="desk-canned-chip" data-text="کد رهگیری مرسوله پستی تا ساعاتی دیگر به همین شماره پیامک خواهد شد.">🚚 ارسال کد پیگیری</button>
										<button type="button" class="desk-canned-chip" data-text="کالای مورد نظر شما در انبار موجود و با گارانتی اصالت کالا آماده تحویل است.">🛍️ موجودی و اصالت</button>
										<button type="button" class="desk-canned-chip" data-text="جهت هماهنگی سریع‌تر لطفاً با شماره پشتیبانی تلفنی فروشگاه تماس حاصل فرمایید.">📞 تماس تکمیلی</button>
									</div>

									<!-- Live Reply Composer -->
									<div style="padding:14px 18px; border-top:1px solid #e2e8f0; background:#ffffff;">
										<div style="display:flex; gap:10px;">
											<textarea id="deskReplyInput" rows="2" placeholder="متن پاسخ خود را به این مشتری بنویسید (فوراً در چت مشتری نمایش داده خواهد شد)..." style="flex:1; border:1.5px solid #cbd5e1; border-radius:10px; padding:10px 12px; font-family:inherit; font-size:0.9rem; resize:none;"></textarea>
											<button type="button" id="deskSendReplyBtn" class="button button-primary button-large" style="background:#2563eb; font-weight:800; padding:0 24px; border-radius:10px; align-self:stretch;">
												ارسال پاسخ زنده 🚀
											</button>
										</div>
										<div id="deskReplyFeedback" style="font-size:0.8rem; margin-top:6px; font-weight:700;"></div>
									</div>

								</div>
							</div>
						</div>
					</div>

					<!-- 2. VISUAL CHAT THEMES (12 THEMES WITH LARGE PREVIEW CARDS) -->
					<div class="admin-card" style="margin-top:24px;">
						<div class="admin-card-header">
							<h3><span>🎨</span> تم‌های رنگی و ظاهری پنجره چت آنلاین (۱۲ تم متنوع و لوکس)</h3>
							<span class="field-badge field-badge-purple">طراحی مدرن سال ۲۰۲۶</span>
						</div>

						<p style="color:#64748b; font-size:0.92rem; line-height:1.6; margin-top:0;">
							می‌توانید تم گرافیکی مورد نظر خود را از طریق دراپ‌داون زیر یا با کلیک بر روی هر یک از پیش‌نمایش‌های بزرگ انتخاب کنید. پنجره چت فروشگاه بلافاصله به رنگ‌بندی و هویت انتخابی شما درخواهد آمد:
						</p>

						<!-- Large Theme Selector Dropdown -->
						<div style="background:#f8fafc; padding:18px 22px; border-radius:14px; border:1px solid #e2e8f0; margin-bottom:24px; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
							<label for="chat_theme_selector" style="font-weight:800; font-size:1rem; color:#0f172a;">
								انتخاب تم چت آنلاین:
							</label>
							<select name="chat_theme" id="chat_theme_selector" style="font-size:1.05rem; padding:10px 16px; border-radius:10px; border:2px solid #2563eb; min-width:320px; font-weight:700; color:#1e293b; background:#ffffff;">
								<option value="royal-blue" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'royal-blue' ); ?>>۱. آبی رویال و کریستالی (Royal Modern Blue - پیش‌فرض شیک)</option>
								<option value="cyberpunk-dark" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'cyberpunk-dark' ); ?>>۲. دارک نئونی و بنفش سایبرپانک (Cyberpunk Dark Violet)</option>
								<option value="emerald-whatsapp" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'emerald-whatsapp' ); ?>>۳. سبز زمردی و واتساپی (Emerald WhatsApp Pro)</option>
								<option value="magenta-rose" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'magenta-rose' ); ?>>۴. صورتی نئونی و سرخابی لوکس (Luxury Magenta Rose)</option>
								<option value="gold-vip" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'gold-vip' ); ?>>۵. مشکی طلایی VIP لاکچری (Luxury Gold & Obsidian VIP)</option>
								<option value="minimal-slate" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'minimal-slate' ); ?>>۶. مینیمال خنثی و تمیز (Minimalist Slate & Clean)</option>
								<option value="aurora-gradient" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'aurora-gradient' ); ?>>۷. گرادینت شفق قطبی (Aurora Borealis Gradient)</option>
								<option value="sunset-coral" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'sunset-coral' ); ?>>۸. غروب آفتاب کالیفرنیا (Sunset Coral & Warm Orange)</option>
								<option value="telegram-ocean" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'telegram-ocean' ); ?>>۹. چت تلگرامی اقیانوسی (Telegram Ocean Blue)</option>
								<option value="warm-caramel" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'warm-caramel' ); ?>>۱۰. شکلاتی و کاراملی کافه‌ای (Warm Caramel & Mocha)</option>
								<option value="mint-pastel" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'mint-pastel' ); ?>>۱۱. نعنایی و فیروزه‌ای پاستلی (Mint & Pastel Turquoise)</option>
								<option value="frosted-glass" <?php selected( $opts['chat_theme'] ?? 'royal-blue', 'frosted-glass' ); ?>>۱۲. شیشه‌ای نیمه‌شفاف گلس‌مورفیسم (Frosted Glassmorphism)</option>
							</select>
							<span style="color:#64748b; font-size:0.85rem;">(با تغییر منو یا کلیک روی کارت‌های زیر، تم بلافاصله تغییر می‌کند)</span>
						</div>

						<!-- Large Visual Preview Cards Grid (12 Distinct Mockups) -->
						<div class="chat-themes-grid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(330px, 1fr)); gap:20px;">
							
							<?php
							$themes_catalog = array(
								'royal-blue' => array(
									'num'     => '۱',
									'title'   => 'آبی رویال و کریستالی',
									'badge'   => 'پیش‌فرض رسمی',
									'hdr_bg'  => 'linear-gradient(135deg, #1e3a8a, #2563eb)',
									'body_bg' => '#f8fafc',
									'user_bg' => 'linear-gradient(135deg, #2563eb, #1d4ed8)',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#e2e8f0',
									'ai_c'    => '#0f172a',
									'adm_bg'  => '#ecfdf5',
									'adm_b'   => '#a7f3d0',
									'adm_c'   => '#065f46',
									'dots'    => array('#1e3a8a', '#2563eb', '#60a5fa'),
								),
								'cyberpunk-dark' => array(
									'num'     => '۲',
									'title'   => 'دارک نئونی و بنفش سایبرپانک',
									'badge'   => 'OLED Dark Mode',
									'hdr_bg'  => 'linear-gradient(135deg, #090514, #2e1065)',
									'body_bg' => '#0f172a',
									'user_bg' => 'linear-gradient(135deg, #7c3aed, #a855f7)',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#1e293b',
									'ai_b'    => '#475569',
									'ai_c'    => '#f1f5f9',
									'adm_bg'  => '#064e3b',
									'adm_b'   => '#10b981',
									'adm_c'   => '#a7f3d0',
									'dots'    => array('#090514', '#7c3aed', '#a855f7'),
								),
								'emerald-whatsapp' => array(
									'num'     => '۳',
									'title'   => 'سبز زمردی و واتساپی',
									'badge'   => 'پیام‌رسان محبوب',
									'hdr_bg'  => 'linear-gradient(135deg, #064e3b, #059669)',
									'body_bg' => '#efeae2',
									'user_bg' => '#d9fdd3',
									'user_c'  => '#111827',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#e5e7eb',
									'ai_c'    => '#111827',
									'adm_bg'  => '#e0f2fe',
									'adm_b'   => '#bae6fd',
									'adm_c'   => '#075985',
									'dots'    => array('#064e3b', '#059669', '#25D366'),
								),
								'magenta-rose' => array(
									'num'     => '۴',
									'title'   => 'صورتی نئونی و سرخابی لوکس',
									'badge'   => 'بیوتی و فشن',
									'hdr_bg'  => 'linear-gradient(135deg, #831843, #db2777)',
									'body_bg' => '#fff1f2',
									'user_bg' => 'linear-gradient(135deg, #db2777, #f43f5e)',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#fecdd3',
									'ai_c'    => '#881337',
									'adm_bg'  => '#f0fdf4',
									'adm_b'   => '#bbf7d0',
									'adm_c'   => '#166534',
									'dots'    => array('#831843', '#db2777', '#fb7185'),
								),
								'gold-vip' => array(
									'num'     => '۵',
									'title'   => 'مشکی طلایی VIP لاکچری',
									'badge'   => 'طلا و اکسسوری VIP',
									'hdr_bg'  => 'linear-gradient(135deg, #09090b, #1c1917)',
									'body_bg' => '#18181b',
									'user_bg' => 'linear-gradient(135deg, #b45309, #d97706)',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#27272a',
									'ai_b'    => '#78350f',
									'ai_c'    => '#fef3c7',
									'adm_bg'  => '#292524',
									'adm_b'   => '#f59e0b',
									'adm_c'   => '#fef9c3',
									'dots'    => array('#09090b', '#d97706', '#fbbf24'),
								),
								'minimal-slate' => array(
									'num'     => '۶',
									'title'   => 'مینیمال خنثی و تمیز',
									'badge'   => 'طراحی اسکاندیناوی',
									'hdr_bg'  => 'linear-gradient(135deg, #1e293b, #334155)',
									'body_bg' => '#f8fafc',
									'user_bg' => '#334155',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#cbd5e1',
									'ai_c'    => '#0f172a',
									'adm_bg'  => '#f1f5f9',
									'adm_b'   => '#94a3b8',
									'adm_c'   => '#0f172a',
									'dots'    => array('#1e293b', '#475569', '#94a3b8'),
								),
								'aurora-gradient' => array(
									'num'     => '۷',
									'title'   => 'گرادینت شفق قطبی',
									'badge'   => 'ارغوانی و فیروزه‌ای',
									'hdr_bg'  => 'linear-gradient(135deg, #4338ca, #06b6d4)',
									'body_bg' => '#f5f3ff',
									'user_bg' => 'linear-gradient(135deg, #4f46e5, #06b6d4)',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#c7d2fe',
									'ai_c'    => '#312e81',
									'adm_bg'  => '#ecfeff',
									'adm_b'   => '#a5f3fc',
									'adm_c'   => '#164e63',
									'dots'    => array('#4338ca', '#6366f1', '#06b6d4'),
								),
								'sunset-coral' => array(
									'num'     => '۸',
									'title'   => 'غروب آفتاب کالیفرنیا',
									'badge'   => 'مرجانی و صمیمی',
									'hdr_bg'  => 'linear-gradient(135deg, #9a3412, #ea580c)',
									'body_bg' => '#fff7ed',
									'user_bg' => 'linear-gradient(135deg, #f97316, #ea580c)',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#fed7aa',
									'ai_c'    => '#7c2d12',
									'adm_bg'  => '#fef2f2',
									'adm_b'   => '#fecaca',
									'adm_c'   => '#991b1b',
									'dots'    => array('#9a3412', '#ea580c', '#fb923c'),
								),
								'telegram-ocean' => array(
									'num'     => '۹',
									'title'   => 'چت تلگرامی اقیانوسی',
									'badge'   => 'آبی تلگرامی',
									'hdr_bg'  => 'linear-gradient(135deg, #0369a1, #0284c7)',
									'body_bg' => '#f0f9ff',
									'user_bg' => '#e0f2fe',
									'user_c'  => '#0369a1',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#bae6fd',
									'ai_c'    => '#0c4a6e',
									'adm_bg'  => '#f0fdf4',
									'adm_b'   => '#bbf7d0',
									'adm_c'   => '#14532d',
									'dots'    => array('#0369a1', '#0284c7', '#38bdf8'),
								),
								'warm-caramel' => array(
									'num'     => '۱۰',
									'title'   => 'شکلاتی و کاراملی کافه‌ای',
									'badge'   => 'گرم و نوستالژیک',
									'hdr_bg'  => 'linear-gradient(135deg, #451a03, #92400e)',
									'body_bg' => '#fffbeb',
									'user_bg' => 'linear-gradient(135deg, #b45309, #92400e)',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#fde68a',
									'ai_c'    => '#78350f',
									'adm_bg'  => '#fef3c7',
									'adm_b'   => '#f59e0b',
									'adm_c'   => '#78350f',
									'dots'    => array('#451a03', '#92400e', '#f59e0b'),
								),
								'mint-pastel' => array(
									'num'     => '۱۱',
									'title'   => 'نعنایی و فیروزه‌ای پاستلی',
									'badge'   => 'آرامش‌بخش و بهداشتی',
									'hdr_bg'  => 'linear-gradient(135deg, #115e59, #0d9488)',
									'body_bg' => '#f0fdfa',
									'user_bg' => 'linear-gradient(135deg, #0d9488, #14b8a6)',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#ccfbf1',
									'ai_c'    => '#134e4a',
									'adm_bg'  => '#ecfdf5',
									'adm_b'   => '#a7f3d0',
									'adm_c'   => '#065f46',
									'dots'    => array('#115e59', '#0d9488', '#2dd4bf'),
								),
								'frosted-glass' => array(
									'num'     => '۱۲',
									'title'   => 'شیشه‌ای نیمه‌شفاف گلس‌مورفیسم',
									'badge'   => 'کریستالی شفاف',
									'hdr_bg'  => 'linear-gradient(135deg, #1e293b, #2563eb)',
									'body_bg' => '#f1f5f9',
									'user_bg' => 'linear-gradient(135deg, #2563eb, #3b82f6)',
									'user_c'  => '#ffffff',
									'ai_bg'   => '#ffffff',
									'ai_b'    => '#cbd5e1',
									'ai_c'    => '#0f172a',
									'adm_bg'  => '#ecfdf5',
									'adm_b'   => '#a7f3d0',
									'adm_c'   => '#065f46',
									'dots'    => array('#1e293b', '#2563eb', '#e2e8f0'),
								),
							);

							$selected_theme = $opts['chat_theme'] ?? 'royal-blue';

							foreach ( $themes_catalog as $t_slug => $t_info ) :
								$is_active = ( $selected_theme === $t_slug );
							?>
								<div class="chat-theme-card <?php echo $is_active ? 'active' : ''; ?>" data-theme="<?php echo esc_attr( $t_slug ); ?>" style="border:2px solid <?php echo $is_active ? '#2563eb' : '#e2e8f0'; ?>; border-radius:16px; overflow:hidden; background:#ffffff; box-shadow:0 6px 18px rgba(0,0,0,0.05); transition:all 0.25s ease; cursor:pointer; position:relative;">
									
									<!-- Card Header Bar -->
									<div style="padding:10px 14px; background:#f8fafc; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
										<div>
											<strong style="font-size:0.9rem; color:#0f172a;"><?php echo esc_html( $t_info['num'] . '. ' . $t_info['title'] ); ?></strong>
										</div>
										<span style="font-size:0.72rem; font-weight:800; background:#e0f2fe; color:#0284c7; padding:2px 8px; border-radius:8px;"><?php echo esc_html( $t_info['badge'] ); ?></span>
									</div>

									<!-- Mini Mockup Chat Window -->
									<div style="padding:12px; background:<?php echo esc_attr( $t_info['body_bg'] ); ?>;">
										<!-- Mockup Header -->
										<div style="background:<?php echo esc_attr( $t_info['hdr_bg'] ); ?>; color:#fff; border-radius:10px; padding:8px 12px; display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
											<div style="display:flex; align-items:center; gap:8px;">
												<span style="font-size:1.1rem;">👩‍💼</span>
												<span style="font-size:0.8rem; font-weight:700;">پشتیبانی فروشگاه</span>
											</div>
											<span style="font-size:0.68rem; opacity:0.85;">🟢 آنلاین</span>
										</div>

										<!-- Mockup Bubbles -->
										<div style="display:flex; flex-direction:column; gap:8px; font-size:0.78rem;">
											<!-- Customer Bubble -->
											<div style="align-self:flex-end; background:<?php echo esc_attr( $t_info['user_bg'] ); ?>; color:<?php echo esc_attr( $t_info['user_c'] ); ?>; border-radius:10px; border-bottom-left-radius:2px; padding:6px 10px; max-width:82%;">
												سلام، ارسال فوری دارید؟
											</div>
											<!-- AI Bubble -->
											<div style="align-self:flex-start; background:<?php echo esc_attr( $t_info['ai_bg'] ); ?>; border:1px solid <?php echo esc_attr( $t_info['ai_b'] ); ?>; color:<?php echo esc_attr( $t_info['ai_c'] ); ?>; border-radius:10px; border-bottom-right-radius:2px; padding:6px 10px; max-width:85%;">
												<div style="font-size:0.68rem; font-weight:800; margin-bottom:2px;">🤖 پشتیبان هوشمند</div>
												بله، کلیه سفارشات ثبت‌شده تا ۱۲ همان روز ارسال می‌شوند.
											</div>
											<!-- Admin Bubble -->
											<div style="align-self:flex-start; background:<?php echo esc_attr( $t_info['adm_bg'] ); ?>; border:1px solid <?php echo esc_attr( $t_info['adm_b'] ); ?>; color:<?php echo esc_attr( $t_info['adm_c'] ); ?>; border-radius:10px; border-bottom-right-radius:2px; padding:6px 10px; max-width:85%;">
												<div style="font-size:0.68rem; font-weight:800; margin-bottom:2px;">👨‍💼 پاسخ ادمین</div>
												همچنین بسته‌بندی ویژه کادویی هم فعال است.
											</div>
										</div>
									</div>

									<!-- Card Footer with Color Dots & Select Button -->
									<div style="padding:10px 14px; background:#ffffff; border-top:1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center;">
										<div style="display:flex; gap:6px;">
											<?php foreach ( $t_info['dots'] as $dot_color ) : ?>
												<span style="width:14px; height:14px; border-radius:50%; background:<?php echo esc_attr( $dot_color ); ?>; display:inline-block; border:1px solid rgba(0,0,0,0.1);"></span>
											<?php endforeach; ?>
										</div>
										<button type="button" class="button button-small btn-pick-theme" data-theme="<?php echo esc_attr( $t_slug ); ?>" style="font-weight:800; font-size:0.78rem;">
											<?php echo $is_active ? '✅ تم فعال' : 'انتخاب این تم'; ?>
										</button>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- 3. CHAT BUTTON DESIGNS ON STOREFRONT (6 BUTTON STYLES) -->
					<div class="admin-card" style="margin-top:24px;">
						<div class="admin-card-header">
							<h3><span>🔘</span> طرح‌های مختلف دکمه شناور چت در صفحه فروشگاه</h3>
							<span class="field-badge field-badge-blue">۶ طرح مدرن و جذاب</span>
						</div>

						<p style="color:#64748b; font-size:0.92rem; line-height:1.6; margin-top:0;">
							طرح دکمه شناور گوشه صفحه فروشگاه را تعیین کنید تا مشتریان با بهترین جلوه بصری به بخش پشتیبانی هدایت شوند:
						</p>

						<!-- Large Button Style Selector Dropdown -->
						<div style="background:#f8fafc; padding:18px 22px; border-radius:14px; border:1px solid #e2e8f0; margin-bottom:24px; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
							<label for="chat_button_style_selector" style="font-weight:800; font-size:1rem; color:#0f172a;">
								انتخاب طرح دکمه چت:
							</label>
							<select name="chat_button_style" id="chat_button_style_selector" style="font-size:1.05rem; padding:10px 16px; border-radius:10px; border:2px solid #2563eb; min-width:320px; font-weight:700; color:#1e293b; background:#ffffff;">
								<option value="pill-label" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'pill-label' ); ?>>۱. کپسولی با متن «پشتیبانی آنلاین» و آیکون (Pill with Label - پیش‌فرض)</option>
								<option value="circle-glow" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'circle-glow' ); ?>>۲. دایره مدرن نئونی با نور رنگی متناسب با تم (Glowing Circle)</option>
								<option value="avatar-ring" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'avatar-ring' ); ?>>۳. آواتار پشتیبان انسانی با حلقه وضعیت آنلاین (Avatar Ring)</option>
								<option value="frosted-glass" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'frosted-glass' ); ?>>۴. شیشه‌ای مات فلوتینگ با افکت بلور گلس‌مورفیک (Frosted Glass)</option>
								<option value="edge-tab" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'edge-tab' ); ?>>۵. زبانه چسبان لبه صفحه بدون اشغال فضای محتوا (Edge Tab)</option>
								<option value="radar-pulse" <?php selected( $opts['chat_button_style'] ?? 'pill-label', 'radar-pulse' ); ?>>۶. رادار پالس امواج صوتی متحرک دوگانه (Radar Wave Pulse)</option>
							</select>
						</div>

						<!-- Visual Button Style Cards Grid -->
						<div class="chat-btn-styles-grid" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:18px;">
							<?php
							$btn_styles = array(
								'pill-label'    => array('name' => '۱. کپسولی با برچسب و نشانگر زنده', 'desc' => 'حالت استاندارد با بالاترین نرخ کلیک'),
								'circle-glow'   => array('name' => '۲. دایره مدرن نئونی با نور پالس', 'desc' => 'دایره مینیمال با نور رنگی درخشان'),
								'avatar-ring'   => array('name' => '۳. آواتار پشتیبان با حلقه آنلاین', 'desc' => 'حس گفتگوی رودررو با یک انسان واقعی'),
								'frosted-glass' => array('name' => '۴. حباب شیشه‌ای مات گلس‌مورفیسم', 'desc' => 'طراحی شفاف کریستالی با بلور لوکس'),
								'edge-tab'      => array('name' => '۵. زبانه چسبان لبه کناری اسکرین', 'desc' => 'چسبیده به لبه صفحه، عالی برای موبایل'),
								'radar-pulse'   => array('name' => '۶. رادار صوتی با امواج دوگانه', 'desc' => 'امواج پیوسته رادار برای جلب توجه فوری'),
							);
							$selected_btn = $opts['chat_button_style'] ?? 'pill-label';
							foreach ( $btn_styles as $b_slug => $b_data ) :
								$is_active_btn = ( $selected_btn === $b_slug );
							?>
								<div class="chat-btn-card <?php echo $is_active_btn ? 'active' : ''; ?>" data-btn-style="<?php echo esc_attr( $b_slug ); ?>" style="border:2px solid <?php echo $is_active_btn ? '#2563eb' : '#e2e8f0'; ?>; border-radius:14px; padding:18px; background:#ffffff; cursor:pointer; box-shadow:0 4px 14px rgba(0,0,0,0.04); transition:all 0.2s;">
									<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
										<strong style="color:#0f172a; font-size:0.92rem;"><?php echo esc_html( $b_data['name'] ); ?></strong>
										<span style="font-size:0.75rem; color:#64748b;"><?php echo $is_active_btn ? '✅ فعال' : ''; ?></span>
									</div>
									<p style="margin:0 0 14px; font-size:0.82rem; color:#64748b;"><?php echo esc_html( $b_data['desc'] ); ?></p>
									<button type="button" class="button button-small btn-pick-btn-style" data-btn-style="<?php echo esc_attr( $b_slug ); ?>" style="font-weight:700; width:100%;">
										<?php echo $is_active_btn ? '✅ این طرح انتخاب شده' : 'انتخاب این طرح دکمه'; ?>
									</button>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<!-- 4. CHAT FORM FIELDS & GENERAL SETTINGS -->
					<div class="admin-card" style="margin-top:24px;">
						<div class="admin-card-header">
							<h3><span>⚙️</span> تنظیمات عمومی پنجره چت و سفارشی‌سازی فیلدهای ورودی</h3>
							<span class="field-badge field-badge-purple">امکان حذف و اضافه فیلدها</span>
						</div>

						<table class="form-table">
							<tr>
								<th scope="row">فعال‌سازی دکمه چت آنلاین:</th>
								<td>
									<label>
										<input type="checkbox" name="enable_support_chat" value="1" <?php checked( ! empty( $opts['enable_support_chat'] ) ); ?>>
										دکمه شناور چت آنلاین و پنجره گفتگو در فروشگاه نمایش داده شود.
									</label>
								</td>
							</tr>
							<tr>
								<th scope="row">موقعیت دکمه چت در صفحه:</th>
								<td>
									<select name="chat_button_position" class="regular-text">
										<option value="left" <?php selected( $opts['chat_button_position'] ?? 'left', 'left' ); ?>>پایین سمت چپ (توصیه شده)</option>
										<option value="right" <?php selected( $opts['chat_button_position'] ?? 'left', 'right' ); ?>>پایین سمت راست</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">عنوان سربرگ پنجره چت:</th>
								<td>
									<input type="text" name="chat_window_title" value="<?php echo esc_attr( $opts['chat_window_title'] ?? 'پشتیبانی آنلاین فروشگاه' ); ?>" class="large-text">
								</td>
							</tr>
							<tr>
								<th scope="row">پیام خوش‌آمدگویی اولیه:</th>
								<td>
									<textarea name="chat_welcome_message" rows="3" class="large-text"><?php echo esc_textarea( $opts['chat_welcome_message'] ?? 'سلام! خوش آمدید 👋 هرگونه سوالی درباره کالاها، قیمت‌ها یا ثبت سفارش دارید بنویسید تا سریعاً پاسخ دهیم.' ); ?></textarea>
								</td>
							</tr>
						</table>

						<!-- Field Customization Table -->
						<div style="margin-top:20px; border-top:1px solid #f1f5f9; padding-top:20px;">
							<h4 style="margin:0 0 12px; font-size:1.05rem; font-weight:800; color:#1e293b;">
								📋 تنظیم فیلدهای ورودی در فرم چت پشتیبانی (حذف/اضافه و تیک‌های الزامی بودن)
							</h4>
							<p style="color:#64748b; font-size:0.88rem; margin:0 0 16px;">
								می‌توانید هر فیلد را در فرم چت فعال یا غیرفعال کنید و با تیک الزامی مشخص نمایید که پر کردن آن برای مشتری اجباری است یا اختیاری:
							</p>

							<table class="wp-list-table widefat fixed striped" style="border-radius:10px; overflow:hidden;">
								<thead>
									<tr>
										<th style="font-weight:800; width:180px;">عنوان فیلد</th>
										<th style="font-weight:800; width:140px;">نمایش در فرم چت</th>
										<th style="font-weight:800; width:140px;">الزامی بودن فیلد</th>
										<th style="font-weight:800;">توضیحات و کاربرد</th>
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
										<td style="color:#64748b; font-size:0.85rem;">در پیام ارسالی به ادمین و پاسخ هوش مصنوعی جهت خطاب قرار دادن مشتری استفاده می‌شود.</td>
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
										<td style="color:#64748b; font-size:0.85rem;">شماره موبایل مشتری برای تماس مستقیم پشتیبان یا ارسال پیام در پیام‌رسان‌ها (توصیه: الزامی).</td>
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
										<td style="color:#64748b; font-size:0.85rem;">ارسال پاسخ رسمی فاکتور یا پیگیری برای مشتریان ایمیلی.</td>
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
										<td style="color:#64748b; font-size:0.85rem;">مشتری موضوع پیام را وارد می‌کند (مثلاً استعلام موجودی، پیگیری مرسوله، درخواست تخفیف).</td>
									</tr>
									<tr>
										<td><strong>📝 متن پیام یا سوال</strong></td>
										<td><span style="color:#059669; font-weight:700;">همیشه فعال</span></td>
										<td><span style="color:#dc2626; font-weight:700;">همیشه الزامی</span></td>
										<td style="color:#64748b; font-size:0.85rem;">متن اصلی سوال مشتری از فروشگاه.</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

				</div>

				<!-- ================= TAB 4: AI & COORDINATION ================= -->
				<div id="tab-ai" class="scraper-tab-panel">
					<div class="admin-card">
						<div class="admin-card-header">
							<h3><span>🤖</span> نحوه تعامل و هماهنگی بین هوش مصنوعی، ادمین پاسخگو و مشتری</h3>
							<span class="field-badge field-badge-purple">دستیار هوشمند ۲۴ ساعته</span>
						</div>

						<p style="color:#64748b; font-size:0.92rem; line-height:1.6; margin-top:0;">
							در این بخش نحوه عملکرد و همکاری هوش مصنوعی با کارشناسان و ادمین‌های انسانی فروشگاه را تنظیم کنید. هوش مصنوعی می‌تواند به عنوان پاسخگوی خط اول بلافاصله به مشتری پاسخ داده و همزمان اطلاعات را برای پیگیری نهایی به پیام‌رسان‌های ادمین بفرستد.
						</p>

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
								<th scope="row">موتور هوش مصنوعی:</th>
								<td>
									<select name="ai_provider" class="regular-text">
										<option value="auto" <?php selected( $opts['ai_provider'] ?? 'auto', 'auto' ); ?>>خودکار بر اساس تنظیمات اسکرپر (connections.json)</option>
										<option value="openai" <?php selected( $opts['ai_provider'] ?? 'auto', 'openai' ); ?>>OpenAI (ChatGPT)</option>
										<option value="gemini" <?php selected( $opts['ai_provider'] ?? 'auto', 'gemini' ); ?>>Google Gemini</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row">کلید API اختصاصی (در صورت انتخاب مستقل):</th>
								<td>
									<input type="password" name="ai_api_key" value="<?php echo esc_attr( $opts['ai_api_key'] ?? '' ); ?>" class="regular-text" dir="ltr" placeholder="sk-...">
									<input type="text" name="ai_model" value="<?php echo esc_attr( $opts['ai_model'] ?? '' ); ?>" class="regular-text" dir="ltr" placeholder="نام مدل (مثلاً gpt-4o-mini)">
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

				<!-- ================= TAB 6: WOOCOMMERCE & SCRAPER ================= -->
				<div id="tab-woocommerce" class="scraper-tab-panel">
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
						<div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:18px 20px;">
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

				<!-- Fixed Save Settings Bar -->
				<div class="scraper-save-bar" style="background:#ffffff; border:1px solid #e2e8f0; border-radius:14px; padding:16px 24px; display:flex; justify-content:space-between; align-items:center; margin-top:20px; box-shadow:0 4px 14px rgba(0,0,0,0.04);">
					<div style="font-size:0.92rem; color:#64748b; font-weight:600;">
						💡 تغییرات اعمال‌شده در هر یک از زبانه‌ها با زدن کلید زیر ذخیره خواهند شد.
					</div>
					<input type="submit" name="scraper_shop_save" class="button button-primary button-large" value="💾 ذخیره تمامی تنظیمات فروشگاه و هوش مصنوعی" style="font-weight:800; padding:8px 28px; font-size:1rem; border-radius:10px;">
				</div>
			</form>
		</div>

		<script>
		jQuery(document).ready(function($){
			// Tab switching logic
			$('#scraperAdminTabs .scraper-tab-link').on('click', function(e){
				e.preventDefault();
				var tabId = $(this).attr('data-tab');
				$('#scraperAdminTabs .scraper-tab-link').removeClass('active');
				$(this).addClass('active');
				$('.scraper-tab-panel').removeClass('active');
				$('#' + tabId).addClass('active');

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

			// Support Desk: Interactive Thread Selection & Live Conversation
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
						$btn.prop('disabled', false).text('ارسال پاسخ زنده 🚀');
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
									.removeClass('field-badge-orange')
									.css({background:'#ecfdf5', color:'#047857'})
									.text('✅ پاسخ داده شد');
							}
							$('#deskHeaderStatus').css({background: '#ecfdf5', color: '#047857'}).text('✅ پاسخ داده شد');
						} else {
							$feedback.html('<span style="color:#dc2626;">❌ ' + (res.data || 'خطا در ارسال پاسخ.') + '</span>');
						}
					},
					error: function(){
						$btn.prop('disabled', false).text('ارسال پاسخ زنده 🚀');
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
				$('.desk-filter-btn').removeClass('active').css({background:'#ffffff', borderColor:'#cbd5e1'});
				$(this).addClass('active').css({background:'#e2e8f0', borderColor:'#94a3b8'});
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

			// Refresh desk button
			$('#btnRefreshAdminDesk').on('click', function(){
				location.reload();
			});

			// 12 Visual Themes Selector Sync
			$('#chat_theme_selector').on('change', function(){
				var selected = $(this).val();
				$('.chat-theme-card').removeClass('active').css('borderColor', '#e2e8f0');
				$('.chat-theme-card[data-theme="' + selected + '"]').addClass('active').css('borderColor', '#2563eb');
				$('.btn-pick-theme').text('انتخاب این تم');
				$('.btn-pick-theme[data-theme="' + selected + '"]').text('✅ تم فعال');
			});

			$('.chat-theme-card, .btn-pick-theme').on('click', function(e){
				var theme = $(this).attr('data-theme');
				if (theme) {
					$('#chat_theme_selector').val(theme).trigger('change');
				}
			});

			// 6 Button Styles Selector Sync
			$('#chat_button_style_selector').on('change', function(){
				var selected = $(this).val();
				$('.chat-btn-card').removeClass('active').css('borderColor', '#e2e8f0');
				$('.chat-btn-card[data-btn-style="' + selected + '"]').addClass('active').css('borderColor', '#2563eb');
				$('.btn-pick-btn-style').text('انتخاب این طرح دکمه');
				$('.btn-pick-btn-style[data-btn-style="' + selected + '"]').text('✅ این طرح انتخاب شده');
			});

			$('.chat-btn-card, .btn-pick-btn-style').on('click', function(e){
				var btnStyle = $(this).attr('data-btn-style');
				if (btnStyle) {
					$('#chat_button_style_selector').val(btnStyle).trigger('change');
				}
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

// Global activation hook
register_activation_hook( __FILE__, array( 'Scraper_Auto_Shop_Plugin', 'on_activate' ) );

// Initialize
if ( did_action( 'plugins_loaded' ) ) {
	Scraper_Auto_Shop_Plugin::init();
} else {
	add_action( 'plugins_loaded', array( 'Scraper_Auto_Shop_Plugin', 'init' ) );
}
