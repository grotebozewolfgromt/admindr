<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
	//defaults als het id niet voorkomt (kortom, het is een nieuw item);
	$iId			= ""; //is het een nieuw record
	$sOnderwerp		= "";
	$sDuur			= '2';
	$sVanafPrijs            = '30,00';
	$iVanafPrijsAantalPersonen = 100;
	$iVanafAantalPersonen   = 10;
        $sInclusief             = '';
        $sOptioneel             = '';
        $sOfferteTekst          = '';
        $bAanbieding            = 0; 
        $bBelangrijk            = 1; 
        $sYoutubeID             = '';      
        $sInhoud                = '';
        $sPlanning              = '';
        $sInclusiefAlt          = '';
        $sOptioneelAlt          = '';
        $sInhoudAlt             = '';
        $sPlanningAlt           = '';
        $sURLFotos              = '';
        $sPrijsbox1title        = '';
        $sPrijsbox1             = '';
        $sPrijsbox1Alt          = '';
        $sPrijsbox2title        = '';
        $sPrijsbox2             = '';
        $sPrijsbox2Alt          = '';
    
	
	if (is_numeric($_GET['id']))
        {
            $sSQL = "SELECT * FROM $tblUitjesCategories WHERE i_id = ".$_GET['id'];
		
            $arrResult = mysqliToArray($sSQL); 
            foreach($arrResult as $row);
            {
                $iId			= $row["i_id"];
                $bNieuw			= false; //is het een nieuw record
                $sOnderwerp             = $row["s_onderwerp"];			
                $sDuur                  = $row["s_duur"];
                $sVanafPrijs            = $row["s_vanafprijs"];
                $iVanafPrijsAantalPersonen = $row["i_vanafprijsaantalpersonen"];
                $iVanafAantalPersonen   = $row["i_vanafaantalpersonen"];
                $sInclusief             = $row["s_inclusief"]; 
                $sOptioneel             = $row["s_optioneel"]; 
                $sOfferteTekst          = $row["s_offertetekst"]; 
                $bAanbieding            = $row["b_aanbieding"]; 
                $bBelangrijk            = $row["b_belangrijk"]; 
                $sYoutubeID             = $row["s_youtubeid"];    
                $sInhoud                = $row["s_inhoud"];
                $sPlanning              = $row["s_planning"];

                $sInclusiefAlt          = $row["s_inclusief_alt"];
                $sOptioneelAlt          = $row["s_optioneel_alt"];
                $sInhoudAlt             = $row["s_inhoud_alt"];
                $sPlanningAlt           = $row["s_planning_alt"];
                $sURLFotos              = $row["s_urlfotos"];  

                $sPrijsbox1title        = $row["s_prijsbox1title"];
                $sPrijsbox1             = $row["s_prijsbox1"];
                $sPrijsbox1Alt          = $row["s_prijsbox1_alt"];
                $sPrijsbox2title        = $row["s_prijsbox2title"];
                $sPrijsbox2             = $row["s_prijsbox2"];
                $sPrijsbox2Alt          = $row["s_prijsbox2_alt"];                        
            }
	}

?>
<input type="hidden" name="itemid" value="<?php echo $iId ?>">

