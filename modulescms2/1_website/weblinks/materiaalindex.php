<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php

    if ($_GET['delete'] == 1)
	deleteRecordDBInclImg($tblWeblinks, "i_id", $_GET['id'], $local_sitemanagerimages);


    if ($_GET['changeorder'] == 1)
        changeRecordOrderDB($tblWeblinks, "i_volgorde", "i_id", $_GET['id'], $_GET['up']);


 
    if ($_GET['allsites'] == '1')        
        $sSql = "SELECT $tblWeblinks.*, $tblWebsites.s_domein FROM $tblWeblinks, $tblWebsites WHERE $tblWeblinks.i_siteid = $tblWebsites.i_id ORDER BY i_volgorde ASC";        
    else
        $sSql = "SELECT $tblWeblinks.*, $tblWebsites.s_domein FROM $tblWeblinks, $tblWebsites WHERE $tblWeblinks.i_siteid = $tblWebsites.i_id AND $tblWeblinks.i_siteid = ".$_SESSION['iSelectedSiteID']." ORDER BY i_volgorde ASC";        

    //var_dump($sSql);
    $arrResult = mysqliToArray($sSql);
       
    
?>
<a href="checkbacklinks.php" target="_blank">check nu backlinks (backlinks ouder dan een week: niet zichtbaar)</a> <br>
<br>
<a href="materiaalindex.php?allsites=1">alle websites tonen</a>|<a href="materiaalindex.php">alleen huidige website tonen</a><br>
<br> 
<br>			
<TABLE border="0" cellpadding="2" cellspacing="0">
  <?php
    $iTeller = 0;
    foreach ($arrResult as $row) 
    {
        $iTeller++;
	?>
        <TR <?php echo getRowColor() ?>>         
            <TD> <?php echo $row["i_id"]; ?> </TD>      
            <TD> <b><?php echo $row["s_domein"]; ?></b> </TD>      
            <TD> <?php echo date('d-m-Y', $row["i_datechanged"]) ?> </TD>
            <TD> &nbsp;<?php echo date('d-m-Y', $row["i_datelastcheck"]) ?> </TD>
            <TD> <?php echo $row["s_link"]?> </TD>
            <TD> <a href="<?php echo $row["s_backlink"]?>" target="_blank"><?php echo $row["s_omschrijving"]?></a> </TD>
            <TD> 
                <?php 
                        if ($row["b_opwebsite"])
                                echo "op website";
                        else
                                echo "niet op website";
                ?> 
            </TD>
            <TD> 
                <?php 
                        if ($row["b_nofollow"])
                                echo "nofollow";
                        else
                                echo "&nbsp;";
                ?> 
            </TD>        
            <TD><a href="materiaalindex.php?changeorder=1&up=0&id=<?php echo $row["i_id"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>down.gif" width="14" height="15" border="0" alt="SCHUIF NAAR BOVEN : <?php echo $row["s_link"]?>"></a></TD>
            <TD><a href="materiaalindex.php?changeorder=1&up=1&id=<?php echo $row["i_id"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>up.gif" width="14" height="15" border="0" alt="SCHUIF NAAR BENEDEN : <?php echo $row["s_link"]?>"></a></TD>
            <TD><a href="materiaaldetail.php?id=<?php echo $row["i_id"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>update.gif" border="0" alt="WIJZIGEN : <?php echo $row["s_link"]?>"></a></TD>
            <TD> <a href="javascript:confirmDeleteRecord('<?php echo "materiaalindex.php?delete=1&id=".$row["i_id"]; ?>', '<?php echo $row["s_link"]?>')"><img src="<?php echo $www_sitemanageradminimages ?>del.gif" border="0" alt="VERWIJDEREN : <?php echo $row["s_link"]?>"></a> </TD>
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
<center><input type="button" name="toevoegen" onClick="location.href='materiaaldetail.php'" value="toevoegen"></center><br>
<br>

<center><input type="button" name="toevoegen" onClick="location.href='meerderelinkstoevoegen.php'" value="meerdere links toevoegen"></center>



<?php include_once($local_sitemanageradmin."openpage.php"); ?>
