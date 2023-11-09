<?php
namespace dr\classes\dom\tag;

/**
 * <body></body>
 */
class Body extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('body');
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}

	public function renderHTMLNodeSpecific()
	{

	}

}
?>