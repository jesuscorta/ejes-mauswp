<?php
/**
 * Hero block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$slides        = get_field( 'slides' );
$anchor        = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : '';
$align_class   = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignfull';
$block_classes = [ 'hero-block', $align_class ];
$carousel_id   = 'hero-carousel-' . wp_unique_id();

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

$items = [];

if ( is_array( $slides ) ) {
	foreach ( $slides as $slide ) {
		if ( ! is_array( $slide ) ) {
			continue;
		}

		$background_url = '';
		$background_id  = 0;
		$title          = ! empty( $slide['title'] ) ? (string) $slide['title'] : '';
		$cta_link       = ! empty( $slide['cta_link'] ) && is_array( $slide['cta_link'] ) ? $slide['cta_link'] : [];

		if ( ! empty( $slide['background_image'] ) && is_array( $slide['background_image'] ) && ! empty( $slide['background_image']['url'] ) ) {
			$background_url = (string) $slide['background_image']['url'];
			$background_id  = ! empty( $slide['background_image']['ID'] ) ? (int) $slide['background_image']['ID'] : 0;
		}

		if ( '' === $title ) {
			$title = __( 'Soluciones de ejes y enganches para remolques a medida.', 'mauswp' );
		}

		$cta_url    = ! empty( $cta_link['url'] ) ? (string) $cta_link['url'] : '';
		$cta_label  = ! empty( $cta_link['title'] ) ? (string) $cta_link['title'] : '';
		$cta_target = ! empty( $cta_link['target'] ) ? (string) $cta_link['target'] : '';
		$cta_rel    = '_blank' === $cta_target ? 'noopener noreferrer' : '';

		$items[] = [
			'background_url' => $background_url,
			'background_id'  => $background_id,
			'title'          => $title,
			'cta_url'        => $cta_url,
			'cta_label'      => $cta_label,
			'cta_target'     => $cta_target,
			'cta_rel'        => $cta_rel,
		];
	}
}

if ( empty( $items ) ) {
	$items[] = [
		'background_url' => '',
		'title'          => __( 'Soluciones de ejes y enganches para remolques a medida.', 'mauswp' ),
		'cta_url'        => '',
		'cta_label'      => '',
		'cta_target'     => '',
		'cta_rel'        => '',
	];
}
?>
<section
	<?php if ( '' !== $anchor ) : ?>
		id="<?php echo esc_attr( $anchor ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
>
	<div class="hero-block__carousel" data-hero-carousel data-carousel-id="<?php echo esc_attr( $carousel_id ); ?>">
		<?php foreach ( $items as $index => $item ) : ?>
			<article
				class="hero-block__slide <?php echo 0 === $index ? esc_attr( 'is-active' ) : ''; ?>"
				data-hero-slide
				aria-hidden="<?php echo 0 === $index ? 'false' : 'true'; ?>"
			>
				<?php if ( '' !== $item['background_url'] ) : ?>
					<?php
					$image_attrs = [
						'class'      => 'hero-block__media',
						'alt'        => '',
						'decoding'   => 'async',
						'loading'    => 0 === $index ? 'eager' : 'lazy',
						'fetchpriority' => 0 === $index ? 'high' : false,
					];

					if ( $item['background_id'] > 0 ) {
						echo wp_get_attachment_image( $item['background_id'], 'full', false, $image_attrs );
					} else {
						printf(
							'<img src="%s" class="%s" alt="" decoding="async" loading="%s"%s>',
							esc_url( $item['background_url'] ),
							esc_attr( $image_attrs['class'] ),
							esc_attr( $image_attrs['loading'] ),
							0 === $index ? ' fetchpriority="high"' : ''
						);
					}
					?>
				<?php else : ?>
					<div class="hero-block__media hero-block__media--fallback"></div>
				<?php endif; ?>
				<div class="hero-block__overlay"></div>

				<div class="hero-block__inner">
					<div class="hero-block__content">
						<h1 class="hero-block__title"><?php echo esc_html( $item['title'] ); ?></h1>

						<?php if ( '' !== $item['cta_url'] && '' !== $item['cta_label'] ) : ?>
							<a
								class="btn-primary"
								href="<?php echo esc_url( $item['cta_url'] ); ?>"
								<?php if ( 0 !== $index ) : ?>
									tabindex="-1"
								<?php endif; ?>
								<?php if ( '' !== $item['cta_target'] ) : ?>
									target="<?php echo esc_attr( $item['cta_target'] ); ?>"
								<?php endif; ?>
								<?php if ( '' !== $item['cta_rel'] ) : ?>
									rel="<?php echo esc_attr( $item['cta_rel'] ); ?>"
								<?php endif; ?>
							>
								<?php echo esc_html( $item['cta_label'] ); ?>
							</a>
						<?php elseif ( ! empty( $is_preview ) ) : ?>
							<span class="btn-primary"><?php esc_html_e( 'Solicitar presupuesto', 'mauswp' ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</article>
		<?php endforeach; ?>

		<?php if ( count( $items ) > 1 ) : ?>
			<div class="hero-block__nav" aria-label="<?php esc_attr_e( 'Controles del carrusel hero', 'mauswp' ); ?>">
				<button class="hero-block__arrow" type="button" data-hero-prev aria-controls="<?php echo esc_attr( $carousel_id ); ?>" aria-label="<?php esc_attr_e( 'Slide anterior', 'mauswp' ); ?>">
					<span aria-hidden="true">&larr;</span>
				</button>
				<div class="hero-block__dots" id="<?php echo esc_attr( $carousel_id ); ?>">
					<?php foreach ( $items as $index => $item ) : ?>
						<button
							class="hero-block__dot <?php echo 0 === $index ? esc_attr( 'is-active' ) : ''; ?>"
							type="button"
							data-hero-dot
							data-slide-index="<?php echo esc_attr( (string) $index ); ?>"
							aria-label="<?php echo esc_attr( sprintf( __( 'Ir al slide %d', 'mauswp' ), $index + 1 ) ); ?>"
							aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>"
						></button>
					<?php endforeach; ?>
				</div>
				<button class="hero-block__arrow" type="button" data-hero-next aria-controls="<?php echo esc_attr( $carousel_id ); ?>" aria-label="<?php esc_attr_e( 'Siguiente slide', 'mauswp' ); ?>">
					<span aria-hidden="true">&rarr;</span>
				</button>
			</div>
		<?php endif; ?>
	</div>
</section>
