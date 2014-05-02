<?php

class Model_TouroCountry {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_TouroCountry();
    }
    
    public function getBullById($id){
    	
    	return $this->_dbTable->fetchRow('bull = '.$id);
    }

    public function save($dados){

        $r = $this->_dbTable->fetchRow('bull = "'.$dados['bull'].'"');

        if(!empty($r)) {
            $this->_dbTable->update($dados, 'bull = "'.$dados['bull'].'"');
        } else {
            $this->_dbTable->insert($dados);
        }    
    }

    public function importFromExcel($dados){

        $r = $this->_dbTable->fetchRow('bull = "'.$dados['bull'].'" and country = '.$dados['country']);

        if(!empty($r)) {
            $this->_dbTable->update($dados, 'bull = "'.$dados['bull'].'"');
        } else {
            $this->_dbTable->insert($dados);
        }    
    }
       
}
