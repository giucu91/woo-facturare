<?php

/**
 * Plugin Name:       Facturare - Persoana Fizica sau Juridica
 * Plugin URI:        https://facturare.georgeciobanu.com
 * Description:       Adauga campuri necesare facturarii persoanelor fizice sau juridice conform legislatiei din Romania in vigoare.
 * Version:           1.0.6
 * Author:            George Ciobanu
 * Text Domain:       woo-facturare
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WOOFACTURARE_VERSION', '1.0.6' );
define( 'WOOFACTURARE_SLUG', plugin_basename( __FILE__ ) );
define( 'WOOFACTURARE_PATH', plugin_dir_path( __FILE__ ) );

require plugin_dir_path( __FILE__ ) . 'includes/class-facturare.php';

function run_woo_facturare() {
		
	$plugin = new Woo_Facturare();
	$plugin->run();

}
run_woo_facturare();
