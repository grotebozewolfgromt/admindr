<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php
	
	if ($_GET['delete'] == 1)
		deleteRecordDB($tblUitjesReviews, "i_id", $_GET['id'], $local_sitemanagerimages);



	$sSql = "SELECT $tblUitjesReviews.*, $tblUitjesCategories.s_onderwerp AS s_uitjesname FROM $tblUitjesReviews, $tblUitjesCategories WHERE $tblUitjesReviews.i_uitjescategoryid = $tblUitjesCategories.i_id ORDER BY i_timestampdatumuitje ASC";
	$arrResult = mysqliToArray($sSql);
?>

				
<TABLE border="0" cellpadding="2" cellspacing="0">
  <?php
    $iTeller = 0;
    foreach ($arrResult as $row) 
    {
        $iTeller++;
	?>
            <TR <?php echo getRowColor() ?>> 
                <TD> <?php echo date("d-m-Y", $row["i_timestamp"])?> </TD>
                <TD> <?php echo date("d-m-Y", $row["i_timestampdatumuitje"])?> </TD>
                <TD> <?php echo $row["s_name"]?> </TD>    
                <TD> <?php echo $row["s_uitjesname"]?> </TD>
                <TD> <?php echo $row["s_plaats"]?> </TD>
                <TD> <?php
                    $iStars= $row["i_numberofstars"];
                    for ($iTeller = 0; $iTeller < $iStars; $iTeller++)
                    {
                        echo '*';
                    }
                    ?>      
                </TD>    
                <TD> 
                <?php 
                        if ($row["b_opwebsite"])
                                echo "op website";
                        else
                                echo "niet op website";
                ?> 
                </TD>
                <TD><a href="reviewsdetail.php?id=<?php echo $row["i_id"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>update.gif" border="0" alt="WIJZIGEN : <?php echo $row["s_title"]?>"></a></TD>
                <TD> <a href="javascript:confirmDeleteRecord('<?php echo "reviewsindex.php?delete=1&id=".$row["i_id"]; ?>', '<?php echo $row["s_name"].'-'.$row["s_uitjesname"] ?>')"><img src="<?php echo $www_sitemanageradminimages ?>del.gif" border="0" alt="VERWIJDEREN : <?php echo $row["s_name"].'-'.$row["s_uitjesname"] ?>"></a>   
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
<center><input type="button" name="toevoegen" onClick="location.href='reviewsdetail.php'" value="toevoegen"></center>

<?php include_once($local_sitemanageradmin."openpage.php"); ?>
