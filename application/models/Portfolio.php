<?php

class Model_Portfolio {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Portfolio();
        $this->sessionCustomer = new Zend_Session_Namespace('sessionCustomer');
    }

    public function getPortfoliosByCustomerrId(){

    	return $this->_dbTable->fetchAll('customer = '.$this->sessionCustomer->id);
    }
    
    public function getPortfolioById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }

    public function getPortfolioByHash($hash){
        
        return $this->_dbTable->fetchRow('hash = "'.$hash.'"');
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_portfolio = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
       
}
