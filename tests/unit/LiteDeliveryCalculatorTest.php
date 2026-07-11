<?php
/**
 * Lite delivery calculator tests.
 *
 * @package WPRuby\DeliveryPromise\Tests\Unit
 */

namespace WPRuby\DeliveryPromise\Tests\Unit;

use WPRuby\DeliveryPromise\Tests\TestCase;

class LiteDeliveryCalculatorTest extends TestCase {

	public function test_default_processing_and_transit_estimate(): void {
		$estimate = $this->calculator()->calculate();

		$this->assertSame( '2026-06-30', $estimate->dispatch_min()->format( 'Y-m-d' ) );
		$this->assertSame( '2026-06-30', $estimate->dispatch_max()->format( 'Y-m-d' ) );
		$this->assertSame( '2026-07-02', $estimate->delivery_min()->format( 'Y-m-d' ) );
		$this->assertSame( '2026-07-06', $estimate->delivery_max()->format( 'Y-m-d' ) );
	}

	public function test_after_cutoff_rolls_base_to_next_working_day(): void {
		$estimate = $this->calculator(
			array(
				'processing_min' => 0,
				'processing_max' => 0,
				'transit_min'    => 1,
				'transit_max'    => 1,
			),
			'2026-06-29 15:00:00'
		)->calculate();

		$this->assertSame( '2026-06-30', $estimate->dispatch_min()->format( 'Y-m-d' ) );
		$this->assertSame( '2026-07-01', $estimate->delivery_min()->format( 'Y-m-d' ) );
	}

	public function test_holiday_is_skipped_in_estimate(): void {
		$estimate = $this->calculator(
			array(
				'processing_min' => 0,
				'processing_max' => 0,
				'transit_min'    => 1,
				'transit_max'    => 1,
				'holidays'       => array(
					array(
						'date'  => '2026-06-30',
						'label' => 'Closed',
					),
				),
			)
		)->calculate();

		$this->assertSame( '2026-06-29', $estimate->dispatch_min()->format( 'Y-m-d' ) );
		$this->assertSame( '2026-07-01', $estimate->delivery_min()->format( 'Y-m-d' ) );
	}

	public function test_preview_overrides_are_respected(): void {
		$estimate = $this->calculator(
			array(
				'processing_min' => 1,
				'processing_max' => 1,
				'transit_min'    => 2,
				'transit_max'    => 4,
			)
		)->calculate(
			null,
			array(
				'processing_min' => 0,
				'processing_max' => 0,
				'transit_min'    => 0,
				'transit_max'    => 0,
			)
		);

		$this->assertSame( '2026-06-29', $estimate->dispatch_min()->format( 'Y-m-d' ) );
		$this->assertSame( '2026-06-29', $estimate->delivery_min()->format( 'Y-m-d' ) );
	}
}
