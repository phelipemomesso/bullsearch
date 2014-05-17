<?php

class IndexController extends Zend_Controller_Action {


    public function indexAction() {
        
    	$this->view->headTitle()->append('Home');

        $session = new Zend_Session_Namespace('Language');
        
        if (empty($session->language)) {
            
            $session->language = 11;
        }      
    }	

    public function step1Action(){

        if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();
                $this->view->dados = $dados;

                $sessionStep = new Zend_Session_Namespace('sessionStep');

                $sessionStep->step1 = $dados['value'];
                $sessionStep->step1Label = $dados['label'];
            } 
        }     
    }

    public function step2Action(){

    	if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();
                $this->view->dados = $dados;

                $sessionStep = new Zend_Session_Namespace('sessionStep');

                $sessionStep->step2 = $dados['value'];
                $sessionStep->step2Label = $dados['label'];
            } 
        }  
    }

    public function step3Action(){

    	if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();
                $this->view->dados = $dados;

                $sessionStep = new Zend_Session_Namespace('sessionStep');

                $sessionStep->step3 = $dados['value'];
                $sessionStep->step3Label = $dados['label'];
            } 
        } 
    }

    public function step4Action(){

    	if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();
                $this->view->dados = $dados;


            } 
        }
    }

    public function goalsAction(){

        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();

            $n = count($dados['goals']);

            $sessionStep = new Zend_Session_Namespace('sessionStep');

            $sessionStep->step4 = true;
                
            $sessionStep->goalVolume        = $dados['checks'][0];
            $sessionStep->goalLongevity     = $dados['checks'][1];
            $sessionStep->goalMilkFat       = $dados['checks'][2];
            $sessionStep->goalMilkProtein   = $dados['checks'][3];
            $sessionStep->goalDaughter      = $dados['checks'][4];
        
            echo 1;

        }    

    }

    public function step5Action(){

        if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();
                
                $sessionStep = new Zend_Session_Namespace('sessionStep');

                $sessionStep->step5  = $dados['records'];
            } 
        }
    	
    }

    public function cookieAction(){

        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->_request->isPost()) {

            $dados = $this->_request->getPost ();

            setcookie("bullsearch", $dados['country'], time()+60*60*24*7, "/","", 0);

            echo $this->getRequest()->getServer('HTTP_REFERER');
        
        }    

    }
}

