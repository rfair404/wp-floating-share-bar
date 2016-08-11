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

function russellsLevitatingSocialSharingButtons() {
    if( is_admin() ) {
        require_once( dirname( __FILE__ ) . '/lib/admin.class.php' );
        $admin = new RussellsLevitatingSocialShareButtons\Admin;
        $admin->init();
    } else {
        //nothing for now
    }
}

add_action( 'plugins_loaded', 'russellsLevitatingSocialSharingButtons' );