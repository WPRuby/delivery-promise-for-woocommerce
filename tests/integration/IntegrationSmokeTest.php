<?php
/**
 * Optional WordPress/WooCommerce integration smoke tests.
 *
 * @package WPRuby\DeliveryPromise\Tests\Integration
 */

namespace WPRuby\DeliveryPromise\Tests\Integration;

use WPRuby\DeliveryPromise\Domain\LiteDeliveryCalculator;
use WPRuby\DeliveryPromise\Domain\MessageFormatter;
use WPRuby\DeliveryPromise\Infrastructure\Settings;
use WPRuby\DeliveryPromise\Rest\RestController;
use WPRuby\DeliveryPromise\Tests\TestCase;

class IntegrationSmokeTest extends TestCase {

	protected function setUp(): void {
		parent::setUp();

		if ( ! class_exists( '\WP_UnitTestCase' ) ) {
			$this->markTestSkipped( 'WordPress test suite is not loaded.' );
		}
	}

	public function test_plugin_classes_load_without_fatal_errors(): void {
		$this->assertTrue( class_exists( '\WPRuby\DeliveryPromise\Plugin' ) );
		$this->assertTrue( class_exists( Settings::class ) );
		$this->assertTrue( class_exists( LiteDeliveryCalculator::class ) );
	}

	public function test_settings_can_be_read_with_defaults(): void {
		$settings = new Settings();

		$this->assertSame( array( 1, 2, 3, 4, 5 ), $settings->working_days() );
		$this->assertSame( '14:00', $settings->cutoff_time() );
		$this->assertSame( Settings::OPTION_KEY, 'delivery_promise_lite_settings' );
	}

	public function test_rest_endpoints_require_capability(): void {
		wp_set_current_user( 0 );

		$settings   = new Settings();
		$calendar   = new \WPRuby\DeliveryPromise\Domain\Calendar( $settings );
		$formatter  = new MessageFormatter( $settings );
		$calculator = new LiteDeliveryCalculator( $settings, $calendar );
		$controller = new RestController( $settings, $calculator, $formatter );

		$this->assertInstanceOf( '\WP_Error', $controller->check_permission() );
	}
}
