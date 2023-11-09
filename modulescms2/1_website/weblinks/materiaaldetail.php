<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
        $iId			= ""; //is het een nieuw record
        $sLink                  = "http://www.";
        $sOmschrijving          = "";
        $bCheckbacklink         = true;
        $sBacklink              = '';
        $bOpWebsite             = true;
        $sEmailEigenaar         = '';
        $sPlaatjeGroot          = "";
        $sPlaatjeKlein          = "";
        $bEmailadresBevestigd   = false;
        $bNofollow              = false;
        $sOpmerkingen           = '';
        $sLinktNaarOnsDomein    = '';
        $sHrefTitleAttribute    = '';
        $iSiteID                = $_SESSION['iSelectedSiteID'];
	
	if (is_numeric($_GET['id']))
        {
            $sSQL = "SELECT * FROM $tblWeblinks WHERE i_id = ".$_GET['id'];

            $arrResult = mysqliToArray($sSQL); 
            foreach($arrResult as $row);
            {
                $iId			= $row["i_id"];
                $sLink                  = $row["s_link"];	           
                $bOpWebsite		= $row["b_opwebsite"];	           
                $sOmschrijving          = $row["s_omschrijving"];
                $bCheckbacklink         = $row["b_checkbacklink"];	           
                $sBacklink              = $row["s_backlink"];	
                $sEmailEigenaar         = $row["s_emaileigenaar"];	
                $sPlaatjeGroot          = $row["s_plaatjeurl"];
                $sPlaatjeKlein          = $row["s_plaatjeurlklein"];
                $bEmailadresBevestigd   = $row["b_emailadresbevestigd"];
                $bNofollow              = $row["b_nofollow"];
                $sOpmerkingen           = $row["s_opmerkingen"];
                $sLinktNaarOnsDomein    = $row["s_linktnaaronsdomein"];
                $sHrefTitleAttribute    = $row["s_hreftitleattribute"];      
                $iSiteID                = $row["i_siteid"];      
            }
	}
        else
            $_GET['id'] = '';
            

?>


