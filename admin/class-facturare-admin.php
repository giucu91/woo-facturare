<?php

class Woo_Facturare_Admin {

	private $fields =array() ;

	public function __construct() {}

	public function setting_page_class( $settings ) {
		$settings[] = include 'class-wc-settings-facturare.php';
		return $settings;
	}

	public function register_wc_admin_tabs( $tabs_with_sections ){
		$tabs_with_sections['facturare'] = array( '', 'pers-fiz', 'pers-jur' );
		return $tabs_with_sections;
	}

	public function wc_admin_connect_page(){

		if ( ! function_exists( 'wc_admin_connect_page' ) ) {
			return;
		}

		$admin_page_base    = 'admin.php';

		wc_admin_connect_page(
			array(
				'id'        => 'woocommerce-settings-facturare',
				'parent'    => 'woocommerce-settings',
				'screen_id' => 'woocommerce_page_wc-settings-facturare',
				'title'     => array(
					__( 'Facturare', 'woo-facturare' ),
					__( 'General', 'woo-facturare' ),
				),
				'path'      => add_query_arg(
					array(
						'page' => 'wc-settings',
						'tab'  => 'facturare',
					),
					$admin_page_base
				),
			)
		);

		wc_admin_connect_page(
			array(
				'id'        => 'woocommerce-settings-facturare-pers-fiz',
				'parent'    => 'woocommerce-settings-facturare',
				'screen_id' => 'woocommerce_page_wc-settings-facturare-pers-fiz',
				'title'     => __( 'Persoana Fizica', 'woo-facturare' ),
			)
		);

		wc_admin_connect_page(
			array(
				'id'        => 'woocommerce-settings-facturare-pers-jur',
				'parent'    => 'woocommerce-settings-facturare',
				'screen_id' => 'woocommerce_page_wc-settings-facturare-pers-jur',
				'title'     => __( 'Persoana Juridica', 'woo-facturare' ),
			)
		);

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
		$tip = isset($facturare['tip_facturare']) ? $facturare['tip_facturare'] : '';
		if ( isset( $facturare['tip_facturare'] ) ) {
			unset( $facturare['tip_facturare'] );
		}

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

		$replacements['{cnp}']        = isset( $args['cnp'] ) ? $args['cnp'] : '';
		$replacements['{cui}']        = isset( $args['cui'] ) ? $args['cui'] : '';
		$replacements['{nr_reg_com}'] = isset( $args['nr_reg_com'] ) ? $args['nr_reg_com'] : '';
		$replacements['{nume_banca}'] = isset( $args['nume_banca'] ) ? $args['nume_banca'] : '';
		$replacements['{iban}']       = isset( $args['iban'] ) ? $args['iban'] : '';

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

	public function settings_links( $links ){

		if ( is_array( $links ) ) {
            $links['facturare-settings'] = sprintf('<a href="%s">%s</a>', admin_url( 'admin.php?page=wc-settings&tab=facturare' ), __( 'Settings', 'woo-facturare' ) );
        }

		return $links;

	}

	public function start_advertise(){
		echo '<style>';
		echo '.woofacturare-feedback-box {width: 50%;background: #fff;padding: 10px;box-sizing: border-box;border: 1px solid #ddd;margin-right: 10px;}';
		echo '.woofacturare-contact-box {width: 30%;background: #fff;padding: 10px;box-sizing: border-box;border: 1px solid #ddd;}';
		echo '.woofacturare-advertise{display:flex;}';
		echo '</style>';
		echo '<div class="woofacturare-advertise">';
	}

	public function stop_advertise(){
		echo '</div>';
	}

	public function feedback_box(){

		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';

		if ( 'facturare' != $tab ) {
			return;
		}

		include WOOFACTURARE_PATH . 'admin/views/feedback-box.php';

	}

	public function contact_box(){

		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';

		if ( 'facturare' != $tab ) {
			return;
		}

		include WOOFACTURARE_PATH . 'admin/views/contact-box.php';

	}

}
