<?php
/**
 * Single product template.
 *
 * @package MausWP
 */

declare(strict_types=1);

get_header();

while ( have_posts() ) :
	the_post();

	global $product;

	if ( ! $product instanceof WC_Product ) {
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
	}

	if ( ! $product instanceof WC_Product ) {
		continue;
	}

	do_action( 'woocommerce_before_single_product' );

	if ( post_password_required() ) {
		echo get_the_password_form();
		continue;
	}

	$product_id        = $product->get_id();
	$primary_term      = null;
	$product_terms     = get_the_terms( $product_id, 'product_cat' );
	$short_description = $product->get_short_description();
	$main_image_id     = $product->get_image_id();
	$gallery_image_ids = $product->get_gallery_image_ids();
	$product_gallery_ids = array_values( array_unique( array_filter( array_merge( [ $main_image_id ], array_map( 'intval', $gallery_image_ids ) ) ) ) );
	$description       = get_the_content();
	$shop_url          = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' );
	$support_title     = function_exists( 'get_field' ) ? (string) get_field( 'mauswp_product_support_title', 'option' ) : '';
	$support_text      = function_exists( 'get_field' ) ? (string) get_field( 'mauswp_product_support_text', 'option' ) : '';
	$has_builder       = function_exists( 'mauswp_product_has_editorial_builder' ) ? mauswp_product_has_editorial_builder( $product_id ) : false;
	$review_count      = (int) $product->get_review_count();
	$average_rating    = (float) $product->get_average_rating();
	$rating_stars_full = max( 0, min( 5, (int) round( $average_rating ) ) );
	$rating_stars      = str_repeat( '★', $rating_stars_full ) . str_repeat( '☆', 5 - $rating_stars_full );
	$review_comments   = [];

	if ( $review_count > 0 ) {
		$review_comments = get_comments(
			[
				'post_id' => $product_id,
				'status'  => 'approve',
				'number'  => 3,
			]
		);
	}

	if ( is_array( $product_terms ) && ! empty( $product_terms ) && $product_terms[0] instanceof WP_Term ) {
		$primary_term = $product_terms[0];
	}

	if ( '' === trim( wp_strip_all_tags( $description ) ) ) {
		$description = $short_description;
	}

	if ( '' === trim( $support_title ) ) {
		$support_title = __( 'Compra asistida', 'mauswp' );
	}

	if ( '' === trim( $support_text ) ) {
		$support_text = __( 'Si necesitas confirmar compatibilidades, medidas o acabado, revisa la ficha y luego consúltanos con el producto exacto.', 'mauswp' );
	}
	?>
	<main id="primary" class="shop-product bg-site py-12 lg:py-16">
		<div class="container space-y-10 lg:space-y-12">
			<?php mauswp_yoast_breadcrumbs( 'shop-product__breadcrumbs' ); ?>

			<article <?php post_class( 'shop-product__layout' ); ?>>
				<section class="shop-product__gallery card" aria-label="<?php esc_attr_e( 'Galería del producto', 'mauswp' ); ?>" data-product-gallery>
					<?php if ( ! empty( $product_gallery_ids ) ) : ?>
						<div class="shop-product__gallery-main">
							<div class="swiper shop-product__gallery-swiper" data-product-gallery-main>
								<div class="swiper-wrapper">
									<?php foreach ( $product_gallery_ids as $gallery_index => $gallery_image_id ) : ?>
										<div class="swiper-slide">
											<button class="shop-product__gallery-zoom-trigger" type="button" data-product-gallery-open data-gallery-index="<?php echo esc_attr( (string) $gallery_index ); ?>" aria-label="<?php esc_attr_e( 'Ampliar imagen del producto', 'mauswp' ); ?>">
												<?php
												echo wp_get_attachment_image(
													$gallery_image_id,
													'large',
													false,
													[
														'class'    => 'shop-product__image',
														'loading'  => 0 === $gallery_index ? 'eager' : 'lazy',
														'decoding' => 'async',
													]
												);
												?>
											</button>
										</div>
									<?php endforeach; ?>
								</div>
								<?php if ( count( $product_gallery_ids ) > 1 ) : ?>
									<button class="shop-product__gallery-nav shop-product__gallery-nav--prev" type="button" data-product-gallery-prev aria-label="<?php esc_attr_e( 'Imagen anterior', 'mauswp' ); ?>">&larr;</button>
									<button class="shop-product__gallery-nav shop-product__gallery-nav--next" type="button" data-product-gallery-next aria-label="<?php esc_attr_e( 'Imagen siguiente', 'mauswp' ); ?>">&rarr;</button>
									<div class="shop-product__gallery-pagination" data-product-gallery-pagination></div>
								<?php endif; ?>
							</div>
						</div>

						<?php if ( count( $product_gallery_ids ) > 1 ) : ?>
							<div class="shop-product__thumbs" aria-label="<?php esc_attr_e( 'Miniaturas del producto', 'mauswp' ); ?>">
								<?php foreach ( $product_gallery_ids as $gallery_index => $gallery_image_id ) : ?>
									<button class="shop-product__thumb <?php echo 0 === $gallery_index ? esc_attr( 'is-active' ) : ''; ?>" type="button" data-product-gallery-thumb data-gallery-index="<?php echo esc_attr( (string) $gallery_index ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ver imagen %d', 'mauswp' ), $gallery_index + 1 ) ); ?>">
										<?php echo wp_get_attachment_image( $gallery_image_id, 'thumbnail', false, [ 'class' => 'shop-product__thumb-image', 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
									</button>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>

						<div class="shop-product__lightbox" hidden data-product-gallery-lightbox aria-modal="true" role="dialog" aria-label="<?php esc_attr_e( 'Galería ampliada del producto', 'mauswp' ); ?>">
							<button class="shop-product__lightbox-backdrop" type="button" data-product-gallery-close aria-label="<?php esc_attr_e( 'Cerrar galería', 'mauswp' ); ?>"></button>
							<div class="shop-product__lightbox-panel">
								<button class="shop-product__lightbox-close" type="button" data-product-gallery-close aria-label="<?php esc_attr_e( 'Cerrar galería', 'mauswp' ); ?>">&times;</button>
								<div class="swiper shop-product__lightbox-swiper" data-product-gallery-lightbox-swiper>
									<div class="swiper-wrapper">
										<?php foreach ( $product_gallery_ids as $gallery_image_id ) : ?>
											<div class="swiper-slide">
												<div class="swiper-zoom-container">
													<?php echo wp_get_attachment_image( $gallery_image_id, 'full', false, [ 'class' => 'shop-product__lightbox-image', 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
												</div>
											</div>
										<?php endforeach; ?>
									</div>
									<?php if ( count( $product_gallery_ids ) > 1 ) : ?>
										<button class="shop-product__lightbox-nav shop-product__lightbox-nav--prev" type="button" data-product-gallery-lightbox-prev aria-label="<?php esc_attr_e( 'Imagen anterior', 'mauswp' ); ?>">&larr;</button>
										<button class="shop-product__lightbox-nav shop-product__lightbox-nav--next" type="button" data-product-gallery-lightbox-next aria-label="<?php esc_attr_e( 'Imagen siguiente', 'mauswp' ); ?>">&rarr;</button>
									<?php endif; ?>
								</div>
								<p class="shop-product__lightbox-help"><?php esc_html_e( 'Haz doble clic o pellizca para ampliar. Arrastra para desplazarte.', 'mauswp' ); ?></p>
							</div>
						</div>
					<?php else : ?>
						<div class="shop-product__gallery-main">
							<div class="shop-product__image-placeholder" aria-hidden="true"></div>
						</div>
					<?php endif; ?>
				</section>

				<section class="shop-product__summary card">
					<div class="shop-product__summary-head">
						<?php if ( $primary_term instanceof WP_Term ) : ?>
							<p class="eyebrow"><?php echo esc_html( $primary_term->name ); ?></p>
						<?php endif; ?>
						<h1 class="shop-product__title"><?php the_title(); ?></h1>
						<?php if ( $review_count > 0 ) : ?>
							<a class="shop-product__rating-summary" href="#product-reviews" aria-label="<?php echo esc_attr( sprintf( _n( 'Ver %s valoración', 'Ver %s valoraciones', $review_count, 'mauswp' ), number_format_i18n( $review_count ) ) ); ?>">
								<span class="shop-product__rating-stars" aria-hidden="true"><?php echo esc_html( $rating_stars ); ?></span>
								<span><?php echo esc_html( sprintf( _n( '%s reseña', '%s reseñas', $review_count, 'mauswp' ), number_format_i18n( $review_count ) ) ); ?></span>
							</a>
						<?php endif; ?>
						<?php if ( '' !== $product->get_price_html() ) : ?>
							<div class="shop-product__price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
						<?php endif; ?>
					</div>

					<?php if ( '' !== trim( wp_strip_all_tags( $short_description ) ) ) : ?>
						<div class="shop-product__intro"><?php echo wp_kses_post( wpautop( $short_description ) ); ?></div>
					<?php endif; ?>

					<div class="shop-product__purchase">
						<?php woocommerce_template_single_add_to_cart(); ?>
					</div>

					<div class="shop-product__support">
						<p class="shop-product__support-label"><?php echo esc_html( $support_title ); ?></p>
						<p class="shop-product__support-text"><?php echo esc_html( $support_text ); ?></p>
					</div>
				</section>
			</article>

			<section class="shop-product__details card">
				<div class="shop-product__details-header">
					<p class="eyebrow"><?php esc_html_e( 'Detalles', 'mauswp' ); ?></p>
					<h2 class="section-title"><?php esc_html_e( 'Información del producto', 'mauswp' ); ?></h2>
				</div>
				<?php if ( $has_builder ) : ?>
					<div class="shop-product__content max-w-none">
						<?php mauswp_render_product_editorial_builder( $product_id ); ?>
					</div>
				<?php else : ?>
					<div class="shop-product__content entry-content max-w-none">
						<?php echo wp_kses_post( apply_filters( 'the_content', $description ) ); ?>
					</div>
				<?php endif; ?>
			</section>

			<?php if ( $review_count > 0 ) : ?>
				<section class="shop-product__reviews card" id="product-reviews" aria-labelledby="product-reviews-title">
					<div class="shop-product__reviews-header">
						<div>
							<p class="eyebrow"><?php esc_html_e( 'Opiniones reales', 'mauswp' ); ?></p>
							<h2 class="section-title" id="product-reviews-title"><?php esc_html_e( 'Valoraciones de clientes', 'mauswp' ); ?></h2>
						</div>
						<div class="shop-product__reviews-score" aria-label="<?php echo esc_attr( sprintf( _n( '%s reseña', '%s reseñas', $review_count, 'mauswp' ), number_format_i18n( $review_count ) ) ); ?>">
							<span class="shop-product__rating-stars" aria-hidden="true"><?php echo esc_html( $rating_stars ); ?></span>
							<span class="shop-product__reviews-score-count"><?php echo esc_html( sprintf( _n( '%s reseña', '%s reseñas', $review_count, 'mauswp' ), number_format_i18n( $review_count ) ) ); ?></span>
						</div>
					</div>

					<?php if ( ! empty( $review_comments ) ) : ?>
						<div class="shop-product__reviews-list">
						<?php foreach ( $review_comments as $review_comment ) : ?>
							<?php
							if ( ! $review_comment instanceof WP_Comment ) {
								continue;
							}

							$comment_rating = (int) get_comment_meta( $review_comment->comment_ID, 'rating', true );
							$comment_rating = max( 0, min( 5, $comment_rating ) );
							?>
							<article class="shop-product__review">
								<header class="shop-product__review-header">
									<div>
										<p class="shop-product__review-author"><?php echo esc_html( get_comment_author( $review_comment ) ); ?></p>
										<time class="shop-product__review-date" datetime="<?php echo esc_attr( get_comment_date( DATE_W3C, $review_comment ) ); ?>"><?php echo esc_html( get_comment_date( '', $review_comment ) ); ?></time>
									</div>
									<?php if ( $comment_rating > 0 ) : ?>
										<span class="shop-product__rating-stars" aria-label="<?php echo esc_attr( sprintf( __( 'Valoración %d de 5', 'mauswp' ), $comment_rating ) ); ?>"><?php echo esc_html( str_repeat( '★', $comment_rating ) . str_repeat( '☆', 5 - $comment_rating ) ); ?></span>
									<?php endif; ?>
								</header>
								<div class="shop-product__review-content">
									<?php echo wp_kses_post( wpautop( get_comment_text( $review_comment ) ) ); ?>
								</div>
							</article>
						<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</section>
			<?php endif; ?>

			<?php
			$related_category_slugs = [];
			if ( $primary_term instanceof WP_Term ) {
				$related_category_slugs[] = $primary_term->slug;
			} elseif ( is_array( $product_terms ) && ! empty( $product_terms ) ) {
				foreach ( $product_terms as $pt ) {
					if ( $pt instanceof WP_Term ) {
						$related_category_slugs[] = $pt->slug;
						break;
					}
				}
			}

			$related_products = [];
			if ( ! empty( $related_category_slugs ) && function_exists( 'wc_get_products' ) ) {
				$related_products = wc_get_products(
					[
						'status'   => 'publish',
						'limit'    => 8,
						'category' => $related_category_slugs,
						'exclude'  => [ $product_id ],
						'orderby'  => 'rand',
					]
				);
			}
			?>

			<?php if ( ! empty( $related_products ) ) : ?>
				<section class="shop-related">
					<div class="shop-related__header">
						<p class="eyebrow"><?php esc_html_e( 'Descubre más', 'mauswp' ); ?></p>
						<h2 class="section-title"><?php esc_html_e( 'Productos relacionados', 'mauswp' ); ?></h2>
					</div>
					<div class="shop-related__swiper" data-related-swiper>
						<div class="swiper">
							<div class="swiper-wrapper">
								<?php foreach ( $related_products as $related_product ) : ?>
									<?php if ( ! $related_product instanceof WC_Product ) { continue; } ?>
									<?php
									$rel_image_id  = $related_product->get_image_id();
									$rel_image_url = $rel_image_id ? wp_get_attachment_image_url( $rel_image_id, 'large' ) : '';
									$rel_image_alt = $rel_image_id ? (string) get_post_meta( $rel_image_id, '_wp_attachment_image_alt', true ) : '';
									?>
									<div class="swiper-slide">
										<article class="shop-related__card">
											<a class="shop-related__card-link" href="<?php echo esc_url( $related_product->get_permalink() ); ?>">
												<div class="shop-related__card-media">
													<?php if ( '' !== $rel_image_url ) : ?>
														<img class="shop-related__card-image" src="<?php echo esc_url( $rel_image_url ); ?>" alt="<?php echo esc_attr( $rel_image_alt ); ?>" loading="lazy">
													<?php else : ?>
														<div class="shop-related__card-placeholder" aria-hidden="true"></div>
													<?php endif; ?>
												</div>
												<div class="shop-related__card-body">
													<?php if ( '' !== $related_product->get_price_html() ) : ?>
														<div class="shop-related__card-price"><?php echo wp_kses_post( $related_product->get_price_html() ); ?></div>
													<?php endif; ?>
													<h3 class="shop-related__card-title"><?php echo esc_html( $related_product->get_name() ); ?></h3>
													<span class="shop-related__card-cta"><?php esc_html_e( 'Ver producto', 'mauswp' ); ?></span>
												</div>
											</a>
										</article>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="shop-related__nav">
							<button type="button" class="shop-related__prev" data-related-prev aria-label="<?php esc_attr_e( 'Anterior', 'mauswp' ); ?>">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
							</button>
							<button type="button" class="shop-related__next" data-related-next aria-label="<?php esc_attr_e( 'Siguiente', 'mauswp' ); ?>">
								<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
							</button>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<?php mauswp_render_contact_block(); ?>
		</div>
	</main>
	<?php
endwhile;

get_footer();
