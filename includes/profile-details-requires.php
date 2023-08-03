<?php 
/**
 * The core plugin file that is used to include helper files and other 
 * admin-specific hooks, and public-facing site hooks.
 * Includes terms to load early for CPT init
 * @package Profile_Details_Tsw
 * @subpackage includes/profile-details-requires
 */
if ( ! defined( 'ABSPATH' ) ) exit;

require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-profile-details.php';
/**
 * late load scripts for public views
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/profile-details-public-functions.php'; 

// PD1
add_action('pdtsw_tableform_dropdown', 'profile_details_tsw_tableform_dropdown', 10); 
// PD3
add_action('pdtsw_gridsortform_dropdown', 'profile_details_tsw_gridsortform_dropdown', 10);
// C1 @subpackage public/partials/profile-details-category
add_action('pdtsw_dropdown_category_children','profile_details_tsw_sortform_dropdown_categories',10);

/**
 * Display options for public views
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/profile-details-public-display.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/profile-details-author-content.php';
/**
 * The files responsible for handling helper functionality which occurs in the 
 * public-facing side of the site.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/profile-details-public-helpers.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/partials/profile-details-category.php'; 	

/**
 * Admin side scripts and setting options 
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/profile-details-admin-display.php';
//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/profile-details-admin-helpers.php';
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-profile-details-tsw-settings.php';
// run settings template class
$settings = new Profile_Details_Tsw_Template_Settings( 'Profile Details TSW', 'profile_details_tsw', __FILE__ ); 
