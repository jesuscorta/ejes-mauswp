<?php
/**
 * Theme helper functions.
 *
 * @package MausWP
 */

declare(strict_types=1);

if ( ! function_exists( 'mauswp_fallback_menu' ) ) {
	/**
	 * Fallback menu output for unassigned locations.
	 *
	 * @param array<string, mixed> $args Nav menu args.
	 */
	function mauswp_fallback_menu( array $args = [] ): void {
		$menu_class = isset( $args['menu_class'] ) ? (string) $args['menu_class'] : '';

		echo '<ul class="' . esc_attr( $menu_class ) . '">';
		echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Inicio', 'mauswp' ) . '</a></li>';
		echo '<li><a href="' . esc_url( home_url( '/nosotros/' ) ) . '">' . esc_html__( 'Nosotros', 'mauswp' ) . '</a></li>';
		echo '<li><a href="' . esc_url( home_url( '/noticias/' ) ) . '">' . esc_html__( 'Noticias', 'mauswp' ) . '</a></li>';
		echo '<li><a href="' . esc_url( home_url( '/contacto/' ) ) . '">' . esc_html__( 'Contacto', 'mauswp' ) . '</a></li>';
		echo '</ul>';
	}
}

/**
 * Add a custom class to the catalog item in the primary menu.
 *
 * @param array<int, WP_Post> $items Menu items.
 * @param stdClass            $args  Nav menu arguments.
 * @return array<int, WP_Post>
 */
function mauswp_mark_catalog_menu_item( array $items, stdClass $args ): array {
	if ( empty( $args->theme_location ) || 'primary' !== $args->theme_location ) {
		return $items;
	}

	$shop_page_id  = function_exists( 'wc_get_page_id' ) ? (int) wc_get_page_id( 'shop' ) : 0;
	$shop_page_url = $shop_page_id > 0 ? get_permalink( $shop_page_id ) : '';

	foreach ( $items as $item ) {
		$title = isset( $item->title ) ? sanitize_title( (string) $item->title ) : '';
		$url   = isset( $item->url ) ? untrailingslashit( (string) $item->url ) : '';

		$is_catalog_item = false;

		if ( $shop_page_id > 0 && isset( $item->object_id ) && (int) $item->object_id === $shop_page_id ) {
			$is_catalog_item = true;
		} elseif ( '' !== $shop_page_url && $url === untrailingslashit( (string) $shop_page_url ) ) {
			$is_catalog_item = true;
		} elseif ( in_array( $title, [ 'catalogo', 'catalogo' ], true ) ) {
			$is_catalog_item = true;
		}

		if ( $is_catalog_item ) {
			$item->classes   = is_array( $item->classes ) ? $item->classes : [];
			$item->classes[] = 'menu-item--catalog-trigger';
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'mauswp_mark_catalog_menu_item', 10, 2 );

/**
 * Build grouped WooCommerce category data for the catalog mega menu.
 *
 * @return array<int, array<string, mixed>>
 */
function mauswp_get_catalog_mega_menu_categories(): array {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return [];
	}

	$included_term_ids = [];
	$excluded_term_ids = [];

	if ( function_exists( 'get_field' ) ) {
		$included_term_ids = get_field( 'mauswp_catalog_mega_included_categories', 'option' );
		$excluded_term_ids = get_field( 'mauswp_catalog_mega_excluded_categories', 'option' );
	}

	$included_term_ids = is_array( $included_term_ids )
		? array_values( array_filter( array_map( 'intval', $included_term_ids ) ) )
		: [];

	$excluded_term_ids = is_array( $excluded_term_ids )
		? array_values( array_filter( array_map( 'intval', $excluded_term_ids ) ) )
		: [];

	$term_args = [
		'taxonomy'   => 'product_cat',
		'hide_empty' => true,
		'parent'     => 0,
		'number'     => 12,
		'orderby'    => 'name',
		'order'      => 'ASC',
	];

	if ( ! empty( $included_term_ids ) ) {
		$term_args['include'] = $included_term_ids;
		$term_args['exclude'] = [];
	} else {
		$term_args['exclude'] = $excluded_term_ids;
	}

	$terms = get_terms( $term_args );

	if ( is_wp_error( $terms ) || empty( $terms ) ) {
		return [];
	}

	$categories = [];

	foreach ( $terms as $term ) {
		if ( ! $term instanceof WP_Term ) {
			continue;
		}

		$child_args = [
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => $term->term_id,
			'number'     => 5,
			'orderby'    => 'name',
			'order'      => 'ASC',
		];

		if ( ! empty( $included_term_ids ) ) {
			$child_args['include'] = $included_term_ids;
			$child_args['exclude'] = [];
		} else {
			$child_args['exclude'] = $excluded_term_ids;
		}

		$children = get_terms( $child_args );

		$child_items = [];

		if ( ! is_wp_error( $children ) && is_array( $children ) ) {
			foreach ( $children as $child ) {
				if ( ! $child instanceof WP_Term ) {
					continue;
				}

				$child_items[] = [
					'name' => $child->name,
					'url'  => get_term_link( $child ),
				];
			}
		}

		$categories[] = [
			'name'     => $term->name,
			'url'      => get_term_link( $term ),
			'children' => $child_items,
		];
	}

	return array_values(
		array_filter(
			$categories,
			static function ( array $category ): bool {
				return ! is_wp_error( $category['url'] ?? null );
			}
		)
	);
}

/**
 * Limit main news queries to 9 posts per page.
 *
 * @param WP_Query $query Main query instance.
 */
function mauswp_limit_news_posts_per_page( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_home() && ! $query->is_category() && ! $query->is_tag() && ! $query->is_date() && ! $query->is_author() ) {
		return;
	}

	$post_type = $query->get( 'post_type' );

	if ( is_array( $post_type ) && ! in_array( 'post', $post_type, true ) ) {
		return;
	}

	if ( is_string( $post_type ) && '' !== $post_type && 'post' !== $post_type ) {
		return;
	}

	$query->set( 'posts_per_page', 9 );
}
add_action( 'pre_get_posts', 'mauswp_limit_news_posts_per_page' );

