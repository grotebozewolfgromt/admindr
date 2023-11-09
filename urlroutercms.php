<?php
/**
 * url router
 * controller instantiator cms5
 * 
 * this is NOT a class for the sake a speed
 * 
 * I also want to include AS LITTLE libraries as possible for the sake of speed
 */

//session started in bootstrap
// include_once 'bootstrap.php';
include_once 'bootstrap_cms.php';

include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_sys_inet.php');


$sURLPath = '';
$arrURLPath = array();
$iCountURLPathLength = 0;
$sModule = '';
$sController = '';
$sControllerPath = '';
$sControllerClass = '';
$bIsModule = false; //is cms or module

//==== inspect the url  on length
//and determine the module and controller
$sURLPath = ltrimLiteral(getURLThisScript(), GLOBAL_PATH_WWW_CMS.'/'); //GLOBAL_PATH_WWW_CMS and getURLThisScript() should be the same for the first part
$sURLPath = explode('?', $sURLPath)[0]; //strip parameters
$arrURLPath = explode('/', $sURLPath);
$iCountURLPathLength = count($arrURLPath);


switch ($iCountURLPathLength)
{
    case 1: //controller in root (this includes the index that is an empty element in the array)
        $sController = $arrURLPath[0];
        $bIsModule = false;
        break;
    case 3: //controller in module
        if ($sModulesDirectory == $arrURLPath[0])
        {
            $sController = $arrURLPath[2];
            $sModule = $arrURLPath[1];
            $bIsModule = true;
        }
        break;
    default:
        include 'error404.php';
        die();
}


//==== index

//index cms
if (($sController == '') and ($sModule == ''))
{
    include 'index.php';
    die();
}

//index module
if (($sController == '') and ($sModule != ''))
{
    include GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$sModule.DIRECTORY_SEPARATOR.'index.php';
    die();
}



if (!$bIsModule) // is cms
{
    include GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.$sController.'.php';
    die();
}
else //is module
{
    //==== instantiate proper controller
    $sControllerClass = '';
    $sController = preg_replace( '/[^abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_]/', '', $sController ); //filter for security reasons to prevent things like directory traversal   
    // $sController = filterBadCharsWhiteList($sController, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_'); //filter for security reasons to prevent things like directory traversal
    $sModule = preg_replace( '/[^abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_]/', '', $sModule ); //filter for security reasons to prevent things like directory traversal   
    // $sModule = filterBadCharsWhiteList($sModule, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_'); //filter for security reasons to prevent things like directory traversal
    $sControllerClass = 'dr\\modules\\'.$sModule.'\\controllers\\'.$sController;

    $objController = new $sControllerClass();
}

?>