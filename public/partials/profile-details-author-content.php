<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin Shortcodes.
 *
 * @since      1.0.5
 * @link       https://tradesouthwest.com
 * @package    Profile_Details
 * @subpackage Profile_Details/public/partials/profile-details-author-content
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Rendering for Author page
 *
 * @since  1.0.5
 * @uses   shortcode [profile_details_profile]
 * @param  string $meta       Gets author meta using custom function of this plugin
 * @param  string $profile_id ID of author gotten from url $_GET method
 * @return HTML               Ignore tab spacing on page to preserve space.
 */

function profile_details_tsw_render_author_page($att='', $content=null) 
{
    if( ! is_page() || !function_exists('pdtswget_author_meta') ) 
        return;
    if( $_SERVER["REQUEST_METHOD"] == "POST" ) :
        $submitted_value = esc_attr( wp_unslash( sanitize_key( 
            $_REQUEST['pdtsw_author_nonce'] ) ) );

        if( !wp_verify_nonce( esc_attr( $submitted_value ), 'pdtsw_author_nonce' )){ 
            exit("No security nonce found. Line 30"); 
        }
    endif;

    $profile_id  = sanitize_text_field( wp_unslash( $_REQUEST['profile_id'] ) );
    $disply_name = pdtswget_author_meta('display_name', absint( $profile_id ) );
    $profile_url = profile_details_tsw_get_author_page(); 
    $contact_selctd = profile_details_tsw_contact_selected();
    
    ob_start();
    
    echo 
    '<div class="profiletsw-section-author">
        <section class="profiletsw-entry-author">';
        echo '<div class="pdtsw-title-row pdtsw_row">
            <h2>' . esc_html($disply_name) . '</h2>
        </div>
        
        <div class="pdtsw-avatar-row pdtsw_row">
            <figure title="'. esc_attr( pdtswget_author_meta('display_name', $profile_id) ).'" 
            class="profiletsw-fig">' 
            . get_avatar( $profile_id, '150' ) . '</figure>
        </div>

        <div class="pdtsw-details-row pdtsw_row">
            <p><strong role="heading" aria-level="4">' 
            . esc_html( profile_details_tsw_thead(absint(3)) ) . ':</strong> ';

            $profile_terms = wp_get_object_terms( absint( $profile_id ), 'profile_details' );
            
            if ( !empty( $profile_terms ) ) {
                foreach( $profile_terms as $term ) { 
                    echo esc_html( $term->name );
                }
            } else {
                echo esc_html__( 'Not Specified.', 'profile-details-tsw' );
            }
        echo '</p>
        </div>

        <div class="profiletsw-dscr-author pdtsw_row">'
            . wp_kses_data( wpautop( 
                pdtswget_author_meta( 'description', absint( $profile_id ) ) ) ) 
            . '</span>
        </div>

        <div class="pdtsw-url-row pdtsw_row">
            <p><strong role="heading" aria-level="4">' 
            . esc_html( profile_details_tsw_thead(absint(5)) ) . ':</strong> 
            <a href="' . esc_url( pdtswget_author_meta( 'user_url', absint($profile_id) )).'" 
                title="' . esc_attr__( 'visit website', 'profile-details-tsw' ) 
                . ' ' . esc_url( pdtswget_author_meta( 'user_url', absint($profile_id) ) ). '" 
                target="_blank">' 
                . esc_url( pdtswget_author_meta( 'user_url', absint($profile_id) )).'</a></p>    
        </div>
        
        <div class="pdtsw-contact-row pdtsw_row">';

        if  ( profile_details_tsw_contact_privi() == 'off') {
        echo '<p><strong role="heading" aria-level="4">' 
            . esc_html( profile_details_tsw_thead(absint(7)) ) . ':</strong>
            <em class="profiletsw-priv-grid ' . esc_attr( profile_details_tsw_contact_display() ) . '" 
            title="' . esc_attr( profile_details_tsw_contact_selected() ) . '">' 
            . esc_html( pdtswget_author_meta(
                profile_details_tsw_contact_selected(), absint($profile_id) ) 
            ) . '</em></p>';
        }
        echo '</div>

        <div class="pdtsw-date-row pdtsw_row">
        <p><strong role="heading" aria-level="4">' 
        . esc_html( profile_details_tsw_thead(absint(6)) ) 
        . ': </strong><date>' . esc_html( mb_substr( 
                                        pdtswget_author_meta( 'user_registered', 
                                            absint( $profile_id ) ), 
                                        0, -8 )) . '</date></p>
        </div>';
            
        if( profile_details_tsw_extend_info() == "on" ): 
        echo '<aside class="profiletsw-section-extend pdtsw_row">
            <p><strong role="heading" aria-level="4">' . esc_html__( 'Company', 'profile-details-tsw' ) . ':</strong> ' 
            . esc_attr( get_the_author_meta( 'profile_details_tsw_company', absint($profile_id) ) ) .'</p>
            <p><strong role="heading" aria-level="4">' . esc_html__( 'Position', 'profile-details-tsw' ) . ':</strong> ' 
            . esc_attr( get_the_author_meta( 'profile_details_tsw_position', absint($profile_id) ) ) .'</p>
            <p><strong role="heading" aria-level="4">' . esc_html__( 'Location', 'profile-details-tsw' ) . ':</strong> ' 
            . esc_attr( get_the_author_meta( 'profile_details_tsw_location', absint($profile_id) ) ) .'</p>
            </aside>';
        endif;
        echo '<div class="pdtsw-contact-row pdtsw_row">
            <p><strong role="heading" aria-level="4">' 
            . esc_html( profile_details_tsw_thead(absint(7)) ) 
            . ': </strong> 
            <a href="mailto:'. esc_attr(pdtswget_author_meta( $contact_selctd, absint($profile_id)  )) .'" 
            class="' . esc_attr( profile_details_tsw_contact_display() ) . '" 
            title="'. esc_attr(pdtswget_author_meta( $contact_selctd, absint($profile_id)  )) .'">'
            . esc_html( pdtswget_author_meta( $contact_selctd, absint($profile_id)  ) ) . '</a></p>
            </div>';
        
        echo 
        '</section>

        <footer class="pdtsw-social pdtsw_row">'; 
            
        // Display Social==true && anchors = on
        if ( !profile_details_tsw_social_urls() == true ): 
        if ( profile_details_tsw_social_anchor() == 'on'): 
        echo '<div class="pdtsw-slinks">
            <p><strong>'. esc_html__('Media Links', 'profile-details-tsw') .'</strong></p>
            <ul class="pdtsw-social-single">
            <li>' . esc_url( pdtswget_author_meta('pdtsw_twitter_profile', absint($profile_id) )) . '</li>
            <li>' . esc_url( pdtswget_author_meta('pdtsw_linkedin_profile', absint($profile_id) )) . '</li>
            <li>' . esc_url( pdtswget_author_meta('pdtsw_facebook_profile', absint($profile_id) )) . '</li>
            <li>' . esc_url( pdtswget_author_meta('pdtsw_google_business', absint($profile_id) )) . '</li>
            <li>' . esc_url( pdtswget_author_meta('pdtsw_rss_url', absint($profile_id) )) . '</li>
            </ul>
            </div>';
        else: 
        echo '<div class="pdtsw-slinks media-author pdtsw_row">
            <p><strong>'. esc_html__( 'External Media Links', 'profile-details-tsw' ) .'</strong></p>
            <ul class="pdtsw-social-single">';
            if('' != pdtswget_author_meta( 'pdtsw_twitter_profile', absint($profile_id ) ) ) { 
            echo
            '<li><a href="' . esc_url( pdtswget_author_meta( 'pdtsw_twitter_profile', absint($profile_id ) )) . '" 
                title="" class="blue-anchor">' . esc_url( pdtswget_author_meta( 'pdtsw_twitter_profile', absint($profile_id) )) . ' 
                <span class="exsvg" title="'. esc_attr__( 'off site link', 'profile-details-tsw' ) . '"><em></em></span></a></li>'; 
            }
            if('' != pdtswget_author_meta( 'pdtsw_linkedin_profile', absint($profile_id) ) ) { 
            echo
            '<li><a href="' . esc_url( pdtswget_author_meta( 'pdtsw_linkedin_profile', absint($profile_id) )) . '" 
                title="" class="blue-anchor">' . esc_url( pdtswget_author_meta( 'pdtsw_linkedin_profile', absint($profile_id) )) . '
                <span class="exsvg" title="'. esc_attr__( 'off site link', 'profile-details-tsw' ) . '"><em></em></span></a> 
            </li>'; 
            }
            if('' != pdtswget_author_meta( 'pdtsw_facebook_profile', absint($profile_id) ) ) { 
            echo
            '<li><a href="' . esc_url( pdtswget_author_meta( 'pdtsw_facebook_profile', absint($profile_id) )) . '" 
                title="" class="blue-anchor">' . esc_url( pdtswget_author_meta( 'pdtsw_facebook_profile', absint($profile_id) )) . ' 
                <span class="exsvg" title="'. esc_attr__( 'off site link', 'profile-details-tsw' ) . '"><em></em></span></a> 
            </li>'; }
            if('' != pdtswget_author_meta( 'pdtsw_google_business', absint($profile_id) ) ) { 
            echo
            '<li><a href="' . esc_url( pdtswget_author_meta( 'pdtsw_google_business', absint($profile_id) )) . '" 
                title="" class="blue-anchor">' . esc_url( pdtswget_author_meta( 'pdtsw_google_business', absint($profile_id) )) . ' 
                <span class="exsvg" title="'. esc_attr__( 'off site link', 'profile-details-tsw' ) . '"><em></em></span></a> 
            </li>'; 
            }
            if('' != pdtswget_author_meta( 'pdtsw_rss_url', absint($profile_id) ) ) { 
            echo
            '<li><a href="' . esc_url( pdtswget_author_meta( 'pdtsw_rss_url', absint($profile_id) )) . '" 
                title="" class="blue-anchor">' . esc_url( pdtswget_author_meta( 'pdtsw_rss_url', absint($profile_id) )) . '
                <span class="exsvg" title="'. esc_attr__( 'off site link', 'profile-details-tsw' ) . '"><em></em></span></a> 
            </li>'; 
            }
            if ( pdtswset_debug_mode() === true ) {

            echo 
            '<li><p> <small>' . esc_html__( 'Copy paste share link: ', 'profile-details-tsw' ) . '</small>' 
                . esc_html__( 'Visit profile for', 'profile-details-tsw' ) . '<em>' 
                . esc_url( $profile_url . '?display_name='. esc_attr($disply_name) . '&profile_id='. esc_attr($profile_id ) ) 
                . ' </em></p>
            </li>';

            }
        echo    '</ul>
            </div>';
        endif;
        endif;

        echo '</footer>
    </div>';

        $htm = ob_get_clean();
        
            echo wp_kses_post( force_balance_tags( $htm ) );
}
