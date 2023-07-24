<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tradesouthwest.com
 * @since      1.0.0
 *
 * @package    Profile_Details
 * @subpackage Profile_Details/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Profile_Details
 * @subpackage Profile_Details/public
 * @author     Larry Judd <tradesouthwest@gmail.com>
 */
class Profile_Details_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profile_Details_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profile_Details_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) 
            . 'css/profile-details-public.css', array(), time(), 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profile_Details_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profile_Details_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

	/* wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) 
            . 'js/profile-details-public.js', array( 'jquery' ), time(), false );
		wp_localize_script( 
        'profile-details-public', 
        'pdtswFrmObject', 
        array( 
            'ajaxurl'     => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce('pdtsw_frm_action_nonce', 'pdtsw_frm_nonce'),
			'orderby_var' => 'pdtsw_sortform_dropdown' 
        )
    ); */
	
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Profile_Details_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Profile_Details_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		add_shortcode( 'profile_details_table', 'profile_details_tsw_shortcode_table');
		add_shortcode( 'profile_details_grid', 'profile_details_tsw_shortcode_grid');
		add_shortcode( 'profile_details_category', 'profile_details_tsw_shortcode_category');
		add_shortcode( 'profile_details_profile', 'profile_details_tsw_render_author_page');

	}
}
