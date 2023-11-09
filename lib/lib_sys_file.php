<?php
/**
 * In this library exist only file/url related functions
 * ook voor het genereren van bestandnamen etc.
 *
 * IMPORTANT:
 * This library is language independant, so don't use language specific element
 *
 * 13 okt 09
 * ==========
 * -extractFileFromPath() en extractDirectoryFromPath aangepast met native PHP functies
 *
 * 16 mei 2010
 * ==========
 * -getFileNameWithoutExtension() aangepast, als file geen extensie dan filenaam teruggegeven
 *
 * 18 mei 2010
 * ==========
 * -saveToFile aangepast met PHP_EOL
 * 
 * 5 april 2019
 * =========-
 * -getFileFolderArray() extensions gaf bug
 * 
 * 28 okt 2020
 * ========
 * -lib_sys_file: uploadFilesRearrangeArray() added
 * -lib_sys_file: function tempdir() added
 * -lib_sys_file: function copyRecursive($sSource, $sDestination) added
 * -lib_sys_file: function renameRecursive($sSource, $sDestination) added
 * -lib_sys_file: function getFileFolderArray() performance improvement
 
 * 
 * 8 juli 2012: utf8_decode() voor readFromFileString
 * 11 juli 2012: saveToFile() bugfix, als utf8 was, dan nogmaals naar utf8 converten, nu wordt utf8 gedetecteerd
 * 11 JULI 2012: addToFile()  bugfix, als utf8 was, dan nogmaals naar utf8 converten, nu wordt utf8 gedetecteerd
 * 11 juli 2012: saveToFileString()bugfix, als utf8 was, dan nogmaals naar utf8 converten, nu wordt utf8 gedetecteerd
 * 11 juli 2012: loadFromFileString() detects and returns always a UTF8 string (before it didn't detect, and always converted)
 * 11 juli 2012: loadFromFile() detects and returns always a UTF8 array (before it didn't detect, and always converted)
 * 13 juli 2012: lib_file: saveToFileString() had een undefined variable, verangen door PHP_EOL
 * 2 mei 2014: lib_file: filterDirectorytraversal hier naar toe verhuisd
 * 2 mei 2014: lib_file: filterDirectorytraversal  bugfix: roep filter sql injection aan ipv filterDirectoryTraversal
 * 2 mei 2014: lib_file: filterDirectorytraversal heet nu: filterFileName()
 * 2 mei 2014: lib_file: filterDirectorytraversal heeft extra filter voor verkeerde bestandsnamen
 * 4 aug 2014: lib_file: getFileFolderArray() betere ondersteuning vooor geen teruggave
  * 4 apr 2014: lib_file: filterFileName vervangen, werkt nu met whitelist
  * 18 nov 2022: lib_file: filterForDirectoryTraversal() added
  * 18 nov 2022: lib_file: filterForDirectoryTraversal() improved
 *
 * @author Dennis Renirie
 */



//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_date.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_file.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_img.php'); 
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_inet.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_math.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php');
include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_string.php');





/**
 * Converts a string to a valid UNIX filename.
 * (also filters for directory traversal)
 * 
 * @param $string The filename to be converted
 * @return $string The filename converted
 */
function filterFileName ($string) {

	// Replace spaces with underscores and makes the string lowercase
	$string = str_replace (" ", "_", $string);

	$string = str_replace ("..", ".", $string);
	$string = strtolower ($string);

	// Match any character that is not in our whitelist
	preg_match_all ("/[^0-9^a-z^_^.]/", $string, $matches);

	// Loop through the matches with foreach
	foreach ($matches[0] as $value) {
		$string = str_replace($value, "", $string);
	}
	return $string;
}

/**
 * 
 * filter filename or directory for directory traversal injection
 * sanatize string filename or directory
 *
 * directory traversal is the changing of directories throug the reserved directory jumping characters (.. and ../ and ..\)
 *
 * example of directory traversal:
 *
 * $template = 'red.php';
 * $template = $_COOKIE['TEMPLATE'];
 * include ("/home/users/phpguru/templates/" . $template);
 *
 * An attack against this system could be to send the following HTTP request:
 * GET /vulnerable.php HTTP/1.0
 * Cookie: TEMPLATE=../../../../../../../../../etc/passwd

 */
function filterDirectoryTraversal($sFilePath)
{

        //filter double dots used in traversal
        $string = str_replace ('..', '', $sFilePath);

	// Match any character that is not in our whitelist
        //the / and \ are supposed to be filtered out this way
        $arrMatches = array();
	preg_match_all ("/[^0-9^a-z^A-Z^_^.^ ^-]/", $sFilePath, $arrMatches);

	//Loop through the matches with foreach
	foreach ($arrMatches[0] as $sMatch) {
		$sFilePath = str_replace($sMatch, '', $sFilePath);
	}

	return $sFilePath;
}
//old version
// function filterFileName($mInput) 
// {
//     if (is_array($mInput))
//     {
//         foreach (array_keys($mInput) as $sKey)
//             $mInput[$sKey] = filterFileName($mInput[$sKey]);
//     }
//     else
//     {
//         $mInput = str_replace('..', '', $mInput);
//         $mInput = str_replace('..\\', '', $mInput);
//         $mInput = str_replace('../', '', $mInput);

