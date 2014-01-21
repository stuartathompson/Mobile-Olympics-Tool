<?php get_header(); ?>
	
	<!-- section -->
	<section id="loop" role="main">
		
		<div id="loop-wrapper">
		
			<?php $postCount = 0; ?>

			<?php
				// Prepopulate query with cookies
				$tagArr = array();
				$ajaxTagsCookie = $_COOKIE['globe-ajaxtags_cookie'];
				// Add ?tags query
				if($_GET['tags']) $ajaxTagsCookie = $ajaxTagsCookie . ',' . $_GET['tags'];
				// Add pagination
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				// Determine whether this is a "big moments" query, which takes an AND statement
				$array = array();
				$bigMomentsQuery = false;
				foreach(explode(',',$ajaxTagsCookie) as $q){
					if($q!='big-moments'){
						array_push($array,$q);
					} else {
						$bigMomentsQuery = true;
					}
				}
				if($bigMomentsQuery && count($array) > 0){
					$args = array(
				 		'posts_per_page'=>10,
						'tag'=>$query,
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
				} else {
					$args = array(
						'tag'=>$ajaxTagsCookie,
						'paged'=>$paged,
						'posts_per_page'=>10
					);
				}
				
 					query_posts( $args );

					if (have_posts()): while (have_posts()) : the_post();

					include(locate_template('loop.php'));
			
					endwhile;

					get_template_part('pagination');

					else:
					
					?>
					<!-- article -->
					<article class="noposts">
						<h2><?php _e( 'No posts were found. <a style="color:#d60000" class="ajaxtags-clear-tags" href="#clear">Clear tags?</a>', 'html5blank' ); ?></h2>
					</article>	
					<?php 
					endif;
					
			/*	} else {
				
					echo 'else here';
					
					if (have_posts()): while (have_posts()) : the_post();
					
					include(locate_template('loop.php'));
			
					endwhile;
		
					else:
					
					?>
					<!-- article -->
					<article>
						<h2><?php _e( 'No posts were found.', 'html5blank' ); ?></h2>
					</article>	
					<?php 
					endif; 
				
					get_template_part('pagination');

				}*/
			?>
	
		</div>		
		
	
	</section>
	<!-- /section -->
	
	<?php get_sidebar(); ?>
	<?php wp_reset_query(); ?>
	
<?php get_footer(); ?>