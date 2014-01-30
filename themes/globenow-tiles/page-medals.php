<?php get_header(); ?>
	
	<!-- section -->
	<section class="globeabout" role="main">
	
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
	<article class="globeabout-lede">
		<h1><img src="<?php bloginfo('template_url'); ?>/img/sochilogo.png" /></h1>
		<p>Keep track of medal counts for Canada and other countries throughout the games using this chart. It will be updated live every time a medal is awarded.</p>
	</article>
	
	<article id="page-medals">
			<?php if(function_exists('globe_social_share')) globe_social_share(); ?>
		<div class="clearfloat"></div>	
			<?php if(function_exists('medals_widget_create_front_end_full')) medals_widget_create_front_end_full(); ?>
		
	</article>
		<!-- /article -->
		
	<?php endwhile; ?>
	
	<?php else: ?>
	
		<!-- article -->
		<article>

			<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
			
		</article>
		<!-- /article -->
	
	<?php endif; ?>
	
	</section>
	<!-- /section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>