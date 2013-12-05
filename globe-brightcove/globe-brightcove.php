<?php
   /*
   Plugin Name: Globe Brightcove
   Plugin URI: http://globeandmail.com
   Description: Creates Brightcove videos based on shortcodes
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */

add_shortcode( 'brightcove', 'globe_brightcove' );

function globe_brightcove($atts, $content = null){
?>
	<!-- Start of Brightcove Player -->

<div style="display:none">

</div>

<!--
By use of this code snippet, I agree to the Brightcove Publisher T and C 
found at https://accounts.brightcove.com/en/terms-and-conditions/. 
-->

<script language="JavaScript" type="text/javascript" src="http://admin.brightcove.com/js/BrightcoveExperiences.js"></script>

<object id="myExperience<?php echo $content; ?>" class="BrightcoveExperience">
  <param name="bgcolor" value="#FFFFFF" />
  <param name="width" value="525" />
  <param name="height" value="394" />
  <param name="playerID" value="<?php echo $content; ?>" />
  <param name="playerKey" value="AQ~~,AAACK2Fg-zk~,VTtcUnDPiU0jzvpfc5BE2_H8tiArMUsQ" />
  <param name="isVid" value="true" />
  <param name="isUI" value="true" />
  <param name="dynamicStreaming" value="true" />
  
  <param name="@videoPlayer" value="<?php echo $content; ?>" />
</object>

<!-- 
This script tag will cause the Brightcove Players defined above it to be created as soon
as the line is read by the browser. If you wish to have the player instantiated only after
the rest of the HTML is processed and the page load is complete, remove the line.
-->
<script type="text/javascript">brightcove.createExperiences();</script>

<!-- End of Brightcove Player -->

<?php
}