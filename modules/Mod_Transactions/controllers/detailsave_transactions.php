<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Transactions\controllers;

use dr\classes\controllers\TControllerAbstract;
use dr\classes\models\TModel;
use dr\classes\controllers\TCRUDDetailSaveController;
use dr\classes\controllers\TCRUDDetailSaveController_org;
use dr\classes\locale\TCountrySettings;

use dr\classes\dom\tag\form\Form;
use dr\classes\dom\tag\form\Select;
use dr\classes\dom\tag\form\InputText;
use dr\classes\dom\tag\form\InputPassword;
use dr\classes\dom\tag\form\InputCheckbox;
use dr\classes\dom\tag\form\InputColor;
use dr\classes\dom\tag\form\Textarea;
use dr\classes\dom\tag\form\InputDate;
use dr\classes\dom\tag\form\InputTime;
use dr\classes\dom\tag\form\Label;
use dr\classes\dom\tag\form\InputDatetime;
use dr\classes\dom\tag\form\InputNumber;
use dr\classes\dom\tag\Li;
use dr\classes\dom\tag\Text;
use dr\classes\dom\tag\Script;
use dr\classes\dom\tag\form\Option;
use dr\classes\dom\validator\ColorHex;
use dr\classes\dom\validator\Maximumlength;
use dr\classes\dom\validator\Required;
use dr\classes\dom\validator\Emailaddress;
use dr\classes\dom\validator\Date;
use dr\classes\dom\validator\DateMin;
use dr\classes\dom\validator\DateMax;
use dr\classes\dom\validator\DateTime;
use dr\classes\dom\validator\Time;
use dr\classes\models\TContactsAbstract;
use dr\classes\models\TSysCMSUserAccounts;
use dr\classes\types\TDateTime;


//don't forget ;)
use dr\classes\models\TSysCMSUsers;
use  dr\classes\models\TSysCMSUsersAccounts;
use dr\classes\models\TSysContacts;
use dr\classes\models\TSysCurrencies;
use dr\classes\models\TUsersAbstract;
use dr\classes\types\TCurrency;
use dr\classes\types\TDecimal;
use dr\modules\Mod_Transactions\Mod_Transactions;
use dr\modules\Mod_Transactions\models\TTransactionsTypes;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;
use dr\modules\Mod_Transactions\models\TTransactions;
use dr\modules\Mod_Transactions\models\TTransactionsLines;

// include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_jquery.php');
include_once(GLOBAL_PATH_LOCAL_CMS . DIRECTORY_SEPARATOR . 'bootstrap_cms_auth.php');




/**
 * Description of TCRUDDetailSaveCMSUsers
 *
 * @author drenirie
 */
class detailsave_transactions extends TCRUDDetailSaveController
{
    //fields
    public $objSelTransactionsType = null; //dr\classes\dom\tag\form\Select
    public $objSelCurrency = null; //dr\classes\dom\tag\form\Select     
    public $objHidBuyer = null; //dr\classes\dom\tag\form\Select     
    public $objEdtPurchaseOrderNo = null; //dr\classes\dom\tag\form\InputText
    public $objTxtNotesInternal = null; //dr\classes\dom\tag\form\Textarea
    public $objTxtNotesExternal = null; //dr\classes\dom\tag\form\Textarea

    //lines
    public $objEdtQuantity = null; //dr\classes\dom\tag\form\InputText
    public $objEdtDescription = null; //dr\classes\dom\tag\form\InputText
    public $objEdtVATPercentage = null; //dr\classes\dom\tag\form\InputText
    public $objEdtPurchasePriceExclVAT = null; //dr\classes\dom\tag\form\InputText
    public $objEdtDiscountPriceExclVAT = null; //dr\classes\dom\tag\form\InputText
    public $objEdtPriceExclVAT = null; //dr\classes\dom\tag\form\InputText


    // private $objForm = null; ////dr\classes\dom\tag\form\Form --> NOT a form generator, because it has so many custom elements
    public $objHidFormSubmitted = null; //dr\classes\dom\tag\form\InputHidden this field is used to detect of form is submitted. dom element is filled with number when the form is submitted.
    public $objHidCSRFToken = null; //dr\classes\dom\tag\form\InputHidden this field is used to detect Cross Site Request Forgery


    private $objTransactionLines = null; //TTransactionsLines

    /**
     * 
     */
    public function __construct()
    {
        $this->objTransactionLines = new TTransactionsLines();

        parent::__construct();
    }

