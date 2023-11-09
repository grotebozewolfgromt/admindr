<?php

namespace dr\classes\models;

use dr\classes\models\TModel;
use dr\classes\types\TDateTime;


/**
 * represents all (failed) login attempts of users to prevent brute force attack
 * Also password recovery and account creation is counted as a login attempt to prevent auto creation of accounts
 * 
 * 16 jan 2020 created
 * 23 jun 2021 empty usernames allowed
 * 23 juni 2021 rename TUserLoginAttempts -> TUsersFloodDetectAbstract
 * 23 juni 2021 added fields FIELD_EMAILADDRESS, FIELD_ISFAILEDLOGINATTEMPT, FIELD_ISPASSWORDRESET, FIELD_ISCREATEACCOUNTATTEMPT, FIELD_ISDUPLICATEUSERNAMEATTEMPT
 * 11 sept: TUsersFloodDetectAbstract: getFingerprint() renamed getFingerprintBrowser
 * 2 okt: TUsersFloodDetectAbstract: encryptUsername() gebruikt local pepper en SHA256
 * 2 okt: TUsersFloodDetectAbstract: bugfix: hashed username sql failed omdat veld te kort was
 */

abstract class TUsersFloodDetectAbstract extends TModel
{
	// const FIELD_USERNAME = 'sUsername'; //replaced by username hashed
	const FIELD_USERNAMEHASHEDENCRYPTED = 'sUNH'; //we can't use id of a user, because the user is not yet identified (so we don't have a userid). we use a md5 hashed username as identifier for flood detection (md5 always gives the same result) and it is safer than storing a plain username in the database
	const FIELD_FINGERPRINTEMAIL = 'sFPE'; 
	const FIELD_IPADDRESS = 'sIPAddress';
	const FIELD_DATEATTEMPT = 'dtDateAttempt';
	const FIELD_FINGERPRINTBROWSER = 'sFPB'; //fingerprint of the users computer (browser)	
	const FIELD_ISFAILEDLOGINATTEMPT = 'bIsFailedLoginAttempt';
	const FIELD_ISSUCCEEDEDLOGINATTEMPT = 'bIsSucceededLoginAttempt'; //although we rather have succeeded attempts than failed. it is weird when someone logs in 200 times a day
	const FIELD_ISPASSWORDRESET = 'bIsPasswordReset';
	const FIELD_ISCREATEACCOUNTATTEMPT = 'bIsCreateAccountAttempt';
	const FIELD_ISCREATEDUPLICATEUSERNAMEATTEMPT = 'bIsCreateDuplicateUsernameAttempt'; //prevent people from trying too much accounts that are already taken

	const PEPPER_HASHEDUSERNAME = 'PlW1@%hikM.,'; //we use a local pepper, because all the hashed usernames are the same for the same user (we can't change this otherwise we can't search for it). if we would have a systemwide pepper, if you would brute force the hash by hashing all usernames from the user table, you know the systemwide pepper
	const DIGEST_HASHEDUSERNAME = ENCRYPTION_DIGESTALGORITHM_SHA512;

	const PEPPER_EMAILADDRESSFINGERPRINT = '94nD_&%#igQW'; //we use a local pepper, because all the hashed email addresses are the same for the same user (we can't change this otherwise we can't search for it). if we would have a systemwide pepper, if you would brute force the hash by looking for email characteristics (@ and . [domain, most likely gmail.com]), you know the systemwide pepper
	const DIGEST_EMAILFINGERPRINT = ENCRYPTION_DIGESTALGORITHM_SHA512;

	/**
	 * get username
	 * @return string 
	 */
	// public function getUsername()
	// {
	// 	return $this->get(TUsersFloodDetectAbstract::FIELD_USERNAME);
	// }
	
	/**
	 * 
	 * @param string $sUser
	 */
	// public function setUsername($sUser)
	// {
	// 	$this->set(TUsersFloodDetectAbstract::FIELD_USERNAME, $sUser);
	// }
	
