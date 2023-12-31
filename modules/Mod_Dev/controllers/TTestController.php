<?php
namespace dr\modules\Mod_Dev\controllers;

use dr\classes\controllers\TControllerAbstract;


include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');

// include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_cms.php');



class TTestController extends TControllerAbstract
{
    
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
        global $arrTabsheets;    
        
        $sTestVar = 'blaat';
        $sTitle = 'title';
        $sHTMLTitle = 'htmltitle';
        $sHTMLMetaDescription  = 'metadescription';        

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
        $sLateBinding = 'hoest';
        return get_defined_vars();
    }


    /**
     * return path of the page template
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.'Mod_Dev'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpl_test.php';
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

    public function getCacheTimeOutSeconds()
    {
        return 0;
    }

 
    public function getCacheFilePath()
    {
        return GLOBAL_PATH_LOCAL_CMS_CACHE.DIRECTORY_SEPARATOR.'cachefilefromtestcontroller2.html';
    }

    public function getCacheLocation()
    {
        return TControllerAbstract::CACHELOCATION_FILE;
    }


}


?>

