<?php
namespace dr\classes\dom\validator;




use dr\classes\dom\tag\form\FormInputAbstract;
use dr\classes\locale\TCountrySettings;


/**
 * is a strong password
 * 
 * a strong password has:
 * -at least 1 uppercase letter
 * -at least 1 lowercase letter
 * -at least 1 digit
 * -at least one special character
 * -min 8 characters long, max 255
 * 
 */
class StrongPassword extends ValidatorAbstract
{
        const LOWERCASELETTERS = 'abcdefghijklmnopqrstuvwxyz';
        const UPPERCASELETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        const DIGITS = '0123456789';
        const SPECIALCHARS = ':;_+=.,!@#$%^&*(){}?<>';        
        
        /**
         * 
         * @param string $sErrorMessage
         */        
    	public function __construct($sErrorMessage)
	{                  
	        parent::__construct($sErrorMessage);
	}
        
	public function isValid(FormInputAbstract $objInput)
	{                
                $sPasswordOrg = '';
                $sPasswordFiltered = '';
                $sPasswordOrg = $objInput->getContentsSubmitted()->getValueAsString();

                //==== check on lengths
                if ($objInput->getContentsSubmitted()->isEmpty())
                        return false;

                if (strlen($sPasswordOrg) <= 8)
                        return false;

                if (strlen($sPasswordOrg) > 255)
                        return false;

                //==== characters that are not allowed
                //filter the password and compare with the original string
                $sPasswordFiltered = filterBadCharsWhiteList($sPasswordOrg, 
                                                                StrongPassword::LOWERCASELETTERS.
                                                                StrongPassword::UPPERCASELETTERS.
                                                                StrongPassword::DIGITS.
                                                                StrongPassword::SPECIALCHARS);
                if ($sPasswordFiltered != $sPasswordOrg)    
                        return false;     

                //==== at least one lowercase character
                if (!$this->doCharsExistInPassword($sPasswordOrg, StrongPassword::LOWERCASELETTERS))
                        return false;
                
                //==== at least one uppercase character
                if (!$this->doCharsExistInPassword($sPasswordOrg, StrongPassword::UPPERCASELETTERS))
                        return false;
                        
                //==== at least one digit
                if (!$this->doCharsExistInPassword($sPasswordOrg, StrongPassword::DIGITS))
                        return false;

                //==== at least one special character
                if (!$this->doCharsExistInPassword($sPasswordOrg, StrongPassword::SPECIALCHARS))
                        return false;                        

                return true;
	}

        /**
         * find occurence of $sCharacters in $sPassword
         * 
         * @return int
         */
        private function doCharsExistInPassword($sPassword, $sCharacters)
        {
                $iLengthChars= 0;
                $iLengthChars = strlen($sCharacters);
                $arrPieces = array();

                for ($iIndexChars = 0; $iIndexChars < $iLengthChars; $iIndexChars++)
                {
                        //explode is faster than strpos
                        $arrPieces = explode($sCharacters[$iIndexChars], $sPassword);
                        
                        if (count($arrPieces) > 1) //if not found, it is 1, else its > 1
                                return true;
                }

                return false;
        }

	public function filterValue(FormInputAbstract $objInput)
	{
                //noop
	}

        /**
         * explains the rules of a strong password.
         * returns the rules as array (every rule is 1 element in the array)
         * the text in the array is already translated with transw()
         *
         * @return array
         */
        static public function getRules()
        {
                //space out the special characters
                $sSpecialCharsWithSpaces = '';
                $iStrLen = 0;
                $iStrLen = strlen(StrongPassword::SPECIALCHARS);
                $sSpecialChars = StrongPassword::SPECIALCHARS;
                for ($iCounter = 0; $iCounter < $iStrLen; $iCounter++)
                {
                        if ($iCounter > 0) //skip the first space
                                $sSpecialCharsWithSpaces.= ' ';
                        $sSpecialCharsWithSpaces.= $sSpecialChars[$iCounter];
                }

                //the rules
                $arrRules = array();
                $arrRules[] = transw('textbox_validator_strongpassword_rule_1uppercaseletter', 'At least 1 upper case letter (A-Z).');
                $arrRules[] = transw('textbox_validator_strongpassword_rule_1lowercaseletter', 'At least 1 lower case letter (a-z).');
                $arrRules[] = transw('textbox_validator_strongpassword_rule_1upperdigit', 'At least 1 digit (0-9).');
                $arrRules[] = transw('textbox_validator_strongpassword_rule_1specialcharacter', 'At least 1 of these special characters: [specialchar]', 'specialchar', $sSpecialCharsWithSpaces);
                $arrRules[] = transw('textbox_validator_strongpassword_rule_between8and255chars', 'Needs to be at least 8 characters long.');

                return $arrRules;
        }

}

?>