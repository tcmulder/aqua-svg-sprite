<?php
/*
Plugin Name: Aqua SVG Sprite
Description: Create SVG sprites within WordPress.
Version:     2.1.2
Author:      Tomas Mulder
Author URI:  http://www.thinkaquamarine.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: aqua-svg-sprite
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Defines common constants for plugin use.
define( 'AQUA_SVG_SPRITE_VERSION', '2.1.2' );
define( 'AQUA_SVG_SPRITE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AQUA_SVG_SPRITE_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

// Runs activation script.
register_activation_hook( __FILE__ , array( 'AQUA_SVG_SPRITE', 'activate_plugin' ) );

// Runs the initial method for the AQUA_SVG_SPRITE class.
require_once( AQUA_SVG_SPRITE_PLUGIN_DIR . 'class-aqua-svg-sprite.php' );
AQUA_SVG_SPRITE::init();

// Exposes the API.
require_once( AQUA_SVG_SPRITE_PLUGIN_DIR . 'aqua-svg-sprite-api.php' );
