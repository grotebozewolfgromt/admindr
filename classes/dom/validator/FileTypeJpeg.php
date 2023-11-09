<?php
namespace dr\classes\dom\validator;

/**
 * file type validator for jpeg images
 */
class FileTypeJpeg extends FileType
{
	public function __construct($sErrorMessage)
	{
		parent::__construct($sErrorMessage, 'image/jpeg');
	}

}
?>