jQuery(function(){
	
	jQuery("#usuario").focus(function() {
		
		jQuery(this).parent(".input-prepend").addClass("input-prepend-focus");
	
	});
	
	jQuery("#usuario").focusout(function() {
		
		jQuery(this).parent(".input-prepend").removeClass("input-prepend-focus");
	
	});
	
	jQuery("#senha").focus(function() {
		
		jQuery(this).parent(".input-prepend").addClass("input-prepend-focus");
	
	});
	
	jQuery("#senha").focusout(function() {
		
		jQuery(this).parent(".input-prepend").removeClass("input-prepend-focus");
	
	});
	
});