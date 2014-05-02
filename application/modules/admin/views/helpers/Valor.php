<?php
/**
* This helper is used to get the base URL
* of the application. It�s useful to call
* CSS styles and JavaScript files, for example.
*/
class Zend_View_Helper_Valor {
	function Valor($valor){
		$currency = new Zend_Currency('pt_BR'); 
		$currency->setFormat(array('symbol' => "R$ "));
		
        return $currency->toCurrency($valor);
	}
}