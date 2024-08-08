<?php
/*
Plugin Name: Aqua SVG Sprite
Description: Create SVG sprites within WordPress.
Version:     3.0.14
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
define( 'AQUA_SVG_SPRITE_VERSION', '3.0.3' );
define( 'AQUA_SVG_SPRITE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AQUA_SVG_SPRITE_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

// Runs the initial method for the AQUA_SVG_SPRITE class.
require_once( AQUA_SVG_SPRITE_PLUGIN_DIR . 'class-aqua-svg-sprite.php' );
AQUA_SVG_SPRITE::init();

// Exposes the API.
require_once( AQUA_SVG_SPRITE_PLUGIN_DIR . 'aqua-svg-sprite-api.php' );
