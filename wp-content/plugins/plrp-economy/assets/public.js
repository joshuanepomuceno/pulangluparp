( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		var containers = document.querySelectorAll( '.plrp-price-list' );

		containers.forEach( function ( container ) {
			var tabs = container.querySelectorAll( '.plrp-tab' );
			var groups = container.querySelectorAll( '.plrp-profession-group' );
			var search = container.querySelector( '.plrp-search' );

			tabs.forEach( function ( tab ) {
				tab.addEventListener( 'click', function () {
					tabs.forEach( function ( t ) { t.classList.remove( 'is-active' ); } );
					tab.classList.add( 'is-active' );

					var target = tab.getAttribute( 'data-target' );
					groups.forEach( function ( group ) {
						if ( 'all' === target || group.getAttribute( 'data-profession' ) === target ) {
							group.style.display = '';
						} else {
							group.style.display = 'none';
						}
					} );
				} );
			} );

			if ( search ) {
				search.addEventListener( 'input', function () {
					var term = search.value.trim().toLowerCase();
					container.querySelectorAll( 'tbody tr[data-search]' ).forEach( function ( row ) {
						var haystack = row.getAttribute( 'data-search' ) || '';
						row.style.display = ( '' === term || haystack.indexOf( term ) !== -1 ) ? '' : 'none';
					} );
				} );
			}
		} );
	} );
} )();
