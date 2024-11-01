<?php
/**
 * Plugin Name: WP User Notifications
 * Description: Send Notification to any user
 * Version: 1.2.6
 * Author: Muhammad Rehman
 * Plugin URI: http://itglobepk.com/
 * License: GPLv2 or later
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

include 'wpun-settings.php';

add_action( 'wp_enqueue_scripts', 'wpun_style_script' );
function wpun_style_script() {
    wp_enqueue_style( 'wpun-style', plugins_url('style/style.css', __FILE__ ) );
    wp_enqueue_script( 'wpun-jquery-script', 'https://code.jquery.com/jquery-2.2.4.min.js' );
    wp_enqueue_script( 'wpun-script', plugins_url('script/script.js', __FILE__ ) );
}
?>