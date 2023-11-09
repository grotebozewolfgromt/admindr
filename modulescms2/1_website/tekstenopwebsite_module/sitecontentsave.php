<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php
        //even lomp alle POST variabelen overnemen
	foreach(array_keys($_POST) as $sKey)
            $$sKey = $_POST[$sKey];
        
        if (is_numeric($_GET['id']))
            $id = $_GET['id'];


        $bNieuwRecord = (!is_numeric($_GET['id']));

	if ($bNieuwRecord)
	{
            //als nieuw, dan nieuwe id en volgorde maken
            $id = getDBValuePlus1("i_id", $tblSitecontent);
	}


	if ($plaatjedeleted == 1)
	{
		//oude verwijderen
		if(is_file($local_sitemanagerimages.$plaatjegroot))
			if(unlink($local_sitemanagerimages.$plaatjegroot) == false)
				echo "kan bestand $plaatjegroot niet verwijderen";		
		//oude verwijderen
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
		//oude verwijderen
		if(is_file($local_sitemanagerimages.$plaatjeklein))
			if(unlink($local_sitemanagerimages.$plaatjeklein) == false)
				echo "kan bestand $plaatjeklein niet verwijderen";
		
		//nieuwe kopieren vanuit de temp directory naar echte lokatie
		$plaatjegroot = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjegroot), 'content-'.generatePrettyURLSafeURL($edtTitel).'_big', $id);
		$plaatjeklein = deliverFileName($local_sitemanagerimages, getExtension($local_sitemanagerimagestemp.$tempplaatjeklein), 'content-'.generatePrettyURLSafeURL($edtTitel).'_small', $id);

		if (rename($local_sitemanagerimagestemp.$tempplaatjegroot, $local_sitemanagerimages.$plaatjegroot) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjegroot naar $sNewImageNameGroot";
		if (rename($local_sitemanagerimagestemp.$tempplaatjeklein, $local_sitemanagerimages.$plaatjeklein) == false)
			echo "problemen bij het verplaatsen van bestand $local_sitemanagerimagestemp$tempplaatjeklein naar $sNewImageNameKlein";

	}


	if ($edtPagina == '')
		echo 'LETOP: bestandsnaam leeg, item wel toegevoegd';
	$edtPagina = generatePrettyURLSafeURL($edtPagina);



        $arrVar = array("i_id","s_tekst", "s_tekst_alt", "s_omschrijving", "s_titel", "s_titel_alt", "s_pagina", "s_htmltitle", "s_htmltitle_alt", "s_htmldescription","s_htmldescription_alt", "s_plaatjeurl", "s_plaatjeurlklein", "i_siteid");
        $arrVal = array($id, $edtTekst, $edtTekstAlt, $edtOmschrijving, $edtTitel, $edtTitelAlt, $edtPagina, $edtHtmlTitle, $edtHtmlTitleAlt, $edtHtmlDescription, $edtHtmlDescriptionAlt, $plaatjegroot, $plaatjeklein, $edtSiteID);


	
	

	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php

                                    if ($bNieuwRecord)
                                    {
                                             addRecordDB($arrVar, $arrVal, $tblSitecontent);
                                             echo "item toegevoegd";
                                    }
                                    else
                                    {
                                            changeRecordDB($arrVar, $arrVal, $tblSitecontent, "i_id", $id);
                                            echo "item gewijzigd";
                                    }

				?></td>
			</tr>
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="20" height="0"></td>
				<td>
					<input type="button" name="btnTerug" onClick="location.href='sitecontentindex.php'" value="  << terug  "></A>
				</td>
			</tr>
		</table>
		</center>
	<?
	
	
?>
<br>
<br>


			

<?php include_once($local_sitemanageradmin."openpage.php"); ?>