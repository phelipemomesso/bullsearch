<?php

class Admin_TouroController extends Zend_Controller_Action {

    public function init() {

        $this->Model = new Model_Touro();
        $this->Model_Video = new Model_TouroVideo();
        $this->Model_TouroCountry = new Model_TouroCountry();
        $this->Model_BullFile = new Model_BullFile();
        $this->Model_BullImage = new Model_BullImage();

        $this->Form = new Momesso_Admin_Form_Touros_Touro();
        $this->Form_Video = new Momesso_Admin_Form_Touros_Video();
        
        $this->Form_UploadBull = new Momesso_Admin_Form_Touros_Excel();
        $this->Form_UploadGoal = new Momesso_Admin_Form_Touros_Goal();

        $this->Form_Images = new Momesso_Admin_Form_Touros_Imagem();
        $this->Form_ImagesEdit = new Momesso_Admin_Form_Touros_ImagemEdit();
        
        $this->ValidateInputUrl = new Momesso_Plugins_ValidateInputUrl();
        $this->ErrorLog = new Momesso_Plugins_ErrorLog();
        $this->Data = new Momesso_Plugins_Data();
        $this->Search = new Momesso_Plugins_ArraySearch();
        $this->ExcelReader = new PHPExcel_Reader_Excel5();

        $this->sessionUsuario = new Zend_Session_Namespace('sessionUsuario');
    }

    public function indexAction() {

        $this->view->Data = $this->Model->getBulls(false,'f1 asc',50);

        /*$paginas = Zend_Paginator::factory($this->Model->getBulls(false,'f1 asc'));
        $paginas->setCurrentPageNumber($this->_getParam('pagina'), 1);
        $paginas->setItemCountPerPage(50);
        $paginas->setPageRange(10);
             
        $this->view->Data = $paginas;*/
    }
    
