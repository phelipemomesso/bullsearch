<?php

class Model_TouroVideo {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_TouroVideo();
    }

    public function getVideosByBullId($id){

    	return $this->_dbTable->fetchAll('bull = '.$id);
    }
    
    public function getVideoById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_video = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
       
}
