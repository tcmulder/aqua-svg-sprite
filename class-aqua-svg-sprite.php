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
		add_action( 'save_post', array( 'Aqua_SVG_Sprite', 'create_svg_sprite' ) );
		add_action( 'save_post', array( 'Aqua_SVG_Sprite', 'set_default_object_terms' ), 0, 2 );
		add_action( 'save_post_aqua_svg_sprite', array( 'Aqua_SVG_Sprite', 'save_group_meta_box' ) );
		add_action( 'save_post_aqua_svg_sprite', array( 'Aqua_SVG_Sprite', 'save_group_meta_box' ) );
		add_action( 'admin_head', array( 'Aqua_SVG_Sprite', 'register_shortcode_button' ) );
		add_action( 'before_wp_tiny_mce', array( 'Aqua_SVG_Sprite', 'localize_shortcode_button_scripts' ) );
		add_action( 'add_meta_boxes', array( 'Aqua_SVG_Sprite', 'aqua_svg_add_meta_boxes' ) );
		add_action( 'save_post', array( 'Aqua_SVG_Sprite', 'save_aqua_svg_sprite_meta_box' ) );
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
	 * Create SVG insert button
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
	public static function meta_box_backend( $post ) {
		wp_nonce_field( 'aqua_svg_sprite_submit', 'aqua_svg_sprite_nonce' );
		$stored_meta = get_post_meta( $post->ID );
		$stored_id = ( isset ( $stored_meta['aqua-svg'] ) ) ? $stored_meta['aqua-svg'][0] : '';
		$image_arr = wp_get_attachment_image_src( $stored_id );
		$image = '<span id="aqua-svg-preview">';
		if ( $image_arr ) {
			$image = '<img src="' . $image_arr[0] . '" id="aqua-svg-preview" style="max-width:200px;height:auto;" alt="SVG preview image" />';
		}
		?>
		<p>
			<?php echo $image; ?>
		</p>
		<p>
			<input type="hidden" name="aqua-svg" id="aqua-svg" class="meta_image" value="<?php echo $stored_id; ?>" />
			<input type="button" id="aqua-svg-button" class="button" value="Choose or Upload an Image" />
		</p>
		<hr>
		<h3>Usage Instructions</h3>
		<?php echo self::field_message(); ?>

	<script>
	jQuery('#aqua-svg-button').click(function() {

		var send_attachment_bkp = wp.media.editor.send.attachment;

		wp.media.editor.send.attachment = function( props, attachment ) {
			jQuery( '#aqua-svg')
				.val( attachment.id );
			jQuery( '#aqua-svg-preview' )
				.each(function(){
					var $this = jQuery(this);
					if ( $this.is( 'img' ) ) {
						$this.attr( 'src', attachment.url );
						console.log('no...');
					} else {
						var imageHTML = '<img ';
							imageHTML += 'src="';
							imageHTML += attachment.url;
							imageHTML += '" id="aqua-svg-preview"';
							imageHTML += ' style="max-width:200px;height:auto;"';
							imageHTML += ' alt="SVG preview image"';
							imageHTML += ' />';
						console.log(imageHTML);
						$this.replaceWith( imageHTML );
					}
				} );
			wp.media.editor.send.attachment = send_attachment_bkp;
		}

		wp.media.editor.open();

		return false;
	});
	</script>
	<?php

	}

	// add meta boxes to aqua_svg_sprite posts
	public static function aqua_svg_add_meta_boxes() {
		add_meta_box( 'aqua-svg-image', 'Aqua SVG Sprite Image', array( 'Aqua_SVG_Sprite', 'meta_box_backend' ), 'aqua_svg_sprite', 'normal', 'low' );
	}

	// save meta box results
	public static function save_aqua_svg_sprite_meta_box( $post_id ) {
		$is_autosave = wp_is_post_autosave( $post_id );
		$is_revision = wp_is_post_revision( $post_id );
		$is_valid_nonce = ( isset( $_POST[ 'aqua_svg_sprite_nonce' ] ) && wp_verify_nonce( $_POST[ 'aqua_svg_sprite_nonce' ], 'aqua_svg_sprite_submit' ) ) ? 'true' : 'false';

		// Exits script depending on save status
		if ( $is_autosave || $is_revision || !$is_valid_nonce  ) {
			return;
		}

		// Checks for input and sanitizes/saves if needed
		if( isset( $_POST['aqua-svg'] ) ) {
			update_post_meta( $post_id, 'aqua-svg', $_POST['aqua-svg'] );
		}
	}

	/**
	 * Create message to users for the field.
	 */
	public static function field_message() {
		// describe requirements
		$message = '
			<p>
				<em>Pro Tip:</em> It\'s a great idea to run the file through an SVG compressor like
				<a href="https://jakearchibald.github.io/svgomg/" target="_blank">SVG OMG</a>
				before uploading it here so you can apply manual compression.
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

<p><strong>Basic shortcode usage:</strong></p>
<p><code>[aqua-svg slug="' . $slug . '"' . ( 'general' !== $sprite_slug ? ' sprite="' . $sprite_slug . '"' : '' ) . ']</code></p>

<p><strong>More complex shortcode example:</strong></p>
<p><code>[aqua-svg slug="' . $slug . '" sprite="' . $sprite_slug . '" attr="viewbox=0 0 1000 1000,fill=aquamarine"]</code></p>

<p><strong>PHP usage:</strong></p>
<p><code>&lt;?php the_aqua_svg( \'' . $slug . '\'' . ( 'general' !== $sprite_slug ? ', \'' . $sprite_slug . '\'' : '' ) . ' ); ?&gt;</code></p>

<p><strong>More complex PHP example:</strong></p>
<pre><code>&lt;?php
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
					// get the id via meta or through the post data if is current post
					$svg_id = ( get_the_id() !== $post_id ? get_post_thumbnail_id( get_the_id() ) : $_POST['aqua-svg'] );
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
			// update the featured image to the uploaded image
			update_post_meta( $post_id, '_thumbnail_id', $_POST['aqua-svg'] );
		}

	}


}
