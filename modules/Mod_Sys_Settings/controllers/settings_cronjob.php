<?php

/**
 * cronjob
 */

namespace dr\modules\Mod_Sys_Settings\controllers;

use dr\classes\controllers\TCacheControllerAbstract;
use dr\classes\controllers\TControllerAbstract;
use dr\classes\models\TModel;
use dr\classes\controllers\TCRUDDetailSaveController;

use dr\classes\dom\tag\form\Form;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\form\InputNumber;
use dr\classes\dom\tag\form\InputPassword;
use dr\classes\dom\tag\Li;
use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\form\Option;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Onlynumeric;
use dr\classes\dom\validator\Required;
use dr\classes\dom\validator\Emailaddress;

//don't forget ;)
use dr\classes\models\TSysSettings;
use dr\classes\patterns\TModuleAbstract;
use dr\classes\models\TSysCMSUsers;
use dr\classes\models\TSysCMSUsersSessions;
use dr\classes\models\TSysCMSUsersRoles;
use dr\modules\Mod_Sys_Settings\Mod_Sys_Settings;


include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


/**
 * Description of settings cronjob
 *
 * @author drenirie
 */
class settings_cronjob extends TControllerAbstract
{    
    public function __construct()
    {
        global $sCurrentModule;

        //handle authentication    
        if (!auth($sCurrentModule, Mod_Sys_Settings::PERM_CAT_CRONJOB, Mod_Sys_Settings::PERM_OP_VIEW))
        {
            showAccessDenied(transm($sCurrentModule, 'message_cronjob_notallowed', 'you are not allowed to view the cronjob tab'));
            die();
        }

        parent::__construct();
    }


    /**
     * This function adds EARLY BINDING variables which are cached
     * (see description on top of this class for more info)
     * 
     * executes the things you want to cache
     * this function is ONLY called on a cache miss 
     * (if caching enabled, if NOT enabled it's ALWAYS called).
     * This function generates content for the cache file and for displaying on-screen
     * 
     * this function is executed BEFORE executeLateBinding(), because it's early binding
     * 
     * @return array with variables, use: "return get_defined_vars();" to use all variables declared in the execute() function
     */
    public function executeEarlyBinding()
    {
        global $objCurrentModule;
        global $sCurrentModule;

        $sTitle = transm($sCurrentModule, 'pagetitle_settingscronjob', 'Cronjob');   
        $sHTMLTitle = $sTitle;
        $sHTMLMetaDescription = $sTitle;    
        $arrTabsheets = $objCurrentModule->getTabsheets(); 

        return get_defined_vars();
    }

    /**
     * This function adds LATE BINDING variables which are NOT cached 
     * (for more info: see description on top of this class)
     * 
     * executes the things you always want to execute, even on a cache miss
     * executeEarlyBinding() is executed first, then executeLateBinding()
     *  
     * These variables that aren't resolved by php in the cache file
     * This way you can add dynamic php code to an otherwise cached page
     * 
     * These late binding variables need to be in the following format in the template: [variablename]
     * (Otherwise PHP will resolve variables in thecachefile with the format: $variablename)
     * 
     * This function is executed AFTER executeEarlyBinding()
     * 
     * @return array with variables, use: "return get_defined_vars();" to use all variables declared in the execute() function
     */
    public function executeLateBinding()
    {
        return;
    }


    /**
     * return path of the page template
     *
     * @return string
     */
    public function getTemplatePath()
    {
        global $sCurrentModule;
        return GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$sCurrentModule.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpl_settings_cronjob.php';
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



}
