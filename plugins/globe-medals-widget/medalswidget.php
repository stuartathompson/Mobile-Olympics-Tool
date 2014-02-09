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
 	<div class="globe-medals-showmore"><a href="<?php bloginfo('url'); ?>/medals">Show All Medals</a></div>
	</div>
	
<?php 
}

function medals_widget_create_front_end_full(){
?>
	<!-- place these with the rest of your CSS & JS -->
	
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

<?php 
}

function create_medals_twitter_front_end(){
# Alasdair McKie - Feb 5 2014 - Read in a JSON and spit out the Top 5 countries

# Config
$jsonUrl = "http://mapi.sochi2014.com/v1/en/olympic/medal/rating";
$userAgent = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:26.0) Gecko/20100101 Firefox/26.0";
$logoUrl = "http://on.theglobeandmail.com/wp-content/plugins/globe-medals-widget/medal-widget-header.png";
$headerUrl = "http://on.theglobeandmail.com/wp-content/plugins/globe-medals-widget/medal-widget-banner.png";
date_default_timezone_set('America/Toronto');

# Set up the params we need to pass in the HTTP session
$options = array('http' => array('user_agent' => $userAgent));
$context = stream_context_create($options);

# Load the file and parse it into an array
$jsonData = json_decode(file_get_contents($jsonUrl,false,$context),true);

# Find the countries we care about and copy the data in a new keyed array called top5
$canadaFound = false;

for ($i = 0; $i < 5; $i++) {
  $country = $jsonData[$i];
  $top5[$i] = $country;
  if ($country[name] == 'Canada') {
    $canadaFound = true;
  }
}

if (!($canadaFound)) {
  while (($i < count($jsonData)) && (!($canadaFound))) {
    $country = $jsonData[$i];
    if ($country[name] == 'Canada') {
      $top5[5] = $country;
      $canadaFound = true;
    }
    $i++;
  }
}

# Spit out the standing stuff with a couple of values filled in on the fly
?>
<meta name="twitter:site" content="@GlobeOlympics"/>
<meta name="twitter:card" content="medalcount"/>
<meta name="twitter:dateprefix" content="as of" />
<meta name="twitter:date" content="<?php echo date('h:ia F jS', time()) ?>"/>
<meta name="twitter:image0" content="<?php echo $logoUrl ?>"/>
<meta name="twitter:image0:width" content="640"/>
<meta name="twitter:image0:height" content="135"/>
<meta name="twitter:image1" content="<?php echo $headerUrl ?>"/>
<meta name="twitter:image1:width" content="640"/>
<meta name="twitter:image1:height" content="59"/>
<?php

# Spit out the meta tags for the top 5
for ($i=0; $i<5; $i++) {
 $thisCountry = $top5[$i];
 $thisNum = ($i + 1);

 echo "<meta name=\"twitter:country" . $thisNum . "\" content=\"" . $thisCountry['name'] . "\"/>\n";
 echo "<meta name=\"twitter:country" . $thisNum . ":gold\" content=\"" . $thisCountry['gold'] . "\"/>\n";
 echo "<meta name=\"twitter:country" . $thisNum . ":silver\" content=\"" . $thisCountry['silver'] . "\"/>\n";
 echo "<meta name=\"twitter:country" . $thisNum . ":bronze\" content=\"" . $thisCountry['bronze'] . "\"/>\n";
 echo "<meta name=\"twitter:country" . $thisNum . ":total\" content=\"" . $thisCountry['total'] . "\"/>\n";
}

# If Canada isn't in the Top 5, we need to append Canada's results
if (isset($top5[5])) {
 $thisCountry = $top5[5];
 echo "<meta name=\"twitter:country_add" . "\" content=\"" . $thisCountry['name'] . "\"/>\n";
 echo "<meta name=\"twitter:country_add" . ":gold\" content=\"" . $thisCountry['gold'] . "\"/>\n";
 echo "<meta name=\"twitter:country_add" . ":silver\" content=\"" . $thisCountry['silver'] . "\"/>\n";
 echo "<meta name=\"twitter:country_add" . ":bronze\" content=\"" . $thisCountry['bronze'] . "\"/>\n";
 echo "<meta name=\"twitter:country_add" . ":total\" content=\"" . $thisCountry['total'] . "\"/>\n";
}


}