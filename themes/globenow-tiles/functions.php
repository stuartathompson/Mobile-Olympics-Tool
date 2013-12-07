<?php
/*
 *  Author: Todd Motto | @toddmotto
 *  URL: html5blank.com | @html5blank
 *  Custom functions, support, custom post types and more.
 */

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 620, 350, true); // Large Thumbnail
    add_image_size('medium', 220, 124, true); // Medium Thumbnail
    add_image_size('small', 140, 79, true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');
    add_image_size('native', 620, '', true);

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('html5blank', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// Load HTML5 Blank scripts (header.php)
function html5blank_header_scripts()
{
    if (!is_admin()) {
    
    	wp_deregister_script('jquery'); // Deregister WordPress jQuery
    	wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js', array(), '1.9.1'); // Google CDN jQuery
    	wp_enqueue_script('jquery'); // Enqueue it!
    	
    	wp_register_script('conditionizr', 'http://cdnjs.cloudflare.com/ajax/libs/conditionizr.js/2.2.0/conditionizr.min.js', array(), '2.2.0'); // Conditionizr
        wp_enqueue_script('conditionizr'); // Enqueue it!
        
        wp_register_script('modernizr', 'http://cdnjs.cloudflare.com/ajax/libs/modernizr/2.6.2/modernizr.min.js', array(), '2.6.2'); // Modernizr
        wp_enqueue_script('modernizr'); // Enqueue it!
        
        wp_register_script('html5blankscripts', get_template_directory_uri() . '/js/scripts.js', array(), '1.0.0'); // Custom scripts
        wp_enqueue_script('html5blankscripts'); // Enqueue it!

        wp_register_script('jqueryui', get_template_directory_uri() . '/js/jquery-ui.min.js', array(), '1.0.0'); // Custom scripts
        wp_enqueue_script('jqueryui'); // Enqueue it!

		// Bootstrap
		wp_register_script('bootstrap', "//netdna.bootstrapcdn.com/bootstrap/3.0.2/js/bootstrap.min.js", array(), '1.0.0');
        wp_enqueue_script('bootstrap'); // Enqueue it!
	
		// jQuery libraries
		wp_register_script('jquerycookie', "//cdn.jsdelivr.net/jquery.cookie/1.4.0/jquery.cookie.min.js", array(), '1.0.0');
        wp_enqueue_script('jquerycookie'); // Enqueue it!
	
		// jQuery mobile
		wp_register_script('jquerymobile','http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.js',array(),'1.0');
        wp_enqueue_script('jquerycookie'); // Enqueue it!
        
        // jQuery Waypoints
		wp_register_script('jquerywaypoints','//cdn.jsdelivr.net/jquery.waypoints/2.0.2/waypoints.min.js',array(),'1.0');
        wp_enqueue_script('jquerywaypoints'); // Enqueue it!
        
    }
}

// Load HTML5 Blank styles
function html5blank_styles()
{

	wp_register_style('bootstrapcss', "//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap.min.css",'all');

    wp_enqueue_style('bootstrapcss'); // Enqueue it!

	wp_register_style('bootstraptheme', "//netdna.bootstrapcdn.com/bootstrap/3.0.2/css/bootstrap-theme.min.css",'all');

    wp_enqueue_style('bootstraptheme'); // Enqueue it!
    
    wp_register_style('normalize', get_template_directory_uri() . '/normalize.css', array(), '1.0', 'all');
    wp_enqueue_style('normalize'); // Enqueue it!
    
    wp_register_style('html5blank', get_template_directory_uri() . '/style.css', array(), '1.0', 'all');
    wp_enqueue_style('html5blank'); // Enqueue it!

    wp_register_style('jqueryuicss', get_template_directory_uri() . '/css/jquery-ui.css', array(), '1.0', 'all');
    wp_enqueue_style('jqueryuicss'); // Enqueue it!

	wp_register_style('jquerymobilecss','http://code.jquery.com/mobile/1.3.2/jquery.mobile-1.3.2.min.css',array(),'1.0','all');

}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'html5blank'),
        'description' => __('Description for this widget-area...', 'html5blank'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}
// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'html5blank_header_scripts'); // Add Custom Scripts to wp_head
//add_action('wp_print_scripts', 'html5blank_conditional_scripts'); // Add Conditional Page Scripts
add_action('wp_enqueue_scripts', 'html5blank_styles'); // Add Theme Stylesheet
//add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu
//add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Globe share bar
function globe_social_share(){
?>
		<div class="gig-button-container">
			<div title="Share on Twitter" class="tw share-reaction-icon"><a href="https://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" rel="<?php the_title(); ?>"><img src="http://beta.images.theglobeandmail.com/static/ROB/interactives/crisis/images/tw20over.png" /></a></div>
			<div title="Share on Facebook" class="fb share-reaction-icon"><a href="http://www.facebook.com/share.php?u=<?php the_permalink(); ?>"><img src="http://beta.images.theglobeandmail.com/static/ROB/interactives/crisis/images/fb20over.png" /></a></div>
			<div title="Email" class="email share-reaction-icon"><a href="mailto:?subject=<?php the_title(); ?>&body=<?php the_title(); ?> - <?php the_permalink() ?>"><img src="http://beta.images.theglobeandmail.com/static/national/timetolead/wealth/images/em-share-50b.png" /></a></div>
			<?php if(function_exists('userlike_create_front_end')) userlike_create_front_end(); ?>
		</div>
<?php
}

// Globe header iframe
function globe_header(){
?>
	
<?php 
}

// Globe footer iframe
function globe_footer(){
?>
	
<?php
}

// Globe tags
function globe_tags(){
	echo '<span class="tags">';
	$posttags = get_the_tags();
	if ($posttags) {
	  foreach($posttags as $tag) {
	    echo '<span rel="tooltip" data-filter="' . $tag->slug . '" class="tag noselect' . $ajaxTrigger . '" data-original-title="" title=""><a href="' . get_bloginfo('url') .'/tag/' . $tag->slug . '">'. $tag->name . '</a></span>';
	  }
	}
	echo '</span>';
}

// Configure the loop if tags query attached
if(isset($_GET['tags'])) add_action( 'pre_get_posts', 'tags_pre_loop' );
function tags_pre_loop($query){
	if ( $query->is_main_query() ) {
        $query->set( 'tag', $_GET['tags'] );
    }
}

// Tracking
function globe_external_assets($args = 'development'){
// production varibales
$s_account = 'bellgmpnewprod';

// Development variables
if($args = 'development'){
	$s_account = 'bellgmpgpsdev';
}

// Sports for use in filters
$acceptedFilters = array(
	"Hockey",
	"Alpine skiing",
	"Biathlon",
	"Bobsleigh",
	"Cross-country skiing",
	"Curling",
	"Figure skating",
	"Freestyle skiing",
	"Luge",
	"Nordic combined",
	"Short track speed skating",
	"Skeleton",
	"Ski jumping",
	"Speed skating"
);
?>
<!-- Begin SiteCatalyst code -->
<script type="text/javascript">
var s_account="<?php echo $s_account; ?>";
</script>
<script type="text/javascript" src="http://beta.images.theglobeandmail.com/media/www/js/plugins/s_code.js"></script>
<!-- prod is: http://beta.images.theglobeandmail.com/media/www/js/plugins/s_code.js -->
<script type="text/javascript">
<!--// SiteCatalyst code version: H.17. Copyright 1997-2008 Omniture, Inc. More info available at http://www.omniture.com -->
/* standard channel vars */
s.pageName = "sports:olympics:blog:<?php if(is_single()){ echo 'article'; } else { echo 'section'; } ?>" // +PAGENAME?; // Page
s.channel = "sports"; // Site section
s.prop12 = "<?php if(is_single()) the_title(); ?>"; // Content Title (headline)
s.prop16 = ""; // Content ID (article ID)
s.prop42 = ""; // Content Type (active article tab)
s.prop37 = ""; // article type or blog section
s.prop43 = "sports"; // Sub-section 1
s.prop44 = "sports:olympics"; // Sub-section 2
s.prop45 = "sports:olympics:blog"; // Sub-section 3
s.prop46 = s.prop45; // Sub-section 4
s.prop47 = s.prop45; // Sub-section 5
s.prop22 = s.prop47; // Sub-section 6
s.prop23 = s.prop45; // Sub-section 7
s.prop36 = "sec"; // section type
s.prop41 = "Olympic blog"; // partner/sponsor name
//try {
// s.campaign = $.query.get('cid'); // $.query script not on page if this fails
//} catch (e) {}
/************* DO NOT ALTER ANYTHING BELOW THIS LINE ! **************/
var s_code=s.t();if(s_code)document.write(s_code)//--></script>
<script language="JavaScript" type="text/javascript"><!--
if(navigator.appVersion.indexOf('MSIE')>=0)document.write(unescape('%3C')+'\!-'+'-')
//--></script><!--/DO NOT REMOVE/-->
<!-- End SiteCatalyst code version: H.17. -->



<!-- Begin comScore Tag -->
<script>
document.write(unescape("%3Cscript src='" + (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js' %3E%3C/script%3E"));
</script>
<script>
COMSCORE.beacon({
c1:2,
c2:3005664,
c3:"",
c4:"",
c5:"",
c6:"",
c15:""
});
</script>
<noscript>
<img src="http://b.scorecardresearch.com/p?c1=2&c2=3005664&c3=&c4=&c5=&c6=&c15=&cj=1" />
</noscript>
<!-- End comScore Tag -->

<?php
}

?>
