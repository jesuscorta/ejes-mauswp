<?php
/**
 * Product editorial flexible content fields.
 *
 * @package MausWP
 */

declare(strict_types=1);

/**
 * Register product flexible content fields.
 */
function mauswp_register_product_content_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		[
			'key'    => 'group_mauswp_product_content_builder',
			'title'  => __( 'Contenido editorial del producto', 'mauswp' ),
			'fields' => [
				[
					'key'          => 'field_mauswp_product_content_builder',
					'label'        => __( 'Bloques de contenido', 'mauswp' ),
					'name'         => 'mauswp_product_content_builder',
					'type'         => 'flexible_content',
					'button_label' => __( 'Añadir bloque', 'mauswp' ),
					'layouts'      => [
						'layout_mauswp_product_heading' => [
							'key'        => 'layout_mauswp_product_heading',
							'name'       => 'heading',
							'label'      => __( 'Título', 'mauswp' ),
							'display'    => 'block',
							'sub_fields' => [
								[
									'key'   => 'field_mauswp_product_heading_text',
									'label' => __( 'Texto', 'mauswp' ),
									'name'  => 'text',
									'type'  => 'text',
								],
								[
									'key'           => 'field_mauswp_product_heading_level',
									'label'         => __( 'Etiqueta HTML', 'mauswp' ),
									'name'          => 'level',
									'type'          => 'select',
									'choices'       => [
										'h1' => 'H1',
										'h2' => 'H2',
										'h3' => 'H3',
									],
									'default_value' => 'h2',
									'return_format' => 'value',
								],
							],
						],
						'layout_mauswp_product_full_image' => [
							'key'        => 'layout_mauswp_product_full_image',
							'name'       => 'full_image',
							'label'      => __( 'Imagen a ancho completo', 'mauswp' ),
							'display'    => 'block',
							'sub_fields' => [
								[
									'key'           => 'field_mauswp_product_full_image_asset',
									'label'         => __( 'Imagen', 'mauswp' ),
									'name'          => 'image',
									'type'          => 'image',
									'return_format' => 'array',
									'preview_size'  => 'large',
									'library'       => 'all',
								],
							],
						],
						'layout_mauswp_product_image_row' => [
							'key'        => 'layout_mauswp_product_image_row',
							'name'       => 'image_row',
							'label'      => __( 'Galería de imágenes', 'mauswp' ),
							'display'    => 'block',
							'sub_fields' => [
								[
									'key'          => 'field_mauswp_product_image_row_gallery',
									'label'        => __( 'Imágenes', 'mauswp' ),
									'name'         => 'images',
									'type'         => 'gallery',
									'instructions' => __( 'Añade tantas imágenes como necesites. Se mostrarán en una galería de 4 columnas.', 'mauswp' ),
									'min'          => 1,
									'insert'       => 'append',
									'library'      => 'all',
									'preview_size' => 'medium',
								],
							],
						],
						'layout_mauswp_product_text' => [
							'key'        => 'layout_mauswp_product_text',
							'name'       => 'text',
							'label'      => __( 'Texto', 'mauswp' ),
							'display'    => 'block',
							'sub_fields' => [
								[
									'key'          => 'field_mauswp_product_text_content',
									'label'        => __( 'Contenido', 'mauswp' ),
									'name'         => 'content',
									'type'         => 'wysiwyg',
									'tabs'         => 'all',
									'toolbar'      => 'full',
									'media_upload' => 0,
									'delay'        => 0,
								],
							],
						],
					],
				],
			],
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'product',
					],
				],
			],
		]
	);
}
add_action( 'acf/init', 'mauswp_register_product_content_fields' );

/**
 * Check whether a product has editorial builder rows.
 *
 * @param int $product_id Product ID.
 * @return bool
 */
function mauswp_product_has_editorial_builder( int $product_id ): bool {
	if ( ! function_exists( 'get_field' ) ) {
		return false;
	}

	$rows = get_field( 'mauswp_product_content_builder', $product_id );

	return is_array( $rows ) && ! empty( $rows );
}

/**
 * Render editorial builder blocks for a product.
 *
 * @param int $product_id Product ID.
 */
function mauswp_render_product_editorial_builder( int $product_id ): void {
	if ( ! function_exists( 'have_rows' ) || ! have_rows( 'mauswp_product_content_builder', $product_id ) ) {
		return;
	}

	echo '<div class="shop-product-builder">';

	while ( have_rows( 'mauswp_product_content_builder', $product_id ) ) {
		the_row();

		$layout = (string) get_row_layout();

		if ( 'heading' === $layout ) {
			$text  = (string) get_sub_field( 'text' );
			$level = (string) get_sub_field( 'level' );
			$level = in_array( $level, [ 'h1', 'h2', 'h3' ], true ) ? $level : 'h2';

			if ( '' !== trim( $text ) ) {
				echo '<div class="shop-product-builder__block shop-product-builder__block--heading">';
				echo '<' . esc_attr( $level ) . ' class="shop-product-builder__heading shop-product-builder__heading--' . esc_attr( $level ) . '">' . esc_html( $text ) . '</' . esc_attr( $level ) . '>';
				echo '</div>';
			}
		}

		if ( 'full_image' === $layout ) {
			$image = get_sub_field( 'image' );

			if ( is_array( $image ) && ! empty( $image['ID'] ) ) {
				echo '<div class="shop-product-builder__block shop-product-builder__block--full-image">';
				echo wp_get_attachment_image( (int) $image['ID'], 'full', false, [ 'class' => 'shop-product-builder__full-image' ] );
				echo '</div>';
			}
		}

		if ( 'image_row' === $layout ) {
			$images = get_sub_field( 'images' );

			if ( is_array( $images ) && ! empty( $images ) ) {
				echo '<div class="shop-product-builder__block shop-product-builder__block--image-row">';
				echo '<div class="shop-product-builder__image-row">';

				foreach ( $images as $image ) {
					if ( ! is_array( $image ) || empty( $image['ID'] ) ) {
						continue;
					}

					echo '<div class="shop-product-builder__image-cell">';
					echo wp_get_attachment_image( (int) $image['ID'], 'large', false, [ 'class' => 'shop-product-builder__row-image' ] );
					echo '</div>';
				}

				echo '</div>';
				echo '</div>';
			}
		}

		if ( 'text' === $layout ) {
			$content = (string) get_sub_field( 'content' );

			if ( '' !== trim( wp_strip_all_tags( $content ) ) ) {
				echo '<div class="shop-product-builder__block shop-product-builder__block--text entry-content">';
				echo wp_kses_post( apply_filters( 'the_content', $content ) );
				echo '</div>';
			}
		}
	}

	echo '</div>';
}
