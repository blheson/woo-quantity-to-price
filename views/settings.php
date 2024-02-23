<?php
/**
 * Woo Quantity To Price
 *
 * @author   Blessing Udor
 * @license  MIT
 * @link     https://github.comblheson/woo-quantity-to-price
 * @package  woo-quantity-to-price
 */

if ( ! current_user_can( 'manage_woocommerce' ) ) {
	return;
}
?>
<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
		settings_fields( 'wqp_settings_options' );

		do_settings_sections( 'wqp_settings_page' );

		settings_errors();

		submit_button( __( 'Save Settings', 'textdomain' ) );
		?>
	</form>
</div>