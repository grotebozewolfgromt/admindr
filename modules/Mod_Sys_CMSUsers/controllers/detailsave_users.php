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
use dr\classes\models\TSysCMSUsersRoles;
use  dr\classes\models\TSysCMSUsersSessions;
use dr\classes\models\TSysLanguages;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;

include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_jquery.php');
include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');

/**
 * Description of TCRUDDetailSaveCMSUsers
 *
 * @author drenirie
 */
class detailsave_users extends TCRUDDetailSaveController
{
    private $objEdtUsername = null;//dr\classes\dom\tag\form\InputText
    private $objEdtUsernamePublic = null;//dr\classes\dom\tag\form\InputText
    private $objEdtPasswordOld = null;//dr\classes\dom\tag\form\InputPassword
    private $objEdtPasswordNew = null;//dr\classes\dom\tag\form\InputPassword
    private $objEdtPasswordRepeat = null;//dr\classes\dom\tag\form\InputPassword
    private $objEdtEmail = null;//dr\classes\dom\tag\form\InputText
    private $objEdtLoginExpiresDate = null;//dr\classes\dom\tag\form\InputDate
    private $objEdtLoginExpiresTime = null;//dr\classes\dom\tag\form\InputTime
    private $objEdtPasswordExpiresDate = null;//dr\classes\dom\tag\form\InputText
    private $objEdtPasswordExpiresTime = null;//dr\classes\dom\tag\form\InputTime
    private $objEdtAccountExpiresDate = null;//dr\classes\dom\tag\form\InputText
    private $objEdtAccountExpiresTime = null;//dr\classes\dom\tag\form\InputTime
    private $objChkLoginEnabled = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objOptLanguage = null;//dr\classes\dom\tag\form\Select     
    private $objOptGroup = null;//dr\classes\dom\tag\form\Select  user role
    private $objOptAccount = null;//dr\classes\dom\tag\form\Select  Usergroup   
    
