<?php
class Momesso_Admin_Form_Conta_Conta  extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
		
		$auth = Zend_Auth::getInstance()->getIdentity();		
		
		$nome = $this->createElement('text', 'nome', array('label' => 'Nome: '))
					   ->setRequired(true)
					   ->addFilters(array('StripTags', 'stringTrim'))
					   ->setAttrib('class', 'span5');
							   
		$usuario = $this->createElement('text', 'usuario', array('label' => 'Usuário: '))
					   ->setRequired(true)
					   ->addFilters(array('StripTags', 'stringTrim'))
					   ->addValidator('Db_NoRecordExists', false, array('table' => 'mod_usuarios', 'field' => 'usuario', 'exclude' => array('field' => 'id', 'value' => $auth->id)))
					   ->setAttrib('class', 'span2');

		$email = $this->createElement('text', 'email', array('label' => 'Email: '))
					  ->setRequired(true)
					  ->addFilters(array('StripTags', 'stringTrim'))
					  ->addValidator('EmailAddress')
					   ->addValidator('Db_NoRecordExists', false, array('table' => 'mod_usuarios', 'field' => 'email', 'exclude' => array('field' => 'id', 'value' => $auth->id)))
					  ->setAttrib('class', 'span5');

					   
		$submit = $this->createElement('submit', 'Gravar')->setAttrib('class','btn btn-primary')->setIgnore(true);
        
        
		$senha = new Momesso_Plugins_Htmlform('altsenha');
		$senha->setValue('<a href="#" id="altsenha">Alterar senha</a>')->removeDecorator('label');
								 
		$this->addElements(array($nome, $usuario, $email, $submit, $senha));
		
		EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Gravar', 'Cancelar');
	}
}