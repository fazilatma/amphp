<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 11.5.0
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
			'enable_shop_takeover'        => true,
			'replace_site_header'         => true,
			'show_top_bar'                => true,
			'top_bar_notice'              => 'تخفیف ویژه امروز: ارسال رایگان برای سفارش‌های بالای ۴۰۰ هزار تومان! 🚚',
			'contact_phone'               => '۰۲۱-۱۲۳۴۵۶۷۸',
			'support_hours'               => 'پاسخگویی ۹ الی ۲۲',
			'shop_title'                  => 'فروشگاه آنلاین نوآوران',
			'shop_subtitle'               => 'تنوع بی‌نظیر کالاها با تضمین اصالت، سلامت فیزیکی و ارسال سریع به سراسر کشور',
			'price_markup_percent'        => 20,
			'price_fixed_add'             => 0,
			'price_rounding'              => '1000', // none, 1000, 10000
			'currency_symbol'             => 'تومان',
			'default_fallback_price'      => 150000, // Fallback base price if source price is missing/0
			'fallback_price_behavior'     => 'use_fallback', // 'use_fallback' or 'call_for_price'
			'accent_color'                => '#2563eb',
			'products_per_page'           => 16,
			'show_features_banner'        => true,
			'show_special_badge'          => true,
			'free_shipping_threshold'     => 400000,
			'enable_support_chat'        => true,
			'chat_button_position'       => 'left', // 'left' or 'right'
			'chat_window_title'          => 'پشتیبانی آنلاین فروشگاه',
			'chat_welcome_message'       => 'سلام! خوش آمدید 👋 هرگونه سوالی درباره محصولات یا ثبت سفارش دارید، پیام بگذارید تا همکاران ما سریعاً پاسخ دهند.',
			'bale_token'                 => '',
			'bale_chat_id'               => '',
			'telegram_token'             => '',
			'telegram_chat_id'           => '',
			'rubika_token'               => '',
			'rubika_chat_id'             => '',
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

		// Support chat AJAX endpoints
		add_action( 'wp_ajax_submit_support_chat', array( __CLASS__, 'ajax_submit_support_chat' ) );
		add_action( 'wp_ajax_nopriv_submit_support_chat', array( __CLASS__, 'ajax_submit_support_chat' ) );
		add_action( 'wp_ajax_test_support_messengers', array( __CLASS__, 'ajax_test_support_messengers' ) );

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
	 * AJAX endpoint for customer support chat submission.
	 */
	public static function ajax_submit_support_chat() {
		check_ajax_referer( 'scraper_support_chat_nonce', 'nonce' );

		$name    = sanitize_text_field( $_POST['name'] ?? '' );
		$phone   = sanitize_text_field( $_POST['phone'] ?? '' );
		$message = sanitize_textarea_field( $_POST['message'] ?? '' );

		if ( empty( $name ) ) {
			$name = 'کاربر مهمان';
		}

		if ( empty( $phone ) ) {
			wp_send_json_error( 'لطفاً شماره تماس یا موبایل خود را وارد نمایید.' );
		}

		if ( empty( $message ) ) {
			wp_send_json_error( 'لطفاً متن پیام خود را بنویسید.' );
		}

		$time_str  = date_i18n( 'Y/m/d - H:i' );
		$site_name = get_bloginfo( 'name' );

		$formatted_text = "💬 پیام جدید از چت پشتیبانی آنلاین فروشگاه\n\n"
			. "👤 نام مشتری: {$name}\n"
			. "📱 شماره تماس: {$phone}\n"
			. "🕒 زمان: {$time_str}\n"
			. "🏢 فروشگاه: {$site_name}\n\n"
			. "📝 متن پیام یا سوال:\n"
			. "{$message}";

		$send_result = self::send_message_to_messengers( $formatted_text );

		// Save in chat logs
		$logs = get_option( 'scraper_support_chat_logs', array() );
		if ( ! is_array( $logs ) ) {
			$logs = array();
		}
		array_unshift( $logs, array(
			'name'    => $name,
			'phone'   => $phone,
			'message' => $message,
			'time'    => $time_str,
			'sent_ok' => $send_result['ok'],
			'sent_to' => $send_result['sent'],
		) );
		$logs = array_slice( $logs, 0, 50 ); // keep last 50
		update_option( 'scraper_support_chat_logs', $logs, false );

		wp_send_json_success( array(
			'message' => 'پیام شما با موفقیت ثبت و برای کارشناسان پشتیبانی ارسال شد. به زودی با شما تماس خواهیم گرفت.',
			'status'  => $send_result,
		) );
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
				grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
				gap: 22px;
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

			/* Online Support Chat Button & Window */
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
			.support-chat-btn {
				display: flex;
				align-items: center;
				gap: 10px;
				background: linear-gradient(135deg, var(--sp-accent, #2563eb) 0%, #7c3aed 100%);
				color: #fff;
				border: none;
				border-radius: 50px;
				padding: 12px 20px;
				cursor: pointer;
				box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
				transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
				position: relative;
				font-size: 0.95rem;
				font-weight: 700;
			}
			.support-chat-btn:hover {
				transform: translateY(-3px) scale(1.03);
				box-shadow: 0 14px 30px rgba(37, 99, 235, 0.5);
			}
			.support-chat-btn svg {
				width: 24px;
				height: 24px;
				fill: none;
				stroke: currentColor;
				stroke-width: 2;
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
				width: 370px;
				max-width: calc(100vw - 30px);
				background: #ffffff;
				border-radius: 20px;
				box-shadow: 0 20px 50px rgba(15, 23, 42, 0.22);
				border: 1px solid rgba(226, 232, 240, 0.9);
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
			.support-chat-window.open {
				display: flex;
			}
			@keyframes chatSlideUp {
				from { opacity: 0; transform: translateY(20px) scale(0.95); }
				to { opacity: 1; transform: translateY(0) scale(1); }
			}

			/* Chat Header */
			.chat-hdr {
				background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
				color: #ffffff;
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
				background: linear-gradient(135deg, #2563eb, #7c3aed);
				display: flex;
				align-items: center;
				justify-content: center;
				font-size: 1.3rem;
				position: relative;
				box-shadow: 0 4px 10px rgba(0,0,0,0.2);
			}
			.chat-agent-avatar::after {
				content: '';
				position: absolute;
				bottom: 0;
				left: 0;
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
				color: #fff;
			}
			.chat-hdr-info span {
				font-size: 0.78rem;
				color: #94a3b8;
				display: flex;
				align-items: center;
				gap: 4px;
			}
			.chat-close-btn {
				background: rgba(255, 255, 255, 0.12);
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
				background: rgba(255, 255, 255, 0.25);
			}

			/* Chat Body */
			.chat-body-scroll {
				padding: 16px 18px;
				max-height: 280px;
				min-height: 150px;
				overflow-y: auto;
				background: #f8fafc;
				display: flex;
				flex-direction: column;
				gap: 12px;
			}
			.chat-msg-bubble {
				max-width: 85%;
				padding: 12px 15px;
				border-radius: 14px;
				font-size: 0.9rem;
				line-height: 1.5;
				position: relative;
				word-break: break-word;
			}
			.chat-msg-bubble.incoming {
				align-self: flex-start;
				background: #ffffff;
				color: #1e293b;
				border-bottom-right-radius: 4px;
				box-shadow: 0 2px 8px rgba(0,0,0,0.04);
				border: 1px solid #e2e8f0;
			}
			.chat-msg-bubble.outgoing {
				align-self: flex-end;
				background: #2563eb;
				color: #ffffff;
				border-bottom-left-radius: 4px;
				box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
			}
			.chat-msg-time {
				font-size: 0.7rem;
				margin-top: 4px;
				opacity: 0.7;
				text-align: left;
			}

			/* Chat Footer Form */
			.chat-footer {
				padding: 16px 18px;
				background: #ffffff;
				border-top: 1px solid #f1f5f9;
			}
			.chat-input-group {
				margin-bottom: 10px;
			}
			.chat-input-group input,
			.chat-input-group textarea {
				width: 100%;
				box-sizing: border-box;
				border: 1.5px solid #e2e8f0;
				border-radius: 10px;
				padding: 9px 12px;
				font-family: inherit;
				font-size: 0.88rem;
				color: #1e293b;
				background: #f8fafc;
				transition: all 0.2s ease;
			}
			.chat-input-group input:focus,
			.chat-input-group textarea:focus {
				outline: none;
				border-color: #2563eb;
				background: #fff;
				box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
			}
			.chat-submit-btn {
				width: 100%;
				background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
				color: #fff;
				border: none;
				border-radius: 10px;
				padding: 11px;
				font-family: inherit;
				font-size: 0.95rem;
				font-weight: 700;
				cursor: pointer;
				display: flex;
				align-items: center;
				justify-content: center;
				gap: 8px;
				transition: all 0.2s;
				box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
			}
			.chat-submit-btn:hover {
				opacity: 0.95;
				transform: translateY(-1px);
			}
			.chat-submit-btn:disabled {
				opacity: 0.6;
				cursor: not-allowed;
				transform: none;
			}

			/* Mobile Responsiveness */
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
					display: none;
				}
				.support-chat-btn {
					padding: 12px;
					border-radius: 50%;
				}
				.support-chat-window {
					bottom: 140px !important;
					left: 15px !important;
					right: 15px !important;
					width: auto !important;
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
				.products-grid {
					grid-template-columns: repeat(2, 1fr);
					gap: 12px;
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

				<!-- Products Grid -->
				<div class="products-grid" id="productsGrid">
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
					$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#';
					?>
					<a href="<?php echo esc_url( $checkout_url ); ?>" class="btn-card-buy" style="display:block; width:100%; text-align:center; padding:14px; font-size:1.05rem;">
						تکمیل سفارش و تسویه حساب
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
			<div class="support-chat-wrapper <?php echo 'right' === ( $settings['chat_button_position'] ?? 'left' ) ? 'pos-right' : 'pos-left'; ?>" id="supportChatWrap">
				<button type="button" class="support-chat-btn" id="supportChatTrigger" aria-label="پشتیبانی آنلاین">
					<span class="support-chat-badge"></span>
					<svg viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
					<span class="support-chat-label">پشتیبانی آنلاین</span>
				</button>

				<div class="support-chat-window" id="supportChatBox">
					<div class="chat-hdr">
						<div class="chat-hdr-agent">
							<div class="chat-agent-avatar">👩‍💼</div>
							<div class="chat-hdr-info">
								<h4><?php echo esc_html( $settings['chat_window_title'] ?? 'پشتیبانی آنلاین فروشگاه' ); ?></h4>
								<span><span style="color:#10b981;">●</span> آنلاین • پاسخگویی سریع</span>
							</div>
						</div>
						<button type="button" class="chat-close-btn" id="supportChatClose" aria-label="بستن">✕</button>
					</div>

					<div class="chat-body-scroll" id="supportChatBody">
						<div class="chat-msg-bubble incoming">
							<div style="font-weight:700; font-size:0.8rem; color:#2563eb; margin-bottom:4px;">پشتیبانی فروشگاه</div>
							<div><?php echo nl2br( esc_html( $settings['chat_welcome_message'] ?? 'سلام! خوش آمدید 👋 هرگونه سوالی درباره محصولات یا ثبت سفارش دارید، پیام بگذارید تا همکاران ما سریعاً پاسخ دهند.' ) ); ?></div>
							<div class="chat-msg-time"><?php echo esc_html( date_i18n( 'H:i' ) ); ?></div>
						</div>
					</div>

					<div class="chat-footer">
						<form id="supportChatForm" onsubmit="return false;">
							<div class="chat-input-group">
								<input type="text" id="chatNameInput" placeholder="نام شما (اختیاری)" maxlength="60">
							</div>
							<div class="chat-input-group">
								<input type="tel" id="chatPhoneInput" placeholder="شماره موبایل / تماس (الزامی)*" required maxlength="20" dir="ltr">
							</div>
							<div class="chat-input-group">
								<textarea id="chatMsgInput" placeholder="سوال یا پیام خود را بنویسید..." rows="2" required maxlength="1000"></textarea>
							</div>
							<button type="submit" id="chatSendBtn" class="chat-submit-btn">
								<span>ارسال پیام به پشتیبانی 🚀</span>
							</button>
						</form>
						<div id="chatSuccessCard" style="display:none; text-align:center; padding:15px 5px;">
							<div style="font-size:2.2rem; margin-bottom:8px;">✅</div>
							<div style="font-weight:800; color:#059669; font-size:1rem; margin-bottom:6px;">پیام شما ارسال شد!</div>
							<div style="font-size:0.85rem; color:#64748b; line-height:1.5;">کارشناسان پشتیبانی پیام شما را دریافت کردند و به زودی از طریق پیام‌رسان یا تماس پاسخ خواهند داد.</div>
							<button type="button" id="chatAskAnotherBtn" style="margin-top:12px; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:8px; padding:6px 14px; font-family:inherit; font-size:0.85rem; cursor:pointer; color:#334155; font-weight:700;">ارسال پیام جدید</button>
						</div>
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

			// Filtering & Searching Logic
			let currentCat = 'all';
			let searchQuery = '';

			const headerSearch = document.getElementById('headerLiveSearch');
			const clearBtn = document.getElementById('searchClearBtn');
			const noResults = document.getElementById('searchNoResults');
			const resetBtn = document.getElementById('resetSearchBtn');

			function applyFilters() {
				const cards = app.querySelectorAll('.product-card');
				let visibleCount = 0;

				cards.forEach(card => {
					const cat = card.getAttribute('data-cat');
					const title = card.getAttribute('data-title');

					const matchCat = (currentCat === 'all' || cat === currentCat);
					const matchSearch = (!searchQuery || title.includes(searchQuery));

					if (matchCat && matchSearch) {
						card.style.display = 'flex';
						visibleCount++;
					} else {
						card.style.display = 'none';
					}
				});

				const counter = document.getElementById('productCounter');
				if (counter) {
					counter.textContent = 'نمایش ' + toFa(visibleCount) + ' محصول فعال';
				}

				if (noResults) {
					noResults.style.display = (visibleCount === 0 && cards.length > 0) ? 'block' : 'none';
				}
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
								cart[idx].qty++;
								saveCart();
							});
						});

						listEl.querySelectorAll('.cart-qty-minus').forEach(btn => {
							btn.addEventListener('click', () => {
								const idx = parseInt(btn.getAttribute('data-idx'));
								if (cart[idx].qty > 1) {
									cart[idx].qty--;
								} else {
									cart.splice(idx, 1);
								}
								saveCart();
							});
						});

						listEl.querySelectorAll('.cart-item-del').forEach(btn => {
							btn.addEventListener('click', () => {
								const idx = parseInt(btn.getAttribute('data-idx'));
								cart.splice(idx, 1);
								saveCart();
								showToast('کالا از سبد خرید حذف شد');
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

			// Support Chat Widget Logic
			const chatTrigger = document.getElementById('supportChatTrigger');
			const chatBox = document.getElementById('supportChatBox');
			const chatClose = document.getElementById('supportChatClose');
			const chatForm = document.getElementById('supportChatForm');
			const chatSendBtn = document.getElementById('chatSendBtn');
			const chatBody = document.getElementById('supportChatBody');
			const chatSuccessCard = document.getElementById('chatSuccessCard');
			const chatAskAnotherBtn = document.getElementById('chatAskAnotherBtn');

			if (chatTrigger && chatBox) {
				chatTrigger.addEventListener('click', () => {
					chatBox.classList.toggle('open');
					if (chatBox.classList.contains('open')) {
						setTimeout(() => {
							const phoneInput = document.getElementById('chatPhoneInput');
							if (phoneInput) phoneInput.focus();
						}, 150);
					}
				});

				if (chatClose) {
					chatClose.addEventListener('click', () => {
						chatBox.classList.remove('open');
					});
				}

				// Close chat if clicked outside
				document.addEventListener('click', (e) => {
					if (chatBox.classList.contains('open') && !chatBox.contains(e.target) && !chatTrigger.contains(e.target)) {
						chatBox.classList.remove('open');
					}
				});

				if (chatForm) {
					chatForm.addEventListener('submit', (e) => {
						e.preventDefault();
						const name = (document.getElementById('chatNameInput').value || '').trim();
						const phone = (document.getElementById('chatPhoneInput').value || '').trim();
						const message = (document.getElementById('chatMsgInput').value || '').trim();

						if (!phone) {
							showToast('لطفاً شماره تماس خود را وارد نمایید.', 'error');
							return;
						}
						if (!message) {
							showToast('لطفاً متن پیام خود را وارد نمایید.', 'error');
							return;
						}

						chatSendBtn.disabled = true;
						chatSendBtn.innerHTML = '<span>در حال ارسال پیام... ⏳</span>';

						const formData = new FormData();
						formData.append('action', 'submit_support_chat');
						formData.append('nonce', '<?php echo esc_js( wp_create_nonce( 'scraper_support_chat_nonce' ) ); ?>');
						formData.append('name', name);
						formData.append('phone', phone);
						formData.append('message', message);

						fetch('<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>', {
							method: 'POST',
							body: formData
						})
						.then(res => res.json())
						.then(res => {
							chatSendBtn.disabled = false;
							chatSendBtn.innerHTML = '<span>ارسال پیام به پشتیبانی 🚀</span>';

							if (res.success) {
								// Add outgoing bubble to chat
								const userBubble = document.createElement('div');
								userBubble.className = 'chat-msg-bubble outgoing';
								userBubble.innerHTML = `<div style="font-weight:700; font-size:0.8rem; margin-bottom:3px;">شما (${escapeHtml(name || 'مشتری')})</div><div>${escapeHtml(message)}</div><div class="chat-msg-time">ارسال شد ✓✓</div>`;
								chatBody.appendChild(userBubble);
								chatBody.scrollTop = chatBody.scrollHeight;

								// Show success card
								chatForm.style.display = 'none';
								chatSuccessCard.style.display = 'block';
								showToast('✅ پیام شما به پشتیبانی ارسال شد.');
							} else {
								showToast(res.data || 'خطا در ثبت پیام.', 'error');
							}
						})
						.catch(err => {
							chatSendBtn.disabled = false;
							chatSendBtn.innerHTML = '<span>ارسال پیام به پشتیبانی 🚀</span>';
							showToast('خطای ارتباط با سرور. لطفاً دوباره تلاش کنید.', 'error');
						});
					});
				}

				if (chatAskAnotherBtn) {
					chatAskAnotherBtn.addEventListener('click', () => {
						document.getElementById('chatMsgInput').value = '';
						chatSuccessCard.style.display = 'none';
						chatForm.style.display = 'block';
						document.getElementById('chatMsgInput').focus();
					});
				}
			}

			// Initialize cart view
			updateCartUI();
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
				'enable_shop_takeover'    => ! empty( $_POST['enable_shop_takeover'] ),
				'replace_site_header'     => ! empty( $_POST['replace_site_header'] ),
				'show_top_bar'            => ! empty( $_POST['show_top_bar'] ),
				'top_bar_notice'          => sanitize_text_field( $_POST['top_bar_notice'] ?? '' ),
				'contact_phone'           => sanitize_text_field( $_POST['contact_phone'] ?? '' ),
				'support_hours'           => sanitize_text_field( $_POST['support_hours'] ?? '' ),
				'default_fallback_price'  => floatval( $_POST['default_fallback_price'] ?? 150000 ),
				'fallback_price_behavior' => sanitize_text_field( $_POST['fallback_price_behavior'] ?? 'use_fallback' ),
				'price_markup_percent'    => floatval( $_POST['price_markup_percent'] ?? 0 ),
				'price_fixed_add'         => floatval( $_POST['price_fixed_add'] ?? 0 ),
				'price_rounding'          => sanitize_text_field( $_POST['price_rounding'] ?? '1000' ),
				'currency_symbol'         => sanitize_text_field( $_POST['currency_symbol'] ?? 'تومان' ),
				'shop_title'              => sanitize_text_field( $_POST['shop_title'] ?? '' ),
				'shop_subtitle'           => sanitize_text_field( $_POST['shop_subtitle'] ?? '' ),
				'accent_color'            => sanitize_text_field( $_POST['accent_color'] ?? '#2563eb' ),
				'products_per_page'       => intval( $_POST['products_per_page'] ?? 16 ),
				'show_features_banner'    => ! empty( $_POST['show_features_banner'] ),
				'show_special_badge'      => ! empty( $_POST['show_special_badge'] ),
				'free_shipping_threshold' => floatval( $_POST['free_shipping_threshold'] ?? 400000 ),
				'enable_support_chat'     => ! empty( $_POST['enable_support_chat'] ),
				'chat_button_position'    => in_array( $_POST['chat_button_position'] ?? '', array( 'left', 'right' ), true ) ? $_POST['chat_button_position'] : 'left',
				'chat_window_title'       => sanitize_text_field( $_POST['chat_window_title'] ?? 'پشتیبانی آنلاین فروشگاه' ),
				'chat_welcome_message'    => sanitize_textarea_field( $_POST['chat_welcome_message'] ?? '' ),
				'bale_token'              => sanitize_text_field( $_POST['bale_token'] ?? '' ),
				'bale_chat_id'            => sanitize_text_field( $_POST['bale_chat_id'] ?? '' ),
				'telegram_token'          => sanitize_text_field( $_POST['telegram_token'] ?? '' ),
				'telegram_chat_id'        => sanitize_text_field( $_POST['telegram_chat_id'] ?? '' ),
				'rubika_token'            => sanitize_text_field( $_POST['rubika_token'] ?? '' ),
				'rubika_chat_id'          => sanitize_text_field( $_POST['rubika_chat_id'] ?? '' ),
			);
			update_option( self::OPTION_NAME, $new_settings );
			$updated = true;
		}

		$opts             = self::get_settings();
		$scraped_products = self::get_all_scraped_products();
		$profiles_summary = self::get_profiles_summary();

		$scraper_embed_url = admin_url( 'admin.php?page=scraper-full-dashboard' );
		$scraper_direct_url = plugins_url( 'scraper4.php', __FILE__ );
		?>
		<div class="wrap" style="direction:rtl; text-align:right; font-family:system-ui, -apple-system, sans-serif;">
			<h1 style="display:flex; align-items:center; gap:10px; font-weight:800; margin-bottom:20px;">
				<span>🛍️</span>
				مدیریت فروشگاه مدرن، سربرگ‌ها، منوها، تعدیل قیمت و اسکرپر هوشمند
			</h1>

			<?php if ( $updated ) : ?>
				<div class="notice notice-success is-dismissible"><p><strong>تنظیمات با موفقیت ذخیره شد.</strong></p></div>
			<?php endif; ?>

			<!-- Scraper Hero Access Banner -->
			<div style="background:linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%); color:#fff; border-radius:16px; padding:24px 30px; margin-bottom:25px; box-shadow:0 10px 25px rgba(15,23,42,0.15); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
				<div>
					<div style="display:inline-block; background:#2563eb; color:#fff; font-size:0.8rem; font-weight:700; padding:3px 10px; border-radius:20px; margin-bottom:8px;">
						⚡ پنل کنترل اسکرپر هوشمند (Scraper 4)
					</div>
					<h2 style="color:#fff; margin:0 0 8px; font-size:1.5rem; font-weight:900;">دسترسی مستقیم به اسکرپر و استخراج محصولات</h2>
					<p style="color:#cbd5e1; margin:0; max-width:650px; font-size:0.95rem; line-height:1.6;">
						برای تعریف پروفایل‌های جدید (باسلام، ترب، دیجی‌کالا، فروشگاه‌های ووکامرس)، تنظیم سلکتورها و اجرای فرایند استخراج، وارد داشبورد اسکرپر شوید. تمامی محصولات با استایل کاملاً حرفه‌ای و بدون افشای نام منبع در فروشگاه قرار می‌گیرند.
					</p>
				</div>
				<div style="display:flex; gap:12px; flex-wrap:wrap;">
					<a href="<?php echo esc_url( $scraper_embed_url ); ?>" class="button button-primary button-hero" style="background:#2563eb; border:none; font-weight:800; font-size:1rem; padding:10px 22px; border-radius:10px; height:auto;">
						⚡ ورود به پنل اسکرپر در وردپرس
					</a>
					<a href="<?php echo esc_url( $scraper_direct_url ); ?>" target="_blank" class="button button-secondary button-hero" style="font-weight:700; font-size:1rem; padding:10px 20px; border-radius:10px; height:auto; color:#0f172a;">
						باز کردن در تب مستقل ↗
					</a>
				</div>
			</div>

			<!-- Profiles Summary Table -->
			<div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:22px 25px; margin-bottom:25px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
				<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
					<h3 style="margin:0; font-size:1.15rem; font-weight:800; color:#0f172a;">
						📊 وضعیت پروفایل‌ها و محصولات استخراج‌شده
					</h3>
					<span style="background:#ecfdf5; color:#059669; font-weight:700; padding:5px 14px; border-radius:20px; font-size:0.85rem;">
						مجموع کل محصولات استخراج‌شده: <?php echo self::to_fa_num( count( $scraped_products ) ); ?> کالا
					</span>
				</div>

				<?php if ( empty( $profiles_summary ) ) : ?>
					<p style="color:#64748b; margin:0;">هنوز پروفایلی در فایل <code>profiles.json</code> یافت نشد. برای شروع، یک پروفایل در پنل اسکرپر ایجاد کنید.</p>
				<?php else : ?>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th style="font-weight:700;">نام پروفایل</th>
								<th style="font-weight:700;">آدرس منبع (URL)</th>
								<th style="font-weight:700; width:140px;">محصولات استخراج‌شده</th>
								<th style="font-weight:700; width:120px;">عملیات</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $profiles_summary as $prof ) : ?>
								<tr>
									<td style="font-weight:700; color:#1e293b;"><?php echo esc_html( $prof['name'] ); ?></td>
									<td style="direction:ltr; text-align:right;"><code style="font-size:0.85rem;"><?php echo esc_html( $prof['url'] ); ?></code></td>
									<td><strong style="color:#059669; font-size:1.05rem;"><?php echo self::to_fa_num( $prof['count'] ); ?></strong> کالا</td>
									<td>
										<a href="<?php echo esc_url( $scraper_embed_url ); ?>" class="button button-small" style="font-weight:600;">
											مدیریت در اسکرپر
										</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>

			<form method="post" action="">
				<?php wp_nonce_field( 'scraper_shop_settings_action', 'scraper_shop_settings_nonce' ); ?>

				<!-- Storefront Header & Navigation Settings -->
				<div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:22px 25px; margin-bottom:25px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
					<h3 style="margin-top:0; margin-bottom:15px; font-size:1.15rem; font-weight:800; color:#0f172a; border-bottom:1px solid #f1f5f9; padding-bottom:10px;">
						🎨 تنظیمات سربرگ، منوها و نوار اعلان فروشگاه
					</h3>

					<table class="form-table">
						<tr>
							<th scope="row">جایگزینی سربرگ قالب با سربرگ مدرن:</th>
							<td>
								<label>
									<input type="checkbox" name="replace_site_header" value="1" <?php checked( $opts['replace_site_header'] ); ?>>
									سربرگ قدیمی و پیش‌فرض سایت در برگه فروشگاه با سربرگ لوکس، منوهای دسته‌بندی و ناوبری مدرن جایگزین شود.
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row">نوار اعلان بالایی (Top Bar):</th>
							<td>
								<label>
									<input type="checkbox" name="show_top_bar" value="1" <?php checked( $opts['show_top_bar'] ); ?>>
									نمایش نوار باریک اطلاع‌رسانی، تماس سریع و پیام‌های تخفیف در بالاترین بخش سربرگ
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row">متن پیام نوار اعلان:</th>
							<td>
								<input type="text" name="top_bar_notice" value="<?php echo esc_attr( $opts['top_bar_notice'] ); ?>" class="large-text">
							</td>
						</tr>
						<tr>
							<th scope="row">شماره تلفن تماس و پشتیبانی:</th>
							<td>
								<input type="text" name="contact_phone" value="<?php echo esc_attr( $opts['contact_phone'] ); ?>" class="regular-text">
								<p class="description">در سربرگ، منوی موبایل و فوتر نمایش داده شده و با کلیک کاربر فوراً شماره‌گیری می‌شود.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">ساعات پاسخگویی:</th>
							<td>
								<input type="text" name="support_hours" value="<?php echo esc_attr( $opts['support_hours'] ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">عنوان فروشگاه (Brand Title):</th>
							<td>
								<input type="text" name="shop_title" value="<?php echo esc_attr( $opts['shop_title'] ); ?>" class="large-text">
							</td>
						</tr>
						<tr>
							<th scope="row">زیرعنوان / شعار فروشگاه:</th>
							<td>
								<input type="text" name="shop_subtitle" value="<?php echo esc_attr( $opts['shop_subtitle'] ); ?>" class="large-text">
							</td>
						</tr>
						<tr>
							<th scope="row">رنگ اختصاصی تم (Accent Color):</th>
							<td>
								<input type="color" name="accent_color" value="<?php echo esc_attr( $opts['accent_color'] ); ?>" style="width:70px; height:38px; border-radius:6px; cursor:pointer;">
							</td>
						</tr>
						<tr>
							<th scope="row">سقف ارسال رایگان (تومان):</th>
							<td>
								<input type="number" name="free_shipping_threshold" value="<?php echo esc_attr( $opts['free_shipping_threshold'] ); ?>" class="regular-text"> تومان
								<p class="description">مبلغی که با رسیدن فاکتور مشتری به آن، ارسال رایگان در سبد خرید اعمال می‌شود.</p>
							</td>
						</tr>
					</table>
				</div>

				<!-- Price Adjustment Settings -->
				<div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:22px 25px; margin-bottom:25px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
					<h3 style="margin-top:0; margin-bottom:15px; font-size:1.15rem; font-weight:800; color:#0f172a; border-bottom:1px solid #f1f5f9; padding-bottom:10px;">
						💰 قوانین قیمت و سود محصولات
					</h3>

					<table class="form-table">
						<tr>
							<th scope="row">درصد سود و افزایش قیمت (Markup %):</th>
							<td>
								<input type="number" step="0.5" name="price_markup_percent" value="<?php echo esc_attr( $opts['price_markup_percent'] ); ?>" class="small-text"> ٪
								<p class="description">این درصد به طور خودکار به قیمت خام اضافه می‌شود (مثلاً ۲۰٪ سود).</p>
							</td>
						</tr>
						<tr>
							<th scope="row">مبلغ ثابت اضافه شونده:</th>
							<td>
								<input type="number" name="price_fixed_add" value="<?php echo esc_attr( $opts['price_fixed_add'] ); ?>" class="regular-text">
								<p class="description">مبلغ ثابت به تومان که به قیمت نهایی اضافه می‌شود (مثلاً هزینه بسته‌بندی).</p>
							</td>
						</tr>
						<tr>
							<th scope="row">قیمت پایه پیش‌فرض (در صورت نبود قیمت در منبع):</th>
							<td>
								<input type="number" name="default_fallback_price" value="<?php echo esc_attr( $opts['default_fallback_price'] ); ?>" class="regular-text"> تومان
								<p class="description">اگر کالایی در سایت مبدأ بدون قیمت بود، این قیمت به عنوان قیمت پایه استفاده شده و درصد افزایش به آن تعلق می‌گیرد تا کالایی با «تماس بگیرید» نمایش داده نشود.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">رفتار هنگام نبود قیمت در منبع:</th>
							<td>
								<select name="fallback_price_behavior" class="regular-text">
									<option value="use_fallback" <?php selected( $opts['fallback_price_behavior'], 'use_fallback' ); ?>>استفاده از قیمت پایه پیش‌فرض و نمایش قیمت فروشگاه (توصیه شده)</option>
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
							<th scope="row">واحد پول:</th>
							<td>
								<input type="text" name="currency_symbol" value="<?php echo esc_attr( $opts['currency_symbol'] ); ?>" class="regular-text">
							</td>
						</tr>
						<tr>
							<th scope="row">نشان‌ها و برچسب‌های کارت کالا:</th>
							<td>
								<label style="display:block; margin-bottom:8px;">
									<input type="checkbox" name="show_special_badge" value="1" <?php checked( ! empty( $opts['show_special_badge'] ) ); ?>>
									نمایش نشان «پیشنهاد ویژه» روی کارت کالا
								</label>
								<label style="display:block;">
									<input type="checkbox" name="show_features_banner" value="1" <?php checked( $opts['show_features_banner'] ); ?>>
									نمایش نشان‌های اعتماد (ارسال سریع، تضمین اصالت و...) در سربرگ
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row">فعال‌سازی حالت جایگزینی ویترین فروشگاه:</th>
							<td>
								<label>
									<input type="checkbox" name="enable_shop_takeover" value="1" <?php checked( $opts['enable_shop_takeover'] ); ?>>
									ویترین پیش‌فرض فروشگاه ووکامرس با این ویترین مدرن جایگزین شود.
								</label>
							</td>
						</tr>
					</table>
				</div>

				<!-- Support Chat & Messenger Forwarding Settings -->
				<div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:22px 25px; margin-bottom:25px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
					<div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #f1f5f9; padding-bottom:12px; margin-bottom:18px;">
						<h3 style="margin:0; font-size:1.15rem; font-weight:800; color:#0f172a; display:flex; align-items:center; gap:8px;">
							<span>💬</span> تنظیمات چت پشتیبانی آنلاین و ارسال پیام‌ها به پیام‌رسان‌ها (بله / تلگرام / روبیکا)
						</h3>
						<span style="background:#eff6ff; color:#2563eb; font-weight:700; padding:4px 12px; border-radius:12px; font-size:0.8rem;">
							متصل به بخش اعلان‌های اسکرپر
						</span>
					</div>

					<p style="color:#64748b; font-size:0.92rem; line-height:1.6; margin-top:0; margin-bottom:18px;">
						با فعال بودن این بخش، یک دکمه چت آنلاین مدرن در ویترین فروشگاه قرار می‌گیرد. هر پیامی که مشتریان ارسال کنند، به صورت آنی به پیام‌رسان‌های پیکربندی‌شده در بخش اعلان‌های فایل اسکرپر (<code>connections.json</code>) شامل <strong>بله (Bale)</strong>، <strong>تلگرام (Telegram)</strong> و <strong>روبیکا (Rubika)</strong> ارسال خواهد شد.
					</p>

					<?php
					$active_messengers = self::get_active_messengers( $opts );
					?>

					<!-- Status overview box -->
					<div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:15px 20px; margin-bottom:20px;">
						<div style="font-weight:800; font-size:0.95rem; color:#1e293b; margin-bottom:10px;">
							📡 وضعیت پیام‌رسان‌های شناسایی‌شده از فایل اسکرپر و تنظیمات:
						</div>
						<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:12px;">
							<!-- Bale -->
							<div style="background:#fff; border:1px solid <?php echo isset( $active_messengers['bale'] ) ? '#bbf7d0' : '#e2e8f0'; ?>; border-radius:10px; padding:12px;">
								<div style="font-weight:700; display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
									<span>🔹 بله (Bale):</span>
									<?php if ( isset( $active_messengers['bale'] ) ) : ?>
										<span style="color:#16a34a; font-size:0.8rem; font-weight:800;">🟢 فعال و متصل</span>
									<?php else : ?>
										<span style="color:#94a3b8; font-size:0.8rem;">⚪ تنظیم نشده</span>
									<?php endif; ?>
								</div>
								<div style="font-size:0.78rem; color:#64748b;">
									<?php
									if ( isset( $active_messengers['bale'] ) ) {
										echo 'منبع: ' . ( 'scraper_connections' === $active_messengers['bale']['source'] ? 'connections.json اسکرپر' : 'تنظیمات سفارشی' );
									} else {
										echo 'توکن یا Chat ID در اسکرپر یا فیلدهای زیر خالی است.';
									}
									?>
								</div>
							</div>

							<!-- Telegram -->
							<div style="background:#fff; border:1px solid <?php echo isset( $active_messengers['telegram'] ) ? '#bbf7d0' : '#e2e8f0'; ?>; border-radius:10px; padding:12px;">
								<div style="font-weight:700; display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
									<span>🔹 تلگرام (Telegram):</span>
									<?php if ( isset( $active_messengers['telegram'] ) ) : ?>
										<span style="color:#16a34a; font-size:0.8rem; font-weight:800;">🟢 فعال و متصل</span>
									<?php else : ?>
										<span style="color:#94a3b8; font-size:0.8rem;">⚪ تنظیم نشده</span>
									<?php endif; ?>
								</div>
								<div style="font-size:0.78rem; color:#64748b;">
									<?php
									if ( isset( $active_messengers['telegram'] ) ) {
										echo 'منبع: ' . ( 'scraper_connections' === $active_messengers['telegram']['source'] ? 'connections.json اسکرپر' : 'تنظیمات سفارشی' );
									} else {
										echo 'توکن یا Chat ID در اسکرپر یا فیلدهای زیر خالی است.';
									}
									?>
								</div>
							</div>

							<!-- Rubika -->
							<div style="background:#fff; border:1px solid <?php echo isset( $active_messengers['rubika'] ) ? '#bbf7d0' : '#e2e8f0'; ?>; border-radius:10px; padding:12px;">
								<div style="font-weight:700; display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
									<span>🔹 روبیکا (Rubika):</span>
									<?php if ( isset( $active_messengers['rubika'] ) ) : ?>
										<span style="color:#16a34a; font-size:0.8rem; font-weight:800;">🟢 فعال و متصل</span>
									<?php else : ?>
										<span style="color:#94a3b8; font-size:0.8rem;">⚪ تنظیم نشده</span>
									<?php endif; ?>
								</div>
								<div style="font-size:0.78rem; color:#64748b;">
									<?php
									if ( isset( $active_messengers['rubika'] ) ) {
										echo 'منبع: ' . ( 'scraper_connections' === $active_messengers['rubika']['source'] ? 'connections.json اسکرپر' : 'تنظیمات سفارشی' );
									} else {
										echo 'توکن یا Chat ID در اسکرپر یا فیلدهای زیر خالی است.';
									}
									?>
								</div>
							</div>
						</div>

						<!-- Test Button -->
						<div style="margin-top:15px; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
							<button type="button" id="btnTestMessengers" class="button button-secondary" style="font-weight:700; padding:6px 16px; border-color:#2563eb; color:#2563eb;">
								🔔 ارسال پیام آزمایشی به پیام‌رسان‌ها (تست اتصال)
							</button>
							<span id="testMessengersStatus" style="font-weight:600; font-size:0.9rem;"></span>
						</div>
					</div>

					<table class="form-table">
						<tr>
							<th scope="row">فعال‌سازی چت آنلاین:</th>
							<td>
								<label>
									<input type="checkbox" name="enable_support_chat" value="1" <?php checked( ! empty( $opts['enable_support_chat'] ) ); ?>>
									دکمه شناور چت آنلاین و فرم گفتگوی مستقیم در صفحات فروشگاه نمایش داده شود.
								</label>
							</td>
						</tr>
						<tr>
							<th scope="row">موقعیت دکمه چت:</th>
							<td>
								<select name="chat_button_position" class="regular-text">
									<option value="left" <?php selected( $opts['chat_button_position'] ?? 'left', 'left' ); ?>>پایین سمت چپ صفحه (توصیه شده)</option>
									<option value="right" <?php selected( $opts['chat_button_position'] ?? 'left', 'right' ); ?>>پایین سمت راست صفحه</option>
								</select>
							</td>
						</tr>
						<tr>
							<th scope="row">عنوان پنجره گفتگو:</th>
							<td>
								<input type="text" name="chat_window_title" value="<?php echo esc_attr( $opts['chat_window_title'] ?? 'پشتیبانی آنلاین فروشگاه' ); ?>" class="large-text">
							</td>
						</tr>
						<tr>
							<th scope="row">پیام خوش‌آمدگویی خودکار:</th>
							<td>
								<textarea name="chat_welcome_message" rows="3" class="large-text"><?php echo esc_textarea( $opts['chat_welcome_message'] ?? 'سلام! خوش آمدید 👋 هرگونه سوالی درباره محصولات یا ثبت سفارش دارید، پیام بگذارید تا همکاران ما سریعاً پاسخ دهند.' ); ?></textarea>
								<p class="description">این متن به عنوان پیام اول از طرف پشتیبان به مشتری نشان داده می‌شود.</p>
							</td>
						</tr>

						<tr>
							<th scope="row" colspan="2" style="padding-top:20px; padding-bottom:5px;">
								<strong style="color:#0f172a; font-size:1rem;">⚙️ تنظیمات اختصاصی پیام‌رسان‌ها (اختیاری — در صورت خالی بودن، خودکار از connections.json خوانده می‌شود):</strong>
							</th>
						</tr>
						<tr>
							<th scope="row">ربات بله (Bale):</th>
							<td>
								<input type="password" name="bale_token" value="<?php echo esc_attr( $opts['bale_token'] ?? '' ); ?>" placeholder="Bot Token بله" class="regular-text" dir="ltr" style="margin-left:10px;">
								<input type="text" name="bale_chat_id" value="<?php echo esc_attr( $opts['bale_chat_id'] ?? '' ); ?>" placeholder="شناسه عددی چت (Chat ID)" class="regular-text" dir="ltr">
								<p class="description">اگر در بخش اعلان‌های اسکرپر وارد شده باشد، نیازی به وارد کردن مجدد در اینجا نیست.</p>
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

					<!-- Recent customer messages log table -->
					<?php
					$recent_chat_logs = get_option( 'scraper_support_chat_logs', array() );
					?>
					<div style="margin-top:25px; border-top:1px solid #f1f5f9; padding-top:15px;">
						<h4 style="margin:0 0 12px; font-size:1rem; font-weight:800; color:#1e293b;">
							📋 آخرین پیام‌های دریافتی از مشتریان (۵ پیام اخیر):
						</h4>
						<?php if ( empty( $recent_chat_logs ) ) : ?>
							<p style="color:#94a3b8; font-size:0.85rem; margin:0;">هنوز پیامی از طرف مشتریان در چت آنلاین ثبت نشده است.</p>
						<?php else : ?>
							<table class="wp-list-table widefat fixed striped" style="border-radius:8px; overflow:hidden;">
								<thead>
									<tr>
										<th style="width:130px; font-weight:700;">زمان</th>
										<th style="width:130px; font-weight:700;">نام مشتری</th>
										<th style="width:120px; font-weight:700;">شماره تماس</th>
										<th style="font-weight:700;">متن پیام</th>
										<th style="width:140px; font-weight:700;">وضعیت ارسال</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( array_slice( $recent_chat_logs, 0, 5 ) as $log ) : ?>
										<tr>
											<td style="font-size:0.82rem; color:#64748b;"><?php echo esc_html( $log['time'] ?? '—' ); ?></td>
											<td style="font-weight:700;"><?php echo esc_html( $log['name'] ?? '—' ); ?></td>
											<td dir="ltr" style="text-align:right;"><a href="tel:<?php echo esc_attr( $log['phone'] ?? '' ); ?>" style="font-weight:600; color:#2563eb;"><?php echo esc_html( $log['phone'] ?? '—' ); ?></a></td>
											<td style="font-size:0.88rem;"><?php echo esc_html( $log['message'] ?? '—' ); ?></td>
											<td>
												<?php if ( ! empty( $log['sent_ok'] ) ) : ?>
													<span style="color:#16a34a; font-weight:700; font-size:0.82rem;">✅ ارسال به پیام‌رسان</span>
												<?php else : ?>
													<span style="color:#d97706; font-weight:700; font-size:0.82rem;">⚠️ ثبت در سیستم</span>
												<?php endif; ?>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php endif; ?>
					</div>
				</div>

				<p class="submit" style="display:flex; gap:15px; align-items:center;">
					<input type="submit" name="scraper_shop_save" class="button button-primary button-large" value="💾 ذخیره تنظیمات کامل فروشگاه و قیمت" style="font-weight:800; padding:8px 24px;">
				</p>
			</form>

			<!-- Direct Sync to WooCommerce Action -->
			<div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:22px 25px; margin-top:25px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
				<h3 style="margin-top:0; margin-bottom:10px; font-size:1.15rem; font-weight:800; color:#0f172a;">
					🔄 درج مستقیم در دیتابیس محصولات ووکامرس (WooCommerce Database Sync)
				</h3>
				<p style="color:#64748b; font-size:0.95rem; line-height:1.6; max-width:800px;">
					با فشردن دکمه زیر، تمامی محصولات به عنوان محصولات رسمی ووکامرس در دیتابیس وردپرس درج یا به‌روزرسانی می‌شوند:
				</p>
				<div style="display:flex; align-items:center; gap:15px; flex-wrap:wrap; margin-top:15px;">
					<button type="button" id="btnSyncToWoo" class="button button-secondary button-hero" style="font-weight:800; padding:8px 24px; border-color:#2563eb; color:#2563eb;">
						همگام‌سازی و درج مستقیم در محصولات ووکامرس
					</button>
					<span id="syncWooStatus" style="font-weight:700; color:#475569;"></span>
				</div>
			</div>
		</div>

		<script>
			jQuery(document).ready(function($){
				$('#btnSyncToWoo').on('click', function(e){
					e.preventDefault();
					var $btn = $(this);
					var $status = $('#syncWooStatus');
					$btn.prop('disabled', true).text('در حال همگام‌سازی با ووکامرس...');
					$status.html('<span style="color:#2563eb;">⏳ در حال انتقال محصولات به دیتابیس ووکامرس...</span>');

				$('#btnTestMessengers').on('click', function(e){
					e.preventDefault();
					var $btn = $(this);
					var $status = $('#testMessengersStatus');
					$btn.prop('disabled', true).text('در حال ارسال پیام تست... ⏳');
					$status.html('<span style="color:#2563eb;">در حال ارسال تست به پیام‌رسان‌های فعال...</span>');

					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'test_support_messengers',
							nonce: '<?php echo esc_js( wp_create_nonce( 'scraper_shop_admin_nonce' ) ); ?>'
						},
						success: function(res){
							$btn.prop('disabled', false).text('🔔 ارسال پیام آزمایشی به پیام‌رسان‌ها (تست اتصال)');
							if (res.success) {
								$status.html('<span style="color:#16a34a; font-weight:700;">✅ ' + (res.data.message || 'پیام با موفقیت به پیام‌رسان‌ها ارسال شد!') + '</span>');
							} else {
								var err = (res.data && res.data.message) ? res.data.message : 'ارسال تست با خطا مواجه شد.';
								$status.html('<span style="color:#dc2626; font-weight:700;">❌ ' + err + '</span>');
							}
						},
						error: function(){
							$btn.prop('disabled', false).text('🔔 ارسال پیام آزمایشی به پیام‌رسان‌ها (تست اتصال)');
							$status.html('<span style="color:#dc2626; font-weight:700;">❌ خطای ارتباط با سرور.</span>');
						}
					});
				});

				$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'scraper_sync_to_woo',
							nonce: '<?php echo wp_create_nonce( "scraper_shop_admin_nonce" ); ?>'
						},
						success: function(res){
							$btn.prop('disabled', false).text('همگام‌سازی و درج مستقیم در محصولات ووکامرس');
							if(res.success && res.data){
								$status.html('✅ همگام‌سازی با موفقیت انجام شد: ' + res.data.created + ' محصول جدید درج شد و ' + res.data.updated + ' محصول به‌روزرسانی شد.');
							} else {
								$status.html('❌ خطا: ' + (res.data.message || res.data || 'خطای نامشخص'));
							}
						},
						error: function(){
							$btn.prop('disabled', false).text('همگام‌سازی و درج مستقیم در محصولات ووکامرس');
							$status.html('❌ خطای ارتباط با سرور.');
						}
					});
				});
			});
		</script>
		<?php
	}

	/**
	 * Render Scraper4 Embedded View in Admin.
	 */
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
