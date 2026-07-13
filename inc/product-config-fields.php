<?php
/**
 * WooCommerce configurable product fields.
 *
 * @package MausWP
 */

declare(strict_types=1);

/**
 * Get configurable product field definitions.
 *
 * @return array<string, array<string, mixed>>
 */
function mauswp_get_product_config_fields(): array {
	return [
		'cota_a' => [
			'label'      => __( 'Cota A', 'mauswp' ),
			'type'       => 'text',
			'enable_key' => '_mauswp_enable_cota_a',
		],
		'cota_b' => [
			'label'      => __( 'Cota B', 'mauswp' ),
			'type'       => 'text',
			'enable_key' => '_mauswp_enable_cota_b',
		],
		'cota_h' => [
			'label'      => __( 'Cota H', 'mauswp' ),
			'type'       => 'text',
			'enable_key' => '_mauswp_enable_cota_h',
		],
		'tipo_montaje' => [
			'label'      => __( 'Tipo de montaje', 'mauswp' ),
			'type'       => 'select',
			'enable_key' => '_mauswp_enable_tipo_montaje',
			'options'    => [
				'eje_simple'          => __( 'Eje simple', 'mauswp' ),
				'eje_delantero_tandem' => __( 'Eje delantero Tandem', 'mauswp' ),
				'eje_trasero_tandem'   => __( 'Eje trasero tandem', 'mauswp' ),
				'dos_ejes_tandem'      => __( '2 ejes tandem', 'mauswp' ),
			],
		],
		'calidad' => [
			'label'      => __( 'Calidad', 'mauswp' ),
			'type'       => 'select',
			'enable_key' => '_mauswp_enable_calidad',
			'options'    => [
				'galvanizado'     => __( 'Galvanizado', 'mauswp' ),
				'sin_galvanizar'  => __( 'Sin Galvanizar', 'mauswp' ),
			],
		],
		'modelo_rueda' => [
			'label'      => __( 'Modelo de rueda', 'mauswp' ),
			'type'       => 'select',
			'enable_key' => '_mauswp_enable_modelo_rueda',
			'options'    => [
				'seat'       => __( 'Seat', 'mauswp' ),
				'mercedes'   => __( 'Mercedes', 'mauswp' ),
				'suzuki'     => __( 'Suzuki', 'mauswp' ),
				'land_rover' => __( 'Land Rover', 'mauswp' ),
				'nissan'     => __( 'Nissan', 'mauswp' ),
			],
		],
	];
}

/**
 * Normalize a measurement value posted by the customer.
 *
 * @param string $value Raw value.
 * @return int|null
 */
function mauswp_normalize_product_measurement_value( string $value ): ?int {
	$value = trim( $value );

	if ( '' === $value || ! preg_match( '/^\d+$/', $value ) ) {
		return null;
	}

	return (int) $value;
}

/**
 * Get the minimum allowed difference between Cota B and Cota A.
 *
 * @param int $product_id Product ID.
 * @return int
 */
function mauswp_get_product_cota_ab_min_difference( int $product_id ): int {
	$value = get_post_meta( $product_id, '_mauswp_cota_ab_min_difference', true );

	if ( ! is_scalar( $value ) || '' === trim( (string) $value ) ) {
		return 0;
	}

	$normalized_value = mauswp_normalize_product_measurement_value( (string) $value );

	return null !== $normalized_value && $normalized_value > 0 ? $normalized_value : 0;
}

/**
 * Get enabled product config fields for a product.
 *
 * @param int $product_id Product ID.
 * @return array<string, array<string, mixed>>
 */
function mauswp_get_enabled_product_config_fields( int $product_id ): array {
	$fields         = mauswp_get_product_config_fields();
	$enabled_fields = [];

	foreach ( $fields as $field_key => $field ) {
		$enable_key = isset( $field['enable_key'] ) ? (string) $field['enable_key'] : '';

		if ( '' === $enable_key ) {
			continue;
		}

		if ( 'yes' === get_post_meta( $product_id, $enable_key, true ) ) {
			$enabled_fields[ $field_key ] = $field;
		}
	}

	return $enabled_fields;
}

/**
 * Output admin toggles for configurable product fields.
 */
