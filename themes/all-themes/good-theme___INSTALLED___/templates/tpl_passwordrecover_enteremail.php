
<div id="loginfieldouterbox">
    <img id="loginuserimage" src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/login-user.png" alt="login user image">
    <?php 
        if ($objLoginController->getMessageNormal())
        {
            ?>
                <div id="messagenormal">
                    <?php echo $objLoginController->getMessageNormal() ?>
                </div>    
            <?php
        }
        if ($objLoginController->getMessageError())
        {
            ?>
                <div id="messageerror">
                    <?php echo $objLoginController->getMessageError() ?>
                </div>    
            <?php
        }                                        
    ?>
    <div id="loginfieldbox">            
        <h1><?php echo $sTitle ?></h1>
        <?php
            if ($objLoginController->getFormPasswordRecover()) //it can be null because of too many login attempts or form submitted
            {
                echo transcms('passwordrecover_message_enteremailaccount', 'Enter the emailaddress that is associated with your account').'<br>';
                echo transcms('passwordrecover_message_enteremailinstructrions', 'We\'ll email you instructions on how to reset your password').'<br>';
                echo '<br>';

                echo $objLoginController->getFormPasswordRecover()->generate()->renderHTMLNode();
            }
        ?><br>
        <div id="loginlostpassword">
            <a href="<?php echo $sURLBackToLogin; ?>"><?php echo transcms('passwordrecover_link_backtologin', 'Back to login page'); ?></a>
        </div>
    </div>        
</div>


