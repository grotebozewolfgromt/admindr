<?php
/**
 * BOOTSTRAP
 * 
 * boots the framework:
 * -includes config file
 * -sets constants and defaults, EVEN if the config file is corrupt!
 * -sets database connection
 * -starts session
 * -reads modules
 * -and much more
 * 
 * if you want authorised access to a page in the cms, include bootstrap_cms_auth (it will include all bootstraps nessesary)
 * 
 * april 2019 Dennis Renirie
 */
	include_once(__DIR__.DIRECTORY_SEPARATOR."config.php");   
        
        

        //================== DEFINING CONSTANTS (and defaults) ===================        

        //DATABASE
            if (!isset($sDBHost))
                $sDBHost = 'localhost';
            define('GLOBAL_DB_HOST', $sDBHost);

            if (!isset($sDBUser))
                $sDBUser = '';
            define('GLOBAL_DB_USER', $sDBUser);

            if (!isset($sDBPassword))
                $sDBPassword = '';
            define('GLOBAL_DB_PASSWORD', $sDBPassword);

            if (!isset($sDBDatabase))
                $sDBDatabase = '';
            define('GLOBAL_DB_DATABASE', $sDBDatabase);  

            if (!isset($sDBTablePrefix))
                $sDBTablePrefix = 'tbl';
            define('GLOBAL_DB_TABLEPREFIX', $sDBTablePrefix);   
        
            if (!isset($iDBSiteID))
                $iDBSiteID = 1;
            define('GLOBAL_DB_SITEID', $iDBSiteID);   
            
            if (!isset($sDBConnectionClass))
                $sDBConnectionClass = 'dr\classes\db\TDBConnectionMySQL';
            define('GLOBAL_DB_CONNECTIONCLASS', $sDBConnectionClass);   
            
            if ((!isset($iDBPort)) || (!is_numeric($iDBPort)))
                $iDBPort = 3306;            
            define('GLOBAL_DB_PORT', $iDBPort);              
            
            
        //GOOGLE SHIZZLE: RECAPTCHA, GOOGLE CLIENT etc.
            if (!isset($sGoogleRecaptchaV2SiteKey))
                $sGoogleRecaptchaV2SiteKey = '';
            define('GLOBAL_GOOGLE_RECAPTCHAV2_SITEKEY', $sGoogleRecaptchaV2SiteKey);          

            if (!isset($sGoogleRecaptchaV2SecretKey))
                $sGoogleRecaptchaV2SecretKey = '';
            define('GLOBAL_GOOGLE_RECAPTCHV2_SECRETKEY', $sGoogleRecaptchaV2SecretKey);          

            if (!isset($sGoogleRecaptchaV3SiteKey))
                $sGoogleRecaptchaV3SiteKey = '';
            define('GLOBAL_GOOGLE_RECAPTCHAV3_SITEKEY', $sGoogleRecaptchaV3SiteKey);          

            if (!isset($sGoogleRecaptchaV3SecretKey))
                $sGoogleRecaptchaV3SecretKey = '';
            define('GLOBAL_GOOGLE_RECAPTCHV3_SECRETKEY', $sGoogleRecaptchaV3SecretKey);          
            
            if (!isset($bGoogleRecaptchaV3Use)) 
                $bGoogleRecaptchaV3Use = false; //default disallow recaptha
            define('GLOBAL_GOOGLE_RECAPTCHAV3_USE', $bGoogleRecaptchaV3Use);

            //google api key
            if (!isset($sGoogleAPIKey)) 
                $sGoogleAPIKey = ''; 
            define('GLOBAL_GOOGLEAPI_KEY', $sGoogleAPIKey);

            //client id: for google api
            if (!isset($sGoogleAPIClientID)) 
                $sGoogleAPIClientID = ''; 
            define('GLOBAL_GOOGLEAPI_CLIENTID', $sGoogleAPIClientID);

            //client secret: for google api
            if (!isset($sGoogleCientSecret)) 
                $sGoogleCientSecret = ''; 
            define('GLOBAL_GOOGLEAPI_CLIENTSECRET', $sGoogleAPIClientSecret);

            //client scopes (what do you want to get access to as application)
            if (!isset($arrGoogleAPICientScopes)) 
                $arrGoogleAPICientScopes = array(); 
            define('GLOBAL_GOOGLEAPI_SCOPES', $arrGoogleAPICientScopes);

            
        //EMAIL ADMIN
            if (!isset($sEmailAdmin))
                $sEmailAdmin = '';
            define('GLOBAL_EMAIL_ADMIN', $sEmailAdmin);    
            
        //PATHS --> paths moeten in de config altijd zonder slashes zijn
            //i.e. /var/www/html_public
            if (!isset($sPathRootLocal))
                $sPathRootLocal = '';
            define('GLOBAL_PATH_LOCAL', $sPathRootLocal);   

            //i.e. https://www.dikkelul.nl
            if (!isset($sPathRootWWW))
                $sPathRootWWW = '';
            define('GLOBAL_PATH_WWW', $sPathRootWWW);   
            
            //i.e. dikkelul.nl
            if (!isset($sPathDomain))
                $sPathDomain = '';
            define('GLOBAL_PATH_DOMAIN', $sPathDomain);       
 
            define('GLOBAL_PATH_WWW_UPLOADS_IMAGES', GLOBAL_PATH_WWW.DIRECTORY_SEPARATOR.'uploads/images');
            define('GLOBAL_PATH_LOCAL_UPLOADS_IMAGES', GLOBAL_PATH_LOCAL.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'images');            
            
            if (!isset($sCMSDirectory))
                $sCMSDirectory = 'admindr';
            define('GLOBAL_PATH_WWW_CMS', GLOBAL_PATH_WWW.'/'.$sCMSDirectory);
            define('GLOBAL_PATH_LOCAL_CMS', GLOBAL_PATH_LOCAL.DIRECTORY_SEPARATOR.$sCMSDirectory);
        
            define('GLOBAL_PATH_WWW_CMS_CLASSES', GLOBAL_PATH_WWW_CMS.'/classes');
            define('GLOBAL_PATH_LOCAL_CMS_CLASSES', GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'classes');            

                //===Themes specific
            define('GLOBAL_PATH_WWW_CMS_THEMES', GLOBAL_PATH_WWW_CMS.DIRECTORY_SEPARATOR.'/themes');                        
            define('GLOBAL_PATH_LOCAL_CMS_THEMES', GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'themes');                        

            define('GLOBAL_PATH_LOCAL_CMS_THEMES_PREVIOUSTHEME', GLOBAL_PATH_LOCAL_CMS_THEMES.DIRECTORY_SEPARATOR.'previous-theme');                        

            define('GLOBAL_PATH_WWW_CMS_THEMES_CURRENTTHEME', GLOBAL_PATH_WWW_CMS_THEMES.DIRECTORY_SEPARATOR.'/current-theme');                        
            define('GLOBAL_PATH_LOCAL_CMS_THEMES_CURRENTTHEME', GLOBAL_PATH_LOCAL_CMS_THEMES.DIRECTORY_SEPARATOR.'current-theme');                        

            define('GLOBAL_PATH_WWW_CMS_THEMES_ALLTHEMES', GLOBAL_PATH_WWW_CMS_THEMES.DIRECTORY_SEPARATOR.'/all-themes');                        
            define('GLOBAL_PATH_LOCAL_CMS_THEMES_ALLTHEMES', GLOBAL_PATH_LOCAL_CMS_THEMES.DIRECTORY_SEPARATOR.'all-themes');                        

            define('GLOBAL_PATH_WWW_CMS_IMAGES', GLOBAL_PATH_WWW_CMS_THEMES_CURRENTTHEME.'/images');
            define('GLOBAL_PATH_LOCAL_CMS_IMAGES', GLOBAL_PATH_LOCAL_CMS_THEMES_CURRENTTHEME.DIRECTORY_SEPARATOR.'images');
            
            define('GLOBAL_PATH_WWW_CMS_STYLESHEETS', GLOBAL_PATH_WWW_CMS_THEMES_CURRENTTHEME.'/css');
            define('GLOBAL_PATH_LOCAL_CMS_STYLESHEETS', GLOBAL_PATH_LOCAL_CMS_THEMES_CURRENTTHEME.DIRECTORY_SEPARATOR.'css');            

            define('GLOBAL_PATH_WWW_CMS_JSSCRIPTS', GLOBAL_PATH_WWW_CMS_THEMES_CURRENTTHEME.'/js');
            define('GLOBAL_PATH_LOCAL_CMS_JSSCRIPTS', GLOBAL_PATH_LOCAL_CMS_THEMES_CURRENTTHEME.DIRECTORY_SEPARATOR.'js');    
            
            define('GLOBAL_PATH_LOCAL_CMS_TEMPLATES', GLOBAL_PATH_LOCAL_CMS_THEMES_CURRENTTHEME.DIRECTORY_SEPARATOR.'templates');                        
                //===END: Themes specifi

            if (!isset($sModulesDirectory))
                $sModulesDirectory = 'modules';
            define('GLOBAL_PATH_WWW_MODULES', GLOBAL_PATH_WWW_CMS.'/'.$sModulesDirectory);
            define('GLOBAL_PATH_LOCAL_MODULES', GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.$sModulesDirectory);            
                    
            define('GLOBAL_PATH_WWW_VENDOR', GLOBAL_PATH_WWW_CMS.'/vendor');
            define('GLOBAL_PATH_LOCAL_VENDOR', GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'vendor');            


            define('GLOBAL_PATH_LOCAL_LIBRARIES', GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'lib');
            define('GLOBAL_PATH_LOCAL_LOGFILES', GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'logfiles');            
            define('GLOBAL_PATH_LOCAL_CMS_LANGUAGES', GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'languages');            
            define('GLOBAL_PATH_LOCAL_CMS_CACHE', GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'cache'); 
                        
            if (!isset($sPathRootLocalBackups))
                $sPathRootLocalBackups = GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'backups';
            define('GLOBAL_PATH_LOCAL_BACKUPS', $sPathRootLocalBackups);   

            if (!isset($sPathRootLocalUploads))
                $sPathRootLocalUploads = GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'uploads';
            define('GLOBAL_PATH_LOCAL_UPLOADS', $sPathRootLocalUploads);               
            
            if (!isset($sPathRootWWWUploads))
                $sPathRootWWWUploads = GLOBAL_PATH_WWW.DIRECTORY_SEPARATOR.'uploads';
            define('GLOBAL_PATH_WWW_UPLOADS', $sPathRootWWWUploads);               
            
            if (!isset($bIsHTTPS))
                $bIsHTTPS = false;
            define('GLOBAL_ISHTTPS', $bIsHTTPS);         
            
            
        
        //LOCALE
            define('GLOBAL_LOCALE_DEFAULT', 'en');
            define('GLOBAL_LOCATION_DEFAULT', 'NL');
            
        //CACHING
            define('GLOBAL_CACHE_TIMEOUT_DEFAULT', 86400);//in seconds; 86400 = 24 hour cache file timeout
            
            if (!isset($bCacheClearOnRun))
                $bCacheClearOnRun = false;
            define('GLOBAL_CACHE_CLEARONRUN', $bCacheClearOnRun);
            
        //CURRENT WEBSITE ID
            define('GLOBAL_WEBSITEID_CURRENT', $iSiteID);
            
        //ALL THE $SESSION VARIABLES for the system (so not defined in controllers or so)
            define('SESSIONARRAYKEY_SELECTEDSITEID', 'iSelectedSiteID');//used in cms5. this NEEDS to be iSelectedSiteID for compatibility with cms2/4, name for cms5 doesn't matter
            define('SESSIONARRAYKEY_SELECTEDLANGUAGEID', 'iSelectedLanguageID');//used in cms5 for default language per site
                        
        //ALL THE $COOKIE VARIABLES for the system (so not defined in controllers or so) 
            define('GLOBAL_COOKIE_EXPIREDAYS', 60);
            define('COOKIEARRAYKEY_SELECTEDSITEID', 'sid');//used in cms5
            
        //ALL THE $_GET VARIABLES for the system (so not defined in controllers or so) 
            define('GETARRAYKEY_SELECTEDSITEID', 'selectedSiteID');//used in cms5 for requesting the default language of the selected site (only lives in session,
            define('GETARRAYKEY_CMSMESSAGE_SUCCESS', 'cmsmessage'); //$GET array indexes for messages
            define('GETARRAYKEY_CMSMESSAGE_ERROR', 'cmserror'); //$GET array indexes for messages
            define('GETARRAYKEY_CMSMESSAGE_NOTIFICATION', 'cmsnotification'); //$GET array indexes for messages
            //
        //ALL THE $_POST VARIABLES for the system (so not defined in controllers or so) 
            //still empty
            
        //MISC
            if (!isset($bDevelopmentEnvironment))
                $bDevelopmentEnvironment = false;//default. 
            define('GLOBAL_DEVELOPMENTENVIRONMENT', $bDevelopmentEnvironment);//this value can't be 0, otherwise default parameters will bug out
            
            if (!isset($sPepper))
                $sPepper = '';//default disabled
            define('GLOBAL_PEPPER', $sPepper);//for peppering passwords and checksums

            if (!isset($sPepper))
                $sCronJobID = '';
            define('GLOBAL_CRONJOBID', $sCronJobID);//to prevent dos attacks on the system by executing many cronjobs (everybody knows the url once the know the system). The cronjob id needs to be supplied as parameter in order to execute the cron job
            
        //================== ERROR LOG =========================
            //use php's own error log mechanism ( error_log('just browsin');) , but put in a separate folder
        $sLogDirToday = '';
        $sLogDirToday = GLOBAL_PATH_LOCAL_LOGFILES.DIRECTORY_SEPARATOR.date('Y-m-d');
        
        if(!is_dir($sLogDirToday))
        {
            //create dir
            mkdir($sLogDirToday);

            //create htaccess that blocks access to dir
            $fhHtaccess = fopen($sLogDirToday.DIRECTORY_SEPARATOR.'.htaccess', 'w'); 
            fwrite($fhHtaccess, 'deny from all');
            fclose($fhHtaccess);
        }

        ini_set('error_log', $sLogDirToday.DIRECTORY_SEPARATOR.'errorlog_'.date('Y-m-d').'.txt');        
        // ini_set('error_log', $sTempLogDir.DIRECTORY_SEPARATOR.''.date('Y-m-d').'_phplog.txt');        
            
        
        //================== DEVELOPMENT OR LIVE?? ===================
        error_reporting(0);
        ini_set('display_errors', 'off');
        if (GLOBAL_DEVELOPMENTENVIRONMENT === true)
        {
            ini_set('display_errors', 'on');
            error_reporting(E_ALL | E_STRICT);
        }

  
                                   
       
        //================== INLCUDE ALL LIBRARIES FROM LIBRARY DIRECTORY =================== 
        //we don't autoload everything in 'lib' directory for performance reasons
        //you have to load in manually on top of every php page.
        //the lib files with _sys_ are system libraries that are loaded for you with the boot of the framewor
        //below are all the library files commented out, copy paste the lines in the php headers and uncomment the ones you need

        include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_sys_framework.php');
        include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_sys_typedef.php'); //ONLY for compatibility reasons with cms2,, @remove if no cms2 components are present
        include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_sys_types.php'); 
        include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_sys_file.php'); //-->we need this later in this bootstrap file
        include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_sys_inet.php'); //-->we need this later in this bootstrap file
        
        //libraries below are included in bootstrap_cms.php:
        //include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_cms.php');          
        //include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_cms_url.php');     
        
        //Use includes below in headers of php files:
        //include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_date.php');
        //include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_img.php'); 
        //include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_math.php');
        //include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php');
        //include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_string.php');
        

        
        //================== AUTOLOADER CLASSES ===================        
        spl_autoload_register('autoLoader'); //autoloader function in lib_system

        //================== CUSTOM ERROR HANDLER ===================        
