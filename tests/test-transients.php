<?php
/**
 * Tests for the transient functions.
 *
 * @package SteveGrunwell\WPCacheRemember
 */

/**
 * Transients test case.
 */
class TransientTest extends WP_UnitTestCase {

	function test_remembers_value() {
		$key      = 'some-cache-key-' . uniqid();
		$callback = function () {
			return uniqid();
		};

		$value = remember_transient( $key, $callback );

		$this->assertEquals(
			$value,
			remember_transient( $key, $callback ),
			'Expected the same value to be returned on subsequent requests.'
		);
		$this->assertEquals( $value, get_transient( $key ) );
	}

	function test_remember_pulls_from_cache() {
		$key   = 'some-cache-key-' . uniqid();
		$value = uniqid();

		set_transient( $key, $value );

		$this->assertEquals(
			$value,
			remember_transient( $key, '__return_false' ),
			'Expected the cache value to be returned.'
		);
	}

	function test_forget_deletes_cached_item() {
		$key = 'some-cache-key-' . uniqid();

		set_transient( $key, 'some value' );

		$this->assertEquals( 'some value', forget_transient( $key ), 'Expected to receive the cached value.' );
		$this->assertFalse( get_transient( $key ), 'Expected the cached value to be removed.' );
	}

	function test_forget_falls_back_to_default() {
		$key = 'some-cache-key-' . uniqid();

		$this->assertEquals( 'some value', forget_transient( $key, 'some value' ), 'Expected to receive the default value.' );
		$this->assertFalse( get_transient( $key ), 'Expected the cached value to remain empty.' );
	}
}
