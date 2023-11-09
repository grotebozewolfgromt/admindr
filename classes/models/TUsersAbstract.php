<?php

namespace dr\classes\models;

use dr\classes\models\TModel;
use dr\classes\types\TDateTime;

/**
 * An abstract class for users website, cms other webapps etc.
 * 
 * when you inherit this class add a link (id) to a usergroup/role
 * 
 * is a user allowed to log in?
 * $obj = new TUsers
 * $obj->loadFromDBByLoginAllowed($user, $pass) --> the record is loaded if success
 * 
 * you can ask a user to set a new password by using:
 * $obj->needsNewPassword()
 * 
 * ENCRYPTION
 * this class supports encryption of passwords in a way you don't have to bother 
 * with them. 
 * Out of security reasons we use one way encryption, 
 * the password_hash() functions of php are used.
 * 
 * You can set passwords, this class will automatically encrypt the password and 
 * stores ONLY the encrypted version into memory.
 * Therefore it is not possible to get a plain password with getPassword(), 
 * only an encrypted one out of security reasons.
 * This method of encryption is done this way out of consistency, because when 
 * loading from database we only have an encrypted version available (and not a plain one).
 * 
 * FAKE PASSWORD FIELDS
 * This class generates fake password fields for security reasons.
 * If someone manages to get access to the database somehome 
 * (injection, hack whatever) they still don't know what the real database
 * password field is, since the fake password fields change too when 
 * you set the real (uncrypted) password.
 * It may be security by obscurity, but with 5 fake fields you need 5x the amount of system resources
 * or 5 times the amount of brute force attempts.
 * 
 * with ->getNoFakePasswordFields() you return the number of fake password fields you want to create.
 * Make sure to create more fake password fields than the index-number of the password field
 * for example:
 * if the real password field is named 'sPassword2', make sure to create at least 2 fake fields
 * 
 * PASSWORD EXPIRATION 
 * if date is zero = no password expiration
 * date in the past = password is expired
 * date in the future = password is not expired
 * password expiration will NOT affect the ability to login, it's merely for GUI
 * purposes to prompt the user to refresh their password
 * (NOT to confuse with LOGIN EXPIRATION which DOES influence the ability to login)
 * 
 * LOGIN EXPIRATION
 * after this date the user can't login anymore. handy for trial users 
 * 
 * USERNAME and EMAIL
 * we don't want to force using emailaddress as username, there are maybe cases, you don't want that
 * 
 * DISABLE LOGIN
 * you can easily kick a user out for the system by switching login-enabled to false.
 * 
 * EMAIL ADDRESS
 * we want to have an email address for password retrieval
 * 
 * AUTO DELETION
 * you can use this class also for automatic expiration.
 * When you use a system that is used based on a recurring fee, you can auto expire
 * a user login when payment is due.
 * Also handy for trial users
 * The users-account class has also the same auto-delete-feature, but that deletes all users in that account 
 * (These features are separated from each other (the one doesn't need the other to function properly))
 * 
 * LOGIN TOKENS
 * we could choose to store username and password in _SESSION and _COOKIE arrays.
 * especially the cookie is dangerous because the user can read it.
 * in stead we use 3 login tokens that represent the equivalent to username and 
 * password. With these 3 tokens you can also log in.
 * Token1 is the id and needs to be unique (stored in plain text in db and _SESSION/_COOKIE)
 * Token2 (stored in plain text in db and _SESSION/_COOKIE)
 * Token3 is stored in plain text in db and encrypted in _SESSION/_COOKIE
 * 
 * 
 * created 10 jan 2020
 * 16 jan 2020: TUsersAbstract: extra filter on username, password and tokens parameter in loadFromDBBy.. functions
 * 3 nov 2020: TUsersAbstract: loadFromDBByUserLoginAllowed() checkt op checksum, dat voorkomt database tempering, dat is een SUPER VEILIG IDEE!!!
 * 10 nov 2020: TUsersAbstract: added fake password fields
 * 11 sept 2021: TUserAbstract: added const for emailfingerprint digest
 * 11 okt 2022: TUserAbstract: added moved to here all the googleid stuff
 */

abstract class TUsersAbstract extends TModel
{
    //some field names are abbreviated for security reasons
    const FIELD_USERNAME                = 'sUsername';
    const FIELD_PASSWORDENCRYPTED       = 'sPassword1'; //the real password is stored here
    const FIELD_LOGINENABLED            = 'bLoEn'; //only possible to log in when user is enabled
    const FIELD_LOGINEXPIRES            = 'dtLoEx'; //date on which the user can't log in anymore
    const FIELD_LASTLOGIN               = 'dtLastLogin'; //date on which the user can't log in anymore
    const FIELD_PASSWORDEXPIRES         = 'dtPasswordExpires'; //date on which the user needs to set a new password, it will not affect the ability to login!
    const FIELD_EMAILADDRESSENCRYPTED   = 'sEAE';//Email Address Encrypted internally stored in encrypted form - 2 way encrypted email address
    const FIELD_EMAILADDRESSFINGERPRINT = 'sEAF';//Fingerprint Email Address, so we can lookup a record based on email address. We can't salt this, because we need to be able to search on it in the database for password recovery
    const FIELD_UPDATEPERMISSIONS       = 'bUpdatePermissions';//update user permissions (for auth()) on next page load?
    const FIELD_EMAILTOKENENCRYPTED     = 'sEMTO';//temp token for emails 
    const FIELD_EMAILTOKENEXPIRES       = 'dtEMTOEX';//expiration date for email token
    const FIELD_DELETEAFTER             = 'dtDeAf';//expiration date for a user. after this date the user will be deleted by a cron job
    const FIELD_GOOGLEID                = 'sGID'; //google openid 255 chars https://openid.net/specs/openid-connect-core-1_0.html#IDToken (called 'SUB' ID). used for login-with-google

