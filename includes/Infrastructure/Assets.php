<?php
/**
 * Asset registration.
 *
 * @package WPRuby\DeliveryPromise
 */

namespace WPRuby\DeliveryPromise\Infrastructure;

use WPRuby\DeliveryPromise\Admin\AdminApp;
use WPRuby\DeliveryPromise\Admin\AdminData;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Assets
 *
 * Registers admin and frontend CSS.
 */
class Assets {

	/**
	 * Settings accessor.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Constructor.
	 *
	 * @param Settings $settings Settings accessor.
	 */
	public function __construct( Settings $settings ) {
		$this->settings = $settings;
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
	}

	/**
	 * Enqueue admin assets on plugin pages only.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 *
	 * @return void
	 */
	public function enqueue_admin( string $hook_suffix ): void {
		unset( $hook_suffix );

		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only page check.

		if ( AdminApp::PAGE_SLUG !== $page ) {
			return;
		}

		$dist_dir = DELIVERY_PROMISE_PLUGIN_DIR . 'assets/admin/dist/';
		$dist_url = DELIVERY_PROMISE_PLUGIN_URL . 'assets/admin/dist/';
		$js_file  = $dist_dir . 'app.js';
		$css_file = $dist_dir . 'app.css';

		if ( ! is_readable( $js_file ) ) {
			add_action(
				'admin_notices',
				static function () {
					echo '<div class="notice notice-error"><p>';
					echo esc_html__(
						'Delivery Promise: the admin app bundle is missing. Run "npm install && npm run build" inside the plugin folder.',
						'delivery-promise-for-woocommerce'
					);
					echo '</p></div>';
				}
			);

			return;
		}

		$version = (string) DELIVERY_PROMISE_VERSION . '.' . (string) filemtime( $js_file );

		if ( is_readable( $css_file ) ) {
			wp_enqueue_style(
				'delivery-promise-lite-admin',
				$dist_url . 'app.css',
				array(),
				$version
			);
		}

		wp_enqueue_script(
			'delivery-promise-lite-admin',
			$dist_url . 'app.js',
			array( 'wp-i18n' ),
			$version,
			true
		);

		wp_localize_script(
			'delivery-promise-lite-admin',
			'deliveryPromiseLite',
			array(
				'restUrl'   => esc_url_raw( rest_url( 'delivery-promise-for-woocommerce/v1/' ) ),
				'restNonce' => wp_create_nonce( 'wp_rest' ),
				'version'   => (string) DELIVERY_PROMISE_VERSION,
				'proActive' => ProConflict::is_pro_active(),
				'proUrl'    => esc_url_raw( 'https://wpruby.com/plugin/delivery-promise-for-woocommerce/' ),
				'data'      => AdminData::all(),
			)
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'delivery-promise-lite-admin', DELIVERY_PROMISE_TEXT_DOMAIN );
		}
	}

	/**
	 * Enqueue frontend CSS when product page output is enabled.
	 *
	 * @return void
	 */
	public function enqueue_frontend(): void {
		if ( ! $this->settings->is_enabled() || ! $this->settings->is_display_enabled( 'product' ) ) {
			return;
		}

		if ( ! function_exists( 'is_product' ) || ! is_product() ) {
			return;
		}

		wp_enqueue_style(
			'delivery-promise-lite',
			DELIVERY_PROMISE_PLUGIN_URL . 'assets/frontend/lite.css',
			array(),
			DELIVERY_PROMISE_VERSION
		);
	}
}
