<?php
/**
* This helper is used to get the base URL
* of the application. It�s useful to call
* CSS styles and JavaScript files, for example.
*/
class Zend_View_Helper_YoutubeUrl {
	
	function YoutubeUrl($url,$image=false){
	
	    if (stristr($url,'youtu.be/')) {
	    	
	    	preg_match('/(https|http):\/\/(.*?)\/([a-zA-Z0-9_]{11})/i', $url, $final_ID);
	    	$return = $final_ID[3]; 
	    
	    } else { 
	    	
	    	preg_match('/(https|http):\/\/(.*?)\/(embed\/|watch\?v=|(.*?)&v=|v\/|e\/|.+\/|watch.*v=|)([a-zA-Z0-9_]{11})/i', $url, $IDD); 
	    	$return = $IDD[5]; 
	    }


	    if($image) {

	    	return '<img src="http://i1.ytimg.com/vi/'.$return.'/default.jpg"/>';
	    } else {

	    	return $return;
	    }
    
	}
}