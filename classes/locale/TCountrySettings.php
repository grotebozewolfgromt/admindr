<?php
namespace dr\classes\locale;

use dr\classes\patterns\TObject;
use dr\classes\files\TIni;

/**
 * Description of TCountrySettings
 * 
 * 22 juli 2012: TCountrySettings created
 *
 * @author drenirie
 */
class TCountrySettings
{
    private $arrCountrySettings = array();
    private $sFilePath = '';
    private $bFileLoaded = false; //helper boolean: determine if the file is loaded. So we can ONLY use it when it is requested by the code (otherwise it only takes up system resources)
    
    //class constants and their key in the ini file
    const CURRENCY_NAME = 'currency-name';
    const CURRENCY_SIGN = 'currency-sign';
    const CURRENCY_FORMAT = 'currency-format';
    const CURRENCY_DECIMALS = 'currency-decimals'; 
    
    const VAT_NAME = 'vat-name';
    const VAT_INCLUDING_SHORT = 'vat-including-short';
    const VAT_INCLUDING_LONG = 'vat-including-long';
    const VAT_EXCLUDING_SHORT = 'vat-excluding-short';
    const VAT_EXCLUDING_LONG = 'vat-excluding-long';
    
    const SEPARATOR_THOUSAND = 'separator-thousand';
    //const THOUSAND_SEPARATOR = SEPARATOR_THOUSAND; //alias
    const SEPARATOR_DECIMAL = 'separator-decimal';
    //const DECIMAL_SEPARATOR = SEPARATOR_DECIMAL;//alias
    const SEPARATOR_THOUSAND_CURRENCY = 'separator-thousand-currency';
    const SEPARATOR_DECIMAL_CURRENCY = 'separator-decimal-currency';
    
    const PERCENT_FORMAT = 'percent-format';
    const PERCENT_DECIMALS = 'percent-decimals';
    const PERCENT_SIGN = 'percent-sign';
    
    const MEASURE_SYSTEM = 'measure-system';//'metric' of 'us'
    
    const CALENDAR_TYPE = 'calendar-type'; //'gregorian', 'buddhist' etc.
    const TIMEZONE = 'timezone';
    
    const DATEFORMAT_FULL = 'dateformat-full';
    const DATEFORMAT_LONG = 'dateformat-long';
    const DATEFORMAT_MEDIUM = 'dateformat-medium';
    const DATEFORMAT_DEFAULT = 'dateformat-medium'; //alias for medium
    const DATEFORMAT_SHORT = 'dateformat-short';
    
    const DATETIME_FORMAT_REGULAR = 'datetimeformat-regular';
    const DATETIME_FORMAT_DEFAULT = 'datetimeformat-regular';
    const TIMEFORMAT_ANTEMERIDIUM_FULL = 'timeformat-antemeridium-full';
    const TIMEFORMAT_ANTEMEDIDIUM_SHORT = 'timeformat-antemeridium-short';
    const TIMEFORMAT_POSTMERIDIUM_FULL = 'timeformat-postmeridium-full';
    const TIMEFORMAT_POSTMERIDIUM_SHORT = 'timeformat-postmeridium-short';
    const TIMEFORMAT_FULL = 'timeformat-full';
    const TIMEFORMAT_SHORT = 'timeformat-short';
    const TIMEFORMAT_DEFAULT = 'timeformat-short'; //alias for short
    
    const FIRST_DAY_OF_THE_WEEK = 'first-day-of-the-week'; //0=zondag, 1=maandag etc.

    const JANUARY = 'January';
    const FEBRUARY = 'February';
    const MARCH = 'March';
    const APRIL = 'April';
    const MAY = 'May';
    const JUNE = 'June';
    const JULY = 'July';
    const AUGUST = 'August';
    const SEPTEMBER = 'September';
    const OCTOBER = 'October';
    const NOVEMBER = 'November';
    const DECEMBER = 'December';
    const JANUARY_SHORT = 'january-short';
    const FEBRUARY_SHORT = 'february-short';
    const MARCH_SHORT = 'march-short';
    const APRIL_SHORT = 'april-short';
    const MAY_SHORT = 'may-short';
    const JUNE_SHORT = 'june-short';
    const JULY_SHORT = 'july-short';
    const AUGUST_SHORT = 'august-short';
    const SEPTEMBER_SHORT = 'september-short';
    const OCTOBER_SHORT = 'october-short';
    const NOVEMBER_SHORT = 'november-short';
    const DECEMBER_SHORT = 'december-short';
    
