<?php

namespace dr\classes\models;

use dr\classes\models\TModel;

/**
 * An abstract class for usergroup permissions
 * 
 * when you want to inherit this class: add a field to link to a user/role/usergroup
 * 
 * the resource is the resource you want to protect, for example: "books/authors/view"
 * 
 * created 30-10-2020
 * 30 okt 2020: TPermissionsAbstract: created
 * 
 */

abstract class TPermissionsAbstract extends TModel
{
    const FIELD_RESOURCE                = 'sResource';
    const FIELD_ALLOWED                 = 'bAllowed';     
           
        
    /**
     * get resource
     * 
     * @return string
     */
    public function getResource()
    {
        return $this->get(TPermissionsAbstract::FIELD_RESOURCE);
    }

    /**
     * set resource
     * 
     * @param string $sName
     */
    public function setResource($sName)
    {
        $this->set(TPermissionsAbstract::FIELD_RESOURCE, $sName);
    }


    /**
     * get allowed
     * 
     * @return bool
     */
    public function getAllowed()
    {
        return $this->get(TPermissionsAbstract::FIELD_ALLOWED);
    }

    /**
     * set allowed
     * 
     * @param string $sName
     */
    public function setAllowed($bAllowed)
    {
        $this->set(TPermissionsAbstract::FIELD_ALLOWED, $bAllowed);
    }


    
    
    /**
     * This function is called in the constructor and the clear() function
     * this is used to define default values for fields
     * 
     * initialize values
     */
    public function initRecord()
    {
        $this->setResource('');
        $this->setAllowed(false);    
    }
	
	
	
    /**
     * defines the fields in the tables
     * i.e. types, default values, enum values, referenced tables etc
    */
    public function defineTable()
    {
       

        //resource
        $this->setFieldDefaultValue(TPermissionsAbstract::FIELD_RESOURCE, null);
        $this->setFieldType(TPermissionsAbstract::FIELD_RESOURCE, CT_VARCHAR);
        $this->setFieldLength(TPermissionsAbstract::FIELD_RESOURCE, 255);
        $this->setFieldDecimalPrecision(TPermissionsAbstract::FIELD_RESOURCE, 0);
        $this->setFieldPrimaryKey(TPermissionsAbstract::FIELD_RESOURCE, false);
        $this->setFieldNullable(TPermissionsAbstract::FIELD_RESOURCE, true);
        $this->setFieldEnumValues(TPermissionsAbstract::FIELD_RESOURCE, null);
        $this->setFieldUnique(TPermissionsAbstract::FIELD_RESOURCE, false);
        $this->setFieldIndexed(TPermissionsAbstract::FIELD_RESOURCE, false);
        $this->setFieldForeignKeyClass(TPermissionsAbstract::FIELD_RESOURCE, null);
        $this->setFieldForeignKeyTable(TPermissionsAbstract::FIELD_RESOURCE, null);
        $this->setFieldForeignKeyField(TPermissionsAbstract::FIELD_RESOURCE, null);
        $this->setFieldForeignKeyJoin(TPermissionsAbstract::FIELD_RESOURCE, null);
        $this->setFieldForeignKeyActionOnUpdate(TPermissionsAbstract::FIELD_RESOURCE, null);
        $this->setFieldForeignKeyActionOnDelete(TPermissionsAbstract::FIELD_RESOURCE, null);
        $this->setFieldAutoIncrement(TPermissionsAbstract::FIELD_RESOURCE, false);
        $this->setFieldUnsigned(TPermissionsAbstract::FIELD_RESOURCE, false);
		$this->setFieldEncryptionDisabled(TPermissionsAbstract::FIELD_RESOURCE);

        //allowed
        $this->setFieldDefaultValue(TPermissionsAbstract::FIELD_ALLOWED, false);
        $this->setFieldType(TPermissionsAbstract::FIELD_ALLOWED, CT_BOOL);
        $this->setFieldLength(TPermissionsAbstract::FIELD_ALLOWED, 0);
        $this->setFieldDecimalPrecision(TPermissionsAbstract::FIELD_ALLOWED, 0);
        $this->setFieldPrimaryKey(TPermissionsAbstract::FIELD_ALLOWED, false);
        $this->setFieldNullable(TPermissionsAbstract::FIELD_ALLOWED, false);
        $this->setFieldEnumValues(TPermissionsAbstract::FIELD_ALLOWED, null);
        $this->setFieldUnique(TPermissionsAbstract::FIELD_ALLOWED, false);
        $this->setFieldIndexed(TPermissionsAbstract::FIELD_ALLOWED, false);
        $this->setFieldForeignKeyClass(TPermissionsAbstract::FIELD_ALLOWED, null);
        $this->setFieldForeignKeyTable(TPermissionsAbstract::FIELD_ALLOWED, null);
        $this->setFieldForeignKeyField(TPermissionsAbstract::FIELD_ALLOWED, null);
        $this->setFieldForeignKeyJoin(TPermissionsAbstract::FIELD_ALLOWED, null);
        $this->setFieldForeignKeyActionOnUpdate(TPermissionsAbstract::FIELD_ALLOWED, null);
        $this->setFieldForeignKeyActionOnDelete(TPermissionsAbstract::FIELD_ALLOWED, null);
        $this->setFieldAutoIncrement(TPermissionsAbstract::FIELD_ALLOWED, false);
        $this->setFieldUnsigned(TPermissionsAbstract::FIELD_ALLOWED, false);	
		$this->setFieldEncryptionDisabled(TPermissionsAbstract::FIELD_ALLOWED);

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
        return array(TPermissionsAbstract::FIELD_RESOURCE);
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
        return $this->get(TPermissionsAbstract::FIELD_RESOURCE);
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


    

    /****************************************************************************
     *              ABSTRACT METHODS
    ****************************************************************************/
    

    
}

?>