(function($){
	
	jQuery(document).ready(function(){

		jQuery( "body" ).on( "change", "#gees_invoice_add_field", function() { 
			if(jQuery('#gees_invoice_add').attr('checked')) {
				jQuery("#gees_custom_checkout_field_hide").show();
			} else {
				jQuery("#gees_custom_checkout_field_hide").hide();
			}
								
		});
		
		jQuery( "body" ).on( "change", "#gees_invoice_add", function() { 
			if(jQuery('#gees_invoice_add').attr('checked')) {
				jQuery("#gees_custom_checkout_field_hide").show();
			} else {
				jQuery("#gees_custom_checkout_field_hide").hide();
			}
								
		});
	});			
	
})(jQuery);