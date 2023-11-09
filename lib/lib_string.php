<?php
/**
 * In this library exist only string related functions
 *
 * IMPORTANT:
 * This library is language independant, so don't use language specific element
 *
 *  4 juli 2012: in checkEmail(): filter_var gebruikt ipv zelf programmeren
 * 8 juli 2012: strToArr en arrToStr hebben als default parameter niet \n maar PHP_EOL
 *
 * 12 juli 2012: lib_string: filterSQLInjection($mInput) toegevoegd en filterDirectoryTraversal()
 * 18 mrt 2014: lib_string: filterBadCharsWhiteList() bugfix. eerste karakter van de whitelist werd niet meegenomen
 * 7 aug 2014: lib_string: filterSQLInjection() - count wordt niet langer gefilterd
 * 14 jan 2015: lib_string: filterSQLInjection(): extra veiligheid: dubbele punt wordt ook gefilterd
 * 14 jan 2015: lib_string: filterSQLInjection(): extra veiligheid: array als parameter moet je expliciet toestaan
 * 14 jan 2015: lib_string: filterSQLInjection(): puntkomma wordt vervangen door een punt
 * 14 jan 2015: lib_string: filterSQLInjection(): dubbele punt uitzonderingen voor http:// en style:
 * 14 jan 2015: lib_string: filterSQLInjection(): ondervanging van utf8 grapjes
 * 2 apr 2015: lib_string: checkEmail(): mx record check eruit, ivm mogelijke spam preventie van de ontvangende server (veel checks zou kunnen leiden tot een blacklist van emails van dit domein), daarbij is de check nu sneller 
 * 4 apr 2015: lib_string: filterBadCharsWhiteList() en filterBadCharsBlackList() vervangen door reguliere expressies, dit werkt bijna 10x sneller dan de handmatige versies
 * 4 apr 2015: lib_string: optimalisaties door count buiten loop te doen
 * 29 apr 2015: lib_string: XSS filter function
 * 5 mei 2015: lib_string: XSS filter aangepast, deze accepteert nu ook het - teken.
 *  10 mei 2015: lib_string: XSS filter aangepast, - teken en accepteert ook underscores
 *  10 mei 2015: lib_string: escapeRegex() functie toegevoegd
 * 10 mei 2015: filterBadCharsWhiteList() uses the regex global constants for default whitelist
 * 10 mei 2015: checkEmail() mx record check is weer mogelijk. default parameter is nu false
 *  10 mei 2015: lib_string: XSS filter gebruikt nu global regex constante voor het filteren
 *  10 mei 2015: lib_string: XSS filter nu strenger bij geen html op htmlencoded en utf8 encode tags
 *  19 jun 2015: lib_string: password_hash en password_verify toegevoegd, zodat ze door het hele framework gebruikt kunnen worden. Gezien deze functies in php 5.5 geintroduceerd worden moeten ze verwijderd worden bij overschakeling naar php 5.5
 * 15 juli 2015: lib_string: filter_varext toegevoegd
 * 17 juli 2015: lib_string: filter_varext() bugje bij FILTEREXT_SANITIZE_CLASS 2 backslashes te weinig 
 * 6 dec 2019: lib_string: plainText2HTML() added
 * 6 jan 2020: lib_string: filterXSS() supports arrays
 * 11 jan 2020: lib_string: generatePassword() updated to support special characters and more improved security
 * 11 jan 2020: lib_string: convertPlaintText2HTML() removed, because plaintText2HTML() exists
 * 11 jan 2020: lib_string: generatePassword() has a second parameter to specify minimum and maximum length, this improves security because before it had always a fixed length
 * 28 jan 2020: lib_string: filterXSS option allowall toegoevoegd
 * 13 mei 2020: lib_string: getYoutubeID() added
 * 13 mei 2020: lib_string: getYoutubeID() nocookie domain toegevoegd
 * 28 mei 2020: lib_string: plainText2HTML() kan nu een punt, komma vraagteken etc hebben na een url
 * 28 mei 2020: lib_string: cleanHTMLMarkup() toegevoegd
 * 14 dec 2020: lib_string: isValidEmail() geupdate op basis van filter_var
 * 24 jun 2021: lib_string: filterBadCharsBlackList(), filterBadCharsWhiteList. regex chars worden nu ge-escaped
 * 25 jun 2021: lib_string: generatePassword() heeft meer special characters en gebruikt random_int() ipv rand() voor betere randomization
 * 20 aug 2021: lib_string: added lastChar(), firstChar() and generateEmailID();
 * 11 sept 2021: lib_string: generateEmailID renamed-> getFingerprintEmail()
 * 11 sept 2021: lib_string: getFingerprintEmail() is case insensitive now
 * 14 sept 2021: lib_string: isEmailValid(), checkEmail(), getBrowserFingerprint() moved from lib_string to lib_inet
 * 28 sept 2021: lib_string: generateRandomString() added
 * 28 sept 2021: lib_string: generateRandomString() is now faster and has parameter for characters to generate
 * 2 okt 2021: lib_string: filterXSS() updated, filterJavascriptInjection() alias added
 * 2 okt 2021: lib_string: filterXSS() updated, <a> tag added in html allowed
 * 2 okt 2021: lib_string: filterXSS(): GIGA SECURITY FLAW BUGFIX!!!! the function didn't filter at all!!
 * 3 nov 2021: lib_string: uniqidReal(): added
 * 3 nov 2021: lib_string: isUniqueidRealValid(): added
 * 3 nov 2021: lib_string: makeUnique gebruikt nu uniqueidReal()
 * 3 nov 2021: lib_string: makeUnique gebruikt nu uniqueidReal()
  * 22 nov 2021: lib_string: uniqidReal(): is more unique, because it doesn't generate hex anymore
 * 22 nov 2021: lib_string: isUniqueidRealValid(): changed to fit uniqidReal()
 * 12 dec 2021: lib_string: endswith(): speedup because strlen is cached
 * 18 mrt 2021: lib_string: replaceAccent(): added
 * 18 mrt 2021: lib_string: filterURL(): added
 * 18 mrt 2021: lib_string: filterURL(): now support for strict url detection
 * 19 mrt 2021: lib_string: filterURL(): changes params, separate tld and protocol detect
 * 18 jan 2023: lib_string: filterBadCharsWhiteList(): support for dots (.) and other special regular expression characters
 * 19 jan 2023: lib_string: filterBadCharsWhiteList(): long standing bug fixed with double escaped regex. Added extra parameter for whitelist in regex
 * @author Dennis Renirie
 */


//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_date.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_img.php'); 
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_inet.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_math.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_string.php');


/**
 * filter string (or array) for SQL injection
 *
 * string of array met strings (onbeperkt diep, dus array in array in array is mogelijk) waarin de waarde van een invulveld kan komen van een formulier
 *  Je kunt in 1 keer een array invoeren (bijvoorbeeld de $_GET of $_POST array) om te filteren op foute input
 * 
 * Er wordt gecheckt op array dimensies, omdat dit ook in injections gebruikt wordt, daarom moet je expliciet aangeven dat je een array wil toestaan als parameter
 *
 * @param mixed $mInput (array or string)
 * @param int $iArrayDimensionLevel (the level of dimensions allowed for array of $mInput), 0 means no array allowed wich is default (you have to explicitly allow array dimensions)
 * @return mixed (array or string)
 */
