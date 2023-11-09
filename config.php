<?php
       
/**
 * CMS5 CONFIG FILE
 * =================
 * 
 * paths in this file are WITHOUT SLASHES at the end, so
 * http://www.domein.com (and not: http://www.domein.com/)
 * 
 * DEFINE IN THIS FILE THE FOLLOWING VALUES:
 * 
 * 
 *     //==== SITE ADMINISTRATOR ====
        $sEmailAdmin = 'website@example.nl';

        //==== CMS specific ===== 
        $sCMSApplicationName = 'CMS 5'; //name of the application
        $bCMSAnyoneCanRegisterAccount = false; //this option SUPERSEDES the option in the settings screen. It can be switched off here for security reasons: When you would get into the database you could check this and do EVERYTHING with the whole system (because you can add a user).
        $bCMSEnableSignInWithGoogle = false; //this is done in the config instead of the database for security reasons. When you would get into the database you could check this and do EVERYTHING with the whole system. this way it is a bit more difficult
        $bCMSShowWebsitesInNavigation = true;//enable or disable the visibility websites in header and menu on left. This disabled makes it a webapp instead of a CMS

        //==== PATHS ====
        $sPathRootLocal = '/home/bedrij2q/public_html';
        $sPathRootWWW = 'https://www.bedrijfsuitje.events';
        $sDomain = 'bedrijfsuitje.events';

        //==== GOOGLE API ===
        $sGoogleAPIKey = '';
        $sGoogleAPIClientID = '';
        $sGoogleAPIClientSecret = '';
        $sGoogleRecaptchaV2SiteKey = '';
        $sGoogleRecaptchaV2SecretKey = '';
        $sGoogleRecaptchaV3SiteKey = '';
        $sGoogleRecaptchaV3SecretKey = '';
        $bGoogleRecaptchaV3Use = true;        


        //==== DATABASE ====
        $sDBHost = 'localhost';
        $sDBUser = 'bedrij2q_generic';
        $sDBPassword = '';
        $sDBDatabase = 'bedrij2q_generic';
        $sDBTablePrefix = "tblBEDEV"; //met welke letters beginnen de tabellen van dit project ? (=prefix)    



        //==== MISC ====
        $iPasswordEncryptionAlgorithm = PASSWORD_BCRYPT; //encryptie algoritme voor wachtwoorden        
        $bDevelopmentEnvironment = true;
        $sPepper = '';
        $sCronJobID = '30fkdPlds_qef'; //change for every system config, to prevent dos attacks on the system by executing many cronjobs (everybody knows the url once the know the system). The cronjob id needs to be supplied as parameter in order to execute the cron job

 */

    if ($_SERVER['SERVER_NAME'] == 'www.bedrijfsuitje.events')
    {
        //==== SITE WIDE ====
        $sEmailAdmin = 'email@dexxterclark.com';

        //==== CMS specific ===== 
        $sCMSApplicationName = 'CMS 5'; //name of the application
        $bCMSAnyoneCanRegisterAccount = true; //this option SUPERSEDES the option in the settings screen. It can be switched off here for security reasons: When you would get into the database you could check this and do EVERYTHING with the whole system (because you can add a user).
        $bCMSEnableSignInWithGoogle = true; //this is done in the config instead of the database for security reasons. When you would get into the database you could check this and do EVERYTHING with the whole system. this way it is a bit more difficult
        $bCMSShowWebsitesInNavigation = false;//enable or disable the visibility websites in header and menu on left. This disabled makes it a webapp instead of a CMS
        $sCMSFirstPage = 'login';//what is the first page to show when entering the directory of the cms? The login page? in the index.php, this page will be used to load

        //==== PATHS ====
        $sPathRootLocal = '/home/bedrij2q/public_html'; //without slash at the end
        $sPathRootWWW = 'https://www.bedrijfsuitje.events'; //without slash at the end
        $sPathDomain = 'bedrijfsuitje.events';
        $bIsHTTPS = true;
        $sPathRootLocalBackups = $sPathRootLocal.DIRECTORY_SEPARATOR.'backups'; //backup dir, preferably a non public directory
        $sPathRootLocalUploads = $sPathRootLocal.DIRECTORY_SEPARATOR.'uploads'; //a centralized location on the server for all websites to store their uploadloads like images/videos/pdfs
        $sPathRootWWWUploads = $sPathRootWWW.DIRECTORY_SEPARATOR.'uploads';
        $sCMSDirectory = 'admindr';
        $sModulesDirectory = 'modules';

        //==== GOOGLE API  ===
        $sGoogleAPIKey = 'AIzaSyAD6YwlYsgV1TGHWF3sSPrIANs1n6fOVqs';
        $sGoogleAPIClientID = '160714657319-3nmtoejtouoa9cbqh9gpme1javh5mn6o.apps.googleusercontent.com';
        $sGoogleAPIClientSecret = 'W5AWgaN3__TLWfkqR5_ouvVL';
        $arrGoogleAPICientScopes = array("email", "profile", "https://www.googleapis.com/auth/youtube");
        
        $sGoogleRecaptchaV2SiteKey = '6LfHICQUAAAAAIQcTKFJCzM7GjjT3Ca6GLNmbHL6';
        $sGoogleRecaptchaV2SecretKey = '6LfHICQUAAAAAEuQRfvkOHtkAQ9DbxcAHKDIGMaH';
        $sGoogleRecaptchaV3SiteKey = '6LdclFcbAAAAAFDrtnMs82OAq00cuUi0s3g_b7rg';
        $sGoogleRecaptchaV3SecretKey = '6LdclFcbAAAAAKKtV0n84_yhC_RyrSQ8pZ9225PQ';
        $bGoogleRecaptchaV3Use = true;

        //==== DATABASE ====
        $sDBHost = 'localhost';
        $sDBUser = 'bedrij2q_cms5';
        $sDBPassword = 'gfK)0f^p003t';
        $sDBDatabase = 'bedrij2q_cms5';    
        $sDBTablePrefix = 'tbl'; //prefix of all the database tables
    //    $sDBConnectionClass = 'dr\classes\db\TDBConnectionMySQL'; ==>use defaults
    //    $iDBPort = 3306; ==>use defaults
        $iDBSiteID = 6; //---> ID of THIS site in the database

        //==== MISC ====
        $bCacheClearOnRun = false;// remove cache every time the framework runs, this is handy for development (so you dont have to empty the cache directory EVERY single time)
        $bDevelopmentEnvironment = true;
        $sPepper = 'pfk4-D2d_.%^67*w1250djeDeeodj392/3_38djks3^#3dksp1k4lc,wmf.rpwjfk3h34i4h5ndsdfA!d';//used for peppering passwords and checksums (as opposed to salting a password) - the longer, the safer
        $sCronJobID = '2dPLoMboq24_pl'; //change for every system config, to prevent dos attacks on the system by executing many cronjobs (everybody knows the url once the know the system). The cronjob id needs to be supplied as parameter in order to execute the cron job        
    }

    if ($_SERVER['SERVER_NAME'] == 'nasischijf')
    {
        //==== SITE ADMINISTRATOR ====
        $sEmailAdmin = 'website@dexxterclark.com';

        //==== CMS specific ===== 
        $sCMSApplicationName = 'CMS 5'; //name of the application
        $bCMSAnyoneCanRegisterAccount = true; //this option SUPERSEDES the option in the settings screen. It can be switched off here for security reasons: When you would get into the database you could check this and do EVERYTHING with the whole system (because you can add a user).
        $bCMSEnableSignInWithGoogle = false; //this is done in the config instead of the database for security reasons. When you would get into the database you could check this and do EVERYTHING with the whole system. this way it is a bit more difficult
        $bCMSShowWebsitesInNavigation = true;//enable or disable the visibility websites in header and menu on left. This disabled makes it a webapp instead of a CMS
        $sCMSFirstPage = 'login';//what is the first page to show when entering the directory of the cms? The login page? in the index.php, this page will be used to load

        //==== PATHS ====
        $sPathRootLocal = '/var/services/web/cms5/www'; //without slash at the end
        $sPathRootWWW = 'http://nasischijf/cms5/www'; //without slash at the end
        $sPathDomain = 'nasischijf';
        $bIsHTTPS = false;
        $sPathRootLocalBackups = $sPathRootLocal.DIRECTORY_SEPARATOR.'admindr'.DIRECTORY_SEPARATOR.'backups'; //backup dir, preferably a non public directory
        $sPathRootLocalUploads = $sPathRootLocal.DIRECTORY_SEPARATOR.'uploads'; //a centralized location on the server for all websites to store their uploadloads like images/videos/pdfs
        $sPathRootWWWUploads = $sPathRootWWW.'/uploads';
        $sCMSDirectory = 'admindr';
        $sModulesDirectory = 'modules';

        //==== GOOGLE API ===
        $sGoogleAPIKey = 'AIzaSyAD6YwlYsgV1TGHWF3sSPrIANs1n6fOVqs';
        $sGoogleAPIClientID = '160714657319-3nmtoejtouoa9cbqh9gpme1javh5mn6o.apps.googleusercontent.com';
        $sGoogleAPIClientSecret = 'W5AWgaN3__TLWfkqR5_ouvVL';
        $arrGoogleAPICientScopes = array("email", "profile", "https://www.googleapis.com/auth/youtube");
        
        $sGoogleRecaptchaV2SiteKey = '6LfHICQUAAAAAIQcTKFJCzM7GjjT3Ca6GLNmbHL6';
        $sGoogleRecaptchaV2SecretKey = '6LfHICQUAAAAAEuQRfvkOHtkAQ9DbxcAHKDIGMaH';
        $sGoogleRecaptchaV3SiteKey = '6LdclFcbAAAAAFDrtnMs82OAq00cuUi0s3g_b7rg';
        $sGoogleRecaptchaV3SecretKey = '6LdclFcbAAAAAKKtV0n84_yhC_RyrSQ8pZ9225PQ';
        $bGoogleRecaptchaV3Use = false;

        //==== DATABASE ====
        $sDBHost = '127.0.0.1';
        $sDBUser = 'dennis';
        $sDBPassword = '894hf0_w22';
        $sDBDatabase = 'cms5';    
        $sDBTablePrefix = 'tbl'; //prefix of all the database tables
        //    $sDBConnectionClass = 'dr\classes\db\TDBConnectionMySQL'; ==>use defaults
        $iDBPort = 3307; 
        $iDBSiteID = 6; //---> ID of THIS site in the database

        //==== MISC ====
        $bCacheClearOnRun = false;// remove cache every time the framework runs, this is handy for development (so you dont have to empty the cache directory EVERY single time)
        $bDevelopmentEnvironment = true;
        $sPepper = 'pfk4-D2d_.%^67*w1250djeDeeodj392/3_38djks3^#3dksp1k4lc,wmf.rpwjfk3h34i4h5ndsdfA!d';//used for peppering passwords and checksums (as opposed to salting a password) - the longer, the safer
        $sCronJobID = '30fkdPlds_qef'; //change for every system config, to prevent dos attacks on the system by executing many cronjobs (everybody knows the url once the know the system). The cronjob id needs to be supplied as parameter in order to execute the cron job
    }

    //==== MISC ====
    $iPasswordEncryptionAlgorithm = PASSWORD_BCRYPT; //encryptie algoritme voor wachtwoorden        




