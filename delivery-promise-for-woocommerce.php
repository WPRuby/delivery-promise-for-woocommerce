<?php
/**
 * The plugin bootstrap file.
 *
 * @wordpress-plugin
 * Plugin Name:       Delivery Promise for WooCommerce
 * Plugin URI:        https://wordpress.org/plugins/delivery-promise-for-woocommerce/
 * Description:       Show simple delivery and dispatch estimates on WooCommerce product pages.
 * Version:           1.0.0
 * Requires PHP:      7.4
 * Requires at least: 5.6
 * Tested up to:      6.8
 * WC requires at least: 6.0
 * WC tested up to:   9.9
 * Author:            WPRuby
 * Author URI:        https://wpruby.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       delivery-promise-for-woocommerce
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

define( 'DELIVERY_PROMISE_VERSION', '1.0.0' );
define( 'DELIVERY_PROMISE_PLUGIN_FILE', __FILE__ );
define( 'DELIVERY_PROMISE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DELIVERY_PROMISE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'DELIVERY_PROMISE_TEXT_DOMAIN', 'delivery-promise-for-woocommerce' );
define( 'DELIVERY_PROMISE_BASENAME', plugin_basename( __FILE__ ) );

require_once DELIVERY_PROMISE_PLUGIN_DIR . 'includes/autoload.php';

/**
 * Declare compatibility with WooCommerce HPOS.
 */
add_action(
	'before_woocommerce_init',
	static function () {
		if ( class_exists( FeaturesUtil::class ) ) {
			FeaturesUtil::declare_compatibility( 'custom_order_tables', DELIVERY_PROMISE_PLUGIN_FILE, true );
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
