<?php

class Model_DbTable_User extends Zend_Db_Table_Abstract {

    protected $_name = 'mod_users';
    protected $_primary = 'cod_user';
    
    protected $_referenceMap = array(
    	
    		'Model_DbTable_Group' => array(
    			'columns' => 'group',
    			'refTableClass' => 'Model_DbTable_Group',
    			'refColumns'=> 'cod_group',
    			'onDelete' => self::CASCADE,
    			'onUpdate' => self::RESTRICT
    		)
    );
    
}

