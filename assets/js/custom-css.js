 // AIO Custom Simple CSS's on/off toggle JS file
 
 ( function( $ ) {
	wp.customize( 'aio_custom_css', function( value ) {
		value.bind( function( newval ) {
			if ( ! $( '#aio_css_output' ).length ) {
				$( '<style id="aio_css_output"></style>' ).appendTo( 'head' );
			}

			$( '#aio_css_output' ).text( newval );
		} );
	} );
} )( jQuery );