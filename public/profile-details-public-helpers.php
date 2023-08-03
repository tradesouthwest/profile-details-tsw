<?php
/**
 * Provide assistance to the public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://tradesouthwest.com
 * @since      1.0.0
 *
 * @package    Profile_Details
 * @subpackage Profile_Details/public/partials
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// #AP0
add_action( 'pdtsw_debug_profile_url', 'pdtsw_display_correct_profile_url');

/**
* @id AP0
 * String to save for rebuilding queries in the loop
 *
 * @param  $query_vars[] profile_id
 * @return Int           To be stripped and sanitized in new action hook.
 * @since  1.0.31
 */

function pdtsw_display_correct_profile_url(){
    if ( get_query_var( 'profile_id' ) ){ 

        echo 'profileIdHash: ' . get_query_var( 'profile_id' );
        
    }
    
}

// #AP1
add_action( 'init', 'profile_details_tsw_contact_body_class' );

/** 
 * @id AP1
 * Add body_class if contact details are hidden on public grid
 *
 * @since 1.0.02
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function profile_details_tsw_contact_body_class($classes)
{

    $options = get_option('profile_details_tsw');
    $state   = (empty ( $options["profile_details_tsw_contact_privi"] ) ) 
                ? false : true;
    
    if( $state == false ) {
        add_filter( 'body_class', function ($classes) {
            if ( isset( $classes['pdtsw-gridpage-nocontact'] ) ) 
            {
                unset( $classes[array_search('pdtsw-gridpage-nocontact', $classes)] );
            }
            $classes[] = 'pdtsw-gridpage-contact';
            return $classes;
        });    
       
    } else {
       add_filter( 'body_class', function ($classes) {
            if ( isset( $classes['pdtsw-gridpage-contact'] ) ) 
            {
                unset( $classes[array_search('pdtsw-gridpage-contact', $classes)] );
            }
            $classes[] = 'pdtsw-gridpage-nocontact';
            return $classes;
        });
    }    
} 

/** 
 * Check to not show social links details on users profile
 *
 * @since 1.0.02
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function profile_details_tsw_social_author_remove()
{
    $options = get_option('profile_details_tsw');
    $state   = (empty ( $options["profile_details_tsw_social_author_urls"] ) ) 
                ? false : true;

        return $state;
} 
/**
 * Get user's capabilities.
 *
 * @param  int|WP_User $user The user ID or object. Default is the current user.
 *
 * @return array             The user's capabilities or empty array if none or user doesn't exist.
 */
function profile_details_tsw_get_user_capabilities( $user = null ) {

	$user = $user ? new WP_User( $user ) : wp_get_current_user();

	return array_keys( $user->allcaps );
}

/**
 * Get author meta for single profile page
 *
 * @param string $meta_key   Meta key
 * @param string $profile_id ID of user
 * @see https://developer.wordpress.org/reference/functions/get_the_author_meta/
 * @return string            Text values
 */
function pdtswget_author_meta($meta_key, $profile_id)
{
    
    $meta = get_the_author_meta( $meta_key, $profile_id );
    $rtrn = ( '' != $meta ) ? $meta : '';

        return $rtrn;
}

/**
 * Get option url. Page that is saved as Select Page with Gridview Shortcode.
 *
 * @since 1.0.0
 */
function profile_details_tsw_get_gridview_page()
{
    $options      = get_option('profile_details_tsw');
    $option       = $options["profile_details_tsw_gridview_page"];
    $gridview_url = ( '' != $option ) ? get_page_link(absint($option)) : '';

        return $gridview_url;
}

/**
 * Get option url. Page that is saved as Select Page with Tableview Shortcode.
 *
 * @since 1.0.0
 */
function profile_details_tsw_get_tableview_page()
{
    $options     = get_option('profile_details_tsw');
    $option      = $options["profile_details_tsw_tableview_page"];
    $profile_url = ( '' != $option ) ? get_page_link(absint($option)) : '';

        return $profile_url;
}

/**
 * Get option url. Page that is saved as Select Page with Profile Shortcode.
 *
 * @uses $option string | int Id of page.
 * @since 1.0.02
 */
function  profile_details_tsw_get_author_page()
{
    $options     = get_option('profile_details_tsw');
    $option      = $options["profile_details_tsw_author_page"];
    $profile_url = ( '' != $option ) ? get_page_link(absint($option)) : '';

        return $profile_url;
}
/**
 * Get option url. Page that is saved as Select Page with Category Shortcode.
 *
 * @since 1.0.02
 */
function  profile_details_tsw_get_category_page()
{
    $options     = get_option('profile_details_tsw');
    $option      = $options["profile_details_tsw_category_page"];
    $profile_url = ( '' != $option ) ? get_page_link(absint($option)) : '';

        return $profile_url;
}

/**
 * Get option table head name.
 *
 * @since 1.0.02
 */
function profile_details_tsw_thead($opt)
{
    $opt     = ( '' != $opt ) ? $opt : '';
    $options = get_option('profile_details_tsw');

    $option  = $options["profile_details_tsw_thead_{$opt}"];
    $rtrn    = ( '' != $option ) ? $option : '';

        return $rtrn;
        $opt = null;
} 

