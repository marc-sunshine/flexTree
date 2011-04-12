<?php

class Admincontroller extends Zend_Controller_Action
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
    
	public function treeAction()
    {
    	//$this->_helper->ModelLoader("Tree");
    	//$treeMapper = new Application_Model_TreeMapper();
    	//$tree = $treeMapper->loadTree(1);
    	//print_r($tree);
    	//print_r($tree->getJson());
    	//$treeMapper = new Application_Model_TreeMapper();
    	//$tree = $treeMapper->loadTree(1);
    	//print_r($tree->getTreePart(4));
    	
    	$mapper  = new Application_Model_NodeMapper();
    	$this->view->nodeNav = $mapper->find(2);
    	$this->view->treeJS = true;
    	
    	$treeMapper = new Application_Model_TreeMapper();
    	$tree = $treeMapper->loadTree(1);
        
        $form    = new Application_Form_Node($tree);
        $this->view->form = $form;
    }
    
	public function treenodeAction()
    {
    	//$this->_helper->ModelLoader("Tree");
    	//$treeMapper = new Application_Model_TreeMapper();
    	//$tree = $treeMapper->loadTree(1);
    	//print_r($tree->getJson());
    	$request = $this->getRequest();
    	$nodeId = $request->getParam('node');
    	$mapper  = new Application_Model_NodeMapper();
    	$treeMapper = new Application_Model_TreeMapper();
    	$tree = $treeMapper->loadTree(1);
    	$node = $mapper->find($nodeId);
    	$pathA = $this->view->getScriptPaths();
        $fileName = $pathA[0]. 'content/'.$node->getNodeNameFile() .'.phtml';
        if(file_exists($fileName))
        	$node->content = file_get_contents($fileName);
        else 
        	$node->content = '';
    	
    	//$this->_helper->layout()->node = $node;
    	
    	if($request->getParam('delete') == 1)
    	{
            if($mapper->delete($nodeId))
            	return $this->_helper->redirector('tree', 'admin');
    	}
    	
    	if($node->getNodeType() == 1)
    		$form    = new Application_Form_Htmlnode($tree, $node);
    	if($node->getNodeType() == 2)
    		$form    = new Application_Form_Linknode($tree, $node);

    	if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                if($node->getNodeType() == Application_Model_Node::NODE_TYPE_HTML)
                {
                	$pathA = $this->view->getScriptPaths();
                	$fileName = $pathA[0]. 'content/'.$node->getNodeNameFile() .'.phtml';
                	echo $fileName;
                	//if(!file_exists($fileName))
                	//{
					//	$fileHandle = fopen($fileName, 'w') or die("can't open file");
					//	fclose($fileHandle);
                	//}
                	//else {
                		// TODO: Nur wenn nötig / Change tracken
						$fh = fopen($fileName, 'w') or die("can't open file");
						fwrite($fh, $form->getValue('content'));
						fclose($fh);
						$fileNameOld = $fileName;
						$node->setOptions($form->getValues());
						$fileName = $pathA[0]. 'content/'.$node->getNodeNameFile() .'.phtml';
						rename($fileNameOld, $fileName);
                	//}
                	
                	$node->setOptions($form->getValues());
               	 	$nodeId = $mapper->save($node);
                	
                }
                
                //return $this->_helper->redirector('treenode', 'admin', null, array('node' => $nodeId));
            }
        }
    		
    	$this->view->form = $form;
    	$this->view->node = $node;
    }
    
    public function newtreenodeAction()
    {
        $request = $this->getRequest();
        
        $treeMapper = new Application_Model_TreeMapper();
    	$tree = $treeMapper->loadTree(1);
        
        $form    = new Application_Form_Node($tree);
        
        
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($request->getPost())) {
                $node = new Application_Model_Node($form->getValues());
                $mapper  = new Application_Model_NodeMapper();
                $nodeId = $mapper->save($node);
                return $this->_helper->redirector('treenode', 'admin', null, array('node' => $nodeId));
            }
        }
 
        $this->view->form = $form;
    }
    
    
    public function treejsonAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->getHelper('viewRenderer')->setNoRender();
    	$treeMapper = new Application_Model_TreeMapper();
    	$tree = $treeMapper->loadTree(1);
    	echo $tree->getJson();
    }
    
	public function treejsonsaveAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->getHelper('viewRenderer')->setNoRender();
    	
    	$mapper = new Application_Model_TreeMapper();
    	//$mapper->saveOrder($_POST["nested-sortable-widget"]["items"]);
    	$mapper->saveOrderJsTree(array($_POST));
    	echo "OK";
    }

}

