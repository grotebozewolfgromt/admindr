<?php
	ob_start();
?>
<html>
<head>
	<title>Betaling</title>
</head>
<body>
<h1>Bestelling</h1>
<?php
	//Show errors so we know if any PHP error occurs
	ini_set('display_errors',1);
	error_reporting(E_ALL & ~E_NOTICE);
	
	//include needed files
	require_once(dirname(__FILE__) . "/ThinMPI.php");
	require_once(dirname(__FILE__) . "/AcquirerTrxRequest.php");

	//Put information from form in variables
	$orderNumber = $_POST['ordernumber'];
	$amount = $_POST['grandtotal'];
	$amount *= 100;//Multiply amount by 100 to remove decimals
	$product1number = $_POST['Product1Number'];
	$product2number = $_POST['Product2Number'];
	$product3number = $_POST['Product3Number'];
	$product4number = $_POST['Product4Number'];
	$issuerID = $_POST['issuerID'];
	if($issuerID==0)
	{
		print("Kies uw bank uit de lijst om met iDEAL te betalen<br>");
		exit();
	}
	//Create TransactionRequest
	$data = & new AcquirerTrxRequest();
	
	//Set parameters for TransactionRequest
	$data -> setIssuerID($issuerID);
	//$directory = "http://" . $_SERVER["HTTP_HOST"] . $_SERVER["PHP_SELF"];
	//$directory = substr($directory, 0, strrpos($directory,"/")+1);
	//$returnURL = $directory . "StatReq.php";
	//$data -> setMerchantReturnURL($returnURL ); 
	$data -> setPurchaseID( $orderNumber  );
	$data -> setAmount($amount );
	//$data -> setCurrency( "EUR" );
	//$data -> setExpirationPeriod( "PT1H" );
	//$data -> setLanguage( "nl");
	//$description = "Demowinkel $orderNumber";
	//$data -> setDescription( $description );
	//$data -> setEntranceCode( "641828d26ad24739809e4bb762a5be4a");
	//$data -> setAcqURL( "https://idealtest.secure-ing.com/ideal/iDeal" );
	
	//Create ThinMPI instance
	$rule = new ThinMPI();
	
	$result = new AcquirerTrxResponse();
	
	//Process Request
	$result = $rule->ProcessRequest( $data );
	
	
	if($result->isOK())
	{
		
		$transactionID = $result->getTransactionID();
		//Here you should store the transactionID along with the order (in the database
		//of your webshop system) so you can later retrieve the order with the 
		//transactionID.  To keep this example simple we store the transaction in a file
		$filename = "trans".$transactionID;
		$file = fopen($filename, 'w');
		
		fputs($file, $description, strlen($description));
		fputs($file, "\r\n\r\n");
		
		
		$strLine = "$product1number x Product 1";
		fputs($file, $strLine , strlen($strLine));
		fputs($file, "\r\n\r\n");
		
		$strLine = "$product2number x Product 2";
		fputs($file, $strLine , strlen($strLine));
		fputs($file, "\r\n\r\n");
		
		$strLine = "$product3number x Product 3";
		fputs($file, $strLine , strlen($strLine));
		fputs($file, "\r\n\r\n");
		
		$strLine = "$product4number x Product 4";
		fputs($file, $strLine , strlen($strLine));
		fputs($file, "\r\n\r\n");
		
		$amount /= 100;
		$strLine = "Voor een totaalbedrag van $amount";
		fputs($file, $strLine , strlen($strLine));
		fputs($file, "\r\n\r\n");
		
		fclose($file);
	

		//Get IssuerURL en decode it
		$ISSURL = $result->getIssuerAuthenticationURL();
		$ISSURL = html_entity_decode($ISSURL);
	
		//Redirect the browser to the issuer URL
		header("Location: $ISSURL"); 
		exit();
	}
	else
	{
		//TransactionRequest failed, inform the consumer
		print("Er is helaas iets misgegaan. Foutmelding van iDEAL:<br>");
		$Msg = $result->getErrorMessage();
		print("$Msg<br>");
	}
?>

</body>
</html>