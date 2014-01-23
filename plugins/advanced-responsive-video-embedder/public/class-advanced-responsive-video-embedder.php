<?php /*

*******************************************************************************

Copyright (C) 2013 Nicolas Jonas
Copyright (C) 2013 Tom Mc Farlin and WP Plugin Boilerplate Contributors

This file is part of Advanced Responsive Video Embedder.

Advanced Responsive Video Embedder is free software: you can redistribute it
and/or modify it under the terms of the GNU General Public License as
published by the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Advanced Responsive Video Embedder is distributed in the hope that it will be
useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General
Public License for more details.

You should have received a copy of the GNU General Public License along with
Advanced Responsive Video Embedder.  If not, see
<http://www.gnu.org/licenses/>.

_  _ ____ _  _ ___ ____ ____ _  _ ___ _  _ ____ _  _ ____ ____  ____ ____ _  _ 
|\ | |___  \/   |  | __ |___ |\ |  |  |__| |___ |\/| |___ [__   |    |  | |\/| 
| \| |___ _/\_  |  |__] |___ | \|  |  |  | |___ |  | |___ ___] .|___ |__| |  | 

*******************************************************************************/

/**
 * Plugin Name.
 *
 * @package   Advanced_Responsive_Video_Embedder
 * @author    Nicolas Jonas
 * @license   GPL-3.0+
 * @link      http://nextgenthemes.com
 * @copyright 2013 Nicolas Jonas
 */

/**
 * Plugin class. This class should ideally be used to work with the
 * public-facing side of the WordPress site.
 *
 * If you're interested in introducing administrative or dashboard
 * functionality, then refer to `class-plugin-name-admin.php`
 *
 * @package Advanced_Responsive_Video_Embedder
 * @author  Nicolas Jonas
 */
class Advanced_Responsive_Video_Embedder {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   2.6.0
	 *
	 * @var     string
	 */
	const VERSION = '3.1.2';

	/**
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'advanced-responsive-video-embedder';

	/**
	 * Instance of this class.
	 *
	 * @since    2.6.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Regular expression for if extraction from url (multiple uses)
	 *
	 * @since    3.0.0
	 *
	 * @var      array
	 */
	protected $regex_list = array();

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since    2.6.0
	 */
	private function __construct() {

		$this->set_regex_list();
		$this->init_options();

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		add_action( 'init', array( $this, 'create_shortcodes' ), 99 );
		add_action( 'init', array( $this, 'create_url_handlers' ), 99 );

		// Activate plugin when new blog is added
		add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_head', array( $this, 'print_styles' ) );

		#add_filter( 'oembed_providers', array( $this, 'remove_wp_default_oembeds' ), 99 );
	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    1.0.0
	 *
	 *@return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return regular expression (for admin class).
	 *
	 * @since    3.0.0
	 *
	 *@return    Plugin slug variable.
	 */
	public function get_regex_list() {
		return $this->regex_list;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     2.6.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    2.6.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    1.0.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    1.0.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    1.0.0
	 */
	private static function single_activate() {

		global $current_user;

		$user_id = $current_user->ID;
		
		delete_user_meta( $user_id, 'arve_ignore_admin_notice' );

	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'assets/js/public.js', __FILE__ ), array( 'jquery', 'colorbox' ), self::VERSION );
	}

	/**
	 * Initialise options by merging possibly existing options with defaults
	 *
	 * @since    2.6.0
	 */
	public function init_options() {

		$defaults = array(
			'mode'                  => 'normal',
			'video_maxwidth'        => '',
			'align_width'           => 400,
			'thumb_width'           => 300,
			'fakethumb'             => true,
			'custom_thumb_image'    => '',
			'autoplay'              => false,
			'shortcodes'            => array(
				'archiveorg'            => 'archiveorg',
				'blip'                  => 'blip',
				'bliptv'                => 'bliptv', //* Deprecated
				'break'                 => 'break',
				'collegehumor'          => 'collegehumor',
				'comedycentral'         => 'comedycentral',
				'dailymotion'           => 'dailymotion',
				'dailymotionlist'       => 'dailymotionlist',
				'flickr'                => 'flickr',
				'funnyordie'            => 'funnyordie',
				'gametrailers'          => 'gametrailers',	
				'iframe'                => 'iframe',
				'ign'                   => 'ign',
				'kickstarter'           => 'kickstarter',
				'liveleak'              => 'liveleak',
				'metacafe'              => 'metacafe',   
				'movieweb'              => 'movieweb',
				'myspace'               => 'myspace',
				'myvideo'               => 'myvideo',
				'snotr'                 => 'snotr',
				'spike'                 => 'spike',
				'ted'                   => 'ted',   
				'ustream'               => 'ustream',
				'veoh'                  => 'veoh',
				'vevo'                  => 'vevo',
				'viddler'               => 'viddler',
				'videojug'              => 'videojug',
				'vimeo'                 => 'vimeo',
				'yahoo'                 => 'yahoo',
				'youtube'               => 'youtube',
				'youtubelist'           => 'youtubelist', //* Deprecated
			)
		);

		$options = get_option( 'arve_options', array() );

		$options               = wp_parse_args( $options, $defaults );
		$options['shortcodes'] = wp_parse_args( $options['shortcodes'], $defaults['shortcodes'] );

		update_option( 'arve_options', $options );
	}

