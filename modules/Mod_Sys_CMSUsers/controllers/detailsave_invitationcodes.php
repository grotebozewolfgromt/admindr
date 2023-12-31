<?php

/*
 */

namespace dr\modules\Mod_Sys_CMSUsers\controllers;

use dr\classes\models\TModel;
use dr\classes\controllers\TCRUDDetailSaveController;

use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\form\InputDate;
use dr\classes\dom\tag\form\InputNumber;
use dr\classes\dom\tag\form\InputTime;
use dr\classes\dom\validator\Characterwhitelist;
use dr\classes\dom\validator\DateMin;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Onlynumeric;
use dr\classes\dom\validator\Required;
use dr\classes\dom\validator\Time;
//don't forget ;)
use dr\classes\models\TSysCMSInvitationCodes;
use dr\classes\types\TDateTime;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;


include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_jquery.php');
include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


/**
 * Description of TCRUDDetailSaveLanguages
 *
 * @author drenirie
 */
class detailsave_invitationcodes extends TCRUDDetailSaveController
{   
    private $objEdtName = null;//dr\classes\dom\tag\form\InputText
    private $objEdtCode = null;//dr\classes\dom\tag\form\InputText
    private $objEdtRedeems = null;//dr\classes\dom\tag\form\InputNumber
    private $objEdtMaxRedeems = null;//dr\classes\dom\tag\form\InputNumber
    private $objEdtStartDate = null;//dr\classes\dom\tag\form\InputDate
    private $objEdtStartTime = null;//dr\classes\dom\tag\form\InputTime
    private $objEdtStopDate = null;//dr\classes\dom\tag\form\InputDate
    private $objEdtStopTime = null;//dr\classes\dom\tag\form\InputTime
    private $objChkEnabled = null;//dr\classes\dom\tag\form\InputCheckbox

    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
            //name
        $this->objEdtName = new InputText();
        $this->objEdtName->setNameAndID('edtName');
        $this->objEdtName->setClass('fullwidthtag');         
        $this->objEdtName->setRequired(true);   
        $this->objEdtName->setMaxLength(100);                
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtName->addValidator($objValidator);    
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtName->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtName, '', transm($this->getModule(), 'form_field_namecode', 'name (only you can see it)'));

            //code
        $this->objEdtCode = new InputText();
        $this->objEdtCode->setNameAndID('edtCode');
        $this->objEdtCode->setClass('fullwidthtag');                 
        $this->objEdtCode->setRequired(true); 
        $this->objEdtCode->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtCode->addValidator($objValidator);  
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtCode->addValidator($objValidator);       
        $objValidator = new Characterwhitelist(transcms('form_error_charactersnotallowed', 'Some characters are not allowed'), TSysCMSInvitationCodes::ALLOWEDCHARSCODE);
        $this->objEdtCode->addValidator($objValidator);       
        $this->getFormGenerator()->add($this->objEdtCode, '', transm($this->getModule(), 'form_field_invitationcode', 'Invitation code (allowed: [allowed])','allowed',  TSysCMSInvitationCodes::ALLOWEDCHARSCODE)); 
   
            //current redeems
        $this->objEdtRedeems = new InputNumber();
        $this->objEdtRedeems->setNameAndID('edtRedeems');
        $this->objEdtRedeems->setRequired(true); 
        $this->objEdtRedeems->setMaxLength(10);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
        $this->objEdtRedeems->addValidator($objValidator);  
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtRedeems->addValidator($objValidator);       
        $objValidator = new Onlynumeric(transcms('form_error_onlynumbersallowed', 'Only numbers are allowed'));
        $this->objEdtRedeems->addValidator($objValidator);       
        $this->getFormGenerator()->add($this->objEdtRedeems, '', transm($this->getModule(), 'form_field_currentredeems', 'Amount of times code is redeemed')); 
        
            //max redeems
        $this->objEdtMaxRedeems = new InputNumber();
        $this->objEdtMaxRedeems->setNameAndID('edtMaxRedeems');
        $this->objEdtMaxRedeems->setRequired(true); 
        $this->objEdtMaxRedeems->setMaxLength(10);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
        $this->objEdtMaxRedeems->addValidator($objValidator);  
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtMaxRedeems->addValidator($objValidator);       
        $objValidator = new Onlynumeric(transcms('form_error_onlynumbersallowed', 'Only numbers are allowed'));
        $this->objEdtMaxRedeems->addValidator($objValidator);       
        $this->getFormGenerator()->add($this->objEdtMaxRedeems, '', transm($this->getModule(), 'form_field_maxredeems', 'Limit redemptions (0=unlimited)')); 
    
            //==== start date+time
            //date
            $this->objEdtStartDate = new InputDate($this->getDateFormatDefault());
            $this->objEdtStartDate->setNameAndID('edtStartDate');   
            // $objDateMin = new TDateTime(time());
            // $objValidator = new DateMin(transcms('form_error_dateneedstobebefore', 'Date needs to be later than [date]', 'date', $objDateMin->getDateAsString($this->getDateFormatDefault())), $objDateMin, $this->getDateFormatDefault(),  true);
            // $this->objEdtStartDate->addValidator($objValidator);                                    
            
            //time
            $this->objEdtStartTime = new InputTime($this->getTimeFormatDefault());
            $this->objEdtStartTime->setNameAndID('edtStartTime');
            $objValidator = new Time(transcms('form_error_notavalidtime', '[time] is not a valid time', 'time', $this->objEdtStartTime->getContentsSubmitted()->getValueAsString()), $this->getTimeFormatDefault(),  true, $this->getTimeFormatDefault());
            $this->objEdtStartTime->addValidator($objValidator);                        
            
            $this->getFormGenerator()->addArray(array($this->objEdtStartDate, $this->objEdtStartTime), '', transm($this->getModule(), 'form_field_invitationcodes_startdatetime', 'Start date'));        

            //==== end date+time
            //date
            $this->objEdtEndDate = new InputDate($this->getDateFormatDefault());
            $this->objEdtEndDate->setNameAndID('edtEndDate');   
            // $objDateMin = new TDateTime(time());
            // $objValidator = new DateMin(transcms('form_error_dateneedstobebefore', 'Date needs to be later than [date]', 'date', $objDateMin->getDateAsString($this->getDateFormatDefault())), $objDateMin, $this->getDateFormatDefault(),  true);
            // $this->objEdtEndDate->addValidator($objValidator);                                    
            
            //time
            $this->objEdtEndTime = new InputTime($this->getTimeFormatDefault());
            $this->objEdtEndTime->setNameAndID('edtEndTime');
            $objValidator = new Time(transcms('form_error_notavalidtime', '[time] is not a valid time', 'time', $this->objEdtEndTime->getContentsSubmitted()->getValueAsString()), $this->getTimeFormatDefault(),  true, $this->getTimeFormatDefault());
            $this->objEdtEndTime->addValidator($objValidator);                        
            
            $this->getFormGenerator()->addArray(array($this->objEdtEndDate, $this->objEdtEndTime), '', transm($this->getModule(), 'form_field_invitationcodes_enddatetime', 'End date'));        



            //is enabled
        $this->objChkEnabled = new InputCheckbox();
        $this->objChkEnabled->setNameAndID('chkEnabled');
        $this->getFormGenerator()->add($this->objChkEnabled, '', transm($this->getModule(), 'form_field_invitationcode_isenabled', 'Enabled'));           
    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_CMSUsers::PERM_CAT_INVITATIONCODES;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
        $this->getModel()->set(TSysCMSInvitationCodes::FIELD_CODENAME, $this->objEdtName->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysCMSInvitationCodes::FIELD_REDEMPTIONCODE, $this->objEdtCode->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysCMSInvitationCodes::FIELD_CURRENTREDEEMS, $this->objEdtRedeems->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysCMSInvitationCodes::FIELD_MAXREDEEMS, $this->objEdtMaxRedeems->getContentsSubmitted()->getValueAsString());

        //start date+time
            //we set the time first so it defaults to 0:00 if the field is empty, but the date is not. the date & time are also empty when you leave the date field empty
            $this->getModel()->setTimeAsString(TSysCMSInvitationCodes::FIELD_DATESTART, $this->objEdtStartTime->getContentsSubmitted()->getValueAsString(), $this->getTimeFormatDefault());        
            $this->getModel()->setDateAsString(TSysCMSInvitationCodes::FIELD_DATESTART, $this->objEdtStartDate->getContentsSubmitted()->getValueAsString(), $this->getDateFormatDefault());                    

        //end date+time
            //we set the time first so it defaults to 0:00 if the field is empty, but the date is not. the date & time are also empty when you leave the date field empty
            $this->getModel()->setTimeAsString(TSysCMSInvitationCodes::FIELD_DATEEND, $this->objEdtEndTime->getContentsSubmitted()->getValueAsString(), $this->getTimeFormatDefault());        
            $this->getModel()->setDateAsString(TSysCMSInvitationCodes::FIELD_DATEEND, $this->objEdtEndDate->getContentsSubmitted()->getValueAsString(), $this->getDateFormatDefault());                    

        $this->getModel()->set(TSysCMSInvitationCodes::FIELD_ISENABLED, $this->objChkEnabled->getContentsSubmitted()->getValueAsBool());                
    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {         
        $this->objEdtName->setValue($this->getModel()->get(TSysCMSInvitationCodes::FIELD_CODENAME));
        $this->objEdtCode->setValue($this->getModel()->get(TSysCMSInvitationCodes::FIELD_REDEMPTIONCODE));
        $this->objEdtRedeems->setValue($this->getModel()->get(TSysCMSInvitationCodes::FIELD_CURRENTREDEEMS));
        $this->objEdtMaxRedeems->setValue($this->getModel()->get(TSysCMSInvitationCodes::FIELD_MAXREDEEMS));

        //start date
        $this->objEdtStartDate->setValue($this->getModel()->getDateAsString(TSysCMSInvitationCodes::FIELD_DATESTART, $this->getDateFormatDefault())); 
        $this->objEdtStartTime->setValue($this->getModel()->getDateAsString(TSysCMSInvitationCodes::FIELD_DATESTART, $this->getTimeFormatDefault())); 

        //end date
        $this->objEdtEndDate->setValue($this->getModel()->getDateAsString(TSysCMSInvitationCodes::FIELD_DATEEND, $this->getDateFormatDefault())); 
        $this->objEdtEndTime->setValue($this->getModel()->getDateAsString(TSysCMSInvitationCodes::FIELD_DATEEND, $this->getTimeFormatDefault())); 


        $this->objChkEnabled->setChecked($this->getModel()->get(TSysCMSInvitationCodes::FIELD_ISENABLED));
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
        return false;
    }    



   /**
     * returns a new model object
     *
     * @return TModel
     */
    public function getNewModel()
    {
        return new TSysCMSInvitationCodes(); 
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
        return 'list_invitationcodes';
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
            return transm($sCurrentModule, 'pagetitle_detailsave_invitationcodes_new', 'Create new invitation code');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_invitationcodes_edit', 'Edit invitation code: [name]', 'name', $this->getModel()->getCodeName());           
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
