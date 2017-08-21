<?php

Class Aqua_SVG_Sprite {

	private static $initiated = false;

	/**
	 * Kick off first things
	 */
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	/**
	 * Attach methods to hooks
	 */
	public static function init_hooks() {
		self::$initiated = true;
		add_action( 'init', array( 'Aqua_SVG_Sprite', 'create_acf_feields' ) );
		add_action( 'init', array( 'Aqua_SVG_Sprite', 'create_svg_post_type' ) );
		add_filter( 'wp_check_filetype_and_ext', array( 'Aqua_SVG_Sprite', 'add_svg_mime_type' ), 10, 4 );
		add_filter( 'upload_mimes', array( 'Aqua_SVG_Sprite', 'cc_mime_types' ) );
		add_action( 'admin_head', array( 'Aqua_SVG_Sprite', 'fix_svg' ) );
		add_action( 'acf/save_post', array( 'Aqua_SVG_Sprite', 'create_svg_sprite' ), 1 );
	}

	/**
	 * Create field for uploading svg files
	 */
	public static function create_acf_feields() {

		if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array (
			'key' => 'group_58d70ae925cf4',
			'title' => 'SVG Sprite',
			'fields' => array (
				array (
					'key' => 'field_58d70aee44096',
					'label' => 'SVG File',
					'name' => 'svg',
					'type' => 'image',
					'instructions' => 'You\'re highly encouraged to run the file through an SVG compressor like <a href="https://jakearchibald.github.io/svgomg/" target="_blank">SVG OMG</a> before uploading it here. This will make the images load faster, but more importantly it will strip extra code added by image editors that could prevent your website from loading SVG images correctly.

		Images must be 1000x1000px in size, with the fill removed from the code.',
					'required' => 1,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
					'library' => 'all',
					'min_width' => '',
					'min_height' => '',
					'min_size' => '',
					'max_width' => '',
					'max_height' => '',
					'max_size' => '',
					'mime_types' => '.svg',
				),
			),
			'location' => array (
				array (
					array (
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'aqua-svg-sprite',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => array (
				0 => 'permalink',
				1 => 'the_content',
				2 => 'excerpt',
				3 => 'custom_fields',
				4 => 'discussion',
				5 => 'comments',
				6 => 'revisions',
				7 => 'slug',
				8 => 'author',
				9 => 'format',
				10 => 'page_attributes',
				11 => 'categories',
				12 => 'tags',
				13 => 'send-trackbacks',
				14 => 'featured_image',
			),
			'active' => 1,
			'description' => '',
		));

		endif;

	}

	/**
	 * Create custom post type for svg files
	 */
	public static function create_svg_post_type() {

		register_post_type('aqua-svg-sprite',
			array(
				'labels'       => array(
					'name'                       => 'SVG Sprite', 'Taxonomy General Name', 'text_domain',
					'singular_name'              => 'SVG Sprite', 'Taxonomy Singular Name', 'text_domain',
					'menu_name'                  => 'SVG Sprite', 'text_domain',
					'all_items'                  => 'All Items', 'text_domain',
					'parent_item'                => 'Parent Item', 'text_domain',
					'parent_item_colon'          => 'Parent Item:', 'text_domain',
					'new_item_name'              => 'New Item Name', 'text_domain',
					'add_new_item'               => 'Add New Item', 'text_domain',
					'edit_item'                  => 'Edit Item', 'text_domain',
					'update_item'                => 'Update Item', 'text_domain',
					'separate_items_with_commas' => 'Separate items with commas', 'text_domain',
					'search_items'               => 'Search Items', 'text_domain',
					'add_or_remove_items'        => 'Add or remove items', 'text_domain',
					'choose_from_most_used'      => 'Choose from the most used items', 'text_domain',
					'not_found'                  => 'Not Found', 'text_domain',
				),
				'menu_icon' => 'dashicons-images-alt',
				'public' => false,
				'show_ui' => true,
				'menu_position' => 100, // bottom-ish
				'supports' => array(
					'editor',
					'custom-fields',
					'title',
					'page-attributes',
					'thumbnail',
				),
			)
		);

	}

	/**
	 * Allow for upload of svg files
	 * workaround needed, see: https://codepen.io/chriscoyier/post/wordpress-4-7-1-svg-upload
	 */
	public static function add_svg_mime_type ( $data, $file, $filename, $mimes ) {

	  $filetype = wp_check_filetype( $filename, $mimes );

	  return [
		  'ext'             => $filetype['ext'],
		  'type'            => $filetype['type'],
		  'proper_filename' => $data['proper_filename']
	  ];

	}

	public static function cc_mime_types( $mimes ){
	  $mimes['svg'] = 'image/svg+xml';
	  return $mimes;
	}

	public static function fix_svg() {
	  echo '<style type="text/css">
			#postimagediv .inside img, .thumbnail img {
				 width: 100% !important;
				 height: auto !important;
			}
			</style>';
	}

	/**
	* Rebuild svg sprite on save of svg post type posts
	*/
	public static function create_svg_sprite( $post_id ) {

		if ( 'aqua-svg-sprite' === get_post_type( $post_id ) ) {
			// get the directory where the svg sprite goes (within uploads)
			$wp_upload_dir = wp_upload_dir();
			$aqua_svg_sprite_dir = $wp_upload_dir['basedir'] . '/aqua-svg-sprite';
			// create the directory if it doesn't already exist
			if ( ! file_exists( $aqua_svg_sprite_dir ) ) {
				mkdir( $aqua_svg_sprite_dir, 0777, true );
			}
			// start the svg sprite wrapper
			$svg_sprite = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
			// loop through all svgs
			$args = array(
				'post_type'         => 'aqua-svg-sprite',
				'posts_per_page'    => -1,
			);
			$query = new WP_Query( $args );
			if($query->have_posts()){
				while($query->have_posts()){ $query->the_post();
					// allow just svg-related info (used for wp_kses)
					$allowed = array(
						'svg' => array(
							'width' => array(),
							'height' => array(),
							'viewbox' => array(),
							'version' => array(),
							'xmlns' => array(),
							'xmlns:xlink' => array(),
						),
						'g' => array(
							'stroke' => array(),
							'stroke-width' => array(),
							'fill' => array(),
							'fill-rule' => array(),
						),
						'path' => array(
							'd' => array(),
							'id' => array(),
						),
					);
					// get the id via acf or through the post data if is current post
					$svg_id = (get_the_id() !== $post_id ? get_field('svg') : $_POST['acf']['field_58d70aee44096']);
					// establish the slug (used as id for sprite)
					$slug = strtolower(trim(preg_replace('/[\s-]+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '-', preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', get_the_title()))))), '-'));
					// create svg code and strip out unneeded elements
					$svg = file_get_contents(get_attached_file(wp_kses($svg_id, $allowed)));
					$svg = preg_replace('#\s(id|class)="[^"]+"#', '', $svg);
					$svg = preg_replace('/<svg/i', '<symbol id="'.$slug.'"', $svg);
					$svg = preg_replace('/<\/svg>/i', '</symbol>', $svg);
					// add this svg to the sprite
					$svg_sprite .= $svg;
				}
			}
			// close the sprite wrapper
			$svg_sprite .= '</svg>';
			// create the svg file (rebuilds each time)
			file_put_contents( $aqua_svg_sprite_dir . '/aqua-svg-sprite.svg', $svg_sprite );
			// update the featured image to the uploaded image (allows acf relationship fields to show previews)
			update_post_meta($post_id, '_thumbnail_id', $_POST['acf']['field_58d70aee44096']);
		}

	}


}