<form name="frmEdit" method=post action="catsave.php?id=<?php echo $iId ?>">
  <br>
  <table width="100%" height="100" border="0" cellpadding="0" cellspacing="0">
    <TR <?php echo getRowColor() ?>> 
        <td>categorienaam<br>intern, niet op website</td>
      <td> <input name="edtOnderwerp" type="text" id="edtOnderwerp" value="<?php echo $sOnderwerp; ?>" size="100" maxlength="250"> 
      </td>
    </tr>

    <TR <?php echo getRowColor() ?>> 
                        <td>prijsbox1 (html)<br>(zoals: "vanaf 10 personen 25 euro, vanaf 20 personen 19,50")
						</td>
                        <td>
                            <input name="edtPrijsbox1title" type="text" id="edtPrijsbox1title" value="<?php echo $sPrijsbox1title; ?>" size="25" maxlength="25">(titel)<br>
                            <textarea name="edtPrijsbox1" cols="100" rows="10"><?php echo $sPrijsbox1; ?></textarea> <br>
                            ALTERNATIEF<br>
                            <textarea name="edtPrijsbox1alt" cols="100" rows="10"><?php echo $sPrijsbox1Alt; ?></textarea> 
                        </td>
                        
    </tr>  
    <TR <?php echo getRowColor() ?>> 
                        <td>prijsbox2 (html)<br>(zoals: "vanaf 10 personen 25 euro, vanaf 20 personen 19,50")
						</td>
                        <td>
                            <input name="edtPrijsbox2title" type="text" id="edtPrijsbox2title" value="<?php echo $sPrijsbox2title; ?>" size="25" maxlength="25">(titel)<br>
                            <textarea name="edtPrijsbox2" cols="100" rows="10"><?php echo $sPrijsbox2; ?></textarea> <br>
                            ALTERNATIEF<br>
                            <textarea name="edtPrijsbox2alt" cols="100" rows="10"><?php echo $sPrijsbox2Alt; ?></textarea> 
                        </td>
                        
    </tr>      
    <TR <?php echo getRowColor() ?>> 
      <td>Vanafprijs</td>
      <td> <input name="edtVanafPrijs" type="text" id="edtVanafPrijs" value="<?php echo $sVanafPrijs; ?>" size="5" maxlength="10"> euro bij <input name="edtVanafPrijsAantalPersonen" type="text" id="edtVanafPrijsAantalPersonen" value="<?php echo $iVanafPrijsAantalPersonen; ?>" size="5" maxlength="10"> personen of meer (bijvoorbeeld: 25 euro bij 15 personen of meer) 
      </td>
    </tr>     
    <TR <?php echo getRowColor() ?>> 
        <td>&nbsp;</td>
        <td> 
	<input name="chkBelangrijk" type="checkbox" id="chkBelangrijk" value="1" <?php echo boolToChecked($bBelangrijk) ?>>belangrijk
        </td>
    </tr>     
    <TR <?php echo getRowColor() ?>> 
        <td>&nbsp;</td>
        <td> 
	<input name="chkAanbieding" type="checkbox" id="chkAanbieding" value="1" <?php echo boolToChecked($bAanbieding) ?>>aanbieding
        </td>
    </tr>       
    <TR <?php echo getRowColor() ?>> 
      <td>Youtube id</td>
      <td> <input name="edtYoutubeID" type="text" id="edtYoutubeID" value="<?php echo $sYoutubeID; ?>"  maxlength="255"> (alleen id, niet embedded code)
      </td>
    </tr>    
    <TR <?php echo getRowColor() ?>> 
      <td>url fotos</td>
      <td> <input name="edtURLFotos" type="text" id="edtURLFotos" value="<?php echo $sURLFotos; ?>"  maxlength="255"> (http://www. etc)
      </td>
    </tr>        
    <TR <?php echo getRowColor() ?>> 
        <td>inclusief (html)<br>(zoals: "een spel rad, wervelende presentator etc.")
        </td>
        <td><textarea name="edtInclusief" cols="100" rows="10"><?php echo $sInclusief; ?></textarea> <br>
            ALTERNATIEF inclusief:<BR>
            <textarea name="edtInclusiefAlt" cols="100" rows="10"><?php echo $sInclusiefAlt; ?></textarea> 
        </td>
    </tr>      
    <TR <?php echo getRowColor() ?>> 
        <td>optioneel (html)<br>(zoals: "geluidsinstallatie, beamerpresentatie etc.")
        </td>
        <td><textarea name="edtOptioneel" cols="100" rows="10"><?php echo $sOptioneel; ?></textarea> <br>
            ALTERNATIEF optioneel:<br>
            <textarea name="edtOptioneelAlt" cols="100" rows="10"><?php echo $sOptioneelAlt; ?></textarea> 
        </td>
    </tr>    
<!--    
    <TR <?php echo getRowColor() ?>> 
        <td>spelinhoud (html)<br> welke rondes etc?
	</td>
        <td><textarea name="edtInhoud" cols="100" rows="10"><?php echo $sInhoud; ?></textarea> <BR>
            ALTERNATIEF:<BR>
            <textarea name="edtInhoudAlt" cols="100" rows="10"><?php echo $sInhoudAlt; ?></textarea> 
        </td>
    </tr>      
    <TR <?php echo getRowColor() ?>> 
        <td>tijdsplanning (html)<br>
	</td>
        <td><textarea name="edtPlanning" cols="100" rows="10"><?php echo $sPlanning; ?></textarea> <br>
            ALTERNATIEF:<BR>
            <textarea name="edtPlanningAlt" cols="100" rows="10"><?php echo $sPlanningAlt; ?></textarea> 
        </td>
    </tr>        
    -->
    <TR <?php echo getRowColor() ?>> 
        <td>offerte tekst (html)
        </td>
        <td><textarea name="edtOfferteTekst" cols="100" rows="10"><?php echo $sOfferteTekst; ?></textarea> 
        </td>
    </tr>       
    <TR <?php echo getRowColor() ?>> 
      <td>Vanaf aantal personen</td>
      <td> <input name="edtVanafAantalPersonen" type="text" id="edtVanafAantalPersonen" value="<?php echo $iVanafAantalPersonen; ?>" size="5" maxlength="5"> personen of meer (bijvoorbeeld: "10" voor 10 personen) 
      </td>
    </tr>        


 
    <TR <?php echo getRowColor() ?>> 
      <td>Duur</td>
      <td> <input name="edtDuur" type="text" id="edtDuur" value="<?php echo $sDuur; ?>" size="5" maxlength="50">uur (bijvoorbeeld: "1,5" voor 1,5 uur) 
      </td>
    </tr>     
    <TR <?php echo getRowColor() ?>> 
      <td>tags<br>1 tag per regel</td>
      <td>
      	<textarea name="edtTags" cols="100" rows="10"><?php
                $sSQL = "SELECT * FROM $tblUitjesTags WHERE i_materialid = '$iId' ORDER BY s_tag";
                $arrResult = mysqliToArray($sSQL);
                foreach ($arrResult as $arrRow) 
                {
                    echo $arrRow['s_tag']."\n";
                }				
		?></textarea>		      	
      </td>
    </tr>     

    <TR <?php echo getRowColor() ?>> 
      <td>gelegenheden</td>
      <td>
        <?php
            $arrSoortenIDs = array();
            $sSQL = "SELECT * FROM $tblSoortenUitjes WHERE i_uitjescategoryid = '$iId'";
            $arrSoorten = mysqliToArray($sSQL);
            foreach($arrSoorten as $row)
            {
                $arrSoortenIDs[] = $row['i_soortenid'];
            }

            $sSQL = "SELECT * FROM $tblWebsites ORDER BY s_domein ";
            $arrSites = mysqliToArray($sSQL);
            foreach ($arrSites as $arrSite)
            {
            
                echo '<b>'.$arrSite['s_domein'].'</b><br>';

                $sSQL = "SELECT $tblSoorten.* FROM $tblSoorten WHERE i_siteid = ".$arrSite['i_id']." ORDER BY s_soort";
                $arrSoortenInner = mysqliToArray($sSQL);
                foreach($arrSoortenInner as $row)
                {
                  if (in_array($row['i_id'], $arrSoortenIDs))
                      $sCheckedText = 'checked';
                  else
                      $sCheckedText = '';
                  ?><input type="checkbox" name="chkSoort[]" value="<?php echo $row['i_id'] ?>" <?php echo $sCheckedText ?>><?php echo $row['s_soort']."<br>\n";
                     }				
                    ?>      
                    <?php
            
             }?>
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
        <input type="button" name="btnAnnuleren" value="annuleren" onclick="window.location.href='catindex.php';"></td>
    </tr>
  </table>
  <br>
</form>

		      	
    </tbody>
</table>


<?php include_once($local_sitemanageradmin."openpage.php"); ?>

