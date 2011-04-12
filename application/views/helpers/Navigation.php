<?php
class Zend_View_Helper_Navigation extends Zend_View_Helper_Abstract {
   
    public function navigation($level = 0) {
    	
    	$treeMapper = new Application_Model_TreeMapper();
    	$tree = $treeMapper->loadTree(1);
    	$this->view->tree = $tree->getTreePart($level);
        return $this->view->render('main_nav.phtml');
       
    }
    
   
}