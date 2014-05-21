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

    public function checkBullByPortfolio($portfolio,$bull){
        
        return $this->_dbTable->fetchRow('portfolio = '.$portfolio.' and bull = '.$bull);
    }
    
    public function save($dados) {
    
        return $this->_dbTable->insert($dados);
    	
    }
       
}
