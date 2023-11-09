<?php

namespace dr\modules\Mod_Transactions\models;

use dr\classes\models\TModel;
use dr\classes\models\TSysContacts;
use dr\classes\types\TCurrency;
use dr\classes\types\TDateTime;


/**
 * Invoices
 * 
 * Part of TTransactions are TTransactionsLines (representing the actual products in a transaction).
 * 
 * ORDERS AND OFFERS:
 * The invoices in this class can also contain Orders or Offers which don't have 
 * a monetary significance for revenue/tax purposes.
 * 
 * FINALIZED-state:
 * It is possible to create invoices/order/offer that can be edited.
 * An invoice/order/offer that is finalized can NOT be edited.
 * Only a finalized invoices have monetary significance for revenue/tax purposes. 
 * An invoice can go from 'non-finalized' to 'finalized' but not the other way around.
 * When an invoice changes from 'non-finalized' to 'finalized' an invoice-number & invoice-date are assigned.
 * The reason: 
 * having the flexibility to change invoices/orders/offers to correct mistakes, but lock invoices to avoid tempering
 * 
 * META FIELDS:
 * We store meta fields that are auto populated on invoice save. 
 * This helps to speed up calculating large amounts of data like of revenue, vat etc
 * (because we don't have to load all the invoice line)
 * 
 * INCREMENT NUMBER/INVOICE NUMBER:
 * This increment number is a string.
 * This can represent an invoice number.
 * This way you can include extra codes in the invoice number.
 * For example an invoice number like this:		werGHKEweo2-jpswew3d42aas-2024-150694
 * In this case this number format could be:  	[accountno]-[websitecode]-[year]-[invoice-increment]
 * 
 * @todo status
 * @todo history
 * 
 * created 21 october 2023
 * 21 oct 2023: TInvoices: created
 * 26 oct 2023: TInvoices: FIELD_PSPNAME added
 * 3 nov 2023: TInvoices -> TTransactions
 * 
 * @author Dennis Renirie
 */

class TTransactions extends TModel
{
	const FIELD_TRANSACTIONSTYPEID		= 'iTransactionsTypeID'; //what kind of transaction is this? Invoice, order, offer??
	const FIELD_BUYERCONTACTID 			= 'iBuyerContactID'; //buyer: from contacts module
	const FIELD_CREATEDBYCONTACTID 		= 'iCreatedByContactID'; //user who created the invoice (for weborder, it is the buyer itself): from contacts module
	const FIELD_INVOICEDATE 			= 'dtInvoiceDate'; //date of the invoice (assigned only when not a finalized-state-invoice). For orders and offers the date is 0. It is possible that an order is created, but the invoice is made 2 days later. The invoice date is needed for tax reasons. 
	const FIELD_INCREMENTNUMBER 			= 'sInvoiceNumber'; //invoice number (assigned only when in finalized-state-invoice) - this is a string because of extra formatting that can be added to the increment number from transaction-types
	const FIELD_ISFINALIZED				= 'bIsFinalized'; //a finalized invoice can not be changed. Only a finalized invoice has monetary significance for tax purposes
	const FIELD_CURRENCYID 				= 'iCurrencyID'; //id of currency
	const FIELD_PURCHASEORDERNUMBER 	= 'sPurchaseOrderNumber';//purchase order number of buyer (completely optional)
	const FIELD_PAYMENTREMINDERSSENT	= 'iPaymentRemindersSent';//how many payment reminders are sent to buyer?
	const FIELD_NOTESINTERNAL			= 'sNotesInternal';//notes only seen by the seller
	const FIELD_NOTESEXTERNAL			= 'sNotesExternal';//notes only seen by buyer AND the seller
	const FIELD_PSPNAME					= 'sPSPName'; //Payment Service Provider name, like Paypal, Stripe, Molly etc
	const FIELD_PSPTRANSACTIONID		= 'sPSPTransactionID'; //Payment Service Provider transaction id. Paypal, Stripe, IDeal all return transaction id's
	const FIELD_IPADDRESSBUYER			= 'sIAB'; //for tax purposes the ip adress of the buyer. encrypted
	
	const FIELD_META_TOTALPRICEINCLVAT			= 'crTotalPriceInclVAT'; //calculated total amount of invoice INCLUDING VAT
	const FIELD_META_TOTALPRICEEXCLVAT			= 'crTotalPriceExclVAT'; //calculated total amount of invoice EXCLUDING VAT
	const FIELD_META_TOTALPURCHASEPRICEEXCLVAT	= 'crTotalPurchasePriceExclVAT'; //calculated total purchase price EXCLUDING VAT
	const FIELD_META_TOTALVAT					= 'crTotalVAT'; //calculated total amount VAT on invoice
	const FIELD_META_AMOUNTDUE					= 'crAmountDue'; //the amount that the buyer still has to pay
	
