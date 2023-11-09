<?php include_once("../../../config.php"); ?>
<?php include_once($local_sitemanageradmin."openpage.php"); ?>

<?php
    $iId		= ''; //is het een nieuw record
    $sBedrijfsnaam	= "";
    $sVoorletters	= "";
    $sAchternaam	= "";
    $sStraat        = '';
    $sPostcode      = '';
    $sPlaats        = '';
    $sEmailadres	= "";
    $sOpmerkingen 	= "";
    $bGeslacht      = '';
    $sTelefoon      = '';


    if (is_numeric($_GET['id']))
    {
        $sSQL = "SELECT * FROM $tblAdressen WHERE i_id = ".$_GET['id'];

        $arrResult = mysqliToArray($sSQL); 
        foreach($arrResult as $arrRow);
        {
            $iId            = $arrRow["i_id"];

            $sBedrijfsnaam	= $arrRow["s_bedrijfsnaam"];		
            $sVoorletters   = $arrRow["s_voorletters"];
            $sAchternaam	= $arrRow["s_achternaam"];
            $sEmailadres	= $arrRow["s_emailadres"];
            $sOpmerkingen 	= $arrRow["s_opmerkingen"];
            $sStraat        = $arrRow["s_straat"];
            $sPostcode      = $arrRow["s_postcode"];
            $sPlaats        = $arrRow["s_plaats"];  
            $bGeslacht      = $arrRow["b_geslacht"]; 
            $sTelefoon      = $arrRow["s_telefoonnr"]; 
            $bOpmailinglist = $arrRow["b_opmailinglist"]; 

        }
    }

?>


<form name="frmEdit" method=post action="materiaalsave.php?id=<?php echo $_GET['id'] ?>">
  <br>
  <table width="100%" height="100" border="0" cellpadding="0" cellspacing="0">
    <TR <?php echo getRowColor() ?>> 
      <td>bedrijfsnaam</td>
      <td> <input name="edtBedrijfsnaam" type="text" id="edtBedrijfsnaam" value="<?php echo $sBedrijfsnaam; ?>" size="100" maxlength="250"> 
      </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>geslacht</td>
      <td> <input name="cbxGeslacht" type="text" id="cbxGeslacht" value="<?php echo $bGeslacht; ?>" size="100" maxlength="250">0=man, 1=vrouw
      </td>
    </tr>       
    <TR <?php echo getRowColor() ?>> 
      <td>voorletters</td>
      <td> <input name="edtVoorletters" type="text" id="edtVoorletters" value="<?php echo $sVoorletters; ?>" size="100" maxlength="250"> 
      </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>achternaam</td>
      <td> <input name="edtAchternaam" type="text" id="edtAchternaam" value="<?php echo $sAchternaam; ?>" size="100" maxlength="250"> 
      </td>
    </tr>
    <TR <?php echo getRowColor() ?>> 
      <td>straat</td>
      <td> <input name="edtStraat" type="text" id="edtStraat" value="<?php echo $sStraat; ?>" size="100" maxlength="250"> 
      </td>
    </tr>   
    <TR <?php echo getRowColor() ?>> 
      <td>postcode</td>
      <td> <input name="edtPostcode" type="text" id="edtPostcode" value="<?php echo $sPostcode; ?>" size="7" maxlength="7"> 
      </td>
    </tr>     
    <TR <?php echo getRowColor() ?>> 
      <td>plaats</td>
      <td> <input name="edtPlaats" type="text" id="edtPlaats" value="<?php echo $sPlaats; ?>" size="100" maxlength="250"> 
      </td>
    </tr>     
    <TR <?php echo getRowColor() ?>> 
      <td>telefoon</td>
      <td> <input name="edtTelefoon" type="text" id="edtTelefoon" value="<?php echo $sTelefoon; ?>" size="100" maxlength="250"> 
      </td>
    </tr>     

    <TR <?php echo getRowColor() ?>> 
      <td>emailadres</td>
      <td> <input name="edtEmailadres" type="text" id="edtEmailadres" value="<?php echo $sEmailadres; ?>" size="100" maxlength="250"> 
      </td>
    </tr>    
    
    
    <TR <?php echo getRowColor() ?>> 
      <td>&nbsp;</td>
      <td> 
		<input name="opmailinglist" type="checkbox" id="opmailinglist" value="1" <?php echo boolToChecked($bOpmailinglist) ?>>	
        op mailinglist
	  </td>
    </tr>   
    
    <TR <?php echo getRowColor() ?>> 
      <td>opmerkingen<br>

        <br> 
      </td>
      <td><textarea name="edtOpmerkingen" cols="100" rows="10"><?php echo $sOpmerkingen; ?></textarea> 
      </td>
    </tr>
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

