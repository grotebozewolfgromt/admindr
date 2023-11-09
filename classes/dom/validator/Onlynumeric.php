<?php

namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * alleen numerieke karaters (0-9)
 */
class Onlynumeric extends ValidatorAbstract
{
	public function isValid(FormInputAbstract $objUploadedFile)
	{
		return parent::isValidRegex("/^[0-9]+$/", $objUploadedFile);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}


?>