function filterSQLInjection($mInput, $iArrayDimensionLevel = 0)
{
    if (!is_numeric($iArrayDimensionLevel))
        $iArrayDimensionLevel = 0;

    
    if (($iArrayDimensionLevel > 0) && (is_array($mInput)))
    {
        $iArrayDimensionLevel--;
        foreach (array_keys($mInput) as $sKey)
            $mInput[$sKey] = filterSQLInjection($mInput[$sKey], $iArrayDimensionLevel);
    } 
    else 
    {
        $mInput = str_replace('\\', '/', $mInput); //  de backslash wordt vervangen door forward slash
        $mInput = str_replace('"', '', $mInput);        
        $mInput = str_replace("'", '', $mInput);
        $mInput = str_replace(';', '.', $mInput);           
        $mInput = str_replace('--', '', $mInput);
        $mInput = str_replace('+', '', $mInput);
        $mInput = str_replace('/*', '', $mInput);
        $mInput = str_replace('*/', '', $mInput);
        $mInput = str_replace('%', '', $mInput);
        if ( (stripos($mInput, 'count(') != false) || (stripos($mInput, 'count (') != false) ) //count even uitsluiten
        {
            $mInput = str_replace('(', '', $mInput);
            $mInput = str_replace(')', '', $mInput);
        }
        if ( (stripos($mInput, 'http://') != false) || (stripos($mInput, 'style:') != false) ) //dubbele punt uitzonderingen even uitsluiten
        {
            $mInput = str_replace(': ', ':', $mInput); //met dubbele punt + variabale is placeholder in sql, die willen we ook niet in de string hebben
            $mInput = str_replace(':', ': ', $mInput); //om te voorkomen dat alle dubbele punten bij iedere filtercycle worden vervangen door dubbelepunt-spatie wordt eerst dubbelepunt-spatie vervangen door een dubbele punt
        }


        //their html equivalent
        $mInput = str_replace(htmlentities('"'), '', $mInput);
        $mInput = str_replace(htmlentities("'"), '', $mInput);
        $mInput = str_replace(htmlentities(';'), '', $mInput);
        $mInput = str_replace(htmlentities('--'), '', $mInput);
        $mInput = str_replace(htmlentities('+'), '', $mInput);
        $mInput = str_replace(htmlentities('/*'), '', $mInput);
        $mInput = str_replace(htmlentities('*/'), '', $mInput);
        $mInput = str_replace(htmlentities('%'), '', $mInput);
        if ( (stripos($mInput, htmlentities('count(')) != false) || (stripos($mInput, htmlentities('count (')) != false) ) //count even uitsluiten
        {        
            $mInput = str_replace(htmlentities('('), '', $mInput);
            $mInput = str_replace(htmlentities(')'), '', $mInput);
        }
        if ( (stripos($mInput, htmlentities('http://')) != false) || (stripos($mInput, htmlentities('style:')) != false) ) //dubbele punt uitzonderingen even uitsluiten
        {
            $mInput = str_replace(htmlentities(': '), htmlentities(':'), $mInput); //met dubbele punt + variabale is placeholder in sql, die willen we ook niet in de string hebben
            $mInput = str_replace(htmlentities(':'), htmlentities(': '), $mInput); //om te voorkomen dat alle dubbele punten bij iedere filtercycle worden vervangen door dubbelepunt-spatie wordt eerst dubbelepunt-spatie vervangen door een dubbele punt
        }
        
        
        //their url-encoded equivalent
        $mInput = str_replace(urlencode('"'), '', $mInput);
        $mInput = str_replace(urlencode("'"), '', $mInput);
        $mInput = str_replace(urlencode(';'), '', $mInput);
        $mInput = str_replace(urlencode('--'), '', $mInput);
        $mInput = str_replace(urlencode('+'), '', $mInput);
        $mInput = str_replace(urlencode('/*'), '', $mInput);
        $mInput = str_replace(urlencode('*/'), '', $mInput);
        $mInput = str_replace(urlencode('%'), '', $mInput);
        if ( (stripos($mInput, urlencode('count(')) != false) || (stripos($mInput, urlencode('count (')) != false) ) //count even uitsluiten
        {        
            $mInput = str_replace(urlencode('('), '', $mInput);
            $mInput = str_replace(urlencode(')'), '', $mInput);
        }
        if ( (stripos($mInput, urlencode('http://')) != false) || (stripos($mInput, urlencode('style:')) != false) ) //dubbele punt uitzonderingen even uitsluiten
        {
            $mInput = str_replace(urlencode(': '), urlencode(':'), $mInput); //met dubbele punt + variabale is placeholder in sql, die willen we ook niet in de string hebben
            $mInput = str_replace(urlencode(':'), urlencode(': '), $mInput); //om te voorkomen dat alle dubbele punten bij iedere filtercycle worden vervangen door dubbelepunt-spatie wordt eerst dubbelepunt-spatie vervangen door een dubbele punt
        }
        
        
        //their utf8 equivalent
        //we gebruiken urt8_decode omdat deze pagina al utf8 gecodeerd is
        $mInput = str_replace(utf8_decode('"'), '', $mInput); 
        $mInput = str_replace(utf8_decode("'"), '', $mInput);
        $mInput = str_replace(utf8_decode(';'), '', $mInput);
        $mInput = str_replace(utf8_decode('--'), '', $mInput);
        $mInput = str_replace(utf8_decode('+'), '', $mInput);
        $mInput = str_replace(utf8_decode('/*'), '', $mInput);
        $mInput = str_replace(utf8_decode('*/'), '', $mInput);
        $mInput = str_replace(utf8_decode('%'), '', $mInput);
        if ( (stripos($mInput, utf8_decode('count(')) != false) || (stripos($mInput, utf8_decode('count (')) != false) ) //count even uitsluiten
        {        
            $mInput = str_replace(utf8_decode('('), '', $mInput);
            $mInput = str_replace(utf8_decode(')'), '', $mInput);
        }
        if ( (stripos($mInput, utf8_decode('http://')) != false) || (stripos($mInput, utf8_decode('style:')) != false) ) //dubbele punt uitzonderingen even uitsluiten
        {
            $mInput = str_replace(utf8_decode(': '), utf8_decode(':'), $mInput); //met dubbele punt + variabale is placeholder in sql, die willen we ook niet in de string hebben
            $mInput = str_replace(utf8_decode(':'), utf8_decode(': '), $mInput); //om te voorkomen dat alle dubbele punten bij iedere filtercycle worden vervangen door dubbelepunt-spatie wordt eerst dubbelepunt-spatie vervangen door een dubbele punt
        }
       
        
        //the left-overs we dont want to allow
        $mInput = str_ireplace('SUBSTRING', '', $mInput); //case insensitive replace
        $mInput = str_ireplace('LOAD_FILE', '', $mInput); //case insensitive replace
        
        //all string manipulation functions are a potential hazard. But we cannot not filter all of them because they can be valid input
    }

    return $mInput;
}

/**
 * filter user input for XSS/javascript injection in <INPUT TYPE="text"> fields
 * 
 * @param mixed $mInput string or array
 * @param bool $bAllowHTML
 */
