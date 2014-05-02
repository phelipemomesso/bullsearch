<?php

class Model_DbTable_Controller extends Zend_Db_Table_Abstract {

    protected $_name = 'mod_controllers';
    protected $_primary = 'cod_controller';
    protected $_dependentTables = array('Model_DbTable_Action', 'Model_DbTable_Role');

}

