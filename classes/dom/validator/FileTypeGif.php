<?php
namespace dr\classes\dom\validator;

/**
 * file type validator for gif image
 */
class FileTypeGif extends FileType
{
	public function __construct($sErrorMessage)
	{
		parent::__construct($sErrorMessage, 'image/gif');
	}

}
?>