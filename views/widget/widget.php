<?php 

	// Exit if accessed directly.
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	$parts = explode('/', $data['url']);
	$origin = 'https://'.$parts[2];
	$issue_url = $parts[count($parts) - 1];
	$pub_url = $parts[count($parts) - 2];
	$thumb = ($data['url']) 
		?  $origin . '/magazine/cover/'.$pub_url.'/'.$issue_url
		: '';

	$target = $origin . '/magazine/wordpress-widget/'.$pub_url.'/'.$issue_url;


?>
	<div class="beacon-promote">
		<h2 class="beacon-title"><?php echo esc_html( $data['headline'] ); ?></h2>
		<div class="thumb">
			<iframe width="70" height="100" src="<?php echo esc_url( $thumb ); ?>" frameborder="0" class="beacon-url"> </iframe>
		</div>
		<h3 class="beacon-headline"><?php echo esc_html( $data['title'] ); ?></h3>
		<form action="<?php echo esc_url( $target ); ?>" method="post">
			<input type="hidden" name="beaconby-url" value="<?php echo esc_url( $data['url'] ); ?>" />
			<input type="email" name="beaconby-email" placeholder="Your Email" />
			<button class="beacon-title" type="submit"><?php echo esc_html( $data['button'] ); ?></button>
		</form>
	</div>

