<?php
/**
 * BOOTSTRAP_CMS
 * =============
 * bootstrap file that includes the (parent) bootstrap
 * include this file if you want the nessary additions for the cns like extra libraries , the selected site etc
 * 
 * this is a separate bootstrap only for the cms, so the (parent) bootstrap can be included for websites without the overhead of the CMS stuff
 * 
 * 9 april 2019 created Dennis Renirie
 * 27 apil onderverdeling tussen bootstrap_cms en bootstrap_cms_auth
 * 
 */
        use dr\classes\models\TSysModules;
        use dr\classes\models\TSysModulesCategories;
    
	
        include_once(__DIR__.DIRECTORY_SEPARATOR.'bootstrap.php');          
        include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_cms.php');          
        include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_cms_url.php');          
        
        //================== DEFINING CMS CONSTANTS (and defaults) ===================        

        //CMS Application name 
        if (!isset($sCMSApplicationName)) 
            $sCMSApplicationName = 'CMS'; //default application name
        define('GLOBAL_CMS_APPLICATIONNAME', $sCMSApplicationName);

        //CMS MEMBERSHIPS 
        if (!isset($bCMSAnyoneCanRegisterAccount)) //can anyone add a cms account?
            $bCMSAnyoneCanRegisterAccount = false; //default disallow
        define('GLOBAL_CMS_ANYONECANREGISTERACCOUNT', $bCMSAnyoneCanRegisterAccount);

        if (!isset($bCMSEnableSignInWithGoogle)) //can you login with google?
            $bCMSEnableSignInWithGoogle = false; //default disallow
        define('GLOBAL_CMS_ENABLESIGNINWITHGOOGLE', $bCMSEnableSignInWithGoogle);        

        //show websites in header and left-menu
        if (!isset($bCMSShowWebsitesInNavigation)) //can anyone see the websites
            $bCMSShowWebsitesInNavigation = false; //default disallow
        define('GLOBAL_CMS_SHOWWEBSITESINNAVIGATION', $bCMSShowWebsitesInNavigation);


        //================== CMS5 MODULES ==========================
            
            //the module array is called $arrSysModules AND NOT $arrModules due to compatibility with cms2/4 (those modules are in $arrModules)
            // $arrSysModules = array(); //loaded from database (installed modules are inserted in db), if loaded from file system: getModuleFolders(); --> removed 13-11-2020 it wasn't used anywhere
            
            //DEFINE MODULE NAME
            $sCurrentModule = '';
            $sCurrentModule = getModuleFromURL();

            //DEFINE MODULE OBJECT
            $objCurrentModule = null;
            if ($sCurrentModule != '')
            {   
                $sTempModClass = '';
                $sTempModClass = getModuleFullNamespaceClass($sCurrentModule);
                $objCurrentModule = new $sTempModClass;                
                unset($sTempModClass);
            }
            
            
            //==== LOAD CMS 5 modules from database             
            $objSysModulesDB = new TSysModules();
            $objTempModCat = new TSysModulesCategories();                            
            $objSysModulesDB->select(array(TSysModules::FIELD_NAMEINTERNAL, TSysModules::FIELD_NAMEDEFAULT, TSysModules::FIELD_VISIBLE));
            $objSysModulesDB->select(array(TSysModulesCategories::FIELD_NAME), $objTempModCat);
            $objSysModulesDB->selectAlias(TSysModulesCategories::FIELD_ID, 'iCategoryID', $objTempModCat);                            
            $objSysModulesDB->sort(TSysModulesCategories::FIELD_ORDER, SORT_ORDER_ASCENDING, $objTempModCat::getTable());
            $objSysModulesDB->sort(TSysModules::FIELD_ORDER);
            $objSysModulesDB->loadFromDB(true);

            //put everything in an associative array:
            //category1
            //|-module1
            //|-module2
            //category2
            //|-module 3
            $arrCats = array();
            while($objSysModulesDB->next())
            {
                if ($objSysModulesDB->getVisible())
                {
                    //$arrCats[$objSysModulesDB->get(TSysModulesCategories::FIELD_NAME, $objTempModCat::getTable())][] = $objSysModulesDB->getNameInternal();
                    $arrCats[$objSysModulesDB->get(TSysModulesCategories::FIELD_NAME, $objTempModCat::getTable())][] = $objSysModulesDB->getRecordPointer();
                    // echo $objSysModulesDB->getNameInternal();
                }     
            }     
       

        
        //================= MISC =================================
            $sURLThisScript = getURLThisScript();

            $arrTabsheets = array(); //declare tabsheets array
            
          
?>