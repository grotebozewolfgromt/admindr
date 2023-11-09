<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace dr\modules\Mod_Transactions\controllers;

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
use dr\classes\models\TSysCMSUserAccounts;
use dr\classes\types\TDateTime;


//don't forget ;)
use dr\classes\models\TSysCMSUsers;
use  dr\classes\models\TSysCMSUsersAccounts;
use dr\classes\models\TSysContacts;
use dr\classes\models\TUsersAbstract;
use dr\modules\Mod_Transactions\Mod_Transactions;
use dr\modules\Mod_Transactions\models\TTransactionsTypes;
use dr\modules\Mod_Sys_CMSUsers\Mod_Sys_CMSUsers;

// include_once(GLOBAL_PATH_LOCAL_LIBRARIES.DIRECTORY_SEPARATOR.'lib_jquery.php');
include_once(GLOBAL_PATH_LOCAL_CMS.DIRECTORY_SEPARATOR.'bootstrap_cms_auth.php');

/**
 * Description of TCRUDDetailSaveCMSUsers
 *
 * @author drenirie
 */
class detailsave_transactionstypes extends TCRUDDetailSaveController
{
    private $objEdtName = null;//dr\classes\dom\tag\form\InputText
    private $objChkStock = null;//dr\classes\dom\tag\form\InputCheckbox     
    private $objChkFinancial = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objChkDefaultSelected = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objChkDefaultInvoice = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objChkDefaultOrder = null;//dr\classes\dom\tag\form\InputCheckbox
    private $objEdtColorForeground = null;//dr\classes\dom\tag\form\InputColor
    private $objEdtColorBackground = null;//dr\classes\dom\tag\form\InputColor
    private $objEdtNewNumber = null;//dr\classes\dom\tag\form\InputNumber
    private $objTxtAddress = null;//dr\classes\dom\tag\form\Textarea
    private $objEdtVatNo = null;//dr\classes\dom\tag\form\InputText
    private $objEdtPaymentDays = null;//dr\classes\dom\tag\form\InputNumber
        
    /**
     * define the fields that are in the detail screen
     * 
     */ 
    protected function populate() 
    {
                 
            //custom identifier
        $this->objEdtName = new InputText();
        $this->objEdtName->setNameAndID('edtName');
        $this->objEdtName->setClass('fullwidthtag');   
        $this->objEdtName->setRequired(true);   
        $this->objEdtName->setMaxLength(50);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtName->addValidator($objValidator);    
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtName->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtName, '', transm($this->getModule(), 'form_field_name', 'Name'));

 
        //stock managed
        $this->objChkStock = new InputCheckbox();
        $this->objChkStock->setNameAndID('chkIsStock');
        $this->getFormGenerator()->add($this->objChkStock, '', transm($this->getModule(), 'form_field_isstock', 'Stock managing transaction (stock reduced or increased when transaction completed)'));         


        //financial transaction
        $this->objChkFinancial = new InputCheckbox();
        $this->objChkFinancial->setNameAndID('chkIsFinancial');
        $this->getFormGenerator()->add($this->objChkFinancial, '', transm($this->getModule(), 'form_field_isfinancial', 'Is financial transaction'));         


        //default selected
        $this->objChkDefaultSelected = new InputCheckbox();
        $this->objChkDefaultSelected->setNameAndID('chkIsDefaultSelected');
        $this->getFormGenerator()->add($this->objChkDefaultSelected, '', transm($this->getModule(), 'form_field_isdefaultselected', 'Is selected by default (in user interface elements)'));         


        //default invoice
        $this->objChkDefaultInvoice = new InputCheckbox();
        $this->objChkDefaultInvoice->setNameAndID('chkIsDefaultInvoice');
        $this->getFormGenerator()->add($this->objChkDefaultInvoice, '', transm($this->getModule(), 'form_field_isdefaultinvoice', 'Is default invoice (when an invoice (in webshop) is needed, this type is selected)'));         


