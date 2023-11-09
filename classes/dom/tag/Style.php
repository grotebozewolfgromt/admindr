<?php
namespace dr\classes\dom\tag;

use dr\classes\dom\tag\Text;

/**
 * <style></style>
 */
class Style extends TagAbstract
{
	private $sType = 'text/css';
	private $sMedia = '';

	public function __construct()
	{
		parent::__construct();
		$this->setTagName('style');

		//this tag is only usefull when adding stylesheet information,
		//so we create that automatically for you:
		$objStylesheet = new Text();
		$this->addNode($objStylesheet);
	}

	/**
	 * setting the type in the head->style tag
	 *
	 * @param string $sType
	 */
	public function setType($sType = 'text/css')
	{
		$this->sType = $sType;
	}

	/**
	 * getting the type in the head->style tag
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->sType;
	}

	/**
	 * setting the media in the head->style tag
	 *
	 * @param string $sMedia
	 */
	public function setMedia($sMedia)
	{
		$this->sMedia = $sMedia;
	}

	/**
	 * getting the media in the head->style tag
	 *
	 * @return string
	 */
	public function getMedia()
	{
		return $this->sMedia;
	}

	/**
	 * setting the stylesheet info
	 *
	 * @param string $sStylesheet
	 */
	public function setStylesheet($sStylesheet)
	{
		$this->getNode(0)->setText($sStylesheet);
	}

	/**
	 * getting the stylesheet info
	 *
	 * @return string
	 */
	public function getStylesheet()
	{
		return $this->getNode(0)->getText();
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		//type toevoegen
// 		if (strlen($this->getType()) > 0)
// 			$objXMLElement->setAttribute('type', $this->getType());

// 		//media toevoegen
// 		if (strlen($this->getMedia()) > 0)
// 			$objXMLElement->setAttribute('media', $this->getMedia());

// 	}

	public function renderHTMLNodeSpecific()
	{
		$sAttributes = '';

		$sAttributes .= $this->addAttributeToHTML('type', $this->getType());
		$sAttributes .= $this->addAttributeToHTML('media', $this->getMedia());

		return $sAttributes;
	}
}


?>
