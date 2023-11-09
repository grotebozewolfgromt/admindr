<?php
namespace dr\classes\dom\tag;

/**
 * <tr></tr>
 */
class Tr extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('tr');
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}

	public function renderHTMLNodeSpecific()
	{

	}
}

?>