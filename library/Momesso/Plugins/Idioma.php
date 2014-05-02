<?php

class Momesso_Plugins_Idioma extends Zend_Controller_Plugin_Abstract {

    function Idioma() {
        
        $Model = new Model_DbTable_Language();
        $r = $Model->fetchAll('situacao = 1','padrao desc');
        return $r;
    }

}