	/**
	 * Create all shortcodes at a late stage because people over and over again using this plugin toghter with jetback or 
	 * other plugins that handle shortcodes we will now overwrite all this suckers.
	 *
	 * @since    2.6.2
	 *
	 * @uses Advanced_Responsive_Video_Embedder_Create_Shortcodes()
	 */
	public function create_shortcodes() {

		$options = get_option( 'arve_options', array() );

		foreach( $options['shortcodes'] as $provider => $shotcode ) {

			${$provider} = new Advanced_Responsive_Video_Embedder_Create_Shortcodes();
			${$provider}->provider = $provider;
			${$provider}->create_shortcode();
		}
	}

	/** 
	 *
	 * @since    3.0.0
	 *
	 */
	public function remove_wp_default_oembeds( $providers ) {

		unset( $providers['#https?://(www\.)?youtube\.com/watch.*#i'           ]);
		unset( $providers['http://youtu.be/*'                                  ]);
		#unset( $providers['http://blip.tv/*'                                   ]);
		unset( $providers['#https?://(www\.)?vimeo\.com/.*#i'                  ]);
		unset( $providers['#https?://(www\.)?dailymotion\.com/.*#i'            ]);
		unset( $providers['http://dai.ly/*'                                    ]);
		#unset( $providers['#https?://(www\.)?flickr\.com/.*#i'                 ]);
		#unset( $providers['http://flic.kr/*'                                   ]);
		#unset( $providers['#https?://(.+\.)?smugmug\.com/.*#i'                 ]);
		#unset( $providers['#https?://(www\.)?hulu\.com/watch/.*#i'             ]);
		unset( $providers['#https?://(www\.)?viddler\.com/.*#i'                ]);
		#unset( $providers['http://qik.com/*'                                   ]);
		#unset( $providers['http://revision3.com/*'                             ]);
		#unset( $providers['http://i*.photobucket.com/albums/*'                 ]);
		#unset( $providers['http://gi*.photobucket.com/groups/*'                ]);
		#unset( $providers['#https?://(www\.)?scribd\.com/.*#i'                 ]);
		#unset( $providers['http://wordpress.tv/*'                              ]);
		#unset( $providers['#https?://(.+\.)?polldaddy\.com/.*#i'               ]);
		unset( $providers['#https?://(www\.)?funnyordie\.com/videos/.*#i'      ]);
		#unset( $providers['#https?://(www\.)?twitter\.com/.+?/status(es)?/.*#i']);
		#unset( $providers['#https?://(www\.)?soundcloud\.com/.*#i'             ]);
		#unset( $providers['#https?://(www\.)?slideshare\.net/*#'               ]);
		#unset( $providers['#http://instagr(\.am|am\.com)/p/.*#i'               ]);
		#unset( $providers['#https?://(www\.)?rdio\.com/.*#i'                   ]);
		#unset( $providers['#https?://rd\.io/x/.*#i'                            ]);
		#unset( $providers['#https?://(open|play)\.spotify\.com/.*#i'           ]);

		#show( $providers );

		return $providers;
	}

