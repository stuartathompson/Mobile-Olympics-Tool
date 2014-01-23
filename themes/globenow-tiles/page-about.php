<?php get_header(); ?>
	
	<!-- section -->
	<section class="globeabout" role="main">
	
	
	<?php if (have_posts()): while (have_posts()) : the_post(); ?>
	
		<!-- article -->
		<article class="globeabout-lede">
		<h1><img src="<?php bloginfo('template_url'); ?>/img/sochilogo.png" /></h1>
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
			<img width="290px" src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone1.png" />
		</div>

	</article>

	<article id="globeabout-bigmoments" class="globeabout-item">
		<div class="globeabout-left">
			<h2>Catch up on the biggest moments</h2>
			<p>Need to get up to speed of the big events and surprises of the past day or two? Tap on "big moments" at the top of the screen to get just the top news and daily recaps.</p>
			<p>You can combine this with your favourite sports to see just the biggest moments from the events you care about most.</p>
		</div>
		<div class="globeabout-right globeabout-big">
			<img width="290px" src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone2.png" />
		</div>
	</article>
	
	<article id="globeabout-alert" class="globeabout-item">
		<div class="globeabout-right">
			<h2>Never miss a medal</h2>
			<p>Download our Globe News app for your iPhone or iPad to get alerts every time Canada wins a medal.</p>
			<p><a href="https://itunes.apple.com/us/app/the-globe-and-mail-news/id429228415?mt=8&uo=4" target="itunes_store"style="display:inline-block;overflow:hidden;background:url(https://linkmaker.itunes.apple.com/htmlResources/assets/en_us//images/web/linkmaker/badge_appstore-lrg.png) no-repeat;width:135px;height:40px;@media only screen{background-image:url(https://linkmaker.itunes.apple.com/htmlResources/assets/en_us//images/web/linkmaker/badge_appstore-lrg.svg);}"></a></p>
		</div>
				<div class="globeabout-left" style="margin-left:50px;width:40%">
			<img src="<?php bloginfo('template_url'); ?>/img/globeabout-assets/phone3.png?1" />
		</div>
	</article>

	<article class="globeabout-item">
		<div class="globeabout-full">
			<h2>Our team in Sochi</h2>
		<div id="about-authors">
			<div class="about-author clearfix">
			<img src="http://beta.images.theglobeandmail.com/97e/sports/hockey/article4244058.ece/ALTERNATES/w140/macgregor09sp10.JPG" width="140"
height="78"/>
			<div class="about-author_bio">
			<h3>Roy MacGregor</h3>
			<div class="email">
			<a href="mailto:rmacgregor@globeandmail.com">E-mail this writer</a>
			</div>
			<p>Hockey columnist</p>
			<p>Follow Roy on Twitter
			<a href="http://twitter.com/#!/RoyMacG"
			target="_blank">@RoyMacG</a></p>
			</div>
			</div>
			<div class="about-author clearfix">
			<img src="http://beta.images.theglobeandmail.com/ede/migration_catalog/article4042494.ece/ALTERNATES/w140/MM_lehmannBIOpic" width="140"
			height="78"/>
			<div class="about-author_bio">
			<h3>John Lehmann</h3>
			<div class="email">
			<a href="mailto:JLehmann@globenandmail.com">E-mail this writer</a>
			</div>
			<p>Photographer</p>
			<p>Follow John on Twitter
			<a href="http://twitter.com/#!/johnlehmann"
			target="_blank">@johnlehmann</a></p>
			</div>
			</div>
			<div class="about-author clearfix">
			<img src="http://beta.images.theglobeandmail.com/606/incoming/article849839.ece/ALTERNATES/w140/mackinnon-edit_253397a" width="140"
			height="78"/>
			<div class="about-author_bio">
			<h3>Mark MacKinnon</h3>
			<div class="email">
			<a href="mailto:mmackinnon@globeandmail.com">E-mail this writer</a>
			</div>
			<p>International affairs correspondent</p>
			<p>Follow Mark on Twitter
			<a href="http://twitter.com/#!/markmackinnon"
			target="_blank">@markmackinnon</a></p>
			</div>
			</div>
			<div class="about-author clearfix">
			<img src="http://beta.images.theglobeandmail.com/1b0/incoming/article4255855.ece/ALTERNATES/w140/duha.jpg" width="140"
			height="78"/>
			<div class="about-author_bio">
			<h3>Eric Duhatschek</h3>
			<div class="email">
			<a href="mailto:eduhatschek@globeandmail.com">E-mail this writer</a>
			</div>
			<p>Hockey columnist</p>
			<p>Follow Eric on Twitter
			<a href="http://twitter.com/#!/eduhatschek"
			target="_blank">@eduhatschek</a></p>
			</div>
			</div>
			<div class="about-author clearfix">
			<img src="http://beta.images.theglobeandmail.com/b24/incoming/article4503698.ece/ALTERNATES/w140/reguly.jpg" width="140"
			height="78"/>
			<div class="about-author_bio">
			<h3>Eric Reguly</h3>
			<div class="email">
			<a href="mailto:ereguly@globeandmail.com">E-mail this writer</a>
			</div>
			<p>European correspondent</p>
			<p>Follow Eric on Twitter
			<a href="http://twitter.com/#!/ereguly"
			target="_blank">@ereguly</a></p>
			</div>
			</div>
			<div class="about-author clearfix">
			<img src="http://beta.images.theglobeandmail.com/2fe/incoming/article849430.ece/ALTERNATES/w140/James+Mirtie" width="140"
			height="78"/>
			<div class="about-author_bio">
			<h3>James Mirtle</h3>
			<div class="email">
			<a href="mailto:jmirtle@globeandmail.com">E-mail this writer</a>
			</div>
			<p>Hockey writer</p>
			<p>Follow James on Twitter
			<a href="http://twitter.com/#!/mirtle"
			target="_blank">@mirtle</a></p>
			</div>
			</div>
			<div class="about-author clearfix">
			<img src="http://beta.images.theglobeandmail.com/7bc/incoming/article4217272.ece/ALTERNATES/w140/Paul_Waldie_876889cl-3.jpg" width="140"
			height="78"/>
			<div class="about-author_bio">
			<h3>Paul Waldie</h3>
			<div class="email">
			<a href="mailto:pwaldie@globeandmail.com">E-mail this writer</a>
			</div>
			<p>London correspondent</p>
			<p>Follow Paul on Twitter
			<a href="http://twitter.com/#!/PwaldieGLOBE"
			target="_blank">@PwaldieGLOBE</a></p>
			</div>
			</div>
			<div class="about-author clearfix">
			<img src="http://beta.images.theglobeandmail.com/318/incoming/article4216853.ece/ALTERNATES/w140/Grant_Robertson_876940cl-3.jpg" width="140"
			height="78"/>
			<div class="about-author_bio">
			<h3>Grant Robertson</h3>
			<div class="email">
			<a href="mailto:grobertson@globeandmail.com">E-mail this writer</a>
			</div>
			</div>
</div>
			<hr>
			<p>Have any feedback or questions? Don't hesitate to get in touch via email: <a href="mailto:mobile@globeandmail.com">mobile@globeandmail.com</a> or on Twitter <a href="http://www.twitter.com/globeolympics">@globeolympics</a></p>
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