	const ENCRYPTION_IPADDR_PASSPHRASE 			= 'MvR%6s_PlcXzw2$4'; //passphrase for the encryption algo	


	/**
	 * get transaction type id
	 * 
	 * @return int
	 */
	public function getTransactionsTypeID()
	{
		return $this->get(TTransactions::FIELD_TRANSACTIONSTYPEID);
	}
	
	/**
	 * set transaction type id
	 * 
	 * @param int $iTypeID
	 */
	public function setTransactionsTypeID($iTypeID)
	{
		$this->set(TTransactions::FIELD_TRANSACTIONSTYPEID, $iTypeID);
	}  

	/**
	 * get contact id
	 * 
	 * @return int
	 */
	public function getBuyerContactID()
	{
		return $this->get(TTransactions::FIELD_BUYERCONTACTID);
	}
	
	/**
	 * set contact id
	 * 
	 * @param int $iContactID
	 */
	public function setBuyerContactID($iContactID)
	{
		$this->set(TTransactions::FIELD_BUYERCONTACTID, $iContactID);
	}        

	
	/**
	 * get user id
	 * 
	 * @return int
	 */
	public function getCreatedByContactID()
	{
		return $this->get(TTransactions::FIELD_CREATEDBYCONTACTID);
	}
	
	/**
	 * set user id
	 * 
	 * @param int $iUserID
	 */
	public function setCreatedByContactID($iUserID)
	{
		$this->set(TTransactions::FIELD_CREATEDBYCONTACTID, $iUserID);
	} 	

	/**
	 * get invoice date
	 * if NOT an invoice (i.e. an order or offer) the returned date = 0
	 * Invoice types like orders and offers don't have invoice dates
	 * 
	 * @return TDateTime
	 */
	public function getInvoiceDate()
	{
		return $this->get(TTransactions::FIELD_INVOICEDATE);
	}
	
	/**
	 * set invoice date
	 * if NOT an invoice (i.e. an order or offer) the returned date = 0
	 * Invoice types like orders and offers don't have invoice dates
	 * 
	 * @param TDateTime $objInvoiceDate
	 */
	public function setInvoiceDate($objInvoiceDate)
	{
		$this->set(TTransactions::FIELD_INVOICEDATE, $objInvoiceDate);
	} 


	/**
	 * get invoice number
	 * 
	 * @return TDateTime
	 */
	public function getInvoiceNumber()
	{
		return $this->get(TTransactions::FIELD_INCREMENTNUMBER);
	}
	
	/**
	 * set invoice number
	 * 
	 * @param string $sInvoiceNumber
	 */
	public function setInvoiceNumber($sInvoiceNumber)
	{
		$this->set(TTransactions::FIELD_INCREMENTNUMBER, $sInvoiceNumber);
	} 	
		

	/**
	 * get finalized state
	 * finalized invoices can NOT be edited and have monetary significance for taxes
	 * 
	 * @return bool
	 */
	public function getIsFinalized()
	{
		return $this->get(TTransactions::FIELD_ISFINALIZED);
	}

	/**
	 * set finalized state
	 * finalized invoices can NOT be edited and have monetary significance for taxes
	 * 
	 * @param bool $bFinal
	 */
	public function setIsFinalized($bFinal)
	{
		$this->set(TTransactions::FIELD_ISFINALIZED, $bFinal);
	} 		


	/**
	 * get currency id
	 * 
	 * @return int
	 */
	public function getCurrencyID()
	{
		return $this->get(TTransactions::FIELD_CURRENCYID);
	}

	/**
	 * set currency id
	 * 
	 * @param int $iCurrencyID
	 */
	public function setCurrencyID($iCurrencyID)
	{
		$this->set(TTransactions::FIELD_CURRENCYID, $iCurrencyID);
	} 	


	/**
	 * get purchase order number
	 * 
	 * @return string
	 */
	public function getPurchaseOrderNumber()
	{
		return $this->get(TTransactions::FIELD_PURCHASEORDERNUMBER);
	}
	
	/**
	 * set purchase order number
	 * 
	 * @param string $sOrderNo
	 */
	public function setPurchaseOrderNumber($sOrderNo)
	{
		$this->set(TTransactions::FIELD_PURCHASEORDERNUMBER, $sOrderNo);
	} 


