<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php
        //even lomp alle POST variabelen overnemen
	foreach(array_keys($_POST) as $sKey)
            $$sKey = $_POST[$sKey];
        
        if (is_numeric($_GET['id']))
            $id = $_GET['id'];
        else
            $id = '';
        
        $bNieuwRecord = (!is_numeric($_GET['id']));
	
        
	
	if ($bNieuwRecord)
	{
		//als nieuw, dan nieuwe id en volgorde maken
		$id = getDBValuePlus1("i_id", $tblWeblinks);
		$volgorde = getDBValuePlus1("i_volgorde", $tblWeblinks);
	}
	else
	{
		$sSql = "SELECT i_volgorde FROM $tblWeblinks WHERE i_id = $id";
		$arrResult = mysqliToArray($sSql);
		foreach ($arrResult as $arrRow)
                    $volgorde = $arrRow["i_volgorde"];
	}

	
	
	if ($plaatjedeleted == 1)
	{
		//oude verwijderen
		if(is_file($local_sitemanagerimages.$plaatjegroot))
			if(unlink($local_sitemanagerimages.$plaatjegroot) == false)
				echo "kan bestand $plaatjegroot niet verwijderen";
		if(is_file($local_sitemanagerimages.$plaatjeklein))
			if(unlink($local_sitemanagerimages.$plaatjeklein) == false)
				echo "kan bestand $plaatjeklein niet verwijderen";
		
	}

	
	if(($tempplaatjegroot != null) && ($tempplaatjeklein != null)) //als er nieuwe plaatjes zijn, dan oude vervangen
	{
		//oude verwijderen
		if(is_file($local_sitemanagerimages.$plaatjegroot))
			if(unlink($local_sitemanagerimages.$plaatjegroot) == false)
				echo "kan bestand $plaatjegroot niet verwijderen";
		if(is_file($local_sitemanagerimages.$plaatjeklein))
			if(unlink($local_sitemanagerimages.$plaatjeklein) == false)
				echo "kan bestand $plaatjeklein niet verwijderen";
		
		//nieuwe kopieren vanuit de temp directory naar echte lokatie
		$plaatjegroot = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjegroot), "weblink_groot", $id);
		$plaatjeklein = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjeklein), "weblink_klein", $id);
			
		if (rename($local_sitemanagerimagestemp.$tempplaatjegroot, $local_sitemanagerimages.$plaatjegroot) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjegroot naar $sNewImageNameGroot";
		if (rename($local_sitemanagerimagestemp.$tempplaatjeklein, $local_sitemanagerimages.$plaatjeklein) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjeklein naar $sNewImageNameKlein";		
			
		//grote afbeelding resizen als nodig		
		checkSizeAndResize($local_sitemanagerimages.$plaatjegroot, $materiaalimagegrootwidth, $materiaalimagegrootheight, $materiaalimagegrootquality);
			
	}

       
    
	$arrVar = array("i_id","s_link", "s_omschrijving","b_checkbacklink", "s_backlink", "s_emaileigenaar", "i_volgorde", 's_plaatjeurl', "s_plaatjeurlklein", "b_opwebsite", 'b_emailadresbevestigd', 'i_datechanged', 'b_nofollow', 's_opmerkingen', 's_linktnaaronsdomein', 's_hreftitleattribute', 'i_siteid');
	$arrVal = array($id, $edtLink, $edtOmschrijving, $checkbacklink, $edtBacklink, $edtEmailEigenaar, $volgorde, $plaatjegroot, $plaatjeklein, $opwebsite, $emailadresbevestigd, time(), $chkNofollow, $edtOpmerkingen, $edtLinktNaarOnsDomein, $edtHrefTitleAttribute, $edtSiteID);


	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php
				if ($bNieuwRecord)
				{
					 addRecordDB($arrVar, $arrVal, $tblWeblinks);
					 echo "item toegevoegd";
				}
				else
				{
					changeRecordDB($arrVar, $arrVal, $tblWeblinks, "i_id", $id);
					echo "item gewijzigd";
				}
				?></td>
			</tr>
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="20" height="0"></td>
				<td>
					<input type="button" name="btnTerug" onClick="location.href='materiaalindex.php'" value="  << terug  "></A>
				</td>
			</tr>
		</table>
		</center>
	<?
	
	
?>
<br>
<br>


			

<?php include_once($local_sitemanageradmin."openpage.php"); ?>
