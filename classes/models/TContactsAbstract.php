<?php
namespace dr\classes\models;

use dr\classes\controllers\TControllerAbstract;
use dr\classes\models\TModel;

/**
 * This class represents contacts in an address book.
 *  
 * This is an abstract class that you can reuse for webshop customers, CMS etc, making an invoicing system for clients etc
 * 
 * ENCRYPTION
 * ==========
 * A lot in this class is encrypted for privacy reasons.
 * If an attacker ever gets a hold of the database
 * the most crucial privacy sentive data is encrypted.
 * The idea: if an attacker gets the database it is worthless to them because everything worthwhile is encrypted and takes too much effort to decrypt, so they move on to something easier
 * 
 * BILLING fields
 * =================
 * There is made a clear distinction between billing address fields, residential address fields, or delivery address fields (residential, delivery: inherit to add)
 * For example: a webshop order can have a delivery and a separate billing address.
 * Since billing addresses are the ones used most often, these are defined in this abstract class
 * 
 * CUSTOM IDENTIFIER
 * =================
 * Because so much is encrypted, it may be hard to search and find contacts 
 * (you can't search on encrypted fields, it defies the whole purpose of encryption)
 * Therefore: the custom identifier.
 * This can be anything that refers to a contact in a reasonably unique way: a postal code, the first 6 letters of the last name. 
 * This will be stored in plain text in database for searching purposes. 
 * Make sure it is not uniquely identifyable enough when the database gets breached
 * 
 * 
 * 2022 Dennis Renirie
 */


abstract class TContactsAbstract extends TModel
{
	const FIELD_CUSTOMIDENTIFIER 		= 'sCustomIdentifier'; //Custom identifier, anything that refers to a contact in a reasonably unique way: a postal code, the first 6 letters of the last name. This will be stored in plain text in database for searching purposes. Make sure it is not identifyable enough when the database gets breached
	const FIELD_COMPANYNAME 			= 'sCompanyName';
	const FIELD_FIRSTNAMEINITALS 		= 'sFirstNameInitials'; //first name or initials
	const FIELD_LASTNAME 				= 'sLastName'; 
	const FIELD_LASTNAMEPREFIX 			= 'sLastNamePrefix';  //tussenvoegsel: Van, Le etc.
	const FIELD_EMAILADDRESSENCRYPTED   = 'sEAE';//Email Address Encrypted internally stored in encrypted form - 2 way encrypted email address
	const FIELD_EMAILADDRESSFINGERPRINT = 'sEAF';//Fingerprint Email Address, so we can lookup a record based on email address. We can't salt this, because we need to be able to search on it in the database for password recovery
	const FIELD_ONMAILINGLIST			= 'bOnMailingList';
	const FIELD_ONBLACKLIST				= 'bOnBlackList';
	const FIELD_PHONENUMBER1			= 'sPhoneNumber1';
	const FIELD_PHONENUMBER2			= 'sPhoneNumber2';
	const FIELD_CHAMBEROFCOMMERCENO		= 'sCCNO'; //chamber of commerce number encrypted
	const FIELD_NOTES 					= 'sNotes'; //internal notes about the client only seen by the user (not client)

	const FIELD_BILLINGADDRESSMISC 				= 'sBillingAddressMisc'; //appartment building/ company department
	const FIELD_BILLINGADDRESSSTREET 			= 'sBillingAddressStreet'; //street + housenumber
	const FIELD_BILLINGPOSTALCODEZIP 			= 'sBillingZipPostalCode'; //postal code / zip code
	const FIELD_BILLINGCITY 					= 'sBillingCity';
	const FIELD_BILLINGSTATEREGION 				= 'sBillingStateRegion'; //state, region, province
	const FIELD_BILLINGCOUNTRYID 				= 'iBillingCountryID'; //contryid from the system
	const FIELD_BILLINGVATNUMBER 				= 'sBillingVatNumber';//vat number encrypted
	const FIELD_BILLINGEMAILADDRESSENCRYPTED   	= 'sBEAE';//Email Address Encrypted internally stored in encrypted form - 2 way encrypted email address
	const FIELD_BILLINGEMAILADDRESSFINGERPRINT 	= 'sBEAF';//Fingerprint Email Address, so we can lookup a record based on email address. We can't salt this, because we need to be able to search on it in the database for password recovery
	const FIELD_BILLINGBANKACCOUNTNO 			= 'sBBANO';//bank account number encrypted

	const FIELD_DELIVERYADDRESSMISC 			= 'sDeliveryAddressMisc'; //appartment building/ company department
	const FIELD_DELIVERYADDRESSSTREET 			= 'sDeliveryAddressStreet'; //street
	const FIELD_DELIVERYPOSTALCODEZIP 			= 'sDeliveryZipPostalCode'; //postal code / zip code
	const FIELD_DELIVERYCITY 					= 'sDeliveryCity';
	const FIELD_DELIVERYSTATEREGION 			= 'sDeliveryStateRegion'; //state, region, province
	const FIELD_DELIVERYCOUNTRYID 				= 'iDeliveryCountryID'; //contryid from the system

	const SEED_EMAILADDRESSFINGERPRINT 			= 'sdfio34msd_#dlwejkwen23ED3eddq_212$dsdf'; //seed to make it harder to decrypt, when we change it up per class not every table has the same seed
	const DIGEST_EMAILADDRESSFINGERPRINT 		= ENCRYPTION_DIGESTALGORITHM_SHA512;
	const SEED_BILLINGEMAILADDRESSFINGERPRINT 	= '49fr040M9834rjiVb34LW4Lmdjsoi89rhf03hern34r'; //seed to make it harder to decrypt, when we change it up per class not every table has the same seed
	const DIGEST_BILLINGEMAILADDRESSFINGERPRINT = ENCRYPTION_DIGESTALGORITHM_SHA512;

