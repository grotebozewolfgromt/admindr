<?php

namespace dr\classes\models;

use dr\classes\models\TModel;


/**
 * currencies according to ISO 4217 standard (https://en.wikipedia.org/wiki/ISO_4217)
 * 
 * The currencyname is in English by default (just not to overcomplicate things)
 * 
 * THIS CLASS IS USED THROUGHOUT THE WHOLE FRAMEWORK!
 * 
 * created 20 october 2023
 * 20 oct 2023: TSysCurrencies: created
 * 25 oct 2023: TSysCurrencies: minor units renamed to decimal precision for consistency with the rest of the framework
 * 
 * @author Dennis Renirie
 */

class TSysCurrencies extends TModel
{
	const FIELD_CURRENCYNAME 		= 'sCurrencyName'; //currency name in english, i.e. Netherlands Antillean guilder
	const FIELD_CURRENCYSYMBOL 		= 'sCurrencySymbol'; //currency symbol i.e. €
	const FIELD_ISOALPHABETIC 		= 'sISOAlphabetic'; //alhabetical 3 letter ISO code, i.e. EUR
	const FIELD_ISONUMERIC 			= 'iISONumeric'; //mumerical ISO code, i.e. 973
	const FIELD_DECIMALPRECISION 	= 'iDecimalPrecision'; //official name: minor unit, the number of digits after decimal separator (currency decimals)
	const FIELD_ISSYSTEMDEFAULT 	= 'bIsSystemDefault';	//boolean: is this the default currency?
	const FIELD_ISVISIBLE 			= 'bIsVisible'; //preventing 400 locales are shown in html select boxes
	
	/**
	 * get name of the currency
	 * 
	 * @return string
	 */
	public function getCurrencyName()
	{
		return $this->get(TSysCurrencies::FIELD_CURRENCYNAME);
	}

	
	/**
	 * set name of the currency
	 * 
	 * @param string $sCurrency
	 */
	public function setCurrencyName($sCurrency)
	{
		$this->set(TSysCurrencies::FIELD_CURRENCYNAME, $sCurrency);
	}        
	
	/**
	 * get name of the currency
	 * 
	 * @return string
	 */
	public function getCurrencySymbol()
	{
		return $this->get(TSysCurrencies::FIELD_CURRENCYSYMBOL);
	}

	
	/**
	 * set name of the currency
	 * 
	 * @param string $sCurrency
	 */
	public function setCurrencySymbol($sSymbol)
	{
		$this->set(TSysCurrencies::FIELD_CURRENCYSYMBOL, $sSymbol);
	} 

	/**
	 * get alphabetical 3 digit ISO code
	 * 
	 * @return string
	 */
	public function getISOAlphabetical()
	{
		return $this->get(TSysCurrencies::FIELD_ISOALPHABETIC);
	}

	/**
	 * set alphabetical 3 digit ISO code
	 * 
	 * @param string $sCode
	 */
	public function setISOAlphabetical($sCode)
	{
		$this->set(TSysCurrencies::FIELD_ISOALPHABETIC, $sCode);
	}           

	
	/**
	 * get numeric ISO code
	 * 
	 * @return int
	 */
	public function getISONumeric()
	{
		return $this->get(TSysCurrencies::FIELD_ISONUMERIC);
	}

	/**
	 * set numeric ISO code
	 * 
	 * @param int $iCode
	 */
	public function setISONumeric($iCode)
	{
		$this->set(TSysCurrencies::FIELD_ISONUMERIC, $iCode);
	}   

	/**
	 * get number of digits after decimal separator
	 * (currency decimals)
	 * 
	 * @return int
	 */
	public function getDecimalPrecision()
	{
		return $this->get(TSysCurrencies::FIELD_DECIMALPRECISION);
	}

	/**
	 * set number of digits after decimal separator 
	 * (currency decimals)
	 * 
	 * @param int $iCode
	 */
	public function setDecimalPrecision($iCode)
	{
		$this->set(TSysCurrencies::FIELD_DECIMALPRECISION, $iCode);
	}  	


	public function getIsDefault()
	{
		return  $this->get(TSysCurrencies::FIELD_ISSYSTEMDEFAULT);
	}
	
	public function setIsDefault($bDefault)
	{
		$this->set(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, $bDefault);
	} 	

	public function getIsVisible()
	{
		return  $this->get(TSysCurrencies::FIELD_ISVISIBLE);
	}
	
	public function setIsVisible($bVisible)
	{
		$this->set(TSysCurrencies::FIELD_ISVISIBLE, $bVisible);
	} 		