function mauswp_render_product_config_field_toggles(): void {
	global $post;

	if ( ! $post instanceof WP_Post ) {
		return;
	}

	$fields = mauswp_get_product_config_fields();

	echo '<div class="options_group">';
	echo '<p class="form-field"><strong>' . esc_html__( 'Campos configurables del producto', 'mauswp' ) . '</strong><br><span class="description">' . esc_html__( 'Activa solo los campos que deban mostrarse en la ficha y enviarse con el pedido.', 'mauswp' ) . '</span></p>';

	foreach ( $fields as $field ) {
		$enable_key = isset( $field['enable_key'] ) ? (string) $field['enable_key'] : '';
		$label      = isset( $field['label'] ) ? (string) $field['label'] : '';

		if ( '' === $enable_key || '' === $label ) {
			continue;
		}

		woocommerce_wp_checkbox(
			[
				'id'          => $enable_key,
				'label'       => sprintf( __( 'Mostrar %s', 'mauswp' ), $label ),
				'value'       => get_post_meta( $post->ID, $enable_key, true ),
				'desc_tip'    => false,
				'description' => __( 'Se pedira al cliente y se guardara en el pedido.', 'mauswp' ),
			]
		);
	}

	woocommerce_wp_text_input(
		[
			'id'                => '_mauswp_cota_ab_min_difference',
			'label'             => __( 'Diferencia mínima Cota B - Cota A (mm)', 'mauswp' ),
			'value'             => get_post_meta( $post->ID, '_mauswp_cota_ab_min_difference', true ),
			'type'              => 'number',
			'desc_tip'          => false,
			'description'       => __( 'Valor mínimo permitido para la diferencia entre Cota B y Cota A. Se valida en la ficha si ambos campos están activos.', 'mauswp' ),
			'custom_attributes' => [
				'min'  => '0',
				'step' => '1',
			],
		]
	);

	echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'mauswp_render_product_config_field_toggles' );

/**
 * Save admin toggles for configurable product fields.
 *
 * @param int $post_id Product ID.
 */
function mauswp_save_product_config_field_toggles( int $post_id ): void {
	foreach ( mauswp_get_product_config_fields() as $field ) {
		$enable_key = isset( $field['enable_key'] ) ? (string) $field['enable_key'] : '';

		if ( '' === $enable_key ) {
			continue;
		}

		update_post_meta( $post_id, $enable_key, isset( $_POST[ $enable_key ] ) ? 'yes' : 'no' );
	}

	$min_difference = isset( $_POST['_mauswp_cota_ab_min_difference'] )
		? wp_unslash( $_POST['_mauswp_cota_ab_min_difference'] )
		: '';

	$normalized_min_difference = is_string( $min_difference ) ? mauswp_normalize_product_measurement_value( $min_difference ) : null;

	update_post_meta( $post_id, '_mauswp_cota_ab_min_difference', null !== $normalized_min_difference && $normalized_min_difference > 0 ? (string) $normalized_min_difference : '' );
}
add_action( 'woocommerce_process_product_meta', 'mauswp_save_product_config_field_toggles' );

/**
 * Render configurable fields in add to cart form.
 */