    public function newAction(){
    
    	$this->view->Form = $this->Form;
    
    	if ($this->_request->isPost()) {
    
    		$dados = $this->_request->getPost();
    
    		if ($this->Form->isValid($dados)) {
    
    			unset($dados['Gravar']);
    
    			try {

                    $dados['f7'] = $this->Data->setData($dados['f7'],1);
    				
    				$idInsert = $this->Model->save($dados);
    
    				$this->view->message = 'Data saved successfully!';
    				$this->view->messageType = 'success';
    
    			} catch (Zend_Db_Exception $e) {
    
    				$this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
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
    	$r = $this->Model->getBullById($Id);
        
        $this->view->Form = $this->Form;
        
        if ($this->_request->isPost()) {
            
        	$dados = $this->_request->getPost();
            
            if ($this->Form->isValid($dados)) {
                
                unset($dados['Gravar']);
                
                try {
                	
                	$dados['f7'] = $this->Data->setData($dados['f7'],1);

                    $this->Model->save($dados,$Id);
                                      
                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
                    
                } catch (Zend_Db_Exception $e) {
                    
                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
	                $this->view->messageType = 'error';
	                   	
	                $this->ErrorLog->setModulo($this->_request->getControllerName());
	                $this->ErrorLog->setAcao($this->_request->getActionName());
	                $this->ErrorLog->setErro($e->getMessage());
	                $this->ErrorLog->recordLog();
                }
            }
        } else {
            $r['birth'] = $this->Data->setData($r['birth'],2);
            $this->Form->populate($r->toArray());
        }
    }
    
    public function deleteAction(){
    	
    	$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    	$r = $this->Model->getBullById($Id);
    	
    	$r->delete();
    	$this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }

    public function uploadbullAction(){

        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
            
            if ($this->Form_UploadBull->isValid($dados)) {
                
                unset($dados['MAX_FILE_SIZE'], $dados['UPLOAD_IDENTIFIER'], $dados['Gravar']);

                try {

                    $adapter = new Zend_File_Transfer_Adapter_Http();
                    $ext = array_reverse(explode(".", strtolower($adapter->getFileName())));
                    $arquivo = md5(date('d-m-Y H:i:s')).'.'.$ext[0];

                    $file['name']   = $dados['nome'];
                    $file['breed']  = $dados['breed'];
                    $file['file']   = $arquivo;
                    $file['type']   = 1;
                    $file['user']   = $this->sessionUsuario->id;

                    $this->Model_BullFile->save($file);
                    
                    unset($file,$dados['nome']);

                    if (isset($arquivo)) {

                        $adapter->addFilter('Rename', array('target' => 'default/uploads/excel-files/bull/' . $arquivo, 'overwrite' => true));

                        if ($adapter->receive()) {
                            
                            $objPHPExcel = $this->ExcelReader->load(getcwd().'/default/uploads/excel-files/bull/'.$arquivo);   

                            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                            
                            $n= count($sheetData);

                            for ($i=2; $i <= $n ; $i++) { 
    
                                $dados['breed']  = $dados['breed'];
                                $dados['f1']     = trim($sheetData[$i]['A']);
                                $dados['f2']     = trim($sheetData[$i]['B']);
                                $dados['f3']     = trim($sheetData[$i]['C']);
                                $dados['f4']     = trim($sheetData[$i]['D']);
                                $dados['f5']     = trim($sheetData[$i]['E']);
                                $dados['f6']     = trim($sheetData[$i]['F']);
                                $dados['f7']     = trim($sheetData[$i]['G']);
                                $dados['f8']     = trim($sheetData[$i]['H']);
                                $dados['f9']     = trim($sheetData[$i]['I']);
                                $dados['f10']    = trim($sheetData[$i]['J']);
                                $dados['f11']    = trim($sheetData[$i]['K']);
                                $dados['f12']    = trim($sheetData[$i]['L']);
                                $dados['f13']    = trim($sheetData[$i]['M']);
                                $dados['f14']    = trim($sheetData[$i]['N']);
                                $dados['f15']    = trim($sheetData[$i]['O']);
                                $dados['f16']    = trim($sheetData[$i]['P']);
                                $dados['f17']    = trim($sheetData[$i]['Q']);
                                $dados['f18']    = trim($sheetData[$i]['R']);
                                $dados['f19']    = trim($sheetData[$i]['S']);
                                $dados['f20']    = trim($sheetData[$i]['T']);
                                $dados['f21']    = trim($sheetData[$i]['U']);
                                $dados['f22']    = trim($sheetData[$i]['V']);
                                $dados['f23']    = trim($sheetData[$i]['W']);
                                $dados['f24']    = trim($sheetData[$i]['X']);
                                $dados['f25']    = trim($sheetData[$i]['Y']);
                                $dados['f26']    = trim($sheetData[$i]['Z']);
                                $dados['f27']    = trim($sheetData[$i]['AA']);
                                $dados['f28']    = trim($sheetData[$i]['AB']);
                                $dados['f29']    = trim($sheetData[$i]['AC']);
                                $dados['f30']    = trim($sheetData[$i]['AD']);
                                $dados['f31']    = trim($sheetData[$i]['AE']);
                                $dados['f32']    = trim($sheetData[$i]['AF']);
                                $dados['f33']    = trim($sheetData[$i]['AG']);
                                $dados['f34']    = trim($sheetData[$i]['AH']);
                                $dados['f35']    = trim($sheetData[$i]['AI']);

                                $this->Model->importFromExcel($dados);

                            }
                        }
                    }

                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
                    
                    $this->view->Form = $this->Form_UploadBull;
                    
                } catch (Zend_Db_Exception $e) {

                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'error';
                        
                    $this->ErrorLog->setModulo($this->_request->getControllerName());
                    $this->ErrorLog->setAcao($this->_request->getActionName());
                    $this->ErrorLog->setErro($e->getMessage());
                    $this->ErrorLog->recordLog();
                
                }
            } else {
                $this->view->Form = $this->Form_UploadBull;
            }
        } else {
            $this->view->Form = $this->Form_UploadBull;
        }

    }

     public function uploadgoalAction(){

        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
            
            if ($this->Form_UploadGoal->isValid($dados)) {
                
                unset($dados['MAX_FILE_SIZE'], $dados['UPLOAD_IDENTIFIER'], $dados['Gravar']);

                try {

                    $adapter = new Zend_File_Transfer_Adapter_Http();
                    $ext = array_reverse(explode(".", strtolower($adapter->getFileName())));
                    $arquivo = md5(date('d-m-Y H:i:s')).'.'.$ext[0];

                    $file['name']       = $dados['nome'];
                    $file['country']    = $dados['country'];
                    $file['file']       = $arquivo;
                    $file['type']       = 2;
                    $file['user']       = $this->sessionUsuario->id;

                    $this->Model_BullFile->save($file);
                    
                    unset($file,$dados['nome']);

                    if (isset($arquivo)) {

                        $adapter->addFilter('Rename', array('target' => 'default/uploads/excel-files/goal/' . $arquivo, 'overwrite' => true));

                        if ($adapter->receive()) {
                            
                            $objPHPExcel = $this->ExcelReader->load(getcwd().'/default/uploads/excel-files/goal/'.$arquivo);   

                            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                            
                            $n= count($sheetData);

                            for ($i=2; $i <= $n ; $i++) { 

                                $bull = $this->Model->getBullByCode($this->Search->find('NAAB2',$i,$sheetData));

                                //if ($bull->f2) {

                                    $dados['bull']          = $bull['cod_bull'];
                                    $dados['country']       = $dados['country'];
                                
                                    $dados['grazing']       = $this->Search->find('Grazing',$i,$sheetData);
                                    $dados['free_stall']    = $this->Search->find('FreeStallBarn',$i,$sheetData);
                                    $dados['dry_lot']       = $this->Search->find('DryLot',$i,$sheetData);
                                    
                                    $dados['volume']        = $this->Search->find('Vol',$i,$sheetData);
                                    $dados['feed']          = $this->Search->find('FE',$i,$sheetData);
                                    $dados['milk']          = $this->Search->find('MC',$i,$sheetData);
                                    $dados['fertility']     = $this->Search->find('F',$i,$sheetData);
                                    $dados['conformation']  = $this->Search->find('C',$i,$sheetData);
                                    $dados['durability']    = $this->Search->find('DUR',$i,$sheetData);
                                    $dados['robot']         = $this->Search->find('R',$i,$sheetData);
                                    
                                    $dados['visible']       = $this->Search->find('Visible',$i,$sheetData);

                                    $this->Model_TouroCountry->importFromExcel($dados);
                                //}

                            }
                        }
                    }

                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
                    
                    $this->view->Form = $this->Form_UploadGoal;
                    
                } catch (Zend_Db_Exception $e) {

                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'error';
                        
                    $this->ErrorLog->setModulo($this->_request->getControllerName());
                    $this->ErrorLog->setAcao($this->_request->getActionName());
                    $this->ErrorLog->setErro($e->getMessage());
                    $this->ErrorLog->recordLog();
                
                }
            } else {
                $this->view->Form = $this->Form_UploadGoal;
            }
        } else {
            $this->view->Form = $this->Form_UploadGoal;
        }

    }


    public function informationAction(){
        
        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));

