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
            $id = getDBValuePlus1("i_id", $tblAdressen);
	}
    
    
    
	$arrVar = array("i_id", 's_bedrijfsnaam', 's_voorletters',"s_achternaam", 's_emailadres', "s_opmerkingen", "s_straat", 's_postcode', 's_plaats', 'b_geslacht', 's_telefoonnr', 'b_opmailinglist');
	$arrVal = array($id, $edtBedrijfsnaam, $edtVoorletters, $edtAchternaam, $edtEmailadres, $edtOpmerkingen, $edtStraat, $edtPostcode, $edtPlaats, $cbxGeslacht, $edtTelefoon, $opmailinglist);


	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php
				if ($bNieuwRecord)
				{
					addRecordDB($arrVar, $arrVal, $tblAdressen);
					echo "item toegevoegd";
				}
				else
				{
					changeRecordDB($arrVar, $arrVal, $tblAdressen, "i_id", $id);
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
