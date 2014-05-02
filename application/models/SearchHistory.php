<?php

class Model_SearchHistory {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_SearchHistory();
    }

    public function getHistoriesByNaab($naab){

        return $this->_dbTable->fetchAll('bulls like "%'.$naab.'%"');
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_history = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }
  
}