	/**
	 *
	 * @since    3.0.0
	 *
	 */
	public function set_regex_list() {

		$hw = 'https?://(?:www\.)?';

		/**
		 * Double hash comment = no id in URL
		 *
		 */
		$this->regex_list = array(
			'archiveorg'          => $hw . 'archive\.org/(?:details|embed)/([0-9a-z]+)',
			'blip'                => $hw . 'blip\.tv/[^/]+/[^/]+-([0-9]{7})',
			##'bliptv'            => 
			'break'               => $hw . 'break\.com/video/(?:[a-z\-]+)-([0-9]+)',
			'collegehumor'        => $hw . 'collegehumor\.com/video/([0-9]+)',
			##'comedycentral'     => 
			'dailymotion_hub'     => $hw . 'dailymotion\.com/hub/' .  '[a-z0-9]+_[a-z0-9_\-]+\#video=([a-z0-9]+)',
			'dailymotionlist'     => $hw . 'dailymotion\.com/playlist/([a-z0-9]+_[a-z0-9_\-]+)',
			'dailymotion'         => $hw . 'dailymotion\.com/video/([^_]+)',
			#'dailymotion_jukebox' => $hw . 'dailymotion\.com/widget/jukebox?list\[\]=%2Fplaylist%2F([a-z0-9]+_[a-z0-9_\-]+)',
			#'flickr'             => 'flickr',
			'funnyordie'          => $hw . 'funnyordie\.com/videos/([a-z0-9_]+)',
			##'gametrailers'      => 
			'ign'                 => '(https?://(?:www\.)?ign\.com/videos/[0-9]{4}/[0-9]{2}/[0-9]{2}/[0-9a-z\-]+)',
			##'iframe'            => 
			'kickstarter'         => $hw . 'kickstarter\.com/projects/([0-9a-z\-]+/[0-9a-z\-]+)',
			'liveleak'            => $hw . 'liveleak\.com/(?:view|ll_embed)\?((f|i)=[0-9a-z\_]+)',
			'metacafe'            => $hw . 'metacafe\.com/(?:watch|fplayer)/([0-9]+)',
			'movieweb'            => $hw . 'movieweb\.com/v/([a-z0-9]{14})',
			'myspace'             => $hw . 'myspace\.com/.+/([0-9]+)',
			'myvideo'             => $hw . 'myvideo\.de/(?:watch|embed)/([0-9]{7})',
			'snotr'               => $hw . 'snotr\.com/(?:video|embed)/([0-9]+)',
			##'spike'             => 
			'ustream'             => $hw . 'ustream\.tv/(?:channel/)?([0-9]{8}|recorded/[0-9]{8}(/highlight/[0-9]+)?)',
			'veoh'                => $hw . 'veoh\.com/watch/([a-z0-9]+)',
			'vevo'                => $hw . 'vevo\.com/watch/[a-z0-9:\-]+/[a-z0-9:\-]+/([a-z0-9]+)',
			'viddler'             => $hw . 'viddler\.com/(?:embed|v)/([a-z0-9]{8})',
			##'videojug'          => 
			'vimeo'               => $hw . 'vimeo\.com/(?:(?:channels/[a-z]+/)|(?:groups/[a-z]+/videos/))?([0-9]+)',
			'yahoo'               => $hw . '(?:screen|shine|omg)\.yahoo\.com/(?:embed/)?([a-z0-9\-]+/[a-z0-9\-]+)\.html',
			'ted'                 => $hw . 'ted\.com/talks/([a-z0-9_]+)',
			'youtubelist'         => $hw . 'youtube\.com/watch\?v=([a-z0-9_\-]{11}&list=[a-z0-9_\-]+)',
			'youtube'             => $hw . 'youtube\.com/watch\?v=([a-z0-9_\-]{11})',
			//* Shorteners
			'youtu_be'            => 'http://youtu.be/([a-z0-9_-]{11})',
			'dai_ly'              => 'http://dai.ly/([^_]+)',
		);
	}

	/**
	 *
	 * @since    3.0.0
	 *
	 */
	public function create_url_handlers() {

		foreach ( $this->get_regex_list() as $provider => $regex ) {
			wp_embed_register_handler( 'arve_' . $provider, '#' . $regex . '#i', array( $this, $provider ) );
		}
		
	}

	/**
	 * Used for callbacks from embed handlers
	 *
	 * @since    3.0.0
	 *
	 */
	function __call( $func, $params ) {

		if( ! array_key_exists( $func, $this->regex_list ) ) {
			wp_die('__call');
		}
		
		switch ( $func ) {
			case 'youtubelist':
			case 'youtu_be':
				$func = 'youtube';
				break;
			case 'dailymotion_hub':
			case 'dai_ly':
				$func = 'dailymotion';
		}

		return $this->url_build_embed( $func, $params[0], $params[1], $params[2], $params[3] );

	}

