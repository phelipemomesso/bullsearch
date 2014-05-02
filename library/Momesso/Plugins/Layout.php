<?

class Momesso_Plugins_Layout extends Zend_Controller_Plugin_Abstract {

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        $layout = Zend_Layout::getMvcInstance ();

        if ($request->getModuleName() == 'ajax') {
            Zend_Registry::set('ajax', true);
            $request->setModuleName('default');
            $Controller = $request->getControllerName();
            $Controller = strpos($Controller, '-') > -1 ? str_replace('-', '', $Controller) : $Controller;
            Zend_Registry::set('Params', $request->getParams());
            $request->setControllerName($Controller);
            $layout->setLayout('ajax')->setLayoutPath(APPLICATION_PATH . '/modules/default/layouts');
        } else {
            /*$moduleName = $request->getModuleName();

            $moduleName = empty($moduleName) ? 'default' : $moduleName;
            $layout->setLayout('default')->setLayoutPath(APPLICATION_PATH . '/modules/' . $moduleName . '/layouts');*/

            
		$layout->setLayout($request->getModuleName())->setLayoutPath(APPLICATION_PATH . '/modules/' . $request->getModuleName() . '/layouts');
        }
    }

}

