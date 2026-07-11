<?php
/**
 * Pro conflict detection tests.
 *
 * @package WPRuby\DeliveryPromise\Tests\Unit
 */

namespace WPRuby\DeliveryPromise\Tests\Unit;

use WPRuby\DeliveryPromise\Infrastructure\ProConflict;
use WPRuby\DeliveryPromise\Tests\TestCase;

class ProConflictTest extends TestCase {

	public function test_pro_is_not_active_by_default(): void {
		$this->assertFalse( ProConflict::is_pro_active() );
	}

	public function test_pro_is_detected_when_constant_is_defined(): void {
		if ( ! defined( 'WPRUBY_DP_PRO_VERSION' ) ) {
			define( 'WPRUBY_DP_PRO_VERSION', '9.9.9' );
		}

		$this->assertTrue( ProConflict::is_pro_active() );
	}
}
