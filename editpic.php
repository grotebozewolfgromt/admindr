<?php include_once("openpopuppage.php"); ?>
<?php 
        //even lomp alle POST/GET variabelen overnemen
	foreach(array_keys($_POST) as $sKey)
            $$sKey = $_POST[$sKey];
        
	foreach(array_keys($_GET) as $sKey)
            $$sKey = $_GET[$sKey];  
        


//var_dump($resizeheight);
//var_dump($resizewidth);
//var_dump($resizeimagequality);
                
        
	//====tijdelijke images ouder dan 1 dag verwijderen
	$d = dir($local_sitemanagerimagestemp);
    while (false !== ($file = $d->read())) 
    {
		$path = "$local_sitemanagerimagestemp/$file";  
	
		if ($file != "." && $file != "..")
		{
			if((time() - fileatime($path)) > (24*60*60)) //als de tijd meer dan 24uur(=24*60*60 seconden) verschilt dan verwijderen
				if(unlink($path) == false) 
					echo "<B>Kon tijdelijk bestand '$file' niet verwijderen</B><BR>";
		}
	}
	//EINDE====tijdelijke images ouder dan 1 dag verwijderen
	
	$MAX_FILE_SIZE = $maxuploadsize; 

	if ($_POST['verwijder'] == "1") //bestanden verwijderen
	{
		if (is_file($local_sitemanagerimagestemp.$tempplaatjegroot))
			if(unlink($local_sitemanagerimagestemp.$tempplaatjegroot) == false)
				echo "fout bij verwijderen van $tempplaatjegroot";
		if (is_file($local_sitemanagerimagestemp.$tempplaatjeklein))
			if (unlink($local_sitemanagerimagestemp.$tempplaatjeklein) == false)
				echo "fout bij verwijderen van $tempplaatjeklein";
		if (is_file($local_sitemanagerimages.$plaatjegroot))
			if (unlink($local_sitemanagerimages.$plaatjegroot) == false)
				echo "fout bij verwijderen van $plaatjegroot";
		if (is_file($local_sitemanagerimages.$plaatjeklein))
			if (unlink($local_sitemanagerimages.$plaatjeklein) == false)
				echo "fout bij verwijderen van $plaatjeklein";
			
	}
	


	if ($_FILES["userfile"]["tmp_name"] != "") //uploaden
	{
//		echo 'test';
		//van $file een path maken
		$iRandom = rand(10000,99999);
		$tempplaatjegroot = deliverFileName($local_sitemanagerimagestemp, getExtension($_FILES["userfile"]["name"]), getFileNameWithoutExtension($_FILES["userfile"]["name"])."_groot", $iRandom);
		$tempplaatjeklein = deliverFileName($local_sitemanagerimagestemp, getExtension($_FILES["userfile"]["name"]), getFileNameWithoutExtension($_FILES["userfile"]["name"])."_klein", $iRandom);
		
		$tmp_name = $_FILES["userfile"]["tmp_name"];
		move_uploaded_file ($tmp_name, $local_sitemanagerimagestemp.$tempplaatjegroot);
		//echo "uploaded : ".$local_sitemanagerimagestemp.$tempplaatjegroot;

		chmod ($local_sitemanagerimagestemp.$tempplaatjegroot, $iOctalRechtenOpFiles);
		//copy($local_sitemanagerimagestemp.$tempplaatjegroot, $local_sitemanagerimagestemp.$tempplaatjeklein); //tijdelijk
                

		resizeJPG($local_sitemanagerimagestemp.$tempplaatjegroot, $local_sitemanagerimagestemp.$tempplaatjeklein, $resizeimagequality, false, $resizeheight, $resizewidth);
	}
?>
<table width="100%" height="100%" border="0">
  <tr> 
    <td align="center" valign="middle"><table width="90%" border="0">
        <tr> 
          <td bgcolor="#000000"><table width="100%" border="0" bgcolor="#EAEAEA">
              <tr> 
                <td width="200" align="center" valign="middle"> 
                  <?php
			if (is_file($local_sitemanagerimagestemp.$tempplaatjeklein))
			{					
			?>
                  <img src="<?php echo $www_sitemanagerimagestemp.$tempplaatjeklein ?>" border="0" name="picdetail"> 
                  <?php
			}
			else
			{
				if (is_file($local_sitemanagerimages.$plaatjeklein))
				{					
				?>
                  <img src="<?php echo $www_sitemanagerimages.$plaatjeklein ?>" border="0" name="picdetail"> 
                  <?php
				}
				else
				{
					echo "[geen afb. geladen]";
				}
			}
		?>
                </td>
                <td> <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr> 
                      <td> <form enctype="multipart/form-data" action="editpic.php" method="post" name="frmUpload">
                          <input type="hidden" name="resizeimagequality" value="<?php echo $resizeimagequality; ?>">
                          <input type="hidden" name="resizeheight" value="<?php echo $resizeheight; ?>">
                          <input type="hidden" name="resizewidth" value="<?php echo $resizewidth; ?>">
                          <input type="hidden" name="tempplaatjeklein" value="<?php echo $tempplaatjeklein; ?>">
                          <input type="hidden" name="tempplaatjegroot" value="<?php echo $tempplaatjegroot; ?>">
                          <input type="hidden" name="plaatjegroot" value="<?php echo $plaatjegroot; ?>">
                          <input type="hidden" name="plaatjeklein" value="<?php echo $plaatjeklein; ?>">
                          <input name="userfile" type="file" size="50" >
                          <BR>
                          <input name="btnSubmit" type="submit" value="upload afbeelding" onClick="window.document.frmUpload.submit();waitimage.src='<?php echo $www_sitemanageradminimages; ?>progressbar.gif';window.document.frmUpload.btnSubmit.disabled = true;">
                          <input name="verwijder" type="hidden" value="1">
                          <br>
                        </form></td>
                    </tr>
                    <tr> 
                      <td>&nbsp;</td>
                    </tr>
                    <tr> 
                      <td> 
                        <?php	if (is_file($local_sitemanagerimagestemp.$tempplaatjeklein))
					{ ?>
                        <input type="button" name="btnOk" value="ok, bestand gebruiken" onClick="opener.showPic()"> 
                        <?php } ?>
                        <input type="button" name="btnAnnuleren" value="annuleren" onClick="self.close();"></td>
                    </tr>
                  </table></td>
              </tr>
              <tr bgcolor="#000000"> 
                <td colspan="2" align="center" valign="middle">
					<table width="100%" border="0" bgcolor="#EAEAEA" cellpadding="1" cellspacing="0">
                    <tr> 
                      <td><img src="<?php echo $www_sitemanageradminimages; ?>transparantpixel.gif" width="100%" height="5" name="waitimage"></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<br>
<?php include_once("closepopuppage.php"); ?>
