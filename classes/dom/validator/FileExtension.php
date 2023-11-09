<?php
namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * file extension validator
 */
class FileExtension extends ValidatorAbstract
{
	private $sFileExtension = 0;

	public function __construct($sErrorMessage, $sFileExtension)
	{
		$this->sFileExtension = $sFileExtension;
		parent::__construct($sErrorMessage);
	}

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		return (strtoupper($objUploadedFile->getContentsSubmitted()->getFileExtension()) ==  strtoupper($this->sFileExtension));
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}

?>