<?php
namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * alfabetische karakters
 */
class Onlyalphabetical extends ValidatorAbstract
{
	public function isValid(FormInputAbstract $objUploadedFile)
	{
		return parent::isValidRegex("/^[aA-zZ]+$/", $objUploadedFile);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}

?>