	const ENCRYPTION_LASTNAME_PASSPHRASE 		= '34r98dvjef9034fiuefjidf_giwsdf#d_sdf'; //passphrase for the encryption algo
	const ENCRYPTION_EMAIL_PASSPHRASE 			= 'e33FPL@dMbDfewd_=EwcqP()#d_sdf'; //passphrase for the encryption algo
	const ENCRYPTION_PHONE1_PASSPHRASE 			= '1dPlodk_ede=weldk3mSw2'; //passphrase for the encryption algo
	const ENCRYPTION_PHONE2_PASSPHRASE 			= '3d4gfggwrty5_==+2m4C3LPd'; //passphrase for the encryption algo
	const ENCRYPTION_CHAMBERCOMMERCE_PASSPHRASE = '6d434349kfkflkPLDSss$3___e'; //passphrase for the encryption algo
	const ENCRYPTION_BILL_ADDR1_PASSPHRASE 		= 'adf83fbwcoisdbjk23bnwe_shwojewdwef_'; //passphrase for the encryption algo: bill=billing
	const ENCRYPTION_BILL_ADDR2_PASSPHRASE 		= 'asdflijn34%$132sdfjsf_sdfkj34ro3m'; //passphrase for the encryption algo
	const ENCRYPTION_BILL_POSTAL_PASSPHRASE 	= 'p934rbervojiw459i4gjlsrg_q398uefjhdv'; //passphrase for the encryption algo
	const ENCRYPTION_BILL_VATNO_PASSPHRASE 		= '234234sdkfsj4__++#+_23me90djd5r'; //passphrase for the encryption algo
	const ENCRYPTION_BILL_EMAIL_PASSPHRASE 		= 'wefoi3fnfriorfnerkp+_wewerjwer'; //passphrase for the encryption algo
	const ENCRYPTION_BILL_BANKACC_PASSPHRASE 	= 'whodlsd finsdfi3 3 dBMa2_2'; //passphrase for the encryption algo
	const ENCRYPTION_DELI_ADDR1_PASSPHRASE 		= 'sdfg4-4fkdfgk333rfs_Plds'; //passphrase for the encryption algo: deli = delivery
	const ENCRYPTION_DELI_ADDR2_PASSPHRASE 		= 'ae444h%3_jsf_sdfkj34ro3m'; //passphrase for the encryption algo
	const ENCRYPTION_DELI_POSTAL_PASSPHRASE 	= 's03jfsdPj93o4kkk___djsifsd'; //passphrase for the encryption algo


	public function getCustomIdentifier()
	{
		return $this->get(TContactsAbstract::FIELD_CUSTOMIDENTIFIER);
	}

	public function setCustomIdentifier($sRefNo)
	{
		$this->set(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, $sRefNo);
	}

	/**
	 * generate a custom identifier, format: abcd-1234
	 * based on:
	 * -the first 4 characters of the last name
	 * -the first 4 digits of the billing postal code/zip
	 */
	public function setCustomIdentifierAuto()
	{
		$sPart1 = '';
		$sPart2 = '';
		$sResult = '';

		$sPart1 = substr($this->getLastName(), 0, 4);
		$sPart2 = substr($this->getBillingPostalCodeZip(),0, 4);

		if ((strlen($sPart1)>0) && (strlen($sPart2)>0)) //if part1 and part2 exist add '-'
			$sResult.= $sPart1.'-'.$sPart2;
		else
			$sResult.= $sPart1.$sPart2;

		$this->setCustomIdentifier($sResult);
	}	


	public function getCompanyName()
	{
		return $this->get(TContactsAbstract::FIELD_COMPANYNAME);
	}

	public function setCompanyName($sCompanyName)
	{
		$this->set(TContactsAbstract::FIELD_COMPANYNAME, $sCompanyName);
	}

	public function getFirstNameInitials()
	{
		return $this->get(TContactsAbstract::FIELD_FIRSTNAMEINITALS);
	}

	public function setFirstNameInitials($sFirstName)
	{
		$this->set(TContactsAbstract::FIELD_FIRSTNAMEINITALS, $sFirstName);
	}

	public function getLastName()
	{
		return $this->get(TContactsAbstract::FIELD_LASTNAME, '', true);
	}

	public function setLastName($sLastName)
	{
		$this->set(TContactsAbstract::FIELD_LASTNAME, $sLastName, '', true);
	}

	public function getLastNamePrefix()
	{
		return $this->get(TContactsAbstract::FIELD_LASTNAMEPREFIX);
	}

	public function setLastNamePrefix($sPrefix)
	{
		$this->set(TContactsAbstract::FIELD_LASTNAMEPREFIX, $sPrefix);
	}


    /**
     * get email address and decrypt it
     * 
     * @return string
     */
    public function getEmailAddressDecrypted()
    {
        return $this->get(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, '', true);
    }

    /**
     * encrypts and sets email address AND email identifier
     * 
     * @param string $sEmail
     */
    public function setEmailAddressDecrypted($sUncryptedEmail)
    {
        $this->set(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, $sUncryptedEmail, '', true);
        $this->set(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, getFingerprintEmail($sUncryptedEmail, TContactsAbstract::SEED_EMAILADDRESSFINGERPRINT, TContactsAbstract::DIGEST_EMAILADDRESSFINGERPRINT));
    }

