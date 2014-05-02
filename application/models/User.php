<?php

class Model_User {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_User();
    }

    public function getUsers(){

    	return $this->_dbTable->fetchAll(null);
    }
    
    public function getUserById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }

    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_user = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
    
       
}
