jQuery(function(){
	
	jQuery('#cep').focusout(function(){
        
        if(jQuery(this).val() != "_____-___") {
		
			jQuery.ajax({
	            url: baseUrl+'/cep/index/cep/'+jQuery(this).val(),
	            dataType: 'html',
	            type: 'get',
	
	            beforeSend: function() {
	                addMascara();                   
	            },
	            complete: function() {
	                removeMascara();        
	            },
	            success: function(data) {               
	                	            		
	        		var aData = data.split('+');
	        		
	        		jQuery('#endereco').val(unescape(aData[0]));
	        		jQuery('#bairro').val(unescape(aData[1]));
	        		jQuery('#cidade').val(unescape(aData[2]));
	        		jQuery('#estado').val(unescape(aData[3]));
	        		
	        		jQuery('#numero').focus();
	            	
	            },
	            error: function(xhr, er) {
	                alert('Error ' + xhr.status + ' - ' + xhr.statusText);
	            }
	        });
		
        }
        
	}); 
})