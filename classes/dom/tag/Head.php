<?php
namespace dr\classes\dom\tag;

/**
 * <head></head>
 */
class Head extends TagAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setTagName('head');

		//according to the w3c standards: every head tag needs to have a title tag,
		//so we create that automatically for you:
		$objTitle = new title();
		$this->addNode($objTitle);
	}

	/**
	 * verkrijg title object
	 *
	 * @return title
	 */
	public function getTitle()
	{
		return $this->getNode(0);
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 	}

	public function renderHTMLNodeSpecific()
	{

	}

}

?>