<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {

    protected function _initConfig() {
        $config = new Zend_Config($this->getOptions());
        Zend_Registry::set('config', $config);
    }
    
	protected function _initAutoLoader() {
        
		$autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace("Momesso");
        $autoloader->registerNamespace("EasyBib");
        
        $autoloader->autoload('Momesso_Plugins_WideImage_WideImage');
        $autoloader->autoload('Momesso_Plugins_PHPExcel_Classes_PHPExcel');
        $autoloader->autoload('Momesso_Plugins_MPDF53_mpdf');
        
        return $autoloader;
    }
    
    protected function _initPlaceholders() {
    	$this->bootstrap('View');
    	$view = $this->getResource('View');
    	$view->doctype('HTML5');
    
    	$title = Zend_Registry::get('config');
    	$view->headTitle($title->projectName)->setSeparator(' - ');
    
    	//$view->addHelperPath("ZendX/JQuery/View/Helper", "ZendX_JQuery_View_Helper");
    	//$view->jQuery()->enable();
    	//$view->jQuery()->uiEnable();
    }
    
    protected function _initCache() {
    	$front = array('automatic_serialization' => true);
    	$back = array('cache_dir' => APPLICATION_PATH . '/../tmp');
    	$cache = Zend_Cache::factory('Core', 'File', $front, $back);
    	Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
    }
    
    
	protected function _initDb() {
            $options = $this->getOption('resources');
            $db = Zend_Db::factory($options['db']['adapter'], $options['db']['params']);
            Zend_Db_Table_Abstract::setDefaultAdapter($db);
            $db->getConnection();
            Zend_Registry::set('db', $db);

            $front = array('automatic_serialization' => true);
            $back = array('cache_dir' => '../tmp');
            $cache = Zend_Cache::factory('Core', 'File', $front, $back);
            Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
	}

	protected function _initPlugins() {
        
       $front = Zend_Controller_Front::getInstance();
		
        $front->registerPlugin(new Momesso_Plugins_Layout())
                ->registerPlugin(new Momesso_Plugins_Acl())
                ->registerPlugin(new Momesso_Plugins_Auth())
                ->registerPlugin(new Momesso_Plugins_Banner())
                ->registerPlugin(new Momesso_Plugins_ApagaDiretorio());
    }
    
    protected function _initAutoload() {
        $moduleLoader = new Zend_Application_Module_Autoloader(array(
                'namespace' => '',
                'basePath' => APPLICATION_PATH)
        );
        return $moduleLoader;
    }

}

