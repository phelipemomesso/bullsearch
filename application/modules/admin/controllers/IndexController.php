<?php

class Admin_IndexController extends Zend_Controller_Action {

    private function formSuporte() {

        $sessionUsuario = Zend_Session::namespaceGet('sessionUsuario');

        $form = new Zend_Form();
        $form->setAttrib('class', 'form-geral');

        $assunto = $form->createElement('text', 'assunto')
                        ->setRequired(true)
                        ->addFilters(array('StripTags', 'stringTrim'))
                        ->setLabel('Assunto')
                        ->setAttrib('class', 'campo-grande');

        $mensagem = $form->createElement('textarea', 'mensagem')
                        ->setRequired(true)
                        ->addFilters(array('StripTags', 'stringTrim'))
                        ->setLabel('Mensagem')
                        ->setAttrib('class', 'textarea-grande-medio');

        $nome = $form->createElement('hidden', 'nome')
                        ->setRequired(true)
                        ->setValue($sessionUsuario['nome'])
                        ->removeDecorator('label');

        $usuario = $form->createElement('hidden', 'usuario')
                        ->setRequired(true)
                        ->setValue($sessionUsuario['usuario'])
                        ->removeDecorator('label');

        $email = $form->createElement('hidden', 'email')
                        ->setRequired(true)
                        ->setValue($sessionUsuario['email'])
                        ->removeDecorator('label');

        $horario = $form->createElement('hidden', 'horario')
                        ->setRequired(true)
                        ->setValue(new Zend_Date(Zend_Date::DATETIME))
                        ->removeDecorator('label');

        $submit = $form->createElement('submit', 'Enviar')->setIgnore(true);

        $form->addElements(array($assunto, $mensagem, $nome, $usuario, $email, $horario, $submit));

        return $form;
    }

    public function indexAction() {

        $config = Zend_Registry::get('config');
        $form = self::formSuporte();
        $this->view->form = $form;
        $this->view->projectName = $config->projectName;

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            if ($form->isValid($dados)) {
                $dialog = new ZendX_JQuery_View_Helper_DialogContainer();
                $dialog->setView($this->view);
                try {
                    $this->view->dados = $dados;

                    $mail = new Zend_Mail('UTF-8');
                    $mail->setBodyHtml($this->view->render('index/mensagem.phtml'))
                            ->setFrom($dados['email'], 'Suporte para ' . $config->projectName)
                            ->addTo('contato@momessoweb.com.br', 'Suporte para ' . $config->projectName)
                            ->setSubject($dados['assunto'])
                            ->send();
                    $form->setDefaults(array('assunto' => '', 'mensagem' => ''));
                    echo $dialog->dialogContainer('dialog1', 'Em breve responderemos seu contato!', array('draggable' => true, 'modal' => true, 'resizable' => false, 'title' => 'Envio com sucesso', 'closeOnEscape' => true));
                } catch (Zend_Exception $e) {
                    echo $dialog->dialogContainer('dialog1', $e->getMessage(), array('draggable' => true, 'modal' => true, 'resizable' => false, 'title' => 'Falha no envio', 'closeOnEscape' => true));
                }
            }
        }
    }


}

