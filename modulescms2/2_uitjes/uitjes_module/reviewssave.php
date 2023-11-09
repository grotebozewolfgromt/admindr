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
            $id = getDBValuePlus1("i_id", $tblUitjesReviews);
	}	
	



	$arrVar = array("i_id","s_name", "s_email","i_uitjescategoryid","i_numberofstars", "s_reviewtext", "i_timestampdatumuitje", "i_timestamp", "s_ip", "b_opwebsite", "s_plaats");
	$arrVal = array($id, $edtNaam, $edtEmail, $cbxUitjesID, $edtNumberOfStars, $edtReviewText, mktime(0, 0,0,$edtMaand, $edtDag, $edtJaar), time(), $edtIP, $opwebsite, $edtPlaats);

         
        
	?>

		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php
				if ($bNieuwRecord)
				{
                                    addRecordDB($arrVar, $arrVal, $tblUitjesReviews);
                                    echo "item toegevoegd";
				}
				else
				{
                                    changeRecordDB($arrVar, $arrVal, $tblUitjesReviews, "i_id", $id);
                                    echo "item gewijzigd";
				}
				?></td>
			</tr>
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="20" height="0"></td>
				<td>
					<input type="button" name="btnTerug" onClick="location.href='reviewsindex.php'" value="  << terug  "></A>
				</td>
			</tr>
		</table>
		</center>
	<?
	
	
?>
<br>
<br>


			

<?php include_once($local_sitemanageradmin."openpage.php"); ?>
