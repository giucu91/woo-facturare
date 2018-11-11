<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Facturare
 * @subpackage Facturare/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Facturare
 * @subpackage Facturare/public
 * @author     Your Name <email@example.com>
 */
class Facturare_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Facturare_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Facturare_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/frontend.js', array( 'jquery' ), $this->version, false );

	}

	public function override_checkout_fields( $fields ) {

		$options = get_option( 'av_facturare' );
		$customer_id = get_current_user_id();
		$customer_facturare = get_user_meta( $customer_id, 'tip_facturare', true );
		
		$facturare = '' != $customer_facturare ? str_replace( '-', '_', $customer_facturare ) : $options['facturare_default'];

		// Add Facturare Field
		$ordered_fields['tip_facturare'] = array(
			'type'     => $options['facturare_output'],
			'label'    => $options['facturare_label'],
			'required' => true,
			'class'    => array( 'form-row-wide' ),
			'options'  => array(
				'pers-fiz' => $options['facturare_pers_fiz_label'],
				'pers-jur' => $options['facturare_pers_jur_label'],
			),
			'default'  => $options['facturare_default'],
			'priority' => $primary_field_priority,
			'clear'    => true,
		);

		// Extra Fields
		$company = $fields['billing']['billing_company'];
		unset( $fields['billing']['billing_company'] );
		$extra_fields = array();

		// CNP Field
		if ( 'yes' == $options['facturare_pers_fiz_cnp_vizibility'] ) {
			$extra_fields['cnp'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_fiz_cnp_label'],
				'placeholder' => $options['facturare_pers_fiz_cnp_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_fiz' ),
				'needed_req'  => $options['facturare_pers_fiz_cnp_required'],
			);

			if ( 'pers_fiz' != $facturare ) {
				$extra_fields['cnp']['class'][] = 'av-hide';
			}

		}

		// Company Field
		if ( 'yes' == $options['facturare_pers_jur_company_vizibility'] ) {
			$extra_fields['billing_company'] = $company;

			$extra_fields['billing_company']['label']       = $options['facturare_pers_jur_company_label'];
			$extra_fields['billing_company']['placeholder'] = $options['facturare_pers_jur_company_placeholder'];
			$extra_fields['billing_company']['needed_req']  = $options['facturare_pers_jur_company_required'];
			$extra_fields['billing_company']['class'][]     = 'show_if_pers_jur';
			$extra_fields['billing_company']['required']    = false;

			if ( 'pers_jur' != $facturare ) {
				$extra_fields['billing_company']['class'][] = 'av-hide';
			}

		}

		// CUI Field
		if ( 'yes' == $options['facturare_pers_jur_cui_vizibility'] ) {
			$extra_fields['cui'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_jur_cui_label'],
				'placeholder' => $options['facturare_pers_jur_cui_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_jur' ),
				'needed_req'  => $options['facturare_pers_jur_cui_required'],
			);

			if ( 'pers_jur' != $facturare ) {
				$extra_fields['cui']['class'][] = 'av-hide';
			}

		}

		// Nr. Reg. Com Field
		if ( 'yes' == $options['facturare_pers_jur_nr_reg_com_vizibility'] ) {
			$extra_fields['nr_reg_com'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_jur_nr_reg_com_label'],
				'placeholder' => $options['facturare_pers_jur_nr_reg_com_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_jur' ),
				'needed_req'  => $options['facturare_pers_jur_nr_reg_com_required'],
			);

			if ( 'pers_jur' != $facturare ) {
				$extra_fields['nr_reg_com']['class'][] = 'av-hide';
			}

		}

		// Nume Banca Field
		if ( 'yes' == $options['facturare_pers_jur_nume_banca_vizibility'] ) {
			$extra_fields['nume_banca'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_jur_nume_banca_label'],
				'placeholder' => $options['facturare_pers_jur_nume_banca_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_jur' ),
				'needed_req'  => $options['facturare_pers_jur_nume_banca_required'],
			);

			if ( 'pers_jur' != $facturare ) {
				$extra_fields['nume_banca']['class'][] = 'av-hide';
			}

		}

		// IBAN Field
		if ( 'yes' == $options['facturare_pers_jur_iban_vizibility'] ) {
			$extra_fields['iban'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_jur_iban_label'],
				'placeholder' => $options['facturare_pers_jur_iban_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_jur' ),
				'needed_req'  => $options['facturare_pers_jur_iban_required'],
			);

			if ( 'pers_jur' != $facturare ) {
				$extra_fields['iban']['class'][] = 'av-hide';
			}
		}

		foreach ( $fields['billing'] as $key => $field ) {
			
			$ordered_fields[ $key ] = $field;

			if ( 'billing_last_name' == $key ) {
				$ordered_fields = array_merge( $ordered_fields, $extra_fields );
			}

		}

		$fields['billing'] = $ordered_fields;

		return $fields;
	}

	public function override_field_html( $field, $key, $args ) {

		$our_fields = array( 'cnp', 'iban', 'nume_banca', 'nr_reg_com', 'cui', 'billing_company', 'cnp' );

		if ( in_array( $key, $our_fields ) ) {
			
			$optional_label = '<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
			$required_label = '<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';

			if ( 'yes' == $args['needed_req'] ) {
				$field = str_replace( $optional_label, $required_label, $field );
			}

		}

		return $field;

	}

	public function hide_fields() {

		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			echo '<style>.av-hide{display:none}</style>';
		}

	}

	public function add_js_to_footer() {

		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			echo '<script>!function(i){"use strict";i(document).ready(function(){i("#tip_facturare").change(function(){"pers-jur"==i(this).val()?(i(".show_if_pers_jur").show(),i(".show_if_pers_fiz").hide()):(i(".show_if_pers_jur").hide(),i(".show_if_pers_fiz").show())})})}(jQuery);</script>';
		}
	}

	public function validate_checkout() {

		$options = get_option( 'av_facturare' );

		if ( 'pers-fiz' == $_POST['tip_facturare'] ) {
			
			// validate CNP
			if ( 'yes' == $options['facturare_pers_fiz_cnp_required'] && '' == $_POST['cnp'] && '' != $options['facturare_pers_fiz_cnp_error'] ) {
				wc_add_notice( $options['facturare_pers_fiz_cnp_error'], 'error' );
			}

		}

		if ( 'pers-jur' == $_POST['tip_facturare'] ) {
			
			// validate Nume Firma
			if ( 'yes' == $options['facturare_pers_jur_company_required'] && '' == $_POST['billing_company'] && '' != $options['facturare_pers_jur_company_error'] ) {
				wc_add_notice( $options['facturare_pers_jur_company_error'], 'error' );
			}

			// validate CUI
			if ( 'yes' == $options['facturare_pers_jur_cui_required'] && '' == $_POST['cui'] && '' != $options['facturare_pers_jur_cui_error'] ) {
				wc_add_notice( $options['facturare_pers_jur_cui_error'], 'error' );
			}

			// validate Nr. Reg. Com.
			if ( 'yes' == $options['facturare_pers_jur_nr_reg_com_required'] && '' == $_POST['nr_reg_com'] && '' != $options['facturare_pers_jur_nr_reg_com_error'] ) {
				wc_add_notice( $options['facturare_pers_jur_nr_reg_com_error'], 'error' );
			}

			// validate Nume Banca
			if ( 'yes' == $options['facturare_pers_jur_nume_banca_required'] && '' == $_POST['nume_banca'] && '' != $options['facturare_pers_jur_nume_banca_error'] ) {
				wc_add_notice( $options['facturare_pers_jur_nume_banca_error'], 'error' );
			}

			// validate Nume Banca
			if ( 'yes' == $options['facturare_pers_jur_iban_required'] && '' == $_POST['iban'] && '' != $options['facturare_pers_jur_iban_error'] ) {
				wc_add_notice( $options['facturare_pers_jur_iban_error'], 'error' );
			}

		}

	}

	// Add fields to customer profile
	public function add_profile_fields( $fields, $load_address ) {

		if ( 'billing' != $load_address ) {
			return $fields;
		}

		$options = get_option( 'av_facturare' );
		$customer_id = get_current_user_id();

		// Add Facturare Field
		$ordered_fields['tip_facturare'] = array(
			'type'     => $options['facturare_output'],
			'label'    => $options['facturare_label'],
			'required' => true,
			'class'    => array( 'form-row-wide' ),
			'options'  => array(
				'pers-fiz' => $options['facturare_pers_fiz_label'],
				'pers-jur' => $options['facturare_pers_jur_label'],
			),
			'default'  => $options['facturare_default'],
			'priority' => $primary_field_priority,
			'clear'    => true,
			'value'    => get_user_meta( $customer_id, 'tip_facturare', true ),
		);

		// Extra Fields
		$company = $fields['billing_company'];
		unset( $fields['billing_company'] );
		$extra_fields = array();

		// CNP Field
		if ( 'yes' == $options['facturare_pers_fiz_cnp_vizibility'] ) {
			$extra_fields['cnp'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_fiz_cnp_label'],
				'placeholder' => $options['facturare_pers_fiz_cnp_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_fiz' ),
				'value'       => get_user_meta( $customer_id, 'cnp', true ),
			);
		}

		// Company Field
		if ( 'yes' == $options['facturare_pers_jur_company_vizibility'] ) {
			$extra_fields['billing_company'] = $company;

			$extra_fields['billing_company']['label']       = $options['facturare_pers_jur_company_label'];
			$extra_fields['billing_company']['placeholder'] = $options['facturare_pers_jur_company_placeholder'];
			$extra_fields['billing_company']['needed_req']  = $options['facturare_pers_jur_company_required'];
			$extra_fields['billing_company']['class'][]     = 'show_if_pers_jur';
			$extra_fields['billing_company']['required']    = false;

		}

		// CUI Field
		if ( 'yes' == $options['facturare_pers_jur_cui_vizibility'] ) {
			$extra_fields['cui'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_jur_cui_label'],
				'placeholder' => $options['facturare_pers_jur_cui_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_jur' ),
				'value'       => get_user_meta( $customer_id, 'cui', true ),
			);

		}

		// Nr. Reg. Com Field
		if ( 'yes' == $options['facturare_pers_jur_nr_reg_com_vizibility'] ) {
			$extra_fields['nr_reg_com'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_jur_nr_reg_com_label'],
				'placeholder' => $options['facturare_pers_jur_nr_reg_com_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_jur' ),
				'value'       => get_user_meta( $customer_id, 'nr_reg_com', true ),
			);

		}

		// Nume Banca Field
		if ( 'yes' == $options['facturare_pers_jur_nume_banca_vizibility'] ) {
			$extra_fields['nume_banca'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_jur_nume_banca_label'],
				'placeholder' => $options['facturare_pers_jur_nume_banca_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_jur' ),
				'value'       => get_user_meta( $customer_id, 'nume_banca', true ),
			);

		}

		// IBAN Field
		if ( 'yes' == $options['facturare_pers_jur_iban_vizibility'] ) {
			$extra_fields['iban'] = array(
				'type'        => 'text',
				'label'       => $options['facturare_pers_jur_iban_label'],
				'placeholder' => $options['facturare_pers_jur_iban_placeholder'],
				'priority'    => 25,
				'clear'       => true,
				'class'       => array( 'form-row-wide', 'show_if_pers_jur' ),
				'value'       => get_user_meta( $customer_id, 'iban', true ),
			);

		}

		foreach ( $fields as $key => $field ) {
			
			$ordered_fields[ $key ] = $field;

			if ( 'billing_last_name' == $key ) {
				$ordered_fields = array_merge( $ordered_fields, $extra_fields );
			}

		}

		return $ordered_fields;
	}

	public function save_profile_fields( $user_id, $load_address ) {

		if ( isset( $_POST['tip_facturare'] ) ) {
			update_user_meta( $user_id, 'tip_facturare', sanitize_text_field( $_POST['tip_facturare'] ) );
		}

		if ( isset( $_POST['cnp'] ) ) {
			update_user_meta( $user_id, 'cnp', sanitize_text_field( $_POST['cnp'] ) );
		}

		if ( isset( $_POST['cui'] ) ) {
			update_user_meta( $user_id, 'cui', sanitize_text_field( $_POST['cui'] ) );
		}

		if ( isset( $_POST['nume_banca'] ) ) {
			update_user_meta( $user_id, 'nume_banca', sanitize_text_field( $_POST['nume_banca'] ) );
		}

		if ( isset( $_POST['iban'] ) ) {
			update_user_meta( $user_id, 'iban', sanitize_text_field( $_POST['iban'] ) );
		}

	}

}
