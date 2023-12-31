<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Sys_CMSUsers\controllers;

use dr\classes\models\TModel;
use dr\classes\controllers\TCRUDDetailSaveController;
use dr\classes\locale\TCountrySettings;

use dr\classes\dom\tag\form\Form;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\InputPassword;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\form\InputDate;
use dr\classes\dom\tag\form\InputTime;
use dr\classes\dom\tag\form\InputDatetime;
use dr\classes\dom\tag\form\InputNumber;
use dr\classes\dom\tag\Li;
use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\Script;
use dr\classes\dom\tag\form\Option;
use dr\classes\dom\tag\form\Label;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Required;
use dr\classes\dom\validator\Emailaddress;
use dr\classes\dom\validator\Date;
use dr\classes\dom\validator\DateMin;
use dr\classes\dom\validator\DateMax;
use dr\classes\dom\validator\DateTime;
use dr\classes\dom\validator\Time;
use dr\classes\models\TSysCMSPermissions;
use dr\classes\models\TSysCMSUsers;
use dr\classes\types\TDateTime;


//don't forget ;)
use dr\classes\models\TSysCMSUsersRoles;
use dr\classes\models\TSysCMSUsersRolesAssignUsers;
use dr\classes\models\TUsersAbstract;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;

include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');
include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_jquery.php');



/**
 * Description of TCRUDDetailSaveLanguages
 *
 * @author drenirie
 */
class detailsave_usersroles extends TCRUDDetailSaveController
{
    private $objEdtGroupname = null;//dr\classes\dom\tag\form\InputText
    private $objEdtDescription = null;//dr\classes\dom\tag\form\InputText
    private $objEdtMaxUsersInAccount = null;//dr\classes\dom\tag\form\InputNumber
    private $objChkAnonymous = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objRolesAll = null; //TSysCMSUsersRoles
    private $objRolesAssignUsers = null; //TSysCMSUsersRolesAssignUsers
    private $objLblHintAssignUsers = null;//Label
    private $objPermissions = null; //TSysCMSPermissions    
    private $objLblHintPermissions = null;//Label


    private $sTransFormSectionTransAllowedAssignUserRoles = 'Assign users to roles';
    private $sTransFormSectionPermissions = 'Permissions';

    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {

        $this->sTransFormSectionTransAllowedAssignUserRoles = transm($this->getModule(), 'userrolesdetail_formsectionname_assignuserstoroles', 'ASSIGN USERS TO ROLES');
        $this->sTransFormSectionPermissions = transm($this->getModule(), 'userrolesdetail_formsectionname_permissions', 'PERMISSIONS');


            //role name
        $this->objEdtGroupname = new InputText();
        $this->objEdtGroupname->setNameAndID('edtRoleName');
        $this->objEdtGroupname->setClass('fullwidthtag');   
        $this->objEdtGroupname->setRequired(true);   
        $this->objEdtGroupname->setMaxLength(50);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtGroupname->addValidator($objValidator);    
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtGroupname->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtGroupname, '', transm($this->getModule(), 'userrolesdetail_form_field_groupname', 'Role name'));

