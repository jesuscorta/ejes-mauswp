<?php
/**
 * Theme header.
 *
 * @package MausWP
 */

declare(strict_types=1);

$mauswp_site_name = get_bloginfo( 'name' );
$mauswp_home_url  = home_url( '/' );

$mauswp_header_logo     = null;
$mauswp_catalog_mega_image = null;
$mauswp_top_bar_message = 'DISPONEMOS DE RECAMBIOS ALKO, KNOTT, GEPLASMETAL, WAP Y AXF';
$mauswp_contact_phone   = '608.725.197';
$mauswp_contact_email   = 'administracion@sumagrogranada.com';
$mauswp_facebook_url    = 'https://www.facebook.com/Granadina-Industrial-Agr%C3%ADcola-100453171807218';

$mauswp_search_title        = __( 'Buscar productos', 'mauswp' );
$mauswp_search_placeholder  = __( '¿Qué producto buscas?', 'mauswp' );
$mauswp_search_suggestions_heading = __( 'Productos populares', 'mauswp' );
$mauswp_search_no_results   = __( 'No hay productos para', 'mauswp' );
$mauswp_search_suggestions_fallback_heading = __( '¿Quizás buscabas...?', 'mauswp' );
$mauswp_search_popular_fallback_heading = __( 'Productos populares', 'mauswp' );

$mauswp_catalog_button_label = __( 'Catálogo', 'mauswp' );
$mauswp_catalog_mega_eyebrow = __( 'Catálogo', 'mauswp' );
$mauswp_catalog_mega_title   = __( 'Explora nuestras categorías', 'mauswp' );
$mauswp_catalog_mega_cta_label = __( 'Ver catálogo completo', 'mauswp' );

if ( function_exists( 'get_field' ) ) {
	$mauswp_header_logo     = get_field( 'mauswp_header_logo', 'option' );
	$mauswp_catalog_mega_image = get_field( 'mauswp_catalog_mega_image', 'option' );
	$mauswp_top_bar_message = (string) ( get_field( 'mauswp_top_bar_message', 'option' ) ?: $mauswp_top_bar_message );
	$mauswp_contact_phone   = (string) ( get_field( 'mauswp_contact_phone', 'option' ) ?: $mauswp_contact_phone );
	$mauswp_contact_email   = (string) ( get_field( 'mauswp_contact_email', 'option' ) ?: $mauswp_contact_email );
	$mauswp_facebook_url    = (string) ( get_field( 'mauswp_facebook_url', 'option' ) ?: $mauswp_facebook_url );

	$mauswp_search_title        = (string) ( get_field( 'mauswp_search_title', 'option' ) ?: $mauswp_search_title );
	$mauswp_search_placeholder  = (string) ( get_field( 'mauswp_search_placeholder', 'option' ) ?: $mauswp_search_placeholder );
	$mauswp_search_suggestions_heading = (string) ( get_field( 'mauswp_search_suggestions_heading', 'option' ) ?: $mauswp_search_suggestions_heading );
	$mauswp_search_no_results   = (string) ( get_field( 'mauswp_search_no_results_text', 'option' ) ?: $mauswp_search_no_results );
	$mauswp_search_suggestions_fallback_heading = (string) ( get_field( 'mauswp_search_suggestions_heading', 'option' ) ?: $mauswp_search_suggestions_fallback_heading );
	$mauswp_search_popular_fallback_heading = (string) ( get_field( 'mauswp_search_popular_heading', 'option' ) ?: $mauswp_search_popular_fallback_heading );

	$mauswp_catalog_button_label = (string) ( get_field( 'mauswp_catalog_button_label', 'option' ) ?: $mauswp_catalog_button_label );
	$mauswp_catalog_mega_eyebrow = (string) ( get_field( 'mauswp_catalog_mega_eyebrow', 'option' ) ?: $mauswp_catalog_mega_eyebrow );
	$mauswp_catalog_mega_title   = (string) ( get_field( 'mauswp_catalog_mega_title', 'option' ) ?: $mauswp_catalog_mega_title );
	$mauswp_catalog_mega_cta_label = (string) ( get_field( 'mauswp_catalog_mega_cta_label', 'option' ) ?: $mauswp_catalog_mega_cta_label );
}

$mauswp_phone_href = preg_replace( '/[^0-9+]/', '', $mauswp_contact_phone );
$mauswp_logo_url   = '';
$mauswp_logo_alt   = $mauswp_site_name;
$mauswp_catalog_mega_image_url = '';
$mauswp_catalog_mega_image_alt = __( 'Categorías del catálogo', 'mauswp' );
$mauswp_catalog_categories = function_exists( 'mauswp_get_catalog_mega_menu_categories' ) ? mauswp_get_catalog_mega_menu_categories() : [];
$mauswp_catalog_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/catalogo/' );

