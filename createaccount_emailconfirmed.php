<?php
    
    use dr\classes\controllers\TCMSLoginController;

/**
 * the page that is called when a user clicks on the link in an email to create an account
 */

    //session started in bootstrap
    include_once 'bootstrap_cms.php';



    $objLoginController = new TCMSLoginController();    
    $objLoginController->handleCreateAccountEmailConfirmed();
    $sURLBackToLogin = $objLoginController->getURLLoginForm();



    $sTitle = transcms('createaccount_emailconfirmation_title', 'Account activation');
    $sHTMLTitle = transcms('createaccount_emailconfirmation_htmltitle', 'Account activation');
    $sHTMLMetaDescription = transcms('createaccount_emailconfirmation_htmlmetadescription', 'Account activation');

    

    //============ RENDER de templates


    $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_createaccount_emailconfirmed.php', get_defined_vars());

    $sContentsPage = '';
    $sContentsPage = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withoutmenu.php', get_defined_vars());

    echo $sContentsPage;


?>

