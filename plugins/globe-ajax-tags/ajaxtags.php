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

add_action("wp_ajax_nopriv_ajax_tags_loop_highlights", "ajax_tags_loop_highlights");
add_action("wp_ajax_ajax_tags_loop_highlights", "ajax_tags_loop_highlights");



function ajax_tags_scripts(){
	wp_enqueue_script('globe_ajax_tags',plugin_dir_url( __FILE__ ) . 'ajaxtags.js',array( 'jquery','modernizr'));
	wp_enqueue_style('globe_ajax_tags',plugin_dir_url( __FILE__ ) . 'ajaxtags.css');
	wp_localize_script( 'globe_ajax_tags', 'ajaxTagUrl', array( 
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'ajaxTagNonce' => wp_create_nonce( 'globe_ajax_tags_nonce')
	));	
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
		"Freestyle skiing",
		"Luge",
		//"Nordic combined",
		// "Short track speed skating",
		"Skeleton",
		//"Ski jumping",
		"Snowboarding",
		"Speed skating"
	);
	// Get query vars suggesting tags
	$tags = $_GET['tags'];
?>
	<div id="filters-bar">
	<?php 
 	// Show "Live Updates" on home
	if(is_home() || is_paged()){
		$selectedLive = ' class="selected"';
		$selectedBig = '';
		$tagArr = explode(',',$_COOKIE['globe-ajaxtags_cookie']);
		foreach($tagArr as $tag){
			if($tag == 'big-moments' || $tag == 'big moments' || $_GET['tags'] == 'big-moments'){
				$selectedLive = '';
				$selectedBig = ' class="selected"';
			}
		}
	?>
		<h3 id="home-nav"><a href="<?php bloginfo('url'); ?>" <?php echo $selectedLive; ?>>Live Updates</a></h3>
		<div id="filter-splitter"></div>
		<h3 id="home-highlights"><a href="<?php bloginfo('url'); ?>/tag/big-moments" <?php echo $selectedBig; ?>>big moments</a></h3> 
	<?php } else { ?>
		<h3 id="home-nav"><a href="<?php bloginfo('url'); ?>">2014 Winter Olympics</a></h3>
	<?php } ?>
	<div id="filters" class="filters">
		<?php if(!is_single() && !is_search()){ ?>
		<div class="select">
			<select id="filterSelect" class="dropdown field" autocomplete="off">
				<option value="">Filter sports</option>
			<?php
				$tagArr = explode(',',$_COOKIE['globe-ajaxtags_cookie']);
				foreach($acceptedFilters as $filter){
					// Check if tag already selected
					$disabled = '';
					if(in_array($filter,$tagArr)) $disabled = ' disabled';
					// Show option in dropdown
					echo "<option value='" . $filter . "' " . $disabled . ">" . $filter . "</option>";
				}
			?>
			</select>
		</div>
		<?php } ?>
		<div id="ajaxtags-loader"><img src="http://beta.images.theglobeandmail.com/static/templates/images/loader.gif" /></div>
	</div>
	</div>
	<?php 
		// If only one tag, ensure it's not big-moments before continuing
		$notBigMoments = true;
		$tagArr = explode(',',$_COOKIE['globe-ajaxtags_cookie']);
		if(count($tagArr) == 1){
			$notBigMoments = true;
			foreach($tagArr as $tag){
				if($tag == 'big-moments') { $notBigMoments = false; }
			}
		}
	?>
	<?php
		// Determine whether tag container should show
		$showing = '';
		if((is_paged() || is_home()) && ((isset($tags) && $tags != '') || ($_COOKIE['globe-ajaxtags_cookie'] && $notBigMoments)) || is_tag() ) $showing = ' showing';
	?>
	<div id="topics" class="topics<?php echo $showing; ?>">
		<?php
			// Show tag if tag page, and nothing else
			if(is_tag()){
				echo '<span class="item noselect" data-filter="';
				$needle = array(',','\'');
				$haystack = array('-','');
				echo str_replace($needle,$haystack,single_tag_title('',false));
				echo '">';
					single_tag_title();
				echo '</span>';
			} else {
				// Show tags for cookied tags
				if($_COOKIE['globe-ajaxtags_cookie']){
					$tagArr = explode(',',$_COOKIE['globe-ajaxtags_cookie']);
					foreach($tagArr as $tag){
						// Prevent Big Moments tag
						if($tag != "big-moments" && $tag != ''){
							echo '<span class="item noselect" data-filter="';
							echo $tag;
							echo '">' . str_replace("-"," ",$tag);
							echo '</span>';
						}
					}
				}
				// Show tags if _GET tags has results
				if(isset($tags) && $tags != ''){
					$tagArr = explode(',',$tags);
					$cookieArr = explode(',',$_COOKIE['globe-ajaxtags_cookie']);
					foreach($tagArr as $tag){
						if(!in_array($tag,$cookieArr) && $tag != 'big-moments'){
							echo '<span class="item noselect" data-filter="';
							echo $tag;
							echo '">' . $tag;
							echo '</span>';
						}
					}
				}
			}
		?>
		</div>
	<div id="filters-error">
		Sorry, no articles were found using those tags. <a id="ajaxtags-clear-tags" href="#clear">Clear tags?</a>
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
 	$nonce = $_POST['ajaxTagNonce'];
 		
 	if(!wp_verify_nonce($nonce,'globe_ajax_tags_nonce'))
 		die('Not a valid request ' . $nonce);

 	wp_reset_postdata();
 	
 	// Adjust for pagination
 	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;


 	$args = array(
 		'posts_per_page'=>10,
		'tag'=>$query,
 		'post_status'=>'publish',
		'paged'=>$paged
 	);

 	$queryposts = new WP_Query( $args );
	
	$postCount = 0;
	
	if ($queryposts->have_posts()): while ($queryposts->have_posts()) : $queryposts->the_post();
	
		include(locate_template('loop.php'));

		endwhile;

	
		if($queryposts->max_num_pages > 1 && $paged < $queryposts->max_num_pages){
		?>
			<div class="pagination">
				<div class="nav-previous alignleft"><a href="<?php bloginfo('url'); ?>/page/<?php echo $paged+1; ?>">Older posts</a></div>
				<div class="nav-next alignright"></div>
			</div>
		<?php
		}
		//next_posts_link('&laquo; Older Entries', $new_query->max_num_pages);
		//previous_posts_link('Newer Entries &raquo;');
		
	endif;
 	
 	// IMPORTANT: don't forget to "exit"
	exit;

}

