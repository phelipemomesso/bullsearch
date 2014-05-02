<?php

class Model_DbTable_Action extends Zend_Db_Table_Abstract {

    protected $_name = 'mod_actions';
    protected $_primary = 'cod_action';
    protected $_dependentTables = array('Model_DbTable_Role');
    
    protected $_referenceMap = array(
    	'Model_DbTable_Controller' => array(
    		'columns' => 'controller',
    		'refTableClass' => 'Model_DbTable_Controller',
    		'refColumns' => 'cod_controller',
    		'onDelete' => self::CASCADE,
    		'onUpdate' => self::RESTRICT
    	)
    );
    
}

