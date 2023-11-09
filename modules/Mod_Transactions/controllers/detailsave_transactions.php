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
use dr\modules\Mod_Transactions\Mod_Transactions;
use dr\modules\Mod_Transactions\models\TTransactionsTypes;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;
use dr\modules\Mod_Transactions\models\TTransactions;

// include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_jquery.php');
include_once(GLOBAL_PATH_LOCAL_CMS . DIRECTORY_SEPARATOR . 'bootstrap_cms_auth.php');

/**
 * Description of TCRUDDetailSaveCMSUsers
 *
 * @author drenirie
 */
class detailsave_transactions extends TCRUDDetailSaveController
{
    private $objSelTransactionsType = null; //dr\classes\dom\tag\form\Select
    private $objSelCurrency = null; //dr\classes\dom\tag\form\Select     
    private $objEdtPurchaseOrderNo = null; //dr\classes\dom\tag\form\InputText
    private $objTxtNotesInternal = null; //dr\classes\dom\tag\form\Textarea
    private $objTxtNotesExternal = null; //dr\classes\dom\tag\form\Textarea

    private $objForm = null; ////dr\classes\dom\tag\form\Form --> NOT a form generator, because it has so many custom elements


    /**
     * define the fields that are in the detail screen
     * 
     */
    protected function populate()
    {

        //transactions-types
        $this->objSelTransactionsType = new Select();
        $this->objSelTransactionsType->setNameAndID('edtTransactionsType');
        // $this->objSelTransactionsType->setClass('fullwidthtag');
        // $this->getFormGenerator()->add($this->objSelTransactionsType, '', transm($this->getModule(), 'form_field_name', 'Name'));


        //currency
        $this->objSelCurrency = new InputCheckbox();
        $this->objSelCurrency->setNameAndID('edtCurrency');
        // $this->getFormGenerator()->add($this->objSelCurrency, '', transm($this->getModule(), 'form_field_isstock', 'Stock managing transaction (stock reduced or increased when transaction completed)'));


        //purchase order number
        $this->objEdtPurchaseOrderNo = new InputNumber();
        $this->objEdtPurchaseOrderNo->setNameAndID('edtPurchaseOrderNo');
        // $this->objEdtPurchaseOrderNo->setClass('fullwidthtag');   
        $this->objEdtPurchaseOrderNo->setMaxLength(50);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtPurchaseOrderNo->addValidator($objValidator);
        // $this->getFormGenerator()->add($this->objEdtPurchaseOrderNo, '', transm($this->getModule(), 'form_field_newincrementednumber', 'New transaction starts at number'));


        //internal notes
        $this->objTxtNotesInternal = new Textarea();
        $this->objTxtNotesInternal->setNameAndID('txtInternalNotes');
        // $this->objTxtNotesInternal->setClass('fullwidthtag');   
        $this->objTxtNotesInternal->addValidator($objValidator);
        // $this->getFormGenerator()->add($this->objTxtAddress, '', transm($this->getModule(), 'form_field_addressseller', 'Address seller'));

         //external notes
         $this->objTxtNotesExternal = new Textarea();
         $this->objTxtNotesExternal->setNameAndID('txtInternalNotes');
         // $this->objTxtNotesExternal->setClass('fullwidthtag');   
         $this->objTxtNotesExternal->addValidator($objValidator);
         // $this->getFormGenerator()->add($this->objTxtAddress, '', transm($this->getModule(), 'form_field_addressseller', 'Address seller'));
 
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
        //transaction type
        $this->getModel()->set(TTransactions::FIELD_TRANSACTIONSTYPEID, $this->objSelTransactionsType->getContentsSubmitted()->getValueAsString());

        //currency
        $this->getModel()->set(TTransactions::FIELD_CURRENCYID, $this->objSelCurrency->getContentsSubmitted()->getValueAsBool());

        //purchase order number
        $this->getModel()->set(TTransactions::FIELD_PURCHASEORDERNUMBER, $this->objEdtPurchaseOrderNo->getContentsSubmitted()->getValueAsBool());

        //internal notes
        $this->getModel()->set(TTransactions::FIELD_NOTESINTERNAL, $this->objTxtNotesInternal->getContentsSubmitted()->getValueAsBool());

        //external notes
        $this->getModel()->set(TTransactions::FIELD_NOTESEXTERNAL, $this->objTxtNotesExternal->getContentsSubmitted()->getValueAsBool());
    }

    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {
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

        //purchase order number
        $this->objEdtPurchaseOrderNo->setValue($this->getModel()->get(TTransactions::FIELD_PURCHASEORDERNUMBER));

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
