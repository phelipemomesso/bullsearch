<?php
/**
* This helper is used to get the base URL
* of the application. It�s useful to call
* CSS styles and JavaScript files, for example.
*/
class Zend_View_Helper_truncate {
    function truncate($Str = null,$Chars = 30){
        if($Str != null && strlen($Str)>$Chars) {
            if($Str[$Chars] != ' ')
                if(strpos($Str,' ')>-1) {
                    while($Str[$Chars] != ' ') :
                        $Chars--;
                    endwhile;
                }
                $Str = strlen($Str) > $Chars ? substr($Str,0,$Chars) . '...' : $Str;
                return $Str;
            }
        else return $Str;
    }
}