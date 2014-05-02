<?php
/**
* This helper is used to get the base URL
* of the application. It�s useful to call
* CSS styles and JavaScript files, for example.
*/
class Zend_View_Helper_Selo {
	function Selo($bull){
		
		$view = Zend_Layout::getMvcInstance()->getView();

		$Model_TouroCountry = new Model_TouroCountry();

		$res = $Model_TouroCountry->getBullById($bull);

		if ($res->breeders_choice == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/breeders_choice.jpg" width="25" height="25" class="img-responsive" /></li>';

		} if ($res->calving_ease == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/calving_ease.jpg" width="25" height="25" class="img-responsive" /></li>';

		} /*if ($res->diamond_sire == 1) {
			
			$selo .='<img src="'.$view->baseUrl().'/default/uploads/selos/diamond_sire.jpg" width="25" height="25" class="img-responsive" />';

		} */ if ($res->durabulls == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/durabulls.jpg" width="25" height="25" class="img-responsive" /></li>';

		} if ($res->feed_efficieny == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/feed_efficieny.jpg" width="25" height="25" class="img-responsive" /></li>';

		} if ($res->judges_choice == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/judges_choice.jpg" width="25" height="25" class="img-responsive" /></li>';

		} if ($res->pregnancy_king == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/pregnancy_king.jpg" width="25" height="25" class="img-responsive" /></li>';

		} if ($res->redwhite == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/redwhite.jpg" width="25" height="25" class="img-responsive" /></li>';

		} if ($res->rsg == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/rsg.jpg" width="25" height="25" class="img-responsive" /></li>';

		} if ($res->sexation == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/sexation.jpg" width="25" height="25" class="img-responsive" /></li>';

		} if ($res->sexation_only == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/sexation_only.jpg" width="25" height="25" class="img-responsive" /></li>';
		}	

		echo '<ul class="list-inline">';
  		echo $selo;
		echo '</ul>';

	}
}