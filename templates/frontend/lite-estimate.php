<?php
/**
 * Lite product page estimate template.
 *
 * @package WPRuby\DeliveryPromise
 *
 * @var string $message   Formatted message HTML.
 * @var string $style     Display style slug.
 * @var bool   $show_icon Whether to show the icon.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="delivery-promise-lite delivery-promise-lite--<?php echo esc_attr( $style ); ?>">
	<?php if ( $show_icon ) : ?>
		<span class="delivery-promise-lite__icon" aria-hidden="true">&#128666;</span>
	<?php endif; ?>
	<span class="delivery-promise-lite__message"><?php echo wp_kses_post( $message ); ?></span>
</div>
