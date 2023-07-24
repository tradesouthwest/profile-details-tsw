<?php 
/**
 * Provide a private-facing view for the plugin, 
 * mostly public private aka user admin panel.
 *
 * This file is used to markup the private-facing aspects of the user profile edits.
 *
 * @link       https://tradesouthwest.com
 * @since      1.0.0
 *
 * @package    Profile_Details
 * @subpackage Profile_Details/public/partials
 */

// PH2
add_action( 'show_user_profile', 'profile_details_tsw_extra_profile_info' );
add_action( 'edit_user_profile', 'profile_details_tsw_extra_profile_info' );

// PH3
add_action( 'personal_options_update', 'profile_details_tsw_save_profile_info' );
add_action( 'edit_user_profile_update', 'profile_details_tsw_save_profile_info' );

// PH4
/* Add section to the edit user page in the admin to select profile_detail. */
add_action( 'show_user_profile', 'profile_details_tsw_edit_user_profile_details_section' );
add_action( 'edit_user_profile', 'profile_details_tsw_edit_user_profile_details_section' );

// PH5
/* Update the profile_details terms when the edit user page is updated. */
add_action( 'personal_options_update', 'profile_details_tsw_save_user_profile_details_terms' );
add_action( 'edit_user_profile_update', 'profile_details_tsw_save_user_profile_details_terms' );

// PH7
add_action('wp_head', 'profile_details_tsw_inline_page_styles'); 

// PH1
add_filter( 'user_contactmethods', 'profile_details_tsw_social_author_profile', 10, 1); 
/** PH1
 * Add social links to author/user profile
 * @uses user_contactmethods
 */
function profile_details_tsw_social_author_profile( $contactmethods ) 
{
    $shown = profile_details_tsw_social_author_remove();
    if ( $shown ) {

	$contactmethods['pdtsw_google_business']        = __( 'Business URL', 'profile-details-tsw' );

    } else {

	$contactmethods['pdtsw_google_business']  = __( 'Business URL', 'profile-details-tsw' );
	$contactmethods['pdtsw_twitter_profile']  = __( 'Social Media URL', 'profile-details-tsw' );
	$contactmethods['pdtsw_facebook_profile'] = __( 'Social Media URL', 'profile-details-tsw' );
	$contactmethods['pdtsw_linkedin_profile'] = __( 'Social Media URL', 'profile-details-tsw' );
    $contactmethods['pdtsw_rss_url']          = __( 'RSS Feed URL', 'profile-details-tsw' );
    }

	    return $contactmethods;
} 

