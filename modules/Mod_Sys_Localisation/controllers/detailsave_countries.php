<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Sys_Localisation\controllers;

use dr\classes\models\TModel;
use dr\classes\controllers\TCRUDDetailSaveController;

use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Required;

//don't forget ;)
use dr\classes\models\TSysCountries;
use dr\modules\Mod_Sys_Localisation\Mod_Sys_Localisation;


include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');

/**
 * Description of TCRUDDetailSaveLanguages
 *
 * @author drenirie
 */
class detailsave_countries extends TCRUDDetailSaveController
{   
    private $objEdtCountry = null;//dr\classes\dom\tag\form\InputText
    private $objEdtISO2 = null;//dr\classes\dom\tag\form\InputText
    private $objEdtISO3 = null;//dr\classes\dom\tag\form\InputText
    private $objChkInEU = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objChkDefault = null;//dr\classes\dom\tag\form\InputCheckbox

    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
            //country
        $this->objEdtCountry = new InputText();
        $this->objEdtCountry->setNameAndID('edtCountry');
        $this->objEdtCountry->setClass('fullwidthtag');         
        $this->objEdtCountry->setRequired(true);   
        $this->objEdtCountry->setMaxLength(100);                
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtCountry->addValidator($objValidator);    
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtCountry->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtCountry, '', transm($this->getModule(), 'form_field_country', 'country'));

            //ISO2
        $this->objEdtISO2 = new InputText();
        $this->objEdtISO2->setNameAndID('edtISO2');
        $this->objEdtISO2->setClass('fullwidthtag');                 
        $this->objEdtISO2->setRequired(true); 
        $this->objEdtISO2->setMaxLength(2);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '2'), 2);
        $this->objEdtISO2->addValidator($objValidator);  
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtISO2->addValidator($objValidator);       
        $this->getFormGenerator()->add($this->objEdtISO2, '', transm($this->getModule(), 'form_field_iso2', 'alpha iso2 code')); 

            //ISO3
        $this->objEdtISO3 = new InputText();
        $this->objEdtISO3->setNameAndID('edtISO3');
        $this->objEdtISO3->setClass('fullwidthtag');                 
        $this->objEdtISO3->setRequired(true); 
        $this->objEdtISO3->setMaxLength(3);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '3'), 3);
        $this->objEdtISO3->addValidator($objValidator);  
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtISO3->addValidator($objValidator);       
        $this->getFormGenerator()->add($this->objEdtISO3, '', transm($this->getModule(), 'form_field_iso3', 'alpha iso3 code')); 
    

            //is in european union
        $this->objChkInEU = new InputCheckbox();
        $this->objChkInEU->setNameAndID('chkEU');
        $this->getFormGenerator()->add($this->objChkInEU, '', transm($this->getModule(), 'form_field_isineuropeanunion', 'in European Union'));           
        
            //is default
        $this->objChkDefault = new InputCheckbox();
        $this->objChkDefault->setNameAndID('chkDefault');
        $this->getFormGenerator()->add($this->objChkDefault, '', transm($this->getModule(), 'form_field_issystemdefault', 'Is system default'));           
    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_Localisation::PERM_CAT_COUNTRIES;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
        $this->getModel()->set(TSysCountries::FIELD_COUNTRYNAME, $this->objEdtCountry->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysCountries::FIELD_ISO2, $this->objEdtISO2->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysCountries::FIELD_ISO3, $this->objEdtISO3->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysCountries::FIELD_ISEUROPEANUNION, $this->objChkInEU->getContentsSubmitted()->getValueAsBool());                
        $this->getModel()->set(TSysCountries::FIELD_ISSYSTEMDEFAULT, $this->objChkDefault->getContentsSubmitted()->getValueAsBool());                
    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {         
        $this->objEdtCountry->setValue($this->getModel()->get(TSysCountries::FIELD_COUNTRYNAME));
        $this->objEdtISO2->setValue($this->getModel()->get(TSysCountries::FIELD_ISO2));
        $this->objEdtISO3->setValue($this->getModel()->get(TSysCountries::FIELD_ISO3));
        $this->objChkInEU->setChecked($this->getModel()->get(TSysCountries::FIELD_ISEUROPEANUNION));
        $this->objChkDefault->setChecked($this->getModel()->get(TSysCountries::FIELD_ISSYSTEMDEFAULT));
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
    public function onSavePost($bWasSaveSuccesful){}
    
    
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
        return true;
    }    



   /**
     * returns a new model object
     *
     * @return TModel
     */
    public function getNewModel()
    {
        return new TSysCountries(); 
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
        return 'list_countries';
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
            return transm($sCurrentModule, 'pagetitle_detailsave_countries_new', 'Create new country');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_countries_edit', 'Edit country: [country]', 'country', $this->getModel()->getCountryName());           
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
