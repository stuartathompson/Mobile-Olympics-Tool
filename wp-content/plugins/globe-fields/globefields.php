<?php
   /*
   Plugin Name: Globe Fields
   Plugin URI: http://globeandmail.com
   Description: Adds fields typically found in Globe and Mail articles like the byline
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */

function globefields_box() {

    $screens = array( 'post', 'page' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'globefields_sectionid',
            __( 'Other information', 'globefields_textdomain' ),
            'globefields_inner_custom_box',
            $screen,
            'side',
            'high'
        );
    }
}

add_action( 'add_meta_boxes', 'globefields_box' );




function globefields_inner_custom_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'globefields_inner_custom_box', 'globefields_box_nonce' );

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */
  $byline = get_post_meta( $post->ID, 'globefields_byline', true );
  $placeline = get_post_meta( $post->ID, 'globefields_placeline', true );
  
  echo '<label for="globefields_byline">';
       _e( "Byline:", 'globefields_textdomain' );
  echo '</label><Br /> ';
?>
  <input name="globefields_byline" type="text" size="32" value="<?php echo esc_attr($byline); ?>"/>
  
  <p>
  	<label for="globefields_placeline">Placeline:</label><br />
  	<input name="globefields_placeline" type="text" size="32" value="<?php echo esc_attr($placeline); ?>"/>
  </p>
  <?php

}



function globefields_save_postdata( $post_id ) {
?>
<?php
  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['globefields_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['globefields_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'globefields_inner_custom_box' ) )
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
  $byline = sanitize_text_field( $_POST['globefields_byline'] );
  $placeline = sanitize_text_field( $_POST['globefields_placeline'] );
  
  // Update the meta field in the database.
  update_post_meta( $post_id, 'globefields_byline', $byline );
  update_post_meta( $post_id, 'globefields_placeline', $placeline );
  
}
add_action( 'save_post', 'globefields_save_postdata' );

