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
		<?php if(is_mobile()){ ?><meta id="viewport" name="viewport" content ="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" /><?php } ?>
		<meta name="description" content="<?php bloginfo('description'); ?>">
		
		<!-- icons -->
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
		<link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
			
		<!-- css + javascript -->
		<?php wp_head(); ?>
		<script>
		!function(){
			// configure legacy, retina, touch requirements @ conditionizr.com
			conditionizr()
		}()
		</script>
		<link href="http://fonts.googleapis.com/css?family=Oswald:400,300" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="http://beta.images.theglobeandmail.com/media/www/css/global.fonts.css">
	</head>
	<body <?php body_class(); ?>>
	
		<!-- wrapper -->
		<div class="hide-overflow">
		<div class="wrapper">
			<?php if(is_mobile()){
?>
			<?php
			} else {
				if(function_exists('globe_header')) globe_header();
			}			
			?>
			<div id="mobile-header">
				<a href="#menu" id="mobile-header-menu"><img src="<?php bloginfo('template_url'); ?>/img/menu-icon.png"></a>
				<a href="http://www.theglobeandmail.com"><img id="mobile-header-logo" src="<?php bloginfo('template_url'); ?>/img/theglobeandmail.png"></a>
			
				<?php if(function_exists('ajax_refresh_front_end') && is_home()) ajax_refresh_front_end(); ?>
			</div>
			
		<div class="strip small"></div>
		<h3 id="home-nav"><a href="<?php bloginfo('url'); ?>">Globe Olympics</a></h3>