jQuery(function(){
	
    // Mascaras
    addMascara = function() {
        jQuery('<div class="ui-widget-overlay" id="mascara"></div>')
        .css({
            width: jQuery(window).width(),
            height: jQuery(window).height(),
            position: 'fixed'
        })
        .insertAfter('#wrap');
		
        jQuery('<img src="'+baseUrl+'/images/ajax-loader.gif" id="ajax-loader" alt="Carregando...">')
        .css({
            position: 'fixed',
            left:(jQuery(window).width()/2 - jQuery('#ajax-loader').width() / 2),
            top:(jQuery(window).height()/2 - jQuery('#ajax-loader').height() / 2)
        })
        .insertAfter('.ui-widget-overlay');
    };
    removeMascara = function() {
        jQuery('#mascara, #ajax-loader').remove();
    };
    /////////////////////////////
	
    // Dialog para excluir item
    jQuery('.confirma').click(function(e) {
        e.preventDefault();
        //var textoTitulo = jQuery(this).parent().prevAll('td.titulo').text();
        //jQuery('#dialogs').text(textoTitulo);
        var link = jQuery(this).attr("href");		
        jQuery('#dialogs').dialog({
            title	: jQuery(this).attr('rel'),
            buttons	: {
                "Sim": function() {
                    location = link;
                },
                "Não": function() {
                    jQuery(this).dialog("close");
                }
				
            }
        });
    });
    ///////////////////////////
		
    // Função para adicionar folha de css
    jQuery.getCSS = function( url, media ) {
        jQuery( document.createElement('link') ).attr({
            href: url,
            media: media || 'screen',
            type: 'text/css',
            rel: 'stylesheet'
        }).appendTo('head');
    };
    //////////////////////////
	
  		
    // Hover nos link de img
    jQuery('.img-link a').hover(
        function() {
            jQuery('span', this).addClass('ui-state-hover');
        }, 
        function() {
            jQuery('span', this).removeClass('ui-state-hover');
        }
        );
          
    var oTable = jQuery('#registros').dataTable( {
        
        "sPaginationType": "bootstrap", 
        "oLanguage": {
            "sProcessing":   "Processando...",
            "sLengthMenu":   "Mostrar _MENU_ registros",
            "sZeroRecords":  "Não foram encontrados resultados",
            "sInfo":         "Mostrando de _START_ até _END_ de _TOTAL_ registros",
            "sInfoEmpty":    "Mostrando de 0 até 0 de 0 registros",
            "sInfoFiltered": "(filtrado de _MAX_ registros no total)",
            "sInfoPostFix":  "",
            "sSearch":       "Buscar:",
            "sUrl":          "",
            "oPaginate": {
                "sFirst":    "Primeiro",
                "sPrevious": "Anterior",
                "sNext":     "Seguinte",
                "sLast":     "Último"
            }
        }
    } );
        
    
    jQuery('#telefone_celular').focusout(function(){
        var phone, element;
        element = jQuery(this);
        element.unmask();
        phone = element.val().replace(/\D/g, '');
        if(phone.length > 10) {
            element.mask("(99) 99999-999?9");
        } else {
            element.mask("(99) 9999-9999?9");
        }
    }).trigger('focusout');
    
    jQuery(".customSelect").chosen();
    jQuery('.btn-tooltip').tooltip();
    
    jQuery('.info').on('click', function(e) {
        
        e.preventDefault();

        jQuery.ajax({
            url: baseUrl+jQuery(this).attr('rel'),
            dataType: 'html',
            type: 'get',

            beforeSend: function() {
                addMascara();                   
            },
            complete: function() {
                removeMascara();        
            },
            success: function(data) {               
            	jQuery('.modal-header h3').html('Informações');
            	jQuery('.modal-body p').html(data);
            	jQuery('#modal-base').modal({
                    keyboard: false
                }).css({
                    width: '750px',
                    'margin-left': function () {
                    return -(jQuery(this).width() / 2);
                    }           
                })
                
                $('.data').datepicker({
            		format:'dd/mm/yyyy',
            		viewMode:0,
            		language: 'pt-BR'
            	});
            },
            error: function(xhr, er) {
                alert('Error ' + xhr.status + ' - ' + xhr.statusText);
            }
        });      
    }); 

    $('.data').datepicker({
		format:'dd/mm/yyyy',
		viewMode:0,
		language: 'pt-BR'
	});
    
    CKFinder.setupCKEditor( null, { basePath : baseUrl + '/admin/js/plugins/ckfinder/'} );
       

    
    /*
     * Calculo Valor Boleto Clube
     */
    
    jQuery('#valor_gerado').on('focus', function(e) {
    	
    	if( jQuery('#fator').val() !='' && jQuery('#socios').val() !='' ) {
    	
    		var $total = jQuery('#fator').val() * jQuery('#socios').val();
    		jQuery('#valor_gerado').val($total);
    	}	
    });	
    
})