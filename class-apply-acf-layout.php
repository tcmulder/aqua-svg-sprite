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
						'label' => 'Layout to Apply',
						'name' => 'apply_layout',
						'type' => 'post_object',
						'instructions' => ( $activated ? '' : '<span class="acf-required">*</span> <strong>This won\'t work yet:</strong> you must choose a flexible content type in <a href="' . get_admin_url( null, '/edit.php?post_type=apply-acf-layouts&page=apply_acf_layout/' ) . '">Layouts > Options</a> first' ),
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
						'multiple' => 1,
						'return_format' => 'id',
						'ui' => 1,
					),
					array (
						'key' => 'field_apply_acf_layout_existing_content_field',
						'label' => 'Existing Content',
						'name' => 'existing_content',
						'type' => 'radio',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array (
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'choices' => array (
							'append' => 'Append',
							'replace' => 'Replace <em>(<strong>warning:</strong> existing content on this page will be replaced)</em>',
						),
						'allow_null' => 0,
						'other_choice' => 0,
						'save_other_choice' => 0,
						'default_value' => 'append',
						'layout' => 'vertical',
						'return_format' => 'value',
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
			__( 'Flexible Content Field Choice', 'apply-acf-layout' ),
			array( 'Apply_ACF_Layout', 'apply_acf_layout_settings_section_callback' ),
			'pluginPage'
		);
		add_settings_field(
			'apply_acf_layout_flexible_field',
			__( 'Use this field:', 'apply-acf-layout' ),
			array( 'Apply_ACF_Layout', 'apply_acf_layout_flexible_field_render' ),
			'pluginPage',
			'apply_acf_layout_pluginPage_section'
		);
	}

	// render the select box
	public static function apply_acf_layout_flexible_field_render(  ) {
		$options = get_option( 'apply_acf_layout_settings' );
		// get all the flexible content options
		$flexible_fields = array();
		$args = array(
			'post_type' => 'acf-field-group',
			'posts_per_page' => -1,
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) { $the_query->the_post();
				$group_id = get_the_id();
				$group_title = get_the_title();
				$fields = acf_get_fields_by_id( $group_id );
				foreach ( $fields as $field ) {
					if ( 'flexible_content' == $field['type'] ) {
						$flexible_fields[$field['key']] = $field['label'] . ' (in ' . $group_title . ' group)';
					}
				}
			}
			wp_reset_postdata();
		}
		$html = '<select name="apply_acf_layout_settings[apply_acf_layout_flexible_field]">';
		$i = 0;
		foreach ( $flexible_fields as $key => $name ) {
			$html .= '<option value="' . $key . '" ' . selected( $options['apply_acf_layout_flexible_field'], $key, false ) . '>' . $name . '</option>"';
		}
		$html .= '</select>';
		echo $html;
	}

	// define section description
	public static function apply_acf_layout_settings_section_callback(  ) {
		echo __( 'Select the flexible content field you are using to define layouts. You can only select one, and it must be at the top level of the field group.', 'apply-acf-layout' );
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
	 * (adapted from https://support.advancedcustomfields.com/forums/topic/copy-flexible-content-layout-from-one-post-to-another/)
	 */
	public static function import_layouts_from_a_different_page( $post_id ) {

		// bail early if no ACF data
		if ( empty( $_POST['acf'] ) ) {
			return;
		}

		// identify the flexible content field to replace
		$replace_option = get_option( 'apply_acf_layout_settings' );
		$replace_key = $replace_option['apply_acf_layout_flexible_field'];

		// bail if the content to replace isn't defined
		if ( ! $replace_key ) {
			return;
		}

		// determine if this should append or replace existing content
		$apply_type = $_POST['acf']['field_apply_acf_layout_existing_content_field'];

		// store current layouts (empty by default so layouts will be replaced instead of appended)
		$current_page_flex_layouts = array();

		// get flex layouts from this page
		if ( 'replace' !== $apply_type ) {
			if ( is_array( $_POST['acf'][$replace_key] ) && ! empty( $_POST['acf'][$replace_key] ) ) {
				$current_page_flex_layouts = $_POST['acf'][$replace_key];
			}
		}

		// determine if there are any pages to import (field_apply_acf_layout_field is a Select field named "Apply Layout")
		$pages_to_import = $_POST['acf']['field_apply_acf_layout_field'];

		// if there aren't any layouts to import, skip the rest
		if ( empty( $pages_to_import ) ) {
			return;
		}

		// loop through the (possibly) multiple pages that we'll import
		foreach ( $pages_to_import as $page_id ) {

			// get the layouts value from the selected page
			$layouts_from_page = get_field_object( 'modules', $page_id, false, true );

			// add the value to this page
			if ( ! empty( $layouts_from_page['value'] ) ) {
				$current_page_flex_layouts = array_merge( $current_page_flex_layouts, $layouts_from_page['value'] );
			}
		}

		// re-set the Layout field value with any imported pages, then continue saving
		$_POST['acf'][$replace_key] = $current_page_flex_layouts;

		// clear out the pages to import setting
		$_POST['acf']['field_apply_acf_layout_field'] = array();

	}

}
