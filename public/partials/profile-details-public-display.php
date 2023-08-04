<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin Shortcodes.
 *
 * @since      1.0.5
 * @package    Profile_Details
 * @subpackage Profile_Details/public/partials
 * 
 * ======== !!! ======== !!! ===========
 * @uses Each view (grid, table) 
 * has its own dropdown search function. 
 *       DO NOT MIX THE TWO UP
 * =====================================
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/** PD1
 * Dropdown sortable query for TABLE VIEW
 *
 * @since 1.0.4
 * @return HTML 
 */

function profile_details_tsw_tableform_dropdown()
{

    $html = ''; $sort_by = 'user_registered'; $order_is = 'ASC';

    if( $_SERVER["REQUEST_METHOD"] == "POST" ) :
    
    $submitted_value = wp_unslash( sanitize_key( $_REQUEST['pdtsw_frm_nonce'] ));
	    
        if( !wp_verify_nonce( esc_attr( $submitted_value ), 'pdtsw_frm_nonce' )){ 
            exit("No funny business please. Line 31"); 
        }

    $sort_by  = ( !isset( $_POST['pdtsw_sortform_dropdown'] ) ) 
              ? 'user_registered' 
              : sanitize_text_field( wp_unslash( $_POST['pdtsw_sortform_dropdown'] )); 
    $order_is = ( !isset( $_POST['pdtsw_sortform_order'] ) ) 
              ? 'ASC' 
              : sanitize_text_field( wp_unslash( $_POST['pdtsw_sortform_order'] )); 
    endif;

    $categor_url = profile_details_tsw_get_category_page();        
    $argz = array(
        'user_registered' => __('Registered Date', 'profile-details-tsw' ),
        'display_name'   => __('Display Name', 'profile-details-tsw' ),
        'user_login'    => __('Login Name', 'profile-details-tsw' ),
        'last_name'    => __('Last Name', 'profile-details-tsw' ),
        'first_name'  => __('First Name', 'profile-details-tsw' ),
        'ID'        => __('ID#', 'profile-details-tsw' ),
        'email'    => __('EMail', 'profile-details-tsw' ),
        'url'     => __('Site Link', 'profile-details-tsw' ),
        );
    
    ob_start(); 
    echo 
    '<form id="pdtsw-sortform" method="POST" 
        action="'. esc_url_raw( wp_unslash( $_SERVER["REQUEST_URI"] ) ) .'">
        <table id="pdtswTableSort" class="profiletsw-sort-table"><tbody>
        <tr>
        <td><label for="pdtsw-sortform-dropdown">
            <span>'. esc_html__('Sort by ', 'profile-details-tsw') . '</span> 
        <select id="pdtsw-sortform-dropdown" name="pdtsw_sortform_dropdown" 
                class="pdtsw-select" onchange="this.form.submit()">';
    
    foreach( $argz as $key => $value ) {
	echo'<option value="'. esc_attr( $key ) .'" 
                '. selected( $key, $sort_by, false ) .'>' 
                . esc_attr( $value ).'</option>'; 
	} 

    echo '</select></label></td>
        <td><label for="pdtsw_sortform_order">' . esc_html__( 'Order Ascending ', 'profile-details-tsw' );
    echo '<input type="radio" value="ASC" name="pdtsw_sortform_order" 
            ' . checked( esc_attr( $order_is ), 'ASC', false) .' onchange="this.form.submit()">
        <span> </span>' . esc_html__( 'Descending ', 'profile-details-tsw' );
    echo '<input type="radio" value="DESC" name="pdtsw_sortform_order" 
            ' . checked( esc_attr( $order_is ), 'DESC', false) .' onchange="this.form.submit()"></label></td>
        <td><a href="'. esc_url( $categor_url ) .'" 
            title="'. esc_attr__( 'View by Categories', 'profile-details-tsw' ) . '" 
            class="pdtsw-button">'. esc_html__( 'Sort by ', 'profile-details-tsw' ) 
            . esc_html( profile_details_tsw_thead(absint(3)) ) .'</a></td>
        <td></td>
        </tr></tbody></table>
        <input type="hidden" 
            value="' . esc_attr( wp_create_nonce('pdtsw_frm_nonce')) .'" 
            name="pdtsw_frm_nonce">
    </form>';

    echo ob_get_clean(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Profile Details TSW TABLE SCREEN
 * 
 * @param string $option The ID of the option being displayed.
 * @uses shortcode [profile_details_table] Preferrably for Admin view only.
 * @since 1.0.01
 */
function profile_details_tsw_shortcode_table($atts, $content = null)
{
    extract( shortcode_atts( array(    // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
        'id' => null,
    ), $atts ) );
    // create strings for displaying table
    $viewable       = profile_details_tsw_table_private();
    $contact_privi  = profile_details_tsw_contact_privi();
    $contact_selctd = profile_details_tsw_contact_selected();
    $hoverclass     = profile_details_tsw_contact_display();
    $profile_url    = profile_details_tsw_get_author_page();  
    $sz             = "32";  // Avatar size in table view
    $ppp_is         = absint(50);

    ob_start();
    echo 
    '<section class="profiletsw-section">
        <div class="profiletsw-before-content">';
    
    echo wp_kses_post( wpautop( wptexturize( 
            profile_details_tsw_before_block() ) ) ); ?>

        </div><div class="tswclearfix"></div>

        <div class="pdtsw-sortform">
            <div class="pdtsw-tableform-sort">
            
                <?php do_action( 'pdtsw_tableform_dropdown' ); ?>
            
            </div>
        </div>

        <?php 
        /* Get posted sorting filters 
        * ************************** */
        
    if( $_SERVER["REQUEST_METHOD"] == "POST" ) :
        $submitted_value = esc_attr( wp_unslash( sanitize_key( 
		$_REQUEST['pdtsw_frm_nonce'] ) ) );
	    
        if( !wp_verify_nonce( esc_attr( $submitted_value ), 'pdtsw_frm_nonce' )){ 
            exit("No funny business please line 140"); 
        }
    endif;

        $order_by = ( isset( $_POST['pdtsw_sortform_dropdown'] ) ) 
                ? sanitize_text_field( wp_unslash( $_POST['pdtsw_sortform_dropdown']) ) 
                : 'user_registered';
        $order_iz = ( isset( $_POST['pdtsw_sortform_order'] ) ) 
                ? sanitize_text_field( wp_unslash( $_POST['pdtsw_sortform_order'] ) ) 
                : 'ASC'; 
    
        /* WP_User_Query arguments 
         * *********************** */
        // count the number of users found in the query 
        $count_args  = array(
                'fields' => 'all_with_meta',
                'number' => 999999      
                );
        $user_count_query = new WP_User_Query($count_args);
        $user_count       = $user_count_query->get_results();
        $total_users      = $user_count ? count($user_count) : 1;

        // grab the current page number and set to 1 if no page number is set
        $page        = get_query_var('paged') ? (int) get_query_var('paged') : 1;
        $total_pages = 1;
        $offset      = $ppp_is * ($page - 1);
        $total_pages = ceil($total_users / $ppp_is);

        $args = array( 
                'orderby' => sanitize_key($order_by),
                'order'   => sanitize_key($order_iz),
                'number'  => $ppp_is,
                'paged'   => $page 
                );
      
        // Create the WP_User_Query object
        $wp_user_query  = new WP_User_Query($args);
        $users          = $wp_user_query->get_results();

    echo '
    <div id="content">
        <table id="pdtswTableView" class="profiletsw-table">
            <thead class="profiletsw-thead"><tr>
                <th>';
    echo        '<small>';
        $til = ( '' != profile_details_tsw_thead(absint(1)) ) ? 
             esc_html( profile_details_tsw_thead(absint(1)) ) : 
             esc_html__('link', 'profile-details-tsw');
    echo esc_html( $til ) . '</small></th>
                <th>' . esc_html( profile_details_tsw_thead(absint(2)) ) . '</th>
                <th>' . esc_html( profile_details_tsw_thead(absint(5)) ) . '</th>
                <th>' . esc_html( profile_details_tsw_thead(absint(6)) ) . '</th>
                <th>' . esc_html( profile_details_tsw_thead(absint(7)) ) . '</th>
                <th>' . esc_html( profile_details_tsw_thead(absint(3)) ) . '</th>
                <th>' . esc_html__('#', 'profile-details-tsw' ) . '</th>
            </tr></thead>
            <tbody class="pdtsw_sortable">';
   
if ( $viewable ): 

    // User Loop
    if ( ! empty( $users ) ) { 

        foreach ( $users as $user ) {
        echo 
        '<tr class="profiletsw-tr">
        <td class="pdtsw-first">
            <form action="' . esc_url( $profile_url ) .'" method="POST">
                <input type="hidden" name="profile_id" 
                    value="'. absint($user->ID) .'">
                <button id="'. esc_attr( $user->display_name ) .'" 
                    class="tiny-submit" 
                    type="submit" 
                    title="' . esc_attr( $user->display_name ) . '">
                <span id="avatar-'. esc_attr( $user->display_name ) .'" 
                    title="'. esc_attr( $user->display_name ) .' 
                    '. esc_attr( $user->ID ) .'">' . get_avatar( $user->ID, $sz ) . '
                </span></button>
                <input type  ="hidden" 
                    value="'. esc_attr( wp_create_nonce( 'pdtsw_author_nonce' ) ) .'" 
                    name="pdtsw_author_nonce">
            </form>
        </td>
        <td class="pdtsw-second">' . esc_html($user->display_name) . ' <small><em class="maybehidden">(' 
            . esc_html($user->user_login) . ')</em><span class="color-green"> ' 
            . esc_html($user->last_name) . ', ' . esc_html($user->first_name) 
            . '</span></small></td>
        <td class="pdtsw-third"><a href="'  . esc_url($user->user_url) . '" 
            title="'  . esc_html($user->user_url) . '" target="_blank">' 
            . esc_html($user->user_url) . '</a></td>
        <td class="pdtsw-fourth">' . esc_html( mb_substr($user->user_registered, 0, -8) ) . '</td>
        <td class="pdtsw-fifth"><span class="profiletsw-priv"> 
            <a href="" class="' . esc_attr( $hoverclass ) . '" 
            title="'. esc_attr($user->$contact_selctd) .'">'
            . esc_html($user->$contact_selctd) . '</a></span></td>
        <td class="pdtsw-sixth profile-details-terms">';

            $profile_terms = wp_get_object_terms( $user->ID,  'profile_details' );
            
            if ( !empty( $profile_terms ) ) {
                foreach ( $profile_terms as $term ) { 
                    echo '<div>' . esc_html( $term->name ) . ' </div>';
                }
            } else {
                    echo esc_html__( 'Not Specified.', 'profile-details-tsw' );
            }
        echo '</td>
        <td class="pdtsw-last"><span class="' . esc_attr( $hoverclass ) . '">
        <a href="/wp-admin/user-edit.php?user_id=' . absint( $user->ID ) . '" 
            title="'. esc_attr__( 'edit ', 'profile-details-tsw' ) 
            .' ' . esc_attr($user->display_name) . '#'. esc_attr( $user->ID ).'">
        [ ' . esc_html($user->ID) . ' ]</a></span></td>
        </tr>';
        
        } // ends foreach USER LOOP
        
    } 
        else {
        echo '<tr><td colspan=7>
        ' . esc_html__( 'No profiles found. Try refreshing permalinks or adding new user levels to the view.', 'profile-details-tsw' ) . '</td></tr>';
        }

else: 

        echo '<tr><td colspan=7>
        <h4>' . esc_html__( 'Table viewable only to administrative persons.', 'profile-details-tsw' ) . '</h4></td></tr>';
    
endif;

        echo '</tbody>
        </table><div class="tswclearfix"></div>

            <footer class="pdtsw-navtext">
                <p>' . esc_html__( 'pg. ', 'profile-details-tsw') . esc_html($page) 
                . esc_html__( ' of ', 'profile-details-tsw') . esc_html($total_pages) 
                . esc_html__( ' pgs.', 'profile-details-tsw') . '</p>
                <div class="tswclearfix"></div>
                <nav class="profile-details-pagination">';  

        if ( $page > 1 ) {
        echo '<a href="'. esc_attr( add_query_arg(array( 'paged' => $page-1 ))) .'" title="-">'
                . esc_html__( 'Previous Page', 'profile-details-tsw') . '</a>';
        }
        // Next page
        if ( $page < $total_pages ) {
        echo '<a href="'. esc_attr( add_query_arg(array( 'paged' => $page+1 ))) .'" title="+">'
                . esc_html__( 'Next Page', 'profile-details-tsw') . '</a>';
        }
        echo '</nav>
            </footer>
    </div>'; /* ends content */

        echo '<div class="profiletsw-after-content">'

        . wp_kses_post( wpautop( wptexturize( 
                profile_details_tsw_after_block() ) ) )

        . '</div>
    
    </section>';

        return ob_get_clean();
}


/** PD3
 * Dropdown sortable query for GRID VIEW
 *
 * @since 1.0.4
 * @uses pdtsw_gridfrm_nonce Nonce to verify selection
 * @return HTML 
 */
function profile_details_tsw_gridsortform_dropdown()
{
    // pdtsw_gridfrm_nonce
    if ($_SERVER["REQUEST_METHOD"] == "POST"):
        if (isset( $_REQUEST['nonce'] ) 
        && !wp_verify_nonce( sanitize_key( $_REQUEST['pdtsw_gridfrm_nonce'] ), 
            "pdtsw_gridfrm_nonce" ) ) {
            exit("Please Try Again.");
        }
    endif;
    
    $sort_by  = isset( $_POST['pdtsw_gridsortform_dropdown'] ) 
                ? sanitize_text_field( wp_unslash( $_POST['pdtsw_gridsortform_dropdown'] ) )
                : sanitize_text_field( 'user_registered' );
    $order_is = isset( $_POST['pdtsw_gridsortform_order'] ) 
                ? sanitize_text_field( wp_unslash( $_POST['pdtsw_gridsortform_order'] ) ) 
                : sanitize_text_field('ASC');  
    
    $args = array(
        'user_registered' => __( 'Registered Date', 'profile-details-tsw' ),
        'display_name'   => __( 'Display Name', 'profile-details-tsw' ),
        'last_name'    => __( 'Last Name', 'profile-details-tsw' ),
        'first_name'  => __( 'First Name', 'profile-details-tsw' ),
    );

    ob_start();
    echo
    '<form id="pdtsw-gridsortform" method="POST" 
        action="'. esc_url_raw( wp_unslash( $_SERVER["REQUEST_URI"] ) ) . '">
    <table class="pdtsw-table"><tbody>
    <tr>
    <td> <label for="pdtsw-gridsortform-dropdown">'. esc_html__('Sort by ', 'profile-details-tsw');
    echo '<select id="pdtsw-gridsortform-dropdown" 
             name="pdtsw_gridsortform_dropdown" 
             class="pdtsw-select" onchange="this.form.submit()">';
             
    foreach( $args as $key => $value ) {
	echo 
    '<option value="'. esc_attr($key) .'" '. selected($key, $sort_by, false) . '>' 
	      . esc_html( $value ) . '</option>'; 
	}

	echo '</select></label></td>
    <td><label for="pdtsw_gridsortform_order">'. esc_html__('Ascending ', 'profile-details-tsw');
    echo '<input type="radio" value="ASC" name="pdtsw_gridsortform_order" 
                ' . checked($order_is, 'ASC', false) . ' onchange="this.form.submit()"></label>
    <label for="pdtsw_gridsortform_order">' . esc_html__('Descending ', 'profile-details-tsw');
    echo '<input type="radio" value="DESC" name="pdtsw_gridsortform_order" 
                ' . checked($order_is, 'DESC', false) . ' onchange="this.form.submit()"></label>
    </td>
    <td><input type="hidden" value="'. esc_attr( wp_create_nonce( 'pdtsw_gridfrm_nonce' )).'" 
        name="pdtsw_gridfrm_nonce">
    </td>
    </tr>
    </tbody></table></form>';
    $output = ob_get_clean();
    
        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
             
}

/**
 * Profile Details TSW Public GRID SCREEN VIEW
 * 
 * @param string | int $user The ID of the user being displayed.
 * @param string Top section sets strings to function values. Spelling intentional
 * @uses shortcode [profile_details_grid]
 * @since 1.0.02
 */
function profile_details_tsw_shortcode_grid($atts = null, $content = null)
{
    if( ! is_page() || ! function_exists('profile_details_tsw_contact_privi') ) 
        return; 
      // reset query
    $wp_user_query = ''; $wp_user_query = null;
    $users         = ''; $users         = null;
    $vieu          = ''; $order_by = $order_is = '';
    // Avatar size in grid view TODO: option?
    $sz            = absint(52);  
    $contact_privi = profile_details_tsw_contact_privi();
    $contact_selctd= profile_details_tsw_contact_selected();
    $hoverclass    = profile_details_tsw_contact_display();
    $profile_url   = profile_details_tsw_get_author_page(); 
    $viewlink      = profile_details_tsw_viewlink();
    $cellclass     = profile_details_tsw_contact_html();
    $ppp           = ( empty ( profile_details_gettsw_pagination() ) ) ? 12 
                             : profile_details_gettsw_pagination();
    $vieu          = get_option('profile_details_tsw')['profile_details_tsw_viewlink'];
    
    ob_start();
    echo 
    '<section class="profiletsw-section-grid">
        <div id="content" class="profile-details-grid">

            <div class="profiletsw-before-content">'
            . wp_kses_post( wpautop( wptexturize( 
                profile_details_tsw_before_block() ) ) ) . '
            </div>';
        
            echo 
                '<div class="pdtsw-sortform">'; 

                    do_action('pdtsw_gridsortform_dropdown');
            
            echo    '</div>';

            // pdtsw_gridfrm_nonce
            if( $_SERVER["REQUEST_METHOD"] == "POST" ) :
            $submitted_value = wp_unslash( sanitize_text_field( $_REQUEST['pdtsw_gridfrm_nonce'] ));

            if( !wp_verify_nonce( esc_attr( $submitted_value ), 'pdtsw_gridfrm_nonce' ) ) { 
                exit( "Please try again. Line 424" );
            }
            endif;

            /* only one post will be set 
            * @uses !sanitize_key would strip uppercase */
            $order_by = ( isset( $_POST['pdtsw_gridsortform_dropdown'] ) ) 
                ? sanitize_text_field( wp_unslash( $_POST['pdtsw_gridsortform_dropdown'] ) ) 
                : 'user_registered';
            $order_is = ( isset( $_POST['pdtsw_gridsortform_order'] ) ) 
                ? sanitize_text_field( wp_unslash( $_POST['pdtsw_gridsortform_order'] ) )
                : 'ASC';
            /*
            * Pagination for user query
            * @since 1.0.21
            * @see https://wordpress.stackexchange.com/questions/57427/how-to-display-pagination-links-for-wp-user-query
            */ 
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            // count the number of users that should be passed over in the pages (offset) – this will take effect at the second page onwards.
            $offset = ($paged - 1) * $ppp; 

            // Add query arguments for pagination
            $args = array( 
                'orderby' => sanitize_text_field($order_by),
                'order'   => sanitize_text_field($order_is),
                'number'  => intval( $ppp ),
                'offset'  => esc_attr( $offset )
            );
    
            // Create the WP_User_Query object to fetch total users without 'number' and 'offset' arguments
            $total_users_query = new WP_User_Query($args);
            $total_users       = $total_users_query->total_users;

            // Create the WP_User_Query object again with updated arguments
            $wp_user_query = new WP_User_Query($args);

            // Get the results
            $total_query = $wp_user_query->total_users;
            $total_pages = intval( $total_users / $ppp ) + 1;

            // Use the following with foreach() loop to display results as per your requirements
            $user_query = $wp_user_query->get_results();

            echo 
            '<div class="profile-details-list-container">
                <ul class="profiletsw-grid">';
    
                if ( ! empty( $user_query ) ) { 
                // start foreach            
                foreach ( $user_query as $user ) 
                { 
                
                echo 
                '<li><article class="profiletsw-entry-grid">
                <div class="pdtsw-avatar-grid">
                <header class="pdtsw-title-grid">
                    <form action="' . esc_url( $profile_url ) .'" method="POST">
                    <input type="hidden" name="profile_id" 
                        value="'. absint($user->ID) .'">
                    <button type="submit" 
                        id="'. esc_attr($user->display_name) .'" 
                        class="tiny-submit" 
                        title="' . esc_attr($user->display_name) . '">
                    <figure class="profiletsw-figlink" 
                        title="' . esc_attr( $vieu . ' ' . $user->display_name) . '">'
                        . get_avatar( $user->ID, $sz ) . '</figure>
                    <figcaption class="captioned-grid-titles">' 
                        . esc_html($user->display_name) . '</figcpation>
                    </figure></button>
                    <input type="hidden" 
                        value="'. esc_attr( wp_create_nonce( 'pdtsw_author_nonce' )) .'" 
                        name="pdtsw_author_nonce">
                    </form>
                    <span role="heading" aria-level="2" class="screen-reader-text" 
                        title="' .  esc_attr( profile_details_tsw_thead( absint(2) ) ) 
                        . ' ' . esc_html( $user->display_name ) . '"></span>
                </header>
                </div><div class="tswclearfix"></div>
        
                <div class="pdtsw-taxonomy-grid pdtsw-eqh eqh-first">
                    <h6>' . esc_html( profile_details_tsw_thead( absint(3) ) ) . '</h6>
                    <p>';
                $profile_terms = wp_get_object_terms( $user->ID, 'profile_details' );
                /* 
                * profile_details tax; profile_details-taxonomy terms 
                */
                if ( !empty( $profile_terms ) ) {
                
                    foreach( $profile_terms as $term ) { 
                        echo '<span title="' . esc_attr( $term->slug ) . '">'
                        . esc_html( $term->name ) . '</span>';
                    }
                } 
                else {
                    echo '<span class="canbe_hidden">'
                    . esc_html__( 'Not Specified.', 'profile-details-tsw' ) .'</span>';
                }
                echo '</p>
                </div>

                <div class="profiletsw-dscr pdtsw-eqh">
                    <h6>' . esc_attr( profile_details_tsw_thead(absint(4) ) ) . '</h6>';
                    $descript = wp_kses_post( get_the_author_meta( 'description', $user->ID ) );
                echo esc_html( substr( $descript, 0, 115 ) . '...' ); 
                echo 
                '</div>
                <div class="pdtsw-url-grid pdtsw-eqh">
                    <h6>' . esc_attr( profile_details_tsw_thead(absint(5) ) ) . '</h6>
                    <p><a class="pdtsw-grid-url" href="' . esc_url( $user->user_url ) . '" 
                    title="'. esc_attr( $user->user_url ) . '" target="_blank">
                    ' . esc_url( $user->user_url ) . '</a></p>
                </div>
                <div class="pdtsw-regdate-grid pdtsw-eqh">
                    <h6>' . esc_attr( profile_details_tsw_thead( absint(6) ) ) . '</h6>
                    <p>' . esc_html( mb_substr( $user->user_registered, 0, -8 ) ) . '</p>
                </div>';
                
                if  ( ! $contact_privi ): 
                echo 
                '<div class="' . esc_attr( $cellclass ) . '">
                    <div class="pdtsw-contact-grid pdtsw-eqh">
                    <h6>' . esc_attr( profile_details_tsw_thead(absint(7)) ) . '</h6>
                    <p class="' . esc_attr( $hoverclass ) . '"><em class="profiletsw-priv-grid">
                    <span title="' . esc_attr( $user->$contact_selctd ) . '">'
                    . esc_html( $user->$contact_selctd ) . '</span></em></p>
                    </div>
                </div>';
                endif;

                echo 
                '</article></li>';

                } // ends foreach
                    wp_reset_postdata();
            
            echo '</ul>
            </div>'; /* ends profile-details-list-container */

            echo
            '<footer class="pdtsw-navtext">
                <p>' . esc_html__( 'pg. ', 'profile-details-tsw') . intval( $paged ) 
                . esc_html__( ' of ', 'profile-details-tsw') . absint( $total_pages ) 
                . esc_html__( ' pgs.', 'profile-details-tsw') . '</p>
                <div class="tswclearfix"></div>
                <nav class="profile-details-pagination">
                    <div id="pagination" class="clearfix gridview-pagi">';

                if( $total_users >= $total_query ) {

                    $current_page = max( 1, get_query_var('paged') );

                echo '<span class="pagination-links">'; 
                echo  wp_kses_post( 
                    paginate_links( array(
                        'base'      => get_pagenum_link(1) . '%_%',
                        'format'    => 'page/%#%/',
                        'current'   => absint( $current_page ),
                        'total'     => intval( $total_pages ),
                        'prev_next' => true,
                        'show_all'  => true,
                        'type'      => 'plain',
                        )) 
                    );
                echo '</span>';
                
                }

            echo '</div>
                </nav>
            </footer>';

            // kick out if there were no users    
            } else {
                echo '<h5>' . esc_html__('No profiles found.', 'profile-details-tsw') . '</h5>';
            }

            echo 
            '<div class="profiletsw-after-content">'
            .  wp_kses_post( wpautop( wptexturize( 
                    profile_details_tsw_after_block() ) ) )  
            . '</div>
         
        </div>

    </section>';

        $output = ob_get_clean();
        
            return $output;
} 
