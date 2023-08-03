# profile-details
Creates easy to view user profile details.


# Notes
<!-- escaping server requests
https://stackoverflow.com/questions/69049402/how-to-parenthesized-the-following-php-line
$items .= '<input type="hidden" name="redirect" value="' . esc_url( ( isset( $_SERVER['HTTP_REFERER'] ) ) ? esc_url_raw( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) : (isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '') ) . '">';

--> 
