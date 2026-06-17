<?php
/**
 * Theme setup.
 *
 * @package MausWP
 */

declare(strict_types=1);

if ( ! function_exists( 'mauswp_setup' ) ) {
	/**
	 * Register theme supports and menus.
	 */
	function mauswp_setup(): void {
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'align-wide' );
		add_theme_support(
			'html5',
			[
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'script',
				'style',
				'navigation-widgets',
			]
		);
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/dist/app.css' );

		register_nav_menus(
			[
				'primary'       => __( 'Menú principal', 'mauswp' ),
				'footer'        => __( 'Menú footer', 'mauswp' ),
				'footer_legal'  => __( 'Menú legal footer', 'mauswp' ),
			]
		);
	}
}
add_action( 'after_setup_theme', 'mauswp_setup' );

/**
 * Clean up common head output without affecting core behavior.
 */
function mauswp_cleanup_head(): void {
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'mauswp_cleanup_head' );

/**
 * Redirect /catalogo to /tienda.
 */
function mauswp_redirect_catalogo(): void {
	if ( ! is_admin() && isset( $_SERVER['REQUEST_URI'] ) && '/catalogo' === untrailingslashit( wp_unslash( $_SERVER['REQUEST_URI'] ) ) ) {
		wp_safe_redirect( home_url( '/tienda/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'mauswp_redirect_catalogo' );
