<?php
/**
 * Register widget with WordPress.
 */
class Profiledetails_Widget extends WP_Widget {

	function __construct() {
    parent::__construct(
    // Base ID of widget
    'Profiledetails_Widget',

    // Widget name will appear in UI
    __( 'ProfileDetails Categories', 'profile-details-tsw' ), // Name
	array(
        'description' => __( 'Adds Widget for profile-details-tsw Plugin',
                            'profile-details-tsw' ),
        ));
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		
        if ( ! empty( $title ) )
		
        echo $args['before_title'] . $title . $args['after_title'];

        echo '<div class="profiletsw-widget-container">';
       
        $tax = get_taxonomy( 'profile_details' );
        /* Get the terms of the 'profile_detail' taxonomy. */
        $user_terms = get_terms( 'profile_details', 
                            array( 
						 	    'hide_empty' => false 
						    	) 
							); 
		$catpg = profile_details_tsw_get_category_page();
	    if ( ! is_wp_error( $user_terms ) ) {
	    
        echo '<ul class="unstyled-list">';
		foreach( $user_terms as $term ) {
			
            echo '<li><a href="'. esc_url( $catpg .'?profile_sortform_catview='. $term->slug) .'" 
                    title="'. esc_attr(get_term_link($term->slug, 'profile_details')) . '"  
					class="pdtsw-link">' 
				. esc_html( $term->name ) . '</a></li>'; 
        }
            echo '</ul>';
        } 
		
        // return after widget parts
        echo '</div>';
}
	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
// Widget Backend
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Categories', 'pdtsw' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'pdtsw' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php
}
	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
	return $instance;
	}
} // Ends class pdtsw_Widget

// Register and load the widget
	function pdtsw_load_widget() {
		register_widget( 'Profiledetails_Widget' );
}

add_action( 'widgets_init', 'pdtsw_load_widget' );
?>
