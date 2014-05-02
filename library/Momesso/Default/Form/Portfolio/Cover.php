<?php

class Momesso_Default_Form_Portfolio_Cover extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('class', 'form-horizontal')
                ->setAttrib('role', 'form');


        $title = $this->createElement('text', 'title', array('label' => 'Title '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('class', 'form-control')
                ->setAttrib('placeholder', 'Title');
        $this->addElement($title);        
        
        $comments = $this->createElement ( 'textarea', 'comments_cover',array('label'=>'Comments') );
        $comments->setRequired ( false )
                    ->addFilter ( 'StripTags' )
                    ->addValidator ( 'stringLength', false, array (10, 5000 ) )
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('rows', '6')
                    ->setAttrib('placeholder', 'Comments...');
        $this->addElement($comments);

        $arquivo1 = $this->createElement('file','imagem1',array('label'=>'ABS Country Specific Logo / Format: jpg'));
        $arquivo1->setRequired(true)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, '2mb')
                ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo1);

        $arquivo2 = $this->createElement('file','imagem2',array('label'=>'Picture / Format: jpg'));
        $arquivo2->setRequired(true)
                ->addValidator('Count', false, 1)
                ->addValidator('Size', false, '2mb')
                ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo2);
    

      	$submit = $this->createElement('submit', 'Save')->setAttrib('class','btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Save', 'Cancelar');
    }

}
