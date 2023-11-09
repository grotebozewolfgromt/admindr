<?php

namespace dr\modules\Mod_Transactions\models;

use dr\classes\models\TModel;
use dr\classes\types\TCurrency;
use dr\classes\types\TDateTime;


/**
 * Transaction Types
 * 
 * TTransactionsTypes
 * Transaction types make the distiction wether a transaction is an invoice, order, offer etc
 * 
 * DEFAULTS:
 * -default: 			the one that is selected by default in <SELECT> box
 * -order-default: 		if an order is needed (for example webshop), this type is used
 * -invoice-default:	if an invoice is needed (for example webshop), this type is used
 * 
 * COLOR:
 * The color is used for visual identification for the user.
 * for example: blue = invoice, green = order etc.
 * This helps to prevent mistakes by confusing different transaction types
 * 
 * 
 * created 3 november 2023
 * 3 nov 2023: TTransactionsTypes: created
 * 
 * @author Dennis Renirie
 */

class TTransactionsTypes extends TModel
{
	const FIELD_NAME					= 'sName'; //name of the type 
	const FIELD_ISSTOCK					= 'bIsStock'; //is stock being added or subtracted for this transaction? Invoices are, orders and offers are not
	const FIELD_ISFINANCIAL				= 'bIsFinancial'; //is this a financial transaction? Invoices are, orders and offers are not
	const FIELD_ISDEFAULTSELECTED		= 'bIsDefaultSelected'; //When <SELECT> box shown, is this the transaction-type selected by default?
	const FIELD_ISDEFAULTINVOICE		= 'bIsDefaultInvoice'; //When website/webshop makes an invoice, is this the one to use?
	const FIELD_ISDEFAULTORDER			= 'bIsDefaultOrder'; //When website/webshop makes an order, is this the one to use?
	const FIELD_COLORFOREGROUND			= 'sColorForeground'; //foreground color value in hexadecimals with #, for example: #ffffff = white
	const FIELD_COLORBACKGROUND			= 'sColorBackground'; //background color value in hexadecimals with #, for example: #ffffff = white
	const FIELD_NEWNUMBERINCREMENT		= 'iNewNumberIncrement'; //new transaction starts at this number. For example: invoice number
	const FIELD_ADDRESSSELLER			= 'sAddressSeller'; //encrypted address seller
	const FIELD_VATNOSELLER				= 'sVATNoSeller';//encrypted VAT number seller
	const FIELD_PAYMENTMADEWITHINDAYS	= 'iPaymentMadeWithinDays';//Payment should be made within [X] days of the invoice date. 21 = 21 days

	const ENCRYPTION_ADDRSELL_PASSPHRASE	= 'LpmDvF4#g_ldpwh4';
	const ENCRYPTION_VATNO_PASSPHRASE		= '3fP_ew3$rfs3d213d';

	const DEFAULT_TYPE_INVOICE			= 'Invoice';
	const DEFAULT_TYPE_ORDER			= 'Order';

	/**
	 * get invoice type name
	 * 
	 * @return string
	 */
	public function getName()
	{
		return $this->get(TTransactionsTypes::FIELD_NAME);
	}
	
	/**
	 * set invoice type name
	 * 
	 * @param string $sName
	 */
	public function setName($sName)
	{
		$this->set(TTransactionsTypes::FIELD_NAME, $sName);
	}        
	

	/**
	 * get if stock is added or subtracted for this transaction
	 * 
	 * @return bool
	 */
	public function getIsStock()
	{
		return $this->get(TTransactionsTypes::FIELD_ISSTOCK);
	}
	
	/**
	 * set if stock is added or subtracted for this transaction 
	 * 
	 * @param bool $bIsStock
	 */
	public function setIsStock($bIsStock)
	{
		$this->set(TTransactionsTypes::FIELD_ISSTOCK, $bIsStock);
	} 


	/**
	 * get if transaction is a financial transaction
	 * 
	 * @return bool
	 */
	public function getIsFinancial()
	{
		return $this->get(TTransactionsTypes::FIELD_ISSTOCK);
	}
	
	/**
	 * set if transaction is a financial transaction
	 * 
	 * @param bool $bIsFinancial
	 */
	public function setIsFinancial($bIsFinancial)
	{
		$this->set(TTransactionsTypes::FIELD_ISSTOCK, $bIsFinancial);
	} 


