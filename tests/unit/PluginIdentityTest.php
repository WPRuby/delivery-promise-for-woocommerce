<?php
/**
 * Plugin identity tests.
 *
 * @package WPRuby\DeliveryPromise\Tests\Unit
 */

namespace WPRuby\DeliveryPromise\Tests\Unit;

use WPRuby\DeliveryPromise\Infrastructure\Settings;
use WPRuby\DeliveryPromise\Tests\TestCase;

class PluginIdentityTest extends TestCase {

	public function test_lite_constants_are_defined(): void {
		$this->assertSame( '1.0.0', DELIVERY_PROMISE_VERSION );
		$this->assertSame( 'delivery-promise-for-woocommerce', DELIVERY_PROMISE_TEXT_DOMAIN );
		$this->assertStringContainsString( 'delivery-promise-for-woocommerce.php', DELIVERY_PROMISE_PLUGIN_FILE );
	}

	public function test_pro_constants_are_not_defined_in_lite(): void {
		$this->assertFalse( defined( 'WPRUBY_DP_STORE_URL' ) );
		$this->assertFalse( defined( 'WPRUBY_DP_ITEM_ID' ) );
	}

	public function test_lite_settings_use_separate_option_key(): void {
		$this->assertSame( 'delivery_promise_lite_settings', Settings::OPTION_KEY );
	}
}
