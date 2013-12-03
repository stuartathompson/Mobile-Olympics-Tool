<?php
   /*
   Plugin Name: Globe Ajax Refresh
   Plugin URI: http://globeandmail.com
   Description: Refresh the page with new content
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */

add_action("wp_ajax_nopriv_ajax_refresh_loop", "ajax_refresh_loop");
add_action("wp_ajax_ajax_refresh_loop", "ajax_refresh_loop");

add_action("wp_ajax_nopriv_ajax_check_new_posts", "ajax_check_new_posts");
add_action("wp_ajax_ajax_check_new_posts", "ajax_check_new_posts");

wp_enqueue_script('globe_ajax_refresh',plugin_dir_url( __FILE__ ) . 'ajaxrefresh.js',array( 'jquery' ));

wp_localize_script( 'globe_ajax_refresh', 'ajaxurl', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	

function ajax_refresh_front_end(){
/*
	<a href="#refresh" id="refresh" class=""><img src="<?php bloginfo('template_url'); ?>/img/refresh-icon.png" scale="0"></a>
*/
}
function ajax_refresh_loop($query) {
		
	$postIds = $_POST['postIds'];

	// get the submitted parameters
	$args = array(
		'post__in'=> array($postIds),
		'ignore_sticky_posts'=>true
 	);
	
	$queryposts = new WP_Query( $args );
	
	$postCount = 0;
	
	if ($queryposts->have_posts()): while ($queryposts->have_posts()) : $queryposts->the_post();
	
		include(locate_template('loop.php'));
		
		endwhile;
		
	endif;
	
	exit;

}
function ajax_check_new_posts($args) {
	global $wpdb;
	
	// get the submitted parameters
	$args = $_POST['postId'];
	
	$query = "
		SELECT ID
		FROM $wpdb->posts
		WHERE post_status = 'publish'
		AND ID > " . $args;
	
	$queryposts = $wpdb->get_results($query);
	if($queryposts){
		$i = 0;
		foreach($queryposts as $querypost){
			$i++;
			$output = $querypost->ID;
			if($i < count($queryposts)) $output .= ',';
			echo $output;
		}
	}
	exit;

}