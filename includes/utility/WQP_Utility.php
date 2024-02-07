<?php
/**
 * Woo Quantity To Price
 *
 * @author   Andy Fragen
 * @license  MIT
 * @link     https://github.com/blheson/woo-quantity-to-price
 * @package  woo-quantity-to-price
 */

if ( ! function_exists( 'wqp_sanitize_ar' ) ) {
	/**
	 * Sanitizes an array and returns it.
	 *
	 * @param array $array Array to sanitize.
	 * @return mixed|string
	 */
	function wqp_sanitize_ar( $array ) {
		if ( ! is_array( $array ) ) {
			return [];
		}
		$keys = array_keys( $array );
		$keys = array_map( 'sanitize_key', $keys );

		$values = array_values( $array );
		$values = array_map( 'sanitize_text_field', $values );

		$array = array_combine( $keys, $values );
		return $array;
	}
}

if ( ! function_exists( 'wqp_get_ar' ) ) {
	/**
	 * Get an array value.
	 *
	 * @param array  $array The array.
	 * @param string $name The name value of the array.
	 * @return string
	 */
	function wqp_get_ar( $array, $name ) {
		if ( isset( $array[ $name ] ) ) {
			return $array[ $name ];
		}

		return '';
	}
}
