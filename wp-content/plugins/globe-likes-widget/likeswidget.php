<?php
   /*
   Plugin Name: Globe User Likes Widget
   Plugin URI: http://globeandmail.com
   Description: Creates a widget so you can display a number of most liked posts
   Version: 0.1
   Author: Stuart A. Thompson
   Author URI: http://www.theglobeandmail.com
   License: GPL2
   */
   
add_action('widgets_init',
	create_function('', 'return register_widget("Globe_User_Likes");')
);
wp_enqueue_style('likes_widget',plugin_dir_url( __FILE__ ) . 'likeswidget.css');

class Globe_User_Likes extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'globe_user_likes', // Base ID
			__('Most Liked Posts', 'text_domain'), // Name
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
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $args['before_widget'];
		if ( ! empty( $title ) )
			echo $args['before_title'] . $title . $args['after_title'];
			$myarrgs = array(
				'orderby' => 'title',
				'oderby' => 'DESC',
				'post_type' => 'post',
				'meta_key' => 'userlike_likes',
			);
			echo '<ol>';
			$results = query_posts( $myarrgs );
			echo $results->query;
			if ( have_posts() ) : while ( have_posts() ) : the_post();
				$likes = get_post_meta(get_the_ID(),'userlike_likes',true);
				if($likes == '') $likes = 0;
				echo '<li class="post-' . get_the_ID() . '"><a href="' . get_permalink() . '">' . get_the_title() . ' <span><img src="' . get_bloginfo('template_url') . '/img/sidebar-star.png" /><span class="user-like-count">' . $likes . '</span></span></a></li>';

			endwhile;
			endif;
			echo '</ol>';
			
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
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Most liked posts', 'text_domain' );
		}
		/*
if ( isset( $instance[ 'post_type' ] ) ) {
			$post_type = $instance[ 'post_type' ];
		}
		else {
			$post_type = __( 'Select a post type', 'text_domain' );
		}
*/
		?>
		<p>
		
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />

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
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}

} // class Foo_Widget
