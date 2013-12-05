<?php get_header(); ?>
	
	<!-- section -->
	<section role="main">
	
		<h3 class="searchresults"><?php echo sprintf( __( 'Search Results for ', 'html5blank' ), $wp_query->found_posts ); echo get_search_query(); ?></h3>
		
		<?php get_template_part('loop'); ?>
		
		<?php get_template_part('pagination'); ?>
	
	</section>
	<!-- /section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>