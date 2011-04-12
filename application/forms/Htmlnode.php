<?php

class Application_Form_Htmlnode extends Zend_Form
{
	protected $_tree;
	protected $_node;
	
	public function __construct($tree, $node)
	{
		$this->_tree = $tree;
		$this->_node = $node;
		parent::__construct();
	}

    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        
        $this->addElement('select', 'parentId', array(
            'label'      => 'Parent:',
            'required'   => true,
        	'multioptions'   => $this->_tree->getForm($this->_node->getId()),
        	'value'	=> array($this->_node->getParentId())
        ));
        
        $this->addElement('text', 'nodeName', array(
            'label'      => 'Titel ',
            'required'   => true,
        	'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 30))
                ),
            'value'	=> ''.$this->_node->getNodeName()
        ));
 
        /*
        $this->addElement('text', 'nodeValue', array(
            'label'      => 'Interner Name (opt) ',
            'required'   => false,
        	'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 30))
                ),
            'value'	=> ''.$this->_node->getNodeValue()
        ));
        */
        
        $this->addElement('textarea', 'content', array(
            'label'      => 'Inhalt ',
            'required'   => true,
            'value'	=> ''.$this->_node->content
        ));
        
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));

    }


}

