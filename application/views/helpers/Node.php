<?php
class Zend_View_Helper_Node extends Zend_View_Helper_Abstract {
   
    public function node() {
       return $this;
    }
    
    public function nodeType($nodeType) {
    	echo Application_Model_Node::nodeType($nodeType);
    }
    
   
}