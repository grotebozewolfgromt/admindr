<?php
namespace dr\classes\dom\tag;


/**
 * klasse voor speciale html karakters, zoals &nbsp; etc. Deze worden door de standaard xml klassen automatisch ge-escaped
 *
 * LET OP: De klasse is anders dan andere!
 *
 *
 */
class EntityReference extends TagAbstract
{
	private $sText = '';

	/**
	 * setting the text for the textnode
	 *
	 * @param string $sText
	 */
	public function setEntity($sText)
	{
		$this->sText = $sText;
	}

	/**
	 * getting the text for the text node
	 *
	 * @return string
	 */
	public function getEntity()
	{
		return $this->sText;
	}

	public function getXMLNode_OLD(DOMDocument $objXMLDoc)
	{
		return $objXMLDoc->createEntityReference($this->getEntity());
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		//just implementing, because it's abstract parent class;
// 		return null;
// 	}

	public function renderHTMLNodeSpecific()
	{

	}
}


?>