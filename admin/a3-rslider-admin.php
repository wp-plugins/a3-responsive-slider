<?php
update_option('a3rev_rslider_plugin', 'a3_responsive_slider');

function a3_rslider_activated(){
	update_option('a3rev_rslider_version', '1.1.5');

	// Set Settings Default from Admin Init
	global $a3_responsive_slider_admin_init;
	$a3_responsive_slider_admin_init->set_default_settings();

	// Build sass
	global $a3_responsive_slider_less;
	$a3_responsive_slider_less->plugin_build_sass();

	A3_Responsive_Slider_Data::install_database();

	update_option('a3rev_rslider_just_installed', true);
}

/**
 * Load languages file
 */
function a3_responsive_slider_init() {
	A3_Responsive_Slider_Custom_Post::register_post_type();

	if ( get_option( 'a3rev_rslider_just_installed' ) ) {
		delete_option( 'a3rev_rslider_just_installed' );
		wp_redirect( admin_url( 'edit.php?post_type=a3_slider', 'relative' ) );
		exit;
	}
	load_plugin_textdomain( 'a3_responsive_slider', false, A3_RESPONSIVE_SLIDER_FOLDER.'/languages' );
}
// Add language
add_action('init', 'a3_responsive_slider_init');

// Add custom style to dashboard
add_action( 'admin_enqueue_scripts', array( 'A3_Responsive_Slider_Hook_Filter', 'a3_wp_admin' ) );

// Add text on right of Visit the plugin on Plugin manager page
add_filter( 'plugin_row_meta', array( 'A3_Responsive_Slider_Hook_Filter', 'plugin_extra_links'), 10, 2 );

