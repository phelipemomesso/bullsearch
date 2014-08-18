<?php

class Model_Breed {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Breed();
    }

    public function getBreeds(){


    	return $this->_dbTable->fetchAll(null,'name asc');
    }

    public function getBreedsByType($type){


        return $this->_dbTable->fetchAll('type = '.$type,'name asc');
    }
    
    public function getBreedById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }

    public function getBreedByName($name){
        
        return $this->_dbTable->fetchRow('name = "'.$name.'"');
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_breed = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }   
}
