<?php

class Admin_LoginController extends Zend_Controller_Action {

    public function init() {
        
    	$this->view->headTitle()->append('Administração');
    	$this->form = new Momesso_Admin_Form_Login_Login();
    }

    public function indexAction() {

        $this->_helper->layout()->setLayout('login');
		$this->view->form = $this->form;
		
		$this->view->Error = $this->_request->getParam('error');

        if ($this->_request->isPost()) {
            
        	$dados = $this->_request->getPost();
            $this->view->dados = $dados;

            if ($this->form->isValid($dados)) {
                
            	@$authAdapter = new Zend_Auth_Adapter_DbTable($db, 'mod_users', 'user', 'password', 'MD5(?)');
                $authAdapter->setIdentity($dados['usuario'])->setCredential($dados['senha']);

                $result = $authAdapter->authenticate();
                
                if ($result->isValid()) {

                    $SessID = md5(uniqid(time()));

                    $auth = Zend_Auth::getInstance();

                    $data = $authAdapter->getResultRowObject(array('cod_user', 'user', 'name', 'group','country'));
                    $auth->getStorage()->write($data);
                    $data->SessID = $SessID;

                    $identify = $auth->getIdentity();
                   
                    $sessionUsuario = new Zend_Session_Namespace('sessionUsuario');
                    $sessionUsuario->id         = $identify->cod_user;
                    $sessionUsuario->nome       = $identify->name;
                    $sessionUsuario->usuario    = $identify->user;
                    $sessionUsuario->country    = $identify->country;

                    $this->_redirect('/admin/touro');
                    
                } else {
                    
                    switch ($result->getCode()) {
                        
                    	case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                            
                    		$this->_redirect('/admin/login/index/error/1');
                        
                        case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                            
                        	$this->_redirect('/admin/login/index/error/2');
                    }
                }
            }
        }
    }

    public function logoutAction() {

    	$this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $sessionUsuario = new Zend_Session_Namespace('sessionUsuario');
        $sessionUsuario->unsetAll();

        Zend_Auth::getInstance()->clearIdentity();

        $this->_redirect('admin/login');
    }

}

