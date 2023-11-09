<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php

        //even lomp alle POST variabelen overnemen
	foreach(array_keys($_POST) as $sKey)
            $$sKey = $_POST[$sKey];
        
        if (is_numeric($_GET['plaatsenid']))
            $plaatsenid = $_GET['plaatsenid'];
        else
            $plaatsenid = '';
        
        if (is_numeric($_GET['uitjesid']))
            $uitjesid = $_GET['uitjesid'];
        else
            $uitjesid = '';
          
        
        
        $bNieuwRecord = ( (!is_numeric($_GET['plaatsenid'])) || (!is_numeric($_GET['uitjesid'])) );
	

        
//	if ($bNieuwRecord)
//	{
//            //als nieuw, dan nieuwe id en volgorde maken
//            $id = getDBValuePlus1("i_id", $tblPlaatsenUitjes);
//	}
        
        

	

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
                $sTempImgName = 'uitjesplaatsen_'.$prettyurluitje.'-in-'.$prettyurlplaats.'-en-omgeving';
		$plaatjegroot = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjegroot), $sTempImgName."_big", $plaatsenid.'-'.$uitjesid);
		$plaatjeklein = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjeklein), $sTempImgName."_small", $plaatsenid.'-'.$uitjesid);
			
		if (rename($local_sitemanagerimagestemp.$tempplaatjegroot, $local_sitemanagerimages.$plaatjegroot) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjegroot naar $sNewImageNameGroot";
		if (rename($local_sitemanagerimagestemp.$tempplaatjeklein, $local_sitemanagerimages.$plaatjeklein) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjeklein naar $sNewImageNameKlein";		
			
		//grote afbeelding resizen als nodig		
		checkSizeAndResize($local_sitemanagerimages.$plaatjegroot, $uitjesplaatsenimagegrootwidth, $uitjesplaatsenimagegrootheight, $uitjesplaatsenimagegrootquality);
			
	}   
    
    


	$arrVar = array("i_plaatsenid","i_uitjesid",  "s_tekst",'b_opwebsite',  's_plaatjegroot', 's_plaatjeklein');
	$arrVal = array($plaatsenid, $uitjesid,  $edtTekst, $chkOpWebsite, $plaatjegroot, $plaatjeklein);
   
	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php
				if ($bNieuwRecord)
				{
                                    addRecordDB($arrVar, $arrVal, $tblPlaatsenUitjes);
                                    echo "item toegevoegd";
				}
				else
				{
                                    changeRecordDB($arrVar, $arrVal, $tblPlaatsenUitjes, "i_plaatsenid", $plaatsenid, "i_uitjesid", $uitjesid);
                                    echo "item gewijzigd";
				}
				?></td>
			</tr>
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="20" height="0"></td>
				<td>
					<input type="button" name="btnTerug" onClick="location.href='plaatsenuitjesindex.php'" value="  << terug  "></A>
				</td>
			</tr>
		</table>
		</center>
	<?
	
	
?>
<br>
<br>


			

<?php include_once($local_sitemanageradmin."openpage.php"); ?>
