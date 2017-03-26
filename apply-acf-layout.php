<?php
/*
Plugin Name: Apply ACF Layout
Description: Apply a flexible layout from one post to another.
Version:     0.2.0
Author:      Tomas Mulder
Author URI:  http://www.tcmulder.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: apply-acf-layout
Domain Path: /languages
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Defines common constants for plugin use.
define( 'VISITOR_CHECK_IN_VERSION', '1.0.0' );
define( 'VISITOR_CHECK_IN_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'VISITOR_CHECK_IN_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

// Runs activation script.
register_activation_hook( __FILE__ , array( 'Apply_ACF_Layout', 'activate_plugin' ) );

// Runs the initial method for the Apply_ACF_Layout class.
require_once( VISITOR_CHECK_IN_PLUGIN_DIR . 'class-apply-acf-layout.php' );
Apply_ACF_Layout::init();
