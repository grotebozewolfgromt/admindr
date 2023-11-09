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
            $id = getDBValuePlus1("i_id", $tblSoorten);
	}
	else
	{
            $sSql = "SELECT i_volgorde FROM $tblSoorten WHERE i_id = $id";
            $arrResult = mysqliToArray($sSql);
            foreach($arrResult as $arrRow)                   
                $volgorde = $arrRow["i_volgorde"];
	}

    
	//tags updaten
	deleteRecordDB($tblSoortenTags, 'i_soortenid', $id); //alle tags verwijderen
	$arrTags = explode("\n", $edtTags);
	for ($iTeller = 0; $iTeller < count($arrTags); $iTeller++) //stuk voor stuk weer toevoegen
	{	
                if (strlen(trim($arrTags[$iTeller]))> 0) //geen lege tags toevoegen
                {
                    $arrVar = array("s_tag","i_soortenid");
                    $arrVal = array(trim($arrTags[$iTeller]), $id);
                    addRecordDB($arrVar, $arrVal, $tblSoortenTags);
                }
	}
        
        
        //plaatsen updaten
        $iCountPlaatsen = $edtTotalCountPlaatsen;
        {   
            //alle plaatsen langslopen
            for ($iPlaatsIndex = 0; $iPlaatsIndex < $iCountPlaatsen; $iPlaatsIndex++)
            {
                //check if already exists in db
                $arrRecExist = mysqliToArray("SELECT i_soortid FROM $tblPlaatsenSoorten WHERE i_plaatsenid = '".$edtPlaatsID[$iPlaatsIndex]."' AND i_soortid = '$id'");
                //echo 'chkplaatsopwebsite:'.$chkPlaatsOpwebsite[$iPlaatsIndex];
      
                
                //wegschrijven
                $chkVarName = 'chkPlaatsOpwebsite'.$iPlaatsIndex;
                $arrFieldsPlaatsenSoorten = array('i_plaatsenid', 'i_soortid', 's_tekst', 'b_opwebsite');
                $arrValuesPlaatsenSoorten = array($edtPlaatsID[$iPlaatsIndex], $id, $edtPlaatsTekst[$iPlaatsIndex], $$chkVarName);
                        
                if ($arrRecExist)
                {
                    changeRecordDB($arrFieldsPlaatsenSoorten, $arrValuesPlaatsenSoorten, $tblPlaatsenSoorten, "i_soortid", $id, 'i_plaatsenid', $edtPlaatsID[$iPlaatsIndex]);
                }
                else
                    addRecordDB($arrFieldsPlaatsenSoorten, $arrValuesPlaatsenSoorten, $tblPlaatsenSoorten);
            }
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
                $sTempImgName = 'gelegenheid_'.generatePrettyURLSafeURL(filterSQLInjection($edtSoort)).'-organiseren';
		$plaatjegroot = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjegroot), $sTempImgName."_big", $id);
		$plaatjeklein = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjeklein), $sTempImgName."_small", $id);
			
		if (rename($local_sitemanagerimagestemp.$tempplaatjegroot, $local_sitemanagerimages.$plaatjegroot) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjegroot naar $sNewImageNameGroot";
		if (rename($local_sitemanagerimagestemp.$tempplaatjeklein, $local_sitemanagerimages.$plaatjeklein) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjeklein naar $sNewImageNameKlein";		
			
		//grote afbeelding resizen als nodig		
		checkSizeAndResize($local_sitemanagerimages.$plaatjegroot, $gelegenheidimagegrootwidth, $gelegenheidimagegrootheight, $gelegenheidimagegrootquality);
			echo "resize: $gelegenheidimagegrootwidth x $gelegenheidimagegrootheight op $gelegenheidimagegrootquality % ";
	}

    
	//$edtPlaats omzetten naar $sPrettyUrlTitle (pretty url titel waarop gezocht kan worden middels pretty urls)
	if (strlen(trim($edtPrettyUrlTitle)) > 0) //als opgegeven, dan die pakken
		$sPrettyUrlTitle = filterSQLInjection($edtPrettyUrlTitle);
	else //anders zelf genereren 
	{
		$sPrettyUrlTitle = generatePrettyURLSafeURL(filterSQLInjection($edtSoort));
		echo '<br>auto generated a pretty url: '.$sPrettyUrlTitle.'<br>';
	}    
    
    
    
	$arrVar = array("i_id","s_soort","s_soort_short", "s_omschrijving", "s_omschrijving_alt", "b_opwebsite", "i_volgorde", "s_plaatjeurl", "s_plaatjeurlklein", "s_sourceimage", "s_prettyurltitle", 'b_belangrijk', 'i_siteid');
	$arrVal = array($id, $edtSoort, $edtSoortShort, $edtBody, $edtBodyAlt, $opwebsite, $volgorde, $plaatjegroot, $plaatjeklein, $edtSourceImage, $sPrettyUrlTitle, $belangrijk, $edtSiteID);


	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php
				if ($bNieuwRecord)
				{
					 addRecordDB($arrVar, $arrVal, $tblSoorten);
					 echo "item toegevoegd";
				}
				else
				{
					changeRecordDB($arrVar, $arrVal, $tblSoorten, "i_id", $id);
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
