<?php
/**
 * Aqua Sprite SVG Code
 *
 * Generates <svg><use></use></svg> code for a particular sprite via its slug.
 *
 * usage:
 *   aqua_svg( string $slug, string $sprite = 'general', array $attr( string 'viewbox' => '', string 'html_attr' => '', boolean echo = true ) )
 * example:
 *   aqua_svg( 'slug' );
 * or a more complex example:
 *   echo aqua_svg( 'slug', '0 0 1000 1000', 'width="100" height="100"', false );
 * @param string	$slug 		The slug (i.e. post slug, also the symbol's ID in the sprite) for which to fetch code. Required.
 * @param string	$sprite 	The sprite to use (i.e. the slug of the sprite term). Defaults to 'general'.
 * @param array		$attr 		Additional options, for viewbox (e.g. '0 0 1000 1000', defaults to ''), html_attr (e.g. 'width="100"', defaults to '') and echo (true|false, defaults to true)
 * @return string|null
 */
function aqua_svg ( $slug, $sprite = 'general', $attr = array() ) {
	// grab optional attributes or set to defaults
	$viewbox   = ( isset( $attr[ 'viewbox' ] ) ? $attr[ 'viewbox' ] : '' );
	$html_attr = ( isset( $attr[ 'html_attr' ] ) ? $attr[ 'html_attr' ] : '' );
	$echo      = ( isset( $attr[ 'echo' ] ) ? $attr[ 'echo' ] : true );

	// if viewbox was set then create html
	if ( $viewbox ) {
		$viewbox = ' viewBox="' . $viewbox . '"';
	}
	// if other attributes were set then create html
	if ( $html_attr ) {
		$html_attr = ' ' . $html_attr;
	}
	// get the url for the sprite
	$aqua_svg_sprite_file = WP_CONTENT_URL . '/uploads/aqua-svg-sprite/aqua-svg-' . $sprite . '-sprite.svg';
	// create the full svg sprite html
	$svg_code = '<svg' . $viewbox . $html_attr . '><use xlink:href="' . $aqua_svg_sprite_file . '#' . $slug . '"' . '></use></svg>';
	// echo it or return it based on $echo value
	if ( $echo ) {
		echo $svg_code;
	} else {
		return $svg_code;
	}
}