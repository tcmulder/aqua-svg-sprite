<?php
/**
 * Image Sizes
 * usage:
 *   aqua_svg( string $slug, string $viewbox = '', string $attr = '', boolean $echo = true )
 * example:
 *   aqua_svg( 'slug' );
 * or a more complex example:
 *   echo aqua_svg( 'slug', '0 0 1000 1000', 'width="100" height="100"', false );
 */
function aqua_svg ( $slug,  $viewbox = '', $attr = '', $echo = true ) {
	// if viewbox was set then create html
	if ( $viewbox ) {
		$viewbox = ' viewBox="' . $viewbox . '"';
	}
	// if other attributes were set then create html
	if ( $attr ) {
		$attr = ' ' . $attr;
	}
	// get the url for the sprite
	$aqua_svg_sprite_file = WP_CONTENT_URL . '/uploads/aqua-svg-sprite/aqua-svg-sprite.svg';
	// create the full svg sprite html
	$svg_code = '<svg' . $viewbox . $attr . '><use xlink:href="' . $aqua_svg_sprite_file . '#' . $slug . '"' . '></use></svg>';
	// echo it or return it based on $echo value
	if ( $echo ) {
		echo $svg_code;
	} else {
		return $svg_code;
	}
}