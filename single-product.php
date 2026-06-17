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
	$description       = get_the_content();
	$shop_url          = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/tienda/' );
	$support_title     = function_exists( 'get_field' ) ? (string) get_field( 'mauswp_product_support_title', 'option' ) : '';
	$support_text      = function_exists( 'get_field' ) ? (string) get_field( 'mauswp_product_support_text', 'option' ) : '';
	$has_builder       = function_exists( 'mauswp_product_has_editorial_builder' ) ? mauswp_product_has_editorial_builder( $product_id ) : false;

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
			<nav class="shop-product__breadcrumbs" aria-label="<?php esc_attr_e( 'Ruta del producto', 'mauswp' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Inicio', 'mauswp' ); ?></a>
				<span aria-hidden="true">/</span>
				<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'Catálogo', 'mauswp' ); ?></a>
				<?php if ( $primary_term instanceof WP_Term ) : ?>
					<span aria-hidden="true">/</span>
					<a href="<?php echo esc_url( get_term_link( $primary_term ) ); ?>"><?php echo esc_html( $primary_term->name ); ?></a>
				<?php endif; ?>
			</nav>

			<article <?php post_class( 'shop-product__layout' ); ?>>
				<section class="shop-product__gallery card" aria-label="<?php esc_attr_e( 'Galería del producto', 'mauswp' ); ?>">
					<div class="shop-product__gallery-main">
						<?php if ( $main_image_id > 0 ) : ?>
							<?php echo wp_get_attachment_image( $main_image_id, 'full', false, [ 'class' => 'shop-product__image' ] ); ?>
						<?php else : ?>
							<div class="shop-product__image-placeholder" aria-hidden="true"></div>
						<?php endif; ?>
					</div>

					<?php if ( ! empty( $gallery_image_ids ) ) : ?>
						<div class="shop-product__thumbs">
							<?php foreach ( $gallery_image_ids as $gallery_image_id ) : ?>
								<div class="shop-product__thumb">
									<?php echo wp_get_attachment_image( (int) $gallery_image_id, 'medium_large', false, [ 'class' => 'shop-product__thumb-image' ] ); ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</section>

				<section class="shop-product__summary card">
					<div class="shop-product__summary-head">
						<?php if ( $primary_term instanceof WP_Term ) : ?>
							<p class="eyebrow"><?php echo esc_html( $primary_term->name ); ?></p>
						<?php endif; ?>
						<h1 class="shop-product__title"><?php the_title(); ?></h1>
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
		</div>
	</main>
	<?php
endwhile;

get_footer();
