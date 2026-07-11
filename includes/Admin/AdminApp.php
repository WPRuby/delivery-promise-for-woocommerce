<?php
/**
 * Admin app page (Vue mount point).
 *
 * @package WPRuby\DeliveryPromise
 */

namespace WPRuby\DeliveryPromise\Admin;

use WPRuby\DeliveryPromise\Infrastructure\ProConflict;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class AdminApp
 *
 * Registers the WooCommerce admin page and renders the Vue mount point.
 */
class AdminApp {

	const PAGE_SLUG  = 'delivery-promise-for-woocommerce';
	const CAPABILITY = 'manage_woocommerce';

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_filter( 'plugin_action_links_' . DELIVERY_PROMISE_BASENAME, array( $this, 'action_links' ) );
		add_action( 'admin_notices', array( $this, 'maybe_render_pro_conflict_notice' ) );
	}

	/**
	 * Add the submenu page under WooCommerce.
	 *
	 * @return void
	 */
	public function add_menu(): void {
		add_submenu_page(
			'woocommerce',
			__( 'Delivery Promise for WooCommerce', 'delivery-promise-for-woocommerce' ),
			__( 'Delivery Promise', 'delivery-promise-for-woocommerce' ),
			self::CAPABILITY,
			self::PAGE_SLUG,
			array( $this, 'render_page' )
		);
	}

	/**
	 * Add a settings link on the plugins screen.
	 *
	 * @param array $links Existing links.
	 *
	 * @return array
	 */
	public function action_links( array $links ): array {
		$url  = admin_url( 'admin.php?page=' . self::PAGE_SLUG );
		$link = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'delivery-promise-for-woocommerce' ) . '</a>';
		array_unshift( $links, $link );

		return $links;
	}

	/**
	 * Show a contextual notice when Pro is active.
	 *
	 * @return void
	 */
	public function maybe_render_pro_conflict_notice(): void {
		if ( ! ProConflict::is_pro_active() ) {
			return;
		}

		$page = isset( $_GET['page'] ) ? sanitize_key( wp_unslash( $_GET['page'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only page check.

		if ( self::PAGE_SLUG !== $page ) {
			return;
		}

		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		echo '<div class="notice notice-warning"><p>';
		echo esc_html__(
			'Delivery Promise Pro is active. Please deactivate Delivery Promise for WooCommerce Lite to avoid duplicate delivery estimates.',
			'delivery-promise-for-woocommerce'
		);
		echo '</p></div>';
	}

	/**
	 * Render the Vue mount point.
	 *
	 * @return void
	 */
	public function render_page(): void {
		if ( ! current_user_can( self::CAPABILITY ) ) {
			return;
		}

		echo '<div class="wrap delivery-promise-lite-wrap">';
		echo '<div id="delivery-promise-lite-admin" class="delivery-promise-lite-app">';
		echo '<p class="delivery-promise-lite-app__loading">' . esc_html__( 'Loading Delivery Promise…', 'delivery-promise-for-woocommerce' ) . '</p>';
		echo '</div>';
		echo '</div>';
	}
}
