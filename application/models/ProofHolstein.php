<?php

class Model_ProofHolstein {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_ProofHolstein();
    }

    public function getProofById($id){
        
        return $this->_dbTable->find($id)->current();
    }
    
    public function getProofByBullId($id){
    	
    	return $this->_dbTable->fetchRow('bull = '.$id);
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
  
}