function mauswp_render_product_config_fields(): void {
	global $product;

	if ( ! $product instanceof WC_Product ) {
		return;
	}

	$fields = mauswp_get_enabled_product_config_fields( $product->get_id() );
	$config_title = function_exists( 'get_field' ) ? (string) get_field( 'mauswp_product_config_title', 'option' ) : '';
	$config_text  = function_exists( 'get_field' ) ? (string) get_field( 'mauswp_product_config_text', 'option' ) : '';
	$help_text    = function_exists( 'get_field' ) ? (string) get_field( 'mauswp_product_config_help_text', 'option' ) : '';
	$modal_id     = 'mauswp-product-config-help-' . $product->get_id();
	$min_difference = mauswp_get_product_cota_ab_min_difference( $product->get_id() );

	if ( '' === trim( $config_title ) ) {
		$config_title = __( 'Configuración', 'mauswp' );
	}

	if ( '' === trim( $config_text ) ) {
		$config_text = __( 'Completa estas medidas y opciones antes de añadir el producto al pedido.', 'mauswp' );
	}

	if ( empty( $fields ) ) {
		return;
	}

	echo '<div class="shop-product__config">';
	echo '<div class="shop-product__config-header">';
	echo '<div class="shop-product__config-copy">';
	echo '<p class="shop-product__config-kicker">' . esc_html( $config_title ) . '</p>';
	echo '<p class="shop-product__config-text">' . esc_html( $config_text ) . '</p>';
	echo '</div>';

	if ( '' !== trim( wp_strip_all_tags( $help_text ) ) ) {
		echo '<button class="shop-product__config-info" type="button" data-product-config-info-open aria-controls="' . esc_attr( $modal_id ) . '" aria-expanded="false">';
		echo '<span class="shop-product__config-info-icon" aria-hidden="true">i</span>';
		echo '<span class="screen-reader-text">' . esc_html__( 'Abrir ayuda de configuración', 'mauswp' ) . '</span>';
		echo '</button>';
	}
	echo '</div>';
	echo '<div class="shop-product__config-grid">';

	foreach ( $fields as $field_key => $field ) {
		$field_id = 'mauswp_' . $field_key;
		$label    = isset( $field['label'] ) ? (string) $field['label'] : '';
		$type     = isset( $field['type'] ) ? (string) $field['type'] : 'text';

		$field_classes = 'shop-product__config-field';

		if ( in_array( $field_key, [ 'cota_a', 'cota_b', 'cota_h' ], true ) ) {
			$field_classes .= ' shop-product__config-field--dimension';
		}

		echo '<p class="' . esc_attr( $field_classes ) . '">';
		echo '<label class="shop-product__config-label" for="' . esc_attr( $field_id ) . '">' . esc_html( $label ) . '</label>';

		if ( 'select' === $type ) {
			echo '<select class="shop-product__config-select" id="' . esc_attr( $field_id ) . '" name="mauswp_product_config[' . esc_attr( $field_key ) . ']" required>';
			echo '<option value="">' . esc_html__( 'Selecciona una opcion', 'mauswp' ) . '</option>';

			$options = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : [];

			foreach ( $options as $option_value => $option_label ) {
				echo '<option value="' . esc_attr( (string) $option_value ) . '">' . esc_html( (string) $option_label ) . '</option>';
			}

			echo '</select>';
		} else {
			if ( in_array( $field_key, [ 'cota_a', 'cota_b', 'cota_h' ], true ) ) {
				echo '<span class="shop-product__config-input-wrap">';
				echo '<input class="shop-product__config-input shop-product__config-input--with-unit" type="number" id="' . esc_attr( $field_id ) . '" name="mauswp_product_config[' . esc_attr( $field_key ) . ']" value="" required inputmode="numeric" min="0" step="1">';
				echo '<span class="shop-product__config-unit" aria-hidden="true">mm</span>';
				echo '</span>';
			} else {
				echo '<input class="shop-product__config-input" type="text" id="' . esc_attr( $field_id ) . '" name="mauswp_product_config[' . esc_attr( $field_key ) . ']" value="" required>';
			}
		}

		echo '</p>';
	}

	echo '</div>';

	if ( isset( $fields['cota_a'], $fields['cota_b'] ) && $min_difference > 0 ) {
		echo '<p class="shop-product__config-note">';
		echo esc_html( sprintf( __( '* La diferencia entre Cota B y Cota A debe ser mayor de: %s mm', 'mauswp' ), wc_format_localized_price( (string) $min_difference ) ) );
		echo '</p>';
	}

	if ( '' !== trim( wp_strip_all_tags( $help_text ) ) ) {
		echo '<div class="shop-product__config-modal" id="' . esc_attr( $modal_id ) . '" hidden data-product-config-info-modal>';
		echo '<div class="shop-product__config-modal-backdrop" data-product-config-info-close></div>';
		echo '<div class="shop-product__config-modal-dialog" role="dialog" aria-modal="true" aria-label="' . esc_attr__( 'Ayuda de configuración', 'mauswp' ) . '">';
		echo '<button class="shop-product__config-modal-close" type="button" data-product-config-info-close aria-label="' . esc_attr__( 'Cerrar ayuda', 'mauswp' ) . '">&times;</button>';
		echo '<div class="shop-product__config-modal-content entry-content">' . wp_kses_post( $help_text ) . '</div>';
		echo '</div>';
		echo '</div>';
	}

	echo '</div>';
}
add_action( 'woocommerce_before_add_to_cart_button', 'mauswp_render_product_config_fields', 15 );

