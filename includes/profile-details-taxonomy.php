<?php
/**
 * Profile Details TSW Taxonomy
 * 
 * @since 1.0.0
 * @param int $option_id The ID of the option being edited.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/* A1 - Adds the taxonomy page in the admin. */
add_action( 'admin_menu', 'profile_details_tsw_add_profile_detail_admin_page' );

/* A2 - Register code with wp user taxonomy */
add_action( 'init', 'profile_details_tsw_register_user_taxonomy' );

/** DT2
 * Add action and filter to implement new User Role
 * 
 * @since 1.0.1
 * @return add_role to database if new role is not set for users. 
 */
add_action( 'init', 'profile_details_update_mediator_role' );

/** A2
 * Registers the 'Profile Detail' taxonomy for users.  
 * This is a taxonomy for the 'user' object type rather than a
 * post being the object type. Profile Detail, Profile Information, achievment, strategy
 */
function profile_details_tsw_register_user_taxonomy() {

	register_taxonomy(
		'profile_details',
		'user',
		array(
			'public' => true,
			'labels' => array( 
                'name'          => __( 'Profile Details', 'profile-details-tsw' ),
				'singular_name' => __( 'Profile Detail', 'profile-details-tsw' ),
				'menu_name'     => __( 'Profile Categories', 'profile-details-tsw' ),
				'search_items'  => __( 'Search Profile Details', 'profile-details-tsw' ),
				'popular_items' => __( 'Popular Profile Details', 'profile-details-tsw' ),
				'all_items'     => __( 'All Profile Details', 'profile-details-tsw' ),
				'edit_item'     => __( 'Edit Profile Detail', 'profile-details-tsw' ),
				'update_item'   => __( 'Update Profile Detail', 'profile-details-tsw' ),
				'add_new_item'  => __( 'Add New Profile Detail Category', 'profile-details-tsw' ),
				'new_item_name' => __( 'New Profile Detail Name', 'profile-details-tsw' ),
			),
			'rewrite'   => array(
				'with_front' => false,
				'slug'       => 'profile-details', //author is default WP user slug.
			),
			'capabilities' => array(
				'manage_terms' => 'edit_users', // Using 'edit_users' cap to keep this simple.
				'edit_terms'   => 'edit_users',
				'delete_terms' => 'edit_users',
				'assign_terms' => 'read',
			),
			'hierarchy' => true,
			'update_count_callback' => function() {
				return; //important
			},
		)
	);
} 

/** DT2 
 * Keep custom role from executing every time the page loads
 *
 * @since 1.0.02
 * @uses add_role()
 * $role (string) (required): Unique name of the role
 * $display_name (string) (required): The name to be displayed
 * $capabilities (array) (optional): Capabilities that one can access
 */
function profile_details_update_mediator_role()
{
    $options = get_option('profile_details_tsw');
	$mediator = ( empty( $options['pdtsw_custom_roles_version'] ) ) 
				? '0' :  $options['pdtsw_custom_roles_version'];
	if ( $mediator < 1 ) {

        $pdtsw_mediator = profile_details_tsw_pdtsw_mediator();
        /* Add custom role */
        add_role('pdtsw_mediator', sanitize_text_field($pdtsw_mediator),
        array(
            'read'              => true, // Allows a user to read
            'create_posts'      => true, // Allows user to create new posts
            'edit_posts'        => true, // Allows user to edit their own posts
            'edit_others_posts' => true, // Allows user to edit others posts too
            'publish_posts'     => true, // Allows the user to publish posts
            'delete_posts'      => true,
            'upload_files'      => true,
            'manage_categories' => true
            )
        );
        // Whether to load the option when WordPress starts up.
        update_option( $mediator, absint('1'), true );
	} 
}
