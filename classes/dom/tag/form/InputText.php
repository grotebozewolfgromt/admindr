<?php
namespace dr\classes\dom\tag\form;


/**
 * <input type="text">
 *
 *     $objHidden = new InputText();
 $objHidden->setName('edtText');
 $objHidden->setValue(100);
 $objForm->addNode($objHidden);
 */
class InputText extends InputAbstract
{
	private $sOnChange = '';
	private $sPlaceHolder = ''; //new in html5

	public function __construct($bIsArray = false)
	{
		parent::__construct($bIsArray);
		$this->setType('text');
	}

	public function setOnchange($sOnChangeEvent)
	{
		$this->sOnChange = $sOnChangeEvent;
	}

	public function getOnchange()
	{
		return $this->sOnChange;
	}

	public function setPlaceholder($sPlaceholder)
	{
		$this->sPlaceHolder = $sPlaceholder;
	}

	public function getPlaceholder()
	{
		return $this->sPlaceHolder;
	}


	public function getXMLNodeSpecificToInputType_OLD(DOMElement $objXMLElement)
	{
	}

	public function renderHTMLNodeSpecificToInputType()
	{	
		$sAttributes = '';
		 
		$sAttributes .= $this->addAttributeToHTML('onchange', $this->getOnchange());
		$sAttributes .= $this->addAttributeToHTML('placeholder', $this->getPlaceholder());

		return $sAttributes;
	}

}

?>