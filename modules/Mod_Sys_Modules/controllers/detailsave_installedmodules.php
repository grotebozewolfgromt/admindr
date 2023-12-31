<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Sys_Modules\controllers;

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
use dr\classes\models\TSysModules;
use dr\modules\Mod_Sys_Modules\Mod_Sys_Modules;


include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');



/**
 * Description of TCRUDDetailSaveLanguages
 *
 * @author drenirie
 */
class detailsave_installedmodules extends TCRUDDetailSaveController
{
    private $objEdtName = null;//dr\classes\dom\tag\form\InputText --> only for consistency, it's a readonly editbox
    private $objOptCategory = null;//dr\classes\dom\tag\form\Select
    private $objChkVisible = null;//dr\classes\dom\tag\form\InputCheckbox
    
        
    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
            //module name
        $this->objEdtName = new InputText();
        $this->objEdtName->setNameAndID('edtName');
        $this->objEdtName->setClass('fullwidthtag');                         
        $this->objEdtName->setReadOnly(true);
        $this->getFormGenerator()->add($this->objEdtName, '', transm($this->getModule(), 'form_field_internalname', 'internal name (read-only)'));
        
            //category
        $this->objOptCategory = new Select();
        $this->objOptCategory->setNameAndID('optCategory');
        $this->getFormGenerator()->add($this->objOptCategory, '', transm($this->getModule(), 'form_field_category', 'category'));

            //is visible
        $this->objChkVisible = new InputCheckbox();
        $this->objChkVisible->setNameAndID('chkVisible');
        $this->getFormGenerator()->add($this->objChkVisible, '', transm($this->getModule(), 'form_field_isvisible', 'is visible (in menus etc)'));   
    
    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_Modules::PERM_CAT_MODULESINSTALLED;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
//        $this->getModel()->set(TSysModules::FIELD_NAMEINTERNAL, $this->objEdtName->getContentsSubmitted()->getValueAsString()); --> read only editbox
        $this->getModel()->set(TSysModules::FIELD_MODULECATEGORYID, $this->objOptCategory->getContentsSubmitted()->getValueAsInt());
        $this->getModel()->set(TSysModules::FIELD_VISIBLE, $this->objChkVisible->getContentsSubmitted()->getValueAsBool());                        
    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {  
        //name
        $this->objEdtName->setValue($this->getModel()->get(TSysModules::FIELD_NAMEINTERNAL));
                
        //categories
        $objCategories = new \dr\classes\models\TSysModulesCategories();
        $objCategories->loadFromDB();
        $objCategories->generateHTMLSelect($this->getModel()->get(TSysModules::FIELD_MODULECATEGORYID), $this->objOptCategory);

        //visible
        $this->objChkVisible->setChecked($this->getModel()->get(TSysModules::FIELD_VISIBLE));
                    
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
    public function onSavePost($bWasSaveSuccesful){ return true; }

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
        return new TSysModules(); 
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
        return 'list_installedmodules';
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
            return transm($sCurrentModule, 'pagetitle_detailsave_installedmodules_new', 'Create new module');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_installedmodules_edit', 'Edit module: [modulename]', 'modulename', $this->getModel()->getNameInternal());           
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
