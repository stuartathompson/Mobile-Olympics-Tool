<?php get_header(); ?>
	
	<!-- section -->
	<section class="globeabout" role="main">
	
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
	<article class="globeabout-lede">
 		<h1 style="font-size:30px">Sochi medal count</h1>
		<p>Keep track of Canada's place in the medal rankings throughout the Games.</p>
		<p>This table will be updated live every time a medal is awarded.</p>
	</article>
	
	<article id="page-medals">
			<?php if(function_exists('globe_social_share')) globe_social_share(); ?>
		<div class="clearfloat"></div>	
			<?php if(function_exists('medals_widget_create_front_end_full')) medals_widget_create_front_end_full(); ?>
		
	</article>
	
	<?php
				$isMobile = false;
			if(function_exists('is_mobile')) $isMobile = is_mobile();
			if($isMobile){
			?>
<div class="ad-unit-square">
<div id="ad-unit-flex1AC"></div>
<script type="text/javascript">
/* mobi web */
flex1AC=fnTktWP('a'+'1',300,50,1,'ajax',nc);
document.getElementById('ad-unit-flex1AC').innerHTML = flex1AC;
</script>	
</div>
			<?php } else { ?>
	<div class="ad-unit-square">

<div id="ad-unit-flex1AC"></div>
<script type="text/javascript">
flex1AC=fnTktWP('a'+'1',300,250,1,'ajax',nc);
document.getElementById('ad-unit-flex1AC').innerHTML = flex1AC;
</script>
</div>
<?php } ?>
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