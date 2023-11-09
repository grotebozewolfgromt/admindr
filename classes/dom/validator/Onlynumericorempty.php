<?php
namespace dr\classes\dom\validator;

use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * alleen numerieke karaters (0-9)
 */
class Onlynumericorempty extends ValidatorAbstract
{
	public function isValid(FormInputAbstract $objUploadedFile)
	{
		if ($objUploadedFile->getContentsSubmitted()->getValue() != '')
			return parent::isValidRegex("/^[0-9]+$/", $objUploadedFile);
		else
			return true;
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>