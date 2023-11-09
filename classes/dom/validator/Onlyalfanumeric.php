<?php
namespace dr\classes\dom\validator;




use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * alleen alfanumerieke karaters (0-9) en a-z
 */
class Onlyalfanumeric extends ValidatorAbstract
{
	public function isValid(FormInputAbstract $objUploadedFile)
	{
		return parent::isValidRegex("/^[0-9aA-zZ]+$/", $objUploadedFile);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>