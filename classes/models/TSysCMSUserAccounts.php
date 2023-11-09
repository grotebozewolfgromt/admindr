<?php
namespace dr\classes\models;

use dr\classes\models\TModel;
use dr\classes\types\TDateTime;

/**
 * This class represents User accounts in the CMS
 * An account can have 0, 1 or more users
 * 
 * The FIELD_DELETEAFTER field is used to delete user accounts automatically with a cronjob.
 * This can be useful for example for trial accounts.
 * A user creates an account, doesn't continue using the service, so they get deleted automatically
 * 
 */
class TSysCMSUserAccounts extends TModel
{
	const FIELD_CUSTOMIDENTIFIER 	= 'sCustomIdentifier'; //how to identify the customer? Can be username, email address etc. Be careful not to use something privacy intensive. we can't encrypt it because it needs to be searchable
	const FIELD_CONTACTID 			= 'iContactID'; //id from contacts module
	const FIELD_LOGINENABLED        = 'bLoEn'; 
	const FIELD_LOGINEXPIRES      	= 'dtLoEx'; //date on which the user can't log in anymore
	const FIELD_DELETEAFTER         = 'dtDeAf';//expiration date for account. after this date the account will be deleted by a cron job

	const DEFAULT_ACCOUNTIDENTIFIER = '[DEFAULT ACCOUNT]';


	public function getCustomIdentifier()
	{
		return $this->get(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER);
	}

	public function setCustomIdentifier($sID)
	{
		$this->set(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, $sID);
	}

	
	public function getContactID()
	{
		return $this->get(TSysCMSUserAccounts::FIELD_CONTACTID);
	}

	public function setContactID($iID)
	{
		$this->set(TSysCMSUserAccounts::FIELD_CONTACTID, $iID);
	}


    /**
     * is the user able to log in?
     * 
     * @return boolean
     */
    public function getLoginEnabled()
    {
        return $this->get(TSysCMSUserAccounts::FIELD_LOGINENABLED);
    }

    /**
     * set if the user able to log in
     * 
     * @param boolean $bAllowed
     */
    public function setLoginEnabled($bAllowed)
    {
        $this->set(TSysCMSUserAccounts::FIELD_LOGINENABLED, $bAllowed);
    }           

    /**
     * default the datetime object is null, which results in NO EXPIRATION
     * 
     * Can be used for automatic payments, if you do not pay, you can't login
     *
     * @param TDateTime $objDateTime when null then an invalid date (timestamp 0) will be set, so no expiration
     */
    public function setLoginExpires($objDateTime = null)
    {
        $this->setTDateTime(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, $objDateTime);
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
        return $this->get(TSysCMSUserAccounts::FIELD_LOGINEXPIRES);
    }        

    /**
	 *  when does the account need to be deleted?
     * default the datetime object is null, which results in NO EXPIRATION
     * 
     * Can be used for automatic payments, if you do not pay, you can't login
     *
     * @param TDateTime $objDateTime when null then an invalid date (timestamp 0) will be set, so no expiration
     */
    public function setDeleteAfter($objDateTime = null)
    {
        $this->setTDateTime(TSysCMSUserAccounts::FIELD_DELETEAFTER, $objDateTime);
    }        

    /**
     * when does the account need to be deleted?
     * can return an object with timestamp 0 when NO EXPIRATION date set
     * 
     * Can be used for automatic payments, if you do not pay, you can't login
     * 
     * @return TDateTime
     */
    public function getDeleteAfter()
    {
        return $this->get(TSysCMSUserAccounts::FIELD_DELETEAFTER);
    }        

	
	/**
	 * delete all useraccounts from datase that are scheduled for deletion
	 * THIS INCLUDES DELETING ALL USERS IN THOSE ACCOUNTS!!!!
	 */
	public function deleteDBUserAccountsExpired()
	{
        $bResult = true;
        $objNow = new TDateTime();
        $objNow->setNow();
        $objZero = new TDateTime();
		$objUsers = null;

		//request all user accounts that are expired
		$objTempAccounts = $this->getCopy();
		$objTempAccounts->find(TSysCMSUserAccounts::FIELD_DELETEAFTER, $objNow, COMPARISON_OPERATOR_LESS_THAN);
        $objTempAccounts->find(TSysCMSUserAccounts::FIELD_DELETEAFTER, $objZero, COMPARISON_OPERATOR_GREATER_THAN);

		if ($objTempAccounts->loadFromDB())
        {

            while($objTempAccounts->next())
            {
				//now delete useraccounts themselves (should also delete users in account because of cascading delete)
				if (!$objTempAccounts->deleteFromDB(true, true))
				{
					error_log(__CLASS__.': '.__FUNCTION__.': '.__LINE__.' delete useraccounts from db in cronjob via DELETEAFTER-field');
					logCronjob(__CLASS__.': '.__FUNCTION__.': '.__LINE__, ' delete useraccounts from db in cronjob via DELETEAFTER-field');

                    $bResult = false;
				}
            }
        }

        unset($objNow);
        unset($objZero);
        unset($objTempUsers);

        return $bResult;    
	}


