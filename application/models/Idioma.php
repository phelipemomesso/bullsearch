<?php

class Model_Idioma {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Idioma();
    }

    public function getLanguages($situacao = false){

    	if(!$situacao)
    		$situacao = null;

    	return $this->_dbTable->fetchAll($situacao);
    }
    
    public function getLanguageById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_language = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
  
}