            //description
        $this->objEdtDescription = new InputText();
        $this->objEdtDescription->setNameAndID('edtRoleDescription');
        $this->objEdtDescription->setClass('fullwidthtag');   
        $this->objEdtDescription->setMaxLength(100);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtDescription->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtDescription, '', transm($this->getModule(), 'userrolesdetail_form_field_description', 'Description'));

            //max users account
        $this->objEdtMaxUsersInAccount = new InputNumber();
        $this->objEdtMaxUsersInAccount->setNameAndID('edtMaxUsersInAccount');
        // $this->objEdtMaxUsersInAccount->setClass('fullwidthtag');   
        $this->objEdtMaxUsersInAccount->setMaxLength(20);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
        $this->objEdtMaxUsersInAccount->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtMaxUsersInAccount, '', transm($this->getModule(), 'userrolesdetail_form_field_maxusersinaccount', 'Maximum number of users allowed in account (0=unlimited, -1=no users)'));
        

        //anonymous
        $this->objChkAnonymous= new InputCheckbox();
        $this->objChkAnonymous->setNameAndID('edtAnonymous');
        $this->getFormGenerator()->add($this->objChkAnonymous, '', transm($this->getModule(), 'userrolesdetail_form_field_anonymous', 'is anonymous (represents users that are not logged-in, users with this role can never log in)'));         


        //=====assign users other roles: 
        //label hint 
        $this->objLblHintAssignUsers = new Label();        
        if (!$this->getModel()->getNew()) //existing record
            $this->objLblHintAssignUsers->setText(transm($this->getModule(), 'userrolesdetail_form_label_permissionsallowassignusersroles', 'This role is allowed to assign users to the following roles:'));
        else
            $this->objLblHintAssignUsers->setText(transm($this->getModule(), 'userrolesdetail_form_label_permissionsallowassignusersroles_shownaftersave', 'Permission checkboxes for roles will be shown after saving'));
        $this->getFormGenerator()->add($this->objLblHintAssignUsers, $this->sTransFormSectionTransAllowedAssignUserRoles);

        //checkboxes
        if (!$this->getModel()->getNew()) //existing record
        {
            $objRolesAll = $this->objRolesAll;//speed things up
            while($objRolesAll->next())
            {                
                $objCheckbox = new InputCheckbox();
                $objCheckbox->setNameAndID('chkAssignUsersRoleID_'.$objRolesAll->getID());//we can't have spaces in variable names
                // $objCheckbox->setChecked($objPerm->getAllowed()); --> we check the checkboxes later
                // $objCheckbox->setLabel($objRolesAll->getRoleName());
                $this->getFormGenerator()->add($objCheckbox, $this->sTransFormSectionTransAllowedAssignUserRoles, $objRolesAll->getRoleName());
                unset($objCheckbox);//I don't save the checkboxes in an array or something, when we need them, we create new ones
            }   
        }

        
        //====permissions: 
        //label hint for permissions
        //we first need to add the label hint, because the modules have each their own section name and the label will be shown below them
        $this->objLblHintPermissions = new Label();        
        if (!$this->getModel()->getNew()) //existing record
            $this->objLblHintPermissions->setText(transm($this->getModule(), 'userrolesdetail_form_label_permissionsbelow', 'Permissions of modules and system:'));
        else
            $this->objLblHintPermissions->setText(transm($this->getModule(), 'userrolesdetail_form_label_permissionsshownaftersave', 'Permission checkboxes will be shown after saving'));
        $this->getFormGenerator()->add($this->objLblHintPermissions, $this->sTransFormSectionPermissions); //we

        //checkboxes
        if (!$this->getModel()->getNew()) //existing record
        {
            $objPerm = $this->objPermissions;//speed things up
            while($objPerm->next())
            {
                $arrResource = getAuthResourceArray($objPerm->getResource());
                $objCheckbox = new InputCheckbox();
                $objCheckbox->setNameAndID($this->resourceToVariablename($objPerm->getResource()));//we can't have spaces in variable names
                // $objCheckbox->setChecked($objPerm->getAllowed()); --> we check the checkboxes later
                $this->getFormGenerator()->add($objCheckbox, $arrResource['module'], $arrResource['category'].': '.$arrResource['operation']);
                unset($objCheckbox);//I don't save the checkboxes in an array or something, when we need them, we create new ones
            }   
        }

        
    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_CMSUsers::PERM_CAT_USERROLES;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {       
        //name
        $this->getModel()->set(TSysCMSUsersRoles::FIELD_ROLENAME, $this->objEdtGroupname->getContentsSubmitted()->getValueAsString());

        //description
        $this->getModel()->set(TSysCMSUsersRoles::FIELD_DESCRIPTION, $this->objEdtDescription->getContentsSubmitted()->getValueAsString());

        //max users in account
        $this->getModel()->set(TSysCMSUsersRoles::FIELD_MAXUSERSINACCOUNT, $this->objEdtMaxUsersInAccount->getContentsSubmitted()->getValueAsString());

        //anonymous
        $this->getModel()->set(TSysCMSUsersRoles::FIELD_ISANONYMOUS, $this->objChkAnonymous->getContentsSubmitted()->getValueAsBool());        

        //====assign users to role
        $sVarName = '';
        $objRoles = $this->objRolesAll;
        $objRoles->resetRecordpointer();

            //remove all the old ones first
            $objAssUser = $this->objRolesAssignUsers;
            $objAssUser->newQuery();
            $objAssUser->find(TSysCMSUsersRolesAssignUsers::FIELD_ROLEID, $this->getModel()->getID());
            $objAssUser->deleteFromDB(true);
            $objAssUser->clear();

            //add new roles
            while($objRoles->next())
            {

                $sVarName = $this->roleIDToVariablename($objRoles->getID());

                if (isset($_POST[$sVarName]))
                {
                    if ($_POST[$sVarName ] == '1')
                    {
                        $objAssUser->newRecord();
                        $objAssUser->setRoleID($this->getModel()->getID());
                        $objAssUser->setAllowedAssignUsersRoleID($objRoles->getID());
                    }
                }

            }

            if (!$objAssUser->saveToDBAll(true, false))
                sendMessageError(transm($this->getModule(), 'errormessage_savefailed_assignuserstoroles', 'Save assign users to roles FAILED'));


        //====permissions
        $sVarName = '';
        $objPerm = $this->objPermissions;
        $objPerm->resetRecordpointer();
        while($objPerm->next())
        {
            $sVarName = $this->resourceToVariablename($objPerm->getResource());
            if (isset($_POST[$sVarName]))
            {
                if ($_POST[$sVarName ] == '1')
                {
                    $objPerm->setAllowed(true);
                }
                else //everything else than 1
                {
                    $objPerm->setAllowed(false);                     
                }
            }
            else
            {
                $objPerm->setAllowed(false);
            }
        }      
        $objPerm->saveToDBAll();

    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {  
        $sSection = '';
        $arrResource = array();
    
        //name
        $this->objEdtGroupname->setValue($this->getModel()->get(TSysCMSUsersRoles::FIELD_ROLENAME));

        //description
        $this->objEdtDescription->setValue($this->getModel()->get(TSysCMSUsersRoles::FIELD_DESCRIPTION));

        //max users in account
        $this->objEdtMaxUsersInAccount->setValue($this->getModel()->getAsInt(TSysCMSUsersRoles::FIELD_MAXUSERSINACCOUNT));

        //anonymous
        $this->objChkAnonymous->setChecked($this->getModel()->get(TSysCMSUsersRoles::FIELD_ISANONYMOUS));   

        //checkboxes assign users userrole
        if (!$this->getModel()->getNew()) //existing record
        {
            $objAssUser = $this->objRolesAssignUsers;//speed things up
            $objAssUser->resetRecordpointer();
            while($objAssUser->next())
            {
                $objCheckbox = $this->getFormGenerator()->getElement($this->roleIDToVariablename($objAssUser->getAllowedAssignUsersRoleID()));
                $objCheckbox->setChecked(true);//only the records in the database are allowed
            }   
        }

        //checkboxes with permissions
        if (!$this->getModel()->getNew()) //existing record
        {
            $objPerm = $this->objPermissions;//speed things up
            $objPerm->resetRecordpointer();
            while($objPerm->next())
            {
                $objCheckbox = $this->getFormGenerator()->getElement($this->resourceToVariablename($objPerm->getResource()));
                $objCheckbox->setChecked($objPerm->getAllowed());
            }   
        }
     
    }
    
   /**
     * is called when a record is loaded
     */
    public function onLoad()
    {
        if (isset($_GET[ACTION_VARIABLE_ID])) //not a new record (we don't have a usergroup id yet)
        {
            if (is_numeric($_GET[ACTION_VARIABLE_ID]))
            {
                //all roles (needed to show all checkboxes - needed for roles assign user)
                $this->objRolesAll->newQuery();
                // $this->objRolesAll->find();//load all
                $this->objRolesAll->limitNone();//no limit
                $this->objRolesAll->loadFromDB();

                //assign user
                $this->objRolesAssignUsers->newQuery();
                $this->objRolesAssignUsers->find(TSysCMSUsersRolesAssignUsers::FIELD_ROLEID, $_GET[ACTION_VARIABLE_ID]);//load all
                $this->objRolesAssignUsers->limitNone();//no limit
                $this->objRolesAssignUsers->loadFromDB();
                if (!$this->objRolesAssignUsers->isChecksumValidAllRecords()) //negate any manipulation attempts
                {
                    logAccess(__FILE__.__LINE__,'Possible intrusion detected. One of the checksums in TSysCMSUsersRolesAssignUsers FAILED');
                    error_log(__FILE__.__LINE__.' Possible intrusion detected. One of the checksums in TSysCMSUsersRolesAssignUsers FAILED');
                    sendMessageError(transm($this->getModule(), 'errormessage_possibleintrusiondetected', 'Possible intrusion detected via Assign Users To Roles. All permissions for role retracted'));

                    $this->objRolesAssignUsers->newQuery();
                    $this->objRolesAssignUsers->find(TSysCMSUsersRolesAssignUsers::FIELD_ROLEID, $this->getModel()->getID());
                    $this->objRolesAssignUsers->deleteFromDB(true);                    
                    $this->objRolesAssignUsers->clear();
                }


            
                //permissions
                $this->objPermissions->newQuery();
                $this->objPermissions->find(TSysCMSPermissions::FIELD_USERROLEID, $_GET[ACTION_VARIABLE_ID]);
                $this->objPermissions->limitNone(0);//no limit
                $this->objPermissions->loadFromDB();
                
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
        //if groupname not unique
        if ($this->getModel()->isGroupnameTakenDB($this->objEdtGroupname->getContentsSubmitted()->getValueAsString()))
        {
            sendMessageError(transm($this->getModule(), 'message_usergroupnamenotunique', 'User group NOT SAVED, choose another group name'));//don't give a reason due to security reasons         
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
    public function onSavePost($bWasSaveSuccesful)
    {
        if ($bWasSaveSuccesful)
        {
            //create permissions when new group, 
            //because we only have an id AFTER saving the record
            if (!isset($_GET[ACTION_VARIABLE_ID])) //if it is a new record (don't use the model, because after save the getNew is reset)
            {
                $objPerm = new TSysCMSPermissions();

                if (!$objPerm->createPermissionsForUsergroup($this->getModel()->getID()))
                {
                    sendMessageError(transm($this->getModule(), 'message_creatingpermissions_failed', 'creating permissions for user-group failed'));
                    return false;
                }
                unset($objPerm);
            }
            else  
            {                
                //save permissions
                //by loading from the database we prevent that someone adds a field in the html-form to spoof the permissions system and gain unauthorised access
                /*
                $objPerm = new TSysCMSPermissions();
                $objPerm->find(TSysCMSPermissions::FIELD_USERROLEID, $_GET[ACTION_VARIABLE_ID]);
                $objPerm->limit(0);
                $objPerm->loadFromDB();   
                $sVarName = '';


                while($objPerm->next())
                {
                    $sVarName = $this->resourceToVariablename($objPerm->getResource());
                    if (isset($_POST[$sVarName]))
                    {
                        if ($_POST[$sVarName ] == '1')
                        {
                            $objPerm->setAllowed(true);
                        }
                        else //everything else than 1
                        {
                            $objPerm->setAllowed(false);                     
                        }
                    }
                    else
                    {
                        $objPerm->setAllowed(false);
                    }
                }

                $objPerm->saveToDBAll();
                unset($objPerm);
                */
            }

            //force reload of permissions for all users in the usergroup
            $objUsers = new TSysCMSUsers();
            $objUsers->find(TSysCMSUsers::FIELD_USERROLEID, $this->getModel()->getID());
            $objUsers->loadFromDB();
            while ($objUsers->next())
            {
                $objUsers->setUpdatePermissions(true);
            }
            $objUsers->saveToDBAll(true);
        
            return true;
        }




        
        return true;
    }
    
    /**
     * is called when this controller is created,
     * so you can instantiate classes or initiate values for example 
     */
    public function onCreate() 
    {
        $this->objRolesAll = new TSysCMSUsersRoles();
        $this->objRolesAssignUsers = new TSysCMSUsersRolesAssignUsers();
        $this->objPermissions = new TSysCMSPermissions();
    }  

    private function resourceToVariablename($sResource)
    {
        return str_replace(' ', '-', $sResource);
    }

    private function roleIDToVariablename($iRoleID)
    {
        return 'chkAssignUsersRoleID_'.$iRoleID;
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
     * returns a new model object
     *
     * @return TModel
     */
    public function getNewModel()
    {
        return new TSysCMSUsersRoles();
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
        return 'list_usersroles';
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
            return transm($sCurrentModule, 'pagetitle_detailsave_usersgroups_new', 'Create new role');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_usersgroups_edit', 'Edit role: [role]', 'role', $this->getModel()->getRoleName());           
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