//        set_error_handler('customErrorHandler');
//        register_shutdown_function('customErrorHandlerFatalShutdown');
        
        
        
        
        
        //================== INIT DATABASE ===================

        //deze moet boven de modules zitten (vanwege de modules die in de application zitten, die een database connectie nodig hebben)
        $sDatabaseClass = GLOBAL_DB_CONNECTIONCLASS;
        $objDBConnection = new $sDatabaseClass(NULL);//make instance of database object
        $objDBConnection->setHost(GLOBAL_DB_HOST);
        $objDBConnection->setUsername(GLOBAL_DB_USER);
        $objDBConnection->setPassword(GLOBAL_DB_PASSWORD);
        $objDBConnection->setDatabaseName(GLOBAL_DB_DATABASE);
        if (GLOBAL_DB_PORT > 0) //only set when higher tha 0 given, otherwise use default
            $objDBConnection->setPort(GLOBAL_DB_PORT);          

        $objDBConnection->connect();
        
        
        
        //================== DEFINING SYSTEM DATABASE TABLES ===================   
        //  ===> system database tables are defined in lib_sys_framework:getSystemModels()


        
                    
        
        //================== DEFINE LANGUAGE FILES =================== 
        //@TODO cache Locale, countrysettings and language files (speed improvement)
        //we ONLY define default file names here, they may be overwritten, like in bootstrap_cms_auth.php with settings of the user
        //files are automatically loaded upon usage (information requests) of the classes
        //this way we don't have performance overhead (loading default language files and later loading the preferred language files of the user)
            
            
        //defining locale
            $objLocale = new dr\classes\locale\TLocale(GLOBAL_LOCALE_DEFAULT);
            $objCountrySettings = new dr\classes\locale\TCountrySettings();//will be autoloaded when needed            
            $objCountrySettings->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'countrysettings_'.GLOBAL_LOCALE_DEFAULT.'.ini');
            
            setlocale(LC_TIME, GLOBAL_LOCALE_DEFAULT);
            
            
        //defining translation files
            $objTranslationSystem = new dr\classes\locale\TTranslation(); //will be autoloaded when needed
            $objTranslationSystem->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'system_translation_'.GLOBAL_LOCALE_DEFAULT.'.txt');
            $objTranslationCMS = new dr\classes\locale\TTranslation();//will be autoloaded when needed
            $objTranslationCMS->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'cms_translation_'.GLOBAL_LOCALE_DEFAULT.'.txt');        
            $arrTranslationsModules = array(); //--> 1d array with TTranslation objects of all modules. (key is the name of the module). the objects are created and added upon request in transm();
            //$objTranslationModule = new dr\classes\locale\TTranslation();//will be autoloaded when needed
            //$objTranslationModule->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'module_translation_'.GLOBAL_LOCALE_DEFAULT.'.txt');        
            $objTranslationWebsite = new dr\classes\locale\TTranslation();//will be autoloaded when needed
            $objTranslationWebsite->setFileName(GLOBAL_PATH_LOCAL_CMS_LANGUAGES.DIRECTORY_SEPARATOR.'website_translation_'.GLOBAL_LOCALE_DEFAULT.'.txt');        
        

        //================== INIT AUTHORIZATION SYSTEM ===================
        //it is initialized here, so it is availble for every page on a website 
        //(that is what this framework is all about: giving a toolbox for every application)            
        //(that is also why the auth() function is in a famework library, not in a cms library)
        //load permissions into array ONLY if you want to use the authorisation system.
        //The resource-string is the key of the array, the value is true or false (true = allowed, false = not allowed)
        //this array looks like this:
        //array('books/authors/view' => true
        //      'book/authors/edit' => false
        //      )
        //$arrPermissions = array();  has become -> $_SESSION[SESSIONARRAYKEY_PERMISSIONS]


        //================== START SESSION ===================
        session_set_cookie_params(0,'/',GLOBAL_PATH_DOMAIN, GLOBAL_ISHTTPS, true);//0 = till browser closes, forces https if available / http only means that it is not accessible by javascript (this prevents xss)
        session_start();
                    
            

                
        //================== REDIRECT TO HTTPS ===================
        //redirect to https version if available
        //only when we have paths and libraries, we can redirect
        redirectToHTTPS();
                
      
            
	//====================================================================
	//        C M S   2/4   - C O M P A T I B I L I T Y   M O D E  (remove below this line to remove compatibility with cms 2 & 4)
 	//====================================================================            

        include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_cms2.php'); //@remove if cms2 components are removed
        
        //compatibility Cms4: @todo remove
        if (!isset($iPasswordEncryptionAlgorithm))
            $iPasswordEncryptionAlgorithm = PASSWORD_BCRYPT;//default. PASSWORD_DEFAULT kan wijzigen bij het uitkomen van nieuwe algoritmes
        define('GLOBAL_PASSWORD_ENCRYPTION_ALGORITHM', $iPasswordEncryptionAlgorithm);//deze waarde mag niet 0 zijn anders gaat het met de default parameters fout

        define('GLOBAL_LOGIN_ACCESS_TOKEN', 'logintoken_123-*&!@333aaaaaa32333nfjdfjfjfhwjhdgsdhgfjhsdgfjhagasdf');


            
        //compatibility Cms2: @todo remove
        $objDB = new mysqli(GLOBAL_DB_HOST, GLOBAL_DB_USER, GLOBAL_DB_PASSWORD, GLOBAL_DB_DATABASE, GLOBAL_DB_PORT); //mysqli connectie leggen
        if ($objDB->connect_errno) 
                die('oops, database connection problem');   
        
                    
        
        //compatibility Cms2: @todo remove
        $_SESSION['iAdminUserLevel'] = 1;//all users can do everything in cms2/4

	//        C M S   2  M O D U L E S   I N L E Z E N - COMPATIBILITY MODE

