<?php include_once("openpage.php"); ?>
<?
	$iId = 1;
	$sTable = $tblFrontpageOver;
	
	if ($userfile != null)
	{
		$tmp_name = $_FILES["userfile"]["tmp_name"];
		$fileHandle = fopen($tmp_name, "r"); 
		$fileContent = fread($fileHandle, filesize($tmp_name)); 
		$fileContent = base64_encode($fileContent);	
		$fileContent = addslashes($fileContent);

		
		$sSQL = "UPDATE $sTable SET bin_afbeelding = '$fileContent' WHERE i_id = '$iId'";	
		$result = mysql_query($sSQL) or die("<B>".mysql_error()."</B><BR>".$sSQL);
	}
?>
<IMG src="showdbimagejpg.php?table=<?php echo $tblFrontpageOver ?>&dbfield=bin_afbeelding&idfield=i_id&idvalue=<?php echo $iId ?>">
<form enctype="multipart/form-data" action="tempsavepicdb.php" method="post" name="frmUpload">

<input name="userfile" type="file" size="50" maxlength="255">
<BR>
<input name="btnSubmit" type="submit" value="upload afbeelding" onClick="window.document.frmUpload.submit();waitimage.src='<?php echo $www_sitemanageradminimages; ?>progressbar.gif';window.document.frmUpload.btnSubmit.disabled = true;">
<input name="verwijder" type="hidden" value="1">
<br>
</form>
<?php include_once("closepage.php"); ?>