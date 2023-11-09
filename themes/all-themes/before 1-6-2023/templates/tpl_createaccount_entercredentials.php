
<div id="loginfieldouterbox">
    <img id="loginuserimage" src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/login-user.png" alt="login user image">
    <?php 
        //errormessages on top
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
            //success messages in box
            if ($objLoginController->getMessageNormal())
            {
                echo $objLoginController->getMessageNormal();
            }                                    
        

            if ($objLoginController->getFormCreateAccount()) //it can be null because of too many login attempts or form submitted
            {
                echo transcms('createaccount_message_entercredentials', 'Enter your credentials to be associated with your account').'<br>';
                echo '<br>';

                echo $objLoginController->getFormCreateAccount()->generate()->renderHTMLNode();
            }

        ?><br>
        <div id="logincreateaccount">
            <a href="<?php echo $sURLBackToLogin; ?>"><?php echo transcms('createaccount_link_backtologin', 'Back to login page'); ?></a>
        </div>
    </div>        
</div>


