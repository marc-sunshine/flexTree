<?php
class Application_Model_NodeMapper
{
    protected $_dbTable;
 
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
 
    public function save(Application_Model_Node $node)
    {
        $data = array(
            'treeId'   => $node->getTreeId(),
            'parentId' => $node->getParentId(),
            'nodeType' => $node->getNodeType(),
        	'nodePosition' => $node->getNodePosition(),
        	'nodeName' => $node->getNodeName(),
        	'nodeValue' => $node->getNodeValue(),
        );
 
        if (null === ($id = $node->getId())) {
            unset($data['id']);
            return $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
            return $id;
        }
    }
 
    public function find($id)
    {
    	$node = new Application_Model_Node();
    	if(is_numeric($id))
    	{
	        $result = $this->getDbTable()->find($id);
	        if (0 == count($result)) {
	            return;
	        }
	        $row = $result->current();
    	}
    	else {
			$select  = $this->getDbTable()->select()->where('nodeName = ?', $id);
			$row = $this->getDbTable()->fetchRow($select);
    	}
        $node->setId($row->Id)
                  ->setTreeId($row->treeId)
                  ->setParentId($row->parentId)
                  ->setNodeType($row->nodeType)
                  ->setNodePosition($row->nodePosition)
                  ->setNodeName($row->nodeName)
                  ->setNodeValue($row->nodeValue);
        return $node;
    }
    
    public function delete($id)
    {
    	return $this->getDbTable()->delete($this->getDbTable()->getAdapter()->quoteInto('Id = ?', $id));
    }
 
    public function fetchAll($treeId, $parentId)
    {
    	$select = $this->getDbTable()->select()->where('treeId = ? && parentId = '.intval($parentId), intval($treeId))->order("nodePosition ASC");
        $resultSet = $this->getDbTable()->fetchAll($select);
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Node();
            $entry->setId(1);
            $entry->setId($row->Id)
                  ->setTreeId($row->treeId)
                  ->setParentId($row->parentId)
                  ->setNodeType($row->nodeType)
                  ->setNodePosition($row->nodePosition)
                  ->setNodeName($row->nodeName)
                  ->setNodeValue($row->nodeValue);
            $entries[] = $entry;
        }
        return $entries;
    }
	
   
	
}