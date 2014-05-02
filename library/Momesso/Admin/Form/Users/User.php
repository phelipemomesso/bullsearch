<?php

class Momesso_Admin_Form_Users_User extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
        
        $Group = new Model_Group();
        $Row = $Group->getGroups();
        $Dados = array();
        $Dados[''] = '-- Select a Group --';
        foreach ($Row as $v) {
            $Dados[$v['cod_group']] = stripslashes($v['name']);
        }

        $group = $this->createElement('select', 'group', array('label' => 'Group: '));
        $group->setRequired(true)->setMultiOptions($Dados)->setAttrib('class', 'customSelect');
        $this->addElement($group);

        $nome = $this->createElement('text', 'name', array('label' => 'Name: '))
                        ->setRequired(true)
                        ->addFilter('StripTags')
                        ->addFilter('stringTrim')
                        ->setAttrib('maxlength', 100)
                        ->setAttrib('class', 'span12');
        $this->addElement($nome);

        $user = $this->createElement('text', 'user', array('label' => 'User: '))
                        ->setRequired(true)
                        ->addFilter('StripTags')
                        ->addFilter('stringTrim')
                        ->setAttrib('maxlength', 20)
                        ->setAttrib('class', 'span2');
        $this->addElement($user);


        $submit = $this->createElement('submit', 'Gravar')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Gravar', 'Cancelar');
    }

}
