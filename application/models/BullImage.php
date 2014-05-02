<?php

class Model_BullImage {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_BullImage();
    }

    public function getImagesByBull($status = false,$order=false,$limite = false){

    	if(!$status)
    		$status = null;

    	return $this->_dbTable->fetchAll($status,$order,$limite);
    }

    public function getImageBull($bull){

        return $this->_dbTable->fetchRow('bull = '.$bull.' and type = 1');
    }

    public function getImagesDaughter($bull){

        return $this->_dbTable->fetchAll('bull = '.$bull.' and type = 2');
    }
    
    public function getImageById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_image = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
       
}
