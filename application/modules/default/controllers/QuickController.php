<?php

class QuickController extends Zend_Controller_Action {


    public function init(){

    	$this->Model_Touro         = new Model_Touro();
        
        $this->Spell               = new Momesso_Plugins_SpellCorrector();
        $this->ValidateInputUrl    = new Momesso_Plugins_ValidateInputUrl();

    }

    public function indexAction() {
        
    	$this->view->headTitle()->append('Result');

    	$search = $this->_getParam('search');
        $this->view->Search = $search;

        if (!empty($search)) {

            $exp = preg_match ("/[0-9]+/", $search);

            if (substr($search, 0,1) == 5) {
                
                $search = substr($search, 1);
            }

            $r = $this->Model_Touro->quickSearch($search);

            if ($exp) {
                
                $this->view->Results = $r;
            
            } else{
                    
                $this->view->Results = $r;
                $this->view->Spell   = $this->spellCorrect($search);
            }
       }    	
    }

    private function spellCorrect($search){

        $nome = "dictionary";
        $file = getcwd() . '/default/uploads/spell/'.$nome;
        $cria = fopen(''.$file.'.txt', 'w+');

        $res = $this->Model_Touro->getBulls('abs = 1','f1 asc');

        foreach ($res as $v) {
            $dados .= $this->RemoveAcentos($v->f3).' '.$this->RemoveAcentos($v->f1).' ';
        }

        $escreve = fwrite($cria, "$dados\r\n");
        fclose($cria);

        return $this->Spell->correct($search);

    }

    private function RemoveAcentos($name) {
        
        $array1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç"," ","'","´","`","/","\\","~","^","¨",":",")","(",".",",","\"");
        $array2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C"," ","","","","","","","","","","","","","","");
        
        return str_replace( $array1, $array2, $name );

    }


   
}

