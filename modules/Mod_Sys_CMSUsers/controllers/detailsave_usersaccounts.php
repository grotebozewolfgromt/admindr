<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Sys_CMSUsers\controllers;

use dr\classes\models\TModel;
use dr\classes\controllers\TCRUDDetailSaveController;
use dr\classes\controllers\TCRUDDetailSaveController_org;
use dr\classes\locale\TCountrySettings;

use dr\classes\dom\tag\form\Form;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\InputPassword;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\form\InputDate;
use dr\classes\dom\tag\form\InputTime;
use dr\classes\dom\tag\form\Label;
use dr\classes\dom\tag\form\InputDatetime;
use dr\classes\dom\tag\Li;
use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\Script;
use dr\classes\dom\tag\form\Option;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Required;
use dr\classes\dom\validator\Emailaddress;
use dr\classes\dom\validator\Date;
use dr\classes\dom\validator\DateMin;
use dr\classes\dom\validator\DateMax;
use dr\classes\dom\validator\DateTime;
use dr\classes\dom\validator\Time;
use dr\classes\models\TSysCMSUserAccounts;
use dr\classes\types\TDateTime;


//don't forget ;)
use dr\classes\models\TSysCMSUsers;
use  dr\classes\models\TSysCMSUsersAccounts;
use dr\classes\models\TSysContacts;
use dr\classes\models\TUsersAbstract;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;

include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_jquery.php');
include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');

/**
 * Description of TCRUDDetailSaveCMSUsers
 *
 * @author drenirie
 */
