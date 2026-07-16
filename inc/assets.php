<?php
/**
 * Theme assets.
 *
 * @package MausWP
 */

declare(strict_types=1);

/**
 * Enqueue frontend assets.
 */
function mauswp_needs_swiper(): bool {
	if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return true;
	}

	$swiper_blocks = [
		'acf/mauswp-product-showcase',
		'acf/mauswp-features-strip',
	];

	foreach ( $swiper_blocks as $block_name ) {
		if ( has_block( $block_name ) ) {
			return true;
		}
	}

	return false;
}

function mauswp_enqueue_assets(): void {
	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();

	$needs_swiper = mauswp_needs_swiper();

	$style_rel_path = '/assets/dist/app.css';
	$style_abs_path = $theme_path . $style_rel_path;

	if ( file_exists( $style_abs_path ) ) {
		$style_deps = [];

		if ( $needs_swiper ) {
			$swiper_style_rel_path = '/assets/dist/vendor/swiper/swiper-bundle.min.css';
			$swiper_style_abs_path = $theme_path . $swiper_style_rel_path;

			if ( file_exists( $swiper_style_abs_path ) ) {
				wp_enqueue_style(
					'mauswp-swiper',
					$theme_uri . $swiper_style_rel_path,
					[],
					(string) filemtime( $swiper_style_abs_path )
				);
				$style_deps[] = 'mauswp-swiper';
			}
		}

		wp_enqueue_style(
			'mauswp-app',
			$theme_uri . $style_rel_path,
			$style_deps,
			(string) filemtime( $style_abs_path )
		);
	}

	$script_deps = [];

	if ( $needs_swiper ) {
		$swiper_script_rel_path = '/assets/dist/vendor/swiper/swiper-bundle.min.js';
		$swiper_script_abs_path = $theme_path . $swiper_script_rel_path;

		if ( file_exists( $swiper_script_abs_path ) ) {
			wp_enqueue_script(
				'mauswp-swiper',
				$theme_uri . $swiper_script_rel_path,
				[],
				(string) filemtime( $swiper_script_abs_path ),
				[
					'strategy'  => 'defer',
					'in_footer' => true,
				]
			);
			$script_deps[] = 'mauswp-swiper';
		}
	}

	$script_rel_path = '/assets/dist/app.js';
	$script_abs_path = $theme_path . $script_rel_path;

	if ( file_exists( $script_abs_path ) ) {
		wp_enqueue_script(
			'mauswp-app',
			$theme_uri . $script_rel_path,
			$script_deps,
			(string) filemtime( $script_abs_path ),
			[
				'strategy'  => 'defer',
				'in_footer' => true,
			]
		);
	}

	wp_localize_script(
		'mauswp-app',
		'mauswpData',
		[
			'ajaxUrl'                     => admin_url( 'admin-ajax.php' ),
			'searchNonce'                 => wp_create_nonce( 'mauswp_search_nonce' ),
			'searchNoResultsText'         => __( 'No hay productos para', 'mauswp' ),
			'searchSuggestionsHeading'    => __( '¿Quizás buscabas...?', 'mauswp' ),
			'searchPopularHeading'        => __( 'Productos populares', 'mauswp' ),
		]
	);
}
add_action( 'wp_enqueue_scripts', 'mauswp_enqueue_assets' );

/**
 * Dequeue WooCommerce assets on non-store pages.
 */
function mauswp_dequeue_woocommerce_assets(): void {
	if ( ! function_exists( 'is_woocommerce' ) ) {
		return;
	}

	if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
		return;
	}

	wp_dequeue_style( 'woocommerce-general' );
	wp_dequeue_style( 'woocommerce-layout' );
	wp_dequeue_style( 'woocommerce-smallscreen' );
	wp_dequeue_style( 'woocommerce_frontend_styles' );
	wp_dequeue_style( 'wc-blocks-style' );

	wp_dequeue_script( 'woocommerce' );
	wp_dequeue_script( 'wc-add-to-cart' );
	wp_dequeue_script( 'wc-cart-fragments' );
	wp_dequeue_script( 'wc-single-product' );
	wp_dequeue_script( 'wc-country-select' );
	wp_dequeue_script( 'wc-address-i18n' );
}
add_action( 'wp_enqueue_scripts', 'mauswp_dequeue_woocommerce_assets', 99 );