/**
 * Get some option checkbox Boolean state for admin control of levels/roles.
 *
 * @since 1.0.03
 * @return Boolean
 */
function profile_details_tsw_admin_assigns()
{
    $state   = false;
    $options = get_option('profile_details_tsw');
    $state   = ( empty ( $options["profile_details_tsw_admin_assigns"] ) ) 
                ? true : false;
    
        return $state;
} 

/** 
 * Check if Not viewable to public
 *
 * @since 1.0.2
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function profile_details_tsw_table_private()
{
    $viewable = false;
    $options  = get_option('profile_details_tsw');
    $state    = (empty($options["profile_details_tsw_table_privi"])) ? 'off' : 'on';

    if ( is_user_logged_in() && 'on' == $state && ( 
        current_user_can( 'administrator' ) || current_user_can( 'editor' ) ) ) {
        $viewable = true; 

    } elseif 
        (is_user_logged_in() && 'on' == $state && ( 
        !current_user_can( 'administrator' ) || !current_user_can( 'editor' ) ) ) {  
        $viewable = false;

    } elseif 
        ( !is_user_logged_in() && 'on' == $state ) {
        $viewable = false;

    } else {
        $viewable = true;
    }

        return $viewable;
} 
/**
 * check if admin or editor 
 * @deprecated 
 */
function profile_details_tsw_is_site_admin()
{
    return in_array( array(
                    'administrator', 
                    'editor'
                    ),  
            wp_get_current_user()->roles 
            );
}

/** 
 * Check to NOT show social anywhere
 *
 * @since 1.0.02
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function profile_details_tsw_social_urls()
{

    $options = get_option('profile_details_tsw');
    $state   = (empty ( $options["profile_details_tsw_social_author_urls"] ) ) 
                ? false : true;

        return $state;
} 
/** 
 * Check to not show social as anchors on public grid
 *
 * @since 1.0.02
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function profile_details_tsw_social_anchor()
{

    $options = get_option('profile_details_tsw');
    $state   = (empty ( $options["profile_details_tsw_social_anchor"] ) ) 
                ? 'on' : 'off';

        return $state;
} 

/** 
 * Check to not show contact details on public grid
 *
 * @since 1.0.02
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function profile_details_tsw_social_privi()
{

    $options = get_option('profile_details_tsw');
    $state   = (empty ( $options["profile_details_tsw_social_privi"] ) ) 
                ? 'off' : 'on';

        return $state;
} 

/** 
 * Check to not show contact details on public grid
 *
 * @since 1.0.02
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function profile_details_tsw_contact_privi()
{

    $options = get_option('profile_details_tsw');
    $state   = (empty ( $options["profile_details_tsw_contact_privi"] ) ) 
                ? false : true;

        return $state;
} 

/**
 * Get option for grid header color
 *
 * @deprecated @since 1.0.21
 */
function profile_details_tsw_gridhead()
{
    $options = get_option('profile_details_tsw');
    $color   = (empty ( $options["profile_details_tsw_gridhead"] ) ) 
                ? "#fafafa" : $options["profile_details_tsw_gridhead"];
        
        return $color;
} 

/**
 * Get text for link to profile in gridview 
 *
 * @since 1.0.03
 */
function profile_details_tsw_viewlink()
{
    $options = get_option('profile_details_tsw');
    $color   = (empty ( $options["profile_details_tsw_viewlink"] ) ) 
                ? "view" : $options["profile_details_tsw_viewlink"];
        
        return $color;
} 

/**
 * Get method to show email or contact on public side
 *
 * @param string $contact_selected
 * @since 1.0.02
 */
function profile_details_tsw_contact_selected()
{
    $options  = get_option('profile_details_tsw');
    $selected = (empty ( $options["profile_details_tsw_contact_selected"] ) )
                ? 'user_emailx' : $options["profile_details_tsw_contact_selected"];

    $rtrn = array();
    switch( $selected ) {

    case 'user_emailx': 
        $rtrn = 'user_email';
    break;
    case 'user_emaily':
        $rtrn = 'user_email';
    break;
    case 'ID':
        $rtrn = 'ID';
    break;
    case 'user_other':
        $rtrn = 'first_name';
    break;
    default:
        $rtrn = 'user_email';
    break;
    } 

        return $rtrn;
} 

/**
 * Get HTML to show email or name on public side
 *
 * @param string $userid GETs from loop
 * @since 1.0.02
 */
function profile_details_tsw_contact_html()
{
    $options = get_option('profile_details_tsw');
    $state   = (empty ( $options["profile_details_tsw_contact_privi"] ) ) 
                ? false : true;
    
    $html = array();
    switch( $state ) {

    case false: 
        $html = 'profiletsw-display';
    break;
    case true: 
        $html = 'profiletsw-hidden';
    break;
    default: 
        $html = 'profile-display';
    break;

    }
        return $html;
}
/**
 * Get class for email or contact on public side
 *
 * @param string $contact_selected
 * @since 1.0.02
 */
