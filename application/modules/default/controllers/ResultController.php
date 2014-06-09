<?php

class ResultController extends Zend_Controller_Action {

	public function init(){

		$this->Model_Breed          = new Model_Breed();
		$this->Model_Touro          = new Model_Touro();
        $this->Model_BullImage      = new Model_BullImage();
        $this->Model_TouroVideo     = new Model_TouroVideo();
        $this->Model_SearchHistory  = new Model_SearchHistory(); 
        $this->Model_BullComment    = new Model_BullComment();
        $this->Model_Portfolio      = new Model_Portfolio();    

        $this->Search               = new Momesso_Plugins_ArraySearch();
        $this->ExcelReader          = new PHPExcel_Reader_Excel5();
        $this->Data                 = new Momesso_Plugins_Data();
        $this->Model_TouroCountry   = new Model_TouroCountry();
        $this->Model_ProofHolstein  = new Model_ProofHolstein();

        $this->Session              = new Momesso_Plugins_SessionWrapper();

        $this->ValidateInputUrl     = new Momesso_Plugins_ValidateInputUrl();
	}
    

    public function indexAction() {
        
    	$this->view->headTitle()->append('Result');

        $this->view->Records    = $this->ValidateInputUrl->validateInput($this->_getParam('results'));
        $saveSearchHistory      = $this->ValidateInputUrl->validateInput($this->_getParam('save'));

    	$res = $this->createSql();
        
        // Verifica a flag para gravar a busca

        if ($saveSearchHistory == 1) {
            $this->createSearchHistory();
        }
    		 
    	$this->view->Data = $res;
    	$this->view->Total = count($res);

        $sess = $this->Session->getInstance();
        $bullHistory = $sess->getSessVar('bullHistory');
        $sess->emptySess();

    }

    public function listAction() {
        
    	$this->view->headTitle()->append('Result List');

    	$res = $this->createSql();
    	$this->view->Data = $res;
    	$this->view->Total = count($res);
    }

     public function coverAction() {
        
        $this->view->headTitle()->append('Result List');

        $res = $this->createSql();
        $this->view->Data = $res;
        $this->view->Total = count($res);
    }

    public function detailscoverAction() {

        $this->view->layout()->disableLayout();

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('bull'));

        $bull = $this->Model_Touro->getBullById($Id);
        $this->view->Data = $bull;

        $ImageBull = $this->Model_BullImage->getImageBull($bull->cod_bull);

        $this->view->ImageBull          = $ImageBull;
        $this->view->ImagesDaughter     = $this->Model_BullImage->getImagesDaughter($bull->cod_bull);
        $this->view->Videos             = $this->Model_TouroVideo->getVideosByBullId($bull->cod_bull);

        $this->view->Proof = $this->Model_ProofHolstein->getProofByBullId($bull->cod_bull);

        $this->view->Comments = $this->Model_BullComment->getCommentsByBull($bull->cod_bull);

