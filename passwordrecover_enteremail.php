<?php
    
    use dr\classes\controllers\TCMSLoginController;

/**
 * when a user lost his password and wants to request a new one
 */

    //session started in bootstrap
    include_once 'bootstrap_cms.php';



    $objLoginController = new TCMSLoginController();    
    $objLoginController->populateFormPasswordRecoverEnterEmail();
    $objLoginController->handlePassworRecoverEnterEmail();
    $sURLBackToLogin = $objLoginController->getURLLoginForm();



    $sTitle = transcms('passwordrecover_enteremail_title', 'Request a new password');
    $sHTMLTitle = transcms('passwordrecover_enteremail_htmltitle', 'Request a new password');
    $sHTMLMetaDescription = transcms('passwordrecover_enteremail_htmlmetadescription', 'Request a new password');

    

    //============ RENDER de templates


    $sHTMLContentMain = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_passwordrecover_enteremail.php', get_defined_vars());

    $sContentsPage = '';
    $sContentsPage = renderTemplate(GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withoutmenu.php', get_defined_vars());

    echo $sContentsPage;


?>

