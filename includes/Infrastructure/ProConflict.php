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
 * Detects when WooCommerce Delivery Dates Pro is active so Lite can avoid
 * duplicate storefront estimates.
 */
class ProConflict {

	/**
	 * Whether WooCommerce Delivery Dates Pro is active.
	 *
	 * Pro defines WCDD_PRO_VERSION in its bootstrap file. Older builds may
	 * define WPRUBY_DP_PRO_VERSION.
	 *
	 * @return bool
	 */
	public static function is_pro_active(): bool {
		return defined( 'WCDD_PRO_VERSION' ) || defined( 'WPRUBY_DP_PRO_VERSION' );
	}
}
