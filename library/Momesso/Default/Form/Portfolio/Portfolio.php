<?php

class Momesso_Default_Form_Portfolio_Portfolio extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('class', 'form-horizontal')
                ->setAttrib('role', 'form');


        $name = $this->createElement('text', 'name', array('label' => 'Name '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Name');;
        $this->addElement($name);

        $information = $this->createElement ( 'textarea', 'information',array('label'=>'Information') );
        $information->setRequired ( false )
                    ->addFilter ( 'StripTags' )
                    ->addValidator ( 'stringLength', false, array (10, 5000 ) )
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('rows', '4');
        $this->addElement($information);


        $comments = $this->createElement ( 'textarea', 'comments',array('label'=>'Comments') );
        $comments->setRequired ( false )
                    ->addFilter ( 'StripTags' )
                    ->addValidator ( 'stringLength', false, array (10, 5000 ) )
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('rows', '6');
        $this->addElement($comments);
    

      	$submit = $this->createElement('submit', 'Send')->setAttrib('class','btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Send', 'Cancelar');
    }

}