/**
 * Validate configurable fields before add to cart.
 *
 * @param bool $passed     Validation state.
 * @param int  $product_id Product ID.
 * @return bool
 */
function mauswp_validate_product_config_fields( bool $passed, int $product_id ): bool {
	$fields       = mauswp_get_enabled_product_config_fields( $product_id );
	$posted_values = isset( $_POST['mauswp_product_config'] ) && is_array( $_POST['mauswp_product_config'] )
		? wp_unslash( $_POST['mauswp_product_config'] )
		: [];

	foreach ( $fields as $field_key => $field ) {
		$label = isset( $field['label'] ) ? (string) $field['label'] : $field_key;
		$type  = isset( $field['type'] ) ? (string) $field['type'] : 'text';
		$value = isset( $posted_values[ $field_key ] ) ? trim( (string) $posted_values[ $field_key ] ) : '';

		if ( '' === $value ) {
			wc_add_notice( sprintf( __( 'Debes completar el campo %s.', 'mauswp' ), $label ), 'error' );
			$passed = false;
			continue;
		}

		if ( 'select' === $type ) {
			$options = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : [];

			if ( ! array_key_exists( $value, $options ) ) {
				wc_add_notice( sprintf( __( 'Selecciona una opcion valida para %s.', 'mauswp' ), $label ), 'error' );
				$passed = false;
			}
		} elseif ( in_array( $field_key, [ 'cota_a', 'cota_b', 'cota_h' ], true ) && null === mauswp_normalize_product_measurement_value( $value ) ) {
			wc_add_notice( sprintf( __( 'El campo %s debe ser un valor numérico en mm.', 'mauswp' ), $label ), 'error' );
			$passed = false;
		}
	}

	if ( isset( $fields['cota_a'], $fields['cota_b'] ) ) {
		$min_difference = mauswp_get_product_cota_ab_min_difference( $product_id );
		$cota_a_value   = isset( $posted_values['cota_a'] ) ? mauswp_normalize_product_measurement_value( (string) $posted_values['cota_a'] ) : null;
		$cota_b_value   = isset( $posted_values['cota_b'] ) ? mauswp_normalize_product_measurement_value( (string) $posted_values['cota_b'] ) : null;

		if ( $min_difference > 0 && null !== $cota_a_value && null !== $cota_b_value && ( $cota_b_value - $cota_a_value ) < $min_difference ) {
			wc_add_notice( sprintf( __( 'La diferencia entre Cota B y Cota A debe ser mayor o igual que %s mm.', 'mauswp' ), wc_format_localized_price( (string) $min_difference ) ), 'error' );
			$passed = false;
		}
	}

	return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'mauswp_validate_product_config_fields', 10, 2 );

/**
 * Normalize posted configurable field values.
 *
 * @param int $product_id Product ID.
 * @return array<string, string>
 */
function mauswp_get_posted_product_config_values( int $product_id ): array {
	$fields        = mauswp_get_enabled_product_config_fields( $product_id );
	$posted_values = isset( $_POST['mauswp_product_config'] ) && is_array( $_POST['mauswp_product_config'] )
		? wp_unslash( $_POST['mauswp_product_config'] )
		: [];
	$values        = [];

	foreach ( $fields as $field_key => $field ) {
		$type  = isset( $field['type'] ) ? (string) $field['type'] : 'text';
		$value = isset( $posted_values[ $field_key ] ) ? trim( (string) $posted_values[ $field_key ] ) : '';

		if ( 'select' === $type ) {
			$options = isset( $field['options'] ) && is_array( $field['options'] ) ? $field['options'] : [];

			if ( array_key_exists( $value, $options ) ) {
				$values[ $field_key ] = (string) $options[ $value ];
			}
		} elseif ( '' !== $value ) {
			$values[ $field_key ] = sanitize_text_field( $value );
		}
	}

	return $values;
}

