<?php
namespace dr\modules\Mod_Sys_CMSUsers\controllers;

use dr\classes\models\TSysCMSUsers;
use dr\classes\models\TSysLanguages;
use dr\classes\models\TSysCMSUsersRoles;
use dr\classes\controllers\TCRUDListController;
use dr\classes\models\TSysCMSUserAccounts;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;



include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class list_users extends TCRUDListController
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
        $objTempLang = new TSysLanguages();  
        $objTempGroups = new TSysCMSUsersRoles();
        $objTempAccounts = new TSysCMSUserAccounts();
        $objModel->select(array(
            TSysCMSUsers::FIELD_ID, 
            TSysCMSUsers::FIELD_USERNAME, 
            TSysCMSUsers::FIELD_USERNAMEPUBLIC, 
            TSysCMSUsers::FIELD_EMAILADDRESSENCRYPTED, 
            TSysCMSUsers::FIELD_LOGINENABLED, 
            TSysCMSUsers::FIELD_LOGINEXPIRES, 
            TSysCMSUsers::FIELD_LASTLOGIN,
            TSysCMSUsers::FIELD_CHECKOUTEXPIRES,
            TSysCMSUsers::FIELD_CHECKOUTSOURCE,
            TSysCMSUsers::FIELD_LOCKED,
            TSysCMSUsers::FIELD_LOCKEDSOURCE
                                    ));
        $objModel->select(array(TSysLanguages::FIELD_LANGUAGE), $objTempLang);
        $objModel->select(array(TSysCMSUsersRoles::FIELD_ROLENAME), $objTempGroups);
        $objModel->select(array(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER), $objTempAccounts);
    //    $objModel->selectAlias(TSysLanguages::FIELD_ID, 'iLangIDAlias', $objTempLang);     
               
       
        $this->executeDB(true);
        
        
        //===show what?
        $arrTableColumnsShow = array(
            array('', TSysCMSUsers::FIELD_USERNAME, transm($sCurrentModule, 'users_overview_column_'.TSysCMSUsers::FIELD_USERNAME, 'username')),
            array('', TSysCMSUsers::FIELD_USERNAMEPUBLIC, transm($sCurrentModule, 'users_overview_column_'.TSysCMSUsers::FIELD_USERNAMEPUBLIC, 'public')),
            array('', TSysCMSUsers::FIELD_EMAILADDRESSENCRYPTED, transm($sCurrentModule, 'users_overview_column_'.TSysCMSUsers::FIELD_EMAILADDRESSENCRYPTED, 'email')),
            array('', TSysCMSUsers::FIELD_LOGINENABLED, transm($sCurrentModule, 'users_overview_column_'.TSysCMSUsers::FIELD_LOGINENABLED, 'enabled')),
            array('', TSysCMSUsers::FIELD_LOGINEXPIRES, transm($sCurrentModule, 'users_overview_column_'.TSysCMSUsers::FIELD_LOGINEXPIRES, 'expires')),
            array('', TSysCMSUsers::FIELD_LASTLOGIN, transm($sCurrentModule, 'users_overview_column_'.TSysCMSUsers::FIELD_LASTLOGIN, 'last login')),
            array(TSysLanguages::getTable(), TSysLanguages::FIELD_LANGUAGE, transm($sCurrentModule, 'users_overview_column_'.TSysLanguages::FIELD_LANGUAGE, 'language')),
            array(TSysCMSUsersRoles::getTable(), TSysCMSUsersRoles::FIELD_ROLENAME, transm($sCurrentModule, 'users_overview_column_'.TSysCMSUsersRoles::FIELD_ROLENAME, 'role')),
            array(TSysCMSUserAccounts::getTable(), TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, transm($sCurrentModule, 'users_overview_column_'.TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, 'account'))
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
        return new TSysCMSUsers();
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
        return Mod_Sys_CMSUsers::PERM_CAT_USERS;
    }
    

     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    public function getDetailPageURL()
    {
        return 'detailsave_users';
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
        return transm($sCurrentModule, TRANS_MODULENAME_TITLE.'_usercms', 'Users');
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