	public function getOnMailingList()
	{
		return $this->get(TContactsAbstract::FIELD_ONMAILINGLIST);
	}

	public function setOnMailingList($bValue)
	{
		$this->set(TContactsAbstract::FIELD_ONMAILINGLIST, $bValue);
	}

	public function getOnBlackList()
	{
		return $this->get(TContactsAbstract::FIELD_ONBLACKLIST);
	}

	public function setOnBlackList($bValue)
	{
		$this->set(TContactsAbstract::FIELD_ONBLACKLIST, $bValue);
	}	

	public function getPhoneNumber1()
	{
		return $this->get(TContactsAbstract::FIELD_PHONENUMBER1);
	}

	public function setPhoneNumber1($sPhone)
	{
		$this->set(TContactsAbstract::FIELD_PHONENUMBER1, $sPhone);
	}		

	public function getPhoneNumber2()
	{
		return $this->get(TContactsAbstract::FIELD_PHONENUMBER2);
	}

	public function setPhoneNumber2($sPhone)
	{
		$this->set(TContactsAbstract::FIELD_PHONENUMBER2, $sPhone);
	}	


	public function getChamberOfCommerceNoDecrypted()
	{
		return $this->get(TContactsAbstract::FIELD_CHAMBEROFCOMMERCENO, '', true);
	}

	public function setChamberCommerceNoEncrypted($sNO)
	{
		$this->set(TContactsAbstract::FIELD_CHAMBEROFCOMMERCENO, $sNO, '', true);
	}		

	public function getNotes()
	{
		return $this->get(TContactsAbstract::FIELD_NOTES);
	}

	public function setNotes($sNotes)
	{
		$this->set(TContactsAbstract::FIELD_NOTES, $sNotes);
	}

	public function getBillingAddressLine1()
	{
		return $this->get(TContactsAbstract::FIELD_BILLINGADDRESSMISC, '', true);
	}

	public function setBillingAddressLine1($sLine1)
	{
		$this->set(TContactsAbstract::FIELD_BILLINGADDRESSMISC, $sLine1, '', true);
	}

	
	public function getBillingAddressLine2()
	{
		return $this->get(TContactsAbstract::FIELD_BILLINGADDRESSMISC, '', true);
	}

	public function setBillingAddressLine2($sLine2)
	{
		$this->set(TContactsAbstract::FIELD_BILLINGADDRESSSTREET, $sLine2, '', true);
	}

	public function getBillingPostalCodeZip()
	{
		return $this->get(TContactsAbstract::FIELD_BILLINGPOSTALCODEZIP, '', true);
	}

	public function setBillingPostalCodeZip($sZip)
	{
		$this->set(TContactsAbstract::FIELD_BILLINGPOSTALCODEZIP, $sZip, '', true);
	}

	public function getBillingCity()
	{
		return $this->get(TContactsAbstract::FIELD_BILLINGCITY);
	}

	public function setBillingCity($sCity)
	{
		$this->set(TContactsAbstract::FIELD_BILLINGCITY, $sCity);
	}

	public function getBillingStateRegion()
	{
		return $this->get(TContactsAbstract::FIELD_BILLINGSTATEREGION);
	}

	public function setBillingStateRegion($sRegion)
	{
		$this->set(TContactsAbstract::FIELD_BILLINGSTATEREGION, $sRegion);
	}

	public function getBillingCountryID()
	{
		return $this->get(TContactsAbstract::FIELD_BILLINGCOUNTRYID);
	}

	public function setBillingCountryID($iCountryID)
	{
		$this->set(TContactsAbstract::FIELD_BILLINGCOUNTRYID, $iCountryID);
	}

	public function getBillingVATNumberDecrypted()
	{
		return $this->get(TContactsAbstract::FIELD_BILLINGVATNUMBER, '', true);
	}

	public function setBillingVATNumberEncrypted($sVATNumber)
	{
		$this->set(TContactsAbstract::FIELD_BILLINGVATNUMBER, $sVATNumber, '', true);
	}	

    /**
     * get email address and decrypt it
     * 
     * @return string
     */
    public function getBillingEmailAddressDecrypted()
    {
        return $this->get(TContactsAbstract::FIELD_BILLINGEMAILADDRESSENCRYPTED, '', true);
    }

    /**
     * encrypts and sets email address AND email identifier
     * 
     * @param string $sEmail
     */
    public function setBillingEmailAddressDecrypted($sUncryptedEmail)
    {
        $this->set(TContactsAbstract::FIELD_BILLINGEMAILADDRESSENCRYPTED, $sUncryptedEmail, '', true);
        $this->set(TContactsAbstract::FIELD_BILLINGEMAILADDRESSFINGERPRINT, getFingerprintEmail($sUncryptedEmail, TContactsAbstract::SEED_BILLINGEMAILADDRESSFINGERPRINT, TContactsAbstract::DIGEST_BILLINGEMAILADDRESSFINGERPRINT));
    }

    /**
     * get bank account number and decrypt it
     * 
     * @return string
     */
    public function getBillingBankAccountNoDecrypted()
    {
        return $this->get(TContactsAbstract::FIELD_BILLINGBANKACCOUNTNO, '', true);
    }

