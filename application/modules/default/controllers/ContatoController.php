<?php

class ContatoController extends Zend_Controller_Action{


    public function init(){

        $this->Form = new Momesso_Default_Form_Contato();
        $this->view->menuContato = 'active';
    }

	public function indexAction(){

    	$this->view->headTitle()->append('Contato');

        if ($this->_request->isPost ()) {

            $dados = $this->_request->getPost ();
            $this->view->dados = $dados;

            if ($this->Form->isValid ( $dados )) {

                $mensagem = $this->view->render('contato/template_mensagem.phtml');

                $headers = "MIME-Version: 1.1\n";
                $headers .= "Content-type: text/html; charset=utf-8\n";
                $headers .= "From:". $dados['nome']." <".$dados['email'].">\n"; // remetente
                $headers .= "Reply-To: ".$dados['email']."\n"; // return-path

                $ok = mail('phelipe.momesso@gmail.com,alexandre@lacorretora.com.br', "Contato - LA Seguradora", $mensagem, $headers);

                if($ok){
                    $this->view->Msg = '<div class="alert alert-success alert-dismissable">
									    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									    <h4>Dados Enviados com Sucesso!</h4>
									    </div>';
                    $this->Form->reset();
                }else{
                    $this->view->Msg = '<div class="alert alert-danger alert-dismissable">
									    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
									    <h4>Não foi possível enviar sua mensagem.</h4>
									    </div>';
                }

            } else {
                $this->view->Form = $this->Form;
            }
        }
        $this->view->Form = $this->Form;

    }

}
