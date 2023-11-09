<html>
<head>

<script language="JavaScript" type="text/JavaScript">
function refresh()
{
	window.location.reload()
}
</script>
</head>

<body>

<?php

	//include needed files
	require_once(dirname(__FILE__) . "/ThinMPI.php");
	require_once(dirname(__FILE__) . "/AcquirerStatusRequest.php");
	
	//Create StatusRequest
	$data = & new AcquirerStatusRequest();
	
	//Set parameters
	//$data -> setMerchantID( $merchantID );
	//$data -> setSubID( "0" );
	//$data -> setAuthentication( $authentication );
	$transID = $_GET['trxid'];
	$transID = str_pad($transID, 16, "0");
	$data -> setTransactionID( $transID  );
	
	//Create ThinMPI instance and process request
	$rule = new ThinMPI();
	$result = $rule->ProcessRequest( $data );
	
	if(!$result->isOK())
	{
		//StatusRequest failed, let the consumer click to try again
		print("Status kon niet worden opgehaald, klik <a href=\"\" onclick=\"refresh()\">hier</a> om het nogmaals te proberen<br>");
		print("Foutmelding van iDEAL: ");
		$Msg = $result->getErrorMessage();
		print("$Msg<br>");
	}
	else if(!$result->isAuthenticated())
	{
		//Transaction failed, inform the consumer
		print("Uw bestelling is helaas niet betaald, probeer het nog eens");
	}
	else
	{
		print("<br>Bedankt voor uw bestelling bij Demowinkel");
		$transactionID = $result->getTransactionID();
		//Here you should retrieve the order from the database, mark it as "payed"
		//and display the result to your customer. Here we retrieve the order from 
		//a file
		$filename = "trans".$transactionID;
		print("<pre>");
		include($filename);
		print("</pre>");
		print("De bestelling is betaald en wordt naar u opgestuurd");
	}
	
?>
</body>
</html>