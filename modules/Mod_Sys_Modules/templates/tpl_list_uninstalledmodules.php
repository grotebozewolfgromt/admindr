
<?php

/**
 * Overview module: sys_modules
 */
    use dr\classes\models\TModel;
    use dr\classes\models\TSysModules;
    use dr\classes\models\TSysModulesCategories;
    use dr\classes\controllers\TCRUDListController;
use dr\classes\patterns\TModuleAbstract;
use dr\modules\Mod_Sys_Modules\Mod_Sys_Modules;

// include_once GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php'; //getNextSortOrder
    

    $bAllowInstall = auth($sCurrentModule, Mod_Sys_Modules::PERM_CAT_MODULESUNINSTALLED, Mod_Sys_Modules::PERM_OP_INSTALL);
    $bAllowDelete = auth($sCurrentModule, Mod_Sys_Modules::PERM_CAT_MODULESUNINSTALLED, TModuleAbstract::PERM_OP_DELETE);
    $sTransInstall = transm($sCurrentModule, 'button_install', 'install');
    $sTransDelete = transm($sCurrentModule, 'button_delete', 'delete');  
    $sURLThisScript = getURLThisScript();
?>
<form action="<?php echo getURLThisScript();?>" method="get" name="frmBulkActions" id="frmBulkActions">
     
    <div class="overview_table_background">
        <table class="overview_table">
            <thead>
                <tr> 
                    <th class="column-display-on-mobile">
                        <?php echo transm($sCurrentModule, 'column-display-on-mobile-header', 'Module') ?>
                    </th>        
                    <th class="column-display-on-desktop">
                        <?php echo transm($sCurrentModule, 'column-display-on-mobile-header', 'Module') ?>
                    </th>                          
                    <th>
                        <?php 
                            //=========== CREATE NEW ========
                            if ($bAllowInstall)
                            {
                                ?>                            
                                    <input type="button" onclick="window.location.href = '<?php echo $sURLDetailPage; ?>';" value="<?php echo transm($sCurrentModule, 'item_uploadmodule', 'upload module'); ?>" class="button_normal">
                                <?php
                            }
                        ?>   
                    </th>                              
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($arrUninstalledModules as $sCurrUninstalledModule)
                    {                  
                        ?>
                        <tr>
                            <td class="column-display-on-mobile">
                                <?php echo $sCurrUninstalledModule; ?>
                            </td>             
                            <td class="column-display-on-desktop">
                                <?php echo $sCurrUninstalledModule ?>
                            </td>           
                            <td>

                                <?php 
                                
                                    if (isset($sURLDetailPage) && $bAllowInstall)
                                    {
                                        ?>
                                        <input type="button" onclick="window.location.href = '<?php echo addVariableToURL($sURLDetailPage, ACTION_VARIABLE_ID, $sCurrUninstalledModule); ?>';" value="<?php echo $sTransInstall ?>" class="button_normal">
                                        <?php
                                    }
                                    else
                                        echo '&nbsp;';   
                                    
                                    if ($bAllowDelete)
                                    {
                                        ?>
                                        <input type="button" onclick="window.location.href = '<?php echo addVariableToURL(addVariableToURL($sURLThisScript, ACTION_VARIABLE_DELETE, '1'), ACTION_VARIABLE_ID, $sCurrUninstalledModule); ?>';" value="<?php echo $sTransDelete ?>" class="button_cancel">
                                        <?php                                    
                                    }
                                    else
                                        echo '&nbsp;';   
                                
                                    
                                ?>

                            </td>                                    
                        </tr>			
                        <?php
                    }
                ?>
            </tbody>
        </table>    
    </div>        
    
    <?php   
        //==== NO RECORDS? ====
        if (!$arrUninstalledModules)
        {
            echo '<center>';
            echo '<img src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-alert-grey128x128.png"><br>';
            echo transm($sCurrentModule, 'message_nomodulestodisplay','[ all available modules are installed ]');
            echo '<br>';
            echo '</center>';
        }
    ?>    
        
   
</form>   


