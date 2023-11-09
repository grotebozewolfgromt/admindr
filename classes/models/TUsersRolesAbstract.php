<?php

namespace dr\classes\models;

use dr\classes\models\TModel;

/**
 * An abstract class for groups of users
 * 
 * 
 * 
 * created 23 jan 2020
 * 23 jan 2020: TUsersGroupsAbstract: created
 * 16 nov 2022: TUsersGroupsAbstract renamed to TUsersRolesAbstract
 * 18 nov 2022: TUsersRolesAbstract: added: FIELD_ISANONYMOUS
 * 18 nov 2022: TUsersRolesAbstract: added: FIELD_ISMANAGABLEBYOTHERUSERS
 * 
 * 
 * IS MANAGABLE BY OTHER USERS: FIELD_ISMANAGABLEBYOTHERUSERS
 * This flag indicates if users can create other users with this role, or change roles of certain users.
 * By "manage" I mean delete or create users (not delete or create roles).
 * For example: 
 * my client (RoleA) has hired a video editor (RoleB) and a script writer (RoleC). 
 * my client (RoleA) wants to be able to create users (with role RoleB and RoleC) for these roles 
 * the role RoleA doesn't get this flag
 * the roles RoleB+RoleC do get this flag
 * 
 */

abstract class TUsersRolesAbstract extends TModel
{
    const FIELD_ROLENAME                = 'sRoleName';
    const FIELD_DESCRIPTION             = 'sDescription'; //describes the role, not intended to be language specific (unnessasary complication for such a small feature of the system)
    const FIELD_ISANONYMOUS             = 'bIsAnonymous'; //is it a role of users that are not logged in?
    const FIELD_ISSYSTEMROLE            = 'bIsSystemRole'; //meaning that it is important for the system to work properly, therefore can't delete
    const FIELD_MAXUSERSINACCOUNT       = 'iMaxUsersInAccount'; //this many users are allowed in an account. For example: my client (with role "account admin") is allowed to add 5 users to their account 0 = unlimited, < 0 = no users

    /**
     * get role name
     * 
     * @return string
     */
    public function getRoleName()
    {
        return $this->get(TUsersRolesAbstract::FIELD_ROLENAME);
    }

    /**
     * set role name
     * 
     * @param string $sName
     */
    public function setRoleName($sName)
    {
        $this->set(TUsersRolesAbstract::FIELD_ROLENAME, $sName);
    }

    
    
    /**
     * set description
     * 
     * @return string
     */
    public function getDescription()
    {
        return $this->get(TUsersRolesAbstract::FIELD_DESCRIPTION);
    }

    /**
     * set description
     * 
     * @param string $sDescription
     */
    public function setDescription($sDescription)
    {
        $this->set(TUsersRolesAbstract::FIELD_DESCRIPTION, $sDescription);
    }

    /**
     * get maximum amount of allowed users in an account
     * 0 = unlimited
     * 
     * @return string
     */
    public function getMaxUsersInAccount()
    {
        return $this->get(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT);
    }

    /**
     * set maximum amount of allowed users in an account
     * 0 = unlimited
     * 
     * @param string $sDescription
     */
    public function setMaxUsersInAccount($iAmount)
    {
        $this->set(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, $iAmount);
    }

   /**
     * set anonymous
     * 
     * @return string
     */
    public function getIsAnonymous()
    {
        return $this->get(TUsersRolesAbstract::FIELD_ISANONYMOUS);
    }

    /**
     * set anonymous
     * 
     * @param string $bValue is anonymous user or not
     */
    public function setIsAnonymous($bValue)
    {
        $this->set(TUsersRolesAbstract::FIELD_ISANONYMOUS, $bValue);
    }      

    /**
     * get system role (cant delete)
     * 
     * @return string
     */
    public function getIsSystemRole()
    {
        return $this->get(TUsersRolesAbstract::FIELD_ISSYSTEMROLE);
    }

