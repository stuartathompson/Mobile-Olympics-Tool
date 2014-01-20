<?php
   /*
   Plugin Name: Globe Gallery
   Plugin URI: http://globeandmail.com
   Description: Reformats the default gallery to one typically found on globeandmail.com
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */

if(is_home() || is_single()){
remove_shortcode('gallery');
add_shortcode('gallery','globe_gallery_regular');
add_action( 'wp_enqueue_scripts', 'globe_gallery_script' );
}

function globe_gallery_script(){
	wp_enqueue_style('globe_gallery',plugin_dir_url( __FILE__ ) . 'globegallery.css');
	wp_enqueue_script('globe_gallery',plugin_dir_url( __FILE__ ) . 'globegallery.js',array( 'jquery' ));
}
function globe_gallery($atts){
	
	$args = array(
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'post_mime_type' => 'image',
		'orderby' => 'post__in',
		'include' => $atts['ids'],
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'columns' => 3,
		'size' => 'medium',
		'link' => 'file'
	);
	
	$images = get_posts($args);
	$w = 100;
?>
	<div class="gi-gallery">
		<!-- <div class="gi-gallery-label"><?php //echo count($images); ?> Photos</div> -->
		<div style="width:<?php echo $w; ?>%" class="gi-gallery-images">
<?php
	$i=0;
	$secondId = '';
	$dataList = array();
	foreach($images as $image){
		if($i==0){
		// Show image
		$imageSrc = wp_get_attachment_image_src( $image->ID, 'large' );
		echo "<div class='wp-caption gi-gallery-image gallery-icon showing'>" . '<a href="' . $imageSrc[0] . '">' . wp_get_attachment_image($image->ID,'large') . "</a><p class='wp-caption-text'>" . $image->post_excerpt . "</a></p></div>";
		} else  {
		// Leave source blank
		// Get image source (necessary step for PHP 5.3 or newer)
		$imageSrc = wp_get_attachment_image_src( $image->ID, 'large' );
		// Echo image and container but leave source blank. Load source via JS.
		echo "<div class='wp-caption gi-gallery-image gallery-icon'>" . '<a href="' . $imageSrc[0] . '"><img src="" data-image-src="' . $imageSrc[0] . "\" alt=\"\" /></a><p class='wp-caption-text'>" . $image->post_excerpt . "</a></p></div>";
		}
		$i++; 
	}	
		
?>
		</div>
	</div>
<?php
}

function globe_gallery_regular($atts){

	$args = array(
		'post_type' => 'attachment',
		'post_status' => 'inherit',
		'post_mime_type' => 'image',
		'orderby' => 'post__in',
		'include' => $atts['ids'],
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'columns' => 3,
		'size' => 'medium',
		'link' => 'file'
	);
	
	$images = get_posts($args);
?>
	<div class="gi-gallery">
		<div class="gi-gallery-sprite"></div>
		<div class="gi-gallery-nav">
			<div class="gi-gallery-num"><span>1</span> of <?php echo count($images); ?></div>
			<a class="next" href="#next">Next</a>
			<a class="prev" href="#Prev">Prev</a>
		</div>
		<div class="gi-gallery-images">
<?php
	$i=0;
	foreach($images as $image){
		if($i==0){
		// Show image
		$imageSrc = wp_get_attachment_image_src( $image->ID, 'large' );
		echo "<div class='wp-caption gi-gallery-image gallery-icon showing'><div class='gi-gallery-image-container'>" . '<a href="' . $imageSrc[0] . '">' . wp_get_attachment_image($image->ID,'large') . "</a></div>";
		if(strlen($image->post_excerpt) > 0) echo "<p class='wp-caption-text'>" . $image->post_excerpt . "</p>";
		echo "</div>";
		} else  {
		// Leave source blank
		$imageSrc = wp_get_attachment_image_src( $image->ID, 'large' );
		echo "<div class='wp-caption gi-gallery-image gallery-icon'><div class='gi-gallery-image-container'>" . '<a href="' . $imageSrc[0] . '"><img src="" data-image-src="' . $imageSrc[0] . "\" alt=\"\" /></a></div>";
		if(strlen($image->post_excerpt) > 0) echo "<p class='wp-caption-text'>" . $image->post_excerpt . "</p>";
		echo "</div>";
		}
		$i++; 
	}	
?>
		</div>
	</div>
<?php
}