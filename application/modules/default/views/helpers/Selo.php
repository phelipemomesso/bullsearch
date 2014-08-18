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
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/breeders_choice.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="Breeders Choice" /></li>';

		} if ($res->calving_ease == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/calving_ease.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="7.4% or Less Calving Ease" /></li>';

		} if ($res->diamond_sire == 1) {
			
			$selo .='<img src="'.$view->baseUrl().'/default/uploads/selos/diamond_sire.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="Diamond Sire (1,000 Milking Dtrs)" />';

		} if ($res->durabulls == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/durabulls.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="DURAbulls (Health & Fitness Leaders)" /></li>';

		} if ($res->feed_efficieny == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/feed_efficieny.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="Feed Efficiency Leader" /></li>';

		} if ($res->judges_choice == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/judges_choice.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="Judge’s Choice (High Type)" /></li>';

		} if ($res->pregnancy_king == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/pregnancy_king.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="ABS Pregnancy Kings (Fertility Leaders)" /></li>';

		} if ($res->redwhite == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/redwhite.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="Red and White" /></li>';

		} if ($res->sexation == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/sexation.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="ABS Sexation® - Sex Sorted Semen" /></li>';

		} if ($res->sexation_only == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/sexation_only.jpg" class="img-responsive selos" data-toggle="tooltip" data-placement="top" title="Available Only in ABS Sexation®" /></li>';
		}	

		echo '<ul class="list-unstyled">';
  		echo $selo;
		echo '</ul>';

	}
}