/**
 * Save configurable field values into cart item data.
 *
 * @param array<string, mixed> $cart_item_data Cart item data.
 * @param int                  $product_id     Product ID.
 * @return array<string, mixed>
 */
function mauswp_add_product_config_to_cart_item( array $cart_item_data, int $product_id ): array {
	$values = mauswp_get_posted_product_config_values( $product_id );

	if ( empty( $values ) ) {
		return $cart_item_data;
	}

	$cart_item_data['mauswp_product_config'] = $values;
	$cart_item_data['unique_key']            = md5( wp_json_encode( $values ) );

	return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'mauswp_add_product_config_to_cart_item', 10, 2 );

/**
 * Show configurable field values in cart and checkout.
 *
 * @param array<int, array<string, string>> $item_data Existing item data.
 * @param array<string, mixed>              $cart_item Cart item.
 * @return array<int, array<string, string>>
 */
function mauswp_render_product_config_cart_item_data( array $item_data, array $cart_item ): array {
	if ( empty( $cart_item['mauswp_product_config'] ) || ! is_array( $cart_item['mauswp_product_config'] ) ) {
		return $item_data;
	}

	$fields = mauswp_get_product_config_fields();

	foreach ( $cart_item['mauswp_product_config'] as $field_key => $value ) {
		if ( ! isset( $fields[ $field_key ]['label'] ) ) {
			continue;
		}

		$item_data[] = [
			'key'   => (string) $fields[ $field_key ]['label'],
			'value' => wc_clean( (string) $value ),
		];
	}

	return $item_data;
}
add_filter( 'woocommerce_get_item_data', 'mauswp_render_product_config_cart_item_data', 10, 2 );

/**
 * Persist configurable field values into order items.
 *
 * @param WC_Order_Item_Product $item          Order item.
 * @param string                $cart_item_key Cart item key.
 * @param array<string, mixed>  $values        Cart item values.
 */
function mauswp_add_product_config_to_order_item( WC_Order_Item_Product $item, string $cart_item_key, array $values ): void {
	unset( $cart_item_key );

	if ( empty( $values['mauswp_product_config'] ) || ! is_array( $values['mauswp_product_config'] ) ) {
		return;
	}

	$fields = mauswp_get_product_config_fields();

	foreach ( $values['mauswp_product_config'] as $field_key => $value ) {
		if ( ! isset( $fields[ $field_key ]['label'] ) ) {
			continue;
		}

		$item->add_meta_data( (string) $fields[ $field_key ]['label'], wc_clean( (string) $value ), true );
	}
}
add_action( 'woocommerce_checkout_create_order_line_item', 'mauswp_add_product_config_to_order_item', 10, 3 );

/**
 * Check whether a cart item has configurable product data.
 *
 * @param array<string, mixed> $cart_item Cart item data.
 * @return bool
 */
function mauswp_cart_item_has_product_config( array $cart_item ): bool {
	return ! empty( $cart_item['mauswp_product_config'] ) && is_array( $cart_item['mauswp_product_config'] );
}

/**
 * Get configurable cart items keyed by cart item key.
 *
 * @return array<string, array<string, mixed>>
 */
function mauswp_get_configurable_cart_items(): array {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return [];
	}

	$configurable_items = [];

	foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		if ( ! is_string( $cart_item_key ) || ! is_array( $cart_item ) || ! mauswp_cart_item_has_product_config( $cart_item ) ) {
			continue;
		}

		$configurable_items[ $cart_item_key ] = $cart_item;
	}

	return $configurable_items;
}

/**
 * Determine if all configurable cart items are confirmed.
 *
 * @return bool
 */
function mauswp_are_configurable_cart_items_confirmed(): bool {
	foreach ( mauswp_get_configurable_cart_items() as $cart_item ) {
		if ( empty( $cart_item['mauswp_measurements_confirmed'] ) || 'yes' !== $cart_item['mauswp_measurements_confirmed'] ) {
			return false;
		}
	}

	return true;
}

/**
 * Restore custom confirmation flag from session.
 *
 * @param array<string, mixed> $cart_item Cart item.
 * @param array<string, mixed> $session_values Session values.
 * @return array<string, mixed>
 */
