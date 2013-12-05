<?php get_header(); ?>
	
	<!-- section -->
	<section id="loop" role="main">

		<?php get_template_part('loop'); ?>
		
		<?php get_template_part('pagination'); ?>
	
	</section>
	<!-- /section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>