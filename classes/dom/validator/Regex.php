<?php
namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * form validator for a regular expression
 */
class Regex extends ValidatorAbstract
{
	private $sRegEx = '';

	public function __construct($sErrorMessage, $sRegEx)
	{
		$this->setPattern($sRegEx);
		parent::__construct($sErrorMessage);
	}

	public function setPattern($sRegEx)
	{
		$this->sRegEx = $sRegEx;
	}

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		parent::isValidRegex($this->sRegEx, $objUploadedFile);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}

?>