//         //their html equivalent
//         $mInput = str_replace(htmlentities('..'), '', $mInput);
//         $mInput = str_replace(htmlentities('..\\'), '', $mInput);
//         $mInput = str_replace(htmlentities('../'), '', $mInput);

//         //their url-encoded equivalent
//         $mInput = str_replace(urlencode('..'), '', $mInput);
//         $mInput = str_replace(urlencode("..\\"), '', $mInput);
//         $mInput = str_replace(urlencode('../'), '', $mInput);

//         //thanks to UTF8 conversion maybe another vulnerability
// //        if (!isUTF8String($mInput)) 
//         {
//             $mInput = str_replace(utf8_decode('..'), '', $mInput);
//             $mInput = str_replace(utf8_decode('..\\'), '', $mInput);
//             $mInput = str_replace(utf8_decode('../'), '', $mInput);
//         }

//         //and thanks to microsoft also some additional UTF8 characters
//         $mInput = str_replace('%c1%1c', '', $mInput);
//         $mInput = str_replace('%c0%af', '', $mInput);
//         $mInput = str_replace('%c0%9v', '', $mInput);

//         $mInput = filterBadCharsWhiteList($mInput, 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890._-\\/');        
//     }

//     return $mInput;
// }

/**
 * het includen van alle files in een directory met een bepaalde extensie
 * 
 * @param string $sDirectory
 * @param string $sExtension
 * @return bool true, false if not exists or error occured
 */
function requireAll($sDirectory, $sExtension = '.php')
{
    if (is_dir($sDirectory))
    {

        $dh = opendir($sDirectory);
        //deb($dir);
        while(false !== ($file = readdir($dh)))
        if (strpos($file, $sExtension) != false)
        {
            require_once $sDirectory.$file;
        }
        closedir($dh);
        return true;
    }
    else
    {
        throw new Exception('Directory "'.$sDirectory.'" does not exist');
        return false;
    }
}

/**
 * het extraheren van een file uit een path. Bijvoorbeeld : je hebt /home/var/local/index.php en je wilt hebben : index.php (dus zonder /home/var/local/)
 * 
 * @param string $sPath
 * @return string
 */
function extractFileFromPath($sPath)
{
    return basename($sPath);
}

/**
 * het extraheren van een directory uit een path. Bijvoorbeeld : je hebt /home/var/local/index.php en je wilt hebben : /home/var/local/ (dus zonder index.php)
 *
 * @param string $sPath
 * @return string inclusief laatste / of \
 */
function extractDirectoryFromPath($sPath)
{
    return dirname($sPath).DIRECTORY_SEPARATOR;
}

/**
 * lezen van bestandsgrootte
 * 
 * @param string $sFilePath (path van een file)
 * @return int grootte van bestand in kilobytes
 */
function getFileSize($sFilePath)
{
        if (is_file($sFilePath))
        {
                $sizekb = (int)(filesize($sFilePath) / 1024);

                if ($sizekb == 0)
                {
                        $size = "1 kb";
                }
                else
                {
                        $size = "$sizekb kb";

                }
        }
        else
                $size = "0 kb";

        return($size);
}

/**
 * Als je een path hebt, dan vorige directory teruggeven
 * VB : als $sPath /var/www/project1/test , dan uitkomst van deze functie /var/www/project1
 * 
 * @param string $sPath
 * @return string Vorige directory van de opgegeven string
 */
function getParentDirectory($sPath)
{
        $sTempDirZonderSlash = $sPath;
//		echo "*$sTempDirZonderSlash*".$sPath."*";
        $iTempPos = strrpos($sTempDirZonderSlash, "/"); //pos opvragen van laatste /
        $sResult = substr($sTempDirZonderSlash, 0, $iTempPos);  //alles voor de laatste / pakken en als restult geven

        return($sResult);
}

/**
 * upload a file to this site from the webserver
 * 
 * This function outputs dutch data to the screen
 * 
 * @param int $iMaxuploadsize
 * @param <type> $sFieldName
 * @param <type> $sNewPathFile
 * @param <type> $arrAllowedExtensions
 */
function uploadFile($sFieldName, $sNewPathFile, $arrAllowedExtensions, $iMaxuploadsize = 0)
{
	function uploadFileFunctionWithExtension($sFieldName, $sExtension, $sNewPathFile)
	{
		$bSuccessSub = false;

		if (strtoupper(getFileExtension($sFieldName['name'])) == strtoupper($sExtension))
		{
			if ( move_uploaded_file($sFieldName['tmp_name'], $sNewPathFile) )
			{
				echo "upload van '".$sFieldName['name']."' succesvol voltooid";
				$bSuccessSub = true;
			}
			else
			{
				echo  "upload van '".$sFieldName['name']."' MISLUKT !";
			}
		}

		return($bSuccessSub);
	}

        if (is_uploaded_file($sFieldName['tmp_name']))
        {
                //echo "uploaden";

                if ($sFieldName['size'] > $iMaxuploadsize)
                {
                        echo "Het bestand neemt te veel ruimte in beslag";
                }

                if (is_array($arrAllowedExtensions))
                {
                			$iCountExt = count ($arrAllowedExtensions);
                        for ($iArrayTeller = 0; $iArrayTeller < $iCountExt; $iArrayTeller++)
                        {
                                uploadFileFunctionWithExtension($sFieldName, $arrAllowedExtensions[$iArrayTeller], $sNewPathFile);
                        }
                }
                else
                {
                        uploadFileFunctionWithExtension($sFieldName, $arrAllowedExtensions, $sNewPathFile);
                }
        }


}

