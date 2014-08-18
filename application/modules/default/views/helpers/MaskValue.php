<?php
/**
* This helper is used to get the base URL
* of the application. It�s useful to call
* CSS styles and JavaScript files, for example.
*/
class Zend_View_Helper_MaskValue {
	
	function MaskValue($value,$decimal,$signal=false,$decimalDigits=true){
		
		if ($value > 0) {
			
			if ($signal)
				$str = '+'; 

			$number = $str.substr($value, 0, $decimal);
			
			if ($decimalDigits) {
				
				return $str.number_format($number, 2, '.', '');  
			
			} else {

				return $number;
			}
			
		} elseif ($value < 0) {

			$number = substr($value, 0, $decimal);
			
			if ($decimalDigits) {
				
				return number_format($number, 2, '.', '');  
			
			} else {

				return $number;
			}
			
		} elseif ($value == 0 or empty($value)) {
			
			return '0.00';
			
		}
		
	}
}