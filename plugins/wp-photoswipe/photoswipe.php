<?php
/*
Plugin Name: Photoswipe
Version: 1.1
Plugin URI: http://www.vtardia.com/photoswipe/
Author: Vito Tardia
Author URI: http://www.vtardia.com
Description: Add Photoswipe effects to your galleries in 5 seconds

	Copyright (c) 2011, Vito Tardia.

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/
/**
 * Main PhotoSwipe plugin class
 * 
 * All methods are used statically
 * 
 * @version 1.1
 */
class PhotoSwipe {
	
	public static $prefix = 'psw_';

	public static $device = 'desktop';

	/**
	 * Initialize the plugin
	 */
	public static function init() {
		add_action('wp_enqueue_scripts', array('PhotoSwipe', 'load'));
		add_action('init', array('PhotoSwipe', 'mdetect'));
	} // end function
	
	/**
	 * Loads Photoswipe JS & CSS components
	 */
	public static function load() {
	
		// Photoswipe libraries
		wp_enqueue_script('psw-klass', plugins_url('/lib/photoswipe/lib/klass.min.js', __FILE__), array('jquery'), '3.0.5', true);
		wp_enqueue_script('psw-jquery', plugins_url('/lib/photoswipe/code.photoswipe-3.0.5.min.js', __FILE__), array('jquery', 'psw-klass'), '3.0.5', true);

		// Photoswipe JS hook
		wp_enqueue_script('psw-call', plugins_url('/lib/psw.call.js', __FILE__), array('jquery', 'psw-jquery'), '1.0', true);
		
		// Photoswipe style
		wp_enqueue_style('psw-css', plugins_url('/lib/photoswipe/photoswipe.css', __FILE__));
	
	} // end function
    
    /**
     * Loads MDetect library
     */
    public static function mdetect() {
        if (!class_exists('uagent_info')) {
            require_once dirname(__FILE__) . '/lib/mdetect/mdetect.php';
            $agent = new uagent_info();
            $is_mobile = $agent->DetectMobileQuick();
            $is_tablet = $agent->DetectTierTablet();
            
            if (true == $is_mobile) {
                PhotoSwipe::$device = 'mobile';
            } elseif (true == $is_tablet) {
                PhotoSwipe::$device = 'tablet';
            } else {
                PhotoSwipe::$device = 'desktop';
            }
            add_action('wp_head', array('PhotoSwipe', 'wp_head'));
        }
    }
    
    /**
     * Prints the header JS with the device type
     */
    public static function wp_head() {
        printf("<script>window.device = '%s';</script>\n", PhotoSwipe::$device);
    }

} // end class

/// MAIN----------------------------------------------------------------------

add_action('plugins_loaded', array('PhotoSwipe', 'init'));
