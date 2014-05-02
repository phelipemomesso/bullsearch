<?php

class Model_Action {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Action();
    }

    public function getActionsByControllerId($id){

    	return $this->_dbTable->fetchAll('controller = '.$id,'label asc');
    }
    
    public function getActionById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_action = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
    
    public function createDefaultActions($controller){
    	
    	
    	$dados['controller'] 	= $controller;
    	$dados['action'] 		= 'index';
    	$dados['label'] 		= 'Listagem';
    	$dados['visible'] 		= 0;
    	
    	$this->_dbTable->insert($dados);
    	
    	unset($dados);
    	
    	$dados['controller'] 	= $controller;
    	$dados['action'] 		= 'new';
    	$dados['label'] 		= 'Novo';
    	$dados['visible'] 		= 0;
    	 
    	$this->_dbTable->insert($dados);
    	 
    	unset($dados);
    	
    	$dados['controller'] 	= $controller;
    	$dados['action'] 		= 'edit';
    	$dados['label'] 		= 'Editar';
    	$dados['visible'] 		= 0;
    	 
    	$this->_dbTable->insert($dados);
    	 
    	unset($dados);
    	
    	$dados['controller'] 	= $controller;
    	$dados['action'] 		= 'delete';
    	$dados['label'] 		= 'Delete';
    	$dados['visible'] 		= 0;
    	 
    	$this->_dbTable->insert($dados);
    	
    }
    
       
}
