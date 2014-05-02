<?php

class Momesso_Plugins_ArraySearch extends Zend_Controller_Plugin_Abstract {

    function find($value, $iterator ,$array) {

    	$key = array_search($value, $array[1]);

    	//echo $array[$iterator][$key].'<br />';

    	if(!empty($array[$iterator][$key]))
    		return trim($array[$iterator][$key]);
    	else
    		return '';
    }

}