    const FIELD_PASSWORDFAKEPREFIX      = 'sPassword';//prefix without the number (for the names of fake password fields, because they are numbered)
        
    const SEED_EMAILADDRESSFINGERPRINT ='49fr04jeoiej3iu4f09834rjib34frhuierf9hriu4EF09j34oin34r'; //seed to make it harder to decrypt, when we change it up per class not every table has the same seed
    const DIGEST_EMAILADDRESSFINGERPRINT = ENCRYPTION_DIGESTALGORITHM_SHA512;

    const ENCRYPTION_EMAILADDRESS_PASSPHRASE = '2834hef93hr0ewhjweioweE3df4A+3idmo3nzo#klajd'; //passphrase for the encryption algo

    /**
     * get username
     * 
     * @return string
     */
    public function getUsername()
    {
        return $this->get(TUsersAbstract::FIELD_USERNAME);
    }

    /**
     * set username
     * 
     * @param string $sName
     */
    public function setUsername($sName)
    {
        $this->set(TUsersAbstract::FIELD_USERNAME, $sName);
    }

    /**
     * get the encrypted password for user
     * 
     * @return string
     */
    public function getPasswordEncrypted()
    {
        return $this->get(TUsersAbstract::FIELD_PASSWORDENCRYPTED);
    }

    /**
     * set uncrypted password for user
     * setPasswordUncrypted('123') will encrypt the password and stores the 
     * encrypted version of '123' in to the internal data storage
     * 
     * You can reset the tokens also with parameter $bResetTokens:
     * The user may want to change a password because of an unauthorised login
     * to prevent the session and cookie used to log in, we change them
     * 
     * @param string $sPassword
     */
    public function setPasswordDecrypted($sPassword)
    {
        $sEncr = '';
        $sEncr = password_hash($sPassword, PASSWORD_DEFAULT);
        $this->set(TUsersAbstract::FIELD_PASSWORDENCRYPTED, $sEncr);   
        
        $iNoFakePasswordFields = $this->getNoFakePasswordFields();
        for ($iIndex = 0; $iIndex <= $iNoFakePasswordFields; $iIndex++)
        {
            $sEncr = password_hash(generatePassword(10, 20), PASSWORD_DEFAULT);
            if (TUsersAbstract::FIELD_PASSWORDFAKEPREFIX.$iIndex != TUsersAbstract::FIELD_PASSWORDENCRYPTED) //prevent overwriting the real password
                $this->set(TUsersAbstract::FIELD_PASSWORDFAKEPREFIX.$iIndex, $sEncr);   
        }
    }        

    /**
     * get email address
     * 
     * @return string
     */
    // public function getEmailAddress_OLD()
    // {
    //     return $this->get(TUsersAbstract::FIELD_EMAILADDRESS_OLD);
    // }

    /**
     * set email address
     * 
     * @param string $sEmail
     */
    // public function setEmailAddress_OLD($sEmail)
    // {
    //     $this->set(TUsersAbstract::FIELD_EMAILADDRESS_OLD, $sEmail);
    // }


    /**
     * get email address and decrypt it
     * 
     * @return string
     */
    public function getEmailAddressDecrypted()
    {
        return $this->get(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, '', true);
    }

    /**
     * encrypts and sets email address AND email identifier
     * 
     * @param string $sEmail
     */
    public function setEmailAddressDecrypted($sUncryptedEmail)
    {
        $this->set(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, $sUncryptedEmail, '', true);
        $this->set(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, getFingerprintEmail($sUncryptedEmail, TUsersAbstract::SEED_EMAILADDRESSFINGERPRINT, TUsersAbstract::DIGEST_EMAILADDRESSFINGERPRINT));
    }


    /**
     * getting email fingerprint from an email address without getting or setting it in this class
     *
     * @param string $sEmailAddress
     * @return void
     */
    public function generateFingerprintEmail($sEmailAddress)
    {
        return getFingerprintEmail($sEmailAddress, TUsersAbstract::SEED_EMAILADDRESSFINGERPRINT, TUsersAbstract::DIGEST_EMAILADDRESSFINGERPRINT);        
    }


    /**
     * is the user able to log in?
     * 
     * @return boolean
     */
    public function getLoginEnabled()
    {
        return $this->get(TUsersAbstract::FIELD_LOGINENABLED);
    }

    /**
     * set if the user able to log in
     * 
     * @param boolean $bAllowed
     */
    public function setLoginEnabled($bAllowed)
    {
        $this->set(TUsersAbstract::FIELD_LOGINENABLED, $bAllowed);
    }           

