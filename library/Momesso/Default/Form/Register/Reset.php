<?php

class Momesso_Default_Form_Register_Reset extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('class', 'form-horizontal')
                ->setAttrib('role', 'form');


        $email = $this->createElement('text', 'email', array('label' => 'E-mail * '))
                ->setRequired(TRUE)
                ->addValidator('EmailAddress')
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('class', 'form-control')
                ->setAttrib('readonly', true)
                ->setAttrib('placeholder', 'E-mail');
        $this->addElement($email);

        $senha = $this->createElement('password', 'password', array('label' => 'New Password * '))
                        ->setRequired(true)
                        ->addFilter('StripTags')
                        ->addFilter('stringTrim')
                        ->setAttrib('maxlength', 10)
                        ->addValidator('stringLength', false, array(6, 10))
                        ->setAttrib('size', 13);
        $this->addElement($senha);

        $senha2 = $this->createElement('password', 'password2', array('label' => 'Password Confirm * '))
                        ->setRequired(true)
                        ->addFilter('StripTags')
                        ->addFilter('stringTrim')
                        ->setAttrib('maxlength', 10)
                        ->addValidator('stringLength', false, array(6, 10))
                        ->setAttrib('size', 13)
                        ->addValidator('Identical', false, array('password'));
        $this->addElement($senha2);
    

      	$submit = $this->createElement('submit', 'Update')->setAttrib('class','btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Update', 'Cancelar');
    }

}