// PH2
function profile_details_tsw_extra_profile_info( $user ) {
	$pdtsw = profile_details_tsw_extend_info();
	if ( $pdtsw === false ) {
	remove_action( 'show_user_profile', 'profile_details_tsw_extra_profile_info' );
    remove_action( 'edit_user_profile', 'profile_details_tsw_extra_profile_info' );
	return false;
	} else {
	// name assigned in admin
	$mediator = (''!= ( get_option('profile_details_tsw')['profile_details_tsw_pdtsw_mediator'])) 
	? get_option('profile_details_tsw')['profile_details_tsw_pdtsw_mediator'] : '';
	?>
    <div id="profile-details-addedinfo">
	<h3><?php esc_html_e( 'Additional Profile Info', 'profile-details-tsw' ); ?></h3>

	<table class="form-table">
        <tr>
			<th><label for="profile_details_tsw_company"><?php esc_html_e( 'Company', 'profile-details-tsw' ); ?></label></th>
			<td><input type="text" name="profile_details_tsw_company" 
			value="<?php echo esc_attr( get_the_author_meta( 'profile_details_tsw_company', $user->ID ) ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="profile_details_tsw_position"><?php esc_html_e( 'Group', 'profile-details-tsw' ); ?></label></th>
			<td><input type="text" name="profile_details_tsw_position" 
			value="<?php echo esc_attr( get_the_author_meta( 'profile_details_tsw_position', $user->ID ) ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="profile_details_tsw_location"><?php esc_html_e( 'Location', 'profile-details-tsw' ); ?></label></th>
			<td><input type="text" name="profile_details_tsw_location" 
			value="<?php echo esc_attr( get_the_author_meta( 'profile_details_tsw_location', $user->ID ) ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="profile_details_tsw_show_email"><?php esc_html_e( 'Email', 'profile-details-tsw' ); ?></label></th>
			<td><input type="checkbox" name="profile_details_tsw_show_email" value="true" <?php checked( "true", esc_attr( get_the_author_meta( 'profile_details_tsw_show_email', $user->ID ) ) ) ?> />
				<?php esc_html_e('Show email address on profile page','profile-details-tsw');?>
			</td>
		</tr>
		<tr>
			<th><label for="profile_details_tsw_pdtsw_mediator">
			<?php esc_html_e( 'Additional Capabilities', 'profile-details-tsw' ); ?></label></th>
			<td><?php $author_caps = profile_details_tsw_get_user_capabilities( $user->ID );
			if ( !empty ( $author_caps ) && in_array( 'pdtsw_mediator', $author_caps ) ) {   
				echo esc_html( $mediator ); }
				else {
				echo esc_html__('None assigned at this time.','profile-details-tsw');
				} ?>
			</td>
		</tr>
		<?php // Only show this box if management is logged in.
		if ( current_user_can( 'manage_options' ) ): ?>
		<tr>
			<th><label for="profile_details_tsw_adminnotes"><?php _e( 'Internal Notes', 'profile-details-tsw' ); ?></label></th>
			<td class="pdtsw-adminnotes">
<textarea id="profile_details_tsw_adminnotes" name="profile_details_tsw_adminnotes" cols=30 rows=5><?php echo trim( get_the_author_meta( 'profile_details_tsw_adminnotes', $user->ID )); ?></textarea>
			</td>
		</tr>
		<?php endif; ?>

	</table>
    </div>
	<?php } 
}

// PH3
function profile_details_tsw_save_profile_info( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;

	update_user_meta( $user_id, 'profile_details_tsw_bio', $_POST['profile_details_tsw_bio'] );
	update_user_meta( $user_id, 'profile_details_tsw_company', $_POST['profile_details_tsw_company'] );
    update_user_meta( $user_id, 'profile_details_tsw_position', $_POST['profile_details_tsw_position'] );
	update_user_meta( $user_id, 'profile_details_tsw_location', $_POST['profile_details_tsw_location'] );
	update_user_meta( $user_id, 'profile_details_tsw_show_email', $_POST['profile_details_tsw_show_email'] );
	update_user_meta( $user_id, 'profile_details_tsw_adminnotes', $_POST['profile_details_tsw_adminnotes'] );
}

/** PH4
 * Adds an additional settings section on the edit user/profile page in the admin.  
 * Allows users to select a profile_detail from a checkbox of terms from the profile_detail 
 *
 * @param object $user The user object currently being edited.
 */
function profile_details_tsw_edit_user_profile_details_section( $user ) {

	$tax = get_taxonomy( 'profile_details' );
 
	/* Make sure the user can assign terms of the profile_detail taxonomy before proceeding. */
	if ( !current_user_can( $tax->cap->assign_terms ) ) return;
    
	/* Get the terms of the 'profile_detail' taxonomy. */
	$terms = get_terms( 'profile_details', array( 'hide_empty' => false ) ); ?>
	<div id="profile-details-type">
	<h2><?php _e( 'Profile Details Type' ); ?></h2>

	<table class="form-table"><tbody>
		<tr>
			<th><label for="profile_details"><?php _e( 'Select profile type', 'profile-details-tsw' ); ?></label></th>
			<td class="pdtsw-radios">
		<?php
	/* If there are any profile_details terms, loop through them and display radio checkboxes. */
	if ( !empty( $terms ) ) {

		foreach ( $terms as $term ) { 
		echo '<input type ="radio" name ="profile_details" 
		    id ="profile_details-' . esc_attr( $term->slug ) . '" 
		    value ="' . esc_attr( $term->slug ) . '"' 
		. checked(true, is_object_in_term($user->ID,"profile_details",$term ),false) .'>
 		<label class="pdtsw-push" for="profile_details-' . esc_attr( $term->slug ). '">'
		. $term->name . ' </label> '; 
		}
	
	}	/* If there are no profile_details terms, display a message. */
		else {
			esc_html_e( 'Profile details unavailable.', 'profile-details-tsw' );
		}
			?></td>
		</tr>
	</tbody></table>

	</div>
<?php 
}


