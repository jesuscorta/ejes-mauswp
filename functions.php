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
