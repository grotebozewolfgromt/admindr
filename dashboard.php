<?php
    
    //session started in bootstrap
    include_once 'bootstrap_cms_auth.php';
        


    
    
    //====== page defaults
    
    
    
    $sTitle = transcms('home_title', '[applicationname] Dashboard', 'applicationname', GLOBAL_CMS_APPLICATIONNAME);
    $sHTMLTitle = transcms('home_htmltitle', '[applicationname] Dashboard', 'applicationname', GLOBAL_CMS_APPLICATIONNAME);
    $sHTMLMetaDescription = transcms('home_htmlmetadescription', '[applicationname] Dashboard', GLOBAL_CMS_APPLICATIONNAME);

//    $sMetaOGType = 'article';
//    $sMetaOGTitle = $arrUitje['s_onderwerp'];
//    $sMetaOGDescription = $arrUitje['s_htmldescription'];
//    $sMetaOGImage = GLOBAL_PATH_WWW.'uploads/images/'.$arrUitje['s_plaatjeurlklein'];
//    $sMetaOGImageHeight = 200;
//    $sMetaOGImageWidth = 200;
//    $arrMetaOGArticleTags = $arrTags;
    
    
     //temp test   
//    $objYrmp = new dr\modules\Mod_Sys_Localisation\TTemp();
//    $objYrmp->test();
//    include(GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.'mod_sys_languages/lib/lib_temp.php');
//    
//    tempfunc();

    

        //============ RENDER de templates

        $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_dashboard.php', get_defined_vars());
    
        $sContentsPage = '';
        $sContentsPage = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withmenu.php', get_defined_vars());

        echo $sContentsPage;
?>