        $r = $this->Model->getBullById($Id);
        $this->view->Bull = $r;
        $this->view->Info = $this->Model_TouroCountry->getBullById($r['cod_bull']);

        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
      
            $dados['grazing']       = isset($dados['grazing']) ? $dados['grazing'] : 0;
            $dados['free_stall']    = isset($dados['free_stall']) ? $dados['free_stall'] : 0;
            $dados['dry_lot']       = isset($dados['dry_lot']) ? $dados['dry_lot'] : 0;


            $dados['volume']        = isset($dados['volume']) ? $dados['volume'] : 0;
            $dados['feed']          = isset($dados['feed']) ? $dados['feed'] : 0;
            $dados['milk']          = isset($dados['milk']) ? $dados['milk'] : 0;
            $dados['fertility']     = isset($dados['fertility']) ? $dados['fertility'] : 0;
            $dados['conformation']  = isset($dados['conformation']) ? $dados['conformation'] : 0;
            $dados['durability']    = isset($dados['durability']) ? $dados['durability'] : 0;
            $dados['robot']         = isset($dados['robot']) ? $dados['robot'] : 0;

            $dados['bull']          = $r['cod_bull'];
            $dados['country']       = $this->sessionUsuario->country;

            $this->Model_TouroCountry->save($dados);

