<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Sys_Websites\controllers;

use dr\classes\models\TModel;
use dr\classes\controllers\TCRUDDetailSaveController;

use dr\classes\dom\tag\form\Form;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\Li;
use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\form\Option;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Required;

//don't forget ;)
use dr\classes\models\TSysWebsites;
use dr\modules\Mod_Sys_Websites\Mod_Sys_Websites;

include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');

/**
 * Description of TCRUDDetailSaveLanguages
 *
 * @author drenirie
 */
class detailsave_websites extends TCRUDDetailSaveController
{
    private $objEdtWebsiteName = null;//dr\classes\dom\tag\form\InputText
    private $objEdtURL = null;//dr\classes\dom\tag\form\InputText
    private $objOptDefaultLanguage = null;//dr\classes\dom\tag\form\Select     
        
    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
            //website name
        $this->objEdtWebsiteName = new InputText();
        $this->objEdtWebsiteName->setNameAndID('edtWebsiteName');
        $this->objEdtWebsiteName->setClass('fullwidthtag');         
        $this->objEdtWebsiteName->setRequired(true);   
        $this->objEdtWebsiteName->setMaxLength(100);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtWebsiteName->addValidator($objValidator);    
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtWebsiteName->addValidator($objValidator);    
        $this->getForm()->add($this->objEdtWebsiteName, '', transm($this->getModule(), 'form_field_websitename', 'website name'));

            //url
        $this->objEdtURL = new InputText();
        $this->objEdtURL->setNameAndID('edtLanguage');
        $this->objEdtURL->setClass('fullwidthtag');                 
        $this->objEdtURL->setRequired(true); 
        $this->objEdtURL->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtURL->addValidator($objValidator);        
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtURL->addValidator($objValidator);       
        $this->getForm()->add($this->objEdtURL, '', transm($this->getModule(), 'form_field_url', 'url')); 
        
        //language
        $this->objOptDefaultLanguage = new Select();
        $this->objOptDefaultLanguage->setNameAndID('optDefaultLanguage');
        $this->getForm()->add($this->objOptDefaultLanguage, '', transm($this->getModule(), 'form_field_defaultlanguage', 'default language'));        

    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_Websites::PERM_CAT_WEBSITES;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
        $this->getModel()->set(TSysWebsites::FIELD_WEBSITENAME, $this->objEdtWebsiteName->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysWebsites::FIELD_URL, $this->objEdtURL->getContentsSubmitted()->getValueAsString());
        //language
        $this->getModel()->set(TSysWebsites::FIELD_DEFAULTLANGUAGEID, $this->objOptDefaultLanguage->getContentsSubmitted()->getValueAsInt());
        
    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {  
        $this->objEdtWebsiteName->setValue($this->getModel()->get(TSysWebsites::FIELD_WEBSITENAME));
        $this->objEdtURL->setValue($this->getModel()->get(TSysWebsites::FIELD_URL));
        
        //language
        $objLangs = new \dr\classes\models\TSysLanguages();
        $objLangs->sort(\dr\classes\models\TSysLanguages::FIELD_LANGUAGE);
        $objLangs->loadFromDBByCMSShown();
        $objLangs->generateHTMLSelect($this->getModel()->get(TSysWebsites::FIELD_DEFAULTLANGUAGEID), $this->objOptDefaultLanguage);
        
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
     */
    public function onSavePost($bWasSaveSuccesful)
    {
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
        return true;
    }    


   /**
     * returns a new model object
     *
     * @return TModel
     */
    public function getNewModel()
    {
        return new TSysWebsites(); 
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
        return 'list_websites';
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
            return transm($sCurrentModule, 'pagetitle_detailsave_websites_new', 'Create new website');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_websites_edit', 'Edit website: [website]', 'website', $this->getModel()->getWebsiteName());           
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
