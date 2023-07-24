<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://tradesouthwest.com
 * @since      1.0.0
 *
 * @package    Profile_Details
 * @subpackage Profile_Details/admin/partials
 */
if( ! defined( 'ABSPATH' ) ) exit;

/** A1
 * Creates the admin page for the 'Profile Detail' taxonomy under the 'Users' menu.  It works the same as any
 * other taxonomy page in the admin.  However, this is kind of hacky and is meant as a quick solution.  When
 * clicking on the menu item in the admin, WordPress' menu system thinks you're viewing something under 'Posts'
 * instead of 'Users'.  We really need WP core support for this.
 */
function profile_details_tsw_add_profile_detail_admin_page() {

	$tax = get_taxonomy( 'profile_details' );

	add_users_page(
		esc_attr( $tax->labels->menu_name ),
		esc_attr( $tax->labels->menu_name ),
		$tax->cap->manage_terms,
		'edit-tags.php?taxonomy=' . $tax->name
	);
}

/* F1 - Create custom columns for the manage profiles page. */
add_filter( 'manage_users_columns', 'profile_details_tsw_manage_profile_details_custom_column' );
/* F2 - Customize the output of the custom column on the manage profiles page. */
add_filter( 'manage_users_custom_column', 'profile_details_tsw_manage_profile_details_column',10,3);
// AD4 
add_action('init', 'profile_details_tsw_update_custom_user_role' );

/** F1
 * Unsets the 'posts' column and adds a 'users' column on the manage profiles admin page.
 *
 * @param array $columns An array of columns to be shown in the manage terms table.
 */
function profile_details_tsw_manage_profile_details_custom_column( $columns ) 
{
	$posts = $columns['posts'];
	unset(   $columns['posts'] );

	$columns['profile_details'] = __( 'Details', 'profile-details-tsw' );

	$columns['posts'] = $posts;

		return $columns;
}

/** F2
 * Displays content for custom columns on the manage profile page in the admin.
 *
 * @param string $display WP just passes an empty string here.
 * @param string $column The name of the custom column.
 * @param int $term_id The ID of the term being displayed in the table.
 */
function profile_details_tsw_manage_profile_details_column( $display, $column, $user_id ) 
{
	$htm           = '';
	$profile_terms = wp_get_object_terms( $user_id,  'profile_details' );
	switch ($column) {
		case 'profile_details':

            if ( !empty( $profile_terms ) ) {

                foreach ( $profile_terms as $term ) { 
                    $htm .= esc_html( $term->name );
                }
            
			} else {
                $htm .= __( 'Not Specified.', 'profile-details-tsw' );
            }
		default: 
	}
	return $htm;
} 
/** AD4
 * Change role name when option is updated
 *
 * @since 1.0.21
 * @param string $mediator Option from plugin set in admin
 * @see https://wpmudev.com/blog/change-wordpress-role-names/
 * @return string value key pair
 */

function profile_details_tsw_update_custom_user_role()
{
global $wp_roles;
if ( ! isset( $wp_roles ) )
$wp_roles = new WP_Roles();

	// name assigned in admin
	$mediator = (''!= ( get_option('profile_details_tsw')['profile_details_tsw_pdtsw_mediator'])) 
	? get_option('profile_details_tsw')['profile_details_tsw_pdtsw_mediator'] : '';
	$wp_roles->roles['pdtsw_mediator']['name'] = sanitize_text_field($mediator );
	$wp_roles->role_names['pdtsw_mediator'] = sanitize_text_field($mediator );
} 