/**
 * wrapper for rmdirrecursive
 * @param <type> $sDirectory
 * @return <type> 
 */
function removedir($sDirectory)
{
    return rmdirrecursive($sDirectory);
}

/**
 * wrapper for rmdirrecursive
 * @param <type> $sDirectory
 * @return <type>
 */
function deletedir($sDirectory)
{
    return rmdirrecursive($sDirectory);
}

/**
 * recursive delete directory.
 * PHP verrot het verwijderen van een directory als deze niet leeg is.
 * 
 * @param string $sDirectory
 * @return bool
 */
function rmdirrecursive($sDirectory)
{
   $dh=opendir($sDirectory);
   while ($file=readdir($dh))
   {
        if($file!="." && $file!="..")
        {
                $fullpath=$sDirectory."/".$file;
                if(!is_dir($fullpath))
                {
                        unlink($fullpath);
                }else{
                        rmdirrecursive($fullpath);
                }
        }
   }
   closedir($dh);
   if(rmdir($sDirectory))
   {
           return true;
   }else{
           return false;
   }
}

/**
 * het verkrijgen van alle bestanden/(sub)directories uit een directory
 * Geeft het resultaat in een 1d string array terug
 *
 * @param string $sDirectory
 * @param bool $bDirectories mappen ook in array zetten ?
 * @param bool $bFiles bestanden ook in array zetten ?
 * @return array met strings met bestanden en directories
 */
/*
function getFileFolderArray($sDirectory, $bDirectories, $bFiles)
{
        $arrFiles = array();
        if (is_dir($sDirectory))
        {
                $d = dir($sDirectory);

                while (false !== ($file = $d->read()))
                {
                        $path = $sDirectory.$file;
                        if ($file != "." && $file != "..")
                        {
                                if ($bDirectories)
                                        if (is_dir($sDirectory.$file))
                                                $arrFiles[] = $file;
                                if ($bFiles)
                                        if (is_file($sDirectory.$file))
                                                $arrFiles[] = $file;
                        }
                }
                $d->close();
        }
        if (is_array($arrFiles))
            if (count($arrFiles) > 0)
                sort($arrFiles);

        return $arrFiles;
}

*/

/**
 * subdirectories en/of bestanden uit een directory in array plaatsen (uitbreiding op getFileFolderArray)
 * je kunt nu echter toegestane bestandsextensies opgeven
 * 
 * @param string $sDirectory
 * @param bool $bDirectories mappen ook in array zetten ?
 * @param bool $bFiles bestanden ook in array zetten ?
 * @param array $arrExtensions array van extensions zonder punt bv jpg, jpeg, bmp
 * @return array
 */
/*
function getFileFolderArrayExtension($sDirectory, $bDirectories, $bFiles, $arrExtensions)
{
        $arrFiles = array();
        if (is_dir($sDirectory))
        {
                $d = dir($sDirectory);

                while (false !== ($file = $d->read()))
                {
                        $path = $sDirectory.$file;
                        if ($file != "." && $file != "..")
                        {
                                if ($bDirectories)
                                        if (is_dir($sDirectory.$file))
                                                $arrFiles[] = $file;
                                if ($bFiles)
                                        if (is_file($sDirectory.$file))
                                                for ($iTeller = 0; $iTeller < count($arrExtensions)-1; $iTeller++)
                                                        if (strtoupper($arrExtensions[$iTeller]) == strtoupper(getFileExtension($sDirectory.$file)))
                                                                $arrFiles[] = $file;
                        }
                }
                $d->close();
        }
        if (is_array($arrFiles))        
            if (count($arrFiles) > 0)
                sort($arrFiles);

        return $arrFiles;
}
 * */
 

/**
 * het verkrijgen van alle bestanden/(sub)directories uit een directory
 * Geeft het resultaat in een 1d string array terug, false on failure
 * 
 * deze functie is geavanceerder dan php's eigen scandir()
 * Je kunt bij deze functie filteren op directory, file en bestandsextensie 
 * daarbij worden de unix punt (.) en punt-punt (..) niet terug gegeven
 * 
 * directories worden herkend met is_dir.
 * als er onvoldoende rechten zijn voor de directory werkt is_dir niet goed!
 * maw. deze functie werkt dan niet goed
 * 
 * Als je alleen een directory inhoud wilt hebben en niet filteren op directory, file of extensie
 * gebruik dan scandir(). dat is sneller
 *
 * @param string $sDirectory
 * @param bool $bAllowDirectories mappen ook in array zetten ?
 * @param bool $bAllowFiles bestanden ook in array zetten ?
 * @param array $arrExtensions de extensies waarop gefilterd moet worden - de vorm van de array: array('exe', 'doc', 'xls');
 * @return array met strings met bestanden en directories / false is failed
 */
