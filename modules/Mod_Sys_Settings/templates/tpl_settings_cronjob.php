<?php

    use dr\modules\Mod_Sys_Settings\Mod_Sys_Settings; 
 
?>


<h2><?php echo transcms('settings_cronjob_h2', 'cron job'); ?></h2>

<?php echo transm($sCurrentModule, 'settings_cronjob_explanation', 'A cronjob executes a certain job on a regular time basis (once a day/once a week) to keep this system running smoothly.<br>Point the cronjob manager to url:<br><br><b>[url]</b><br><br>You can trigger this cronjob by manually by clicking on the button below:', 'url', getURLCMSCronjob())?><br>

<?php
    if (auth($sCurrentModule, Mod_Sys_Settings::PERM_CAT_CRONJOB, Mod_Sys_Settings::PERM_OP_EXECUTE))    
    {
        ?>
            <input type="button" class="button_normal" onclick="openInNewTab('<?php echo getURLCMSCronjob(); ?>')" value="<?php echo transcms('settings_execute_cronjobnow', 'execute cron job now'); ?>">
        <?php
    }
?>