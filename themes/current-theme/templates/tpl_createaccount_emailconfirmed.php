<div id="loginfieldouterbox">
    <img id="loginuserimage" src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/login-user.png" alt="login user image">
    <div id="loginfieldbox">            
        <h1><?php echo $sTitle ?></h1>
        <?php
            if ($objLoginController->getMessageNormal())
            {
                echo $objLoginController->getMessageNormal();
            }
            if ($objLoginController->getMessageError())
            {
                echo $objLoginController->getMessageError();
            }   

        ?><br>
        <div id="loginlostpassword">
            <a href="<?php echo $sURLBackToLogin; ?>"><?php echo transcms('createaccount_link_backtologin', 'Back to login page'); ?></a>
        </div>
    </div>        
</div>


