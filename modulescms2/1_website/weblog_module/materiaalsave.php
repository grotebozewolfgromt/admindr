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
	

        //======update eol date ONLY if eol state has changed
        $arrEOL = mysqliToArray('SELECT b_eol, i_eoldate FROM '.$tblWeblog.' WHERE i_id = '.$id);
        foreach($arrEOL as $arrEOLItem)
        {
            $bOldEOLState = $arrEOLItem['b_eol'];
            $iOldEOLDate = $arrEOLItem['i_eoldate'];
        }        
        
        $iEOLDate = $iOldEOLDate;//default        
        if ($chkEol) //update if now end of life
        {                     
            if ($bOldEOLState == 0) //oud = niet EOL, nieuw = wel EOL
            {
                $iEOLDate = time();
            }
        }
        //======END: update eol date 
        
        
        
	/** ondervangen categorie id geldig **/
/*	if (!is_numeric($edtParentID))
		$edtParentID = 0;
	if ($edtParentID < 1)
	{
		$edtParentID = 1;
		echo '<b>Categorie ID NIET opgegeven, categorie ID 1 will be assumed</b>';
	}
	*/
		
        
        //cleaning up the shit from the html editor
        $edtBody = str_replace('<div>', '<br><div>', $edtBody); //it starts a div for a new line. this div (along with it's closing tag, that ALSO NEEDS TO FILTERED)  in the next line
        $edtBody = str_replace('<p', '<br><p', $edtBody); //it starts a p for a new line. this div (along with it's closing tag, that ALSO NEEDS TO FILTERED) in the next line
        $edtBody = strip_tags($edtBody, '<table><thead><th><tr><td><a><b><i><u><br><h1><h2><h3><h4><h5><h6><ul><ol><li>');
        
        

	//$edtPlaats omzetten naar $sPrettyUrlTitle (pretty url titel waarop gezocht kan worden middels pretty urls)
	if (strlen(trim($edtPrettyUrlTitle)) > 0) //als opgegeven, dan die pakken
        {
		$sPrettyUrlTitle = generatePrettyURLSafeURL(filterSQLInjection($edtPrettyUrlTitle));
        }
	else //anders zelf genereren 
	{
		$sPrettyUrlTitle = generatePrettyURLSafeURL(filterSQLInjection($edtOnderwerp));
		echo '<br>auto generated a pretty url: '.$sPrettyUrlTitle.'<br>';
	}    
    
	
	if ($bNieuwRecord)
	{
		//als nieuw, dan nieuwe id en volgorde maken
		$id = getDBValuePlus1("i_id", $tblWeblog);
		$volgorde = getDBValuePlus1("i_volgorde", $tblWeblog);
	}
	else
	{
		$sSql = "SELECT i_volgorde FROM $tblWeblog WHERE i_id = $id";
		$arrResult = mysqliToArray($sSql);
                foreach ($arrResult as $arrRow)
                    $volgorde = $arrRow["i_volgorde"];
	}


	//tags updaten
	deleteRecordDB($tblWeblogTags, 'i_weblogid', $id); //alle tags verwijderen
	$arrTags = array_unique(explode("\n", $edtTags)); //array va nmaken en dubbele filteren
	for ($iTeller = 0; $iTeller < count($arrTags); $iTeller++) //stuk voor stuk weer toevoegen
	{	
		$arrVar = array("s_tag","i_weblogid");
		$arrVal = array(rtrim(ltrim($arrTags[$iTeller])), $id);
                if (rtrim(ltrim($arrTags[$iTeller])))
                    addRecordDB($arrVar, $arrVal, $tblWeblogTags);
	}
        //all-blog-tags checkboxen
	$arrAllBlogTags = array_unique($_POST['chkAllBlogTags']); //array va nmaken en dubbele filteren
//        var_dump($_POST);
	for ($iTeller = 0; $iTeller < count($arrAllBlogTags); $iTeller++) //stuk voor stuk weer toevoegen
	{	
		$arrVar = array("s_tag","i_weblogid");
		$arrVal = array(rtrim(ltrim($arrTags[$iTeller])), $id);
                if (rtrim(ltrim($arrTags[$iTeller])))
                    addRecordDB($arrVar, $arrVal, $tblWeblogTags);
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
		$plaatjegroot = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjegroot), "blog-".$sPrettyUrlTitle.'_big', $id);
		$plaatjeklein = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjeklein), "blog-".$sPrettyUrlTitle.'_small', $id);
			
		if (rename($local_sitemanagerimagestemp.$tempplaatjegroot, $local_sitemanagerimages.$plaatjegroot) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjegroot naar $sNewImageNameGroot";
		if (rename($local_sitemanagerimagestemp.$tempplaatjeklein, $local_sitemanagerimages.$plaatjeklein) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjeklein naar $sNewImageNameKlein";		
			
		//grote afbeelding resizen als nodig		
		checkSizeAndResize($local_sitemanagerimages.$plaatjegroot, $weblogimagegrootwidth, $weblogimagegrootheight, $weblogimagegrootquality);
			
	}



	$arrVar = array("i_id","s_tekst", "i_datum","s_onderwerp","b_opwebsite", "i_volgorde", "s_plaatjeurl", "s_plaatjeurlklein", "b_belangrijk", "s_sourceimage", 's_prettyurltitle', 's_youtubeurl', 's_transcription', "i_siteid", "b_autoreplandate", "b_eol", "i_eoldate");
	$arrVal = array($id, $edtBody, mktime($edtUur, $edtMinuut,0,$edtMaand, $edtDag, $edtJaar), $edtOnderwerp, $opwebsite, $volgorde, $plaatjegroot, $plaatjeklein, $chkBelangrijk, $edtSourceImage, $sPrettyUrlTitle, $edtYoutubeUrl , $edtTranscription, $edtSiteID,  $chkAutoReplanDate, $chkEol, $iEOLDate);


	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php
				if ($bNieuwRecord)
				{
                                    addRecordDB($arrVar, $arrVal, $tblWeblog);
                                    echo "item toegevoegd met id ".$id;
				}
				else
				{
                                    changeRecordDB($arrVar, $arrVal, $tblWeblog, "i_id", $id);
                                    echo "item met id $id gewijzigd";
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
