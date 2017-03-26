<?php

Class Apply_ACF_Layout {

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
		add_action( 'init', array( 'Apply_ACF_Layout', 'create_layout_select' ) );
		add_action( 'init', array( 'Apply_ACF_Layout', 'create_layout_post_type' ) );
		add_action( 'transition_post_status', array( 'Apply_ACF_Layout', 'set_layout_status' ), 10, 3);
		add_action( 'template_redirect', array( 'Apply_ACF_Layout', 'redirect_non_layout_users' ) );
		add_action( 'admin_menu', array( 'Apply_ACF_Layout', 'apply_acf_layout_add_admin_menu' ) );
		add_action( 'admin_init', array( 'Apply_ACF_Layout', 'apply_acf_layout_settings_init' ) );
		add_action( 'acf/save_post', array( 'Apply_ACF_Layout', 'import_layouts_from_a_different_page' ), 1 );
	}

	/**
	 * Create field for choosing a layout to apply
	 */
	public static function create_layout_select() {
		if( function_exists( 'acf_add_local_field_group' ) ) {

			// get plugin options
			$post_type_options = get_option( 'apply_acf_layout_settings' );

			// show only on post types where it should appear
			$post_types = array();
			if ( ! empty ( $post_type_options['apply_acf_show_post_types'] ) ) {
				foreach( $post_type_options['apply_acf_show_post_types'] as $type ) {
					$post_types[] = array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => $type,
						),
					);
				}
			}

			// use only post types defined as layouts
			$layout_types = array (
				0 => 'apply-acf-layouts',
			);
			if ( ! empty ( $post_type_options['apply_acf_layout_types'] ) ) {
				foreach( $post_type_options['apply_acf_layout_types'] as $type ) {
					$layout_types[] = $type;
				}
			}

			// create the field for layout post type only
			acf_add_local_field_group(array (
				'key' => 'group_apply_acf_layout_on_layout',
				'title' => 'Apply Layout',
				'fields' => array (
					array (
						'key' => 'field_apply_acf_layout_on_layout_field',
						'label' => __( 'Choose New Layout', 'apply-acf-layout' ),
						'name' => 'apply_layout',
						'type' => 'post_object',
						'instructions' => __( '<p>Create a new layout from an existing post. <strong><em>warning:</em></strong> this will replace all content on this layout with the content from the page you choose.</p>', 'apply-acf-layout' ),
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'post_type' => array (
						),
						'taxonomy' => array (
						),
						'allow_null' => 0,
						'multiple' => 0,
						'return_format' => 'id',
						'ui' => 1,
					),
				),
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'apply-acf-layouts',
						),
					),
				),
				// must be way down so hide_on_screen applies for other fields
				'menu_order' => 99,
				'position' => 'side',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'active' => 1,
				'description' => '',
			));

			// create the field for all selected post types (other than layout)
			acf_add_local_field_group(array (
				'key' => 'group_apply_acf_layout',
				'title' => 'Apply Layout',
				'fields' => array (
					array (
						'key' => 'field_apply_acf_layout_field',
						'label' => __( 'Choose New Layout', 'apply-acf-layout' ),
						'name' => 'apply_layout',
						'type' => 'post_object',
						'instructions' => __( '<p>Apply a new layout to this page. <strong><em>warning:</em></strong> this will replace all content on this page with placeholder content from the new layout.</p>', 'apply-acf-layout' ),
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						// add additional post types if you would like more than just the built-in layouts
						'post_type' => $layout_types,
						'taxonomy' => array (
						),
						'allow_null' => 0,
						'multiple' => 0,
						'return_format' => 'id',
						'ui' => 1,
					),
				),
				'location' => $post_types,
				// must be way down so hide_on_screen applies for other fields
				'menu_order' => 99,
				'position' => 'side',
				'style' => 'default',
				'label_placement' => 'top',
				'instruction_placement' => 'label',
				'active' => 1,
				'description' => '',
			));
		}
	}

	/**
	 * Create custom post type for layouts
	 * (adapted from https://support.advancedcustomfields.com/forums/topic/copy-flexible-content-layout-from-one-post-to-another/)
	 */

	// custom post type
	public static function create_layout_post_type() {
	    register_post_type('apply-acf-layouts',
	        array(
	            'labels' => array(
	                'name'                       => _x( 'Layouts', 'Taxonomy General Name', 'apply-acf-layout' ),
	                'singular_name'              => _x( 'Layout', 'Taxonomy Singular Name', 'apply-acf-layout' ),
	                'menu_name'                  => __( 'Layouts', 'apply-acf-layout' ),
	                'all_items'                  => __( 'All Items', 'apply-acf-layout' ),
	                'parent_item'                => __( 'Parent Item', 'apply-acf-layout' ),
	                'parent_item_colon'          => __( 'Parent Item:', 'apply-acf-layout' ),
	                'new_item_name'              => __( 'New Item Name', 'apply-acf-layout' ),
	                'add_new_item'               => __( 'Add New Item', 'apply-acf-layout' ),
	                'edit_item'                  => __( 'Edit Item', 'apply-acf-layout' ),
	                'update_item'                => __( 'Update Item', 'apply-acf-layout' ),
	                'separate_items_with_commas' => __( 'Separate items with commas', 'apply-acf-layout' ),
	                'search_items'               => __( 'Search Items', 'apply-acf-layout' ),
	                'add_or_remove_items'        => __( 'Add or remove items', 'apply-acf-layout' ),
	                'choose_from_most_used'      => __( 'Choose from the most used items', 'apply-acf-layout' ),
	                'not_found'                  => __( 'Not Found', 'apply-acf-layout' ),
	            ),
	            'menu_icon' => 'dashicons-layout',
	            'has_archive' => true,
	            'public' => true,
	            'hierarchical' => true,
	            'menu_position' => 100, // bottom-ish
	            'supports' => array(
	                'editor',
	                'custom-fields',
	                'title',
	                'page-attributes'
	            ),
	        )
	    );
	}

	// set public posts to private
	public static function set_layout_status($new_status, $old_status, $post) {
	    if ('apply-acf-layouts' == $post->post_type && 'publish' == $new_status && $old_status != $new_status && !post_password_required($post)) {
	        $post->post_status = 'private';
	        wp_update_post($post);
	    }
	}

	// redirect non-logged-in users to login page
	public static function redirect_non_layout_users() {
	    if(is_post_type_archive('apply-acf-layouts') && !is_user_logged_in()) {
	        auth_redirect();
	    }
	}

	/**
	 * Create the settings page for the plugin
	 */

	// add a menu link inside the Layouts menu
	public static function apply_acf_layout_add_admin_menu(  ) {
		add_submenu_page( 'edit.php?post_type=apply-acf-layouts', 'Apply ACF Layout', 'Options', 'manage_options', 'apply_acf_layout', array( 'Apply_ACF_Layout', 'apply_acf_layout_options_page' ) );
	}

	// establish option page setting sections/fields
	public static function apply_acf_layout_settings_init(  ) {

		// register the settings option storage location
		register_setting( 'pluginPage', 'apply_acf_layout_settings' );
		register_setting( 'pluginPage', 'apply_acf_layout_settings_2' );

		// create selection of post types where layouts will appear
		add_settings_section(
			'apply_acf_layout_pluginPage_section',
			__( 'Where To Show', 'apply-acf-layout' ),
			array( 'Apply_ACF_Layout', 'apply_acf_layout_settings_section_callback' ),
			'pluginPage'
		);
		add_settings_field(
			'apply_acf_show_post_types',
			__( 'Show on post types:', 'apply-acf-layout' ),
			array( 'Apply_ACF_Layout', 'apply_acf_layout_post_types_render' ),
			'pluginPage',
			'apply_acf_layout_pluginPage_section'
		);

		// create selection for what post types are available as layouts
		add_settings_section(
			'apply_acf_layout_pluginPage_section_2',
			__( 'Use As Templates', 'apply-acf-layout' ),
			array( 'Apply_ACF_Layout', 'apply_acf_layout_settings_2_section_callback' ),
			'pluginPage'
		);
		add_settings_field(
			'apply_acf_show_post_types_2',
			__( 'Use as layouts:', 'apply-acf-layout' ),
			array( 'Apply_ACF_Layout', 'apply_acf_layout_layout_types_render' ),
			'pluginPage',
			'apply_acf_layout_pluginPage_section_2'
		);
	}

	// define section descriptions
	public static function apply_acf_layout_settings_section_callback(  ) {
		echo '<p>' . __( 'Select which post types should have Apply ACF Layout available.', 'apply-acf-layout' ) . '</p>';
	}
	public static function apply_acf_layout_settings_2_section_callback(  ) {
		echo '<p>' . __( 'Select which post types are available as layouts (in addition to the Layouts post type).', 'apply-acf-layout' ) . '</p>';
	}

	// render the form fields
	public static function apply_acf_layout_post_types_render(  ) {
		$options = get_option( 'apply_acf_layout_settings' );
		// get all post types that are publicly available
		$args = array(
		   'public'   => true,
		);
		$post_types = get_post_types( $args );
		// create checkboxs listing the post types for which layouts should appear
		$html = '<fieldset>';
		foreach ( $post_types as $key => $name ) {
			// the layouts post type is always selected so don't show it
			if ( 'apply-acf-layouts' == $key ) {
				continue;
			}
			$type_obj = get_post_type_object( $name );
			$html .= '<p>';
				$html .= '<label for="' . $key .'">';
					$html .= '<input';
						$html .= ' type="checkbox"';
						$html .= ' id="' . $key . '"';
						$html .= ' name="apply_acf_layout_settings[apply_acf_show_post_types][' . $key . ']"';
						$html .= ' value="' . $key . '"';
						$html .= ' ' . checked( $options['apply_acf_show_post_types'][$key], $key, false );
					$html .= '>';
				$html .= $type_obj->labels->name . '</label>';
			$html .= '</p>';
		}
		$html .= '<fieldset>';
		echo $html;
	}
	public static function apply_acf_layout_layout_types_render(  ) {
		$options = get_option( 'apply_acf_layout_settings' );
		// get all post types that are publicly available
		$args = array(
		   'public'   => true,
		);
		$post_types = get_post_types( $args );
		// create checkboxs listing the post types for which layouts should appear
		$html = '<fieldset>';
		foreach ( $post_types as $key => $name ) {
			// the layouts post type is always selected so don't show it
			if ( 'apply-acf-layouts' == $key ) {
				continue;
			}
			// determine if should be checked
			$checked = '';
			if ( ! empty( $options['apply_acf_layout_types'][$key] ) ) {
				$checked = checked( $options['apply_acf_layout_types'][$key], $key, false );
			}
			// set up the html
			$type_obj = get_post_type_object( $name );
			$html .= '<p>';
				$html .= '<label for="' . $key .'">';
					$html .= '<input';
						$html .= ' type="checkbox"';
						$html .= ' id="' . $key . '"';
						$html .= ' name="apply_acf_layout_settings[apply_acf_layout_types][' . $key . ']"';
						$html .= ' value="' . $key . '"';
						$html .= ' ' . $checked;
					$html .= '>';
				$html .= $type_obj->labels->name . '</label>';
			$html .= '</p>';
		}
		$html .= '<fieldset>';
		echo $html;
	}

	// define options page form html
	public static function apply_acf_layout_options_page(  ) {
		?>
		<form action='options.php' method='post'>
			<h1><?php _e( 'Layout Options', 'apply-acf-layout' ) ?></h1>
			<p><?php _e( '<strong><em>Important Note:</em></strong> make sure the posts you are applying layouts from and to both share the same ACF fields, since only matching fields get updated.', 'apply-acf-layout' ) ?></p>
			<?php
				settings_fields( 'pluginPage' );
				do_settings_sections( 'pluginPage' );
				submit_button();
			?>
		</form>
		<?php
	}

	/**
	* Apply a layout from one page's flexible layout to another.
	*/
	public static function import_layouts_from_a_different_page( $post_id ) {

		// see if there's an id for a layout to apply to this post
		$import_layout_id = $_POST['acf']['field_apply_acf_layout_field'];
		$import_layout_id_on_layout = $_POST['acf']['field_apply_acf_layout_on_layout_field'];

		// bail early if no layout replacement was requested
		if ( empty( $import_layout_id ) && empty( $import_layout_id_on_layout ) ) {
			return;
		// choose the appropriate id: one for common pages or another for for the layout post type itself
		} else {
			$import_layout_id = ( ! empty( $import_layout_id ) ? $import_layout_id : $import_layout_id_on_layout );
		}

		// prep to store all fields from the layout
		$layout_fields = array();

		// get all top-level fields from the layout
		$fields_from_layout = get_field_objects( $import_layout_id );

		// apply each layout field to this post
		if ( ! empty( $fields_from_layout )) {
			foreach ( $fields_from_layout as $field) {
				$layout_fields[$field['key']] = $field['value'];
			}
		}

		// add the new acf field values to be saved with this post
		$_POST['acf'] = $layout_fields;

		// clear out the pages to import setting
		$_POST['acf']['field_apply_acf_layout_field'] = array();

	}

}
