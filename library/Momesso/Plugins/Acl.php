<?php

class Momesso_Plugins_Acl extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {
        
        //criando a instancia do ACL e definindo os papeis

        $module = $this->_request->getModuleName();

        if ($module == 'admin') {

            $acl = new Zend_Acl();

            $Model_Group 		= new Model_DbTable_Group();
            $Model_Controller 	= new Model_DbTable_Controller();
            $Model_Role	 		= new Model_DbTable_Role();

            // Cria os controllers
            
            $controllers = $Model_Controller->fetchAll();
            
            foreach ($controllers as $controller) {
                
            	$acl->add(new Zend_Acl_Resource($controller['name']));
            }
            
            $acl->add(new Zend_Acl_Resource('error'));
            $acl->add(new Zend_Acl_Resource('login'));
            
            //////////////////////

            $auth = Zend_Auth::getInstance();
            $identify = $auth->getIdentity();

            if ($auth->hasIdentity()) {
                
            	$acl->addRole(new Zend_Acl_Role($identify->group));
                
                if (count($Model_Group->fetchRow("cod_group = '$identify->group' AND name = 'superadmin'"))) {
                    
                	$acl->allow($identify->group);
                
                } elseif (count($Model_Group->fetchRow("cod_group = '$identify->group' AND name = 'admin'"))) {
                    
                	$acl->allow($identify->group);
                    $acl->deny($identify->group, 'modulos');
                
                } else {
                    
                	$groups = $Model_Role->fetchAll('grupo = '.$identify->group);
                    
                    foreach ($groups as $group) {
                        
                    	$grupoController = $groups->current()->findParentRow('Model_DbTable_Controller');
                    	$actions = $groups->current()->findParentRow('Model_DbTable_Action');

                        $acl->allow($identify->group, $grupoController['name'], $actions['action']);

                    }
                    
                    $acl->allow($identify->group, 'login');
                }
            }
            
            $registry = Zend_Registry::getInstance();
            $registry->set('acl', $acl);
            
           
        }
    }

}