<?php
/**
 * Plugin Name: Scraper & Auto Shop Pro
 * Plugin URI: https://github.com/fazilatma/amphp
 * Description: افزونه جامع اسکرپر، استخراج هوشمند محصولات، همگام‌ساز ووکامرس و باسلام، همراه با ظاهر مدرن و جذاب برای فروشگاه، تعدیل قیمت خودکار و جایگزینی مستقیم محصولات ووکامرس
 * Version: 11.0.0
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
			'shop_title'           => 'فروشگاه مدرن آنلاین',
			'shop_subtitle'        => 'تنوع بی‌نظیر محصولات با بهترین کیفیت و تضمین بهترین قیمت',
			'accent_color'         => '#2563eb',
			'products_per_page'    => 16,
			'show_source_badge'    => true,
			'auto_sync_woo'        => false,
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

		// Shortcode for modern shop
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
	 * Load profiles and extracted products from scraper storage.
	 *
	 * @return array
	 */
	public static function get_all_scraped_products() {
		$products = array();
		$settings = self::get_settings();
		$seen     = array();

		// 1. Check profiles.json
		$profiles_file = plugin_dir_path( __FILE__ ) . 'profiles.json';
		if ( file_exists( $profiles_file ) ) {
			$json = @file_get_contents( $profiles_file );
			$data = @json_decode( $json, true );
			if ( is_array( $data ) ) {
				foreach ( $data as $p_key => $p_item ) {
					$profile_name = $p_item['name'] ?? $p_key;
					$raw_prods    = $p_item['products'] ?? array();
					if ( is_array( $raw_prods ) ) {
						foreach ( $raw_prods as $prod ) {
							if ( is_array( $prod ) ) {
								$title = trim( $prod['title'] ?? $prod['name'] ?? '' );
								if ( '' !== $title && ! isset( $seen[ $title ] ) ) {
									$seen[ $title ] = true;
									$price_calc     = self::calculate_price( $prod['price'] ?? 0, $settings );
									$products[]     = array(
										'id'             => md5( $title . ( $prod['link'] ?? '' ) ),
										'title'          => $title,
										'original_price' => $price_calc['original'],
										'price'          => $price_calc['adjusted'],
										'price_formatted'=> $price_calc['formatted'],
										'image'          => $prod['image'] ?? $prod['img'] ?? '',
										'category'       => $prod['category'] ?? $prod['cat'] ?? 'عمومی',
										'description'    => $prod['description'] ?? $prod['desc'] ?? '',
										'link'           => $prod['link'] ?? $prod['url'] ?? '',
										'profile'        => $profile_name,
										'in_stock'       => true,
									);
								}
							}
						}
					}
				}
			}
		}

		// 2. Check woo_products_temp.json
		$woo_temp = plugin_dir_path( __FILE__ ) . 'woo_products_temp.json';
		if ( file_exists( $woo_temp ) ) {
			$json = @file_get_contents( $woo_temp );
			$data = @json_decode( $json, true );
			if ( is_array( $data ) ) {
				foreach ( $data as $prod ) {
					if ( is_array( $prod ) ) {
						$title = trim( $prod['title'] ?? $prod['name'] ?? '' );
						if ( '' !== $title && ! isset( $seen[ $title ] ) ) {
							$seen[ $title ] = true;
							$price_calc     = self::calculate_price( $prod['price'] ?? 0, $settings );
							$products[]     = array(
								'id'             => md5( $title . ( $prod['link'] ?? '' ) ),
								'title'          => $title,
								'original_price' => $price_calc['original'],
								'price'          => $price_calc['adjusted'],
								'price_formatted'=> $price_calc['formatted'],
								'image'          => $prod['image'] ?? $prod['img'] ?? '',
								'category'       => $prod['category'] ?? $prod['cat'] ?? 'عمومی',
								'description'    => $prod['description'] ?? $prod['desc'] ?? '',
								'link'           => $prod['link'] ?? $prod['url'] ?? '',
								'profile'        => 'ووکامرس / استخراج‌شده',
								'in_stock'       => true,
							);
						}
					}
				}
			}
		}

		// 3. Fallback sample products if none extracted yet
		if ( empty( $products ) ) {
			$samples = array(
				array(
					'title'    => 'گوشی هوشمند سامسونگ مدل Galaxy A54',
					'price'    => 18500000,
					'image'    => 'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=600',
					'category' => 'کالای دیجیتال',
					'desc'     => 'صفحه نمایش Super AMOLED با نرخ نوسازی ۱۲۰ هرتز، دوربین ۵۰ مگاپیکسلی و باتری ۵۰۰۰ میلی‌آمپر ساعت.',
				),
				array(
					'title'    => 'هدفون بی‌سیم انکر مدل Soundcore Life Q30',
					'price'    => 3900000,
					'image'    => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600',
					'category' => 'لوازم صوتی',
					'desc'     => 'کیفیت صدای Hi-Res، فناوری پیشرفته حذف نویز فعال نویز کنسلینگ (ANC) و شارژدهی تا ۴۰ ساعت.',
				),
				array(
					'title'    => 'ساعت هوشمند شیائومی مدل Watch S1 Active',
					'price'    => 4800000,
					'image'    => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600',
					'category' => 'گجت پوشیدنی',
					'desc'     => 'صفحه نمایش ۱.۴۳ اینچی AMOLED، بیش از ۱۱۷ حالت ورزشی، ضدآب تا عمق ۵۰ متر و قابلیت مکالمه بلوتوثی.',
				),
				array(
					'title'    => 'اسپیکر قابل حمل جی‌بی‌ال مدل Flip 6',
					'price'    => 5400000,
					'image'    => 'https://images.unsplash.com/photo-1545454675-3531b543be5d?w=600',
					'category' => 'لوازم صوتی',
					'desc'     => 'صدای قدرتمند با بیس شفاف، استاندارد ضدآب و گردوغبار IP67 و ۱۲ ساعت پخش مداوم موسیقی.',
				),
			);

			foreach ( $samples as $s ) {
				$calc = self::calculate_price( $s['price'], $settings );
				$products[] = array(
					'id'             => md5( $s['title'] ),
					'title'          => $s['title'],
					'original_price' => $calc['original'],
					'price'          => $calc['adjusted'],
					'price_formatted'=> $calc['formatted'],
					'image'          => $s['image'],
					'category'       => $s['category'],
					'description'    => $s['desc'],
					'link'           => '#',
					'profile'        => 'محصول پیش‌نمایش اسکرپر',
					'in_stock'       => true,
				);
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

		foreach ( $products as $p ) {
			// Find existing product by title or meta
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
				// Set WooCommerce meta
				update_post_meta( $prod_id, '_price', $p['price'] );
				update_post_meta( $prod_id, '_regular_price', $p['price'] );
				update_post_meta( $prod_id, '_manage_stock', 'no' );
				update_post_meta( $prod_id, '_stock_status', 'instock' );
				update_post_meta( $prod_id, '_scraped_source_profile', $p['profile'] );
				update_post_meta( $prod_id, '_scraped_original_price', $p['original_price'] );
				if ( ! empty( $p['image'] ) ) {
					update_post_meta( $prod_id, '_scraped_image_url', $p['image'] );
				}

				// Assign category
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

		// Check if it's WooCommerce shop or taxonomy archive
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
		// Embed modern Vazirmatn font if not present
		wp_register_style( 'vazirmatn-font', 'https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css', array(), '33.003' );
		wp_enqueue_style( 'vazirmatn-font' );
	}

	/**
	 * Render Standalone Modern Shop Page.
	 */
	public static function render_standalone_shop_page() {
		get_header();
		echo '<div class="scraper-shop-wrap" style="width:100%; max-width:1440px; margin:0 auto; padding:20px;">';
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

		// Collect unique categories
		$categories = array();
		foreach ( $products as $p ) {
			$cat = $p['category'] ?: 'عمومی';
			$categories[ $cat ] = ( $categories[ $cat ] ?? 0 ) + 1;
		}

		ob_start();
		?>
		<!-- Scraper & Auto Shop Modern Storefront -->
		<style>
			:root {
				--s-primary: <?php echo esc_attr( $settings['accent_color'] ); ?>;
				--s-primary-hover: #1d4ed8;
				--s-bg-card: #ffffff;
				--s-border: #e2e8f0;
				--s-text-main: #0f172a;
				--s-text-muted: #64748b;
			}
			.modern-shop-container {
				font-family: Vazirmatn, system-ui, -apple-system, sans-serif;
				direction: rtl;
				text-align: right;
				margin: 30px auto;
				color: var(--s-text-main);
			}
			.modern-shop-hero {
				background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
				border-radius: 24px;
				padding: 50px 30px;
				color: #fff;
				text-align: center;
				margin-bottom: 40px;
				box-shadow: 0 20px 40px rgba(0,0,0,0.12);
				position: relative;
				overflow: hidden;
			}
			.modern-shop-hero::before {
				content: '';
				position: absolute;
				top: -50%;
				right: -20%;
				width: 350px;
				height: 350px;
				background: radial-gradient(circle, var(--s-primary) 0%, transparent 70%);
				opacity: 0.35;
			}
			.modern-shop-hero h1 {
				font-size: 2.5rem;
				font-weight: 900;
				margin-bottom: 12px;
				color: #fff;
			}
			.modern-shop-hero p {
				font-size: 1.1rem;
				color: #94a3b8;
				max-width: 650px;
				margin: 0 auto 25px;
			}
			.shop-search-bar {
				max-width: 520px;
				margin: 0 auto;
				position: relative;
			}
			.shop-search-bar input {
				width: 100%;
				padding: 16px 24px 16px 50px;
				border-radius: 50px;
				border: 1px solid rgba(255,255,255,0.2);
				background: rgba(255,255,255,0.1);
				backdrop-filter: blur(10px);
				color: #fff;
				font-size: 1rem;
				outline: none;
				transition: all 0.3s;
			}
			.shop-search-bar input:focus {
				background: rgba(255,255,255,0.2);
				border-color: #fff;
				box-shadow: 0 0 20px rgba(255,255,255,0.2);
			}
			.shop-search-bar input::placeholder { color: #cbd5e1; }
			.shop-search-bar svg {
				position: absolute;
				left: 18px;
				top: 50%;
				transform: translateY(-50%);
				width: 22px;
				height: 22px;
				fill: #94a3b8;
			}
			.category-pills {
				display: flex;
				flex-wrap: wrap;
				gap: 10px;
				justify-content: center;
				margin-bottom: 35px;
			}
			.category-pill {
				padding: 10px 20px;
				border-radius: 30px;
				background: #f1f5f9;
				color: #475569;
				font-weight: 600;
				cursor: pointer;
				border: 1px solid #e2e8f0;
				transition: all 0.25s;
				font-size: 0.95rem;
			}
			.category-pill.active, .category-pill:hover {
				background: var(--s-primary);
				color: #fff;
				border-color: var(--s-primary);
				box-shadow: 0 6px 16px rgba(37,99,235,0.25);
			}
			.products-grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
				gap: 25px;
			}
			.product-card {
				background: var(--s-bg-card);
				border: 1px solid var(--s-border);
				border-radius: 18px;
				overflow: hidden;
				display: flex;
				flex-direction: column;
				transition: transform 0.3s, box-shadow 0.3s;
				position: relative;
			}
			.product-card:hover {
				transform: translateY(-6px);
				box-shadow: 0 16px 30px rgba(0,0,0,0.08);
			}
			.card-img-wrap {
				position: relative;
				width: 100%;
				padding-top: 100%;
				background: #f8fafc;
				overflow: hidden;
			}
			.card-img-wrap img {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				object-fit: cover;
				transition: transform 0.4s ease;
			}
			.product-card:hover .card-img-wrap img {
				transform: scale(1.06);
			}
			.card-badge {
				position: absolute;
				top: 12px;
				right: 12px;
				background: #10b981;
				color: #fff;
				font-size: 0.75rem;
				font-weight: 700;
				padding: 4px 10px;
				border-radius: 12px;
				z-index: 2;
			}
			.card-profile-tag {
				position: absolute;
				bottom: 12px;
				right: 12px;
				background: rgba(15,23,42,0.75);
				backdrop-filter: blur(4px);
				color: #fff;
				font-size: 0.7rem;
				padding: 3px 8px;
				border-radius: 8px;
			}
			.card-content {
				padding: 20px;
				display: flex;
				flex-direction: column;
				flex-grow: 1;
			}
			.card-cat {
				font-size: 0.8rem;
				color: var(--s-text-muted);
				margin-bottom: 6px;
			}
			.card-title {
				font-size: 1.05rem;
				font-weight: 700;
				line-height: 1.5;
				margin-bottom: 12px;
				height: 48px;
				overflow: hidden;
				display: -webkit-box;
				-webkit-line-clamp: 2;
				-webkit-box-orient: vertical;
			}
			.card-pricing {
				margin-top: auto;
				display: flex;
				align-items: baseline;
				gap: 8px;
				margin-bottom: 16px;
			}
			.card-price {
				font-size: 1.25rem;
				font-weight: 800;
				color: #059669;
			}
			.card-old-price {
				font-size: 0.85rem;
				color: #94a3b8;
				text-decoration: line-through;
			}
			.card-btn {
				background: var(--s-primary);
				color: #fff;
				border: none;
				border-radius: 10px;
				padding: 12px;
				font-weight: 700;
				cursor: pointer;
				text-align: center;
				width: 100%;
				transition: background 0.2s;
				text-decoration: none;
				display: block;
			}
			.card-btn:hover {
				background: var(--s-primary-hover);
				color: #fff;
			}
			/* Quick view modal */
			.modal-overlay {
				position: fixed;
				inset: 0;
				background: rgba(0,0,0,0.6);
				backdrop-filter: blur(5px);
				display: none;
				align-items: center;
				justify-content: center;
				z-index: 99999;
				padding: 20px;
			}
			.modal-box {
				background: #fff;
				border-radius: 20px;
				max-width: 650px;
				width: 100%;
				overflow: hidden;
				box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
				position: relative;
				animation: modalSlide 0.3s ease;
			}
			@keyframes modalSlide {
				from { transform: translateY(30px); opacity: 0; }
				to { transform: translateY(0); opacity: 1; }
			}
			.modal-close {
				position: absolute;
				top: 15px;
				left: 15px;
				font-size: 1.5rem;
				cursor: pointer;
				width: 36px;
				height: 36px;
				background: #f1f5f9;
				border-radius: 50%;
				display: flex;
				align-items: center;
				justify-content: center;
				color: #475569;
			}
			.modal-inner {
				display: flex;
				flex-direction: column;
				max-height: 85vh;
				overflow-y: auto;
			}
			@media(min-width: 640px) {
				.modal-inner { flex-direction: row; }
				.modal-img-col { width: 45%; }
				.modal-info-col { width: 55%; padding: 30px; }
			}
			.modal-img-col img { width: 100%; height: 100%; object-fit: cover; }
			.modal-info-col { padding: 20px; }
		</style>

		<div class="modern-shop-container" id="modernShopApp">
			<!-- Hero Header -->
			<div class="modern-shop-hero">
				<h1><?php echo esc_html( $settings['shop_title'] ); ?></h1>
				<p><?php echo esc_html( $settings['shop_subtitle'] ); ?></p>
				<div class="shop-search-bar">
					<input type="text" id="shopSearchInput" placeholder="جستجو در میان تمام محصولات...">
					<svg viewBox="0 0 24 24"><path d="M10 18a7.952 7.952 0 0 0 4.897-1.688l4.396 4.396 1.414-1.414-4.396-4.396A7.952 7.952 0 0 0 18 10c0-4.411-3.589-8-8-8s-8 3.589-8 8 3.589 8 8 8zm0-14c3.309 0 6 2.691 6 6s-2.691 6-6 6-6-2.691-6-6 2.691-6 6-6z"/></svg>
				</div>
			</div>

			<!-- Categories -->
			<div class="category-pills" id="categoryPills">
				<div class="category-pill active" data-cat="all">همه محصولات (<?php echo self::to_fa_num( count( $products ) ); ?>)</div>
				<?php foreach ( $categories as $cat_name => $cat_count ) : ?>
					<div class="category-pill" data-cat="<?php echo esc_attr( $cat_name ); ?>">
						<?php echo esc_html( $cat_name ); ?> (<?php echo self::to_fa_num( $cat_count ); ?>)
					</div>
				<?php endforeach; ?>
			</div>

			<!-- Product Grid -->
			<div class="products-grid" id="productsGrid">
				<?php foreach ( $products as $p ) : ?>
					<div class="product-card" data-cat="<?php echo esc_attr( $p['category'] ); ?>" data-title="<?php echo esc_attr( mb_strtolower( $p['title'] ) ); ?>">
						<div class="card-img-wrap">
							<span class="card-badge">موجود در انبار</span>
							<?php if ( ! empty( $settings['show_source_badge'] ) && ! empty( $p['profile'] ) ) : ?>
								<span class="card-profile-tag"><?php echo esc_html( $p['profile'] ); ?></span>
							<?php endif; ?>
							<?php if ( ! empty( $p['image'] ) ) : ?>
								<img src="<?php echo esc_url( $p['image'] ); ?>" alt="<?php echo esc_attr( $p['title'] ); ?>" loading="lazy">
							<?php else : ?>
								<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;font-size:3rem;color:#cbd5e1;">🛍️</div>
							<?php endif; ?>
						</div>
						<div class="card-content">
							<div class="card-cat"><?php echo esc_html( $p['category'] ); ?></div>
							<div class="card-title" title="<?php echo esc_attr( $p['title'] ); ?>"><?php echo esc_html( $p['title'] ); ?></div>
							<div class="card-pricing">
								<div class="card-price"><?php echo esc_html( $p['price_formatted'] ); ?></div>
								<?php if ( $p['original_price'] > 0 && $p['original_price'] < $p['price'] ) : ?>
									<div class="card-old-price"><?php echo self::to_fa_num( number_format( $p['original_price'] ) ); ?></div>
								<?php endif; ?>
							</div>
							<button type="button" class="card-btn open-quick-view" 
								data-title="<?php echo esc_attr( $p['title'] ); ?>"
								data-price="<?php echo esc_attr( $p['price_formatted'] ); ?>"
								data-img="<?php echo esc_url( $p['image'] ); ?>"
								data-cat="<?php echo esc_attr( $p['category'] ); ?>"
								data-desc="<?php echo esc_attr( $p['description'] ); ?>"
								data-link="<?php echo esc_url( $p['link'] ); ?>">
								مشاهده و خرید
							</button>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>

		<!-- Quick View Modal -->
		<div class="modal-overlay" id="quickViewModal">
			<div class="modal-box">
				<div class="modal-close" id="modalCloseBtn">&times;</div>
				<div class="modal-inner">
					<div class="modal-img-col">
						<img id="mImg" src="" alt="product">
					</div>
					<div class="modal-info-col">
						<div id="mCat" style="font-size:0.85rem; color:#64748b; margin-bottom:8px;"></div>
						<h2 id="mTitle" style="font-size:1.3rem; font-weight:800; margin-bottom:15px; line-height:1.4;"></h2>
						<div id="mPrice" style="font-size:1.5rem; font-weight:900; color:#059669; margin-bottom:20px;"></div>
						<p id="mDesc" style="font-size:0.95rem; color:#475569; line-height:1.7; margin-bottom:25px;"></p>
						<a id="mActionBtn" href="#" target="_blank" class="card-btn" style="text-align:center;">ثبت سفارش و خرید</a>
					</div>
				</div>
			</div>
		</div>

		<script>
			document.addEventListener('DOMContentLoaded', function(){
				const search = document.getElementById('shopSearchInput');
				const pills = document.querySelectorAll('.category-pill');
				const cards = document.querySelectorAll('.product-card');

				let activeCat = 'all';

				function filterProducts(){
					const query = (search ? search.value : '').toLowerCase().trim();
					cards.forEach(card => {
						const cardCat = card.getAttribute('data-cat') || '';
						const cardTitle = card.getAttribute('data-title') || '';
						const matchCat = (activeCat === 'all' || cardCat === activeCat);
						const matchQuery = (!query || cardTitle.includes(query));
						if(matchCat && matchQuery){
							card.style.display = 'flex';
						} else {
							card.style.display = 'none';
						}
					});
				}

				if(search){
					search.addEventListener('input', filterProducts);
				}

				pills.forEach(pill => {
					pill.addEventListener('click', function(){
						pills.forEach(p => p.classList.remove('active'));
						this.classList.add('active');
						activeCat = this.getAttribute('data-cat');
						filterProducts();
					});
				});

				// Quick view modal
				const modal = document.getElementById('quickViewModal');
				const mClose = document.getElementById('modalCloseBtn');
				const mImg = document.getElementById('mImg');
				const mTitle = document.getElementById('mTitle');
				const mCat = document.getElementById('mCat');
				const mPrice = document.getElementById('mPrice');
				const mDesc = document.getElementById('mDesc');
				const mAction = document.getElementById('mActionBtn');

				document.querySelectorAll('.open-quick-view').forEach(btn => {
					btn.addEventListener('click', function(){
						mImg.src = this.getAttribute('data-img') || '';
						mTitle.textContent = this.getAttribute('data-title') || '';
						mCat.textContent = this.getAttribute('data-cat') || '';
						mPrice.textContent = this.getAttribute('data-price') || '';
						mDesc.textContent = this.getAttribute('data-desc') || 'توضیحات تکمیلی برای این محصول ثبت نشده است.';
						const link = this.getAttribute('data-link') || '#';
						mAction.href = link;
						modal.style.display = 'flex';
					});
				});

				if(mClose){
					mClose.addEventListener('click', () => { modal.style.display = 'none'; });
				}
				if(modal){
					modal.addEventListener('click', function(e){
						if(e.target === modal) modal.style.display = 'none';
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
			);
			update_option( self::OPTION_NAME, $new_settings );
			$updated = true;
		}

		$opts = self::get_settings();
		$scraped_products = self::get_all_scraped_products();
		?>
		<div class="wrap" style="direction:rtl; text-align:right; font-family:system-ui, -apple-system, sans-serif;">
			<h1 style="display:flex; align-items:center; gap:10px; font-weight:800; margin-bottom:20px;">
				<span>🛍️</span>
				مدیریت فروشگاه، ظاهر مدرن و اسکرپر هوشمند
			</h1>

			<?php if ( $updated ) : ?>
				<div class="notice notice-success is-dismissible"><p><strong>تنظیمات با موفقیت ذخیره شد.</strong></p></div>
			<?php endif; ?>

			<!-- Quick stats -->
			<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:15px; margin-bottom:25px;">
				<div style="background:#fff; border:1px solid #cbd5e1; border-radius:12px; padding:18px; box-shadow:0 2px 4px rgba(0,0,0,0.04);">
					<div style="color:#64748b; font-size:0.9rem;">تعداد کل محصولات آمادهٔ نمایش</div>
					<div style="font-size:1.8rem; font-weight:900; color:#0f172a; margin-top:5px;"><?php echo self::to_fa_num( count( $scraped_products ) ); ?></div>
				</div>
				<div style="background:#fff; border:1px solid #cbd5e1; border-radius:12px; padding:18px; box-shadow:0 2px 4px rgba(0,0,0,0.04);">
					<div style="color:#64748b; font-size:0.9rem;">تعدیل قیمت فعال</div>
					<div style="font-size:1.8rem; font-weight:900; color:#059669; margin-top:5px;">+<?php echo self::to_fa_num( $opts['price_markup_percent'] ); ?>٪</div>
				</div>
				<div style="background:#fff; border:1px solid #cbd5e1; border-radius:12px; padding:18px; box-shadow:0 2px 4px rgba(0,0,0,0.04);">
					<div style="color:#64748b; font-size:0.9rem;">وضعیت جایگزینی ویترین ووکامرس</div>
					<div style="font-size:1.3rem; font-weight:800; color:<?php echo $opts['enable_shop_takeover'] ? '#2563eb' : '#94a3b8'; ?>; margin-top:10px;">
						<?php echo $opts['enable_shop_takeover'] ? 'فعال (ظاهر مدرن)' : 'غیرفعال'; ?>
					</div>
				</div>
			</div>

			<!-- Direct sync to WooCommerce box -->
			<div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:12px; padding:20px; margin-bottom:30px;">
				<h3 style="margin-top:0; color:#166534; display:flex; align-items:center; gap:8px;">
					<span>🔄</span>
					همگام‌سازی و قرار دادن محصولات بجای محصولات ووکامرس
				</h3>
				<p style="color:#15803d; font-size:0.95rem; margin-bottom:15px;">
					با فشردن این دکمه، تمام محصولات استخراج‌شده به همراه قیمت‌های تعدیل‌شده، تصاویر، دسته‌بندی و مشخصات، مستقیماً در دیتابیس ووکامرس درج می‌شوند تا به عنوان محصولات رسمی فروشگاه قرار گیرند.
				</p>
				<button type="button" id="btnSyncToWoo" class="button button-primary button-hero" style="background:#16a34a; border-color:#15803d;">
					درون‌ریزی و جایگزینی مستقیم در محصولات ووکامرس
				</button>
				<span id="syncWooStatus" style="margin-right:15px; font-weight:700; color:#166534;"></span>
			</div>

			<!-- Settings form -->
			<form method="post" action="" style="background:#fff; border:1px solid #cbd5e1; border-radius:14px; padding:25px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
				<?php wp_nonce_field( 'scraper_shop_settings_action', 'scraper_shop_settings_nonce' ); ?>
				
				<h2 style="border-bottom:2px solid #f1f5f9; padding-bottom:12px; margin-top:0; font-weight:800;">
					⚙️ تنظیمات ظاهر، تعدیل قیمت و رفتار فروشگاه
				</h2>

				<table class="form-table">
					<tr>
						<th scope="row"><label for="enable_shop_takeover">جایگزینی ظاهر ووکامرس</label></th>
						<td>
							<label>
								<input type="checkbox" name="enable_shop_takeover" id="enable_shop_takeover" value="1" <?php checked( $opts['enable_shop_takeover'] ); ?>>
								فعال‌سازی ظاهر فوق‌العاده جذاب و مدرن روی صفحه فروشگاه سایت
							</label>
							<p class="description">با فعال بودن این گزینه، صفحه فروشگاه ووکامرس مستقیماً با لایوت جذاب و مدرن جایگزین می‌شود. همچنین می‌توانید از شورت‌کد <code>[scraped_shop]</code> در هر برگه دلخواهی استفاده کنید.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="price_markup_percent">درصد تعدیل / سود قیمت (٪)</label></th>
						<td>
							<input type="number" step="0.5" name="price_markup_percent" id="price_markup_percent" value="<?php echo esc_attr( $opts['price_markup_percent'] ); ?>" class="regular-text" style="width:120px;">
							<span>درصد افزایش نسبت به قیمت استخراج‌شده اولیه</span>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="price_fixed_add">مبلغ ثابت اضافه شونده</label></th>
						<td>
							<input type="number" name="price_fixed_add" id="price_fixed_add" value="<?php echo esc_attr( $opts['price_fixed_add'] ); ?>" class="regular-text" style="width:160px;">
							<span>مبلغی که به صورت ثابت به هر محصول اضافه می‌شود (اختیاری)</span>
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
				</table>

				<p class="submit">
					<input type="submit" name="scraper_shop_save" id="submit" class="button button-primary button-large" value="ذخیره تغییرات">
				</p>
			</form>
		</div>

		<script>
			jQuery(document).ready(function($){
				$('#btnSyncToWoo').on('click', function(){
					var $btn = $(this);
					var $status = $('#syncWooStatus');
					$btn.prop('disabled', true).text('در حال همگام‌سازی و ارسال به محصولات ووکامرس...');
					$status.text('');

					$.ajax({
						url: ajaxurl,
						type: 'POST',
						data: {
							action: 'scraper_sync_to_woo',
							nonce: '<?php echo wp_create_nonce( "scraper_shop_admin_nonce" ); ?>'
						},
						success: function(res){
							$btn.prop('disabled', false).text('درون‌ریزی و جایگزینی مستقیم در محصولات ووکامرس');
							if(res.success && res.data){
								$status.html('✅ همگام‌سازی کامل شد: ' + res.data.created + ' محصول جدید درج شد و ' + res.data.updated + ' محصول به‌روزرسانی شد.');
							} else {
								$status.html('❌ خطا در همگام‌سازی: ' + (res.data || 'نامشخص'));
							}
						},
						error: function(){
							$btn.prop('disabled', false).text('درون‌ریزی و جایگزینی مستقیم در محصولات ووکامرس');
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
				<h1 style="font-weight:800;">⚡ پنل کامل اسکرپر هوشمند (scraper4)</h1>
				<a href="<?php echo esc_url( $scraper_url ); ?>" target="_blank" class="button button-secondary button-large">
					باز کردن در پنجرهٔ مجزا ↗
				</a>
			</div>
			<p style="color:#64748b;">می‌توانید تمامی عملیات استخراج، مدیریت پروفایل‌ها، صف و ارتباط با باسلام/ووکامرس را مستقیماً از فریم زیر مدیریت نمایید:</p>
			<iframe src="<?php echo esc_url( $scraper_url ); ?>" style="width:100%; height:820px; border:1px solid #cbd5e1; border-radius:12px; background:#fff;"></iframe>
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
