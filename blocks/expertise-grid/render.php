<?php
/**
 * Expertise grid block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$anchor        = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : '';
$align_class   = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignwide';
$block_classes = [ 'expertise-grid-block', $align_class ];
$eyebrow       = (string) ( get_field( 'eyebrow' ) ?: __( 'Áreas de especialización', 'mauswp' ) );
$title         = (string) ( get_field( 'title' ) ?: __( 'Sectores donde aportamos más valor', 'mauswp' ) );
$intro         = (string) ( get_field( 'intro' ) ?: '' );
$items         = get_field( 'items' );

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

$cards = [];

if ( is_array( $items ) ) {
	foreach ( $items as $item ) {
		if ( ! is_array( $item ) ) {
			continue;
		}

		$image = ! empty( $item['image'] ) && is_array( $item['image'] ) ? $item['image'] : [];

		$cards[] = [
			'image_id'    => ! empty( $image['ID'] ) ? (int) $image['ID'] : 0,
			'image_url'   => ! empty( $image['sizes']['large'] ) ? (string) $image['sizes']['large'] : ( ! empty( $image['url'] ) ? (string) $image['url'] : '' ),
			'image_alt'   => ! empty( $image['alt'] ) ? (string) $image['alt'] : '',
			'highlight'   => ! empty( $item['highlight'] ) ? (string) $item['highlight'] : '',
			'title'       => ! empty( $item['title'] ) ? (string) $item['title'] : '',
			'description' => ! empty( $item['description'] ) ? (string) $item['description'] : '',
		];
	}
}

if ( empty( $cards ) ) {
	$cards = [
		[
			'image_id'    => 0,
			'image_url'   => '',
			'image_alt'   => '',
			'highlight'   => __( '24/48h', 'mauswp' ),
			'title'       => __( 'Atención especializada a feriantes', 'mauswp' ),
			'description' => __( 'Fabricación a medida y reposición rápida de ejes, enganches y recambios para remolques de feria.', 'mauswp' ),
		],
		[
			'image_id'    => 0,
			'image_url'   => '',
			'image_alt'   => '',
			'highlight'   => __( 'O1 / O2', 'mauswp' ),
			'title'       => __( 'Remolques categoría O1 y O2', 'mauswp' ),
			'description' => __( 'Soluciones para remolques de coche con y sin freno, desde ejes fabricados a medida hasta recambios de distintas marcas.', 'mauswp' ),
		],
		[
			'image_id'    => 0,
			'image_url'   => '',
			'image_alt'   => '',
			'highlight'   => __( 'Náutico', 'mauswp' ),
			'title'       => __( 'Sector náutico', 'mauswp' ),
			'description' => __( 'Ejes y componentes preparados para entorno marino, con configuraciones adaptadas al transporte de embarcaciones.', 'mauswp' ),
		],
		[
			'image_id'    => 0,
			'image_url'   => '',
			'image_alt'   => '',
			'highlight'   => __( 'Agrícola / Industrial', 'mauswp' ),
			'title'       => __( 'Aplicaciones agrícolas e industriales', 'mauswp' ),
			'description' => __( 'Distribución de ejes, enganches, ruedas, ballestas y otros componentes para usos profesionales y maquinaria auxiliar.', 'mauswp' ),
		],
	];
}
?>
<section
	<?php if ( '' !== $anchor ) : ?>
		id="<?php echo esc_attr( $anchor ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
>
	<div class="expertise-grid-block__header">
		<div class="expertise-grid-block__intro">
			<?php if ( '' !== $eyebrow ) : ?>
				<p class="expertise-grid-block__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== $title ) : ?>
				<h2 class="expertise-grid-block__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
		</div>

		<?php if ( '' !== $intro ) : ?>
			<div class="expertise-grid-block__text">
				<?php echo wp_kses_post( wpautop( $intro ) ); ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="expertise-grid-block__grid">
		<?php foreach ( $cards as $card ) : ?>
			<article class="expertise-grid-block__card">
				<div class="expertise-grid-block__media">
					<?php if ( $card['image_id'] > 0 ) : ?>
						<?php echo wp_get_attachment_image( $card['image_id'], 'large', false, [ 'class' => 'expertise-grid-block__image', 'alt' => $card['image_alt'], 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
					<?php elseif ( '' !== $card['image_url'] ) : ?>
						<img class="expertise-grid-block__image" src="<?php echo esc_url( $card['image_url'] ); ?>" alt="<?php echo esc_attr( $card['image_alt'] ); ?>" loading="lazy" decoding="async" />
					<?php else : ?>
						<div class="expertise-grid-block__placeholder" aria-hidden="true"></div>
					<?php endif; ?>
				</div>

				<div class="expertise-grid-block__body">
					<?php if ( '' !== $card['highlight'] ) : ?>
						<p class="expertise-grid-block__highlight"><?php echo esc_html( $card['highlight'] ); ?></p>
					<?php endif; ?>

					<?php if ( '' !== $card['title'] ) : ?>
						<h3 class="expertise-grid-block__card-title"><?php echo esc_html( $card['title'] ); ?></h3>
					<?php endif; ?>

					<?php if ( '' !== $card['description'] ) : ?>
						<div class="expertise-grid-block__description">
							<?php echo wp_kses_post( wpautop( $card['description'] ) ); ?>
						</div>
					<?php endif; ?>
				</div>
			</article>
		<?php endforeach; ?>
	</div>
</section>
