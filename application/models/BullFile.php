<?php

class Model_BullFile {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_BullFile();
    }
    
    public function getFilesByType($type) {
    
    	return $this->_dbTable->fetchAll('type = '.$type,'create_time desc');
    	
    }

    public function save($dados) {
    
    	return $this->_dbTable->insert($dados);
    	
    }

       
}
