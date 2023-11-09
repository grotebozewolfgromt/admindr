
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
            //can be null if password email is sent
            if ($objLoginController->getFormPasswordRecover()) //it can be null because of too many login attempts
            {
                echo transcms('passwordrecover_message_enterpassword', 'Please enter your new password').'<br>';
                echo '<br>';

                echo $objLoginController->getFormPasswordRecover()->generate()->renderHTMLNode();
            }
        ?><br>
        <div id="loginlostpassword">
            <a href="<?php echo $sURLBackToLogin; ?>"><?php echo transcms('passwordrecover_link_backtologin', 'Back to login page'); ?></a>
        </div>
    </div>        
</div>


