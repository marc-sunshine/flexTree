<?php
class Application_Model_TreeMapper
{
	protected $_dbTable;
	protected $_elementCount = 0;
	protected $_treeId = 0;
	private $_nodeMapper;
 
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Tree');
        }
        return $this->_dbTable;
    }
    
    public function loadTree($treeId)
    {
    	$this->_treeId = $treeId;
    	$tree = new Application_Model_Tree();
    	$this->_nodeMapper = new Application_Model_NodeMapper();
        $result = $this->_nodeMapper->fetchAll($this->_treeId, 0);
        if (0 == count($result)) {
            return;
        }
        $tree->setTreeArray($this->generateTreeArray($result));
        $tree->setElementCount($this->_elementCount);
        
        return $tree;
        
        //$row = $result->current();
        
        //$guestbook->setId($row->id)
        //         ->setEmail($row->email)
        //          ->setComment($row->comment)
        //          ->setCreated($row->created);
    }
    
    public function generateTreeArray($nodes)
    {
    	$tree = array();
    	foreach($nodes as $node)
    	{
    		$this->_elementCount++;
    		$tree[$node->getId()]['node'] = $node;
    		$tree[$node->getId()]['childs'] = $this->generateTreeArrayGetChilds($node->getId());
    	}
    	//print_r($tree);
    	return $tree;
    }
    
    public function generateTreeArrayGetChilds($parentId)
    {
    	$treepart = array();
        $nodes = $this->_nodeMapper->fetchAll($this->_treeId, $parentId);
        if (0 == count($nodes)) {
        	return $treepart;
        }
        foreach($nodes as $node)
        {
        	$this->_elementCount++;
        	$treepart[$node->getId()]['node'] = $node;
        	$treepart[$node->getId()]['childs'] = $this->generateTreeArrayGetChilds($node->getId());
        }
        return $treepart;
    }
    
    // TODO: nicer!
    
    /**
     * saveOrder nestedSortable
     * @param unknown_type $items
     * @param unknown_type $parentId
     */
    public function saveOrder($items, $parentId = 0)
    {
    	if($id = 0)
    	{
    		$pos = 0;
    		foreach($items as $item)
    		{
    			$id = $item["id"];
    			
    			$data = array(
    				'nodePosition'      => $pos++
				);
    			$this->getDbTable()->update($data, 'ID = '.intval($id));
    			
    			if(is_array($item["children"]))
    			$this->saveOrder($item["children"], $id);
    		}
    	}
    	else {
    		$pos = 0;
    		foreach($items as $item)
    		{
    			$id = $item["id"];
    			if(isset($item["children"]) && is_array($item["children"]))
    			$this->saveOrder($item["children"], $id);
    			
				$data = array(
    				'parentID'      => $parentId,
					'nodePosition'	=> $pos++
				);
    			$this->getDbTable()->update($data, 'ID = '.intval($id));
    		}
    	}
    }
    
	public function saveOrderJsTree($items, $parentId = 0)
    {
    	$pos = 0;
    	if(is_array($items))
    	foreach($items as $item)
    	{
    		$id = $item["attr"]["id"];
    		if(isset($item["children"]) && is_array($item["children"]))
    		$this->saveOrderJsTree($item["children"], $id);
    		
			$data = array(
    			'parentID'      => $parentId,
				'nodePosition'	=> (isset($item["position"])) ? $item["position"] : $pos++
			);
    		$this->getDbTable()->update($data, 'ID = '.intval($id));
    	}
    }
   
	
}