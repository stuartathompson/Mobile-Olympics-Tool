<?php get_header(); ?>
	
	<!-- section -->
	<section class="globeabout" role="main">
	
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
		<!-- article -->
		<article class="globeabout-lede">
		<h1>Sochi 2014</h1>
		<p>The Globe's mobile Olympic site delivers the news you need to know throughout the Sochi Games in a way that's easy to digest on the go.
		<p>Expect breaking news and medal results, snap analysis and features from our team of reporters in Russia, great video and photography, and highlights every morning.
		<p>Here are a few tips on how to get you started.</p>
	</article>

	<article id="globeabout-tags" class="globeabout-item">
		<div class="globeabout-right">
			<h2>Choose the sports that matter to you</h2>
			<p>Just looking for news about hockey and sliding sports? No problem. Tap the section icon at the top of the screen and select the sports you're most interested in.<p>You can also filter the feed at any time by tapping on a tag within a post.</p>
		</div>
		<div class="globeabout-left globeabout-big">
			<video width="257px" height="400px" autoplay loop>
				<source src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone1.mp4" type="video/mp4" />
				<source src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone1.ogv" type="video/ogg" />
			<img src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone1.png?1" />
			</video>
		</div>

	</article>

	<article id="globeabout-bigmoments" class="globeabout-item">
		<div class="globeabout-left">
			<h2>Catch up on the biggest moments</h2>
			<p>Need to get up to speed of the big events and surprises of the past day or two? Tap on "big moments" at the top of the screen to get just the top news and daily recaps.</p>
			<p>You combine this with your favourite sports to see the biggest moments from your favourite events.</p>
		</div>
		<div class="globeabout-right globeabout-big">
			<video width="257px" height="400px" autoplay loop>
				<source src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone2.mp4" type="video/mp4" />
				<source src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone2.ogv" type="video/ogg" />
			<img src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone2.png?1" />
			</video>
		</div>
	</article>
	
	<article id="globeabout-alert" class="globeabout-item">
		<div class="globeabout-left" style="margin-left:50px;width:40%">
			<img src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone3.png?1" />
		</div>
		<div class="globeabout-right">
			<h2>Never miss a medal</h2>
			<p>Download our Globe News app for your iPhone or iPad to get alerts every time Canada wins a medal.</p>
			<p><a href="https://itunes.apple.com/us/app/the-globe-and-mail-news/id429228415?mt=8&uo=4" target="itunes_store"style="display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/htmlResources/assets/en_us//images/web/linkmaker/badge_appstore-lrg.png) no-repeat;width:135px;height:40px;@media only screen{background-image:url(https://linkmaker.itunes.apple.com/htmlResources/assets/en_us//images/web/linkmaker/badge_appstore-lrg.svg);}"></a></p>
		</div>
	</article>

	<article class="globeabout-item">
		<div class="globeabout-full">
			<h2>Our team in Sochi</h2>
			<p>Have any feedback or questions? Don't hesitate to get in touch via email: mobile@globeandmail.com or on Twitter @globeolympics</p>
		</div>
		<div class="globeabout-full">
		</div>

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