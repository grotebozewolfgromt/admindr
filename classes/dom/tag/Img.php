<?php
namespace dr\classes\dom\tag;


class Img extends TagAbstract
{
	private $sSrc = '';
	private $sAlt = '';

	public function __construct()
	{
		parent::__construct();
		$this->setTagName('img');
		$this->setHasClosingTag(false);

		$this->setSourceFormattingIdentForCloseTag(false);
		$this->setSourceFormattingNewLineAfterOpenTag(false);
		$this->setSourceFormattingNewLineAfterCloseTag(false);
		$this->setSourceFormattingIdentForOpenTag(false);
	}

	public function setSrc($sText)
	{
		$this->sSrc = $sText;
	}

	public function getSrc()
	{
		return $this->sSrc;
	}

	public function setAlt($sText)
	{
		$this->sAlt = $sText;
	}

	public function getAlt()
	{
		return $this->sAlt;
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		//src toevoegen
// 		//if (strlen($this->getSrc()) > 0 ) src is verplicht
// 		$objXMLElement->setAttribute('src', $this->getSrc());

// 		//alt toevoegen
// 		//if (strlen($this->getAlt()) > 0 ) alt is verplicht
// 		$objXMLElement->setAttribute('alt', $this->getAlt());

// 	}

	public function renderHTMLNodeSpecific()
	{
		$sAttributes = '';

		$sAttributes .= $this->addAttributeToHTML('src', $this->getSrc(), true); //verplichte attributen
		$sAttributes .= $this->addAttributeToHTML('alt', $this->getAlt(), true); //verplicht

		return $sAttributes;

	}
}


?>