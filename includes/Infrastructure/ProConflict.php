<?php
/**
 * Pro plugin conflict detection.
 *
 * @package WPRuby\DeliveryPromise
 */

namespace WPRuby\DeliveryPromise\Infrastructure;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class ProConflict
 *
 * Detects when Delivery Promise Pro is active so Lite can avoid duplicate output.
 */
class ProConflict {

	/**
	 * Whether Delivery Promise Pro is active.
	 *
	 * Pro defines the WPRUBY_DP_PRO_VERSION constant in its bootstrap file.
	 *
	 * @return bool
	 */
	public static function is_pro_active(): bool {
		return defined( 'WPRUBY_DP_PRO_VERSION' );
	}
}
