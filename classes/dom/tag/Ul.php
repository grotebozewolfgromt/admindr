<?php
namespace dr\classes\dom\tag;

/**
 * <ul></ul>
 */
class Ul extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('ul');
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}

	public function renderHTMLNodeSpecific()
	{

	}
	
	public function addListItem($sText)
	{
		$objLI = new Li();
		$objLI->setText($sText);
		$this->addNode($objLI);
	}
}
?>