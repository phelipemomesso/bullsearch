<?php

class Momesso_Default_Form_Register_CustomerEdit extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('class', 'form-horizontal')
                ->setAttrib('role', 'form')
                ->setAttrib('id', 'form-register-edit');

  
        $fisrtName = $this->createElement('text', 'first_name', array('label' => 'First Name * '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'First Name');;
        $this->addElement($fisrtName);

        $lastName = $this->createElement('text', 'last_name', array('label' => 'Last Name * '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'Last Name');;
        $this->addElement($lastName);

        $email = $this->createElement('text', 'email', array('label' => 'E-mail * '))
                ->setRequired(TRUE)
                ->addValidator('EmailAddress')
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'E-mail');
        $this->addElement($email);

        $Model_Country = new Model_Country();
        $Country = $Model_Country->getCountries();


        $country = $this->createElement('select', 'country', array('label' => 'Country *'));
        $country->addMultiOption('', '--Select--');
        foreach ($Country as $v) {
            $country->addMultiOption($v['cod_country'], $v['name']);
        }
        $country->setRequired(TRUE);
        $this->addElement($country);

        $city = $this->createElement('text', 'city', array('label' => 'City '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'City');;
        $this->addElement($city);

        $state = $this->createElement('text', 'state', array('label' => 'State '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'State');;
        $this->addElement($state);

        $zip = $this->createElement('text', 'zip', array('label' => 'Zip Code or Postal Code * '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 15)
                ->setAttrib('size', 15)
                ->setAttrib('placeholder', 'Zip Code or Postal Code');;
        $this->addElement($zip);

        $abs = $this->createElement('text', 'abs_customer', array('label' => 'ABS Customer Number : '))
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 15)
                ->setAttrib('size', 15)
                ->setAttrib('placeholder', 'ABS Customer Number ');;
        $this->addElement($abs);


      	$submit = $this->createElement('submit', 'Update')->setAttrib('class','btn-primary update-register')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Update', 'Cancelar');
    }

}