    /**
     * set system role (cant delete)
     * 
     * @param string $bValue is managable user or not
     */
    public function setIsSystemRole($bValue)
    {
        $this->set(TUsersRolesAbstract::FIELD_ISSYSTEMROLE, $bValue);
    }    
   


    
    /**
     * looks in database if usernames already exists in database
     * this function excludes the current record 
     * (it looks at all records except with current id if it's an existing record)
     *  
     * @param string $sUsername
     */
    public function isGroupnameTakenDB($sName)
    {
        $bResult = false;
        $objClone = clone $this;
        $objClone->clear();

        //exclude current record
        if (!$this->getNew())
            $objClone->find(TUsersRolesAbstract::FIELD_ID, $this->getID(), COMPARISON_OPERATOR_NOT_EQUAL_TO);
        
        if ($objClone->loadFromDBByGroupname($sName))
        {
            if ($objClone->count() > 0) //username taken
                $bResult = true;
        }
        
        unset($objClone);
        return $bResult;        
    }




    /**
     * load user from database with username $sUsername
     * 
     * @param string $sUsername
     */
    public function loadFromDBByGroupname($sName)
    {
        $bResult = false;
        $this->find(TUsersRolesAbstract::FIELD_ROLENAME, $sName);
        if ($this->loadFromDB(true))
            $bResult = true;
        $this->newQuery();
        return $bResult;
    }
    
    
    /**
     * preventing system roles from being deleted
     */
    public function deleteFromDB($bYesISpecifiedAWhereInMyModel, $bCheckAffectedRows = false)
    {
        $this->find(TUsersRolesAbstract::FIELD_ISSYSTEMROLE, false);

        return parent::deleteFromDB($bYesISpecifiedAWhereInMyModel, $bCheckAffectedRows);
    }
	
    /**
     * This function is called in the constructor and the clear() function
     * this is used to define default values for fields
     * 
     * initialize values
     */
    public function initRecord()
    {    
    }
	
	
	
