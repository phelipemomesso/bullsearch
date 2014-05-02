<?php

class Admin_AclController extends Zend_Controller_Action {
	
	private function formGrupo() {
		$form = new Zend_Form();
		$form->setAttrib('class', 'form-geral');
		
		$grupo = $form->createElement('text', 'grupo')
						   ->setRequired(true)
						   ->addFilters(array('StripTags', 'stringTrim'))
						   ->setAttrib('class', 'campo-grande')
						   ->setLabel('Grupo:');
		
		$module = $form->createElement('select', 'module')
					   ->setRequired(true)
					   ->setLabel('Module:')
					   ->setAttrib('class', 'campo-curto');
					   
		$Model_Modules = new Model_Modules();
		$res = $Model_Modules->fetchAll(null, 'module');		
		foreach ($res as $rs) {
			$module->addMultiOptions(array($rs['cod_module'] => $rs['module']));
		}
				   
		$submit = $form->createElement('submit', 'Gravar')->setIgnore(true);
						   
		$form->addElements(array($grupo, $module, $submit));
		
		return $form;
	}
	
	private function formUsuario() {
		$form = new Zend_Form();
		$form->setAttrib('class', 'form-geral');
		
		$auth = Zend_Auth::getInstance()->getIdentity();		
		
		$nome = $form->createElement('text', 'nome', array('label' => 'Nome: '))
					   ->setRequired(true)
					   ->addFilters(array('StripTags', 'stringTrim'))
					   ->setAttrib('class', 'campo-grande');
							   
		$usuario = $form->createElement('text', 'usuario', array('label' => 'Usuário: '))
					   ->setRequired(true)
					   ->addFilters(array('StripTags', 'stringTrim'))					   
					   ->setAttrib('class', 'campo-grande');

		$email = $form->createElement('text', 'email', array('label' => 'Email: '))
					  ->setRequired(true)
					  ->addFilters(array('StripTags', 'stringTrim'))
					  ->addValidator('EmailAddress')
					  ->setAttrib('class', 'campo-grande');
					  
		$senha = $form->createElement('password', 'senha')
					 ->setLabel('Senha:')
					 ->setRequired(true)
					 ->addFilters(array('StripTags', 'stringTrim'))
					 ->addValidator ('stringLength', false, array (6, null))
					 ->setAttrib('class', 'campo-grande');
					 
		$confirma = $form->createElement('password', 'confirma')
					 ->setLabel('Confirma senha:')
					 ->setRequired(true)
					 ->addFilters(array('StripTags', 'stringTrim'))
					 ->setAttribs(array('class' => 'campo'))
					 ->addValidator ('stringLength', false, array (6, null))
					 ->addValidator('Identical', false, array('senha'))
					 ->setAttrib('class', 'campo-grande');
					  
		/*$tema = $form->createElement('select', 'tema')
					   ->setRequired(true)
					   ->setLabel('Tema:')
					   ->setAttrib('class', 'campo-medio');
					   
		$Model_Temas = new Model_Temas();
		$res = $Model_Temas->fetchAll(null, 'tema');		
		foreach ($res as $rs) {
			$tema->addMultiOptions(array($rs['cod_tema'] => $rs['tema']));
		}
		$tema->setValue(24);*/
		
		$grupo = $form->createElement('select', 'grupo')
					   ->setRequired(true)
					   ->setLabel('Grupo:')
					   ->setAttrib('class', 'campo-medio');
					   
		$Model_Grupos = new Model_Grupos();
		$cod_module = $this->_request->getParam('cod_module');
		$cod_grupo = $this->_request->getParam('cod_grupo');
		$res = $Model_Grupos->fetchAll("module = '$cod_module' and grupo != 'superadmin'", 'grupo');		
		foreach ($res as $rs) {
			$grupo->addMultiOptions(array($rs['cod_grupo'] => $rs['grupo']));
		}
		$grupo->setValue($cod_grupo);
					   
		$submit = $form->createElement('submit', 'Gravar')->setIgnore(true);
										 
		$form->addElements(array($nome, $usuario, $email, $senha, $confirma, $grupo, $submit));
		
		return $form;
	}
			
	public function indexAction() {		
		$Model_Grupos = new Model_Grupos();		
		$res = $Model_Grupos->fetchAll("grupo != 'superadmin'", 'grupo');
		
		$paginas = Zend_Paginator::factory($res);
		$paginas->setCurrentPageNumber($this->_getParam('pagina'), 1);
		$paginas->setItemCountPerPage(25);
		$paginas->setPageRange(10);
								   
		$this->view->res = $paginas;
	}
	
