<?php get_header(); ?>
	
	<!-- section -->
	<section class="globeabout" role="main">
	
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
	<article class="globeabout-lede">
	<div style="text-align:center"><img src="http://on.theglobeandmail.com/wp-content/uploads/2014/02/pathicon.png" width="95px" style="width:95px !important;margin:0 auto;" /></div>
 		<h1 style="font-size:30px;margin-top:15px">Sochi medal count</h1>
		<p>Keep track of Canada's place in the medal rankings throughout the Games.</p>
		<p>This table will be updated live every time a medal is awarded.	<p>Scroll <strong>below the medal count</strong> for stories about Canada's medals.</p>
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
			<?php } ?>
	<article id="page-medals">
			<?php if(function_exists('globe_social_share')) globe_social_share(); ?>
		<div class="clearfloat"></div>	
			<?php if(function_exists('medals_widget_create_front_end_full')) medals_widget_create_front_end_full(); ?>
		
	</article>
	
 <?php if(!$isMobile) { ?>
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
	
	<h3 class="moreposts">Stories about Canada's medals</h3>
	
	<?php
	
		/* - Get related medal posts - */

		$postCount = 2;

		$args = array(
			'tag'=>'medal',
			'paged'=>$paged,
	 		'post_status'=>'publish',
			'posts_per_page'=>5
		);
	
		query_posts( $args );

		if (have_posts()): while (have_posts()) : the_post();

		include(locate_template('loop.php'));
	
		endwhile;

		endif;
	
	?>
	
	<div class="single-loadmore"><a href="<?php bloginfo('url'); ?>?tags=medals"><img width="25px" height="25px" src="http://olympics.theglobeandmail.com/wp-content/themes/globenow-tiles/img/logo-leaf.png?!" /><br /><br />View all posts about Canada's medals »</a></div>


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