function mauswp_restore_product_config_confirmation_from_session( array $cart_item, array $session_values ): array {
	if ( isset( $session_values['mauswp_measurements_confirmed'] ) ) {
		$cart_item['mauswp_measurements_confirmed'] = 'yes' === $session_values['mauswp_measurements_confirmed'] ? 'yes' : 'no';
	}

	return $cart_item;
}
add_filter( 'woocommerce_get_cart_item_from_session', 'mauswp_restore_product_config_confirmation_from_session', 10, 2 );

/**
 * Handle configurable product confirmation form from cart.
 */
function mauswp_handle_product_config_confirmation_submission(): void {
	if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ?? '' ) ) {
		return;
	}

	if ( empty( $_POST['mauswp_confirm_product_config'] ) || empty( $_POST['mauswp_product_config_confirmation_nonce'] ) ) {
		return;
	}

	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mauswp_product_config_confirmation_nonce'] ) ), 'mauswp_confirm_product_config' ) ) {
		wc_add_notice( __( 'No se ha podido validar la confirmación de medidas. Vuelve a intentarlo.', 'mauswp' ), 'error' );
		return;
	}

	$configurable_items = mauswp_get_configurable_cart_items();

	if ( empty( $configurable_items ) ) {
		wp_safe_redirect( wc_get_checkout_url() );
		exit;
	}

	$submitted_confirmations = isset( $_POST['mauswp_config_confirmed'] ) && is_array( $_POST['mauswp_config_confirmed'] )
		? array_map( 'sanitize_text_field', wp_unslash( $_POST['mauswp_config_confirmed'] ) )
		: [];

	$all_confirmed = true;

	foreach ( $configurable_items as $cart_item_key => $cart_item ) {
		$is_confirmed = isset( $submitted_confirmations[ $cart_item_key ] ) && 'yes' === $submitted_confirmations[ $cart_item_key ];

		WC()->cart->cart_contents[ $cart_item_key ]['mauswp_measurements_confirmed'] = $is_confirmed ? 'yes' : 'no';

		if ( ! $is_confirmed ) {
			$all_confirmed = false;
		}
	}

	WC()->cart->set_session();

	if ( ! $all_confirmed ) {
		wc_add_notice( __( 'Debes confirmar las medidas de todos los productos configurados antes de continuar al checkout.', 'mauswp' ), 'error' );
		wp_safe_redirect( wc_get_cart_url() );
		exit;
	}

	wc_add_notice( __( 'Has confirmado correctamente las medidas de los productos configurados.', 'mauswp' ), 'success' );
	wp_safe_redirect( wc_get_checkout_url() );
	exit;
}
add_action( 'template_redirect', 'mauswp_handle_product_config_confirmation_submission' );

/**
 * Prevent access to checkout while configured products remain unconfirmed.
 */
function mauswp_require_product_config_confirmation_before_checkout(): void {
	if ( ! function_exists( 'is_checkout' ) || ! function_exists( 'WC' ) || ! WC()->cart ) {
		return;
	}

	if ( ! is_checkout() || is_order_received_page() || wp_doing_ajax() ) {
		return;
	}

	if ( empty( mauswp_get_configurable_cart_items() ) || mauswp_are_configurable_cart_items_confirmed() ) {
		return;
	}

	wc_add_notice( __( 'Antes de finalizar la compra debes confirmar que las medidas de los productos a medida son correctas.', 'mauswp' ), 'error' );
	wp_safe_redirect( wc_get_cart_url() );
	exit;
}
add_action( 'template_redirect', 'mauswp_require_product_config_confirmation_before_checkout', 20 );

/**
 * Render configurable product confirmation panel on cart page.
 */
