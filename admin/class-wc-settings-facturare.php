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
		$this->label = __( 'Facturare', 'woo-facturare' );

		parent::__construct();
	}

	/**
	 * Get sections.
	 *
	 * @return array
	 */
	public function get_sections() {
		$sections = array(
			''         => __( 'General', 'woo-facturare' ),
			'pers-fiz' => __( 'Persoana Fizica', 'woo-facturare' ),
			'pers-jur' => __( 'Persoana Juridica', 'woo-facturare' ),
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
					'title' => __( 'Setari Persoane Fizice', 'woo-facturare' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_fiz_start',
				),
				array(
	                'name'    => __( 'Label Persoana Fizica', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Persoana Fizica',
	                'id'      => 'av_facturare[facturare_pers_fiz_label]'
	            ),
	            array(
					'type' => 'sectionend',
					'id'   => 'facturare_pers_fiz_end',
				),

				array(
					'title' => __( 'Camp CNP', 'woo-facturare' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_fiz_cnp_start',
				),
				array(
	                'name'    => __( 'Label', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'CNP',
	                'id'      => 'av_facturare[facturare_pers_fiz_cnp_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Introduceti Codul numeric personal',
	                'id'      => 'av_facturare[facturare_pers_fiz_cnp_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woo-facturare' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_fiz_cnp_vizibility]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woo-facturare' ),
					'desc'    => __( 'Da, campul <strong>CNP</strong> este Obligatoriu', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_fiz_cnp_required]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woo-facturare' ),
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
					'title' => __( 'Setari Persoane Juridice', 'woo-facturare' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_start',
				),
				array(
	                'name'    => __( 'Label Persoana Juridica', 'woo-facturare' ),
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
					'title' => __( 'Camp Nume Firma', 'woo-facturare' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_company_start',
				),
				array(
	                'name'    => __( 'Label', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Nume Firma',
	                'id'      => 'av_facturare[facturare_pers_jur_company_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Introduceti numele firmei dumneavoastra',
	                'id'      => 'av_facturare[facturare_pers_jur_company_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woo-facturare' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_company_vizibility]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woo-facturare' ),
					'desc'    => __( 'Da, campul <strong>Nume Firma</strong> este Obligatoriu', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_company_required]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woo-facturare' ),
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
					'title' => __( 'Camp CUI', 'woo-facturare' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_cui_start',
				),
				array(
	                'name'    => __( 'Label', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'CUI',
	                'id'      => 'av_facturare[facturare_pers_jur_cui_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Introduceti Codul Unic de Inregistrare',
	                'id'      => 'av_facturare[facturare_pers_jur_cui_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woo-facturare' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_cui_vizibility]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woo-facturare' ),
					'desc'    => __( 'Da, campul <strong>CUI</strong> este Obligatoriu', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_cui_required]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woo-facturare' ),
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
					'title' => __( 'Camp Nr. Reg. Com.', 'woo-facturare' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_nr_reg_com_start',
				),
				array(
	                'name'    => __( 'Label', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Nr. Reg. Com',
	                'id'      => 'av_facturare[facturare_pers_jur_nr_reg_com_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'J20/20/20.02.2020',
	                'id'      => 'av_facturare[facturare_pers_jur_nr_reg_com_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woo-facturare' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_nr_reg_com_vizibility]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woo-facturare' ),
					'desc'    => __( 'Da, campul <strong>Nr. Reg. Com</strong> este Obligatoriu', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_nr_reg_com_required]',
					'default' => 'yes',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woo-facturare' ),
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
					'title' => __( 'Camp Nume Banca', 'woo-facturare' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_nume_banca_start',
				),
				array(
	                'name'    => __( 'Label', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Nume Banca',
	                'id'      => 'av_facturare[facturare_pers_jur_nume_banca_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Numele bancii cu care lucrati',
	                'id'      => 'av_facturare[facturare_pers_jur_nume_banca_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woo-facturare' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_nume_banca_vizibility]',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woo-facturare' ),
					'desc'    => __( 'Da, campul <strong>Nume Banca</strong> este Obligatoriu', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_nume_banca_required]',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woo-facturare' ),
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
					'title' => __( 'Camp IBAN', 'woo-facturare' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_pers_jur_iban_start',
				),
				array(
	                'name'    => __( 'Label', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'IBAN',
	                'id'      => 'av_facturare[facturare_pers_jur_iban_label]'
	            ),
	            array(
	                'name'    => __( 'Placeholder', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Numarul contului IBAN',
	                'id'      => 'av_facturare[facturare_pers_jur_iban_placeholder]'
	            ),
				array(
					'title'   => __( 'Vizibilitate', 'woo-facturare' ),
					'desc'    => __( 'Arata acest camp pe pagina de checkout', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_iban_vizibility]',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
					'title'   => __( 'Obligatoriu', 'woo-facturare' ),
					'desc'    => __( 'Da, campul <strong>IBAN</strong> este Obligatoriu', 'woo-facturare' ),
					'id'      => 'av_facturare[facturare_pers_jur_iban_required]',
					'default' => 'no',
					'type'    => 'checkbox',
				),
				array(
	                'name'    => __( 'Mesaj Eroare', 'woo-facturare' ),
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
					'title' => __( 'Setari Generale', 'woo-facturare' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'facturare_general_start',
				),
				array(
	                'name' => __( 'Tip', 'woo-facturare' ),
	                'type' => 'select',
	                'options' => array(
	                	'radio'  => 'Butoane radio',
	                	'select' => 'Select',
	                ),
	                'default' => 'select',
	                'desc' => __( '<p>Cum va fi afisata optiunea de a alaege intre persoana fizica sau juridica</p>', 'woo-facturare' ),
	                'id'   => 'av_facturare[facturare_output]'
	            ),
	            array(
	                'name' => __( 'Optiune implicita', 'woo-facturare' ),
	                'type' => 'select',
	                'options' => array(
	                	'pers-fiz' => 'Persoana Fizica',
	                	'pers-jur' => 'Persoana Juridica',
	                ),
	                'desc' => __( '<p>Optiunea care va fi selectata implicit pe pagina de checkout</p>', 'woo-facturare' ),
	                'id'   => 'av_facturare[facturare_default]'
	            ),
	            array(
	                'name'    => __( 'Label', 'woo-facturare' ),
	                'type'    => 'text',
	                'default' => 'Tip Facturare',
	                'id'      => 'av_facturare[facturare_label]'
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