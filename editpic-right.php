<?php include_once("openpopuppage.php"); ?>
<?
	$arrExt[] = "jpg";
	$arrExt[] = "jpeg";
?>
<script language="JavaScript">
	function inputFileRename(sFile)
	{
		var sNewName= prompt('Voer een nieuwe naam in voor het bestand ' + sFile + '\n(vergeet de extensie JPG niet)', sFile);
	
		if ((sNewName != "") && (sNewName != null))
		{
			location.href = "editpic-right.php?rename=1&file="+  sFile +  "&fileDest=" + sNewName;
		}
	}
	
	parent.left.location.reload();
</script>


<?
	if ($delete == 1)
	{
		if(unlink($local_sitemanagerimages.$file) == false)
			echo "kan bestand '$file' niet verwijderen. Heeft u wel voldoende rechten ?<BR>";
		else
			echo "bestand '$file' verwijderd<BR>";
	}
	
	if ($rename == 1)
	{
		if ((strtoupper(getExtension($fileDest)) != "JPG") && (strtoupper(getExtension($fileDest)) != "JPEG"))
		{
			$fileDest = $fileDest.".jpg";
		}

		
		if(rename($local_sitemanagerimages.$file, $local_sitemanagerimages.$fileDest) == false)
			echo "kan bestand '$file' niet hernoemen. Heeft u wel voldoende rechten ?<BR>";
		else
			echo "bestand '$file' hernoemd naar '$fileDest'<BR>";
	}	
	
	if ($upload == 1) //uploaden
	{
		$sTmp_name = $_FILES["userfile"]["tmp_name"];
		$sName = $_FILES["userfile"]["name"];
		if ((strtoupper(getExtension($sName)) == "JPG") || (strtoupper(getExtension($sName)) == "JPEG"))
		{
			move_uploaded_file ($sTmp_name, $local_sitemanagerimages.$sName);
			echo "ge-upload : ".$sName;
			chmod ($local_sitemanagerimages.$sName, $iOctalRechtenOpFiles);
		}
		else
		{
			echo "<B>Bestand heeft geen JPG of JPEG extensie. Bestand is NIET geupload</B>";
		}
		
	}	


	$arrFiles = getFileFolderArrayExtension($local_sitemanagerimages, false, true, $arrExt);
?>

<TABLE border="0" cellpadding="2" cellspacing="0">
 <?
	for($iTeller = 0; $iTeller < count($arrFiles); $iTeller++)
	{
		$file = $arrFiles[$iTeller];
		if ($file != "." && $file != "..")
		{	
			$sExtension = strtoupper(getExtension($local_sitemanagerimages."/".$file));
			$bApproved = (($sExtension == "JPG") || ($sExtension == "JPEG")); //alleen jpg en jpeg files laten zien
			if (is_file($local_sitemanagerimages.$file) && ($bApproved))
			{
				//echo getExtension($local_sitemanagerfotoboek.$id."/".$file)."-";	
?>
  <TR <?php echo getRowColor() ?>> 
	<TD> <?php echo "<IMG src=\"$www_sitemanageradminimagesfiles".getImageByExtension($path)."\" alt=\"$file\">"; ?></TD>
    <TD width="300">&nbsp;<A href="editpic-left.php?file=<?php echo $file ?>" target="left"><?php echo $file; ?></A></TD>
    <TD><a href="javascript:popUp('editpic-resize.php?file=<?php echo $file; ?>')"><img src="images_cms2/resize.gif" border="0"></a></TD>
    <TD><a href="javascript:inputFileRename('<?php echo $file; ?>');"><img src="images_cms2/update.gif" border="0"></a></TD>
    <TD> <a href="javascript:confirmDeleteRecord('<?php echo "editpic-right.php?delete=1&file=".$file; ?>', '<?php echo $file; ?>')"><img src="images_cms2/del.gif" border="0"></a> 
    </TD>
  </TR>
<?
			}//einde if is_dir
		} //einde if ($file != "." && $file != "..")
	}//einde while
?>
</TABLE>
<form enctype="multipart/form-data" action="editpic-right.php" method="post" name="frmUpload">
<input name="userfile" type="file" size="15" maxlength="255">
<input type="hidden" name="upload" value="1">
<input name="btnSubmit" type="submit" value="upload" onClick="window.document.frmUpload.submit();waitimage.src='<?php echo $www_sitemanageradminimages; ?>progressbar.gif';window.document.frmUpload.btnSubmit.disabled = true;">
</form>
<img src="<?php echo $www_sitemanageradminimages; ?>transparantpixel.gif" width="100%" height="5" name="waitimage">
<?php include_once("closepopuppage.php"); ?>
