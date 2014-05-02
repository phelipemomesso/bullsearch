jQuery(function(){

    var oTable1 = $('#registros').dataTable();

    $( ".btn-tooltip" ).tooltip();

    $( ".step" ).on( "click", function( e ) {

        e.preventDefault();
        
        var $rel = $(this).attr('rel');
        var $value = $(this).attr('value');

        var $steps = $rel.split('+');

        jQuery.ajax({

            url: baseUrl+'/index/'+$steps[0],
            dataType: 'html',
            type: 'POST',
            data: {

                value : $value,
                label : $steps[2]
            },

            success: function(data) {               

                location.href=baseUrl+'/index/'+$steps[1];
            },
            
            error: function(xhr, er) {
                alert('Error ' + xhr.status + ' - ' + xhr.statusText);
            }
        }); 

    });



    $( "#step5" ).on( "click", function( e ) {

        e.preventDefault();
        
        var $goals = new Array();
        var $check = new Array();   

        $('.checkbox-goals').each(function() {

            $goals.push($(this).attr('id'));

            if($(this).is(':checked'))
                $check.push('1');
            else
                $check.push('0');
        });

        jQuery.ajax({
            url: baseUrl+'/index/goals',
            data: { 

                goals: $goals,
                checks: $check     
            },
            type: 'post',

            success: function(data) {               

                location.href=baseUrl+'/index/step5';              
            },
            error: function(xhr, er) {
                alert('Error ' + xhr.status + ' - ' + xhr.statusText);
            }
        });

    });

    $('.checkbox-goals').on( "click", function( e ) {

        var $check = new Array();

        $('.checkbox-goals').each(function() {

            if($(this).is(':checked'))
                $check.push('1');
        });

        if ($check.length > 3) {

            alert('You can select only three items.');
            $(this).attr("checked",false);
        };
    
    });

    $( "#go" ).on( "click", function( e ) {

        e.preventDefault();

        jQuery.ajax({
            url: baseUrl+'/index/step5',
            data: { 

                records: $('#records').val()
            },
            type: 'post',

            success: function(data) {               

                location.href=baseUrl+'/result';              
            },
            error: function(xhr, er) {
                alert('Error ' + xhr.status + ' - ' + xhr.statusText);
            }
        });

    });

    $( "#records-result" ).on( "change", function( e ) {

        e.preventDefault();

        jQuery.ajax({
            url: baseUrl+'/index/step5',
            data: { 

                records: $('#records-result').val()
            },
            type: 'post',

            success: function(data) {               

                location.href=baseUrl+'/result/index/results/'+$('#records-result').val();              
            },
            error: function(xhr, er) {
                alert('Error ' + xhr.status + ' - ' + xhr.statusText);
            }
        });

    });

    


    $('#btn-country').click(function (e) {

        e.preventDefault()

        jQuery.ajax({
            url: baseUrl+'/index/cookie',
            dataType: 'html',
            type: 'post',
            data: {
              country : $('#country').val()
        },

            success: function(data) {               

                location.href=baseUrl+'/index';
            },
            error: function(xhr, er) {
                alert('Error ' + xhr.status + ' - ' + xhr.statusText);
            }
        });      
    })


    $('#btn-login').on("click",function(e){

        e.preventDefault();

        if ($('#usuario').val() == '') {
            
            alert('Enter a E-mail.');
            $('#usuario').focus();
        
        } else if ($('#senha').val() == '') {

             alert('Enter a Password.');
             $('#senha').focus();

        } else {

            var $remember = 0;

            if($('#remember').is(':checked')) 
                $remember = 1;

            $.ajax({
                type: "POST",
                url: baseUrl+'/register/login',
                data: {
                  usuario : $('#usuario').val(),
                  senha : $('#senha').val(),
                  remember : $remember
                }
            })
              .done(function( data ) {
                
                if (data == 1) {

                    location.href=baseUrl+'/';
                    
                } else if (data == 2) {

                    $('#msg-login').css("display","block");
                    $('#msg-login').html('User unknown, please register');

                } else if (data == 3) {

                    $('#msg-login').css("display","block");
                    $('#msg-login').html('Incorrect password, please re-enter your password or click on the "Forgot Password" to retrieve your password');
                }

              })
              .fail(function( data ) {
                
                $('#msg-login').css("display","block");
                $('#msg-login').html(data);
              })
            
        }
    });

    $('.update-register').on("click",function(e){

        e.preventDefault();

        $.ajax({
            type: "POST",
            url: baseUrl+'/register/checkemail',
            data: {
              email : $('#email').val(),
          }
        })
        .done(function( data ) {

            if (data == 1) {

                $('#msg-login').css("display","block");
                $('#msg-login').html('This email <b>'+$('#email').val()+'</b> address already exists!');

            } else if (data == 2) {

                $( "#form-register-edit" ).submit();

            }

        })
        .fail(function( data ) {

            $('#msg-login').css("display","block");
            $('#msg-login').html(data);
        })

    });        

    $('#loginface').on("click",function(e){

        e.preventDefault();
          
        FB.getLoginStatus(function(response) {
        
            if (response.status !== 'connected' && response.status !== 'not_authorized') {

                FB.login(function(response) {
                    
                    if (response.authResponse) {

                        var $responseAuth = response.authResponse;

                        FB.api('/me', function(response) {
                            
                            var $responseMe = response;  

                            jQuery.ajax({
                                
                                url: baseUrl+'/register/loginface',
                                dataType: 'html',
                                type: 'post',
                                data: {
                                    responseAuth : $responseAuth,
                                    responseMe : $responseMe
                                },

                                success: function(data) {               

                                    location.href=baseUrl+'/index';

                                    console.log(data);
                                },
                                error: function(xhr, er) {
                                    alert('Error ' + xhr.status + ' - ' + xhr.statusText);
                                }
                            }); 

                        });
                    
                    } else {
                      // The person cancelled the login dialog
                    }
                },{scope: 'email'});
           }
        })
    })

    $('#logout-face').on("click",function(e){

        e.preventDefault();

        FB.getLoginStatus(function(response) {
        
            if (response.status === 'connected') {

                FB.logout(function(response) {
                    location.href=baseUrl+'/register/logout';
                });
            } else {
                location.href=baseUrl+'/register/logout';  
            }
        });      

    })

    $('#modalPortfolio').on('hidden.bs.modal', function () {
        $(this).removeData('bs.modal');
    });


    $('#btn-compare').on( "click", function( e ) {
        
        var $bulls = new Array();
        
        $('.checkbox-compare').each(function() {

            if($(this).is(':checked')) {
                $bulls.push($(this).attr('id'));
            }
        });

        console.log($bulls.length);

        if ($bulls.length <= 1) {

            alert('You must select at least two bulls to use this feature.');

        } else if ($bulls.length > 3) {

            alert('You may only compare up to three bulls. ');

        } else if ($bulls.length >= 2 && $bulls.length <= 3) {   

            jQuery.ajax({

                url: baseUrl+'/result/checkbox',
                data: { 

                    bulls: $bulls
                },
                type: 'post',

                success: function(data) {               

                    
                        location.href=baseUrl+'/result/compare';

                },
                
                error: function(xhr, er) {
                    alert('Error ' + xhr.status + ' - ' + xhr.statusText);
                }
            }); 
        }    
    });


    $('.loginportfolio').on("click",function(e){

        alert('To add to the portfolio, you must register or log into your account.');
        location.href=baseUrl+'/register';

    });

    $('#bt-password').on("click",function(e){

        e.preventDefault();

        if ($('#email').val() == '')
            alert('Enter a E-mail.');
        else
           $('#form-password').submit(); 
    }); 


    $( ".update-register" ).after( '<a href="'+baseUrl+'/index" id="cancel-register" class="btn btn-danger">Cancel</a>' );  
    
})


 window.fbAsyncInit = function() {
  FB.init({
    appId      : '674324285923668',
    status     : true, // check login status
    cookie     : true, // enable cookies to allow the server to access the session
    xfbml      : true  // parse XFBML
  });

  };

  // Load the SDK asynchronously
  (function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
   }(document));