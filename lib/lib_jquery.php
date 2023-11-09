<?php
/**
 * helper library for jquery related components
 * 
 * it's pretty sad we need this library, but since the guys that programmed jquery 
 * are complete morons, we need this
 * sad, sad, sad, sad and stupid
 * but hey, why would you think if you can annoy programmers?
 *
 * 
 * 
 * IMPORTANT:
 * This library is language independant, so don't use language specific element
 *
 * 13 jan 2020 created
 * 
 * @author Dennis Renirie
 */

//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_date.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_img.php'); 
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_inet.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_math.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_string.php');

/**
 * convert the php date format to the format that jquery uses for it's datepicker
 * 
 * 
 * anton@stackoverflow
 * 
 * @param type $sPHPDateFormat
 */
function dateformatPHPtojQueryUI($php_format)
{
    $SYMBOLS_MATCHING = array(
        // Day
        'd' => 'dd',
        'D' => 'D',
        'j' => 'd',
        'l' => 'DD',
        'N' => '',
        'S' => '',
        'w' => '',
        'z' => 'o',
        // Week
        'W' => '',
        // Month
        'F' => 'MM',
        'm' => 'mm',
        'M' => 'M',
        'n' => 'm',
        't' => '',
        // Year
        'L' => '',
        'o' => '',
        'Y' => 'yy',
        'y' => 'y',
        // Time
        'a' => '',
        'A' => '',
        'B' => '',
        'g' => '',
        'G' => '',
        'h' => '',
        'H' => '',
        'i' => '',
        's' => '',
        'u' => ''
    );
    $jqueryui_format = "";
    $escaping = false;
    for($i = 0; $i < strlen($php_format); $i++)
    {
        $char = $php_format[$i];
        if($char === '\\') // PHP date format escaping character
        {
            $i++;
            if($escaping) $jqueryui_format .= $php_format[$i];
            else $jqueryui_format .= '\'' . $php_format[$i];
            $escaping = true;
        }
        else
        {
            if($escaping) { $jqueryui_format .= "'"; $escaping = false; }
            if(isset($SYMBOLS_MATCHING[$char]))
                $jqueryui_format .= $SYMBOLS_MATCHING[$char];
            else
                $jqueryui_format .= $char;
        }
    }
    return $jqueryui_format;
}




?>
