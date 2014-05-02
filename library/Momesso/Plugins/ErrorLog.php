<?php

class Momesso_Plugins_ErrorLog extends Zend_Controller_Plugin_Abstract {

    public $modulo;
    public $acao;
    public $erro;
    public $model;
    
	public function __construct(){
		
		$this->model = new Model_DbTable_Erros();
	}

	public function getModulo() {
		return $this->modulo;
	}

	public function getAcao() {
		return $this->acao;
	}

	public function getErro() {
		return $this->erro;
	}

	public function setModulo($modulo) {
		$this->modulo = $modulo;
	}

	public function setAcao($acao) {
		$this->acao = $acao;
	}

	public function setErro($erro) {
		$this->erro = $erro;
	}

    public function recordLog(){
    	
    	$dados['modulo'] 	= $this->getModulo();
    	$dados['acao'] 		= $this->getAcao();
    	$dados['erro'] 		= $this->getErro();
    	
    	$this->model->insert($dados);
    }
    
}