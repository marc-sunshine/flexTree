<?php

class Contentcontroller extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function contentAction()
    {
    	$request = $this->getRequest();
    	$nodeName = $request->getParam('title');
    	$mapper  = new Application_Model_NodeMapper();
    	$node = $mapper->find($nodeName);
    	$this->_helper->viewRenderer($node->getNodeName()); 
    	$this->view->nodeNav = $node;
    	if($node->getParentId() != 0)
    		$this->view->nodeNavId = $node->getParentId();
    }
    
	
}

