<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Sys_Settings\controllers;

use dr\classes\models\TModel;
use dr\classes\controllers\TCRUDDetailSaveController;

use dr\classes\dom\tag\form\Form;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\form\InputNumber;
use dr\classes\dom\tag\form\InputPassword;
use dr\classes\dom\tag\Li;
use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\form\Option;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Onlynumeric;
use dr\classes\dom\validator\Required;
use dr\classes\dom\validator\Emailaddress;

//don't forget ;)
use dr\classes\models\TSysSettings;
use dr\classes\patterns\TModuleAbstract;
use dr\classes\models\TSysCMSUsers;
use dr\classes\models\TSysCMSUsersSessions;
use dr\classes\models\TSysCMSUsersRoles;
use dr\modules\Mod_Sys_Settings\Mod_Sys_Settings;


include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


/**
 * Description of TCRUDDetailSaveSettingsSystem
 *
 * @author drenirie
 */
class settings_system extends TCRUDDetailSaveController
{
    private $objEdtEmailSysAdmin = null;//dr\classes\dom\tag\form\InputText
    //private $objChkAnyoneCanRegister = null;//dr\classes\dom\tag\form\InputCheckbox -->moved to config
    private $objOptNewUserRegisterGroupID = null;//dr\classes\dom\tag\form\InputSelect
    private $objEdtUserPasswordExpiresDays = null;//dr\classes\dom\tag\form\InputText
    private $objEdtPaginatorMaxResult = null;//dr\classes\dom\tag\form\Select
    private $objEdtMailbotFromEmailaddress = null;//dr\classes\dom\tag\form\InputText
    private $objEdtMailbotFromName = null;//dr\classes\dom\tag\form\InputText
        

    /**
     * OVERLOADED FROM PARENT!!!!
     * handle db loading or creating a new record
     */
    protected function handleNewEditRecord()
    {
        global $objLoginController;

        //obtain newest settings (someone might have changed them in the meantime)
        if (!$this->getModel()->loadFromDB())
        {
            error_log(__CLASS__.': settingsReload() ERROR');
            sendMessageError(transcms('message_loadsettings_failed', 'Failed to load system settings'));
        }

        if (auth($this->getModule(), $this->getAuthorisationCategory(), TModuleAbstract::PERM_OP_CHANGE)) //only if you have rights to save: check in. otherwise when saving you need to checkin again
        {        
            //WE DONT USE checkout/checkin otherwise you can't click from one tab to another.
            //first check if all the records are not checked out
            // while ($this->getModel()->next())
            // {            
            //     if ($this->getModel()->getCheckedOut())
            //     {
            //         showAccessDenied(transcms('error_settings_recordlocked', 'settings are locked for editing by another user'));
            //         die();
            //     }
            // }

            //checkout mechanics: the actual checkout
            // $this->getModel()->resetRecordPointer();
            // while ($this->getModel()->next())
            // {            
            //     $this->getModel()->checkoutNowDB($this->getModel()->getID(), $this->getModule().': '.$this->getAuthorisationCategory().': by user: '.$objLoginController->getUsers()->getUsername()); 
            // }
        }

    }

    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
        $sTransSectionSystem = transm($this->getModule(), 'sectiontitle_system', 'System');
        $sTransSectionCMS = transm($this->getModule(), 'sectiontitle_cms', 'CMS');
        $sTransSectionCMSMemberships = transm($this->getModule(), 'sectiontitle_cms_users', 'CMS: users');

