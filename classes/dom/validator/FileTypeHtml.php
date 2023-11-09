<?php
namespace dr\classes\dom\validator;



/**
 * file type validator for html files
 */
class FileTypeHtml extends FileType
{
	public function __construct($sErrorMessage)
	{
		parent::__construct($sErrorMessage, 'text/html');
	}

}
?>