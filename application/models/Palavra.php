<?php

class Model_Palavra {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Palavra();
    }

    public function getWords(){

    	return $this->_dbTable->fetchAll(null,'nome asc');
    }
    
    public function getWordById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_palavra = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
  
}
