<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $sHTMLTitle ?></title>
        <meta name="description" content="<?php echo $sHTMLMetaDescription ?>">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="shortcut icon" type="image/png" href="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/projecticons/icon128.png">
        <meta name="viewport" content="width=300, initial-scale=1">
        <link rel="stylesheet" href="<?php echo GLOBAL_PATH_WWW_CMS ?>/vendor/cookieconsent/cookieconsent.css" media="print" onload="this.media='all'">
        <script defer src="<?php echo GLOBAL_PATH_WWW_CMS ?>/vendor/cookieconsent/cookieconsent.js"></script>
        <script defer src="<?php echo GLOBAL_PATH_WWW_CMS ?>/vendor/cookieconsent/cookieconsent-init.js"></script>                   
        <link href="<?php echo GLOBAL_PATH_WWW_CMS_STYLESHEETS ?>/loggedout.css" rel="stylesheet" type="text/css">        
        <?php
            /*
            if (isset($objLoginController))
            {
                if ($objLoginController->getUseRecapthaLogin())
                {
                    $sElementID = '';
                    if ($objLoginController->getFormLogin())
                        $sElementID = $objLoginController->getFormLogin()->getForm()->getID();
                    if ($objLoginController->getFormPasswordRecover())
                        $sElementID = $objLoginController->getFormPasswordRecover()->getForm()->getID();
                    if ($objLoginController->getFormCreateAccount())
                        $sElementID = $objLoginController->getFormCreateAccount()->getForm()->getID();


                    if ($sElementID !=  '')
                    {
                        ?>
                            <script src="https://www.google.com/recaptcha/api.js"></script>
                            <script>
                                function onSubmitRecaptcha(token) 
                                {
                                    document.getElementById("<?php echo $sElementID; ?>").submit();
                                }
                            </script>
                        <?php
                    }
                }
            }
            */
        ?>
    </head>
    <body>    
        <div id="page">
            <?php echo $sHTMLContentMain; ?>
        </div>            
    </body>

</html>