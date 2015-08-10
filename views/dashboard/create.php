
<div class="info">
<i class="fa fa-info-circle"></i>
<p>
Automatically convert evergreen blog posts into a professional, interactive eBook without any help from a designer.
</p>

<p>
Simply select the posts you want to feature in your eBook below, click create and we'll take care of the rest.
</p>
</div>


	<div class="search">
		<input type="text" class="filter" placeholder="search" />
		<span class="clear">x</span>
	</div>
	

<div class="err error-no-posts">
Please select at least one post
</div>

<div class="col">
	<?php $posts = get_posts( array('numberposts' => -1) ); ?>
	<script>
	var BeaconByPosts = <?php echo json_encode( $posts ); ?>;
	</script>
	<form action="http://<?php echo BEACONBY_CREATE_TARGET; ?>/api/ebook" method="post" target="_blank" class="select-posts">

	<input type="hidden" name="url" value="<?php echo get_site_url() ?>" />
	<input type="hidden" name="title" value="<?php echo get_bloginfo('name') ?>" />
	<input type="hidden" name="decription" value="<?php echo get_bloginfo('description') ?>" />




	<?php 
	if ( $posts ) :
		foreach ( $posts as $post ) :
			$cats = get_the_category( $post->ID );
			$post_cats = array();
			foreach  ($cats as $cat ) {
				$post_cats[] = $cat->cat_name;
			}
			$post->cats = implode( ',', $post_cats );
			$encoded = base64_encode ( serialize( $post ) );
	?>

	<div class="form-row">
		<input type="checkbox" 
				class="post_toggle" 
				id="beacon_export_<?php echo $post->ID?>" />
		<input type="hidden" 
				class="post_data" 
				data-cats="<?php echo $post->cats; ?>" 
				data-title="<?php echo $post->post_title; ?>"
				name="posts[<?php echo $post->id; ?>]" 
				value="<?php echo $encoded; ?>" />

		<label for="beacon_export_<?php echo $post->ID ?>">
		<b><?php echo $post->post_title; ?></b>
		<small><?php echo $post->cats; ?></small>
		</label>
	</div>

	<?php
		endforeach;
	endif;
	?>

</div>

<div class="col">
	<h3>Filter Categories</h3>
	<p>
	Click category to toggle
	</p>

	<span class="all-cat">Show All</span>
	<br>
	<?php 
	$categories = get_categories(); 
	foreach ( $categories as $cat ):
	?>
	<span class="toggle-cat"><?php echo $cat->name; ?></span>
	<?php
	endforeach;
	?>
</div>

<button class="button large create fixed">Create eBook &raquo;</button>

</form>

