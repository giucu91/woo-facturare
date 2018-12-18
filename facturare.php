<?php

/**
 * Plugin Name:       Facturare - Persoana Fizica sau Juridica
 * Plugin URI:        https://facturare.georgeciobanu.com
 * Description:       Adauga campuri necesare facturarii persoanelor fizice sau juridice conform legislatiei in vigoare.
 * Version:           1.0.0
 * Author:            George Ciobanu
 * Text Domain:       facturare
 * Domain Path:       /languages
 */

/*

Integrated with :
WooCommerce PDF Invoices -> https://wordpress.org/plugins/woocommerce-pdf-invoices/
WooCommerce PDF Invoices & Packing Slips -> https://wordpress.org/plugins/woocommerce-pdf-invoices-packing-slips/

*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'GC_FACTURARE_VERSION', '1.0.0' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-facturare.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_facturare() {

		
	$plugin = new Facturare();
	$plugin->run();

}
run_facturare();
