<?php

class Momesso_Admin_Form_Customer_Customer extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('id', 'form');
        
        $fisrtName = $this->createElement('text', 'first_name', array('label' => 'Fisrt Name * '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('size', 50)
                ->setAttrib('placeholder', 'Fisrt Name');;
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

        $zip = $this->createElement('text', 'zip', array('label' => 'Zip * '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 15)
                ->setAttrib('size', 15)
                ->setAttrib('placeholder', 'Zip');;
        $this->addElement($zip);

        $abs = $this->createElement('text', 'abs_customer', array('label' => 'ABS Customer: '))
                ->setRequired(false)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 15)
                ->setAttrib('size', 15)
                ->setAttrib('placeholder', 'ABS Customer');;
        $this->addElement($abs);

        $typeOptions = array(
            1 => "Normal",
            2 => "ABS Employee",
            3 => "Agent"
        );

        $type = $this->createElement('select', 'type', array('label' => 'Type:'));
        $type->setRequired(TRUE)
                ->setMultiOptions($typeOptions);
        $this->addElement($type);

        $portfolioOptions = array(
            1 => "Yes",
            0 => "No"
        );

        $portfolio = $this->createElement('select', 'portfolio', array('label' => 'Portfolio Cover:'));
        $portfolio->setRequired(TRUE)
                ->setMultiOptions($portfolioOptions);
        $this->addElement($portfolio);

        $statusOptions = array(
            1 => "Active",
            0 => "Inactive"
        );

        $status = $this->createElement('select', 'situacao', array('label' => 'Status:'));
        $status->setRequired(TRUE)
                ->setMultiOptions($statusOptions);
        $this->addElement($status);


        $submit = $this->createElement('submit', 'Gravar')->setAttrib('class','btn btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Gravar', 'Cancelar');
    }

}
