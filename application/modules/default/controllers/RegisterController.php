<?php

class RegisterController extends Zend_Controller_Action {


    public function init() {
        

        $this->Form = new Momesso_Default_Form_Register_Customer();
        $this->Form_Edit = new Momesso_Default_Form_Register_CustomerEdit();
        $this->Form_Login = new Momesso_Default_Form_Register_Login();
        $this->Form_Reset = new Momesso_Default_Form_Register_Reset();

        $this->Model = new Model_Customer();

        if ($this->_helper->FlashMessenger->hasMessages()) {
            $this->view->messages = $this->_helper->FlashMessenger->getMessages();
            $this->_helper->FlashMessenger->clearMessages();
        }
    }

    public function indexAction() {
        
    	$this->view->headTitle()->append('Register');


        if ($this->_request->isPost()) {

            $dados = $this->_request->getPost();
            $this->view->dados = $dados;

            if ($this->Form->isValid($dados)) {

                unset($dados['password2'],$dados['Send']);

                try {

                    /* Send Email Welcome */
                    
                    $this->view->dados = $dados;

                    $message = $this->view->render('template/welcome.phtml');

                    $headers = "MIME-Version: 1.1\n";
                    $headers .= "Content-type: text/html; charset=utf-8\n";
                    $headers .= "From:no-responder <bruno@spring.bi>\n"; // remetente
                    $headers .= "Reply-To: ".$dados['email']."\n"; // return-path
                    $emailsender = 'bruno@spring.bi';

                    $ok = mail($dados['email'], "Welcome - Genus Bull Search", $message, $headers,"-r".$emailsender);

                    /* Save the data */
                    $dados['password'] = md5($dados['password']);

                    $r = $this->Model->save($dados);

                    /* Automatic Login */

                    $sessionCustomer            = new Zend_Session_Namespace('sessionCustomer');
                    $sessionCustomer->id        = $r;
                    $sessionCustomer->name      = $dados['first_name'].' '.$dados['last_name'];
                    $sessionCustomer->email     = $dados['email'];

                    $this->Form->reset();

                    $this->view->message = 'Thank you for registering.';
                    $this->view->messageType = 'success';

                } catch (Zend_Db_Exception $e) {
                
                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'danger';

                }
            }
        }
        $this->view->Form = $this->Form;
    }

    public function editAction() {
        
        $this->view->headTitle()->append('Register');

        $sessionCustomer = new Zend_Session_Namespace('sessionCustomer');

        $r = $this->Model->getCustomerById($sessionCustomer->id);

        $this->view->Form = $this->Form_Edit;

        if ($this->_request->isPost()) {

            $dados = $this->_request->getPost();
            $this->view->dados = $dados;

            if ($this->Form_Edit->isValid($dados)) {

                unset($dados['Update']);

                try {

                    $this->Model->save($dados,$sessionCustomer->id);

                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';

                } catch (Zend_Db_Exception $e) {
                
                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'danger';

                }
            }
        } else {

            $this->Form_Edit->populate($r->toArray());
            $this->view->Form = $this->Form_Edit;
        }
       
    }