class detailsave_usersaccounts extends TCRUDDetailSaveController
{
    private $objEdtCustomIdentifier = null;//dr\classes\dom\tag\form\InputText
    private $objSelContactID = null;//dr\classes\dom\tag\form\Select     
    private $objChkLoginEnabled = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objEdtLoginExpiresDate = null;//dr\classes\dom\tag\form\InputText
    private $objEdtLoginExpiresTime = null;//dr\classes\dom\tag\form\InputTime
    private $objEdtDeleteAfterDate = null;//dr\classes\dom\tag\form\InputText
    private $objEdtDeleteAfterTime = null;//dr\classes\dom\tag\form\InputTime

  
    // private $objLblHintSessions1 = null;//Label
    // private $objLblHintSessions2 = null;//Label
    // private $objUserSessions = null;//TSysCMSUserSessions
        
    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
      
                
            //custom identifier
        $this->objEdtCustomIdentifier = new InputText();
        $this->objEdtCustomIdentifier->setNameAndID('edtCustomIdentifier');
        $this->objEdtCustomIdentifier->setClass('fullwidthtag');   
        $this->objEdtCustomIdentifier->setRequired(true);   
        $this->objEdtCustomIdentifier->setMaxLength(50);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtCustomIdentifier->addValidator($objValidator);    
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtCustomIdentifier->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtCustomIdentifier, '', transm($this->getModule(), 'form_field_customidentifier', 'Label (used to identify this account just to you)'));

        //contact id
        $this->objSelContactID = new Select();
        $this->objSelContactID->setNameAndID('optContactID');
        $this->getFormGenerator()->add($this->objSelContactID, '', transm($this->getModule(), 'form_field_contactid', 'Contact'));
        
        //login enabled
        $this->objChkLoginEnabled = new InputCheckbox();
        $this->objChkLoginEnabled->setNameAndID('edtLoginEnabled');
        $this->getFormGenerator()->add($this->objChkLoginEnabled, '', transm($this->getModule(), 'form_field_enabled', 'able to log in (users in this account)'));         


        //login expires 
            //date
            $this->objEdtLoginExpiresDate = new InputDate($this->getDateFormatDefault());
            $this->objEdtLoginExpiresDate->setNameAndID('edtLoginExpiresDate');   
            $objValidator = new Date(transcms('form_error_notavaliddate', 'This not a valid date'), $this->getDateFormatDefault(),  true, $this->getDateFormatDefault());            
            $this->objEdtLoginExpiresDate->addValidator($objValidator);            
            
            //time
            $this->objEdtLoginExpiresTime = new InputTime($this->getTimeFormatDefault());
            $this->objEdtLoginExpiresTime->setNameAndID('edtLoginExpiresTime');
            $objValidator = new Time(transcms('form_error_notavalidtime', '[time] is not a valid time', 'time', $this->objEdtLoginExpiresTime->getContentsSubmitted()->getValueAsString()), $this->getTimeFormatDefault(),  true, $this->getTimeFormatDefault());
            $this->objEdtLoginExpiresTime->addValidator($objValidator);                        
            
            $this->getFormGenerator()->addArray(array($this->objEdtLoginExpiresDate, $this->objEdtLoginExpiresTime), '', transm($this->getModule(), 'form_field_loginexpires', 'Login expires after (users in account can\'t log in after this date, empty = no expiration)'));        
        
        //scheduled for deletion after
            //date
            $this->objEdtDeleteAfterDate = new InputDate($this->getDateFormatDefault());
            $this->objEdtDeleteAfterDate->setNameAndID('edtDeleteAfter');   
            $objValidator = new Date(transcms('form_error_notavaliddate', 'This not a valid date'), $this->getDateFormatDefault(),  true, $this->getDateFormatDefault());
            $this->objEdtDeleteAfterDate->addValidator($objValidator);            
            
            //time
            $this->objEdtDeleteAfterTime = new InputTime($this->getTimeFormatDefault());
            $this->objEdtDeleteAfterTime->setNameAndID('edtPasswordExpiresTime');
            $objValidator = new Time(transcms('form_error_notavalidtime', '[time] is not a valid time', 'time', $this->objEdtDeleteAfterTime->getContentsSubmitted()->getValueAsString()), $this->getTimeFormatDefault(),  true, $this->getTimeFormatDefault());
            $this->objEdtDeleteAfterTime->addValidator($objValidator);                        
            
            $this->getFormGenerator()->addArray(array($this->objEdtDeleteAfterDate, $this->objEdtDeleteAfterTime), '', transm($this->getModule(), 'form_field_deleteafter', 'Auto delete account after (also deleting ALL users in account, empty = no deletion)'));                                             


        //users in account
        //done in modelToForm()
        
    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_CMSUsers::PERM_CAT_USERACCOUNTS;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
        //custom identifier
        $this->getModel()->set(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, $this->objEdtCustomIdentifier->getContentsSubmitted()->getValueAsString());

        //contactid
        $this->getModel()->set(TSysCMSUserAccounts::FIELD_CONTACTID, $this->objSelContactID->getContentsSubmitted()->getValueAsInt());

        //login enabled
        $this->getModel()->set(TSysCMSUserAccounts::FIELD_LOGINENABLED, $this->objChkLoginEnabled->getContentsSubmitted()->getValueAsBool());        

        //login expires
            //we set the time first so it defaults to 0:00 if the field is empty, but the date is not. the date & time are also empty when you leave the date field empty
            $this->getModel()->setTimeAsString(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, $this->objEdtLoginExpiresTime->getContentsSubmitted()->getValueAsString(), $this->getTimeFormatDefault());        
            $this->getModel()->setDateAsString(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, $this->objEdtLoginExpiresDate->getContentsSubmitted()->getValueAsString(), $this->getDateFormatDefault());        

        //scheduled delete after
            //we set the time first so it defaults to 0:00 if the field is empty, but the date is not. the date & time are also empty when you leave the date field empty
            $this->getModel()->setTimeAsString(TSysCMSUserAccounts::FIELD_DELETEAFTER, $this->objEdtDeleteAfterTime->getContentsSubmitted()->getValueAsString(), $this->getTimeFormatDefault());        
            $this->getModel()->setDateAsString(TSysCMSUserAccounts::FIELD_DELETEAFTER, $this->objEdtDeleteAfterDate->getContentsSubmitted()->getValueAsString(), $this->getDateFormatDefault());        

        //users in account
        //@todo able add users from this screen in the future
    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {  
        //custom identifier
        $this->objEdtCustomIdentifier->setValue($this->getModel()->get(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER));

        //contact id
        $objContacts = new TSysContacts();
        $objContacts->sort(TSysContacts::FIELD_CUSTOMIDENTIFIER);
        $objContacts->limitNone(); //not very fun, but hope to have a better solution in the future
        $objContacts->loadFromDB();
        $objContacts->generateHTMLSelect($this->getModel()->get(TSysCMSUserAccounts::FIELD_CONTACTID), $this->objSelContactID);

        //login enabled
        $this->objChkLoginEnabled->setChecked($this->getModel()->get(TSysCMSUserAccounts::FIELD_LOGINENABLED));   

        //login expires
            $this->objEdtLoginExpiresDate->setValue($this->getModel()->getDateAsString(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, $this->getDateFormatDefault())); 
            $this->objEdtLoginExpiresTime->setValue($this->getModel()->getDateAsString(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, $this->getTimeFormatDefault())); 

        //scheduled delete after
            $this->objEdtDeleteAfterDate->setValue($this->getModel()->getDateAsString(TSysCMSUserAccounts::FIELD_DELETEAFTER, $this->getDateFormatDefault())); 
            $this->objEdtDeleteAfterTime->setValue($this->getModel()->getDateAsString(TSysCMSUserAccounts::FIELD_DELETEAFTER, $this->getTimeFormatDefault())); 


        //sessions
        $this->populateUsers();

    }
    
   /**
     * is called when a record is loaded
     */
    public function onLoad()
    {
               
    }
    
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
    public function onSavePost($bWasSaveSuccesful){ return true; }


    /**
     * is called when this controller is created,
     * so you can instantiate classes or initiate values for example 
     */
    public function onCreate() 
    {
        $this->objUserSessions = new TSysCMSUserAccounts();
    }  

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
        return true;
    }    


    /**
     * populate sessions
     * we need to call them twice: once on form load and once when clicked save and sessions are deleted
     */
    private function populateUsers()
    {
        $this->objLblHintUsersInAccount = new Label();  
        $sTransSectionUsersInAccount = '';

        $sTransSectionUsersInAccount = transm($this->getModule(), 'section_useraccounts_detail_usersinaccount_title', 'Users in this account');

        

        if ($this->getModel()->getNew()) //existing record
        {
            $this->objLblHintUsersInAccount->setText(transm($this->getModule(), 'section_useraccounts_detail_usersinaccount_newrecordnousers', 'Create account first before you can assign users'));        
            $this->getFormGenerator()->add($this->objLblHintUsersInAccount, $sTransSectionUsersInAccount);        
        }
        else
        {
            //load from db
            $objUsers = new TSysCMSUsers();
            $objUsers->limitNone();
            $objUsers->find(TSysCMSUsers::FIELD_CMSUSERACCOUNTID, $this->getModel()->getID());
            $objUsers->loadFromDB();
    

            $this->objLblHintUsersInAccount->setText(transm($this->getModule(), 'section_useraccounts_detail_usersinaccount_explanation', '[amount] user(s) are part of this account:', 'amount', $objUsers->count()));        
            $this->getFormGenerator()->add($this->objLblHintUsersInAccount, $sTransSectionUsersInAccount);        



            //display users
            while($objUsers->next())
            {             
                $objLblUserLine = new Label();
                $objLblUserLine->setText('-'.$objUsers->getUsername());        
                $this->getFormGenerator()->add($objLblUserLine, $sTransSectionUsersInAccount);        
    

        //         $objCheckbox->setNameAndID('chkSession'.$objSessions->getRandomID());
                
        //         $sTempTextCheckbox = '';
        //         if ($objSessions->getRandomID() == $objLoginController->getUserSessions()->getRandomID())
        //         {
        //             $sTempTextCheckbox.= transm($this->getModule(), 'message_sessions_iscurrentsession', 'CURRENT SESSION: ');
        //             $objCheckbox->setDisabled(true);
        //         }

        //         $sTempTextCheckbox.= $objSessions->getBrowser().', '.$objSessions->getOperatingSystem().' ('.$objSessions->getSessionStarted()->getDateTimeAsString($this->getDateTimeFormatDefault()).')';


        //         $this->getFormGenerator()->add($objCheckbox, $sTransSectionSessions, $sTempTextCheckbox);
        //         unset($objCheckbox);//I don't save the checkboxes in an array or something, when we need them, we create new ones
            }   
            
        }
    }



   /**
     * returns a new model object
     *
     * @return TModel
     */
    public function getNewModel()
    {
        return new TSysCMSUserAccounts(); 
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
        return 'list_usersaccounts';
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

        if ($this->getModel()->getNew())   
            return transm($sCurrentModule, 'pagetitle_detailsave_useraccount_new', 'Create new user account');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_useraccount_edit', 'Edit user account: [identifier]', 'identifier', $this->getModel()->getCustomIdentifier());   
    }

    /**
     * show tabsheets on top of the page?
     *
     * @return bool
     */
    public function showTabs()
    {
        return false;
    }    

}
