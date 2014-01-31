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
		<link rel="apple-touch-icon" href="http://beta.images.theglobeandmail.com/static/mobile/sports/sochi-live-icons/touch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="http://beta.images.theglobeandmail.com/static/mobile/sports/sochi-live-icons/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="http://beta.images.theglobeandmail.com/static/mobile/sports/sochi-live-icons/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="http://beta.images.theglobeandmail.com/static/mobile/sports/sochi-live-icons/touch-icon-ipad-retina.png">
		<meta name="apple-mobile-web-app-title" content="Sochi Live"/>	
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
				
		<script type="text/javascript">
var adv_AC="";
var adv_ai=1;
var adv_DblD="http://ad.doubleclick.net";
adv_site="theglobeandmail.com";
var adv_aSize="";
adv_aTl="";
var nc="";

function fnTktWP(aU,aW,aH,aTl,aTp,aId,id){
  if(aId==null){aId=""};
    adv_aSize=aW+"x"+aH;
    adv_aTl=adv_ai;adv_ai=adv_ai+1;

  if(aH==50){
    adv_site="mob.tgam.mobi";
    /* test multi size flex may not be possible with AJAX || adv_aSize=adv_aSize+="300x250,3x3"; */
  }

  /* custom olympics wordpress string */
  adv_ACtbV=adv_DblD+'/adi/'+adv_site+'/sports/olympics/article;;ekw=canada;ekw=sochi2014;ekw=2014olympics;ekw=olympics;ptf=j;pv8=ud;pv7=ud;pv6=n;pv5=n;pv4=n;pv3=n;pv2=sochilive;pv1=n;pv0=n;mode=wp;loc=art;pgsb=n;adpg=olympics;pp2=article;pp1=olympics;pp0=sports;arena=sprt;arena=sports;arena=olympics;arena=homepage;arena=hockey;rgwl=n;rgpc=n;rgpz=n;rgdv=n;rgcg=0;rbx=0;cp0=olympics;ops=n;nc=;kw=n;pos=flex'+adv_aTl+';sz='+adv_aSize+';tile='+adv_aTl+';';

  var adv_ajax_abc=Math.random()+"";adv_ajax_ord=adv_ajax_abc.substring(2,adv_ajax_abc.length);adv_AC=adv_ACtbV+';ord='+adv_ajax_ord+'?';
  /*alert(adv_AC);*/

  adv_AC_ajax_iframe='<iframe src="'+adv_AC+'" width="'+aW+'" height="'+aH+'" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe>';
  /* alert(adv_AC_ajax_iframe); */

  /* CHOOSE 1 return value  || string SRC only */
  //return(adv_AC);

  /* adv_AC_ajax_iframe = iframe tags PLUS src value || enable this and disable return(adv_AC) if needed */
  return(adv_AC_ajax_iframe);

  adv_AC='';
  adv_AC_ajax_iframe='';
  adv_AC_stop=false;
  aPs='';
  sD=adv_site;

}
</script>



	</head>
	<body <?php body_class(); ?>>
	
		<!-- wrapper -->
		<div class="hide-overflow">
		<div class="wrapper">
			<div id="mobile-header">
				<div id="mobile-header-wrapper">
				<?php if(function_exists('ajax_tags_create_front_end')) { ?><a href="#menu" id="mobile-header-menu"><img src="<?php bloginfo('template_url'); ?>/img/menu-icon-retina.png"></a><?php } ?>
				<a href="http://www.theglobeandmail.com"><img id="mobile-header-logo" src="<?php bloginfo('template_url'); ?>/img/globelogo.png"></a> <a id="header-olympics-home" href="<?php bloginfo('url'); ?>"> Sochi Live</a>
				<a id="menu-search-button" href="#search"><img id="mobile-header-search" src="<?php bloginfo('template_url'); ?>/img/search-icon-retina.png"></a>
				<a id="menu-about-button" href="<?php bloginfo('template_url'); ?>/about"><img src="<?php bloginfo('template_url'); ?>/img/globeabout-icon.png" width="14px" height="13px" /></a>
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

		<?php if(function_exists('medals_widget_create_front_end') && !is_page()) medals_widget_create_front_end(); ?>


		<?php 
			// Get filter bar for all but single, searrch pages
			if(function_exists('ajax_tags_create_front_end')) ajax_tags_create_front_end(); 
		?>