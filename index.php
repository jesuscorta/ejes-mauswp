<?php
/**
 * Main index template.
 *
 * @package MausWP
 */

declare(strict_types=1);

get_header();
?>
<main id="primary" class="bg-site py-16">
	<div class="container space-y-8">
		<?php mauswp_yoast_breadcrumbs(); ?>

		<header class="space-y-4">
			<p class="eyebrow">Actualidad</p>
			<h1 class="text-4xl font-semibold tracking-tight text-slate-900">
				<?php echo esc_html( get_the_archive_title() ?: get_bloginfo( 'name' ) ); ?>
			</h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article <?php post_class( 'card flex h-full flex-col overflow-hidden' ); ?>>
						<a class="flex h-full flex-col" href="<?php the_permalink(); ?>">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="aspect-[16/10] overflow-hidden bg-slate-200">
									<?php the_post_thumbnail( 'large', [ 'class' => 'h-full w-full object-cover' ] ); ?>
								</div>
							<?php endif; ?>
							<div class="flex flex-1 flex-col gap-4 p-6">
								<p class="text-sm font-medium text-slate-500">
									<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								</p>
								<h2 class="text-2xl font-semibold text-slate-900"><?php the_title(); ?></h2>
								<div class="text-base leading-7 text-slate-600">
									<?php the_excerpt(); ?>
								</div>
								<span class="mt-auto inline-flex items-center text-sm font-semibold text-brand-700">
									<?php esc_html_e( 'Leer más', 'mauswp' ); ?>
								</span>
							</div>
						</a>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<?php the_posts_pagination(); ?>
		<?php else : ?>
			<section class="card p-8">
				<h2 class="text-2xl font-semibold text-slate-900"><?php esc_html_e( 'No hay contenido disponible.', 'mauswp' ); ?></h2>
			</section>
		<?php endif; ?>
	</div>
</main>
<?php
get_footer();
