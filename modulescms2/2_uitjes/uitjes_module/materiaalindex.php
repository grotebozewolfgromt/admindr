<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php

    if (isset($_POST['btnKopieer']))
    {
        echo 'inhoud kopieren site '.$_POST['cbxKopieerVan'].' naar site '.$_POST['cbxKopieerNaar'].'<br>';
        
        $arrTable = mysqliToArray("SELECT * FROM $tblUitjes WHERE i_siteid = ".$_POST['cbxKopieerVan']); //===> TABEL AANPASSEN 
        
        foreach ($arrTable as $arrRecord)
        {
            $iNewID = getDBValuePlus1("i_id", $tblUitjes);//===> TABEL AANPASSEN 
            $iNewVolgorde = getDBValuePlus1("i_volgorde", $tblUitjes);//===> TABEL AANPASSEN 

            $arrVar = array();
            $arrVal = array();
            $arrFields = array_keys($arrRecord);
            foreach ($arrFields as $sField) //alle velden langslopen
            {
                if (!is_numeric($sField ))//rarigheidje in mysqlToArray die ook indexnummers teruggeeft ipv alleen kolomnamen
                {
                    //fieldname overnemen
                    $arrVar[] = $sField;

                    //waardes overnemen (met uitzonderingen voor id, volgorde en site)
                    if ($sField == 'i_id')
                        $arrVal[] = $iNewID;
                    elseif ($sField == 'i_volgorde') 
                        $arrVal[] = $iNewVolgorde;
                    elseif ($sField == 'i_siteid') 
                        $arrVal[] = $_POST['cbxKopieerNaar'];
                    else
                        $arrVal[] = $arrRecord[$sField];
                }
            }

            addRecordDB($arrVar, $arrVal, $tblUitjes);//===> TABEL AANPASSEN 
            echo 'record gekopieerd naar '.$iNewID.' <br>';
        }
	
        echo '<hr>';
    }
    


	if ($_GET['delete'] == 1)
	{
            deleteRecordDBInclImg($tblUitjes, "i_id", $_GET['id'], $local_sitemanagerimages);
	}

	if ($_GET['changeorder'] == 1)
            changeRecordOrderDB($tblUitjes, "i_volgorde", "i_id", $_GET['id'], $up);


	$sSql = "SELECT $tblUitjes.*, $tblWebsites.s_domein FROM $tblUitjes, $tblWebsites WHERE $tblUitjes.i_siteid = $tblWebsites.i_id AND i_siteid = ".$_SESSION['iSelectedSiteID']." ORDER BY i_volgorde ASC";
	$arrResult = mysqliToArray($sSql);
            
        
             
?>

<b>Uitjes van geselecteerde website</b>				
<TABLE border="0" cellpadding="2" cellspacing="0">
  <?php
    $iTeller = 0;
    foreach($arrResult as $row)
    {
        $iTeller++;
        ?>
        <TR <?php echo getRowColor() ?>> 
            <TD> <?php echo $row["s_onderwerp"]?> </TD>
            <TD> <?php echo $row["s_htmltitle"]?> </TD>
            <TD> <?php echo $row["s_prettyurltitle"]?> </TD>   
            <TD> <b><?php echo $row["s_domein"]?></b> </TD> 
            <TD> 
            <?php 
                if ($row["b_opwebsite"])
                    echo "op website";
                else
                    echo "niet op website";
            ?> 
            </TD>
            <TD><a href="materiaalindex.php?changeorder=1&up=0&id=<?php echo $row["i_id"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>down.gif" width="14" height="15" border="0" alt="SCHUIF NAAR BOVEN : <?php echo $row["s_onderwerp"]?>"></a></TD>
            <TD><a href="materiaalindex.php?changeorder=1&up=1&id=<?php echo $row["i_id"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>up.gif" width="14" height="15" border="0" alt="SCHUIF NAAR BENEDEN : <?php echo $row["s_onderwerp"]?>"></a></TD>
            <TD><a href="materiaaldetail.php?id=<?php echo $row["i_id"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>update.gif" border="0" alt="WIJZIGEN : <?php echo $row["s_onderwerp"]?>"></a></TD>
            <TD> <a href="javascript:confirmDeleteRecord('<?php echo "materiaalindex.php?delete=1&id=".$row["i_id"]; ?>', '<?php echo $row["s_onderwerp"]?>')"><img src="<?php echo $www_sitemanageradminimages ?>del.gif" border="0" alt="VERWIJDEREN : <?php echo $row["s_onderwerp"]?>"></a></TD>
        </TR>
        <?php
            }
	?>
</TABLE>
        <?php 
            if ($iTeller == 0)
                echo "<CENTER>[momenteel geen items]</CENTER><BR>";						
        ?>
<BR>
<center><input type="button" name="toevoegen" onClick="location.href='materiaaldetail.php'" value="toevoegen"></center>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<hr>
<form method="post" action="materiaalindex.php">
    Kopieer alle items van
    	<select name="cbxKopieerVan" >
		<?php 
			$arrSites = mysqliToArray("SELECT * FROM $tblWebsites ORDER BY i_id");
			foreach ($arrSites as $arrSite)
			{
				echo '<option value="'.$arrSite['i_id'].'">'.$arrSite['s_domein'].'</option>';
			}
		?>
	</select>
    naar
    	<select name="cbxKopieerNaar">
		<?php 
			$arrSites = mysqliToArray("SELECT * FROM $tblWebsites ORDER BY i_id");
			foreach ($arrSites as $arrSite)
			{
				$sSelected = '';
				if ($arrSite['i_id'] == $_SESSION['iSelectedSiteID'])
					$sSelected = ' selected';                            
				echo '<option value="'.$arrSite['i_id'].'"'.$sSelected.'>'.$arrSite['s_domein'].'</option>';
			}
		?>
	</select>
    <input type="submit" name="btnKopieer" value="========= KOPIEER IK WEET HET ZEKER ========">
</form>

<?php include_once($local_sitemanageradmin."openpage.php"); ?>
