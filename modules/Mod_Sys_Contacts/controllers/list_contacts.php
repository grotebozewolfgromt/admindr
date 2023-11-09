<?php
namespace dr\modules\Mod_Sys_Contacts\controllers;

use dr\classes\models\TSysContacts;
use dr\classes\controllers\TCRUDListController;
use dr\modules\Mod_Sys_Contacts\Mod_Sys_Contacts;

include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class list_contacts extends TCRUDListController
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
            TSysContacts::FIELD_ID, 
            // TSysContacts::FIELD_CHECKOUTEXPIRES,
            // TSysContacts::FIELD_CHECKOUTSOURCE,
            // TSysContacts::FIELD_LOCKED,
            // TSysContacts::FIELD_LOCKEDSOURCE,
            TSysContacts::FIELD_CUSTOMIDENTIFIER,
            TSysContacts::FIELD_COMPANYNAME,
            TSysContacts::FIELD_FIRSTNAMEINITALS,
            TSysContacts::FIELD_LASTNAME,
            TSysContacts::FIELD_BILLINGADDRESSMISC,
            TSysContacts::FIELD_BILLINGADDRESSSTREET,
            TSysContacts::FIELD_BILLINGCITY
                                    ));
      
        $this->executeDB();
      
        //===show what?
        $arrTableColumnsShow = array(
            array('', TSysContacts::FIELD_CUSTOMIDENTIFIER, transm($sCurrentModule, 'overview_column_'.TSysContacts::FIELD_CUSTOMIDENTIFIER, 'Identifier label')),
            array('', TSysContacts::FIELD_COMPANYNAME, transm($sCurrentModule, 'overview_column_'.TSysContacts::FIELD_COMPANYNAME, 'Company')),
            array('', TSysContacts::FIELD_FIRSTNAMEINITALS, transm($sCurrentModule, 'overview_column_'.TSysContacts::FIELD_FIRSTNAMEINITALS, 'Initials')),
            array('', TSysContacts::FIELD_LASTNAME, transm($sCurrentModule, 'overview_column_'.TSysContacts::FIELD_LASTNAME, 'Last name')),
            array('', TSysContacts::FIELD_BILLINGADDRESSMISC, transm($sCurrentModule, 'overview_column_'.TSysContacts::FIELD_BILLINGADDRESSMISC, 'Address 1')),
            array('', TSysContacts::FIELD_BILLINGADDRESSSTREET, transm($sCurrentModule, 'overview_column_'.TSysContacts::FIELD_BILLINGADDRESSSTREET, 'Address 2')),
            array('', TSysContacts::FIELD_BILLINGCITY, transm($sCurrentModule, 'overview_column_'.TSysContacts::FIELD_BILLINGCITY, 'City'))        
                );
        
    
        // $bNoRecordsToDisplay = false;
        // if ($objModel != null)
        // {
        //     if ($objModel->count() == 0)
        //             $bNoRecordsToDisplay = true;
        // }
             
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
        return new TSysContacts();
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
        return Mod_Sys_Contacts::PERM_CAT_CONTACTS;
    }

     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    public function getDetailPageURL()
    {
        return 'detailsave_contacts';
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
        return transm($sCurrentModule, TRANS_MODULENAME_TITLE, 'Contacts');
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