/**
 * Get selected product category slugs from the current request.
 *
 * @return array<int, string>
 */
function mauswp_get_requested_product_filter_category_slugs(): array {
	if ( empty( $_GET['product_cats'] ) ) {
		return [];
	}

	$slugs = wp_unslash( $_GET['product_cats'] );

	if ( ! is_array( $slugs ) ) {
		return [];
	}

	return array_values(
		array_filter(
			array_map(
				static function ( $slug ): string {
					return sanitize_title( (string) $slug );
				},
				$slugs
			),
			static function ( string $slug ): bool {
				return '' !== $slug;
			}
		)
	);
}

/**
 * Get minimum requested product price.
 *
 * @return float|null
 */
function mauswp_get_requested_product_filter_min_price(): ?float {
	if ( ! isset( $_GET['price_min'] ) ) {
		return null;
	}

	$value = str_replace( ',', '.', trim( (string) wp_unslash( $_GET['price_min'] ) ) );

	if ( '' === $value || ! is_numeric( $value ) ) {
		return null;
	}

	$price = (float) $value;

	return $price >= 0 ? $price : null;
}

/**
 * Get maximum requested product price.
 *
 * @return float|null
 */
function mauswp_get_requested_product_filter_max_price(): ?float {
	if ( ! isset( $_GET['price_max'] ) ) {
		return null;
	}

	$value = str_replace( ',', '.', trim( (string) wp_unslash( $_GET['price_max'] ) ) );

	if ( '' === $value || ! is_numeric( $value ) ) {
		return null;
	}

	$price = (float) $value;

	return $price >= 0 ? $price : null;
}

/**
 * Apply basic shop filters to the main WooCommerce product archive query.
 *
 * @param WP_Query $query Main query instance.
 */
function mauswp_filter_product_archive_query( WP_Query $query ): void {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'product' ) && ! $query->is_tax( 'product_cat' ) ) {
		return;
	}

	$selected_categories = mauswp_get_requested_product_filter_category_slugs();

	if ( ! empty( $selected_categories ) ) {
		$tax_query = $query->get( 'tax_query' );
		$tax_query = is_array( $tax_query ) ? $tax_query : [];

		$tax_query = array_values(
			array_filter(
				$tax_query,
				static function ( $clause ): bool {
					return ! is_array( $clause ) || ( $clause['taxonomy'] ?? '' ) !== 'product_cat';
				}
			)
		);

		$tax_query[] = [
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => $selected_categories,
			'operator' => 'IN',
		];

		$query->set( 'tax_query', $tax_query );
	}


}
add_action( 'pre_get_posts', 'mauswp_filter_product_archive_query' );

/**
 * Get min and max product prices for the current archive context.
 *
 * @param array<int, string> $category_slugs Category slugs to constrain the bounds.
 * @return array<string, float>
 */
