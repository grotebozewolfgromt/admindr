<?php
namespace dr\classes\dom\tag\form;

use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\TagAbstract;

/**
 * <label></label>:
 *
 *   <label for="male">Male</label>
 <input type="radio" name="sex" id="male" />
 <br />
 <label for="female">Female</label>
 <input type="radio" name="sex" id="female" />
 */
class Label extends TagAbstract
{
	private $sFor = '';

	public function __construct()
	{
		parent::__construct();
		$this->setTagName('label');

		$this->setSourceFormattingIdentForCloseTag(false);
		$this->setSourceFormattingNewLineAfterOpenTag(false);
		$this->setSourceFormattingNewLineAfterCloseTag(false);
		$this->setSourceFormattingIdentForOpenTag(false);

		//according to the w3c standards: every option needs text
		//so we create that automatically for you:
		$objText = new Text();
		$this->addNode($objText);
	}

	/**
	 * setting input value
	 * @param string $sType
	 */
	public function setFor($sFor)
	{
		$this->sFor = $sFor;
	}

	/**
	 * get input value
	 *
	 * @return string
	 */
	public function getFor()
	{
		return $this->sFor;
	}

	/**
	 * getting the text of a text area
	 * @return string
	 */
	public function getText()
	{
		return $this->getNode(0)->getText();
	}

	/**
	 * setting the text of a text area
	 * @param string $sText
	 */
	public function setText($sText)
	{
		$this->getNode(0)->setText($sText);
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		//for toevoegen
// 		if (strlen($this->getFor()) > 0 )
// 			$objXMLElement->setAttribute('for', $this->getFor());
// 	}

	public function renderHTMLNodeSpecific()
	{
		$sAttributes = '';

		$sAttributes .= $this->addAttributeToHTML('for', $this->getFor());

		return $sAttributes;
	}
}

?>