<?php

class Momesso_Admin_Form_Palavra_Palavra extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
        
        $palavra = $this->createElement('textarea', 'nome', array('label' => 'Palavra: '))
                        ->setRequired(true)
                        ->addFilters(array('stringTrim'))
                        ->setAttrib('class', 'span12')
        				->setAttrib('rows', '8');
        $this->addElement($palavra);

        $submit = $this->createElement('submit', 'Gravar')->setAttrib('class','btn btn-sm btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Gravar', 'Cancelar');
    }

}
