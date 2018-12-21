<?php

class Woo_Facturare_Admin {

	public function __construct() {}

	public function setting_page_class( $settings ) {
		$settings[] = include 'class-wc-settings-facturare.php';
	}

	/**
	 * Update the order meta with extra fields
	*/
	public function update_order_meta( $order_id ) {
		$av_settings = array();

		if ( ! isset( $_POST['tip_facturare'] ) ) {
			return;
		}

		$av_settings['tip_facturare'] = sanitize_text_field($_POST['tip_facturare']);

		if ( 'pers-fiz' == $_POST['tip_facturare'] ) {
			
			if ( isset( $_POST['cnp'] ) && '' != $_POST['cnp'] ) {
				$av_settings['cnp'] = sanitize_text_field($_POST['cnp']);
			}

		}elseif ( 'pers-jur' == $_POST['tip_facturare'] ) {
			
			if ( isset( $_POST['cui'] ) && '' != $_POST['cui'] ) {
				$av_settings['cui'] = sanitize_text_field($_POST['cui']);
			}

			if ( isset( $_POST['nr_reg_com'] ) && '' != $_POST['nr_reg_com'] ) {
				$av_settings['nr_reg_com'] = sanitize_text_field($_POST['nr_reg_com']);
			}

			if ( isset( $_POST['nume_banca'] ) && '' != $_POST['nume_banca'] ) {
				$av_settings['nume_banca'] = sanitize_text_field($_POST['nume_banca']);
			}

			if ( isset( $_POST['iban'] ) && '' != $_POST['iban'] ) {
				$av_settings['iban'] = sanitize_text_field($_POST['iban']);
			}

		}

		if ( ! empty( $av_settings ) ) {
			update_post_meta( $order_id, 'av_facturare', $av_settings );
		}

	}

	/**
	 * Update the customer meta with extra fields
	*/
	public function update_customer_data( $customer_id, $data ) {
		$av_settings = array();

		if ( ! isset( $data['tip_facturare'] ) ) {
			return;
		}

		$av_settings['tip_facturare'] = $data['tip_facturare'];

		if ( 'pers-fiz' == $data['tip_facturare'] ) {
			
			if ( isset( $data['cnp'] ) && '' != $data['cnp'] ) {
				$av_settings['cnp'] = sanitize_text_field($data['cnp']);
			}

		}elseif ( 'pers-jur' == $data['tip_facturare'] ) {
			
			if ( isset( $data['cui'] ) && '' != $data['cui'] ) {
				$av_settings['cui'] = sanitize_text_field($data['cui']);
			}

			if ( isset( $data['nr_reg_com'] ) && '' != $data['nr_reg_com'] ) {
				$av_settings['nr_reg_com'] = sanitize_text_field($data['nr_reg_com']);
			}

			if ( isset( $data['nume_banca'] ) && '' != $data['nume_banca'] ) {
				$av_settings['nume_banca'] = sanitize_text_field($data['nume_banca']);
			}

			if ( isset( $data['iban'] ) && '' != $data['iban'] ) {
				$av_settings['iban'] = sanitize_text_field($data['iban']);
			}

		}

		if ( ! empty( $av_settings ) ) {
			foreach ( $av_settings as $key => $value ) {
				update_user_meta( $customer_id, $key, sanitize_text_field( $value ) );
			}
		}

	}

	/**
	 * Filter billing fields.
	*/
	public function filter_billing_fields( $fields, $order ){

		$defaults = array(
			'cnp'        => '',
			'cui'        => '',
			'nr_reg_com' => '',
			'nume_banca' => '',
			'iban'       => '',
		);

		$facturare = $order->get_meta( 'av_facturare' );
		$tip = $facturare['tip_facturare'];
		unset( $facturare['tip_facturare'] );

		if ( 'pers-fiz' == $tip && isset( $fields['company'] ) ) {
			$fields['company'] = '';
		}

		$extra_fields = wp_parse_args( $facturare, $defaults );

		return array_merge( $fields, $extra_fields );

	}

	public function myacc_filter_billing_fields( $fields, $customer_id, $address_type ) {

		if ( 'billing' != $address_type ) {
			return $fields;
		}

		$fields['cnp']        = get_user_meta( $customer_id, 'cnp', true );
		$fields['cui']        = get_user_meta( $customer_id, 'cui', true );
		$fields['nr_reg_com'] = get_user_meta( $customer_id, 'nr_reg_com', true );
		$fields['nume_banca'] = get_user_meta( $customer_id, 'nume_banca', true );
		$fields['iban']       = get_user_meta( $customer_id, 'iban', true );

		return $fields;

	}

	/**
	 * Add replacements for our extra fields.
	*/
	public function extra_fields_replacements( $replacements, $args ) {

		$replacements['{cnp}']        = $args['cnp'];
		$replacements['{cui}']        = $args['cui'];
		$replacements['{nr_reg_com}'] = $args['nr_reg_com'];
		$replacements['{nume_banca}'] = $args['nume_banca'];
		$replacements['{iban}']       = $args['iban'];

		return $replacements;

	}

	public function localisation_address_formats( $formats ) {
		$formats['default'] = "{name}\n{cnp}\n{company}\n{cui}\n{nr_reg_com}\n{nume_banca}\n{iban}\n{address_1}\n{address_2}\n{city}\n{state}\n{postcode}\n{country}";

		return $formats;
	}

	public function action_links( $links, $file ){

		if ( 'woo-facturare/facturare.php' == $file ) {
			$links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=facturare&section' ) . '">' . esc_html__( 'Setari', 'woo-facturare' ) . '</a>';
		}

		return $links;
	}

}
