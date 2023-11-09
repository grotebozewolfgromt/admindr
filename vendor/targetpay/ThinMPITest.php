<?php
/* ******************************************************************************
 * History: 
 * $Log: ThinMPITest.php,v $
 * Revision 1.1.2.9  2005/12/01 17:27:07  mike
 * bug in dirReq fixed
 *
 * Revision 1.1.2.8  2005/10/18 09:25:45  mos
 * AW references removed
 *
 * Revision 1.1.2.5  2005/09/05 20:32:30  mike
 * source files redesigned
 *
 * Revision 1.1.2.4  2005/06/28 08:59:19  mike
 * switch to Ascii
 * 
 * ****************************************************************************** 
 * Last CheckIn : $Author: mike $ 
 * Date : $Date: 2005/12/01 17:27:07 $ 
 * Revision : $Revision: 1.1.2.9 $ 
 * Repository File : $Source: /cvs/as/WLP_NEW/src_php/Attic/ThinMPITest.php,v $ 
 * ******************************************************************************
 */

require_once("ThinMPI.php");
require_once("DirectoryRequest.php");
require_once("DirectoryResponse.php");
require_once("AcquirerStatusRequest.php");
require_once("AcquirerStatusResponse.php");
require_once("AcquirerTrxRequest.php");
require_once("AcquirerTrxResponse.php");
require_once("LoadConf.php");

// clear variables
$errorCode = "";
$errorMsg = "";
$acquirerID = "";
$issuerList = "";
$IssuerAuthenticationURL = "";
$authenticated = "";
$consumerName = "";
$consumerAccountNumber = "";
$consumerCity = "";

$type = 0;	// directory request

// set defaults
$button = $_POST["Button"];
$merchantID = $_POST["MerchantID"];
$subID = $_POST["SubID"];
$authType = $_POST["Authtype"];
$issuerID = $_POST["IssuerID"];
$merchantReturnURL = $_POST["MerchantReturnURL"];
$purchaseID = $_POST["PurchaseID"];
$amount = $_POST["Amount"];
$currency = $_POST["Currency"];
$expirationPeriod = $_POST["ExpirationPeriod"];
$language = $_POST["Language"];
$description = $_POST["Description"];
$entranceCode = $_POST["EntranceCode"];
$transactionID = $_POST["TransactionID"];

// start the transmission-class
$thinMPI = new ThinMPI();

// load defaults
$conf = LoadConfiguration();

if ($merchantID == "")
	$merchantID = $conf["MERCHANTID"];
if ($subID == "")
	$subID = $conf["SUBID"];
if ($authType == "")
	$authType = $conf["AUTHENTICATIONTYPE"];
if ($merchantReturnURL == "")
	$merchantReturnURL = $conf["MERCHANTRETURNURL"];
if ($curreny == "")
	$currency = $conf["CURRENCY"];
if ($expirationPeriod == "")
	$expirationPeriod = $conf["EXPIRATIONPERIOD"];
if ($language == "")
	$language = $conf["LANGUAGE"];
if ($description == "")
	$description = $conf["DESCRIPTION"];
if ($entranceCode == "")
	$entranceCode = $conf["ENTRANCECODE"];


if ($button == "DirectoryRequest") {
	$request = & new DirectoryRequest();
	$request->setMerchantID( $merchantID );
	$request->setSubID( $subID );
	$request->setAuthentication( $authType );
	
	$response = $thinMPI->ProcessRequest( $request );

	if ($response->isOk() != "1") {
        	$errorCode = $response->getErrorCode();
        	$errorMsg = $response->getErrorMessage();
	} else {
		$IssuerList = $response->getIssuerList();
		$trans = array (" " => "&nbsp");
		foreach ($IssuerList as $Issuer => $wert) {
			$issuerList = $issuerList . "<option value=\"" . $wert->getIssuerID() ."\">"
				. strtr(str_pad($wert->getIssuerID(), 20), $trans) . " "
				. $wert->getIssuerName() . "</option>\n";
		}
		$acquirerID = $response->getAcquirerID();
		$type = 1;	// AcquirerTrxRequest
	}
} else if ($button == "AcquirerTrxRequest") {
	$request = & new AcquirerTrxRequest();
	// Issuer
	$request -> setIssuerID( $issuerID );
	// Merchant
	$request -> setMerchantID( $merchantID );
	$request -> setSubID( $subID );
	$request -> setAuthentication( $authType );
	$request -> setMerchantReturnURL( $merchantReturnURL );
	// Transaction
	$request -> setPurchaseID( $purchaseID );
	$request -> setAmount( $amount );
	$request -> setCurrency( $currency );
	$request -> setExpirationPeriod( $expirationPeriod );
	$request -> setLanguage( $language );
	$request -> setDescription( $description );
	$request -> setEntranceCode( $entranceCode );
	$response = $thinMPI->ProcessRequest( $request );
	if ($response->isOk() != "1") {
        	$errorCode = $response->getErrorCode();
        	$errorMsg = $response->getErrorMessage();
	} else {
		$acquirerID = $response->getAcquirerID;
		$issuerAuthenticationURL = $response->getIssuerAuthenticationURL();
		$transactionID = $response->getTransactionID();
		$type = 3;
	}
} else if ($button == "AcquirerStatusRequest") {
	$request = & new AcquirerStatusRequest();
	$request->setMerchantID( $merchantID );
	$request->setSubID( $subID );
	$request->setAuthentication( $authType );
	//Transaction
	$request -> setTransactionID( str_pad($transactionID, 16, "0", STR_PAD_LEFT) );

	$response = $thinMPI->ProcessRequest( $request );
	if ($response->isOk() != "1") {
        	$errorCode = $response->getErrorCode();
        	$errorMsg = $response->getErrorMessage();
	} else {
		$authenticated = $response->isAuthenticated();
   		$consumerName = $response->getConsumerName();
		$consumerAccountNumber = $response->getConsumerAccountNumber();
		$consumerCity = $response->getConsumerCity();
		$type = 4;
	}
}

