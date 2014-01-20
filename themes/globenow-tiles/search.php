<?php get_header(); ?>
	
	<!-- section -->
	<section role="main">
	
		<h3 class="searchresults"><?php echo sprintf( __( 'Search Results for ', 'html5blank' ), $wp_query->found_posts ); echo '"' . get_search_query() . '"'; ?></h3>
		<?php $postCount = 0; ?>
		<?php
		if (have_posts()): while (have_posts()) : the_post();

 		?>

		<?php get_template_part('loop'); ?>

		<?php
		endwhile;
		else:
		?>
		<!-- article -->
		<article id="post-<?php the_ID(); ?>">
		
		    <p>Sorry, no posts were found using those search terms.</p>
		    <div class="suggestions">
		        <div class="quickies">
		            <p>Return to our <a href="<?php bloginfo('url'); ?>">Olympics coverage</a> or to <a href="http://www.theglobeandmail.com">globeandmail.com</a></div>
		    </div>
		
		</article>
		<!-- /article -->
		
		<?php 
		endif;
		?>
	
	</section>
	<!-- /section -->
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>