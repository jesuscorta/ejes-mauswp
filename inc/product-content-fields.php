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
						'layout_mauswp_product_faqs' => [
							'key'        => 'layout_mauswp_product_faqs',
							'name'       => 'faqs',
							'label'      => __( 'FAQs', 'mauswp' ),
							'display'    => 'block',
							'sub_fields' => [
								[
									'key'          => 'field_mauswp_product_faqs_title',
									'label'        => __( 'Título del bloque', 'mauswp' ),
									'name'         => 'title',
									'type'         => 'text',
									'instructions' => __( 'Opcional. Por ejemplo: Preguntas frecuentes.', 'mauswp' ),
								],
								[
									'key'           => 'field_mauswp_product_faqs_enable_image',
									'label'         => __( 'Añadir imagen', 'mauswp' ),
									'name'          => 'enable_image',
									'type'          => 'true_false',
									'instructions'  => __( 'Activa esta opción para mostrar una imagen a la izquierda y las FAQs a la derecha.', 'mauswp' ),
									'default_value' => 0,
									'ui'            => 1,
									'ui_on_text'    => __( 'Sí', 'mauswp' ),
									'ui_off_text'   => __( 'No', 'mauswp' ),
								],
								[
									'key'               => 'field_mauswp_product_faqs_image',
									'label'             => __( 'Imagen', 'mauswp' ),
									'name'              => 'image',
									'type'              => 'image',
									'return_format'     => 'array',
									'preview_size'      => 'medium_large',
									'library'           => 'all',
									'conditional_logic' => [
										[
											[
												'field'    => 'field_mauswp_product_faqs_enable_image',
												'operator' => '==',
												'value'    => '1',
											],
										],
									],
								],
								[
									'key'          => 'field_mauswp_product_faqs_items',
									'label'        => __( 'Preguntas y respuestas', 'mauswp' ),
									'name'         => 'items',
									'type'         => 'repeater',
									'instructions' => __( 'Añade las preguntas frecuentes relacionadas con el producto.', 'mauswp' ),
									'button_label' => __( 'Añadir pregunta', 'mauswp' ),
									'layout'       => 'block',
									'min'          => 1,
									'sub_fields'   => [
										[
											'key'      => 'field_mauswp_product_faqs_question',
											'label'    => __( 'Pregunta', 'mauswp' ),
											'name'     => 'question',
											'type'     => 'text',
											'required' => 1,
										],
										[
											'key'          => 'field_mauswp_product_faqs_answer',
											'label'        => __( 'Respuesta', 'mauswp' ),
											'name'         => 'answer',
											'type'         => 'wysiwyg',
											'required'     => 1,
											'tabs'         => 'all',
											'toolbar'      => 'basic',
											'media_upload' => 0,
											'delay'        => 0,
										],
									],
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

	$builder_images = [];

	echo '<div class="shop-product-builder" data-product-builder-gallery>';

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
				$builder_images[] = (int) $image['ID'];
				$image_index      = count( $builder_images ) - 1;

				echo '<div class="shop-product-builder__block shop-product-builder__block--full-image">';
				echo '<button class="shop-product-builder__image-zoom-trigger" type="button" data-product-builder-gallery-open data-builder-gallery-index="' . esc_attr( (string) $image_index ) . '" aria-label="' . esc_attr__( 'Ampliar imagen', 'mauswp' ) . '">';
				echo wp_get_attachment_image( (int) $image['ID'], 'full', false, [ 'class' => 'shop-product-builder__full-image' ] );
				echo '</button>';
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

					$builder_images[] = (int) $image['ID'];
					$image_index      = count( $builder_images ) - 1;

					echo '<div class="shop-product-builder__image-cell">';
					echo '<button class="shop-product-builder__image-zoom-trigger" type="button" data-product-builder-gallery-open data-builder-gallery-index="' . esc_attr( (string) $image_index ) . '" aria-label="' . esc_attr__( 'Ampliar imagen', 'mauswp' ) . '">';
					echo wp_get_attachment_image( (int) $image['ID'], 'large', false, [ 'class' => 'shop-product-builder__row-image' ] );
					echo '</button>';
					echo '</div>';
				}

				echo '</div>';
				echo '</div>';
			}
		}

		if ( 'faqs' === $layout ) {
			$title        = (string) get_sub_field( 'title' );
			$enable_image = (bool) get_sub_field( 'enable_image' );
			$image        = get_sub_field( 'image' );
			$items        = get_sub_field( 'items' );
			$has_image    = $enable_image && is_array( $image ) && ! empty( $image['ID'] );

			if ( is_array( $items ) && ! empty( $items ) ) {
				$section_class = 'shop-product-builder__block shop-product-builder__block--faqs';

				if ( $has_image ) {
					$section_class .= ' shop-product-builder__block--faqs-with-image';
				}

				echo '<section class="' . esc_attr( $section_class ) . '" aria-label="' . esc_attr__( 'Preguntas frecuentes', 'mauswp' ) . '">';
				echo '<div class="shop-product-builder__faqs-layout">';

				if ( $has_image ) {
					$builder_images[] = (int) $image['ID'];
					$image_index      = count( $builder_images ) - 1;

					echo '<div class="shop-product-builder__faqs-media">';
					echo '<button class="shop-product-builder__image-zoom-trigger" type="button" data-product-builder-gallery-open data-builder-gallery-index="' . esc_attr( (string) $image_index ) . '" aria-label="' . esc_attr__( 'Ampliar imagen', 'mauswp' ) . '">';
					echo wp_get_attachment_image( (int) $image['ID'], 'large', false, [ 'class' => 'shop-product-builder__faqs-image', 'loading' => 'lazy', 'decoding' => 'async' ] );
					echo '</button>';
					echo '</div>';
				}

				echo '<div class="shop-product-builder__faqs-content">';

				if ( '' !== trim( $title ) ) {
					echo '<h2 class="shop-product-builder__faqs-title">' . esc_html( $title ) . '</h2>';
				}

				echo '<div class="shop-product-builder__faqs-list">';

				foreach ( $items as $item ) {
					if ( ! is_array( $item ) ) {
						continue;
					}

					$question = isset( $item['question'] ) ? (string) $item['question'] : '';
					$answer   = isset( $item['answer'] ) ? (string) $item['answer'] : '';

					if ( '' === trim( $question ) || '' === trim( wp_strip_all_tags( $answer ) ) ) {
						continue;
					}

					echo '<details class="shop-product-builder__faq-item">';
					echo '<summary class="shop-product-builder__faq-question"><span>' . esc_html( $question ) . '</span></summary>';
					echo '<div class="shop-product-builder__faq-answer entry-content">' . wp_kses_post( apply_filters( 'the_content', $answer ) ) . '</div>';
					echo '</details>';
				}

				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</section>';
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

	if ( ! empty( $builder_images ) ) {
		echo '<div class="shop-product-builder__lightbox" hidden data-product-builder-gallery-lightbox aria-modal="true" role="dialog" aria-label="' . esc_attr__( 'Imágenes ampliadas del contenido del producto', 'mauswp' ) . '">';
		echo '<button class="shop-product__lightbox-backdrop" type="button" data-product-builder-gallery-close aria-label="' . esc_attr__( 'Cerrar galería', 'mauswp' ) . '"></button>';
		echo '<div class="shop-product__lightbox-panel">';
		echo '<button class="shop-product__lightbox-close" type="button" data-product-builder-gallery-close aria-label="' . esc_attr__( 'Cerrar galería', 'mauswp' ) . '">&times;</button>';
		echo '<div class="swiper shop-product__lightbox-swiper" data-product-builder-gallery-swiper>';
		echo '<div class="swiper-wrapper">';

		foreach ( $builder_images as $builder_image_id ) {
			echo '<div class="swiper-slide"><div class="swiper-zoom-container">';
			echo wp_get_attachment_image( $builder_image_id, 'full', false, [ 'class' => 'shop-product__lightbox-image', 'loading' => 'lazy', 'decoding' => 'async' ] );
			echo '</div></div>';
		}

		echo '</div>';

		if ( count( $builder_images ) > 1 ) {
			echo '<button class="shop-product__lightbox-nav shop-product__lightbox-nav--prev" type="button" data-product-builder-gallery-prev aria-label="' . esc_attr__( 'Imagen anterior', 'mauswp' ) . '">&larr;</button>';
			echo '<button class="shop-product__lightbox-nav shop-product__lightbox-nav--next" type="button" data-product-builder-gallery-next aria-label="' . esc_attr__( 'Imagen siguiente', 'mauswp' ) . '">&rarr;</button>';
		}

		echo '</div>';
		echo '<p class="shop-product__lightbox-help">' . esc_html__( 'Haz doble clic o pellizca para ampliar. Arrastra para desplazarte.', 'mauswp' ) . '</p>';
		echo '</div>';
		echo '</div>';
	}

	echo '</div>';
}