function mauswp_get_product_archive_price_bounds( array $category_slugs = [] ): array {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return [
			'min' => 0,
			'max' => 0,
		];
	}

	$query_args = [
		'status' => 'publish',
		'limit'  => -1,
		'return' => 'ids',
	];

	if ( ! empty( $category_slugs ) ) {
		$query_args['category'] = $category_slugs;
	}

	$product_ids = wc_get_products( $query_args );

	if ( ! is_array( $product_ids ) || empty( $product_ids ) ) {
		return [
			'min' => 0,
			'max' => 0,
		];
	}

	$prices = [];

	foreach ( $product_ids as $product_id ) {
		$price = get_post_meta( (int) $product_id, '_price', true );

		if ( '' === $price || ! is_numeric( $price ) ) {
			continue;
		}

		$prices[] = (float) $price;
	}

	if ( empty( $prices ) ) {
		return [
			'min' => 0,
			'max' => 0,
		];
	}

	return [
		'min' => (float) floor( min( $prices ) ),
		'max' => (float) ceil( max( $prices ) ),
	];
}

/**
 * Generate a table of contents from H2 headings in content.
 * Also adds IDs to the headings for anchor navigation.
 *
 * @param string $content HTML content.
 * @return array<string, mixed> ['toc' => array, 'content' => string]
 */
function mauswp_generate_toc( string $content ): array {
	if ( empty( $content ) ) {
		return [
			'toc'     => [],
			'content' => $content,
		];
	}

	$toc = [];

	// Find all h2 headings.
	$content = preg_replace_callback(
		'/<h2[^>]*>(.*?)<\/h2>/i',
		static function ( array $matches ) use ( &$toc ): string {
			$text = wp_strip_all_tags( $matches[1] );
			$slug = sanitize_title( $text );
			// Ensure unique slug.
			$original_slug = $slug;
			$counter       = 1;
			$existing      = array_column( $toc, 'id' );
			while ( in_array( $slug, $existing, true ) ) {
				$slug = $original_slug . '-' . $counter;
				++$counter;
			}
			$toc[] = [
				'text' => $text,
				'id'   => $slug,
			];
			return '<h2 id="' . esc_attr( $slug ) . '">' . $matches[1] . '</h2>';
		},
		$content
	);

	return [
		'toc'     => $toc,
		'content' => $content,
	];
}

/**
 * Get related posts for the current single post.
 *
 * @param int   $post_id  Current post ID.
 * @param int   $limit    Max posts.
 * @return array<int, WP_Post>
 */
function mauswp_get_related_posts( int $post_id, int $limit = 4 ): array {
	$categories = get_the_category( $post_id );
	if ( empty( $categories ) || is_wp_error( $categories ) ) {
		return [];
	}

	$cat_ids = array_filter(
		array_map(
			static function ( $cat ) {
				return $cat instanceof WP_Term ? $cat->term_id : 0;
			},
			$categories
		)
	);

	if ( empty( $cat_ids ) ) {
		return [];
	}

	$query = new WP_Query(
		[
			'category__in'   => $cat_ids,
			'post__not_in'   => [ $post_id ],
			'posts_per_page' => $limit,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'DESC',
		]
	);

	$posts = $query->posts;
	wp_reset_postdata();

	return is_array( $posts ) ? $posts : [];
}

/**
 * Output Yoast SEO breadcrumbs if available.
 *
 * @param string $wrapper_class Additional CSS class for the wrapper.
 */
function mauswp_yoast_breadcrumbs( string $wrapper_class = '' ): void {
	if ( ! function_exists( 'yoast_breadcrumb' ) ) {
		return;
	}

	$class = 'breadcrumbs';

	if ( '' !== trim( $wrapper_class ) ) {
		$class .= ' ' . trim( $wrapper_class );
	}

	yoast_breadcrumb(
		'<nav class="' . esc_attr( $class ) . '" aria-label="' . esc_attr__( 'Migas de pan', 'mauswp' ) . '">',
		'</nav>'
	);
}

/**
 * Render the contact form block markup.
 *
 * @param array<string, mixed> $args Optional args: anchor, align, className.
 */
