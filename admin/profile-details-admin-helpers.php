<?php //wp_kses($string, $allowed_html, $allowed_protocols);
// https://wp-punk.com/best-guide-to-security-output-for-wordpress/
// may need wp_kses( apply_filters( 'shapeSpace_allowed_html', $default ), $allowed_tags );

/**
 * Creates a escaping function to allowed certain HTML for settings form content.
 * Needed for when echoing the innerblock HTML.
 *
 * @param array An array of HTML elements allowed.
 */
function profile_details_admin_allowed_html($context) {

	$allowed_tags = array(
		'a' => array(
            'id'    => true,
			'class' => true,
			'href'  => true,
			'rel'   => true,
			'title' => true,
            'target' => true,
		),
		'code' => true,
		'del' => array(
			'datetime' => true,
			'title' => true,
		),
		'img' => array(
			'alt'    => true,
			'class'  => true,
			'height' => true,
			'src'    => true,
			'width'  => true,
		),
        /* for admin side */
        'form' => array(
            'id'    => true,
            'action'         => true,
	        'accept'         => true,
            'accept-charset' => true,
            'enctype'        => true,
            'method'         => true,
            'name'           => true,
            'target'         => true,
        ),

        'input'    => array(
            'id'    => true,
            'value'    => true,
            'class'    => true,
            'type'     => true,
            'disabled' => true,
        ),
        'textarea' => array(
            'id'    => true,
            'class'    => true,
            'readonly' => true,
            'rows'     => true,
        ),
         'select' => array(
            'id'    => true,
            'name'  => true,
            'value' => true,
         ),
         'option' => array(
            'id'    => true,
            'name'  => true,
            'value' => true,
         ),
        
	);
	return $allowed_tags; 
    //apply_filters( 'wp_kses_allowed_html', $allowed_tags, $context ) 
	
}
/*
		'attrributes' => array(
				'accept' => true,
        'align' => true,
        'alt' => true,
        'aria-describedby' => true,
        'autocomplete' => true,
        'autofocus' => true,
        'checked' => true,
        'class' => true,
        'col' => true,
        'dirname' => true,
        'disabled' => true,
        'for' => true,
        'form' => true,
        'formaction' => true,
        'formenctype' => true,
        'formmethod' => true,
        'formnovalidate' => true,
        'formtarget' => true,
        'height' => true,
        'label' => true,
        'list' => true,
        'max' => true,
        'maxlength' => true,
        'min' => true,
        'multiple' => true,
        'name' => true,
        'pattern' => true,
        'placeholder' => true,
        'readonly' => true,
        'required' => true,
        'row' => true,
        'selected' => true,
        'size' => true,
        'src' => true,
        'step' => true,
        'type' => true,
        'value' => true,
        'width' => true,
        'wrap' => true,
			 ),
		);
        */ 