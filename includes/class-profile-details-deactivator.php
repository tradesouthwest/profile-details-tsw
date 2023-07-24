<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://tradesouthwest
 * @since      1.0.0
 *
 * @package    profile_details
 * @subpackage profile_details/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    profile_details
 * @subpackage profile_details/includes
 * @author     Larry Judd <tradesouthwest@gmail.com>
 */
class Profile_Details_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
 
		remove_role( 'pdtsw_mediator' );
		
	}

}