function getFileFolderArray($sDirectory, $bAllowDirectories=true, $bAllowFiles=true, $arrExtensions = null)
{
    $arrResult = array();
    
    if ($arrExtensions != null)//foutieve parameter ondervangen
    {
        if (!is_array($arrExtensions))
            $arrExtensions = null;
    }
    
    //directory filteren zodat er geen separator achter komt
    if (endswith($sDirectory, DIRECTORY_SEPARATOR))
        $sDirectory = removeLastChar($sDirectory);
    
    if (is_dir($sDirectory))
    {
        $arrTempFiles= scandir($sDirectory);

        
        if ($arrTempFiles)
        {
            foreach ($arrTempFiles as $sFile) //array filteren
            {                
                if (($sFile != '.') && ($sFile != '..') && ($sFile != '.DS_Store') && (!startswith($sFile, '@')))
                {        
                    
                    if ($bAllowFiles)
                    {
                        if (is_file($sDirectory.DIRECTORY_SEPARATOR.$sFile))
                        {


                            if ($arrExtensions != null)
                            {
                                foreach ($arrExtensions as $sExtension)
                                {
                                    if (endswith($sFile, '.'.$sExtension))
                                         $arrResult[] = $sFile;   
                                }
                            }    
                            else
                                $arrResult[] = $sFile;
                        }
                    }
                    
                    if ($bAllowDirectories)
                    {
                        if (is_dir($sDirectory.DIRECTORY_SEPARATOR.$sFile.DIRECTORY_SEPARATOR))
                           $arrResult[] = $sFile;
                        
                    }


                    
                }
            }    
            return $arrResult;
                
        }
        else
            return false;
    }
    else
        return false;
    
}


/**
 * extensie van bestand verkrijgen
 *
 * @param string $sFile
 * @return string
 */
function getFileExtension($sFile)
{
        $iPosPunt = strrpos($sFile, ".");
        $iLengthExtension = strlen($sFile) - $iPosPunt -1;
        return substr($sFile, $iPosPunt + 1, $iLengthExtension);
}

/**
 * bestandsnaam verkrijgen zonder extensie
 * (er wordt gezocht naar de laatste punt(.) in de string)
 * als $sFile geen extensie heeft, wordt $sFile weer teruggegeven
 * 
 * @param string $sFile
 * @return string
 */
function getFileNameWithoutExtension($sFile)
{
        $iPosPunt = strrpos($sFile, ".");
        $iLengthFileNameWithoutExtension = $iPosPunt;

        if ($iLengthFileNameWithoutExtension == false) //als geen extensie, dan alleen filenaam teruggeven
            return $sFile;
        else
            return substr($sFile, 0, $iLengthFileNameWithoutExtension);
}

/**
 * het genereren van een unieke filename in die directory
 * functie heette voorheen deliverFileName()
 *
 * @param string $sDirectory
 * @param string $sExtension extensie van het bestand, bv: jpg (zonder punt .)
 * @param string $sPrefix prefix van de te genereren bestandsnaam, bv: 'nieuws_'
 * @param string $sPostfixId postfix van de te generen bestandnaam, meestal de id van het record in de databasetabel
 * @return string
 */
function generateUniqueFileName($sDirectory, $sExtension, $sPrefix, $sPostfixId)
{
        $sPrefix = $sPrefix."_";
        $iHoogsteWaarde = 0;

        //kijken wat de hoogste waarde is van bestanden met dezelfde prefix(inclusief $sPostfixId)
        $d = dir($sDirectory);
        while (false !== ($file = $d->read())) //directory doorlopen
        {
                $path = "$sDirectory/$file";

                if (($file != "." && $file != "..") && is_file($path))
                {
                        if(substr($file, 0, strlen($sPrefix.$sPostfixId)) == $sPrefix.$sPostfixId) //komen er bestanden voor met dezelde prefix ?
                                if(strrpos($file, "(")) // ja ? dan kijk naar de postfix (tussen () haakjes)
                                        $iHoogsteWaarde = substr($file, strrpos($file, "(")+1, 1);
                }
        }
        $iNieuwNr = $iHoogsteWaarde+1;
        $sReturn = $sPrefix.$sPostfixId."(".$iNieuwNr.").".$sExtension; //de haakjes () erbij

        return $sReturn;
}


/**
 * net zoals in windows weergeven wat voor type file het is d.m.v. een plaatje
 * er worden een flink aantal bestandsextensies herkend.
 * Terug gegeven wordt de bestandsnaam van het plaatje in de vorm van: file-avi.gif voor een avi file
 *
 * als $sFile='filmpje.avi', wordt het resultaat: 'file-avi.gif'
 *
 * @param string $sFile
* @return string met bestandsnaam van het plaatje dat bij de extensie hoort
 */
