			<!-- footer -->
			<footer class="footer" role="contentinfo">
			<?php if(!is_mobile()){
?>
				<?php if(function_exists('globe_footer')) globe_footer(); ?>
			<?php } ?>
			</footer>
			<!-- /footer -->
		
		</div>
		<!-- /wrapper -->
		</div>
		<!-- /hide-overflow -->
		
		<!-- Mobile menu -->
		<div id="mobile-menu" class="wrapper">
			<?php if(function_exists('ajax_tags_create_mobile_front_end')) ajax_tags_create_mobile_front_end(); ?>
		</div>
		
		<?php wp_footer(); ?>
		<?php if(function_exists('globe_external_assets')) globe_external_assets(); ?>
		<!-- analytics -->
		<script>
			var _gaq=[['_setAccount','UA-XXXXXXXX-XX'],['_trackPageview']];
			(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
			g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
			s.parentNode.insertBefore(g,s)})(document,'script');
		</script>
	
	</body>
</html>