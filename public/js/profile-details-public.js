(function( $ ) {
	'use strict';

	/**
	 * https://stackoverflow.com/questions/24764662/wordpress-custom-query-function-ajax-callback-returns-0
	 */
	$(document).ready(function($) {
	//wrapper
    	$('#pdtsw-sortform-dropdown').on('change', function() {
			
			//use in callback 
			var selectd = $(this).val();
			$('#pdtswResult').text(selectd);
			//var pdtsw_post_id = $('input[name=pdtsw_post_id]').val();
			//POST request
		
		});
 
		
	});
})( jQuery );