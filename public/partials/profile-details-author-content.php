<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin Shortcodes.
 *
 * @link       https://tradesouthwest.com
 * @since      1.0.0
 * @version    1.0.32
 * @package    Profile_Details
 * @subpackage Profile_Details/public/partials
 */
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Rendering for Author page
 *
 * @uses   shortcode [profile_details_profile]
 * @since  1.0.02
 * @uses   shortcode on any page
 * @param  string $meta       Gets author meta using custom function of this plugin
 * @param  string $profile_id ID of author gotten from url $_GET method
 * @return HTML
 */

function profile_details_tsw_render_author_page($att='', $content=null) 
{
    
    if( ! is_page() || !function_exists('pdtswget_author_meta') ) 
        return;
     
    $profile_id     = $_REQUEST['profile_id'];

    // load all custom strings
    require_once plugin_dir_path( __FILE__ ).'/helpers/profile-details-author-values.php';
    
    $default_avatar = get_avatar( $profile_id, $sz );
    $disply_name    = pdtswget_author_meta('display_name', $profile_id);
    $profile_url    = profile_details_tsw_get_author_page(); 
    ob_start();
    
    echo '<div class="profiletsw-section-author" style="background: #fafafa;">
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

                $profile_terms = wp_get_object_terms( $profile_id, 'profile_details' );
                
                if ( !empty( $profile_terms ) ) {
                    foreach( $profile_terms as $term ) { 
                        echo esc_html( $term->name );
                    }
                } else {
                    echo esc_html__( 'Not Specified.', 'profile-details-tsw' );
                }
            echo '</p>
            <div class="profiletsw-dscr-author pdtsw_row">'
                . wpautop( pdtswget_author_meta( 'description', $profile_id ) ) . '</div>
            </div>

            <div class="pdtsw-url-row pdtsw_row">
                <p><strong role="heading" aria-level="4">' 
                . esc_html( profile_details_tsw_thead(absint(5)) ) . ':</strong> 
                <a href="' . pdtswget_author_meta('user_url', $profile_id) . '" 
                   title="' . __('visit website', 'profile-details-tsw') 
                   . ' ' . pdtswget_author_meta('user_url', $profile_id) . '" 
                   target="_blank">' . pdtswget_author_meta('user_url', $profile_id) . '</a></p>    
            </div>
            
            <div class="pdtsw-contact-row pdtsw_row">';

            if  ( $contact_privi == 'off') {
            echo '<p><strong role="heading" aria-level="4">' 
                . esc_html( profile_details_tsw_thead(absint(7)) ) . ':</strong>
                <em class="profiletsw-priv-grid ' . esc_attr( $hoverclass ) . '" 
                    title="' . esc_attr($contact_selctd) . '">' 
                    . pdtswget_author_meta($contact_selctd, $profile_id) . '</em></p>';
            }
            echo '</div>
            
            <div class="pdtsw-date-row pdtsw_row">
            <p><strong role="heading" aria-level="4">' 
            . esc_html( profile_details_tsw_thead(absint(6)) ) 
            . ':</strong> ' . mb_substr(pdtswget_author_meta('user_registered', 
                                                            $profile_id), 0, -8) . '</p>
            </div>';
            
            if( $extend_info == "on" ): 
            echo '<aside class="profiletsw-section-extend pdtsw_row">
                <p><strong role="heading" aria-level="4">' . esc_html__( 'Company', 'profile-details-tsw' ) . ':</strong> ' 
                . esc_attr( get_the_author_meta( 'profile_details_tsw_company', $profile_id ) ) .'</p>
                <p><strong role="heading" aria-level="4">' . esc_html__( 'Position', 'profile-details-tsw' ) . ':</strong> ' 
                . esc_attr( get_the_author_meta( 'profile_details_tsw_position', $profile_id ) ) .'</p>
                <p><strong role="heading" aria-level="4">' . esc_html__( 'Location', 'profile-details-tsw' ) . ':</strong> ' 
                . esc_attr( get_the_author_meta( 'profile_details_tsw_location', $profile_id ) ) .'</p>
                </aside>';
            endif;
            echo '<div class="pdtsw-contact-row pdtsw_row">
                <p><strong role="heading" aria-level="4">' 
                . esc_html( profile_details_tsw_thead(absint(7)) ) 
                . ': </strong> 
                <a href="mailto:'. esc_attr(pdtswget_author_meta( $contact_selctd, $profile_id )) .'" 
                class="' . esc_attr( $hoverclass ) . '" 
                title="'. esc_attr(pdtswget_author_meta( $contact_selctd, $profile_id )) .'">'
                . esc_html( pdtswget_author_meta( $contact_selctd, $profile_id ) ) . '</a></p>
                </div>';
        
        echo '</section>

                <footer class="pdtsw-social pdtsw_row">'; 
            
            // Display Social==true
            if ( !$social_urls == true ): 
            if ( $social_anchor == 'on'): 
        echo '<div class="pdtsw-slinks">
                <p><strong>'. esc_html__('Media Links', 'profile-details-tsw') .'</strong></p>
                <ul class="pdtsw-social-single">
                <li>' . pdtswget_author_meta('pdtsw_twitter_profile', $profile_id) . '</li>
                <li>' . pdtswget_author_meta('pdtsw_linkedin_profile', $profile_id) . '</li>
                <li>' . pdtswget_author_meta('pdtsw_facebook_profile', $profile_id) . '</li>
                <li>' . pdtswget_author_meta('pdtsw_google_business', $profile_id) . '</li>
                <li>' . pdtswget_author_meta('pdtsw_rss_url', $profile_id) . '</li>
                </ul>
                </div>';
            else:
        echo '<div class="pdtsw-slinks media-author pdtsw_row">
                <p><strong>'. esc_html__('External Media Links', 'profile-details-tsw') .'</strong></p>
                <ul class="pdtsw-social-single">';
                        if('' != pdtswget_author_meta('pdtsw_twitter_profile', $profile_id) ) { 
                echo
                '<li><a href="' . pdtswget_author_meta('pdtsw_twitter_profile', $profile_id) . '" 
                    title="" class="blue-anchor">' . pdtswget_author_meta('pdtsw_twitter_profile', $profile_id) . '</a> 
                    <span class="exsvg" title="'. esc_attr__('off site link', 'profile-details-tsw') . '">' . $exsvg . '</span></li>'; }
                        if('' != pdtswget_author_meta('pdtsw_linkedin_profile', $profile_id) ) { 
                echo
                '<li><a href="' . pdtswget_author_meta('pdtsw_linkedin_profile', $profile_id) . '" 
                    title="" class="blue-anchor">' . pdtswget_author_meta('pdtsw_linkedin_profile', $profile_id) . '</a> 
                    <span class="exsvg" title="'. esc_attr__('off site link', 'profile-details-tsw') . '">' . $exsvg . '</span></li>'; }
                        if('' != pdtswget_author_meta('pdtsw_facebook_profile', $profile_id) ) { 
                echo
                '<li><a href="' . pdtswget_author_meta('pdtsw_facebook_profile', $profile_id) . '" 
                    title="" class="blue-anchor">' . pdtswget_author_meta('pdtsw_facebook_profile', $profile_id) . '</a> 
                    <span class="exsvg" title="'. esc_attr__('off site link', 'profile-details-tsw') . '">' . $exsvg . '</span></li>'; }
                        if('' != pdtswget_author_meta('pdtsw_google_business', $profile_id) ) { 
                echo
                '<li><a href="' . pdtswget_author_meta('pdtsw_google_business', $profile_id) . '" 
                    title="" class="blue-anchor">' . pdtswget_author_meta('pdtsw_google_business', $profile_id) . '</a> 
                    <span class="exsvg" title="'. esc_attr__('off site link', 'profile-details-tsw') . '">' . $exsvg . '</span></li>'; }
                        if('' != pdtswget_author_meta('pdtsw_rss_url', $profile_id) ) { 
                echo
                '<li><a href="' . pdtswget_author_meta('pdtsw_rss_url', $profile_id) . '" 
                    title="" class="blue-anchor">' . pdtswget_author_meta('pdtsw_rss_url', $profile_id) . '</a> 
                    <span class="exsvg" title="'. esc_attr__('off site link', 'profile-details-tsw') . '">' . $exsvg . '</span></li>'; 
                        }
                        if ( pdtswset_debug_mode() === true ) {
                echo 
                '<li><p> <small>' . esc_html__('Copy paste share link: ', 'profile-details-tsw') . '</small>' 
                    . esc_html__( 'Visit profile for', 'profile-details-tsw') . '<em>' 
                    . esc_url( $profile_url . '?display_name='. esc_attr($disply_name) . '&profile_id='. esc_attr($profile_id ) ) 
                    . ' </em></p></li>'; 
                        }
                
        echo    '</ul>
            </div>';
            endif;
            endif;

        echo '</footer>
        </div>';

        $htm = ob_get_clean();
        
            echo $htm;
}
