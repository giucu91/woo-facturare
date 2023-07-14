<?php

class ROAPI_CUI {
	
	function __construct() {
		
		add_filter( 'woocommerce_get_sections_facturare', array( $this, 'add_sections' ) );
		add_filter( 'woocommerce_get_settings_facturare', array( $this, 'add_settings' ), 10, 2 );
		add_action( 'admin_notices', array( $this, 'show_notice' ) );
		add_action( 'admin_init', array( $this, 'generate_acc' ) );

	}

	public function add_sections( $sections ) {
		$sections['roapi'] = esc_html__( 'ROAPI', 'woo-facturare' );
		return $sections;
	}

	public function add_settings( $settings, $current_section ){

		if ( 'roapi' != $current_section ) {
			return $settings;
		}

		$settings = array(
			array(
				'title' => esc_html__( 'Setari ROAPI', 'woo-facturare' ),
				'type'  => 'title',
				'id'    => 'roapi_section',
			),
			array(
                'name'    => esc_html__( 'ROAPI Token', 'woo-facturare' ),
                'type'    => 'text',
                'id'      => 'roapi_token'
            ),
            array(
				'type' => 'sectionend',
				'id'   => 'roapi_section_end',
			),
		);

		return $settings;

	}

	public function show_notice(){

		$token  = get_option( 'roapi_token', false );
		$dismis = get_option( 'roapi_dismiss', false );

		if ( $token ) {
			return;
		}

		if ( $dismis ) {
			return;
		}
		?>


		<div class="notice notice-success is-dismissible">
             <p>Salut 👋,</p>
             <p>Am lansat <a href="https://roapi.ro" target="_blank">roapi.ro</a> care ofera avantajul de a autocompleta nume firma/adresa/judet/oras in functie de CUI-ul clientului. Primele 100 ce autocompletari sunt grauite tot ce trebuie e sa iti faci cont.</p>
             <form method="POST">
             	<input type="text" name="roapi_email" value="<?php echo esc_attr( get_bloginfo('admin_email') ) ?>" style="width:300px;">
             	<?php wp_nonce_field( 'roapicreateacc', 'roapi_nonce' ); ?>
             	<input type="submit" name="roapi_create" value="Creaza cont" class="button">
             </form><br>
		</div>

		<?php

	}

	public function generate_acc(){

		if ( ! isset( $_POST['roapi_create'] ) )  {
		   return;
		}

		if ( ! isset( $_POST['roapi_nonce'] ) ) {
		   return;
		}

		if ( ! wp_verify_nonce( $_POST['roapi_nonce'], 'roapicreateacc' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
		    return;
		}

		$email = sanitize_email( $_POST['roapi_email'] );

		$args = array(
			'email' => $email,
			'name'  => '',
			'url'   => site_url()
		);

		$response = wp_remote_post( 'https://roapi.ro/wp-json/roapi/getfirsttoken', array( 'body' => $args ) );
		$body = wp_remote_retrieve_body( $response );
		$body = json_decode( $body, true );

		if ( isset( $body['success'] ) && $body['success'] ) {
			$token = sanitize_text_field( $body['data']['token'] );
			update_option( 'roapi_token', $token, false );
		}

	}

}

new ROAPI_CUI();