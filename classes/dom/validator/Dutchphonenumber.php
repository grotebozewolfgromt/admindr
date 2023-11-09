<?php
namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * nederlands telefoonnummer
 */
class Dutchphonenumber extends ValidatorAbstract
{
	public function isValid(FormInputAbstract $objUploadedFile)
	{
		return parent::isValidRegex("/^(\d{3}-?\d{7}|\d{4}-?\d{6})$/", $objUploadedFile);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>