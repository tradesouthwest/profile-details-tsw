<?php 
/** C1
 * Dropdown sortable query for table view
 *
 * @since 1.0.5
 * @subpackage public/partials/profile-details-category
 * @return HTML 
 */

function profile_details_tsw_sortform_dropdown_categories()
{
    $cattosort = ''; 
    $orderis    = 'ASC';
    $mediator    = profile_details_tsw_pdtsw_mediator();
    $user         = wp_get_current_user();
    $allowed_roles = array('editor', 'administrator', $mediator);

       if( $_SERVER["REQUEST_METHOD"] == "POST" ) :
        // uses pdtsw_catsort_nonce
        $submitted_value = esc_attr( 
            wp_unslash( sanitize_key( $_REQUEST['pdtsw_catsort_nonce'] ) ) );

        if ( !wp_verify_nonce( esc_attr( $submitted_value ), 
            'pdtsw_catsort_nonce' ) ) { 
            exit("No funny business please. Line 17"); 
        }
           
    $cattosort    = ( isset( $_POST['profile_sortform_catview'] ) ) 
                  ? sanitize_title_with_dashes( wp_unslash( 
                            $_POST['profile_sortform_catview'] ) ) 
                  : ''; 
    $orderis      = ( isset( $_POST['pdtsw_catsortform_order'] ) )
                  ? sanitize_text_field( wp_unslash( 
                            $_POST['pdtsw_catsortform_order'] ) ) 
                  : 'ASC';
    endif;

    $categor_url  = profile_details_tsw_get_tableview_page(); 
    $gridview_url = profile_details_tsw_get_gridview_page();  
    
    $taxonomies = get_terms( array(
        'taxonomy'   => 'profile_details',
        'hide_empty' => false
    ) );
    
    if ( !empty($taxonomies) ) :

    $html = '<table class="pdtsw-table"><tbody>
    <tr>
        <td>
        <form id="pdtsw-sortform-cats" method="POST" 
        action="'. wp_unslash( sanitize_key( esc_url_raw( $_SERVER["REQUEST_URI"] ) ) ) . '">'; 
    $html .= '<label for="pdtsw-sortform-catview">' . esc_html__('Sort by ', 'profile-details-tsw');
    $html .= '<select id="pdtsw-sortform-catview" name="profile_sortform_catview" 
              class="pdtsw-select" onchange="this.form.submit()">
        <option value="">'. esc_html__('Select to Order By', 'profile-details-tsw') .'</option>';

    ob_start();
        foreach( $taxonomies as $category ) {
            echo '<option value="'. esc_attr( $category->slug ) .'" 
             '. selected($category->slug, $cattosort, false) . '>'
             . esc_html( $category->name ) .'</option>';     
        }
    $html .= 
    ob_get_clean();

    $html .= '</select></label>
        </td>
        <td>
        <label for="pdtsw_catsortform_order">' . esc_html__('Order Ascending ', 'profile-details-tsw');
    $html .= '<input type="radio" value="ASC" name="pdtsw_catsortform_order" 
             ' . checked($orderis, 'ASC', false) .' onchange="this.form.submit()"></label>
        <label for="pdtsw_catsortform_order">' . esc_html__('Descending ', 'profile-details-tsw');
    $html .= '<input type="radio" value="DESC" name="pdtsw_catsortform_order" 
             ' . checked($orderis, 'DESC', false) .' onchange="this.form.submit()"></label>
        <input type="hidden" value="' . wp_create_nonce('pdtsw_catsort_nonce') .'" 
            name="pdtsw_catsort_nonce">
        </form>
        </td>
        <td>';
        if( array_intersect($allowed_roles, $user->roles ) ) {

    $html .= '<a href="'. esc_url( $categor_url ) .'" title="'. esc_attr__('Back to Table View','profile-details-tsw').'" 
        class="pdtsw-button">' . esc_html__('Back to Table View','profile-details-tsw') .'</a>';
        } else {
    $html .= '<a href="'. esc_url( $gridview_url ) .'" title="'. esc_attr__('Back to Profiles View','profile-details-tsw').'" 
        class="pdtsw-button">' . esc_html__('Back to Profiles View','profile-details-tsw') .'</a>';
        }
    $html .= '</td>
    </tr>
    </tbody></table>';

    echo $html;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

    endif;
}

