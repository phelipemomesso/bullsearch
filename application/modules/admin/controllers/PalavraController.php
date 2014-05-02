<?php

class Admin_PalavraController extends Zend_Controller_Action {

    public function init() {

        $this->Model = new Model_Palavra();
        $this->Model_PalavraLanguage = new Model_PalavraLanguage();
        
        $this->Form = new Momesso_Admin_Form_Palavra_Palavra();
        $this->Form_Translate = new Momesso_Admin_Form_Palavra_Translate();
        
        $this->ValidateInputUrl = new Momesso_Plugins_ValidateInputUrl();
        $this->ErrorLog = new Momesso_Plugins_ErrorLog();
        $this->Data = new Momesso_Plugins_Data();
    }

    public function indexAction() {

    	$this->view->Data = $this->Model->getWords();
    }
    
    public function newAction(){
    
    	$this->view->Form = $this->Form;
    
    	if ($this->_request->isPost()) {
    
    		$dados = $this->_request->getPost();
    
    		if ($this->Form->isValid($dados)) {
    
    			unset($dados['Gravar']);
    
    			try {
    				
    				$this->Model->save($dados);

    				$this->view->message = 'Data saved successfully!';
    				$this->view->messageType = 'success';
    
    			} catch (Zend_Db_Exception $e) {
    
    				$this->view->message = 'Houve um erro, tente novamente. <br /><br />'.$e->getMessage();
    				$this->view->messageType = 'error';
    				 
    				$this->ErrorLog->setModulo($this->_request->getControllerName());
    				$this->ErrorLog->setAcao($this->_request->getActionName());
    				$this->ErrorLog->setErro($e->getMessage());
    				$this->ErrorLog->recordLog();
    			}
    		}
    	}
    }

	public function editAction(){

    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$r = $this->Model->getWordById($Id);
        
        $this->view->Form = $this->Form;
        
        if ($this->_request->isPost()) {
            
        	$dados = $this->_request->getPost();
            
            if ($this->Form->isValid($dados)) {
                
                unset($dados['Gravar']);
                
                try {
                	
                	$this->Model->save($dados,$Id);
                                      
                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
                    
                } catch (Zend_Db_Exception $e) {
                    
                    $this->view->message = 'Houve um erro, tente novamente. <br /><br />'.$e->getMessage();
	                $this->view->messageType = 'error';
	                   	
	                $this->ErrorLog->setModulo($this->_request->getControllerName());
	                $this->ErrorLog->setAcao($this->_request->getActionName());
	                $this->ErrorLog->setErro($e->getMessage());
	                $this->ErrorLog->recordLog();
                }
            }
        } else {
            $this->Form->populate($r->toArray());
        }
    }
    
    public function deleteAction(){
    	
    	$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$r = $this->Model->getWordById($Id);
    	
    	$r->delete();
    	$this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }
    
    /*
     * Words
     */
    
    public function listAction() {
    	
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$this->view->Id = $Id;
    
    	$this->view->Palavra = $this->Model->getWordById($Id);
    	
    	$this->view->Data = $this->Model_PalavraLanguage->getTranslatesByWord($Id);
    }
    
    public function wordnewAction(){
    
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$this->Form_Translate->getElement('palavra')->setValue($Id);
    	
    	$this->view->Form = $this->Form_Translate;
    
    	if ($this->_request->isPost()) {
    
    		$dados = $this->_request->getPost();
    
    		if ($this->Form_Translate->isValid($dados)) {
    
    			unset($dados['Gravar']);
    
    			try {
    
    				$this->Model_PalavraLanguage->save($dados);
    
    				$this->view->message = 'Data saved successfully!';
    				$this->view->messageType = 'success';
    
    			} catch (Zend_Db_Exception $e) {
    
    				$this->view->message = 'Houve um erro, tente novamente. <br /><br />'.$e->getMessage();
    				$this->view->messageType = 'error';
    					
    				$this->ErrorLog->setModulo($this->_request->getControllerName());
    				$this->ErrorLog->setAcao($this->_request->getActionName());
    				$this->ErrorLog->setErro($e->getMessage());
    				$this->ErrorLog->recordLog();
    			}
    		}
    	}
    }
    
    public function wordeditAction(){
    
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$r = $this->Model_PalavraLanguage->getTranslateById($Id);
    
    	$this->view->Form = $this->Form_Translate;
    
    	if ($this->_request->isPost()) {
    
    		$dados = $this->_request->getPost();
    
    		if ($this->Form_Translate->isValid($dados)) {
    
    			unset($dados['Gravar']);
    
    			try {
    				 
    				$this->Model_PalavraLanguage->save($dados,$Id);
    
    				$this->view->message = 'Data saved successfully!';
    				$this->view->messageType = 'success';
    
    			} catch (Zend_Db_Exception $e) {
    
    				$this->view->message = 'Houve um erro, tente novamente. <br /><br />'.$e->getMessage();
    				$this->view->messageType = 'error';
    				 
    				$this->ErrorLog->setModulo($this->_request->getControllerName());
    				$this->ErrorLog->setAcao($this->_request->getActionName());
    				$this->ErrorLog->setErro($e->getMessage());
    				$this->ErrorLog->recordLog();
    			}
    		}
    	} else {
    		$this->Form_Translate->populate($r->toArray());
    	}
    }
    
    public function worddeleteAction(){
    	 
    	$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	 
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$r = $this->Model_PalavraLanguage->getTranslateById($Id);
    	 
    	$r->delete();
    	$this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }
    
    
   }