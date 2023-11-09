<?php
namespace dr\classes\dom\validator;

use dr\classes\dom\tag\form\InputAbstract;
use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * file size validator in megabytes
 */
class FileMaxsizemb extends ValidatorAbstract
{
	private $iSizeMB = 0;

	public function __construct($sErrorMessage, $iSizeMB)
	{
		$this->iSizeMB = $iSizeMB;
		parent::__construct($sErrorMessage);
	}

	public function isValid(FormInputAbstract $objUploadedFile)
	{
		return ($objUploadedFile->getContentsSubmitted()->getFileSizeMB() <=  $this->iSizeMB);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}
}
?>