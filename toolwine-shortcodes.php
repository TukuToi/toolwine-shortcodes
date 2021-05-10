<?php
/**
 * Plugin Name:       ToolWine ShortCodes
 * Plugin URI:        https://www.tukutoi.com
 * Description:       Addon Custom ShortCodes for usage ith Toolset and WordPress
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Beda Schmid
 * Author URI:        https://www.tukutoi.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       tw_shortcodes
 * Domain Path:       /languages
 */

/**
 * This plugin needs WordPress
 */
if ( ! defined( 'WPINC' ) ) {
  die;
}

/**
 * Define Plugin Version
 */
if( !defined( 'TWS_VER' ) ){
  define( 'TWS_VER', '1.0.1' );
}
if( !defined( 'TWS_MAIN_DIR' ) ){
  define('TWS_MAIN_DIR', dirname(__FILE__).'/');
}
if( !defined( 'TWS_MAIN_URL' ) ){
  define('TWS_MAIN_URL', plugin_dir_url( __FILE__ ));
}

/**
 * Check requirements
 */
function tws_requirements() {

	$toolset = false;
	$types = in_array( 'types/wpcf.php', apply_filters('active_plugins', get_option('active_plugins')));
	$views = in_array( 'wp-views/wp-views.php', apply_filters('active_plugins', get_option('active_plugins')));

	if( empty($types) OR empty($views)){ 
		wp_die('Sorry, but this plugin requires the Toolset Plugins (Types and Views) to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
	}

}
 
/**
 * Activate the plugin.
 */
function tws_activate() { 
    // Trigger our function that checks if requirements are met
    tws_requirements();  

}
register_activation_hook( __FILE__, 'tws_activate' );

/**
 * Load ShortCodes
 */
function tws_init(){
  //if ( !is_admin() ) {
    require_once(TWS_MAIN_DIR.'includes/class.shortcodes.php');
    $shortcodes = array(
      'tws_group_by'    => 'tws_group_by',
      'tws_get_term'    => 'tws_get_term_by',
      'tws_get_lang'    => 'tws_current_wpml_language',
      'tws_children'    => 'tws_get_wp_children',
      'tws_info'        => 'tws_shortcodes_info',
      'tws_current_url'	=> 'tws_current_url',
      'tws_round'	    => 'tws_round',
      'tws_rep_field'    => 'tws_repeating_field',
    );
    $shortcodes     = apply_filters( 'tws_shortcodes', $shortcodes );
    $tws_shortcodes = new TWS_Shortcodes( $shortcodes );
    return $tws_shortcodes;
  //}
}
function tws_run(){
  if ( ( ! is_admin() || wp_doing_ajax() ) && ! wp_doing_cron() ) {
    add_action('wp', 'tws_init');
  }
  else{
    add_action('init', 'tws_init');
  }
}
add_action( 'plugins_loaded', 'tws_run' );
