<?php
namespace dr\classes\dom\validator;



use dr\classes\dom\tag\form\FormInputAbstract;
use dr\classes\locale\TCountrySettings;
use dr\classes\types\TDateTime;

include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_date.php');

/**
 * date not allowed before ...
 * 
 * checks also if the date is a valid date
 * 
 */
class DateMin extends ValidatorAbstract
{
        private $objDateMin = ''; //default
        private $sPHPDateFormat = ''; //default
        private $bAllowEmpty = false;
        
        /**
         * 
         * @param string $sErrorMessage
         * @param TDateTime $objDateNotAllowedBefore
         * @param string $bAllowEmpty
         */
    	public function __construct($sErrorMessage, $objDateNotAllowedBefore, $sPHPDateFormat = '', $bAllowEmpty = false)
	{   
                $this->sPHPDateFormat = $sPHPDateFormat;
                $this->objDateMin = $objDateNotAllowedBefore;
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
                
                if ($this->objDateMin)
                {
                    if ($this->objDateMin instanceof TDateTime)
                    {
                        if (isValidDate($objUploadedFile->getContentsSubmitted()->getValue(), $this->sPHPDateFormat))
                        {
                            $bResult = false;
                            $objDateFromInputField = new TDateTime();
                            $objDateFromInputField->setDateAsString($objUploadedFile->getContentsSubmitted()->getValue(), $this->sPHPDateFormat);                            
                            $bResult = $this->objDateMin->isEarlier($objDateFromInputField);
             
                            unset($objDateFromInputField);
                            
                            return $bResult;
                        }
                        else
                            return false;
                                
                    }
                    return false;
                }
                else
                    return false;
                    
                
		return isValidDate($objUploadedFile->getContentsSubmitted()->getValue(), $this->sPHPDateFormat);
	}

	public function filterValue(FormInputAbstract $objUploadedFile)
	{
		//
	}

}

?>