	/**
	 * this function creates table in database and calls all foreign key classes to do the same
	 * 
	 * the $arrPreviousDependenciesModelClasses prevents a endless loop by storing all the classnames that are already installed
	 *
	 * @param array $arrPreviousDependenciesModelClasses with classnames. 
	 * @return bool success?
	 */
	public function install($arrPreviousDependenciesModelClasses = null)
	{
		$bSuccess = parent::install($arrPreviousDependenciesModelClasses);

		if ($bSuccess)
		{
			$this->limitOne();
			$this->loadFromDB(false);
			if ($this->count() == 0) //only add when table is empty
			{
				$this->clear();
				
				$this->newRecord();
				$this->setCustomIdentifier(TSysCMSUserAccounts::DEFAULT_ACCOUNTIDENTIFIER);

				$objContacts = new TSysContacts();
				$objContacts->limitOne();
				$objContacts->loadFromDB();
				$this->setContactID($objContacts->getID());
				unset($objContacts);

				$this->setLoginEnabled(true);
				$this->setLoginExpires();
				$this->setDeleteAfter();
					
				if (!$this->saveToDB())
					error('error saving language on install CMS User account');				
			}
		}
		
		return $bSuccess;
	}
	

    /**
     * load account from database with identifier 
     * 
     * @param string $sID
     */
    public function loadFromDBByCustomIdentifier($sID)
    {
        $bResult = false;
        $this->find(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, $sID);
        if ($this->loadFromDB(false))
            $bResult = true;
        $this->newQuery();
        return $bResult;
    }
    
	
	/**
	 * This function is called in the constructor and the clear() function
	 * this is used to define default values for fields
         * 
	 * initialize values
	 */
	public function initRecord()
	{}
	
	
	
