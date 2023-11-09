<?php
/**
 * In this library alle the cms specific functions
 *
 * 27 april 2019: created
 * @author Dennis Renirie
 */


/**
 * translate originaltext for CMS
 * It uses the languagefiles of the cms
 * 
 * if language content not found, it returns the $sOriginalText
 * @param string $sUniqueKey 
 * @param string $sDefaultEnglishTranslation
 */ 
function transcms($sUniqueKey, $sDefaultEnglishTranslation = '', $sVariable1 = '', $sValue1 = '', $sVariable2 = '', $sValue2 = '', $sVariable3 = '', $sValue3 = '')
{
    global $objTranslationCMS;
// vardump($objTranslationCMS);   
    if ($objTranslationCMS)
    {          
        return $objTranslationCMS->translate($sUniqueKey, $sDefaultEnglishTranslation, $sVariable1, $sValue1, $sVariable2, $sValue2, $sVariable3, $sValue3);
    }
    else
    {
    	if ($sDefaultEnglishTranslation == '')    		
       	 	return $sUniqueKey;
    	else
    		return $sDefaultEnglishTranslation;
    }    
}


/**
 * return permissions array
 * this function is the same as a TModuleAbstract->getPermissions(),
 * but instead of a module a applies to the CMS specific 
 *
 * @return array
 */
function getPermissionsCMS()
{
    return array(
        AUTH_CATEGORY_SYSSETTINGS => array (  
                                            AUTH_OPERATION_SYSSETTINGS_VIEW
                                        ),
        AUTH_CATEGORY_SYSSITES => array(
                                            AUTH_OPERATION_SYSSITES_VISIBILITY,
                                            AUTH_OPERATION_SYSSITES_SWITCH
                                        )
        );   
}

/**
 * render templates for an "access denied message"
 *
 * @return void
 */
function showAccessDenied($sExtraMessage = '')
{
    global $objLoginController;

    $sHTMLContentMain = '';
    $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_accessdenied.php', get_defined_vars());

    $sHTMLTitle = 'access denied';
    $sHTMLMetaDescription = 'access denied';

    error_log('showAccessDenied(): showed access denied to user with message: "'.$sExtraMessage.'"');

    //determine skin
    $sSkin = 'skin_withoutmenu.php';
    // if ($objLoginController->getIsLoggedIn()) --> doesn't work because it can be handled before variables are defined (so errors in loggedinskin showed)
    //     $sSkin = 'skin_withmenu.php';


    echo renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.$sSkin, get_defined_vars());
   
}



?>