    /**
     * encrypts and sets bank account number
     * 
     * @param string $sUncryptedBankAccountNo
     */
    public function setBillingBankAccountNoDecrypted($sUncryptedBankAccountNo)
    {
        $this->set(TContactsAbstract::FIELD_BILLINGBANKACCOUNTNO, $sUncryptedBankAccountNo, '', true);
    }	

	public function getDeliveryAddressLine1()
	{
		return $this->get(TContactsAbstract::FIELD_DELIVERYADDRESSMISC, '', true);
	}

	public function setDeliveryAddressLine1($sLine1)
	{
		$this->set(TContactsAbstract::FIELD_DELIVERYADDRESSMISC, $sLine1, '', true);
	}

	
	public function getDeliveryAddressLine2()
	{
		return $this->get(TContactsAbstract::FIELD_DELIVERYADDRESSMISC, '', true);
	}

	public function setDeliveryAddressLine2($sLine2)
	{
		$this->set(TContactsAbstract::FIELD_DELIVERYADDRESSSTREET, $sLine2, '', true);
	}

	public function getDeliveryPostalCodeZip()
	{
		return $this->get(TContactsAbstract::FIELD_DELIVERYPOSTALCODEZIP, '', true);
	}

	public function setDeliveryPostalCodeZip($sZip)
	{
		$this->set(TContactsAbstract::FIELD_DELIVERYPOSTALCODEZIP, $sZip, '', true);
	}

	public function getDeliveryCity()
	{
		return $this->get(TContactsAbstract::FIELD_DELIVERYCITY);
	}

	public function setDeliveryCity($sCity)
	{
		$this->set(TContactsAbstract::FIELD_DELIVERYCITY, $sCity);
	}

	public function getDeliveryStateRegion()
	{
		return $this->get(TContactsAbstract::FIELD_DELIVERYSTATEREGION);
	}

	public function setDeliveryStateRegion($sRegion)
	{
		$this->set(TContactsAbstract::FIELD_DELIVERYSTATEREGION, $sRegion);
	}

	public function getDeliveryCountryID()
	{
		return $this->get(TContactsAbstract::FIELD_DELIVERYCOUNTRYID);
	}

	public function setDeliveryCountryID($iCountryID)
	{
		$this->set(TContactsAbstract::FIELD_DELIVERYCOUNTRYID, $iCountryID);
	}




	

	/**
	 * this function creates table in database and calls all foreign key classes to do the same
	 * 
	 * the $arrPreviousDependenciesModelClasses prevents a endless loop by storing all the classnames that are already installed
	 *
	 * @param array $arrPreviousDependenciesModelClasses with classnames. 
	 * @return bool success?
	 */
	// public function install($arrPreviousDependenciesModelClasses = null)
	// {
	// 	$bSuccess = parent::install($arrPreviousDependenciesModelClasses);
		
		
	// 	if ($bSuccess)
	// 	{
	// 		$this->limitOne();
	// 		$this->loadFromDB(false);
	// 		if ($this->count() == 0) //only add when table is empty
	// 		{
	// 			$this->clear();

	// 			$this->newRecord();
	// 			$this->setCustomIdentifier('DEFAULT');
	// 			$this->setCompanyName('DEFAULT');
				
	// 			if (!$this->saveToDB())
	// 				error('error saving default contact on install');

	// 		}
	// 	}
		
