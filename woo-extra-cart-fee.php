<?php

/**
 * Plugin Name:       Woo Extra Cart Fee 
 * Plugin URI:        http://www.wpcodelibrary.com
 * Description:       This plugin allow merchant to add extra fee based on product and product variations in the cart.
 * Version:           1.0.1
 * Author:            WPCodelibrary
 * Author URI:        http://www.wpcodelibrary.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-extra-cart-fee
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-extra-cart-fee-activator.php
 */
function activate_woo_extra_cart_fee() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-extra-cart-fee-activator.php';
	Woo_Extra_Cart_Fee_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-extra-cart-fee-deactivator.php
 */
function deactivate_woo_extra_cart_fee() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woo-extra-cart-fee-deactivator.php';
	Woo_Extra_Cart_Fee_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woo_extra_cart_fee' );
register_deactivation_hook( __FILE__, 'deactivate_woo_extra_cart_fee' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woo-extra-cart-fee.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_extra_cart_fee() {

	$plugin = new Woo_Extra_Cart_Fee();
	$plugin->run();

}
run_woo_extra_cart_fee();