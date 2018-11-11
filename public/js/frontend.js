(function( $ ) {
	'use strict';

	$( document ).ready( function(){
		$( '#tip_facturare' ).change( function(){
			var val = $( this ).val();

			if ( 'pers-jur' == val ) {
				$( '.show_if_pers_jur' ).show();
				$( '.show_if_pers_fiz' ).hide();
			}else{
				$( '.show_if_pers_jur' ).hide();
				$( '.show_if_pers_fiz' ).show();
			}
		});
	});
	

})( jQuery );
