<?php

class PortfolioController extends Zend_Controller_Action {


    public function init() {

        $this->Model = new Model_Portfolio();
        $this->Model_PortfolioBull = new Model_PortfolioBull();

        $this->Form = new Momesso_Default_Form_Portfolio_Portfolio();
        $this->Form_Email = new Momesso_Default_Form_Portfolio_Email();
        $this->Form_Cover = new Momesso_Default_Form_Portfolio_Cover();
        
        $this->ValidateInputUrl = new Momesso_Plugins_ValidateInputUrl();
        $this->ErrorLog = new Momesso_Plugins_ErrorLog();
        $this->Data = new Momesso_Plugins_Data();

        $this->sessionCustomer = new Zend_Session_Namespace('sessionCustomer');
    }

    public function indexAction() {

    	$this->view->Data = $this->Model->getPortfoliosByCustomerrId();
    }
    
    public function newAction(){
    
    	$this->view->Form = $this->Form;
    
    	if ($this->_request->isPost()) {
    
    		$dados = $this->_request->getPost();
    
    		if ($this->Form->isValid($dados)) {
    
    			unset($dados['Send']);
    
    			try {

                    $dados['customer'] = $this->sessionCustomer->id;
                    $dados['hash'] = md5(date('His'));
    				
    				$idInsert = $this->Model->save($dados);
    
    				$this->view->message = 'Data saved successfully!';
    				$this->view->messageType = 'success';

    			} catch (Zend_Db_Exception $e) {
    
    				$this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
    				$this->view->messageType = 'danger';
    				 
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
    	$r = $this->Model->getPortfolioById($Id);
        
        $this->view->Form = $this->Form;
        
        if ($this->_request->isPost()) {
            
        	$dados = $this->_request->getPost();
            
            if ($this->Form->isValid($dados)) {
                
                unset($dados['Send']);
                
                try {

                    $this->Model->save($dados,$Id);
                                      
                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
                    
                } catch (Zend_Db_Exception $e) {
                    
                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
	                $this->view->messageType = 'danger';
	                   	
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
    	$r = $this->Model->getPortfolioById($Id);
    	
    	$r->delete();
    	$this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }


    public function selectboxAction(){

        $this->view->layout()->disableLayout();

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));

        $this->view->Data = $this->Model->getPortfoliosByCustomerrId();

        $this->view->Bull = $Id;
    }

    public function saveAction(){
        
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();

                try {

                    $this->Model_PortfolioBull->save($dados);
                                      
                    echo 'Data saved successfully!';
                    
                } catch (Zend_Db_Exception $e) {
                    
                    echo 'There was an error, please try again. <br /><br />'.$e->getMessage();
                        
                    $this->ErrorLog->setModulo($this->_request->getControllerName());
                    $this->ErrorLog->setAcao($this->_request->getActionName());
                    $this->ErrorLog->setErro($e->getMessage());
                    $this->ErrorLog->recordLog();
                }

            }
        }        
    }

    /*
    Create a new portfolio by ajax methdd.
    */

    public function createAction(){
        
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();

                try {

                    $bull = $dados['bull'];
                    unset($dados['bull']);

                    $dados['customer']  = $this->sessionCustomer->id;
                    $dados['hash']      = md5(date('His'));
                    
                    $idInsert = $this->Model->save($dados);

                    unset($dados);
                    
                    $dados['portfolio'] = $idInsert;
                    $dados['bull'] = $bull;

                    $this->Model_PortfolioBull->save($dados); 

                    echo 'Data saved successfully!';                 

                    
                } catch (Zend_Db_Exception $e) {
                    
                    echo 'There was an error, please try again. <br /><br />'.$e->getMessage();
                        
                    $this->ErrorLog->setModulo($this->_request->getControllerName());
                    $this->ErrorLog->setAcao($this->_request->getActionName());
                    $this->ErrorLog->setErro($e->getMessage());
                    $this->ErrorLog->recordLog();
                }

            }
        }        
    }

    public function bullAction() {

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $this->view->Data = $this->Model_PortfolioBull->getBullsByPortfolioId($Id);
    }

