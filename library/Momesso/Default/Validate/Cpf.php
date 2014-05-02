<?php

require_once 'Zend/Validate/Abstract.php';

/**
 * Validador para fazer a validação de CPF (Cadastro de Pessoas Físicas)
 *
 * @author Diego Tremper <diegotremper@gmail.com>
 */
class Momesso_Default_Validate_Cpf extends Zend_Validate_Abstract
{
	const INVALID_DIGITS = 'i_number';
	
	const INVALID_FORMAT = 'i_format';
	
	protected $_messageTemplates = array (
					self::INVALID_DIGITS => "O cpf '%value%' não é válido",
					self::INVALID_FORMAT => "O formato do cpf '%value%' não é válido"
			  );
			  
	private $_pattern = '/(\d{3})\.(\d{3})\.(\d{3})-(\d{2})/i';
	
	private $_skipFormat = false;
	
	/**
	 * Inicializa a instância do validador
	 *
	 * @param bool $skipFormat fazer validação no formato?
	 */
	public function __construct($skipFormat = false) {
		$this->_skipFormat = $skipFormat;
	}
	
	/**
	 * verifica se o cpf é válido
	 *
	 * @param string $value cpf a ser validado
	 * @return bool
	 */
	public function isValid($value)
	{
		$this->_setValue ( $value );
		
	 	if( ($value == '111.111.111-11') || ($value == '222.222.222-22') ||
		   ($value == '333.333.333-33') || ($value == '444.444.444-44') ||
		   ($value == '555.555.555-55') || ($value == '666.666.666-66') ||
		   ($value == '777.777.777-77') || ($value == '888.888.888-88') ||
		   ($value == '999.999.999-99') || ($value == '000.000.000-00') ) {
		  	$this->_error(self::INVALID_FORMAT);
			return false;
  		}
		
		if (!$this->_skipFormat && preg_match($this->_pattern, $value) == false) {
			$this->_error(self::INVALID_FORMAT);
			return false;
		}
		
		$digits = preg_replace('/[^\d]+/i', '', $value);
		$firstSum = 0;
		$secondSum = 0;
		
		for ($i=0; $i<9; $i++) {
			$firstSum += $digits{$i} * (10 - $i);
			$secondSum += $digits{$i} * (11 - $i);
		}
		
		$firstDigit = 11 - fmod($firstSum, 11);
		
		if ($firstDigit >= 10) {
			$firstDigit = 0;
		}
		
		$secondSum = $secondSum + ($firstDigit*2);
		$secondDigit = 11 - fmod($secondSum, 11);
		
		if ($secondDigit >= 10) {
			$secondDigit = 0;
		}
		
		if (substr($digits, -2) != ($firstDigit . $secondDigit)) {
			$this->_error(self::INVALID_DIGITS);
			return false;
		}
		
		return true;
	}
}


