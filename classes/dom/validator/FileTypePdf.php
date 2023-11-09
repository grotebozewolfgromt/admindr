<?php
namespace dr\classes\dom\validator;


/**
 * file type validator for pdf
 */
class FileTypePdf extends FileType
{
	public function __construct($sErrorMessage)
	{
		parent::__construct($sErrorMessage, 'application/pdf');
	}

}
?>