    const SUNDAY = 'sunday';
    const MONDAY = 'monday';
    const TUESDAY = 'tuesday';
    const WEDNESDAY = 'wednesday';
    const THURSDAY = 'thursday';
    const FRIDAY = 'friday';
    const SATURDAY = 'saturday';
    const SUNDAY_SHORT = 'sunday-short';
    const MONDAY_SHORT = 'monday-short';
    const TUESDAY_SHORT = 'tuesday-short';
    const WEDNESDAY_SHORT = 'wednesday-short';
    const THURSDAY_SHORT = 'thursday-short';
    const FRIDAY_SHORT = 'friday-short';
    const SATURDAY_SHORT = 'saturnday-short';

    const DAY_ONE = 'day-one';
    const DAY_OTHER = 'day-other';
    const MONTH_ONE = 'month-one';
    const MONTH_OTHER = 'month-other';
    const YEAR_ONE = 'year-one';
    const YEAR_OTHER = 'year-other';
    const HOUR_ONE = 'hour-one';
    const HOUR_OTHER = 'hour-other';
    const MINUTE_ONE = 'minute-one';
    const MINUTE_OTHER = 'minute-other';
    const SECOND_ONE = 'second-one';
    const SECOND_OTHER = 'second-other';
    
//     const YES = 'yes';
//     const NO = 'no';
//     const TRUE_ = 'true'; //true en false zijn gereserveerde woorden in php
//     const FALSE_ = 'false'; //true en false zijn gereserveerde woorden in php

    const PREFIX_LANGUAGECODE = 'languagecode_';
    const PREFIX_COUNTRYCODE = 'countrycode_';
    const PREFIX_SCRIPTS = 'scripts_';
    
    
    public function __construct() 
    {
        $this->init();
    }
    
    public function init()
    {
        if ($this->arrCountrySettings)
            unset($this->arrCountrySettings);
        $this->arrCountrySettings = array();          
        
    }
    
    
    public function setFileName($sFilePath)
    {
        $this->sFilePath = $sFilePath;
    }
    
    public function getFileName()
    {
        return $this->sFilePath;
    }
    
