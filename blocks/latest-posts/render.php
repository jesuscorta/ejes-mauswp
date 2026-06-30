<?php
/**
 * Latest posts block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$eyebrow       = (string) get_field( 'eyebrow' );
$title         = (string) get_field( 'title' );
$intro         = (string) get_field( 'intro' );
$cta_link      = get_field( 'cta_link' );
$anchor        = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : '';
$align_class   = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignwide';
$block_classes = [ 'latest-posts-block', $align_class ];

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

if ( '' === $eyebrow ) {
	$eyebrow = __( 'Actualidad', 'mauswp' );
}

if ( '' === $title ) {
	$title = __( 'Las últimas entradas del sector', 'mauswp' );
}

if ( '' === $intro ) {
	$intro = __( 'Contenido reciente sobre ejes, enganches, normativa, montaje y decisiones de compra para remolques y soluciones de arrastre.', 'mauswp' );
}

$cta_url    = home_url( '/noticias/' );
$cta_label  = __( 'Ver más', 'mauswp' );
$cta_target = '';
$cta_rel    = '';

if ( is_array( $cta_link ) ) {
	$cta_url    = ! empty( $cta_link['url'] ) ? (string) $cta_link['url'] : $cta_url;
	$cta_label  = ! empty( $cta_link['title'] ) ? (string) $cta_link['title'] : $cta_label;
	$cta_target = ! empty( $cta_link['target'] ) ? (string) $cta_link['target'] : '';
	$cta_rel    = '_blank' === $cta_target ? 'noopener noreferrer' : '';
}

$posts = get_transient( 'mauswp_latest_posts_block' );

if ( false === $posts || ! is_array( $posts ) ) {
	$posts = [];

	$latest_posts_query = new WP_Query(
		[
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'posts_per_page'      => 4,
			'ignore_sticky_posts' => true,
			'no_found_rows'       => true,
		]
	);

	if ( $latest_posts_query->have_posts() ) {
		while ( $latest_posts_query->have_posts() ) {
			$latest_posts_query->the_post();

			$post_id         = get_the_ID();
			$post_categories = get_the_category( $post_id );
			$primary_term    = ! empty( $post_categories ) ? $post_categories[0]->name : __( 'Blog', 'mauswp' );
			$excerpt_source  = has_excerpt() ? get_the_excerpt() : wp_strip_all_tags( get_the_content() );

			$posts[] = [
				'id'           => $post_id,
				'permalink'    => get_permalink(),
				'title'        => get_the_title(),
				'date'         => get_the_date(),
				'date_w3c'     => get_the_date( DATE_W3C ),
				'primary_term' => $primary_term,
				'excerpt'      => wp_trim_words( $excerpt_source, 18, '...' ),
				'has_thumb'    => has_post_thumbnail(),
			];
		}

		wp_reset_postdata();
	}

	set_transient( 'mauswp_latest_posts_block', $posts, 5 * MINUTE_IN_SECONDS );
}
$featured_post = ! empty( $posts ) ? array_shift( $posts ) : null;
?>
<section
	<?php if ( '' !== $anchor ) : ?>
		id="<?php echo esc_attr( $anchor ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
>
	<div class="latest-posts-block__header">
		<div class="latest-posts-block__intro">
			<p class="latest-posts-block__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<h2 class="latest-posts-block__title"><?php echo esc_html( $title ); ?></h2>
			<p class="latest-posts-block__text"><?php echo esc_html( $intro ); ?></p>
		</div>

		<a
			class="latest-posts-block__cta"
			href="<?php echo esc_url( $cta_url ); ?>"
			<?php if ( '' !== $cta_target ) : ?>
				target="<?php echo esc_attr( $cta_target ); ?>"
			<?php endif; ?>
			<?php if ( '' !== $cta_rel ) : ?>
				rel="<?php echo esc_attr( $cta_rel ); ?>"
			<?php endif; ?>
		>
			<span><?php echo esc_html( $cta_label ); ?></span>
			<span aria-hidden="true">&rarr;</span>
		</a>
	</div>

	<?php if ( ! empty( $featured_post ) ) : ?>
		<div class="latest-posts-block__layout">
			<article class="latest-posts-block__card latest-posts-block__card--featured">
				<a class="latest-posts-block__featured-link" href="<?php echo esc_url( $featured_post['permalink'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Leer entrada: %s', 'mauswp' ), $featured_post['title'] ) ); ?>">
					<div class="latest-posts-block__featured-media">
						<?php if ( $featured_post['has_thumb'] ) : ?>
							<?php echo get_the_post_thumbnail( $featured_post['id'], 'large', [ 'class' => 'latest-posts-block__image' ] ); ?>
						<?php else : ?>
							<div class="latest-posts-block__placeholder" aria-hidden="true"></div>
						<?php endif; ?>
					</div>

					<div class="latest-posts-block__featured-body">
						<div class="latest-posts-block__meta">
							<span class="latest-posts-block__term"><?php echo esc_html( $featured_post['primary_term'] ); ?></span>
							<span class="latest-posts-block__separator" aria-hidden="true">|</span>
							<time datetime="<?php echo esc_attr( $featured_post['date_w3c'] ); ?>"><?php echo esc_html( $featured_post['date'] ); ?></time>
						</div>

						<h3 class="latest-posts-block__card-title latest-posts-block__card-title--featured"><?php echo esc_html( $featured_post['title'] ); ?></h3>
						<p class="latest-posts-block__excerpt latest-posts-block__excerpt--featured"><?php echo esc_html( $featured_post['excerpt'] ); ?></p>

						<span class="latest-posts-block__more">
							<?php esc_html_e( 'Leer entrada', 'mauswp' ); ?>
							<span aria-hidden="true">&rarr;</span>
						</span>
					</div>
				</a>
			</article>

			<div class="latest-posts-block__stack">
				<?php foreach ( $posts as $post_item ) : ?>
					<article class="latest-posts-block__card latest-posts-block__card--compact">
						<a class="latest-posts-block__compact-link" href="<?php echo esc_url( $post_item['permalink'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Leer entrada: %s', 'mauswp' ), $post_item['title'] ) ); ?>">
							<div class="latest-posts-block__compact-body">
								<div class="latest-posts-block__meta">
									<span class="latest-posts-block__term"><?php echo esc_html( $post_item['primary_term'] ); ?></span>
									<span class="latest-posts-block__separator" aria-hidden="true">|</span>
									<time datetime="<?php echo esc_attr( $post_item['date_w3c'] ); ?>"><?php echo esc_html( $post_item['date'] ); ?></time>
								</div>

								<h3 class="latest-posts-block__card-title latest-posts-block__card-title--compact"><?php echo esc_html( $post_item['title'] ); ?></h3>
								<p class="latest-posts-block__excerpt latest-posts-block__excerpt--compact"><?php echo esc_html( $post_item['excerpt'] ); ?></p>

								<span class="latest-posts-block__more latest-posts-block__more--compact">
									<?php esc_html_e( 'Leer entrada', 'mauswp' ); ?>
									<span aria-hidden="true">&rarr;</span>
								</span>
							</div>
						</a>
					</article>
				<?php endforeach; ?>
			</div>
		</div>
	<?php else : ?>
		<div class="latest-posts-block__empty">
			<p><?php esc_html_e( 'Todavía no hay entradas publicadas.', 'mauswp' ); ?></p>
		</div>
	<?php endif; ?>
</section>
