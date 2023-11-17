<?php
namespace dr\classes\dom\tag\form;

/**
 * parent class for input types: text, password, checkbox en radio
 * <input type="x">
 * the contents if X in the example above depends on child class
 * 
 * 23 jun 2021: InputAbstract: setValueSubmitted() added
 */
abstract class InputAbstract extends FormInputAbstract
{
	private $sType = '';
	private $sOnchange = '';
	private $iSize = '';
	private $iMaxLength = '';

	public function __construct($bIsArray = false)
	{
		parent::__construct($bIsArray);
		$this->setTagName('input');
		$this->setHasClosingTag(false);

		$this->setSourceFormattingIdentForCloseTag(false);
		$this->setSourceFormattingNewLineAfterOpenTag(false);
		$this->setSourceFormattingNewLineAfterCloseTag(false);
		$this->setSourceFormattingIdentForOpenTag(false);
	}

	/**
	 * setting input type
	 * @param string $sType
	 */
	public function setType($sType)
	{
		$this->sType = $sType;
	}

	/**
	 * get input type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->sType;
	}

	/**
	 * setting input value
	 * @param string $sValue
	 */
	public function setValue($sValue)
	{
		$this->getContentsInit()->setValue($sValue);
	}

	/**
	 * same as setValue(), but it takes the input of the submitted $_GET or $_POST
	 * 
	 * @param $sFormMethod read the $_POST or $_GET array? use constant Form::METHOD_POST
	 * @return void
	 */
	public function setValueSubmitted($sFormMethod = Form::METHOD_POST)
	{
		$this->setValue($this->getContentsSubmitted($sFormMethod)->getValue());
	}


	/**
	 * get input value
	 *
	 * @return string
	 */
	public function getValue()
	{
		return $this->getContentsInit()->getValue();
	}
	
	/**
	 * setting size of box in chars (maxlength is something else)
	 * @param int $iValue
	 */
	public function setSize($iValue)
	{
		$this->iSize = $iValue;
	}
	
	/**
	 * get size of box in chars (maxlength is something else)
	 *
	 * @return int
	 */
	public function getSize()
	{
		return $this->iSize;
	}	
	
	/**
	 * setting size (length of input)
	 * @param int $iValue
	 */
	public function setMaxLength($iValue)
	{
		$this->iMaxLength = $iValue;
	}
	
	/**
	 * get maxlength
	 *
	 * @return int
	 */
	public function getMaxLength()
	{
		return $this->iMaxLength;
	}

	public function setOnchange($sOnChangeEvent)
	{
		$this->sOnchange = $sOnChangeEvent;
	}
	
	public function getOnchange()
	{
		return $this->sOnchange;
	}
	

// 	public function getXMLNodeSpecificToNode_OLD(DOMElement $objXMLElement)
// 	{
// 		//type toevoegen
// 		if (strlen($this->getType()) > 0 )
// 			$objXMLElement->setAttribute('type', $this->getType());

// 		//value toevoegen
// 		if (strlen($this->getValue()) > 0 )
// 			$objXMLElement->setAttribute('value', $this->getValue());

		
// 		$this->getXMLNodeSpecificToInputType_OLD($objXMLElement);
// 	}

	public function renderHTMLNodeSpecific()
	{
		$sAttributes = parent::renderHTMLNodeSpecific();

		$sAttributes .= $this->addAttributeToHTML('type', $this->getType());
		$sAttributes .= $this->addAttributeToHTML('value', $this->getValue());
		$sAttributes .= $this->addAttributeToHTML('size', $this->getSize());
		$sAttributes .= $this->addAttributeToHTML('maxlength', $this->getMaxLength());

		$sAttributes .= $this->renderHTMLNodeSpecificToInputType();

		return $sAttributes;
	}

	abstract public function renderHTMLNodeSpecificToInputType();

	//abstract public function getXMLNodeSpecificToInputType_OLD(DOMElement $objXMLElement);

}

?>