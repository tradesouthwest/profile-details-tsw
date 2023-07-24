<?php
/**
 * Provide a public-facing author functionality for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin Shortcodes.
 *
 * @link       https://tradesouthwest.com
 * @since      1.0.0
 *
 * @package    Profile_Details
 * @subpackage Profile_Details/public/partials
 */
if ( ! defined( 'ABSPATH' ) ) exit;
    // Avatar size in grid view TODO: option?
    $sz             = "72"; 
    // Options
    $social_privi   = profile_details_tsw_social_privi();
    $social_anchor  = profile_details_tsw_social_anchor();
    $social_urls    = profile_details_tsw_social_urls();
    $contact_privi  = profile_details_tsw_contact_privi();
    $contact_selctd = profile_details_tsw_contact_selected();
    $hoverclass     = profile_details_tsw_contact_display();
    $extend_info    = profile_details_tsw_extend_info();
    $allow_avatar   = profile_details_tsw_allow_avatar();    
    $exsvg = '<svg viewBox="0 0 40 35" width="18" height="17" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M14 4h-13v18h20v-11h1v12h-22v-20h14v1zm10 5h-1v-6.293l-11.646 11.647-.708-.708 11.647-11.646h-6.293v-1h8v8z"/></svg>';
    $pdtws_debug    = pdtswset_debug_mode(); 
