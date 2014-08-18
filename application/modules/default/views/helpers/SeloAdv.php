<?php
/**
* This helper is used to get the base URL
* of the application. It�s useful to call
* CSS styles and JavaScript files, for example.
*/
class Zend_View_Helper_SeloAdv {
	function SeloAdv($bull){
		
		$view = Zend_Layout::getMvcInstance()->getView();

		$Model_TouroCountry = new Model_TouroCountry();

		$res = $Model_TouroCountry->getBullById($bull);

		if ($res->breeders_choice == 1) {
			
			$selo .='<li>
			<img src="'.$view->baseUrl().'/default/uploads/selos/breeders_choice.jpg" />
			<br>
			Breeders Choice
			</li>';

		} if ($res->calving_ease == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/calving_ease.jpg" />
			<br>
			7.4% or Less Calving Ease
			</li>';

		} if ($res->diamond_sire == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/diamond_sire.jpg" />
			<br>
			Diamond Sire (1,000 Milking Dtrs)
			</li>';

		} if ($res->durabulls == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/durabulls.jpg" />
			<br>
			DURAbulls (Health & Fitness Leaders)
			</li>';

		} if ($res->feed_efficieny == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/feed_efficieny.jpg" />
			<br>
			Feed Efficiency Leader
			</li>';

		} if ($res->judges_choice == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/judges_choice.jpg" />
			<br>
			Judge’s Choice (High Type)
			</li>';

		} if ($res->pregnancy_king == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/pregnancy_king.jpg" />
			<br>
			ABS Pregnancy Kings (Fertility Leaders)
			</li>';

		} if ($res->redwhite == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/redwhite.jpg" />
			<br>
			Red and White
			</li>';

		} if ($res->sexation == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/sexation.jpg" />
			<br>
			ABS Sexation® - Sex Sorted Semen
			</li>';

		} if ($res->sexation_only == 1) {
			
			$selo .='<li><img src="'.$view->baseUrl().'/default/uploads/selos/sexation_only.jpg" />
			<br>
			Available Only in ABS Sexation®
			</li>';
		}	

		echo '<ul class="list-inline">';
  		echo $selo;
		echo '</ul>';

	}
}