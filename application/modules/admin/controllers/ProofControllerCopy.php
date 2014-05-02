<?php

class Admin_ProofController extends Zend_Controller_Action {

    public function init() {

        $this->Model = new Model_Touro();
        $this->Model_BullFile = new Model_BullFile();
        $this->Model_ProofHolstein = new Model_ProofHolstein();

        $this->Form = new Momesso_Admin_Form_Proof_Proof();
        
        $this->ValidateInputUrl = new Momesso_Plugins_ValidateInputUrl();
        $this->ErrorLog = new Momesso_Plugins_ErrorLog();
        $this->Data = new Momesso_Plugins_Data();
        $this->Search = new Momesso_Plugins_ArraySearch();
        $this->ExcelReader = new PHPExcel_Reader_Excel5();

        $this->sessionUsuario = new Zend_Session_Namespace('sessionUsuario');
    }

    public function indexAction() {

        $this->view->Data = $this->Model_BullFile->getFilesByType(3);

        /*$paginas = Zend_Paginator::factory($this->Model->getBulls(false,'f1 asc'));
        $paginas->setCurrentPageNumber($this->_getParam('pagina'), 1);
        $paginas->setItemCountPerPage(50);
        $paginas->setPageRange(10);
             
        $this->view->Data = $paginas;*/
    }