	/**
	 * get if transaction is selected by default in <SELECT> box
	 * 
	 * @return bool
	 */
	public function getIsDefaultSelected()
	{
		return $this->get(TTransactionsTypes::FIELD_ISDEFAULTSELECTED);
	}
	
	
	/**
	 * set if transaction is selected by default in <SELECT> box
	 * 
	 * @param bool $bIsDefault
	 */
	public function setIsDefaultSelected($bIsDefault)
	{
		$this->set(TTransactionsTypes::FIELD_ISDEFAULTSELECTED, $bIsDefault);
	} 

	
	/**
	 * get if invoice transaction type is needed, this is selected automatically
	 * (for example in a webshop)
	 * 
	 * @return bool
	 */
	public function getIsDefaultInvoice()
	{
		return $this->get(TTransactionsTypes::FIELD_ISDEFAULTINVOICE);
	}
	
	
	/**
	 * set if invoice transaction type is needed, this is selected automatically
	 * (for example in a webshop)
	 * 
	 * @param bool $bIsDefault
	 */
	public function setIsDefaultInvoice($bIsDefault)
	{
		$this->set(TTransactionsTypes::FIELD_ISDEFAULTINVOICE, $bIsDefault);
	} 	


	/**
	 * get if order transaction type is needed, this is selected automatically
	 * (for example in a webshop)
	 * 
	 * @return bool
	 */
	public function getIsDefaultOrder()
	{
		return $this->get(TTransactionsTypes::FIELD_ISDEFAULTORDER);
	}
	
	
	/**
	 * set if order transaction type is needed, this is selected automatically
	 * (for example in a webshop)
	 * 
	 * @param bool $bIsDefault
	 */
	public function setIsDefaultOrder($bIsDefault)
	{
		$this->set(TTransactionsTypes::FIELD_ISDEFAULTORDER, $bIsDefault);
	} 	


	/**
	 * get foreground color value in hexadecimals
	 * 
	 * @return bool
	 */
	public function getColorForeground()
	{
		return $this->get(TTransactionsTypes::FIELD_COLORFOREGROUND);
	}
	
	
	/**
	 * set foreground color value in hexadecimals
	 * 
	 * @param string $sColorHex in hexadecimals
	 */
	public function setColorForeground($sHexValue)
	{
		$this->set(TTransactionsTypes::FIELD_COLORFOREGROUND, $sHexValue);
	} 	

	/**
	 * get background color value in hexadecimals
	 * 
	 * @return bool
	 */
	public function getColorBackground()
	{
		return $this->get(TTransactionsTypes::FIELD_COLORBACKGROUND);
	}
	
	
	/**
	 * set background color value in hexadecimals
	 * 
	 * @param string $sColorHex in hexadecimals
	 */
	public function setColorBackground($sHexValue)
	{
		$this->set(TTransactionsTypes::FIELD_COLORBACKGROUND, $sHexValue);
	} 		

	/**
	 * get new number increment
	 * (order number, invoice number etc)
	 * 
	 * @return bool
	 */
	public function getNewNumberIncrement()
	{
		return $this->get(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT);
	}
	
	
	/**
	 * set new number increment
	 * (order number, invoice number etc)
	 * 
	 * @param int $iNewNumber
	 */
	public function setNewNumberIncrement($iNewNumber)
	{
		$this->set(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, $iNewNumber);
	} 	


	/**
	 * get seller address decrypted
	 * 
	 * @return string
	 */
	public function getAddressSellerDecrypted()
	{
		return $this->get(TTransactionsTypes::FIELD_ADDRESSSELLER, '', true);
	}
	
	
	/**
	 * set vat number seller
	 * 
	 * @param string $sAddress
	 */
	public function setAddressSellerEncrypted($sAddress)
	{
		$this->set(TTransactionsTypes::FIELD_ADDRESSSELLER, $sAddress, '', true);
	} 	


	/**
	 * get vat number seller
	 * 
	 * @return string
	 */
	public function getVATNoSellerDecrypted()
	{
		return $this->get(TTransactionsTypes::FIELD_VATNOSELLER, '', true);
	}
	
	
	/**
	 * set vat number seller
	 * 
	 * @param string $sNo
	 */
	public function setVATNoSellerEncrypted($sNo)
	{
		$this->set(TTransactionsTypes::FIELD_VATNOSELLER, $sNo, '', true);
	} 		


