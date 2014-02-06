	<!-- article -->
	<?php 
		// Exclude post ID
		if($exclude!=get_the_ID()):
		
		// Odd/even and post type
		$oddEven = '';
		if($postCount%2) $oddEven = 'odd';
		$posttype = str_replace(' ', '', get_post_meta(get_the_ID(),'posttype',true));
		if($posttype=='breaking' && current_time('timestamp')-get_the_time('U') > 1200) $posttype = 'newsarticle';
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
				if($posttype=='breaking' && current_time('timestamp')-get_the_time('U') < 1200){
					echo '<a href="' . get_bloginfo('url') . '/tag/breaking" title="Filter by tag: Breaking"><span class="glyphicon glyphicon-exclamation-sign"></span> <span>Breaking</span></a>';
				} else {
					if($posttype == 'fullwidth'){
						$glyphicon = 'glyphicon-camera';
					} else {
						$glyphicon = 'glyphicon-pencil';
					}
					if($posttags){
						$i = 0;
						$showfirst = get_post_meta($post->ID, 'tagtype-showfirst', true);
						$showfirst = $showfirst[0];
						foreach($posttags as $posttag){
							// Check to see which is selected tag to show
							if($posttag->name == $showfirst){
								echo '<a href="' . get_bloginfo('url') . '?tags=' . str_replace(' ','-',$posttag->name) . '" title="Filter by tag: ' . str_replace('-',' ',$posttag->name) . '">' . '<span class="glyphicon ' . $glyphicon . '"></span>' . '<span>' . str_replace('-',' ',$posttag->name). '</a></span>';	
								$i++;
							}	
						}
						if($i==0){
							$j = 0;
							foreach($posttags as $posttag){
								if($j==0) echo '<a href="' . get_bloginfo('url') . '?tags=' . str_replace(' ','-',$posttag->name) . '" title="Filter by tag: ' . str_replace('-',' ',$posttag->name) . '">' . '<span class="glyphicon ' . $glyphicon . '"></span>' . '<span>' . str_replace('-',' ',$posttag->name) . '</a></span>';
								$j++;
							}
						}		
					}
				}
			?>
			</div>
			<div class="datetime">
			<?php 
				$deskdiff = human_time_diff( get_the_time('U'), current_time('timestamp') );
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
		if($postCount==1){	

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

		<?php }	?>

	<?php $postCount++; ?>
	
	<?php endif; // exclude ?>
