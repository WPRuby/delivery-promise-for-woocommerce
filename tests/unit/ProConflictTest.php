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

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public function test_pro_is_detected_when_wcdd_constant_is_defined(): void {
		define( 'WCDD_PRO_VERSION', '9.9.9' );

		$this->assertTrue( ProConflict::is_pro_active() );
	}
}
