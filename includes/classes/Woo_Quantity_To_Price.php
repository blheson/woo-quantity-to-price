<?php
/**
 * Woo Quantity To Price
 *
 * @author   Andy Fragen
 * @license  MIT
 * @link     https://github.com/blheson/woo-quantity-to-price
 * @package  woo-quantity-to-price
 */

namespace WQP\WooQuantityToPrice;

/**
 * Class Woo_Quantity_To_Price
 *
 * Represents the wqp Woo Quantity To Price set up.
 *
 * @package woo-quantity-to-price
 */
class Woo_Quantity_To_Price {
	/**
	 * The plugin name.
	 *
	 * @var string $plugin
	 */
	protected $plugin;

	/**
	 * The plugin directory.
	 *
	 * @var string $plugin_dir
	 */
	protected $plugin_dir;

	/**
	 * The plugin file.
	 *
	 * @var string $plugin_file
	 */
	protected $plugin_file;

	/**
	 * Constructs a new instance of the class.
	 *
	 * Initializes the plugin directory path and assigns the plugin name and file path.
	 */
	public function __construct() {
		$this->plugin_dir = plugin_dir_path( WQP_PLUGIN_FILE );
		$this->plugin     = wp_basename( $this->plugin_dir );

		$this->plugin_file = $this->plugin . '/woo-quantity-to-price.php';
	}

	/**
	 * Initializes the function.
	 *
	 * Adds various actions and filters to WordPress hooks to set up the functionality of the plugin.
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'add_admin_wqp_menu' ] );
		add_filter( 'plugin_action_links_' . $this->plugin_file, [ $this, 'add_settings_link' ] );
	}

	/**
	 * Adds the admin menu for the Woo Quantity To Price plugin.
	 *
	 * @return void
	 */
	public function add_admin_wqp_menu() {
		add_menu_page( 'Woo Quantity To Price', 'Woo Quantity To Price', 'manage_options', 'wqp_index_page', [ $this, 'wqp_index_page_view' ], '', 110 );
		add_submenu_page( 'wqp_index_page', 'Settings', 'Settings', 'manage_options', 'wqp_settings_page', [ $this, 'wqp_settings_page_view' ] );
	}

	/**
	 * Admin index page for the Woo Quantity To Price plugin.
	 *
	 * @return void
	 */
	public function wqp_index_page_view() {
		require_once $this->plugin_dir . 'views/index.php';
	}

	/**
	 * Admin settings page for the Woo Quantity To Price plugin.
	 *
	 * @return void
	 */
	public function wqp_settings_page_view() {
		require_once $this->plugin_dir . 'views/settings.php';
	}

	/**
	 * Adds a settings link to the array of links.
	 *
	 * @param array $links The array of links.
	 * @return array The updated array of links.
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=wqp_settings_page">Settings</a>';
		array_push( $links, $settings_link );
		return $links;
	}
}
