=== Apply ACF Layout ===

Contributors: tcmulder
Tags: acf, layout, layouts, template, templates, duplicate
Requires at least: 4.7.3
Tested up to: 4.7.3
Stable tag: 0.2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Allows you to apply Advanced Custom Field flexible content layouts to pages.

== Description ==

This plugin allows you to create layouts with various Advanced Custom Field (ACF) configurations and then apply those to the pages of your site. For instance, if you have a common layout that you'd like to use for a Team custom post type that includes a headshot module, a bio module, and a contact details module, you can create that as a layout in this plugin's Layouts area and name it Team Member Layout; then, when you're creating your individual Team posts, you can apply that Team Member Layout and the three modules will be populated on the page, ready for new content.

* This plugin creates a Layouts custom post type where you can create various ACF layouts you'd like to reuse.
* You can then apply those layouts to the pages of your site.
* You can choose which post types have the Apply ACF Layout functionality available to them.
* You can choose which which post types you can apply as layouts if you'd like to use post types other than the built in Layouts.
* You can create a new Layout from any existing page of any post type on your site. So if you've built a page already and later decide you'd like to use that layout for other pages, you can use generate a Layout from it for this purpose.

== Installation ==

1. Upload "apply-acf-layout" to the "/wp-content/plugins/" directory.
2. Activate the plugin through the "Plugins" menu in WordPress.

== Changelog ==

= 0.2.0 =

* Made it so any matching top-level ACF field from the layout chosen will be applied to the post chosen (instead of limiting this to just one flexible content field as in version 0.1.0).

= 0.1.0 =

* Initial release.
