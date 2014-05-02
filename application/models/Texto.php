<?php

class Model_Texto {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Texto();
    }

    public function getTextos($situacao = false){

    	if(!$situacao)
    		$situacao = null;

    	return $this->_dbTable->fetchAll($situacao,'nome asc');
    }
    
    public function getTextoById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_texto = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
       
}
