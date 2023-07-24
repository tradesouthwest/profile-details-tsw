<?php
/**
 * Plugin Name:       Profile Details TSW 
 * Description:       Creates easy to view user profile details. Settings and Help under Settings > Profile Details TSW
 * Author:            Tradesouthwest
 * Author URI:        https://tradesouthwest.com/
 * Version:           1.0.4
 * Requires PHP:      7.2
 * Requires CP:       1.4
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * Documentation URI: https://leadspilot.com/test/business-directory/wp-content/plugins/profile-details-tsw/docs/
 */  
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! defined( 'PROFILE_DETAILS_VERSION' ) ) { 
	    define('PROFILE_DETAILS_VERSION', '1.0.4' ); }
if ( !defined ( 'PROFILE_DETAILS_TSW_PATH' ) ) { 
	    define( 'PROFILE_DETAILS_TSW_PATH', plugins_url( '', __FILE__ ) ); }
/**
 * Load early on or after init to register custom taxonomy
 *
 * @since 1.0.02
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/profile-details-taxonomy.php';

/** 
 * Filter WordPress/ClassicPress to allow our custom parameters in a query.
 *
 * @param $query_vars Accept custom query variable and check for its existence.
 * @since 1.0.31
 */

add_filter( 'query_vars', 'pdtsw_manage_profile_user_query');

function pdtsw_manage_profile_user_query( $query_vars ){
	$query_vars[] = 'profile_id';
	
		return $query_vars;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-profile-details-activator.php
 */ 

function activate_profile_details_tsw() 
{

require_once plugin_dir_path( __FILE__ ) . 'includes/class-profile-details-activator.php';
	Profile_Details_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-profile-details-deactivator.php
 */
function deactivate_profile_details_tsw() 
{

require_once plugin_dir_path( __FILE__ ) . 'includes/class-profile-details-deactivator.php';
	Profile_Details_Deactivator::deactivate();
}

register_activation_hook(   __FILE__, 'activate_profile_details_tsw' );
register_deactivation_hook( __FILE__, 'deactivate_profile_details_tsw' );

require_once plugin_dir_path( __FILE__ ) . 'includes/profile-details-requires.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
 
function run_profile_details_tsw() {

	$plugin = new Profile_Details();
	$plugin->run();

}
run_profile_details_tsw(); 
