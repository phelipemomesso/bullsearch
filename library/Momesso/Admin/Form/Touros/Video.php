<?php

class Momesso_Admin_Form_Touros_Video extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
        
        $name = $this->createElement('text', 'name', array('label' => 'Name: '))
                        ->setRequired(true)
                        ->addFilter('StripTags')
                        ->addFilter('stringTrim')
                        ->setAttrib('maxlength', 30)
                        ->setAttrib('class', '');
        $this->addElement($name);

        $data = $this->createElement('text', 'video', array('label' => 'Url From Youtube: '))
                        ->setRequired(true)
                        ->addFilter('StripTags')
                        ->addFilter('stringTrim')
                        ->setAttrib('maxlength', 50)
                        ->setAttrib('class', '');
        $this->addElement($data);

       $statusOptions = array(
            1 => "Yes",
            0 => "No"
        );

        $status = $this->createElement('select', 'status', array('label' => 'Published:'));
        $status->setRequired(TRUE)
                ->setMultiOptions($statusOptions)
                ->setAttrib('class', 'span2');
        $this->addElement($status);

        

        $submit = $this->createElement('submit', 'Gravar')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Gravar', 'Cancelar');
    }

}
