<?php
namespace dr\classes\dom\tag;


/**
 * <a></a>
 *
 *     $objA = new a();
 $objA->setHref('http://www.av.com');
 $objA->setTarget('_blank');
 $objA->setText('ga naar google');
 */
class A extends TagAbstract
{
	private $sHref = '';
	private $sTarget = '';
	private $sTitle = '';

	public function __construct()
	{
		parent::__construct();
		$this->setTagName('a');

		//you probably wanna have some text in the a tag
		//so we create that automatically for you:
		$objText = new Text();
		$this->addNode($objText);
	}

	public function setText($sText)
	{
		$this->getNode(0)->setText($sText);
	}

	public function getText()
	{
		return $this->getNode(0)->getText();
	}

	public function setHref($sHref)
	{
		$this->sHref = $sHref;
	}

	public function getHref()
	{
		return $this->sHref;
	}

	public function setTarget($sTarget)
	{
		$this->sTarget = $sTarget;
	}

	public function getTarget()
	{
		return $this->sTarget;
	}

	public function setTitle($sTitle)
	{
		$this->sTitle = $sTitle;
	}

	public function getTitle()
	{
		return $this->sTitle;
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		//target toevoegen
// 		if (strlen($this->getTarget()) > 0 )
// 			$objXMLElement->setAttribute('target', $this->getTarget());

// 		//href toevoegen
// 		if (strlen($this->getHref()) > 0 )
// 			$objXMLElement->setAttribute('href', $this->getHref());

// 		//title toevoegen
// 		if (strlen($this->getTitle()) > 0 )
// 			$objXMLElement->setAttribute('title', $this->getTitle());

// 	}

	public function renderHTMLNodeSpecific()
	{
		$sAttributes = '';

		$sAttributes .= $this->addAttributeToHTML('target', $this->getTarget());
		$sAttributes .= $this->addAttributeToHTML('href', $this->getHref());
		$sAttributes .= $this->addAttributeToHTML('title', $this->getTitle());

		return $sAttributes;
	}
}

?>
