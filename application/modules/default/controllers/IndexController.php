<?php

class IndexController extends Zend_Controller_Action {

    public function indexAction() {
        
    	$this->view->headTitle()->append('Home');

        $session     = new Zend_Session_Namespace('Language');
        $sessionStep = new Zend_Session_Namespace('sessionStep');
        
        
        if (empty($session->language)) {
            
            $session->language = 11;
        }

        if ($this->_getParam('type')) {
            $sessionStep->step1      = $this->_getParam('type');
            $sessionStep->step1Label = $this->_getParam('type');
        }  

        if ($this->_getParam('search')) {

            if ($this->_getParam('search') == 'helpmechoose') {

                $this->_redirect('index/step2');
             
            } elseif ($this->_getParam('search') == 'advanced') {

                $this->_redirect('advanced');
            }

        }  
    }	

    public function step1Action(){
    
    }

    public function step2Action(){
        
    }

    public function step3Action(){

    }

    public function step4Action(){

    }

    public function goalsAction(){

        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();

            $sessionStep = new Zend_Session_Namespace('sessionStep');

            $sessionStep->step4 = true;

            unset($sessionStep->goalVolume,$sessionStep->goalLongevity,$sessionStep->goalMilkFat,$sessionStep->goalMilkProtein,$sessionStep->goalDaughter);
                

            $sessionStep->goalVolume        = $dados['checks'][0];
            $sessionStep->goalLongevity     = $dados['checks'][1];
            $sessionStep->goalMilkFat       = $dados['checks'][2];
            $sessionStep->goalMilkProtein   = $dados['checks'][3];
            $sessionStep->goalDaughter      = $dados['checks'][4];

        }    

    }

    public function step5Action(){

    	
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

    public function stepsAction(){

        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $sessionStep = new Zend_Session_Namespace('sessionStep');

        $dados = $this->_request->getPost ();

        if ($dados['step'] == 'step1') {
           
            $sessionStep->step1      = $dados['value'];
            $sessionStep->step1Label = $dados['label'];
        }

        if ($dados['step'] == 'step2') {
           
            $sessionStep->step2      = $dados['value'];
            $sessionStep->step2Label = $dados['label'];
        }

        if ($dados['step'] == 'step3') {
           
            $sessionStep->step3      = $dados['value'];
            $sessionStep->step3Label = $dados['label'];
        }

        if (condition) {
           
           $sessionStep->step5  = $dados['records'];
        }

    }
}

