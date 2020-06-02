<?php
/**
 * Plugin Name: DC Core
 * Plugin URI: https://github.com/arunbasillal/WordPress-Starter-Plugin
 * Description: A UI Toolkit plugin for WordPress complete with components for VC plugin
 * Author: Herman Andres
 * Author URI: https://github.com/heanfig
 * Version: 1.0
 * Text Domain: dc
 * Domain Path: /languages
 * License: GPL v2 - http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */
 
/**
 * ~ Directory Structure ~
 *
 * /admin/ 						- Plugin backend stuff.
 * /functions/					- Functions and plugin operations.
 * /includes/					- External third party classes and libraries.
 * /languages/					- Translation files go here. 
 * /public/						- Front end files and functions that matter on the front end go here.
 * index.php					- Dummy file.
 * license.txt					- GPL v2
 * prefix_starter-plugin.php	- Main plugin file containing plugin name and other version info for WordPress.
 * readme.txt					- Readme for WordPress plugin repository. https://wordpress.org/plugins/files/2018/01/readme.txt
 * uninstall.php				- Fired when the plugin is uninstalled. 
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Define constants
 *
 * @since 1.0
 */
if ( ! defined( 'DC_VERSION_NUM' ) ) 		define( 'DC_VERSION_NUM'		, '1.0' ); // Plugin version constant
if ( ! defined( 'DC_STARTER_PLUGIN' ) )		define( 'DC_STARTER_PLUGIN'		, trim( dirname( plugin_basename( __FILE__ ) ), '/' ) ); // Name of the plugin folder eg - 'starter-plugin'
if ( ! defined( 'DC_STARTER_PLUGIN_DIR' ) )	define( 'DC_STARTER_PLUGIN_DIR'	, plugin_dir_path( __FILE__ ) ); // Plugin directory absolute path with the trailing slash. Useful for using with includes eg - /var/www/html/wp-content/plugins/starter-plugin/
if ( ! defined( 'DC_STARTER_PLUGIN_URL' ) )	define( 'DC_STARTER_PLUGIN_URL'	, plugin_dir_url( __FILE__ ) ); // URL to the plugin folder with the trailing slash. Useful for referencing src eg - http://localhost/wp/wp-content/plugins/starter-plugin/

/**
 * Add plugin version to database
 *
 * @refer https://codex.wordpress.org/Creating_Tables_with_Plugins#Adding_an_Upgrade_Function
 * @since 1.0
 */
update_option( 'abl_prefix_version', DC_VERSION_NUM );	// Change this to add_option if a release needs to check installed version.

// Load everything
require_once( DC_STARTER_PLUGIN_DIR . 'loader.php' );

// Register activation hook (this has to be in the main plugin file or refer bit.ly/2qMbn2O)
register_activation_hook( __FILE__, 'prefix_activate_plugin' );