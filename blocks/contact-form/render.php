<?php
/**
 * Contact form block render template.
 *
 * @package MausWP
 */

declare(strict_types=1);

$anchor        = ! empty( $block['anchor'] ) ? (string) $block['anchor'] : 'contacto';
$align_class   = ! empty( $block['align'] ) ? 'align' . sanitize_html_class( (string) $block['align'] ) : 'alignwide';
$block_classes = [ 'contact-form-block', $align_class ];
$contact_phone = '608.725.197';
$contact_email = 'administracion@sumagrogranada.com';
$facebook_url  = 'https://www.facebook.com/Granadina-Industrial-Agr%C3%ADcola-100453171807218';
$contact_title = __( 'Hablemos de tu proyecto', 'mauswp' );
$contact_intro = __( 'Cuéntanos qué necesitas y te responderemos en menos de 24h.', 'mauswp' );
$email_label   = __( 'Email', 'mauswp' );
$phone_label   = __( 'Teléfono', 'mauswp' );

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
					<h2 class="contact-form-block__title"><?php echo esc_html( $contact_title ); ?></h2>
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
