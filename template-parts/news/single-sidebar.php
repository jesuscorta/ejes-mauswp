<?php
/**
 * Single post sidebar with related posts.
 *
 * @package MausWP
 */

declare(strict_types=1);

$related_posts = mauswp_get_related_posts( get_the_ID(), 4 );

if ( empty( $related_posts ) ) {
	return;
}
?>
<aside class="single-post__sidebar">
	<div class="single-post__sidebar-block">
		<h3 class="single-post__sidebar-title"><?php esc_html_e( 'Noticias relacionadas', 'mauswp' ); ?></h3>
		<div class="single-post__related-list">
			<?php foreach ( $related_posts as $related_post ) : ?>
				<?php
				$related_title = get_the_title( $related_post );
				$related_url   = get_permalink( $related_post );
				$related_date  = get_the_date( '', $related_post );
				$related_thumb = get_the_post_thumbnail_url( $related_post, 'thumbnail' );
				?>
				<a class="single-post__related-item" href="<?php echo esc_url( $related_url ); ?>">
					<?php if ( $related_thumb ) : ?>
						<img class="single-post__related-thumb" src="<?php echo esc_url( $related_thumb ); ?>" alt="" loading="lazy">
					<?php else : ?>
						<div class="single-post__related-thumb single-post__related-thumb--placeholder"></div>
					<?php endif; ?>
					<div class="single-post__related-body">
						<p class="single-post__related-date"><?php echo esc_html( $related_date ); ?></p>
						<h4 class="single-post__related-title"><?php echo esc_html( $related_title ); ?></h4>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</aside>
