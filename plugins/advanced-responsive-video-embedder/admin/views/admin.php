<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Advanced_Responsive_Video_Embedder
 * @author    Nicolas Jonas
 * @license   GPL-3.0+
 * @link      http://nextgenthemes.com
 * @copyright 2013 Nicolas Jonas
 */

$options = get_option( 'arve_options', array() );

?>
<div class="wrap">

	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<?php
	$message = sprintf(
		__( 'It is always nice when people show their appreciation for a plugin by <a href="%s" target="_blank">testing, contributing</a> or <a href="%s" target="_blank">donating</a>. Thank you!', $this->plugin_slug ),
		'http://nextgenthemes.com/plugins/advanced-responsive-video-embedder/contribute/',
		'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=UNDSCARF3ZPBC'
	);

	echo '<div class="updated"><p><big>' . $message . '</big></p></div>';
	?>

	<form method="post" action="options.php">
		<?php settings_fields('arve_plugin_options'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Default Mode:</th>
				<td>
					<select id="arve_options[mode]" name="arve_options[mode]" size="1">
					  <option<?php selected( $options['mode'], 'normal'); ?> value="normal"><?php _e('Normal', $this->plugin_slug ); ?></option>
					  <option<?php selected( $options['mode'], 'thumbnail'); ?> value="thumbnail"><?php _e('Thumbnail', $this->plugin_slug); ?></option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="video_maxwidth"><?php _e('Video Maximal Width', $this->plugin_slug); ?></label></th>
				<td>
					<input id="arve_options[video_maxwidth]" name="arve_options[video_maxwidth]" type="text" value="<?php echo $options['video_maxwidth'] ?>" class="small-text">px<br>
					<span class='description'><?php _e('Optional, if not set your videos will be the maximum size of the container they are in. If your content area has a big width you might want to set this.', $this->plugin_slug); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="arve_options[align_width]"><?php _e('Aligned Maximal Width (Normal Mode)', $this->plugin_slug); ?></label></label></th>
				<td>
					<input id="arve_options[align_width]" name="arve_options[align_width]" type="text" value="<?php echo $options['align_width'] ?>" class="small-text">px<br>
					<span class="description"><?php _e('Needed! Must be 200+ to work.', $this->plugin_slug); ?></span>
				</td>
			</tr>			
			<tr valign="top">
				<th scope="row"><label for="arve_options[thumb_width]"><?php _e('Thumbnail Maximal Width', $this->plugin_slug); ?></label></label></th>
				<td>
					<input id="arve_options[thumb_width]" name="arve_options[thumb_width]" type="text" value="<?php echo $options['thumb_width'] ?>" class="small-text">px<br>
					<span class="description"><?php _e('Needed! Must be 50+ to work.', $this->plugin_slug); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="fakethumb"><?php _e('Fake Thumbnails', $this->plugin_slug); ?></label></th>
				<td>
					<input id="arve_options[fakethumb]" name="arve_options[fakethumb]" type="checkbox" value="1" <?php checked( 1, $options['fakethumb'] ); ?> /><br>
					<span class='description'><?php _e('Loads the actual Videoplayer as "background image" to for thumbnails to emulate the feature Youtube, Dailymotion, and Bliptv have. If not enabled thumbnails are displayed black or you can choose a image below.', $this->plugin_slug); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="custom_thumb_image"><?php _e('Custom Thumbnail Image', $this->plugin_slug); ?></label></th>
				<td>
					<input id="arve_options[custom_thumb_image]" name="arve_options[custom_thumb_image]" type="text" value="<?php echo $options['custom_thumb_image'] ?>" class="large-text"><br>
					<span class='description'><?php _e('To be used instead of black background. Upload a 16:10 Image with a size bigger or equal the thumbnials size you want to use into your WordPress and paste the URL of it here.', $this->plugin_slug); ?></span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="autoplay"><?php _e('Autoplay all', $this->plugin_slug); ?></label></th>
				<td>
					<input id="arve_options[autoplay]" name="arve_options[autoplay]" type="checkbox" value="1" <?php checked( 1, $options['autoplay'] ); ?> /><br>
				</td>
			</tr>
			<tr>
				<th>
					<?php submit_button( __('Reset options', $this->plugin_slug), 'secondary', 'arve_options[reset]' ); ?>
				</th>
				<td>
					<?php submit_button(); ?>
				</td>
			</tr>
		</table>
		
		<h3><?php _e('Change shortcode tags', $this->plugin_slug); ?></h3>
		<p>
			<?php _e('You might need this to prevent conflicts with other plugins you want to use. At least 3 alphanumec characters with optional underscores are needed!', $this->plugin_slug); ?>
		</p>
		
		<table class="form-table">
			<?php
			
			foreach ( $options['shortcodes'] as $provider => $shortcode ) {
				
				?>
				<tr valign="top">
					<th scope="row">
						<?php printf( '<label for="arve_options[shortcodes][%s]">%s</label>', $provider, ucwords( $provider ) . ' tag' ); ?>
					</th>
					<td>
						<?php printf( '<input type="text" id="arve_options[shortcodes][%s]" name="arve_options[shortcodes][%s]" value="%s">', $provider, $provider, $shortcode ); ?>
					</td>
				</tr>
				<?php
			}
			
			?>

			<tr>
				<th></th>
				<td>
					<?php submit_button(); ?>
				</td>
			</tr>
		</table>
	</form>
</div>