    /**
     * default the datetime object is null, which results in NO EXPIRATION
     * 
     * Can be used for automatic payments, if you do not pay, you can't login
     *
     * @param TDateTime $objDateTime when null then an invalid date (timestamp 0) will be set, so never expires
     */
    public function setLoginExpires($objDateTime = null)
    {
        $this->setTDateTime(TUsersAbstract::FIELD_LOGINEXPIRES, $objDateTime);
    }        

    /**
     * when does the user login expire?
     * can return an object with timestamp 0 when NO EXPIRATION date set
     * 
     * Can be used for automatic payments, if you do not pay, you can't login
     * 
     * @return TDateTime
     */
    public function getLoginExpires()
    {
        return $this->get(TUsersAbstract::FIELD_LOGINEXPIRES);
    }        
    
    /**
     * set the last time the user logged in
     * 
     * @param TDateTime $objDateTime
     */
    public function setLastLogin($objDateTime = null)
    {
        $this->setTDateTime(TUsersAbstract::FIELD_LASTLOGIN, $objDateTime);
    }        

    /**
     * when did the user log in for the last time
     * 
     * @return TDateTime
     */
    public function getLastLogin()
    {
        return $this->get(TUsersAbstract::FIELD_LASTLOGIN);
    }       
    
    /**
     * set the time on which user has to set a new password for security reasons
     * (has nothing to do with password_needs_rehash(), needsNewPassword() does that)
     * 
     * if date is zero = no password expiration
     * date in the past = password is expired
     * date in the future = password is not expired
     * password expiration will not affect the ability to login, it's merely for GUI
     * purposes to prompt the user to refresh their password
     *  
     * @param TDateTime $objDateTime
     */
    public function setPasswordExpires($objDateTime = null)
    {
        $this->setTDateTime(TUsersAbstract::FIELD_PASSWORDEXPIRES, $objDateTime);
    }        

    /**
     * get the time on which user has to set a new password for security reasons
     * (has nothing to do with password_needs_rehash(), needsNewPassword() does that)
     * 
     * if date is zero = no password expiration
     * date in the past = password is expired
     * date in the future = password is not expired
     * password expiration will not affect the ability to login, it's merely for GUI
     * purposes to prompt the user to refresh their password
     * 
     * @return TDateTime
     */
    public function getPasswordExpires()
    {
        return $this->get(TUsersAbstract::FIELD_PASSWORDEXPIRES);
    }        
    
     
   /**
     * update user permissions from db on next page load?
     *
     * @return bool
     */
    public function getUpdatePermissions()
    {
        return $this->get(TUsersAbstract::FIELD_UPDATEPERMISSIONS);
    }


    /**
     * update user permissions from db on next page load?
     *
     * @param bool $bUpdate
     * @return void
     */
    public function setUpdatePermissions($bUpdate)
    {
        $this->set(TUsersAbstract::FIELD_UPDATEPERMISSIONS, $bUpdate);
    }
    

   /**
     * get email token
     * (token that is sent in emails to verify validity)
     *
     * @return string
     */
    public function getEmailTokenEncrypted()
    {
        return $this->get(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED);
    }


    /**
     * set email token
     * (token that is sent in emails to verify validity)
     *
     * @param string $sToken
     * @return void
     */
    public function setEmailTokenDecrypted($sToken)
    {
        // $this->set(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, $sToken);
        $sEncr = '';
        $sEncr = password_hash($sToken, PASSWORD_DEFAULT);
        $this->set(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, $sEncr);          
    }    

    /**
     * empty the email token field
     * When we don't need email token, we empty it
     *
     * @return boolean
     */
    public function setEmailTokenEmpty()
    {
        $this->set(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, '');          
    }

    /**
     * check if email token field is empty
     *
     * @return boolean
     */
    public function getEmailTokenIsEmpty()
    {
        return $this->get(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED) == '';
    }    

    /**
     * compare an external token (sent via email) with the internal one
     * if they are equal this function returns true, otherwise false
     * if token is empty it returns also false
     * @param string $sUncryptedTokenSentByEmail
     * @return boolean
     */
    public function isValidEmailToken($sUncryptedTokenSentByEmail)
    {
        if ($this->getEmailTokenIsEmpty())
            return false;

        return password_verify($sUncryptedTokenSentByEmail, $this->get(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED));
    }
    
   /**
     * get email token expiration date+time
     * (token that is sent in emails to verify validity)
     *
     * @return TDateTime
     */
    public function getEmailTokenExpires()
    {
        return $this->get(TUsersAbstract::FIELD_EMAILTOKENEXPIRES);
    }


    /**
     * email token expiration date+time
     * (token that is sent in emails to verify validity)
     *
     * @param TDateTime $objDateTime
     * @return void
     */
    public function setEmailTokenExpires($objDateTime = null)
    {
        $this->setTDateTime(TUsersAbstract::FIELD_EMAILTOKENEXPIRES, $objDateTime);
    }    


    /**
     * when is the user scheduled for deletion?
     * after this date an account will be deleted in with a cron job
     *
     * @param TDateTime $objDateTime when null then an invalid date (timestamp 0) will be set, no deletion scheduled
     */
    public function setDeleteAfter($objDateTime = null)
    {
        $this->setTDateTime(TUsersAbstract::FIELD_DELETEAFTER, $objDateTime);
    }        

