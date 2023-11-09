<?php
namespace dr\modules\Support\controllers;

use dr\classes\controllers\TContactFormController;
use dr\classes\controllers\TControllerAbstract;
use dr\classes\patterns\TContactForm;

include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');


class supportcontactform extends TContactFormController
{
    public function __construct()
    {
        $this->setHyperlinksAllowed(true);//hyperlinks are allowed in a support form
        $this->setRecaptchaV3Use(true); //form doesn't exist yet  

        parent::__construct();//renders controller
    }


    /**
     * categories topics
     */
    public function getCategories()
    {
        $arrReturn = array();
        $arrReturn[] = transw('contactform_productsupport', 'Product support');
        $arrReturn[] = transw('contactform_requestnewfeatures', 'Request new features');
        $arrReturn[] = transw('contactform_marketingandsales', 'Marketing and sales');
        $arrReturn[] = transw('contactform_legal', 'Legal matter');
        $arrReturn[] = transw('contactform_reportanissue', 'Report an issue');
        $arrReturn[] = transw('contactform_pricing', 'Pricing');
        $arrReturn[] = transw('contactform_other', 'Other');
        return $arrReturn;
    }

    public function populate()
    {
        
    }


    /**
     * This function adds EARLY BINDING variables to template, which are cached (if cache enabled)
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
        $sTitle = transw('supportcontactform_title', 'Contact Us');
        $sHTMLTitle = transw('supportcontactform_htmltitle', 'Contact Us');
        $sHTMLMetaDescription = transw('supportcontactform_metadescription', 'Please fill out the form to contact us');
        $objForm = $this->objForm;

        return get_defined_vars();
    }

    /**
     * This function adds LATE BINDING variables to template which are NOT cached 
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
    public function executeLateBinding() {}    

    /**
     * return path of the page template
     *
     * @return string
     */
    public function getTemplatePath()
    {
        global $sCurrentModule;
        return GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$sCurrentModule.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpl_contactform.php';
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
     * return path of the template for the email message
     *
     * @return string
     */
    public function getPathEmailTemplate()
    {
        global $sCurrentModule;
        return GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$sCurrentModule.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpl_emailmessage.php';
    }
  
    /**
     * return path of the template for the email message
     *
     * @return string
     */
    public function getPathEmailSkin()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_email.php';        
    }    

    /**
     * display error message when form not succesfully sent
     *
     * @return string
     */
    public function sendErrorMessage($sError)
    {
        sendMessageError($sError);
        // vardumpdie($sError, 'nsoekielp');
    }

    /**
     * display message when form succesfully sent
     *
     * @return string
     */
    public function sendSuccessMessage($sMessage)
    {
        sendMessageSuccess($sMessage);
        // vardumpdie($sError, 'nsoekielp');
    }


}