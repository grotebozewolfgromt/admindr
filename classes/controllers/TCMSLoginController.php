<?php



namespace dr\classes\controllers;

use dr\classes\models\TSysCMSPermissions;
use dr\classes\models\TUsersAbstract;
use dr\classes\models\TModel;

use dr\classes\dom\FormGenerator;    
use dr\classes\dom\tag\form\Form;
use dr\classes\dom\tag\form\InputSubmit;
use dr\classes\dom\tag\form\InputButton;
use dr\classes\dom\tag\form\InputPassword;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\form\InputEmail;
use dr\classes\dom\tag\form\InputHidden;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\Li;
use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\form\Option;
use dr\classes\dom\validator\Emailaddress;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Required;
use dr\classes\locale\TCountrySettings;
use dr\classes\mail\TMailSend;
use dr\classes\models\TSysCMSUserAccounts;
use dr\classes\models\TSysCMSUsers;
use dr\classes\models\TSysContacts;
use dr\classes\models\TSysCountries;
use dr\classes\types\TDateTime;

/**
 * Description of TCMSLoginController
 *
 * 4 nov 2020 : TCMSLoginController->populatePermissionsSessionArray(), checks for existence of users and empty's permission array (otherwise it requests a usergroup that doesn;t exist) 
 * @author drenirie
 */
class TCMSLoginController extends TLoginControllerAbstract
{
    private $objSelLanguage = null;//dr\classes\dom\tag\form\Select
    
    /**
     * unique class login ID to prevent 2 (or more) login classes on the same site 
     * using the same variables for usernames,passwords etc.
     * 
     * return a constant string that is always the same to identify this class,
     * for example 'cms' or 'webshop'
     * DO NOT GENERATE with uniqid() then this class won't authenticate!!!!!!!!!!!
     * 
     * @return string
     */
    public function getControllerID()
    {
        return 'cms5';
    }
    
    /**
     * what is the url of the loginform?
     * use full urls with https in front (no relative urls)!
     * with relative urls it wil redirect to the relative script which 
     * can be non-existing if not in root directory!
     * 
     * for example for a cms it could be: https://www.mysite.com/cms/index.php
     *      
     * This class will redirect logouts and login-fails to this url.    
     * 
     * DO NOT USE getURLThisScript()! because it depends on the script that 
     * calls this class, this class does header redirects, the script will change
     * at it will call itself indefinitely
     * 
     * @return string
     */
    public function getURLLoginForm()
    {
        return getURLCMSLogin();
    }
    
    /**
     * what url do you want to forward if a login is succesful?
     * use full urls with https in front (no relative urls)!
     * with relative urls it wil redirect to the relative script which 
     * can be non-existing if not in root directory!
     * 
     * for example for a cms it could be: https://www.mysite.com/cms/home.php
     *      
     * This class will redirect successful logins to this url.
     * 
     * @return string
     */
    public function getURLLoginSuccess()
    {
        return getURLCMSDashboard();
    }

   /**
     * what url do you want to forward if a login is succesful?
     * BUT THE USER NEEDS TO CHANGE HIS/HER PASSWORD
     * 
     * use full urls with https in front (no relative urls)!
     * with relative urls it wil redirect to the relative script which 
     * can be non-existing if not in root directory!
     * 
     * for example for a cms it could be: https://www.mysite.com/cms/settings.php
     *      
     * This class will redirect successful logins to this url.
     * 
     * @return string
     */
    public function getURLLoginSuccessUserChangePassword()
    {
        return getURLCMSSettings();
    }    

    /**
     * what url do you want to forward to to recover a password
     * 
     * for example for a cms it could be: https://www.mysite.com/cms/passwordrecover.php
     *      
     * 
     * @return string
     */
    public function getURLPasswordRecoverEnterEmail()
    {
        return getURLPasswordRecoverEnterEmail();
    }    

