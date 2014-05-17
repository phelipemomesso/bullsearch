<?php
/**
* This helper is used to get the base URL
* of the application. It�s useful to call
* CSS styles and JavaScript files, for example.
*/
class Zend_View_Helper_Word {
	
	function Word($word){
		
		$Model = new Model_PalavraLanguage();

		$session = new Zend_Session_Namespace('Language');

		if (empty($session->language)) {
			$session->language = 11;
		}

		$res = $Model->getTranslatesByWordandLanguage($word,$session->language);

		return $res->translate;
	}
}