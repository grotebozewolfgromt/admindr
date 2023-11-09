<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php
        //even lomp alle POST variabelen overnemen
	foreach(array_keys($_POST) as $sKey)
            $$sKey = $_POST[$sKey];
	

        
	?>
		<center>
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td><IMG src="<?php echo $www_sitemanageradminimages ?>information.gif"><IMG src="<?php echo $www_sitemanageradminimages ?>transparantpixel.gif" width="10" height="0"></td>
				<td><?php

                                    for ($iTeller = 0; $iTeller < count($edtLink); $iTeller++)
                                    {

                                        //nieuwe id en volgorde maken
                                        $id = getDBValuePlus1("i_id", $tblWeblinks);
                                        $volgorde = getDBValuePlus1("i_volgorde", $tblWeblinks);


                                        
                                        if (strlen($edtLink[$iTeller]) > 0)
                                        {
                                            $arrVar = array("i_id","s_link", "s_omschrijving","b_checkbacklink", "s_backlink", "i_volgorde", "b_opwebsite", 'i_datechanged', 's_linktnaaronsdomein', 'i_siteid');
                                            $arrVal = array($id, $edtLink[$iTeller], $edtOmschrijving[$iTeller], $checkbacklink[$iTeller], $edtBacklink[$iTeller], $volgorde, '1', time(), $edtLinktNaarOnsDomein[$iTeller], $edtSiteID[$iTeller]);

                                            if ($edtLink[$iTeller] != '')
                                            {
                                                addRecordDB($arrVar, $arrVal, $tblWeblinks);
                                                echo $edtLink[$iTeller]." toegevoegd <br>";
                                            }
                                        }
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
