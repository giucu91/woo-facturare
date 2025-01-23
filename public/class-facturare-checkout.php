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

	}

	public function register_fields(){

		$options = get_option( 'av_facturare', array() );
		$options = wp_parse_args( $options, $this->defaults );

		// register tip facturare
		woocommerce_register_additional_checkout_field(
			array(
				'id'            => 'avfacturare/tip-facturare',
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
				// 'attributes'    => array(
				// 	'autocomplete'     => 'tip-facturare',
				// 	'aria-describedby' => 'some-element',
				// 	'aria-label'       => 'custom aria label',
				// 	'pattern'          => '[A-Z0-9]{5}', // A 5-character string of capital letters and numbers.
				// 	'title'            => 'Title to show on hover',
				// 	'data-custom'      => 'custom data',
				// ),
			),
		);



		// woocommerce_register_additional_checkout_field(
		// 	array(
		// 		'id'            => 'namespace/gov-id',
		// 		'label'         => 'Government ID',
		// 		'optionalLabel' => 'Government ID (optional)',
		// 		'location'      => 'address',
		// 		'required'      => true,
		// 		'attributes'    => array(
		// 			'autocomplete'     => 'government-id',
		// 			'aria-describedby' => 'some-element',
		// 			'aria-label'       => 'custom aria label',
		// 			'pattern'          => '[A-Z0-9]{5}', // A 5-character string of capital letters and numbers.
		// 			'title'            => 'Title to show on hover',
		// 			'data-custom'      => 'custom data',
		// 		),
		// 	),
		// );

	}


}

new Woo_Facturare_Experimental_Checkout();