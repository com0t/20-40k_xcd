<?php
/**
 * Admin Class
 *
 * Handles the admin functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpnw_Admin {
	
	function __construct() {
		
		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wpnw_register_menu'), 12 );
		
		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'wpnw_post_sett_metabox') );

		// Init Processes
		add_action( 'admin_init', array($this, 'wpnw_admin_init_process') );

		// Filter to add row action in category table
		add_filter( WPNW_CAT.'_row_actions', array($this, 'wpnw_add_tax_row_data'), 10, 2 );

		// Filter to add display news tag
		add_filter( 'pre_get_posts', array($this, 'wpnw_display_news_tags') );
	}

	/**
	 * Function to add menu
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0.0
	 */
	function wpnw_register_menu() {

		// How it work page
		add_submenu_page( 'edit.php?post_type='.WPNW_POST_TYPE, __('How it works - WP News and Scrolling Widgets', 'sp-news-and-widget'), __('How It Works', 'sp-news-and-widget'), 'edit_posts', 'wpnw-designs', array($this, 'wpnw_designs_page') );

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.WPNW_POST_TYPE, __('Upgrade to PRO - WP News and Scrolling Widgets', 'sp-news-and-widget'), '<span style="color:#ff2700">'.__('Upgrade to PRO', 'sp-news-and-widget').'</span>', 'manage_options', 'wpnw-premium', array($this, 'wpnw_premium_page') );
	}

	/**
	 * How it work Page Html
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0.0
	 */
	function wpnw_designs_page() {
		include_once( WPNW_DIR . '/includes/admin/wpnw-how-it-work.php' );		
	}

	/**
	 * Premium Page Html
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0.0
	 */
	function wpnw_premium_page() {
		include_once( WPNW_DIR . '/includes/admin/settings/premium.php' );		
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 4.5
	 */
	function wpnw_post_sett_metabox() {
		add_meta_box( 'wpnw-post-metabox-pro', __('More Premium - Settings', 'sp-news-and-widget'), array($this, 'wpnw_post_sett_box_callback_pro'), WPNW_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Function to handle 'premium ' metabox HTML
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 4.5
	 */
	function wpnw_post_sett_box_callback_pro( $post ) {		
		include_once( WPNW_DIR .'/includes/admin/metabox/wpnw-post-setting-metabox-pro.php');
	}

	/**
	 * Function to notification transient
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.4.3
	 */
	function wpnw_admin_init_process() {
		// If plugin notice is dismissed
	    if( isset($_GET['message']) && $_GET['message'] == 'wpnw-plugin-notice' ) {
	    	set_transient( 'wpnw_install_notice', true, 604800 );
	    }
	}

	/**
	 * Function to add category row action
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0
	 */
	function wpnw_add_tax_row_data( $actions, $tag ) {
		return array_merge( array( 'wpnw_id' => "<span style='color:#555'>ID: {$tag->term_id}</span>" ), $actions );
	}

	/**
	 * Function to display news tag filter
	 * 
	 * @package WP News and Scrolling Widgets
	 * @since 1.0
	 */
	function wpnw_display_news_tags( $query ) {

		if( ! is_admin() && is_tag() && $query->is_main_query() ) {
			
			$post_types = array( 'post', WPNW_POST_TYPE );
			$query->set( 'post_type', $post_types );
		}
	}

}

$wpnw_Admin = new Wpnw_Admin();