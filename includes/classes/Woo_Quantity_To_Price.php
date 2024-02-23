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
	 * The price configuration settings options
	 *
	 * @var array
	 */
	public array $wqp_settings;

	/**
	 * The default price configuration settings options
	 *
	 * @var array
	 */
	public array $defaultSettingsOptions = [
		'value' => '',
		'type'  => '',
	];

	/**
	 * Constructs a new instance of the class.
	 *
	 * Initializes the plugin directory path and assigns the plugin name and file path.
	 */
	public function __construct() {
		$this->plugin_dir = plugin_dir_path( WQP_PLUGIN_FILE );
		$this->plugin     = wp_basename( $this->plugin_dir );

		$this->plugin_file = $this->plugin . '/woo-quantity-to-price.php';

		$this->wqp_settings = get_option( 'wqp_settings_options', $this->defaultSettingsOptions );
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

		add_action( 'admin_init', [ $this, 'add_wqp_settings' ] );

		add_action( 'woocommerce_before_calculate_totals', [ $this, 'wqp_modify_cart' ] );
	}

	/**
	 * Adds the admin menu for the Woo Quantity To Price plugin.
	 *
	 * @return void
	 */
	public function add_admin_wqp_menu() {
		add_submenu_page( 'woocommerce', 'Price configuration', 'Price configuration', 'manage_woocommerce', 'wqp_settings_page', [ $this, 'wqp_settings_page_view' ] );
	}

	/**
	 * Admin price configuration settings page for the Woo Quantity To Price plugin.
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

	/**
	 * Settings page for price configuration settings page for the Woo Quantity To Price plugin.
	 *
	 * @return void
	 */
	public function add_wqp_settings() {
		register_setting( 'wqp_settings_options', 'wqp_settings_options', [ 'sanitize_callback' => [ $this, 'wqp_settings_options_validate' ] ] );

		add_settings_section( 'wqp_settings_section', 'Price Configuration', [ $this, 'wqp_settings_section_text' ], 'wqp_settings_page' );

		add_settings_field( 'value', 'Value', [ $this, 'wqp_settings_value' ], 'wqp_settings_page', 'wqp_settings_section' );
		add_settings_field( 'type', 'Type', [ $this, 'wqp_settings_type' ], 'wqp_settings_page', 'wqp_settings_section' );
	}

	/**
	 * Validates and sanitizes the input for the wqp_settings_options function.
	 *
	 * @param array $input The input array containing the options to be validated.
	 * @return array The validated and sanitized input array.
	 */
	public function wqp_settings_options_validate( $input ) {
		// die(var_dump($input));

		$newinput['value'] = trim( $input['value'] );
		$newinput['type']  = trim( $input['type'] );
		// die(var_dump($newinput));

		if ( empty( $newinput['value'] ) ) {
			$options           = get_option( 'wqp_settings_options' );
			$newinput['value'] = $options['value'] ?? '';
			add_settings_error( 'wqp_settings_options', 'wqp_error', 'value can not be empty.' );
		}

		if ( '' === $newinput['type'] ) {
			$options          = get_option( 'wqp_settings_options' );
			$newinput['type'] = $options['type'] ?? '';
			add_settings_error( 'wqp_settings_options', 'wqp_error', 'Please select a type.' );
		}

		return $newinput;
	}

	/**
	 * Settings section text.
	 *
	 * @return void
	 */
	public function wqp_settings_section_text() {
		// echo 'Set your price configuration';
	}

	/**
	 * Function to write html input for price value in the settings options.
	 *
	 * @return void
	 */
	public function wqp_settings_value() {
		$options = get_option( 'wqp_settings_options', $this->defaultSettingsOptions );
		echo '
            <input type="number" id="wqp_settings_value" name="wqp_settings_options[value]" value="' . esc_attr( $options['value'] ) . '" />
        ';
	}

	/**
	 * Function to write html input for price type in the settings options.
	 *
	 * @return void
	 */
	public function wqp_settings_type() {
		$options = get_option( 'wqp_settings_options', $this->defaultSettingsOptions );
		$select_percent = $options['type'] === 'percent' ? 'selected' : '';
		$select_fixed = $options['type']   === 'fixed' ? 'selected' : '';
		// die(var_dump($options));
		echo '
			<select id="wqp_settings_type" name="wqp_settings_options[type]" value="' . esc_attr( $options['type'] ) . '">
				<option value="">Select a type</option>
				<option value="percent"'. $select_percent .'>Percent</option>
				<option value="fixed"'. $select_fixed .'>Fixed</option>
			</select>
        ';
	}

	/**
	 * Modify the cart based on quantity.
	 */
	public function wqp_modify_cart() {
		global $woocommerce;
		$cart = $woocommerce->cart->get_cart();

		foreach ( $cart as $cart_item_key => $cart_item ) {
			$product_id = $cart_item['product_id'];
			$quantity   = $cart_item['quantity'];

			$product = wc_get_product( $product_id );
			$price   = $product->get_price();

			if ( $quantity > 5 ) {
				if ( '' === $this->wqp_settings['value'] && '' === $this->wqp_settings['type'] ) {
					$cart_item['data']->set_price( $price );
				} elseif ( '' !== $this->wqp_settings['value'] && 'fixed' === $this->wqp_settings['type'] ) {
					$newprice = $price - $this->wqp_settings['value'];

					if ($newprice < 0 ) {
						$newprice = 1;
					}

					$cart_item['data']->set_price( $newprice );
				} elseif ( '' !== $this->wqp_settings['value'] && 'percent' === $this->wqp_settings['type'] ) {
					$newprice = $price * ( ( 100 - (int) $this->wqp_settings['value'] ) / 100 );

					if ($newprice < 0 ) {
						$newprice = 1;
					}

					$cart_item['data']->set_price( $newprice );
				}
			} else {
				$cart_item['data']->set_price( $price );
			}
		}
	}
}
