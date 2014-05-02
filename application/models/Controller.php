<?php

class Model_Controller {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Controller();
        $this->_model_Action = new Model_Action();
    }

    public function getControllers(){

    	return $this->_dbTable->fetchAll(null,'label asc');
    }
    
    public function getControllerById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id) {
    		$id = $this->_dbTable->update($dados, 'cod_controller = '. $id);
    	} else {
    
    		$id = $this->_dbTable->insert($dados);
    		
    		$this->_model_Action->createDefaultActions($id);
    		
    		return $id;
    	}
    }
    
       
}
