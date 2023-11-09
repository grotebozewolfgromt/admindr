<?php

namespace dr\classes\models;

use dr\classes\models\TModel;
use dr\classes\patterns\TModuleAbstract;


/**
 * 10jan2020: TSysModulesCategories: default system category added
 */

class TSysModulesCategories extends TModel
{
	const FIELD_NAME = 'sName';
	
	
	public function setNameCategory($sName)
	{
		$this->set(TSysModulesCategories::FIELD_NAME, $sName);
	}
        
	public function getNameCategory()
	{
            return $this->get(TSysModulesCategories::FIELD_NAME);
	}
        
	/**
	 * de child moet deze overerven
	 *
	 * @return string naam van de databasetabel
	*/
	public static function getTable()
	{
		return GLOBAL_DB_TABLEPREFIX.'SysModuleCategories';
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
		//internal name
		$this->setFieldDefaultValue(TSysModulesCategories::FIELD_NAME, '');
		$this->setFieldType(TSysModulesCategories::FIELD_NAME, CT_VARCHAR);
		$this->setFieldLength(TSysModulesCategories::FIELD_NAME, 100);
		$this->setFieldDecimalPrecision(TSysModulesCategories::FIELD_NAME, 0);
		$this->setFieldPrimaryKey(TSysModulesCategories::FIELD_NAME, false);
		$this->setFieldNullable(TSysModulesCategories::FIELD_NAME, false);
		$this->setFieldEnumValues(TSysModulesCategories::FIELD_NAME, null);
		$this->setFieldUnique(TSysModulesCategories::FIELD_NAME, true);
		$this->setFieldIndexed(TSysModulesCategories::FIELD_NAME, false);
		$this->setFieldForeignKeyClass(TSysModulesCategories::FIELD_NAME, null);
		$this->setFieldForeignKeyTable(TSysModulesCategories::FIELD_NAME, null);
		$this->setFieldForeignKeyField(TSysModulesCategories::FIELD_NAME, null);
		$this->setFieldForeignKeyJoin(TSysModulesCategories::FIELD_NAME, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysModulesCategories::FIELD_NAME, null);
		$this->setFieldForeignKeyActionOnDelete(TSysModulesCategories::FIELD_NAME, null);
		$this->setFieldAutoIncrement(TSysModulesCategories::FIELD_NAME, false);
		$this->setFieldUnsigned(TSysModulesCategories::FIELD_NAME, false);
        $this->setFieldEncryptionDisabled(TSysModulesCategories::FIELD_NAME);				
					
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
		return array(TSysModulesCategories::FIELD_NAME);
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
		return false;
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
		return true;
	}
		
	/**
	 * use record locking to prevent record editing
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
		return $this->get(TSysModulesCategories::FIELD_NAME);
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
		return 'categorychecksumiseenbeetjeraar'.$this->get(TSysModulesCategories::FIELD_NAME).'duswedoenmaareenbeetjewat'.$this->get(TSysModulesCategories::FIELD_NAME);
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
         * additions to the install procedure
         * 
         * @param array $arrPreviousDependenciesModelClasses
         */
        public function install($arrPreviousDependenciesModelClasses = null)
        {
            $bSuccess = true;
            $bSuccess = parent::install($arrPreviousDependenciesModelClasses);
            
            //==check if at least one category exists, if not, then add it
            $this->newQuery();
            $this->clear();    
            $this->limitOne(); //we need just one record to be returned
            if (!$this->loadFromDB())
            {
                error(__CLASS__.': loadFromDB() failed in install()');
                return false;
            }
            
            //==if no category exists, add a default one
            if($this->count() == 0)
            {
                //system
                $this->clear();
                $this->newRecord();
                $this->setNameCategory(TModuleAbstract::CATEGORYDEFAULT_SYSTEM); //default category name
                if (!$this->saveToDB())
                {
                    error(__CLASS__.': error saving default module category:'.TModuleAbstract::CATEGORYDEFAULT_SYSTEM);
                    return false;
                }    
                
                //website
                $this->clear();
                $this->newRecord();
                $this->setNameCategory(TModuleAbstract::CATEGORYDEFAULT_WEBSITE); //default category name
                if (!$this->saveToDB())
                {
                    error(__CLASS__.': error saving default module category:'. TModuleAbstract::CATEGORYDEFAULT_WEBSITE);
                    return false;
				}     
				
                //tools
                $this->clear();
                $this->newRecord();
                $this->setNameCategory(TModuleAbstract::CATEGORYDEFAULT_TOOLS); //default category name
                if (!$this->saveToDB())
                {
                    error(__CLASS__.': error saving default module category:'. TModuleAbstract::CATEGORYDEFAULT_TOOLS);
                    return false;
                }   				
            }
                
            return $bSuccess;
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