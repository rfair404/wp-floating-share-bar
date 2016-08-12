<?php
/**
 * Plugin Name: Russell's Levitating Social Sharing Buttons
 * Version: 0.1
 * Description: A plugin that will automatically display selected social network(s) sharing buttons in posts and/or on pages. Supports Facebook, Twitter, Google+, Pinterest, LinkedIn, Whatsapp. Built for Toptal by Russell Fair
 * Author: Russell Fair
 * Author URI: https://twitter.com/rfair
 * Text Domain: russell-fair
 * Domain Path: /languages
 * @package Russell's Levitating Social Sharing Buttons
 */

register_activation_hook( __FILE__, 'rlssbActivate' );
function rlssbActivate(){
    require_once( dirname( __FILE__ ) . '/lib/common.class.php' );
    $common = new RussellsLevitatingSocialShareButtons\Common;
    update_option( $common->getSlug(), $common->defaultSettings() );
}




function russellsLevitatingSocialSharingButtons() {
    if( is_admin() ) {
        require_once( dirname( __FILE__ ) . '/lib/admin.class.php' );
        $admin = new RussellsLevitatingSocialShareButtons\Admin;
        $admin->init();
    } else {
        require_once( dirname( __FILE__ ) . '/lib/display.class.php' );
        $display = new RussellsLevitatingSocialShareButtons\Display;
        $display->init();
    }
}

add_action( 'plugins_loaded', 'russellsLevitatingSocialSharingButtons' );