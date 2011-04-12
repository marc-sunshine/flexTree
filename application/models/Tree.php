<?php

class Application_Model_Tree
{
	protected $_tree;
	protected $_treeParts;
	protected $_jsontree;
	protected $_formtree = array(0 => 'None');
	protected $_elementCount;
	private $_columns = array('nodeName', 'control');
	
	public function getTreePart($parentId = 0)
	{
		if($parentId == 0)
			return $this->_tree;
		else {
			return $this->findTreePart($this->_tree, $parentId);
		}
	}
	
	public function getTreeArray()
	{
		$this->getTreePart(0);
	}
	
	public function findTreePart($curPart, $s_id)
	{
		$part = $curPart;
		foreach($part as $id => $node)
		{
			if(intval($id) == $s_id)
			{
				if(isset($node["childs"]))
					return $node["childs"];
			}
			else
				if(isset($node['childs']) && is_array($node['childs']))
				{
					$temp = $this->findTreePart($node['childs'], $s_id);
					if(!empty($temp))
						return $temp;
				}
		}
	}
	
	public function setTreeArray($tree)
	{
		$this->_tree = $tree;
	}
	
	public function setElementCount($elementCount)
	{
		$this->_elementCount = $elementCount;
	}
	
	public function getJson()
	{
		//return $this->generateJsonTree();
		return json_encode($this->generateJsTree());
	}
	
	public function getForm($notId)
	{
		$this->generateFormTree($notId);
		return $this->_formtree;
	}
	
	public function generateFormTree($notId, $level = 0, $parentarray = null)
	{
		$level++;
		if(is_null($parentarray))
		foreach($this->_tree as $node)
		{
			if($node['node']->getId() != $notId)
			{
			$this->_formtree[$node['node']->getId()] = $node['node']->getNodeName();
			if(isset($node['childs']) && is_array($node['childs']))
				$this->generateFormTree($notId, $level, $node['childs']);
			}
		}
		else
		foreach($parentarray as $node)
		{
			if($node['node']->getId() != $notId)
			{
			$pre = '';
			for($i = 0; $i < $level - 1; $i++)
				$pre .= '--';
			$this->_formtree[$node['node']->getId()] = $pre.' '.$node['node']->getNodeName();
			if(isset($node['childs']) && is_array($node['childs']))
				$this->generateFormTree($notId, $level, $node['childs']);
			}
		}
	}
	
	/*
	 * Json Tree for JsTree
	 */
	public function generateJsTree($parent = 0)
	{
		$items = array();
		
		if($parent == 0)
			$parent = $this->_tree;
		if(is_array($parent))
		foreach($parent as $node)
		{
			$children = $this->generateJsTree($node["childs"]);
			
			//$control1 = '<a href="'.Zend_Controller_Front::getInstance()->getBaseUrl().'/admin/treenode/node/'.$node["node"]->getId().'">edit</a>';
			//$control2 = '<a href="'.Zend_Controller_Front::getInstance()->getBaseUrl().'/admin/treenode/node/'.$node["node"]->getId().'/delete/1" onclick="javascript:return confirm(\'Are you sure you want to delete this node and all it\'s childs?\')">delete</a>';
			$control1 = $node["node"]->getId();
			
			if(is_array($children) && !empty($children))
				array_push($items, array("data" => array("title" => $node["node"]->getNodeName()), "attr" => array("id" => $node["node"]->getId(), "type" => Application_Model_Node::nodeType($node["node"]->getNodeType()), "control" => $control1), "state" => "open", "children" => $children));
			else
				array_push($items, array("data" => $node["node"]->getNodeName(), "attr" => array("id" => $node["node"]->getId(), "type" => Application_Model_Node::nodeType($node["node"]->getNodeType()), "control" => $control1)));
		}
		return $items;
	}
	
	/*
	 * Json Tree for Nested Sortable
	 */
	public function generateJsonTree()
	{
		$this->_jsontree = array();
		$this->_jsontree["requestFirstIndex"] = 0;
		$this->_jsontree["firstIndex"] = 0;
		$this->_jsontree["count"] = $this->_elementCount;
		$this->_jsontree["totalCount"] = $this->_elementCount;
		$this->_jsontree["columns"] = $this->_columns;
		$this->_jsontree["items"] = $this->generateJsonTreeGetChildren();
		return json_encode($this->_jsontree);
	}
	
	public function generateJsonTreeGetChildren($parent = 0)
	{
		$items = array();
		
		if($parent == 0)
			$parent = $this->_tree;
		foreach($parent as $node)
		{
			$control = '<a href="'.Zend_Controller_Front::getInstance()->getBaseUrl().'/admin/treenode/node/'.$node["node"]->getId().'">edit</a>';
			$control .= ' | ';
			$control .= '<a href="'.Zend_Controller_Front::getInstance()->getBaseUrl().'/admin/treenode/node/'.$node["node"]->getId().'/delete/1" onclick="javascript:return confirm(\'Are you sure you want to delete this node and all it\'s childs?\')">delete</a>';
			array_push($items, array("id" => $node["node"]->getId(), "info" => array($node["node"]->getNodeName()." - <i>".Application_Model_Node::nodeType($node["node"]->getNodeType()) ."</i>", $control), "children" => $this->generateJsonTreeGetChildren($node["childs"])));
		}
		return $items;
	}
}