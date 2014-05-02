<?php

class Momesso_Admin_Form_Touros_Comment extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
        
        $title = $this->createElement('text', 'title', array('label' => 'Title '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'Title');;
        $this->addElement($title);

        $name = $this->createElement('text', 'name', array('label' => 'First and Last Name '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'Fisrt and Last Name');;
        $this->addElement($name);

        $job = $this->createElement('text', 'job', array('label' => 'Job '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'Job');;
        $this->addElement($job);

        $Country = $this->createElement('text', 'country', array('label' => 'Country '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'Country');;
        $this->addElement($job);

        $comment = $this->createElement('textarea', 'comment', array('label' => 'Comment: '))
                        ->setRequired(true)
                        ->addFilters(array('stringTrim'))
                        ->setAttrib('rows', '8');
        $this->addElement($comment);


        $submit = $this->createElement('submit', 'Save')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Save', 'Cancelar');
    }

}