    /**
     * what url do you want to forward to to recover a enter 
     * 
     * for example for a cms it could be: https://www.mysite.com/cms/passwordrecover_enterpassword.php
     *      
     * 
     * @return string
     */
    public function getURLPasswordRecoverEnterPassword()
    {
        return getURLPasswordRecoverEnterNewPassword();
    }     
    
   /**
     * the path of the email skin
     *
     * @return string
     */
    public function getPathEmailSkin()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_email.php';
    }
    
    /**
     * get path of the email password recovery email
     *
     * @return string
     */
    public function getPathEmailTemplatePasswordRecover()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_email_passwordrecover_clicktoresetpassword.php';
    }

    /**
     * get path of the email temlate to acticate account
     *
     * @return string
     */
    public function getPathEmailTemplateCreateAccountActivate()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_email_createaccount_clicktoconfirm.php';
    }

    /**
     * get path of the email emailaddress exists template
     *
     * @return string
     */
    public function getPathEmailTemplateCreateAccountEmailExists()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_email_createaccount_emailexists.php';
    }    


    /**
     * get url to create a new account
     * 
     * for example for a cms it could be: https://www.mysite.com/cms/createaccount_entercredentials.php
     *      
     * 
     * @return string
     */
    public function getURLCreateAccountEnterCredentials()
    {
        return getURLCreateAccountEnterCredentials();
    }

        
    /**
     * get a new object of 
     * @return TUsersAbstract
     */
    public function getNewUsers()
    {
        return new \dr\classes\models\TSysCMSUsers();
    }

    /**
     * get a new object of 
     * @return TUsersFloodDetectAbstract
     */
    public function getNewUsersFloodDetectModel()
    {
        return new \dr\classes\models\TSysCMSUsersFloodDetect();
    }
         
    /**
     * get a new object of 
     * @return TUsersSessionsAbstract
     */
    public function getNewUsersSessions()
    {
        return new \dr\classes\models\TSysCMSUsersSessions();
    }
    
     /**
     * disable or enable the 'keep me logged in' system via a cookie
     */
    public function getUseKeepLoggedIn()
    {
        return true;
    }
    

    /**
     * get name of the application
     * (for example: 'CMS 5')
     * This is used in emails (account activation and password reset)
     * But since this is an abstract controller wich is also used outside the cms, we need to request it
     *
     * @return string
     */
    public function getApplicationName()
    {
        return GLOBAL_CMS_APPLICATIONNAME;
    }
    

    /**
     * how many failed logins can a user have per day?
     * 
     * @return int
     */
    public function getMaxAllowedFailedLoginAttemptsPerDay()
    {
        if (GLOBAL_DEVELOPMENTENVIRONMENT)
            return 1000; //for debugging //lierelei
        
        return 3;                 
    }

    /**
     * how many times can a user log-in in a day?
     * when someone logs in 200 times a day, this smells fishy
     * 
     * @return int
     */
    public function getMaxAllowedSuccessfulLoginAttemptsPerDay()
    {        
        if (GLOBAL_DEVELOPMENTENVIRONMENT)
            return 1000; //for debugging //lierelei

        return 24; //once per hour
    }


    /**
     * how many times can a user reset a password in a day?
     * 
     * @return int
     */
    public function getMaxAllowedPasswordResetsPerDay()
    {
        if (GLOBAL_DEVELOPMENTENVIRONMENT)
            return 1000; //for debugging //lierelei

        return 2;
    }


    /**
     * how many times can a user create an account PER MONTH?
     * 
     * @return int
     */
    public function getMaxAllowedCreateAccountsPerMonth()
    {
        if (GLOBAL_DEVELOPMENTENVIRONMENT)
            return 1000; //for debugging //lierelei

        return 1;
    }

    /**
     * How many times can a user create an account under the same email address.
     * Although 1-at-a-time is ALWAYS standard, this function returns how many creates are allowed
     * in the entire history of the logs in the database.
     * (if you delete the logs once every 6 months, it is keeping track of the last 6 months).
     * 
     * for example: a user can delete an account and create a new one with the same email address.
     * How many times is allowed?
     * Too many times is very suspicious.
     * 
     * @return int
     */
    public function getMaxAllowedCreateAccountsWithSameEmail()
    {
        if (GLOBAL_DEVELOPMENTENVIRONMENT)
            return 1000; //for debugging //lierelei

        return 2;
    }  

    /**
     * how many times can the site accept an attempt PER HOUR?
     * this is all attempts: failed logins, successful logins, change password, create accounts
     * We do per day and per hour
     * 
     * this to prevents an overflow of login-type actions to cause a Denial Of Service for logged in users,
     * we rather have a Denial Of Service on logging in than the whole system going down
     * 
     * For a CMS 20 is pretty generous, 
     * but for a site like youtube 20 is pretty conservative
     * 
     * @return int
     */
    public function getMaxAllowedAttemptsPerHour()
    {
        if (GLOBAL_DEVELOPMENTENVIRONMENT)
            return 1000; //for debugging //lierelei

        return 100;
    }
        

    /**
     * how many times can the site accept an attempt PER DAY?
     * this is ALL attempts for ALL USERS combined: failed logins, successful logins, change password, create accounts
     * 
     * this to prevents an overflow of login-type actions to cause a Denial Of Service for logged in users,
     * we rather have a Denial Of Service on logging in than the whole system going down
     * 
     * For a CMS 100 is pretty generous, 
     * but for a site like youtube 100 is pretty conservative
     * 
     * @return int
     */
    public function getMaxAllowedAttemptsPerDay()
    {
        if (GLOBAL_DEVELOPMENTENVIRONMENT)
            return 2000; //for debugging //lierelei        

        return 500;
    }  

    /**
     * use google's recaptcha to login?
     * you may want to use this for a cms, but not a webshop
     * 
     * @return bool
     */
    public function getUseRecapthaLogin()
    {
        return GLOBAL_GOOGLE_RECAPTCHAV3_USE;
    }




    /**
     * you can define extra functionality when a login is succesful.
     * this function is called right before the header redirect
     */
    protected function onLoginSuccess()
    {        

        //register current website
        if ($this->getUseCookie()) //use cookie
        {
            // logDebug('onLoginSuccess() getUseCookie == true')            ;
            if (!setcookie(COOKIEARRAYKEY_SELECTEDSITEID, GLOBAL_DB_SITEID, time() + (DAY_IN_SECS * GLOBAL_COOKIE_EXPIREDAYS), '/', GLOBAL_PATH_DOMAIN, GLOBAL_ISHTTPS, true)) // 86400 = 1 day
                error_log('cookie set error: selectedsiteid: '.COOKIEARRAYKEY_SELECTEDSITEID);
        }
        else //use session
        {
            $_SESSION[SESSIONARRAYKEY_SELECTEDSITEID] = GLOBAL_DB_SITEID;
        }
 
    }
    
    /**
     * you can define extra functionality when a logging out
     */
    protected function onLogout()
    {
        //remove current website from cookie or session
        if (isset($_COOKIE[$this->getTokenIDSessionCookieKey()])) //from cookie
        {
            if (!setcookie(COOKIEARRAYKEY_SELECTEDSITEID, '', time() - DAY_IN_SECS, '/', GLOBAL_PATH_DOMAIN, GLOBAL_ISHTTPS, true))
                error_log('setcookie failed (delete cookie): selectedsiteid: '.COOKIEARRAYKEY_SELECTEDSITEID);                            
        }
        else //from session
        {
            if (isset($_SESSION[SESSIONARRAYKEY_SELECTEDSITEID]))
                unset($_SESSION[SESSIONARRAYKEY_SELECTEDSITEID]);   
        }

        //remove permissions
        if (isset($_SESSION[SESSIONARRAYKEY_PERMISSIONS]))
        {
            unset($_SESSION[SESSIONARRAYKEY_PERMISSIONS]);  
        }

        //remove settings
        if (isset($_SESSION[SESSIONARRAYKEY_SETTINGS]))
        {
            unset($_SESSION[SESSIONARRAYKEY_SETTINGS]);  
        }        

    }

    /**
     * populate $_SESSION permission array
     * (user authorisation system)
     * 
     * this function is called right after onLoginSuccess()
     * 
     * @param bool $bForceReload regenerate permissionsarray. on false only load when permissionsarray is empty (default = true)* 
     * @return bool success??
     */    
    public function populatePermissionsSessionArray($bForceReload = true)
    {
        $bLoadPSA = true; //declare boolean: Load Permissions Session Array
        $bLoadPSA = $bForceReload; //default

        if (!$bLoadPSA)
        {
            $bLoadPSA = (!isset($_SESSION[SESSIONARRAYKEY_PERMISSIONS])); //only load if session is empty
        }
        

        if ($bLoadPSA)
        {

            //logDev('populatePermissionsSessionArray() start');
            $objUsers = null;
            $objUsers = $this->getUsers();
            $arrTempPerm = array();

            if (!$objUsers)
            {
                $_SESSION[SESSIONARRAYKEY_PERMISSIONS] = array();//empty permissions array for security reasons
                return false;
            }

            if (!$objUsers->count())
            {
                $_SESSION[SESSIONARRAYKEY_PERMISSIONS] = array();//empty permissions array for security reasons
                return false;            
            }

            //get permissions from database
            $objPermissions = new TSysCMSPermissions();
            $objPermissions->select(array(TSysCMSPermissions::FIELD_RESOURCE, TSysCMSPermissions::FIELD_ALLOWED, TSysCMSPermissions::FIELD_CHECKSUMENCRYPTED, TSysCMSPermissions::FIELD_USERROLEID));
            $objPermissions->limit(0);//we want all!!!
            $objPermissions->find(TSysCMSPermissions::FIELD_USERROLEID, $objUsers->getUserRoleID());
            if (!$objPermissions->loadFromDB())
                return false;

            while ($objPermissions->next())
            {
                if ($objPermissions->isChecksumValid())//prevent tempering with database to gain access
                    $arrTempPerm[$objPermissions->getResource()] = $objPermissions->getAllowed();
                else
                    error_log('checksum permissions did not match: didn\'t add permission to permission session array');
            }
            
            $_SESSION[SESSIONARRAYKEY_PERMISSIONS] = $arrTempPerm;
        }
        return true;        
    }

   /**
     * if you have additional permissions definied, handle them in this function
     * (user authorisation system)
     * 
     * 
     * @return bool success??
     */
    protected function handleAuthenticationChild()
    {
        $bAccess = false;

        //=== module access
        global $sCurrentModule;

        if ($sCurrentModule != '') //the framework has non-module pages too (home, settings, loginform)
        {
            if (!auth($sCurrentModule, AUTH_CATEGORY_MODULEACCESS, AUTH_OPERATION_MODULEACCESS))
            {
                showAccessDenied(transcms('message_noacess_tomodule','you don\'t have access to this (part of) the module'));
                die();
                return false;                         
            }
            else
            {                
                return true;
            }

        }

        
        return $bAccess;
    }

    /**
     * the mailbot that sends account confirmations and password reset emails
     * has a from emailaddress, for example: system@example.com
     * 
     * @return string email address of the email bot
     */
    protected function getMailbotFromEmailAddress()
    {
        return getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_SYSTEMMAILBOT_FROM_EMAILADDRESS);
    }

    /**
     * the mailbot that sends account confirmations and password reset emails
     * has a name, for example John Doe in: John Doe <system@example.com>
     * 
     * @return string the name of email address of the email bot
     */
    protected function getMailbotFromName()
    {
        return getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_SYSTEMMAILBOT_FROM_NAME);
    } 


    /**
     * is anyone allowed to create an account?
     * for closed systems like a CMS we'd like to switch this off (return false)
     * but for open systems like a webshop we can enable this (return true)
     * 
     * @return bool
     */
    public function getCanAnyoneCreateAccount()
    {
        return GLOBAL_CMS_ANYONECANREGISTERACCOUNT;
    }


    /**
     * use google to sign in?
     * you may want to use this for a cms, but not a webshop
     * 
     * @return bool
     */
    public function getUseSigninWithGoogle()
    {
        return GLOBAL_CMS_ENABLESIGNINWITHGOOGLE;
    }

    /**
     * generate the form object and all the UI elements to create an account specific to this child class
     * (=username, email address, password + button are already created)
     * 
     */
    public function populateFormCreateAccountEnterCredentialsChild()
    {
            //language
        $this->objSelLanguage = new Select();
        $this->objSelLanguage->setNameAndID('selLanguage');
        $this->objSelLanguage->setClass('input_type_text');    
        $this->objFormCreateAccount->add($this->objSelLanguage, '', transw('createaccount_field_language', 'language'));
    }    
        

    /**
     * transfer database elements to form
     * 
     * (the namegiving is because of consistency with other controllers, there is not a lot of 'model' in 'modelToForm')
     */                 
    protected function modelToFormCreateAccountEnterCredentialsChild()
    {  
        //language
        $objLangs = new \dr\classes\models\TSysLanguages();
        $objLangs->sort(\dr\classes\models\TSysLanguages::FIELD_LANGUAGE);
        $objLangs->loadFromDBByCMSLanguage();
        $objLangs->generateHTMLSelect($this->objSelLanguage->getContentsSubmitted()->getValue(), $this->objSelLanguage);
        
    }    


    /**
     * specific handling of the child class inside handleCreateAccountEnterCredentials();
     *
     * @param TUsersAbstract $objUsersNew
     * @return void
     */
    public function handleCreateAccountEnterCredentialsChild($objUsersNew)
    {
        $objUsersNew->setLanguageID($this->objSelLanguage->getContentsSubmitted()->getValueAsInt());
        $objUsersNew->setUserRoleID(getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_NEWUSER_ROLEID)); //11-10-2023: changed getUserRoleID to setUserRoleID
        $objUsersNew->setUsernamePublic($objUsersNew->getUsername());

        //lookup: default country (needed for contact)
        $objCountries = new TSysCountries();
        $objCountries->loadFromDBByIsDefault();
        if ($objCountries->count() == 0)
        {
            error_log(__CLASS__.': '.__FUNCTION__.': '.__LINE__.': loading countries failed');
            return false; //it is gonna error out anyway, because country is obligatory, so might as well stop now
        }

        //create new contact (needed for user-account)
        $objContact = new TSysContacts();
        $objContact->setIsClient(true);
        $objContact->setCustomIdentifier($objUsersNew->getUsername());
        $objContact->setBillingCountryID($objCountries->getID());
        $objContact->setDeliveryCountryID($objCountries->getID());
        $objContact->setEmailAddressDecrypted($this->objEdtEmailAddress->getContentsSubmitted()->getValueAsString());
        $objContact->setBillingEmailAddressDecrypted($this->objEdtEmailAddress->getContentsSubmitted()->getValueAsString());
        $objContact->saveToDB(true, false);

        //create user-account (needed for user)
        $objAccount = new TSysCMSUserAccounts();
        $objAccount->setCustomIdentifier($objUsersNew->getUsername());
        $objAccount->setLoginEnabled(true);
        $objAccount->setContactID($objContact->getID());
        $objAccount->saveToDB(true, false);
        
        $objUsersNew->setCMSUserAccountID($objAccount->getID());

        unset($objContact);            
        unset($objAccount);            
        unset($objCountries);            
    } 


    /**
     * send an email to system administrator
     * 
     * @return bool
     */
    protected function sendEmailToSystemAdmin($sSubject, $sMessage)
    {
        $objMail = new TMailSend();
        $objMail->setFrom(getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_SYSTEMMAILBOT_FROM_EMAILADDRESS), getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_SYSTEMMAILBOT_FROM_NAME));
        $objMail->setTo(getSetting(SETTINGS_MODULE_SYSTEM, SETTINGS_SYSTEM_EMAILSYSADMIN));
        $objMail->setSubject($sSubject);
        $objMail->setBody($sMessage, true);
        $objMail->send();
    }
      
}
