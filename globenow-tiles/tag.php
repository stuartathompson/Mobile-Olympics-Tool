<?php get_header(); ?>
	
	<!-- section -->
	<section id="loop" role="main">

		<div id="loop-wrapper">
		
			<?php if (have_posts()): while (have_posts()) : the_post(); ?>
			
				<?php include(locate_template('loop.php')); ?>
			
			<?php endwhile; ?>
		
			<?php else: ?>

				<!-- article -->
				<article>
					<h2><?php _e( 'No posts were found.', 'html5blank' ); ?></h2>
				</article>	

			<?php endif; ?>
		
		</div>
					
		<?php get_template_part('pagination'); ?>
	
	</section>
	<!-- /section -->
	
<?php // get_sidebar(); ?>

<?php get_footer(); ?>