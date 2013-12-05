<?php get_header(); ?>
	
	<!-- section -->
	<section id="loop" role="main">
		
		<div id="loop-wrapper">
		
			<?php $postCount = 0; ?>

			<?php 
				$ajaxTagsCookie = $_COOKIE['globe-ajaxtags_cookie'];
				if($ajaxTagsCookie){
					$args = array(
						'posts_per_page'=>5,
						'tag'=>$ajaxTagsCookie 		
 					);
 	
 					$queryposts = new WP_Query( $args );

					if ($queryposts->have_posts()): while ($queryposts->have_posts()) : $queryposts->the_post();

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
					
				} else {
				
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
				
				}
			?>
	
		</div>		
		
		<?php get_template_part('pagination'); ?>
	
	</section>
	<!-- /section -->
	

	<?php get_sidebar(); ?>
	<?php wp_reset_query(); ?>
	
<?php get_footer(); ?>