function mauswp_render_product_config_confirmation_panel(): void {
	$configurable_items = mauswp_get_configurable_cart_items();

	if ( empty( $configurable_items ) ) {
		return;
	}

	echo '<section class="shop-config-confirmation shop-config-confirmation--with-checkout" aria-labelledby="shop-config-confirmation-title">';
	echo '<div class="shop-config-confirmation__header">';
	echo '<p class="eyebrow">' . esc_html__( 'Verificación de medidas', 'mauswp' ) . '</p>';
	echo '<h2 id="shop-config-confirmation-title" class="section-title">' . esc_html__( 'Confirma los productos hechos a medida', 'mauswp' ) . '</h2>';
	echo '<p class="shop-config-confirmation__intro">' . esc_html__( 'Estos productos se fabrican o preparan según las medidas indicadas y no admiten devolución. Revisa cada configuración y confirma que es correcta antes de continuar.', 'mauswp' ) . '</p>';
	echo '</div>';

	echo '<form class="shop-config-confirmation__form" method="post" action="' . esc_url( wc_get_cart_url() ) . '">';
	wp_nonce_field( 'mauswp_confirm_product_config', 'mauswp_product_config_confirmation_nonce' );
	echo '<input type="hidden" name="mauswp_confirm_product_config" value="1">';
	echo '<div class="shop-config-confirmation__list">';

	foreach ( $configurable_items as $cart_item_key => $cart_item ) {
		$product_name  = isset( $cart_item['data'] ) && $cart_item['data'] instanceof WC_Product ? $cart_item['data']->get_name() : __( 'Producto', 'mauswp' );
		$quantity      = isset( $cart_item['quantity'] ) ? (int) $cart_item['quantity'] : 1;
		$config_values = isset( $cart_item['mauswp_product_config'] ) && is_array( $cart_item['mauswp_product_config'] ) ? $cart_item['mauswp_product_config'] : [];
		$is_confirmed  = isset( $cart_item['mauswp_measurements_confirmed'] ) && 'yes' === $cart_item['mauswp_measurements_confirmed'];

		echo '<article class="shop-config-confirmation__item">';
		echo '<div class="shop-config-confirmation__item-head">';
		echo '<h3 class="shop-config-confirmation__item-title">' . esc_html( $product_name ) . ' <span>&times; ' . esc_html( (string) $quantity ) . '</span></h3>';
		echo '</div>';
		echo '<dl class="shop-config-confirmation__meta">';

		foreach ( $config_values as $config_label => $config_value ) {
			$fields = mauswp_get_product_config_fields();
			$label  = isset( $fields[ $config_label ]['label'] ) ? (string) $fields[ $config_label ]['label'] : (string) $config_label;

			echo '<dt>' . esc_html( $label ) . '</dt>';
			echo '<dd>' . esc_html( (string) $config_value ) . '</dd>';
		}

		echo '</dl>';
		echo '<label class="shop-config-confirmation__check">';
		echo '<input type="checkbox" name="mauswp_config_confirmed[' . esc_attr( $cart_item_key ) . ']" value="yes"' . checked( $is_confirmed, true, false ) . '>';
		echo '<span>' . esc_html__( 'Confirmo que estas medidas y opciones son correctas y acepto que este producto a medida no admite devolución.', 'mauswp' ) . '</span>';
		echo '</label>';
		echo '</article>';
	}

	echo '</div>';
	echo '<div class="shop-config-confirmation__actions">';
	echo '<button class="button alt shop-config-confirmation__submit" type="submit">' . esc_html__( 'Confirmar medidas y pasar al checkout', 'mauswp' ) . '</button>';
	echo '</div>';
	echo '</form>';
	echo '</section>';
}
add_action( 'woocommerce_before_cart_collaterals', 'mauswp_render_product_config_confirmation_panel', 5 );

/**
 * Add product measurement confirmation to order items.
 *
 * @param WC_Order_Item_Product $item  Order item.
 * @param string                $key   Cart item key.
 * @param array<string, mixed>  $values Cart item values.
 */
function mauswp_add_product_config_confirmation_to_order_item( WC_Order_Item_Product $item, string $key, array $values ): void {
	unset( $key );

	if ( empty( $values['mauswp_product_config'] ) || ! is_array( $values['mauswp_product_config'] ) ) {
		return;
	}

	$item->add_meta_data(
		__( 'Confirmación medidas', 'mauswp' ),
		! empty( $values['mauswp_measurements_confirmed'] ) && 'yes' === $values['mauswp_measurements_confirmed'] ? __( 'Aceptada por el cliente', 'mauswp' ) : __( 'Pendiente', 'mauswp' ),
		true
	);
}
add_action( 'woocommerce_checkout_create_order_line_item', 'mauswp_add_product_config_confirmation_to_order_item', 20, 3 );
