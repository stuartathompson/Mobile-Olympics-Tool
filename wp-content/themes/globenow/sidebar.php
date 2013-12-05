<!-- sidebar -->
<aside class="sidebar" role="complementary">
	<p><img src="<?php bloginfo('template_url'); ?>/img/ad.png" /></p>
	
	<?php //get_template_part('searchform'); ?>
    		
	<div class="sidebar-widget">
		<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-1')) ?>
	</div>
	
	<div class="sidebar-widget">
		<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('widget-area-2')) ?>
	</div>
		
</aside>
<!-- /sidebar -->