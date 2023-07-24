<?php
/**
 * Provide a public-facing content functionality for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin Shortcodes.
 *
 * @link       https://tradesouthwest.com
 * @since      1.0.0 //$users = array_slice($users, $offset, $ppp);
 * @version    1.0.3
 * @package    Profile_Details
 * @subpackage Profile_Details/public/partials
 */
if ( ! defined( 'ABSPATH' ) ) exit;
    //create new object pass in number of pages and identifier 
    $sz             = absint(52);  // Avatar size in grid view TODO: option?
    $contact_privi  = profile_details_tsw_contact_privi();
    $contact_selctd = profile_details_tsw_contact_selected();
    $hoverclass     = profile_details_tsw_contact_display();
    $profile_url    = profile_details_tsw_get_author_page(); 
    $viewlink       = profile_details_tsw_viewlink();
    $cellclass      = profile_details_tsw_contact_html();
    $ppp            = profile_details_gettsw_pagination();
    $total          = count( $users);
    $pages          = ceil( $total / $ppp );
    $current        = isset($_GET['page']) ? $_GET['page'] : 1;
    $next           = ( $current + 1 ) ? $current + 1 : null;
    $previous       = $next - 1;
    $offset         = ($current - 1) * $ppp;
