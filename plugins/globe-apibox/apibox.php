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
	wp_enqueue_script( 'apibox-admin', plugin_dir_url( __FILE__ ) . 'apibox.js', array( 'jquery' ) );
}
