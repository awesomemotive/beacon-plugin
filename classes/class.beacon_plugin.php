<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		if ( isset( $_REQUEST['page'] ) && strstr( sanitize_key( $_REQUEST['page'] ), 'beaconby') !== -1 ) {
			wp_enqueue_style( 'beaconby_admin', BEACONBY_PLUGIN_URL . 'css/beacon.css', array(), BEACONBY_VERSION );
			wp_enqueue_style( 'beaconby_fontawesome', BEACONBY_PLUGIN_URL .  'css/font-awesome.min.css', array(), BEACONBY_VERSION );

			wp_enqueue_script( 'beaconby_admin', BEACONBY_PLUGIN_URL .  'js/beacon.js', array(), BEACONBY_VERSION, true );
		}
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

		add_submenu_page( 'beaconby', 'Connect', 'Connect', $capability, 'beaconby-connect', $action);

		add_submenu_page( 'beaconby', 'Help', 'Help', $capability, 'beaconby-help', $action);

	}


	public static function get_posts() {

		$from = isset( $_POST['from'] ) ? absint( wp_unslash( $_POST['from'] ) ) : 0;
		$per_page = BEACONBY_PER_PAGE;
		$next = $from + $per_page;
		$data = array();
		$args = array(
			'posts_per_page'   => $per_page,
			'offset'           => $from,
			'orderby'          => 'date',
			'order'            => 'ASC',
			'post_type' => array('page', 'post')
		);
		$posts = get_posts( $args );
		$data['posts'] = array();
		foreach ($posts as $post)
		{
			if (BEACONBY_INCLUDE_TITLES)
			{
				$post->post_content = '<h1>' . esc_html( $post->post_title ) . '</h1>'
					. $post->post_content;
			}

			$post->encoded = base64_encode(serialize($post));
			$tags = wp_get_post_tags( $post->ID );
			$post_tags = array();
			foreach  ($tags as $tag ) {
				$post_tags[] = $tag->name;
			}
			$post->tags = implode( ',', $post_tags );
			$post->main_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
			$cats = get_the_category( $post->ID );
			$post_cats = array();
			foreach  ($cats as $cat ) {
				$post_cats[] = $cat->cat_name;
			}
			$post->cats = implode( ',', $post_cats );
			$data['posts'][] = $post;
		}



		$data['from'] = $from;
		$data['next'] = $next;

		echo json_encode($data);

		wp_die();
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
			? sanitize_key( $_REQUEST['page'] )
			: 'beaconby';

		wp_enqueue_style( 'beaconby_admin', BEACONBY_PLUGIN_URL . 'css/beacon.css', array(), BEACONBY_VERSION );
		wp_enqueue_style( 'beaconby_fontawesome', BEACONBY_PLUGIN_URL .  'css/font-awesome.min.css', array(), BEACONBY_VERSION );

		wp_enqueue_script( 'beaconby_admin', BEACONBY_PLUGIN_URL .  'js/beacon.js', array(), BEACONBY_VERSION, true );

		$self = self::get_instance();
		$self->data = array (
			'has_connected' => self::has_connected(),
		);

		$beacon = isset($_REQUEST['beacon'])
			? sanitize_text_field( wp_unslash( $_REQUEST['beacon'] ) )
			: false;

		if (!$self->data['has_connected'] && $beacon)
		{

			add_option( 'beacon_connected', $beacon );
			update_option( 'beacon_connected', $beacon );
			$self->data['has_connected'] = self::has_connected();
		}

		if ($current_page === 'beaconby-help')
		{
				$current_page = 'beaconby-help';
		}
		else if ( ( $current_page !== 'beaconby' OR $current_page !=='beaconby-help' )
				&&
				$self->data['has_connected'] === false ) {
				$current_page = 'beaconby-connect';
		}

		switch ( $current_page )
		{

			case 'beaconby-create':
				$output = $self->page_create();
			break;

			case 'beaconby-help':
				$output = $self->page_help();
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

		$data['connected'] = isset( $_GET['beacon'] )
			? sanitize_text_field( wp_unslash( $_GET['beacon'] ) )
			: false;

		if ( $self->data['has_connected']  ) {
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
		$only_posts = wp_count_posts('post');
		$only_pages = wp_count_posts('page');
		$total = $only_pages->publish + $only_posts->publish;

		$debug = isset( $_REQUEST['debug'] );
		$exit = isset( $_REQUEST['exit'] );
		$show = isset( $_REQUEST['show'] )
			? sanitize_text_field( wp_unslash( $_REQUEST['show'] ) )
			: false;

		$mem = $this->increaseMemoryLimit();
		list($post_limit, $low_mem_mode) = $this->getPostLimit($mem, $total);

		$posts = array();


		$data = array(
			'debug' => $debug,
			'exit' => $exit,
			'low_mem_mode' => $low_mem_mode ,
			'low_mem_mode_display' => ( $low_mem_mode ) ? 'YES' : 'NO',
			'mem' => $mem,
			'post_limit' => $post_limit,
			'per_page' => BEACONBY_PER_PAGE,
			'total' => $only_pages->publish + $only_posts->publish,
			'posts' => $posts,
			'set_limit' => (boolean) $show
		);
		wp_enqueue_script( 'beaconby_create', BEACONBY_PLUGIN_URL . 'js/beacon-create.js', array(), BEACONBY_VERSION, true );
		return $self->get_view('create', 'Create an eBook', $data);
	}

	/**
	 * renders help page
	 *
	 * @access private
	 * @return string
	 */
	private function page_help() {

		$self = self::get_instance();

		if ( ! isset( $_POST['beacon'] ) ) {
			return $self->get_view( 'help', 'Help' );
		}

		// Verify nonce for CSRF protection
		check_admin_referer( 'beaconby_manual_connect_action', 'beaconby_manual_connect_nonce' );

		$beacon = sanitize_text_field( wp_unslash( $_POST['beacon'] ) );
		update_option( 'beacon_connected', $beacon );
		$self->data['has_connected'] = self::has_connected();
		return $self->get_view( 'connect', 'Connect' );
	}



	/**
	 * renders connect page
	 *
	 * @access private
	 * @return string
	 */
	private function page_connect() {

		$self = self::get_instance();

		if ( isset( $_POST['disconnect'] ) && current_user_can( 'manage_options' ) ) {
			// Verify nonce for CSRF protection
			check_admin_referer( 'beaconby_disconnect_action', 'beaconby_disconnect_nonce' );

			delete_option('beacon_authorized');
			delete_option('widget_beacon_widget');
			delete_option('beacon_promote_options');
			delete_option('beacon_connected');
		}

		return $self->get_view( 'connect', 'Connect' );
	}


	/**
	 * attempts to increase memory in order to
	 * grab more posts
	 *
	 * @access private
	 * @return int memory available in mb
	 */
	private function increaseMemoryLimit()
	{

		try {
			ini_set("memory_limit","256M");
			ini_set('max_execution_time', 240);
			$mem = ini_get("memory_limit")."\n";
			$mem = (int) $mem;
		} catch (Exception $e) {
			$mem = 0; // i.e. php cannot tell us available RAM
		}

		return $mem;

	}


	/**
	 * roughly guesses post limit that WONT
	 * crash wordpress
	 *
	 * @access private
	 * @param int
	 * @param int
	 * @return array
	 */
	private function getPostLimit($mem, $total)
	{

		$low_mem_mode = false;
		$post_limit = 500;


		if ($mem <= 50) {
			$post_limit = 100;
			$low_mem_mode = true;
		} else if ($mem <= 64) {
			$post_limit = 200;
			$low_mem_mode = true;
		} else if ($mem <= 128) {
			$post_limit = 650;
			$low_mem_mode = false;
		} else if ($mem <= 256) {
			$post_limit = 900;
			$low_mem_mode = false;
		} else if ($mem > 256) {
			$post_limit = 2000;
			$low_mem_mode = false;
		}

		if ($total > 1000)
		{
			$low_mem_mode = false;
		}

		return array($post_limit, $low_mem_mode);

	}

}