    public function deletebullAction(){
        
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $r = $this->Model_PortfolioBull->getPortfolioById($Id);
        
        $r->delete();
        $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }

    public function emailAction(){

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $r = $this->Model->getPortfolioById($Id);
        
        $this->view->Form = $this->Form_Email;
        
        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
            
            if ($this->Form_Email->isValid($dados)) {

                unset($dados['Send']);

                $dados['hash'] = $r->hash;

                $this->view->dados = $dados;

                $message = $this->view->render('template/portfolio.phtml');

                $headers = "MIME-Version: 1.1\n";
                $headers .= "Content-type: text/html; charset=utf-8\n";
                $headers .= "From:". $dados['name']." <bruno@spring.bi>\n"; // remetente
                $headers .= "Reply-To: ".$dados['your_email']."\n"; // return-path
                $emailsender = 'bruno@spring.bi';

                $ok = mail($dados['customer_email'], "Bull Search - Portfolio", $message, $headers,"-r".$emailsender);

                if ($ok) {

                    $this->view->message = 'Message sent successfully!';
                    $this->view->messageType = 'success';
                    $this->Form_Email->reset();

                } else {

                    $this->view->message = 'There was an error, please try again';
                    $this->view->messageType = 'danger';
                }    

                }
        }
    }

    public function viewAction(){
        
        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $r = $this->Model->getPortfolioByHash($Id);
        
        $this->view->Data = $r;

        $this->view->Bulls = $this->Model_PortfolioBull->getBullsByPortfolioId($r->cod_portfolio);
    }


    public function coverAction(){

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $r = $this->Model->getPortfolioById($Id);

        if(!is_dir(getcwd().'/default/uploads/portfolio/'.$Id.'/')) {
            mkdir(getcwd().'/default/uploads/portfolio/'.$Id.'/',0777);
        } 
        
        $this->view->Form = $this->Form_Cover;
        
        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
            
            if ($this->Form_Cover->isValid($dados)) {
                
                unset($dados['MAX_FILE_SIZE'], $dados['UPLOAD_IDENTIFIER'], $dados['Save']);
                
                try {

                    $adapter = new Zend_File_Transfer_Adapter_Http();
                    $i = 1;
                        
                    foreach ($adapter->getFileInfo() as $info) {
                       
                        if (!empty($info['name'])) {
                            
                            $adapter = new Zend_File_Transfer_Adapter_Http();
                            $ext = array_reverse(explode(".", strtolower($info['name'])));
                            $arquivo = time().$i.'.' . $ext[0];

                            if ($ext[0]) {
                                    
                                if ($i==1) {
                                   
                                   $imagem['logo_cover'] = $arquivo;

                                } elseif ($i==2) {
                                   
                                    $imagem['image_cover'] = $arquivo;
                                }
                                    
                                $this->Model->save($imagem,$Id);
                    
                                $adapter->addFilter('Rename', array('target' => 'default/uploads/portfolio/'.$Id.'/'.$arquivo, 'overwrite' => true));
                    
                                if ($adapter->receive($info['name'])) {
                                        
                                    $img = WideImage::load(getcwd() . '/default/uploads/portfolio/'.$Id.'/'.$arquivo);
                                    $img->saveToFile(getcwd() . '/default/uploads/portfolio/'.$Id.'/'.$arquivo);
                                }
                            }
                                    
                        }

                        $i++;
                        unset($imagem);
                    }       

                    $imagem['title']            = $dados['title'];
                    $imagem['comments_cover']   = $dados['comments_cover'];

                    $this->Model->save($dados,$Id);
                                      
                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
                    
                } catch (Zend_Db_Exception $e) {
                    
                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'danger';
                        
                    $this->ErrorLog->setModulo($this->_request->getControllerName());
                    $this->ErrorLog->setAcao($this->_request->getActionName());
                    $this->ErrorLog->setErro($e->getMessage());
                    $this->ErrorLog->recordLog();
                }
            }
        } else {
            $this->Form_Cover->populate($r->toArray());
        }
       
    }


    public function printAction(){

        $this->view->layout()->disableLayout();

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));

        $this->view->Portfolio = $this->Model->getPortfolioById($Id);
    }
    
}