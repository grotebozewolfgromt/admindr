<?
	include "config.php";
	//include "library.php";
	
	header('Content-Type: image/jpeg');
	header('Content-Disposition: inline; filename=afbeelding.jpg');

	//cachen uitzetten
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");    // Datum in het verleden
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // Altijd veranderd
	header("Cache-Control: no-store, no-cache, must-revalidate");  // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");                          // HTTP/1.0
	
	//moet bekend zijn, voordat script uitgevoerd wordt:
	//$table
	//$dbfield
	//$idfield
	//$idvalue
	
	$link = mysql_connect($sqlhost, $sqluser, $sqlpassword) or die("Could not connect<br>");
	mysql_select_db($sqldatabase) or die("Could not select database<br>");
	$sSQL = "SELECT $dbfield FROM $table WHERE $idfield = '$idvalue'";
	$result = mysql_query($sSQL) or die("<B>".mysql_error()."</B><BR>".$sSQL);
	$row = mysql_fetch_array($result);
	$jpg = $row[$dbfield];
	echo base64_decode($jpg);

	
	//echo stripslashes($binFileContent);

    /* Closing connection */
    mysql_close($link);
?>