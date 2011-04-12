<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    
    protected function _initDb()
    {
		$db = Zend_Db::factory('Pdo_Mysql', array(
    		'host'     => '127.0.0.1',
			'username' => 'root',
    		'password' => '',
    		'dbname'   => 'ngn'
		));
		Zend_Db_Table::setDefaultAdapter($db);
    }

    protected function _initRoute()
    {
    	//$router = $this->frontController->getRouter(); // gibt standardmäßig einen Rewrite Router zurück
    	//echo $router->getCurrentRouteName();
    	//$router->addRoute(
    	//'default'
		//);
    }
    
}

