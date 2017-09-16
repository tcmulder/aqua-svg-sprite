<?php

Class Aqua_SVG_Sprite {

	private static $initiated = false;

	/**
	 * Kick off first things.
	 */
	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	 /**
	 * Allow for upload of svg files.
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
	 * Attach methods to hooks.
	 */
	public static function init_hooks() {
		self::$initiated = true;
		add_action( 'init', array( 'Aqua_SVG_Sprite', 'create_svg_post_type' ) );
		add_filter( 'wp_check_filetype_and_ext', array( 'Aqua_SVG_Sprite', 'add_svg_mime_type' ), 10, 4 );
		add_filter( 'upload_mimes', array( 'Aqua_SVG_Sprite', 'cc_mime_types' ) );
		add_action( 'admin_head', array( 'Aqua_SVG_Sprite', 'fix_svg' ) );
		add_action( 'acf/save_post', array( 'Aqua_SVG_Sprite', 'create_svg_sprite' ), 1 );
		add_action( 'save_post_aqua_svg_sprite', array( 'Aqua_SVG_Sprite', 'save_group_meta_box' ) );
		add_action( 'save_post_aqua_svg_sprite', array( 'Aqua_SVG_Sprite', 'save_group_meta_box' ) );
		add_action( 'save_post', array( 'Aqua_SVG_Sprite', 'set_default_object_terms' ), 0, 2 );
	}

	/**
	 * Create custom post type for svg files.
	 */
	public static function create_svg_post_type() {

		// create taxonomy first
		register_taxonomy(
			'aqua_svg_sprite_group',
			'aqua_svg_sprite',
			array(
				'labels' => array(
					'name'                       => 'Sprite Groups', 'Taxonomy General Name', 'aqua_svg_sprite',
					'singular_name'              => 'Sprite Group', 'Taxonomy Singular Name', 'aqua_svg_sprite',
					'menu_name'                  => 'Sprite Groups', 'aqua_svg_sprite',
					'all_items'                  => 'All Items', 'aqua_svg_sprite',
					'parent_item'                => 'Parent Item', 'aqua_svg_sprite',
					'parent_item_colon'          => 'Parent Item:', 'aqua_svg_sprite',
					'new_item_name'              => 'New Item Name', 'aqua_svg_sprite',
					'add_new_item'               => 'Add New Item', 'aqua_svg_sprite',
					'edit_item'                  => 'Edit Item', 'aqua_svg_sprite',
					'update_item'                => 'Update Item', 'aqua_svg_sprite',
					'separate_items_with_commas' => 'Separate items with commas', 'aqua_svg_sprite',
					'search_items'               => 'Search Items', 'aqua_svg_sprite',
					'add_or_remove_items'        => 'Add or remove items', 'aqua_svg_sprite',
					'choose_from_most_used'      => 'Choose from the most used items', 'aqua_svg_sprite',
					'not_found'                  => 'Not Found', 'aqua_svg_sprite',
				),
				'meta_box_cb'       => array ( 'Aqua_SVG_Sprite', 'group_meta_box' ),
				'capabilities' => array(
					'manage__terms' => 'edit_posts',
					'edit_terms'    => 'manage_categories',
					'delete_terms'  => 'manage_categories',
					'assign_terms'  => 'edit_posts'
				)
			)
		);

		// create post type
		register_post_type( 'aqua_svg_sprite',
			array(
				'labels'       => array(
					'name'                       => 'SVG Sprite', 'Taxonomy General Name', 'aqua_svg_sprite',
					'singular_name'              => 'SVG Sprite', 'Taxonomy Singular Name', 'aqua_svg_sprite',
					'menu_name'                  => 'SVG Sprite', 'aqua_svg_sprite',
					'all_items'                  => 'All Items', 'aqua_svg_sprite',
					'parent_item'                => 'Parent Item', 'aqua_svg_sprite',
					'parent_item_colon'          => 'Parent Item:', 'aqua_svg_sprite',
					'new_item_name'              => 'New Item Name', 'aqua_svg_sprite',
					'add_new_item'               => 'Add New Item', 'aqua_svg_sprite',
					'edit_item'                  => 'Edit Item', 'aqua_svg_sprite',
					'update_item'                => 'Update Item', 'aqua_svg_sprite',
					'separate_items_with_commas' => 'Separate items with commas', 'aqua_svg_sprite',
					'search_items'               => 'Search Items', 'aqua_svg_sprite',
					'add_or_remove_items'        => 'Add or remove items', 'aqua_svg_sprite',
					'choose_from_most_used'      => 'Choose from the most used items', 'aqua_svg_sprite',
					'not_found'                  => 'Not Found', 'aqua_svg_sprite',
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

		// create a default term
		wp_insert_term( 'General', 'aqua_svg_sprite_group' );

		// connect the two
		register_taxonomy_for_object_type( 'aqua_svg_sprite_group', 'aqua_svg_sprite' );

		// create ACF fields (must be called after init builds taxonomies: normally called by acf/init instead)
		self::create_acf_feields();

	}

	/**
	 * Create field for uploading svg files.
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
					'instructions' => self::field_message(),
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
						'value' => 'aqua_svg_sprite',
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
	 * Create message to users for the field.
	 */
	public static function field_message() {
		// describe requirements
		$message = '
			<p>
				You\'re highly encouraged to run the file through an SVG compressor like
				<a href="https://jakearchibald.github.io/svgomg/" target="_blank">SVG OMG</a>
				before uploading it here. This will make the images load faster,
				but more importantly it will strip extra code added by image editors that could
				prevent your website from loading SVG images correctly. In most cases,
				images should be 1000x1000px in size, with the fill removed from the code.
			</p>
		';
		// provide API helpers
		if ( get_post_field( 'post_name', $_GET['post'] ) ) {
			// get this post's slug
			$slug = get_post_field( 'post_name', $_GET['post'] );
			// get the sprite this is part of (can only be one)
			$term_arr = wp_get_post_terms( $_GET['post'], 'aqua_svg_sprite_group' );
			$first_term_obj = $term_arr[0];
			$term_id = $first_term_obj->term_taxonomy_id;
			$term_obj = get_term_by( 'id', $term_id, 'aqua_svg_sprite_group' );
			$sprite_slug = $term_obj->slug;
			// set up the message text
			$message .='
			<p>
				Output this sprite item with default settings like so:
				<code>&lt;?php aqua_svg( \'' . $slug . '\'' . ( 'general' !== $sprite_slug ? ', \'' . $sprite_slug . '\'' : '' ) . ' ); ?&gt;</code>
				.
			</p>
			<p>
				Example using all options:
				<br>
<pre><code>&lt;?php
/* Get Sprite String and Echo */
$slug = \''. $slug .'\';
$sprite = \'' . $sprite_slug . '\';
$attr = array(
	\'viewbox\' => \'0 0 1000 1000\',
	\'fill\' => \'aquamarine\',
);
aqua_svg($slug,  $sprite, $attr );
?&gt;</code></pre>
			</p>

			';
		} else {
			$message .= '<p><em>(helpful API docs will appear here once you save the post)</em></p>';
		}
		return $message;

	}

	/**
	 * Add meta box for sprite group.
	 *
	 * @link http://sudarmuthu.com/blog/creating-single-select-wordpress-taxonomies/
	 * @param obj post object
	 */
	function group_meta_box( $post ) {
		$terms = get_terms( 'aqua_svg_sprite_group', array( 'hide_empty' => false ) );
		$post  = get_post();
		$group = wp_get_object_terms( $post->ID, 'aqua_svg_sprite_group', array( 'orderby' => 'term_id', 'order' => 'ASC' ) );
		$name  = '';
		if ( ! is_wp_error( $group ) ) {
			if ( isset( $group[0] ) && isset( $group[0]->name ) ) {
				$name = $group[0]->name;
			}
		}
		foreach ( $terms as $term ) {
			echo '<label title="' . __( esc_attr( $term->name ) ) . '">';
				echo '<input type="radio" name="aqua_sprite_group" value="' . __( esc_attr( $term->name ) ) . '" ' . checked( $term->name, $name, false ) . '>';
				echo '<span>' . esc_html_e( $term->name ) . '</span>';
			echo '</label><br>';
		}
	}

	/**
	 * Save the sprite group meta box results.
	 *
	 * @link http://sudarmuthu.com/blog/creating-single-select-wordpress-taxonomies/
	 * @param int $post_id The ID of the post that's being saved.
	 */
	function save_group_meta_box( $post_id ) {
		// handle autosaves
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// don't do stuff if this doesn't even have the meta box
		if ( ! isset( $_POST['aqua_sprite_group'] ) ) {
			return;
		}
		// get the value inpu
		$group = sanitize_text_field( $_POST['aqua_sprite_group'] );
		// if there is a value then update the term
		if ( ! empty( $group ) ) {
			$term = get_term_by( 'name', $group, 'aqua_svg_sprite_group' );
			if ( ! empty( $term ) && ! is_wp_error( $term ) ) {
				wp_set_object_terms( $post_id, $term->term_id, 'aqua_svg_sprite_group', false );
			}
		}
	}

	/**
	* Add an automatic default custom taxonomy for custom post type.
	* If no story (taxonomy) is set, the comic post will be sorted as “draft” and won’t return an offset error.
	* @link https://gist.github.com/mayeenulislam/f208b4fd408fd4742c06
	*/
	function set_default_object_terms( $post_id, $post ) {
		// only for the aqua sprites
		if ( 'publish' === $post->post_status && 'aqua_svg_sprite' === $post->post_type ) {
			// set default to "general" nothing is selected
			$defaults = array( 'aqua_svg_sprite_group' => array( 'general' ) );
			$taxonomies = get_object_taxonomies( $post->post_type );
			foreach ( (array) $taxonomies as $taxonomy ) {
				$terms = wp_get_post_terms( $post_id, $taxonomy );
				if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
					wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
				}
			}
		}
	}

	/**
	* Rebuild svg sprite on save of svg post type posts.
	*/
	public static function create_svg_sprite( $post_id ) {

		if ( 'aqua_svg_sprite' === get_post_type( $post_id ) ) {
			// get the directory where the svg sprite goes (within uploads)
			$wp_upload_dir = wp_upload_dir();
			$aqua_svg_sprite_dir = $wp_upload_dir['basedir'] . '/aqua-svg-sprite';
			// create the directory if it doesn't already exist
			if ( ! file_exists( $aqua_svg_sprite_dir ) ) {
				mkdir( $aqua_svg_sprite_dir, 0755, true );
			}
			// get the post term (there will only be one)
			$term = 'general';
			$terms_arr = wp_get_post_terms( $post_id, 'aqua_svg_sprite_group' );
			if ( $terms_arr ) {
				$term_obj = $terms_arr[ 0 ];
				$term = $term_obj->slug;
			}

			// start the svg internals (symbols)
			$svg_symbols = '';
			// loop through all svgs in this group
			$args = array(
				'post_type'         => 'aqua_svg_sprite',
				'posts_per_page'    => -1,
				'tax_query' => array(
					array(
						'taxonomy' => 'aqua_svg_sprite_group',
						'field'    => 'slug',
						'terms'    => $term,
					),
				),
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
				while( $query->have_posts() ) { $query->the_post();
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
					$svg_id = ( get_the_id() !== $post_id ? get_field( 'svg' ) : $_POST['acf']['field_58d70aee44096'] );
					// establish the slug (used as id for sprite)
					$slug = strtolower( trim( preg_replace( '/[\s-]+/', '-', preg_replace( '/[^A-Za-z0-9-]+/', '-', preg_replace( '/[&]/', 'and', preg_replace( '/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', get_the_title() ) ) ) ) ), '-' ) );
					// create svg code and strip out unneeded elements
					$svg = file_get_contents(get_attached_file(wp_kses($svg_id, $allowed)));
					// get rid of classes and ids
					$svg = preg_replace( '#\s(id|class)="[^"]+"#', '', $svg );
					// get rid of <?xml ...
					$svg = preg_replace( '/\s*<\?xml.*?>/i', '', $svg );
					// get rid of comments
					$svg = preg_replace( '/\s*<\!--.*?-->/i', '', $svg );
					// change svg to symbol
					$svg = preg_replace( '/<svg/i', '<symbol id="'.$slug.'"', $svg );
					$svg = preg_replace( '/<\/svg>/i', '</symbol>', $svg );
					// // get rid of xml namespaces (should be on <svg> instead of <symbol>)
					$svg = preg_replace( '/\s*xmlns.*?".*?"/i', '', $svg );
					// add this svg to the sprite
					$svg_symbols .= $svg;
				}
			}
			// wrap svg internals
			$svg_sprite = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
			$svg_sprite .= $svg_symbols;
			$svg_sprite .= '</svg>';
			// create the svg file (rebuilds each time)
			file_put_contents( $aqua_svg_sprite_dir . '/aqua-svg-' . $term . '-sprite.svg', $svg_sprite );
			// update the featured image to the uploaded image (allows acf relationship fields to show previews)
			update_post_meta( $post_id, '_thumbnail_id', $_POST['acf']['field_58d70aee44096'] );
		}

	}


}