if ( is_array( $mauswp_header_logo ) ) {
	$mauswp_logo_url = ! empty( $mauswp_header_logo['url'] ) ? (string) $mauswp_header_logo['url'] : '';
	$mauswp_logo_alt = ! empty( $mauswp_header_logo['alt'] ) ? (string) $mauswp_header_logo['alt'] : $mauswp_logo_alt;
}

if ( is_array( $mauswp_catalog_mega_image ) ) {
	$mauswp_catalog_mega_image_url = ! empty( $mauswp_catalog_mega_image['sizes']['large'] ) ? (string) $mauswp_catalog_mega_image['sizes']['large'] : '';
	$mauswp_catalog_mega_image_alt = ! empty( $mauswp_catalog_mega_image['alt'] ) ? (string) $mauswp_catalog_mega_image['alt'] : $mauswp_catalog_mega_image_alt;

	if ( '' === $mauswp_catalog_mega_image_url && ! empty( $mauswp_catalog_mega_image['url'] ) ) {
		$mauswp_catalog_mega_image_url = (string) $mauswp_catalog_mega_image['url'];
	}
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
	<?php wp_nonce_field( 'mauswp_search_nonce', 'mauswp_search_nonce', false ); ?>
</head>
<body <?php body_class( 'bg-white font-sans text-slate-900 antialiased' ); ?>>
<?php wp_body_open(); ?>
<a class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-white focus:px-4 focus:py-2 focus:text-slate-900" href="#primary">
	<?php esc_html_e( 'Saltar al contenido', 'mauswp' ); ?>
</a>
<header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/95 backdrop-blur" data-site-header>
	<div class="bg-brand-800 text-white">
		<div class="container flex flex-col gap-3 py-3 text-xs font-semibold uppercase tracking-[0.08em] sm:text-sm lg:flex-row lg:items-center lg:justify-between">
			<div class="topbar-marquee" aria-label="<?php echo esc_attr( $mauswp_top_bar_message ); ?>">
				<div class="topbar-marquee__track">
					<span class="topbar-marquee__item"><?php echo esc_html( $mauswp_top_bar_message ); ?></span>
					<span class="topbar-marquee__dot" aria-hidden="true">&bull;</span>
					<span class="topbar-marquee__item"><?php echo esc_html( $mauswp_top_bar_message ); ?></span>
					<span class="topbar-marquee__dot" aria-hidden="true">&bull;</span>
					<span class="topbar-marquee__item"><?php echo esc_html( $mauswp_top_bar_message ); ?></span>
				</div>
			</div>

			<ul class="flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-[0.78rem] normal-case tracking-normal text-white/90 sm:text-sm lg:gap-x-3 lg:justify-end">
				<li>
					<a class="inline-flex items-center gap-2 transition hover:text-white lg:px-[5px] lg:py-[3px]" href="tel:<?php echo esc_attr( $mauswp_phone_href ); ?>">
						<svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.68 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.32 1.85.55 2.81.68A2 2 0 0 1 22 16.92z" />
						</svg>
						<span class="lg:hidden"><?php esc_html_e( 'Llamar', 'mauswp' ); ?></span>
						<span class="hidden lg:inline"><?php echo esc_html( $mauswp_contact_phone ); ?></span>
					</a>
				</li>
				<li>
					<a class="inline-flex items-center gap-2 transition hover:text-white lg:px-[5px] lg:py-[3px]" href="mailto:<?php echo esc_attr( antispambot( $mauswp_contact_email ) ); ?>">
						<svg class="h-4 w-4 shrink-0" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<rect width="20" height="16" x="2" y="4" rx="2" />
							<path d="m22 7-8.97 5.7a2 2 0 0 1-2.06 0L2 7" />
						</svg>
						<span class="lg:hidden"><?php esc_html_e( 'Contacto', 'mauswp' ); ?></span>
						<span class="hidden lg:inline"><?php echo esc_html( antispambot( $mauswp_contact_email ) ); ?></span>
					</a>
				</li>
				<li>
					<a class="inline-flex items-center justify-center transition hover:text-white lg:px-[5px] lg:py-[3px]" href="<?php echo esc_url( $mauswp_facebook_url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'mauswp' ); ?>">
						<svg class="h-4 w-4" viewBox="0 0 24 24" aria-hidden="true" fill="currentColor">
							<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3.5l.5-4h-4V7a1 1 0 0 1 1-1h3z" />
						</svg>
					</a>
				</li>
			</ul>
		</div>
	</div>

	<div class="container relative flex min-h-[5rem] items-center justify-between gap-6 py-4" data-catalog-mega-root>
		<div class="flex items-center gap-3">
			<a class="inline-flex items-center gap-3 text-slate-900" href="<?php echo esc_url( $mauswp_home_url ); ?>" rel="home">
				<?php if ( '' !== $mauswp_logo_url ) : ?>
					<img class="block max-w-full shrink-0 sm:w-[14rem] lg:w-[16rem]" src="<?php echo esc_url( $mauswp_logo_url ); ?>" alt="<?php echo esc_attr( $mauswp_logo_alt ); ?>" width="192" height="96" style="width:12rem;height:auto;">
				<?php else : ?>
					<span class="inline-flex h-11 w-11 items-center justify-center bg-brand-700 text-sm font-bold uppercase tracking-[0.18em] text-white">
						<?php echo esc_html( strtoupper( substr( $mauswp_site_name ?: 'EP', 0, 2 ) ) ); ?>
					</span>
					<span class="flex flex-col">
						<span class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-700"><?php esc_html_e( 'Ingeniería de arrastre', 'mauswp' ); ?></span>
						<span class="text-lg font-semibold tracking-tight"><?php echo esc_html( $mauswp_site_name ); ?></span>
					</span>
				<?php endif; ?>
			</a>
		</div>

		<div class="mauswp-primary-nav hidden lg:block">
			<nav aria-label="<?php esc_attr_e( 'Menú principal', 'mauswp' ); ?>">
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'flex items-center gap-6 text-base font-medium text-slate-700',
						'fallback_cb'    => 'mauswp_fallback_menu',
						'depth'          => 1,
					]
				);
				?>
			</nav>

		</div>

		<div class="flex items-center gap-3">
			<div class="header-search" data-header-search>
				<button class="header-search__trigger inline-flex h-11 w-11 items-center justify-center border border-slate-200 text-slate-700 transition hover:border-slate-300 hover:text-slate-900" type="button" data-header-search-trigger aria-expanded="false" aria-controls="header-search-panel" aria-label="<?php esc_attr_e( 'Abrir buscador', 'mauswp' ); ?>">
					<svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
						<circle cx="11" cy="11" r="8" />
						<path d="m21 21-4.3-4.3" />
					</svg>
				</button>
				<div class="header-search__panel" id="header-search-panel" data-header-search-panel hidden>
					<div class="header-search__inner">
						<div class="header-search__input-wrap">
							<svg class="header-search__icon" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
								<circle cx="11" cy="11" r="8" />
								<path d="m21 21-4.3-4.3" />
							</svg>
							<input
								class="header-search__input"
								type="search"
								data-header-search-input
								placeholder="<?php echo esc_attr( $mauswp_search_placeholder ); ?>"
								autocomplete="off"
								aria-label="<?php echo esc_attr( $mauswp_search_placeholder ); ?>"
								aria-controls="header-search-results"
								aria-autocomplete="list"
							>
							<button class="header-search__close" type="button" data-header-search-close aria-label="<?php esc_attr_e( 'Cerrar buscador', 'mauswp' ); ?>">
								<svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
									<path d="M18 6 6 18" />
									<path d="m6 6 12 12" />
								</svg>
							</button>
						</div>
					<div class="header-search__results" id="header-search-results" data-header-search-results role="listbox" aria-label="<?php echo esc_attr( $mauswp_search_title ); ?>">
						<div class="header-search__suggestions" data-header-search-suggestions hidden>
							<p class="header-search__suggestions-heading"><?php echo esc_html( $mauswp_search_suggestions_heading ); ?></p>
							<div class="header-search__list" data-header-search-list></div>
						</div>
						<div class="header-search__list" data-header-search-list></div>
						<div class="header-search__empty" data-header-search-empty hidden>
							<p class="header-search__empty-text"><?php echo esc_html( $mauswp_search_no_results ); ?></p>
						</div>
					</div>
					</div>
				</div>
			</div>
			<a class="btn-secondary hidden sm:inline-flex" href="<?php echo esc_url( $mauswp_catalog_url ); ?>" data-catalog-mega-trigger aria-expanded="false" aria-controls="desktop-catalog-mega-menu">
				<?php echo esc_html( $mauswp_catalog_button_label ); ?>
			</a>
			<button class="inline-flex h-11 w-11 items-center justify-center border border-slate-200 text-slate-700 lg:hidden" type="button" data-mobile-menu-toggle aria-expanded="false" aria-controls="mobile-primary-menu" aria-label="<?php esc_attr_e( 'Abrir menú', 'mauswp' ); ?>">
				<svg class="h-5 w-5" viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
					<path d="M4 7h16" />
					<path d="M4 12h16" />
					<path d="M4 17h16" />
				</svg>
			</button>
		</div>

		<?php if ( ! empty( $mauswp_catalog_categories ) ) : ?>
			<div class="catalog-mega-menu" id="desktop-catalog-mega-menu" data-catalog-mega-panel>
				<div class="catalog-mega-menu__inner">
					<div class="catalog-mega-menu__content">
						<div class="catalog-mega-menu__intro">
							<p class="catalog-mega-menu__eyebrow"><?php echo esc_html( $mauswp_catalog_mega_eyebrow ); ?></p>
							<p class="catalog-mega-menu__title"><?php echo esc_html( $mauswp_catalog_mega_title ); ?></p>
						</div>

						<div class="catalog-mega-menu__grid">
							<?php foreach ( $mauswp_catalog_categories as $mauswp_catalog_category ) : ?>
								<div class="catalog-mega-menu__group">
									<a class="catalog-mega-menu__group-title" href="<?php echo esc_url( (string) $mauswp_catalog_category['url'] ); ?>">
										<?php echo esc_html( (string) $mauswp_catalog_category['name'] ); ?>
									</a>

									<?php if ( ! empty( $mauswp_catalog_category['children'] ) && is_array( $mauswp_catalog_category['children'] ) ) : ?>
										<ul class="catalog-mega-menu__links">
											<?php foreach ( $mauswp_catalog_category['children'] as $mauswp_catalog_child ) : ?>
												<?php if ( ! empty( $mauswp_catalog_child['url'] ) ) : ?>
													<li>
														<a href="<?php echo esc_url( (string) $mauswp_catalog_child['url'] ); ?>">
															<?php echo esc_html( (string) $mauswp_catalog_child['name'] ); ?>
														</a>
													</li>
												<?php endif; ?>
											<?php endforeach; ?>
										</ul>
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					</div>

					<a class="catalog-mega-menu__media" href="<?php echo esc_url( $mauswp_catalog_url ); ?>">
						<?php if ( '' !== $mauswp_catalog_mega_image_url ) : ?>
							<img class="catalog-mega-menu__image" src="<?php echo esc_url( $mauswp_catalog_mega_image_url ); ?>" alt="<?php echo esc_attr( $mauswp_catalog_mega_image_alt ); ?>">
						<?php else : ?>
							<div class="catalog-mega-menu__placeholder" aria-hidden="true"></div>
						<?php endif; ?>
						<div class="catalog-mega-menu__media-copy">
							<span class="catalog-mega-menu__media-label"><?php echo esc_html( $mauswp_catalog_mega_cta_label ); ?></span>
							<span class="catalog-mega-menu__media-arrow" aria-hidden="true">&rarr;</span>
						</div>
					</a>
				</div>
			</div>
		<?php endif; ?>
	</div>

	<div class="border-t border-slate-200 lg:hidden" id="mobile-primary-menu" data-mobile-menu-panel>
		<div class="container flex h-full flex-col items-start gap-4 py-6">
			<nav class="w-full" aria-label="<?php esc_attr_e( 'Menú principal móvil', 'mauswp' ); ?>">
				<?php
				wp_nav_menu(
					[
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'flex w-full flex-col gap-2 text-2xl font-medium text-slate-900',
						'fallback_cb'    => 'mauswp_fallback_menu',
						'depth'          => 1,
					]
				);
				?>
			</nav>

			<?php if ( ! empty( $mauswp_catalog_categories ) ) : ?>
				<div class="mobile-catalog-menu w-full border-t border-slate-200 pt-4" data-mobile-catalog-menu>
					<button
						class="mobile-catalog-menu__trigger inline-flex w-full items-center justify-between py-3 text-2xl font-medium text-slate-900"
						type="button"
						data-mobile-catalog-trigger
						aria-expanded="false"
						aria-controls="mobile-catalog-panel"
					>
						<?php echo esc_html( $mauswp_catalog_button_label ); ?>
						<svg class="mobile-catalog-menu__icon h-5 w-5 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="m6 9 6 6 6-6" />
						</svg>
					</button>
					<div class="mobile-catalog-menu__panel" id="mobile-catalog-panel" data-mobile-catalog-panel hidden>
						<?php foreach ( $mauswp_catalog_categories as $mauswp_catalog_category ) : ?>
							<div class="mobile-catalog-menu__group">
								<a class="mobile-catalog-menu__group-title" href="<?php echo esc_url( (string) $mauswp_catalog_category['url'] ); ?>">
									<?php echo esc_html( (string) $mauswp_catalog_category['name'] ); ?>
								</a>
								<?php if ( ! empty( $mauswp_catalog_category['children'] ) && is_array( $mauswp_catalog_category['children'] ) ) : ?>
									<ul class="mobile-catalog-menu__links">
										<?php foreach ( $mauswp_catalog_category['children'] as $mauswp_catalog_child ) : ?>
											<?php if ( ! empty( $mauswp_catalog_child['url'] ) ) : ?>
												<li>
													<a class="mobile-catalog-menu__link" href="<?php echo esc_url( (string) $mauswp_catalog_child['url'] ); ?>">
														<?php echo esc_html( (string) $mauswp_catalog_child['name'] ); ?>
													</a>
												</li>
											<?php endif; ?>
										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</header>
