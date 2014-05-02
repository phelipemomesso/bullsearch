<?php

class Model_DbTable_Role extends Zend_Db_Table_Abstract {

    protected $_name = 'mod_role';
    protected $_primary = 'cod_role';
    
    protected $_referenceMap = array(
    		
    		'Model_DbTable_Action' => array(
    				'columns' => 'action',
    				'refTableClass' => 'Model_DbTable_Action',
    				'refColumns'=> 'cod_action',
    				'onDelete' => self::CASCADE,
    				'onUpdate' => self::RESTRICT
    		),
    		
    		'Model_DbTable_Group' => array(
    				'columns' => 'grupo',
    				'refTableClass' => 'Model_DbTable_Group',
    				'refColumns'=> 'cod_group',
    				'onDelete' => self::CASCADE,
    				'onUpdate' => self::RESTRICT
    		),
    		
    		'Model_DbTable_Controller' => array(
    				'columns' => 'controller',
    				'refTableClass' => 'Model_DbTable_Controller',
    				'refColumns'=> 'cod_controller',
    				'onDelete' => self::CASCADE,
    				'onUpdate' => self::RESTRICT
    		)
    );
    
}

