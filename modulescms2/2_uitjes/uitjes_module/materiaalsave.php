<?php 	//	error_reporting(E_ALL);
   // ini_set('display_errors', '1');
   ?>
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
            $id = getDBValuePlus1("i_id", $tblUitjes);
	}
	else
	{
            $sSql = "SELECT i_volgorde FROM $tblUitjes WHERE i_id = $id";
            $arrResult = mysqliToArray($sSql);
            foreach($arrResult as $arrRow)                   
                $volgorde = $arrRow["i_volgorde"];
	}



       
        //plaatsen updaten
        $iCountPlaatsen = $edtTotalCountPlaatsen;
        {   
            //alle plaatsen langslopen
            for ($iPlaatsIndex = 0; $iPlaatsIndex < $iCountPlaatsen; $iPlaatsIndex++)
            {
                //check if already exists in db
                $arrRecExist = mysqliToArray("SELECT i_uitjesid FROM $tblPlaatsenUitjes WHERE i_plaatsenid = '".$edtPlaatsID[$iPlaatsIndex]."' AND i_uitjesid = '$id'");
                //echo 'chkplaatsopwebsite:'.$chkPlaatsOpwebsite[$iPlaatsIndex];

                $chkVarName = 'chkPlaatsOpwebsite'.$iPlaatsIndex;                
                if (isset($$chkVarName)) //if not exists you can't change or add it
                {
                    //wegschrijven
                    $arrFieldsPlaatsenUitjes = array('i_plaatsenid', 'i_uitjesid', 's_tekst', 'b_opwebsite');
                    $arrValuesPlaatsenUitjes = array($edtPlaatsID[$iPlaatsIndex], $id, $edtPlaatsTekst[$iPlaatsIndex], $$chkVarName);

                    if ($arrRecExist)
                    {
                        changeRecordDB($arrFieldsPlaatsenUitjes, $arrValuesPlaatsenUitjes, $tblPlaatsenUitjes, "i_uitjesid", $id, 'i_plaatsenid', $edtPlaatsID[$iPlaatsIndex]);
                    }
                    else
                        addRecordDB($arrFieldsPlaatsenUitjes, $arrValuesPlaatsenUitjes, $tblPlaatsenUitjes);
                }
  
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
                $sTempImgName = 'uitjes_'.generatePrettyURLSafeURL(filterSQLInjection($edtOnderwerp)).'-bedrijfsuitje-organiseren';
		$plaatjegroot = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjegroot), $sTempImgName."_big", $id);
		$plaatjeklein = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjeklein), $sTempImgName."_small", $id);
			
		if (rename($local_sitemanagerimagestemp.$tempplaatjegroot, $local_sitemanagerimages.$plaatjegroot) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjegroot naar $sNewImageNameGroot";
		if (rename($local_sitemanagerimagestemp.$tempplaatjeklein, $local_sitemanagerimages.$plaatjeklein) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjeklein naar $sNewImageNameKlein";		
			
		//grote afbeelding resizen als nodig		
		checkSizeAndResize($local_sitemanagerimages.$plaatjegroot, $uitjesimagegrootwidth, $uitjesimagegrootheight, $uitjesimagegrootquality);
			
	}

	//$edtOnderwerp omzetten naar $sPrettyUrlTitle (pretty url titel waarop gezocht kan worden middels pretty urls)
	if (strlen(trim($edtPrettyUrlTitle)) > 0) //als opgegeven, dan die pakken
		$sPrettyUrlTitle = filterSQLInjection($edtPrettyUrlTitle);
	else //anders zelf genereren
	{
		$sPrettyUrlTitle = generatePrettyURLSafeURL(filterSQLInjection($edtOnderwerp));
		echo '<br>auto generated a pretty url: '.$sPrettyUrlTitle.'<br>';
	}


	$arrVar = array("i_id","s_tekst", "s_onderwerp","b_opwebsite", "i_volgorde", "s_plaatjeurl", "s_plaatjeurlklein", 's_prettyurltitle', 'i_inclusiefdiner', 'i_siteid', 'i_categoryid', 's_tekst_alt', 's_htmltitle', 's_htmltitle_alt', 's_htmldescription', 's_htmldescription_alt', 's_onderwerp_short', 's_fotoboekdir', 's_rondes');
	$arrVal = array($id, $edtBody,  $edtOnderwerp, $opwebsite, $volgorde, $plaatjegroot, $plaatjeklein, $sPrettyUrlTitle, $chkInclusiefDiner, $edtSiteID, $edtUitjesCategoryID, $edtBodyAlt, $edtHTMLTitle, $edtHTMLTitleAlt, $edtHTMLDescription, $edtHTMLDescriptionAlt, $edtOnderwerpShort, $cbxFotoboekDir, $edtRondes);
   
	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php
				if ($bNieuwRecord)
				{
					 addRecordDB($arrVar, $arrVal, $tblUitjes);
					 echo "item toegevoegd";
				}
				else
				{
					changeRecordDB($arrVar, $arrVal, $tblUitjes, "i_id", $id);
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
