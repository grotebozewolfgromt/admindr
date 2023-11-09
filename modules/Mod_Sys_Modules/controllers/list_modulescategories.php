<?php
namespace dr\modules\Mod_Sys_Modules\controllers;

use dr\classes\controllers\TCRUDListController;
use dr\classes\models\TSysModulesCategories;
use dr\modules\Mod_Sys_Modules\Mod_Sys_Modules;

include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class list_modulescategories extends TCRUDListController
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
            TSysModulesCategories::FIELD_ID,  
            TSysModulesCategories::FIELD_NAME, 
            TSysModulesCategories::FIELD_CHECKOUTEXPIRES, 
            TSysModulesCategories::FIELD_CHECKOUTSOURCE, 
            TSysModulesCategories::FIELD_LOCKED,
            TSysModulesCategories::FIELD_ORDER,
            TSysModulesCategories::FIELD_LOCKEDSOURCE));
    
       
        $this->executeDB(true);
         
        
        //===show what?
        $arrTableColumnsShow = array(
            array('', TSysModulesCategories::FIELD_NAME, transm($sCurrentModule, 'overview_column_'.TSysModulesCategories::FIELD_NAME, 'name')),
            array('', TSysModulesCategories::FIELD_ORDER, transm($sCurrentModule, 'overview_column_'.TSysModulesCategories::FIELD_ORDER, 'order'))
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
        return new TSysModulesCategories();
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
        return Mod_Sys_Modules::PERM_CAT_MODULECATEGORIES;
    }

     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    public function getDetailPageURL()
    {
        return 'detailsave_modulescategories';
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
        return transm($sCurrentModule, TRANS_MODULENAME_TITLE, 'modules');
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