    public function holsteinAction(){

        if ($this->_request->isPost()) {
            
            $dados = $this->_request->getPost();
            
            if ($this->Form->isValid($dados)) {
                
                unset($dados['MAX_FILE_SIZE'], $dados['UPLOAD_IDENTIFIER'], $dados['Gravar']);

                try {

                    $adapter = new Zend_File_Transfer_Adapter_Http();
                    $ext = array_reverse(explode(".", strtolower($adapter->getFileName())));
                    $arquivo = md5(date('d-m-Y H:i:s')).'.'.$ext[0];

                    $file['name']   = $dados['nome'];
                    $file['breed']  = 2;
                    $file['file']   = $arquivo;
                    $file['type']   = 3;
                    $file['user']   = $this->sessionUsuario->id;

                    $this->Model_BullFile->save($file);
                    
                    unset($file,$dados['nome']);

                    if (isset($arquivo)) {

                        $adapter->addFilter('Rename', array('target' => 'default/uploads/excel-files/proof/' . $arquivo, 'overwrite' => true));

                        if ($adapter->receive()) {
                            
                            $objPHPExcel = $this->ExcelReader->load(getcwd().'/default/uploads/excel-files/proof/'.$arquivo);   

                            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                            
                            $n= count($sheetData);

                            for ($i=2; $i <= $n ; $i++) { 

                                $bull = $this->Model->getBullByCode($sheetData[$i]['C']);

                                if ($bull->f2) {

                                    $dados['bull']   = $bull['cod_bull'];
    
                                    $dados['f1']     = $this->Search->find('Expr1',$i,$sheetData);
                                    $dados['f2']     = $this->Search->find('Expr2',$i,$sheetData);
                                    $dados['f3']     = $this->Search->find('NumDtrsDPR',$i,$sheetData);
                                    $dados['f4']     = $this->Search->find('NumHerdDPR',$i,$sheetData);
                                    $dados['f5']     = $this->Search->find('Expr1006',$i,$sheetData);
                                    $dados['f6']     = $this->Search->find('Type',$i,$sheetData);
                                    $dados['f7']     = $this->Search->find('UDC',$i,$sheetData);
                                    $dados['f8']     = $this->Search->find('UDC_Comment',$i,$sheetData);
                                    $dados['f9']     = $this->Search->find('FLC',$i,$sheetData);
                                    $dados['f10']    = $this->Search->find('FLC_Comment',$i,$sheetData);
                                    $dados['f11']    = $this->Search->find('STA',$i,$sheetData);
                                    $dados['f12']    = $this->Search->find('STADesc',$i,$sheetData);
                                    $dados['f13']    = $this->Search->find('STR',$i,$sheetData);
                                    $dados['f14']    = $this->Search->find('STRDesc',$i,$sheetData);
                                    $dados['f15']    = $this->Search->find('BD',$i,$sheetData);
                                    $dados['f16']    = $this->Search->find('BDDesc',$i,$sheetData);
                                    $dados['f17']    = $this->Search->find('DF',$i,$sheetData);
                                    $dados['f18']    = $this->Search->find('DFDesc',$i,$sheetData);
                                    $dados['f19']    = $this->Search->find('RA',$i,$sheetData);
                                    $dados['f20']    = $this->Search->find('RADesc',$i,$sheetData);
                                    $dados['f21']    = $this->Search->find('TW',$i,$sheetData);
                                    $dados['f22']    = $this->Search->find('TWDesc',$i,$sheetData);
                                    $dados['f23']    = $this->Search->find('RLS',$i,$sheetData);
                                    $dados['f24']    = $this->Search->find('RLSDesc',$i,$sheetData);
                                    $dados['f25']    = $this->Search->find('RLRV',$i,$sheetData);
                                    $dados['f26']    = $this->Search->find('RLRVDesc',$i,$sheetData);
                                    $dados['f27']    = $this->Search->find('FA',$i,$sheetData);
                                    $dados['f28']    = $this->Search->find('FADesc',$i,$sheetData);
                                    $dados['f29']    = $this->Search->find('FLS',$i,$sheetData);
                                    $dados['f30']    = $this->Search->find('FLSDesc',$i,$sheetData);
                                    $dados['f31']    = $this->Search->find('FUA',$i,$sheetData);
                                    $dados['f32']    = $this->Search->find('FUADesc',$i,$sheetData);
                                    $dados['f33']    = $this->Search->find('RUH',$i,$sheetData);
                                    $dados['f34']    = $this->Search->find('RUHDesc',$i,$sheetData);
                                    $dados['f35']    = $this->Search->find('RUW',$i,$sheetData);
                                    $dados['f36']    = $this->Search->find('RUWDesc',$i,$sheetData);
                                    $dados['f37']    = $this->Search->find('UC',$i,$sheetData);
                                    $dados['f38']    = $this->Search->find('UCDesc',$i,$sheetData);
                                    $dados['f39']    = $this->Search->find('UD',$i,$sheetData);
                                    $dados['f40']    = $this->Search->find('UDDesc',$i,$sheetData);
                                    $dados['f41']    = $this->Search->find('FTP',$i,$sheetData);
                                    $dados['f42']    = $this->Search->find('FTPDesc',$i,$sheetData);
                                    $dados['f43']    = $this->Search->find('RTP',$i,$sheetData);
                                    $dados['f44']    = $this->Search->find('RTPDesc',$i,$sheetData);
                                    $dados['f45']    = $this->Search->find('TL',$i,$sheetData);
                                    $dados['f46']    = $this->Search->find('TLDesc',$i,$sheetData);


                                    $dados['f47']    = $this->Search->find('Source',$i,$sheetData);
                                    $dados['f48']    = $this->Search->find('EvalDate',$i,$sheetData);
                                    $dados['f49']    = $this->Search->find('ProdDtrs',$i,$sheetData);
                                    $dados['f50']    = $this->Search->find('ProdHerds',$i,$sheetData);
                                    $dados['f51']    = $this->Search->find('TPI/PTI',$i,$sheetData);
                                    $dados['f52']    = $this->Search->find('Milk',$i,$sheetData);
                                    $dados['f53']    = $this->Search->find('Milk%',$i,$sheetData);
                                    $dados['f54']    = $this->Search->find('MilkREL',$i,$sheetData);
                                    $dados['f55']    = $this->Search->find('Pro',$i,$sheetData);
                                    $dados['f56']    = $this->Search->find('Pro%',$i,$sheetData);
                                    $dados['f57']    = $this->Search->find('ProRel',$i,$sheetData);
                                    $dados['f58']    = $this->Search->find('Fat',$i,$sheetData);
                                    $dados['f59']    = $this->Search->find('Fat%',$i,$sheetData);
                                    $dados['f60']    = $this->Search->find('MFRel',$i,$sheetData);
                                    $dados['f61']    = $this->Search->find('NM',$i,$sheetData);
                                    $dados['f62']    = $this->Search->find('NM%',$i,$sheetData);
                                    $dados['f63']    = $this->Search->find('NMREL',$i,$sheetData);
                                    $dados['f64']    = $this->Search->find('CM$',$i,$sheetData);
                                    $dados['f65']    = $this->Search->find('CM%',$i,$sheetData);
                                    $dados['f66']    = $this->Search->find('CMREL',$i,$sheetData);
                                    

                                    $dados['f67']    = $this->Search->find('CEDiff',$i,$sheetData);
                                    $dados['f68']    = $this->Search->find('CERel',$i,$sheetData);
                                    $dados['f69']    = $this->Search->find('PctDifficultyMGS',$i,$sheetData);
                                    $dados['f70']    = $this->Search->find('ReliabilityMGS',$i,$sheetData);
                                    $dados['f71']    = $this->Search->find('PctSSB',$i,$sheetData);
                                    $dados['f72']    = $this->Search->find('RelSSB',$i,$sheetData);
                                    $dados['f73']    = $this->Search->find('PctDSB',$i,$sheetData);
                                    $dados['f74']    = $this->Search->find('RelDSB',$i,$sheetData);
                                    $dados['f75']    = $this->Search->find('DPRPTA',$i,$sheetData);
                                    $dados['f76']    = $this->Search->find('DPRRel',$i,$sheetData);
                                    $dados['f77']    = $this->Search->find('SCS',$i,$sheetData);
                                    $dados['f78']    = $this->Search->find('SCSRel',$i,$sheetData);
                                    $dados['f79']    = $this->Search->find('PL',$i,$sheetData);
                                    $dados['f80']    = $this->Search->find('PLRel',$i,$sheetData);

                                    $this->Model_ProofHolstein->save($dados);
                                }    

                            }
                        }
                    }

                    $this->view->message = 'Data saved successfully!';
                    $this->view->messageType = 'success';
                    
                    $this->view->Form = $this->Form;
                    
                } catch (Zend_Db_Exception $e) {

                    $this->view->message = 'There was an error, please try again. <br /><br />'.$e->getMessage();
                    $this->view->messageType = 'error';
                        
                    $this->ErrorLog->setModulo($this->_request->getControllerName());
                    $this->ErrorLog->setAcao($this->_request->getActionName());
                    $this->ErrorLog->setErro($e->getMessage());
                    $this->ErrorLog->recordLog();
                
                }
            } else {
                $this->view->Form = $this->Form;
            }
        } else {
            $this->view->Form = $this->Form;
        }

    }
    
   }