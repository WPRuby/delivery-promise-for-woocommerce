<?php
/**
 * Shared PHPUnit helpers.
 *
 * @package WPRuby\DeliveryPromise\Tests
 */

namespace WPRuby\DeliveryPromise\Tests;

use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase as PhpUnitTestCase;
use WPRuby\DeliveryPromise\Domain\Calendar;
use WPRuby\DeliveryPromise\Domain\LiteDeliveryCalculator;
use WPRuby\DeliveryPromise\Domain\MessageFormatter;
use WPRuby\DeliveryPromise\Infrastructure\Settings;

abstract class TestCase extends PhpUnitTestCase {

	protected function setUp(): void {
		parent::setUp();

		$GLOBALS['wpruby_dp_test_options'] = array(
			'date_format'     => 'F j, Y',
			'time_format'     => 'g:i a',
			'timezone_string' => 'UTC',
		);
	}

	/**
	 * Store plugin settings for the next service instance.
	 *
	 * @param array<string,mixed> $settings Settings overrides.
	 */
	protected function set_plugin_settings( array $settings ): void {
		$GLOBALS['wpruby_dp_test_options'][ Settings::OPTION_KEY ] = $settings;
	}

	protected function settings( array $overrides = array() ): Settings {
		$this->set_plugin_settings( $overrides );

		return new Settings();
	}

	/**
	 * @param array<string,mixed> $settings Settings overrides.
	 */
	protected function calculator( array $settings = array(), string $now = '2026-06-29 10:00:00' ): LiteDeliveryCalculator {
		$settings_service = $this->settings( $settings );
		$calendar         = new FixedCalendar( $settings_service, $now );

		return new LiteDeliveryCalculator( $settings_service, $calendar );
	}

	protected function date( string $date ): DateTimeImmutable {
		return new DateTimeImmutable( $date, new DateTimeZone( 'UTC' ) );
	}
}

class FixedCalendar extends Calendar {

	/**
	 * @var DateTimeImmutable
	 */
	private $fixed_now;

	public function __construct( Settings $settings, string $fixed_now ) {
		parent::__construct( $settings );

		$this->fixed_now = new DateTimeImmutable( $fixed_now, new DateTimeZone( 'UTC' ) );
	}

	public function timezone(): DateTimeZone {
		return new DateTimeZone( 'UTC' );
	}

	public function now(): DateTimeImmutable {
		return $this->fixed_now;
	}
}
