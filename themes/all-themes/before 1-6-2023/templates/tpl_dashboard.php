<?php
    use dr\classes\models\TSysModules;
    use dr\classes\models\TSysModulesCategories;    
 
?>
<!--
<div class="tilesenclosure">
    <div class="tilebox tileboxwithoutsub">
        <div class="tileboxinner">
            <a href="">
                <div class="tileimage">
                    <img src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/icon-module128x128.png" alt="">
                </div>
                <div class="titletitle">
                    blaat
                </div>
            </a>
        </div>
    </div>
</div>
-->


<h2><?php echo transcms('tpl_home_h2_modules', 'modules'); ?></h2>



<?php

    //display
    $arrKeys = array_keys($arrCats);
    foreach($arrKeys as $sCatName)
    {
        $arrMods = $arrCats[$sCatName];

        echo '<h3>'.$sCatName.'</h3>';
        echo '<div class="tilesenclosure">';

        foreach ($arrMods as $iIndexMod)
        {
            $objSysModulesDB->setRecordPointerToIndex($iIndexMod);

            $sIconPath = GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-module128x128.png';  //default                              
            if (is_file(getPathModuleImages($objSysModulesDB->getNameInternal()).DIRECTORY_SEPARATOR.'icon-module128x128.png'))
                $sIconPath = getURLModuleImages($objSysModulesDB->getNameInternal()).'/icon-module128x128.png'; 
            
            //$bModDirExists = file_exists(GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$objSysModulesDB->getNameInternal());                                 
            
            ?>
            <div class="tilebox tileboxwithoutsub">
                <div class="tileboxinner">
                    <a href="<?php echo getURLModule($objSysModulesDB->getNameInternal()); ?>">
                        <div class="tileimage">
                            <img src="<?php echo $sIconPath ?>" alt="<?php echo str_replace('"', '', transm($objSysModulesDB->getNameInternal(), $objSysModulesDB->getNameInternal(), $objSysModulesDB->getNameDefault())); ?>">
                        </div>
                        <div class="titletitle">
                            <?php 
                                echo transm($objSysModulesDB->getNameInternal(), $objSysModulesDB->getNameInternal(), $objSysModulesDB->getNameDefault()); 
                            ?>
                        </div>
                    </a>
                </div>
            </div>
            <?php                                 
        }                                

        echo '</div>'; //end tilesenclosure
    }
                            
                        
//    $sIconPath = '';
//    foreach($arrSysModules as $sModule)
//    {
//        $sIconPath = GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-module16x16.png';                                
//        if (is_file(getPathModuleImages($sModule).DIRECTORY_SEPARATOR.'icon-module16x16.png'))
//            $sIconPath = getURLModuleImages($sModule).'/icon-module16x16.png';
//        echo '<img src="'.$sIconPath.'"><a href="'. getURLModule($sModule).'/index.php">'.transm($sModule, 'cmsmodulelist_modulename',$sModule).'</a><br>';
//    }
                      

    //====CMS 2 modules
    //categorien weergeven en hun modules
    for($iTeller=0;$iTeller < count($arrModuleCaths);$iTeller++)
    {
            ?>
            <h3><?php echo str_replace("_", "&nbsp;", $arrModuleCaths[$iTeller]); //underscores vervangen door spaties?></h3>
            <div class="tilesenclosure">
            <?php
            //================modules van deze categorie weergeven


            //array van modules doorlopen en weergeven  --> opbouw array : localpath van script, wwwpath van script, volgnummer, naam van de module, minimaal dit recht hebben om te kunnen gebruiken, category
            for($iTeller3=0;$iTeller3 < count($arrModules);$iTeller3++)
            {
                    $arrCurrModule = $arrModules[$iTeller3];//1 item uit de modulearray pakken

                    if ($arrCurrModule[5] == $arrModuleCaths[$iTeller]) //goede categorie ?
                    {
//			var_dump($_SESSION);
//                        die();
                        if (($_SESSION['iAdminUserLevel'] <= $arrCurrModule[4]) && (isset($_SESSION['iAdminUserLevel']))) //heb je wel voldoende rechten ?
                        {

                         //   echo "&nbsp;<a href=\"".$arrCurrModule[1]."?titelsub=".$arrCurrModule[3]."\"><img border=\"0\" src=\"".GLOBAL_PATH_WWW_CMS_IMAGES."/icon-module16x16.png\"></a>&nbsp;<a href=\"".$arrCurrModule[1]."?titelsub=".$arrCurrModule[3]."\">".str_replace(" ", "&nbsp;", $arrCurrModule[3])."</a><br>\n";
                            ?>
                            <div class="tilebox tileboxwithoutsub">
                                <div class="tileboxinner">
                                    <a href="<?php echo $arrCurrModule[1] ?>?titelsub=<?php echo $arrCurrModule[3] ?>">
                                        <div class="tileimage">
                                            <img src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/icon-module128x128.png" alt="">
                                        </div>
                                        <div class="titletitle">
                                            <?php echo str_replace(" ", "&nbsp;", $arrCurrModule[3]); ?>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <?php                            
                        }
                    }
            }
            ?>
            </div> <!-- EIND: tilesenclosure -->            
            <?php
    }                        
?>



<h2><?php echo transcms('tpl_home_h2_websites', 'websites'); ?></h2>
<div class="tilesenclosure">
    <?php 
//        $objSelectBox = null;
//        $objSelectBox = $objWebsites->generateHTMLSelect();
//        $objSelectBox->setSelectedOption(GLOBAL_WEBSITEID_SELECTED);
//        echo $objSelectBox->renderHTMLNode();
    
    
    /*
    $arrSites = mysqliToArray("SELECT * FROM $tblWebsites ORDER BY i_id");   
    foreach ($arrSites as $arrSite)
    {
        $sSelected = '';
        if ($arrSite['i_id'] == $_SESSION['iSelectedSiteID'])
                $sSelected = ' selected';                            
        ?>
        <div class="tilebox tileboxwithoutsub">
            <div class="tileboxinner">
                <a href="<?php echo GLOBAL_PATH_WWW_CMS ?>/home.php?selectedSiteID=<?php echo $arrSite['i_id'] ?>">
                    <div class="tileimage">
                        <img src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/icon-website128x128.png" alt="">
                    </div>
                    <div class="titletitle">
                        <?php echo $arrSite['s_domein']; ?>
                    </div>
                </a>
            </div>
        </div>
        <?php
    }
    */

    if (auth(AUTH_MODULE_CMS, AUTH_CATEGORY_SYSSITES, AUTH_OPERATION_SYSSITES_VISIBILITY))
    {

        $objWebsites->resetRecordPointer();
        while($objWebsites->next())
        {
            ?>
                <div class="tilebox tileboxwithoutsub">
                    <div class="tileboxinner">
                        <a href="<?php echo addVariableToURL(getURLCMSDashboard(), GETARRAYKEY_SELECTEDSITEID, $objWebsites->getID()) ?>">
                            <div class="tileimage">
                                <img src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/icon-website128x128.png" alt="">
                            </div>
                            <div class="titletitle">
                                <?php  echo $objWebsites->getWebsiteName(); ?>
                            </div>
                        </a>
                    </div>
                </div>
            <?php
        }
    }
    
    ?>
</div>                        
                                      