function getImageByExtension($sFile)
{
        if (is_dir($sFile))
        {
                return "file-folder.gif";
        }
        else
        {
                switch(getFileExtension(strtolower($sFile)))
                {
                        case "avi":
                                return "file-avi.gif";
                                break;
                        case "mpg":
                                return "file-avi.gif";
                                break;
                        case "mpeg":
                                return "file-avi.gif";
                                break;
                        case "mov":
                                return "file-avi.gif";
                                break;
                        case "bat":
                                return "file-bat.gif";
                                break;
                        case "dfm":
                                return "file-dfm.gif";
                                break;
                        case "gif":
                                return "file-gif.gif";
                                break;
                        case "dll":
                                return "file-dll.gif";
                                break;
                        case "dpr":
                                return "file-dpr.gif";
                                break;
                        case "exe":
                                return "file-exe.gif";
                                break;
                        case "com":
                                return "file-com.gif";
                                break;
                        case "gif":
                                return "file-gif.gif";
                                break;
                        case "htm":
                                return "file-htm.gif";
                                break;
                        case "html":
                                return "file-htm.gif";
                                break;
                        case "asp":
                                return "file-htm.gif";
                                break;
                        case "php":
                                return "file-htm.gif";
                                break;
                        case "pl":
                                return "file-htm.gif";
                                break;
                        case "ini":
                                return "file-ini.gif";
                                break;
                        case "jpg":
                                return "file-jpg.gif";
                                break;
                        case "jpeg":
                                return "file-jpg.gif";
                                break;
                        case "png":
                                return "file-jpg.gif";
                                break;
                        case "bmp":
                                return "file-jpg.gif";
                                break;
                        case "mdb":
                                return "file-mdb.gif";
                                break;
                        case "csv":
                                return "file-txt.gif";
                                break;
                        case "sql":
                                return "file-txt.gif";
                                break;
                        case "png":
                                return "file-txt.gif";
                                break;
                        case "java":
                                return "file-txt.gif";
                                break;
                        case "mp3":
                                return "file-mp3.gif";
                                break;
                        case "msi":
                                return "file-msi.gif";
                                break;
                        case "pas":
                                return "file-pas.gif";
                                break;
                        case "pdf":
                                return "file-pdf.gif";
                                break;
                        case "ppt":
                                return "file-ppt.gif";
                                break;
                        case "rar":
                                return "file-rar.gif";
                                break;
                        case "ttf":
                                return "file-ttf.gif";
                                break;
                        case "txt":
                                return "file-txt.gif";
                                break;
                        case "vsd":
                                return "file-vsd.gif";
                                break;
                        case "doc":
                                return "file-doc.gif";
                                break;
                        case "xls":
                                return "file-xls.gif";
                                break;
                        case "zip":
                                return "file-zip.gif";
                                break;
                        case "bmp":
                                return "file-bmp.gif";
                                break;
                        case "xml":
                                return "file-xml.gif";
                                break;
                        case "rtf":
                                return "file-rtf.gif";
                                break;
                        case "tar":
                                return "file-tar.gif";
                                break;
                        case "psd":
                                return "file-psd.gif";
                                break;
                        case "png":
                                return "file-png.gif";
                                break;
                        case "bmp":
                                return "file-bmp.gif";
                                break;

                        default:
                                return "file.gif";
                                break;
                }//einde case
        }//einde else is_dir
}


/**
 * load contents from a file in UTF8 format and put it in an array
 *
 * @param string $sFileName path of file to read
 * @return array
 */
function loadFromFile($sFileName)
{

    $arrFileContents = array();

    try
    {
        $sContents = file_get_contents($sFileName);
        if (isUTF8String($sContents))
            $sContents = file_get_contents($sFileName);
        else
            $sContents = utf8_decode(file_get_contents($sFileName));
            
        $arrFileContents = strToArr($sContents);
    }
    catch (Exception $objException)
    {
        error($objException);
        return false;
    }

    return $arrFileContents;
}

/**
 * saves the the array with strings to a file in UTF-8 format (detects and converts in necesary)
 * if file not exists it creates the file
 * 
 * @param array $arrContents array met strings die opgeslagen worden in het bestand
 * @param string $sFileName
 * @param string $sLineEndingCharacter
 * @param $iChmod unix style change access mode code: 777 all access, if -1 cmod will not be performed
 * @return bool succesful ?
 */
