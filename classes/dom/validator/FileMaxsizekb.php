<?php
namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\InputAbstract;

/**
 * file size validator in kilobytes
 */
class FileMaxsizekb extends ValidatorAbstract
{
	private $iSizeKB = 0;

	public function __construct($sErrorMessage, $iSizeKB)
	{
		$this->iSizeKB = $iSizeKB;
		parent::__construct($sErrorMessage);
	}

	public function isValid(InputAbstract $objUploadedFile)
	{
		return ($objUploadedFile->getContentsSubmitted()->getFileSizeKB() <=  $this->iSizeKB);
	}

	public function filterValue(InputAbstract $objUploadedFile)
	{
		//
	}
}

?>