/** PH5
 * Saves the term selected on the edit user/profile page in the admin. This function is triggered when the page
 * is updated.  We just grab the posted data and use wp_set_object_terms() to save it.
 *
 * @param int $user_id The ID of the user to save the terms for.
 */
function profile_details_tsw_save_user_profile_details_terms( $user_id ) {

	$tax = get_taxonomy( 'profile_details' );
    /* Make sure the current user can edit the user and assign terms before proceeding. */
	if ( !current_user_can( 'edit_user', $user_id )
	   && current_user_can( $tax->cap->assign_terms ) )
		return false;

	$term = esc_attr( $_POST['profile_details'] );

	/* Sets the terms (we're just using a single term) for the user. */
	wp_set_object_terms( $user_id, array( $term ), 'profile_details', false);

	clean_object_term_cache( $user_id, 'profile_details' );
}

/** PH6
 * Dropdown sortable query for cats view --- widget maybe
 *
 * @since 1.0.31
 * @see maybe use https://github.com/ebenhaezerbm/fwc-dropdown-users
 * @return HTML
 */
function profile_details_tsw_sortterms_dropdown()
{
    $html = '<form method="post" name="pdtsw-sortterms-dropdown">';
    $tax = get_taxonomy( 'profile_details' );
	/* Get the terms of the 'profile_detail' taxonomy. */
	$user_terms = get_terms( 'profile_details', 
							 array( 
							 	'hide_empty' => false 
						    	) 
							); 
	$args       = array( 
						'orderby' => 'name', 
						'order' => 'ASC', 
						'fields' => 'all'
						);
    $html .= '<p><label for="pdtsw-sortform-dropdown">' . __('Sort by ', 'profile-details-tsw');
    if ( ! is_wp_error( $user_terms ) ) {
			$html .= '<select id="pdtsw-sortform-dropdown">';
		foreach( $user_terms as $term ) {
			$html .= '<option value="'. get_term_link($term->slug,'profile_details') .'">' 
				. esc_html( $term->name ) . '</option>'; 
		}
			$html .= '</select>';
		} 
    $html .= '</label></p></form>';
    return $html;
}

/** PH7
 * Inline styles on grid page and single
 * @since 1.0.2
 * @return HTML
 */

function profile_details_tsw_inline_page_styles()
{
	$classes = get_body_class();
if(in_array('pdtsw-gridpage-contact', $classes) || in_array('pdtsw-gridpage-nocontact', 
$classes) ) {
	$options = get_option('profile_details_tsw');
    $pdtsw_hdbkg   = (empty ( $options["profile_details_tsw_gridhead"] ) ) 
                ? '#fafafa' : $options["profile_details_tsw_gridhead"];
	$pdtsw_h6color = (empty ( $options["profile_details_tsw_heading_color"] ) ) 
                ? '#3c4c5f' : $options["profile_details_tsw_heading_color"];
	$pdtsw_gridht  = (empty ( $options["profile_details_tsw_gridHeight"] ) ) 
                ? '360'     : $options["profile_details_tsw_gridHeight"];
	$pdtsw_grliwd  = (empty ( $options["profile_details_tsw_gridWidth"] ) ) 
                ? '33.3333336'     : $options["profile_details_tsw_gridWidth"];
echo '<style id="pdtsw-inlinegridview">
.pdtsw_row strong, .profiletsw-grid h6{color: '. esc_attr($pdtsw_h6color) .';font-weight:bolder;}
.pdtsw-gridpage-contact .profiletsw-entry-grid{min-height: '. esc_attr($pdtsw_gridht) . 'px;}
.pdtsw-avatar-grid{background: '. esc_attr($pdtsw_hdbkg) . ';}
@media screen and (min-width: 780px){.profiletsw-grid li {width: '. esc_attr($pdtsw_grliwd) . '%;}</style>';
	} else {
	return false;
	}
} 