    /**
     * when is the user scheduled for deletion?
     * after this date an account will be deleted in with a cron job
     * 
     * 
     * @return TDateTime
     */
    public function getDeleteAfter()
    {
        return $this->get(TUsersAbstract::FIELD_DELETEAFTER);
    }        

    /**
     * set google id
     * (used for login-with-google)
     * 
     * @param string $sGoogleID open id (called sub id)
     */
    public function setGoogleID($sGoogleID)
    {
        $this->set(TUsersAbstract::FIELD_GOOGLEID, $sGoogleID);
    }        

    /**
     * get google id
     * (used for login-with-google)
     * 
     * @return TDateTime
     */
    public function getGoogleID()
    {
        return $this->get(TUsersAbstract::FIELD_GOOGLEID);
    }   


    /**
     * looks in database if usernames already exists in database
     * this function excludes the current record 
     * (it looks at all records except with current id if it's an existing record)
     *  
     * @param string $sUsername
     */
    public function isUsernameTakenDB($sUsername)
    {
        $bResult = false;
        $objClone = clone $this;
        $objClone->clear();

        //exclude current record
        if (!$this->getNew())
            $objClone->find(TUsersAbstract::FIELD_ID, $this->getID(), COMPARISON_OPERATOR_NOT_EQUAL_TO);
        
        if ($objClone->loadFromDBByUsername($sUsername))
        {
            if ($objClone->count() > 0) //username taken
                $bResult = true;
        }
        
        unset($objClone);
        return $bResult;        
    }
    
    /**
     * this function loads user with username from database
     * and determines if the current loaded user 
     * is allowed to log in based on the $sUsername and $sPassword.
     * it takes into account the password, login expired and enabled
     * (password expiration is not taken into account)
     * 
     * this method is safe for sql injections
     * 
     * returns false if $sUsername or $sPassword is empty
     * 
     * TRUE = verified and allowed to login, record loaded into memory
     * FALSE = not allowed to log in, RECORD NOT LOADED into memory
     * 
     * @param string $sUsername
     * @param string $sPassword password
     * @return boolean allowed
     */
    public function loadFromDBByUserLoginAllowed($sUsername, $sPassword)
    {

        if ($sUsername == '')
            return false;
        if ($sPassword == '')
            return false;
        
        //just to be sure: filter on XSS and weird characters
        $sUsername = strip_tags($sUsername);
        $sPassword = strip_tags($sPassword);        
        $sUsername = filterBadCharsWhiteList($sUsername, REGEX_TEXT_NORMAL, true);
//        $sPassword = filterBadCharsWhiteList($sPassword, REGEX_TEXT_NORMAL, true); -->runs through password_verify() anyway
        
        $this->clear();
        $this->find(TUsersAbstract::FIELD_USERNAME, $sUsername);
        if ($this->loadFromDB(1)) //needs to be at least 1, so the child class can read additional data from externally-referenced-tables like useraccounts.
        {
            
            if ($this->count() > 0)
            {
                if (password_verify($sPassword, $this->getPasswordEncrypted()))
                {
                    if ($this->getLoginEnabled())
                    {
                        if ($this->getLoginExpires()->isInTheFuture() || $this->getLoginExpires()->isZero())
                        {
                            if ($this->isChecksumValid())
                            {
                                $this->newQuery(); //otherwise you build upon the existing query
                                return true;
                            }
                            else
                            {
                                logAccess(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'isChecksumValid() failed for user. return false', $sUsername);
                                preventTimingAttack(10,200);
                                return false;
                            }
                        }
                    }
                    else
                    {
                        logAccess(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'user->getLoginEnabled() failed for user. return false', $sUsername);
                        preventTimingAttack(20,500);
                        return false;
                    }
                }
                else
                {
                    logAccess(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'password_verify() failed for user. return false', $sUsername);
                    preventTimingAttack(40,200);
                    return false;
                }
            }            
        }
        $this->clear();
        return false;
    }



    /**
     * load user from database with username $sUsername
     * 
     * @param string $sUsername
     */
    public function loadFromDBByUsername($sUsername)
    {
        $bResult = false;
        $this->find(TUsersAbstract::FIELD_USERNAME, $sUsername);
        if ($this->loadFromDB(true))
            $bResult = true;
        $this->newQuery();
        return $bResult;
    }

    /**
     * load user from database with username $sUsername
     * 
     * @param int $iID
     */
    public function loadFromDBByGoogleID($iID)
    {
        $bResult = false;

        if (!is_numeric($iID))
        {
            logError(__CLASS__.': '.__FUNCTION__.': '.__LINE__, 'google id is not numeric');
            return false;
        }

        $this->find(TSysCMSUsers::FIELD_GOOGLEID, $iID);
        if ($this->loadFromDB(true))
            $bResult = true;
        $this->newQuery();
        return $bResult;
    } 
    
    /**
     * this function determines if the user needs to set a new password
     * 
     * this function returns true, if:
     * - there is a new encryption algoritm for encrypting passwords (password_needs_rehash)
     * - the password is expired
     */
    public function needsNewPassword()
    {
        if (password_needs_rehash($this->getPasswordEncrypted(), PASSWORD_DEFAULT))
            return true;

        if ($this->getPasswordExpires()->isInThePast() && (!$this->getPasswordExpires()->isZero()) )
        {
            return true;
        }
        
        return false;
    }
    
