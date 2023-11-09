<?php
namespace dr\classes\dom\tag;

class Div extends TagAbstract
{


        
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('div');
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement) {
// 	}

	public function renderHTMLNodeSpecific()
	{
	}
}
?>