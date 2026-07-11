<?php
/**
 * Working-day calendar service.
 *
 * @package WPRuby\DeliveryPromise
 */

namespace WPRuby\DeliveryPromise\Domain;

use DateTimeImmutable;
use DateTimeZone;
use WPRuby\DeliveryPromise\Infrastructure\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Calendar
 *
 * Handles store-timezone aware date math: working days, holiday skipping and cutoff evaluation.
 * Working days are ISO-8601 day numbers (1 = Monday ... 7 = Sunday).
 */
class Calendar {

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
	 * The store timezone.
	 *
	 * @return DateTimeZone
	 */
	public function timezone(): DateTimeZone {
		return function_exists( 'wp_timezone' ) ? wp_timezone() : new DateTimeZone( 'UTC' );
	}

	/**
	 * The current date/time in the store timezone.
	 *
	 * @return DateTimeImmutable
	 */
	public function now(): DateTimeImmutable {
		return new DateTimeImmutable( 'now', $this->timezone() );
	}

	/**
	 * Whether the given date is a working day (in working days and not a holiday).
	 *
	 * @param DateTimeImmutable $date         Date to test.
	 * @param int[]             $working_days ISO weekday numbers.
	 * @param string[]          $holidays     Holiday dates (Y-m-d).
	 *
	 * @return bool
	 */
	public function is_working_day( DateTimeImmutable $date, array $working_days, array $holidays ): bool {
		$iso = (int) $date->format( 'N' );

		if ( ! in_array( $iso, array_map( 'intval', $working_days ), true ) ) {
			return false;
		}

		return ! in_array( $date->format( 'Y-m-d' ), $holidays, true );
	}

	/**
	 * Move forward to the next working day (always advances at least one day).
	 *
	 * @param DateTimeImmutable $date         Starting date.
	 * @param int[]             $working_days ISO weekday numbers.
	 * @param string[]          $holidays     Holiday dates (Y-m-d).
	 *
	 * @return DateTimeImmutable
	 */
	public function next_working_day( DateTimeImmutable $date, array $working_days, array $holidays ): DateTimeImmutable {
		$guard = 0;
		do {
			$date = $date->modify( '+1 day' );
			++$guard;
		} while ( ! $this->is_working_day( $date, $working_days, $holidays ) && $guard < 3660 );

		return $date;
	}

	/**
	 * Ensure a date lands on a working day, moving forward if necessary.
	 *
	 * @param DateTimeImmutable $date         Date.
	 * @param int[]             $working_days ISO weekday numbers.
	 * @param string[]          $holidays     Holiday dates (Y-m-d).
	 *
	 * @return DateTimeImmutable
	 */
	public function ensure_working_day( DateTimeImmutable $date, array $working_days, array $holidays ): DateTimeImmutable {
		if ( $this->is_working_day( $date, $working_days, $holidays ) ) {
			return $date;
		}

		return $this->next_working_day( $date, $working_days, $holidays );
	}

	/**
	 * Add a number of working days to a date, skipping weekends/holidays.
	 *
	 * Zero working days returns the same date snapped forward to a working day.
	 *
	 * @param DateTimeImmutable $date         Starting date (assumed working day).
	 * @param int               $days         Number of working days to add.
	 * @param int[]             $working_days ISO weekday numbers.
	 * @param string[]          $holidays     Holiday dates (Y-m-d).
	 *
	 * @return DateTimeImmutable
	 */
	public function add_working_days( DateTimeImmutable $date, int $days, array $working_days, array $holidays ): DateTimeImmutable {
		$date = $this->ensure_working_day( $date, $working_days, $holidays );

		$remaining = max( 0, $days );
		while ( $remaining > 0 ) {
			$date = $this->next_working_day( $date, $working_days, $holidays );
			--$remaining;
		}

		return $date;
	}

	/**
	 * Determine the base dispatch date given the current time and cutoff.
	 *
	 * If today is a working day and we are before the cutoff, processing starts today.
	 * Otherwise processing starts on the next working day.
	 *
	 * @param DateTimeImmutable $now          Current date/time (store tz).
	 * @param string            $cutoff_time  Cutoff in HH:MM (empty = no cutoff restriction).
	 * @param int[]             $working_days ISO weekday numbers.
	 * @param string[]          $holidays     Holiday dates (Y-m-d).
	 *
	 * @return DateTimeImmutable Date at midnight in store timezone.
	 */
	public function dispatch_base_date( DateTimeImmutable $now, string $cutoff_time, array $working_days, array $holidays ): DateTimeImmutable {
		$today = $now->setTime( 0, 0, 0 );

		if ( ! $this->is_working_day( $today, $working_days, $holidays ) ) {
			return $this->next_working_day( $today, $working_days, $holidays );
		}

		if ( $this->is_after_cutoff( $now, $cutoff_time ) ) {
			return $this->next_working_day( $today, $working_days, $holidays );
		}

		return $today;
	}

	/**
	 * Whether the current time is at or past the cutoff time.
	 *
	 * @param DateTimeImmutable $now         Current date/time.
	 * @param string            $cutoff_time Cutoff in HH:MM (empty = never past cutoff).
	 *
	 * @return bool
	 */
	public function is_after_cutoff( DateTimeImmutable $now, string $cutoff_time ): bool {
		if ( '' === $cutoff_time || ! preg_match( '/^(\d{1,2}):(\d{2})$/', $cutoff_time, $m ) ) {
			return false;
		}

		$cutoff_minutes = ( (int) $m[1] * 60 ) + (int) $m[2];
		$now_minutes    = ( (int) $now->format( 'G' ) * 60 ) + (int) $now->format( 'i' );

		return $now_minutes >= $cutoff_minutes;
	}
}
