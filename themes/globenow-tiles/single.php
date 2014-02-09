<?php get_header(); ?>
	
	<section id="loop" role="main">
	
	<div id="loop-wrapper">
		
	<?php 
		
		$extra_classes = 'standalone'; 
		$postCount = 1;
	
	?>
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
		<?php include(locate_template('loop.php')); ?>
		
	<?php endwhile; ?>
	
	<?php else: ?>
	
		<!-- article -->
		<article>
			
			<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
			
		</article>
		<!-- /article -->
	
	<?php endif; ?>
			<h3 class="moreposts">More stories from Sochi 2014</h3>
			<?php 
				// Get other posts
				$exclude = get_the_ID();
				$extra_classes = '';
				$queryposts = new WP_Query('posts_per_page=5,post_status=published');
				
				if ($queryposts->have_posts()): while ($queryposts->have_posts()) : $queryposts->the_post();
	
					include(locate_template('loop.php'));
			
				endwhile;
		
				endif;
			?>
		<div class="single-loadmore"><a href="<?php bloginfo('url'); ?>"><img width="25px" height="25px" src="http://olympics.theglobeandmail.com/wp-content/themes/globenow-tiles/img/logo-leaf.png?!" /><br /><br />View all posts from Sochi Live »</a></div>
	</div>
	</section>
	<!-- /section -->
	
<?php // get_sidebar(); ?>

<?php get_footer(); ?>