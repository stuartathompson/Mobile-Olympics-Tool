<?php
/**
 * Plugin Name: Fix Twitter Oembed
*/
 
class FixTwitterOembed {
	private static $twitter_oembed_regex = '#https?://(www\.)?twitter\.com/.+?/status(es)?/.*#i';
	public static function init() {
		self::add_actions();
	}
 
	public static function add_actions() {
		add_filter( 'oembed_providers', array( __CLASS__, 'fix_twitter_oembed' ) );
	}
 
	public static function fix_twitter_oembed( $providers ) {
		if ( isset( $providers[self::$twitter_oembed_regex] ) ) {
			$providers[self::$twitter_oembed_regex][0] = 'https://api.twitter.com/1/statuses/oembed.{format}';
		}
 
		return $providers;
	}
}
 
FixTwitterOembed::init();