//        //        include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_date.php');
//        include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_file.php');
//        include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_img.php'); 
//        include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_sys_inet.php');
//        include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_math.php');
//        include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php');
//        include_once(GLOBAL_PATH_LOCAL_CMS_LIBRARIES.DIRECTORY_SEPARATOR.'lib_string.php');

	//alle categorien inlezen
	//include_once("library.php"); 

   
	$arrModuleCaths = getFileFolderArray($local_sitemanageradminmodules, true, false);
	sort($arrModuleCaths); //array ff sorteren	
	
 
	//alle informatie uit de config files van de modules ophalen
	for($iTeller=0;$iTeller < count($arrModuleCaths);$iTeller++)
	{
		$arrDirs = getFileFolderArray($local_sitemanageradminmodules.$arrModuleCaths[$iTeller]."/", true, false);
		for($iTeller2=0;$iTeller2 < count($arrDirs);$iTeller2++)
		{
//                    var_dump($sThisModuleWwwPath);
//                    echo '<br>';
			$sThisModuleWwwPath = $www_sitemanageradminmodules.$arrModuleCaths[$iTeller]."/".$arrDirs[$iTeller2]."/"; //$sThisModulePath leest de config.php file van de module weer uit, zo weet deze op welk path deze uithangt
			$sThisModuleLocalPath = $local_sitemanageradminmodules.$arrModuleCaths[$iTeller]."/".$arrDirs[$iTeller2]."/"; //$sThisModulePath leest de config.php file van de module weer uit, zo weet deze op welk path deze uithangt
			$sThisModuleCath = $arrModuleCaths[$iTeller];
			include_once($local_sitemanageradminmodules.$arrModuleCaths[$iTeller]."/".$arrDirs[$iTeller2]."/config.php");
		}
	}
	//====================================================================
	 

		
?>