<?php

class Model_Group {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Group();
    }

    public function getGroups(){

    	return $this->_dbTable->fetchAll(null);
    }
    
    public function getGroupById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_group = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
    
       
}
