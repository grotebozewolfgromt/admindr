<?php
namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * this checkbox needs to be checked
 */
class CheckboxChecked extends ValidatorAbstract
{
	private $sCheckboxValueChecked = ''; //the vaue of the checkbox when checked

	public function __construct($sErrorMessage, $sCheckboxValue)
	{
		$this->setCheckboxValue($sCheckboxValue);
		parent::__construct($sErrorMessage);
	}

	public function setCheckboxValue($sCheckboxValue)
	{
		$this->sCheckboxValueChecked = $sCheckboxValue;
	}

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		$sCheckboxValue = $objUploadedFile->getContentsSubmitted()->getValue();
		return ($sCheckboxValue == $this->sCheckboxValueChecked);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>