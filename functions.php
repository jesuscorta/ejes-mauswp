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
 * Regenerar indexables de Yoast para todos los productos.
 * Acceder con ?regenerar_yoast_productos=1 siendo administrador.
 */
add_action( 'admin_init', function (): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( ! isset( $_GET['regenerar_yoast_productos'] ) ) {
		return;
	}

	$products = get_posts( [
		'post_type'      => 'product',
		'post_status'    => [ 'publish', 'draft', 'private' ],
		'posts_per_page' => -1,
		'fields'         => 'ids',
	] );

	foreach ( $products as $product_id ) {
		wp_update_post( [
			'ID' => $product_id,
		] );
	}

	wp_die( 'Productos actualizados: ' . count( $products ) );
} );
