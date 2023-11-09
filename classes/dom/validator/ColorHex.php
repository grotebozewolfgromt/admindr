<?php

namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * valid hex
 * 
 * it can ignore when the field is empty
 * 
 * 3 nov 2023- Color hex validator
 * 
 */
class ColorHex extends ValidatorAbstract
{
    private $bIgnoreEmpty = false;
    private $bTrailingHash = false;
        
    /**
     * ignore validation when email field is empty
     * 
     * @param string $sErrorMessage
     * @param boolean $bIgnoreEmpty
     * @param boolean $bTrailingHash  to include #-sign before hex value?
     */
    public function __construct($sErrorMessage, $bIgnoreEmpty = false, $bTrailingHash = false)
    {
            $this->setTrailingHash($bTrailingHash);
            parent::__construct($sErrorMessage);
    }

    public function isValid(FormInputAbstract $objInput)
    {
        $sUserInput = '';
        $sFilteredInput = '';

        $sUserInput = $objInput->getContentsSubmitted()->getValueAsString();

        //can be empty
        if ($this->bIgnoreEmpty)
        {
                if ($sUserInput  == '')
                        return true;
        }

        //compare validated input with user input
        $sFilteredInput = $this->filterValue($objInput);
        return ($sFilteredInput == $sUserInput);
    }

    public function filterValue(FormInputAbstract $objInput)
    {
        //declaration
        $sDirtyInput = '';
        $iAllowedLength = 6;
        $sAllowedChars = 'abcdefABCDEF0123456789';

        //init
        $sDirtyInput = $objInput->getContentsSubmitted()->getValueAsString();
        if ($this->bTrailingHash)
        {
            $iAllowedLength = 7;
            $sAllowedChars = $sAllowedChars.'#';
        }

        //check length              
        if (strlen($sDirtyInput) > $iAllowedLength)
              substr($sDirtyInput, 0, $iAllowedLength);

        //now filter input        
        return filterBadCharsWhiteList($objInput->getContentsSubmitted()->getValueAsString(), $sAllowedChars);
    }
    
    public function setTrailingHash($bHash)
    {
        $this->bTrailingHash = $bHash;
    }


}
?>