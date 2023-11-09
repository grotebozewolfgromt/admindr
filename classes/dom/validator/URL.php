<?php
namespace dr\classes\dom\validator;

use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * is a valid url
 */
class URL extends ValidatorAbstract
{
	private $bIgnoreEmpty = false;


	public function __construct($sErrorMessage, $bIgnoreEmpty = false)
	{
		$this->setIgnoreEmpty($bIgnoreEmpty);
		parent::__construct($sErrorMessage);
	}	


    public function setIgnoreEmpty($bIgnore)
    {
            $this->bIgnoreEmpty = $bIgnore;
    }
		
	public function isValid(FormInputAbstract $objUploadedFile)
	{
		$sFormInputValue = $objUploadedFile->getContentsSubmitted()->getValue();
		return (isValidURL($sFormInputValue));
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		return preg_replace(preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",'', $sURL);)
	}
}

?>