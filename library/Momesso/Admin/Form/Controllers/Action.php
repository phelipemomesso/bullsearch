<?php

class Momesso_Admin_Form_Controllers_Action extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
        
        $controller = $this->createElement('hidden', 'controller')
				        ->setRequired(true)
				        ->addFilters(array('StripTags', 'stringTrim'))
				        ->removeDecorator('label');
        $this->addElement($controller);
        
        $action = $this->createElement('text', 'action', array('label' => 'Action: '))
                        ->setRequired(true)
                        ->addFilter('StripTags')
                        ->addFilter('stringTrim')
                        ->setAttrib('maxlength', 30)
                        ->setAttrib('size', 30)
                        ->setAttrib('class', 'span6');
        $this->addElement($action);
        
        $label = $this->createElement('text', 'label', array('label' => 'Nome Menu: '))
				        ->setRequired(true)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 30)
				        ->setAttrib('size', 30)
				        ->setAttrib('class', 'span6');
        $this->addElement($label);

        $statusOptions = array(
            1 => "Publicado",
            0 => "Não Publicado"
        );

        $status = $this->createElement('select', 'visible', array('label' => 'Situação:'));
        $status->setRequired(TRUE)
                ->setMultiOptions($statusOptions)
                ->setAttrib('class', 'span2');
        $this->addElement($status);

        $submit = $this->createElement('submit', 'Gravar')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Gravar', 'Cancelar');
    }

}
