<?php

class Model_Customer {
	

	public function __construct() {
        
        $this->_dbTable = new Model_DbTable_Customer();
    }
    
    public function getCustomers(){

        return $this->_dbTable->fetchAll(null,'first_name');
    }

    public function getCustomerById($id){

        return $this->_dbTable->find($id)->current();

    }

    public function getCustomerByEmail($email){

        return $this->_dbTable->fetchRow('email = "'.$email.'"');
    }

    public function getCustomerByEmailAndId($email,$id){

        return $this->_dbTable->fetchRow('email = "'.$email.'" and cod_customer <> '.$id);
    }
    

    public function getUserByFacebookId($id){

    	return $this->_dbTable->fetchRow('facebook_id = '.$id);
    }


    public function save($dados,$id=false) {

        if ($id) {

            return $this->_dbTable->update($dados,'cod_customer ='. $id);
        } else {

            return $this->_dbTable->insert($dados);
        }
    
    }

       
}