<form name="frmEdit" method=post action="materiaalsave.php?id=<?php echo $_GET['id'] ?>">
  <br>
  <table width="100%" height="100" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td>op onze website</td>
      <td> 
        <select name="edtSiteID">
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
    </tr>        
    <TR <?php echo getRowColor() ?>> 
      <td>link</td>
      <td> <input name="edtLink" type="text" id="edtLink" value="<?php echo $sLink; ?>" size="100" maxlength="250"> </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>anchor text - tekst tussen de &lt;a&gt en &lt;/a&gt</td>
      <td> <input name="edtOmschrijving" type="text" id="edtOmschrijving" value="<?php echo $sOmschrijving; ?>" size="100" maxlength="250"> </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>title attribute (title= "...")</td>
      <td> <input name="edtHrefTitleAttribute" type="text" id="edtHrefTitleAttribute" value="<?php echo $sHrefTitleAttribute; ?>" size="100" maxlength="250">  </td>
    </tr>

    <TR <?php echo getRowColor() ?>> 
      <td>backlink</td>
      <td> <input name="edtBacklink" type="text" id="edtBacklink" value="<?php echo $sBacklink; ?>" size="100" maxlength="250"> </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>linkt naar ons domein</td>
      <td> <input name="edtLinktNaarOnsDomein" type="text" id="edtLinktNaarOnsDomein" value="<?php echo $sLinktNaarOnsDomein; ?>" size="100" maxlength="250"> welke van onze domeinen wordt gelinkt ?</td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="checkbacklink" type="checkbox" id="checkbacklink" value="1" <?php echo boolToChecked($bCheckbacklink) ?>>	
        Check backlink (bij geen teruglink, wordt record automatisch verwijderd)
	  </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>emailadres eigenaar website link</td>
      <td> <input name="edtEmailEigenaar" type="text" id="edtEmailEigenaar" value="<?php echo $sEmailEigenaar; ?>" size="100" maxlength="250"> </td>
    </tr>    
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="emailadresbevestigd" type="checkbox" id="emailadresbevestigd" value="1" <?php echo boolToChecked($bEmailadresBevestigd) ?>>	
        emailadres bevestigd
	  </td>
    </tr>  
   
    <TR <?php echo getRowColor() ?>> 
      <td>Afbeelding</td>
      <td> 
        <input type="hidden" name="tempplaatjeklein" value="">
   	    <input type="hidden" name="tempplaatjegroot" value="">
        <input type="hidden" name="plaatjeklein" value="<?php echo $sPlaatjeKlein ?>">
        <input type="hidden" name="plaatjegroot" value="<?php echo $sPlaatjeGroot ?>"> 
        <input type="hidden" name="plaatjedeleted" value=""> 
        <SCRIPT TYPE="text/javascript" LANGUAGE="JavaScript">
		<!--
			function delPic()
			{
				document.frmEdit.itempic.src = '<?php echo $www_sitemanageradminimages."transparantpixel.gif" ?>';
				document.frmEdit.tempplaatjeklein.value = '';
				document.frmEdit.tempplaatjegroot.value = '';
				document.frmEdit.plaatjedeleted.value = '1';	
			}
			
			function showPic() 
			{
				document.frmEdit.itempic.src = picWindow.picdetail.src;
				document.frmEdit.tempplaatjeklein.value = picWindow.frmUpload.tempplaatjeklein.value;
				document.frmEdit.tempplaatjegroot.value = picWindow.frmUpload.tempplaatjegroot.value;
				document.frmEdit.plaatjeklein.value = picWindow.frmUpload.plaatjeklein.value;
				document.frmEdit.plaatjegroot.value = picWindow.frmUpload.plaatjegroot.value;
				picWindow.close();
			}

			function openpicWindow() 
			{
				picWindow=open('<?php echo $www_sitemanageradmin ?>editpic.php?tempplaatjegroot=' + document.frmEdit.tempplaatjegroot.value + '&tempplaatjeklein=' + document.frmEdit.tempplaatjeklein.value + '&plaatjegroot=' + document.frmEdit.plaatjegroot.value + '&plaatjeklein=' + document.frmEdit.plaatjeklein.value + '&resizeimagequality=<?php echo $materiaalimagekleinquality;?>&resizewidth=<?php echo $materiaalimagekleinwidth;?>&resizeheight=<?php echo $materiaalimagekleinheight;?>','myname','resizable=yes,statusbar=yes, width=600,height=200');
				//mywindow.location.href = 'editpic.php';
				if (picWindow.opener == null)
					 picWindow.opener = self;
			}
		//-->
		</SCRIPT>
        <?php 
	  	if (is_file($local_sitemanagerimages.$sPlaatjeKlein))
		{
			?>
			<a href="javascript:newWindow();"><IMG src="<?php echo $www_sitemanagerimages.$sPlaatjeKlein ?>" border="0" name="itempic"></a><br>
			<?
		}
		else
		{
			?>
			<a href="javascript:newWindow();"><IMG src="<?php echo $www_sitemanageradminimages."transparantpixel.gif" ?>" border="0" name="itempic"></a><br>
			<?
		}		
		?>
                          
                          <input type="button" name="btnAddImg" value="afbeelding wijzigen/toevoegen" onClick="openpicWindow();"> 
						  <input type="button" name="btnDelImg" value="afbeelding verwijderen" onClick="delPic();"> 
                        </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="opwebsite" type="checkbox" id="opwebsite" value="1" <?php echo boolToChecked($bOpWebsite) ?>>	
        Weergeven op website
	  </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="chkNofollow" type="checkbox" id="chkNofollow" value="1" <?php echo boolToChecked($bNofollow) ?>>	
        Nofollow attribute
	  </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>opmerkingen</td>
      <td> 
          <textarea name="edtOpmerkingen" rows="3" cols="100"><?php echo $sOpmerkingen ?></textarea> 	
	  </td>
    </tr>        
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
        <input type="submit" name="opslaan" value="opslaan">
        <input type="button" name="btnAnnuleren" value="annuleren" onclick="window.location.href='materiaalindex.php';"></td>
    </tr>
  </table>
  <br>
</form>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

