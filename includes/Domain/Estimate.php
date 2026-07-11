<?php
/**
 * Delivery estimate value object.
 *
 * @package WPRuby\DeliveryPromise
 */

namespace WPRuby\DeliveryPromise\Domain;

use DateTimeImmutable;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Estimate
 *
 * Holds calculated dispatch and delivery date ranges.
 */
class Estimate {

	/**
	 * Dispatch range start.
	 *
	 * @var DateTimeImmutable
	 */
	private $dispatch_min;

	/**
	 * Dispatch range end.
	 *
	 * @var DateTimeImmutable
	 */
	private $dispatch_max;

	/**
	 * Delivery range start.
	 *
	 * @var DateTimeImmutable
	 */
	private $delivery_min;

	/**
	 * Delivery range end.
	 *
	 * @var DateTimeImmutable
	 */
	private $delivery_max;

	/**
	 * Cutoff time used (HH:MM).
	 *
	 * @var string
	 */
	private $cutoff_time;

	/**
	 * Processing days minimum.
	 *
	 * @var int
	 */
	private $processing_min;

	/**
	 * Processing days maximum.
	 *
	 * @var int
	 */
	private $processing_max;

	/**
	 * Transit days minimum.
	 *
	 * @var int
	 */
	private $transit_min;

	/**
	 * Transit days maximum.
	 *
	 * @var int
	 */
	private $transit_max;

	/**
	 * Constructor.
	 *
	 * @param DateTimeImmutable $dispatch_min   Dispatch range start.
	 * @param DateTimeImmutable $dispatch_max   Dispatch range end.
	 * @param DateTimeImmutable $delivery_min   Delivery range start.
	 * @param DateTimeImmutable $delivery_max   Delivery range end.
	 * @param string            $cutoff_time    Cutoff time (HH:MM).
	 * @param int               $processing_min Processing days minimum.
	 * @param int               $processing_max Processing days maximum.
	 * @param int               $transit_min    Transit days minimum.
	 * @param int               $transit_max    Transit days maximum.
	 */
	public function __construct(
		DateTimeImmutable $dispatch_min,
		DateTimeImmutable $dispatch_max,
		DateTimeImmutable $delivery_min,
		DateTimeImmutable $delivery_max,
		string $cutoff_time = '',
		int $processing_min = 0,
		int $processing_max = 0,
		int $transit_min = 0,
		int $transit_max = 0
	) {
		$this->dispatch_min   = $dispatch_min;
		$this->dispatch_max   = $dispatch_max;
		$this->delivery_min   = $delivery_min;
		$this->delivery_max   = $delivery_max;
		$this->cutoff_time    = $cutoff_time;
		$this->processing_min = $processing_min;
		$this->processing_max = $processing_max;
		$this->transit_min    = $transit_min;
		$this->transit_max    = $transit_max;
	}

	/**
	 * Dispatch range start date.
	 *
	 * @return DateTimeImmutable
	 */
	public function dispatch_min(): DateTimeImmutable {
		return $this->dispatch_min;
	}

	/**
	 * Dispatch range end date.
	 *
	 * @return DateTimeImmutable
	 */
	public function dispatch_max(): DateTimeImmutable {
		return $this->dispatch_max;
	}

	/**
	 * Delivery range start date.
	 *
	 * @return DateTimeImmutable
	 */
	public function delivery_min(): DateTimeImmutable {
		return $this->delivery_min;
	}

	/**
	 * Delivery range end date.
	 *
	 * @return DateTimeImmutable
	 */
	public function delivery_max(): DateTimeImmutable {
		return $this->delivery_max;
	}

	/**
	 * Cutoff time (HH:MM).
	 *
	 * @return string
	 */
	public function cutoff_time(): string {
		return $this->cutoff_time;
	}

	/**
	 * Processing days minimum.
	 *
	 * @return int
	 */
	public function processing_min(): int {
		return $this->processing_min;
	}

	/**
	 * Processing days maximum.
	 *
	 * @return int
	 */
	public function processing_max(): int {
		return $this->processing_max;
	}

	/**
	 * Transit days minimum.
	 *
	 * @return int
	 */
	public function transit_min(): int {
		return $this->transit_min;
	}

	/**
	 * Transit days maximum.
	 *
	 * @return int
	 */
	public function transit_max(): int {
		return $this->transit_max;
	}

	/**
	 * Whether dispatch is a single day (min equals max).
	 *
	 * @return bool
	 */
	public function dispatch_is_single(): bool {
		return $this->dispatch_min->format( 'Y-m-d' ) === $this->dispatch_max->format( 'Y-m-d' );
	}

	/**
	 * Whether delivery is a single day (min equals max).
	 *
	 * @return bool
	 */
	public function delivery_is_single(): bool {
		return $this->delivery_min->format( 'Y-m-d' ) === $this->delivery_max->format( 'Y-m-d' );
	}
}