function profile_details_tsw_contact_display()
{
    $options  = get_option('profile_details_tsw');
    $selected = (empty ( $options["profile_details_tsw_contact_selected"] ) )
                ? 'user_emailx' : $options["profile_details_tsw_contact_selected"];

    switch( $selected ) {

    case 'user_emailx': 
        $rtrn = 'profiletsw-hover';
    break;
    case 'user_emaily':
        $rtrn = 'profiletsw-display';
    break;
    case 'ID':
        $rtrn = 'profiletsw-hidden';
    break;
    case 'user_other':
        $rtrn = 'profiletsw-display';
    break;
    default:
        $rtrn = 'profiletsw-display';
    break;
    } 

        return $rtrn;
}

/**
 * Selected options for user Roles to display
 *
 * @param string[] $roles
 * @return array
 */
function profile_details_tswget_select_roles()
{
    //global $wp_roles;

    //if ( !isset( $wp_roles ) ) $wp_roles = new WP_Roles();
    $roles   = array();
    $options = get_option('profile_details_tsw');
    $roles   = (empty ( $options["profile_details_tsw_select_roles"] ) ) 
                ? array( 'subscriber', 'contributor', 'author' ) 
                : $options["profile_details_tsw_select_roles"];
    
    $roles = "'" . implode("', '", $roles) . "'";

        return sanitize_text_field($roles);
}
// pagination count
function profile_details_gettsw_pagination()
{
    $options = get_option('profile_details_tsw');
    $count   = (empty ( $options["profile_details_tsw_pagination"] ) ) 
                ? '32' : $options["profile_details_tsw_pagination"];

        return $count;
}
// profile_details_tsw_before_block
function profile_details_tsw_before_block()
{

    $options = get_option('profile_details_tsw');
    $content = (empty ( $options["profile_details_tsw_before_block"] ) ) 
                ? '' : $options["profile_details_tsw_before_block"];

        return $content;
} 
// profile_details_tsw_after_block
function profile_details_tsw_after_block()
{

    $options = get_option('profile_details_tsw');
    $content   = (empty ( $options["profile_details_tsw_after_block"] ) ) 
                ? '' : $options["profile_details_tsw_after_block"];

        return $content;
} 
// edit link @TODO
function pdtswget_editfront_author($current_user_id)
{
//if ('' != $current_user_id )

//include PROFILE_DETAILS_TSW_PATH . '/templates/profile-details-template-front.php';

return false;
} 
/** 
 * Get use Avatar
 *
 * @since 1.0.31
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function profile_details_tsw_allow_avatar()
{
    $allow = ( empty (
    get_option('profile_details_tsw')['profile_details_tsw_allow_author_avatar']))
    ? false : true;

        return $allow;
}
/** 
 * Check to turn on extended info in public view
 *
 * @since 1.0.02
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function profile_details_tsw_extend_info()
{

    $options = get_option('profile_details_tsw');
    $state   = (empty ( $options["profile_details_tsw_extend_info"] ) ) 
                ? false : true;

        return $state;
} 
 
/**
 * Get custom "mediator" role name
 */
function profile_details_tsw_pdtsw_mediator()
{

    $options          = get_option('profile_details_tsw');
    $pdtsw_mediator = (empty ( $options['profile_details_tsw_pdtsw_mediator'] ) )
                        ? '' : $options['profile_details_tsw_pdtsw_mediator'];

        return $pdtsw_mediator;
}

/**
 * Get user roles by user ID.
 *
 * @param  int $id
 * @return array
 */
function pdtsw_user_roles_by_id( $id )
{
    $user = new WP_User( $id );

    if ( empty ( $user->roles ) or ! is_array( $user->roles ) )
        return array ();

    $wp_roles = new WP_Roles;
    $names    = $wp_roles->get_names();
    $out      = array ();

    foreach ( $user->roles as $role )
    {
        if ( isset ( $names[ $role ] ) )
            $out[ $role ] = $names[ $role ];
    }

    return $out;
}
/**
* Sanitize SVG markup for front-end display.
*
* @param  string $svg SVG markup to sanitize.
* @return string 	  Sanitized markup.
*/
function pdtsw_sanitize_svg( $svg = '' ) {
	$allowed_html = [
		'svg'  => [
			'xmlns'       => [],
			'fill'        => [],
			'viewbox'     => [],
			'role'        => [],
			'aria-hidden' => [],
			'focusable'   => [],
			'height'      => [],
			'width'       => [],
		],
		'path' => [
			'd'    => [],
			'fill' => [],
		],
	];

	return wp_kses( $svg, $allowed_html );
}
/** 
 * Check to turn on debug mode
 *
 * @since 1.0.02
 * @param string $state Option not checked is true
 * @return Bool 
 */ 
function pdtswset_debug_mode()
{

    $options = get_option('profile_details_tsw');
    $state   = (empty ( $options["profile_details_tsw_debug_mode"] ) ) 
                ? false : true;

        return $state;
} 
