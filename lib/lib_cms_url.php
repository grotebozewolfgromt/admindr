<?php
/**
 * In this library urls of the framework 
 * NOT THE WEBSITES connected to the framework.
 * websites connected to the framework have their own custom lib_url
 * 
 * lookup urls so it is really easy to change locations of file
 * if you can use a global constant, do that!
 * but sometimes you need business logic over and over again to generate an url. 
 * for example: a weblog that is in the footer, on the left and on a separate page.
 * use 1 function to generate detail-pages of the weblog.
 *
 * 
 * EXAMPLE
 * function getURLPlaats($sPrettyUrlTitlePlaats)
 * {
 *      return GLOBAL_PATH_WWW.'/'.'plaats/'.$sPrettyUrlTitlePlaats.'';
 * }   
 *
 * 10 mei 2019 getModuleFromURL() bugfix, geen controle op directory
 * 12 dec 2021: lib_cms_url: getModuleFromURL() filter op illegale karakters
 * 
 * @author Dennis Renirie
 */

//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_date.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_img.php'); 
include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_sys_inet.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_math.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php');
//include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_string.php');
        

/**
 * return the name of the module by looking at the current url
 * 
 * @return string
 */
function getModuleFromURL()
{
    $arrDirsCurrURL = array();
    $arrBasePathURL = array();
    $sURL = '';
    $iCountBasePath = 0;
    $iCountDirsCurr = 0;
    $sModuleName = '';
    
    
    /***
     * WHAT AM I TRYING TO DO HERE?
     * example: 
     * if this is url: https://www.bedrijfsuitje.events/admindr/modules/Mod_Sys_Localisation/index.php
     * then this is basepath-url of modules: https://www.bedrijfsuitje.events/admindr/modules
     * in other words: I know that the string thing AFTER the 'modules' part is the name of the module
     * count module-basepath array = 5
     * cound van de url = 7
     * because i know the modulebasepath is part of the current url
     * i know that the index of the urlarray with the modulename = count of the modulebasepath = 5
     */
    
    
    $arrDirsCurrURL = explode('/', getURLThisScript()); //performance test show that explode is faster than substr() by a ratio 1 to 10
    $arrBasePathURL = explode('/', GLOBAL_PATH_WWW_MODULES); //performance test show that explode is faster than substr() by a ratio 1 to 10
//tracepoint('gloinkasdf'.GLOBAL_PATH_WWW_MODULES. ' -- '.getURLThisScript())          ;
    $iCountBasePath = count($arrBasePathURL);
    $iCountDirsCurr = count($arrDirsCurrURL);          
    

    if ($iCountDirsCurr > $iCountBasePath) //checken of the current directory uberhaupt wel groter is dan van de basepath, anders geen modulenaam teruggeven
    {
        //performance test show that this code is 8x faster on average
        $bStructureEqual = true;
        for ($iIndex = 0; $iIndex < $iCountBasePath; $iIndex++)
        {
           if ($arrDirsCurrURL[$iIndex] !=  $arrBasePathURL[$iIndex])
               $bStructureEqual = false;
        }

        if ($bStructureEqual)
            $sModuleName = $arrDirsCurrURL[$iCountBasePath];

        //faster than this code
//            if (stristr(getURLThisScript(), GLOBAL_PATH_WWW_MODULES) != '')
//                $sModuleName = $arrDirsCurrURL[$iCountBasePath];
    }

    $sModuleName = filterBadCharsWhiteList($sModuleName, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_'); //filter for security reasons to prevent things like directory traversal

    
    return $sModuleName;
}


function getURLCMSSettings()
{
    // return GLOBAL_PATH_WWW_CMS.'/settings.php';
    return GLOBAL_PATH_WWW_MODULES.'/Mod_Sys_Settings/';
}

function getURLCMSDashboard()
{
    return GLOBAL_PATH_WWW_CMS.'/dashboard';
}

function getURLCMSCronjob()
{
    return GLOBAL_PATH_WWW_CMS.'/cronjob?'.ACTION_VARIABLE_ID.'='.GLOBAL_CRONJOBID;
}

function getURLCMSLogin()
{
    return GLOBAL_PATH_WWW_CMS.'/login';
}

function getPathModuleImages($sModuleName)
{
    return GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$sModuleName.DIRECTORY_SEPARATOR.'images';
}

function getURLModuleImages($sModuleName)
{
    return GLOBAL_PATH_WWW_MODULES.'/'.$sModuleName.'/images';
}

function getPathModule($sModuleName)
{
    return GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$sModuleName;
}

function getURLModule($sModuleName)
{
    return GLOBAL_PATH_WWW_MODULES.'/'.$sModuleName;
}

function getURLPasswordRecoverEnterEmail()
{
    return GLOBAL_PATH_WWW_CMS.'/passwordrecover_enteremail';
}

function getURLPasswordRecoverEnterNewPassword()
{
    return GLOBAL_PATH_WWW_CMS.'/passwordrecover_enternewpassword';
}

function getURLCreateAccountEnterCredentials()
{
    return GLOBAL_PATH_WWW_CMS.'/createaccount_entercredentials';
}

function getURLCreateAccountEmailConfirm()
{
    return GLOBAL_PATH_WWW_CMS.'/createaccount_emailconfirmed';
}
?>
