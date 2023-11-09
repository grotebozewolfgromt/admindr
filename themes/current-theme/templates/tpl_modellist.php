<?php
/**
 * DEFAULT template: Overview of modules.
 * If you want to specify your own custom overview, add acopy of this template to 
 * the module directory and make sure you load that in the module-controller
 */
    use dr\classes\models\TModel;
    use dr\classes\models\TSysModules;
    use dr\classes\models\TSysModulesCategories;
    use dr\classes\controllers\TCRUDListController;
    
    include_once GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_misc.php'; //getNextSortOrder
    
    //most used translations cachen
    $sTranslatedBooleanYes = '';
    $sTranslatedBooleanYes = transcms('boolean_yes', 'yes');    
    $sTranslatedBooleanNo = '';
    $sTranslatedBooleanNo = transcms('boolean_no', 'no');
    $sTranslatedMoveOneUp = '';
    $sTranslatedMoveOneUp = transcms('recordlist_move_up', 'move record up');
    $sTranslatedMoveOneDown = '';
    $sTranslatedMoveOneDown = transcms('recordlist_move_down', 'move record down');
    $sTranslatedEdit = '';
    $sTranslatedEdit = transcms('recordlist_edit', 'edit record');
    $sTranslatedTranslate = ''; //translate icon
    $sTranslatedTranslate = transcms('recordlist_translate', 'translate record to other language');//translate icon
    
    $bShowTranslateIcon = $objModel->getTableUseTranslationLanguageID();
        
//=========== QUICKSEARCH ========

$sQuickSearchFieldValue = '';
if(isset($_GET[TCRUDListController::FIELD_QUICKSEARCH]))
{
    $sQuickSearchFieldValue = $_GET[TCRUDListController::FIELD_QUICKSEARCH];
}
?>
<div class="overview_quicksearch">
    <form name="frmQuickSearch" id="frmQuickSearch" action="<?php echo $sURLThisScript; ?>">
        <input type="image" src="<?php echo GLOBAL_PATH_WWW_CMS_IMAGES ?>/icon-quicksearch128x128.png" >        
        <input type="search" name="<?php echo TCRUDListController::FIELD_QUICKSEARCH; ?>" class="quicksearchbox" placeholder="<?php echo transcms('overview_edit_quicksearch_default', 'search'); ?>" value="<?php echo $sQuickSearchFieldValue; ?>" onsearch="onQuickSearch(this)">        
    </form>
</div>


<?php
//=========== FILTERS ========
//suspended

?>

