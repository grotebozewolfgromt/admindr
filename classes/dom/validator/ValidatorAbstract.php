<?php
namespace dr\classes\dom\validator;


use dr\classes\dom\tag\form\FormInputAbstract;

/**
 * the validators for the form generator
 *
 * 14 okt 09
 * =========
 * -onlyalfanumeric toegevoegd
 */

/**
 * base class for the form validator classes
 */
abstract class ValidatorAbstract
{
    private $sErrorMessage = 'Sorry, submitted value is not allowed!'; //default

    /**
     * constructor
     * supply an error message to the validator for displaying errors when nessecary
     * @param string $sErrorMessage when not valid: display this message
     */
    public function __construct($sErrorMessage)
    {
        $this->setErrorMessage($sErrorMessage);
    }

    /**
     * set a custom error message for the specific validator when value is not valid
     * @param string $sError
     */
    public function setErrorMessage($sError)
    {
        $this->sErrorMessage = $sError;
    }

    /**
     * get the custom error message for the specific validator when value is not valid
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->sErrorMessage;
    }

    /**
     * a helping function for using regular expressions to check for valid
     * values in the descendants of this class
     * just so you can write your own validor very quickly
     *
     * @param string $sRegEx the regular expression to match
     * @param string input $objInput the input value of the form
     * @return bool
     */
    protected function isValidRegex($sRegEx, FormInputAbstract $objInput)
    {
        $sFormInputValue = $objInput->getContentsSubmitted()->getValue();
        $iResult = preg_match($sRegEx, $sFormInputValue);

        if ($iResult == 0)
            return false;
        else
            return true;
    }

    /**
     * filters the form input value to make it valid.
     *
     * @param input $objInput - input object
     * @return string returns filtered form-input-value
     */
    abstract public function filterValue(FormInputAbstract $objInput);

    /**
     * the function that checks all input values uses this function.
     * DO NOT OVERRIDE!
     * It filters the value to make it valid, then checks if it is valid
     * @param input $objInput input object
     * @return bool is valid after filtering ?
     */
    final public function filterAndValidate(FormInputAbstract $objInput)
    {
        $this->filterValue($objInput);
        return $this->isValid($sFormInputValue);
    }

    /**
     * checks if the uploaded file value is valid
     * @param input $objInput input object
     * @return bool
     */
    abstract public function isValid(FormInputAbstract $objInput);

}

?>
