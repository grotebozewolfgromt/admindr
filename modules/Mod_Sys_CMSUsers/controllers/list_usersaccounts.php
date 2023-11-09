<?php
namespace dr\modules\Mod_Sys_CMSUsers\controllers;

use dr\classes\models\TSysCMSUserAccounts;
use dr\classes\models\TSysLanguages;
use dr\classes\models\TSysCMSUsersRoles;
use dr\classes\controllers\TCRUDListController;
use dr\classes\models\TSysCMSUsers;
use dr\classes\models\TSysContacts;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;



include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class list_usersaccounts extends TCRUDListController
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

        $objModel = $this->objModel;
        $objTempContacts = new TSysContacts();  
        $objModel->select(array(
            TSysCMSUserAccounts::FIELD_ID, 
            TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER,
            TSysCMSUserAccounts::FIELD_LOGINENABLED
                               ));
        $objModel->select(array(TSysContacts::FIELD_COMPANYNAME), $objTempContacts);        
    //    $objModel->selectAlias(TSysLanguages::FIELD_ID, 'iLangIDAlias', $objTempLang);     
               
       
        $this->executeDB(1);
        
        
        //===show what?
        $arrTableColumnsShow = array(
            array('', TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, transm($sCurrentModule, 'overview_column_'.TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, 'label id')),
            array('', TSysCMSUserAccounts::FIELD_LOGINENABLED, transm($sCurrentModule, 'overview_column_'.TSysCMSUserAccounts::FIELD_LOGINENABLED, 'enabled')),
            array($objTempContacts::getTable(), TSysContacts::FIELD_COMPANYNAME, transm($sCurrentModule, 'overview_column_'.TSysContacts::FIELD_COMPANYNAME, 'company')),
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
        return new TSysCMSUserAccounts();
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
        return Mod_Sys_CMSUsers::PERM_CAT_USERACCOUNTS;
    }
    

     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    public function getDetailPageURL()
    {
        return 'detailsave_usersaccounts';
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
        return transm($sCurrentModule, TRANS_MODULENAME_TITLE.'_cmsuseraccounts', 'User accounts');
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