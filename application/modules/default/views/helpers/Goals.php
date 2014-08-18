<?php

class Zend_View_Helper_Goals {
	
	function Goals($bull){
	
		$Model_ProofHolstein = new Model_ProofHolstein();
		$sessionStep         = new Zend_Session_Namespace('sessionStep');

		$res = $Model_ProofHolstein->getProofByBullId($bull);

		if(!empty($sessionStep->step4)) {
	
			if ($res->f52 > 0) {
				$sinal = '+';
			}elseif ($res->f52 < 0) {
				$sinal = '-';
			}

			if ($sessionStep->goalVolume == 1) {
				$label .= 'Volume: '.$this->MaskValue($res->f52,4,true).' lbs<br />';
			} 
			if ($sessionStep->goalLongevity == 1) {
				$label .= 'Longevity: '.$this->MaskValue($res->f79,4,false).'<br />';
			} 
			if ($sessionStep->goalMilkFat == 1) {
				$label .= 'Milk Fat: '.$this->MaskValue($res->f55,3,true).'<br />';
			}
			if ($sessionStep->goalMilkProtein == 1) {
				$label .= 'Milk Protein: '.$this->MaskValue($res->f58,3,true).'<br />';
			}
			if ($sessionStep->goalDaughter == 1) {
				$label .= 'Daughter Fertility: '.$this->MaskValue($res->f75,4,false);
			}

			echo '<span style="font-size:10px;">';
			echo $label;
			echo '</span>';
		}
	}	

	function MaskValue($value,$decimal,$signal=false){
		
		if ($value > 0) {
			
			if ($signal)
				$str = '+'; 

			return $str.substr($value, 0, $decimal);
			
		} elseif ($value < 0) {
			
			return substr($value, 0, $decimal);
			
		} elseif ($value == 0 or empty($value)) {
			
			return substr($value, 0, $decimal);
			
		}
		
	}
		
}