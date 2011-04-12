<?php

class Application_Form_Node extends Zend_Form
{
	protected $_tree;
	
	public function __construct($tree)
	{
		$this->_tree = $tree;
		parent::__construct();
	}

    public function init()
    {
        // Set the method for the display form to POST
        $this->setMethod('post');
        
        $this->addElement('select', 'nodeType', array(
            'label'      => 'Typ:',
            'required'   => true,
        	'multioptions'   => array(
                            Application_Model_Node::NODE_TYPE_HTML => 'HTML Node',
                            Application_Model_Node::NODE_TYPE_LINK => 'LINK Node',
                            )
        ));
        
        $this->addElement('select', 'parentId', array(
            'label'      => 'Parent:',
            'required'   => true,
        	'multioptions'   => $this->_tree->getForm(0)
        ));

 
        // Add an email element
        $this->addElement('text', 'nodeName', array(
            'label'      => 'Titel ',
            'required'   => true,
        	'validators' => array(
                array('validator' => 'StringLength', 'options' => array(0, 30))
                )
        ));
        
 
        // Add the submit button
        $this->addElement('submit', 'submit', array(
            'ignore'   => true,
            'label'    => 'Submit',
        ));

    }


}

