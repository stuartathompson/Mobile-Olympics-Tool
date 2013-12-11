<?php
   /*
   Plugin Name: Globe Ajax Tags
   Plugin URI: http://globeandmail.com
   Description: Loads and filters tags on the same page
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */
add_action( 'wp_enqueue_scripts', 'ajax_tags_scripts' );

add_action("wp_ajax_nopriv_ajax_tags_loop", "ajax_tags_loop");
add_action("wp_ajax_ajax_tags_loop", "ajax_tags_loop");

function ajax_tags_scripts(){
	wp_enqueue_script('globe_ajax_tags',plugin_dir_url( __FILE__ ) . 'ajaxtags.js',array( 'jquery','modernizr' ));
	wp_enqueue_style('globe_ajax_tags',plugin_dir_url( __FILE__ ) . 'ajaxtags.css');
	wp_localize_script( 'globe_ajax_tags', 'ajaxurl', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
}

function ajax_tags_create_front_end(){
 	// Sports for use in filters
	$acceptedFilters = array(
		"Hockey",
		//"Alpine skiing",
		//"Biathlon",
		"Bobsleigh",
		//"Cross-country skiing",
		"Curling",
		"Figure skating",
		//"Freestyle skiing",
		//"Luge",
		//"Nordic combined",
		"Short track speed skating",
		//"Skeleton",
		//"Ski jumping",
		"Speed skating"
	);
	// Get query vars suggesting tags
	$tags = $_GET['tags'];
?>
	<div id="filters-bar">
	<?php 
 	// Only get header for homepage
	if(is_home()){ ?> <h3 id="home-nav"><a href="<?php bloginfo('url'); ?>">Live Updates</a></h3> <?php } else { ?> <h3 id="home-nav"><a href="<?php bloginfo('url'); ?>">Globe Olympics</a></h3><?php } ?>
	<div id="filters" class="filters">
		<div class="select">
			<select id="filterSelect" class="dropdown field" autocomplete="off">
				<option value="">Select sports</option>
			<?php
				foreach($acceptedFilters as $filter){
					echo "<option value='" . $filter . "'>" . $filter . "</option>";
				}
			?>
			</select>
		</div>
		<div id="ajaxtags-loader"><img src="http://beta.images.theglobeandmail.com/static/templates/images/loader.gif" /></div>
	</div>

	<?php 
		$showing = '';
		if(is_tag() || (isset($tags) && $tags != '') || $_COOKIE['globe-ajaxtags_cookie']) $showing = ' showing';
	?>
	<div id="topics" class="topics<?php echo $showing; ?>">
		<?php
			// Show tag if tag page
			if(is_tag()){
				echo '<span class="item noselect" data-filter="';
				$needle = array(' ','\'');
				$haystack = array('-','');
				echo str_replace($needle,$haystack,single_tag_title('',false));
				echo '">';
					single_tag_title();
				echo '</span>';
			}
			// Show tags if _GET tags has results
			if(isset($tags) && $tags != ''){
				$tagArr = explode(' ',$tags);
				foreach($tagArr as $tag){
					echo '<span class="item noselect" data-filter="';
					echo $tag;
					echo '">' . $tag;
					echo '</span>';
				}
			}
			// Show tags for cookied tags
			if(is_home() && $_COOKIE['globe-ajaxtags_cookie']){
				$tagArr = explode(' ',$_COOKIE['globe-ajaxtags_cookie']);
				foreach($tagArr as $tag){
					echo '<span class="item noselect" data-filter="';
					echo $tag;
					echo '">' . $tag;
					echo '</span>';
				}
			}
		?>
		</div>
	<div id="filters-error">
		Sorry, no articles were found using those tags. <a id="ajaxtags-clear-tags" href="#clear">Clear tags?</a>
	</div>
	</div>
	<div class="clearfloat"></div>
<?php
}

function ajax_tags_create_mobile_front_end(){
?>
<div class="ajaxtags-container">
	<h3>Your filters</h3>
	<?php
	// Sports for use in filters
	$acceptedFilters = array(
		"Hockey",
		//"Alpine skiing",
		//"Biathlon",
		"Bobsleigh",
		//"Cross-country skiing",
		"Curling",
		"Figure skating",
		"Freestyle skiing",
		"Luge",
		//"Nordic combined",
		// "Short track speed skating",
		"Skeleton",
		//"Ski jumping",
		"Snowboarding",
		"Speed skating"
	);
		foreach($acceptedFilters as $filter){
			echo "<div class='ajaxtags-option'><span></span>" . $filter . "</div>";
		}
	?>
</div>
<?php
}

function ajax_tags_loop() {
	// get the submitted parameters
	
	$query = $_POST['query'];
 	
 	$args = array(
 		'posts_per_page'=>5,
		'tag'=>$query 		
 	);
 	
 	$queryposts = new WP_Query( $args );
	
	$postCount = 0;
	
	if ($queryposts->have_posts()): while ($queryposts->have_posts()) : $queryposts->the_post();
	
		include(locate_template('loop.php'));
		
		endwhile;
		
	endif;
 	
 	// IMPORTANT: don't forget to "exit"
	exit;

}
