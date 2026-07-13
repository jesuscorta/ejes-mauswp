<?php
/**
 * Theme-wide options powered by ACF.
 *
 * @package MausWP
 */

declare(strict_types=1);

/**
 * Register the global theme options page.
 */
function mauswp_register_theme_options_page(): void {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	$parent_page = acf_add_options_page(
		[
			'page_title' => __( 'Opciones del tema', 'mauswp' ),
			'menu_title' => __( 'Opciones del tema', 'mauswp' ),
			'menu_slug'  => 'mauswp-theme-options',
			'capability' => 'edit_theme_options',
			'redirect'   => false,
			'position'   => 61,
		]
	);

	if ( ! is_array( $parent_page ) || empty( $parent_page['menu_slug'] ) ) {
		return;
	}

	acf_add_options_sub_page(
		[
			'page_title'  => __( 'Opciones de productos', 'mauswp' ),
			'menu_title'  => __( 'Productos', 'mauswp' ),
			'menu_slug'   => 'mauswp-theme-options-products',
			'parent_slug' => (string) $parent_page['menu_slug'],
			'capability'  => 'edit_theme_options',
		]
	);

	acf_add_options_sub_page(
		[
			'page_title'  => __( 'Opciones de footer', 'mauswp' ),
			'menu_title'  => __( 'Footer', 'mauswp' ),
			'menu_slug'   => 'mauswp-theme-options-footer',
			'parent_slug' => (string) $parent_page['menu_slug'],
			'capability'  => 'edit_theme_options',
		]
	);

	acf_add_options_sub_page(
		[
			'page_title'  => __( 'Opciones del buscador', 'mauswp' ),
			'menu_title'  => __( 'Buscador', 'mauswp' ),
			'menu_slug'   => 'mauswp-theme-options-search',
			'parent_slug' => (string) $parent_page['menu_slug'],
			'capability'  => 'edit_theme_options',
		]
	);

	acf_add_options_sub_page(
		[
			'page_title'  => __( 'Opciones del megamenu', 'mauswp' ),
			'menu_title'  => __( 'Megamenu', 'mauswp' ),
			'menu_slug'   => 'mauswp-theme-options-megamenu',
			'parent_slug' => (string) $parent_page['menu_slug'],
			'capability'  => 'edit_theme_options',
		]
	);

	acf_add_options_sub_page(
		[
			'page_title'  => __( 'Opciones de tienda', 'mauswp' ),
			'menu_title'  => __( 'Tienda', 'mauswp' ),
			'menu_slug'   => 'mauswp-theme-options-shop',
			'parent_slug' => (string) $parent_page['menu_slug'],
			'capability'  => 'edit_theme_options',
		]
	);
}
add_action( 'acf/init', 'mauswp_register_theme_options_page' );

/**
 * Register editable fields for the header top bar.
 */
