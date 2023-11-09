<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Sys_Contacts\controllers;

use dr\classes\models\TModel;
use dr\classes\controllers\TCRUDDetailSaveController;

use dr\classes\dom\tag\form\Form;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\Li;
use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\form\Option;
use dr\classes\dom\tag\form\Textarea;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Onlynumeric;
use dr\classes\dom\validator\Required;

//don't forget ;)
use dr\classes\models\TSysContacts;
use dr\classes\models\TSysCountries;
use dr\modules\Mod_Blog\controllers\detailsave_blog;
use dr\modules\Mod_Sys_Contacts\Mod_Sys_Contacts;


include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');

/**
 * Description of TCRUDDetailSaveLanguages
 *
 * @author drenirie
 */
class detailsave_contacts extends TCRUDDetailSaveController
{
    
    private $objEdtCustomIdentifier = null;//dr\classes\dom\tag\form\InputText
    private $objEdtCompanyName = null;//dr\classes\dom\tag\form\InputText
    private $objEdtFirstNameInitials = null;//dr\classes\dom\tag\form\InputText
    private $objEdtLastName = null;//dr\classes\dom\tag\form\InputText
    private $objEdtLastNamePrefix = null;//dr\classes\dom\tag\form\InputText
    private $objEdtEmailAddress = null;//dr\classes\dom\tag\form\InputText
    private $objChkOnMailingList = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objChkOnBlackList = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objEdtPhone1 = null;//dr\classes\dom\tag\form\InputText
    private $objEdtPhone2 = null;//dr\classes\dom\tag\form\InputText        
    private $objEdtChamberCommerce = null;//dr\classes\dom\tag\form\InputText        
    private $objTxtArNotes = null;//dr\classes\dom\tag\form\Textarea
    
    private $objEdtBillingAddressLine1 = null;//dr\classes\dom\tag\form\InputText
    private $objEdtBillingAddressLine2 = null;//dr\classes\dom\tag\form\InputText
    private $objEdtBillingPostalCodeZip = null;//dr\classes\dom\tag\form\InputText
    private $objEdtBillingCity = null;//dr\classes\dom\tag\form\InputText
    private $objEdtBillingStateRegion = null;//dr\classes\dom\tag\form\InputText
    private $objSelBillingCountryID = null;//dr\classes\dom\tag\form\Select
    private $objEdtBillingVatNumber = null;//dr\classes\dom\tag\form\InputText
    private $objEdtBillingEmailAddress = null;//dr\classes\dom\tag\form\InputText
    private $objEdtBillingBankAccountNo = null;//dr\classes\dom\tag\form\InputText    

    private $objEdtDeliveryAddressLine1 = null;//dr\classes\dom\tag\form\InputText
    private $objEdtDeliveryAddressLine2 = null;//dr\classes\dom\tag\form\InputText
    private $objEdtDeliveryPostalCodeZip = null;//dr\classes\dom\tag\form\InputText
    private $objEdtDeliveryCity = null;//dr\classes\dom\tag\form\InputText
    private $objEdtDeliveryStateRegion = null;//dr\classes\dom\tag\form\InputText
    private $objSelDeliveryCountryID = null;//dr\classes\dom\tag\form\Select

    private $objChkIsClient = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objChkIsSupplier = null;//dr\classes\dom\tag\form\InputCheckbox

        
    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
        // $sFormSectionType = '';
        // $sFormSectionType = transm($this->getModule(), 'form_section_general', 'General');
        $sFormSectionPersonal = '';
        $sFormSectionPersonal = transm($this->getModule(), 'form_section_personal', 'Personal');
        $sFormSectionBilling = '';
        $sFormSectionBilling = transm($this->getModule(), 'form_section_billing', 'Billing');
        $sFormSectionDelivery = '';
        $sFormSectionDelivery = transm($this->getModule(), 'form_section_delivery', 'Delivery');
        $sFormSectionMisc = '';
        $sFormSectionMisc = transm($this->getModule(), 'form_section_misc', 'Miscellaneous');


            //is client
        $this->objChkIsClient = new InputCheckbox();
        $this->objChkIsClient->setNameAndID('chkIsClient');
        $this->getForm()->add($this->objChkIsClient, '', transm($this->getModule(), 'form_field_isclient', 'is client'));   
            
