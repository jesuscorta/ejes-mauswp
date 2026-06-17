<?php
/**
 * Page template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$is_shop_flow = function_exists( 'is_cart' ) && ( is_cart() || is_checkout() );
$page_kicker  = __( 'Página', 'mauswp' );
$main_class   = 'bg-site py-16 lg:py-20';
$article_class = 'mx-auto max-w-4xl border border-slate-200 bg-white p-8 shadow-sm sm:p-10 lg:p-12';

if ( $is_shop_flow ) {
	$page_kicker   = is_cart() ? __( 'Carrito', 'mauswp' ) : __( 'Finalizar compra', 'mauswp' );
	$main_class    = 'shop-flow bg-site py-12 lg:py-16';
	$article_class = 'shop-flow__card mx-auto w-full';
}

get_header();
?>
<main id="primary" class="<?php echo esc_attr( $main_class ); ?>">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article <?php post_class( $article_class ); ?>>
				<header class="<?php echo esc_attr( $is_shop_flow ? 'shop-flow__header' : 'mb-8 space-y-4 border-b border-slate-200 pb-8' ); ?>">
					<p class="eyebrow"><?php echo esc_html( $page_kicker ); ?></p>
					<h1 class="text-4xl font-semibold tracking-tight text-slate-950 sm:text-5xl"><?php the_title(); ?></h1>
					<?php if ( $is_shop_flow ) : ?>
						<p class="shop-flow__lead">
							<?php echo esc_html( is_cart() ? __( 'Revisa tu selección, ajusta cantidades y confirma la configuración antes de pasar al pedido.', 'mauswp' ) : __( 'Completa tus datos y revisa el resumen final para cerrar el pedido con claridad.', 'mauswp' ) ); ?>
						</p>
					<?php endif; ?>
				</header>
				<div class="<?php echo esc_attr( $is_shop_flow ? 'shop-flow__content entry-content max-w-none' : 'entry-content max-w-none' ); ?>">
					<?php the_content(); ?>
				</div>
			</article>
			<?php
		endwhile;
		?>
	</div>
</main>
<?php
get_footer();
