<?php
/**
 * The plugin bootstrap file.
 *
 * @wordpress-plugin
 * Plugin Name:       Estimated Delivery and Dispatch Dates for WooCommerce
 * Plugin URI:        https://wpruby.com/plugin/estimated-delivery-and-dispatch-dates-for-woocommerce/
 * Description:       Show simple WooCommerce delivery and dispatch estimates on product pages using processing days, transit days, and working days.
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Requires at least: 6.5
 * Tested up to:      7.0
 * WC requires at least: 8.0
 * WC tested up to:   10.9
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       estimated-delivery-and-dispatch-dates-for-woocommerce
 * Domain Path:       /languages
 *
 * @package WPRuby\DeliveryPromise
 */

namespace WPRuby\DeliveryPromise;

use Automattic\WooCommerce\Utilities\FeaturesUtil;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'EDDD_VERSION', '1.0.0' );
define( 'EDDD_PLUGIN_FILE', __FILE__ );
define( 'EDDD_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'EDDD_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'EDDD_TEXT_DOMAIN', 'estimated-delivery-and-dispatch-dates-for-woocommerce' );
define( 'EDDD_BASENAME', plugin_basename( __FILE__ ) );

require_once EDDD_PLUGIN_DIR . 'includes/autoload.php';

/**
 * Declare compatibility with WooCommerce HPOS.
 */
add_action(
	'before_woocommerce_init',
	static function () {
		if ( class_exists( FeaturesUtil::class ) ) {
			FeaturesUtil::declare_compatibility( 'custom_order_tables', EDDD_PLUGIN_FILE, true );
		}
	}
);

/**
 * Boot the plugin once all plugins are loaded so WooCommerce availability can be detected.
 */
add_action(
	'plugins_loaded',
	static function () {
		Plugin::get_instance();
	}
);
