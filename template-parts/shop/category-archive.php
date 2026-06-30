<?php
/**
 * Shared product category archive layout.
 *
 * @package MausWP
 */

declare(strict_types=1);

$term             = get_queried_object();
$shop_page_id     = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'shop' ) : 0;
$is_product_term  = $term instanceof WP_Term;
$shop_url         = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' );
$title            = $is_product_term ? $term->name : __( 'Catálogo', 'mauswp' );
$description      = $is_product_term ? term_description( $term, 'product_cat' ) : '';
$thumbnail_id     = $is_product_term ? (int) get_term_meta( $term->term_id, 'thumbnail_id', true ) : 0;
$banner_image     = $thumbnail_id > 0 ? wp_get_attachment_image_url( $thumbnail_id, 'full' ) : '';
$banner_image_alt = $thumbnail_id > 0 ? (string) get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true ) : '';
$product_count    = $is_product_term ? (int) $term->count : (int) $GLOBALS['wp_query']->found_posts;
$badge_label      = __( 'Catálogo', 'mauswp' );
$empty_text       = __( 'Todavía no hay productos publicados en esta categoría.', 'mauswp' );
$lead             = $is_product_term
	? __( 'Una categoría pensada para comparar rápido, detectar variantes útiles y pasar de la exploración a la ficha del producto sin ruido visual.', 'mauswp' )
	: __( 'Un catálogo pensado para encontrar rápido la pieza adecuada, comparar opciones con claridad y pasar a la ficha del producto sin fricción.', 'mauswp' );

if ( $is_product_term && function_exists( 'get_field' ) ) {
	$badge_value = (string) get_field( 'mauswp_product_category_badge', $term );
	$lead_value  = (string) get_field( 'mauswp_product_category_lead', $term );
	$empty_value = (string) get_field( 'mauswp_product_category_empty_text', $term );

	if ( '' !== trim( $badge_value ) ) {
		$badge_label = $badge_value;
	}

	if ( '' !== trim( $lead_value ) ) {
		$lead = $lead_value;
	}

	if ( '' !== trim( $empty_value ) ) {
		$empty_text = $empty_value;
	}
}

if ( ! $is_product_term && $shop_page_id > 0 ) {
	$shop_page = get_post( $shop_page_id );

	if ( $shop_page instanceof WP_Post ) {
		$title       = get_the_title( $shop_page );
		$description = function_exists( 'get_field' ) ? (string) get_field( 'mauswp_shop_archive_useful_info', $shop_page_id ) : '';
		$lead        = function_exists( 'get_field' ) ? (string) get_field( 'mauswp_shop_archive_description', $shop_page_id ) : '';
		$image_field = function_exists( 'get_field' ) ? get_field( 'mauswp_shop_archive_image', $shop_page_id ) : null;
		$thumbnail_id = (int) get_post_thumbnail_id( $shop_page_id );

		if ( is_array( $image_field ) ) {
			$banner_image     = isset( $image_field['url'] ) ? (string) $image_field['url'] : '';
			$banner_image_alt = isset( $image_field['alt'] ) ? (string) $image_field['alt'] : '';
		}

		if ( '' === $banner_image && $thumbnail_id > 0 ) {
			$banner_image     = wp_get_attachment_image_url( $thumbnail_id, 'full' ) ?: '';
			$banner_image_alt = (string) get_post_meta( $thumbnail_id, '_wp_attachment_image_alt', true );
		}

		if ( '' === trim( $description ) ) {
			$description = (string) $shop_page->post_content;
		}
	}
}
$pagination_links = paginate_links(
	[
		'type'      => 'array',
		'mid_size'  => 1,
		'prev_text' => __( 'Anterior', 'mauswp' ),
		'next_text' => __( 'Siguiente', 'mauswp' ),
	]
);
$selected_category_slugs = function_exists( 'mauswp_get_requested_product_filter_category_slugs' ) ? mauswp_get_requested_product_filter_category_slugs() : [];
$filter_categories       = get_terms(
	[
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'orderby'    => 'name',
		'order'      => 'ASC',
	]
);
$reset_url               = $is_product_term && $term instanceof WP_Term ? get_term_link( $term ) : $shop_url;

