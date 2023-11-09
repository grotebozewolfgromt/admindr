<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
//die('henk5') ;
    if ($_GET['delete'] == 1)
            deleteRecordDB($tblAdressen, "i_id", $_GET['id'], $local_sitemanagerimages);


    $sSql = "SELECT * FROM $tblAdressen ORDER BY s_achternaam";

    $arrResult = mysqliToArray($sSql);

    //var_dump($arrResult);
?>

				
<TABLE border="0" cellpadding="2" cellspacing="0">
  <?php
    $iTeller = 0;
    foreach ($arrResult as $row) 
    {
        $iTeller++;
	?>
        <TR <?php echo getRowColor() ?>> 
            <TD> <?php echo $row["s_bedrijfsnaam"]?> </TD>
            <TD> <?php echo $row["s_voorletters"]?> </TD>
            <TD> <?php echo $row["s_achternaam"]?> </TD>
            <TD> <?php echo $row["s_straat"]?> </TD>
            <TD> <?php echo $row["s_postcode"]?> </TD>
            <TD> <?php echo $row["s_plaats"]?> </TD>
            <TD> <?php echo $row["s_emailadres"]?> </TD>  
            <TD> 
        <?php 
                if ($row["b_opmailinglist"])
                        echo "op mailinglist";
                else
                        echo "";
        ?> 
	</TD>     
    <TD><a href="materiaaldetail.php?id=<?php echo $row["i_id"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>update.gif" border="0" alt="WIJZIGEN : <?php echo $row["s_achternaam"]?>"></a></TD>
    <TD> <a href="javascript:confirmDeleteRecord('<?php echo "materiaalindex.php?delete=1&id=".$row["i_id"]; ?>', '<?php echo $row["s_bedrijfsnaam"]?> <?php echo $row["s_achternaam"]?>')"><img src="<?php echo $www_sitemanageradminimages ?>del.gif" border="0" alt="VERWIJDEREN : <?php echo $row["s_achternaam"]?>"></a> 
    </TD>
  </TR>
  <?
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
<hr>
<b>Mailing versturen</b><br>
<?php
        $arrOpmailinglist = array();
        $arrNietOpmailinglist = array();
	$sSql = "SELECT * FROM $tblAdressen ORDER BY s_achternaam";
	$arrResult = mysqliToArray($sSql);
        
        foreach ($arrResult as $row) 
        {
            if ($row['b_opmailinglist'] == '1')
                $arrOpmailinglist[] = $row["s_voorletters"].','.$row["s_achternaam"].','.$row["s_emailadres"];
            else
                $arrNietOpmailinglist[] = $row["s_voorletters"].','.$row["s_achternaam"].','.$row["s_emailadres"];
                
        }
?>
<br>
<b>op mailinglist (CSV formaat)</b><br>
<textarea name="edtMailinlist" cols="100" rows="10"><?php echo implode($arrOpmailinglist, "\n"); ?></textarea><br>
<b>overige, niet op mailinglist (CSV formaat)</b><br>
<textarea name="edtNietMailinglist" cols="100" rows="10"><?php echo implode($arrNietOpmailinglist, "\n"); ?></textarea> 




<?php include_once($local_sitemanageradmin."openpage.php"); ?>
