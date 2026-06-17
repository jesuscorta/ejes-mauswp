<?php
/**
 * Shared news archive listing.
 *
 * @package MausWP
 */

declare(strict_types=1);

$page_for_posts_id = (int) get_option( 'page_for_posts' );
$archive_title     = $page_for_posts_id > 0 ? get_the_title( $page_for_posts_id ) : '';

if ( ! is_string( $archive_title ) || '' === trim( $archive_title ) ) {
	$archive_title = __( 'Noticias', 'mauswp' );
}

$pagination_links = paginate_links(
	[
		'type'      => 'array',
		'mid_size'  => 1,
		'prev_text' => __( 'Anterior', 'mauswp' ),
		'next_text' => __( 'Siguiente', 'mauswp' ),
	]
);
?>
<main id="primary" class="news-archive bg-site py-16 lg:py-20">
	<div class="container space-y-10">
		<header class="news-archive__header">
			<p class="eyebrow"><?php esc_html_e( 'Noticias', 'mauswp' ); ?></p>
			<h1 class="news-archive__title"><?php echo esc_html( $archive_title ); ?></h1>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="news-archive__grid">
				<?php
				while ( have_posts() ) :
					the_post();

					$excerpt_source = has_excerpt() ? get_the_excerpt() : get_the_content( null, false );
					$excerpt        = wp_trim_words( wp_strip_all_tags( (string) $excerpt_source ), 25, '...' );
					?>
					<article <?php post_class( 'news-archive__card' ); ?>>
						<a class="news-archive__link" href="<?php the_permalink(); ?>">
							<div class="news-archive__media">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'large', [ 'class' => 'news-archive__image' ] ); ?>
								<?php else : ?>
									<div class="news-archive__placeholder" aria-hidden="true"></div>
								<?php endif; ?>
							</div>

							<div class="news-archive__body">
								<p class="news-archive__meta">
									<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								</p>
								<h2 class="news-archive__card-title"><?php the_title(); ?></h2>
								<p class="news-archive__excerpt"><?php echo esc_html( $excerpt ); ?></p>
								<span class="news-archive__cta"><?php esc_html_e( 'Leer más', 'mauswp' ); ?></span>
							</div>
						</a>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<?php if ( is_array( $pagination_links ) && ! empty( $pagination_links ) ) : ?>
				<nav class="news-archive__pagination" aria-label="<?php esc_attr_e( 'Paginación de noticias', 'mauswp' ); ?>">
					<?php foreach ( $pagination_links as $pagination_link ) : ?>
						<?php echo wp_kses_post( $pagination_link ); ?>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<section class="news-archive__empty">
				<h2 class="text-2xl font-semibold text-slate-900"><?php esc_html_e( 'Todavía no hay publicaciones disponibles.', 'mauswp' ); ?></h2>
			</section>
		<?php endif; ?>
	</div>
</main>
