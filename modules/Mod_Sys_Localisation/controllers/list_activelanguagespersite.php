<?php
namespace dr\modules\Mod_Sys_Localisation\controllers;

use dr\classes\controllers\TCRUDListController;
use dr\classes\models\TModel;
use dr\classes\models\TSysActiveLanguagesPerSite;
use dr\classes\models\TSysLanguages;
use dr\classes\models\TSysWebsites;
use dr\modules\Mod_Sys_Localisation\Mod_Sys_Localisation;



include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class list_activelanguagespersite extends TCRUDListController
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
        $objTempLang = new TSysLanguages();  
        $objTempSites = new TSysWebsites();    
        $objModel->select(array(
            TModel::FIELD_ID,
            TSysActiveLanguagesPerSite::FIELD_WEBSITEID,
            TSysActiveLanguagesPerSite::FIELD_LANGUAGEID,
            TModel::FIELD_CHECKOUTEXPIRES,
            TModel::FIELD_CHECKOUTSOURCE,
            TModel::FIELD_LOCKED,
            TModel::FIELD_LOCKEDSOURCE
                ));
        $objModel->select(array(TSysLanguages::FIELD_LANGUAGE, TSysLanguages::FIELD_LOCALE), $objTempLang);
        $objModel->select(array(TSysWebsites::FIELD_WEBSITENAME), $objTempSites);    
        $objModel->join('', TSysActiveLanguagesPerSite::FIELD_WEBSITEID, TSysWebsites::getTable(), TSysWebsites::FIELD_ID);
        $objModel->join('', TSysActiveLanguagesPerSite::FIELD_LANGUAGEID, TSysLanguages::getTable(), TSysLanguages::FIELD_ID);
                

        $this->executeDB(false);
                            
        //===show what?
        $arrTableColumnsShow = array(
            array($objTempSites::getTable(), TSysWebsites::FIELD_WEBSITENAME, transm($sCurrentModule, 'overview_column_'.TSysWebsites::FIELD_WEBSITENAME, 'website')),
            array($objTempLang::getTable(), TSysLanguages::FIELD_LANGUAGE, transm($sCurrentModule, 'overview_column_'.TSysLanguages::FIELD_LANGUAGE, 'language')),
            array($objTempLang::getTable(), TSysLanguages::FIELD_LOCALE, transm($sCurrentModule, 'overview_column_'.TSysLanguages::FIELD_LOCALE, 'locale')),
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
        return new TSysActiveLanguagesPerSite();
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
        return Mod_Sys_Localisation::PERM_CAT_ACTIVELANGUAGES;
    }
  
     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    public function getDetailPageURL()
    {
        return 'detailsave_activelanguagespersite';
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
        return transm($sCurrentModule, TRANS_MODULENAME_TITLE.'_languagespersite', 'Languages on sites');
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