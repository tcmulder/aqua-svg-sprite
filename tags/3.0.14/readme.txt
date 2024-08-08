=== Aqua SVG Sprite ===

Contributors: thinkaquamarine
Tags: svg sprite, svg, sprite
Requires at least: 5.6.0
Tested up to: 6.6.1
Stable tag: 3.0.14
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates SVG sprites.

== Description ==

This plugin allows you to create SVG sprites out of images from your WordPress media library.

= Why Use SVG Sprites? =

SVG, or Scalable Vector Graphics, allow you to add resolution-independent images to your websites. These images are generally much sharper and smaller in file size compared to other formats like JPEG.

However, each SVG image needs to be requested separately, which slows down your website. Adding SVG images to a sprite allows the browser to download multiple images with just one request, then show the individual pieces of the sprite separately. By reducing requests, this speeds up your site.

= Creating a Sprite =

Aqua SVG Sprite adds a new SVG Sprite menu to the WordPress sidebar, which functions a lot like the default Posts or Pages menus; you can add, edit, and trash individual SVG images under All Items.

When you add or edit an item, You’re able to choose a few things:

1. The Title makes it easy to find an individual SVG and is also used for selecting an SVG by the shortcode generator button.
2. The Slug is used as the ID for the symbol in the sprite, which essentially allows WordPress to extract one SVG image from the sprite and display it on the page.
3. The Aqua SVG Sprite Image is where you add a single SVG image that gets added to the sprite.
4. There are some basic instructions on usage, pre-populated with the ID and group (explained in the next section) for the individual SVG you’re viewing.
5. In the sidebar, you can select a Sprite Group as explained in the next section.
6. Last of course, Publish adds the SVG to the sprite.

= Creating Additional Sprite Groups =

If you’d like to use more than one sprite (the built in General sprite), you can add additional groups by clicking SVG Sprite > Sprite Groups in the WordPress sidebar. These work similar to WordPress tags, except you can only add each individual SVG image to one sprite group. Since they’re compiled into separate sprite groups, marking the same SVG for multiple groups would duplicate code, defeating the purpose of a sprite.

After you add a new Sprite Group, it appears as a selection in the right sidebar’s Sprite Group selector when creating or editing individual SVG sprite images.

= Using the Gutenberg Block =

Aqua SVG Sprite will add a new block called SVG Sprite to your Gutenberg block editor. After you add an SVG Sprite block, you can select the image you would like to use in the block inspector to the right. You can also add an advanced pseudo-array or HTML properties for more control, like `viewbox=0 0 1000 1000,fill=aquamarine` to add a viewbox and fill to the SVG element.

= Using the Shortcodes =

Aqua SVG Sprite will add a new `[SVG]` button to your classic editor (anywhere a WYSIWYG with TinyMCE appears), which will guide you through adding a shortcode to your content.

For example, you could display the "Some Slug" image from the default "General" sprite group like `[aqua-svg slug="some-slug"]`. If "Some Slug" were part of the "Some Group" sprite instead, you would use `[aqua-svg slug="some-slug" sprite="some-group"]`. You can also add a pseudo-array of HTML properties for more control like `[aqua-svg slug="some-slug" sprite="some-sprite" attr="viewbox=0 0 1000 1000,fill=aquamarine"]` to add a viewbox and fill to the SVG element..

= Using PHP Functions and Advanced Features =

You can [visit the documentation](http://www.thinkaquamarine.com/development/aqua-svg-sprite-plugin/) for more information on using PHP functions, along with code examples and details on more advanced features.

== Installation ==

1. Upload "aqua-svg-sprite" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Changelog ==

= 3.0.14 =

* Fixed trash and new post related PHP errors.

= 3.0.13 =

* Update stable tag.

= 3.0.12 =

* Test with WordPress 6.6.1.

= 3.0.11 =

* Update stable tag.

= 3.0.10 =

* Tested on WordPress 6.5.
* Fixed PHP 8 error when saving posts.

= 3.0.9 =

* Fixed changelog wording.

= 3.0.8 =

* Tested on WordPress 6.4.2.

= 3.0.7 =

* Tested on WordPress 6.3.

= 3.0.6 =

* Made spacing adjustments to block editor fields.
* Tested on WordPress 6.0.3.

= 3.0.5 =

* Tested on WordPress 5.6.0.

= 3.0.4 =

* Tested on WordPress 5.5.3.

= 3.0.3 =

* Removed unused activation hook call.
* Tested on WordPress 5.4.2.

= 3.0.2 =

* Fixed issue with gutenberg-block.js not being loaded.
* Tested on WordPress 5.4.

= 3.0.1 =

* Added gutenberg block.
* Prevent unsafe attributes from being added.
* Update documentation.

= 3.0.0 =

* Set groundwork for gutenberg block (beta).
* Fixed issue with HTTPS being used for HTTP sites.

= 2.1.5 =

* Update WordPress compatibility.

= 2.1.4 =

* Fixed bug causing special characters in titles to break symbol IDs.

= 2.1.3 =

* Added sprite recompilation on trash/untrash of svg images.
* Added sprite recompilation when switching groups (to remove from the previous sprite).
* Added deletion of sprite groups no longer containing any svg images.
* Updated readme with all basic instructions but a link to advanced features.

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
