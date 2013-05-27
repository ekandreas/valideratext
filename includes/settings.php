<?php

/**
 * Class ValideraText_Settings
 */
class ValideraText_Settings{

	function __construct() {
		add_action( 'admin_init', array( &$this, 'admin_init' ) );
		add_action( 'admin_menu', array( &$this, 'wedevs_admin_menu' ) );
		add_action( 'personal_options', array( &$this, 'add_profile_options' ) );
		add_action( 'edit_user_profile_update', array( &$this, 'save_custom_profile_fields' ) );
		add_action( 'personal_options_update', array( &$this, 'save_custom_profile_fields' ) );
	}

	function save_custom_profile_fields( $user_id ) {
		update_user_meta( $user_id, 'valideratext_username', $_POST['valideratext_username'], get_user_meta( $user_id, 'valideratext_username', true ) );
		update_user_meta( $user_id, 'valideratext_password', $_POST['valideratext_password'], get_user_meta( $user_id, 'valideratext_password', true ) );
	}

	function add_profile_options( $profileuser ) {

		$valideratext_username = get_user_meta($profileuser->ID, 'valideratext_username', true);
		$valideratext_password = get_user_meta($profileuser->ID, 'valideratext_password', true);

		$options = get_option( 'valideratext_general' );
		if( empty( $valideratext_username ) ) $valideratext_username = $options['username'];
		if( empty( $valideratext_password ) ) $valideratext_password = $options['userpassword'];

		?>

		<tr>
			<th scope="row">Valideratext, personligt konto</th>
			<td>
				<input type="text" autocomplete="off" placeholder="Användarnamn" name="valideratext_username" value="<?php echo $valideratext_username; ?>" /></label>
				&nbsp;&nbsp;
				<input type="password" autocomplete="off" placeholder="Lösenord" name="valideratext_password" value="<?php echo $valideratext_password; ?>" /><br/>
				<span class="description">Om inte dessa uppgifter anges kommer det globala kontot<br/>under "Inställningar / Valideratext" att användas.</span>
			</td>
		</tr>

		<?php
	}

	function admin_init(){
		//initialize them
		$settings_api = $this->wedevs_admin_init();
		$settings_api->admin_init();
	}
	/**
	 * Register the plugin page
	 */
	function wedevs_admin_menu() {
		add_options_page( 'Valideratext', 'Valideratext', 'administrator', 'settings_api_test', array( &$this, 'wedevs_plugin_page' ) );
	}

	/**
	 * Display the plugin settings options page
	 */
	function wedevs_plugin_page() {

		$settings_api = $this->wedevs_admin_init();

		echo '<div class="wrap">';
		settings_errors();

		$settings_api->show_navigation();
		$settings_api->show_forms();

		echo '</div>';
	}

	/**
	 * Registers settings section and fields
	 */
	function wedevs_admin_init() {

		$sections = array(
			array(
				'id' => 'valideratext_general',
				'title' => __( 'Valideratext', 'wedevs' )
			),
		);

		$fields = array(
			'valideratext_general' => array(
				array(
					'name' => 'apiurl',
					'label' => __( 'API Url', 'wedevs' ),
					'desc' => __( 'Webbadress till API-service.', 'wedevs' ),
					'type' => 'text',
					'default' => 'https://www.valideratext.se/integration/wordpress/html'
				),
				array(
					'name' => 'username',
					'label' => 'Användarnamn',
					'desc' => __( 'Ditt användarnamn för tjänsten.', 'wedevs' ),
					'type' => 'text',
					'default' => ''
				),
				array(
					'name' => 'userpassword',
					'label' => __( 'Lösenord', 'wedevs' ),
					'desc' => __( 'Ditt lösenord för tjänsten.', 'wedevs' ),
					'type' => 'password',
					'default' => ''
				),
				array(
					'name' => 'debug',
					'label' => __( 'Debug?', 'wedevs' ),
					'desc' => __( 'Vid debug så skickas inte formulär till Valideratext, för felsökning.', 'wedevs' ),
					'type' => 'checkbox',
					'default' => ''
				),
			),
		);

		$settings_api = new WeDevs_Settings_API();

		//set sections and fields
		$settings_api->set_sections( $sections );
		$settings_api->set_fields( $fields );

		return $settings_api;
	}


}

new ValideraText_Settings();