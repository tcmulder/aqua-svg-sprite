=== Aqua SVG Sprite ===

Contributors: tcmulder
Tags: svg sprite, svg, sprite, acf
Requires at least: 4.7.1
Tested up to: 4.8.1
Stable tag: 0.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates an SVG sprite.

== Description ==

This plugin allows you to create an SVG sprite by uploading individual files to the WordPress media library.

Currently the API is pretty limited. A call to `aqua_svg('slug')` will output  svg use code for the sprite with an ID (post slug) of "slug". You can also pass in viewbox, additional attributes, and tell it to echo or not echo like so:
```
<?php
    $svg_use = aqua_svg('slug', '0 0 1000 1000', 'width="100" height="100"', false);
    echo $svg_use;
?>
```

== Installation ==

1. Upload "aqua-svg-sprite" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Changelog ==

= 0.0.3 =

* Add more details to readme.

= 0.0.2 =

* Fix issue where featured image script executed on save of non-SVG posts.

= 0.0.1 =

* Initial release.
