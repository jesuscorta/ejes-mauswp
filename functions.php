<?php
/**
 * Theme bootstrap.
 *
 * @package MausWP
 */

declare(strict_types=1);

$mauswp_includes = [
	'/inc/setup.php',
	'/inc/assets.php',
	'/inc/helpers.php',
	'/inc/product-config-fields.php',
	'/inc/product-content-fields.php',
	'/inc/product-category-fields.php',
	'/inc/shop-page-fields.php',
	'/inc/acf-blocks.php',
	'/inc/theme-options.php',
	'/inc/allowed-blocks.php',
	'/inc/search-ajax.php',
];

foreach ( $mauswp_includes as $mauswp_file ) {
	$mauswp_path = get_template_directory() . $mauswp_file;

	if ( file_exists( $mauswp_path ) ) {
		require_once $mauswp_path;
	}
}

/**
 * Regenerar indexables de Yoast para todo el contenido público.
 * Acceder con ?regenerar_yoast_todo=1 siendo administrador.
 */
add_action( 'admin_init', function (): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ! isset( $_GET['regenerar_yoast_todo'] ) ) {
		return;
	}

	$post_types = get_post_types( [
		'public' => true,
	], 'names' );

	$excluded = [
		'attachment',
	];

	$post_types = array_diff( $post_types, $excluded );

	$posts = get_posts( [
		'post_type'      => $post_types,
		'post_status'    => [ 'publish', 'draft', 'pending', 'private', 'future' ],
		'posts_per_page' => -1,
		'fields'         => 'ids',
	] );

	foreach ( $posts as $post_id ) {
		wp_update_post( [
			'ID' => $post_id,
		] );
	}

	wp_die( 'Contenido actualizado: ' . count( $posts ) );
} );
