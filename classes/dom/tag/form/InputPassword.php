<?php
namespace dr\classes\dom\tag\form;


/**
 * <input type="password">
 *
 $objHidden = new InputPassword();
 $objHidden->setName('edtPassword');
 $objHidden->setValue(100);
 $objForm->addNode($objHidden);
 * *
 */
class InputPassword extends InputText
{
	public function __construct()
	{
		parent::__construct();
		$this->setType('password');
		$this->setShowValuesOnReloadForm(false);//uit veiligheidsoverwegingen het weergeven van passwords in plain html is uitgeschakeld bij het herladen van een formulier (bijvoporbeeld bij foutieve inlog gegevens).
	}

	public function getXMLNodeSpecificToInputType_OLD(DOMElement $objXMLElement)
	{
	}

	public function renderHTMLNodeSpecificToInputType()
	{
		return parent::renderHTMLNodeSpecificToInputType();
	}
}

?>
