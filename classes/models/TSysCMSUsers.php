<?php


namespace dr\classes\models;

use dr\classes\types\TDateTime;

/**
 * TSysCMSUsers created 10 jan 2020
 * 
 * 4 dec 2020: TSysCMSUsers: public function initRecord() added with default usergroupid from settings
 * 11 okt 2023: TSysCMSUsers: removed googleid (moved to TUsersAbstract)
 *
 * @author drenirie
 */
class TSysCMSUsers extends TUsersAbstract
{
    const FIELD_LANGUAGEID      = 'iLanguageID';
    const FIELD_USERROLEID     = 'iUserRoleID'; //=role id
    const FIELD_CMSUSERACCOUNTID   = 'iCMSUserAccountID';
    const FIELD_USERNAMEPUBLIC  = 'sUsernamePublic'; //the public publishable name on websites
    
    const USERNAMEDEFAULT       = 'root';
    const PASSWORDDEFAULT       = 'root';    
        
    public function getLanguageID()
    {
        return $this->get(TSysCMSUsers::FIELD_LANGUAGEID);
    }
    
    public function setLanguageID($iID)
    {
        $this->set(TSysCMSUsers::FIELD_LANGUAGEID, $iID);
    }

    public function getUserRoleID()
    {
        return $this->get(TSysCMSUsers::FIELD_USERROLEID);
    }
    
    public function setUserRoleID($iID)
    {
        $this->set(TSysCMSUsers::FIELD_USERROLEID, $iID);
    }

    public function getCMSUserAccountID()
    {
        return $this->get(TSysCMSUsers::FIELD_CMSUSERACCOUNTID);
    }
    
    public function setCMSUserAccountID($iID)
    {
        $this->set(TSysCMSUsers::FIELD_CMSUSERACCOUNTID, $iID);
    }    

    public function getUsernamePublic()
    {
        return $this->get(TSysCMSUsers::FIELD_USERNAMEPUBLIC);
    }
    
    public function setUsernamePublic($sUsername)
    {
        $this->set(TSysCMSUsers::FIELD_USERNAMEPUBLIC, $sUsername);
    }
   
    /**
     * called in handleLogInGoogleSigninCallback() of parent when it creates a new user.
     * This function adds specific functions to this class, like creating an account
     * @param \Google_Service_Oauth2 $objGoogle_Service_Oauth2
     */
    public function handleLogInGoogleSigninCallbackCreateNewUser($objGoogle_Service_Oauth2)
    {
        $objGoogle_account_info = $objGoogle_Service_Oauth2->userinfo->get();
        $iCountryID = 0;
        
        if (!GLOBAL_CMS_ANYONECANREGISTERACCOUNT)
        {
            logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'Cannot create new account upon google login, because account creation is not allowed according to GLOBAL_CMS_ANYONECANREGISTERACCOUNT');
            return false;
        }


        //@todo ==> foreign key constraint failed:
        //contact: billing countryid
        //contact: delivery countryid
        //language


        //requesting country id for contacts       
        $objCountries = new TSysCountries();
        $objCountries->loadFromDBByIsDefault();
        $iCountryID = $objCountries->getID();
        unset($objCountries);        


        //create new contact 
        //(we need that to create a new account()
        $objNewContact = new TSysContacts();
        $objNewContact->setEmailAddressDecrypted($objGoogle_account_info->email);
        $objNewContact->setFirstNameInitials($objGoogle_account_info->name);
        $objNewContact->setLastName($objGoogle_account_info->familyName);
        $objNewContact->setBillingEmailAddressDecrypted($objGoogle_account_info->email);
        $objNewContact->setBillingCountryID($iCountryID);
        $objNewContact->setDeliveryCountryID($iCountryID);

