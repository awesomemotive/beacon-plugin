<div class="beacon-by-admin-wrap">

	<h1 class="title">
	Beacon Plugin
		<?php 
			if (isset($title)) 
			{
				echo '&raquo; '. $title;
			}
		?>
	</h1>

	<hr size="1" />

	<div class="prompt-login">
		<div class="info">
			<p>To use this feature you must be logged into your <a href="http://<?php echo BEACONBY_CREATE_TARGET; ?>/login" target="_blank">Beacon account</a></p>
		</div>
	</div>

	<div class="requires-login">
	


