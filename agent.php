<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 11.1.0
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
			'enable_shop_takeover' => true,
			'price_markup_percent' => 20,
			'price_fixed_add'      => 0,
			'price_rounding'       => '1000', // none, 1000, 10000
			'currency_symbol'      => 'تومان',
			'shop_title'           => 'فروشگاه آنلاین و مدرن',
			'shop_subtitle'        => 'تنوع بی‌نظیر محصولات با تضمین اصالت و بهترین قیمت بازار',
			'accent_color'         => '#2563eb',
			'products_per_page'    => 16,
			'show_source_badge'    => true,
			'show_features_banner' => true,
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
			'تنظیمات فروشگاه و قیمت',
			'تنظیمات فروشگاه و قیمت',
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
	 * Convert English numbers to Persian.
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
	 * Price Adjustment Engine.
	 *
	 * @param int|float|string $raw_price
	 * @param array|null       $settings
	 * @return array
	 */
	public static function calculate_price( $raw_price, $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}

		$cleaned = preg_replace( '/[^\d]/', '', (string) $raw_price );
		$original = ! empty( $cleaned ) ? (float) $cleaned : 0;

		if ( $original <= 0 ) {
			return array(
				'original'  => 0,
				'adjusted'  => 0,
				'formatted' => 'تماس بگیرید',
			);
		}

		$markup_pct = (float) ( $settings['price_markup_percent'] ?? 0 );
		$fixed_add  = (float) ( $settings['price_fixed_add'] ?? 0 );
		$rounding   = (string) ( $settings['price_rounding'] ?? '1000' );
		$currency   = (string) ( $settings['currency_symbol'] ?? 'تومان' );

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
			'original'  => $original,
			'adjusted'  => $adjusted,
			'formatted' => $formatted,
		);
	}

	/**
	 * Get summary of extracted profiles from profiles.json.
	 *
	 * @return array
	 */
	public static function get_profiles_summary() {
		$summary = array();
		$profiles_file = plugin_dir_path( __FILE__ ) . 'profiles.json';
		if ( file_exists( $profiles_file ) ) {
			$json = @file_get_contents( $profiles_file );
			$data = @json_decode( $json, true );
			if ( is_array( $data ) ) {
				foreach ( $data as $key => $p ) {
					if ( is_array( $p ) ) {
						$raw_prods = $p['products'] ?? array();
						$prod_count = 0;
						if ( is_array( $raw_prods ) ) {
							$prod_count = count( $raw_prods );
						}
						$summary[] = array(
							'key'       => $key,
							'name'      => ! empty( $p['name'] ) ? $p['name'] : $key,
							'url'       => $p['url'] ?? '',
							'count'     => $prod_count,
							'updatedAt' => $p['updatedAt'] ?? 0,
						);
					}
				}
			}
		}
		return $summary;
	}

	/**
	 * Load REAL products extracted from profiles.json (NO hardcoded fake products).
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

							$raw_price  = $prod['final_price'] ?? $prod['price'] ?? 0;
							$price_calc = self::calculate_price( $raw_price, $settings );

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

		// Also check woo_products_temp.json if present
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
								$price_calc     = self::calculate_price( $prod['price'] ?? 0, $settings );
								$img            = $prod['image'] ?? $prod['img'] ?? '';
								$gallery        = ! empty( $prod['images'] ) && is_array( $prod['images'] ) ? $prod['images'] : ( ! empty( $img ) ? array( $img ) : array() );
								$products[]     = array(
									'id'              => $hash,
									'title'           => $title,
									'original_price'  => $price_calc['original'],
									'price'           => $price_calc['adjusted'],
									'price_formatted' => $price_calc['formatted'],
									'image'           => $img,
									'gallery'         => $gallery,
									'category'        => $prod['category'] ?? $prod['cat'] ?? 'عمومی',
									'description'     => $prod['description'] ?? $prod['desc'] ?? '',
									'link'            => $prod['link'] ?? $prod['url'] ?? '',
									'profile'         => 'ووکامرس / استخراج‌شده',
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

			if ( ! empty( $existing ) ) {
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
				wp_update_post( $post_data );
				$prod_id = $existing_id;
				$updated++;
			} else {
				$prod_id = wp_insert_post( $post_data );
				$created++;
			}

			if ( $prod_id && ! is_wp_error( $prod_id ) ) {
				update_post_meta( $prod_id, '_price', $p['price'] );
				update_post_meta( $prod_id, '_regular_price', $p['price'] );
				update_post_meta( $prod_id, '_manage_stock', 'no' );
				update_post_meta( $prod_id, '_stock_status', 'instock' );
				update_post_meta( $prod_id, '_scraped_source_profile', $p['profile'] );
				update_post_meta( $prod_id, '_scraped_original_price', $p['original_price'] );
				if ( ! empty( $p['image'] ) ) {
					update_post_meta( $prod_id, '_scraped_image_url', $p['image'] );
				}

				if ( ! empty( $p['category'] ) && taxonomy_exists( 'product_cat' ) ) {
					wp_set_object_terms( $prod_id, $p['category'], 'product_cat', true );
				}
			}
		}

		return array(
			'ok'      => true,
			'created' => $created,
			'updated' => $updated,
			'total'   => count( $products ),
		);
	}

	/**
	 * AJAX Handler: Sync to WooCommerce.
	 */
	public static function ajax_sync_to_woo() {
		check_ajax_referer( 'scraper_shop_admin_nonce', 'nonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Unauthorized' );
		}

		$result = self::sync_to_woocommerce();
		wp_send_json_success( $result );
	}

	/**
	 * Maybe takeover WooCommerce shop page template.
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
		get_header();
		echo '<div class="scraper-shop-wrap" style="width:100%; max-width:1440px; margin:0 auto; padding:15px;">';
		echo self::render_shop_shortcode();
		echo '</div>';
		get_footer();
	}

	/**
	 * Shortcode [scraped_shop] / [modern_shop] HTML Renderer.
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

		// Unique profiles
		$prof_counts = array();
		foreach ( $products as $p ) {
			$pr = ! empty( $p['profile'] ) ? $p['profile'] : 'سایر';
			$prof_counts[ $pr ] = ( $prof_counts[ $pr ] ?? 0 ) + 1;
		}

		ob_start();
		?>
		<!-- Scraper & Auto Shop Modern Storefront -->
		<style>
			:root {
				--sp-accent: <?php echo esc_attr( $settings['accent_color'] ); ?>;
				--sp-accent-rgb: 37, 99, 235;
				--sp-accent-hover: #1d4ed8;
				--sp-bg-card: #ffffff;
				--sp-border: #e2e8f0;
				--sp-text: #0f172a;
				--sp-muted: #64748b;
				--sp-radius: 20px;
				--sp-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.07);
			}
			.modern-shop-root {
				font-family: Vazirmatn, system-ui, -apple-system, sans-serif;
				direction: rtl;
				text-align: right;
				margin: 25px auto 60px;
				color: var(--sp-text);
				width: 100%;
			}
			/* Luxury Hero Banner */
			.modern-shop-hero {
				background: radial-gradient(circle at 85% 20%, rgba(37,99,235,0.3) 0%, transparent 50%),
				            linear-gradient(135deg, #090d16 0%, #0f172a 50%, #1e1b4b 100%);
				border-radius: var(--sp-radius);
				padding: 55px 30px;
				color: #fff;
				text-align: center;
				margin-bottom: 35px;
				box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35);
				position: relative;
				overflow: hidden;
				border: 1px solid rgba(255,255,255,0.1);
			}
			.modern-shop-hero h1 {
				font-size: 2.6rem;
				font-weight: 900;
				margin-bottom: 12px;
				color: #ffffff;
				letter-spacing: -0.5px;
			}
			.modern-shop-hero p {
				font-size: 1.15rem;
				color: #cbd5e1;
				max-width: 680px;
				margin: 0 auto 30px;
				line-height: 1.7;
			}
			.hero-features-bar {
				display: flex;
				flex-wrap: wrap;
				justify-content: center;
				gap: 15px;
				margin-bottom: 30px;
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
			.shop-search-box {
				max-width: 580px;
				margin: 0 auto;
				position: relative;
			}
			.shop-search-box input {
				width: 100%;
				padding: 16px 52px 16px 25px;
				border-radius: 50px;
				border: 1px solid rgba(255,255,255,0.25);
				background: rgba(255,255,255,0.12);
				backdrop-filter: blur(16px);
				color: #fff;
				font-size: 1.05rem;
				outline: none;
				transition: all 0.3s ease;
			}
			.shop-search-box input:focus {
				background: rgba(255,255,255,0.22);
				border-color: #fff;
				box-shadow: 0 0 30px rgba(255,255,255,0.25);
			}
			.shop-search-box input::placeholder { color: #cbd5e1; }
			.shop-search-box svg {
				position: absolute;
				right: 20px;
				top: 50%;
				transform: translateY(-50%);
				width: 22px;
				height: 22px;
				fill: #94a3b8;
			}
			/* Toolbar & Controls */
			.shop-toolbar {
				background: #fff;
				border: 1px solid var(--sp-border);
				border-radius: 16px;
				padding: 16px 20px;
				margin-bottom: 25px;
				display: flex;
				flex-wrap: wrap;
				align-items: center;
				justify-content: space-between;
				gap: 15px;
				box-shadow: 0 4px 15px rgba(0,0,0,0.03);
			}
			.toolbar-left, .toolbar-right {
				display: flex;
				align-items: center;
				gap: 12px;
				flex-wrap: wrap;
			}
			.sort-select {
				padding: 8px 14px;
				border-radius: 10px;
				border: 1px solid var(--sp-border);
				background: #f8fafc;
				font-size: 0.9rem;
				color: var(--sp-text);
				outline: none;
			}
			.filter-pills-wrap {
				display: flex;
				flex-wrap: wrap;
				gap: 8px;
				margin-bottom: 25px;
			}
			.filter-pill {
				padding: 9px 18px;
				border-radius: 30px;
				background: #fff;
				color: #475569;
				font-weight: 600;
				cursor: pointer;
				border: 1px solid var(--sp-border);
				transition: all 0.2s ease;
				font-size: 0.9rem;
				display: flex;
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
			/* Grid of Cards */
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
			.card-source-tag {
				position: absolute;
				top: 12px;
				right: 12px;
				background: rgba(15, 23, 42, 0.75);
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
			.card-old-price {
				font-size: 0.85rem;
				color: #94a3b8;
				text-decoration: line-through;
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
			/* Floating Cart Badge */
			.floating-cart-btn {
				position: fixed;
				bottom: 28px;
				left: 28px;
				background: var(--sp-accent);
				color: #fff;
				width: 65px;
				height: 65px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				cursor: pointer;
				box-shadow: 0 10px 25px rgba(37,99,235,0.4);
				z-index: 9999;
				transition: transform 0.2s;
			}
			.floating-cart-btn:hover { transform: scale(1.08); }
			.floating-cart-count {
				position: absolute;
				top: -4px;
				right: -4px;
				background: #ef4444;
				color: #fff;
				font-size: 0.8rem;
				font-weight: 800;
				width: 24px;
				height: 24px;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				border: 2px solid #fff;
			}
			/* Cart Drawer */
			.cart-drawer-overlay {
				position: fixed;
				inset: 0;
				background: rgba(0,0,0,0.5);
				backdrop-filter: blur(4px);
				z-index: 99998;
				display: none;
			}
			.cart-drawer {
				position: fixed;
				top: 0;
				left: -400px;
				width: 100%;
				max-width: 390px;
				height: 100vh;
				background: #fff;
				z-index: 99999;
				box-shadow: 0 0 30px rgba(0,0,0,0.2);
				transition: left 0.3s cubic-bezier(0.16, 1, 0.3, 1);
				display: flex;
				flex-direction: column;
			}
			.cart-drawer.open { left: 0; }
			.cart-drawer-head {
				padding: 20px;
				border-bottom: 1px solid var(--sp-border);
				display: flex;
				justify-content: space-between;
				align-items: center;
			}
			.cart-drawer-body {
				padding: 20px;
				overflow-y: auto;
				flex-grow: 1;
			}
			.cart-drawer-foot {
				padding: 20px;
				border-top: 1px solid var(--sp-border);
				background: #f8fafc;
			}
			.cart-item {
				display: flex;
				gap: 12px;
				margin-bottom: 16px;
				padding-bottom: 16px;
				border-bottom: 1px solid #f1f5f9;
				align-items: center;
			}
			.cart-item img {
				width: 60px;
				height: 60px;
				border-radius: 10px;
				object-fit: cover;
			}
			/* Modal View */
			.modal-overlay {
				position: fixed;
				inset: 0;
				background: rgba(0,0,0,0.65);
				backdrop-filter: blur(6px);
				display: none;
				align-items: center;
				justify-content: center;
				z-index: 99999;
				padding: 20px;
			}
			.modal-box {
				background: #fff;
				border-radius: 24px;
				max-width: 720px;
				width: 100%;
				overflow: hidden;
				box-shadow: 0 25px 50px -12px rgba(0,0,0,0.3);
				position: relative;
				animation: modalSlide 0.3s ease;
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
		</style>

		<div class="modern-shop-root" id="modernShopApp">
			<!-- Hero Header -->
			<div class="modern-shop-hero">
				<h1><?php echo esc_html( $settings['shop_title'] ); ?></h1>
				<p><?php echo esc_html( $settings['shop_subtitle'] ); ?></p>
				
				<?php if ( ! empty( $settings['show_features_banner'] ) ) : ?>
					<div class="hero-features-bar">
						<div class="hero-feature-item"><span>🚀</span> ارسال سریع سراسر کشور</div>
						<div class="hero-feature-item"><span>💎</span> تضمین اصالت کالا</div>
						<div class="hero-feature-item"><span>🔄</span> قیمت‌های رقابتی با تخفیف ویژه</div>
						<div class="hero-feature-item"><span>🛡️</span> پشتیبانی ۲۴ ساعته</div>
					</div>
				<?php endif; ?>

				<div class="shop-search-box">
					<input type="text" id="shopSearchInput" placeholder="جستجوی سریع بین تمام محصولات...">
					<svg viewBox="0 0 24 24"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"/></svg>
				</div>
			</div>

			<?php if ( empty( $products ) ) : ?>
				<!-- Clean Empty State when no profiles scraped yet -->
				<div class="shop-empty-state">
					<div class="empty-icon">📦</div>
					<h3>هنوز محصولی از پروفایل‌ها استخراج نشده است</h3>
					<p>محصولات این فروشگاه مستقیماً از پروفایل‌های تعریف‌شده در اسکرپر بارگذاری می‌شوند. برای اضافه کردن محصول، وارد پنل مدیریت اسکرپر شوید و اولین استخراج خود را آغاز کنید.</p>
					<?php if ( current_user_can( 'manage_options' ) ) : ?>
						<a href="<?php echo admin_url( 'admin.php?page=scraper-full-dashboard' ); ?>" class="btn-card-buy" style="padding:12px 28px; font-size:1rem;">
							⚡ ورود به پنل اسکرپر و استخراج محصولات
						</a>
					<?php endif; ?>
				</div>
			<?php else : ?>

				<!-- Toolbar -->
				<div class="shop-toolbar">
					<div class="toolbar-right">
						<span id="productCounter" style="font-weight:700; color:#475569;">
							نمایش <?php echo self::to_fa_num( count( $products ) ); ?> محصول استخراج‌شده
						</span>
					</div>
					<div class="toolbar-left">
						<label for="sortSelector" style="font-size:0.9rem; color:#64748b;">مرتب‌سازی:</label>
						<select id="sortSelector" class="sort-select">
							<option value="default">پیش‌فرض</option>
							<option value="price-asc">ارزان‌ترین به گران‌ترین</option>
							<option value="price-desc">گران‌ترین به ارزان‌ترین</option>
							<option value="title">نام محصول (الف-ی)</option>
						</select>
					</div>
				</div>

				<!-- Source Profile Filter Pills (if multiple profiles exist) -->
				<?php if ( count( $prof_counts ) > 1 ) : ?>
					<div style="margin-bottom:12px; font-size:0.9rem; font-weight:700; color:#64748b;">فیلتر بر اساس پروفایل منبع:</div>
					<div class="filter-pills-wrap" id="profilePills">
						<div class="filter-pill active" data-profile="all">همه منابع <span class="filter-pill-badge"><?php echo self::to_fa_num( count( $products ) ); ?></span></div>
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
								<span class="card-stock-badge">موجود</span>
								<?php if ( ! empty( $settings['show_source_badge'] ) && ! empty( $p['profile'] ) ) : ?>
									<span class="card-source-tag">📍 <?php echo esc_html( $p['profile'] ); ?></span>
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
									<?php if ( $p['original_price'] > 0 && $p['original_price'] < $p['price'] ) : ?>
										<div class="card-old-price"><?php echo self::to_fa_num( number_format( $p['original_price'] ) ); ?> <?php echo esc_html( $settings['currency_symbol'] ); ?></div>
									<?php endif; ?>
									<div class="card-new-price"><?php echo esc_html( $p['price_formatted'] ); ?></div>
								</div>

								<div class="card-actions">
									<button type="button" class="btn-card-quick open-quick-view" 
										data-title="<?php echo esc_attr( $p['title'] ); ?>"
										data-price="<?php echo esc_attr( $p['price_formatted'] ); ?>"
										data-img="<?php echo esc_url( $p['image'] ); ?>"
										data-cat="<?php echo esc_attr( $p['category'] ); ?>"
										data-desc="<?php echo esc_attr( $p['description'] ); ?>"
										data-profile="<?php echo esc_attr( $p['profile'] ); ?>"
										data-link="<?php echo esc_url( $p['link'] ); ?>">
										جزئیات
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
		</div>

		<!-- Quick View Modal -->
		<div class="modal-overlay" id="quickViewModal">
			<div class="modal-box">
				<div class="modal-close" id="modalCloseBtn">&times;</div>
				<div class="modal-inner">
					<div class="modal-col-img">
						<img id="mImg" src="" alt="product">
					</div>
					<div class="modal-col-info">
						<div id="mProfileTag" style="display:inline-block; background:#f1f5f9; color:#475569; font-size:0.8rem; padding:4px 10px; border-radius:8px; margin-bottom:8px;"></div>
						<div id="mCat" style="font-size:0.85rem; color:#64748b; margin-bottom:6px;"></div>
						<h2 id="mTitle" style="font-size:1.35rem; font-weight:800; margin-bottom:15px; line-height:1.45;"></h2>
						<div id="mPrice" style="font-size:1.6rem; font-weight:900; color:#059669; margin-bottom:15px;"></div>
						<div id="mDesc" style="font-size:0.95rem; color:#475569; line-height:1.75; margin-bottom:25px; max-height:180px; overflow-y:auto;"></div>
						
						<div style="display:flex; gap:12px;">
							<button type="button" id="mAddCartBtn" class="btn-card-buy" style="flex:2; padding:12px; font-size:1rem;">افزودن به سبد خرید</button>
							<a id="mSourceBtn" href="#" target="_blank" class="btn-card-quick" style="flex:1; padding:12px; font-size:0.9rem; text-decoration:none; text-align:center;">لینک مرجع ↗</a>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Floating Cart Drawer & Button -->
		<div class="floating-cart-btn" id="floatingCartBtn" title="مشاهده سبد خرید">
			<span style="font-size:1.8rem;">🛒</span>
			<span class="floating-cart-count" id="cartCountBadge">۰</span>
		</div>

		<div class="cart-drawer-overlay" id="cartDrawerOverlay"></div>
		<div class="cart-drawer" id="cartDrawer">
			<div class="cart-drawer-head">
				<h3 style="margin:0; font-size:1.2rem; font-weight:800;">🛍️ سبد خرید شما</h3>
				<span id="closeCartBtn" style="cursor:pointer; font-size:1.5rem; color:#64748b;">&times;</span>
			</div>
			<div class="cart-drawer-body" id="cartItemsList">
				<div style="text-align:center; color:#94a3b8; padding:40px 0;">سبد خرید شما در حال حاضر خالی است.</div>
			</div>
			<div class="cart-drawer-foot">
				<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px; font-weight:800;">
					<span>مجموع کل:</span>
					<span id="cartTotalPrice" style="color:#059669; font-size:1.2rem;">۰ تومان</span>
				</div>
				<?php
				$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#';
				?>
				<a href="<?php echo esc_url( $checkout_url ); ?>" class="btn-card-buy" style="display:block; text-align:center; padding:14px; font-size:1.05rem;">
					تکمیل سفارش و تسویه‌حساب ➔
				</a>
			</div>
		</div>

		<script>
			document.addEventListener('DOMContentLoaded', function(){
				const search = document.getElementById('shopSearchInput');
				const sortSelect = document.getElementById('sortSelector');
				const catPills = document.querySelectorAll('#categoryPills .filter-pill');
				const profPills = document.querySelectorAll('#profilePills .filter-pill');
				const grid = document.getElementById('productsGrid');
				const counter = document.getElementById('productCounter');

				let currentCat = 'all';
				let currentProfile = 'all';

				function toFa(num){
					const en = ['0','1','2','3','4','5','6','7','8','9'];
					const fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
					return String(num).replace(/[0-9]/g, function(w){ return fa[en.indexOf(w)]; });
				}

				function applyFilters(){
					if(!grid) return;
					const cards = Array.from(grid.querySelectorAll('.product-card'));
					const query = (search ? search.value : '').toLowerCase().trim();
					let visibleCount = 0;

					cards.forEach(card => {
						const cCat = card.getAttribute('data-cat') || '';
						const cProf = card.getAttribute('data-profile') || '';
						const cTitle = card.getAttribute('data-title') || '';

						const matchCat = (currentCat === 'all' || cCat === currentCat);
						const matchProf = (currentProfile === 'all' || cProf === currentProfile);
						const matchQuery = (!query || cTitle.includes(query));

						if(matchCat && matchProf && matchQuery){
							card.style.display = 'flex';
							visibleCount++;
						} else {
							card.style.display = 'none';
						}
					});

					if(counter){
						counter.textContent = 'نمایش ' + toFa(visibleCount) + ' محصول';
					}
				}

				if(search) search.addEventListener('input', applyFilters);

				catPills.forEach(pill => {
					pill.addEventListener('click', function(){
						catPills.forEach(p => p.classList.remove('active'));
						this.classList.add('active');
						currentCat = this.getAttribute('data-cat');
						applyFilters();
					});
				});

				profPills.forEach(pill => {
					pill.addEventListener('click', function(){
						profPills.forEach(p => p.classList.remove('active'));
						this.classList.add('active');
						currentProfile = this.getAttribute('data-profile');
						applyFilters();
					});
				});

				// Sort
				if(sortSelect && grid){
					sortSelect.addEventListener('change', function(){
						const mode = this.value;
						const cards = Array.from(grid.querySelectorAll('.product-card'));
						cards.sort((a,b) => {
							const priceA = parseFloat(a.getAttribute('data-price-num') || 0);
							const priceB = parseFloat(b.getAttribute('data-price-num') || 0);
							const titleA = a.getAttribute('data-title') || '';
							const titleB = b.getAttribute('data-title') || '';

							if(mode === 'price-asc') return priceA - priceB;
							if(mode === 'price-desc') return priceB - priceA;
							if(mode === 'title') return titleA.localeCompare(titleB, 'fa');
							return 0;
						});
						cards.forEach(c => grid.appendChild(c));
					});
				}

				// Quick View Modal
				const modal = document.getElementById('quickViewModal');
				const mClose = document.getElementById('modalCloseBtn');
				const mImg = document.getElementById('mImg');
				const mTitle = document.getElementById('mTitle');
				const mCat = document.getElementById('mCat');
				const mProfileTag = document.getElementById('mProfileTag');
				const mPrice = document.getElementById('mPrice');
				const mDesc = document.getElementById('mDesc');
				const mSource = document.getElementById('mSourceBtn');
				const mAddCart = document.getElementById('mAddCartBtn');

				let currentModalProduct = null;

				document.querySelectorAll('.open-quick-view').forEach(btn => {
					btn.addEventListener('click', function(){
						currentModalProduct = {
							title: this.getAttribute('data-title'),
							priceTxt: this.getAttribute('data-price'),
							img: this.getAttribute('data-img'),
							cat: this.getAttribute('data-cat'),
							profile: this.getAttribute('data-profile'),
							desc: this.getAttribute('data-desc'),
							link: this.getAttribute('data-link')
						};

						mImg.src = currentModalProduct.img || '';
						mTitle.textContent = currentModalProduct.title || '';
						mCat.textContent = currentModalProduct.cat ? 'دسته: ' + currentModalProduct.cat : '';
						mProfileTag.textContent = currentModalProduct.profile ? 'منبع: ' + currentModalProduct.profile : '';
						mPrice.textContent = currentModalProduct.priceTxt || '';
						mDesc.textContent = currentModalProduct.desc || 'توضیحات تکمیلی برای این محصول در دسترس نیست.';
						mSource.href = currentModalProduct.link || '#';
						if(!currentModalProduct.link || currentModalProduct.link === '#'){
							mSource.style.display = 'none';
						} else {
							mSource.style.display = 'inline-block';
						}
						modal.style.display = 'flex';
					});
				});

				if(mClose) mClose.addEventListener('click', () => { modal.style.display = 'none'; });
				if(modal) modal.addEventListener('click', (e) => { if(e.target === modal) modal.style.display = 'none'; });

				// Cart Drawer state
				let cart = [];
				const cartBtn = document.getElementById('floatingCartBtn');
				const drawer = document.getElementById('cartDrawer');
				const overlay = document.getElementById('cartDrawerOverlay');
				const closeCart = document.getElementById('closeCartBtn');
				const cartList = document.getElementById('cartItemsList');
				const cartCountBadge = document.getElementById('cartCountBadge');
				const cartTotalPrice = document.getElementById('cartTotalPrice');

				function updateCartUI(){
					if(!cartList) return;
					if(cart.length === 0){
						cartList.innerHTML = '<div style="text-align:center; color:#94a3b8; padding:40px 0;">سبد خرید شما در حال حاضر خالی است.</div>';
						cartCountBadge.textContent = '۰';
						cartTotalPrice.textContent = '۰ <?php echo esc_attr( $settings['currency_symbol'] ); ?>';
						return;
					}

					let count = 0;
					let total = 0;
					let html = '';

					cart.forEach((item, idx) => {
						count += item.qty;
						total += (item.price * item.qty);
						html += `
							<div class="cart-item">
								<img src="${item.img || ''}" alt="">
								<div style="flex-grow:1;">
									<div style="font-weight:700; font-size:0.9rem; margin-bottom:4px;">${item.title}</div>
									<div style="color:#059669; font-weight:800; font-size:0.85rem;">${item.priceTxt}</div>
									<div style="display:flex; align-items:center; gap:8px; margin-top:6px;">
										<button type="button" class="btn-card-quick" style="padding:2px 8px;" onclick="changeCartQty(${idx}, -1)">-</button>
										<span>${toFa(item.qty)}</span>
										<button type="button" class="btn-card-quick" style="padding:2px 8px;" onclick="changeCartQty(${idx}, 1)">+</button>
									</div>
								</div>
								<span style="cursor:pointer; color:#ef4444; font-size:1.1rem;" onclick="removeCartItem(${idx})">&times;</span>
							</div>
						`;
					});

					cartList.innerHTML = html;
					cartCountBadge.textContent = toFa(count);
					cartTotalPrice.textContent = toFa(total.toLocaleString('en-US')) + ' <?php echo esc_attr( $settings['currency_symbol'] ); ?>';
				}

				window.changeCartQty = function(idx, delta){
					if(!cart[idx]) return;
					cart[idx].qty += delta;
					if(cart[idx].qty <= 0) cart.splice(idx, 1);
					updateCartUI();
				};

				window.removeCartItem = function(idx){
					cart.splice(idx, 1);
					updateCartUI();
				};

				document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
					btn.addEventListener('click', function(){
						const id = this.getAttribute('data-id');
						const title = this.getAttribute('data-title');
						const price = parseFloat(this.getAttribute('data-price') || 0);
						const priceTxt = this.getAttribute('data-price-txt');
						const img = this.getAttribute('data-img');

						const exist = cart.find(c => c.id === id);
						if(exist){
							exist.qty++;
						} else {
							cart.push({ id, title, price, priceTxt, img, qty: 1 });
						}
						updateCartUI();
						drawer.classList.add('open');
						overlay.style.display = 'block';
					});
				});

				if(mAddCart){
					mAddCart.addEventListener('click', function(){
						if(currentModalProduct){
							cart.push({
								id: currentModalProduct.title,
								title: currentModalProduct.title,
								price: 0,
								priceTxt: currentModalProduct.priceTxt,
								img: currentModalProduct.img,
								qty: 1
							});
							updateCartUI();
							modal.style.display = 'none';
							drawer.classList.add('open');
							overlay.style.display = 'block';
						}
					});
				}

				if(cartBtn){
					cartBtn.addEventListener('click', () => {
						drawer.classList.add('open');
						overlay.style.display = 'block';
					});
				}
				if(closeCart){
					closeCart.addEventListener('click', () => {
						drawer.classList.remove('open');
						overlay.style.display = 'none';
					});
				}
				if(overlay){
					overlay.addEventListener('click', () => {
						drawer.classList.remove('open');
						overlay.style.display = 'none';
					});
				}
			});
		</script>
		<?php
		return ob_get_clean();
	}

	/**
	 * Admin Dashboard & Settings Page HTML.
	 */
	public static function render_admin_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$updated = false;
		if ( isset( $_POST['scraper_shop_save'] ) && check_admin_referer( 'scraper_shop_settings_action', 'scraper_shop_settings_nonce' ) ) {
			$new_settings = array(
				'enable_shop_takeover' => ! empty( $_POST['enable_shop_takeover'] ),
				'price_markup_percent' => floatval( $_POST['price_markup_percent'] ?? 0 ),
				'price_fixed_add'      => floatval( $_POST['price_fixed_add'] ?? 0 ),
				'price_rounding'       => sanitize_text_field( $_POST['price_rounding'] ?? '1000' ),
				'currency_symbol'      => sanitize_text_field( $_POST['currency_symbol'] ?? 'تومان' ),
				'shop_title'           => sanitize_text_field( $_POST['shop_title'] ?? '' ),
				'shop_subtitle'        => sanitize_text_field( $_POST['shop_subtitle'] ?? '' ),
				'accent_color'         => sanitize_text_field( $_POST['accent_color'] ?? '#2563eb' ),
				'products_per_page'    => intval( $_POST['products_per_page'] ?? 16 ),
				'show_source_badge'    => ! empty( $_POST['show_source_badge'] ),
				'show_features_banner' => ! empty( $_POST['show_features_banner'] ),
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
				مدیریت فروشگاه مدرن، تعدیل قیمت و اسکرپر هوشمند
			</h1>

			<?php if ( $updated ) : ?>
				<div class="notice notice-success is-dismissible"><p><strong>تنظیمات با موفقیت ذخیره شد.</strong></p></div>
			<?php endif; ?>

			<!-- Hero Direct Link to Scraper Page -->
			<div style="background:linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color:#fff; border-radius:16px; padding:25px 30px; margin-bottom:25px; display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:20px; box-shadow:0 10px 25px rgba(0,0,0,0.12);">
				<div>
					<div style="display:flex; align-items:center; gap:10px; margin-bottom:8px;">
						<span style="font-size:1.6rem;">⚡</span>
						<h2 style="margin:0; font-size:1.4rem; font-weight:900; color:#fff;">پنل پیشرفته استخراج و همگام‌ساز (scraper4)</h2>
					</div>
					<p style="margin:0; color:#94a3b8; font-size:1rem; max-width:650px;">
						برای افزودن سایت جدید، تعریف دسته‌بندی‌ها، آغاز فرآیند استخراج و همگام‌سازی مستقیم با ووکامرس یا باسلام، وارد پنل اسکرپر شوید:
					</p>
				</div>
				<div style="display:flex; gap:12px; flex-wrap:wrap;">
					<a href="<?php echo esc_url( $scraper_embed_url ); ?>" class="button button-primary button-hero" style="background:#2563eb; border-color:#1d4ed8; font-weight:700;">
						ورود به پنل اسکرپر در وردپرس ➔
					</a>
					<a href="<?php echo esc_url( $scraper_direct_url ); ?>" target="_blank" class="button button-secondary button-hero" style="font-weight:700;">
						باز کردن در تب مستقل ↗
					</a>
				</div>
			</div>

			<!-- Quick stats -->
			<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:15px; margin-bottom:25px;">
				<div style="background:#fff; border:1px solid #cbd5e1; border-radius:12px; padding:18px; box-shadow:0 2px 4px rgba(0,0,0,0.04);">
					<div style="color:#64748b; font-size:0.9rem;">تعداد پروفایل‌های اسکرپر</div>
					<div style="font-size:1.8rem; font-weight:900; color:#0f172a; margin-top:5px;"><?php echo self::to_fa_num( count( $profiles_summary ) ); ?></div>
				</div>
				<div style="background:#fff; border:1px solid #cbd5e1; border-radius:12px; padding:18px; box-shadow:0 2px 4px rgba(0,0,0,0.04);">
					<div style="color:#64748b; font-size:0.9rem;">تعداد محصولات فعال پروفایل‌ها</div>
					<div style="font-size:1.8rem; font-weight:900; color:#0f172a; margin-top:5px;"><?php echo self::to_fa_num( count( $scraped_products ) ); ?></div>
				</div>
				<div style="background:#fff; border:1px solid #cbd5e1; border-radius:12px; padding:18px; box-shadow:0 2px 4px rgba(0,0,0,0.04);">
					<div style="color:#64748b; font-size:0.9rem;">نرخ سود / تعدیل قیمت</div>
					<div style="font-size:1.8rem; font-weight:900; color:#059669; margin-top:5px;">+<?php echo self::to_fa_num( $opts['price_markup_percent'] ); ?>٪</div>
				</div>
				<div style="background:#fff; border:1px solid #cbd5e1; border-radius:12px; padding:18px; box-shadow:0 2px 4px rgba(0,0,0,0.04);">
					<div style="color:#64748b; font-size:0.9rem;">وضعیت جایگزینی ویترین ووکامرس</div>
					<div style="font-size:1.3rem; font-weight:800; color:<?php echo $opts['enable_shop_takeover'] ? '#2563eb' : '#94a3b8'; ?>; margin-top:10px;">
						<?php echo $opts['enable_shop_takeover'] ? 'فعال (ظاهر جذاب)' : 'غیرفعال'; ?>
					</div>
				</div>
			</div>

			<!-- Profiles Summary Table -->
			<?php if ( ! empty( $profiles_summary ) ) : ?>
				<div style="background:#fff; border:1px solid #cbd5e1; border-radius:14px; padding:20px; margin-bottom:25px; box-shadow:0 2px 8px rgba(0,0,0,0.04);">
					<h3 style="margin-top:0; font-weight:800; color:#1e293b;">📂 وضعیت پروفایل‌های ثبت‌شده در اسکرپر</h3>
					<table class="wp-list-table widefat fixed striped">
						<thead>
							<tr>
								<th>نام پروفایل</th>
								<th>آدرس مبدأ</th>
								<th>تعداد محصولات استخراج‌شده</th>
								<th>عملیات</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $profiles_summary as $prof ) : ?>
								<tr>
									<td style="font-weight:700;"><?php echo esc_html( $prof['name'] ); ?></td>
									<td><a href="<?php echo esc_url( $prof['url'] ); ?>" target="_blank" style="direction:ltr; display:inline-block;"><?php echo esc_html( $prof['url'] ); ?></a></td>
									<td><span class="badge" style="background:#e0f2fe; color:#0369a1; padding:3px 10px; border-radius:12px; font-weight:700;"><?php echo self::to_fa_num( $prof['count'] ); ?> محصول</span></td>
									<td>
										<a href="<?php echo esc_url( $scraper_embed_url ); ?>" class="button button-small">مدیریت در اسکرپر</a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			<?php endif; ?>

			<!-- Direct sync to WooCommerce box -->
			<div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:14px; padding:22px; margin-bottom:30px; box-shadow:0 2px 6px rgba(0,0,0,0.03);">
				<h3 style="margin-top:0; color:#166534; display:flex; align-items:center; gap:8px; font-weight:800;">
					<span>🔄</span>
					درون‌ریزی و قرار دادن محصولات پروفایل‌ها بجای محصولات ووکامرس
				</h3>
				<p style="color:#15803d; font-size:0.95rem; margin-bottom:18px; line-height:1.7;">
					با فشردن این دکمه، تمامی محصولات موجود در پروفایل‌ها به همراه قیمت‌های تعدیل‌شده، مشخصات، دسته‌بندی‌ها و تصاویر، مستقیماً به جدول محصولات اصلی ووکامرس (<code>post_type => product</code>) اضافه و به‌روزرسانی می‌شوند تا با درگاه‌های پرداخت، فاکتور و افزونه‌های جانبی هماهنگ شوند.
				</p>
				<div style="display:flex; align-items:center; gap:15px; flex-wrap:wrap;">
					<button type="button" id="btnSyncToWoo" class="button button-primary button-hero" style="background:#16a34a; border-color:#15803d; font-weight:700;">
						همگام‌سازی و درج مستقیم در محصولات ووکامرس
					</button>
					<span id="syncWooStatus" style="font-weight:700; color:#166534; font-size:0.95rem;"></span>
				</div>
			</div>

			<!-- Settings form -->
			<form method="post" action="" style="background:#fff; border:1px solid #cbd5e1; border-radius:14px; padding:25px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
				<?php wp_nonce_field( 'scraper_shop_settings_action', 'scraper_shop_settings_nonce' ); ?>
				
				<h2 style="border-bottom:2px solid #f1f5f9; padding-bottom:14px; margin-top:0; font-weight:800; color:#0f172a;">
					⚙️ تنظیمات ظاهر فروشگاه، تعدیل قیمت و رفتار ویترین
				</h2>

				<table class="form-table">
					<tr>
						<th scope="row"><label for="enable_shop_takeover">جایگزینی ظاهر ووکامرس</label></th>
						<td>
							<label>
								<input type="checkbox" name="enable_shop_takeover" id="enable_shop_takeover" value="1" <?php checked( $opts['enable_shop_takeover'] ); ?>>
								<strong>فعال‌سازی ظاهر فوق‌العاده جذاب و مدرن روی صفحه فروشگاه سایت</strong>
							</label>
							<p class="description">با فعال بودن این گزینه، صفحه فروشگاه ووکامرس با لایوت شیک و مدرن، فیلتر آنی، سبد خرید کشویی و جستجوی لحظه‌ای جایگزین می‌شود. همچنین می‌توانید از شورت‌کد <code>[scraped_shop]</code> در هر برگه دلخواهی استفاده کنید.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="price_markup_percent">درصد تعدیل / سود قیمت (٪)</label></th>
						<td>
							<input type="number" step="0.5" name="price_markup_percent" id="price_markup_percent" value="<?php echo esc_attr( $opts['price_markup_percent'] ); ?>" class="regular-text" style="width:120px;">
							<span>درصد افزایش نسبت به قیمت اولیه استخراج‌شده در پروفایل</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="price_fixed_add">مبلغ ثابت اضافه شونده</label></th>
						<td>
							<input type="number" name="price_fixed_add" id="price_fixed_add" value="<?php echo esc_attr( $opts['price_fixed_add'] ); ?>" class="regular-text" style="width:160px;">
							<span>مبلغی که به صورت ثابت به قیمت نهایی هر کالا افزوده می‌شود (اختیاری)</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="price_rounding">قانون رند کردن قیمت</label></th>
						<td>
							<select name="price_rounding" id="price_rounding">
								<option value="none" <?php selected( $opts['price_rounding'], 'none' ); ?>>بدون رند کردن</option>
								<option value="1000" <?php selected( $opts['price_rounding'], '1000' ); ?>>رند به نزدیک‌ترین ۱,۰۰۰ تومان</option>
								<option value="10000" <?php selected( $opts['price_rounding'], '10000' ); ?>>رند به نزدیک‌ترین ۱۰,۰۰۰ تومان</option>
							</select>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="currency_symbol">واحد پولی</label></th>
						<td>
							<input type="text" name="currency_symbol" id="currency_symbol" value="<?php echo esc_attr( $opts['currency_symbol'] ); ?>" class="regular-text" style="width:120px;">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="shop_title">عنوان فروشگاه در ویترین</label></th>
						<td>
							<input type="text" name="shop_title" id="shop_title" value="<?php echo esc_attr( $opts['shop_title'] ); ?>" class="large-text">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="shop_subtitle">شعار / زیرعنوان فروشگاه</label></th>
						<td>
							<input type="text" name="shop_subtitle" id="shop_subtitle" value="<?php echo esc_attr( $opts['shop_subtitle'] ); ?>" class="large-text">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="accent_color">رنگ سازمانی و دکمه‌ها</label></th>
						<td>
							<input type="color" name="accent_color" id="accent_color" value="<?php echo esc_attr( $opts['accent_color'] ); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="show_features_banner">بنر مزایای فروشگاه در هیرو</label></th>
						<td>
							<label>
								<input type="checkbox" name="show_features_banner" id="show_features_banner" value="1" <?php checked( $opts['show_features_banner'] ); ?>>
								نمایش نشان‌های اعتماد (ارسال سریع، ضمانت اصالت، پشتیبانی) در بالای فروشگاه
							</label>
						</td>
					</tr>
				</table>

				<p class="submit">
					<input type="submit" name="scraper_shop_save" id="submit" class="button button-primary button-large" value="ذخیره تنظیمات">
				</p>
			</form>
		</div>

		<script>
			jQuery(document).ready(function($){
				$('#btnSyncToWoo').on('click', function(){
					var $btn = $(this);
					var $status = $('#syncWooStatus');
					$btn.prop('disabled', true).text('در حال همگام‌سازی و درج در ووکامرس...');
					$status.text('');

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
