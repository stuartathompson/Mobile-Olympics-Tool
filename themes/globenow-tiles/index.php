<?php get_header(); ?>
	
	<!-- section -->
	<section id="loop" role="main">
		
		<div id="loop-wrapper">
		
			<?php $postCount = 0; ?>

			<?php 
				$ajaxTagsCookie = $_COOKIE['globe-ajaxtags_cookie'];
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				//if($ajaxTagsCookie){
					$args = array(
						'posts_per_page'=>5,
						'tag'=>$ajaxTagsCookie,
						'paged'=>$paged,
						'posts_per_page'=>10
 					);
 	
 					query_posts( $args );

					if (have_posts()): while (have_posts()) : the_post();

					include(locate_template('loop.php'));
			
					endwhile;

					get_template_part('pagination');

					else:
					
					?>
					<!-- article -->
					<article class="noposts">
						<h2><?php _e( 'No posts were found.', 'html5blank' ); ?></h2>
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