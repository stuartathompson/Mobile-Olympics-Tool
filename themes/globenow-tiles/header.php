<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
		
		<!-- dns prefetch -->
		<link href="//www.google-analytics.com" rel="dns-prefetch">
		
		<!-- meta -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width,initial-scale=1.0">
		<?php if(function_exists('is_mobile') && is_mobile()){ ?><meta id="viewport" name="viewport" content ="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" /><?php } ?>
		<meta name="description" content="<?php bloginfo('description'); ?>">
		
		<!-- icons -->
		<link rel="shortcut icon" type="image/x-icon" href="http://beta.images.theglobeandmail.com/media/www/images/flag/favicon.ico">
		<link rel="apple-touch-icon-precomposed" media="screen and (resolution: 163dpi)" href="http://beta.images.theglobeandmail.com/media/mobile/images/iphone.icon.png">
		<link rel="apple-touch-icon-precomposed" media="screen and (resolution: 132dpi)" href="http://beta.images.theglobeandmail.com/media/mobile/images/ipad.icon.png">
		<link rel="apple-touch-icon-precomposed" media="screen and (resolution: 326dpi)" href="http://beta.images.theglobeandmail.com/media/mobile/images/iphone.icon.highres.png">
		<!-- <link rel="stylesheet" type="text/css" media="screen" href="http://beta.images.theglobeandmail.com/bundle-css/gzip_1716237129/www-css-core-composite-section.css" /> -->
			
		<!-- css + javascript -->
		<?php wp_head(); ?>
		<script>
		!function(){
			// configure legacy, retina, touch requirements @ conditionizr.com
			conditionizr()
		}()
		</script>
		<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
		<link href="http://fonts.googleapis.com/css?family=Oswald:400,300" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="http://beta.images.theglobeandmail.com/media/www/css/global.fonts.css">
				
		
<!-- Ad units -->
<!-- Start: GPT Sync -->
<script type='text/javascript'>
	var gptadslots=[];
	(function(){
		var useSSL = 'https:' == document.location.protocol;
		var src = (useSSL ? 'https:' : 'http:') + '//www.googletagservices.com/tag/js/gpt.js';
		document.write('<scr' + 'ipt src="' + src + '"></scr' + 'ipt>');
	})();
</script>
<script type="text/javascript">
var adId1 = 'div-gpt-ad-311805903592310440-1',
	adId2 = 'div-gpt-ad-311805903592310440-2';
		//Adslot 1 declaration
		gptadslots[1]= googletag.defineSlot('/58/theglobeandmail.com/sports/olympics', [[728,90],[960,90]],adId1).setTargeting('ptf',['gpt']).setTargeting('mode',['wp']).setTargeting('adpg',['olympics']).setTargeting('arena',['olympics','sports']).setTargeting('pos',['ldbd']).setTargeting('loc',['n']).addService(googletag.pubads());
		//Adslot 2 declaration
		gptadslots[2]= googletag.defineSlot('/58/theglobeandmail.com/sports/olympics', [[300,250]],adId2).setTargeting('ptf',['gpt']).setTargeting('mode',['wp']).setTargeting('adpg',['olympics']).setTargeting('arena',['olympics','sports']).setTargeting('pos',['boxr']).setTargeting('loc',['n']).addService(googletag.pubads());

function displayAds( call ) {
	googletag.pubads().enableSingleRequest();
	googletag.pubads().enableSyncRendering();
	googletag.enableServices();
	if ( call ) {
		$('#' + adId2).append('<scrip' + 't>googletag.display(adId2)</scrip' + 't>');
	}
};

displayAds(false);

</script>
<!-- End: GPT -->


	</head>
	<body <?php body_class(); ?>>
	
		<!-- wrapper -->
		<div class="hide-overflow">
		<div class="wrapper">
			<div id="mobile-header">
				<div id="mobile-header-wrapper">
				<?php if(function_exists('ajax_tags_create_front_end') && (function_exists('is_moboile') && is_mobile()) ) { ?><a href="#menu" id="mobile-header-menu"><img src="<?php bloginfo('template_url'); ?>/img/menu-icon-retina.png"></a><?php } ?>
				<a href="http://www.theglobeandmail.com"><img id="mobile-header-logo" src="<?php bloginfo('template_url'); ?>/img/globelogo.png"></a> <a id="header-olympics-home" href="<?php bloginfo('url'); ?>"> 2014 Winter Olympics</a>
				<a id="menu-search-button" href="#search"><img id="mobile-header-search" src="<?php bloginfo('template_url'); ?>/img/search-icon-retina.png"></a>
				</div>
			</div>
			<div id="search-box">
				<?php get_search_form(); ?>
			</div>
		
			
		<div class="strip small"></div>

		<div id="new-alert-container">
			<a href="#loadnew" id="new-alert">
				<span id="new-alert-number">0</span> <span id="new-alert-text">new update</span>
			</a>
		</div>

		<?php if(function_exists('medals_widget_create_front_end')) medals_widget_create_front_end(); ?>


		<?php 
			// Get filter bar for all but single, searrch pages
			if(function_exists('ajax_tags_create_front_end')) ajax_tags_create_front_end(); 
		?>