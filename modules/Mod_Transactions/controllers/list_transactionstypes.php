<?php

namespace dr\modules\Mod_Transactions\controllers;

use dr\classes\models\TSysContacts;
use dr\classes\controllers\TCRUDListController;
use dr\modules\Mod_Transactions\Mod_Transactions;
use dr\modules\Mod_Transactions\models\TTransactions;
use dr\modules\Mod_Transactions\models\TTransactionsTypes;

// use dr\modules\Mod_Sys_Contacts\Mod_Sys_Contacts;

include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class list_transactionstypes extends TCRUDListController
{
    
    /**
     * executes the controller
     * this function is ONLY called on a cache miss
     * to generate new content for the cache and to 
     * display to the screen
     *
     * 
     * @return array with variables, use: "return get_defined_vars();" to use all variables declared in the execute() function
     */
    public function execute()
    {
 
        // global $objCurrentModule;
        global $sCurrentModule;        
        // global $arrTabsheets;        


        $objModel = $this->objModel;
        $objModel->select(array(
            TTransactionsTypes::FIELD_NAME, 
            TTransactionsTypes::FIELD_ISSTOCK,
            TTransactionsTypes::FIELD_ISDEFAULTSELECTED,
            TTransactionsTypes::FIELD_ISDEFAULTINVOICE,
            TTransactionsTypes::FIELD_ISDEFAULTORDER,
            TTransactionsTypes::FIELD_COLORBACKGROUND,
            TTransactionsTypes::FIELD_NEWNUMBERINCREMENT,
            TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS
                                    ));
        $this->executeDB();
      
        //===show what?
        $arrTableColumnsShow = array(
            array('', TTransactionsTypes::FIELD_NAME, transm($sCurrentModule, 'list_column_'.TTransactionsTypes::FIELD_NAME, 'Type name')),
            array('', TTransactionsTypes::FIELD_ISSTOCK, transm($sCurrentModule, 'list_column_'.TTransactionsTypes::FIELD_ISSTOCK, 'Stock')),
            array('', TTransactionsTypes::FIELD_ISDEFAULTSELECTED, transm($sCurrentModule, 'list_column_'.TTransactionsTypes::FIELD_ISFINANCIAL, 'Financial')),
            array('', TTransactionsTypes::FIELD_ISDEFAULTINVOICE, transm($sCurrentModule, 'list_column_'.TTransactionsTypes::FIELD_ISDEFAULTINVOICE, 'Invoice')),
            array('', TTransactionsTypes::FIELD_ISDEFAULTORDER, transm($sCurrentModule, 'list_column_'.TTransactionsTypes::FIELD_ISDEFAULTORDER, 'Order')),
            array('', TTransactionsTypes::FIELD_COLORBACKGROUND, transm($sCurrentModule, 'list_column_'.TTransactionsTypes::FIELD_COLORBACKGROUND, 'Color')),
            array('', TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, transm($sCurrentModule, 'list_column_'.TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, 'Increment')),
            array('', TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, transm($sCurrentModule, 'list_column_'.TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, 'Pay days')),
                );
        

             
        return get_defined_vars();    
    }


    /**
     * return path of the page template
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_modellist.php';
    }

    /**
     * return path of the skin template
     * 
     * return '' if no skin
     *
     * @return string
     */
    public function getSkinPath()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withmenu.php';
    }

    /**
     * return new TModel object
     * 
     * @return TModel;
     */
    public function getNewModel()
    {
        return new TTransactionsTypes();
    }

    /**
     * return permission category 
     * =class constant of module class
     * 
     * for example: Mod_Sys_CMSUsers::PERM_CAT_USERS
     *
     * @return string
     */
    public function getAuthorisationCategory()
    {
        return Mod_Transactions::PERM_CAT_TRANSACTIONS;
    }

     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    public function getDetailPageURL()
    {
        return 'detailsave_transactionstypes';
    }

    /**
     * return page title
     * It returns in the translated text in the current language of the user (it is not translated in the controller)
     * 
     * for example: "create a new user" or "edit user John" (based on if $objModel->getNew())
     *
     * @return string
     */
    function getTitle()
    {
        global $sCurrentModule;
        return transm($sCurrentModule, 'tab_title_transactiontypes', 'Transaction types');
    }

    /**
     * show tabsheets on top?
     *
     * @return bool
     */
    public function showTabs()
    {
        return true;
    }      
  
}