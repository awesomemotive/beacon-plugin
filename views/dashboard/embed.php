<div class="info">
	<i class="fa fa-info-circle"></i>
	<p>
	You can embed any eBook on your blog.
	</p>
	<p>
	Follow the steps below to get embedding in a couple of minutes.
	</p>

	<p>
	<button href="#" class="button large bn-refresh">
		<i class="fa fa-refresh"></i>
		Refresh list of eBooks
	</button>
	</p>
</div>

<div class="beacon-embed select-issue step-1" style="width: 40%; display: inline-block; vertical-align: top;">
	<h3>Step 1. Select eBook:</h3>


	<input type="hidden" name="url" value="<?php echo $url; ?>" />

	<ul class="issues">
	</ul>

	<div class="collapse help-image">
		<a href="#" class="close"><i class="fa fa-times"></i> close</a>
		<img src="<?php echo BEACONBY_PLUGIN_URL . 'i/embed-help.gif'; ?>" />
	</div>
</div>

<div class="beacon-embed step-2" style="width: 40%; display: inline-block; vertical-align: top;">
	<h3>Step 2. Embed code</h3>
	<div class="embed-code embed-col">
	<textarea name="beacon-embed-code"></textarea>
	</div>


	<p>Paste the above into your blog post, ensuring that you are in the editor's <i>Text</i> Tab rather than <i>Visual</i> <br />
	<a class="show-help" href="#">View Image</a>
	</p>


</div>

<hr />

<div class="beacon-embed step-3">

	<h3>Step 3. Preview</h3>
	<div class="embed-preview">
		<iframe src="" class="beacon-iframe" width="100%" style="min-height: 500px;" scrolling="no" frameborder="no"></iframe>
	</div>
</div>