    /**
     * automatically set some stuff
     * 
     * @param boolean $bResetDirtyNewOnSuccess
     * @param boolean $bStartOwnDatabaseTransaction
     * @param boolean $bCheckForLock
     * @return boolean
     */
//    public function saveToDB($bResetDirtyNewOnSuccess = true, $bStartOwnDatabaseTransaction = true, $bCheckForLock = false)    
//    {
//        if ($this->getDaysAutoRenewPasswordExpiration() > 0)
//        {
//            $this->getPasswordExpires()->setNow();
//            $this->getPasswordExpires()->addDays($this->getDaysAutoRenewPasswordExpiration());
//        }
//        
//        return parent::saveToDB($bResetDirtyNewOnSuccess, $bStartOwnDatabaseTransaction, $bCheckForLock);
//    }
    
    
    
    
     /**
     * additions to the install procedure
     * 
     * @param array $arrPreviousDependenciesModelClasses
     */
    public function install($arrPreviousDependenciesModelClasses = null)
    {
        return parent::install($arrPreviousDependenciesModelClasses);
    }     
        


    /**
     * check if email tokens are expired, if yes then empty the token field
     *
     * @return bool
     */
    public function deleteEmailTokensExpired()
    {
        $bResult = false;
        $objNow = new TDateTime();
        $objNow->setNow();
        $objZero = new TDateTime();
        
        $objTempUsers = $this->getCopy();
        $objTempUsers->find(TUsersAbstract::FIELD_EMAILTOKENEXPIRES, $objNow, COMPARISON_OPERATOR_LESS_THAN);
        $objTempUsers->find(TUsersAbstract::FIELD_EMAILTOKENEXPIRES, $objZero, COMPARISON_OPERATOR_GREATER_THAN);
        
        if ($objTempUsers->loadFromDB())
        {
            while($objTempUsers->next())
            {
                $objTempUsers->setEmailTokenEmpty();
                $objTempUsers->setEmailTokenExpires();
            }
        }
        $bResult = $objTempUsers->saveToDBAll();

        unset($objNow);
        unset($objZero);
        unset($objTempUsers);

        return $bResult;
    }

    /**
     * delete all users where the account date is expired
     *
     * @return bool
     */
    public function deleteUsersExpired()
    {
        $bResult = true;
        $objNow = new TDateTime();
        $objNow->setNow();
        $objZero = new TDateTime();
        
        $objTempUsers = $this->getCopy();
        $objTempUsers->find(TUsersAbstract::FIELD_DELETEAFTER, $objNow, COMPARISON_OPERATOR_LESS_THAN);
        $objTempUsers->find(TUsersAbstract::FIELD_DELETEAFTER, $objZero, COMPARISON_OPERATOR_GREATER_THAN);
        
        if ($objTempUsers->loadFromDB())
        {
            while($objTempUsers->next())
            {
                if (!$objTempUsers->deleteFromDB(true, true))
                    $bResult = false;
            }
        }

        unset($objNow);
        unset($objZero);
        unset($objTempUsers);

        return $bResult;    
    }

	
    /**
     * This function is called in the constructor and the clear() function
     * this is used to define default values for fields
     * 
     * initialize values
     */
    public function initRecord()
    {
        $this->setLoginEnabled(false);
        $this->setLoginExpires();
        
    }
	
	
	