<form action="<?php echo $sURLThisScript;?>" method="get" name="frmBulkActions" id="frmBulkActions">
    <div class="overview_table_background">
        <table class="overview_table">
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" name="chkCheckAll" onClick="toggleAllCheckboxes(this, '<?php echo BULKACTION_VARIABLE_CHECKBOX_RECORDID ?>[]')">
                    </th>     
                    <th class="column-display-on-mobile">
                        <?php echo transcms('column-display-on-mobile-header', 'Record') ?>
                    </th>
                    <?php
                        /**
                         * we work with sort column indexes in stead of sort column names to pass through the visible url
                         * to prevent messing with the column names
                         */
                    
                        $sColumnHead = '';
                        $sURL = '';
                        $sSortOrder = ''; //sort order from $_GET[ACTION_VARIABLE_SORTORDER]
                        $arrQBSort = array();
                        $arrQBSort = $objModel->getQBSort();//array(TModel::QB_SORTINDEX_TABLE => $sTable, TModel::QB_SORTINDEX_FIELD => $sField, TModel::QB_SORTINDEX_ORDER => $sSortOrder);                            
                        $arrQBSelect = array(); // to determine on which column to sort
                        $arrQBSelect = $objModel->getQBSelectFrom();   
                        $iCountQBSelect = count($arrQBSelect);
                        $iSortColIndex = 0; //index in $arrQBSelect
                        $arrQBSelectRow = array();
                        
                        //going through every column
                        foreach ($arrTableColumnsShow as $arrColumn)
                        {     
                            $iSortColIndex = 0;
                            $sSortOrder = '';                        
                            $sTableName = $arrColumn[0];
                            $sColumnName = $arrColumn[1];
                            $sColumnHead = $arrColumn[2];                        
                            $bCSSClassTDShowOnDesktop = true; //add css class  showOnDesktop - some columns you want to show on mobile AND desktop (like the sort up-down);
                                
                            //figure out the index of the sortcolumn
                            for ($iSCICounter = 0; $iSCICounter < $iCountQBSelect; $iSCICounter++)
                            {
                                if ($arrQBSelect[$iSCICounter][TModel::QB_SELECTINDEX_FIELD] == $sColumnName)
                                {
                                    if (($arrQBSelect[$iSCICounter][TModel::QB_SELECTINDEX_TABLE] == $sTableName) || $sTableName == '') //tablenames of the current TModel are empty (and later replaced by the real tablename)
                                    {
                                        $iSortColIndex = $iSCICounter;
                                        $iSCICounter = $iCountQBSelect;//jump out of for loop, we found the sort column
                                    }
                                }
                            }
                            
                            //determine sort order
                            foreach($arrQBSort as $arrQBSortItem) //technically there can be multiple rows sorted in model, although not supported (yet) in GUI, because via the url is currently only sort column passed
                            {
                                if ($arrQBSortItem[TModel::QB_SORTINDEX_FIELD] == $sColumnName) //is actually sorted on this column in database?
                                    $sSortOrder = $arrQBSortItem[TModel::QB_SORTINDEX_ORDER];
                            }                            

                            //only add link when NOT an encrypted field
                            $bAddLinkToHead = true;
                            if ($objModel->getFieldsDefinedExists($sColumnName))
                                $bAddLinkToHead = ($objModel->getFieldEncryptionDisabled($sColumnName));

                            if ($bAddLinkToHead)
                            {
                                $sURL = $sURLThisScript;
                                $sURL = addVariableToURL($sURL, ACTION_VARIABLE_SORTCOLUMNINDEX, $iSortColIndex);//sort on column INDEX!!
                                $sURL = addVariableToURL($sURL, ACTION_VARIABLE_SORTORDER, getNextSortOrder($sSortOrder));//sort order
                                $sColumnHead = '<a href="'.$sURL.'">'.$sColumnHead.'</a>';   
                            }

                            //sort order arrows
                            foreach($arrQBSort as $arrQBSortItem) //technically there can be multiple rows sorted in model, although not supported (yet) in GUI, because via the url is currently only sort column passed
                            {
                                if ($arrQBSortItem[TModel::QB_SORTINDEX_FIELD] == $sColumnName) //is actually sorted on this column in database?
                                {
                                    $bCSSClassTDShowOnDesktop = false;

                                    if ($sSortOrder == SORT_ORDER_ASCENDING)
                                    {
                                        $sColumnHead.='<img src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-sortasc16x16.png">';
                                    }

                                    if ($sSortOrder == SORT_ORDER_DESCENDING)
                                    {
                                        $sColumnHead.='<img src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-sortdesc16x16.png">';
                                    }      
                                }
                            }                            

                        
                            ?>                    
                                <th class="<?php if ($bCSSClassTDShowOnDesktop){ echo 'column-display-on-desktop';} ?>"><?php echo $sColumnHead; ?></th>
                            <?php
                        }
                    ?>
                    <th>
                        <?php 
                            //=========== CREATE NEW ========
                            if (auth($sCurrentModule, $objCRUD->getAuthorisationCategory(), AUTH_OPERATION_CREATE))
                            {
                                ?>                            
                                    <input type="button" onclick="window.location.href = '<?php echo $sURLDetailPage; ?>';" value="<?php echo transcms('item_create', 'New'); ?>" class="button_normal">
                                <?php
                            }
                        ?>   
                    </th> 
                    

                </tr>
            </thead>
            <tbody>
                <?php
                
                    
                
                    $bEditAllowed = false;
                    $bEditAllowed = auth($sCurrentModule, $objCRUD->getAuthorisationCategory(), AUTH_OPERATION_CHANGE);
                                                                
                    
                    //ONLY allow up and down if sorted on iOrder
                    $bOrderOneUpDownAllowed = false;
                    $arrQBSortItem = array();
                    if (count($arrQBSort) > 0) 
                    {
                        $arrQBSortItem = $arrQBSort[0]; ///we only have to know if the first column in QBSort is iOrder (more columns doesn't matter, because if it isn't the first sort column, you don't see anything of moving up or down )
                        if ($arrQBSortItem[TModel::QB_SORTINDEX_FIELD] == TModel::FIELD_ORDER) //is actually sorted on iOrder?
                        {
                            if (auth($sCurrentModule, $objCRUD->getAuthorisationCategory(), AUTH_OPERATION_CHANGEORDER)) //if also allowed by authentication, then it is allowed to show
                            {
                                $bOrderOneUpDownAllowed = true;
                            }
                        }
                    }
                
                    
                    while($objModel->next())
                    {                                  
                        //edit alowed?
                        $bEditAllowedThisRecord = true;
                        $bEditAllowedThisRecord = $bEditAllowed; //temp only for this record so we can change the privilges based on locks and checkout

                        //checkout
                        $bRecordCheckedOut = false;
                        if ($objModel->getTableUseCheckout())
                        {
                            $objDateCheckoutExpire = null;
                            $objDateCheckoutExpire = $objModel->getCheckoutExpires();
                            if ($objDateCheckoutExpire->isInTheFuture())
                            {
                                $bRecordCheckedOut = true;
                                $bEditAllowedThisRecord = false;
                            }
                        }

                        //lock
                        $bRecordLocked = false;
                        if ($objModel->getTableUseLock())
                        {
                            if ($objModel->getLocked())
                            {
                                $bRecordLocked = true;                            
                                $bEditAllowedThisRecord = false;
                            }
                        }                        
                        
                        ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="<?php echo BULKACTION_VARIABLE_CHECKBOX_RECORDID ?>[]" value="<?php echo $objModel->getID() ?>" onchange="toggleRowColorCheckboxClick(this)">
                            </td>
                            <td class="column-display-on-mobile">
                                <?php echo $objModel->getGUIItemName(); ?>
                            </td>
                            <?php
                                foreach ($arrTableColumnsShow as $arrColumn)
                                {
                                    $sTableName = $arrColumn[0];
                                    $sColumnName = $arrColumn[1];
                                    $bCSSClassTDShowOnDesktop = true; //add css class  showOnDesktop - some columns you want to show on mobile AND desktop (like the sort up-down);                                
                                    $sColumnValue = '';
                                    $iColType = $objModel->getFieldType($sColumnName);

                                    switch ($iColType)
                                    {
                                            case TP_DATETIME:                                                
                                                    $sColumnValue = $objModel->get($sColumnName, $sTableName, true)->getDateTimeAsString(); 
                                                    break;
                                            case TP_BOOL:  
                                                    if ($objModel->get($sColumnName, $sTableName, true))
                                                        $sColumnValue = '<img alt="'.$sTranslatedBooleanYes.'" src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-checked-true32x32.png">';
                                                    else
                                                        $sColumnValue = '<img alt="'.$sTranslatedBooleanNo.'" src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-checked-false32x32.png">';
                                                    break;
                                            case TP_CURRENCY:
                                                    $sColumnValue = $objModel->get($sColumnName, $sTableName, true)->getValueFormatted();
                                                    break;
                                            case TP_DECIMAL:
                                                    $sColumnValue = $objModel->get($sColumnName, $sTableName, true)->getValueFormatted();
                                                    break;
                                            case TP_FLOAT:
                                                    $sColumnValue = $objModel->get($sColumnName, $sTableName, true)->getAsString();
                                                    break;
                                            default: 
                                                    $sColumnValue = $objModel->get($sColumnName, $sTableName, true);
                                    }            
                                    
                                    //CHANGE ORDER up and down arrows
                                    if ($sColumnName == TModel::FIELD_ORDER)
                                    {
                                        $bCSSClassTDShowOnDesktop = false;
                                        $bOrderOneUpAllowed = $bOrderOneUpDownAllowed; //per item can be disabled if it is the first or last item
                                        $bOrderOneDownAllowed = $bOrderOneUpDownAllowed; //per item can be disabled if it is the first or last item
                                        
                                        if (!$bEditAllowedThisRecord) //record can be locked or checked out 
                                        {
                                            $bOrderOneUpAllowed = false;
                                            $bOrderOneDownAllowed = false;
                                        }
                                        
                                        $sURL = $sURLThisScript;
                                        $sURL = addVariableToURL($sURL, ACTION_VARIABLE_ID, $objModel->getID());//move record id
                                        $sURL = addVariableToURL($sURL, ACTION_VARIABLE_ORDERONEUPDOWN, ACTION_VALUE_ORDERONEUPDOWN);//Change Order 1 record
                                        $sURL = addVariableToURL($sURL, ACTION_VARIABLE_SORTORDER, $sSortOrder);//Change Order 1 record                                    
                                        
                                        //not allowed if it's first item on first page
                                        if ($objPaginator->isFirstPage())
                                        {
                                            if ($objModel->isFirstRecord())
                                                $bOrderOneUpAllowed = false;
                                        }
                                        
                                        //not allowed if it's the last item on the last page
                                        if ($objPaginator->isLastPage())
                                        {
                                            if ($objModel->isLastRecord())
                                                $bOrderOneDownAllowed = false;                                        
                                        }                                    
                                        
                                        
                                        //up arrow
                                        if ($bOrderOneUpAllowed)
                                        {
                                            $sURL = addVariableToURL($sURL, ACTION_VARIABLE_ORDERONEUP, ACTION_VALUE_ORDERONEUP); //move one up
                                            $sColumnValue = '<a href="'.$sURL.'"><img alt="'.$sTranslatedMoveOneUp.'" src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-up-enabled32x32.png"></a>';                                        
                                        }
                                        else
                                        {
                                            $sColumnValue = '<img alt="'.$sTranslatedMoveOneUp.'" src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-up-disabled32x32.png">';
                                        }
                                        
                                        
                                        //down arrow
                                        if ($bOrderOneDownAllowed)
                                        {
                                            $sURL = addVariableToURL($sURL, ACTION_VARIABLE_ORDERONEUP, ACTION_VALUE_ORDERONEDOWN); //move one down
                                            $sColumnValue.= '<a href="'.$sURL.'"><img alt="'.$sTranslatedMoveOneDown.'" src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-down-enabled32x32.png"></a>';                                        
                                        }
                                        else
                                        {
                                            $sColumnValue.= '<img alt="'.$sTranslatedMoveOneDown.'" src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-down-disabled32x32.png">';
                                        }                                    
        
                                    }   
                                    
                                    ?>
                                        <td class="<?php if ($bCSSClassTDShowOnDesktop){ echo 'column-display-on-desktop';} ?>"><?php echo $sColumnValue ?></td>
                                    <?php
                                }
                            ?>
                            <td>
                                <?php 
                                
                                    
                                    //checkout
                                    if ($bRecordCheckedOut)
                                        echo '<img src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-checkout-locked32x32.png" alt="'.transcms('recordlist_record_checkedout', 'Record CHECKED OUT by [source], not available for editing', 'source', $objModel->getCheckoutSource()) .'">';

                                    
                                    //lock
                                    if ($bRecordLocked)
                                        echo '<img src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-lock-closed32x32.png" alt="'.transcms('recordlist_record_locked', 'Record LOCKED by [source], not available for editing','source', $objModel->getLockedSource()).'">';
                                
                                    
                                    //edit-icon
                                    if (isset($sURLDetailPage) && $bEditAllowedThisRecord)
                                    {   
                                        if (isset($sURLDetailPage))
                                        {
                                            if ($objModel->getTableUseRandomIDAsPrimaryKey())
                                                echo '<a href="'.$sURLDetailPage.'?'.ACTION_VARIABLE_UNIQUEID.'='.$objModel->getRandomID().'">'; //I choose not to use addvariableToID() because of speed
                                            
                                            if ($objModel->getTableUseIDField())
                                                echo '<a href="'.$sURLDetailPage.'?'.ACTION_VARIABLE_ID.'='.$objModel->getID().'">'; //I choose not to use addvariableToID() because of speed
                                        }
                                        echo '<img src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-edit32x32.png" alt="'.$sTranslatedEdit.'">';
                                        if (isset($sURLDetailPage))
                                            echo '</a>';                                       
                                    
                                    }
                                    
                                    //translate
                                    if ($bShowTranslateIcon)
                                    {
                                        if (isset($sURLTranslatePage))
                                            echo '<a href="'.$sURLTranslatePage.'?'.ACTION_VARIABLE_ID.'='.$objModel->getID().'">';
                                        echo '<img src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-translate32x32.png" alt="'.$sTranslatedTranslate.'">';
                                        if (isset($sURLTranslatePage))
                                            echo '</a>';          
                                    }

                                    
                                    unset($objDateCheckoutExpire);
                                ?>
                            </td>                                    
                        </tr>			
                        <?php
                    }
                ?>
            </tbody>
        </table>    

        <?php   
            //==== NO RECORDS? ====
            if ($objModel->count() == 0)
            {
                echo '<center>';
                echo '<img src="'.GLOBAL_PATH_WWW_CMS_IMAGES.'/icon-alert-grey128x128.png"><br>';
                echo transcms('message_noitemstodisplay','[ no items to display ]');
                echo '<br>';
                echo '</center>';
            }
        ?>          
    </div>
    
    
    <?php
    
    //=========== BULK ACTIONS ========
    if (isset($objSelectBulkActions)) //auth() is checked in crud controller
    {
        ?>
            <div class="overview_bulkactions">
                <?php echo $objSelectBulkActions->renderHTMLNode(); ?>
                <input type="button" value="<?php echo transcms('overview_bulkactions_button_execute', 'execute');?>" onclick="confirmBulkAction('<?php echo $objSelectBulkActions->getID();?>')" class="button_normal">
            </div>
        <?php
    }

    ?>    
</form>   



<div class="overview_paginator">
<?php
       $objUL = $objPaginator->generateHTMLList();
       $objUL->setClass('paginator');
       echo $objUL->renderHTMLNode();       
?>
    <div class="paginator_textshowingXfromY">
        <?php
            if ($objPaginator->getTotalItemsCount() > 0)
            {
                echo transcms('paginator_showingXtoYofZ', 'Showing [start] to [finish] of [total] entries', 
                'start', $objPaginator->getCurrentPageFirstItem(),
                'finish', $objPaginator->getCurrentPageLastItem(),
                'total', $objPaginator->getTotalItemsCount()
                );   
            }
            else
            {
                echo transcms('paginator_showingXtoYofZ_noentries', 'Showing 0 entries'); 
            }
        ?>
    </div>
</div>  
          