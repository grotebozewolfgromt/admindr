<?php


    use dr\classes\controllers\TCMSLoginController;
    use dr\classes\models\TSysCMSPermissionsCountries;
    use dr\classes\models\TSysCountries;

    
    //session started in bootstrap
    include_once 'bootstrap_cms.php';
    include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_cms.php');
    include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_cms_url.php');
       
    $sMessage = '';


    $objLoginController = new TCMSLoginController();    
    $objLoginController->populateFormLogin();
    $objLoginController->handleLoginLogout();

    $objPermCountries = new TSysCMSPermissionsCountries();
    $objTempCountries = new TSysCountries();  
    $objPermCountries->select(array(TSysCountries::FIELD_ID));
    $objPermCountries->select(array(TSysCountries::FIELD_COUNTRYNAME), $objTempCountries);               
    $objPermCountries->loadFromDB(true);
    //unset($objTempCountries);
    
    
    
    //====== page defaults
    
    
    $sTitle = GLOBAL_CMS_APPLICATIONNAME;
    $sHTMLTitle = transcms('index_htmltitle', '[applicationname] login','applicationname', GLOBAL_CMS_APPLICATIONNAME);
    $sHTMLMetaDescription = transcms('index_htmlmetadescription', 'Protected sitemanager environment');

//    $sMetaOGType = 'article';
//    $sMetaOGTitle = $arrUitje['s_onderwerp'];
//    $sMetaOGDescription = $arrUitje['s_htmldescription'];
//    $sMetaOGImage = GLOBAL_PATH_WWW.'uploads/images/'.$arrUitje['s_plaatjeurlklein'];
//    $sMetaOGImageHeight = 200;
//    $sMetaOGImageWidth = 200;
//    $arrMetaOGArticleTags = $arrTags;
    
    
    
   
    
    
    //============ RENDER de templates
    $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_loginform.php', get_defined_vars());

    $sContentsPage = '';
    $sContentsPage = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withoutmenu.php', get_defined_vars());

    echo $sContentsPage;

?>