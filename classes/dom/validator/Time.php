<?php
namespace dr\classes\dom\validator;



use dr\classes\dom\tag\form\FormInputAbstract;
use dr\classes\locale\TCountrySettings;

include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_date.php');

/**
 * valid date
 */
class Time extends ValidatorAbstract
{
        private $sPHPDateFormat = ''; //default 
        private $bAllowEmpty = false;
        
        /**
         * 
         * @param string $sErrorMessage
         * @param string $sPHPDateFormat like 'd-m-Y', if '' then TCountrySettings default date is assumed
         * @param string $bAllowEmpty
         */        
    	public function __construct($sErrorMessage, $sPHPDateFormat = '', $bAllowEmpty = false)
	{   
                $this->sPHPDateFormat = $sPHPDateFormat;
                $this->bAllowEmpty = $bAllowEmpty;
                
		parent::__construct($sErrorMessage);
	}
        
	public function isValid(FormInputAbstract $objUploadedFile)
	{
                if ($this->bAllowEmpty)
                        if ($objUploadedFile->getContentsSubmitted()->isEmpty())
                                return true;
            
                if ($objUploadedFile->getContentsSubmitted()->isEmpty())
                        return false;
                        
		return isValidTime($objUploadedFile->getContentsSubmitted()->getValue(), $this->sPHPDateFormat);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}

}

?>