<?php
/**
 * Template Name: Página por bloques
 * Template Post Type: page
 *
 * @package MausWP
 */

declare(strict_types=1);

get_header();
?>
<main id="primary" class="page-blocks-content">
	<?php mauswp_yoast_breadcrumbs( 'container' ); ?>
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<?php if ( has_blocks( get_the_content() ) || trim( wp_strip_all_tags( get_the_content() ) ) ) : ?>
			<?php the_content(); ?>
		<?php else : ?>
			<section class="border-b border-slate-200 bg-site py-20">
				<div class="container max-w-3xl space-y-5 text-center">
					<p class="eyebrow justify-center"><?php esc_html_e( 'Página editable', 'mauswp' ); ?></p>
					<h1 class="text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"><?php the_title(); ?></h1>
					<p class="text-lg leading-8 text-slate-600"><?php esc_html_e( 'Añade bloques para construir esta página con el layout libre del tema.', 'mauswp' ); ?></p>
				</div>
			</section>
		<?php endif; ?>
		<?php
	endwhile;
	?>
</main>
<?php
get_footer();
