<?php

/**
 * This script will execute all cronjobs from all modules
 */

    //session started in bootstrap
    include_once 'bootstrap_cms.php';





    $sTitle = transcms('cronjob_title', 'Cron job');
    $sHTMLTitle = transcms('cronjob_htmltitle', 'Cron job');
    $sHTMLMetaDescription = transcms('cronjob_htmlmetadescription', 'Cron job');

    //only execute if you have access to the right crobjobid (defined in config file)
    if (isset($_GET[ACTION_VARIABLE_ID]))
    {
        if ($_GET[ACTION_VARIABLE_ID] != GLOBAL_CRONJOBID)
        {
            showAccessDenied();
            die();
        }
    }
    else
    {
        showAccessDenied();
        die();        
    }

    logAccess('cronjob','Loading page:"'.getURLThisScript().'". Starting cronjob.');


    //redirecting output
    ob_start();
    
    
    //actually doing the cronjob
    echo 'cronjob running (extra details in logfiles) ...<br>';
    logCronjob(__FILE__.': '.__LINE__,'=== Starting Cronjob.php ===');
    
    
    //deleting logfiles older than a year
    echo 'deleting old logfiles<br>';
    logCronjob(__FILE__.': '.__LINE__,'deleting old logfiles (older than a year)');    
    $arrLogFiles = getFileFolderArray(GLOBAL_PATH_LOCAL_LOGFILES, true, false);
    foreach($arrLogFiles as $sLogFile)
    {
        if (filemtime(GLOBAL_PATH_LOCAL_LOGFILES.DIRECTORY_SEPARATOR.$sLogFile) < (time() - YEAR_IN_SECS)) //older than a year
        {
            if(!rmdirrecursive(GLOBAL_PATH_LOCAL_LOGFILES.DIRECTORY_SEPARATOR.$sLogFile))//delete dir
            {
                logCronjob(__FILE__.': '.__LINE__,'error occured deleting logfile dir: '.GLOBAL_PATH_LOCAL_LOGFILES.DIRECTORY_SEPARATOR.$sLogFile);
                echo 'error occured deleting logfile (details in cronjob logfile)'.'<br>';                
            }
        }
    } 

    //17-11-2022 removed, old style logfiles
    // $arrLogFiles = getFileFolderArray(GLOBAL_PATH_LOCAL_LOGFILES, false, true, array('txt'));
    // foreach($arrLogFiles as $sLogFile)
    // {
    //     if (filemtime(GLOBAL_PATH_LOCAL_LOGFILES.DIRECTORY_SEPARATOR.$sLogFile) < (time() - YEAR_IN_SECS)) //older than a year
    //         if (!unlink(GLOBAL_PATH_LOCAL_LOGFILES.DIRECTORY_SEPARATOR.$sLogFile)) //delete file
    //         {
    //             error_log('error occured deleting logfile: '.GLOBAL_PATH_LOCAL_LOGFILES.DIRECTORY_SEPARATOR.$sLogFile);
    //             echo 'error occured deleting logfile (details in todays logfile)'.'<br>';
                
    //         }
    // } 

    //look for important directories than cannot be accessed via browser (like logfiles)

    echo 'checking restrictions to public directories<br>';
    logCronjob(__FILE__.': '.__LINE__,'creating htaccess files');
    
    $arrHtaccessDirs = array(GLOBAL_PATH_LOCAL_LOGFILES, 
            GLOBAL_PATH_LOCAL_VENDOR,
            GLOBAL_PATH_LOCAL_BACKUPS,
            GLOBAL_PATH_LOCAL_CMS_CACHE,
            GLOBAL_PATH_LOCAL_CMS_LANGUAGES,
            GLOBAL_PATH_LOCAL_CMS_TEMPLATES,
            GLOBAL_PATH_LOCAL_LIBRARIES,
            GLOBAL_PATH_LOCAL_CMS_CLASSES
    );

    foreach ($arrHtaccessDirs as $sHtaccesDir)
    {
        if (!createHtaccessFile($sHtaccesDir))
        {
            echo 'error in creating restrictions<br>';
            logCronjob(__FILE__.': '.__LINE__,'error creating htaccess file in directory: '.$sHtaccesDir);        
        }
    }
    

    //cronjob of modules
    $arrSysModules = getModuleFolders();
    $sTempModClass = '';
    foreach($arrSysModules as $sMod)
    {
        // $sTempModClass = '\dr\modules\\'.$sMod.'\\'.$sMod; -->replaced 13-11-2020
        $sTempModClass = getModuleFullNamespaceClass($sMod);

        $objCurrMod = new $sTempModClass; 
        if (!$objCurrMod->handleCronJob())
        {
            logCronjob(__FILE__.': '.__LINE__, 'error occured executing cronjob in module: '.$sMod);
            echo 'error occured executing cronjob in module: '.$sMod.'<br>';
        }
    }
    logCronjob(__FILE__.': '.__LINE__, '=== end of Cronjob.php ===');
    echo 'cronjob done.<br>';    
    //end: actually doing the cronjob
    
    $sHTMLContentMain = ob_get_contents();
    ob_end_clean();  

    //============ RENDER de templates


    $sContentsPage = '';
    $sContentsPage = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withoutmenu.php', get_defined_vars());

    echo $sContentsPage;


?>

