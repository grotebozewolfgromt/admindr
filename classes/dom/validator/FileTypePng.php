<?php
namespace dr\classes\dom\validator;



/**
 * file type validator for png images
 */
class FileTypePng extends FileType
{
	public function __construct($sErrorMessage)
	{
		parent::__construct($sErrorMessage, 'image/png');
	}

}
?>