/**
 * Remove Mailchimp SMS consent frontend scripts from classic checkout.
 *
 * Mailchimp's `mailchimp-woocommerce_sms_consent` script mutates the
 * `billing_phone` field label/required state on the frontend after checkout
 * refreshes. The classic checkout already works without that script because
 * Mailchimp renders its own inline behavior for the consent field.
 */
function mauswp_dequeue_mailchimp_checkout_scripts(): void {
	if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return;
	}

	wp_dequeue_script( 'mailchimp-woocommerce_sms_consent' );
	wp_dequeue_script( 'mailchimp-woocommerce_sms_consent_phone_validation' );
}
add_action( 'wp_enqueue_scripts', 'mauswp_dequeue_mailchimp_checkout_scripts', 100 );

/**
 * Preload critical self-hosted fonts.
 */
function mauswp_preload_fonts(): void {
	$theme_uri = get_template_directory_uri();

	$fonts = [
		$theme_uri . '/assets/fonts/manrope-400-latin.woff2'    => '400',
		$theme_uri . '/assets/fonts/manrope-600-latin.woff2'    => '600',
		$theme_uri . '/assets/fonts/manrope-700-latin.woff2'    => '700',
		$theme_uri . '/assets/fonts/manrope-400-latin-ext.woff2' => '400',
		$theme_uri . '/assets/fonts/manrope-600-latin-ext.woff2' => '600',
		$theme_uri . '/assets/fonts/manrope-700-latin-ext.woff2' => '700',
	];

	foreach ( $fonts as $url => $weight ) {
		printf(
			"<link rel=\"preload\" href=\"%s\" as=\"font\" type=\"font/woff2\" crossorigin=\"anonymous\" data-weight=\"%s\">\n",
			esc_url( $url ),
			esc_attr( $weight )
		);
	}
}
add_action( 'wp_head', 'mauswp_preload_fonts', 1 );

/**
 * Enqueue editor stylesheet when available.
 */
function mauswp_enqueue_editor_assets(): void {
	$editor_rel_path = '/assets/dist/app.css';
	$editor_abs_path = get_template_directory() . $editor_rel_path;

	if ( file_exists( $editor_abs_path ) ) {
		wp_enqueue_style(
			'mauswp-editor-style',
			get_template_directory_uri() . $editor_rel_path,
			[],
			(string) filemtime( $editor_abs_path )
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'mauswp_enqueue_editor_assets' );

/**
 * Google Tag Manager container ID.
 */
if ( ! defined( 'MAUSWP_GTM_ID' ) ) {
	define( 'MAUSWP_GTM_ID', 'GTM-585RLCD' );
}

/**
 * Output GTM script in <head>.
 */
function mauswp_gtm_head(): void {
	if ( ! defined( 'MAUSWP_GTM_ID' ) || '' === MAUSWP_GTM_ID ) {
		return;
	}
	?>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','<?php echo esc_js( MAUSWP_GTM_ID ); ?>');</script>
	<?php
}
add_action( 'wp_head', 'mauswp_gtm_head', 1 );

/**
 * Output GTM noscript iframe after <body>.
 */
function mauswp_gtm_body_open(): void {
	if ( ! defined( 'MAUSWP_GTM_ID' ) || '' === MAUSWP_GTM_ID ) {
		return;
	}
	?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo esc_attr( MAUSWP_GTM_ID ); ?>" height="0" width="0" style="display:none;visibility:hidden" aria-hidden="true"></iframe></noscript>
	<?php
}
add_action( 'wp_body_open', 'mauswp_gtm_body_open', 1 );
