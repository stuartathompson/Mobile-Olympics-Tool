<?php
   /*
   Plugin Name: Globe Tag Types
   Plugin URI: http://globeandmail.com
   Description: Adds an option to the New Posts screen to set specific post options
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */

function tagtype_box() {

    $screens = array( 'post', 'page' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'tagtype_sectionid',
            __( 'Select the tags', 'tagtype_textdomain' ),
            'tagtype_inner_custom_box',
            $screen,
            'side',
            'high'
        );
    }
}

add_action( 'add_meta_boxes', 'tagtype_box' );




function tagtype_inner_custom_box( $post ) {

  // Add an nonce field so we can check for it later.
  wp_nonce_field( 'tagtype_inner_custom_box', 'tagtype_box_nonce' );

  /*
   * Use get_post_meta() to retrieve an existing value
   * from the database and use the value for the form.
   */

?>
  <?php
  	// Sports for use in filters
	$acceptedFilters = array(
		//"Alpine skiing",
		//"Biathlon",
		"Bobsleigh",
		//"Cross-country skiing",
		"Curling",
		"Figure skating",
		"Freestyle skiing",
		"Hockey",
		"Luge",
		//"Nordic combined",
		// "Short track speed skating",
		"Skeleton",
		//"Ski jumping",
		"Snowboarding",
		"Speed skating"
	);

	echo '<form id="posttype-newtag">';
    $tags = wp_get_post_tags( $post->ID );
	$addedTags = array();
	foreach($acceptedFilters as $tag){
		$selected = '';
		foreach($tags as $t){
			if(str_replace(' ','-',$t->name) == strtolower(str_replace(' ','-',$tag))) {
				$selected = 'checked';
				array_push($addedTags,$tag);
			}
		}
		echo '<div style="padding:5px 0;border-bottom:1px solid #ececec;"><label><input ' . $selected . ' type="checkbox" id="' .  str_replace(' ','-',$tag) . '" value="' . strtolower(str_replace(' ','-',$tag)) .'" name="tagtype-tags[]" />' . ucwords($tag) . '</label></div>';
	}
	echo '<strong style="padding-top:15px;display:block;">Other</strong><div style="padding:5px 0;border-bottom:1px solid #ececec;"><label><input type="checkbox" id="big-moments" value="big-moments" name="tagtype-tags[]" />Big Moments</label></div>';
	foreach($tags as $t){
		$found = true;
		foreach($addedTags as $tag){
			if(str_replace(' ','-',$t->name) == strtolower(str_replace(' ','-',$tag))) $found = false;
		}
		if($found){
			echo '<div style="padding:5px 0;border-bottom:1px solid #ececec;"><label><input checked type="checkbox" id="' .  str_replace(' ','-',$t->name) . '" value="' . strtolower(str_replace(' ','-',$t->name)) .'" name="tagtype-tags[]" />' . ucwords($t->name) . '</label></div>';
		}
	}
	echo '<div id="tagtype-addnew" style="padding:5px 0;border-bottom:1px solid #ececec;"><input type="input" id="tagtype-input" class="newtag form-input-tip" size="16" name="posttag-user-submitted"/> <input type="button" id="tagtype-button" class="button tagadd" value="Add"></div>';
	
	echo '<div style="padding-top:15px;display:block;width:100%;"></div><input type="submit" value="Submit" name="tagtype-save" id="tagtype-save-post" value="Save" class="button">';

	echo '</form>';
  	/*
$i = 0;
  	foreach($tagtypes as $tagtype){
  		$selected = '';
  		if($tagtype == esc_attr($value)) $selected = 'checked';
  		if($tagtype == 'newsarticle' && esc_attr($value) == '') $selected = 'checked';
  		echo '<p><input type="radio" name="tagtype_newfield" id="tagtype_newfield_' . $tagtype . '" value="' . $tagtype . '" ' . $selected . '> <label for="tagtype_newfield_' . $tagtype . '">' . $tagtypeNames[$i] . '</label></p>';
  		$i++;
  	}
*/
  ?>

<?php

}



function tagtype_save_postdata( $post_id ) {
?>
<?php
  /*
   * We need to verify this came from the our screen and with proper authorization,
   * because save_post can be triggered at other times.
   */

  // Check if our nonce is set.
  if ( ! isset( $_POST['tagtype_box_nonce'] ) )
    return $post_id;

  $nonce = $_POST['tagtype_box_nonce'];

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $nonce, 'tagtype_inner_custom_box' ) )
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
  if(isset($_POST['tagtype-tags']) && is_array($_POST['tagtype-tags']) ){
  	wp_set_post_terms( $post_id, $_POST['tagtype-tags'], 'post_tag' );
  }
  
}
add_action( 'save_post', 'tagtype_save_postdata' );


/* Options Page */

add_action('admin_menu', 'tagtype_admin_menu');
add_action( 'admin_enqueue_scripts', 'tagtype_admin_scripts' );

function tagtype_admin_menu(){

	// Create top-level menu
	add_menu_page('Post tags','Post tags', 'administrator', __FILE__, 'tagtype_settings_page');
	
	//add_action('admin_init','register_tagtype_settings');
}

function register_tagtype_settings() {
	//register our settings
	register_setting( 'tagtype-settings-group', 'post_types' );
}

function tagtype_admin_scripts(){
	wp_enqueue_script( 'tagtype-admin', plugin_dir_url( __FILE__ ) . 'tagtype.js', array( 'jquery' ) );
}
