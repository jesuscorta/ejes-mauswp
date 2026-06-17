<?php
/**
 * About hero block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$anchor          = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : '';
$align_class     = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignfull';
$block_classes   = [ 'about-hero-block', $align_class ];
$background      = get_field( 'background_image' );
$title           = (string) ( get_field( 'title' ) ?: __( 'Suministros Agroindustriales SCA', 'mauswp' ) );
$subtitle        = (string) ( get_field( 'subtitle' ) ?: '' );
$subtitle_place  = (string) ( get_field( 'subtitle_position' ) ?: 'below' );
$description     = (string) ( get_field( 'description' ) ?: __( 'Más de 25 años especializados en soluciones agrícolas, industriales y remolques a nivel nacional.', 'mauswp' ) );
$cta_label       = (string) ( get_field( 'cta_label' ) ?: __( 'Contactar', 'mauswp' ) );
$cta_anchor      = (string) ( get_field( 'cta_anchor' ) ?: 'contacto' );
$background_url  = '';
$background_alt  = '';
$section_target  = '#' . ltrim( sanitize_title( $cta_anchor ), '#' );

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

if ( is_array( $background ) ) {
	$background_url = ! empty( $background['url'] ) ? (string) $background['url'] : '';
	$background_alt = ! empty( $background['alt'] ) ? (string) $background['alt'] : '';
}
?>
<section
	<?php if ( '' !== $anchor ) : ?>
		id="<?php echo esc_attr( $anchor ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
>
	<div class="about-hero-block__media">
		<?php if ( '' !== $background_url ) : ?>
			<img
				class="about-hero-block__image"
				src="<?php echo esc_url( $background_url ); ?>"
				alt="<?php echo esc_attr( $background_alt ); ?>"
			/>
		<?php else : ?>
			<div class="about-hero-block__image about-hero-block__image--placeholder" aria-hidden="true"></div>
		<?php endif; ?>

		<div class="about-hero-block__overlay"></div>

		<div class="about-hero-block__inner">
			<div class="about-hero-block__content">
				<?php if ( '' !== $subtitle && 'above' === $subtitle_place ) : ?>
					<p class="about-hero-block__subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
				<h1 class="about-hero-block__title"><?php echo esc_html( $title ); ?></h1>
				<?php if ( '' !== $subtitle && 'above' !== $subtitle_place ) : ?>
					<p class="about-hero-block__subtitle"><?php echo esc_html( $subtitle ); ?></p>
				<?php endif; ?>
				<div class="about-hero-block__description">
					<?php echo wp_kses_post( wpautop( $description ) ); ?>
				</div>
				<a class="btn-primary" href="<?php echo esc_url( $section_target ); ?>">
					<?php echo esc_html( $cta_label ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