            //is supplier
        $this->objChkIsSupplier = new InputCheckbox();
        $this->objChkIsSupplier->setNameAndID('chkIsSupplier');
        $this->getForm()->add($this->objChkIsSupplier,  '', transm($this->getModule(), 'form_field_issupplier', 'is supplier'));         
    

            //on mailing list
        $this->objChkOnMailingList = new InputCheckbox();
        $this->objChkOnMailingList->setNameAndID('chkOnMailingList');
        $this->getForm()->add($this->objChkOnMailingList, '', transm($this->getModule(), 'form_field_onmailinglist', 'on mailing list'));   
        
                //on black list
        $this->objChkOnBlackList = new InputCheckbox();
        $this->objChkOnBlackList->setNameAndID('chkOnBlackList');
        $this->getForm()->add($this->objChkOnBlackList, '', transm($this->getModule(), 'form_field_onblacklist', 'on blacklist'));   
    

            //custom identifier
        $this->objEdtCustomIdentifier = new InputText();
        $this->objEdtCustomIdentifier->setNameAndID('edtCustomIdentifier');
        $this->objEdtCustomIdentifier->setClass('fullwidthtag');         
        $this->objEdtCustomIdentifier->setMaxLength(50);                
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtCustomIdentifier->addValidator($objValidator);    
        $this->getForm()->add($this->objEdtCustomIdentifier, '', transm($this->getModule(), 'form_field_customidentifier', 'Label (to identify this contact just to you, so you can search for it)'));