        $this->createBullHistory($bull);
        $this->createAlsoViewed($bull);
    }

    public function detailsAction() {
        
    	$this->view->headTitle()->append('Details');

    	(int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));

    	$bull = $this->Model_Touro->getBullById($Id);
        $this->view->Data = $bull;

        $ImageBull = $this->Model_BullImage->getImageBull($bull->cod_bull);

        $this->view->ImageBull          = $ImageBull;
        $this->view->ImagesDaughter     = $this->Model_BullImage->getImagesDaughter($bull->cod_bull);
        $this->view->Videos             = $this->Model_TouroVideo->getVideosByBullId($bull->cod_bull);

        $this->view->Proof = $this->Model_ProofHolstein->getProofByBullId($bull->cod_bull);

        $this->view->Comments = $this->Model_BullComment->getCommentsByBull($bull->cod_bull);

        $this->createBullHistory($bull);
        $this->createAlsoViewed($bull);

    }	


    public function checkboxAction(){

        $this->view->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        if ($this->getRequest()->isXmlHttpRequest()) {
            
            if ($this->_request->isPost ()) {

                $dados = $this->_request->getPost ();

                $sess = $this->Session->getInstance();
                $sess->emptySess();
                $sess->setSessVar('sessionCompare',$dados);
            }
        }        
    }

    public function compareAction(){

        $sess = $this->Session->getInstance();
        $sessionCompare = $sess->getSessVar('sessionCompare');

        if (count($sessionCompare['bulls'])==3) {
          
             $this->view->Bull1         = $this->Model_Touro->getBullById($sessionCompare['bulls'][0]);
             $this->view->Proof1        = $this->Model_ProofHolstein->getProofByBullId($sessionCompare['bulls'][0]);
             $this->view->ImageBull1    = $this->Model_BullImage->getImageBull($sessionCompare['bulls'][0]);

             $this->view->Bull2         = $this->Model_Touro->getBullById($sessionCompare['bulls'][1]);
             $this->view->Proof2        = $this->Model_ProofHolstein->getProofByBullId($sessionCompare['bulls'][1]);
             $this->view->ImageBull2    = $this->Model_BullImage->getImageBull($sessionCompare['bulls'][1]);

             $this->view->Bull3         = $this->Model_Touro->getBullById($sessionCompare['bulls'][2]);
             $this->view->Proof3        = $this->Model_ProofHolstein->getProofByBullId($sessionCompare['bulls'][2]);
             $this->view->ImageBull3    = $this->Model_BullImage->getImageBull($sessionCompare['bulls'][2]); 

             $this->view->Total = 3;

             //$sess->emptySess();    
                    
         } elseif (count($sessionCompare['bulls'])==2) {
          
             $this->view->Bull1         = $this->Model_Touro->getBullById($sessionCompare['bulls'][0]);
             $this->view->Proof1        = $this->Model_ProofHolstein->getProofByBullId($sessionCompare['bulls'][0]);
             $this->view->ImageBull1    = $this->Model_BullImage->getImageBull($sessionCompare['bulls'][0]);

             $this->view->Bull2         = $this->Model_Touro->getBullById($sessionCompare['bulls'][1]);
             $this->view->Proof2        = $this->Model_ProofHolstein->getProofByBullId($sessionCompare['bulls'][1]);
             $this->view->ImageBull2    = $this->Model_BullImage->getImageBull($sessionCompare['bulls'][1]);

             $this->view->Total = 2;

             //$sess->emptySess();    

        } 

    }

    public function printAction(){

        $this->view->layout()->disableLayout();

        (int) $Id = $this->ValidateInputUrl->validateInput($this->_getParam('id'));
        (int) $Comment = $this->ValidateInputUrl->validateInput($this->_getParam('comments'));

        $bull = $this->Model_Touro->getBullById($Id);
        $this->view->Data = $bull;

        $this->view->ImageBull          = $this->Model_BullImage->getImageBull($bull->cod_bull);
        $this->view->ImagesDaughter     = $this->Model_BullImage->getImagesDaughter($bull->cod_bull);
        $this->view->Videos             = $this->Model_TouroVideo->getVideosByBullId($bull->cod_bull);

        $this->view->Proof = $this->Model_ProofHolstein->getProofByBullId($bull->cod_bull);

        if ($Comment == 1) {
            $this->view->Comments = $this->Model_BullComment->getCommentsByBull($bull->cod_bull);
        }
    }




    private function createSql(){

    	$sessionStep = new Zend_Session_Namespace('sessionStep');

    	$sql = '';
    	
    	if ($sessionStep->step1) {

    		$sql = 'bc.type = "'.$sessionStep->step1.'" ';
    	}

    	if ($sessionStep->step2) {

    		if ($sessionStep->step2 == 'Other Breeds') {
                
                $sql .= 'and b.breed <> 3 and b.breed <> 2 ';

            } else {

                $r = $this->Model_Breed->getBreedByName($sessionStep->step2);

        		$sql .= 'and b.breed = "'.$r->cod_breed.'" ';

            }
        }

    	if ($sessionStep->step3) {

    		if ($sessionStep->step3 == 'daughter_proven')
    			$style = 'bc.daughter_proven = 1';
    		
    		if ($sessionStep->step3 == 'genomic_young_sires')
    			$style = 'bc.genomic_young_sires = 1';

    		if ($sessionStep->step3 == 'all')
    			$style = ' (bc.daughter_proven = 1 or bc.genomic_young_sires = 1) ';

    		$sql .= 'and '.$style;

    	}

    	if ($sessionStep->step4) {

            $volume  = '';
    		$longevity  = '';
    		$milkFat  = '';
    		$milkProtein  = '';
    		$daughter  = '';

    		//$sql .= $volume.$longevity.$milkFat.$milkProtein.$daughter;
            
    	}  


    	 if (isset($_COOKIE['bullsearch']))
    	 	$sql .= ' and bc.country = '.$_COOKIE['bullsearch'];
         else
            $sql .= ' and bc.country = 223';


         $r =  $this->Model_Touro->fullSearch($sql);

         $itens = array();

        foreach ($r as $key => $value) {

             $itens[$key]['index']      = $this->calculateIndex($value);
             $itens[$key]['f1']         = $value->f1;
             $itens[$key]['f2']         = $value->f2;
             $itens[$key]['f3']         = $value->f3;
             $itens[$key]['f36']        = $value->f36;
             $itens[$key]['cod_bull']   = $value->cod_bull;
        }

        arsort($itens);
        array_values($itens);

        if ($sessionStep->step5) {

            if ($sessionStep->step5 == 10) {
                $array = array_slice($itens,0,10);
            }
            elseif ($sessionStep->step5 == 20) {
                $array = array_slice($itens,0,20);
            }
            elseif ($sessionStep->step5 == 30) {
                $array = array_slice($itens,0,30);
            }
            elseif ($sessionStep->step5 == 'all') {
                $array = $itens;
            }

        } 

        return $array;

    }


    private function calculateIndex($index){

        $sessionStep = new Zend_Session_Namespace('sessionStep');

        $cont = 0;
        $itens = array();


        if ($sessionStep->goalVolume) {
            $itens[] = $index['volume'];
            $cont++;
        }    

        if ($sessionStep->goalLongevity) {
            $itens[] = $index['longevity'];
            $cont++;
        }    

        if ($sessionStep->goalMilkFat) {
            $itens[] = $index['milk_fat'];
            $cont++;
        }    

        if ($sessionStep->goalMilkProtein) {
            $itens[] = $index['milk_protein'];
            $cont++;
        }    

        if ($sessionStep->goalDaughter) {
            $itens[] = $index['daughter_fertility'];
            $cont++;
        }    

        if ($cont == 1) {
            
            return $itens[0];

        } elseif ($cont == 2) {

            return ($itens[0]*0.5) + ($itens[1]*0.5);
        
        } elseif ($cont == 3) {
           
            return ($itens[0]*0.333) + ($itens[1]*0.333) + ($itens[2]*0.333);

        }

    }

    private function createAlsoViewed($bull){

        $r = $this->Model_SearchHistory->getHistoriesByNaab($bull->f2);

        $arrayTemp = array();
        $arrayBullsInfo = array();

        foreach ($r as $v) {
            
            $arrayValues = explode(',', $v->bulls);

            $arrayTemp = array_merge($arrayTemp, $arrayValues);

            $key = array_search($bull->f2, $arrayTemp);
            unset($arrayTemp[$key]);
        }

        arsort($arrayTemp);
        
        $arrayBulls = array_count_values($arrayTemp);

        arsort($arrayBulls);
        $arrayBulls = array_slice($arrayBulls, 0, 5);
        $arrayBulls = array_keys($arrayBulls);
        
        foreach ($arrayBulls as $v) {

            $touro = $this->Model_Touro->getBullByNaab($v);
            $image = $this->Model_BullImage->getImageBull($touro->cod_bull);
            
            $arrayBullsInfo[] = array('bullId' => $touro->cod_bull,
                'name' => $touro->f1,
                'naab' => $touro->f2,
                'image' => $image->image 
                );

        }

        $this->view->bullHistory = $arrayBullsInfo;
    }

    private function createBullHistory($bull){

        // Grava o registro temporário de acesso em um determinado animal 

        $sessionStep        = new Zend_Session_Namespace('sessionStep');
        $sessionCustomer    = new Zend_Session_Namespace('sessionCustomer');

        // Grava o registro do animal apenas se tiver sido feita uma busca antes.
        // Feita validacao para nao gravar historico de animal quando é acessado pelo Quick Search 

        if ($sessionStep->idSearchHistory) {  

            $sess = $this->Session->getInstance();
            $bullHistory = $sess->getSessVar('bullHistory');  

            $existe = false;
            $n = count($bullHistory);

            if (stristr($bullHistory, $bull->f2)) {

                $existe = true;
            }    

            if (!$existe) {

                if (empty($bullHistory)) {
                    
                     $bullHistory = $bull->f2;

                } else {

                    $bullHistory = $bullHistory.','.$bull->f2;
                }

            }

            $sess->emptySess();
            $sess->setSessVar('bullHistory',$bullHistory);

            if ($sessionCustomer->id) {
                $dados['logged'] = 1;
            }

            $dados['bulls'] = $bullHistory;

            $this->Model_SearchHistory->save($dados,$sessionStep->idSearchHistory);

        }
        
    }


    private function createSearchHistory(){

        $sessionStep        = new Zend_Session_Namespace('sessionStep');
        $sessionCustomer    = new Zend_Session_Namespace('sessionCustomer');

        //if (!$sessionStep->idSearchHistory) {
                
            $dados['country']   = $_COOKIE['bullsearch'];
            $dados['type']      = $sessionStep->step1;
            $dados['breed']     = $sessionStep->step2;

            if ($sessionStep->step3 == 'daughter_proven')
                $dados['sire_type'] = 'Daughter';

            if ($sessionStep->step3 == 'genomic_young_sires')
                $dados['sire_type'] = 'Genomic';

            if ($sessionStep->step3 == 'all'){
                $dados['sire_type'] = 'All';
            }

            $dados['volume']                = $sessionStep->goalVolume;
            $dados['longevity']             = $sessionStep->goalLongevity;
            $dados['milk_fat']              = $sessionStep->goalMilkFat;
            $dados['milk_protein']          = $sessionStep->goalMilkProtein;
            $dados['daughter_fertility']    = $sessionStep->goalDaughter;


            if($sessionCustomer->id)
                $dados['logged'] = $sessionCustomer->id;
            else
                $dados['logged'] = 0;   
            
            $id = $this->Model_SearchHistory->save($dados);

            $sessionStep->idSearchHistory = $id;

       // }

    }

    

    public function importaAction(){

        $this->view->layout()->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);

        $Model_Touro = new Model_Touro();


        $objPHPExcel = $this->ExcelReader->load(getcwd().'/default/uploads/excel-files/Propow1000.xls');   

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        $n = count($sheetData);

        for ($i=2; $i <= $n ; $i++) { 

            $breed  = $this->Search->find('Breed',$i,$sheetData);

            switch ($breed) {
                case 'MS':
                    $breedCode = 42;
                    break;
                
                case 'JE':
                    $breedCode = 3;
                    break;

                case 'HO':
                    $breedCode = 2;
                    break;

                case 'GU':
                    $breedCode = 41;
                    break;

                case 'BS':
                    $breedCode = 40;
                    break; 

                case 'AY':
                    $breedCode = 1;
                    break;               
            }


            $dados['breed']  = $breedCode;
            $dados['f1']     = $this->Search->find('ShortName',$i,$sheetData);
            $dados['f2']     = $this->Search->find('NAAB.',$i,$sheetData);
            $dados['f3']     = $this->Search->find('RegName',$i,$sheetData);
            $dados['f4']     = $this->Search->find('Reg.',$i,$sheetData);
            $dados['f5']     = $this->Search->find('HOAns',$i,$sheetData);
            $dados['f6']     = $this->Search->find('FinalScore',$i,$sheetData);
            $dados['f7']     = $this->Data->setData($this->Search->find('BirthDate',$i,$sheetData),1);
            $dados['f8']     = $this->Search->find('Gencodes',$i,$sheetData);
            $dados['f9']     = $this->Search->find('Hyplo',$i,$sheetData);
            $dados['f10']    = $this->Search->find('AAA',$i,$sheetData);
            $dados['f11']    = $this->Search->find('DMS',$i,$sheetData);
            $dados['f12']    = $this->Search->find('EFIPC',$i,$sheetData);
            $dados['f13']    = $this->Search->find('Bredby',$i,$sheetData);
            $dados['f14']    = $this->Search->find('Symbols',$i,$sheetData);
            $dados['f15']    = $this->Search->find('SireRegName',$i,$sheetData);
            $dados['f16']    = $this->Search->find('SireReg.',$i,$sheetData);
            $dados['f17']    = $this->Search->find('SireFinalScore',$i,$sheetData);
            $dados['f18']    = $this->Search->find('DamRegName',$i,$sheetData);
            $dados['f19']    = $this->Search->find('DamReg.',$i,$sheetData);
            $dados['f20']    = $this->Search->find('DamFinalScore',$i,$sheetData);
            $dados['f21']    = $this->Search->find('DamOfMerit',$i,$sheetData);
            $dados['f22']    = $this->Search->find('DamAgeAtCalf',$i,$sheetData);
            $dados['f23']    = $this->Search->find('DamTimesMilked',$i,$sheetData);
            $dados['f24']    = $this->Search->find('DamRecordLength',$i,$sheetData);
            $dados['f25']    = $this->Search->find('DamMilk',$i,$sheetData);
            $dados['f26']    = $this->Search->find('DamFat%',$i,$sheetData);
            $dados['f27']    = $this->Search->find('DamFat.',$i,$sheetData);
            $dados['f28']    = $this->Search->find('DamPro%',$i,$sheetData);
            $dados['f29']    = $this->Search->find('DamPro.',$i,$sheetData);
            $dados['f30']    = $this->Search->find('MGSRegName',$i,$sheetData);
            $dados['f31']    = $this->Search->find('MGSReg.',$i,$sheetData);
            $dados['f32']    = $this->Search->find('MGSFinalScore',$i,$sheetData);
            $dados['f33']    = $this->Search->find('ThirdSireRegName',$i,$sheetData);
            $dados['f34']    = $this->Search->find('ThirdSireRegNum',$i,$sheetData);
            $dados['f35']    = $this->Search->find('ThirdSireFinalScore',$i,$sheetData);
            $dados['f36']    = $this->Search->find('BovineID',$i,$sheetData);
            $dados['f37']    = $this->Search->find('RegStatus',$i,$sheetData);
            $dados['f38']    = $this->Search->find('RegOrigin',$i,$sheetData);
            $dados['abs']    = $this->Search->find('ABSBull',$i,$sheetData);

            try {

                $Model_Touro->importFromExcel($dados);
    
            } catch (Zend_Db_Exception $e) {
    
                echo $e->getMessage();
            }
               
        }
    }


    public function importa3Action(){

        $this->view->layout()->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);

        $Model_Touro = new Model_Touro();


        $objPHPExcel = $this->ExcelReader->load(getcwd().'/default/uploads/excel-files/Propow1000.xls');   

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        $n = count($sheetData);

        for ($i=2; $i <= $n ; $i++) { 

            $bull = $Model_Touro->getBullByCode($this->Search->find('BovineID',$i,$sheetData));

            if ($bull->f36) {

                $dados['bull']                  = $bull['cod_bull'];
                $dados['country']               = 223;

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

                try {

                 $this->Model_TouroCountry->importFromExcel($dados);

                 } catch (Zend_Db_Exception $e) {

                    echo $e->getMessage();
                }
            }    
        }
    }


    public function importa2Action(){

        $this->view->layout()->disableLayout();
        //$this->_helper->viewRenderer->setNoRender(true);

        $Model_Touro = new Model_Touro();


        $objPHPExcel = $this->ExcelReader->load(getcwd().'/default/uploads/excel-files/Propow1000.xls');   

        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

        $n = count($sheetData);

        for ($i=2; $i <= $n ; $i++) { 

            $bull = $Model_Touro->getBullByCode($this->Search->find('BovineID',$i,$sheetData));

            if ($bull->f36) {

                $dados['bull']   = $bull->cod_bull;

                $dados['f1']     = $this->Search->find('TypeSource',$i,$sheetData);
                $dados['f2']     = $this->Search->find('TypeDate',$i,$sheetData);
                $dados['f3']     = $this->Search->find('TypeDtrs',$i,$sheetData);
                $dados['f4']     = $this->Search->find('TypeHerds',$i,$sheetData);
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
                $dados['f55']    = $this->Search->find('Pro.',$i,$sheetData);
                $dados['f56']    = $this->Search->find('Pro%',$i,$sheetData);
                $dados['f57']    = $this->Search->find('ProRel',$i,$sheetData);
                $dados['f58']    = $this->Search->find('Fat.',$i,$sheetData);
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
                $dados['f76']    = $this->Search->find('TypeRel',$i,$sheetData);
                $dados['f77']    = $this->Search->find('SCS',$i,$sheetData);
                $dados['f78']    = $this->Search->find('SCSRel',$i,$sheetData);
                $dados['f79']    = $this->Search->find('PL',$i,$sheetData);
                $dados['f80']    = $this->Search->find('PLRel',$i,$sheetData);
                $dados['f81']    = $this->Search->find('Recessives',$i,$sheetData);
                $dados['f82']    = $this->Search->find('RWDRank',$i,$sheetData);



                try {

                    $this->Model_ProofHolstein->save($dados);

                } catch (Zend_Db_Exception $e) {

                    echo $e->getMessage();
                }
            }    
        }
    }
}
