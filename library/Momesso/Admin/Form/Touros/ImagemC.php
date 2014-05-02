<?php

class Momesso_Admin_Form_Touros_Imagem extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
           
        $nome1 = $this->createElement('text', 'nome1', array('label' => 'Image 1: '))
				        ->setRequired(true)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
        $this->addElement($nome1);
        
        $arquivo1 = $this->createElement('file','imagem1',array('label'=>'Image : ( 640px X 480px ) / Format: jpg'));
        $arquivo1->setRequired(true)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo1);

        $typeOptions1 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type1 = $this->createElement('select', 'type1', array('label' => 'Type of Photo:'));
        $type1->setRequired(TRUE)
                ->setMultiOptions($typeOptions1);
        $this->addElement($type1);

        
        $nome2 = $this->createElement('text', 'nome2', array('label' => 'Image 2: '))
				        ->setRequired(false)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
        $this->addElement($nome2);
        
        $arquivo2 = $this->createElement('file','imagem2',array('label'=>'Image 2 : ( 640px X 480px ) / Format: jpg'));
        $arquivo2->setRequired(false)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo2);

        $typeOptions2 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type2 = $this->createElement('select', 'type2', array('label' => 'Type of Photo:'));
        $type2->setRequired(false)
                ->setMultiOptions($typeOptions2);
        $this->addElement($type2);


        
        
        $nome3 = $this->createElement('text', 'nome3', array('label' => 'Image 3: '))
				        ->setRequired(false)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
        $this->addElement($nome3);
        
        $arquivo3 = $this->createElement('file','imagem3',array('label'=>'Imagem 3 : ( 640px X 480px ) / Format: jpg'));
        $arquivo3->setRequired(false)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo3);


        $typeOptions3 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type3 = $this->createElement('select', 'type3', array('label' => 'Type of Photo:'));
        $type3->setRequired(false)
                ->setMultiOptions($typeOptions3);
        $this->addElement($type3);




        
        $nome4 = $this->createElement('text', 'nome4', array('label' => 'Image 4: '))
				        ->setRequired(false)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
        $this->addElement($nome4);
        
        $arquivo4 = $this->createElement('file','imagem4',array('label'=>'Image 4 : ( 640px X 480px ) / Format: jpg'));
        $arquivo4->setRequired(false)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo4);

        $typeOptions4 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type4 = $this->createElement('select', 'type4', array('label' => 'Type of Photo:'));
        $type4->setRequired(false)
                ->setMultiOptions($typeOptions4);
        $this->addElement($type4);
        
        
        
        $nome5 = $this->createElement('text', 'nome5', array('label' => 'Image 5: '))
				        ->setRequired(false)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
				        $this->addElement($nome5);
        
        $arquivo5 = $this->createElement('file','imagem5',array('label'=>'Image 5 : ( 640px X 480px ) / Format: jpg'));
        $arquivo5->setRequired(false)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo5);

        $typeOptions5 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type5 = $this->createElement('select', 'type5', array('label' => 'Type of Photo:'));
        $type5->setRequired(false)
                ->setMultiOptions($typeOptions5);
        $this->addElement($type5);



        
        $nome6 = $this->createElement('text', 'nome6', array('label' => 'Image 6: '))
				        ->setRequired(false)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
        $this->addElement($nome6);
        
        $arquivo6 = $this->createElement('file','imagem6',array('label'=>'Image 6 : ( 640px X 480px ) / Format: jpg'));
        $arquivo6->setRequired(false)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo6);

        $typeOptions6 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type6 = $this->createElement('select', 'type6', array('label' => 'Type of Photo:'));
        $type6->setRequired(false)
                ->setMultiOptions($typeOptions6);
        $this->addElement($type6);



        
        
        $nome7 = $this->createElement('text', 'nome7', array('label' => 'Image 7: '))
				        ->setRequired(false)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
        $this->addElement($nome7);
        
        $arquivo7 = $this->createElement('file','imagem7',array('label'=>'Image 7 : ( 640px X 480px ) / Format: jpg'));
        $arquivo7->setRequired(false)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo7);

        $typeOptions7 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type7 = $this->createElement('select', 'type7', array('label' => 'Type of Photo:'));
        $type7->setRequired(false)
                ->setMultiOptions($typeOptions7);
        $this->addElement($type7);


        
        
        $nome8 = $this->createElement('text', 'nome8', array('label' => 'Image 8: '))
				        ->setRequired(false)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
        $this->addElement($nome8);
        
        $arquivo8 = $this->createElement('file','imagem8',array('label'=>'Image 8 : ( 640px X 480px ) / Format: jpg'));
        $arquivo8->setRequired(false)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo8);

        $typeOptions8 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type8 = $this->createElement('select', 'type8', array('label' => 'Type of Photo:'));
        $type8->setRequired(false)
                ->setMultiOptions($typeOptions8);
        $this->addElement($type8);




        
        
        $nome9 = $this->createElement('text', 'nome9', array('label' => 'Image 9: '))
				        ->setRequired(false)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
        $this->addElement($nome9);
        
        $arquivo9 = $this->createElement('file','imagem9',array('label'=>'Imagem 9 : ( 640px X 480px ) / Formato: jpg'));
        $arquivo9->setRequired(false)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo9);

        $typeOptions9 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type9 = $this->createElement('select', 'type9', array('label' => 'Type of Photo:'));
        $type9->setRequired(false)
                ->setMultiOptions($typeOptions9);
        $this->addElement($type9);



        
        
        $nome10 = $this->createElement('text', 'nome10', array('label' => 'Image 10: '))
				        ->setRequired(false)
				        ->addFilter('StripTags')
				        ->addFilter('stringTrim')
				        ->setAttrib('maxlength', 100)
				        ->setAttrib('class', 'form-control');
        $this->addElement($nome10);
        
        $arquivo10 = $this->createElement('file','imagem10',array('label'=>'Image 10 : ( 640px X 480px ) / Format: jpg'));
        $arquivo10->setRequired(false)
		        ->addValidator('Count', false, 1)
		        ->addValidator('Size', false, '2mb')
		        ->addValidator('Extension', false, 'jpg');
        $this->addElement($arquivo10);

        $typeOptions10 = array(
            1 => "Main",
            2 => "Thumbnails"
        );

        $type10 = $this->createElement('select', 'type10', array('label' => 'Type of Photo:'));
        $type10->setRequired(false)
                ->setMultiOptions($typeOptions10);
        $this->addElement($type10);
        
       
        $submit = $this->createElement('submit', 'Save')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Save', 'Cancelar');
    }

}
