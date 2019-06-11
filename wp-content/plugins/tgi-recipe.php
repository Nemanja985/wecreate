<?php
/**
 * Plugin Name: TGI Recipe Customization
 * Description: Customization for the new custom post type - Recipe
 * Author:      Jason Yip
 * Version:     1.0
 */

/**
 * Add taxonomies to custom post type - recipe
 */
function add_wp_taxonomy_to_recipe() {
	// Replace post_type with actual CPT slug.
	register_taxonomy_for_object_type( 'category', 'recipe' );
	register_taxonomy_for_object_type( 'post_tag', 'recipe' );
}
add_action( 'init', 'add_wp_taxonomy_to_recipe' );

/**
 * Add recipe posts to the archives
 *
 * @param object $query get post query.
 */
function add_recipe_to_archives( $query ) {
	// We do not want unintended consequences.
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( is_category() || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
		$query->set(
			'post_type',
			[ 'post', 'recipe' ]
		);
	}
}
add_filter( 'pre_get_posts', 'add_recipe_to_archives' );


/**
 * Update the total duration field of the recipe
 *
 * @param int $post_id Recipe post ID.
 */
function update_total_duration( $post_id ) {
	$post_type = get_post_type( $post_id );
	if ( 'recipe' === $post_type && get_field( 'total_duration', $post_id ) !== null && get_field( 'step_groups', $post_id ) ) {
		$total_duration = 0;
		while ( the_repeater_field( 'step_groups', $post_id ) ) {
			$duration = get_sub_field( 'duration' );
			if ( $duration && is_numeric( $duration ) ) {
				$total_duration += $duration;
			}
		}
		update_field( 'total_duration', $total_duration, $post_id );
	}
}
add_action( 'save_post', 'update_total_duration', 100, 1 );

/**
 * Validation the recipe post data
 *
 * @param int $post_id Recipe post ID.
 */
function save_recipe_validation( $post_id ) {
	$error      = false;
	$taxonomies = get_object_taxonomies( 'recipe', 'names' );
	if ( in_array( 'device_type', $taxonomies, true ) ) {
		if ( ! isset( $_POST['tax_input']['device_type'] ) ||
			empty( $_POST['tax_input']['device_type'] )
		) {
			add_settings_error(
				'missing-device-type',
				'missing-device-type',
				'You did not set the device type for the recipe.',
				'error'
			);
			$error = true;
		}
	}

	if ( in_array( 'complexity', $taxonomies, true ) ) {
		if ( ! isset( $_POST['tax_input']['complexity'] ) ||
			empty( $_POST['tax_input']['complexity'] )
		) {
			add_settings_error(
				'missing-complexity',
				'missing-complexity',
				'You did not set the complexity for the recipe.',
				'error'
			);
			$error = true;
		}
	}

	if ( $error ) {
		set_transient( 'settings_errors', get_settings_errors(), 30 );
		wp_safe_redirect( wp_get_referer() );
		exit;
	}
}

/**
 * Validate the recipe saving
 *
 * @param int $post_id Recipe post ID.
 */
function before_save_recipe( $post_id ) {
	$post_type = get_post_type( $post_id );
	// If this isn't a 'recipe' post, don't proceed it.
	if ( 'recipe' !== $post_type || ! isset( $_POST['action'] ) || 'editpost' !== $_POST['action'] ) {
		return;
	}

	save_recipe_validation( $post_id );
}
add_filter( 'pre_post_update', 'before_save_recipe' );

/**
 * Display the recipe validation error in admin panel
 */
function recipe_admin_notices() {
	// If there are no errors, then we'll exit the function.
	$errors = get_transient( 'settings_errors' );
	if ( ! $errors ) {
		return;
	}
	// Otherwise, build the list of errors that exist in the settings errors.
	$message = '<div id="acme-message" class="error below-h2"><p><ul>';
	foreach ( $errors as $error ) {
		$message .= '<li>' . $error['message'] . '</li>';
	}
	$message .= '</ul></p></div><!-- #error -->';
	// Write them out to the screen.
	echo $message; // WPCS: XSS ok.
	// Clear and the transient and unhook any other notices so we don't see duplicate messages.
	delete_transient( 'settings_errors' );
	remove_action( 'admin_notices', '_location_admin_notices' );
}
add_action( 'admin_notices', 'recipe_admin_notices' );

/**
 * Overwrite the existing recipe taxonomies settings
 */
function overwrite_taxonomies_settings() {
	$device_type = get_taxonomy( 'device_type' );
	if ( $device_type ) {
		$device_type_args                = (array) $device_type;
		$device_type_args['meta_box_cb'] = 'render_dropdown_taxonomy';
		register_taxonomy( 'device_type', $device_type->object_type, $device_type_args );
	}

	$complexity = get_taxonomy( 'complexity' );
	if ( $complexity ) {
		$complexity_args                = (array) $complexity;
		$complexity_args['meta_box_cb'] = 'render_dropdown_taxonomy';
		register_taxonomy( 'complexity', $complexity->object_type, $complexity_args );
	}
}
add_action( 'init', 'overwrite_taxonomies_settings' );

/**
 * Render the taxonomy meta box with dropdown field
 *
 * @param object $post Post.
 * @param array  $box meta box arguments.
 */
function render_dropdown_taxonomy( $post, $box ) {
	$args     = $box['args'];
	$tax_name = esc_attr( $args['taxonomy'] );
	$taxonomy = get_taxonomy( $args['taxonomy'] );
	?>
	<div id="<?php echo $tax_name;  // WPCS: XSS ok. ?>" class="tagsdiv">
		<?php
		$name     = 'tax_input[' . $tax_name . ']';
		$term_obj = wp_get_object_terms( $post->ID, $tax_name );
		wp_dropdown_categories(
			array(
				'taxonomy'          => $tax_name,
				'hide_empty'        => 0,
				'name'              => "{$name}[]",
				'selected'          => $term_obj[0]->name,
				'orderby'           => 'name',
				'hierarchical'      => 0,
				'show_option_none'  => "Select {$taxonomy->labels->singular_name}",
				'option_none_value' => '',
				'value_field'       => 'name',
				'required'          => true,
			)
		);
		?>
	</div>
	<?php
}