/**
 * Profile Details TSW Category Taxonomy Table Layout Screen
 * 
 * @param string $option The ID of the option being displayed.
 * @uses shortcode [profile_details_category] Or =cat name.
 * @since 1.0.1
 * @see https://developer.wordpress.org/reference/functions/get_terms/
 *
 * @param string $profile_terms Gets terms assoc/w/ given object(s), in given taxonomies.
 */

function profile_details_tsw_shortcode_category($atts, $content = null)
{
    extract( shortcode_atts( array(       // phpcs:ignore WordPress.PHP.DontExtract.extract_extract
        'id' => null,
        'taxonomy' => 'profile_details',
        'term'   => 'all'
    ), $atts ) );
    // keeps query clean
    $users = ''; 
    $users = null;
    // values
    $viewable       = profile_details_tsw_table_private();
    $contact_privi  = profile_details_tsw_contact_privi();
    $contact_selctd = profile_details_tsw_contact_selected();
    $hoverclass     = profile_details_tsw_contact_display();
    $profile_url    = profile_details_tsw_get_author_page();  
    $sz             = "32";  // Avatar size in table view
    $order_by        = 'last_name'; 
    //$order_is        = 'ASC';
    $vieu           = (empty ( get_option('profile_details_tsw')['profile_details_tsw_viewlink']))
                    ? 'view ' : get_option('profile_details_tsw')['profile_details_tsw_viewlink'];
    
    ob_start();
    echo 
    '<section class="profiletsw-section">
            <div class="profiletsw-before-content">'
            
            .  wp_kses_post( force_balance_tags( profile_details_tsw_before_block() ) ) 

            . '</div>';
    echo '<div class="pdtsw-sortform">';
        /* @subpackage includes/profile-details-requires C1 */
        do_action('pdtsw_dropdown_category_children');
    
    echo '</div>';
    echo 
    '<div id="content">
    <table id="pdtswTableView" class="profiletsw-table">
        <thead class="profiletsw-thead">
        <tr>
            <th>' . esc_html( profile_details_tsw_thead(absint(1)) )  .'</th>
            <th>' . esc_html( profile_details_tsw_thead(absint(2)) ) . '</th>
            <th>' . esc_html( profile_details_tsw_thead(absint(5)) ) . '</th>
            <th>' . esc_html( profile_details_tsw_thead(absint(6)) ) . '</th>
            <th>' . esc_html( profile_details_tsw_thead(absint(7)) ) . '</th>
            <th>' . esc_html( profile_details_tsw_thead(absint(3)) ) . '</th>
            <th>' . esc_html__('Id#', 'profile-details-tsw' ) . '</th>
        </tr>
        </thead>
            <tbody class="pdtsw_sortable">';
    
    if(isset( $_REQUEST['profile_sortform_catview'] ) ) :  
        $verify = wp_verify_nonce( wp_unslash( sanitize_key( 
            $_REQUEST['pdtsw_catsort_nonce'] ) ), 'pdtsw_catsort_nonce' ); 
        if ( !$verify ) { exit("Nonce not found for category. Line 160"); }
    endif;
    $cattosort  = ( isset( $_REQUEST['profile_sortform_catview'] ) && 
                   !empty( $_REQUEST['profile_sortform_catview'] ) ) 
                ? sanitize_text_field( wp_unslash( $_REQUEST['profile_sortform_catview'] ) )
                : 'none';
    $order_is   = ( isset( $_POST['pdtsw_catsortform_order'] ) ) 
                ? sanitize_text_field( wp_unslash( $_POST['pdtsw_catsortform_order'] ) ) 
                : 'ASC';
    // WP_User_Query arguments
    $args = array (
        'orderby' => esc_attr($order_by),    
        'order'   => esc_attr($order_is),
    );
    // The User Query
    $user_query = new WP_User_Query( $args );
    $users      = $user_query->results;
    
    echo 
    '<tr><td colspan="4">'. esc_html( $cattosort ) .'</td><td colspan="3">
    </td></tr>';

    // User Loop
    if ( ! empty( $users ) ) { 
        
        foreach ( $users as $user ) {

            $term_list = wp_get_object_terms( absint( $user->ID ), 'profile_details' );
            
            if ( ! empty( $term_list ) && ! is_wp_error( $term_list ) ) {
                $terms_string = join(', ', wp_list_pluck($term_list, 'name'));
            } else { 
                $terms_string = ''; 
            }
            //only loop if in cat
        if( sanitize_title_with_dashes($terms_string) 
                == sanitize_title_with_dashes($cattosort) ) {
        echo '<tr class="profiletsw-tr">
        <td class="pdtsw-first">
            <form action="' . esc_url( $profile_url ) .'" method="POST">
            <input type="hidden" name="profile_id" 
                value="'. absint($user->ID) .'">
            <button type="submit" 
                id="'. esc_attr($user->display_name) .'" 
                class="tiny-submit" 
                title="' . esc_attr($user->display_name) . '">
            <figure class="profiletsw-figlink" 
                title="' . esc_attr( $vieu . ' ' . $user->display_name) . '">'
                . get_avatar( $user->ID, $sz ) . '</figure></button>
            <input type="hidden" 
                value="'. esc_attr( wp_create_nonce( 'pdtsw_author_nonce' )) .'" 
                name="pdtsw_author_nonce">
            </form></td>
        <td class="pdtsw-second">' . esc_html($user->display_name) . ' <small>(' 
            . esc_html($user->user_login) . ')<span style="color:green"> ' 
            . esc_html($user->last_name) . ', ' . esc_html($user->first_name) 
            . '</span></small></td>
        <td class="pdtsw-third"><a href="'  . esc_url($user->user_url) . '" 
            title="'  . esc_html($user->user_url) . '" target="_blank">' 
            . esc_html($user->user_url) . '</a></td>
        <td class="pdtsw-fourth">' . esc_attr( mb_substr($user->user_registered, 0, -8) ) . '</td>
        <td class="pdtsw-fifth"><span class="profiletsw-priv">'; 
            echo '<a href="" class="' . esc_attr( $hoverclass ) . '" 
            title="'. esc_attr($user->$contact_selctd) .'">'
            . esc_html($user->$contact_selctd) . '</a></span></td>
        <td class="pdtsw-sixth profile-details-terms">';

        $profile_terms = wp_get_object_terms( $user->ID,  'profile_details' );
        
        if ( !empty( $profile_terms ) ) {
            foreach ( $profile_terms as $term ) { 
                echo '<span class="pdtsw-item">' . esc_html( $term->name ) . ' </span>';
            }
            } else {
                echo esc_html__( 'Not Specified.', 'profile-details-tsw' );
        }
        echo '</td>
            <td class="pdtsw-last">'  . esc_html($user->ID) . '</td>
        </tr>';
            } // ends filter in cats
        }    //ends foreach
        
        $users = $terms_string = ''; $users = null;    
    } else { 
        echo '<tr><td colspan=7>' . esc_html__( 'Nothing found', 'profile-details-tsw' ) 
        . '</td></tr>';     
    }
    
    echo 
    '<tr>
        <td colspan="4">'. esc_html( $cattosort ).'</td>
        <td colspan="3"><a href="#content" title="'. esc_attr__('Back to top', 'profile-details-tsw') .'">' 
                           . esc_html__('Back to top', 'profile-details-tsw') .'</a></td>
    </tr></tbody></table>
    </div>';

    echo '<div class="profiletsw-after-content">';
            
    echo  wp_kses_post( force_balance_tags( profile_details_tsw_after_block() ) );

    echo '</div>
    </section>';

        $output = ob_get_clean();
        
            return $output;
}
