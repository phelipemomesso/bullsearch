<?php
class Momesso_Admin_Form_Login_Login extends Zend_Form {
	
	public function init() {
				 
		$this->setAction('login')
			 ->setMethod('post')
			 ->setAttrib('id', 'form_login');

		$usuario = $this->createElement('text', 'usuario', array('label' => 'Nome: '))
						 ->setRequired(true)
						 ->addFilter('StripTags')
						 ->addFilter('stringTrim');
						 
		$recSenha = new Momesso_Plugins_Htmlform('recSenha');
		$recSenha->setValue('<a href="#" id="rec_senha">Perdeu a senha?</a>')->removeDecorator('label');
						 
		$senha = $this->createElement('password', 'senha', array('label' => 'Senha: '))
						 ->setRequired(true)
						 ->addFilter('StripTags')
						 ->addFilter('stringTrim');
						 
		$submit = $this->createElement ('image', 'Entrar')
					   ->setImage(Zend_Controller_Front::getInstance()->getBaseUrl() .'/admin/images/btn-login.jpg')
					   ->setAttrib('alt', 'Entrar');	
								 
		$this->addElements(array($usuario, $senha, $recSenha, $submit));	 
	}
}