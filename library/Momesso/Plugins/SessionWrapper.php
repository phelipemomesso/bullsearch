<?php

class Momesso_Plugins_SessionWrapper extends Zend_Controller_Plugin_Abstract {

    protected static $_instance;
    public $namespace = null;
    
	public function __construct(){
		
		Zend_Session::start();

		$this->namespace = new Zend_Session_Namespace('carrinho');

		if (!isset($this->namespace->initialized)) {

			Zend_Session::regenerateId();
			$this->namespace->initialized = true;
		}
	}

	public static function getInstance() {

		if (null === self::$_instance) {

			self::$_instance = new self();
		}

		return self::$_instance;
	}


	public function getSessVar($var, $default=null) {

		return (isset($this->namespace->$var)) ? $this->namespace->$var : $default;

	}


	public function setSessVar($var,$value) {

		if (!empty($var) && !empty($value)) {

			$this->namespace->$var = $value;
		}

	}


	public function emptySess() {

		Zend_Session::namespaceUnset('carrinho');

	}
	
    
}