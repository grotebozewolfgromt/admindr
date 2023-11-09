<?php
namespace dr\classes\dom\tag;

/**
 * <title></title>
 */
class Title extends TagAbstract
{
	private $sTitle;

	public function __construct()
	{
		parent::__construct();
		$this->setTagName('title');
	}

	/**
	 * setting the text in the title tag
	 *
	 * @param string $sTitle
	 */
	public function setTitle($sTitle)
	{
		$this->sTitle = $sTitle;
	}

	/**
	 * getting the title tag
	 *
	 * @return string
	 */
	public function getTitle()
	{
		return $this->sTitle;
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		$this->addText($this->getTitle());
// 	}

	public function renderHTMLNodeSpecific()
	{
		$this->addText($this->getTitle());
	}

}

?>