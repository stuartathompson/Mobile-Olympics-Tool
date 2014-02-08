<?php
   /*
   Plugin Name: Globe API Box
   Plugin URI: http://globeandmail.com
   Description: Adds a checkbox to posts so you can add it to the API, which appears on other globeandmail products
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */

function apibox_box() {

    $screens = array( 'post', 'page' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'apibox_sectionid',
            __( 'Where this headline appears', 'apibox_textdomain' ),
            'apibox_inner_custom_box',
            $screen,
            'side',
            'low'
        );
    }
}

add_action( 'add_meta_boxes', 'apibox_box' );




function apibox_inner_custom_box( $post ) {
	
  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'apibox_inner_custom_box', 'apibox_box_nonce' );

  $apiboxSelect = get_post_meta($post->ID, 'apibox', true);
  $selectedOn = 'checked';
  $selectedAll = '';
  if($apiboxSelect == 'everywhere'){
  	$selectedOn = '';
  	$selectedAll='checked';
  }
  echo '<p><label><input ' . $selectedOn . ' type="radio" value="on" name="apibox" /> Just the Olympics blog</label><p><label><input ' . $selectedAll . ' type="radio" value="everywhere" name="apibox" /> All websites and apps</label><br />';

  echo '<div style="padding-top:5px;display:block;width:100%;"></div><input type="submit" value="Submit" name="apibox-save" id="apibox-save-post" value="Save" class="button" />';

}



function apibox_save_postdata( $post_id ) {
?>
<?php
  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['apibox_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['apibox_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'apibox_inner_custom_box' ) )
      return $post_id;

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return $post_id;

  // Check the user's permissions.
  if ( 'page' == $_POST['post_type'] ) {

    if ( ! current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  
  } else {

    if ( ! current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  /* OK, its safe for us to save the data now. */
  
  if(isset($_POST['apibox']) ){
  	update_post_meta($post_id, 'apibox', $_POST['apibox']);
  }
  
}
add_action( 'save_post', 'apibox_save_postdata' );


/* Options Page */
add_action( 'admin_enqueue_scripts', 'apibox_admin_scripts' );

function register_apibox_settings() {
	//register our settings
	register_setting( 'apibox-settings-group', 'post_types' );
}

function apibox_admin_scripts(){
	//wp_enqueue_script( 'apibox-admin', plugin_dir_url( __FILE__ ) . 'apibox.js', array( 'jquery' ) );
}

// Add column to all posts screen
function apibox_columns_head($defaults){
	$defaults['apibox_column'] = 'Distribution';
	return $defaults;
}
function apibox_columns_content($column_name,$post_ID){
	if($column_name = 'apibox_column'){
		 $apiboxSelect = get_post_meta($post_ID, 'apibox', true);
		  $selectedOn = 'checked';
		  $selectedAll = '';
		  if($apiboxSelect == 'everywhere'){
		  	$selectedOn = '';
		  	$selectedAll='checked';
		  }
		  echo '<p><label><input ' . $selectedOn . ' type="radio" value="on" name="apibox-' . $post_ID . '" /> Blog</label><p><label><input ' . $selectedAll . ' type="radio" value="everywhere" name="apibox-' . $post_ID . '" /> Everywhere</label><br />';
	}
}
function apibox_columns_save(){
	return 'yes';
/*
	global $wpdb;
	
	$post_id = $_POST['postID'];
	$apibox_value = $_POST['apiboxValue'];
	
	update_post_meta($post_id, 'apibox', $apibox_value);
	
	return 'Success';
*/
	
	die();
		
}
add_action( 'admin_footer', 'apibox_columns_javascript' );

function apibox_columns_javascript(){
?>
<script type="text/javascript">
	jQuery(document).ready(function($) {
	$('.apibox_column input[type="radio"]').click(function(){
		var postID = $(this).attr('name').split('-')[1],	
			apiboxValue = $(this).val(),
			action = 'my_action';
		var data = {
			action:action,
			postID:postID,
			apiboxValue:apiboxValue
		}
		$.post(ajaxurl, data, function(response) {
			console.log(response);
		});
	})
	});
</script>
<?php
}

add_action( 'wp_ajax_my_action', 'my_action_callback' );
function my_action_callback() {

	global $wpdb; // this is how you get access to the database

	$post_id = $_POST['postID'];
	$apibox_value = $_POST['apiboxValue'];
	
	update_post_meta($post_id, 'apibox', $apibox_value);
	
	echo 'Success';
	
	die(); // this is required to return a proper result
}

// REMOVE DEFAULT CATEGORY COLUMN
function apibox_columns_remove($defaults) {
    // to get defaults column names:
    // print_r($defaults);
    unset($defaults['categories']);
    unset($defaults['comments']);
    return $defaults;
}

add_action("wp_ajax_apibox_columns", "apibox_columns_save");
add_filter('manage_post_posts_columns', 'apibox_columns_remove');
add_filter('manage_posts_columns', 'apibox_columns_head');
add_action('manage_posts_custom_column', 'apibox_columns_content', 10, 2);