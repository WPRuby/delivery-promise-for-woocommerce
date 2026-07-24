<?php
/**
 * Reference data provider for the Vue admin app.
 *
 * @package WPRuby\DeliveryPromise
 */

namespace WPRuby\DeliveryPromise\Admin;

use WPRuby\DeliveryPromise\Infrastructure\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class AdminData
 *
 * Collects read-only reference data for the Lite admin app.
 */
class AdminData {

	/**
	 * Build the full reference data payload for the admin app.
	 *
	 * @return array<string,mixed>
	 */
	public static function all(): array {
		return array(
			'weekdays'     => self::weekdays(),
			'placeholders' => self::placeholders(),
			'placements'   => self::placements(),
			'styles'       => self::styles(),
			'wpDateFormat' => (string) get_option( 'date_format', 'F j, Y' ),
			'maxHolidays'  => 5,
		);
	}

	/**
	 * ISO weekday labels (1 = Monday ... 7 = Sunday).
	 *
	 * @return array<int,array<string,mixed>>
	 */
	public static function weekdays(): array {
		$labels = array(
			1 => __( 'Monday', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			2 => __( 'Tuesday', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			3 => __( 'Wednesday', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			4 => __( 'Thursday', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			5 => __( 'Friday', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			6 => __( 'Saturday', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			7 => __( 'Sunday', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
		);

		$out = array();
		foreach ( $labels as $value => $label ) {
			$out[] = array(
				'value' => $value,
				'label' => $label,
			);
		}

		return $out;
	}

	/**
	 * Message template placeholders with descriptions.
	 *
	 * @return array<int,array<string,string>>
	 */
	public static function placeholders(): array {
		return array(
			array(
				'token' => '{earliest_date}',
				'desc'  => __( 'Earliest delivery date.', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
			array(
				'token' => '{latest_date}',
				'desc'  => __( 'Latest delivery date.', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
			array(
				'token' => '{processing_days}',
				'desc'  => __( 'Processing days (e.g. 1 or 1–2).', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
			array(
				'token' => '{min_transit_days}',
				'desc'  => __( 'Minimum transit days.', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
			array(
				'token' => '{max_transit_days}',
				'desc'  => __( 'Maximum transit days.', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
			array(
				'token' => '{delivery_range}',
				'desc'  => __( 'Delivery date range.', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
			array(
				'token' => '{cutoff_time}',
				'desc'  => __( 'Same-day dispatch cutoff time.', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
		);
	}

	/**
	 * Product page placement options.
	 *
	 * @return array<int,array<string,string>>
	 */
	public static function placements(): array {
		return array(
			array(
				'value' => Settings::PLACEMENT_AFTER_PRICE,
				'label' => __( 'After price', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
			array(
				'value' => Settings::PLACEMENT_BEFORE_ADD_TO_CART,
				'label' => __( 'Before add to cart', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
			array(
				'value' => Settings::PLACEMENT_AFTER_ADD_TO_CART,
				'label' => __( 'After add to cart', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
		);
	}

	/**
	 * Display style options.
	 *
	 * @return array<int,array<string,string>>
	 */
	public static function styles(): array {
		return array(
			array(
				'value' => Settings::STYLE_PLAIN,
				'label' => __( 'Plain', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
			array(
				'value' => Settings::STYLE_HIGHLIGHTED,
				'label' => __( 'Highlighted', 'estimated-delivery-and-dispatch-dates-for-woocommerce' ),
			),
		);
	}
}
