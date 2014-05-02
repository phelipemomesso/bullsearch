<?php

class Momesso_Plugins_Editor extends Zend_Form_Element_Xhtml {

    public $helper = 'formTextarea';

    public function init() {
        $sessionUsuario = Zend_Session::namespaceGet('sessionUsuario');

        $this->setView($this->getView());
        $this->_view->jQuery()->addJavascriptFile($this->_view->baseUrl() . '/admin/js/ckeditor/ckeditor.js')
                ->addJavascriptFile($this->_view->baseUrl() . '/admin/js/ckfinder/ckfinder.js');

        $this->_view->jQuery()->addOnLoad(
                "var editor = CKEDITOR.replace( '" . $this->getId() . "', {
				width: '667px',
				height: '500px',
				toolbar : 'Full',
				uiColor  : '" . $sessionUsuario['temaCor'] . "',
				skin : 'kama',
				disabled : 'true' 		
			});
			CKFinder.SetupCKEditor( editor, '" . $this->_view->baseUrl() . "/admin/js/ckfinder/' );"
        );
    }

}