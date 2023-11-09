<?php
namespace dr\classes\dom\tag;

use dr\classes\dom\tag\Text;

class U extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('u');

		//you probably wanna have some text in the u tag
		//so we create that automatically for you:
		$objText = new Text();
		$this->addNode($objText);
	}

	public function setText($sText)
	{
		$this->getNode(0)->setText($sText);
	}

	public function getText()
	{
		return $this->getNode(0)->getText();
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}

	public function renderHTMLNodeSpecific()
	{

	}
}

?>