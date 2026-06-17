<?php
/**
 * Front page template.
 *
 * @package MausWP
 */

declare(strict_types=1);

get_header();
?>
<main id="primary" class="front-page-content">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php if ( has_blocks( get_the_content() ) || trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<section class="border-b border-slate-200 bg-site py-20">
				<div class="container max-w-3xl space-y-5 text-center">
					<p class="eyebrow justify-center"><?php esc_html_e( 'Home editable', 'mauswp' ); ?></p>
					<h1 class="text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"><?php esc_html_e( 'Añade el bloque Hero ACF para empezar a construir la portada.', 'mauswp' ); ?></h1>
					<p class="text-lg leading-8 text-slate-600"><?php esc_html_e( 'La portada ahora renderiza contenido Gutenberg, así que la home queda lista para migración por bloques.', 'mauswp' ); ?></p>
				</div>
			</section>
		<?php endif; ?>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