function ajax_tags_loop_highlights() {
	// get the submitted parameters
	
	$query = $_POST['query'];

	wp_reset_postdata();
 	
 	// Adjust for pagination
 	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	
	$array = array();
	foreach(explode(',',$query) as $q){
		if($q!='big-moments') array_push($array,$q);
	}
 	$args = array(
 		'posts_per_page'=>10,
		'tag'=>$query,
 		'post_status'=>'publish',
		'tax_query' => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'post_tag',
				'field' => 'slug',
				'terms' => array( 'big-moments')
			),
			array(
				'taxonomy' => 'post_tag',
				'field' => 'slug',
				'terms' => $array
			)
		),
		'paged'=>$paged
 	);
 	
 	$queryposts = new WP_Query( $args );
	
	$postCount = 0;
	
	if ($queryposts->have_posts()): while ($queryposts->have_posts()) : $queryposts->the_post();
	
		include(locate_template('loop.php'));

		endwhile;

	
		if($queryposts->max_num_pages > 1 && $paged < $queryposts->max_num_pages){
		?>
			<div class="pagination">
				<div class="nav-previous alignleft"><a href="<?php bloginfo('url'); ?>/page/<?php echo $paged+1; ?>">Older posts</a></div>
				<div class="nav-next alignright"></div>
			</div>
		<?php
		}
		//next_posts_link('&laquo; Older Entries', $new_query->max_num_pages);
		//previous_posts_link('Newer Entries &raquo;');
		
	endif;
 	

 	// IMPORTANT: don't forget to "exit"
	exit;

}

