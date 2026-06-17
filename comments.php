<?php
/**
 * Comments template.
 *
 * @package MausWP
 */

declare(strict_types=1);

if ( post_password_required() ) {
	return;
}

$comment_count = get_comments_number();
?>

<section id="comments" class="single-post__comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="single-post__comments-title">
			<?php
			printf(
				/* translators: 1: comment count number */
				esc_html( _nx( '%1$s comentario', '%1$s comentarios', $comment_count, 'comments title', 'mauswp' ) ),
				number_format_i18n( $comment_count )
			);
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 ) : ?>
			<nav class="single-post__comments-nav" aria-label="<?php esc_attr_e( 'Navegación de comentarios', 'mauswp' ); ?>">
				<?php paginate_comments_links(); ?>
			</nav>
		<?php endif; ?>

		<ol class="single-post__comments-list">
			<?php
			wp_list_comments(
				[
					'callback'     => 'mauswp_comment_callback',
					'short_ping'   => true,
					'avatar_size'  => 48,
					'style'        => 'ol',
				]
			);
			?>
		</ol>

		<?php if ( get_comment_pages_count() > 1 ) : ?>
			<nav class="single-post__comments-nav" aria-label="<?php esc_attr_e( 'Navegación de comentarios', 'mauswp' ); ?>">
				<?php paginate_comments_links(); ?>
			</nav>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
		<p class="single-post__comments-closed"><?php esc_html_e( 'Los comentarios están cerrados.', 'mauswp' ); ?></p>
	<?php endif; ?>

	<?php
	comment_form(
		[
			'class_form'           => 'single-post__comment-form',
			'class_container'      => 'single-post__comment-respond',
			'class_submit'         => 'single-post__comment-submit',
			'title_reply'          => __( 'Dejar un comentario', 'mauswp' ),
			'title_reply_to'       => __( 'Responder a %s', 'mauswp' ),
			'cancel_reply_link'    => __( 'Cancelar respuesta', 'mauswp' ),
			'label_submit'         => __( 'Publicar comentario', 'mauswp' ),
			'comment_field'        => '<p class="single-post__comment-field"><label for="comment">' . esc_html__( 'Comentario', 'mauswp' ) . '</label><textarea id="comment" name="comment" cols="45" rows="6" required="required"></textarea></p>',
			'must_log_in'          => '<p class="single-post__comment-must-log-in">' . sprintf( wp_kses_post( __( 'Debes <a href="%s">iniciar sesión</a> para dejar un comentario.', 'mauswp' ) ), esc_url( wp_login_url( get_permalink() ) ) ) . '</p>',
			'logged_in_as'         => '<p class="single-post__comment-logged-in-as">' . sprintf( wp_kses_post( __( 'Conectado como %1$s. <a href="%2$s">Cerrar sesión</a>?', 'mauswp' ) ), '<strong>' . esc_html( wp_get_current_user()->display_name ) . '</strong>', esc_url( wp_logout_url( get_permalink() ) ) ) . '</p>',
			'fields'               => [
				'author' => '<p class="single-post__comment-field"><label for="author">' . esc_html__( 'Nombre', 'mauswp' ) . ( get_option( 'require_name_email' ) ? ' <span class="required">*</span>' : '' ) . '</label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ?? '' ) . '" size="30"' . ( get_option( 'require_name_email' ) ? ' required="required"' : '' ) . ' /></p>',
				'email'  => '<p class="single-post__comment-field"><label for="email">' . esc_html__( 'Email', 'mauswp' ) . ( get_option( 'require_name_email' ) ? ' <span class="required">*</span>' : '' ) . '</label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ?? '' ) . '" size="30"' . ( get_option( 'require_name_email' ) ? ' required="required"' : '' ) . ' /></p>',
			],
		]
	);
	?>
</section>

<?php
/**
 * Custom comment callback.
 *
 * @param WP_Comment $comment Comment object.
 * @param array<string, mixed> $args  Arguments.
 * @param int        $depth   Depth.
 */
function mauswp_comment_callback( WP_Comment $comment, array $args, int $depth ): void {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo esc_attr( $tag ); ?> <?php comment_class( 'single-post__comment-item', $comment ); ?> id="comment-<?php echo esc_attr( $comment->comment_ID ); ?>">
		<article class="single-post__comment-body">
			<header class="single-post__comment-header">
				<?php if ( 0 !== $args['avatar_size'] ) : ?>
					<div class="single-post__comment-avatar">
						<?php echo get_avatar( $comment, $args['avatar_size'], '', '', [ 'class' => 'single-post__comment-avatar-img' ] ); ?>
					</div>
				<?php endif; ?>
				<div class="single-post__comment-meta">
					<?php
					printf(
						'<cite class="single-post__comment-author">%s</cite>',
						get_comment_author_link( $comment )
					);
					?>
					<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>" class="single-post__comment-date">
						<time datetime="<?php echo esc_attr( get_comment_time( DATE_W3C, true, $comment ) ); ?>">
							<?php echo esc_html( get_comment_date( '', $comment ) ); ?>
						</time>
					</a>
				</div>
			</header>

			<div class="single-post__comment-content">
				<?php comment_text(); ?>
			</div>

			<?php if ( '0' === $comment->comment_approved ) : ?>
				<p class="single-post__comment-awaiting"><?php esc_html_e( 'Tu comentario está pendiente de moderación.', 'mauswp' ); ?></p>
			<?php endif; ?>

			<?php
			comment_reply_link(
				array_merge(
					$args,
					[
						'add_below' => 'comment',
						'depth'     => $depth,
						'max_depth' => $args['max_depth'],
						'reply_text' => __( 'Responder', 'mauswp' ),
					]
				),
				$comment
			);
			?>
		</article>
	<?php
}