function saveToFile($arrContents, $sFileName, $sLineEndingCharacter = PHP_EOL, $iChmod = -1)
{
    try
    {
        if ($sFileName == '')
            throw new Exception('saveToFile(): supplied filename is empty!');

        $iFilePointer = fopen($sFileName, 'w'); //file openen of createn

        if ($iFilePointer != false)
        {
            //array naar file brengen
            foreach ($arrContents as $sLine)
            {
                $sLineWithEOF = $sLine.$sLineEndingCharacter;
                if (isUTF8String($sLineWithEOF))
                    fwrite($iFilePointer,$sLineWithEOF);
                else
                    fwrite($iFilePointer,utf8_encode($sLineWithEOF));
            }

            //file opslaan
            $bResult = fclose($iFilePointer);
            
            //rechten wijzigen
            if ($iChmod > -1)
            {
                if (!chmod($sFileName, $iChmod))
                {
                    $bResult = false;
                    throw new Exception('saveToFile(): chmod could not be performed, maybe lack of rights ???');
                }
            }

            return $bResult;
            
        }
        else
            throw new Exception('saveToFile(): file access denied (maybe a chmod helps)');

    }
    catch (Exception $objException)
    {
        error($objException);
        return false;
    }

}

/**
 * add a line of text to a file UTF8 safe
 * if file not exists it creates the file
 *
 * @param string $sLine line to add to the file
 * @param string $sFileName filepath to add the d
 * @param string $sLineEndingCharacter
 * @return bool writing ok ?
 */
function addToFile($sLine, $sFileName, $sLineEndingCharacter = "\n")
{
    try
    {
        $fp = fopen($sFileName, 'a');

        
        $sLineWithEOF = $sLine.$sLineEndingCharacter;
        if (isUTF8String($sLineWithEOF))
            fwrite($fp,$sLineWithEOF);
        else
            fwrite($fp,$sLineWithEOF);                    

        return fclose($fp);

    }
    catch (Exception $objException)
    {
        error($objException);
        return false;
    }

}

/**
 * saves a single string to a file UTF8-style
 *
 * @param string $sStringToWriteToFile
 * @param string $sFileName
 * @return bool true if ok
 */
function saveToFileString($sStringToWriteToFile, $sFileName)
{
    try
    {
        $fp = fopen($sFileName, 'w');

        if (isUTF8String($sStringToWriteToFile))
            fwrite($fp,$sStringToWriteToFile);           
        else
            fwrite($fp,utf8_encode($sStringToWriteToFile));

        return fclose($fp);

    }
    catch (Exception $objException)
    {
        error($objException);
        return false;
    }
}

/**
 * load a string from a file
 *
 * @param string $sStringToWriteToFile
 * @return UTF8 string from the file
 */
function loadFromFileString($sStringToWriteToFile)
{

    try
    {
        $sContents = file_get_contents($sStringToWriteToFile);
        if (isUTF8String($sContents))
            return $sContents;
        else
            return utf8_decode($sContents);
    }
    catch (Exception $objException)
    {
        error($objException);
        return false;
    }
}

/**
 * returns maximum upload size (wich is set in de php.ini) in bytes
 *
 * this function uses takes the post_max_size and upload_max_filesize of the php.ini in account
 *
 */
function getMaxFileUploadSize()
{
    $iResult = 0;
    
    $sMaxPostSize = ini_get('post_max_size');
    $iMaxPostSize = convertReadableBinairySizeToBytes($sMaxPostSize);
    $sMaxUploadSize = ini_get('upload_max_filesize');
    $iMaxUploadSize = convertReadableBinairySizeToBytes($sMaxUploadSize);
    
    $iResult = $iMaxUploadSize; //default

    if ($iMaxPostSize < $iResult) //als postsize kleiner, dan is dit het resultaat
        $iResult = $iMaxPostSize;

    return $iResult;
}

/**
 * add a path to the php.ini include path configuration for this website
 * @param string $sPath path to add to the includepath
 */
function addIncludePath($sPath)
{
    set_include_path(get_include_path() . PATH_SEPARATOR . $sPath);
}

/**
 * get the temporary directory
 *
 */
function getTempDir()
{
    $tmpdir = array();
    foreach (array($_ENV, $_SERVER) as $tab) {
            foreach (array('TMPDIR', 'TEMP', 'TMP', 'windir', 'SystemRoot') as $key) {
                    if (isset($tab[$key])) {
                            if (($key == 'windir') or ($key == 'SystemRoot')) {
                    $dir = realpath($tab[$key] . '\\temp');
                } else {
                    $dir = realpath($tab[$key]);
                }
                            if ($this->_isGoodTmpDir($dir)) {
                                    return $dir;
                            }
                    }
            }
    }
    $upload = ini_get('upload_tmp_dir');
    if ($upload) {
        $dir = realpath($upload);
            if ($this->_isGoodTmpDir($dir)) {
                    return $dir;
            }
    }
    if (function_exists('sys_get_temp_dir')) {
        $dir = sys_get_temp_dir();
            if ($this->_isGoodTmpDir($dir)) {
                    return $dir;
            }
    }
    // Attemp to detect by creating a temporary file
    $tempFile = tempnam(md5(uniqid(rand(), TRUE)), '');
    if ($tempFile) {
            $dir = realpath(dirname($tempFile));
        unlink($tempFile);
        if ($this->_isGoodTmpDir($dir)) {
            return $dir;
        }
    }

    throw new Exception('Could not determine temp directory');
}


/**
 * gets the contents of a website and returns it in an array
 *
 * @param string $sURL
 * @return array
 */