	/**
	 *
	 * @since    3.0.0
	 *
	 */
	public function url_build_embed( $provider, $matches, $attr, $url, $rawattr ) {

		$id = $matches[1];

		if ( empty( $id ) ) {
			return '<p><strong>Critical ARVE error:</strong> No match, please report this bug';
		}

		//* Fix 'Markdown on save enhanced' issue
		if ( substr( $url, -4 ) === '</p>' ) {
			$url = substr( $url, 0, -4 );
		}
		if ( substr( $id, -4 ) === '</p>' ) {
			$id = substr( $id, 0, -4 );
		}

		$output     = '';
		$parsed_url = parse_url( $url );
		$args       = array();

		if ( ! empty( $parsed_url['query'] ) ) {
			parse_str( $parsed_url['query'], $args );
		}

		foreach ( $args as $key => $value ) {
			$new_key = str_replace( 'arve-', '', $key );
			$args[$new_key] = $value;
			unset( $args[$key] );
		}

		$shortcode_atts = shortcode_atts( array(
			'align'    => '',
			'autoplay' => '',
			#'id'       => '',
			'maxw'     => '',
			'maxwidth' => '',
			'mode'     => '',
			'start'    => '',
			'end'      => '',
			'time'     => '',
		), $args );

		$shortcode_atts['id'] = $id;

		#$output .= showr($url);
		#$output .= showr($id);

		$output .= $this->build_embed( $provider, $shortcode_atts );
		$output .= sprintf( '<a href="%s" class="arve-hidden">%s</a>', esc_url( $url ), esc_html( $url ) );

		return $output;

	}

	/**
	 *
	 *
	 * @since    2.6.0
	 */
	public function build_embed( $provider, $shortcode_atts ) {

		extract( $shortcode_atts );

		//* Remap for backwards compatibility
		if ( ! empty( $maxw ) && empty( $maxwidth ) ) {
			$maxwidth = $maxw;
		}
		$start = $time;

		$options            = get_option('arve_options');
		$output             = '';
		$randid             = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 10);
		$thumbnail          = null;

		$flashvars          = '';
		$flashvars_autoplay = '';

		$url_autoplay_no   = '';
		$url_autoplay_yes  = '';		

		$no_wmode_transparent = array(
			'comedycentral',
			'gametrailers',
			'iframe',
			'movieweb',
			'myvideo',
			'snotr',
			'spike',
			'ustream',
			'viddler'
		);

		$fakethumb = (bool) $options['fakethumb'];

		if ( in_array($provider, $no_wmode_transparent) ) {
			$fakethumb = false;
		}

		$iframe = true;

		if ( in_array( $provider, array( 'flickr', 'veoh', 'vevo' ) ) ) {
			$iframe = false;
		}

		switch ( $id ) {
			case '':
				return "<p><strong>ARVE Error:</strong> no video ID</p>";
				break;
			case ( ! preg_match('/[^\x20-\x7f]/', $id ) ):
				break;
			default:
				return "<p><strong>ARVE Error:</strong> id '$id' not valid.</p>";
				break;
		}

		switch ( $provider ) {
			case '':
				return "<p><strong>ARVE Error:</strong> no provider set";
				break;
			case ( ! preg_match('/[^\x20-\x7f]/', $id ) ):
				break;
			default:
				return "<p><strong>ARVE Error:</strong> provider '$provider' not valid.</p>";
				break;
		}

		switch ( $mode ) {
			case '':
				$mode = $options['mode'];
				break;
			case 'fixed':
				if ( $customsize_inline_css != '' )
					break;
				elseif ( ( $options["video_width"] < 50 ) || ( $options["video_height"] < 50 ) )
					return "<p><strong>ARVE Error:</strong> No sizes for mode 'fixed' in options. Set it up in options or use the shortcode values (w=xxx h=xxx) for this.</p>";
				break;
			case 'thumb':
				$mode = 'thumbnail';
			case 'normal':
			case 'thumbnail':
			case 'special':
				break;
			default:
				return "<p><strong>ARVE Error:</strong> mode '$mode' not valid.</p>";
				break;
		}

		$maxwidth = str_replace( 'px', '', $maxwidth );

