<?php
/**
 * Input sanitization helpers.
 *
 * @package WPRuby\DeliveryPromise
 */

namespace WPRuby\DeliveryPromise\Infrastructure;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Sanitizer
 *
 * Centralised sanitization for Lite settings inputs.
 */
class Sanitizer {

	const MAX_HOLIDAYS = 5;

	/**
	 * Sanitize a non-negative integer (days).
	 *
	 * @param mixed $value Raw value.
	 *
	 * @return int
	 */
	public static function days( $value ): int {
		$int = is_numeric( $value ) ? (int) $value : 0;

		return max( 0, $int );
	}

	/**
	 * Sanitize an HH:MM time string. Returns empty string if invalid/blank.
	 *
	 * @param mixed $value Raw value.
	 *
	 * @return string
	 */
	public static function time( $value ): string {
		$value = is_string( $value ) ? trim( $value ) : '';

		if ( '' === $value ) {
			return '';
		}

		if ( preg_match( '/^([01]?\d|2[0-3]):([0-5]\d)$/', $value, $m ) ) {
			return sprintf( '%02d:%02d', (int) $m[1], (int) $m[2] );
		}

		return '';
	}

	/**
	 * Sanitize a single Y-m-d date string. Returns empty string if invalid.
	 *
	 * @param mixed $value Raw value.
	 *
	 * @return string
	 */
	public static function date( $value ): string {
		$value = is_string( $value ) ? trim( $value ) : '';

		if ( '' === $value ) {
			return '';
		}

		$parts = date_parse_from_format( 'Y-m-d', $value );
		if ( is_array( $parts ) && 0 === (int) $parts['error_count'] && checkdate( (int) $parts['month'], (int) $parts['day'], (int) $parts['year'] ) ) {
			return sprintf( '%04d-%02d-%02d', (int) $parts['year'], (int) $parts['month'], (int) $parts['day'] );
		}

		return '';
	}

	/**
	 * Sanitize a list of holiday entries (max 5).
	 *
	 * @param mixed $values Raw values.
	 *
	 * @return array<int,array<string,string>>
	 */
	public static function holidays( $values ): array {
		if ( ! is_array( $values ) ) {
			return array();
		}

		$by_date = array();

		foreach ( $values as $entry ) {
			if ( is_array( $entry ) ) {
				$date  = self::date( $entry['date'] ?? '' );
				$label = sanitize_text_field( (string) ( $entry['label'] ?? '' ) );
			} else {
				$date  = self::date( $entry );
				$label = '';
			}

			if ( '' === $date ) {
				continue;
			}

			if ( ! isset( $by_date[ $date ] ) || ( '' === $by_date[ $date ] && '' !== $label ) ) {
				$by_date[ $date ] = $label;
			}
		}

		ksort( $by_date );

		$out = array();
		foreach ( $by_date as $date => $label ) {
			$out[] = array(
				'date'  => $date,
				'label' => $label,
			);

			if ( count( $out ) >= self::MAX_HOLIDAYS ) {
				break;
			}
		}

		return $out;
	}

	/**
	 * Sanitize an array of ISO weekday numbers (1-7).
	 *
	 * @param mixed $values Raw values.
	 *
	 * @return int[]
	 */
	public static function working_days( $values ): array {
		if ( ! is_array( $values ) ) {
			return array();
		}

		$out = array();
		foreach ( $values as $value ) {
			$day = (int) $value;
			if ( $day >= 1 && $day <= 7 ) {
				$out[] = $day;
			}
		}

		sort( $out );

		return array_values( array_unique( $out ) );
	}

	/**
	 * Sanitize a message template, allowing limited safe HTML.
	 *
	 * @param mixed $value Raw value.
	 *
	 * @return string
	 */
	public static function message( $value ): string {
		$value = is_string( $value ) ? $value : '';

		return wp_kses_post( trim( $value ) );
	}

	/**
	 * Sanitize a Lite display style setting.
	 *
	 * @param mixed $value Raw value.
	 *
	 * @return string
	 */
	public static function display_style( $value ): string {
		$value   = is_string( $value ) ? sanitize_key( $value ) : '';
		$allowed = array( Settings::STYLE_PLAIN, Settings::STYLE_HIGHLIGHTED );

		return in_array( $value, $allowed, true ) ? $value : Settings::STYLE_HIGHLIGHTED;
	}

	/**
	 * Sanitize product page placement.
	 *
	 * @param mixed $value Raw value.
	 *
	 * @return string
	 */
	public static function product_placement( $value ): string {
		$value   = is_string( $value ) ? sanitize_key( $value ) : '';
		$allowed = array(
			Settings::PLACEMENT_AFTER_PRICE,
			Settings::PLACEMENT_BEFORE_ADD_TO_CART,
			Settings::PLACEMENT_AFTER_ADD_TO_CART,
		);

		return in_array( $value, $allowed, true ) ? $value : Settings::PLACEMENT_AFTER_ADD_TO_CART;
	}

	/**
	 * Sanitize a yes/no checkbox value.
	 *
	 * @param mixed $value Raw value.
	 *
	 * @return string 'yes' or 'no'.
	 */
	public static function checkbox( $value ): string {
		return ( 'yes' === $value || '1' === (string) $value || 1 === $value || true === $value ) ? 'yes' : 'no';
	}
}
