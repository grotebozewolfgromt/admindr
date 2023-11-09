<?php

use dr\classes\models\TSysCMSUsers;
use dr\classes\models\TSysLanguages;
use dr\classes\models\TSysSettings;
use dr\modules\Mod_Sys_Settings\controllers\TCRUDDetailSaveSettingsSystem;    

            
    //session started in bootstrap
    include_once '../../bootstrap_cms_auth.php';
        
    
    //return url
    $sReturnURL = getURLCMSDashboard();


    $objModel = new TSysSettings(); 
    $objCRUD = new TCRUDDetailSaveSettingsSystem($objModel, $sReturnURL);
    
    $objForm = $objCRUD->getForm();
    
    //====== page defaults
    
    $sTitle = transm($sCurrentModule, 'title_settings', 'Settings');
   
    $sHTMLTitle = $sTitle;
    $sHTMLMetaDescription = $sTitle;

    //===fill tabsheets array (only if you want tabsheets)
    $arrTabsheets = $objCurrentModule->getTabsheets();     



    //============ RENDER de templates

    $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_modeldetailsave.php', get_defined_vars());

    echo renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withmenu.php', get_defined_vars());
    

?>