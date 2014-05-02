<?php

/**
 * This helper is used to get the base URL
 * of the application. It�s useful to call
 * CSS styles and JavaScript files, for example.
 */
class Zend_View_Helper_RemoveAcentos {

    function RemoveAcentos($str, $replace=null, $delimiter='-') {
        setlocale(LC_ALL, 'en_US.UTF8');
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

}