        if (!$objNewContact->saveToDB())
        {
            logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'Error creating contact from google api login');
            return false;
        }

        //create new account
        $objNewAccount = new TSysCMSUserAccounts();
        $objNewAccount->setCustomIdentifier(substr($objGoogle_account_info->email, 0, 8).$objGoogle_account_info->id);//8 characters from email + googleid
        $objNewAccount->setLoginEnabled(true);
        $objNewAccount->setContactID($objNewContact->getID());
        if (!$objNewAccount->saveToDB())
        {
            logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'Error creating account from google api login');
            return false;
        }

        //the rest ...
        $this->setUserRoleID(getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_NEWUSER_ROLEID)); 
        $this->setUsernamePublic($objGoogle_account_info->name); 







        return true;
    }




    public static function getTable()
    {
        return GLOBAL_DB_TABLEPREFIX.'SysCMSUsers';
    }
    
     /**
     * additions to the install procedure
     * 
     * @param array $arrPreviousDependenciesModelClasses
     */
    public function install($arrPreviousDependenciesModelClasses = null)
    {
        $bSuccess = true;
        $bSuccess = parent::install($arrPreviousDependenciesModelClasses);

        //==check if at least one user exists, if not, then add it
        $this->newQuery();
        $this->clear();    
        $this->limitOne(); //we need just one record to be returned
        if (!$this->loadFromDB())
        {
            error(__CLASS__.': loadFromDB() failed in install()');
            return false;
        }

        //==get default language (we need to know this first before we can add a user)
        $objLang = new TSysLanguages();
        if (!$objLang->loadFromDBByLocale(GLOBAL_LOCALE_DEFAULT))
        {
            error(__CLASS__.': TSysLanguages->loadFromDBByLocale() failed');
            return false;
        }
        
        //==get default user group
        $objGroup = new TSysCMSUsersRoles();
        if (!$objGroup->loadFromDBByGroupname(TSysCMSUsersRoles::ROLENAME_DEFAULT_ADMINISTRATORS))
        {
            error(__CLASS__.': TSysCMSUsersRoles->loadFromDBByGroupname() failed');
            return false;
        }        

        //==get default user account
        $objAccount = new TSysCMSUserAccounts();
        if (!$objAccount->loadFromDBByCustomIdentifier(TSysCMSUserAccounts::DEFAULT_ACCOUNTIDENTIFIER))
        {
            error(__CLASS__.': objAccount->loadFromDBByCustomIdentifier() failed');
            return false;
        }        

        
        //==if no user exists, add a default one
        if($this->count() == 0)
        {            
            $this->clear();
            $this->newRecord();
            $this->setUsername(TSysCMSUsers::USERNAMEDEFAULT); //default username
            $this->setPasswordDecrypted(TSysCMSUsers::PASSWORDDEFAULT); //default password
            $this->setPasswordExpires(new TDateTime(time()));
            $this->setLoginExpires(); //default no expiration
            $this->setLoginEnabled(true); //default enabled
            $this->setLanguageID($objLang->getID());
            $this->setUserRoleID($objGroup->getID());
            $this->setCMSUserAccountID($objAccount->getID());
            
            if (!$this->saveToDB())
            {
                error(__CLASS__.': saving new user');
                return false;
            }
        }
        
        unset($objLang);
        unset($objGroup);
        unset($objAccount);
        
        

        return $bSuccess;
    }       

    /**
     * defines the fields in the tables
     * i.e. types, default values, enum values, referenced tables etc
    */
    public function defineTable()
    {
        parent::defineTable();
        
        //language		
        $this->setFieldDefaultValue(TSysCMSUsers::FIELD_LANGUAGEID, '');
        $this->setFieldType(TSysCMSUsers::FIELD_LANGUAGEID, CT_INTEGER64);
        $this->setFieldLength(TSysCMSUsers::FIELD_LANGUAGEID, 0);
        $this->setFieldDecimalPrecision(TSysCMSUsers::FIELD_LANGUAGEID, 0);
        $this->setFieldPrimaryKey(TSysCMSUsers::FIELD_LANGUAGEID, false);
        $this->setFieldNullable(TSysCMSUsers::FIELD_LANGUAGEID, false);
        $this->setFieldEnumValues(TSysCMSUsers::FIELD_LANGUAGEID, null);
        $this->setFieldUnique(TSysCMSUsers::FIELD_LANGUAGEID, false);
        $this->setFieldIndexed(TSysCMSUsers::FIELD_LANGUAGEID, true);
        $this->setFieldForeignKeyClass(TSysCMSUsers::FIELD_LANGUAGEID, TSysLanguages::class);
        $this->setFieldForeignKeyTable(TSysCMSUsers::FIELD_LANGUAGEID, TSysLanguages::getTable());
        $this->setFieldForeignKeyField(TSysCMSUsers::FIELD_LANGUAGEID, TModel::FIELD_ID);
        $this->setFieldForeignKeyJoin(TSysCMSUsers::FIELD_LANGUAGEID);
        $this->setFieldForeignKeyActionOnUpdate(TSysCMSUsers::FIELD_LANGUAGEID, TModel::FOREIGNKEY_REFERENCE_CASCADE);
        $this->setFieldForeignKeyActionOnDelete(TSysCMSUsers::FIELD_LANGUAGEID, TModel::FOREIGNKEY_REFERENCE_RESTRICT); //dont delete when language is deleted (which it never should btw)
        $this->setFieldAutoIncrement(TSysCMSUsers::FIELD_LANGUAGEID, false);
        $this->setFieldUnsigned(TSysCMSUsers::FIELD_LANGUAGEID, true);
        $this->setFieldEncryptionDisabled(TSysCMSUsers::FIELD_LANGUAGEID);

        
        //user group/role
        $this->setFieldCopyProps(TSysCMSUsers::FIELD_USERROLEID, TSysCMSUsers::FIELD_LANGUAGEID);
        $this->setFieldForeignKeyClass(TSysCMSUsers::FIELD_USERROLEID, TSysCMSUsersRoles::class);
        $this->setFieldForeignKeyTable(TSysCMSUsers::FIELD_USERROLEID, TSysCMSUsersRoles::getTable());
        $this->setFieldForeignKeyField(TSysCMSUsers::FIELD_USERROLEID, TModel::FIELD_ID);
        $this->setFieldForeignKeyActionOnDelete(TSysCMSUsers::FIELD_LANGUAGEID, TModel::FOREIGNKEY_REFERENCE_RESTRICT); //dont delete when role is deleted


        //user account
        $this->setFieldCopyProps(TSysCMSUsers::FIELD_CMSUSERACCOUNTID, TSysCMSUsers::FIELD_LANGUAGEID);
        $this->setFieldForeignKeyClass(TSysCMSUsers::FIELD_CMSUSERACCOUNTID, TSysCMSUserAccounts::class);
        $this->setFieldForeignKeyTable(TSysCMSUsers::FIELD_CMSUSERACCOUNTID, TSysCMSUserAccounts::getTable());
        $this->setFieldForeignKeyField(TSysCMSUsers::FIELD_CMSUSERACCOUNTID, TModel::FIELD_ID);
        $this->setFieldForeignKeyActionOnDelete(TSysCMSUsers::FIELD_CMSUSERACCOUNTID, TModel::FOREIGNKEY_REFERENCE_CASCADE); //if useraccount is deleted, also delete user
        

        //public username		
        $this->setFieldDefaultValue(TSysCMSUsers::FIELD_USERNAMEPUBLIC, '');
        $this->setFieldType(TSysCMSUsers::FIELD_USERNAMEPUBLIC, CT_VARCHAR);
        $this->setFieldLength(TSysCMSUsers::FIELD_USERNAMEPUBLIC, 255);
        $this->setFieldDecimalPrecision(TSysCMSUsers::FIELD_USERNAMEPUBLIC, 0);
        $this->setFieldPrimaryKey(TSysCMSUsers::FIELD_USERNAMEPUBLIC, false);
        $this->setFieldNullable(TSysCMSUsers::FIELD_USERNAMEPUBLIC, true);
        $this->setFieldEnumValues(TSysCMSUsers::FIELD_USERNAMEPUBLIC, null);
        $this->setFieldUnique(TSysCMSUsers::FIELD_USERNAMEPUBLIC, false);
        $this->setFieldIndexed(TSysCMSUsers::FIELD_USERNAMEPUBLIC, false);
        $this->setFieldForeignKeyClass(TSysCMSUsers::FIELD_USERNAMEPUBLIC, null);
        $this->setFieldForeignKeyTable(TSysCMSUsers::FIELD_USERNAMEPUBLIC, null);
        $this->setFieldForeignKeyField(TSysCMSUsers::FIELD_USERNAMEPUBLIC, null);
        $this->setFieldForeignKeyJoin(TSysCMSUsers::FIELD_USERNAMEPUBLIC, null);
        $this->setFieldForeignKeyActionOnUpdate(TSysCMSUsers::FIELD_USERNAMEPUBLIC, null);
        $this->setFieldForeignKeyActionOnDelete(TSysCMSUsers::FIELD_USERNAMEPUBLIC, null);
        $this->setFieldAutoIncrement(TSysCMSUsers::FIELD_USERNAMEPUBLIC, false);
        $this->setFieldUnsigned(TSysCMSUsers::FIELD_USERNAMEPUBLIC, false);    
        $this->setFieldEncryptionDisabled(TSysCMSUsers::FIELD_USERNAMEPUBLIC);        
                 
    }
    

    /**
     * returns an array with fields that are publicly viewable
     * sometimes (for security reasons the password-field for example) you dont want to display all table fields to the user
     *
     * i.e. it can be used for searchqueries, sorting, filters or exports
     *
     * @return array function returns array WITHOUT tablename
    */
    public function getFieldsPublic()
    {
        return array(TUsersAbstract::FIELD_USERNAME, TSysCMSUsers::FIELD_USERNAMEPUBLIC, TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, TUsersAbstract::FIELD_LOGINENABLED, TUsersAbstract::FIELD_LOGINEXPIRES,  TUsersAbstract::FIELD_PASSWORDEXPIRES,  TUsersAbstract::FIELD_LASTLOGIN);
    } 

    /**
     * erf deze functie over om je eigen checksum te maken voor je tabel.
     * je berekent deze de belangrijkste velden te pakken, wat strings toe te
     * voegen en alles vervolgens de coderen met een hash algoritme
     * zoals met sha1 (geen md5, gezien deze makkelijk te breken is)
     * de checksum mag maar maximaal 50 karakters lang zijn
     *
     * BELANGRIJK: je mag NOOIT het getID() en getChecksum()-field meenemen in
     * je checksum berekening (id wordt pas toegekend na de save in de database,
     * dus is nog niet bekend ten tijde van het checksum berekenen)
     *
     * @return string
    */
    public function getChecksumUncrypted()
    {
        // return 'piekkdl1'.$this->get(TUsersAbstract::FIELD_USERNAME).'_'.$this->get(TUsersAbstract::FIELD_PASSWORDENCRYPTED).$this->getUserRoleID().'e03-flubbergast';//before 15-11-2022
        return 'piekkdl1'.$this->get(TUsersAbstract::FIELD_USERNAME).'_'.$this->get(TUsersAbstract::FIELD_PASSWORDENCRYPTED).$this->getUserRoleID().'e03-flubbergast'.boolToStr($this->getLoginEnabled(), true).$this->getLoginExpires()->getTimestamp().$this->getDeleteAfter()->getTimestamp().$this->getUsernamePublic();
    }    
    
    /**
     * how many fake password fields do you want
     */
    public function getNoFakePasswordFields()
    {
        return 4;
    }    

    /**
     * This function is called in the constructor and the clear() function
     * this is used to define default values for fields
     * 
     * initialize values
     */
    public function initRecord()
    {
        parent::initRecord();

        $this->setUserRoleID(getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_NEWUSER_ROLEID));            
    }    
  

    /**
     * override from parent class to add restrictions for useraccounts (able-to-login and account expired)
     * 
     * NOTE: we use the auto-join feature from parent::loadFromDBByUserLoginAllowed() to ask for additional information about user-accounts
     */
    public function loadFromDBByUserLoginAllowed($sUsername, $sPassword)
    {
        $bAllowed = parent::loadFromDBByUserLoginAllowed($sUsername, $sPassword);

        if (($this->count() > 0) && ($bAllowed)) //if parent fails loading user (because not authorized), we have no data to work with
        {
            //user account not enabled
            if ($this->get(TSysCMSUserAccounts::FIELD_LOGINENABLED, TSysCMSUserAccounts::getTable()) === false)
            {
                logAccess(__CLASS__.': '.__FUNCTION__.': '.__LINE__,'access denied for user because disabled via useraccount', $sUsername);
                preventTimingAttack(50,400);
                return false;
            }
            

            //user account expired        
            $objDate = $this->get(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, TSysCMSUserAccounts::getTable());
            if ($objDate->isInTheFuture() || $objDate->isZero())
            {
                $bAllowed = true;
            }
            else
            {
                logAccess(__CLASS__.': '.__FUNCTION__.': '.__LINE__,'access denied for user because loginexpired via useraccount', $sUsername);
                preventTimingAttack(20,500);
                return false;
            }

            //check user account checksum
            //kind of clunky way by doing a separate query, but I want to make sure the database is not tempered with
            $objAccount = new TSysCMSUserAccounts();
            $objAccount->loadFromDBByID($this->get(TSysCMSUserAccounts::FIELD_ID, TSysCMSUserAccounts::getTable()));
            if (!$objAccount->isChecksumValid())
            {
                logAccess(__CLASS__.': '.__FUNCTION__.': '.__LINE__,'access denied for user, because checksum useraccount "'.$objAccount->getCustomIdentifier().'" failed', $sUsername);
                preventTimingAttack(10,200);
                return false;
            }
            unset($objAccount);

            //anonymous users are never allowed to log in 
            if ($this->get(TSysCMSUsersRoles::FIELD_ISANONYMOUS, TSysCMSUsersRoles::getTable()) === true)
            {
                logAccess(__CLASS__.': '.__FUNCTION__.': '.__LINE__,'access denied for user, because user falls under anonymous role', $sUsername);
                preventTimingAttack(20,400);
                return false;
            }

            //check checksum role
            //kind of clunky way by doing a separate query, but I want to make sure the database is not tempered with
            $objRole = new TSysCMSUsersRoles();
            $objRole->loadFromDBByID($this->get(TSysCMSUsersRoles::FIELD_ID, TSysCMSUsersRoles::getTable()));
            if (!$objRole->isChecksumValid())
            {                
                logAccess(__CLASS__.': '.__FUNCTION__.': '.__LINE__,'access denied for user, because checksum role "'.$objRole->getRoleName().'" failed. Possible intrusion attempt detected.', $sUsername);
                preventTimingAttack(50,300);
                return false;
            }   
            unset($objRole); 


            //check checksum roles-assigned-user
            //kind of clunky way by doing a separate query, but I want to make sure the database is not tempered with
            $objAssignedUsers = new TSysCMSUsersRolesAssignUsers();
            $objAssignedUsers->loadFromDBByID($this->get(TSysCMSUsersRolesAssignUsers::FIELD_ROLEID, TSysCMSUsersRolesAssignUsers::getTable()));
            if ($objAssignedUsers->count() > 0) //could be empty
            {
                if (!$objAssignedUsers->isChecksumValid())
                {                
                    logAccess(__CLASS__.': '.__FUNCTION__.': '.__LINE__,'access denied for user, because checksum TSysCMSUsersRolesAssignUsers for role-id: '.$objAssignedUsers->getRoleID().' failed. Possible intrusion attempt detected.', $sUsername);
                    preventTimingAttack(50,300);
                    return false;
                }   
            }
            unset($objAssignedUsers); 
        }


        return $bAllowed;
    }

       

    /**
     * automatically set some stuff
     * 
     * @param boolean $bResetDirtyNewOnSuccess
     * @param boolean $bStartOwnDatabaseTransaction
     * @param boolean $bCheckForLock
     * @return boolean
     */
   public function saveToDB($bResetDirtyNewOnSuccess = true, $bStartOwnDatabaseTransaction = true, $bCheckForLock = false)    
   {
        if ($this->getNew())
        {
            //make sure we have a public username
            if ($this->getUsernamePublic() == '')
            {
                $this->setUsernamePublic($this->getUsername());
            }

            //make sure we have a language id
            if ($this->getLanguageID() == '')
            {
                $objLang = new TSysLanguages();
                if (!$objLang->loadFromDBByLocale(GLOBAL_LOCALE_DEFAULT))
                {
                    error(__CLASS__.': TSysLanguages->loadFromDBByLocale() failed');
                    return false;
                }
                $this->setLanguageID($objLang->getID());    
                unset($objLang);
            }

            //make sure we have a usergroupid
            if ($this->getUserRoleID() == '')
            {
                $objGroup = new TSysCMSUsersRoles();
                if (!$objGroup->loadFromDBByGroupname(TSysCMSUsersRoles::ROLENAME_DEFAULT_ACCOUNTUSERS)) //for security reasons dont make them administrators by default
                {
                    error(__CLASS__.': TSysCMSUsersRoles->loadFromDBByGroupname() failed');
                    return false;
                }        
                $this->setUserRoleID($objGroup->getID());
                unset($objGroup);
            }

            if ($this->getPasswordExpires()->isZero())
            {
                $this->getPasswordExpires()->setNow();
                $this->getPasswordExpires()->addDays(getSetting(SETTINGS_MODULE_CMS, SETTINGS_CMS_MEMBERSHIP_USERPASSWORDEXPIRES_DAYS));
            }            
        }

       
        return parent::saveToDB($bResetDirtyNewOnSuccess, $bStartOwnDatabaseTransaction, $bCheckForLock);
   }



}
