<?php

/*
  Plugin Name: Simple Custom Post Order
  Plugin URI: http://hsameer.com.np/simple-custom-post-order/
  Description: Order Items (Posts, Pages, and Custom Post Types) using a Drag and Drop Sortable JavaScript.
  Version: 2.1
  Author: Sameer Humagain
  Author URI: http://hsameer.com.np/
 */


/* ====================================================================
  Define
  ==================================================================== */

define( 'SCPO_URL', plugins_url( '', __FILE__ ) );
define( 'SCPO_DIR', plugin_dir_path( __FILE__ ) );

/* ====================================================================
  Class & Method
  ==================================================================== */

$scporder = new SCPO_Engine();

class SCPO_Engine {

	function __construct() {
		if ( !get_option( 'scporder_options' ) )
			$this->scporder_install();

		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
		add_action( 'admin_init', array( &$this, 'refresh' ) );
		add_action( 'admin_init', array( &$this, 'update_options' ) );
		add_action( 'init', array( &$this, 'enable_objects' ) );

		add_action( 'wp_ajax_update-menu-order', array( &$this, 'update_menu_order' ) );


		add_filter( 'pre_get_posts', array( &$this, 'scporder_filter_active' ) );
		add_filter( 'pre_get_posts', array( &$this, 'scporder_pre_get_posts' ) );


		add_filter( 'get_previous_post_where', array( &$this, 'scporder_previous_post_where' ) );
		add_filter( 'get_previous_post_sort', array( &$this, 'scporder_previous_post_sort' ) );
		add_filter( 'get_next_post_where', array( &$this, 'scporder_next_post_where' ) );
		add_filter( 'get_next_post_sort', array( &$this, 'scporder_next_post_sort' ) );
	}

	function scporder_install() {
		global $wpdb;

		//Initialize Options

		$post_types = get_post_types( array(
			'public' => true
				), 'objects' );

		foreach ( $post_types as $post_type ) {
			$init_objects[] = $post_type->name;
		}
		$input_options = array( 'objects' => $init_objects );

		update_option( 'scporder_options', $input_options );


		// Initialize : menu_order from date_post

		$scporder_options = get_option( 'scporder_options' );
		$objects = $scporder_options['objects'];

		foreach ( $objects as $object ) {
			$sql = "SELECT
						ID
					FROM
						$wpdb->posts
					WHERE
						post_type = '" . $object . "'
						AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
					ORDER BY
						post_date DESC
					";

			$results = $wpdb->get_results( $sql );

			foreach ( $results as $key => $result ) {
				$wpdb->update( $wpdb->posts, array( 'menu_order' => $key + 1 ), array( 'ID' => $result->ID ) );
			}
		}
	}

	function admin_menu() {
		add_options_page( __( 'SCPOrder', 'scporder' ), __( 'SCPOrder', 'scporder' ), 'manage_options', 'scporder-settings', array( &$this, 'admin_page' ) );
	}

	function admin_page() {
		require SCPO_DIR . 'settings.php';
	}

	function enable_objects() {
		$scporder_options = get_option( 'scporder_options' );
		$objects = $scporder_options['objects']; 
		if ( is_array( $objects ) ) {
			$active = false;

			// for Pages or Custom Post Types
			if ( isset( $_GET['post_type'] ) ) {
				if ( in_array( $_GET['post_type'], $objects ) ) {
					$active = true;
				}
				// for Posts
			} else {
				$post_list = strstr( $_SERVER["REQUEST_URI"], 'wp-admin/edit.php' );
				if ( $post_list && in_array( 'post', $objects ) ) {
					$active = true;
				}
			}

			if ( $active ) {
				$this->load_script_css();
			}
		}
	}

	function load_script_css() {
		global $pagenow;
		if ( is_admin() && $pagenow == 'edit-tags.php' )
			return;
		// load JavaScript
		wp_enqueue_script( 'jQuery' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'scporderjs', SCPO_URL . '/assets/scporder.js', array( 'jquery' ), null, true );
		// load CSS
		wp_enqueue_style( 'scporder', SCPO_URL . '/assets/scporder.css', array( ), null );
	}

