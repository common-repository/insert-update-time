<?php
/*
Plugin Name: Insert Update Time
Plugin URI:  https://www.ixiqin.com/2017/09/wordpress-plugin-insert-update-time/
Description: Insert A Update Time log into your Content;
Version:     0.0.2
Author:      Bestony
Author URI:  https://www.ixiqin.com/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: insert-update-time
Domain Path: /languages
*/
/**
 *  TinyMCE Editor Function
 */

class Insert_Update_Time_Tinymce_Class {

	/**
	 * Constructor. Called when the plugin is initialised.
	 */
	function __construct() {

		if ( is_admin() ) {
			add_action( 'init', array( &$this, 'setup_tinymce_plugin' ) );
			add_action( 'admin_enqueue_scripts', array( &$this, 'admin_scripts_css' ) );
			add_action( 'admin_print_footer_scripts', array( &$this, 'admin_footer_scripts' ) );
		}
	}
	/**
	 * Check if the current user can edit Posts or Pages, and is using the Visual Editor
	 * If so, add some filters so we can register our plugin
	 */
	function setup_tinymce_plugin() {

		// Check if the logged in WordPress User can edit Posts or Pages
		// If not, don't register our TinyMCE plugin
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

		// Check if the logged in WordPress User has the Visual Editor enabled
		// If not, don't register our TinyMCE plugin
		if ( get_user_option( 'rich_editing' ) !== 'true' ) {
			return;
		}

		// Setup some filters
		add_filter( 'mce_external_plugins', array( &$this, 'add_tinymce_plugin' ) );
		add_filter( 'mce_buttons', array( &$this, 'add_tinymce_toolbar_button' ) );

	}
	/**
	 * Adds a TinyMCE plugin compatible JS file to the TinyMCE / Visual Editor instance
	 *
	 * @param array $plugin_array Array of registered TinyMCE Plugins
	 * @return array Modified array of registered TinyMCE Plugins
	 */
	function add_tinymce_plugin( $plugin_array ) {

		$plugin_array['insert_update_time'] = plugin_dir_url( __FILE__ ) . 'insert-update-time.js';
		return $plugin_array;

	}
	/**
	 * Adds a button to the TinyMCE / Visual Editor which the user can click
	 * to insert a custom CSS class.
	 *
	 * @param array $buttons Array of registered TinyMCE Buttons
	 * @return array Modified array of registered TinyMCE Buttons
	 */
	function add_tinymce_toolbar_button( $buttons ) {

		array_push( $buttons, 'insert_update_time' );
		return $buttons;

	}
	/**
	 * Enqueues CSS for TinyMCE Dashicons
	 */
	function admin_scripts_css() {
		wp_enqueue_style( 'tinymce-custom-class', plugins_url( 'insert-update-time.css', __FILE__ ) );
	}
	/**
	 * Adds the Custom Class button to the Quicktags (Text) toolbar of the content editor
	 */
	function admin_footer_scripts() {
		// Get User Infomation
		global $current_user;
		get_currentuserinfo();
		if (!wp_script_is('quicktags')) {
			return ;
		}else{
			?>
			<input type="hidden" id="insert-update-time-name" value="<?php echo $current_user->user_login; ?>">
			<input type="hidden" id="insert-update-time-email" value="<?php echo $current_user->user_email; ?>">
			<input type="hidden" id="insert-update-time-time" value="<?php echo date('Y/m/d'); ?>">

			<script type="text/javascript">
                QTags.addButton( 'insert-update-time','<?php _e("Time Tag","insert-update-time");?>', insert_update_time );
                function insert_update_time(){
                    /**
                     * Insert Content
                     */
                    QTags.insertContent(
                        '<!--more--></br>\n <a href="mailto:<?php echo $current_user->user_email ;?>">@<?php echo $current_user->user_login; ?></a> Edit at  <?php echo get_the_date(); ?> </br>\n'
                    );
                }
			</script>
			<?php
		}
	}

}

$tinymce_custom_class = new Insert_Update_Time_Tinymce_Class;