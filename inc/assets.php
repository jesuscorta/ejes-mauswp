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
function mauswp_enqueue_assets(): void {
	$theme_uri  = get_template_directory_uri();
	$theme_path = get_template_directory();

	$swiper_style_rel_path = '/assets/dist/vendor/swiper/swiper-bundle.min.css';
	$swiper_style_abs_path = $theme_path . $swiper_style_rel_path;

	if ( file_exists( $swiper_style_abs_path ) ) {
		wp_enqueue_style(
			'mauswp-swiper',
			$theme_uri . $swiper_style_rel_path,
			[],
			(string) filemtime( $swiper_style_abs_path )
		);
	}

	$style_rel_path = '/assets/dist/app.css';
	$style_abs_path = $theme_path . $style_rel_path;

	if ( file_exists( $style_abs_path ) ) {
		wp_enqueue_style(
			'mauswp-app',
			$theme_uri . $style_rel_path,
			[ 'mauswp-swiper' ],
			(string) filemtime( $style_abs_path )
		);
	}

	$swiper_script_rel_path = '/assets/dist/vendor/swiper/swiper-bundle.min.js';
	$swiper_script_abs_path = $theme_path . $swiper_script_rel_path;

	if ( file_exists( $swiper_script_abs_path ) ) {
		wp_enqueue_script(
			'mauswp-swiper',
			$theme_uri . $swiper_script_rel_path,
			[],
			(string) filemtime( $swiper_script_abs_path ),
			true
		);
	}

	$script_rel_path = '/assets/dist/app.js';
	$script_abs_path = $theme_path . $script_rel_path;

	if ( file_exists( $script_abs_path ) ) {
		wp_enqueue_script(
			'mauswp-app',
			$theme_uri . $script_rel_path,
			[ 'mauswp-swiper' ],
			(string) filemtime( $script_abs_path ),
			true
		);
	}

	wp_localize_script(
		'mauswp-app',
		'mauswpData',
		[
			'ajaxUrl'                     => admin_url( 'admin-ajax.php' ),
			'searchNoResultsText'         => __( 'No hay productos para', 'mauswp' ),
			'searchSuggestionsHeading'    => __( '¿Quizás buscabas...?', 'mauswp' ),
			'searchPopularHeading'        => __( 'Productos populares', 'mauswp' ),
		]
	);
}
add_action( 'wp_enqueue_scripts', 'mauswp_enqueue_assets' );

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
