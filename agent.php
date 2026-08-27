<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، سربرگ و منوهای لوکس، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 11.3.0
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
			'shop_title'                  => 'فروشگاه آنلاین و هوشمند نوآوران',
			'shop_subtitle'               => 'تنوع بی‌نظیر کالاها با تضمین اصالت، سلامت فیزیکی و ارسال سریع به سراسر کشور',
			'price_markup_percent'        => 20,
			'price_fixed_add'             => 0,
			'price_rounding'              => '1000', // none, 1000, 10000
			'currency_symbol'             => 'تومان',
			'default_fallback_price'      => 150000, // Fallback base price if source price is missing/0
			'fallback_price_behavior'     => 'use_fallback', // 'use_fallback' or 'call_for_price'
			'accent_color'                => '#2563eb',
			'products_per_page'           => 16,
			'show_brand_badge'            => true,
			'show_features_banner'        => true,
			'show_special_badge'          => true,
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
	 * Get summary list of profiles from scraper4 profiles.json.
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
					$profile_name = ! empty( $p_item['name'] ) ? $p_item['name'] : $p_key;
					$raw_prods    = $p_item['products'] ?? array();
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
							$link = $prod['link'] ?? $prod['url'] ?? '';

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
								'link'            => $link,
								'profile'         => $profile_name,
								'profile_key'     => $p_key,
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
									'link'            => $prod['link'] ?? $prod['url'] ?? '',
									'profile'         => 'برند برتر',
									'profile_key'     => 'woo_temp',
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
				'message' => 'هیچ محصولی در پروفایل‌ها برای همگام‌سازی یافت نشد.',
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
				update_post_meta( $product_id, '_scraped_source_profile', $p['profile'] );
				if ( ! empty( $p['link'] ) ) {
					update_post_meta( $product_id, '_scraped_source_url', $p['link'] );
				}

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
	 * 100% clean customer experience with NO internal scraper/extraction references.
	 */
	public static function render_shop_shortcode() {
		$settings = self::get_settings();
		$products = self::get_all_scraped_products();
		$profiles = self::get_profiles_summary();

		// Unique categories
		$categories = array();
		foreach ( $products as $p ) {
			$cat = ! empty( $p['category'] ) ? $p['category'] : 'عمومی';
			$categories[ $cat ] = ( $categories[ $cat ] ?? 0 ) + 1;
		}

		// Unique brands/profiles (presented cleanly as Brands/Suppliers)
		$prof_counts = array();
		foreach ( $products as $p ) {
			$pr = ! empty( $p['profile'] ) ? $p['profile'] : 'سایر';
			$prof_counts[ $pr ] = ( $prof_counts[ $pr ] ?? 0 ) + 1;
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
				--sp-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.07);
			}
			.modern-shop-root {
				font-family: Vazirmatn, system-ui, -apple-system, sans-serif;
				direction: rtl;
				text-align: right;
				margin: 15px auto 60px;
				color: var(--sp-text);
				width: 100%;
				box-sizing: border-box;
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
				padding: 2px 8px;
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
				padding: 12px 46px 12px 18px;
				border: 1.5px solid #cbd5e1;
				border-radius: 30px;
				font-size: 0.92rem;
				background: #f8fafc;
				transition: all 0.25s ease;
				outline: none;
			}
			.store-header-search input:focus {
				border-color: var(--sp-accent);
				background: #fff;
				box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
			}
			.store-header-search svg {
				position: absolute;
				right: 16px;
				top: 50%;
				transform: translateY(-50%);
				width: 20px;
				height: 20px;
				fill: #94a3b8;
				pointer-events: none;
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
				margin-bottom: 25px;
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

			/* Dropdown Mega Menu for Categories & Brands */
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

			/* Luxury Hero Banner */
			.modern-shop-hero {
				background: radial-gradient(circle at 85% 20%, rgba(37,99,235,0.3) 0%, transparent 50%),
				            linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e1b4b 100%);
				border-radius: var(--sp-radius);
				padding: 50px 30px;
				color: #fff;
				text-align: center;
				margin-bottom: 30px;
				box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35);
				position: relative;
				overflow: hidden;
				border: 1px solid rgba(255,255,255,0.1);
			}
			.modern-shop-hero h1 {
				font-size: 2.5rem;
				font-weight: 900;
				margin-bottom: 12px;
				color: #ffffff;
				letter-spacing: -0.5px;
			}
			.modern-shop-hero p {
				font-size: 1.12rem;
				color: #cbd5e1;
				max-width: 680px;
				margin: 0 auto 26px;
				line-height: 1.7;
			}
			.hero-features-bar {
				display: flex;
				flex-wrap: wrap;
				justify-content: center;
				gap: 15px;
			}
			.hero-feature-item {
				background: rgba(255,255,255,0.08);
				backdrop-filter: blur(10px);
				border: 1px solid rgba(255,255,255,0.15);
				padding: 8px 16px;
				border-radius: 30px;
				font-size: 0.88rem;
				color: #f8fafc;
				display: flex;
				align-items: center;
				gap: 8px;
			}

			/* Toolbar & Filters */
			.shop-toolbar {
				display: flex;
				justify-content: space-between;
				align-items: center;
				margin-bottom: 20px;
				padding: 14px 20px;
				background: #ffffff;
				border: 1px solid var(--sp-border);
				border-radius: var(--sp-radius);
				flex-wrap: wrap;
				gap: 15px;
			}
			.sort-select {
				padding: 9px 16px;
				border-radius: 12px;
				border: 1px solid #cbd5e1;
				font-family: inherit;
				font-size: 0.9rem;
				background-color: #fff;
				color: #334155;
				cursor: pointer;
				outline: none;
			}
			.filter-pills-wrap {
				display: flex;
				flex-wrap: wrap;
				gap: 10px;
				margin-bottom: 24px;
			}
			.filter-pill {
				background: #fff;
				border: 1px solid var(--sp-border);
				padding: 9px 18px;
				border-radius: 30px;
				font-size: 0.88rem;
				font-weight: 600;
				color: #475569;
				cursor: pointer;
				transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
				display: inline-flex;
				align-items: center;
				gap: 6px;
			}
			.filter-pill.active, .filter-pill:hover {
				background: var(--sp-accent);
				color: #fff;
				border-color: var(--sp-accent);
				box-shadow: 0 6px 16px rgba(37,99,235,0.25);
			}
			.filter-pill-badge {
				background: rgba(0,0,0,0.08);
				padding: 2px 7px;
				border-radius: 20px;
				font-size: 0.75rem;
			}
			.filter-pill.active .filter-pill-badge {
				background: rgba(255,255,255,0.25);
				color: #fff;
			}

			/* Products Grid */
			.products-grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
				gap: 24px;
			}
			.product-card {
				background: var(--sp-bg-card);
				border: 1px solid var(--sp-border);
				border-radius: var(--sp-radius);
				overflow: hidden;
				display: flex;
				flex-direction: column;
				transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.3s ease;
				position: relative;
				box-shadow: var(--sp-shadow);
			}
			.product-card:hover {
				transform: translateY(-8px);
				box-shadow: 0 20px 35px -10px rgba(0,0,0,0.12);
			}
			.card-thumb-wrap {
				position: relative;
				width: 100%;
				padding-top: 100%;
				background: #f1f5f9;
				overflow: hidden;
			}
			.card-thumb-wrap img {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				object-fit: cover;
				transition: transform 0.5s ease;
			}
			.product-card:hover .card-thumb-wrap img {
				transform: scale(1.08);
			}
			.card-brand-tag {
				position: absolute;
				top: 12px;
				right: 12px;
				background: rgba(15, 23, 42, 0.78);
				backdrop-filter: blur(8px);
				color: #fff;
				font-size: 0.75rem;
				font-weight: 600;
				padding: 4px 10px;
				border-radius: 8px;
				z-index: 2;
			}
			.card-stock-badge {
				position: absolute;
				top: 12px;
				left: 12px;
				background: #10b981;
				color: #fff;
				font-size: 0.75rem;
				font-weight: 700;
				padding: 4px 10px;
				border-radius: 8px;
				z-index: 2;
			}
			.card-body {
				padding: 20px;
				display: flex;
				flex-direction: column;
				flex-grow: 1;
			}
			.card-category {
				font-size: 0.8rem;
				color: var(--sp-muted);
				margin-bottom: 6px;
				font-weight: 500;
			}
			.card-title {
				font-size: 1.05rem;
				font-weight: 700;
				line-height: 1.55;
				margin-bottom: 14px;
				height: 50px;
				overflow: hidden;
				display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
				color: var(--sp-text);
			}
			.card-pricing-block {
				margin-top: auto;
				margin-bottom: 18px;
				display: flex;
				flex-direction: column;
				gap: 4px;
			}
			.pricing-row-top {
				display: flex;
				align-items: center;
				justify-content: space-between;
			}
			.card-old-price {
				font-size: 0.85rem;
				color: #94a3b8;
				text-decoration: line-through;
			}
			.card-special-badge {
				background: #ecfdf5;
				color: #059669;
				border: 1px solid #a7f3d0;
				padding: 2px 8px;
				border-radius: 6px;
				font-size: 0.75rem;
				font-weight: 700;
			}
			.card-new-price {
				font-size: 1.35rem;
				font-weight: 900;
				color: #059669;
			}
			.card-actions {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 10px;
			}
			.btn-card-quick {
				background: #f1f5f9;
				color: #334155;
				border: none;
				border-radius: 10px;
				padding: 10px;
				font-weight: 700;
				font-size: 0.88rem;
				cursor: pointer;
				text-align: center;
				transition: all 0.2s;
			}
			.btn-card-quick:hover {
				background: #e2e8f0;
			}
			.btn-card-buy {
				background: var(--sp-accent);
				color: #fff;
				border: none;
				border-radius: 10px;
				padding: 10px;
				font-weight: 700;
				font-size: 0.88rem;
				cursor: pointer;
				text-align: center;
				transition: all 0.2s;
				text-decoration: none;
				display: inline-block;
			}
			.btn-card-buy:hover {
				background: var(--sp-accent-hover);
				color: #fff;
			}

			/* Empty state */
			.shop-empty-state {
				background: #fff;
				border: 2px dashed #cbd5e1;
				border-radius: var(--sp-radius);
				padding: 60px 20px;
				text-align: center;
				margin: 30px auto;
				max-width: 600px;
			}
			.shop-empty-state .empty-icon {
				font-size: 4rem;
				margin-bottom: 15px;
			}
			.shop-empty-state h3 {
				font-size: 1.4rem;
				font-weight: 800;
				margin-bottom: 10px;
			}
			.shop-empty-state p {
				color: #64748b;
				margin-bottom: 25px;
				line-height: 1.7;
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
				left: -400px;
				width: 380px;
				max-width: 90vw;
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
				padding: 20px;
				border-bottom: 1px solid var(--sp-border);
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			.cart-drawer-items {
				flex-grow: 1;
				overflow-y: auto;
				padding: 20px;
				display: flex;
				flex-direction: column;
				gap: 15px;
			}
			.cart-item-row {
				display: flex;
				gap: 12px;
				align-items: center;
				padding-bottom: 12px;
				border-bottom: 1px solid #f1f5f9;
			}
			.cart-item-img {
				width: 60px;
				height: 60px;
				border-radius: 10px;
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
				font-size: 0.85rem;
				color: #059669;
				font-weight: 800;
			}
			.cart-drawer-footer {
				padding: 20px;
				border-top: 1px solid var(--sp-border);
				background: #f8fafc;
			}
			.cart-total-row {
				display: flex;
				justify-content: space-between;
				font-size: 1.1rem;
				font-weight: 800;
				margin-bottom: 15px;
			}

			/* Mobile Off-Canvas Drawer Menu */
			.mobile-drawer-overlay {
				position: fixed;
				inset: 0;
				background: rgba(0, 0, 0, 0.45);
				backdrop-filter: blur(4px);
				z-index: 9998;
				opacity: 0;
				pointer-events: none;
				transition: opacity 0.3s ease;
			}
			.mobile-drawer-overlay.open {
				opacity: 1;
				pointer-events: auto;
			}
			.mobile-drawer {
				position: fixed;
				top: 0;
				right: -320px;
				width: 300px;
				height: 100vh;
				background: #ffffff;
				z-index: 9999;
				box-shadow: -10px 0 30px rgba(0,0,0,0.15);
				transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
				display: flex;
				flex-direction: column;
				padding: 20px;
				overflow-y: auto;
			}
			.mobile-drawer.open {
				right: 0;
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
				max-width: 780px;
				width: 100%;
				overflow: hidden;
				position: relative;
				animation: modalSlide 0.3s cubic-bezier(0.16, 1, 0.3, 1);
				box-shadow: 0 25px 60px -15px rgba(0,0,0,0.3);
			}
			@keyframes modalSlide {
				from { transform: translateY(30px); opacity: 0; }
				to { transform: translateY(0); opacity: 1; }
			}
			.modal-close {
				position: absolute;
				top: 18px;
				left: 18px;
				font-size: 1.4rem;
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
			}
			.modal-inner { display: flex; flex-direction: column; max-height: 85vh; overflow-y: auto; }
			@media(min-width: 680px) {
				.modal-inner { flex-direction: row; }
				.modal-col-img { width: 45%; }
				.modal-col-info { width: 55%; padding: 30px; }
			}
			.modal-col-info { padding: 20px; }
			.modal-col-img img { width: 100%; height: 100%; object-fit: cover; }

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

			/* Responsive */
			@media (max-width: 860px) {
				.store-topbar { flex-direction: column; gap: 8px; text-align: center; }
				.store-header-search { order: 3; max-width: 100%; width: 100%; }
				.store-navbar { display: none; }
				.btn-mobile-toggle { display: block; }
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
					<svg viewBox="0 0 24 24"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"/></svg>
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

					<?php if ( count( $prof_counts ) > 0 ) : ?>
						<div style="position:relative; display:inline-block;">
							<button type="button" class="nav-item-link" id="sourcesDropdownBtn" style="border:none; background:transparent; cursor:pointer;">
								<span>🏷️</span>
								<span>برندها و تأمین‌کنندگان</span>
								<span style="font-size:0.7rem;">▼</span>
							</button>
							<div class="mega-dropdown-panel" id="sourcesDropdownPanel">
								<div style="padding:8px 10px; font-weight:800; font-size:0.85rem; color:#64748b; border-bottom:1px solid #f1f5f9;">انتخاب برند و سازنده:</div>
								<div class="dropdown-cat-item" data-profile="all">
									<span>همه برندها</span>
									<span class="filter-pill-badge"><?php echo self::to_fa_num( count( $products ) ); ?></span>
								</div>
								<?php foreach ( $prof_counts as $pr_name => $pr_count ) : ?>
									<div class="dropdown-cat-item" data-profile="<?php echo esc_attr( $pr_name ); ?>">
										<span><?php echo esc_html( $pr_name ); ?></span>
										<span class="filter-pill-badge"><?php echo self::to_fa_num( $pr_count ); ?></span>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>

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

			<!-- Mobile Drawer -->
			<div class="mobile-drawer-overlay" id="mobileDrawerOverlay"></div>
			<div class="mobile-drawer" id="mobileDrawer">
				<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; padding-bottom:15px; border-bottom:1px solid #e2e8f0;">
					<span style="font-weight:900; font-size:1.1rem;">منوی فروشگاه</span>
					<button type="button" id="closeMobileDrawer" style="background:none; border:none; font-size:1.5rem; cursor:pointer; color:#64748b;">✕</button>
				</div>
				<div style="margin-bottom:20px;">
					<input type="text" id="mobileSearchInput" placeholder="جستجوی کالا..." style="width:100%; padding:10px 14px; border-radius:10px; border:1px solid #cbd5e1;">
				</div>
				<div style="font-weight:800; font-size:0.9rem; color:#64748b; margin-bottom:10px;">دسته‌بندی‌ها:</div>
				<div style="display:flex; flex-direction:column; gap:6px; margin-bottom:20px;">
					<div class="dropdown-cat-item" data-cat="all" style="background:#f8fafc;">همه دسته‌ها</div>
					<?php foreach ( $categories as $cat_name => $cat_count ) : ?>
						<div class="dropdown-cat-item" data-cat="<?php echo esc_attr( $cat_name ); ?>" style="background:#f8fafc;">
							<span><?php echo esc_html( $cat_name ); ?></span>
							<span class="filter-pill-badge"><?php echo self::to_fa_num( $cat_count ); ?></span>
						</div>
					<?php endforeach; ?>
				</div>
				<a href="<?php echo esc_url( $account_url ); ?>" class="btn-card-buy" style="margin-top:auto; padding:12px; text-align:center;">
					👤 ورود / ثبت‌نام در فروشگاه
				</a>
			</div>

			<!-- Hero Banner -->
			<div class="modern-shop-hero">
				<h1><?php echo esc_html( $settings['shop_title'] ); ?></h1>
				<p><?php echo esc_html( $settings['shop_subtitle'] ); ?></p>
				
				<?php if ( ! empty( $settings['show_features_banner'] ) ) : ?>
					<div class="hero-features-bar">
						<div class="hero-feature-item"><span>🚀</span> ارسال سریع سراسر کشور</div>
						<div class="hero-feature-item"><span>💎</span> تضمین اصالت و سلامت کالا</div>
						<div class="hero-feature-item"><span>🔄</span> تضمین بهترین قیمت بازار</div>
						<div class="hero-feature-item"><span>🛡️</span> پشتیبانی ۲۴ ساعته</div>
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
						<span id="productCounter" style="font-weight:700; color:#475569;">
							نمایش <?php echo self::to_fa_num( count( $products ) ); ?> محصول فعال
						</span>
					</div>
					<div class="toolbar-left" style="display:flex; align-items:center; gap:10px;">
						<label for="sortSelector" style="font-size:0.9rem; color:#64748b;">مرتب‌سازی:</label>
						<select id="sortSelector" class="sort-select">
							<option value="default">پیش‌فرض</option>
							<option value="price-asc">ارزان‌ترین به گران‌ترین</option>
							<option value="price-desc">گران‌ترین به ارزان‌ترین</option>
							<option value="title">نام محصول (الف-ی)</option>
						</select>
					</div>
				</div>

				<!-- Brand Filter Pills (if multiple brands exist) -->
				<?php if ( count( $prof_counts ) > 1 ) : ?>
					<div style="margin-bottom:8px; font-size:0.9rem; font-weight:700; color:#64748b;">فیلتر بر اساس برند:</div>
					<div class="filter-pills-wrap" id="profilePills">
						<div class="filter-pill active" data-profile="all">همه برندها <span class="filter-pill-badge"><?php echo self::to_fa_num( count( $products ) ); ?></span></div>
						<?php foreach ( $prof_counts as $pr_name => $pr_count ) : ?>
							<div class="filter-pill" data-profile="<?php echo esc_attr( $pr_name ); ?>">
								<?php echo esc_html( $pr_name ); ?>
								<span class="filter-pill-badge"><?php echo self::to_fa_num( $pr_count ); ?></span>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<!-- Category Filter Pills -->
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
							data-profile="<?php echo esc_attr( $p['profile'] ); ?>"
							data-title="<?php echo esc_attr( mb_strtolower( $p['title'] ) ); ?>"
							data-price-num="<?php echo esc_attr( $p['price'] ); ?>">
							
							<div class="card-thumb-wrap">
								<span class="card-stock-badge">✨ موجود</span>
								<?php if ( ! empty( $settings['show_brand_badge'] ) && ! empty( $p['profile'] ) ) : ?>
									<span class="card-brand-tag">🏷️ <?php echo esc_html( $p['profile'] ); ?></span>
								<?php endif; ?>

								<?php if ( ! empty( $p['image'] ) ) : ?>
									<img src="<?php echo esc_url( $p['image'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy">
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
											<span class="card-special-badge">✨ پیشنهاد ویژه</span>
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
										data-desc="<?php echo esc_attr( $p['description'] ); ?>"
										data-profile="<?php echo esc_attr( $p['profile'] ); ?>"
										data-link="<?php echo esc_url( $p['link'] ); ?>">
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
							<div style="display:flex; gap:10px; margin-bottom:8px;">
								<span id="modalCat" style="background:#f1f5f9; padding:4px 10px; border-radius:6px; font-size:0.8rem; font-weight:700; color:#475569;"></span>
								<span id="modalProfile" style="background:#e0f2fe; color:#0369a1; padding:4px 10px; border-radius:6px; font-size:0.8rem; font-weight:700;"></span>
							</div>
							<h2 id="modalTitle" style="font-size:1.35rem; font-weight:900; margin-bottom:15px; line-height:1.5;"></h2>
							
							<div style="margin-bottom:15px;">
								<div id="modalOldPrice" style="font-size:0.9rem; color:#94a3b8; text-decoration:line-through; margin-bottom:2px;"></div>
								<div id="modalPrice" style="font-size:1.6rem; font-weight:900; color:#059669;"></div>
							</div>

							<p id="modalDesc" style="color:#64748b; font-size:0.92rem; line-height:1.8; max-height:150px; overflow-y:auto; margin-bottom:20px;"></p>
							
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

			let cart = [];
			try {
				const saved = localStorage.getItem('modern_shop_cart');
				if (saved) cart = JSON.parse(saved);
			} catch(e) {}

			function toFa(num) {
				const fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
				return String(num).replace(/\d/g, d => fa[d]);
			}

			function formatPrice(num) {
				return toFa(new Intl.NumberFormat('en-US').format(Math.round(num))) + ' <?php echo esc_js( $settings['currency_symbol'] ); ?>';
			}

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

			// Brands dropdown toggle
			const sourcesBtn = document.getElementById('sourcesDropdownBtn');
			const sourcesPanel = document.getElementById('sourcesDropdownPanel');
			if (sourcesBtn && sourcesPanel) {
				sourcesBtn.addEventListener('click', (e) => {
					e.stopPropagation();
					sourcesPanel.classList.toggle('open');
				});
				document.addEventListener('click', () => {
					sourcesPanel.classList.remove('open');
				});
			}

			// Mobile Drawer toggle
			const mobToggle = document.getElementById('mobileMenuToggle');
			const mobDrawer = document.getElementById('mobileDrawer');
			const mobOverlay = document.getElementById('mobileDrawerOverlay');
			const mobClose = document.getElementById('closeMobileDrawer');

			function toggleMobile(open) {
				if (!mobDrawer) return;
				if (open) {
					mobDrawer.classList.add('open');
					mobOverlay.classList.add('open');
				} else {
					mobDrawer.classList.remove('open');
					mobOverlay.classList.remove('open');
				}
			}

			if (mobToggle) mobToggle.addEventListener('click', () => toggleMobile(true));
			if (mobClose) mobClose.addEventListener('click', () => toggleMobile(false));
			if (mobOverlay) mobOverlay.addEventListener('click', () => toggleMobile(false));

			// Filtering & Searching Logic
			let currentCat = 'all';
			let currentProfile = 'all';
			let searchQuery = '';

			const headerSearch = document.getElementById('headerLiveSearch');
			const mobileSearch = document.getElementById('mobileSearchInput');

			function applyFilters() {
				const cards = app.querySelectorAll('.product-card');
				let visibleCount = 0;

				cards.forEach(card => {
					const cat = card.getAttribute('data-cat');
					const prof = card.getAttribute('data-profile');
					const title = card.getAttribute('data-title');

					const matchCat = (currentCat === 'all' || cat === currentCat);
					const matchProf = (currentProfile === 'all' || prof === currentProfile);
					const matchSearch = (!searchQuery || title.includes(searchQuery));

					if (matchCat && matchProf && matchSearch) {
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
			}

			function onSearch(val) {
				searchQuery = val.trim().toLowerCase();
				if (headerSearch && headerSearch.value !== val) headerSearch.value = val;
				if (mobileSearch && mobileSearch.value !== val) mobileSearch.value = val;
				applyFilters();
			}

			if (headerSearch) {
				headerSearch.addEventListener('input', (e) => onSearch(e.target.value));
			}
			if (mobileSearch) {
				mobileSearch.addEventListener('input', (e) => onSearch(e.target.value));
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
					toggleMobile(false);
					applyFilters();
					const grid = document.getElementById('productsGrid');
					if (grid) grid.scrollIntoView({behavior:'smooth'});
				});
			});

			// Brand pill clicks
			app.querySelectorAll('#profilePills .filter-pill').forEach(pill => {
				pill.addEventListener('click', () => {
					app.querySelectorAll('#profilePills .filter-pill').forEach(p => p.classList.remove('active'));
					pill.classList.add('active');
					currentProfile = pill.getAttribute('data-profile');
					applyFilters();
				});
			});

			// Dropdown brand clicks
			app.querySelectorAll('.dropdown-cat-item[data-profile]').forEach(item => {
				item.addEventListener('click', (e) => {
					e.preventDefault();
					const prof = item.getAttribute('data-profile');
					currentProfile = prof;
					app.querySelectorAll('#profilePills .filter-pill').forEach(p => {
						p.classList.toggle('active', p.getAttribute('data-profile') === prof);
					});
					if (sourcesPanel) sourcesPanel.classList.remove('open');
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

			// Cart Management
			function updateCartUI() {
				const countEl = document.getElementById('headerCartCount');
				const listEl = document.getElementById('cartItemsList');
				const totalEl = document.getElementById('cartTotalPrice');

				const totalItems = cart.reduce((acc, it) => acc + it.qty, 0);
				const totalPrice = cart.reduce((acc, it) => acc + (it.price * it.qty), 0);

				if (countEl) countEl.textContent = toFa(totalItems);
				if (totalEl) totalEl.textContent = formatPrice(totalPrice);

				if (listEl) {
					if (cart.length === 0) {
						listEl.innerHTML = '<div style="text-align:center; color:#94a3b8; padding:40px 10px;">سبد خرید شما در حال حاضر خالی است.</div>';
					} else {
						listEl.innerHTML = cart.map((it, idx) => `
							<div class="cart-item-row">
								<img src="${it.img || ''}" class="cart-item-img" alt="${it.title}">
								<div class="cart-item-info">
									<div class="cart-item-title">${it.title}</div>
									<div class="cart-item-price">${it.priceTxt || formatPrice(it.price)} × ${toFa(it.qty)}</div>
								</div>
								<button type="button" class="remove-cart-item" data-idx="${idx}" style="background:none; border:none; color:#ef4444; font-size:1.1rem; cursor:pointer;">✕</button>
							</div>
						`).join('');

						listEl.querySelectorAll('.remove-cart-item').forEach(btn => {
							btn.addEventListener('click', () => {
								const idx = parseInt(btn.getAttribute('data-idx'));
								cart.splice(idx, 1);
								saveCart();
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

			function addToCart(prod) {
				const found = cart.find(it => it.id === prod.id);
				if (found) {
					found.qty++;
				} else {
					cart.push({
						id: prod.id,
						title: prod.title,
						price: prod.price,
						priceTxt: prod.priceTxt,
						img: prod.img,
						qty: 1
					});
				}
				saveCart();
				openCartDrawer();
			}

			// Add to cart buttons
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
					addToCart(prod);
				});
			});

			// Drawer controls
			const cartDrawer = document.getElementById('cartDrawer');
			const cartOverlay = document.getElementById('cartDrawerOverlay');
			const closeCart = document.getElementById('closeCartDrawer');
			const headerCartBtn = document.getElementById('headerCartBtn');

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
			if (closeCart) closeCart.addEventListener('click', closeCartDrawer);
			if (cartOverlay) cartOverlay.addEventListener('click', closeCartDrawer);

			// Quick View Modal
			const qvModal = document.getElementById('quickViewModal');
			const closeQv = document.getElementById('closeQuickView');
			let activeModalProduct = null;

			app.querySelectorAll('.open-quick-view').forEach(btn => {
				btn.addEventListener('click', () => {
					const title = btn.getAttribute('data-title');
					const price = btn.getAttribute('data-price');
					const oldPrice = btn.getAttribute('data-old-price');
					const img = btn.getAttribute('data-img');
					const cat = btn.getAttribute('data-cat');
					const desc = btn.getAttribute('data-desc');
					const profile = btn.getAttribute('data-profile');

					activeModalProduct = {
						id: 'qv_' + Math.random(),
						title, price: 0, priceTxt: price, img
					};

					document.getElementById('modalTitle').textContent = title;
					document.getElementById('modalPrice').textContent = price;
					
					const oldEl = document.getElementById('modalOldPrice');
					if (oldPrice) {
						oldEl.textContent = oldPrice;
						oldEl.style.display = 'block';
					} else {
						oldEl.style.display = 'none';
					}

					document.getElementById('modalCat').textContent = '📂 ' + (cat || 'عمومی');
					document.getElementById('modalProfile').textContent = profile ? '🏷️ برند: ' + profile : '';
					document.getElementById('modalDesc').textContent = desc || 'توضیحات تکمیلی برای این محصول درج نشده است.';
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

			const modalAddBtn = document.getElementById('modalAddToCartBtn');
			if (modalAddBtn) {
				modalAddBtn.addEventListener('click', () => {
					if (activeModalProduct) {
						addToCart(activeModalProduct);
						qvModal.classList.remove('open');
					}
				});
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
				'show_brand_badge'        => ! empty( $_POST['show_brand_badge'] ),
				'show_features_banner'    => ! empty( $_POST['show_features_banner'] ),
				'show_special_badge'      => ! empty( $_POST['show_special_badge'] ),
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
						برای تعریف پروفایل‌های جدید (باسلام، ترب، دیجی‌کالا، فروشگاه‌های ووکامرس)، تنظیم سلکتورها و اجرای فرایند استخراج، وارد داشبورد اسکرپر شوید. تمام محصولات استخراج‌شده فوراً در ویترین مدرن این فروشگاه ظاهر می‌شوند.
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
					</table>
				</div>

				<!-- Price Adjustment Settings -->
				<div style="background:#fff; border:1px solid #e2e8f0; border-radius:16px; padding:22px 25px; margin-bottom:25px; box-shadow:0 4px 12px rgba(0,0,0,0.03);">
					<h3 style="margin-top:0; margin-bottom:15px; font-size:1.15rem; font-weight:800; color:#0f172a; border-bottom:1px solid #f1f5f9; padding-bottom:10px;">
						💰 قوانین تعدیل خودکار قیمت و رفع مشکل «تماس بگیرید»
					</h3>

					<table class="form-table">
						<tr>
							<th scope="row">درصد افزایش قیمت (Markup %):</th>
							<td>
								<input type="number" step="0.5" name="price_markup_percent" value="<?php echo esc_attr( $opts['price_markup_percent'] ); ?>" class="small-text"> ٪
								<p class="description">این درصد به طور خودکار به قیمت خام استخراج‌شده از منابع اضافه می‌شود (مثلاً ۲۰٪ سود).</p>
							</td>
						</tr>
						<tr>
							<th scope="row">مبلغ ثابت اضافه شونده:</th>
							<td>
								<input type="number" name="price_fixed_add" value="<?php echo esc_attr( $opts['price_fixed_add'] ); ?>" class="regular-text">
								<p class="description">مبلغ ثابت به تومان که پس از درصد افزایش به قیمت نهایی اضافه می‌شود (مثلاً ۱۰,۰۰۰ تومان هزینه بسته‌بندی).</p>
							</td>
						</tr>
						<tr>
							<th scope="row">قیمت پایه پیش‌فرض (در صورت نبود قیمت در منبع):</th>
							<td>
								<input type="number" name="default_fallback_price" value="<?php echo esc_attr( $opts['default_fallback_price'] ); ?>" class="regular-text"> تومان
								<p class="description">اگر کالایی در سایت مبدأ بدون قیمت بود یا سلکتور قیمت روی آن عمل نکرد، این قیمت به عنوان قیمت پایه استفاده شده و درصد افزایش به آن تعلق می‌گیرد تا کالایی با «تماس بگیرید» نمایش داده نشود.</p>
							</td>
						</tr>
						<tr>
							<th scope="row">رفتار هنگام نبود قیمت در منبع:</th>
							<td>
								<select name="fallback_price_behavior" class="regular-text">
									<option value="use_fallback" <?php selected( $opts['fallback_price_behavior'], 'use_fallback' ); ?>>استفاده از قیمت پایه پیش‌فرض و نمایش قیمت تعدیل‌شده (توصیه شده)</option>
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
								<label style="display:block; margin-bottom:8px;">
									<input type="checkbox" name="show_brand_badge" value="1" <?php checked( ! empty( $opts['show_brand_badge'] ) ); ?>>
									نمایش برچسب برند/سازنده روی تصویر محصول
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
					با فشردن دکمه زیر، تمامی محصولات به عنوان محصولات رسمی ووکامرس (با قیمت نهایی، دسته‌بندی و متای برند) در دیتابیس وردپرس درج یا به‌روزرسانی می‌شوند:
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
