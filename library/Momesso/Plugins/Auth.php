<?php

class Momesso_Plugins_Auth extends Zend_Controller_Plugin_Abstract {

    public function preDispatch(Zend_Controller_Request_Abstract $request) {

        $module = $request->getModuleName();
        $controller = $request->getControllerName();
        $action = $request->getActionName();

        $auth = Zend_Auth::getInstance();
        $identify = $auth->getInstance()->getIdentity();

        if ($module == 'admin') {
            
        	if (!$auth->hasIdentity() and $controller != 'login') {
                
            	$response = $this->getResponse();
                $response->setRedirect($this->getRequest()->getBaseUrl() . '/admin/login');
                $response->sendHeaders();
                exit();

            } elseif ($auth->hasIdentity() and $controller != 'login' and $controller != 'permissao') {
                
            	$acl = Zend_Registry::get('acl');

                $isAllowed = $acl->isAllowed($identify->group,
                                $request->getControllerName(),
                                $request->getActionName());
                if (!$isAllowed) {
                    
                	$response = $this->getResponse();
                    $response->setRedirect($this->getRequest()->getBaseUrl() . '/admin/permissao');
                    $response->sendHeaders();
                    exit();
                }
            }
        }
    }

}