<?php

class Woo_Facturare_Experimental_Checkout {

	private $defaults;

	public function __construct() {
		$this->defaults = array(
			'facturare_pers_fiz_label'                  => esc_html__( 'Persoana Fizica', 'woo-facturare' ),
			'facturare_pers_fiz_cnp_label'              => esc_html__( 'CNP', 'woo-facturare' ),
			'facturare_pers_fiz_cnp_placeholder'        => esc_html__( 'Introduceti Codul numeric personal', 'woo-facturare' ),
			'facturare_pers_fiz_cnp_vizibility'         => 'no',
			'facturare_pers_fiz_cnp_required'           => 'no',
			'facturare_pers_fiz_cnp_error'              => esc_html__( 'Datorita legislatiei in vigoare trebuie sa completati campul CNP', 'woo-facturare' ),
			'facturare_pers_jur_label'                  => esc_html__( 'Persoana Juridica', 'woo-facturare' ),
			'facturare_pers_jur_company_label'          => esc_html__( 'Nume Firma', 'woo-facturare' ),
			'facturare_pers_jur_company_placeholder'    => esc_html__( 'Introduceti numele firmei dumneavoastra', 'woo-facturare' ),
			'facturare_pers_jur_company_vizibility'     => 'yes',
			'facturare_pers_jur_company_required'       => 'yes',
			'facturare_pers_jur_company_error'          => esc_html__( 'Pentru a va putea emite factura avem nevoie de numele firmei dumneavoastra', 'woo-facturare' ),
			'facturare_pers_jur_cui_label'              => esc_html__( 'CUI', 'woo-facturare' ),
			'facturare_pers_jur_cui_placeholder'        => esc_html__( 'Introduceti Codul Unic de Inregistrare', 'woo-facturare' ),
			'facturare_pers_jur_cui_vizibility'         => 'yes',
			'facturare_pers_jur_cui_validare'           => 'yes',
			'facturare_pers_jur_cui_required'           => 'yes',
			'facturare_pers_jur_cui_error'              => esc_html__( 'Pentru a va putea emite factura avem nevoie de CUI-ul firmei dumneavoastra', 'woo-facturare' ),
			'facturare_pers_jur_nr_reg_com_label'       => esc_html__( 'Nr. Reg. Com', 'woo-facturare' ),
			'facturare_pers_jur_nr_reg_com_placeholder' => 'J20/20/20.02.2020',
			'facturare_pers_jur_nr_reg_com_vizibility'  => 'yes',
			'facturare_pers_jur_nr_reg_com_required'    => 'yes',
			'facturare_pers_jur_nr_reg_com_error'       => esc_html__( 'Pentru a va putea emite factura avem nevoie de numarul de ordine in registrul comertului', 'woo-facturare' ),
			'facturare_pers_jur_nume_banca_label'       => esc_html__( 'Nume Banca', 'woo-facturare' ),
			'facturare_pers_jur_nume_banca_placeholder' => esc_html__( 'Numele bancii cu care lucrati', 'woo-facturare' ),
			'facturare_pers_jur_nume_banca_vizibility'  => 'no',
			'facturare_pers_jur_nume_banca_required'    => 'no',
			'facturare_pers_jur_nume_banca_error'       => esc_html__( 'Pentru a va putea emite factura avem nevoie de numele bancii cu care lucrati', 'woo-facturare' ),
			'facturare_pers_jur_iban_label'             => esc_html__( 'IBAN', 'woo-facturare' ),
			'facturare_pers_jur_iban_placeholder'       => esc_html__( 'Numarul contului IBAN', 'woo-facturare' ),
			'facturare_pers_jur_iban_vizibility'        => 'no',
			'facturare_pers_jur_iban_required'          => 'no',
			'facturare_pers_jur_iban_error'             => esc_html__( 'Pentru a va putea emite factura avem nevoie de numarul contului', 'woo-facturare' ),
			'facturare_output'                          => 'select',
			'facturare_default'                         => 'pers-fiz',
			'facturare_label'                           => esc_html__( 'Tip Facturare', 'woo-facturare' ),
		);

		add_action( 'woocommerce_init', array( $this, 'register_fields' ) );
		add_filter( 'woocommerce_get_country_locale', array( $this, 'order_fields' ) );
		add_action( 'woocommerce_set_additional_field_value', array( $this, 'save_fields' ), 10, 4 );
		add_action( 'woocommerce_store_api_checkout_order_processed', array( $this, 'delete_meta' ) );
		add_action( 'wp_head', array( $this, 'enqueue_block_styles' ) );
		add_filter( 'pre_option_woocommerce_checkout_company_field', array( $this, '_return_hide' ) );
		add_filter( 'woocommerce_get_default_value_for_avfacturare/tip_facturare', array( $this, 'default_tip_facturare' ), 10, 3 );

	}