	/**
	 * load default currency
	 * 
	 * @return boolean load ok?
	 */
	public function loadFromDBByIsDefault()
	{
		$this->clear();
		$this->find(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, true);
		return $this->loadFromDB();
	}	


	/**
	 * load currency by ISO alphabetical code
	 * 
	 * @param string $sISOCode
	 * @return boolean load ok?
	 */
	public function loadFromDBByISOAlphabetical($sISOCode)
	{
		$this->clear();
		$this->find(TSysCurrencies::FIELD_ISOALPHABETIC, $sISOCode);
		return $this->loadFromDB();
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
		
		$sCSV = "Euro,€,EUR,978,2,1
		United States Dollar,$,USD,840,2,0
		Pound Sterling,£,GBP,826,2,0";
	
	
		if ($bSuccess)
		{
			$this->limitOne();
			$this->loadFromDB(false);
			if ($this->count() == 0) //only add when table is empty
			{
				$this->clear();
				
				$arrLines = explode("\n", $sCSV);
				
				foreach ($arrLines as $sLine)
				{
					$arrColumns = explode(',', $sLine);
					$this->newRecord();
					$this->set(TSysCurrencies::FIELD_CURRENCYNAME, trim($arrColumns[0])); //the csv above has tab-chars in it, trim them
					$this->set(TSysCurrencies::FIELD_CURRENCYSYMBOL, $arrColumns[1]); 
					$this->set(TSysCurrencies::FIELD_ISOALPHABETIC, $arrColumns[2]);
					$this->set(TSysCurrencies::FIELD_ISONUMERIC, $arrColumns[3]);
					$this->set(TSysCurrencies::FIELD_DECIMALPRECISION, $arrColumns[4]);
					if ($arrColumns[5] == '1')
						$this->set(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, true);
					$this->set(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, true);
					

					if (!$this->saveToDB())
						error('error saving currencies on install: '. $arrColumns[0]);
				}
			}
		}
		
		return $bSuccess;
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
		//currency name
		$this->setFieldDefaultValue(TSysCurrencies::FIELD_CURRENCYNAME, '');
		$this->setFieldType(TSysCurrencies::FIELD_CURRENCYNAME, CT_VARCHAR);
		$this->setFieldLength(TSysCurrencies::FIELD_CURRENCYNAME, 100);
		$this->setFieldDecimalPrecision(TSysCurrencies::FIELD_CURRENCYNAME, 0);
		$this->setFieldPrimaryKey(TSysCurrencies::FIELD_CURRENCYNAME, false);
		$this->setFieldNullable(TSysCurrencies::FIELD_CURRENCYNAME, false);
		$this->setFieldEnumValues(TSysCurrencies::FIELD_CURRENCYNAME, null);
		$this->setFieldUnique(TSysCurrencies::FIELD_CURRENCYNAME, true);
		$this->setFieldIndexed(TSysCurrencies::FIELD_CURRENCYNAME, false);
		$this->setFieldForeignKeyClass(TSysCurrencies::FIELD_CURRENCYNAME, null);
		$this->setFieldForeignKeyTable(TSysCurrencies::FIELD_CURRENCYNAME, null);
		$this->setFieldForeignKeyField(TSysCurrencies::FIELD_CURRENCYNAME, null);
		$this->setFieldForeignKeyJoin(TSysCurrencies::FIELD_CURRENCYNAME, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCurrencies::FIELD_CURRENCYNAME, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCurrencies::FIELD_CURRENCYNAME, null);
		$this->setFieldAutoIncrement(TSysCurrencies::FIELD_CURRENCYNAME, false);
		$this->setFieldUnsigned(TSysCurrencies::FIELD_CURRENCYNAME, false);
		$this->setFieldEncryptionDisabled(TSysCurrencies::FIELD_CURRENCYNAME);									


		//currency symbol
		$this->setFieldDefaultValue(TSysCurrencies::FIELD_CURRENCYSYMBOL, '');
		$this->setFieldType(TSysCurrencies::FIELD_CURRENCYSYMBOL, CT_VARCHAR);
		$this->setFieldLength(TSysCurrencies::FIELD_CURRENCYSYMBOL, 5);
		$this->setFieldDecimalPrecision(TSysCurrencies::FIELD_CURRENCYSYMBOL, 0);
		$this->setFieldPrimaryKey(TSysCurrencies::FIELD_CURRENCYSYMBOL, false);
		$this->setFieldNullable(TSysCurrencies::FIELD_CURRENCYSYMBOL, false);
		$this->setFieldEnumValues(TSysCurrencies::FIELD_CURRENCYSYMBOL, null);
		$this->setFieldUnique(TSysCurrencies::FIELD_CURRENCYSYMBOL, false);
		$this->setFieldIndexed(TSysCurrencies::FIELD_CURRENCYSYMBOL, false);
		$this->setFieldForeignKeyClass(TSysCurrencies::FIELD_CURRENCYSYMBOL, null);
		$this->setFieldForeignKeyTable(TSysCurrencies::FIELD_CURRENCYSYMBOL, null);
		$this->setFieldForeignKeyField(TSysCurrencies::FIELD_CURRENCYSYMBOL, null);
		$this->setFieldForeignKeyJoin(TSysCurrencies::FIELD_CURRENCYSYMBOL, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCurrencies::FIELD_CURRENCYSYMBOL, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCurrencies::FIELD_CURRENCYSYMBOL, null);
		$this->setFieldAutoIncrement(TSysCurrencies::FIELD_CURRENCYSYMBOL, false);
		$this->setFieldUnsigned(TSysCurrencies::FIELD_CURRENCYSYMBOL, false);
		$this->setFieldEncryptionDisabled(TSysCurrencies::FIELD_CURRENCYSYMBOL);	

		
		//iso alphabetic code
		$this->setFieldDefaultValue(TSysCurrencies::FIELD_ISOALPHABETIC, '');
		$this->setFieldType(TSysCurrencies::FIELD_ISOALPHABETIC, CT_VARCHAR);
		$this->setFieldLength(TSysCurrencies::FIELD_ISOALPHABETIC, 3);
		$this->setFieldDecimalPrecision(TSysCurrencies::FIELD_ISOALPHABETIC, 0);
		$this->setFieldPrimaryKey(TSysCurrencies::FIELD_ISOALPHABETIC, false);
		$this->setFieldNullable(TSysCurrencies::FIELD_ISOALPHABETIC, false);
		$this->setFieldEnumValues(TSysCurrencies::FIELD_ISOALPHABETIC, null);
		$this->setFieldUnique(TSysCurrencies::FIELD_ISOALPHABETIC, true);
		$this->setFieldIndexed(TSysCurrencies::FIELD_ISOALPHABETIC, false); //it is already unique
		$this->setFieldForeignKeyClass(TSysCurrencies::FIELD_ISOALPHABETIC, null);
		$this->setFieldForeignKeyTable(TSysCurrencies::FIELD_ISOALPHABETIC, null);
		$this->setFieldForeignKeyField(TSysCurrencies::FIELD_ISOALPHABETIC, null);
		$this->setFieldForeignKeyJoin(TSysCurrencies::FIELD_ISOALPHABETIC, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCurrencies::FIELD_ISOALPHABETIC, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCurrencies::FIELD_ISOALPHABETIC, null);
		$this->setFieldAutoIncrement(TSysCurrencies::FIELD_ISOALPHABETIC, false);
		$this->setFieldUnsigned(TSysCurrencies::FIELD_ISOALPHABETIC, false);
		$this->setFieldEncryptionDisabled(TSysCurrencies::FIELD_ISOALPHABETIC);									


		//iso numeric code
		$this->setFieldDefaultValue(TSysCurrencies::FIELD_ISONUMERIC, 0);
		$this->setFieldType(TSysCurrencies::FIELD_ISONUMERIC, CT_INTEGER);
		$this->setFieldLength(TSysCurrencies::FIELD_ISONUMERIC, 0);
		$this->setFieldDecimalPrecision(TSysCurrencies::FIELD_ISONUMERIC, 0);
		$this->setFieldPrimaryKey(TSysCurrencies::FIELD_ISONUMERIC, false);
		$this->setFieldNullable(TSysCurrencies::FIELD_ISONUMERIC, false);
		$this->setFieldEnumValues(TSysCurrencies::FIELD_ISONUMERIC, null);
		$this->setFieldUnique(TSysCurrencies::FIELD_ISONUMERIC, true);
		$this->setFieldIndexed(TSysCurrencies::FIELD_ISONUMERIC, false); //it is already unique
		$this->setFieldForeignKeyClass(TSysCurrencies::FIELD_ISONUMERIC, null);
		$this->setFieldForeignKeyTable(TSysCurrencies::FIELD_ISONUMERIC, null);
		$this->setFieldForeignKeyField(TSysCurrencies::FIELD_ISONUMERIC, null);
		$this->setFieldForeignKeyJoin(TSysCurrencies::FIELD_ISONUMERIC, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCurrencies::FIELD_ISONUMERIC, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCurrencies::FIELD_ISONUMERIC, null);
		$this->setFieldAutoIncrement(TSysCurrencies::FIELD_ISONUMERIC, false);
		$this->setFieldUnsigned(TSysCurrencies::FIELD_ISONUMERIC, true);
		$this->setFieldEncryptionDisabled(TSysCurrencies::FIELD_ISONUMERIC);	


		//decimal precision, aka minor unit: number of digits after decimal separator
		$this->setFieldDefaultValue(TSysCurrencies::FIELD_DECIMALPRECISION, 0);
		$this->setFieldType(TSysCurrencies::FIELD_DECIMALPRECISION, CT_INTEGER);
		$this->setFieldLength(TSysCurrencies::FIELD_DECIMALPRECISION, 0);
		$this->setFieldDecimalPrecision(TSysCurrencies::FIELD_DECIMALPRECISION, 0);
		$this->setFieldPrimaryKey(TSysCurrencies::FIELD_DECIMALPRECISION, false);
		$this->setFieldNullable(TSysCurrencies::FIELD_DECIMALPRECISION, false);
		$this->setFieldEnumValues(TSysCurrencies::FIELD_DECIMALPRECISION, null);
		$this->setFieldUnique(TSysCurrencies::FIELD_DECIMALPRECISION, false);
		$this->setFieldIndexed(TSysCurrencies::FIELD_DECIMALPRECISION, false);
		$this->setFieldForeignKeyClass(TSysCurrencies::FIELD_DECIMALPRECISION, null);
		$this->setFieldForeignKeyTable(TSysCurrencies::FIELD_DECIMALPRECISION, null);
		$this->setFieldForeignKeyField(TSysCurrencies::FIELD_DECIMALPRECISION, null);
		$this->setFieldForeignKeyJoin(TSysCurrencies::FIELD_DECIMALPRECISION, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCurrencies::FIELD_DECIMALPRECISION, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCurrencies::FIELD_DECIMALPRECISION, null);
		$this->setFieldAutoIncrement(TSysCurrencies::FIELD_DECIMALPRECISION, false);
		$this->setFieldUnsigned(TSysCurrencies::FIELD_DECIMALPRECISION, true);
		$this->setFieldEncryptionDisabled(TSysCurrencies::FIELD_DECIMALPRECISION);	


		//default currency
		$this->setFieldDefaultValue(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldType(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, CT_BOOL);
		$this->setFieldLength(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, 0);
		$this->setFieldDecimalPrecision(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, 0);
		$this->setFieldPrimaryKey(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldNullable(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldEnumValues(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldUnique(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldIndexed(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldForeignKeyClass(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyTable(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyField(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyJoin(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldForeignKeyActionOnDelete(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, null);
		$this->setFieldAutoIncrement(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, false);
		$this->setFieldUnsigned(TSysCurrencies::FIELD_ISSYSTEMDEFAULT, false);	
        $this->setFieldEncryptionDisabled(TSysCurrencies::FIELD_ISSYSTEMDEFAULT);						


		//is visible
		$this->setFieldCopyProps(TSysCurrencies::FIELD_ISVISIBLE, TSysCurrencies::FIELD_ISSYSTEMDEFAULT);
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
		return array(TSysCurrencies::FIELD_CURRENCYNAME, TSysCurrencies::FIELD_CURRENCYSYMBOL, TSysCurrencies::FIELD_ISOALPHABETIC, TSysCurrencies::FIELD_ISONUMERIC, TSysCurrencies::FIELD_DECIMALPRECISION, TSysCurrencies::FIELD_ISSYSTEMDEFAULT, TSysCurrencies::FIELD_ISVISIBLE);
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
		return true;
	}
	
	/**
	 * use checkout for locking file for editing
	*/
	public function getTableUseCheckout()
	{
		return false;
	}
		
	/**
	 * use record locking to prevent record editing
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
		return GLOBAL_DB_TABLEPREFIX.'SysCurrencies';
	}
	
	
	
	/**
	 * OVERRIDE BY CHILD CLASS IF necessary
	 *
	 * Voor de gui functies (zoals het maken van comboboxen) vraagt deze functie op
	 * welke waarde er in het gui-element geplaatst moet worden, zoals de naam bijvoorbeeld
	 *
	 *
	 * return '??? - functie niet overschreven door child klasse';
	*/
	public function getGUIItemName()
	{
		return $this->get(TSysCurrencies::FIELD_ISOALPHABETIC).' - '.$this->get(TSysCurrencies::FIELD_CURRENCYSYMBOL.'');
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
		return 'gekkiegerrit'.$this->get(TSysCurrencies::FIELD_ISOALPHABETIC).'isnietgek'.$this->get(TSysCurrencies::FIELD_ISONUMERIC).boolToStr($this->get(TSysCountries::FIELD_ISSYSTEMDEFAULT));
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