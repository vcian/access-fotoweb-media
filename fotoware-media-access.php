<?php
/**
 * Plugin Name: FotoWare Wordpress Lite
 * Plugin URI: https://viitorcloud.com/
 * Description: The Plugin's WordPress editor button connector for Fotoware allows to include images directly from the Fotoweb DAM, into WordPress.
 * Version:1.0.0
 * Author: VIITORCLOUD
 * Author URI: https://viitorcloud.com/
 * License: GPL2
 */ 


// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $wpdb;

if( !defined( 'FW_MEDIA_AC_DIR' ) ) {
	define( 'FW_MEDIA_AC_DIR', dirname( __FILE__ ) ); // plugin dir
}

if( !defined( 'FW_MEDIA_AC_URL' ) ) {
	define( 'FW_MEDIA_AC_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'FW_MEDIA_AC_IMG_URL' ) ) {
	define( 'FW_MEDIA_AC_IMG_URL', FW_MEDIA_AC_URL . '/images' ); // plugin images url
}
if( !defined( 'FW_MEDIA_AC_TEXT_DOMAIN' ) ) {
	define( 'FW_MEDIA_AC_TEXT_DOMAIN', 'fotoware_media_access' ); // text domain for doing language translation
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package FotoWare Media Access
 * @since 1.0.0
 */
load_plugin_textdomain( 'fotoware_media_access', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
/**
 * Activation hook
 * 
 * Register plugin activation hook.
 * 
 * @package FotoWare Media Access
 *@since 1.0.0
 */
register_activation_hook( __FILE__, 'fotoware_media_access_install' );

/**
 * Deactivation hook
 *
 * Register plugin deactivation hook.
 * 
 * @package FotoWare Media Access
 * @since 1.0.0
 */
register_deactivation_hook( __FILE__, 'fotoware_media_access_uninstall' );

/**
 * Plugin Setup Activation hook call back 
 *
 * Initial setup of the plugin setting default options 
 * and database tables creations.
 * 
 * @package FotoWare Media Access
 * @since 1.0.0
 */
function fotoware_media_access_install() {
	
	global $wpdb; 
		
}
/**
 * Plugin Setup (On Deactivation)
 *
 * Does the drop tables in the database and
 * delete  plugin options.
 *
 * @package FotoWare Media Access
 * @since 1.0.0
 */
function fotoware_media_access_uninstall() {
	
	global $wpdb;
			
}

/**
 * Includes
 *
 * Includes all the needed files for plugin
 *
 * @package FotoWare Media Access
 * @since 1.0.0
 */

//require_once options file
require_once( FW_MEDIA_AC_DIR . '\fotoware-options.php');

//require_once callback file
require_once( FW_MEDIA_AC_DIR . '\callback.php');

//require_once auth file
require_once( FW_MEDIA_AC_DIR . '\fotoware-auth.php');

//require_once options file
 require_once( FW_MEDIA_AC_DIR . '\fotoware-main.php');
