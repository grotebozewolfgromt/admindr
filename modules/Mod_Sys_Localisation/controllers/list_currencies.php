<?php
namespace dr\modules\Mod_Sys_Localisation\controllers;

use dr\classes\models\TSysCurrencies;
use dr\classes\controllers\TCRUDListController;
use dr\classes\models\TModel;
use dr\modules\Mod_Sys_Localisation\Mod_Sys_Localisation;



include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class list_currencies extends TCRUDListController
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
            TSysCurrencies::FIELD_ID, 
            TSysCurrencies::FIELD_CURRENCYNAME,
            TSysCurrencies::FIELD_CURRENCYSYMBOL,
            TSysCurrencies::FIELD_ISOALPHABETIC,
            TSysCurrencies::FIELD_ISONUMERIC,            
            TSysCurrencies::FIELD_DECIMALPRECISION,            
            TSysCurrencies::FIELD_ISVISIBLE,
            TSysCurrencies::FIELD_ISSYSTEMDEFAULT,
            TModel::FIELD_ORDER
                                    ));
      
        $this->executeDB();
      
        //===show what?
        $arrTableColumnsShow = array(
            array('', TSysCurrencies::FIELD_CURRENCYNAME, transm($sCurrentModule, 'currencies_overview_column_'.TSysCurrencies::FIELD_CURRENCYNAME, 'Currency')),
            array('', TSysCurrencies::FIELD_CURRENCYSYMBOL, transm($sCurrentModule, 'currencies_overview_column_'.TSysCurrencies::FIELD_CURRENCYSYMBOL, 'Symbol')),
            array('', TSysCurrencies::FIELD_ISOALPHABETIC, transm($sCurrentModule, 'currencies_overview_column_'.TSysCurrencies::FIELD_ISOALPHABETIC, 'ISO A')),
            array('', TSysCurrencies::FIELD_ISONUMERIC, transm($sCurrentModule, 'currencies_overview_column_'.TSysCurrencies::FIELD_ISONUMERIC, 'ISO N')),
            array('', TSysCurrencies::FIELD_DECIMALPRECISION, transm($sCurrentModule, 'currencies_overview_column_'.TSysCurrencies::FIELD_DECIMALPRECISION, 'Dec')),
            array('', TSysCurrencies::FIELD_ISVISIBLE, transm($sCurrentModule, 'currencies_overview_column_'.TSysCurrencies::FIELD_ISVISIBLE, 'Visible')),
            array('', TSysCurrencies::FIELD_ISSYSTEMDEFAULT, transm($sCurrentModule, 'currencies_overview_column_'.TSysCurrencies::FIELD_ISSYSTEMDEFAULT, 'Default')),
            array('', TSysCurrencies::FIELD_ORDER, transm($sCurrentModule, 'currencies_overview_column_'.TModel::FIELD_ORDER, 'Order'))
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
        return new TSysCurrencies();
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
        return Mod_Sys_Localisation::PERM_CAT_CURRENCIES;
    }

     /**
     * returns the url for the detailpage for the browser to go to
     *
     * @return string
     */
    public function getDetailPageURL()
    {
        return 'detailsave_currencies';
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
        return transm($sCurrentModule, TRANS_MODULENAME_TITLE.'_currencies', 'All system currencies');
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