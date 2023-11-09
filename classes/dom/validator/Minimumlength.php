<?php
namespace dr\classes\dom\validator;

use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * waarde moet minimale lengte hebben
 */
class MinimumLength extends ValidatorAbstract
{
	private $iMaxLength = 0;

	public function __construct($sErrorMessage, $iMinLength)
	{
		$this->setMinimumLength($iMinLength);
		parent::__construct($sErrorMessage);
	}

	public function setMinimumLength($iMinLength = 0)
	{
		$this->iMaxLength = $iMinLength;
	}

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		$sFormInputValue = $objUploadedFile->getContentsSubmitted()->getValue();
		return (strlen($sFormInputValue) >= $this->iMaxLength);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>