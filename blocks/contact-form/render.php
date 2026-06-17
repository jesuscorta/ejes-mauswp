<?php
/**
 * Contact form block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

mauswp_render_contact_block(
	[
		'anchor'    => ! empty( $block['anchor'] ) ? (string) $block['anchor'] : 'contacto',
		'align'     => ! empty( $block['align'] ) ? (string) $block['align'] : 'wide',
		'className' => ! empty( $block['className'] ) ? (string) $block['className'] : '',
	]
);
