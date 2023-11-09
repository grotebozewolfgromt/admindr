<?php
namespace dr\classes\dom\validator;

use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * als waarde niet leeg mag zijn
 */
class Notempty extends ValidatorAbstract
{
	public function isValid(FormInputAbstract $objUploadedFile)
	{
		$sFormInputValue = $objUploadedFile->getContentsSubmitted()->getValue();
		return (strlen($sFormInputValue) > 0);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}

?>