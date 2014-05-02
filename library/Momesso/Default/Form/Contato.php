<?php

class Momesso_Default_Form_Contato extends EasyBib_Form {

    	public function init() {

        $this->setAction('')
                ->setMethod('post')
                ->setAttrib('enctype', 'multipart/form-data')
                ->setAttrib('class', 'form-horizontal')
                ->setAttrib('role', 'form');

  
        $nome = $this->createElement('text', 'nome', array('label' => 'Nome Completo: '))
                ->setRequired(TRUE)
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 100)
                ->setAttrib('placeholder', 'Nome Completo')
                ->setAttrib('class', 'form-control');
        $this->addElement($nome);

        $cidade = $this->createElement('text', 'cidade', array('label' => 'Cidade :'));
        $cidade->setRequired(TRUE)
                ->addValidator('stringLength', false, array(2, 50))
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('placeholder', 'Cidade')
                ->setAttrib('class', 'form-control');
        $this->addElement($cidade);

        $estadoOptions = array(
            "" => "--Selecione--",
            "AC" => "Acre",
            "AL" => "Alagoas",
            "AP" => "Amapá",
            "AM" => "Amazonas",
            "BA" => "Bahia",
            "CE" => "Ceará",
            "DF" => "Distrito Federal",
            "ES" => "Espiríto Santo",
            "GO" => "Goiás",
            "MA" => "Maranhão",
            "MT" => "Mato Grosso",
            "MS" => "Mato Grosso do Sul",
            "MG" => "Minas Gerais",
            "PA" => "Pará",
            "PB" => "Paraíba",
            "PR" => "Paraná",
            "PE" => "Pernambuco",
            "PI" => "Piauí",
            "RJ" => "Rio de Janeiro",
            "RN" => "Rio Grande do Norte",
            "RS" => "Rio Grande do Sul",
            "RO" => "Rondônia",
            "RR" => "Roraima",
            "SC" => "Santa Catarina",
            "SP" => "São Paulo",
            "SE" => "Sergipe",
            "TO" => "Tocantins"
        );

        $estado = $this->createElement('select', 'estado', array('label' => 'Estado :'));
        $estado->setRequired(TRUE)
                ->setMultiOptions($estadoOptions)
                ->setAttrib('class', 'form-control');
        ;
        $this->addElement($estado);

        $email = $this->createElement('text', 'email', array('label' => 'E-mail: '))
                ->setRequired(FALSE)
                ->addValidator('EmailAddress')
                ->addFilter('StripTags')
                ->addFilter('stringTrim')
                ->setAttrib('maxlength', 50)
                ->setAttrib('placeholder', 'E-mail')
                ->setAttrib('class', 'form-control');
        $this->addElement($email);
        
        $mensagem = $this->createElement ( 'textarea', 'mensagem',array('label'=>'Mensagem :') );
			        $mensagem->setRequired ( true )
			        ->addFilter ( 'StripTags' )
			        ->addValidator ( 'stringLength', false, array (10, 5000 ) )
			        ->setAttrib('class', 'form-control')
        			->setAttrib('rows', '8')
			        ->setAttrib('placeholder', 'Digite sua mensagem aqui...');
			        $this->addElement($mensagem);
        

      	$submit = $this->createElement('submit', 'Enviar')->setAttrib('class','btn-primary')->setIgnore(true);

        $this->addElement($submit);
        
        EasyBib_Form_Decorator::setFormDecorator($this,EasyBib_Form_Decorator::BOOTSTRAP, 'Enviar', 'Cancelar');
    }

}