function loadFromUrl($sURL)
{
    return file($sURL);
}

/**
 * gets the contents of a website and returns it in a string
 *
 * @param string $sURL
 * @return string
 */
function loadFromUrlString($sURL)
{
    return file_get_contents($sURL);
}

/*****************************************************************

/**
 * read the text from a Microsoft Word file
 *
I don't pretend that it makes a complete success of extracting the text from all Word documents, but I've found it very reliable for the vast majority of the several thousand docs I've used it with. The function returns text from the Word document as a string,&nbsp;with all the formatting removed. Please note that some parts of the Word document (header, footer etc) are not parsed.</P><FONT color=#cc0000><XMP><?php

 * This approach uses detection of NUL (chr(00)) and end line (chr(13))
to decide where the text is:
- divide the file contents up by chr(13)
- reject any slices containing a NUL
- stitch the rest together again
- clean up with a regular expression
 * 
 * @param string $sWordFile
 * @return string text from Microsoft Word file
 */
function getTextMSWordDcoumentNonXML($sWordFile)
{
    $fileHandle = fopen($sWordFile, "r");
    $line = @fread($fileHandle, filesize($sWordFile));
    $lines = explode(chr(0x0D),$line);
    $outtext = "";
    foreach($lines as $thisline)
      {
        $pos = strpos($thisline, chr(0x00));
        if (($pos !== FALSE)||(strlen($thisline)==0))
          {
          } else {
            $outtext .= $thisline." ";
          }
      }
    $outtext = preg_replace("/[^a-zA-Z0-9\s\,\.\-\n\r\t@\/\_\(\)]/","",$outtext);
    return $outtext;
}



/**
 * function write_ini_file()
 * 
 * the counterpart for the php function parse_ini_file to write the contents of the associative array to an ini file
 * 
 * @param array $assoc_arr
 * @param string $path
 * @param string $has_sections
 * @return boolean|number
 */
function write_ini_file($assoc_arr, $path, $has_sections=FALSE) {
	$content = '';
	if ($has_sections) {
		foreach ( $assoc_arr as $key => $elem ) {
			$content .= '[' . $key . "]\n";
			foreach ( $elem as $key2 => $elem2 ) {
				if (is_array ( $elem2 )) {
					for($i = 0; $i < count ( $elem2 ); $i ++) {
						$content .= $key2 . '[] = "' . $elem2 [$i] . "\"\n";
					}
				} else if ($elem2 == '')
					$content .= $key2 . " = \n";
				else
					$content .= $key2 . ' = "' . $elem2 . "\"\n";
			}
			$content .= "\n";
		}
	} else {
		foreach ( $assoc_arr as $key => $elem ) {
			if (is_array ( $elem )) {
				for($i = 0; $i < count ( $elem ); $i ++) {
					$content .= $key . '[] = "' . $elem [$i] . "\"\n";
				}
			} else if ($elem == '')
				$content .= $key . " = \n";
			else
				$content .= $key . ' = "' . $elem . "\"\n";
		}
	}
	
	if (! $handle = fopen ( $path, 'w' )) {
		return false;
	}
	
	$success = fwrite ( $handle, $content );
	fclose ( $handle );
	
	return $success;
}

/*========================================================================================================================\
* name				: includeAll
* programmer name 	: 
* date 				: 28 juni 2005
* input				: $dir: string - wich directory to include ? ;$ext - file extensie
* output			: 
* use for			: het includen van alle files in een directory met een bepaalde extensie
* description		: 
\========================================================================================================================*/   
function includeAll($sDir, $ext = '.php')
{
    $strDH = opendir($sDir);
   
    while(false !== ($file = readdir($strDH)))
    if (strpos($file, $ext) != false)
    {
        include_once($sDir.$file);
    }
    closedir($strDH);
}

/**
 * for uploading files with a html form:
 * this function rearranges the array in 1 file per array element
 * 
 * The original format is:
 * Array
(
    [name] => Array
        (
            [0] => foo.txt
            [1] => bar.txt
        )

    [type] => Array
        (
            [0] => text/plain
            [1] => text/plain
        )

    [tmp_name] => Array
        (
            [0] => /tmp/phpYzdqkD
            [1] => /tmp/phpeEwEWG
        )

    [error] => Array
        (
            [0] => 0
            [1] => 0
        )

    [size] => Array
        (
            [0] => 123
            [1] => 456
        )
)
 * 
 * this function converts that array to a 1 element per file:
 * Array
(
    [0] => Array
        (
            [name] => foo.txt
            [type] => text/plain
            [tmp_name] => /tmp/phpYzdqkD
            [error] => 0
            [size] => 123
        )

    [1] => Array
        (
            [name] => bar.txt
            [type] => text/plain
            [tmp_name] => /tmp/phpeEwEWG
            [error] => 0
            [size] => 456
        )
)
 * @author some dude on php.net https://www.php.net/manual/en/features.file-upload.multiple.php
 * @param string $file_post
 * @return array
 */
