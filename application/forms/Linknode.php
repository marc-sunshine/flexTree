<?php

class Application_Form_Linknode extends Zend_Form
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
 
        $this->addElement('text', 'nodeValue', array(
            'label'      => 'Link Url ',
            'required'   => true,
        	'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 30))
                ),
            'value'	=> ''.$this->_node->getNodeValue()
        ));
        
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));

    }


}