		switch ( $maxwidth ) {
			case '':
				if ( $options['video_maxwidth'] > 0 )
					$maxwidth_options = true;
				break;
			case ( ! preg_match("/^[0-9]{2,4}$/", $maxwidth) ):
			default:
				return "<p><strong>ARVE Error:</strong> maxwidth (maxw) '$maxwidth' not valid.</p>";
				break;
			case ( $maxwidth > 50 ):
				if ($mode != 'normal')
					return "<p><strong>ARVE Error:</strong> for the maxwidth (maxw) option you need to have normal mode enabled, either for all videos in the plugins options or through shortcode e.g. '[youtube id=your_id <strong>mode=normal</strong> maxw=999 ]'.</p>";
				$maxwidth_shortcode = $maxwidth;
				break;
		}

		switch ( $align ) {
			case '':
				break;
			case 'left':
				$align = "alignleft";
				break;
			case 'right':
				$align = "alignright";
				break;
			case 'center':
				$align = "aligncenter";
				break;
			default:
				return "<p><strong>ARVE Error:</strong> align '$align' not valid.</p>";
				break;
		}

		switch ( $autoplay ) {
			case '':
				$autoplay = (bool) $options['autoplay'];
				break;
			case 'true':
			case '1':
			case 'yes':
				$autoplay = true;
				break;
			case 'false':
			case '0':
			case 'no':
				$autoplay = false;
				break;
			default:
				return "<p><strong>ARVE Error:</strong> Autoplay '$autoplay' not valid.</p>";
				break;
		}

		switch ($start) {
			case '':
			case ( $start > 0 ):
				break;
			case ( ! preg_match("/^[0-9a-z]{1,6}$/", $start) ):
			default:
				return "<p><strong>ARVE Error:</strong> Time '$start' not valid.</p>";
				break;
		}

		switch ($end) {
			case '':
			case ( $end > 0 ):
				break;
			case ( ! preg_match("/^[0-9a-z]{1,6}$/", $end) ):
			default:
				return "<p><strong>ARVE Error:</strong> Time '$end' not valid.</p>";
				break;
		}

