<?php
/**
 * Lite delivery calculator.
 *
 * @package WPRuby\DeliveryPromise
 */

namespace WPRuby\DeliveryPromise\Domain;

use DateTimeImmutable;
use WPRuby\DeliveryPromise\Infrastructure\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class LiteDeliveryCalculator
 *
 * Calculates dispatch and delivery date ranges from global Lite settings only.
 */
class LiteDeliveryCalculator {

	/**
	 * Settings accessor.
	 *
	 * @var Settings
	 */
	private $settings;

	/**
	 * Calendar service.
	 *
	 * @var Calendar
	 */
	private $calendar;

	/**
	 * Constructor.
	 *
	 * @param Settings $settings Settings accessor.
	 * @param Calendar $calendar Calendar service.
	 */
	public function __construct( Settings $settings, Calendar $calendar ) {
		$this->settings = $settings;
		$this->calendar = $calendar;
	}

	/**
	 * Calculate a delivery estimate from global settings.
	 *
	 * @param DateTimeImmutable|null $now       Optional reference datetime (store timezone).
	 * @param array<string,mixed>    $overrides Optional setting overrides for previews.
	 *
	 * @return Estimate
	 */
	public function calculate( ?DateTimeImmutable $now = null, array $overrides = array() ): Estimate {
		$now          = $now ?: $this->calendar->now();
		$holidays     = $this->resolve_holidays( $overrides );
		$working_days = $this->resolve_working_days( $overrides );
		$cutoff       = isset( $overrides['cutoff_time'] )
			? (string) $overrides['cutoff_time']
			: $this->settings->cutoff_time();

		$processing_min = (int) ( $overrides['processing_min'] ?? $this->settings->get( 'processing_min', 1 ) );
		$processing_max = (int) ( $overrides['processing_max'] ?? $this->settings->get( 'processing_max', 1 ) );
		$transit_min    = (int) ( $overrides['transit_min'] ?? $this->settings->get( 'transit_min', 2 ) );
		$transit_max    = (int) ( $overrides['transit_max'] ?? $this->settings->get( 'transit_max', 4 ) );

		$base = $this->calendar->dispatch_base_date( $now, $cutoff, $working_days, $holidays );

		$dispatch_min = $this->calendar->add_working_days( $base, $processing_min, $working_days, $holidays );
		$dispatch_max = $this->calendar->add_working_days( $base, $processing_max, $working_days, $holidays );
		$delivery_min = $this->calendar->add_working_days( $dispatch_min, $transit_min, $working_days, $holidays );
		$delivery_max = $this->calendar->add_working_days( $dispatch_max, $transit_max, $working_days, $holidays );

		$estimate = new Estimate(
			$dispatch_min,
			$dispatch_max,
			$delivery_min,
			$delivery_max,
			$cutoff,
			$processing_min,
			$processing_max,
			$transit_min,
			$transit_max
		);

		/**
		 * Filter the calculated Lite delivery estimate.
		 *
		 * @param Estimate $estimate Calculated estimate.
		 * @param Settings $settings Settings accessor.
		 */
		return apply_filters( 'eddd_calculated_estimate', $estimate, $this->settings );
	}

	/**
	 * Resolve working days from overrides or stored settings.
	 *
	 * @param array<string,mixed> $overrides Setting overrides.
	 *
	 * @return int[]
	 */
	private function resolve_working_days( array $overrides ): array {
		if ( ! isset( $overrides['working_days'] ) ) {
			return $this->settings->working_days();
		}

		$days = is_array( $overrides['working_days'] ) ? array_map( 'intval', $overrides['working_days'] ) : array();

		return empty( $days ) ? array( 1, 2, 3, 4, 5 ) : $days;
	}

	/**
	 * Resolve holiday dates from overrides or stored settings.
	 *
	 * @param array<string,mixed> $overrides Setting overrides.
	 *
	 * @return string[]
	 */
	private function resolve_holidays( array $overrides ): array {
		if ( ! isset( $overrides['holidays'] ) ) {
			return $this->settings->holidays();
		}

		$out = array();
		foreach ( (array) $overrides['holidays'] as $holiday ) {
			if ( is_array( $holiday ) && ! empty( $holiday['date'] ) ) {
				$out[] = (string) $holiday['date'];
			} elseif ( is_string( $holiday ) && '' !== $holiday ) {
				$out[] = $holiday;
			}
		}

		return $out;
	}
}
