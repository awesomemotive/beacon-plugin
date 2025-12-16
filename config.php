<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

//Prevent directly browsing to the file
if ( function_exists( 'plugin_dir_url' ) ) {

	define( 'BEACONBY_VERSION', '1.5.8' );
	define( 'BEACONBY_HOMEPAGE', 'https://beacon.by/' );
	define( 'BEACONBY_HELPLINK', 'https://beacon.by/wordpress' );
	define( 'BEACONBY_PLUGIN_URL', plugin_dir_url(__FILE__) );
	define( 'BEACONBY_PLUGIN_PATH', plugin_dir_path(__FILE__) );
	define( 'BEACONBY_SITE_URL', get_site_url() );
	define( 'BEACONBY_INCLUDE_TITLES', true );
	define( 'BEACONBY_PER_PAGE', 50 );

	if ( ! defined( 'BEACONBY_CREATE_TARGET' ) ) {
		define('BEACONBY_CREATE_TARGET', 'https://beacon.by' );
	}
}



