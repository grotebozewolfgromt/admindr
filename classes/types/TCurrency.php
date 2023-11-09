<?php
namespace dr\classes\types;

/**
 * Description of TCurrency
 * represents a currency value. deze klasse HEEFT NIETS MET TFLOAT te maken!
 * De interne waarde is gepresenteerd in centen (bij precisie 2) of honderdste centen (bij precisie 4)
 * 
 * vanwege de afwijkingingen van floats door de bit representatie
 * is dat een beetje link om dat ook met geld zo te doen.
 * vandaar deze klasse.
 * Floats kun je niet goed met elkaar vergelijken.
 * 
 * om problemen te voorkomen wordt geld opgeslagen als integer, zoals volgens 
 * GAAP de goede werkwijze is.
 * de decimalen precisie kun je zelf instellen (iDecimalPrecision)
 * 
 * Het maximum van deze interne waarde = maximum integer64 / 10000 (bij 4 decimalen precisie) 
 * omdat sommige systemen met 32 bits integers werkt zit je met ��� 21.0000,0000 (21 duizend euro) aan je maximum met 4 decimalen precisie!!!
 * vraag maximum op met getMaxValue(), deze berekent aan de hand van je precisie of je waarde over de grens gaat
* 
 * 
 * If you want to meet Generally Accepted Accounting Principles (GAAP), 
 * you need to have at least FOUR decimal places. 
 * This ensures that rounding errors will not, on average, exceed $0.01 more 
 * often than many thousand transactions (because rounding errors tend to even out).
 * 
 * If you want to be compliant with Generally Accepted Accounting Principles (GAAP), 
 * then you should use DECIMAL(13,4) in MySQL.
 * 
 * 20 juli 2012: TCurrency created
 * 20 juli 2012: TCurrency erft nu over van TFloatAbstract
 * 20 juli 2012: TCurrency heeft omrekening naar inclusief en exclusief btw erbij
 * 20 juli 2012: TCurrency nieuwe functie : convertToOtherCurrency()
 * 20 juli 2012: TCurrency nieuwe functie: getAsStringWithoutCurrencySymbol()
 * 20 juli 2012: TCurrency nieuwe functie: getAsStringWithoutCurrencySymbol()
 * 3 mei 2014: TCurrency geen child meer van TFloat ivm afrondingsverschillen, klasse volledig opnieuw gebouwd
 * 
 * @author drenirie
 */
class TCurrency extends TDecimal
{
   /**
     * get the value as a locale formatted string
     * 
     * @global Application $objApplication
     * @return string 
     */            
    public function getValueFormatted()
    {
        global $objApplication;
        $sResultString = '';
       
        if ($objApplication)
        {
            if ($objApplication->getLocale())
            {
                $sThousandSeparator = $objApplication->getCountrySettings()->getCountrySetting(TCountrySettings::SEPARATOR_THOUSAND_CURRENCY);
                $sDecimalSeparator = $objApplication->getCountrySettings()->getCountrySetting(TCountrySettings::SEPARATOR_DECIMAL_CURRENCY);
                $fRealValue = $this->getValueInternal() / $this->getMultiplyFactor($this->getDecimalPrecision());
                
                return number_format($fRealValue, $objApplication->getCountrySettings()->getCountrySetting(TCountrySettings::CURRENCY_DECIMALS), $sDecimalSeparator, $sThousandSeparator);                 
            }
        }        
        
        return $sResultString;
    }    
    
    
   

    
    
    /**
     * set a value as a string
     * 
     * it uses the locale format settings to extract the correct value
     * 
     * this function determines what the decimal precision of $sValue and and adjusts it to internal precision
     * 
     * LET OP: afrondingsverschillen als de precisie van $sValue GROTER dan van de interne waarde
     * 
     * je kunt deze functie gebruiken om waardes uit een html editbox te interpreteren
     * deze functie is sql-injection safe, omdat alle foute karakters gefilterd worden
     * 
     * (deze functie wordt overerfd omdat de separators van geld anders kunnen zijn dan van standaard getallen)
     * 
     * @global Application $objApplication
     * @param string $sValue 
     */            
    public function setValueFormatted($sValue)
    {
        global $objApplication;
       
        if ($objApplication)
        {
            if ($objApplication->getLocale())
            {                
                $sThousandSeparator = $objApplication->getCountrySettings()->getCountrySetting(TCountrySettings::SEPARATOR_THOUSAND_CURRENCY);
                $sDecimalSeparator = $objApplication->getCountrySettings()->getCountrySetting(TCountrySettings::SEPARATOR_DECIMAL_CURRENCY); 
                
                $sValue = str_replace($sThousandSeparator, '', $sValue); //de 'duizend'-scheidings-karakters filteren
                $sValue = str_replace($sDecimalSeparator, '.', $sValue); //decimaal karakter vervangen voor engelse punt(.)
                
                $this->setValue($sValue);
            }
        }
    }     
    
    /**
     * return new TCurrency object including sales taxes
     * 
     * bedrag omrekenen naar inclusief btw
     * 
     * @param TPercentage $objVATPercentage
     * @return TCurrency 
     */
    function getIncludingVAT(TDecimal $objVATPercentage)
    {
        $objExclBTW = clone $this;
        $objVatPercentageCopy = clone $objVATPercentage;
        
        $objExclBTW->divideInt(100);
        $objVatPercentageCopy->addInt(100);
        
        $objExclBTW->multiply($objVatPercentageCopy);
                
        return $objExclBTW;
    }    
    

    /**
     * return new TCurrency object excluding sales taxes
     * 
     * bedrag omrekenen naar exclusief btw 
     * 
     * @param TPercentage $objVATPercentage
     * @return TCurrency 
     */
    function getExcludingVAT(TDecimal $objVATPercentage)
    {
        $objVatPercentageCopy = clone $objVATPercentage;
        $objInclBTW = clone $this;
          
        $objVatPercentageCopy->addInt(100);
        $objInclBTW->divide($objVatPercentageCopy);
          
        $objInclBTW->multiplyInt(100);
          

        return $objInclBTW;
    }  
    
    
    /**
     * currency converter
     * convert this object to other currency object i.e.
     * convert Euro's to Dollars
     * For example:
     * 1 euro = 1,22 dollars, so the exchange rate is 1,22
     * 
     * @param TCurrency $objExchangeRate how many [Dollars] is 1 [Euro] ?
     * @return TCurrency 
     */
    function convertToOtherCurrency(TCurrency $objExchangeRate)
    {       
        $objOtherCurr = clone $this;
        $objOtherCurr->multiply($objExchangeRate);
        return $objOtherCurr;
    }
}

?>
