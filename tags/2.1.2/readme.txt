=== Aqua SVG Sprite ===

Contributors: thinkaquamarine 
Tags: svg sprite, svg, sprite
Requires at least: 4.7.1
Tested up to: 4.8.1
Stable tag: 2.1.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates SVG sprites.

== Description ==

This plugin allows you to create an SVG sprite by uploading individual files to the WordPress media library.

= Shortcode Usage =

Aqua SVG Sprite will add a new `[SVG]` button to your editor (anywhere a WYSIWYG with TinyMCE appears), which will guide you through adding a shortcode to your content.

For example, you could display the "Some Slug" image from the default "General" sprite group like `[aqua-svg slug="some-slug"]`. If "Some Slug" were part of the "Some Group" sprite instead, you would use `[aqua-svg slug="some-slug" sprite="some-group"]`. You can also add a pseudo-array of HTML properties for more control like `[aqua-svg slug="some-slug" sprite="some-sprite" attr="viewbox=0 0 1000 1000,fill=aquamarine"]`.

= PHP Usage =

A call to `the_aqua_svg( 'some-slug' )` will output  SVG <use> code for the sprite with an ID (post slug) of "some-slug". If you tag an image with a different sprite group than the default "general" one, you access those by calling `the_aqua_svg( 'some-slug', 'some-group' )`.

Full PHP usage options are as follows:
```
the_aqua_svg( string $slug, string $sprite = 'general', array $attr( 'attribute' => 'value' ) )
```

For example:
```
// echo the "some-slug" svg from the default "general" group
the_aqua_svg( 'some-slug' );

// store (not echo) the "some-slug" SVG from the "some-sprite" group,
// adding viewbox and fill properties.
$svg_string = get_aqua_svg( 'some-slug', 'some-sprite', array( 'viewbox' => '0 0 1000 1000', 'fill' => 'aquamarine' ) );

// echo it manually
echo $svg_string;
```


= Why Use SVG Sprites? =

SVG, or Scalable Vector Graphics, allow you to add resolution-independent images to your websites. These images are generally much sharper and smaller in file size compared to other formats like JPEG.

However, each SVG image needs to be requested separately, which slows down your website. Adding SVG images to a sprite allows the browser to download multiple images with just one request, then show the individual pieces of the sprite separately. Depending on the number of images you're displaying, this can significantly speed up your website.

= More Information =

You can also [learn more about using Aqua SVG Sprite](http://www.thinkaquamarine.com/development/aqua-svg-sprite-plugin/), with advanced options and useful tips.

== Installation ==

1. Upload "aqua-svg-sprite" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Changelog ==

= 2.1.2 =

* Fixed bug causing the wrong ID to be saved as featured image.

= 2.1.1 =

* Added more flexibility to URI paths for sprite.
* Added better validation and sanitization of superglobals.
* Fixed legacy function echo bug.

= 2.1.0 =

* Added get_aqua_svg and the_aqua_svg API functions.
* Added tinymce shortcode button.
* Added validation (must have added SVG, must _be_ SVG).
* Added internationalization functions.

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
