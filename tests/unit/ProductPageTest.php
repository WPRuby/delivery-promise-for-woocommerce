<?php
/**
 * Product page settings tests.
 *
 * @package WPRuby\DeliveryPromise\Tests\Unit
 */

namespace WPRuby\DeliveryPromise\Tests\Unit;

use WPRuby\DeliveryPromise\Infrastructure\Settings;
use WPRuby\DeliveryPromise\Tests\TestCase;

class ProductPageTest extends TestCase {

	public function test_placement_priority_for_after_price(): void {
		$settings = $this->settings(
			array(
				'product_placement' => Settings::PLACEMENT_AFTER_PRICE,
			)
		);

		$this->assertSame( Settings::PLACEMENT_AFTER_PRICE, $settings->product_placement() );
		$this->assertSame( 11, $settings->placement_priorities()[ Settings::PLACEMENT_AFTER_PRICE ] );
	}

	public function test_in_stock_only_defaults_to_enabled(): void {
		$settings = $this->settings();

		$this->assertTrue( $settings->in_stock_only() );
	}

	public function test_display_can_be_disabled(): void {
		$settings = $this->settings(
			array(
				'enabled'         => 'yes',
				'display_product' => 'no',
			)
		);

		$this->assertFalse( $settings->is_display_enabled( 'product' ) );
	}
}
