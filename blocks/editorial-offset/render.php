<?php
/**
 * Editorial offset block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$image         = get_field( 'image' );
$eyebrow       = (string) get_field( 'eyebrow' );
$title         = (string) get_field( 'title' );
$content       = (string) get_field( 'content' );
$cta_link      = get_field( 'cta_link' );
$anchor        = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : '';
$align_class   = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignfull';
$block_classes = [ 'editorial-offset-block', $align_class ];

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
	$image_url = ! empty( $image['sizes']['large'] ) ? (string) $image['sizes']['large'] : '';
	$image_alt = ! empty( $image['alt'] ) ? (string) $image['alt'] : '';

	if ( '' === $image_url && ! empty( $image['url'] ) ) {
		$image_url = (string) $image['url'];
	}
}

if ( '' === $eyebrow ) {
	$eyebrow = __( 'Suministro técnico', 'mauswp' );
}

if ( '' === $title ) {
	$title = __( 'Quiénes somos', 'mauswp' );
}

if ( '' === $content ) {
	$content = '<p>' . esc_html__( 'Bloque editorial pensado para hablar de empresa, experiencia, fabricación o enfoque comercial con una composición más expresiva que el bloque imagen + texto estándar.', 'mauswp' ) . '</p>';
}

$cta_url    = '';
$cta_label  = '';
$cta_target = '';
$cta_rel    = '';

if ( is_array( $cta_link ) ) {
	$cta_url    = ! empty( $cta_link['url'] ) ? (string) $cta_link['url'] : '';
	$cta_label  = ! empty( $cta_link['title'] ) ? (string) $cta_link['title'] : '';
	$cta_target = ! empty( $cta_link['target'] ) ? (string) $cta_link['target'] : '';
	$cta_rel    = '_blank' === $cta_target ? 'noopener noreferrer' : '';
}
?>
<section
	<?php if ( '' !== $anchor ) : ?>
		id="<?php echo esc_attr( $anchor ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
>
	<div class="editorial-offset-block__shell">
		<div class="editorial-offset-block__panel">
			<div class="editorial-offset-block__content">
				<p class="editorial-offset-block__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
				<h2 class="editorial-offset-block__title"><?php echo esc_html( $title ); ?></h2>
				<div class="editorial-offset-block__copy">
					<?php echo wp_kses_post( $content ); ?>
				</div>

				<?php if ( '' !== $cta_url && '' !== $cta_label ) : ?>
					<a
						class="editorial-offset-block__cta"
						href="<?php echo esc_url( $cta_url ); ?>"
						<?php if ( '' !== $cta_target ) : ?>
							target="<?php echo esc_attr( $cta_target ); ?>"
						<?php endif; ?>
						<?php if ( '' !== $cta_rel ) : ?>
							rel="<?php echo esc_attr( $cta_rel ); ?>"
						<?php endif; ?>
					>
						<span aria-hidden="true">&rarr;</span>
						<span><?php echo esc_html( $cta_label ); ?></span>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="editorial-offset-block__media">
			<?php if ( $image_id > 0 ) : ?>
				<?php echo wp_get_attachment_image( $image_id, 'large', false, [ 'class' => 'editorial-offset-block__image', 'alt' => $image_alt, 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
			<?php elseif ( '' !== $image_url ) : ?>
				<img class="editorial-offset-block__image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" decoding="async">
			<?php elseif ( ! empty( $is_preview ) ) : ?>
				<div class="editorial-offset-block__placeholder" aria-hidden="true"></div>
			<?php endif; ?>
		</div>
	</div>
</section>
