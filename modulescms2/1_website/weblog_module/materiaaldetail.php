<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>
<?php
/*
	$objTree = new TTree();
    $objTree->setName('[HOOFDCATEGORIE]');
		
	$sSQL = "SELECT * FROM $tblCategories ORDER BY i_order";
	$result = mysql_query($sSQL) or die("<B>".mysql_error()."</B><BR>".$sSQL);
	while ($row = @ mysql_fetch_array($result)) 
	{
		$objChildNode = new TTree();
		$objChildNode->setName($row["s_name"]);
		$objChildNode->setID($row["i_id"]);
		$objChildNode->setAHREF("catdetail.php?id=".$row["i_id"]);		
		$objTree->addChildNodeByParentNodeID($objChildNode, $row["i_parentnodeid"]);
	}	
	*/
?>
<?php
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
	$iId			= ""; //is het een nieuw record
	$iJaar			= date("Y"); 
	$iMaand			= date("m");
	$iDag 			= date("d");
	$iUur			= date("H"); 
	$iMinuut		= date("i");
        
	$bAutoReplanDate        = true;
	$sOnderwerp		= "";
	$sTekst			= "";
	$bOpWebsite		= true;
	$sPlaatjeGroot          = "";
	$sPlaatjeKlein          = "";
	$iAantalKeerBekeken     = 0;
	$bBelangrijk            = true;
	$sAchtergrond           = '';
	$sSourceImage           = '';
	$sYoutubeUrl            = '';
	$sTranscription         = '';
        $sPrettyUrlTitle        = '';
        $iSiteID		= $_SESSION['iSelectedSiteID'];
        $bEOL                   = false;
        $iEOLDate               = 0;
	
	if (is_numeric($_GET['id']))
        {
            $sSQL = "SELECT * FROM $tblWeblog WHERE i_id = ".$_GET['id'];

            $arrResult = mysqliToArray($sSQL); 
            foreach($arrResult as $row); 
            {
                $iId			= $row["i_id"];
                $iJaar			= date("Y", $row["i_datum"]);
                $iMaand			= date("m", $row["i_datum"]);
                $iDag			= date("d", $row["i_datum"]);
                $iUur			= date("H", $row["i_datum"]);
                $iMinuut		= date("i", $row["i_datum"]);
                $bAutoReplanDate        = $row["b_autoreplandate"];		
                $bOpWebsite		= $row["b_opwebsite"];		
                $sOnderwerp             = $row["s_onderwerp"];
                $sTekst 		= $row["s_tekst"];
                $sPlaatjeGroot          = $row["s_plaatjeurl"];
                $sPlaatjeKlein          = $row["s_plaatjeurlklein"];
                $iParentNodeID          = $row["i_parentnodeid"];
                $iAantalKeerBekeken	= $row["i_aantalkeerbekeken"];	
                $bBelangrijk            = $row["b_belangrijk"];	
                $sSourceImage           = $row["s_sourceimage"];	
                $sYoutubeUrl            = $row["s_youtubeurl"];	
                $sTranscription 	= $row["s_transcription"];
                $sPrettyUrlTitle        = $row["s_prettyurltitle"]; 
                $iSiteID                = $row["i_siteid"]; 
                $bEOL                   = $row["b_eol"]; 
                $iEOLDate               = $row["i_eoldate"]; 
            }
	}
        else
            $_GET['id'] = '';

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
      <td>onderwerp</td>
      <td> <input name="edtOnderwerp" type="text" id="edtOnderwerp" value="<?php echo $sOnderwerp; ?>" size="100" maxlength="250"> 
      </td>
    </tr>

    <TR <?php echo getRowColor() ?>> 
      <td>youtube id</td>
      <td>https://www.youtube.com/embed/<input name="edtYoutubeUrl" type="text" id="edtYoutubeUrl" value="<?php echo $sYoutubeUrl; ?>" size="100" maxlength="250"> 
      </td>
    </tr>
    
    <TR <?php echo getRowColor() ?>> 
      <td>Uniek pretty-url</td>
      <td> <input name="edtPrettyUrlTitle" type="text" id="edtPrettyUrlTitle" value="<?php echo $sPrettyUrlTitle; ?>" size="100" maxlength="255"> alleen cijfers, letters en 1x het min teken (-). niet invullen = auto generate
      </td>
    </tr>     
    
    <TR <?php echo getRowColor() ?>> 
                        <td>inhoud (html)<br>
                          <input name="btnHTMLEditor" type="button" value="html editor" onclick="openHTMLEditor(edtBody);"> 
                          <br> 
						</td>
                        <td><textarea name="edtBody" cols="100" rows="10"><?php echo $sTekst; ?></textarea> 
                        </td>
    </tr>   
    <TR <?php echo getRowColor() ?>> 
                        <td>transcription (non html)<br>
                                                    
						</td>
                        <td><textarea name="edtTranscription" cols="100" rows="10"><?php echo $sTranscription; ?></textarea> 
                        </td>
    </tr>   
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="opwebsite" type="checkbox" id="opwebsite" value="1" <?php echo boolToChecked($bOpWebsite) ?>>	
        Weergeven op website (direct van website verwijderd)
	  </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="chkEol" type="checkbox" id="chkEol" value="1" <?php echo boolToChecked($bEOL) ?>>	
                EOL (End Of Life).<br>Item gaat verdwijnen, maar is nog wel actief ivm cache files en google.<br>
                Item verschijnt niet meer in nieuw aangemaakte cache files/database requests.<br>
                Raadplegen van dit item geeft een 404 header error, maar is nog wel zichtbaar op site.<br>
                <?php 
                    if ($bEOL)
                        echo 'EOL datum: '. date('d-m-Y H:i:s',$iEOLDate);
                ?>
	  </td>
    </tr>        
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="chkBelangrijk" type="checkbox" id="chkBelangrijk" value="1" <?php echo boolToChecked($bBelangrijk) ?>>	
        Belangrijk  (belangrijke items staan op de blog, onbelangrijke items staan in de sitemap en footer voor google)
	  </td>
    </tr>    
    <TR <?php echo getRowColor() ?>> 
      <td>Datum</td>
      <td>
        <?php showDatumSelectBoxen($iDag, $iMaand, $iJaar); ?><br>
		<?php showTijdSelectBoxen($iUur, $iMinuut); ?>
      </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="chkAutoReplanDate" type="checkbox" id="chkAutoReplanDate" value="1" <?php echo boolToChecked($bAutoReplanDate) ?>>	
        automatisch opnieuw inplannen
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
				picWindow=open('<?php echo $www_sitemanageradmin ?>editpic.php?tempplaatjegroot=' + document.frmEdit.tempplaatjegroot.value + '&tempplaatjeklein=' + document.frmEdit.tempplaatjeklein.value + '&plaatjegroot=' + document.frmEdit.plaatjegroot.value + '&plaatjeklein=' + document.frmEdit.plaatjeklein.value + '&resizeimagequality=<?echo $weblogimagekleinquality;?>&resizewidth=<?echo $weblogimagekleinwidth;?>&resizeheight=<?echo $weblogimagekleinheight;?>','myname','resizable=yes,statusbar=yes, width=600,height=200');
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
      <td>tags<br>1 tag per regel</td>
      <td>
      	<textarea name="edtTags" cols="100" rows="10"><?php 
			$sSQL = "SELECT * FROM $tblWeblogTags WHERE i_weblogid = '".$_GET['id'].".' ORDER BY s_tag";
			$arrResult = mysqliToArray($sSQL);
                        foreach($arrResult as $arrRow)
			{
                            echo $arrRow['s_tag']."\n";
			}				
		?></textarea>		      	
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

