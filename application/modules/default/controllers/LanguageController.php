<?php

class LanguageController extends Zend_Controller_Action {

    /*public function indexAction() {
        
    	$this->_helper->viewRenderer->setNoRender(true);
    	$this->view->layout()->disableLayout();
    	
    	if (Zend_Validate::is($this->getRequest()->getParam('lang'), 'InArray', array('haystack' => array('en','pt','es')))) {
      
    	 	$session = new Zend_Session_Namespace('ttb.l10n');
      		$session->locale = $this->getRequest()->getParam('lang');
            
    	}
    
    	// redirect to requesting URL
    	$url = $this->getRequest()->getServer('HTTP_REFERER');
   		$this->_redirect($url);
    }*/

     public function indexAction() {
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->view->layout()->disableLayout();
        
        $session = new Zend_Session_Namespace('Language');
        $session->language = $this->getRequest()->getParam('lang');
    
        // redirect to requesting URL
        $url = $this->getRequest()->getServer('HTTP_REFERER');
        $this->_redirect($url);
    }
	
}

