<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
	$iId			= ""; //is het een nieuw record

	$sOnderwerp		= "";
	$sTekst			= "";
	$bOpWebsite		= true;
	$sPlaatjeGroot          = "";
	$sPlaatjeKlein          = "";
	$bNieuw			= true;
	$sPrettyUrlTitle        = '';
	$iSiteID		= $_SESSION['iSelectedSiteID'];
        $iCategoryID            = 0; //doenst exist
        $sTekstAlt              = '';
        $sHTMLTitle             = '';
        $sHTMLTitleAlt          = '';
        $sHTMLDescription       = '';
        $sHTMLDescriptionAlt    = '';
        $sOnderwerpShort        = '';
        $sFotoboekDir           = '';
        $sRondes                = '';

        
	
	if (is_numeric($_GET['id']))
        {
            $sSQL = "SELECT * FROM $tblUitjes WHERE i_id = ".$_GET['id'];

            $arrResult = mysqliToArray($sSQL); 
            foreach($arrResult as $row);
            {
                $iId			= $row["i_id"];	

                $bOpWebsite		= $row["b_opwebsite"];	
                $sPlaatjeGroot          = $row["s_plaatjeurl"];
                $sPlaatjeKlein          = $row["s_plaatjeurlklein"];
                $sOnderwerp             = $row["s_onderwerp"];
                $sTekst 		= $row["s_tekst"];
                $bInclusiefDiner        = $row["i_inclusiefdiner"];
                $sPrettyUrlTitle        = $row["s_prettyurltitle"];
                $iSiteID                = $row["i_siteid"]; 
                $iCategoryID            = $row["i_categoryid"];   
                $sTekstAlt              = $row["s_tekst_alt"];   
                $sHTMLTitle             = $row["s_htmltitle"];   
                $sHTMLTitleAlt          = $row["s_htmltitle_alt"];   
                $sHTMLDescription       = $row["s_htmldescription"];   
                $sHTMLDescriptionAlt    = $row["s_htmldescription_alt"];   
                $sOnderwerpShort        = $row["s_onderwerp_short"];                        
                $sFotoboekDir           = $row["s_fotoboekdir"];        
                $sRondes                = $row["s_rondes"];        
            }
	}
        else
            $_GET['id'] = '';//voor de zkerheid

?>


