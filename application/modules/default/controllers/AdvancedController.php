<?php

class AdvancedController extends Zend_Controller_Action {


    public function init() {

        $this->Model_Breed = new Model_Breed();
        $this->Model_Touro = new Model_Touro();

    }

    public function indexAction() {
        
    	$this->view->headTitle()->append('Home');

        $session = new Zend_Session_Namespace('Language');
        
        if (empty($session->language)) {
            
            $session->language = 11;
        }

        $this->view->Breeds = $this->Model_Breed->getBreedsByType(2);      
    }

    public function resultAction(){

        $res = $this->createSql();

        $this->view->Data = $res;
        $this->view->Total = count($res);

    }

    public function exportAction(){

        $this->view->layout()->disableLayout();

        $res = $this->createSql();

        $this->view->Data = $res;
        $this->view->Total = count($res);

    }	

    public function advsAction(){

        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();  

                $sessionAdvSearchStep = new Zend_Session_Namespace('sessionAdvSearchStep');

                $sessionAdvSearchStep->type  = 'Dairy';
                $sessionAdvSearchStep->breed = $dados['breed'];
                $sessionAdvSearchStep->sire  = $dados['sire'];
                $sessionAdvSearchStep->abs   = $dados['abs'];

                if (isset($dados['selectIds'])) {

                    $c = array_combine($dados['selectIds'], $dados['selectValues']);
                    
                    foreach ($dados['selectIds'] as $key => $v) {
                        
                        $sql .= 'bf.'.$v.' > '.$dados['selectValues'][$key].' and ';
                    }

                    $pos = strripos($sql,' and ');
                    $sql = substr($sql, 0, $pos);

                    $sessionAdvSearchStep->parametros = $c;

                    $sessionAdvSearchStep->sql = $sql;

                } else {

                    unset($sessionAdvSearchStep->sql);
                }

                if (isset($dados['selectSelos']) and $dados['abs'] == 1) {
                    
                    foreach ($dados['selectSelos'] as $key => $v) {
                        
                        $sqlSelos .= ' bc.'.$v.' = 1 and ';
                    }

                    $pos = strripos($sqlSelos,' and ');
                    $sqlSelos = substr($sqlSelos, 0, $pos);

                    $sessionAdvSearchStep->sqlSelos = $sqlSelos;

                    echo $sessionAdvSearchStep->sqlSelos;

                } else {

                    unset($sessionAdvSearchStep->sqlSelos);
                }
            } 
        }     
    }

    public function tableAction(){

        $this->view->layout()->disableLayout();

        if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();  

                $this->view->Breed = $dados['breed'];
                $this->view->Sire  = $dados['sireType'];
            } 
        }     
    }

    private function createSql(){

        $sessionAdvSearchStep = new Zend_Session_Namespace('sessionAdvSearchStep');

        $sql = '';
        
        if ($sessionAdvSearchStep->type) {

            $sql = 'bc.type = "'.$sessionAdvSearchStep->type.'" ';
        }

        if ($sessionAdvSearchStep->breed) {
                
            $sql .= ' and b.breed = '.$sessionAdvSearchStep->breed;
        }

        if ($sessionAdvSearchStep->sire) {
                
            if ($sessionAdvSearchStep->sire == 'all') {
                
                $sql .= ' and bc.daughter_proven = 1 or bc.genomic_young_sires = 1';
            
            } elseif ($sessionAdvSearchStep->sire == 'daughter_proven') {

                $sql .= ' and bc.daughter_proven = 1';

            } elseif ($sessionAdvSearchStep->sire == 'genomic_young_sires') {

                $sql .= ' and bc.genomic_young_sires = 1';
            }

        }

        if ($sessionAdvSearchStep->abs == 1) {

            $sql .= ' and b.abs = '.$sessionAdvSearchStep->abs;
        }

        if (isset($_COOKIE['bullsearch']))
            $sql .= ' and bc.country = '.$_COOKIE['bullsearch'];
         else
            $sql .= ' and bc.country = 223';


        if ($sessionAdvSearchStep->sql) {

            $sql .= ' and '.$sessionAdvSearchStep->sql;
        }

        if ($sessionAdvSearchStep->sqlSelos and $sessionAdvSearchStep->abs == 1) {

            $sql .= ' and '.$sessionAdvSearchStep->sqlSelos;
        }

         return $this->Model_Touro->advancedSearch($sql);
       
    }    

    
}

