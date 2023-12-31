<?php
namespace dr\modules\Mod_Sys_CMSUsers\controllers;

use dr\classes\controllers\TCRUDListController;
use dr\classes\models\TSysCMSInvitationCodes;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;




include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class list_invitationcodes extends TCRUDListController
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
            TSysCMSInvitationCodes::FIELD_ID, 
            TSysCMSInvitationCodes::FIELD_CODENAME,
            TSysCMSInvitationCodes::FIELD_REDEMPTIONCODE,
            TSysCMSInvitationCodes::FIELD_CURRENTREDEEMS,
            TSysCMSInvitationCodes::FIELD_DATESTART,
            TSysCMSInvitationCodes::FIELD_DATEEND,
            TSysCMSInvitationCodes::FIELD_ISENABLED
                                    ));
      
        $this->executeDB();
      
        //===show what?
        $arrTableColumnsShow = array(
            array('', TSysCMSInvitationCodes::FIELD_CODENAME, transm($sCurrentModule, 'overview_column_'.TSysCMSInvitationCodes::FIELD_CODENAME, 'Name')),
            array('', TSysCMSInvitationCodes::FIELD_REDEMPTIONCODE, transm($sCurrentModule, 'overview_column_'.TSysCMSInvitationCodes::FIELD_REDEMPTIONCODE, 'Code')),
            array('', TSysCMSInvitationCodes::FIELD_CURRENTREDEEMS, transm($sCurrentModule, 'overview_column_'.TSysCMSInvitationCodes::FIELD_CURRENTREDEEMS, 'Redeemed')),
            // array('', TSysCMSInvitationCodes::FIELD_DATESTART, transm($sCurrentModule, 'overview_column_'.TSysCMSInvitationCodes::FIELD_DATESTART, 'Starts on')),
            array('', TSysCMSInvitationCodes::FIELD_DATEEND, transm($sCurrentModule, 'overview_column_'.TSysCMSInvitationCodes::FIELD_DATEEND, 'Expires on')),
            array('', TSysCMSInvitationCodes::FIELD_ISENABLED, transm($sCurrentModule, 'overview_column_'.TSysCMSInvitationCodes::FIELD_ISENABLED, 'Enabled'))
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
        return new TSysCMSInvitationCodes();
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
        return Mod_Sys_CMSUsers::PERM_CAT_INVITATIONCODES;
    }

     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    public function getDetailPageURL()
    {
        return 'detailsave_invitationcodes';
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
        return transm($sCurrentModule, TRANS_MODULENAME_TITLE, 'Invitation codes');
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