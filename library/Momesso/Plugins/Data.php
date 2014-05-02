<?php

class Momesso_Plugins_Data extends Zend_Controller_Plugin_Abstract {

    function setData($data, $tipo) {

        $date = new Zend_Date($data);

        if ($tipo == 1)
            return $date->toString('YYYY-MM-dd');
        else
            return $date->toString('dd/MM/YYYY');
    }

}