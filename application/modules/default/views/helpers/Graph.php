<?php

class Zend_View_Helper_Graph {

	function Graph ($Valor) {
		$width = 35;
		
		$Valor = str_replace(',', '.', $Valor);

		$view = Zend_Layout::getMvcInstance()->getView();
		
		if ($Valor < 0) {
			if ($Valor > -1) {
				$Pix = ceil($width * $Valor * -1);
				$Resto = floor($width - $Pix);
				echo '<img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="13" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$Resto.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$Pix.'" height="20" />';
			}
			else if ($Valor > -2) {
				$Pix = $Valor + 1;
				$Pix = ceil($Pix * $width * -1);
				$Resto = floor($width - $Pix);
				echo '<img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="13" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$Resto.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$Pix.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$width.'" height="20" />';
			}
			else if ($Valor < -2) {
				echo '<img src="'.$view->baseUrl().'/default/imagens/provas/ponta_esquerda.gif" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$width.'" height="20" />';
			}
		}
		else if ($Valor >= 0)	{
			echo '<img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="13" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$width.'" height="20" />';
		}
		
		echo '<img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="2" height="20" />';
		
		if ($Valor > 0) {
			if ($Valor < 1) {
				$Pix = ceil($width * $Valor);
				$Resto = floor($width - $Pix);
				echo '<img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$Pix.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$Resto.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="13" height="20" />';
			}
			else if ($Valor < 2) {
				$Pix = $Valor - 1;
				$Pix = ceil($Pix * $width);
				$Resto = floor($width - $Pix);
				echo '<img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$Pix.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$Resto.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="13" height="20" />';
			}
			else if ($Valor == 2) {
				echo '<img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="13" height="20" />';
			}
			else if ($Valor > 2) {
				echo '<img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/linha.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/ponta_direita.gif" />';
			}
		}
		else if ($Valor <= 0)	{
			echo '<img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="'.$width.'" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/divisor_azul.gif" width="1" height="20" /><img src="'.$view->baseUrl().'/default/imagens/provas/transp.gif" width="13" height="20" />';
		}
	}
}