<?php

class Admin_TouroController extends Zend_Controller_Action {

    public function init() {

        $this->Model                    = new Model_Touro();
        $this->Model_Video              = new Model_TouroVideo();
        $this->Model_TouroCountry       = new Model_TouroCountry();
        $this->Model_BullFile           = new Model_BullFile();
        $this->Model_BullImage          = new Model_BullImage();
        $this->Model_BullComment        = new Model_BullComment();

        $this->Form                     = new Momesso_Admin_Form_Touros_Touro();
        $this->Form_Video               = new Momesso_Admin_Form_Touros_Video();
        $this->Form_Comment             = new Momesso_Admin_Form_Touros_Comment();
        
        $this->Form_UploadBull          = new Momesso_Admin_Form_Touros_Excel();
        $this->Form_UploadGoal          = new Momesso_Admin_Form_Touros_Goal();

        $this->Form_Images              = new Momesso_Admin_Form_Touros_Imagem();
        $this->Form_ImagesEdit          = new Momesso_Admin_Form_Touros_ImagemEdit();
        
        $this->ValidateInputUrl         = new Momesso_Plugins_ValidateInputUrl();
        $this->ErrorLog                 = new Momesso_Plugins_ErrorLog();
        $this->Data                     = new Momesso_Plugins_Data();
        $this->Search                   = new Momesso_Plugins_ArraySearch();
        $this->ExcelReader              = new PHPExcel_Reader_Excel5();

        $this->sessionUsuario           = new Zend_Session_Namespace('sessionUsuario');
    }

