<?php
   /*
   Plugin Name: Globe Tags Sidebar Widget
   Plugin URI: http://globeandmail.com
   Description: Creates a widget so you can display a group of posts by tag
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */
   
add_action('widgets_init',
	create_function('', 'return register_widget("Globe_Tags_Widget");')
);
wp_enqueue_style('tags_widget',plugin_dir_url( __FILE__ ) . 'tagwidget.css');

class Globe_Tags_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'globe_tags_widget', // Base ID
			__('Sidebar Tags Widget', 'text_domain'), // Name
			array( 'description' => __( 'A widget to show the most liked posts', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$tags = apply_filters( 'widget_tags', $instance['tags'] );

		echo $args['before_widget'];
		if ( ! empty( $tags ) )
			echo $args['before_title'] . '<a href="' . get_bloginfo('url') . '/tag/' . $tags . '">' . $tags . ' »</a>' . $args['after_title'];
			$myarrgs = array(
				'numberposts' => 4, //get all posts, or set a number here
				'offset' => 0,
				'tag' => $tags,
				'order' => 'DESC', //You can order by ASC (ascending) or DESC (descending)
				'post_type' => 'post', //got a custom post type? Put it here
				'post_status' => 'publish'
			);
			$results = get_posts( $myarrgs );
			$i = 0;
			foreach($results as $result){
				$attachArgs = array(
					'numberposts' => 1,
					'post_type' => 'attachment',
					'post_parent' => $result->ID
				);
				
				$attachments = get_posts($attachArgs);
				echo '<div class="sidebar-article-image post-' . $result->ID;
				if($i%2) echo ' sidebar-article-right';
				echo '"><a href="' . get_permalink($result->ID) . '">';
				$likes = get_post_meta($result->ID,'userlike_likes',true);
				if($likes == '' ) $likes = 0;
				if($attachments){
					foreach($attachments as $attachment){
						echo wp_get_attachment_image($attachment->ID,'small');
					}
				}
				echo '<h4>' . $result->post_title . ' <span><img src="' . get_bloginfo('template_url') . '/img/sidebar-star.png" /><span class="user-like-count">' . $likes . '</span></span></h4></a></div>';
				$i++;
			}
			
			echo $args['after_widget'];
	
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$tags = apply_filters( 'widget_tags', $instance['tags'] );
		
		?>
		<p>
		
<label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e( 'Tags:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'tags' ); ?>" name="<?php echo $this->get_field_name( 'tags' ); ?>" type="text" value="<?php echo esc_attr( $tags ); ?>" />

		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['tags'] = ( ! empty( $new_instance['tags'] ) ) ? strip_tags( $new_instance['tags'] ) : '';

		return $instance;
	}

} // class Foo_Widget
