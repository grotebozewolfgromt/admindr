<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Sys_Localisation\controllers;

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
use dr\classes\models\TSysLanguages;
use dr\classes\models\TSysWebsites;
use dr\classes\models\TSysActiveLanguagesPerSite;
use dr\modules\Mod_Sys_Localisation\Mod_Sys_Localisation;


include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');

/**
 * Description of TCRUDDetailSaveLanguages
 *
 * @author drenirie
 */
class detailsave_activelanguagespersite extends TCRUDDetailSaveController
{
    
    private $objOptWebsite = null;//dr\classes\dom\tag\form\Option
    private $objOptLanguage = null;//dr\classes\dom\tag\form\Option
    
    private $objWebsites = null;//TSysWebsites
    private $objLanguages = null;//TSysLanguages
    
        
    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
        //website
        $this->objOptWebsite = new Select();
        $this->objOptWebsite->setNameAndID('optWebsite');
        $this->getFormGenerator()->add($this->objOptWebsite, '', transm($this->getModule(), 'form_field_website', 'website'));

        //language
        $this->objOptLanguage = new Select();
        $this->objOptLanguage->setNameAndID('optLanguage');
        $this->getFormGenerator()->add($this->objOptLanguage, '', transm($this->getModule(), 'form_field_language', 'language (missing languages? go to: "all languages", "edit" and check: "shown-in-cms")'));
    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_Localisation::PERM_CAT_ACTIVELANGUAGES;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
        //website
        $this->getModel()->set(TSysActiveLanguagesPerSite::FIELD_WEBSITEID, $this->objOptWebsite->getContentsSubmitted()->getValueAsInt());        

        //language
        $this->getModel()->set(TSysActiveLanguagesPerSite::FIELD_LANGUAGEID, $this->objOptLanguage->getContentsSubmitted()->getValueAsInt());        
    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {  
        //websites
        $objSites = new TSysWebsites();
        $objSites->sort(TSysWebsites::FIELD_WEBSITENAME);
        $objSites->loadFromDB();
        $objSites->generateHTMLSelect($this->getModel()->get(TSysActiveLanguagesPerSite::FIELD_WEBSITEID), $this->objOptWebsite);        

        //language
        $objLangs = new TSysLanguages();
        $objLangs->sort(TSysLanguages::FIELD_LANGUAGE);
        $objLangs->loadFromDBByCMSShown();
        $objLangs->generateHTMLSelect($this->getModel()->get(TSysActiveLanguagesPerSite::FIELD_LANGUAGEID), $this->objOptLanguage);        
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
        $this->objWebsites = new TSysWebsites();
        $this->objLanguages = new TSysLanguages();
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
        return new TSysActiveLanguagesPerSite();
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
        return 'list_activelanguagespersite';
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
            return transm($sCurrentModule, 'pagetitle_detailsave_activelanguagespersite_new', 'Create new language for a site');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_activelanguagespersite_edit', 'Edit site language');           
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
