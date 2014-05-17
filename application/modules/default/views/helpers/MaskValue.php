<?php
/**
* This helper is used to get the base URL
* of the application. It�s useful to call
* CSS styles and JavaScript files, for example.
*/
class Zend_View_Helper_MaskValue {
	
	function MaskValue($value,$signal=false){
		
		if ($value > 0) {
			
			if ($signal)
				$str = '+'; 

			return $str.number_format($value, 2, '.', '');
			
		} elseif ($value < 0) {
			
			return number_format($value, 2, '.', '');
			
		} elseif ($value == 0 or empty($value)) {
			
			return number_format(0, 2, '.', '');
			
		}
		
	}
}