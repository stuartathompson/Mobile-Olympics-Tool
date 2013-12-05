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


remove_shortcode('gallery');
add_shortcode('gallery','globe_gallery');

function globe_gallery($atts){
	wp_enqueue_style('globe_gallery',plugin_dir_url( __FILE__ ) . 'globegallery.css');
	wp_enqueue_script('globe_gallery',plugin_dir_url( __FILE__ ) . 'globegallery.js',array( 'jquery' ));

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
		echo "<div class='wp-caption gi-gallery-image gallery-icon showing'>" . '<a href="' . wp_get_attachment_image_src( $image->ID, 'large' )[0] . '">' . wp_get_attachment_image($image->ID,'large') . "</a><p class='wp-caption-text'>" . $image->post_excerpt . "</a></p></div>";
		} else  {
		// Leave source blank
		echo "<div class='wp-caption gi-gallery-image gallery-icon'>" . '<a href="' . wp_get_attachment_image_src( $image->ID, 'large' )[0] . '"><img src="" data-image-src="' . wp_get_attachment_image_src($image->ID,'large')[0] . "\" alt=\"\" /></a><p class='wp-caption-text'>" . $image->post_excerpt . "</a></p></div>";
		}
		$i++; 
	}	
		
?>
		</div>
	</div>
<?php
}

function globe_gallery_regular($atts){
	wp_enqueue_style('globe_gallery',plugin_dir_url( __FILE__ ) . 'globegallery.css');
	wp_enqueue_script('globe_gallery',plugin_dir_url( __FILE__ ) . 'globegallery.js',array( 'jquery' ));

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
		<div class="gi-gallery-nav">
			<a class="next" href="#next">Next</a>
			<a class="prev" href="#Prev">Prev</a>
		</div>
		<div class="gi-gallery-images">
<?php
	$i=0;
	foreach($images as $image){
		$showing = '';
		if($i==0) $showing = ' showing';
		echo "<div class='wp-caption gi-gallery-image gallery-icon" . $showing . "'>" . '<a href="' . wp_get_attachment_image_src( $image->ID, 'large' )[0] . '">' . wp_get_attachment_image($image->ID,'large') . "</a><p class='wp-caption-text'>" . $image->post_excerpt . "</a></p></div>";
		$i++;
	}
?>
		</div>
	</div>
<?php
}