

	<div class="header">
            <div class="headertop">
                <div class="headerlogouticon">
                    <a href="#" onclick="confirmLogout();"><img src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES; ?>/icon-logout.png" alt="logout"></a>
		        </div> <!-- end headerlogouticon -->   
                
                <div class="headerhomeicon">
                    <a href="<?php echo getURLCMSDashboard(); ?>"><img src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES; ?>/icon-home.png" alt="home"></a>
                </div> <!-- end headerhomeicon -->   
                
                <?php
                    if (auth(AUTH_MODULE_CMS, AUTH_CATEGORY_SYSSETTINGS, AUTH_OPERATION_SYSSETTINGS_VIEW))
                    {
                        ?>
                            <div class="headersettingsicon"> <!-- door de float right worden menuicon en callicon omgedraaid -->
                                <a href="<?php echo getURLCMSSettings(); ?>"><img src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES; ?>/icon-settings.png" alt="settings"></a>
                            </div> <!-- end headersettingsicon  -->
                        <?php
                    }
                ?>

            </div>

            <?php
                if (auth(AUTH_MODULE_CMS, AUTH_CATEGORY_SYSSITES, AUTH_OPERATION_SYSSITES_VISIBILITY) && GLOBAL_CMS_SHOWWEBSITESINNAVIGATION)
                {
                    ?>
                        <div class="headerselectedwebsite">
                            <?php                 
                                $objWebsites->setRecordPointerToValue(\dr\classes\models\TModel::FIELD_ID, GLOBAL_WEBSITEID_SELECTEDINCMS);
                                echo $objWebsites->getWebsiteName();
                            ?>
                        </div>       
                    <?php
                }
            ?>
   
            
            <?php 
                if (isset($_GET[GETARRAYKEY_CMSMESSAGE_SUCCESS]))
                {
                    ?>
                    <div class="headermessagesuccess"><?php echo $_GET[GETARRAYKEY_CMSMESSAGE_SUCCESS]?></div>
                    <script>
                        $('.headermessagesuccess').click(function () {
                            $(this).slideUp().empty();
                            });
                            

                            setTimeout(function() {
                                $('.headermessagesuccess').slideUp(function() {});
                            }, 3000);
                    </script>
                    <?php
                }

                if (isset($_GET[GETARRAYKEY_CMSMESSAGE_ERROR]))
                {
                    ?>
                    <div class="headermessageerror">
                        <?php echo $_GET[GETARRAYKEY_CMSMESSAGE_ERROR]?>&nbsp;[<?php echo transcms('message_error_clicktodismiss', 'click to dismiss'); ?>]
                    </div>
                    <script>
                        $('.headermessageerror').click(function () {
                            $(this).slideUp().empty();
                            });
                    </script>
                    <?php
                
                }
                
                if (isset($_GET[GETARRAYKEY_CMSMESSAGE_NOTIFICATION]))
                {
                    ?>
                    <div class="headermessagenotification">
                        <?php echo $_GET[GETARRAYKEY_CMSMESSAGE_NOTIFICATION]?>
                    </div>
                    <script>
                        $('.headermessagenotification').click(function () {
                            $(this).slideUp().empty();
                            });
                            
                        setTimeout(function() {
                                $('.headermessagenotification').slideUp(function() {});
                            }, 3000);                                
                    </script>
                    <?php
                
                }                    
            ?>            
	</div> <!-- end header -->