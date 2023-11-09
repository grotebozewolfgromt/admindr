<?php
namespace dr\classes\dom\tag\form;

/**
 * <input type="hidden">
 *
 *     $objHidden = new InputHidden();
 $objHidden->setName('edtHidden');
 $objHidden->setValue(100);
 $objForm->addNode($objHidden);
 */
class InputHidden extends InputAbstract
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('hidden');
	}

	public function getXMLNodeSpecificToInputType_OLD(DOMElement $objXMLElement)
	{
	}

	public function renderHTMLNodeSpecificToInputType()
	{

	}
}

?>