<?php

class Momesso_Admin_Form_Palavra_Translate extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
        
        $palavra = $this->createElement('hidden', 'palavra');
        $palavra->removeDecorator('Label');
        $this->addElement($palavra);
        
        
        $Model_Idioma = new Model_Idioma();
        $Row = $Model_Idioma->getLanguages();
        $Dados = array();
        $Dados[''] = '-- Selecione o Idioma --';
        foreach ($Row as $v) {
        	$Dados[$v['cod_language']] = stripslashes($v['nome']);
        }
        
        $language = $this->createElement('select', 'language', array('label' => 'Language: '));
        $language->setRequired(true)
        			->setMultiOptions($Dados)
        			->setAttrib('class', 'customSelect');
        $this->addElement($language);
        
        $palavra = $this->createElement('textarea', 'translate', array('label' => 'Tradução: '))
                        ->setRequired(true)
                        ->addFilters(array('stringTrim'))
                        ->setAttrib('class', 'span12')
        				->setAttrib('rows', '8');
        $this->addElement($palavra);

        $submit = $this->createElement('submit', 'Gravar')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Gravar', 'Cancelar');
    }

}
