<?php
/**
 * Message formatter tests.
 *
 * @package WPRuby\DeliveryPromise\Tests\Unit
 */

namespace WPRuby\DeliveryPromise\Tests\Unit;

use WPRuby\DeliveryPromise\Domain\Estimate;
use WPRuby\DeliveryPromise\Domain\MessageFormatter;
use WPRuby\DeliveryPromise\Tests\TestCase;

class MessageFormatterTest extends TestCase {

	private function formatter(): MessageFormatter {
		$GLOBALS['wpruby_dp_test_options']['date_format'] = 'Y-m-d';

		return new MessageFormatter( $this->settings() );
	}

	private function estimate(): Estimate {
		return new Estimate(
			$this->date( '2030-01-02' ),
			$this->date( '2030-01-03' ),
			$this->date( '2030-01-06' ),
			$this->date( '2030-01-08' ),
			'14:00',
			1,
			2,
			2,
			4
		);
	}

	public function test_replaces_standard_placeholders(): void {
		$message = $this->formatter()->format(
			'Dispatch {dispatch_date} ({dispatch_range}); delivery {delivery_date} ({delivery_range}) before {cutoff_time}.',
			$this->estimate()
		);

		$this->assertStringContainsString( 'Dispatch 2030-01-02 (2030-01-02 – 2030-01-03)', $message );
		$this->assertStringContainsString( 'delivery 2030-01-06 (2030-01-06 – 2030-01-08)', $message );
		$this->assertStringContainsString( 'before 2:00 pm', $message );
	}

	public function test_replaces_lite_placeholders(): void {
		$message = $this->formatter()->format(
			'Order between {earliest_date} and {latest_date}. Processing {processing_days}. Transit {min_transit_days}-{max_transit_days}.',
			$this->estimate()
		);

		$this->assertStringContainsString( 'Order between 2030-01-06 and 2030-01-08', $message );
		$this->assertStringContainsString( 'Processing 1–2', $message );
		$this->assertStringContainsString( 'Transit 2-4', $message );
	}

	public function test_single_date_range_collapses_to_one_date(): void {
		$estimate = new Estimate(
			$this->date( '2030-01-02' ),
			$this->date( '2030-01-02' ),
			$this->date( '2030-01-06' ),
			$this->date( '2030-01-06' )
		);

		$this->assertSame( 'Delivery 2030-01-06', $this->formatter()->format( 'Delivery {delivery_range}', $estimate ) );
	}

	public function test_missing_placeholder_data_does_not_fatal(): void {
		$message = $this->formatter()->format( 'Unknown {not_a_placeholder}', $this->estimate() );

		$this->assertSame( 'Unknown {not_a_placeholder}', $message );
	}
}
