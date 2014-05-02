<?php

class Admin_ContaController extends Zend_Controller_Action {

    public function indexAction() {

        $form = new Momesso_Admin_Form_Conta_Conta();

        $this->view->Form = $form;

        $auth = Zend_Auth::getInstance()->getIdentity();
        $Model_Usuario = new Model_Usuario();
        $rs = $Model_Usuario->fetchRow("id = $auth->id")->toArray();

        $form->populate($rs);

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();
            unset($dados['Gravar']);
            if ($form->isValid($dados)) {
                $dialog = new ZendX_JQuery_View_Helper_DialogContainer();
                $dialog->setView($this->view);
                try {
                    $Model_Usuario->update($dados, "id = $auth->id");
                    echo $dialog->dialogContainer('dialog1', 'Alteração realizada com sucesso!', array('modal' => true, 'title' => 'Sucesso', 'closeOnEscape' => true));
                    $form->setDefault('altsenha', '<a href="#" id="altsenha">Alterar senha</a>');

                    $Model_Temas = new Model_Temas();
                    $tema = $Model_Temas->fetchRow("cod_tema = '$dados[tema]'");
                    $sessionUsuario = new Zend_Session_Namespace('sessionUsuario');
                    $sessionUsuario->nome = $dados['nome'];
                    $sessionUsuario->email = $dados['email'];
                    $sessionUsuario->usuario = $dados['usuario'];

                } catch (Zend_Exception $e) {
                    echo $dialog->dialogContainer('dialog1', $e->getMessage(), array('modal' => true, 'title' => 'Erro na gravação', 'closeOnEscape' => true));
                }
            }
        }
    }

    public function altsenhaAction() {
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $auth = Zend_Auth::getInstance()->getIdentity();
        $Model_Usuarios = new Model_Usuario();

        if ($this->_request->isPost()) {
            $dados = $this->_request->getPost();

            $sql = $Model_Usuarios->select()->where('id = ?', $auth->id);

            $res = $Model_Usuarios->fetchRow($sql)->toArray();

            $db = Zend_Registry::get('db');

            $authAdapter = new Zend_Auth_Adapter_DbTable($db, 'mod_usuarios', 'usuario', 'senha', 'MD5(?)');
            $authAdapter->setIdentity($res['usuario'])->setCredential($dados['antiga']);

            $result = $authAdapter->authenticate();

            if ($result->isValid()) {
                unset($dados['antiga'], $dados['confirma'], $dados['submit']);
                $dados['senha'] = md5($dados['senha']);
                $Model_Usuarios->update($dados, "id = $auth->id");
                echo 'validou';
            } else {
                switch ($result->getCode()) {
                    case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                        echo 'Usuário inexistente!';
                        break;
                    case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                        echo 'Senha inválida!';
                        break;
                }
            }
        }
    }

}