<?php
namespace dr\classes\dom\validator;


/**
 * file type validator for excel files
 */
class FileTypeExcel extends FileType
{
	public function __construct($sErrorMessage)
	{
		parent::__construct($sErrorMessage, 'application/vnd.ms-excel');
	}

}
?>