<?php
namespace dr\classes\dom\validator;



use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * alleen bepaalde karakaters zijn toegestaan
 */
class Characterblacklist extends ValidatorAbstract
{
	private $sCharsNotAllowed;

	public function __construct($sErrorMessage, $sCharsNotAllowed)
	{
		$this->setCharactersNotAllowed($sCharsNotAllowed);
		parent::__construct($sErrorMessage);
	}

	public function setCharactersNotAllowed($sCharsNotAllowed)
	{
		$this->sCharsNotAllowed = $sCharsNotAllowed;
	}

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		$sFormInputValue = $objUploadedFile->getContentsSubmitted()->getValue();
		for ($iTeller = 0; $iTeller < strlen($this->sCharsNotAllowed); $iTeller++)
		{
		$cChar = $this->sCharsNotAllowed[$iTeller];

		if (strpos($sFormInputValue, $cChar))
			return false;
		}
		}

		public function filterValue(FormInputAbstract $objUploadedFile)
		{
		$objUploadedFile->getContentsSubmitted()->setValue(filterBadCharsBlackList($objUploadedFile->getContentsSubmitted()->getValue(), $this->sCharsNotAllowed));
		}
		}
?>