   $(function() {

        $('#altsenha').click(function(e) {
            e.preventDefault();
            $( "#teste" ).dialog({

                title: 'Alterar senha',
                position: [220,300],
                resizable: false,
                draggable: false,
                width: 370,
                minHeight: 200,modal: true,
                buttons: {
                    Gravar: function() {

                        $('#form_senha').submit(function(e) {
                            e.preventDefault();
                        });

                        validatorFormSenha = $('#form_senha').validate({
                            rules: {
                                antiga: {
                                    required : true,
                                    minlength : 6
                                },
                                senha: {
                                    required : true,
                                    minlength : 6
                                },
                                confirma: {
                                    required : true,
                                    minlength : 6,
                                    equalTo: "#senha"
                                }
                            }
                        });


                        if($("#form_senha").valid()) {
                            $.ajax({
                                url: $('#form_senha').attr('action'),
                                dataType: 'html',
                                type: 'post',
                                data: $('#form_senha').serialize(),

                                beforeSend: function() {
                                    addMascara();
                                },
                                complete: function() {
                                    removeMascara();
                                },
                                success: function(data) {
                                    if (data == 'validou') {
                                        $('#res_form_senha').html('<div id="senha_retorno">Senha alterada com sucesso!</div>').dialog({
                                            title: 'Sucesso',
                                            position: [310,325],
                                            resizable: false,
                                            draggable: false,
                                            width: 200,
                                            buttons: {
                                                Fechar: function() {
                                                    $('*').dialog('close');
                                                }
                                            }
                                        });
                                    }
                                    else {
                                        $('#res_form_senha').html('<div id="senha_retorno">'+data+'</div>').dialog({
                                            title: 'Erro',
                                            position: [310,350],
                                            resizable: false,
                                            draggable: false,
                                            width: 200
                                        });
                                    }
                                },
                                error: function(xhr, er) {
                                    alert('Error ' + xhr.status + ' - ' + xhr.statusText);
                                }
                            });
                        }
                    },
                    Cancelar: function() {
                        $(this).dialog('close');
                    }

                }
            });
        });


        $('#altsenha').click(function(e) {
            e.preventDefault();
            $('#form_senha input[type="password"]').val('');
            $('#form_senha').dialog('open');
        });
    });
