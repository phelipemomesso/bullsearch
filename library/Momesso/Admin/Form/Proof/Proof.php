<?php

class Momesso_Admin_Form_Proof_Proof extends EasyBib_Form {

	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');

        $nome = $this->createElement('text', 'nome', array('label' => 'Name'))
                        ->setRequired(true)
                        ->addFilter('StripTags')
                        ->addFilter('stringTrim')
                        ->setAttrib('maxlength', 100)
                        ->setAttrib('class', 'span12');
        $this->addElement($nome);

        $arquivo = $this->createElement('file','imagem',array('label'=>'Excel File'));
       	$arquivo->setRequired(true)
       		   ->addValidator('Count', false, 1)
       		   ->addValidator('Size', false, '2mb')
       		   ->addValidator('Extension', false, 'xls,xlsx');
    	$this->addElement($arquivo);

    	$html = new Momesso_Plugins_Htmlform('foto');
		$html->removeDecorator('label');
		$this->addElement($html);

        $submit = $this->createElement('submit', 'Gravar')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Gravar', 'Cancelar');
    }

}
