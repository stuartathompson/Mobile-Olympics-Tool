<?php
   /*
   Plugin Name: Globe Medals Widget
   Plugin URI: http://globeandmail.com
   Description: Olympic medals count via Reuters
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */
add_action( 'wp_enqueue_scripts', 'medals_widget_scripts' );

function medals_widget_scripts(){
	wp_enqueue_script('globe_medals_widget',plugin_dir_url( __FILE__ ) . 'medalswidget.js',array( 'jquery','modernizr'));
	wp_enqueue_style('globe_medals_widget',plugin_dir_url( __FILE__ ) . 'medalswidget.css');
}

function medals_widget_create_front_end(){
?>
<div class="globe-medals-widget">
		<div class="globe-medals-nav">
			<div class="globe-medals-count">
				<table class="globe-medals-table"><tr><td>CAN</td><td class="globe-medals-gold"><span class="globe-medals-medal"></span> <span class="globe-medals-num">0</span></td><td class="globe-medals-silver"><span class="globe-medals-medal"></span> <span class="globe-medals-num">0</span></td><td class="globe-medals-bronze"><span class="globe-medals-medal"></span> <span class="globe-medals-num">0</span></td></tr></table>
			</div>
			<div class="globe-medals-plusminus">+ Show top medal counts</div>
		</div>
		<div class="clearfloat"></div>
	<!-- place these with the rest of your CSS & JS -->
	<script>
		window.REUTERS_OLYMPICS_DISCIPLINE_URL = "https://olyadmin.reuters.com/sports/:discipline";
		window.REUTERS_OLYMPICS_EVENT_URL = "https://olyadmin.reuters.com/event/:event";
		window.REUTERS_OLYMPICS_ATHLETE_URL = "https://olyadmin.reuters.com/athlete/:athlete";
		window.REUTERS_OLYMPICS_COUNTRY_URL = "https://olyadmin.reuters.com/team/:country";
		window.REUTERS_OLYMPICS_HOCKEY_EVENT_URL = "";
	</script>
	<script src="http://oly2014-prod.s3.amazonaws.com/compiled/js/reutersOlympics.min.js"></script>
	<script src="http://oly2014-prod.s3.amazonaws.com/dict/en-gb_globeandmail.com.js"></script>
	<link href="http://oly2014-prod.s3.amazonaws.com/compiled/css/app.min.css" rel="stylesheet"/>

<!-- place this in your page where you want the module to appear -->
	<div id="tr_results_connection_076a560ba4f3"></div>
	<script type="text/javascript">
	$(document).ready(function(){
		var tr_results_connection_076a560ba4f3 = new Reuters.Olympics.View.ResultsConnection({
			"highlight": "CAN",
			"el": "#tr_results_connection_076a560ba4f3",
			"sort": "total"
		});
	});
	</script>
	</div>
	
<?php 
}