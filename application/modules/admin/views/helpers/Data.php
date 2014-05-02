<?php
/**
* This helper is used to get the base URL
* of the application. It�s useful to call
* CSS styles and JavaScript files, for example.
*/
class Zend_View_Helper_Data {
	function Data($data){
		$date = new Zend_Date($data);
		return $date->toString('dd/MM/YYYY');
	}
}