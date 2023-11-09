<?php
namespace dr\modules\Mod_Sys_Localisation\controllers;

use dr\classes\models\TSysCountries;
use dr\classes\controllers\TCRUDListController;
use dr\modules\Mod_Sys_Localisation\Mod_Sys_Localisation;



include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class list_countries extends TCRUDListController
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
            TSysCountries::FIELD_ID, 
            TSysCountries::FIELD_COUNTRYNAME,
            TSysCountries::FIELD_ISO2,
            TSysCountries::FIELD_ISO3,
            TSysCountries::FIELD_ISEUROPEANUNION,            
            TSysCountries::FIELD_ISSYSTEMDEFAULT
                                    ));
      
        $this->executeDB();
      
        //===show what?
        $arrTableColumnsShow = array(
            array('', TSysCountries::FIELD_COUNTRYNAME, transm($sCurrentModule, 'countries_overview_column_'.TSysCountries::FIELD_COUNTRYNAME, 'Country')),
            array('', TSysCountries::FIELD_ISO2, transm($sCurrentModule, 'countries_overview_column_'.TSysCountries::FIELD_ISO2, 'ISO 2')),
            array('', TSysCountries::FIELD_ISO3, transm($sCurrentModule, 'countries_overview_column_'.TSysCountries::FIELD_ISO3, 'ISO 3')),
            array('', TSysCountries::FIELD_ISEUROPEANUNION, transm($sCurrentModule, 'countries_overview_column_'.TSysCountries::FIELD_ISEUROPEANUNION, 'In EU')),
            array('', TSysCountries::FIELD_ISSYSTEMDEFAULT, transm($sCurrentModule, 'countries_overview_column_'.TSysCountries::FIELD_ISSYSTEMDEFAULT, 'Default'))
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
        return new TSysCountries();
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
        return Mod_Sys_Localisation::PERM_CAT_COUNTRIES;
    }

     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    public function getDetailPageURL()
    {
        return 'detailsave_countries';
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
        return transm($sCurrentModule, TRANS_MODULENAME_TITLE.'_countries', 'All system Countries');
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