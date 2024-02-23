<?php
/**
 * Woo Quantity To Price
 *
 * @author   Blessing Udor
 * @license  MIT
 * @link     https://github.comblheson/woo-quantity-to-price
 * @package  woo-quantity-to-price
 */

// If uninstall not called from WordPress die immediately.
defined( 'WP_UNINSTALL_PLUGIN' ) || die( 'Gerrarahae' );

// Remove Option.
delete_option( 'wqp_settings_options' );
// Site options in Multisite.
delete_site_option( 'wqp_settings_options' );

// Clear any cached data that has been removed.
wp_cache_flush();
