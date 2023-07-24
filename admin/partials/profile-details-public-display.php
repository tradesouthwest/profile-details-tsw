<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin Shortcodes.
 *
 * @since      1.0.1
 * @version    1.0.31
 * @package    Profile_Details
 * @subpackage Profile_Details/public/partials
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/** PD1
 * Dropdown sortable query for TABLE VIEW
 *
 * @since 1.0.31
 * @return HTML 
 */

function profile_details_tsw_sortform_dropdown()
{
    $html = '';
    $sort_by  =  ( !isset($_POST['pdtsw_sortform_dropdown'] ) ) ? 'user_registered' 
                        : $_POST['pdtsw_sortform_dropdown']; 
    $order_is = ( !isset( $_POST['pdtsw_sortform_order'] ) ) ? 'ASC' 
                        : $_POST['pdtsw_sortform_order']; 
    $categor_url = profile_details_tsw_get_category_page();        
    $argz = array(
            'user_registered' => __('Registered Date'),
            'display_name'   => __('Display Name'),
            'user_login'    => __('Login Name'),
            'last_name'    => __('Last Name'),
            'first_name'  => __('First Name'),
            'ID'        => __('ID#'),
            'email'    => __('EMail'),
            'url'     => __('Site Link'),
            );

    $html .= 
    '<form id="pdtsw-sortform" method="POST" action="'.htmlspecialchars($_SERVER["REQUEST_URI"]).'">
        <table id="pdtswTableSort" class="profiletsw-sort-table"><tbody>
        <tr>
        <td><label for="pdtsw-sortform-dropdown"><span>'. __('Sort by ', 'profile-details-tsw');
    $html .= ' </span> 
        <select id="pdtsw-sortform-dropdown" name="pdtsw_sortform_dropdown" 
                class="pdtsw-select" onchange="this.form.submit()">';
    
    foreach( $argz as $key => $value ) {
	    $html .= '<option value="'.esc_attr($key).'" '.selected($key,$sort_by,false).'>' 
                 . esc_html($value).'</option>'; 
	} 

    $html .= '</select></label></td>
        <td><label for="pdtsw_sortform_order">' . __('Order Ascending ', 'profile-details-tsw');
    $html .= '<input type="radio" value="ASC" name="pdtsw_sortform_order" 
             ' . checked($order_is, 'ASC', false) .' onchange="this.form.submit()">
        <span> </span>' . __('Descending ', 'profile-details-tsw');
    $html .= '<input type="radio" value="DESC" name="pdtsw_sortform_order" 
             ' . checked($order_is, 'DESC', false) .' onchange="this.form.submit()"></label></td>
        <td><a href="'. esc_url( $categor_url ) .'" 
            title="'. __('View by Categories', 'profile-details-tsw') . '" 
            class="pdtsw-button">'. __('View by ', 'profile-details-tsw'). esc_html(profile_details_tsw_thead(absint(3))) .'</a></td>
        <td></td>
        </tr></tbody></table>
        <input type="hidden" value="' . wp_create_nonce('pdtsw_frm_nonce') .'" name="nonce">
        </form>';
    
        echo $html;     
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
    extract( shortcode_atts( array(
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
    echo '<section class="profiletsw-section">
              <div class="profiletsw-before-content">';
    
    echo    wp_kses_post( wpautop( wptexturize( 
                profile_details_tsw_before_block() ) ) ); 
    ?>

            </div><div class="tswclearfix"></div>

            <div class="pdtsw-sortform">
                <div class="pdtsw-tableform-sort">
                <?php 
                    do_action( 'pdtsw_sortform_dropdown' ); 
                ?>
                </div>
            </div>

    <?php 
    /* Get posted sorting filters 
     * ************************** */
    if ($_SERVER["REQUEST_METHOD"] == "POST"):
        if (isset( $_REQUEST['nonce'] ) 
        && !wp_verify_nonce( $_REQUEST['nonce'], "pdtsw_frm_nonce")) {
            exit("No funny business please");
        }
    endif; 

        $order_by = ( isset($_POST['pdtsw_sortform_dropdown'] ) ) 
                    ? sanitize_text_field($_POST['pdtsw_sortform_dropdown']) 
                    : 'user_registered';
        $order_iz = ( isset( $_POST['pdtsw_sortform_order'] ) ) 
                    ? sanitize_text_field($_POST['pdtsw_sortform_order']) : 'ASC'; 
    
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
                'orderby' => sanitize_text_field($order_by),
                'order'   => sanitize_text_field($order_iz),
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
            $til = ( '' != esc_html( profile_details_tsw_thead(absint(1)) ) ) ? 
                esc_html( profile_details_tsw_thead(absint(1)) ) : 
                esc_html__('link', 'profile-details-tsw');
    echo        $til . '</small></th>
                <th>' . esc_html( profile_details_tsw_thead(absint(2)) ) . '</th>
                <th>' . esc_html( profile_details_tsw_thead(absint(5)) ) . '</th>
                <th>' . esc_html( profile_details_tsw_thead(absint(6)) ) . '</th>
                <th>' . esc_html( profile_details_tsw_thead(absint(7)) ) . '</th>
                <th>' . esc_html( profile_details_tsw_thead(absint(3)) ) . '</th>
                <th>' . esc_html__('*', 'profile-details-tsw' ) . '</th>
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
            <input type="hidden" name="profile_id" value="'. absint($user->ID) .'">
            <button type="submit" id="'. sanitize_title_with_dashes($user->display_name) .'" 
            class="tiny-submit" title="'. esc_attr($user->display_name) .'">
            <span class="profiletsw-figlink">'
                . get_avatar( $user->ID, $sz ) . '</span></button></form></td>
            <td class="pdtsw-second">' . esc_html($user->display_name) . ' <small><em class="maybehidden">(' 
                . esc_html($user->user_login) . ')</em><span style="color:green"> ' 
                . esc_html($user->last_name) . ', ' . esc_html($user->first_name) 
                . '</span></small></td>
            <td class="pdtsw-third"><a href="'  . esc_url($user->user_url) . '" 
                title="'  . esc_html($user->user_url) . '" target="_blank">' 
                . esc_html($user->user_url) . '</a></td>
            <td class="pdtsw-fourth">' . mb_substr($user->user_registered, 0, -8) . '</td>
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
        <td class="pdtsw-last"><span class="' . esc_attr( $hoverclass ) . '">'  
                . esc_html($user->ID) . '</span></td>
        </tr>';
        
        } // ends foreach USER LOOP
        
        } else {
            echo '<tr><td colspan=7>' . esc_html__( 'No profiles found. Try refreshing permalinks or adding new user levels to the view.', 'profile-details-tsw' ) . '</td></tr>';
    }
    else: 

        echo '<tr><td colspan=7><h4>' . esc_html__( 'Table viewable only to administrative persons.', 'profile-details-tsw' ) . '</h4></td></tr>';
    
    endif;

        echo '</tbody></table><div class="tswclearfix"></div>

                <footer class="pdtsw-navtext">
                    <p>' . esc_html( 'pg. ', 'profile-details-tsw') . $page 
                . esc_html( ' of ', 'profile-details-tsw') . $total_pages 
                . esc_html( ' pgs.', 'profile-details-tsw') 
                . '</p><div class="tswclearfix"></div>
                    <nav class="profile-details-pagination">';  

            if ( $page > 1 ) {
            echo '<a href="'. add_query_arg(array('paged' => $page-1)) .'" title="-">'
                    . esc_html__( 'Previous Page', 'profile-details-tsw') . '</a>';
            }
            // Next page
            if ( $page < $total_pages ) {
            echo '<a href="'. add_query_arg(array('paged' => $page+1)) .'" title="+">'
                    . esc_html__( 'Next Page', 'profile-details-tsw') . '</a>';
            }
            echo '</nav>
                </footer>
        </div>
            <div class="profiletsw-after-content">'

            . wp_kses_post( wpautop( wptexturize( 
                 profile_details_tsw_after_block() ) ) )

            . '</div>
        </section>';

        $output = ob_get_clean();
        
            return $output;
}


/** PD3
 * Dropdown sortable query for GRID VIEW
 *
 * @since 1.0.31
 * @return HTML 
 */
function profile_details_tsw_gridsortform_dropdown()
{
    
    $sort_by  = isset( $_POST['pdtsw_gridsortform_dropdown'] ) 
                ? sanitize_text_field($_POST['pdtsw_gridsortform_dropdown'])
                : sanitize_text_field('user_registered');
    $order_is = isset( $_POST['pdtsw_gridsortform_order'] ) 
                ? sanitize_text_field($_POST['pdtsw_gridsortform_order'])
                : sanitize_text_field('ASC');      
    $args         = array(
        'user_registered' => __('Registered Date'),
        'display_name'   => __('Display Name'),
        'last_name'    => __('Last Name'),
        'first_name'  => __('First Name'),
    );
    
    $html = 
    '<form id="pdtsw-gridsortform" method="POST" 
        action="'.htmlspecialchars($_SERVER["REQUEST_URI"]).'/#content">
        <table class="pdtsw-table"><tbody>
    <tr>
    <td> <label for="pdtsw-gridsortform-dropdown">'. __('Sort by ', 'profile-details-tsw');
    $html .= '<select id="pdtsw-gridsortform-dropdown" 
             name="pdtsw_gridsortform_dropdown" 
             class="pdtsw-select" onchange="this.form.submit()">';

    foreach( $args as $key => $value ) {
	$html .= 
    '<option value="'. esc_attr($key) .'" '. selected($key, $sort_by, false) . '>' 
	      . esc_html( $value ) . '</option>'; 
	}

	$html .= '</select></label></td>
    <td> <label for="pdtsw_gridsortform_order">'. __('Ascending ', 'profile-details-tsw');
    $html .= '<input type="radio" value="ASC" name="pdtsw_gridsortform_order" 
                ' . checked($order_is, 'ASC', false) . ' onchange="this.form.submit()"></label>
    <label for="pdtsw_gridsortform_order">' . __('Descending ', 'profile-details-tsw');
    $html .= '<input type="radio" value="DESC" name="pdtsw_gridsortform_order" 
                ' . checked($order_is, 'DESC', false) . ' onchange="this.form.submit()"></label>
    
    </td>
    <td><input type="hidden" value="'. wp_create_nonce('pdtsw_gridfrm_nonce') .'" 
               name="nonce"></td>
    </tr></tbody></table></form>';
    
        echo $html;     
}

/**
 * Profile Details TSW Public GRID SCREEN VIEW
 * 
 * @param string | int $user The ID of the user being displayed.
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

    // Avatar size in grid view TODO: option?
    $sz             = absint(52);  
    $contact_privi  = profile_details_tsw_contact_privi();
    $contact_selctd = profile_details_tsw_contact_selected();
    $hoverclass     = profile_details_tsw_contact_display();
    $profile_url    = profile_details_tsw_get_author_page(); 
    $viewlink       = profile_details_tsw_viewlink();
    $cellclass      = profile_details_tsw_contact_html();
    $ppp            = ( empty ( profile_details_gettsw_pagination() ) ) ? 12 
                              : profile_details_gettsw_pagination();
    
    ob_start();
    echo 
    '<section class="profiletsw-section-grid">
        <div id="content" class="profile-details-grid">

            <div class="profiletsw-before-content">'
            . wp_kses_post( wpautop( wptexturize( 
                profile_details_tsw_before_block() ) ) ) 
            . '</div>';
         echo 
            '<div class="pdtsw-sortform">';

            echo do_action('pdtsw_gridsortform_dropdown');
        
            echo '</div>';
        
  
   
    if ($_SERVER["REQUEST_METHOD"] == "POST"):
        if (isset( $_REQUEST['nonce'] ) 
        && !wp_verify_nonce( $_REQUEST['nonce'], "pdtsw_gridfrm_nonce")) {
            exit("No funny business please");
        }
    endif;

    // only one post will be set
    $order_by = ( isset( $_POST['pdtsw_gridsortform_dropdown'] ) ) 
              ? sanitize_text_field( $_POST['pdtsw_gridsortform_dropdown'] ) 
              : 'user_registered';
    $order_is = ( isset( $_POST['pdtsw_gridsortform_order'] ) ) 
              ? sanitize_text_field( $_POST['pdtsw_gridsortform_order'] ) 
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
            'number'  => $ppp,
            'offset' => $offset
            );
    
        // Create the WP_User_Query object to fetch total users without 'number' and 'offset' arguments
        $total_users_query = new WP_User_Query($args);
        $total_users = $total_users_query->total_users;

        // Create the WP_User_Query object again with updated arguments
        $wp_user_query = new WP_User_Query($args);

        // Get the results
        $total_query = $wp_user_query->total_users;
        $total_pages = intval($total_users / $ppp) + 1;

        // Use the following with foreach() loop to display results as per your requirements
        $user_query = $wp_user_query->get_results();

           
        echo '<div class="profile-details-list-container">
                <ul class="profiletsw-grid">';
    
        if ( ! empty( $user_query ) ) { 
        // start foreach            
        foreach ( $user_query as $user ) { 
         
        echo 
        '<li><article class="profiletsw-entry-grid">
        <div class="pdtsw-avatar-grid">
        <form action="' . esc_url( $profile_url ) .'" method="POST">
            <input type="hidden" name="profile_id" value="'. absint($user->ID) .'">
            <button type="submit" id="'. sanitize_title_with_dashes($user->display_name) .'" 
            class="tiny-submit" title="'. esc_attr($user->display_name) .'">
            <figure class="profiletsw-figlink" title="' . esc_attr( profile_details_tsw_thead( absint(1) ) ) . '">'
            . get_avatar( $user->ID, $sz ) . '</figure></button>
        </form>
            <header class="pdtsw-title-grid">
            <span role="heading" aria-level="2" class="screen-reader-text" 
            title="' .  esc_attr( profile_details_tsw_thead( absint(2) ) ) . ' ' 
            . esc_html($user->display_name) . '"></span>
            <h6 class="pdtsw-grid-title">
<a href="' . esc_url( $profile_url .'?display_name='. $user->display_name .'&profile_id='. $user->ID ) . '" 
            title="' . esc_attr__( 'visit profile for', 'profile-details-tsw') . ' ' 
            . esc_attr( $user->display_name ) . '" class="pdtsw-viewlink">' 
            . esc_html($user->display_name) . '</a></h6>
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
            $descript = wpautop( get_the_author_meta( 'description', $user->ID ) );
            echo substr( $descript, 0, 115 ) . '...'; 
        echo 
        '</div>
        <div class="pdtsw-url-grid pdtsw-eqh">
            <h6>' . esc_attr( profile_details_tsw_thead(absint(5) ) ) . '</h6>
            <p><a class="pdtsw-grid-url" href="' . esc_url($user->user_url) . '" 
            title="'. esc_attr($user->user_url) . '" target="_blank">
            ' . esc_url($user->user_url) . '</a></p>
        </div>
        <div class="pdtsw-regdate-grid pdtsw-eqh">
            <h6>' . esc_attr( profile_details_tsw_thead(absint(6) ) ) . '</h6>
            <p>' . mb_substr($user->user_registered, 0, -8) . '</p>
        </div>';
        
        if  ( ! $contact_privi ): 
        echo 
        '<div class="' . esc_attr( $cellclass ) . '">
            <div class="pdtsw-contact-grid pdtsw-eqh">
                <h6>' . esc_attr( profile_details_tsw_thead(absint(7)) ) . '</h6>
                <p class="' . esc_attr( $hoverclass ) . '"><em class="profiletsw-priv-grid">
                <span title="' . esc_attr($user->$contact_selctd) . '">'
                . esc_html($user->$contact_selctd) . '</span></em></p>
            </div>
        </div>';
        endif;

        echo 
        '</article></li>';

        } // ends foreach
        wp_reset_query();
            echo '</ul>
            </div>

                <footer class="pdtsw-navtext">
                    <p>' . esc_html( 'pg. ', 'profile-details-tsw') . $paged 
                . esc_html( ' of ', 'profile-details-tsw') . $total_pages 
                . esc_html( ' pgs.', 'profile-details-tsw') 
                . '</p><div class="tswclearfix"></div>
                    <nav class="profile-details-pagination">'; 

                if ($total_users >= $total_query) {
                    echo '<div id="pagination" class="clearfix um-members-pagi">';
                        echo '<span class="pages">Pages:</span>';
                        $current_page = max(1, get_query_var('paged'));

                        echo paginate_links(array(
                        'base'      => get_pagenum_link(1) . '%_%',
                        'format'    => 'page/%#%/',
                        'current'   => $current_page,
                        'total'     => $total_pages,
                        'prev_next' => true,
                        'show_all'  => true,
                        'type'      => 'plain',
                        ));
                }
    echo '</div>';


            echo '</nav>
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
        
            echo $output;
} 
