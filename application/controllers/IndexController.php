<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$request = $this->getRequest();
    	$test = $request->getParam('test');
    	$this->view->test = $test;
    }
    
	public function testAction()
    {
        echo "test";
    }


}