DEFINE('FILTERXSS_ALLOW_HTML_NONE', 0);//NO HTML = default, to prevent cross site scripting
DEFINE('FILTERXSS_ALLOW_HTML_MARKUP', 1);//<b><i><u> tags
DEFINE('FILTERXSS_ALLOW_HTML_FILTEREDXSS', 2);//gefilterd
DEFINE('FILTERXSS_ALLOW_ALL', 3);//not filtered at all
function filterXSS($mInput, $iAllowHTML = FILTERXSS_ALLOW_HTML_NONE)
{
        if ($iAllowHTML == FILTERXSS_ALLOW_ALL)
            return $mInput;


        if (is_array($mInput))
        {
                foreach ($mInput as $mInputItem)
                {
                    filterXSS($mInputItem);
                }
        }
        else
        {
            //only allow characters on the whitelist (no special system characters)
            //$sInput = preg_replace( '/[^_\-0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZéëèęėēúüûùūíïìióöôòáäâà., <>=\\/@]/', '', $sInput );		                
            $mInput = preg_replace( '/[^'.REGEX_TEXT_NORMAL.']/', '', $mInput );

            //start filtering xss
            switch ($iAllowHTML)
            {
                    case FILTERXSS_ALLOW_HTML_NONE:		
                            $mInput = strip_tags($mInput);	
                            $mInput = str_ireplace(utf8_decode('<'), '', $mInput); //utf equivalent
                            $mInput = str_ireplace(utf8_encode('<'), '', $mInput); //utf equivalent
                            $mInput = str_ireplace(htmlentities('<'), '', $mInput); //html equivalent
                            $mInput = str_ireplace(urlencode('<'), '', $mInput); //url equivalent					
                            $mInput = str_ireplace(utf8_decode('>'), '', $mInput); //utf equivalent
                            $mInput = str_ireplace(utf8_encode('>'), '', $mInput); //utf equivalent
                            $mInput = str_ireplace(htmlentities('>'), '', $mInput); //html equivalent
                            $mInput = str_ireplace(urlencode('>'), '', $mInput); //url equivalent					
                            return $mInput;
                            break;
                    case FILTERXSS_ALLOW_HTML_MARKUP:
                            //@todo unsafe. still able to do onfocus, onclicke etc
                            //@todo create htmlpurifyer that interprets dom and removes all attributes
                            return strip_tags($mInput, '<br><br/><p><b><i><u><font>'); //whitelist of allowed html tags		
                            break;
                    case FILTERXSS_ALLOW_HTML_FILTEREDXSS:			
                            //@todo create htmlpurifyer that interprets dom and removes all attributes (except some predefined ones)
                            $mInput = strip_tags($mInput, '<br><br/><p><b><i><u><font><a><img><table><tr><thead><td><th><div><h1><h2><h3><h4><h5><h6><ol><ul><li>'); //whitelist of allowed html tags

                            //do extra filters on
                            $arrBannedWords = array('javascript', 'vbscript',
                                    'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut',
                                    'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate',
                                    'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut',
                                    'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend',
                                    'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange',
                                    'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete',
                                    'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover',
                                    'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange',
                                    'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted',
                                    'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'
                            );

                            foreach ($arrBannedWords as $sBannedWord)
                            {
                                    $mInput = str_ireplace($sBannedWord, '', $mInput); //case insensitive replace
                                    $mInput = str_ireplace(utf8_encode($sBannedWord), '', $mInput); //utf equivalent
                                    $mInput = str_ireplace(utf8_decode($sBannedWord), '', $mInput); //utf equivalent
                                    $mInput = str_ireplace(htmlentities($sBannedWord), '', $mInput); //html equivalent
                                    $mInput = str_ireplace(urlencode($sBannedWord), '', $mInput); //url equivalent
                            }

                            return $mInput;	
                    default:
                        //FILTERXSS_ALLOW_ALL
                        break;
                            
            }//switch	
    }//is_array


	return $mInput;
}

/**
 * alias for filterXSS()
 * filter user input for XSS/javascript injection
 * 
 * @param mixed $mInput string or array
 * @param bool $bAllowHTML
 */
function filterJavascriptInjection($mInput, $iAllowHTML = FILTERXSS_ALLOW_HTML_NONE)
{
    return filterXSS($mInput, $iAllowHTML);
}

/**
 * filter 'bad' characters from a string (for example for preventing SQL injection)
 * preg_replace is faster!!! so if you can use preg_replace, USE IT!!! 
 *
 * @param string $sValue
 * @param string $sWhitelist a regex whitelist of all good characters (use escapeRegex() to escape regular expression) 
 * @param string $bWhiteListIsRegex whitelist parameter is regular expression? true = regular expression, false = literal string
 * @return string
 */
function filterBadCharsWhiteList($sValue, $sWhitelist = null, $bWhiteListIsRegex = false) 
{    
	if ($sWhitelist == null) //als geen whitelist/array meegegeven dan zelf een samenstellen
    {
		$sWhitelist = REGEX_TEXT_NORMAL;//'0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ., éëèęėēúüûùūíïìióöôòáäâà';
        $bWhiteListIsRegex = true;
    }

    //if $sWhitelist is NOT a regular expression we need to escape it
    if (!$bWhiteListIsRegex)
        $sWhitelist = preg_quote($sWhitelist); //only user input is escaped, the predefined CONSTs should be safe

// logDebug('filterBadCharsWhiteList', $sWhitelist.' -> '.$sValue);

	return preg_replace( '/[^'.$sWhitelist.']/', '', $sValue );
}

/**
 * 10x slower version of filterBadCharsWhiteList, but it is less vulnerable to special characters like '\'
 */
// function filterBadCharsWhiteListSlow($sValue, $sWhitelist = null)
// {
//     if ($sWhitelist == null) //als geen whitelist/array meegegeven dan zelf een samenstellen
//         $sWhitelist = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ., éëèęėēúüûùūíïìióöôòáäâà';

//     $sResult = '';
//     $sCurrChar = '';
//     $iStrLen = strlen($sValue);
//     for ($iTeller = 0; $iTeller < $iStrLen; $iTeller ++)
//     {
//         $sCurrChar = substr($sValue, $iTeller, 1);
    
//         if ((strpos($sWhitelist, $sCurrChar) !== false)) //het moet letterlijk FALSE zijn, niet 0 (want dat kan ook het eerste karakter zijn)
//                 $sResult.= $sCurrChar;
//     }

// 	return $sResult;
// }




/**
 * filter 'bad' characters from a string (for example for preventing SQL injection)
 * using a blacklist of bad characters
 *
 * @param string $sValue
 * @param string $sWhitelist
 * @return string filtered string
 */
function filterBadCharsBlackList($sValue, $sBlacklist)
{
	return preg_replace('/['.$sBlacklist.']/','',$sValue); 
}

/**
 * filter and replace a url in a string
 *
 * @param string $sInput
 * @param string $sReplaceText
 * @param bool $bProtocolDetect (http/ftp etc) if true: looks for protocols in the text
 * @param bool $bTLDDetect (top level domain) if true: also detects end of sentences without space as url like: "Thank you.This was helpful"
 * @return string
 */
function filterURL($sInput, $sReplaceText = '[url removed]', $bProtocolDetect = true, $bTLDDetect = true)
{
    $arrExplode = array();
    $iCount = 0;
    $sOutput = $sInput;

    //method 1: filter on protocol and beginning of www and http
    if ($bProtocolDetect)
    {
        $arrExplode = explode(' ', $sInput);
        $iCount = count($arrExplode);
        for ($iIndex = 0; $iIndex < $iCount; ++$iIndex)
        {
            if (is_numeric(stripos($arrExplode[$iIndex], '://'))) //protocol divider like in: https://
            {
                $arrExplode[$iIndex] = $sReplaceText;
            }
            else
            {
                if (startswith($arrExplode[$iIndex], 'http')) //also includes https
                    $arrExplode[$iIndex] = $sReplaceText;
                if (startswith($arrExplode[$iIndex], 'ftp'))
                    $arrExplode[$iIndex] = $sReplaceText;
                if (startswith($arrExplode[$iIndex], 'www.'))
                    $arrExplode[$iIndex] = $sReplaceText;
            }
        }
        $sOutput = implode(' ', $arrExplode);
    }

    //method 2: filter on TLDs like .com and .nl
    if ($bTLDDetect)
    {
        //$patternexample = '/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/';     
        $sOutput = preg_replace( '/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(\/\S*)?/', $sReplaceText, $sOutput);
    }

    return $sOutput;
}

/**
 * convert a character with accent to an alphabetical equivalent
 *
 * @param string $str
 * @return string
 */
function replaceAccent($str)
{
  $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ');
  $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'ss', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o');
  return str_replace($a, $b, $str);
} 


/**
 * inverted function of php function nl2br()
 *
 * @param string $sValue
 * @return string
 */
function br2nl($sValue)
{
        return preg_replace( '!<br.*>!iU', "\n", $sValue );

}

