<?php

class Momesso_Admin_Form_Touros_ImagemEdit extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');

        $nome1 = $this->createElement('text', 'name', array('label' => 'Nome Imagem: '))
		        ->setRequired(true)
		        ->addFilter('StripTags')
		        ->addFilter('stringTrim')
		        ->setAttrib('maxlength', 100)
		        ->setAttrib('class', 'form-control');
        $this->addElement($nome1);

         $typeOptions = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type = $this->createElement('select', 'type', array('label' => 'Type of Photo:'));
        $type->setRequired(false)
                ->setMultiOptions($typeOptions);
        $this->addElement($type);

        
        $arquivo1 = $this->createElement('file','imagem',array('label'=>'Image : ( 640px X 480px ) / Format: jpg'));
        $arquivo1->setRequired(true)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo1);
        
        $html = new Momesso_Plugins_Htmlform('foto');
        $html->removeDecorator('label');
        $this->addElement($html);
       
        $submit = $this->createElement('submit', 'Save')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Save', 'Cancelar');
    }

}
