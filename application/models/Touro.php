<?php

class Model_Touro {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Touro();
    }

    public function getBulls($situacao = false,$order,$limite = false){

    	if(!$situacao)
    		$situacao = null;

    	return $this->_dbTable->fetchAll($situacao,$order,$limite);
    }
    
    public function getBullById($id){
    	
    	return $this->_dbTable->find($id)->current();
    }

    public function getBullByCode($code){
        
        return $this->_dbTable->fetchRow('f36 = "'.$code.'"');
    }

    public function getBullByNaab($naab){
        
        return $this->_dbTable->fetchRow('f2 = "'.$naab.'"');
    }
    
    public function save($dados,$id=false) {
    
    	if($id)
    		return $this->_dbTable->update($dados, 'cod_bull = '. $id);
    	else {
    
    		return $this->_dbTable->insert($dados);
    	}
    }

    public function importFromExcel($dados){

        $r = $this->_dbTable->fetchRow('f36 = "'.$dados['f36'].'"');

        if(!empty($r)) {
            $this->_dbTable->update($dados, 'f36 = "'.$dados['f36'].'"');
        } else {
            $this->_dbTable->insert($dados);
        }    
    }


     public function quickSearch($string){

        $select = $this->_dbTable->select()
                ->setIntegrityCheck(false)
                ->from(array('b' => 'mod_bulls'))
                ->join(array('bc' => 'mod_bulls_countries'), 'b.cod_bull = bc.bull',array('b.*'))
                ->where('(b.f1 like "%'.$string.'%" or b.f2 like "%5'.$string.'%") and bc.visible = 1  and b.abs = 1')
                ->order('b.f1 asc');

        return $this->_dbTable->fetchAll($select);        
    }

    public function fullSearch($string){

        $select = $this->_dbTable->select()
                ->setIntegrityCheck(false)
                ->from(array('b' => 'mod_bulls'))
                ->join(array('bc' => 'mod_bulls_countries'), 'b.cod_bull = bc.bull',array('b.*','bc.*'))
                ->where('('.$string.') and bc.visible = 1 and b.abs = 1')
                ->order('b.f1 asc');      

        return $this->_dbTable->fetchAll($select);        
    }
       
}
