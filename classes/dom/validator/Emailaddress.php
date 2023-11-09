<?php

namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * valid emailaddress
 * 
 * it can ignore when the field is empty
 * 
 * 11jan 2020- Emailaddress validator - can ignore an empty field
 * 
 */
class Emailaddress extends ValidatorAbstract
{
    private $bIgnoreEmpty = false;
    private $bCheckDNS = false;
    private $bCheckLatinChars = false;
    
    /**
     * ignore validation when email field is empty
     * 
     * @param string $sErrorMessage
     * @param boolean $bIgnoreEmpty
     */
    public function __construct($sErrorMessage, $bIgnoreEmpty = false, $bCheckDNS= false, $bCheckLatinChars =false)
    {
            $this->setIgnoreEmpty($bIgnoreEmpty);
            $this->setCheckDNSRecord($bCheckDNS);
            $this->setCheckLatinChars($bCheckLatinChars);
            parent::__construct($sErrorMessage);
    }

    public function isValid(FormInputAbstract $objUploadedFile)
    {
            if ($this->bIgnoreEmpty)
            {
                if ($objUploadedFile->getContentsSubmitted()->getValueAsString() == '')
                        return true;
            }

// vardumpdie(isValidEmail($objUploadedFile->getContentsSubmitted()->getValue(), $this->bCheckDNS, $this->bCheckLatinChars), 'fredoorkleop')            ;
            return isValidEmail($objUploadedFile->getContentsSubmitted()->getValue(), $this->bCheckDNS, $this->bCheckLatinChars);
    }

    public function filterValue(FormInputAbstract $objUploadedFile)
    {
            //
    }
    
    public function setIgnoreEmpty($bIgnore)
    {
            $this->bIgnoreEmpty = $bIgnore;
    }

    public function setCheckDNSRecord($bCheck)
    {
            $this->bCheckDNS = $bCheck;
    }

    public function setCheckLatinChars($bCheck)
    {
            $this->bCheckLatinChars = $bCheck;
    }

}
?>