<?php
/**
 * Generate Aqua Sprite SVG code.
 *
 * Generates <svg><use></use></svg> code for a particular sprite via its slug.
 *
 * usage:
 *   aqua_svg( string $slug, string $sprite = 'general', boolean echo = true, array $attr( 'attribute' => 'value' ) )
 * example:
 *   aqua_svg( 'some-slug' );
 * or a more complex example:
 *   $svg_string = aqua_svg( 'some-slug', 'some-sprite', false, array( 'viewbox' => '0 0 1000 1000' ) );
 *   echo $svg_string;
 *
 * @param string	$slug 		The slug (i.e. post slug, also the symbol's ID in the sprite) for which to fetch code. Required.
 * @param string	$sprite 	The sprite to use (i.e. the slug of the sprite term). Defaults to 'general'.
 * @param boolean	$echo 		Whether to echo (true) or return (false) the value. Defaults to true.
 * @param array		$attr 		HTML attributes to add to the <svg> tag. Defaults to empty array.
 * @return string|null
 */
function aqua_svg ( $slug, $sprite = 'general', $echo = true, $attr = array() ) {

	// if other attributes were set then create html
	$attr_html = '';
	if ( ! empty( $attr ) ) {
		foreach ( $attr as $key => $value ) {
			$attr_html .= ' ' . $key . '="' . $value . '"';
		}
	}
	// get the url for the sprite
	$aqua_svg_sprite_file = WP_CONTENT_URL . '/uploads/aqua-svg-sprite/aqua-svg-' . $sprite . '-sprite.svg';
	// create the full svg sprite html
	$svg_code = '<svg' . $attr_html . '><use xlink:href="' . $aqua_svg_sprite_file . '#' . $slug . '"' . '></use></svg>';
	// echo it or return it based on $echo value
	if ( $echo ) {
		echo $svg_code;
	} else {
		return $svg_code;
	}

}

/**
 * Generate Aqua Sprite SVG code via shortcode.
 *
 * Generates <svg><use></use></svg> code via a shortcode
 *
 * example:
 *   [aqua-svg slug="some-slug"]
 * or a more complex example:
 *   [aqua-svg slug="some-slug" sprite="some-sprite" attr="viewbox=0 0 1000 1000,fill=aquamarine"]
 *
 * @param string	$slug 		The slug (i.e. post slug, also the symbol's ID in the sprite) for which to fetch code. Required.
 * @param string	$sprite 	The sprite to use (i.e. the slug of the sprite term). Defaults to 'general'.
 * @param array		$attr 		HTML attributes to add to the <svg> tag in the pseudo-array format 'key1=val1,key2=val2'. Defaults to empty string.
 * @return string
 */
add_shortcode( 'aqua-svg', 'aqua_svg_sprite_shortcode' );
function aqua_svg_sprite_shortcode( $attr ) {

	// get attributes and set defaults
	$attr_array = shortcode_atts(
		array(
			'slug' 		=> 'slug',
			'sprite' 	=> 'general',
			'attr'		=> '',
		),
		$attr
	);

	// sanitize values
	$slug = esc_html( $attr_array['slug'] );
	$sprite = esc_html( $attr_array['sprite'] );

	// set up html attributes
	$html_attr = array();
	// if there are attributes to add
	if ( $attr_array['attr'] ) {
		// split the property/value pairs
		$html_arr = explode( ',', $attr_array['attr'] );
		// add the property/values into the html array
		foreach( $html_arr as $key_val ) {
			$key_val = explode( '=', $key_val );
			$html_attr[esc_html( $key_val[0] )] = esc_html( $key_val[1] );
		}
	}

	// sent the <svg> code
	return aqua_svg( $slug, $sprite, false, $html_attr );

}
