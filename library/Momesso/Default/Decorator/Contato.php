<?php
class Momesso_Default_Decorator_Contato extends Zend_Form_Decorator_Abstract {
	public function buildLabel() {
		$element = $this->getElement ();
		
		$label = $element->getLabel () != '&nbsp;' ? $element->getLabel () : '';
		if ($element->isRequired ()) {
			$label = '* ' . $label;
		}
		if ($translator = $element->getTranslator ()) {
			$label = $translator->translate ( $label );
		}
		$label .= '';
		return $element->getView ()->formLabel ( $element->getName (), $label );
	}
	
	public function buildInput() {
		$element = $this->getElement ();
		$helper = $element->helper;
		$messages = $element->getMessages ();
		if (! empty ( $messages )) {
			$element->setAttribs ( array ('class' => str_replace ( ' erro', '', $element->getAttrib ( 'class' ) ) . ' erro' ) );
		}
		$label = $element->getLabel ();
		if (empty ( $label )) {
			
		}
		return $element->getView ()->$helper ( $element->getName (), $element->getValue (), $element->getAttribs (), $element->options );
	}
	
	public function buildErrors() {
		$element = $this->getElement ();
		$messages = $element->getMessages ();
		if (empty ( $messages )) {
			return '';
		}
		
		$view = $element->getView ();
		$view->getHelper ( 'formErrors' )->setElementStart ( '<div%s><span>' )->setElementSeparator ( '<span></span>' )->setElementEnd ( '</span></div>' );
		//print_r($NomeErro = $element->getErrors($this->getElement()));
		$MSGs = $element->getView ()->formErrors ( $messages );
		$Retorno = !empty($MSGs) ? 	'<br clear="all" />' . $element->getView ()->formErrors ( $messages ) : '';
		return $Retorno;
	}
	
	public function buildDescription() {
		$element = $this->getElement ();
		$desc = $element->getDescription ();
		if (empty ( $desc )) {
			return '';
		}
		return '<div class="description">' . $desc . '</div>';
	}
	
	public function render($content) {
		$element = $this->getElement ();
		if (! $element instanceof Zend_Form_Element) {
			return $content;
		}
		if (null === $element->getView ()) {
			return $content;
		}
		
		$separator = $this->getSeparator ();
		$placement = $this->getPlacement ();
		$label = $this->buildLabel ();
		$input = $this->buildInput ();
		$errors = $this->buildErrors ();
		$desc = $this->buildDescription ();
		
		$errors = empty ( $errors ) ? $errors . '<br />' : $errors;
		
		$output = '<div class="form element">' . $label . '' . $input . $desc . $errors . '</div>';
		
		switch ($placement) {
			case (self::PREPEND) :
				return $output . $separator . $content;
			case (self::APPEND) :
			default :
				return $content . $separator . $output;
		}
	}
}