<?php
namespace dr\classes\dom\validator;



use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * file type validator for types like application/octet-stream
 */
class FileType extends ValidatorAbstract
{
	private $sFileType = 'application/octet-stream';

	public function __construct($sErrorMessage, $sFileType)
	{
		$this->sFileType = $sFileType;
		parent::__construct($sErrorMessage);
	}

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		return ($objUploadedFile->getContentsSubmitted()->getFileType() == $this->sFileType);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>