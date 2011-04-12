<?php

class Application_Model_Node
{
	protected $_Id;
	protected $_treeId = 1;
	protected $_parentId = 0;
	protected $_nodeType;
	protected $_nodePosition = 0;
	protected $_nodeName;
	protected $_nodeValue = '';
	
	const NODE_TYPE_HTML = 1;
	const NODE_TYPE_LINK = 2;
	
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
	
	public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
	
	
	public function getId()
	{
		return $this->_Id;
	}
	
	public function getTreeId()
	{
		return $this->_treeId;
	}
	
	public function getParentId()
	{
		return $this->_parentId;
	}
	
	public function getNodeType()
	{
		return $this->_nodeType;
	}
	
	public function getNodePosition()
	{
		return $this->_nodePosition;
	}
	
	public function getNodeName()
	{
		return $this->_nodeName;
	}
	
	public function getNodeValue()
	{
		return $this->_nodeValue;
	}
	
	public function setId($id)
	{
		$this->_Id= $id;
		return $this;
	}
	
	public function setTreeId($treeId)
	{
		$this->_treeId = $treeId;
		return $this;
	}
	
	public function setParentId($parentId)
	{
		$this->_parentId = $parentId;
		return $this;
	}
	
	public function setNodeType($nodeType)
	{
		$this->_nodeType = $nodeType;
		return $this;
	}
	
	public function setNodePosition($nodePosition)
	{
		$this->_nodePosition = $nodePosition;
		return $this;
	}
	
	public function setNodeName($nodeName)
	{
		$this->_nodeName = $nodeName;
		return $this;
	}
	
	public function setNodeValue($nodeValue)
	{
		$this->_nodeValue = $nodeValue;
		return $this;
	}
	
	public function getNodeNameFile()
	{
		return strtolower(preg_replace("![^a-z0-9]+!i", "-", $this->getNodeName()));
	}
	
	public static function nodeType($nodeType) {
    
    	switch($nodeType)
		{
			case Application_Model_Node::NODE_TYPE_HTML:
				return "HTML Node";
				break;
			case Application_Model_Node::NODE_TYPE_LINK:
				return "Linknode";
				break;
		}
    }
	
}