		switch ($provider) {
			case 'metacafe':
				$urlcode = 'http://www.metacafe.com/embed/' . $id . '/';
				break;
			case 'liveleak':
				//* For backwards compatibilty and possible mistakes
				if ( $id[0] != 'f' && $id[0] != 'i' ) {
					$id = 'i=' . $id;
				}
				$urlcode = 'http://www.liveleak.com/ll_embed?' . $id . '&wmode=transparent';
				break;
			case 'myspace':
				$urlcode = 'https://myspace.com/play/video/' . $id;
				break;
			case 'blip':
				if ( $blip_xml = simplexml_load_file( 'http://blip.tv/rss/view/' . $id ) ) {
					$blip_result = $blip_xml->xpath( "/rss/channel/item/blip:embedLookup" );
					$id = (string) $blip_result[0];
				} else {
					return '<p><strong>ARVE Error:</strong> could not get Blip.tv thumbnail</p>';
				}
			case 'bliptv':
				$urlcode = 'http://blip.tv/play/' . $id . '.html?p=1&backcolor=0x000000&lightcolor=0xffffff';
				break;
			case 'collegehumor':
				$urlcode = 'http://www.collegehumor.com/e/' . $id;
				break;
			case 'videojug':
				$urlcode = 'http://www.videojug.com/embed/' . $id;
				break;
			case 'veoh':
				$urlcode = 'http://www.veoh.com/swf/webplayer/WebPlayer.swf?version=AFrontend.5.7.0.1396&permalinkId=' . $id . '&player=videodetailsembedded&id=anonymous';
				break;
			case 'break':
				$urlcode = 'http://break.com/embed/' . $id;
				break;
			case 'dailymotion':
				$urlcode = 'http://www.dailymotion.com/embed/video/' . $id . '?logo=0&hideInfos=1&forcedQuality=hq';
				break;
			case 'dailymotionlist':
				$urlcode = 'http://www.dailymotion.com/widget/jukebox?list[]=%2Fplaylist%2F' . $id . '%2F1&skin=default';
				break;
			case 'movieweb':
				$urlcode = 'http://www.movieweb.com/v/' . $id;
				break;
			case 'myvideo':
				$urlcode = 'http://www.myvideo.de/movie/' . $id;
				break;
			case 'vimeo':
				$urlcode = 'http://player.vimeo.com/video/' . $id . '?title=0&byline=0&portrait=0';
				break;
			case 'gametrailers':
				$urlcode = 'http://media.mtvnservices.com/embed/mgid:arc:video:gametrailers.com:' . $id;
				break;
			case 'comedycentral':
				$urlcode = 'http://media.mtvnservices.com/embed/mgid:arc:video:comedycentral.com:' . $id;
				break;
			case 'spike':
				$urlcode = 'http://media.mtvnservices.com/embed/mgid:arc:video:spike.com:' . $id;
				break;
			case 'viddler':
				$urlcode = 'http://www.viddler.com/player/' . $id . '/?f=1&disablebranding=1&wmode=transparent';
				break;
			case 'snotr':
				$urlcode = 'http://www.snotr.com/embed/' . $id;
				break;
			case 'funnyordie':
				$urlcode = 'http://www.funnyordie.com/embed/' . $id;
				break;
			case 'youtube':
				//* If we have a playlist
				#if ( strpos( $id, '&list=' ) !== false ) {
				#	$id = str_replace( '&list=', '?list=', $id );
				#	$urlcode = '//www.youtube.com/embed/' . $id;
				#} else {
				#	$urlcode = '//www.youtube-nocookie.com/embed/' . $id;
				#}
				$id = str_replace( '&list=', '?list=', $id );
				$urlcode = '//www.youtube-nocookie.com/embed/' . $id;
				$urlcode = add_query_arg( array(
					'autohide'       => 1,
					'hd'             => 1,
					'iv_load_policy' => 3,
					'modestbranding' => 1,
					'rel'            => 0,
					'wmode'          => 'transparent',
				), $urlcode );
				break;
			case 'youtubelist': //* DEPRICATED
				$urlcode = 'http://www.youtube-nocookie.com/embed/videoseries?list=' . $id . '&wmode=transparent&rel=0&autohide=1&hd=1&iv_load_policy=3';
				break;
			case 'archiveorg':
				$urlcode = 'http://www.archive.org/embed/' . $id . '/';
				break;
			case 'flickr':
				$urlcode = 'http://www.flickr.com/apps/video/stewart.swf?v=109786';
				$flashvars = '<param name="flashvars" value="intl_lang=en-us&photo_secret=9da70ced92&photo_id=' . $id . '"></param>';
				break;
			case 'ustream':
				$urlcode = 'http://www.ustream.tv/embed/' . $id . '?v=3&wmode=transparent';
				break;
			case 'yahoo':
				$id = str_replace( array( 'screen.yahoo,com/', 'screen.yahoo.com/embed/' ), '', $id );
				$urlcode = 'http://screen.yahoo.com/embed/' . $id . '.html';
				break;
			case 'vevo':
				$urlcode = 'http://videoplayer.vevo.com/embed/Embedded?videoId=' . $id;
				$urlcode = add_query_arg( array(
					'playlist'       => 'false',
					'playerType'     => 'embedded',
					#'playerId'       => '62FF0A5C-0D9E-4AC1-AF04-1D9E97EE3961',
					'env'            => 0,
					#'cultureName'    => 'en-US',
					#'cultureIsRTL'   => 'False',
				), $urlcode );
				break;
			case 'ted':
				$urlcode = 'http://embed.ted.com/talks/' . $id . '.html';
				break;
			case 'iframe':
				$urlcode = $id;
				break;
			case 'kickstarter':
				$urlcode = 'http://www.kickstarter.com/projects/' . $id . '/widget/video.html';
				break;
			case 'ign':
				$urlcode = 'http://widgets.ign.com/video/embed/content.html?url=' . $id;
				break;
			default:
				$output .= 'ARVE Error: No provider';
				break;
		}