print("<HTML>\n");
print("<HEAD>\n");
print("<style type=\"text/css\">\n");
print("div.result { border:3pt solid #0000ff; padding: 10pt; margin-top: 15px; margin-bottom: 15px; font-size:16pt; }\n");
print("div.input { margin-top: 15px; margin-bottom: 15px; margin-left: 15 px; margin-right: 15px; }\n");
print("div.error { border:3pt solid #ff0000; padding: 10pt; margin-top: 15px; margin-bottom: 15px; font-size:16pt; }\n");
print("div.form { border:3pt solid #0000ff; padding: 10pt; margin-top: 15pt; margin-bottom: 15px; }\n");
print("div.disclaim { border:3pt solid #ff0000; padding: 10pt; margin-top: 15px; margin-bottom: 15px; background-color:#ffe0e0;}\n");
print("span.explain { border: 1pt solid #888888; padding: 2pt; margin: 2pt; font-size:10pt; background-color:#FFFF66; }\n");
print("span.descr { width: 100px; }\n");
print("img.icon { border:1pt solid #0000ff; margin: 15px; }\n");
print("input { margin-left:15px; margin-right:15px; }\n");
print("</style>\n");
print("</HEAD>\n");
print("<BODY>\n");

print("<img class=\"icon\" src=\"icons/iDeal.jpg\" alt=\"iDEAL\">\n");

print("<div class=\"disclaim\">This page is for testing the merchant-acquirer communication. ");
print("It is provided on an \"as is\" basis. There is no warranty or support of any kind ");
print("beside the comments given in the source files.</div>\n");

if ($errorMsg != "") {
    print("<div class=\"error\">\n");
    print("ErrorCode: " . $errorCode . "<br>\n");
    print("ErrorMessage: " . $errorMsg . "<br>\n");
    print("</div>\n");
}

