<?php
   /*
   Plugin Name: Globe Post Types
   Plugin URI: http://globeandmail.com
   Description: Adds an option to the New Posts screen to set specific post options
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */

function posttype_box() {

    $screens = array( 'post', 'page' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'posttype_sectionid',
            __( 'Select the type of post', 'posttype_textdomain' ),
            'posttype_inner_custom_box',
            $screen,
            'side',
            'high'
        );
    }
}

add_action( 'add_meta_boxes', 'posttype_box' );




function posttype_inner_custom_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'posttype_inner_custom_box', 'posttype_box_nonce' );

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */
  $value = get_post_meta( $post->ID, 'posttype', true );

  echo '<label for="posttype_newfield">';
       _e( "Post type", 'posttype_textdomain' );
  echo '</label> ';
?>
  <select id="posttype_newfield" name="posttype_newfield" value="<?php esc_attr($value); ?>">
  
  	<option value="newsarticle" <?php if(esc_attr($value) == 'newsarticle') echo 'selected'; ?>>News article</option>
  	<option value="fullwidth" <?php if(esc_attr($value) == 'fullwidth') echo 'selected'; ?>>Media</option>
  	<option value="breaking" <?php if(esc_attr($value) == 'breaking') echo 'selected'; ?>>Breaking</option>  	
  </select>
<?php

}



function posttype_save_postdata( $post_id ) {
?>
<?php
  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['posttype_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['posttype_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'posttype_inner_custom_box' ) )
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

  // Sanitize user input.
  $mydata = sanitize_text_field( $_POST['posttype_newfield'] );

  // Update the meta field in the database.
  update_post_meta( $post_id, 'posttype', $mydata );
}
add_action( 'save_post', 'posttype_save_postdata' );


/* Options Page */

add_action('admin_menu', 'posttype_admin_menu');
add_action( 'admin_enqueue_scripts', 'posttype_admin_scripts' );

function posttype_admin_menu(){

	// Create top-level menu
	add_menu_page('Post type settings','Post type settings', 'administrator', __FILE__, 'posttype_settings_page');
	
	add_action('admin_init','register_posttype_settings');
}

function register_mysettings() {
	//register our settings
	register_setting( 'posttype-settings-group', 'post_types' );
}

function posttype_admin_scripts(){
	wp_enqueue_script( 'posttype-admin', plugin_dir_url( __FILE__ ) . 'posttype-admin.js', array( 'jquery' ) );
}

function posttype_settings_page() {
?>
<div class="wrap">
<h2>Post types</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'posttype-settings-group' ); ?>
    <?php do_settings_sections( 'posttype-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">New Option Name</th>
        <td><input type="text" name="post_types" value="<?php echo get_option('post_types'); ?>" /></td><td><input type="button" class="posttype-remove button" value="Remove" /></td>
        </tr>
    </table>
    
    <p><input type="button" class="posttype-add button" value="Add another" /></p>
    
    <?php submit_button(); ?>

</form>
</div>

<?php 
}