            //email sys administrator
        $this->objEdtEmailSysAdmin = new InputText();
        $this->objEdtEmailSysAdmin->setNameAndID('edtEmailSysAdmin');
        $this->objEdtEmailSysAdmin->setClass('fullwidthtag');   
        $this->objEdtEmailSysAdmin->setRequired(true);   
        $this->objEdtEmailSysAdmin->setMaxLength(255);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '255'), 255);
        $this->objEdtEmailSysAdmin->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtEmailSysAdmin, $sTransSectionSystem, transm($this->getModule(), 'form_field_emailsysadmin', 'Email address system administrator (whole system, all sites, all login controllers etc'));

            //max results paginator
        $this->objEdtPaginatorMaxResult = new InputNumber();
        $this->objEdtPaginatorMaxResult->setNameAndID('edtMaxResultsPaginator');
        $this->objEdtPaginatorMaxResult->setClass('fullwidthtag');   
        $this->objEdtPaginatorMaxResult->setRequired(true);   
        $this->getFormGenerator()->add($this->objEdtPaginatorMaxResult, $sTransSectionCMS, transm($this->getModule(), 'form_field_paginatormaxresultsperpage', '# Records shown per page'));

            //emailbot from email address
        $this->objEdtMailbotFromEmailaddress = new InputText();
        $this->objEdtMailbotFromEmailaddress->setNameAndID('edtMailbotFromEmailaddress');
        $this->objEdtMailbotFromEmailaddress->setClass('fullwidthtag');   
        $this->objEdtMailbotFromEmailaddress->setRequired(true);   
        $this->objEdtMailbotFromEmailaddress->setMaxLength(255);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '255'), 255);
        $this->objEdtMailbotFromEmailaddress->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtMailbotFromEmailaddress, $sTransSectionCMS, transm($this->getModule(), 'form_field_mailbot_from_emailaddress', 'FROM Email address mailbot (i.e. used for password recovery)'));
    
            //emailbot from email name
        $this->objEdtMailbotFromName = new InputText();
        $this->objEdtMailbotFromName->setNameAndID('edtMailbotFromName');
        $this->objEdtMailbotFromName->setClass('fullwidthtag');   
        $this->objEdtMailbotFromName->setRequired(true);   
        $this->objEdtMailbotFromName->setMaxLength(255);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '255'), 255);
        $this->objEdtMailbotFromName->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtMailbotFromName, $sTransSectionCMS, transm($this->getModule(), 'form_field_mailbot_from_name', 'FROM name mailbot (i.e. used for password recovery)'));
    

            //anyone can make user account (option in config.php supersedes this one)
        // $this->objChkAnyoneCanRegister = new InputCheckbox();
        // $this->objChkAnyoneCanRegister->setNameAndID('chkAnyoneCanRegister');
        // $this->getFormGenerator()->add($this->objChkAnyoneCanRegister, $sTransSectionCMSMemberships, transm($this->getModule(), 'form_field_anyonecanregister', 'anyone can register'));         

            //user groupid
        $this->objOptNewUserRegisterGroupID = new Select();
        $this->objOptNewUserRegisterGroupID->setNameAndID('optDefaultUsergroupID');
        $this->getFormGenerator()->add($this->objOptNewUserRegisterGroupID, $sTransSectionCMSMemberships, transm($this->getModule(), 'form_field_usergroup', 'new user default role'));

            //auto expire password days
        $this->objEdtUserPasswordExpiresDays = new InputNumber();
        $this->objEdtUserPasswordExpiresDays->setNameAndID('edtUserPasswordExpiresDays');
        $this->objEdtUserPasswordExpiresDays->setClass('fullwidthtag');   
        $this->objEdtUserPasswordExpiresDays->setRequired(true);   
        $this->getFormGenerator()->add($this->objEdtUserPasswordExpiresDays, $sTransSectionCMSMemberships, transm($this->getModule(), 'form_field_userpasswordexpiresdays', 'Users need to change password after X days (0 = never, 1 = 1 day, 2 = 2 days etc.)'));

    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_Settings::PERM_CAT_SYSTEMSETTINGS;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {    
        //we have multiple records to deal with
        $this->getModel()->resetRecordPointer();
        while($this->getModel()->next())
        {
            //pick out the record we want to set

            //email sys admin
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_SYSTEM, SETTINGS_SYSTEM_EMAILSYSADMIN))
            {
                $this->getModel()->set(TSysSettings::FIELD_VALUE, $this->objEdtEmailSysAdmin->getContentsSubmitted()->getValueAsString());
            }

            //anyone can make user account (option in config.php supersedes this one)
            // if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_ANYONECANREGISTER))
            // {
            //     $this->getModel()->set(TSysSettings::FIELD_VALUE, boolToStr($this->objChkAnyoneCanRegister->getContentsSubmitted()->getValueAsBool()));
            // }


            //default group id
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_NEWUSER_ROLEID))
            {
                $this->getModel()->set(TSysSettings::FIELD_VALUE, intToStr($this->objOptNewUserRegisterGroupID->getContentsSubmitted()->getValueAsInt()));
            }

            //password expires days
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_USERPASSWORDEXPIRES_DAYS))
            {
                $this->getModel()->set(TSysSettings::FIELD_VALUE, intToStr($this->objEdtUserPasswordExpiresDays->getContentsSubmitted()->getValueAsInt()));
            }            

            //max records per page
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_PAGINATOR_MAXRESULTSPERPAGE))
            {
                $this->getModel()->set(TSysSettings::FIELD_VALUE, intToStr($this->objEdtPaginatorMaxResult->getContentsSubmitted()->getValue()));
            }

            //mailbot email address
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_SYSTEMMAILBOT_FROM_EMAILADDRESS))
            {
                $this->getModel()->set(TSysSettings::FIELD_VALUE, $this->objEdtMailbotFromEmailaddress->getContentsSubmitted()->getValue());
            }            

            //mailbot name
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_SYSTEMMAILBOT_FROM_NAME))
            {
                $this->getModel()->set(TSysSettings::FIELD_VALUE, $this->objEdtMailbotFromName->getContentsSubmitted()->getValue());
            }                   

            
        }

        //$this->getModel()->saveToDBAll(); --> dit werkt maar dat hoort bij handleSubmitted() afgehandeld te worden
        

    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {  

        //we have multiple records to deal with
        $this->getModel()->resetRecordPointer();
        while($this->getModel()->next())
        {
            //pick out the record we want to set

            //email sys admin
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_SYSTEM, SETTINGS_SYSTEM_EMAILSYSADMIN))
            {
                $this->objEdtEmailSysAdmin->setValue($this->getModel()->get(TSysSettings::FIELD_VALUE));
            }

            //anyone can make user account (option in config.php supersedes this one)
            // if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_ANYONECANREGISTER))
            // {
            //     $this->objChkAnyoneCanRegister->setChecked(strToBool($this->getModel()->get(TSysSettings::FIELD_VALUE)));
            // }

            //default usergroup id
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_NEWUSER_ROLEID))
            {    

                $objGroups = new TSysCMSUsersRoles();
                $objGroups->sort(TSysCMSUsersRoles::FIELD_ROLENAME);
                $objGroups->loadFromDB();
                $objGroups->generateHTMLSelect($this->getModel()->get(TSysSettings::FIELD_VALUE), $this->objOptNewUserRegisterGroupID);
            }            

            //password expires days
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_USERPASSWORDEXPIRES_DAYS))
            {
                $this->objEdtUserPasswordExpiresDays->setValue($this->getModel()->get(TSysSettings::FIELD_VALUE));
            }

            //records per page
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_PAGINATOR_MAXRESULTSPERPAGE))
            {
                $this->objEdtPaginatorMaxResult->setValue($this->getModel()->get(TSysSettings::FIELD_VALUE));
            }

            //mailbot email address
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_SYSTEMMAILBOT_FROM_EMAILADDRESS))
            {
                $this->objEdtMailbotFromEmailaddress->setValue($this->getModel()->get(TSysSettings::FIELD_VALUE));
            }            

            //mailbot name
            if ($this->getModel()->getResource() == getSettingsResourceString(SETTINGS_MODULE_CMS, SETTINGS_CMS_SYSTEMMAILBOT_FROM_NAME))
            {
                $this->objEdtMailbotFromName->setValue($this->getModel()->get(TSysSettings::FIELD_VALUE));
            }               

        }
    }
    
   /**
     * is called when a record is loaded
     */
    public function onLoad()
    {}
    
    /**
     * is called when a record is saved
     * this method has to send the proper error messages to the user!!
     * 
     * @return boolean it will NOT SAVE
     */
    public function onSavePre()
    {
        return true;
    }
    
    /**
     * is called AFTER a record is saved
     * 
     * @param boolean $bWasSaveSuccesful did saveToDB() return false or true?
     * @return boolean returns true on success otherwise false
     */
    public function onSavePost($bWasSaveSuccesful)
    {
        //refresh the settings in the session array
        settingsReload();

        return true;
    }



    /**
     * is called when this controller is created,
     * so you can instantiate classes or initiate values for example 
     */
    public function onCreate() {}  


    /**
     * sometimes you don;t want to user the checkin checkout system, even though the model supports it
     * for example: the settings.
     * The user needs to be able to navigate through the tabsheets, without locking records
     * 
     * ATTENTION: if this method returns true and the model doesn't support it: the checkinout will NOT happen!
     * 
     * @return bool return true if you want to use the check-in/checkout-system
     */
    public function getUseCheckinout()
    {
        return false;
    }    


   /**
     * returns a new model object
     *
     * @return TModel
     */
    public function getNewModel()
    {
        return new TSysSettings(); 
    }

    /**
     * return path of the page template
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_modeldetailsave.php';
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
     * returns the url to which the browser returns after closing the detailsave screen
     *
     * @return string
     */
    public function getReturnURL()
    {
        return getURLCMSDashboard();
    }

    /**
     * return page title
     * This title is different for creating a new record and editing one.
     * It returns in the translated text in the current language of the user (it is not translated in the controller)
     * 
     * for example: "create a new user" or "edit user John" (based on if $objModel->getNew())
     *
     * @return string
     */
    public function getTitle()
    {
        global $sCurrentModule;

        return transm($sCurrentModule, 'pagetitle_settingssystem_edit', 'Settings system');           
    }

    /**
     * show tabsheets on top of the page?
     *
     * @return bool
     */
    public function showTabs()
    {
        return true;
    }    

}
