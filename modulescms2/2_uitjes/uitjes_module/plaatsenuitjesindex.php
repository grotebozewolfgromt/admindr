<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php

	if ($_GET['delete'] == 1)
		deleteRecordDB($tblUitjesCategories, "i_id", $_GET['id'], $local_sitemanagerimages); 



	$sSql = "SELECT $tblPlaatsenUitjes.*, $tblUitjes.s_onderwerp, $tblPlaatsen.s_plaats FROM $tblPlaatsenUitjes, $tblUitjes, $tblPlaatsen WHERE $tblPlaatsenUitjes.i_plaatsenid = $tblPlaatsen.i_id AND $tblPlaatsenUitjes.i_uitjesid = $tblUitjes.i_id AND $tblUitjes.i_siteid = ".$_SESSION['iSelectedSiteID']." ORDER BY s_onderwerp";
	$arrResult = mysqliToArray($sSql);
        
             
?>

<b> Uitjes plaatsen van geselecteerde site </b>				
Verwijderen items bij uitjes
<TABLE border="0" cellpadding="2" cellspacing="0">
    <?php
    $iTeller = 0;
    foreach ($arrResult as $row) 
    {
        $iTeller++;
        ?>
        <TR <?php echo getRowColor() ?>> 
            <TD> <?php echo $row["s_onderwerp"]?> </TD>
            <TD> <?php echo $row["s_plaats"]?> </TD>
            <TD><a href="plaatsenuitjesdetail.php?plaatsenid=<?php echo $row["i_plaatsenid"]; ?>&uitjesid=<?php echo $row["i_uitjesid"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>update.gif" border="0" alt="WIJZIGEN : <?php echo $row["s_onderwerp"]?>"></a></TD>
            <!--<TD> <a href="javascript:confirmDeleteRecord('<?php echo "catindex.php?delete=1&id=".$row["i_id"]; ?>', '<?php echo $row["s_onderwerp"]?>')"><img src="<?php echo $www_sitemanageradminimages ?>del.gif" border="0" alt="VERWIJDEREN : <?php echo $row["s_onderwerp"]?>"></a> </TD>-->
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
<!--<center><input type="button" name="toevoegen" onClick="location.href='catdetail.php'" value="toevoegen"></center>-->

<?php include_once($local_sitemanageradmin."openpage.php"); ?>
