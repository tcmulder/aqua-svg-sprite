=== Aqua SVG Sprite ===

Contributors: tcmulder
Tags: svg sprite, svg, sprite, acf
Requires at least: 4.7.1
Tested up to: 4.8.1
Stable tag: 2.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates an SVG sprite.

== Description ==

This plugin allows you to create an SVG sprite by uploading individual files to the WordPress media library.

A call to `the_aqua_svg( 'some-slug' )` will output  SVG <use> code for the sprite with an ID (post slug) of "some-slug". If you tag an image with a different sprite group than the default "general" one, you access those by calling `the_aqua_svg( 'some-slug', 'some-group' )`.

You can also use shortcodes, like `[aqua-svg slug="some-slug"]` or `[aqua-svg slug="some-slug" sprite="some-group"]` to achieve the same as the above paragraph.

Full PHP usage options are as follows:
```
the_aqua_svg( string $slug, string $sprite = 'general', array $attr( 'attribute' => 'value' ) )
```

For example:
```
<?php
    // echo the "some-slug" svg from the default "general" group
    the_aqua_svg( 'some-slug' );
    
    // store (not echo) the "some-slug" SVG from the "some-sprite" group,
    // adding viewbox and fill properties.
    $svg_string = get_aqua_svg( 'some-slug', 'some-sprite', array( 'viewbox' => '0 0 1000 1000', 'fill' => 'aquamarine' ) );
    
    // echo it manually
    echo $svg_string;
?>
```

You can achieve the same thing using the short code. Notice in particular that there is no echo option, and there's a pseudo-array format for properties:
```
[aqua-svg slug="some-slug" sprite="some-sprite" attr="viewbox=0 0 1000 1000,fill=aquamarine"]
```

== Installation ==

1. Upload "aqua-svg-sprite" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Changelog ==

= 2.1.0 =

* Added get_aqua_svg and the_aqua_svg API functions.
* Added tinymce shortcode button.
* Add validation (must have added SVG, must _be_ SVG).
* Add internationalization functions.

= 2.0.0 =

* Changed PHP API.
* Added shortcode.
* Updated the created directory permissions.

= 1.0.0 =

* Added ability to have multiple sprites.
* Overhaul API.

= 0.0.4 =

* Striped more code from <svg> files for better <symbol> support.
* Added better comments and on-post documentation for usage.

= 0.0.3 =

* Added more details to readme.

= 0.0.2 =

* Fixed issue where featured image script executed on save of non-SVG posts.

= 0.0.1 =

* Initial release.