		switch ($provider) {
			case 'youtube':
			case 'youtubelist':
			case 'vimeo':
			case 'dailymotion':
			case 'dailymotionlist':
			case 'viddler':
			case 'vevo':
				$url_autoplay_no  = add_query_arg( 'autoplay', 0, $urlcode );
				$url_autoplay_yes = add_query_arg( 'autoplay', 1, $urlcode );
				break;
			case 'usrteam':
				$url_autoplay_no  = add_query_arg( 'autoplay', 'false', $urlcode );
				$url_autoplay_yes = add_query_arg( 'autoplay', 'true',  $urlcode );
				break;
			case 'yahoo':
				$url_autoplay_no  = add_query_arg( 'player_autoplay', 'false', $urlcode );
				$url_autoplay_yes = add_query_arg( 'player_autoplay', 'true',  $urlcode );
				break;
			case 'metacafe':
			case 'videojug':
				$url_autoplay_no  = add_query_arg( 'ap', 0, $urlcode );
				$url_autoplay_yes = add_query_arg( 'ap', 1, $urlcode );
				break;
			case 'blip':
			case 'bliptv':
				$url_autoplay_no  = add_query_arg( 'autoStart', 'false', $urlcode );
				$url_autoplay_yes = add_query_arg( 'autoStart', 'true',  $urlcode );
				break;
			case 'veoh':
				$url_autoplay_no  = add_query_arg( 'videoAutoPlay', 0, $urlcode );
				$url_autoplay_yes = add_query_arg( 'videoAutoPlay', 1, $urlcode );
				break;
			case 'snotr':
				$url_autoplay_no  = $urlcode;
				$url_autoplay_yes = add_query_arg( 'autoplay', '', $urlcode );
				break;
			//* Do nothing for providers that to not support autoplay or fail with parameters
			case 'ign':
			case 'ign':
			case 'collegehumor':
				$url_autoplay_no  = $urlcode;
				$url_autpplay_yes = $urlcode;
				break;
			case 'iframe':
			default:
				//* We are spamming all kinds of autoplay parameters here in hope of a effect
				$url_autoplay_no  = add_query_arg( array(
					'ap'               => '0',
					'autoplay'         => '0',
					'autoStart'        => 'false',
					'player_autoStart' => 'false',
				), $urlcode );
				$url_autoplay_yes = add_query_arg( array(
					'ap'               => '1',
					'autoplay'         => '1',
					'autoStart'        => 'true',
					'player_autoStart' => 'true',
				), $urlcode );
			break;
		}

		// Maybe add start-/endtime
		if ( 'youtube' == $provider && ! empty( $start ) ) {
			$url_autoplay_no  = add_query_arg( 'start', $start, $url_autoplay_no  );
			$url_autoplay_yes = add_query_arg( 'start', $start, $url_autoplay_yes );
		}
		if ( 'youtube' == $provider && ! empty( $end ) ) {
			$url_autoplay_no  = add_query_arg( 'start', $end, $url_autoplay_no  );
			$url_autoplay_yes = add_query_arg( 'start', $end, $url_autoplay_yes );
		} elseif ( 'vimeo' == $provider && ! empty( $start ) ) {
			$url_autoplay_no  .= '#t=' . $start;
			$url_autoplay_yes .= '#t=' . $start;
		}

		#$output .= showr($urlcode);

		if ( $iframe == true ) {
			$href = str_replace( 'jukebox?list%5B0%5D', 'jukebox?list[]', esc_url( $url_autoplay_yes ) );
			$fancybox_class = 'fancybox arve_iframe iframe';
			//$href = "#inline_".$randid;
			//$fancybox_class = 'fancybox';	
		} else {
			$href = "#inline_" . $randid;
			$fancybox_class = 'fancybox inline';
		}

		if ( true == $autoplay ) {
			$url_option_autoplay = $url_autoplay_yes;
		} else {
			$url_option_autoplay = $url_autoplay_no;
		}

		if ( $mode == 'normal' ) {

			$output .= sprintf(
				'<div class="arve-wrapper arve-normal-wrapper arve-%s-wrapper %s"%s><div class="arve-embed-container">%s</div></div>',
				esc_attr( $provider ),
				esc_attr( $align ),
				( isset( $maxwidth_shortcode ) ) ? sprintf( ' style="max-width: %spx;"', (int) $maxwidth_shortcode ) : '',
				( $iframe ) ? $this->create_iframe( $url_option_autoplay ) : $this->create_object( $url_option_autoplay, $flashvars, $flashvars_autoplay )
			);

		} elseif ( $mode == 'thumbnail' ) {

			switch ($provider) {
				case 'youtube':

					$thumbnail = 'http://img.youtube.com/vi/' . $id . '/0.jpg';
					break;

				case 'vimeo':

					if ( $vimeo_hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . $id . '.php')) ) {
						$thumbnail = (string) $vimeo_hash[0]['thumbnail_large'];
					} else {
						return "<p><strong>ARVE Error:</strong> could not get Vimeo thumbnail";
					}
					break;
	
				case 'blip':
				case 'bliptv':

					if ( $blip_xml = simplexml_load_file( "http://blip.tv/players/episode/$id?skin=rss" ) ) {
						$blip_result = $blip_xml->xpath( "/rss/channel/item/media:thumbnail/@url" );
						$thumbnail = (string) $blip_result[0]['url'];
					} else {
						return '<p><strong>ARVE Error:</strong> could not get Blip.tv thumbnail</p>';
					}
					break;

