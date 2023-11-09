<?php
namespace dr\classes\dom\validator;


/**
 * file type validator for plain text files
 */
class FileTypeTextplain extends FileType
{
	public function __construct($sErrorMessage)
	{
		parent::__construct($sErrorMessage, 'text/plain');
	}

}
?>
