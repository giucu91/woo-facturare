<?php

/**
 * 
 */
class Facturare_Options_Helper {

	private $options = array();

	public static function get_instance() {
		static $inst;
		if ( ! $inst ) {
			$inst = new Facturare_Options_Helper();
		}
		return $inst;
	}

	public function get_keys(){
		return apply_filters( 'facturare_options_keys', array( '_av_facturare_cnp', '_av_facturare_nr_reg_com', '_av_facturare_cui', '_av_facturare_nume_banca', '_av_facturare_iban', '_billing_facturare_cnp', '_billing_facturare_cui', '_billing_facturare_nr_reg_com', '_billing_facturare_nume_banca', '_billing_facturare_iban' ) );
	}

	private function get_order_data( $order_id ){
		$order = wc_get_order( $order_id );
		$this->options[ $order_id ] = $order->get_meta( 'av_facturare', true );
	}

	public function get_cnp( $order_id ){

		if ( ! isset( $this->options[ $order_id ] ) ) {
			$this->get_order_data( $order_id );
		}

		return isset( $this->options[ $order_id ]['cnp'] ) ? $this->options[ $order_id ]['cnp'] : '-';

	}

	public function get_nr_reg_com( $order_id ){

		if ( ! isset( $this->options[ $order_id ] ) ) {
			$this->get_order_data( $order_id );
		}

		return isset( $this->options[ $order_id ]['nr_reg_com'] ) ? $this->options[ $order_id ]['nr_reg_com'] : '-';
		
	}

	public function get_cui( $order_id ){

		if ( ! isset( $this->options[ $order_id ] ) ) {
			$this->get_order_data( $order_id );
		}

		return isset( $this->options[ $order_id ]['cui'] ) ? $this->options[ $order_id ]['cui'] : '-';
		
	}

	public function get_nume_banca( $order_id ){

		if ( ! isset( $this->options[ $order_id ] ) ) {
			$this->get_order_data( $order_id );
		}

		return isset( $this->options[ $order_id ]['nume_banca'] ) ? $this->options[ $order_id ]['nume_banca'] : '-';
		
	}

	public function get_iban( $order_id ){

		if ( ! isset( $this->options[ $order_id ] ) ) {
			$this->get_order_data( $order_id );
		}

		return isset( $this->options[ $order_id ]['iban'] ) ? $this->options[ $order_id ]['iban'] : '-';
		
	}

	public function get_tip( $order_id ){
		if ( ! isset( $this->options[ $order_id ] ) ) {
			$this->get_order_data( $order_id );
		}

		return isset( $this->options[ $order_id ]['tip_facturare'] ) ? $this->options[ $order_id ]['tip_facturare'] : '-';
		
	}
}