    public function indexAction() {

        //$this->view->Data = $this->Model->getBulls(false,'f1 asc');

        $paginas = Zend_Paginator::factory($this->Model->getBulls(false,'f1 asc'));
        $paginas->setCurrentPageNumber($this->_getParam('pagina'), 1);
        $paginas->setItemCountPerPage(50);
        $paginas->setPageRange(10);
             
        $this->view->Data = $paginas;
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
                	
                	//$dados['f7'] = $this->Data->setData($dados['f7'],1);

                    $dados['bull'] = $Id;
                    
                    $this->Model_TouroCountry->save($dados);
                                      
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
            //$r['f7'] = $this->Data->setData($r['f7'],2);
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
                                
                                $dados['f1']     = $this->Search->find('ShortName',$i,$sheetData);
                                $dados['f2']     = $this->Search->find('NAAB.',$i,$sheetData);
                                $dados['f3']     = $this->Search->find('RegName',$i,$sheetData);
                                $dados['f4']     = $this->Search->find('Reg',$i,$sheetData);
                                $dados['f5']     = $this->Search->find('HOAns',$i,$sheetData);
                                $dados['f6']     = $this->Search->find('FinalScore',$i,$sheetData);
                               // $dados['f7']     = $this->Data->setData($this->Search->find('BirthDate',$i,$sheetData),1);
                                $dados['f8']     = $this->Search->find('Gencodes',$i,$sheetData);
                                $dados['f9']     = $this->Search->find('Hyplo',$i,$sheetData);
                                $dados['f10']    = $this->Search->find('AAA',$i,$sheetData);
                                $dados['f11']    = $this->Search->find('DMS',$i,$sheetData);
                                $dados['f12']    = $this->Search->find('EFIPC',$i,$sheetData);
                                $dados['f13']    = $this->Search->find('Bredby',$i,$sheetData);
                                $dados['f14']    = $this->Search->find('Symbols',$i,$sheetData);
                                $dados['f15']    = $this->Search->find('SireRegName',$i,$sheetData);
                                $dados['f16']    = $this->Search->find('SireReg',$i,$sheetData);
                                $dados['f17']    = $this->Search->find('SireFinalScore',$i,$sheetData);
                                $dados['f18']    = $this->Search->find('DamRegName',$i,$sheetData);
                                $dados['f19']    = $this->Search->find('DamReg',$i,$sheetData);
                                $dados['f20']    = $this->Search->find('DamFinalScore',$i,$sheetData);
                                $dados['f21']    = $this->Search->find('DamOfMerit',$i,$sheetData);
                                $dados['f22']    = $this->Search->find('DamAgeAtCalf',$i,$sheetData);
                                $dados['f23']    = $this->Search->find('DamTimesMilked',$i,$sheetData);
                                $dados['f24']    = $this->Search->find('DamRecordLength',$i,$sheetData);
                                $dados['f25']    = $this->Search->find('DamMilk',$i,$sheetData);
                                $dados['f26']    = $this->Search->find('DamFat%',$i,$sheetData);
                                $dados['f27']    = $this->Search->find('DamFat',$i,$sheetData);
                                $dados['f28']    = $this->Search->find('PAProt',$i,$sheetData);
                                $dados['f29']    = $this->Search->find('DamPro',$i,$sheetData);
                                $dados['f30']    = $this->Search->find('MGSRegName',$i,$sheetData);
                                $dados['f31']    = $this->Search->find('MGSReg',$i,$sheetData);
                                $dados['f32']    = $this->Search->find('MGSFinalScore',$i,$sheetData);
                                $dados['f33']    = $this->Search->find('ThirdSireRegName',$i,$sheetData);
                                $dados['f34']    = $this->Search->find('ThirdSireRegNum',$i,$sheetData);
                                $dados['f35']    = $this->Search->find('ThirdSireFinalScore',$i,$sheetData);
                                $dados['f36']    = $this->Search->find('BovineID',$i,$sheetData);


                                $this->Model->importFromExcel($dados);

                                /*echo '<pre>';
                                print_r($dados);
                                echo '</pre>';
                                */
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

                                $bull = $this->Model->getBullByCode($this->Search->find('BovineID',$i,$sheetData));

                                //print_r($bull);

                                if ($bull->f36) {

                                    $dados['bull']                  = $bull['cod_bull'];
                                    $dados['country']               = $dados['country'];
                                
                                    $dados['daughter_proven']       = $this->Search->find('D',$i,$sheetData);
                                    $dados['genomic_young_sires']   = $this->Search->find('G',$i,$sheetData);
                                    
                                    $dados['volume']                = $this->Search->find('MilkSTD',$i,$sheetData);
                                    $dados['milk_protein']          = $this->Search->find('ProSTD',$i,$sheetData);
                                    $dados['milk_fat']              = $this->Search->find('FatSTD',$i,$sheetData);
                                    $dados['longevity']             = $this->Search->find('PLSTD',$i,$sheetData);
                                    $dados['daughter_fertility']    = $this->Search->find('DPRSTD',$i,$sheetData);

                                    $dados['breeders_choice']       = $this->Search->find('BreedersChoice',$i,$sheetData);
                                    $dados['calving_ease']          = $this->Search->find('CalvingEase',$i,$sheetData);
                                    $dados['diamond_sire']          = $this->Search->find('DiamondSire',$i,$sheetData);
                                    $dados['durabulls']             = $this->Search->find('Durabulls',$i,$sheetData);
                                    $dados['judges_choice']         = $this->Search->find('JudgesChoice',$i,$sheetData);
                                    $dados['feed_efficieny']        = $this->Search->find('FeedEfficiency',$i,$sheetData);
                                    $dados['pregnancy_king']        = $this->Search->find('PregnancyKing',$i,$sheetData);
                                    $dados['redwhite']              = $this->Search->find('RedWhite',$i,$sheetData);
                                    $dados['rsg']                   = $this->Search->find('RSG',$i,$sheetData);
                                    $dados['sexation']              = $this->Search->find('Sexation',$i,$sheetData);
                                    $dados['sexation_only']         = $this->Search->find('SexationOnly',$i,$sheetData);

                                    
                                    $visible = $this->Search->find('Visible',$i,$sheetData);

                                    if ($visible != null) {
                                        $dados['visible'] = $visible;
                                    }

                                    $this->Model_TouroCountry->importFromExcel($dados);
                                }

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
        $this->view->Data = $this->Model_Video->getVideosByBullId($Id);
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
        (int) $Bull = $this->ValidateInputUrl->validateInput($this->_getParam('bull'));
    
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

                    $this->_redirect('/admin/touro/image/id/'.$Bull);
    
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

                        $this->_redirect('/admin/touro/image/id/'.$Id);
            
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

    public function commentAction(){

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));

        $this->view->Touro = $Id;
        $this->view->Data = $this->Model_BullComment->getCommentsByBull($Id);
    }

    public function newcommentAction(){
    
        $this->view->Form = $this->Form_Comment;
    
        if ($this->_request->isPost()) {
    
            $dados = $this->_request->getPost();
    
            if ($this->Form_Comment->isValid($dados)) {
    
                unset($dados['Save']);
    
                try {

                    (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));

                    $dados['bull'] = $Id;
                    
                    $idInsert = $this->Model_BullComment->save($dados);
    
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

    public function editcommentAction(){

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $r = $this->Model_BullComment->getCommentById($Id);
        
        $this->view->Form = $this->Form_Comment;
        
        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
            
            if ($this->Form_Comment->isValid($dados)) {
                
                unset($dados['Save']);
                
                try {
                    
                    $this->Model_BullComment->save($dados,$Id);
                                      
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

            $this->Form_Comment->populate($r->toArray());
        }
    }

    public function deletecommentAction(){
         
        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
         
        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        $r = $this->Model_BullComment->getCommentById($Id);
         
        $r->delete();
        $this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
    }
    
   }