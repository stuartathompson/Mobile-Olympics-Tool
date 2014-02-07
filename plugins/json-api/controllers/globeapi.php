<?php

class JSON_API_GlobeAPI_Controller {

  public function globe_api() {
      global $json_api;

	  // Make sure we have key/value query vars
	  if (!$json_api->query->key || !$json_api->query->value) {
	    $json_api->error("Include a 'key' and 'value' query var.");
	  }

	
	  // See also: http://codex.wordpress.org/Template_Tags/query_posts
	  $getposts = get_posts(array(
	    'meta_key' => $json_api->query->key,
	    'meta_value' => $json_api->query->value,
	    'showposts' => 10,
	    'orderby' => 'post_date',
	    'order' => 'DESC'
	  ));

		  
	    return array(
		    'key' => $json_api->query->key,
    		'value' => $json_api->query->value,
	    	'posts' => $getposts
  		);
//	  return $getposts;

  }

}

?>