/* GOEDE CMS 2 CONFIG FILE ter referentie
       


        $iPasswordEncryptionAlgorithm = PASSWORD_BCRYPT; //encryptie algoritme voor wachtwoorden        
            
            
	//algemene variabelen
        $sDatumNu = date("d-m-Y");
        $sTijdNu = date("H:i");
	$sTitelSitemanager = "Sitemanager";
	$sTitelSite = "Bedrijfsuitje"; 

	$sDBTablePrefix = "tblBEDEV"; //met welke letters beginnen de tabellen van dit project ? (=prefix)



	$iOctalRechtenOpFiles = 0777; //rechten op files, die de sitemanager aanmaakt (in octale notatie)

	$iAantalSecondenRedirect = 5;
	$iSimultaniousFileUpload = 5; //max 5 bestanden tegelijk uploaden
	
	$sRowColor1	= "#FFFFFF";
	$sRowColor2	= "#F9F9F9";
	$sRowColorCurrent = $sRowColor1;	

	$maxuploadsize = 10485760; //max 10 mb uploaden
	ini_set("memory_limit","1000M"); //totale geheugen tot 1000 MB beperken
	set_time_limit(90); //time out instellen

        

		$sAdminEmailadres = "website@beterevenementen.nl";
		$sAdminEmailadresMetAlias = $sAdminEmailadres;

		$www_url = "https://www.bedrijfsuitje.events/"; 
                $domain = 'bedrijfsuitje.events';
                
		$local_url = "/home/bedrij2q/public_html/";
					
		
		$sqlhost = "localhost";
		$sqluser = "bedrij2q_generic";
		$sqlpassword = "T(PlX1d(skMx";
		$sqldatabase = "bedrij2q_generic"; 

                $recaptcha_sitekey = '6LfHICQUAAAAAIQcTKFJCzM7GjjT3Ca6GLNmbHL6';
                $recaptcha_privatekey = '6LfHICQUAAAAAEuQRfvkOHtkAQ9DbxcAHKDIGMaH';
                

        
        
	
	$www_images = $www_url."images/";
	$local_images = $local_url."images/";
	
	$www_sitemanager = $www_url."sitemanager/";
	$local_sitemanager = $local_url."sitemanager/";

	$www_sitemanagerimages = $www_sitemanager."images/";
	$local_sitemanagerimages = $local_sitemanager."images/";

	$www_sitemanagerfotoboek = $www_sitemanager."fotoboek/"; //voor online fotoboek
	$local_sitemanagerfotoboek = $local_sitemanager."fotoboek/"; //voor online fotoboek

	$www_sitemanagerimagestemp = $www_sitemanagerimages."temp/";
	$local_sitemanagerimagestemp = $local_sitemanagerimages."temp/";
	
	$www_sitemanageradmin = $www_sitemanager."admin/";
	$local_sitemanageradmin = $local_sitemanager."admin/";

	$www_sitemanageradminzend = $www_sitemanager."zend/";
	$local_sitemanageradminzend = $local_sitemanager."zend/";
	
	$www_sitemanagerfonts = $www_sitemanageradmin."fonts/";
	$local_sitemanagerfonts = $local_sitemanageradmin."fonts/";
	
	$www_sitemanageradminmodules = $www_sitemanageradmin."modules/";
	$local_sitemanageradminmodules = $local_sitemanageradmin."modules/";

	$www_sitemanageradminlibraries = $www_sitemanageradmin."libraries/";
	$local_sitemanageradminlibraries = $local_sitemanageradmin."libraries/";
        
	$www_sitemanageradminimages = $www_sitemanageradmin."images/";
	$local_sitemanageradminimages = $local_sitemanageradmin."images/";
	
	$www_sitemanageradminimagesfiles = $www_sitemanageradminimages."files/"; //plaatjes voor herkenning van bestandstypen 
	$local_sitemanageradminimagesfiles = $local_sitemanageradminimages."files/";
	
	$www_stylesheetwebsite = $www_url."stylesheet.css";

 
*/

        
        //================== CMS 2 COMPATIBILITY : DON'T TOUCH!!!!!! ===================      
	//algemene variabelen
        $sDatumNu = date("d-m-Y");
        $sTijdNu = date("H:i");
	$sTitelSitemanager = "Sitemanager";
	$sTitelSite = "Bedrijfsuitje"; 

	//$sDBTablePrefix = "tblBEDEV"; //met welke letters beginnen de tabellen van dit project ? (=prefix)



	$iOctalRechtenOpFiles = 0777; //rechten op files, die de sitemanager aanmaakt (in octale notatie)

	$iAantalSecondenRedirect = 5;
	$iSimultaniousFileUpload = 5; //max 5 bestanden tegelijk uploaden
	
	$sRowColor1	= "#FFFFFF";
	$sRowColor2	= "#F9F9F9";
	$sRowColorCurrent = $sRowColor1;	

	$maxuploadsize = 10485760; //max 10 mb uploaden
	ini_set("memory_limit","1000M"); //totale geheugen tot 1000 MB beperken
	set_time_limit(90); //time out instellen

        
        
        $sAdminEmailadres = $sEmailAdmin;
        $sAdminEmailadresMetAlias = $sEmailAdmin;

        $www_url = $sPathRootWWW.'/';
        $domain = $sPathDomain;

        $local_url = $sPathRootLocal.'/';


        $sqlhost = $sDBHost;
        $sqluser = $sDBUser;
        $sqlpassword = $sDBPassword;
        $sqldatabase = $sDBDatabase;

        $recaptcha_sitekey = $sRecaptchaSiteKey;
        $recaptcha_privatekey = $sRecaptchaPrivateKey;
                

	
	$www_images = $www_url."images/";
	$local_images = $local_url."images/";
	
	$www_sitemanager = $www_url."admindr/";
	$local_sitemanager = $local_url."admindr/";

	$www_sitemanagerimages = $www_url."uploads/images/";
	$local_sitemanagerimages = $local_url."uploads/images/";

	$www_sitemanagerfotoboek = $www_sitemanager."fotoboek/"; //voor online fotoboek
	$local_sitemanagerfotoboek = $local_sitemanager."fotoboek/"; //voor online fotoboek

	$www_sitemanagerimagestemp = $www_sitemanagerimages."temp/";
	$local_sitemanagerimagestemp = $local_sitemanagerimages."temp/";
	
	$www_sitemanageradmin = $www_sitemanager."";
	$local_sitemanageradmin = $local_sitemanager."";

	$www_sitemanageradminzend = $www_sitemanager."zend/";
	$local_sitemanageradminzend = $local_sitemanager."zend/";
	
	$www_sitemanagerfonts = $www_sitemanageradmin."fonts/";
	$local_sitemanagerfonts = $local_sitemanageradmin."fonts/";
	
	$www_sitemanageradminmodules = $www_sitemanageradmin."modulescms2/";
	$local_sitemanageradminmodules = $local_sitemanageradmin."modulescms2/";

	$www_sitemanageradminlibraries = $www_sitemanageradmin."libraries/";
	$local_sitemanageradminlibraries = $local_sitemanageradmin."libraries/";
        
	$www_sitemanageradminimages = $www_sitemanageradmin."images_cms2/";
	$local_sitemanageradminimages = $local_sitemanageradmin."images_cms2/";
	
	$www_sitemanageradminimagesfiles = $www_sitemanageradminimages."files/"; //plaatjes voor herkenning van bestandstypen 
	$local_sitemanageradminimagesfiles = $local_sitemanageradminimages."files/";
	
	$www_stylesheetwebsite = $www_url."stylesheet.css";
    



		


		
?>