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
        $id = getDBValuePlus1("i_id", $tblUitjesCategories);
    }



    //tags updaten
    deleteRecordDB($tblUitjesTags, 'i_materialid', $id); //alle tags verwijderen
    $arrTags = explode("\n", $edtTags);
    for ($iTeller = 0; $iTeller < count($arrTags); $iTeller++) //stuk voor stuk weer toevoegen
    {	
        if (strlen(trim($arrTags[$iTeller]))> 0) //geen lege tags toevoegen
        {
            $arrVar = array("s_tag","i_materialid");
            $arrVal = array(trim($arrTags[$iTeller]), $id);
            addRecordDB($arrVar, $arrVal, $tblUitjesTags);
        }
    }

    
    

    //soorten updaten
    deleteRecordDB($tblSoortenUitjes, 'i_uitjescategoryid', $id); //alle plaatsen eerst verwijderen
    for ($iTeller = 0; $iTeller < count($chkSoort); $iTeller++) //stuk voor stuk weer toevoegen
    {	

            $arrVar = array("i_soortenid","i_uitjescategoryid");
            $arrVal = array($chkSoort[$iTeller], $id);
            addRecordDB($arrVar, $arrVal, $tblSoortenUitjes);
    }    

    


	$arrVar = array("i_id","s_onderwerp",  's_duur', 's_vanafprijs','i_vanafprijsaantalpersonen', 'i_vanafaantalpersonen', 's_inclusief', 's_inclusief_alt', 's_optioneel','s_optioneel_alt', 's_offertetekst', 'b_aanbieding', 'b_belangrijk', 's_youtubeid', 's_urlfotos', 's_inhoud', 's_inhoud_alt',  's_planning','s_planning_alt', 's_prijsbox1title', 's_prijsbox1', 's_prijsbox1_alt', 's_prijsbox2title', 's_prijsbox2', 's_prijsbox2_alt');
	$arrVal = array($id, $edtOnderwerp,  $edtDuur, $edtVanafPrijs, $edtVanafPrijsAantalPersonen,  $edtVanafAantalPersonen, $edtInclusief, $edtInclusiefAlt, $edtOptioneel, $edtOptioneelAlt, $edtOfferteTekst, $chkAanbieding, $chkBelangrijk, $edtYoutubeID, $edtURLFotos, $edtInhoud, $edtInhoudAlt, $edtPlanning ,$edtPlanningAlt , $edtPrijsbox1title, $edtPrijsbox1, $edtPrijsbox1alt, $edtPrijsbox2title, $edtPrijsbox2, $edtPrijsbox2alt);
   
	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php
				if ($bNieuwRecord)
				{
					 addRecordDB($arrVar, $arrVal, $tblUitjesCategories);
					 echo "item toegevoegd";
				}
				else
				{
					changeRecordDB($arrVar, $arrVal, $tblUitjesCategories, "i_id", $id);
					echo "item gewijzigd";
				}
				?></td>
			</tr>
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="20" height="0"></td>
				<td>
					<input type="button" name="btnTerug" onClick="location.href='catindex.php'" value="  << terug  "></A>
				</td>
			</tr>
		</table>
		</center>
	<?
	
	
?>
<br>
<br>


			

<?php include_once($local_sitemanageradmin."openpage.php"); ?>
