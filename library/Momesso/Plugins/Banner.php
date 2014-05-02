<?php

class Momesso_Plugins_Banner extends Zend_Controller_Plugin_Abstract {

    function Banner() {
        
        $Model = new Model_Banners();
        $r = $Model->fetchAll('situacao = 1','rand()');
        return $r;
    }

}