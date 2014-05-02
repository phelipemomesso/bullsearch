<?php

class Model_Country {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Country();
    }
    
    public function getCountries(){

    	return $this->_dbTable->fetchAll();
    }

     public function getCountryById($id){

        return $this->_dbTable->find($id)->current();

    }

    public function save($dados) {
    
    	return $this->_dbTable->insert($dados);
    	
    }

       
}
