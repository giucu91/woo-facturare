<?php

/**
 * 
 */
class Facturare_Options_Helper {

	private $options = array();
	private $metas = array(
		'customers' => array(),
		'orders' => array()
	);

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

	/**
	 * Setează un câmp de facturare personalizat într-un meta consolidat pentru un obiect WC_Order sau WC_Customer.
	 * 
	 * Cheile primite din checkout sunt de forma 'avfacturare/xxx'. Această metodă extrage 'xxx'
	 * și o salvează în meta-ul `av_facturare` sau `av_shipping_facturare`, în funcție de grup.
	 *
	 * @param WC_Order|WC_Customer $object Obiectul WooCommerce pentru care salvăm datele (comandă sau client).
	 * @param string $key Cheia completă de la checkout (ex: 'avfacturare/cnp').
	 * @param string $value Valoarea câmpului respectiv.
	 * @param string $group Grupul de date: 'billing' sau 'shipping'. Implicit: 'billing'.
	 *
	 * @return array Array-ul actualizat de date pentru obiectul și grupul specificat.
	 */
	public function set_block_field( $object, $key, $value, $group = 'billing' ) {
		$defaults = array(
			'tip_facturare' => '',
			'cnp'           => '',
			'cui'           => '',
			'nr_reg_com'    => '',
			'nume_banca'    => '',
			'iban'          => '',
		);

		$type = null;
		$id = null;

		// Extrage cheia internă după slash
		$key_parts = explode( '/', $key );
		$internal_key = end( $key_parts );

		av_facturare_logger(
		    'Start set_block_field',
		    array(
		        'source'        => 'woo-facturare',
		    )
		);

		av_facturare_logger(
		    'Key: ' . $key . ' PKey: ' . $internal_key . ' Value: ' . $value . ' Group: ' . $group,
		    array(
		        'source' => 'woo-facturare',
		    )
		);

		if ( ! array_key_exists( $internal_key, $defaults ) ) {
			// Dacă cheia nu e validă, nu o procesăm
			av_facturare_logger(
			    'Cheia nu e valida',
			    array(
			        'source' => 'woo-facturare',
			    )
			);
			return $defaults;
		}

		// Determină tipul obiectului și ID-ul
		if ( $object instanceof WC_Order ) {
			$type = 'orders';
			$id = $object->get_id();
		} elseif ( $object instanceof WC_Customer ) {
			$type = 'customers';
			$id = $object->get_id();
		}

		av_facturare_logger(
		    'Type: ' . $type . ' ID: ' . $id . ' Key: ' . $key . ' PKey: ' . $internal_key . ' Value: ' . $value . ' Group: ' . $group,
		    array(
		        'source' => 'woo-facturare',
		    )
		);

		if ( ! $type || ! $id ) {
			av_facturare_logger(
			    'Nu avem type sau ID',
			    array(
			        'source' => 'woo-facturare',
			    )
			);
			return $defaults;
		}

		// Inițializează structura dacă nu există
		if ( ! isset( $this->metas[ $type ][ $group ][ $id ] ) ) {
			$this->metas[ $type ][ $group ][ $id ] = $defaults;
		}

		// Setează valoarea în array
		$this->metas[ $type ][ $group ][ $id ][ $internal_key ] = $value;

		// Definește cheia meta
		$meta_key = $group === 'shipping' ? 'av_shipping_facturare' : 'av_facturare';
		$meta_value = $this->metas[ $type ][ $group ][ $id ];

		av_facturare_logger(
		    'Array construit: ' . print_r( $meta_value, true ),
		    array(
		        'source' => 'woo-facturare',
		    )
		);

		// Salvează în meta
		if ( $object instanceof WC_Order || $object instanceof WC_Customer ) {
			$object->update_meta_data( $meta_key, $meta_value );
		}

	}

}