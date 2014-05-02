<?php

class Momesso_Default_Form_Portfolio_Email extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('class', 'form-horizontal')
                ->setAttrib('role', 'form');

  
        $name = $this->createElement('text', 'name', array('label' => 'Your Name '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'Your Name');;
        $this->addElement($name);

        $email1 = $this->createElement('text', 'your_email', array('label' => 'Your E-mail '))
                ->setRequired(TRUE)
                ->addValidator('EmailAddress')
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'E-mail');
        $this->addElement($email1);

        $customerName = $this->createElement('text', 'customer_name', array('label' => 'Customer Name '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'Customer Name');;
        $this->addElement($customerName);

        $email = $this->createElement('text', 'customer_email', array('label' => 'Customer E-mail '))
                ->setRequired(TRUE)
                ->addValidator('EmailAddress')
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'E-mail');
        $this->addElement($email);

        $mensagem = $this->createElement ( 'textarea', 'message',array('label'=>'Message :') );
                    $mensagem->setRequired ( true )
                    ->addFilter ( 'StripTags' )
                    ->addValidator ( 'stringLength', false, array (10, 5000 ) )
                    ->setAttrib('class', 'form-control')
                    ->setAttrib('rows', '8')
                    ->setAttrib('placeholder', 'Enter your message here...');
        $this->addElement($mensagem);
    

      	$submit = $this->createElement('submit', 'Send')->setAttrib('class','btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Send', 'Cancelar');
    }

}
