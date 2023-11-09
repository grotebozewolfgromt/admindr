<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php
	//XML feed genereren
//	createrssfeedweblog($local_url."weblog-rss.xml");
	
	
	//statistieken bijwerken (de hits van statistieken tabel naar materiaal tabel kopieren)
// 	$sSQL = "SELECT DISTINCT(i_materialid), count(s_ip) AS i_countip FROM $tblMaterialStats GROUP BY i_materialid";
// 	$result = mysql_query($sSQL) or die("<B>".mysql_error()."</B><BR>".$sSQL);
// 	while ($row = @ mysql_fetch_array($result)) 
// 	{
// 		$arrVar = array("i_aantalkeerbekeken");
// 		$arrVal = array($row['i_countip']);
// 		changeRecord($arrVar, $arrVal, $tblMaterial, 'i_id', $row['i_materialid']);				
// 	}
	


	if ($_GET['delete'] == 1)
		deleteRecordDBInclImg($tblWeblog, "i_id", $_GET['id'], $local_sitemanagerimages);
        
	if ($_GET['changeorder'] == 1)
            changeRecordOrderDB($tblWeblog, "i_id", $_GET['id'], $local_sitemanagerimages, $_GET['up'] );
        
     


	$sSql = "SELECT $tblWeblog.* FROM $tblWeblog WHERE $tblWeblog.i_siteid = ".$_SESSION['iSelectedSiteID']." ORDER BY i_datum ASC";

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
                <TD> <?php echo date("d-m-Y", $row["i_datum"])?> </TD>
                <TD> <?php echo $row["s_onderwerp"]?> </TD>
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
                        if ($row["b_belangrijk"])
                                echo "<b>belangrijk</b>";
                        else
                                echo "&nbsp;";
                ?> 
                </TD>	
                <TD> 
                <?php 
                        if ($row["b_eol"])
                                echo "<b>EOL</b>";
                        else
                                echo "&nbsp;";
                ?> 
                </TD>   
                <TD> 
                <?php 
                        if ($row["b_autoreplandate"])
                                echo "auto replan";
                        else
                                echo "&nbsp;";
                ?> 
                </TD>                  
                <TD><a href="previewblog.php?id=<?php echo $row["i_id"] ?>">preview</a></TD>
                
                <TD><a href="materiaaldetail.php?id=<?php echo $row["i_id"]; ?>"><img src="<?php echo $www_sitemanageradminimages ?>update.gif" border="0" alt="WIJZIGEN : <?echo $row["s_onderwerp"]?>"></a></TD>
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

<?php include_once($local_sitemanageradmin."openpage.php"); ?>
