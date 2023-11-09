<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
        //defaults als het id niet voorkomt (kortom, het is een nieuw item);
        $iId                    = ""; //is het een nieuw record
        $sLink                  = "";
        $sOmschrijving          = "";
        $bCheckbacklink         = false;
        $sBacklink              = '';
        $bOpWebsite             = true;
        $sEmailEigenaar         = '';
        $sPlaatjeGroot          = "";
        $sPlaatjeKlein          = "";
        $bEmailadresBevestigd = false;
        $bNofollow              = false;
        $sOpmerkingen           = '';
        $sLinktNaarOnsDomein    = '';
        $sHrefTitleAttribute    = '';
        $iSiteID                = $_SESSION['iSelectedSiteID'];
	
//	if($id != "")
//	{
//		$sSQL = "SELECT * FROM $tblWeblinks WHERE i_id = $id";
//		
//		$result = mysql_query($sSQL) or die("<B>".mysql_error()."</B><BR>".$sSQL);
//		while ($row = @ mysql_fetch_array($result)) 
//		{
//			$iId			= $id;
//
//                        $sLink          = $row["s_link"];	           
//                        $bOpWebsite		= $row["b_opwebsite"];	           
//                                    $sOmschrijving	= $row["s_omschrijving"];
//                        $bCheckbacklink	= $row["b_checkbacklink"];	           
//                        $sBacklink      = $row["s_backlink"];	
//                        $sEmailEigenaar = $row["s_emaileigenaar"];	
//                        $sPlaatjeGroot	= $row["s_plaatjeurl"];
//                                    $sPlaatjeKlein 	= $row["s_plaatjeurlklein"];
//                        $bEmailadresBevestigd = $row["b_emailadresbevestigd"];
//
//                        $bNofollow = $row["b_nofollow"];
//                        $sOpmerkingen = $row["s_opmerkingen"];
//                        $sLinktNaarOnsDomein = $row["s_linktnaaronsdomein"];
//                        $sHrefTitleAttribute = $row["s_hreftitleattribute"];
//                        $iSiteID = $row["i_siteid"];    
//		}
//	}

?>
<input type="hidden" name="itemid" value="<?php echo $iId ?>">

<form name="frmEdit" method=post action="meerderelinkstoevoegensave.php">
  <br>
  <table width="100%" height="100" border="0" cellpadding="0" cellspacing="0">
      <tr>
          <td>onze site</td>          
          <td>link</td>
          <td>achortext</td>
          <td>backlink</td>
          <td>checkbacklink</td>
          <td>linkt ons domein</td>              
      </tr>
    <?php 
    for ($iTeller = 0; $iTeller < 50; $iTeller++)
    {
        ?>   
        <TR <?php echo getRowColor() ?>> 

            <td> 
              <select name="edtSiteID[]">
                  <?php 
                          $arrSites = mysqliToArray("SELECT * FROM $tblWebsites ORDER BY i_id");
                          foreach ($arrSites as $arrSite)
                          {
                                  $sSelected = '';
                                  if ($arrSite['i_id'] == $iSiteID)
                                          $sSelected = ' selected';
                                  echo '<option value="'.$arrSite['i_id'].'"'.$sSelected.'>'.$arrSite['s_domein'].'</option>';
                          }
                  ?>
              </select>
            </td>             
          <td><input name="edtLink[]" type="text" id="edtLink" value="" size="40" maxlength="250"> </td>
          <td><input name="edtOmschrijving[]" type="text" id="edtOmschrijving" value="" size="40" maxlength="250"></td>
          <td><input name="edtBacklink[]" type="text" id="edtBacklink" value="" size="25" maxlength="250"></td>
          <td><input name="checkbacklink[]" type="checkbox" id="checkbacklink" value="1" >check</td>
          <td><input name="edtLinktNaarOnsDomein[]" type="text" id="edtLinktNaarOnsDomein" value="" size="25" maxlength="250"></td>
        </tr>
        <?php
    }
    ?>
    <TR <?php echo getRowColor() ?>> 
      <td colspan="5">&nbsp;</td>
    </tr>        
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
        <input type="submit" name="opslaan" value="opslaan">
        <input type="button" name="btnAnnuleren" value="annuleren" onClick="location.href('materiaalindex.php');"></td>
    </tr>
  </table>
  <br>
</form>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

