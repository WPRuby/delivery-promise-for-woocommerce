<?php
/**
 * Settings sanitizer tests.
 *
 * @package WPRuby\DeliveryPromise\Tests\Unit
 */

namespace WPRuby\DeliveryPromise\Tests\Unit;

use WPRuby\DeliveryPromise\Infrastructure\Sanitizer;
use WPRuby\DeliveryPromise\Infrastructure\Settings;
use WPRuby\DeliveryPromise\Tests\TestCase;

class SettingsSanitizerTest extends TestCase {

	public function test_holidays_are_capped_at_five(): void {
		$holidays = Sanitizer::holidays(
			array(
				array( 'date' => '2026-12-24', 'label' => 'A' ),
				array( 'date' => '2026-12-25', 'label' => 'B' ),
				array( 'date' => '2026-12-26', 'label' => 'C' ),
				array( 'date' => '2026-12-27', 'label' => 'D' ),
				array( 'date' => '2026-12-28', 'label' => 'E' ),
				array( 'date' => '2026-12-29', 'label' => 'F' ),
			)
		);

		$this->assertCount( 5, $holidays );
		$this->assertSame( '2026-12-24', $holidays[0]['date'] );
		$this->assertSame( '2026-12-28', $holidays[4]['date'] );
	}

	public function test_product_placement_is_validated(): void {
		$this->assertSame(
			Settings::PLACEMENT_AFTER_PRICE,
			Sanitizer::product_placement( 'after_price' )
		);
		$this->assertSame(
			Settings::PLACEMENT_AFTER_ADD_TO_CART,
			Sanitizer::product_placement( 'invalid' )
		);
	}

	public function test_display_style_is_validated(): void {
		$this->assertSame( Settings::STYLE_PLAIN, Sanitizer::display_style( 'plain' ) );
		$this->assertSame( Settings::STYLE_HIGHLIGHTED, Sanitizer::display_style( 'invalid' ) );
	}
}