	/**
	 * get number of reminders sent to buyer
	 * 
	 * @return int
	 */
	public function getPaymentRemindersSent()
	{
		return $this->get(TTransactions::FIELD_PAYMENTREMINDERSSENT);
	}
	
	/**
	 * set number of reminders sent to buyer
	 * 
	 * @param int $iReminders
	 */
	public function setPaymentReminderSent($iReminders)
	{
		$this->set(TTransactions::FIELD_PAYMENTREMINDERSSENT, $iReminders);
	} 


	/**
	 * get internal notes 
	 * (notes only seen by the seller)
	 * 
	 * @return string
	 */
	public function getNotesInternal()
	{
		return $this->get(TTransactions::FIELD_NOTESINTERNAL);
	}
	
	/**
	 * set internal notes
	 * (notes only seen by the seller)
	 * 
	 * @param string $sNotes
	 */
	public function setNotesInternal($sNotes)
	{
		$this->set(TTransactions::FIELD_NOTESINTERNAL, $sNotes);
	} 


	/**
	 * get external notes 
	 * (notes seen by the seller AND buyer)
	 * 
	 * @return string
	 */
	public function getNotesExternal()
	{
		return $this->get(TTransactions::FIELD_NOTESEXTERNAL);
	}
	
	/**
	 * set external notes
	 * (notes seen by the seller AND buyer)
	 * 
	 * @param string $sNotes
	 */
	public function setNotesExternal($sNotes)
	{
		$this->set(TTransactions::FIELD_NOTESEXTERNAL, $sNotes);
	} 


	/**
	 * get Payment Service Provider transaction id 
	 * 
	 * @return string
	 */
	public function getPSPTransactionID()
	{
		return $this->get(TTransactions::FIELD_PSPTRANSACTIONID);
	}
	
	/**
	 * set Payment Service Provider transaction id
	 * 
	 * @param string $sTransID
	 */
	public function setPSPTransactionID($sTransID)
	{
		$this->set(TTransactions::FIELD_PSPTRANSACTIONID, $sTransID);
	} 


	/**
	 * get Payment Service Provider name
	 * (like Paypal, Stripe, Molly etc)
	 * 
	 * @return string
	 */
	public function getPSPName()
	{
		return $this->get(TTransactions::FIELD_PSPNAME);
	}
	
	/**
	 * set Payment Service Provider name
	 * (like Paypal, Stripe, Molly etc)
	 * 
	 * @param string $sPSP
	 */
	public function setPSPName($sPSP)
	{
		$this->set(TTransactions::FIELD_PSPNAME, $sPSP);
	}


	/**
	 * get ip address of buyer
	 * 
	 * @return string
	 */
	public function getIPAdressBuyer()
	{
		return $this->get(TTransactions::FIELD_IPADDRESSBUYER);
	}
	
	/**
	 * set Payment Service Provider transaction id
	 * 
	 * @param string $sIP
	 */
	public function setIPAdressBuyer($sIP)
	{
		$this->set(TTransactions::FIELD_IPADDRESSBUYER, $sIP);
	} 
	

	/**
	 * get meta date database field: total amount including vat
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @return TCurrency
	 */
	public function getMetaTotalPriceInclVat()
	{
		return $this->get(TTransactions::FIELD_META_TOTALPRICEINCLVAT);
	}
	
	/**
	 * set meta date database field: total amount including vat
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @param TCurrency $objTotal
	 */
	public function setMetaTotalPriceInclVat($objTotal)
	{
		$this->set(TTransactions::FIELD_META_TOTALPRICEINCLVAT, $objTotal);
	} 	


	/**
	 * get meta date database field: total amount excluding vat
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @return TCurrency
	 */
	public function getMetaTotalPriceExclVat()
	{
		return $this->get(TTransactions::FIELD_META_TOTALPRICEEXCLVAT);
	}
	
	/**
	 * set meta date database field: total amount excluding vat
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @param TCurrency $objTotal
	 */
	public function setMetaTotalPriceExclVat($objTotal)
	{
		$this->set(TTransactions::FIELD_META_TOTALPRICEEXCLVAT, $objTotal);
	} 


	/**
	 * get meta date database field: total purchase price excluding vat
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @return TCurrency
	 */
	public function getMetaTotalPurchasePriceExclVat()
	{
		return $this->get(TTransactions::FIELD_META_TOTALPURCHASEPRICEEXCLVAT);
	}
	
	/**
	 * set meta date database field: total purchase price excluding vat
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @param TCurrency $objTotal
	 */
	public function setMetaTotalPurchasePriceExclVat($objTotal)
	{
		$this->set(TTransactions::FIELD_META_TOTALPURCHASEPRICEEXCLVAT, $objTotal);
	} 	

