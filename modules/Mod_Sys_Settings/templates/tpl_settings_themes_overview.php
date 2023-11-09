<?php

use dr\modules\Mod_Sys_Settings\controllers\settings_themes_overview;
use dr\modules\Mod_Sys_Settings\Mod_Sys_Settings; 
 
?>


<h2><?php echo transcms('settings_themes_overview', 'Themes'); ?></h2>

<?php
    //old version:
    // $sThisScript = '';
    // $sThisScript = getURLThisScript();
    // foreach($objArrThemes as $sTemplate)
    // {
    //     $sUrl = '';
    //     $sUrl = $sThisScript;
    //     $sUrl = removeVariableFromURL($sUrl, settings_themes_overview::ARRAYKEY_GET_INSTALLTHEMENAME); //remove old install variable
    //     $sUrl = addVariableToURL($sUrl, settings_themes_overview::ARRAYKEY_GET_INSTALLTHEMENAME, $sTemplate); //add new install variable

    //     $bInstalled = false;
    //     $sCleanName = '';
    //     $sCleanName = str_replace(INSTALLED_POSTFIX, '', $sTemplate);
        
    
        
    //     echo $sCleanName;
    //     if ($sCleanName != $sTemplate)
    //         echo ' (currently installed)';

    //     echo '<a href="'.$sUrl.'">install</a><br>';
    // }



    $sThisScript = '';
    $sTransInstall = '';
    $sTransInstalled = '';

    $sThisScript = getURLThisScript();
    $sTransInstall = transm($sCurrentModule, 'error_themes_overview_text_installtheme','Install');
    $sTransInstalled = transm($sCurrentModule, 'error_themes_overview_text_themecurrentlyinstalled','INSTALLED');

    foreach($objArrThemes as $sTemplate)
    {
        $sUrl = '';
        $sUrl = $sThisScript;
        $sUrl = removeVariableFromURL($sUrl, settings_themes_overview::ARRAYKEY_GET_INSTALLTHEMENAME); //remove old install variable
        $sUrl = addVariableToURL($sUrl, settings_themes_overview::ARRAYKEY_GET_INSTALLTHEMENAME, $sTemplate); //add new install variable

        $bInstalled = false;
        $sCleanName = '';
        $sCleanName = str_replace(INSTALLED_POSTFIX, '', $sTemplate);

    //     echo $sCleanName;
    //     if ($sCleanName != $sTemplate)
    //         echo ' (currently installed)';

    //     echo '<a href="'.$sUrl.'">install</a><br>';        
        ?>
            <div class="tilebox tileboxwithoutsub">
                <div class="tileboxinner">
                    <!-- <a href="<?php echo $sUrl; ?>"> -->
                        <div class="tileimage">
                            <img src="<?php echo GLOBAL_PATH_WWW_CMS_THEMES_ALLTHEMES.'/'.$sTemplate.'/thumbnail.png'; ?>" alt="<?php echo $sCleanName; ?>">
                        </div>
                        <div class="titletitle">
                            <?php 
                                // echo transm($objSysModulesDB->getNameInternal(), $objSysModulesDB->getNameInternal(), $objSysModulesDB->getNameDefault()); 
                                echo $sCleanName.'<br>';
                                if ($sCleanName != $sTemplate)//is currently installed?
                                    echo '<b>['.$sTransInstalled.']</b>';
                                else
                                    echo '<a href="'.$sUrl.'">'.$sTransInstall.'</a>';
                            ?>
                        </div>
                    <!-- </a> -->
                </div>
            </div>
        <?php

    }


/*
    if (auth($sCurrentModule, Mod_Sys_Settings::PERM_CAT_CRONJOB, Mod_Sys_Settings::PERM_OP_EXECUTE))    
    {
        ?>
            <input type="button" class="button_normal" onclick="openInNewTab('<?php echo getURLCMSCronjob(); ?>')" value="<?php echo transcms('settings_execute_cronjobnow', 'execute cron job now'); ?>">
        <?php
    } */
?>