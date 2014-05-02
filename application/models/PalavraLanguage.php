<?php

class Model_PalavraLanguage {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_PalavraLanguage();
    }

    public function getTranslatesByWord($word){

    	return $this->_dbTable->fetchAll('palavra = '.$word);
    }

    public function getTranslatesByWordandLanguage($word,$language){

        return $this->_dbTable->fetchRow('palavra = '.$word.' and language = '.$language);
    }
    
    public function getTranslateById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
  
}
