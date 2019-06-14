<?php

class Woo_Facturare {

	protected $loader;
	private $plugin_name;
	private $version;

	public function __construct() {

		if ( defined( 'WOOFACTURARE_VERSION' ) ) {
			$this->version = WOOFACTURARE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'woo-facturare';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	private function load_dependencies() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-facturare-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-facturare-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-facturare-public.php';
		$this->loader = new Woo_Facturare_Loader();

	}

	private function set_locale() {

		$this->loader->add_action( 'plugins_loaded', $this, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$facturare_admin = new Woo_Facturare_Admin( $this->plugin_name, $this->version );

		$this->loader->add_filter( 'woocommerce_get_settings_pages', $facturare_admin, 'setting_page_class' );
		$this->loader->add_filter( 'wc_admin_page_tab_sections', $facturare_admin, 'register_wc_admin_tabs' );

		// Edit Plugin Links
		$this->loader->add_filter( 'plugin_action_links', $facturare_admin, 'action_links', 10, 2 );

		// Save Order Meta
		$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $facturare_admin, 'update_order_meta' );

		// Save extra fields to customer
		$this->loader->add_action( 'woocommerce_checkout_update_user_meta', $facturare_admin, 'update_customer_data', 10, 2 );

		// Filter billing fields
		$this->loader->add_filter( 'woocommerce_order_formatted_billing_address', $facturare_admin, 'filter_billing_fields', 90, 2 );
		$this->loader->add_filter( 'woocommerce_my_account_my_address_formatted_address', $facturare_admin, 'myacc_filter_billing_fields', 90, 3 );
		$this->loader->add_filter( 'woocommerce_formatted_address_replacements', $facturare_admin, 'extra_fields_replacements', 90, 2 );
		$this->loader->add_filter( 'woocommerce_localisation_address_formats', $facturare_admin, 'localisation_address_formats', 90 );

		// WC Admin
		$this->loader->add_action( 'admin_menu', $facturare_admin, 'wc_admin_connect_page', 15 );

	}

	private function define_public_hooks() {

		$plugin_public = new Woo_Facturare_Public();

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

	public function run() {
		$this->loader->run();
	}

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woo-facturare',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/'
		);

	}

}
