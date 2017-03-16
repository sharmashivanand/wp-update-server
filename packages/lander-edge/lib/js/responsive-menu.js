( function( window, $, undefined ) {
	'use strict';

	$( '.nav-primary').before( '<button class="menu-toggle menu-toggle-primary" role="button" aria-pressed="false"></button>' ); // Add toggles to menus
	
	$( '.nav-secondary' ).before( '<button class="menu-toggle menu-toggle-secondary" role="button" aria-pressed="false"></button>' ); // Add toggles to menus
	
	$( '.nav-primary .sub-menu, .nav-secondary .sub-menu' ).before( '<button class="sub-menu-toggle" role="button" aria-pressed="false"></button>' ); // Add toggles to sub menus

	// Show/hide the navigation
	$( '.menu-toggle, .sub-menu-toggle' ).on( 'click', function() {
		var $this = $( this );
		$this.attr( 'aria-pressed', function( index, value ) {
			return 'false' === value ? 'true' : 'false';
		});

		$this.toggleClass( 'activated' );
		$this.next( '.nav-primary, .nav-secondary, .sub-menu' ).slideToggle( 'fast' );

	});

})( this, jQuery );