if ( $is_product_term && empty( $selected_category_slugs ) && $term instanceof WP_Term ) {
	$selected_category_slugs = [ $term->slug ];
}

if ( ! $is_product_term && '' !== trim( $description ) ) {
	$description = apply_filters( 'the_content', $description );
}

if ( ! $is_product_term && '' === trim( $lead ) ) {
	$lead = __( 'Un catálogo pensado para encontrar rápido la pieza adecuada, comparar opciones con claridad y pasar a la ficha del producto sin fricción.', 'mauswp' );
}

if ( '' === trim( wp_strip_all_tags( $description ) ) ) {
	$description = wpautop(
		sprintf(
			/* translators: %s: current archive title. */
			__( 'Explora esta selección de %s y revisa cada ficha para comparar opciones, acabados y configuraciones disponibles antes de solicitar información.', 'mauswp' ),
			esc_html( mb_strtolower( $title ) )
		)
	);
}
?>
<main id="primary" class="shop-category bg-site pb-16 lg:pb-20">
	<section class="shop-category__hero">
		<div class="container">
			<?php mauswp_yoast_breadcrumbs( 'shop-category__hero-breadcrumbs' ); ?>
			<div class="shop-category__hero-banner card-heavy">
				<?php if ( '' !== $banner_image ) : ?>
					<img class="shop-category__hero-image" src="<?php echo esc_url( $banner_image ); ?>" alt="<?php echo esc_attr( $banner_image_alt ); ?>">
				<?php else : ?>
					<div class="shop-category__hero-placeholder" aria-hidden="true"></div>
				<?php endif; ?>
				<div class="shop-category__hero-badge">
					<p class="eyebrow"><?php echo esc_html( $badge_label ); ?></p>
				</div>
			</div>

			<div class="shop-category__hero-panel">
				<div class="shop-category__hero-head">
					<h1 class="shop-category__title"><?php echo esc_html( $title ); ?></h1>
					<p class="shop-category__lead"><?php echo esc_html( $lead ); ?></p>
				</div>


			</div>
		</div>
	</section>

	<section class="container shop-category__catalog" data-shop-archive>
		<div class="shop-category__toolbar" data-shop-archive-toolbar>
			<div class="shop-category__results">
				<?php if ( function_exists( 'woocommerce_result_count' ) ) : ?>
					<?php woocommerce_result_count(); ?>
				<?php endif; ?>
			</div>
			<div class="shop-category__toolbar-actions">
				<button class="btn-secondary shop-category__filters-trigger" type="button" data-shop-filters-open aria-controls="shop-category-filters-drawer" aria-expanded="false">
					<?php esc_html_e( 'Filtrar', 'mauswp' ); ?>
				</button>
			</div>
		</div>

		<div class="shop-category__filters-drawer" id="shop-category-filters-drawer" hidden data-shop-filters-drawer>
			<div class="shop-category__filters-backdrop" data-shop-filters-close></div>
			<div class="shop-category__filters-panel">
				<div class="shop-category__filters-topbar">
					<div>
						<p class="shop-category__filters-eyebrow"><?php esc_html_e( 'Filtrar catálogo', 'mauswp' ); ?></p>
						<p class="shop-category__filters-title"><?php esc_html_e( 'Afina la selección', 'mauswp' ); ?></p>
					</div>
					<button class="shop-category__filters-close" type="button" data-shop-filters-close aria-label="<?php esc_attr_e( 'Cerrar filtros', 'mauswp' ); ?>">&times;</button>
				</div>

				<form class="shop-category__filters" method="get" action="<?php echo esc_url( is_string( $reset_url ) ? $reset_url : $shop_url ); ?>" data-shop-filters-form>
					<div class="shop-category__filters-grid">
						<div class="shop-category__filters-group">
							<p class="shop-category__filters-label"><?php esc_html_e( 'Categorías', 'mauswp' ); ?></p>
							<div class="shop-category__filters-options">
								<?php if ( is_array( $filter_categories ) ) : ?>
									<?php foreach ( $filter_categories as $filter_category ) : ?>
										<?php if ( ! $filter_category instanceof WP_Term ) : ?>
											<?php continue; ?>
										<?php endif; ?>
										<label class="shop-category__filter-chip">
											<input type="checkbox" name="product_cats[]" value="<?php echo esc_attr( $filter_category->slug ); ?>" <?php checked( in_array( $filter_category->slug, $selected_category_slugs, true ) ); ?>>
											<span><?php echo esc_html( $filter_category->name ); ?></span>
										</label>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>


					</div>

					<div class="shop-category__filters-actions">
						<button class="btn-primary" type="submit"><?php esc_html_e( 'Aplicar filtros', 'mauswp' ); ?></button>
						<a class="btn-secondary" href="<?php echo esc_url( is_string( $reset_url ) ? $reset_url : $shop_url ); ?>" data-shop-filters-reset><?php esc_html_e( 'Limpiar', 'mauswp' ); ?></a>
					</div>
				</form>
			</div>
		</div>

		<?php if ( have_posts() ) : ?>
			<div class="shop-category__results-area" data-shop-archive-results>
			<div class="shop-category__grid">
				<?php
				while ( have_posts() ) :
					the_post();

					$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;

					if ( ! $product instanceof WC_Product ) {
						continue;
					}

					$image_id  = $product->get_image_id();
					$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : '';
					$image_alt = $image_id ? (string) get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : '';
					?>
					<article <?php post_class( 'shop-category__card' ); ?>>
						<a class="shop-category__card-link" href="<?php the_permalink(); ?>">
							<div class="shop-category__card-media">
								<?php if ( '' !== $image_url ) : ?>
									<img class="shop-category__card-image" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy">
								<?php else : ?>
									<div class="shop-category__card-placeholder" aria-hidden="true"></div>
								<?php endif; ?>
							</div>

							<div class="shop-category__card-body">
								<?php if ( '' !== $product->get_price_html() ) : ?>
									<div class="shop-category__card-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
								<?php endif; ?>
								<h2 class="shop-category__card-title"><?php the_title(); ?></h2>
								<span class="shop-category__card-cta"><?php esc_html_e( 'Ver producto', 'mauswp' ); ?></span>
							</div>
						</a>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<?php if ( is_array( $pagination_links ) && ! empty( $pagination_links ) ) : ?>
				<nav class="shop-category__pagination" aria-label="<?php esc_attr_e( 'Paginación de productos', 'mauswp' ); ?>">
					<?php foreach ( $pagination_links as $pagination_link ) : ?>
						<?php echo wp_kses_post( $pagination_link ); ?>
					<?php endforeach; ?>
				</nav>
			<?php endif; ?>
			</div>
		<?php else : ?>
			<section class="shop-category__empty" data-shop-archive-results>
				<h2 class="text-2xl font-semibold text-slate-900"><?php echo esc_html( $is_product_term ? $empty_text : __( 'Todavía no hay productos publicados en el catálogo.', 'mauswp' ) ); ?></h2>
			</section>
		<?php endif; ?>
	</section>

	<section class="container shop-category__editorial-wrap">
		<div class="shop-category__editorial">
			<p class="shop-category__editorial-eyebrow"><?php esc_html_e( 'Información útil', 'mauswp' ); ?></p>
			<div class="shop-category__editorial-content">
				<?php echo wp_kses_post( $description ); ?>
			</div>
		</div>
	</section>

	<?php mauswp_render_contact_block(); ?>
</main>