	function refresh() {

		global $wpdb;

		$scporder_options = get_option( 'scporder_options' );
		$objects = $scporder_options['objects'];

		if ( is_array( $objects ) ) {
			foreach ( $objects as $object ) {
				$sql = "SELECT
							ID
						FROM
							$wpdb->posts
						WHERE
							post_type = '" . $object . "'
							AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
						ORDER BY
							menu_order ASC
						";

				$results = $wpdb->get_results( $sql );

				foreach ( $results as $key => $result ) {
					$wpdb->update( $wpdb->posts, array( 'menu_order' => $key + 1 ), array( 'ID' => $result->ID ) );
				}
			}
		}
	}

	function update_menu_order() {
		global $wpdb;

		parse_str( $_POST['order'], $data );

		if ( is_array( $data ) ) {


			$id_arr = array( );
			foreach ( $data as $key => $values ) {
				foreach ( $values as $position => $id ) {
					$id_arr[] = $id;
				}
			}


			$menu_order_arr = array( );
			foreach ( $id_arr as $key => $id ) {
				$results = $wpdb->get_results( "SELECT menu_order FROM $wpdb->posts WHERE ID = " . $id );
				foreach ( $results as $result ) {
					$menu_order_arr[] = $result->menu_order;
				}
			}

			sort( $menu_order_arr );

			foreach ( $data as $key => $values ) {
				foreach ( $values as $position => $id ) {
					$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order_arr[$position] ), array( 'ID' => $id ) );
				}
			}
		}
	}

	function update_options() {
		if ( isset( $_POST['scporder_submit'] ) ) {

			check_admin_referer( 'nonce_scporder' );

			if ( isset( $_POST['objects'] ) ) {
				$input_options = array( 'objects' => $_POST['objects'] );
			} else {
				$input_options = array( 'objects' => '' );
			}

			update_option( 'scporder_options', $input_options );
			wp_redirect( 'admin.php?page=scporder-settings&msg=update' );
		}
	}

	function scporder_previous_post_where( $where ) {
		global $post;

		$scporder_options = get_option( 'scporder_options' );
		$objects = $scporder_options['objects'];

		if ( in_array( $post->post_type, $objects ) ) {
			$current_menu_order = $post->menu_order;
			$where = "WHERE p.menu_order > '" . $current_menu_order . "' AND p.post_type = '" . $post->post_type . "' AND p.post_status = 'publish'";
		}
		return $where;
	}

	function scporder_previous_post_sort( $orderby ) {
		global $post;

		$scporder_options = get_option( 'scporder_options' );
		$objects = $scporder_options['objects'];

		if ( in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order ASC LIMIT 1';
		}
		return $orderby;
	}

	function scporder_next_post_where( $where ) {
		global $post;

		$scporder_options = get_option( 'scporder_options' );
		$objects = $scporder_options['objects'];

		if ( in_array( $post->post_type, $objects ) ) {
			$current_menu_order = $post->menu_order;
			$where = "WHERE p.menu_order < '" . $current_menu_order . "' AND p.post_type = '" . $post->post_type . "' AND p.post_status = 'publish'";
		}
		return $where;
	}

	function scporder_next_post_sort( $orderby ) {
		global $post;

		$scporder_options = get_option( 'scporder_options' );
		$objects = $scporder_options['objects'];

		if ( in_array( $post->post_type, $objects ) ) {
			$orderby = 'ORDER BY p.menu_order DESC LIMIT 1';
		}
		return $orderby;
	}

	function scporder_filter_active( $wp_query ) {

		if ( isset( $wp_query->query['suppress_filters'] ) )
			$wp_query->query['suppress_filters'] = false;
		if ( isset( $wp_query->query_vars['suppress_filters'] ) )
			$wp_query->query_vars['suppress_filters'] = false;
		return $wp_query;
	}

	function scporder_pre_get_posts( $wp_query ) {
		$scporder_options = get_option( 'scporder_options' );
		$objects = $scporder_options['objects'];

		if ( is_array( $objects ) ) {



			if ( is_admin() && !defined( 'DOING_AJAX' ) ) {


				if ( isset( $wp_query->query['post_type'] ) ) {
					if ( in_array( $wp_query->query['post_type'], $objects ) ) {
						$wp_query->set( 'orderby', 'menu_order' );
						$wp_query->set( 'order', 'ASC' );
					}
				}
			} else {

				$active = false;



				if ( empty( $wp_query->query ) ) {
					if ( in_array( 'post', $objects ) ) {
						$active = true;
					}
				} else {



					if ( isset( $wp_query->query['suppress_filters'] ) ) {


						if ( is_array( $wp_query->query['post_type'] ) ) {
							$post_types = $wp_query->query['post_type'];
							foreach ( $post_types as $post_type ) {
								if ( in_array( $post_type, $objects ) ) {
									$active = true;
								}
							}
						} else {
							if ( in_array( $wp_query->query['post_type'], $objects ) ) {
								$active = true;
							}
						}
					} else {


						if ( isset( $wp_query->query['post_type'] ) ) {


							if ( is_array( $wp_query->query['post_type'] ) ) {
								$post_types = $wp_query->query['post_type'];
								foreach ( $post_types as $post_type ) {
									if ( in_array( $post_type, $objects ) ) {
										$active = true;
									}
								}
							} else {
								if ( in_array( $wp_query->query['post_type'], $objects ) ) {
									$active = true;
								}
							}
						} else {
							if ( in_array( 'post', $objects ) ) {
								$active = true;
							}
						}
					}
				}

				if ( $active ) {
					if ( !isset( $wp_query->query['orderby'] ) || $wp_query->query['orderby'] == 'post_date' )
						$wp_query->set( 'orderby', 'menu_order' );
					if ( !isset( $wp_query->query['order'] ) || $wp_query->query['order'] == 'DESC' )
						$wp_query->set( 'order', 'ASC' );
				}
			}
		}
	}

}

