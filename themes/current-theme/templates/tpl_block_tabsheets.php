<?php

    //display tabsheets

 
    //declarations (for speed)
    $sTempTabUrl = '';
    $sTempTabPermCat = '';
    $sTempTabNameTrans = '';
    $sTempTabDescriptionTrans = '';
    $sTempLiClass = '';
    $bShowTab = false;

    if (count($arrTabsheets) > 1)//dont show tabs, if there is only one tab
    {
        echo '<div class="module_tabsheets">';
        echo '<ul>';

        foreach($arrTabsheets as $arrTab)
        {
            $bShowTab = false; //default

            //cms tabs or module tabs?
            if ($sCurrentModule) //module
            {
                $sTempTabUrl = $arrTab[0];
                $sTempTabPermCat = $arrTab[1];
                $sTempTabNameTrans = transm($sCurrentModule, 'tabsheets_module_name_'.$arrTab[2],$arrTab[2]);
                $sTempTabDescriptionTrans = transm($sCurrentModule, 'tabsheets_module_explanation_'.$arrTab[3], $arrTab[3]);
                $bShowTab = auth($sCurrentModule, $sTempTabPermCat, AUTH_OPERATION_VIEW);
            }
            else
            {
                $sTempTabUrl = $arrTab[0];
                $sTempTabPermCat = $arrTab[1];
                $sTempTabNameTrans = transcms('tabsheets_cms_name_'.$arrTab[2],$arrTab[2]); //possible name collision if tabs have the same names on multiple pages
                $sTempTabDescriptionTrans = transcms('tabsheets_cms_explanation_'.$arrTab[3], $arrTab[3]); //possible name collision if tabs have the same names on multiple pages
                $bShowTab = auth(AUTH_MODULE_CMS, $sTempTabPermCat, AUTH_OPERATION_VIEW);
            }


            if ($bShowTab)
            {
                //if tabsheet is selected
                $sTempLiClass = '';
                
                //@todo tab selection doesn't work when url parameter exists
                if (endswith($sURLThisScript, $sTempTabUrl)) 
                    $sTempLiClass = ' class="selected"';
                echo '<li'.$sTempLiClass.'>';
                echo '<a href="'.$sTempTabUrl.'" title="'.$sTempTabDescriptionTrans.'">';                
                echo $sTempTabNameTrans;
                echo '</a>';
                echo '</li>'."\n";                 
            }
        }

        echo '</ul>';
        echo '</div>';    
    }
    
    //unset used vars prevent possible collisions with names later in scirpts
    unset($sTempTabUrl);
    unset($sTempTabPermCat);
    unset($sTempTabNameTrans);
    unset($sTempTabDescriptionTrans);
    unset($sTempLiClass);

?>