	public function novogrupoAction() {				
		$form = self::formGrupo();
		$form->getElement('grupo')->addValidator('Db_NoRecordExists', false, array('table' => 'mod_grupos', 'field' => 'grupo'));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$dados = $this->_request->getPost();
				if ($form->isValid($dados)) {
					unset($dados['Gravar']);				
					try {
						$Model_Grupos = new Model_Grupos();
						$Model_Grupos->insert($dados);
						$this->_redirect('/admin/'.$this->_request->getControllerName());
					} catch (Zend_Db_Exception $e) {
						$dialog = new ZendX_JQuery_View_Helper_DialogContainer();
						$dialog->setView($this->view);															
						echo $dialog->dialogContainer('dialog1', $e->getMessage(), array('draggable' => true, 'modal' => true, 'resizable' => false, 'title' => 'Erro na gravação', 'closeOnEscape' => true));
					}
				}
		}		
	}
	
	public function editargrupoAction() {
		$cod = $this->_request->getParam('cod');		
		$Model_Grupos = new Model_Grupos();
		$row = $Model_Grupos->fetchRow("cod_grupo = '$cod'")->toArray();		
    	$form = self::formGrupo();
    	$form->getElement('grupo')->addValidator('Db_NoRecordExists', false, array('table' => 'mod_grupos', 'field' => 'grupo', 'exclude' => array('field' => 'cod_grupo', 'value' => $cod)));
		
		if ($this->_request->isPost()) {
			$dados = $this->_request->getPost();
			if ($form->isValid($dados)) {
				unset($dados['Gravar']);				
				try {
					$Model_Grupos->update($dados, "cod_grupo = '$cod'");
					$this->_redirect('/admin/'.$this->_request->getControllerName());					
				} catch (Zend_Db_Exception $e) {
					$dialog = new ZendX_JQuery_View_Helper_DialogContainer();
					$dialog->setView($this->view);
					echo $dialog->dialogContainer('dialog1', $e->getMessage(), array('draggable' => true, 'modal' => true, 'resizable' => false, 'title' => 'Erro na gravação', 'closeOnEscape' => true, 'buttons' => array('OK' => new Zend_Json_Expr('function() { $(this).dialog(\'close\');}'))));
				}
			}
		}
		else {			
			$form->populate($row);
		}		
		$this->view->form = $form;
	}
	
	public function excluirgrupoAction() {
		$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$cod = $this->_request->getParam('cod');
    	$Model_Grupos = new Model_Grupos();
    	
    	$row = $Model_Grupos->find($cod)->current();  	
    	
    	$usuarios = $row->findDependentRowset('Model_Usuario');    	
    	foreach ($usuarios as $usuario) {
    	 	$usuarios->current()->delete();
    	}
    	
    	$row->delete();    	
    	$this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
	}
	
	public function usuariosAction() {
		$cod_grupo = $this->_request->getParam('cod_grupo');
		$Model_Usuarios = new Model_Usuario();
		$res = $Model_Usuarios->fetchAll("grupo = $cod_grupo", 'grupo');
		
		$paginas = Zend_Paginator::factory($res);
		$paginas->setCurrentPageNumber($this->_getParam('pagina'), 1);
		$paginas->setItemCountPerPage(25);
		$paginas->setPageRange(10);
								   
		$this->view->res = $paginas;
		if (count($this->view->res)) {
			$this->view->grupo = $res->current()->findParentRow('Model_Grupos');
		}
	}
	
	public function novousuarioAction() {
		$form = self::formUsuario();
		$cod_module = $this->_request->getParam('cod_module');
		$cod_grupo = $this->_request->getParam('cod_grupo');
		$form->getElement('usuario')->addValidator('Db_NoRecordExists', false, array('table' => 'mod_usuarios', 'field' => 'usuario'));
		$form->getElement('email')->addValidator('Db_NoRecordExists', false, array('table' => 'mod_usuarios', 'field' => 'email'));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$dados = $this->_request->getPost();
				if ($form->isValid($dados)) {
					unset($dados['confirma'], $dados['Gravar']);
					$dados['senha'] = md5($dados['senha']);				
					try {
						$Model_Usuarios = new Model_Usuario();
						$Model_Usuarios->insert($dados);
						$this->_redirect('/admin/'.$this->_request->getControllerName().'/usuarios/cod_grupo/'.$cod_grupo.'/cod_module/'.$cod_module);
					} catch (Zend_Db_Exception $e) {
						$dialog = new ZendX_JQuery_View_Helper_DialogContainer();
						$dialog->setView($this->view);															
						echo $dialog->dialogContainer('dialog1', $e->getMessage(), array('draggable' => true, 'modal' => true, 'resizable' => false, 'title' => 'Erro na gravação', 'closeOnEscape' => true));
					}
				}
		}		
	}
	
	public function editarusuarioAction() {
		$cod_module = $this->_request->getParam('cod_module');
		$cod_grupo = $this->_request->getParam('cod_grupo');
		$cod = $this->_request->getParam('cod');
		$Model_Usuario = new Model_Usuario();
    	$rs = $Model_Usuario->fetchRow("id = $cod")->toArray();
		$form = self::formUsuario();		
		$form->getElement('usuario')->addValidator('Db_NoRecordExists', false, array('table' => 'mod_usuarios', 'field' => 'usuario', 'exclude' => array('field' => 'id', 'value' => $cod)));
		$form->getElement('email')->addValidator('Db_NoRecordExists', false, array('table' => 'mod_usuarios', 'field' => 'email', 'exclude' => array('field' => 'id', 'value' => $cod)));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$dados = $this->_request->getPost();
				if ($form->isValid($dados)) {
					unset($dados['confirma'], $dados['Gravar']);
					$dados['senha'] = md5($dados['senha']);				
					try {
						$Model_Usuario->update($dados, "id = $cod");
						
						$sessionUsuario = new Zend_Session_Namespace('sessionUsuario');
						if ($cod == $sessionUsuario->id) {
							$Model_Temas = new Model_Temas();
							$tema = $Model_Temas->fetchRow("cod_tema = '$dados[tema]'");						
							$sessionUsuario->nome = $dados['nome'];
							$sessionUsuario->email = $dados['email'];
							$sessionUsuario->usuario = $dados['usuario'];
							$filter = new Zend_Filter();
							$filter->addFilter(new Zend_Filter_StringToLower());
							$filter->addFilter(new Zend_Filter_Word_SeparatorToDash());
							$sessionUsuario->tema = $filter->filter($tema['tema']);
							//$sessionUsuario->temaCor = $tema['cor'];
						}
						
						$this->_redirect('/admin/'.$this->_request->getControllerName().'/usuarios/cod_grupo/'.$cod_grupo.'/cod_module/'.$cod_module);
					} catch (Zend_Db_Exception $e) {
						$dialog = new ZendX_JQuery_View_Helper_DialogContainer();
						$dialog->setView($this->view);															
						echo $dialog->dialogContainer('dialog1', $e->getMessage(), array('draggable' => true, 'modal' => true, 'resizable' => false, 'title' => 'Erro na gravação', 'closeOnEscape' => true));
					}
				}
		}
		else {
			$form->populate($rs);
		}		
	}
	
	public function excluirusuarioAction() {
		$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$cod = $this->_request->getParam('cod');
    	$Model_Usuarios = new Model_Usuario();
    	$Model_Usuarios->find($cod)->current()->delete();
    	$this->_redirect($this->getRequest()->getServer('HTTP_REFERER'));
	}
	
	public function permissoesAction() {
		$cod_module = $this->_request->getParam('cod_module');
		$cod_grupo = $this->_request->getParam('cod_grupo');
		
		if ($this->_request->isPost()) {
			$dados = $this->_request->getPost();
			$Model_Roles_Grupos = new Model_Rolesgrupos();
			$Model_Roles_Actions = new Model_Rolesactions();
			unset($dados['Gravar']);
			$Model_Roles_Grupos->delete("grupo = '$cod_grupo'");
			$Model_Roles_Actions->delete("grupo = '$cod_grupo'");
			foreach ($dados as $controllers => $value) {
				$roleGrupo['controller'] = $controllers;
				$roleGrupo['module'] = $cod_module;
				$roleGrupo['grupo'] = $cod_grupo;
				$Model_Roles_Grupos->insert($roleGrupo);
				$insertId = $Model_Roles_Grupos->getAdapter()->lastInsertId();				
					foreach ($value as $actions) {
						$roleAction['controller'] = $controllers;
						$roleAction['grupo'] = $cod_grupo;
						$roleAction['role_grupos'] = $insertId;
						$roleAction['action'] = $actions;
						$Model_Roles_Actions->insert($roleAction);
					}
				
			}
			$this->_redirect('/admin/'.$this->_request->getControllerName());
		}
	}
    
	public function helpAction() {
    	$this->view->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$Model_Modules = new Model_Modules();
    	$queryModule = $Model_Modules->fetchRow(array('module = ?' => $this->_request->getModuleName()));
    	
    	$Model_Controller = new Model_Controllers();    	
    	$queryController = $Model_Controller->fetchRow(array('controller = ?' => $this->_request->getControllerName(), 'module = ?' => $queryModule['cod_module']));
    	
    	echo $queryController['descricao'];
    }
	
}