/**
 * generate password
 * 
 * if attackers know how the generatePassword() works via a random generation routing, 
 * they might be able to crack the system.
 * So for non-password related things, please use generateRandomString(), it is less resource intensive
 * 
 * the password function avoids 0 and O, 1 and L etc
 * generateRandomString() doesn't
 * 
 *
 * @param int $iMinLength how many characters should password be?
 * @param boolean $iMaxLength can the password have a variable length
 * @return string password
 */
function generatePassword($iMinLength = 8, $iMaxLength = 8)
{
        $sResult = '';
        $iCharChoice = 0;
        $iCharIndex = 0;
        $sSpecialChars = '!@#$%^&*(){}?<>:;_+=.,'; //no special UTF or SQL characters to prevent sql injection such as '"%;=()

        if ($iMaxLength < $iMinLength) //prevent bug when the programmer switched min and max values by accident
            $iMaxLength = $iMinLength;
        $iLength = random_int($iMinLength, $iMaxLength);
        
        for ($iTeller = 0; $iTeller < $iLength; $iTeller++)
        {
            $iType = random_int(0,2); //0 and 1 are characters, 2 is special character (so 1 in 3 is an special char)
            
            //character
            if (($iType == 0) || ($iType == 1))
            {
                $iCharChoice = random_int(0, 6);

                switch ($iCharChoice)
                {
                        case 0 :  //lowercase letters a t/m k (l avoid)
                                $iChar = random_int(97, 107);
                                break;
                        case 1 : //lowercase letters m t/m n (o avoid)
                                $iChar = random_int(109, 110);
                                break;
                        case 2 : //lowercase letters p t/m z
                                $iChar = random_int(112, 122);
                                break;
                        case 3 : //uppercase A t/m H (I avoid)
                                $iChar = random_int(65, 72);
                                break;
                        case 4 :  //uppercase J t/m N (O avoid)
                                $iChar = random_int(74, 78);
                                break;
                        case 5 :  //uppercase P t/m Z
                                $iChar = random_int(80, 90);
                                break;
                        case 6 :  //digits 2 t/m 9 (0 en 1 avoid)
                                $iChar = random_int(50, 57);
                                break;
                }

                $sResult.= chr($iChar);
            }
            
            //character
            if ($iType == 2)
            {
                $iCharIndex = random_int(0, strlen($sSpecialChars)-1);
                $sResult.= $sSpecialChars[$iCharIndex];
            }             
        }
        
        if (random_int(0, 4) == 0)//hash or not? 1 in 5 chance
            $sResult = md5($sResult);
        
        return substr($sResult, 0, $iLength);
}

/**
 * generate a random string.
 * This function exist to alleviate to password function.
 * DON'T USE THIS FUNCTION TO GENERATE PASSWORDS! use generatePassword() instead
 * 
 * if attackers know how the generatePassword() works via a random generation routing, 
 * they might be able to crack the system.
 * So for non-password related things, please use generateRandomString()
 * 
 * the password function avoids 0 and O, 1 and L etc
 * generateRandomString() doesn't
 *
 * @param int $iMinLength how many characters should password be?
 * @param boolean $iMaxLength can the password have a variable length
 * @param string $sPossibleChars which characters can be generated?
 * @return string random string
 */
