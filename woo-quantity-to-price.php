<?php
/**
 * Plugin Name:       Woo Quantity To Price
 * Plugin URI:        https://github.com/blheson/woo-quantity-to-price
 * Description:       A plugin that reduces the price of product by 10% when more that 5 of the product is added to cart
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Udor Blessing
 * Author URI:        https://github.com/blheson
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/blheson/woo-quantity-to-price
 * Text Domain:       woo-quantity-to-price
 *
 * @link     https://github.com/blheson/woo-quantity-to-price
 * @package  woo-quantity-to-price
 */

use WQP\WooQuantityToPrice\Woo_Quantity_To_Price;

defined( 'ABSPATH' ) || die( 'Gerrarahae' );
define( 'WQP_PLUGIN_FILE', __FILE__ );

if ( ! function_exists( 'get_plugin_data' ) ) {
	require_once ABSPATH . 'wp-admin/includes/plugin.php';
}

$plugin_data = get_plugin_data( __FILE__ );

define( 'WQP_PLUGIN_VERSION', $plugin_data['Version'] );

require plugin_dir_path( __FILE__ ) . 'includes' . DIRECTORY_SEPARATOR . 'utility' . DIRECTORY_SEPARATOR . 'WQP_Utility.php';

require_once plugin_dir_path( __FILE__ ) . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
	if ( class_exists( 'WQP\WooQuantityToPrice\Woo_Quantity_To_Price' ) ) {
		$WooQuantityToPrice = new Woo_Quantity_To_Price();
		$WooQuantityToPrice->init();
	}
}
