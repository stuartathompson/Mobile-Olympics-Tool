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
		<div class="date-container">
			<div class="date">
			<div class="datedate">
			<?php
				$posttags = get_the_tags();
				if($posttype=='breaking'){
					echo '<span class="glyphicon glyphicon-exclamation-sign"></span> <span>Breaking</span>';
				} else {
					if($posttype == 'fullwidth'){
						$glyphicon = 'glyphicon-camera';
					} else {
						$glyphicon = 'glyphicon-pencil';
					}
					echo '<span class="glyphicon ' . $glyphicon . '"></span>';
					if($posttags){
						$i = 0;
						foreach($posttags as $posttag){
							if($i == 0)	echo '<span>' . $posttag->name . '</span>';	
							$i++;
						}
					}
				}
			?>
			</div>
			<div class="datetime">
			<?php 
				$deskdiff = human_time_diff( get_the_time('U'), current_time('timestamp') );
				$mobidiff = human_time_diff( get_the_time('U'), current_time('timestamp') );
				$replace = array(
		        	'mins' => 'minutes'
    			);
    			echo '<a href="' . get_permalink() . '" title="' . get_the_title() . '">' . strtr($deskdiff,$replace) . ' ago' . '</a>';
			?>
			</div>
			</div>
			
			<!-- social -->
			<?php if(function_exists('globe_social_share')) globe_social_share(); ?>
			<div class="clearfloat"></div>
		</div>

		<div class="post-content">

		<!-- post title -->
		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
			<?php
				the_title();
			?>
			</a>
		</h2>
		
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
		
		<?php //edit_post_link(); ?>

		</div><!-- .post-content -->
	
	</article>

	<?php 
		// Mobile ad
		if($postCount==1){	?>
		<!-- Beginning Sync AdSlot 2 for Ad unit theglobeandmail.com/sports/olympics ### size: [[300,250]]  -->
		
		<div class="ad-unit-square" id='div-gpt-ad-311805903592310440-2'>
			<script type='text/javascript'>
				googletag.display('div-gpt-ad-311805903592310440-2');
			</script>
		</div>

		<!-- End AdSlot 2 -->
		<?php }	?>

	<?php $postCount++; ?>
	
	<?php endif; // exclude ?>
