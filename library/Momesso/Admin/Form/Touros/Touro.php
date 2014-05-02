<?php

class Momesso_Admin_Form_Touros_Touro extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
        
       $visibleOptions = array(
            1 => "Visible",
            0 => "No"
        );

        $visible = $this->createElement('select', 'visible', array('label' => 'Visible:'));
        $visible->setRequired(false)
                ->setMultiOptions($visibleOptions);
        $this->addElement($visible);

        

        $submit = $this->createElement('submit', 'Gravar')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Gravar', 'Cancelar');
    }

}
