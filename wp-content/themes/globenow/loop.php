<!--
<div id="new-alert-container">
	<a href="#loadnew" id="new-alert">
		<img src="<?php //bloginfo('template_url'); ?>/img/plus.png?1" /> <span>1</span> new update
	</a>
</div>
-->
	<!-- article -->
	<?php 
		// Exclude post ID
		if($exclude!=get_the_ID()):
		
		// Odd/even and post type
		$oddEven = '';
		if($postCount%2) $oddEven = 'odd';
		$posttype = str_replace(' ', '', get_post_meta(get_the_ID(),'posttype',true));
		$classes = array(
			$oddEven,
			$posttype
		);
		if(isset($extra_classes)) array_push($classes,$extra_classes);
	?>
	<article id="post-<?php the_ID(); ?>" <?php post_class( $classes ); ?>>
		<!-- post details -->
		<div class="date">
			<?php 
				$deskdiff = human_time_diff( get_the_time('U'), current_time('timestamp') );
				$mobidiff = human_time_diff( get_the_time('U'), current_time('timestamp') );
				$replace = array(
		        	'mins' => 'minutes'
    			);
    			echo strtr($deskdiff,$replace) . ' ago';
			?>
		</div>
			
		<!-- post title -->
		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php
				if($posttype=='breaking'){
					echo '<span>Breaking</span><br />';
				}
				the_title();
			?>
			</a>
		</h2>
		
		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail(array(120,120)); // Declare pixel size you need inside the array ?>
			</a>
		<?php endif; ?>

		<!-- post content -->
		<?php 
			$byline = get_post_meta(get_the_ID(), 'globefields_byline',true);
			if($byline) {
		?>
			<div class="byline">
				<?php 
				echo $byline;
				if(get_post_meta(get_the_ID(),'globefields_placeline',true) != '') echo '<span class="placeline"> — ' . get_post_meta(get_the_ID(),'globefields_placeline',true) . '</span>';
				?>
			</div>
		<?php } ?>
		<div class="content">
			<?php the_content(); // Build your custom callback length in functions.php ?>
		</div>
		
		<!-- Tags -->
		<?php if(function_exists(globe_tags())) globe_tags(); ?>
		
		<!-- social -->
		<?php if(function_exists('globe_social_share')) globe_social_share(); ?>
		
		<?php //edit_post_link(); ?>
		
	
	</article>

	<?php 
		// Mobile ad
		if($postCount==1){
			echo '<article class="mobile-ad-unit"><a href="http://www.walmart.ca"><img src="' . get_bloginfo('template_url') . '/img/mobile-ad.jpg" /></a></article>';
		}
	?>

	<?php $postCount++; ?>
	
	<?php endif; // exclude ?>
