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
		add_filter('woocommerce_get_country_locale', array( $this, 'order_fields' ) );
		add_action( 'woocommerce_set_additional_field_value', array( $this, 'save_fields' ), 10, 4 );
		add_action( 'woocommerce_store_api_checkout_order_processed', array( $this, 'delete_meta' ) );
		add_action( 'woocommerce_store_api_checkout_update_customer_from_request', array( $this, 'check_company_visibility' ), 10, 2 );
		add_action( 'woocommerce_store_api_cart_update_customer_from_request', array( $this, 'check_company_visibility' ), 10, 2 );

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
										'const' => 'pers-fiz'
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
			'avfacturare/cui' => 1,
			'company' => 2,
			'avfacturare/nr_reg_com' => 2,
			'avfacturare/nume_banca' => 2,
			'avfacturare/iban' => 2,
		);

		foreach ( $locale as $key => $value ) {

			foreach ( $fields as $field => $priority ) {
					$locale[ $key ][ $field ] = [
						'priority' => $priority,
					];
			}
			
			$locale[ $key ]['country'] = [
				'priority' => 3,
			];

			// $locale[ $key ]['company']['required'] = [
			// 		'customer' => [
			// 			'properties' => [
			// 				'address' => [
			// 					'properties' => [
			// 						'avfacturare/tip_facturare' => [
			// 							'const' => 'pers-jur'
			// 						]
			// 					]
			// 				]
			// 			]
			// 		]
			// 	];


			// $locale[ $key ]['company']['hidden'] = [
			// 		'customer' => [
			// 			'properties' => [
			// 				'address' => [
			// 					'properties' => [
			// 						'avfacturare/tip_facturare' => [
			// 							'const' => 'pers-fiz'
			// 						]
			// 					]
			// 				]
			// 			]
			// 		]
			// 	];

		}

		return $locale;
	}

	public function save_fields( $key, $value, $group, $wc_object ){

		$keys = array( 'avfacturare/tip_facturare', 'avfacturare/cnp', 'avfacturare/cui', 'avfacturare/nr_reg_com', 'avfacturare/nume_banca', 'avfacturare/iban' );
		if ( in_array( $key, $keys ) ) {
			$options_helper = Facturare_Options_Helper::get_instance();
			$meta = $options_helper->set_block_field( $wc_object, $key, $value, $group );
			$wc_object->delete_meta_data( $key );
		}

		return;

	}

	public function delete_meta( $order ){

		$keys = array( 'avfacturare/tip_facturare', 'avfacturare/cnp', 'avfacturare/cui', 'avfacturare/nr_reg_com', 'avfacturare/nume_banca', 'avfacturare/iban' );

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

	// hide company
	public function check_company_visibility( $customer, $request ){

		if ( 'pers-fiz' == $request['additional_fields']['avfacturare/tip_facturare'] ) {
			add_filter( 'default_option_woocommerce_checkout_phone_field', array( $this, '_return_hide' ), 10, 1 );
		}else{
			add_filter( 'default_option_woocommerce_checkout_phone_field', array( $this, '_return_required' ), 10, 1 );
		}

	}

	public function _return_hide(){
		return 'hidden';
	}

	public function _return_required(){
		return 'required';
	}

}

new Woo_Facturare_Experimental_Checkout();