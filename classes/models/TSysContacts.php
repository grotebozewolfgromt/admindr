<?php
namespace dr\classes\models;

use dr\classes\controllers\TControllerAbstract;
use dr\classes\models\TModel;

/**
 * This class represents contacts in an address book for the CMS
 *  
 * This is deemed a System-class because it is used by the system-accounts module
 * 
 */


class TSysContacts extends TContactsAbstract
{
	const FIELD_ISCLIENT = 'bIsClient';
	const FIELD_ISSUPPLIER = 'bIsSupplier';


	const VALUE_DEFAULT = 'No Name (default)';

	public function getIsClient()
	{
		return $this->get(TSysContacts::FIELD_ISCLIENT);
	}

	public function setIsClient($bIsClient)
	{
		$this->set(TSysContacts::FIELD_ISCLIENT, $bIsClient);
	}

	public function getIsSupplier()
	{
		return $this->get(TSysContacts::FIELD_ISSUPPLIER);
	}

	public function setIsSupplier($bIsSupplier)
	{
		$this->set(TSysContacts::FIELD_ISSUPPLIER, $bIsSupplier);
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
				$this->setCustomIdentifier(TSysContacts::VALUE_DEFAULT);
				$this->setIsClient(true);
				$this->setIsSupplier(true);
				
				//getting country id
				$objCountries = new TSysCountries();
				$objCountries->limitNone();
				$objCountries->loadFromDBByIsDefault();	
				$this->setBillingCountryID($objCountries->getID());
				$this->setDeliveryCountryID($objCountries->getID());
				unset($objCountries);

				if (!$this->saveToDB())
					error('error saving default contact on install TSysContacts');

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
		parent::defineTable();


		//is client
		$this->setFieldDefaultValue(TSysContacts::FIELD_ISCLIENT, false);
		$this->setFieldType(TSysContacts::FIELD_ISCLIENT, CT_BOOL);
		$this->setFieldLength(TSysContacts::FIELD_ISCLIENT, 0);
		$this->setFieldDecimalPrecision(TSysContacts::FIELD_ISCLIENT, 0);
		$this->setFieldPrimaryKey(TSysContacts::FIELD_ISCLIENT, false);
		$this->setFieldNullable(TSysContacts::FIELD_ISCLIENT, false);
		$this->setFieldEnumValues(TSysContacts::FIELD_ISCLIENT, null);
		$this->setFieldUnique(TSysContacts::FIELD_ISCLIENT, false); //it is annoying when you dont fill it in for 2 customers, you get an error
		$this->setFieldIndexed(TSysContacts::FIELD_ISCLIENT, false); 
		$this->setFieldForeignKeyClass(TSysContacts::FIELD_ISCLIENT, null);
		$this->setFieldForeignKeyTable(TSysContacts::FIELD_ISCLIENT, null);
		$this->setFieldForeignKeyField(TSysContacts::FIELD_ISCLIENT, null);
		$this->setFieldForeignKeyJoin(TSysContacts::FIELD_ISCLIENT, null);
		$this->setFieldForeignKeyActionOnUpdate(TSysContacts::FIELD_ISCLIENT, null);
		$this->setFieldForeignKeyActionOnDelete(TSysContacts::FIELD_ISCLIENT, null);
		$this->setFieldAutoIncrement(TSysContacts::FIELD_ISCLIENT, false);
		$this->setFieldUnsigned(TSysContacts::FIELD_ISCLIENT, true);
        $this->setFieldEncryptionDisabled(TSysContacts::FIELD_ISCLIENT);		


		//is supplier
		$this->setFieldCopyProps(TSysContacts::FIELD_ISSUPPLIER, TSysContacts::FIELD_ISCLIENT);


		// $this->defineTableDebug(TSysContacts::FIELD_BILLINGADDRESSMISC);
		// $this->defineTableDebug();
	}
	
		
	
	/**
	 * de child moet deze overerven
	 *
	 * @return string naam van de databasetabel
	*/
	public static function getTable()
	{
		return GLOBAL_DB_TABLEPREFIX.'SysContacts';
	}
	
	
	

			
}

?>