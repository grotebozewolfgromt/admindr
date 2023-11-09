<?php

use dr\classes\models\TSysCMSUsers;
use dr\classes\models\TSysLanguages;
use dr\classes\models\TSysSettings;
use dr\modules\Mod_Sys_Settings\Mod_Sys_Settings;
use dr\modules\Mod_Sys_Settings\controllers\TCRUDDetailSaveSettingsUser;    

            
    //session started in bootstrap
    include_once '../../bootstrap_cms_auth.php';
        
    //handle authentication    
    if (!auth($sCurrentModule, Mod_Sys_Settings::PERM_CAT_CRONJOB, Mod_Sys_Settings::PERM_OP_VIEW))
    {
        showAccessDenied(transm($sCurrentModule, 'message_cronjob_notallowed', 'you are not allowed to view the cronjob tab'));
        die();
    }

    //====== page defaults
    
    $sTitle = transm($sCurrentModule, 'title_settings', 'Settings');
   
    $sHTMLTitle = $sTitle;
    $sHTMLMetaDescription = $sTitle;

    //===fill tabsheets array (only if you want tabsheets)
    $arrTabsheets = $objCurrentModule->getTabsheets();     



    //============ RENDER de templates

    $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$sCurrentModule.DIRECTORY_SEPARATOR.'tpl_settings_cronjob.php', get_defined_vars());

    echo renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withmenu.php', get_defined_vars());
    

?>