<?php
    
    use dr\classes\controllers\TCMSLoginController;

/**
 * when a user lost his password and wants to request a new one
 */

    //session started in bootstrap
    include_once 'bootstrap_cms.php';



    $objLoginController = new TCMSLoginController();    
    $objLoginController->populateFormPasswordRecoverEnterPassword();
    $objLoginController->handlePassworRecoverEnterPassword();
    $sURLBackToLogin = $objLoginController->getURLLoginForm();



    $sTitle = transcms('passwordrecover_enterpassword_title', 'Reset password');
    $sHTMLTitle = transcms('passwordrecover_enterpassword_htmltitle', 'Reset password');
    $sHTMLMetaDescription = transcms('passwordrecover_enterpassword_htmlmetadescription', 'Reset password');

    

    //============ RENDER de templates


    $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_passwordrecover_enternewpassword.php', get_defined_vars());

    $sContentsPage = '';
    $sContentsPage = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withoutmenu.php', get_defined_vars());

    echo $sContentsPage;


?>

