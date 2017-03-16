jQuery(document).ready(function() {
	
	/* Collasible divs */
	jQuery('.collapsible-heading.open').addClass('opened');
	jQuery('.collapsible-content.open').css('display','block');
		
	jQuery('.collapsible-heading').on('click',function(event){
		var cc = jQuery(this).next();
		// If the title is clicked and the collapsible content is not currently animated,
		// start an animation with the slideToggle() method.				
		jQuery('.collapsible-heading.opened').next().slideToggle();	//show/hide  collapsible content inside collapsible-heading.opened
		jQuery('.collapsible-heading.opened').toggleClass('opened');	//remove class opened

		if(!cc.is(':animated')){
			cc.slideToggle();
			jQuery(this).toggleClass('opened');	
		}
	});
	
});	