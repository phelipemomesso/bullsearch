<?php
class Momesso_Plugins_ApagaDiretorio extends Zend_Controller_Plugin_Abstract {
			
	function apagar($rootDir){

		if (!is_dir($rootDir)) {
	        return false;
	    }
	
	    if (!preg_match("/\\/$/", $rootDir)) {
	        $rootDir .= '/';
	    }
	
	    $stack = array($rootDir);
	
	    while (count($stack) > 0){
	        
	    	$hasDir = false;
	        $dir    = end($stack);
	        $dh     = opendir($dir);
	
	        while (($file = readdir($dh)) !== false) {
	            if ($file == '.'  ||  $file == '..') {
	                continue;
	            }
	
	            if (is_dir($dir . $file)) {
	                $hasDir = true;
	                array_push($stack, $dir . $file . '/');
	            }
	
	            else if (is_file($dir . $file)) {
	                unlink($dir . $file);
	            }
	        }
	
	        closedir($dh);
	
	        if ($hasDir == false) {
	            array_pop($stack);
	            rmdir($dir);
	        }
	    }
	
	    return true;
	}
		
}