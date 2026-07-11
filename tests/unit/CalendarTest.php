<?php
/**
 * Calendar service tests.
 *
 * @package WPRuby\DeliveryPromise\Tests\Unit
 */

namespace WPRuby\DeliveryPromise\Tests\Unit;

use WPRuby\DeliveryPromise\Domain\Calendar;
use WPRuby\DeliveryPromise\Infrastructure\Settings;
use WPRuby\DeliveryPromise\Tests\TestCase;

class CalendarTest extends TestCase {

	private function calendar(): Calendar {
		return new Calendar( new Settings() );
	}

	public function test_adds_working_days_across_weekend(): void {
		$result = $this->calendar()->add_working_days(
			$this->date( '2026-07-03' ),
			1,
			array( 1, 2, 3, 4, 5 ),
			array()
		);

		$this->assertSame( '2026-07-06', $result->format( 'Y-m-d' ) );
	}

	public function test_skips_configured_holiday(): void {
		$result = $this->calendar()->add_working_days(
			$this->date( '2026-07-02' ),
			1,
			array( 1, 2, 3, 4, 5 ),
			array( '2026-07-03' )
		);

		$this->assertSame( '2026-07-06', $result->format( 'Y-m-d' ) );
	}

	public function test_before_cutoff_processing_zero_can_dispatch_today(): void {
		$result = $this->calendar()->dispatch_base_date(
			$this->date( '2026-07-01 13:00:00' ),
			'14:00',
			array( 1, 2, 3, 4, 5 ),
			array()
		);

		$this->assertSame( '2026-07-01', $result->format( 'Y-m-d' ) );
	}

	public function test_after_cutoff_starts_next_working_day(): void {
		$result = $this->calendar()->dispatch_base_date(
			$this->date( '2026-07-03 14:00:00' ),
			'14:00',
			array( 1, 2, 3, 4, 5 ),
			array()
		);

		$this->assertSame( '2026-07-06', $result->format( 'Y-m-d' ) );
	}

	public function test_custom_working_days_are_respected(): void {
		$result = $this->calendar()->add_working_days(
			$this->date( '2026-06-30' ),
			1,
			array( 2, 4 ),
			array()
		);

		$this->assertSame( '2026-07-02', $result->format( 'Y-m-d' ) );
	}

	public function test_empty_working_days_falls_back_to_monday_friday_settings(): void {
		$settings = $this->settings( array( 'working_days' => array() ) );

		$this->assertSame( array( 1, 2, 3, 4, 5 ), $settings->working_days() );
	}
}
