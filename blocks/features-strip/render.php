<?php
/**
 * Features strip block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$items         = get_field( 'items' );
$anchor        = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : '';
$align_class   = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignwide';
$block_classes = [ 'features-strip-block', $align_class ];

if ( ! empty( $block['className'] ) ) {
	$custom_classes = preg_split( '/\s+/', (string) $block['className'] );

	if ( is_array( $custom_classes ) ) {
		foreach ( $custom_classes as $custom_class ) {
			if ( '' !== $custom_class ) {
				$block_classes[] = sanitize_html_class( $custom_class );
			}
		}
	}
}

$features = [];

if ( is_array( $items ) ) {
	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$icon_url = '';

		if ( ! empty( $item['icon'] ) && is_array( $item['icon'] ) && ! empty( $item['icon']['url'] ) ) {
			$icon_url = (string) $item['icon']['url'];
		}

		$features[] = [
			'icon_url' => $icon_url,
			'title'    => ! empty( $item['title'] ) ? (string) $item['title'] : '',
			'text'     => ! empty( $item['text'] ) ? (string) $item['text'] : '',
		];
	}
}

if ( empty( $features ) ) {
	$features = [
		[
			'icon_url' => '',
			'title'    => __( 'Fabricación a medida', 'mauswp' ),
			'text'     => __( 'Adaptamos cada pieza a tus especificaciones exactas.', 'mauswp' ),
		],
		[
			'icon_url' => '',
			'title'    => __( 'Galvanizado y sin galvanizar', 'mauswp' ),
			'text'     => __( 'Elige el acabado que mejor se adapta a tu uso y entorno.', 'mauswp' ),
		],
		[
			'icon_url' => '',
			'title'    => __( 'Envío a toda España', 'mauswp' ),
			'text'     => __( 'Consulta plazos y condiciones según tu destino.', 'mauswp' ),
		],
		[
			'icon_url' => '',
			'title'    => __( 'Recambios disponibles', 'mauswp' ),
			'text'     => __( 'Stock de piezas para mantenimiento y reparación.', 'mauswp' ),
		],
	];
}
?>
<section
	<?php if ( '' !== $anchor ) : ?>
		id="<?php echo esc_attr( $anchor ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
	aria-label="<?php esc_attr_e( 'Ventajas clave', 'mauswp' ); ?>"
>
	<div class="features-strip-block__panel">
		<div class="features-strip-block__swiper swiper" data-features-strip-swiper>
		<ul class="features-strip-block__list swiper-wrapper">
		<?php foreach ( $features as $feature ) : ?>
			<li class="features-strip-block__item swiper-slide">
				<div class="features-strip-block__icon-wrap">
					<?php if ( '' !== $feature['icon_url'] ) : ?>
						<img class="features-strip-block__icon" src="<?php echo esc_url( $feature['icon_url'] ); ?>" alt="" loading="lazy">
					<?php else : ?>
						<span class="features-strip-block__icon-fallback" aria-hidden="true"></span>
					<?php endif; ?>
				</div>
				<?php if ( '' !== $feature['title'] ) : ?>
					<p class="features-strip-block__title"><?php echo esc_html( $feature['title'] ); ?></p>
				<?php endif; ?>
				<?php if ( '' !== $feature['text'] ) : ?>
					<p class="features-strip-block__text"><?php echo esc_html( $feature['text'] ); ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ul>
		</div>
	</div>
</section>
