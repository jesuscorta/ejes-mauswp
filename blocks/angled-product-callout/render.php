<?php
/**
 * Angled product callout block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$anchor        = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : '';
$align_class   = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignfull';
$block_classes = [ 'angled-product-callout-block', $align_class ];
$eyebrow       = (string) ( get_field( 'eyebrow' ) ?: '' );
$title         = (string) ( get_field( 'title' ) ?: __( 'Otros productos', 'mauswp' ) );
$content       = (string) ( get_field( 'content' ) ?: __( 'Disponemos de una amplia gama de ejes agrícolas con y sin freno, cilindros telescópicos, ballestas, enganches tiro de lanza, rodetes de giro, bombas de accionamiento manual y soluciones complementarias para distintas configuraciones de remolque.', 'mauswp' ) );
$cta_label     = (string) ( get_field( 'cta_label' ) ?: __( 'Contacta con nosotros', 'mauswp' ) );
$cta_anchor    = (string) ( get_field( 'cta_anchor' ) ?: 'contacto' );
$image         = get_field( 'image' );
$section_target = '#' . ltrim( sanitize_title( $cta_anchor ), '#' );

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

$image_id  = 0;
$image_url = '';
$image_alt = '';

if ( is_array( $image ) ) {
	$image_id  = ! empty( $image['ID'] ) ? (int) $image['ID'] : 0;
	$image_url = ! empty( $image['sizes']['large'] ) ? (string) $image['sizes']['large'] : ( ! empty( $image['url'] ) ? (string) $image['url'] : '' );
	$image_alt = ! empty( $image['alt'] ) ? (string) $image['alt'] : '';
}
?>
<section
	<?php if ( '' !== $anchor ) : ?>
		id="<?php echo esc_attr( $anchor ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
>
	<div class="angled-product-callout-block__shell">
		<div class="angled-product-callout-block__media">
			<?php if ( $image_id > 0 ) : ?>
				<figure class="angled-product-callout-block__figure">
					<?php echo wp_get_attachment_image( $image_id, 'large', false, [ 'class' => 'angled-product-callout-block__image', 'alt' => $image_alt, 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
				</figure>
			<?php elseif ( '' !== $image_url ) : ?>
				<figure class="angled-product-callout-block__figure">
					<img class="angled-product-callout-block__image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" decoding="async" />
				</figure>
			<?php else : ?>
				<div class="angled-product-callout-block__placeholder angled-product-callout-block__figure" aria-hidden="true"></div>
			<?php endif; ?>
		</div>

		<div class="angled-product-callout-block__content">
			<?php if ( '' !== $eyebrow ) : ?>
				<p class="angled-product-callout-block__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>

			<h2 class="angled-product-callout-block__title"><?php echo esc_html( $title ); ?></h2>

			<div class="angled-product-callout-block__copy">
				<?php echo wp_kses_post( wpautop( $content ) ); ?>
			</div>

			<a class="angled-product-callout-block__cta" href="<?php echo esc_url( $section_target ); ?>">
				<span aria-hidden="true">&rarr;</span>
				<span><?php echo esc_html( $cta_label ); ?></span>
			</a>
		</div>
	</div>
</section>