    /**
     * defines the fields in the tables
     * i.e. types, default values, enum values, referenced tables etc
    */
    public function defineTable()
    {
       

        //username
        $this->setFieldDefaultValue(TUsersAbstract::FIELD_USERNAME, '');
        $this->setFieldType(TUsersAbstract::FIELD_USERNAME, CT_VARCHAR);
        $this->setFieldLength(TUsersAbstract::FIELD_USERNAME, 100);
        $this->setFieldDecimalPrecision(TUsersAbstract::FIELD_USERNAME, 0);
        $this->setFieldPrimaryKey(TUsersAbstract::FIELD_USERNAME, false);
        $this->setFieldNullable(TUsersAbstract::FIELD_USERNAME, false);
        $this->setFieldEnumValues(TUsersAbstract::FIELD_USERNAME, null);
        $this->setFieldUnique(TUsersAbstract::FIELD_USERNAME, true);
        $this->setFieldIndexed(TUsersAbstract::FIELD_USERNAME, false);//it is already UNIQUE
        $this->setFieldForeignKeyClass(TUsersAbstract::FIELD_USERNAME, null);
        $this->setFieldForeignKeyTable(TUsersAbstract::FIELD_USERNAME, null);
        $this->setFieldForeignKeyField(TUsersAbstract::FIELD_USERNAME, null);
        $this->setFieldForeignKeyJoin(TUsersAbstract::FIELD_USERNAME, null);
        $this->setFieldForeignKeyActionOnUpdate(TUsersAbstract::FIELD_USERNAME, null);
        $this->setFieldForeignKeyActionOnDelete(TUsersAbstract::FIELD_USERNAME, null);
        $this->setFieldAutoIncrement(TUsersAbstract::FIELD_USERNAME, false);
        $this->setFieldUnsigned(TUsersAbstract::FIELD_USERNAME, false);
		$this->setFieldEncryptionDisabled(TUsersAbstract::FIELD_USERNAME);			          

        // real and fake password fields (they are technically the same)
        $iNoFakePasswordFields = $this->getNoFakePasswordFields();
        $bRealPasswordCreated = false;
        for ($iIndex = 0; $iIndex <= $iNoFakePasswordFields; $iIndex++)
        {
            $this->setFieldCopyProps(TUsersAbstract::FIELD_PASSWORDFAKEPREFIX.$iIndex, TUsersAbstract::FIELD_USERNAME);       
            $this->setFieldUnique(TUsersAbstract::FIELD_PASSWORDFAKEPREFIX.$iIndex, false);
            $this->setFieldIndexed(TUsersAbstract::FIELD_PASSWORDFAKEPREFIX.$iIndex, false);

            if (TUsersAbstract::FIELD_PASSWORDFAKEPREFIX.$iIndex == TUsersAbstract::FIELD_PASSWORDENCRYPTED)
                $bRealPasswordCreated = true;
        }  

        //create real password if it wasn't created earlier
        if (!$bRealPasswordCreated) //make sure we have a password-field
        {
            $this->setFieldCopyProps(TUsersAbstract::FIELD_PASSWORDENCRYPTED, TUsersAbstract::FIELD_USERNAME);       
            $this->setFieldUnique(TUsersAbstract::FIELD_PASSWORDENCRYPTED, false);
        }

        //enabled
        $this->setFieldDefaultValue(TUsersAbstract::FIELD_LOGINENABLED, false);
        $this->setFieldType(TUsersAbstract::FIELD_LOGINENABLED, CT_BOOL);
        $this->setFieldLength(TUsersAbstract::FIELD_LOGINENABLED, 0);
        $this->setFieldDecimalPrecision(TUsersAbstract::FIELD_LOGINENABLED, 0);
        $this->setFieldPrimaryKey(TUsersAbstract::FIELD_LOGINENABLED, false);
        $this->setFieldNullable(TUsersAbstract::FIELD_LOGINENABLED, false);
        $this->setFieldEnumValues(TUsersAbstract::FIELD_LOGINENABLED, null);
        $this->setFieldUnique(TUsersAbstract::FIELD_LOGINENABLED, false);
        $this->setFieldIndexed(TUsersAbstract::FIELD_LOGINENABLED, false);
        $this->setFieldForeignKeyClass(TUsersAbstract::FIELD_LOGINENABLED, null);
        $this->setFieldForeignKeyTable(TUsersAbstract::FIELD_LOGINENABLED, null);
        $this->setFieldForeignKeyField(TUsersAbstract::FIELD_LOGINENABLED, null);
        $this->setFieldForeignKeyJoin(TUsersAbstract::FIELD_LOGINENABLED, null);
        $this->setFieldForeignKeyActionOnUpdate(TUsersAbstract::FIELD_LOGINENABLED, null);
        $this->setFieldForeignKeyActionOnDelete(TUsersAbstract::FIELD_LOGINENABLED, null);
        $this->setFieldAutoIncrement(TUsersAbstract::FIELD_LOGINENABLED, false);
        $this->setFieldUnsigned(TUsersAbstract::FIELD_LOGINENABLED, false);	
		$this->setFieldEncryptionDisabled(TUsersAbstract::FIELD_LOGINENABLED);			                  

        //login exires
        $this->setFieldDefaultValue(TUsersAbstract::FIELD_LOGINEXPIRES, 0);
        $this->setFieldType(TUsersAbstract::FIELD_LOGINEXPIRES, CT_DATETIME);
        $this->setFieldLength(TUsersAbstract::FIELD_LOGINEXPIRES, 0);
        $this->setFieldDecimalPrecision(TUsersAbstract::FIELD_LOGINEXPIRES, 0);
        $this->setFieldPrimaryKey(TUsersAbstract::FIELD_LOGINEXPIRES, false);
        $this->setFieldNullable(TUsersAbstract::FIELD_LOGINEXPIRES, false);
        $this->setFieldEnumValues(TUsersAbstract::FIELD_LOGINEXPIRES, null);
        $this->setFieldUnique(TUsersAbstract::FIELD_LOGINEXPIRES, false);
        $this->setFieldIndexed(TUsersAbstract::FIELD_LOGINEXPIRES, false);
        $this->setFieldForeignKeyClass(TUsersAbstract::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyTable(TUsersAbstract::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyField(TUsersAbstract::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyJoin(TUsersAbstract::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyActionOnUpdate(TUsersAbstract::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyActionOnDelete(TUsersAbstract::FIELD_LOGINEXPIRES, null);
        $this->setFieldAutoIncrement(TUsersAbstract::FIELD_LOGINEXPIRES, false);
        $this->setFieldUnsigned(TUsersAbstract::FIELD_LOGINEXPIRES, false);	
		$this->setFieldEncryptionDisabled(TUsersAbstract::FIELD_LOGINEXPIRES);			                          

        //last login
        $this->setFieldCopyProps(TUsersAbstract::FIELD_LASTLOGIN, TUsersAbstract::FIELD_LOGINEXPIRES);       
        
        //email OLD
        // $this->setFieldCopyProps(TUsersAbstract::FIELD_EMAILADDRESS_OLD, TUsersAbstract::FIELD_USERNAME);       
        // $this->setFieldUnique(TUsersAbstract::FIELD_EMAILADDRESS_OLD, false);
        
        //2-way encrypted email address
        $this->setFieldDefaultValue(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, '');
        $this->setFieldType(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, CT_LONGTEXT);
        $this->setFieldLength(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, 0);
        $this->setFieldDecimalPrecision(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, 0);
        $this->setFieldPrimaryKey(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
        $this->setFieldNullable(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, true);
        $this->setFieldEnumValues(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldUnique(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
        $this->setFieldIndexed(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
        $this->setFieldForeignKeyClass(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyTable(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyField(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyJoin(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyActionOnUpdate(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyActionOnDelete(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldAutoIncrement(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
        $this->setFieldUnsigned(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
		$this->setFieldEncryptionCypher(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, ENCRYPTION_CYPHERMETHOD_AES256CBC);			                          
		$this->setFieldEncryptionDigest(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, ENCRYPTION_DIGESTALGORITHM_SHA512);			                          
		$this->setFieldEncryptionPassphrase(TUsersAbstract::FIELD_EMAILADDRESSENCRYPTED, TUsersAbstract::ENCRYPTION_EMAILADDRESS_PASSPHRASE);			                          


        //email fingerprint, so we can lookup the record based on email address
        $this->setFieldDefaultValue(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, '');
        $this->setFieldType(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, CT_VARCHAR);
        $this->setFieldLength(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, 100);
        $this->setFieldDecimalPrecision(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, 0);
        $this->setFieldPrimaryKey(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, false);
        $this->setFieldNullable(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, true);
        $this->setFieldEnumValues(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldUnique(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, false);
        $this->setFieldIndexed(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, true);//for quick lookup
        $this->setFieldForeignKeyClass(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyTable(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyField(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyJoin(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyActionOnUpdate(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyActionOnDelete(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldAutoIncrement(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, false);
        $this->setFieldUnsigned(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT, false);
		$this->setFieldEncryptionDisabled(TUsersAbstract::FIELD_EMAILADDRESSFINGERPRINT);			                                  

        //password expires
        $this->setFieldCopyProps(TUsersAbstract::FIELD_PASSWORDEXPIRES, TUsersAbstract::FIELD_LOGINEXPIRES);       
           
        //update permissions
        $this->setFieldCopyProps(TUsersAbstract::FIELD_UPDATEPERMISSIONS, TUsersAbstract::FIELD_LOGINENABLED);       

        //email token
        $this->setFieldDefaultValue(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, '');
        $this->setFieldType(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, CT_VARCHAR);
        $this->setFieldLength(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, 255);
        $this->setFieldDecimalPrecision(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, 0);
        $this->setFieldPrimaryKey(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, false);
        $this->setFieldNullable(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, true);
        $this->setFieldEnumValues(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, null);
        $this->setFieldUnique(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, false);
        $this->setFieldIndexed(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, true); //for quick lookup
        $this->setFieldForeignKeyClass(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, null);
        $this->setFieldForeignKeyTable(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, null);
        $this->setFieldForeignKeyField(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, null);
        $this->setFieldForeignKeyJoin(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, null);
        $this->setFieldForeignKeyActionOnUpdate(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, null);
        $this->setFieldForeignKeyActionOnDelete(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, null);
        $this->setFieldAutoIncrement(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, false);
        $this->setFieldUnsigned(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED, false);    
		$this->setFieldEncryptionDisabled(TUsersAbstract::FIELD_EMAILTOKENENCRYPTED);			                                              
      
        //email token expires
        $this->setFieldCopyProps(TUsersAbstract::FIELD_EMAILTOKENEXPIRES, TUsersAbstract::FIELD_LOGINEXPIRES);           

        //delete after
        $this->setFieldCopyProps(TUsersAbstract::FIELD_DELETEAFTER, TUsersAbstract::FIELD_LOGINEXPIRES);  
        
        //google id (openid)
        $this->setFieldDefaultValue(TSysCMSUsers::FIELD_GOOGLEID, '');
        $this->setFieldType(TSysCMSUsers::FIELD_GOOGLEID, CT_VARCHAR);
        $this->setFieldLength(TSysCMSUsers::FIELD_GOOGLEID, 100);
        $this->setFieldDecimalPrecision(TSysCMSUsers::FIELD_GOOGLEID, 0);
        $this->setFieldPrimaryKey(TSysCMSUsers::FIELD_GOOGLEID, false);
        $this->setFieldNullable(TSysCMSUsers::FIELD_GOOGLEID, false);
        $this->setFieldEnumValues(TSysCMSUsers::FIELD_GOOGLEID, null);
        $this->setFieldUnique(TSysCMSUsers::FIELD_GOOGLEID, false);//this field can be null, when no googleid is used (then it is not unique anymore)
        $this->setFieldIndexed(TSysCMSUsers::FIELD_GOOGLEID, true);//for quick lookup
        $this->setFieldForeignKeyClass(TSysCMSUsers::FIELD_GOOGLEID, null);
        $this->setFieldForeignKeyTable(TSysCMSUsers::FIELD_GOOGLEID, null);
        $this->setFieldForeignKeyField(TSysCMSUsers::FIELD_GOOGLEID, null);
        $this->setFieldForeignKeyJoin(TSysCMSUsers::FIELD_GOOGLEID, null);
        $this->setFieldForeignKeyActionOnUpdate(TSysCMSUsers::FIELD_GOOGLEID, null);
        $this->setFieldForeignKeyActionOnDelete(TSysCMSUsers::FIELD_GOOGLEID, null);
        $this->setFieldAutoIncrement(TSysCMSUsers::FIELD_GOOGLEID, false);
        $this->setFieldUnsigned(TSysCMSUsers::FIELD_GOOGLEID, false);     
        $this->setFieldEncryptionDisabled(TSysCMSUsers::FIELD_GOOGLEID);     

    }
            
	

    /**
     * use the auto-added id-field ?
     * @return bool
    */
    public function getTableUseIDField()
    {
        return true;	
    }


    /**
     * use the auto-added date-changed & date-created field ?
     * @return bool
    */
    public function getTableUseDateCreatedChangedField()
    {
        //out of security reasons disabled, otherwise you could see the time a user changed his password, which makes the time element in a password vulnerable
        return false; 
    }


    /**
     * use the checksum field ?
     * @return bool
    */
    public function getTableUseChecksumField()
    {
        return true;
    }

    /**
     * order field to switch order between records
    */
    public function getTableUseOrderField()
    {
        return false;
    }

    /**
     * use checkout for locking file for editing
    */
    public function getTableUseCheckout()
    {
        return true;
    }

    /**
     * use locking file for editing
    */
    public function getTableUseLock()
    {
        return true;
    }        

    /**
     * use image in your record?
     * if you don't want a small and large version, use this one
    */
    public function getTableUseImageFileLarge()
    {
        return false;
    }

    /**
     * use image in your record?
     * this is the small version
    */
    public function getTableUseImageFileThumbnail()
    {
        return false;
    }

    /**
     * use image in your record?
     * this is the large version
    */
    public function getTableUseImageFileMedium()
    {
        return false;
    }    

    /**
     * opvragen of records fysiek uit de databasetabel verwijderd moeten worden
     *
     * returnwaarde interpretatie:
     * true = fysiek verwijderen uit tabel
     * false = record-hidden-veld gebruiken om bij te houden of je het record kan zien in overzichten
     *
     * @return bool moeten records fysiek verwijderd worden ?
    */
    public function getTablePhysicalDeleteRecord()
    {
        return true;
    }




    /**
     * type of primary key field
     *
     * @return integer with constant CT_AUTOINCREMENT or CT_INTEGER or something else that is not recommendable
    */
    public function getTableIDFieldType()
    {
        return CT_AUTOINCREMENT;
    }


    /**
     * OVERSCHRIJF DOOR CHILD KLASSE ALS NODIG
     *
     * Voor de gui functies (zoals het maken van comboboxen) vraagt deze functie op
     * welke waarde er in het gui-element geplaatst moet worden, zoals de naam bijvoorbeeld
     *
     *
     * return '??? - functie niet overschreven door child klasse';
    */
    public function getGUIItemName()
    {
        return $this->get(TUsersAbstract::FIELD_USERNAME);
    }



    /**
     * for the automatic database table upgrade system to work this function
     * returns the version number of this class
     * The update system can compare the version of the database with the Business Logic
     *
     * default with no updates = 0
     * first update = 1, second 2 etc
     *
     * @return int
    */
    public function getVersion()
    {
        return 0;
    }
    
    /**
     * DEZE FUNCTIE MOET OVERGEERFD WORDEN DOOR DE CHILD KLASSE
     *
     * checken of alle benodigde waardes om op te slaan wel aanwezig zijn
     *
     * @return bool true=ok, false=not ok
    */
    public function areValuesValid()
    {   
        return true;
    }



    /**
     * update the table in the database
     * (may have been changes to fieldnames, fields added or removed etc)
     *
     * @param int $iFromVersion upgrade vanaf welke versie ?
     * @return bool is alles goed gegaan ? true = ok (of er is geen upgrade gedaan)
    */
    public function updateDBTable($iFromVersion)
    {
        return true;
    }	
    
    /**
     * use a second id that has no follow-up numbers?
     */
    public function getTableUseRandomID()
    {
        return true;
    }
    
    /**
     * is randomid field a primary key?
     */        
    public function getTableUseRandomIDAsPrimaryKey()
    {
       return false;
    }

	/**
	 * use a third character-based id that has no logically follow-up numbers?
	 * 
	 * a tertiary unique key (uniqueid) can be useful for security reasons like login sessions: you don't want to _POST the follow up numbers in url
	 */
	public function getTableUseUniqueID()
	{
		return true;
	}    
    
    /**
     * is this model a translation model?
     *
     * @return bool is this model a translation model?
     */
    public function getTableUseTranslationLanguageID()
    {
        return false;
    }    
    
    /****************************************************************************
     *              ABSTRACT METHODS
    ****************************************************************************/
    
    /**
     * how many fake password fields do you want
     */
    abstract public function getNoFakePasswordFields();
    
}

?>