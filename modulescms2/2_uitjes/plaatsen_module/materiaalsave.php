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
            $id = getDBValuePlus1("i_id", $tblPlaatsen);
            $volgorde = getDBValuePlus1("i_volgorde", $tblPlaatsen);
	}
	else
	{
            $sSql = "SELECT i_volgorde FROM $tblPlaatsen WHERE i_id = $id";
            $arrResult = mysqliToArray($sSql);
            foreach($arrResult as $arrRow)                   
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
                $sTempImgName = 'plaats_regio-'.generatePrettyURLSafeURL(filterSQLInjection($edtPlaats));
		$plaatjegroot = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjegroot), $sTempImgName."_big", $id);
		$plaatjeklein = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjeklein), $sTempImgName."_small", $id);
			
		if (rename($local_sitemanagerimagestemp.$tempplaatjegroot, $local_sitemanagerimages.$plaatjegroot) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjegroot naar $sNewImageNameGroot";
		if (rename($local_sitemanagerimagestemp.$tempplaatjeklein, $local_sitemanagerimages.$plaatjeklein) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjeklein naar $sNewImageNameKlein";		
			
		//grote afbeelding resizen als nodig		
		checkSizeAndResize($local_sitemanagerimages.$plaatjegroot, $plaatsenimagegrootwidth, $plaatsenimagegrootheight, $plaatsenimagegrootquality);
			
	}

    
	//$edtPlaats omzetten naar $sPrettyUrlTitle (pretty url titel waarop gezocht kan worden middels pretty urls)
	if (strlen(trim($edtPrettyUrlTitle)) > 0) //als opgegeven, dan die pakken
		$sPrettyUrlTitle = filterSQLInjection($edtPrettyUrlTitle);
	else //anders zelf genereren 
	{
		$sPrettyUrlTitle = generatePrettyURLSafeURL(filterSQLInjection($edtPlaats));
		echo '<br>auto generated a pretty url: '.$sPrettyUrlTitle.'<br>';
	}    
    
    
        //dubbele plaatsen filteren    
        $edtPlaatsenInDeBuurt = trim($edtPlaatsenInDeBuurt);
        $arrPIDB = explode("\n", $edtPlaatsenInDeBuurt);
        $arrPIDB = array_unique($arrPIDB); //dubbele eruit
        $arrPIDBNoEmptyEl = array();
        foreach ($arrPIDB as $sElement)//lege eruit + sturingskarakters
        {
            if (trim($sElement) != "")
                $arrPIDBNoEmptyEl[] = trim($sElement);//gelijk alle sturingskarakters er ook uit
        }        
        asort($arrPIDBNoEmptyEl);//gelijk even sorteren
        $edtPlaatsenInDeBuurt = implode("\n", $arrPIDBNoEmptyEl);//weer aan elkaar plakken
        
        
	$arrVar = array("i_id","s_plaats", "s_provincie", "s_omschrijving", "s_omschrijving_alt", "b_opwebsite", "i_volgorde", "s_plaatjeurl", "s_plaatjeurlklein", "s_sourceimage", "s_prettyurltitle", "b_isopeigenlocatie", "i_siteid", "s_plaatsenindebuurt");
	$arrVal = array($id, $edtPlaats, $edtProvincie, $edtBody, $edtBodyAlt, $opwebsite, $volgorde, $plaatjegroot, $plaatjeklein, $edtSourceImage, $sPrettyUrlTitle, $chkIsOpEigenLocatie, $edtSiteID, $edtPlaatsenInDeBuurt);


	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php
				if ($bNieuwRecord)
				{
					 addRecordDB($arrVar, $arrVal, $tblPlaatsen);
					 echo "item toegevoegd";
				}
				else
				{
					changeRecordDB($arrVar, $arrVal, $tblPlaatsen, "i_id", $id);
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