    /**
     * defines the fields in the tables
     * i.e. types, default values, enum values, referenced tables etc
    */
    public function defineTable()
    {
        //role name
        $this->setFieldDefaultValue(TUsersRolesAbstract::FIELD_ROLENAME, '');
        $this->setFieldType(TUsersRolesAbstract::FIELD_ROLENAME, CT_VARCHAR);
        $this->setFieldLength(TUsersRolesAbstract::FIELD_ROLENAME, 50);
        $this->setFieldDecimalPrecision(TUsersRolesAbstract::FIELD_ROLENAME, 0);
        $this->setFieldPrimaryKey(TUsersRolesAbstract::FIELD_ROLENAME, false);
        $this->setFieldNullable(TUsersRolesAbstract::FIELD_ROLENAME, false);
        $this->setFieldEnumValues(TUsersRolesAbstract::FIELD_ROLENAME, null);
        $this->setFieldUnique(TUsersRolesAbstract::FIELD_ROLENAME, true);
        $this->setFieldIndexed(TUsersRolesAbstract::FIELD_ROLENAME, false);
        $this->setFieldForeignKeyClass(TUsersRolesAbstract::FIELD_ROLENAME, null);
        $this->setFieldForeignKeyTable(TUsersRolesAbstract::FIELD_ROLENAME, null);
        $this->setFieldForeignKeyField(TUsersRolesAbstract::FIELD_ROLENAME, null);
        $this->setFieldForeignKeyJoin(TUsersRolesAbstract::FIELD_ROLENAME, null);
        $this->setFieldForeignKeyActionOnUpdate(TUsersRolesAbstract::FIELD_ROLENAME, null);
        $this->setFieldForeignKeyActionOnDelete(TUsersRolesAbstract::FIELD_ROLENAME, null);
        $this->setFieldAutoIncrement(TUsersRolesAbstract::FIELD_ROLENAME, false);
        $this->setFieldUnsigned(TUsersRolesAbstract::FIELD_ROLENAME, false);
		$this->setFieldEncryptionDisabled(TUsersRolesAbstract::FIELD_ROLENAME);

        //description
        $this->setFieldCopyProps(TUsersRolesAbstract::FIELD_DESCRIPTION, TUsersRolesAbstract::FIELD_ROLENAME);
        $this->setFieldLength(TUsersRolesAbstract::FIELD_DESCRIPTION, 100);
        $this->setFieldUnique(TUsersRolesAbstract::FIELD_DESCRIPTION, false);


        //max users in account
        $this->setFieldDefaultValue(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, 0);
        $this->setFieldType(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, CT_INTEGER64);
        $this->setFieldLength(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, 0);
        $this->setFieldDecimalPrecision(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, 0);
        $this->setFieldPrimaryKey(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, false);
        $this->setFieldNullable(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, false);
        $this->setFieldEnumValues(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, null);
        $this->setFieldUnique(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, false);
        $this->setFieldIndexed(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, false);
        $this->setFieldForeignKeyClass(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, null);
        $this->setFieldForeignKeyTable(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, null);
        $this->setFieldForeignKeyField(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, null);
        $this->setFieldForeignKeyJoin(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, null);
        $this->setFieldForeignKeyActionOnUpdate(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, null);
        $this->setFieldForeignKeyActionOnDelete(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, null);
        $this->setFieldAutoIncrement(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, false);
        $this->setFieldUnsigned(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT, false);
		$this->setFieldEncryptionDisabled(TUsersRolesAbstract::FIELD_MAXUSERSINACCOUNT);

        //is anonymous
        $this->setFieldDefaultValue(TUsersRolesAbstract::FIELD_ISANONYMOUS, '');
        $this->setFieldType(TUsersRolesAbstract::FIELD_ISANONYMOUS, CT_BOOL);
        $this->setFieldLength(TUsersRolesAbstract::FIELD_ISANONYMOUS, 0);
        $this->setFieldDecimalPrecision(TUsersRolesAbstract::FIELD_ISANONYMOUS, 0);
        $this->setFieldPrimaryKey(TUsersRolesAbstract::FIELD_ISANONYMOUS, false);
        $this->setFieldNullable(TUsersRolesAbstract::FIELD_ISANONYMOUS, false);
        $this->setFieldEnumValues(TUsersRolesAbstract::FIELD_ISANONYMOUS, null);
        $this->setFieldUnique(TUsersRolesAbstract::FIELD_ISANONYMOUS, false);
        $this->setFieldIndexed(TUsersRolesAbstract::FIELD_ISANONYMOUS, false);
        $this->setFieldForeignKeyClass(TUsersRolesAbstract::FIELD_ISANONYMOUS, null);
        $this->setFieldForeignKeyTable(TUsersRolesAbstract::FIELD_ISANONYMOUS, null);
        $this->setFieldForeignKeyField(TUsersRolesAbstract::FIELD_ISANONYMOUS, null);
        $this->setFieldForeignKeyJoin(TUsersRolesAbstract::FIELD_ISANONYMOUS, null);
        $this->setFieldForeignKeyActionOnUpdate(TUsersRolesAbstract::FIELD_ISANONYMOUS, null);
        $this->setFieldForeignKeyActionOnDelete(TUsersRolesAbstract::FIELD_ISANONYMOUS, null);
        $this->setFieldAutoIncrement(TUsersRolesAbstract::FIELD_ISANONYMOUS, false);
        $this->setFieldUnsigned(TUsersRolesAbstract::FIELD_ISANONYMOUS, false);
		$this->setFieldEncryptionDisabled(TUsersRolesAbstract::FIELD_ISANONYMOUS);        


        //is system user (=can't delete)
        $this->setFieldCopyProps(TUsersRolesAbstract::FIELD_ISSYSTEMROLE, TUsersRolesAbstract::FIELD_ISANONYMOUS);
        
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
        return array(TUsersRolesAbstract::FIELD_ROLENAME);
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
        return $this->get(TUsersRolesAbstract::FIELD_ROLENAME);
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
        return 'asdff556g_'.$this->get(TUsersRolesAbstract::FIELD_ROLENAME).'yobitch'.boolToStr($this->getIsAnonymous(), true).boolToStr($this->getIsSystemRole(), true);
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