	/**
	 * get meta date database field: total amount of vat
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @return TCurrency
	 */
	public function getMetaTotalVat()
	{
		return $this->get(TTransactions::FIELD_META_TOTALVAT);
	}
	
	/**
	 * set meta date database field: total amount of vat
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @param TCurrency $objTotal
	 */
	public function setMetaTotalVat($objTotal)
	{
		$this->set(TTransactions::FIELD_META_TOTALVAT, $objTotal);
	} 


	/**
	 * get meta date database field: amount due
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @return TCurrency
	 */
	public function getMetaAmountDue()
	{
		return $this->get(TTransactions::FIELD_META_AMOUNTDUE);
	}
	
	/**
	 * set meta date database field: amount due
	 * (this does no calculation, merely get the database-field-value)
	 * 
	 * @param TCurrency $objTotal
	 */
	public function setAmountDue($objDue)
	{
		$this->set(TTransactions::FIELD_META_AMOUNTDUE, $objDue);
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
		//contact id
		$this->setFieldDefaultValue(TTransactions::FIELD_TRANSACTIONSTYPEID, 0);
		$this->setFieldType(TTransactions::FIELD_TRANSACTIONSTYPEID, CT_INTEGER64);
		$this->setFieldLength(TTransactions::FIELD_TRANSACTIONSTYPEID, 0);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_TRANSACTIONSTYPEID, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_TRANSACTIONSTYPEID, false);
		$this->setFieldNullable(TTransactions::FIELD_TRANSACTIONSTYPEID, false);
		$this->setFieldEnumValues(TTransactions::FIELD_TRANSACTIONSTYPEID, null);
		$this->setFieldUnique(TTransactions::FIELD_TRANSACTIONSTYPEID, false); 
		$this->setFieldIndexed(TTransactions::FIELD_TRANSACTIONSTYPEID, true); 
		$this->setFieldForeignKeyClass(TTransactions::FIELD_TRANSACTIONSTYPEID, TTransactionsTypes::class);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_TRANSACTIONSTYPEID, TTransactionsTypes::getTable());
		$this->setFieldForeignKeyField(TTransactions::FIELD_TRANSACTIONSTYPEID, TModel::FIELD_ID);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_TRANSACTIONSTYPEID);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_TRANSACTIONSTYPEID, TModel::FOREIGNKEY_REFERENCE_CASCADE);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_TRANSACTIONSTYPEID, TModel::FOREIGNKEY_REFERENCE_RESTRICT); 
		$this->setFieldAutoIncrement(TTransactions::FIELD_TRANSACTIONSTYPEID, false);
		$this->setFieldUnsigned(TTransactions::FIELD_TRANSACTIONSTYPEID, true);
        $this->setFieldEncryptionDisabled(TTransactions::FIELD_TRANSACTIONSTYPEID);

		//contact id
		$this->setFieldDefaultValue(TTransactions::FIELD_BUYERCONTACTID, 0);
		$this->setFieldType(TTransactions::FIELD_BUYERCONTACTID, CT_INTEGER64);
		$this->setFieldLength(TTransactions::FIELD_BUYERCONTACTID, 0);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_BUYERCONTACTID, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_BUYERCONTACTID, false);
		$this->setFieldNullable(TTransactions::FIELD_BUYERCONTACTID, false);
		$this->setFieldEnumValues(TTransactions::FIELD_BUYERCONTACTID, null);
		$this->setFieldUnique(TTransactions::FIELD_BUYERCONTACTID, false); 
		$this->setFieldIndexed(TTransactions::FIELD_BUYERCONTACTID, true); 
		$this->setFieldForeignKeyClass(TTransactions::FIELD_BUYERCONTACTID, TSysContacts::class);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_BUYERCONTACTID, TSysContacts::getTable());
		$this->setFieldForeignKeyField(TTransactions::FIELD_BUYERCONTACTID, TModel::FIELD_ID);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_BUYERCONTACTID);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_BUYERCONTACTID, TModel::FOREIGNKEY_REFERENCE_CASCADE);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_BUYERCONTACTID, TModel::FOREIGNKEY_REFERENCE_RESTRICT); 
		$this->setFieldAutoIncrement(TTransactions::FIELD_BUYERCONTACTID, false);
		$this->setFieldUnsigned(TTransactions::FIELD_BUYERCONTACTID, true);
        $this->setFieldEncryptionDisabled(TTransactions::FIELD_BUYERCONTACTID);

		//createdby contact id
		$this->setFieldCopyProps(TTransactions::FIELD_CREATEDBYCONTACTID, TTransactions::FIELD_BUYERCONTACTID);

        //invoice date
        $this->setFieldDefaultValue(TTransactions::FIELD_INVOICEDATE, 0);
        $this->setFieldType(TTransactions::FIELD_INVOICEDATE, CT_DATETIME);
        $this->setFieldLength(TTransactions::FIELD_INVOICEDATE, 0);
        $this->setFieldDecimalPrecision(TTransactions::FIELD_INVOICEDATE, 0);
        $this->setFieldPrimaryKey(TTransactions::FIELD_INVOICEDATE, false);
        $this->setFieldNullable(TTransactions::FIELD_INVOICEDATE, false);
        $this->setFieldEnumValues(TTransactions::FIELD_INVOICEDATE, null);
        $this->setFieldUnique(TTransactions::FIELD_INVOICEDATE, false);
        $this->setFieldIndexed(TTransactions::FIELD_INVOICEDATE, true);
        $this->setFieldForeignKeyClass(TTransactions::FIELD_INVOICEDATE, null);
        $this->setFieldForeignKeyTable(TTransactions::FIELD_INVOICEDATE, null);
        $this->setFieldForeignKeyField(TTransactions::FIELD_INVOICEDATE, null);
        $this->setFieldForeignKeyJoin(TTransactions::FIELD_INVOICEDATE, null);
        $this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_INVOICEDATE, null);
        $this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_INVOICEDATE, null);
        $this->setFieldAutoIncrement(TTransactions::FIELD_INVOICEDATE, false);
        $this->setFieldUnsigned(TTransactions::FIELD_INVOICEDATE, false);	
		$this->setFieldEncryptionDisabled(TTransactions::FIELD_INVOICEDATE);	
			
		//invoice number
		$this->setFieldDefaultValue(TTransactions::FIELD_INCREMENTNUMBER, '');
		$this->setFieldType(TTransactions::FIELD_INCREMENTNUMBER, CT_VARCHAR);
		$this->setFieldLength(TTransactions::FIELD_INCREMENTNUMBER, 50);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_INCREMENTNUMBER, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_INCREMENTNUMBER, false);
		$this->setFieldNullable(TTransactions::FIELD_INCREMENTNUMBER, true);
		$this->setFieldEnumValues(TTransactions::FIELD_INCREMENTNUMBER, null);
		$this->setFieldUnique(TTransactions::FIELD_INCREMENTNUMBER, false); 
		$this->setFieldIndexed(TTransactions::FIELD_INCREMENTNUMBER, true); 
		$this->setFieldForeignKeyClass(TTransactions::FIELD_INCREMENTNUMBER, null);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_INCREMENTNUMBER, null);
		$this->setFieldForeignKeyField(TTransactions::FIELD_INCREMENTNUMBER, null);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_INCREMENTNUMBER, null);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_INCREMENTNUMBER, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_INCREMENTNUMBER, null);
		$this->setFieldAutoIncrement(TTransactions::FIELD_INCREMENTNUMBER, false);
		$this->setFieldUnsigned(TTransactions::FIELD_INCREMENTNUMBER, false);
        $this->setFieldEncryptionDisabled(TTransactions::FIELD_INCREMENTNUMBER);	
		
		//is finalized
		$this->setFieldDefaultValue(TTransactions::FIELD_ISFINALIZED, false);
		$this->setFieldType(TTransactions::FIELD_ISFINALIZED, CT_BOOL);
		$this->setFieldLength(TTransactions::FIELD_ISFINALIZED, 0);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_ISFINALIZED, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_ISFINALIZED, false);
		$this->setFieldNullable(TTransactions::FIELD_ISFINALIZED, false);
		$this->setFieldEnumValues(TTransactions::FIELD_ISFINALIZED, null);
		$this->setFieldUnique(TTransactions::FIELD_ISFINALIZED, false);
		$this->setFieldIndexed(TTransactions::FIELD_ISFINALIZED, false);
		$this->setFieldForeignKeyClass(TTransactions::FIELD_ISFINALIZED, null);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_ISFINALIZED, null);
		$this->setFieldForeignKeyField(TTransactions::FIELD_ISFINALIZED, null);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_ISFINALIZED, null);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_ISFINALIZED, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_ISFINALIZED, null);
		$this->setFieldAutoIncrement(TTransactions::FIELD_ISFINALIZED, false);
		$this->setFieldUnsigned(TTransactions::FIELD_ISFINALIZED, false);	
        $this->setFieldEncryptionDisabled(TTransactions::FIELD_ISFINALIZED);		
		
		//currency id
		$this->setFieldDefaultValue(TTransactions::FIELD_CURRENCYID, 0);
		$this->setFieldType(TTransactions::FIELD_CURRENCYID, CT_INTEGER64);
		$this->setFieldLength(TTransactions::FIELD_CURRENCYID, 0);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_CURRENCYID, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_CURRENCYID, false);
		$this->setFieldNullable(TTransactions::FIELD_CURRENCYID, false);
		$this->setFieldEnumValues(TTransactions::FIELD_CURRENCYID, null);
		$this->setFieldUnique(TTransactions::FIELD_CURRENCYID, false); 
		$this->setFieldIndexed(TTransactions::FIELD_CURRENCYID, false); 
		$this->setFieldForeignKeyClass(TTransactions::FIELD_CURRENCYID, null);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_CURRENCYID, null);
		$this->setFieldForeignKeyField(TTransactions::FIELD_CURRENCYID, null);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_CURRENCYID, null);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_CURRENCYID, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_CURRENCYID, null); 
		$this->setFieldAutoIncrement(TTransactions::FIELD_CURRENCYID, false);
		$this->setFieldUnsigned(TTransactions::FIELD_CURRENCYID, true);
        $this->setFieldEncryptionDisabled(TTransactions::FIELD_CURRENCYID);	
		
		//purchase order number
		$this->setFieldCopyProps(TTransactions::FIELD_PURCHASEORDERNUMBER, TTransactions::FIELD_INCREMENTNUMBER);

		//reminderssent
		$this->setFieldDefaultValue(TTransactions::FIELD_PAYMENTREMINDERSSENT, 0);
		$this->setFieldType(TTransactions::FIELD_PAYMENTREMINDERSSENT, CT_INTEGER);
		$this->setFieldLength(TTransactions::FIELD_PAYMENTREMINDERSSENT, 0);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_PAYMENTREMINDERSSENT, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_PAYMENTREMINDERSSENT, false);
		$this->setFieldNullable(TTransactions::FIELD_PAYMENTREMINDERSSENT, false);
		$this->setFieldEnumValues(TTransactions::FIELD_PAYMENTREMINDERSSENT, null);
		$this->setFieldUnique(TTransactions::FIELD_PAYMENTREMINDERSSENT, false); 
		$this->setFieldIndexed(TTransactions::FIELD_PAYMENTREMINDERSSENT, false); 
		$this->setFieldForeignKeyClass(TTransactions::FIELD_PAYMENTREMINDERSSENT, null);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_PAYMENTREMINDERSSENT, null);
		$this->setFieldForeignKeyField(TTransactions::FIELD_PAYMENTREMINDERSSENT, null);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_PAYMENTREMINDERSSENT, null);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_PAYMENTREMINDERSSENT, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_PAYMENTREMINDERSSENT, null); 
		$this->setFieldAutoIncrement(TTransactions::FIELD_PAYMENTREMINDERSSENT, false);
		$this->setFieldUnsigned(TTransactions::FIELD_PAYMENTREMINDERSSENT, false);
		$this->setFieldEncryptionDisabled(TTransactions::FIELD_PAYMENTREMINDERSSENT);	
		
		//notes internal
		$this->setFieldDefaultValue(TTransactions::FIELD_NOTESINTERNAL, '');
		$this->setFieldType(TTransactions::FIELD_NOTESINTERNAL, CT_LONGTEXT);
		$this->setFieldLength(TTransactions::FIELD_NOTESINTERNAL, 0);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_NOTESINTERNAL, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_NOTESINTERNAL, false);
		$this->setFieldNullable(TTransactions::FIELD_NOTESINTERNAL, false);
		$this->setFieldEnumValues(TTransactions::FIELD_NOTESINTERNAL, null);
		$this->setFieldUnique(TTransactions::FIELD_NOTESINTERNAL, false); 
		$this->setFieldIndexed(TTransactions::FIELD_NOTESINTERNAL, false); 
		$this->setFieldForeignKeyClass(TTransactions::FIELD_NOTESINTERNAL, null);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_NOTESINTERNAL, null);
		$this->setFieldForeignKeyField(TTransactions::FIELD_NOTESINTERNAL, null);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_NOTESINTERNAL);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_NOTESINTERNAL, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_NOTESINTERNAL, null); 
		$this->setFieldAutoIncrement(TTransactions::FIELD_NOTESINTERNAL, false);
		$this->setFieldUnsigned(TTransactions::FIELD_NOTESINTERNAL, false);
		$this->setFieldEncryptionDisabled(TTransactions::FIELD_NOTESINTERNAL);		

		//notes external
		$this->setFieldCopyProps(TTransactions::FIELD_NOTESEXTERNAL, TTransactions::FIELD_NOTESINTERNAL);
		
		//Payment Service Provider name
		$this->setFieldDefaultValue(TTransactions::FIELD_PSPNAME, '');
		$this->setFieldType(TTransactions::FIELD_PSPNAME, CT_VARCHAR);
		$this->setFieldLength(TTransactions::FIELD_PSPNAME, 100);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_PSPNAME, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_PSPNAME, false);
		$this->setFieldNullable(TTransactions::FIELD_PSPNAME, false);
		$this->setFieldEnumValues(TTransactions::FIELD_PSPNAME, null);
		$this->setFieldUnique(TTransactions::FIELD_PSPNAME, false); 
		$this->setFieldIndexed(TTransactions::FIELD_PSPNAME, false); 
		$this->setFieldForeignKeyClass(TTransactions::FIELD_PSPNAME, null);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_PSPNAME, null);
		$this->setFieldForeignKeyField(TTransactions::FIELD_PSPNAME, null);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_PSPNAME);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_PSPNAME, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_PSPNAME, null); 
		$this->setFieldAutoIncrement(TTransactions::FIELD_PSPNAME, false);
		$this->setFieldUnsigned(TTransactions::FIELD_PSPNAME, false);
		$this->setFieldEncryptionDisabled(TTransactions::FIELD_PSPNAME);			

		
		//Payment Service Provider transaction id
		$this->setFieldCopyProps(TTransactions::FIELD_PSPTRANSACTIONID, TTransactions::FIELD_PSPNAME);


		//ip address buyer
		$this->setFieldDefaultValue(TTransactions::FIELD_IPADDRESSBUYER, '');
		$this->setFieldType(TTransactions::FIELD_IPADDRESSBUYER, CT_LONGTEXT);
		$this->setFieldLength(TTransactions::FIELD_IPADDRESSBUYER, 0);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_IPADDRESSBUYER, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_IPADDRESSBUYER, false);
		$this->setFieldNullable(TTransactions::FIELD_IPADDRESSBUYER, false);
		$this->setFieldEnumValues(TTransactions::FIELD_IPADDRESSBUYER, null);
		$this->setFieldUnique(TTransactions::FIELD_IPADDRESSBUYER, false); 
		$this->setFieldIndexed(TTransactions::FIELD_IPADDRESSBUYER, false); 
		$this->setFieldForeignKeyClass(TTransactions::FIELD_IPADDRESSBUYER, null);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_IPADDRESSBUYER, null);
		$this->setFieldForeignKeyField(TTransactions::FIELD_IPADDRESSBUYER, null);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_IPADDRESSBUYER);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_IPADDRESSBUYER, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_IPADDRESSBUYER, null); 
		$this->setFieldAutoIncrement(TTransactions::FIELD_IPADDRESSBUYER, false);
		$this->setFieldUnsigned(TTransactions::FIELD_IPADDRESSBUYER, false);
		$this->setFieldEncryptionCypher(TTransactions::FIELD_IPADDRESSBUYER, ENCRYPTION_CYPHERMETHOD_AES256CBC);			                          
		$this->setFieldEncryptionDigest(TTransactions::FIELD_IPADDRESSBUYER, ENCRYPTION_DIGESTALGORITHM_SHA512);			                          
		$this->setFieldEncryptionPassphrase(TTransactions::FIELD_IPADDRESSBUYER, TTransactions::ENCRYPTION_IPADDR_PASSPHRASE);	
	

		//META: total including vat
		$this->setFieldDefaultValue(TTransactions::FIELD_META_TOTALPRICEINCLVAT, 0);
		$this->setFieldType(TTransactions::FIELD_META_TOTALPRICEINCLVAT, CT_CURRENCY);
		$this->setFieldLength(TTransactions::FIELD_META_TOTALPRICEINCLVAT, 0);
		$this->setFieldDecimalPrecision(TTransactions::FIELD_META_TOTALPRICEINCLVAT, 0);
		$this->setFieldPrimaryKey(TTransactions::FIELD_META_TOTALPRICEINCLVAT, false);
		$this->setFieldNullable(TTransactions::FIELD_META_TOTALPRICEINCLVAT, false);
		$this->setFieldEnumValues(TTransactions::FIELD_META_TOTALPRICEINCLVAT, null);
		$this->setFieldUnique(TTransactions::FIELD_META_TOTALPRICEINCLVAT, false); 
		$this->setFieldIndexed(TTransactions::FIELD_META_TOTALPRICEINCLVAT, true); //it is not unthinkable you would search for an invoice of which the total amount is between 90 and 120 dollars
		$this->setFieldForeignKeyClass(TTransactions::FIELD_META_TOTALPRICEINCLVAT, null);
		$this->setFieldForeignKeyTable(TTransactions::FIELD_META_TOTALPRICEINCLVAT, null);
		$this->setFieldForeignKeyField(TTransactions::FIELD_META_TOTALPRICEINCLVAT, null);
		$this->setFieldForeignKeyJoin(TTransactions::FIELD_META_TOTALPRICEINCLVAT);
		$this->setFieldForeignKeyActionOnUpdate(TTransactions::FIELD_META_TOTALPRICEINCLVAT, null);
		$this->setFieldForeignKeyActionOnDelete(TTransactions::FIELD_META_TOTALPRICEINCLVAT, null); 
		$this->setFieldAutoIncrement(TTransactions::FIELD_META_TOTALPRICEINCLVAT, false);
		$this->setFieldUnsigned(TTransactions::FIELD_META_TOTALPRICEINCLVAT, false);
		$this->setFieldEncryptionDisabled(TTransactions::FIELD_META_TOTALPRICEINCLVAT);			

		//META: total excluding vat
		$this->setFieldCopyProps(TTransactions::FIELD_META_TOTALPRICEEXCLVAT, TTransactions::FIELD_META_TOTALPRICEINCLVAT);
		$this->setFieldIndexed(TTransactions::FIELD_META_TOTALPRICEEXCLVAT, false); 

		//META: total purchase price excluding vat
		$this->setFieldCopyProps(TTransactions::FIELD_META_TOTALPURCHASEPRICEEXCLVAT, TTransactions::FIELD_META_TOTALPRICEINCLVAT);
		$this->setFieldIndexed(TTransactions::FIELD_META_TOTALPURCHASEPRICEEXCLVAT, false); 

		//META: total vat
		$this->setFieldCopyProps(TTransactions::FIELD_META_TOTALVAT, TTransactions::FIELD_META_TOTALPRICEINCLVAT);
		$this->setFieldIndexed(TTransactions::FIELD_META_TOTALVAT, false); 

		//META: amount due
		$this->setFieldCopyProps(TTransactions::FIELD_META_AMOUNTDUE, TTransactions::FIELD_META_TOTALPRICEINCLVAT);
		$this->setFieldIndexed(TTransactions::FIELD_META_AMOUNTDUE, false); 
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
		return array(TTransactions::FIELD_CREATEDBYCONTACTID, 
					TTransactions::FIELD_INVOICEDATE,
					TTransactions::FIELD_INCREMENTNUMBER,
					TTransactions::FIELD_ISFINALIZED,
					TTransactions::FIELD_CURRENCYID,
					TTransactions::FIELD_PURCHASEORDERNUMBER,
					TTransactions::FIELD_PAYMENTREMINDERSSENT,
					TTransactions::FIELD_NOTESINTERNAL,
					TTransactions::FIELD_NOTESEXTERNAL,
					TTransactions::FIELD_PSPTRANSACTIONID,
					// TTransactions::FIELD_IPADDRESSBUYER,
					TTransactions::FIELD_META_TOTALPRICEINCLVAT,
					TTransactions::FIELD_META_TOTALPRICEEXCLVAT,
					TTransactions::FIELD_META_TOTALVAT,
					TTransactions::FIELD_META_AMOUNTDUE
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
		return true;
	}
		
	/**
	 * use record locking to prevent record editing
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
	 * de child moet deze overerven
	 *
	 * @return string naam van de databasetabel
	*/
	public static function getTable()
	{
		return GLOBAL_DB_TABLEPREFIX.'Transactions';
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
		return $this->get(TTransactions::FIELD_INCREMENTNUMBER).' - '.$this->getMetaTotalPriceInclVat()->getValueFormatted().'';
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
		return 'lekker-verkopen'.
			$this->get(TTransactions::FIELD_BUYERCONTACTID).
			$this->get(TTransactions::FIELD_CREATEDBYCONTACTID).
			'isnietgek'.
			$this->get(TTransactions::FIELD_INCREMENTNUMBER).
			$this->get(TTransactions::FIELD_CURRENCYID).
			$this->get(TTransactions::FIELD_PSPTRANSACTIONID).
			$this->getMetaTotalPriceInclVat()->getValueInternal().
			$this->getMetaTotalPriceExclVat()->getValueInternal().
			$this->getMetaTotalVat()->getValueInternal().
			$this->getMetaAmountDue()->getValueInternal().
			boolToStr($this->get(TTransactions::FIELD_ISFINALIZED));
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