	/**
	 * defines the fields in the tables
	 * i.e. types, default values, enum values, referenced tables etc
	*/
	public function defineTable()
	{
            
		//custom identifier
		$this->setFieldDefaultValue(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, '');
		$this->setFieldType(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, CT_VARCHAR);
		$this->setFieldLength(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, 50);
		$this->setFieldDecimalPrecision(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, 0);
		$this->setFieldPrimaryKey(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, false);
		$this->setFieldNullable(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, true);
		$this->setFieldEnumValues(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldUnique(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, false); //false, because it's anoying when 2 accounts have no identifier and it errors because of it		
		$this->setFieldIndexed(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, true);
		$this->setFieldForeignKeyClass(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyTable(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyField(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyJoin(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldAutoIncrement(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, false);
		$this->setFieldUnsigned(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, false);
        $this->setFieldEncryptionDisabled(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER);		

		//contact id
		$this->setFieldDefaultValue(TSysCMSUserAccounts::FIELD_CONTACTID, 0);
		$this->setFieldType(TSysCMSUserAccounts::FIELD_CONTACTID, CT_INTEGER64);
		$this->setFieldLength(TSysCMSUserAccounts::FIELD_CONTACTID, 0);
		$this->setFieldDecimalPrecision(TSysCMSUserAccounts::FIELD_CONTACTID, 0);
		$this->setFieldPrimaryKey(TSysCMSUserAccounts::FIELD_CONTACTID, false);
		$this->setFieldNullable(TSysCMSUserAccounts::FIELD_CONTACTID, false);
		$this->setFieldEnumValues(TSysCMSUserAccounts::FIELD_CONTACTID, null);
		$this->setFieldUnique(TSysCMSUserAccounts::FIELD_CONTACTID, false); 
		$this->setFieldIndexed(TSysCMSUserAccounts::FIELD_CONTACTID, true); 
		$this->setFieldForeignKeyClass(TSysCMSUserAccounts::FIELD_CONTACTID, TSysContacts::class);
		$this->setFieldForeignKeyTable(TSysCMSUserAccounts::FIELD_CONTACTID, TSysContacts::getTable());
		$this->setFieldForeignKeyField(TSysCMSUserAccounts::FIELD_CONTACTID, TModel::FIELD_ID);
		$this->setFieldForeignKeyJoin(TSysCMSUserAccounts::FIELD_CONTACTID);
		$this->setFieldForeignKeyActionOnUpdate(TSysCMSUserAccounts::FIELD_CONTACTID, TModel::FOREIGNKEY_REFERENCE_CASCADE);
		$this->setFieldForeignKeyActionOnDelete(TSysCMSUserAccounts::FIELD_CONTACTID, TModel::FOREIGNKEY_REFERENCE_RESTRICT); 
		$this->setFieldAutoIncrement(TSysCMSUserAccounts::FIELD_CONTACTID, false);
		$this->setFieldUnsigned(TSysCMSUserAccounts::FIELD_CONTACTID, true);
        $this->setFieldEncryptionDisabled(TSysCMSUserAccounts::FIELD_CONTACTID);		
		
		//login enabled
		$this->setFieldDefaultValue(TSysCMSUserAccounts::FIELD_LOGINENABLED, false);
		$this->setFieldType(TSysCMSUserAccounts::FIELD_LOGINENABLED, CT_BOOL);
		$this->setFieldLength(TSysCMSUserAccounts::FIELD_LOGINENABLED, 0);
		$this->setFieldDecimalPrecision(TSysCMSUserAccounts::FIELD_LOGINENABLED, 0);
		$this->setFieldPrimaryKey(TSysCMSUserAccounts::FIELD_LOGINENABLED, false);
		$this->setFieldNullable(TSysCMSUserAccounts::FIELD_LOGINENABLED, false);
		$this->setFieldEnumValues(TSysCMSUserAccounts::FIELD_LOGINENABLED, null);
		$this->setFieldUnique(TSysCMSUserAccounts::FIELD_LOGINENABLED, false);
		$this->setFieldIndexed(TSysCMSUserAccounts::FIELD_LOGINENABLED, false);
		$this->setFieldForeignKeyClass(TSysCMSUserAccounts::FIELD_LOGINENABLED, null);
		$this->setFieldForeignKeyTable(TSysCMSUserAccounts::FIELD_LOGINENABLED, null);
		$this->setFieldForeignKeyField(TSysCMSUserAccounts::FIELD_LOGINENABLED, null);
		$this->setFieldForeignKeyJoin(TSysCMSUserAccounts::FIELD_LOGINENABLED, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCMSUserAccounts::FIELD_LOGINENABLED, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCMSUserAccounts::FIELD_LOGINENABLED, null);
		$this->setFieldAutoIncrement(TSysCMSUserAccounts::FIELD_LOGINENABLED, false);
		$this->setFieldUnsigned(TSysCMSUserAccounts::FIELD_LOGINENABLED, false);	
        $this->setFieldEncryptionDisabled(TSysCMSUserAccounts::FIELD_LOGINENABLED);				
    
        //login exires
        $this->setFieldDefaultValue(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, 0);
        $this->setFieldType(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, CT_DATETIME);
        $this->setFieldLength(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, 0);
        $this->setFieldDecimalPrecision(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, 0);
        $this->setFieldPrimaryKey(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, false);
        $this->setFieldNullable(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, false);
        $this->setFieldEnumValues(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, null);
        $this->setFieldUnique(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, false);
        $this->setFieldIndexed(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, false);
        $this->setFieldForeignKeyClass(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyTable(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyField(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyJoin(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyActionOnUpdate(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, null);
        $this->setFieldForeignKeyActionOnDelete(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, null);
        $this->setFieldAutoIncrement(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, false);
        $this->setFieldUnsigned(TSysCMSUserAccounts::FIELD_LOGINEXPIRES, false);	
		$this->setFieldEncryptionDisabled(TSysCMSUserAccounts::FIELD_LOGINEXPIRES);			                          

        //delete after
        $this->setFieldCopyProps(TSysCMSUserAccounts::FIELD_DELETEAFTER, TSysCMSUserAccounts::FIELD_LOGINEXPIRES);  	                                  

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
		return array(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER, TSysCMSUserAccounts::FIELD_CONTACTID);
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
		return true;
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
		return false;
	}
        
	/**
	 * use locking file for editing
	*/
	public function getTableUseLock()
	{
		return false;
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
	 * de child moet deze overerven
	 *
	 * @return string naam van de databasetabel
	*/
	public static function getTable()
	{
		return GLOBAL_DB_TABLEPREFIX.'SysCMSUserAccounts';
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
		return $this->get(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER);
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
		return 'henkiw2'.$this->get(TSysCMSUserAccounts::FIELD_CUSTOMIDENTIFIER).'_'.boolToStr($this->getLoginEnabled(), true).$this->getLoginExpires()->getTimestamp().$this->getDeleteAfter()->getTimestamp().$this->getContactID();
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
			return false;
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
		return false;
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
		
			
}

?>