<?php
/**
 * Allowed block types.
 *
 * @package MausWP
 */

declare(strict_types=1);

/**
 * Limit Gutenberg blocks to a controlled base set.
 *
 * @param bool|array $allowed_block_types Current allowed blocks.
 * @return array<int, string>
 */
function mauswp_allowed_block_types( $allowed_block_types ): array {
	$blocks = [
		'acf/mauswp-hero',
		'acf/mauswp-about-hero',
		'acf/mauswp-features-strip',
		'acf/mauswp-expertise-grid',
		'acf/mauswp-angled-product-callout',
		'acf/mauswp-media-text',
		'acf/mauswp-editorial-offset',
		'acf/mauswp-latest-posts',
		'acf/mauswp-product-showcase',
		'acf/mauswp-contact-form',
		'core/paragraph',
		'core/heading',
		'core/image',
		'core/list',
		'core/buttons',
		'core/button',
		'core/group',
		'core/columns',
		'core/column',
		'core/spacer',
		'core/separator',
		'core/embed',
	];

	// Future ACF blocks can be added here, for example:
	// $blocks[] = 'acf/mauswp-features';

	return $blocks;
}
add_filter( 'allowed_block_types_all', 'mauswp_allowed_block_types' );
