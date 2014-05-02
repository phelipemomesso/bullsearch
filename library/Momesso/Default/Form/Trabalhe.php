<?php

class Momesso_Default_Form_Trabalhe extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('class', 'form-horizontal')
                ->setAttrib('role', 'form');

  
        $nome = $this->createElement('text', 'nome', array('label' => 'Nome Completo: '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 100)
                ->setAttrib('placeholder', 'Nome Completo')
                ->setAttrib('class', 'form-control');
        $this->addElement($nome);


        $email = $this->createElement('text', 'email', array('label' => 'E-mail: '))
                ->setRequired(FALSE)
                ->addValidator('EmailAddress')
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('placeholder', 'E-mail')
                ->setAttrib('class', 'form-control');
        $this->addElement($email);
        
        $arquivo = $this->createElement('file','imagem',array('label'=>'Currículo ( PDF, Word ):'));
        $arquivo->setRequired(true)
               ->addValidator('Count', false, 1)
               ->addValidator('Size', false, '2mb');
        $this->addElement($arquivo);
        

      	$submit = $this->createElement('submit', 'Enviar')->setAttrib('class','btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Enviar', 'Cancelar');
    }

}
