<?php
/**
 * WooCommerce Facturare Settings
 *
 * @package WooCommerce/Admin
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'WC_Settings_Facturare', false ) ) {
	return new WC_Settings_Facturare();
}

/**
 * WC_Settings_Facturare.
 */
class WC_Settings_Facturare extends WC_Settings_Page {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->id    = 'facturare';
		$this->label = __( 'Facturare', 'woocommerce' );

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''         => __( 'General', 'woocommerce' ),
			'pers-fiz' => __( 'Persoana Fizica', 'woocommerce' ),
			'pers-jur' => __( 'Persoana Juridica', 'woocommerce' ),
		);

		return apply_filters( 'woocommerce_get_sections_' . $this->id, $sections );
	}

	/**
	 * Output the settings.
	 */
	public function output() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::output_fields( $settings );
	}

	/**
	 * Save settings.
	 */
	public function save() {
		global $current_section;

		$settings = $this->get_settings( $current_section );
		WC_Admin_Settings::save_fields( $settings );

		if ( $current_section ) {
			do_action( 'woocommerce_update_options_' . $this->id . '_' . $current_section );
		}
	}

	/**
	 * Get settings array.
	 *
	 * @param string $current_section Current section name.
	 * @return array
	 */
	public function get_settings( $current_section = '' ) {

		if ( 'pers-fiz' === $current_section ) {
			$settings = array(
				array(
					'title' => __( 'Setari Persoane Fizice', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_fiz_start',
				),
				array(
	                'name'    => __( 'Label Persoana Fizica', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Persoana Fizica',
	                'id'      => 'av_facturare[facturare_pers_fiz_label]'
	            ),
	            array(
					'type' => 'sectionend',
					'id'   => 'facturare_pers_fiz_end',
				),

				array(
					'title' => __( 'Camp CNP', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_fiz_cnp_start',
				),
				array(
	                'name'    => __( 'Label', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'CNP',
	                'id'      => 'av_facturare[facturare_pers_fiz_cnp_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Introduceti Codul numeric personal',
	                'id'      => 'av_facturare[facturare_pers_fiz_cnp_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woocommerce' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_fiz_cnp_vizibility]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woocommerce' ),
					'desc'    => __( 'Da, campul <strong>CNP</strong> este Obligatoriu', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_fiz_cnp_required]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'textarea',
	                'default' => 'Datorita legislatiei in vigoare trebuie sa completati campul CNP',
	                'id'      => 'av_facturare[facturare_pers_fiz_cnp_error]'
	            ),
				array(
					'type' => 'sectionend',
					'id'   => 'facturare_pers_fiz_cnp_end',
				),
			);

		} elseif ( 'pers-jur' === $current_section ) {
			$settings = array(
				array(
					'title' => __( 'Setari Persoane Juridice', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_start',
				),
				array(
	                'name'    => __( 'Label Persoana Juridica', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Persoana Juridica',
	                'id'      => 'av_facturare[facturare_pers_jur_label]'
	            ),
		        array(
					'type' => 'sectionend',
					'id'   => 'facturare_pers_jur_end',
				),

		        // Nume Firma
		        array(
					'title' => __( 'Camp Nume Firma', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_company_start',
				),
				array(
	                'name'    => __( 'Label', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Nume Firma',
	                'id'      => 'av_facturare[facturare_pers_jur_company_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Introduceti numele firmei dumneavoastra',
	                'id'      => 'av_facturare[facturare_pers_jur_company_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woocommerce' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_company_vizibility]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woocommerce' ),
					'desc'    => __( 'Da, campul <strong>Nume Firma</strong> este Obligatoriu', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_company_required]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'textarea',
	                'default' => 'Pentru a va putea emite factura avem nevoie de numele firmei dumneavoastra',
	                'id'      => 'av_facturare[facturare_pers_jur_company_error]'
	            ),
				array(
					'type' => 'sectionend',
					'id'   => 'facturare_pers_jur_company_end',
				),

		        // CUI
		        array(
					'title' => __( 'Camp CUI', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_cui_start',
				),
				array(
	                'name'    => __( 'Label', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'CUI',
	                'id'      => 'av_facturare[facturare_pers_jur_cui_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Introduceti Codul Unic de Inregistrare',
	                'id'      => 'av_facturare[facturare_pers_jur_cui_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woocommerce' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_cui_vizibility]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woocommerce' ),
					'desc'    => __( 'Da, campul <strong>CUI</strong> este Obligatoriu', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_cui_required]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'textarea',
	                'default' => 'Pentru a va putea emite factura avem nevoie de CUI-ul firmei dumneavoastra',
	                'id'      => 'av_facturare[facturare_pers_jur_cui_error]'
	            ),
				array(
					'type' => 'sectionend',
					'id'   => 'facturare_pers_jur_cui_end',
				),

				// Nr. Reg. Com.
				array(
					'title' => __( 'Camp Nr. Reg. Com.', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_nr_reg_com_start',
				),
				array(
	                'name'    => __( 'Label', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Nr. Reg. Com',
	                'id'      => 'av_facturare[facturare_pers_jur_nr_reg_com_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'J20/20/20.02.2020',
	                'id'      => 'av_facturare[facturare_pers_jur_nr_reg_com_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woocommerce' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_nr_reg_com_vizibility]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woocommerce' ),
					'desc'    => __( 'Da, campul <strong>Nr. Reg. Com</strong> este Obligatoriu', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_nr_reg_com_required]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'textarea',
	                'default' => 'Pentru a va putea emite factura avem nevoie de numarul de ordine in registrul comertului',
	                'id'      => 'av_facturare[facturare_pers_jur_nr_reg_com_error]'
	            ),
				array(
					'type' => 'sectionend',
					'id'   => 'facturare_pers_jur_nr_reg_com_end',
				),

				// Nume Banca
				array(
					'title' => __( 'Camp Nume Banca', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_nume_banca_start',
				),
				array(
	                'name'    => __( 'Label', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Nume Banca',
	                'id'      => 'av_facturare[facturare_pers_jur_nume_banca_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Numele bancii cu care lucrati',
	                'id'      => 'av_facturare[facturare_pers_jur_nume_banca_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woocommerce' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_nume_banca_vizibility]',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woocommerce' ),
					'desc'    => __( 'Da, campul <strong>Nume Banca</strong> este Obligatoriu', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_nume_banca_required]',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'textarea',
	                'default' => 'Pentru a va putea emite factura avem nevoie de numele bancii cu care lucrati',
	                'id'      => 'av_facturare[facturare_pers_jur_nume_banca_error]',
	            ),
				array(
					'type' => 'sectionend',
					'id'   => 'facturare_pers_jur_nume_banca_end',
				),

				// IBAN
				array(
					'title' => __( 'Camp IBAN', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_iban_start',
				),
				array(
	                'name'    => __( 'Label', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'IBAN',
	                'id'      => 'av_facturare[facturare_pers_jur_iban_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Numarul contului IBAN',
	                'id'      => 'av_facturare[facturare_pers_jur_iban_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woocommerce' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_iban_vizibility]',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woocommerce' ),
					'desc'    => __( 'Da, campul <strong>IBAN</strong> este Obligatoriu', 'woocommerce' ),
					'id'      => 'av_facturare[facturare_pers_jur_iban_required]',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'textarea',
	                'default' => 'Pentru a va putea emite factura avem nevoie de numarul contului',
	                'id'      => 'av_facturare[facturare_pers_jur_iban_error]'
	            ),
				array(
					'type' => 'sectionend',
					'id'   => 'facturare_pers_jur_iban_end',
				),

			);

		} else {
			$settings = array(
				array(
					'title' => __( 'Setari Generale', 'woocommerce' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_general_start',
				),
				array(
	                'name' => __( 'Tip', 'woocommerce-settings-tab-demo' ),
	                'type' => 'select',
	                'options' => array(
	                	'radio'  => 'Butoane radio',
	                	'select' => 'Select',
	                ),
	                'desc' => __( '<p>Cum va fi afisata optiunea de a alaege intre persoana fizica sau juridica</p>', 'woocommerce-settings-tab-demo' ),
	                'id'   => 'av_facturare[facturare_output]'
	            ),
	            array(
	                'name' => __( 'Optiune implicita', 'woocommerce-settings-tab-demo' ),
	                'type' => 'select',
	                'options' => array(
	                	'pers_fiz' => 'Persoana Fizica',
	                	'pers_jur' => 'Persoana Juridica',
	                ),
	                'desc' => __( '<p>Optiunea care va fi selectata implicit pe pagina de checkout</p>', 'woocommerce-settings-tab-demo' ),
	                'id'   => 'av_facturare[facturare_default]'
	            ),
	            array(
	                'name'    => __( 'Label', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => 'Tip Facturare',
	                'id'      => 'av_facturare[facturare_label]'
	            ),
	            array(
	                'name'    => __( 'Licenta', 'woocommerce-settings-tab-demo' ),
	                'type'    => 'text',
	                'default' => '',
	                'id'      => 'av_licenta'
	            ),
	            array(
					'type' => 'sectionend',
					'id'   => 'facturare_general_end',
				),
			);

		}

		return apply_filters( 'woocommerce_get_settings_' . $this->id, $settings, $current_section );
	}
}

return new WC_Settings_Facturare();