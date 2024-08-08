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
		  'ext'				=> $filetype['ext'],
		  'type'			=> $filetype['type'],
		  'proper_filename'	=> $data['proper_filename']
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
		add_action( 'init', array( 'Aqua_SVG_Sprite', 'register_gutenberg_block' ) );
		add_action( 'init', array( 'Aqua_SVG_Sprite', 'create_svg_post_type' ) );
		add_action( 'admin_enqueue_scripts', array( 'Aqua_SVG_Sprite', 'add_admin_scripts' ) );
		add_action( 'admin_head', array( 'Aqua_SVG_Sprite', 'fix_svg' ) );
		add_action( 'admin_head', array( 'Aqua_SVG_Sprite', 'register_shortcode_button' ) );
		add_action( 'add_meta_boxes', array( 'Aqua_SVG_Sprite', 'aqua_svg_add_meta_boxes' ) );
		add_action( 'before_wp_tiny_mce', array( 'Aqua_SVG_Sprite', 'localize_shortcode_button_scripts' ) );
		add_action( 'pre_post_update', array( 'Aqua_SVG_Sprite', 'validate_values' ) );
		add_action( 'save_post_aqua_svg_sprite', array( 'Aqua_SVG_Sprite', 'set_default_object_terms' ), 0, 2 );
		add_action( 'save_post_aqua_svg_sprite', array( 'Aqua_SVG_Sprite', 'save_group_meta_box' ), 1 );
		add_action( 'save_post_aqua_svg_sprite', array( 'Aqua_SVG_Sprite', 'save_aqua_svg_sprite_meta_box' ), 2 );
		add_action( 'save_post_aqua_svg_sprite', array( 'Aqua_SVG_Sprite', 'request_svg_sprite_creation' ), 3 );
		add_action( 'wp_trash_post', array( 'Aqua_SVG_Sprite', 'request_svg_sprite_creation' ) );
		add_filter( 'upload_mimes', array( 'Aqua_SVG_Sprite', 'cc_mime_types' ) );
		add_filter( 'wp_check_filetype_and_ext', array( 'Aqua_SVG_Sprite', 'add_svg_mime_type' ), 10, 4 );
		add_action( 'rest_api_init', array( 'Aqua_SVG_Sprite', 'add_rest_endpoint' ) );
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
					'name'                       => 'Sprite Groups', 'Taxonomy General Name', 'aqua-svg-sprite',
					'singular_name'              => 'Sprite Group', 'Taxonomy Singular Name', 'aqua-svg-sprite',
					'menu_name'                  => 'Sprite Groups', 'aqua-svg-sprite',
					'all_items'                  => 'All Items', 'aqua-svg-sprite',
					'parent_item'                => 'Parent Item', 'aqua-svg-sprite',
					'parent_item_colon'          => 'Parent Item:', 'aqua-svg-sprite',
					'new_item_name'              => 'New Item Name', 'aqua-svg-sprite',
					'add_new_item'               => 'Add New Item', 'aqua-svg-sprite',
					'edit_item'                  => 'Edit Item', 'aqua-svg-sprite',
					'update_item'                => 'Update Item', 'aqua-svg-sprite',
					'separate_items_with_commas' => 'Separate items with commas', 'aqua-svg-sprite',
					'search_items'               => 'Search Items', 'aqua-svg-sprite',
					'add_or_remove_items'        => 'Add or remove items', 'aqua-svg-sprite',
					'choose_from_most_used'      => 'Choose from the most used items', 'aqua-svg-sprite',
					'not_found'                  => 'Not Found', 'aqua-svg-sprite',
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
					'name'                       => 'SVG Sprite', 'Taxonomy General Name', 'aqua-svg-sprite',
					'singular_name'              => 'SVG Sprite', 'Taxonomy Singular Name', 'aqua-svg-sprite',
					'menu_name'                  => 'SVG Sprite', 'aqua-svg-sprite',
					'all_items'                  => 'All Items', 'aqua-svg-sprite',
					'parent_item'                => 'Parent Item', 'aqua-svg-sprite',
					'parent_item_colon'          => 'Parent Item:', 'aqua-svg-sprite',
					'new_item_name'              => 'New Item Name', 'aqua-svg-sprite',
					'add_new_item'               => 'Add New Item', 'aqua-svg-sprite',
					'edit_item'                  => 'Edit Item', 'aqua-svg-sprite',
					'update_item'                => 'Update Item', 'aqua-svg-sprite',
					'separate_items_with_commas' => 'Separate items with commas', 'aqua-svg-sprite',
					'search_items'               => 'Search Items', 'aqua-svg-sprite',
					'add_or_remove_items'        => 'Add or remove items', 'aqua-svg-sprite',
					'choose_from_most_used'      => 'Choose from the most used items', 'aqua-svg-sprite',
					'not_found'                  => 'Not Found', 'aqua-svg-sprite',
				),
				'menu_icon' => 'dashicons-images-alt',
				'public' => false,
				'show_ui' => true,
				'menu_position' => 100, // bottom-ish
				'show_in_rest' => true,
				'supports' => array(
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

	}

	/**
	 * Add Admin CSS/JS.
	 */
	public static function add_admin_scripts() {

		if( 'aqua_svg_sprite' === get_post_type() ) {
			// load the CSS
			wp_register_style( 'aqua_svg_sprite_admin_styles', AQUA_SVG_SPRITE_PLUGIN_URI .'assets/css/aqua-svg-sprite-admin.css', false, '1.0.0' );
			wp_enqueue_style( 'aqua_svg_sprite_admin_styles' );
			// load the JS
			wp_register_script( 'aqua_svg_sprite_admin_scripts', AQUA_SVG_SPRITE_PLUGIN_URI .'assets/js/aqua-svg-sprite-admin.js', 'jquery', '1.0.0', true );
			wp_enqueue_script( 'aqua_svg_sprite_admin_scripts' );
		}

	}

	/**
	 * Add Gutenberg Block
	 */
	public static function register_gutenberg_block() {
		// register the js and define dependencies
		wp_register_script(
			'svg-use',
			AQUA_SVG_SPRITE_PLUGIN_URI .'assets/js/gutenberg-block.js',
			array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-api-fetch', 'wp-i18n' )
		);
		// register the block type itself
		register_block_type( 'aqua-svg-sprite/svg-use', array(
			'attributes' => array(
				'slug' => array(
					'type' => 'string',
					'default' => '',
				),
				'properties' => array(
					'type' => 'string',
					'default' => 'width=50,height=50',
				),
			),
			'editor_script' => 'svg-use',
			'render_callback' => array( 'Aqua_SVG_Sprite', 'gutenberg_dynamic_render_callback' )
		) );

	}

	public static function gutenberg_dynamic_render_callback( $attr, $content ) {

		// get the svg code and return it
		if ( $attr[ 'slug' ] ) {
			// parse attributes
			$slug_sprite = explode( ',', $attr[ 'slug' ] );
			// use shortcode as it parses properties and escapes all input
			return do_shortcode( '[aqua-svg slug="' . $slug_sprite[ 0 ] . '" sprite="' . $slug_sprite[ 1 ] . '" attr="' . $attr[ 'properties' ] . '"]' );
		}

		// return nothing if there's no slug (possibly true when adding a new onw)
		return '';

	}

	/**
	 * Create REST endpoint to collect posts.
	 */
	public static function add_rest_endpoint() {
		register_rest_route( 'aqua-svg-sprite/v1', '/svg/', array(
			'methods' => 'GET',
			'callback' => array( 'Aqua_SVG_Sprite', 'get_all_svgs' ),
		) );
	}
	public static function get_all_svgs() {
		$svg_posts = get_posts( array( 'numberposts' => 500, 'post_type'   => 'aqua_svg_sprite', 'post_status' => 'publish' ) );
		$svg_arr = array( array( 'slug' => '', 'title' => 'No Sprites Found', 'sprite' => '' ) );
		if ( $svg_posts ) {
			$svg_arr = array();
			foreach( $svg_posts as $svg ) {
				$all_terms = get_the_terms( $svg->ID, 'aqua_svg_sprite_group' );
				$term_obj = $all_terms[ 0 ];
				$term_slug = $term_obj->slug;
				$svg_arr[] = array(
					'slug' => $svg->post_name,
					'title' => $svg->post_title,
					'sprite' => $term_slug,
				);
			}
		}
		// return $svg_posts;
		return $svg_arr;
	}


	/**
	 * Create SVG insert button.
	 */
	public static function register_shortcode_button() {
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( 'Aqua_SVG_Sprite', 'add_shortcode_script' ) );
			add_filter( 'mce_buttons', array( 'Aqua_SVG_Sprite', 'register_mce_buttons' ) );
		}
	}

	// add the path to the js file with the custom button function
	public static function add_shortcode_script( $plugin_array ) {
		$plugin_array['aqua_svg_sprite_button'] = AQUA_SVG_SPRITE_PLUGIN_URI .'assets/js/tinymce-button.js';
		return $plugin_array;
	}

	// register and add new button in the editor
	public static function register_mce_buttons( $buttons ) {
		array_push( $buttons, 'aqua_svg_sprite_button' );
		return $buttons;
	}

	// localize scripts for button
	public static function localize_shortcode_button_scripts( $buttons ) {
		// get all the sprites and their groups
		$sprite_info = array();
		$query = new WP_Query( array( 'post_type' => 'aqua_svg_sprite' ) );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				// get ready for name/slug of post
				global $post;
				// get the group
				$terms = get_the_terms( get_the_id(), 'aqua_svg_sprite_group' );
				$term = $terms[0];
				// add them to the array
				array_push( $sprite_info,
					array (
						'svg' => array(
							'name' => $post->post_title,
							'slug' => $post->post_name,
						),
						'sprite' => array(
							'name' => $term->name,
							'slug' => $term->slug,
						),
					)
				);
			}
		}
		// add directly to the page (because wp_localize_script has nothing to tie into)
		echo '
			<script type="text/javascript">
			/* <![CDATA[ */
			var aquaSVGSpriteShortcode = ' . json_encode( $sprite_info ) . ';
			/* ]]> */
			</script>
		';
	}

	/**
	 * Create field for uploading svg files.
	 */
	// orchestrate everything
	public static function aqua_svg_add_meta_boxes() {

		add_meta_box( 'aqua-svg-image', __( 'Aqua SVG Sprite Image', 'aqua-svg-sprite' ), array( 'Aqua_SVG_Sprite', 'meta_box_backend' ), 'aqua_svg_sprite', 'normal', 'low' );
		add_meta_box( 'aqua-svg-image-instructions', __( 'Aqua SVG Sprite Usage Instructions', 'aqua-svg-sprite' ), array( 'Aqua_SVG_Sprite', 'meta_box_instructions' ), 'aqua_svg_sprite', 'normal', 'low' );

	}

	// add the back-end fields
	public static function meta_box_backend( $post ) {
		// make it secure
		wp_nonce_field( 'aqua_svg_sprite_submit', 'aqua_svg_sprite_nonce' );
		// get some meta data
		$stored_meta = get_post_meta( $post->ID );
		$stored_id = ( isset ( $stored_meta['aqua-svg'] ) ) ? $stored_meta['aqua-svg'][0] : '';
		$image_arr = wp_get_attachment_image_src( $stored_id );
		// set up the preview image (not an img if no src available, e.g. new posts)
		$image = '<span id="aqua-svg-preview">';
		if ( $image_arr ) {
			$image = '<img src="' . $image_arr[0] . '" id="aqua-svg-preview" style="max-width:200px;height:auto;" alt="' . esc_html__( 'SVG preview image', 'aqua-svg-sprite' ) . '" />';
		}

		$html = '<p>' . $image . '</p>';
		$html .= '<p>';
			$html .= '<input type="hidden" name="aqua-svg" id="aqua-svg" class="meta_image" value="' . $stored_id . '" />';
			$html .= '<input type="button" id="aqua-svg-button" class="button" value="' . __( 'Choose or Upload an Image', 'aqua-svg-sprite' ) . '" />';
		$html .= '</p>';

		echo $html;

	}

	// add instructions
	public static function meta_box_instructions( $post ) {

		// describe requirements
		$message = sprintf( __( '
			<p>
				<em>Pro Tip:</em> It\'s a great idea to run the file through an SVG compressor like
				%1$s
				before uploading it here so you can apply manual compression.
			</p>
		', 'aqua-svg-sprite' ), '<a href="https://jakearchibald.github.io/svgomg/" target="_blank">SVG OMG</a>' );
		// get valid post id
		$post_id = isset( $_GET['post'] ) ? ( int ) $_GET['post'] : 0;
		// provide API helpers
		if ( get_post_field( 'post_name', $post_id ) ) {
			// get this post's slug
			$slug = get_post_field( 'post_name', $post_id );
			// get the sprite this is part of (can only be one)
			$term_arr = wp_get_post_terms( $post_id, 'aqua_svg_sprite_group' );
			$first_term_obj = $term_arr[0] ?? "NO_SPRITE_GROUP_CHOSEN";
			$term_id = $first_term_obj->term_taxonomy_id ?? "NO_SPRITE_GROUP_CHOSEN";
			$term_obj = get_term_by( 'id', $term_id, 'aqua_svg_sprite_group' );
			$sprite_slug = $term_obj->slug ?? "NO_SPRITE_GROUP_CHOSEN";
			// set up the message text
			$message .='

<p><strong>' . __( 'Basic shortcode usage', 'aqua-svg-sprite' ) . ':</strong></p>
<p><code>[aqua-svg slug="' . $slug . '"' . ( 'general' !== $sprite_slug ? ' sprite="' . $sprite_slug . '"' : '' ) . ']</code></p>

<p><strong>' . __( 'More complex shortcode example', 'aqua-svg-sprite' ) . ':</strong></p>
<p><code>[aqua-svg slug="' . $slug . '" sprite="' . $sprite_slug . '" attr="viewbox=0 0 1000 1000,fill=aquamarine"]</code></p>

<p><strong>' . __( 'PHP usage', 'aqua-svg-sprite' ) . ':</strong></p>
<p><code>&lt;?php the_aqua_svg( \'' . $slug . '\'' . ( 'general' !== $sprite_slug ? ', \'' . $sprite_slug . '\'' : '' ) . ' ); ?&gt;</code></p>

<p><strong>' . __( 'More complex PHP example', 'aqua-svg-sprite' ) . ':</strong></p>
<pre><code class="aqua-svg-sprite-multiline">&lt;?php
	/* Get Sprite String and Echo */
	$slug = \''. $slug .'\';
	$sprite = \'' . $sprite_slug . '\';
	$attr = array(
		\'viewbox\' => \'0 0 1000 1000\',
		\'fill\' => \'aquamarine\',
	);
	echo get_aqua_svg( $slug, $sprite, $attr );
?&gt;</code></pre>

			';
		} else {
			$message .= '<p><em>(' . __( 'helpful API docs will appear here once you save the post', 'aqua-svg-sprite' ) . ')</em></p>';
		}

		// output it
		echo $message;

	}

	// save meta box results
	public static function save_aqua_svg_sprite_meta_box( $post_id ) {
		// check to make sure this should be happening
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'aqua_svg_sprite_nonce' ] ) && wp_verify_nonce( $_POST[ 'aqua_svg_sprite_nonce' ], 'aqua_svg_sprite_submit' ) ) ? 'true' : 'false';

		// exit if not
		if ( $is_autosave || $is_revision || ! $is_valid_nonce  ) {
			return;
		}

		// save the new attachment id as the thumb for this post
		$att_id = isset( $_POST['aqua-svg'] ) ? ( int ) $_POST['aqua-svg'] : 0;
		if( isset( $att_id ) && 0 < $att_id ) {
			update_post_meta( $post_id, 'aqua-svg', $att_id );
		}
	}

	/**
	 * Add meta box for sprite group.
	 *
	 * @link http://sudarmuthu.com/blog/creating-single-select-wordpress-taxonomies/
	 * @param obj post object
	 */
	public static function group_meta_box( $post ) {
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
	 * Save the sprite group meta box values.
	 *
	 * @link http://sudarmuthu.com/blog/creating-single-select-wordpress-taxonomies/
	 * @param int $post_id The ID of the post that's being saved.
	 */
	public static function save_group_meta_box( $post_id ) {

		// handle autosaves
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		// don't do stuff if this doesn't even have the meta box
		if ( ! isset( $_POST['aqua_sprite_group'] ) ) {
			return;
		}
		// validate nonce
		$is_valid_nonce = ( isset( $_POST[ 'aqua_svg_sprite_nonce' ] ) && wp_verify_nonce( $_POST[ 'aqua_svg_sprite_nonce' ], 'aqua_svg_sprite_submit' ) ) ? 'true' : 'false';
		if ( ! $is_valid_nonce ) {
			return;
		}
		// get the value input
		$group = sanitize_text_field( $_POST['aqua_sprite_group'] );
		// if there is a value
		if ( ! empty( $group ) ) {
			$term = get_term_by( 'name', $group, 'aqua_svg_sprite_group' );
			if ( ! empty( $term ) && ! is_wp_error( $term ) ) {

				// get the new term slug
				$new_term = $term->slug;
				// get the current term id (general by default)
				$old_term_arr = get_the_terms( $post_id, 'aqua_svg_sprite_group' );
				$old_term_obj = $old_term_arr[0];
				$old_term = $old_term_obj->slug;
				// update the term
				wp_set_object_terms( $post_id, $term->term_id, 'aqua_svg_sprite_group', false );
				// if the term has now changed
				if ( $new_term !== $old_term ) {
					// create code for the old sprite to remove this svg from it
					self::create_svg_sprite( 0, $old_term );
				}
			}
		}

	}

	/**
	* Add an automatic default custom taxonomy for custom post type.
	* If no story (taxonomy) is set, the comic post will be sorted as “draft” and won’t return an offset error.
	* @link https://gist.github.com/mayeenulislam/f208b4fd408fd4742c06
	*/
	public static function set_default_object_terms( $post_id, $post ) {

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
	* Validate before saving posts.
	*/
	public static function validate_values( $post_id ) {

		if ( 'aqua_svg_sprite' === get_post_type( $post_id ) ) {
			// if there's post value (empty if moving to trash)
			if ( ! empty( $_POST ) ) {
				// get the attachment id
				$att_id = ( int ) $_POST['aqua-svg'];
				// if there is no SVG attached
				if ( ! $att_id ) {
					wp_die( 'You must add an SVG before saving.'.$att_id, 'Error - Missing SVG', array( 'back_link' => true ) );
				}
				$attachment_src_arr = wp_get_attachment_image_src( $att_id );
				$ext = pathinfo( $attachment_src_arr[0], PATHINFO_EXTENSION );
				if ( 'svg' !== $ext ) {
					wp_die( 'You must choose an SVG file (file extension of chosen file was ".' . $ext . '").', 'Error - Not SVG', array( 'back_link' => true ) );
				}
			}
		}

	}

	/**
	* Set up request for SVG sprite creation on hooks (given only post ID).
	*/
	public static function request_svg_sprite_creation( $post_id ) {

		// called on saves: make sure this is of the right custom post type before executing
		if ( 'aqua_svg_sprite' === get_post_type( $post_id ) ) {
			// get the post term (there will only be one) or default to general
			$group = 'general';
			$terms_arr = wp_get_post_terms( $post_id, 'aqua_svg_sprite_group' );
			if ( $terms_arr ) {
				$term_obj = $terms_arr[ 0 ];
				$group = $term_obj->slug;
			}
			// create code for this sprite
			self::create_svg_sprite( $post_id, $group );
		}

	}

	/**
	* Create svg code.
	*/
	public static function create_svg_sprite( $post_id, $group ) {

		// store the svg sprite code
		$svg_sprite = '';
		// loop through all svgs in this group
		$args = array(
			'post_type'         => 'aqua_svg_sprite',
			'posts_per_page'    => -1,
			'tax_query' => array(
				array(
					'taxonomy' => 'aqua_svg_sprite_group',
					'field'    => 'slug',
					'terms'    => $group,
				),
			),
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			// store the svg internals (symbols)
			$svg_symbols = '';
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
				// store the svg's id
				$attachment_id = 0;
				// if loop has reached this current post then use the value being posted
				$svg_id = isset( $_POST['aqua-svg'] ) ? ( int ) $_POST['aqua-svg'] : 0;
				$attachment_id = ( get_the_id() === $post_id ? ( int ) $_POST['aqua-svg'] : 0 );
				// if on a different post or if the value is still 0 (e.g. untrash_post hook) then query db
				$attachment_id = ( 0 === $attachment_id ? get_post_meta( get_the_id(), 'aqua-svg', true ) : $attachment_id );
				// get the slug (used as id for sprite)
				$slug = basename( get_permalink() );
				// create svg code and strip out unneeded elements
				$svg = file_get_contents( get_attached_file( wp_kses( $attachment_id, $allowed ) ) );
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
			// create the svg code with symbols inside
			$svg_sprite  = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">';
			$svg_sprite .= $svg_symbols;
			$svg_sprite .= '</svg>';
			// write svg code to the sprite file
			self::write_svg_sprite( $svg_sprite, $group );
			// update the featured image to the uploaded image
			$svg_id = isset( $_POST['aqua-svg'] ) ? ( int ) $_POST['aqua-svg'] : 0;
			update_post_meta( $post_id, '_thumbnail_id', $svg_id );
		// if there are no images left in this sprite
		} else {
			// get rid of the empty sprite file
			self::delete_svg_sprite( $group );
		}

	}

	/**
	* Write svg code into the sprite file.
	*/
	public static function write_svg_sprite( $svg, $group ) {

		// get the upload directory for sprites
		$wp_upload_dir = wp_upload_dir();
		$aqua_svg_sprite_dir = $wp_upload_dir['basedir'] . '/aqua-svg-sprite';
		// create the directory if it doesn't already exist
		if ( ! file_exists( $aqua_svg_sprite_dir ) ) {
			mkdir( $aqua_svg_sprite_dir, 0755, true );
		}
		// establish sprite file name
		$file = $aqua_svg_sprite_dir . '/aqua-svg-' . $group . '-sprite.svg';
		// write code to the file
		file_put_contents( $file, $svg );

	}

	/**
	* Delete sprite file (e.g. if it no longer contains any svg symbols)
	*/
	public static function delete_svg_sprite( $group ) {

		// get the upload directory for sprites
		$wp_upload_dir = wp_upload_dir();
		$aqua_svg_sprite_dir = $wp_upload_dir['basedir'] . '/aqua-svg-sprite';
		// establish sprite file name
		$file = $aqua_svg_sprite_dir . '/aqua-svg-' . $group . '-sprite.svg';
		// if this file in fact exists
		if ( file_exists( $file ) ) {
			// get rid of it
			wp_delete_file( $file );
		}

	}

}
