<?php 
use dr\classes\models\TSysCMSPermissionsCountries;
use dr\classes\models\TSysCountries;

?>            
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
                        if ($objLoginController->getFormLogin()) //it can be null because of too many login attempts
                        {
                            echo $objLoginController->getFormLogin()->generate()->renderHTMLNode();
                        }
                        else
                            echo transw('loginform_logindisabled', 'Login disabled');

                        if ($objLoginController->getShowPasswordRecoverLink())
                            echo '<div id="loginlostpassword"><a href="'.$objLoginController->getURLPasswordRecoverEnterEmail().'">'.transcms('loginform_lostpassword_link','I forgot my password').'</a></div>';

                        if ($objLoginController->getShowCreateAccountLink())                            
                            echo '<div id="logincreateaccount"><a href="'.$objLoginController->getURLCreateAccountEnterCredentials().'">'.transcms('loginform_createaccount_link','Create a new account').'</a></div>';

                    ?>
                    <div id="logincookiesettings"><a href="#" data-cc="c-settings"><?php echo transcms('loginform_cookiesettings_link','Cookie settings')?></a></div>

                    <div id="serviceavailable">
                        <?php
                            echo transcms('login_serviceonlyavailable_countries', 'This service is available in:');
                            echo '<br>';
                            while ($objPermCountries->next())
                            {
                                if (!$objPermCountries->isFirstRecord())
                                    echo ', ';                                
                                echo $objPermCountries->get(TSysCountries::FIELD_COUNTRYNAME, TSysCountries::getTable());
                            }
                        ?>
                    <div>


                </div>    
                
            </div>
