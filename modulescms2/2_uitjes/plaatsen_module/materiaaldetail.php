<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
	$iId			= ""; //is het een nieuw record
	$iJaar			= date("Y"); 
	$iMaand			= date("m");
	$iDag 			= date("d");
	$iUur			= date("H"); 
	$iMinuut		= date("i");

	$sPlaats		= "";
        $sProvincie             = "";
	$sOmschrijving          = "";
	$sOmschrijvingAlt	= "";
	$bOpWebsite		= true;
	$sPlaatjeGroot          = "";
	$sPlaatjeKlein          = "";
        $sSourceImage           = "";
        $sPrettyUrlTitle        = '';
        $bIsOpEigenLocatie      = false;
        $iSiteID                = $_SESSION['iSelectedSiteID'];
        $sPlaatsenInDeBuurt     = '';
         
	
	if (is_numeric($_GET['id']))
        {
            $sSQL = "SELECT * FROM $tblPlaatsen WHERE i_id = ".$_GET['id'];

            $arrResult = mysqliToArray($sSQL);
            foreach ($arrResult as $row)
            {
                $iId                = $row["i_id"];

                $bOpWebsite         = $row["b_opwebsite"];		
                $sPlaats            = $row["s_plaats"];
                $sProvincie         = $row["s_provincie"];
                $sOmschrijving      = $row["s_omschrijving"];
                $sOmschrijvingAlt   = $row["s_omschrijving_alt"];
                $sPlaatjeGroot      = $row["s_plaatjeurl"];
                $sPlaatjeKlein      = $row["s_plaatjeurlklein"];
                $sSourceImage       = $row["s_sourceimage"];
                $sPrettyUrlTitle    = $row["s_prettyurltitle"];
                $bIsOpEigenLocatie  = $row["b_isopeigenlocatie"];
                $iSiteID            = $row["i_siteid"];
                $sPlaatsenInDeBuurt = $row["s_plaatsenindebuurt"];
            }
	}
?>


<form name="frmEdit" method=post action="materiaalsave.php?id=<?php echo $iId ?>">
  <br>
  <table width="100%" height="100" border="0" cellpadding="0" cellspacing="0">
    <tr> 
      <td>website</td>
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
      <td>plaats</td>
      <td> <input name="edtPlaats" type="text" id="edtPlaats" value="<?php echo $sPlaats; ?>" size="100" maxlength="250"> 
      </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>provincie</td>
      <td> <input name="edtProvincie" type="text" id="edtProvincie" value="<?php echo $sProvincie; ?>" size="100" maxlength="250"> 
      </td>
    </tr>    

    <TR <?php echo getRowColor() ?>> 
        <td>inhoud (html)<br>
          <input name="btnHTMLEditor" type="button" value="html editor" onclick="openHTMLEditor(edtBody);"> 
          <input name="btnHTMLEditorAlt" type="button" value="html editor alt" onclick="openHTMLEditor(edtBodyAlt);"> 
          <br> 
        </td>
        <td><textarea name="edtBody" cols="100" rows="10"><?php echo $sOmschrijving; ?></textarea><br>
            <b>ALTERNATIEF</b><br>
            <textarea name="edtBodyAlt" cols="100" rows="10"><?php echo $sOmschrijvingAlt; ?></textarea>
        </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
        <td>plaatsen in de buurt<br>
        1 plaats per regel</td>
      <td> <textarea name="edtPlaatsenInDeBuurt" cols="100" rows="10"><?php echo $sPlaatsenInDeBuurt; ?></textarea> 
      </td>           
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
		<input name="chkIsOpEigenLocatie" type="checkbox" id="chkIsOpEigenLocatie" value="1" <?php echo boolToChecked($bIsOpEigenLocatie) ?>>	
        op eigen locatie
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
				picWindow=open('<?php echo $www_sitemanageradmin ?>editpic.php?tempplaatjegroot=' + document.frmEdit.tempplaatjegroot.value + '&tempplaatjeklein=' + document.frmEdit.tempplaatjeklein.value + '&plaatjegroot=' + document.frmEdit.plaatjegroot.value + '&plaatjeklein=' + document.frmEdit.plaatjeklein.value + '&resizeimagequality=<?php echo $plaatsenimagekleinquality;?>&resizewidth=<?php echo $plaatsenimagekleinwidth;?>&resizeheight=<?php echo $plaatsenimagekleinheight;?>','myname','resizable=yes,statusbar=yes, width=800,height=600');
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
      <td>bron plaatje</td>
      <td> <input name="edtSourceImage" type="text" id="edtSourceImage" value="<?php echo $sSourceImage; ?>" size="100" maxlength="255"> 
      </td>
    </tr>    
    <TR <?php echo getRowColor() ?>> 
      <td>Uniek pretty-url</td>
      <td> <input name="edtPrettyUrlTitle" type="text" id="edtPrettyUrlTitle" value="<?php echo $sPrettyUrlTitle; ?>" size="25" maxlength="50">alleen cijfers, letters en 1x het min teken (-). niet invullen = auto generate
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