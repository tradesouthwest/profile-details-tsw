<?php
/** 
 * @package Profile Details TSW 
 * @since 1.0.1
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Profile_Details_Tsw_Template_Settings 
{
	private $file;
	private $plugin_name = 'Profile Details TSW';
	private $plugin_slug = 'profile_details_tsw';
	private $textdomain  = 'profile-details-tsw';
	private $options;
	private $settings;

	public function __construct( $plugin_name, $plugin_slug, $file ) {
		$this->plugin_slug = $plugin_slug;
		$this->plugin_name = $plugin_name;

		// Initialise settings
		add_action( 'admin_init', array( $this, 'init' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->file ) , 
                    array( $this, 'add_settings_link' ) 
                );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init() {
		$this->settings = $this->settings_fields();
		$this->options = $this->get_options();
		$this->register_settings();
	}

	/**
	 * Options getter
	 * @return array Options, either saved or default ones.
	 */
	public function get_options() {
		$options = get_option($this->plugin_slug);

		if ( !$options && is_array( $this->settings ) ) {
			$options = Array();
			foreach( $this->settings as $section => $data ) {
				foreach( $data['fields'] as $field ) {
					$options[ $field['id'] ] = $field['default'];
				}
			}

			add_option( $this->plugin_slug, $options );
		}

		return $options;
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item() {
		$page = add_options_page( $this->plugin_name, 
								  $this->plugin_name, 
								  'manage_options' , 
								  $this->plugin_slug,  
								  array( $this, 'settings_page' ) 
								);
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link( $links ) {
		$settings_link = '<a href="options-general.php?page='.$this->plugin_slug.'">' 
					. esc_html__( 'Settings', 'profile-details-tsw' ) . '</a>';
  							array_push( $links, $settings_link );
  		
		  	return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields() {
		 
		$settings['textfields'] = array(
			'title'					=> __( 'Text Fields', 'profile-details-tsw' ),
			'description'			=> __( 'Add custom name of table titles. Leave blank for none.', 'profile-details-tsw' ),
			'fields'				=> array( 
				array(
					'id' 			=> 'profile_details_tsw_thead_1',
					'label'			=> __( 'Row 1' , 'profile-details-tsw' ),
					'description'	=> __( 'Text for first row. Can be left blank in most cases.', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				), 
				array(
					'id' 			=> 'profile_details_tsw_thead_2',
					'label'			=> __( 'Row 2' , 'profile-details-tsw' ),
					'description'	=> __( 'Text for second row. Person/Member maybe', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				), // 2= user name
				array(
					'id' 			=> 'profile_details_tsw_thead_3',
					'label'			=> __( 'Row 3' , 'profile-details-tsw' ),
					'description'	=> __( 'Text for third row. Details/Objective maybe', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				), // 3= profile_details custom
				array(
					'id' 			=> 'profile_details_tsw_thead_4',
					'label'			=> __( 'Row 4' , 'profile-details-tsw' ),
					'description'	=> __( 'Text for fifth row. Bio/Further maybe', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				), // 4= bio
				array(
					'id' 			=> 'profile_details_tsw_thead_5',
					'label'			=> __( 'Row 5' , 'profile-details-tsw' ),
					'description'	=> __( 'Text for fifth row. Website/Forms maybe', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> 'Website',
					'placeholder'	=> ''
				), // 5= url
				array(
					'id' 			=> 'profile_details_tsw_thead_6',
					'label'			=> __( 'Row 6' , 'profile-details-tsw' ),
					'description'	=> __( 'Text for sixth row. Joined On maybe (Date register defaults)', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'Joined On', 'profile-details-tsw' )
				), // 6= registration date
				array(
					'id' 			=> 'profile_details_tsw_thead_7',
					'label'			=> __( 'Row 7' , 'profile-details-tsw' ),
					'description'	=> __( 'Text for seventh row. Contact/Internal Email maybe', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'EMail Contact', 'profile-details-tsw' )
				), // 7= contact
				array(
					'id' 			=> 'profile_details_tsw_pdtsw_mediator',
					'label'			=> __( 'Custom Role Name' , 'profile-details-tsw' ),
					'description'	=> __( 'Name of custom role to add. Supervisor/Mediator maybe (Leave blank to not use custom role.)', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> ''
				), 
				array(
					'id' 			=> 'profile_details_tsw_contact_privi',
					'label'			=> __( 'Make Contact Private', 'profile-details-tsw' ),
					'description'	=> __( 'Check box to NOT show Email field.', 'profile-details-tsw' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'profile_details_tsw_contact_selected',
					'label'			=> __( 'Select Contact to Display', 'profile-details-tsw' ),
					'description'	=> __( 'If Contact shown, select what to display.', 'profile-details-tsw' ),
					'type'			=> 'select',
					'options'       => array( 'user_emailx' => __( 'Email on Hover Only', 'profile-details-tsw' ), 
											  'user_emaily' => __( 'Email Shown - not recommended', 'profile-details-tsw' ),
											  'user_other'  => __( 'First Name Displayed', 'profile-details-tsw' ),  
											  'ID'          => __( 'Show Nothing in this Field', 'profile-details-tsw' ) 
											),
					'default'		=> array('user_emailx')
				),
				array(
					'id' 			=> 'profile_details_tsw_social_privi',
					'label'			=> __( 'Display Social', 'profile-details-tsw' ),
					'description'	=> __( 'Check box to SHOW social links in public profiles. Unchecked leaves admin side fields but not public side.', 'profile-details-tsw' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'profile_details_tsw_social_anchor',
					'label'			=> __( 'Social Link Action', 'profile-details-tsw' ),
					'description'	=> __( 'Check box to make social links active/clickable.', 'profile-details-tsw' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'profile_details_tsw_social_author_urls',
					'label'			=> __( 'Remove Social Links', 'profile-details-tsw' ),
					'description'	=> __( 'Check box to remove ALL social urls from User Panel 
										and public profiles.', 'profile-details-tsw' ),
					'type'			=> 'checkbox',
					'default'		=> 'off'
				) /*
				array(
					'id' 			=> 'profile_details_tsw_allow_author_avatar',
					'label'			=> __( 'Allow Upload Picture', 'profile-details-tsw' ),
					'description'	=> __( 'Check box to allow uploading of individual profile picture. 
										This replaces default WordPress Avatar.', 'profile-details-tsw' ),
					'type'			=> 'checkbox',
					'default'		=> 'off'
				)	*/	
			)
		);

		$settings['advanced'] = array(
			'title'					=> __( 'Page Views', 'profile-details-tsw' ),
			'description'			=> __( 'Advanced setting for content displayed.', 'profile-details-tsw' ),
			'fields'				=> array(
				array(
					'id' 			=> 'profile_details_tsw_author_page',
					'label'			=> __( 'Select Page with user PROFILE Shortcode', 'profile-details-tsw' ),
					'description'	=> __( 'shortcode: [profile_details_profile] These pages must be set here so that internal forms will work correctly.', 'profile-details-tsw' ),
					'type'			=> 'dropdown-pages',
					'default'       => ''
				),
				array(
					'id' 			=> 'profile_details_tsw_gridview_page',
					'label'			=> __( 'Page with GRID view Shortcode', 'profile-details-tsw' ),
					'description'	=> __( 'shortcode: [profile_details_grid]', 'profile-details-tsw' ),
					'type'			=> 'dropdown-pages',
					'default'       => ''
				),
				array(
					'id' 			=> 'profile_details_tsw_tableview_page',
					'label'			=> __( 'Page with TABLE view Shortcode', 'profile-details-tsw' ),
					'description'	=> __( 'shortcode: [profile_details_table]', 'profile-details-tsw' ),
					'type'			=> 'dropdown-pages',
					'default'       => ''
				),
				array(
					'id' 			=> 'profile_details_tsw_category_page',
					'label'			=> __( 'Page with CATEGORY view Shortcode', 'profile-details-tsw' ),
					'description'	=> __( 'shortcode: [profile_details_category]', 'profile-details-tsw' ),
					'type'			=> 'dropdown-pages',
					'default'       => ''
				),
				array(
					'id' 			=> 'profile_details_tsw_table_privi',
					'label'			=> __( 'Make TABLE view Private', 'profile-details-tsw' ),
					'description'	=> __( 'Check box to show person table ONLY to Administrator and Editors.', 'profile-details-tsw' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'profile_details_tsw_pagination',
					'label'			=> __( 'Pagination' , 'profile-details-tsw' ),
					'description'	=> __( 'How many profiles to show per page.', 'profile-details-tsw' ),
					'type'			=> 'number',
					'default'		=> '',
					'placeholder'	=> __( '32', 'profile-details-tsw' )
				), 
				array(
					'id' 			=> 'profile_details_tsw_extend_info',
					'label'			=> __( 'Show Extended Info', 'profile-details-tsw' ),
					'description'	=> __( 'Check box to show extended three fields on individual public profile.', 
										'profile-details-tsw' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				),
				array(
					'id' 			=> 'profile_details_tsw_select_roles',
					'label'			=> __( 'Only show these roles', 'profile-details-tsw' ),
					'description'	=> __( 'Select roles to show on front end. Hold CTRL to select multiple.', 'profile-details-tsw' ),
					'type'			=> 'checkbox_multi',
					'options'		=> array( 
						'subscriber'      => 'Subscriber', 
						'author'          => 'Author', 
						'editor'          => 'Editor', 
						'contributor'     => 'Contributor', 
						'pdtsw_mediator'  => 'Custom', 
						'administrator'   => 'Administrator' 
					),
					'default'		=> array( 
										'subscriber', 
										'contributor', 
										'author' 
					)
				), 
				/* array(
					'id' 			=> 'profile_details_tsw_admin_assigns',
					'label'			=> __( 'Admin Selects Category', 'profile-details-tsw' ),
					'description'	=> __( 'Check box to to allow ONLY administrator or custom role* to assign user categories.', 'profile-details-tsw' ),
					'type'			=> 'checkbox',
					'default'		=> 'on'
				), */
				array(
					'id' 			=> 'profile_details_tsw_viewlink',
					'label'			=> __( 'Text in View Link' , 'profile-details-tsw' ),
					'description'	=> __( 'Wording inside of link of author grid header.', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> 'view',
					'placeholder'	=> __( 'view', 'profile-details-tsw' )
				),
				array(
					'id' 			=> 'profile_details_tsw_gridhead',
					'label'			=> __( 'Background Color in Grid Heading' , 'profile-details-tsw' ),
					'description'	=> __( 'Set color for the background in grid view headers.', 'profile-details-tsw' ),
					'type'			=> 'text_color',
					'default'		=> '#fafafa',
					'class'         => 'pdtswfirst-color-field'
				),
				array(
					'id' 			=> 'profile_details_tsw_heading_color',
					'label'			=> __( 'Title Color in Grid Heading' , 'profile-details-tsw' ),
					'description'	=> __( 'Set color for the title (H6) of each row.', 'profile-details-tsw' ),
					'type'			=> 'text_color',
					'default'		=> '#3b3b3b',
					'class'         => 'pdtswsecond-color-field'
				),
				array(
					'id' 			=> 'profile_details_tsw_gridHeight',
					'label'			=> __( 'Height Adjust Grids' , 'profile-details-tsw' ),
					'description'	=> __( 'Adjustment to make grids equal heights in pixels (exclude px, just numbers). Height will vary depending on theme used and amount of content.', 'profile-details-tsw' ),
					'type'			=> 'number',
					'default'		=> '460',
					'placeholder'   => ''
				),
				array(
					'id' 			=> 'profile_details_tsw_gridWidth',
					'label'			=> __( 'Width Adjust Grids at 780px wide' , 'profile-details-tsw' ),
					'description'	=> __( 'Set to 33.33333336 or 50. If theme is full width try 25. (w/out %sign) Plugin will add the percent sign.', 'profile-details-tsw' ),
					'type'			=> 'text',
					'default'		=> '33.3333336',
					'placeholder'   => ''
				),
				array(
					'id' 			=> 'profile_details_tsw_before_block',
					'label'			=> __( 'Content Before Grid or Table' , 'profile-details-tsw' ),
					'description'	=> __( 'Text area for content.', 'profile-details-tsw' ),
					'type'			=> 'textarea',
					'default'		=> '',
					'placeholder'	=> __( 'HTML and text', 'profile-details-tsw' )
				),
				array(
					'id' 			=> 'profile_details_tsw_after_block',
					'label'			=> __( 'Content After Grid or Table' , 'profile-details-tsw' ),
					'description'	=> __( 'Text area for content.', 'profile-details-tsw' ),
					'type'			=> 'textarea',
					'default'		=> '',
					'placeholder'	=> __( 'HTML and text', 'profile-details-tsw' )
				)
			)
		);

		$settings['docs'] = array(
			'title'					=> __( 'Help', 'profile-details-tsw' ),
			'description'			=> __( 'Documentation and Links', 'profile-details-tsw' ),
			'fields'				=> array(
				array(
					'id' 			=> 'profile_details_tsw_link_1',
					'label'			=> __( 'Documentation' , 'profile-details-tsw' ),
					'url'			=> site_url('/') . 'wp-content/plugins/profile-details-tsw/docs/',
					'description'	=> __( 'Read help and instructions.', 'profile-details-tsw' ),
					'type'			=> 'link',
					'class'		    => 'button',
					'target'        => '_blank',
					'value'         => __( 'View Documentation', 'profile-details-tsw' ),
					'title'	        => __( 'Read plugin documentation', 'profile-details-tsw' )
				),
				/* removed for security risk
				array(
					'id' 			=> 'profile_details_tsw_debug_mode',
					'label'			=> __( 'Turn on Share Mode', 'profile-details-tsw' ),
					'description'	=> __( 'Check box to show a share link at the bottom of profiles. CAUTION gives out id of user.', 'profile-details-tsw' ),
					'type'			=> 'checkbox',
					'default'		=> 'off'
				),  */
				array(
					'id' 			=> 'profile_details_tsw_cats_link',
					'label'			=> __( 'Add or Change Categories', 'profile-details-tsw' ),
					'class'	        => 'button', 
					'type'			=> 'link',
					'target'        => '',
					'value'         => __( 'Add/Edit Categories', 'profile-details-tsw' ),
					'title'         => __( 'Profile categories can be edited here', 'profile-details-tsw' ),
					'url'	    	=> admin_url('edit-tags.php?taxonomy=profile_details'),
					'description'   => __( 'Profile categories can be edited here', 'profile-details-tsw' )
				),
				array(
					'id' 			=> 'pdtsw_custom_roles_version',
					'label'			=> __( 'Custom Role Reset' , 'profile-details-tsw' ),
					'description'	=> __( 'Only for Debug. Keeps database from loading on page loads (should be 1.)', 'profile-details-tsw' ),
					'type'			=> 'number',
					'default'		=> '',
					'placeholder'	=> ''
				), 
				array(
					'id' 			=> 'profile_details_tsw_getting_started',
					'label'			=> __( 'Getting Started', 'profile-details-tsw' ),
					'description'	=> __( 'Instructions to help setup Profile Details TSW plugin.', 'profile-details-tsw' ),
					'type'			=> 'instructions',
				)
			)
		);

		$settings = apply_filters( 'plugin_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings() {
		if( is_array( $this->settings ) ) {

			register_setting( $this->plugin_slug, $this->plugin_slug, 
								array( $this, 'validate_fields' ) );

			foreach( $this->settings as $section => $data ) {

				// Add section to page
				add_settings_section( $section, $data['title'], 
										array( $this, 'settings_section' ), 
										$this->plugin_slug 
										);

				foreach( $data['fields'] as $field ) {

					// Add field to page
					add_settings_field( $field['id'], $field['label'], 
										array( $this, 'display_field' ), 
												$this->plugin_slug, 
												$section, array( 'field' => $field ) 
												);
				}
			}
		}
	}

	public function settings_section( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo wp_kses_post( $html );
	}

	/**
	 * Generate HTML for displaying fields  
	 * @param  array $args Field data
	 * @return void
	 */
	/* TODO class="' . esc_attr( $field['class'] ) . '" */
	public function display_field( $args ) {

		$field = $args['field'];

		$html = '';

		$option_name = $this->plugin_slug ."[". $field['id']. "]";

		$data = (isset($this->options[$field['id']])) ? $this->options[$field['id']] : '';

		switch( $field['type'] ) {

			case 'text':
			case 'password':
			case 'number':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" 
				name="' . esc_attr( $option_name ) . '" 
				placeholder="' . esc_attr( $field['placeholder'] ) . '" 
				value="' . $data . '"/>' . "\n";
			break;

			case 'text_secret':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="text" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value=""/>' . "\n";
			break;

			case 'text_color':
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" 
				class="' . esc_attr($field['class']) . ' color-picker" 
				type="text" name="' . esc_attr( $option_name ) . '" 
				value="' . $data . '" data-default-color="' . $field['default'] . '">' . "\n";
			break;

			case 'textarea':
				$html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '">' . $data . '</textarea><br/>'. "\n";
			break;

			case 'checkbox':
				$checked = '';
				if( $data && 'on' == $data ){
					$checked = 'checked="checked"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" 
						 type="' . $field['type'] . '" 
						 name="' . esc_attr( $option_name ) . '" ' . $checked . '/>' . "\n";
			break;

			case 'link':
				$html .= '<a id="' . esc_attr( $field['id'] ) . '" 
							class="' . $field['class'] . '" 
						 	href="'. esc_url( $field['url'] ) .'" 
							title="' . esc_attr( $field['title'] ) . '" 
						 	target="' . esc_attr( $field['target'] ) . ' ">' 
							. esc_html( $field['value'] ) . '</a>';
			break;

			case 'checkbox_multi':
				foreach( $field['options'] as $k => $v ) {
					$checked = false;
					if( is_array($data) && in_array( $k, $data ) ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '">
					      <input type="checkbox" ' . checked( $checked, true, false ) . ' 
						         name="' . esc_attr( $option_name ) . '[]" 
								 value="' . esc_attr( $k ) . '" 
								 id="' . esc_attr( $field['id'] . '_' . $k ) . '" />
								 <span class="pdtsw-push">' . $v . ' </span></label> ';
				}
			break;

			case 'radio':
				foreach( $field['options'] as $k => $v ) {
					$checked = false;
					if( $k == $data ) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '"><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" /> ' . $v . '</label> ';
				}
			break;

			case 'select':
				$html .= '<select name="' . esc_attr( $option_name ) . '" 
						 id="' . esc_attr( $field['id'] ) . '">';
				foreach( $field['options'] as $k => $v ) {
					$selected = false;
					if( $k == $data ) {
						$selected = true;
					}
					$html .= '<option value="' . esc_attr( $k ) . '"'; 
					$html .= selected( $selected, true, false );
					$html .= '>' . $v . '</option>';
				}
				$html .= '</select> ';
			break;
			
			case 'dropdown-pages': 
				$html .= '<select name="' . esc_attr( $option_name ) . '" 
							id="' . esc_attr( $field['id'] ) . '">';
				$pages = get_pages(array('sort_column' => 'post_date', 'sort_order' => 'desc'));
				foreach( $pages as $page ) {
						$selected = '';
						if( $page->ID == $data ) {
							$selected = true;
						}
				$html .= '<option   
						 value="' . esc_attr( $page->ID ) . '" 
						 ' . selected( $selected, true, false ) . ' >'
						 . $page->post_title . '</option> ';
				}
				$html .= '</select> ';
		    break;

			case 'select_multi':
				$html .= '<select name="' . esc_attr( $option_name ) . '[]" id="' . esc_attr( $field['id'] ) . '" multiple="multiple">';
				foreach( $field['options'] as $k => $v ) {
					$selected = false;
					if( in_array( $k, $data ) ) {
						$selected = true;
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '" />' . $v . '</label> ';
				}
				$html .= '</select> ';
			break;

		}

		switch( $field['type'] ) {

			case 'checkbox_multi':
			case 'radio':
			case 'select_multi':
				$html .= '<br/><span class="description">' . $field['description'] . '</span>';
			break;

			case 'instructions':
				$html .= '<div class="pdtsw-help"><strong>'. $field['description'] .'</strong>
				<h5><strong>' . __( 'Due to User information which displays in this plugin TABLE VIEW or "list view" MAY NOT BE USE ON HOME PAGES','profile-details-tsw') . '</strong></h5>
				<p>'. esc_html__( 'This section describes how to setup the plugin and get it working.', 'profile-details-tsw') .'</p>
				<p>'. esc_html__( '1. Create pages with appropriate names and Add Shortcodes to Pages.', 'profile-details-tsw') .'</p>
				<ul class="unordered-list">
				<li>'. esc_html__( ' To display GRID view:', 'profile-details-tsw') .'<span>  [profile_details_grid] </span></li>
				<li>'. esc_html__( ' To display TABLE view:', 'profile-details-tsw') .'<span>  [profile_details_table] </span></li>
				<li>'. esc_html__( ' To display individual&#39;s PROFILES:', 'profile-details-tsw') .'<span>  [profile_details_profile] </span></li>
				<li>'. esc_html__( ' To display CATEGORY view:', 'profile-details-tsw') .'<span>  [profile_details_category] </span></li></ul>
				<p>'. esc_html__( '2. Optional, add CATEGORY shortcode to page or use widget to display categories', 'profile-details-tsw') .'</p>
				<p>'. esc_html__( '3. Go through settings and add field names and page view preferrences.', 'profile-details-tsw') .'</p></div>';

			break;

			default:
				$html .= '<label for="' . esc_attr( $field['id'] ) . '">
				<span class="description">' . $field['description'] . '</span></label>' . "\n";
			break;
		}
		//echo wp_kses( $html, profile_details_admin_allowed_html() );
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Validate individual settings field
	 * @param  array $data Inputted value
	 * @return array       Validated value
	 */
	public function validate_fields( $data ) {
		// $data array contains values to be saved:
		// either sanitize/modify $data or return false
		// to prevent the new options to be saved

		// Sanitize fields, eg. cast number field to integer
		// $data['number_field'] = (int) $data['number_field'];

		// Validate fields, eg. don't save options if the password field is empty
		// if ( $data['password_field'] == '' ) {
		// 	add_settings_error( $this->plugin_slug, 'no-password', __('A password is required.', 'profile-details-tsw'), 'error' );
		// 	return false;
		// }

		return $data;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page() {
		// Build page HTML output
	?>
	  <div class="wrap" id="<?php echo esc_attr( $this->plugin_slug ); ?>">
	  	<h2><?php esc_html_e('Profile Details TSW Plugin Settings', 'profile-details-tsw'); ?></h2>

		<!-- Tab navigation starts -->
		<h2 class="nav-tab-wrapper settings-tabs hide-if-no-js">
			<?php
			foreach( $this->settings as $section => $data ) {
				echo '<a href="#' . esc_attr($section) . '" class="nav-tab">' . esc_html($data['title']) . '</a>';
			}
			?>
		</h2>
		<?php $this->do_script_for_tabbed_nav(); ?>
		<!-- Tab navigation ends -->

		<form action="options.php" method="POST">
	        <?php settings_fields( $this->plugin_slug ); ?>
	        <div class="settings-container">
	        <?php do_settings_sections( $this->plugin_slug ); ?>
	    	</div>
	        <?php submit_button(); ?>
		</form>
	</div>
	<?php 
	}

	/**
	 * Print jQuery script for tabbed navigation
	 * @return void
	 */
	private function do_script_for_tabbed_nav() {
		// Very simple jQuery logic for the tabbed navigation.
		// Delete this function if you don't need it.
		// If you have other JS assets you may merge this there.
		?>
		<script>
		jQuery(document).ready(function($) {
			var headings = jQuery('.settings-container > h2, .settings-container > h3');
			var paragraphs  = jQuery('.settings-container > p');
			var tables      = jQuery('.settings-container > table');
			var triggers = jQuery('.settings-tabs a');
			var saving = jQuery('.submit #submit');

			triggers.each(function(i){
				triggers.eq(i).on('click', function(e){
					e.preventDefault();
					triggers.removeClass('nav-tab-active');
					headings.hide();
					paragraphs.hide();
					tables.hide();

					triggers.eq(i).addClass('nav-tab-active');
					headings.eq(i).show();
					paragraphs.eq(i).show();
					tables.eq(i).show();
				});
			})

			triggers.eq(0).click();
		});
		</script>
	<?php
	}
}