	public function register_fields(){

		$options = get_option( 'av_facturare', array() );
		$options = wp_parse_args( $options, $this->defaults );

		// register tip facturare
		woocommerce_register_additional_checkout_field(
			array(
				'id'            => 'avfacturare/tip_facturare',
				'label'         => $options['facturare_label'],
				'optionalLabel' => $options['facturare_label'],
				'location'      => 'address',
				'required'      => true,
				'type'          => 'select',
				'options'     => [
					[
						'value' => 'pers-fiz',
						'label' => $options['facturare_pers_fiz_label']
					],
					[
						'value' => 'pers-jur',
						'label' => $options['facturare_pers_jur_label']
					]
				]
			),
		);

		// CNP Field
		if ( 'yes' == $options['facturare_pers_fiz_cnp_vizibility'] ) {

			$args = array(
				'id'            => 'avfacturare/cnp',
				'label'         => $options['facturare_pers_fiz_cnp_label'],
				'optionalLabel' => $options['facturare_pers_fiz_cnp_label'],
				'location'      => 'address',
				'type'          => 'text',
				'required' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'const' => 'pers-fiz'
									]
								]
							]
						]
					]
				],
				'hidden' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'not' => [
											'const' => 'pers-fiz'
										]
									]
								]
							]
						]
					]
				]
			);

			if ( 'no' == $options['facturare_pers_fiz_cnp_required'] ) {
				$args['required'] = false;
			}

			woocommerce_register_additional_checkout_field( $args );

		}

		// // CUI Field
		if ( 'yes' == $options['facturare_pers_jur_cui_vizibility'] ) {

			$args = array(
				'id'            => 'avfacturare/cui',
				'label'         => $options['facturare_pers_jur_cui_label'],
				'optionalLabel' => $options['facturare_pers_jur_cui_label'],
				'location'      => 'address',
				'type'          => 'text',
				'required' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'const' => 'pers-jur'
									]
								]
							]
						]
					]
				],
				'hidden' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'not' => [
											'const' => 'pers-jur'
										]
									]
								]
							]
						]
					]
				]
			);

			if ( 'no' == $options['facturare_pers_jur_cui_required'] ) {
				$args['required'] = false;
			}

			woocommerce_register_additional_checkout_field( $args );

		}

		// Company Field
		if ( 'yes' == $options['facturare_pers_jur_company_vizibility'] ) {

			$args = array(
				'id'            => 'avfacturare/company',
				'label'         => $options['facturare_pers_jur_company_label'],
				'optionalLabel' => $options['facturare_pers_jur_company_label'],
				'location'      => 'address',
				'type'          => 'text',
				'required' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'const' => 'pers-jur'
									]
								]
							]
						]
					]
				],
				'hidden' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'not' => [
											'const' => 'pers-jur'
										]
									]
								]
							]
						]
					]
				]
			);

			if ( 'no' == $options['facturare_pers_jur_company_required'] ) {
				$args['required'] = false;
			}

			woocommerce_register_additional_checkout_field( $args );

		}

		// // Nr. Reg. Com Field
		if ( 'yes' == $options['facturare_pers_jur_nr_reg_com_vizibility'] ) {

			$args = array(
				'id'            => 'avfacturare/nr_reg_com',
				'label'         => $options['facturare_pers_jur_nr_reg_com_label'],
				'optionalLabel' => $options['facturare_pers_jur_nr_reg_com_label'],
				'location'      => 'address',
				'type'          => 'text',
				'required' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'const' => 'pers-jur'
									]
								]
							]
						]
					]
				],
				'hidden' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'not' => [
											'const' => 'pers-jur'
										]
									]
								]
							]
						]
					]
				]
			);

			if ( 'no' == $options['facturare_pers_jur_nr_reg_com_required'] ) {
				$args['required'] = false;
			}

			woocommerce_register_additional_checkout_field( $args );

		}

		// // Nume Banca Field
		if ( 'yes' == $options['facturare_pers_jur_nume_banca_vizibility'] ) {

			$args = array(
				'id'            => 'avfacturare/nume_banca',
				'label'         => $options['facturare_pers_jur_nume_banca_label'],
				'optionalLabel' => $options['facturare_pers_jur_nume_banca_label'],
				'location'      => 'address',
				'type'          => 'text',
				'required' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'const' => 'pers-jur'
									]
								]
							]
						]
					]
				],
				'hidden' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'not' => [
											'const' => 'pers-jur'
										]
									]
								]
							]
						]
					]
				]
			);

			if ( 'no' == $options['facturare_pers_jur_nume_banca_required'] ) {
				$args['required'] = false;
			}

			woocommerce_register_additional_checkout_field( $args );

		}

		// // IBAN Field
		if ( 'yes' == $options['facturare_pers_jur_iban_vizibility'] ) {

			$args = array(
				'id'            => 'avfacturare/iban',
				'label'         => $options['facturare_pers_jur_iban_label'],
				'optionalLabel' => $options['facturare_pers_jur_iban_label'],
				'location'      => 'address',
				'type'          => 'text',
				'required' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'const' => 'pers-jur'
									]
								]
							]
						]
					]
				],
				'hidden' => [
					'customer' => [
						'properties' => [
							'address' => [
								'properties' => [
									'avfacturare/tip_facturare' => [
										'not' => [
											'const' => 'pers-jur'
										]
									]
								]
							]
						]
					]
				]
			);

			if ( 'no' == $options['facturare_pers_jur_iban_required'] ) {
				$args['required'] = false;
			}

			woocommerce_register_additional_checkout_field( $args );

		}

	}

	public function order_fields( $locale ) {
		$fields = array(
			'avfacturare/tip_facturare' => 1,
			'avfacturare/cnp' => 1,
			'avfacturare/cui' => 2,
			'avfacturare/nr_reg_com' => 2,
			'avfacturare/company' => 3,
			'avfacturare/nume_banca' => 4,
			'avfacturare/iban' => 4,
		);

		foreach ( $locale as $key => $value ) {

			foreach ( $fields as $field => $priority ) {
					$locale[ $key ][ $field ] = [
						'priority' => $priority,
					];
			}

			$locale[ $key ]['country'] = [
				'priority' => 5,
			];

		}

		return $locale;
	}

	public function save_fields( $key, $value, $group, $wc_object ){

		$keys = array( 'avfacturare/tip_facturare', 'avfacturare/cnp', 'avfacturare/cui', 'avfacturare/company', 'avfacturare/nr_reg_com', 'avfacturare/nume_banca', 'avfacturare/iban' );
		if ( in_array( $key, $keys ) ) {
			$options_helper = Facturare_Options_Helper::get_instance();
			$meta = $options_helper->set_block_field( $wc_object, $key, $value, $group );
			$wc_object->delete_meta_data( $key );
		}

		// Map avfacturare/company to billing_company
		if ( 'avfacturare/company' === $key ) {
			if ( $wc_object instanceof WC_Order ) {
				$wc_object->set_billing_company( $value );
			} elseif ( $wc_object instanceof WC_Customer ) {
				$wc_object->set_billing_company( $value );
			}
		}

		return;

	}

	public function delete_meta( $order ){

		$keys = array( 'avfacturare/tip_facturare', 'avfacturare/cnp', 'avfacturare/cui', 'avfacturare/company', 'avfacturare/nr_reg_com', 'avfacturare/nume_banca', 'avfacturare/iban' );

		foreach ( array( '_wc_billing/', '_wc_shipping/' ) as $prefix ) {
			foreach ( $keys as $key ) {
				wc_get_logger()->info(
				    'Delete meta: ' . $prefix . $key,
				    array(
				        'source'        => 'av_facturare_checkout_block',
				    )
				);
				$order->delete_meta_data( $prefix . $key );
			}
		}

		$order->save();
		

	}

	public function enqueue_block_styles() {

		if ( ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
			return;
		}

		$options = get_option( 'av_facturare', array() );
		$options = wp_parse_args( $options, $this->defaults );

		$billing = '.wc-block-checkout__billing-fields .wc-block-components-address-form';
		$shipping = '.wc-block-checkout__shipping-fields .wc-block-components-address-form';

		echo '<style>';

		// Tip Facturare - always full width
		echo $billing . ' .wc-block-components-select-input-avfacturare-tip_facturare,' . $shipping . ' .wc-block-components-select-input-avfacturare-tip_facturare { flex: 0 0 100%; }';

		// CNP - full width when visible
		echo $billing . ' .wc-block-components-address-form__avfacturare-cnp,' . $shipping . ' .wc-block-components-address-form__avfacturare-cnp { flex: 0 0 100%; }';

		// CUI - align with Nr. Reg. Com vertically (specificity 0,5,0 to override WC :first-child+ rule)
		echo '.wc-block-checkout ' . $billing . ' .wc-block-components-text-input.wc-block-components-address-form__avfacturare-cui,' . '.wc-block-checkout ' . $shipping . ' .wc-block-components-text-input.wc-block-components-address-form__avfacturare-cui { margin-top: 12px; }';

		// CUI + Nr. Reg. Com: full width only when the other is not visible
		$cui_visible = 'yes' == $options['facturare_pers_jur_cui_vizibility'];
		$nr_reg_com_visible = 'yes' == $options['facturare_pers_jur_nr_reg_com_vizibility'];

		if ( $cui_visible && ! $nr_reg_com_visible ) {
			echo $billing . ' .wc-block-components-address-form__avfacturare-cui,' . $shipping . ' .wc-block-components-address-form__avfacturare-cui { flex: 0 0 100%; }';
		}
		if ( ! $cui_visible && $nr_reg_com_visible ) {
			echo $billing . ' .wc-block-components-address-form__avfacturare-nr_reg_com,' . $shipping . ' .wc-block-components-address-form__avfacturare-nr_reg_com { flex: 0 0 100%; }';
		}

		// Company - full width
		echo $billing . ' .wc-block-components-address-form__avfacturare-company,' . $shipping . ' .wc-block-components-address-form__avfacturare-company { flex: 0 0 100%; }';

		// Nume Banca + IBAN: full width only when the other is not visible
		$nume_banca_visible = 'yes' == $options['facturare_pers_jur_nume_banca_vizibility'];
		$iban_visible = 'yes' == $options['facturare_pers_jur_iban_vizibility'];

		if ( $nume_banca_visible && ! $iban_visible ) {
			echo $billing . ' .wc-block-components-address-form__avfacturare-nume_banca,' . $shipping . ' .wc-block-components-address-form__avfacturare-nume_banca { flex: 0 0 100%; }';
		}
		if ( ! $nume_banca_visible && $iban_visible ) {
			echo $billing . ' .wc-block-components-address-form__avfacturare-iban,' . $shipping . ' .wc-block-components-address-form__avfacturare-iban { flex: 0 0 100%; }';
		}

		// Country - restore margin-top (WC removes it via .wc-block-components-country-input rule)
		echo '.wc-block-components-form .wc-block-components-checkout-step.wc-block-checkout__billing-fields .wc-block-components-address-form .wc-block-components-country-input,' . '.wc-block-components-form .wc-block-components-checkout-step.wc-block-checkout__shipping-fields .wc-block-components-address-form .wc-block-components-country-input { margin-top: 12px; }';

		echo '</style>';

	}

	public function default_tip_facturare( $value, $group, $wc_object ) {
		$options = get_option( 'av_facturare', array() );
		$options = wp_parse_args( $options, $this->defaults );
		return $options['facturare_default'];
	}

	public function _return_hide(){
		return 'hidden';
	}

}

new Woo_Facturare_Experimental_Checkout();