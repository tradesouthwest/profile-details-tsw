<?php

/**
 * Fired during plugin activation
 *
 * @link       https://tradesouthwest
 * @since      1.0.0
 *
 * @version    1.0.32
 * @package    profile_details
 * @subpackage profile_details/includes
 */

class Profile_Details_Activator {

	/**
	 * Activate plugin with WP.
	 * Attempts activation of plugin in a “sandbox” and redirects on success.
	 * @since    1.0.0
	 * @return false;
	 */
	public static function activate() {

		flush_rewrite_rules();
	}
	
} 