        //default order
        $this->objChkDefaultOrder = new InputCheckbox();
        $this->objChkDefaultOrder->setNameAndID('chkIsDefaultOrder');
        $this->getFormGenerator()->add($this->objChkDefaultOrder, '', transm($this->getModule(), 'form_field_isdefaultorder', 'Is default order (when an order (in webshop) is needed, this type is selected)'));         
        
        
            //foreground color
        $this->objEdtColorForeground = new InputColor();
        $this->objEdtColorForeground->setNameAndID('edtColorForeground');
        // $this->objEdtColorForeground->setClass('fullwidthtag');   
        $this->objEdtColorForeground->setRequired(false);   
        $this->objEdtColorForeground->setMaxLength(7);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '7'), 7);
        $this->objEdtColorForeground->addValidator($objValidator);    
        $objValidator = new ColorHex(transcms('form_error_colorinvalid', 'Color needs to in Hex values (from #00000 to #FFFFFF), [value] given.', 'value', $this->objEdtColorForeground->getContentsSubmitted()->getValueAsString()), true, true);
        $this->objEdtColorForeground->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtColorForeground, '', transm($this->getModule(), 'form_field_colorforeground', 'Foreground color (hexadecimal value from #00000 to #FFFFFF) (including #-sign)'));
    
            //background color
        $this->objEdtColorBackground = new InputColor();
        $this->objEdtColorBackground->setNameAndID('edtColorBackground');
        // $this->objEdtColorBackground->setClass('fullwidthtag');   
        $this->objEdtColorBackground->setRequired(false);   
        $this->objEdtColorBackground->setMaxLength(7);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '7'), 7);
        $this->objEdtColorBackground->addValidator($objValidator);    
        $objValidator = new ColorHex(transcms('form_error_colorinvalid', 'Color needs to in Hex values (from #00000 to #FFFFFF), [value] given.', 'value', $this->objEdtColorBackground->getContentsSubmitted()->getValueAsString()), true, true);
        $this->objEdtColorBackground->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtColorBackground, '', transm($this->getModule(), 'form_field_colorbackground', 'Background color (hexadecimal value from #00000 to #FFFFFF) (including #-sign)'));
            

            //new incremented number
        $this->objEdtNewNumber = new InputNumber();
        $this->objEdtNewNumber->setNameAndID('edtNewIncrementNumber');
        // $this->objEdtLastNumber->setClass('fullwidthtag');   
        $this->objEdtNewNumber->setRequired(true);   
        $this->objEdtNewNumber->setMaxLength(10);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '10'), 10);
        $this->objEdtNewNumber->addValidator($objValidator);    
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtNewNumber->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtNewNumber, '', transm($this->getModule(), 'form_field_newincrementednumber', 'New transaction starts at number'));


            //address seller
        $this->objTxtAddress = new Textarea();
        $this->objTxtAddress->setNameAndID('txtAddressSeller');
        // $this->objTxtAddress->setClass('fullwidthtag');   
        $this->objTxtAddress->setRequired(false);   
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '1000'), 1000);
        $this->objTxtAddress->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objTxtAddress, '', transm($this->getModule(), 'form_field_addressseller', 'Address seller'));

            //vat no
        $this->objEdtVatNo = new InputText();
        $this->objEdtVatNo->setNameAndID('edtVATNo');
        // $this->objEdtVatNo->setClass('fullwidthtag');   
        // $this->objEdtVatNo->setRequired(true);   
        $this->objEdtVatNo->setMaxLength(50);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '50'), 50);
        $this->objEdtVatNo->addValidator($objValidator);    
        // $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        // $this->objEdtVatNo->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtVatNo, '', transm($this->getModule(), 'form_field_vatno', 'VAT number seller'));

            //payment within days
        $this->objEdtPaymentDays = new InputNumber();
        $this->objEdtPaymentDays->setNameAndID('edtPaymentDays');
        // $this->objEdtPaymentDays->setClass('fullwidthtag');   
        // $this->objEdtPaymentDays->setRequired(true);   
        $this->objEdtPaymentDays->setMaxLength(4);
        $objValidator = new Maximumlength(transcms('form_error_maxlengthexceeded', 'The maximumlength [length] of this field is exceeded', 'length', '4'), 4);
        $this->objEdtPaymentDays->addValidator($objValidator);    
        $objValidator = new Required(transcms('form_error_requiredfield', 'This is a required field'));
        $this->objEdtPaymentDays->addValidator($objValidator);    
        $this->getFormGenerator()->add($this->objEdtPaymentDays, '', transm($this->getModule(), 'form_field_paymentwithindays', 'Must be payed within (amount of days)'));

    }

    /**
     * what is the category that the auth() function uses?
     */
    protected function getAuthorisationCategory() 
    {
        return Mod_Transactions::PERM_CAT_TRANSACTIONSTYPES;
    }
    
    /**
     * transfer form elements to database
     */
    protected function formToModel()
    {
        //name
        $this->getModel()->set(TTransactionsTypes::FIELD_NAME, $this->objEdtName->getContentsSubmitted()->getValueAsString());

        //stock
        $this->getModel()->set(TTransactionsTypes::FIELD_ISSTOCK, $this->objChkStock->getContentsSubmitted()->getValueAsBool());        

        //financial
        $this->getModel()->set(TTransactionsTypes::FIELD_ISFINANCIAL, $this->objChkFinancial->getContentsSubmitted()->getValueAsBool());        

        //default selected
        $this->getModel()->set(TTransactionsTypes::FIELD_ISDEFAULTSELECTED, $this->objChkDefaultSelected->getContentsSubmitted()->getValueAsBool());        

        //default invoice type
        $this->getModel()->set(TTransactionsTypes::FIELD_ISDEFAULTINVOICE, $this->objChkDefaultInvoice->getContentsSubmitted()->getValueAsBool());        

        //default order type
        $this->getModel()->set(TTransactionsTypes::FIELD_ISDEFAULTORDER, $this->objChkDefaultOrder->getContentsSubmitted()->getValueAsBool());        

        //foreground color
        $this->getModel()->set(TTransactionsTypes::FIELD_COLORFOREGROUND, $this->objEdtColorForeground->getContentsSubmitted()->getValueAsString());

        //background color
        $this->getModel()->set(TTransactionsTypes::FIELD_COLORBACKGROUND, $this->objEdtColorBackground->getContentsSubmitted()->getValueAsString());

        //new number increment
        $this->getModel()->set(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT, $this->objEdtNewNumber->getContentsSubmitted()->getValueAsString());

        //address seller
        $this->getModel()->set(TTransactionsTypes::FIELD_ADDRESSSELLER, $this->objTxtAddress->getContentsSubmitted()->getValueAsString(), '', true);

        //vat no seller
        $this->getModel()->set(TTransactionsTypes::FIELD_VATNOSELLER, $this->objEdtVatNo->getContentsSubmitted()->getValueAsString(), '', true);

        //payment within days
        $this->getModel()->set(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS, $this->objEdtPaymentDays->getContentsSubmitted()->getValueAsString());

    }
    
    /**
     * transfer database elements to form
     */
    protected function modelToForm()
    {  
        //name
        $this->objEdtName->setValue($this->getModel()->get(TTransactionsTypes::FIELD_NAME));

        //stock
        $this->objChkStock->setChecked($this->getModel()->get(TTransactionsTypes::FIELD_ISSTOCK));   

        //financial
        $this->objChkFinancial->setChecked($this->getModel()->get(TTransactionsTypes::FIELD_ISFINANCIAL));   

        //default selected
        $this->objChkDefaultSelected->setChecked($this->getModel()->get(TTransactionsTypes::FIELD_ISDEFAULTSELECTED));   

        //default invoice
        $this->objChkDefaultInvoice->setChecked($this->getModel()->get(TTransactionsTypes::FIELD_ISDEFAULTINVOICE));   

        //default order
        $this->objChkDefaultOrder->setChecked($this->getModel()->get(TTransactionsTypes::FIELD_ISDEFAULTORDER));   

        //foreground color
        $this->objEdtColorForeground->setValue($this->getModel()->get(TTransactionsTypes::FIELD_COLORFOREGROUND));

        //background color
        $this->objEdtColorBackground->setValue($this->getModel()->get(TTransactionsTypes::FIELD_COLORBACKGROUND));

        //new number increment
        $this->objEdtNewNumber->setValue($this->getModel()->get(TTransactionsTypes::FIELD_NEWNUMBERINCREMENT));

        //address
        $this->objTxtAddress->setValue($this->getModel()->get(TTransactionsTypes::FIELD_ADDRESSSELLER, '', true));

        //vat no
        $this->objEdtVatNo->setValue($this->getModel()->get(TTransactionsTypes::FIELD_VATNOSELLER, '', true));

        //payment within days
        $this->objEdtPaymentDays->setValue($this->getModel()->get(TTransactionsTypes::FIELD_PAYMENTMADEWITHINDAYS));

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
        return false;
    }    



   /**
     * returns a new model object
     *
     * @return TModel
     */
    public function getNewModel()
    {
        return new TTransactionsTypes(); 
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
        return 'list_transactionstypes';
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
            return transm($sCurrentModule, 'pagetitle_detailsave_transactionstypes_new', 'Create transaction type');
        else
            return transm($sCurrentModule, 'pagetitle_detailsave_transactionstypes_edit', 'Edit transaction type: [name]', 'name', $this->getModel()->getName());   
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
