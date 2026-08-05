( function ( $ ) {
	'use strict';

	function updateLinePrice( $row ) {
		var $select = $row.find( 'select[name="ingredient_material[]"]' );
		var price = $select.find( 'option:selected' ).data( 'price' );
		$row.find( '.plrp-line-price' ).text( price ? parseFloat( price ).toFixed( 4 ) : '' );
	}

	$( document ).on( 'change', 'select[name="ingredient_material[]"]', function () {
		updateLinePrice( $( this ).closest( 'tr' ) );
	} );

	$( document ).on( 'click', '#plrp-add-ingredient', function () {
		var $body = $( '#plrp-ingredients-body' );
		var $last = $body.find( '.plrp-ingredient-row' ).last();
		var $clone = $last.length ? $last.clone() : null;

		if ( $clone ) {
			$clone.find( 'select' ).val( '' );
			$clone.find( 'input[type="number"]' ).val( '' );
			$clone.find( '.plrp-line-price' ).text( '' );
			$body.append( $clone );
		}
	} );

	$( document ).on( 'click', '.plrp-remove-ingredient', function () {
		var $body = $( '#plrp-ingredients-body' );
		if ( $body.find( '.plrp-ingredient-row' ).length > 1 ) {
			$( this ).closest( 'tr' ).remove();
		} else {
			// Keep at least one row - just clear it.
			var $row = $( this ).closest( 'tr' );
			$row.find( 'select' ).val( '' );
			$row.find( 'input[type="number"]' ).val( '' );
			$row.find( '.plrp-line-price' ).text( '' );
		}
	} );
} )( jQuery );
