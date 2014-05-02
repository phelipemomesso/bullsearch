<?php

class Admin_ModuloController extends Zend_Controller_Action {

    public function init(){
    	
    	$this->Model_Controller 	= new Model_Controller();
    	$this->Model_Action 		= new Model_Action();
    	
    	$this->Form_Controller 		= new Momesso_Admin_Form_Controllers_Controller();
    	$this->Form_Action 			= new Momesso_Admin_Form_Controllers_Action();
    	
    	$this->ValidateInputUrl 	= new Momesso_Plugins_ValidateInputUrl();
    	$this->ErrorLog 			= new Momesso_Plugins_ErrorLog();
    }


    public function indexAction() {
        
    	$this->view->Data = $this->Model_Controller->getControllers();
    }
    
    public function newAction(){
    
    	$this->view->Form = $this->Form_Controller;
    
    	if ($this->_request->isPost()) {
    
    		$dados = $this->_request->getPost();
    
    		if ($this->Form_Controller->isValid($dados)) {
    
    			unset($dados['Gravar']);
    
    			try {
    
    				$idInsert = $this->Model_Controller->save($dados);
    				
    				$this->createControllers($dados['name']);
    				$this->createFolders($dados['name']);
    
    				$this->view->message = 'Dados salvos com sucesso!';
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
    	$r = $this->Model_Controller->getControllerById($Id);
    
    	$this->view->Form = $this->Form_Controller;
    
    	if ($this->_request->isPost()) {
    
    		$dados = $this->_request->getPost();
    
    		if ($this->Form_Controller->isValid($dados)) {
    
    			unset($dados['Gravar']);
    
    			try {
    				 
    				$this->Model_Controller->save($dados,$Id);
    				
    				$this->criaNavigation();
    
    				$this->view->message = 'Dados salvos com sucesso!';
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
    		$this->Form_Controller->populate($r->toArray());
    	}
    }
    
    public function deleteAction(){
    	 
    	$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	 
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$r = $this->Model_Controller->getControllerById($Id);
    	 
    	$r->delete();
    	$this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }
    
    
    /*
     * Actions
     * */
    
    public function actionAction() {
    	
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	
    	$this->view->Controller = $this->Model_Controller->getControllerById($Id);
    	
    	$this->view->controllerId  = $Id;
    	$this->view->Data = $this->Model_Action->getActionsByControllerId($Id);
    }
    
    public function newactionAction(){
    
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	
    	$this->Form_Action->getElement('controller')->setValue($Id);
    	$this->view->Form = $this->Form_Action;
    
    	if ($this->_request->isPost()) {
    
    		$dados = $this->_request->getPost();
    
    		if ($this->Form_Action->isValid($dados)) {
    
    			unset($dados['Gravar']);
    
    			try {
    
    				$idInsert = $this->Model_Action->save($dados);
    
    				$this->view->message = 'Dados salvos com sucesso!';
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
    
    public function editactionAction(){
    
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$r = $this->Model_Action->getActionById($Id);
    
    	$this->view->Form = $this->Form_Action;
    
    	if ($this->_request->isPost()) {
    
    		$dados = $this->_request->getPost();
    
    		if ($this->Form_Action->isValid($dados)) {
    
    			unset($dados['Gravar']);
    
    			try {
    					
    				$this->Model_Action->save($dados,$Id);
    
    				$this->view->message = 'Dados salvos com sucesso!';
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
    		$this->Form_Action->populate($r->toArray());
    	}
    }
    
    public function deleteactionAction(){
    
    	$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$r = $this->Model_Action->getActionById($Id);
    
    	$r->delete();
    	$this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }

    private function createControllers($name){
    	
    	$name = ucwords($name);
    	
    	$fileAdmin = file_get_contents('../template/ControllerAdmin.php', FILE_USE_INCLUDE_PATH);
    	$fileDefault = file_get_contents('../template/ControllerDefault.php', FILE_USE_INCLUDE_PATH);
    	
    	$fileLocationAdmin = '../application/modules/admin/controllers/'.$name.'Controller.php';
    	$fileLocationDefault = '../application/modules/default/controllers/'.$name.'Controller.php';
    	   	
    	$file = fopen($fileLocationAdmin,"w+");
    	fwrite($file,$fileAdmin);
    	fclose($file);
    	
    	$file = fopen($fileLocationDefault,"w+");
    	fwrite($file,$fileDefault);
    	fclose($file);
    }
    
    private function createFolders($name){
    	
    	mkdir('../application/modules/admin/views/scripts/'.$name.'/', 0777);
    	mkdir('../application/modules/default/views/scripts/'.$name.'/', 0777);
    	  	
    }
    
    
    private function criaNavigation() {
    
    	$xmlFile = '../application/configs/admin.xml';
    
    	if (!file_exists($xmlFile)) {
    		$cria = fopen($xmlFile, 'w');
    		fclose($cria);
    	}
    
    	$xml = new DOMDocument("1.0", "UTF-8");
    	$xml->preserveWhiteSpace = false;
    	$xml->formatOutput = true;
    
    	$configdata = $xml->createElement("configdata");
    	$nav = $xml->createElement("nav");
    	$xml->appendChild($configdata)->appendChild($nav);
    
    	$controllers = $this->Model_Controller->getControllers();
    
    	foreach ($controllers as $controllerArray) {
    		
    		$actions = $this->Model_Action->getActionsByControllerId($controllerArray->cod_controller);
    
    		$controller = $nav->appendChild($xml->createElement($controllerArray->name));
    
    		$controller->appendChild($xml->createElement('label', $controllerArray->label));
    		$controller->appendChild($xml->createElement('class', ''));
    		$controller->appendChild($xml->createElement('title', $controllerArray->label));
    		$controller->appendChild($xml->createElement('module', 'admin'));
    		$controller->appendChild($xml->createElement('controller', $controllerArray->name));
    		$controller->appendChild($xml->createElement('resource', $controllerArray->name));
    		$controller->appendChild($xml->createElement('privilege', 'index'));
    		$controller->appendChild($xml->createElement('action', 'index'));
    		$controller->appendChild($xml->createElement('visible', $controllerArray->visible));
    		$controller->appendChild($xml->createElement('route', 'default'));
    
    		if (count($actions)) {
    			$pages = $controller->appendChild($xml->createElement('pages'));
    			foreach ($actions as $actionArray) {
    				$action = $pages->appendChild($xml->createElement($actionArray->action));
    				$action->appendChild($xml->createElement('label', $actionArray->label));
    				$action->appendChild($xml->createElement('class', ''));
    				$action->appendChild($xml->createElement('title', $actionArray->label));
    				$action->appendChild($xml->createElement('module', 'admin'));
    				$action->appendChild($xml->createElement('controller', $controllerArray->name));
    				$action->appendChild($xml->createElement('resource', $controllerArray->name));
    				$action->appendChild($xml->createElement('privilege', $actionArray->action));
    				$action->appendChild($xml->createElement('action', $actionArray->action));
    				$action->appendChild($xml->createElement('visible', $actionArray->visible));
    				$action->appendChild($xml->createElement('route','default'));
    			}
    		}
    	}
    	$xml->save($xmlFile);
    }


}