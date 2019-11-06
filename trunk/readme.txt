=== Aqua SVG Sprite ===

Contributors: thinkaquamarine 
Tags: svg sprite, svg, sprite
Requires at least: 4.7.1
Tested up to: 5.2.4
Stable tag: 3.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates SVG sprites.

== Description ==

This plugin allows you to create an SVG sprite by uploading individual files to the WordPress media library.

= Why Use SVG Sprites? =

SVG, or Scalable Vector Graphics, allow you to add resolution-independent images to your websites. These images are generally much sharper and smaller in file size compared to other formats like JPEG.

However, each SVG image needs to be requested separately, which slows down your website. Adding SVG images to a sprite allows the browser to download multiple images with just one request, then show the individual pieces of the sprite separately. Depending on the number of images you're displaying, this can significantly speed up your website.

= Creating a Sprite =

Aqua SVG Sprite adds a new SVG Sprite menu to the WordPress sidebar, which functions a lot like the default Posts or Pages menus; you can add, edit, and delete individual SVG images under All Items.

When you add or edit an item, You’re able to choose a few things:

1. The Title makes it easy to find an individual SVG and is also used for selecting an SVG by the shortcode generator button.
2. The Slug is used as the ID for the symbol in the sprite, which essentially allows WordPress to extract one SVG image from the sprite and display it on the page.
3. The Aqua SVG Sprite Image is where you add a single SVG image that gets added to the sprite.
4. There are some basic instructions on usage, pre-populated with the ID and group (explained in the next section) for the individual SVG you’re viewing.
5. In the sidebar, you can select a Sprite Group as explained in the next section.
6. Last of course, Publish adds the SVG to the sprite.

= Creating Additional Sprite Groups =

If you’d like to use more than one sprite (the built in General sprite), you can add additional groups by going to the sidebar and clicking SVG Sprite > Sprite Groups. These work similar to WordPress tags, except you can only add each individual SVG image to one sprite group. Since they’re compiled into separate sprite groups, marking the same SVG for multiple groups would duplicate code, somewhat defeating the purpose of a sprite.

After you add a new Sprite Group, it appears as a selection in the right sidebar’s Sprite Group selector when creating or editing individual SVG images.

= Using the Shortcodes =

Aqua SVG Sprite will add a new `[SVG]` button to your editor (anywhere a WYSIWYG with TinyMCE appears), which will guide you through adding a shortcode to your content.

For example, you could display the "Some Slug" image from the default "General" sprite group like `[aqua-svg slug="some-slug"]`. If "Some Slug" were part of the "Some Group" sprite instead, you would use `[aqua-svg slug="some-slug" sprite="some-group"]`. You can also add a pseudo-array of HTML properties for more control like `[aqua-svg slug="some-slug" sprite="some-sprite" attr="viewbox=0 0 1000 1000,fill=aquamarine"]`.

= Using PHP Functions and Advanced Features =

You can [visit the documentation](http://www.thinkaquamarine.com/development/aqua-svg-sprite-plugin/) for more information on using PHP functions, along with code examples and details on more advanced features.

== Installation ==

1. Upload "aqua-svg-sprite" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Changelog ==

= 3.0.0 =

* Fixed issue with HTTPS being used for HTTPS sites.

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