function uploadFilesRearrangeArray(&$file_post) 
{

        $file_ary = array();
        $file_count = count($file_post['name']);
        $file_keys = array_keys($file_post);
    
        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $file_ary[$i][$key] = $file_post[$key][$i];
            }
        }
    
        return $file_ary;
}

/**
 * creates a temporary file in the temp folder 
 * (don't forget to delete it after using it)
 * 
 * some dude @ https://stackoverflow.com/questions/1707801/making-a-temporary-dir-for-unpacking-a-zipfile-into
 * 
 * @return string
 */
function tempdir() 
{
        $tempfile=tempnam(sys_get_temp_dir(),'');
        // you might want to reconsider this line when using this snippet.
        // it "could" clash with an existing directory and this line will
        // try to delete the existing one. Handle with caution.
        if (file_exists($tempfile)) { unlink($tempfile); }
        mkdir($tempfile);
        if (is_dir($tempfile)) { return $tempfile; }
}

/**
 * copy a file or directory
 * if directory it will be done recursively
 * 
 * 
 * @param string $sSource source file or directory
 * @param string $sDestination destination file or directory
 */
function copyRecursive($sSource, $sDestination)
{
        $bResult = true;
        $arrFiles = getFileFolderArray($sSource);
        $sFullSourcePath = '';
        
        if ($arrFiles)
        {
                foreach ($arrFiles as $sFile)
                {
                        $sFullSourcePath = $sSource.DIRECTORY_SEPARATOR.$sFile;
                        $sFullDestinationPath = $sDestination.DIRECTORY_SEPARATOR.$sFile;
                        if (is_dir($sFullSourcePath))
                        {
                                if (!is_dir($sFullDestinationPath)) //if directory already exists, dont create it
                                        mkdir($sFullDestinationPath); //create destination directory

                                if (copyRecursive($sFullSourcePath, $sFullDestinationPath) === false)
                                        $bResult = false;
                        }
                        else
                        {
                                if (copy($sFullSourcePath, $sFullDestinationPath) === false)
                                        $bResult = false;
                        }
                }
        }
        
        return $bResult;
}


/**
 * Copy a file, or recursively copy a folder and its contents
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @param       int      $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
// function xcopy($source, $dest, $permissions = 0755)
// {




//     $sourceHash = xcopyhashDirectory($source);
//     // Check for symlinks
//     if (is_link($source)) {
//         return symlink(readlink($source), $dest);
//     }

//     // Simple copy for a file
//     if (is_file($source)) {
//         return copy($source, $dest);
//     }

//     // Make destination directory
//     if (!is_dir($dest)) {
//         mkdir($dest, $permissions);
//     }

//     // Loop through the folder
//     $dir = dir($source);
//     while (false !== $entry = $dir->read()) {
//         // Skip pointers
//         if ($entry == '.' || $entry == '..') {
//             continue;
//         }

//         // Deep copy directories
//         if($sourceHash != xcopyhashDirectory($source."/".$entry)){
//              xcopy("$source/$entry", "$dest/$entry", $permissions);
//         }
//     }

//     // Clean up
//     $dir->close();
//     return true;
// }


//         // In case of coping a directory inside itself, there is a need to hash check the directory otherwise and infinite loop of coping is generated
//         function xcopyhashDirectory($directory){
//                 if (! is_dir($directory)){ return false; }
        
//                 $files = array();
//                 $dir = dir($directory);
        
//                 while (false !== ($file = $dir->read())){
//                 if ($file != '.' and $file != '..') {
//                         if (is_dir($directory . '/' . $file)) { $files[] = xcopyhashDirectory($directory . '/' . $file); }
//                         else { $files[] = md5_file($directory . '/' . $file); }
//                 }
//                 }
        
//                 $dir->close();
        
//                 return md5(implode('', $files));
//         }

/**
 * recursive version of rename for directories
 *
 * @param string $sSource
 * @param string $sDestination
 * @return bool
 */
function renameRecursive($sSource, $sDestination)
{
        $bResult = true;
        if (copyRecursive($sSource, $sDestination) === false)
                $bResult = false;

        if ($bResult)
        {
                if (rmdirrecursive($sSource) === false)
                        $bResult = false;
        }

        return $bResult;
}

/**
 * create .htaccess file in directory with contents $sContent
 * 
 * if not exists, create one.
 * if exists overwrite
 * @param string $sDirectory
 * @param bool $bOverwrite overwrite existing htaccess when it exists?
 * @param string $sContent
 * @return bool error
 */
function createHtaccessFile($sDirectory, $bOverwrite = false, $sContent = 'Deny from all')
{
        $sFile = $sDirectory.DIRECTORY_SEPARATOR.'.htaccess';

        //if file exists and may not be overwritten, do nothing
        if (file_exists($sFile) && (!$bOverwrite))
                return true;
        
        //create htaccess that blocks access to dir
        $fhHtaccess = fopen($sFile, 'w'); 
        if ($fhHtaccess !== false)
        {
                fwrite($fhHtaccess, $sContent);
                fclose($fhHtaccess);
                return true;
        }
        else
                return false;
}

?>
