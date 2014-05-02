<?php

class Momesso_Plugins_ValidateInputUrl extends Zend_Controller_Plugin_Abstract {

    function validateInput($str) {

		if( !is_array( $str ) ) {
			
			$str = preg_replace('~&amp;#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $str);
			$str = preg_replace('~&amp;#([0-9]+);~e', 'chr("\\1")', $str);
			$str = str_replace("<script","",$str);
			$str = str_replace("script>","",$str);
			$str = str_replace("<Script","",$str);
			$str = str_replace("Script>","",$str);
			$str = trim($str);
			$tbl = get_html_translation_table(HTML_ENTITIES);
			$tbl = array_flip($tbl);
			$str = addslashes($str);
			$str = strip_tags($str);
			return strtr($str, $tbl);

		} else 
			return $str;
	}
}