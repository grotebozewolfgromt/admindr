<?php
namespace dr\classes\dom\tag;


/**

 */
class Br extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('br');

	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}
	
	public function renderHTMLNodeSpecific()
	{
		$sAttributes = '';
	
	
		return $sAttributes;
	}	
}

?>
