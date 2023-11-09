<?php
namespace dr\classes\dom\tag;

/**
 * <ol></ol>
 */
class Ol extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('ol');
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}

	public function renderHTMLNodeSpecific()
	{

	}
	
	public function addListItem($sText)
	{
		$objLI = new THTMLCodeGen_li();
		$objLI->setText($sText);
		$this->addNode($objLI);
	}
}
?>