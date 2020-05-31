<?php
/**
 * Loads the plugin files
 *
 * @since 1.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// Load basic setup. Plugin list links, text domain, footer links etc. 
require_once( DC_STARTER_PLUGIN_DIR . 'admin/basic-setup.php' );

// Load admin setup. Register menus and settings
require_once( DC_STARTER_PLUGIN_DIR . 'admin/admin-ui-setup.php' );

// Render Admin UI
require_once( DC_STARTER_PLUGIN_DIR . 'admin/admin-ui-render.php' );

// Do plugin operations
require_once( DC_STARTER_PLUGIN_DIR . 'functions/do.php' );

function dc_plugin_init(){
    // Custom VC_elements
    if ( class_exists( 'WPBakeryShortCode' )) {
        require_once( DC_STARTER_PLUGIN_DIR . 'vc_elements/vc.carousel.php' );
        require_once( DC_STARTER_PLUGIN_DIR . 'vc_elements/vc.character.filter.php' );
    }
}

add_action( 'plugins_loaded', 'dc_plugin_init' );