     public function loginAction() {
		
		$this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->getRequest()->isXmlHttpRequest()) {

            if ($this->_request->isPost()) {
                
            	$dados = $this->_request->getPost();
                $this->view->dados = $dados;

                if ($this->Form_Login->isValid($dados)) {
                    
                	@$authAdapter = new Zend_Auth_Adapter_DbTable($db, 'mod_customers', 'email', 'password', 'MD5(?)');
                    $authAdapter->setIdentity($dados['usuario'])->setCredential($dados['senha']);

                    $result = $authAdapter->authenticate();
                    
                    if ($result->isValid()) {

                        $SessID = md5(uniqid(time()));

                        $auth = Zend_Auth::getInstance();

                        $data = $authAdapter->getResultRowObject(array('cod_customer', 'email', 'first_name','last_name'));
                        $auth->getStorage()->write($data);
                        $data->SessID = $SessID;

                        $remember = $dados['remember'] == 1 ? 1 : 0;
                        $seconds  = 60 * 60 * 24 * 7; // 7 days
                 
                        if ($remember) {
                            Zend_Session::RememberMe($seconds);
                        }
                        else {
                            Zend_Session::ForgetMe();
                        }

                        $identify = $auth->getIdentity();
                       
                        $sessionCustomer = new Zend_Session_Namespace('sessionCustomer');
                        $sessionCustomer->id            = $identify->cod_customer;
                        $sessionCustomer->name          = $identify->first_name.' '.$identify->last_name;
                        $sessionCustomer->email         = $identify->email;
                        $sessionCustomer->portfolio     = $identify->portfolio;

                        echo 1;
                        
                    } else {
                        
                        switch ($result->getCode()) {
                            
                        	case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                                
                        		echo 2;
                                break;
                            
                            case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                                
                            	echo 3;
                                break;
                        }
                    }
                }
            }

        }
    }

    public function loginfaceAction() {

        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();
                $this->view->dados = $dados;

                $sessionCustomer = new Zend_Session_Namespace('sessionCustomer');

                $user = $this->Model->getUserByFacebookId($dados['responseMe']['id']);

                if ($user) {
                    
                    $sessionCustomer->id       = $user->cod_customer;
                    $sessionCustomer->name     = $user->first_name.' '.$user->last_name;
                    $sessionCustomer->email    = $user->email;

                } else {

                    $insert['facebook_id']      = $dados['responseMe']['id'];
                    $insert['first_name']       = $dados['responseMe']['first_name'];
                    $insert['last_name']        = $dados['responseMe']['last_name'];
                    $insert['email']            = $dados['responseMe']['email'];

                    $cidade = explode(',', $dados['responseMe']['hometown']['name']);

                    $insert['city']             = $cidade[0];
                    $insert['state']            = $cidade[1];

                    $r = $this->Model->save($insert);

                    $sessionCustomer->id        = $r;
                    $sessionCustomer->name      = $dados['responseMe']['first_name'].' '.$dados['responseMe']['last_name'];
                    $sessionCustomer->email     = $dados['responseMe']['email'];

                }
            }
            
        }        
    }


    public function logoutAction() {

    	$this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $sessionCustomer = new Zend_Session_Namespace('sessionCustomer');
        $sessionCustomer->unsetAll();

        Zend_Auth::getInstance()->clearIdentity();

        $this->_redirect('/');
    }


    public function forgotAction(){

        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
           

            $r = $this->Model->getCustomerByEmail($dados['email']);

            if (!empty($r->email)) {

                
                if (!empty($r->facebook_id)) {
                    
                    $this->view->message = 'Your login is through facebook.';
                    $this->view->messageType = 'danger';

                } else {

                    $this->view->dados = $r;

                    $message = $this->view->render('template/password.phtml');

                    $headers = "MIME-Version: 1.1\n";
                    $headers .= "Content-type: text/html; charset=utf-8\n";
                    $headers .= "From:no-responder <bruno@spring.bi>\n"; // remetente
                    $headers .= "Reply-To: ".$dados['email']."\n"; // return-path
                    $emailsender = 'bruno@spring.bi';

                    $ok = mail($dados['email'], "Bull Search - Password Reset", $message, $headers,"-r".$emailsender);

                    if ($ok) {

                        $this->view->message = 'Message sent successfully!';
                        $this->view->messageType = 'success';

                    } else {

                        $this->view->message = 'There was an error, please try again';
                        $this->view->messageType = 'danger';
                    } 
                }    

            } else {

                $this->view->message = 'E-mail not found, please try again';
                $this->view->messageType = 'danger';
            }   
        }
            
    }

    public function resetAction(){

    
        $Id =  base64_decode($this->_request->getParam('id'));

        $r = $this->Model->getCustomerById($Id);

        if ($this->_request->isPost()) {

            $dados = $this->_request->getPost();
            $this->view->dados = $dados;

            if ($this->Form_Reset->isValid($dados)) {

                unset($dados['password2'],$dados['Update']);

                try {

                    $dados['password'] = md5($dados['password']);

                    $this->Model->save($dados,$Id);

                    $sessionCustomer            = new Zend_Session_Namespace('sessionCustomer');
                    
                    if (!$sessionCustomer->id) {
                        
                        $sessionCustomer->id        = $Id;
                        $sessionCustomer->name      = $r['first_name'].' '.$r['last_name'];
                        $sessionCustomer->email     = $r['email'];
                    }

                    $this->Form_Reset->reset();

                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';

                } catch (Zend_Db_Exception $e) {
                
                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'danger';

                }
            }  else {

                $this->view->Form = $this->Form_Reset;
            }
         } else {

            $this->Form_Reset->populate($r->toArray());
            $this->view->Form = $this->Form_Reset;
        }
    }

    public function checkemailAction(){

        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();

                 $sessionCustomer  = new Zend_Session_Namespace('sessionCustomer');

                $r = $this->Model->getCustomerByEmailAndId($dados['email'],$sessionCustomer->id );

                // E-mail encontrado
                if (count($r) == 1) {
                   
                   echo 1;
                
                } elseif (count($r) == 0) {
                    
                    echo 2;
                }
            }
        }        
    }


    public function deleteAction(){

        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        try {

            $sessionCustomer = new Zend_Session_Namespace('sessionCustomer');

            $r = $this->Model->getCustomerById($sessionCustomer->id);        
            $r->delete();

            $sessionCustomer->unsetAll();
            Zend_Auth::getInstance()->clearIdentity();

            $this->view->message = 'Your account has been deleted successfully!';
            $this->view->messageType = 'success';

            $this->_redirect('/');

        } catch (Zend_Db_Exception $e) {

            $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
            $this->view->messageType = 'danger';
            $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
        }    
    }	
   
}

