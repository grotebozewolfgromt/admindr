<?php
    
    use dr\classes\controllers\TCMSLoginController;

/**
 * when a user wants to create a new account
 */

    //session started in bootstrap
    include_once 'bootstrap_cms.php';



    $objLoginController = new TCMSLoginController();    
    $objLoginController->populateFormCreateAccountEnterCredentials();
    $objLoginController->handleCreateAccountEnterCredentials();
    $sURLBackToLogin = $objLoginController->getURLLoginForm();



    $sTitle = transcms('createaccount_entercredentials_title', 'Create a new account');
    $sHTMLTitle = transcms('createaccount_entercredentials_htmltitle', 'Create a new account');
    $sHTMLMetaDescription = transcms('createaccount_entercredentials_htmlmetadescription', 'Create a new account');

    

    //============ RENDER de templates


    $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_createaccount_entercredentials.php', get_defined_vars());

    $sContentsPage = '';
    $sContentsPage = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withoutmenu.php', get_defined_vars());

    echo $sContentsPage;


?>

