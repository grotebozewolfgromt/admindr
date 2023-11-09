<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
	$iId			= ''; //is het een nieuw record
	$iJaar			= date("Y"); 
	$iMaand			= date("m");
	$iDag 			= date("d");
	$iUur			= date("H"); 
	$iMinuut		= date("i");

	$sSoort                 = "";
	$sSoortShort		= "";
	$sOmschrijving          = "";
        $sOmschrijvingAlt	= "";
	$bOpWebsite		= true;
	$sPlaatjeGroot          = "";
	$sPlaatjeKlein          = "";
        $sSourceImage           = "";
        $sPrettyUrlTitle        = '';
        $bBelangrijk            = false;
        $iSiteID                = $_SESSION['iSelectedSiteID'];
	
        
	if (is_numeric($_GET['id']))
	{
            $sSQL = "SELECT * FROM $tblSoorten WHERE i_id = ".$_GET['id'];

            $arrResult = mysqliToArray($sSQL);
            foreach ($arrResult as $row)
            {
                $iId = $row["i_id"];
                $bOpWebsite		= $row["b_opwebsite"];		
                $sSoort        = $row["s_soort"];
                $sSoortShort        = $row["s_soort_short"];
                $sOmschrijving	= $row["s_omschrijving"];
                $sOmschrijvingAlt	= $row["s_omschrijving_alt"];
                $sPlaatjeGroot	= $row["s_plaatjeurl"];
                $sPlaatjeKlein 	= $row["s_plaatjeurlklein"];
                $sSourceImage   = $row["s_sourceimage"];
                $sPrettyUrlTitle = $row["s_prettyurltitle"];
                $bBelangrijk =  $row["b_belangrijk"];
                $iSiteID =  $row["i_siteid"];
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
      <td>gelegenheid</td>
        <td> <input name="edtSoort" type="text" id="edtSoort" value="<?php echo $sSoort; ?>" size="100" maxlength="250">  (bijv. vrijgezellenfeest, familiedag)<br>
            KORT (zodat t in de boxjes past)<br>
            <input name="edtSoortShort" type="text" id="edtSoortShort" value="<?php echo $sSoortShort; ?>" size="100" maxlength="100">
      </td>
    </tr>

    <TR <?php echo getRowColor() ?>> 
        <td>inhoud (html)<br>
          <input name="btnHTMLEditor" type="button" value="html editor" onclick="openHTMLEditor(edtBody);"> 
          <br> 
        </td>
        <td><textarea name="edtBody" cols="100" rows="10"><?php echo $sOmschrijving; ?></textarea> 
        </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
        <td>inhoud (html) ALTERNATIEF<br>
          <input name="btnHTMLEditor" type="button" value="html editor" onclick="openHTMLEditor(edtBodyAlt);"> 
          <br> 
         </td>
        <td><textarea name="edtBodyAlt" cols="100" rows="10"><?php echo $sOmschrijvingAlt; ?></textarea> 
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
		<input name="belangrijk" type="checkbox" id="belangrijk" value="1" <?php echo boolToChecked($bBelangrijk) ?>>	
        belangrijk
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
				picWindow=open('<?php echo $www_sitemanageradmin ?>editpic.php?tempplaatjegroot=' + document.frmEdit.tempplaatjegroot.value + '&tempplaatjeklein=' + document.frmEdit.tempplaatjeklein.value + '&plaatjegroot=' + document.frmEdit.plaatjegroot.value + '&plaatjeklein=' + document.frmEdit.plaatjeklein.value + '&resizeimagequality=<?php echo $gelegenheidimagekleinquality;?>&resizewidth=<?php echo $gelegenheidimagekleinwidth;?>&resizeheight=<?php echo $gelegenheidimagekleinheight;?>','myname','resizable=yes,statusbar=yes, width=800,height=600');
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
      <td>tags<br>1 tag per regel</td>
      <td>
      	<textarea name="edtTags" cols="100" rows="10"><?php         
            $sSQL = "SELECT * FROM $tblSoortenTags WHERE i_soortenid = '$iId' ORDER BY s_tag";
            $arrResult = mysqliToArray($sSQL);
            foreach($arrResult as $arrRow)
            {
                echo $arrRow['s_tag']."\n";
            }				
	?></textarea>		      	
      </td>
    </tr>         
    <?php 
    
            $arrPlaatsen = mysqliToArray("SELECT * FROM $tblPlaatsen WHERE i_siteid = ".$_SESSION['iSelectedSiteID']." ORDER BY s_plaats");
//            var_dump($arrPlaatsen);
            $iCountPlaatsen = 0;
            foreach ($arrPlaatsen as $arrPlaats)
            {                
                
                $arrPlaatsSoort = array();
                $arrPlaatsenSoorten = mysqliToArray("SELECT * FROM $tblPlaatsenSoorten WHERE i_plaatsenid = '".$arrPlaats['i_id']."' AND i_soortid = '$iId'");               
                
                foreach ($arrPlaatsenSoorten as $arrRowPlaatsUitje) //het mag er eigenlijk maar 1 zijn
                {               
                    $arrPlaatsSoort = $arrRowPlaatsUitje;
                    //var_dump($arrPlaatsUitje);
                }
                
                if (!$arrPlaatsSoort)//defaults als nieuwe plaats
                {
                    $arrPlaatsSoort['b_opwebsite'] = 0;
                    $arrPlaatsSoort['s_tekst'] = $sSoort.' in '.$arrPlaats['s_plaats'].'.<br>'.$sTekst; //default tekst overnemen van t uitje zelf
                }
                ?>
                <tr <?php echo getRowColor() ?>> 
                    <td>
                      <?php echo $arrPlaats['s_plaats'] ?> tekst (html)<br>
                      <?php 
                        if (is_file($local_sitemanagerimages.$arrPlaats['s_plaatjeurlklein']))
                            echo '<img src="'.$www_sitemanagerimages.$arrPlaats['s_plaatjeurlklein'].'">';
                      ?>
                    </td>
                    <td>
                        <!--<input type="checkbox" name="chkPlaatsOpwebsite<?php echo $iCountPlaatsen ?>" value="1" <?php echo boolToChecked($arrPlaatsSoort['b_opwebsite'])?>><?php echo $row['s_plaats']; ?>--> <b><?php echo $arrPlaats['s_plaats'] ?></b> <br>
                        <textarea name="edtPlaatsTekst[]" cols="100" rows="10"><?php echo $arrPlaatsSoort['s_tekst']; ?></textarea> 
                        <input type="hidden" name="edtPlaatsID[]" value="<?php echo $arrPlaats['i_id'] ?>">
                    </td>
                </tr>     
                <?php
                ++$iCountPlaatsen;
            }
    ?>  
    <input type="hidden" name="edtTotalCountPlaatsen" value="<?php echo $iCountPlaatsen ?>">
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

