<?php
/**
 * Google reviews block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$anchor        = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : '';
$align_class   = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignwide';
$block_classes = [ 'google-reviews-block', $align_class ];
$title         = function_exists( 'get_field' ) ? (string) get_field( 'title' ) : '';
$shortcode     = function_exists( 'get_field' ) ? trim( (string) get_field( 'shortcode' ) ) : '';

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

if ( '' === $shortcode && ! is_admin() ) {
	return;
}
?>
<section
	<?php if ( '' !== $anchor ) : ?>
		id="<?php echo esc_attr( $anchor ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
>
	<div class="google-reviews-block__inner">
		<?php if ( '' !== trim( $title ) ) : ?>
			<header class="google-reviews-block__header">
				<p class="google-reviews-block__eyebrow"><?php esc_html_e( 'Opiniones verificadas', 'mauswp' ); ?></p>
				<h2 class="google-reviews-block__title"><?php echo esc_html( $title ); ?></h2>
			</header>
		<?php endif; ?>

		<?php if ( '' !== $shortcode ) : ?>
			<div class="google-reviews-block__widget">
				<?php echo do_shortcode( $shortcode ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php elseif ( is_admin() ) : ?>
			<div class="google-reviews-block__placeholder">
				<?php esc_html_e( 'Añade el shortcode de Trustindex para mostrar las reseñas de Google.', 'mauswp' ); ?>
			</div>
		<?php endif; ?>
	</div>
</section>