	/**
	 * get hashed username
	 * @return string 
	 */
	public function getUsernameHashedEncrypted()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED);
	}
	
	/**
	 * set username
	 * @param string $sUser
	 */
	public function setUsernameHashedUncrypted($sUser)
	{
		$this->set(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, TUsersFloodDetectAbstract::encryptUsername($sUser));
	}

	/**
	 * hash username
	 *
	 * @return void
	 */
	public static function encryptUsername($sUsernameUncrypted)
	{
		$sResult = '';
		$sResult = $sUsernameUncrypted.TUsersFloodDetectAbstract::PEPPER_HASHEDUSERNAME;
		return hash(TUsersFloodDetectAbstract::DIGEST_HASHEDUSERNAME, $sResult);
	}

	/**
	 * get ip address
	 * @return string 
	 */
	public function getIP()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_IPADDRESS);
	}
	
	/**
	 * set ip address
	 * @param string $sIP
	 */
	public function setIP($sIP)
	{
		$this->set(TUsersFloodDetectAbstract::FIELD_IPADDRESS, $sIP);
	}        
	
	/**
	 * get date login attempt
	 * 
	 * @return TDateTime 
	 */
	public function getDateAttempt()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT);
	}
	
	/**
	 * set date login attempt
	 * 
	 * @param string $sIP
	 */
	public function setDateAttempt(TDateTime $objDateTime)
	{
		$this->setTDateTime(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, $objDateTime);
	}          
        
	/**
	 * set email address fingerprint (encrypted)
	 *
	 * @param string $sAddress
	 * @return void
	 */
	public function setFingerprintEmail($sFingerprint)
	{
		$this->set(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, $sFingerprint);
	}

	/**
	 * just make email fingerprint with seed and digest from this class
	 * WITHOUT setting or getting it in this class
	 *
	 * @param string $sEmailAddress
	 * @return string
	 */
	public function generateEmailAddressFingerprint($sEmailAddress)
	{
		return getFingerprintEmail($sEmailAddress, TUsersFloodDetectAbstract::PEPPER_EMAILADDRESSFINGERPRINT, TUsersFloodDetectAbstract::DIGEST_EMAILFINGERPRINT);
	}

	/**
	 * set email address uncrypted
	 *
	 * @param string $sAddress
	 * @return void
	 */
	public function setEmailAddressUncrypted($sEmailAddress)
	{
		$this->setFingerprintEmail($this->generateEmailAddressFingerprint($sEmailAddress));
	}	

	/**
	 * get email address
	 * 
	 * @return string 
	 */
	public function getFingerprintEmail()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL);
	}

	/**
	 * set fingerprint browser
	 *
	 * @param string $sAddress
	 * @return void
	 */
	public function setFingerprintBrowser($sFingerPrint)
	{
		$this->set(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, $sFingerPrint);
	}

	/**
	 * get fingerprint browser
	 * 
	 * @return string 
	 */
	public function getFingerprintBrowser()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER);
	}

	/**
	 * set failed login attempt
	 *
	 * @param int $sAddress
	 * @return void
	 */
	public function setIsFailedLoginAttempt($bStatus)
	{
		$this->set(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, $bStatus);
	}

	/**
	 * get failed login attempt
	 * 
	 * @return string 
	 */
	public function getIsFailedLoginAttempt()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT);
	}

	/**
	 * set succeeded login attempt
	 *
	 * @param int $sAddress
	 * @return void
	 */
	public function setIsSucceededLoginAttempt($bStatus)
	{
		$this->set(TUsersFloodDetectAbstract::FIELD_ISSUCCEEDEDLOGINATTEMPT, $bStatus);
	}

	/**
	 * get succeeded login attempt
	 * 
	 * @return string 
	 */
	public function getIsSucceededLoginAttempt()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_ISSUCCEEDEDLOGINATTEMPT);
	}	

	/**
	 * set password reset
	 *
	 * @param int $sAddress
	 * @return void
	 */
	public function setIsPasswordReset($bStatus)
	{
		$this->set(TUsersFloodDetectAbstract::FIELD_ISPASSWORDRESET, $bStatus);
	}

	/**
	 * get password reset
	 * 
	 * @return string 
	 */
	public function getIsPasswordReset()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_ISPASSWORDRESET);
	}	

	/**
	 * set create account attempt
	 *
	 * @param int $sAddress
	 * @return void
	 */
	public function setIsCreateAccountAttempt($bStatus)
	{
		$this->set(TUsersFloodDetectAbstract::FIELD_ISCREATEACCOUNTATTEMPT, $bStatus);
	}

	/**
	 * get create account attempt
	 * 
	 * @return string 
	 */
	public function getIsCreateAccountAttempt()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_ISCREATEACCOUNTATTEMPT);
	}	


	/**
	 * set create account duplicate username attempt
	 *
	 * @param int $sAddress
	 * @return void
	 */
	public function setIsCreateDuplicateUsernameAttempt($bStatus)
	{
		$this->set(TUsersFloodDetectAbstract::FIELD_ISCREATEDUPLICATEUSERNAMEATTEMPT, $bStatus);
	}

	/**
	 * get create account duplicate username attempt
	 * 
	 * @return string 
	 */
	public function getIsCreateDuplicateUsernameAttempt()
	{
		return $this->get(TUsersFloodDetectAbstract::FIELD_ISCREATEDUPLICATEUSERNAMEATTEMPT);
	}		


	/**
	 * delete logs older than 6 months from database
	 * (you can set the number of days as parameter)
	 *
	 * @param int $iDaysOld number of days that the logs be old before they get deleted, default is 183 (that is 6 months)
	 * @return boolean true = success, false is error
	 */
	public function deleteOldLogsFromDB($iDaysOld = 183)
	{
		$bResult = false;
		$objCopy = $this->getCopy();
        $objTime = new TDateTime(time());
        $objTime->subtractDays($iDaysOld);
        $objCopy->newQuery();
        $objCopy->find(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, $objTime, COMPARISON_OPERATOR_LESS_THAN);
        $bResult = $objCopy->deleteFromDB(true);
        unset($objUsersAttempts);
        unset($objTime);	
		
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
		$objToday = new TDateTime(time());
		$this->setDateAttempt($objToday);
		unset($objToday);
	}
	
	
	
	/**
	 * defines the fields in the tables
	 * i.e. types, default values, enum values, referenced tables etc
	*/
	public function defineTable()
	{
		//username ===> replaced by username hashed
		// $this->setFieldDefaultValue(TUsersFloodDetectAbstract::FIELD_USERNAME, '');
		// $this->setFieldType(TUsersFloodDetectAbstract::FIELD_USERNAME, CT_VARCHAR);
		// $this->setFieldLength(TUsersFloodDetectAbstract::FIELD_USERNAME, 100);
		// $this->setFieldDecimalPrecision(TUsersFloodDetectAbstract::FIELD_USERNAME, 0);
		// $this->setFieldPrimaryKey(TUsersFloodDetectAbstract::FIELD_USERNAME, false);
		// $this->setFieldNullable(TUsersFloodDetectAbstract::FIELD_USERNAME, true);//is possible to be empty, sometimes we want to register attempts for password recovery and account creation
		// $this->setFieldEnumValues(TUsersFloodDetectAbstract::FIELD_USERNAME, null);
		// $this->setFieldUnique(TUsersFloodDetectAbstract::FIELD_USERNAME, false);
		// $this->setFieldIndexed(TUsersFloodDetectAbstract::FIELD_USERNAME, false);
		// $this->setFieldForeignKeyClass(TUsersFloodDetectAbstract::FIELD_USERNAME, null);
		// $this->setFieldForeignKeyTable(TUsersFloodDetectAbstract::FIELD_USERNAME, null);
		// $this->setFieldForeignKeyField(TUsersFloodDetectAbstract::FIELD_USERNAME, null);
		// $this->setFieldForeignKeyJoin(TUsersFloodDetectAbstract::FIELD_USERNAME, null);
		// $this->setFieldForeignKeyActionOnUpdate(TUsersFloodDetectAbstract::FIELD_USERNAME, null);
		// $this->setFieldForeignKeyActionOnDelete(TUsersFloodDetectAbstract::FIELD_USERNAME, null);
		// $this->setFieldAutoIncrement(TUsersFloodDetectAbstract::FIELD_USERNAME, false);
		// $this->setFieldUnsigned(TUsersFloodDetectAbstract::FIELD_USERNAME, false);
		// $this->setFieldEncryptionDisabled(TUsersFloodDetectAbstract::FIELD_USERNAME);			                                              

		//username hashed
		$this->setFieldDefaultValue(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, '');
		$this->setFieldType(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, CT_VARCHAR);
		$this->setFieldLength(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, 255);
		$this->setFieldDecimalPrecision(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, 0);
		$this->setFieldPrimaryKey(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, false);
		$this->setFieldNullable(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, true);//is possible to be empty, sometimes we want to register attempts for password recovery and account creation
		$this->setFieldEnumValues(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, null);
		$this->setFieldUnique(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, false);
		$this->setFieldIndexed(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, false);
		$this->setFieldForeignKeyClass(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, null);
		$this->setFieldForeignKeyTable(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, null);
		$this->setFieldForeignKeyField(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, null);
		$this->setFieldForeignKeyJoin(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, null);
		$this->setFieldForeignKeyActionOnUpdate(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, null);
		$this->setFieldForeignKeyActionOnDelete(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, null);
		$this->setFieldAutoIncrement(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, false);
		$this->setFieldUnsigned(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED, false);		
		$this->setFieldEncryptionDisabled(TUsersFloodDetectAbstract::FIELD_USERNAMEHASHEDENCRYPTED);


		//email address fingerprint
		$this->setFieldDefaultValue(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, '');
		$this->setFieldType(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, CT_VARCHAR);
		$this->setFieldLength(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, LENGTH_STRING_MD5);
		$this->setFieldDecimalPrecision(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, 0);
		$this->setFieldPrimaryKey(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, false);
		$this->setFieldNullable(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, true);//is possible to be empty, sometimes we want to register attempts for password recovery and account creation
		$this->setFieldEnumValues(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, null);
		$this->setFieldUnique(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, false);
		$this->setFieldIndexed(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, true);
		$this->setFieldForeignKeyClass(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, null);
		$this->setFieldForeignKeyTable(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, null);
		$this->setFieldForeignKeyField(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, null);
		$this->setFieldForeignKeyJoin(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, null);
		$this->setFieldForeignKeyActionOnUpdate(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, null);
		$this->setFieldForeignKeyActionOnDelete(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, null);
		$this->setFieldAutoIncrement(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, false);
		$this->setFieldUnsigned(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL, false);		
		$this->setFieldEncryptionDisabled(TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL);

		
		//ip
		$this->setFieldCopyProps(TUsersFloodDetectAbstract::FIELD_IPADDRESS, TUsersFloodDetectAbstract::FIELD_FINGERPRINTEMAIL);
        $this->setFieldLength(TUsersFloodDetectAbstract::FIELD_IPADDRESS, LENGTH_STRING_IPV6); //ip v6 has 45 characters
        $this->setFieldIndexed(TUsersFloodDetectAbstract::FIELD_IPADDRESS, true); 
                
		//datetime of attempt
		$this->setFieldDefaultValue(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, 0);
		$this->setFieldType(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, CT_DATETIME);
		$this->setFieldLength(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, 0);
		$this->setFieldDecimalPrecision(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, 0);
		$this->setFieldPrimaryKey(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, false);
		$this->setFieldNullable(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, false);
		$this->setFieldEnumValues(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, null);
		$this->setFieldUnique(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, false);
		$this->setFieldIndexed(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, false);
		$this->setFieldForeignKeyClass(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, null);
		$this->setFieldForeignKeyTable(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, null);
		$this->setFieldForeignKeyField(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, null);
		$this->setFieldForeignKeyJoin(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, null);
		$this->setFieldForeignKeyActionOnUpdate(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, null);
		$this->setFieldForeignKeyActionOnDelete(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, null);
		$this->setFieldAutoIncrement(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, false);
		$this->setFieldUnsigned(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT, false);    
		$this->setFieldEncryptionDisabled(TUsersFloodDetectAbstract::FIELD_DATEATTEMPT);		
		
		
		//fingerprint browser
		$this->setFieldDefaultValue(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, '');
		$this->setFieldType(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, CT_VARCHAR);
		$this->setFieldLength(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, LENGTH_STRING_MD5);
		$this->setFieldDecimalPrecision(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, 0);
		$this->setFieldPrimaryKey(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, false);
		$this->setFieldNullable(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, true);//is possible to be empty
		$this->setFieldEnumValues(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, null);
		$this->setFieldUnique(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, false);
		$this->setFieldIndexed(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, true);
		$this->setFieldForeignKeyClass(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, null);
		$this->setFieldForeignKeyTable(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, null);
		$this->setFieldForeignKeyField(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, null);
		$this->setFieldForeignKeyJoin(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, null);
		$this->setFieldForeignKeyActionOnUpdate(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, null);
		$this->setFieldForeignKeyActionOnDelete(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, null);
		$this->setFieldAutoIncrement(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, false);
		$this->setFieldUnsigned(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER, false);
		$this->setFieldEncryptionDisabled(TUsersFloodDetectAbstract::FIELD_FINGERPRINTBROWSER);				


		//failed login attempt
		$this->setFieldDefaultValue(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, 0);
		$this->setFieldType(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, CT_BOOL);
		$this->setFieldLength(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, 0);
		$this->setFieldDecimalPrecision(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, 0);
		$this->setFieldPrimaryKey(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, false);
		$this->setFieldNullable(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, false);
		$this->setFieldEnumValues(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, null);
		$this->setFieldUnique(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, false);
		$this->setFieldIndexed(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, false);
		$this->setFieldForeignKeyClass(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, null);
		$this->setFieldForeignKeyTable(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, null);
		$this->setFieldForeignKeyField(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, null);
		$this->setFieldForeignKeyJoin(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, null);		
		$this->setFieldForeignKeyActionOnUpdate(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, null);
		$this->setFieldForeignKeyActionOnDelete(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, null);
		$this->setFieldAutoIncrement(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, false);
		$this->setFieldUnsigned(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT, false);	
		$this->setFieldEncryptionDisabled(TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT);							

		//is succeeded login attempt
		$this->setFieldCopyProps(TUsersFloodDetectAbstract::FIELD_ISSUCCEEDEDLOGINATTEMPT, TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT);
		
		//is password reset
		$this->setFieldCopyProps(TUsersFloodDetectAbstract::FIELD_ISPASSWORDRESET, TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT);

		//is create account attempt
		$this->setFieldCopyProps(TUsersFloodDetectAbstract::FIELD_ISCREATEACCOUNTATTEMPT, TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT);

		//Is Create Duplicate Username Attempt
		$this->setFieldCopyProps(TUsersFloodDetectAbstract::FIELD_ISCREATEDUPLICATEUSERNAMEATTEMPT, TUsersFloodDetectAbstract::FIELD_ISFAILEDLOGINATTEMPT);
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
		return array(TUsersFloodDetectAbstract::FIELD_IPADDRESS, TUsersFloodDetectAbstract::FIELD_DATEATTEMPT);
	}
	
	/**
	 * use the auto-added id-field ?
	 * @return bool
	*/
	public function getTableUseIDField()
	{
		return false;
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
		return false;
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
		return GLOBAL_DB_TABLEPREFIX.'SysWebsites';
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
		return $this->get($this->get(TUsersFloodDetectAbstract::FIELD_IPADDRESS));
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
		return '';
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
			return true;
	} 
	
	/**
	 * is randomid field a primary key?
	 */        
	public function getTableUseRandomIDAsPrimaryKey()
	{
			return true;
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