				case 'dailymotion':

					$thumbnail = 'http://www.dailymotion.com/thumbnail/video/' . $id;
					break;

				case 'dailymotionlist':

					$dayli_api = file_get_contents( 'https://api.dailymotion.com/playlist/' . $id . '?fields=thumbnail_large_url' );
					$dayli_api = json_decode( $dayli_api, true );

					$thumbnail = (string) $dayli_api['thumbnail_large_url'];

					if ( empty( $thumbnail ) ) {
						return "<p><strong>ARVE Error:</strong> could not get Thumbnail for this dailymotion playlist";
					}

					break;
			}
			
			$thumb_bg = '';

			if ( $thumbnail ) {
				$thumb_bg = sprintf( ' style="background-image: url(%s);"', esc_url( $thumbnail ) );
			}
			elseif ( ! empty( $options['custom_thumb_image'] ) ) {
				$thumb_bg = sprintf( ' style="background-image: url(%s);"', esc_url( $options['custom_thumb_image'] ) );
			}

			$output .= sprintf( '<div class="arve-wrapper arve-thumb-wrapper arve-%s-wrapper %s"%s>', esc_attr( $provider ), esc_attr( $align ), $thumb_bg );
			$output .= '<div class="arve-embed-container">';

			//* if we not have a real thumbnail by now and fakethumb is enabled
			if ( ! $thumbnail && $fakethumb ) {

				if ( $iframe )
					$output .= $this->create_iframe( $url_autoplay_no  );
				else
					$output .= $this->create_object( $url_autoplay_no , $flashvars, '' );

				$output .= "<a href='$href' class='arve-inner $fancybox_class'>&nbsp;</a>";

			} else {
				$output .= "<a href='$href' class='arve-inner arve-play-background $fancybox_class'>&nbsp;</a>";
			}
			
			$output .= '</div>'; //* end arve-embed-container
			$output .= '</div>'; //* end arve-thumb-wrapper
			
			if ( $iframe == false )
				$output .= '<div class="arve-hidden">' . $this->create_object( $url_autoplay_yes, $flashvars, $flashvars_autoplay, $randid ) . '</div>';
		}

		return $output;
	}

	/**
	 * 
	 *
	 * @since    2.6.0
	 */
	public function create_object( $url, $flashvars = '', $flashvars_autoplay = '', $id = false ) {

		if ( $id ) {
			$class_or_id = "id='inline_$id' class='arve-hidden-obj'";
		}
		else {
			$class_or_id = 'class="arve-inner"';
		}

		return
			'<object ' . $class_or_id . ' data="' . esc_url( $url ) . '" type="application/x-shockwave-flash">' .
				'<param name="movie" value="' . esc_url( $url ) . '" />' .
				'<param name="quality" value="high" />' .
				'<param name="wmode" value="transparent" />' .
				'<param name="allowFullScreen" value="true" />' .
				$flashvars .
				$flashvars_autoplay .
			'</object>';
	}

	/**
	 * 
	 *
	 * @since    2.6.0
	 */
	public function create_iframe( $url ) {

		//* Fix escaped brackets we don't want escaped for dailymotion playlists
		$url = str_replace( 'jukebox?list%5B0%5D', 'jukebox?list[]', esc_url( $url ) );

		return '<iframe class="arve-inner" src="' . $url . '" frameborder="0" scrolling="no" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';

	}

	/**
	 * 
	 *
	 * @since    3.0.0
	 */
	public function create_html5fullscreen( $url ) {
		return '';
	}

	/**
	 * Print variable CSS
	 *
	 * @since    2.6.0
	 */
	public function print_styles() {

		$options  = get_option('arve_options');
		$maxwidth = (int) $options["video_maxwidth"];

		$css = sprintf( '.arve-thumb-wrapper { width: %spx; }', (int) $options['thumb_width'] );

		if ( $maxwidth > 0 ) {
			$css .= sprintf( '.arve-normal-wrapper { width: %spx; }', $maxwidth );
		}

		//* Fallback if no width is set neither with options nor with shortcode (inline CSS)
		$css .= sprintf(
			'.arve-normal-wrapper.alignleft, ' .
			'.arve-normal-wrapper.alignright, ' . 
			'.arve-normal-wrapper.aligncenter { width: %spx; }', 
			(int) $options['align_width']
		);

		echo '<style type="text/css">' . $css . "</style>\n";
	}

}