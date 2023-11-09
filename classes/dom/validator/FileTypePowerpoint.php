<?php
namespace dr\classes\dom\validator;

/**
 * file type validator for powerpoint files
 */
class FileTypePowerpoint extends FileType
{
	public function __construct($sErrorMessage)
	{
		parent::__construct($sErrorMessage, 'application/vnd.ms-powerpoint');
	}

}
?>