	// 	return $bSuccess;
	// }
	

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
		$this->setFieldDefaultValue(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, '');
		$this->setFieldType(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, CT_VARCHAR);
		$this->setFieldLength(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, 50);
		$this->setFieldDecimalPrecision(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, 0);
		$this->setFieldPrimaryKey(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, false);
		$this->setFieldNullable(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, true);
		$this->setFieldEnumValues(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldUnique(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, false); //it is annoying when you dont fill it in for 2 customers, you get an error
		$this->setFieldIndexed(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, true); 
		$this->setFieldForeignKeyClass(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyTable(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyField(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyJoin(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyActionOnUpdate(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldForeignKeyActionOnDelete(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, null);
		$this->setFieldAutoIncrement(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, false);
		$this->setFieldUnsigned(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, false);
        $this->setFieldEncryptionDisabled(TContactsAbstract::FIELD_CUSTOMIDENTIFIER);		


		//company name
		$this->setFieldDefaultValue(TContactsAbstract::FIELD_COMPANYNAME, '');
		$this->setFieldType(TContactsAbstract::FIELD_COMPANYNAME, CT_VARCHAR);
		$this->setFieldLength(TContactsAbstract::FIELD_COMPANYNAME, 100);
		$this->setFieldDecimalPrecision(TContactsAbstract::FIELD_COMPANYNAME, 0);
		$this->setFieldPrimaryKey(TContactsAbstract::FIELD_COMPANYNAME, false);
		$this->setFieldNullable(TContactsAbstract::FIELD_COMPANYNAME, true);
		$this->setFieldEnumValues(TContactsAbstract::FIELD_COMPANYNAME, null);
		$this->setFieldUnique(TContactsAbstract::FIELD_COMPANYNAME, false); //it is annoying when you dont fill it in for 2 customers, you get an error
		$this->setFieldIndexed(TContactsAbstract::FIELD_COMPANYNAME, true); 
		$this->setFieldForeignKeyClass(TContactsAbstract::FIELD_COMPANYNAME, null);
		$this->setFieldForeignKeyTable(TContactsAbstract::FIELD_COMPANYNAME, null);
		$this->setFieldForeignKeyField(TContactsAbstract::FIELD_COMPANYNAME, null);
		$this->setFieldForeignKeyJoin(TContactsAbstract::FIELD_COMPANYNAME, null);
		$this->setFieldForeignKeyActionOnUpdate(TContactsAbstract::FIELD_COMPANYNAME, null);
		$this->setFieldForeignKeyActionOnDelete(TContactsAbstract::FIELD_COMPANYNAME, null);
		$this->setFieldAutoIncrement(TContactsAbstract::FIELD_COMPANYNAME, false);
		$this->setFieldUnsigned(TContactsAbstract::FIELD_COMPANYNAME, false);
        $this->setFieldEncryptionDisabled(TContactsAbstract::FIELD_COMPANYNAME);		


		//first name and initials
		$this->setFieldCopyProps(TContactsAbstract::FIELD_FIRSTNAMEINITALS, TContactsAbstract::FIELD_COMPANYNAME);
		$this->setFieldLength(TContactsAbstract::FIELD_FIRSTNAMEINITALS, 50);
		$this->setFieldIndexed(TContactsAbstract::FIELD_FIRSTNAMEINITALS, false);

		//last name
		$this->setFieldDefaultValue(TContactsAbstract::FIELD_LASTNAME, '');
		$this->setFieldType(TContactsAbstract::FIELD_LASTNAME, CT_LONGTEXT);
		$this->setFieldLength(TContactsAbstract::FIELD_LASTNAME, 0);
		$this->setFieldDecimalPrecision(TContactsAbstract::FIELD_LASTNAME, 0);
		$this->setFieldPrimaryKey(TContactsAbstract::FIELD_LASTNAME, false);
		$this->setFieldNullable(TContactsAbstract::FIELD_LASTNAME, true);
		$this->setFieldEnumValues(TContactsAbstract::FIELD_LASTNAME, null);
		$this->setFieldUnique(TContactsAbstract::FIELD_LASTNAME, false); //it is annoying when you dont fill it in for 2 customers, you get an error
		$this->setFieldIndexed(TContactsAbstract::FIELD_LASTNAME, false); 
		$this->setFieldForeignKeyClass(TContactsAbstract::FIELD_LASTNAME, null);
		$this->setFieldForeignKeyTable(TContactsAbstract::FIELD_LASTNAME, null);
		$this->setFieldForeignKeyField(TContactsAbstract::FIELD_LASTNAME, null);
		$this->setFieldForeignKeyJoin(TContactsAbstract::FIELD_LASTNAME, null);
		$this->setFieldForeignKeyActionOnUpdate(TContactsAbstract::FIELD_LASTNAME, null);
		$this->setFieldForeignKeyActionOnDelete(TContactsAbstract::FIELD_LASTNAME, null);
		$this->setFieldAutoIncrement(TContactsAbstract::FIELD_LASTNAME, false);
		$this->setFieldUnsigned(TContactsAbstract::FIELD_LASTNAME, false);
		$this->setFieldEncryptionCypher(TContactsAbstract::FIELD_LASTNAME, ENCRYPTION_CYPHERMETHOD_AES256CBC);			                          
		$this->setFieldEncryptionDigest(TContactsAbstract::FIELD_LASTNAME, ENCRYPTION_DIGESTALGORITHM_SHA512);			                          
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_LASTNAME, TContactsAbstract::ENCRYPTION_LASTNAME_PASSPHRASE);			                          
		
		//last name prefix
		$this->setFieldCopyProps(TContactsAbstract::FIELD_LASTNAMEPREFIX, TContactsAbstract::FIELD_LASTNAME);
		$this->setFieldLength(TContactsAbstract::FIELD_LASTNAMEPREFIX, 20);


        //2-way encrypted email address
        $this->setFieldDefaultValue(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, '');
        $this->setFieldType(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, CT_LONGTEXT);
        $this->setFieldLength(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, 0);
        $this->setFieldDecimalPrecision(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, 0);
        $this->setFieldPrimaryKey(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
        $this->setFieldNullable(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, true);
        $this->setFieldEnumValues(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldUnique(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
        $this->setFieldIndexed(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
        $this->setFieldForeignKeyClass(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyTable(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyField(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyJoin(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyActionOnUpdate(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldForeignKeyActionOnDelete(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, null);
        $this->setFieldAutoIncrement(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
        $this->setFieldUnsigned(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, false);
		$this->setFieldEncryptionCypher(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, ENCRYPTION_CYPHERMETHOD_AES256CBC);			                          
		$this->setFieldEncryptionDigest(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, ENCRYPTION_DIGESTALGORITHM_SHA512);			                          
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED, TContactsAbstract::ENCRYPTION_EMAIL_PASSPHRASE);			                          

        //email fingerprint, so we can lookup the record based on email address
        $this->setFieldDefaultValue(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, '');
        $this->setFieldType(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, CT_VARCHAR);
        $this->setFieldLength(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, 255);
        $this->setFieldDecimalPrecision(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, 0);
        $this->setFieldPrimaryKey(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, false);
        $this->setFieldNullable(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, true);
        $this->setFieldEnumValues(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldUnique(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, false);
        $this->setFieldIndexed(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, true);
        $this->setFieldForeignKeyClass(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyTable(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyField(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyJoin(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyActionOnUpdate(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldForeignKeyActionOnDelete(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, null);
        $this->setFieldAutoIncrement(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, false);
        $this->setFieldUnsigned(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT, false);
		$this->setFieldEncryptionDisabled(TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT);	

		//on mailing list
		$this->setFieldDefaultValue(TContactsAbstract::FIELD_ONMAILINGLIST, '');
		$this->setFieldType(TContactsAbstract::FIELD_ONMAILINGLIST, CT_BOOL);
		$this->setFieldLength(TContactsAbstract::FIELD_ONMAILINGLIST, 0);
		$this->setFieldDecimalPrecision(TContactsAbstract::FIELD_ONMAILINGLIST, 0);
		$this->setFieldPrimaryKey(TContactsAbstract::FIELD_ONMAILINGLIST, false);
		$this->setFieldNullable(TContactsAbstract::FIELD_ONMAILINGLIST, false);
		$this->setFieldEnumValues(TContactsAbstract::FIELD_ONMAILINGLIST, null);
		$this->setFieldUnique(TContactsAbstract::FIELD_ONMAILINGLIST, false); 
		$this->setFieldIndexed(TContactsAbstract::FIELD_ONMAILINGLIST, false); 
		$this->setFieldForeignKeyClass(TContactsAbstract::FIELD_ONMAILINGLIST, null);
		$this->setFieldForeignKeyTable(TContactsAbstract::FIELD_ONMAILINGLIST, null);
		$this->setFieldForeignKeyField(TContactsAbstract::FIELD_ONMAILINGLIST, null);
		$this->setFieldForeignKeyJoin(TContactsAbstract::FIELD_ONMAILINGLIST, null);
		$this->setFieldForeignKeyActionOnUpdate(TContactsAbstract::FIELD_ONMAILINGLIST, null);
		$this->setFieldForeignKeyActionOnDelete(TContactsAbstract::FIELD_ONMAILINGLIST, null);
		$this->setFieldAutoIncrement(TContactsAbstract::FIELD_ONMAILINGLIST, false);
		$this->setFieldUnsigned(TContactsAbstract::FIELD_ONMAILINGLIST, true);
        $this->setFieldEncryptionDisabled(TContactsAbstract::FIELD_ONMAILINGLIST);	

		//on blacklist
		$this->setFieldCopyProps(TContactsAbstract::FIELD_ONBLACKLIST, TContactsAbstract::FIELD_ONMAILINGLIST);

		//phone1
        $this->setFieldDefaultValue(TContactsAbstract::FIELD_PHONENUMBER1, '');
        $this->setFieldType(TContactsAbstract::FIELD_PHONENUMBER1, CT_LONGTEXT);
        $this->setFieldLength(TContactsAbstract::FIELD_PHONENUMBER1, 0);
        $this->setFieldDecimalPrecision(TContactsAbstract::FIELD_PHONENUMBER1, 0);
        $this->setFieldPrimaryKey(TContactsAbstract::FIELD_PHONENUMBER1, false);
        $this->setFieldNullable(TContactsAbstract::FIELD_PHONENUMBER1, true);
        $this->setFieldEnumValues(TContactsAbstract::FIELD_PHONENUMBER1, null);
        $this->setFieldUnique(TContactsAbstract::FIELD_PHONENUMBER1, false);
        $this->setFieldIndexed(TContactsAbstract::FIELD_PHONENUMBER1, false);
        $this->setFieldForeignKeyClass(TContactsAbstract::FIELD_PHONENUMBER1, null);
        $this->setFieldForeignKeyTable(TContactsAbstract::FIELD_PHONENUMBER1, null);
        $this->setFieldForeignKeyField(TContactsAbstract::FIELD_PHONENUMBER1, null);
        $this->setFieldForeignKeyJoin(TContactsAbstract::FIELD_PHONENUMBER1, null);
        $this->setFieldForeignKeyActionOnUpdate(TContactsAbstract::FIELD_PHONENUMBER1, null);
        $this->setFieldForeignKeyActionOnDelete(TContactsAbstract::FIELD_PHONENUMBER1, null);
        $this->setFieldAutoIncrement(TContactsAbstract::FIELD_PHONENUMBER1, false);
        $this->setFieldUnsigned(TContactsAbstract::FIELD_PHONENUMBER1, false);
		$this->setFieldEncryptionCypher(TContactsAbstract::FIELD_PHONENUMBER1, ENCRYPTION_CYPHERMETHOD_AES256CBC);			                          
		$this->setFieldEncryptionDigest(TContactsAbstract::FIELD_PHONENUMBER1, ENCRYPTION_DIGESTALGORITHM_SHA512);			                          
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_PHONENUMBER1, TContactsAbstract::ENCRYPTION_PHONE1_PASSPHRASE);	

		//phone2
		$this->setFieldCopyProps(TContactsAbstract::FIELD_PHONENUMBER2, TContactsAbstract::FIELD_PHONENUMBER1);
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_PHONENUMBER2, TContactsAbstract::ENCRYPTION_PHONE2_PASSPHRASE);	

       	//2-way encrypted chamber of commerce number
	   	$this->setFieldCopyProps(TContactsAbstract::FIELD_CHAMBEROFCOMMERCENO, TContactsAbstract::FIELD_PHONENUMBER1);
	   	$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_CHAMBEROFCOMMERCENO, TContactsAbstract::ENCRYPTION_CHAMBERCOMMERCE_PASSPHRASE);			                          


		//notes
		$this->setFieldCopyProps(TContactsAbstract::FIELD_NOTES, TContactsAbstract::FIELD_COMPANYNAME);
		$this->setFieldType(TContactsAbstract::FIELD_NOTES, CT_LONGTEXT);
		$this->setFieldLength(TContactsAbstract::FIELD_NOTES, 0);
		$this->setFieldIndexed(TContactsAbstract::FIELD_NOTES, false);

		//Billing: addressline1
		$this->setFieldCopyProps(TContactsAbstract::FIELD_BILLINGADDRESSMISC, TContactsAbstract::FIELD_LASTNAME);
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_BILLINGADDRESSMISC, TContactsAbstract::ENCRYPTION_BILL_ADDR1_PASSPHRASE);			                          

		//Billing: addressline2
		$this->setFieldCopyProps(TContactsAbstract::FIELD_BILLINGADDRESSSTREET, TContactsAbstract::FIELD_LASTNAME);
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_BILLINGADDRESSSTREET, TContactsAbstract::ENCRYPTION_BILL_ADDR2_PASSPHRASE);			                          

		//Billing: postal code / zip code
		$this->setFieldCopyProps(TContactsAbstract::FIELD_BILLINGPOSTALCODEZIP, TContactsAbstract::FIELD_LASTNAME);
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_BILLINGPOSTALCODEZIP, TContactsAbstract::ENCRYPTION_BILL_POSTAL_PASSPHRASE);			                          

		//Billing: city
		$this->setFieldCopyProps(TContactsAbstract::FIELD_BILLINGCITY, TContactsAbstract::FIELD_COMPANYNAME);
		$this->setFieldLength(TContactsAbstract::FIELD_BILLINGCITY, 50);
		$this->setFieldIndexed(TContactsAbstract::FIELD_BILLINGCITY, false);

		//Billing: state/region
		$this->setFieldCopyProps(TContactsAbstract::FIELD_BILLINGSTATEREGION, TContactsAbstract::FIELD_COMPANYNAME);
		$this->setFieldLength(TContactsAbstract::FIELD_BILLINGSTATEREGION, 50);
		$this->setFieldIndexed(TContactsAbstract::FIELD_BILLINGSTATEREGION, false);

        //Billing: country id (from country table)
		$this->setFieldDefaultValue(TContactsAbstract::FIELD_BILLINGCOUNTRYID, '');
		$this->setFieldType(TContactsAbstract::FIELD_BILLINGCOUNTRYID, CT_INTEGER64);
		$this->setFieldLength(TContactsAbstract::FIELD_BILLINGCOUNTRYID, 0);
		$this->setFieldDecimalPrecision(TContactsAbstract::FIELD_BILLINGCOUNTRYID, 0);
		$this->setFieldPrimaryKey(TContactsAbstract::FIELD_BILLINGCOUNTRYID, false);
		$this->setFieldNullable(TContactsAbstract::FIELD_BILLINGCOUNTRYID, false);
		$this->setFieldEnumValues(TContactsAbstract::FIELD_BILLINGCOUNTRYID, null);
		$this->setFieldUnique(TContactsAbstract::FIELD_BILLINGCOUNTRYID, false); 
		$this->setFieldIndexed(TContactsAbstract::FIELD_BILLINGCOUNTRYID, false); 
		$this->setFieldForeignKeyClass(TContactsAbstract::FIELD_BILLINGCOUNTRYID, TSysCountries::class);
		$this->setFieldForeignKeyTable(TContactsAbstract::FIELD_BILLINGCOUNTRYID, TSysCountries::getTable());
		$this->setFieldForeignKeyField(TContactsAbstract::FIELD_BILLINGCOUNTRYID, TModel::FIELD_ID);
		$this->setFieldForeignKeyJoin(TContactsAbstract::FIELD_BILLINGCOUNTRYID);
		$this->setFieldForeignKeyActionOnUpdate(TContactsAbstract::FIELD_BILLINGCOUNTRYID, TModel::FOREIGNKEY_REFERENCE_CASCADE);
		$this->setFieldForeignKeyActionOnDelete(TContactsAbstract::FIELD_BILLINGCOUNTRYID, TModel::FOREIGNKEY_REFERENCE_RESTRICT);
		$this->setFieldAutoIncrement(TContactsAbstract::FIELD_BILLINGCOUNTRYID, false);
		$this->setFieldUnsigned(TContactsAbstract::FIELD_BILLINGCOUNTRYID, true);
        $this->setFieldEncryptionDisabled(TContactsAbstract::FIELD_BILLINGCOUNTRYID);		

		//Billing: VAT number
		$this->setFieldCopyProps(TContactsAbstract::FIELD_BILLINGVATNUMBER, TContactsAbstract::FIELD_BILLINGADDRESSSTREET);
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_BILLINGVATNUMBER, TContactsAbstract::ENCRYPTION_BILL_VATNO_PASSPHRASE);			                          

        //Billing: 2-way encrypted email address
		$this->setFieldCopyProps(TContactsAbstract::FIELD_BILLINGEMAILADDRESSENCRYPTED, TContactsAbstract::FIELD_EMAILADDRESSENCRYPTED);

        //Billing: email fingerprint, so we can lookup the record based on email address
		$this->setFieldCopyProps(TContactsAbstract::FIELD_BILLINGEMAILADDRESSFINGERPRINT, TContactsAbstract::FIELD_EMAILADDRESSFINGERPRINT);
	
        //Billing: bank account number
		$this->setFieldCopyProps(TContactsAbstract::FIELD_BILLINGBANKACCOUNTNO, TContactsAbstract::FIELD_CHAMBEROFCOMMERCENO);
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_BILLINGBANKACCOUNTNO, TContactsAbstract::ENCRYPTION_BILL_BANKACC_PASSPHRASE);			                          

		//Delivery: addressline1
		$this->setFieldCopyProps(TContactsAbstract::FIELD_DELIVERYADDRESSMISC, TContactsAbstract::FIELD_BILLINGADDRESSMISC);
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_DELIVERYADDRESSMISC, TContactsAbstract::ENCRYPTION_DELI_ADDR1_PASSPHRASE);			                          

		//Delivery: addressline2
		$this->setFieldCopyProps(TContactsAbstract::FIELD_DELIVERYADDRESSSTREET, TContactsAbstract::FIELD_BILLINGADDRESSSTREET);
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_DELIVERYADDRESSSTREET, TContactsAbstract::ENCRYPTION_DELI_ADDR2_PASSPHRASE);			                          

		//Delivery: postal code / zip code
		$this->setFieldCopyProps(TContactsAbstract::FIELD_DELIVERYPOSTALCODEZIP, TContactsAbstract::FIELD_BILLINGPOSTALCODEZIP);
		$this->setFieldEncryptionPassphrase(TContactsAbstract::FIELD_DELIVERYPOSTALCODEZIP, TContactsAbstract::ENCRYPTION_DELI_POSTAL_PASSPHRASE);			                          

		//Delivery: city
		$this->setFieldCopyProps(TContactsAbstract::FIELD_DELIVERYCITY, TContactsAbstract::FIELD_BILLINGCITY);

		//Delivery: state/region
		$this->setFieldCopyProps(TContactsAbstract::FIELD_DELIVERYSTATEREGION, TContactsAbstract::FIELD_BILLINGSTATEREGION);

        //Delivery: country id (from country table)
		$this->setFieldCopyProps(TContactsAbstract::FIELD_DELIVERYCOUNTRYID, TContactsAbstract::FIELD_BILLINGCOUNTRYID);
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
		return array(TContactsAbstract::FIELD_CUSTOMIDENTIFIER, 
			TContactsAbstract::FIELD_COMPANYNAME, 
			TContactsAbstract::FIELD_CUSTOMIDENTIFIER, 
			TContactsAbstract::FIELD_FIRSTNAMEINITALS, 
			TContactsAbstract::FIELD_NOTES, 
			TContactsAbstract::FIELD_BILLINGSTATEREGION,
			TContactsAbstract::FIELD_BILLINGCITY,
			TContactsAbstract::FIELD_DELIVERYSTATEREGION,
			TContactsAbstract::FIELD_DELIVERYCITY,
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
	// public static function getTable()
	// {
	// 	return GLOBAL_DB_TABLEPREFIX.'SysLanguages';
	// }
	
	
	
	/**
	 * OVERRIDE BY CHILD CLASS
	 *
	 * Voor de gui functies (zoals het maken van comboboxen) vraagt deze functie op
	 * welke waarde er in het gui-element geplaatst moet worden, zoals de naam bijvoorbeeld
	 *
	 *
	 * return '??? - functie niet overschreven door child klasse';
	*/
	public function getGUIItemName()
	{
		$sResult = '';
		$sCompany = '';
		$bCompanyExists = true;
		$sFirst = '';
		$bFirstExists = true;
		$sLast = '';
		$bLastExists = true;
		$sCity = '';
		$bCityExists = true;


		$sCompany =  $this->get(TContactsAbstract::FIELD_COMPANYNAME);
		$bCompanyExists = strlen($sCompany) > 0;
		$sFirst =  $this->get(TContactsAbstract::FIELD_FIRSTNAMEINITALS);
		$bFirstExists = strlen($sFirst) > 0;
		$sLast =  $this->get(TContactsAbstract::FIELD_LASTNAME, '', true);
		$bLastExists = strlen($sLast) > 0;
		$sBillingCity =  $this->get(TContactsAbstract::FIELD_BILLINGCITY);
		$bBillingCityExists = strlen($sBillingCity) > 0;
		$sIdentifier =  $this->get(TContactsAbstract::FIELD_CUSTOMIDENTIFIER);
		$bIndentifierExists = strlen($sIdentifier) > 0;		

		//start building the result
		$sResult.= $sIdentifier;

		if ($bCompanyExists)
		$sResult.= ' - '.$sCompany;

		if ($bFirstExists || $bLastExists)
		{
			if ($bCompanyExists)
				$sResult.= ' (';

			$sResult.= $sFirst;
			if ($bFirstExists && $bLastExists)//only add space when first and lastname exist
				$sResult.= ' ';

			$sResult.= $sLast;

			if ($bCompanyExists)
				$sResult.= ')';			
		}

		if ($bBillingCityExists)
		{
			$sResult.= ', '.$sBillingCity;			
		}

		// if ((!$bFirstExists) && (!$bLastExists) && (!$bCompanyExists) && (!$bBillingCityExists))
		// {
		// 	$sResult.= ' '.$this->get(TContactsAbstract::FIELD_BILLINGEMAILADDRESSENCRYPTED, '', true);
		// }

		return $sResult;
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
		return 'fikiefleflop'.$this->get(TContactsAbstract::FIELD_COMPANYNAME).'isop'.$this->get(TContactsAbstract::FIELD_LASTNAME).'sdf4f'.$this->get(TContactsAbstract::FIELD_FIRSTNAMEINITALS).'ajwop'.$this->get(TContactsAbstract::FIELD_BILLINGCITY).'nonkietonk';
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
		
			
}

?>