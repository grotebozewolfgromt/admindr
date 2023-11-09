<?php
namespace dr\classes\dom\tag\form;

/**
 * <input type="submit">
 *
 *     $objSubmit = new InputSubmit();
 $objSubmit->setName('btnSubmit');
 $objSubmit->setValue('verstuur de hele rommel');
 $objForm->addNode($objSubmit);
 */
class InputSubmit extends InputAbstract
{

        
	public function __construct()
	{
		parent::__construct();
		$this->setType('submit');
	}
        
        
	public function getXMLNodeSpecificToInputType_OLD(DOMElement $objXMLElement)
	{
	}

	public function renderHTMLNodeSpecificToInputType()
	{
//		$sAttributes = '';
//
//
//		return $sAttributes;
	}

}
?>