<form name="frmEdit" method=post action="materiaalsave.php?id=<?php echo $_GET['id'] ?>">
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
       <tr> 
        <td>categorie</td>
        <td> 
            <select name="edtUitjesCategoryID">
                    <?php 
                            $arrCats = mysqliToArray("SELECT * FROM $tblUitjesCategories ORDER BY s_onderwerp");
                            foreach ($arrCats as $arrCat)
                            {
                                    $sSelected = '';
                                    if ($arrCat['i_id'] == $iCategoryID)
                                            $sSelected = ' selected';
                                    echo '<option value="'.$arrCat['i_id'].'"'.$sSelected.'>'.$arrCat['s_onderwerp'].'</option>';
                            }
                    ?>
            </select>
        </td>
      </tr>        
    <TR <?php echo getRowColor() ?>> 
      <td>naam</td>
        <td> <input name="edtOnderwerp" type="text" id="edtOnderwerp" value="<?php echo $sOnderwerp; ?>" size="100" maxlength="250"> <br>
            KORT (zodat t in boxjes past)<br>
            <input name="edtOnderwerpShort" type="text" id="edtOnderwerp" value="<?php echo $sOnderwerpShort; ?>" size="100" maxlength="25"> 
      </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>html title</td>
        <td> <input name="edtHTMLTitle" type="text" id="edtHTMLTitle" value="<?php echo $sHTMLTitle; ?>" size="100" maxlength="250"> <br>
            <input name="edtHTMLTitleAlt" type="text" id="edtHTMLTitleAlt" value="<?php echo $sHTMLTitleAlt ; ?>" size="100" maxlength="250"> (alternatief)
      </td>
    </tr>   
    <TR <?php echo getRowColor() ?>> 
      <td>html description</td>
        <td> <input name="edtHTMLDescription" type="text" id="edtHTMLDescription" value="<?php echo $sHTMLDescription; ?>" size="100" maxlength="250"> <br>
            <input name="edtHTMLDescriptionAlt" type="text" id="edtHTMLDescriptionAlt" value="<?php echo $sHTMLDescriptionAlt ; ?>" size="100" maxlength="250"> (alternatief)
      </td>
    </tr>      
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="chkInclusiefDiner" type="checkbox" id="chkInclusiefDiner" value="1" <?php echo boolToChecked($bInclusiefDiner) ?>>	
        inclusief diner 
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
                        <td>omschrijving (html)<br>
                          <input name="btnHTMLEditor" type="button" value="html editor" onclick="openHTMLEditor(edtBody);"> 
                          <br> 
						</td>
                        <td><textarea name="edtBody" cols="100" rows="10"><?php echo $sTekst; ?></textarea> 
                        </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
                        <td>omschrijving ALTERNATIEF<br>
                          <input name="btnHTMLEditorAlt" type="button" value="html editor" onclick="openHTMLEditor(edtBodyAlt);"> 
                          <br> 
						</td>
                        <td><textarea name="edtBodyAlt" cols="100" rows="10"><?php echo $sTekstAlt; ?></textarea> 
                        </td>
    </tr>  
    <TR <?php echo getRowColor() ?>> 
                        <td>Rondes<br>
                          <input name="btnHTMLEditorAlt" type="button" value="html editor" onclick="openHTMLEditor(edtRondes);"> 
                          <br> 
						</td>
                        <td><textarea name="edtRondes" cols="100" rows="10"><?php echo $sRondes; ?></textarea> 
                        </td>
    </tr>     
    <TR <?php echo getRowColor() ?>> 
      <td>fotoboek directory</td>
      <td>   
          
        <select name="cbxFotoboekDir">
            <option value="">[geen]</option>
            <?php
               $sFotoBaseDir = $local_url.'fotos';
               $arrFolders = scandir($sFotoBaseDir);            
               sort($arrFolders);
               //var_dump($arrFolders);
               foreach($arrFolders as $sFolder)
               {
                   if (is_dir($sFotoBaseDir.'/'.$sFolder))
                   {
                       if (($sFolder != '.') && ($sFolder != '..'))
                            echo '<option value="'.$sFolder.'" '.boolToSelected ($sFotoboekDir == $sFolder).'>'.$sFolder.'</option>';             
                   }
               }
               ?>
        </select><?php 
        if (is_dir($sFotoBaseDir.'/'.$sFotoboekDir))
            echo 'opgeslagen directory is aanwezig';
        else
            echo '<b>opgeslagen directory BESTAAT NIET! Kies een ander!</b>';
        ?>
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
				picWindow=open('<?php echo $www_sitemanageradmin ?>editpic.php?tempplaatjegroot=' + document.frmEdit.tempplaatjegroot.value + '&tempplaatjeklein=' + document.frmEdit.tempplaatjeklein.value + '&plaatjegroot=' + document.frmEdit.plaatjegroot.value + '&plaatjeklein=' + document.frmEdit.plaatjeklein.value + '&resizeimagequality=<?php echo $uitjesimagekleinquality;?>&resizewidth=<?php echo $uitjesimagekleinwidth;?>&resizeheight=<?php echo $uitjesimagekleinheight;?>','myname','resizable=yes,statusbar=yes, width=800,height=600');
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
      <td>Uniek pretty-url</td>
      <td> <input name="edtPrettyUrlTitle" type="text" id="edtPrettyUrlTitle" value="<?php echo $sPrettyUrlTitle; ?>" size="25" maxlength="50">.html (alleen cijfers, letters en 1x het min teken (-). niet invullen = auto generate)
      </td>
    </tr>          

    
  
    
    <?php 
    
            $arrPlaatsen = mysqliToArray("SELECT * FROM $tblPlaatsen WHERE i_siteid = ".$_SESSION['iSelectedSiteID']." ORDER BY s_plaats");
            $iCountPlaatsen = 0;
            foreach ($arrPlaatsen as $arrPlaats)
            {                
                
                $arrPlaatsUitje = array();
                $arrPlaatsenUitjes = mysqliToArray("SELECT * FROM $tblPlaatsenUitjes WHERE i_plaatsenid = '".$arrPlaats['i_id']."' AND i_uitjesid = '".$_GET['id']."'");               
                
                foreach ($arrPlaatsenUitjes as $arrRowPlaatsUitje) //het mag er eigenlijk maar 1 zijn
                {               
                    $arrPlaatsUitje = $arrRowPlaatsUitje;
                    //var_dump($arrPlaatsUitje);
                }
                
                if (!$arrPlaatsUitje)//defaults als nieuwe plaats
                {
                    $arrPlaatsUitje['b_opwebsite'] = 0;
                    //$arrPlaatsUitje['s_tekst'] = $sOnderwerp.' in '.$arrPlaats['s_plaats'].'.<br>'.$sTekst; //default tekst overnemen van t uitje zelf
                }
                ?>
                <tr <?php echo getRowColor() ?>> 
                    <td>
                      <?php echo $arrPlaats['s_plaats'] ?> tekst (html)<br>
                      <?php 
                        if (is_file($local_sitemanagerimages.$arrPlaatsUitje['s_plaatjeklein']))
                            echo '<img src="'.$www_sitemanagerimages.$arrPlaatsUitje['s_plaatjeklein'].'">';
                      ?>
                    </td>
                    <td>
                        <input type="checkbox" name="chkPlaatsOpwebsite<?php echo $iCountPlaatsen ?>" value="1" <?php echo boolToChecked($arrPlaatsUitje['b_opwebsite'])?>><?php echo $row['s_plaats']; ?><b><?php echo $arrPlaats['s_plaats'] ?></b> weergeven op website (tekst hieronder alleen opgeslagen wanneer aangevinkt)<br>
                        <textarea name="edtPlaatsTekst[]" cols="100" rows="10"><?php echo $arrPlaatsUitje['s_tekst']; ?></textarea> 
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

		      	
    </tbody>
</table>


<?php include_once($local_sitemanageradmin."openpage.php"); ?>

