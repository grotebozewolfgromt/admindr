<?php
namespace dr\classes\dom\validator;




use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * if 2 fields have to be the same value
 * (for example password or email fields)
 * 
 *  1 apr 2016: characterwhitelist created
 */
class Repeatfieldvalue extends ValidatorAbstract
{
	private $objOtherField = null;

	public function __construct($sErrorMessage, FormInputAbstract &$objOtherField)
	{
		$this->setOtherField($objOtherField);
		parent::__construct($sErrorMessage);
	}

	public function setOtherField(FormInputAbstract &$objOtherField)
	{
		$this->objOtherField = $objOtherField;
	}

	public function isValid(FormInputAbstract $objField)
	{
// vardump($objField->getContentsSubmitted()->getValue(), 'frikantel')		;
// vardumpdie($objField->objOtherField()->getValue(), 'frikantel2')		;
		if (!$this->objOtherField)
			return false;

		return ($objField->getContentsSubmitted()->getValue() == $this->objOtherField->getContentsSubmitted()->getValue());
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>