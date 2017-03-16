jQuery(document).ready(function(jQuery) {
	jQuery(document).on('postbox-toggled',function(element){
		jQuery(".chosen-container").css('width','75%');
		jQuery(".chosen-container").css('max-width','100%');
	});
	
	var font_fields = {"body-font-family" : "body-font-weight",
	"form-font-family" : "form-font-weight",
	"site-title-font-family" : "site-title-font-weight",
	"site-description-font-family" : "site-description-font-weight",
	"headline-font-family" : "headline-font-weight",
	"headline-subhead-font-family" : "headline-subhead-font-weight",
	"nav-menu-font-family" : "nav-menu-font-weight",
	"subnav-menu-font-family" : "subnav-menu-font-weight",
	"byline-font-family" : "byline-font-weight",
	"sidebar-font-family" : "sidebar-font-weight",
	"sidebar-heading-font-family" : "sidebar-heading-font-weight",
	"footer-widgets-font-family" : "footer-widgets-font-weight",
	"footer-widgets-heading-font-family" : "footer-widgets-heading-font-weight",
	"footer-font-family" : "footer-font-weight"};
	
	if(typeof lander !== 'undefined' && lander.lander_fonts){
		jQuery.each(font_fields,function(key, value){
			jQuery("."+key).chosen();
			jQuery("."+key).on('change', function(evt, params) {
					selected_font = params.selected;
					lander_fonts = JSON.parse(lander.lander_fonts);

					//alert(typeof(lander_fonts));
					//alert(lander_fonts[selected_font].font_weights);
					options = get_font_variant_html(lander_fonts[selected_font].font_weights);
					jQuery("."+value).empty().append(weightoptions)
				});
		});
	}
	
	jQuery('#lander_google_fonts .gfonts-api-key').mouseover(function() {
		
		if(jQuery('#lander_google_fonts .gfonts-api-key').val().length != 0) {
			jQuery('#lander_google_fonts .gfonts-api-key').removeClass('validated').addClass('validating');

			jQuery.ajax({
				method: 'GET',
				cache: false,
				url: 'https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key='+jQuery('#lander_google_fonts .gfonts-api-key').val(),
				dataType: 'json',
				success: function(data){
					jQuery('#lander_google_fonts .gfonts-api-key').removeClass('validating valid invalid empty').addClass('validated valid');
				},
				error: function(xhr, type, exception) { 
					// if ajax fails display error alert
					//alert("Oops... ajax error response type "+type);
					jQuery('#lander_google_fonts .gfonts-api-key').removeClass('validating valid invalid empty').addClass('validated invalid');
				}
			});
		}
		else {
			jQuery('#lander_google_fonts .gfonts-api-key').addClass('empty');
		}
		
	});	
			
	// 'use strict';
	jQuery('.lander-color-selector','.gl-section').each(function (i) {
		jQuery(this).after('<div class="lander-color-picker" id="picker-' + i + '" style="position:absolute;z-index:9999;right:50px;"></div>');
		jQuery('#picker-' + i).hide().farbtastic(jQuery(this));
	})
	.focus(function() {
		jQuery(this).next().show();
	})
	.blur(function() {
		jQuery(this).next().hide();	
	});
	
	// Check for undefined as this script is not enqueued localized on evey page
	if(typeof lander !== 'undefined' && lander.firstTime){
		jQuery('div.postbox').addClass('closed');
		postboxes.save_state(pagenow);
	}
	
	// Add toggle button functionality.
	jQuery('<input id="toggle-meta-boxes" type="button" class="button button-secondary lexpand" value="Toggle All" />"') 
		.appendTo(jQuery('.top-buttons'))
		.click(function() {
			
			if(jQuery('#toggle-meta-boxes').hasClass('lexpand')){					
				jQuery('#toggle-meta-boxes').removeClass('lexpand');
				jQuery('#toggle-meta-boxes').addClass('lcollapse');					
				jQuery('div.postbox').removeClass('closed');
				}
			else{
				if(jQuery('#toggle-meta-boxes').hasClass('lcollapse')){
					jQuery('#toggle-meta-boxes').removeClass('lcollapse');
					jQuery('#toggle-meta-boxes').addClass('lexpand');
					jQuery('div.postbox').addClass('closed');
				}
			}
			
			
			jQuery('#lander_extras_help_box').removeClass('closed');				
			jQuery('#lander_design_help_box').removeClass('closed');
			
			postboxes.save_state(pagenow);
			jQuery.event.trigger('postbox-toggled');
			return false;
		});		

	jQuery( "#tip-one-col" ).css('visibility','hidden');
	jQuery( "#tip-two-col" ).css('visibility','hidden');
	jQuery( "#tip-three-col" ).css('visibility','hidden');

	// Change spacing: trigger tips for two col and three col
	jQuery( ".col-spacing" ).change(function() {
		var col_spacing = parseInt(jQuery( ".col-spacing" ).val());
		var column_content_1col = parseInt(jQuery( ".column-content-1col" ).val());
		var tip_two = column_content_1col - col_spacing;
		var tip_three = column_content_1col - (2 * col_spacing);
		jQuery( "#tip-two-col .ans" ).text(tip_two);
		jQuery( "#tip-three-col .ans" ).text(tip_three);
		jQuery( "#tip-two-col" ).css('visibility','visible');
		jQuery( "#tip-three-col" ).css('visibility','visible');
	});
	
	// Change single column content: trigger tips for two col and three col
	jQuery( ".column-content-1col" ).change(function() {
		var col_spacing = parseInt(jQuery( ".col-spacing" ).val());
		var column_content_1col = parseInt(jQuery( ".column-content-1col" ).val());
		var tip_two = column_content_1col - col_spacing;			
		var tip_three = column_content_1col - (2 * col_spacing);
		jQuery( "#tip-two-col .ans" ).text(tip_two);
		jQuery( "#tip-three-col .ans" ).text(tip_three);
		jQuery( "#tip-one-col" ).css('visibility','hidden');
		jQuery( "#tip-two-col" ).css('visibility','visible');
		jQuery( "#tip-three-col" ).css('visibility','visible');
	});
	
	// Change two col content: trigger tips for single col and three col
	jQuery( ".column-content-2col" ).change(function() {
		var col_spacing = parseInt(jQuery( ".col-spacing" ).val());
		var column_content_2col = parseInt(jQuery( ".column-content-2col" ).val());
		var sidebar_one_2col = parseInt(jQuery( ".sidebar-one-2col" ).val());
		var tip_one = column_content_2col +  col_spacing + sidebar_one_2col;			
		var tip_three = column_content_2col + sidebar_one_2col - (col_spacing);
		jQuery( "#tip-one-col .ans" ).text(tip_one);
		jQuery( "#tip-three-col .ans" ).text(tip_three);
		jQuery( "#tip-one-col" ).css('visibility','visible');
		jQuery( "#tip-two-col" ).css('visibility','hidden');
		jQuery( "#tip-three-col" ).css('visibility','visible');
	});
	
	// Change two col sb1: trigger tips for single col and three col
	jQuery( ".sidebar-one-2col" ).change(function() {
		var col_spacing = parseInt(jQuery( ".col-spacing" ).val());
		var column_content_2col = parseInt(jQuery( ".column-content-2col" ).val());
		var sidebar_one_2col = parseInt(jQuery( ".sidebar-one-2col" ).val());
		var tip_one = column_content_2col +  col_spacing + sidebar_one_2col;			
		var tip_three = column_content_2col + sidebar_one_2col - (col_spacing);
		jQuery( "#tip-one-col .ans" ).text(tip_one);
		jQuery( "#tip-three-col .ans" ).text(tip_three);
		jQuery( "#tip-one-col" ).css('visibility','visible');
		jQuery( "#tip-two-col" ).css('visibility','hidden');
		jQuery( "#tip-three-col" ).css('visibility','visible');
	});
	
	// Change three col content: trigger tips for single col and two col
	jQuery( ".column-content-3col" ).change(function() {
		var col_spacing = parseInt(jQuery( ".col-spacing" ).val());
		var column_content_3col = parseInt(jQuery( ".column-content-3col" ).val());
		var sidebar_one_3col = parseInt(jQuery( ".sidebar-one-3col" ).val());
		var sidebar_two_3col = parseInt(jQuery( ".sidebar-two-3col" ).val());			
		var tip_one = column_content_3col + sidebar_one_3col + sidebar_two_3col + (2*col_spacing);			
		var tip_two = column_content_3col + sidebar_one_3col + sidebar_two_3col + col_spacing; 			
		jQuery( "#tip-one-col .ans" ).text(tip_one);
		jQuery( "#tip-two-col .ans" ).text(tip_two);
		jQuery( "#tip-one-col" ).css('visibility','visible');
		jQuery( "#tip-two-col" ).css('visibility','visible');
		jQuery( "#tip-three-col" ).css('visibility','hidden');
	});
	
	// Change three col sb1: trigger tips for single col and two col
	jQuery( ".sidebar-one-3col" ).change(function() {
		var col_spacing = parseInt(jQuery( ".col-spacing" ).val());
		var column_content_3col = parseInt(jQuery( ".column-content-3col" ).val());
		var sidebar_one_3col = parseInt(jQuery( ".sidebar-one-3col" ).val());
		var sidebar_two_3col = parseInt(jQuery( ".sidebar-two-3col" ).val());			
		var tip_one = column_content_3col + sidebar_one_3col + sidebar_two_3col + (2*col_spacing);			
		var tip_two = column_content_3col + sidebar_one_3col + sidebar_two_3col + col_spacing; 			
		jQuery( "#tip-one-col .ans" ).text(tip_one);
		jQuery( "#tip-two-col .ans" ).text(tip_two);
		jQuery( "#tip-one-col" ).css('visibility','visible');
		jQuery( "#tip-two-col" ).css('visibility','visible');
		jQuery( "#tip-three-col" ).css('visibility','hidden');
	});
	
	// Change three col sb2: trigger tips for single col and two col
	jQuery( ".sidebar-two-3col" ).change(function() {
		var col_spacing = parseInt(jQuery( ".col-spacing" ).val());
		var column_content_3col = parseInt(jQuery( ".column-content-3col" ).val());
		var sidebar_one_3col = parseInt(jQuery( ".sidebar-one-3col" ).val());
		var sidebar_two_3col = parseInt(jQuery( ".sidebar-two-3col" ).val());			
		var tip_one = column_content_3col + sidebar_one_3col + sidebar_two_3col + (2*col_spacing);			
		var tip_two = column_content_3col + sidebar_one_3col + sidebar_two_3col + col_spacing; 			
		jQuery( "#tip-one-col .ans" ).text(tip_one);
		jQuery( "#tip-two-col .ans" ).text(tip_two);
		jQuery( "#tip-one-col" ).css('visibility','visible');
		jQuery( "#tip-two-col" ).css('visibility','visible');
		jQuery( "#tip-three-col" ).css('visibility','hidden');
	});

	// Toggle mobile template meta box options
	if( jQuery( "#mobile_use_global" ).is( ':checked' ) ) {
		jQuery( "#mobile-global-lp" ).css( "display", "none" );
	}
	else {
		jQuery( "#mobile-global-lp" ).css( "display", "block" );
	}
	
	jQuery( "#mobile_use_global" ).change(function() {
		var ischecked = this.checked;
		if(ischecked) {
			jQuery( "#mobile-global-lp" ).css( "display", "none" );
		}
		else{
			jQuery( "#mobile-global-lp" ).css( "display", "block" );
		}
	});
	
	function get_font_variant_html(weights) {				
		weightoptions = '';
		if(weights === 'undefined'){
			weightoptions += "<option value=\""+normal+"\">"+normal+"</option>";
		}
		else {
			jQuery.each(weights,function(key, value){
				weightoptions += "<option value=\""+value+"\">"+value+"</option>";
			})
		}
		
		return weightoptions;
	}
	
	jQuery('.lander-lp-layout input:checked').each( function() {
		jQuery('.lander-lp-layout input[name="' + jQuery(this).attr('name') + '"]:checked').parent('label').addClass('active');
	});
	
	// Append classes to Lander page / post layout style selector markup
	
	jQuery('.lander-lp-layout input').click(function() {
		jQuery('.lander-lp-layout input[name="' + jQuery(this).attr('name') + '"]').parent('label').removeClass('active');
		jQuery(this).parent('label').addClass('active');
	});
	
});


