<?php
/**
 * Product search AJAX endpoint.
 *
 * @package MausWP
 */

declare(strict_types=1);

/**
 * Build a single product result array for the search response.
 *
 * @param int $product_id Product ID.
 * @return array<string, mixed>|null
 */
function mauswp_build_search_result_item( int $product_id ): ?array {
	$product = wc_get_product( $product_id );

	if ( ! $product ) {
		return null;
	}

	$image_id  = $product->get_image_id();
	$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'thumbnail' ) : '';
	$categories = [];
	$product_cats = get_the_terms( $product_id, 'product_cat' );

	if ( is_array( $product_cats ) && ! empty( $product_cats ) ) {
		$first_cat = $product_cats[0];
		if ( $first_cat instanceof WP_Term ) {
			$categories[] = $first_cat->name;
		}
	}

	return [
		'id'         => $product_id,
		'title'      => wp_strip_all_tags( get_the_title( $product_id ) ),
		'url'        => esc_url_raw( (string) get_permalink( $product_id ) ),
		'price_html' => wp_kses_post( $product->get_price_html() ),
		'image_url'  => esc_url_raw( (string) $image_url ),
		'category'   => wp_strip_all_tags( implode( ', ', $categories ) ),
	];
}

/**
 * Run a product query and return result items.
 *
 * @param array<string, mixed> $args WP_Query args.
 * @return array<int, array<string, mixed>>
 */
function mauswp_run_product_search_query( array $args ): array {
	$defaults = [
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 8,
	];

	$args  = array_merge( $defaults, $args );
	$query = new WP_Query( $args );
	$items = [];

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$item = mauswp_build_search_result_item( get_the_ID() );
			if ( $item ) {
				$items[] = $item;
			}
		}
		wp_reset_postdata();
	}

	return $items;
}

/**
 * Search products via AJAX.
 *
 * Returns exact matches first. If none, tries taxonomy match (categories/tags).
 * If still none, returns configured popular products.
 */
function mauswp_ajax_search_products(): void {
	check_ajax_referer( 'mauswp_search_nonce', 'nonce' );

	$search_term = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	$search_term = trim( $search_term );

	$max_results = 8;
	if ( function_exists( 'get_field' ) ) {
		$max_results = (int) get_field( 'mauswp_search_max_results', 'option' );
		if ( $max_results < 1 || $max_results > 20 ) {
			$max_results = 8;
		}
	}

	$items               = [];
	$is_suggestions      = false;
	$suggestion_reason   = '';
	$enable_tax_search   = true;
	$suggestion_heading  = __( '¿Quizás buscabas...?', 'mauswp' );
	$popular_heading     = __( 'Productos populares', 'mauswp' );

	if ( function_exists( 'get_field' ) ) {
		$enable_tax_search  = (bool) get_field( 'mauswp_search_enable_taxonomy_search', 'option' );
		$suggestion_heading = (string) ( get_field( 'mauswp_search_suggestions_heading', 'option' ) ?: $suggestion_heading );
		$popular_heading    = (string) ( get_field( 'mauswp_search_popular_heading', 'option' ) ?: $popular_heading );
	}

	// 1. Exact match search (title, content, excerpt).
	if ( '' !== $search_term ) {
		$items = mauswp_run_product_search_query(
			[
				'posts_per_page' => $max_results,
				's'              => $search_term,
				'orderby'        => 'relevance',
			]
		);
	}

	// 2. Fallback: search by category/tag name.
	if ( empty( $items ) && '' !== $search_term && $enable_tax_search ) {
		$matched_term_ids = [];

		$cat_terms = get_terms(
			[
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'name__like' => $search_term,
				'number'     => 10,
			]
		);

		if ( ! is_wp_error( $cat_terms ) && is_array( $cat_terms ) ) {
			foreach ( $cat_terms as $term ) {
				if ( $term instanceof WP_Term ) {
					$matched_term_ids[] = $term->term_id;
				}
			}
		}

		$tag_terms = get_terms(
			[
				'taxonomy'   => 'product_tag',
				'hide_empty' => true,
				'name__like' => $search_term,
				'number'     => 10,
			]
		);

		if ( ! is_wp_error( $tag_terms ) && is_array( $tag_terms ) ) {
			foreach ( $tag_terms as $term ) {
				if ( $term instanceof WP_Term ) {
					$matched_term_ids[] = $term->term_id;
				}
			}
		}

		$matched_term_ids = array_unique( array_filter( $matched_term_ids ) );

		if ( ! empty( $matched_term_ids ) ) {
			$items = mauswp_run_product_search_query(
				[
					'posts_per_page' => $max_results,
					'tax_query'      => [
						[
							'taxonomy' => 'product_cat',
							'field'    => 'term_id',
							'terms'    => $matched_term_ids,
							'operator' => 'IN',
						],
					],
					'orderby' => 'title',
					'order'   => 'ASC',
				]
			);

			if ( ! empty( $items ) ) {
				$is_suggestions    = true;
				$suggestion_reason = $suggestion_heading;
			}
		}
	}

	// 3. Final fallback: popular (configured) products.
	if ( empty( $items ) ) {
		$items = mauswp_ajax_get_popular_product_items( $max_results );
		if ( ! empty( $items ) ) {
			$is_suggestions    = true;
			$suggestion_reason = $popular_heading;
		}
	}

	wp_send_json_success(
		[
			'items'             => $items,
			'is_suggestions'    => $is_suggestions,
			'suggestion_reason' => $suggestion_reason,
			'search_term'       => $search_term,
		]
	);
}
add_action( 'wp_ajax_mauswp_search_products', 'mauswp_ajax_search_products' );
add_action( 'wp_ajax_nopriv_mauswp_search_products', 'mauswp_ajax_search_products' );

/**
 * Get suggested products for the search dropdown (empty-query suggestions).
 */
function mauswp_ajax_search_suggestions(): void {
	check_ajax_referer( 'mauswp_search_nonce', 'nonce' );
	$items = mauswp_ajax_get_popular_product_items( 6 );
	wp_send_json_success( $items );
}
add_action( 'wp_ajax_mauswp_search_suggestions', 'mauswp_ajax_search_suggestions' );
add_action( 'wp_ajax_nopriv_mauswp_search_suggestions', 'mauswp_ajax_search_suggestions' );

/**
 * Retrieve popular product items from ACF configuration.
 *
 * @param int $limit Max items.
 * @return array<int, array<string, mixed>>
 */
function mauswp_ajax_get_popular_product_items( int $limit = 6 ): array {
	if ( ! function_exists( 'get_field' ) ) {
		return [];
	}

	$suggested_ids = get_field( 'mauswp_search_suggested_products', 'option' );

	if ( ! is_array( $suggested_ids ) || empty( $suggested_ids ) ) {
		return [];
	}

	return mauswp_run_product_search_query(
		[
			'posts_per_page' => $limit,
			'post__in'       => array_map( 'intval', $suggested_ids ),
			'orderby'        => 'post__in',
		]
	);
}
