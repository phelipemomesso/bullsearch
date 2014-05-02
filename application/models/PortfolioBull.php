<?php

class Model_PortfolioBull {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_PortfolioBull();
    }
    
    public function getPortfolioById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }

    public function getBullsByPortfolioId($id){
        
        return $this->_dbTable->fetchAll('portfolio = '.$id);
    }
    
    public function save($dados) {
    
        return $this->_dbTable->insert($dados);
    	
    }
       
}
