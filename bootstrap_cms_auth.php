<?php

    use dr\classes\models\TModel;
    use dr\classes\controllers\TCMSLoginController;
    use dr\classes\models\TSysCMSUsers;
    
/**
 * BOOTSTRAP_CMS_AUTH
 * ===================
 * bootstrap file that includes the cms cms bootstrap but WITH AUTHORISATION CHECK if user is logged in
 * 
 * include this file if you want to have username and password checked!
 * 
 * created: 9 april 2019 Dennis Renirie
 */
	
    include_once(__DIR__.DIRECTORY_SEPARATOR.'bootstrap_cms.php');          
   
    //======================= MAKING VARIABLES GLOBAL ==============
    //when you use a class via the autoloader which needs bootstrap_cms_auth,
    //variables declared in this file are not globally available and global variables are not available
    //The code below makes them globally available
        global $objLoginController;
        global $objWebsites;
        global $objLocale;
        global $objCountrySettings;
        global $objTranslationSystem;
        global $objTranslationCMS;
        global $arrTranslationsModules;
        global $objTranslationWebsite;


    //================= AUTHORISE USER ==================
    $objLoginController = new TCMSLoginController();    
    $objLoginController->handleAuthentication();  

    


    
    //================= WEBSITES =================================
    //we always need a list of websites so we can switch websites by clicking on the combobox on the left side of the screen
        $iTempCMS5SelectedSiteID = GLOBAL_DB_SITEID;//cms5: default that is gonna be overwritten
        $iTempCMS5LanguageIDSelectedSite = 0;
                
        $objWebsites = new dr\classes\models\TSysWebsites();
        $objWebsites->loadFromDB();    //===> the list of websites on the left side of the screen so we can switch between them

        //the selected site is set on login
        
        //====SELECTED SITE DEFAULT (if something went wrong with login)
        //we need to prefer the session over the cookie (for security reasons)
        //due to backwards compatibility with cms2/4 the session is always used, cookie is ignored, only when session expires, the cookie is used to fill the session array
        
        if (!isset($_SESSION[SESSIONARRAYKEY_SELECTEDSITEID])) //to be sure we have always a selected site (a session can expire)
        {
            if (!isset($_COOKIE[COOKIEARRAYKEY_SELECTEDSITEID]))//cookie not set
            {
                $iTempCMS5SelectedSiteID = GLOBAL_DB_SITEID;
                $_SESSION[SESSIONARRAYKEY_SELECTEDSITEID] = GLOBAL_DB_SITEID; //make sure there is always a session with a site id for cms5
                sendMessageNotification(transcms('changed_website_default', 'you changed to defaultwebsite'));
            }
            else //if cookie set
            {
                if (is_numeric($_COOKIE[COOKIEARRAYKEY_SELECTEDSITEID]))
                {
                    $iTempCMS5SelectedSiteID = $_COOKIE[COOKIEARRAYKEY_SELECTEDSITEID];              
                    $_SESSION[SESSIONARRAYKEY_SELECTEDSITEID] = $_COOKIE[COOKIEARRAYKEY_SELECTEDSITEID];
                }
                else //assume defaults
                {
                    $iTempCMS5SelectedSiteID = GLOBAL_DB_SITEID;
                    $_SESSION[SESSIONARRAYKEY_SELECTEDSITEID] = GLOBAL_DB_SITEID;
                    sendMessageNotification(transcms('changed_website_default', 'you changed to defaultwebsite'));
                }
            }

        }
        else //session exists
        {
            $iTempCMS5SelectedSiteID = $_SESSION[SESSIONARRAYKEY_SELECTEDSITEID];
        }
         
    
        
                
        //====CHANGE SELECTED SITE
        if (isset($_GET[GETARRAYKEY_SELECTEDSITEID]))
        {
            if (auth(AUTH_MODULE_CMS, AUTH_CATEGORY_SYSSITES, AUTH_OPERATION_SYSSITES_SWITCH))
            {
                if (is_numeric($_GET[GETARRAYKEY_SELECTEDSITEID]))
                {
                    $iTempCMS5SelectedSiteID = $_GET[GETARRAYKEY_SELECTEDSITEID];
                    
                    if (isset($_SESSION[SESSIONARRAYKEY_SELECTEDSITEID]))
                    {
                        $_SESSION[SESSIONARRAYKEY_SELECTEDSITEID] = $_GET[GETARRAYKEY_SELECTEDSITEID];
                        sendMessageNotification(transcms('changed_website', 'you changed website'));
                        if (isset($_SESSION[SESSIONARRAYKEY_SELECTEDLANGUAGEID]))//make sure the languageid is set later
                            unset($_SESSION[SESSIONARRAYKEY_SELECTEDLANGUAGEID]) ;                           
                    }
                    
                    if (isset($_COOKIE[COOKIEARRAYKEY_SELECTEDSITEID]))
                    {
                        if (!setcookie(COOKIEARRAYKEY_SELECTEDSITEID, $_GET[GETARRAYKEY_SELECTEDSITEID], time() + (DAY_IN_SECS * GLOBAL_COOKIE_EXPIREDAYS), '/', GLOBAL_PATH_DOMAIN, GLOBAL_ISHTTPS, true)) // 86400 = 1 day
                            error_log(__FILE__.' '.__LINE__.' :siteid cookie not set');
                        if (isset($_SESSION[SESSIONARRAYKEY_SELECTEDLANGUAGEID]))//make sure the languageid is set later
                            unset($_SESSION[SESSIONARRAYKEY_SELECTEDLANGUAGEID]) ;                       
                    }
                        
                }
                else //use defaults
                {
                    $iTempCMS5SelectedSiteID = GLOBAL_DB_SITEID;
                    $_SESSION[SESSIONARRAYKEY_SELECTEDSITEID] = GLOBAL_DB_SITEID;//@todo remove cms2/4 compatibility
                    if (isset($_SESSION[SESSIONARRAYKEY_SELECTEDLANGUAGEID]))//make sure the languageid is set later
                        unset($_SESSION[SESSIONARRAYKEY_SELECTEDLANGUAGEID]) ;                   
                }
            }
        }       
        //END====SELECTED SITE  
        
        
        //===== default language id for the selected site
        if (!isset($_SESSION[SESSIONARRAYKEY_SELECTEDLANGUAGEID]))//if default site language is not set in session
        {
            //look it up in the websites object
            $objWebsites->setRecordPointerToValue(dr\classes\models\TSysWebsites::FIELD_ID, $iTempCMS5SelectedSiteID);
            $iTempCMS5LanguageIDSelectedSite = $objWebsites->getDefaultLanguageID();
            $_SESSION[SESSIONARRAYKEY_SELECTEDLANGUAGEID] = $iTempCMS5LanguageIDSelectedSite; //save in session
        }        
        else //if set, read out of session, so we can store it later in a global constant
        {
            $iTempCMS5LanguageIDSelectedSite = $_SESSION[SESSIONARRAYKEY_SELECTEDLANGUAGEID];
        }
        //END===== default language id for the selected site
        
        
        
        define('GLOBAL_WEBSITEID_SELECTEDINCMS', $iTempCMS5SelectedSiteID); //new for cms5
        define('GLOBAL_LANGUAGEID_SELECTEDSITE', $iTempCMS5LanguageIDSelectedSite); //new for cms5
        unset($iTempCMS5SelectedSiteID); //prevent accessing it by accident
        unset($iTempCMS5LanguageIDSelectedSite); //prevent accessing it by accident


        

    //================== DEFINE LANGUAGE FILES =================== 
    //@TODO Locale, countrysettings en language files cachen
    //objects are created in bootstrap.php 
    //files of language paths are defined by default in bootstrap.php
    //now we are gonna override the paths with values of the user

        $sTempLocale = '';
        $sTempLocale = $objLoginController->getLanguages()->getLocale();
        
    //defining locale
        $objLocale->setLocale($sTempLocale);
        $objCountrySettings = new dr\classes\locale\TCountrySettings();//will be autoloaded when needed            
        $objCountrySettings->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'countrysettings_'.$sTempLocale.'.ini');

        setlocale(LC_TIME, $sTempLocale);


    //defining translation files
        $objTranslationSystem = new dr\classes\locale\TTranslation(); //will be autoloaded when needed
        $objTranslationSystem->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'system_translation_'.$sTempLocale.'.txt');
        $objTranslationCMS = new dr\classes\locale\TTranslation();//will be autoloaded when needed
        $objTranslationCMS->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'cms_translation_'.$sTempLocale.'.txt');        
        $arrTranslationsModules = array(); //--> 1d array with TTranslation objects of all modules. (key is the name of the module). the objects are created and added upon request in transm();
        //$objTranslationModule = new dr\classes\locale\TTranslation();//will be autoloaded when needed
        //$objTranslationModule->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'module_translation_'.$sTempLocale.'.txt');        
        $objTranslationWebsite = new dr\classes\locale\TTranslation();//will be autoloaded when needed
        $objTranslationWebsite->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'website_translation_'.$sTempLocale.'.txt');        

        
        unset($sTempLocale); //prevent using by accident
 
    
?>