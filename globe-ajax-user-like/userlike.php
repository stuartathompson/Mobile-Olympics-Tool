<?php
   /*
   Plugin Name: Globe Ajax User Like
   Plugin URI: http://globeandmail.com
   Description: Lets users like content
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */

// Enqueue styles and scripts
add_action( 'wp_enqueue_scripts', 'userlike_scripts' );

add_action("wp_ajax_nopriv_userlike_addLike", "userlike_addLike");
add_action("wp_ajax_userlike_addLike", "userlike_addLike");

add_action("wp_ajax_nopriv_userlike_removeLike", "userlike_removeLike");
add_action("wp_ajax_userlike_removeLike", "userlike_removeLike");

function userlike_scripts(){
	// embed the javascript file that makes the AJAX request
	wp_enqueue_script( 'userlike-ajax', plugin_dir_url( __FILE__ ) . 'userlike.js', array( 'jquery' ) );
	wp_enqueue_style( 'userlike-ajax', plugin_dir_url( __FILE__ ) . 'userlike.css');

	// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
	wp_localize_script( 'userlike-ajax', 'ajaxurl', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

}
// addLikes
function userlike_addLike(){
	global $post;
	$postId = $_POST['query'];
	$current_likes = get_post_meta($postId,"userlike_likes",true);
	if(!isset($current_likes) OR empty($current_likes) OR !is_numeric($current_likes)){
		$current_likes = 0;
	}
	$new_likes = $current_likes + 1;
	update_post_meta($postId,"userlike_likes",$new_likes);
	echo $new_likes;
	exit;
}
function userlike_removeLike(){
	global $post;
	$postId = $_POST['query'];
	$current_likes = get_post_meta($postId,"userlike_likes",true);
	if(!isset($current_likes) OR empty($current_likes) OR !is_numeric($current_likes)){
		$current_likes = 1;
	}
	$new_likes = $current_likes - 1;
	update_post_meta($postId,"userlike_likes",$new_likes);

	echo $new_likes;
	exit;
}
function userlike_create_front_end(){
?>
			<div title="<?php 
				$like = get_post_meta(get_the_ID(), 'userlike_likes', true); 
				if($like =='') { 
					echo 'Like'; 
				} else if($like == 1){ 
					echo '1 like'; 
				} else { 
					echo $like . ' likes'; 
				};
			?>" class="star share-reaction-icon"><a href="#"><span class="user-like-count"><?php $likes = get_post_meta(get_the_ID(), 'userlike_likes', true); if($likes=='') $likes = 0; echo $likes; ?></span><img src="<?php bloginfo('template_url'); ?>/img/star.png" /></a></div>
<?php
}