            //company name
        $this->objEdtCompanyName = new InputText();
        $this->objEdtCompanyName->setNameAndID('edtCompanyName');
        $this->objEdtCompanyName->setClass('fullwidthtag');                         
        $this->objEdtCompanyName->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtCompanyName->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtCompanyName, '', transm($this->getModule(), 'form_field_companyname', 'Company name')); 

            //chamber of commerce number
        $this->objEdtChamberCommerce = new InputText();
        $this->objEdtChamberCommerce->setNameAndID('edtChamberOfCommerceNumber');
        $this->objEdtChamberCommerce->setClass('fullwidthtag');                         
        $this->objEdtChamberCommerce->setMaxLength(25);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '25'), 25);
        $this->objEdtChamberCommerce->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtChamberCommerce, '', transm($this->getModule(), 'form_field_chamberofcommerceno', 'Chamber of commerce registration number (encrypted, not searchable)')); 
                

            //first name
        $this->objEdtFirstNameInitials = new InputText();
        $this->objEdtFirstNameInitials->setNameAndID('edtFirstName');
        $this->objEdtFirstNameInitials->setClass('fullwidthtag');                         
        $this->objEdtFirstNameInitials->setMaxLength(50);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtFirstNameInitials->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtFirstNameInitials, $sFormSectionPersonal, transm($this->getModule(), 'form_field_firstnameinitials', 'First name (or initials)')); 

            //last name
        $this->objEdtLastName = new InputText();
        $this->objEdtLastName->setNameAndID('edtLastName');
        $this->objEdtLastName->setClass('fullwidthtag');                         
        $this->objEdtLastName->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtLastName->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtLastName, $sFormSectionPersonal, transm($this->getModule(), 'form_field_lastname', 'Last name (encrypted, not searchable)')); 

            //last name prefix
        $this->objEdtLastNamePrefix = new InputText();
        $this->objEdtLastNamePrefix->setNameAndID('edtLastNamePrefix');
        // $this->objEdtLastName->setClass('fullwidthtag');                         
        $this->objEdtLastNamePrefix->setMaxLength(20);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '20'), 20);
        $this->objEdtLastNamePrefix->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtLastNamePrefix, $sFormSectionPersonal, transm($this->getModule(), 'form_field_lastnameprefix', 'Last name prefix')); 
    

            //email
        $this->objEdtEmailAddress = new InputText();
        $this->objEdtEmailAddress->setNameAndID('edtEmailAddress');
        $this->objEdtEmailAddress->setClass('fullwidthtag');                         
        $this->objEdtEmailAddress->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtEmailAddress->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtEmailAddress, $sFormSectionPersonal, transm($this->getModule(), 'form_field_emailaddress', 'Email address (encrypted, not searchable)')); 
    

            //phone1
        $this->objEdtPhone1 = new InputText();
        $this->objEdtPhone1->setNameAndID('edtPhone1');
        $this->objEdtPhone1->setClass('fullwidthtag');                         
        $this->objEdtPhone1->setMaxLength(50);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtPhone1->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtPhone1, $sFormSectionPersonal, transm($this->getModule(), 'form_field_phonenumber1', 'Phone number (encrypted, not searchable)')); 
                
            //phone1
        $this->objEdtPhone2 = new InputText();
        $this->objEdtPhone2->setNameAndID('edtPhone2');
        $this->objEdtPhone2->setClass('fullwidthtag');                         
        $this->objEdtPhone2->setMaxLength(50);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtPhone2->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtPhone2, $sFormSectionPersonal, transm($this->getModule(), 'form_field_phonenumber2', 'Phone number 2 (encrypted, not searchable)')); 

    
            //billing: address line 2
        $this->objEdtBillingAddressLine2 = new InputText();
        $this->objEdtBillingAddressLine2->setNameAndID('edtBillingAddressLine2');
        $this->objEdtBillingAddressLine2->setClass('fullwidthtag');                         
        $this->objEdtBillingAddressLine2->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtBillingAddressLine2->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtBillingAddressLine2, $sFormSectionBilling, transm($this->getModule(), 'form_FIELD_BILLINGADDRESSSTREET', 'Street + house number (encrypted, not searchable)')); 

            //billing: address line 1
        $this->objEdtBillingAddressLine1 = new InputText();
        $this->objEdtBillingAddressLine1->setNameAndID('edtBillingAddressLine1');
        $this->objEdtBillingAddressLine1->setClass('fullwidthtag');                         
        $this->objEdtBillingAddressLine1->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtBillingAddressLine1->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtBillingAddressLine1, $sFormSectionBilling, transm($this->getModule(), 'form_FIELD_BILLINGADDRESSMISC', 'Appt. building/ company dept. etc (encrypted, not searchable)')); 

            
            //billing: postal code or zip
        $this->objEdtBillingPostalCodeZip = new InputText();
        $this->objEdtBillingPostalCodeZip->setNameAndID('edtBillingPostalCode');
        // $this->objEdtBillingPostalCodeZip->setClass('fullwidthtag');                         
        $this->objEdtBillingPostalCodeZip->setMaxLength(10);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
        $this->objEdtBillingPostalCodeZip->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtBillingPostalCodeZip, $sFormSectionBilling, transm($this->getModule(), 'form_field_billingpostalcodezip', 'Postal code/zip (encrypted, not searchable)')); 
    
            //billing: city
        $this->objEdtBillingCity = new InputText();
        $this->objEdtBillingCity->setNameAndID('edtBillingCity');
        $this->objEdtBillingCity->setClass('fullwidthtag');                         
        $this->objEdtBillingCity->setMaxLength(50);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtBillingCity->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtBillingCity, $sFormSectionBilling, transm($this->getModule(), 'form_field_billingcity', 'City')); 

            //billing: state/region
        $this->objEdtBillingStateRegion = new InputText();
        $this->objEdtBillingStateRegion->setNameAndID('edtBillingState');
        $this->objEdtBillingStateRegion->setClass('fullwidthtag');                         
        $this->objEdtBillingStateRegion->setMaxLength(50);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtBillingStateRegion->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtBillingStateRegion, $sFormSectionBilling, transm($this->getModule(), 'form_field_billingstateregion', 'State/region')); 
            

            //billing: country
        $this->objSelBillingCountryID = new Select();
        $this->objSelBillingCountryID->setNameAndID('optBillingCountryID');
        $this->getForm()->add($this->objSelBillingCountryID, $sFormSectionBilling, transm($this->getModule(), 'form_field_billingcountry', 'Country'));
        
            //billing: vat no
        $this->objEdtBillingVatNumber = new InputText();
        $this->objEdtBillingVatNumber->setNameAndID('edtBillingVATNumber');
        $this->objEdtBillingVatNumber->setClass('fullwidthtag');                         
        $this->objEdtBillingVatNumber->setMaxLength(20);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '20'), 20);
        $this->objEdtBillingVatNumber->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtBillingVatNumber, $sFormSectionBilling, transm($this->getModule(), 'form_field_billingvatno', 'VAT number (encrypted, not searchable)')); 

            //billing: bank account no
        $this->objEdtBillingBankAccountNo = new InputText();
        $this->objEdtBillingBankAccountNo->setNameAndID('edtBillingBankAccountNumber');
        $this->objEdtBillingBankAccountNo->setClass('fullwidthtag');                         
        $this->objEdtBillingBankAccountNo->setMaxLength(20);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '20'), 20);
        $this->objEdtBillingBankAccountNo->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtBillingBankAccountNo, $sFormSectionBilling, transm($this->getModule(), 'form_field_billingbankaccoutno', 'Bank account number (encrypted, not searchable)')); 
            
            //billing: email
        $this->objEdtBillingEmailAddress = new InputText();
        $this->objEdtBillingEmailAddress->setNameAndID('edtBillingEmailAddress');
        $this->objEdtBillingEmailAddress->setClass('fullwidthtag');                         
        $this->objEdtBillingEmailAddress->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtBillingEmailAddress->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtBillingEmailAddress, $sFormSectionBilling, transm($this->getModule(), 'form_field_billingemailaddress', 'Email address (encrypted, not searchable)')); 
                            
    
            //delivery: address line 2
        $this->objEdtDeliveryAddressLine2 = new InputText();
        $this->objEdtDeliveryAddressLine2->setNameAndID('edtDeliveryAddressLine2');
        $this->objEdtDeliveryAddressLine2->setClass('fullwidthtag');                         
        $this->objEdtDeliveryAddressLine2->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtDeliveryAddressLine2->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtDeliveryAddressLine2, $sFormSectionDelivery, transm($this->getModule(), 'form_FIELD_DELIVERYADDRESSSTREET', 'Street + house number (encrypted, not searchable)')); 
    

            //delivery: address line 1
        $this->objEdtDeliveryAddressLine1 = new InputText();
        $this->objEdtDeliveryAddressLine1->setNameAndID('edtDeliveryAddressLine1');
        $this->objEdtDeliveryAddressLine1->setClass('fullwidthtag');                         
        $this->objEdtDeliveryAddressLine1->setMaxLength(100);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '100'), 100);
        $this->objEdtDeliveryAddressLine1->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtDeliveryAddressLine1, $sFormSectionDelivery, transm($this->getModule(), 'form_FIELD_DELIVERYADDRESSMISC', 'Appt. building/ company dept. etc (encrypted, not searchable)')); 

            
            //delivery: postal code or zip
        $this->objEdtDeliveryPostalCodeZip = new InputText();
        $this->objEdtDeliveryPostalCodeZip->setNameAndID('edtDeliveryPostalCode');
        // $this->objEdtDeliveryPostalCodeZip->setClass('fullwidthtag');                         
        $this->objEdtDeliveryPostalCodeZip->setMaxLength(10);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
        $this->objEdtDeliveryPostalCodeZip->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtDeliveryPostalCodeZip, $sFormSectionDelivery, transm($this->getModule(), 'form_field_deliverypostalcodezip', 'Postal code/zip (encrypted, not searchable)')); 
    
            //delivery: city
        $this->objEdtDeliveryCity = new InputText();
        $this->objEdtDeliveryCity->setNameAndID('edtDeliveryCity');
        $this->objEdtDeliveryCity->setClass('fullwidthtag');                         
        $this->objEdtDeliveryCity->setMaxLength(50);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtDeliveryCity->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtDeliveryCity, $sFormSectionDelivery, transm($this->getModule(), 'form_field_deliverycity', 'City')); 

            //delivery: state/region
        $this->objEdtDeliveryStateRegion = new InputText();
        $this->objEdtDeliveryStateRegion->setNameAndID('edtDeliveryState');
        $this->objEdtDeliveryStateRegion->setClass('fullwidthtag');                         
        $this->objEdtDeliveryStateRegion->setMaxLength(50);    
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtDeliveryStateRegion->addValidator($objValidator);          
        $this->getForm()->add($this->objEdtDeliveryStateRegion, $sFormSectionDelivery, transm($this->getModule(), 'form_field_deliverystateregion', 'State/region')); 
            

            //delivery: country
        $this->objSelDeliveryCountryID = new Select();
        $this->objSelDeliveryCountryID->setNameAndID('optDeliveryCountryID');
        $this->getForm()->add($this->objSelDeliveryCountryID, $sFormSectionDelivery, transm($this->getModule(), 'form_field_deliverycountry', 'Country'));
               

           //notes
        $this->objTxtArNotes = new Textarea();
        $this->objTxtArNotes->setNameAndID('txtArNotes');
        $this->objTxtArNotes->setClass('fullwidthtag');                         
        $this->getForm()->add($this->objTxtArNotes, $sFormSectionMisc, transm($this->getModule(), 'form_field_notes', 'Notes (only seen by you)')); 
   

    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Sys_Contacts::PERM_CAT_CONTACTS;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
        $this->getModel()->set(TSysContacts::FIELD_ISCLIENT, $this->objChkIsClient->getContentsSubmitted()->getValueAsBool());                
        $this->getModel()->set(TSysContacts::FIELD_ISSUPPLIER, $this->objChkIsSupplier->getContentsSubmitted()->getValueAsBool());                
        $this->getModel()->set(TSysContacts::FIELD_CUSTOMIDENTIFIER, $this->objEdtCustomIdentifier->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysContacts::FIELD_COMPANYNAME, $this->objEdtCompanyName->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysContacts::FIELD_CHAMBEROFCOMMERCENO, $this->objEdtChamberCommerce->getContentsSubmitted()->getValueAsString(), '', true);

        $this->getModel()->set(TSysContacts::FIELD_FIRSTNAMEINITALS, $this->objEdtFirstNameInitials->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysContacts::FIELD_LASTNAME, $this->objEdtLastName->getContentsSubmitted()->getValueAsString(), '', true);
        $this->getModel()->set(TSysContacts::FIELD_LASTNAMEPREFIX, $this->objEdtLastNamePrefix->getContentsSubmitted()->getValueAsString());
        $this->getModel()->setEmailAddressDecrypted($this->objEdtEmailAddress->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysContacts::FIELD_ONMAILINGLIST, $this->objChkOnMailingList->getContentsSubmitted()->getValueAsBool());                
        $this->getModel()->set(TSysContacts::FIELD_ONBLACKLIST, $this->objChkOnBlackList->getContentsSubmitted()->getValueAsBool());                
        $this->getModel()->set(TSysContacts::FIELD_PHONENUMBER1, $this->objEdtPhone1->getContentsSubmitted()->getValueAsString(), '', true);
        $this->getModel()->set(TSysContacts::FIELD_PHONENUMBER2, $this->objEdtPhone2->getContentsSubmitted()->getValueAsString(), '', true);        
        $this->getModel()->set(TSysContacts::FIELD_NOTES, $this->objTxtArNotes->getContentsSubmitted()->getValueAsString());

        $this->getModel()->set(TSysContacts::FIELD_BILLINGADDRESSMISC, $this->objEdtBillingAddressLine1->getContentsSubmitted()->getValueAsString(), '', true);
        $this->getModel()->set(TSysContacts::FIELD_BILLINGADDRESSSTREET, $this->objEdtBillingAddressLine2->getContentsSubmitted()->getValueAsString(), '', true);
        $this->getModel()->set(TSysContacts::FIELD_BILLINGPOSTALCODEZIP, $this->objEdtBillingPostalCodeZip->getContentsSubmitted()->getValueAsString(), '', true);
        $this->getModel()->set(TSysContacts::FIELD_BILLINGCITY, $this->objEdtBillingCity->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysContacts::FIELD_BILLINGSTATEREGION, $this->objEdtBillingStateRegion->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysContacts::FIELD_BILLINGCOUNTRYID, $this->objSelBillingCountryID->getContentsSubmitted()->getValueAsInt());
        $this->getModel()->set(TSysContacts::FIELD_BILLINGVATNUMBER, $this->objEdtBillingVatNumber->getContentsSubmitted()->getValueAsString(), '', true);
        $this->getModel()->setBillingEmailAddressDecrypted($this->objEdtBillingEmailAddress->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysContacts::FIELD_BILLINGBANKACCOUNTNO, $this->objEdtBillingBankAccountNo->getContentsSubmitted()->getValueAsString(), '', true);

        $this->getModel()->set(TSysContacts::FIELD_DELIVERYADDRESSMISC, $this->objEdtDeliveryAddressLine1->getContentsSubmitted()->getValueAsString(), '', true);
        $this->getModel()->set(TSysContacts::FIELD_DELIVERYADDRESSSTREET, $this->objEdtDeliveryAddressLine2->getContentsSubmitted()->getValueAsString(), '', true);
        $this->getModel()->set(TSysContacts::FIELD_DELIVERYPOSTALCODEZIP, $this->objEdtDeliveryPostalCodeZip->getContentsSubmitted()->getValueAsString(), '', true);
        $this->getModel()->set(TSysContacts::FIELD_DELIVERYCITY, $this->objEdtDeliveryCity->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysContacts::FIELD_DELIVERYSTATEREGION, $this->objEdtDeliveryStateRegion->getContentsSubmitted()->getValueAsString());
        $this->getModel()->set(TSysContacts::FIELD_DELIVERYCOUNTRYID, $this->objSelDeliveryCountryID->getContentsSubmitted()->getValueAsInt());

        //generate custom identifier AFTER lastname and postal code are set
        if (strlen($this->objEdtCustomIdentifier->getContentsSubmitted()->getValueAsString())==0)
            $this->getModel()->setCustomIdentifierAuto();
    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {  
        $iDefaultCountryID = 0;
        $objCountries = new TSysCountries();
        $objCountries->sort(TSysCountries::FIELD_COUNTRYNAME);
        $objCountries->loadFromDB();

        //search for default country
        while($objCountries->next())
            if ($objCountries->getIsDefault() == true)
                $iDefaultCountryID = $objCountries->getID();


        $this->objChkIsClient->setChecked($this->getModel()->get(TSysContacts::FIELD_ISCLIENT));
        $this->objChkIsSupplier->setChecked($this->getModel()->get(TSysContacts::FIELD_ISSUPPLIER));
        $this->objEdtCustomIdentifier->setValue($this->getModel()->get(TSysContacts::FIELD_CUSTOMIDENTIFIER));
        $this->objEdtCompanyName->setValue($this->getModel()->get(TSysContacts::FIELD_COMPANYNAME));
        $this->objEdtChamberCommerce->setValue($this->getModel()->get(TSysContacts::FIELD_CHAMBEROFCOMMERCENO, '', true));

        $this->objEdtFirstNameInitials->setValue($this->getModel()->get(TSysContacts::FIELD_FIRSTNAMEINITALS));
        $this->objEdtLastName->setValue($this->getModel()->get(TSysContacts::FIELD_LASTNAME, '', true));
        $this->objEdtLastNamePrefix->setValue($this->getModel()->get(TSysContacts::FIELD_LASTNAMEPREFIX));
        $this->objEdtEmailAddress->setValue($this->getModel()->get(TSysContacts::FIELD_EMAILADDRESSENCRYPTED, '', true));        
        $this->objChkOnMailingList->setChecked($this->getModel()->get(TSysContacts::FIELD_ONMAILINGLIST));
        $this->objChkOnBlackList->setChecked($this->getModel()->get(TSysContacts::FIELD_ONBLACKLIST));
        $this->objEdtPhone1->setValue($this->getModel()->get(TSysContacts::FIELD_PHONENUMBER1, '', true));        
        $this->objEdtPhone2->setValue($this->getModel()->get(TSysContacts::FIELD_PHONENUMBER2, '', true));        
        $this->objTxtArNotes->setValue($this->getModel()->get(TSysContacts::FIELD_NOTES));

        //billing adress
        $this->objEdtBillingAddressLine1->setValue($this->getModel()->get(TSysContacts::FIELD_BILLINGADDRESSMISC, '', true));
        $this->objEdtBillingAddressLine2->setValue($this->getModel()->get(TSysContacts::FIELD_BILLINGADDRESSSTREET, '', true));
        $this->objEdtBillingPostalCodeZip->setValue($this->getModel()->get(TSysContacts::FIELD_BILLINGPOSTALCODEZIP, '', true));
        $this->objEdtBillingCity->setValue($this->getModel()->get(TSysContacts::FIELD_BILLINGCITY));
        $this->objEdtBillingStateRegion->setValue($this->getModel()->get(TSysContacts::FIELD_BILLINGSTATEREGION));
        if ($this->getModel()->getNew())//country default or existing id
            $objCountries->generateHTMLSelect($iDefaultCountryID, $this->objSelBillingCountryID);    
        else
            $objCountries->generateHTMLSelect($this->getModel()->get(TSysContacts::FIELD_BILLINGCOUNTRYID), $this->objSelBillingCountryID);    
        $this->objEdtBillingVatNumber->setValue($this->getModel()->get(TSysContacts::FIELD_BILLINGVATNUMBER, '', true));
        $this->objEdtBillingEmailAddress->setValue($this->getModel()->get(TSysContacts::FIELD_BILLINGEMAILADDRESSENCRYPTED, '', true));
        $this->objEdtBillingBankAccountNo->setValue($this->getModel()->get(TSysContacts::FIELD_BILLINGBANKACCOUNTNO, '', true));

        //delivery address
        $this->objEdtDeliveryAddressLine1->setValue($this->getModel()->get(TSysContacts::FIELD_DELIVERYADDRESSMISC, '', true));
        $this->objEdtDeliveryAddressLine2->setValue($this->getModel()->get(TSysContacts::FIELD_DELIVERYADDRESSSTREET, '', true));
        $this->objEdtDeliveryPostalCodeZip->setValue($this->getModel()->get(TSysContacts::FIELD_DELIVERYPOSTALCODEZIP, '', true));
        $this->objEdtDeliveryCity->setValue($this->getModel()->get(TSysContacts::FIELD_DELIVERYCITY));
        $this->objEdtDeliveryStateRegion->setValue($this->getModel()->get(TSysContacts::FIELD_DELIVERYSTATEREGION));
        if ($this->getModel()->getNew())//country default or existing id
            $objCountries->generateHTMLSelect($iDefaultCountryID, $this->objSelDeliveryCountryID);    
        else
            $objCountries->generateHTMLSelect($this->getModel()->get(TSysContacts::FIELD_DELIVERYCOUNTRYID), $this->objSelDeliveryCountryID);


        unset($objCountries);
    }



    
    /**
     * is called when a record is loaded
     */
    public function onLoad() 
    {        
    }
    
    /**
     * is called when a record is saved
     * this method has to send the proper error messages to the user!!
     * 
     * @return boolean it will NOT SAVE
     */
    public function onSavePre()
    {
        
        
        return true;
    }    

    /**
     * is called AFTER a record is saved
     * 
     * @param boolean $bWasSaveSuccesful did saveToDB() return false or true?
     * @return boolean returns true on success otherwise false
     */
    public function onSavePost($bWasSaveSuccesful){}
    
    
    /**
     * is called when this controller is created,
     * so you can instantiate classes or initiate values for example 
     */
    public function onCreate() 
    {
    }      

    /**
     * sometimes you don;t want to user the checkin checkout system, even though the model supports it
     * for example: the settings.
     * The user needs to be able to navigate through the tabsheets, without locking records
     * 
     * ATTENTION: if this method returns true and the model doesn't support it: the checkinout will NOT happen!
     * 
     * @return bool return true if you want to use the check-in/checkout-system
     */
    public function getUseCheckinout()
    {
        return true;
    }    



   /**
     * returns a new model object
     *
     * @return TModel
     */
    public function getNewModel()
    {
        return new TSysContacts(); 
    }

    /**
     * return path of the page template
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'tpl_modeldetailsave.php';
    }

    /**
     * return path of the skin template
     * 
     * return '' if no skin
     *
     * @return string
     */
    public function getSkinPath()
    {
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES.DIRECTORY_SEPARATOR.'skin_withmenu.php';
    }

    /**
     * returns the url to which the browser returns after closing the detailsave screen
     *
     * @return string
     */
    public function getReturnURL()
    {
        return 'list_contacts';
    }

    /**
     * return page title
     * This title is different for creating a new record and editing one.
     * It returns in the translated text in the current language of the user (it is not translated in the controller)
     * 
     * for example: "create a new user" or "edit user John" (based on if $objModel->getNew())
     *
     * @return string
     */
    public function getTitle()
    {
        global $sCurrentModule;

        if ($this->getModel()->getNew())   
            return transm($sCurrentModule, 'pagetitle_detailsave_contact_new', 'Create new contact');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_contact_edit', 'Edit contact: [contact]', 'contact', $this->getModel()->getGUIItemName());           
    }

    /**
     * show tabsheets on top of the page?
     *
     * @return bool
     */
    public function showTabs()
    {
        return false;
    }    
}
