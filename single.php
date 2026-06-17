<?php
/**
 * Single post template.
 *
 * @package MausWP
 */

declare(strict_types=1);

get_header();

while ( have_posts() ) :
	the_post();

	$categories = get_the_category();
	$category_name = '';
	if ( ! empty( $categories ) && $categories[0] instanceof WP_Term ) {
		$category_name = $categories[0]->name;
	}

	$content_data = mauswp_generate_toc( get_the_content() );
	$toc          = $content_data['toc'];
	$content      = $content_data['content'];
	$has_toc      = ! empty( $toc );
	?>
	<main id="primary" class="single-post">
		<div class="single-post__hero">
			<div class="container">
				<div class="single-post__hero-inner">
					<?php if ( '' !== $category_name ) : ?>
						<p class="single-post__hero-category"><?php echo esc_html( $category_name ); ?></p>
					<?php endif; ?>
					<h1 class="single-post__hero-title"><?php the_title(); ?></h1>
					<p class="single-post__hero-meta">
						<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
					</p>
				</div>
			</div>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="single-post__featured">
				<div class="container">
					<?php the_post_thumbnail( 'large', [ 'class' => 'single-post__featured-image' ] ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="container">
			<div class="single-post__layout">
				<div class="single-post__main">
					<?php if ( $has_toc ) : ?>
						<nav class="single-post__toc" aria-label="<?php esc_attr_e( 'Tabla de contenido', 'mauswp' ); ?>">
							<h2 class="single-post__toc-title"><?php esc_html_e( 'Tabla de contenido', 'mauswp' ); ?></h2>
							<ol class="single-post__toc-list">
								<?php foreach ( $toc as $item ) : ?>
									<li class="single-post__toc-item">
										<a class="single-post__toc-link" href="#<?php echo esc_attr( $item['id'] ); ?>">
											<?php echo esc_html( $item['text'] ); ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ol>
						</nav>
					<?php endif; ?>

					<div class="single-post__content entry-content">
						<?php echo apply_filters( 'the_content', $content ); ?>
					</div>

					<?php comments_template(); ?>

					<nav class="single-post__nav" aria-label="<?php esc_attr_e( 'Navegación entre entradas', 'mauswp' ); ?>">
						<div class="single-post__nav-grid">
							<div class="single-post__nav-prev">
								<?php previous_post_link( '%link', '<span class="single-post__nav-label">' . esc_html__( 'Anterior', 'mauswp' ) . '</span><span class="single-post__nav-title">%title</span>' ); ?>
							</div>
							<div class="single-post__nav-next">
								<?php next_post_link( '%link', '<span class="single-post__nav-label">' . esc_html__( 'Siguiente', 'mauswp' ) . '</span><span class="single-post__nav-title">%title</span>' ); ?>
							</div>
						</div>
					</nav>
				</div>

				<?php get_template_part( 'template-parts/news/single-sidebar' ); ?>
			</div>
		</div>
	</main>
	<?php
endwhile;

get_footer();
