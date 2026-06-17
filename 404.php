<?php
/**
 * 404 template.
 *
 * @package MausWP
 */

declare(strict_types=1);

get_header();
?>
<main id="primary" class="bg-site py-20 lg:py-28">
	<div class="container">
		<section class="mx-auto max-w-3xl border border-slate-200 bg-white p-10 text-center shadow-sm">
			<p class="eyebrow justify-center"><?php esc_html_e( 'Error 404', 'mauswp' ); ?></p>
			<h1 class="mt-4 text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"><?php esc_html_e( 'La página que buscas no está disponible.', 'mauswp' ); ?></h1>
			<p class="mt-6 text-lg leading-8 text-slate-600"><?php esc_html_e( 'Usa la navegación principal o vuelve al inicio para seguir explorando el nuevo entorno corporativo.', 'mauswp' ); ?></p>
			<div class="mt-8 flex flex-col justify-center gap-4 sm:flex-row">
				<a class="btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<?php esc_html_e( 'Volver al inicio', 'mauswp' ); ?>
				</a>
				<a class="btn-secondary" href="<?php echo esc_url( home_url( '/contacto/' ) ); ?>">
					<?php esc_html_e( 'Ir a contacto', 'mauswp' ); ?>
				</a>
			</div>
		</section>
	</div>
</main>
<?php
get_footer();
