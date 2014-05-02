<?php

class QuickController extends Zend_Controller_Action {


    public function init(){

    	$this->Model = new Model_Touro();

    }

    public function indexAction() {
        
    	$this->view->headTitle()->append('Result');

    	if ($this->_request->isPost ()) {

            $dados = $this->_request->getPost ();
            $this->view->dados = $dados;

            if (!empty($dados['search']))

            	$this->view->Results = $this->Model->quickSearch($dados['search']);
            else

            	$this->_redirect('/');

        }    

    	
    }

    


   
}