    private $objLblHintSessions1 = null;//Label
    private $objLblHintSessions2 = null;//Label
    private $objUserSessions = null;//TSysCMSUserSessions
        
    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
        global $objCountrySettings;
        
                
            //username
        $this->objEdtUsername = new InputText();
        $this->objEdtUsername->setNameAndID('edtUsername');
        $this->objEdtUsername->setClass('fullwidthtag');   
        $this->objEdtUsername->setRequired(true);   
        $this->objEdtUsername->setMaxLength(255);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '255'), 255);
        $this->objEdtUsername->addValidator($objValidator);    
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtUsername->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtUsername, '', transm($this->getModule(), 'userdetail_form_field_username', 'username'));

            //username public
        $this->objEdtUsernamePublic = new InputText();
        $this->objEdtUsernamePublic->setNameAndID('edtUsernamePublic');
        $this->objEdtUsernamePublic->setClass('fullwidthtag');   
        $this->objEdtUsernamePublic->setRequired(true);   
        $this->objEdtUsernamePublic->setMaxLength(255);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '255'), 255);
        $this->objEdtUsernamePublic->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtUsernamePublic, '', transm($this->getModule(), 'userdetail_form_field_usernamepublic', 'public username (published on websites etc)'));        

            //password old
        $sPassHint = '';
        if (!$this->getModel()->getNew())//existing record
            $sPassHint = ' '.transm($this->getModule(), 'form_field_passwordold_hint_existingrecord_leaveempty', '(only if you want to change password, otherwise leave empty)');
        $this->objEdtPasswordOld = new InputPassword();
        $this->objEdtPasswordOld->setNameAndID('edtPasswordOld');
        $this->objEdtPasswordOld->setClass('fullwidthtag');   
        $this->objEdtPasswordOld->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        if (!$this->getModel()->getNew())//existing record
            $this->getFormGenerator()->add($this->objEdtPasswordOld, '', transm($this->getModule(), 'userdetail_form_field_passwordold', 'current password').$sPassHint); 

            //password new
        $sPassHint = '';
        if (!$this->getModel()->getNew())//existing record
            $sPassHint = ' '.transm($this->getModule(), 'form_field_passwordnew_hint_existingrecord_leaveempty', '(if you want to change password, otherwise leave empty)');
        $this->objEdtPasswordNew = new InputPassword();
        $this->objEdtPasswordNew->setNameAndID('edtPasswordNew');
        $this->objEdtPasswordNew->setClass('fullwidthtag');   
        $this->objEdtPasswordNew->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->getFormGenerator()->add($this->objEdtPasswordNew, '', transm($this->getModule(), 'userdetail_form_field_passwordnew', 'new password').$sPassHint); 

            //password repeat
        $sPassRepeatHint = '';
        if (!$this->getModel()->getNew())//existing record
            $sPassHint = ' '.transm($this->getModule(), 'form_field_passwordrepeat_hint_existingrecord_leaveempty', '(only if you want to change password)');
        $this->objEdtPasswordRepeat = new InputPassword();
        $this->objEdtPasswordRepeat->setNameAndID('edtPasswordRepeat');
        $this->objEdtPasswordRepeat->setClass('fullwidthtag');   
        $this->objEdtPasswordRepeat->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->getFormGenerator()->add($this->objEdtPasswordRepeat, '', transm($this->getModule(), 'userdetail_form_field_passwordnewrepeat', 'repeat new password').$sPassHint); 
        
        
             //email (can be empty)
        $this->objEdtEmail = new InputText();
        $this->objEdtEmail->setNameAndID('edtEmail');   
        $this->objEdtEmail->setClass('fullwidthtag');   
        $this->objEdtEmail->setMaxLength(255);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '255'), 255);
        $this->objEdtEmail->addValidator($objValidator);    
        $objValidator = new Emailaddress(transcms('form_error_notavalidemailaddress', 'This not a valid email address'), true);
        $this->objEdtEmail->addValidator($objValidator);            
        $this->getFormGenerator()->add($this->objEdtEmail, '', transm($this->getModule(), 'userdetail_form_field_emailaddress', 'email address'));
        
        //login expires 
        // if (auth($this->getModule(), $this->getAuthorisationCategory(), 'edit: date login expires'))
        {           
            //date
            $this->objEdtLoginExpiresDate = new InputDate($this->getDateFormatDefault());
            $this->objEdtLoginExpiresDate->setNameAndID('edtLoginExpiresDate');   
            // $objDateMin = new TDateTime(time());
            // $objValidator = new DateMin(transcms('form_error_dateneedstobebefore', 'Date needs to be later than [date]', 'date', $objDateMin->getDateAsString($this->getDateFormatDefault())), $objDateMin, $this->getDateFormatDefault(),  true);
            // $this->objEdtLoginExpiresDate->addValidator($objValidator);                                    
            
            //time
            $this->objEdtLoginExpiresTime = new InputTime($this->getTimeFormatDefault());
            $this->objEdtLoginExpiresTime->setNameAndID('edtLoginExpiresTime');
            $objValidator = new Time(transcms('form_error_notavalidtime', '[time] is not a valid time', 'time', $this->objEdtLoginExpiresTime->getContentsSubmitted()->getValueAsString()), $this->getTimeFormatDefault(),  true, $this->getTimeFormatDefault());
            $this->objEdtLoginExpiresTime->addValidator($objValidator);                        
            
            $this->getFormGenerator()->addArray(array($this->objEdtLoginExpiresDate, $this->objEdtLoginExpiresTime), '', transm($this->getModule(), 'userdetail_form_field_loginexpires', 'login expires (user can\'t log in after this date) (leave empty for no expiration)'));        
        }
        
        //password expires 
        // if (auth($this->getModule(), $this->getAuthorisationCategory(), 'edit: date password expires'))
        {                 
            //date
            $this->objEdtPasswordExpiresDate = new InputDate($this->getDateFormatDefault());
            $this->objEdtPasswordExpiresDate->setNameAndID('edtPasswordExpiresDate');   
            $objValidator = new Date(transcms('form_error_notavaliddate', 'This not a valid date'), $this->getDateFormatDefault(),  true, $this->getDateFormatDefault());
            $this->objEdtPasswordExpiresDate->addValidator($objValidator);            
            
            //time
            $this->objEdtPasswordExpiresTime = new InputTime($this->getTimeFormatDefault());
            $this->objEdtPasswordExpiresTime->setNameAndID('edtPasswordExpiresTime');
            $objValidator = new Time(transcms('form_error_notavalidtime', '[time] is not a valid time', 'time', $this->objEdtPasswordExpiresTime->getContentsSubmitted()->getValueAsString()), $this->getTimeFormatDefault(),  true, $this->getTimeFormatDefault());
            $this->objEdtPasswordExpiresTime->addValidator($objValidator);                        
            
            
            $this->getFormGenerator()->addArray(array($this->objEdtPasswordExpiresDate, $this->objEdtPasswordExpiresTime), '', transm($this->getModule(), 'form_field_passwordexpires', 'password expires (prompts user to change password on login) (leave empty for no expiration)'));                                        
        }        

        //scheduled for deletion after (and is delete in cron job afterwards)
            //date
            $this->objEdtAccountExpiresDate = new InputDate($this->getDateFormatDefault());
            $this->objEdtAccountExpiresDate->setNameAndID('edtAccountExpiresDate');   
            $objValidator = new Date(transcms('form_error_notavaliddate', 'This not a valid date'), $this->getDateFormatDefault(),  true, $this->getDateFormatDefault());
            $this->objEdtAccountExpiresDate->addValidator($objValidator);            
            
            //time
            $this->objEdtAccountExpiresTime = new InputTime($this->getTimeFormatDefault());
            $this->objEdtAccountExpiresTime->setNameAndID('edtAccountExpiresTime');
            $objValidator = new Time(transcms('form_error_notavalidtime', '[time] is not a valid time', 'time', $this->objEdtAccountExpiresTime->getContentsSubmitted()->getValueAsString()), $this->getTimeFormatDefault(),  true, $this->getTimeFormatDefault());
            $this->objEdtAccountExpiresTime->addValidator($objValidator);                        
            
        
        $this->getFormGenerator()->addArray(array($this->objEdtAccountExpiresDate, $this->objEdtAccountExpiresTime), '', transm($this->getModule(), 'userdetail_form_field_scheduleddeletion', 'Auto delete user after (deleted by cron job, leave empty for no expiration)'));                                        



        //is user enabled to log in?        
        // if (auth($this->getModule(), $this->getAuthorisationCategory(), 'edit: user enabled (to log in)'))
        {
            $this->objChkLoginEnabled = new InputCheckbox();
            $this->objChkLoginEnabled->setNameAndID('edtLoginEnabled');
            $this->getFormGenerator()->add($this->objChkLoginEnabled, '', transm($this->getModule(), 'userdetail_form_field_enabled', 'able to log in'));         
        }
        
        //language
        $this->objOptLanguage = new Select();
        $this->objOptLanguage->setNameAndID('optLanguage');
        $this->getFormGenerator()->add($this->objOptLanguage, '', transm($this->getModule(), 'userdetail_form_field_language', 'language'));
        
        //user group / role
        $this->objOptGroup = new Select();
        $this->objOptGroup->setNameAndID('optGroup');
        $this->getFormGenerator()->add($this->objOptGroup, '', transm($this->getModule(), 'userdetail_form_field_userrole', 'user role'));

        //user acount
        $this->objOptAccount = new Select();
        $this->objOptAccount->setNameAndID('optAccount');
        $this->getFormGenerator()->add($this->objOptAccount, '', transm($this->getModule(), 'userdetail_form_field_useraccount', 'part of user acount'));


        //sessions
        //done in modelToForm()
        
    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_CMSUsers::PERM_CAT_USERS;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
        global $objLoginController;
        $objUserSessionsCurr = $objLoginController->getUserSessions();
        
        $this->getModel()->set(TSysCMSUsers::FIELD_USERNAME, $this->objEdtUsername->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysCMSUsers::FIELD_USERNAMEPUBLIC, $this->objEdtUsernamePublic->getContentsSubmitted()->getValueAsString());
        // $this->getModel()->set(TSysCMSUsers::FIELD_EMAILADDRESS_OLD, $this->objEdtEmail->getContentsSubmitted()->getValueAsString());
        $this->getModel()->setEmailAddressDecrypted($this->objEdtEmail->getContentsSubmitted()->getValueAsString());
        
        //password
        if ($this->objEdtPasswordNew->getContentsSubmitted()->getValueAsString()) //only set if there is a value in the field
        {                        
            $this->getModel()->setPasswordDecrypted($this->objEdtPasswordNew->getContentsSubmitted()->getValueAsString(), true);
            
            //the user may want to change a password because of an unauthorised login
            //to be sure we also change the tokens
            $objUserSessionsDel = new TSysCMSUsersSessions();
            $objUserSessionsDel->find(TSysCMSUsersSessions::FIELD_USERID, $this->getModel()->getID()); //all sessions of user
            $objUserSessionsDel->find(TSysCMSUsersSessions::FIELD_RANDOMID, $objUserSessionsCurr->getRandomID(), COMPARISON_OPERATOR_NOT_EQUAL_TO); //except current session
            $objUserSessionsDel->deleteFromDB(true);

            //set new expiration date on password --> is not changed here automatically, but you can do it by hand by filling out expiration date
            // $iDaysExpires = 0;
            // $iDaysExpires = (int)getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_USERPASSWORDEXPIRES_DAYS);
            // if ($iDaysExpires > 0)
            // {
            //     $this->getModel()->getPasswordExpires()->setNow();
            //     $this->getModel()->getPasswordExpires()->addDays($iDaysExpires);
            // }            
            
        }
        
        //login expires
        // if (auth($this->getModule(), $this->getAuthorisationCategory(), 'edit: date login expires'))        
        {
            //we set the time first so it defaults to 0:00 if the field is empty, but the date is not. the date & time are also empty when you leave the date field empty
            $this->getModel()->setTimeAsString(TSysCMSUsers::FIELD_LOGINEXPIRES, $this->objEdtLoginExpiresTime->getContentsSubmitted()->getValueAsString(), $this->getTimeFormatDefault());        
            $this->getModel()->setDateAsString(TSysCMSUsers::FIELD_LOGINEXPIRES, $this->objEdtLoginExpiresDate->getContentsSubmitted()->getValueAsString(), $this->getDateFormatDefault());        
        }
        
        //password expires
        // if (auth($this->getModule(), $this->getAuthorisationCategory(), 'edit: date password expires'))
        {            
            //we set the time first so it defaults to 0:00 if the field is empty, but the date is not. the date & time are also empty when you leave the date field empty
            $this->getModel()->setTimeAsString(TSysCMSUsers::FIELD_PASSWORDEXPIRES, $this->objEdtPasswordExpiresTime->getContentsSubmitted()->getValueAsString(), $this->getTimeFormatDefault());        
            $this->getModel()->setDateAsString(TSysCMSUsers::FIELD_PASSWORDEXPIRES, $this->objEdtPasswordExpiresDate->getContentsSubmitted()->getValueAsString(), $this->getDateFormatDefault());                    
        }
        
        //account expires
        $this->getModel()->setTimeAsString(TSysCMSUsers::FIELD_DELETEAFTER, $this->objEdtAccountExpiresTime->getContentsSubmitted()->getValueAsString(), $this->getTimeFormatDefault());        
        $this->getModel()->setDateAsString(TSysCMSUsers::FIELD_DELETEAFTER, $this->objEdtAccountExpiresDate->getContentsSubmitted()->getValueAsString(), $this->getDateFormatDefault());                    


        //user enabled
        // if (auth($this->getModule(), $this->getAuthorisationCategory(), 'edit: user enabled (to log in)'))
            $this->getModel()->set(TSysCMSUsers::FIELD_LOGINENABLED, $this->objChkLoginEnabled->getContentsSubmitted()->getValueAsBool());        
        
        //language
        $this->getModel()->set(TSysCMSUsers::FIELD_LANGUAGEID, $this->objOptLanguage->getContentsSubmitted()->getValueAsInt());
        
        //user role
        $this->getModel()->set(TSysCMSUsers::FIELD_USERROLEID, $this->objOptGroup->getContentsSubmitted()->getValueAsInt());

        //user account
        $this->getModel()->set(TSysCMSUsers::FIELD_CMSUSERACCOUNTID, $this->objOptAccount->getContentsSubmitted()->getValueAsInt());



        //user sessions
        $objSessions = $this->objUserSessions;
        $objSessions->resetRecordpointer();
        while($objSessions->next())
        {
            $sVarName = 'chkSession'.$objSessions->getRandomID();
            if (isset($_POST[$sVarName]))
            {
                if ($_POST[$sVarName ] == '1')
                {                
                    $objSessionsDel = $objSessions->getCopy();
                    $objSessionsDel->newQuery();
                    $objSessionsDel->findRandomID($objSessions->getRandomID());
                    $objSessionsDel->deleteFromDB(true);
                    unset($objSessionsDel);
                }
            }
        } 
        $this->objUserSessions->loadFromDB();
    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {  
        $this->objEdtUsername->setValue($this->getModel()->get(TSysCMSUsers::FIELD_USERNAME));
        $this->objEdtUsernamePublic->setValue($this->getModel()->get(TSysCMSUsers::FIELD_USERNAMEPUBLIC));
        //$this->objEdtPassword->setValue($this->getModel()->get(TSysCMSUsers::FIELD_PASSWORDENCRYPTED)); --> don't show password (it's no use because it's encrypted)
        // $this->objEdtEmail->setValue($this->getModel()->get(TSysCMSUsers::FIELD_EMAILADDRESS_OLD)); 
        $this->objEdtEmail->setValue($this->getModel()->get(TSysCMSUsers::FIELD_EMAILADDRESSENCRYPTED, '', true)); 
        
        //login expires
        // if (auth($this->getModule(), $this->getAuthorisationCategory(), 'edit: date login expires'))        
        {
            $this->objEdtLoginExpiresDate->setValue($this->getModel()->getDateAsString(TSysCMSUsers::FIELD_LOGINEXPIRES, $this->getDateFormatDefault())); 
            $this->objEdtLoginExpiresTime->setValue($this->getModel()->getDateAsString(TSysCMSUsers::FIELD_LOGINEXPIRES, $this->getTimeFormatDefault())); 
        }
        
        //password expires
        // if (auth($this->getModule(), $this->getAuthorisationCategory(), 'edit: date password expires'))
        {
            $this->objEdtPasswordExpiresDate->setValue($this->getModel()->getDateAsString(TSysCMSUsers::FIELD_PASSWORDEXPIRES, $this->getDateFormatDefault())); 
            $this->objEdtPasswordExpiresTime->setValue($this->getModel()->getDateAsString(TSysCMSUsers::FIELD_PASSWORDEXPIRES, $this->getTimeFormatDefault())); 
        }

        //account expires
        $this->objEdtAccountExpiresDate->setValue($this->getModel()->getDateAsString(TSysCMSUsers::FIELD_DELETEAFTER, $this->getDateFormatDefault())); 
        $this->objEdtAccountExpiresTime->setValue($this->getModel()->getDateAsString(TSysCMSUsers::FIELD_DELETEAFTER, $this->getTimeFormatDefault())); 
    

        //enabled
        // if (auth($this->getModule(), $this->getAuthorisationCategory(), 'edit: user enabled (to log in)'))
            $this->objChkLoginEnabled->setChecked($this->getModel()->get(TSysCMSUsers::FIELD_LOGINENABLED));   
        
        //language
        $objLangs = new TSysLanguages();
        $objLangs->sort(TSysLanguages::FIELD_LANGUAGE);
        $objLangs->limitNone();
        $objLangs->loadFromDBByCMSLanguage();
        $objLangs->generateHTMLSelect($this->getModel()->get(TSysCMSUsers::FIELD_LANGUAGEID), $this->objOptLanguage);
        
        //user roles
        $objGroups = new TSysCMSUsersRoles();
        $objGroups->find(TSysCMSUsersRoles::FIELD_ISANONYMOUS, false); //exclude anonymous users
        $objGroups->sort(TSysCMSUsersRoles::FIELD_ROLENAME);
        $objGroups->limitNone();
        $objGroups->loadFromDB();
        $objGroups->generateHTMLSelect($this->getModel()->get(TSysCMSUsers::FIELD_USERROLEID), $this->objOptGroup);

        //user accounts
        $objAccounts = new TSysCMSUserAccounts();
        $objAccounts->sort(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER);
        $objAccounts->limitNone();
        $objAccounts->loadFromDB();
        $objAccounts->generateHTMLSelect($this->getModel()->get(TSysCMSUsers::FIELD_CMSUSERACCOUNTID), $this->objOptAccount);

        //sessions
        $this->populateSessions();

    }
    
   /**
     * is called when a record is loaded
     */
    public function onLoad()
    {
        //needs to change password?
        if (!$this->getModel()->getNew())
            if ($this->getModel()->needsNewPassword())
                sendMessageError(transm($this->getModule(), 'message_user_needstochangepassword', 'This user needs to change password for security reasons'));


        if (isset($_GET[ACTION_VARIABLE_ID])) //not a new record (we don't have sessions yet)
        {
            if (is_numeric($_GET[ACTION_VARIABLE_ID]))
            {
                $this->objUserSessions->newQuery();
                $this->objUserSessions->find(TSysCMSUsersSessions::FIELD_USERID, $_GET[ACTION_VARIABLE_ID]);
                $this->objUserSessions->limit(0);//no limit
                $this->objUserSessions->loadFromDB();
            }
                
        }                
    }
    
    /**
     * is called when a record is saved
     * this method has to send the proper error messages to the user!!
     * 
     * @return boolean it will NOT SAVE
     */
    public function onSavePre()
    {                        
        //if username not unique
        if ($this->getModel()->isUsernameTakenDB($this->objEdtUsername->getContentsSubmitted()->getValueAsString()))
        {
            sendMessageError(transm($this->getModule(), 'message_usernamenotunique', 'User NOT SAVED, choose another username'));//don't give a reason due to security reasons         
            return false;
        }
        
        //check current password
        if (!$this->objEdtPasswordOld->getContentsSubmitted()->isEmpty())
        {
            $objTempUser = new TSysCMSUsers();
            if (!$objTempUser->loadFromDBByUsername($this->objEdtUsername->getContentsSubmitted()->getValueAsString()))
                return false;
            if (!password_verify($this->objEdtPasswordOld->getContentsSubmitted()->getValueAsString(), $objTempUser->getPasswordEncrypted()))
            {   
                sendMessageError(transm($this->getModule(), 'message_passwordold_isnotcorrect', 'User NOT SAVED, your current password isn\'t correct'));
                return false;
            }
        }    
        
        //password and password repeat need to match
        if ($this->objEdtPasswordNew->getContentsSubmitted()->getValueAsString() != $this->objEdtPasswordRepeat->getContentsSubmitted()->getValueAsString())
        {
            sendMessageError(transm($this->getModule(), 'message_passwords_donotmatch', 'User NOT SAVED, passwords do not match. The two passwords need to match in order to set the new password'));//don't give a reason due to security reasons         
            return false;
        }        
        
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
        $this->objUserSessions = new TSysCMSUsersSessions();
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
    private function populateSessions()
    {
        global $objLoginController;
        $this->objLblHintSessions1 = new Label();  
        $this->objLblHintSessions2 = new Label();  
        $sTransSectionSessions = '';
        $sTransSectionSessions = transm($this->getModule(), 'section_sessions_title', 'Open login sessions');
        $sTempTextCheckbox = '';

        if (isset($_GET[ACTION_VARIABLE_ID])) //existing record
        {
            $objSessions = $this->objUserSessions;//speed things up

            //label explanation
            if ($objSessions->count() > 0)
            {
                $this->objLblHintSessions1->setText(transm($this->getModule(), 'form_label_sessions_userloggedinhere', 'User is logged in [times] times on these devices:', 'times', $objSessions->count()));
                $this->objLblHintSessions2->setText(transm($this->getModule(), 'form_label_sessions_checktodelete', '(to delete session: click checkbox and click save)'));
            }
            else
            {
                $this->objLblHintSessions1->setText(transm($this->getModule(), 'form_label_sessions_nosessions', 'User isn\'t logged in anywhere'));
            }
            $this->getFormGenerator()->add($this->objLblHintSessions1, $sTransSectionSessions);        
            $this->getFormGenerator()->add($this->objLblHintSessions2, $sTransSectionSessions);        

            //display sessions
            $objSessions->resetRecordpointer();
            while($objSessions->next())
            {             
                $objCheckbox = new InputCheckbox();
                $objCheckbox->setNameAndID('chkSession'.$objSessions->getRandomID());
                
                $sTempTextCheckbox = '';
                if ($objSessions->getRandomID() == $objLoginController->getUserSessions()->getRandomID())
                {
                    $sTempTextCheckbox.= transm($this->getModule(), 'message_sessions_iscurrentsession', 'CURRENT SESSION: ');
                    $objCheckbox->setDisabled(true);
                }

                $sTempTextCheckbox.= $objSessions->getBrowser().', '.$objSessions->getOperatingSystem().' ('.$objSessions->getSessionStarted()->getDateTimeAsString($this->getDateTimeFormatDefault()).')';


                $this->getFormGenerator()->add($objCheckbox, $sTransSectionSessions, $sTempTextCheckbox);
                unset($objCheckbox);//I don't save the checkboxes in an array or something, when we need them, we create new ones
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
        return new TSysCMSUsers(); 
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
        return 'list_users';
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
            return transm($sCurrentModule, 'pagetitle_detailsave_user_new', 'Create new user');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_user_edit', 'Edit user: [username]', 'username', $this->getModel()->getUsername());   
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
