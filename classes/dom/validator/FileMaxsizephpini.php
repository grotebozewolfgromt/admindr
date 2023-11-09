<?php
namespace dr\classes\dom\validator;

use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * file size validator based on the php max file upload set in ini file
 */
class FileMaxsizephpini extends ValidatorAbstract
{

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		$iMaxFileSize = getMaxFileUploadSize();
		return ($objUploadedFile->getContentsSubmitted()->getFileSize() < $iMaxFileSize);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>