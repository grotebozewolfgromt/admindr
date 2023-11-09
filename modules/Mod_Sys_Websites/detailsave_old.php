<?php
    use dr\classes\models\TSysWebsites;
    
    use dr\modules\Mod_Sys_Websites\controllers\TCRUDDetailSaveWebsites;    

            
    //session started in bootstrap
    include_once '../../bootstrap_cms_auth.php';
        
    
    //return url
    $sReturnURL = 'index.php';


    $objModel = new dr\classes\models\TSysWebsites(); 

    $objCRUD = new TCRUDDetailSaveWebsites($objModel, $sReturnURL);
    
    $objForm = $objCRUD->getForm();
    
    //====== page defaults
    
    // if ($objModel->getNew())
    // {          
    //     $sTitle = transm($sCurrentModule, 'detail_title_newrecord', $sCurrentModule.': Add record to database');
    // }
    // else
    //     $sTitle = transcms('detail_title_edititem', 'Edit: [item]', 'item', $objModel->getGUIItemName());
    if ($objModel->getNew())   
        $sTitle = transcms(TRANS_DETAILSAVE_CREATERECORD_TITLE, '[module]: Add record to database', 'module', $sCurrentModule);
    else
        $sTitle = transcms(TRANS_DETAILSAVE_EDITRECORD_TITLE, 'Edit: [item]', 'item', $objModel->getGUIItemName());

   
    $sHTMLTitle = $sTitle;
    $sHTMLMetaDescription = $sTitle;



    //============ RENDER de templates

    $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_modeldetailsave.php', get_defined_vars());

    echo renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withmenu.php', get_defined_vars());
    

?>