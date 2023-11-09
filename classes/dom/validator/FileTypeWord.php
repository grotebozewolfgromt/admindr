<?php
namespace dr\classes\dom\validator;


/**
 * file type validator for ms word
 */
class FileTypeWord extends FileType
{
	public function __construct($sErrorMessage)
	{
		parent::__construct($sErrorMessage, 'application/msword');
	}

}
?>