<?php
/**
 * Shop page editable fields.
 *
 * @package MausWP
 */

declare(strict_types=1);

/**
 * Register editable fields for the WooCommerce shop page.
 */
function mauswp_register_shop_page_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) || ! function_exists( 'wc_get_page_id' ) ) {
		return;
	}

	$shop_page_id = (int) wc_get_page_id( 'shop' );

	if ( $shop_page_id <= 0 ) {
		return;
	}

	acf_add_local_field_group(
		[
			'key'    => 'group_mauswp_shop_page_fields',
			'title'  => __( 'Campos de tienda', 'mauswp' ),
			'fields' => [
				[
					'key'           => 'field_mauswp_shop_archive_image',
					'label'         => __( 'Imagen', 'mauswp' ),
					'name'          => 'mauswp_shop_archive_image',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'library'       => 'all',
					'instructions'  => __( 'Imagen principal del banner de la tienda.', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_shop_archive_description',
					'label'         => __( 'Descripción', 'mauswp' ),
					'name'          => 'mauswp_shop_archive_description',
					'type'          => 'textarea',
					'rows'          => 3,
					'new_lines'     => 'br',
					'instructions'  => __( 'Texto breve que aparece en la cabecera de la tienda.', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_shop_archive_useful_info',
					'label'         => __( 'Información útil', 'mauswp' ),
					'name'          => 'mauswp_shop_archive_useful_info',
					'type'          => 'wysiwyg',
					'tabs'          => 'all',
					'toolbar'       => 'basic',
					'media_upload'  => 0,
					'delay'         => 0,
					'instructions'  => __( 'Bloque editorial inferior de la tienda.', 'mauswp' ),
				],
			],
			'location' => [
				[
					[
						'param'    => 'post',
						'operator' => '==',
						'value'    => (string) $shop_page_id,
					],
				],
			],
		]
	);
}
add_action( 'acf/init', 'mauswp_register_shop_page_fields' );
