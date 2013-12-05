<?php get_header(); ?>

	<!-- section -->
	<section role="main">
	
		<!-- article -->
		<article id="post-<?php the_ID(); ?>" class="fourohfour">
		
    <h2 class="lede">Oops! That page isn’t here.</h2>
    <p>We couldn’t find the page you were looking for one of the following reasons:<br>
        <strong>It has moved</strong>, <strong>it never existed</strong>, or an agreement<br>
        with an outside provider <strong>has expired</strong>.
    </p>
    <div class="suggestions">
        <div class="quickies">
            <p>Return to our <a href="<?php bloginfo('url'); ?>">Olympics coverage</a> or to <a href="http://www.theglobeandmail.com">globeandmail.com</a></div>
    </div>

		</article>
		<!-- /article -->
		
	</section>
	<!-- /section -->
	
<?php //get_sidebar(); ?>

<?php get_footer(); ?>