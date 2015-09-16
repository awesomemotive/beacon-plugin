<?php defined( 'ABSPATH' ) or die( '' );

/**
 * Beacon plugin class
 *
 * handles all dashboard aspects of plugin
 *
 * @package Beacon Wordpress plugin
 * @author Beacon
**/
class Beacon_plugin {

	/**
	 * instance of this class
	 *
	 * @var Object / null
	 */
	private static $instance = null;


	private function __construct() {
	}


	/**
	 * returns instance of class or creates one if not exists
	 *
	 * @access public
	 * @param string
	 * @return Object
	 */
	public static function get_instance() {
		
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}



	/**
	 * loads styles and scripts required for plugin to run
	 *
	 * @access public
	 * @return void
	 */
	public static function init() {

		wp_enqueue_style( 'beaconby_admin', 
						BEACONBY_PLUGIN_URL . 'css/beacon.css' );

		wp_enqueue_style( 'beaconby_widget', 
					 BEACONBY_PLUGIN_URL . 'css/beacon-widget.css' );

		wp_enqueue_style( 'beaconby_fontawesome',
					BEACONBY_PLUGIN_URL .  'css/font-awesome.min.css');

		wp_enqueue_script( 'beaconby_admin', 
					BEACONBY_PLUGIN_URL .  'js/beacon.js' );
	}


	/**
	 * loads an admin wrapped with header and footer
	 *
	 * @access public
	 * @param string
	 * @param string
	 * @param array
	 * @return string
	 */
	public function get_view($view, $title = false, $data = array()) {
		
		$self = self::get_instance();
		$data= array_merge($self->data, $data);


		ob_start();
		include( BEACONBY_PLUGIN_PATH . 'views/dashboard/header.tpl.php' );
		include( BEACONBY_PLUGIN_PATH . 'views/dashboard/'.$view.'.php' );
		include( BEACONBY_PLUGIN_PATH . 'views/dashboard/footer.tpl.php' );
		$output = ob_get_contents();
		ob_end_clean();


		return $output;

	}


	/**
	 * inits dashboard menu
	 *
	 * @access public
	 * @return void
	 */
	public static function menu() {

		$capability = 'manage_options';
		$action = array('Beacon_plugin', 'router');

		add_menu_page( 'Beacon eBook plugin', 'Beacon', $capability, 'beaconby', $action, BEACONBY_PLUGIN_URL . 'i/beacon.png' );

		add_submenu_page( 'beaconby', 'Create', 'Create', $capability, 'beaconby-create', $action);

		add_submenu_page( 'beaconby', 'Promote', 'Promote', $capability, 'beaconby-promote', $action);

		add_submenu_page( 'beaconby', 'Embed', 'Embed', $capability, 'beaconby-embed', $action);

		add_submenu_page( 'beaconby', 'Connect', 'Connect', $capability, 'beaconby-connect', $action);

		add_submenu_page( 'beaconby', 'Help', 'Help', $capability, 'beaconby-help', $action);

	}


	public static function plugin_activation() {

	}


	/**
	 * removes all traces of plugin
	 *
	 * @access public
	 * @return void
	 */
	public static function plugin_deactivation() {

	}


	/**
	 * checks whether there is positive entry in wp_options
	 * for authorization
	 *
	 * @access public
	 * @return boolean
	 */
	public static function has_authorized() {
		return (bool) get_option('beacon_authorized');
	}



	/**
	 * checks whether there is positive entry in wp_options
	 * for authorization
	 *
	 * @access public
	 * @return boolean
	 */
	public static function has_connected() {
		return get_option('beacon_connected');
	}


	/**
	 * router for all plugin actions
	 *
	 * @access public
	 * @param string
	 * @return void
	 */
	public static function router() {

		$current_page = isset($_REQUEST['page']) 
			? esc_html($_REQUEST['page']) 
			: 'beaconby';


		$self = self::get_instance();
		$self->data = array ( 
			'has_connected' => self::has_connected(),
		);

		if ( $current_page !== 'beaconby' && 
				$self->data['has_connected'] === false ) {
				$current_page = 'beaconby-connect';
		}

		switch ( $current_page )
		{

			case 'beaconby-create':
				$output = $self->page_create();
			break;

			case 'beaconby-embed':
				$output = $self->page_embed();
			break;

			case 'beaconby-help':
				$output = $self->page_help();
			break;

			case 'beaconby-promote':
				$output = $self->page_promote();
			break;

			case 'beaconby-connect':
				$output = $self->page_connect();
			break;

			case 'beaconby':
				$output = $self->page_main();
			break;

		}

		echo $output;

	}


	/**
	 * renders main plugin landing page
	 *
	 * @access private
	 * @return string
	 */
	private function page_main() {

		$self = self::get_instance();

		$data = array();

		$data['connected'] = array_key_exists ( 'beacon', $_GET )
			? esc_html( $_GET['beacon'] ) : false;

		if ( $self->data['has_connected']  ) {
			return $self->get_view( 'main', 'Welcome', $data );
		}
		else if ( !$self->data['has_connected'] && 
							$data['connected']  ) {
			add_option( 'beacon_connected', $data['connected'] );
			return $self->get_view( 'main', 'Welcome', $data );
		}
		else {
			return $self->get_view( 'connect', 'Connect', $data );
		}

	}


	/**
	 * renders create plugin page
	 *
	 * @access private
	 * @return string
	 */
	private function page_create() {

		$self = self::get_instance();
		wp_enqueue_script( 'beaconby_create', 
				BEACONBY_PLUGIN_URL . 'js/beacon-create.js' );
		return $self->get_view('create', 'Create an eBook');
	}


	/**
	 * renders embed ebook page
	 *
	 * @access private
	 * @return string
	 */
	private function page_embed() {

		$self = self::get_instance();
		wp_enqueue_script( 'beaconby_embed', 
			BEACONBY_PLUGIN_URL . 'js/beacon-embed.js' );
		return $self->get_view( 'embed', 'Embed an eBook' );
	}


	/**
	 * renders help page
	 *
	 * @access private
	 * @return string
	 */
	private function page_help() {

		$self = self::get_instance();
		return $self->get_view( 'help', 'Help' );
	}



	/**
	 * renders connect page
	 *
	 * @access private
	 * @return string
	 */
	private function page_connect() {

		$self = self::get_instance();
		return $self->get_view( 'connect', 'Connect' );
	}


	/**
	 * renders promote ebook page and saves 
	 * text fields for widget
	 *
	 * @access private
	 * @return string
	 */
	private function page_promote() {

		$self = self::get_instance();

		wp_enqueue_script( 'beaconby_promote', 
			BEACONBY_PLUGIN_URL .  'js/beacon-promote.js' );

		$data = array();
		if ( !empty($_POST) ) {

			$post = array();
			foreach ( $_POST as $k => $v ) {
				$k = esc_html( $k );
				$v = esc_html( $v );
				$post[$k] = $v;	
			}

			$serialized = serialize( $post) ;

			try {
				update_option( 'beacon_promote_options', $serialized );
			} catch ( Exception $e ) {

			}
			$data = get_option( 'beacon_promote_options' );
			$data = unserialize($data);
			$data['saved'] = true;

		}
		else {

			$data = get_option( 'beacon_promote_options' );
			$data = unserialize($data);
			if ( !$data ) {
				$data = array(
					'url' => '',
					'headline' => 'Headline',
					'title' => 'Short blurb goes here',
					'button' => 'Access eBook Now!',
				);
			}
		}
		
		return $self->get_view( 'promote', 'Promote an eBook', $data );
	}


}

