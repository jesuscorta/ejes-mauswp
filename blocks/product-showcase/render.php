<?php
/**
 * Product showcase block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$anchor        = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : '';
$align_class   = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignwide';
$block_classes = [ 'product-showcase-block', $align_class ];
$carousel_id   = 'product-showcase-' . wp_unique_id();
$eyebrow       = function_exists( 'get_field' ) ? (string) get_field( 'eyebrow' ) : '';
$title         = function_exists( 'get_field' ) ? (string) get_field( 'title' ) : '';
$description   = function_exists( 'get_field' ) ? (string) get_field( 'description' ) : '';
$scope         = (string) get_field( 'scope' );
$category_id   = get_field( 'product_category' );

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

$shop_url      = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' );
$theme_uri     = get_template_directory_uri();
$scope         = in_array( $scope, [ 'all', 'category' ], true ) ? $scope : 'all';
$category_ids  = is_array( $category_id ) ? array_filter( array_map( 'intval', $category_id ) ) : ( is_numeric( $category_id ) ? [ (int) $category_id ] : [] );
$category_slugs = [];

foreach ( $category_ids as $cat_id ) {
	$term = get_term( $cat_id, 'product_cat' );
	if ( $term instanceof WP_Term && ! is_wp_error( $term ) ) {
		$category_slugs[] = $term->slug;
	}
}

$cache_key = 'mauswp_product_showcase_' . md5( wp_json_encode( [ $scope, $category_slugs ] ) );
$products  = get_transient( $cache_key );

if ( false === $products || ! is_array( $products ) ) {
	$products = [];

	if ( function_exists( 'wc_get_products' ) ) {
		$query_args = [
			'limit'   => 8,
			'status'  => 'publish',
			'orderby' => 'date',
			'order'   => 'DESC',
			'return'  => 'objects',
		];

		if ( 'category' === $scope && ! empty( $category_slugs ) ) {
			$query_args['category'] = $category_slugs;
		}

		$wc_products = wc_get_products( $query_args );

		foreach ( $wc_products as $wc_product ) {
			if ( ! $wc_product instanceof WC_Product ) {
				continue;
			}

			$product_id     = $wc_product->get_id();
			$image_id       = $wc_product->get_image_id();
			$image_alt      = $image_id ? (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
			$product_image  = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';
			$excerpt_source = $wc_product->get_short_description() ?: $wc_product->get_description();

			$products[] = [
				'title'       => $wc_product->get_name(),
				'price'       => wp_kses_post( $wc_product->get_price_html() ?: '' ),
				'url'         => get_permalink( $product_id ) ?: '',
				'excerpt'     => wp_trim_words( wp_strip_all_tags( $excerpt_source ), 18, '...' ),
				'cta_label'   => __( 'Ver producto', 'mauswp' ),
				'image_label' => mb_strtoupper( wp_trim_words( $wc_product->get_name(), 2, '' ) ),
				'image_id'    => $image_id,
				'image_url'   => is_string( $product_image ) ? $product_image : '',
				'image_alt'   => $image_alt,
			];
		}
	}

	set_transient( $cache_key, $products, 5 * MINUTE_IN_SECONDS );
}
?>
<section
	<?php if ( '' !== $anchor ) : ?>
		id="<?php echo esc_attr( $anchor ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
>
	<div class="product-showcase-block__header">
		<div class="product-showcase-block__intro">
			<?php if ( '' !== trim( $eyebrow ) ) : ?>
				<p class="product-showcase-block__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<?php if ( '' !== trim( $title ) ) : ?>
				<h2 class="product-showcase-block__title"><?php echo esc_html( $title ); ?></h2>
			<?php endif; ?>
			<?php if ( '' !== trim( $description ) ) : ?>
				<p class="product-showcase-block__text"><?php echo esc_html( $description ); ?></p>
			<?php endif; ?>
		</div>

		<a class="btn-primary" href="<?php echo esc_url( $shop_url ); ?>">
			<?php esc_html_e( 'Ir a la tienda', 'mauswp' ); ?>
		</a>
	</div>

	<div class="product-showcase-block__carousel" data-product-swiper>
		<?php if ( ! empty( $products ) ) : ?>
			<div class="product-showcase-block__controls">
				<button class="product-showcase-block__nav product-showcase-block__nav--prev" type="button" data-product-prev aria-controls="<?php echo esc_attr( $carousel_id ); ?>" aria-label="<?php esc_attr_e( 'Productos anteriores', 'mauswp' ); ?>">
					<span aria-hidden="true">&larr;</span>
				</button>
				<button class="product-showcase-block__nav product-showcase-block__nav--next" type="button" data-product-next aria-controls="<?php echo esc_attr( $carousel_id ); ?>" aria-label="<?php esc_attr_e( 'Productos siguientes', 'mauswp' ); ?>">
					<span aria-hidden="true">&rarr;</span>
				</button>
			</div>

			<div class="swiper product-showcase-block__swiper" id="<?php echo esc_attr( $carousel_id ); ?>">
				<div class="swiper-wrapper">
					<?php foreach ( $products as $product ) : ?>
						<div class="swiper-slide product-showcase-block__slide">
							<article class="product-showcase-block__card">
								<a class="product-showcase-block__card-link" href="<?php echo esc_url( $product['url'] ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Ver producto: %s', 'mauswp' ), $product['title'] ) ); ?>">
									<div class="product-showcase-block__media">
										<?php if ( ! empty( $product['image_id'] ) ) : ?>
											<?php echo wp_get_attachment_image( (int) $product['image_id'], 'large', false, [ 'class' => 'product-showcase-block__image', 'loading' => 'lazy', 'alt' => (string) $product['image_alt'] ] ); ?>
										<?php elseif ( '' !== $product['image_url'] ) : ?>
											<img class="product-showcase-block__image" src="<?php echo esc_url( $product['image_url'] ); ?>" alt="<?php echo esc_attr( $product['image_alt'] ); ?>" loading="lazy">
										<?php else : ?>
											<div class="product-showcase-block__placeholder" aria-hidden="true">
												<span class="product-showcase-block__placeholder-tag"><?php echo esc_html( $product['image_label'] ); ?></span>
											</div>
										<?php endif; ?>
									</div>

									<div class="product-showcase-block__body">
										<?php if ( '' !== $product['price'] ) : ?>
											<div class="product-showcase-block__price"><?php echo wp_kses_post( $product['price'] ); ?></div>
										<?php endif; ?>
										<h3 class="product-showcase-block__card-title"><?php echo esc_html( $product['title'] ); ?></h3>
										<?php if ( '' !== $product['excerpt'] ) : ?>
											<p class="product-showcase-block__excerpt"><?php echo esc_html( $product['excerpt'] ); ?></p>
										<?php endif; ?>
										<span class="product-showcase-block__cta">
											<?php echo esc_html( $product['cta_label'] ); ?>
											<span aria-hidden="true">&rarr;</span>
										</span>
									</div>
								</a>
							</article>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		<?php else : ?>
			<div class="product-showcase-block__empty">
				<p>
					<?php
					if ( 'category' === $scope && ! empty( $category_slugs ) ) {
						$cat_names = array_map(
							function ( $slug ) {
								$term = get_term_by( 'slug', $slug, 'product_cat' );
								return $term instanceof WP_Term ? $term->name : $slug;
							},
							$category_slugs
						);
						/* translators: %s: comma-separated list of category names */
						echo esc_html( sprintf( __( 'No hay productos publicados en las categorías: %s.', 'mauswp' ), implode( ', ', $cat_names ) ) );
					} else {
						echo esc_html( __( 'Todavía no hay productos publicados en el catálogo.', 'mauswp' ) );
					}
					?>
				</p>
			</div>
		<?php endif; ?>
	</div>
</section>
