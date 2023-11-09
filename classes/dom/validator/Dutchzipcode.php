<?php
namespace dr\classes\dom\validator;



use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * postcode
 */
class Dutchzipcode extends ValidatorAbstract
{
	public function isValid(FormInputAbstract $objUploadedFile)
	{
		return parent::isValidRegex("/^[0-9]{4}\s?[A-Z]{2}$/", $objUploadedFile);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>