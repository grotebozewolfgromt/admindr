<html>
<head>
<title>Untitled Document</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<TABLE border="0" cellpadding="0" cellspacing="0">
<TR>
<?
	for ($iTeller = 0; $iTeller < hexdec("F"); $iTeller = $iTeller + (hexdec("FFFFFF")/10000))
	{
		
		echo "<TD bgcolor=\"".dechex($iTeller)."\"><IMG src=\"images/transparantpixel.gif\" width=\"10\" height=\"10\"></TD>\n";
	}
?>
</TR>
</TABLE>

</body>
</html>
