<?php
/**
 * Product category editable fields.
 *
 * @package MausWP
 */

declare(strict_types=1);

/**
 * Register editable fields for product categories.
 */
function mauswp_register_product_category_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		[
			'key'    => 'group_mauswp_product_category_fields',
			'title'  => __( 'Campos de categoría de producto', 'mauswp' ),
			'fields' => [
				[
					'key'           => 'field_mauswp_product_category_badge',
					'label'         => __( 'Badge', 'mauswp' ),
					'name'          => 'mauswp_product_category_badge',
					'type'          => 'text',
					'default_value' => __( 'Catálogo', 'mauswp' ),
				],
				[
					'key'          => 'field_mauswp_product_category_lead',
					'label'        => __( 'Descripción corta cabecera', 'mauswp' ),
					'name'         => 'mauswp_product_category_lead',
					'type'         => 'textarea',
					'rows'         => 3,
					'new_lines'    => 'br',
					'instructions' => __( 'Texto corto que aparece bajo el título en la cabecera de la categoría.', 'mauswp' ),
				],
				[
					'key'          => 'field_mauswp_product_category_empty_text',
					'label'        => __( 'Texto sin productos', 'mauswp' ),
					'name'         => 'mauswp_product_category_empty_text',
					'type'         => 'text',
					'instructions' => __( 'Mensaje mostrado si la categoría no tiene productos publicados.', 'mauswp' ),
				],
			],
			'location' => [
				[
					[
						'param'    => 'taxonomy',
						'operator' => '==',
						'value'    => 'product_cat',
					],
				],
			],
		]
	);
}
add_action( 'acf/init', 'mauswp_register_product_category_fields' );
