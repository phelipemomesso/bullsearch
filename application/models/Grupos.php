<?php

class Model_Grupos extends Zend_Db_Table_Abstract {

    protected $_name = 'mod_grupos';
    protected $_primary = 'cod_grupo';
    protected $_dependentTables = array('Model_Usuario', 'Model_Rolesgrupos', 'Model_Rolesactions');
    protected $_referenceMap = array(
        'Model_Modules' => array(
            'columns' => 'module',
            'refTableClass' => 'Model_Modules',
            'refColumns' => 'cod_module',
            'onDelete' => self::CASCADE,
            'onUpdate' => self::RESTRICT
        )
    );

}

