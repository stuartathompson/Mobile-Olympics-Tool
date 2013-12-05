<?php

// Options Page Functions

function themeoptions_admin_menu() 
{
	// here's where we add our theme options page link to the dashboard sidebar
	add_theme_page("Theme Options", "Theme Options", 'edit_themes', basename(__FILE__), 'themeoptions_page');
}

function themeoptions_page() 
{
	// here's the main function that will generate our options page
	
	if ( $_POST['update_themeoptions'] == 'true' ) { themeoptions_update(); }
	
	//if ( get_option() == 'checked'
	
	?>
	<div class="wrap">
		<div id="icon-themes" class="icon32"><br /></div>
		<h2>Theme Options</h2>
	
		<form method="POST" action="">
			<input type="hidden" name="update_themeoptions" value="true" />
			
			<h4>Colour Stylesheet To Use</h4>
			<select name ="colour">
				<?php $colour = get_option('mytheme_colour'); ?>
				<option value="red" <?php if ($colour=='red') { echo 'selected'; } ?> >Red Stylesheet</option>
				<option value="green" <?php if ($colour=='green') { echo 'selected'; } ?>>Green Stylesheet</option>
				<option value="blue" <?php if ($colour=='blue') { echo 'selected'; } ?>>Blue Stylesheet</option>
			</select>
			
			<h4>Advertising Spot #1</h4>
			<p><input type="text" name="ad1image" id="ad1image" size="32" value="<?php echo get_option('mytheme_ad1image'); ?>"/> Advert Image</p>
			<p><input type="text" name="ad1url" id="ad1url" size="32" value="<?php echo get_option('mytheme_ad1url'); ?>"/> Advert Link</p>
			
			<h4>Advertising Spot #2</h4>
			<p><input type="text" name="ad2image" id="ad2image" size="32" value="<?php echo get_option('mytheme_ad2image'); ?>"/> Advert Image</p>
			<p><input type="text" name="ad2url" id="ad2url" size="32" value="<?php echo get_option('mytheme_ad2url'); ?>"/> Advert Link</p>
			
			<h4><input type="checkbox" name="display_sidebar" id="display_sidebar" <?php echo get_option('mytheme_display_sidebar'); ?> /> Display Sidebar</h4>
			
			<h4><input type="checkbox" name="display_search" id="display_search" <?php echo get_option('mytheme_display_search'); ?> /> Display Search Box</h4>
			
			<h4><input type="checkbox" name="display_twitter" id="display_twitter" <?php echo get_option('mytheme_display_twitter'); ?> /> Display Twitter Stream</h4>
			<p><input type="text" name="twitter_username" id="twitter_username" size="32" value="<?php echo get_option('mytheme_twitter_username'); ?>" /> Twitter Username</p> 
			
			<p><input type="submit" name="search" value="Update Options" class="button" /></p>
		</form>
	
	</div>
	<?php
}

function themeoptions_update()
{
	// this is where validation would go
	update_option('mytheme_colour', 	$_POST['colour']);
	
	update_option('mytheme_ad1image', 	$_POST['ad1image']);
	update_option('mytheme_ad1url', 	$_POST['ad1url']);
	
	update_option('mytheme_ad2image', 	$_POST['ad2image']);
	update_option('mytheme_ad2url', 	$_POST['ad2url']);
	
	if ($_POST['display_sidebar']=='on') { $display = 'checked'; } else { $display = ''; }
	update_option('mytheme_display_sidebar', 	$display);
	
	if ($_POST['display_search']=='on') { $display = 'checked'; } else { $display = ''; }
	update_option('mytheme_display_search', 	$display);
	
	if ($_POST['display_twitter']=='on') { $display = 'checked'; } else { $display = ''; }
	update_option('mytheme_display_twitter', 	$display);
	
	update_option('mytheme_twitter_username', 	$_POST['twitter_username']);
}

add_action('admin_menu', 'themeoptions_admin_menu');

?>
