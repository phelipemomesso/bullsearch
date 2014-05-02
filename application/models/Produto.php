<?php

class Model_Produto {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Produto();
    }

    public function getProducts($situacao = false,$limite = false){

    	if(!$situacao)
    		$situacao = null;

    	return $this->_dbTable->fetchAll($situacao,'nome asc',$limite);
    }
    
    
    public function getProductById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_produto = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
       
}