function generateRandomString($iMinLength = 8, $iMaxLength = 8, $sPossibleChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*(){}?<>:;_+=.,^ ')
{
    $sResult = '';
    $iLengthRndStr = '';
    $iIndexPossibleChars = 0;

    if ($iMaxLength < $iMinLength) //prevent bug when the programmer switched min and max values by accident
        $iMaxLength = $iMinLength;
    $iLengthRndStr = random_int($iMinLength, $iMaxLength);
    $iMaxIndexPossibleChars = strlen($sPossibleChars)-1;
    
    for ($iTeller = 0; $iTeller < $iLengthRndStr; $iTeller++)
    {
        $iIndexPossibleChars = random_int(0, $iMaxIndexPossibleChars); //which index?
        $sResult.=$sPossibleChars[$iIndexPossibleChars];
    }
            
    return $sResult;
}

/**
* generates a hash for passwords
* example: password_hash('mybeautifullpassowrd, PASSWORD_DEFAULT);
* 
* as of php 5.5 there are better hashing possibilities unused by previous versions
* 
* @return string max 255 characters
* 
* REMOVE THIS FUNCTION WHEN USING PHP 5.5 !!!
*
* This function will generate an error on php 5.5 because it exists. 
* This is done so you will know that password encryptions are changed and logins may not work!!
*/
//define('PASSWORD_DEFAULT', 'sha512'); //remove constant when on php 5.5
//function password_hash($password, $algo) //remove function when on php 5.5
//{
//	return hash($algo, $password);
//}	

/**
 * checks a password if it matches the hash
 * as of php 5.5 there are better hashing possibilities unused by previous versions
 *
 * @return boolean 
 *
 * REMOVE THIS FUNCTION WHEN USING PHP 5.5 !!!
 * 
 * This function will generate an error on php 5.5 because it exists. 
* This is done so you will know that password encryptions are changed and logins may not work!!
 */
//function password_verify ($password , $hash) //remove function when on php 5.5
//{
//	return password_hash($password, PASSWORD_DEFAULT) === $hash;
//}



/**
 * remove the last chacter form a string
 *
 * @param string $sString
 * @return string
 */
function removeLastChar($sString)
{
        $sResult = substr_replace($sString,'',-1);
        return($sResult);
}

/**
 * Remove first characters from string
 * ltrim() uses a character pattern, this function uses a literal string
 * 
 * when you want to remove "poep" ($sStartsWith) from "poephoofd" ($sHaystack), 
 * the result will be "hoofd"
 * 
 */
function ltrimLiteral($sHaystack, $sNeedleLiteral)
{
    /*
    $iLenNeedle = 0;
    $sTemp = '';

    $iLenNeedle = strlen($sNeedleLiteral);

    //we do blindly a substring without knowing if the first part actually exists
    $sTemp = substr($sHaystack, $iLenNeedle);
    
    //check if first part matches up with the result from substring
    if ($sNeedleLiteral.$sTemp == $sHaystack)
        return $sTemp;
    else
        return '';
        */
    
    //from performance tests I can see that exploding works
    //quicker than the code above

    $arrExp = array();
    $arrExp = explode($sNeedleLiteral, $sHaystack);
    
    //if exists, we have 2 elements in array, if not we have 1
    if (count($arrExp) == 2)
        return $arrExp[1];
    else
        return '';
}

/**
 * returns the last character of a string
 */
function lastChar($sString)
{
    return substr($sString, -1);
}

/**
 * returns the last character of a string
 * 
 * please use $sString[0] instead because it is much faster.
 * But becuase lastChar() exists, for consistency firstChar() also exists
 */
function firstChar($sString)
{
    return $sString[0];
}

/**
 * afkappen van een tekst na iMaxCharacters. Hij zoekt echter naar spaties, om de tekst af te kappen
 * bijvoorbeeld bij het weergegeven van nieuwsberichten op de voorkant van de site wil je de tekst
 * afkappen en zetten : lees meer ...
 *
 * @param string $sString volledige tekst
 * @param int $iMaxCharacters
 * @param int $iSpatieCharacters hoeveel karakters voor het einde van de string moet ie gaan zoeken naar spaties
 * @return string
 */
function chopText($sString, $iMaxCharacters, $iSpatieCharacters)
{
        $bChanged = false;
        $sResult = $sString;

        $iStartPos = $iMaxCharacters - $iSpatieCharacters;


        //zoeken naar spaties binnen een bepaald bereik
        $iStrLen = strlen($sResult);
        while (($iStartPos > 0) && ($iStartPos <= $iStrLen))
        {
                $iStartPos++;
                if ((substr($sString, $iStartPos, 1)) == " " || (substr($sString, $iStartPos, 1) == "-") || (substr($sString, $iStartPos, 1) == "_"))
                {
                        $sResult = substr($sResult, 0, $iStartPos);
                        $bChanged = true;
                }
        }

        //als niets veranderd, dan toch afkappen
        if ($bChanged == false)
                $sResult = substr($sResult, 0, $iMaxCharacters);

        //puntjes zetten als afgekapt
        if (strlen($sResult) < strlen($sString))
                $sResult = $sResult." ...";

        return($sResult);
}

/**
 * strippen van 1 specifieke html tag (str_tags van php stript ALLE tags)
 *
 * @param string $sText
 * @param string $sHTMLTag
 * @param string $sHTMLEndTag
 * @return string
 */
function stripTag($sText, $sHTMLTag, $sHTMLEndTag)
{
        $sReturn = $sText;

        $sReturn = str_replace(strtolower($sHTMLTag) , "", $sReturn);
        $sReturn = str_replace(strtoupper($sHTMLTag) , "", $sReturn);
        $sReturn = str_replace(strtolower($sHTMLEndTag) , "", $sReturn);
        $sReturn = str_replace(strtoupper($sHTMLEndTag) , "", $sReturn);

        return $sReturn;
}

/**
 * replace the <p> tag with <br> tags
 *
 * @param <type> $sText
 * @return <type>
 */
function replacePTag($sText)
{
        $sReturn = $sText;
        $sReturn = str_replace("<P>", "", $sReturn);
        $sReturn = str_replace("<p>", "", $sReturn);
        $sReturn = str_replace("</P>" , "<br><br>", $sReturn);
        $sReturn = str_replace("</p>" , "<br><br>", $sReturn);

        return $sReturn;
}

/**
 * convert an array of strings to one single string with newline-characters
 *
 * @param string $sSeparator waarmee zijn de strings gescheiden ?
 * @param array $arrStrings array met strings
 */
function arrToStr($arrStrings, $sSeparator = PHP_EOL)
{
    //var_dump($arrStrings)
    return implode($sSeparator, $arrStrings);
}

/**
 * convert a string to an array searching for newline-characters
 *
 * @param string $sSeparator waarmee zijn de strings gescheiden ?
 * @param <type> $sString
 * @return <type>
 */
function strToArr($sString, $sSeparator = PHP_EOL)
{
    return explode($sSeparator, $sString);
}

/**
 * checks if a dutch bank account is valid middels de 11 proef (11proef)
 * Postbank rekeningnummers kunnen 2 to 7 cijfers lang zijn. hier wordt/kan niet op getest worden
 * postbank rekeningnummers worden herkend aan de hand van de lengte
 * De functie geeft TRUE als het een Postbanknummer zou kunnen zijn, of als het een correct bankrekeningnummer is.
 *
 * @param string $sBankAccountNumber
 * @return bool true als OK of postbank, false als niet klopt
 */
// function proef11($sBankAccountNumber)
// {
//   $csom = 0;                            // variabele initialiseren
//   $pos = 9;                             // het aantal posities waaruit een bankrekeningnr hoort te bestaan
  
//   for ($i = 0; $i < strlen($sBankAccountNumber); $i++){
//     $num = substr($sBankAccountNumber,$i,1);       // bekijk elk karakter van de ingevoerde string
//     if ( is_numeric( $num )){           // controleer of het karakter numeriek is
//       $csom += $num * $pos;                        // bereken somproduct van het cijfer en diens positie
//       $pos--;                           // naar de volgende positie
//     }
//   }
//   $postb = ($pos > 1) && ($pos < 7);    // True als resterende posities tussen 1 en 7 => Postbank
//   $mod = $csom % 11;                                        // bereken restwaarde van somproduct/11.
//   return( $postb || !($pos || $mod) );  // True als het een postbanknr is of restwaarde=0 zonder resterende posities
// }


/**
 * tests if a string(haystack) ends with needle
 *
 * @param mixed haystack: Object to look in
 * @param string needle: String to look for
 * @return: if found, true, otherwise false
 */
function endswith($haystack, $needle)
{


    if (is_array($haystack))
    {
        foreach($haystack as $hay)
        {
            if(substr($hay, strlen($hay)-strlen($needle), strlen($hay)) == $needle) {
                return true;
            }
        }
        //return in_array($needle, $haystack);
        return false;
    }
    else
    {
        // return (substr($haystack, strlen($haystack)-strlen($needle), strlen($haystack)) == $needle);
        $iStrLenHaystack = 0;
        $iStrLenHaystack = strlen($haystack);
        return (substr($haystack, $iStrLenHaystack-strlen($needle), $iStrLenHaystack) == $needle);
    }
}

/**
 * tests if a string(haystack) starts with needle
 *
 * @param mixed haystack: Object to look in
 * @param string needle: String to look for
 * @return: if found, true, otherwise false
 */
function startswith($haystack, $needle)
{

    if (is_array($haystack)) {
        foreach($haystack as $hay) {
            if(substr($hay, 0, strlen($needle)) == $needle) {
                return true;
            }
        }
        //return in_array($needle, $haystack);
        return false;
    }
    else {
        return (substr($haystack, 0, strlen($needle)) == $needle);
    }
}

/**
 * same as trim(), except trim does only the beginning and the back of the string
 * this function does the whole string.
 * it removes all the control characters from the string
 * NOTE: this function does NOT trim whitespaces (spaties)
 *
 * @param string $sString
 * @return string
 */
function trimAll($sString)
{
    $arrBannedChars = array("\r" => '',
                  "\n" => '',
                  "\t" => '',
                  '\0'  => '',
                  '\x0B'  => '',                    
    );

    return strtr($sString, $arrBannedChars);
}

/**
 * detects if a string is utf8
 *
 * @return boolean, false if not UTF, 'UTF-8' if string is utf8
 */
function isUTF8String($sString)
{
	$sCharset =  mb_detect_encoding($sString);
	return ($sCharset == CHARSET_UTF8);
}

/**
 * function for replacing characters that are control characters in regular expression.
 * A whitelist function can use this so you can easily supply provide a list of allowed characters
 * but be carefull the string replacers a very resource expensive.
 * 
 * Provide a string of allowed characters and this function will produce their regex equivalent
 * 
 *  escapeRegex('abcdefg[]') will output: 'abcdefg\[\] because the brackets have a special funcion in regular expressions
 * 
 * USE preq_quote instead its faster!
 * 
 * @param unknown $sInput
 */
function escapeRegex($sAllowedChars)
{	
//old code as of 15 july 2015
// 	$sAllowedChars = str_replace('\\', '\\\\', $sAllowedChars);//belangrijk dat deze als eerste gaat	
	
// 	$sAllowedChars = str_replace('[', '\\[', $sAllowedChars);
// 	$sAllowedChars = str_replace(']', '\\]', $sAllowedChars);
	
// 	$sAllowedChars = str_replace('(', '\\(', $sAllowedChars);
// 	$sAllowedChars = str_replace(')', '\\)', $sAllowedChars);

// 	$sAllowedChars = str_replace('{', '\\{', $sAllowedChars);
// 	$sAllowedChars = str_replace('}', '\\}', $sAllowedChars);
	
// 	$sAllowedChars = str_replace('.', '\\.', $sAllowedChars);
// 	$sAllowedChars = str_replace('?', '\\?', $sAllowedChars);
// 	$sAllowedChars = str_replace('*', '\\*', $sAllowedChars);
// 	//$sAllowedChars = str_replace('^', '\\^', $sAllowedChars);
// 	$sAllowedChars = str_replace('$', '\\$', $sAllowedChars);
// 	$sAllowedChars = str_replace('+', '\\+', $sAllowedChars);
// 	$sAllowedChars = str_replace('-', '\\-', $sAllowedChars);
// 	$sAllowedChars = str_replace('|', '\|', $sAllowedChars);
	
// 	return $sAllowedChars;
	return preg_quote($sAllowedChars);
}

/**
 * makes a string 'unique' by adding the delimiter and uniqueid
 * when the delimiter exists it replaces the last part of the string with a new unique 
 *
 * this function differs somewhat from uniqueid with prefix, because this function will replace 
 * the last part of the string by a new uniqueid
 * 
 * @param string $sString
 * @param string $sDelimiter
 */
function makeUnique($sString, $sDelimiter = '_', $bMoreEntropy = false)
{
	$arrString = explode($sDelimiter, $sString);
	
	return $arrString[0].$sDelimiter.uniqidReal(26);	
}

/**
 * extended version of php's filter_var
 * 
 * the filter_var function has some limitations
 * we wanted to extend the function and add our own (stricter) filters
 * 

 * @param unknown $sVar
 * @param unknown $sSanitationFilter
 * @return string (or null if filter not found)
 */
function filter_varext($sVar, $sSanitationFilter)
{
	$sRegEx = '';
	
	switch ($sSanitationFilter)
	{
		case FILTEREXT_SANITIZE_CLASS:
			$sRegEx = REGEX_ALPHANUMERIC_UNDERSCORE.'\\\\'; //backslash is present in namespaced classes
			break; 
		case FILTEREXT_SANITIZE_FUNCTION:
			$sRegEx = REGEX_ALPHANUMERIC_UNDERSCORE_MINUS;
			break;			
		case FILTEREXT_SANITIZE_FILE:
			$sRegEx = REGEX_ALPHANUMERIC_UNDERSCORE_MINUS.preg_quote('.');
			break;
		case FILTEREXT_SANITIZE_DIRECTORY:
			$sRegEx = REGEX_ALPHANUMERIC_UNDERSCORE_MINUS;
			break;
		case FILTEREXT_SANITIZE_URL_DIRECTORY:
			$sRegEx = REGEX_ALPHANUMERIC_UNDERSCORE_MINUS;
			break;
		case FILTEREXT_SANITIZE_URL_FILE:
			$sRegEx = REGEX_ALPHANUMERIC_UNDERSCORE_MINUS.preg_quote('.');
			break;					
		case FILTEREXT_SANITIZE_URL_STRICT:
			$sRegEx = REGEX_ALPHANUMERIC_UNDERSCORE_MINUS.preg_quote(':/.');
			break;					
		default:
			error('filter not found');
			return null;
	}

	//return preg_replace( '/[^'.$sRegEx.']/', '',  $sVar);
	return $sVar;
}

/**
 * convert plain text into html:
 * - it replaces line ends with <br>
 * - makes <a href=""> out of links
 * - html entities like "<" becomes "&gt;
 * 
 * @param string $sInput
 * @param bool $bRelNoFollow append rel="nofollow" to all links
 * @param bool $bTargetBlank append target="_blank" to all links
 * @return string (or null if filter not found)
 */
function plainText2HTML($sInput, $bTargetBlank = false, $bRelNoFollow = true)
{
    
//    $sInput = htmlentities($sInput); --> later because otherwise the links will be mutulated
    $sInput = nl2br($sInput);

    //make <a href=""> out of links
    $arrLines = array();
    $arrWords = array();
    $iWordCount = 0;
    $iLineCount = 0;
    $sOutput = '';
    
        //first: every line
    $arrLines = explode("<br />", $sInput); //<br /> possible if preceded by nl2br()

    $iLineCount = count($arrLines);
    for ($iLineIndex = 0; $iLineIndex < $iLineCount; $iLineIndex++)
    {
        $arrWords = explode(' ', $arrLines[$iLineIndex]);
    
            //second: every word on every line
        $iWordCount = count($arrWords);
        $sOrgWord = '';
        $sCleanWord = ''; //some manipulations like stripping the dot
        $sReplacedWord = ''; //the https shizzle
        for ($iWordIndex = 0; $iWordIndex < $iWordCount; $iWordIndex++)
        {

            if (startswith(ltrim($arrWords[$iWordIndex]), 'http')) //it can also be https so no ://
            {
                $sOrgWord = $arrWords[$iWordIndex];
                $sCleanWord = $arrWords[$iWordIndex];
                $sReplacedWord = '';

                if (endswith($sOrgWord, '.')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                if (endswith($sOrgWord, '!')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                if (endswith($sOrgWord, '?')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                if (endswith($sOrgWord, ';')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                if (endswith($sOrgWord, ':')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                if (endswith($sOrgWord, ',')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                if (endswith($sOrgWord, '\\')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                if (endswith($sOrgWord, '/')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                if (endswith($sOrgWord, '"')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                if (endswith($sOrgWord, '\'')) //can be end of sentence, then we don't want to include the dot
                    $sCleanWord = substr($sOrgWord, 0, strlen($sOrgWord) - 1);
                        
                $sReplacedWord = '<a href="'.ltrim($sCleanWord).'"';
                if ($bRelNoFollow)
                    $sReplacedWord = $sReplacedWord.' rel="nofollow"';
                if ($bTargetBlank)
                    $sReplacedWord = $sReplacedWord.' target="_blank"';

                $sReplacedWord = $sReplacedWord.'>'.htmlentities($sCleanWord).'</a>';
                
                if (endswith($sOrgWord, '.')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.'.';
                if (endswith($sOrgWord, '!')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.'!';
                if (endswith($sOrgWord, '?')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.'?';
                if (endswith($sOrgWord, ';')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.';';
                if (endswith($sOrgWord, ':')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.':';
                if (endswith($sOrgWord, ',')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.',';
                if (endswith($sOrgWord, '\\')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.'\\';
                if (endswith($sOrgWord, '/')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.'/';
                if (endswith($sOrgWord, '"')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.'"';
                if (endswith($sOrgWord, '\'')) //add dot if it was there in the first place
                    $sReplacedWord = $sReplacedWord.'\'';
                
                $arrWords[$iWordIndex] = $sReplacedWord;
            }
            else
                $arrWords[$iWordIndex] = htmlentities($arrWords[$iWordIndex]); 
        }
        
        $arrLines[$iLineIndex] = implode(' ', $arrWords);
    }
    
    
    //putting back together
    $sOutput = implode('<br>', $arrLines);
         
    

    return $sOutput;
 
}

/**
 * wrapper for plainText2HTML()
 */
function text2HTML($sInput, $bTargetBlank = false, $bRelNoFollow = true)
{
    return plainText2HTML($sInput, $bTargetBlank, $bRelNoFollow);
}

/**
 * extracts youtube id from url
 * there are different formats of youtube urls.
 * 
 * returns empty string if it couldnt find the id
 * 
 * popular formats are:
 * https://youtube.com/vi/tFad5gHoBjY
http://www.youtube.com/?v=tFad5gHoBjY
http://www.youtube.com/?vi=tFad5gHoBjY
https://www.youtube.com/watch?v=tFad5gHoBjY
youtube.com/watch?vi=tFad5gHoBjY
youtu.be/tFad5gHoBjY
http://youtu.be/qokEYBNWA_0?t=30m26s
youtube.com/v/7HCZvhRAk-M
youtube.com/vi/7HCZvhRAk-M
youtube.com/?v=7HCZvhRAk-M
youtube.com/?vi=7HCZvhRAk-M
youtube.com/watch?v=7HCZvhRAk-M
youtube.com/watch?vi=7HCZvhRAk-M
youtu.be/7HCZvhRAk-M
youtube.com/embed/7HCZvhRAk-M
http://youtube.com/v/7HCZvhRAk-M
http://www.youtube.com/v/7HCZvhRAk-M
https://www.youtube.com/v/7HCZvhRAk-M
youtube.com/watch?v=7HCZvhRAk-M&wtv=wtv
http://www.youtube.com/watch?dev=inprogress&v=7HCZvhRAk-M&feature=related
youtube.com/watch?v=7HCZvhRAk-M
http://youtube.com/v/dQw4w9WgXcQ?feature=youtube_gdata_player
http://youtube.com/vi/dQw4w9WgXcQ?feature=youtube_gdata_player
http://youtube.com/?v=dQw4w9WgXcQ&feature=youtube_gdata_player
http://www.youtube.com/watch?v=dQw4w9WgXcQ&feature=youtube_gdata_player
http://youtube.com/?vi=dQw4w9WgXcQ&feature=youtube_gdata_player
http://youtube.com/watch?v=dQw4w9WgXcQ&feature=youtube_gdata_player
http://youtube.com/watch?vi=dQw4w9WgXcQ&feature=youtube_gdata_player
http://youtu.be/dQw4w9WgXcQ?feature=youtube_gdata_player';
 * https://www.youtube.com/embed/EnTBKlNXlV4
 * 
 * 
 * @param string $sURL
 * @return string youtube id
 */
function getYouTubeID(&$sURL)
{
    $arrTemp = array();
    $arrTemp2 = array();
    $sTempURL = '';
    $sTempURL = $sURL;
    
    //we go on the hunt for the id, 
    
    //is there a v= parameter?
    //handle: youtube.com/watch?v=7HCZvhRAk-M&wtv=wtv
    parse_str( parse_url( $sURL, PHP_URL_QUERY ), $arrTemp);
    if (isset($arrTemp['v']))
        return $arrTemp['v'];    
    
    //very common shortcode, id after youtu.be/
    //handle: youtu.be/tFad5gHoBjY?feature=youtube_gdata_player
    $arrTemp = explode('outu.be/', $sURL);
    if (count($arrTemp) == 2) //1 part before (http etc) + 1 part after (=id)
    {
        //are there parameters after the slash?
        $arrTemp2 = explode('?',$arrTemp[1]);//second index        
        if (count($arrTemp2) == 2)
        {
            return $arrTemp2[0];
        }
        else
            return $arrTemp[1]; //second index
    }    
    
    //if embed url, id is after last slash
    //handle: https://www.youtube.com/embed/EnTBKlNXlV4?feature=youtube_gdata_player
    $arrTemp = explode('tube.com/embed/', $sURL);
    if (count($arrTemp) == 2) //1 part before (http etc) + 1 part after (=id)
    {
        //are there parameters after the slash?
        $arrTemp2 = explode('?',$arrTemp[1]);//second index        
        if (count($arrTemp2) == 2)
        {
            return $arrTemp2[0];
        }
        else
            return $arrTemp[1]; //second index
    }
    
    
    //id after /v/
    //handle: http://youtube.com/v/7HCZvhRAk-M?feature=youtube_gdata_player
    $arrTemp = explode('tube.com/v/', $sURL);
    if (count($arrTemp) == 2) //1 part before (http etc) + 1 part after (=id)
    {
        //are there parameters after the slash?
        $arrTemp2 = explode('?',$arrTemp[1]);//second index        
        if (count($arrTemp2) == 2)
        {
            return $arrTemp2[0];
        }
        else
            return $arrTemp[1]; //second index
    }    

    //id after /vi/
    //handle: https://youtube.com/vi/tFad5gHoBjY?feature=youtube_gdata_player
    $arrTemp = explode('tube.com/vi/', $sURL);
    if (count($arrTemp) == 2) //1 part before (http etc) + 1 part after (=id)
    {
        //are there parameters after the slash?
        $arrTemp2 = explode('?',$arrTemp[1]);//second index        
        if (count($arrTemp2) == 2)
        {
            return $arrTemp2[0];
        }
        else
            return $arrTemp[1]; //second index
    }    
    
    //is there a vi= parameter?
    //handle: youtube.com/watch?vi=tFad5gHoBjY
    parse_str( parse_url( $sURL, PHP_URL_QUERY ), $arrTemp);
    if (isset($arrTemp['vi']))
        return $arrTemp['vi'];     
    
    
    //if embed url NOCOOKIE DOMAIN, id is after last slash
    //handle: https://www.youtube-nocookie.com/embed/EnTBKlNXlV4
    $arrTemp = explode('tube-nocookie.com/embed/', $sURL);
    if (count($arrTemp) == 2) //1 part before (http etc) + 1 part after (=id)
    {
        //are there parameters after the slash?
        $arrTemp2 = explode('?',$arrTemp[1]);//second index        
        if (count($arrTemp2) == 2)
        {
            return $arrTemp2[0];
        }
        else
            return $arrTemp[1]; //second index
    }    
    
    
    return '';    
}

/**
 * all timestamps in a youtube video description -30 seconds
 * accepts format (no trailing bullet points or whatever): 
 * 0:53 input selection
 * 2:07 Browse tracks / playlists
 * 3:47 search tracks
 */
function youTubeTimestampMinus($sInput, $iMinusSecs)
{
    //lines
    $arrLines = explode("\n", $sInput);
    // vardump(count($arrLines));
    if (count($arrLines) > 1)
    {
        $sResult = '';
        foreach ($arrLines as $sLine)
        {
            $sResult .= youTubeTimestampMinus(trim($sLine), $iMinusSecs)."\n";
        }
        return $sResult;
    }
    else if (count($arrLines) == 1)
    {
        //spaces
        $arrSpace = explode(' ', $sInput);
        $sRest= '';
        for ($iTeller = 1; $iTeller < count($arrSpace); $iTeller++)
            $sRest.= ' '.$arrSpace[$iTeller];

        //timestamps
        $arrExplode = explode(':', $arrSpace[0]);
        $iMinutes = $arrExplode[0];
        $iSeconds = $arrExplode[1];
        $iTotal = ($iMinutes * 60) + $iSeconds;
        $iTotal-= $iMinusSecs;

        return (floor($iTotal/60). ':'.($iTotal%60)).' '. $sRest;
    }
}


/**
 * html markup sanitizer
 * 
 * This function does 2 things which you can switch on or off via the parameters:
 * 1) cleanup the HTML-poop that word processors (Word, LibreOffice) and browser-HTML-editors produce
 *    This function will remove things like HTML attributes that can conflict the the website's css
 * 2) nice HTML formatting that a human can read
 * 
 * 
 * @param string $sHTML
 * @param boolean $bSanitizeMarkup remove all tags except for a few whitelisted
 * @param boolean $bFormatHTML if true will add linebreaks (\n) and tab characters for identation
 * @return string clean html
 */
function cleanHTMLMarkup($sHTML, $bSanitizeMarkup = true, $bFormatHTML = true, $bReplacePTagByBR = false, $bLinksTargetBlank = true, $bLinksRelNofollow = false) 
{
    $sCleanHTML = '';

    
    /**
     * recursive reading the html
     * 
     * @param type $objNode
     * @param type $bCleanMarkup
     * @param type $bDoFormatHTML
     * @param type $iIdentLevel
     * @param type $bRemoveDIVs
     * @return string
     */
    function cleanChildNode($objNode, $bRemoveUnwantedTagsAndAttributes ,$bDoFormatHTML, $iIdentLevel, $bReplacePByBR, $bLinksTargetIsBlank = true, $bLinksRelIsNofollow = false)
    {
        $sReturnHTML = '';
        $arrAllowHTMLTags = array('a','p','table', 'th', 'tr','td', 'br', 'hr', 'section', 'ul','ol', 'li', 'table', 'tbody', 'thead', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'b', 'i'); //no <div>, no <u> (dueo to confusion with links)
//        $arrIdentBeforeHTMLTags = array('#text', 'li','br', 'table', 'tbody', 'tr', 'td', 'thead');
        $arrNLBeforeOpeningHTMLTags = array('ul','ol', 'table', 'tbody', 'thead','tr', 'td', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
        $arrNLAfterClosingHTMLTags = array('tr', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ol', 'ul');
        $arrNoClosingHTMLTags = array('br','hr');
        $bAddTag = true; //declare;
        
        if ($objNode->hasChildNodes())
        {
            foreach ($objNode->childNodes as $objChildNode)
            {
                if ($objChildNode->nodeName == '#text') //is text node, add text immediately
                {
                    $sReturnHTML.= htmlentities($objChildNode->nodeValue);
                }
                else //is tag
                {
                    $bAddTag = true; //only add a tag if it's on the whitelist (when $bRemoveUnwantedTags is true)                    
                    if ($bRemoveUnwantedTagsAndAttributes) //only add a tag if it's on the whitelist
                        $bAddTag = in_array($objChildNode->nodeName, $arrAllowHTMLTags); 
                    if ($bReplacePByBR)
                        if ($objChildNode->nodeName == 'p')
                            $bAddTag = false;
                    
                    if ($bAddTag)
                    {
                        //====identation before start tag
                        if ($bDoFormatHTML)
                        {
                            //add new line before certain tags
                            if (in_array($objChildNode->nodeName, $arrNLBeforeOpeningHTMLTags)) 
                                $sReturnHTML.= "\n";                            

                            if (in_array($objChildNode->nodeName, $arrNLBeforeOpeningHTMLTags)) //need to use identation?
                            {
                                for ($iIdentCounter = 0; $iIdentCounter < $iIdentLevel; $iIdentCounter++)
                                {
                                    $sReturnHTML.= "\t";                                    
                                }
                            }                            
                        }

                        
                        //====start tag
                        $sReturnHTML.= '<'.strToLower($objChildNode->nodeName);
                        if ($objChildNode->hasAttributes())
                        {
                            if ($objChildNode->nodeName == 'a')
                            {
                                if ($bLinksTargetIsBlank)
                                    $objChildNode->setAttribute('target', '_blank');
                                if ($bLinksRelIsNofollow)
                                    $objChildNode->setAttribute('rel', 'nofollow');
                                
                                
                            }
                            
                            foreach ($objChildNode->attributes as $objAttribute)
                            {
                                //for links rel=nofollow and target=_blank
//                                if ($objChildNode->nodeName == 'a')
//                                {
//                                    if ($bLinksTargetIsBlank)
//                                    {
//                                        if ($objAttribute->nodeName == 'target')
//                                        {
//                                            $objAttribute->nodeValue = '_blank';
//                                        }
//                                    }
//                                    
//                                    if ($bLinksRelIsNofollow)
//                                    {
//                                        if ($objAttribute->nodeName == 'rel')
//                                        {
//                                            $objAttribute->nodeValue = 'nofollow';
//                                        }                                        
//                                    }
//                                }
                                
                                
                                if ($bRemoveUnwantedTagsAndAttributes) //only allow certain attributes
                                {
                                    if ($objChildNode->nodeName == 'a')
                                    {
                                        //only add allowed attributes, the default is: don't add
                                        switch ($objAttribute->nodeName) 
                                        {
                                            case 'href':
                                            case 'rel':
                                            case 'target':
                                            case 'cellpadding':
                                                $sReturnHTML.= ' '.$objAttribute->nodeName.'="'.$objAttribute->nodeValue.'"';
                                                break;
                                        }           
                                    }
                                }
                                else //add all attributes
                                {
                                    $sReturnHTML.= ' '.$objAttribute->nodeName.'="'.$objAttribute->nodeValue.'"';
                                }

                            }
                        }
                        $sReturnHTML.= '>';



                    }//end: if (addtag)
                    
                    //====content of tag
                    //we want to have the content of a tag, whether the tag is allowed or not
                    if ($bAddTag) //increase ident number based on the tag is allowed or not
                        $sReturnHTML.= cleanChildNode($objChildNode,$bRemoveUnwantedTagsAndAttributes,$bDoFormatHTML,($iIdentLevel + 1), $bReplacePByBR, $bLinksTargetIsBlank, $bLinksRelIsNofollow);
                    else
                        $sReturnHTML.= cleanChildNode($objChildNode,$bRemoveUnwantedTagsAndAttributes,$bDoFormatHTML,$iIdentLevel, $bReplacePByBR, $bLinksTargetIsBlank, $bLinksRelIsNofollow);

                    if ($bAddTag)
                    {
                        //add new line before certain tags
//                        if (in_array($objChildNode->nodeName, $arrNLAfterClosingHTMLTags)) 
//                            $sReturnHTML.= "\n";
                        
                        if (in_array($objChildNode->nodeName, $arrNLBeforeOpeningHTMLTags)) //need to use identation?
                        {
                            for ($iIdentCounter = 0; $iIdentCounter < $iIdentLevel; $iIdentCounter++)
                            {
                                $sReturnHTML.= "\t";                                    
                            }
                        }  
                        
                        
                        //====end tag
                        if (!in_array($objChildNode->nodeName, $arrNoClosingHTMLTags)) 
                            $sReturnHTML.= '</'.strToLower($objChildNode->nodeName).'>';

                        //add new line after certain tags
                        if ($bDoFormatHTML)
                            if (in_array($objChildNode->nodeName, $arrNLAfterClosingHTMLTags)) 
                                $sReturnHTML.= "\n";   
                    }//end: if (addtag)
                    
                    if ($bReplacePByBR)
                        if ($objChildNode->nodeName == 'p')
                            $sReturnHTML.='<br>'."\n"; ;
                }//end: else is tag (or text)
            } //end: foreach childnode
            
        }//end: hasChildnodes
        
        return $sReturnHTML;
    }
    
    
    
    
    //====remove attributes
    $objDoc = new DOMDocument();
//    $objDoc->loadHTML($sHTML, LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD);
    $objDoc->loadHTML(mb_convert_encoding($sHTML, 'HTML-ENTITIES', 'UTF-8'));
    
    
    $sCleanHTML = cleanChildNode($objDoc, $bSanitizeMarkup, $bFormatHTML, 0, $bReplacePTagByBR, $bLinksTargetBlank, $bLinksRelNofollow);
      
    
    
    return $sCleanHTML;
}

/**
 * get a cryptographically secure, real unique uniqueid
 * 
 * you can check the validity with isUniqueidRealValid(), like in url parameters for example
 * 
 * @param integer $iLengthID
 * @return string
 */
function uniqidReal($iLengthID = 13) 
{
    $sResult = '';
    $sUniqueIDChars = UNIQUEID_CHARS;
    $iMaxIndexUniqueIDChars = 0;
    $iMaxIndexUniqueIDChars = strlen($sUniqueIDChars)-1;
    $iTempIndex = 0;

    for ($iCounter = 0; $iCounter < $iLengthID; ++$iCounter)
    {
        $iTempIndex = random_int(0, $iMaxIndexUniqueIDChars);
        $sResult.= $sUniqueIDChars[$iTempIndex];
    }

    //* @author hackan at gmail dot com (https://www.php.net/manual/en/function.uniqid.php#120123)
    // if (function_exists("random_bytes")) 
    // {
    //     $bytes = random_bytes(ceil($iLengthID / 2));
    // } 
    // elseif (function_exists("openssl_random_pseudo_bytes")) 
    // {
    //     $bytes = openssl_random_pseudo_bytes(ceil($iLengthID / 2));
    // } else 
    // {
    //     throw new Exception("no cryptographically secure random function available");
    // }
    // vardump(bindec($bytes));
    // return substr(bin2hex($bytes), 0, $iLengthID);

    return $sResult;
}

/**
 * Test if a uniqueidReal is a valid value
 * use case: test url parameters for poisoning
 *
 * @param string $sUniqueidReal
 * @return boolean
 */
function isUniqueidRealValid($sUniqueidReal, $bReturnFalseWhenEmpty = true)
{
    if ((strlen($sUniqueidReal)>0) && ($bReturnFalseWhenEmpty))
    {
        $sFilteredResult = '';
        $sFilteredResult = filterBadCharsWhiteList($sUniqueidReal, UNIQUEID_CHARS);
        return ($sFilteredResult === $sUniqueidReal);
        // return ctype_xdigit($sUniqueidReal); //test for valid hex
    }
    else
        return false;
}

/**
 * return a valid javascript function name
 */
function getSafeJavascriptFunctionName($sJSFunctionName)
{
    return preg_replace( '/[^_ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789]/', '', $sJSFunctionName );
}



?>