// Add admin sidebar menu css
add_action( 'admin_enqueue_scripts', array( 'A3_Responsive_Slider_Hook_Filter', 'admin_sidebar_menu_css' ) );

	global $a3_responsive_slider_admin_init;
	$a3_responsive_slider_admin_init->init();

	// Add upgrade notice to Dashboard pages
	add_filter( $a3_responsive_slider_admin_init->plugin_name . '_plugin_extension', array( 'A3_Responsive_Slider_Hook_Filter', 'plugin_extension' ) );

	add_action( 'init', array( 'A3_Responsive_Slider_Edit', 'slider_form_action' ) );

	add_action( 'wp_head', array( 'A3_Responsive_Slider_Hook_Filter', 'include_frontend_script' ), 10 );

	// Include google fonts into header
	add_action( 'wp_head', array( 'A3_Responsive_Slider_Hook_Filter', 'add_google_fonts'), 10 );

	// Add Script & Style for Preview
	if ( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post.php', 'edit.php', 'post-new.php' ) ) ) {
		$is_a3_slider_list_edit_page = false;
		if ( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'edit.php', 'post-new.php' ) ) && isset( $_GET['post_type'] ) && $_GET['post_type'] == 'a3_slider' ) $is_a3_slider_list_edit_page = true;

		if ( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post.php' ) ) && isset( $_GET['post'] ) && get_post_type( $_GET['post'] ) == 'a3_slider' ) $is_a3_slider_list_edit_page = true;

		if ( $is_a3_slider_list_edit_page ) {
			add_action( 'admin_enqueue_scripts', array( 'A3_Responsive_Slider_Hook_Filter', 'include_frontend_script' ) );

			add_action( 'admin_footer', array( 'A3_Responsive_Slider_Hook_Filter', 'enqueue_frontend_script' ) );

			// Include google fonts into header
			add_action( 'admin_footer', array( 'A3_Responsive_Slider_Hook_Filter', 'add_google_fonts'), 10 );
			// Add Custom style on frontend
			global $a3_responsive_slider_less;
			add_action( 'admin_footer', array ( $a3_responsive_slider_less, 'apply_style_css_fontend') );
		}
	}

	// AJAX show slider preview
	add_action( 'wp_ajax_a3_slider_preview', array( 'A3_Responsive_Slider_Preview', 'a3_slider_preview' ) );
	add_action( 'wp_ajax_nopriv_a3_slider_preview', array( 'A3_Responsive_Slider_Preview', 'a3_slider_preview' ) );

	$GLOBALS['a3_rslider_shortcode'] = new A3_Responsive_Slider_Shortcode();

	// AJAX remove shortcode inside the content of post
	global $a3_rslider_shortcode;

	// Custom Post Type
	if ( in_array( basename( $_SERVER['PHP_SELF'] ), array( 'post.php', 'page.php', 'page-new.php', 'post-new.php' ) ) ) {
		if ( isset( $_GET['post_type'] ) && in_array( $_GET['post_type'], array( 'a3_slider' ) ) ) {
			add_action( 'edit_form_top', array( 'A3_Responsive_Slider_Custom_Post', 'show_own_edit_slider_page' ) );
		} elseif ( isset( $_GET['post'] ) && isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
			$postid = $_GET['post'];
			$post_type = get_post_type( $postid );
			if ( $post_type == 'a3_slider' ) add_action( 'edit_form_top', array( 'A3_Responsive_Slider_Custom_Post', 'show_own_edit_slider_page' ) );
		}
	}

	// Remove all images of slider when delete the slider
	add_action( 'delete_post', array( 'A3_Responsive_Slider_Data', 'remove_slider_images' ) );

	add_filter( 'post_row_actions', array( 'A3_Responsive_Slider_Custom_Post', 'post_row_actions' ), 11, 2 );

	/* START : Update the columns for Room post type */

	// Add sortable for custom column
	add_action( 'load-edit.php', array( 'A3_Responsive_Slider_Custom_Post', 'sortable_column_load' ) );
	add_action( 'restrict_manage_posts', array( 'A3_Responsive_Slider_Custom_Post', 'cats_restrict_manage_posts' ) );
	add_filter( 'manage_edit-a3_slider_sortable_columns', array( 'A3_Responsive_Slider_Custom_Post', 'edit_sortable_columns' ) );
	add_filter( 'parse_query', array( 'A3_Responsive_Slider_Custom_Post', 'slider_filters_query' ) );

	// Add custom column for Room post type
	add_filter( 'manage_edit-a3_slider_columns', array( 'A3_Responsive_Slider_Custom_Post', 'edit_columns' ) );
	add_filter( 'manage_a3_slider_posts_columns', array( 'A3_Responsive_Slider_Custom_Post', 'edit_columns' ) );
	add_action( 'manage_a3_slider_posts_custom_column', array( 'A3_Responsive_Slider_Custom_Post', 'custom_columns' ) );

	/* END : Update the columns for Room post type */

	/* START : Duplicate a room */
	add_action( 'admin_action_duplicate_a3_slider', array( 'A3_Responsive_Slider_Duplicate', 'duplicate_action' ) );

	//Duplicate a room link on room list
	add_filter('post_row_actions', array( 'A3_Responsive_Slider_Duplicate', 'duplicate_link_row' ) ,10,2);
	add_filter('page_row_actions', array( 'A3_Responsive_Slider_Duplicate', 'duplicate_link_row' ),10,2);

	// Duplicate a room link on edit screen
	add_action( 'post_submitbox_start', array( 'A3_Responsive_Slider_Duplicate', 'duplicate_post_button' ) );

	/* END : Duplicate a room */

add_action( 'init', 'a3_rslider_upgrade_plugin' );
function a3_rslider_upgrade_plugin () {
	if( version_compare(get_option('a3rev_rslider_version'), '1.1.3') === -1 ){
		// Build sass
		global $a3_responsive_slider_less;
		$a3_responsive_slider_less->plugin_build_sass();
		update_option('a3rev_rslider_version', '1.1.3');
	}

	// Upgrade to 1.1.4
	if( version_compare(get_option('a3rev_rslider_version'), '1.1.4') === -1 ){
		include( A3_RESPONSIVE_SLIDER_DIR. '/includes/updates/a3_rslider-update-1.1.4.php' );
		update_option('a3rev_rslider_version', '1.1.4');
	}

	update_option('a3rev_rslider_version', '1.1.5');
}

	// Template Tag for Developer use to put into php code
	function a3_responsive_slider( $slider_id = 0 ) {
		return '';
	}
?>
