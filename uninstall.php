<?php
/**
 * Uninstall handler.
 *
 * Intentionally does NOT delete merchant data (settings, rules or order meta).
 * Removing delivery promise history from existing orders would be destructive,
 * so V1 leaves all data in place on uninstall by design.
 *
 * If a future version needs an opt-in cleanup, gate it behind an explicit setting
 * and remove option: delivery_promise_lite_settings.
 *
 * @package WPRuby\DeliveryPromise
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// No-op by design: merchant data is preserved.
