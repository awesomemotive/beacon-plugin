<?php if ($data['has_connected']): ?>
<div class="info beacon-connect-info">
	<i class="fa fa-info-circle"></i>
	<h1> <i class="fa fa-check green"></i> Connected</h1>
	<p>
	Wordpress has successfully connected to your Beacon account:<b> <span class="bn-title"> </span></b>

	</p>

	<p>
	<b>Published eBooks:</b>
	<ul class="issues bn-issues"></ul>
	</p>

	<p>
	<button href="#" class="button large bn-refresh">
		<i class="fa fa-refresh"></i>
		Refresh list of eBooks
	</button>
	</p>



</div>
<?php else: ?>
<div class="info">
	<i class="fa fa-info-circle"></i>
	<h1>Hey there!</h1>

	<p>
	In order to get you up and running we first need to connect your Beacon account to your blog.
	</p>

	<p>If you don't already have an account you can register one here 

	<form action="http://<?php echo BEACONBY_CREATE_TARGET; ?>/auth/register-wordpress" method="post">
		<input type="hidden" name="page" value="<?php echo $_SERVER['HTTP_HOST']; ?>"/>
		<input type="hidden" name="domain" value="<?php echo $_SERVER['PHP_SELF']; ?>"/>
		<button type="submit" class="button">Create a Beacon account</button>
	</form>
	<br />
	Once you've registered, we'll direct you back here.</p>

	<p>
		The connect process will take you to Beacon, to log on, and then redirect you back here. <b>No personal information, such as your email address, will be shared</b>
	</p>


	<form action="http://<?php echo BEACONBY_CREATE_TARGET; ?>/auth/wordpress" method="post">
		<input type="hidden" name="blog" value="<?php echo $_SERVER['HTTP_HOST']; ?>" />
		<input type="hidden" name="ref" value="<?php echo Beacon_plugin::getPageURL(); ?>" />
		<button class="button large">Let's Connect</button>
	</form>

	

</div>

<?php endif; ?>
