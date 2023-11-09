<?php
namespace dr\classes\dom\validator;

use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * alleen bepaalde karakaters zijn toegestaan
 */
class Characterwhitelist extends ValidatorAbstract
{
	private $sCharsAllowed;

	public function __construct($sErrorMessage, $sCharWhitelist)
	{
		$this->setCharactersAllowed($sCharWhitelist);
		parent::__construct($sErrorMessage);
	}

	public function setCharactersAllowed($sCharsAllowed)
	{
		$this->sCharsAllowed = $sCharsAllowed;
	}


	public function isValid(FormInputAbstract $objInput)
	{
		$sFormInputValue = '';
		$sFormInputValue = $objInput->getContentsSubmitted()->getValue();

		return (filterBadCharsWhiteList($sFormInputValue, $this->sCharsAllowed) == $sFormInputValue);
	}

	public function filterValue(FormInputAbstract $objInput)
	{
		$objInput->getContentsSubmitted()->setValue(filterBadCharsWhiteList($objInput->getContentsSubmitted()->getValue(), $this->sCharsAllowed));
	}
}
?>