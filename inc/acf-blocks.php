<?php
/**
 * ACF block registration.
 *
 * @package MausWP
 */

declare(strict_types=1);

/**
 * Get all theme block.json files.
 *
 * @return array<int, string>
 */
function mauswp_get_theme_block_json_files(): array {
	$block_json_files = glob( get_template_directory() . '/blocks/*/block.json' );

	if ( ! is_array( $block_json_files ) ) {
		return [];
	}

	return $block_json_files;
}

/**
 * Register ACF blocks when ACF Pro is active.
 */
function mauswp_register_acf_blocks(): void {
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	$block_json_files = mauswp_get_theme_block_json_files();

	foreach ( $block_json_files as $block_json_file ) {
		register_block_type( dirname( $block_json_file ) );
	}
}
add_action( 'acf/init', 'mauswp_register_acf_blocks' );

/**
 * Save ACF JSON inside the theme for versioned field groups.
 *
 * @return string
 */
function mauswp_acf_json_save_point(): string {
	return get_template_directory() . '/acf-json';
}
add_filter( 'acf/settings/save_json', 'mauswp_acf_json_save_point' );

/**
 * Load ACF JSON from the theme.
 *
 * @param array<int, string> $paths Existing ACF JSON paths.
 * @return array<int, string>
 */
function mauswp_acf_json_load_point( array $paths ): array {
	$paths[] = get_template_directory() . '/acf-json';

	return array_values( array_unique( $paths ) );
}
add_filter( 'acf/settings/load_json', 'mauswp_acf_json_load_point' );