    /**
     * read the locale settings from the countrysettings ini file 
     * 
     * @return bool
     */
    public function loadFromFile()
    {
        $sFileName = $this->getFileName();
        
        $objCountrySettingsFile = new TIni(false, false);
        if (is_file($sFileName))
        {
            if (!$objCountrySettingsFile->loadFromFile($sFileName))
                return false;
        }        

       
        //====numbers
        $this->arrCountrySettings[TCountrySettings::CURRENCY_NAME] = $objCountrySettingsFile->read('numbers', TCountrySettings::CURRENCY_NAME, 'Pounds Sterling');
        $this->arrCountrySettings[TCountrySettings::CURRENCY_SIGN] = $objCountrySettingsFile->read('numbers', TCountrySettings::CURRENCY_SIGN, '£');
        $this->arrCountrySettings[TCountrySettings::CURRENCY_FORMAT] = $objCountrySettingsFile->read('numbers', TCountrySettings::CURRENCY_FORMAT, '£ [amount]');
        $this->arrCountrySettings[TCountrySettings::CURRENCY_DECIMALS] = $objCountrySettingsFile->read('numbers', TCountrySettings::CURRENCY_DECIMALS, '2');               
        
        $this->arrCountrySettings[TCountrySettings::VAT_NAME] = $objCountrySettingsFile->read('numbers', TCountrySettings::VAT_NAME, 'vat');
        $this->arrCountrySettings[TCountrySettings::VAT_INCLUDING_LONG] = $objCountrySettingsFile->read('numbers', TCountrySettings::VAT_INCLUDING_LONG, 'including VAT');
        $this->arrCountrySettings[TCountrySettings::VAT_INCLUDING_SHORT] = $objCountrySettingsFile->read('numbers', TCountrySettings::VAT_INCLUDING_SHORT, 'incl VAT');
        $this->arrCountrySettings[TCountrySettings::VAT_EXCLUDING_LONG] = $objCountrySettingsFile->read('numbers', TCountrySettings::VAT_EXCLUDING_LONG, 'excluding VAT');
        $this->arrCountrySettings[TCountrySettings::VAT_EXCLUDING_SHORT] = $objCountrySettingsFile->read('numbers', TCountrySettings::VAT_EXCLUDING_SHORT, 'excl VAT');

        $this->arrCountrySettings[TCountrySettings::SEPARATOR_THOUSAND] = $objCountrySettingsFile->read('numbers', TCountrySettings::SEPARATOR_THOUSAND, '.');
        $this->arrCountrySettings[TCountrySettings::SEPARATOR_DECIMAL] = $objCountrySettingsFile->read('numbers', TCountrySettings::SEPARATOR_DECIMAL, ',');
        $this->arrCountrySettings[TCountrySettings::SEPARATOR_THOUSAND_CURRENCY] = $objCountrySettingsFile->read('numbers', TCountrySettings::SEPARATOR_THOUSAND_CURRENCY, '.');
        $this->arrCountrySettings[TCountrySettings::SEPARATOR_DECIMAL_CURRENCY] = $objCountrySettingsFile->read('numbers', TCountrySettings::SEPARATOR_DECIMAL_CURRENCY, ',');
        
        $this->arrCountrySettings[TCountrySettings::PERCENT_FORMAT] = $objCountrySettingsFile->read('numbers', TCountrySettings::PERCENT_FORMAT, '[amount]%');
        $this->arrCountrySettings[TCountrySettings::PERCENT_DECIMALS] = $objCountrySettingsFile->read('numbers', TCountrySettings::PERCENT_DECIMALS, '2');
        $this->arrCountrySettings[TCountrySettings::PERCENT_SIGN] = $objCountrySettingsFile->read('numbers', TCountrySettings::PERCENT_SIGN, '%');
        
        $this->arrCountrySettings[TCountrySettings::MEASURE_SYSTEM] = $objCountrySettingsFile->read('numbers', TCountrySettings::MEASURE_SYSTEM, 'metric');//'metric' of 'us'
        
        
        //====dates and times
        $this->arrCountrySettings[TCountrySettings::CALENDAR_TYPE] = $objCountrySettingsFile->read('dates', TCountrySettings::CALENDAR_TYPE, 'gregorian');//'gregorian', 'buddhist' etc.
        $this->arrCountrySettings[TCountrySettings::TIMEZONE] = $objCountrySettingsFile->read('dates', TCountrySettings::TIMEZONE, 'Europe/London');

        $this->arrCountrySettings[TCountrySettings::DATEFORMAT_FULL] = $objCountrySettingsFile->read('dates', TCountrySettings::DATEFORMAT_FULL, 'l j F Y'); //maandag 9 januari 2012
        $this->arrCountrySettings[TCountrySettings::DATEFORMAT_LONG] = $objCountrySettingsFile->read('dates', TCountrySettings::DATEFORMAT_LONG, 'D j D Y'); //ma 9 jan 2012
        $this->arrCountrySettings[TCountrySettings::DATEFORMAT_MEDIUM] = $objCountrySettingsFile->read('dates', TCountrySettings::DATEFORMAT_MEDIUM, 'd-m-Y');//09-01-2012
        $this->arrCountrySettings[TCountrySettings::DATEFORMAT_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::DATEFORMAT_SHORT, 'j-n-y');//9-1-12
        
        $this->arrCountrySettings[TCountrySettings::DATETIME_FORMAT_REGULAR] = $objCountrySettingsFile->read('dates', TCountrySettings::DATETIME_FORMAT_REGULAR, 'd-m-Y H:i'); //datum met tijd notatie

        $this->arrCountrySettings[TCountrySettings::TIMEFORMAT_ANTEMERIDIUM_FULL] = $objCountrySettingsFile->read('dates', TCountrySettings::TIMEFORMAT_ANTEMERIDIUM_FULL, 'ante meridium'); //AM of PM
        $this->arrCountrySettings[TCountrySettings::TIMEFORMAT_ANTEMEDIDIUM_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::TIMEFORMAT_ANTEMEDIDIUM_SHORT, 'AM'); //AM of PM
        $this->arrCountrySettings[TCountrySettings::TIMEFORMAT_POSTMERIDIUM_FULL] = $objCountrySettingsFile->read('dates', TCountrySettings::TIMEFORMAT_POSTMERIDIUM_FULL, 'post meridium'); //AM of PM
        $this->arrCountrySettings[TCountrySettings::TIMEFORMAT_POSTMERIDIUM_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::TIMEFORMAT_POSTMERIDIUM_SHORT, 'PM'); //AM of PM        
        $this->arrCountrySettings[TCountrySettings::TIMEFORMAT_FULL] = $objCountrySettingsFile->read('dates', TCountrySettings::TIMEFORMAT_FULL, 'H:i:s'); //09:12:22
        $this->arrCountrySettings[TCountrySettings::TIMEFORMAT_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::TIMEFORMAT_SHORT, 'H:i'); //09:12
        
        $this->arrCountrySettings[TCountrySettings::FIRST_DAY_OF_THE_WEEK] = $objCountrySettingsFile->read('dates', TCountrySettings::FIRST_DAY_OF_THE_WEEK, '1'); //0=zondag, 1=maandag etc.
        
        
        //====dates and times - names
        $this->arrCountrySettings[TCountrySettings::JANUARY] = $objCountrySettingsFile->read('dates', TCountrySettings::JANUARY, 'January'); 
        $this->arrCountrySettings[TCountrySettings::FEBRUARY] = $objCountrySettingsFile->read('dates', TCountrySettings::FEBRUARY, 'February'); 
        $this->arrCountrySettings[TCountrySettings::MARCH] = $objCountrySettingsFile->read('dates', TCountrySettings::MARCH, 'March'); 
        $this->arrCountrySettings[TCountrySettings::APRIL] = $objCountrySettingsFile->read('dates', TCountrySettings::APRIL, 'April'); 
        $this->arrCountrySettings[TCountrySettings::MAY] = $objCountrySettingsFile->read('dates', TCountrySettings::MAY, 'May'); 
        $this->arrCountrySettings[TCountrySettings::JUNE] = $objCountrySettingsFile->read('dates', TCountrySettings::JUNE, 'June'); 
        $this->arrCountrySettings[TCountrySettings::JULY] = $objCountrySettingsFile->read('dates', TCountrySettings::JULY, 'July'); 
        $this->arrCountrySettings[TCountrySettings::AUGUST] = $objCountrySettingsFile->read('dates', TCountrySettings::AUGUST, 'August'); 
        $this->arrCountrySettings[TCountrySettings::SEPTEMBER] = $objCountrySettingsFile->read('dates', TCountrySettings::SEPTEMBER, 'September'); 
        $this->arrCountrySettings[TCountrySettings::OCTOBER] = $objCountrySettingsFile->read('dates', TCountrySettings::OCTOBER, 'October'); 
        $this->arrCountrySettings[TCountrySettings::NOVEMBER] = $objCountrySettingsFile->read('dates', TCountrySettings::NOVEMBER, 'Novermber'); 
        $this->arrCountrySettings[TCountrySettings::DECEMBER] = $objCountrySettingsFile->read('dates', TCountrySettings::DECEMBER, 'December'); 
        $this->arrCountrySettings[TCountrySettings::JANUARY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::JANUARY_SHORT, 'Jan'); 
        $this->arrCountrySettings[TCountrySettings::FEBRUARY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::FEBRUARY_SHORT, 'Feb'); 
        $this->arrCountrySettings[TCountrySettings::MARCH_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::MARCH_SHORT, 'Mrch'); 
        $this->arrCountrySettings[TCountrySettings::APRIL_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::APRIL_SHORT, 'Apr'); 
        $this->arrCountrySettings[TCountrySettings::MAY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::MAY_SHORT, 'May'); 
        $this->arrCountrySettings[TCountrySettings::JUNE_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::JUNE_SHORT, 'Jun'); 
        $this->arrCountrySettings[TCountrySettings::JULY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::JULY_SHORT, 'Jul'); 
        $this->arrCountrySettings[TCountrySettings::SEPTEMBER_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::SEPTEMBER_SHORT, 'Aug'); 
        $this->arrCountrySettings[TCountrySettings::OCTOBER_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::OCTOBER_SHORT, 'Oct'); 
        $this->arrCountrySettings[TCountrySettings::NOVEMBER_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::NOVEMBER_SHORT, 'Nov'); 
        $this->arrCountrySettings[TCountrySettings::DECEMBER_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::DECEMBER_SHORT, 'Dec'); 
        
        $this->arrCountrySettings[TCountrySettings::SUNDAY] = $objCountrySettingsFile->read('dates', TCountrySettings::SUNDAY, 'Sunday'); 
        $this->arrCountrySettings[TCountrySettings::MONDAY] = $objCountrySettingsFile->read('dates', TCountrySettings::MONDAY, 'Monday'); 
        $this->arrCountrySettings[TCountrySettings::TUESDAY] = $objCountrySettingsFile->read('dates', TCountrySettings::TUESDAY, 'Tuesday'); 
        $this->arrCountrySettings[TCountrySettings::WEDNESDAY] = $objCountrySettingsFile->read('dates', TCountrySettings::WEDNESDAY, 'Wednesday'); 
        $this->arrCountrySettings[TCountrySettings::THURSDAY] = $objCountrySettingsFile->read('dates', TCountrySettings::THURSDAY, 'Thursday'); 
        $this->arrCountrySettings[TCountrySettings::FRIDAY] = $objCountrySettingsFile->read('dates', TCountrySettings::FRIDAY, 'Friday'); 
        $this->arrCountrySettings[TCountrySettings::SATURDAY] = $objCountrySettingsFile->read('dates', TCountrySettings::SATURDAY, 'Saturday'); 
        $this->arrCountrySettings[TCountrySettings::SUNDAY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::SUNDAY_SHORT, 'Su'); 
        $this->arrCountrySettings[TCountrySettings::MONDAY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::MONDAY_SHORT, 'Mo'); 
        $this->arrCountrySettings[TCountrySettings::TUESDAY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::TUESDAY_SHORT, 'Tu'); 
        $this->arrCountrySettings[TCountrySettings::WEDNESDAY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::WEDNESDAY_SHORT, 'We'); 
        $this->arrCountrySettings[TCountrySettings::THURSDAY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::THURSDAY_SHORT, 'Th'); 
        $this->arrCountrySettings[TCountrySettings::FRIDAY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::FRIDAY_SHORT, 'Fr'); 
        $this->arrCountrySettings[TCountrySettings::SATURDAY_SHORT] = $objCountrySettingsFile->read('dates', TCountrySettings::SATURDAY_SHORT, 'Sa'); 

        $this->arrCountrySettings[TCountrySettings::DAY_ONE] = $objCountrySettingsFile->read('dates', TCountrySettings::DAY_ONE, 'day'); 
        $this->arrCountrySettings[TCountrySettings::DAY_OTHER] = $objCountrySettingsFile->read('dates', TCountrySettings::DAY_OTHER, 'days'); 
        $this->arrCountrySettings[TCountrySettings::MONTH_ONE] = $objCountrySettingsFile->read('dates', TCountrySettings::MONTH_ONE, 'month'); 
        $this->arrCountrySettings[TCountrySettings::MONTH_OTHER] = $objCountrySettingsFile->read('dates', TCountrySettings::MONTH_OTHER, 'months'); 
        $this->arrCountrySettings[TCountrySettings::YEAR_ONE] = $objCountrySettingsFile->read('dates', TCountrySettings::YEAR_ONE, 'year'); 
        $this->arrCountrySettings[TCountrySettings::YEAR_OTHER] = $objCountrySettingsFile->read('dates', TCountrySettings::YEAR_OTHER, 'years'); 
        $this->arrCountrySettings[TCountrySettings::HOUR_ONE] = $objCountrySettingsFile->read('dates', TCountrySettings::HOUR_ONE, 'hour'); 
        $this->arrCountrySettings[TCountrySettings::HOUR_OTHER] = $objCountrySettingsFile->read('dates', TCountrySettings::HOUR_OTHER, 'hours'); 
        $this->arrCountrySettings[TCountrySettings::MINUTE_ONE] = $objCountrySettingsFile->read('dates', TCountrySettings::MINUTE_ONE, 'minute'); 
        $this->arrCountrySettings[TCountrySettings::MINUTE_OTHER] = $objCountrySettingsFile->read('dates', TCountrySettings::MINUTE_OTHER, 'minutes'); 
        $this->arrCountrySettings[TCountrySettings::SECOND_ONE] = $objCountrySettingsFile->read('dates', TCountrySettings::SECOND_ONE, 'second'); 
        $this->arrCountrySettings[TCountrySettings::SECOND_OTHER] = $objCountrySettingsFile->read('dates', TCountrySettings::SECOND_OTHER, 'seconds'); 
        
        //===misc
//         $this->arrCountrySettings[TCountrySettings::YES] = $objCountrySettingsFile->read('misc', TCountrySettings::YES, 'ja'); 
//         $this->arrCountrySettings[TCountrySettings::NO] = $objCountrySettingsFile->read('misc', TCountrySettings::NO, 'nee'); 
//         $this->arrCountrySettings[TCountrySettings::TRUE_] = $objCountrySettingsFile->read('misc', TCountrySettings::TRUE_, 'waar'); 
//         $this->arrCountrySettings[TCountrySettings::FALSE_] = $objCountrySettingsFile->read('misc', TCountrySettings::FALSE_, 'onwaar'); 
        
        //languages
        $this->arrCountrySettings[TCountrySettings::PREFIX_LANGUAGECODE.'nl'] = $objCountrySettingsFile->read('languages', TCountrySettings::PREFIX_LANGUAGECODE.'nl', 'Netherlands (Dutch)');
        $this->arrCountrySettings[TCountrySettings::PREFIX_LANGUAGECODE.'nl-BE'] = $objCountrySettingsFile->read('languages', TCountrySettings::PREFIX_LANGUAGECODE.'nl-BE', 'Dutch (Flemmish)');
        $this->arrCountrySettings[TCountrySettings::PREFIX_LANGUAGECODE.'de'] = $objCountrySettingsFile->read('languages', TCountrySettings::PREFIX_LANGUAGECODE.'de', 'German');
        $this->arrCountrySettings[TCountrySettings::PREFIX_LANGUAGECODE.'de-AT'] = $objCountrySettingsFile->read('languages', TCountrySettings::PREFIX_LANGUAGECODE.'de-AT', 'Gernan (Austia)');
        $this->arrCountrySettings[TCountrySettings::PREFIX_LANGUAGECODE.'de-CH'] = $objCountrySettingsFile->read('languages', TCountrySettings::PREFIX_LANGUAGECODE.'de-CH', 'Gernan (Swiss)');        
        $this->arrCountrySettings[TCountrySettings::PREFIX_LANGUAGECODE.'fr'] = $objCountrySettingsFile->read('languages', TCountrySettings::PREFIX_LANGUAGECODE.'fr', 'French');
        $this->arrCountrySettings[TCountrySettings::PREFIX_LANGUAGECODE.'fr-CH'] = $objCountrySettingsFile->read('languages', TCountrySettings::PREFIX_LANGUAGECODE.'fr', 'Swiss (French)');
        $this->arrCountrySettings[TCountrySettings::PREFIX_LANGUAGECODE.'en'] = $objCountrySettingsFile->read('languages', TCountrySettings::PREFIX_LANGUAGECODE.'en', 'English');
        $this->arrCountrySettings[TCountrySettings::PREFIX_LANGUAGECODE.'da'] = $objCountrySettingsFile->read('languages', TCountrySettings::PREFIX_LANGUAGECODE.'da', 'Danish');
        
        //scripts
        //geen spannende scripts op dit moment
        $this->arrCountrySettings[TCountrySettings::PREFIX_SCRIPTS.'Zyyy'] = $objCountrySettingsFile->read('scripts', TCountrySettings::PREFIX_SCRIPTS.'Zyyy', 'Common');
        $this->arrCountrySettings[TCountrySettings::PREFIX_SCRIPTS.'Zzzz'] = $objCountrySettingsFile->read('scripts', TCountrySettings::PREFIX_SCRIPTS.'Zzzz', 'Code for uncoded languagesystems');
        $this->arrCountrySettings[TCountrySettings::PREFIX_SCRIPTS.'Brai'] = $objCountrySettingsFile->read('scripts', TCountrySettings::PREFIX_SCRIPTS.'Brai', 'Braille');
        $this->arrCountrySettings[TCountrySettings::PREFIX_SCRIPTS.'Zsym'] = $objCountrySettingsFile->read('scripts', TCountrySettings::PREFIX_SCRIPTS.'Zsym', 'Symbols');
        $this->arrCountrySettings[TCountrySettings::PREFIX_SCRIPTS.'Zmth'] = $objCountrySettingsFile->read('scripts', TCountrySettings::PREFIX_SCRIPTS.'Zmth', 'Math notation');
        
        //countries
        $this->arrCountrySettings[TCountrySettings::PREFIX_COUNTRYCODE.'NL'] = $objCountrySettingsFile->read('territories', TCountrySettings::PREFIX_COUNTRYCODE.'NL', 'Netherlands');
        $this->arrCountrySettings[TCountrySettings::PREFIX_COUNTRYCODE.'BE'] = $objCountrySettingsFile->read('territories', TCountrySettings::PREFIX_COUNTRYCODE.'BE', 'Belgium');
        $this->arrCountrySettings[TCountrySettings::PREFIX_COUNTRYCODE.'DE'] = $objCountrySettingsFile->read('territories', TCountrySettings::PREFIX_COUNTRYCODE.'DE', 'Germany');
        $this->arrCountrySettings[TCountrySettings::PREFIX_COUNTRYCODE.'FR'] = $objCountrySettingsFile->read('territories', TCountrySettings::PREFIX_COUNTRYCODE.'FR', 'France');
        $this->arrCountrySettings[TCountrySettings::PREFIX_COUNTRYCODE.'GB'] = $objCountrySettingsFile->read('territories', TCountrySettings::PREFIX_COUNTRYCODE.'GB', 'Great Brittain');
        $this->arrCountrySettings[TCountrySettings::PREFIX_COUNTRYCODE.'CH'] = $objCountrySettingsFile->read('territories', TCountrySettings::PREFIX_COUNTRYCODE.'CH', 'Switserland');
        $this->arrCountrySettings[TCountrySettings::PREFIX_COUNTRYCODE.'AT'] = $objCountrySettingsFile->read('territories', TCountrySettings::PREFIX_COUNTRYCODE.'AT', 'Austria');
        
        if (!is_file($sFileName)) //only if settings file not exists
        {
 
            if (GLOBAL_DEVELOPMENTENVIRONMENT) //alleen in development stadium mag het bestand door iedereen aangepast worden, op webserver niet! (veiligheidsmaatregel)
            {
                if (!$objCountrySettingsFile->saveToFile($sFileName, PHP_EOL, 0777))
                    return false;
            }
            else
            {
                if (!$objCountrySettingsFile->saveToFile($sFileName, PHP_EOL, 0755))
                    return false;
            }
        }
        
        $this->bFileLoaded = true;
        
        unset($objCountrySettingsFile); //closes file
        
        return true;
    }
            
    
    
    /**
     * get a country-specific setting
     * such as the thousand separator
     * 
     * if not exists it returns null
     * 
     * @param string $sSettingName
     * @return string
     */
    public function getCountrySetting($sSettingName)
    {
        if (!$this->bFileLoaded) //only load when not loaded yet
            $this->loadFromFile();
        
        if (!array_key_exists($sSettingName,$this->arrCountrySettings))
        {
            error_log('getCountrySetting(): setting "'.$sSettingName.'" not found', $this);
            return null;    
        }
                
        return $this->arrCountrySettings[$sSettingName];
    }
    

      
}

?>