if ($type == 0) {	// no form submitted, so we should show the directoryRequest form
    print("<div class=\"form\">\n");
    print("<form action='ThinMPITest.php' method=\"POST\">\n");
    print("<span class=\"explain\">The directory request retrieves a list of all issuers known to this merchant. One do not need ");
    print("to perform the directory request at each transaction.</span>\n");
    print("<div class=\"input\"><span class=\"descr\">MerchantID: </span>");
    print("<input name =\"MerchantID\" type='text' size='20' value='" . $merchantID . "'></input>");
    print("<span class=\"explain\">The merchantID is given by the acquirer</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">SubID: </span>");
    print("<input name =\"subID\" type='text' size='20' value='" . $subID . "'></input>");
    print("<span class=\"explain\">The subID is given by the acquirer</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">Authtype: </span>");
    print("<input name =\"Authtype\" type='text' size='20' value='" . $authType . "'></input>");
    print("<span class=\"explain\">The authenticationType specifies how to authenticate the merchant. Currently only SHA1_RSA is supported</span></div>\n");
    print("<span class=\"explain\">Token and Tokencode will be calculated based on the Authtype.</span>");
    print("<input name =\"Button\" type='Submit' value='DirectoryRequest'></input>\n");
    print("</form>\n");
    print("</div>\n");
}
if ($type == 1) {	// we have to show the result of dirRequest now and the button for AcquirerTrxRequest
    print("<div class=\"form\">\n");
    print("<form action='ThinMPITest.php' method=\"POST\">\n");
    print("<span class=\"explain\">The Acquirer Request is the first step of the real transaction. It is in fact a request ");
    print("to the issuer to perform a payment from a customers account to the merchants account</span>\n");
    print("<div class=\"input\"><span class=\"descr\">MerchantID: </span>");
    print("<input name =\"MerchantID\" type='text' size='20' value='" . $merchantID . "'></input>");
    print("<span class=\"explain\">The merchantID is given by the acquirer</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">SubID: </span>");
    print("<input name =\"SubID\" type='text' size='20' value='" . $subID . "'></input>");
    print("<span class=\"explain\">The subID is given by the acquirer</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">issuerID: </span>");
    print("<select name=\"IssuerID\">");
    print( $issuerList );
    print("</select>\n");
    print("<span class=\"explain\">The List of issuers retrieved from the directoryRequest</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">MerchantReturnURL: </span>");
    print("<input name =\"MerchantReturnURL\" type='text' size='50' value='" . $merchantReturnURL . "'></input>\n");
    print("<span class=\"explain\">The URL the acquirer should be redirected to after the payment</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">Description: </span>");
    print("<input name =\"Description\" type='text' size='50' value='" . $description . "'></input>\n");
    print("<span class=\"explain\">The description of the payment</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">PurchaseID: </span>");
    print("<input name =\"PurchaseID\" type='text' size='20' value='" . $purchaseID . "'></input>\n");
    print("<span class=\"explain\">The purchaseID for this payment</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">Amount: </span>");
    print("<input name =\"Amount\" type='text' size='20' value='" . $amount . "'></input>\n");
    print("<span class=\"explain\">The amount that should be payed</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">Currency: </span>");
    print("<input name =\"Currency\" type='text' size='20' value='" . $currency . "'></input>\n");
    print("<span class=\"explain\">The currency of the bill (EUR)</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">ExpirationPeriod: </span>");
    print("<input name =\"ExpirationPeriod\" type='text' size='20' value='" . $expirationPeriod . "'></input>\n");
    print("<span class=\"explain\">The length of time the payment process can last before it will be cancelled</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">Language: </span>");
    print("<input name =\"Language\" type='text' size='20' value='" . $language . "'></input>\n");
    print("<span class=\"explain\">The desired language of the customer</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">EntranceCode: </span>");
    print("<input name =\"EntranceCode\" type='text' size='50' value='" . $entranceCode . "'></input>\n");
    print("<span class=\"explain\">The entranceCode enables the merchant to recognise the consumer and his transaction</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">Authtype: </span>");
    print("<input name =\"Authtype\" type='text' size='20' value='" . $authType . "'></input>");
    print("<span class=\"explain\">The authenticationType specifies how to authenticate the merchant. Currently only SHA1_RSA is supported</span></div>\n");
    print("<span class=\"explain\">Token and Tokencode will be calculated based on the Authtype.</span>");
    print("<input name =\"Button\" type='Submit' value='AcquirerTrxRequest'></input>\n");
    print("</form>\n");
    print("</div>\n");
}
if ($type == 3) {
    print("<div class=\"form\">\n");
    print("<H2>Result of last TrxRequest: </H2>\n");
    print("acquirerID: " . $acquirerID . "<br>\n");
    print("IssuerAuthenticationURL: " . $issuerAuthenticationURL . " (redirect the customer to this url to perform the payment)<br>\n");
    print("transactionID: " . $transactionID . " (keep this ID for future references)<br>\n");
    print("</div>\n");
}
if ($type == 4) {
    print("<div class=\"form\">\n");
    print("<H2>Result of last Status: </H2>\n");
    print("Authenticated: " . $authenticated . "<br>\n");
    print("ConsumerName: " . $consumerName . "<br>\n");
    print("ConsumerAccountNumber: " . $consumerAccountNumber . "<br>\n");
    print("ConsumerCity: " . $consumerCity . "<br>\n");
    print("</div>\n");
}
if ($type == 3 || $type == 4) {	// Type 3: Result of TrxRequest and Button of StatusRequest
    print("<div class=\"form\">\n");
    print("<form action='ThinMPITest.php' method=\"POST\">\n");
    print("<span class=\"explain\">The Status Request queries the acquirer and issuer for the status of the payment. ");
    print("This is necessary in order to know whether the goods can be delivered to the customer or not.</span>\n");
    print("<div class=\"input\"><span class=\"descr\">MerchantID: </span>");
    print("<input name =\"MerchantID\" type='text' size='20' value='" . $merchantID . "'></input>");
    print("<span class=\"explain\">The merchantID is given by the acquirer</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">SubID: </span>");
    print("<input name =\"subID\" type='text' size='20' value='" . $subID . "'></input>");
    print("<span class=\"explain\">The subID is given by the acquirer</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">TransactionID: </span>");
    print("<input name =\"TransactionID\" type='text' size='20' value='" . $transactionID . "'></input>\n");
    print("<span class=\"explain\">The unique ID for the payment got from the acquirerTrxResponse</span></div>\n");
    print("<div class=\"input\"><span class=\"descr\">Authtype: </span>");
    print("<input name =\"Authtype\" type='text' size='20' value='" . $authType . "'></input>");
    print("<span class=\"explain\">The authenticationType specifies how to authenticate the merchant. Currently only SHA1_RSA is supported</span></div>\n");
    print("<span class=\"explain\">Token and Tokencode will be calculated based on the Authtype.</span>");
    print("<input name =\"Button\" type='Submit' value='AcquirerStatusRequest'></input>\n");
    print("</form>\n");
    print("</div>\n");
}
if ($type == 4) {
    print("<div class=\"form\">\n");
    print("<a href='ThinMPITest.php'>Klick here to repeat the whole test</a>\n");
    print("</div>\n");
}
print("</HTML>\n");
print("</BODY>\n");

?>