            $this->view->message = 'Data saved successfully!';
            $this->view->messageType = 'success';

        }
    }


    /*
        Videos
    */

    public function videoAction(){

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));

        $this->view->Touro = $Id;
        $this->view->Data = $this->Model_Video->getVideosByBull('bull = '.$Id,'name asc');
    }

    public function newvideoAction(){

        $this->view->Form = $this->Form_Video;
    
        if ($this->_request->isPost()) {
    
            $dados = $this->_request->getPost();
    
            if ($this->Form_Video->isValid($dados)) {
    
                unset($dados['Gravar']);
    
                try {
                    
                    $dados['bull'] = $this->ValidateInputUrl->validateInput($this->_getParam('id'));

                    $idInsert = $this->Model_Video->save($dados);
    
                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
    
                } catch (Zend_Db_Exception $e) {
    
                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'error';
                     
                    $this->ErrorLog->setModulo($this->_request->getControllerName());
                    $this->ErrorLog->setAcao($this->_request->getActionName());
                    $this->ErrorLog->setErro($e->getMessage());
                    $this->ErrorLog->recordLog();
                }
            }
        }

    }

    public function editvideoAction(){

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $r = $this->Model_Video->getVideoById($Id);
        
        $this->view->Form = $this->Form_Video;
        
        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
            
            if ($this->Form_Video->isValid($dados)) {
                
                unset($dados['Gravar']);
                
                try {

                    $this->Model_Video->save($dados,$Id);
                                      
                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
                    
                } catch (Zend_Db_Exception $e) {
                    
                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'error';
                        
                    $this->ErrorLog->setModulo($this->_request->getControllerName());
                    $this->ErrorLog->setAcao($this->_request->getActionName());
                    $this->ErrorLog->setErro($e->getMessage());
                    $this->ErrorLog->recordLog();
                }
            }
        } else {
            $this->Form_Video->populate($r->toArray());
        }
    }

    public function deletevideoAction(){
        
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        
        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $r = $this->Model_Video->getVideoById($Id);
        
        $r->delete();
        $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }

    /*
        Images
    */

    public function imageAction(){

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));

        $this->view->Touro = $Id;
        $this->view->Data = $this->Model_BullImage->getImagesByBull('bull = '.$Id,'name asc');
    }

    public function editimageAction() {
    
        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
    
        $r = $this->Model_BullImage->getImageById($Id);
        $this->view->Form = $this->Form_ImagesEdit;
    
        $this->Form_ImagesEdit->getElement('imagem')->setRequired(false);
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
    
        if ($this->_request->isPost()) {
    
            $dados = $this->_request->getPost();
    
            if ($this->Form_ImagesEdit->isValid($dados)) {
    
                unset($dados['MAX_FILE_SIZE'], $dados['UPLOAD_IDENTIFIER'], $dados['Save']);
    
                try {
    
                    $this->Model_BullImage->save($dados,$Id);
    
                    $adapter = new Zend_File_Transfer_Adapter_Http();
                    @$ext = array_reverse(explode(".", strtolower($adapter->getFileName())));
                    $arquivo = time().'.' . $ext[0];
                    
                    $idInsert = $this->Model_BullImage->save($dados,$Id);
    
                    if ($ext[0]) {
    
                        if (!empty($r['image'])) {
                            $file = getcwd() . '/default/uploads/bulls/'.$r['bull'].'/'.$r['image'];
                            
                            if (file_exists($file)) {
                                unlink($file);
                            }
                        }
                        
                        $adapter->addFilter('Rename', array('target' => 'default/uploads/bulls/'.$r['bull'].'/'.$arquivo, 'overwrite' => true));
                        
                        if ($adapter->receive()) {
                            $this->Model_BullImage->save(array('image' => $arquivo),$Id);
                            $img = WideImage::load(getcwd() . '/default/uploads/bulls/'.$r['bull'].'/'.$arquivo);
                            $img->resize(640,480,'outside')->saveToFile(getcwd() . '/default/uploads/bulls/'.$r['bull'].'/'.$arquivo);
                            //$img->resize(150,113,'outside', 'down')->saveToFile(getcwd() . '/default/uploads/bulls/'.$r['bull'].'/thumbs/'.$arquivo);
                        }
                    }
    
                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
    
                } catch (Zend_Db_Exception $e) {
                     
                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'error';
    
                    $this->ErrorLog->setModulo($this->_request->getControllerName());
                    $this->ErrorLog->setAcao($this->_request->getActionName());
                    $this->ErrorLog->setErro($e->getMessage());
                    $this->ErrorLog->recordLog();
    
                }
            }
        } else {
            if (!empty($r['image'])) {
    
                $fileCaminho = $baseUrl.'/default/uploads/bulls/'.$r['bull'].'/'.$r['image'];
                $file = '<img src="' . $fileCaminho . '" alt="' . $r['image'] . '" style="width: 300px; margin-top: 5px;" />';
                $this->Form_ImagesEdit->setDefault('foto', $file);
            }
    
            $this->Form_ImagesEdit->populate($r->toArray());
        }
    }
    
    
    
    public function newimageAction(){
         
        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
         
        if(!empty($Id)){

            if(!is_dir(getcwd().'/default/uploads/bulls/'.$Id.'/')) {
                mkdir(getcwd().'/default/uploads/bulls/'.$Id.'/',0777);
            }    
             
            $this->view->Form = $this->Form_Images;
            
            if ($this->_request->isPost()) {
            
                $dados = $this->_request->getPost();

                 //print_r($dados);
            
                if ($this->Form_Images->isValid($dados)) {
            
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
                                    
                                    $imagem['bull']  = $Id;
                                    $imagem['name']  = $dados['nome'.$i];
                                    $imagem['type']  = $dados['type'.$i];
                                    
                                    $idInsert = $this->Model_BullImage->save($imagem);
                    
                                    $adapter->addFilter('Rename', array('target' => 'default/uploads/bulls/'.$Id.'/'.$arquivo, 'overwrite' => true));
                    
                                     if ($adapter->receive($info['name'])) {
                                        $this->Model_BullImage->save(array('image' => $arquivo),$idInsert);
                                        $img = WideImage::load(getcwd() . '/default/uploads/bulls/'.$Id.'/'.$arquivo);
                                        $img->resize(640,480,'outside')->saveToFile(getcwd() . '/default/uploads/bulls/'.$Id.'/'.$arquivo);
                                        //$img->resize(150,113,'outside', 'down')->saveToFile(getcwd() . '/default/uploads/bulls/'.$Id.'/thumbs/'.$arquivo);
                                    }
                               }
                                    
                            }

                            $i++;
                            unset($imagem);
                        }       
            
                        $this->view->message = 'Data saved successfully!';
                        $this->view->messageType = 'success';
            
                    } catch (Zend_Db_Exception $e) {
            
                        $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                        $this->view->messageType = 'error';
                            
                        $this->ErrorLog->setModulo($this->_request->getControllerName());
                        $this->ErrorLog->setAcao($this->_request->getActionName());
                        $this->ErrorLog->setErro($e->getMessage());
                        $this->ErrorLog->recordLog();
                    }
                }
            }
    
        } else {
    
            $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    
        }
    }
    
    public function deleteimageAction(){
         
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
         
        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $r = $this->Model_BullImage->getImageById($Id);
         
        if (!empty($r['image'])) {
            $file = getcwd() . '/default/uploads/bulls/'.$r['bull'].'/'.$r['image'];
            //$fileThumbs = getcwd() . '/default/uploads/bulls/'.$r['bull'].'/thumbs/'.$r['image'];
            
            if (file_exists($file)) {
                unlink($file);
            }
        }
         
        $r->delete();
        $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }
    
   }