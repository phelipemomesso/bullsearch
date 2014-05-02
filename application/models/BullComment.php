<?php

class Model_BullComment {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_BullComment();
    }

    public function getCommentsByBull($bull){

    	return $this->_dbTable->fetchAll('bull = '.$bull,'create_time desc');
    }
    
    public function getCommentById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_comment = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
       
}