	/**
	 * get payment must be made in X days
	 * 
	 * @return bool
	 */
	public function getPaymentMadeWithInDays()
	{
		return $this->get(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS);
	}
	
	
	/**
	 * set payment must be made in X days
	 * 
	 * @param int $iDays
	 */
	public function setPaymentMadeWithInDays($iDays)
	{
		$this->set(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, $iDays);
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
		//transaction type name
		$this->setFieldDefaultValue(TTransactionsTypes::FIELD_NAME, '');
		$this->setFieldType(TTransactionsTypes::FIELD_NAME, CT_VARCHAR);
		$this->setFieldLength(TTransactionsTypes::FIELD_NAME, 50);
		$this->setFieldDecimalPrecision(TTransactionsTypes::FIELD_NAME, 0);
		$this->setFieldPrimaryKey(TTransactionsTypes::FIELD_NAME, false);
		$this->setFieldNullable(TTransactionsTypes::FIELD_NAME, false);
		$this->setFieldEnumValues(TTransactionsTypes::FIELD_NAME, null);
		$this->setFieldUnique(TTransactionsTypes::FIELD_NAME, true); 
		$this->setFieldIndexed(TTransactionsTypes::FIELD_NAME, false); 
		$this->setFieldForeignKeyClass(TTransactionsTypes::FIELD_NAME, null);
		$this->setFieldForeignKeyTable(TTransactionsTypes::FIELD_NAME, null);
		$this->setFieldForeignKeyField(TTransactionsTypes::FIELD_NAME, null);
		$this->setFieldForeignKeyJoin(TTransactionsTypes::FIELD_NAME);
		$this->setFieldForeignKeyActionOnUpdate(TTransactionsTypes::FIELD_NAME, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactionsTypes::FIELD_NAME, null); 
		$this->setFieldAutoIncrement(TTransactionsTypes::FIELD_NAME, false);
		$this->setFieldUnsigned(TTransactionsTypes::FIELD_NAME, false);
        $this->setFieldEncryptionDisabled(TTransactionsTypes::FIELD_NAME);


        //is stock
        $this->setFieldDefaultValue(TTransactionsTypes::FIELD_ISSTOCK, false);
        $this->setFieldType(TTransactionsTypes::FIELD_ISSTOCK, CT_BOOL);
        $this->setFieldLength(TTransactionsTypes::FIELD_ISSTOCK, 0);
        $this->setFieldDecimalPrecision(TTransactionsTypes::FIELD_ISSTOCK, 0);
        $this->setFieldPrimaryKey(TTransactionsTypes::FIELD_ISSTOCK, false);
        $this->setFieldNullable(TTransactionsTypes::FIELD_ISSTOCK, false);
        $this->setFieldEnumValues(TTransactionsTypes::FIELD_ISSTOCK, null);
        $this->setFieldUnique(TTransactionsTypes::FIELD_ISSTOCK, false);
        $this->setFieldIndexed(TTransactionsTypes::FIELD_ISSTOCK, false);
        $this->setFieldForeignKeyClass(TTransactionsTypes::FIELD_ISSTOCK, null);
        $this->setFieldForeignKeyTable(TTransactionsTypes::FIELD_ISSTOCK, null);
        $this->setFieldForeignKeyField(TTransactionsTypes::FIELD_ISSTOCK, null);
        $this->setFieldForeignKeyJoin(TTransactionsTypes::FIELD_ISSTOCK, null);
        $this->setFieldForeignKeyActionOnUpdate(TTransactionsTypes::FIELD_ISSTOCK, null);
        $this->setFieldForeignKeyActionOnDelete(TTransactionsTypes::FIELD_ISSTOCK, null);
        $this->setFieldAutoIncrement(TTransactionsTypes::FIELD_ISSTOCK, false);
        $this->setFieldUnsigned(TTransactionsTypes::FIELD_ISSTOCK, false);	
		$this->setFieldEncryptionDisabled(TTransactionsTypes::FIELD_ISSTOCK);	
			

        //is financial
        $this->setFieldCopyProps(TTransactionsTypes::FIELD_ISFINANCIAL, TTransactionsTypes::FIELD_ISSTOCK);

        //is default
        $this->setFieldCopyProps(TTransactionsTypes::FIELD_ISDEFAULTSELECTED, TTransactionsTypes::FIELD_ISSTOCK);

        //is default invoice
        $this->setFieldCopyProps(TTransactionsTypes::FIELD_ISDEFAULTINVOICE, TTransactionsTypes::FIELD_ISSTOCK);

        //is default order
        $this->setFieldCopyProps(TTransactionsTypes::FIELD_ISDEFAULTORDER, TTransactionsTypes::FIELD_ISSTOCK);
		
		//foreground color
		$this->setFieldCopyProps(TTransactionsTypes::FIELD_COLORFOREGROUND, TTransactionsTypes::FIELD_NAME);
		$this->setFieldDefaultValue(TTransactionsTypes::FIELD_COLORFOREGROUND, '#000000');
		$this->setFieldUnique(TTransactionsTypes::FIELD_COLORFOREGROUND, false);

		//background color
		$this->setFieldCopyProps(TTransactionsTypes::FIELD_COLORBACKGROUND, TTransactionsTypes::FIELD_COLORFOREGROUND);
		$this->setFieldDefaultValue(TTransactionsTypes::FIELD_COLORBACKGROUND, '#FFFFFF');

		//new number increment
		$this->setFieldDefaultValue(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, 0);
		$this->setFieldType(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, CT_INTEGER64);
		$this->setFieldLength(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, 0);
		$this->setFieldDecimalPrecision(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, 0);
		$this->setFieldPrimaryKey(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, false);
		$this->setFieldNullable(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, false);
		$this->setFieldEnumValues(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, null);
		$this->setFieldUnique(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, false); 
		$this->setFieldIndexed(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, false); 
		$this->setFieldForeignKeyClass(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, null);
		$this->setFieldForeignKeyTable(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, null);
		$this->setFieldForeignKeyField(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, null);
		$this->setFieldForeignKeyJoin(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT);
		$this->setFieldForeignKeyActionOnUpdate(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, null); 
		$this->setFieldAutoIncrement(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, false);
		$this->setFieldUnsigned(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, false);
        $this->setFieldEncryptionDisabled(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT);
		
		//address seller
		$this->setFieldDefaultValue(TTransactionsTypes::FIELD_ADDRESSSELLER, '');
		$this->setFieldType(TTransactionsTypes::FIELD_ADDRESSSELLER, CT_LONGTEXT);
		$this->setFieldLength(TTransactionsTypes::FIELD_ADDRESSSELLER, 0);
		$this->setFieldDecimalPrecision(TTransactionsTypes::FIELD_ADDRESSSELLER, 0);
		$this->setFieldPrimaryKey(TTransactionsTypes::FIELD_ADDRESSSELLER, false);
		$this->setFieldNullable(TTransactionsTypes::FIELD_ADDRESSSELLER, false);
		$this->setFieldEnumValues(TTransactionsTypes::FIELD_ADDRESSSELLER, null);
		$this->setFieldUnique(TTransactionsTypes::FIELD_ADDRESSSELLER, false); 
		$this->setFieldIndexed(TTransactionsTypes::FIELD_ADDRESSSELLER, false); 
		$this->setFieldForeignKeyClass(TTransactionsTypes::FIELD_ADDRESSSELLER, null);
		$this->setFieldForeignKeyTable(TTransactionsTypes::FIELD_ADDRESSSELLER, null);
		$this->setFieldForeignKeyField(TTransactionsTypes::FIELD_ADDRESSSELLER, null);
		$this->setFieldForeignKeyJoin(TTransactionsTypes::FIELD_ADDRESSSELLER);
		$this->setFieldForeignKeyActionOnUpdate(TTransactionsTypes::FIELD_ADDRESSSELLER, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactionsTypes::FIELD_ADDRESSSELLER, null); 
		$this->setFieldAutoIncrement(TTransactionsTypes::FIELD_ADDRESSSELLER, false);
		$this->setFieldUnsigned(TTransactionsTypes::FIELD_ADDRESSSELLER, false);
		$this->setFieldEncryptionCypher(TTransactionsTypes::FIELD_ADDRESSSELLER, ENCRYPTION_CYPHERMETHOD_AES256CBC);			                          
		$this->setFieldEncryptionDigest(TTransactionsTypes::FIELD_ADDRESSSELLER, ENCRYPTION_DIGESTALGORITHM_SHA512);			                          
		$this->setFieldEncryptionPassphrase(TTransactionsTypes::FIELD_ADDRESSSELLER, TTransactionsTypes::ENCRYPTION_ADDRSELL_PASSPHRASE);	

		//VAT number sellert
		$this->setFieldCopyProps(TTransactionsTypes::FIELD_VATNOSELLER, TTransactionsTypes::FIELD_ADDRESSSELLER);
		$this->setFieldEncryptionCypher(TTransactionsTypes::FIELD_ADDRESSSELLER, ENCRYPTION_CYPHERMETHOD_AES256CBC);			                          
		$this->setFieldEncryptionDigest(TTransactionsTypes::FIELD_ADDRESSSELLER, ENCRYPTION_DIGESTALGORITHM_SHA512);			                          
		$this->setFieldEncryptionPassphrase(TTransactionsTypes::FIELD_ADDRESSSELLER, TTransactionsTypes::ENCRYPTION_VATNO_PASSPHRASE);	

		//payment made within days
		$this->setFieldDefaultValue(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, 14);
		$this->setFieldType(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, CT_INTEGER);
		$this->setFieldLength(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, 0);
		$this->setFieldDecimalPrecision(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, 0);
		$this->setFieldPrimaryKey(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, false);
		$this->setFieldNullable(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, false);
		$this->setFieldEnumValues(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, null);
		$this->setFieldUnique(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, false); 
		$this->setFieldIndexed(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, false); 
		$this->setFieldForeignKeyClass(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, null);
		$this->setFieldForeignKeyTable(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, null);
		$this->setFieldForeignKeyField(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, null);
		$this->setFieldForeignKeyJoin(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS);
		$this->setFieldForeignKeyActionOnUpdate(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, null); 
		$this->setFieldAutoIncrement(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, false);
		$this->setFieldUnsigned(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, false);
        $this->setFieldEncryptionDisabled(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS);
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
		return array(TTransactionsTypes::FIELD_NAME, 
					TTransactionsTypes::FIELD_ISSTOCK,
					TTransactionsTypes::FIELD_ISFINANCIAL,
					TTransactionsTypes::FIELD_ISDEFAULTSELECTED,
					TTransactionsTypes::FIELD_ISDEFAULTINVOICE,
					TTransactionsTypes::FIELD_ISDEFAULTORDER,
					TTransactionsTypes::FIELD_COLORFOREGROUND,
					TTransactionsTypes::FIELD_COLORBACKGROUND,
					TTransactionsTypes::FIELD_NEWNUMBERINCREMENT,
					TTransactionsTypes::FIELD_ADDRESSSELLER,
					TTransactionsTypes::FIELD_VATNOSELLER,
					TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS
				);
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
		return false;
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
		return GLOBAL_DB_TABLEPREFIX.'TransactionsTypes';
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
		return $this->get(TTransactionsTypes::FIELD_NAME);
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
		return 'typetjeblaatberlk'.
			$this->getName().
			$this->getName().
			'hreiiesdflksjdflsjkdfl'.
			boolToStr($this->getIsStock()).
			boolToStr($this->getIsFinancial()).
			boolToStr($this->getPaymentMadeWithInDays()).
			'whodl,emikjsdf'.
			$this->getNewNumberIncrement();			
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


        /**
         * additions to the install procedure
         * 
         * @param array $arrPreviousDependenciesModelClasses
         */
        public function install($arrPreviousDependenciesModelClasses = null)
        {
            $bSuccess = true;
            $bSuccess = parent::install($arrPreviousDependenciesModelClasses);
            
            //==check if at least one Transaction type exists, if not, then add it
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
                //invoice
                $this->clear();
                $this->newRecord();
                $this->setName(TTransactionsTypes::DEFAULT_TYPE_INVOICE); //default invoice type
				$this->setIsStock(true);
				$this->setIsFinancial(true);
				$this->setIsDefaultSelected(true);
				$this->setIsDefaultInvoice(true);
				$this->setColorBackground('#f11f1f'); //red 
				$this->setColorForeground('#ffffff'); //white
				$this->setPaymentMadeWithInDays(14); 
                if (!$this->saveToDB())
                {
                    error(__CLASS__.': error saving default transaction type invoice:'.TTransactionsTypes::DEFAULT_TYPE_INVOICE);
                    return false;
                }    
                
                //order
                $this->clear();
                $this->newRecord();
                $this->setName(TTransactionsTypes::DEFAULT_TYPE_ORDER); //default order type
				$this->setIsDefaultOrder(true);
				$this->setColorBackground('#009b17'); //green
				$this->setColorForeground('#ffffff'); //white
				$this->setPaymentMadeWithInDays(14); 
                if (!$this->saveToDB())
                {
                    error(__CLASS__.': error saving default transaction type order:'.TTransactionsTypes::DEFAULT_TYPE_ORDER);
                    return false;
                }    

            }
                
            return $bSuccess;
        }        	
} 
?>