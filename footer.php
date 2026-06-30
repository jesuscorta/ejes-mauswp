<?php
/**
 * Theme footer.
 *
 * @package MausWP
 */

declare(strict_types=1);

$footer_logo           = [];
$footer_column_1_title = get_bloginfo( 'name' );
$footer_column_1_text  = __( 'Base provisional del nuevo entorno corporativo para ejes, enganches y soluciones de arrastre robustas para remolques industriales y agrícolas.', 'mauswp' );
$footer_column_2_title = __( 'Navegación', 'mauswp' );
$footer_column_3_title = __( 'Contacto', 'mauswp' );
$footer_phone          = '+34 900 000 000';
$footer_email          = 'info@ejespararemolques.com';
$footer_subfooter_text = __( 'Todos los derechos reservados.', 'mauswp' );

if ( function_exists( 'get_field' ) ) {
	$footer_logo           = get_field( 'mauswp_footer_logo', 'option' ) ?: [];
	$header_logo           = get_field( 'mauswp_header_logo', 'option' ) ?: [];
	$footer_column_1_title = (string) ( get_field( 'mauswp_footer_column_1_title', 'option' ) ?: $footer_column_1_title );
	$footer_column_1_text  = (string) ( get_field( 'mauswp_footer_column_1_text', 'option' ) ?: $footer_column_1_text );
	$footer_column_2_title = (string) ( get_field( 'mauswp_footer_column_2_title', 'option' ) ?: $footer_column_2_title );
	$footer_column_3_title = (string) ( get_field( 'mauswp_footer_column_3_title', 'option' ) ?: $footer_column_3_title );
	$footer_phone          = (string) ( get_field( 'mauswp_footer_contact_phone', 'option' ) ?: $footer_phone );
	$footer_email          = (string) ( get_field( 'mauswp_footer_contact_email', 'option' ) ?: $footer_email );
	$footer_subfooter_text = (string) ( get_field( 'mauswp_footer_subfooter_text', 'option' ) ?: $footer_subfooter_text );

	if ( empty( $footer_logo ) && is_array( $header_logo ) ) {
		$footer_logo = $header_logo;
	}
}

$footer_phone_href = preg_replace( '/[^0-9+]/', '', $footer_phone );
?>
<footer class="bg-slate-950 py-16 text-slate-200">
	<div class="container grid gap-10 lg:grid-cols-[1.3fr_0.7fr_0.8fr]">
		<div class="space-y-5">
			<?php if ( is_array( $footer_logo ) && ! empty( $footer_logo['ID'] ) ) : ?>
				<div>
					<?php echo wp_get_attachment_image( (int) $footer_logo['ID'], 'medium', false, [ 'class' => 'block max-w-full shrink-0 sm:w-[14rem] lg:w-[16rem]', 'style' => 'width:12rem;height:auto;' ] ); ?>
				</div>
			<?php endif; ?>
			<p class="text-3xl font-semibold tracking-tight text-white"><?php echo esc_html( $footer_column_1_title ); ?></p>
			<p class="max-w-xl text-base leading-7 text-slate-400">
				<?php echo esc_html( $footer_column_1_text ); ?>
			</p>
		</div>

		<div class="space-y-4">
			<h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500"><?php echo esc_html( $footer_column_2_title ); ?></h3>
			<?php
			wp_nav_menu(
				[
					'theme_location' => 'footer',
					'container'      => false,
					'menu_class'     => 'space-y-3 text-sm text-slate-300',
					'fallback_cb'    => 'mauswp_fallback_menu',
					'depth'          => 1,
				]
			);
			?>
		</div>

		<div class="space-y-5">
			<h3 class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-500"><?php echo esc_html( $footer_column_3_title ); ?></h3>
			<ul class="space-y-3 text-sm text-slate-300">
				<?php if ( '' !== $footer_phone ) : ?>
					<li><a class="transition hover:text-white" href="tel:<?php echo esc_attr( $footer_phone_href ); ?>"><?php echo esc_html( $footer_phone ); ?></a></li>
				<?php endif; ?>
				<?php if ( '' !== $footer_email ) : ?>
					<li><a class="transition hover:text-white" href="mailto:<?php echo esc_attr( antispambot( $footer_email ) ); ?>"><?php echo esc_html( antispambot( $footer_email ) ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

	<div class="container mt-12 border-t border-slate-800 pt-6 text-sm text-slate-500">
		<div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
			<p>&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php echo esc_html( $footer_subfooter_text ); ?></p>
			<?php
			wp_nav_menu(
				[
					'theme_location' => 'footer_legal',
					'container'      => false,
					'menu_class'     => 'flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-400',
					'fallback_cb'    => '__return_empty_string',
					'depth'          => 1,
				]
			);
			?>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
