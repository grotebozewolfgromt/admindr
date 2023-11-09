<?php

/**
 * cronjob
 */

namespace dr\modules\Mod_Sys_Settings\controllers;


use dr\classes\controllers\TControllerAbstract;


//don't forget ;)
use dr\modules\Mod_Sys_Settings\Mod_Sys_Settings;


include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


/**
 * Description of themes overview:
 * 
 * see what themes are available to the system
 * 
 * this reads the 'all-themes' folder inside the 'themes' folder
 *
 * @author drenirie
 */
class settings_themes_overview extends TControllerAbstract
{    
    const ARRAYKEY_GET_INSTALLTHEMENAME = 'install'; //array key in $_GET[];

    public function __construct()
    {
        $this->setCachedEnabled(false);
        parent::__construct();
    }


    /**
     * This function adds EARLY BINDING variables which are cached (if cache enabled)
     * (see description on top of this class for more info)
     * 
     * this is the gold-old-fashion way of doing php with regular php variables etc.
     * 
     * executes the things you want to cache
     * this function is ONLY called on a cache miss 
     * (if caching enabled, if NOT enabled it's ALWAYS called).
     * This function generates content for the cache file and for displaying on-screen
     * 
     * this function is executed BEFORE executeLateBinding(), because it's early binding
     * 
     * @return array with variables, use: "return get_defined_vars();" to use all variables declared in the execute() function
     */
    public function executeEarlyBinding()
    {
        global $objCurrentModule;
        global $sCurrentModule;


        //install 
        if (isset($_GET[settings_themes_overview::ARRAYKEY_GET_INSTALLTHEMENAME])) //only install when parameter is given
        {
            
            if(auth($sCurrentModule, Mod_Sys_Settings::PERM_CAT_THEMES, AUTH_OPERATION_CHANGE))
            {
                if (!installTheme($_GET[settings_themes_overview::ARRAYKEY_GET_INSTALLTHEMENAME], GLOBAL_PATH_LOCAL_CMS_THEMES_CURRENTTHEME, GLOBAL_PATH_LOCAL_CMS_THEMES_PREVIOUSTHEME, GLOBAL_PATH_LOCAL_CMS_THEMES_ALLTHEMES))
                    sendMessageError(transm($sCurrentModule, 'message_themes_overview_installfailed','Installation of theme "[themename]" FAILED!', 'themename', $_GET[settings_themes_overview::ARRAYKEY_GET_INSTALLTHEMENAME]));                    
                else
                    sendMessageSuccess(transm($sCurrentModule, 'message_themes_overview_installsuccess','Installation "[themename]" succesful. Clear browser cache if not visible.', 'themename', $_GET[settings_themes_overview::ARRAYKEY_GET_INSTALLTHEMENAME]));
            }
            else
                sendMessageError(transm($sCurrentModule, 'error_themes_overview_installfailed_notauthorised','You are not authorised to install themes'));
        }

        //request dirs (after the install is done to take account for the changed directory names)
        $objArrThemes = getFileFolderArray(GLOBAL_PATH_LOCAL_CMS_THEMES_ALLTHEMES, true, false);


        $sTitle = transm($sCurrentModule, 'pagetitle_themes_overview', 'Themes');   
        $sHTMLTitle = $sTitle;
        $sHTMLMetaDescription = $sTitle;    
        $arrTabsheets = $objCurrentModule->getTabsheets(); 

        return get_defined_vars();
    }

    /**
     * This function adds LATE BINDING variables which are NOT cached 
     * (for more info: see description on top of this class)
     * 
     * executes the things you always want to execute, even on a cache miss
     * executeEarlyBinding() is executed first, then executeLateBinding()
     *  
     * These variables that aren't resolved by php in the cache file
     * This way you can add dynamic php code to an otherwise cached page
     * 
     * These late binding variables need to be in the following format in the template: [variablename]
     * (Otherwise PHP will resolve variables in thecachefile with the format: $variablename)
     * 
     * This function is executed AFTER executeEarlyBinding()
     * 
     * @return array with variables, use: "return get_defined_vars();" to use all variables declared in the execute() function
     */
    public function executeLateBinding()
    {
        return;
    }


    /**
     * return path of the page template
     *
     * @return string
     */
    public function getTemplatePath()
    {
        global $sCurrentModule;
        return GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$sCurrentModule.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpl_settings_themes_overview.php';
    }

    /**
     * return path of the skin template
     * 
     * return '' if no skin
     *
     * @return string
     */
    public function getSkinPath()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withmenu.php';
    }



}
