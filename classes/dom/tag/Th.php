<?php
namespace dr\classes\dom\tag;


/**
 * <td></td>
 */
class Th extends TagAbstract
{
	private $iColspan = 1;

	public function __construct()
	{
		parent::__construct();
		$this->setTagName('td');

		$this->setSourceFormattingIdentForCloseTag(false);
		$this->setSourceFormattingNewLineAfterOpenTag(false);
	}

	/**
	 * get the colspan of the TD
	 * @return int
	 */
	public function getColSpan()
	{
		return $this->iColspan;
	}

	/**
	 * set the colspan of the td
	 *
	 * @param int $iSpan
	 */
	public function setColSpan($iSpan)
	{
		if (is_numeric($iSpan))
		{
			$this->iColspan = $iSpan;
		}
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		//colspan toevoegen
// 		if ($this->getColSpan() > 1)
// 		{
// 			$objXMLElement->setAttribute('colspan', $this->getColSpan());
// 		}
// 	}

	public function renderHTMLNodeSpecific()
	{
		$sAttributes = '';

		//colspan toevoegen
		if ($this->getColSpan() > 1)
		{
			$sAttributes .= $this->addAttributeToHTML('colspan', $this->getColSpan());
		}

		return $sAttributes;
	}
}

/**
 * table header
 * <th></th>
 */
class th extends TagAbstract
{
	private $iColspan = 1;

	public function __construct()
	{
		parent::__construct();
		$this->setTagName('th');
	}

	/**
	 * get the colspan of the TD
	 * @return int
	 */
	public function getColSpan()
	{
		return $this->iColspan;
	}

	/**
	 * set the colspan of the td
	 *
	 * @param int $iSpan
	 */
	public function setColSpan($iSpan)
	{
		if (is_numeric($iSpan))
		{
			$this->iColspan = $iSpan;
		}
	}

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		//colspan toevoegen
// 		if ($this->getColSpan() > 1)
// 		{
// 			$objXMLElement->setAttribute('colspan', $this->getColSpan());
// 		}
// 	}

	public function renderHTMLNodeSpecific()
	{
		$sAttributes = '';

		//colspan toevoegen
		if ($this->getColSpan() > 1)
		{
			$sAttributes .= $this->addAttributeToHTML('colspan', $this->getColSpan());
		}

		return $sAttributes;
	}
}

?>