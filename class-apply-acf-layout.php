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
		if( function_exists('acf_add_local_field_group') ) {
			// make sure the flexible content layout to use has been activated
			$activated = get_option( 'apply_acf_layout_settings' );
			acf_add_local_field_group(array (
				'key' => 'group_apply_acf_layout',
				'title' => 'Apply Layout',
				'fields' => array (
					array (
						'key' => 'field_apply_acf_layout_field',
						'label' => 'Choose New Layout',
						'name' => 'apply_layout',
						'type' => 'post_object',
						'instructions' => '<p>Apply a new layout to this page. <strong><em>warning:</em></strong> this will replace all content on this page with placeholder content from the new layout.</p>',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						// add additional post types if you would like more than just the built-in layouts
						'post_type' => array (
							0 => 'apply-acf-layouts',
						),
						'taxonomy' => array (
						),
						'allow_null' => 0,
						'multiple' => 0,
						'return_format' => 'id',
						'ui' => 1,
					),
				),
				// shows up anywhere: you can customize this to include just specific post types if you want
				'location' => array (
					array (
						array (
							'param' => 'post_type',
							'operator' => '==',
							'value' => 'page',
						),
					),
					array (
						array (
							'param' => 'post_type',
							'operator' => '!=',
							'value' => 'page',
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
	            'labels'       => array(
	                'name'                       => 'Layouts', 'Taxonomy General Name', 'text_domain',
	                'singular_name'              => 'Layout', 'Taxonomy Singular Name', 'text_domain',
	                'menu_name'                  => 'Layouts', 'text_domain',
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
		register_setting( 'pluginPage', 'apply_acf_layout_settings' );
		add_settings_section(
			'apply_acf_layout_pluginPage_section',
			__( 'Where To Show', 'apply-acf-layout' ),
			array( 'Apply_ACF_Layout', 'apply_acf_layout_settings_section_callback' ),
			'pluginPage'
		);
		add_settings_field(
			'apply_acf_layout_flexible_field',
			__( 'Post types:', 'apply-acf-layout' ),
			array( 'Apply_ACF_Layout', 'apply_acf_layout_post_types_render' ),
			'pluginPage',
			'apply_acf_layout_pluginPage_section'
		);
	}

	// define section description
	public static function apply_acf_layout_settings_section_callback(  ) {
		echo __( 'Select which post types should show the Apply ACF Layout options.', 'apply-acf-layout' );
	}

	// render the check boxes
	public static function apply_acf_layout_post_types_render(  ) {
		$options = get_option( 'apply_acf_layout_settings' );
		// get all the flexible content options
		$post_types = get_post_types();
		$html = '<select name="apply_acf_layout_settings[apply_acf_layout_flexible_field]">';

		foreach ( $post_types as $key => $name ) {
			$type_obj = get_post_type_object( $name );
			$html .= '<option value="' . $key . '" ' . selected( $options['apply_acf_layout_flexible_field'], $key, false ) . '>' . $type_obj->labels->name . '</option>"';
		}
		$html .= '</select>';
		echo $html;
	}

	// define options page html
	public static function apply_acf_layout_options_page(  ) {
		?>
		<form action='options.php' method='post'>
			<h2>Layout Options</h2>
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

		// bail early if no layout replacement was requested
		if ( empty( $import_layout_id ) ) {
			return;
		}

		// store all fields from this post
		$post_fields = get_field_objects( $post_id );

		// prep to store all fields from the layout
		$layout_fields = array();

		// get all top-level fields from the layout
		$fields_from_layout = get_field_objects( $import_layout_id );

		// apply each layout field to this post
		foreach ( $fields_from_layout as $field) {
			$layout_fields[$field['key']] = $field['value'];
		}

		// add the new acf field values to be saved with this post
		$_POST['acf'] = $layout_fields;

		// clear out the pages to import setting
		$_POST['acf']['field_apply_acf_layout_field'] = array();

	}

}
