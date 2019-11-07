<?php
/**
 * Return Aqua Sprite SVG code.
 *
 * Returns <svg><use></use></svg> code for a particular sprite via its slug.
 *
 * usage:
 *   get_aqua_svg( string $slug, string $sprite = 'general', array $attr( 'attribute' => 'value' ) )
 * example:
 *   get_aqua_svg( 'some-slug' );
 * or a more complex example:
 *   $svg_string = get_aqua_svg( 'some-slug', 'some-sprite', array( 'viewbox' => '0 0 1000 1000' ) );
 *   echo $svg_string;
 *
 * @param string	$slug 		The slug (i.e. post slug, also the symbol's ID in the sprite) for which to fetch code. Required.
 * @param string	$sprite 	The sprite to use (i.e. the slug of the sprite term). Defaults to 'general'.
 * @param array		$attr 		HTML attributes to add to the <svg> tag. Defaults to empty array.
 * @return string|null
 */
function get_aqua_svg ( $slug, $sprite = 'general', $attr = array() ) {

	// if other attributes were set then create html
	$attr_html = '';
	if ( ! empty( $attr ) ) {
		foreach ( $attr as $key => $value ) {
			// don't allow unsafe attributes
			$unsafe = array( 'onbegin', 'onend', 'onrepeat', 'onabort', 'onerror', 'onresize', 'onscroll', 'onunload', 'oncancel', 'oncanplay', 'oncanplaythrough', 'onchange', 'onclick', 'onclose', 'oncuechange', 'ondblclick', 'ondrag', 'ondragend', 'ondragenter', 'ondragexit', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'ondurationchange', 'onemptied', 'onended', 'onerror', 'onfocus', 'oninput', 'oninvalid', 'onkeydown', 'onkeypress', 'onkeyup', 'onload', 'onloadeddata', 'onloadedmetadata', 'onloadstart', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onpause', 'onplay', 'onplaying', 'onprogress', 'onratechange', 'onreset', 'onresize', 'onscroll', 'onseeked', 'onseeking', 'onselect', 'onshow', 'onstalled', 'onsubmit', 'onsuspend', 'ontimeupdate', 'ontoggle', 'onvolumechange', 'onwaiting', 'onactivate', 'onfocusin', 'onfocusout' );
			if ( ! in_array( $key, $unsafe ) ) {
				// create the key/value
				$attr_html .= ' ' . $key . '="' . $value . '"';
			}
		}
	}
	// get the uploads directory url (and fix https issue: see https://developer.wordpress.org/reference/functions/wp_upload_dir/#comment-2576)
	$upload_arr = wp_upload_dir();
	$url =  ( is_ssl() ? str_replace( 'http://', 'https://', $upload_arr['baseurl'] ) : $upload_arr['baseurl'] );
	// get the url for the sprite
	$aqua_svg_sprite_file = $url . '/aqua-svg-sprite/aqua-svg-' . $sprite . '-sprite.svg';
	// create the full svg sprite html
	$svg_code = '<svg' . $attr_html . '><use xlink:href="' . $aqua_svg_sprite_file . '#' . $slug . '"' . '></use></svg>';

	// return svg's html code
	return $svg_code;

}

/**
 * Echo Aqua Sprite SVG code.
 *
 * Echos <svg><use></use></svg> code for a particular sprite via its slug.
 *
 * usage:
 *   the_aqua_svg( string $slug, string $sprite = 'general', array $attr( 'attribute' => 'value' ) )
 * example:
 *   the_aqua_svg( 'some-slug' );
 * or a more complex example:
 *   $svg_string = the_aqua_svg( 'some-slug', 'some-sprite', array( 'viewbox' => '0 0 1000 1000' ) );
 *   echo $svg_string;
 *
 * @param string	$slug 		The slug (i.e. post slug, also the symbol's ID in the sprite) for which to fetch code. Required.
 * @param string	$sprite 	The sprite to use (i.e. the slug of the sprite term). Defaults to 'general'.
 * @param array		$attr 		HTML attributes to add to the <svg> tag. Defaults to empty array.
 * @return string|null
 */
function the_aqua_svg ( $slug, $sprite = 'general', $attr = array() ) {

	// simply echo the_aqua_svg
	echo get_aqua_svg( $slug, $sprite, $attr );

}

/**
 * Legacy: Generate Aqua Sprite SVG code.
 *
 * Fairly high level option for generating <svg><use></use></svg> code
 * for a particular sprite via its slug. Consider the_aqua_svg() and
 * get_aqua_svg() for general use.
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

	// echo it or return it based on $echo value
	if ( ! $echo ) {
		return get_aqua_svg( $slug, $sprite, $attr );
	} else {
		the_aqua_svg( $slug, $sprite, $attr );
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
			$key = esc_html( $key_val[0] );
			$val = esc_html( $key_val[1] );
			// if there are key/values (fixes trailing commas also)
			if ( ! empty( $key ) && ! empty( $val ) ) {
				$html_attr[$key] = $val;
			}
		}
	}

	// sent the <svg> code
	return get_aqua_svg( $slug, $sprite, $html_attr );

}