function mauswp_render_contact_block( array $args = [] ): void {
	$anchor        = ! empty( $args['anchor'] ) ? (string) $args['anchor'] : 'contacto';
	$align_class   = ! empty( $args['align'] ) ? 'align' . sanitize_html_class( (string) $args['align'] ) : 'alignwide';
	$block_classes = [ 'contact-form-block', $align_class ];
	$contact_phone = '608.725.197';
	$contact_email = 'administracion@sumagrogranada.com';
	$facebook_url  = 'https://www.facebook.com/Granadina-Industrial-Agr%C3%ADcola-100453171807218';
	$contact_title = __( 'Hablemos de tu proyecto', 'mauswp' );
	$contact_intro = __( 'Cuéntanos qué necesitas y te responderemos en menos de 24h.', 'mauswp' );
	$email_label   = __( 'Email', 'mauswp' );
	$phone_label   = __( 'Teléfono', 'mauswp' );

	if ( ! empty( $args['className'] ) ) {
		$custom_classes = preg_split( '/\s+/', (string) $args['className'] );

		if ( is_array( $custom_classes ) ) {
			foreach ( $custom_classes as $custom_class ) {
				if ( '' !== $custom_class ) {
					$block_classes[] = sanitize_html_class( $custom_class );
				}
			}
		}
	}

	if ( function_exists( 'get_field' ) ) {
		$contact_phone = (string) ( get_field( 'mauswp_contact_phone', 'option' ) ?: $contact_phone );
		$contact_email = (string) ( get_field( 'mauswp_contact_email', 'option' ) ?: $contact_email );
		$facebook_url  = (string) ( get_field( 'mauswp_facebook_url', 'option' ) ?: $facebook_url );
		$contact_title = (string) ( get_field( 'mauswp_contact_block_title', 'option' ) ?: $contact_title );
		$contact_intro = (string) ( get_field( 'mauswp_contact_block_intro', 'option' ) ?: $contact_intro );
		$email_label   = (string) ( get_field( 'mauswp_contact_block_email_label', 'option' ) ?: $email_label );
		$phone_label   = (string) ( get_field( 'mauswp_contact_block_phone_label', 'option' ) ?: $phone_label );
	}

	$phone_href    = preg_replace( '/[^0-9+]/', '', $contact_phone );
	$whatsapp_href = 'https://wa.me/' . preg_replace( '/\D+/', '', $contact_phone );

	$form_id = (int) apply_filters( 'mauswp_contact_form_id', 0 );

	if ( $form_id <= 0 && class_exists( 'GFAPI' ) ) {
		$forms = GFAPI::get_forms( true );

		if ( is_array( $forms ) && ! empty( $forms[0]['id'] ) ) {
			$form_id = (int) $forms[0]['id'];
		}
	}

	$form_markup = '';

	if ( $form_id > 0 && function_exists( 'gravity_form' ) ) {
		$form_markup = gravity_form(
			$form_id,
			false,
			false,
			false,
			null,
			true,
			0,
			false
		);
	}

	?>
	<section
		<?php if ( '' !== $anchor ) : ?>
			id="<?php echo esc_attr( $anchor ); ?>"
		<?php endif; ?>
		class="<?php echo esc_attr( implode( ' ', array_filter( $block_classes ) ) ); ?>"
	>
		<div class="contact-form-block__shell">
			<div class="contact-form-block__layout">
				<aside class="contact-form-block__details">
					<div class="contact-form-block__copy">
						<p class="contact-form-block__title"><?php echo esc_html( $contact_title ); ?></p>
						<p class="contact-form-block__intro"><?php echo esc_html( $contact_intro ); ?></p>
					</div>

					<ul class="contact-form-block__list">
						<li>
							<a class="contact-form-block__item" href="mailto:<?php echo esc_attr( antispambot( $contact_email ) ); ?>">
								<span class="contact-form-block__item-icon contact-form-block__item-icon--email" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
										<path d="M4 6h16v12H4z" />
										<path d="m4 7 8 6 8-6" />
									</svg>
								</span>
								<span class="contact-form-block__item-copy">
								<span class="contact-form-block__item-label"><?php echo esc_html( $email_label ); ?></span>
								<span class="contact-form-block__item-value"><?php echo esc_html( antispambot( $contact_email ) ); ?></span>
								</span>
							</a>
						</li>
						<li>
							<a class="contact-form-block__item" href="tel:<?php echo esc_attr( $phone_href ); ?>">
								<span class="contact-form-block__item-icon contact-form-block__item-icon--phone" aria-hidden="true">
									<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
										<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.86 19.86 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.86 19.86 0 0 1 2.08 4.18 2 2 0 0 1 4.06 2h3a2 2 0 0 1 2 1.72c.12.9.35 1.77.68 2.6a2 2 0 0 1-.45 2.11L8 9.94a16 16 0 0 0 6.06 6.06l1.51-1.29a2 2 0 0 1 2.11-.45c.83.33 1.7.56 2.6.68A2 2 0 0 1 22 16.92z" />
									</svg>
								</span>
								<span class="contact-form-block__item-copy">
								<span class="contact-form-block__item-label"><?php echo esc_html( $phone_label ); ?></span>
								<span class="contact-form-block__item-value"><?php echo esc_html( $contact_phone ); ?></span>
								</span>
							</a>
						</li>
					</ul>
				</aside>

				<div class="contact-form-block__panel">
					<?php if ( '' !== $form_markup ) : ?>
						<?php echo $form_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php else : ?>
						<div class="contact-form-block__empty">
							<p><?php esc_html_e( 'No se ha encontrado un formulario de Gravity Forms disponible para este bloque.', 'mauswp' ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}
