<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Facturare
 * @subpackage Facturare/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Facturare
 * @subpackage Facturare/includes
 * @author     Your Name <email@example.com>
 */
class Facturare {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Facturare_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'GC_FACTURARE_VERSION' ) ) {
			$this->version = GC_FACTURARE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'facturare';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Facturare_Loader. Orchestrates the hooks of the plugin.
	 * - Facturare_i18n. Defines internationalization functionality.
	 * - Facturare_Admin. Defines all hooks for the admin area.
	 * - Facturare_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-facturare-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-facturare-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-facturare-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-facturare-public.php';

		$this->loader = new Facturare_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Facturare_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Facturare_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$facturare_admin = new Facturare_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_filter( 'woocommerce_get_settings_pages', $facturare_admin, 'setting_page_class' );

		// Save Order Meta
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $facturare_admin, 'update_order_meta' );

		// Add extra info to order details.
		// $this->loader->add_action( 'woocommerce_admin_order_data_after_billing_address', $facturare_admin, 'output_order_details' );

		// Save extra fields to customer
		$this->loader->add_action( 'woocommerce_checkout_update_user_meta', $facturare_admin, 'update_customer_data', 10, 2 );

		// Output extra fields through billing info

		// Filter billing fields
		$this->loader->add_filter( 'woocommerce_order_formatted_billing_address', $facturare_admin, 'filter_billing_fields', 90, 2 );
		$this->loader->add_filter( 'woocommerce_my_account_my_address_formatted_address', $facturare_admin, 'myacc_filter_billing_fields', 90, 3 );
		$this->loader->add_filter( 'woocommerce_formatted_address_replacements', $facturare_admin, 'extra_fields_replacements', 90, 2 );
		$this->loader->add_filter( 'woocommerce_localisation_address_formats', $facturare_admin, 'localisation_address_formats', 90 );

		

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Facturare_Public( $this->get_plugin_name(), $this->get_version() );

		// $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'hide_fields' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'add_js_to_footer', 99 );

		// Change checkout fields
		$this->loader->add_filter( 'woocommerce_checkout_fields', $plugin_public, 'override_checkout_fields', 99 );
		$this->loader->add_filter( 'woocommerce_form_field', $plugin_public, 'override_field_html', 20, 3 );

		// Validate checkout fields
		$this->loader->add_action( 'woocommerce_checkout_process', $plugin_public, 'validate_checkout' );

		// Add fields to profile
		$this->loader->add_filter( 'woocommerce_address_to_edit', $plugin_public, 'add_profile_fields', 90, 2 );
		// Save fields to profile
		$this->loader->add_filter( 'woocommerce_customer_save_address', $plugin_public, 'save_profile_fields', 90, 2 );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Facturare_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
