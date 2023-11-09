<?php
namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * waarde mag maximale lengte hebben
 */
class Maximumlength extends ValidatorAbstract
{
	private $iMaxLength = 0;

	public function __construct($sErrorMessage, $iMaxLength)
	{
		$this->setMaximumLength($iMaxLength);
		parent::__construct($sErrorMessage);
	}

	public function setMaximumLength($iMaxLength = 0)
	{
		$this->iMaxLength = $iMaxLength;
	}

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		$sFormInputValue = $objUploadedFile->getContentsSubmitted()->getValue();
		return (strlen($sFormInputValue) <= $this->iMaxLength);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>