/* =============================================================================================================
 * Taxonomy Sort
  =============================================================================================================== */
class Taxonomy_Order_Engine {

		/**
		 * Simple class constructor
		 */
		function __construct() {
			// admin initialize
			add_action( 'admin_init', array( $this, 'admin_init' ) );

			// front-end initialize
			add_action( 'init', array( $this, 'init' ) );
		}

		/**
		 * Initialize administration
		 */
		function admin_init() {
			// load scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );


			// ajax to save the sorting
			add_action( 'wp_ajax_get_inline_boxes', array( $this, 'inline_edit_boxes' ) );

			// reorder terms when someone tries to get terms
			add_filter( 'get_terms', array( $this, 'reorder_terms' ) );
		}

		/**
		 * Initialize front-page
		 *
		 */
		function init() {
			// reorder terms when someone tries to get terms
			add_filter( 'get_terms', array( $this, 'reorder_terms' ) );
		}

		/**
		 * Load scripts 
		 */
		function enqueue_scripts() {
			// allow enqueue only on tags/taxonomy page
			if ( get_current_screen()->base != 'edit-tags' )
				return;
			// load jquery and plugin's script
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'taxonomyorder', plugins_url( 'assets/taxonomy_order.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ) );

			wp_enqueue_style( 'scporder', SCPO_URL . '/assets/scporder.css', array( ), null );
		}

		/**
		 * Do the sorting
		 */
		function inline_edit_boxes() {
			// loop through rows
			foreach ( $_POST['rows'] as $key => $row ) {
				// skip empty
				if ( !isset( $row ) || $row == "" )
					continue;

				// update order
				update_post_meta( $row, 'thets_order', ( $key + 1 ) );
			}

			// kill it for ajax
			exit;
		}

		/**
		 * Order terms 
		 */
		function reorder_terms( $objects ) {
			// we do not need empty objects
			if ( empty( $objects ) )
				return $objects;

      // do not apply ordering to list that contains just names
      if ( !is_object( $objects[0] ) )
        return $objects;
      
			// placeholder for ordered objects
			$placeholder = array( );

			// invalid key counter (if key is not set)
			$invalid_key = 9000;

			// loop through objects
			foreach ( $objects as $key => $object ) {
				// increase invalid key count
				$invalid_key++;

				// continue if no term_id
				if ( !isset( $object->term_id ) )
					continue;

				// get the order key
				$term_order = get_post_meta( $object->term_id, 'thets_order', true );

				// use order key if exists, invalid key if not
				$term_key = ( $term_order != "" && $term_order != 0 ) ? (int) $term_order : $invalid_key;

 
				$placeholder[$term_key] = $object;
			}

 
			ksort( $placeholder );

 
			return $placeholder;
		}

	}
 
	new Taxonomy_Order_Engine;