    /**
     * define the fields that are in the detail screen
     * 
     */
    protected function populate()
    {
        //obligatory fields for forms
        $this->objHidFormSubmitted = $this->getFormGenerator()->getFormSubmittedDOMElement(); //use the hidden field from form generator to detect if form is submitted
        $this->objHidCSRFToken = $this->getFormGenerator()->getCSRFTokenDOMElement(); //use the hidden field from form generator to detect if form is submitted

        //transactions-types
        $this->objSelTransactionsType = new Select();
        $this->objSelTransactionsType->setNameAndID('edtTransactionsTypeID');
        // $this->objSelTransactionsType->setClass('fullwidthtag');
        // $this->getFormGenerator()->add($this->objSelTransactionsType, '', transm($this->getModule(), 'form_field_name', 'Name'));


        //currency
        $this->objSelCurrency = new Select();
        $this->objSelCurrency->setNameAndID('selCurrencyID');
        // $this->getFormGenerator()->add($this->objSelCurrency, '', transm($this->getModule(), 'form_field_isstock', 'Stock managing transaction (stock reduced or increased when transaction completed)'));


        //buyer
        $this->objHidBuyer = new Select();
        $this->objHidBuyer->setNameAndID('hdBuyerID');
        // $this->getFormGenerator()->add($this->objSelCurrency, '', transm($this->getModule(), 'form_field_isstock', 'Stock managing transaction (stock reduced or increased when transaction completed)'));



        //purchase order number
        $this->objEdtPurchaseOrderNo = new InputText();
        $this->objEdtPurchaseOrderNo->setNameAndID('edtPurchaseOrderNo');
        // $this->objEdtPurchaseOrderNo->setClass('fullwidthtag');   
        $this->objEdtPurchaseOrderNo->setMaxLength(50);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtPurchaseOrderNo->addValidator($objValidator);
        // $this->getFormGenerator()->add($this->objEdtPurchaseOrderNo, '', transm($this->getModule(), 'form_field_newincrementednumber', 'New transaction starts at number'));


        //==== LINES

            //quantity
            $this->objEdtQuantity = new InputText(true);
            $this->objEdtQuantity->setName('edtQuantity');
            // $this->objEdtQuantity->setClass('fullwidthtag');   
            $this->objEdtQuantity->setMaxLength(10);
            $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
            $this->objEdtQuantity->addValidator($objValidator);
            // $this->getFormGenerator()->add($this->objEdtPurchaseOrderNo, '', transm($this->getModule(), 'form_field_newincrementednumber', 'New transaction starts at number'));


            //description
            $this->objEdtDescription = new InputText(true);
            $this->objEdtDescription->setName('edtDescription');
            // $this->objEdtDescription->setClass('fullwidthtag');   
            $this->objEdtDescription->setMaxLength(50);
            $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
            $this->objEdtDescription->addValidator($objValidator);
            // $this->getFormGenerator()->add($this->objEdtPurchaseOrderNo, '', transm($this->getModule(), 'form_field_newincrementednumber', 'New transaction starts at number'));


            //vat percentage
            $this->objEdtVATPercentage = new InputText(true);
            $this->objEdtVATPercentage->setName('edtVATPercentage');
            // $this->objEdtVATPercentage->setClass('fullwidthtag');   
            $this->objEdtVATPercentage->setMaxLength(50);
            $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
            $this->objEdtVATPercentage->addValidator($objValidator);
            // $this->getFormGenerator()->add($this->objEdtPurchaseOrderNo, '', transm($this->getModule(), 'form_field_newincrementednumber', 'New transaction starts at number'));
        

            //purchase price
            $this->objEdtPurchasePriceExclVAT = new InputText(true);
            $this->objEdtPurchasePriceExclVAT->setName('edtPurchasePrice');
            // $this->objEdtPurchasePriceExclVAT->setClass('fullwidthtag');   
            $this->objEdtPurchasePriceExclVAT->setMaxLength(50);
            $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
            $this->objEdtPurchasePriceExclVAT->addValidator($objValidator);
            // $this->getFormGenerator()->add($this->objEdtPurchaseOrderNo, '', transm($this->getModule(), 'form_field_newincrementednumber', 'New transaction starts at number'));


            //discount price
            $this->objEdtDiscountPriceExclVAT = new InputText(true);
            $this->objEdtDiscountPriceExclVAT->setName('edtDiscountPrice');
            // $this->objEdtDiscountPriceExclVAT->setClass('fullwidthtag');   
            $this->objEdtDiscountPriceExclVAT->setMaxLength(50);
            $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
            $this->objEdtDiscountPriceExclVAT->addValidator($objValidator);
            // $this->getFormGenerator()->add($this->objEdtPurchaseOrderNo, '', transm($this->getModule(), 'form_field_newincrementednumber', 'New transaction starts at number'));
            

            //unit price
            $this->objEdtPriceExclVAT = new InputText(true);
            $this->objEdtPriceExclVAT->setName('edtUnitPrice');
            // $this->objEdtPriceExclVAT->setClass('fullwidthtag');   
            $this->objEdtPriceExclVAT->setMaxLength(50);
            $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
            $this->objEdtPriceExclVAT->addValidator($objValidator);
            // $this->getFormGenerator()->add($this->objEdtPurchaseOrderNo, '', transm($this->getModule(), 'form_field_newincrementednumber', 'New transaction starts at number'));
                 


        //internal notes
        $this->objTxtNotesInternal = new Textarea();
        $this->objTxtNotesInternal->setNameAndID('txtInternalNotes');
        $this->objTxtNotesInternal->setClass('fullwidthtag');   
        $this->objTxtNotesInternal->addValidator($objValidator);
        // $this->getFormGenerator()->add($this->objTxtAddress, '', transm($this->getModule(), 'form_field_addressseller', 'Address seller'));


         //external notes
         $this->objTxtNotesExternal = new Textarea();
         $this->objTxtNotesExternal->setNameAndID('txtExternalNotes');
         $this->objTxtNotesExternal->setClass('fullwidthtag');   
         $this->objTxtNotesExternal->addValidator($objValidator);
        //  $this->getFormGenerator()->add($this->objTxtAddress, '', transm($this->getModule(), 'form_field_addressseller', 'Address seller'));
 
    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory()
    {
        return Mod_Transactions::PERM_CAT_TRANSACTIONS;
    }

    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
        global $objLoginController;


        //===== HEADER ====

        //transaction type
        $this->getModel()->set(TTransactions::FIELD_TRANSACTIONSTYPEID, $this->objSelTransactionsType->getContentsSubmitted()->getValueAsInt());

        //currency
        $this->getModel()->set(TTransactions::FIELD_CURRENCYID, $this->objSelCurrency->getContentsSubmitted()->getValueAsInt());

        //buyer
        $this->getModel()->set(TTransactions::FIELD_BUYERCONTACTID, $this->objHidBuyer->getContentsSubmitted()->getValueAsInt());

        //purchase order number
        $this->getModel()->set(TTransactions::FIELD_PURCHASEORDERNUMBER, $this->objEdtPurchaseOrderNo->getContentsSubmitted()->getValue());


        //==== LINES ====
        $iTotalLines = count($this->objEdtDescription->getValueSubmitted()); //get length of array of one of the arrays, doesn't matter which one
        if ($iTotalLines > 0)
        {
            $this->objTransactionLines->resetRecordPointer();
            for ($iLC = 0; $iLC < $iTotalLines; $iLC++)
            {
                $this->objTransactionLines->newRecord();

                $this->objTransactionLines->set(TTransactionsLines::FIELD_QUANTITY, new TDecimal($this->objEdtQuantity->getValueSubmitted()[$iLC], 4));
                $this->objTransactionLines->set(TTransactionsLines::FIELD_DESCRIPTION, $this->objEdtDescription->getValueSubmitted()[$iLC]);
                $this->objTransactionLines->set(TTransactionsLines::FIELD_VATPERCENTAGE, new TCurrency($this->objEdtVATPercentage->getValueSubmitted()[$iLC]));
                $this->objTransactionLines->set(TTransactionsLines::FIELD_UNITPURCHASEPRICEEXCLVAT, new TCurrency($this->objEdtPurchasePriceExclVAT->getValueSubmitted()[$iLC]));
                $this->objTransactionLines->set(TTransactionsLines::FIELD_UNITDISCOUNTEXCLVAT, new TCurrency($this->objEdtDiscountPriceExclVAT->getValueSubmitted()[$iLC]));
                $this->objTransactionLines->set(TTransactionsLines::FIELD_UNITPRICEEXCLVAT, new TCurrency($this->objEdtPriceExclVAT->getValueSubmitted()[$iLC]));
            }
        }      
        //save is done in onSavePost(), 
        //because there we have the transactionid to store in transaction lines
        //BUT we need to read the lines here from the fields, because we need to calculate the meta fields to save them in TTransaction


        //==== NOTES ===

        //internal notes
        $this->getModel()->set(TTransactions::FIELD_NOTESINTERNAL, $this->objTxtNotesInternal->getContentsSubmitted()->getValue());

        //external notes
        $this->getModel()->set(TTransactions::FIELD_NOTESEXTERNAL, $this->objTxtNotesExternal->getContentsSubmitted()->getValue());


        //==== HISTORY ===




        //==== AUTO GENERATED ====

        //user who created the transaction
        $this->getModel()->set(TTransactions::FIELD_CREATEDBYCONTACTID, $objLoginController->getUsers()->getID());

        //date
        $this->getModel()->set(TTransactions::FIELD_DATEFINALIZED, new TDateTime());

        //meta fields
        $this->getModel()->set(TTransactions::FIELD_META_TOTALPRICEINCLVAT, $this->objTransactionLines->calculateTotalPriceInclVat());
        $this->getModel()->set(TTransactions::FIELD_META_TOTALPRICEEXCLVAT, $this->objTransactionLines->calculateTotalPriceExclVat());
        $this->getModel()->set(TTransactions::FIELD_META_TOTALPURCHASEPRICEEXCLVAT, $this->objTransactionLines->calculateTotalPurchasePriceExclVat());
        $this->getModel()->set(TTransactions::FIELD_META_TOTALVAT, $this->objTransactionLines->calculateTotalVat());
        // $this->getModel()->set(TTransactions::FIELD_META_AMOUNTDUE, 0);--> @todo from transaction payments

    }

    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {
       
        //==== HEADER ====

        //transactions-types
        $objTypes = new TTransactionsTypes();
        $objTypes->sort(TTransactionsTypes::FIELD_ORDER);
        $objTypes->limit(1000);
        $objTypes->loadFromDB();
        $objTypes->generateHTMLSelect($this->getModel()->get(TTransactions::FIELD_TRANSACTIONSTYPEID), $this->objSelTransactionsType);
                      
        //currency
        $objCurr = new TSysCurrencies();
        $objCurr->sort(TSysCurrencies::FIELD_ORDER);
        $objCurr->where(TSysCurrencies::FIELD_ISVISIBLE, true);
        $objCurr->loadFromDB();
        $objCurr->generateHTMLSelect($this->getModel()->get(TTransactions::FIELD_CURRENCYID), $this->objSelCurrency);

        //buyer contact id
        $objContacts = new TSysContacts();
        $objContacts->sort(TSysContacts::FIELD_CUSTOMIDENTIFIER);
        $objContacts->where(TSysContacts::FIELD_ISCLIENT, true); 
        $objContacts->limitNone(); //not very fun, but hope to have a better solution in the future
        $objContacts->loadFromDB();
        $objContacts->generateHTMLSelect($this->getModel()->get(TTransactions::FIELD_BUYERCONTACTID), $this->objHidBuyer);

        //purchase order number
        $this->objEdtPurchaseOrderNo->setValue($this->getModel()->get(TTransactions::FIELD_PURCHASEORDERNUMBER));


        //==== TRANSACTIONS LINES ====
        if ($this->getModel()->getNewAll())
        {
            $this->objTransactionLines->sort(TTransactionsLines::FIELD_ORDER);
            $this->objTransactionLines->limit(1000);
            $this->objTransactionLines->loadFromDB();
        }
        //the actual field values are set in the template


        //===== NOTES ====

        //internal notes
        $this->objTxtNotesInternal->setValue($this->getModel()->get(TTransactions::FIELD_NOTESINTERNAL));

        //external notes
        $this->objTxtNotesExternal->setValue($this->getModel()->get(TTransactions::FIELD_NOTESEXTERNAL));
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
    public function onSavePost($bWasSaveSuccesful)
    {
        //we need the transaction id first. 
        //We only get id on a new transaction after the transaction is created
             
        //delete old lines
        //@todo

        //go through all transaction lines and update the transaction id, so we can save the lines
        $this->objTransactionLines->resetRecordPointer();
        while ($this->objTransactionLines->next())
        {
            $this->objTransactionLines->set(TTransactionsLines::FIELD_TRANSACTIONSID, $this->getModel()->getID());
        }

        if (!$this->objTransactionLines->saveToDBAll())
            return false;

        //history
        //@todo

        return true;
    }


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
        return false;
    }



    /**
     * returns a new model object
     *
     * @return TModel
     */
    public function getNewModel()
    {
        return new TTransactions();
    }

    /**
     * return path of the page template
     *
     * @return string
     */
    public function getTemplatePath()
    {
        return GLOBAL_PATH_LOCAL_MODULES.DIRECTORY_SEPARATOR.$this->getModule().DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'tpl_detailsave_transactions.php';
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
        return GLOBAL_PATH_LOCAL_CMS_TEMPLATES . DIRECTORY_SEPARATOR . 'skin_withmenu.php';
    }

    /**
     * returns the url to which the browser returns after closing the detailsave screen
     *
     * @return string
     */
    public function getReturnURL()
    {
        return 'list_transactions';
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
            return transm($sCurrentModule, 'pagetitle_detailsave_transactions_new', 'Create new transaction');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_transactions_edit', 'Edit transaction');
            // return transm($sCurrentModule, 'pagetitle_detailsave_transactions_edit', 'Edit transaction: [name]', 'name', $this->getModel()->getName());
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