function mauswp_register_theme_options_fields(): void {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group(
		[
			'key'    => 'group_mauswp_theme_options',
			'title'  => __( 'Cabecera', 'mauswp' ),
			'fields' => [
				[
					'key'           => 'field_mauswp_header_logo',
					'label'         => __( 'Logo cabecera', 'mauswp' ),
					'name'          => 'mauswp_header_logo',
					'type'          => 'image',
					'instructions'  => __( 'Logo principal que se mostrará en la cabecera.', 'mauswp' ),
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'library'       => 'all',
				],
				[
					'key'           => 'field_mauswp_top_bar_message',
					'label'         => __( 'Mensaje barra superior', 'mauswp' ),
					'name'          => 'mauswp_top_bar_message',
					'type'          => 'text',
					'default_value' => 'DISPONEMOS DE RECAMBIOS ALKO, KNOTT, GEPLASMETAL, WAP Y AXF',
				],
				[
					'key'           => 'field_mauswp_contact_phone',
					'label'         => __( 'Teléfono', 'mauswp' ),
					'name'          => 'mauswp_contact_phone',
					'type'          => 'text',
					'default_value' => '608.725.197',
				],
				[
					'key'           => 'field_mauswp_contact_email',
					'label'         => __( 'Email', 'mauswp' ),
					'name'          => 'mauswp_contact_email',
					'type'          => 'email',
					'default_value' => 'administracion@sumagrogranada.com',
				],
				[
					'key'           => 'field_mauswp_facebook_url',
					'label'         => __( 'URL de Facebook', 'mauswp' ),
					'name'          => 'mauswp_facebook_url',
					'type'          => 'url',
					'default_value' => 'https://www.facebook.com/Granadina-Industrial-Agr%C3%ADcola-100453171807218',
				],
				[
					'key'           => 'field_mauswp_contact_block_title',
					'label'         => __( 'Título bloque contacto', 'mauswp' ),
					'name'          => 'mauswp_contact_block_title',
					'type'          => 'text',
					'default_value' => __( 'Hablemos de tu proyecto', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_contact_block_intro',
					'label'         => __( 'Texto bloque contacto', 'mauswp' ),
					'name'          => 'mauswp_contact_block_intro',
					'type'          => 'textarea',
					'rows'          => 3,
					'new_lines'     => 'br',
					'default_value' => __( 'Cuéntanos qué necesitas y te responderemos en menos de 24h.', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_contact_block_email_label',
					'label'         => __( 'Etiqueta email bloque contacto', 'mauswp' ),
					'name'          => 'mauswp_contact_block_email_label',
					'type'          => 'text',
					'default_value' => __( 'Email', 'mauswp' ),
				],
			[
				'key'           => 'field_mauswp_contact_block_phone_label',
				'label'         => __( 'Etiqueta teléfono bloque contacto', 'mauswp' ),
				'name'          => 'mauswp_contact_block_phone_label',
				'type'          => 'text',
				'default_value' => __( 'Teléfono', 'mauswp' ),
			],
			[
				'key'          => 'field_mauswp_contact_form_id',
				'label'        => __( 'ID formulario bloque contacto', 'mauswp' ),
				'name'         => 'mauswp_contact_form_id',
				'type'         => 'number',
				'instructions' => __( 'ID del formulario de Gravity Forms que se mostrará en el bloque de contacto. Dejar vacío para usar el primer formulario disponible.', 'mauswp' ),
				'default_value' => '',
			],
		],
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'mauswp-theme-options',
					],
				],
			],
		]
	);

	acf_add_local_field_group(
		[
			'key'    => 'group_mauswp_product_options',
			'title'  => __( 'Productos', 'mauswp' ),
			'fields' => [
				[
					'key'           => 'field_mauswp_product_config_title',
					'label'         => __( 'Título bloque configuración', 'mauswp' ),
					'name'          => 'mauswp_product_config_title',
					'type'          => 'text',
					'default_value' => __( 'Configuración', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_product_config_text',
					'label'         => __( 'Texto bloque configuración', 'mauswp' ),
					'name'          => 'mauswp_product_config_text',
					'type'          => 'textarea',
					'instructions'  => __( 'Texto corto que aparece encima de los campos configurables.', 'mauswp' ),
					'rows'          => 3,
					'new_lines'     => 'br',
					'default_value' => __( 'Completa estas medidas y opciones antes de añadir el producto al pedido.', 'mauswp' ),
				],
				[
					'key'          => 'field_mauswp_product_config_help_text',
					'label'        => __( 'Texto ayuda configuración', 'mauswp' ),
					'name'         => 'mauswp_product_config_help_text',
					'type'         => 'wysiwyg',
					'instructions' => __( 'Contenido mostrado en el popup de ayuda de la configuración del producto.', 'mauswp' ),
					'tabs'         => 'all',
					'toolbar'      => 'basic',
					'media_upload' => 0,
					'delay'        => 0,
				],
				[
					'key'           => 'field_mauswp_product_support_title',
					'label'         => __( 'Título compra asistida', 'mauswp' ),
					'name'          => 'mauswp_product_support_title',
					'type'          => 'text',
					'default_value' => __( 'Compra asistida', 'mauswp' ),
				],
			[
				'key'           => 'field_mauswp_product_support_text',
				'label'         => __( 'Texto compra asistida', 'mauswp' ),
				'name'          => 'mauswp_product_support_text',
				'type'          => 'textarea',
				'rows'          => 4,
				'new_lines'     => 'br',
				'default_value' => __( 'Si necesitas confirmar compatibilidades, medidas o acabado, revisa la ficha y luego consúltanos con el producto exacto.', 'mauswp' ),
			],
			[
				'key'          => 'field_mauswp_product_quote_form_id',
				'label'        => __( 'ID formulario presupuesto', 'mauswp' ),
				'name'         => 'mauswp_product_quote_form_id',
				'type'         => 'number',
				'instructions' => __( 'ID del formulario de Gravity Forms que se mostrará en el modal "Solicitar presupuesto" de productos sin stock. Dejar vacío para usar el primer formulario disponible.', 'mauswp' ),
				'default_value' => '',
			],
		],
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'mauswp-theme-options-products',
					],
				],
			],
		]
	);

	acf_add_local_field_group(
		[
			'key'    => 'group_mauswp_search_options',
			'title'  => __( 'Buscador', 'mauswp' ),
			'fields' => [
				[
					'key'           => 'field_mauswp_search_title',
					'label'         => __( 'Título del buscador', 'mauswp' ),
					'name'          => 'mauswp_search_title',
					'type'          => 'text',
					'default_value' => __( 'Buscar productos', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_search_placeholder',
					'label'         => __( 'Placeholder del input', 'mauswp' ),
					'name'          => 'mauswp_search_placeholder',
					'type'          => 'text',
					'default_value' => __( '¿Qué producto buscas?', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_search_suggestions_heading',
					'label'         => __( 'Título de sugerencias', 'mauswp' ),
					'name'          => 'mauswp_search_suggestions_heading',
					'type'          => 'text',
					'default_value' => __( 'Productos populares', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_search_suggested_products',
					'label'         => __( 'Productos sugeridos', 'mauswp' ),
					'name'          => 'mauswp_search_suggested_products',
					'type'          => 'relationship',
					'instructions'  => __( 'Selecciona hasta 6 productos para mostrar como sugerencias cuando el buscador está vacío.', 'mauswp' ),
					'post_type'     => [ 'product' ],
					'filters'       => [ 'search' ],
					'elements'      => [ 'featured_image' ],
					'min'           => 0,
					'max'           => 6,
					'return_format' => 'id',
				],
				[
					'key'           => 'field_mauswp_search_max_results',
					'label'         => __( 'Máximo de resultados', 'mauswp' ),
					'name'          => 'mauswp_search_max_results',
					'type'          => 'number',
					'instructions'  => __( 'Número máximo de productos a mostrar en el autocompletado.', 'mauswp' ),
					'default_value' => 8,
					'min'           => 1,
					'max'           => 20,
					'step'          => 1,
				],
				[
					'key'           => 'field_mauswp_search_enable_taxonomy_search',
					'label'         => __( 'Buscar por categorías/tags', 'mauswp' ),
					'name'          => 'mauswp_search_enable_taxonomy_search',
					'type'          => 'true_false',
					'instructions'  => __( 'Si no hay coincidencias exactas, buscar productos por categorías y tags que contengan el texto escrito.', 'mauswp' ),
					'default_value' => 1,
					'ui'            => 1,
				],
				[
					'key'           => 'field_mauswp_search_suggestions_heading',
					'label'         => __( 'Título sugerencias por categoría', 'mauswp' ),
					'name'          => 'mauswp_search_suggestions_heading',
					'type'          => 'text',
					'instructions'  => __( 'Texto mostrado cuando se sugieren productos de una categoría o tag coincidente.', 'mauswp' ),
					'default_value' => __( '¿Quizás buscabas...?', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_search_popular_heading',
					'label'         => __( 'Título productos populares', 'mauswp' ),
					'name'          => 'mauswp_search_popular_heading',
					'type'          => 'text',
					'instructions'  => __( 'Texto mostrado cuando se muestran los productos populares como fallback.', 'mauswp' ),
					'default_value' => __( 'Productos populares', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_search_no_results_text',
					'label'         => __( 'Texto sin resultados exactos', 'mauswp' ),
					'name'          => 'mauswp_search_no_results_text',
					'type'          => 'text',
					'instructions'  => __( 'Texto introductorio cuando no hay coincidencias exactas. El término buscado se añade automáticamente.', 'mauswp' ),
					'default_value' => __( 'No hay productos para', 'mauswp' ),
				],
			],
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'mauswp-theme-options-search',
					],
				],
			],
		]
	);

	acf_add_local_field_group(
		[
			'key'    => 'group_mauswp_footer_options',
			'title'  => __( 'Footer', 'mauswp' ),
			'fields' => [
				[
					'key'           => 'field_mauswp_footer_logo',
					'label'         => __( 'Logo primera columna', 'mauswp' ),
					'name'          => 'mauswp_footer_logo',
					'type'          => 'image',
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'library'       => 'all',
				],
				[
					'key'           => 'field_mauswp_footer_column_1_title',
					'label'         => __( 'Título primera columna', 'mauswp' ),
					'name'          => 'mauswp_footer_column_1_title',
					'type'          => 'text',
					'default_value' => get_bloginfo( 'name' ),
				],
				[
					'key'           => 'field_mauswp_footer_column_1_text',
					'label'         => __( 'Texto primera columna', 'mauswp' ),
					'name'          => 'mauswp_footer_column_1_text',
					'type'          => 'textarea',
					'rows'          => 4,
					'new_lines'     => 'br',
					'default_value' => __( 'Base provisional del nuevo entorno corporativo para ejes, enganches y soluciones de arrastre robustas para remolques industriales y agrícolas.', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_footer_column_2_title',
					'label'         => __( 'Título segunda columna', 'mauswp' ),
					'name'          => 'mauswp_footer_column_2_title',
					'type'          => 'text',
					'default_value' => __( 'Navegación', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_footer_column_3_title',
					'label'         => __( 'Título tercera columna', 'mauswp' ),
					'name'          => 'mauswp_footer_column_3_title',
					'type'          => 'text',
					'default_value' => __( 'Contacto', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_footer_contact_phone',
					'label'         => __( 'Teléfono footer', 'mauswp' ),
					'name'          => 'mauswp_footer_contact_phone',
					'type'          => 'text',
					'default_value' => '+34 900 000 000',
				],
				[
					'key'           => 'field_mauswp_footer_contact_email',
					'label'         => __( 'Email footer', 'mauswp' ),
					'name'          => 'mauswp_footer_contact_email',
					'type'          => 'email',
					'default_value' => 'info@ejespararemolques.com',
				],
				[
					'key'           => 'field_mauswp_footer_subfooter_text',
					'label'         => __( 'Texto subfooter', 'mauswp' ),
					'name'          => 'mauswp_footer_subfooter_text',
					'type'          => 'text',
					'default_value' => __( 'Todos los derechos reservados.', 'mauswp' ),
				],
			],
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'mauswp-theme-options-footer',
					],
				],
			],
		]
	);

	acf_add_local_field_group(
		[
			'key'    => 'group_mauswp_megamenu_options',
			'title'  => __( 'Megamenu', 'mauswp' ),
			'fields' => [
				[
					'key'           => 'field_mauswp_catalog_button_label',
					'label'         => __( 'Texto del botón Catálogo', 'mauswp' ),
					'name'          => 'mauswp_catalog_button_label',
					'type'          => 'text',
					'default_value' => __( 'Catálogo', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_catalog_mega_eyebrow',
					'label'         => __( 'Eyebrow del mega menú', 'mauswp' ),
					'name'          => 'mauswp_catalog_mega_eyebrow',
					'type'          => 'text',
					'default_value' => __( 'Catálogo', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_catalog_mega_title',
					'label'         => __( 'Título del mega menú', 'mauswp' ),
					'name'          => 'mauswp_catalog_mega_title',
					'type'          => 'text',
					'default_value' => __( 'Explora nuestras categorías', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_catalog_mega_cta_label',
					'label'         => __( 'CTA del mega menú', 'mauswp' ),
					'name'          => 'mauswp_catalog_mega_cta_label',
					'type'          => 'text',
					'instructions'  => __( 'Texto del enlace en la parte inferior del mega menú.', 'mauswp' ),
					'default_value' => __( 'Ver catálogo completo', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_catalog_mega_image',
					'label'         => __( 'Imagen mega menú catálogo', 'mauswp' ),
					'name'          => 'mauswp_catalog_mega_image',
					'type'          => 'image',
					'instructions'  => __( 'Imagen lateral para el mega menú de Catálogo.', 'mauswp' ),
					'return_format' => 'array',
					'preview_size'  => 'medium',
					'library'       => 'all',
				],
				[
					'key'           => 'field_mauswp_catalog_mega_included_categories',
					'label'         => __( 'Categorías a mostrar', 'mauswp' ),
					'name'          => 'mauswp_catalog_mega_included_categories',
					'type'          => 'taxonomy',
					'instructions'  => __( 'Selecciona solo las categorías que quieres mostrar en el mega menú. Si dejas vacío, se mostrarán todas.', 'mauswp' ),
					'taxonomy'      => 'product_cat',
					'field_type'    => 'multi_select',
					'return_format' => 'id',
					'allow_null'    => 1,
					'multiple'      => 1,
					'add_term'      => 0,
					'save_terms'    => 0,
					'load_terms'    => 0,
				],
				[
					'key'           => 'field_mauswp_catalog_mega_excluded_categories',
					'label'         => __( 'Ocultar categorías en mega menú', 'mauswp' ),
					'name'          => 'mauswp_catalog_mega_excluded_categories',
					'type'          => 'taxonomy',
					'instructions'  => __( 'Selecciona las categorías de producto que no quieres mostrar en el mega menú. Solo aplica si has dejado vacío el campo anterior.', 'mauswp' ),
					'taxonomy'      => 'product_cat',
					'field_type'    => 'multi_select',
					'return_format' => 'id',
					'allow_null'    => 1,
					'multiple'      => 1,
					'add_term'      => 0,
					'save_terms'    => 0,
					'load_terms'    => 0,
				],
			],
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'mauswp-theme-options-megamenu',
					],
				],
			],
		]
	);

	acf_add_local_field_group(
		[
			'key'    => 'group_mauswp_shop_options',
			'title'  => __( 'Tienda', 'mauswp' ),
			'fields' => [
				[
					'key'           => 'field_mauswp_shop_notice_enabled',
					'label'         => __( 'Activar aviso en carrito/checkout', 'mauswp' ),
					'name'          => 'mauswp_shop_notice_enabled',
					'type'          => 'true_false',
					'instructions'  => __( 'Activa o desactiva el mensaje de aviso visible en carrito y/o checkout.', 'mauswp' ),
					'default_value' => 0,
					'ui'            => 1,
					'ui_on_text'    => __( 'Sí', 'mauswp' ),
					'ui_off_text'   => __( 'No', 'mauswp' ),
				],
				[
					'key'           => 'field_mauswp_shop_notice_text',
					'label'         => __( 'Texto del aviso', 'mauswp' ),
					'name'          => 'mauswp_shop_notice_text',
					'type'          => 'textarea',
					'instructions'  => __( 'Mensaje que se mostrará dentro del aviso. Salto de línea con Enter.', 'mauswp' ),
					'rows'          => 4,
					'new_lines'     => 'br',
				],
				[
					'key'           => 'field_mauswp_shop_notice_show_on',
					'label'         => __( 'Dónde mostrar el aviso', 'mauswp' ),
					'name'          => 'mauswp_shop_notice_show_on',
					'type'          => 'checkbox',
					'instructions'  => __( 'Selecciona en qué páginas debe aparecer el aviso.', 'mauswp' ),
					'choices'       => [
						'cart'     => __( 'Carrito', 'mauswp' ),
						'checkout' => __( 'Checkout', 'mauswp' ),
					],
					'default_value' => [ 'cart', 'checkout' ],
					'layout'        => 'horizontal',
					'allow_null'    => 0,
					'multiple'      => 1,
				],
			],
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'mauswp-theme-options-shop',
					],
				],
			],
		]
	);
}
add_action( 'acf/init', 'mauswp_register_theme_options_fields' );
